<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanHTMLPurifierBehaviourType extends BorhanDynamicEnum implements HTMLPurifierBehaviourType
{
	/**
	 * @return string
	 */
	public static function getEnumClass()
	{
		return 'HTMLPurifierBehaviourType';
	}
}
