<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/http/TingClientZfHttpRequestAdapter.php';
require_once dirname(__FILE__) . '/../lib/request/rest-json/RestJsonTingClientRequestFactory.php';
require_once dirname(__FILE__) . '/../lib/TingClient.php';
require_once dirname(__FILE__) . '/../lib/log/TingClientSimpleTestLogger.php';

require_once 'Zend/Http/Client.php';

class TingClientLiveZfTest extends UnitTestCase {

/*
  //  LIVE urls: protected
	private $searchUrl = 'http://opensearch.addi.dk/opensearch/0.14/';
	private $scanUrl = 'http://openscan.addi.dk/1.7/';
	private $recommendationUrl = 'http://openadhl.addi.dk/1.1/';
	private $spellUrl = 'http://openspell.addi.dk/1.2/';
*/

  // test-urls
  private $searchUrl = 'http://didicas.dbc.dk/opensearch_nextkilde/';
  private $scanUrl = 'http://didicas.dbc.dk/openscan_nextkilde/';
  private $recommendationUrl = 'http://openadhl.addi.dk/1.1/';
  private $spellUrl = 'http://didicas.dbc.dk/opensearch_nextkilde/';


	/**
	 * @var TingClient
	 */
	protected $client;

	/**
	 * @var TingClientRequestFactory
	 */
	protected $requestFactory;

	function __construct()
	{
		$this->requestFactory = new RestJsonTingClientRequestFactory(
																array('search' => $this->searchUrl,
																			'scan' => $this->scanUrl,
																			'collection' => $this->searchUrl,
																			'object' => $this->searchUrl,
																			'recommendation' => $this->recommendationUrl,
																			'spell' => $this->spellUrl));

		$requestAdapter = new TingClientZfHttpRequestAdapter(new Zend_Http_Client());

		$this->client = new TingClient(	$requestAdapter,
																		new TingClientSimpleTestLogger($this));

	}

}
