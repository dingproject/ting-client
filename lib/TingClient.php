<?php

$basePath = dirname(__FILE__);
require_once $basePath.'/adapter/request/TingClientRequestAdapter.php';
require_once $basePath.'/adapter/response/TingClientResponseAdapter.php';

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
	
	function __construct(TingClientRequestAdapter $requestAdapter,
						 TingClientResponseAdapter $responseAdapter)
	{
		$this->requestAdapter = $requestAdapter;
		$this->responseAdapter = $responseAdapter;
	}
	
	public function search(TingSearchRequest $searchRequest)
	{
		$response = $this->requestAdapter->search($searchRequest);
		return $this->responseAdapter->parseSearchResult($response);
	}
	
}

?>