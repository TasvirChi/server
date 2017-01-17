<?php

/**
 * @package plugins.scheduledTask
 * @subpackage api.objects.objectTasks
 */
class BorhanModifyCategoriesObjectTask extends BorhanObjectTask
{
	/**
	 * Should the object task add or remove categories?
	 *
	 * @var BorhanScheduledTaskAddOrRemoveType
	 */
	public $addRemoveType;

	/**
	 * The list of category ids to add or remove
	 *
	 * @var BorhanIntegerValueArray
	 */
	public $categoryIds;

	public function __construct()
	{
		$this->type = ObjectTaskType::MODIFY_CATEGORIES;
	}

	public function toObject($dbObject = null, $skip = array())
	{
		/** @var kObjectTask $dbObject */
		$dbObject = parent::toObject($dbObject, $skip);
		$dbObject->setDataValue('addRemoveType', $this->addRemoveType);
		$dbObject->setDataValue('categoryIds', $this->categoryIds);
		return $dbObject;
	}

	public function doFromObject($srcObj, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($srcObj, $responseProfile);

		/** @var kObjectTask $srcObj */
		$this->addRemoveType = $srcObj->getDataValue('addRemoveType');
		$this->categoryIds = $srcObj->getDataValue('categoryIds');
	}
}