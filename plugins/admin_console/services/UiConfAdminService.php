<?php
/**
 * UiConf Admin service
 *
 * @service uiConfAdmin
 * @package plugins.adminConsole
 * @subpackage api.services
 */
class UiConfAdminService extends BorhanBaseService
{
	const PERMISSION_GLOBAL_PARTNER_UI_CONF_UPDTAE = 'GLOBAL_PARTNER_UI_CONF_UPDTAE';
	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);

		if(!AdminConsolePlugin::isAllowedPartner($this->getPartnerId()))
			throw new BorhanAPIException(BorhanErrors::FEATURE_FORBIDDEN, AdminConsolePlugin::PLUGIN_NAME);
	}
	
	/**
	 * Adds new UIConf with no partner limitation
	 * 
	 * @action add
	 * @param BorhanUiConfAdmin $uiConf
	 * @return BorhanUiConfAdmin
	 */
	function addAction(BorhanUiConfAdmin $uiConf)
	{
		// if not specified set to true (default)
		if(is_null($uiConf->useCdn))
			$uiConf->useCdn = true;
			
		$dbUiConf = $uiConf->toObject(new uiConf());	
		if ($dbUiConf->getPartnerId() == PartnerPeer::GLOBAL_PARTNER && !kPermissionManager::isPermitted(self::PERMISSION_GLOBAL_PARTNER_UI_CONF_UPDTAE))
			throw new BorhanAPIException ( BorhanErrors::INVALID_PARTNER_ID, PartnerPeer::GLOBAL_PARTNER );
		
		$dbUiConf->save();
		
		$uiConf = new BorhanUiConfAdmin();
		$uiConf->fromObject($dbUiConf, $this->getResponseProfile());
		
		return $uiConf;
	}

	/**
	 * Update an existing UIConf with no partner limitation
	 * 
	 * @action update
	 * @param int $id 
	 * @param BorhanUiConfAdmin $uiConf
	 * @return BorhanUiConfAdmin
	 *
	 * @throws APIErrors::INVALID_UI_CONF_ID
	 */	
	function updateAction($id, BorhanUiConfAdmin $uiConf)
	{
		$dbUiConf = uiConfPeer::retrieveByPK( $id );
		if (!$dbUiConf)
			throw new BorhanAPIException ( APIErrors::INVALID_UI_CONF_ID , $id );
		
		if ($dbUiConf->getPartnerId() == PartnerPeer::GLOBAL_PARTNER && !kPermissionManager::isPermitted(self::PERMISSION_GLOBAL_PARTNER_UI_CONF_UPDTAE))
			throw new BorhanAPIException ( APIErrors::INVALID_UI_CONF_ID , $id );
		
		$dbUiConf = $uiConf->toObject($dbUiConf);
		$dbUiConf->save();
		
		$uiConf = new BorhanUiConfAdmin();
		$uiConf->fromObject($dbUiConf, $this->getResponseProfile());
		
		return $uiConf;
	}
	
	/**
	 * Retrieve a UIConf by id with no partner limitation
	 * 
	 * @action get
	 * @param int $id 
	 * @return BorhanUiConfAdmin
	 *
	 * @throws APIErrors::INVALID_UI_CONF_ID
	 */		
	function getAction($id)
	{
		$dbUiConf = uiConfPeer::retrieveByPK($id);
		
		if (!$dbUiConf)
			throw new BorhanAPIException(APIErrors::INVALID_UI_CONF_ID, $id);
			
		$uiConf = new BorhanUiConfAdmin();
		$uiConf->fromObject($dbUiConf, $this->getResponseProfile());
		
		return $uiConf;
	}
	
	/**
	 * Delete an existing UIConf with no partner limitation
	 * 
	 * @action delete
	 * @param int $id
	 *
	 * @throws APIErrors::INVALID_UI_CONF_ID
	 */		
	function deleteAction($id)
	{
		$dbUiConf = uiConfPeer::retrieveByPK($id);
		
		if (!$dbUiConf)
			throw new BorhanAPIException(APIErrors::INVALID_UI_CONF_ID, $id);
			
		if ($dbUiConf->getPartnerId() == PartnerPeer::GLOBAL_PARTNER && !kPermissionManager::isPermitted(self::PERMISSION_GLOBAL_PARTNER_UI_CONF_UPDTAE))
			throw new BorhanAPIException ( APIErrors::INVALID_UI_CONF_ID , $id );
			
		$dbUiConf->setStatus(uiConf::UI_CONF_STATUS_DELETED);
		$dbUiConf->save();
	}
	
	/**
	 * Retrieve a list of available UIConfs  with no partner limitation
	 * 
	 * @action list
	 * @param BorhanUiConfFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanUiConfAdminListResponse
	 */		
	function listAction( BorhanUiConfFilter $filter = null , BorhanFilterPager $pager = null)
	{
		myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL2;
		
		if (!$filter)
			$filter = new BorhanUiConfFilter();
			
		$uiConfFilter = new uiConfFilter();
		$filter->toObject($uiConfFilter);
		
		$c = new Criteria();
		$uiConfFilter->attachToCriteria($c);
		$count = uiConfPeer::doCount($c);
		if (! $pager)
			$pager = new BorhanFilterPager ();
		$pager->attachToCriteria($c);
		$list = uiConfPeer::doSelect($c);
		
		$newList = BorhanUiConfAdminArray::fromDbArray($list, $this->getResponseProfile());
		
		$response = new BorhanUiConfAdminListResponse();
		$response->objects = $newList;
		$response->totalCount = $count;
		
		return $response;
	}
}
