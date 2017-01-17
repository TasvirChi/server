<?php

/**
 * Stats Service
 *
 * @service liveStats
 * @package api
 * @subpackage services
 */
class LiveStatsService extends BorhanBaseService 
{
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'collect') {
			return false;
		}
		
		return parent::partnerRequired($actionName);
	}
	
	
	/**
	 * Will write to the event log a single line representing the event
	 * 
	 * 
 	* 
 
	 * BorhanStatsEvent $event
	 * 
	 * @action collect
	 * @return bool
	 */
	function collectAction( BorhanLiveStatsEvent $event )
	{
		return true;
	}

	
	
}
