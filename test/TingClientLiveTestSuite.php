<?php
require_once(dirname(__FILE__).'/../vendor/simpletest/autorun.php');

class TingClientLiveZfTestSuide extends TestSuite
{

	function __construct()
	{
        $this->TestSuite('Ting Client Live Zend Framework test suite');
        $this->addFile('TingClientLiveSearchTest.php');
        $this->addFile('TingClientLiveScanTest.php');
        $this->addFile('TingClientLiveObjectTest.php');
        $this->addFile('TingClientLiveCollectionTest.php');
        $this->addFile('TingClientLiveObjectRecommendationTest.php');
	}
	
	public function addFile($file)
	{
		parent::addFile(dirname(__FILE__).'/'.$file);
	}
	
}
?>
