<?php

class TingClientSearchRequest
{
		private $query;
		private $facets = array();
		private $format;
		private $start;
		private $stepValue;
		private $sort;
	
		function __construct($query)
		{
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
		
		public function getStepValue()
		{
			return $this->stepValue;
		}
		
		public function setStepValue($stepValue)
		{
			$this->stepValue = $stepValue;
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
