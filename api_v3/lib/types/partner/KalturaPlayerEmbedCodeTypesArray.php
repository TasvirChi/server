<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanPlayerEmbedCodeTypesArray extends BorhanTypedArray
{
	public function __construct()
	{
		return parent::__construct("BorhanPlayerEmbedCodeType");
	}
	
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$ret = new BorhanPlayerEmbedCodeTypesArray();
		foreach($arr as $id => $item)
		{
			$obj = new BorhanPlayerEmbedCodeType();
			$obj->id = $id;
			$obj->fromArray($item);
			$ret[] = $obj;
		}
		return $ret;
	}
}