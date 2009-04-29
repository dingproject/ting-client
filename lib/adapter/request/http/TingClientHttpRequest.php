<?php

$basePath = dirname(__FILE__);
require_once $basePath.'/../TingClientHttpRequest.php';


class TingClientHttpRequest
{
	const GET = 'GET';
	const POST = 'POST';
	const METHODS = array(TingClientHttpRequest::GET,
												TingClientHttpRequest::POST);
	
	private $method;
	private $baseUrl;
	private $parameters = array(TingClientHttpRequest::GET => array(), 
															TingClientHttpRequest::POST => array());
	
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
		return $this->baseUrl;
	}
	
	public function getParameters($method)
	{
		return $this->parameters[$method];
	}
	
	private function validateMethod($method)
	{
		if (!in_array($method, self::METHODS))
		{
			throw new TingClientException('Unrecognized method '.$method);
		}
	}
	
}
