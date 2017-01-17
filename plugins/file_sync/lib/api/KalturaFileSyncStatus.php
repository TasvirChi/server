<?php
/**
 * @package plugins.fileSync
 * @subpackage api.enum
 */
class BorhanFileSyncStatus extends BorhanEnum 
{
	const ERROR = -1;
	const PENDING = 1;
	const READY = 2;
	const DELETED = 3;
	const PURGED = 4;
}