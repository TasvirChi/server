<?php
/**
 * Enable you to add database connections
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanDatabaseConfig extends IBorhanBase
{
	/**
	 * @return array
	 */
	public static function getDatabaseConfig();	
}