<?php
/**
 * @package plugins.freewheelGenericDistribution
 */
class FreewheelGenericDistributionPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanEnumerator, IBorhanPending, IBorhanObjectLoader, IBorhanContentDistributionProvider
{
	const PLUGIN_NAME = 'freewheelGenericDistribution';
	const CONTENT_DSTRIBUTION_VERSION_MAJOR = 2;
	const CONTENT_DSTRIBUTION_VERSION_MINOR = 0;
	const CONTENT_DSTRIBUTION_VERSION_BUILD = 0;

	const DEPENDENTS_ON_PLUGIN_NAME_CUE_POINT = 'cuePoint';

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

		$dependency1 = new BorhanDependency(ContentDistributionPlugin::getPluginName(), $contentDistributionVersion);
		$dependency2 = new BorhanDependency(FreewheelGenericDistributionPlugin::DEPENDENTS_ON_PLUGIN_NAME_CUE_POINT);
		return array($dependency1, $dependency2);
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
			return array('FreewheelGenericDistributionProviderType');
	
		if($baseEnumName == 'DistributionProviderType')
			return array('FreewheelGenericDistributionProviderType');
			
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
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::FREEWHEEL_GENERIC)
		{
			if($baseClass == 'IDistributionEngineCloseDelete')
				return new FreewheelGenericDistributionEngine();
					
			if($baseClass == 'IDistributionEngineCloseSubmit')
				return new FreewheelGenericDistributionEngine();
					
			if($baseClass == 'IDistributionEngineCloseUpdate')
				return new FreewheelGenericDistributionEngine();
					
			if($baseClass == 'IDistributionEngineDelete')
				return new FreewheelGenericDistributionEngine();
					
			if($baseClass == 'IDistributionEngineReport')
				return new FreewheelGenericDistributionEngine();
					
			if($baseClass == 'IDistributionEngineSubmit')
				return new FreewheelGenericDistributionEngine();
					
			if($baseClass == 'IDistributionEngineUpdate')
				return new FreewheelGenericDistributionEngine();
		
			if($baseClass == 'IDistributionEngineEnable')
				return new FreewheelGenericDistributionEngine();
					
			if($baseClass == 'IDistributionEngineDisable')
				return new FreewheelGenericDistributionEngine();
		
			if($baseClass == 'BorhanDistributionProfile')
				return new BorhanFreewheelGenericDistributionProfile();
		
			if($baseClass == 'BorhanDistributionJobProviderData')
				return new BorhanFreewheelGenericDistributionJobProviderData();
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::FREEWHEEL_GENERIC)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
			{
				$reflect = new ReflectionClass('Form_FreewheelGenericProfileConfiguration');
				return $reflect->newInstanceArgs($constructorArgs);
			}
		}
		
		if($baseClass == 'BorhanDistributionJobProviderData' && $enumValue == self::getDistributionProviderTypeCoreValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
		{
			$reflect = new ReflectionClass('BorhanFreewheelGenericDistributionJobProviderData');
			return $reflect->newInstanceArgs($constructorArgs);
		}
	
		if($baseClass == 'kDistributionJobProviderData' && $enumValue == self::getApiValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
		{
			$reflect = new ReflectionClass('kFreewheelGenericDistributionJobProviderData');
			return $reflect->newInstanceArgs($constructorArgs);
		}
	
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
			return new BorhanFreewheelGenericDistributionProfile();
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
			return new FreewheelGenericDistributionProfile();
			
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
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::FREEWHEEL_GENERIC)
		{
			if($baseClass == 'IDistributionEngineCloseDelete')
				return 'FreewheelGenericDistributionEngine';
					
			if($baseClass == 'IDistributionEngineCloseSubmit')
				return 'FreewheelGenericDistributionEngine';
					
			if($baseClass == 'IDistributionEngineCloseUpdate')
				return 'FreewheelGenericDistributionEngine';
					
			if($baseClass == 'IDistributionEngineDelete')
				return 'FreewheelGenericDistributionEngine';
					
			if($baseClass == 'IDistributionEngineReport')
				return 'FreewheelGenericDistributionEngine';
					
			if($baseClass == 'IDistributionEngineSubmit')
				return 'FreewheelGenericDistributionEngine';
					
			if($baseClass == 'IDistributionEngineUpdate')
				return 'FreewheelGenericDistributionEngine';
		
			if($baseClass == 'IDistributionEngineEnable')
				return 'FreewheelGenericDistributionEngine';
					
			if($baseClass == 'IDistributionEngineDisable')
				return 'FreewheelGenericDistributionEngine';
		
			if($baseClass == 'BorhanDistributionProfile')
				return 'BorhanFreewheelGenericDistributionProfile';
		
			if($baseClass == 'BorhanDistributionJobProviderData')
				return 'BorhanFreewheelGenericDistributionJobProviderData';
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::FREEWHEEL_GENERIC)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
				return 'Form_FreewheelGenericProfileConfiguration';
				
			if($baseClass == 'Borhan_Client_ContentDistribution_Type_DistributionProfile')
				return 'Borhan_Client_FreewheelGenericDistribution_Type_FreewheelGenericDistributionProfile';
		}
		
		if($baseClass == 'BorhanDistributionJobProviderData' && $enumValue == self::getDistributionProviderTypeCoreValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
			return 'BorhanFreewheelGenericDistributionJobProviderData';
	
		if($baseClass == 'kDistributionJobProviderData' && $enumValue == self::getApiValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
			return 'kFreewheelGenericDistributionJobProviderData';
	
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
			return 'BorhanFreewheelGenericDistributionProfile';
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(FreewheelGenericDistributionProviderType::FREEWHEEL_GENERIC))
			return 'FreewheelGenericDistributionProfile';
			
		return null;
	}
	
	/**
	 * Return a distribution provider instance
	 * 
	 * @return IDistributionProvider
	 */
	public static function getProvider()
	{
		return FreewheelGenericDistributionProvider::get();
	}
	
	/**
	 * Return an API distribution provider instance
	 * 
	 * @return BorhanDistributionProvider
	 */
	public static function getBorhanProvider()
	{
		$distributionProvider = new BorhanFreewheelGenericDistributionProvider();
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
