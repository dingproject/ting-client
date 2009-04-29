<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientZfHttpRequestAdapter.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__) . '/../lib/adapter/response/json/TingClientJsonResponseAdapter.php';
require_once dirname(__FILE__) . '/../lib/TingClient.php';
require_once dirname(__FILE__) . '/../lib/search/TingClientSearchRequest.php';

require_once 'Zend/Http/Client.php';

class TingClientLiveTest extends UnitTestCase {
	
	private $baseUrl = 'http://didicas.dbc.dk/opensearch/';
	
	function testRequest()
	{
		//End to end test
		$requestAdapter = new TingClientZfHttpRequestAdapter(	new Zend_Http_Client(),
																													new TingClientHttpRequestFactory($this->baseUrl));
		$responseAdapter = new TingClientJsonResponseAdapter();

		$client = new TingClient($requestAdapter, $responseAdapter);

		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		$searchRequest->setOutput('json');
		
		$searchResult = $client->search($searchRequest);
		
		$this->assertNoErrors('Search should not throw errors!');
	}

}
