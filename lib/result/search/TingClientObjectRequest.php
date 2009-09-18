<?php

require_once dirname(__FILE__).'/TingClientRequest.php';

class TingClientObjectRequest extends TingClientRequest
{
		private $id;
		private $output;
	
		function __construct($id = null, $output = null)
		{
			parent::__construct($output);
			$this->id = $id;
		}

		function getId()
		{
			return $this->id;
		}
		
		function setId($id)
		{
			$this->id = $id;
		}
		
}