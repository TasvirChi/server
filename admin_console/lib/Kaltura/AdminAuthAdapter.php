<?php
/**
 * @package Admin
 * @subpackage Authentication
 */
class Borhan_AdminAuthAdapter extends Infra_AuthAdapter
{
	/* (non-PHPdoc)
	 * @see Infra_AuthAdapter::getUserIdentity()
	 */
	protected function getUserIdentity(Borhan_Client_Type_User $user=null, $ks=null, $partnerId=null)
	{
		return new Borhan_AdminUserIdentity($user, $ks, $this->timezoneOffset, $partnerId);
	}
}
