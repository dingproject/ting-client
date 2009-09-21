<?php

require_once dirname(__FILE__) . '/RestJsonTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientSpellRequest.php';
require_once dirname(__FILE__) . '/../../result/spell/TingClientSpellSuggestion.php';

class RestJsonTingClientSpellRequest	extends RestJsonTingClientRequest
																			implements TingClientSpellRequest
{
		protected $word;
		protected $numResults;
		
		protected function getHttpRequest()
		{
			$request = new TingClientHttpRequest();
			$request->setBaseUrl($this->baseUrl);
			$request->setMethod(TingClientHttpRequest::GET);
			$request->setGetParameter('outputType', 'json');
			
			if ($this->word)
			{
				$request->setGetParameter('word', $this->word);
			}
			if ($this->numResults)
			{
				$request->setGetParameter('number', $this->numResults);
			}
			
			return $request;
		}
		
		protected function parseJson($response)
		{
			if (isset($response->error))
			{
				throw new TingClientException($response->error);
			}

			$suggestions = array();
			if (isset($response->term) && $response->term)
			{
				foreach ($response->term as $term)
				{
					$suggestions[] = new TingClientSpellSuggestion($term->suggestion, floatval(str_replace(',', '.', $term->weight)));
				}
			}
			return $suggestions;
		}

	
		public function getWord()
		{
			return $this->word;
		}
		
		public function setWord($word)
		{
			$this->word = $word;
		}
		
		public function getNumResults()
		{
			return $this->numResults;
		}
		
		public function setNumResults($numResults)
		{
			$this->numResults = $numResults;
		}
		
}
