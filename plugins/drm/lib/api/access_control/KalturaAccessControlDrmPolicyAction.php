<?php
/**
 * @package plugins.drm
 * @subpackage api.objects
 */
class BorhanAccessControlDrmPolicyAction extends BorhanRuleAction
{
	/**
	 * Drm policy id
	 * 
	 * @var int
	 */
	public $policyId;

	private static $mapBetweenObjects = array
	(
		'policyId',
	);

	/**
	 * Init object type
	 */
	public function __construct() 
	{
		$this->type = DrmAccessControlActionType::DRM_POLICY;
	}
	
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanObject::toObject()
	 */
	public function toObject($dbObject = null, $skip = array())
	{
		if(!$dbObject)
			$dbObject = new kAccessControlDrmPolicyAction();
			
		return parent::toObject($dbObject, $skip);
	}
}
