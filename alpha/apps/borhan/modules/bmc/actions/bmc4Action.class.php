<?php
/**
 * @package    Core
 * @subpackage BMC
 */
class bmc4Action extends borhanAction
{
	const CURRENT_BMC_VERSION = 4;
	
	private $confs = array();
	
	const SYSTEM_DEFAULT_PARTNER = 0;
	
	public function execute ( ) 
	{
		
		sfView::SUCCESS;

		/** check parameters and verify user is logged-in **/
		$this->ks = $this->getP ( "bmcks" );
		if(!$this->ks)
		{
			// if bmcks from cookie doesn't exist, try ks from REQUEST
			$this->ks = $this->getP('ks');
		}
		
		/** if no KS found, redirect to login page **/
		if (!$this->ks)
		{
			$this->redirect( "bmc/bmc" );
			die();
		}
		$ksObj = kSessionUtils::crackKs($this->ks);
		// Set partnerId from KS
		$this->partner_id = $ksObj->partner_id;

		// Check if the BMC can be framed
		$allowFrame = PermissionPeer::isValidForPartner(PermissionName::FEATURE_BMC_ALLOW_FRAME, $this->partner_id);
		if(!$allowFrame) {
			header( 'X-Frame-Options: DENY' );
		}
		// Check for forced HTTPS
		$force_ssl = PermissionPeer::isValidForPartner(PermissionName::FEATURE_BMC_ENFORCE_HTTPS, $this->partner_id);
		if( $force_ssl && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') ) {
			header( "Location: " . infraRequestUtils::PROTOCOL_HTTPS . "://" . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"] );
			die();
		}
		/** END - check parameters and verify user is logged-in **/
		
		/** Get array of allowed partners for the current user **/
		$allowedPartners = array();
		$this->full_name = "";
		$currentUser = kuserPeer::getKuserByPartnerAndUid($this->partner_id, $ksObj->user, true);
		if($currentUser) {
			$partners = myPartnerUtils::getPartnersArray($currentUser->getAllowedPartnerIds());
			foreach ($partners as $partner)
				$allowedPartners[] = array('id' => $partner->getId(), 'name' => $partner->getName());
				
			$this->full_name = $currentUser->getFullName();
		}
		$this->showChangeAccount = (count($allowedPartners) > 1 ) ? true : false;

		// Load partner
		$this->partner = $partner = PartnerPeer::retrieveByPK($this->partner_id);
		if (!$partner)
			KExternalErrors::dieError(KExternalErrors::PARTNER_NOT_FOUND);
		
		if (!$partner->validateApiAccessControl())
			KExternalErrors::dieError(KExternalErrors::SERVICE_ACCESS_CONTROL_RESTRICTED);
		
		bmcUtils::redirectPartnerToCorrectBmc($partner, $this->ks, null, null, null, self::CURRENT_BMC_VERSION);
		$this->templatePartnerId = $this->partner ? $this->partner->getTemplatePartnerId() : self::SYSTEM_DEFAULT_PARTNER;
		$ignoreEntrySeoLinks = PermissionPeer::isValidForPartner(PermissionName::FEATURE_IGNORE_ENTRY_SEO_LINKS, $this->partner_id);
		$useEmbedCodeProtocolHttps = PermissionPeer::isValidForPartner(PermissionName::FEATURE_EMBED_CODE_DEFAULT_PROTOCOL_HTTPS, $this->partner_id);
		$showFlashStudio = PermissionPeer::isValidForPartner(PermissionName::FEATURE_SHOW_FLASH_STUDIO, $this->partner_id);
		$showHTMLStudio = PermissionPeer::isValidForPartner(PermissionName::FEATURE_SHOW_HTML_STUDIO, $this->partner_id);
		$deliveryTypes = $partner->getDeliveryTypes();
		$embedCodeTypes = $partner->getEmbedCodeTypes();
		$defaultDeliveryType = ($partner->getDefaultDeliveryType()) ? $partner->getDefaultDeliveryType() : 'http';
		$defaultEmbedCodeType = ($partner->getDefaultEmbedCodeType()) ? $partner->getDefaultEmbedCodeType() : 'auto';
		$this->previewEmbedV2 = PermissionPeer::isValidForPartner(PermissionName::FEATURE_PREVIEW_AND_EMBED_V2, $this->partner_id);
		
		/** set values for template **/
		$this->service_url = requestUtils::getRequestHost();
		$this->host = $this->stripProtocol( $this->service_url );
		$this->embed_host = $this->stripProtocol( myPartnerUtils::getHost($this->partner_id) );
		if (kConf::hasParam('cdn_api_host') && kConf::hasParam('www_host') && $this->host == kConf::get('cdn_api_host')) {
	        $this->host = kConf::get('www_host');
		}
		if($this->embed_host == kConf::get("www_host") && kConf::hasParam('cdn_api_host')) {
			$this->embed_host = kConf::get('cdn_api_host');
		}
		$this->embed_host_https = (kConf::hasParam('cdn_api_host_https')) ? kConf::get('cdn_api_host_https') : kConf::get('www_host');	

		$this->cdn_url = myPartnerUtils::getCdnHost($this->partner_id);
		$this->cdn_host = $this->stripProtocol( $this->cdn_url );
		$this->rtmp_host = kConf::get("rtmp_url");
		$this->flash_dir = $this->cdn_url . myContentStorage::getFSFlashRootPath ();

		/** set payingPartner flag **/
		$this->payingPartner = 'false';
		if($partner && $partner->getPartnerPackage() != PartnerPackages::PARTNER_PACKAGE_FREE)
		{
			$this->payingPartner = 'true';
			$ignoreSeoLinks = true;
		} else {
			$ignoreSeoLinks = $this->partner->getIgnoreSeoLinks();
		}

		/** get partner languae **/
		$language = null;
		if ($partner->getBMCLanguage())
			$language = $partner->getBMCLanguage();

		$first_login = $partner->getIsFirstLogin();
		if ($first_login === true)
		{
			$partner->setIsFirstLogin(false);
			$partner->save();
		}
		
		/** get logout url **/
		$logoutUrl = null; 
		if ($partner->getLogoutUrl())
			$logoutUrl = $partner->getLogoutUrl();
		
		$this->bmc_swf_version = kConf::get('bmc_version');

		$akamaiEdgeServerIpURL = null;
		if( kConf::hasParam('akamai_edge_server_ip_url') ) {
			$akamaiEdgeServerIpURL = kConf::get('akamai_edge_server_ip_url');
		}
		
	/** uiconf listing work **/
		/** fill $confs with all uiconf objects for all modules **/
		$bmcGeneralUiConf = bmcUtils::getAllBMCUiconfs('bmc',   $this->bmc_swf_version, self::SYSTEM_DEFAULT_PARTNER);
		$bmcGeneralTemplateUiConf = bmcUtils::getAllBMCUiconfs('bmc',   $this->bmc_swf_version, $this->templatePartnerId);

		
		/** for each module, create separated lists of its uiconf, for each need **/
		/** bmc general uiconfs **/
		$this->bmc_general = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_bmcgeneral", false, $bmcGeneralUiConf);
		$this->bmc_permissions = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_bmcpermissions", false, $bmcGeneralUiConf);
		/** P&E players: **/
		//$this->content_uiconfs_previewembed = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_previewembed", true, $bmcGeneralUiConf);
		//$this->content_uiconfs_previewembed_list = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_previewembed_list", true, $bmcGeneralUiConf);
		$this->content_uiconfs_flavorpreview = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_flavorpreview", false, $bmcGeneralUiConf);

		/* BCW uiconfs */
		$this->content_uiconfs_upload_webcam = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_uploadWebCam", false, $bmcGeneralUiConf);
		$this->content_uiconfs_upload_import = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_uploadImport", false, $bmcGeneralUiConf);

		$this->content_uiconds_clipapp_bdp = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_bdpClipApp", false, $bmcGeneralUiConf);
		$this->content_uiconds_clipapp_kclip = bmcUtils::find_confs_by_usage_tag($bmcGeneralTemplateUiConf, "bmc_kClipClipApp", false, $bmcGeneralUiConf);
		
		$this->studioUiConf = bmcUtils::getStudioUiconf(kConf::get("studio_version"));
		$this->content_uiconfs_studio_v2 = isset($this->studioUiConf) ? array_values($this->studioUiConf) : null;
		$this->content_uiconf_studio_v2 = (is_array($this->content_uiconfs_studio_v2) && reset($this->content_uiconfs_studio_v2)) ? reset($this->content_uiconfs_studio_v2) : null;
		
		$this->liveAUiConf = bmcUtils::getLiveAUiconf();
		$this->content_uiconfs_livea = isset($this->liveAUiConf) ? array_values($this->liveAUiConf) : null;
		$this->content_uiconf_livea = (is_array($this->content_uiconfs_livea) && reset($this->content_uiconfs_livea)) ? reset($this->content_uiconfs_livea) : null;
		

		$bmcVars = array(
			'bmc_version'				=> $this->bmc_swf_version,
			'bmc_general_uiconf'		=> $this->bmc_general->getId(),
			'bmc_permissions_uiconf'	=> $this->bmc_permissions->getId(),
			'allowed_partners'			=> $allowedPartners,
			'bmc_secured'				=> (bool) kConf::get("bmc_secured_login"),
			'enableLanguageMenu'		=> true,
			'service_url'				=> $this->service_url,
			'host'						=> $this->host,
			'cdn_host'					=> $this->cdn_host,
			'rtmp_host'					=> $this->rtmp_host,
			'embed_host'				=> $this->embed_host,
			'embed_host_https'			=> $this->embed_host_https,
			'flash_dir'					=> $this->flash_dir,
			'getuiconfs_url'			=> '/index.php/bmc/getuiconfs',
			'terms_of_use'				=> kConf::get('terms_of_use_uri'),
			'ks'						=> $this->ks,
			'partner_id'				=> $this->partner_id,
			'first_login'				=> (bool) $first_login,
			'whitelabel'				=> $this->templatePartnerId,
			'ignore_seo_links'			=> (bool) $ignoreSeoLinks,
			'ignore_entry_seo'			=> (bool) $ignoreEntrySeoLinks,
			'embed_code_protocol_https'	=> (bool) $useEmbedCodeProtocolHttps,
			'delivery_types'			=> $deliveryTypes,
			'embed_code_types'			=> $embedCodeTypes,
			'default_delivery_type'		=> $defaultDeliveryType,
			'default_embed_code_type'	=> $defaultEmbedCodeType,
			'bcw_webcam_uiconf'			=> $this->content_uiconfs_upload_webcam->getId(),
			'bcw_import_uiconf'			=> $this->content_uiconfs_upload_import->getId(),
			'default_bdp'				=> array(
				'id'					=> $this->content_uiconfs_flavorpreview->getId(),
				'height'				=> $this->content_uiconfs_flavorpreview->getHeight(),
				'width'					=> $this->content_uiconfs_flavorpreview->getWidth(),
				'swf_version'			=> $this->content_uiconfs_flavorpreview->getswfUrlVersion(),
			),
			'clipapp'					=> array(
				'version'				=> kConf::get("clipapp_version"),
				'bdp'					=> $this->content_uiconds_clipapp_bdp->getId(),
				'kclip'					=> $this->content_uiconds_clipapp_kclip->getId(),
			),
			'studio'					=> array(
                'version'				=> kConf::get("studio_version"),
                'uiConfID'				=> isset($this->content_uiconf_studio_v2) ? $this->content_uiconf_studio_v2->getId() : '',
                'config'				=> isset($this->content_uiconf_studio_v2) ? $this->content_uiconf_studio_v2->getConfig() : '',
                'showFlashStudio'		=> $showFlashStudio,
                'showHTMLStudio'		=> $showHTMLStudio,
            ),
			'liveanalytics'					=> array(
                'version'				=> kConf::get("liveanalytics_version"),
                'player_id'				=> isset($this->content_uiconf_livea) ? $this->content_uiconf_livea->getId() : '',
					
				'map_zoom_levels' => kConf::hasParam ("map_zoom_levels") ? kConf::get ("map_zoom_levels") : '',
			    'map_urls' => kConf::hasParam ("cdn_static_hosts") ? array_map(function($s) {return "$s/content/static/maps/v1";}, kConf::get ("cdn_static_hosts")) : '',
            ),
			'usagedashboard'			=> array(
				'version'				=> kConf::get("usagedashboard_version"),
			),
			'disable_analytics'			=> (bool) kConf::get("bmc_disable_analytics"),
			'google_analytics_account'	=> kConf::get("ga_account"),
			'language'					=> $language,
			'logoutUrl'					=> $logoutUrl,
			'allowFrame'				=> (bool) $allowFrame,
			'akamaiEdgeServerIpURL'		=> $akamaiEdgeServerIpURL,
			'logoUrl' 					=> bmcUtils::getWhitelabelData( $partner, 'logo_url'),
			'supportUrl' 				=> bmcUtils::getWhitelabelData( $partner, 'support_url'),
		);
		
		$this->bmcVars = $bmcVars;
	}

	private function stripProtocol( $url )
	{
		$url_data = parse_url( $url );
		if( $url_data !== false ){
			$port = (isset($url_data['port'])) ? ':' . $url_data['port'] : '';
			return $url_data['host'] . $port;
		} else {
			return $url;
		}
	}
    
}
