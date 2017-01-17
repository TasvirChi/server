<?php
/**
 * @package    Core
 * @subpackage BMC
 */
class reportsAction extends borhanAction
{
	public function execute ( ) 
	{
		$this->ks = $this->getP ( "ks" );
		$this->partner_id = $this->getP ( "partner_id" );
				
		$this->subp_id = $this->getP ( "subp_id" );
		$this->uid = $this->getP ( "uid" );

		$this->screen_name = $this->getP ( "screen_name" );
		$this->email = $this->getP ( "email" );
		
		$this->beta = $this->getRequestParameter( "beta" );
		
		sfView::SUCCESS;
	}
}
