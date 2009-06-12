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
		$httpRequest = $this->httpRequestFactory->fromSearchRequest($searchRequest);
		$this->logger->log('Sending search request to '.$httpRequest->getUrl(), TingClientLogger::INFO);
		return $this->request($httpRequest);
	}
	
	/**
	 * @param string $request
	 * @return string The response body
	 */
	public function scan($scanRequest)
	{
		$httpRequest = $this->httpRequestFactory->fromSearchRequest($searchRequest);
		$this->logger->log('Sending scan request to '.$httpRequest->getUrl(), TingClientLogger::INFO);
		return $this->request($httpRequest);
	}
	/**
	 * @param TingClientHttpRequest $request
	 * @return string The response body
	 */
	protected abstract function request(TingClientHttpRequest $request);
	
}