<?php
/**
 * @package plugins.audit
 * @subpackage api.objects
 */
class BorhanAuditTrailChangeInfo extends BorhanAuditTrailInfo
{
	/**
	 * @var BorhanAuditTrailChangeItemArray
	 */
	public $changedItems;

	/**
	 * @param kAuditTrailChangeInfo $dbAuditTrail
	 * @param array $propsToSkip
	 * @return kAuditTrailInfo
	 */
	public function toObject($auditTrailInfo = null, $propsToSkip = array())
	{
		if(is_null($auditTrailInfo))
			$auditTrailInfo = new kAuditTrailChangeInfo();
			
		$auditTrailInfo = parent::toObject($auditTrailInfo, $propsToSkip);
		$auditTrailInfo->setChangedItems($this->changedItems->toObjectArray());
		
		return $auditTrailInfo;
	}

	/**
	 * @param kAuditTrailChangeInfo $auditTrailInfo
	 */
	public function doFromObject($auditTrailInfo, BorhanDetachedResponseProfile $responseProfile = null)
	{
		parent::doFromObject($auditTrailInfo, $responseProfile);
		
		if($this->shouldGet('changedItems', $responseProfile))
			$this->changedItems = BorhanAuditTrailChangeItemArray::fromDbArray($auditTrailInfo->getChangedItems());
	}
}
