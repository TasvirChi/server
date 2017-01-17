<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 */
class BorhanPreviewRestriction extends BorhanSessionRestriction 
{
	/**
	 * The preview restriction length 
	 * 
	 * @var int
	 */
	public $previewLength;
	
	private static $mapBetweenObjects = array
	(
		"previewLength",
	);
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanBaseRestriction::toRule()
	 */
	public function toRule(BorhanRestrictionArray $restrictions)
	{
		// Preview restriction became a rule action, it's not a rule.
		return null;
	}
}