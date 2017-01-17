<?php
/**
 * This class represents object-specific data passed to the 
 * bulk upload job.
 * @abstract
 * @package plugins.bulkUpload
 * @subpackage api.objects
 *
 */
abstract class BorhanBulkServiceData extends BorhanObject
{
	abstract public function getType ();
	abstract public function toBulkUploadJobData(BorhanBulkUploadJobData $jobData);
}