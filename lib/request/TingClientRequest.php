<?php

abstract class TingClientRequest {
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

  public function parseResponse($responseString) {
    $response = json_decode($responseString);

    if (!$response) {
      throw new TingClientException('Unable to decode response as JSON: '.$responseString);
    }

    if (!is_object($response)) {
      throw new TingClientException('Unexpected JSON response: '.var_export($response, true));
    }
    return $this->processResponse($response);
  }
  
  protected static function getValue($object) {
    if (is_array($object)) {
      return array_map(array('RestJsonTingClientRequest', 'getValue'), $object);
    }
    else {
      return self::getBadgerFishValue($object, '$');
    }
  }

  protected static function getAttributeValue($object, $attributeName) {
    $attribute = self::getAttribute($object, $attributeName);
    if (is_array($attribute)) {
      return array_map(array('RestJsonTingClientRequest', 'getValue'), $attribute);
    }
    else {
      return self::getValue($attribute); 
    }
  }

  protected static function getAttribute($object, $attributeName) {
    //ensure that attribute names are prefixed with @
    $attributeName = ($attributeName[0] != '@') ? '@'.$attributeName : $attributeName;
    return self::getBadgerFishValue($object, $attributeName);
  }
  
  protected static function getNamespace($object) {
    return self::getBadgerFishValue($object, '@');
  }
  
  /**
   * Helper to reach JSON BadgerFish values with tricky attribute names.
   */
  protected static function getBadgerFishValue($badgerFishObject, $valueName) {
    $properties = get_object_vars($badgerFishObject);
    if (isset($properties[$valueName])) {
      $value = $properties[$valueName];     
      if (is_string($value)) {
        //some values contain html entities - decode these
        $value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
      }
            
      return $value;
    }
    else {
      return NULL;      
    }
  }
  
  public abstract function processResponse(stdClass $response); 
}

