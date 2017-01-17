<?php
/**
 * @package    Core
 * @subpackage externalServices
 */
class redirectAction extends borhanAction
{
	public function execute()
	{
		$return_to = $_REQUEST["return_to"];
		$url = $_REQUEST["url"];
		setcookie( 'borhan_redirect', base64_encode($return_to), time() + 3600 , '/' );
		
		$this->redirect( $url );
	}
}
