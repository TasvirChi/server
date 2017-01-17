<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanDistributionDeleteJobData extends BorhanDistributionJobData
{
	/**
	 * Flag signifying that the associated distribution item should not be moved to 'removed' status
	 * @var bool
	 */
	public $keepDistributionItem;
}
