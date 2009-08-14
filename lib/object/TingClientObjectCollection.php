<?php

require_once dirname(__FILE__).'/TingClientObject.php';

class TingClientObjectCollection
{
	public $objects;
	
  public function __construct($objects = array()) {
  	$this->objects = $objects;
  }
  
}