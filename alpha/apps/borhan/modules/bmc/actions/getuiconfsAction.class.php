<?php
/**
 * @package    Core
 * @subpackage BMC
 */
class getuiconfsAction extends borhanAction
{
	public function execute ( ) 
	{
		header('Access-Control-Allow-Origin:*');

		$this->partner_id = $this->getP ( "partner_id" );
		$this->ks = $this->getP ( "ks" );
		$type = $this->getP("type");
		
		$this->partner = PartnerPeer::retrieveByPK($this->partner_id);
		if (!$this->partner)
			KExternalErrors::dieError( KExternalErrors::PARTNER_NOT_FOUND );
					
		if (!$this->partner->validateApiAccessControl())
			KExternalErrors::dieError( KExternalErrors::SERVICE_ACCESS_CONTROL_RESTRICTED );
			
		$this->templatePartnerId = $this->partner ? $this->partner->getTemplatePartnerId() : 0;
		$this->isBDP3 = ($this->partner->getBmcVersion() != '1')? true: false;

		// FIXME: validate the ks!
		
		
		$partnerUiconfs = bmcUtils::getPartnersUiconfs($this->partner_id, $type);
		$partner_uiconfs_array = array();
		foreach($partnerUiconfs as $uiconf)
		{
			$uiconf_array = array();
			$uiconf_array["id"] = $uiconf->getId();
			$uiconf_array["name"] = $uiconf->getName();
			$uiconf_array["width"] = $uiconf->getWidth();
			$uiconf_array["height"] = $uiconf->getHeight();
			//$uiconf_array["swfUrlVersion"] = $uiconf->getSwfUrlVersion();
			$uiconf_array["swf_version"] = "v" . $uiconf->getSwfUrlVersion();
			$uiconf_array["html5Url"] = $uiconf->getHtml5Url();
            $uiconf_array["updatedAt"] = $uiconf->getUpdatedAt(null);

			$partner_uiconfs_array[] = $uiconf_array;
		}
		
		// default uiconf array
		$this->bmc_swf_version = kConf::get('bmc_version');
		$bmcGeneralUiConf = array();
		$bmcGeneralTemplateUiConf = array();
		if (!PermissionPeer::isValidForPartner(PermissionName::FEATURE_HIDE_TEMPLATE_PARTNER_UICONFS, $this->partner->getId()))
		{
			$bmcGeneralUiConf = bmcUtils::getAllBMCUiconfs('bmc',   $this->bmc_swf_version, $this->templatePartnerId);
			$bmcGeneralTemplateUiConf = bmcUtils::getAllBMCUiconfs('bmc',   $this->bmc_swf_version, $this->templatePartnerId);
		}
			
		if($type == 'player')
		{
			$content_uiconfs_previewembed = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_previewembed", true, $bmcGeneralUiConf);
		}
		else
		{
			$content_uiconfs_previewembed = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_previewembed_list", true, $bmcGeneralUiConf);
		}
		
		$default_uiconfs_array = array();
		foreach($content_uiconfs_previewembed as $uiconf)
		{
			$uiconf_array = array();
			$uiconf_array["id"] = $uiconf->getId();
			$uiconf_array["name"] = $uiconf->getName();
			$uiconf_array["width"] = $uiconf->getWidth();
			$uiconf_array["height"] = $uiconf->getHeight();
			//$uiconf_array["swfUrlVersion"] = $uiconf->getSwfUrlVersion();
			$uiconf_array["swf_version"] = "v" . $uiconf->getSwfUrlVersion();
			$uiconf_array["html5Url"] = $uiconf->getHtml5Url();
			$uiconf_array["updatedAt"] = $uiconf->getUpdatedAt(null);

			$default_uiconfs_array[] = $uiconf_array;
		}
		
		$bdp508_uiconfs = array();
		if($type == 'player' && $this->partner->getEnable508Players())
		{
			$bdp508_uiconfs = bmcUtils::getPlayerUiconfsByTag('bdp508');
		}

		// Add HTML5 v2.0.0 Preview Player
		$v2_preview_players = array();
		if( $type == 'player'&& PermissionPeer::isValidForPartner(PermissionName::FEATURE_HTML5_V2_PLAYER_PREVIEW, $this->partner_id)){
			$v2_preview_players = bmcUtils::getPlayerUiconfsByTag('html5_v2_preview');
		}
		
		$merged_list = array();
		if(count($default_uiconfs_array))
			foreach($default_uiconfs_array as $uiconf)
				$merged_list[] = $uiconf;
		if(count($bdp508_uiconfs))
			foreach($bdp508_uiconfs as $uiconf)
				$merged_list[] = $uiconf;
		if(count($v2_preview_players))
			foreach($v2_preview_players as $uiconf)
				$merged_list[] = $uiconf;			
		if(count($partner_uiconfs_array))
			foreach($partner_uiconfs_array as $uiconf)
				$merged_list[] = $uiconf;

		return $this->renderText(json_encode($merged_list));
	}
}
