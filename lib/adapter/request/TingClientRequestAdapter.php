<?php

require_once dirname(__FILE__).'/../../search/TingClientSearchRequest.php';
require_once dirname(__FILE__).'/../../log/TingClientLogger.php';

interface TingClientRequestAdapter
{

	/**
	 * @param TingClientSearchRequest $request
	 * @return string The response body
	 */
	public function search(TingClientSearchRequest $searchRequest);
	
	public function setLogger(TingClientLogger $logger);
	
}
