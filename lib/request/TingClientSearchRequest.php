<?php

class TingClientSearchRequest extends TingClientRequest {
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

  // Query parameter is required, so if not provided, we will just do a 
  // wildcard search.
  protected $query = '*:*';
  protected $facets = array();
  protected $numFacets;
  protected $format;
  protected $start;
  protected $numResults;
  protected $sort;
  protected $allObjects;
  protected $allRelations;
  protected $relationData;
  protected $agency;

  protected function getRequest() {
    // These defaults are always needed.
    $this->setParameter('action', 'search');
    $this->setParameter('format', 'dkabm');

    $methodParameterMap = array(
      'query' => 'query',
      'format' => 'format',
      'start' => 'start',
      'numResults' => 'stepValue',
      'sort' => 'sort',
      'allObjects' => 'allObjects',
      'allRelations' => 'allRelations',
      'relationData' => 'relationData',
      'agency' => 'agency',
    );

    foreach ($methodParameterMap as $method => $parameter) {
      $getter = 'get'.ucfirst($method);
      if ($value = $this->$getter()) {
        $this->setParameter($parameter, $value);
      }
    }

    // If we have facets to display, we need to construct an array of 
    // them for SoapClient's benefit.
    $facets = $this->getFacets();
    if ($facets) {
      $this->setParameter('facets', array(
        'facetName' => $facets,
        'numberOfTerms' => $this->getNumFacets(),
      ));
    }

    return $this;
  }

  public function getQuery() {
    return $this->query;
  }

  public function setQuery($query) {
    $this->query = $query;
  }

  public function getFacets() {
    return $this->facets;
  }

  public function setFacets($facets) {
    $this->facets = $facets;
  }

  function getNumFacets() {
    return $this->numFacets;
  }

  function setNumFacets($numFacets) {
    $this->numFacets = $numFacets;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setFormat($format) {
    $this->format = $format;
  }

  public function getStart() {
    return $this->start;
  }

  public function setStart($start) {
    $this->start = $start;
  }

  function getNumResults() {
    return $this->numResults;
  }

  function setNumResults($numResults) {
    $this->numResults = $numResults;
  }

  public function getSort() {
    return $this->sort;
  }

  public function setSort($sort) {
    $this->sort = $sort;
  }

  function getAllObjects() {
    return $this->allObjects;
  }

  function setAllObjects($allObjects) {
    $this->allObjects = $allObjects;
  }

  function getAllRelations() {
    return $this->allRelations;
  }

  function setAllRelations($allRelations) {
    $this->allRelations = $allRelations;
  }

  function getRelationData() {
    return $this->relationData;
  }

  function setRelationData($relationData) {
    $this->relationData = $relationData;
  }

  function getAgency() {
    return $this->agency;
  }

  function setAgency($agency) {
    $this->agency = $agency;
  }

  function processResponse(stdClass $response) {
    $searchResult = new TingClientSearchResult();

    if (isset($response->error)) {
      throw new TingClientException('Error handling search request: '.self::getValue($response->error));
    }

    $searchResult->numTotalObjects = $response->hitCount;
    $searchResult->numTotalCollections = $response->collectionCount;
    $searchResult->more = (bool) preg_match('/true/i', $response->more);

    // Make collection objects for each search result.
    if (isset($response->searchResult)) {
      foreach ($response->searchResult as $entry => $result) {
        $searchResult->collections[] = $this->generateCollection($result);
      }
    }

    // If we have facets in the result, they need processing.
    if (isset($response->facetResult) && isset($response->facetResult->facet)) {
      // We may get more than one facetResult group, process each in turn.
      if (is_array($response->facetResult->facet)) {
        $facets = array();
        foreach ($response->facetResult->facet as $facetData) {
          $facets += $this->generateFacetResult($facetData);
        }
      }
      // Otherwise, just process the one group.
      else {
        $facets = $this->generateFacetResult($response->facetResult->facet);
      }

      $searchResult->facets = $facets;
    }

    return $searchResult;
  }

  private function generateCollection($collectionData) {
    $objects = array();

    // If there's multiple objects, we get an array.
    if (isset($collectionData->object) && is_array($collectionData->object)) {
      foreach ($collectionData->object as $objectData) {
        $objects[] = $this->generateObject($objectData);
      }
    }
    // If not, we just get the object directly.
    elseif ($collectionData->object instanceOf stdClass) {
      $objects[] = $this->generateObject($collectionData->object);
    }

    return new TingClientObjectCollection($objects);
  }

  private function generateObject($objectData) {
    $object = new TingClientObject();
    $object->id = $objectData->identifier;

    $object->record = array();
    $object->relations = array();

    // The prefixes used in the response from the server may change over
    // time. We use our own map to provide a stable interface.
    $prefixes = array_flip(self::$namespaces);

    // Take each data element in the record and transform it.
    foreach ($objectData->record as $name => $elements) {
      if (!is_array($elements)) {
        continue;
      }

      // Transform each value from a SoapVar to what the API consumers 
      // expect to receive.
      foreach ($elements as $element_name => $element) {
        if ($element instanceOf SoapVar) {
          $namespace = $element->enc_ns;
          $prefix = isset($prefixes[$namespace]) ? $prefixes[$namespace] : 'unknown';
          $key1 = $prefix . ':' . $name;

          // TODO: Figure out what this does.
          if (isset($element->enc_stype)) {
            $type_name = $element->enc_stype;
            $type_prefix = isset($prefixes[$element->enc_ns]) ? $prefixes[$element->enc_ns] : 'unknown';
            $key2 = $type_prefix . ':' . $type_name;
          }
          else {
            $key2 = '';
          }
          if (!isset($object->record[$key1][$key2])) {
            $object->record[$key1][$key2] = array();
          }
          $object->record[$key1][$key2][] = $element->enc_value;
        }
        else {
          $object->record[$name][] = $element;
        }
      }
    }

    if (!empty($object->record['ac:identifier'][''])) {
      list($object->localId, $object->ownerId) = explode('|', $object->record['ac:identifier'][''][0]);
    }
    else {
      $object->localId = $object->ownerId = FALSE;
    }

    if (isset($objectData->relations)) {
      foreach ($objectData->relations->relation as $relation) {
        if (isset($relation->relationObject)) {
          $relation_object = $this->generateObject($relation->relationObject->object, $namespaces);
        }
        $relation_object->relationType = $relation->relationType->{'$'};
        $relation_object->relationUri = $relation->relationUri->{'$'};
        $object->relations[] = $relation_object;
      }
    }

    return $object;
  }

  /**
   * Transforms the SOAP data into a TingClientFacetResult instance.
   *
   * @param stdClass $facetData
   *   The data returned from the webservice
   * @return TingClientFacetResult
   */
  public function generateFacetResult($facetData) {
    $facet = new TingClientFacetResult();
    $facet->name = $facetData->facetName;

    // We may have multiple facetTerms, in which case we add them to the 
    // array one by one.
    if (is_array($facetData->facetTerm)) {
      foreach ($facetData->facetTerm as $term) {
        $facet->terms[$term->term] = $term->frequence;
      }
    }
    // If there's only one facetTerm, it's avaiable as a stdClass object 
    // instead of an array.
    else {
      $facet->terms[$facetData->facetTerm->term] = $facetData->facetTerm->frequence;
    }

    // Return a single-element of facets, keyed by name, so the final 
    // array will also be keyed by name.
    return array($facet->name => $facet);
  }
}

