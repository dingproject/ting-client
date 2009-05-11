<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/request/http/TingClientHttpRequest.php';

class TingClientHttpRequestTest extends UnitTestCase {

	private $baseUrl = 'http://ting.dbc.dk';
	
	function __construct()
	{
	}
	
	function testUrlGeneration()
	{
		$request = new TingClientHttpRequest();
		$request->setBaseUrl($this->baseUrl);
		$request->setParameter(TingClientHttpRequest::GET, 'foo', 'bar');
		
		//single parameter
		$this->assertEqual($request->getUrl(), $this->baseUrl.'?foo=bar');
		
		//multiple parameters
		$request->setParameter(TingClientHttpRequest::GET, 'baz', 'boink');
		$request->setParameter(TingClientHttpRequest::GET, 'bik', 'bok');
		$this->assertEqual($request->getUrl(), $this->baseUrl.'?foo=bar&baz=boink&bik=bok');
		
		//POST parameters should not show up in URL
		$request->setParameter(TingClientHttpRequest::POST, 'pish', 'posh');
		$this->assertEqual($request->getUrl(), $this->baseUrl.'?foo=bar&baz=boink&bik=bok');
	}
	
	function testArrayParameterUrlGeneration()
	{
		$request = new TingClientHttpRequest();
		$request->setBaseUrl($this->baseUrl);
		$request->setParameter(TingClientHttpRequest::GET, 'foo', array('bar', 'baz'));
		$this->assertEqual($request->getUrl(), $this->baseUrl.'?foo=bar&foo=baz');
	}

}
