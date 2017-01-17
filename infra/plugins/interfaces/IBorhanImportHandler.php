<?php
/**
 * Enable the plugin to handle bulk upload additional data
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanImportHandler extends IBorhanBase
{
	/**
	 * This method makes an intermediate change to the imported file or its related data under certain conditions.
	 * @param KCurlHeaderResponse $curlInfo
	 * @param BorhanImportJobData $importData
	 * @param Object $params
	 */
	public static function handleImportContent($curlInfo, $importData, $params);	
}