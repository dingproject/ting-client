<?php
/**
 * @file
 * TingClientRequestFactory class definition.
 */

/**
 * Factory class for getting the correct request object for any service.
 */
class TingClientRequestFactory {
  public $urls;

  /**
   * Create the request factury with the service URLs to used.
   */
  public function __construct($urls) {
    $this->urls = $urls;
  }

  /**
   * Get OpenSearch request.
   *
   * @return TingClientSearchRequest
   *   Request object.
   */
  public function getSearchRequest() {
    if (isset($this->urls['search'])) {
      return new TingClientSearchRequest($this->urls['search']);
    }
  }

  /**
   * Get OpenScan request.
   *
   * @return TingClientScanRequest
   *   Request object.
   */
  public function getScanRequest() {
    if (isset($this->urls['scan'])) {
      return new TingClientScanRequest($this->urls['scan']);
    }
  }

  /**
   * Get OpenScan collection request.
   *
   * @return TingClientCollectionRequest
   *   Request object.
   */
  public function getCollectionRequest() {
    if (isset($this->urls['collection'])) {
      return new TingClientCollectionRequest($this->urls['collection']);
    }
  }

  /**
   * Get OpenScan object request.
   *
   * @return TingClientObjectRequest
   *   Request object.
   */
  public function getObjectRequest() {
    if (isset($this->urls['object'])) {
      return new TingClientObjectRequest($this->urls['object']);
    }
  }

  /**
   * Get OpenSpell object request.
   *
   * @return TingClientSpellRequest
   *   Request object.
   */
  public function getSpellRequest() {
    if (isset($this->urls['spell'])) {
      return new TingClientSpellRequest($this->urls['spell']);
    }
  }

  /**
   * Get OpenADHL object request.
   *
   * @return TingClientObjectRecommendationRequest
   *   Request object.
   */
  public function getObjectRecommendationRequest() {
    if (isset($this->urls['recommendation'])) {
      return new TingClientObjectRecommendationRequest($this->urls['recommendation']);
    }
  }
}
