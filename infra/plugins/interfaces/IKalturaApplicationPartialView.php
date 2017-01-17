<?php
/**
 * Enable the plugin to add phtml view to existing page
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanApplicationPartialView extends IBorhanBase
{
	/**
	 * @return array<Borhan_View_Helper_PartialViewPlugin>
	 */
	public static function getApplicationPartialViews($controller, $action);
}