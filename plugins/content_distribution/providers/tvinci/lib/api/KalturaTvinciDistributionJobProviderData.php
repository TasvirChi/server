<?php
/**
 * @package plugins.tvinciDistribution
 * @subpackage api.objects
 */
class BorhanTvinciDistributionJobProviderData extends BorhanConfigurableDistributionJobProviderData
{
	/**
	 * @var string
	 */
	public $xml;

	public function __construct(BorhanDistributionJobData $distributionJobData = null)
	{
		parent::__construct($distributionJobData);

		if( (!$distributionJobData) ||
			(!($distributionJobData->distributionProfile instanceof BorhanTvinciDistributionProfile)) ||
			(! $distributionJobData->entryDistribution) )
			return;

		$entry = null;
		if ( $distributionJobData->entryDistribution->entryId )
		{
			$entry = entryPeer::retrieveByPK($distributionJobData->entryDistribution->entryId);
		}

		if ( ! $entry ) {
			BorhanLog::err("Can't find entry with id: {$distributionJobData->entryDistribution->entryId}");
			return;
		}

		$feedHelper = new TvinciDistributionFeedHelper($distributionJobData->distributionProfile, $entry);

		if ($distributionJobData instanceof BorhanDistributionSubmitJobData)
		{
			$this->xml = $feedHelper->buildSubmitFeed();
		}
		elseif ($distributionJobData instanceof BorhanDistributionUpdateJobData)
		{
			$this->xml = $feedHelper->buildUpdateFeed();
		}
		elseif ($distributionJobData instanceof BorhanDistributionDeleteJobData)
		{
			$this->xml = $feedHelper->buildDeleteFeed();
		}
	}

	private static $map_between_objects = array
	(
		'xml',
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
}
