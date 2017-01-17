<?php
/**
 * @package plugins.activitiBusinessProcessNotification
 * @subpackage model.enum
 */
class ActivitiBusinessProcessProvider implements IBorhanPluginEnum, BusinessProcessProvider
{
	const ACTIVITI = 'Activiti';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues()
	{
		return array(
			'ACTIVITI' => self::ACTIVITI,
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() 
	{
		return array(
			self::ACTIVITI => 'Activiti BPM Platform',
		);
	}
}
