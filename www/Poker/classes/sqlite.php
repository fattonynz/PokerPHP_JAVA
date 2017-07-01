<?php



class Db{
	private $db;
	function __construct(){
		$this->db = new SQLite3('data/db.db');

	}
	function query($sql,$mode=SQLITE3_NUM){
	$result = $this->db->query($sql);
	$data= array();

while ($res= $result->fetchArray($mode))
{

array_push($data, $res);

}
if(count($data)>1){
	return $data;
}
elseif(count($data)==1){
	return $data[0];	
}
	else{
return false;
}
	}
	function execute($sql){
		//var_dump($sql);
		return $this->db->exec($sql);
	}
	function last_id(){
		return $this->db->lastInsertRowID();
	}
}








?>