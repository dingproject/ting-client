<?php

class TingClientRequest {
  private $wsdlUrl;
  private $parameters = array();

  public function __construct($wsdlUrl) {
    $this->wsdlUrl = $wsdlUrl;
  }

  public function setwsdlUrl($wsdlUrl) {
    $this->wsdlUrl = $wsdlUrl;
  }

  public function setParameter($name, $value) {
    $this->parameters[$name] = $value;
  }

  public function setParameters($name, $array) {
    $this->parameters = array_merge($this->parameters, $array);
  }

  public function getNumResults() {
    return $this->numResults;
  }
  
  public function setNumResults($numResults) {
    $this->numResults = $numResults;
  }

  public function getWsdlUrl() {
    return $this->wsdlUrl;
  }

  public function getParameters() {
    return $this->parameters;
  }

  public function execute(TingClientRequestAdapter $adapter) {
    return $adapter->execute($this->getRequest());
  }
}

