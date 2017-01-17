<?php
/**
 * @package plugins.FeedDropFolder
 * @subpackage model.enum
 */
class FeedDropFolderType implements IBorhanPluginEnum, DropFolderType
{
	const FEED = 'FEED';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() {
		return array('FEED' => self::FEED);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}
}