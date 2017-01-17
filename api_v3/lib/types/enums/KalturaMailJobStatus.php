<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanMailJobStatus extends BorhanEnum
{
	const PENDING = 1;
	const SENT = 2;
	const ERROR = 3;
	const QUEUED = 4;
}