<?php

require_once dirname(__FILE__).'/../../search/TingClientSearchRequest.php';
require_once dirname(__FILE__).'/../../search/TingClientCollectionRequest.php';
require_once dirname(__FILE__).'/../../search/TingClientObjectRequest.php';
require_once dirname(__FILE__).'/../../scan/TingClientScanRequest.php';
require_once dirname(__FILE__).'/../../log/TingClientLogger.php';

interface TingClientRequestAdapter
{

	/**
	 * @param TingClientSearchRequest $request
	 * @return string The response body
	 */
	public function search(TingClientSearchRequest $searchRequest);

	/**
	 * @param TingClientScanRequest $scanRequest
	 * @return string The response body
	 */
	public function scan(TingClientScanRequest $scanRequest);
	
	/**
	 * @param TingClientCollectionRequest $collectionRequest
	 * @return string The response body
	 */
	public function getCollection(TingClientCollectionRequest $collectionRequest);
	
	/**
	 * @param TingClientObjectRequest $objectRequest
	 * @return string The response body
	 */
	public function getObject(TingClientObjectRequest $objectRequest);
	
	public function setLogger(TingClientLogger $logger);
	
}
