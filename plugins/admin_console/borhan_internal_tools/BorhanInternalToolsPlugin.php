<?php
/**
 * @package plugins.BorhanInternalTools
 */
class BorhanInternalToolsPlugin extends BorhanPlugin implements IBorhanServices, IBorhanAdminConsolePages
{
	const PLUGIN_NAME = 'BorhanInternalTools';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/**
	 * @return array<string,string> in the form array[serviceName] = serviceClass
	 */
	public static function getServicesMap()
	{
		$map = array(
			'BorhanInternalTools' => 'BorhanInternalToolsService',
			'BorhanInternalToolsSystemHelper' => 'BorhanInternalToolsSystemHelperService',
		);
		return $map;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanAdminConsolePages::getApplicationPages()
	 */
	public static function getApplicationPages()
	{
		$BorhanInternalTools = array(new BorhanInternalToolsPluginSystemHelperAction(),new BorhanInternalToolsPluginFlavorParams());
		return $BorhanInternalTools;
	}
	
	public static function isAllowedPartner($partnerId)
	{
		if($partnerId == Partner::ADMIN_CONSOLE_PARTNER_ID)
			return true;
		
		return false;
	}
}
