<?php

require_once dirname(__FILE__) . '/../vendor/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../lib/search/data/identifier/TingClientRecordIdentifier.php';

class TingClientRecordIdentifierTest extends UnitTestCase {

	function testIsbnParsing()
	{
		$identifier = TingClientRecordIdentifier::factory('ISBN: 9788772710624');
		$this->assertEqual($identifier->getType(), TingClientRecordIdentifier::ISBN, 'Unable to determine type from ISBN string');
		$this->assertEqual($identifier->getId(), '9788772710624', 'Uable to determine id from ISBN string');
	}
	
	function testIssnParsing()
	{
		$identifier = TingClientRecordIdentifier::factory('ISSN: 0907-1814');
		$this->assertEqual($identifier->getType(), TingClientRecordIdentifier::ISSN, 'Unable to determine type from ISSN string');
		$this->assertEqual($identifier->getId(), '0907-1814', 'Uable to determine id from ISSN string');
	}

}
