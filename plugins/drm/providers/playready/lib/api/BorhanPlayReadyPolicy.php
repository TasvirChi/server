<?php
/**
 * @package plugins.playReady
 * @subpackage api.objects
 */
class BorhanPlayReadyPolicy extends BorhanDrmPolicy
{
    /**
	 * @var int
	 */
	public $gracePeriod;	
	
	/**
	 * @var BorhanPlayReadyLicenseRemovalPolicy
	 */
	public $licenseRemovalPolicy;	
	
	/**
	 * @var int
	 */
	public $licenseRemovalDuration;	
	
	/**
	 * @var BorhanPlayReadyMinimumLicenseSecurityLevel
	 */
	public $minSecurityLevel;	
	
	/**
	 * @var BorhanPlayReadyRightArray
	 */
	public $rights;	
	
	
	private static $map_between_objects = array(
		'gracePeriod',
		'licenseRemovalPolicy',
		'licenseRemovalDuration',
		'minSecurityLevel',
		'rights',
	 );
		 
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			$dbObject = new PlayReadyPolicy();
			
		parent::toObject($dbObject, $skip);
					
		return $dbObject;
	}
	
	public function validatePolicy()
	{
		if(count($this->rights))
		{
			foreach ($this->rights as $right) 
			{
				if($right instanceof BorhanPlayReadyPlayRight)
				{
					$this->validatePlayRight($right);
				}
				else if($right instanceof BorhanPlayReadyCopyRight)
				{
					$this->validateCopyRight($right);
				}
			}
		}
		
		parent::validatePolicy();
	}
	
	private function validatePlayRight(BorhanPlayReadyPlayRight $right)
	{
		if(	count($right->analogVideoOutputProtectionList) && 
			in_array(BorhanPlayReadyAnalogVideoOPId::EXPLICIT_ANALOG_TV, $right->analogVideoOutputProtectionList) && 
			in_array(BorhanPlayReadyAnalogVideoOPId::BEST_EFFORT_EXPLICIT_ANALOG_TV, $right->analogVideoOutputProtectionList))
		{
			throw new BorhanAPIException(BorhanPlayReadyErrors::ANALOG_OUTPUT_PROTECTION_ID_NOT_ALLOWED, BorhanPlayReadyAnalogVideoOPId::EXPLICIT_ANALOG_TV, BorhanPlayReadyAnalogVideoOPId::BEST_EFFORT_EXPLICIT_ANALOG_TV);
		}
	}
	
	private function validateCopyRight(BorhanPlayReadyCopyRight $right)
	{
		if($right->copyCount > 0 && !count($right->copyEnablers))
		{
			throw new BorhanAPIException(BorhanPlayReadyErrors::COPY_ENABLER_TYPE_MISSING);
		}
	}
	
}

