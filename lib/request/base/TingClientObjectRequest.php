<?php

require_once dirname(__FILE__) . '/TingClientAgentRequest.php';

interface TingClientObjectRequest extends TingClientAgentRequest
{

		function getObjectId();
		
		function setObjectId($id);
		
		function getLocalId();
		
		function setLocalId($id);
		
}
