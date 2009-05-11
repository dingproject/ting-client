<?php

require_once dirname(__FILE__).'/../TingClientResponseAdapter.php';
require_once dirname(__FILE__).'/../../../search/TingClientSearchResult.php';
require_once dirname(__FILE__).'/../../../search/TingClientRecord.php';
require_once dirname(__FILE__).'/../../../search/TingClientFacetResult.php';
require_once dirname(__FILE__).'/../../../search/data/TingClientRecordDataFactory.php';
require_once dirname(__FILE__).'/../../../log/TingClientLogger.php';

class TingClientJsonResponseAdapter implements TingClientResponseAdapter 
{
	
	/**
	 * @var TingClientLogger
	 */
	private $logger;

	public function __construct(TingClientLogger $logger = NULL)
	{
		$this->logger = (isset($logger)) ? $logger : new TingClientVoidLogger();
	}
	
	public function setLogger(TingClientLogger $logger)
	{
		$this->logger = $logger;
	}
	
	/**
	 * @param string $responseString
	 * @return TingClientSearchResult
	 */
	public function parseSearchResult($responseString)
	{
		$result = new TingClientSearchResult();
		$response = json_decode($responseString);
		if (!$response)
		{
			throw new TingClientException('Unable to decode response as JSON: '.$responseString);
		}
		if (!is_object($response))
		{
			throw new TingClientException('Unexpected JSON response: '.var_export($response, true));
		}

		$this->logger->log('Total number of results '.$response->searchResult->hitCount, TingClientLogger::INFO);
		$result->setNumTotalRecords($response->searchResult->hitCount);
		
		if (isset($response->searchResult->records) && is_object($response->searchResult->records))
		{
			foreach ($response->searchResult->records as $recordsResult)
			{
				foreach ($recordsResult as $recordResult)
				{
					$this->logger->log('Extracting search result '.$recordResult->identifier, TingClientLogger::DEBUG);
					
					$record = new TingClientRecord();
					$record->setId($recordResult->identifier);
					$record->setData(TingClientRecordDataFactory::fromSearchRecordData($recordResult));
					
					$result->addRecord($record);
				}
			}
		}

		$this->logger->log('Extracting '.sizeof($response->searchResult->facetResult).' facets', TingClientLogger::INFO);
		foreach ($response->searchResult->facetResult as $facetResult)
		{
			$this->logger->log('Extracting facet '.$facetResult->facetName, TingClientLogger::DEBUG);
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
