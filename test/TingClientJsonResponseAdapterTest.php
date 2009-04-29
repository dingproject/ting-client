<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/adapter/response/TingClientJsonResponseAdapter.php';

class TingClientJsonResponseAdapterTest extends UnitTestCase {

	
	function __construct()
	{
	}
	
	function testJsonParsing()
	{
		$json = file_get_contents(dirname(__FILE__).'/examples/json/single.js');
		
		$responseAdapter = new TingClientJsonResponseAdapter();
		$result = $responseAdapter->parseSearchResult($json);

		$this->assertEqual($result->getNumTotalRecords(), '13926', 'Wrong total number of records');
		
		//Record tests
		$records = $result->getRecords();
		$this->assertEqual(sizeof($records), 1, 'Wrong number of expected records');
		foreach ($records as $record)
		{
			$this->assertEqual($record->getTitles(), array('Danmark Kbh. : 1940'), 'Wrong title extracted');
			$this->assertEqual($record->getTypes(), array('type'), 'Wrong type extracted');
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

}
