<?php
/**
 * An array of BorhanString
 * 
 * @package api
 * @subpackage objects
 */
class BorhanStringArray extends BorhanTypedArray
{
	public static function fromDbArray(array $strings = null)
	{
		return self::fromStringArray($strings);
	}
	
	public static function fromStringArray(array $strings = null)
	{
		$stringArray = new BorhanStringArray();
		if($strings && is_array($strings))
		{
			foreach($strings as $string)
			{
				$stringObject = new BorhanString();
				$stringObject->value = $string;
				$stringArray[] = $stringObject;
			}
		}
		return $stringArray;
	}
	
	public function __construct()
	{
		return parent::__construct("BorhanString");
	}

}
