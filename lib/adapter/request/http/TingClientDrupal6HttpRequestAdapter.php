<?php

require_once dirname(__FILE__).'/TingClientHttpRequestAdapter.php';
require_once dirname(__FILE__).'/TingClientHttpRequestFactory.php';
require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/../../../exception/TingClientException.php';

class TingClientDrupal6HttpRequestAdapter extends TingClientHttpRequestAdapter 
{
	private $numRetries;
	
	function __construct(TingClientHttpRequestFactory $httpRequestFactory, $options = array())
	{
		$this->numRetries = (isset($options['num_retries'])) ? $options['num_retries'] : 3;
		parent::__construct($httpRequestFactory);
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