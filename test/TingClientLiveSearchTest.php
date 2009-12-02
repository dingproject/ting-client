<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveSearchTest extends TingClientLiveZfTest {
  
  /**
   * Test sending a request.
   */
  function testRequest()
  {
    //End to end test
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('dc.title:danmark');
    $searchResult = $this->client->execute($searchRequest);
    
    $this->assertNoErrors('Search should not throw errors');
  }
  
  /**
   * Test support for international characters in queries.
   */
  function testRequestInternationalChars()
  {
    //Test using international characters ÆØÅ
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('dc.title:blåbærgrød');
    $searchResult = $this->client->execute($searchRequest);
    
    $this->assertNoErrors('Search should not throw errors');
    
    //Æ as first character
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('dc.title:æblegrød');
    $searchResult = $this->client->execute($searchRequest);
    
    $this->assertNoErrors('Search should not throw errors');
  }
    
  /**
   * Test support for specifying search result size.
   */
  function testNumResults()
  {
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('dc.title:danmark');
    $searchRequest->setNumResults(1);
    $searchResult = $this->client->execute($searchRequest);
  
    $this->assertNoErrors('Search should not throw errors');
    
    $this->assertEqual(sizeof($searchResult->numTotalCollections), 1, 'Returned collection count does not match requested number');            
    $this->assertEqual(sizeof($searchResult->collections), 1, 'Returned number of results does not match requested number');            
  }
  
  /**
   * Test to ensure support for handling facets and number of facet terms in search requests.
   */
  function testFacet()
  {
    $facetName = 'dc.title';
    $numFacets = 1;
    
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('dc.title:danmark');
    $searchRequest->setFacets($facetName);
    $searchRequest->setNumFacets($numFacets);
    $searchResult = $this->client->execute($searchRequest);
  
    $this->assertNoErrors('Search should not throw errors');
    
    $searchFacetFound = false;
    $facetResults = $searchResult->facets;
    $this->assertEqual(sizeof($facetResults), 1, 'Search should return 1 facet');
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
    
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('dc.title:danmark');
    $searchRequest->setFacets($facetNames);
    $searchRequest->setNumFacets($numFacets);
    $searchResult = $this->client->execute($searchRequest);
  
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
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('dc.title:danmark');
    $searchRequest->setFacets(array('dc.creator'));
    $searchRequest->setNumFacets(10);
    $searchResult = $this->client->execute($searchRequest);
    
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
    
    $narrowedSearchResult = $this->client->execute($searchRequest);
        
    $this->assertTrue($narrowedSearchResult->numTotalObjects < $searchResult->numTotalObjects, 'Total number of results in narrowed result ('.$narrowedSearchResult->numTotalObjects.') should be less than original result ('.$searchResult->numTotalObjects.')');
    $this->assertEqual($facetCount, $narrowedSearchResult->numTotalObjects, 'Number of results in narrowed search result ('.$narrowedSearchResult->numTotalObjects.') should be equal to count from narrowing facet term ('.$facetCount.')');
  }
  
  function testEmptyFacets()
  {
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('ostemadder');
    $searchRequest->setFacets(array('dc.creator'));
    $searchRequest->setNumFacets(10);
    $searchResult = $this->client->execute($searchRequest);
    
    $this->assertNoErrors('Search should not throw errors');
    
    foreach ($searchResult->facets as $facet)
    {
      foreach ($facet->terms as $term => $count)
      {
        $this->assertTrue($count > 0, 'Count for facet '.$facet->name.' term '.$term.' shound not be 0');
      }
    }
  }
  
  function testAgency()
  {
    $agency = 775100;
    
    $searchRequest = $this->requestFactory->getSearchRequest();
    $searchRequest->setQuery('Harry Potter');
    $searchRequest->setAgency(775100);
    $searchResult = $this->client->execute($searchRequest);
  
    $this->assertNoErrors('Search should not throw errors');
  
    $this->assertTrue(sizeof($searchResult->collections) > 0, 'Search with agency should return collections');
  } 
  
}
