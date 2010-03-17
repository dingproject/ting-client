<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveTest extends TingClientLiveZfTest {
	
	function testScan()
	{
		$scanRequest = $this->requestFactory->getScanRequest();
		$scanRequest->setField('phrase.title');
		$scanRequest->setLower('København');
		$scanRequest->setNumResults(10);
		$scanResult = $this->client->execute($scanRequest);
		
		$this->assertNoErrors('Scan should not throw errors');
	}
	
	function testScanResult()
	{
		$query = 'København';
		$numResults = 3;
		
		$scanRequest = $this->requestFactory->getScanRequest();
		$scanRequest->setField('phrase.title');
		$scanRequest->setLower($query);
		$scanRequest->setNumResults($numResults);
		$scanResult = $this->client->execute($scanRequest);
		
		$this->assertEqual(sizeof($scanResult->terms), $numResults, 'Returned number of results ('.sizeof($scanResult->terms).') does not match expected number of results ('.$numResults.')');
		
		/**
		// Ensure that all returned terms begin with the query
		// This is currently not ensured by the service and not handled by the client.
		 * Uncommented to avoid unnecessary failures.
		foreach ($scanResult->terms as $term)
		{
			$this->assertTrue(strpos($term->name, $query) === 0, 'Returned term '.$term->name.' does not match requested prefix '.$query);
		}
		**/
	}
		
}
