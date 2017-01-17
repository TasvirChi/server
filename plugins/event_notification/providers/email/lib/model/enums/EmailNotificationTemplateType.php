<?php
/**
 * @package plugins.emailNotification
 * @subpackage model.enum
 */
class EmailNotificationTemplateType implements IBorhanPluginEnum, EventNotificationTemplateType
{
	const EMAIL = 'Email';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues()
	{
		return array(
			'EMAIL' => self::EMAIL,
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() 
	{
		return array(
			self::EMAIL => 'Email event notification',
		);
	}
}
