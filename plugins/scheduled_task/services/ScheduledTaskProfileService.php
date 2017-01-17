<?php

/**
 * Schedule task service lets you create and manage scheduled task profiles
 *
 * @service scheduledTaskProfile
 * @package plugins.scheduledTask
 * @subpackage api.services
 */
class ScheduledTaskProfileService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		$partnerId = $this->getPartnerId();
		if (!ScheduledTaskPlugin::isAllowedPartner($partnerId))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, "{$this->serviceName}->{$this->actionName}");

		$this->applyPartnerFilterForClass('ScheduledTaskProfile');
	}

	/**
	 * Add a new scheduled task profile
	 *
	 * @action add
	 * @param BorhanScheduledTaskProfile $scheduledTaskProfile
	 * @return BorhanScheduledTaskProfile
	 *
	 * @disableRelativeTime $scheduledTaskProfile
	 */
	public function addAction(BorhanScheduledTaskProfile $scheduledTaskProfile)
	{
		/* @var $dbScheduledTaskProfile ScheduledTaskProfile */
		$dbScheduledTaskProfile = $scheduledTaskProfile->toInsertableObject();
		$dbScheduledTaskProfile->setPartnerId(kCurrentContext::getCurrentPartnerId());
		$dbScheduledTaskProfile->save();

		// return the saved object
		$scheduledTaskProfile = new BorhanScheduledTaskProfile();
		$scheduledTaskProfile->fromObject($dbScheduledTaskProfile, $this->getResponseProfile());
		return $scheduledTaskProfile;
	}

	/**
	 * Retrieve a scheduled task profile by id
	 *
	 * @action get
	 * @param int $id
	 * @return BorhanScheduledTaskProfile
	 *
	 * @throws BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND
	 */
	public function getAction($id)
	{
		// get the object
		$dbScheduledTaskProfile = ScheduledTaskProfilePeer::retrieveByPK($id);
		if (!$dbScheduledTaskProfile)
			throw new BorhanAPIException(BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND, $id);

		// return the found object
		$scheduledTaskProfile = new BorhanScheduledTaskProfile();
		$scheduledTaskProfile->fromObject($dbScheduledTaskProfile, $this->getResponseProfile());
		return $scheduledTaskProfile;
	}

	/**
	 * Update an existing scheduled task profile
	 *
	 * @action update
	 * @param int $id
	 * @param BorhanScheduledTaskProfile $scheduledTaskProfile
	 * @return BorhanScheduledTaskProfile
	 *
	 * @throws BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND
	 * @disableRelativeTime $scheduledTaskProfile
	 */
	public function updateAction($id, BorhanScheduledTaskProfile $scheduledTaskProfile)
	{
		// get the object
		$dbScheduledTaskProfile = ScheduledTaskProfilePeer::retrieveByPK($id);
		if (!$dbScheduledTaskProfile)
			throw new BorhanAPIException(BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND, $id);

		// save the object
		/** @var ScheduledTaskProfile $dbScheduledTaskProfile */
		$dbScheduledTaskProfile = $scheduledTaskProfile->toUpdatableObject($dbScheduledTaskProfile);
		$dbScheduledTaskProfile->save();

		// return the saved object
		$scheduledTaskProfile = new BorhanScheduledTaskProfile();
		$scheduledTaskProfile->fromObject($dbScheduledTaskProfile, $this->getResponseProfile());
		return $scheduledTaskProfile;
	}

	/**
	 * Delete a scheduled task profile
	 *
	 * @action delete
	 * @param int $id
	 *
	 * @throws BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND
	 */
	public function deleteAction($id)
	{
		// get the object
		$dbScheduledTaskProfile = ScheduledTaskProfilePeer::retrieveByPK($id);
		if (!$dbScheduledTaskProfile)
			throw new BorhanAPIException(BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND, $id);

		// set the object status to deleted
		$dbScheduledTaskProfile->setStatus(ScheduledTaskProfileStatus::DELETED);
		$dbScheduledTaskProfile->save();
	}

	/**
	 * List scheduled task profiles
	 *
	 * @action list
	 * @param BorhanScheduledTaskProfileFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanScheduledTaskProfileListResponse
	 */
	public function listAction(BorhanScheduledTaskProfileFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanScheduledTaskProfileFilter();

		if (!$pager)
			$pager = new BorhanFilterPager();

		$scheduledTaskFilter = new ScheduledTaskProfileFilter();
		$filter->toObject($scheduledTaskFilter);

		$c = new Criteria();
		$scheduledTaskFilter->attachToCriteria($c);
		$count = ScheduledTaskProfilePeer::doCount($c);

		$pager->attachToCriteria($c);
		$list = ScheduledTaskProfilePeer::doSelect($c);

		$response = new BorhanScheduledTaskProfileListResponse();
		$response->objects = BorhanScheduledTaskProfileArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;

		return $response;
	}

	/**
	 *
	 *
	 * @action requestDryRun
	 * @param int $scheduledTaskProfileId
	 * @param int $maxResults
	 * @return int
	 *
	 * @throws BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND
	 */
	public function requestDryRunAction($scheduledTaskProfileId, $maxResults = 500)
	{
		// get the object
		$dbScheduledTaskProfile = ScheduledTaskProfilePeer::retrieveByPK($scheduledTaskProfileId);
		if (!$dbScheduledTaskProfile)
			throw new BorhanAPIException(BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND, $scheduledTaskProfileId);

		if (!in_array($dbScheduledTaskProfile->getStatus(), array(BorhanScheduledTaskProfileStatus::ACTIVE, BorhanScheduledTaskProfileStatus::DRY_RUN_ONLY)))
			throw new BorhanAPIException(BorhanScheduledTaskErrors::SCHEDULED_TASK_DRY_RUN_NOT_ALLOWED, $scheduledTaskProfileId);

		$jobData = new kScheduledTaskJobData();
		$jobData->setMaxResults($maxResults);
		$referenceTime = kCurrentContext::$ks_object->getPrivilegeValue(ks::PRIVILEGE_REFERENCE_TIME);
		if ($referenceTime)
			$jobData->setReferenceTime($referenceTime);
		$batchJob = $this->createScheduledTaskJob($dbScheduledTaskProfile, $jobData);

		return $batchJob->getId();
	}

	/**
	 *
	 *
	 * @action getDryRunResults
	 * @param int $requestId
	 * @return BorhanObjectListResponse
	 *
	 * @throws BorhanScheduledTaskErrors::SCHEDULED_TASK_PROFILE_NOT_FOUND
	 */
	public function getDryRunResultsAction($requestId)
	{
		$this->applyPartnerFilterForClass('BatchJob');
		$batchJob = BatchJobPeer::retrieveByPK($requestId);
		$batchJobType = ScheduledTaskPlugin::getBatchJobTypeCoreValue(ScheduledTaskBatchType::SCHEDULED_TASK);
		if (is_null($batchJob) || $batchJob->getJobType() != $batchJobType)
			throw new BorhanAPIException(BorhanScheduledTaskErrors::OBJECT_NOT_FOUND);

		if (in_array($batchJob->getStatus(), array(BorhanBatchJobStatus::FAILED, BorhanBatchJobStatus::FATAL)))
			throw new BorhanAPIException(BorhanScheduledTaskErrors::DRY_RUN_FAILED);

		if ($batchJob->getStatus() != BorhanBatchJobStatus::FINISHED)
			throw new BorhanAPIException(BorhanScheduledTaskErrors::DRY_RUN_NOT_READY);

		$syncKey = $batchJob->getSyncKey(BatchJob::FILE_SYNC_BATCHJOB_SUB_TYPE_BULKUPLOAD);
		$data = kFileSyncUtils::file_get_contents($syncKey, true);
		$results = unserialize($data);
		return $results;
	}

	/**
	 * @param ScheduledTaskProfile $scheduledTaskProfile
	 * @param kScheduledTaskJobData $jobData
	 * @return BatchJob
	 */
	protected function createScheduledTaskJob(ScheduledTaskProfile $scheduledTaskProfile, kScheduledTaskJobData $jobData)
	{
		$scheduledTaskProfileId = $scheduledTaskProfile->getId();
		$jobType = ScheduledTaskPlugin::getBatchJobTypeCoreValue(ScheduledTaskBatchType::SCHEDULED_TASK);
		$objectType = ScheduledTaskPlugin::getBatchJobObjectTypeCoreValue(ScheduledTaskBatchJobObjectType::SCHEDULED_TASK_PROFILE);

		BorhanLog::log("Creating scheduled task dry run job for profile [".$scheduledTaskProfileId."]");
		$batchJob = new BatchJob();
		$batchJob->setPartnerId($scheduledTaskProfile->getPartnerId());
		$batchJob->setObjectId($scheduledTaskProfileId);
		$batchJob->setObjectType($objectType);
		$batchJob->setStatus(BatchJob::BATCHJOB_STATUS_PENDING);

		$batchJob = kJobsManager::addJob($batchJob, $jobData, $jobType);

		return $batchJob;
	}
}