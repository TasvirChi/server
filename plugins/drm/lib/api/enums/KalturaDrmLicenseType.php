<?php
/**
 * @package plugins.drm
 * @subpackage api.enum
 */
class BorhanDrmLicenseType extends BorhanDynamicEnum implements DrmLicenseType
{
	public static function getEnumClass()
	{
		return 'DrmLicenseType';
	}
}