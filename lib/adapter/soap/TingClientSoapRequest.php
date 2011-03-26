<?php

require_once dirname(__FILE__).'/../../exception/TingClientException.php';

class TingClientSoapRequest {
  private $wsdlUrl;
  private $parameters = array();

  public function setwsdlUrl($wsdlUrl) {
    $this->wsdlUrl = $wsdlUrl;
  }

  public function setParameter($name, $value) {
    $this->parameters[$name] = $value;
  }

  public function setParameters($name, $array) {
    $this->parameters = array_merge($this->parameters, $array);
  }

  public function getWsdlUrl() {
    return $this->wsdlUrl;
  }

  public function getParameters() {
    return $this->parameters;
  }
}
