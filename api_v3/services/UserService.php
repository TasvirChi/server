<?php
/**
 * Manage partner users on Borhan's side
 * The userId in borhan is the unique Id in the partner's system, and the [partnerId,Id] couple are unique key in borhan's DB
 *
 * @service user
 * @package api
 * @subpackage services
 */
class UserService extends BorhanBaseUserService 
{

	/**
	 * Adds a new user to an existing account in the Borhan database.
	 * Input param $id is the unique identifier in the partner's system.
	 *
	 * @action add
	 * @param BorhanUser $user The new user
	 * @return BorhanUser The new user
	 *
	 * @throws BorhanErrors::DUPLICATE_USER_BY_ID
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::UNKNOWN_PARTNER_ID
	 * @throws BorhanErrors::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::DUPLICATE_USER_BY_LOGIN_ID
	 * @throws BorhanErrors::USER_ROLE_NOT_FOUND
	 */
	function addAction(BorhanUser $user)
	{
		if (!preg_match(kuser::PUSER_ID_REGEXP, $user->id))
		{
			throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'id');
		}

		if ($user instanceof BorhanAdminUser)
		{
			$user->isAdmin = true;
		}
		$user->partnerId = $this->getPartnerId();


		$lockKey = "user_add_" . $this->getPartnerId() . $user->id;
		return kLock::runLocked($lockKey, array($this, 'adduserImpl'), array($user));
	}
	
	function addUserImpl($user)
	{
		$dbUser = null;
		$dbUser = $user->toObject($dbUser);
		try {
			$checkPasswordStructure = isset($user->password) ? true : false;
			$dbUser = kuserPeer::addUser($dbUser, $user->password, $checkPasswordStructure);
		}
		
		catch (kUserException $e) {
			$code = $e->getCode();
			if ($code == kUserException::USER_ALREADY_EXISTS) {
				throw new BorhanAPIException(BorhanErrors::DUPLICATE_USER_BY_ID, $user->id); //backward compatibility
			}
			if ($code == kUserException::LOGIN_ID_ALREADY_USED) {
				throw new BorhanAPIException(BorhanErrors::DUPLICATE_USER_BY_LOGIN_ID, $user->email); //backward compatibility
			}
			else if ($code == kUserException::USER_ID_MISSING) {
				throw new BorhanAPIException(BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL, $user->getFormattedPropertyNameWithClassName('id'));
			}
			else if ($code == kUserException::INVALID_EMAIL) {
				throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'email');
			}
			else if ($code == kUserException::INVALID_PARTNER) {
				throw new BorhanAPIException(BorhanErrors::UNKNOWN_PARTNER_ID);
			}
			else if ($code == kUserException::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED) {
				throw new BorhanAPIException(BorhanErrors::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED);
			}
			else if ($code == kUserException::PASSWORD_STRUCTURE_INVALID) {
				$partner = $dbUser->getPartner();
				$invalidPasswordStructureMessage='';
				if($partner && $partner->getInvalidPasswordStructureMessage())
					$invalidPasswordStructureMessage = $partner->getInvalidPasswordStructureMessage();
				throw new BorhanAPIException(BorhanErrors::PASSWORD_STRUCTURE_INVALID,$invalidPasswordStructureMessage);
			}
			throw $e;			
		}
		catch (kPermissionException $e)
		{
			$code = $e->getCode();
			if ($code == kPermissionException::ROLE_ID_MISSING) {
				throw new BorhanAPIException(BorhanErrors::ROLE_ID_MISSING);
			}
			if ($code == kPermissionException::ONLY_ONE_ROLE_PER_USER_ALLOWED) {
				throw new BorhanAPIException(BorhanErrors::ONLY_ONE_ROLE_PER_USER_ALLOWED);
			}
			else if ($code == kPermissionException::USER_ROLE_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::USER_ROLE_NOT_FOUND);
			}
			throw $e;
		}

		$newUser = new BorhanUser();
		$newUser->fromObject($dbUser, $this->getResponseProfile());
		
		return $newUser;
	}

	/**
	 * Updates an existing user object.
	 * You can also use this action to update the userId.
	 * 
	 * @action update
	 * @param string $userId The user's unique identifier in the partner's system
	 * @param BorhanUser $user The user parameters to update
	 * @return BorhanUser The updated user object
	 *
	 * @throws BorhanErrors::INVALID_USER_ID
	 * @throws BorhanErrors::CANNOT_DELETE_OR_BLOCK_ROOT_ADMIN_USER
	 * @throws BorhanErrors::USER_ROLE_NOT_FOUND
	 * @throws BorhanErrors::ACCOUNT_OWNER_NEEDS_PARTNER_ADMIN_ROLE
	 */
	public function updateAction($userId, BorhanUser $user)
	{		
		$dbUser = kuserPeer::getKuserByPartnerAndUid($this->getPartnerId(), $userId);
		
		if (!$dbUser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);

		if ($dbUser->getIsAdmin() && !is_null($user->isAdmin) && !$user->isAdmin) {
			throw new BorhanAPIException(BorhanErrors::CANNOT_SET_ROOT_ADMIN_AS_NO_ADMIN);
		}
			
		// update user
		try
		{
			if (!is_null($user->roleIds)) {
				UserRolePeer::testValidRolesForUser($user->roleIds, $this->getPartnerId());
				if ($user->roleIds != $dbUser->getRoleIds() &&
					$dbUser->getId() == $this->getKuser()->getId()) {
					throw new BorhanAPIException(BorhanErrors::CANNOT_CHANGE_OWN_ROLE);
				}
			}
			if (!is_null($user->id) && $user->id != $userId) {
				if(!preg_match(kuser::PUSER_ID_REGEXP, $user->id)) {
					throw new BorhanAPIException(BorhanErrors::INVALID_FIELD_VALUE, 'id');
				} 
				
				$existingUser = kuserPeer::getKuserByPartnerAndUid($this->getPartnerId(), $user->id);
				if ($existingUser) {
					throw new BorhanAPIException(BorhanErrors::DUPLICATE_USER_BY_ID, $user->id);
				}
			}			
			$dbUser = $user->toUpdatableObject($dbUser);
			$dbUser->save();
		}
		catch (kPermissionException $e)
		{
			$code = $e->getCode();
			if ($code == kPermissionException::ROLE_ID_MISSING) {
				throw new BorhanAPIException(BorhanErrors::ROLE_ID_MISSING);
			}
			if ($code == kPermissionException::ONLY_ONE_ROLE_PER_USER_ALLOWED) {
				throw new BorhanAPIException(BorhanErrors::ONLY_ONE_ROLE_PER_USER_ALLOWED);
			}
			if ($code == kPermissionException::USER_ROLE_NOT_FOUND) {
				throw new BorhanAPIException(BorhanErrors::USER_ROLE_NOT_FOUND);
			}
			if ($code == kPermissionException::ACCOUNT_OWNER_NEEDS_PARTNER_ADMIN_ROLE) {
				throw new BorhanAPIException(BorhanErrors::ACCOUNT_OWNER_NEEDS_PARTNER_ADMIN_ROLE);
			}
			throw $e;
		}
		catch (kUserException $e) {
			$code = $e->getCode();
			if ($code == kUserException::CANNOT_DELETE_OR_BLOCK_ROOT_ADMIN_USER) {
				throw new BorhanAPIException(BorhanErrors::CANNOT_DELETE_OR_BLOCK_ROOT_ADMIN_USER);
			}
			throw $e;			
		}
				
		$user = new BorhanUser();
		$user->fromObject($dbUser, $this->getResponseProfile());
		
		return $user;
	}

	
	/**
	 * Retrieves a user object for a specified user ID.
	 * 
	 * @action get
	 * @param string $userId The user's unique identifier in the partner's system
	 * @return BorhanUser The specified user object
	 *
	 * @throws BorhanErrors::INVALID_USER_ID
	 */		
	public function getAction($userId = null)
	{
	    if (is_null($userId) || $userId == '')
	    {
            $userId = kCurrentContext::$ks_uid;	        
	    }

		if (!kCurrentContext::$is_admin_session && kCurrentContext::$ks_uid != $userId)
			throw new BorhanAPIException(BorhanErrors::CANNOT_RETRIEVE_ANOTHER_USER_USING_NON_ADMIN_SESSION, $userId);

		$dbUser = kuserPeer::getKuserByPartnerAndUid($this->getPartnerId(), $userId);
	
		if (!$dbUser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);

		$user = new BorhanUser();
		$user->fromObject($dbUser, $this->getResponseProfile());
		
		return $user;
	}
	
	/**
	 * Retrieves a user object for a user's login ID and partner ID.
	 * A login ID is the email address used by a user to log into the system.
	 * 
	 * @action getByLoginId
	 * @param string $loginId The user's email address that identifies the user for login
	 * @return BorhanUser The user object represented by the login and partner IDs
	 * 
	 * @throws BorhanErrors::LOGIN_DATA_NOT_FOUND
	 * @throws BorhanErrors::USER_NOT_FOUND
	 */
	public function getByLoginIdAction($loginId)
	{
		$loginData = UserLoginDataPeer::getByEmail($loginId);
		if (!$loginData) {
			throw new BorhanAPIException(BorhanErrors::LOGIN_DATA_NOT_FOUND);
		}
		
		$kuser = kuserPeer::getByLoginDataAndPartner($loginData->getId(), $this->getPartnerId());
		if (!$kuser) {
			throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
		}

		// users that are not publisher administrator are only allowed to get their own object   
		if ($kuser->getId() != kCurrentContext::getCurrentKsKuserId() && !in_array(PermissionName::MANAGE_ADMIN_USERS, kPermissionManager::getCurrentPermissions()))
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $loginId);
		
		$user = new BorhanUser();
		$user->fromObject($kuser, $this->getResponseProfile());
		
		return $user;
	}

	/**
	 * Deletes a user from a partner account.
	 * 
	 * @action delete
	 * @param string $userId The user's unique identifier in the partner's system
	 * @return BorhanUser The deleted user object
	 *
	 * @throws BorhanErrors::INVALID_USER_ID
	 */		
	public function deleteAction($userId)
	{
		$dbUser = kuserPeer::getKuserByPartnerAndUid($this->getPartnerId(), $userId);
	
		if (!$dbUser) {
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
		}
					
		try {
			$dbUser->setStatus(BorhanUserStatus::DELETED);
		}
		catch (kUserException $e) {
			$code = $e->getCode();
			if ($code == kUserException::CANNOT_DELETE_OR_BLOCK_ROOT_ADMIN_USER) {
				throw new BorhanAPIException(BorhanErrors::CANNOT_DELETE_OR_BLOCK_ROOT_ADMIN_USER);
			}
			throw $e;			
		}
		$dbUser->save();
		
		$user = new BorhanUser();
		$user->fromObject($dbUser, $this->getResponseProfile());
		
		return $user;
	}
	
	/**
	 * Lists user objects that are associated with an account.
	 * Blocked users are listed unless you use a filter to exclude them.
	 * Deleted users are not listed unless you use a filter to include them.
	 * 
	 * @action list
	 * @param BorhanUserFilter $filter A filter used to exclude specific types of users
	 * @param BorhanFilterPager $pager A limit for the number of records to display on a page
	 * @return BorhanUserListResponse The list of user objects
	 */
	public function listAction(BorhanUserFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if (!$filter)
			$filter = new BorhanUserFilter();
			
		if(!$pager)
			$pager = new BorhanFilterPager();
			
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}
	
	/**
	 * Notifies that a user is banned from an account.
	 * 
	 * @action notifyBan
	 * @param string $userId The user's unique identifier in the partner's system
	 *
	 * @throws BorhanErrors::INVALID_USER_ID
	 */		
	public function notifyBan($userId)
	{
		$dbUser = kuserPeer::getKuserByPartnerAndUid($this->getPartnerId(), $userId);
		if (!$dbUser)
			throw new BorhanAPIException(BorhanErrors::INVALID_USER_ID, $userId);
		
		myNotificationMgr::createNotification(kNotificationJobData::NOTIFICATION_TYPE_USER_BANNED, $dbUser);
	}

	/**
	 * Logs a user into a partner account with a partner ID, a partner user ID (puser), and a user password.
	 * 
	 * @action login
	 * @param int $partnerId The identifier of the partner account
	 * @param string $userId The user's unique identifier in the partner's system
	 * @param string $password The user's password
	 * @param int $expiry The requested time (in seconds) before the generated KS expires (By default, a KS expires after 24 hours).
	 * @param string $privileges Special privileges
	 * @return string A session KS for the user
	 *
	 * @throws BorhanErrors::USER_NOT_FOUND
	 * @throws BorhanErrors::USER_WRONG_PASSWORD
	 * @throws BorhanErrors::INVALID_PARTNER_ID
	 * @throws BorhanErrors::LOGIN_RETRIES_EXCEEDED
	 * @throws BorhanErrors::LOGIN_BLOCKED
	 * @throws BorhanErrors::PASSWORD_EXPIRED
	 * @throws BorhanErrors::USER_IS_BLOCKED
	 */		
	public function loginAction($partnerId, $userId, $password, $expiry = 86400, $privileges = '*')
	{
		// exceptions might be thrown
		return parent::loginImpl($userId, null, $password, $partnerId, $expiry, $privileges);
	}
	
	/**
	 * Logs a user into a partner account with a user login ID and a user password.
	 * 
	 * @action loginByLoginId
	 * 
	 * @param string $loginId The user's email address that identifies the user for login
	 * @param string $password The user's password
	 * @param int $partnerId The identifier of the partner account
	 * @param int $expiry The requested time (in seconds) before the generated KS expires (By default, a KS expires after 24 hours).
	 * @param string $privileges Special privileges
	 * @param string $otp the user's one-time password
	 * @return string A session KS for the user
	 *
	 * @throws BorhanErrors::USER_NOT_FOUND
	 * @throws BorhanErrors::USER_WRONG_PASSWORD
	 * @throws BorhanErrors::INVALID_PARTNER_ID
	 * @throws BorhanErrors::LOGIN_RETRIES_EXCEEDED
	 * @throws BorhanErrors::LOGIN_BLOCKED
	 * @throws BorhanErrors::PASSWORD_EXPIRED
	 * @throws BorhanErrors::USER_IS_BLOCKED
	 */		
	public function loginByLoginIdAction($loginId, $password, $partnerId = null, $expiry = 86400, $privileges = '*', $otp = null)
	{
		// exceptions might be thrown
		return parent::loginImpl(null, $loginId, $password, $partnerId, $expiry, $privileges, $otp);
	}
	
	
	/**
	 * Updates a user's login data: email, password, name.
	 * 
	 * @action updateLoginData
	 * 
	 * @param string $oldLoginId The user's current email address that identified the user for login
	 * @param string $password The user's current email address that identified the user for login
	 * @param string $newLoginId Optional, The user's email address that will identify the user for login
	 * @param string $newPassword Optional, The user's new password
	 * @param string $newFirstName Optional, The user's new first name
	 * @param string $newLastName Optional, The user's new last name
	 *
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::LOGIN_DATA_NOT_FOUND
	 * @throws BorhanErrors::WRONG_OLD_PASSWORD
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::LOGIN_ID_ALREADY_USED
	 */
	public function updateLoginDataAction( $oldLoginId , $password , $newLoginId = "" , $newPassword = "", $newFirstName = null, $newLastName = null)
	{	
		return parent::updateLoginDataImpl($oldLoginId , $password , $newLoginId, $newPassword, $newFirstName, $newLastName);
	}
	
	/**
	 * Reset user's password and send the user an email to generate a new one.
	 * 
	 * @action resetPassword
	 * 
	 * @param string $email The user's email address (login email)
	 *
	 * @throws BorhanErrors::LOGIN_DATA_NOT_FOUND
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::INVALID_FIELD_VALUE
	 * @throws BorhanErrors::LOGIN_ID_ALREADY_USED
	 */	
	public function resetPasswordAction($email)
	{
		return parent::resetPasswordImpl($email);
	}
	
	/**
	 * Set initial users password
	 * 
	 * @action setInitialPassword
	 * 
	 * @param string $hashKey The hash key used to identify the user (retrieved by email)
	 * @param string $newPassword The new password to set for the user
	 *
	 * @throws BorhanErrors::LOGIN_DATA_NOT_FOUND
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::NEW_PASSWORD_HASH_KEY_EXPIRED
	 * @throws BorhanErrors::NEW_PASSWORD_HASH_KEY_INVALID
	 * @throws BorhanErrors::PASSWORD_ALREADY_USED
	 * @throws BorhanErrors::INTERNAL_SERVERL_ERROR
	 */	
	public function setInitialPasswordAction($hashKey, $newPassword)
	{
		return parent::setInitialPasswordImpl($hashKey, $newPassword);
	}
	
	/**
	 * Enables a user to log into a partner account using an email address and a password
	 * 
	 * @action enableLogin
	 * 
	 * @param string $userId The user's unique identifier in the partner's system
	 * @param string $loginId The user's email address that identifies the user for login
	 * @param string $password The user's password
	 * @return BorhanUser The user object represented by the user and login IDs
	 * 
	 * @throws BorhanErrors::USER_LOGIN_ALREADY_ENABLED
	 * @throws BorhanErrors::USER_NOT_FOUND
	 * @throws BorhanErrors::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED
	 * @throws BorhanErrors::PASSWORD_STRUCTURE_INVALID
	 * @throws BorhanErrors::LOGIN_ID_ALREADY_USED
	 *
	 */	
	public function enableLoginAction($userId, $loginId, $password = null)
	{		
		try
		{
			$user = kuserPeer::getKuserByPartnerAndUid($this->getPartnerId(), $userId);
			
			if (!$user)
			{
				throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
			}
			
			if (!$user->getIsAdmin() && !$password) {
				throw new BorhanAPIException(BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL, 'password');
			}
			
			// Gonen 2011-05-29 : NOTE - 3rd party uses this action and expect that email notification will not be sent by default
			// if this call ever changes make sure you do not change default so mails are sent.
			$user->enableLogin($loginId, $password, true);	
			$user->save();
		}
		catch (Exception $e)
		{
			$code = $e->getCode();
			if ($code == kUserException::USER_LOGIN_ALREADY_ENABLED) {
				throw new BorhanAPIException(BorhanErrors::USER_LOGIN_ALREADY_ENABLED);
			}
			if ($code == kUserException::INVALID_EMAIL) {
				throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
			}
			else if ($code == kUserException::INVALID_PARTNER) {
				throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
			}
			else if ($code == kUserException::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED) {
				throw new BorhanAPIException(BorhanErrors::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED);
			}
			else if ($code == kUserException::PASSWORD_STRUCTURE_INVALID) {
				throw new BorhanAPIException(BorhanErrors::PASSWORD_STRUCTURE_INVALID);
			}
			else if ($code == kUserException::LOGIN_ID_ALREADY_USED) {
				throw new BorhanAPIException(BorhanErrors::LOGIN_ID_ALREADY_USED);
			}
			else if ($code == kUserException::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED) {
				throw new BorhanAPIException(BorhanErrors::ADMIN_LOGIN_USERS_QUOTA_EXCEEDED);
			}
			throw $e;
		}
		
		$apiUser = new BorhanUser();
		$apiUser->fromObject($user, $this->getResponseProfile());
		return $apiUser;
	}
	
	
	
	/**
	 * Disables a user's ability to log into a partner account using an email address and a password.
	 * You may use either a userId or a loginId parameter for this action.
	 * 
	 * @action disableLogin
	 * 
	 * @param string $userId The user's unique identifier in the partner's system
	 * @param string $loginId The user's email address that identifies the user for login
	 * 
	 * @return BorhanUser The user object represented by the user and login IDs
	 * 
	 * @throws BorhanErrors::USER_LOGIN_ALREADY_DISABLED
	 * @throws BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL
	 * @throws BorhanErrors::USER_NOT_FOUND
	 * @throws BorhanErrors::CANNOT_DISABLE_LOGIN_FOR_ADMIN_USER
	 *
	 */	
	public function disableLoginAction($userId = null, $loginId = null)
	{
		if (!$loginId && !$userId)
		{
			throw new BorhanAPIException(BorhanErrors::PROPERTY_VALIDATION_CANNOT_BE_NULL, 'userId');
		}
		
		$user = null;
		try
		{
			if ($loginId)
			{
				$loginData = UserLoginDataPeer::getByEmail($loginId);
				if (!$loginData) {
					throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
				}
				$user = kuserPeer::getByLoginDataAndPartner($loginData->getId(), $this->getPartnerId());
			}
			else
			{
				$user = kuserPeer::getKuserByPartnerAndUid($this->getPArtnerId(), $userId);
			}
			
			if (!$user)
			{
				throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
			}
			
			$user->disableLogin();
		}
		catch (Exception $e)
		{
			$code = $e->getCode();
			if ($code == kUserException::USER_LOGIN_ALREADY_DISABLED) {
				throw new BorhanAPIException(BorhanErrors::USER_LOGIN_ALREADY_DISABLED);
			}
			if ($code == kUserException::CANNOT_DISABLE_LOGIN_FOR_ADMIN_USER) {
				throw new BorhanAPIException(BorhanErrors::CANNOT_DISABLE_LOGIN_FOR_ADMIN_USER);
			}
			throw $e;
		}
		
		$apiUser = new BorhanUser();
		$apiUser->fromObject($user, $this->getResponseProfile());
		return $apiUser;
	}
	
	/**
	 * Index an entry by id.
	 * 
	 * @action index
	 * @param string $id
	 * @param bool $shouldUpdate
	 * @return string 
	 * @throws BorhanErrors::USER_NOT_FOUND
	 */
	function indexAction($id, $shouldUpdate = true)
	{
		$kuser = kuserPeer::getActiveKuserByPartnerAndUid(kCurrentContext::getCurrentPartnerId(), $id);
		
		if (!$kuser)
			throw new BorhanAPIException(BorhanErrors::USER_NOT_FOUND);
		
		$kuser->indexToSearchIndex();
			
		return $kuser->getPuserId();
	}


}