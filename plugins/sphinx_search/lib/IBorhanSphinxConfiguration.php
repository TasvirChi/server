<?php
/**
 * Enable the plugin to add sphinx indexes
 * @package plugins.sphinxSearch
 * @subpackage lib
 */
interface IBorhanSphinxConfiguration extends IBorhanBase
{	
	/**
	 * @return array of sphinx index schema to expand
	 */
	public static function getSphinxSchema();
}