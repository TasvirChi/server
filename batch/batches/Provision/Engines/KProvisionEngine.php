<?php
/**
 * base class for the real ProvisionEngine in the system - currently only akamai 
 * 
 * @package Scheduler
 * @subpackage Provision
 * @abstract
 */
abstract class KProvisionEngine
{
	
	/**
	 * Will return the proper engine depending on the type (BorhanSourceType)
	 *
	 * @param int $provider
	 * @param BorhanProvisionJobData $data
	 * @return KProvisionEngine
	 */
	public static function getInstance ( $provider , BorhanProvisionJobData $data = null)
	{
		$engine =  null;
		
		switch ($provider )
		{
			case BorhanSourceType::AKAMAI_LIVE:
				$engine = new KProvisionEngineAkamai($data);
				break;
			case BorhanSourceType::AKAMAI_UNIVERSAL_LIVE:
				$engine = new KProvisionEngineUniversalAkamai($data);
				break;
			default:
				$engine = BorhanPluginManager::loadObject('KProvisionEngine', $provider);
		}
		
		return $engine;
	}

	
	/**
	 * @return string
	 */
	abstract public function getName();
	
	/**
	 * @param BorhanBatchJob $job
	 * @param BorhanProvisionJobData $data
	 * @return KProvisionEngineResult
	 */
	abstract public function provide( BorhanBatchJob $job, BorhanProvisionJobData $data );
	
	/**
	 * @param BorhanBatchJob $job
	 * @param BorhanProvisionJobData $data
	 * @return KProvisionEngineResult
	 */
	abstract public function delete( BorhanBatchJob $job, BorhanProvisionJobData $data );
	
	/**
	 * @param BorhanBatchJob $job
	 * @param BorhanProvisionJobData $data
	 * @return KProvisionEngineResult
	 */
	abstract public function checkProvisionedStream ( BorhanBatchJob $job, BorhanProvisionJobData $data ) ;
}


/**
 * @package Scheduler
 * @subpackage Conversion
 *
 */
class KProvisionEngineResult
{
	/**
	 * @var int
	 */
	public $status;
	
	/**
	 * @var string
	 */
	public $errMessage;
	
	/**
	 * @var BorhanProvisionJobData
	 */
	public $data;
	
	/**
	 * @param int $status
	 * @param string $errMessage
	 * @param BorhanProvisionJobData $data
	 */
	public function __construct( $status , $errMessage, BorhanProvisionJobData $data = null )
	{
		$this->status = $status;
		$this->errMessage = $errMessage;
		$this->data = $data;
	}
}

