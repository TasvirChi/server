<?php
/**
 * @package plugins.tag_search
 *  @subpackage model.enum
 */
class IndexTagsByPrivacyContextJobType implements IBorhanPluginEnum, BatchJobType
{
	const INDEX_TAGS = 'IndexTagsByPrivacyContext';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() {
		return array(
			'INDEX_TAGS' => self::INDEX_TAGS,
		);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}


}