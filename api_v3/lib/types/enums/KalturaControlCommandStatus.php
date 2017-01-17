<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanControlCommandStatus extends BorhanEnum
{
	const PENDING = 0;
	const HANDLED = 1;
	const DONE = 2;
	const FAILED = 3;
}