<?php

/**
 * @package plugins.scheduledTask
 * @subpackage api.objects
 */
abstract class BorhanObjectTask extends BorhanObject
{
	/**
	 * @readonly
	 * @var BorhanObjectTaskType
	 */
	public $type;

	/**
	 * @var bool
	 */
	public $stopProcessingOnError;

	/*
	 */
	private static $map_between_objects = array(
		'type',
		'stopProcessingOnError',
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			$dbObject = new kObjectTask();

		return parent::toObject($dbObject, $skip);
	}

	/**
	 * @param array $propertiesToSkip
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validatePropertyNotNull('stopProcessingOnError');
	}

	static function getInstanceByDbObject(kObjectTask $dbObject)
	{
		switch($dbObject->getType())
		{
			case ObjectTaskType::DELETE_ENTRY:
				return new BorhanDeleteEntryObjectTask();
			case ObjectTaskType::MODIFY_CATEGORIES:
				return new BorhanModifyCategoriesObjectTask();
			case ObjectTaskType::DELETE_ENTRY_FLAVORS:
				return new BorhanDeleteEntryFlavorsObjectTask();
			case ObjectTaskType::CONVERT_ENTRY_FLAVORS:
				return new BorhanConvertEntryFlavorsObjectTask();
			case ObjectTaskType::DELETE_LOCAL_CONTENT:
				return new BorhanDeleteLocalContentObjectTask();
			case ObjectTaskType::STORAGE_EXPORT:
				return new BorhanStorageExportObjectTask();
			case ObjectTaskType::MODIFY_ENTRY:
				return new BorhanModifyEntryObjectTask();
			default:
				return BorhanPluginManager::loadObject('BorhanObjectTask', $dbObject->getType());
		}
	}
}
