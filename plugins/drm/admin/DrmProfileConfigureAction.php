<?php
/**
 * @package plugins.drm
 * @subpackage Admin
 */
class DrmProfileConfigureAction extends BorhanApplicationPlugin
{	
	/**
	 * @return string - absolute file path of the phtml template
	 */
	public function getTemplatePath()
	{
		return realpath(dirname(__FILE__));
	}
	
	public function getRequiredPermissions()
	{
		return array(Borhan_Client_Enum_PermissionName::SYSTEM_ADMIN_DRM_PROFILE_MODIFY);
	}
	
	public function doAction(Zend_Controller_Action $action)
	{
		$action->getHelper('layout')->disableLayout();
		$request = $action->getRequest();
		$drmProfileId = $this->_getParam('drm_profile_id');
		$partnerId = $this->_getParam('new_partner_id');
		$drmProfileProvider = $this->_getParam('new_drm_profile_provider');
		$drmProfileForm = null;
		$action->view->formValid = false;
		
		try
		{
			if ($request->isPost())
			{
				$partnerId = $this->_getParam('partnerId');
				$drmProfileProvider = $this->_getParam('provider');
				$drmProfileForm = new Form_DrmProfileConfigure($partnerId, $drmProfileProvider);
				$action->view->formValid = $this->processForm($drmProfileForm, $request->getPost(), $partnerId, $drmProfileId);
				if(!is_null($drmProfileId))
				{
					$drmProfile = $drmProfileForm->getObject("Borhan_Client_Drm_Type_DrmProfile", $request->getPost(), false, true);
				}
			}
			else
			{
				if (!is_null($drmProfileId))
				{
					$client = Infra_ClientHelper::getClient();
					$drmPluginClient = Borhan_Client_Drm_Plugin::get($client);
					$drmProfile = $drmPluginClient->drmProfile->get($drmProfileId);
					$partnerId = $drmProfile->partnerId;
					$drmProfileProvider = $drmProfile->provider;
					$drmProfileForm = new Form_DrmProfileConfigure($partnerId, $drmProfileProvider);
					$drmProfileForm->populateFromObject($drmProfile, false);
				}
				else
				{
					$drmProfileForm = new Form_DrmProfileConfigure($partnerId, $drmProfileProvider);
					$drmProfileForm->getElement('partnerId')->setValue($partnerId);					
				}
			}
		}
		catch(Exception $e)
		{
		    $action->view->formValid = false;
			BorhanLog::err($e->getMessage() . "\n" . $e->getTraceAsString());
			$action->view->errMessage = $e->getMessage();
		}
		
		$action->view->form = $drmProfileForm;
		$pluginInstances = BorhanPluginManager::getPluginInstances('IBorhanApplicationPartialView');
		foreach($pluginInstances as $pluginInstance)
		{
			$drmProfilePlugins = $pluginInstance->getApplicationPartialViews('plugin', get_class($this));
			if(!$drmProfilePlugins)
				continue;
			foreach($drmProfilePlugins as $plugin)
			{
				/* @var $plugin Borhan_View_Helper_PartialViewPlugin */
	    			$plugin->plug($action->view);
			}
		}
	}
	
	private function processForm(Form_DrmProfileConfigure $form, $formData, $partnerId, $drmProfileId = null)
	{
		if ($form->isValid($formData))
		{
			$client = Infra_ClientHelper::getClient();
			$drmPluginClient = Borhan_Client_Drm_Plugin::get($client);
			
			$drmProfile = $form->getObject("Borhan_Client_Drm_Type_DrmProfile", $formData, false, true);
			unset($drmProfile->id);
			
			Infra_ClientHelper::impersonate($partnerId);
			if (is_null($drmProfileId)) {
				$drmProfile->status = Borhan_Client_Drm_Enum_DrmProfileStatus::ACTIVE;
				$responseDrmProfile = $drmPluginClient->drmProfile->add($drmProfile);
			}
			else {
				$responseDrmProfile = $drmPluginClient->drmProfile->update($drmProfileId, $drmProfile);
			}
			Infra_ClientHelper::unimpersonate();
			
			$form->setAttrib('class', 'valid');
			return true;
		}
		else
		{
			$form->populate($formData);
			return false;
		}
	}
	
}

