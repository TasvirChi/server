<?php
/**
 * An array of BorhanStringValue
 * 
 * @package api
 * @subpackage objects
 */
class BorhanStringValueArray extends BorhanTypedArray
{
	/**
	 * @param array<string|kStringValue> $strings
	 * @return BorhanStringValueArray
	 */
	public static function fromDbArray(array $strings = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$stringArray = new BorhanStringValueArray();
		if($strings && is_array($strings))
		{
			foreach($strings as $string)
			{
				$stringObject = new BorhanStringValue();
				
				if($string instanceof kValue)
				{
					$stringObject->fromObject($string, $responseProfile);;
				}
				else
				{					
					$stringObject->value = $string;
				}
				
				$stringArray[] = $stringObject;
			}
		}
		return $stringArray;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanStringValue");
	}
}
