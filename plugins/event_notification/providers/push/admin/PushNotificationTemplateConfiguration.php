<?php
/**
 * @package plugins.pushNotification
* @subpackage admin
*/
class Form_PushNotificationTemplateConfiguration extends Form_EventNotificationTemplateConfiguration
{
    protected function addTypeElements(Borhan_Client_EventNotification_Type_EventNotificationTemplate $eventNotificationTemplate)
    {
        $element = new Infra_Form_Html('http_title', array(
            'content' => '<b>Notification Handler Service  Details</b>',
        ));
        $this->addElements(array($element));
        
        $this->addElement('select', 'api_object_type', array(
            'label'			=> 'Object Type (BorhanObject):',
 			'default'       => $eventNotificationTemplate->apiObjectType,
            'filters'		=> array('StringTrim'),
            'required'		=> true,            
            'multiOptions' 	=> array(
                'BorhanBaseEntry' => 'Base Entry',
                'BorhanDataEntry' => 'Data Entry',
                'BorhanDocumentEntry' => 'Document Entry',
                'BorhanMediaEntry' => 'Media Entry',
                'BorhanExternalMediaEntry' => 'External Media Entry',
                'BorhanLiveStreamEntry' => 'Live Stream Entry',
                'BorhanPlaylist' => 'Playlist',
                'BorhanCategory' => 'Category',
                'BorhanUser' => 'User',
                'BorhanCuePoint' => 'CuePoint',
                'BorhanAdCuePoint' => 'Ad Cue-Point',
                'BorhanAnnotation' => 'Annotation',
                'BorhanCodeCuePoint' => 'Code Cue-Point',
                'BorhanDistributionProfile' => 'Distribution Profile',
                'BorhanEntryDistribution' => 'Entry Distribution',
                'BorhanMetadata' => 'Metadata',
                'BorhanAsset' => 'Asset',
                'BorhanFlavorAsset' => 'Flavor Asset',
                'BorhanThumbAsset' => 'Thumbnail Asset',
                'BorhanAccessControlProfile' => 'Access Control',
                'BorhanBatchJob' => 'BatchJob',
                'BorhanBulkUploadResultEntry' => 'Bulk-Upload Entry Result',
                'BorhanBulkUploadResultCategory' => 'Bulk-Upload Category Result',
                'BorhanBulkUploadResultUser' => 'Bulk-Upload User Result',
                'BorhanBulkUploadResultCategoryUser' => 'Bulk-Upload Category - User Result',
                'BorhanCategoryUser' => 'Category - User',
                'BorhanConversionProfile' => 'Conversion Profile',
                'BorhanFlavorParams' => 'Flavor Params',
                'BorhanConversionProfileAssetParams' => 'Asset Params - Conversion Profile',
                'BorhanFlavorParamsOutput' => 'Flavor Params Output',
                'BorhanGenericsynDicationFeed' => 'Genericsyn Dication Feed',
                'BorhanPartner' => 'Partner',
                'BorhanPermission' => 'Permission',
                'BorhanPermissionItem' => 'Permission Item',
                'BorhanScheduler' => 'Scheduler',
                'BorhanSchedulerConfig' => 'Scheduler Config',
                'BorhanSchedulerStatus' => 'Scheduler Status',
                'BorhanSchedulerWorker' => 'Scheduler Worker',
                'BorhanStorageProfile' => 'Storage Profile',
                'BorhanThumbParams' => 'Thumbnail Params',
                'BorhanThumbParamsOutput' => 'Thumbnail Params Output',
                'BorhanUploadToken' => 'Upload Token',
                'BorhanUserLoginData' => 'User Login Data',
                'BorhanUserRole' => 'User Role',
                'BorhanWidget' => 'Widget',
                'BorhanCategoryEntry' => 'Category - Entry',
                'BorhanLiveStreamScheduleEvent' => 'Schedule Live-Stream Event',
                'BorhanRecordScheduleEvent' => 'Schedule Recorded Event',
                'BorhanLocationScheduleResource' => 'Schedule Location Resource',
                'BorhanLiveEntryScheduleResource' => 'Schedule Live-Entry Resource',
                'BorhanCameraScheduleResource' => 'Schedule Camera Resource',
                'BorhanScheduleEventResource' => 'Schedule Event-Resource',
            ),
        ));
    
        $this->addElement('select', 'object_format', array(
            'label'			=> 'Format:',
            'filters'		=> array('StringTrim'),
            'required'		=> true,
            'multiOptions' 	=> array(
                Borhan_Client_Enum_ResponseType::RESPONSE_TYPE_JSON => 'JSON',
                Borhan_Client_Enum_ResponseType::RESPONSE_TYPE_XML => 'XML',
                Borhan_Client_Enum_ResponseType::RESPONSE_TYPE_PHP => 'PHP',
            ),
        ));

        $responseProfile = new Borhan_Form_Element_ObjectSelect('response_profile_id', array(
        	'label' => 'Response Profile:',
        	'nameAttribute' => 'name',
        	'service' => 'responseProfile',
        	'pageSize' => 500,
        	'impersonate' => $eventNotificationTemplate->partnerId,
        ));
        $this->addElements(array($responseProfile));
    }    
}