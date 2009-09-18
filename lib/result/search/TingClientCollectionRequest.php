<?php

require_once dirname(__FILE__).'/TingClientRequest.php';

class TingClientCollectionRequest extends TingClientRequest
{
		private $objectId;
		private $output;
	
		function __construct($objectId = null, $output = null)
		{
			parent::__construct($output);
			$this->objectId = $objectId;
		}

		function getObjectId()
		{
			return $this->objectId;
		}
		
		function setObjectId($objectId)
		{
			$this->objectId = $objectId;
		}
				
}