<?php

require_once dirname(__FILE__).'/TingClientHttpRequestAdapter.php';
require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/../../exception/TingClientException.php';

class TingClientZfHttpRequestAdapter extends TingClientHttpRequestAdapter 
{
	
	/**
	 * @var Zend_Http_Client
	 */
	private $client;
	
	function __construct(Zend_Http_Client $client)
	{
		$this->client = $client;
	}
	
	public function setClient(Zend_Http_Client $client)
	{
		$this->client = $client;
	}
	
	public function executeRequest(TingClientHttpRequest $request)
	{		
		//Transfer request configuration to Zend Client 
		$method = $request->getMethod();
		$class = new ReflectionClass(get_class($this->client));
		$this->client->setMethod($class->getConstant($method));
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