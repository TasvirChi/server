<?php
/**
 * External media service lets you upload and manage embed codes and external playable content
 *
 * @service externalMedia
 * @package plugins.externalMedia
 * @subpackage api.services
 */
class ExternalMediaService extends BorhanEntryService
{
	protected function borhanNetworkAllowed($actionName)
	{
		if($actionName === 'get')
			return true;
		
		return parent::borhanNetworkAllowed($actionName);
	}
	
	/**
	 * Add external media entry
	 *
	 * @action add
	 * @param BorhanExternalMediaEntry $entry
	 * @return BorhanExternalMediaEntry
	 */
	function addAction(BorhanExternalMediaEntry $entry)
	{
		$dbEntry = parent::add($entry, $entry->conversionProfileId);
		$dbEntry->setStatus(entryStatus::READY);
		$dbEntry->save();
		
		$trackEntry = new TrackEntry();
		$trackEntry->setEntryId($dbEntry->getId());
		$trackEntry->setTrackEventTypeId(TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY);
		$trackEntry->setDescription(__METHOD__ . ":" . __LINE__ . "::ENTRY_EXTERNAL_MEDIA");
		TrackEntry::addTrackEntry($trackEntry);
		
		$entry->fromObject($dbEntry, $this->getResponseProfile());
		return $entry;
	}
	
	/**
	 * Get external media entry by ID.
	 * 
	 * @action get
	 * @param string $id External media entry id
	 * @return BorhanExternalMediaEntry The requested external media entry
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 */
	function getAction($id)
	{
		return $this->getEntry($id, ExternalMediaPlugin::getEntryTypeCoreValue(ExternalMediaEntryType::EXTERNAL_MEDIA));
	}
	
	/**
	 * Update external media entry. Only the properties that were set will be updated.
	 * 
	 * @action update
	 * @param string $id External media entry id to update
	 * @param BorhanExternalMediaEntry $entry External media entry object to update
	 * @return BorhanExternalMediaEntry The updated external media entry
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry id edit
	 */
	function updateAction($id, BorhanExternalMediaEntry $entry)
	{
		return $this->updateEntry($id, $entry, ExternalMediaPlugin::getEntryTypeCoreValue(ExternalMediaEntryType::EXTERNAL_MEDIA));
	}
	
	/**
	 * Delete a external media entry.
	 *
	 * @action delete
	 * @param string $id External media entry id to delete
	 * 
	 * @throws BorhanErrors::ENTRY_ID_NOT_FOUND
	 * @validateUser entry id edit
	 */
	function deleteAction($id)
	{
		$this->deleteEntry($id, ExternalMediaPlugin::getEntryTypeCoreValue(ExternalMediaEntryType::EXTERNAL_MEDIA));
	}
	
	/**
	 * List media entries by filter with paging support.
	 * 
	 * @action list
	 * @param BorhanExternalMediaEntryFilter $filter External media entry filter
	 * @param BorhanFilterPager $pager Pager
	 * @return BorhanExternalMediaEntryListResponse Wrapper for array of media entries and total count
	 */
	function listAction(BorhanExternalMediaEntryFilter $filter = null, BorhanFilterPager $pager = null)
	{
		if(!$filter)
			$filter = new BorhanExternalMediaEntryFilter();
		
		list($list, $totalCount) = parent::listEntriesByFilter($filter, $pager);
		
		$response = new BorhanExternalMediaEntryListResponse();
		$response->objects = BorhanExternalMediaEntryArray::fromDbArray($list, $this->getResponseProfile());
		$response->totalCount = $totalCount;
		return $response;
	}
	
	/**
	 * Count media entries by filter.
	 * 
	 * @action count
	 * @param BorhanExternalMediaEntryFilter $filter External media entry filter
	 * @return int
	 */
	function countAction(BorhanExternalMediaEntryFilter $filter = null)
	{
		if(!$filter)
			$filter = new BorhanExternalMediaEntryFilter();
		
		return parent::countEntriesByFilter($filter);
	}

	protected function duplicateTemplateEntry($conversionProfileId, $templateEntryId, $baseTo = null)
	{
		return parent::duplicateTemplateEntry($conversionProfileId, $templateEntryId, new ExternalMediaEntry());
	}
}
