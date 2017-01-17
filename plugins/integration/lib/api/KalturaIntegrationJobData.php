<?php
/**
 * @package plugins.integration
 * @subpackage api.objects
 */
class BorhanIntegrationJobData extends BorhanJobData
{
	/**
	 * @var string
	 * @readonly
	 */
	public $callbackNotificationUrl;
	
	/**
	 * @var BorhanIntegrationProviderType
	 */
	public $providerType;

	/**
	 * Additional data that relevant for the provider only
	 * @var BorhanIntegrationJobProviderData
	 */
	public $providerData;

	/**
	 * @var BorhanIntegrationTriggerType
	 */
	public $triggerType;

	/**
	 * Additional data that relevant for the trigger only
	 * @var BorhanIntegrationJobTriggerData
	 */
	public $triggerData;
	
	private static $map_between_objects = array
	(
		"callbackNotificationUrl",
		"providerType" ,
		"triggerType" ,
	);

	/* (non-PHPdoc)
	 * @see BorhanObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::fromObject($srcObj)
	 */
	public function doFromObject($sourceObject, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($sourceObject, $responseProfile);
		
		$providerType = $sourceObject->getProviderType();
		$this->providerData = BorhanPluginManager::loadObject('BorhanIntegrationJobProviderData', $providerType);
		$providerData = $sourceObject->getProviderData();
		if($this->providerData && $providerData && $providerData instanceof kIntegrationJobProviderData)
			$this->providerData->fromObject($providerData);
			
		$triggerType = $sourceObject->getTriggerType();
		$this->triggerData = BorhanPluginManager::loadObject('BorhanIntegrationJobTriggerData', $triggerType);
		$triggerData = $sourceObject->getTriggerData();
		if($this->triggerData && $triggerData && $triggerData instanceof kIntegrationJobTriggerData)
			$this->triggerData->fromObject($triggerData);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject($object_to_fill, $props_to_skip)
	 */
	public function toObject($object = null, $skip = array())
	{
		if(is_null($object))
		{
			$object = new kIntegrationJobData();
		} 
		$object = parent::toObject($object, $skip);
				
		if($this->providerType && $this->providerData && $this->providerData instanceof BorhanIntegrationJobProviderData)
		{
			$providerData = BorhanPluginManager::loadObject('kIntegrationJobProviderData', $this->providerType);
			if($providerData)
			{
				$providerData = $this->providerData->toObject($providerData);
				$object->setProviderData($providerData);
			}
		}
		
		if($this->triggerType && $this->triggerData && $this->triggerData instanceof BorhanIntegrationJobTriggerData)
		{
			$triggerData = BorhanPluginManager::loadObject('kIntegrationJobTriggerData', $this->triggerType);
			if($triggerData)
			{
				$triggerData = $this->triggerData->toObject($triggerData);
				$object->setTriggerData($triggerData);
			}
		}
		
		return $object;
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::validateForUsage($sourceObject, $propertiesToSkip)
	 */
	public function validateForUsage($sourceObject, $propertiesToSkip = array())
	{
		parent::validateForUsage($sourceObject, $propertiesToSkip);
		
		$this->validatePropertyNotNull('providerType');
		$this->validatePropertyNotNull('providerData');
		$this->validatePropertyNotNull('triggerType');
		
		if ($this->triggerType != BorhanIntegrationTriggerType::MANUAL)
			$this->validatePropertyNotNull('triggerData');
	}
	
	/* (non-PHPdoc)
	 * @see BorhanJobData::toSubType()
	 */
	public function toSubType($subType)
	{
		return kPluginableEnumsManager::apiToCore('IntegrationProviderType', $subType);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanJobData::fromSubType()
	 */
	public function fromSubType($subType)
	{
		return kPluginableEnumsManager::coreToApi('IntegrationProviderType', $subType);
	}
}
