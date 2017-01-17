<?php
/**
 * @package plugins.httpNotification
 * @subpackage model.enum
 */
class HttpNotificationTemplateType implements IBorhanPluginEnum, EventNotificationTemplateType
{
	const HTTP = 'Http';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues()
	{
		return array(
			'HTTP' => self::HTTP,
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() 
	{
		return array(
			self::HTTP => 'Http event notification',
		);
	}
}
