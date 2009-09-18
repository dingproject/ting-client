<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientSearchRequest.php';
require_once dirname(__FILE__) . '/../../result/search/TingClientSearchResult.php';
require_once dirname(__FILE__) . '/../../result/object/data/TingClientObjectDataFactory.php';


class RestJsonTingClientSearchRequest extends RestJsonTingClientRequest 
																			implements TingClientSearchRequest
{
	
		protected $query;
		protected $facets = array();
		protected $numFacets;
		protected $format;
		protected $start;
		protected $numResults;
		protected $sort;
		protected $allObjects;
	
		public function __construct($baseUrl)
		{
			parent::__construct($baseUrl);
		}		

		public function getHttpRequest()
		{
			$httpRequest = new TingClientHttpRequest();
			$httpRequest->setMethod(TingClientHttpRequest::GET);
			$httpRequest->setBaseUrl($this->baseUrl);
			$httpRequest->setGetParameter('action', 'searchRequest');
			$httpRequest->setGetParameter('outputType', 'json');
			
			$methodParameterMap = array('query' => 'query',
																	'facets' => 'facets.facetName',
																	'numFacets' => 'facets.numberOfTerms',
																	'format' => 'format',
																	'start' => 'start',
																	'numResults' => 'stepValue',
																	'allObjects' => 'allObjects');
			
			foreach ($methodParameterMap as $method => $parameter)
			{
				$getter = 'get'.ucfirst($method);
				if ($value = $this->$getter())
				{
					$httpRequest->setParameter(TingClientHttpRequest::GET, $parameter, $value);
				}
			}
			
			return $httpRequest;			
		}
			
		public function parseResponse($responseString)
		{
			$searchResult = new TingClientSearchResult();
			$response = $this->parseJson($responseString);
	
			$searchResult->numTotalObjects = $response->result->hitCount;
	
			if (isset($response->result->searchResult) && is_array($response->result->searchResult))
			{
				foreach ($response->result->searchResult as $entry => $result)
				{
					$searchResult->collections[] = $this->generateCollection($result);
				}
			}
	
			foreach ($response->result->facetResult as $facetResult)
			{
				$facet = new TingClientFacetResult();
				$facet->name = $facetResult->facetName;
				if (isset($facetResult->facetTerm))
				{
					foreach ($facetResult->facetTerm as $term)
					{
						if (isset($term->frequence) && ($term->frequence > 0))
						{
							$facet->terms[$term->term] = $term->frequence;
						}
					}
				}
					
				$searchResult->facets[$facet->name] = $facet;
			}
			
			return $searchResult;			
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
		
		function getAllObjects()
		{
			return $this->allObjects;
		}
		
		function setAllObjects($allObjects)
		{
			$this->allObjects = $allObjects;
		}
	
}
