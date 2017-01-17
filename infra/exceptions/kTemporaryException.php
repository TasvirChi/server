<?php
/**
 * @package infra
 * @subpackage Exceptions
 * 
 * this calss is an exception calss represents a minor exception.
 * the concrete usage of this calss is to pass a batch_job retry request to KAsyncConvert.
 */
class kTemporaryException extends kException
{
	/**
	 * @var boolean
	 */
	protected $resetJobExecutionAttempts = false;
	
	/**
	 * @var BorhanJobData
	 */
	protected $data = null;

	public function __construct($message, $code = 0, $data = null)
	{
		parent::__construct($code, $message);
		$this->data = $data;
	}
	
	/**
	 * @return the $resetJobExecutionAttempts
	 */
	public function getResetJobExecutionAttempts() 
	{
		return $this->resetJobExecutionAttempts;
	}

	/**
	 * @param bool $resetJobExecutionAttempts
	 */
	public function setResetJobExecutionAttempts($resetJobExecutionAttempts) 
	{
		$this->resetJobExecutionAttempts = $resetJobExecutionAttempts;
	}
	
	/**
	 * @return the $data
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param BorhanJobData $data
	 */
	public function setData(BorhanJobData $data)
	{
		$this->data = $data;
	}
}