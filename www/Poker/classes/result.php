<?php



class Result{
	private $status;
	private $messeges=array();
	private $additional_info=array();
	
function __construct(){
	$status=0;
}	
public function set_status($status){
	$this->status=$status;
}	
public function set_messeges($messeges){
	$this->messeges[]=$messeges;
}
public function set_additional_info($key,$value){
	$this->additional_info[$key]=$value;
}
public function get_result($return_type){
	$result=array("status"=>$this->status,"messeges"=>$this->messeges);
	foreach($this->additional_info as $key=>$value){
		$result[$key]=$value;
	}
	if($return_type=="json"){
	return json_encode($result);
	}
	else{
		return $result;
	}
}	
}








?>