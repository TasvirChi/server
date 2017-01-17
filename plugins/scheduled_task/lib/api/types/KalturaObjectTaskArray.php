<?php

/**
 * @package plugins.scheduledTask
 * @subpackage api.objects
 */
class BorhanObjectTaskArray extends BorhanTypedArray
{
	public function __construct()
	{
		parent::__construct('BorhanObjectTask');
	}

	public static function fromDbArray(array $dbArray, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$apiArray = new BorhanObjectTaskArray();
		foreach($dbArray as $dbObject)
		{
			/** @var kObjectTask $dbObject */
			$apiObject = BorhanObjectTask::getInstanceByDbObject($dbObject);
			if (is_null($apiObject))
			{
				throw new Exception('Couldn\'t load api object for db object '.$dbObject->getType());
			}
			$apiObject->fromObject($dbObject, $responseProfile);;
			$apiArray[] = $apiObject;
		}

		return $apiArray;
	}
}