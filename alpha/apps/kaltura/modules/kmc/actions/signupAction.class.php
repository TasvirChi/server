<?php
/**
 * @package    Core
 * @subpackage BMC
 */
class signupAction extends borhanAction
{
	public function execute ( ) 
	{
		$this->redirect("http://corp.borhan.com/about/signup");
		sfView::SUCCESS;
	}
}
