<?php
/**
* @package plugins.doubleClickDistribution
 * @subpackage api.objects
 */
class BorhanDoubleClickDistributionJobProviderData extends BorhanDistributionJobProviderData
{
	public function __construct(BorhanDistributionJobData $distributionJobData = null)
	{
	}

	private static $map_between_objects = array();

	public function getMapBetweenObjects()
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}	
}
