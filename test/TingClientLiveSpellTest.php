<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveSpellTest extends TingClientLiveZfTest
{
	
	function testSpelling()
	{
		$request = $this->requestFactory->getSpellRequest();
		$request->setWord('tilyke');
		$suggestions = $this->client->execute($request);
		
		$this->assertNoErrors('Spell request should not throw errors');

		$this->assertTrue(sizeof($suggestions) > 0, 'Suggestions should return at least one result');
	}
	
}