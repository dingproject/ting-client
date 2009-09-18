<?php

require_once dirname(__FILE__).'/../log/TingClientLogger.php';

interface TingClientRequestAdapter
{

	/**
	 * @param TingClientRequest $request
	 * @return string The response body
	 */
	public function setLogger(TingClientLogger $logger);
	
}
