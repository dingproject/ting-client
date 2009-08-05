<?php

class TingClientObjectCollection implements Iterator, ArrayAccess
{
	public $objects;
	private $position = 0;
	
  public function __construct($objects = array()) {
  	$this->objects = $objects;
  }

  
  //Methods to implement Iterator and ArrayAccess interfaces
  function rewind() {
  	$this->position = 0;
  }

  function current() {
  	return $this->objects[$this->position];
  }

  function key() {
  	return $this->position;
  }

  function next() {
  	++$this->position;
  }

  function valid() {
  	return isset($this->objects[$this->position]);
  }	
  
  public function offsetSet($offset, $value) {
		$this->objects[$offset] = $value;
  }
  
  public function offsetExists($offset) {
  	return isset($this->objects[$offset]);
  }

  public function offsetUnset($offset) {
  	unset($this->objects[$offset]);
  }
  
  public function offsetGet($offset) {
  	return isset($this->objects[$offset]) ? $this->objects[$offset] : null;
  }
  
}