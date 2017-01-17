<?php
/**
 * @package plugins.drm
 * @subpackage api.enum
 */
class BorhanDrmProviderType extends BorhanDynamicEnum implements DrmProviderType
{
	public static function getEnumClass()
	{
		return 'DrmProviderType';
	}
}