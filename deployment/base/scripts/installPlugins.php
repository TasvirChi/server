<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

myDbHelper::$use_alternative_con = myDbHelper::DB_HELPER_CONN_MASTER;

kPluginableEnumsManager::enableNewValues();

$pluginInstances = BorhanPluginManager::getPluginInstances('IBorhanEnumerator');
foreach($pluginInstances as $pluginInstance)
{
	$pluginName = $pluginInstance->getPluginName();
	BorhanLog::debug("Installs plugin [$pluginName]");
	$enums = $pluginInstance->getEnums();
	foreach($enums as $enum)
	{
		$interfaces = class_implements($enum);
		foreach($interfaces as $interface)
		{
			if($interface == 'IBorhanPluginEnum' || $interface == 'BaseEnum')
				continue;
		
			$interfaceInterfaces = class_implements($interface);
			if(!in_array('BaseEnum', $interfaceInterfaces))
				continue;
				
			BorhanLog::debug("Installs enum [$enum] of type [$interface]");
			$values = call_user_func(array($enum, 'getAdditionalValues'));
			foreach($values as $value)
			{
				$enumValue = $pluginName . IBorhanEnumerator::PLUGIN_VALUE_DELIMITER . $value;
				BorhanLog::debug("Installs enum value [$enumValue] to type [$interface]");
				kPluginableEnumsManager::apiToCore($interface, $enumValue);
			}
		}
	}
}

