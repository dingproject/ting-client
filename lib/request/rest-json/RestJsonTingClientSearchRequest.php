<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientSearchRequest.php';
require_once dirname(__FILE__) . '/../../result/search/TingClientSearchResult.php';


class RestJsonTingClientSearchRequest extends RestJsonTingClientRequest
                                      implements TingClientSearchRequest
{
		/**
		 * Prefix to namespace URI map.
		 */
		static $namespaces = array(
			'' => 'http://oss.dbc.dk/ns/opensearch',
			'xs' => 'http://www.w3.org/2001/XMLSchema',
			'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'oss' => 'http://oss.dbc.dk/ns/osstypes',
			'dc' => 'http://purl.org/dc/elements/1.1/',
			'dkabm' => 'http://biblstandard.dk/abm/namespace/dkabm/',
			'dcmitype' => 'http://purl.org/dc/dcmitype/',
			'dcterms' => 'http://purl.org/dc/terms/',
			'ac' => 'http://biblstandard.dk/ac/namespace/',
			'dkdcplus' => 'http://biblstandard.dk/abm/namespace/dkdcplus/',
		);

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
																	'sort' => 'sort',
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
      $searchResult->numTotalCollections = self::getValue($searchResponse->result->collectionCount);

			if (isset($searchResponse->result->searchResult) && is_array($searchResponse->result->searchResult))
			{
				foreach ($searchResponse->result->searchResult as $entry => $result)
				{
					$searchResult->collections[] = $this->generateCollection($result->collection, (array)$response->{'@namespaces'});
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

		private function generateObject($objectData, $namespaces)
		{
			$object = new TingClientObject();
			$object->id = self::getValue($objectData->identifier);

			$object->record = array();

			// The prefixes used in the response from the server may change over
			// time. We use our own map to provide a stable interface.
			$prefixes = array_flip(self::$namespaces);
			foreach ($objectData->record as $name => $elements) {
				if (!is_array($elements)) {
					continue;
				}
				foreach ($elements as $element) {
					$namespace = $namespaces[isset($element->{'@'}) ? $element->{'@'} : '$'];
					$prefix = isset($prefixes[$namespace]) ? $prefixes[$namespace] : 'unknown';
					$key1 = $prefix . ':' . $name;
					if (isset($element->{'@type'})) {
						list($type_prefix, $type_name) = explode(':', $element->{'@type'}->{'$'}, 2);
						$type_namespace = $namespaces[isset($type_prefix) ? $type_prefix : '$'];
						$type_prefix = isset($prefixes[$type_namespace]) ? $prefixes[$type_namespace] : 'unknown';
						$key2 = $type_prefix . ':' . $type_name;
					}
          else {
            $key2 = '';
          }
					if (!isset($object->record[$key1][$key2])) {
						$object->record[$key1][$key2] = array();
					}
					$object->record[$key1][$key2][] = $element->{'$'};
				}
			}

			if (!empty($object->record['ac:identifier'][''])) {
				list($object->localId, $object->ownerId) = explode('|', $object->record['ac:identifier'][''][0]);
			}
			else {
				$object->localId = $object->ownerId = FALSE;
			}

			return $object;
		}

		private function generateCollection($collectionData, $namespaces)
		{
			$objects = array();
			if (isset($collectionData->object) && is_array($collectionData->object))
			{
				foreach ($collectionData->object as $objectData)
				{
					$objects[] = $this->generateObject($objectData, $namespaces);
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
