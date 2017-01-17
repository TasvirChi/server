<?php
/**
 * @package api
 * @subpackage filters
 */
class BorhanBulkUploadFilter extends BorhanBulkUploadBaseFilter
{
    static private $map_between_objects = array
	(
		"bulkUploadObjectTypeEqual" => "_eq_param_1",
		"bulkUploadObjectTypeIn" => "_in_param_1",
	    "uploadedOnGreaterThanOrEqual" => "_gte_created_at",
		"uploadedOnLessThanOrEqual" => "_lte_created_at",
		"uploadedOnEqual" => "_eq_created_at",
	);
	
    public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	/* (non-PHPdoc)
	 * @see BorhanFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new BatchJobLogFilter();
	}
}
