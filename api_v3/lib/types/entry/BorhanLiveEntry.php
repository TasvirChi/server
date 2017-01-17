<?php
/**
 * @package api
 * @subpackage objects
 */
abstract class BorhanLiveEntry extends BorhanMediaEntry
{
	/**
	 * The message to be presented when the stream is offline
	 * 
	 * @var string
	 */
	public $offlineMessage;
	
	/**
	 * Recording Status Enabled/Disabled
	 * @var BorhanRecordStatus
	 */
	public $recordStatus;
	
	/**
	 * DVR Status Enabled/Disabled
	 * @var BorhanDVRStatus
	 */
	public $dvrStatus;
	
	/**
	 * Window of time which the DVR allows for backwards scrubbing (in minutes)
	 * @var int
	 */
	public $dvrWindow;
	
	/**
	 * Elapsed recording time (in msec) up to the point where the live stream was last stopped (unpublished).
	 * @var int
	 */
	public $lastElapsedRecordingTime;

	/**
	 * Array of key value protocol->live stream url objects
	 * @var BorhanLiveStreamConfigurationArray
	 */
	public $liveStreamConfigurations;
	
	/**
	 * Recorded entry id
	 * 
	 * @var string
	 */
	public $recordedEntryId;
	

	/**
	 * Flag denoting whether entry should be published by the media server
	 * 
	 * @var BorhanLivePublishStatus
	 * @requiresPermission all
	 */
	public $pushPublishEnabled;
	
	/**
	 * Array of publish configurations
	 * 
	 * @var BorhanLiveStreamPushPublishConfigurationArray
	 * @requiresPermission all
	 */
	public $publishConfigurations;
	
	/**
	 * The first time in which the entry was broadcast
	 * @var int
	 * @readonly
	 * @filter order
	 */
	public $firstBroadcast;
	
	/**
	 * The Last time in which the entry was broadcast
	 * @var int
	 * @readonly
	 * @filter order
	 */
	public $lastBroadcast;
	
	/**
	 * The time (unix timestamp in milliseconds) in which the entry broadcast started or 0 when the entry is off the air
	 * @var float
	 */
	public $currentBroadcastStartTime;

	/**
	 * @var BorhanLiveEntryRecordingOptions
	 */
	public $recordingOptions;

	/**
	 * the status of the entry of type EntryServerNodeStatus
	 * @var BorhanEntryServerNodeStatus
	 * @readonly
	 * @deprecated use BorhanLiveStreamService.isLive instead
	 */
	public $liveStatus;

	private static $map_between_objects = array
	(
		"offlineMessage",
	    "recordStatus",
	    "dvrStatus",
	    "dvrWindow",
		"lastElapsedRecordingTime",
		"liveStreamConfigurations",
		"recordedEntryId",
		"pushPublishEnabled",
		"firstBroadcast",
		"lastBroadcast",
		"publishConfigurations",
		"currentBroadcastStartTime",
		"recordingOptions",
		"liveStatus"
	);
	
	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function toInsertableObject($sourceObject = null, $propsToSkip = array())
	{
		if(is_null($this->recordStatus))
		{
			$this->recordStatus = BorhanRecordStatus::DISABLED;
			if(PermissionPeer::isValidForPartner(PermissionName::FEATURE_LIVE_STREAM_RECORD, kCurrentContext::getCurrentPartnerId()) ||
				PermissionPeer::isValidForPartner(PermissionName::FEATURE_LIVE_STREAM_BORHAN_RECORDING, kCurrentContext::getCurrentPartnerId()) )
			{
				$this->recordStatus = BorhanRecordStatus::APPENDED;
			}
		}
			


		if ((is_null($this->recordingOptions) || is_null($this->recordingOptions->shouldCopyEntitlement)) && PermissionPeer::isValidForPartner(PermissionName::FEATURE_LIVE_STREAM_COPY_ENTITELMENTS, kCurrentContext::getCurrentPartnerId()))
		{
			if (is_null($this->recordingOptions))
			{
				$this->recordingOptions = new BorhanLiveEntryRecordingOptions();
			}
			$this->recordingOptions->shouldCopyEntitlement = true;
		}
		return parent::toInsertableObject($sourceObject, $propsToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanMediaEntry::fromObject()
	 */
	public function doFromObject($dbObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		if(!($dbObject instanceof LiveEntry))
			return;
			
		parent::doFromObject($dbObject, $responseProfile);

		if($this->shouldGet('recordingOptions', $responseProfile) && !is_null($dbObject->getRecordingOptions()))
		{
			$this->recordingOptions = new BorhanLiveEntryRecordingOptions();
			$this->recordingOptions->fromObject($dbObject->getRecordingOptions());
		}
	}

	public function validateConversionProfile(entry $sourceObject = null)
	{
		if(!is_null($this->conversionProfileId) && $this->conversionProfileId != conversionProfile2::CONVERSION_PROFILE_NONE)
		{
			$conversionProfile = conversionProfile2Peer::retrieveByPK($this->conversionProfileId);
			if(!$conversionProfile || $conversionProfile->getType() != ConversionProfileType::LIVE_STREAM)
				throw new BorhanAPIException(BorhanErrors::CONVERSION_PROFILE_ID_NOT_FOUND, $this->conversionProfileId);
		}
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUpdate($source_object)
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		$updateValidateAttributes = array(
				"dvrStatus" => array("validatePropertyChanged"), 
				"dvrWindow" => array("validatePropertyChanged"), 
				"recordingOptions" => array("validateRecordingOptionsChanged"),
				"recordStatus" => array("validatePropertyChanged","validateRecordedEntryId"), 
				"conversionProfileId" => array("validatePropertyChanged","validateRecordedEntryId")
		);
		
		foreach ($updateValidateAttributes as $attr => $validateFucntions)
		{
			if(isset($this->$attr))
			{
				foreach ($validateFucntions as $function)
				{
					$this->$function($sourceObject, $attr);
				}
			}
		}
		
		parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	protected function validatePropertyChanged($sourceObject, $attr)
	{
		$resolvedAttrName = $this->getObjectPropertyName($attr);
		if(!$resolvedAttrName)
			throw new BorhanAPIException(BorhanErrors::PROPERTY_IS_NOT_DEFINED, $attr, get_class($this));
		
		/* @var $sourceObject LiveEntry */
		$getter = "get" . ucfirst($resolvedAttrName);
		if($sourceObject->$getter() !== $this->$attr && $sourceObject->getLiveStatus() !== BorhanEntryServerNodeStatus::STOPPED)
		{
			throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_FIELDS_WHILE_ENTRY_BROADCASTING, $attr);
		}
	}
	
	protected function validateRecordedEntryId($sourceObject, $attr)
	{
		$resolvedAttrName = $this->getObjectPropertyName($attr);
		if(!$resolvedAttrName)
			throw new BorhanAPIException(BorhanErrors::PROPERTY_IS_NOT_DEFINED, $attr, get_class($this));
		
		/* @var $sourceObject LiveEntry */
		$getter = "get" . ucfirst($resolvedAttrName);
		if($sourceObject->$getter() !== $this->$attr)
		{
			$this->validateRecordingDone($sourceObject, $attr);
		}
	}
	
	private function validateRecordingDone($sourceObject, $attr)
	{
		/* @var $sourceObject LiveEntry */
		$recordedEntry = $sourceObject->getRecordedEntryId() ? entryPeer::retrieveByPK($sourceObject->getRecordedEntryId()) : null;
		if($recordedEntry)
		{
			$validUpdateStatuses = array(BorhanEntryStatus::READY, BorhanEntryStatus::ERROR_CONVERTING, BorhanEntryStatus::ERROR_IMPORTING);
			if( !in_array($recordedEntry->getStatus(), $validUpdateStatuses) )
				throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_FIELDS_RECORDED_ENTRY_STILL_NOT_READY, $attr);
			
			$noneReadyAssets = assetPeer::retrieveByEntryId($recordedEntry->getId(),
					array(BorhanAssetType::FLAVOR),
					array(BorhanFlavorAssetStatus::CONVERTING, BorhanFlavorAssetStatus::QUEUED, BorhanFlavorAssetStatus::WAIT_FOR_CONVERT, BorhanFlavorAssetStatus::VALIDATING));
			
			if(count($noneReadyAssets))
				throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_FIELDS_RECORDED_ENTRY_STILL_NOT_READY, $attr);
		}
	}
	
	protected function validateRecordingOptionsChanged($sourceObject, $attr)
	{
		if(!isset($this->recordingOptions))
			return;
		
		if(!isset($this->recordingOptions->shouldCopyEntitlement))
			return;
		
		/* @var $sourceObject LiveEntry */
		$hasObjectChanged = false;
		if( !$sourceObject->getRecordingOptions() || ($sourceObject->getRecordingOptions() && $sourceObject->getRecordingOptions()->getShouldCopyEntitlement() !== $this->recordingOptions->shouldCopyEntitlement) )
			$hasObjectChanged = true;
		
		if($hasObjectChanged)
		{
			if( $sourceObject->getLiveStatus() !== BorhanEntryServerNodeStatus::STOPPED)
				throw new BorhanAPIException(BorhanErrors::CANNOT_UPDATE_FIELDS_WHILE_ENTRY_BROADCASTING, "recordingOptions");
			
			$this->validateRecordingDone($sourceObject, "recordingOptions");
		}
	}
}
