<style>
body { background-color:#272929 !important; background-image:none !important;}
div#login { width:500px; margin: 0 auto; text-align:center;}
</style>
<link rel="stylesheet" type="text/css" media="screen" href="/lib/css/bmc5.css" />
<div id="bmcHeader">
	<?php if( $logoUrl ) { ?>
	<img src="<?php echo $logoUrl; ?>" />
	<?php } else { ?>
	<img src="/lib/images/bmc/logo_bmc.png" alt="Borhan CMS" />
	<?php } ?>
	<div id="langIcon" style="display: none"></div>
	<div id="user_links" style="right: 36px">
    	<a href="/content/docs/pdf/BMC_User_Manual.pdf" target="_blank">User Manual</a>
	</div> 
</div><!-- end bmcHeader -->

<div id="langMenu"></div>

<div id="login">
	<div id="notSupported">Thank you for your logging into the Borhan Management Console.<br />The BMC is no longer supported in Internet Explorer 7.<br />Please upgrade your Internet Explorer to a higher version or browse to the BMC from another browser.</div>
    <div id="login_swf"><img src="/lib/images/bmc/flash.jpg" alt="Install Flash Player" /><span>You must have flash installed. <a href="http://get.adobe.com/flashplayer/" target="_blank">click here to download</a></span></div>
</div>

<script type="text/javascript">
// Prevent the page to be framed
if(top != window) { top.location = window.location; }
// Options
var options = {
	secureLogin: <?php echo ($securedLogin) ? 'true' : 'false'; ?>,
	enableLanguageMenu: "<?php echo 'true'; ?>",
	swfUrl: "<?php echo $swfUrl; ?>",
	flashVars: {
		host: "<?php echo $www_host; ?>",
		displayErrorFromServer: "<?php echo ($displayErrorFromServer)? 'true': 'false'; ?>",
		visibleSignup: "<?php echo (kConf::get('bmc_login_show_signup_link'))? 'true': 'false'; ?>",
		hashKey: "<?php echo (isset($setPassHashKey) && $setPassHashKey) ? $setPassHashKey : ''; ?>",
		errorCode: "<?php echo (isset($hashKeyErrorCode) && $hashKeyErrorCode) ? $hashKeyErrorCode : ''; ?>"
	}
};
</script>
<script src="/lib/js/bmc/6.0.10/langMenu.min.js"></script>
<script type="text/javascript" src="/lib/js/bmc.login.js"></script>