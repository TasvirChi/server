<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanStorageExportJobData extends BorhanStorageJobData
{
    
	/**
	 * @var bool
	 */   	
    public $force;
    
    /**
	 * @var bool
	 */   	
    public $createLink;
	
    
	private static $map_between_objects = array
	(
	    "force",
		"createLink",
	);

	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}

	
	public function toObject($dbData = null, $props_to_skip = array()) 
	{
		if(is_null($dbData))
			$dbData = new kStorageExportJobData();
			
		return parent::toObject($dbData);
	}
	
	/**
	 * @param string $subType
	 * @return int
	 */
	public function toSubType($subType)
	{
		switch ($subType) {
			case BorhanStorageProfileProtocol::FTP:
            case BorhanStorageProfileProtocol::SFTP:
            case BorhanStorageProfileProtocol::SCP:
            case BorhanStorageProfileProtocol::S3:
            case BorhanStorageProfileProtocol::BORHAN_DC:
            case BorhanStorageProfileProtocol::LOCAL:
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
            case StorageProfileProtocol::FTP:
            case StorageProfileProtocol::SFTP:
            case StorageProfileProtocol::SCP:
            case StorageProfileProtocol::S3:
            case StorageProfileProtocol::BORHAN_DC:
          	case StorageProfileProtocol::LOCAL:
                return $subType;                    
            default:
                return kPluginableEnumsManager::coreToApi('StorageProfileProtocol', $subType);
        }
	}
}
