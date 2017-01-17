<?php
/**
 * @package plugins.FeedDropFolder
 */
class FeedDropFolderPlugin extends BorhanPlugin implements IBorhanPlugin, IBorhanPending, IBorhanObjectLoader, IBorhanEnumerator, IBorhanApplicationTranslations
{
	const PLUGIN_NAME = 'FeedDropFolder';
	const DROP_FOLDER_PLUGIN_NAME = 'dropFolder';
	
	const ERROR_MESSAGE_INCOMPLETE_HANDLING = "Feed is too long- because of handling limitation not all feed items will be handled. Feed Drop Folder ID ";
	
	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName() {
		return self::PLUGIN_NAME;
	}

	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		switch ($baseClass)
		{
			case 'KDropFolderEngine':
				if ($enumValue == BorhanDropFolderType::FEED)
				{
					return new KFeedDropFolderEngine();
				}
				break;
			case ('BorhanDropFolderFile'):
				if ($enumValue == self::getDropFolderTypeCoreValue(FeedDropFolderType::FEED) )
				{
					return new BorhanFeedDropFolderFile();
				}
				break;
			case ('BorhanDropFolder'):
				if ($enumValue == self::getDropFolderTypeCoreValue(FeedDropFolderType::FEED) )
				{
					return new BorhanFeedDropFolder();
				}
				break;
			case 'kDropFolderXmlFileHandler':
				if ($enumValue == self::getDropFolderTypeCoreValue(FeedDropFolderType::FEED))
				{
					return new kDropFolderFeedXmlFileHandler();
				}
				break;
			case 'Form_DropFolderConfigureExtend_SubForm':
				if ($enumValue == Borhan_Client_DropFolder_Enum_DropFolderType::FEED)
				{
					return new Form_FeedDropFolderConfigureExtend_SubForm();
				}
				break;
			case 'Borhan_Client_DropFolder_Type_DropFolder':
				if ($enumValue == Borhan_Client_DropFolder_Enum_DropFolderType::FEED)
				{
					return new Borhan_Client_FeedDropFolder_Type_FeedDropFolder();
				}
				break;
		}
	}
	
	public static function getObjectClass($baseClass, $enumValue)
	{
		switch ($baseClass) {
			case 'DropFolderFile':
				if ($enumValue == self::getDropFolderTypeCoreValue(FeedDropFolderType::FEED))
				return 'FeedDropFolderFile';				
				break;
				
			case 'DropFolder':
				if ($enumValue == self::getDropFolderTypeCoreValue(FeedDropFolderType::FEED))
				return 'FeedDropFolder';				
				break;

		}
	}

	/* (non-PHPdoc)
	 * @see IBorhanEnumerator::getEnums()
	 */
	public static function getEnums($baseEnumName = null)
	{
		if (!$baseEnumName)
		{
			return array('FeedDropFolderType');
		}
		if ($baseEnumName == 'DropFolderType')
		{
			return array('FeedDropFolderType');
		}
		
		return array();
	}
		
	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn() {
		$dropFolderDependency = new BorhanDependency(self::DROP_FOLDER_PLUGIN_NAME);
		
		return array($dropFolderDependency);
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

	/**
	 * @return array
	 */
	public static function getTranslations($locale)
	{
		$array = array();
		
		$langFilePath = __DIR__ . "/config/lang/$locale.php";
		if(!file_exists($langFilePath))
		{
			$default = 'en';
			$langFilePath = __DIR__ . "/config/lang/$default.php";
		}
		
		BorhanLog::info("Loading file [$langFilePath]");
		$array = include($langFilePath);
	
		return array($locale => $array);
	}
}
