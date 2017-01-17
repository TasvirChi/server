<?php
/**
 * @package plugins.annotation
 * @subpackage api.filters
 */
class BorhanAnnotationFilter extends BorhanAnnotationBaseFilter
{
	const CHAPTERS_PUBLIC_TAG = 'chaptering';
	
	/* (non-PHPdoc)
 	 * @see BorhanFilter::getCoreFilter()
 	 */
	protected function getCoreFilter()
	{
		return new AnnotationFilter();
	}
	
	/* (non-PHPdoc)
	 * @see BorhanRelatedFilter::validateForResponseProfile()
	 */
	public function validateForResponseProfile()
	{
		if(		!kCurrentContext::$is_admin_session
			&&	!$this->isPublicEqual)
		{
			parent::validateForResponseProfile();
		}
	}

	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, $type = null)
	{
		return parent::getTypeListResponse($pager, $responseProfile, AnnotationPlugin::getCuePointTypeCoreValue(AnnotationCuePointType::ANNOTATION));
	}
}
