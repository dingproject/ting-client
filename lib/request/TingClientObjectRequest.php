<?php
/**
 * @file
 * TingClientObjectRequest class definition.
 */

/**
 * Get a Ting object by ID.
 *
 * Objects requests are much like search request, so this is implemented
 * as a subclass, even though it is a different request type.
 */
class TingClientObjectRequest extends TingClientRequest {
  protected $agency;
  protected $allRelations;
  protected $outputType;
  protected $objectFormat;
  protected $id;
  protected $localId;
  protected $relationData;
  protected $identifier;
  protected $profile;

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
   * Get all relations flag.
   */
  public function getAllRelations() {
    return $this->allRelations;
  }

  /**
   * Set all relations flag.
   */
  public function setAllRelations($all_relations) {
    $this->allRelations = $all_relations;
  }

  /**
   * Get output format requested from server.
   */
  public function getObjectFormat() {
    return $this->objectFormat;
  }

  /**
   * Set output format requested from server.
   */
  public function setObjectFormat($object_format) {
    $this->objectFormat = $object_format;
  }

  /**
   * Get output type requested from server.
   */
  public function getOutputType() {
    return $this->outputType;
  }

  /**
   * Set output type requested from server.
   */
  public function setOutputType($output_type) {
    $this->outputType = $output_type;
  }

  /**
   * Get local ID for the request object.
   */
  public function getLocalId() {
    return $this->localId;
  }

  /**
   * Set local ID for the request object.
   */
  public function setLocalId($local_id) {
    $this->localId = $local_id;
  }

  /**
   * Get object ID for the request object.
   */
  public function getObjectId() {
    return $this->identifier;
  }

  /**
   * Set object ID for the request object.
   */
  public function setObjectId($id) {
    $this->identifier = $id;
  }

  /**
   * Get relation data flag.
   */
  public function getRelationData() {
    return $this->relationData;
  }

  /**
   * Set relation data flag.
   */
  public function setRelationData($relation_data) {
    $this->relationData = $relation_data;
  }

  /**
   * Return the request object instance, ready for use.
   */
  public function getRequest() {
    $parameters = $this->getParameters();

    // These defaults are always needed.
    $this->setParameter('action', 'getObjectRequest');

    if (empty($parameters['objectFormat'])) {
      $this->setParameter('objectFormat', 'dkabm');
    }

    // Determine which id to use and the corresponding index.
    if ($this->identifier) {
      $this->setParameter('identifier', $this->identifier);
    }

    // If we have both localId and ownerId, combine them to get.
    elseif ($this->getAgency() && $this->localId) {
      $this->setParameter('identifier', implode('|', array(
         $this->localId,
        $this->getAgency(),
      )));
    }

    $method_parameter_map = array(
      'outputType' => 'outputType',
      'objectFormat' => 'objectFormat',
      'allRelations' => 'allRelations',
      'relationData' => 'relationData',
      'agency' => 'agency',
      'profile' => 'profile',
    );

    foreach ($method_parameter_map as $method => $parameter) {
      $getter = 'get' . ucfirst($method);
      if ($value = $this->$getter()) {
        $this->setParameter($parameter, $value);
      }
    }

    if ($all_relations = $this->getAllRelations()) {
      $this->setAllRelations($all_relations);
      $this->setRelationData($this->getRelationData());
    }

    return $this;
  }

  /**
   * Process response from OpenSearch.
   */
  public function processResponse(stdClass $response) {
    // Use TingClientSearchRequest::processResponse for processing the
    // response from Ting.
    $request = new TingClientSearchRequest(NULL);
    $response = $request->processResponse($response);

    if (isset($response->collections[0]->objects[0])) {
      return $response->collections[0]->objects[0];
    }
  }
}
