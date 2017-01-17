<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectTaskEngine
 */
class KObjectTaskStorageExportEngine extends KObjectTaskEntryEngineBase
{

	/**
	 * @param BorhanBaseEntry $object
	 */
	function processObject($object)
	{
		/** @var BorhanStorageExportObjectTask $objectTask */
		$objectTask = $this->getObjectTask();
		if (is_null($objectTask))
			return;

		$entryId = $object->id;
		$storageId = $objectTask->storageId;
		if (!$storageId)
			throw new Exception('Storage profile was not configured');

		BorhanLog::info("Submitting entry export for entry $entryId to remote storage $storageId");

		$client = $this->getClient();
		$this->impersonate($object->partnerId);
		$client->baseEntry->export($entryId, $storageId);
		$this->unimpersonate();
	}
}