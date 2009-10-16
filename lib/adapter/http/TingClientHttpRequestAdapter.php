<?php

require_once dirname(__FILE__).'/../TingClientRequestAdapter.php';
require_once dirname(__FILE__).'/TingClientHttpRequest.php';
require_once dirname(__FILE__).'/../../log/TingClientVoidLogger.php';

abstract class TingClientHttpRequestAdapter implements TingClientRequestAdapter
{
	
	/**
	 * @var TingClientLogger
	 */
	protected $logger;

	function __construct()
	{
		$this->logger = new TingClientVoidLogger();
	}
	
	public function setLogger(TingClientLogger $logger)
	{
		$this->logger = $logger;
	}
	
	public function execute(TingClientHttpRequest $request)
	{
		$startTime = explode(' ', microtime());
		try {
			$response = $this->executeRequest($request);

			$stopTime = explode(' ', microtime());
			$time = floatval(($stopTime[1]+$stopTime[0]) - ($startTime[1]+$startTime[0]));
			$this->logger->log('Completed HTTP request '.$request->getUrl().' ('.round($time, 3).'s)');
			
			return $response;
		} 
		catch (TingClientException $e)
		{
			$this->logger->log('Error handling HTTP request '.$request->getUrl().' : '.$e->getMessage());
			throw $e;
		}
	}
	
	protected abstract function executeRequest(TingClientHttpRequest $request);
	
}