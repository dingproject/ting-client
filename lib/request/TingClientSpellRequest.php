<?php

class TingClientSpellRequest extends TingClientRequest {
  protected $word;
  protected $numResults;

  protected function getSoapRequest() {
    $soapRequest = new TingClientSoapRequest();
    $soapRequest->setWsdlUrl($this->wsdlUrl);
    $soapRequest->setParameter('action', 'openSpell');
    $soapRequest->setParameter('format', 'dkabm');

    // TODO: Figure out what this does. It's required in the SOAP
    // implementation, but not used in the REST implementation.
    $soapRequest->setParameter('filter', '');

    // TODO: This should be configurable somewhere, even though it's not an
    // option in the REST implementation.
    $soapRequest->setParameter('language', 'da');

    $methodParameterMap = array(
      'word' => 'word',
      'numResults' => 'number',
    );

    foreach ($methodParameterMap as $method => $parameter) {
      $getter = 'get'.ucfirst($method);
      if ($value = $this->$getter()) {
        $soapRequest->setParameter($parameter, $value);
      }
    }

    return $soapRequest;
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
}

