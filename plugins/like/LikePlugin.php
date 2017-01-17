<?php
/**
 * Enable 'liking' or 'unliking' an entry as the current user, rather than anonymously ranking it.
 * @package plugins.like
 */
class LikePlugin extends BorhanPlugin implements IBorhanServices, IBorhanPermissions
{
    const PLUGIN_NAME = "like";
    
	/* (non-PHPdoc)
     * @see IBorhanServices::getServicesMap()
     */
    public static function getServicesMap ()
    {
        $map = array(
			'like' => 'LikeService',
		);
		return $map;
    }

	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
	 */
	public static function isAllowedPartner($partnerId)
	{
		$partner = PartnerPeer::retrieveByPK($partnerId);
		
		return $partner->getEnabledService(BorhanPermissionName::FEATURE_LIKE);
	}
	

	/* (non-PHPdoc)
     * @see IBorhanPlugin::getPluginName()
     */
    public static function getPluginName ()
    {
        return self::PLUGIN_NAME;
    }

    
}