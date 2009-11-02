<?php

class TingClientObjectIdentifier
{
	
  const FAUST_NUMBER = 'FAUST';
	const ISBN = 'ISBN';
	const ISSN = 'ISSN';
	const URL = 'URL';
	const UNKNOWN = 'UNKNOWN';
																
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
	public static function factory($identifier, $type = NULL)
	{
    $t = self::UNKNOWN;
    if ($type == 'dkdcplus:ISBN')
    {
      $t = self::ISBN;
    }
    elseif (strpos($identifier, '|') !== FALSE)
    {
    	$t = self::FAUST_NUMBER;
		}
    
		return new TingClientObjectIdentifier($t, $identifier);
	}
	
	public function __toString()
	{
		return $this->type.':'.$this->id;	
	}
	
}

?>
