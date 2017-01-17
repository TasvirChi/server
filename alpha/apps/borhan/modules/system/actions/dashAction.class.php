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
class dashAction extends borhanSystemAction
{
	/**
	 * Will give a good view of the batch processes in the system
	 */
	public function execute()
	{
		$this->systemAuthenticated();
		
	}
}

?>