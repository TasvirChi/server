<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanResponseProfileCacheRecalculateResults extends BorhanObject
{
	/**
	 * Last recalculated id
	 * 
	 * @var string
	 */
	public $lastObjectKey;
	
	/**
	 * Number of recalculated keys
	 * 
	 * @var int
	 */
	public $recalculated;
}