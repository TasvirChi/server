<?php
/**
 * @package plugins.attUverseDistribution
 * @subpackage api.objects
 */
class BorhanAttUverseDistributionFile extends BorhanObject
{
	
	/**
	 * @var string
	 */
	public $remoteFilename;
	
	/**
	 * @var string
	 */
	public $localFilePath;
	
	/**
	 * @var BorhanAssetType
	 */
	public $assetType;
	
	/**
	 * @var string
	 */
	public $assetId;

}
