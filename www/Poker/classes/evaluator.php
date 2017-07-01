<?php

class Evaluator{
	
	private $cards_both=array("K|CLUBS","K|HEARTS","7|SPADES","5|SPADES","7|DIAMONDS","2|CLUBS","3|CLUBS");
	private $cards=null;
	private $output="";
	private $output_card_count;
	
	
	function __construct(){
	$this->cards=$this->format_cards($this->cards_both);
	
	//var_dump($this->cards);
		$this->royal_flush();
		
		echo $this->output;
	}
	private function remove_card_from_array($card,$array=array()){
$ex=explode("|",$card);
$no=$ex[0];
$suit=$ex[1];

	for($i=0;$i<count($array[$suit]);$i++){
		if($array[$suit][$i]==$no){
			unset($array[$suit][$i]);
		}
	}
return $array;	
	}
	
	private function find_high_card(){
		$high_card=0;
		$high_suit="";
		foreach($this->cards as $key=>$value){
			
				$max=max($value);
				if($max>$high_card){
					$high_card=$max;
					$high_suit=$key;
				}
			
		}
		return $high_card."|".$high_suit;
	}
	private function set_ace_to_higher(){
		$new_array=array();
		$new_array["HEARTS"]=array();
		$new_array["CLUBS"]=array();
		$new_array["SPADES"]=array();
		$new_array["DIAMONDS"]=array();
		foreach($this->cards as $key=>$value){
			foreach($value as $j=>$k){
			if($k=="1"){
				$k="14";
			}
			$new_array[$key][]=$k;
			}
		}
		
		return $new_array;
	}
	private function set_ace_to_lower(){
		$new_array=array();
		$new_array["HEARTS"]=array();
		$new_array["CLUBS"]=array();
		$new_array["SPADES"]=array();
		$new_array["DIAMONDS"]=array();
		foreach($this->cards as $key=>$value){
			
			foreach($value as $j=>$k){
			if($k=="14"){
				$k="1";
			}
			$new_array[$key][]=$k;
			}
		}
		
		return $new_array;
	}
	
	private function format_cards($array){
		
		$new_array["HEARTS"]=array();
		$new_array["CLUBS"]=array();
		$new_array["SPADES"]=array();
		$new_array["DIAMONDS"]=array();
		
		foreach($array as $card){
			
			$ex=explode("|",$card);
			$card_no=$ex[0];
			if($card_no=="J"){$card_no="11";}
			if($card_no=="Q"){$card_no="12";}
			if($card_no=="K"){$card_no="13";}
			if($card_no=="A"){$card_no="14";}
			$card_suit=$ex[1];
		
			array_push($new_array[$card_suit],$card_no);
		}
		return $new_array;
	}
	private function occurences($card,$return_array=null){
		$c=0;
		$a=array();
		$unmatched=array();
	foreach($this->cards as $key=>$value){
			
			foreach($this->cards[$key] as $d=>$p){
			if($p==$card){
				$c=$c+1;
				array_push($a,$p."|".$key);
			}
            else{
             array_push($unmatched,$p."|".$key);	
              }			
			}
			
			
			
		}
		if($return_array!==null){
			return array("count"=>$c,"matched_cards"=>$a,"unmatched"=>$unmatched);
		}
		else{
		return $c;
		}
	}
	private function get_highest_occurence_card($count,$return_matched_cards=null){
		$matched_cards=array();
		$occurence_array=array();
		$unmatched_cards=array();
		foreach($this->cards as $key=>$value){
			
			foreach($this->cards[$key] as $d=>$p){
				
				if($return_matched_cards!==null){
					$o=$this->occurences($p,true);
					$occurence_array[$p]=$o['count'];
					$m=$o['matched_cards'];
					$u=$o['unmatched'];
					if(count($m)==$count){
					$matched_cards[0]=$m;
					$unmatched_cards[0]=$u;
					}
					
				}
				else{
					$occurence_array[$p]=$this->occurences($p);
				}
			}
			
			
			
		}
		
		krsort($occurence_array);
		
		//var_dump($occurence_array);
		foreach($occurence_array as $k=>$v){
			if($v==$count){
				if($return_matched_cards!==null){
					return array("count"=>$k,"matched_cards"=>$matched_cards,"unmatched_cards"=>$unmatched_cards);
				}
				else{
				return $k;
				}
			}
		}

		
		
	}
	private function check_same_suit_count($c){
		foreach($this->cards as $key=>$value){
			if(count($this->cards[$key])>=$c){
				return $key;
			}
		}
	}
function find_consecutive($array, $count) {
    $consecutive = array();
    $previous = null;
    foreach ($array as $value) {
        if ($previous !== null && $value == $previous + 1) {
            $consecutive[] = $value;
            if ($found == $count) {
                return $consecutive;
            }
        } else {
            $consecutive = array($value);
            $found = 1;
        }
        $previous = $value;
        $found++;
    }
	
}
	private function checkConsec($array,$total_consecutive_numbers=5) {
sort($array);
$cosecutive_array=$this->find_consecutive($array,$total_consecutive_numbers);

if(is_array($cosecutive_array) && count($cosecutive_array)==$total_consecutive_numbers){
return array_sum($cosecutive_array);
}
else{
	return false;
}
	}

	private function check_cards_exists($suit,$card_no=array()){
		$matched_count=0;
	if(isset($this->cards[$suit])){
		foreach($this->cards[$suit] as $key=>$value){
			for($i=0;$i<count($card_no);$i++){
				if($card_no[$i]==$value){
				$matched_count=$matched_count+1;	
				}
			}
		}
	}
	if($matched_count==count($card_no)){
		return true;
		
	}
	else{
		return false;
	}
	}
	private function check_same_card_different_suit($card,$current_pair,$occurence=1){
		$cards=$this->cards;
		$occurence_now=0;
		$occ_array=array();
		$occ_array[$current_pair]=$card;
		unset($cards[$current_pair]);
		foreach($cards as $key=>$value){
			foreach($value as $j=>$k){
			
				if($k==$card){
				$occurence_now=$occurence+1;
                array_push($occ_array,$k."|".$key);				
				}
			}
		}
		if($occurence_now>=$occurence){
			return array("count"=>$occurence_now,"occ_array"=>$occ_array);	}
			else{
				return false;
			}
	}
	private function check_pair_exists($array=null){
		if($array==null){
			$cards=$this->cards;
		}
		else{
			$cards=$array;
		}
		$pair_array=array();
		foreach($cards as $key=>$value){
			foreach($value as $d=>$k){
			$pair_array[$k][]=$k."|".$key;
				
			}
		}
		krsort($pair_array);
	foreach($pair_array as $pair=>$suits){
		if(count($suits)>=2){
		return array("card"=>$pair,"card_names"=>$suits);	
		}
	}
		
	}
	private function royal_flush(){
		if($this->check_cards_exists($this->check_same_suit_count(5),array(14,13,12,11,10))){
			
			$this->output="ROYAL_FLUSH";
		}
		else{
		$this->straight_flush();	
		}
	}
	
	private function straight_flush(){
		$suit=$this->check_same_suit_count(5);
		if($suit!==null){
		if($this->element_count=$this->checkConsec($this->cards[$suit],5)){
		$this->output="STRAIGHT_FLUSH";	
		}
		else{
		$this->four_of_a_kind();	
		}
		}
		else{
			$this->four_of_a_kind();	
		}
		
	}
	private function four_of_a_kind(){
	$highest_occurnce=$this->get_highest_occurence_card(4);
	
	if($highest_occurnce!==null){
	$this->output="FOUR_OF_A_KIND";	
    $this->element_count=($highest_occurnce*4);
	}
	else{
		$this->full_house();
	}
	}
	
	private function full_house(){
	
		$matches=$this->get_highest_occurence_card(3,true);
		
		if($matches!==null){
			$three_of_kind=$matches['count'];
			$matched_cards=$matches['matched_cards'][0];
		$unmatched_cards=$this->format_cards($matches['unmatched_cards'][0]);
		//var_dump($unmatched_cards);
		$pair=$this->check_pair_exists($unmatched_cards);
		
		if(is_int($pair['card'])){
	$this->output="FULL_HOUSE";	
	$this->element_count=array_sum($matched_cards);
    
		}
		else{
			$this->poker_flush();
		}
			
		}
		else{
			$this->poker_flush();
		}
	}
	
	private function poker_flush(){
		$found_match=false;
		$card_sum=0;
		$matched_cards=array();
	foreach($this->cards as $key=>$value){
	if(count($value)>=5){
		$matched_cards=$value;
		$found_match=true;
		$card_sum=array_sum($value);
		
	}	
	}

       if($found_match==true){
	$this->output="FLUSH";	
	$this->element_count=$card_sum;

     }	
	 else{
		 $this->straight();
	 }
	}

	private function straight(){
	$diamonds=$this->cards["DIAMONDS"];
	$spades=$this->cards["SPADES"];
	$hearts=$this->cards["HEARTS"];
	$clubs=$this->cards["CLUBS"];
	$new_array=array_merge($diamonds,$spades,$hearts,$clubs);
	sort($new_array);

	$consecutive_ace_high=$this->checkConsec($new_array,5);


	$lower_ace=$this->set_ace_to_lower();
	$diamonds=$lower_ace["DIAMONDS"];
	$spades=$lower_ace["SPADES"];
	$hearts=$lower_ace["HEARTS"];
	$clubs=$lower_ace["CLUBS"];
	$new_array=array_merge($diamonds,$spades,$hearts,$clubs);
	sort($new_array);
	$consecutive_ace_low=$this->checkConsec($new_array,5);
	
	if(is_int($consecutive_ace_low)&&$consecutive_ace_low>=$consecutive_ace_high){
	$this->output="STRAIGHT";	
	$this->element_count=$consecutive_ace_low;
	}
	elseif(is_int($consecutive_ace_high)&&$consecutive_ace_high>=$consecutive_ace_low){		
	$this->output="STRAIGHT";	
	$this->element_count=$consecutive_ace_high;
	}else{
		$this->three_of_kind();
	}
	
	
	}
	
	
	private function three_of_kind(){
		$card=$this->get_highest_occurence_card(3);
		if(is_int($card)){
		$this->output="THREE_OF_A_KIND";	
	    $this->element_count=$card*3;
		}
		else{
		$this->two_pairs();	
		}
	}
	private function two_pairs(){
		$pair1=$this->check_pair_exists();
		
		if(is_array($pair1)){
			$matched_cards=$pair1['card_names'];
			$pair1_sum=$pair1['card']*2;
			$cards=$this->cards;
			$removed=$this->remove_card_from_array($matched_cards[0],$cards);
			$removed=$this->remove_card_from_array($matched_cards[1],$removed);
			
			$pair2=$this->check_pair_exists($removed);
				if(is_array($pair2)){
				$matched_cards2=$pair2['card_names'];
			    $pair2_sum=$pair2['card']*2;	
				$this->output="TWO_PAIRS";	
	            $this->element_count=$pair2_sum+$pair1_sum;
				}
				else{
				$this->pair();		
				}
		}
		else{
		$this->pair();	
		}
	}
	
	private function pair(){
		$pair1=$this->check_pair_exists();
		
		if(is_array($pair1)){
			$matched_cards=$pair1['card_names'];
			$pair1_sum=$pair1['card']*2;
				$this->output="PAIR";	
	            $this->element_count=$pair1_sum;
		}
		else{
			$this->high_card();
		}
	}
	
	private function high_card(){
		if($high=$this->find_high_card()){
			
		
		$this->output="HIGH_CARD";	
	            $this->element_count=$high;
		}
	}
}



$eval=new Evaluator();
///$eval->royal_flush();




?>