<?php

require_once dirname(__FILE__).'/adapter/request/TingClientRequestAdapter.php';
require_once dirname(__FILE__).'/adapter/response/TingClientResponseAdapter.php';
require_once dirname(__FILE__).'/log/TingClientVoidLogger.php';

class TingClient
{
	
	/**
	 * @var TingClientRequestAdapter
	 */
	private $requestAdapter;
	
	/**
	 * @var TingClientResponseAdapter
	 */
	private $responseAdapter;
	
	/**
	 * @var TingClientLogger
	 */
	private $logger;
	
	function __construct(TingClientRequestAdapter $requestAdapter,
						 TingClientResponseAdapter $responseAdapter, TingClientLogger $logger = NULL)
	{
		$this->logger = (isset($logger)) ? $logger : new TingClientVoidLogger();
		$this->requestAdapter = $requestAdapter;
		$this->requestAdapter->setLogger($this->logger);
		$this->responseAdapter = $responseAdapter;
		$this->responseAdapter->setLogger($this->logger);
	}
	
	/**
	 * @param TingClientSearchRequest $searchRequest
	 * @return TingClientSearchResult
	 */
	public function search(TingClientSearchRequest $searchRequest)
	{
		$response = $this->requestAdapter->search($searchRequest);
		return $this->responseAdapter->parseSearchResult($response);
	}
	
	/**
	 * @param TingClientScanRequest $scanRequest
	 * @return TingClientScanResult
	 */
	public function scan(TingClientScanRequest $scanRequest)
	{
		$response = $this->requestAdapter->scan($scanRequest);
		return $this->responseAdapter->parseScanResult($response);
	}
	
	/**
	 * @param string $collectionId
	 * @return TingClientObjectCollection 
	 */
	public function getCollection($collectionId)
	{
		$response = $this->requestAdapter->getCollection($collectionId);
		return $this->responseAdapter->parseCollectionResult($response);
	}
	
	/**
	 * @param string $objectId
	 * @return TongClientObject
	 */
	public function getObject($objectId)
	{
		$response = $this->requestAdapter->getObject($objectId);
		return $this->responseAdapter->parseObjectResult($response);
	}
	
}

?>