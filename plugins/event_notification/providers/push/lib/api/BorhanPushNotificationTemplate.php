<?php
/**
 * @package plugins.pushNotification
 * @subpackage api.objects
*/
class BorhanPushNotificationTemplate extends BorhanEventNotificationTemplate
{
    /**
     * Borhan API object type
     * @var string
     */
    public $apiObjectType;
    
    /**
     * Borhan Object format
     * @var BorhanResponseType
     */    
    public $objectFormat;
    
    /**
     * Borhan response-profile id
     * @var int
     */    
    public $responseProfileId;
    

    private static $map_between_objects = array('apiObjectType', 'objectFormat', 'responseProfileId');
    
    public function __construct()
    {
        $this->type = PushNotificationPlugin::getApiValue(PushNotificationTemplateType::PUSH);
    }
    
    /* (non-PHPdoc)
     * @see BorhanObject::getMapBetweenObjects()
     */
    public function getMapBetweenObjects()
    {
        return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
    }    

    /* (non-PHPdoc)
     * @see BorhanObject::validateForUpdate()
     */
    public function validateForUpdate($sourceObject, $propertiesToSkip = array())
    {
        $propertiesToSkip[] = 'type';
        return parent::validateForUpdate($sourceObject, $propertiesToSkip);
    }
    
    /* (non-PHPdoc)
     * @see BorhanObject::toObject()
     */
    public function toObject($dbObject = null, $propertiesToSkip = array())
    {
        if(is_null($dbObject))
            $dbObject = new PushNotificationTemplate();
        	
        return parent::toObject($dbObject, $propertiesToSkip);
    }
}