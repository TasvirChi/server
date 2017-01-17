<?php
/**
 * Enable you to give version to the plugin
 * The version might be importent for depencies between different plugins
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanVersion extends IBorhanBase
{
	/**
	 * @return BorhanVersion
	 */
	public static function getVersion();
}