<?php
/**
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanBase
{
	/**
	 * Return an instance implementing the interface
	 * @param string $interface
	 * @return IBorhanBase
	 */
	public function getInstance($interface);
}