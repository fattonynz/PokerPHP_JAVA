<?php
define("MAX_PLAYERS",6);


require_once("classes/datamanager.php");
require_once("classes/deck.php");
require_once("classes/sqlite.php");
require_once("classes/result.php");
require_once("classes/gamerooom.php");
require_once("classes/players.php");
require_once("classes/pokerhand.php");
require_once("classes/pokerround.php");
require_once("classes/filehandler.php");
require_once("classes/updatesmanager.php");
$gameroom=new GameRoom();
$db=new db();
$datamanager=new DataManager();
$updatesmanager=new UpdatesManager();
$result=new Result();
$players=new Players();
$round=new PokerRound();
$hand=new PokerHand();
$deck=new Deck();

//$game_id=time()."_newgame";
//$deck->shuffle_cards();

//var_dump($deck->get_two_cards());


//var_dump($deck->get_deck());


if($datamanager->post_check_exists(array("action"))){
$action=$datamanager->get_var("action");
switch($action){

case "CONNECT":
$name=$datamanager->get_var("player_name");
connect($db,$gameroom,$result,$name);
break;

case "PLAYER_LIST":
$room_id=$datamanager->get_var("room_id");
$my_id=$datamanager->get_var("player_id");
get_player_list($room_id,$gameroom,$my_id,$players,$deck,$hand,$round,$updatesmanager);
break;
}	
	
	
}

function connect($db,$gameroom,$result,$name){
if($name){}else{$name="guest";}
$ip=$_SERVER['REMOTE_ADDR'];
$ip="192.168.1.1";
if($result=$gameroom->join_to_room($ip,$name)){
	
	echo json_encode($result);
}
else{
	echo "Error Joining To Room";
}
}

function save_game($players,$hand,$deck,$id,$round,$updatesmanager){
	$filehandler=new FileHandler();
	$save_path="data/".$id;
	if($filehandler->new_directory($save_path)){
	$players=serialize($players);
	$hand=serialize($hand);
	$deck=serialize($deck);
	$round=serialize($round);
	$updates=serialize($updatesmanager);
	$filehandler->save_file($players,$save_path."/players.dat");
	$filehandler->save_file($hand,$save_path."/hand.dat");
	$filehandler->save_file($deck,$save_path."/deck.dat");
	//$filehandler->save_file($deck,$save_path."/deck_".rand().".dat");
	$filehandler->save_file($round,$save_path."/round.dat");
	$filehandler->save_file($updates,$save_path."/updatesmanager.dat");
	}
}
function get_player_list($room_id,$gameroom,$myid,$players,$deck,$hand,$round,$updatesmanager){
	$list=$gameroom->get_list_players_room($room_id,$myid);
	$filehandler=new FileHandler();
	$game_started=(bool)$gameroom->check_game_started($room_id);
	$result_array=array("players"=>$list);
	$result_array['game_started']=$game_started;
	$players->set_room_id($room_id);
	$game_id="room_".$room_id;
	usleep(rand(1000000,3000000));
	if($game_started){
		if(!file_exists("data/$game_id/deck.dat")){
		
//file_put_contents(microtime(),"");
		$deck->shuffle_cards();
        $cards=$deck->get_first_three_cards();
		$deck->add_cards_to_middle($cards[0]);
		$deck->add_cards_to_middle($cards[1]);
		$deck->add_cards_to_middle($cards[2]);
		//file_put_contents("new_deck_".rand(),serialize($deck));
			
		
		
	foreach($list as $key=>$value){
     $id=$value['id'];
     $name=$value['name'];
	 $get_two_cards=$deck->get_two_cards();
     $players->add_player($id,array("name"=>$name,"hand"=>$get_two_cards));	 
	}
		save_game($players,$hand,$deck,$game_id,$round,$updatesmanager);
	}
		else{
			
		}	
		}
	
		$result_array['game_id']=$game_id;
		
		
		
		
	
echo json_encode($result_array);
}
?>