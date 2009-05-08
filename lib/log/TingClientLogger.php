<?php

/**
 * Base logger class which can be injected into the Ting client. 
 */
class TingClientLogger {
	
	const EMERGENCY = 'EMERGENCY';
	const ALERT = 'ALERT';
	const CRITICAL = 'CRITICAL';
	const ERROR = 'ERROR';
	const WARNING = 'WARNING';
	const NOTICE = 'NOTICE';
	const INFO = 'INFO';
	const DEBUG = 'DEBUG';
	
  /**
   * Log a message. Override this in subclasses. Current implementation does nothing.
   *
   * @param string $message The message to log
   * @param string $severity The severity of the message
   */
	public function log($message, $severity)
	{
		
	}
	
}

?>