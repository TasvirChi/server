<?php
/**
 * @package plugins.dailymotionDistribution
 * @subpackage api.objects
 *
 */
class BorhanDailymotionDistributionCaptionInfo extends BorhanObject{

	/**
	 * @var string
	 */
	public $language; 
	
	/**
	 * @var string
	 */
	public $filePath;
	
	/**
	 * @var string
	 */
	public $remoteId;
	
	/**
	 * @var BorhanDailymotionDistributionCaptionAction
	 */
	public $action;	
	
	/**
	 * @var string
	 */
	public $version;
	
	/**
	 * @var string
	 */
	public $assetId;
	
	/**
	 * @var BorhanDailymotionDistributionCaptionFormat
	 */
	public $format;
		
}