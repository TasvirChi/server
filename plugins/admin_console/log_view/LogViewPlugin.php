<?php
/**
 * Enable log view for admin-console entry investigation page
 * @package plugins.logView
 */
class LogViewPlugin extends BorhanPlugin implements IBorhanApplicationPartialView, IBorhanAdminConsolePages
{
	const PLUGIN_NAME = 'logView';

	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanApplicationPartialView::getApplicationPartialViews()
	 */
	public static function getApplicationPartialViews($controller, $action)
	{
		if($controller == 'batch' && $action == 'entryInvestigation')
		{
			return array(
				new Borhan_View_Helper_EntryInvestigateLogView(),
			);
		}
		
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanAdminConsolePages::getApplicationPages()
	 */
	public static function getApplicationPages()
	{
		$BorhanInternalTools = array(
			new BorhanLogViewAction(),
			new BorhanObjectInvestigateLogAction(),
		);
		return $BorhanInternalTools;
	}
}
