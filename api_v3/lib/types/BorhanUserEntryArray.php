<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanUserEntryArray extends BorhanTypedArray
{
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanUserEntryArray();
		foreach($arr as $obj)
		{
			/* @var $obj UserEntry */
			$nObj = BorhanUserEntry::getInstanceByType($obj->getType());
			if (!$nObj)
			{
				throw new BorhanAPIException(BorhanErrors::USER_ENTRY_OBJECT_TYPE_ERROR, $obj->getType(), $obj->getId());
			}
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}

		return $newArr;
	}

	public function __construct( )
	{
		return parent::__construct ( "BorhanUserEntry" );
	}
}
