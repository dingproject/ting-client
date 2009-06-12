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
	 * @param string $string
	 * @return TingClientScanResult
	 */
	public function scan($scanString)
	{
		$response = $this->requestAdapter->scan($scanString);
		return $this->responseAdapter->parseScanResult($response);
	}
	
}

?>