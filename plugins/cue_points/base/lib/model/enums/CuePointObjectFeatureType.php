<?php
/**
 * @package plugins.cuePoint
 * @subpackage model.enum
 */
class CuePointObjectFeatureType implements IBorhanPluginEnum, ObjectFeatureType
{
	const CUE_POINT = 'CuePoint';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() 
	{
		return array
		(
			'CUE_POINT' => self::CUE_POINT,
		);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}
}