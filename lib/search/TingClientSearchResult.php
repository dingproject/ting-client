<?php

class TingClientSearchResult
{
	private $numTotalRecords;
	private $records = array();
	private $facets = array();

	public function getNumTotalRecords()
	{
		return $this->numTotalRecords;
	}
	
	public function setNumTotalRecords($numTotalRecords)
	{
		$this->numTotalRecords = $numTotalRecords;
	}
	
	public function getRecords()
	{
		return $this->records;
	}
	
	public function addRecord(TingClientRecord $record)
	{
		$this->records[] = $record;
	}
	
	public function getFacets()
	{
		return $this->facets;
	}
	
	public function addFacet(TingClientFacetResult $facet)
	{
		$this->facets[$facet->getName()] = $facet;
	}
	
}
