<?php

require_once dirname(__FILE__).'/adapter/TingClientRequestAdapter.php';
require_once dirname(__FILE__).'/request/TingClientRequest.php';
require_once dirname(__FILE__).'/log/TingClientVoidLogger.php';

class TingClient
{
	
	/**
	 * @var TingClientRequestAdapter
	 */
	private $requestAdapter;
	
	/**
	 * @var TingClientLogger
	 */
	private $logger;
	
	function __construct(	TingClientRequestAdapter $requestAdapter, 
												TingClientLogger $logger = NULL)
	{
		$this->logger = (isset($logger)) ? $logger : new TingClientVoidLogger();
		$this->requestAdapter = $requestAdapter;
		$this->requestAdapter->setLogger($this->logger);
	}
	
	function execute(TingClientRequest $request)
	{
		return $request->execute($this->requestAdapter);
	}
	
}

?>