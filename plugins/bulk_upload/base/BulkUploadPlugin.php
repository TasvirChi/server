<?php

/**
 * This plugin replaces the deprecated BulkUploadService. It includes a service for uploading entries, categories, users and categoryUsers in bulks.
 *@package plugins.bulkUpload
 *
 */
class BulkUploadPlugin extends BorhanPlugin implements IBorhanServices, IBorhanEventConsumers
{
    const PLUGIN_NAME = "bulkUpload";

	/* (non-PHPdoc)
     * @see IBorhanPlugin::getPluginName()
     */
    public static function getPluginName ()
    {
        return self::PLUGIN_NAME;
        
    }

    public static function getServicesMap()
	{
		$map = array(
			'bulk' => 'BulkService',
		);
		return $map;
	}

	/* (non-PHPdoc)
     * @see IBorhanEventConsumers::getEventConsumers()
     */
    public static function getEventConsumers ()
    {
        return array('kBatchJobLogManager');
    }
}
