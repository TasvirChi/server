<?php
/**
 * @package api
 * @subpackage v3
 */
class BorhanDispatcher 
{
	private static $instance = null;
	private $arguments = null;

	const OWNER_ONLY_OPTION = "ownerOnly";
	/**
	 * Return a BorhanDispatcher instance
	 *
	 * @return BorhanDispatcher
	 */
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}
		    
		return self::$instance;
	}
	
	public function getArguments()
	{
		return $this->arguments;
	}
	
	public function dispatch($service, $action, $params = array()) 
	{
		$start = microtime( true );
	
		// prevent impersonate to partner zero
		$p = isset($params["p"]) && $params["p"] ? $params["p"] : null;
		if (!$p) 
			$p = isset($params["partnerId"]) && $params["partnerId"] ? $params["partnerId"] : null;
			
		$GLOBALS["partnerId"] = $p; // set for logger
		
		$userId = "";
		$ksStr = isset($params["ks"]) ? $params["ks"] : null ;
		 
		if (!$service)
			throw new BorhanAPIException(BorhanErrors::SERVICE_NOT_SPECIFIED);
		
        //strtolower on service - map is indexed according to lower-case service IDs
        $service = strtolower($service);
        
        $serviceActionItem = BorhanServicesMap::retrieveServiceActionItem($service, $action);
        $action = strtolower($action);
        if (!isset($serviceActionItem->actionMap[$action]))
        {
            BorhanLog::crit("Action does not exist!");
		    throw new BorhanAPIException(BorhanErrors::ACTION_DOES_NOT_EXISTS, $action, $service);
        }
        
        try
        {
	        $actionReflector = new BorhanActionReflector($service, $action, $serviceActionItem->actionMap[$action]);
        }
        catch (Exception $e)
        {
             throw new Exception("Could not create action reflector for service [$service], action [$action]. Received error: ". $e->getMessage());
        }
        
		$actionParams = $actionReflector->getActionParams();
		$actionInfo = $actionReflector->getActionInfo();
		// services.ct - check if partner is allowed to access service ...
		kCurrentContext::$host = (isset($_SERVER["HOSTNAME"]) ? $_SERVER["HOSTNAME"] : gethostname());
		kCurrentContext::$user_ip = requestUtils::getRemoteAddress();
		kCurrentContext::$ps_vesion = "ps3";
		kCurrentContext::$service = $serviceActionItem->serviceInfo->serviceName;
		kCurrentContext::$action =  $action;
		kCurrentContext::$client_lang =  isset($params['clientTag']) ? $params['clientTag'] : null;
		kCurrentContext::initKsPartnerUser($ksStr, $p, $userId);

		// validate it's ok to access this service
		$deserializer = new BorhanRequestDeserializer($params);
		$this->arguments = $deserializer->buildActionArguments($actionParams);
		BorhanLog::debug("Dispatching service [".$service."], action [".$action."], reqIndex [".
			kCurrentContext::$multiRequest_index."] with params " . print_r($this->arguments, true));

		$responseProfile = $deserializer->getResponseProfile();
		if($responseProfile)
		{
			BorhanLog::debug("Response profile: " . print_r($responseProfile, true));
		}
		
		PermissionPeer::preFetchPermissions(array(PermissionName::FEATURE_END_USER_REPORTS, PermissionName::FEATURE_ENTITLEMENT));

		kPermissionManager::init(kConf::get('enable_cache'));
		kEntitlementUtils::initEntitlementEnforcement();
		
	    $disableTags = $actionInfo->disableTags;
		if($disableTags && is_array($disableTags) && count($disableTags))
		{
			foreach ($disableTags as $disableTag)
			{
			    BorhanCriterion::disableTag($disableTag);
			}
		}
		
		if($actionInfo->validateUserObjectClass && $actionInfo->validateUserIdParamName && isset($actionParams[$actionInfo->validateUserIdParamName]))
		{
//			// TODO maybe if missing should throw something, maybe a bone?
//			if(!isset($actionParams[$actionInfo->validateUserIdParamName]))
//				throw new BorhanAPIException(BorhanErrors::MISSING_MANDATORY_PARAMETER, $actionInfo->validateUserIdParamName);
			BorhanLog::debug("validateUserIdParamName: ".$actionInfo->validateUserIdParamName);
			$objectId = $params[$actionInfo->validateUserIdParamName];
			$this->validateUser($actionInfo->validateUserObjectClass, $objectId, $actionInfo->validateUserPrivilege, $actionInfo->validateOptions);
		}
		
		// initialize the service before invoking the action on it
		// action reflector will init the service to maintain the pluginable action transparency
		$actionReflector->initService($responseProfile);
		
		$invokeStart = microtime(true);
		BorhanLog::debug("Invoke start");
		try
		{
			$res =  $actionReflector->invoke( $this->arguments );
		}
		catch (BorhanAPIException $e)
		{
			if ($actionInfo->returnType != 'file')
			{
				 throw ($e);
			}
					
			BorhanResponseCacher::adjustApiCacheForException($e);
			$res = new kRendererDieError ($e->getCode(), $e->getMessage());
		 }
		
		kEventsManager::flushEvents();
		
		BorhanLog::debug("Invoke took - " . (microtime(true) - $invokeStart) . " seconds");
		BorhanLog::debug("Dispatch took - " . (microtime(true) - $start) . " seconds, memory: ".memory_get_peak_usage(true));		
				
		
		return $res;
	}
	

	/**
	 * @param string $objectClass
	 * @param string $objectId
	 * @param string $privilege optional
	 * @param string $options optional
	 * @throws BorhanErrors::INVALID_KS
	 */
	protected function validateUser($objectClass, $objectId, $privilege = null, $options = null)
	{
		// don't allow operations without ks
		if (!kCurrentContext::$ks_object)
			throw new BorhanAPIException(BorhanErrors::INVALID_KS, "", ks::INVALID_TYPE, ks::getErrorStr(ks::INVALID_TYPE));

		// if admin always allowed
		if (kCurrentContext::$is_admin_session)
			return;

		
		$objectGetters = null;
		if(strstr($objectClass, '::'))
		{
			$objectGetters = explode('::', $objectClass);
			$objectClass = array_shift($objectGetters);
		}
			
		$objectClassPeer = "{$objectClass}Peer";
		if(!class_exists($objectClassPeer))
			return;
			
		$dbObject = $objectClassPeer::retrieveByPK($objectId);
		
		if($objectGetters)
		{
			foreach($objectGetters as $objectGetter)
			{
				$getterMethod = "get{$objectGetter}";
				
				$reflector = new ReflectionObject($dbObject); 
				
				if (! $reflector->hasMethod($getterMethod))
				{
					BorhanLog::err("Method ".$getterMethod. " does not exist for class ".$reflector->getName());
					return;
				}
				
				$dbObject = $dbObject->$getterMethod();
			}
		}
		
		if(!($dbObject instanceof IOwnable))
		{
			return;
		}
	
		if($privilege)
		{
			// check if all ids are privileged
			if (kCurrentContext::$ks_object->verifyPrivileges($privilege, ks::PRIVILEGE_WILDCARD))
			{
				return;
			}
				
			// check if object id is privileged
			if (kCurrentContext::$ks_object->verifyPrivileges($privilege, $dbObject->getId()))
			{
				return;
			}
			
		}
		
		if ((strtolower($dbObject->getPuserId()) != strtolower(kCurrentContext::$ks_uid)) && (kCurrentContext::$ks_partner_id != Partner::MEDIA_SERVER_PARTNER_ID))
		{
			$optionsArray = array();
			if ( $options ) {
				$optionsArray = explode(",", $options);
			}
			if (!$dbObject->isEntitledKuserEdit(kCurrentContext::getCurrentKsKuserId())
				||
				(in_array(self::OWNER_ONLY_OPTION,$optionsArray)))
					throw new BorhanAPIException(BorhanErrors::INVALID_KS, "", ks::INVALID_TYPE, ks::getErrorStr(ks::INVALID_TYPE));
		}
	}
}
