<?php
/**
 * Adjust asset-params with watermarks according to custom metadata
 *
 * @package plugins.watermark
 */
class WatermarkPlugin extends BorhanPlugin implements IBorhanPending, IBorhanAssetParamsAdjuster
{
	const PLUGIN_NAME = 'watermark';
	
	const METADATA_PLUGIN_NAME = 'metadata';
	const METADATA_PLUGIN_VERSION_MAJOR = 1;
	const METADATA_PLUGIN_VERSION_MINOR = 0;
	const METADATA_PLUGIN_VERSION_BUILD = 0;

	const TRANSCODING_METADATA_PROF_SYSNAME = 'TRANSCODINGPARAMS';
		
	const TRANSCODING_METADATA_WATERMMARK_SETTINGS = 'WatermarkSettings';
	const TRANSCODING_METADATA_WATERMMARK_IMAGE_ENTRY = 'WatermarkImageEntry';
	const TRANSCODING_METADATA_WATERMMARK_IMAGE_URL = 'WatermarkImageURL';
	
	/* (non-PHPdoc)
	 * @see IBorhanPlugin::getPluginName()
	 */
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanPending::dependsOn()
	 */
	public static function dependsOn()
	{
		$metadataVersion = new BorhanVersion(self::METADATA_PLUGIN_VERSION_MAJOR, self::METADATA_PLUGIN_VERSION_MINOR, self::METADATA_PLUGIN_VERSION_BUILD);
		$metadataDependency = new BorhanDependency(self::METADATA_PLUGIN_NAME, $metadataVersion);
		
		return array($metadataDependency);
	}
		
	/* (non-PHPdoc)
	 * @see IBorhanAssetParamsAdjuster::adjustAssetParams()
	 */
	public function adjustAssetParams($entryId, array &$flavors)
	{
		$entry = entryPeer::retrieveByPK($entryId);
		if(!isset($entry)){
			BorhanLog::warning("Bad entry id ($entryId).");
			return;
		}

		$partnerId = $entry->getPartnerId();
		$profile = MetadataProfilePeer::retrieveBySystemName(self::TRANSCODING_METADATA_PROF_SYSNAME,$partnerId);
		if(!isset($profile)){
			BorhanLog::log("No Transcoding Metadata Profile (sysName:".self::TRANSCODING_METADATA_PROF_SYSNAME.", partner:$partnerId). Nothing to adjust");
			return;
		}

		$metadata = MetadataPeer::retrieveByObject($profile->getId(), MetadataObjectType::ENTRY, $entryId);
		if(!isset($metadata)){
			BorhanLog::log("No Metadata for entry($entryId), metadata profile (id:".$profile->getId()."). Nothing to adjust");
			return;
		}

		BorhanLog::log("Entry ($entryId) has following metadata fields:".print_r($metadata,1));
		
		// Retrieve the associated XML file
		$key = $metadata->getSyncKey(Metadata::FILE_SYNC_METADATA_DATA);
		if(!isset($key)){
			BorhanLog::log("Entry($entryId) metadata object misses file sync key! Nothing to adjust");
			return;
		}
		$xmlStr = kFileSyncUtils::file_get_contents($key, true, false);
		if(!isset($xmlStr)){
			BorhanLog::log("Entry($entryId) metadata object misses valid file sync! Nothing to adjust");
			return;
		}
		
		BorhanLog::log("Adjusting: entry($entryId),metadata profile(".self::TRANSCODING_METADATA_PROF_SYSNAME."),xml==>$xmlStr");

		// Retrieve the custom metadata fields from the asocieted XML
		
		
		/*
		 * Acquire the optional 'full' WM settings (TRANSCODING_METADATA_WATERMMARK_SETTINGS) 
		 * adjust it to custom meta imageEntry/imageUrl values,
		 * if those provided.
		 */
		$watermarkSettings = array();
		$xml = new SimpleXMLElement($xmlStr);
		$fldName = self::TRANSCODING_METADATA_WATERMMARK_SETTINGS;

		if(isset($xml->$fldName)) {
			$watermarkSettingsStr =(string)$xml->$fldName;
			BorhanLog::log("Found custom metadata - $fldName($watermarkSettingsStr)");
			if(isset($watermarkSettingsStr)) {
				$watermarkSettings = json_decode($watermarkSettingsStr);
				if(!is_array($watermarkSettings)) {
					$watermarkSettings = array($watermarkSettings);
				}
				BorhanLog::log("WM($fldName) object:".serialize($watermarkSettings));
			}
		}
		else
			BorhanLog::log("No custom metadata - $fldName");

		/*
		 * Acquire the optional partial WM settings ('imageEntry'/'url') 
		 * Prefer the 'imageEntry' in case when both 'imageEntr' and 'url' are previded ('url' ignored).
		 */
		$wmTmp = null;
		$fldName = self::TRANSCODING_METADATA_WATERMMARK_IMAGE_ENTRY;
		if(isset($xml->$fldName)) {
			$wmTmp->imageEntry =(string)$xml->$fldName;
			BorhanLog::log("Found custom metadata - $fldName($wmTmp->imageEntry)");
		}
		else {
			BorhanLog::log("No custom metadata - $fldName");
			$fldName = self::TRANSCODING_METADATA_WATERMMARK_IMAGE_URL;
			if(isset($xml->$fldName)) {
				$fldVal = (string)$xml->$fldName;
				$wmTmp->url =(string)$xml->$fldName;
				BorhanLog::log("Found custom metadata - $fldName($wmTmp->url)");
			}
			else 
				BorhanLog::log("No custom metadata - $fldName");
		}
		
		/*
		 * Merge the imageEntry/imageUrl values into previously aquired 'full' WM settings (if provided).
		 */
		if(isset($wmTmp))
			$watermarkSettings = self::adjustWatermarSettings($watermarkSettings, $wmTmp);
		BorhanLog::log("Custom meta data WM settings:".serialize($watermarkSettings));

		/*
		 * Check for valuable WM custom data.
		 * If none - leave
		 */
		{
			foreach($watermarkSettings as $wmI=>$wmTmp){
				if(isset($wmTmp)){
					$fldCnt+= count((array)$wmTmp);
				}
			}
			if($fldCnt==0){
				BorhanLog::log("No WM custom data to merge");
				return;
			}
		}
		
		/*
		 * Loop through the flavor params to update the WM settings,
		 * if it is required.
		 */
		foreach($flavors as $k=>$flavor) {
			BorhanLog::log("Processing flavor id:".$flavor->getId());
			$wmDataFixed = null;
			$wmPredefined = null;
			$wmPredefinedStr = $flavor->getWatermarkData();
			if(!(isset($wmPredefinedStr) && ($wmPredefined=json_decode($wmPredefinedStr))!=null)){
				BorhanLog::log("No WM data for flavor:".$flavor->getId());
				continue;
			}
			BorhanLog::log("wmPredefined : count(".count($wmPredefined).")-".serialize($wmPredefined));

			$wmDataFixed = self::adjustWatermarSettings($wmPredefined, $watermarkSettings);

			/*
			 * The 'full' WM settings in the custom metadata overides any exitings WM settings 
			 */
			$wmJsonStr = json_encode($wmDataFixed);
			$flavor->setWatermarkData($wmJsonStr);
			$flavors[$k]= $flavor;
			BorhanLog::log("Update flavor (".$flavor->getId().") WM to: $wmJsonStr");
		}
	}

	/**
	 * 
	 * @param unknown_type $watermarkData
	 * @param unknown_type $watermarkToMerge
	 */
	protected static function adjustWatermarSettings($watermarkData, $watermarkToMerge)
	{
		BorhanLog::log("Merge WM (".serialize($watermarkToMerge).") into (".serialize($watermarkData).")");
		if(is_array($watermarkData))
			$watermarkDataArr = $watermarkData;
		else 
			$watermarkDataArr = array($watermarkData);
		
		if(is_array($watermarkToMerge))
			$watermarkToMergeArr = $watermarkToMerge;
		else 
			$watermarkToMergeArr = array($watermarkToMerge);
		
		foreach($watermarkToMergeArr as $wmI=>$watermarkToMerge){
			BorhanLog::log("Merging WM:$wmI");
			if(!array_key_exists($wmI, $watermarkDataArr)){
				$watermarkDataArr[$wmI] = $watermarkToMerge;
				BorhanLog::log("Added object ($wmI)-".serialize($watermarkToMerge));
				continue;
			}

			foreach($watermarkToMerge as $fieldName=>$fieldValue){
				$watermarkDataArr[$wmI]->$fieldName = $fieldValue;
				BorhanLog::log("set($fieldName):".$fieldValue);
				switch($fieldName){
				case "imageEntry":
					BorhanLog::log("unset(url):".$watermarkDataArr[$wmI]->url);
					unset($watermarkDataArr[$wmI]->url);
					break;
				case  "url":
					BorhanLog::log("unset(imageEntry):".$watermarkDataArr[$wmI]->imageEntry);
					unset($watermarkDataArr[$wmI]->imageEntry);
					break;
				}
			}
		}
		
		BorhanLog::log("Merged WM (".serialize($watermarkDataArr).")");
		return $watermarkDataArr;
	}
}
