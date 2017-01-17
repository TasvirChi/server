<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanEntryContextDataResult extends BorhanContextDataResult
{
	/**
	 * @var bool
	 * @deprecated
	 */
	public $isSiteRestricted = false;
	
	/**
	 * @var bool
	 * @deprecated
	 */
	public $isCountryRestricted = false;
	
	/**
	 * @var bool
	 * @deprecated
	 */
	public $isSessionRestricted = false;
	
	/**
	 * @var bool
	 * @deprecated
	 */
	public $isIpAddressRestricted = false;
	
	/**
	 * @var bool
	 * @deprecated
	 */
	public $isUserAgentRestricted = false;
	
	/**
	 * @var int
	 * @deprecated
	 */
	public $previewLength = -1;
	
	/**
	 * @var bool
	 */
	public $isScheduledNow;
	
	/**
	 * @var bool
	 */
	public $isAdmin;
	
	/**
	 * http/rtmp/hdnetwork
	 * @var string
	 */
	public $streamerType;
	
	/**
	 * http/https, rtmp/rtmpe
	 * @var string
	 */
	public $mediaProtocol;
	
	/**
	 * @var string
	 */
	public $storageProfilesXML;
	
	/**
	 * Array of messages as received from the access control rules that invalidated
	 * @var BorhanStringArray
	 * @deprecated
	 */
	public $accessControlMessages;
	
	/**
	 * Array of actions as received from the access control rules that invalidated
	 * @var BorhanRuleActionArray
	 * @deprecated
	 */
	public $accessControlActions;
	
	/**
	 * Array of allowed flavor assets according to access control limitations and requested tags
	 * 
	 * @var BorhanFlavorAssetArray
	 */
	public $flavorAssets;

	/**
	 * The duration of the entry in milliseconds
	 * 
	 * @var int
	 */
	public $msDuration;
	
	/**
     * Array of allowed flavor assets according to access control limitations and requested tags
     *
     * @var BorhanPluginDataArray
     */
    public $pluginData;

	private static $mapBetweenObjects = array
	(
		'isSiteRestricted',
		'isCountryRestricted',
		'isSessionRestricted',
		'isIpAddressRestricted',
		'isUserAgentRestricted',
		'previewLength',
		'accessControlMessages' => 'messages',
		'accessControlActions' => 'actions',
		'msDuration',
	);
	
	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
}