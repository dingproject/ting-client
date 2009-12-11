<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientScanRequest.php';
require_once dirname(__FILE__) . '/../../result/scan/TingClientScanResult.php';

class RestJsonTingClientScanRequest extends RestJsonTingClientRequest 
																		implements TingClientScanRequest
{

	protected $field;
	protected $prefix;
	protected $numResults;
	protected $lower;
	protected $upper;
	protected $minFrequency;
	protected $maxFrequency;
	protected $output;
	
	public function __construct($baseUrl)
	{
		parent::__construct($baseUrl);
	}
	
	protected function getHttpRequest()
	{
		$httpRequest = new TingClientHttpRequest();
		$httpRequest->setMethod(TingClientHttpRequest::GET);
		$httpRequest->setBaseUrl($this->baseUrl);
		$httpRequest->setGetParameter('outputType', 'json');
		$httpRequest->setGetParameter('action', 'openScan');

		$methodParameterMap = array('field' => 'field',
																'prefix' => 'prefix',
																'numResults' => 'limit',
																'lower' => 'lower',
																'upper' => 'upper',
																'minFrequency' => 'minFrequency',
																'maxFrequency' => 'maxFrequency',
																);
		
		foreach ($methodParameterMap as $method => $parameter)
		{
			$getter = 'get'.ucfirst($method);
			if ($value = $this->$getter())
			{
				$httpRequest->setParameter(TingClientHttpRequest::GET, $parameter, $value);
			}
		}
		
		return $httpRequest;		
	}
		
	protected function parseJson($response)
	{
		$result = new TingClientScanResult();

		if (isset($response->scanResponse->term) && is_array($response->scanResponse->term))
		{
			foreach ($response->scanResponse->term as $scanTerm)
			{
					$term = new TingClientScanTerm();
					$term->name = $scanTerm->name->{'$'};
					$term->count = $scanTerm->hitCount->{'$'};
					$result->terms[] = $term;
			}
		}

		return $result;		
	}
	
	public function getField()
	{
		return $this->field;
	}
	
	public function setField($field)
	{
		$this->field = $field;
	}
	
	public function getPrefix()
	{
		return $this->prefix;
	}
	
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}
	
	public function getNumResults()
	{
		return $this->numResults;
	}
	
	public function setNumResults($numResults)
	{
		$this->numResults = $numResults;
	}
	
	public function getLower()
	{
		return $this->lower;
	}
	
	public function setLower($lower)
	{
		$this->lower = $lower;
	}
	
	public function getUpper()
	{
		return $this->upper;
	}
	
	public function setUpper($upper)
	{
		$this->upper = $upper;
	}
	
	public function getMinFrequency()
	{
		return $this->minFrequency;
	}
	
	public function setMinFrequency($minFrequency)
	{
		$this->minFrequency = $minFrequency;
	}
	
	public function getMaxFrequency()
	{
		return $this->maxFrequency;
	}
	
	public function setMaxFrequency($maxFrequency)
	{
		$this->maxFrequency = $maxFrequency;
	}
	
	public function getOutput()
	{
		return $this->output;
	}
	
	public function setOutput($output)
	{
		$this->output = $output;
	}
		
}
