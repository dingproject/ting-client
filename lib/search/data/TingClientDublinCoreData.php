<?php

require_once dirname(__FILE__).'/TingClientRecordData.php';

class TingClientDublinCoreData implements TingClientRecordData
{
	
	private $identifier;
	private $title;
	private $creator;
	private $subject;
	private $description;
	private $publisher;
	private $contributor;
	private $date;
	private $type;
	private $format;
	private $source;
	private $language;
	private $relation;
	private $coverage;
	private $rights;

	function getIdentifier()
	{
		return $this->identifier;
	}
	
	function setIdentifier($identifier)
	{
		$this->identifier = $identifier;
	}
	
	function getTitle()
	{
		return $this->title;
	}
	
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	function getCreator()
	{
		return $this->creator;
	}
	
	function setCreator($creator)
	{
		$this->creator = $creator;
	}
	
	function getSubject()
	{
		return $this->subject;
	}
	
	function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	function getDescription()
	{
		return $this->description;
	}
	
	function setDescription($description)
	{
		$this->description = $description;
	}
	
	function getPublisher()
	{
		return $this->publisher;
	}
	
	function setPublisher($publisher)
	{
		$this->publisher = $publisher;
	}
	
	function getContributor()
	{
		return $this->contributor;
	}
	
	function setContributor($contributor)
	{
		$this->contributor = $contributor;
	}
	
	function getDate()
	{
		return $this->date;
	}
	
	function setDate($date)
	{
		$this->date = $date;
	}
	
	function getType()
	{
		return $this->type;
	}
	
	function setType($type)
	{
		$this->type = $type;
	}
	
	function getFormat()
	{
		return $this->format;
	}
	
	function setFormat($format)
	{
		$this->format = $format;
	}
	
	function getSource()
	{
		return $this->source;
	}
	
	function setSource($source)
	{
		$this->source = $source;
	}
	
	function getLanguage()
	{
		return $this->language;
	}
	
	function setLanguage($language)
	{
		$this->language = $language;
	}
	
	function getRelation()
	{
		return $this->relation;
	}
	
	function setRelation($relation)
	{
		$this->relation = $relation;
	}
	
	function getCoverage()
	{
		return $this->coverage;
	}
	
	function setCoverage($coverage)
	{
		$this->coverage = $coverage;
	}
	
	function getRights()
	{
		return $this->rights;
	}
	
	function setRights($rights)
	{
		$this->rights = $rights;
	}
	
}

?>