<?php

/**
 * Objects requests are really just simplified search requests.
 */
class TingClientObjectRequest extends TingClientSearchRequest {
  protected $id;
  protected $localId;
  public $ownerId;

  public function getObjectId() {
    return $this->id;
  }

  public function setObjectId($id) {
    $this->id = $id;
  }

  public function getLocalId() {
    return $this->localId;
  }

  public function setLocalId($localId) {
    $this->localId = $localId;
  }

  public function getRequest() {
    // Determine which id to use and the corresponding index
    if ($this->id) {
      $this->setQuery('rec.id=' . $this->id);
    } 
    // If we have both localId and ownerId, combine them to get 
    elseif ($this->ownerId && $this->localId) {
      $this->setQuery('rec.id=' . implode('|', array(
        $this->localId,
        $this->ownerId,
      )));
    }
    elseif ($this->localId) {
      // Use contains (any) instead of equality (=) as local id is not a
      // complete value.
      // TODO: This does not seem to work :(
      $this->setQuery('rec.id any ' . $this->localId);
    }

    if ($allRelations = $this->getAllRelations()) {
      $this->setAllRelations($allRelations);
      $this->setRelationData($this->getRelationData());
    }

    // We only want one object.
    $this->setNumResults(1);

    return parent::getRequest();
  }

  public function processResponse(stdClass $response) {
    $response = parent::processResponse($response);

    if (isset($response->collections[0]->objects[0])) {
      return $response->collections[0]->objects[0];
    }
  }
}

