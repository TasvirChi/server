<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectTaskEngine
 */
class KObjectTaskDeleteEntryEngine extends KObjectTaskEntryEngineBase
{
	/**
	 * @param BorhanBaseEntry $object
	 */
	function processObject($object)
	{
		$client = $this->getClient();
		$entryId = $object->id;
		BorhanLog::info('Deleting entry '. $entryId);
		$client->baseEntry->delete($entryId);
	}
}