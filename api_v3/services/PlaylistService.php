<?php
/**
 * Playlist service lets you create,manage and play your playlists
 * Playlists could be static (containing a fixed list of entries) or dynamic (baseed on a filter)
 *
 * @service playlist
 *
 * @package api
 * @subpackage services
 */
class PlaylistService extends BorhanEntryService
{
	/* (non-PHPdoc)
	 * @see BorhanBaseService::globalPartnerAllowed()
	 */
	protected function borhanNetworkAllowed($actionName)
	{
		if ($actionName === 'executeFromContent') {
			return true;
		}
		if ($actionName === 'executeFromFilters') {
			return true;
		}
		if ($actionName === 'getStatsFromContent') {
			return true;
		}
		return parent::borhanNetworkAllowed($actionName);
	}
	
	protected function globalPartnerAllowed($actionName)
	{
		if ($this->actionName == 'execute')
			return true;
	}
	
	protected function partnerRequired($actionName)
	{
		if ($actionName === 'executeFromContent') {
			return false;
		}
		if ($actionName === 'executeFromFilters') {
			return false;
		}
		if ($actionName === 'execute') {
			return false;
		}
		return parent::partnerRequired($actionName);
	}
	
	/**
	 * Add new playlist
	 * Note that all entries used in a playlist will become public and may appear in BorhanNetwork
	 *
	 * @action add
	 * @param BorhanPlaylist $playlist
	 * @param bool $updateStats indicates that the playlist statistics attributes should be updated synchronously now
	 * @return BorhanPlaylist
	 *
	 * @disableRelativeTime $playlist
	 */
	function addAction( BorhanPlaylist $playlist , $updateStats = false)
	{
		$dbPlaylist = $playlist->toInsertableObject();
		
		$this->checkAndSetValidUserInsert($playlist, $dbPlaylist);
		$this->checkAdminOnlyInsertProperties($playlist);
		$this->validateAccessControlId($playlist);
		$this->validateEntryScheduleDates($playlist, $dbPlaylist);
		
		$dbPlaylist->setPartnerId ( $this->getPartnerId() );
		$dbPlaylist->setStatus ( entryStatus::READY );
		$dbPlaylist->setKshowId ( null ); // this is brave !!
		$dbPlaylist->setType ( entryType::PLAYLIST );
		
		myPlaylistUtils::validatePlaylist( $dbPlaylist );
		
		$dbPlaylist->save();
		
		if ( $updateStats )
			myPlaylistUtils::updatePlaylistStatistics( $dbPlaylist->getPartnerId() , $dbPlaylist );
		
		$trackEntry = new TrackEntry();
		$trackEntry->setEntryId($dbPlaylist->getId());
		$trackEntry->setTrackEventTypeId(TrackEntry::TRACK_ENTRY_EVENT_TYPE_ADD_ENTRY);
		$trackEntry->setDescription(__METHOD__ . ":" . __LINE__ . "::ENTRY_PLAYLIST");
		TrackEntry::addTrackEntry($trackEntry);
		
		$playlist = new BorhanPlaylist(); // start from blank
		$playlist->fromObject($dbPlaylist, $this->getResponseProfile());
		
		return $playlist;
	}
	

	/**
	 * Retrieve a playlist
	 *
	 * @action get
	 * @param string $id
	 * @param int $version Desired version of the data
	 * @return BorhanPlaylist
	 *
	 * @throws APIErrors::INVALID_ENTRY_ID
	 * @throws APIErrors::INVALID_PLAYLIST_TYPE
	 */
	function getAction( $id, $version = -1 )
	{
		$dbPlaylist = entryPeer::retrieveByPK( $id );
		
		if ( ! $dbPlaylist )
			throw new BorhanAPIException ( APIErrors::INVALID_ENTRY_ID , "Playlist" , $id  );
		if ( $dbPlaylist->getType() != entryType::PLAYLIST )
			throw new BorhanAPIException ( APIErrors::INVALID_PLAYLIST_TYPE );
			
		if ($version !== -1)
			$dbPlaylist->setDesiredVersion($version);
			
		$playlist = new BorhanPlaylist(); // start from blank
		$playlist->fromObject($dbPlaylist, $this->getResponseProfile());
		
		return $playlist;
	}
		
	/**
	 * Update existing playlist
	 * Note - you cannot change playlist type. updated playlist must be of the same type.
	 *
	 * @action update
	 * @param string $id
	 * @param BorhanPlaylist $playlist
	 * @param bool $updateStats
	 * @return BorhanPlaylist
	 *
	 * @throws APIErrors::INVALID_ENTRY_ID
	 * @throws APIErrors::INVALID_PLAYLIST_TYPE
	 * @validateUser entry id edit
	 *
	 * @disableRelativeTime $playlist
	 */
	function updateAction( $id , BorhanPlaylist $playlist , $updateStats = false )
	{
		$dbPlaylist = entryPeer::retrieveByPK( $id );
		
		if ( ! $dbPlaylist )
			throw new BorhanAPIException ( APIErrors::INVALID_ENTRY_ID , "Playlist" , $id  );
		if ( $dbPlaylist->getType() != entryType::PLAYLIST )
			throw new BorhanAPIException ( APIErrors::INVALID_PLAYLIST_TYPE );
		
		$playlist->playlistType = $dbPlaylist->getMediaType();
		
		// Added the following 2 lines in order to make the permission verifications in toUpdatableObject work on the actual db object
		// TODO: the following use of autoFillObjectFromObject should be replaced by a normal toUpdatableObject
		$playlistUpdate = clone $dbPlaylist;
		$playlistUpdate = $playlist->toUpdatableObject($playlistUpdate);

		$this->checkAndSetValidUserUpdate($playlist, $dbPlaylist);
		$this->checkAdminOnlyUpdateProperties($playlist);
		$this->validateAccessControlId($playlist);
		$this->validateEntryScheduleDates($playlist, $dbPlaylist);

		$allowEmpty = true ; // TODO - what is the policy  ?
		if ( $playlistUpdate->getMediaType() && ($playlistUpdate->getMediaType() != $dbPlaylist->getMediaType() ) )
		{
			throw new BorhanAPIException ( APIErrors::INVALID_PLAYLIST_TYPE );
		}
		else
		{
			$playlistUpdate->setMediaType( $dbPlaylist->getMediaType() ); // incase  $playlistUpdate->getMediaType() was empty
		}

		// copy properties from the playlistUpdate to the $dbPlaylist
		baseObjectUtils::autoFillObjectFromObject ( $playlistUpdate , $dbPlaylist , $allowEmpty );
		// after filling the $dbPlaylist from  $playlist - make sure the data content is set properly
		if(!is_null($playlistUpdate->getDataContent(true)) && $playlistUpdate->getDataContent(true) != $dbPlaylist->getDataContent())
		{
			$dbPlaylist->setDataContent ( $playlistUpdate->getDataContent(true)  );
			myPlaylistUtils::validatePlaylist( $dbPlaylist );
		}
		
		if ( $updateStats )
			myPlaylistUtils::updatePlaylistStatistics ( $this->getPartnerId() , $dbPlaylist );//, $extra_filters , $detailed );
		
		$dbPlaylist->save();
		$playlist->fromObject($dbPlaylist, $this->getResponseProfile());
		
		return $playlist;
	}
		

	/**
	 * Delete existing playlist
	 *
	 * @action delete
	 * @param string $id
	 *
	 * @throws APIErrors::INVALID_ENTRY_ID
	 * @throws APIErrors::INVALID_PLAYLIST_TYPE
	 * @validateUser entry id edit
	 */
	function deleteAction(  $id )
	{
		$this->deleteEntry($id, BorhanEntryType::PLAYLIST);
	}
	
	
	/**
	 * Clone an existing playlist
	 *
	 * @action clone
	 * @param string $id  Id of the playlist to clone
	 * @param BorhanPlaylist $newPlaylist Parameters defined here will override the ones in the cloned playlist
	 * @return BorhanPlaylist
	 *
	 * @throws APIErrors::INVALID_ENTRY_ID
	 * @throws APIErrors::INVALID_PLAYLIST_TYPE
	 */
	function cloneAction( $id, BorhanPlaylist $newPlaylist = null)
	{
		$dbPlaylist = entryPeer::retrieveByPK( $id );
		
		if ( !$dbPlaylist )
			throw new BorhanAPIException ( APIErrors::INVALID_ENTRY_ID , "Playlist" , $id  );
			
		if ( $dbPlaylist->getType() != entryType::PLAYLIST )
			throw new BorhanAPIException ( APIErrors::INVALID_PLAYLIST_TYPE );
			
		if ($newPlaylist->playlistType && ($newPlaylist->playlistType != $dbPlaylist->getMediaType()))
			throw new BorhanAPIException ( APIErrors::CANT_UPDATE_PARAMETER, 'playlistType' );
		
		$oldPlaylist = new BorhanPlaylist();
		$oldPlaylist->fromObject($dbPlaylist, $this->getResponseProfile());
			
		if (!$newPlaylist) {
			$newPlaylist = new BorhanPlaylist();
		}
		
		$reflect = new ReflectionClass($newPlaylist);
		$props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
		foreach ($props as $prop) {
			$propName = $prop->getName();
			// do not override new parameters
			if ($newPlaylist->$propName) {
				continue;
			}
			// do not copy read only parameters
			if (stristr($prop->getDocComment(), '@readonly')) {
				continue;
			}
			// copy from old to new
			$newPlaylist->$propName = $oldPlaylist->$propName;
		}

		// add the new entry
		return $this->addAction($newPlaylist, true);
	}
	
	/**
	 * List available playlists
	 *
	 * @action list
	 * @param BorhanPlaylistFilter // TODO
	 * @param BorhanFilterPager $pager
	 * @return BorhanPlaylistListResponse
	 */
	function listAction( BorhanPlaylistFilter $filter=null, BorhanFilterPager $pager=null )
	{
	    myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL3;
		

	    if (!$filter)
			$filter = new BorhanPlaylistFilter();
			
	    $filter->typeEqual = BorhanEntryType::PLAYLIST;
	    list($list, $totalCount) = parent::listEntriesByFilter($filter, $pager);
	    
	    $newList = BorhanPlaylistArray::fromDbArray($list, $this->getResponseProfile());
		$response = new BorhanPlaylistListResponse();
		$response->objects = $newList;
		$response->totalCount = $totalCount;
		return $response;
	}
	
	/**
	 * Retrieve playlist for playing purpose
	 * @disableTags TAG_WIDGET_SESSION
	 *
	 * @action execute
	 * @param string $id
	 * @param string $detailed
	 * @param BorhanContext $playlistContext
	 * @param BorhanMediaEntryFilterForPlaylist $filter
	 * @param BorhanFilterPager $pager
	 * @return BorhanBaseEntryArray
	 */
	function executeAction( $id , $detailed = false, BorhanContext $playlistContext = null, $filter = null, $pager = null )
	{
		myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL3;

		$playlist = entryPeer::retrieveByPK($id);
		if (!$playlist)
			throw new BorhanAPIException ( APIErrors::INVALID_ENTRY_ID , "Playlist" , $id  );

		if ($playlist->getType() != entryType::PLAYLIST)
			throw new BorhanAPIException ( APIErrors::INVALID_PLAYLIST_TYPE );

		$entryFilter = null;
		if ($filter)
		{
			$coreFilter = new entryFilter();
			$filter->toObject($coreFilter);
			$entryFilter = $coreFilter;
		}
			
		if ($this->getKs() && is_object($this->getKs()) && $this->getKs()->isAdmin())
			myPlaylistUtils::setIsAdminKs(true);

	    $corePlaylistContext = null;
	    if ($playlistContext)
	    {
	        $corePlaylistContext = $playlistContext->toObject();
	        myPlaylistUtils::setPlaylistContext($corePlaylistContext);
	    }
	    
		// the default of detrailed should be true - most of the time the kuse is needed
		if (is_null($detailed))
			 $detailed = true ;

		try
		{
			$entryList = myPlaylistUtils::executePlaylist( $this->getPartnerId() , $playlist , $entryFilter , $detailed, $pager);
		}
		catch (kCoreException $ex)
		{   		
			throw $ex;
		}

		myEntryUtils::updatePuserIdsForEntries ( $entryList );
			
		return BorhanBaseEntryArray::fromDbArray($entryList, $this->getResponseProfile());
	}
	

	/**
	 * Retrieve playlist for playing purpose, based on content
	 * @disableTags TAG_WIDGET_SESSION
	 *
	 * @action executeFromContent
	 * @param BorhanPlaylistType $playlistType
	 * @param string $playlistContent
	 * @param string $detailed
	 * @param BorhanFilterPager $pager
	 * @return BorhanBaseEntryArray
	 */
	function executeFromContentAction($playlistType, $playlistContent, $detailed = false, $pager = null)
	{
	    myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL3;
	    
		if ($this->getKs() && is_object($this->getKs()) && $this->getKs()->isAdmin())
			myPlaylistUtils::setIsAdminKs(true);

		$entryList = array();
		if ($playlistType == BorhanPlaylistType::DYNAMIC)
			$entryList = myPlaylistUtils::executeDynamicPlaylist($this->getPartnerId(), $playlistContent, null, true, $pager);
		else if ($playlistType == BorhanPlaylistType::STATIC_LIST)
			$entryList = myPlaylistUtils::executeStaticPlaylistFromEntryIdsString($playlistContent, null, true, $pager);
			
		myEntryUtils::updatePuserIdsForEntries($entryList);
		
		return BorhanBaseEntryArray::fromDbArray($entryList, $this->getResponseProfile());
	}
	
	/**
	 * Revrieve playlist for playing purpose, based on media entry filters
	 * @disableTags TAG_WIDGET_SESSION
	 * @action executeFromFilters
	 * @param BorhanMediaEntryFilterForPlaylistArray $filters
	 * @param int $totalResults
	 * @param string $detailed
	 * @param BorhanFilterPager $pager
	 * @return BorhanBaseEntryArray
	 */
	function executeFromFiltersAction(BorhanMediaEntryFilterForPlaylistArray $filters, $totalResults, $detailed = true, $pager = null)
	{
	    myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL3;
	    
		$tempPlaylist = new BorhanPlaylist();
		$tempPlaylist->playlistType = BorhanPlaylistType::DYNAMIC;
		$tempPlaylist->filters = $filters;
		$tempPlaylist->totalResults = $totalResults;
		$tempPlaylist->filtersToPlaylistContentXml();
		return $this->executeFromContentAction($tempPlaylist->playlistType, $tempPlaylist->playlistContent, true, $pager);
	}
	
	
	/**
	 * Retrieve playlist statistics
	 *
	 * @action getStatsFromContent
	 * @param BorhanPlaylistType $playlistType
	 * @param string $playlistContent
	 * @return BorhanPlaylist
	 */
	function getStatsFromContentAction( $playlistType , $playlistContent )
	{
	    myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_PROPEL3;
	    
		$dbPlaylist = new entry();
		$dbPlaylist->setId( -1 ); // set with some dummy number so the getDataContent will later work properly
		$dbPlaylist->setType ( entryType::PLAYLIST ); // prepare the playlist type before filling from request
		$dbPlaylist->setMediaType ( $playlistType );
		$dbPlaylist->setDataContent( $playlistContent );
				
		myPlaylistUtils::updatePlaylistStatistics ( $this->getPartnerId() , $dbPlaylist );//, $extra_filters , $detailed );
		
		$playlist = new BorhanPlaylist(); // start from blank
		$playlist->fromObject($dbPlaylist, $this->getResponseProfile());
		
		return $playlist;
	}
	

}
