<?php

class TingClientSpellRequest extends TingClientRequest {
  protected $word;
  protected $numResults;

  protected function getRequest() {
    $this->setParameter('action', 'openSpell');
    $this->setParameter('format', 'dkabm');

    // TODO: Figure out what this does. It's required in the SOAP
    // implementation, but not used in the REST implementation.
    $this->setParameter('filter', '');

    // TODO: This should be configurable somewhere, even though it's not an
    // option in the REST implementation.
    $this->setParameter('language', 'da');

    $methodParameterMap = array(
      'word' => 'word',
      'numResults' => 'number',
    );

    foreach ($methodParameterMap as $method => $parameter) {
      $getter = 'get' . ucfirst($method);
      if ($value = $this->$getter()) {
        $this->setParameter($parameter, $value);
      }
    }

    return $this;
  }

  public function getWord() {
    return $this->word;
  }

  public function setWord($word) {
    $this->word = $word;
  }

  public function getNumResults() {
    return $this->numResults;
  }

  public function setNumResults($numResults) {
    $this->numResults = $numResults;
  }

  public function processResponse(stdClass $response) {
    // The old API expects an array of suggestions, so let's transform.
    if (isset($response->term) && is_array($response->term)) {
      return $response->term;
    }
    elseif (isset($response->term) && $response->term instanceOf stdClass) {
      return array($response->term);
    }
    else {
      return array();
    }
  }
}

