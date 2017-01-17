<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanSearchItemArray extends BorhanTypedArray
{
	/**
	 * @param array $arr
	 * @return BorhanSearchItemArray
	 */
	public static function fromDbArray(array $arr = null, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanSearchItemArray();
		if(!$arr || !count($arr))
			return $newArr;
			
		foreach ( $arr as $obj )
		{
			$borhanClass = $obj->getBorhanClass();
			if(!class_exists($borhanClass))
			{
				BorhanLog::err("Class [$borhanClass] not found");
				continue;
			}
				
			$nObj = new $borhanClass();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}
		
		return $newArr;
	}
	
	/**
	 * @return array
	 */
	public function toObjectsArray()
	{
		$ret = array();
		foreach($this as $item)
		{
			$ret[] = $item->toObject();
		}
			
		return $ret;
	}
	
	public function __construct( )
	{
		return parent::__construct ( "BorhanSearchItem" );
	}
}
?>