<?php
/**
 * @package plugins.metadata
 *  @subpackage model.enum
 */
class MetadataObjectFeatureType implements IBorhanPluginEnum, ObjectFeatureType
{
	const CUSTOM_DATA = 'CustomData';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() 
	{
		return array
		(
			'CUSTOM_DATA' => self::CUSTOM_DATA,
		);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}
}