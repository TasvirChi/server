<?php
/**
 * @package    Core
 * @subpackage BMC
 */
class varloginAction extends borhanAction
{
	public function execute ( ) 
	{
		$this->beta = $this->getRequestParameter( "beta" );
		$this->bmc_login_version 	= kConf::get('bmc_login_version');
				
		sfView::SUCCESS;
	}
}
