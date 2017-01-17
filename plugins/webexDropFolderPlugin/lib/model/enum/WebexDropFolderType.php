<?php
/**
 * @package plugins.webexDropFolder
 *  @subpackage model.enum
 */
class WebexDropFolderType implements IBorhanPluginEnum, DropFolderType
{
	const WEBEX = 'WEBEX';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() {
		return array('WEBEX' => self::WEBEX);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}
}
