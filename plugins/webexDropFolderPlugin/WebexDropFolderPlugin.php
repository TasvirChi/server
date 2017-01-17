<?php
/**
 * @package plugins.WebexDropFolder
 */
class WebexDropFolderPlugin extends BorhanPlugin implements IBorhanPending, IBorhanPermissions, IBorhanObjectLoader, IBorhanEnumerator
{
	const PLUGIN_NAME = 'WebexDropFolder';
	const DROP_FOLDER_PLUGIN_NAME = 'dropFolder';
		
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	public static function dependsOn()
	{
		$dropFolderDependency = new BorhanDependency(self::DROP_FOLDER_PLUGIN_NAME);
		
		return array($dropFolderDependency);
	}
	
	public static function isAllowedPartner($partnerId)
	{
		if (in_array($partnerId, array(Partner::ADMIN_CONSOLE_PARTNER_ID, Partner::BATCH_PARTNER_ID)))
			return true;
		
		$partner = PartnerPeer::retrieveByPK($partnerId);
		return $partner->getPluginEnabled(self::PLUGIN_NAME);
	}
	
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		switch ($baseClass)
		{
			case 'KDropFolderEngine':
				if ($enumValue == BorhanDropFolderType::WEBEX)
				{
					return new KWebexDropFolderEngine();
				}
				break;
			case ('BorhanDropFolder'):
				if ($enumValue == self::getDropFolderTypeCoreValue(WebexDropFolderType::WEBEX) )
				{
					return new BorhanWebexDropFolder();
				}
				break;
			case ('BorhanDropFolderFile'):
				if ($enumValue == self::getDropFolderTypeCoreValue(WebexDropFolderType::WEBEX) )
				{
					return new BorhanWebexDropFolderFile();
				}
				break;
			case 'kDropFolderContentProcessorJobData':
				if ($enumValue == self::getDropFolderTypeCoreValue(WebexDropFolderType::WEBEX))
				{
					return new kWebexDropFolderContentProcessorJobData();
				}
				break;
			case 'BorhanJobData':
				$jobSubType = $constructorArgs["coreJobSubType"];
			    if ($enumValue == DropFolderPlugin::getApiValue(DropFolderBatchType::DROP_FOLDER_CONTENT_PROCESSOR) &&
					$jobSubType == self::getDropFolderTypeCoreValue(WebexDropFolderType::WEBEX) )
				{
					return new BorhanWebexDropFolderContentProcessorJobData();
				}
				break;
			case 'Form_DropFolderConfigureExtend_SubForm':
				if ($enumValue == Borhan_Client_DropFolder_Enum_DropFolderType::WEBEX)
				{
					return new Form_WebexDropFolderConfigureExtend_SubForm();
				}
				break;
			case 'Borhan_Client_DropFolder_Type_DropFolder':
				if ($enumValue == Borhan_Client_DropFolder_Enum_DropFolderType::WEBEX)
				{
					return new Borhan_Client_WebexDropFolder_Type_WebexDropFolder();
				}
				break;
				break;
				
		}
	}
	
	public static function getObjectClass($baseClass, $enumValue)
	{
		switch ($baseClass) {
			case 'DropFolder':
				if ($enumValue == self::getDropFolderTypeCoreValue(WebexDropFolderType::WEBEX))
				return 'WebexDropFolder';				
				break;
			case 'DropFolderFile':
				if ($enumValue == self::getDropFolderTypeCoreValue(WebexDropFolderType::WEBEX))
				return 'WebexDropFolderFile';				
				break;

		}
	}
	
	public static function getEnums($baseEnumName = null)
	{
		if (!$baseEnumName)
		{
			return array('WebexDropFolderType');
		}
		if ($baseEnumName == 'DropFolderType')
		{
			return array('WebexDropFolderType');
		}

		return array();
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}

	public static function getDropFolderTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('DropFolderType', $value);
	}
}
