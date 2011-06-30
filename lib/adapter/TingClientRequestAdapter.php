<?php

class TingClientRequestAdapter {
  /**
   * @var TingClientLogger
   */
  protected $logger;
 
  function __construct($options = array()) {
    $this->logger = new TingClientVoidLogger();
  }
 
  public function setLogger(TingClientLogger $logger) {
    $this->logger = $logger;
  }
 
  public function execute(TingClientRequest $request) {
    //Prepare the parameters for the SOAP request
    $soapParameters = $request->getParameters();
    // Separate the action from other parameters
    $soapAction = $soapParameters['action'];
    unset($soapParameters['action']);

    // We use JSON as the default outputType.
    if (!isset($soapParameters['outputType'])) {
      $soapParameters['outputType'] = 'json';
    }
    
    try {
      try {
        $startTime = explode(' ', microtime());
        
        $client = new NanoSOAPClient($request->getWsdlUrl());
        $response = $client->call($soapAction, $soapParameters);
  
        $stopTime = explode(' ', microtime());
        $time = floatval(($stopTime[1]+$stopTime[0]) - ($startTime[1]+$startTime[0]));
    
        $this->logger->log('Completed SOAP request ' . $soapAction . ' ' . $request->getWsdlUrl() . ' (' . round($time, 3) . 's). Request body: ' . $client->requestBodyString);
   
        // If using JSON and DKABM, we help parse it.
        if ($soapParameters['outputType'] == 'json') {
          return $request->parseResponse($response);
        }
        else {
          return $response;
        }
      } catch (NanoSOAPcURLException $e) {
        //Convert NanoSOAP exceptions to TingClientExceptions as callers
        //should not deal with protocol details
        throw new TingClientException($e->getMessage(), $e->getCode());
      }
    } catch (TingClientException $e) {
      $this->logger->log('Error handling SOAP request ' . $soapAction . ' ' . $request->getWsdlUrl() .': '. $e->getMessage());
      throw $e;
    }
  }
}
