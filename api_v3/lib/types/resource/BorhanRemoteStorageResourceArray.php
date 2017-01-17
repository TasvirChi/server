<?php
/**
 * Used to ingest media that is available on remote server and accessible using the supplied URL, the media file won't be downloaded but a file sync object of URL type will point to the media URL.
 *
 * @package api
 * @subpackage objects
 */
class BorhanRemoteStorageResourceArray extends BorhanTypedArray
{
	/**
	 * @param array<kRemoteStorageResource> $arr
	 * @return BorhanRemoteStorageResourceArray
	 */
	public static function fromDbArray(array $arr, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$newArr = new BorhanRemoteStorageResourceArray();
		foreach($arr as $obj)
		{
			$nObj = new BorhanRemoteStorageResource();
			$nObj->fromObject($obj, $responseProfile);
			$newArr[] = $nObj;
		}

		return $newArr;
	}
	
	public function __construct()
	{
		parent::__construct("BorhanRemoteStorageResource");
	}
}