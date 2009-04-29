<?php

$basePath = dirname(__FILE__);
require_once $basePath.'/../../search/TingClientSearchRequest.php';
require_once $basePath.'/../../exception/TingClientException.php';

interface TingClientRequestAdapter
{

	/**
	 * @param TingClientSearchRequest $request
	 * @return string The response body
	 */
	function search(TingSearchRequest $searchRequest);
	
}
