<?php

require_once dirname(__FILE__).'/TingClientRecordData.php';

class TingClientDublinCoreData implements TingClientRecordData
{
	
	public $identifier;
	public $title;
	public $creator;
	public $subject;
	public $description;
	public $publisher;
	public $contributor;
	public $date;
	public $type;
	public $format;
	public $source;
	public $language;
	public $relation;
	public $coverage;
	public $rights;
	
}

?>