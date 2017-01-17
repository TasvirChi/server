<?php
/**
 * @package    Core
 * @subpackage system
 * @deprecated
 */
require_once ( __DIR__ . "/borhanSystemAction.class.php" );

/**
 * @package    Core
 * @subpackage system
 * @deprecated
 */
class blockedEmailsAction extends borhanSystemAction
{
	public function execute()
	{
		$this->forceSystemAuthentication();		
	}
}
?>