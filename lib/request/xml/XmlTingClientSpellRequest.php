<?php

require_once dirname(__FILE__) . '/XmlTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientSpellRequest.php';
require_once dirname(__FILE__) . '/../../result/spell/TingClientSpellSuggestion.php';

class XmlTingClientSpellRequest extends XmlTingClientRequest
                                implements TingClientSpellRequest
{
    protected $word;
    protected $numResults;

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
