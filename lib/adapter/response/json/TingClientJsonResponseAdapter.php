<?php

require_once dirname(__FILE__).'/../TingClientResponseAdapter.php';
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
		$searchResult = new TingClientSearchResult();
		$response = $this->parseJson($responseString);

		$this->logger->log('Total number of results '.$response->result->hitCount, TingClientLogger::INFO);
		$searchResult->numTotalObjects = $response->result->hitCount;

		if (isset($response->result->searchResult) && is_array($response->result->searchResult))
		{
			foreach ($response->result->searchResult as $entry => $result)
			{
				$searchResult->collections[] = $this->generateCollection($result);
			}
		}

		$this->logger->log('Extracting '.sizeof($response->result->facetResult).' facets', TingClientLogger::INFO);
		foreach ($response->result->facetResult as $facetResult)
		{
			$this->logger->log('Extracting facet '.$facetResult->facetName, TingClientLogger::DEBUG);
			$facet = new TingClientFacetResult();
			$facet->name = $facetResult->facetName;
			foreach ($facetResult->facetTerm as $term)
			{
				$facet->terms[$term->term] = $term->frequence;
			}
			
			$searchResult->facets[$facet->name] = $facet;
		}
		
		return $searchResult;
	}
	
	/**
	 * @param string $responseString
	 * @return TingClientScanResult
	 */
	public function parseScanResult($responseString)
	{
		$result = new TingClientScanResult();
		$response = $this->parseJson($responseString);
		
		if (isset($response->term) && is_array($response->term))
		{
			foreach ($response->term as $scanTerm)
			{
					$term = new TingClientScanTerm();
					$term->name = $scanTerm->name;
					$term->count = $scanTerm->hitCount;
					$result->terms[] = $term;
			}
		}

		return $result;
	}
	
	public function parseCollectionResult($responseString)
	{
		$response = $this->parseJson($responseString);

		$collection = null;
		if (isset($response->result->searchResult) && is_array($response->result->searchResult))
		{
			$collection = $this->generateCollection($response->result->searchResult[0]);
		}
		
		return $collection;		
	}
	
	public function parseObjectResult($responseString)
	{
		$response = $this->parseJson($responseString);

		$object = null;
		if (isset($response->result->searchResult->collection->object[0]))
		{
			$object = $this->generateObject($response->result->searchResult->collection->object[0]);
		}
		return $object;		
	}
	
	private function parseJson($responseString)
	{
		$response = json_decode($responseString);
		if (!$response)
		{
			throw new TingClientException('Unable to decode response as JSON: '.$responseString);
		}
		if (!is_object($response))
		{
			throw new TingClientException('Unexpected JSON response: '.var_export($response, true));
		}
		return $response;
	}
	
	private function generateObject($objectData)
	{
		$object = new TingClientObject();
		$object->id = $objectData->identifier;
		$object->data = TingClientObjectDataFactory::fromSearchObjectData($objectData);
		return $object;
	}
	
	private function generateCollection($collectionData)
	{
		$objects = array();
		if (isset($collectionData->object) && is_array($collectionData->object))
		{
			foreach ($collectionData->object as $objectData)
			{
				$objects[] = $this->generateObject($objectData);
			}		
		}
		return new TingClientObjectCollection($objects);
	}

}
