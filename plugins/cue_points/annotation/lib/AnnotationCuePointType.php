<?php
/**
 * @package plugins.annotation
 * @subpackage lib.enum
 */
class AnnotationCuePointType implements IBorhanPluginEnum, CuePointType
{
	const ANNOTATION = 'Annotation';
	
	public static function getAdditionalValues()
	{
		return array(
			'ANNOTATION' => self::ANNOTATION,
		);
	}
	
	/**
	 * @return array
	 */
	public static function getAdditionalDescriptions()
	{
		return array();
	}
}
