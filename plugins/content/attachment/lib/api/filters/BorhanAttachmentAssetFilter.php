<?php
/**
 * @package plugins.attachment
 * @subpackage api.filters
 */
class BorhanAttachmentAssetFilter extends BorhanAttachmentAssetBaseFilter
{
	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getTypeListResponse()
	 */
	public function getTypeListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null, array $types = null)
	{
		list($list, $totalCount) = $this->doGetListResponse($pager, $types);
		
		$response = new BorhanAttachmentAssetListResponse();
		$response->objects = BorhanAttachmentAssetArray::fromDbArray($list, $responseProfile);
		$response->totalCount = $totalCount;
		return $response;  
	}

	/* (non-PHPdoc)
	 * @see BorhanAssetFilter::getListResponse()
	 */
	public function getListResponse(BorhanFilterPager $pager, BorhanDetachedResponseProfile $responseProfile = null)
	{
		$types = BorhanPluginManager::getExtendedTypes(assetPeer::OM_CLASS, AttachmentPlugin::getAssetTypeCoreValue(AttachmentAssetType::ATTACHMENT));
		return $this->getTypeListResponse($pager, $responseProfile, $types);
	}
}
