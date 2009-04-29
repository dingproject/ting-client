<?php

require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/../../../search/TingClientSearchRequest.php';

class TingClientHttpRequestFactory
{
	private $baseUrl;
	
	function __construct($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}
	
	/**
	 * @param TingClientSearchRequest $searchRequest
	 * @return TingClientHttpRequest
	 */
	function fromSearchRequest(TingClientSearchRequest $searchRequest)
	{
		$httpRequest = new TingClientHttpRequest();
		$httpRequest->setMethod(TingClientHttpRequest::GET);
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