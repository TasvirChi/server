<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanPartnerFilter extends BorhanPartnerBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new partnerFilter();
	}
}
