<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientObjectRequest.php';


class RestJsonTingClientObjectRequest extends RestJsonTingClientRequest 
																			implements TingClientObjectRequest
{

		protected $id;
		protected $localId;
		protected $agency;
	
		function getObjectId()
		{
			return $this->id;
		}
		
		function setObjectId($id)
		{
			$this->id = $id;
		}
		
		function getLocalId()
		{
			return $this->localId;
		}
		
		function setLocalId($localId)
		{
			$this->localId = $localId;
		}
		
		function getAgency()
		{
			return $this->agency;
		}
		
		function setAgency($agency)
		{
			$this->agency = $agency;
		}
		
		public function execute(TingClientRequestAdapter $adapter)
		{
			$request = new RestJsonTingClientSearchRequest($this->baseUrl);
			
			//determine which id to use and the corresponding index
			$ids = array(	'fedoraPid' => 'objectId',
										'rec.id' => 'localId');
			foreach ($ids as $index => $attribute)
			{
				$id = call_user_func(array($this, 'get'.ucfirst($attribute)));
				if ($id)
				{	
					$request->setQuery($index.':'.str_replace(':', '?', $id));
					break;
				}
			}
			
			$request->setNumResults(1);
			
			$searchResult = $request->execute($adapter);
			return $searchResult->collections[0]->objects[0];
		}

		protected function getHttpRequest() {}
	
		protected function parseJson($responseString) {}
}
