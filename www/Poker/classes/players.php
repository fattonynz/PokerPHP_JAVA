<?php

class Players{
	public $room_id;
	public $player_array=array();
	
	
	
	
	
function set_room_id($room_id){
	$this->room_id=$room_id;
}


function add_player($player_id,$data){
$this->player_array[$player_id]=$data;
}	
	
}


?>