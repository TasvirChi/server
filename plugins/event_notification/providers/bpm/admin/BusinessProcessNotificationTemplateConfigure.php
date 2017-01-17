<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage admin
 */ 
class Borhan_View_Helper_BusinessProcessNotificationTemplateConfigure extends Borhan_View_Helper_PartialViewPlugin
{
	/* (non-PHPdoc)
	 * @see Borhan_View_Helper_PartialViewPlugin::getDataArray()
	 */
	protected function getDataArray()
	{
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see Borhan_View_Helper_PartialViewPlugin::getTemplatePath()
	 */
	protected function getTemplatePath()
	{
		return realpath(dirname(__FILE__));
	}
	
	/* (non-PHPdoc)
	 * @see Borhan_View_Helper_PartialViewPlugin::getPHTML()
	 */
	protected function getPHTML()
	{
		return 'business_process-notification-template-configure.phtml';
	}
}