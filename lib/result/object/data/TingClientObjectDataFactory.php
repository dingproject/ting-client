<?php

require_once dirname(__FILE__).'/TingClientDublinCoreData.php';
require_once dirname(__FILE__).'/../identifier/TingClientObjectIdentifier.php';

class TingClientObjectDataFactory
{
	
	public function fromSearchObjectData($objectData)
	{
		if (!isset($objectData->dc))
		{
			throw new TingClientException('Search record does not contain recognized data format');
		}
		
		$data = new TingClientDublinCoreData();
		
		foreach ($objectData->dc as $attribute => $value)
		{
			if ($attribute == 'identifier' && $value)
			{	
				$data->$attribute = array();
				foreach ($value as $i => $v)
				{
					array_push($data->$attribute, TingClientObjectIdentifier::factory($v, $data));
				}
			}
			elseif ($attribute == 'local_id_hack' && $value)
			{
				$value = (is_array($value)) ? array_shift($value) : $value;
				$value = explode('|', $value, 2);
				$data->localId = $value[0];
				$data->ownerId = (isset($value[1])) ? $value[1] : null;	
			}
			else
			{
				$data->$attribute = $value;
			}
		}
		
		return $data;	
	}
	
}
