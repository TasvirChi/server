<?php
/**
 * @package plugins.pushNotification
 */

class PushNotificationPlugin extends BorhanPlugin implements IBorhanPermissions, IBorhanPending, IBorhanObjectLoader, IBorhanEnumerator, IBorhanApplicationTranslations, IBorhanServices
{
    const PLUGIN_NAME = 'pushNotification';

    const EVENT_NOTIFICATION_PLUGIN_NAME = 'eventNotification';
    const EVENT_NOTIFICATION_PLUGIN_VERSION_MAJOR = 1;
    const EVENT_NOTIFICATION_PLUGIN_VERSION_MINOR = 0;
    const EVENT_NOTIFICATION_PLUGIN_VERSION_BUILD = 0;

    /* (non-PHPdoc)
     * @see IBorhanPlugin::getPluginName()
     */
    public static function getPluginName()
    {
        return self::PLUGIN_NAME;
    }
    
    /* (non-PHPdoc)
     * @see IBorhanPermissions::isAllowedPartner()
     */
    public static function isAllowedPartner($partnerId)
    {
        $partner = PartnerPeer::retrieveByPK($partnerId);
        if ($partner)
        {
            // check that both the push plugin and the event notification plugin are enabled
            return $partner->getPluginEnabled(self::PLUGIN_NAME) && EventNotificationPlugin::isAllowedPartner($partnerId);
        }
        return false;
    }
    
    /* (non-PHPdoc)
     * @see IBorhanEnumerator::getEnums()
     */
    public static function getEnums($baseEnumName = null)
    {
        if(is_null($baseEnumName))
            return array('PushNotificationTemplateType');
    
        if($baseEnumName == 'EventNotificationTemplateType')
            return array('PushNotificationTemplateType');
        	
        return array();
    }   
    
    /* (non-PHPdoc)
     * @see IBorhanObjectLoader::loadObject()
     */
    public static function loadObject($baseClass, $enumValue, array $constructorArgs = null)
    {

        $class = self::getObjectClass($baseClass, $enumValue);
        if($class)
        {
            if(is_array($constructorArgs))
            {
                $reflect = new ReflectionClass($class);
                return $reflect->newInstanceArgs($constructorArgs);
            }
            	
            return new $class();
        }

        return null;
    }
        
    /* (non-PHPdoc)
     * @see IBorhanObjectLoader::getObjectClass()
     */
    public static function getObjectClass($baseClass, $enumValue)
    {
        if ($baseClass == 'EventNotificationTemplate' && $enumValue == self::getPushNotificationTemplateTypeCoreValue(PushNotificationTemplateType::PUSH))
            return 'PushNotificationTemplate';
    
        if ($baseClass == 'BorhanEventNotificationTemplate' && $enumValue == self::getPushNotificationTemplateTypeCoreValue(PushNotificationTemplateType::PUSH))
            return 'BorhanPushNotificationTemplate';
                          
        if($baseClass == 'Borhan_Client_EventNotification_Type_EventNotificationTemplate' && $enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::PUSH)
            return 'Borhan_Client_PushNotification_Type_PushNotificationTemplate';
        
        if($baseClass == 'Form_EventNotificationTemplateConfiguration' && $enumValue == Borhan_Client_EventNotification_Enum_EventNotificationTemplateType::PUSH)
            return 'Form_PushNotificationTemplateConfiguration';     
           
        return null;
    }  
    

    /* (non-PHPdoc)
     * @see IBorhanPending::dependsOn()
     */
    public static function dependsOn()
    {
        $minVersion = new BorhanVersion(
            self::EVENT_NOTIFICATION_PLUGIN_VERSION_MAJOR,
            self::EVENT_NOTIFICATION_PLUGIN_VERSION_MINOR,
            self::EVENT_NOTIFICATION_PLUGIN_VERSION_BUILD
        );
        $dependency = new BorhanDependency(self::EVENT_NOTIFICATION_PLUGIN_NAME, $minVersion);
    
        return array($dependency);
    }
    
    /**
     * @return int id of dynamic enum in the DB.
     */
    public static function getPushNotificationTemplateTypeCoreValue($valueName)
    {
        $value = self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
        return kPluginableEnumsManager::apiToCore('EventNotificationTemplateType', $value);
    }
    
    /**
     * @return string external API value of dynamic enum.
     */
    public static function getApiValue($valueName)
    {
        return self::getPluginName() . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $valueName;
    }    
    
    /* (non-PHPdoc)
     * @see IBorhanApplicationTranslations::getTranslations()
     */
    public static function getTranslations($locale)
    {
        $array = array();
    
        $langFilePath = __DIR__ . "/config/lang/$locale.php";
        if(!file_exists($langFilePath))
        {
            $default = 'en';
            $langFilePath = __DIR__ . "/config/lang/$default.php";
        }
    
        $array = include($langFilePath);
    
        return array($locale => $array);
    }
    
    /* (non-PHPdoc)
     * @see IBorhanServices::getServicesMap()
     */
    public static function getServicesMap()
    {
        return array(
            'pushNotificationTemplate' => 'PushNotificationTemplateService',
        );
    }    
}