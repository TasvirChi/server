<?php 
/**
 * @package Admin
 * @subpackage Users
 */
class Form_Partner_BmcUsersResetPassword extends Infra_Form
{
	public function init()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->setAttrib('id', 'frmBmcUsersResetPassword');
		
		$this->addElement('text', 'newPassword', array(
			'label' 		=> 'New Password:',
			'required'		=> true,
			'filters' 		=> array('StringTrim'),	
		));
	}
}

