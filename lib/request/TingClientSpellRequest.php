<?php

class TingClientSpellRequest extends TingClientRequest {
  protected $word;
  protected $numResults;

  protected function getRequest() {
    $this->setParameter('action', 'spellRequest');
    $this->setParameter('format', 'dkabm');

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
    $suggestions = array();

    if (isset($response->spellResponse)) {
      $response = $response->spellResponse;

      if (isset($response->term) && $response->term) {
        foreach ($response->term as $term) {
          $suggestions[] = new TingClientSpellSuggestion($this->getValue($term->suggestion), floatval(str_replace(',', '.', $this->getValue($term->weight))));
        }
      }
    }
    return $suggestions;
  }
}

