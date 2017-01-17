<?php
/**
 * @package Var
 * @subpackage Authentication
 */
class Borhan_VarUserIdentity extends Infra_UserIdentity
{
	/**
	 * @var string
	 */
	protected $password;
	
	/**
	 * @return string $password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
}