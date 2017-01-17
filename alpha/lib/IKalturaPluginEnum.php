<?php
/**
 * Marker interface
 *
 * @package Core
 * @subpackage enum
 */ 
interface IBorhanPluginEnum 
{
	/**
	 * @return array
	 */
	public static function getAdditionalValues();
	
	/**
	* @return array
	*/
	public static function getAdditionalDescriptions();
}
