<?php
/**
 * Enable the plugin to define dependency on another plugin
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanPending extends IBorhanBase
{
	/**
	 * Returns a Borhan dependency object that defines the relationship between two plugins.
	 * 
	 * @return array<BorhanDependency> The Borhan dependency object
	 */
	public static function dependsOn();
}