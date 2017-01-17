<?php

/**
 * @service userEntry
 * @package api
 * @subpackage services
 */
class UserEntryService extends BorhanBaseService {

	public function initService($serviceId, $serviceName, $actionName)
	{
		parent::initService($serviceId, $serviceName, $actionName);
	}

	/**
	 * Adds a user_entry to the Borhan DB.
	 *
	 * @action add
	 * @param BorhanUserEntry $userEntry
	 * @return BorhanUserEntry
	 */
	public function addAction(BorhanUserEntry $userEntry)
	{
		$entry = entryPeer::retrieveByPK($userEntry->entryId);
		if (!$entry)
			throw new BorhanAPIException(BorhanErrors::INVALID_ENTRY_ID, $userEntry->entryId);

		$dbUserEntry = $userEntry->toInsertableObject(null, array('type'));
		$dbUserEntry->save();

		$userEntry->fromObject($dbUserEntry, $this->getResponseProfile());

		return $userEntry;
	}

	/**
	 *
	 * @action update
	 * @param int $id
	 * @param BorhanUserEntry $userEntry
	 * @throws BorhanAPIException
	 */
	public function updateAction($id, BorhanUserEntry $userEntry)
	{
		$dbUserEntry = UserEntryPeer::retrieveByPK($id);
		if (!$dbUserEntry)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);

		$dbUserEntry = $userEntry->toUpdatableObject($dbUserEntry);
		$dbUserEntry->save();
	}

	/**
	 * @action delete
	 * @param int $id
	 * @return BorhanUserEntry The deleted UserEntry object
 	 * @throws BorhanAPIException
	 */
	public function deleteAction($id)
	{
		$dbUserEntry = UserEntryPeer::retrieveByPK($id);
		if (!$dbUserEntry)
			throw new BorhanAPIException(BorhanErrors::INVALID_OBJECT_ID, $id);
		$dbUserEntry->setStatus(BorhanUserEntryStatus::DELETED);
		$dbUserEntry->save();

		$userEntry = BorhanUserEntry::getInstanceByType($dbUserEntry->getType());
		$userEntry->fromObject($dbUserEntry, $this->getResponseProfile());

		return $userEntry;

	}

	/**
	 * @action list
	 * @param BorhanUserEntryFilter $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanUserEntryListResponse
	 */
	public function listAction(BorhanUserEntryFilter $filter, BorhanFilterPager $pager = null)
	{
		if (!$filter)
		{
			$filter = new BorhanUserEntryFilter();
		}
		if (!$pager)
		{
			$pager = new BorhanFilterPager();
		}
		// return empty list when userId was not given
		if ( $this->getKs() && !$this->getKs()->isAdmin() && !kCurrentContext::$ks_uid ) {
		    return new BorhanUserEntryListResponse();
		}
		return $filter->getListResponse($pager, $this->getResponseProfile());
	}

	/**
	 * @action get
	 * @param string $id
	 * @return BorhanUserEntry
	 * @throws BorhanAPIException
	 */
	public function getAction($id)
	{
		$dbUserEntry = UserEntryPeer::retrieveByPK( $id );
		if(!$dbUserEntry)
			throw new BorhanAPIException(BorhanErrors::USER_ENTRY_NOT_FOUND, $id);

		$userEntry = BorhanUserEntry::getInstanceByType($dbUserEntry->getType());
		if (!$userEntry)
			return null;
		$userEntry->fromObject($dbUserEntry);
		return $userEntry;
	}

}