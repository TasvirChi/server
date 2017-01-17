<?php

/**
 * @package api
 * @subpackage objects.factory
 */
class BorhanDeliveryProfileFactory {
	
	public static function getCoreDeliveryProfileInstanceByType($type) {
		$coreType = kPluginableEnumsManager::apiToCore('DeliveryProfileType', $type); 
		$class = DeliveryProfilePeer::getClassByDeliveryProfileType($coreType);
		return new $class();
	}
	
	public static function getDeliveryProfileInstanceByType($type) {
		switch ($type) {
			case BorhanDeliveryProfileType::GENERIC_HLS:
			case BorhanDeliveryProfileType::GENERIC_HLS_MANIFEST:
				return new BorhanDeliveryProfileGenericAppleHttp();
			case BorhanDeliveryProfileType::GENERIC_HDS:
			case BorhanDeliveryProfileType::GENERIC_HDS_MANIFEST:
				return new BorhanDeliveryProfileGenericHds();
			case BorhanDeliveryProfileType::GENERIC_HTTP:
					return new BorhanDeliveryProfileGenericHttp();
			case BorhanDeliveryProfileType::RTMP:
			case BorhanDeliveryProfileType::LIVE_RTMP:
				return new BorhanDeliveryProfileRtmp();
			case BorhanDeliveryProfileType::AKAMAI_HTTP:
				return new BorhanDeliveryProfileAkamaiHttp();
			case BorhanDeliveryProfileType::AKAMAI_HLS_MANIFEST:
				return new BorhanDeliveryProfileAkamaiAppleHttpManifest();
			case BorhanDeliveryProfileType::AKAMAI_HDS:
				return new BorhanDeliveryProfileAkamaiHds();
			case BorhanDeliveryProfileType::LIVE_PACKAGER_HLS:
			case BorhanDeliveryProfileType::LIVE_HLS:
				return new BorhanDeliveryProfileLiveAppleHttp();
			case BorhanDeliveryProfileType::GENERIC_SS:
				return new BorhanDeliveryProfileGenericSilverLight();
			case BorhanDeliveryProfileType::GENERIC_RTMP:
				return new BorhanDeliveryProfileGenericRtmp();
			case BorhanDeliveryProfileType::VOD_PACKAGER_HLS:
				return new BorhanDeliveryProfileVodPackagerHls();
			case BorhanDeliveryProfileType::VOD_PACKAGER_DASH:
				return new BorhanDeliveryProfileVodPackagerPlayServer();
			case BorhanDeliveryProfileType::VOD_PACKAGER_MSS:
				return new BorhanDeliveryProfileVodPackagerPlayServer();
			default:
				$obj = BorhanPluginManager::loadObject('BorhanDeliveryProfile', $type);
				if(!$obj)
					$obj = new BorhanDeliveryProfile();
				return $obj;
		}
	}
	
	public static function getTokenizerInstanceByType($type) {
		switch ($type) {
			case 'kLevel3UrlTokenizer':
				return new BorhanUrlTokenizerLevel3();
			case 'kLimeLightUrlTokenizer':
				return new BorhanUrlTokenizerLimeLight();
			case 'kAkamaiHttpUrlTokenizer':
				return new BorhanUrlTokenizerAkamaiHttp();
			case 'kAkamaiRtmpUrlTokenizer':
				return new BorhanUrlTokenizerAkamaiRtmp();
			case 'kAkamaiRtspUrlTokenizer':
				return new BorhanUrlTokenizerAkamaiRtsp();
			case 'kAkamaiSecureHDUrlTokenizer':
				return new BorhanUrlTokenizerAkamaiSecureHd();
			case 'kCloudFrontUrlTokenizer':
				return new BorhanUrlTokenizerCloudFront();
			case 'kBitGravityUrlTokenizer':
				return new BorhanUrlTokenizerBitGravity();
			case 'kVnptUrlTokenizer':
				return new BorhanUrlTokenizerVnpt();
			case 'kChtHttpUrlTokenizer':
				return new BorhanUrlTokenizerCht();	
			case 'kKsUrlTokenizer':
				return new BorhanUrlTokenizerKs();

			// Add other tokenizers here
			default:
				$apiObject = BorhanPluginManager::loadObject('BorhanTokenizer', $type);
				if($apiObject)
					return $apiObject;
				BorhanLog::err("Cannot load API object for core Tokenizer [" . $type . "]");
				return null;
		}
	}
	
	public static function getRecognizerByType($type) {
		switch ($type) {
			case 'kUrlRecognizerAkamaiG2O':
				return new BorhanUrlRecognizerAkamaiG2O();
			case 'kUrlRecognizer':
				return new BorhanUrlRecognizer();
			default:
				$apiObject = BorhanPluginManager::loadObject('BorhanRecognizer', $type);
				if($apiObject)
					return $apiObject;
				BorhanLog::err("Cannot load API object for core Recognizer [" . $type . "]");
				return null;
		}
	}

}
