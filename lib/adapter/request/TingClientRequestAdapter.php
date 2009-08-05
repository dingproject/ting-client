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
	 * @param TingClientScanRequest $scanRequest
	 * @return string The response body
	 */
	public function scan(TingClientScanRequest $scanRequest);
	
	/**
	 * @param string $collectionId
	 * @return string The response body
	 */
	public function getCollection($collectionId);
	
	/**
	 * @param string $objectId
	 * @return string The response body
	 */
	public function getObject($objectId);
	
	public function setLogger(TingClientLogger $logger);
	
}
