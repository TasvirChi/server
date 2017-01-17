<?php
/**
 * @package plugins.synacorHboDistribution
 */
class SynacorHboDistributionPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanEnumerator, IBorhanPending, IBorhanObjectLoader, IBorhanContentDistributionProvider, IBorhanEventConsumers, IBorhanServices
{
	const PLUGIN_NAME = 'synacorHboDistribution';
	const SYNACOR_HBO_EVENT_CONSUMER = 'kSynacorHboFlowManager';
	const CONTENT_DSTRIBUTION_VERSION_MAJOR = 1;
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
			return array('SynacorHboDistributionProviderType');
	
		if($baseEnumName == 'DistributionProviderType')
			return array('SynacorHboDistributionProviderType');
			
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
	

	
	/**
	 * @param string $baseClass
	 * @param string $enumValue
	 * @return string
	 */
	public static function getObjectClass($baseClass, $enumValue)
	{
		// client side apps like batch and admin console
		if (class_exists('BorhanClient') && $enumValue == BorhanDistributionProviderType::SYNACOR_HBO)
		{
			if($baseClass == 'BorhanDistributionProfile')
				return 'BorhanSynacorHboDistributionProfile';
		}
		
		if (class_exists('Borhan_Client_Client') && $enumValue == Borhan_Client_ContentDistribution_Enum_DistributionProviderType::SYNACOR_HBO)
		{
			if($baseClass == 'Form_ProviderProfileConfiguration')
				return 'Form_SynacorHboProfileConfiguration';
				
			if($baseClass == 'Borhan_Client_ContentDistribution_Type_DistributionProfile')
				return 'Borhan_Client_SynacorHboDistribution_Type_SynacorHboDistributionProfile';
		}
		
		if($baseClass == 'BorhanDistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(SynacorHboDistributionProviderType::SYNACOR_HBO))
			return 'BorhanSynacorHboDistributionProfile';
			
		if($baseClass == 'DistributionProfile' && $enumValue == self::getDistributionProviderTypeCoreValue(SynacorHboDistributionProviderType::SYNACOR_HBO))
			return 'SynacorHboDistributionProfile';
			
		return null;
	}
	
	/**
	 * Return a distribution provider instance
	 * 
	 * @return IDistributionProvider
	 */
	public static function getProvider()
	{
		return SynacorHboDistributionProvider::get();
	}
	
	/**
	 * Return an API distribution provider instance
	 * 
	 * @return BorhanDistributionProvider
	 */
	public static function getBorhanProvider()
	{
		$distributionProvider = new BorhanSynacorHboDistributionProvider();
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
	    // append YouTube specific report statistics
	    $distributionProfile = DistributionProfilePeer::retrieveByPK($entryDistribution->getDistributionProfileId());
		$mrss->addChild('feed_title', $distributionProfile->getFeedTitle());
		$mrss->addChild('feed_subtitle', $distributionProfile->getFeedSubtitle());
		$mrss->addChild('feed_link', $distributionProfile->getFeedLink());
		$mrss->addChild('feed_author_name', $distributionProfile->getFeedAuthorName());	
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
			self::SYNACOR_HBO_EVENT_CONSUMER,
		);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanServices::getServicesMap()
	 */
	public static function getServicesMap()
	{
		return array(
			'synacorHbo' => 'SynacorHboService'
		);
	}
}
