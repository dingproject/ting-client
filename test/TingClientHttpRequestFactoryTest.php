<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__) . '/../lib/search/TingClientSearchRequest.php';

class TingClientHttpRequestFactoryTest extends UnitTestCase {
	
	private $baseUrl = 'http://ting.dbc.dk';
	
	function testSearchRequestGeneration()
	{
		$factory = new TingClientHttpRequestFactory($this->baseUrl);
		$factory->setLogger(new TingClientSimpleTestLogger($this));
		$searchRequest = new TingClientSearchRequest('dc.title:danmark');
		
		$httpRequest = $factory->fromSearchRequest($searchRequest);
		$this->assertEqual(	$httpRequest->getGetParameters(), 
												array('action' => 'searchRequest',
															'query' => 'dc.title:danmark'),
												'Search GET parameters have not been set correctly');		

		$this->assertEqual($httpRequest->getBaseUrl(), $this->baseUrl, 'Base URL not transfered connectly.');
	}

}
