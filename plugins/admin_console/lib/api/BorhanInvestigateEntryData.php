<?php
/**
 * @package plugins.adminConsole
 * @subpackage api.objects
 */
class BorhanInvestigateEntryData extends BorhanObject
{
	/**
	 * @var BorhanBaseEntry
	 * @readonly
	 */
	public $entry;

	/**
	 * @var BorhanFileSyncListResponse
	 * @readonly
	 */
	public $fileSyncs;

	/**
	 * @var BorhanBatchJobListResponse
	 * @readonly
	 */
	public $jobs;
	
	/**
	 * @var BorhanInvestigateFlavorAssetDataArray
	 * @readonly
	 */
	public $flavorAssets;
	
	/**
	 * @var BorhanInvestigateThumbAssetDataArray
	 * @readonly
	 */
	public $thumbAssets;
	
	/**
	 * @var BorhanTrackEntryArray
	 * @readonly
	 */
	public $tracks;
}