<?php
/**
 * @package plugins.logView
 * @subpackage admin
 */
class Borhan_View_Helper_EntryInvestigateLogView extends Borhan_View_Helper_PartialViewPlugin
{
	/* (non-PHPdoc)
	 * @see Borhan_View_Helper_PartialViewPlugin::getDataArray()
	 */
	protected function getDataArray()
	{
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
		return 'entry-investigate-log-view.phtml';
	}
}