<?php

require_once dirname(__FILE__).'/../../log/TingClientLogger.php';
require_once dirname(__FILE__).'/../../search/TingClientSearchResult.php';
require_once dirname(__FILE__).'/../../search/data/TingClientRecordDataFactory.php';

interface TingClientResponseAdapter
{

	/**
	 * @param string $responseString
	 * @return TingClientSearchResult
	 */
	public function parseSearchResult($responseString);
	
	public function setLogger(TingClientLogger $logger);
	
}
