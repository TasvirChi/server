<?php
/**
 * @package plugins
 * @subpackage api
 */
class BorhanPluginReplacementOptionsArray extends BorhanTypedArray
{
	public function __construct( )
	{
		return parent::__construct ("BorhanPluginReplacementOptionsItem");
	}
}
