<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanUploadTokenFilter extends BorhanUploadTokenBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new UploadTokenFilter();
	}
}
