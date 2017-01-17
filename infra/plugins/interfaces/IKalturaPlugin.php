<?php
/**
 * Must be implemented by all plugins
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanPlugin extends IBorhanBase
{
	/**
	 * @return string the name of the plugin
	 */
	public static function getPluginName();
}