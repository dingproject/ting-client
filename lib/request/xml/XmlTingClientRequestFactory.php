<?php

require_once dirname(__FILE__) . '/../TingClientRequestFactory.php';
require_once dirname(__FILE__).'/XmlTingClientSearchRequest.php';
require_once dirname(__FILE__).'/XmlTingClientScanRequest.php';
require_once dirname(__FILE__).'/XmlTingClientObjectRequest.php';
require_once dirname(__FILE__).'/XmlTingClientCollectionRequest.php';
require_once dirname(__FILE__).'/XmlTingClientObjectRecommendationRequest.php';
require_once dirname(__FILE__).'/XmlTingClientSpellRequest.php';

class XmlTingClientRequestFactory implements TingClientRequestFactory
{
	private $urls = array();

	public function __construct($urls)
	{
		$this->urls = $urls;
	}

	/**
	 * @return XmlTingClientSearchRequest
	 */
	public function getSearchRequest()
	{
		return new XmlTingClientSearchRequest($this->urls['search']);
	}

	/**
	 * @return XmlTingClientScanRequest
	 */
	public function getScanRequest()
	{
		return new XmlTingClientScanRequest($this->urls['scan']);
	}

	/**
	 * @return XmlTingClientCollectionRequest
	 */
	public function getCollectionRequest()
	{
		return new XmlTingClientCollectionRequest($this->urls['collection']);
	}

	/**
	 * @return XmlTingClientObjectRequest
	 */
	public function getObjectRequest()
	{
		return new XmlTingClientObjectRequest($this->urls['object']);
	}

	/**
	 * @return XmlTingClientSpellRequest
	 */
	public function getSpellRequest()
	{
		return new XmlTingClientSpellRequest($this->urls['spell']);
	}

	/**
	 * @return XmlTingClientObjectRecommendationRequest
	 */
	function getObjectRecommendationRequest()
	{
		return new XmlTingClientObjectRecommendationRequest($this->urls['recommendation']);
	}
}

