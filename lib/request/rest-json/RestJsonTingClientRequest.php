<?php

require_once dirname(__FILE__).'/../http/HttpTingClientRequest.php';

abstract class RestJsonTingClientRequest extends HttpTingClientRequest
{
	
	public function __construct($baseUrl)
	{
		parent::__construct($baseUrl);
	}
	
	public function parseResponse($responseString)
	{
		$response = json_decode($responseString);
		if (!$response)
		{
			throw new TingClientException('Unable to decode response as JSON: '.$responseString);
		}
		if (!is_object($response))
		{
			throw new TingClientException('Unexpected JSON response: '.var_export($response, true));
		}
		return $this->parseJson($response);
	}
	
	protected abstract function parseJson($response);
	
}