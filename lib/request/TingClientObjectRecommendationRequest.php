<?php

class TingClientObjectRecommendationRequest extends TingClientRequest {
  protected $isbn;
  protected $numResults;
  protected $sex;
  protected $minAge;
  protected $maxAge;
  protected $fromDate;
  protected $toDate;

  public function getIsbn() {
    return $this->isbn;
  }

  public function setIsbn($isbn) {
    $this->isbn = $isbn;
  }

  function getNumResults() {
    return $this->numResults;
  }

  function setNumResults($numResults) {
    $this->numResults = $numResults;
  }

  public function getSex() {
    return $this->sex;
  }

  public function setSex($sex) {
    $this->sex = $sex;
  }

  public function getAge() {
    return array($this->minAge, $this->maxAge);
  }

  public function setAge($minAge, $maxAge) {
    $this->minAge = $minAge;
    $this->maxAge = $maxAge;
  }

  public function getDate() {
    return array($this->fromDate, $this->toDate);
  }

  public function setDate($fromDate, $toDate) {
    $this->fromDate = $fromDate;
    $this->toDate = $toDate;
  }

  protected function getRequest() {
    $this->setParameter('action', 'ADHLRequest');
    $this->setParameter('format', 'dkabm');

    if ($this->isbn) {
      $this->setParameter('isbn', $this->isbn);
    }

    if ($this->numResults) {
      $this->setParameter('numRecords', $this->numResults);
    }

    if ($this->sex) {
      switch ($this->sex) {
        case TingClientObjectRecommendationRequest::SEX_MALE:
          $sex = 'm';
          break;
        case TingClientObjectRecommendationRequest::SEX_FEMALE:
          $sex = 'k';
      }
      $this->setParameter($sex);
    }

    if ($this->minAge || $this->maxAge) {
      $minAge = ($this->minAge) ? $this->minAge : 0;
      $maxAge = ($this->maxAge) ? $this->maxAge : 100;
      $this->setParameter('minAge', $minAge);
      $this->setParameter('maxAge', $maxAge);
    }

    if ($this->fromDate || $this->toDate) {
      $this->setParameter('from', $this->fromDate);
      $this->setParameter('to', $this->toDate);
    }

    return $this;
  }
}

