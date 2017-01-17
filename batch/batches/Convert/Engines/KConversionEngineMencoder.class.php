<?php
/**
 * @package Scheduler
 * @subpackage Conversion.engines
 */
class KConversionEngineMencoder  extends KJobConversionEngine
{
	const MENCODER = "mencoder";
		
	public function getName()
	{
		return self::MENCODER;
	}
	
	public function getType()
	{
		return BorhanConversionEngineType::MENCODER;
	}
	
	public function getCmd ()
	{
		return KBatchBase::$taskConfig->params->mencderCmd;
	}
}
