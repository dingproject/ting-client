<?php

/**
 * Get a Ting object by ID.
 *
 * Objects requests are much like search request, so this is implemented
 * as a subclass, even though it is a different request type.
 */
class TingClientObjectRequest extends TingClientRequest {
  protected $agency;
  protected $allRelations;
  protected $format;
  protected $id;
  protected $localId;
  protected $relationData;
  protected $identifier;
  protected $profile;

  public function getProfile() {
    return $this->profile;
  }
  public function setProfile($profile) {
    $this->profile = $profile;
  }
  public function getAgency() {
    return $this->agency;
  }

  public function setAgency($agency) {
    $this->agency = $agency;
  }

  public function getAllRelations() {
    return $this->allRelations;
  }

  public function setAllRelations($allRelations) {
    $this->allRelations = $allRelations;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setFormat($format) {
    $this->format = $format;
  }

  public function getLocalId() {
    return $this->localId;
  }

  public function setLocalId($localId) {
    $this->localId = $localId;
  }

  public function getObjectId() {
    return $this->identifier;
  }

  public function setObjectId($id) {
    $this->identifier = $id;
  }

  public function getRelationData() {
    return $this->relationData;
  }

  public function setRelationData($relationData) {
    $this->relationData = $relationData;
  }

  public function getRequest() {
    $parameters = $this->getParameters();
    //
    // These defaults are always needed.
    $this->setParameter('action', 'getObjectRequest');
    if (!isset($parameters['format']) || empty($parameters['format'])) {
      $this->setParameter('format', 'dkabm');
    }

    // Determine which id to use and the corresponding index
    if ($this->identifier) {
      $this->setParameter('identifier', $this->identifier);
    }
    // If we have both localId and ownerId, combine them to get
    elseif ($this->getAgency() && $this->localId) {
      $this->setParameter('identifier', implode(':', array(
        $this->getAgency(),
        $this->localId,
      )));
    }

    $methodParameterMap = array(
      'format' => 'objectFormat',
      'allRelations' => 'allRelations',
      'relationData' => 'relationData',
      'agency' => 'agency',
      'profile' => 'profile',
    );

    foreach ($methodParameterMap as $method => $parameter) {
      $getter = 'get' . ucfirst($method);
      if ($value = $this->$getter()) {
        $this->setParameter($parameter, $value);
      }
    }

    if ($allRelations = $this->getAllRelations()) {
      $this->setAllRelations($allRelations);
      $this->setRelationData($this->getRelationData());
    }

    return $this;
  }

  public function processResponse(stdClass $response) {
    // Use TingClientSearchRequest::processResponse for processing the
    // response from Ting.
    $searchRequest = new TingClientSearchRequest(NULL);
    $response = $searchRequest->processResponse($response);

    if (isset($response->collections[0]->objects[0])) {
      //  print_r($response->collections);
      return $response->collections[0]->objects[0];
    }
  }
}

