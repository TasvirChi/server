<?php
/**
 * @package Core
 * @subpackage ExternalServices
 */
class myBorhanPartnerKshowServices extends myBorhanKshowServices implements IMediaSource
{
	const AUTH_SALT = "myBorhanPartnerKshowServices:gogog123";
	const AUTH_INTERVAL = 3600;
	
	protected $id = entry::ENTRY_MEDIA_SOURCE_BORHAN_PARTNER_KSHOW;
	
	private static $NEED_MEDIA_INFO = "0";
	
	// assume the extraData is the partner_id to be searched 
	protected function getKshowFilter ( $extraData )
	{
		$filter = new kshowFilter ();
		// This is the old way to search within a partner
//		$entry_filter->setByName ( "_eq_partner_id" , $extraData );

		// this is the better way -
		$filter->setPartnerSearchScope( $extraData );
		return $filter;
	}
}
