<?php
/**
 * Manage details for the administrative user
 *
 * @service adminUser
 * @package api
 * @subpackage services
 * @deprecated use user service instead
 */
class AdminUserService extends BorhanBaseUserService 
{
	
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'updatePassword') {
			return false;
		}
		if ($actionName === 'resetPassword') {
			return false;
		}
		if ($actionName === 'login') {
			return false;
		}
		if ($actionName === 'setInitialPassword') {
			return false;
		}
		return parent::partnerRequired($actionName);
	}	
	

	/**
	 * keep backward compatibility with changed error codes
	 * @param BorhanAPIException $e
	 * @throws BorhanAPIException
	 */
	private function throwTranslatedException(BorhanAPIException $e)
	{
		$code = $e->getCode();
		if ($code == BorhanErrors::USER_NOT_FOUND) {
			throw new BorhanAPIException(BorhanErrors::ADMIN_KUSER_NOT_FOUND);
		}
		else if ($code == BorhanErrors::WRONG_OLD_PASSWORD) {
			throw new BorhanAPIException(BorhanErrors::ADMIN_KUSER_WRONG_OLD_PASSWORD, "wrong password" );
		}
		else if ($code == BorhanErrors::USER_WRONG_PASSWORD) {
			throw new BorhanAPIException(BorhanErrors::ADMIN_KUSER_NOT_FOUND);
		}
		else if ($code == BorhanErrors::LOGIN_DATA_NOT_FOUND) {
			throw new BorhanAPIException(BorhanErrors::ADMIN_KUSER_NOT_FOUND);
		}
		throw $e;
	}
	
	
	/**
	 * Update admin user password and email
	 * 
	 * @action updatePassword
	 * @param string $email
	 * @param string $password
	 * @param string $newEmail Optional, provide only when you want to update the email
	 * @param string $newPassword
	 * @return BorhanAdminUser
	 *
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::ADMIN_KUSER_WRONG_OLD_PASSWORD
	 * @throws BorhanErrors::ADMIN_KUSER_NOT_FOUND
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::LOGIN_ID_ALREADY_USED
	 * 
	 * @deprecated
	 */
	public function updatePasswordAction( $email , $password , $newEmail = "" , $newPassword = "" )
	{
		try
		{
			parent::updateLoginDataImpl($email, $password, $newEmail, $newPassword);
			
			// copy required parameters to a BorhanAdminUser object for backward compatibility
			$adminUser = new BorhanAdminUser();
			$adminUser->email = $newEmail ? $newEmail : $email;
			$adminUser->password = $newPassword ? $newPassword : $password;
			
			return $adminUser;
		}
		catch (BorhanAPIException $e) // keep backward compatibility with changed error codes
		{
			$this->throwTranslatedException($e);
		}
	}
	
	
	/**
	 * Reset admin user password and send it to the users email address
	 * 
	 * @action resetPassword
	 * @param string $email
	 *
	 * @throws BorhanErrors::ADMIN_KUSER_NOT_FOUND
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::LOGIN_ID_ALREADY_USED
	 */	
	public function resetPasswordAction($email)
	{
		try
		{
			return parent::resetPasswordImpl($email);
		}
		catch (BorhanAPIException $e) // keep backward compatibility with changed error codes
		{
			$this->throwTranslatedException($e);
		}
	}
	
	/**
	 * Get an admin session using admin email and password (Used for login to the BMC application)
	 * 
	 * @action login
	 * @param string $email
	 * @param string $password
	 * @param int $partnerId
	 * @return string
	 *
	 * @throws BorhanErrors::ADMIN_KUSER_NOT_FOUND
	 * @thrown BorhanErrors::INVALID_PARTNER_ID
	 * @thrown BorhanErrors::LOGIN_RETRIES_EXCEEDED
	 * @thrown BorhanErrors::LOGIN_BLOCKED
	 * @thrown BorhanErrors::PASSWORD_EXPIRED
	 * @thrown BorhanErrors::INVALID_PARTNER_ID
	 * @thrown BorhanErrors::INTERNAL_SERVERL_ERROR
	 */		
	public function loginAction($email, $password, $partnerId = null)
	{
		try
		{
			$ks = parent::loginImpl(null, $email, $password, $partnerId);
			$tempKs = kSessionUtils::crackKs($ks);
			if (!$tempKs->isAdmin()) {
				throw new BorhanAPIException(BorhanErrors::ADMIN_KUSER_NOT_FOUND); 
			}
			return $ks;
		}
		catch (BorhanAPIException $e) // keep backward compatibility with changed error codes
		{
			$this->throwTranslatedException($e);
		}
	}
	
	
	
	/**
	 * Set initial users password
	 * 
	 * @action setInitialPassword
	 * @param string $hashKey
	 * @param string $newPassword new password to set
	 *
	 * @throws BorhanErrors::ADMIN_KUSER_NOT_FOUND
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::NEW_PASSWORD_HASH_KEY_EXPIRED
	 * @throws BorhanErrors::NEW_PASSWORD_HASH_KEY_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::INTERNAL_SERVERL_ERROR
	 */	
	public function setInitialPasswordAction($hashKey, $newPassword)
	{
		try
		{
			return parent::setInitialPasswordImpl($hashKey, $newPassword);
		}
		catch (BorhanAPIException $e) // keep backward compatibility with changed error codes
		{
			$this->throwTranslatedException($e);
		}
	}
	
	
}