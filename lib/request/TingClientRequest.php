<?php

interface TingClientRequest {
	
	public function execute(TingClientRequestAdapter $adapter);
	
}