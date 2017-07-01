<?php
class Deck{
public  $shuffled_cards=array();	
public   $cards = array(
		
		    "A|SPADES",
			"K|SPADES",
			"Q|SPADES" ,
			"J|SPADES",
			"10|SPADES",
			"9|SPADES",
			"8|SPADES",
			"7|SPADES",
			"6|SPADES",
			"5|SPADES",
			"4|SPADES",
			"3|SPADES" ,
			"2|SPADES",
			
			"A|HEARTS",
			"K|HEARTS",
			"Q|HEARTS" ,
			"J|HEARTS",
			"10|HEARTS",
			"9|HEARTS",
			"8|HEARTS",
			"7|HEARTS",
			"6|HEARTS",
			"5|HEARTS",
			"4|HEARTS",
			"3|HEARTS" ,
			"2|HEARTS",
			
			"A|DIAMONDS",
			"K|DIAMONDS",
			"Q|DIAMONDS" ,
			"J|DIAMONDS",
			"10|DIAMONDS",
			"9|DIAMONDS",
			"8|DIAMONDS",
			"7|DIAMONDS",
			"6|DIAMONDS",
			"5|DIAMONDS",
			"4|DIAMONDS",
			"3|DIAMONDS" ,
			"2|DIAMONDS",
			
			
			"A|CLUBS",
			"K|CLUBS",
			"Q|CLUBS" ,
			"J|CLUBS",
			"10|CLUBS",
			"9|CLUBS",
			"8|CLUBS",
			"7|CLUBS",
			"6|CLUBS",
			"5|CLUBS",
			"4|CLUBS",
			"3|CLUBS" ,
			"2|CLUBS"		
			
	
		);
public  $cards_middle=array();
function __construct(){
	$this->shuffled_cards=$this->cards;
}
function add_cards_to_middle($card){
array_push($this->cards_middle,$card);
}
function shuffle_cards(){
	shuffle($this->shuffled_cards);
}	
function get_deck(){
	return $this->shuffled_cards;
}
function get_one_card(){
	$card1_rand=array_rand($this->shuffled_cards);
	$card1=$this->shuffled_cards[$card1_rand];	
	unset($this->shuffled_cards[$card1_rand]);
	return $card1;
}
function get_two_cards(){
		$card1_rand=array_rand($this->shuffled_cards);
	$card1=$this->shuffled_cards[$card1_rand];	
	unset($this->shuffled_cards[$card1_rand]);
	
		$card2_rand=array_rand($this->shuffled_cards);
	$card2=$this->shuffled_cards[$card2_rand];	
	unset($this->shuffled_cards[$card2_rand]);
	return array(0=>$card1,1=>$card2);	
}
function get_first_three_cards(){
	$card1_rand=array_rand($this->shuffled_cards);
	$card1=$this->shuffled_cards[$card1_rand];	
	unset($this->shuffled_cards[$card1_rand]);
	
	$card2_rand=array_rand($this->shuffled_cards);
    $card2=$this->shuffled_cards[$card2_rand];
    unset($this->shuffled_cards[$card2_rand]);

	$card3_rand=array_rand($this->shuffled_cards);
    $card3=$this->shuffled_cards[$card3_rand];
    unset($this->shuffled_cards[$card3_rand]);
	
	
$this->shuffled_cards=array_values($this->shuffled_cards);

return array(0=>$card1,1=>$card2,2=>$card3);	
}
}












?>