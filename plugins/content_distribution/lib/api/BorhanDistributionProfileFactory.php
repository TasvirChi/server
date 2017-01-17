<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api
 */
class BorhanDistributionProfileFactory
{	
	/**
	 * @param int $providerType
	 * @return BorhanDistributionProfile
	 */
	public static function createBorhanDistributionProfile($providerType)
	{
		if($providerType == BorhanDistributionProviderType::GENERIC)
			return new BorhanGenericDistributionProfile();
			
		if($providerType == BorhanDistributionProviderType::SYNDICATION)
			return new BorhanSyndicationDistributionProfile();
			
		$distributionProfile = BorhanPluginManager::loadObject('BorhanDistributionProfile', $providerType);
		if($distributionProfile)
			return $distributionProfile;
		
		return null;
	}
}