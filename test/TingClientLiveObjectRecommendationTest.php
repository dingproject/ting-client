<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveObjectRecommendationTest extends TingClientLiveZfTest
{
	
	function testObjectRecommendations()
	{
		$request = $this->requestFactory->getObjectRecommendationRequest();
		$request->setIsbn('9788700398368');
		$recommendations = $this->client->execute($request);

		$this->assertTrue(sizeof($recommendations) > 0, 'Recommendations should return at least one result');
	}

  function testNumResults()
  {
    $request = $this->requestFactory->getObjectRecommendationRequest();
    $request->setIsbn('9788700398368');
    $request->setNumResults(3);
    $recommendations = $this->client->execute($request);
		
    $this->assertTrue(sizeof($recommendations) == 3, 'Recommendations should return 3 results.');

    $recommendation = array_pop($recommendations);
    $this->assertTrue(isset($recommendation->localId) && $recommendation->localId, 'Recommendations should have a local id');
  }	
	
}
