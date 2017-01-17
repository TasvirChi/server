<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanLiveStreamEntry extends BorhanLiveEntry
{	
	/**
	 * The stream id as provided by the provider
	 * 
	 * @var string
	 * @readonly
	 */
	public $streamRemoteId;
	
	/**
	 * The backup stream id as provided by the provider
	 * 
	 * @var string
	 * @readonly
	 */
	public $streamRemoteBackupId;
	
	/**
	 * Array of supported bitrates
	 * 
	 * @var BorhanLiveStreamBitrateArray
	 */
	public $bitrates;
	
	/**
	 * @var string
	 */
	public $primaryBroadcastingUrl;
	
	/**
	 * @var string
	 */
	public $secondaryBroadcastingUrl;
	
	/**
	 * @var string
	 */
	public $primaryRtspBroadcastingUrl;
	
	/**
	 * @var string
	 */
	public $secondaryRtspBroadcastingUrl;
	
	/**
	 * @var string
	 */
	public $streamName;
	
	/**
	 * The stream url
	 * 
	 * @var string
	 */
	public $streamUrl;
	
	/**
	 * HLS URL - URL for live stream playback on mobile device
	 * @var string
	 */
	public $hlsStreamUrl;
	
	/**
	 * URL Manager to handle the live stream URL (for instance, add token)
	 * @var string
	 * @deprecated
	 */
	public $urlManager;
	
	/**
	 * The broadcast primary ip
	 * @requiresPermission all
	 * @var string
	 */
	public $encodingIP1;
	
	/**
	 * The broadcast secondary ip
	 * 
	 * @requiresPermission all
	 * @var string
	 */
	public $encodingIP2;
	
	/**
	 * The broadcast password
	 * 
	 * @requiresPermission all
	 * @var string
	 */
	public $streamPassword;
	
	/**
	 * The broadcast username
	 * 
	 * @requiresPermission read
	 * @var string
	 * @readonly
	 */
	public $streamUsername;
	
	/**
	 * The Streams primary server node id 
	 *
	 * @var int
	 * @readonly
	 */
	public $primaryServerNodeId;
	
	private static $map_between_objects = array
	(
		"streamRemoteId",
	 	"streamRemoteBackupId",
		"primaryBroadcastingUrl",
		"secondaryBroadcastingUrl",
		"primaryRtspBroadcastingUrl",
		"secondaryRtspBroadcastingUrl",
		"streamName",
		"streamUrl",
	    "hlsStreamUrl",
		"encodingIP1",
		"encodingIP2",
		"streamPassword",
		"streamUsername",
		"bitrates" => "streamBitrates",
		"primaryServerNodeId",
	);

	public function __construct()
	{
		parent::__construct();
		
		$this->type = BorhanEntryType::LIVE_STREAM;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		if(!($dbObject instanceof LiveStreamEntry))
			return;

		/**
		 * @var LiveStreamEntry @dbObject
		 */
		$ksObject = kCurrentContext::$ks_object;
		if ( !kCurrentContext::$is_admin_session && !(kCurrentContext::getCurrentKsKuserId() == $dbObject->getKuserId())
				&& (!$ksObject || !$ksObject->verifyPrivileges(ks::PRIVILEGE_EDIT, $this->id)) )
		{
			$this->primaryBroadcastingUrl = null;
			$this->secondaryBroadcastingUrl = null;
			$this->primaryRtspBroadcastingUrl = null;
			$this->secondaryRtspBroadcastingUrl = null;
		}
		parent::doFromObject($dbObject, $responseProfile);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::toInsertableObject()
	 */
	public function toInsertableObject ( $dbObject = null , $props_to_skip = array() )
	{
		//This is required for backward compatibility support of api calls in KMS
		$propertiesToSkip[] = "id";
		
		/* @var $dbObject LiveStreamEntry */
		
		// if the given password is empty, generate a random 8-character string as the new password
		if(($this->streamPassword == null) || (strlen(trim($this->streamPassword)) <= 0))
		{
			$tempPassword = sha1(md5(uniqid(rand(), true)));
			$this->streamPassword = substr($tempPassword, rand(0, strlen($tempPassword) - 8), 8);		
		}
	
		return parent::toInsertableObject($dbObject, $props_to_skip);
	}

	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::toSourceType()
	 */
	protected function toSourceType(entry $entry) 
	{
		if (!$this->sourceType)
		{
			$partner = PartnerPeer::retrieveByPK(kCurrentContext::getCurrentPartnerId());
			if($partner)
				$this->sourceType = kPluginableEnumsManager::coreToApi('EntrySourceType', $partner->getDefaultLiveStreamEntrySourceType());
		}
		
		return parent::toSourceType($entry);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseEntry::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		//This is required for backward compatibility support of api calls in KMS
		$propertiesToSkip[] = "id";
		
		$this->validatePropertyNotNull("mediaType");
		$this->validatePropertyNotNull("sourceType");
		$this->validatePropertyNotNull("streamPassword");
		if (in_array($this->sourceType, array(BorhanSourceType::AKAMAI_LIVE,BorhanSourceType::AKAMAI_UNIVERSAL_LIVE)))
		{
			$this->validatePropertyNotNull("encodingIP1");
			$this->validatePropertyNotNull("encodingIP2");
		}
		
		parent::validateForInsert($propertiesToSkip);
	}
	
	protected function validateEncodingIP ($ip)
	{
		if (!filter_var($this->encodingIP1, FILTER_VALIDATE_IP))
			throw new BorhanAPIException(BorhanErrors::ENCODING_IP_NOT_PINGABLE);	
		
		@exec("ping -w " . kConf::get('ping_default_timeout') . " {$this->encodingIP1}", $output, $return);
		if ($return)
			throw new BorhanAPIException(BorhanErrors::ENCODING_IP_NOT_PINGABLE);
	}
}
