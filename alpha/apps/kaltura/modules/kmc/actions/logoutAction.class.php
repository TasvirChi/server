<?php
/**
 * @package    Core
 * @subpackage BMC
 */
class logoutAction extends borhanAction
{
	public function execute ( ) 
	{
		$ksStr = $this->getP("ks");
		if($ksStr) {
			$ksObj = null;
			try
			{
				$ksObj = ks::fromSecureString($ksStr);
			}
			catch(Exception $e)
			{				
			}
				
			if ($ksObj)
			{
				$partner = PartnerPeer::retrieveByPK($ksObj->partner_id);
				if (!$partner)
					KExternalErrors::dieError(KExternalErrors::PARTNER_NOT_FOUND);
						
				if (!$partner->validateApiAccessControl())
					KExternalErrors::dieError(KExternalErrors::SERVICE_ACCESS_CONTROL_RESTRICTED);
				
				$ksObj->kill();
			}
			BorhanLog::info("Killing session with ks - [$ksStr], decoded - [".base64_decode($ksStr)."]");
		}
		else {
			BorhanLog::err('logoutAction called with no KS');
		}
		
		setcookie('pid', "", 0, "/");
		setcookie('subpid', "", 0, "/");
		setcookie('bmcks', "", 0, "/");

		return sfView::NONE; //redirection to bmc/bmc is done from java script
	}
}
