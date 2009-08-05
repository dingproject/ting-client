<?php

require_once dirname(__FILE__).'/TingClientDublinCoreData.php';
require_once dirname(__FILE__).'/../identifier/TingClientObjectIdentifier.php';

class TingClientObjectDataFactory
{
	
	public function fromSearchObjectData($objectData)
	{
		$formats = array('dc' => 'TingClientDublinCoreData');
		
		$data = false;
		$dataArray = array();
		foreach ($formats as $name => $dataClass)
		{
			$data = new $dataClass();
			if (is_array($objectData) &&
					isset($objectData[$name]))
			{
				$dataArray = $objectData[$name];
			} elseif (is_object($objectData) &&
								isset($objectData->$name)) {
				$dataArray = $objectData->$name;
			}
		}
		
		if (!$data)
		{
			throw new TingClientException('Search record does not contain recognized data format');
		}
		
		foreach ($objectData->dc as $attribute => $value)
		{
			if ($attribute == 'identifier' && $value)
			{	
				$data->$attribute = array();
				foreach ($value as $i => $v)
				{
					array_push($data->$attribute, TingClientObjectIdentifier::factory($v));
				}
			}
			else
			{
				$data->$attribute = $value;
			}
		}
		
		return $data;	
	}
	
}
