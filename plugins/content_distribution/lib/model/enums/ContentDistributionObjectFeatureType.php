<?php
/**
 * @package plugins.contentDistribution
 * @subpackage model.enum
 */
class ContentDistributionObjectFeatureType implements IBorhanPluginEnum, ObjectFeatureType
{
	const CONTENT_DISTRIBUTION = 'ContentDistribution';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() 
	{
		return array
		(
			'CONTENT_DISTRIBUTION' => self::CONTENT_DISTRIBUTION,
		);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}
}