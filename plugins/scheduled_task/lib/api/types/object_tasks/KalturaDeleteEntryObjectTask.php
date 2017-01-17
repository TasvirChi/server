<?php

/**
 * @package plugins.scheduledTask
 * @subpackage api.objects.objectTasks
 */
class BorhanDeleteEntryObjectTask extends BorhanObjectTask
{
	public function __construct()
	{
		$this->type = ObjectTaskType::DELETE_ENTRY;
	}
}