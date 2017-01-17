<?php
/**
 * @package Core
 * @subpackage AccessControl
 */
class kGeoCoderManager
{
	/**
	 * @param int $type of enum geoCoderType
	 * @return kGeoCoder
	 */
	public static function getGeoCoder($type = null)
	{
		if(!$type || $type == geoCoderType::BORHAN)
			return new myIPGeocoder();
			
		return BorhanPluginManager::loadObject('kGeoCoder', $type);
	}
}