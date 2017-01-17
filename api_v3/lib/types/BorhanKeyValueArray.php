<?php
/**
 * An array of BorhanKeyValue
 * 
 * @package api
 * @subpackage objects
 */
class BorhanKeyValueArray extends BorhanTypedArray
{
	public static function fromDbArray(array $pairs = null)
	{
		return self::fromKeyValueArray($pairs);
	}
	
	protected function appendFromArray(array $pairs, $prefix = '')
	{
		foreach($pairs as $key => $value)
		{
			if(is_array($value))
			{
				$this->appendFromArray($value, "$key.");
				continue;
			}
			
			$pairObject = new BorhanKeyValue();
			$pairObject->key = $prefix . $key;
			$pairObject->value = $value;
			$this[] = $pairObject;
		}
	}
	
	public static function fromKeyValueArray(array $pairs = null)
	{
		$pairsArray = new BorhanKeyValueArray();
		if($pairs && is_array($pairs))
		{
			foreach($pairs as $key => $value)
			{
				if(is_array($value))
				{
					$pairsArray->appendFromArray($value, "$key.");
					continue;
				}
				
				$pairObject = new BorhanKeyValue();
				$pairObject->key = $key;
				$pairObject->value = $value;
				$pairsArray[] = $pairObject;
			}
		}
		return $pairsArray;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanKeyValue");
	}
	
	public function toObjectsArray()
	{
		$ret = array();
		foreach ($this->toArray() as $keyValueObject)
		{
			/* @var $keyValueObject BorhanKeyValue */
			$ret[$keyValueObject->key] = $keyValueObject->value;
		}
		
		return $ret;
	}
}
