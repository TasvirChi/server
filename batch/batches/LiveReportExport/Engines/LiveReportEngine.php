<?php
/**
 * @package Scheduler
 * @subpackage LiveReportExport
 */
abstract class LiveReportEngine  
{
	protected function checkParams(array $args, array $paramsNames) {
		foreach($paramsNames as $param) {
			if(!array_key_exists($param, $args))
				throw new KOperationEngineException("Missing mandatory argument : " . $param);
		}
	}

	protected function shouldShowDvrColumns($entryIds)
	{
		$filter = new BorhanLiveStreamEntryFilter();
		$filter->idIn = $entryIds;

		/** @var BorhanLiveStreamListResponse */
		$response = KBatchBase::$kClient->liveStream->listAction($filter, null);

		foreach ($response->objects as $object) {
			if ($object->dvrStatus) {
				BorhanLog::info("Found entry with DVR status = true: " . $object->id);
				return true;
			}
		}
		return false;
	}

	/**
	 * Executes the given engine
	 * @param pointer resource $fp - A file system pointer resource.
	 * @param array $args The args to run with
	 */
	abstract public function run($fp, array $args = array());
	
}

