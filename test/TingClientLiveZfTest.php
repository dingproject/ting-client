<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientZfHttpRequestAdapter.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__) . '/../lib/adapter/response/json/TingClientJsonResponseAdapter.php';
require_once dirname(__FILE__) . '/../lib/TingClient.php';
require_once dirname(__FILE__) . '/../lib/search/TingClientSearchRequest.php';
require_once dirname(__FILE__) . '/../lib/log/TingClientSimpleTestLogger.php';

require_once 'Zend/Http/Client.php';

class TingClientLiveTest extends UnitTestCase {
	
	private $searchUrl = 'http://didicas.dbc.dk/opensearch_0.2/';
	private $scanUrl = 'http://didicas.dbc.dk/openscan/server.php';
	
	/**
	 * @var TingClient
	 */
	private $client;
	
	function __construct()
	{
		$requestAdapter = new TingClientZfHttpRequestAdapter(	new Zend_Http_Client(),
																													new TingClientHttpRequestFactory($this->searchUrl, $this->scanUrl));
		$responseAdapter = new TingClientJsonResponseAdapter();

		$this->client = new TingClient(	$requestAdapter, 
																		$responseAdapter, 
																		new TingClientSimpleTestLogger($this));
		
	}
	
	/**
	 * Test sending a request.
	 */
	function testRequest()
	{
		//End to end test
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);
		
		$this->assertNoErrors('Search should not throw errors');
	}
	
	/**
	 * Test support for international characters in queries.
	 */
	function testRequestInternationalChars()
	{
		//Test using international characters ÆØÅ
		$searchRequest = new TingClientSearchRequest('dc.title:blåbærgrød');
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);
		
		$this->assertNoErrors('Search should not throw errors');
		
		//Æ as first character
		$searchRequest = new TingClientSearchRequest('dc.title:æblegrød');
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);
		
		$this->assertNoErrors('Search should not throw errors');
		
	}

	/**
	 * Test support for specifying search result size.
	 */
	function testNumResults()
	{
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setNumResults(1);
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);

		$this->assertNoErrors('Search should not throw errors');
		
		$this->assertEqual(sizeof($searchResult->collections), 1, 'Returned number of results does not match requested number');						
	}
	
	/**
	 * Test to ensure support for handling facets and number of facet terms in search requests.
	 */
	function testFacet()
	{
		$facetName = 'dc.title';
		$numFacets = 1;
		
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setFacets($facetName);
		$searchRequest->setNumFacets($numFacets);
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);

		$this->assertNoErrors('Search should not throw errors');
		
		$searchFacetFound = false;
		$facetResults = $searchResult->facets;
		$facet = array_shift($facetResults);
		$this->assertEqual($facet->name, $facetName, 'Expected facet used in search was not part of search result');
		$this->assertEqual(sizeof($facet->terms), $numFacets, 'Returned number of facet terms does not match expected number');						
	}
	
	/**
	 * Test to ensure support for handling several facets and facet terms in search requests.
	 */
	function testMultipleFacets()
	{
		$facetNames = array('dc.title', 'dc.creator', 'dc.subject');
		$numFacets = 3;
		
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setFacets($facetNames);
		$searchRequest->setNumFacets($numFacets);
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);

		$this->assertNoErrors('Search should not throw errors');
		
		$facetResults = $searchResult->facets;
		$this->assertEqual(sizeof($facetResults), sizeof($facetNames), 'Returned number of facets does not match expected number');
		foreach ($facetResults as $facetResult)
		{
			$this->assertTrue(in_array($facetResult->name, $facetNames), 'Returned facet '.$facetResult->name.' was not part of expected facets');
			$this->assertEqual(sizeof($facetResult->terms), $numFacets, 'Returned number of facet terms for '.$facetResult->name.' does not match expected number');
		}					
	}
	
	/**
	 * Test to check that when adding a facet to a query the result is smaller than
	 * the original result set.
	 */
	function testFacetNarrowing()
	{
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setFacets(array('dc.creator'));
		$searchRequest->setNumFacets(10);
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);
		
		$this->assertNoErrors('Search should not throw errors');
		
		$facetCount = 0;
		$facet = array_shift($searchResult->facets);
		
		$query = '';
		foreach ($facet->terms as $facetTerm => $facetCount)
		{
			if ($facetCount < $searchResult->numTotalObjects)
			{
				$query = $searchRequest->getQuery();
				$query .= ' AND '.$facet->name.':'.$facetTerm;
				break;
			}
		}
		$searchRequest->setQuery($query);
		
		$narrowedSearchResult = $this->client->search($searchRequest);
				
		$this->assertTrue($narrowedSearchResult->numTotalObjects < $searchResult->numTotalObjects, 'Total number of results in narrowed result ('.$narrowedSearchResult->numTotalObjects.') should be less than original result ('.$searchResult->numTotalObjects.')');
		$this->assertEqual($facetCount, $narrowedSearchResult->numTotalObjects, 'Number of results in narrowed search result ('.$narrowedSearchResult->numTotalObjects.') should be equal to count from narrowing facet term ('.$facetCount.')');
	}
	
	/**
	 * Test to retrieve an object with an object id from a search result, 
	 * perform a separate query for this id and ensure that the result is equal
	 * to the original object.
	 */
	function testObjectRetrieval()
	{
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);

		$this->assertTrue(sizeof($searchResult->collections) > 0, 'Search should return at least one result');
		
		$searchObject = $searchResult->collections[0]->objects[0];
		$this->assertNotNull($searchObject, 'Search should return at least one collection containing one object');
		
		$objectRequest = new TingClientObjectRequest($searchObject->id, 'json');
		$object = $this->client->getObject($objectRequest);

		$this->assertEqual($searchObject, $object, 'Retrieved object should be equal to search result');
	}
	
	function testCollectionRetrieval()
	{
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setOutput('json');		
		$searchRequest->setNumResults(10);
		$searchResult = $this->client->search($searchRequest);

		$this->assertTrue(sizeof($searchResult->collections) > 0, 'Search should return at least one result');
		
		$searchCollection = $searchResult->collections[0];
		$this->assertNotNull($searchCollection, 'Search should return at least one collection');
		
		$collectionRequest = new TingClientCollectionRequest('dc.title:danmark', 'json');
		$collection = $this->client->getCollection($collectionRequest);

		$this->assertEqual($searchCollection, $collection, 'Retrieved collection should be equal to search result');
	}
	
	function testScan()
	{
		$scanRequest = new TingClientScanRequest();
		$scanRequest->setField('title');
		$scanRequest->setLower('København');
		$scanRequest->setOutput('json');
		$scanResult = $this->client->scan($scanRequest);
		
		$this->assertNoErrors('Scan should not throw errors');
	}
	
	function testScanResult()
	{
		$query = 'København';
		$numResults = 10;
		
		$scanRequest = new TingClientScanRequest();
		$scanRequest->setField('title');
		$scanRequest->setLower($query);
		$scanRequest->setNumResults($numResults);
		$scanRequest->setOutput('json');
		$scanResult = $this->client->scan($scanRequest);
		
		$this->assertEqual(sizeof($scanResult->terms), 10, 'Returned number of results does not match expected number of results');

		foreach ($scanResult->terms as $term)
		{
			$this->assertTrue(strpos($term->name, $query) === 0, 'Returned term '.$term->name.' does not match requested prefix '.$query);
		}
	}
	
	function testScanDcCaps()
	{
		$query = 'Rest';
		
		$scanRequest = new TingClientScanRequest();
		$scanRequest->setField('dc.title');
		$scanRequest->setLower($query);
		$scanRequest->setOutput('json');
		$scanResult = $this->client->scan($scanRequest);
		
		$this->assertNoErrors('Scan should not throw errors');
		
		foreach ($scanResult->terms as $term)
		{
			$this->assertTrue(strpos($term->name, $query) === 0, 'Returned term '.$term->name.' does not match requested prefix '.$query);
		}
	}
}
