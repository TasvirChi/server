<?php
require_once ( MODULES . "/partnerservices2/actions/startsessionAction.class.php" );
require_once ( MODULES . "/partnerservices2/actions/addkshowAction.class.php" );
class myPartnerServicesClient
{
	public static function createBorhanSession ( $uid, $privileges = null)
	{
		$borhan_services = new startsessionAction();
		
		$params = array ( "format" => borhanWebserviceRenderer::RESPONSE_TYPE_PHP_ARRAY , 
			"partner_id" => 0 , "subp_id" => 100 , "uid" => $uid , "secret" => "11111" );
		
		if ($privileges)
			$params["privileges"] = $privileges;
		
		$borhan_services->setInputParams( $params );
		$result = $borhan_services->internalExecute () ;
		return @$result["result"]["ks"];		
	}
	
	public static function createKshow ( $ks , $uid , $name , $partner_id = 0 , $subp_id = 100, $extra_params = null )
	{
		$borhan_services = new addkshowAction();
		
		$params = array ( "format" => borhanWebserviceRenderer::RESPONSE_TYPE_RAW , 
			"partner_id" => $partner_id , "subp_id" => $subp_id , "uid" => $uid , "ks" => $ks , "kshow_name" => $name ,
			"allow_duplicate_names" => "1" ) ;
		if ( $extra_params ) $params = array_merge( $params , $extra_params );
		
		$borhan_services->setInputParams( $params );
		$result = $borhan_services->internalExecute ( ) ;
		
		$kshow_wrapper = @$result["result"]["kshow"];
		
		if ( $kshow_wrapper )
		{
			$kshow = $kshow_wrapper->getWrappedObj();
			return 	$kshow	;
		}
		else
		{
			return null;
		}
	}
	

}
?>