<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/mock/TingClientMockHttpRequestAdapter.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__) . '/../lib/adapter/response/json/TingClientJsonResponseAdapter.php';
require_once dirname(__FILE__) . '/../lib/TingClient.php';
require_once dirname(__FILE__) . '/../lib/search/TingClientSearchRequest.php';

class TingClientTest extends UnitTestCase {

	
	function __construct()
	{
	}
	
	function testRequest()
	{
		//End to end internal test
		$requestAdapter = new TingClientMockHttpRequestAdapter(new TingClientHttpRequestFactory('http://ting.dbc.dk'));
		$requestAdapter->setResponse(file_get_contents(dirname(__FILE__).'/examples/json/single.js'));
		$responseAdapter = new TingClientJsonResponseAdapter();

		$client = new TingClient($requestAdapter, $responseAdapter);
		$client->search(new TingClientSearchRequest('dc.title:danmark'));
		$this->assertNoErrors('Search should not throw errors!');
	}

}
