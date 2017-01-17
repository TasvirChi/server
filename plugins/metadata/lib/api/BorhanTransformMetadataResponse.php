<?php
/**
 * @package plugins.metadata
 * @subpackage api.objects
 */
class BorhanTransformMetadataResponse extends BorhanObject
{
	/**
	 * @var BorhanMetadataArray
	 * @readonly
	 */
	public $objects;

	/**
	 * @var int
	 * @readonly
	 */
	public $totalCount;

	/**
	 * @var int
	 * @readonly
	 */
	public $lowerVersionCount;
}