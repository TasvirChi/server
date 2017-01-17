<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanPlayerDeliveryTypesArray extends BorhanTypedArray
{
	public function __construct()
	{
		return parent::__construct("BorhanPlayerDeliveryType");
	}

	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$ret = new BorhanPlayerDeliveryTypesArray();
		foreach($arr as $id => $item)
		{
			$obj = new BorhanPlayerDeliveryType();
			$obj->id = $id;
			$obj->fromArray($item);
			$obj->enabledByDefault = (bool)$obj->enabledByDefault;
				
			if(isset($item['flashvars']))
				$obj->flashvars = BorhanKeyValueArray::fromDbArray($item['flashvars']);
				
			$ret[] = $obj;
		}
		return $ret;
	}
}