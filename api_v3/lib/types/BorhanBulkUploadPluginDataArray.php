<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanBulkUploadPluginDataArray extends BorhanTypedArray
{
	public function __construct()
	{
		return parent::__construct("BorhanBulkUploadPluginData");
	}
	
	public function toValuesArray()
	{
		$ret = array();
		foreach($this as $pluginData)
			$ret[$pluginData->field] = $pluginData->value;
			
		return $ret;
	}
}
?>