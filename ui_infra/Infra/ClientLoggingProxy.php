<?php
/**
 * Implements the IKaltuarLogger interface used by the BorhanClient for logging purposes and proxies the message to the BorhanLog
 *  
 * @package UI-infra
 * @subpackage Client
 */
class Infra_ClientLoggingProxy implements Borhan_Client_ILogger
{
	public function log($msg)
	{
		BorhanLog::debug($msg);
	}
}