<?php

class TingClientRequest
{
		private $output;
	
		function __construct($output = null)
		{
			$this->output = $output;
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