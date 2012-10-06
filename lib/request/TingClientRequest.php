<?php
/**
 * @file
 * TingClientRequest abstract class definition.
 */

/**
 * Base class for Ting client requests.
 */
abstract class TingClientRequest {
  protected $wsdlUrl;
  protected $parameters = array();

  /**
   * Process the response from OpenSearch.
   */
  abstract public function processResponse(stdClass $response);

  /**
   * Return the request object instance, ready for use.
   */
  abstract protected function getRequest();

  /**
   * Construct the class with the WSDL URL.
   */
  public function __construct($wsdl_url) {
    $this->wsdlUrl = $wsdl_url;
  }

  /**
   * Get the WSDL URL.
   */
  public function getWsdlUrl() {
    return $this->wsdlUrl;
  }

  /**
   * Set the WSDL URL.
   */
  public function setwsdlUrl($wsdl_url) {
    $this->wsdlUrl = $wsdl_url;
  }

  /**
   * Get the value of a single request parameter.
   */
  public function getParameter($name) {
    return $this->parameters[$name];
  }

  /**
   * Set the value of a single request parameter.
   */
  public function setParameter($name, $value) {
    $this->parameters[$name] = $value;
  }


  /**
   * Get the value of all currently set request parameters.
   */
  public function getParameters() {
    return $this->parameters;
  }

  /**
   * Set the value multiple request parameters.
   *
   * @param array $parameters
   *   Array of parameters to set, as key/value pairs.
   */
  public function setParameters(array $parameters) {
    $this->parameters = array_merge($this->parameters, $parameters);
  }

  /**
   * Get the number of results from a completed request.
   */
  public function getNumResults() {
    return $this->numResults;
  }

  /**
   * Set the number of results from a completed request.
   */
  public function setNumResults($result_count) {
    $this->numResults = $result_count;
  }

  /**
   * Execute the request with a given request adapter.
   */
  public function execute(TingClientRequestAdapter $adapter) {
    return $adapter->execute($this->getRequest());
  }

  /**
   * Parse the JSON returned from the server.
   */
  public function parseResponse($response_string) {
    $response = json_decode($response_string);

    if (!$response) {
      throw new TingClientException('Unable to decode response as JSON: ' . $response_string);
    }

    if (!is_object($response)) {
      throw new TingClientException('Unexpected JSON response: ' . var_export($response, TRUE));
    }

    return $this->processResponse($response);
  }

  /**
   * Get value from a BadgerFish object.
   */
  protected static function getValue($object) {
    if (is_array($object)) {
      return array_map(array('RestJsonTingClientRequest', 'getValue'), $object);
    }
    else {
      return self::getBadgerFishValue($object, '$');
    }
  }

  /**
   * Get attribute value from a BadgerFish object.
   */
  protected static function getAttributeValue($object, $attribute_name) {
    $attribute = self::getAttribute($object, $attribute_name);
    if (is_array($attribute)) {
      return array_map(array('RestJsonTingClientRequest', 'getValue'), $attribute);
    }
    else {
      return self::getValue($attribute);
    }
  }

  /**
   * Get attribute from a BadgerFish object.
   */
  protected static function getAttribute($object, $attribute_name) {
    // Ensure that attribute names are prefixed with @.
    $attribute_name = ($attribute_name[0] != '@') ? '@' . $attribute_name : $attribute_name;
    return self::getBadgerFishValue($object, $attribute_name);
  }

  /**
   * Get namespace from a BadgerFish object.
   */
  protected static function getNamespace($object) {
    return self::getBadgerFishValue($object, '@');
  }

  /**
   * Helper to reach JSON BadgerFish values with tricky attribute names.
   */
  protected static function getBadgerFishValue($object, $value_name) {
    $properties = get_object_vars($object);
    if (isset($properties[$value_name])) {
      $value = $properties[$value_name];

      if (is_string($value)) {
        // Some values contain HTML entities - decode these.
        $value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
      }

      return $value;
    }
    else {
      return NULL;
    }
  }
}
