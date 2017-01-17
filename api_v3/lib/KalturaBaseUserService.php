<?php
/**
 * @package api
 * @subpackage services
 */
class BorhanBaseUserService extends BorhanBaseService 
{
	
	protected function partnerRequired($actionName)
	{
		$actionName = strtolower($actionName);
		if ($actionName === 'loginbyloginid') {
			return false;
		}
		if ($actionName === 'updatelogindata') {
			return false;
		}
		if ($actionName === 'resetpassword') {
			return false;
		}
		if ($actionName === 'setinitialpassword') {
			return false;
		}
		return parent::partnerRequired($actionName);
	}
	
	
	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService ($serviceId, $serviceName, $actionName);
		$this->applyPartnerFilterForClass('kuser');
	}	
	
	/**
	 * Update admin user password and email
	 * 
	 * @param string $email
	 * @param string $password
	 * @param string $newEmail Optional, provide only when you want to update the email
	 * @param string $newPassword
	 *
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::LOGIN_DATA_NOT_FOUND
	 * @throws BorhanErrors::WRONG_OLD_PASSWORD
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::LOGIN_ID_ALREADY_USED
	 */
	protected function updateLoginDataImpl( $email , $password , $newEmail = "" , $newPassword = "", $newFirstName, $newLastName)
	{
		BorhanResponseCacher::disableCache();

		$this->validateApiAccessControlByEmail($email);
		
		if ($newEmail != "")
		{
			if(!kString::isEmailString($newEmail))
				throw new BorhanAPIException ( BorhanErrors::INVALID_FIELD_VALUE, "newEmail" );
		}

		try {
			UserLoginDataPeer::updateLoginData ( $email , $password, $newEmail, $newPassword, $newFirstName, $newLastName);
		}
		catch (kUserException $e) {
			$code = $e->getCode();
			if ($code == kUserException::LOGIN_DATA_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_DATA_NOT_FOUND);
			}
			else if ($code == kUserException::WRONG_PASSWORD) {
				if($password == $newPassword)
					throw new BorhanAPIException(BorhanErrors::USER_WRONG_PASSWORD);
				else
					throw new BorhanAPIException(BorhanErrors::WRONG_OLD_PASSWORD);
			}
			else if ($code == kUserException::PASSWORD_STRUCTURE_INVALID) {
				$c = new Criteria(); 
				$c->add(UserLoginDataPeer::LOGIN_EMAIL, $email ); 
				$loginData = UserLoginDataPeer::doSelectOne($c);
				$invalidPasswordStructureMessage = $loginData->getInvalidPasswordStructureMessage();
				throw new BorhanAPIException(BorhanErrors::PASSWORD_STRUCTURE_INVALID,$invalidPasswordStructureMessage);
			}
			else if ($code == kUserException::PASSWORD_ALREADY_USED) {
				throw new BorhanAPIException(BorhanErrors::PASSWORD_ALREADY_USED);
			}
			else if ($code == kUserException::INVALID_EMAIL) {
				throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'email');
			}
			else if ($code == kUserException::LOGIN_ID_ALREADY_USED) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_ID_ALREADY_USED);
			}
			throw $e;			
		}
	}

	
	/**
	 * Reset admin user password and send it to the users email address
	 * 
	 * @param string $email
	 *
	 * @throws BorhanErrors::LOGIN_DATA_NOT_FOUND
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::LOGIN_ID_ALREADY_USED
	 */	
	protected function resetPasswordImpl($email)
	{
		BorhanResponseCacher::disableCache();
		
		$this->validateApiAccessControlByEmail($email);
		
		try {
			$new_password = UserLoginDataPeer::resetUserPassword($email);
		}
		catch (kUserException $e) {
			$code = $e->getCode();
			if ($code == kUserException::LOGIN_DATA_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_DATA_NOT_FOUND, "user not found");
			}
			else if ($code == kUserException::PASSWORD_STRUCTURE_INVALID) {
				throw new BorhanAPIException(BorhanErrors::PASSWORD_STRUCTURE_INVALID);
			}
			else if ($code == kUserException::PASSWORD_ALREADY_USED) {
				throw new BorhanAPIException(BorhanErrors::PASSWORD_ALREADY_USED);
			}
			else if ($code == kUserException::INVALID_EMAIL) {
				throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'email');
			}
			else if ($code == kUserException::LOGIN_ID_ALREADY_USED) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_ID_ALREADY_USED);
			}
			throw $e;			
		}	
		
		if (!$new_password)
			throw new BorhanAPIException(BorhanErrors::LOGIN_DATA_NOT_FOUND, "user not found" );
	}

	
	/**
	 * Get a session using user email and password
	 * 
	 * @param string $puserId
	 * @param string $loginEmail
	 * @param string $password
	 * @param int $partnerId
	 * @param int $expiry
	 * @param string $privileges
	 * @param string $otp
	 * 
	 * @return string KS
	 *
	 * @throws BorhanErrors::USER_NOT_FOUND
	 * @thrown BorhanErrors::LOGIN_RETRIES_EXCEEDED
	 * @thrown BorhanErrors::LOGIN_BLOCKED
	 * @thrown BorhanErrors::PASSWORD_EXPIRED
	 * @thrown BorhanErrors::INVALID_PARTNER_ID
	 * @thrown BorhanErrors::INTERNAL_SERVERL_ERROR
	 * @throws BorhanErrors::USER_IS_BLOCKED
	 */		
	protected function loginImpl($puserId, $loginEmail, $password, $partnerId = null, $expiry = 86400, $privileges = '*', $otp = null)
	{
		BorhanResponseCacher::disableCache();
		myPartnerUtils::resetPartnerFilter('kuser');
		kuserPeer::setUseCriteriaFilter(true);
		
		// if a KS of a specific partner is used, don't allow logging in to a different partner
		if ($this->getPartnerId() && $partnerId && $this->getPartnerId() != $partnerId) {
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $partnerId);
		}

		if ($loginEmail && !$partnerId) {
			$this->validateApiAccessControlByEmail($loginEmail);
		}
		
		try {
			if ($loginEmail) {
				$user = UserLoginDataPeer::userLoginByEmail($loginEmail, $password, $partnerId, $otp);
			}
			else {
				$user = kuserPeer::userLogin($puserId, $password, $partnerId);
			}
		}
		catch (kUserException $e) {
			$code = $e->getCode();
			if ($code == kUserException::LOGIN_DATA_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
			}
			if ($code == kUserException::USER_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
			}
			else if ($code == kUserException::LOGIN_RETRIES_EXCEEDED) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_RETRIES_EXCEEDED);
			}
			else if ($code == kUserException::LOGIN_BLOCKED) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_BLOCKED);
			}
			else if ($code == kUserException::PASSWORD_EXPIRED) {
				throw new BorhanAPIException(BorhanErrors::PASSWORD_EXPIRED);
			}
			else if ($code == kUserException::WRONG_PASSWORD) {
				throw new BorhanAPIException(BorhanErrors::USER_WRONG_PASSWORD);
			}
			else if ($code == kUserException::USER_IS_BLOCKED) {
				throw new BorhanAPIException(BorhanErrors::USER_IS_BLOCKED);
			}
			else if ($code == kUserException::INVALID_OTP) {
				throw new BorhanAPIException(BorhanErrors::INVALID_OTP);
			}
									
			throw new $e;
		}
		if (!$user) {
			throw new BorhanAPIException(BorhanErrors::LOGIN_DATA_NOT_FOUND);
		}		
		
		if ( ($partnerId && $user->getPartnerId() != $partnerId) ||
		     ($this->getPartnerId() && !$partnerId && $user->getPartnerId() != $this->getPartnerId()) ) {
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $partnerId);
		}			
		
		$partner = PartnerPeer::retrieveByPK($user->getPartnerId());
		
		if (!$partner || $partner->getStatus() == Partner::PARTNER_STATUS_FULL_BLOCK)
			throw new BorhanAPIException(BorhanErrors::INVALID_PARTNER_ID, $user->getPartnerId());
		
		$ks = null;
		
		$admin = $user->getIsAdmin() ? BorhanSessionType::ADMIN : BorhanSessionType::USER;
		// create a ks for this admin_kuser as if entered the admin_secret using the API
		kSessionUtils::createKSessionNoValidations ( $partner->getId() ,  $user->getPuserId() , $ks , $expiry , $admin , "" , $privileges );
		
		return $ks;
	}
	
	
	/**
	 * Set initial users password
	 * 
	 * @param string $hashKey
	 * @param string $newPassword new password to set
	 *
	 * @throws BorhanErrors::LOGIN_DATA_NOT_FOUND
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::NEW_PASSWORD_HASH_KEY_EXPIRED
	 * @throws BorhanErrors::NEW_PASSWORD_HASH_KEY_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::INTERNAL_SERVERL_ERROR
	 */	
	protected function setInitialPasswordImpl($hashKey, $newPassword)
	{
		BorhanResponseCacher::disableCache();
		
		try {
			$loginData = UserLoginDataPeer::isHashKeyValid($hashKey);
			if ($loginData)
				$this->validateApiAccessControl($loginData->getLastLoginPartnerId());
			$result = UserLoginDataPeer::setInitialPassword($hashKey, $newPassword);
		}
		catch (kUserException $e) {
			$code = $e->getCode();
			if ($code == kUserException::LOGIN_DATA_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_DATA_NOT_FOUND);
			}
			if ($code == kUserException::PASSWORD_STRUCTURE_INVALID) {
				$loginData = UserLoginDataPeer::isHashKeyValid($hashKey);
				$invalidPasswordStructureMessage = $loginData->getInvalidPasswordStructureMessage();
				throw new BorhanAPIException(BorhanErrors::PASSWORD_STRUCTURE_INVALID,$invalidPasswordStructureMessage);
			}
			if ($code == kUserException::NEW_PASSWORD_HASH_KEY_EXPIRED) {
				throw new BorhanAPIException(BorhanErrors::NEW_PASSWORD_HASH_KEY_EXPIRED);
			}
			if ($code == kUserException::NEW_PASSWORD_HASH_KEY_INVALID) {
				throw new BorhanAPIException(BorhanErrors::NEW_PASSWORD_HASH_KEY_INVALID);
			}
			if ($code == kUserException::PASSWORD_ALREADY_USED) {
				throw new BorhanAPIException(BorhanErrors::PASSWORD_ALREADY_USED);
			}
			
			throw $e;
		}
		if (!$result) {
			throw new BorhanAPIException(BorhanErrors::INTERNAL_SERVERL_ERROR);
		}
	}
	
	protected function validateApiAccessControlByEmail($email)
	{ 
		$loginData = UserLoginDataPeer::getByEmail($email);
		if ($loginData)
		{
			$this->validateApiAccessControl($loginData->getLastLoginPartnerId());
		}
	}
}
