<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientObjectRecommendationRequest.php';
require_once dirname(__FILE__) . '/../../result/recommendation/TingClientObjectRecommendation.php';

class RestJsonTingClientObjectRecommendationRequest extends RestJsonTingClientRequest
																										implements TingClientObjectRecommendationRequest
{
		protected $isbn;
		protected $numResults;
		protected $sex;
		protected $minAge;
		protected $maxAge;
		protected $fromDate;
		protected $toDate;
		
		public function getIsbn()
		{
			return $this->isbn;
		}
		
		public function setIsbn($isbn)
		{
			$this->isbn = $isbn;
		}
		
		function getNumResults()
		{
			return $this->numResults;
		}
		
		function setNumResults($numResults)
		{
			$this->numResults = $numResults;
		}
		
		public function getSex()
		{
			return $this->sex;
		}
		
		public function setSex($sex)
		{
			$this->sex = $sex;
		}
	
		public function getAge()
		{
			return array($this->minAge, $this->maxAge);
		}
		
		public function setAge($minAge, $maxAge)
		{
			$this->minAge = $minAge;
			$this->maxAge = $maxAge;
		}
		
		public function getDate()
		{
			return array($this->fromDate, $this->toDate);
		}
		
		public function setDate($fromDate, $toDate)
		{
			$this->fromDate = $fromDate;
			$this->toDate = $toDate;
		}
		
		protected function getHttpRequest()
		{
			$request = new TingClientHttpRequest();
			$request->setBaseUrl($this->baseUrl);
			$request->setMethod(TingClientHttpRequest::GET);
			$request->setGetParameter('action', 'ADHLRequest');
      $request->setGetParameter('outputType', 'json');
      
			if ($this->isbn)
			{
				$request->setGetParameter('isbn', $this->isbn);
			}
			
		  if ($this->numResults)
      {
        $request->setGetParameter('numRecords', $this->numResults);
      }
			
			if ($this->sex)
			{
				switch ($this->sex)
				{
					case TingClientObjectRecommendationRequest::SEX_MALE:
						$sex = 'm';
						break;
					case TingClientObjectRecommendationRequest::SEX_FEMALE:
						$sex = 'k';
				}
				$request->setGetParameter($sex);
			}
			
			if ($this->minAge || $this->maxAge)
			{
				$minAge = ($this->minAge) ? $this->minAge : 0;
				$maxAge = ($this->maxAge) ? $this->maxAge : 100;
				$request->setGetParameter('minAge', $minAge);
				$request->setGetParameter('maxAge', $maxAge);
			}

			if ($this->fromDate || $this->toDate)
			{
				$request->setGetParameter('from', $this->fromDate);
				$request->setGetParameter('to', $this->toDate);
			}
			
			return $request;
		}
		
		protected function parseJson($response)
		{
			if (isset($response->error))
			{
				throw new TingClientException('Error handling recommendation request: '.$response->error);
			}
			
			$recommendations = array();
			if (isset($response->adhlResponse->record))
			{
				foreach($response->adhlResponse->record as $record)
				{
					$recommendation = new TingClientObjectRecommendation();
					if ($id = $this->getValue($record->recordId))
					{
						$id = explode('|', $id, 2);
						$recommendation->localId = $id[0];
						$recommendation->ownerId = (isset($id[1])) ? $id[1] : null;
						
						$recommendations[] = $recommendation;
					}
					
				}
			}
			
			return $recommendations;
		}
		
}
