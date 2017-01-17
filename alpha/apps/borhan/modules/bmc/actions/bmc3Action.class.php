<?php
/**
 * @package    Core
 * @subpackage BMC
 */
class bmc3Action extends borhanAction
{
	const CURRENT_BMC_VERSION = 3;
	private $confs = array();
	
	const SYSTEM_DEFAULT_PARTNER = 0;
	
	public function execute ( ) 
	{
		
		sfView::SUCCESS;

	/** check parameters and verify user is logged-in **/
		$this->partner_id = $this->getP ( "pid" );
		$this->subp_id = $this->getP ( "subpid", ((int)$this->partner_id)*100 );
		$this->uid = $this->getP ( "uid" );
		$this->ks = $this->getP ( "bmcks" );
		if(!$this->ks)
		{
			// if bmcks from cookie doesn't exist, try ks from REQUEST
			$this->ks = $this->getP('ks');
		}
		$this->screen_name = $this->getP ( "screen_name" );
		$this->email = $this->getP ( "email" );


		/** if no KS found, redirect to login page **/
		if (!$this->ks)
		{
			$this->redirect( "bmc/bmc" );
			die();
		}
	/** END - check parameters and verify user is logged-in **/

	/** load partner from DB, and set templatePartnerId **/
		$this->partner = $partner = null;
		$this->templatePartnerId = self::SYSTEM_DEFAULT_PARTNER;
		if ($this->partner_id !== NULL)
		{
			$this->partner = $partner = PartnerPeer::retrieveByPK($this->partner_id);
			bmcUtils::redirectPartnerToCorrectBmc($partner, $this->ks, $this->uid, $this->screen_name, $this->email, self::CURRENT_BMC_VERSION);
			$this->templatePartnerId = $this->partner ? $this->partner->getTemplatePartnerId() : self::SYSTEM_DEFAULT_PARTNER;
		}
	/** END - load partner from DB, and set templatePartnerId **/

	/** set default flags **/
		$this->allow_reports = false;
		$this->payingPartner = 'false';
		$this->embed_code  = "";
		$this->enable_live_streaming = 'false';
		$this->bmc_enable_custom_data = 'false';
		$this->bdp508_players = array();
		$this->first_login = false;
		$this->enable_vast = 'false';
	/** END - set default flags **/
	
	/** set values for template **/
	$this->service_url = myPartnerUtils::getHost($this->partner_id);
	$this->host = str_replace ( "http://" , "" , $this->service_url );
	$this->cdn_url = myPartnerUtils::getCdnHost($this->partner_id);
	$this->cdn_host = str_replace ( "http://" , "" , $this->cdn_url );
	$this->rtmp_host = kConf::get("rtmp_url");
	$this->flash_dir = $this->cdn_url . myContentStorage::getFSFlashRootPath ();
		
	/** set embed_code value **/
		if ( $this->partner_id !== null )
		{
			$widget = widgetPeer::retrieveByPK( "_" . $this->partner_id );
			if ( $widget )
			{
				$this->embed_code = $widget->getWidgetHtml( "borhan_player" );
				
				$ui_conf = $widget->getuiConf();
			}
		}
	/** END - set embed_code value **/

	/** set payingPartner flag **/
		if($partner && $partner->getPartnerPackage() != PartnerPackages::PARTNER_PACKAGE_FREE)
		{
			$this->payingPartner = 'true';
		}
	/** END - set payingPartner flag **/
		
	/** set enable_live_streaming flag **/
		if(kConf::get('bmc_content_enable_live_streaming') && $partner)
		{
			if ($partner->getLiveStreamEnabled())
			{
				$this->enable_live_streaming = 'true';
			}
		}
	/** END - set enable_live_streaming flag **/

	/** set enable_live_streaming flag **/
		if($partner && $partner->getEnableVast())
		{
			$this->enable_vast = 'true';
		}
	/** END - set enable_live_streaming flag **/
		
	/** set bmc_enable_custom_data flag **/
		$defaultPlugins = kConf::get('default_plugins');
		if(is_array($defaultPlugins) && in_array('MetadataPlugin', $defaultPlugins) && $partner)
		{
			if ($partner->getPluginEnabled('metadata') && $partner->getBmcVersion() == self::CURRENT_BMC_VERSION)
			{
				$this->bmc_enable_custom_data = 'true';
			}
		}
	/** END - set bmc_enable_custom_data flag **/

	/** set allow_reports flag **/
		// 2009-08-27 is the date we added ON2 to BMC trial account
		// TODO - should be depracated
		if(strtotime($partner->getCreatedAt()) >= strtotime('2009-08-27') ||
		   $partner->getEnableAnalyticsTab())
		{
			$this->allow_reports = true;
		}
		if($partner->getEnableAnalyticsTab())
		{
			$this->allow_reports = true;
		}
		// if the email is empty - it is an indication that the borhan super user is logged in
		if ( !$this->email) $this->allow_reports = true;
	/** END - set allow_reports flag **/
	
	/** set first_login and jw_license flags **/
		if ($partner)
		{
			$this->first_login = $partner->getIsFirstLogin();
			if ($this->first_login === true)
			{
				$partner->setIsFirstLogin(false);
				$partner->save();
			}
			$this->jw_license = $partner->getLicensedJWPlayer();
		}
	/** END - set first_login and jw_license flags **/
		
	/** partner-specific: change BDP version for partners working with auto-moderaion **/
		// set content bdp version according to partner id
		$moderated_partners = array( 31079, 28575, 32774 );
		$this->content_bdp_version = 'v2.7.0';
		if(in_array($this->partner_id, $moderated_partners))
		{
			$this->content_bdp_version = 'v2.1.2.29057';
		}
	/** END - partner-specific: change BDP version for partners working with auto-moderaion **/
		
	/** applications versioning **/
		$this->bmc_content_version 	= kConf::get('bmc_content_version');
		$this->bmc_account_version 	= kConf::get('bmc_account_version');
		$this->bmc_appstudio_version 	= kConf::get('bmc_appstudio_version');
		$this->bmc_rna_version 		= kConf::get('bmc_rna_version');
		$this->bmc_dashboard_version 	= kConf::get('bmc_dashboard_version');
	/** END - applications versioning **/
		
	/** uiconf listing work **/
		/** fill $this->confs with all uiconf objects for all modules **/
		$contentSystemUiConfs = bmcUtils::getAllBMCUiconfs('content',   $this->bmc_content_version, self::SYSTEM_DEFAULT_PARTNER);
		$contentTemplateUiConfs = bmcUtils::getAllBMCUiconfs('content',   $this->bmc_content_version, $this->templatePartnerId);
		//$this->confs = bmcUtils::getAllBMCUiconfs('content',   $this->bmc_content_version, $this->templatePartnerId);
		$appstudioSystemUiConfs = bmcUtils::getAllBMCUiconfs('appstudio', $this->bmc_appstudio_version, self::SYSTEM_DEFAULT_PARTNER);
		$appstudioTemplateUiConfs = bmcUtils::getAllBMCUiconfs('appstudio', $this->bmc_appstudio_version, $this->templatePartnerId);
		//$this->confs = array_merge($this->confs, bmcUtils::getAllBMCUiconfs('appstudio', $this->bmc_appstudio_version, $this->templatePartnerId));
		$reportsSystemUiConfs = bmcUtils::getAllBMCUiconfs('reports',   $this->bmc_rna_version, self::SYSTEM_DEFAULT_PARTNER);
		$reportsTemplateUiConfs = bmcUtils::getAllBMCUiconfs('reports',   $this->bmc_rna_version, $this->templatePartnerId);
		//$this->confs = array_merge($this->confs, bmcUtils::getAllBMCUiconfs('reports',   $this->bmc_rna_version, $this->templatePartnerId));
		
		/** for each module, create separated lists of its uiconf, for each need **/
		/** content players: **/
		$this->content_uiconfs_previewembed = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_previewembed", true, $contentSystemUiConfs);
		$this->content_uiconfs_previewembed_list = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_previewembed_list", true, $contentSystemUiConfs);
		$this->content_uiconfs_moderation = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_moderation", false, $contentSystemUiConfs);
		$this->content_uiconfs_drilldown = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_drilldown", false, $contentSystemUiConfs);
		$this->content_uiconfs_flavorpreview = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_flavorpreview", false, $contentSystemUiConfs);
		$this->content_uiconfs_metadataview = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_metadataview", false, $contentSystemUiConfs);
		/** content BCW,KSE,BAE **/
		$this->content_uiconfs_upload = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_upload", false, $contentSystemUiConfs);
		$this->simple_editor = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_simpleedit", false, $contentSystemUiConfs);
		$this->advanced_editor = bmcUtils::find_confs_by_usage_tag($contentTemplateUiConfs, "content_advanceedit", false, $contentSystemUiConfs);
		
		/** appStudio templates uiconf **/
		$this->appstudio_uiconfs_templates = bmcUtils::find_confs_by_usage_tag($appstudioTemplateUiConfs, "appstudio_templates", false, $appstudioSystemUiConfs);
		
		/** reports drill-down player **/
		$this->reports_uiconfs_drilldown = bmcUtils::find_confs_by_usage_tag($reportsTemplateUiConfs, "reports_drilldown", false, $reportsSystemUiConfs);
		
		/** silverlight uiconfs **/
		$this->silverLightPlayerUiConfs = array();
		$this->silverLightPlaylistUiConfs = array();
		if($partner->getBmcVersion() == self::CURRENT_BMC_VERSION && $partner->getEnableSilverLight())
		{
			$this->silverLightPlayerUiConfs = bmcUtils::getSilverLightPlayerUiConfs('slp');
			$this->silverLightPlaylistUiConfs = bmcUtils::getSilverLightPlayerUiConfs('sll');
		}

		/** jw uiconfs **/
		$this->jw_uiconfs_array = bmcUtils::getJWPlayerUIConfs();
		$this->jw_uiconf_playlist = bmcUtils::getJWPlaylistUIConfs();
		
		/** 508 uicinfs **/
		if($partner->getBmcVersion() == self::CURRENT_BMC_VERSION && $partner->getEnable508Players())
		{
			$this->bdp508_players = bmcUtils::getPlayerUiconfsByTag('bdp508');
		}
		
		/** partner's preview&embed uiconfs **/
		$this->content_pne_partners_player = bmcUtils::getPartnersUiconfs($this->partner_id, 'player');
		$this->content_pne_partners_playlist = bmcUtils::getPartnersUiconfs($this->partner_id, 'playlist');
		
		/** appstudio: default entry and playlists **/
		$this->appStudioExampleEntry = $partner->getAppStudioExampleEntry();
		$appStudioExampleEntry = entryPeer::retrieveByPK($this->appStudioExampleEntry);
		if (!($appStudioExampleEntry && $appStudioExampleEntry->getDisplayInSearch() == mySearchUtils::DISPLAY_IN_SEARCH_BORHAN_NETWORK && $appStudioExampleEntry->getStatus()== entryStatus::READY &&	$appStudioExampleEntry->getType() == entryType::MEDIA_CLIP ))
			$this->appStudioExampleEntry = "_BMCLOGO1";
		
		$this->appStudioExamplePlayList0 = $partner->getAppStudioExamplePlayList0();
		$appStudioExamplePlayList0 = entryPeer::retrieveByPK($this->appStudioExamplePlayList0);		
		if (!($appStudioExamplePlayList0 && $appStudioExamplePlayList0->getStatus()== entryStatus::READY && $appStudioExamplePlayList0->getType() == entryType::PLAYLIST ))
			$this->appStudioExamplePlayList0 = "_BMCSPL1";
		
		$this->appStudioExamplePlayList1 = $partner->getAppStudioExamplePlayList1();
		$appStudioExamplePlayList1 = entryPeer::retrieveByPK($this->appStudioExamplePlayList1);
		if (!($appStudioExamplePlayList1 && $appStudioExamplePlayList1->getStatus()== entryStatus::READY && $appStudioExamplePlayList1->getType() == entryType::PLAYLIST ))
			$this->appStudioExamplePlayList1 = "_BMCSPL2";
		/** END - appstudio: default entry and playlists **/
		
	/** END - uiconf listing work **/
		
		/** get templateXmlUrl for whitelabeled partners **/
		$this->appstudio_templatesXmlUrl = $this->getAppStudioTemplatePath();
	}

	private function getAppStudioTemplatePath()
	{
		$template_partner_id = (isset($this->templatePartnerId))? $this->templatePartnerId: self::SYSTEM_DEFAULT_PARTNER;
		if (!$template_partner_id)
			return false;
	
		$c = new Criteria();
		$c->addAnd(uiConfPeer::PARTNER_ID, $template_partner_id );
		$c->addAnd ( uiConfPeer::STATUS , uiConf::UI_CONF_STATUS_READY );
		$c->addAnd ( uiConfPeer::OBJ_TYPE , uiConf::UI_CONF_TYPE_BMC_APP_STUDIO );
		$c->addAnd(uiConfPeer::DISPLAY_IN_SEARCH, mySearchUtils::DISPLAY_IN_SEARCH_BORHAN_NETWORK);
	
		$uiConf = uiConfPeer::doSelectOne($c);
		if ($uiConf)
		{
			$sync_key = $uiConf->getSyncKey( uiConf::FILE_SYNC_UICONF_SUB_TYPE_DATA );
			if ($sync_key)
			{
				$file_sync = kFileSyncUtils::getLocalFileSyncForKey( $sync_key , true );
				if ($file_sync)
				{
					return "/".$file_sync->getFilePath();
				}
			}
	
		}
	
		return false;
	}
    
	/** TODO - remove Deprecated **/
	private function DEPRECATED_getAdvancedEditorUiConf()
	{
		$c = new Criteria();
		$c->addAnd( uiConfPeer::DISPLAY_IN_SEARCH , mySearchUtils::DISPLAY_IN_SEARCH_BORHAN_NETWORK , Criteria::GREATER_EQUAL );
		$c->addAnd ( uiConfPeer::STATUS , uiConf::UI_CONF_STATUS_READY );
		$c->addAnd ( uiConfPeer::OBJ_TYPE , uiConf::UI_CONF_TYPE_ADVANCED_EDITOR );
		$c->addAnd ( uiConfPeer::TAGS, 'andromeda_bae_for_bmc', Criteria::LIKE);
		$c->addAscendingOrderByColumn(uiConfPeer::ID);

		$uiConf = uiConfPeer::doSelectOne($c);
		if ($uiConf)
			return $uiConf->getId();
		else
			return -1;
	}
	
	/** TODO - remove Deprecated **/
	private function DEPRECATED_getSimpleEditorUiConf()
	{
		$c = new Criteria();
		$c->addAnd( uiConfPeer::DISPLAY_IN_SEARCH , mySearchUtils::DISPLAY_IN_SEARCH_BORHAN_NETWORK , Criteria::GREATER_EQUAL );
		$c->addAnd ( uiConfPeer::STATUS , uiConf::UI_CONF_STATUS_READY );
		$c->addAnd ( uiConfPeer::OBJ_TYPE , uiConf::UI_CONF_TYPE_EDITOR );
		$c->addAnd ( uiConfPeer::TAGS, 'andromeda_kse_for_bmc', Criteria::LIKE);
		$c->addAscendingOrderByColumn(uiConfPeer::ID);

		$uiConf = uiConfPeer::doSelectOne($c);
		if ($uiConf)
			return $uiConf->getId();
		else
			return -1;
	}

	private function getCritria ( )
	{
		$c = new Criteria();
		
		// or belongs to the partner or a template  
		$criterion = $c->getNewCriterion( uiConfPeer::PARTNER_ID , $this->partner_id ) ; // or belongs to partner
		$criterion2 = $c->getNewCriterion( uiConfPeer::DISPLAY_IN_SEARCH , mySearchUtils::DISPLAY_IN_SEARCH_BORHAN_NETWORK , Criteria::GREATER_EQUAL );	// or belongs to borhan_network == templates
		
		$criterion2partnerId = $c->getNewCriterion(uiConfPeer::PARTNER_ID, $this->templatePartnerId);
		$criterion2->addAnd($criterion2partnerId);  
		
		$criterion->addOr ( $criterion2 ) ;
		$c->addAnd ( $criterion );
		
		$c->addAnd ( uiConfPeer::OBJ_TYPE , uiConf::UI_CONF_TYPE_WIDGET );	//	only ones that are of type WIDGET
		$c->addAnd ( uiConfPeer::STATUS , uiConf::UI_CONF_STATUS_READY ); 	//	display only ones that are ready - not deleted or in draft mode
		
		
		$order_by = "(" . uiConfPeer::PARTNER_ID . "={$this->partner_id})";  // first take the templates  and then the rest
		$c->addAscendingOrderByColumn ( $order_by );//, Criteria::CUSTOM );

		return $c;
	}
	
	private function getUiconfList($tag = 'player')
	{
		$template_partner_id = (isset($this->templatePartnerId))? $this->templatePartnerId: self::SYSTEM_DEFAULT_PARTNER;
		$c = new Criteria();
		$crit_partner = $c->getNewCriterion(uiConfPeer::PARTNER_ID, $this->partner_id);
		 $crit_default = $c->getNewCriterion(uiConfPeer::DISPLAY_IN_SEARCH, mySearchUtils::DISPLAY_IN_SEARCH_BORHAN_NETWORK, Criteria::GREATER_EQUAL);
		
		$crit_default_partner_id = $c->getNewCriterion(uiConfPeer::PARTNER_ID, $template_partner_id);
		$crit_default_swf_url = $c->getNewCriterion(uiConfPeer::SWF_URL, '%/bdp3/%bdp3.swf', Criteria::LIKE);
		$crit_default->addAnd($crit_default_partner_id);
		$crit_default->addAnd($crit_default_swf_url);
		
		$crit_partner->addOr($crit_default);
		$c->add($crit_partner);
		$c->addAnd(uiConfPeer::OBJ_TYPE, array(uiConf::UI_CONF_TYPE_WIDGET, uiConf::UI_CONF_TYPE_BDP3), Criteria::IN);
		$c->addAnd ( uiConfPeer::STATUS , uiConf::UI_CONF_STATUS_READY );
		$c->addAnd ( uiConfPeer::TAGS, '%'.$tag.'%', Criteria::LIKE );
		$c->addAnd ( uiConfPeer::TAGS, '%jw'.$tag.'%', Criteria::NOT_LIKE );
		
		$c->addAnd ( uiConfPeer::ID, array(48501, 48502, 48504, 48505), Criteria::NOT_IN );
		
		$order_by = "(" . uiConfPeer::PARTNER_ID . "=".$this->partner_id.")";
		$c->addAscendingOrderByColumn ( $order_by );
		$c->addDescendingOrderByColumn(uiConfPeer::CREATED_AT);
		
		$confs = uiConfPeer::doSelect($c);
		return $confs;
	}	
}
