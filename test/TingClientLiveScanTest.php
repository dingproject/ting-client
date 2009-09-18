<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveTest extends TingClientLiveZfTest {
	
	function testScan()
	{
		$scanRequest = $this->requestFactory->getScanRequest();
		$scanRequest->setField('title');
		$scanRequest->setLower('København');
		$scanResult = $this->client->execute($scanRequest);
		
		$this->assertNoErrors('Scan should not throw errors');
	}
	
	function testScanResult()
	{
		$query = 'København';
		$numResults = 3;
		
		$scanRequest = $this->requestFactory->getScanRequest();
		$scanRequest->setField('title');
		$scanRequest->setLower($query);
		$scanRequest->setNumResults($numResults);
		$scanResult = $this->client->execute($scanRequest);
		
		$this->assertEqual(sizeof($scanResult->terms), $numResults, 'Returned number of results does not match expected number of results');

		foreach ($scanResult->terms as $term)
		{
			$this->assertTrue(strpos($term->name, $query) === 0, 'Returned term '.$term->name.' does not match requested prefix '.$query);
		}
	}
	
	function testScanDcCaps()
	{
		$query = 'Rest';
		
		$scanRequest = $this->requestFactory->getScanRequest();
		$scanRequest->setField('dc.title');
		$scanRequest->setLower($query);
		$scanRequest->setNumResults(3);
		$scanResult = $this->client->execute($scanRequest);
		
		$this->assertNoErrors('Scan should not throw errors');
		
		foreach ($scanResult->terms as $term)
		{
			$this->assertTrue(strpos($term->name, $query) === 0, 'Returned term '.$term->name.' does not match requested prefix '.$query);
		}
	}
}
