<?php
/**
 * 
 * @service drmPolicy
 * @package plugins.drm
 * @subpackage api.services
 */
class DrmPolicyService extends BorhanBaseService
{	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		if (!DrmPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
			
		$this->applyPartnerFilterForClass('DrmPolicy');
	}
	
	/**
	 * Allows you to add a new DrmPolicy object
	 * 
	 * @action add
	 * @param BorhanDrmPolicy $drmPolicy
	 * @return BorhanDrmPolicy
	 * 
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 */
	public function addAction(BorhanDrmPolicy $drmPolicy)
	{
		// check for required parameters
		$drmPolicy->validatePropertyNotNull('name');
		$drmPolicy->validatePropertyNotNull('status');
		$drmPolicy->validatePropertyNotNull('provider');
		$drmPolicy->validatePropertyNotNull('systemName');
		$drmPolicy->validatePropertyNotNull('scenario');
		$drmPolicy->validatePropertyNotNull('partnerId');
		
		// validate values
		$drmPolicy->validatePolicy();
						
		if (!PartnerPeer::retrieveByPK($drmPolicy->partnerId)) {
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $drmPolicy->partnerId);
		}
		
		if (!DrmPlugin::isAllowedPartner($drmPolicy->partnerId))
		{
			throw new BorhanAPIException(BorhanErrors::PLUGIN_NOT_AVAILABLE_FOR_PARTNER, DrmPlugin::getPluginName(), $drmPolicy->partnerId);
		}

		if(DrmPolicyPeer::retrieveBySystemName($drmPolicy->systemName))
		{
			throw new BorhanAPIException(DrmErrors::DRM_POLICY_DUPLICATE_SYSTEM_NAME, $drmPolicy->systemName);
		}
				
		// save in database
		$dbDrmPolicy = $drmPolicy->toInsertableObject();
		$dbDrmPolicy->save();
		
		// return the saved object
		$drmPolicy = BorhanDrmPolicy::getInstanceByType($dbDrmPolicy->getProvider());
		$drmPolicy->fromObject($dbDrmPolicy, $this->getResponseProfile());
		return $drmPolicy;
		
	}
	
	/**
	 * Retrieve a BorhanDrmPolicy object by ID
	 * 
	 * @action get
	 * @param int $drmPolicyId 
	 * @return BorhanDrmPolicy
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function getAction($drmPolicyId)
	{
		$dbDrmPolicy = DrmPolicyPeer::retrieveByPK($drmPolicyId);
		
		if (!$dbDrmPolicy) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmPolicyId);
		}
			
		$drmPolicy = BorhanDrmPolicy::getInstanceByType($dbDrmPolicy->getProvider());
		$drmPolicy->fromObject($dbDrmPolicy, $this->getResponseProfile());
		
		return $drmPolicy;
	}
	

	/**
	 * Update an existing BorhanDrmPolicy object
	 * 
	 * @action update
	 * @param int $drmPolicyId
	 * @param BorhanDrmPolicy $drmPolicy
	 * @return BorhanDrmPolicy
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */	
	public function updateAction($drmPolicyId, BorhanDrmPolicy $drmPolicy)
	{
		$dbDrmPolicy = DrmPolicyPeer::retrieveByPK($drmPolicyId);
		
		if (!$dbDrmPolicy) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmPolicyId);
		}
		
		$drmPolicy->validatePolicy();
						
		$dbDrmPolicy = $drmPolicy->toUpdatableObject($dbDrmPolicy);
		$dbDrmPolicy->save();
	
		$drmPolicy = BorhanDrmPolicy::getInstanceByType($dbDrmPolicy->getProvider());
		$drmPolicy->fromObject($dbDrmPolicy, $this->getResponseProfile());
		
		return $drmPolicy;
	}

	/**
	 * Mark the BorhanDrmPolicy object as deleted
	 * 
	 * @action delete
	 * @param int $drmPolicyId 
	 * @return BorhanDrmPolicy
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function deleteAction($drmPolicyId)
	{
		$dbDrmPolicy = DrmPolicyPeer::retrieveByPK($drmPolicyId);
		
		if (!$dbDrmPolicy) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmPolicyId);
		}

		$dbDrmPolicy->setStatus(DrmPolicyStatus::DELETED);
		$dbDrmPolicy->save();
			
		$drmPolicy = BorhanDrmPolicy::getInstanceByType($dbDrmPolicy->getProvider());
		$drmPolicy->fromObject($dbDrmPolicy, $this->getResponseProfile());
		
		return $drmPolicy;
	}
	
	/**
	 * List BorhanDrmPolicy objects
	 * 
	 * @action list
	 * @param BorhanDrmPolicyFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDrmPolicyListResponse
	 */
	public function listAction(BorhanDrmPolicyFilter  $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanDrmPolicyFilter();
			
		$drmPolicyFilter = $filter->toObject();

		$c = new Criteria();
		$drmPolicyFilter->attachToCriteria($c);
		$count = DrmPolicyPeer::doCount($c);		
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria ( $c );
		$list = DrmPolicyPeer::doSelect($c);
		
		$response = new BorhanDrmPolicyListResponse();
		$response->objects = BorhanDrmPolicyArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
		
		return $response;
	}

}
