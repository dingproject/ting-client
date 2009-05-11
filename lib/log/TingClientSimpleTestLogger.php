<?php

require_once dirname(__FILE__).'/TingClientLogger.php';
require_once dirname(__FILE__).'/../../vendor/simpletest/test_case.php';

class TingClientSimpleTestLogger extends TingClientLogger 
{
	
	/**
	 * @var SimpleTestCase
	 */
	private $test;
	
	public function __construct(SimpleTestCase $test)
	{
		$this->test = $test;
	}
	
	public function doLog($message, $severity)
	{
		$this->test->dump(NULL, $message);
	}	
	
}
