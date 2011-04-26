<?php

/**
 * Base logger class which can be injected into the Ting client.
 */
abstract class TingClientLogger {

  const EMERGENCY = 'EMERGENCY';
  const ALERT = 'ALERT';
  const CRITICAL = 'CRITICAL';
  const ERROR = 'ERROR';
  const WARNING = 'WARNING';
  const NOTICE = 'NOTICE';
  const INFO = 'INFO';
  const DEBUG = 'DEBUG';

  static public $levels = array(self::EMERGENCY, self::ALERT, self::CRITICAL, self::ERROR,
                                self::WARNING,  self::NOTICE, self::INFO, self::DEBUG);

  /**
   * Log a message.
   *
   * @param string $message The message to log
   * @param string $severity The severity of the message
   */
  public function log($message, $severity = self::INFO) {
    if (!in_array($severity, self::$levels)) {
      throw new TingClientException('Unsupported severity: '.$severity);
    }
    $this->doLog($message, $severity);
  }

  abstract protected function doLog($message, $severity);
}

