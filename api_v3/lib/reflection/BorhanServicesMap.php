<?php 
/**
 * @package api
 * @subpackage v3
 */
class BorhanServicesMap
{
	/**
	 * @var array <BorhanServiceActionItem>
	 */
	private static $services = array();
	
	private static $extraServices = array();
	
	const SERVICES_MAP_MODIFICATION_TIME = "serviceMapModificationTime";
	
	public static function addService($serviceId, $class)
	{
		$serviceId = strtolower($serviceId);
		if(class_exists($class))
			self::$extraServices[$serviceId] = $class;
	}
	
	static function getMap()
	{
		if(!count(self::$services))
		{
			$cacheFilePathArray = array(kConf::get("cache_root_path"), 'api_v3', 'BorhanServicesMap.cache');
			$cacheFilePath = implode(DIRECTORY_SEPARATOR, $cacheFilePathArray);
			if (!file_exists($cacheFilePath))
			{
				$servicesPathArray = array(BORHAN_API_PATH, 'services',);
				$servicesPath = implode(DIRECTORY_SEPARATOR, $servicesPathArray);
				self::cacheMap($servicesPath, $cacheFilePath);
				if (!file_exists($cacheFilePath))
					throw new Exception('Failed to save services cached map to ['.$cacheFilePath.']');
			}
			
			self::$services = unserialize(file_get_contents($cacheFilePath));
		}
		return self::$services + self::$extraServices;
	}
	
	static function getService($serviceId)
	{
		$services = self::getMap();
		if(isset($services[$serviceId]))
			return $services[$serviceId];
			
		return null;
	}
	
	static function getServiceIdsFromName($serviceName)
	{
		$serviceIds = array();
		$allServices = self::getMap();
		foreach ($allServices as $currentServiceId => $currentService)
		{
			$currentServiceName = end(explode('_', $currentServiceId));
			if (strtolower($currentServiceName) === strtolower($serviceName)) {
				$serviceIds[] = $currentServiceId;
			}
		}
		return $serviceIds;
	}
	
	static function filterEmptyServices($service)
	{
		return count($service->actionMap) != 0;
	}
	
	//TODO create a function for the subsequent loops
	static function cacheMap($servicePath, $cacheFilePath)
	{
		if (!is_dir($servicePath))
			throw new Exception('Invalid directory ['.$servicePath.']');
			
		$servicePath = realpath($servicePath);
		$serviceMap = array();
		$classMap = KAutoloader::getClassMap();
		$checkedClasses = array();
		
		//Retrieve all service classes from the classMap.
		$serviceClasses = array();
		foreach ($classMap as $class => $classFilePath)
		{
		    $classFilePath = realpath($classFilePath);
			if (strpos($classFilePath, $servicePath) === 0) // make sure the class is in the request service path
			{
				$reflectionClass = new ReflectionClass($class);
				
				
				if ($reflectionClass->isSubclassOf('BorhanBaseService'))
				{
				    $serviceDoccomment = new BorhanDocCommentParser($reflectionClass->getDocComment());
				    $serviceClasses[$serviceDoccomment->serviceName] = $class;
				}
			}
		}
		
		//Retrieve all plugin service classes.
		$pluginInstances = BorhanPluginManager::getPluginInstances('IBorhanServices');
		foreach($pluginInstances as $pluginName => $pluginInstance)
		{
			$pluginServices = $pluginInstance->getServicesMap();
			foreach($pluginServices as $serviceName => $serviceClass)
			{
			    $serviceName = strtolower($serviceName);
				$serviceId = "{$pluginName}_{$serviceName}";
			    $serviceClasses[$serviceId] = $serviceClass;
			}
		}
		
		//Add core & plugin services to the services map
		$aliasActions = array();
		foreach($serviceClasses as $serviceId => $serviceClass)
		{
			$serviceReflectionClass = BorhanServiceReflector::constructFromClassName($serviceClass);
			$serviceMapEntry = new BorhanServiceActionItem();
			$serviceMapEntry->serviceId = $serviceId;
			$serviceMapEntry->serviceClass = $serviceClass;
			$serviceMapEntry->serviceInfo = $serviceReflectionClass->getServiceInfo();
            $actionMap = array();
            $nativeActions = $serviceReflectionClass->getActions();
            foreach ($nativeActions as $actionId => $actionName)	
            {
                $actionMap[strtolower($actionId)] = array ("serviceClass" => $serviceClass, "actionMethodName" => $actionName, "serviceId" => $serviceId, "actionName" => $actionId);
            }	

            $serviceMapEntry->actionMap = $actionMap;
            $serviceMap[strtolower($serviceId)] = $serviceMapEntry;
            
            foreach ($serviceReflectionClass->getAliasActions() as $alias => $methodName)
            	$aliasActions[$alias] = "$serviceId.$methodName";
		}
		
		// add aliases
		foreach ($aliasActions as $aliasAction => $sourceAction)
		{
			list($aliasService, $aliasAction) = explode('.', $aliasAction);
			list($sourceService, $sourceAction) = explode('.', $sourceAction);
			$aliasService = strtolower($aliasService);
			$sourceService = strtolower($sourceService);
			
			$extServiceClass = $serviceClasses[$sourceService];
			
			if(!isset($serviceMap[$aliasService]))
				throw new Exception("Alias service [$aliasService] not found");
			
			$serviceMap[$aliasService]->actionMap[strtolower($aliasAction)] = 
				array ("serviceClass" => $extServiceClass, "actionMethodName" => $sourceAction, "serviceId" => $sourceService, "actionName" => $aliasAction);
		}
		
		// filter out services that have no actions
		$serviceMap = array_filter($serviceMap, array('BorhanServicesMap', 'filterEmptyServices'));

		if (!is_dir(dirname($cacheFilePath))) {
			mkdir(dirname($cacheFilePath));
			chmod(dirname($cacheFilePath), 0755);
		}
		kFile::safeFilePutContents($cacheFilePath, serialize($serviceMap), 0644);
	}
	
	public static function getServiceMapModificationTime ()
	{
	    $cacheFilePathArray = array(kConf::get("cache_root_path"), 'api_v3', 'BorhanServicesMap.cache');
		$cacheFilePath = implode(DIRECTORY_SEPARATOR, $cacheFilePathArray);
	    return filemtime($cacheFilePath);
	}
	
    /**
     * Function tpo retrieve a specific BorhanServiceActionItem from the cache by a service ID and action ID.
     * If the item was not found, it is retrieved from the services map and cached.
     * @param string $serviceId
     * @param string $actionId
     * @throws BorhanAPIException
     * @return BorhanServiceActionItem
     */
    public static function retrieveServiceActionItem($serviceId, $actionId)
	{
        if (function_exists('apc_fetch'))
        {
            $apcFetchSuccess = null;
            $serviceItemFromCache = apc_fetch($serviceId, $apcFetchSuccess);
            if ($apcFetchSuccess && $serviceItemFromCache[BorhanServicesMap::SERVICES_MAP_MODIFICATION_TIME] == self::getServiceMapModificationTime())
            {
                return $serviceItemFromCache["serviceActionItem"];
            } 
        }
		
		// load the service reflector
		$serviceMap = self::getMap();
		
		if(!isset($serviceMap[$serviceId]))
		{
			BorhanLog::crit("Service [$serviceId] does not exist!");
			throw new BorhanAPIException(BorhanErrors::SERVICE_DOES_NOT_EXISTS, $serviceId);
		}
		
		// check if action exists
		if(!$actionId)
		{
			BorhanLog::crit("Action not specified!");
			throw new BorhanAPIException(BorhanErrors::ACTION_NOT_SPECIFIED, $serviceId);
		}
		$reflector = $serviceMap[$serviceId];
		
		if(function_exists('apc_store'))
		{
			$servicesMapLastModTime = self::getServiceMapModificationTime();
			$success = apc_store($serviceId, array("serviceActionItem" => $serviceMap[$serviceId], BorhanServicesMap::SERVICES_MAP_MODIFICATION_TIME => $servicesMapLastModTime));
		}
		
		return $reflector;
	}
	
}
