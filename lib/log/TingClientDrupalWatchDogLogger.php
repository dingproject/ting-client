<?php

require_once dirname(__FILE__).'/TingClientLogger.php';

/**
 * Ting logger wrapper for the Drupal watchdog API.
 * 
 * @see http://api.drupal.org/api/function/watchdog/
 */
class TingClientDrupalWatchDogLogger extends TingClientLogger 
{
	
	public function doLog($message, $severity)
	{
		watchdog(	'ting client', $message, null, 
							constant('WATCHDOG_'.$severity), 
							'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
	}	
	
}
