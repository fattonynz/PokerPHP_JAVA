<?php
require_once("classes/deck.php");
require_once("classes/players.php");
require_once("classes/datamanager.php");
require_once("classes/filehandler.php");
require_once("classes/unserializer.php");
require_once("classes/pokerround.php");
require_once("classes/updatesmanager.php");
$datamanager=new DataManager();
$filehandler=new FileHandler();

if($datamanager->get_check_exists(array("game_id"))){
$game_id=$datamanager->get_var("game_id");

}
else{
	exit;
}

$path_to_deck="data/$game_id/deck.dat";
$path_to_players="data/$game_id/players.dat";
$path_to_round="data/$game_id/round.dat";
$path_to_updates="data/$game_id/updatesmanager.dat";

$deck_saved=unserialize($filehandler->open_file($path_to_deck));
$player_saved=unserialize($filehandler->open_file($path_to_players));
$round_saved=unserialize($filehandler->open_file($path_to_round));
$updatesmanager_saved=unserialize($filehandler->open_file($path_to_updates));

$deck=new Deck();
$players=new Players();
$round=new PokerRound();
$updatesmanager=new UpdatesManager();

$deck->cards_middle=$deck_saved->cards_middle;
$deck->cards=$deck_saved->cards;
$deck->shuffled_cards=$deck_saved->shuffled_cards;

$players->room_id=$player_saved->room_id;
$players->player_array=$player_saved->player_array;

$round->current_round=$round_saved->current_round;
$round->current_subround=$round_saved->current_subround;
$round->round_array=$round_saved->round_array;
$round->round_over_check=$round_saved->round_over_check;
$round->check_players_revisited=$round_saved->check_players_revisited;


$updatesmanager->updates_array=$updatesmanager_saved->updates_array;

if($datamanager->get_check_exists(array("action"))){
$action=$datamanager->get_var("action");
switch($action){
case "GET_INITIAL_CARDS":
//var_dump($deck);
file_put_contents("deck.txt",serialize($deck). PHP_EOL, FILE_APPEND);
get_cards_in_middle($deck);
//save_game($players,$deck,$game_id,$round,$updatesmanager);
break;

case "CURRENT_HAND":
$player_id=$datamanager->get_var("player_id");
get_current_hand($players,$player_id);
save_game($players,$deck,$game_id,$round,$updatesmanager);
break;

case "CHECK_WHOS_NEXT":
$player_id=$datamanager->get_var("player_id");
check_whos_next($round,$players,$player_id,$updatesmanager,$filehandler,$deck,$game_id);
save_game($players,$deck,$game_id,$round,$updatesmanager);
break;

case "NEW_BET":
$player_id=$datamanager->get_var("player_id");
$type=$datamanager->get_var("type");
$raised_amount=$datamanager->get_var("raised_amount");
$highest_bid=$datamanager->get_var("highest_bid");
new_bet($round,$type,$highest_bid,$raised_amount,$player_id,$updatesmanager,$players);
save_game($players,$deck,$game_id,$round,$updatesmanager);
break;

case "GET_NEW_MIDDLE_CARD":
get_new_middle_card($deck);
break;
}	
	
	
}

function get_new_middle_card($deck){
echo json_encode(array("card"=>end($deck->cards_middle),"position"=>count($deck->cards_middle)));
}
function new_bet($round,$type,$highest_bet,$raised,$player_id,$updatesmanager,$players){
	$entry=array();
	
	if(isset($round->round_array[$round->current_round][$round->current_subround][$player_id])){
		switch($type){
		case "fold":
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['type']="fold";
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet']=$highest_bet;
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet_completed']=1;
		$updatesmanager->insert_an_update($round->round_array[$round->current_round][$round->current_subround][$player_id],$players,$player_id);
		break;
		case "check":
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['type']="fold";
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet']=$highest_bet;
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet_completed']=1;
		$updatesmanager->insert_an_update($round->round_array[$round->current_round][$round->current_subround][$player_id],$players,$player_id);
		break;
		
		case "call":
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['type']="call";
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet']=$highest_bet;
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet_completed']=1;
		$updatesmanager->insert_an_update($round->round_array[$round->current_round][$round->current_subround][$player_id],$players,$player_id);
		break;
		case "raise":
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['type']="raise";
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet']=$raised;
		$round->round_array[$round->current_round][$round->current_subround][$player_id]['bet_completed']=1;
		$updatesmanager->insert_an_update($round->round_array[$round->current_round][$round->current_subround][$player_id],$players,$player_id);
		break;
	}
	}
	else{
	switch($type){
	case "fold":
	$entry['type']="fold";
	$entry['bet']=0;
	$entry['bet_completed']=1;
    break;	
	
	case "check":
	$entry['type']="check";
	$entry['bet']=0;
	$entry['bet_completed']=0;
	break;
	
	case "call":
	$entry['type']="call";
	$entry['bet']=$highest_bet;
	$entry['bet_completed']=1;
	break;
		case "raise":
	$entry['type']="raise";
	$entry['bet']=$raised;
	$entry['bet_completed']=1;
	break;
	}
	
	$round->new_entry($entry,$player_id);
	$updatesmanager->insert_an_update($entry,$players,$player_id);
	}
}
function get_cards_in_middle($deck){
	echo json_encode(array("cards"=>$deck->cards_middle));
}

function get_current_hand($players,$player_id){
	echo json_encode(array("cards"=>$players->player_array[$player_id]));
}


function check_whos_next($round,$players,$myid,$updates_manager,$filehandler,$deck,$game_id){
$next_player=find_next_bet($round,$players,$myid,$updates_manager,$filehandler,$deck,$game_id);
if(is_array($next_player)){
echo json_encode($next_player);	
}else{
if($next_player==$myid){
	$highest_bid=(int)$round->get_highest_bid();
	if($highest_bid==null){
		$highest_bid=0;
	}
		if(isset($updates_manager->updates_array[$myid])){
	echo json_encode(array("messege"=>"NEW_UPDATES","updates"=>$updates_manager->get_updates($myid),"messege2"=>"PLACE_YOUR_BET","HIGHEST_BID"=>$highest_bid));
	}else{
		
echo json_encode(array("messege"=>"PLACE_YOUR_BET","HIGHEST_BID"=>$highest_bid));
	}
}
else{
//echo "<pre>";
//print_r($updates_manager);
//echo "</pre>";
	if(isset($updates_manager->updates_array[$myid])){
	echo json_encode(array("messege"=>"NEW_UPDATES","updates"=>$updates_manager->get_updates($myid)));
	}
	else{
		echo json_encode(array("messege"=>"NO_NEW_UPDATES"));
	}
}	
}	
}
function find_next_bet($round,$players,$myid,$updates_manager,$filehandler,$deck,$game_id){
$player_array=$players->player_array;
$round_array=$round->round_array;
$current_round=$round->current_round;
$current_subround=$round->current_subround;	

$folded_count=$round->get_folded_count_current_round();
$c=0;

if($round->round_over_check==0){



foreach($player_array as $key=>$value){
	if(isset($round_array[$current_round][$current_subround][$key])){
		$c=$c+1;
		
		continue;
	}
	else{
		if($round->check_player_folded($key)){
			$c=$c+1;
			
		continue;	
		}
		else{
			$c=$c+1;
			
			
			return $key;
		}
	}
}
if($c==6||($c+$folded_count)==6){
	
				$round->round_over_check=1;	
			
			}
}
else{


	$sub_round_array=$round_array[$current_round][$current_subround];
	foreach($sub_round_array as $key=>$value){
		$type=$value['bet_completed'];
		if($type==0 and $myid==$key){
			
			$round->check_players_revisited=$round->check_players_revisited+1;
			
			return $key;
		}
		else{
			continue;
		}
		
	}
		$checked_count=$round->get_check_count_current_round();
if($checked_count==0){

	//initiating a new sub round
	$round->new_sub_round();
	$round->round_over_check=0;
	$round->check_players_revisited=0;
if(!$filehandler->check_file_exists("lock.txt")){
	$new_card=$deck->get_one_card();
	$deck->add_cards_to_middle($new_card);
	$filehandler->save_file(serialize($deck),"data/$game_id/deck.dat");
}
	$updates_manager->insert_an_update(array("type"=>"SUB_ROUND_ENDED","bet"=>0,"next_round"=>$round->current_subround),$players,0);

	return array("messege"=>"SUB_ROUND_ENDED");
}
}
}

function save_game($players=null,$deck=null,$id,$round=null,$updatesmanager=null){
	$filehandler=new FileHandler();
	$save_path="data/".$id;
	if($filehandler->new_directory($save_path)){
		
	if($players!==null){
	$players=serialize($players);
	$filehandler->save_file($players,$save_path."/players.dat");
	}	
	if($deck!==null){
	$deck=serialize($deck);
	$filehandler->save_file($deck,$save_path."/deck.dat");
	}
	if($round!==null){
	$round=serialize($round);	
	$filehandler->save_file($round,$save_path."/round.dat");
	}
	if($updatesmanager!==null){
	$updates=serialize($updatesmanager);	
	$filehandler->save_file($updates,$save_path."/updatesmanager.dat");
	}
	
	
	

	
	
	
	}
}

?>