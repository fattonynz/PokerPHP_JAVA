<?php

class PokerRound{
	
	public $current_round=0;
	public $current_subround=0;
	public $round_over_check=0;
	public $check_players_revisited=0;
	public $round_array=array();
	

	function __construct(){
		if(count($this->round_array)==0){
			//$this->round_array[0][0]=array();
			//var_dump($this->round_array);
			
			
		}
		
	}
function new_round(){
$this->current_round=$this->current_round+1;
$this->current_subround=0;	
}	
function new_sub_round(){	
	$this->current_subround=$this->current_subround+1;
}
function new_entry($entry,$player_id){
$this->round_array[$this->current_round][$this->current_subround][$player_id]=$entry;	
}

function get_highest_bid(){

	if(count($this->round_array)==0){
		return 5;
	}else{
		if(isset($this->round_array[$this->current_round][$this->current_subround])){
	$a=$this->round_array[$this->current_round][$this->current_subround];
	$highest_bet=0;
	if(count($a)>0){
		foreach($a as $key=>$value){
			
			$current_bet=$value['bet'];
			if($current_bet>$highest_bet){
				$highest_bet=$current_bet;
			}
		}
		return $highest_bet;
	}
	else{
		return 5;
	}
}
	}	
}

function get_check_count_current_round(){
	if(isset($this->round_array[$this->current_round])){
$a=$this->round_array[$this->current_round];
$count=0;
foreach($a as $key=>$value){
foreach($value as $j=>$k){
	if($k['bet_completed']==0){
		$count=$count+1;
	}
}}
return $count;	
}else{return 0;}

}


function get_folded_count_current_round(){
	if(isset($this->round_array[$this->current_round])){
$a=$this->round_array[$this->current_round];
$count=0;
foreach($a as $key=>$value){
foreach($value as $j=>$k){
	if($k['type']=="fold"){
		$count=$count+1;
	}
}}
return $count;	
}else{return 0;}

}
function check_player_folded($player_id){
	if(isset($this->round_array[$this->current_round])){	
$a=$this->round_array[$this->current_round];;

foreach($a as $key=>$value){
if(isset($value[$player_id])){
	$type=$value[$player_id]['type'];
	if($type=="fold"){
		return true;
	}
}
else{
	return false;
}
}
return false;
}else{
	return false;
}
}
}

/*
array structure

array(0=>array(0=>array(1234=>array(bet=>123,bet_completed=>1,type=>call/raise/check/fold)



))




))





*/


?>