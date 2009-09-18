<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveObjectRecommendationTest extends TingClientLiveZfTest
{
	
	function testObjectRecommendations()
	{
		$request = $this->requestFactory->getObjectRecommendationRequest();
		$request->setIsbn('978-87-7053-256-3');
		$recommendations = $this->client->execute($request);

		$this->assertTrue(sizeof($recommendations) > 0, 'Recommendations should return at least one result');
	}
	
}
