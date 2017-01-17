<?php
/**
 * Enable widevine flavor ingestion from XML bulk upload
 * @package plugins.widevine
 */
class WidevineBulkUploadXmlPlugin extends BorhanPlugin implements IBorhanPending, IBorhanSchemaContributor, IBorhanBulkUploadXmlHandler
{
	const PLUGIN_NAME = 'widevineBulkUploadXml';
	const BULK_UPLOAD_XML_PLUGIN_NAME = 'bulkUploadXml';
	
	/**
	 * @var BulkUploadEngineXml
	 */
	private $xmlBulkUploadEngine = null;
	
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
		$bulkUploadXmlDependency = new BorhanDependency(self::BULK_UPLOAD_XML_PLUGIN_NAME);
		$widevineDependency = new BorhanDependency(WidevinePlugin::getPluginName());
		
		return array($bulkUploadXmlDependency, $widevineDependency);
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanSchemaContributor::contributeToSchema()
	 */
	public static function contributeToSchema($type)
	{
		$coreType = kPluginableEnumsManager::apiToCore('SchemaType', $type);
		if(
			$coreType != BulkUploadXmlPlugin::getSchemaTypeCoreValue(XmlSchemaType::BULK_UPLOAD_XML)
			&&
			$coreType != BulkUploadXmlPlugin::getSchemaTypeCoreValue(XmlSchemaType::BULK_UPLOAD_RESULT_XML)
		)
			return null;
	
		$xsd = '
		
	<!-- ' . self::getPluginName() . ' -->
	
	<xs:complexType name="T_widevineAssets">
		<xs:sequence>
			<xs:element name="action" minOccurs="0" maxOccurs="1">
				<xs:annotation>
					<xs:documentation>
						The action to apply:<br/>
						Update - Update existing asset<br/>
					</xs:documentation>
				</xs:annotation>
				<xs:simpleType>
					<xs:restriction base="xs:string">
						<xs:enumeration value="update" />
					</xs:restriction>
				</xs:simpleType>
			</xs:element>
			<xs:element ref="widevineAsset" maxOccurs="unbounded" minOccurs="0">
				<xs:annotation>
					<xs:documentation>All widevine elements</xs:documentation>
				</xs:annotation>
			</xs:element>
		</xs:sequence>
	</xs:complexType>
	
	<xs:complexType name="T_widevineAsset">
		<xs:sequence>
			<xs:element name="widevineAssetId" minOccurs="1" maxOccurs="1" type="xs:long">
				<xs:annotation>
					<xs:documentation>widevine asset id</xs:documentation>
				</xs:annotation>
			</xs:element>
			<xs:element name="flavorParamsId" minOccurs="1" maxOccurs="1" type="xs:long">
				<xs:annotation>
					<xs:documentation>widevine asset flavor params Id</xs:documentation>
				</xs:annotation>
			</xs:element>	
			<xs:element maxOccurs="1" minOccurs="0" name="widevineDistributionStartDate" type="xs:dateTime">
				<xs:annotation>
					<xs:documentation>
						The license distribution window start date.<br/>
					</xs:documentation>
				</xs:annotation>
			</xs:element>
			<xs:element maxOccurs="1" minOccurs="0" name="widevineDistributionEndDate" type="xs:dateTime">
				<xs:annotation>
					<xs:documentation>
						The license distribution window end date.<br/>
					</xs:documentation>
				</xs:annotation>
			</xs:element>	
		</xs:sequence>		
		<xs:attribute name="flavorAssetId" type="xs:string" use="optional">
			<xs:annotation>
				<xs:documentation>The asset id to be updated with this resource used only for update</xs:documentation>
			</xs:annotation>
		</xs:attribute>
						
	</xs:complexType>
	
	<xs:element name="widevineAsset-extension" />
		<xs:element name="widevineAssets" type="T_widevineAssets" substitutionGroup="item-extension">
		<xs:annotation>
			<xs:documentation>All widevine elements</xs:documentation>
			<xs:appinfo>
				<example>
					<widevineAssets>
						<action>update</action>
						<widevineAsset>...</widevineAsset>
						<widevineAsset>...</widevineAsset>
						<widevineAsset>...</widevineAsset>
					</widevineAssets>
				</example>
			</xs:appinfo>
		</xs:annotation>
	</xs:element>
	<xs:element name="widevineAsset" type="T_widevineAsset" substitutionGroup="item-extension">
		<xs:annotation>
			<xs:documentation>Widevine asset element</xs:documentation>
			<xs:appinfo>
				<example>
					<widevineAsset flavorAssetId="{asset id}">
						<widevineAssetId>123456</widevineAssetId>
						<flavorParamsId>61</flavorParamsId>
						<widevineDistributionStartDate>2011-05-05T00:00:00</widevineDistributionStartDate>
						<widevineDistributionEndDate>2014-05-19T00:00:00</widevineDistributionEndDate>
					</widevineAsset>
				</example>
			</xs:appinfo>
		</xs:annotation>
	</xs:element>
		';
		
		return $xsd;
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
		
		if(!isset($item->widevineAssets))
			return;
		
		if(empty($item->widevineAssets->widevineAsset))
			return;
			
		$this->handleWidevineAssets($object->id, $item);		
	}

	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemUpdated()
	 */
	public function handleItemUpdated(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		if(!($object instanceof BorhanBaseEntry))
			return;
		
		if(!$item->widevineAssets)
			return;
			
		if(empty($item->widevineAssets->widevineAsset))
			return;
		
		$action = KBulkUploadEngine::$actionsMap[BorhanBulkUploadAction::UPDATE];
		
		if(isset($item->widevineAssets->action))
			$action = strtolower($item->widevineAssets->action);
			
		switch ($action)
		{
			case KBulkUploadEngine::$actionsMap[BorhanBulkUploadAction::UPDATE]:
				$this->handleWidevineAssets($object->id, $item);
				break;
			default:
				throw new BorhanBatchException("widevineAssets->action: $action is not supported", BorhanBatchJobAppErrors::BULK_ACTION_NOT_SUPPORTED);
		}		
	}

	/* (non-PHPdoc)
	 * @see IBorhanBulkUploadXmlHandler::handleItemDeleted()
	 */
	public function handleItemDeleted(BorhanObjectBase $object, SimpleXMLElement $item)
	{
		// No handling required
	}
	
	/* (non-PHPdoc)
	 * @see IBorhanConfigurator::getContainerName()
	*/
	public function getContainerName()
	{
		return 'widevineAssets';
	}
	
	private function handleWidevineAssets($entryId, SimpleXMLElement $item)
	{	
		BorhanLog::debug("Handling widevine assets for entry: ".$entryId);
							
		$pluginsErrorResults = array();
		
		KBatchBase::impersonate($this->xmlBulkUploadEngine->getCurrentPartnerId());
		
		foreach($item->widevineAssets->widevineAsset as $widevineAsset)
		{
			try 
			{
				$this->handleWidevineAsset($entryId, $widevineAsset);
			}
			catch (Exception $e)
			{
				BorhanLog::err($this->getContainerName() . ' failed: ' . $e->getMessage());
				$pluginsErrorResults[] = $e->getMessage();
			}
		}	

		KBatchBase::unimpersonate();
						
		if(count($pluginsErrorResults))
			throw new Exception(implode(', ', $pluginsErrorResults));					
	}
	
	/**
	 * Update widevine asset properties
	 * If flavorAssetId is not set find asset by entryID and flavorParamsId
	 * 
	 * @param string $entryId
	 * @param SimpleXMLElement $widevineAssetElm
	 */
	private function handleWidevineAsset($entryId, SimpleXMLElement $widevineAssetElm)
	{		
		$widevineAsset = new BorhanWidevineFlavorAsset();
		$widevineAsset->widevineAssetId = $widevineAssetElm->widevineAssetId;
		
		if($widevineAssetElm->widevineDistributionStartDate)
			$widevineAsset->widevineDistributionStartDate = KBulkUploadEngine::parseFormatedDate((string)$widevineAssetElm->widevineDistributionStartDate);
		if($widevineAssetElm->widevineDistributionEndDate)
			$widevineAsset->widevineDistributionEndDate = KBulkUploadEngine::parseFormatedDate((string)$widevineAssetElm->widevineDistributionEndDate);
					 
		$flavorAssetId = null;
		if(isset($widevineAssetElm['flavorAssetId']))
			$flavorAssetId = $widevineAssetElm['flavorAssetId'];
			
		if(!$flavorAssetId)
		{
			$flavorParamsId = $widevineAssetElm->flavorParamsId;
			$filter = new BorhanAssetFilter();
			$filter->entryIdEqual = $entryId;
			$flavorAssetList = KBatchBase::$kClient->flavorAsset->listAction($filter);	
			if($flavorAssetList->objects)
			{
				foreach ($flavorAssetList->objects as $flavorAsset) 
				{
					if($flavorAsset->flavorParamsId == $flavorParamsId)
						$flavorAssetId = $flavorAsset->id;
				}
			}			
		}

		if($flavorAssetId)
		{
			KBatchBase::$kClient->flavorAsset->update($flavorAssetId, $widevineAsset);
		}
	}
}
