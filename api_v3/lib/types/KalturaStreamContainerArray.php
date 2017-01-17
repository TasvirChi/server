<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanStreamContainerArray extends BorhanTypedArray
{
	public static function fromDbArray($arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanStreamContainerArray();
		if ($arr == null)
			return $newArr;

		foreach ($arr as $obj)
		{
			$stream = new BorhanStreamContainer();
			$stream->fromObject( $obj, $responseProfile );
			$newArr[] = $stream;
		}

		return $newArr;
	}

	public function __construct()
	{
		parent::__construct("BorhanStreamContainer");
	}
}