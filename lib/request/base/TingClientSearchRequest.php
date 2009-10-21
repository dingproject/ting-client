<?php

require_once dirname(__FILE__) . '/TingClientAgentRequest.php';

interface TingClientSearchRequest extends TingClientAgentRequest
{
	
		function getQuery();
		
		function setQuery($query);
		
		function getFacets();
		
		function setFacets($facets);
		
		function getNumFacets();
		
		function setNumFacets($numFacets);
		
		function getFormat();
		
		function setFormat($format);
		
		function getStart();
		
		function setStart($start);
		
		function getNumResults();
		
		function setNumResults($numResults);
		
		function getSort();
		
		function setSort($sort);
		
		function getAllObjects();
		
		function setAllObjects($allObjects);
	
}
