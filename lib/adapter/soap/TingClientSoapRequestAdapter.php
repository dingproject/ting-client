<?php

require_once dirname(__FILE__).'/../TingClientRequestAdapter.php';
require_once dirname(__FILE__).'/TingClientSoapRequest.php';
require_once dirname(__FILE__).'/../../log/TingClientVoidLogger.php';

/**
 * Request adapter, running the query through PHPs SoapClient.
 */
class TingClientSoapRequestAdapter implements TingClientRequestAdapter
{

  /**
   * @var TingClientLogger
   */
  protected $logger;

  function __construct($options = array())
  {
    $this->logger = new TingClientVoidLogger();
    $this->numRetries = (isset($options['num_retries'])) ? $options['num_retries'] : 3;
  }

  private $clientInstance;

  public function executeRequest(TingClientSoapRequest $request) {
    $client = new SoapClient($request->getWsdlUrl(), array(
      'cache_wsdl' => WSDL_CACHE_BOTH,
      'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
      'soap_version'   => SOAP_1_2,
      'trace' => FALSE, // TRUE for debugging
    ));

    $requestParameters = $request->getParameters();
    $soapParameters = (object) $requestParameters;
    unset($soapParameters->action);

    $result = $client->{$requestParameters['action']}($soapParameters);

    if (isset($result->error)) {
      throw new TingClientException('Unable to excecute SOAP request: '.$result->error, $result->code);
    }

    return $result->result;
  }

  public function setLogger(TingClientLogger $logger)
  {
    $this->logger = $logger;
  }

  public function execute(TingClientSoapRequest $request)
  {
    $startTime = explode(' ', microtime());
    try {
      $response = $this->executeRequest($request);

      $stopTime = explode(' ', microtime());
      $time = floatval(($stopTime[1]+$stopTime[0]) - ($startTime[1]+$startTime[0]));
      $this->logger->log('Completed SOAP request ' . $request->getWsdlUrl() . ' (' . round($time, 3) . 's)');

      return $response;
    }
    catch (TingClientException $e)
    {
      $this->logger->log('Error handling SOAP request ' . $request->getWsdlUrl() .' : '. $e->getMessage());
      throw $e;
    }
  }
}
