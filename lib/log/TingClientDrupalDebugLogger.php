<?php

/**
 * Ting logger wrapper for the Drupal watchdog API.
 *
 * @see http://api.drupal.org/api/function/watchdog/
 */
class TingClientDrupalDebugLogger extends TingClientLogger {
  public function doLog($message, $severity) {
    debug($message, $severity);
  }
}

