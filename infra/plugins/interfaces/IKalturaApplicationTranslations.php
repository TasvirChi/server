<?php
/**
 * Enable to plugin to add translated keys
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanApplicationTranslations extends IBorhanBase
{
	/**
	 * @return array
	 */
	public static function getTranslations($locale);	
}