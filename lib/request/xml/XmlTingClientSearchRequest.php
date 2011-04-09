<?php

require_once dirname(__FILE__) . '/XmlTingClientRequest.php';
require_once dirname(__FILE__) . '/../base/TingClientSearchRequest.php';
require_once dirname(__FILE__) . '/../../result/search/TingClientSearchResult.php';


class XmlTingClientSearchRequest extends XmlTingClientRequest
                                 implements TingClientSearchRequest
{
    /**
     * Prefix to namespace URI map.
     */
    static $namespaces = array(
      '' => 'http://oss.dbc.dk/ns/opensearch',
      'xs' => 'http://www.w3.org/2001/XMLSchema',
      'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
      'oss' => 'http://oss.dbc.dk/ns/osstypes',
      'dc' => 'http://purl.org/dc/elements/1.1/',
      'dkabm' => 'http://biblstandard.dk/abm/namespace/dkabm/',
      'dcmitype' => 'http://purl.org/dc/dcmitype/',
      'dcterms' => 'http://purl.org/dc/terms/',
      'ac' => 'http://biblstandard.dk/ac/namespace/',
      'dkdcplus' => 'http://biblstandard.dk/abm/namespace/dkdcplus/',
    );

    protected $query;
    protected $facets = array();
    protected $numFacets;
    protected $format;
    protected $start;
    protected $numResults;
    protected $sort;
    protected $allObjects;
    protected $allRelations;
    protected $relationData;
    protected $agency;

    public function __construct($wsdlUrl)
    {
      parent::__construct($wsdlUrl);
    }

    protected function getSoapRequest()
    {
      $soapRequest = new TingClientSoapRequest();
      $soapRequest->setWsdlUrl($this->wsdlUrl);
      $soapRequest->setParameter('action', 'search');
      $soapRequest->setParameter('format', 'dkabm');

      $methodParameterMap = array(
        'query' => 'query',
        'facets' => 'facets.facetName',
        'numFacets' => 'facets.numberOfTerms',
        'format' => 'format',
        'start' => 'start',
        'numResults' => 'stepValue',
        'sort' => 'sort',
        'allObjects' => 'allObjects',
        'allRelations' => 'allRelations',
        'relationData' => 'relationData',
        'agency' => 'agency',
      );

      foreach ($methodParameterMap as $method => $parameter)
      {
        $getter = 'get'.ucfirst($method);
        if ($value = $this->$getter())
        {
          $soapRequest->setParameter($parameter, $value);
        }
      }

      return $soapRequest;
    }

    public function getQuery()
    {
      return $this->query;
    }

    public function setQuery($query)
    {
      $this->query = $query;
    }

    public function getFacets()
    {
      return $this->facets;
    }

    public function setFacets($facets)
    {
      $this->facets = $facets;
    }

    function getNumFacets()
    {
      return $this->numFacets;
    }

    function setNumFacets($numFacets)
    {
      $this->numFacets = $numFacets;
    }

    public function getFormat()
    {
      return $this->format;
    }

    public function setFormat($format)
    {
      $this->format = $format;
    }

    public function getStart()
    {
      return $this->start;
    }

    public function setStart($start)
    {
      $this->start = $start;
    }

    function getNumResults()
    {
      return $this->numResults;
    }

    function setNumResults($numResults)
    {
      $this->numResults = $numResults;
    }

    public function getSort()
    {
      return $this->sort;
    }

    public function setSort($sort)
    {
      $this->sort = $sort;
    }

    function getAllObjects()
    {
      return $this->allObjects;
    }

    function setAllObjects($allObjects)
    {
      $this->allObjects = $allObjects;
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

    function getAgency()
    {
      return $this->agency;
    }

    function setAgency($agency)
    {
      $this->agency = $agency;
    }
}
