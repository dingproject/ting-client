<?php

require_once dirname(__FILE__) . '/TingClientLiveZfTest.php';

class TingClientLiveSpellTest extends TingClientLiveZfTest
{
	
	function testSpelling()
	{
		$request = $this->requestFactory->getSpellRequest();
		$request->setWord('tilyke');
		$request->setNumResults(3);
		$suggestions = $this->client->execute($request);

		$this->assertNoErrors('Spell request should not throw errors');

		$this->assertEqual(sizeof($suggestions), 3, 'Suggestions should return the requested number of results');
	}
	
}