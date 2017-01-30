<?php
/**
 * 
 * @service drmDevice
 * @package plugins.drm
 * @subpackage api.services
 */
class DrmDeviceService extends BorhanBaseService
{	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		if (!DrmPlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
			
		$this->applyPartnerFilterForClass('DrmDevice');
	}
	
	/**
	 * Allows you to add a new DrmDevice in Pending status
	 * 
	 * @action add
	 * @param BorhanDrmDevice $drmDevice
	 * @return BorhanDrmDevice
	 * 
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 */
	public function addAction(BorhanDrmDevice $drmDevice)
	{
		// check for required parameters
		$drmDevice->validatePropertyNotNull('name');
		$drmDevice->validatePropertyNotNull('provider');
		$drmDevice->validatePropertyNotNull('deviceId');
		$drmDevice->validatePropertyNotNull('partnerId');
		
		
		if (!PartnerPeer::retrieveByPK($drmDevice->partnerId)) {
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $drmDevice->partnerId);
		}
		
		if (!DrmPlugin::isAllowedPartner($drmDevice->partnerId))
		{
			throw new BorhanAPIException(BorhanErrors::PLUGIN_NOT_AVAILABLE_FOR_PARTNER, DrmPlugin::getPluginName(), $drmDevice->partnerId);
		}
				
		// save in database
		$dbDrmDevice = $drmDevice->toInsertableObject();
		$dbDrmDevice->setStatus(DrmDeviceStatus::PENDING);
		$dbDrmDevice->save();
		
		// return the saved object
		$drmDevice = BorhanDrmDevice::getInstanceByType($dbDrmDevice->getProvider());
		$drmDevice->fromObject($dbDrmDevice, $this->getResponseProfile());
		return $drmDevice;
		
	}
	
	/**
	 * Retrieve a DrmDevice object by ID
	 * 
	 * @action get
	 * @param int $drmDeviceId 
	 * @return BorhanDrmDevice
	 * 
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function getAction($drmDeviceId)
	{
		$dbDrmDevice = DrmDevicePeer::retrieveByPK($drmDeviceId);
		
		if (!$dbDrmDevice) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmDeviceId);
		}
			
		$drmDevice = BorhanDrmDevice::getInstanceByType($dbDrmDevice->getProvider());
		$drmDevice->fromObject($dbDrmDevice, $this->getResponseProfile());
		
		return $drmDevice;
	}
	

	/**
	 * Update an existing BorhanDrmDevice object
	 * 
	 * @action update
	 * @param int $drmDeviceId
	 * @param BorhanDrmDevice $drmDevice
	 * @return BorhanDrmDevice
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */	
	public function updateAction($drmDeviceId, BorhanDrmDevice $drmDevice)
	{
		$dbDrmDevice = DrmDevicePeer::retrieveByPK($drmDeviceId);
		
		if (!$dbDrmDevice) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmDeviceId);
		}
							
		$dbDrmDevice = $drmDevice->toUpdatableObject($dbDrmDevice);
		$dbDrmDevice->save();
	
		$drmDevice = BorhanDrmDevice::getInstanceByType($dbDrmDevice->getProvider());
		$drmDevice->fromObject($dbDrmDevice, $this->getResponseProfile());
		
		return $drmDevice;
	}

	/**
	 * Mark the BorhanDrmDevice object as deleted
	 * 
	 * @action delete
	 * @param int $drmDeviceId 
	 * @return BorhanDrmDevice
	 *
	 * @throws BorhanErrors::INVALID_OBJECT_ID
	 */		
	public function deleteAction($drmDeviceId)
	{
		$dbDrmDevice = DrmDevicePeer::retrieveByPK($drmDeviceId);
		
		if (!$dbDrmDevice) {
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $drmDeviceId);
		}

		$dbDrmDevice->setStatus(DrmDeviceStatus::DELETED);
		$dbDrmDevice->save();
			
		$drmDevice = BorhanDrmDevice::getInstanceByType($dbDrmDevice->getProvider());
		$drmDevice->fromObject($dbDrmDevice, $this->getResponseProfile());
		
		return $drmDevice;
	}
	
	/**
	 * List BorhanDrmDevice objects
	 * 
	 * @action list
	 * @param BorhanDrmDeviceFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanDrmDeviceListResponse
	 */
	public function listAction(BorhanDrmDeviceFilter  $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanDrmDeviceFilter();
			
		$drmDeviceFilter = $filter->toObject();

		$c = new Criteria();
		$drmDeviceFilter->attachToCriteria($c);
		$count = DrmDevicePeer::doCount($c);		
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria ( $c );
		$list = DrmDevicePeer::doSelect($c);
		
		$response = new BorhanDrmDeviceListResponse();
		$response->objects = BorhanDrmDeviceArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $count;
		
		return $response;
	}

}
