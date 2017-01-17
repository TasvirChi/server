<?php
/**
 * @package plugins.symantecScanEngine
 */
class SymantecScanEnginePlugin extends BorhanPlugin implements IBorhanPending, IBorhanEnumerator, IBorhanObjectLoader
{
	const PLUGIN_NAME = 'symantecScanEngine';
	const VIRUS_SCAN_PLUGIN_NAME = 'virusScan';
	
	/**
	 * @return array<BorhanDependency>
	 */
	public static function dependsOn()
	{
		return array(new BorhanDependency(self::VIRUS_SCAN_PLUGIN_NAME));
	}

	/**
	 * @return array<string> list of enum classes names that extend the base enum name
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('SymantecScanEngineVirusScanEngineType');
			
		if($baseEnumName == 'VirusScanEngineType')
			return array('SymantecScanEngineVirusScanEngineType');
			
		return array();
	}

	/**
	 * 
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/**
	 * @param string $baseClass
	 * @param string $enumValue
	 * @param array $constructorArgs
	 * @return object
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		if($baseClass == 'VirusScanEngine')
		{
			if($enumValue == BorhanVirusScanEngineType::SYMANTEC_SCAN_ENGINE)
				return new SymantecScanEngine();
		
			if($enumValue == BorhanVirusScanEngineType::SYMANTEC_SCAN_JAVA_ENGINE)
				return new SymantecScanJavaEngine();

			if($enumValue == BorhanVirusScanEngineType::SYMANTEC_SCAN_DIRECT_ENGINE)
				return new SymantecScanDirectEngine();
		}
		
		return null;
	}
	
	/**
	 * @param string $baseClass
	 * @param string $enumValue
	 * @return string
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		if($baseClass == 'VirusScanEngine')
		{
			if($enumValue == BorhanVirusScanEngineType::SYMANTEC_SCAN_ENGINE)
				return 'SymantecScanEngine';
			
			if($enumValue == BorhanVirusScanEngineType::SYMANTEC_SCAN_JAVA_ENGINE)
				return 'SymantecScanJavaEngine';

			if($enumValue == BorhanVirusScanEngineType::SYMANTEC_SCAN_DIRECT_ENGINE)
				return 'SymantecScanDirectEngine';
		}

		return null;
	}
}