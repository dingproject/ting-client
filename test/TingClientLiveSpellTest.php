<?php

require_once dirname(__FILE__) . '/TingClientLiveSoapTest.php';

class TingClientLiveSpellTest extends TingClientLiveSoapTest
{

	function testSpelling()
	{
		$request = $this->requestFactory->getSpellRequest();
		$request->setWord('terasse'); // Should be 'terrasse', but gives lots of other fun suggestions.
		$request->setNumResults(3);
		$suggestions = $this->client->execute($request);

		$this->assertNoErrors('Spell request should not throw errors');

		$this->assertEqual(sizeof($suggestions), 3, 'Suggestions should return the requested number of results');
	}

}
