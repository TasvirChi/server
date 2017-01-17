<?php
/**
 * An array of BorhanIntegerValue
 * 
 * @package api
 * @subpackage objects
 */
class BorhanIntegerValueArray extends BorhanTypedArray
{
	/**
	 * @param array<string|kIntegerValue> $strings
	 * @return BorhanIntegerValueArray
	 */
	public static function fromDbArray(array $ints = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$intArray = new BorhanIntegerValueArray();
		if($ints && is_array($ints))
		{
			foreach($ints as $int)
			{
				$intObject = new BorhanStringValue();
				
				if($int instanceof kValue)
				{
					$intObject->fromObject($int, $responseProfile);;
				}
				else
				{					
					$intObject->value = $int;
				}
				
				$intArray[] = $intObject;
			}
		}
		return $intArray;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanIntegerValue");
	}
}
