<?php
/**
 * base class for the real ProvisionEngine in the system - currently only akamai 
 * 
 * @package Scheduler
 * @subpackage Provision
 */
class KProvisionEngineAkamai extends KProvisionEngine
{
	/**
	 * @var AkamaiStreamsClient
	 */
	private $streamClient;

	/**
	 * @return string
	 */
	public function getName()
	{
		return get_class($this);
	}
	
	/**
	 * @param BorhanProvisionJobData $data
	 */
	protected function __construct(BorhanProvisionJobData $data = null)
	{
		$username = null;
		$password = null;
		
		if (!is_null($data) && $data instanceof BorhanAkamaiProvisionJobData)
		{
			//all fields are set and are not empty string
			if ($data->wsdlUsername && $data->wsdlPassword && $data->cpcode && $data->emailId && $data->primaryContact)
			{
				$username = $data->wsdlUsername;
				$password = $data->wsdlPassword;
			}
		}
		//if one of the params was not set, use the taskConfig data	
		if (!$username || !$password )
		{
			$username = KBatchBase::$taskConfig->params->wsdlUsername;
			$password = KBatchBase::$taskConfig->params->wsdlPassword;
		}
		
		BorhanLog::debug("Connecting to Akamai(username: $username, password: $password)");
		$this->streamClient = new AkamaiStreamsClient($username, $password);
	}
	
	/* (non-PHPdoc)
	 * @see batches/Provision/Engines/KProvisionEngine#provide()
	 */
	public function provide( BorhanBatchJob $job, BorhanProvisionJobData $data )
	{
		$cpcode = null;
		$emailId = null;
		$primaryContact = null;
		$secondaryContact = null;
		
		if ($data instanceof BorhanAkamaiProvisionJobData)
		{
			if ($data->wsdlUsername && $data->wsdlPassword)
			{
				$cpcode = $data->cpcode;
				$emailId = $data->emailId;
				$primaryContact = $data->primaryContact;
				$secondaryContact = $data->secondaryContact ? $data->secondaryContact : $data->primaryContact;
			}
		}
		//if one of the params was not set, use the taskConfig data		
		if (!$cpcode || !$emailId || !$primaryContact || !$secondaryContact)
		{
			$cpcode = KBatchBase::$taskConfig->params->cpcode;
			$emailId = KBatchBase::$taskConfig->params->emailId;
			$primaryContact = KBatchBase::$taskConfig->params->primaryContact;
			$secondaryContact = KBatchBase::$taskConfig->params->secondaryContact;
		}
		
		$name = $job->entryId;
		$encoderIP = $data->encoderIP;
		$backupEncoderIP = $data->backupEncoderIP;
		$encoderPassword = $data->encoderPassword;
		$endDate = $data->endDate;
		$dynamic = true;
		
		BorhanLog::debug("provideEntry(encoderIP: $encoderIP, backupEncoderIP: $backupEncoderIP, encoderPassword: $encoderPassword, endDate: $endDate)");
		$flashLiveStreamInfo = $this->streamClient->provisionFlashLiveDynamicStream($cpcode, $name, $encoderIP, $backupEncoderIP, $encoderPassword, $emailId, $primaryContact, $secondaryContact, $endDate, $dynamic);
		
		if(!$flashLiveStreamInfo)
		{
			return new KProvisionEngineResult(BorhanBatchJobStatus::FAILED, "Error: " . $this->streamClient->getError());
		}
		
		foreach($flashLiveStreamInfo as $field => $value)
			BorhanLog::info("Returned $field => $value");
				
		if(isset($flashLiveStreamInfo['faultcode']))
		{
			return new KProvisionEngineResult(BorhanBatchJobStatus::FAILED, "Error: " . $flashLiveStreamInfo['faultstring']);
		}
		
		$arr = null;
		if(preg_match('/p\.ep(\d+)\.i\.akamaientrypoint\.net/', $flashLiveStreamInfo['primaryEntryPoint'], $arr))
			$data->streamID = $arr[1];
			
		if(preg_match('/b\.ep(\d+)\.i\.akamaientrypoint\.net/', $flashLiveStreamInfo['backupEntryPoint'], $arr))
			$data->backupStreamID = $arr[1];
			
		$data->rtmp = $flashLiveStreamInfo['connectUrl'];
		$data->encoderUsername = $flashLiveStreamInfo['encoderUsername'];
		$data->primaryBroadcastingUrl = 'rtmp://'.$flashLiveStreamInfo['primaryEntryPoint'].'/EntryPoint';
		$data->secondaryBroadcastingUrl = 'rtmp://'.$flashLiveStreamInfo['backupEntryPoint'].'/EntryPoint';
		$tempStreamName = explode('@', $flashLiveStreamInfo['streamName']);
		if (count($tempStreamName) == 2) {
			$data->streamName = $tempStreamName[0] . '_%i@' . $tempStreamName[1];
		}
		else {
			$data->streamName = $flashLiveStreamInfo['streamName'];
		}
		
		
		return new KProvisionEngineResult(BorhanBatchJobStatus::FINISHED, 'Succesfully provisioned entry', $data);
	}
	
	/* (non-PHPdoc)
	 * @see batches/Provision/Engines/KProvisionEngine#delete()
	 */
	public function delete( BorhanBatchJob $job, BorhanProvisionJobData $data )
	{
		$returnVal = $this->streamClient->deleteStream($data->streamID, true);
		
		if(!$returnVal)
		{
			return new KProvisionEngineResult(BorhanBatchJobStatus::FAILED, "Error: " . $this->streamClient->getError());
		}
		
		if(is_array($returnVal))
		{
			foreach($returnVal as $field => $value)
				BorhanLog::info("Returned $field => $value");
		}
		else
		{
			BorhanLog::info("Returned: $returnVal");
		}
				
		if(isset($returnVal['faultcode']))
		{
			return new KProvisionEngineResult(BorhanBatchJobStatus::FAILED, "Error: " . $returnVal['faultstring']);
		}
		
		$data->returnVal = $returnVal;
		return new KProvisionEngineResult(BorhanBatchJobStatus::FINISHED, 'Succesfully deleted entry', $data);
	}
	
	
	/* (non-PHPdoc)
	 * @see KProvisionEngine::checkProvisionedStream()
	 */
	public function checkProvisionedStream(BorhanBatchJob $job, BorhanProvisionJobData $data) 
	{
		$data = $job->data;
		/* @var $data BorhanAkamaiUniversalProvisionJobData */
		$primaryEntryPoint = parse_url($data->primaryBroadcastingUrl, PHP_URL_HOST);
		$backupEntryPoint = parse_url($data->secondaryBroadcastingUrl, PHP_URL_HOST);
		if (!$primaryEntryPoint || !$backupEntryPoint)
		{
			return new KProvisionEngineResult(BorhanBatchJobStatus::FAILED, "Missing one or both entry points");
		}
		
		$pingTimeout = KBatchBase::$taskConfig->params->pingTimeout;
		@exec("ping -w $pingTimeout $primaryEntryPoint", $output, $return);
		if ($return)
		{
			return new KProvisionEngineResult(BorhanBatchJobStatus::ALMOST_DONE, "No reponse from primary entry point - retry in 5 mins");
		}
		
		@exec("ping -w $pingTimeout $backupEntryPoint", $output, $return);
		if ($return)
		{
			return new KProvisionEngineResult(BorhanBatchJobStatus::ALMOST_DONE, "No reponse from backup entry point - retry in 5 mins");
		}
		
		return new KProvisionEngineResult(BorhanBatchJobStatus::FINISHED, "Stream is Provisioned");
		
	}

}

