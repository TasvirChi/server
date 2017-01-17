package lib.Borhan.notification.handlers;

import lib.Borhan.notification.BaseNotificationHandler;
import lib.Borhan.notification.NotificationHandlerException;
import lib.Borhan.output.Console;

import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import com.borhan.client.BorhanApiException;
import com.borhan.client.enums.BorhanEventNotificationEventObjectType;
import com.borhan.client.enums.BorhanEventNotificationEventType;
import com.borhan.client.enums.BorhanMetadataObjectType;
import com.borhan.client.types.BorhanHttpNotification;
import com.borhan.client.types.BorhanMediaEntry;
import com.borhan.client.types.BorhanMetadata;
import com.borhan.client.types.BorhanMetadataFilter;
import com.borhan.client.types.BorhanMetadataListResponse;
import com.borhan.client.utils.XmlUtils;

public class SyncSampleHandler extends BaseNotificationHandler {

	// Handler constants
	protected static final int METADATA_PROFILE_ID = METADATA_PROFILE_ID;
	protected static final String APPROVAL_FIELD_NAME = "ApprovalStatus";
	protected static final String SYNC_FIELD_NAME = "SyncStatus";
	
	// Constants
	protected static final String SYNC_NEEDED = "Sync Needed";
	protected static final String SYNC_DONE = "Sync Done";
	
	public SyncSampleHandler(Console console) {
		super(console);
	}
	
	public boolean shouldHandle(BorhanHttpNotification httpNotification) {
		// Only handles if the event type is custom-metadata field changed.
		if(!((httpNotification.eventType.equals(BorhanEventNotificationEventType.OBJECT_DATA_CHANGED)) &&
				(httpNotification.eventObjectType.equals(BorhanEventNotificationEventObjectType.METADATA)))) 
			return false;
		
		// Only handle metadata of entries
		BorhanMetadata object = (BorhanMetadata)httpNotification.object;
		
		// Test that the metadata profile is the one we test
		if(object.metadataProfileId != METADATA_PROFILE_ID)
			return false;
		
		return (object.metadataObjectType == BorhanMetadataObjectType.ENTRY);
	}
	
	/**
	 * The handling function. 
	 * @param httpNotification The notification that should be handled
	 * @throws BorhanApiException In case something bad happened
	 */
	public void handle(BorhanHttpNotification httpNotification) {
	
		try {
			// Since the custom-metadata is the returned object, there is no need in querying it.
			BorhanMetadata metadata = (BorhanMetadata)httpNotification.object;
			
			// If the custom metadata in within another custom-metadata profile, retrieve it by executing
			// BorhanMetadata extraMetadata = fetchMetadata(metadata.objectId, OTHER METADATA PROFILE ID)
			
			String approvalStatus = getValue(metadata.xml, APPROVAL_FIELD_NAME);
			if(approvalStatus == null)
				return;
			
			// Entry retrieval for basic and common attributes.
			BorhanMediaEntry entry = fetchEntry(metadata.objectId);
			
			if(approvalStatus.equals("Ready For Website")) {
				handleReadyForSite(entry, metadata);
			} else if (approvalStatus.equals("Deleted")) {
				handleDelete(entry, metadata);
			} 
			// TODO - Add other cases here, in this code sample we're only monitoring these values. 
			
		} catch (BorhanApiException e) {
			console.write("Failed while handling notification");
			console.write(e.getMessage());
			throw new NotificationHandlerException("Failed while handling notification" + e.getMessage(), NotificationHandlerException.ERROR_PROCESSING);
		}
	}
	
	/**
     * Fetch an entry using the API
     *
     * @param String, entryId: id of the entry you want to fetch
     * @return 
     * @throws BorhanApiException 
     * @throws Exception 
     *
     */
	protected BorhanMediaEntry fetchEntry(String entryId) throws BorhanApiException{
		 return getClient().getMediaService().get(entryId);
	}
	
	/**
	 * This function fetches the metadata of a given type for a given entry
	 * @param entryId The entry for which we fetch the metadata
	 * @param metadataProfileId The metadata profile id
	 * @return The matching metadata
	 * @throws BorhanApiException
	 */
	protected BorhanMetadata fetchMetadata(String entryId, int metadataProfileId)
			throws BorhanApiException {
		BorhanMetadataFilter filter = new BorhanMetadataFilter();
		filter.objectIdEqual = entryId;
		filter.metadataProfileIdEqual = metadataProfileId;
		BorhanMetadataListResponse metadatas = getClient().getMetadataService().list(filter);
		if(metadatas.totalCount == 0) {
			console.write("Failed to retrieve metadata for entry " + entryId + " and profile " + metadataProfileId);
			return null;
		}
		
		BorhanMetadata metadata = metadatas.objects.get(0);
		console.write("Successfully retrieved metadata. ID " + metadata.id);
		return metadata;
	}
	
	/**
	 * This function handles the case in which an entry was marked as deleted
	 * @param entry The entry.
	 * @param syncMetadata The SyncMetadataObject
	 * @throws BorhanApiException
	 */
	protected void handleDelete(BorhanMediaEntry entry, BorhanMetadata syncMetadata) throws BorhanApiException {
		if(!SYNC_DONE.equals(getValue(syncMetadata.xml, SYNC_FIELD_NAME))) {
			console.write("Entry is not marked as synched with the CMS, do nothing");
			return;
		}
		
		console.write("The entry " + entry.name + " has been marked as deleted on Borhan. Sync this delete with customer's website CMS");
		deleteReference(entry, syncMetadata);
		// Mark the entry again as sync needed as we removed it from the CMS
		updateSyncStatus(syncMetadata, SYNC_NEEDED);
	}

	/**
	 * This function handles the case in which an entry is ready for site
	 * @param entry The entry.
	 * @param syncMetadata The SyncMetadataObject
	 * @throws BorhanApiException
	 */
	protected void handleReadyForSite(BorhanMediaEntry entry, BorhanMetadata syncMetadata) throws BorhanApiException {
		if(!SYNC_NEEDED.equals(getValue(syncMetadata.xml, SYNC_FIELD_NAME))) {
			console.write("No sync is needed");
			return;
		}
		
		console.write("The entry " + entry.name + " has been approved to be synced with customer's website CMS");
		syncReference(entry, syncMetadata);
		updateSyncStatus(syncMetadata, SYNC_DONE);
	}

	/**
	 * This function updates the sync field value
	 * @param object The metadata object we'd like to update
	 * @param newValue The new value for the sync field
	 * @throws BorhanApiException
	 */
	protected void updateSyncStatus(BorhanMetadata object, String newValue) throws BorhanApiException {
		String xml = object.xml;
		String oldValue = getValue(xml, SYNC_FIELD_NAME);
		String oldStr = "<" + SYNC_FIELD_NAME +">" + oldValue + "</" + SYNC_FIELD_NAME +">";
		String newStr = "<" + SYNC_FIELD_NAME +">" + newValue + "</" + SYNC_FIELD_NAME +">";
		xml = xml.replaceAll(oldStr, newStr);
		
		getClient().getMetadataService().update(object.id, xml);
	}

	/**
	 * This function parses an XML and returns a specific field value from it
	 * @param xml The parsed XML
	 * @param fieldName The field name we want to retrieve
	 * @return The field avtual value
	 * @throws BorhanApiException
	 */
	protected static String getValue(String xml, String fieldName) throws BorhanApiException {
		Element xmlElement = XmlUtils.parseXml(xml);
		NodeList childNodes = xmlElement.getChildNodes();
		for (int i = 0; i < childNodes.getLength(); i++) {
			Node aNode = childNodes.item(i);
			String nodeName = aNode.getNodeName();
			if (nodeName.equals(fieldName))
				return aNode.getTextContent();
		}
		return null;
	}
	
	// Customer specific functions
	
	/**
	 * This function should delete an object reference from the external system 
	 * @param entry The entry that has to be deleted
	 * @param object The metadata object describing the object
	 */
	protected void deleteReference(BorhanMediaEntry entry, BorhanMetadata object) {
		console.write("Delete this entry's reference from your external system");
		// TODO - Add your code here
	}
	
	/**
	 * This function should sync an object reference to the external system 
	 * @param entry The entry that has to be synced
	 * @param object The metadata object describing the object
	 */
	protected void syncReference(BorhanMediaEntry entry, BorhanMetadata object) {
		console.write("Sync the entry to your external system");
		// TODO - Add your code here
	}
}
