<?php

class TingClientRequestFactory {
	public function __construct($urls) {
		$this->urls = $urls;
	}

	/**
	 * @return TingClientSearchRequest
	 */
	public function getSearchRequest() {
		return new TingClientSearchRequest($this->urls['search']);
	}

	/**
	 * @return TingClientScanRequest
	 */
	public function getScanRequest() {
		return new TingClientScanRequest($this->urls['scan']);
	}

	/**
	 * @return TingClientCollectionRequest
	 */
	public function getCollectionRequest() {
		return new TingClientCollectionRequest($this->urls['collection']);
	}

	/**
	 * @return TingClientObjectRequest
	 */
	public function getObjectRequest() {
		return new TingClientObjectRequest($this->urls['object']);
	}

	/**
	 * @return TingClientSpellRequest
	 */
	public function getSpellRequest() {
		return new TingClientSpellRequest($this->urls['spell']);
	}

	/**
	 * @return TingClientObjectRecommendationRequest
	 */
	function getObjectRecommendationRequest() {
		return new TingClientObjectRecommendationRequest($this->urls['recommendation']);
	}
}

