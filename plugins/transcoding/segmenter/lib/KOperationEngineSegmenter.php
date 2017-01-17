<?php
/**
 * @package plugins.segmenter
 * @subpackage lib
 */
class KOperationEngineSegmenter  extends KSingleOutputOperationEngine
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

	public function operate(kOperator $operator = null, $inFilePath, $configFilePath = null)
	{
//$this->outFilePath = "k:".$this->outFilePath;
		BorhanLog::debug("creating directory:".$this->outFilePath);
		kFile::fullMkfileDir($this->outFilePath, 0777, true);
		$res = parent::operate($operator, $inFilePath, $configFilePath);
		rename("$this->outFilePath//playlist.m3u8", "$this->outFilePath//playlist.tmp");
		self::parsePlayList("$this->outFilePath//playlist.tmp","$this->outFilePath//playlist.m3u8");
//		rename("out_dummy.m3u8", "$this->outFilePath//out_dummy.m3u8");
//		BorhanLog::info("operator($operator), inFilePath($inFilePath), configFilePath($configFilePath)");

		return $res;
	}

	private function parsePlayList($fileIn, $fileOut)
	{
		$fdIn = fopen($fileIn, 'r');
		if($fdIn==false)
			return false;
		$fdOut = fopen($fileOut, 'w');
		if($fdOut==false)
			return false;
		$strIn=null;
		while ($strIn=fgets($fdIn)){
			if(strstr($strIn,"---")){
				$i=strrpos($strIn,"/");
				$strIn = substr($strIn,$i+1);
			}
			fputs($fdOut,$strIn);
			echo $strIn;
		}
		fclose($fdOut);
		fclose($fdIn);
		return true;
	}
}
