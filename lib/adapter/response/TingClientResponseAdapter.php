<?php

require_once dirname(__FILE__).'/../../log/TingClientLogger.php';
require_once dirname(__FILE__).'/../../search/TingClientSearchResult.php';
require_once dirname(__FILE__).'/../../object/data/TingClientObjectDataFactory.php';
require_once dirname(__FILE__).'/../../object/TingClientObject.php';
require_once dirname(__FILE__).'/../../object/TingClientObjectCollection.php';

interface TingClientResponseAdapter
{

	/**
	 * @param string $responseString
	 * @return TingClientSearchResult
	 */
	public function parseSearchResult($responseString);

	/**
	 * @param string $responseString
	 * @return TingClientScanResult
	 */
	public function parseScanResult($responseString);

	/**
	 * @param string $responseString
	 * @return array Array og TingClientObject instances
	 */
	public function parseCollectionResult($responseString);

	/**
	 * @param string $responseString
	 * @return TingClientObject
	 */
	public function parseObjectResult($responseString);
	
	public function setLogger(TingClientLogger $logger);
	
}
