<?php
/**
 * @package plugins.attachment
 * @subpackage model.enum
 */
class AttachmentObjectFeatureType implements IBorhanPluginEnum, ObjectFeatureType
{
	const ATTACHMENT = 'Attachment';
	
	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalValues()
	 */
	public static function getAdditionalValues() 
	{
		return array
		(
			'ATTACHMENT' => self::ATTACHMENT,
		);
		
	}

	/* (non-PHPdoc)
	 * @see IBorhanPluginEnum::getAdditionalDescriptions()
	 */
	public static function getAdditionalDescriptions() {
		return array();
		
	}
}