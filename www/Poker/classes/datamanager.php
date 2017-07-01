<?php

class DataManager{
private $get=array();
private $post=array();	
function __construct(){

$this->get=filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING,true);
$this->post=filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING,true);	
file_put_contents("log.txt",print_r($this->get,true). PHP_EOL, FILE_APPEND);
}	
public function get_var($key){
	if(isset($this->get[$key])){
	return htmlspecialchars($this->get[$key], ENT_QUOTES, 'UTF-8');
	}
	else{
		return false;
	}
}	
public function post_get($key){
	if($this->post[$key]==""){
		return true;
	}
	elseif(isset($this->post[$key])){
	return htmlspecialchars($this->post[$key], ENT_QUOTES, 'UTF-8');
	}
	
	else{
		return true;
	}
}
public function get_array_post(){
	return $this->post;
}
public function get_check_exists($array=array(),$debug=false){
$stat=true;
$k="";
foreach($array as $ar){
	if($stat==false){
		
		break;
	}
if($this->get_var($ar)==false){
$k=$ar;
	$stat=false;
}
else{
	$stat=true;
}	
}
if($debug==true){return array("error_param"=>$k,"status"=>$stat);}
return $stat;	
}	
public function post_check_exists($array=array(),$debug=false){
$stat=true;
$k="";
foreach($array as $ar){
	if($stat==false){
		
		break;
	}
if($this->post_get($ar)==false){
$k=$ar;
	$stat=false;
}
else{
	$stat=true;
}	
}
if($debug==true){return array("error_param"=>$k,"status"=>$stat);}
return $stat;	
}
public function  sanitize($value){
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}	
public function redirect_user($location){
header("LOCATION:$location");
exit;
}
}











?>