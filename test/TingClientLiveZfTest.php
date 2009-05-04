<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientZfHttpRequestAdapter.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__) . '/../lib/adapter/response/json/TingClientJsonResponseAdapter.php';
require_once dirname(__FILE__) . '/../lib/TingClient.php';
require_once dirname(__FILE__) . '/../lib/search/TingClientSearchRequest.php';

require_once 'Zend/Http/Client.php';

class TingClientLiveTest extends UnitTestCase {
	
	private $baseUrl = 'http://didicas.dbc.dk/opensearch/';

	/**
	 * @var TingClient
	 */
	private $client;
	
	function __construct()
	{
		$requestAdapter = new TingClientZfHttpRequestAdapter(	new Zend_Http_Client(),
																													new TingClientHttpRequestFactory($this->baseUrl));
		$responseAdapter = new TingClientJsonResponseAdapter();

		$this->client = new TingClient($requestAdapter, $responseAdapter);
		
	}
	
	function testRequest()
	{
		//End to end test
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);
		
		$this->assertNoErrors('Search should not throw errors');
	}

	function testNumResults()
	{
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setNumResults(1);
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);

		$this->assertNoErrors('Search should not throw errors');
		
		$this->assertEqual(sizeof($searchResult->getRecords()), 1, 'Returned number of results does not match requested number');						
	}
	
	function testFacets()
	{
		$facetName = 'dc.title';
		$numFacets = 1;
		
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setFacets('dc.title');
		$searchRequest->setNumFacets($numFacets);
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);

		$this->assertNoErrors('Search should not throw errors');
		
		$searchFacetFound = false;
		$facetResults = $searchResult->getFacets();
		$facet = array_shift($facetResults);
		$this->assertEqual($facet->getName(), $facetName, 'Expected facet used in search was not part of search result');
		$this->assertEqual(sizeof($facet->getTerms()), $numFacets, 'Expected number of facet terms does not match expected number');						
	}

}
