<?php
/**
 * This file store all of mwEmbed local configuration ( in a default svn check out this file is empty )
 *
 * See includes/DefaultSettings.php for a configuration options
 */

// Old kConf path
$kConfPath = '@APP_DIR@/alpha/config/kConf.php';
if( ! file_exists( $kConfPath ) ) {
        // New kConf path
        $kConfPath = '@APP_DIR@/infra/kConf.php';
        if( ! file_exists( $kConfPath ) ) {
                die('Error: Unable to find kConf.php at ' . $kConfPath);
        }
}
// Load borhan configuration file
require_once( $kConfPath );

$kConf = new kConf();

// Borhan HTML5lib Version
$wgBorhanVersion = basename(getcwd()); // Gets the version by the folder name

// The default Borhan service url:
$wgBorhanServiceUrl = wgGetUrl('cdn_api_host');
// Default Borhan CDN url:
$wgBorhanCDNUrl = wgGetUrl('cdn_host');
// Default Stats URL
$wgBorhanStatsServiceUrl = wgGetUrl('stats_host');
// Default Live Stats URL
$wgBorhanLiveStatsServiceUrl = wgGetUrl('live_stats_host');
// Default Borhan Analytics URL
$wgBorhanAnalyticsServiceUrl = wgGetUrl('analytics_host');

// SSL host names
if( $wgHTTPProtocol == 'https' ){
        $wgBorhanServiceUrl = wgGetUrl('cdn_api_host_https');
        $wgBorhanCDNUrl = wgGetUrl('cdn_host_https');
        $wgBorhanStatsServiceUrl = wgGetUrl('stats_host_https');
	$wgBorhanLiveStatsServiceUrl = wgGetUrl('live_stats_host_https');
	$wgBorhanAnalyticsServiceUrl = wgGetUrl('analytics_host_https');
}

// Default Asset CDN Path (used in ResouceLoader.php):
$wgCDNAssetPath = $wgBorhanCDNUrl;

// Default Borhan Cache Path
$wgScriptCacheDirectory = $kConf->get('cache_root_path') . '/html5/' . $wgBorhanVersion;

$wgLoadScript = $wgBorhanServiceUrl . '/html5/html5lib/' . $wgBorhanVersion . '/load.php';
$wgResourceLoaderUrl = $wgLoadScript;

// Salt for proxy the user IP address to Borhan API
if( $kConf->hasParam('remote_addr_header_salt') ) {
        $wgBorhanRemoteAddressSalt = $kConf->get('remote_addr_header_salt');
}

// Disable Apple HLS if defined in kConf
if( $kConf->hasParam('use_apple_adaptive') ) {
        $wgBorhanUseAppleAdaptive = $kConf->get('use_apple_adaptive');
}

// Get Borhan Supported API Features
if( $kConf->hasParam('features') ) {
        $wgBorhanApiFeatures = $kConf->get('features');
}

// Allow Iframe to connect remote service
$wgBorhanAllowIframeRemoteService = true;

// Set debug for true (testing only)
$wgEnableScriptDebug = false;

// Get PlayReady License URL
if( $kConf->hasMap('playReady') ) {
        $playReadyMap = $kConf->getMap('playReady');
        if($playReadyMap)
                $wgBorhanLicenseServerUrl = $playReadyMap['license_server_url'];
}

// A helper function to get full URL of host
function wgGetUrl( $hostKey = null ) {
        global $wgHTTPProtocol, $wgServerPort, $kConf;
        if( $hostKey && $kConf->hasParam($hostKey) ) {
                return $wgHTTPProtocol . '://' . $kConf->get($hostKey);
        }
        return null;
}
