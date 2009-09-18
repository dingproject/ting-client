<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientCollectionRequest.php';

class RestJsonTingClientCollectionRequest extends RestJsonTingClientRequest 
																					implements TingClientCollectionRequest
{

		protected $id;
	
		function getObjectId()
		{
			return $id;
		}
		
		function setObjectId($id)
		{
			$this->id = $id;
		}

		public function execute(TingClientRequestAdapter $adapter)
		{
			$request = new RestJsonTingClientSearchRequest($this->baseUrl);
			$request->setQuery('fedoraPid:'.str_replace(':', '?', $this->id));
			$request->setAllObjects(true); 
			$request->setNumResults(1);
			
			$searchResult = $request->execute($adapter);
			return $searchResult->collections[0];
		}

		public function getHttpRequest() {}
		
		public function parseResponse($responseString) {}	
		
}
