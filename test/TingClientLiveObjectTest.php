<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveCollectionTest extends TingClientLiveZfTest
{
	
	/**
	 * Test to retrieve an object with an object id from a search result, 
	 * perform a separate query for this id and ensure that the result is equal
	 * to the original object.
	 */
	function testObjectRetrieval()
	{
		$searchRequest = $this->requestFactory->getSearchRequest();
		$searchRequest->setQuery('dc.title:danmark');
		$searchRequest->setNumResults(1);
		$searchResult = $this->client->execute($searchRequest);
		$this->assertTrue(sizeof($searchResult->collections) > 0, 'Search should return at least one result');
		
		$searchObject = $searchResult->collections[0]->objects[0];
		$this->assertNotNull($searchObject, 'Search should return at least one collection containing one object');
		
		$objectRequest = $this->requestFactory->getObjectRequest();
		$objectRequest->setObjectId($searchObject->id);
    $searchRequest->setNumResults(1);
		$object = $this->client->execute($objectRequest);

		$this->assertEqual($searchObject, $object, 'Retrieved object should be equal to search result');
	}
	
	function testObjectRetrievalByLocalId()
	{
		$searchRequest = $this->requestFactory->getSearchRequest();
		$searchRequest->setQuery('dc.title:danmark');
    $searchRequest->setNumResults(1);
		$searchResult = $this->client->execute($searchRequest);

		$this->assertTrue(sizeof($searchResult->collections) > 0, 'Search should return at least one result');
		
		$localId = null;
		$searchObject = null;
		foreach ($searchResult->collections as $collection)
		{
			foreach ($collection->objects as $object)
			{
				if ($object->localId)
				{
					$localId = $object->localId;
					$searchObject = $object;
					break 2;
				}
			}
		}
		$this->assertNotNull($localId, 'Search should return at least one object containing a local id');
		
		$objectRequest = $this->requestFactory->getObjectRequest();
		$objectRequest->setLocalId($localId);
		$object = $this->client->execute($objectRequest);

		$this->assertEqual($searchObject, $object, 'Retrieved object should be equal to search result');
	}
	
}
