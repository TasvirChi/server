<?php
/**
 * @package plugins.dropFolder
 * @subpackage api.filters
 */
class BorhanDropFolderFileFilter extends BorhanDropFolderFileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new DropFolderFileFilter();
	}
}
