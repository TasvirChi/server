<?php
/**
 * @package plugins.drm
 * @subpackage api.enum
 */
class BorhanDrmSchemeName extends BorhanDynamicEnum implements DrmSchemeName
{
	public static function getEnumClass()
	{
		return 'DrmSchemeName';
	}
}