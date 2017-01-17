<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanAkamaiUniversalProvisionJobData extends BorhanProvisionJobData
{
	/**
	 * @var int
	 */
	public $streamId;
	
	/**
	 * @var string
	 */
	public $systemUserName;
	
	/**
	 * @var string
	 */
	public $systemPassword;
	
	/**
	 * @var string
	 */
	public $domainName;

	/**
	 * @var BorhanDVRStatus
	 */
	public $dvrEnabled;
	
	/**
	 * @var int
	 */
	public $dvrWindow;
	
	/**
	 * @var string
	 */
	public $primaryContact;
	
	/**
	 * @var string
	 */
	public $secondaryContact;
	
	/**
	 * @var BorhanAkamaiUniversalStreamType
	 */
	public $streamType;
	
	/**
	 * @var string
	 */
	public $notificationEmail;
	
	private static $map_between_objects = array
	(
		"streamId",
		"systemUserName",
		"systemPassword",
		"domainName",
		"dvrEnabled",
		"dvrWindow",
		"primaryContact",
		"secondaryContact",
		"streamType",
		"notificationEmail",
	);

	/* (non-PHPdoc)
	 * @see BorhanProvisionJobData::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	
	/* (non-PHPdoc)
	 * @see BorhanProvisionJobData::toObject()
	 */
	public function toObject($dbData = null, $props_to_skip = array()) 
	{
		if(is_null($dbData))
			$dbData = new kAkamaiUniversalProvisionJobData();
			
		return parent::toObject($dbData, $props_to_skip);
	}
	
}