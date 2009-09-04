<?php

require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/../../../search/TingClientSearchRequest.php';
require_once dirname(__FILE__).'/../../../log/TingClientVoidLogger.php';

class TingClientHttpRequestFactory
{
	private $searchUrl;
	private $scanUrl;
	
	/**
	 * @var TingClientLogger
	 */
	private $logger;
	
	function __construct($searchUrl, $scanUrl, TingClientLogger $logger = NULL)
	{
		$this->searchUrl = $searchUrl;
		$this->scanUrl = $scanUrl;
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
		$httpRequest->setBaseUrl($this->searchUrl);
		$httpRequest->setGetParameter('action', 'searchRequest');
		
		$methodParameterMap = array('query' => 'query',
																'facets' => 'facets.facetName',
																'numFacets' => 'facets.numberOfTerms',
																'format' => 'format',
																'start' => 'start',
																'numResults' => 'stepValue',
																'allObjects' => 'allObjects',
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
	
	/**
	 * @param TingClientScanRequest $scanRequest
	 * @return TingClientHttpRequest
	 */
	function fromScanRequest(TingClientScanRequest $scanRequest)
	{
		$httpRequest = new TingClientHttpRequest();
		$httpRequest->setMethod(TingClientHttpRequest::GET);
		$httpRequest->setBaseUrl($this->scanUrl);
		
		$methodParameterMap = array('field' => 'field',
																'prefix' => 'prefix',
																'numResults' => 'limit',
																'lower' => 'lower',
																'upper' => 'upper',
																'minFrequency' => 'minFrequency',
																'maxFrequency' => 'maxFrequency',
																'output' => 'outputType'
																);
		
		foreach ($methodParameterMap as $method => $parameter)
		{
			$getter = 'get'.ucfirst($method);
			if ($value = $scanRequest->$getter())
			{
				$httpRequest->setParameter(TingClientHttpRequest::GET, $parameter, $value);
			}
		}
		
		return $httpRequest;
	}

	function fromCollectionRequest(TingClientCollectionRequest $collectionRequest)
	{
		$searchRequest = new TingClientSearchRequest('fedoraPid:'.str_replace(':', '?', $collectionRequest->getObjectId()));
		$searchRequest->setAllObjects(true); 
		$searchRequest->setOutput($collectionRequest->getOutput());
		return $this->fromSearchRequest($searchRequest);
	}
	
	function fromObjectRequest(TingClientObjectRequest $objectRequest)
	{		
		$searchRequest = new TingClientSearchRequest('fedoraPid:'.str_replace(':', '?', $objectRequest->getId()));
		$searchRequest->setOutput($objectRequest->getOutput());
		$searchRequest->setNumResults(1);
		return $this->fromSearchRequest($searchRequest);
	}
	
}