<?php
/**
 * @package plugins.kontiki
 *  @subpackage model.enum
 */
class KontikiStorageProfileProtocol implements IBorhanPluginEnum, StorageProfileProtocol
{
	const KONTIKI = 'KONTIKI';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() {
		return array('KONTIKI' => self::KONTIKI);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}

	
}