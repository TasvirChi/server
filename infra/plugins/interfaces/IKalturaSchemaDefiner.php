<?php
/**
 * Enable the plugin to define new XML schema type
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanSchemaDefiner extends IBorhanBase
{
	/**
	 * @param SchemaType $type
	 * @return SimpleXMLElement XSD
	 */
	public static function getPluginSchema($type);
}