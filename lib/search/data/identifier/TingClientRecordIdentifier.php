<?php

class TingClientRecordIdentifier
{
	
	const ISSN = 'ISSN';
	const ISBN = 'ISBN';
	static public $TYPES = array(	self::ISSN,
																self::ISBN);
	private $type;
	private $id;
	
	public function __construct($type, $id) {
		$this->type = $type;
		$this->id = $id;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * @param Identifier string $string
	 * @return TingClientRecordIdentifier
	 */
	public static function factory($string)
	{
		$type = FALSE;
		foreach (self::$TYPES as $t)
		{
			if (strpos($string, $t) !== FALSE)
			{
				$type = $t;
				break;
			}
		}
		
		if (!$type)
		{
			throw new TingClientException('Unable to determine type from string: '.$string);	
		}
		
		$id = substr($string, strpos($string, ': ') + 2);
		
		return new TingClientRecordIdentifier($type, $id);
	}
	
}

?>