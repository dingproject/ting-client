<?php

class TingClientRecordIdentifier
{
	
	const ISSN = 'ISSN';
	const ISBN = 'ISBN';
	const URL = 'URL';
	const UNKNOWN = 'UNKNOWN';

	static private $ID_TYPE_MAP = array(self::ISSN => '/^ISSN:\s(.*)/i', 
																			self::ISBN => '/^ISBN:\s(.*)/i',
																			self::URL => '/^(http\:\/\/[a-zA-Z0-9_\-]+(?:\.[a-zA-Z0-9_\-]+)*\.[a-zA-Z]{2,4}(?:\/[a-zA-Z0-9_]+)*(?:\/[a-zA-Z0-9_]+\.[a-zA-Z]{2,4}(?:\?[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)?)?(?:\&[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)*)$/i');
																
	public $type;
	public $id;
	
	public function __construct($type, $id) {
		$this->type = $type;
		$this->id = $id;
	}
	
	/**
	 * @param Identifier string $string
	 * @return TingClientRecordIdentifier
	 */
	public static function factory($string)
	{
		$type = self::UNKNOWN;
		foreach (self::$ID_TYPE_MAP as $t => $regExp)
		{
			if (preg_match($regExp, $string, $matches))
			{
				$type = $t;
				$id = $matches[1];
				break;
			}
		}
		
		return new TingClientRecordIdentifier($type, $id);
	}
	
}

?>