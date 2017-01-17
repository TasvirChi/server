<?php
/**
 * Plugins can handle bulk upload xml additional data by implementing this interface
 * @package plugins.bulkUploadXml
 * @subpackage lib
 */
interface IBorhanBulkUploadXmlHandler
{
	/**
	 * Configures the handler by passing all the required configuration 
	 * @param BulkUploadEngineXml $xmlBulkUploadEngine  
	 */
	public function configureBulkUploadXmlHandler(BulkUploadEngineXml $xmlBulkUploadEngine);
	
	/**
	 * Handles plugin data for new created object 
	 * @param BorhanObjectBase $object
	 * @param SimpleXMLElement $item
	 * @throws BorhanBulkUploadXmlException  
	 */
	public function handleItemAdded(BorhanObjectBase $object, SimpleXMLElement $item);

	/**
	 * 
	 * Handles plugin data for updated object  
	 * @param BorhanObjectBase $object
	 * @param SimpleXMLElement $item
	 * @throws BorhanBulkUploadXmlException  
	 */
	public function handleItemUpdated(BorhanObjectBase $object, SimpleXMLElement $item);

	/**
	 * 
	 * Handles plugin data for deleted object  
	 * @param BorhanObjectBase $object
	 * @param SimpleXMLElement $item
	 * @throws BorhanBulkUploadXmlException  
	 */
	public function handleItemDeleted(BorhanObjectBase $object, SimpleXMLElement $item);
	
	/**
	 * Return the container name to be handeled
	 */
	public function getContainerName();
}