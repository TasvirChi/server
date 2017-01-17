<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated use BorhanRule instead
 * @abstract
 */
abstract class BorhanBaseRestriction extends BorhanObject
{
	/**
	 * @param BorhanRestrictionArray $restrictions enable one restriction to be affected by other restrictions
	 * @return kAccessControlRestriction
	 * @abstract must be implemented
	 */
	abstract public function toRule(BorhanRestrictionArray $restrictions);
}