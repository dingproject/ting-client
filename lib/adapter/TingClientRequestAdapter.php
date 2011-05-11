<?php

class TingClientRequestAdapter {
  /**
   * @var TingClientLogger
   */
  protected $logger;
 
  function __construct($options = array()) {
    $this->logger = new TingClientVoidLogger();
  }
 
  private $clientInstance;
 
  public function executeRequest(TingClientRequest $request) {
    $client = new NanoSOAPClient($request->getWsdlUrl());
 
    $requestParameters = $request->getParameters();
    $soapParameters = $requestParameters;
    unset($soapParameters['action']);

    // We always want serialised JSON output.
    $soapParameters['outputType'] = 'json';
 
    return $client->call($requestParameters['action'], $soapParameters);
  }
 
  public function setLogger(TingClientLogger $logger) {
    $this->logger = $logger;
  }
 
  public function execute(TingClientRequest $request) {
    $startTime = explode(' ', microtime());
    try {
      $response = $this->executeRequest($request);
 
      $stopTime = explode(' ', microtime());
      $time = floatval(($stopTime[1]+$stopTime[0]) - ($startTime[1]+$startTime[0]));
      $this->logger->log('Completed SOAP request ' . $request->getWsdlUrl() . ' (' . round($time, 3) . 's)');
 
      return $request->parseResponse($response);
    }
    catch (TingClientException $e) {
      $this->logger->log('Error handling SOAP request ' . $request->getWsdlUrl() .' : '. $e->getMessage());
      throw $e;
    }
  }
}

