<?php
/**
 * @package plugins.wowza
 * @subpackage api.filters
 */
class BorhanWowzaMediaServerNodeFilter extends BorhanWowzaMediaServerNodeBaseFilter
{
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, $type = null)
	{
		if(!$type)
			$type = WowzaPlugin::getWowzaMediaServerTypeCoreValue(WowzaMediaServerNodeType::WOWZA_MEDIA_SERVER);
	
		return parent::getTypeListResponse($pager, $responseProfile, $type);
	}
}
