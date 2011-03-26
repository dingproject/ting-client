<?php

require_once dirname(__FILE__) . '/XmlTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientObjectRecommendationRequest.php';
require_once dirname(__FILE__) . '/../../result/recommendation/TingClientObjectRecommendation.php';

class XmlTingClientObjectRecommendationRequest extends XmlTingClientRequest
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

    protected function getSoapRequest()
    {
      $request = new TingClientSoapRequest();
      $request->setWsdlUrl($this->wsdlUrl);
      $request->setParameter('action', 'ADHLRequest');
      $request->setParameter('format', 'dkabm');

      if ($this->isbn)
      {
        $request->setParameter('isbn', $this->isbn);
      }

      if ($this->numResults)
      {
        $request->setParameter('numRecords', $this->numResults);
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
        $request->setParameter($sex);
      }

      if ($this->minAge || $this->maxAge)
      {
        $minAge = ($this->minAge) ? $this->minAge : 0;
        $maxAge = ($this->maxAge) ? $this->maxAge : 100;
        $request->setParameter('minAge', $minAge);
        $request->setParameter('maxAge', $maxAge);
      }

      if ($this->fromDate || $this->toDate)
      {
        $request->setParameter('from', $this->fromDate);
        $request->setParameter('to', $this->toDate);
      }

      return $request;
    }

}
