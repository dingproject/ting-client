<?php

require_once dirname(__FILE__) . '/XmlTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientCollectionRequest.php';

class XmlTingClientCollectionRequest extends XmlTingClientRequest
                                     implements TingClientCollectionRequest
{
  protected $id;
  protected $agency;

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

  public function execute(TingClientRequestAdapter $adapter)
  {
    $request = new XmlTingClientSearchRequest($this->wsdlUrl);
    $request->setQuery('rec.id='.$this->id);
    $request->setAgency($this->agency);
    $request->setAllObjects(true);
    $request->setNumResults(1);

    $searchResult = $request->execute($adapter);
    return $searchResult->collections[0];
  }

}
