<?php

require_once dirname(__FILE__).'/../TingClientRequest.php';

abstract class HttpTingClientRequest implements TingClientRequest
{
	protected $baseUrl;
	
	public function __construct($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}	
	
	public function execute(TingClientRequestAdapter $adapter)
	{
		return $this->parseResponse($adapter->execute($this->getHttpRequest()));
	}
	
	abstract protected function getHttpRequest();
	
	abstract protected function parseResponse($responseString);
	
}