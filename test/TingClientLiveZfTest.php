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

		$this->client = new TingClient(	$requestAdapter, 
																		$responseAdapter, 
																		new TingClientSimpleTestLogger($this));
		
	}
	
	function testRequest()
	{
		//End to end test
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);
		
		$this->assertNoErrors('Search should not throw errors');
	}
	
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
	
	function testNumResults()
	{
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setNumResults(1);
		$searchRequest->setOutput('json');		
		$searchResult = $this->client->search($searchRequest);

		$this->assertNoErrors('Search should not throw errors');
		
		$this->assertEqual(sizeof($searchResult->records), 1, 'Returned number of results does not match requested number');						
	}
	
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
		foreach ($facet->terms as $facetTerm => $facetCount)
		{
			if ($facetCount < $searchResult->numTotalRecords)
			{
				$query = $searchRequest->getQuery();
				$query .= ' and '.$facet->name.':'.$facetTerm;
				break;
			}
		}
		$searchRequest->setQuery($query);
		
		$narrowedSearchResult = $this->client->search($searchRequest);
		
		$this->assertTrue($narrowedSearchResult->numTotalRecords < $searchResult->numTotalRecords, 'Total number of results in narrowed result ('.$narrowedSearchResult->numTotalRecords.') should be less than original result ('.$searchResult->numTotalRecords.')');
		$this->assertEqual($facetCount, $narrowedSearchResult->numTotalRecords, 'Number of results in narrowed search result ('.$narrowedSearchResult->numTotalRecords.') should be equal to count from narrowing facet term ('.$facetCount.')');
	}
}
