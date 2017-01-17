<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanStorageServePriority extends BorhanEnum
{	  				
	const BORHAN_ONLY = 1;
	const BORHAN_FIRST = 2;
	const EXTERNAL_FIRST = 3;
	const EXTERNAL_ONLY = 4;
}
