<?php
/**
 * @package api
 * @subpackage objects
 * @deprecated
 */
class BorhanSearchResultResponse extends BorhanObject
{
	/**
	 * @var BorhanSearchResultArray
	 * @readonly
	 */
	public $objects;

	/**
	 * @var bool
	 * @readonly
	 */
	public $needMediaInfo;
}