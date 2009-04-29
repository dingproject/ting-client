<?php

require_once dirname(__FILE__).'/../../lib/adapter/request/http/TingClientHttpRequestAdapter.php';
require_once dirname(__FILE__).'/../../lib/adapter/request/http//TingClientHttpRequest.php';
require_once dirname(__FILE__).'/../../lib/exception/TingClientException.php';

class TingClientMockHttpRequestAdapter extends TingClientHttpRequestAdapter 
{
	private $response;
	private $requiredRequest;
	
	public function setResponse($response, TingClientHttpRequest $requiredRequest = NULL)
	{
		$this->response = $response;
		$this->requiredRequest = $requiredRequest;
	}
	
	protected function request(TingClientHttpRequest $request)
	{
		if (isset($this->requiredRequest) &&
				$request != $this->requiredRequest)
		{
			throw new TingClientException('Request does not match required request'); 
		}
		
		return $this->response;
	}
	
}

?>