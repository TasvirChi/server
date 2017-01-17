
var kObjects = {
	coreObjectType: {
		label: 			'Event',
		subSelections:	{
			baseEntry:						{label: 'Base Entry', coreType: 'entry', apiType: 'BorhanBaseEntry'},
			dataEntry:						{label: 'Data Entry', coreType: 'entry', apiType: 'BorhanDataEntry'},
			documentEntry:					{label: 'Document Entry', coreType: 'entry', apiType: 'BorhanDocumentEntry'},
			mediaEntry:						{label: 'Media Entry', coreType: 'entry', apiType: 'BorhanMediaEntry'},
			externalMediaEntry:				{label: 'External Media Entry', coreType: 'entry', apiType: 'BorhanExternalMediaEntry'},
			liveStreamEntry:				{label: 'Live Stream Entry', coreType: 'entry', apiType: 'BorhanLiveStreamEntry'},
			playlist:						{label: 'Playlist', coreType: 'entry', apiType: 'BorhanPlaylist'},
			category:						{label:	'Category', apiType: 'BorhanCategory'},
			kuser:							{label:	'User', apiType: 'BorhanUser'},
	        CuePoint:						{label:	'CuePoint', apiType: 'BorhanCuePoint'},
	        AdCuePoint:						{label:	'Ad Cue-Point', apiType: 'BorhanAdCuePoint'},
	        Annotation:						{label:	'Annotation', apiType: 'BorhanAnnotation'},
	        CodeCuePoint:					{label:	'Code Cue-Point', apiType: 'BorhanCodeCuePoint'},
	        DistributionProfile:			{label:	'Distribution Profile', apiType: 'BorhanDistributionProfile'},
	        EntryDistribution:				{label:	'Entry Distribution', apiType: 'BorhanEntryDistribution'},
	        Metadata:						{label:	'Metadata', apiType: 'BorhanMetadata'},
	        asset:							{label:	'Asset', apiType: 'BorhanAsset'},
	        attachmentAsset:				{label: 'AttachmentAsset', apiType: 'BorhanAttachmentAsset'},
	        flavorAsset:					{label:	'Flavor Asset', apiType: 'BorhanFlavorAsset'},
	        thumbAsset:						{label:	'Thumbnail Asset', apiType: 'BorhanThumbAsset'},
	        accessControl:					{label:	'Access Control', apiType: 'BorhanAccessControlProfile'},
	        BatchJob:						{label:	'BatchJob', apiType: 'BorhanBatchJob'},
	        BulkUploadResultEntry:			{label:	'Bulk-Upload Entry Result', apiType: 'BorhanBulkUploadResultEntry'},
	        BulkUploadResultCategory:		{label:	'Bulk-Upload Category Result', apiType: 'BorhanBulkUploadResultCategory'},
	        BulkUploadResultKuser:			{label:	'Bulk-Upload User Result', apiType: 'BorhanBulkUploadResultUser'},
	        BulkUploadResultCategoryKuser:	{label:	'Bulk-Upload Category - User Result', apiType: 'BorhanBulkUploadResultCategoryUser'},
	        categoryKuser:					{label:	'Category - User', apiType: 'BorhanCategoryUser'},
	        conversionProfile2:				{label:	'Conversion Profile', apiType: 'BorhanConversionProfile'},
	        flavorParams:					{label:	'Flavor Params', apiType: 'BorhanFlavorParams'},
	        flavorParamsConversionProfile:	{label:	'Asset Params - Conversion Profile', apiType: 'BorhanConversionProfileAssetParams'},
	        flavorParamsOutput:				{label:	'Flavor Params Output', apiType: 'BorhanFlavorParamsOutput'},
	        genericsynDicationFeed:			{label:	'Genericsyn Dication Feed', apiType: 'BorhanGenericsynDicationFeed'},
	        Partner:						{label:	'Partner', apiType: 'BorhanPartner'},
	        Permission:						{label:	'Permission', apiType: 'BorhanPermission'},
	        PermissionItem:					{label:	'Permission Item', apiType: 'BorhanPermissionItem'},
	        Scheduler:						{label:	'Scheduler', apiType: 'BorhanScheduler'},
	        SchedulerConfig:				{label:	'Scheduler Config', apiType: 'BorhanSchedulerConfig'},
	        SchedulerStatus:				{label:	'Scheduler Status', apiType: 'BorhanSchedulerStatus'},
	        SchedulerWorker:				{label:	'Scheduler Worker', apiType: 'BorhanSchedulerWorker'},
	        StorageProfile:					{label:	'Storage Profile', apiType: 'BorhanStorageProfile'},
	        thumbParams:					{label:	'Thumbnail Params', apiType: 'BorhanThumbParams'},
	        thumbParamsOutput:				{label:	'Thumbnail Params Output', apiType: 'BorhanThumbParamsOutput'},
	        UploadToken:					{label:	'Upload Token', apiType: 'BorhanUploadToken'},
	        UserLoginData:					{label:	'User Login Data', apiType: 'BorhanUserLoginData'},
	        UserRole:						{label:	'User Role', apiType: 'BorhanUserRole'},
	        widget:							{label:	'Widget', apiType: 'BorhanWidget'},
	        categoryEntry:					{label:	'Category - Entry', apiType: 'BorhanCategoryEntry'}
		},
		subLabel:		'Select Object Type',
		getData:		function(subCode, variables){
							var coreType = variables.value;
							if(variables.coreType != null)
								coreType = variables.coreType;
								
							var ret = {
								code: '(($scope->getEvent()->getObject() instanceof ' + coreType + ') ? $scope->getEvent()->getObject() : null)',
								coreType: coreType
							};
							
							if(variables.apiType != null)
								ret.apiName = variables.apiType;
								
							return ret;
		}
	}
};
