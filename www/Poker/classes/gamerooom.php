<?php

class GameRoom{
private $db;	


function __construct(){
	$this->db=new Db();
}

function check_game_started($room_id){
	$sql="SELECT game_started FROM room WHERE id=$room_id";
	$result=$this->db->query($sql,SQLITE3_ASSOC);
	return $result['game_started'];
}
function find_available_game_room(){
	$sql="SELECT id FROM  room WHERE status=1 LIMIT 1";
	$result=$this->db->query($sql);
	
	if($result){
		return $result[0];
	}
	else{
		return false;
	}
}
function moveElement(&$array, $a, $b) {
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
}
function get_list_players_room($room_id,$myid){
$sql="SELECT * FROM joined_players WHERE room_id=$room_id";

$result=$this->db->query($sql,SQLITE3_ASSOC);
if(count($result)>0){
foreach($result as $key=>$value){

	if($value['id']==$myid){
 $this->moveElement($result,$key,3);
	}
}
}
return $result;
}
function find_joined_count_to_room($roomid){
	
	$sql="SELECT count(id) AS c
FROM joined_players WHERE room_id=$roomid ";
$result=$this->db->query($sql,SQLITE3_ASSOC);

if($result){
return $result['c'];
}
else{
	return 0;
}
}
function add_player_to_room($player_ip,$name,$room_id){
	$sql="INSERT INTO joined_players (player_ip,name,room_id) VALUES('{$player_ip}','{$name}',$room_id)";
	if($this->db->execute($sql)){
		return $this->db->last_id();
	}
	else{
		return false;
	}
}
function close_room($room_id){
	$sql="UPDATE room SET status=0 WHERE id=$room_id";
	if($this->db->execute($sql)){
		return true;
	}
	else{
		return false;
	}
}

function create_a_room(){
	$sql="INSERT INTO room (status) VALUES(1)";
		if($this->db->execute($sql)){
		return $this->db->last_id();
	}
	else{
		return false;
	}
}
function start_poker_game($room_id){
	$sql="UPDATE room set game_started=1 WHERE id=$room_id";
	return $this->db->execute($sql);
}


function join_to_room($player_ip,$name){
$room_id=$this->find_available_game_room();	
if($room_id){
$joined_count=$this->find_joined_count_to_room($room_id);	
if($joined_count>5){
	echo "Room Is Full";
	$this->close_room($room_id);
}
else{
$add_player=$this->add_player_to_room($player_ip,$name,$room_id);
$joined_count=$this->find_joined_count_to_room($room_id);
if($joined_count==6){
$this->start_poker_game($room_id);	
}	
if($add_player){
	return array("player_id"=>$add_player,"room_id"=>$room_id);
}
else{
	echo "Error Occured While Adding Player to Room";
}
}	
	
	
	
	
	
}
else{
$room_id=$this->create_a_room();
if($room_id){
$insert_player=$this->add_player_to_room($player_ip,$name,$room_id);
if($insert_player){
return array("player_id"=>$insert_player,"room_id"=>$room_id);
}
else{
	echo "Error Occured Adding Player to Romm";
}
}
else{
	echo "error Occured Creating a New Room";
}	
	
}
}
}










?>