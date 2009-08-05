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

		$this->assertEqual($result->numTotalRecords, '13926', 'Wrong total number of records');
		
		//Record tests
		$this->assertEqual(sizeof($result->records), 1, 'Wrong number of expected records');
		foreach ($result->records as $record)
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
				$this->assertEqual($record->data->$name, $expectedValue, 'Wrong data extracted for '.$name);				
			}
		}
		
		//Facet tests
		$this->assertEqual(sizeof($result->facets), 2, 'Wrong number of facets');

		$facetNames = array('dc.creator' => array('af' => 1788), 'dc.title' => array('danmark' => 13926));
		foreach ($facetNames as $facetName => $termNames)
		{
			$this->assertTrue(isset($result->facets[$facetName]), 'Expected facet is not included');
			$terms = $result->facets[$facetName]->terms;
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

		$this->assertEqual($result->numTotalRecords, 0, 'Wrong total number of records');
		$this->assertEqual(sizeof($result->records), 0, 'Wrong number of records');
		$this->assertEqual(sizeof($result->facets), 'Wrong number of facets');
	}
	
	function testCollectionSearch()
	{
		$json = file_get_contents(dirname(__FILE__).'/examples/json/search-collection-1.js');
		
		$responseAdapter = new TingClientJsonResponseAdapter();
		$responseAdapter->setLogger(new TingClientSimpleTestLogger($this));
		//$result = $responseAdapter->parseSearchResult($json);
	}
	
	function testCollection()
	{
		$json = file_get_contents(dirname(__FILE__).'/examples/json/collection.js');
		
		$responseAdapter = new TingClientJsonResponseAdapter();
		$responseAdapter->setLogger(new TingClientSimpleTestLogger($this));
		$collection = $responseAdapter->parseCollectionResult($json);
		
		$this->assertNotNull($collection);
		$this->assertTrue(sizeof($collection) > 0, 'Empty collection of objects');
		
	}
	

}
