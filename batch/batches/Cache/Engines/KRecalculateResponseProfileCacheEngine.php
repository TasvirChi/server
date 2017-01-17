<?php
/**
 * @package Scheduler
 * @subpackage RecalculateCache
 */
class KRecalculateResponseProfileCacheEngine extends KRecalculateCacheEngine
{
	const RESPONSE_PROFILE_CACHE_ALREADY_RECALCULATED = 'RESPONSE_PROFILE_CACHE_ALREADY_RECALCULATED';
	const RESPONSE_PROFILE_CACHE_RECALCULATE_RESTARTED = 'RESPONSE_PROFILE_CACHE_RECALCULATE_RESTARTED';
	
	protected $maxCacheObjectsPerRequest = 10;
	
	public function __construct()
	{
		if(KBatchBase::$taskConfig->params->maxCacheObjectsPerRequest)
			$this->maxCacheObjectsPerRequest = intval(KBatchBase::$taskConfig->params->maxCacheObjectsPerRequest);
	}
	
	/* (non-PHPdoc)
	 * @see KRecalculateCacheEngine::recalculate()
	 */
	public function recalculate(BorhanRecalculateCacheJobData $data)
	{
		return $this->doRecalculate($data);
	}
	
	public function doRecalculate(BorhanRecalculateResponseProfileCacheJobData $data)
	{
		$job = KJobHandlerWorker::getCurrentJob();
		KBatchBase::impersonate($job->partnerId);
		$partner = KBatchBase::$kClient->partner->get($job->partnerId);
		KBatchBase::unimpersonate();
		
		$role = reset($data->userRoles);
		/* @var $role BorhanIntegerValue */
		$privileges = array(
			'setrole:' . $role->value,
			'disableentitlement',
		);
		$privileges = implode(',', $privileges);
		
		$client = new BorhanClient(KBatchBase::$kClientConfig);
		$ks = $client->generateSession($partner->adminSecret, 'batchUser', $data->ksType, $job->partnerId, 86400, $privileges);
		$client->setKs($ks);
		
		$options = new BorhanResponseProfileCacheRecalculateOptions();
		$options->limit = $this->maxCacheObjectsPerRequest;
		$options->cachedObjectType = $data->cachedObjectType;
		$options->objectId = $data->objectId;
		$options->startObjectKey = $data->startObjectKey;
		$options->endObjectKey = $data->endObjectKey;
		$options->jobCreatedAt = $job->createdAt;
		$options->isFirstLoop = true;
		
		$recalculated = 0;
		try 
		{
			do
			{
				$results = $client->responseProfile->recalculate($options);
				$recalculated += $results->recalculated;
				$options->startObjectKey = $results->lastObjectKey;
				$options->isFirstLoop = false;
			} while($results->lastObjectKey);
		}
		catch(BorhanException $e)
		{
			if($e->getCode() != self::RESPONSE_PROFILE_CACHE_ALREADY_RECALCULATED && $e->getCode() != self::RESPONSE_PROFILE_CACHE_RECALCULATE_RESTARTED)
				throw $e;
			
			BorhanLog::err($e);
		}
		
		return $recalculated;
	}
}
