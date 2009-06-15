<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/search/data/identifier/TingClientRecordIdentifier.php';

class TingClientRecordIdentifierTest extends UnitTestCase {
	
	function __construct()
	{
	}
	
	function testIsbnParsing()
	{
		$identifier = TingClientRecordIdentifier::factory('ISBN: 9788772710624');
		$this->assertEqual($identifier->getType(), TingClientRecordIdentifier::ISBN, 'Unable to determine type from ISBN string');
		$this->assertEqual($identifier->getId(), '9788772710624', 'Unable to determine id from ISBN string');
	}
	
	function testIssnParsing()
	{
		$identifier = TingClientRecordIdentifier::factory('ISSN: 0907-1814');
		$this->assertEqual($identifier->getType(), TingClientRecordIdentifier::ISSN, 'Unable to determine type from ISSN string');
		$this->assertEqual($identifier->getId(), '0907-1814', 'Unable to determine id from ISSN string');
	}
	
	function testUrlParsing()
	{
		$identifier = TingClientRecordIdentifier::factory('http://www.bibliotekspressen.dk/artikel.asp?id=4923');
		$this->assertEqual($identifier->getType(), TingClientRecordIdentifier::URL, 'Unable to determine type from URL string');
		$this->assertEqual($identifier->getId(), 'http://www.bibliotekspressen.dk/artikel.asp?id=4923', 'Unable to determine id from URL string');
	}

}
