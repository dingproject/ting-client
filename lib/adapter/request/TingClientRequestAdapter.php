<?php

require_once dirname(__FILE__).'/../../search/TingClientSearchRequest.php';

interface TingClientRequestAdapter
{

	/**
	 * @param TingClientSearchRequest $request
	 * @return string The response body
	 */
	function search(TingClientSearchRequest $searchRequest);
	
}
