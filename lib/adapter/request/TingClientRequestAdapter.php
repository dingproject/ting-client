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

	/**
	 * @param string $scanString
	 * @return string The response body
	 */
	public function scan($scanString);
	
	public function setLogger(TingClientLogger $logger);
	
}
