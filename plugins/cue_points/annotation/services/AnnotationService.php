<?php
/**
 * Annotation service - Video Annotation
 *
 * @service annotation
 * @package plugins.annotation
 * @subpackage api.services
 * @throws BorhanErrors::SERVICE_FORBIDDEN
 * @deprecated use cuePoint service instead
 */
class AnnotationService extends CuePointService
{
	/**
	 * @return CuePointType or null to limit the service type
	 */
	protected function getCuePointType()
	{
		return AnnotationPlugin::getCuePointTypeCoreValue(AnnotationCuePointType::ANNOTATION);
	}

	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		if(!AnnotationPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, AnnotationPlugin::PLUGIN_NAME);
	}

	/**
	 * Allows you to add an annotation object associated with an entry
	 *
	 * @action add
	 * @param BorhanAnnotation $annotation
	 * @return BorhanAnnotation
	 */
	function addAction(BorhanCuePoint $annotation)
	{
		return parent::addAction($annotation);
	}

	/**
	 * Update annotation by id
	 *
	 * @action update
	 * @param string $id
	 * @param BorhanAnnotation $annotation
	 * @return BorhanAnnotation
	 * @throws BorhanCuePointErrors::INVALID_CUE_POINT_ID
	 */
	function updateAction($id, BorhanCuePoint $annotation)
	{
		return parent::updateAction($id, $annotation);
	}
	
	/**
	* List annotation objects by filter and pager
	*
	* @action list
	* @param BorhanAnnotationFilter $filter
	* @param BorhanFilterPager $pager
	* @return BorhanAnnotationListResponse
	*/
	function listAction(BorhanCuePointFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if(!$filter)
			$filter = new BorhanAnnotationFilter();
		
		$filter->cuePointTypeEqual = AnnotationPlugin::getApiValue(AnnotationCuePointType::ANNOTATION);
		
		$list = parent::listAction($filter, $pager);
		$ret = new BorhanAnnotationListResponse();
		$ret->objects = $list->objects;
		$ret->totalCount = $list->totalCount;
		
		return $ret;
	}
}
