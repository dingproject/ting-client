<?php

class TingClientObjectIdentifier
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
	 * @param TingClientDublinCoreData
	 * @return TingClientRecordIdentifier
	 */
	public static function factory($identifier, TingClientDublinCoreData $object) {
		if (in_array($object->type[0], array('Bog', 'Årbog', 'Bog stor skrift', 'Billedbog', 'Tegneserie'))) {
			return new TingClientObjectIdentifier(self::ISBN, $identifier);
		}
		return new TingClientObjectIdentifier(self::UNKNOWN, $identifier);
	}
	
	public function __toString()
	{
		return $this->type.':'.$this->id;	
	}
	
}

?>
