<?php

require_once dirname(__FILE__).'/adapter/request/TingClientRequestAdapter.php';
require_once dirname(__FILE__).'/adapter/response/TingClientResponseAdapter.php';

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
	
	public function search(TingClientSearchRequest $searchRequest)
	{
		$response = $this->requestAdapter->search($searchRequest);
		return $this->responseAdapter->parseSearchResult($response);
	}
	
}

?>