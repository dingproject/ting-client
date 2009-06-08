<?php

require_once dirname(__FILE__).'/TingClientRecord.php';
require_once dirname(__FILE__).'/TingClientFacetResult.php';

class TingClientSearchResult
{
	public $numTotalRecords;
	public $records = array();
	public $facets = array();	
}
