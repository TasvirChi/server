<?php
/**
 * Enable the plugin to return extended BorhanCriteria object according to the searched object type
 * @package infra
 * @subpackage Plugins
 */
interface IBorhanCriteriaFactory extends IBorhanBase
{
	/**
	 * Creates a new BorhanCriteria for the given object name
	 * 
	 * @param string $objectType object type to create Criteria for.
	 * @return BorhanCriteria derived object
	 */
	public static function getBorhanCriteria($objectType);
}