<?php
/**
 * @package Scheduler
 * @subpackage Conversion
 */
class KOperationManager
{
	/**
	 * @param int $type
	 * @param BorhanConvartableJobData $data
	 * @param BorhanBatchJob $job
	 * @return KOperationEngine
	 */
	public static function getEngine($type, BorhanConvartableJobData $data, BorhanBatchJob $job)
	{
		$engine = self::createNewEngine($type, $data);
		if(!$engine)
			return null;
			
		$engine->configure($data, $job);
		return $engine;
	}
	
	/**
	 * @param int $type
	 * @param BorhanConvartableJobData $data
	 * @return KOperationEngine
	 */
	protected static function createNewEngine($type, BorhanConvartableJobData $data)
	{
		// TODO - remove after old version deprecated
		/*
		 * The 'flavorParamsOutput' is not set only for SL/ISM collections - that is definently old engine' flow
		 */		
		if(!isset($data->flavorParamsOutput) || !$data->flavorParamsOutput->engineVersion)
		{
			return new KOperationEngineOldVersionWrapper($type, $data);
		}
		
		switch($type)
		{ 
			case BorhanConversionEngineType::MENCODER:
				return new KOperationEngineMencoder(KBatchBase::$taskConfig->params->mencderCmd, $data->destFileSyncLocalPath);
				
			case BorhanConversionEngineType::ON2:
				return new KOperationEngineFlix(KBatchBase::$taskConfig->params->on2Cmd, $data->destFileSyncLocalPath);
				
			case BorhanConversionEngineType::FFMPEG:
				return new KOperationEngineFfmpeg(KBatchBase::$taskConfig->params->ffmpegCmd, $data->destFileSyncLocalPath);
				
			case BorhanConversionEngineType::FFMPEG_AUX:
				return new KOperationEngineFfmpegAux(KBatchBase::$taskConfig->params->ffmpegAuxCmd, $data->destFileSyncLocalPath);
				
			case BorhanConversionEngineType::FFMPEG_VP8:
				return new KOperationEngineFfmpegVp8(KBatchBase::$taskConfig->params->ffmpegVp8Cmd, $data->destFileSyncLocalPath);
				
			case BorhanConversionEngineType::ENCODING_COM :
				return new KOperationEngineEncodingCom(
					KBatchBase::$taskConfig->params->EncodingComUserId, 
					KBatchBase::$taskConfig->params->EncodingComUserKey, 
					KBatchBase::$taskConfig->params->EncodingComUrl);
		}
		
		if($data instanceof BorhanConvertCollectionJobData)
		{
			$engine = self::getCollectionEngine($type, $data);
			if($engine)
				return $engine;
		}
		$engine = BorhanPluginManager::loadObject('KOperationEngine', $type, array('params' => KBatchBase::$taskConfig->params, 'outFilePath' => $data->destFileSyncLocalPath));
		
		return $engine;
	}
	
	protected static function getCollectionEngine($type, BorhanConvertCollectionJobData $data)
	{
		switch($type)
		{
			case BorhanConversionEngineType::EXPRESSION_ENCODER3:
				return new KOperationEngineExpressionEncoder3(KBatchBase::$taskConfig->params->expEncoderCmd, $data->destFileName, $data->destDirLocalPath);
		}
		
		return  null;
	}
}


