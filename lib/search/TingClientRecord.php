<?php

class TingClientRecord
{
	private $id;	
	private $data;
	
	public function getId()
	{
		return $this->id;
	}
	
	function setId($id) {
		$this->id = $id;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function setData(TingClientRecordData $data)
	{
		$this->data = $data;
	}
	
}

?>