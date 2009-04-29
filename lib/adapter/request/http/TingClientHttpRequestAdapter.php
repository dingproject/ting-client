<?php

require_once dirname(__FILE__).'/../TingClientRequestAdapter.php';
require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__).'/../../../search/TingClientSearchRequest.php';

abstract class TingClientHttpRequestAdapter implements TingClientRequestAdapter
{
		
	/**
	 * @var TingClientHttpRequestFactory
	 */
	private $httpRequestFactory;

	function __construct(TingClientHttpRequestFactory $httpRequestFactory)
	{
		$this->httpRequestFactory = $httpRequestFactory;
	}
	
	public function setHttpRequestFactory(TingClientHttpRequestFactory $httpRequestFactory)
	{
		$this->httpRequestFactory = $httpRequestFactory;
	}
	
	/**
	 * @param TingClientSearchRequest $request
	 * @return string The response body
	 */
	public function search(TingClientSearchRequest $searchRequest)
	{
		return $this->request($this->httpRequestFactory->fromSearchRequest($searchRequest));
	}

	/**
	 * @param TingClientHttpRequest $request
	 * @return string The response body
	 */
	protected abstract function request(TingClientHttpRequest $request);
	
}