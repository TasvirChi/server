<?php
/**
 * @package api
 * @subpackage enum
 */
abstract class BorhanDynamicEnum extends BorhanStringEnum implements IBorhanDynamicEnum
{
	public static function mergeDescriptions($baseEnumName, array $descriptions)
	{
		$pluginInstances = BorhanPluginManager::getPluginInstances('IBorhanEnumerator');
		foreach($pluginInstances as $pluginInstance)
		{
			$pluginName = $pluginInstance->getPluginName();
			$enums = $pluginInstance->getEnums($baseEnumName);
			foreach($enums as $enum)
			{
				$additionalDescriptions = $enum::getAdditionalDescriptions();
				foreach($additionalDescriptions as $key => $description)
					$descriptions[$key] = $description;
			}
		}
		return $descriptions;
	}
}
