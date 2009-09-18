<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveObjectTest extends TingClientLiveZfTest
{
	
	function testCollectionRetrieval()
	{
		$searchRequest = $this->requestFactory->getSearchRequest();
		$searchRequest->setQuery('dc.title:danmark');
		$searchRequest->setNumResults(10);
		$searchResult = $this->client->execute($searchRequest);

		$this->assertTrue(sizeof($searchResult->collections) > 0, 'Search should return at least one result');
		
		$searchCollection = $searchResult->collections[0];
		$this->assertNotNull($searchCollection, 'Search should return at least one collection');
		
		$collectionRequest = $this->requestFactory->getCollectionRequest();
		$collectionRequest->setObjectId($searchCollection->objects[0]->id);
		$collection = $this->client->execute($collectionRequest);

		$this->assertEqual($searchCollection, $collection, 'Retrieved collection should be equal to search result');
	}
	
}
