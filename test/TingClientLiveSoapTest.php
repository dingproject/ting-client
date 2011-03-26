<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/soap/TingClientSoapRequestAdapter.php';
require_once dirname(__FILE__) . '/../lib/request/xml/XmlTingClientRequestFactory.php';
require_once dirname(__FILE__) . '/../lib/TingClient.php';
require_once dirname(__FILE__) . '/../lib/log/TingClientSimpleTestLogger.php';

class TingClientLiveSoapTest extends UnitTestCase {

  // WSDL URLs.
  const SEARCH_URL = 'http://opensearch.addi.dk/1.1/opensearch.wsdl';
  const SCAN_URL = 'http://didicas.dbc.dk/openscan_nextkilde/';
  const RECOMMENDATION_URL = 'http://openadhl.addi.dk/1.1/';
  const SPELL_URL = 'http://didicas.dbc.dk/opensearch_nextkilde/';

  /**
   * @var TingClient
   */
  protected $client;

  /**
   * @var TingClientRequestFactory
   */
  protected $requestFactory;

  function __construct() {
    $this->requestFactory = new XmlTingClientRequestFactory(array(
      'search' => self::SEARCH_URL,
      'scan' => self::SCAN_URL,
      'collection' => self::SEARCH_URL,
      'object' => self::SEARCH_URL,
      'recommendation' => self::RECOMMENDATION_URL,
      'spell' => self::SPELL_URL,
    ));

    $requestAdapter = new TingClientSoapRequestAdapter();

    $this->client = new TingClient($requestAdapter, new TingClientSimpleTestLogger($this));
  }
}
