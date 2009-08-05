<?php

require_once dirname(__FILE__).'/../TingClientRequestAdapter.php';
require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__).'/../../../search/TingClientSearchRequest.php';
require_once dirname(__FILE__).'/../../../log/TingClientVoidLogger.php';

abstract class TingClientHttpRequestAdapter implements TingClientRequestAdapter
{
		
	/**
	 * @var TingClientHttpRequestFactory
	 */
	private $httpRequestFactory;
	
	/**
	 * @var TingClientLogger
	 */
	protected $logger;

	function __construct(TingClientHttpRequestFactory $httpRequestFactory)
	{
		$this->logger = new TingClientVoidLogger();
		$this->httpRequestFactory = $httpRequestFactory;
	}
	
	public function setHttpRequestFactory(TingClientHttpRequestFactory $httpRequestFactory)
	{
		$this->httpRequestFactory = $httpRequestFactory;
	}
	
	public function setLogger(TingClientLogger $logger)
	{
		$this->logger = $logger;
		$this->httpRequestFactory->setLogger($logger);
	}
	
	/**
	 * @param TingClientSearchRequest $request
	 * @return string The response body
	 */
	public function search(TingClientSearchRequest $searchRequest)
	{
		return $this->doRequest($this->httpRequestFactory->fromSearchRequest($searchRequest));
	}
	
	/**
	 * @param TingClientScanRequest $scanRequest
	 * @return string The response body
	 */
	public function scan(TingClientScanRequest $scanRequest)
	{
		return $this->doRequest($this->httpRequestFactory->fromScanRequest($scanRequest));
	}
	
	/**
	 * @param TingClientCollectionRequest $collectionRequest
	 * @return string The response body
	 */
	public function getCollection(TingClientCollectionRequest $collectionRequest)
	{
		return $this->doRequest($this->httpRequestFactory->fromCollectionRequest($collectionRequest));
	}
	
	/**
	 * @param TingClientObjectRequest $objectRequest
	 * @return string The response body
	 */
	public function getObject(TingClientObjectRequest $objectRequest)
	{
		return $this->doRequest($this->httpRequestFactory->fromObjectRequest($objectRequest));
	}
	
	/**
	 * Private function used to prepare for and handle HTTP request/responses 
	 *
	 * @param TingClientHttpRequest $request
	 * @return The response body
	 */
	private function doRequest(TingClientHttpRequest $request)
	{
		$this->logger->log('Sending HTTP request to '.$request->getUrl(), TingClientLogger::DEBUG);
		return $this->request($request);
	}
	
	/**
	 * @param TingClientHttpRequest $request
	 * @return string The response body
	 */
	protected abstract function request(TingClientHttpRequest $request);
	
}