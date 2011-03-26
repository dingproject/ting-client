<?php

require_once dirname(__FILE__).'/../TingClientRequest.php';

abstract class SoapTingClientRequest implements TingClientRequest
{
	protected $wsdlUrl;

	public function __construct($wsdlUrl)
	{
		$this->wsdlUrl = $wsdlUrl;
	}

	public function execute(TingClientRequestAdapter $adapter)
	{
		return $adapter->execute($this->getSoapRequest());
	}

  public function getWsdlUrl() {
    return $this->wsdlUrl;
  }

}
