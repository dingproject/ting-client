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
			//getting an object is actually just performing a search using specific indexes
			$request = new RestJsonTingClientSearchRequest($this->baseUrl);
			
			//determine which id to use and the corresponding index
			$ids = array(	'fedoraNormPid' => 'objectId',
										'rec.id' => 'localId');
			foreach ($ids as $index => $attribute)
			{
				$id = call_user_func(array($this, 'get'.ucfirst($attribute)));
				if ($id)
				{	
					$request->setQuery($index.':'.str_replace(':', '_', $id));
					break;
				}
			}
			
			//transfer agency from object to search request
			if ($agency = $this->getAgency()) {
				$request->setAgency($agency);
			}
			
			//we only need one object
			$request->setNumResults(1);
			
			$searchResult = $request->execute($adapter);

			return $searchResult->collections[0]->objects[0];
		}

		protected function getHttpRequest() {}
	
		protected function parseJson($responseString) {}
}