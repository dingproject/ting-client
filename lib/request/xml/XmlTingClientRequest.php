<?php

require_once dirname(__FILE__).'/../soap/SoapTingClientRequest.php';

abstract class XmlTingClientRequest extends SoapTingClientRequest
{

  public function __construct($wsdlUrl)
  {
    parent::__construct($wsdlUrl);
  }

  protected static function getValue($object)
  {
    if (is_array($object))
    {
      return array_map(array('XmlTingClientRequest', 'getValue'), $object);
    }
    else
    {
      return self::getBadgerFishValue($object, '$');
    }
  }

  protected static function getAttributeValue($object, $attributeName)
  {
    $attribute = self::getAttribute($object, $attributeName);
    if (is_array($attribute))
    {
      return array_map(array('XmlTingClientRequest', 'getValue'), $attribute);
    }
    else
    {
      return self::getValue($attribute);
    }
  }

  protected static function getAttribute($object, $attributeName)
  {
    //ensure that attribute names are prefixed with @
    $attributeName = ($attributeName[0] != '@') ? '@'.$attributeName : $attributeName;
    return self::getBadgerFishValue($object, $attributeName);
  }

  protected static function getNamespace($object)
  {
    return self::getBadgerFishValue($object, '@');
  }

  /**
   * Helper to reach JSON BadgerFish values with tricky attribute names.
   */
  protected static function getBadgerFishValue($badgerFishObject, $valueName)
  {
    $properties = get_object_vars($badgerFishObject);
    if (isset($properties[$valueName])) {
      $value = $properties[$valueName];
      if (is_string($value))
      {
        //some values contain html entities - decode these
        $value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
      }

      return $value;
    }
    else
    {
      return NULL;
    }
  }

}
