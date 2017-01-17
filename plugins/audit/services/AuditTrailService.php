<?php
/**
 * Audit Trail service
 *
 * @service auditTrail
 * @package plugins.audit
 * @subpackage api.services
 */
class AuditTrailService extends BorhanBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		$this->applyPartnerFilterForClass('AuditTrail');
		$this->applyPartnerFilterForClass('AuditTrailData');
		$this->applyPartnerFilterForClass('AuditTrailConfig');
		
		if(!AuditPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, AuditPlugin::PLUGIN_NAME);
	}
	
	/**
	 * Allows you to add an audit trail object and audit trail content associated with Borhan object
	 * 
	 * @action add
	 * @param BorhanAuditTrail $auditTrail
	 * @return BorhanAuditTrail
	 * @throws AuditTrailErrors::AUDIT_TRAIL_DISABLED
	 */
	function addAction(BorhanAuditTrail $auditTrail)
	{
		$auditTrail->validatePropertyNotNull("auditObjectType");
		$auditTrail->validatePropertyNotNull("objectId");
		$auditTrail->validatePropertyNotNull("action");
		$auditTrail->validatePropertyMaxLength("description", 1000);
		
		$dbAuditTrail = $auditTrail->toInsertableObject();
		$dbAuditTrail->setPartnerId($this->getPartnerId());
		$dbAuditTrail->setStatus(AuditTrail::AUDIT_TRAIL_STATUS_READY);
		$dbAuditTrail->setContext(BorhanAuditTrailContext::CLIENT);
		
		$enabled = kAuditTrailManager::traceEnabled($this->getPartnerId(), $dbAuditTrail);
		if(!$enabled)
			throw new BorhanAPIException(AuditTrailErrors::AUDIT_TRAIL_DISABLED, $this->getPartnerId(), $dbAuditTrail->getObjectType(), $dbAuditTrail->getAction());
			
		$created = $dbAuditTrail->save();
		if(!$created)
			return null;
		
		$auditTrail = new BorhanAuditTrail();
		$auditTrail->fromObject($dbAuditTrail, $this->getResponseProfile());
		
		return $auditTrail;
	}
	
	/**
	 * Retrieve an audit trail object by id
	 * 
	 * @action get
	 * @param int $id 
	 * @return BorhanAuditTrail
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	function getAction($id)
	{
		$dbAuditTrail = AuditTrailPeer::retrieveByPK( $id );
		
		if(!$dbAuditTrail)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);
			
		$auditTrail = new BorhanAuditTrail();
		$auditTrail->fromObject($dbAuditTrail, $this->getResponseProfile());
		
		return $auditTrail;
	}

		/**
	 * List audit trail objects by filter and pager
	 * 
	 * @action list
	 * @param BorhanAuditTrailFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanAuditTrailListResponse
	 */
	function listAction(BorhanAuditTrailFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanAuditTrailFilter;
			
		if (!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
}
