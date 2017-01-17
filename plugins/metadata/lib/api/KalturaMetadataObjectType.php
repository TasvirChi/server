<?php
/**
 * @package plugins.metadata
 * @subpackage api.enum
 */
class BorhanMetadataObjectType extends BorhanDynamicEnum implements MetadataObjectType
{
	public static function getEnumClass()
	{
		return 'MetadataObjectType';
	}
}