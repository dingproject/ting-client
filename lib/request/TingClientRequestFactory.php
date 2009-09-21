<?php

require_once dirname(__FILE__).'/base/TingClientSearchRequest.php';
require_once dirname(__FILE__).'/base/TingClientScanRequest.php';
require_once dirname(__FILE__).'/base/TingClientObjectRequest.php';
require_once dirname(__FILE__).'/base/TingClientCollectionRequest.php';
require_once dirname(__FILE__).'/base/TingClientObjectRecommendationRequest.php';
require_once dirname(__FILE__).'/base/TingClientSpellRequest.php';

interface TingClientRequestFactory {
	
	/**
	 * @return TingClientSearchRequest
	 */
	function getSearchRequest();
	
	/**
	 * @return TingClientScanRequest
	 */
	function getScanRequest();
	
	/**
	 * @return TingClientCollectionRequest
	 */
	function getCollectionRequest();
	
	/**
	 * @return TingClientObjectRequest
	 */
	function getObjectRequest();
	
	/**
	 * @return TingClientSpellRequest
	 */
	function getSpellRequest();
	
	/**
	 * @return TingClientObjectRecommendationRequest
	 */
	function getObjectRecommendationRequest();
	
}