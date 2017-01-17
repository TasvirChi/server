<?php
/**
 * @package plugins.adminConsoleGallery
 */
class AdminConsoleGalleryPlugin extends BorhanPlugin implements IBorhanAdminConsolePages
{
	const PLUGIN_NAME = 'adminConsoleGallery';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanAdminConsolePages::getApplicationPages()
	 */
	public static function getApplicationPages()
	{
		$pages = array();
		$pages[] = new AdminConsoleGalleryAction();
		return $pages;
	}
}
