<?php

$basePath = dirname(__FILE__);
require_once $basePath.'/TingClientHttpRequestAdapter.php';
require_once $basePath.'/TingClientHttpRequest.php';
require_once $basePath.'/../../../exception/TingClientException.php';

class TingClientZfHttpRequestAdapterabstract extends TingClientHttpRequestAdapter 
{
	
	/**
	 * @var Zend_Http_Client
	 */
	private $client;
	
	function __construct(Zend_Http_Client $client)
	{
		$this->client = $client;
	}
	
	protected function request(TingClientHttpRequest $request)
	{
		//Transfer request configuration to Zend Client 
		$method = $request->getMethod();
		$this->client->setMethod(self::$method);
		$this->client->setUri($request->getBaseUrl());
		$this->client->setParameterGet($request->getParameters(TingClientHttpRequest::GET));
		$this->client->setParameterPost($request->getParameters(TingClientHttpRequest::POST));
		
		//Check for errors
		$response = $this->client->request();
		if ($response->isError())
		{
			throw new TingClientException('Unable to excecute Zend Framework HTTP request: '.$response->getMessage(), $response->getStatus());
		}
		
		return $response->getBody();
	}
	
}