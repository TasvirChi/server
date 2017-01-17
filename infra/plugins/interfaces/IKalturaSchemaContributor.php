<?php
/**
 * Enable the plugin to add additional XML nodes and attributes to specific schema type
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanSchemaContributor extends IBorhanBase
{
	/**
	 * @param SchemaType $type
	 * @return string XSD elements
	 */
	public static function contributeToSchema($type);
}