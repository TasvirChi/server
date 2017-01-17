<?php
/**
 * Enable to plugin to add pages to external applications
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanApplicationPages extends IBorhanBase
{
	/**
	 * @return array
	 */
	public static function getApplicationPages();	
}