<?php
/**
 * @package api
 * @subpackage enum
 */
class BorhanObjectFeatureType extends BorhanDynamicEnum implements ObjectFeatureType
{
	/* (non-PHPdoc)
	 * @see IBorhanDynamicEnum::getEnumClass()
	 */
	public static function getEnumClass() 
	{
		return 'ObjectFeatureType';
	}

	/* (non-PHPdoc)
	 * @see IBorhanEnum::getDescriptions()
	 */
	public static function getDescriptions() {
		// TODO Auto-generated method stub
		
	}

	
}