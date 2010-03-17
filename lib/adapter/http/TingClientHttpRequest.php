<?php

require_once dirname(__FILE__).'/../../exception/TingClientException.php';

class TingClientHttpRequest
{
	const GET = 'GET';
	const POST = 'POST';
	static public $METHODS = array(self::GET,
																	self::POST);
	
	private $method;
	private $baseUrl;
	private $parameters = array(self::GET => array('encoding' => 'utf-8'),
															self::POST => array());
	
	public function setMethod($method)
	{
		$this->validateMethod($method);
		$this->method = $method;
	}
	
	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	public function setParameter($method, $name, $value)
	{
		$this->validateMethod($method);
		$this->parameters[$method][$name] = $value;
	}
	
	public function setGetParameter($name, $value)
	{
		$this->setParameter(self::GET, $name, $value);
	}
	
	public function setPostParameter($name, $value)
	{
		$this->setParameter(self::POST, $name, $value);
	}
	
	public function setParameters($method, $array)
	{
		$this->validateMethod($method);
		$this->parameters[$method] = array_merge($this->parameters[$method], $array);
	}
	
	public function getMethod()
	{
		return $this->method;
	}

	public function getBaseUrl()
	{
		return $this->baseUrl;
	}
	
	public function getUrl()
	{
    $parameters = $this->getGetParameters();
    $parameters = self::encodeUtf8($parameters);
    
    $elements = array();
    foreach ($parameters as $key => $value) {
    	$elements[] = self::buildQueryElement($key, $value);
    }
    $elements = implode('&', $elements);
    
    return $this->baseUrl.'?'.$elements;
	}
	
	public function getParameters($method)
	{
		return $this->parameters[$method];
	}
	
	public function getGetParameters()
	{
		return $this->getParameters(self::GET);
	}
	
	public function getPostParameters()
	{
		return $this->getParameters(self::POST);
	}
	
	private function validateMethod($method)
	{
		if (!in_array($method, self::$METHODS))
		{
			throw new TingClientException('Unrecognized method "'.$method.'"');
		}
	}
	
	private static function encodeUtf8($value) {
		if (is_array($value)) {
			foreach ($value as &$v) {
				$v = self::encodeUtf8($v);
			}
		} else {
		  if (!(preg_match('/^./us', $value) == 1)) {
        $value = utf8_encode($value);
      }
		}
		
		return $value;
	}
	
	private static function buildQueryElement($key, $value) {
		if (is_array($value)) {
			$element = array();
			foreach ($value as $i => $v) {
        $element[] = self::buildQueryElement($key.'['.$i.']', $v);
			}
			$element = implode('&', $element);
		}	else {
      $element = $key.'='.urlencode($value);			
		}
		
		return $element;
	}
	
}
