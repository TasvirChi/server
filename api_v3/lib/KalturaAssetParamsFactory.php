<?php
/**
 * @package api
 * @subpackage objects.factory
 */
class BorhanAssetParamsFactory
{
	static function getAssetParamsOutputInstance($type)
	{
		switch ($type) 
		{
			case BorhanAssetType::FLAVOR:
				return new BorhanFlavorParamsOutput();
				
			case BorhanAssetType::THUMBNAIL:
				return new BorhanThumbParamsOutput();
				
			default:
				$obj = BorhanPluginManager::loadObject('BorhanAssetParamsOutput', $type);
				if($obj)
					return $obj;
					
				return new BorhanFlavorParamsOutput();
		}
	}
	
	static function getAssetParamsInstance($type)
	{
		switch ($type) 
		{
			case BorhanAssetType::FLAVOR:
				return new BorhanFlavorParams();
				
			case BorhanAssetType::THUMBNAIL:
				return new BorhanThumbParams();
				
			default:
				$obj = BorhanPluginManager::loadObject('BorhanAssetParams', $type);
				if($obj)
					return $obj;
					
				return new BorhanFlavorParams();
		}
	}
}
