<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanServerNodeArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanServerNodeArray();
		foreach($arr as $obj)
		{
			$nObj = BorhanServerNode::getInstance($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanServerNode" );
	}
}