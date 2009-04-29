<?php

require_once dirname(__FILE__).'/../TingClientResponseAdapter.php';
require_once dirname(__FILE__).'/../../../search/TingClientSearchResult.php';
require_once dirname(__FILE__).'/../../../search/TingClientRecord.php';
require_once dirname(__FILE__).'/../../../search/TingClientFacetResult.php';
require_once dirname(__FILE__).'/../../../search/data/TingClientRecordDataFactory.php';

class TingClientJsonResponseAdapter implements TingClientResponseAdapter 
{

	/**
	 * @param string $responseString
	 * @return TingSearchResult
	 */
	public function parseSearchResult($responseString)
	{
		$result = new TingClientSearchResult();
		$response = json_decode($responseString);
		
		$result->setNumTotalRecords($response->searchResult->hitCount);
		
		foreach ($response->searchResult->records as $recordsResult)
		{
			foreach ($recordsResult as $recordResult)
			{
				$record = new TingClientRecord();
				$record->setId($recordResult->identifier);
				$record->setData(TingClientRecordDataFactory::fromSearchRecordData($recordResult));
				
				$result->addRecord($record);
			}
		}
		
		foreach ($response->searchResult->facetResult as $facetResult)
		{
			$facet = new TingClientFacetResult();
			$facet->setName($facetResult->facetName);
			foreach ($facetResult->facetTerm as $term)
			{
				$facet->addTerm($term->term, $term->frequence);
			}
			
			$result->addFacet($facet);
		}
		
		
		return $result;
	}

}
