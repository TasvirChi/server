<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanListResponse extends BorhanObject
{
	/**
	 * @var int
	 * @readonly
	 */
	public $totalCount;

	/* (non-PHPdoc)
	 * @see BorhanObject::loadRelatedObjects($responseProfile)
	 */
	public function loadRelatedObjects(BorhanDetachedResponseProfile $responseProfile)
	{
		if($this->objects)
		{
			$this->objects->loadRelatedObjects($responseProfile);
		}
	}
}