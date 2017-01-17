<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanDeleteFileJobData extends BorhanJobData
{
	/**
	 * @var string
	 */
	public $localFileSyncPath;
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($source_object)
	 */
	public function doFromObject($sourceObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$this->localFileSyncPath = $sourceObject->getLocalFileSyncPath();
		parent::doFromObject($sourceObject, $responseProfile);
	}
	
}