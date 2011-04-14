<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientCollectionRequest.php';

class RestJsonTingClientCollectionRequest extends RestJsonTingClientRequest
																					implements TingClientCollectionRequest
{

		protected $id;
	  protected $agency;
                protected $allRelations;
                protected $relationData;

		function getObjectId()
		{
			return $id;
		}

		function setObjectId($id)
		{
			$this->id = $id;
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
			$request = new RestJsonTingClientSearchRequest($this->baseUrl);
			$request->setQuery('rec.id='.$this->id);
			$request->setAgency($this->agency);
			$request->setAllObjects(true);

			if ($allRelations = $this->getAllRelations()) {
				$request->setAllRelations($allRelations);
                                $request->setRelationData($this->getRelationData());
			}

                        $request->setNumResults(1);

			$searchResult = $request->execute($adapter);
			return $searchResult->collections[0];
		}

		protected function getHttpRequest() {}

		protected function parseJson($responseString) {}

}
