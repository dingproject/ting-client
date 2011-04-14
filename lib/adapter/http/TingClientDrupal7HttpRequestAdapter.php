<?php

class TingClientDrupal7HttpRequestAdapter extends TingClientHttpRequestAdapter {
	public $max_redirects;
	
	function __construct($options = array()) {
		$this->max_redirects = (isset($options['max_redirects'])) ? $options['max_redirects'] : 3;
	}
	
	public function executeRequest(TingClientHttpRequest $request) {
    timer_start('ting_net');

    $result = drupal_http_request($request->getUrl(), array(
      'method' => $request->getMethod(),
      'max_redirects' => $this->max_redirects,
    ));

    timer_stop('ting_net');

		if (isset($result->error)) {
			throw new TingClientException('Unable to excecute Drupal HTTP request: ' . $result->error, $result->code);
		}

		return $result->data;
	}
}

