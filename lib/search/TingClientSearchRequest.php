<?php

require_once dirname(__FILE__).'/TingClientRequest.php';

class TingClientSearchRequest extends TingClientRequest
{
		private $query;
		private $facets = array();
		private $numFacets;
		private $format;
		private $start;
		private $numResults;
		private $sort;
	
		function __construct($query = null)
		{
			parent::__construct();
			$this->query = $query;
		}
		
		public function getQuery()
		{
			return $this->query;
		}
		
		public function setQuery($query)
		{
			$this->query = $query;
		}
		
		public function getFacets()
		{
			return $this->facets;
		}
		
		public function setFacets($facets)
		{
			$this->facets = $facets;	
		}
		
		function getNumFacets()
		{
			return $this->numFacets;
		}
		
		function setNumFacets($numFacets)
		{
			$this->numFacets = $numFacets;
		}
		
		public function getFormat()
		{
			return $this->format;
		}
		
		public function setFormat($format)
		{
			$this->format = $format;
		}
		
		public function getStart()
		{
			return $this->start;
		}
		
		public function setStart($start)
		{
			$this->start = $start;
		}
		
		function getNumResults()
		{
			return $this->numResults;
		}
		
		function setNumResults($numResults)
		{
			$this->numResults = $numResults;
		}
		
		public function getSort()
		{
			return $this->sort;
		}
		
		public function setSort($sort)
		{
			$this->sort = $sort;
		}
	
}
