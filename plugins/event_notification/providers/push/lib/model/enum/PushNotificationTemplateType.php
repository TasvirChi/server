<?php
/**
 * @package plugins.pushNotification
 * @subpackage model.enum
 */
class PushNotificationTemplateType implements IBorhanPluginEnum, EventNotificationTemplateType
{
	const PUSH = 'Push';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues()
	{
		return array(
			'PUSH' => self::PUSH,
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() 
	{
		return array(
			self::PUSH => 'Push event notification',
		);
	}
}
