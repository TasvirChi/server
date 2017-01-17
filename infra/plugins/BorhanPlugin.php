<?php
/**
 * @package infra
 * @subpackage Plugins
 */
abstract class BorhanPlugin implements IBorhanPlugin
{
	public function getInstance($interface)
	{
		if($this instanceof $interface)
			return $this;
			
		return null;
	}
}