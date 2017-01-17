<?php

/**
 *
 * @service upload
 * @package api
 * @subpackage services
 * @deprecated Please use UploadToken service
 */
class UploadService extends BorhanEntryService
{
	/**
	 * 
	 * @action upload
	 * @param file $fileData The file data
	 * @return string Upload token id
	 */
	function uploadAction($fileData)
	{
		$ksUnique = md5($this->getKs()->toSecureString());
		
		$uniqueId = md5($fileData["name"]);
		
		$ext = pathinfo($fileData["name"], PATHINFO_EXTENSION);
		$token = $ksUnique."_".$uniqueId.".".$ext;
		
		$res = myUploadUtils::uploadFileByToken($fileData, $token, "", null, true);
	
		return $res["token"];
	}
	
	/**
	 * 
	 * @action getUploadedFileTokenByFileName
	 * @param string $fileName
	 * @return BorhanUploadResponse
	 */
	function getUploadedFileTokenByFileNameAction($fileName)
	{
		BorhanResponseCacher::disableConditionalCache();
		
		$res = new BorhanUploadResponse();
		$ksUnique = md5($this->getKs()->toSecureString());
		
		$uniqueId = md5($fileName);
		
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		$token = $ksUnique."_".$uniqueId.".".$ext;
		
		$entryFullPath = myUploadUtils::getUploadPath($token, "", null , strtolower($ext)); // filesync ok
		if (!file_exists($entryFullPath))
			throw new BorhanAPIException(BorhanErrors::UPLOADED_FILE_NOT_FOUND_BY_TOKEN);
			
		$res->uploadTokenId = $token;
		$res->fileSize = kFile::fileSize($entryFullPath);
		return $res; 
	}
}