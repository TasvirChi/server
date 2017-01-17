<?php

/**
 * @package plugins.scheduledTaskContentDistribution
 * @subpackage lib.objectTaskEngine
 */
class KObjectTaskDistributeEngine extends KObjectTaskEntryEngineBase
{

	/**
	 * @param BorhanBaseEntry $object
	 */
	function processObject($object)
	{
		/** @var BorhanDistributeObjectTask $objectTask */
		$objectTask = $this->getObjectTask();
		if (is_null($objectTask))
			return;

		$entryId = $object->id;
		$distributionProfileId = $objectTask->distributionProfileId;
		if (!$distributionProfileId)
			throw new Exception('Distribution profile id was not configured');

		BorhanLog::info("Trying to distribute entry $entryId with profile $distributionProfileId");

		$client = $this->getClient();
		$contentDistributionPlugin = BorhanContentDistributionClientPlugin::get($client);

		$this->impersonate($object->partnerId);
		$distributionProfile = $contentDistributionPlugin->distributionProfile->get($distributionProfileId);

		if ($distributionProfile->submitEnabled == BorhanDistributionProfileActionStatus::DISABLED)
			throw new Exception("Submit action for distribution profile $distributionProfileId id disabled");

		$entryDistribution = $this->getEntryDistribution($entryId, $distributionProfileId);
		if ($entryDistribution && $entryDistribution->status == BorhanEntryDistributionStatus::REMOVED)
		{
			BorhanLog::info("Entry distribution is in status REMOVED, deleting it completely");
			$contentDistributionPlugin->entryDistribution->delete($entryDistribution->id);
			$entryDistribution = null;
		}

		if ($entryDistribution)
		{
			BorhanLog::info("Entry distribution already exists with id $entryDistribution->id");
		}
		else
		{
			$entryDistribution = new BorhanEntryDistribution();
			$entryDistribution->distributionProfileId = $distributionProfileId;
			$entryDistribution->entryId = $entryId;
			$entryDistribution = $contentDistributionPlugin->entryDistribution->add($entryDistribution);
		}

		$shouldSubmit = false;
		switch($entryDistribution->status)
		{
			case BorhanEntryDistributionStatus::PENDING:
				$shouldSubmit = true;
				break;
			case BorhanEntryDistributionStatus::QUEUED:
				BorhanLog::info('Entry distribution is already queued');
				break;
			case BorhanEntryDistributionStatus::READY:
				BorhanLog::info('Entry distribution was already submitted');
				break;
			case BorhanEntryDistributionStatus::SUBMITTING:
				BorhanLog::info('Entry distribution is currently being submitted');
				break;
			case BorhanEntryDistributionStatus::UPDATING:
				BorhanLog::info('Entry distribution is currently being updated, so it was submitted already');
				break;
			case BorhanEntryDistributionStatus::DELETING:
				// throwing exception, the task will retry on next execution
				throw new Exception('Entry distribution is currently being deleted and cannot be handled at this stage');
				break;
			case BorhanEntryDistributionStatus::ERROR_SUBMITTING:
			case BorhanEntryDistributionStatus::ERROR_UPDATING:
			case BorhanEntryDistributionStatus::ERROR_DELETING:
				BorhanLog::info('Entry distribution is in error state, trying to resubmit');
				$shouldSubmit = true;
				break;
			case BorhanEntryDistributionStatus::IMPORT_SUBMITTING:
			case BorhanEntryDistributionStatus::IMPORT_UPDATING:
				BorhanLog::info('Entry distribution is waiting for an import job to be finished, do nothing, it will be submitted/updated automatically');
				break;
			default:
				throw new Exception("Entry distribution status $entryDistribution->status is invalid");
		}

		if ($shouldSubmit)
		{
			$contentDistributionPlugin->entryDistribution->submitAdd($entryDistribution->id, true);
		}

		$this->unimpersonate();
	}

	protected function getEntryDistribution($entryId, $distributionProfileId)
	{
		$distributionPlugin = BorhanContentDistributionClientPlugin::get($this->getClient());
		$entryDistributionFilter = new BorhanEntryDistributionFilter();
		$entryDistributionFilter->entryIdEqual = $entryId;
		$entryDistributionFilter->distributionProfileIdEqual = $distributionProfileId;
		$result = $distributionPlugin->entryDistribution->listAction($entryDistributionFilter);
		if (count($result->objects))
			return $result->objects[0];
		else
			return null;
	}
}