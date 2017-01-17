<?php
/**
 * @package api
 * @subpackage objects.factory
 */
class BorhanEntryFactory
{
	/**
	 * @param int $type
	 * @param bool $isAdmin
	 * @return BorhanBaseEntry
	 */
	static function getInstanceByType ($type, $isAdmin = false)
	{
		switch ($type) 
		{
			case BorhanEntryType::MEDIA_CLIP:
				$obj = new BorhanMediaEntry();
				break;
				
			case BorhanEntryType::MIX:
				$obj = new BorhanMixEntry();
				break;
				
			case BorhanEntryType::PLAYLIST:
				$obj = new BorhanPlaylist();
				break;
				
			case BorhanEntryType::DATA:
				$obj = new BorhanDataEntry();
				break;
				
			case BorhanEntryType::LIVE_STREAM:
				if($isAdmin)
				{
					$obj = new BorhanLiveStreamAdminEntry();
				}
				else
				{
					$obj = new BorhanLiveStreamEntry();
				}
				break;
				
			case BorhanEntryType::LIVE_CHANNEL:
				$obj = new BorhanLiveChannel();
				break;
				
			default:
				$obj = BorhanPluginManager::loadObject('BorhanBaseEntry', $type);
				
				if(!$obj)
					$obj = new BorhanBaseEntry();
					
				break;
		}
		
		return $obj;
	}
}
