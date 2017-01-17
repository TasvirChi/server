<?php
/**
 * @package plugins.captions
 * @subpackage model.enum
 */
class CaptionObjectFeatureType implements IBorhanPluginEnum, ObjectFeatureType
{
	const CAPTIONS = 'Captions';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() 
	{
		return array
		(
			'CAPTIONS' => self::CAPTIONS,
		);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}
}