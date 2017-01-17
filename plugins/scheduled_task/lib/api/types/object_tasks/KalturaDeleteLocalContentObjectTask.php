<?php

/**
 * @package plugins.scheduledTask
 * @subpackage api.objects.objectTasks
 */
class BorhanDeleteLocalContentObjectTask extends BorhanObjectTask
{
	public function __construct()
	{
		$this->type = ObjectTaskType::DELETE_LOCAL_CONTENT;
	}
}