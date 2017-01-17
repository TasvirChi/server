<?php
/**
 * @package plugins.adminConsole
 */
class AdminConsolePlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanServices, IBorhanPending
{
	const PLUGIN_NAME = 'adminConsole';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	public static function dependsOn()
	{			
		$dependency = new BorhanDependency(FileSyncPlugin::getPluginName());
		return array($dependency);
	}
	
	public function getInstance($interface)
	{
		if($this instanceof $interface)
			return $this;
			
		return null;
	}
		
	public static function getServicesMap()
	{
		$map = array(
			'entryAdmin' => 'EntryAdminService',
			'uiConfAdmin' => 'UiConfAdminService',
			'reportAdmin' => 'ReportAdminService',
		);
		return $map;
	}
	
	public static function isAllowedPartner($partnerId)
	{
		if($partnerId == Partner::ADMIN_CONSOLE_PARTNER_ID)
			return true;
		
		return false;
	}
}
