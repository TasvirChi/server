<?php

/**
 * Represents the Bulk service input for filter bulk upload
 * @package plugins.bulkUploadFilter
 * @subpackage api.objects
 */
class BorhanBulkServiceFilterData extends BorhanBulkServiceData
{	
	/**
	 * Filter for extracting the objects list to upload 
	 * @var BorhanFilter
	 */
	public $filter;

	/**
	 * Template object for new object creation
	 * @var BorhanObject
	 */
	public $templateObject;
	
	public function getType ()
	{
	    return kPluginableEnumsManager::apiToCore("BulkUploadType", BulkUploadFilterPlugin::getApiValue(BulkUploadFilterType::FILTER));
	}
	
	public function toBulkUploadJobData(BorhanBulkUploadJobData $jobData)
	{
		$jobData->filter = $this->filter;
		$jobData->templateObject = $this->templateObject;
	}
}