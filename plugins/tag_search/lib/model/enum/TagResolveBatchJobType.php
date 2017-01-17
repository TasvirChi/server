<?php
/**
 * @package plugins.tag_search
 *  @subpackage model.enum
 */
class TagResolveBatchJobType implements IBorhanPluginEnum, BatchJobType
{
	const TAG_RESOLVE = 'TagResolve';
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() {
		return array(
			'TAG_RESOLVE' => self::TAG_RESOLVE,
		);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}


}