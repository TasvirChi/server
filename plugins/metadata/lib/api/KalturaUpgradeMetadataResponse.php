<?php
/**
 * @package plugins.metadata
 * @subpackage api.objects
 */
class BorhanUpgradeMetadataResponse extends BorhanObject
{
	/**
	 * @var int
	 * @readonly
	 */
	public $totalCount;

	/**
	 * @var int
	 * @readonly
	 */
	public $lowerVersionCount;
}