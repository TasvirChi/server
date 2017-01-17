<?php
/**
 * Enable the plugin to define another plugin as a mandatory requirement for its load
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanRequire extends IBorhanBase
{
	/**
	 * Returns string(s) of Borhan Plugins which the plugin requires
	 * 
	 * @return array<String> The Borhan dependency object
	 */
	public static function requires();
}