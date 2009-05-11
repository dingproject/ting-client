<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/response/json/TingClientJsonResponseAdapter.php';
require_once dirname(__FILE__) . '/../lib/log/TingClientSimpleTestLogger.php';


class TingClientJsonResponseAdapterTest extends UnitTestCase {

	
	function __construct()
	{
	}
	
	function testJsonParsing()
	{
		$json = file_get_contents(dirname(__FILE__).'/examples/json/single.js');
		
		$responseAdapter = new TingClientJsonResponseAdapter();
		$responseAdapter->setLogger(new TingClientSimpleTestLogger($this));
		$result = $responseAdapter->parseSearchResult($json);

		$this->assertEqual($result->getNumTotalRecords(), '13926', 'Wrong total number of records');
		
		//Record tests
		$records = $result->getRecords();
		$this->assertEqual(sizeof($records), 1, 'Wrong number of expected records');
		foreach ($records as $record)
		{
			//Data tests
			$data = array('title' => array('Danmark Kbh. : 1940'),
										'language' => array('Dansk'),
										'type' => array('Tidsskrift'),
										'publisher' => array('\\Kbh.\\'),
										'subject' => array('05.6','Periodica af blandet indhold. Danmark'),
										'date' => array('194? 194?'));
			
			foreach ($data as $name => $expectedValue)
			{
				$getter = 'get'.ucwords($name);
				$this->assertEqual($record->getData()->$getter(), $expectedValue, 'Wrong data extracted for '.$name);				
			}
		}
		
		//Facet tests
		$facets = $result->getFacets();
		$this->assertEqual(sizeof($facets), 2, 'Wrong number of facets');

		$facetNames = array('dc.creator' => array('af' => 1788), 'dc.title' => array('danmark' => 13926));
		foreach ($facetNames as $facetName => $termNames)
		{
			$this->assertTrue(isset($facets[$facetName]), 'Expected facet is not included');
			$terms = $facets[$facetName]->getTerms();
			foreach ($termNames as $termName => $frequency)
			{
				$this->assertTrue(isset($terms[$termName]), 'Excepted term is not included');
				$this->assertEqual($terms[$termName], $frequency, 'Wrong frequency form term');
			}
		}		
	}
	
	function testEmptyResponse()
	{
		$json = file_get_contents(dirname(__FILE__).'/examples/json/empty.js');
		
		$responseAdapter = new TingClientJsonResponseAdapter();
		$responseAdapter->setLogger(new TingClientSimpleTestLogger($this));
		$result = $responseAdapter->parseSearchResult($json);

		$this->assertEqual($result->getNumTotalRecords(), 0, 'Wrong total number of records');
		$this->assertEqual(sizeof($result->getRecords()), 0, 'Wrong number of records');
		$this->assertEqual(sizeof($result->getFacets()), 'Wrong number of facets');
	}
	

}
