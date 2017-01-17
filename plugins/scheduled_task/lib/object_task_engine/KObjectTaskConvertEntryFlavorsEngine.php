<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectTaskEngine
 */
class KObjectTaskConvertEntryFlavorsEngine extends KObjectTaskEntryEngineBase
{
	/**
	 * @param BorhanBaseEntry $object
	 */
	function processObject($object)
	{
		/** @var BorhanConvertEntryFlavorsObjectTask $objectTask */
		$objectTask = $this->getObjectTask();
		$entryId = $object->id;
		$reconvert = $objectTask->reconvert;

		$client = $this->getClient();
		$flavorParamsIds = explode(',', $objectTask->flavorParamsIds);
		foreach($flavorParamsIds as $flavorParamsId)
		{
			try
			{
				$this->impersonate($object->partnerId);
				$flavorAssetFilter = new BorhanFlavorAssetFilter();
				$flavorAssetFilter->entryIdEqual = $entryId;
				$flavorAssetFilter->flavorParamsIdEqual = $flavorParamsId;
				$flavorAssetFilter->statusEqual = BorhanFlavorAssetStatus::READY;
				$flavorAssetResponse = $client->flavorAsset->listAction($flavorAssetFilter);
				if (!count($flavorAssetResponse->objects) || $reconvert)
					$client->flavorAsset->convert($entryId, $flavorParamsId);

				$this->unimpersonate();
			}
			catch(Exception $ex)
			{
				BorhanLog::err(sprintf('Failed to convert entry id %s with flavor params id %s', $entryId, $flavorParamsId));
				BorhanLog::err($ex);
			}
		}
	}
}