<?php
/**
 * @package api
 * @subpackage objects.factory
 */
class BorhanFlavorParamsFactory
{
	static function getFlavorParamsOutputInstance($type)
	{
		switch ($type) 
		{
			case BorhanAssetType::FLAVOR:
				return new BorhanFlavorParamsOutput();
				
			case BorhanAssetType::THUMBNAIL:
				return new BorhanThumbParamsOutput();
				
			default:
				$obj = BorhanPluginManager::loadObject('BorhanFlavorParamsOutput', $type);
				if($obj)
					return $obj;
					
				return new BorhanFlavorParamsOutput();
		}
	}
	
	static function getFlavorParamsInstance($type)
	{
		switch ($type) 
		{
			case BorhanAssetType::FLAVOR:
				return new BorhanFlavorParams();
				
			case BorhanAssetType::THUMBNAIL:
				return new BorhanThumbParams();
				
			case BorhanAssetType::LIVE:
				return new BorhanLiveParams();
				
			default:
				$obj = BorhanPluginManager::loadObject('BorhanFlavorParams', $type);
				if($obj)
					return $obj;
					
				return new BorhanFlavorParams();
		}
	}
}
