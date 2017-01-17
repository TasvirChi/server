<?php

/**
 * @package plugins.scheduledTask
 * @subpackage lib.objectFilterEngine
 */
class KObjectFilterBaseEntryEngine extends KObjectFilterEngineBase
{
	/**
	 * @param BorhanFilter $filter
	 * @return array
	 */
	public function query(BorhanFilter $filter)
	{
		return $this->_client->baseEntry->listAction($filter, $this->getPager());
	}
}