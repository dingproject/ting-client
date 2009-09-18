<?php

interface TingClientScanRequest
{
	
	function getField();
	
	function setField($field);

	function getPrefix();

	function setPrefix($prefix);
	
	function getNumResults();
	
	function setNumResults($numResults);
	
	function getLower();
	
	function setLower($lower);
	
	function getUpper();
	
	function setUpper($upper);
	
	function getMinFrequency();
	
	function setMinFrequency($minFrequency);
	
	function getMaxFrequency();
	
	function setMaxFrequency($maxFrequency);
	
	function getOutput();
	
	function setOutput($output);
	
}