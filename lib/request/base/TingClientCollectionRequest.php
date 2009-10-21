<?php

require_once dirname(__FILE__) . '/TingClientAgentRequest.php';

interface TingClientCollectionRequest extends TingClientAgentRequest
{

		function getObjectId();
		
		function setObjectId($id);
		
}
