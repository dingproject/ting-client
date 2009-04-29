<?php
require_once(dirname(__FILE__).'/../vendor/simpletest/autorun.php');

class TingClientLocalTestSuide extends TestSuite {

	function __construct() {
        $this->TestSuite('Ting Client test suite');
        $this->addFile('TingClientJsonResponseAdapterTest.php');
        $this->addFile('TingClientHttpRequestTest.php');
        $this->addFile('TingClientHttpRequestFactoryTest.php');
	}
	
	public function addFile($file)
	{
		parent::addFile(dirname(__FILE__).'/'.$file);
	}
	
}
?>
