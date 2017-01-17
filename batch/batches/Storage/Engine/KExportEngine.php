<?php
/**
 * 
 */
abstract class KExportEngine
{
	/**
	 * @var BorhanStorageExportJobData
	 */
	protected $data;
	
	/**
	 * @param BorhanStorageExportJobData $data
	 * @param int $jobSubType
	 */
	public function __construct(BorhanStorageJobData $data)
	{
		$this->data = $data;
	}
	
	/**
	 * @return bool
	 */
	abstract function export ();
	
	
	/**
	 * @return bool
	 */
	abstract function verifyExportedResource ();
    
    /**
     * @return bool
     */
    abstract function delete();
	
	/**
	 * @param int $protocol
	 * @param BorhanStorageExportJobData $data
	 * @return KExportEngine
	 */
	public static function getInstance ($protocol, $partnerId, BorhanStorageJobData $data)
	{
		switch ($protocol)
		{
			case BorhanStorageProfileProtocol::FTP:
			case BorhanStorageProfileProtocol::BORHAN_DC:
			case BorhanStorageProfileProtocol::S3:
			case BorhanStorageProfileProtocol::SCP:
			case BorhanStorageProfileProtocol::SFTP:
			case BorhanStorageProfileProtocol::LOCAL:
				return new KFileTransferExportEngine($data, $protocol);
			default:
				return BorhanPluginManager::loadObject('KExportEngine', $protocol, array($data, $partnerId));
		}
	}
}