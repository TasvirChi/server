<?php
/**
 * Enable the plugin to clean unused memory, instances and pools
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanMemoryCleaner extends IBorhanBase
{
	public static function cleanMemory();
}