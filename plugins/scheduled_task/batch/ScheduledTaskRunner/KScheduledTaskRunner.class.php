<?php
/**
 * @package plugins.scheduledTask
 * @subpackage Scheduler
 */
class KScheduledTaskRunner extends KPeriodicWorker
{
	/**
	 * @var array
	 */
	protected $_objectEngineTasksCache;

	/* (non-PHPdoc)
	 * @see KBatchBase::getType()
	 */
	public static function getType()
	{
		return BorhanBatchJobType::SCHEDULED_TASK;
	}

	/* (non-PHPdoc)
	 * @see KBatchBase::getJobType()
	 */
	public function getJobType()
	{
		return self::getType();
	}

	/* (non-PHPdoc)
	 * @see KBatchBase::run()
	*/
	public function run($jobs = null)
	{
		$maxProfiles = $this->getParams('maxProfiles');

		$profiles = $this->getScheduledTaskProfiles($maxProfiles);
		foreach($profiles as $profile)
		{
			try
			{
				$this->processProfile($profile);
			}
			catch(Exception $ex)
			{
				BorhanLog::err($ex);
			}
		}
	}

	/**
	 * @param int $maxProfiles
	 * @return array
	 */
	protected function getScheduledTaskProfiles($maxProfiles = 500)
	{
		$scheduledTaskClient = $this->getScheduledTaskClient();

		$filter = new BorhanScheduledTaskProfileFilter();
		$filter->orderBy = BorhanScheduledTaskProfileOrderBy::LAST_EXECUTION_STARTED_AT_ASC;
		$filter->statusEqual = BorhanScheduledTaskProfileStatus::ACTIVE;

		$pager = new BorhanFilterPager();
		$pager->pageSize = $maxProfiles;

		$result = $scheduledTaskClient->scheduledTaskProfile->listAction($filter, $pager);

		return $result->objects;
	}

	/**
	 * @param BorhanScheduledTaskProfile $profile
	 */
	protected function processProfile(BorhanScheduledTaskProfile $profile)
	{
		$this->updateProfileBeforeExecution($profile);
		if ($profile->maxTotalCountAllowed)
			$maxTotalCountAllowed = $profile->maxTotalCountAllowed;
		else
			$maxTotalCountAllowed = $this->getParams('maxTotalCountAllowed');

		$pager = new BorhanFilterPager();
		$pager->pageIndex = 1;
		$pager->pageSize = 500;
		while(true)
		{
			$this->impersonate($profile->partnerId);
			try
			{
				$result = ScheduledTaskBatchHelper::query($this->getClient(), $profile, $pager);
				$this->unimpersonate();
			}
			catch(Exception $ex)
			{
				$this->unimpersonate();
				throw $ex;
			}

			if ($result->totalCount > $maxTotalCountAllowed)
			{
				BorhanLog::crit("List query for profile $profile->id returned too many results ($result->totalCount when the allowed total count is $maxTotalCountAllowed), suspending the profile");
				$this->suspendProfile($profile);
				break;
			}
			if (!count($result->objects))
				break;

			foreach($result->objects as $object)
			{
				$this->processObject($profile, $object);
			}

			$pager->pageIndex++;
		}
	}

	/**
	 * @param BorhanScheduledTaskProfile $profile
	 * @param $object
	 */
	protected function processObject(BorhanScheduledTaskProfile $profile, $object)
	{
		foreach($profile->objectTasks as $objectTask)
		{
			/** @var BorhanObjectTask $objectTask */
			$objectTaskEngine = $this->getObjectTaskEngineByType($objectTask->type);
			$objectTaskEngine->setObjectTask($objectTask);
			try
			{
				$objectTaskEngine->execute($object);
			}
			catch(Exception $ex)
			{
				$this->unimpersonate();
				$id = '';
				if (property_exists($object, 'id'))
					$id = $object->id;
				BorhanLog::err(sprintf('An error occurred while executing %s on object %s (id %s)', get_class($objectTaskEngine), get_class($object), $id));
				BorhanLog::err($ex);

				if ($objectTask->stopProcessingOnError)
				{
					BorhanLog::log('Object task is configured to stop processing on error');
					break;
				}
			}
		}
	}

	/**
	 * @param $type
	 * @return KObjectTaskEngineBase
	 */
	protected function getObjectTaskEngineByType($type)
	{
		if (!isset($this->_objectEngineTasksCache[$type]))
		{
			$objectTaskEngine = KObjectTaskEngineFactory::getInstanceByType($type);
			$objectTaskEngine->setClient($this->getClient());
			$this->_objectEngineTasksCache[$type] = $objectTaskEngine;
		}

		return $this->_objectEngineTasksCache[$type];
	}

	/**
	 * @return BorhanScheduledTaskClientPlugin
	 */
	protected function getScheduledTaskClient()
	{
		$client = $this->getClient();
		return BorhanScheduledTaskClientPlugin::get($client);
	}

	/**
	 * Update the profile last execution time so we would have profiles rotation in case one execution dies
	 *
	 * @param BorhanScheduledTaskProfile $profile
	 */
	protected function updateProfileBeforeExecution(BorhanScheduledTaskProfile $profile)
	{
		$scheduledTaskClient = $this->getScheduledTaskClient();
		$profileForUpdate = new BorhanScheduledTaskProfile();
		$profileForUpdate->lastExecutionStartedAt = time();
		$this->impersonate($profile->partnerId);
		$scheduledTaskClient->scheduledTaskProfile->update($profile->id, $profileForUpdate);
		$this->unimpersonate();
	}

	/**
	 * Moves the profile to suspended status
	 *
	 * @param BorhanScheduledTaskProfile $profile
	 */
	protected function suspendProfile(BorhanScheduledTaskProfile $profile)
	{
		$scheduledTaskClient = $this->getScheduledTaskClient();
		$profileForUpdate = new BorhanScheduledTaskProfile();
		$profileForUpdate->status = BorhanScheduledTaskProfileStatus::SUSPENDED;
		$this->impersonate($profile->partnerId);
		$scheduledTaskClient->scheduledTaskProfile->update($profile->id, $profileForUpdate);
		$this->unimpersonate();
	}
}
