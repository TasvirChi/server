<?php
/**
 * @package plugins.adminConsole
 * @subpackage api.objects
 */
class BorhanInvestigateFlavorAssetData extends BorhanObject
{
	/**
	 * @var BorhanFlavorAsset
	 * @readonly
	 */
	public $flavorAsset;

	/**
	 * @var BorhanFileSyncListResponse
	 * @readonly
	 */
	public $fileSyncs;

	/**
	 * @var BorhanMediaInfoListResponse
	 * @readonly
	 */
	public $mediaInfos;

	/**
	 * @var BorhanFlavorParams
	 * @readonly
	 */
	public $flavorParams;

	/**
	 * @var BorhanFlavorParamsOutputListResponse
	 * @readonly
	 */
	public $flavorParamsOutputs;
}