<?php

require_once dirname(__FILE__) . '/../TingClientRequestFactory.php';
require_once dirname(__FILE__).'/RestJsonTingClientSearchRequest.php';
require_once dirname(__FILE__).'/RestJsonTingClientScanRequest.php';
require_once dirname(__FILE__).'/RestJsonTingClientObjectRequest.php';
require_once dirname(__FILE__).'/RestJsonTingClientCollectionRequest.php';
require_once dirname(__FILE__).'/RestJsonTingClientObjectRecommendationRequest.php';

class RestJsonTingClientRequestFactory implements TingClientRequestFactory
{
	private $urls = array();
	
	public function __construct($urls)
	{
		$this->urls = $urls;
	}
	
	/**
	 * @return RestJsonTingClientSearchRequest
	 */
	public function getSearchRequest()
	{
		return new RestJsonTingClientSearchRequest($this->urls['search']);
	}

	/**
	 * @return RestJsonTingClientScanRequest
	 */	
	public function getScanRequest()
	{
		return new RestJsonTingClientScanRequest($this->urls['scan']);
	}
	
	/**
	 * @return RestJsonTingClientCollectionRequest
	 */
	public function getCollectionRequest()
	{
		return new RestJsonTingClientCollectionRequest($this->urls['collection']);
	}
	
	/**
	 * @return RestJsonTingClientObjectRequest
	 */	
	public function getObjectRequest()
	{
		return new RestJsonTingClientObjectRequest($this->urls['object']);
	}
	
	public function getSpellRequest()
	{
		
	}
	
	/**
	 * @return RestJsonTingClientObjectRecommendationRequest
	 */
	function getObjectRecommendationRequest()
	{
		return new RestJsonTingClientObjectRecommendationRequest($this->urls['recommendation']);
	}

}
