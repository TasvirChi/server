<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanStorageProfileFilter extends BorhanStorageProfileBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new StorageProfileFilter();
	}
}
