<?php

class ChartOptions{
	public function __construct(){
		$this->optList = array();
	}

	public function set($name,$val){
		$this->optList[$name] = $val;
		return $this;
	}

	public function getAll(){
		return $this->optList;
	}
}

?>
