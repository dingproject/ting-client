<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientSearchRequest.php';
require_once dirname(__FILE__) . '/../../result/search/TingClientSearchResult.php';
require_once dirname(__FILE__) . '/../../result/object/data/TingClientDublinCoreData.php';
require_once dirname(__FILE__) . '/../../result/object/identifier/TingClientObjectIdentifier.php';


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
		protected $agency;
	
		public function __construct($baseUrl)
		{
			parent::__construct($baseUrl);
		}		

		protected function getHttpRequest()
		{
			$httpRequest = new TingClientHttpRequest();
			$httpRequest->setMethod(TingClientHttpRequest::GET);
			$httpRequest->setBaseUrl($this->baseUrl);
			$httpRequest->setGetParameter('action', 'search');
			$httpRequest->setGetParameter('outputType', 'json');
			
			$methodParameterMap = array('query' => 'query',
																	'facets' => 'facets.facetName',
																	'numFacets' => 'facets.numberOfTerms',
																	'format' => 'format',
																	'start' => 'start',
																	'numResults' => 'stepValue',
																	'allObjects' => 'allObjects',
			                            'agency' => 'agency');
			
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
			
		protected function parseJson($response)
		{
			$searchResult = new TingClientSearchResult();

			$searchResponse = $response->searchResponse;
      if (isset($searchResponse->error))
      {
        throw new TingClientException('Error handling search request: '.$searchResponse->error);
      }
			
			$searchResult->numTotalObjects = self::getValue($searchResponse->result->hitCount);
	
			if (isset($searchResponse->result->searchResult) && is_array($searchResponse->result->searchResult))
			{
				foreach ($searchResponse->result->searchResult as $entry => $result)
				{
					$searchResult->collections[] = $this->generateCollection($result->collection);
				}
			}
	    
			if (isset($searchResponse->result->facetResult->facet) && is_array($searchResponse->result->facetResult->facet))
			{
				foreach ($searchResponse->result->facetResult->facet as $facetResult)
				{
					$facet = new TingClientFacetResult();
					$facet->name = self::getValue($facetResult->facetName);
					if (isset($facetResult->facetTerm))
					{
						foreach ($facetResult->facetTerm as $term)
						{
							$facet->terms[self::getValue($term->term)] = self::getValue($term->frequence);
						}
					}
						
					$searchResult->facets[$facet->name] = $facet;
				}
		  }
		  
			return $searchResult;			
		}
		
		private function generateObject($objectData)
		{
			$object = new TingClientObject();
			$object->id = self::getValue($objectData->identifier);
			
			$data = new TingClientDublinCoreData();

			//Set all known attributes mapping 1:1.
			$varNames = array_keys(get_object_vars($data));
			foreach ($objectData->record as $name => $value)
			{
				if (in_array($name, $varNames))
				{
					$data->$name = self::getValue($value);
				}
			}
			
			//Handle creator seperately to filter on types
			if (isset($objectData->record->creator))
			{
				$autCreators = array();
				foreach($objectData->record->creator as $creator)
				{
					//For now only use oss:aut names. Other types duplicate this in various forms
					if (self::getAttributeValue($creator, 'type') == 'oss:aut')
					{
						$autCreators[] = self::getValue($creator);
					}
				}
				//Only reset creators if aut names were extracted
				if (sizeof($autCreators) > 0)
				{
					$data->creator = $autCreators;
				}
			}
			
			//Handle identifiers separately to extract types etc.
			$data->identifier = array();
			if (isset($objectData->record->identifier))
			{
				foreach($objectData->record->identifier as $identifier)
				{
					$id = self::getValue($identifier);
					$type = self::getAttributeValue($identifier, 'type');
					$data->identifier[] = TingClientObjectIdentifier::factory($id, $type);
				}
			}
			foreach ($data->identifier as $identifier)
			{
				if ($identifier->type == TingClientObjectIdentifier::FAUST_NUMBER)
				{
					$identifier = explode('|', $identifier->id, 2);
					$idNames = array('localId', 'ownerId');
					foreach ($identifier as $index => $value)
					{
						$data->$idNames[$index] = $value;
					}
				}
			}
			
			$object->data = $data;
						
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
		
		function getAgency()
		{
			return $this->agency;
		}
		
		function setAgency($agency)
		{
			$this->agency = $agency;
		}
	
}
