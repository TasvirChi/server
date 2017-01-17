<?php
/**
 * @package plugins.varConsole
 */
class VarConsolePlugin extends BorhanPlugin implements IBorhanServices, IBorhanPermissions
{
    const PLUGIN_NAME = "varConsole";

	/* (non-PHPdoc)
     * @see IBorhanPlugin::getPluginName()
     */
    public static function getPluginName ()
    {    
        return self::PLUGIN_NAME;
    }


	/* (non-PHPdoc)
     * @see IBorhanServices::getServicesMap()
     */
    public static function getServicesMap ()
    {
        $map = array(
			'varConsole' => 'VarConsoleService',
		);
		
		return $map;
    }
    
    /* (non-PHPdoc)
     * @see IBorhanPermissions::isAllowedPartner($partnerId)
     */
    public static function isAllowedPartner($partnerId)
    {
        $partner = PartnerPeer::retrieveByPK($partnerId);
		
		return $partner->getEnabledService(BorhanPermissionName::FEATURE_VAR_CONSOLE_LOGIN);
    }

}