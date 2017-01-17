<?php
/**
 * @package plugins.scheduledTask
 * @subpackage model.enum
 */ 
class ScheduledTaskBatchType implements IBorhanPluginEnum, BatchJobType
{
	const SCHEDULED_TASK = 'ScheduledTask';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues()
	{
		return array(
			'SCHEDULED_TASK' => self::SCHEDULED_TASK,
		);
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() 
	{
		return array();
	}
}
