<?php
/**
 * @package plugins.youtubeApiDistribution
 * @subpackage api.objects
 *
 */
class BorhanYouTubeApiCaptionDistributionInfo extends BorhanObject{

	/**
	 * @var string
	 */
	public $language; 
	
	/**
	 * @var string
	 */
	public $label; 
	
	/**
	 * @var string
	 */
	public $filePath;
	
	/**
	 * @var string
	 */
	public $remoteId;
	
	/**
	 * @var BorhanYouTubeApiDistributionCaptionAction
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
		
}