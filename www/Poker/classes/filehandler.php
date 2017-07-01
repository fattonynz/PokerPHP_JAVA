<?php




class FileHandler{
	
	
	
	function __construct(){
		
		
	}
public function checknames($name){
return urldecode($name);
}	
public function open_file($file_path){	
$str=file_get_contents($file_path);
return $str;
}
public function replace_credentials($info,$file_content){
	
foreach($info as $key=>$value){
$file_content=str_replace($key,$this->checknames($value),$file_content);	
}	
return $file_content;	
}

public function get_file_owner($file){
return posix_getpwuid(fileowner($file));
}
public  function get_file_group($file){
return posix_getgrgid(filegroup($file));
}
public function save_file($content,$new_file_name,$mode=null){

$s=@file_put_contents($new_file_name,$content,$mode);
if(!$s){
	echo "Permission Error!";
}
return $s;		
}
public function check_file_exists($path){
	if(file_exists($path)){return true;}else{return false;}
}	
public function new_directory($path){

	if($this->check_file_exists($path)){return true;}else{
		if(mkdir($path)){
			return true;
		}
		else{
			return false;
		}
		
		}

}

public function get_list_of_directories($path){
	return glob($path. '/*' , GLOB_ONLYDIR);
}
public function get_jpg_in_directory($dir){
	return glob($dir."/".'*.jpeg');
}
public function get_php_in_directory($dir){
	return glob($dir."/".'*.php');
}
public function htmlpath($relative_path) {
    $realpath=realpath($relative_path);
    $htmlpath=str_replace($_SERVER['DOCUMENT_ROOT'],'',$realpath);
    return $htmlpath;
}
}





?>