<?php
/**
 * @package plugins.businessProcessNotification
 * @subpackage admin
 */
class BusinessProcessNotificationTemplatesListProcessesAction extends BorhanApplicationPlugin
{
	public function __construct()
	{
		$this->action = 'listProcessesBusinessProcessNotificationTemplates';
	}
	
	/* (non-PHPdoc)
	 * @see BorhanApplicationPlugin::getTemplatePath()
	 */
	public function getTemplatePath()
	{
		return realpath(dirname(__FILE__));
	}
	
	/* (non-PHPdoc)
	 * @see BorhanApplicationPlugin::getRequiredPermissions()
	 */
	public function getRequiredPermissions()
	{
		return array(Borhan_Client_Enum_PermissionName::SYSTEM_ADMIN_EVENT_NOTIFICATION_MODIFY);
	}
	
	/* (non-PHPdoc)
	 * @see BorhanApplicationPlugin::doAction()
	 */
	public function doAction(Zend_Controller_Action $action)
	{	
		$action->getHelper('viewRenderer')->setNoRender();
		$serverId = $this->_getParam('server_id');
		
		$client = Infra_ClientHelper::getClient();
		$businessProcessNotificationPlugin = Borhan_Client_BusinessProcessNotification_Plugin::get($client);
		
		$partnerId = $this->_getParam('partner_id');
		if($partnerId)
			Infra_ClientHelper::impersonate($partnerId);
		
		try{
			if($serverId == 0)
			{
				$filter = new Borhan_Client_BusinessProcessNotification_Type_BusinessProcessServerFilter();
				$filter->currentDc = Borhan_Client_Enum_NullableBoolean::TRUE_VALUE;
				$pager = new Borhan_Client_Type_FilterPager();
				$pager->pageSize = 1;
				$serversList = $businessProcessNotificationPlugin->businessProcessServer->listAction($filter, $pager);
				/* @var $serversList Borhan_Client_BusinessProcessNotification_Type_BusinessProcessServerListResponse */
				$server = $serversList->objects[0];
			}
			else
				$server = $businessProcessNotificationPlugin->businessProcessServer->get($serverId);
				/* @var $server Borhan_Client_BusinessProcessNotification_Type_BusinessProcessServer */

			$businessProcessProvider = kBusinessProcessProvider::get($server);
			$processes = $businessProcessProvider->listBusinessProcesses();
			asort($processes);
		}
		catch(Exception $e)
		{
			BorhanLog::err($e->getMessage() . "\n" . $e->getTraceAsString());
			echo $action->getHelper('json')->sendJson($e->getMessage(), false);
		}
		
		echo $action->getHelper('json')->sendJson($processes, false);
	}
}

