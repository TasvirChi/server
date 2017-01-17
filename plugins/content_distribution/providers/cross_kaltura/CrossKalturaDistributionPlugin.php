<?php
/**
 * @package plugins.crossBorhanDistribution
 */
class CrossBorhanDistributionPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanEnumerator, IBorhanPending, IBorhanObjectLoader, IBorhanContentDistributionProvider, IBorhanEventConsumers
{
    
	const PLUGIN_NAME = 'crossBorhanDistribution';
	const CROSS_BORHAN_EVENT_CONSUMER = 'kCrossBorhanDistributionEventsConsumer';
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
			return array('CrossBorhanDistributionProviderType');
			
		if($baseEnumName == 'DistributionProviderType')
			return array('CrossBorhanDistributionProviderType');
			
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::loadObject()
	 */
	public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
	{			
		$objectClass = self::getObjectClass($baseClass, $enumValue);
		
		if (is_null($objectClass)) {
			return null;
		}
		
		if (!is_null($constructorArgs))
		{
			$reflect = new ReflectionClass($objectClass);
			return $reflect->newInstanceArgs($constructorArgs);
		}
		else
		{
			return new $objectClass();
		}
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanObjectLoader::getObjectClass()
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		// client side apps like batch and admin console
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::CROSS_BORHAN)
		{
			if($baseClass == 'IDistributionEngineDelete')
				return 'CrossBorhanDistributionEngine';
					
			if($baseClass == 'IDistributionEngineSubmit')
				return 'CrossBorhanDistributionEngine';
					
			if($baseClass == 'IDistributionEngineUpdate')
				return 'CrossBorhanDistributionEngine';

			if($baseClass == 'BorhanDistributionJobProviderData')
				return 'BorhanCrossBorhanDistributionJobProviderData';
								
			if($baseClass == 'BorhanDistributionProfile')
				return 'BorhanCrossBorhanDistributionProfile';
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::CROSS_BORHAN)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
				return 'Form_CrossBorhanProfileConfiguration';
				
			if($baseClass == 'Borhan_Client_ContentDistribution_Type_DistributionProfile')
				return 'Borhan_Client_CrossBorhanDistribution_Type_CrossBorhanDistributionProfile';
		}
		
		// content distribution does not work in partner services 2 context because it uses dynamic enums
		if (!class_exists('kCurrentContext') || kCurrentContext::$ps_vesion != 'ps3')
			return null;
		
		if($baseClass == 'BorhanDistributionJobProviderData' && $enumValue == self::getDistributionProviderTypeCoreValue(CrossBorhanDistributionProviderType::CROSS_BORHAN))
			return 'BorhanCrossBorhanDistributionJobProviderData';
	
		if($baseClass == 'kDistributionJobProviderData' && $enumValue == self::getApiValue(CrossBorhanDistributionProviderType::CROSS_BORHAN))
			return 'kCrossBorhanDistributionJobProviderData';
	
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(CrossBorhanDistributionProviderType::CROSS_BORHAN))
			return 'BorhanCrossBorhanDistributionProfile';
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(CrossBorhanDistributionProviderType::CROSS_BORHAN))
			return 'CrossBorhanDistributionProfile';
			
		return null;
	}
	
	/**
	 * Return a distribution provider instance
	 * 
	 * @return IDistributionProvider
	 */
	public static function getProvider()
	{
		return CrossBorhanDistributionProvider::get();
	}
	
	/**
	 * Return an API distribution provider instance
	 * 
	 * @return BorhanDistributionProvider
	 */
	public static function getBorhanProvider()
	{
		$distributionProvider = new BorhanCrossBorhanDistributionProvider();
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
		// nothing special
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
	
	/* (non-PHPdoc)
	 * @see IBorhanEventConsumers::getEventConsumers()
	 */
	public static function getEventConsumers()
	{
		return array(
			self::CROSS_BORHAN_EVENT_CONSUMER,
		);
	}
}
