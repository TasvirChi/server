<?php
/**
 * @package plugins.scheduledTask
 */
class ScheduledTaskPlugin extends BorhanPlugin implements IBorhanVersion, IBorhanPermissions, IBorhanServices, IBorhanEnumerator, IBorhanObjectLoader, IBorhanEventConsumers
{
	const PLUGIN_NAME = 'scheduledTask';
	const PLUGIN_VERSION_MAJOR = 1;
	const PLUGIN_VERSION_MINOR = 0;
	const PLUGIN_VERSION_BUILD = 0;

	const BATCH_JOB_FLOW_MANAGER = 'kScheduledTaskBatchJobFlowManager';

	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}

	/* (non-PHPdoc)
	 * @see IBorhanVersion::getVersion()
	 */
	public static function getVersion()
	{
		return new BorhanVersion(
			self::PLUGIN_VERSION_MAJOR,
			self::PLUGIN_VERSION_MINOR,
			self::PLUGIN_VERSION_BUILD
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPermissions::isAllowedPartner()
 	*/
	public static function isAllowedPartner($partnerId)
	{
		if($partnerId == Partner::ADMIN_CONSOLE_PARTNER_ID || $partnerId == Partner::BATCH_PARTNER_ID)
			return true;

		$partner = PartnerPeer::retrieveByPK($partnerId);
		if ($partner)
			return $partner->getPluginEnabled(self::PLUGIN_NAME);

		return false;
	}

	/* (non-PHPdoc)
 	* @see IBorhanServices::getServicesMap()
 	*/
	public static function getServicesMap()
	{
		return array(
			'scheduledTaskProfile' => 'ScheduledTaskProfileService',
		);
	}

	/**
	 * Returns a list of enumeration class names that implement the baseEnumName interface.
	 * @param string $baseEnumName the base implemented enum interface, set to null to retrieve all plugin enums
	 * @return array<string> A string listing the enum class names that extend baseEnumName
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('ScheduledTaskBatchType', 'ScheduledTaskBatchJobObjectType');

		if($baseEnumName == 'BatchJobType')
			return array('ScheduledTaskBatchType');

		if($baseEnumName == 'BatchJobObjectType')
			return array('ScheduledTaskBatchJobObjectType');

		return array();
	}

	/**
	 * Returns an object that is known only to the plugin, and extends the baseClass.
	 *
	 * @param string $baseClass The base class of the loaded object
	 * @param string $enumValue The enumeration value of the loaded object
	 * @param array $constructorArgs The constructor arguments of the loaded object
	 * @return object
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'BorhanJobData' && $enumValue == self::getApiValue(ScheduledTaskBatchType::SCHEDULED_TASK))
			return new BorhanScheduledTaskJobData();

		return null;
	}

	/**
	 * Retrieves a class name that is defined by the plugin and is known only to the plugin, and extends the baseClass.
	 *
	 * @param string $baseClass The base class of the searched class
	 * @param string $enumValue The enumeration value of the searched class
	 * @return string The name of the searched object's class
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'BorhanJobData' && $enumValue == self::getApiValue(ScheduledTaskBatchType::SCHEDULED_TASK))
			return 'BorhanScheduledTaskJobData';

		return null;
	}

	/**
	 * Retrieves the event consumers used by the plugin.
	 *
	 * @return array The list of event consumers
	 */
	public static function getEventConsumers()
	{
		return array(self::BATCH_JOB_FLOW_MANAGER);
	}

	/**
	 * @param $valueName
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getBatchJobTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('BatchJobType', $value);
	}

	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getBatchJobObjectTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('BatchJobObjectType', $value);
	}
}