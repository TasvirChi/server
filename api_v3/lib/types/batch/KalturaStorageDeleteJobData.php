<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanStorageDeleteJobData extends BorhanStorageJobData
{
	private static $map_between_objects = array
	(
	);

	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}

	
	public function toObject($dbData = null, $props_to_skip = array()) 
	{
		if(is_null($dbData))
			$dbData = new kStorageDeleteJobData();
			
		return parent::toObject($dbData);
	}
	
	/**
	 * @param string $subType
	 * @return int
	 */
	public function toSubType($subType)
	{
		switch ($subType) {
			case BorhanStorageProfileProtocol::SFTP:
            case BorhanStorageProfileProtocol::FTP:
            case BorhanStorageProfileProtocol::SCP:
            case BorhanStorageProfileProtocol::S3:
            case BorhanStorageProfileProtocol::BORHAN_DC:
                return $subType;	
			default:
				return kPluginableEnumsManager::apiToCore('BorhanStorageProfileProtocol', $subType);
		}
	}
	
	/**
	 * @param int $subType
	 * @return string
	 */
	public function fromSubType($subType)
	{
		switch ($subType) {
            case StorageProfileProtocol::SFTP:
            case StorageProfileProtocol::FTP:
            case StorageProfileProtocol::SCP:
            case StorageProfileProtocol::S3:
            case StorageProfileProtocol::BORHAN_DC:
                return $subType;    
            default:
                return kPluginableEnumsManager::coreToApi('StorageProfileProtocol', $subType);
        }
	}
}

