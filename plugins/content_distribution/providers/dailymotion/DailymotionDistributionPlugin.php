<?php
/**
 * @package plugins.dailymotionDistribution
 */
class DailymotionDistributionPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanEnumerator, IBorhanPending, IBorhanObjectLoader, IBorhanContentDistributionProvider
{
	const PLUGIN_NAME = 'dailymotionDistribution';
	const CONTENT_DSTRIBUTION_VERSION_MAJOR = 2;
	const CONTENT_DSTRIBUTION_VERSION_MINOR = 0;
	const CONTENT_DSTRIBUTION_VERSION_BUILD = 0;
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	public static function dependsOn()
	{
		$contentDistributionVersion = new BorhanVersion(
			self::CONTENT_DSTRIBUTION_VERSION_MAJOR,
			self::CONTENT_DSTRIBUTION_VERSION_MINOR,
			self::CONTENT_DSTRIBUTION_VERSION_BUILD);
			
		$dependency = new BorhanDependency(ContentDistributionPlugin::getPluginName(), $contentDistributionVersion);
		return array($dependency);
	}
	
	public static function isAllowedPartner($partnerId)
	{
		if($partnerId == Partner::ADMIN_CONSOLE_PARTNER_ID)
			return true;
			
		$partner = PartnerPeer::retrieveByPK($partnerId);
		return $partner->getPluginEnabled(ContentDistributionPlugin::getPluginName());
	}
	
	/**
	 * @return array<string> list of enum classes names that extend the base enum name
	 */
	public static function getEnums($baseEnumName = null)
	{
		if(is_null($baseEnumName))
			return array('DailymotionDistributionProviderType');
			
		if($baseEnumName == 'DistributionProviderType')
			return array('DailymotionDistributionProviderType');
			
		return array();
	}
	
	/**
	 * @param string $baseClass
	 * @param string $enumValue
	 * @param array $constructorArgs
	 * @return object
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{
		// client side apps like batch and admin console
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::DAILYMOTION)
		{
			if($baseClass == 'IDistributionEngineCloseDelete')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineCloseSubmit')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineCloseUpdate')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineDelete')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineReport')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineSubmit')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineUpdate')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineEnable')
				return new DailymotionDistributionEngine();
					
			if($baseClass == 'IDistributionEngineDisable')
				return new DailymotionDistributionEngine();
		
			if($baseClass == 'BorhanDistributionProfile')
				return new BorhanDailymotionDistributionProfile();
		
			if($baseClass == 'BorhanDistributionJobProviderData')
				return new BorhanDailymotionDistributionJobProviderData();
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::DAILYMOTION)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
			{
				$reflect = new ReflectionClass('Form_DailymotionProfileConfiguration');
				return $reflect->newInstanceArgs($constructorArgs);
			}
		}
		
		if($baseClass == 'BorhanDistributionJobProviderData' && $enumValue == self::getDistributionProviderTypeCoreValue(DailymotionDistributionProviderType::DAILYMOTION))
		{
			$reflect = new ReflectionClass('BorhanDailymotionDistributionJobProviderData');
			return $reflect->newInstanceArgs($constructorArgs);
		}
	
		if($baseClass == 'kDistributionJobProviderData' && $enumValue == self::getApiValue(DailymotionDistributionProviderType::DAILYMOTION))
		{
			$reflect = new ReflectionClass('kDailymotionDistributionJobProviderData');
			return $reflect->newInstanceArgs($constructorArgs);
		}
	
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(DailymotionDistributionProviderType::DAILYMOTION))
			return new BorhanDailymotionDistributionProfile();
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(DailymotionDistributionProviderType::DAILYMOTION))
			return new DailymotionDistributionProfile();
			
		return null;
	}
	
	/**
	 * @param string $baseClass
	 * @param string $enumValue
	 * @return string
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		// client side apps like batch and admin console
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::DAILYMOTION)
		{
			if($baseClass == 'IDistributionEngineCloseDelete')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineCloseSubmit')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineCloseUpdate')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineDelete')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineReport')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineSubmit')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineUpdate')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineEnable')
				return 'DailymotionDistributionEngine';
					
			if($baseClass == 'IDistributionEngineDisable')
				return 'DailymotionDistributionEngine';
		
			if($baseClass == 'BorhanDistributionProfile')
				return 'BorhanDailymotionDistributionProfile';
		
			if($baseClass == 'BorhanDistributionJobProviderData')
				return 'BorhanDailymotionDistributionJobProviderData';
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::DAILYMOTION)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
				return 'Form_DailymotionProfileConfiguration';
				
			if($baseClass == 'Borhan_Client_ContentDistribution_Type_DistributionProfile')
				return 'Borhan_Client_DailymotionDistribution_Type_DailymotionDistributionProfile';
		}
		
		if($baseClass == 'BorhanDistributionJobProviderData' && $enumValue == self::getDistributionProviderTypeCoreValue(DailymotionDistributionProviderType::DAILYMOTION))
			return 'BorhanDailymotionDistributionJobProviderData';
	
		if($baseClass == 'kDistributionJobProviderData' && $enumValue == self::getApiValue(DailymotionDistributionProviderType::DAILYMOTION))
			return 'kDailymotionDistributionJobProviderData';
	
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(DailymotionDistributionProviderType::DAILYMOTION))
			return 'BorhanDailymotionDistributionProfile';
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(DailymotionDistributionProviderType::DAILYMOTION))
			return 'DailymotionDistributionProfile';
			
		return null;
	}
	
	/**
	 * Return a distribution provider instance
	 * 
	 * @return IDistributionProvider
	 */
	public static function getProvider()
	{
		return DailymotionDistributionProvider::get();
	}
	
	/**
	 * Return an API distribution provider instance
	 * 
	 * @return BorhanDistributionProvider
	 */
	public static function getBorhanProvider()
	{
		$distributionProvider = new BorhanDailymotionDistributionProvider();
		$distributionProvider->fromObject(self::getProvider());
		return $distributionProvider;
	}
	
	/**
	 * Append provider specific nodes and attributes to the MRSS
	 * 
	 * @param EntryDistribution $entryDistribution
	 * @param SimpleXMLElement $mrss
	 */
	public static function contributeMRSS(EntryDistribution $entryDistribution, SimpleXMLElement $mrss)
	{
		
	}
	
	/**
	 * @return int id of dynamic enum in the DB.
	 */
	public static function getDistributionProviderTypeCoreValue($valueName)
	{
		$value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
		return kPluginableEnumsManager::apiToCore('DistributionProviderType', $value);
	}
	
	/**
	 * @return string external API value of dynamic enum.
	 */
	public static function getApiValue($valueName)
	{
		return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
	}
}
