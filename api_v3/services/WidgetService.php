<?php

/**
 * widget service for full widget management
 *
 * @service widget
 * @package api
 * @subpackage services
 */
class WidgetService extends BorhanBaseService 
{
	// use initService to add a peer to the partner filter
	/**
	 * @ignore
	 */
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('widget'); 	
	}

	/**
	 * Add new widget, can be attached to entry or kshow
	 * SourceWidget is ignored.
	 * 
	 * @action add
	 * @param BorhanWidget $widget
	 * @return BorhanWidget
	 */
	function addAction(BorhanWidget $widget)
	{
		if ($widget->sourceWidgetId === null && $widget->uiConfId === null)
		{
			throw new BorhanAPIException(BorhanErrors::SOURCE_WIDGET_OR_UICONF_REQUIRED);
		}
		
		if ($widget->sourceWidgetId !== null)
		{
			$sourceWidget = widgetPeer::retrieveByPK($widget->sourceWidgetId);
			if (!$sourceWidget) 
				throw new BorhanAPIException(BorhanErrors::SOURCE_WIDGET_NOT_FOUND, $widget->sourceWidgetId);
				
			if ($widget->uiConfId === null)
				$widget->uiConfId = $sourceWidget->getUiConfId();
		}
		
		if ($widget->uiConfId !== null)
		{
			$uiConf = uiConfPeer::retrieveByPK($widget->uiConfId);
			if (!$uiConf)
				throw new BorhanAPIException(BorhanErrors::UICONF_ID_NOT_FOUND, $widget->uiConfId);
		}
		
		if(!is_null($widget->enforceEntitlement) && $widget->enforceEntitlement == false && kEntitlementUtils::getEntitlementEnforcement())
			throw new BorhanAPIException(BorhanErrors::CANNOT_DISABLE_ENTITLEMENT_FOR_WIDGET_WHEN_ENTITLEMENT_ENFORCEMENT_ENABLE);
		
		if ($widget->entryId !== null)
		{
			$entry = entryPeer::retrieveByPK($widget->entryId);
			if (!$entry)
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $widget->entryId);
		}
		elseif ($widget->enforceEntitlement != null && $widget->enforceEntitlement == false)
		{
			throw new BorhanAPIException(BorhanErrors::CANNOT_DISABLE_ENTITLEMENT_WITH_NO_ENTRY_ID);
		}
		
		$dbWidget = $widget->toInsertableWidget();
		$dbWidget->setPartnerId($this->getPartnerId());
		$dbWidget->setSubpId($this->getPartnerId() * 100);
		$widgetId = $dbWidget->calculateId($dbWidget);

		$dbWidget->setId($widgetId);
		
		if ($entry && $entry->getType() == entryType::PLAYLIST)
			$dbWidget->setIsPlayList(true);
			
		$dbWidget->save();
		$savedWidget = widgetPeer::retrieveByPK($widgetId);
		
		$widget = new BorhanWidget(); // start from blank
		$widget->fromObject($savedWidget, $this->getResponseProfile());
		
		return $widget;
	}

	/**
 	 * Update exisiting widget
 	 * 
	 * @action update
	 * @param string $id 
	 * @param BorhanWidget $widget
	 * @return BorhanWidget
	 */	
	function updateAction( $id , BorhanWidget $widget )
	{
		$dbWidget = widgetPeer::retrieveByPK( $id );
		
		if ( ! $dbWidget )
			throw new BorhanAPIException ( APIErrors::INVALID_WIDGET_ID , $id );
		
		if(!is_null($widget->enforceEntitlement) && $widget->enforceEntitlement == false && kEntitlementUtils::getEntitlementEnforcement())
			throw new BorhanAPIException(BorhanErrors::CANNOT_DISABLE_ENTITLEMENT_FOR_WIDGET_WHEN_ENTITLEMENT_ENFORCEMENT_ENABLE);
		
		if ($widget->entryId !== null)
		{
			$entry = entryPeer::retrieveByPK($widget->entryId);
			if (!$entry)
				throw new BorhanAPIException(BorhanErrors::ENTRY_ID_NOT_FOUND, $widget->entryId);
		}
		elseif ($widget->enforceEntitlement != null && $widget->enforceEntitlement == false)
		{
			throw new BorhanAPIException(BorhanErrors::CANNOT_DISABLE_ENTITLEMENT_WITH_NO_ENTRY_ID);
		}
			
		$widgetUpdate = $widget->toUpdatableWidget();
		
		if ($entry && $entry->getType() == entryType::PLAYLIST)
		{
			$dbWidget->setIsPlayList(true);
		}
		else 
		{
			$dbWidget->setIsPlayList(false);
		}

		$allow_empty = true ; // TODO - what is the policy  ? 
		baseObjectUtils::autoFillObjectFromObject ( $widgetUpdate , $dbWidget , $allow_empty );
		
		$dbWidget->save();
		// TODO: widget in cache, should drop from cache

		$widget->fromObject($dbWidget, $this->getResponseProfile());
		
		return $widget;
	}

	/**
	 * Get widget by id
	 *  
	 * @action get
	 * @param string $id 
	 * @return BorhanWidget
	 */		
	function getAction( $id )
	{
		$dbWidget = widgetPeer::retrieveByPK( $id );

		if ( ! $dbWidget )
			throw new BorhanAPIException ( APIErrors::INVALID_WIDGET_ID , $id );
		$widget = new BorhanWidget();
		$widget->fromObject($dbWidget, $this->getResponseProfile());
		
		return $widget;
	}

	/**
	 * Add widget based on existing widget.
	 * Must provide valid sourceWidgetId
	 * 
	 * @action clone
	 * @paran BorhanWidget $widget
	 * @return BorhanWidget
	 */		
	function cloneAction( BorhanWidget $widget )
	{
		$dbWidget = widgetPeer::retrieveByPK( $widget->sourceWidgetId );
		
		if ( ! $dbWidget )
			throw new BorhanAPIException ( APIErrors::INVALID_WIDGET_ID , $widget->sourceWidgetId );

		$newWidget = widget::createWidgetFromWidget( $dbWidget , $widget->kshowId, $widget->entryId, $widget->uiConfId ,
			null , $widget->partnerData , $widget->securityType );
		if ( !$newWidget )
			throw new BorhanAPIException ( APIErrors::INVALID_KSHOW_AND_ENTRY_PAIR , $widget->kshowId, $widget->entryId );

		$widget = new BorhanWidget;
		$widget->fromObject($newWidget, $this->getResponseProfile());
		return $widget;
	}
	
	/**
	 * Retrieve a list of available widget depends on the filter given
	 * 
	 * @action list
	 * @param BorhanWidgetFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanWidgetListResponse
	 */		
	function listAction( BorhanWidgetFilter $filter=null , BorhanFilterPager $pager=null)
	{
		if (!$filter)
			$filter = new BorhanWidgetFilter;
			
		$widgetFilter = new widgetFilter ();
		$filter->toObject( $widgetFilter );
		
		$c = new Criteria();
		$widgetFilter->attachToCriteria( $c );
		
		$totalCount = widgetPeer::doCount( $c );
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria ( $c );
		$list = widgetPeer::doSelect( $c );
		
		$newList = BorhanWidgetArray::fromDbArray($list, $this->getResponseProfile());
		
		$response = new BorhanWidgetListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		
		return $response;
	}
}