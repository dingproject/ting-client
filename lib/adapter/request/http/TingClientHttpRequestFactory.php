<?php

$basePath = dirname(__FILE__);
require_once $basePath.'/TingClientHttpRequest.php';
require_once $basePath.'/../../../search/TingClientSearchRequest.php';

class TingClientHttpRequestFactory
{
	
	function fromSearchRequest(TingClientSearchRequest $searchRequest)
	{
		$request = new TingClientHttpRequest();
		return $request;
	}
	
}