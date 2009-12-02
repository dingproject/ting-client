<?php

require_once dirname(__FILE__).'/../object/TingClientObject.php';
require_once dirname(__FILE__).'/../object/TingClientObjectCollection.php';
require_once dirname(__FILE__).'/TingClientFacetResult.php';

class TingClientSearchResult
{
	public $numTotalObjects = 0;
	public $numTotalCollections = 0;
	public $collections = array();
	public $facets = array();	
}
