<?php
/**
 * @package api
 * @subpackage objects
 */
class BorhanFlavorAssetWithParams extends BorhanObject
{
	/**
	 * The Flavor Asset (Can be null when there are params without asset)
	 * 
	 * @var BorhanFlavorAsset
	 */
	public $flavorAsset;
	
	/**
	 * The Flavor Params
	 * 
	 * @var BorhanFlavorParams
	 */
	public $flavorParams;
	
	/**
	 * The entry id
	 * 
	 * @var string
	 */
	public $entryId;
}