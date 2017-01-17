<?php
/**
 * @package plugins.uverseDistribution
 */
class UverseDistributionPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanEnumerator, IBorhanPending, IBorhanObjectLoader, IBorhanContentDistributionProvider, IBorhanEventConsumers, IBorhanServices
{
	const PLUGIN_NAME = 'uverseDistribution';
	const CONTENT_DSTRIBUTION_VERSION_MAJOR = 2;
	const CONTENT_DSTRIBUTION_VERSION_MINOR = 0;
	const CONTENT_DSTRIBUTION_VERSION_BUILD = 0;
	
	const UVERSE_EVENT_CONSUMER = 'kUverseDistributionEventConsumer';
	
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
			return array('UverseDistributionProviderType');
	
		if($baseEnumName == 'DistributionProviderType')
			return array('UverseDistributionProviderType');
			
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
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::UVERSE)
		{
			if($baseClass == 'IDistributionEngineDelete')
				return new UverseDistributionEngine();
					
			if($baseClass == 'IDistributionEngineReport')
				return new UverseDistributionEngine();
					
			if($baseClass == 'IDistributionEngineSubmit')
				return new UverseDistributionEngine();
					
			if($baseClass == 'IDistributionEngineUpdate')
				return new UverseDistributionEngine();
		
			if($baseClass == 'BorhanDistributionProfile')
				return new BorhanUverseDistributionProfile();
		
			if($baseClass == 'BorhanDistributionJobProviderData')
				return new BorhanUverseDistributionJobProviderData();
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::UVERSE)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
			{
				$reflect = new ReflectionClass('Form_UverseProfileConfiguration');
				return $reflect->newInstanceArgs($constructorArgs);
			}
		}
		
		// content distribution does not work in partner services 2 context because it uses dynamic enums
		if (!class_exists('kCurrentContext') || kCurrentContext::$ps_vesion != 'ps3')
			return null;

		if($baseClass == 'BorhanDistributionJobProviderData' && $enumValue == self::getDistributionProviderTypeCoreValue(UverseDistributionProviderType::UVERSE))
		{
			$reflect = new ReflectionClass('BorhanUverseDistributionJobProviderData');
			return $reflect->newInstanceArgs($constructorArgs);
		}
	
		if($baseClass == 'kDistributionJobProviderData' && $enumValue == self::getApiValue(UverseDistributionProviderType::UVERSE))
		{
			$reflect = new ReflectionClass('kUverseDistributionJobProviderData');
			return $reflect->newInstanceArgs($constructorArgs);
		}
	
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(UverseDistributionProviderType::UVERSE))
			return new BorhanUverseDistributionProfile();
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(UverseDistributionProviderType::UVERSE))
			return new UverseDistributionProfile();
			
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
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::UVERSE)
		{
			if($baseClass == 'IDistributionEngineDelete')
				return 'UverseDistributionEngine';
					
			if($baseClass == 'IDistributionEngineReport')
				return 'UverseDistributionEngine';
					
			if($baseClass == 'IDistributionEngineSubmit')
				return 'UverseDistributionEngine';
					
			if($baseClass == 'IDistributionEngineUpdate')
				return 'UverseDistributionEngine';
		
			if($baseClass == 'BorhanDistributionProfile')
				return 'BorhanUverseDistributionProfile';
		
			if($baseClass == 'BorhanDistributionJobProviderData')
				return 'BorhanUverseDistributionJobProviderData';
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::UVERSE)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
				return 'Form_UverseProfileConfiguration';
				
			if($baseClass == 'Borhan_Client_ContentDistribution_Type_DistributionProfile')
				return 'Borhan_Client_UverseDistribution_Type_UverseDistributionProfile';
		}
		
		// content distribution does not work in partner services 2 context because it uses dynamic enums
		if (!class_exists('kCurrentContext') || kCurrentContext::$ps_vesion != 'ps3')
			return null;

		if($baseClass == 'BorhanDistributionJobProviderData' && $enumValue == self::getDistributionProviderTypeCoreValue(UverseDistributionProviderType::UVERSE))
			return 'BorhanUverseDistributionJobProviderData';
	
		if($baseClass == 'kDistributionJobProviderData' && $enumValue == self::getApiValue(UverseDistributionProviderType::UVERSE))
			return 'kUverseDistributionJobProviderData';
	
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(UverseDistributionProviderType::UVERSE))
			return 'BorhanUverseDistributionProfile';
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(UverseDistributionProviderType::UVERSE))
			return 'UverseDistributionProfile';
			
		return null;
	}
	
	/**
	 * Return a distribution provider instance
	 * 
	 * @return IDistributionProvider
	 */
	public static function getProvider()
	{
		return UverseDistributionProvider::get();
	}
	
	/**
	 * Return an API distribution provider instance
	 * 
	 * @return BorhanDistributionProvider
	 */
	public static function getBorhanProvider()
	{
		$distributionProvider = new BorhanUverseDistributionProvider();
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
	
	/**
	 * @return array
	 */
	public static function getEventConsumers()
	{
		return array(
			self::UVERSE_EVENT_CONSUMER,
		);
	}
	
	
	/* (non-PHPdoc)
	 * @see IBorhanServices::getServicesMap()
	 */
	public static function getServicesMap()
	{
		return array(
			'uverse' => 'UverseService'
		);
	}
}
