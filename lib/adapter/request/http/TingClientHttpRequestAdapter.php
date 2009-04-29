<?php

$basePath = dirname(__FILE__);
require_once $basePath.'/../TingClientHttpRequest.php';
require_once $basePath.'/../TingClientHttpRequestAdapter.php';
require_once $basePath.'/../TingClientHttpRequestFactory.php';
require_once $basePath.'/../../../search/TingClientSearchRequest.php';

abstract class TingClientHttpRequestAdapter implements TingClientRequestAdapter
{

	/**
	 * @var TingClientHttpRequestFactory
	 */
	private $httpRequestFactory;
	
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
	protected function request(TingClientHttpRequest $request);
	
}