<?php
/**
 * @package plugins.adminConsole
 * @subpackage api.objects
 */
class BorhanInvestigateThumbAssetData extends BorhanObject
{
	/**
	 * @var BorhanThumbAsset
	 * @readonly
	 */
	public $thumbAsset;

	/**
	 * @var BorhanFileSyncListResponse
	 * @readonly
	 */
	public $fileSyncs;

	/**
	 * @var BorhanThumbParams
	 * @readonly
	 */
	public $thumbParams;

	/**
	 * @var BorhanThumbParamsOutputListResponse
	 * @readonly
	 */
	public $thumbParamsOutputs;
}