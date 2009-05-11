<?php

require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/../../../search/TingClientSearchRequest.php';
require_once dirname(__FILE__).'/../../../log/TingClientVoidLogger.php';

class TingClientHttpRequestFactory
{
	private $baseUrl;
	
	/**
	 * @var TingClientLogger
	 */
	private $logger;
	
	function __construct($baseUrl, TingClientLogger $logger = NULL)
	{
		$this->baseUrl = $baseUrl;
		$this->logger = (isset($logger)) ? $logger : new TingClientVoidLogger();
	}
	
	function setLogger(TingClientLogger $logger)
	{
		$this->logger = $logger;
	}	
	
	/**
	 * @param TingClientSearchRequest $searchRequest
	 * @return TingClientHttpRequest
	 */
	function fromSearchRequest(TingClientSearchRequest $searchRequest)
	{
		$httpRequest = new TingClientHttpRequest();
		$httpRequest->setMethod(TingClientHttpRequest::GET);
		$httpRequest->setBaseUrl($this->baseUrl);
		$httpRequest->setGetParameter('action', 'searchRequest');
		
		$methodParameterMap = array('query' => 'query',
																'facets' => 'facets.facetName',
																'numFacets' => 'facets.number',
																'format' => 'format',
																'start' => 'start',
																'numResults' => 'stepValue',
																'output' => 'outputType'
																);
		
		foreach ($methodParameterMap as $method => $parameter)
		{
			$getter = 'get'.ucfirst($method);
			if ($value = $searchRequest->$getter())
			{
				$httpRequest->setParameter(TingClientHttpRequest::GET, $parameter, $value);
			}
		}
		
		return $httpRequest;
	}
	
}