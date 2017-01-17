<?php
/**
 * @package plugins.transcript
 * @subpackage api.filters
 */
class BorhanTranscriptAssetFilter extends BorhanTranscriptAssetBaseFilter
{	
	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getTypeListResponse()
	 */
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, array $types = null)
	{
		$types = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, TranscriptPlugin::getAssetTypeCoreValue(TranscriptAssetType::TRANSCRIPT));
		list($list, $totalCount) = $this->doGetListResponse($pager, $types);

		$response = new BorhanTranscriptAssetListResponse();
		$response->objects = BorhanTranscriptAssetArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;
	}

	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$types = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, TranscriptPlugin::getAssetTypeCoreValue(TranscriptAssetType::TRANSCRIPT));
		return $this->getTypeListResponse($pager, $responseProfile, $types);
	}
}
