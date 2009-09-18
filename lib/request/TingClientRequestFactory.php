<?php

require_once dirname(__FILE__).'/base/TingClientSearchRequest.php';
require_once dirname(__FILE__).'/base/TingClientScanRequest.php';
require_once dirname(__FILE__).'/base/TingClientObjectRequest.php';
require_once dirname(__FILE__).'/base/TingClientCollectionRequest.php';

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
	 * @return TingClientRequest
	 */
	function getSpellRequest();
	
	/**
	 * @return TingClientRequest
	 */
	function getAdhlRequest();
	
}