<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated
 */
class BorhanDirectoryRestriction extends BorhanBaseRestriction 
{
	/**
	 * Borhan directory restriction type
	 * 
	 * @var BorhanDirectoryRestrictionType
	 */
	public $directoryRestrictionType;
	
	/* (non-PHPdoc)
	 * @see BorhanBaseRestriction::toRule()
	 */
	public function toRule(BorhanRestrictionArray $restrictions)
	{
		return null;
	}
}