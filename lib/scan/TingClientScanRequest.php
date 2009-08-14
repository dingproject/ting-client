<?php

class TingClientScanRequest
{
	
	private $field;
	private $prefix;
	private $numResults;
	private $lower;
	private $upper;
	private $minFrequency;
	private $maxFrequency;
	private $output;
	
	function getField()
	{
		return $this->field;
	}
	
	function setField($field)
	{
		$this->field = $field;
	}
	
	function getPrefix()
	{
		return $this->prefix;
	}
	
	function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}
	
	function getNumResults()
	{
		return $this->numResults;
	}
	
	function setNumResults($numResults)
	{
		$this->numResults = $numResults;
	}
	
	function getLower()
	{
		return $this->lower;
	}
	
	function setLower($lower)
	{
		$this->lower = $lower;
	}
	
	function getUpper()
	{
		return $this->upper;
	}
	
	function setUpper($upper)
	{
		$this->upper = $upper;
	}
	
	function getMinFrequency()
	{
		return $this->minFrequency;
	}
	
	function setMinFrequency($minFrequency)
	{
		$this->minFrequency = $minFrequency;
	}
	
	function getMaxFrequency()
	{
		return $this->maxFrequency;
	}
	
	function setMaxFrequency($maxFrequency)
	{
		$this->maxFrequency = $maxFrequency;
	}
	
	function getOutput()
	{
		return $this->output;
	}
	
	function setOutput($output)
	{
		$this->output = $output;
	}
	
}