<?php
/**
 * Live Cue Point service
 *
 * @service liveCuePoint
 * @package plugins.cuePoint
 * @subpackage api.services
 * @throws BorhanErrors::SERVICE_FORBIDDEN
 */
class LiveCuePointService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('CuePoint');

		// when session is not admin, allow access to user entries only
		if (!$this->getKs() || !$this->getKs()->isAdmin()) {
			BorhanCriterion::enableTag(BorhanCriterion::TAG_USER_SESSION);
			CuePointPeer::setUserContentOnly(true);
		}
		
		if(!CuePointPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, CuePointPlugin::PLUGIN_NAME);
		
		if(!$this->getPartner()->getEnabledService(PermissionName::FEATURE_BORHAN_LIVE_STREAM))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, 'Borhan Live Streams');
	}

	/**
	 * Creates perioding metadata sync-point events on a live stream
	 * 
	 * @action createPeriodicSyncPoints
	 * @actionAlias liveStream.createPeriodicSyncPoints
	 * @deprecated This actions is not required, sync points are sent automatically on the stream.
	 * @param string $entryId Borhan live-stream entry id
	 * @param int $interval Events interval in seconds 
	 * @param int $duration Duration in seconds
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @throws BorhanErrors::NO_MEDIA_SERVER_FOUND
	 * @throws BorhanErrors::MEDIA_SERVER_SERVICE_NOT_FOUND
	 */
	function createPeriodicSyncPoints($entryId, $interval, $duration)
	{
	}
}
