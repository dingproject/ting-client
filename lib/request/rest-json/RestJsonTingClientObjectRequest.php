<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientObjectRequest.php';


class RestJsonTingClientObjectRequest extends RestJsonTingClientRequest
																			implements TingClientObjectRequest
{

		protected $id;
		protected $localId;
		protected $agency;
                protected $allRelations;
                protected $relationData;

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

                function getAllRelations()
		{
			return $this->allRelations;
		}

		function setAllRelations($allRelations)
		{
			$this->allRelations = $allRelations;
		}

                function getRelationData()
		{
			return $this->relationData;
		}

		function setRelationData($relationData)
		{
			$this->relationData = $relationData;
		}

		public function execute(TingClientRequestAdapter $adapter)
		{
			//getting an object is actually just performing a search using specific indexes
			$request = new RestJsonTingClientSearchRequest($this->baseUrl);

			//Determine which id to use and the corresponding index
			if ($objectId = $this->getObjectId()) {
				$request->setQuery('rec.id='.$objectId);
			} elseif ($localId = $this->getLocalId()) {
				//Use contains (:) instead of equality (=) as local id is not a complete value
				//TODO: Update to support complete faust numbers (local id and owner id)
				$request->setQuery('rec.id:'.$localId);
			}

			//transfer agency from object to search request
			if ($agency = $this->getAgency()) {
				$request->setAgency($agency);
			}

			if ($allRelations = $this->getAllRelations()) {
				$request->setAllRelations($allRelations);
                                $request->setRelationData($this->getRelationData());
			}

			//we only need one object
			$request->setNumResults(1);

			$searchResult = $request->execute($adapter);

			return $searchResult->collections[0]->objects[0];
		}

		protected function getHttpRequest() {}

		protected function parseJson($responseString) {}
}