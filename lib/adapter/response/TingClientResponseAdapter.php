<?php

require_once dirname(__FILE__).'/../../log/TingClientLogger.php';

interface TingClientResponseAdapter
{

	/**
	 * Enter description here...
	 *
	 * @param string $responseString
	 */
	public function parseSearchResult($responseString);
	
	public function setLogger(TingClientLogger $logger);
	
}
