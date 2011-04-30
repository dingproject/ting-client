<?php

class TingClient {
  /**
   * @var TingClientLogger
   */
  private $logger;

  /**
   * @var TingClientRequestAdapter
   */
  private $requestAdapter;

  function __construct(TingClientRequestAdapter $requestAdapter, TingClientLogger $logger = NULL) {
    $this->logger = (isset($logger)) ? $logger : new TingClientVoidLogger();
    $this->requestAdapter = $requestAdapter;
    $this->requestAdapter->setLogger($this->logger);
  }

  function execute(TingClientRequest $request) {
    return $request->execute($this->requestAdapter);
  }
}

