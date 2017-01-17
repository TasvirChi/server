<?php
/**
 * @service captureSpace
 * @package plugins.captureSpace
 * @subpackage api.services
 */
class CaptureSpaceService extends BorhanBaseService
{
	/**
	 * Returns latest version and URL
	 *
	 * @action clientUpdates
	 * @param string $os
	 * @param string $version
	 * @return BorhanCaptureSpaceUpdateResponse
	 * @throws CaptureSpaceErrors::ALREADY_LATEST_VERSION
	 * @throws CaptureSpaceErrors::NO_UPDATE_IS_AVAILABLE
	 */
	function clientUpdatesAction ($os, $version)
	{
		$hashValue = kCaptureSpaceVersionManager::getUpdateHash($os, $version);
		if (!$hashValue) {
			throw new BorhanAPIException(CaptureSpaceErrors::NO_UPDATE_IS_AVAILABLE, $version, $os);
		}
			
		$path = "/api_v3/service/captureSpace_captureSpace/action/serveUpdate/os/$os/version/$version";
		$downloadUrl = myPartnerUtils::getCdnHost(null) . $path;
		
		$info = new BorhanCaptureSpaceUpdateResponseInfo();
		$info->url = $downloadUrl;
		$info->hash = new BorhanCaptureSpaceUpdateResponseInfoHash();
		$info->hash->algorithm = BorhanCaptureSpaceHashAlgorithm::MD5;
		$info->hash->value = $hashValue;
		
		$response = new BorhanCaptureSpaceUpdateResponse();
		$response->info = $info;
		
		return $response;
	}

	/**
	 * Serve installation file
	 *
	 * @action serveInstall
	 * @param string $os
	 * @return file
	 * @throws CaptureSpaceErrors::NO_INSTALL_IS_AVAILABLE
	 */
	public function serveInstallAction($os)
	{
		$filename = kCaptureSpaceVersionManager::getInstallFile($os);
		if (!$filename) {
			throw new BorhanAPIException(CaptureSpaceErrors::NO_INSTALL_IS_AVAILABLE, $os);
		}
		
		$actualFilePath = myContentStorage::getFSContentRootPath() . "/content/third_party/capturespace/$filename";
		if (!file_exists($actualFilePath)) {
			throw new BorhanAPIException(CaptureSpaceErrors::NO_INSTALL_IS_AVAILABLE, $os);
		}
		
		$mimeType = kFile::mimeType($actualFilePath);
		header("Content-Disposition: attachment; filename=\"$filename\"");
		return $this->dumpFile($actualFilePath, $mimeType);
	}


	/**
	 * Serve update file
	 *
	 * @action serveUpdate
	 * @param string $os
	 * @param string $version
	 * @return file
	 * @throws CaptureSpaceErrors::NO_UPDATE_IS_AVAILABLE
	 */
	public function serveUpdateAction($os, $version)
	{
		$filename = kCaptureSpaceVersionManager::getUpdateFile($os, $version);
		if (!$filename) {
			throw new BorhanAPIException(CaptureSpaceErrors::NO_UPDATE_IS_AVAILABLE, $version, $os);
		}
		
		$actualFilePath = myContentStorage::getFSContentRootPath() . "/content/third_party/capturespace/$filename";
		if (!file_exists($actualFilePath)) {
			throw new BorhanAPIException(CaptureSpaceErrors::NO_UPDATE_IS_AVAILABLE, $version, $os);
		}
		
		$mimeType = kFile::mimeType($actualFilePath);
		header("Content-Disposition: attachment; filename=\"$filename\"");
		return $this->dumpFile($actualFilePath, $mimeType);
	}
}


