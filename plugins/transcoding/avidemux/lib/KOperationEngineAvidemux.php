<?php
/**
 * @package plugins.avidemux
 * @subpackage lib
 */
class KOperationEngineAvidemux  extends KSingleOutputOperationEngine
{

	public function __construct($cmd, $outFilePath)
	{
		parent::__construct($cmd,$outFilePath);
		BorhanLog::info(": cmd($cmd), outFilePath($outFilePath)");
	}

	protected function getCmdLine()
	{
		$exeCmd =  parent::getCmdLine();
		BorhanLog::info(print_r($this,true));
		return $exeCmd;
	}
}
