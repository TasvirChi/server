<?php
/**
 * An array of BorhanBooleanValue
 * 
 * @package api
 * @subpackage objects
 */
class BorhanBooleanValueArray extends BorhanTypedArray
{
	/**
	 * @param array<string|kBooleanValue> $strings
	 * @return BorhanBooleanValueArray
	 */
	public static function fromDbArray(array $bools = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$boolArray = new BorhanBooleanValueArray();
		if($bools && is_array($bools))
		{
			foreach($bools as $bool)
			{
				$boolObject = new BorhanStringValue();
				
				if($bool instanceof kValue)
				{
					$boolObject->fromObject($bool, $responseProfile);;
				}
				else
				{					
					$boolObject->value = $bool;
				}
				
				$boolArray[] = $boolObject;
			}
		}
		return $boolArray;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanBooleanValue");
	}
}
