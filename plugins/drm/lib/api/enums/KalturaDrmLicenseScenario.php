<?php
/**
 * @package plugins.drm
 * @subpackage api.enum
 */
class BorhanDrmLicenseScenario extends BorhanDynamicEnum implements DrmLicenseScenario
{
	public static function getEnumClass()
	{
		return 'DrmLicenseScenario';
	}
}