<?php

class TingClientFacetResult
{
	
	private $name;
	private $terms = array();
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getTerms()
	{
		return $this->terms;
	}
	
	public function addTerm($name, $frequency)
	{
		$this->terms[$name] = $frequency;
	}
	
}