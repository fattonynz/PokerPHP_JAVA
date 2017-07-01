<?php

class UpdatesManager{
	
	public $updates_array=array();
	
	
	
	
	function insert_an_update($update,&$player_array,$player_id=null){
		if($player_id!==null){
			$update['player_id']=$player_id;
		}
		foreach($player_array->player_array as $key=>$value){
			$this->updates_array[$key][]=$update;
		}
		
	}
	function get_updates($player_id){
		$update=$this->updates_array[$player_id];
		unset($this->updates_array[$player_id]);
		return $update;
	}
	
	
	
}





?>