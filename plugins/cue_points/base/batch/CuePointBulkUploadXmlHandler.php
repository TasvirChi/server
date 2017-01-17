<?php
/**
 * Handles cue point ingestion from XML bulk upload
 * @package plugins.cuePoint
 * @subpackage batch
 */
abstract class CuePointBulkUploadXmlHandler implements IBorhanBulkUploadXmlHandler
{
	/**
	 * @var BulkUploadEngineXml
	 */
	protected $xmlBulkUploadEngine = null;
	
	/**
	 * @var BorhanCuePointClientPlugin
	 */
	protected $cuePointPlugin = null;
	
	/**
	 * @var int
	 */
	protected $entryId = null;
	
	/**
	 * @var array ingested cue points
	 */
	protected $ingested = array();
	
	/**
	 * @var array each item operation
	 */
	protected $operations = array();
	
	/**
	 * @var array of existing Cue Points with systemName
	 */
	protected static $existingCuePointsBySystemName = null;
	
	protected function __construct()
	{
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::configureBulkUploadXmlHandler()
	 */
	public function configureBulkUploadXmlHandler(BulkUploadEngineXml $xmlBulkUploadEngine)
	{
		$this->xmlBulkUploadEngine = $xmlBulkUploadEngine;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemAdded()
	 */
	public function handleItemAdded(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		if(!($object instanceof BorhanBaseEntry))
			return;
			
		if(empty($item->scenes))
			return;
			
		$this->entryId = $object->id;
		$this->cuePointPlugin = BorhanCuePointClientPlugin::get(KBatchBase::$kClient);
		
		KBatchBase::impersonate($this->xmlBulkUploadEngine->getCurrentPartnerId());
		KBatchBase::$kClient->startMultiRequest();
	
		$items = array();
		foreach($item->scenes->children() as $scene)
			if($this->addCuePoint($scene))
				$items[] = $scene;
			
		$results = KBatchBase::$kClient->doMultiRequest();
		KBatchBase::unimpersonate();
		
		if(is_array($results) && is_array($items))
			$this->handleResults($results, $items);
	}

	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemUpdated()
	 */
	public function handleItemUpdated(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		if(!($object instanceof BorhanBaseEntry))
			return;
			
		if(empty($item->scenes))
			return;

		$action = KBulkUploadEngine::$actionsMap[BorhanBulkUploadAction::UPDATE];
		if(isset($item->scenes->action))
			$action = strtolower($item->scenes->action);
			
		switch ($action)
		{
			case KBulkUploadEngine::$actionsMap[BorhanBulkUploadAction::UPDATE]:
				break;
			default:
				throw new BorhanBatchException("scenes->action: $action is not supported", BorhanBatchJobAppErrors::BULK_ACTION_NOT_SUPPORTED);
		}
			
		$this->entryId = $object->id;
		$this->cuePointPlugin = BorhanCuePointClientPlugin::get(KBatchBase::$kClient);
		
		KBatchBase::impersonate($this->xmlBulkUploadEngine->getCurrentPartnerId());
		
		$this->getExistingCuePointsBySystemName($this->entryId);
		KBatchBase::$kClient->startMultiRequest();
		
		$items = array();
		foreach($item->scenes->children() as $scene)
		{
			if($this->updateCuePoint($scene))
				$items[] = $scene;
		}
			
		$results = KBatchBase::$kClient->doMultiRequest();
		KBatchBase::unimpersonate();

		if(is_array($results) && is_array($items))
			$this->handleResults($results, $items);
	}

	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemDeleted()
	 */
	public function handleItemDeleted(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		// No handling required
	}

	/**
	 * @param string $entryId
	 * @return array of cuepoint that have systemName
	 */
	protected function getExistingCuePointsBySystemName($entryId)
	{
		if (is_array(self::$existingCuePointsBySystemName))
			return;
		
		$filter = new BorhanCuePointFilter();
		$filter->entryIdEqual = $entryId;
		
		$pager = new BorhanFilterPager();
		$pager->pageSize = 500;
		
		$cuePoints = $this->cuePointPlugin->cuePoint->listAction($filter, $pager);
		self::$existingCuePointsBySystemName = array();
		
		if (!isset($cuePoints->objects))
			return;

		foreach ($cuePoints->objects as $cuePoint)
		{
			if($cuePoint->systemName != '')
				self::$existingCuePointsBySystemName[$cuePoint->systemName] = $cuePoint->id;
		}
	}
	
	
	protected function handleResults(array $results, array $items)
	{
		if(count($results) != count($this->operations) || count($this->operations) != count($items))
		{
			BorhanLog::err("results count [" . count($results) . "] operations count [" . count($this->operations) . "] items count [" . count($items) . "]");
			return;
		}
			
		$pluginsInstances = BorhanPluginManager::getPluginInstances('IBorhanBulkUploadXmlHandler');
		
		foreach($results as $index => $cuePoint)
		{
			if(is_array($cuePoint) && isset($cuePoint['code']))
				throw new Exception($cuePoint['message']);
			
			foreach($pluginsInstances as $pluginsInstance)
			{
				/* @var $pluginsInstance IBorhanBulkUploadXmlHandler */
				
				$pluginsInstance->configureBulkUploadXmlHandler($this->xmlBulkUploadEngine);
				
				if($this->operations[$index] == BorhanBulkUploadAction::ADD)
					$pluginsInstance->handleItemAdded($cuePoint, $items[$index]);
				elseif($this->operations[$index] == BorhanBulkUploadAction::UPDATE)
					$pluginsInstance->handleItemUpdated($cuePoint, $items[$index]);
				elseif($this->operations[$index] == BorhanBulkUploadAction::DELETE)
					$pluginsInstance->handleItemDeleted($cuePoint, $items[$index]);
			}
		}
	}

	/**
	 * @return BorhanCuePoint
	 */
	abstract protected function getNewInstance();

	/**
	 * @param SimpleXMLElement $scene
	 * @return BorhanCuePoint
	 */
	protected function parseCuePoint(SimpleXMLElement $scene)
	{
		$cuePoint = $this->getNewInstance();
		
		if(isset($scene['systemName']) && $scene['systemName'])
			$cuePoint->systemName = $scene['systemName'] . '';
			
		$cuePoint->startTime = kXml::timeToInteger($scene->sceneStartTime);
	
		$tags = array();
		foreach ($scene->tags->children() as $tag)
		{
			$value = "$tag";
			if($value)
				$tags[] = $value;
		}
		$cuePoint->tags = implode(',', $tags);
		
		return $cuePoint;
	}
	
	/**
	 * @param SimpleXMLElement $scene
	 */
	protected function addCuePoint(SimpleXMLElement $scene)
	{
		$cuePoint = $this->parseCuePoint($scene);
		if(!$cuePoint)
			return false;
			
		$cuePoint->entryId = $this->entryId;
		$ingestedCuePoint = $this->cuePointPlugin->cuePoint->add($cuePoint);
		$this->operations[] = BorhanBulkUploadAction::ADD;
		if($cuePoint->systemName)
			$this->ingested[$cuePoint->systemName] = $ingestedCuePoint;
			
		return true;
	}

	/**
	 * @param SimpleXMLElement $scene
	 */
	protected function updateCuePoint(SimpleXMLElement $scene)
	{
		$cuePoint = $this->parseCuePoint($scene);
		if(!$cuePoint)
			return false;

		if(isset($scene['sceneId']) && $scene['sceneId'])
		{
			$cuePointId = $scene['sceneId'];
			$ingestedCuePoint = $this->cuePointPlugin->cuePoint->update($cuePointId, $cuePoint);
			$this->operations[] = BorhanBulkUploadAction::UPDATE;
		}
		elseif(isset($cuePoint->systemName) && isset(self::$existingCuePointsBySystemName[$cuePoint->systemName]))
		{
			$cuePoint = $this->removeNonUpdatbleFields($cuePoint);
			$cuePointId = self::$existingCuePointsBySystemName[$cuePoint->systemName];
			$ingestedCuePoint = $this->cuePointPlugin->cuePoint->update($cuePointId, $cuePoint);
			$this->operations[] = BorhanBulkUploadAction::UPDATE;
		}
		else
		{
			$cuePoint->entryId = $this->entryId;
			$ingestedCuePoint = $this->cuePointPlugin->cuePoint->add($cuePoint);
			$this->operations[] = BorhanBulkUploadAction::ADD;
		}
		if($cuePoint->systemName)
			$this->ingested[$cuePoint->systemName] = $ingestedCuePoint;
			
		return true;
	}
	
	/**
	 * @param string $cuePointSystemName
	 * @return string
	 */
	protected function getCuePointId($systemName)
	{
		if(isset($this->ingested[$systemName]))
		{
			$id = $this->ingested[$systemName]->id;
			return "$id";
		}
		return null;
	
//		Won't work in the middle of multi request
//		
//		$filter = new BorhanAnnotationFilter();
//		$filter->entryIdEqual = $this->entryId;
//		$filter->systemNameEqual = $systemName;
//		
//		$pager = new BorhanFilterPager();
//		$pager->pageSize = 1;
//		
//		try
//		{
//			$cuePointListResponce = $this->cuePointPlugin->cuePoint->listAction($filter, $pager);
//		}
//		catch(Exception $e)
//		{
//			return null;
//		}
//		
//		if($cuePointListResponce->totalCount && $cuePointListResponce->objects[0] instanceof BorhanAnnotation)
//			return $cuePointListResponce->objects[0]->id;
//			
//		return null;
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanConfigurator::getContainerName()
	*/
	public function getContainerName()
	{
		return 'scenes';
	}
	
	/**
	 * Removes all non updatble fields from the cuepoint
	 * @param BorhanCuePoint $entry
	 */
	protected function removeNonUpdatbleFields(BorhanCuePoint $cuePoint)
	{
		return $cuePoint;
	}
}
