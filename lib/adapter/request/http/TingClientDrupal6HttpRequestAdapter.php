<?php

$basePath = dirname(__FILE__);
require_once $basePath.'/TingClientHttpRequestAdapter.php';
require_once $basePath.'/TingClientHttpRequest.php';
require_once $basePath.'/../../../exception/TingClientException.php';

class TingClientDrupal6HttpRequestAdapter extends TingClientHttpRequestAdapter 
{
	private $numRetries;
	
	function __construct($options = array())
	{
		$this->numRetries = (isset($options['num_retries'])) ? $options['num_retries'] : 3;
	}
	
	protected function request(TingClientHttpRequest $request)
	{
		$result = drupal_http_request($request->getUrl(), array(), $request->getMethod(), NULL, $this->numRetries);
		if (isset($result->error))
		{
			throw new TingClientException('Unable to excecute Drupal HTTP request: '.$result->error, $result->code);
		}
		return $result->data;
	}
	
}