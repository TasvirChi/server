<?php

require_once(__DIR__ . '/BorhanMediaServerClient.class.php');
	
class BorhanMediaServerLiveService extends BorhanMediaServerClient
{
	function __construct($url)
	{
		parent::__construct($url);
	}
	
	
	/**
	 * 
	 * @param string $liveEntryId
	 * @return BorhanMediaServerSplitRecordingNowResponse
	 **/
	public function splitRecordingNow($liveEntryId)
	{
		$params = array();
		
		$params["liveEntryId"] = $this->parseParam($liveEntryId, 'xsd:string');

		return $this->doCall("splitRecordingNow", $params, 'BorhanMediaServerSplitRecordingNowResponse');
	}
	
}		
	
