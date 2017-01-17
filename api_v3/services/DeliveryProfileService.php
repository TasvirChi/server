<?php

/**
 * delivery service is used to control delivery objects
 *
 * @service deliveryProfile
 * @package api
 * @subpackage services
 */
class DeliveryProfileService extends BorhanBaseService
{
	
	/**
	 * Add new delivery.
	 *
	 * @action add
	 * @param BorhanDeliveryProfile $delivery
	 * @return BorhanDeliveryProfile
	 */
	function addAction(BorhanDeliveryProfile $delivery)
	{
		$dbBorhanDelivery = $delivery->toInsertableObject();
		$dbBorhanDelivery->setPartnerId($this->getPartnerId());
		$dbBorhanDelivery->setParentId(0);
		$dbBorhanDelivery->save();
		
		$delivery = BorhanDeliveryProfileFactory::getDeliveryProfileInstanceByType($dbBorhanDelivery->getType());
		$delivery->fromObject($dbBorhanDelivery, $this->getResponseProfile());
		return $delivery;
	}
	
	/**
	 * Update exisiting delivery
	 *
	 * @action update
	 * @param string $id
	 * @param BorhanDeliveryProfile $delivery
	 * @return BorhanDeliveryProfile
	 */
	function updateAction( $id , BorhanDeliveryProfile $delivery )
	{
		DeliveryProfilePeer::setUseCriteriaFilter(false);
		$dbDelivery = DeliveryProfilePeer::retrieveByPK($id);
		DeliveryProfilePeer::setUseCriteriaFilter(true);
		if (!$dbDelivery)
			throw new BorhanAPIException(BorhanErrors::DELIVERY_ID_NOT_FOUND, $id);
		
		// Don't allow to update default delivery profiles from the outside
		if($dbDelivery->getIsDefault())
			throw new BorhanAPIException(BorhanErrors::DELIVERY_UPDATE_ISNT_ALLOWED, $id);
		
		$delivery->toUpdatableObject($dbDelivery);
		$dbDelivery->save();
		
		$delivery = BorhanDeliveryProfileFactory::getDeliveryProfileInstanceByType($dbDelivery->getType());
		$delivery->fromObject($dbDelivery, $this->getResponseProfile());
		return $delivery;
	}
	
	/**
	* Get delivery by id
	*
	* @action get
	* @param string $id
	* @return BorhanDeliveryProfile
	*/
	function getAction( $id )
	{
		DeliveryProfilePeer::setUseCriteriaFilter(false);
		$dbDelivery = DeliveryProfilePeer::retrieveByPK($id);
		DeliveryProfilePeer::setUseCriteriaFilter(true);
		
		if (!$dbDelivery)
			throw new BorhanAPIException(BorhanErrors::DELIVERY_ID_NOT_FOUND, $id);
			
		$delivery = BorhanDeliveryProfileFactory::getDeliveryProfileInstanceByType($dbDelivery->getType());
		$delivery->fromObject($dbDelivery, $this->getResponseProfile());
		return $delivery;
	}
	
	/**
	* Add delivery based on existing delivery.
	* Must provide valid sourceDeliveryId
	*
	* @action clone
	* @param int $deliveryId
	* @return BorhanDeliveryProfile
	*/
	function cloneAction( $deliveryId )
	{
		$dbDelivery = DeliveryProfilePeer::retrieveByPK( $deliveryId );
		
		if ( ! $dbDelivery )
			throw new BorhanAPIException ( APIErrors::DELIVERY_ID_NOT_FOUND , $deliveryId );
		
		$className = get_class($dbDelivery);
		$class = new ReflectionClass($className);
		$dbBorhanDelivery = $class->newInstanceArgs(array());
		$dbBorhanDelivery = $dbDelivery->cloneToNew ( $dbBorhanDelivery );
		
		$delivery = BorhanDeliveryProfileFactory::getDeliveryProfileInstanceByType($dbBorhanDelivery->getType());
		$delivery->fromObject($dbBorhanDelivery, $this->getResponseProfile());
		return $delivery;
	}
	
	/**
	* Retrieve a list of available delivery depends on the filter given
	*
	* @action list
	* @param BorhanDeliveryProfileFilter $filter
	* @param BorhanFilterPager $pager
	* @return BorhanDeliveryProfileListResponse
	*/
	function listAction( BorhanDeliveryProfileFilter $filter=null , BorhanFilterPager $pager=null)
	{
		if (!$filter)
			$filter = new BorhanDeliveryProfileFilter();

		if (!$pager)
			$pager = new BorhanFilterPager();
			
		$delivery = new DeliveryProfileFilter();
		$filter->toObject($delivery);

		DeliveryProfilePeer::setUseCriteriaFilter(false);
		
		$c = new Criteria();
		$c->add(DeliveryProfilePeer::PARTNER_ID, array(0, kCurrentContext::getCurrentPartnerId()), Criteria::IN);
		$delivery->attachToCriteria($c);
		
		$totalCount = DeliveryProfilePeer::doCount($c);
		
		$pager->attachToCriteria($c);
		$dbList = DeliveryProfilePeer::doSelect($c);
		
		DeliveryProfilePeer::setUseCriteriaFilter(true);
		
		$objects = BorhanDeliveryProfileArray::fromDbArray($dbList, $this->getResponseProfile());
		$response = new BorhanDeliveryProfileListResponse();
		$response->objects = $objects;
		$response->totalCount = $totalCount;
		return $response;    
	}
}

