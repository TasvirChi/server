<?php
/**
 * @package plugins.contentDistribution
 * @subpackage api.objects
 */
class BorhanGenericDistributionJobProviderData extends BorhanDistributionJobProviderData
{
	private static $actionAttributes = array(
		BorhanDistributionAction::SUBMIT => 'submitAction',
		BorhanDistributionAction::UPDATE => 'updateAction',
		BorhanDistributionAction::DELETE => 'deleteAction',
		BorhanDistributionAction::FETCH_REPORT => 'fetchReportAction',
	);
	
	/**
	 * @var string
	 */
	public $xml;
	
	/**
	 * @var string
	 */
	public $resultParseData;
	
	/**
	 * @var BorhanGenericDistributionProviderParser
	 */
	public $resultParserType;
	
	public function __construct(BorhanDistributionJobData $distributionJobData = null)
	{
		if(!$distributionJobData)
			return;
			
		$action = BorhanDistributionAction::SUBMIT;
		if($distributionJobData instanceof BorhanDistributionDeleteJobData)
			$action = BorhanDistributionAction::DELETE;
		if($distributionJobData instanceof BorhanDistributionUpdateJobData)
			$action = BorhanDistributionAction::UPDATE;
		if($distributionJobData instanceof BorhanDistributionFetchReportJobData)
			$action = BorhanDistributionAction::FETCH_REPORT;
			
		if(!($distributionJobData->distributionProfile instanceof BorhanGenericDistributionProfile))
		{
			BorhanLog::err("Distribution profile is not generic");
			return;
		}
		
		$this->loadProperties($distributionJobData, $distributionJobData->distributionProfile, $action);
	}
	
	public function loadProperties(BorhanDistributionJobData $distributionJobData, BorhanGenericDistributionProfile $distributionProfile, $action)
	{
		$actionName = self::$actionAttributes[$action];
		
		$genericProviderAction = GenericDistributionProviderActionPeer::retrieveByProviderAndAction($distributionProfile->genericProviderId, $action);
		if(!$genericProviderAction)
		{
			BorhanLog::err("Generic provider [{$distributionProfile->genericProviderId}] action [$actionName] not found");
			return;
		}
		
		if(!$distributionJobData->entryDistribution)
		{
			BorhanLog::err("Entry Distribution object not provided");
			return;
		}
		
		if(!$distributionProfile->$actionName->protocol)
			$distributionProfile->$actionName->protocol = $genericProviderAction->getProtocol();
		if(!$distributionProfile->$actionName->serverUrl)
			$distributionProfile->$actionName->serverUrl = $genericProviderAction->getServerAddress();
		if(!$distributionProfile->$actionName->serverPath)
			$distributionProfile->$actionName->serverPath = $genericProviderAction->getRemotePath();
		if(!$distributionProfile->$actionName->username)
			$distributionProfile->$actionName->username = $genericProviderAction->getRemoteUsername();
		if(!$distributionProfile->$actionName->password)
			$distributionProfile->$actionName->password = $genericProviderAction->getRemotePassword();
		if(!$distributionProfile->$actionName->ftpPassiveMode)
			$distributionProfile->$actionName->ftpPassiveMode = $genericProviderAction->getFtpPassiveMode();
		if(!$distributionProfile->$actionName->httpFieldName)
			$distributionProfile->$actionName->httpFieldName = $genericProviderAction->getHttpFieldName();
		if(!$distributionProfile->$actionName->httpFileName)
			$distributionProfile->$actionName->httpFileName = $genericProviderAction->getHttpFileName();
	
		$entry = entryPeer::retrieveByPKNoFilter($distributionJobData->entryDistribution->entryId);
		if(!$entry)
		{
			BorhanLog::err("Entry [" . $distributionJobData->entryDistribution->entryId . "] not found");
			return;
		}
			
		$mrss = kMrssManager::getEntryMrss($entry);
		if(!$mrss)
		{
			BorhanLog::err("MRSS not returned for entry [" . $entry->getId() . "]");
			return;
		}
			
		$xml = new KDOMDocument();
		if(!$xml->loadXML($mrss))
		{
			BorhanLog::err("MRSS not is not valid XML:\n$mrss\n");
			return;
		}
		
		$key = $genericProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_MRSS_TRANSFORMER);
		if(kFileSyncUtils::fileSync_exists($key))
		{
			$xslPath = kFileSyncUtils::getLocalFilePathForKey($key);
			if($xslPath)
			{
				$xsl = new KDOMDocument();
				$xsl->load($xslPath);
			
				// set variables in the xsl
				$varNodes = $xsl->getElementsByTagName('variable');
				foreach($varNodes as $varNode)
				{
					$nameAttr = $varNode->attributes->getNamedItem('name');
					if(!$nameAttr)
						continue;
						
					$name = $nameAttr->value;
					if($name && $distributionJobData->$name)
					{
						$varNode->textContent = $distributionJobData->$name;
						$varNode->appendChild($xsl->createTextNode($distributionJobData->$name));
					}
				}
				
				$proc = new XSLTProcessor;
				$proc->registerPHPFunctions(kXml::getXslEnabledPhpFunctions());
				$proc->importStyleSheet($xsl);
				
				$xml = $proc->transformToDoc($xml);
				if(!$xml)
				{
					BorhanLog::err("Transform returned false");
					return;
				}
			}
		}
	
		$key = $genericProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_MRSS_VALIDATOR);
		if(kFileSyncUtils::fileSync_exists($key))
		{
			$xsdPath = kFileSyncUtils::getLocalFilePathForKey($key);
			if($xsdPath && !$xml->schemaValidate($xsdPath))	
			{
				BorhanLog::err("Inavlid XML:\n" . $xml->saveXML());
				BorhanLog::err("Schema [$xsdPath]:\n" . file_get_contents($xsdPath));	
				return;
			}
		}
		
		$this->xml = $xml->saveXML();
		
		$key = $genericProviderAction->getSyncKey(GenericDistributionProviderAction::FILE_SYNC_DISTRIBUTION_PROVIDER_ACTION_RESULTS_TRANSFORMER);
		if(kFileSyncUtils::fileSync_exists($key))
			$this->resultParseData = kFileSyncUtils::file_get_contents($key, true, false);
			
		$this->resultParserType = $genericProviderAction->getResultsParser();
	}
		
	private static $map_between_objects = array
	(
		"xml" ,
		"resultParseData" ,
		"resultParserType" ,
	);

	public function getMapBetweenObjects ( )
	{
		return array_merge ( parent::getMapBetweenObjects() , self::$map_between_objects );
	}
}
