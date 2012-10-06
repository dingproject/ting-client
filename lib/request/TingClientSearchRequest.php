<?php
/**
 * @file
 * TingClientSearchRequest class definition.
 */


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
  protected $objectFormat;
  protected $start;
  protected $numResults;
  protected $rank;
  protected $sort;
  protected $allObjects;
  protected $allRelations;
  protected $relationData;
  protected $agency;
  protected $profile;
  protected $userDefinedBoost;
  protected $userDefinedRanking;

  /**
   * Return the request object instance, ready for use.
   */
  public function getRequest() {
    $parameters = $this->getParameters();

    // These defaults are always needed.
    $this->setParameter('action', 'searchRequest');

    if (isset($parameters['objectFormat'])) {
      $this->setObjectFormat($parameters['objectFormat']);
    }
    elseif (empty($parameters['format'])) {
      $this->setParameter('format', 'dkabm');
    }

    $method_parameter_map = array(
      'query' => 'query',
      'format' => 'format',
      'start' => 'start',
      'numResults' => 'stepValue',
      'rank' => 'rank',
      'sort' => 'sort',
      'allObjects' => 'allObjects',
      'allRelations' => 'allRelations',
      'relationData' => 'relationData',
      'agency' => 'agency',
      'profile' => 'profile',
      'userDefinedBoost' => 'userDefinedBoost',
      'userDefinedRanking' => 'userDefinedRanking',
    );

    foreach ($method_parameter_map as $method => $parameter) {
      $getter = 'get' . ucfirst($method);
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

    // Include userDefinedBoost if set on the request.
    if (is_array($this->userDefinedBoost) && !empty($this->userDefinedBoost)) {
      $this->setParameter('userDefinedBoost', $this->userDefinedBoost);
    }

    // Include userDefinedRanking if set on the request.
    if (is_array($this->userDefinedRanking) && !empty($this->userDefinedRanking)) {
      $this->setParameter('userDefinedRanking', $this->userDefinedRanking);
    }

    return $this;
  }

  /**
   * Get search query.
   */
  public function getQuery() {
    return $this->query;
  }

  /**
   * Set search query.
   */
  public function setQuery($query) {
    $this->query = $query;
  }

  /**
   * Get facets to be requested.
   */
  public function getFacets() {
    return $this->facets;
  }

  /**
   * Set facets to be requested.
   */
  public function setFacets($facets) {
    $this->facets = $facets;
  }

  /**
   * Get the number of facets returned from OpenSearch.
   */
  public function getNumFacets() {
    return $this->numFacets;
  }

  /**
   * Set the number of facets returned from OpenSearch.
   */
  public function setNumFacets($facet_count) {
    $this->numFacets = $facet_count;
  }

  /**
   * Get format.
   */
  public function getFormat() {
    return $this->format;
  }

  /**
   * Set format.
   */
  public function setFormat($format) {
    $this->format = $format;
  }

  /**
   * Get object format.
   */
  public function getObjectFormat() {
    return $this->objectFormat;
  }

  /**
   * Set object format.
   */
  public function setObjectFormat($value) {
    $this->objectFormat = $value;
  }

  /**
   * Get start.
   */
  public function getStart() {
    return $this->start;
  }

  /**
   * Set start.
   */
  public function setStart($start) {
    $this->start = $start;
  }

  /**
   * Get number of results on executed query.
   */
  public function getNumResults() {
    return $this->numResults;
  }

  /**
   * Set number of results on executed query.
   */
  public function setNumResults($result_count) {
    $this->numResults = $result_count;
  }

  /**
   * Get rank.
   */
  public function getRank() {
    return $this->rank;
  }

  /**
   * Set rank.
   */
  public function setRank($rank) {
    $this->rank = $rank;
  }

  /**
   * Get current sort setting.
   */
  public function getSort() {
    return $this->sort;
  }

  /**
   * Set new sort setting.
   */
  public function setSort($sort) {
    $this->sort = $sort;
  }

  /**
   * Get allObjects flag.
   */
  public function getAllObjects() {
    return $this->allObjects;
  }

  /**
   * Set allObjects flag.
   */
  public function setAllObjects($value) {
    $this->allObjects = $value;
  }

  /**
   * Get allRelations flag.
   */
  public function getAllRelations() {
    return $this->allRelations;
  }

  /**
   * Set allRelations flag.
   */
  public function setAllRelations($value) {
    $this->allRelations = $value;
  }

  /**
   * Get relationData flag.
   */
  public function getRelationData() {
    return $this->relationData;
  }

  /**
   * Set relationData flag.
   */
  public function setRelationData($value) {
    $this->relationData = $value;
  }

  /**
   * Get agency code.
   */
  public function getAgency() {
    return $this->agency;
  }

  /**
   * Set agency code.
   */
  public function setAgency($agency) {
    $this->agency = $agency;
  }

  /**
   * Get search profile name.
   */
  public function getProfile() {
    return $this->profile;
  }

  /**
   * Set search profile name.
   */
  public function setProfile($profile) {
    $this->profile = $profile;
  }

  /**
   * Get current userDefinedBoost configuration.
   */
  public function getUserDefinedBoost() {
    return $this->userDefinedBoost;
  }

  /**
   * Set new userDefinedBoost configuration.
   */
  public function setUserDefinedBoost($config) {
    $this->userDefinedBoost = $config;
  }

  /**
   * Get current userDefinedRanking configuration.
   */
  public function getUserDefinedRanking() {
    return $this->userDefinedRanking;
  }

  /**
   * Set new userDefinedRanking configuration.
   */
  public function setUserDefinedRanking($config) {
    $this->userDefinedRanking = $config;
  }

  /**
   * Process response from OpenSearch.
   */
  public function processResponse(stdClass $response) {
    $search_result = new TingClientSearchResult();

    $search_response = $response->searchResponse;
    if (isset($search_response->error)) {
      throw new TingClientException('Error handling search request: ' . self::getValue($search_response->error));
    }

    $search_result->numTotalObjects = self::getValue($search_response->result->hitCount);
    $search_result->numTotalCollections = self::getValue($search_response->result->collectionCount);
    $search_result->more = (strpos(self::getValue($search_response->result->more), 'true') !== FALSE);

    if (isset($search_response->result->searchResult) && is_array($search_response->result->searchResult)) {
      foreach ($search_response->result->searchResult as $entry => $result) {
        if (self::getValue($result->collection->numberOfObjects) >= 1) {
          $search_result->collections[] = $this->generateCollection($result->collection, (array) $response->{'@namespaces'});
        }
      }
    }

    if (isset($search_response->result->facetResult->facet) && is_array($search_response->result->facetResult->facet)) {
      foreach ($search_response->result->facetResult->facet as $facet_result) {
        $facet = new TingClientFacetResult();
        $facet->name = self::getValue($facet_result->facetName);
        if (isset($facet_result->facetTerm)) {
          foreach ($facet_result->facetTerm as $term) {
            $facet->terms[self::getValue($term->term)] = self::getValue($term->frequence);
          }
        }

        $search_result->facets[$facet->name] = $facet;
      }
    }

    return $search_result;
  }

  /**
   * Generate TingClientObject from response data.
   */
  protected function generateObject($object_data, $namespaces) {
    $object = new TingClientObject();
    $object->id = self::getValue($object_data->identifier);

    $object->record = array();
    $object->relations = array();
    $object->formatsAvailable = array();

    // The prefixes used in the response from the server may change over
    // time. We use our own map to provide a stable interface.
    $prefixes = array_flip(self::$namespaces);

    if (isset($object_data->record)) {
      foreach ($object_data->record as $name => $elements) {
        if (!is_array($elements)) {
          continue;
        }
        foreach ($elements as $element) {
          $namespace = $namespaces[isset($element->{'@'}) ? $element->{'@'} : '$'];
          $prefix = isset($prefixes[$namespace]) ? $prefixes[$namespace] : 'unknown';
          $key1 = $prefix . ':' . $name;
          if (isset($element->{'@type'}) && strpos($element->{'@type'}->{'$'}, ':') !== FALSE) {
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
    }

    if (!empty($object->record['ac:identifier'][''])) {
      list($object->localId, $object->ownerId) = explode('|', $object->record['ac:identifier'][''][0]);
    }
    else {
      $object->localId = $object->ownerId = FALSE;
    }

    if (isset($object_data->relations)) {
      $object->relationsData = array();
      foreach ($object_data->relations->relation as $relation) {
        $relation_data = (object) array(
          'relationType' => $relation->relationType->{'$'},
          'relationUri' => $relation->relationUri->{'$'},
        );
        if (isset($relation->relationObject)) {
          $relation_object = $this->generateObject($relation->relationObject->object, $namespaces);
          $relation_data->relationObject = $relation_object;
          $relation_object->relationType = $relation_data->relationType;
          $relation_object->relationUri = $relation_data->relationUri;
          $object->relations[] = $relation_object;
        }
        $object->relationsData[] = $relation_data;
      }
    }

    if (isset($object_data->formatsAvailable)) {
      foreach ($object_data->formatsAvailable->format as $format) {
        $object->formatsAvailable[] = $format->{'$'};
      }
    }

    return $object;
  }

  /**
   * Generate TingClientObjectCollection from response data.
   */
  protected function generateCollection($collection_data, $namespaces) {
    $objects = array();
    if (isset($collection_data->object) && is_array($collection_data->object)) {
      foreach ($collection_data->object as $object_data) {
        $objects[] = $this->generateObject($object_data, $namespaces);
      }
    }
    return new TingClientObjectCollection($objects);
  }
}
