<?php
/**
 * @package plugins.playReady
 * @subpackage api.objects
 */
class BorhanPlayReadyPlayRight extends BorhanPlayReadyRight
{
    /**
	 * @var BorhanPlayReadyAnalogVideoOPL
	 */
	public $analogVideoOPL ;
	
	/**
	 * @var BorhanPlayReadyAnalogVideoOPIdHolderArray
	 */
	public $analogVideoOutputProtectionList ;
	
    /**
	 * @var BorhanPlayReadyDigitalAudioOPL
	 */
	public $compressedDigitalAudioOPL ;
	
    /**
	 * @var BorhanPlayReadyCompressedDigitalVideoOPL
	 */
	public $compressedDigitalVideoOPL ;

	/**
	 * @var BorhanPlayReadyDigitalAudioOPIdHolderArray
	 */
	public $digitalAudioOutputProtectionList; 
	
	/**
	 * @var BorhanPlayReadyDigitalAudioOPL
	 */	
	public $uncompressedDigitalAudioOPL;

    /**
	 * @var BorhanPlayReadyUncompressedDigitalVideoOPL
	 */
	public $uncompressedDigitalVideoOPL; 
	
    /**
	 * @var int
	 */
	public $firstPlayExpiration;
	
    /**
	 * @var BorhanPlayReadyPlayEnablerHolderArray
	 */
	public $playEnablers; 
	
	
	private static $map_between_objects = array(
		'analogVideoOPL',
    	'analogVideoOutputProtectionList',
    	'compressedDigitalAudioOPL',
    	'compressedDigitalVideoOPL',
		'digitalAudioOutputProtectionList',
		'uncompressedDigitalAudioOPL',
		'uncompressedDigitalVideoOPL',
		'firstPlayExpiration',
		'playEnablers',
	 );
		 
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			$dbObject = new PlayReadyPlayRight();
			
		parent::toObject($dbObject, $skip);
					
		return $dbObject;
	}
}


