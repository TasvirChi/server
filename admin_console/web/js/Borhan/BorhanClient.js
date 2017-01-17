function BorhanAccessControlOrderBy()
{
}
BorhanAccessControlOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanAccessControlOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanAudioCodec()
{
}
BorhanAudioCodec.prototype.NONE = "";
BorhanAudioCodec.prototype.MP3 = "mp3";
BorhanAudioCodec.prototype.AAC = "aac";

function BorhanBaseEntryOrderBy()
{
}
BorhanBaseEntryOrderBy.prototype.NAME_ASC = "+name";
BorhanBaseEntryOrderBy.prototype.NAME_DESC = "-name";
BorhanBaseEntryOrderBy.prototype.MODERATION_COUNT_ASC = "+moderationCount";
BorhanBaseEntryOrderBy.prototype.MODERATION_COUNT_DESC = "-moderationCount";
BorhanBaseEntryOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanBaseEntryOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanBaseEntryOrderBy.prototype.RANK_ASC = "+rank";
BorhanBaseEntryOrderBy.prototype.RANK_DESC = "-rank";

function BorhanBaseJobOrderBy()
{
}
BorhanBaseJobOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanBaseJobOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanBaseJobOrderBy.prototype.EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
BorhanBaseJobOrderBy.prototype.EXECUTION_ATTEMPTS_DESC = "-executionAttempts";

function BorhanBaseSyndicationFeedOrderBy()
{
}
BorhanBaseSyndicationFeedOrderBy.prototype.PLAYLIST_ID_ASC = "+playlistId";
BorhanBaseSyndicationFeedOrderBy.prototype.PLAYLIST_ID_DESC = "-playlistId";
BorhanBaseSyndicationFeedOrderBy.prototype.NAME_ASC = "+name";
BorhanBaseSyndicationFeedOrderBy.prototype.NAME_DESC = "-name";
BorhanBaseSyndicationFeedOrderBy.prototype.TYPE_ASC = "+type";
BorhanBaseSyndicationFeedOrderBy.prototype.TYPE_DESC = "-type";
BorhanBaseSyndicationFeedOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanBaseSyndicationFeedOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanBatchJobErrorTypes()
{
}
BorhanBatchJobErrorTypes.prototype.APP = 0;
BorhanBatchJobErrorTypes.prototype.RUNTIME = 1;
BorhanBatchJobErrorTypes.prototype.HTTP = 2;
BorhanBatchJobErrorTypes.prototype.CURL = 3;

function BorhanBatchJobOrderBy()
{
}
BorhanBatchJobOrderBy.prototype.STATUS_ASC = "+status";
BorhanBatchJobOrderBy.prototype.STATUS_DESC = "-status";
BorhanBatchJobOrderBy.prototype.QUEUE_TIME_ASC = "+queueTime";
BorhanBatchJobOrderBy.prototype.QUEUE_TIME_DESC = "-queueTime";
BorhanBatchJobOrderBy.prototype.FINISH_TIME_ASC = "+finishTime";
BorhanBatchJobOrderBy.prototype.FINISH_TIME_DESC = "-finishTime";
BorhanBatchJobOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanBatchJobOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanBatchJobOrderBy.prototype.EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
BorhanBatchJobOrderBy.prototype.EXECUTION_ATTEMPTS_DESC = "-executionAttempts";

function BorhanBatchJobStatus()
{
}
BorhanBatchJobStatus.prototype.PENDING = 0;
BorhanBatchJobStatus.prototype.QUEUED = 1;
BorhanBatchJobStatus.prototype.PROCESSING = 2;
BorhanBatchJobStatus.prototype.PROCESSED = 3;
BorhanBatchJobStatus.prototype.MOVEFILE = 4;
BorhanBatchJobStatus.prototype.FINISHED = 5;
BorhanBatchJobStatus.prototype.FAILED = 6;
BorhanBatchJobStatus.prototype.ABORTED = 7;
BorhanBatchJobStatus.prototype.ALMOST_DONE = 8;
BorhanBatchJobStatus.prototype.RETRY = 9;
BorhanBatchJobStatus.prototype.FATAL = 10;

function BorhanBatchJobType()
{
}
BorhanBatchJobType.prototype.CONVERT = 0;
BorhanBatchJobType.prototype.IMPORT = 1;
BorhanBatchJobType.prototype.DELETE = 2;
BorhanBatchJobType.prototype.FLATTEN = 3;
BorhanBatchJobType.prototype.BULKUPLOAD = 4;
BorhanBatchJobType.prototype.DVDCREATOR = 5;
BorhanBatchJobType.prototype.DOWNLOAD = 6;
BorhanBatchJobType.prototype.OOCONVERT = 7;
BorhanBatchJobType.prototype.CONVERT_PROFILE = 10;
BorhanBatchJobType.prototype.POSTCONVERT = 11;
BorhanBatchJobType.prototype.PULL = 12;
BorhanBatchJobType.prototype.REMOTE_CONVERT = 13;
BorhanBatchJobType.prototype.EXTRACT_MEDIA = 14;
BorhanBatchJobType.prototype.MAIL = 15;
BorhanBatchJobType.prototype.NOTIFICATION = 16;
BorhanBatchJobType.prototype.CLEANUP = 17;
BorhanBatchJobType.prototype.SCHEDULER_HELPER = 18;
BorhanBatchJobType.prototype.BULKDOWNLOAD = 19;
BorhanBatchJobType.prototype.PROJECT = 1000;

function BorhanBulkUploadCsvVersion()
{
}
BorhanBulkUploadCsvVersion.prototype.V1 = "1";
BorhanBulkUploadCsvVersion.prototype.V2 = "2";

function BorhanCategoryOrderBy()
{
}
BorhanCategoryOrderBy.prototype.DEPTH_ASC = "+depth";
BorhanCategoryOrderBy.prototype.DEPTH_DESC = "-depth";
BorhanCategoryOrderBy.prototype.FULL_NAME_ASC = "+fullName";
BorhanCategoryOrderBy.prototype.FULL_NAME_DESC = "-fullName";
BorhanCategoryOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanCategoryOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanCommercialUseType()
{
}
BorhanCommercialUseType.prototype.COMMERCIAL_USE = "commercial_use";
BorhanCommercialUseType.prototype.NON_COMMERCIAL_USE = "non-commercial_use";

function BorhanContainerFormat()
{
}
BorhanContainerFormat.prototype.FLV = "flv";
BorhanContainerFormat.prototype.MP4 = "mp4";
BorhanContainerFormat.prototype.AVI = "avi";
BorhanContainerFormat.prototype.MOV = "mov";
BorhanContainerFormat.prototype._3GP = "3gp";

function BorhanConversionProfileOrderBy()
{
}
BorhanConversionProfileOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanConversionProfileOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanCountryRestrictionType()
{
}
BorhanCountryRestrictionType.prototype.RESTRICT_COUNTRY_LIST = 0;
BorhanCountryRestrictionType.prototype.ALLOW_COUNTRY_LIST = 1;

function BorhanDataEntryOrderBy()
{
}
BorhanDataEntryOrderBy.prototype.NAME_ASC = "+name";
BorhanDataEntryOrderBy.prototype.NAME_DESC = "-name";
BorhanDataEntryOrderBy.prototype.MODERATION_COUNT_ASC = "+moderationCount";
BorhanDataEntryOrderBy.prototype.MODERATION_COUNT_DESC = "-moderationCount";
BorhanDataEntryOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanDataEntryOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanDataEntryOrderBy.prototype.RANK_ASC = "+rank";
BorhanDataEntryOrderBy.prototype.RANK_DESC = "-rank";

function BorhanDirectoryRestrictionType()
{
}
BorhanDirectoryRestrictionType.prototype.DONT_DISPLAY = 0;
BorhanDirectoryRestrictionType.prototype.DISPLAY_WITH_LINK = 1;

function BorhanDocumentEntryOrderBy()
{
}
BorhanDocumentEntryOrderBy.prototype.NAME_ASC = "+name";
BorhanDocumentEntryOrderBy.prototype.NAME_DESC = "-name";
BorhanDocumentEntryOrderBy.prototype.MODERATION_COUNT_ASC = "+moderationCount";
BorhanDocumentEntryOrderBy.prototype.MODERATION_COUNT_DESC = "-moderationCount";
BorhanDocumentEntryOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanDocumentEntryOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanDocumentEntryOrderBy.prototype.RANK_ASC = "+rank";
BorhanDocumentEntryOrderBy.prototype.RANK_DESC = "-rank";

function BorhanDocumentType()
{
}
BorhanDocumentType.prototype.DOCUMENT = 11;
BorhanDocumentType.prototype.SWF = 12;

function BorhanDurationType()
{
}
BorhanDurationType.prototype.NOT_AVAILABLE = "notavailable";
BorhanDurationType.prototype.SHORT = "short";
BorhanDurationType.prototype.MEDIUM = "medium";
BorhanDurationType.prototype.LONG = "long";

function BorhanEditorType()
{
}
BorhanEditorType.prototype.SIMPLE = 1;
BorhanEditorType.prototype.ADVANCED = 2;

function BorhanEntryModerationStatus()
{
}
BorhanEntryModerationStatus.prototype.PENDING_MODERATION = 1;
BorhanEntryModerationStatus.prototype.APPROVED = 2;
BorhanEntryModerationStatus.prototype.REJECTED = 3;
BorhanEntryModerationStatus.prototype.FLAGGED_FOR_REVIEW = 5;
BorhanEntryModerationStatus.prototype.AUTO_APPROVED = 6;

function BorhanEntryStatus()
{
}
BorhanEntryStatus.prototype.ERROR_IMPORTING = -2;
BorhanEntryStatus.prototype.ERROR_CONVERTING = -1;
BorhanEntryStatus.prototype.IMPORT = 0;
BorhanEntryStatus.prototype.PRECONVERT = 1;
BorhanEntryStatus.prototype.READY = 2;
BorhanEntryStatus.prototype.DELETED = 3;
BorhanEntryStatus.prototype.PENDING = 4;
BorhanEntryStatus.prototype.MODERATE = 5;
BorhanEntryStatus.prototype.BLOCKED = 6;

function BorhanEntryType()
{
}
BorhanEntryType.prototype.AUTOMATIC = -1;
BorhanEntryType.prototype.MEDIA_CLIP = 1;
BorhanEntryType.prototype.MIX = 2;
BorhanEntryType.prototype.PLAYLIST = 5;
BorhanEntryType.prototype.DATA = 6;
BorhanEntryType.prototype.DOCUMENT = 10;

function BorhanFlavorAssetStatus()
{
}
BorhanFlavorAssetStatus.prototype.ERROR = -1;
BorhanFlavorAssetStatus.prototype.QUEUED = 0;
BorhanFlavorAssetStatus.prototype.CONVERTING = 1;
BorhanFlavorAssetStatus.prototype.READY = 2;
BorhanFlavorAssetStatus.prototype.DELETED = 3;
BorhanFlavorAssetStatus.prototype.NOT_APPLICABLE = 4;

function BorhanFlavorParamsOrderBy()
{
}

function BorhanFlavorParamsOutputOrderBy()
{
}

function BorhanGender()
{
}
BorhanGender.prototype.UNKNOWN = 0;
BorhanGender.prototype.MALE = 1;
BorhanGender.prototype.FEMALE = 2;

function BorhanGoogleSyndicationFeedAdultValues()
{
}
BorhanGoogleSyndicationFeedAdultValues.prototype.YES = "Yes";
BorhanGoogleSyndicationFeedAdultValues.prototype.NO = "No";

function BorhanGoogleVideoSyndicationFeedOrderBy()
{
}
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.PLAYLIST_ID_ASC = "+playlistId";
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.PLAYLIST_ID_DESC = "-playlistId";
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.NAME_ASC = "+name";
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.NAME_DESC = "-name";
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.TYPE_ASC = "+type";
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.TYPE_DESC = "-type";
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanGoogleVideoSyndicationFeedOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanITunesSyndicationFeedAdultValues()
{
}
BorhanITunesSyndicationFeedAdultValues.prototype.YES = "yes";
BorhanITunesSyndicationFeedAdultValues.prototype.NO = "no";
BorhanITunesSyndicationFeedAdultValues.prototype.CLEAN = "clean";

function BorhanITunesSyndicationFeedCategories()
{
}
BorhanITunesSyndicationFeedCategories.prototype.ARTS = "Arts";
BorhanITunesSyndicationFeedCategories.prototype.ARTS_DESIGN = "Arts/Design";
BorhanITunesSyndicationFeedCategories.prototype.ARTS_FASHION_BEAUTY = "Arts/Fashion &amp; Beauty";
BorhanITunesSyndicationFeedCategories.prototype.ARTS_FOOD = "Arts/Food";
BorhanITunesSyndicationFeedCategories.prototype.ARTS_LITERATURE = "Arts/Literature";
BorhanITunesSyndicationFeedCategories.prototype.ARTS_PERFORMING_ARTS = "Arts/Performing Arts";
BorhanITunesSyndicationFeedCategories.prototype.ARTS_VISUAL_ARTS = "Arts/Visual Arts";
BorhanITunesSyndicationFeedCategories.prototype.BUSINESS = "Business";
BorhanITunesSyndicationFeedCategories.prototype.BUSINESS_BUSINESS_NEWS = "Business/Business News";
BorhanITunesSyndicationFeedCategories.prototype.BUSINESS_CAREERS = "Business/Careers";
BorhanITunesSyndicationFeedCategories.prototype.BUSINESS_INVESTING = "Business/Investing";
BorhanITunesSyndicationFeedCategories.prototype.BUSINESS_MANAGEMENT_MARKETING = "Business/Management &amp; Marketing";
BorhanITunesSyndicationFeedCategories.prototype.BUSINESS_SHOPPING = "Business/Shopping";
BorhanITunesSyndicationFeedCategories.prototype.COMEDY = "Comedy";
BorhanITunesSyndicationFeedCategories.prototype.EDUCATION = "Education";
BorhanITunesSyndicationFeedCategories.prototype.EDUCATION_TECHNOLOGY = "Education/Education Technology";
BorhanITunesSyndicationFeedCategories.prototype.EDUCATION_HIGHER_EDUCATION = "Education/Higher Education";
BorhanITunesSyndicationFeedCategories.prototype.EDUCATION_K_12 = "Education/K-12";
BorhanITunesSyndicationFeedCategories.prototype.EDUCATION_LANGUAGE_COURSES = "Education/Language Courses";
BorhanITunesSyndicationFeedCategories.prototype.EDUCATION_TRAINING = "Education/Training";
BorhanITunesSyndicationFeedCategories.prototype.GAMES_HOBBIES = "Games &amp; Hobbies";
BorhanITunesSyndicationFeedCategories.prototype.GAMES_HOBBIES_AUTOMOTIVE = "Games &amp; Hobbies/Automotive";
BorhanITunesSyndicationFeedCategories.prototype.GAMES_HOBBIES_AVIATION = "Games &amp; Hobbies/Aviation";
BorhanITunesSyndicationFeedCategories.prototype.GAMES_HOBBIES_HOBBIES = "Games &amp; Hobbies/Hobbies";
BorhanITunesSyndicationFeedCategories.prototype.GAMES_HOBBIES_OTHER_GAMES = "Games &amp; Hobbies/Other Games";
BorhanITunesSyndicationFeedCategories.prototype.GAMES_HOBBIES_VIDEO_GAMES = "Games &amp; Hobbies/Video Games";
BorhanITunesSyndicationFeedCategories.prototype.GOVERNMENT_ORGANIZATIONS = "Government &amp; Organizations";
BorhanITunesSyndicationFeedCategories.prototype.GOVERNMENT_ORGANIZATIONS_LOCAL = "Government &amp; Organizations/Local";
BorhanITunesSyndicationFeedCategories.prototype.GOVERNMENT_ORGANIZATIONS_NATIONAL = "Government &amp; Organizations/National";
BorhanITunesSyndicationFeedCategories.prototype.GOVERNMENT_ORGANIZATIONS_NON_PROFIT = "Government &amp; Organizations/Non-Profit";
BorhanITunesSyndicationFeedCategories.prototype.GOVERNMENT_ORGANIZATIONS_REGIONAL = "Government &amp; Organizations/Regional";
BorhanITunesSyndicationFeedCategories.prototype.HEALTH = "Health";
BorhanITunesSyndicationFeedCategories.prototype.HEALTH_ALTERNATIVE_HEALTH = "Health/Alternative Health";
BorhanITunesSyndicationFeedCategories.prototype.HEALTH_FITNESS_NUTRITION = "Health/Fitness &amp; Nutrition";
BorhanITunesSyndicationFeedCategories.prototype.HEALTH_SELF_HELP = "Health/Self-Help";
BorhanITunesSyndicationFeedCategories.prototype.HEALTH_SEXUALITY = "Health/Sexuality";
BorhanITunesSyndicationFeedCategories.prototype.KIDS_FAMILY = "Kids &amp; Family";
BorhanITunesSyndicationFeedCategories.prototype.MUSIC = "Music";
BorhanITunesSyndicationFeedCategories.prototype.NEWS_POLITICS = "News &amp; Politics";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY = "Religion &amp; Spirituality";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY_BUDDHISM = "Religion &amp; Spirituality/Buddhism";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY_CHRISTIANITY = "Religion &amp; Spirituality/Christianity";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY_HINDUISM = "Religion &amp; Spirituality/Hinduism";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY_ISLAM = "Religion &amp; Spirituality/Islam";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY_JUDAISM = "Religion &amp; Spirituality/Judaism";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY_OTHER = "Religion &amp; Spirituality/Other";
BorhanITunesSyndicationFeedCategories.prototype.RELIGION_SPIRITUALITY_SPIRITUALITY = "Religion &amp; Spirituality/Spirituality";
BorhanITunesSyndicationFeedCategories.prototype.SCIENCE_MEDICINE = "Science &amp; Medicine";
BorhanITunesSyndicationFeedCategories.prototype.SCIENCE_MEDICINE_MEDICINE = "Science &amp; Medicine/Medicine";
BorhanITunesSyndicationFeedCategories.prototype.SCIENCE_MEDICINE_NATURAL_SCIENCES = "Science &amp; Medicine/Natural Sciences";
BorhanITunesSyndicationFeedCategories.prototype.SCIENCE_MEDICINE_SOCIAL_SCIENCES = "Science &amp; Medicine/Social Sciences";
BorhanITunesSyndicationFeedCategories.prototype.SOCIETY_CULTURE = "Society &amp; Culture";
BorhanITunesSyndicationFeedCategories.prototype.SOCIETY_CULTURE_HISTORY = "Society &amp; Culture/History";
BorhanITunesSyndicationFeedCategories.prototype.SOCIETY_CULTURE_PERSONAL_JOURNALS = "Society &amp; Culture/Personal Journals";
BorhanITunesSyndicationFeedCategories.prototype.SOCIETY_CULTURE_PHILOSOPHY = "Society &amp; Culture/Philosophy";
BorhanITunesSyndicationFeedCategories.prototype.SOCIETY_CULTURE_PLACES_TRAVEL = "Society &amp; Culture/Places &amp; Travel";
BorhanITunesSyndicationFeedCategories.prototype.SPORTS_RECREATION = "Sports &amp; Recreation";
BorhanITunesSyndicationFeedCategories.prototype.SPORTS_RECREATION_AMATEUR = "Sports &amp; Recreation/Amateur";
BorhanITunesSyndicationFeedCategories.prototype.SPORTS_RECREATION_COLLEGE_HIGH_SCHOOL = "Sports &amp; Recreation/College &amp; High School";
BorhanITunesSyndicationFeedCategories.prototype.SPORTS_RECREATION_OUTDOOR = "Sports &amp; Recreation/Outdoor";
BorhanITunesSyndicationFeedCategories.prototype.SPORTS_RECREATION_PROFESSIONAL = "Sports &amp; Recreation/Professional";
BorhanITunesSyndicationFeedCategories.prototype.TECHNOLOGY = "Technology";
BorhanITunesSyndicationFeedCategories.prototype.TECHNOLOGY_GADGETS = "Technology/Gadgets";
BorhanITunesSyndicationFeedCategories.prototype.TECHNOLOGY_TECH_NEWS = "Technology/Tech News";
BorhanITunesSyndicationFeedCategories.prototype.TECHNOLOGY_PODCASTING = "Technology/Podcasting";
BorhanITunesSyndicationFeedCategories.prototype.TECHNOLOGY_SOFTWARE_HOW_TO = "Technology/Software How-To";
BorhanITunesSyndicationFeedCategories.prototype.TV_FILM = "TV &amp; Film";

function BorhanITunesSyndicationFeedOrderBy()
{
}
BorhanITunesSyndicationFeedOrderBy.prototype.PLAYLIST_ID_ASC = "+playlistId";
BorhanITunesSyndicationFeedOrderBy.prototype.PLAYLIST_ID_DESC = "-playlistId";
BorhanITunesSyndicationFeedOrderBy.prototype.NAME_ASC = "+name";
BorhanITunesSyndicationFeedOrderBy.prototype.NAME_DESC = "-name";
BorhanITunesSyndicationFeedOrderBy.prototype.TYPE_ASC = "+type";
BorhanITunesSyndicationFeedOrderBy.prototype.TYPE_DESC = "-type";
BorhanITunesSyndicationFeedOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanITunesSyndicationFeedOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanLicenseType()
{
}
BorhanLicenseType.prototype.UNKNOWN = -1;
BorhanLicenseType.prototype.NONE = 0;
BorhanLicenseType.prototype.COPYRIGHTED = 1;
BorhanLicenseType.prototype.PUBLIC_DOMAIN = 2;
BorhanLicenseType.prototype.CREATIVECOMMONS_ATTRIBUTION = 3;
BorhanLicenseType.prototype.CREATIVECOMMONS_ATTRIBUTION_SHARE_ALIKE = 4;
BorhanLicenseType.prototype.CREATIVECOMMONS_ATTRIBUTION_NO_DERIVATIVES = 5;
BorhanLicenseType.prototype.CREATIVECOMMONS_ATTRIBUTION_NON_COMMERCIAL = 6;
BorhanLicenseType.prototype.CREATIVECOMMONS_ATTRIBUTION_NON_COMMERCIAL_SHARE_ALIKE = 7;
BorhanLicenseType.prototype.CREATIVECOMMONS_ATTRIBUTION_NON_COMMERCIAL_NO_DERIVATIVES = 8;
BorhanLicenseType.prototype.GFDL = 9;
BorhanLicenseType.prototype.GPL = 10;
BorhanLicenseType.prototype.AFFERO_GPL = 11;
BorhanLicenseType.prototype.LGPL = 12;
BorhanLicenseType.prototype.BSD = 13;
BorhanLicenseType.prototype.APACHE = 14;
BorhanLicenseType.prototype.MOZILLA = 15;

function BorhanMailJobOrderBy()
{
}
BorhanMailJobOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanMailJobOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanMailJobOrderBy.prototype.EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
BorhanMailJobOrderBy.prototype.EXECUTION_ATTEMPTS_DESC = "-executionAttempts";

function BorhanMailJobStatus()
{
}
BorhanMailJobStatus.prototype.PENDING = 1;
BorhanMailJobStatus.prototype.SENT = 2;
BorhanMailJobStatus.prototype.ERROR = 3;
BorhanMailJobStatus.prototype.QUEUED = 4;

function BorhanMailType()
{
}
BorhanMailType.prototype.MAIL_TYPE_BORHAN_NEWSLETTER = 10;
BorhanMailType.prototype.MAIL_TYPE_ADDED_TO_FAVORITES = 11;
BorhanMailType.prototype.MAIL_TYPE_ADDED_TO_CLIP_FAVORITES = 12;
BorhanMailType.prototype.MAIL_TYPE_NEW_COMMENT_IN_PROFILE = 13;
BorhanMailType.prototype.MAIL_TYPE_CLIP_ADDED_YOUR_BORHAN = 20;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_ADDED = 21;
BorhanMailType.prototype.MAIL_TYPE_ROUGHCUT_CREATED = 22;
BorhanMailType.prototype.MAIL_TYPE_ADDED_BORHAN_TO_YOUR_FAVORITES = 23;
BorhanMailType.prototype.MAIL_TYPE_NEW_COMMENT_IN_BORHAN = 24;
BorhanMailType.prototype.MAIL_TYPE_CLIP_ADDED = 30;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_CREATED = 31;
BorhanMailType.prototype.MAIL_TYPE_ADDED_BORHAN_TO_HIS_FAVORITES = 32;
BorhanMailType.prototype.MAIL_TYPE_NEW_COMMENT_IN_BORHAN_YOU_CONTRIBUTED = 33;
BorhanMailType.prototype.MAIL_TYPE_CLIP_CONTRIBUTED = 40;
BorhanMailType.prototype.MAIL_TYPE_ROUGHCUT_CREATED_SUBSCRIBED = 41;
BorhanMailType.prototype.MAIL_TYPE_ADDED_BORHAN_TO_HIS_FAVORITES_SUBSCRIBED = 42;
BorhanMailType.prototype.MAIL_TYPE_NEW_COMMENT_IN_BORHAN_YOU_SUBSCRIBED = 43;
BorhanMailType.prototype.MAIL_TYPE_REGISTER_CONFIRM = 50;
BorhanMailType.prototype.MAIL_TYPE_PASSWORD_RESET = 51;
BorhanMailType.prototype.MAIL_TYPE_LOGIN_MAIL_RESET = 52;
BorhanMailType.prototype.MAIL_TYPE_REGISTER_CONFIRM_VIDEO_SERVICE = 54;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_READY = 60;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_IS_READY = 62;
BorhanMailType.prototype.MAIL_TYPE_BULK_DOWNLOAD_READY = 63;
BorhanMailType.prototype.MAIL_TYPE_NOTIFY_ERR = 70;
BorhanMailType.prototype.MAIL_TYPE_ACCOUNT_UPGRADE_CONFIRM = 80;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_SERVICE_NOTICE = 81;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_SERVICE_NOTICE_LIMIT_REACHED = 82;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_SERVICE_NOTICE_ACCOUNT_LOCKED = 83;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_SERVICE_NOTICE_ACCOUNT_DELETED = 84;
BorhanMailType.prototype.MAIL_TYPE_VIDEO_SERVICE_NOTICE_UPGRADE_OFFER = 85;
BorhanMailType.prototype.MAIL_TYPE_ACCOUNT_REACTIVE_CONFIRM = 86;
BorhanMailType.prototype.MAIL_TYPE_SYSTEM_USER_RESET_PASSWORD = 110;
BorhanMailType.prototype.MAIL_TYPE_SYSTEM_USER_RESET_PASSWORD_SUCCESS = 111;

function BorhanPlayableEntryOrderBy()
{
}
BorhanPlayableEntryOrderBy.prototype.PLAYS_ASC = "+plays";
BorhanPlayableEntryOrderBy.prototype.PLAYS_DESC = "-plays";
BorhanPlayableEntryOrderBy.prototype.VIEWS_ASC = "+views";
BorhanPlayableEntryOrderBy.prototype.VIEWS_DESC = "-views";
BorhanPlayableEntryOrderBy.prototype.DURATION_ASC = "+duration";
BorhanPlayableEntryOrderBy.prototype.DURATION_DESC = "-duration";
BorhanPlayableEntryOrderBy.prototype.NAME_ASC = "+name";
BorhanPlayableEntryOrderBy.prototype.NAME_DESC = "-name";
BorhanPlayableEntryOrderBy.prototype.MODERATION_COUNT_ASC = "+moderationCount";
BorhanPlayableEntryOrderBy.prototype.MODERATION_COUNT_DESC = "-moderationCount";
BorhanPlayableEntryOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanPlayableEntryOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanPlayableEntryOrderBy.prototype.RANK_ASC = "+rank";
BorhanPlayableEntryOrderBy.prototype.RANK_DESC = "-rank";

function BorhanMediaEntryOrderBy()
{
}
BorhanMediaEntryOrderBy.prototype.MEDIA_TYPE_ASC = "+mediaType";
BorhanMediaEntryOrderBy.prototype.MEDIA_TYPE_DESC = "-mediaType";
BorhanMediaEntryOrderBy.prototype.PLAYS_ASC = "+plays";
BorhanMediaEntryOrderBy.prototype.PLAYS_DESC = "-plays";
BorhanMediaEntryOrderBy.prototype.VIEWS_ASC = "+views";
BorhanMediaEntryOrderBy.prototype.VIEWS_DESC = "-views";
BorhanMediaEntryOrderBy.prototype.DURATION_ASC = "+duration";
BorhanMediaEntryOrderBy.prototype.DURATION_DESC = "-duration";
BorhanMediaEntryOrderBy.prototype.NAME_ASC = "+name";
BorhanMediaEntryOrderBy.prototype.NAME_DESC = "-name";
BorhanMediaEntryOrderBy.prototype.MODERATION_COUNT_ASC = "+moderationCount";
BorhanMediaEntryOrderBy.prototype.MODERATION_COUNT_DESC = "-moderationCount";
BorhanMediaEntryOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanMediaEntryOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanMediaEntryOrderBy.prototype.RANK_ASC = "+rank";
BorhanMediaEntryOrderBy.prototype.RANK_DESC = "-rank";

function BorhanMediaType()
{
}
BorhanMediaType.prototype.VIDEO = 1;
BorhanMediaType.prototype.IMAGE = 2;
BorhanMediaType.prototype.AUDIO = 5;

function BorhanMixEntryOrderBy()
{
}
BorhanMixEntryOrderBy.prototype.PLAYS_ASC = "+plays";
BorhanMixEntryOrderBy.prototype.PLAYS_DESC = "-plays";
BorhanMixEntryOrderBy.prototype.VIEWS_ASC = "+views";
BorhanMixEntryOrderBy.prototype.VIEWS_DESC = "-views";
BorhanMixEntryOrderBy.prototype.DURATION_ASC = "+duration";
BorhanMixEntryOrderBy.prototype.DURATION_DESC = "-duration";
BorhanMixEntryOrderBy.prototype.NAME_ASC = "+name";
BorhanMixEntryOrderBy.prototype.NAME_DESC = "-name";
BorhanMixEntryOrderBy.prototype.MODERATION_COUNT_ASC = "+moderationCount";
BorhanMixEntryOrderBy.prototype.MODERATION_COUNT_DESC = "-moderationCount";
BorhanMixEntryOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanMixEntryOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanMixEntryOrderBy.prototype.RANK_ASC = "+rank";
BorhanMixEntryOrderBy.prototype.RANK_DESC = "-rank";

function BorhanModerationFlagStatus()
{
}
BorhanModerationFlagStatus.prototype.PENDING = 1;
BorhanModerationFlagStatus.prototype.MODERATED = 2;

function BorhanModerationFlagType()
{
}
BorhanModerationFlagType.prototype.SEXUAL_CONTENT = 1;
BorhanModerationFlagType.prototype.VIOLENT_REPULSIVE = 2;
BorhanModerationFlagType.prototype.HARMFUL_DANGEROUS = 3;
BorhanModerationFlagType.prototype.SPAM_COMMERCIALS = 4;

function BorhanModerationObjectType()
{
}
BorhanModerationObjectType.prototype.ENTRY = 2;
BorhanModerationObjectType.prototype.USER = 3;

function BorhanNotificationObjectType()
{
}
BorhanNotificationObjectType.prototype.ENTRY = 1;
BorhanNotificationObjectType.prototype.KSHOW = 2;
BorhanNotificationObjectType.prototype.USER = 3;
BorhanNotificationObjectType.prototype.BATCH_JOB = 4;

function BorhanNotificationOrderBy()
{
}
BorhanNotificationOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanNotificationOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanNotificationOrderBy.prototype.EXECUTION_ATTEMPTS_ASC = "+executionAttempts";
BorhanNotificationOrderBy.prototype.EXECUTION_ATTEMPTS_DESC = "-executionAttempts";

function BorhanNotificationStatus()
{
}
BorhanNotificationStatus.prototype.PENDING = 1;
BorhanNotificationStatus.prototype.SENT = 2;
BorhanNotificationStatus.prototype.ERROR = 3;
BorhanNotificationStatus.prototype.SHOULD_RESEND = 4;
BorhanNotificationStatus.prototype.ERROR_RESENDING = 5;
BorhanNotificationStatus.prototype.SENT_SYNCH = 6;
BorhanNotificationStatus.prototype.QUEUED = 7;

function BorhanNotificationType()
{
}
BorhanNotificationType.prototype.ENTRY_ADD = 1;
BorhanNotificationType.prototype.ENTR_UPDATE_PERMISSIONS = 2;
BorhanNotificationType.prototype.ENTRY_DELETE = 3;
BorhanNotificationType.prototype.ENTRY_BLOCK = 4;
BorhanNotificationType.prototype.ENTRY_UPDATE = 5;
BorhanNotificationType.prototype.ENTRY_UPDATE_THUMBNAIL = 6;
BorhanNotificationType.prototype.ENTRY_UPDATE_MODERATION = 7;
BorhanNotificationType.prototype.USER_ADD = 21;
BorhanNotificationType.prototype.USER_BANNED = 26;

function BorhanNullableBoolean()
{
}
BorhanNullableBoolean.prototype.NULL_VALUE = -1;
BorhanNullableBoolean.prototype.FALSE_VALUE = 0;
BorhanNullableBoolean.prototype.TRUE_VALUE = 1;

function BorhanPartnerOrderBy()
{
}
BorhanPartnerOrderBy.prototype.ID_ASC = "+id";
BorhanPartnerOrderBy.prototype.ID_DESC = "-id";
BorhanPartnerOrderBy.prototype.NAME_ASC = "+name";
BorhanPartnerOrderBy.prototype.NAME_DESC = "-name";
BorhanPartnerOrderBy.prototype.WEBSITE_ASC = "+website";
BorhanPartnerOrderBy.prototype.WEBSITE_DESC = "-website";
BorhanPartnerOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanPartnerOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanPartnerOrderBy.prototype.ADMIN_NAME_ASC = "+adminName";
BorhanPartnerOrderBy.prototype.ADMIN_NAME_DESC = "-adminName";
BorhanPartnerOrderBy.prototype.ADMIN_EMAIL_ASC = "+adminEmail";
BorhanPartnerOrderBy.prototype.ADMIN_EMAIL_DESC = "-adminEmail";
BorhanPartnerOrderBy.prototype.STATUS_ASC = "+status";
BorhanPartnerOrderBy.prototype.STATUS_DESC = "-status";

function BorhanPartnerType()
{
}
BorhanPartnerType.prototype.BMC = 1;
BorhanPartnerType.prototype.WIKI = 100;
BorhanPartnerType.prototype.WORDPRESS = 101;
BorhanPartnerType.prototype.DRUPAL = 102;
BorhanPartnerType.prototype.DEKIWIKI = 103;
BorhanPartnerType.prototype.MOODLE = 104;
BorhanPartnerType.prototype.COMMUNITY_EDITION = 105;
BorhanPartnerType.prototype.JOOMLA = 106;

function BorhanPlaylistOrderBy()
{
}
BorhanPlaylistOrderBy.prototype.NAME_ASC = "+name";
BorhanPlaylistOrderBy.prototype.NAME_DESC = "-name";
BorhanPlaylistOrderBy.prototype.MODERATION_COUNT_ASC = "+moderationCount";
BorhanPlaylistOrderBy.prototype.MODERATION_COUNT_DESC = "-moderationCount";
BorhanPlaylistOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanPlaylistOrderBy.prototype.CREATED_AT_DESC = "-createdAt";
BorhanPlaylistOrderBy.prototype.RANK_ASC = "+rank";
BorhanPlaylistOrderBy.prototype.RANK_DESC = "-rank";

function BorhanPlaylistType()
{
}
BorhanPlaylistType.prototype.DYNAMIC = 10;
BorhanPlaylistType.prototype.STATIC_LIST = 3;
BorhanPlaylistType.prototype.EXTERNAL = 101;

function BorhanReportType()
{
}
BorhanReportType.prototype.TOP_CONTENT = 1;
BorhanReportType.prototype.CONTENT_DROPOFF = 2;
BorhanReportType.prototype.CONTENT_INTERACTIONS = 3;
BorhanReportType.prototype.MAP_OVERLAY = 4;
BorhanReportType.prototype.TOP_CONTRIBUTORS = 5;
BorhanReportType.prototype.TOP_SYNDICATION = 6;
BorhanReportType.prototype.CONTENT_CONTRIBUTIONS = 7;
BorhanReportType.prototype.WIDGETS_STATS = 8;

function BorhanSearchProviderType()
{
}
BorhanSearchProviderType.prototype.FLICKR = 3;
BorhanSearchProviderType.prototype.YOUTUBE = 4;
BorhanSearchProviderType.prototype.MYSPACE = 7;
BorhanSearchProviderType.prototype.PHOTOBUCKET = 8;
BorhanSearchProviderType.prototype.JAMENDO = 9;
BorhanSearchProviderType.prototype.CCMIXTER = 10;
BorhanSearchProviderType.prototype.NYPL = 11;
BorhanSearchProviderType.prototype.CURRENT = 12;
BorhanSearchProviderType.prototype.MEDIA_COMMONS = 13;
BorhanSearchProviderType.prototype.BORHAN = 20;
BorhanSearchProviderType.prototype.BORHAN_USER_CLIPS = 21;
BorhanSearchProviderType.prototype.ARCHIVE_ORG = 22;
BorhanSearchProviderType.prototype.BORHAN_PARTNER = 23;
BorhanSearchProviderType.prototype.METACAFE = 24;
BorhanSearchProviderType.prototype.SEARCH_PROXY = 28;

function BorhanSessionType()
{
}
BorhanSessionType.prototype.USER = 0;
BorhanSessionType.prototype.ADMIN = 2;

function BorhanSiteRestrictionType()
{
}
BorhanSiteRestrictionType.prototype.RESTRICT_SITE_LIST = 0;
BorhanSiteRestrictionType.prototype.ALLOW_SITE_LIST = 1;

function BorhanSourceType()
{
}
BorhanSourceType.prototype.FILE = 1;
BorhanSourceType.prototype.WEBCAM = 2;
BorhanSourceType.prototype.URL = 5;
BorhanSourceType.prototype.SEARCH_PROVIDER = 6;

function BorhanStatsEventType()
{
}
BorhanStatsEventType.prototype.WIDGET_LOADED = 1;
BorhanStatsEventType.prototype.MEDIA_LOADED = 2;
BorhanStatsEventType.prototype.PLAY = 3;
BorhanStatsEventType.prototype.PLAY_REACHED_25 = 4;
BorhanStatsEventType.prototype.PLAY_REACHED_50 = 5;
BorhanStatsEventType.prototype.PLAY_REACHED_75 = 6;
BorhanStatsEventType.prototype.PLAY_REACHED_100 = 7;
BorhanStatsEventType.prototype.OPEN_EDIT = 8;
BorhanStatsEventType.prototype.OPEN_VIRAL = 9;
BorhanStatsEventType.prototype.OPEN_DOWNLOAD = 10;
BorhanStatsEventType.prototype.OPEN_REPORT = 11;
BorhanStatsEventType.prototype.BUFFER_START = 12;
BorhanStatsEventType.prototype.BUFFER_END = 13;
BorhanStatsEventType.prototype.OPEN_FULL_SCREEN = 14;
BorhanStatsEventType.prototype.CLOSE_FULL_SCREEN = 15;
BorhanStatsEventType.prototype.REPLAY = 16;
BorhanStatsEventType.prototype.SEEK = 17;
BorhanStatsEventType.prototype.OPEN_UPLOAD = 18;
BorhanStatsEventType.prototype.SAVE_PUBLISH = 19;
BorhanStatsEventType.prototype.CLOSE_EDITOR = 20;
BorhanStatsEventType.prototype.PRE_BUMPER_PLAYED = 21;
BorhanStatsEventType.prototype.POST_BUMPER_PLAYED = 22;
BorhanStatsEventType.prototype.BUMPER_CLICKED = 23;
BorhanStatsEventType.prototype.FUTURE_USE_1 = 24;
BorhanStatsEventType.prototype.FUTURE_USE_2 = 25;
BorhanStatsEventType.prototype.FUTURE_USE_3 = 26;

function BorhanStatsBmcEventType()
{
}
BorhanStatsBmcEventType.prototype.CONTENT_PAGE_VIEW = 1001;
BorhanStatsBmcEventType.prototype.CONTENT_ADD_PLAYLIST = 1010;
BorhanStatsBmcEventType.prototype.CONTENT_EDIT_PLAYLIST = 1011;
BorhanStatsBmcEventType.prototype.CONTENT_DELETE_PLAYLIST = 1012;
BorhanStatsBmcEventType.prototype.CONTENT_DELETE_ITEM = 1058;
BorhanStatsBmcEventType.prototype.CONTENT_EDIT_ENTRY = 1013;
BorhanStatsBmcEventType.prototype.CONTENT_CHANGE_THUMBNAIL = 1014;
BorhanStatsBmcEventType.prototype.CONTENT_ADD_TAGS = 1015;
BorhanStatsBmcEventType.prototype.CONTENT_REMOVE_TAGS = 1016;
BorhanStatsBmcEventType.prototype.CONTENT_ADD_ADMIN_TAGS = 1017;
BorhanStatsBmcEventType.prototype.CONTENT_REMOVE_ADMIN_TAGS = 1018;
BorhanStatsBmcEventType.prototype.CONTENT_DOWNLOAD = 1019;
BorhanStatsBmcEventType.prototype.CONTENT_APPROVE_MODERATION = 1020;
BorhanStatsBmcEventType.prototype.CONTENT_REJECT_MODERATION = 1021;
BorhanStatsBmcEventType.prototype.CONTENT_BULK_UPLOAD = 1022;
BorhanStatsBmcEventType.prototype.CONTENT_ADMIN_BCW_UPLOAD = 1023;
BorhanStatsBmcEventType.prototype.CONTENT_CONTENT_GO_TO_PAGE = 1057;
BorhanStatsBmcEventType.prototype.ACCOUNT_CHANGE_PARTNER_INFO = 1030;
BorhanStatsBmcEventType.prototype.ACCOUNT_CHANGE_LOGIN_INFO = 1031;
BorhanStatsBmcEventType.prototype.ACCOUNT_CONTACT_US_USAGE = 1032;
BorhanStatsBmcEventType.prototype.ACCOUNT_UPDATE_SERVER_SETTINGS = 1033;
BorhanStatsBmcEventType.prototype.ACCOUNT_ACCOUNT_OVERVIEW = 1034;
BorhanStatsBmcEventType.prototype.ACCOUNT_ACCESS_CONTROL = 1035;
BorhanStatsBmcEventType.prototype.ACCOUNT_TRANSCODING_SETTINGS = 1036;
BorhanStatsBmcEventType.prototype.ACCOUNT_ACCOUNT_UPGRADE = 1037;
BorhanStatsBmcEventType.prototype.ACCOUNT_SAVE_SERVER_SETTINGS = 1038;
BorhanStatsBmcEventType.prototype.ACCOUNT_ACCESS_CONTROL_DELETE = 1039;
BorhanStatsBmcEventType.prototype.ACCOUNT_SAVE_TRANSCODING_SETTINGS = 1040;
BorhanStatsBmcEventType.prototype.LOGIN = 1041;
BorhanStatsBmcEventType.prototype.DASHBOARD_IMPORT_CONTENT = 1042;
BorhanStatsBmcEventType.prototype.DASHBOARD_UPDATE_CONTENT = 1043;
BorhanStatsBmcEventType.prototype.DASHBOARD_ACCOUNT_CONTACT_US = 1044;
BorhanStatsBmcEventType.prototype.DASHBOARD_VIEW_REPORTS = 1045;
BorhanStatsBmcEventType.prototype.DASHBOARD_EMBED_PLAYER = 1046;
BorhanStatsBmcEventType.prototype.DASHBOARD_EMBED_PLAYLIST = 1047;
BorhanStatsBmcEventType.prototype.DASHBOARD_CUSTOMIZE_PLAYERS = 1048;
BorhanStatsBmcEventType.prototype.APP_STUDIO_NEW_PLAYER_SINGLE_VIDEO = 1050;
BorhanStatsBmcEventType.prototype.APP_STUDIO_NEW_PLAYER_PLAYLIST = 1051;
BorhanStatsBmcEventType.prototype.APP_STUDIO_NEW_PLAYER_MULTI_TAB_PLAYLIST = 1052;
BorhanStatsBmcEventType.prototype.APP_STUDIO_EDIT_PLAYER_SINGLE_VIDEO = 1053;
BorhanStatsBmcEventType.prototype.APP_STUDIO_EDIT_PLAYER_PLAYLIST = 1054;
BorhanStatsBmcEventType.prototype.APP_STUDIO_EDIT_PLAYER_MULTI_TAB_PLAYLIST = 1055;
BorhanStatsBmcEventType.prototype.APP_STUDIO_DUPLICATE_PLAYER = 1056;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_BANDWIDTH_USAGE_TAB = 1070;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_CONTENT_REPORTS_TAB = 1071;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_USERS_AND_COMMUNITY_REPORTS_TAB = 1072;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_TOP_CONTRIBUTORS = 1073;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_MAP_OVERLAYS = 1074;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_TOP_SYNDICATIONS = 1075;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_TOP_CONTENT = 1076;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_CONTENT_DROPOFF = 1077;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_CONTENT_INTERACTIONS = 1078;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_CONTENT_CONTRIBUTIONS = 1079;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_VIDEO_DRILL_DOWN = 1080;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_CONTENT_DRILL_DOWN_INTERACTION = 1081;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_CONTENT_CONTRIBUTIONS_DRILLDOWN = 1082;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_VIDEO_DRILL_DOWN_DROPOFF = 1083;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_MAP_OVERLAYS_DRILLDOWN = 1084;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_TOP_SYNDICATIONS_DRILL_DOWN = 1085;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_BANDWIDTH_USAGE_VIEW_MONTHLY = 1086;
BorhanStatsBmcEventType.prototype.REPORTS_AND_ANALYTICS_BANDWIDTH_USAGE_VIEW_YEARLY = 1087;

function BorhanSyndicationFeedStatus()
{
}
BorhanSyndicationFeedStatus.prototype.DELETED = -1;
BorhanSyndicationFeedStatus.prototype.ACTIVE = 1;

function BorhanSyndicationFeedType()
{
}
BorhanSyndicationFeedType.prototype.GOOGLE_VIDEO = 1;
BorhanSyndicationFeedType.prototype.YAHOO = 2;
BorhanSyndicationFeedType.prototype.ITUNES = 3;
BorhanSyndicationFeedType.prototype.TUBE_MOGUL = 4;

function BorhanSystemUserOrderBy()
{
}
BorhanSystemUserOrderBy.prototype.ID_ASC = "+id";
BorhanSystemUserOrderBy.prototype.ID_DESC = "-id";
BorhanSystemUserOrderBy.prototype.STATUS_ASC = "+status";
BorhanSystemUserOrderBy.prototype.STATUS_DESC = "-status";

function BorhanSystemUserStatus()
{
}
BorhanSystemUserStatus.prototype.BLOCKED = 0;
BorhanSystemUserStatus.prototype.ACTIVE = 1;

function BorhanTubeMogulSyndicationFeedCategories()
{
}
BorhanTubeMogulSyndicationFeedCategories.prototype.ARTS_AND_ANIMATION = "Arts &amp; Animation";
BorhanTubeMogulSyndicationFeedCategories.prototype.COMEDY = "Comedy";
BorhanTubeMogulSyndicationFeedCategories.prototype.ENTERTAINMENT = "Entertainment";
BorhanTubeMogulSyndicationFeedCategories.prototype.MUSIC = "Music";
BorhanTubeMogulSyndicationFeedCategories.prototype.NEWS_AND_BLOGS = "News &amp; Blogs";
BorhanTubeMogulSyndicationFeedCategories.prototype.SCIENCE_AND_TECHNOLOGY = "Science &amp; Technology";
BorhanTubeMogulSyndicationFeedCategories.prototype.SPORTS = "Sports";
BorhanTubeMogulSyndicationFeedCategories.prototype.TRAVEL_AND_PLACES = "Travel &amp; Places";
BorhanTubeMogulSyndicationFeedCategories.prototype.VIDEO_GAMES = "Video Games";
BorhanTubeMogulSyndicationFeedCategories.prototype.ANIMALS_AND_PETS = "Animals &amp; Pets";
BorhanTubeMogulSyndicationFeedCategories.prototype.AUTOS = "Autos";
BorhanTubeMogulSyndicationFeedCategories.prototype.VLOGS_PEOPLE = "Vlogs &amp; People";
BorhanTubeMogulSyndicationFeedCategories.prototype.HOW_TO_INSTRUCTIONAL_DIY = "How To/Instructional/DIY";
BorhanTubeMogulSyndicationFeedCategories.prototype.COMMERCIALS_PROMOTIONAL = "Commercials/Promotional";
BorhanTubeMogulSyndicationFeedCategories.prototype.FAMILY_AND_KIDS = "Family &amp; Kids";

function BorhanTubeMogulSyndicationFeedOrderBy()
{
}
BorhanTubeMogulSyndicationFeedOrderBy.prototype.PLAYLIST_ID_ASC = "+playlistId";
BorhanTubeMogulSyndicationFeedOrderBy.prototype.PLAYLIST_ID_DESC = "-playlistId";
BorhanTubeMogulSyndicationFeedOrderBy.prototype.NAME_ASC = "+name";
BorhanTubeMogulSyndicationFeedOrderBy.prototype.NAME_DESC = "-name";
BorhanTubeMogulSyndicationFeedOrderBy.prototype.TYPE_ASC = "+type";
BorhanTubeMogulSyndicationFeedOrderBy.prototype.TYPE_DESC = "-type";
BorhanTubeMogulSyndicationFeedOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanTubeMogulSyndicationFeedOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanUiConfCreationMode()
{
}
BorhanUiConfCreationMode.prototype.WIZARD = 2;
BorhanUiConfCreationMode.prototype.ADVANCED = 3;

function BorhanUiConfObjType()
{
}
BorhanUiConfObjType.prototype.PLAYER = 1;
BorhanUiConfObjType.prototype.CONTRIBUTION_WIZARD = 2;
BorhanUiConfObjType.prototype.SIMPLE_EDITOR = 3;
BorhanUiConfObjType.prototype.ADVANCED_EDITOR = 4;
BorhanUiConfObjType.prototype.PLAYLIST = 5;
BorhanUiConfObjType.prototype.APP_STUDIO = 6;

function BorhanUiConfOrderBy()
{
}
BorhanUiConfOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanUiConfOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanUploadErrorCode()
{
}
BorhanUploadErrorCode.prototype.NO_ERROR = 0;
BorhanUploadErrorCode.prototype.GENERAL_ERROR = 1;
BorhanUploadErrorCode.prototype.PARTIAL_UPLOAD = 2;

function BorhanUserOrderBy()
{
}
BorhanUserOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanUserOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanUserStatus()
{
}
BorhanUserStatus.prototype.BLOCKED = 0;
BorhanUserStatus.prototype.ACTIVE = 1;
BorhanUserStatus.prototype.DELETED = 2;

function BorhanVideoCodec()
{
}
BorhanVideoCodec.prototype.NONE = "";
BorhanVideoCodec.prototype.VP6 = "vp6";
BorhanVideoCodec.prototype.H263 = "h263";
BorhanVideoCodec.prototype.H264 = "h264";
BorhanVideoCodec.prototype.FLV = "flv";

function BorhanWidgetOrderBy()
{
}
BorhanWidgetOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanWidgetOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanWidgetSecurityType()
{
}
BorhanWidgetSecurityType.prototype.NONE = 1;
BorhanWidgetSecurityType.prototype.TIMEHASH = 2;

function BorhanYahooSyndicationFeedAdultValues()
{
}
BorhanYahooSyndicationFeedAdultValues.prototype.ADULT = "adult";
BorhanYahooSyndicationFeedAdultValues.prototype.NON_ADULT = "nonadult";

function BorhanYahooSyndicationFeedCategories()
{
}
BorhanYahooSyndicationFeedCategories.prototype.ACTION = "Action";
BorhanYahooSyndicationFeedCategories.prototype.ART_AND_ANIMATION = "Art &amp; Animation";
BorhanYahooSyndicationFeedCategories.prototype.ENTERTAINMENT_AND_TV = "Entertainment &amp; TV";
BorhanYahooSyndicationFeedCategories.prototype.FOOD = "Food";
BorhanYahooSyndicationFeedCategories.prototype.GAMES = "Games";
BorhanYahooSyndicationFeedCategories.prototype.HOW_TO = "How-To";
BorhanYahooSyndicationFeedCategories.prototype.MUSIC = "Music";
BorhanYahooSyndicationFeedCategories.prototype.PEOPLE_AND_VLOGS = "People &amp; Vlogs";
BorhanYahooSyndicationFeedCategories.prototype.SCIENCE_AND_ENVIRONMENT = "Science &amp; Environment";
BorhanYahooSyndicationFeedCategories.prototype.TRANSPORTATION = "Transportation";
BorhanYahooSyndicationFeedCategories.prototype.ANIMALS = "Animals";
BorhanYahooSyndicationFeedCategories.prototype.COMMERCIALS = "Commercials";
BorhanYahooSyndicationFeedCategories.prototype.FAMILY = "Family";
BorhanYahooSyndicationFeedCategories.prototype.FUNNY_VIDEOS = "Funny Videos";
BorhanYahooSyndicationFeedCategories.prototype.HEALTH_AND_BEAUTY = "Health &amp; Beauty";
BorhanYahooSyndicationFeedCategories.prototype.MOVIES_AND_SHORTS = "Movies &amp; Shorts";
BorhanYahooSyndicationFeedCategories.prototype.NEWS_AND_POLITICS = "News &amp; Politics";
BorhanYahooSyndicationFeedCategories.prototype.PRODUCTS_AND_TECH = "Products &amp; Tech.";
BorhanYahooSyndicationFeedCategories.prototype.SPORTS = "Sports";
BorhanYahooSyndicationFeedCategories.prototype.TRAVEL = "Travel";

function BorhanYahooSyndicationFeedOrderBy()
{
}
BorhanYahooSyndicationFeedOrderBy.prototype.PLAYLIST_ID_ASC = "+playlistId";
BorhanYahooSyndicationFeedOrderBy.prototype.PLAYLIST_ID_DESC = "-playlistId";
BorhanYahooSyndicationFeedOrderBy.prototype.NAME_ASC = "+name";
BorhanYahooSyndicationFeedOrderBy.prototype.NAME_DESC = "-name";
BorhanYahooSyndicationFeedOrderBy.prototype.TYPE_ASC = "+type";
BorhanYahooSyndicationFeedOrderBy.prototype.TYPE_DESC = "-type";
BorhanYahooSyndicationFeedOrderBy.prototype.CREATED_AT_ASC = "+createdAt";
BorhanYahooSyndicationFeedOrderBy.prototype.CREATED_AT_DESC = "-createdAt";

function BorhanAccessControl()
{
}
BorhanAccessControl.prototype = new BorhanObjectBase();
/**
 * The id of the Access Control Profile
	 * 
 *
 * @var int
 * @readonly
 */
BorhanAccessControl.prototype.id = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanAccessControl.prototype.partnerId = null;

/**
 * The name of the Access Control Profile
	 * 
 *
 * @var string
 */
BorhanAccessControl.prototype.name = null;

/**
 * The description of the Access Control Profile
	 * 
 *
 * @var string
 */
BorhanAccessControl.prototype.description = null;

/**
 * Creation date as Unix timestamp (In seconds) 
	 * 
 *
 * @var int
 * @readonly
 */
BorhanAccessControl.prototype.createdAt = null;

/**
 * True if this Conversion Profile is the default
	 * 
 *
 * @var BorhanNullableBoolean
 */
BorhanAccessControl.prototype.isDefault = null;

/**
 * Array of Access Control Restrictions
	 * 
 *
 * @var BorhanRestrictionArray
 */
BorhanAccessControl.prototype.restrictions = null;


function BorhanFilter()
{
}
BorhanFilter.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanFilter.prototype.orderBy = null;


function BorhanAccessControlFilter()
{
}
BorhanAccessControlFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var int
 */
BorhanAccessControlFilter.prototype.idEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanAccessControlFilter.prototype.idIn = null;

/**
 * 
 *
 * @var int
 */
BorhanAccessControlFilter.prototype.createdAtGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanAccessControlFilter.prototype.createdAtLessThanOrEqual = null;


function BorhanAccessControlListResponse()
{
}
BorhanAccessControlListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanAccessControlArray
 * @readonly
 */
BorhanAccessControlListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanAccessControlListResponse.prototype.totalCount = null;


function BorhanAdminUser()
{
}
BorhanAdminUser.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanAdminUser.prototype.password = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanAdminUser.prototype.email = null;

/**
 * 
 *
 * @var string
 */
BorhanAdminUser.prototype.screenName = null;


function BorhanBaseEntry()
{
}
BorhanBaseEntry.prototype = new BorhanObjectBase();
/**
 * Auto generated 10 characters alphanumeric string
	 * 
 *
 * @var string
 * @readonly
 */
BorhanBaseEntry.prototype.id = null;

/**
 * Entry name (Min 1 chars)
	 * 
 *
 * @var string
 */
BorhanBaseEntry.prototype.name = null;

/**
 * Entry description
	 * 
 *
 * @var string
 */
BorhanBaseEntry.prototype.description = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseEntry.prototype.partnerId = null;

/**
 * The ID of the user who is the owner of this entry 
	 * 
 *
 * @var string
 */
BorhanBaseEntry.prototype.userId = null;

/**
 * Entry tags
	 * 
 *
 * @var string
 */
BorhanBaseEntry.prototype.tags = null;

/**
 * Entry admin tags can be updated only by administrators
	 * 
 *
 * @var string
 */
BorhanBaseEntry.prototype.adminTags = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntry.prototype.categories = null;

/**
 * 
 *
 * @var BorhanEntryStatus
 * @readonly
 */
BorhanBaseEntry.prototype.status = null;

/**
 * Entry moderation status
	 * 
 *
 * @var BorhanEntryModerationStatus
 * @readonly
 */
BorhanBaseEntry.prototype.moderationStatus = null;

/**
 * Number of moderation requests waiting for this entry
	 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseEntry.prototype.moderationCount = null;

/**
 * The type of the entry, this is auto filled by the derived entry object
	 * 
 *
 * @var BorhanEntryType
 * @readonly
 */
BorhanBaseEntry.prototype.type = null;

/**
 * Entry creation date as Unix timestamp (In seconds)
	 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseEntry.prototype.createdAt = null;

/**
 * Calculated rank
	 * 
 *
 * @var float
 * @readonly
 */
BorhanBaseEntry.prototype.rank = null;

/**
 * The total (sum) of all votes
	 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseEntry.prototype.totalRank = null;

/**
 * Number of votes
	 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseEntry.prototype.votes = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntry.prototype.groupId = null;

/**
 * Can be used to store various partner related data as a string 
	 * 
 *
 * @var string
 */
BorhanBaseEntry.prototype.partnerData = null;

/**
 * Download URL for the entry
	 * 
 *
 * @var string
 * @readonly
 */
BorhanBaseEntry.prototype.downloadUrl = null;

/**
 * Indexed search text for full text search
 *
 * @var string
 * @readonly
 */
BorhanBaseEntry.prototype.searchText = null;

/**
 * License type used for this entry
	 * 
 *
 * @var BorhanLicenseType
 */
BorhanBaseEntry.prototype.licenseType = null;

/**
 * Version of the entry data
 *
 * @var int
 * @readonly
 */
BorhanBaseEntry.prototype.version = null;

/**
 * Thumbnail URL
	 * 
 *
 * @var string
 * @readonly
 */
BorhanBaseEntry.prototype.thumbnailUrl = null;

/**
 * The Access Control ID assigned to this entry (null when not set, send -1 to remove)  
	 * 
 *
 * @var int
 */
BorhanBaseEntry.prototype.accessControlId = null;

/**
 * Entry scheduling start date (null when not set, send -1 to remove)
	 * 
 *
 * @var int
 */
BorhanBaseEntry.prototype.startDate = null;

/**
 * Entry scheduling end date (null when not set, send -1 to remove)
	 * 
 *
 * @var int
 */
BorhanBaseEntry.prototype.endDate = null;


function BorhanBaseEntryFilter()
{
}
BorhanBaseEntryFilter.prototype = new BorhanFilter();
/**
 * This filter should be in use for retrieving only a specific entry (identified by its entryId).
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.idEqual = null;

/**
 * This filter should be in use for retrieving few specific entries (string should include comma separated list of entryId strings).
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.idIn = null;

/**
 * This filter should be in use for retrieving specific entries while applying an SQL 'LIKE' pattern matching on entry names. It should include only one pattern for matching entry names against.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.nameLike = null;

/**
 * This filter should be in use for retrieving specific entries, while applying an SQL 'LIKE' pattern matching on entry names. It could include few (comma separated) patterns for matching entry names against, while applying an OR logic to retrieve entries that match at least one input pattern.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.nameMultiLikeOr = null;

/**
 * This filter should be in use for retrieving specific entries, while applying an SQL 'LIKE' pattern matching on entry names. It could include few (comma separated) patterns for matching entry names against, while applying an AND logic to retrieve entries that match all input patterns.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.nameMultiLikeAnd = null;

/**
 * This filter should be in use for retrieving entries with a specific name.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.nameEqual = null;

/**
 * This filter should be in use for retrieving only entries which were uploaded by/assigned to users of a specific Borhan Partner (identified by Partner ID).
	 * @var int
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.partnerIdEqual = null;

/**
 * This filter should be in use for retrieving only entries within Borhan network which were uploaded by/assigned to users of few Borhan Partners  (string should include comma separated list of PartnerIDs)
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.partnerIdIn = null;

/**
 * This filter parameter should be in use for retrieving only entries, uploaded by/assigned to a specific user (identified by user Id).
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.userIdEqual = null;

/**
 * This filter should be in use for retrieving specific entries while applying an SQL 'LIKE' pattern matching on entry tags. It should include only one pattern for matching entry tags against.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsLike = null;

/**
 * This filter should be in use for retrieving specific entries, while applying an SQL 'LIKE' pattern matching on tags.  It could include few (comma separated) patterns for matching entry tags against, while applying an OR logic to retrieve entries that match at least one input pattern.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsMultiLikeOr = null;

/**
 * This filter should be in use for retrieving specific entries, while applying an SQL 'LIKE' pattern matching on tags.  It could include few (comma separated) patterns for matching entry tags against, while applying an AND logic to retrieve entries that match all input patterns.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsMultiLikeAnd = null;

/**
 * This filter should be in use for retrieving specific entries while applying an SQL 'LIKE' pattern matching on entry tags, set by an ADMIN user. It should include only one pattern for matching entry tags against.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.adminTagsLike = null;

/**
 * This filter should be in use for retrieving specific entries, while applying an SQL 'LIKE' pattern matching on tags, set by an ADMIN user.  It could include few (comma separated) patterns for matching entry tags against, while applying an OR logic to retrieve entries that match at least one input pattern.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.adminTagsMultiLikeOr = null;

/**
 * This filter should be in use for retrieving specific entries, while applying an SQL 'LIKE' pattern matching on tags, set by an ADMIN user.  It could include few (comma separated) patterns for matching entry tags against, while applying an AND logic to retrieve entries that match all input patterns.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.adminTagsMultiLikeAnd = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.categoriesMatchAnd = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.categoriesMatchOr = null;

/**
 * This filter should be in use for retrieving only entries, at a specific {@link ?object=BorhanEntryStatus BorhanEntryStatus}.
	 * @var BorhanEntryStatus
 *
 * @var BorhanEntryStatus
 */
BorhanBaseEntryFilter.prototype.statusEqual = null;

/**
 * This filter should be in use for retrieving only entries, not at a specific {@link ?object=BorhanEntryStatus BorhanEntryStatus}.
	 * @var BorhanEntryStatus
 *
 * @var BorhanEntryStatus
 */
BorhanBaseEntryFilter.prototype.statusNotEqual = null;

/**
 * This filter should be in use for retrieving only entries, at few specific {@link ?object=BorhanEntryStatus BorhanEntryStatus} (comma separated).
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.statusIn = null;

/**
 * This filter should be in use for retrieving only entries, not at few specific {@link ?object=BorhanEntryStatus BorhanEntryStatus} (comma separated).
	 * @var BorhanEntryStatus
 *
 * @var BorhanEntryStatus
 */
BorhanBaseEntryFilter.prototype.statusNotIn = null;

/**
 * 
 *
 * @var BorhanEntryModerationStatus
 */
BorhanBaseEntryFilter.prototype.moderationStatusEqual = null;

/**
 * 
 *
 * @var BorhanEntryModerationStatus
 */
BorhanBaseEntryFilter.prototype.moderationStatusNotEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.moderationStatusIn = null;

/**
 * 
 *
 * @var BorhanEntryModerationStatus
 */
BorhanBaseEntryFilter.prototype.moderationStatusNotIn = null;

/**
 * 
 *
 * @var BorhanEntryType
 */
BorhanBaseEntryFilter.prototype.typeEqual = null;

/**
 * This filter should be in use for retrieving entries of few {@link ?object=BorhanEntryType BorhanEntryType} (string should include a comma separated list of {@link ?object=BorhanEntryType BorhanEntryType} enumerated parameters).
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.typeIn = null;

/**
 * This filter parameter should be in use for retrieving only entries which were created at Borhan system after a specific time/date (standard timestamp format).
	 * @var int
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.createdAtGreaterThanOrEqual = null;

/**
 * This filter parameter should be in use for retrieving only entries which were created at Borhan system before a specific time/date (standard timestamp format).
	 * @var int
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.createdAtLessThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.groupIdEqual = null;

/**
 * This filter should be in use for retrieving specific entries while search match the input string within all of the following metadata attributes: name, description, tags, adminTags.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.searchTextMatchAnd = null;

/**
 * This filter should be in use for retrieving specific entries while search match the input string within at least one of the following metadata attributes: name, description, tags, adminTags.
	 * @var string
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.searchTextMatchOr = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.accessControlIdEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.accessControlIdIn = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.startDateGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.startDateLessThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.startDateGreaterThanOrEqualOrNull = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.startDateLessThanOrEqualOrNull = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.endDateGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.endDateLessThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.endDateGreaterThanOrEqualOrNull = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseEntryFilter.prototype.endDateLessThanOrEqualOrNull = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsNameMultiLikeOr = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsAdminTagsMultiLikeOr = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsAdminTagsNameMultiLikeOr = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsNameMultiLikeAnd = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsAdminTagsMultiLikeAnd = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseEntryFilter.prototype.tagsAdminTagsNameMultiLikeAnd = null;


function BorhanBaseEntryListResponse()
{
}
BorhanBaseEntryListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanBaseEntryArray
 * @readonly
 */
BorhanBaseEntryListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseEntryListResponse.prototype.totalCount = null;


function BorhanBaseJob()
{
}
BorhanBaseJob.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseJob.prototype.id = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseJob.prototype.partnerId = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseJob.prototype.createdAt = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseJob.prototype.updatedAt = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseJob.prototype.processorExpiration = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseJob.prototype.executionAttempts = null;


function BorhanBaseJobFilter()
{
}
BorhanBaseJobFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var int
 */
BorhanBaseJobFilter.prototype.idEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseJobFilter.prototype.idGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseJobFilter.prototype.partnerIdEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseJobFilter.prototype.partnerIdIn = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseJobFilter.prototype.createdAtGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseJobFilter.prototype.createdAtLessThanOrEqual = null;


function BorhanBaseRestriction()
{
}
BorhanBaseRestriction.prototype = new BorhanObjectBase();

function BorhanBaseSyndicationFeed()
{
}
BorhanBaseSyndicationFeed.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanBaseSyndicationFeed.prototype.id = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanBaseSyndicationFeed.prototype.feedUrl = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseSyndicationFeed.prototype.partnerId = null;

/**
 * link a playlist that will set what content the feed will include
	 * if empty, all content will be included in feed
	 * 
 *
 * @var string
 */
BorhanBaseSyndicationFeed.prototype.playlistId = null;

/**
 * feed name
	 * 
 *
 * @var string
 */
BorhanBaseSyndicationFeed.prototype.name = null;

/**
 * feed status
	 * 
 *
 * @var BorhanSyndicationFeedStatus
 * @readonly
 */
BorhanBaseSyndicationFeed.prototype.status = null;

/**
 * feed type
	 * 
 *
 * @var BorhanSyndicationFeedType
 * @readonly
 */
BorhanBaseSyndicationFeed.prototype.type = null;

/**
 * Base URL for each video, on the partners site
	 * This is required by all syndication types.
 *
 * @var string
 */
BorhanBaseSyndicationFeed.prototype.landingPage = null;

/**
 * Creation date as Unix timestamp (In seconds)
	 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseSyndicationFeed.prototype.createdAt = null;

/**
 * allow_embed tells google OR yahoo weather to allow embedding the video on google OR yahoo video results
	 * or just to provide a link to the landing page.
	 * it is applied on the video-player_loc property in the XML (google)
	 * and addes media-player tag (yahoo)
 *
 * @var bool
 */
BorhanBaseSyndicationFeed.prototype.allowEmbed = null;

/**
 * Select a uiconf ID as player skin to include in the bwidget url
 *
 * @var int
 */
BorhanBaseSyndicationFeed.prototype.playerUiconfId = null;

/**
 * 
 *
 * @var int
 */
BorhanBaseSyndicationFeed.prototype.flavorParamId = null;

/**
 * 
 *
 * @var bool
 */
BorhanBaseSyndicationFeed.prototype.transcodeExistingContent = null;

/**
 * 
 *
 * @var bool
 */
BorhanBaseSyndicationFeed.prototype.addToDefaultConversionProfile = null;

/**
 * 
 *
 * @var string
 */
BorhanBaseSyndicationFeed.prototype.categories = null;


function BorhanBaseSyndicationFeedFilter()
{
}
BorhanBaseSyndicationFeedFilter.prototype = new BorhanFilter();

function BorhanBaseSyndicationFeedListResponse()
{
}
BorhanBaseSyndicationFeedListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanBaseSyndicationFeedArray
 * @readonly
 */
BorhanBaseSyndicationFeedListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBaseSyndicationFeedListResponse.prototype.totalCount = null;


function BorhanBatchJob()
{
}
BorhanBatchJob.prototype = new BorhanBaseJob();
/**
 * 
 *
 * @var string
 */
BorhanBatchJob.prototype.entryId = null;

/**
 * 
 *
 * @var BorhanBatchJobType
 * @readonly
 */
BorhanBatchJob.prototype.jobType = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.jobSubType = null;

/**
 * 
 *
 * @var BorhanJobData
 */
BorhanBatchJob.prototype.data = null;

/**
 * 
 *
 * @var BorhanBatchJobStatus
 */
BorhanBatchJob.prototype.status = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.abort = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.checkAgainTimeout = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.progress = null;

/**
 * 
 *
 * @var string
 */
BorhanBatchJob.prototype.message = null;

/**
 * 
 *
 * @var string
 */
BorhanBatchJob.prototype.description = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.updatesCount = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.priority = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.workGroupId = null;

/**
 * The id of the bulk upload job that initiated this job
 *
 * @var int
 */
BorhanBatchJob.prototype.bulkJobId = null;

/**
 * When one job creates another - the parent should set this parentJobId to be its own id.
 *
 * @var int
 */
BorhanBatchJob.prototype.parentJobId = null;

/**
 * The id of the root parent job
 *
 * @var int
 */
BorhanBatchJob.prototype.rootJobId = null;

/**
 * The time that the job was pulled from the queue
 *
 * @var int
 */
BorhanBatchJob.prototype.queueTime = null;

/**
 * The time that the job was finished or closed as failed
 *
 * @var int
 */
BorhanBatchJob.prototype.finishTime = null;

/**
 * 
 *
 * @var BorhanBatchJobErrorTypes
 */
BorhanBatchJob.prototype.errType = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.errNumber = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJob.prototype.fileSize = null;

/**
 * 
 *
 * @var bool
 */
BorhanBatchJob.prototype.lastWorkerRemote = null;


function BorhanBatchJobFilter()
{
}
BorhanBatchJobFilter.prototype = new BorhanBaseJobFilter();
/**
 * 
 *
 * @var string
 */
BorhanBatchJobFilter.prototype.entryIdEqual = null;

/**
 * 
 *
 * @var BorhanBatchJobType
 */
BorhanBatchJobFilter.prototype.jobTypeEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanBatchJobFilter.prototype.jobTypeIn = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJobFilter.prototype.jobSubTypeEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanBatchJobFilter.prototype.jobSubTypeIn = null;

/**
 * 
 *
 * @var BorhanBatchJobStatus
 */
BorhanBatchJobFilter.prototype.statusEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanBatchJobFilter.prototype.statusIn = null;

/**
 * 
 *
 * @var string
 */
BorhanBatchJobFilter.prototype.workGroupIdIn = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJobFilter.prototype.queueTimeGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJobFilter.prototype.queueTimeLessThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJobFilter.prototype.finishTimeGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJobFilter.prototype.finishTimeLessThanOrEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanBatchJobFilter.prototype.errTypeIn = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJobFilter.prototype.fileSizeLessThan = null;

/**
 * 
 *
 * @var int
 */
BorhanBatchJobFilter.prototype.fileSizeGreaterThan = null;


function BorhanBatchJobListResponse()
{
}
BorhanBatchJobListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanBatchJobArray
 * @readonly
 */
BorhanBatchJobListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBatchJobListResponse.prototype.totalCount = null;


function BorhanBatchJobResponse()
{
}
BorhanBatchJobResponse.prototype = new BorhanObjectBase();
/**
 * The main batch job
	 * 
 *
 * @var BorhanBatchJob
 */
BorhanBatchJobResponse.prototype.batchJob = null;

/**
 * All batch jobs that reference the main job as root
	 * 
 *
 * @var BorhanBatchJobArray
 */
BorhanBatchJobResponse.prototype.childBatchJobs = null;


function BorhanJobData()
{
}
BorhanJobData.prototype = new BorhanObjectBase();

function BorhanBulkDownloadJobData()
{
}
BorhanBulkDownloadJobData.prototype = new BorhanJobData();
/**
 * Comma separated list of entry ids
	 * 
 *
 * @var string
 */
BorhanBulkDownloadJobData.prototype.entryIds = null;

/**
 * Flavor params id to use for conversion
	 * 
 *
 * @var int
 */
BorhanBulkDownloadJobData.prototype.flavorParamsId = null;

/**
 * The id of the requesting user
	 * 
 *
 * @var string
 */
BorhanBulkDownloadJobData.prototype.puserId = null;


function BorhanBulkUpload()
{
}
BorhanBulkUpload.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var int
 */
BorhanBulkUpload.prototype.id = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUpload.prototype.uploadedBy = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUpload.prototype.uploadedOn = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUpload.prototype.numOfEntries = null;

/**
 * 
 *
 * @var BorhanBatchJobStatus
 */
BorhanBulkUpload.prototype.status = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUpload.prototype.logFileUrl = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUpload.prototype.csvFileUrl = null;

/**
 * 
 *
 * @var BorhanBulkUploadResultArray
 */
BorhanBulkUpload.prototype.results = null;


function BorhanBulkUploadJobData()
{
}
BorhanBulkUploadJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var int
 */
BorhanBulkUploadJobData.prototype.userId = null;

/**
 * The screen name of the user
	 * 
 *
 * @var string
 */
BorhanBulkUploadJobData.prototype.uploadedBy = null;

/**
 * Selected profile id for all bulk entries
	 * 
 *
 * @var int
 */
BorhanBulkUploadJobData.prototype.conversionProfileId = null;

/**
 * Created by the API
	 * 
 *
 * @var string
 */
BorhanBulkUploadJobData.prototype.csvFilePath = null;

/**
 * Created by the API
	 * 
 *
 * @var string
 */
BorhanBulkUploadJobData.prototype.resultsFileLocalPath = null;

/**
 * Created by the API
	 * 
 *
 * @var string
 */
BorhanBulkUploadJobData.prototype.resultsFileUrl = null;

/**
 * Number of created entries
	 * 
 *
 * @var int
 */
BorhanBulkUploadJobData.prototype.numOfEntries = null;

/**
 * The version of the csv file
	 * 
 *
 * @var BorhanBulkUploadCsvVersion
 */
BorhanBulkUploadJobData.prototype.csvVersion = null;


function BorhanBulkUploadListResponse()
{
}
BorhanBulkUploadListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanBulkUploads
 * @readonly
 */
BorhanBulkUploadListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanBulkUploadListResponse.prototype.totalCount = null;


function BorhanBulkUploadResult()
{
}
BorhanBulkUploadResult.prototype = new BorhanObjectBase();
/**
 * The id of the result
	 * 
 *
 * @var int
 * @readonly
 */
BorhanBulkUploadResult.prototype.id = null;

/**
 * The id of the parent job
	 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.bulkUploadJobId = null;

/**
 * The index of the line in the CSV
	 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.lineIndex = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.partnerId = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.entryId = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.entryStatus = null;

/**
 * The data as recieved in the csv
	 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.rowData = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.title = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.description = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.tags = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.url = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.contentType = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.conversionProfileId = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.accessControlProfileId = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.category = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.scheduleStartDate = null;

/**
 * 
 *
 * @var int
 */
BorhanBulkUploadResult.prototype.scheduleEndDate = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.thumbnailUrl = null;

/**
 * 
 *
 * @var bool
 */
BorhanBulkUploadResult.prototype.thumbnailSaved = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.partnerData = null;

/**
 * 
 *
 * @var string
 */
BorhanBulkUploadResult.prototype.errorDescription = null;


function BorhanCEError()
{
}
BorhanCEError.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanCEError.prototype.id = null;

/**
 * 
 *
 * @var int
 */
BorhanCEError.prototype.partnerId = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.browser = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.serverIp = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.serverOs = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.phpVersion = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.ceAdminEmail = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.type = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.description = null;

/**
 * 
 *
 * @var string
 */
BorhanCEError.prototype.data = null;


function BorhanCategory()
{
}
BorhanCategory.prototype = new BorhanObjectBase();
/**
 * The id of the Category
	 * 
 *
 * @var int
 * @readonly
 */
BorhanCategory.prototype.id = null;

/**
 * 
 *
 * @var int
 */
BorhanCategory.prototype.parentId = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanCategory.prototype.depth = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanCategory.prototype.partnerId = null;

/**
 * The name of the Category. 
	 * The following characters are not allowed: '<', '>', ','
	 * 
 *
 * @var string
 */
BorhanCategory.prototype.name = null;

/**
 * The full name of the Category
	 * 
 *
 * @var string
 * @readonly
 */
BorhanCategory.prototype.fullName = null;

/**
 * Number of entries in this Category (including child categories)
	 * 
 *
 * @var int
 * @readonly
 */
BorhanCategory.prototype.entriesCount = null;

/**
 * Creation date as Unix timestamp (In seconds)
	 * 
 *
 * @var int
 * @readonly
 */
BorhanCategory.prototype.createdAt = null;


function BorhanCategoryFilter()
{
}
BorhanCategoryFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var int
 */
BorhanCategoryFilter.prototype.idEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanCategoryFilter.prototype.idIn = null;

/**
 * 
 *
 * @var int
 */
BorhanCategoryFilter.prototype.parentIdEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanCategoryFilter.prototype.parentIdIn = null;

/**
 * 
 *
 * @var int
 */
BorhanCategoryFilter.prototype.depthEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanCategoryFilter.prototype.fullNameEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanCategoryFilter.prototype.fullNameStartsWith = null;


function BorhanCategoryListResponse()
{
}
BorhanCategoryListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanCategoryArray
 * @readonly
 */
BorhanCategoryListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanCategoryListResponse.prototype.totalCount = null;


function BorhanClientNotification()
{
}
BorhanClientNotification.prototype = new BorhanObjectBase();
/**
 * The URL where the notification should be sent to 
 *
 * @var string
 */
BorhanClientNotification.prototype.url = null;

/**
 * The serialized notification data to send
 *
 * @var string
 */
BorhanClientNotification.prototype.data = null;


function BorhanConvartableJobData()
{
}
BorhanConvartableJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var string
 */
BorhanConvartableJobData.prototype.srcFileSyncLocalPath = null;

/**
 * 
 *
 * @var string
 */
BorhanConvartableJobData.prototype.srcFileSyncRemoteUrl = null;

/**
 * 
 *
 * @var int
 */
BorhanConvartableJobData.prototype.flavorParamsOutputId = null;

/**
 * 
 *
 * @var BorhanFlavorParamsOutput
 */
BorhanConvartableJobData.prototype.flavorParamsOutput = null;

/**
 * 
 *
 * @var int
 */
BorhanConvartableJobData.prototype.mediaInfoId = null;


function BorhanConversionProfile()
{
}
BorhanConversionProfile.prototype = new BorhanObjectBase();
/**
 * The id of the Conversion Profile
	 * 
 *
 * @var int
 * @readonly
 */
BorhanConversionProfile.prototype.id = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanConversionProfile.prototype.partnerId = null;

/**
 * The name of the Conversion Profile
	 * 
 *
 * @var string
 */
BorhanConversionProfile.prototype.name = null;

/**
 * The description of the Conversion Profile
	 * 
 *
 * @var string
 */
BorhanConversionProfile.prototype.description = null;

/**
 * Creation date as Unix timestamp (In seconds) 
	 * 
 *
 * @var int
 * @readonly
 */
BorhanConversionProfile.prototype.createdAt = null;

/**
 * List of included flavor ids (comma separated)
	 * 
 *
 * @var string
 */
BorhanConversionProfile.prototype.flavorParamsIds = null;

/**
 * True if this Conversion Profile is the default
	 * 
 *
 * @var BorhanNullableBoolean
 */
BorhanConversionProfile.prototype.isDefault = null;

/**
 * Cropping dimensions
	 * 
 *
 * @var BorhanCropDimensions
 */
BorhanConversionProfile.prototype.cropDimensions = null;

/**
 * Clipping start position (in miliseconds)
	 * 
 *
 * @var int
 */
BorhanConversionProfile.prototype.clipStart = null;

/**
 * Clipping duration (in miliseconds)
	 * 
 *
 * @var int
 */
BorhanConversionProfile.prototype.clipDuration = null;


function BorhanConversionProfileFilter()
{
}
BorhanConversionProfileFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var int
 */
BorhanConversionProfileFilter.prototype.idEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanConversionProfileFilter.prototype.idIn = null;


function BorhanConversionProfileListResponse()
{
}
BorhanConversionProfileListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanConversionProfileArray
 * @readonly
 */
BorhanConversionProfileListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanConversionProfileListResponse.prototype.totalCount = null;


function BorhanConvertJobData()
{
}
BorhanConvertJobData.prototype = new BorhanConvartableJobData();
/**
 * 
 *
 * @var string
 */
BorhanConvertJobData.prototype.destFileSyncLocalPath = null;

/**
 * 
 *
 * @var string
 */
BorhanConvertJobData.prototype.destFileSyncRemoteUrl = null;

/**
 * 
 *
 * @var string
 */
BorhanConvertJobData.prototype.logFileSyncLocalPath = null;

/**
 * 
 *
 * @var string
 */
BorhanConvertJobData.prototype.flavorAssetId = null;

/**
 * 
 *
 * @var string
 */
BorhanConvertJobData.prototype.remoteMediaId = null;


function BorhanConvertProfileJobData()
{
}
BorhanConvertProfileJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var string
 */
BorhanConvertProfileJobData.prototype.inputFileSyncLocalPath = null;

/**
 * The height of last created thumbnail, will be used to comapare if this thumbnail is the best we can have
	 * 
 *
 * @var int
 */
BorhanConvertProfileJobData.prototype.thumbHeight = null;

/**
 * The bit rate of last created thumbnail, will be used to comapare if this thumbnail is the best we can have
	 * 
 *
 * @var int
 */
BorhanConvertProfileJobData.prototype.thumbBitrate = null;


function BorhanCountryRestriction()
{
}
BorhanCountryRestriction.prototype = new BorhanBaseRestriction();
/**
 * Country restriction type (Allow or deny)
	 * 
 *
 * @var BorhanCountryRestrictionType
 */
BorhanCountryRestriction.prototype.countryRestrictionType = null;

/**
 * Comma separated list of country codes to allow to deny 
	 * 
 *
 * @var string
 */
BorhanCountryRestriction.prototype.countryList = null;


function BorhanCropDimensions()
{
}
BorhanCropDimensions.prototype = new BorhanObjectBase();
/**
 * Crop left point
	 * 
 *
 * @var int
 */
BorhanCropDimensions.prototype.left = null;

/**
 * Crop top point
	 * 
 *
 * @var int
 */
BorhanCropDimensions.prototype.top = null;

/**
 * Crop width
	 * 
 *
 * @var int
 */
BorhanCropDimensions.prototype.width = null;

/**
 * Crop height
	 * 
 *
 * @var int
 */
BorhanCropDimensions.prototype.height = null;


function BorhanDataEntry()
{
}
BorhanDataEntry.prototype = new BorhanBaseEntry();
/**
 * The data of the entry
 *
 * @var string
 */
BorhanDataEntry.prototype.dataContent = null;


function BorhanDataEntryFilter()
{
}
BorhanDataEntryFilter.prototype = new BorhanBaseEntryFilter();

function BorhanDataListResponse()
{
}
BorhanDataListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanDataEntryArray
 * @readonly
 */
BorhanDataListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanDataListResponse.prototype.totalCount = null;


function BorhanDirectoryRestriction()
{
}
BorhanDirectoryRestriction.prototype = new BorhanBaseRestriction();
/**
 * Borhan directory restriction type
	 * 
 *
 * @var BorhanDirectoryRestrictionType
 */
BorhanDirectoryRestriction.prototype.directoryRestrictionType = null;


function BorhanDocumentEntry()
{
}
BorhanDocumentEntry.prototype = new BorhanBaseEntry();
/**
 * The type of the document
 *
 * @var BorhanDocumentType
 * @insertonly
 */
BorhanDocumentEntry.prototype.documentType = null;


function BorhanDocumentEntryFilter()
{
}
BorhanDocumentEntryFilter.prototype = new BorhanBaseEntryFilter();
/**
 * 
 *
 * @var BorhanDocumentType
 */
BorhanDocumentEntryFilter.prototype.documentTypeEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanDocumentEntryFilter.prototype.documentTypeIn = null;


function BorhanEntryExtraDataParams()
{
}
BorhanEntryExtraDataParams.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanEntryExtraDataParams.prototype.referrer = null;


function BorhanEntryExtraDataResult()
{
}
BorhanEntryExtraDataResult.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var bool
 */
BorhanEntryExtraDataResult.prototype.isSiteRestricted = null;

/**
 * 
 *
 * @var bool
 */
BorhanEntryExtraDataResult.prototype.isCountryRestricted = null;

/**
 * 
 *
 * @var bool
 */
BorhanEntryExtraDataResult.prototype.isSessionRestricted = null;

/**
 * 
 *
 * @var int
 */
BorhanEntryExtraDataResult.prototype.previewLength = null;

/**
 * 
 *
 * @var bool
 */
BorhanEntryExtraDataResult.prototype.isScheduledNow = null;

/**
 * 
 *
 * @var bool
 */
BorhanEntryExtraDataResult.prototype.isAdmin = null;


function BorhanExtractMediaJobData()
{
}
BorhanExtractMediaJobData.prototype = new BorhanConvartableJobData();
/**
 * 
 *
 * @var string
 */
BorhanExtractMediaJobData.prototype.flavorAssetId = null;


function BorhanFilterPager()
{
}
BorhanFilterPager.prototype = new BorhanObjectBase();
/**
 * The number of objects to retrieve. (Default is 30, maximum page size is 500).
	 * 
 *
 * @var int
 */
BorhanFilterPager.prototype.pageSize = null;

/**
 * The page number for which {pageSize} of objects should be retrieved (Default is 1).
	 * 
 *
 * @var int
 */
BorhanFilterPager.prototype.pageIndex = null;


function BorhanFlattenJobData()
{
}
BorhanFlattenJobData.prototype = new BorhanJobData();

function BorhanFlavorAsset()
{
}
BorhanFlavorAsset.prototype = new BorhanObjectBase();
/**
 * The ID of the Flavor Asset
	 * 
 *
 * @var string
 * @readonly
 */
BorhanFlavorAsset.prototype.id = null;

/**
 * The entry ID of the Flavor Asset
	 * 
 *
 * @var string
 * @readonly
 */
BorhanFlavorAsset.prototype.entryId = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanFlavorAsset.prototype.partnerId = null;

/**
 * The status of the Flavor Asset
	 * 
 *
 * @var BorhanFlavorAssetStatus
 * @readonly
 */
BorhanFlavorAsset.prototype.status = null;

/**
 * The Flavor Params used to create this Flavor Asset
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorAsset.prototype.flavorParamsId = null;

/**
 * The version of the Flavor Asset
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorAsset.prototype.version = null;

/**
 * The width of the Flavor Asset 
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorAsset.prototype.width = null;

/**
 * The height of the Flavor Asset
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorAsset.prototype.height = null;

/**
 * The overall bitrate (in KBits) of the Flavor Asset 
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorAsset.prototype.bitrate = null;

/**
 * The frame rate (in FPS) of the Flavor Asset
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorAsset.prototype.frameRate = null;

/**
 * The size (in KBytes) of the Flavor Asset
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorAsset.prototype.size = null;

/**
 * True if this Flavor Asset is the original source
	 * 
 *
 * @var bool
 */
BorhanFlavorAsset.prototype.isOriginal = null;

/**
 * Tags used to identify the Flavor Asset in various scenarios
	 * 
 *
 * @var string
 */
BorhanFlavorAsset.prototype.tags = null;

/**
 * True if this Flavor Asset is playable in BDP
	 * 
 *
 * @var bool
 */
BorhanFlavorAsset.prototype.isWeb = null;

/**
 * The file extension
	 * 
 *
 * @var string
 */
BorhanFlavorAsset.prototype.fileExt = null;

/**
 * The container format
	 * 
 *
 * @var string
 */
BorhanFlavorAsset.prototype.containerFormat = null;

/**
 * The video codec
	 * 
 *
 * @var string
 */
BorhanFlavorAsset.prototype.videoCodecId = null;


function BorhanFlavorAssetWithParams()
{
}
BorhanFlavorAssetWithParams.prototype = new BorhanObjectBase();
/**
 * The Flavor Asset (Can be null when there are params without asset)
	 * 
 *
 * @var BorhanFlavorAsset
 */
BorhanFlavorAssetWithParams.prototype.flavorAsset = null;

/**
 * The Flavor Params
	 * 
 *
 * @var BorhanFlavorParams
 */
BorhanFlavorAssetWithParams.prototype.flavorParams = null;

/**
 * The entry id
	 * 
 *
 * @var string
 */
BorhanFlavorAssetWithParams.prototype.entryId = null;


function BorhanFlavorParams()
{
}
BorhanFlavorParams.prototype = new BorhanObjectBase();
/**
 * The id of the Flavor Params
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorParams.prototype.id = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorParams.prototype.partnerId = null;

/**
 * The name of the Flavor Params
	 * 
 *
 * @var string
 */
BorhanFlavorParams.prototype.name = null;

/**
 * The description of the Flavor Params
	 * 
 *
 * @var string
 */
BorhanFlavorParams.prototype.description = null;

/**
 * Creation date as Unix timestamp (In seconds)
	 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorParams.prototype.createdAt = null;

/**
 * True if those Flavor Params are part of system defaults
	 * 
 *
 * @var BorhanNullableBoolean
 * @readonly
 */
BorhanFlavorParams.prototype.isSystemDefault = null;

/**
 * The Flavor Params tags are used to identify the flavor for different usage (e.g. web, hd, mobile)
	 * 
 *
 * @var string
 */
BorhanFlavorParams.prototype.tags = null;

/**
 * The container format of the Flavor Params
	 * 
 *
 * @var BorhanContainerFormat
 */
BorhanFlavorParams.prototype.format = null;

/**
 * The video codec of the Flavor Params
	 * 
 *
 * @var BorhanVideoCodec
 */
BorhanFlavorParams.prototype.videoCodec = null;

/**
 * The video bitrate (in KBits) of the Flavor Params
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.videoBitrate = null;

/**
 * The audio codec of the Flavor Params
	 * 
 *
 * @var BorhanAudioCodec
 */
BorhanFlavorParams.prototype.audioCodec = null;

/**
 * The audio bitrate (in KBits) of the Flavor Params
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.audioBitrate = null;

/**
 * The number of audio channels for "downmixing"
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.audioChannels = null;

/**
 * The audio sample rate of the Flavor Params
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.audioSampleRate = null;

/**
 * The desired width of the Flavor Params
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.width = null;

/**
 * The desired height of the Flavor Params
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.height = null;

/**
 * The frame rate of the Flavor Params
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.frameRate = null;

/**
 * The gop size of the Flavor Params
	 * 
 *
 * @var int
 */
BorhanFlavorParams.prototype.gopSize = null;

/**
 * The list of conversion engines (comma separated)
	 * 
 *
 * @var string
 */
BorhanFlavorParams.prototype.conversionEngines = null;

/**
 * The list of conversion engines extra params (separated with "|")
	 * 
 *
 * @var string
 */
BorhanFlavorParams.prototype.conversionEnginesExtraParams = null;

/**
 * 
 *
 * @var bool
 */
BorhanFlavorParams.prototype.twoPass = null;


function BorhanFlavorParamsFilter()
{
}
BorhanFlavorParamsFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var BorhanNullableBoolean
 */
BorhanFlavorParamsFilter.prototype.isSystemDefaultEqual = null;


function BorhanFlavorParamsListResponse()
{
}
BorhanFlavorParamsListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanFlavorParamsArray
 * @readonly
 */
BorhanFlavorParamsListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanFlavorParamsListResponse.prototype.totalCount = null;


function BorhanFlavorParamsOutput()
{
}
BorhanFlavorParamsOutput.prototype = new BorhanFlavorParams();
/**
 * 
 *
 * @var int
 */
BorhanFlavorParamsOutput.prototype.flavorParamsId = null;

/**
 * 
 *
 * @var string
 */
BorhanFlavorParamsOutput.prototype.commandLinesStr = null;


function BorhanFlavorParamsOutputFilter()
{
}
BorhanFlavorParamsOutputFilter.prototype = new BorhanFlavorParamsFilter();

function BorhanGoogleVideoSyndicationFeed()
{
}
BorhanGoogleVideoSyndicationFeed.prototype = new BorhanBaseSyndicationFeed();
/**
 * 
 *
 * @var BorhanGoogleSyndicationFeedAdultValues
 */
BorhanGoogleVideoSyndicationFeed.prototype.adultContent = null;


function BorhanGoogleVideoSyndicationFeedFilter()
{
}
BorhanGoogleVideoSyndicationFeedFilter.prototype = new BorhanBaseSyndicationFeedFilter();

function BorhanITunesSyndicationFeed()
{
}
BorhanITunesSyndicationFeed.prototype = new BorhanBaseSyndicationFeed();
/**
 * feed description
	 * 
 *
 * @var string
 */
BorhanITunesSyndicationFeed.prototype.feedDescription = null;

/**
 * feed language
	 * 
 *
 * @var string
 */
BorhanITunesSyndicationFeed.prototype.language = null;

/**
 * feed landing page (i.e publisher website)
	 * 
 *
 * @var string
 */
BorhanITunesSyndicationFeed.prototype.feedLandingPage = null;

/**
 * author/publisher name
	 * 
 *
 * @var string
 */
BorhanITunesSyndicationFeed.prototype.ownerName = null;

/**
 * publisher email
	 * 
 *
 * @var string
 */
BorhanITunesSyndicationFeed.prototype.ownerEmail = null;

/**
 * podcast thumbnail
	 * 
 *
 * @var string
 */
BorhanITunesSyndicationFeed.prototype.feedImageUrl = null;

/**
 * 
 *
 * @var BorhanITunesSyndicationFeedCategories
 * @readonly
 */
BorhanITunesSyndicationFeed.prototype.category = null;

/**
 * 
 *
 * @var BorhanITunesSyndicationFeedAdultValues
 */
BorhanITunesSyndicationFeed.prototype.adultContent = null;

/**
 * 
 *
 * @var string
 */
BorhanITunesSyndicationFeed.prototype.feedAuthor = null;


function BorhanITunesSyndicationFeedFilter()
{
}
BorhanITunesSyndicationFeedFilter.prototype = new BorhanBaseSyndicationFeedFilter();

function BorhanImportJobData()
{
}
BorhanImportJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var string
 */
BorhanImportJobData.prototype.srcFileUrl = null;

/**
 * 
 *
 * @var string
 */
BorhanImportJobData.prototype.destFileLocalPath = null;

/**
 * 
 *
 * @var string
 */
BorhanImportJobData.prototype.flavorAssetId = null;


function BorhanMailJob()
{
}
BorhanMailJob.prototype = new BorhanBaseJob();
/**
 * 
 *
 * @var BorhanMailType
 */
BorhanMailJob.prototype.mailType = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJob.prototype.mailPriority = null;

/**
 * 
 *
 * @var BorhanMailJobStatus
 */
BorhanMailJob.prototype.status = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJob.prototype.recipientName = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJob.prototype.recipientEmail = null;

/**
 * kuserId  
 *
 * @var int
 */
BorhanMailJob.prototype.recipientId = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJob.prototype.fromName = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJob.prototype.fromEmail = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJob.prototype.bodyParams = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJob.prototype.subjectParams = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJob.prototype.templatePath = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJob.prototype.culture = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJob.prototype.campaignId = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJob.prototype.minSendDate = null;


function BorhanMailJobData()
{
}
BorhanMailJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var BorhanMailType
 */
BorhanMailJobData.prototype.mailType = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJobData.prototype.mailPriority = null;

/**
 * 
 *
 * @var BorhanMailJobStatus
 */
BorhanMailJobData.prototype.status = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJobData.prototype.recipientName = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJobData.prototype.recipientEmail = null;

/**
 * kuserId  
 *
 * @var int
 */
BorhanMailJobData.prototype.recipientId = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJobData.prototype.fromName = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJobData.prototype.fromEmail = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJobData.prototype.bodyParams = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJobData.prototype.subjectParams = null;

/**
 * 
 *
 * @var string
 */
BorhanMailJobData.prototype.templatePath = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJobData.prototype.culture = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJobData.prototype.campaignId = null;

/**
 * 
 *
 * @var int
 */
BorhanMailJobData.prototype.minSendDate = null;

/**
 * 
 *
 * @var bool
 */
BorhanMailJobData.prototype.isHtml = null;


function BorhanMailJobFilter()
{
}
BorhanMailJobFilter.prototype = new BorhanBaseJobFilter();

function BorhanPlayableEntry()
{
}
BorhanPlayableEntry.prototype = new BorhanBaseEntry();
/**
 * Number of plays
	 * 
 *
 * @var int
 * @readonly
 */
BorhanPlayableEntry.prototype.plays = null;

/**
 * Number of views
	 * 
 *
 * @var int
 * @readonly
 */
BorhanPlayableEntry.prototype.views = null;

/**
 * The width in pixels
	 * 
 *
 * @var int
 * @readonly
 */
BorhanPlayableEntry.prototype.width = null;

/**
 * The height in pixels
	 * 
 *
 * @var int
 * @readonly
 */
BorhanPlayableEntry.prototype.height = null;

/**
 * The duration in seconds
	 * 
 *
 * @var int
 * @readonly
 */
BorhanPlayableEntry.prototype.duration = null;

/**
 * The duration type (short for 0-4 mins, medium for 4-20 mins, long for 20+ mins)
	 * 
 *
 * @var BorhanDurationType
 * @readonly
 */
BorhanPlayableEntry.prototype.durationType = null;


function BorhanMediaEntry()
{
}
BorhanMediaEntry.prototype = new BorhanPlayableEntry();
/**
 * The media type of the entry
	 * 
 *
 * @var BorhanMediaType
 * @insertonly
 */
BorhanMediaEntry.prototype.mediaType = null;

/**
 * Override the default conversion quality  
	 * 
 *
 * @var string
 * @insertonly
 */
BorhanMediaEntry.prototype.conversionQuality = null;

/**
 * The source type of the entry 
 *
 * @var BorhanSourceType
 * @readonly
 */
BorhanMediaEntry.prototype.sourceType = null;

/**
 * The search provider type used to import this entry
 *
 * @var BorhanSearchProviderType
 * @readonly
 */
BorhanMediaEntry.prototype.searchProviderType = null;

/**
 * The ID of the media in the importing site
 *
 * @var string
 * @readonly
 */
BorhanMediaEntry.prototype.searchProviderId = null;

/**
 * The user name used for credits
 *
 * @var string
 */
BorhanMediaEntry.prototype.creditUserName = null;

/**
 * The URL for credits
 *
 * @var string
 */
BorhanMediaEntry.prototype.creditUrl = null;

/**
 * The media date extracted from EXIF data (For images) as Unix timestamp (In seconds)
 *
 * @var int
 * @readonly
 */
BorhanMediaEntry.prototype.mediaDate = null;

/**
 * The URL used for playback. This is not the download URL.
 *
 * @var string
 * @readonly
 */
BorhanMediaEntry.prototype.dataUrl = null;

/**
 * Comma separated flavor params ids that exists for this media entry
	 * 
 *
 * @var string
 * @readonly
 */
BorhanMediaEntry.prototype.flavorParamsIds = null;


function BorhanPlayableEntryFilter()
{
}
BorhanPlayableEntryFilter.prototype = new BorhanBaseEntryFilter();
/**
 * 
 *
 * @var int
 */
BorhanPlayableEntryFilter.prototype.durationLessThan = null;

/**
 * 
 *
 * @var int
 */
BorhanPlayableEntryFilter.prototype.durationGreaterThan = null;

/**
 * 
 *
 * @var int
 */
BorhanPlayableEntryFilter.prototype.durationLessThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanPlayableEntryFilter.prototype.durationGreaterThanOrEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanPlayableEntryFilter.prototype.durationTypeMatchOr = null;


function BorhanMediaEntryFilter()
{
}
BorhanMediaEntryFilter.prototype = new BorhanPlayableEntryFilter();
/**
 * 
 *
 * @var BorhanMediaType
 */
BorhanMediaEntryFilter.prototype.mediaTypeEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanMediaEntryFilter.prototype.mediaTypeIn = null;

/**
 * 
 *
 * @var int
 */
BorhanMediaEntryFilter.prototype.mediaDateGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanMediaEntryFilter.prototype.mediaDateLessThanOrEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanMediaEntryFilter.prototype.flavorParamsIdsMatchOr = null;

/**
 * 
 *
 * @var string
 */
BorhanMediaEntryFilter.prototype.flavorParamsIdsMatchAnd = null;


function BorhanMediaEntryFilterForPlaylist()
{
}
BorhanMediaEntryFilterForPlaylist.prototype = new BorhanMediaEntryFilter();
/**
 * 
 *
 * @var int
 */
BorhanMediaEntryFilterForPlaylist.prototype.limit = null;


function BorhanMediaListResponse()
{
}
BorhanMediaListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanMediaEntryArray
 * @readonly
 */
BorhanMediaListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanMediaListResponse.prototype.totalCount = null;


function BorhanMixEntry()
{
}
BorhanMixEntry.prototype = new BorhanPlayableEntry();
/**
 * Indicates whether the user has submited a real thumbnail to the mix (Not the one that was generated automaticaly)
	 * 
 *
 * @var bool
 * @readonly
 */
BorhanMixEntry.prototype.hasRealThumbnail = null;

/**
 * The editor type used to edit the metadata
	 * 
 *
 * @var BorhanEditorType
 */
BorhanMixEntry.prototype.editorType = null;

/**
 * The xml data of the mix
 *
 * @var string
 */
BorhanMixEntry.prototype.dataContent = null;


function BorhanMixEntryFilter()
{
}
BorhanMixEntryFilter.prototype = new BorhanPlayableEntryFilter();

function BorhanMixListResponse()
{
}
BorhanMixListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanMixEntryArray
 * @readonly
 */
BorhanMixListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanMixListResponse.prototype.totalCount = null;


function BorhanModerationFlag()
{
}
BorhanModerationFlag.prototype = new BorhanObjectBase();
/**
 * Moderation flag id
 *
 * @var int
 * @readonly
 */
BorhanModerationFlag.prototype.id = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanModerationFlag.prototype.partnerId = null;

/**
 * The user id that added the moderation flag
 *
 * @var string
 * @readonly
 */
BorhanModerationFlag.prototype.userId = null;

/**
 * The type of the moderation flag (entry or user)
 *
 * @var BorhanModerationObjectType
 * @readonly
 */
BorhanModerationFlag.prototype.moderationObjectType = null;

/**
 * If moderation flag is set for entry, this is the flagged entry id
 *
 * @var string
 */
BorhanModerationFlag.prototype.flaggedEntryId = null;

/**
 * If moderation flag is set for user, this is the flagged user id
 *
 * @var string
 */
BorhanModerationFlag.prototype.flaggedUserId = null;

/**
 * The moderation flag status
 *
 * @var BorhanModerationFlagStatus
 * @readonly
 */
BorhanModerationFlag.prototype.status = null;

/**
 * The comment that was added to the flag
 *
 * @var string
 */
BorhanModerationFlag.prototype.comments = null;

/**
 * 
 *
 * @var BorhanModerationFlagType
 */
BorhanModerationFlag.prototype.flagType = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanModerationFlag.prototype.createdAt = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanModerationFlag.prototype.updatedAt = null;


function BorhanModerationFlagListResponse()
{
}
BorhanModerationFlagListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanModerationFlagArray
 * @readonly
 */
BorhanModerationFlagListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanModerationFlagListResponse.prototype.totalCount = null;


function BorhanNotification()
{
}
BorhanNotification.prototype = new BorhanBaseJob();
/**
 * 
 *
 * @var string
 */
BorhanNotification.prototype.puserId = null;

/**
 * 
 *
 * @var BorhanNotificationType
 */
BorhanNotification.prototype.type = null;

/**
 * 
 *
 * @var string
 */
BorhanNotification.prototype.objectId = null;

/**
 * 
 *
 * @var BorhanNotificationStatus
 */
BorhanNotification.prototype.status = null;

/**
 * 
 *
 * @var string
 */
BorhanNotification.prototype.notificationData = null;

/**
 * 
 *
 * @var int
 */
BorhanNotification.prototype.numberOfAttempts = null;

/**
 * 
 *
 * @var string
 */
BorhanNotification.prototype.notificationResult = null;

/**
 * 
 *
 * @var BorhanNotificationObjectType
 */
BorhanNotification.prototype.objType = null;

function BorhanNotificationJobData()
{
}
BorhanNotificationJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var string
 */
BorhanNotificationJobData.prototype.userId = null;

/**
 * 
 *
 * @var BorhanNotificationType
 */
BorhanNotificationJobData.prototype.type = null;

/**
 * 
 *
 * @var string
 */
BorhanNotificationJobData.prototype.typeAsString = null;

/**
 * 
 *
 * @var string
 */
BorhanNotificationJobData.prototype.objectId = null;

/**
 * 
 *
 * @var BorhanNotificationStatus
 */
BorhanNotificationJobData.prototype.status = null;

/**
 * 
 *
 * @var string
 */
BorhanNotificationJobData.prototype.data = null;

/**
 * 
 *
 * @var int
 */
BorhanNotificationJobData.prototype.numberOfAttempts = null;

/**
 * 
 *
 * @var string
 */
BorhanNotificationJobData.prototype.notificationResult = null;

/**
 * 
 *
 * @var BorhanNotificationObjectType
 */
BorhanNotificationJobData.prototype.objType = null;


function BorhanPartner()
{
}
BorhanPartner.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanPartner.prototype.id = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.name = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.website = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.notificationUrl = null;

/**
 * 
 *
 * @var int
 */
BorhanPartner.prototype.appearInSearch = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanPartner.prototype.createdAt = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.adminName = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.adminEmail = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.description = null;

/**
 * 
 *
 * @var BorhanCommercialUseType
 */
BorhanPartner.prototype.commercialUse = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.landingPage = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.userLandingPage = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.contentCategories = null;

/**
 * 
 *
 * @var BorhanPartnerType
 */
BorhanPartner.prototype.type = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.phone = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.describeYourself = null;

/**
 * 
 *
 * @var bool
 */
BorhanPartner.prototype.adultContent = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.defConversionProfileType = null;

/**
 * 
 *
 * @var int
 */
BorhanPartner.prototype.notify = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanPartner.prototype.status = null;

/**
 * 
 *
 * @var int
 */
BorhanPartner.prototype.allowQuickEdit = null;

/**
 * 
 *
 * @var int
 */
BorhanPartner.prototype.mergeEntryLists = null;

/**
 * 
 *
 * @var string
 */
BorhanPartner.prototype.notificationsConfig = null;

/**
 * 
 *
 * @var int
 */
BorhanPartner.prototype.maxUploadSize = null;

/**
 * readonly
 *
 * @var int
 */
BorhanPartner.prototype.partnerPackage = null;

/**
 * readonly
 *
 * @var string
 */
BorhanPartner.prototype.secret = null;

/**
 * readonly
 *
 * @var string
 */
BorhanPartner.prototype.adminSecret = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanPartner.prototype.cmsPassword = null;

/**
 * readonly
 *
 * @var int
 */
BorhanPartner.prototype.allowMultiNotification = null;


function BorhanPartnerFilter()
{
}
BorhanPartnerFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var string
 */
BorhanPartnerFilter.prototype.nameLike = null;

/**
 * 
 *
 * @var string
 */
BorhanPartnerFilter.prototype.nameMultiLikeOr = null;

/**
 * 
 *
 * @var string
 */
BorhanPartnerFilter.prototype.nameMultiLikeAnd = null;

/**
 * 
 *
 * @var string
 */
BorhanPartnerFilter.prototype.nameEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanPartnerFilter.prototype.statusEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanPartnerFilter.prototype.statusIn = null;


function BorhanPartnerUsage()
{
}
BorhanPartnerUsage.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var float
 * @readonly
 */
BorhanPartnerUsage.prototype.hostingGB = null;

/**
 * 
 *
 * @var float
 * @readonly
 */
BorhanPartnerUsage.prototype.Percent = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanPartnerUsage.prototype.packageBW = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanPartnerUsage.prototype.usageGB = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanPartnerUsage.prototype.reachedLimitDate = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanPartnerUsage.prototype.usageGraph = null;


function BorhanPlaylist()
{
}
BorhanPlaylist.prototype = new BorhanBaseEntry();
/**
 * Content of the playlist - 
	 * XML if the playlistType is dynamic 
	 * text if the playlistType is static 
	 * url if the playlistType is mRss 
 *
 * @var string
 */
BorhanPlaylist.prototype.playlistContent = null;

/**
 * 
 *
 * @var BorhanMediaEntryFilterForPlaylistArray
 */
BorhanPlaylist.prototype.filters = null;

/**
 * 
 *
 * @var int
 */
BorhanPlaylist.prototype.totalResults = null;

/**
 * Type of playlist  
 *
 * @var BorhanPlaylistType
 */
BorhanPlaylist.prototype.playlistType = null;

/**
 * Number of plays
 *
 * @var int
 * @readonly
 */
BorhanPlaylist.prototype.plays = null;

/**
 * Number of views
 *
 * @var int
 * @readonly
 */
BorhanPlaylist.prototype.views = null;

/**
 * The duration in seconds
 *
 * @var int
 * @readonly
 */
BorhanPlaylist.prototype.duration = null;


function BorhanPlaylistFilter()
{
}
BorhanPlaylistFilter.prototype = new BorhanBaseEntryFilter();

function BorhanPlaylistListResponse()
{
}
BorhanPlaylistListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanPlaylistArray
 * @readonly
 */
BorhanPlaylistListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanPlaylistListResponse.prototype.totalCount = null;


function BorhanPostConvertJobData()
{
}
BorhanPostConvertJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var string
 */
BorhanPostConvertJobData.prototype.srcFileSyncLocalPath = null;

/**
 * 
 *
 * @var string
 */
BorhanPostConvertJobData.prototype.flavorAssetId = null;

/**
 * Indicates if a thumbnail should be created
	 * 
 *
 * @var bool
 */
BorhanPostConvertJobData.prototype.createThumb = null;

/**
 * The path of the created thumbnail
	 * 
 *
 * @var string
 */
BorhanPostConvertJobData.prototype.thumbPath = null;

/**
 * The position of the thumbnail in the media file
	 * 
 *
 * @var int
 */
BorhanPostConvertJobData.prototype.thumbOffset = null;

/**
 * The height of the movie, will be used to comapare if this thumbnail is the best we can have
	 * 
 *
 * @var int
 */
BorhanPostConvertJobData.prototype.thumbHeight = null;

/**
 * The bit rate of the movie, will be used to comapare if this thumbnail is the best we can have
	 * 
 *
 * @var int
 */
BorhanPostConvertJobData.prototype.thumbBitrate = null;

/**
 * 
 *
 * @var int
 */
BorhanPostConvertJobData.prototype.flavorParamsOutputId = null;


function BorhanSessionRestriction()
{
}
BorhanSessionRestriction.prototype = new BorhanBaseRestriction();

function BorhanPreviewRestriction()
{
}
BorhanPreviewRestriction.prototype = new BorhanSessionRestriction();
/**
 * The preview restriction length 
	 * 
 *
 * @var int
 */
BorhanPreviewRestriction.prototype.previewLength = null;


function BorhanPullJobData()
{
}
BorhanPullJobData.prototype = new BorhanJobData();
/**
 * 
 *
 * @var string
 */
BorhanPullJobData.prototype.srcFileUrl = null;

/**
 * 
 *
 * @var string
 */
BorhanPullJobData.prototype.destFileLocalPath = null;


function BorhanRemoteConvertJobData()
{
}
BorhanRemoteConvertJobData.prototype = new BorhanConvartableJobData();
/**
 * 
 *
 * @var string
 */
BorhanRemoteConvertJobData.prototype.srcFileUrl = null;

/**
 * Should be set by the API
	 * 
 *
 * @var string
 */
BorhanRemoteConvertJobData.prototype.destFileUrl = null;


function BorhanReportGraph()
{
}
BorhanReportGraph.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanReportGraph.prototype.id = null;

/**
 * 
 *
 * @var string
 */
BorhanReportGraph.prototype.data = null;


function BorhanReportInputFilter()
{
}
BorhanReportInputFilter.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var int
 */
BorhanReportInputFilter.prototype.fromDate = null;

/**
 * 
 *
 * @var int
 */
BorhanReportInputFilter.prototype.toDate = null;

/**
 * 
 *
 * @var string
 */
BorhanReportInputFilter.prototype.keywords = null;

/**
 * 
 *
 * @var bool
 */
BorhanReportInputFilter.prototype.searchInTags = null;

/**
 * 
 *
 * @var bool
 */
BorhanReportInputFilter.prototype.searchInAdminTags = null;

/**
 * 
 *
 * @var string
 */
BorhanReportInputFilter.prototype.categories = null;


function BorhanReportTable()
{
}
BorhanReportTable.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanReportTable.prototype.header = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanReportTable.prototype.data = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanReportTable.prototype.totalCount = null;


function BorhanReportTotal()
{
}
BorhanReportTotal.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanReportTotal.prototype.header = null;

/**
 * 
 *
 * @var string
 */
BorhanReportTotal.prototype.data = null;


function BorhanSearch()
{
}
BorhanSearch.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanSearch.prototype.keyWords = null;

/**
 * 
 *
 * @var BorhanSearchProviderType
 */
BorhanSearch.prototype.searchSource = null;

/**
 * 
 *
 * @var BorhanMediaType
 */
BorhanSearch.prototype.mediaType = null;

/**
 * Use this field to pass dynamic data for searching
	 * For example - if you set this field to "mymovies_$partner_id"
	 * The $partner_id will be automatically replcaed with your real partner Id
	 * 
 *
 * @var string
 */
BorhanSearch.prototype.extraData = null;

/**
 * 
 *
 * @var string
 */
BorhanSearch.prototype.authData = null;


function BorhanSearchAuthData()
{
}
BorhanSearchAuthData.prototype = new BorhanObjectBase();
/**
 * The authentication data that further should be used for search
	 * 
 *
 * @var string
 */
BorhanSearchAuthData.prototype.authData = null;

/**
 * Login URL when user need to sign-in and authorize the search
 *
 * @var string
 */
BorhanSearchAuthData.prototype.loginUrl = null;

/**
 * Information when there was an error
 *
 * @var string
 */
BorhanSearchAuthData.prototype.message = null;


function BorhanSearchResult()
{
}
BorhanSearchResult.prototype = new BorhanSearch();
/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.id = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.title = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.thumbUrl = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.description = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.tags = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.url = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.sourceLink = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.credit = null;

/**
 * 
 *
 * @var BorhanLicenseType
 */
BorhanSearchResult.prototype.licenseType = null;

/**
 * 
 *
 * @var string
 */
BorhanSearchResult.prototype.flashPlaybackType = null;


function BorhanSearchResultResponse()
{
}
BorhanSearchResultResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanSearchResultArray
 * @readonly
 */
BorhanSearchResultResponse.prototype.objects = null;

/**
 * 
 *
 * @var bool
 * @readonly
 */
BorhanSearchResultResponse.prototype.needMediaInfo = null;


function BorhanSiteRestriction()
{
}
BorhanSiteRestriction.prototype = new BorhanBaseRestriction();
/**
 * The site restriction type (allow or deny)
	 * 
 *
 * @var BorhanSiteRestrictionType
 */
BorhanSiteRestriction.prototype.siteRestrictionType = null;

/**
 * Comma separated list of sites (domains) to allow or deny
	 * 
 *
 * @var string
 */
BorhanSiteRestriction.prototype.siteList = null;


function BorhanStartWidgetSessionResponse()
{
}
BorhanStartWidgetSessionResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanStartWidgetSessionResponse.prototype.partnerId = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanStartWidgetSessionResponse.prototype.ks = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanStartWidgetSessionResponse.prototype.userId = null;


function BorhanStatsEvent()
{
}
BorhanStatsEvent.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanStatsEvent.prototype.clientVer = null;

/**
 * 
 *
 * @var BorhanStatsEventType
 */
BorhanStatsEvent.prototype.eventType = null;

/**
 * the client's timestamp of this event
	 * 
 *
 * @var float
 */
BorhanStatsEvent.prototype.eventTimestamp = null;

/**
 * a unique string generated by the client that will represent the client-side session: the primary component will pass it on to other components that sprout from it
 *
 * @var string
 */
BorhanStatsEvent.prototype.sessionId = null;

/**
 * 
 *
 * @var int
 */
BorhanStatsEvent.prototype.partnerId = null;

/**
 * 
 *
 * @var string
 */
BorhanStatsEvent.prototype.entryId = null;

/**
 * the UV cookie - creates in the operational system and should be passed on ofr every event 
 *
 * @var string
 */
BorhanStatsEvent.prototype.uniqueViewer = null;

/**
 * 
 *
 * @var string
 */
BorhanStatsEvent.prototype.widgetId = null;

/**
 * 
 *
 * @var int
 */
BorhanStatsEvent.prototype.uiconfId = null;

/**
 * the partner's user id 
 *
 * @var string
 */
BorhanStatsEvent.prototype.userId = null;

/**
 * the timestamp along the video when the event happend 
 *
 * @var int
 */
BorhanStatsEvent.prototype.currentPoint = null;

/**
 * the duration of the video in milliseconds - will make it much faster than quering the db for each entry 
 *
 * @var int
 */
BorhanStatsEvent.prototype.duration = null;

/**
 * will be retrieved from the request of the user 
 *
 * @var string
 * @readonly
 */
BorhanStatsEvent.prototype.userIp = null;

/**
 * the time in milliseconds the event took
 *
 * @var int
 */
BorhanStatsEvent.prototype.processDuration = null;

/**
 * the id of the GUI control - will be used in the future to better understand what the user clicked
 *
 * @var string
 */
BorhanStatsEvent.prototype.controlId = null;

/**
 * true if the user ever used seek in this session 
 *
 * @var bool
 */
BorhanStatsEvent.prototype.seek = null;

/**
 * timestamp of the new point on the timeline of the video after the user seeks 
 *
 * @var int
 */
BorhanStatsEvent.prototype.newPoint = null;

/**
 * the referrer of the client
 *
 * @var string
 */
BorhanStatsEvent.prototype.referrer = null;

/**
 * will indicate if the event is thrown for the first video in the session
 *
 * @var bool
 */
BorhanStatsEvent.prototype.isFirstInSession = null;


function BorhanStatsBmcEvent()
{
}
BorhanStatsBmcEvent.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanStatsBmcEvent.prototype.clientVer = null;

/**
 * 
 *
 * @var string
 */
BorhanStatsBmcEvent.prototype.bmcEventActionPath = null;

/**
 * 
 *
 * @var BorhanStatsBmcEventType
 */
BorhanStatsBmcEvent.prototype.bmcEventType = null;

/**
 * the client's timestamp of this event
	 * 
 *
 * @var float
 */
BorhanStatsBmcEvent.prototype.eventTimestamp = null;

/**
 * a unique string generated by the client that will represent the client-side session: the primary component will pass it on to other components that sprout from it
 *
 * @var string
 */
BorhanStatsBmcEvent.prototype.sessionId = null;

/**
 * 
 *
 * @var int
 */
BorhanStatsBmcEvent.prototype.partnerId = null;

/**
 * 
 *
 * @var string
 */
BorhanStatsBmcEvent.prototype.entryId = null;

/**
 * 
 *
 * @var string
 */
BorhanStatsBmcEvent.prototype.widgetId = null;

/**
 * 
 *
 * @var int
 */
BorhanStatsBmcEvent.prototype.uiconfId = null;

/**
 * the partner's user id 
 *
 * @var string
 */
BorhanStatsBmcEvent.prototype.userId = null;

/**
 * will be retrieved from the request of the user 
 *
 * @var string
 * @readonly
 */
BorhanStatsBmcEvent.prototype.userIp = null;


function BorhanSyndicationFeedEntryCount()
{
}
BorhanSyndicationFeedEntryCount.prototype = new BorhanObjectBase();
/**
 * the total count of entries that should appear in the feed without flavor filtering
 *
 * @var int
 */
BorhanSyndicationFeedEntryCount.prototype.totalEntryCount = null;

/**
 * count of entries that will appear in the feed (including all relevant filters)
 *
 * @var int
 */
BorhanSyndicationFeedEntryCount.prototype.actualEntryCount = null;

/**
 * count of entries that requires transcoding in order to be included in feed
 *
 * @var int
 */
BorhanSyndicationFeedEntryCount.prototype.requireTranscodingCount = null;


function BorhanSystemUser()
{
}
BorhanSystemUser.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanSystemUser.prototype.id = null;

/**
 * 
 *
 * @var string
 */
BorhanSystemUser.prototype.email = null;

/**
 * 
 *
 * @var string
 */
BorhanSystemUser.prototype.firstName = null;

/**
 * 
 *
 * @var string
 */
BorhanSystemUser.prototype.lastName = null;

/**
 * 
 *
 * @var string
 */
BorhanSystemUser.prototype.password = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanSystemUser.prototype.createdBy = null;

/**
 * 
 *
 * @var BorhanSystemUserStatus
 */
BorhanSystemUser.prototype.status = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanSystemUser.prototype.statusUpdatedAt = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanSystemUser.prototype.createdAt = null;


function BorhanSystemUserFilter()
{
}
BorhanSystemUserFilter.prototype = new BorhanFilter();

function BorhanSystemUserListResponse()
{
}
BorhanSystemUserListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanSystemUserArray
 * @readonly
 */
BorhanSystemUserListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanSystemUserListResponse.prototype.totalCount = null;


function BorhanTubeMogulSyndicationFeed()
{
}
BorhanTubeMogulSyndicationFeed.prototype = new BorhanBaseSyndicationFeed();
/**
 * 
 *
 * @var BorhanTubeMogulSyndicationFeedCategories
 * @readonly
 */
BorhanTubeMogulSyndicationFeed.prototype.category = null;


function BorhanTubeMogulSyndicationFeedFilter()
{
}
BorhanTubeMogulSyndicationFeedFilter.prototype = new BorhanBaseSyndicationFeedFilter();

function BorhanUiConf()
{
}
BorhanUiConf.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanUiConf.prototype.id = null;

/**
 * Name of the uiConf, this is not a primary key
 *
 * @var string
 */
BorhanUiConf.prototype.name = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.description = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanUiConf.prototype.partnerId = null;

/**
 * 
 *
 * @var BorhanUiConfObjType
 */
BorhanUiConf.prototype.objType = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanUiConf.prototype.objTypeAsString = null;

/**
 * 
 *
 * @var int
 */
BorhanUiConf.prototype.width = null;

/**
 * 
 *
 * @var int
 */
BorhanUiConf.prototype.height = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.htmlParams = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.swfUrl = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanUiConf.prototype.confFilePath = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.confFile = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.confFileFeatures = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.confVars = null;

/**
 * 
 *
 * @var bool
 */
BorhanUiConf.prototype.useCdn = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.tags = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConf.prototype.swfUrlVersion = null;

/**
 * Entry creation date as Unix timestamp (In seconds)
 *
 * @var int
 * @readonly
 */
BorhanUiConf.prototype.createdAt = null;

/**
 * Entry creation date as Unix timestamp (In seconds)
 *
 * @var int
 * @readonly
 */
BorhanUiConf.prototype.updatedAt = null;

/**
 * 
 *
 * @var BorhanUiConfCreationMode
 */
BorhanUiConf.prototype.creationMode = null;


function BorhanUiConfFilter()
{
}
BorhanUiConfFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var int
 */
BorhanUiConfFilter.prototype.idEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConfFilter.prototype.idIn = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConfFilter.prototype.nameLike = null;

/**
 * 
 *
 * @var BorhanUiConfObjType
 */
BorhanUiConfFilter.prototype.objTypeEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConfFilter.prototype.tagsMultiLikeOr = null;

/**
 * 
 *
 * @var string
 */
BorhanUiConfFilter.prototype.tagsMultiLikeAnd = null;

/**
 * 
 *
 * @var int
 */
BorhanUiConfFilter.prototype.createdAtGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanUiConfFilter.prototype.createdAtLessThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanUiConfFilter.prototype.updatedAtGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanUiConfFilter.prototype.updatedAtLessThanOrEqual = null;


function BorhanUiConfListResponse()
{
}
BorhanUiConfListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanUiConfArray
 * @readonly
 */
BorhanUiConfListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanUiConfListResponse.prototype.totalCount = null;


function BorhanUploadResponse()
{
}
BorhanUploadResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanUploadResponse.prototype.uploadTokenId = null;

/**
 * 
 *
 * @var int
 */
BorhanUploadResponse.prototype.fileSize = null;

/**
 * 
 *
 * @var BorhanUploadErrorCode
 */
BorhanUploadResponse.prototype.errorCode = null;

/**
 * 
 *
 * @var string
 */
BorhanUploadResponse.prototype.errorDescription = null;


function BorhanUser()
{
}
BorhanUser.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.id = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanUser.prototype.partnerId = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.screenName = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.fullName = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.email = null;

/**
 * 
 *
 * @var int
 */
BorhanUser.prototype.dateOfBirth = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.country = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.state = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.city = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.zip = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.thumbnailUrl = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.description = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.tags = null;

/**
 * Admin tags can be updated only by using an admin session
 *
 * @var string
 */
BorhanUser.prototype.adminTags = null;

/**
 * 
 *
 * @var BorhanGender
 */
BorhanUser.prototype.gender = null;

/**
 * 
 *
 * @var BorhanUserStatus
 */
BorhanUser.prototype.status = null;

/**
 * Creation date as Unix timestamp (In seconds)
 *
 * @var int
 * @readonly
 */
BorhanUser.prototype.createdAt = null;

/**
 * Last update date as Unix timestamp (In seconds)
 *
 * @var int
 * @readonly
 */
BorhanUser.prototype.updatedAt = null;

/**
 * Can be used to store various partner related data as a string 
 *
 * @var string
 */
BorhanUser.prototype.partnerData = null;

/**
 * 
 *
 * @var int
 */
BorhanUser.prototype.indexedPartnerDataInt = null;

/**
 * 
 *
 * @var string
 */
BorhanUser.prototype.indexedPartnerDataString = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanUser.prototype.storageSize = null;


function BorhanUserFilter()
{
}
BorhanUserFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.idEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.idIn = null;

/**
 * 
 *
 * @var int
 */
BorhanUserFilter.prototype.partnerIdEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.screenNameLike = null;

/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.screenNameStartsWith = null;

/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.emailLike = null;

/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.emailStartsWith = null;

/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.tagsMultiLikeOr = null;

/**
 * 
 *
 * @var string
 */
BorhanUserFilter.prototype.tagsMultiLikeAnd = null;

/**
 * 
 *
 * @var int
 */
BorhanUserFilter.prototype.createdAtGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanUserFilter.prototype.createdAtLessThanOrEqual = null;


function BorhanUserListResponse()
{
}
BorhanUserListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanUserArray
 * @readonly
 */
BorhanUserListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanUserListResponse.prototype.totalCount = null;


function BorhanWidget()
{
}
BorhanWidget.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanWidget.prototype.id = null;

/**
 * 
 *
 * @var string
 */
BorhanWidget.prototype.sourceWidgetId = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanWidget.prototype.rootWidgetId = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanWidget.prototype.partnerId = null;

/**
 * 
 *
 * @var string
 */
BorhanWidget.prototype.entryId = null;

/**
 * 
 *
 * @var int
 */
BorhanWidget.prototype.uiConfId = null;

/**
 * 
 *
 * @var BorhanWidgetSecurityType
 */
BorhanWidget.prototype.securityType = null;

/**
 * 
 *
 * @var int
 */
BorhanWidget.prototype.securityPolicy = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanWidget.prototype.createdAt = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanWidget.prototype.updatedAt = null;

/**
 * Can be used to store various partner related data as a string 
 *
 * @var string
 */
BorhanWidget.prototype.partnerData = null;

/**
 * 
 *
 * @var string
 * @readonly
 */
BorhanWidget.prototype.widgetHTML = null;


function BorhanWidgetFilter()
{
}
BorhanWidgetFilter.prototype = new BorhanFilter();
/**
 * 
 *
 * @var string
 */
BorhanWidgetFilter.prototype.idEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanWidgetFilter.prototype.idIn = null;

/**
 * 
 *
 * @var string
 */
BorhanWidgetFilter.prototype.sourceWidgetIdEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanWidgetFilter.prototype.rootWidgetIdEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanWidgetFilter.prototype.partnerIdEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanWidgetFilter.prototype.entryIdEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanWidgetFilter.prototype.uiConfIdEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanWidgetFilter.prototype.createdAtGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanWidgetFilter.prototype.createdAtLessThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanWidgetFilter.prototype.updatedAtGreaterThanOrEqual = null;

/**
 * 
 *
 * @var int
 */
BorhanWidgetFilter.prototype.updatedAtLessThanOrEqual = null;

/**
 * 
 *
 * @var string
 */
BorhanWidgetFilter.prototype.partnerDataLike = null;


function BorhanWidgetListResponse()
{
}
BorhanWidgetListResponse.prototype = new BorhanObjectBase();
/**
 * 
 *
 * @var BorhanWidgetArray
 * @readonly
 */
BorhanWidgetListResponse.prototype.objects = null;

/**
 * 
 *
 * @var int
 * @readonly
 */
BorhanWidgetListResponse.prototype.totalCount = null;


function BorhanYahooSyndicationFeed()
{
}
BorhanYahooSyndicationFeed.prototype = new BorhanBaseSyndicationFeed();
/**
 * 
 *
 * @var BorhanYahooSyndicationFeedCategories
 * @readonly
 */
BorhanYahooSyndicationFeed.prototype.category = null;

/**
 * 
 *
 * @var BorhanYahooSyndicationFeedAdultValues
 */
BorhanYahooSyndicationFeed.prototype.adultContent = null;

/**
 * feed description
	 * 
 *
 * @var string
 */
BorhanYahooSyndicationFeed.prototype.feedDescription = null;

/**
 * feed landing page (i.e publisher website)
	 * 
 *
 * @var string
 */
BorhanYahooSyndicationFeed.prototype.feedLandingPage = null;


function BorhanYahooSyndicationFeedFilter()
{
}
BorhanYahooSyndicationFeedFilter.prototype = new BorhanBaseSyndicationFeedFilter();


function BorhanAccessControlService(client)
{
	this.init(client);
}

BorhanAccessControlService.prototype = new BorhanServiceBase();

BorhanAccessControlService.prototype.add = function(callback, accessControl)
{

	kparams = new Object();
	this.client.addParam(kparams, "accessControl", accessControl.toParams());
	this.client.queueServiceActionCall("accesscontrol", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanAccessControlService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("accesscontrol", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanAccessControlService.prototype.update = function(callback, id, accessControl)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "accessControl", accessControl.toParams());
	this.client.queueServiceActionCall("accesscontrol", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanAccessControlService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("accesscontrol", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanAccessControlService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("accesscontrol", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanAdminconsoleService(client)
{
	this.init(client);
}

BorhanAdminconsoleService.prototype = new BorhanServiceBase();

BorhanAdminconsoleService.prototype.listBatchJobs = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", toParams(filter));
	if (pager != null)
		this.client.addParam(kparams, "pager", toParams(pager));
	this.client.queueServiceActionCall("adminconsole", "listBatchJobs", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanAdminUserService(client)
{
	this.init(client);
}

BorhanAdminUserService.prototype = new BorhanServiceBase();

BorhanAdminUserService.prototype.updatePassword = function(callback, email, password, newEmail, newPassword)
{
	if(!newEmail)
		newEmail = "";
	if(!newPassword)
		newPassword = "";

	kparams = new Object();
	this.client.addParam(kparams, "email", email);
	this.client.addParam(kparams, "password", password);
	this.client.addParam(kparams, "newEmail", newEmail);
	this.client.addParam(kparams, "newPassword", newPassword);
	this.client.queueServiceActionCall("adminuser", "updatePassword", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanAdminUserService.prototype.resetPassword = function(callback, email)
{

	kparams = new Object();
	this.client.addParam(kparams, "email", email);
	this.client.queueServiceActionCall("adminuser", "resetPassword", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanAdminUserService.prototype.login = function(callback, email, password)
{

	kparams = new Object();
	this.client.addParam(kparams, "email", email);
	this.client.addParam(kparams, "password", password);
	this.client.queueServiceActionCall("adminuser", "login", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanBaseEntryService(client)
{
	this.init(client);
}

BorhanBaseEntryService.prototype = new BorhanServiceBase();

BorhanBaseEntryService.prototype.addFromUploadedFile = function(callback, entry, uploadTokenId, type)
{
	if(!type)
		type = -1;

	kparams = new Object();
	this.client.addParam(kparams, "entry", entry.toParams());
	this.client.addParam(kparams, "uploadTokenId", uploadTokenId);
	this.client.addParam(kparams, "type", type);
	this.client.queueServiceActionCall("baseentry", "addFromUploadedFile", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.get = function(callback, entryId, version)
{
	if(!version)
		version = -1;

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "version", version);
	this.client.queueServiceActionCall("baseentry", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.update = function(callback, entryId, baseEntry)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "baseEntry", baseEntry.toParams());
	this.client.queueServiceActionCall("baseentry", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.getByIds = function(callback, entryIds)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryIds", entryIds);
	this.client.queueServiceActionCall("baseentry", "getByIds", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.delete = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("baseentry", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("baseentry", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.count = function(callback, filter)
{
	if(!filter)
		filter = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	this.client.queueServiceActionCall("baseentry", "count", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.upload = function(callback, fileData)
{

	kparams = new Object();
	kfiles = new Object();
	this.client.addParam(kfiles, "fileData", fileData);
	this.client.queueServiceActionCall("baseentry", "upload", kparams, kfiles);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.updateThumbnailJpeg = function(callback, entryId, fileData)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	kfiles = new Object();
	this.client.addParam(kfiles, "fileData", fileData);
	this.client.queueServiceActionCall("baseentry", "updateThumbnailJpeg", kparams, kfiles);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.updateThumbnailFromUrl = function(callback, entryId, url)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "url", url);
	this.client.queueServiceActionCall("baseentry", "updateThumbnailFromUrl", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.updateThumbnailFromSourceEntry = function(callback, entryId, sourceEntryId, timeOffset)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "sourceEntryId", sourceEntryId);
	this.client.addParam(kparams, "timeOffset", timeOffset);
	this.client.queueServiceActionCall("baseentry", "updateThumbnailFromSourceEntry", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.flag = function(callback, moderationFlag)
{

	kparams = new Object();
	this.client.addParam(kparams, "moderationFlag", moderationFlag.toParams());
	this.client.queueServiceActionCall("baseentry", "flag", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.reject = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("baseentry", "reject", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.approve = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("baseentry", "approve", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.listFlags = function(callback, entryId, pager)
{
	if(!pager)
		pager = null;

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("baseentry", "listFlags", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.anonymousRank = function(callback, entryId, rank)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "rank", rank);
	this.client.queueServiceActionCall("baseentry", "anonymousRank", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBaseEntryService.prototype.getExtraData = function(callback, entryId, extraDataParams)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "extraDataParams", extraDataParams.toParams());
	this.client.queueServiceActionCall("baseentry", "getExtraData", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanBulkUploadService(client)
{
	this.init(client);
}

BorhanBulkUploadService.prototype = new BorhanServiceBase();

BorhanBulkUploadService.prototype.add = function(callback, conversionProfileId, csvFileData)
{

	kparams = new Object();
	this.client.addParam(kparams, "conversionProfileId", conversionProfileId);
	kfiles = new Object();
	this.client.addParam(kfiles, "csvFileData", csvFileData);
	this.client.queueServiceActionCall("bulkupload", "add", kparams, kfiles);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBulkUploadService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("bulkupload", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanBulkUploadService.prototype.listAction = function(callback, pager)
{
	if(!pager)
		pager = null;

	kparams = new Object();
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("bulkupload", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanCategoryService(client)
{
	this.init(client);
}

BorhanCategoryService.prototype = new BorhanServiceBase();

BorhanCategoryService.prototype.add = function(callback, category)
{

	kparams = new Object();
	this.client.addParam(kparams, "category", category.toParams());
	this.client.queueServiceActionCall("category", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanCategoryService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("category", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanCategoryService.prototype.update = function(callback, id, category)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "category", category.toParams());
	this.client.queueServiceActionCall("category", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanCategoryService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("category", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanCategoryService.prototype.listAction = function(callback, filter)
{
	if(!filter)
		filter = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	this.client.queueServiceActionCall("category", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanConversionProfileService(client)
{
	this.init(client);
}

BorhanConversionProfileService.prototype = new BorhanServiceBase();

BorhanConversionProfileService.prototype.add = function(callback, conversionProfile)
{

	kparams = new Object();
	this.client.addParam(kparams, "conversionProfile", conversionProfile.toParams());
	this.client.queueServiceActionCall("conversionprofile", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanConversionProfileService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("conversionprofile", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanConversionProfileService.prototype.update = function(callback, id, conversionProfile)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "conversionProfile", conversionProfile.toParams());
	this.client.queueServiceActionCall("conversionprofile", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanConversionProfileService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("conversionprofile", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanConversionProfileService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("conversionprofile", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanDataService(client)
{
	this.init(client);
}

BorhanDataService.prototype = new BorhanServiceBase();

BorhanDataService.prototype.add = function(callback, dataEntry)
{

	kparams = new Object();
	this.client.addParam(kparams, "dataEntry", dataEntry.toParams());
	this.client.queueServiceActionCall("data", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanDataService.prototype.get = function(callback, entryId, version)
{
	if(!version)
		version = -1;

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "version", version);
	this.client.queueServiceActionCall("data", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanDataService.prototype.update = function(callback, entryId, documentEntry)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "documentEntry", documentEntry.toParams());
	this.client.queueServiceActionCall("data", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanDataService.prototype.delete = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("data", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanDataService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("data", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanFlavorAssetService(client)
{
	this.init(client);
}

BorhanFlavorAssetService.prototype = new BorhanServiceBase();

BorhanFlavorAssetService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("flavorasset", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorAssetService.prototype.getByEntryId = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("flavorasset", "getByEntryId", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorAssetService.prototype.getWebPlayableByEntryId = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("flavorasset", "getWebPlayableByEntryId", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorAssetService.prototype.convert = function(callback, entryId, flavorParamsId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "flavorParamsId", flavorParamsId);
	this.client.queueServiceActionCall("flavorasset", "convert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorAssetService.prototype.reconvert = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("flavorasset", "reconvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorAssetService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("flavorasset", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorAssetService.prototype.getDownloadUrl = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("flavorasset", "getDownloadUrl", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorAssetService.prototype.getFlavorAssetsWithParams = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("flavorasset", "getFlavorAssetsWithParams", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanFlavorParamsService(client)
{
	this.init(client);
}

BorhanFlavorParamsService.prototype = new BorhanServiceBase();

BorhanFlavorParamsService.prototype.add = function(callback, flavorParams)
{

	kparams = new Object();
	this.client.addParam(kparams, "flavorParams", flavorParams.toParams());
	this.client.queueServiceActionCall("flavorparams", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorParamsService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("flavorparams", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorParamsService.prototype.update = function(callback, id, flavorParams)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "flavorParams", flavorParams.toParams());
	this.client.queueServiceActionCall("flavorparams", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorParamsService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("flavorparams", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorParamsService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("flavorparams", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanFlavorParamsService.prototype.getByConversionProfileId = function(callback, conversionProfileId)
{

	kparams = new Object();
	this.client.addParam(kparams, "conversionProfileId", conversionProfileId);
	this.client.queueServiceActionCall("flavorparams", "getByConversionProfileId", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanJobsService(client)
{
	this.init(client);
}

BorhanJobsService.prototype = new BorhanServiceBase();

BorhanJobsService.prototype.getImportStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getImportStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteImport = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteImport", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortImport = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortImport", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryImport = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryImport", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getBulkUploadStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getBulkUploadStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteBulkUpload = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteBulkUpload", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortBulkUpload = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortBulkUpload", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryBulkUpload = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryBulkUpload", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getConvertStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getConvertStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getConvertProfileStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getConvertProfileStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getRemoteConvertStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getRemoteConvertStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteRemoteConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteRemoteConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortRemoteConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortRemoteConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryRemoteConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryRemoteConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteConvertProfile = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteConvertProfile", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortConvertProfile = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortConvertProfile", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryConvertProfile = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryConvertProfile", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getPostConvertStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getPostConvertStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deletePostConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deletePostConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortPostConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortPostConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryPostConvert = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryPostConvert", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getPullStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getPullStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deletePull = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deletePull", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortPull = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortPull", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryPull = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryPull", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getExtractMediaStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getExtractMediaStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteExtractMedia = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteExtractMedia", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortExtractMedia = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortExtractMedia", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryExtractMedia = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryExtractMedia", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getNotificationStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getNotificationStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteNotification = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteNotification", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortNotification = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortNotification", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryNotification = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryNotification", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getMailStatus = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "getMailStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteMail = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "deleteMail", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortMail = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "abortMail", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryMail = function(callback, jobId)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.queueServiceActionCall("jobs", "retryMail", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.addMailJob = function(callback, mailJobData)
{

	kparams = new Object();
	this.client.addParam(kparams, "mailJobData", mailJobData.toParams());
	this.client.queueServiceActionCall("jobs", "addMailJob", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.addBatchJob = function(callback, batchJob)
{

	kparams = new Object();
	this.client.addParam(kparams, "batchJob", batchJob.toParams());
	this.client.queueServiceActionCall("jobs", "addBatchJob", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.getStatus = function(callback, jobId, jobType)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.addParam(kparams, "jobType", jobType);
	this.client.queueServiceActionCall("jobs", "getStatus", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.deleteJob = function(callback, jobId, jobType)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.addParam(kparams, "jobType", jobType);
	this.client.queueServiceActionCall("jobs", "deleteJob", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.abortJob = function(callback, jobId, jobType)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.addParam(kparams, "jobType", jobType);
	this.client.queueServiceActionCall("jobs", "abortJob", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.retryJob = function(callback, jobId, jobType)
{

	kparams = new Object();
	this.client.addParam(kparams, "jobId", jobId);
	this.client.addParam(kparams, "jobType", jobType);
	this.client.queueServiceActionCall("jobs", "retryJob", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanJobsService.prototype.listBatchJobs = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", toParams(filter));
	if (pager != null)
		this.client.addParam(kparams, "pager", toParams(pager));
	this.client.queueServiceActionCall("jobs", "listBatchJobs", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanMediaService(client)
{
	this.init(client);
}

BorhanMediaService.prototype = new BorhanServiceBase();

BorhanMediaService.prototype.addFromBulk = function(callback, mediaEntry, url, bulkUploadId)
{

	kparams = new Object();
	this.client.addParam(kparams, "mediaEntry", mediaEntry.toParams());
	this.client.addParam(kparams, "url", url);
	this.client.addParam(kparams, "bulkUploadId", bulkUploadId);
	this.client.queueServiceActionCall("media", "addFromBulk", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.addFromUrl = function(callback, mediaEntry, url)
{

	kparams = new Object();
	this.client.addParam(kparams, "mediaEntry", mediaEntry.toParams());
	this.client.addParam(kparams, "url", url);
	this.client.queueServiceActionCall("media", "addFromUrl", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.addFromSearchResult = function(callback, mediaEntry, searchResult)
{
	if(!mediaEntry)
		mediaEntry = null;
	if(!searchResult)
		searchResult = null;

	kparams = new Object();
	if (mediaEntry != null)
		this.client.addParam(kparams, "mediaEntry", mediaEntry.toParams());
	if (searchResult != null)
		this.client.addParam(kparams, "searchResult", searchResult.toParams());
	this.client.queueServiceActionCall("media", "addFromSearchResult", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.addFromUploadedFile = function(callback, mediaEntry, uploadTokenId)
{

	kparams = new Object();
	this.client.addParam(kparams, "mediaEntry", mediaEntry.toParams());
	this.client.addParam(kparams, "uploadTokenId", uploadTokenId);
	this.client.queueServiceActionCall("media", "addFromUploadedFile", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.addFromRecordedWebcam = function(callback, mediaEntry, webcamTokenId)
{

	kparams = new Object();
	this.client.addParam(kparams, "mediaEntry", mediaEntry.toParams());
	this.client.addParam(kparams, "webcamTokenId", webcamTokenId);
	this.client.queueServiceActionCall("media", "addFromRecordedWebcam", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.get = function(callback, entryId, version)
{
	if(!version)
		version = -1;

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "version", version);
	this.client.queueServiceActionCall("media", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.update = function(callback, entryId, mediaEntry)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "mediaEntry", mediaEntry.toParams());
	this.client.queueServiceActionCall("media", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.delete = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("media", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("media", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.count = function(callback, filter)
{
	if(!filter)
		filter = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	this.client.queueServiceActionCall("media", "count", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.upload = function(callback, fileData)
{

	kparams = new Object();
	kfiles = new Object();
	this.client.addParam(kfiles, "fileData", fileData);
	this.client.queueServiceActionCall("media", "upload", kparams, kfiles);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.updateThumbnail = function(callback, entryId, timeOffset)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "timeOffset", timeOffset);
	this.client.queueServiceActionCall("media", "updateThumbnail", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.updateThumbnailFromSourceEntry = function(callback, entryId, sourceEntryId, timeOffset)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "sourceEntryId", sourceEntryId);
	this.client.addParam(kparams, "timeOffset", timeOffset);
	this.client.queueServiceActionCall("media", "updateThumbnailFromSourceEntry", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.updateThumbnailJpeg = function(callback, entryId, fileData)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	kfiles = new Object();
	this.client.addParam(kfiles, "fileData", fileData);
	this.client.queueServiceActionCall("media", "updateThumbnailJpeg", kparams, kfiles);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.updateThumbnailFromUrl = function(callback, entryId, url)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "url", url);
	this.client.queueServiceActionCall("media", "updateThumbnailFromUrl", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.requestConversion = function(callback, entryId, fileFormat)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "fileFormat", fileFormat);
	this.client.queueServiceActionCall("media", "requestConversion", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.flag = function(callback, moderationFlag)
{

	kparams = new Object();
	this.client.addParam(kparams, "moderationFlag", moderationFlag.toParams());
	this.client.queueServiceActionCall("media", "flag", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.reject = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("media", "reject", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.approve = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("media", "approve", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.listFlags = function(callback, entryId, pager)
{
	if(!pager)
		pager = null;

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("media", "listFlags", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMediaService.prototype.anonymousRank = function(callback, entryId, rank)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "rank", rank);
	this.client.queueServiceActionCall("media", "anonymousRank", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanMixingService(client)
{
	this.init(client);
}

BorhanMixingService.prototype = new BorhanServiceBase();

BorhanMixingService.prototype.add = function(callback, mixEntry)
{

	kparams = new Object();
	this.client.addParam(kparams, "mixEntry", mixEntry.toParams());
	this.client.queueServiceActionCall("mixing", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.get = function(callback, entryId, version)
{
	if(!version)
		version = -1;

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "version", version);
	this.client.queueServiceActionCall("mixing", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.update = function(callback, entryId, mixEntry)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "mixEntry", mixEntry.toParams());
	this.client.queueServiceActionCall("mixing", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.delete = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("mixing", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("mixing", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.count = function(callback, filter)
{
	if(!filter)
		filter = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	this.client.queueServiceActionCall("mixing", "count", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.cloneAction = function(callback, entryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.queueServiceActionCall("mixing", "clone", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.appendMediaEntry = function(callback, mixEntryId, mediaEntryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "mixEntryId", mixEntryId);
	this.client.addParam(kparams, "mediaEntryId", mediaEntryId);
	this.client.queueServiceActionCall("mixing", "appendMediaEntry", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.requestFlattening = function(callback, entryId, fileFormat, version)
{
	if(!version)
		version = -1;

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "fileFormat", fileFormat);
	this.client.addParam(kparams, "version", version);
	this.client.queueServiceActionCall("mixing", "requestFlattening", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.getMixesByMediaId = function(callback, mediaEntryId)
{

	kparams = new Object();
	this.client.addParam(kparams, "mediaEntryId", mediaEntryId);
	this.client.queueServiceActionCall("mixing", "getMixesByMediaId", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.getReadyMediaEntries = function(callback, mixId, version)
{
	if(!version)
		version = -1;

	kparams = new Object();
	this.client.addParam(kparams, "mixId", mixId);
	this.client.addParam(kparams, "version", version);
	this.client.queueServiceActionCall("mixing", "getReadyMediaEntries", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanMixingService.prototype.anonymousRank = function(callback, entryId, rank)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "rank", rank);
	this.client.queueServiceActionCall("mixing", "anonymousRank", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanNotificationService(client)
{
	this.init(client);
}

BorhanNotificationService.prototype = new BorhanServiceBase();

BorhanNotificationService.prototype.getClientNotification = function(callback, entryId, type)
{

	kparams = new Object();
	this.client.addParam(kparams, "entryId", entryId);
	this.client.addParam(kparams, "type", type);
	this.client.queueServiceActionCall("notification", "getClientNotification", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanPartnerService(client)
{
	this.init(client);
}

BorhanPartnerService.prototype = new BorhanServiceBase();

BorhanPartnerService.prototype.register = function(callback, partner, cmsPassword)
{
	if(!cmsPassword)
		cmsPassword = "";

	kparams = new Object();
	this.client.addParam(kparams, "partner", partner.toParams());
	this.client.addParam(kparams, "cmsPassword", cmsPassword);
	this.client.queueServiceActionCall("partner", "register", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPartnerService.prototype.update = function(callback, partner, allowEmpty)
{
	if(!allowEmpty)
		allowEmpty = false;

	kparams = new Object();
	this.client.addParam(kparams, "partner", partner.toParams());
	this.client.addParam(kparams, "allowEmpty", allowEmpty);
	this.client.queueServiceActionCall("partner", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPartnerService.prototype.getSecrets = function(callback, partnerId, adminEmail, cmsPassword)
{

	kparams = new Object();
	this.client.addParam(kparams, "partnerId", partnerId);
	this.client.addParam(kparams, "adminEmail", adminEmail);
	this.client.addParam(kparams, "cmsPassword", cmsPassword);
	this.client.queueServiceActionCall("partner", "getSecrets", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPartnerService.prototype.getInfo = function(callback)
{

	kparams = new Object();
	this.client.queueServiceActionCall("partner", "getInfo", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPartnerService.prototype.get = function(callback, partnerId)
{

	kparams = new Object();
	this.client.addParam(kparams, "partnerId", partnerId);
	this.client.queueServiceActionCall("partner", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPartnerService.prototype.getUsage = function(callback, year, month, resolution)
{
	if(!year)
		year = "";
	if(!month)
		month = 1;
	if(!resolution)
		resolution = "days";

	kparams = new Object();
	this.client.addParam(kparams, "year", year);
	this.client.addParam(kparams, "month", month);
	this.client.addParam(kparams, "resolution", resolution);
	this.client.queueServiceActionCall("partner", "getUsage", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanPlaylistService(client)
{
	this.init(client);
}

BorhanPlaylistService.prototype = new BorhanServiceBase();

BorhanPlaylistService.prototype.add = function(callback, playlist, updateStats)
{
	if(!updateStats)
		updateStats = false;

	kparams = new Object();
	this.client.addParam(kparams, "playlist", playlist.toParams());
	this.client.addParam(kparams, "updateStats", updateStats);
	this.client.queueServiceActionCall("playlist", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.get = function(callback, id, version)
{
	if(!version)
		version = -1;

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "version", version);
	this.client.queueServiceActionCall("playlist", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.update = function(callback, id, playlist, updateStats)
{
	if(!updateStats)
		updateStats = false;

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "playlist", playlist.toParams());
	this.client.addParam(kparams, "updateStats", updateStats);
	this.client.queueServiceActionCall("playlist", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("playlist", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("playlist", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.execute = function(callback, id, detailed)
{
	if(!detailed)
		detailed = false;

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "detailed", detailed);
	this.client.queueServiceActionCall("playlist", "execute", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.executeFromContent = function(callback, playlistType, playlistContent, detailed)
{
	if(!detailed)
		detailed = false;

	kparams = new Object();
	this.client.addParam(kparams, "playlistType", playlistType);
	this.client.addParam(kparams, "playlistContent", playlistContent);
	this.client.addParam(kparams, "detailed", detailed);
	this.client.queueServiceActionCall("playlist", "executeFromContent", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.executeFromFilters = function(callback, filters, totalResults, detailed)
{
	if(!detailed)
		detailed = false;

	kparams = new Object();
	for(var index in filters)
	{
		var obj = filters[index];
		this.client.addParam(kparams, "filters:" + index, obj.toParams());
	}
	this.client.addParam(kparams, "totalResults", totalResults);
	this.client.addParam(kparams, "detailed", detailed);
	this.client.queueServiceActionCall("playlist", "executeFromFilters", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanPlaylistService.prototype.getStatsFromContent = function(callback, playlistType, playlistContent)
{

	kparams = new Object();
	this.client.addParam(kparams, "playlistType", playlistType);
	this.client.addParam(kparams, "playlistContent", playlistContent);
	this.client.queueServiceActionCall("playlist", "getStatsFromContent", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanReportService(client)
{
	this.init(client);
}

BorhanReportService.prototype = new BorhanServiceBase();

BorhanReportService.prototype.getGraphs = function(callback, reportType, reportInputFilter, dimension, objectIds)
{
	if(!dimension)
		dimension = null;
	if(!objectIds)
		objectIds = null;

	kparams = new Object();
	this.client.addParam(kparams, "reportType", reportType);
	this.client.addParam(kparams, "reportInputFilter", reportInputFilter.toParams());
	this.client.addParam(kparams, "dimension", dimension);
	this.client.addParam(kparams, "objectIds", objectIds);
	this.client.queueServiceActionCall("report", "getGraphs", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanReportService.prototype.getTotal = function(callback, reportType, reportInputFilter, objectIds)
{
	if(!objectIds)
		objectIds = null;

	kparams = new Object();
	this.client.addParam(kparams, "reportType", reportType);
	this.client.addParam(kparams, "reportInputFilter", reportInputFilter.toParams());
	this.client.addParam(kparams, "objectIds", objectIds);
	this.client.queueServiceActionCall("report", "getTotal", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanReportService.prototype.getTable = function(callback, reportType, reportInputFilter, pager, order, objectIds)
{
	if(!order)
		order = null;
	if(!objectIds)
		objectIds = null;

	kparams = new Object();
	this.client.addParam(kparams, "reportType", reportType);
	this.client.addParam(kparams, "reportInputFilter", reportInputFilter.toParams());
	this.client.addParam(kparams, "pager", pager.toParams());
	this.client.addParam(kparams, "order", order);
	this.client.addParam(kparams, "objectIds", objectIds);
	this.client.queueServiceActionCall("report", "getTable", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanReportService.prototype.getUrlForReportAsCsv = function(callback, reportTitle, reportText, headers, reportType, reportInputFilter, dimension, pager, order, objectIds)
{
	if(!dimension)
		dimension = null;
	if(!pager)
		pager = null;
	if(!order)
		order = null;
	if(!objectIds)
		objectIds = null;

	kparams = new Object();
	this.client.addParam(kparams, "reportTitle", reportTitle);
	this.client.addParam(kparams, "reportText", reportText);
	this.client.addParam(kparams, "headers", headers);
	this.client.addParam(kparams, "reportType", reportType);
	this.client.addParam(kparams, "reportInputFilter", reportInputFilter.toParams());
	this.client.addParam(kparams, "dimension", dimension);
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.addParam(kparams, "order", order);
	this.client.addParam(kparams, "objectIds", objectIds);
	this.client.queueServiceActionCall("report", "getUrlForReportAsCsv", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanSearchService(client)
{
	this.init(client);
}

BorhanSearchService.prototype = new BorhanServiceBase();

BorhanSearchService.prototype.search = function(callback, search, pager)
{
	if(!pager)
		pager = null;

	kparams = new Object();
	this.client.addParam(kparams, "search", search.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("search", "search", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSearchService.prototype.getMediaInfo = function(callback, searchResult)
{

	kparams = new Object();
	this.client.addParam(kparams, "searchResult", searchResult.toParams());
	this.client.queueServiceActionCall("search", "getMediaInfo", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSearchService.prototype.searchUrl = function(callback, mediaType, url)
{

	kparams = new Object();
	this.client.addParam(kparams, "mediaType", mediaType);
	this.client.addParam(kparams, "url", url);
	this.client.queueServiceActionCall("search", "searchUrl", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSearchService.prototype.externalLogin = function(callback, searchSource, userName, password)
{

	kparams = new Object();
	this.client.addParam(kparams, "searchSource", searchSource);
	this.client.addParam(kparams, "userName", userName);
	this.client.addParam(kparams, "password", password);
	this.client.queueServiceActionCall("search", "externalLogin", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanSessionService(client)
{
	this.init(client);
}

BorhanSessionService.prototype = new BorhanServiceBase();

BorhanSessionService.prototype.start = function(callback, secret, userId, type, partnerId, expiry, privileges)
{
	if(!userId)
		userId = "";
	if(!type)
		type = 0;
	if(!partnerId)
		partnerId = -1;
	if(!expiry)
		expiry = 86400;
	if(!privileges)
		privileges = null;

	kparams = new Object();
	this.client.addParam(kparams, "secret", secret);
	this.client.addParam(kparams, "userId", userId);
	this.client.addParam(kparams, "type", type);
	this.client.addParam(kparams, "partnerId", partnerId);
	this.client.addParam(kparams, "expiry", expiry);
	this.client.addParam(kparams, "privileges", privileges);
	this.client.queueServiceActionCall("session", "start", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSessionService.prototype.startWidgetSession = function(callback, widgetId, expiry)
{
	if(!expiry)
		expiry = 86400;

	kparams = new Object();
	this.client.addParam(kparams, "widgetId", widgetId);
	this.client.addParam(kparams, "expiry", expiry);
	this.client.queueServiceActionCall("session", "startWidgetSession", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanStatsService(client)
{
	this.init(client);
}

BorhanStatsService.prototype = new BorhanServiceBase();

BorhanStatsService.prototype.collect = function(callback, event)
{

	kparams = new Object();
	this.client.addParam(kparams, "event", event.toParams());
	this.client.queueServiceActionCall("stats", "collect", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanStatsService.prototype.bmcCollect = function(callback, bmcEvent)
{

	kparams = new Object();
	this.client.addParam(kparams, "bmcEvent", bmcEvent.toParams());
	this.client.queueServiceActionCall("stats", "bmcCollect", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanStatsService.prototype.reportKceError = function(callback, borhanCEError)
{

	kparams = new Object();
	this.client.addParam(kparams, "borhanCEError", borhanCEError.toParams());
	this.client.queueServiceActionCall("stats", "reportKceError", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanSyndicationFeedService(client)
{
	this.init(client);
}

BorhanSyndicationFeedService.prototype = new BorhanServiceBase();

BorhanSyndicationFeedService.prototype.add = function(callback, syndicationFeed)
{

	kparams = new Object();
	this.client.addParam(kparams, "syndicationFeed", syndicationFeed.toParams());
	this.client.queueServiceActionCall("syndicationfeed", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSyndicationFeedService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("syndicationfeed", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSyndicationFeedService.prototype.update = function(callback, id, syndicationFeed)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "syndicationFeed", syndicationFeed.toParams());
	this.client.queueServiceActionCall("syndicationfeed", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSyndicationFeedService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("syndicationfeed", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSyndicationFeedService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("syndicationfeed", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSyndicationFeedService.prototype.getEntryCount = function(callback, feedId)
{

	kparams = new Object();
	this.client.addParam(kparams, "feedId", feedId);
	this.client.queueServiceActionCall("syndicationfeed", "getEntryCount", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSyndicationFeedService.prototype.requestConversion = function(callback, feedId)
{

	kparams = new Object();
	this.client.addParam(kparams, "feedId", feedId);
	this.client.queueServiceActionCall("syndicationfeed", "requestConversion", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanSystemService(client)
{
	this.init(client);
}

BorhanSystemService.prototype = new BorhanServiceBase();

BorhanSystemService.prototype.ping = function(callback)
{

	kparams = new Object();
	this.client.queueServiceActionCall("system", "ping", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanUiConfService(client)
{
	this.init(client);
}

BorhanUiConfService.prototype = new BorhanServiceBase();

BorhanUiConfService.prototype.add = function(callback, uiConf)
{

	kparams = new Object();
	this.client.addParam(kparams, "uiConf", uiConf.toParams());
	this.client.queueServiceActionCall("uiconf", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUiConfService.prototype.update = function(callback, id, uiConf)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "uiConf", uiConf.toParams());
	this.client.queueServiceActionCall("uiconf", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUiConfService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("uiconf", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUiConfService.prototype.delete = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("uiconf", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUiConfService.prototype.cloneAction = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("uiconf", "clone", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUiConfService.prototype.listTemplates = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("uiconf", "listTemplates", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUiConfService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("uiconf", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanUploadService(client)
{
	this.init(client);
}

BorhanUploadService.prototype = new BorhanServiceBase();

BorhanUploadService.prototype.getUploadTokenId = function(callback)
{

	kparams = new Object();
	this.client.queueServiceActionCall("upload", "getUploadTokenId", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUploadService.prototype.uploadByTokenId = function(callback, fileData, uploadTokenId)
{

	kparams = new Object();
	kfiles = new Object();
	this.client.addParam(kfiles, "fileData", fileData);
	this.client.addParam(kparams, "uploadTokenId", uploadTokenId);
	this.client.queueServiceActionCall("upload", "uploadByTokenId", kparams, kfiles);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUploadService.prototype.getUploadedFileStatusByTokenId = function(callback, uploadTokenId)
{

	kparams = new Object();
	this.client.addParam(kparams, "uploadTokenId", uploadTokenId);
	this.client.queueServiceActionCall("upload", "getUploadedFileStatusByTokenId", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUploadService.prototype.upload = function(callback, fileData)
{

	kparams = new Object();
	kfiles = new Object();
	this.client.addParam(kfiles, "fileData", fileData);
	this.client.queueServiceActionCall("upload", "upload", kparams, kfiles);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanUserService(client)
{
	this.init(client);
}

BorhanUserService.prototype = new BorhanServiceBase();

BorhanUserService.prototype.add = function(callback, user)
{

	kparams = new Object();
	this.client.addParam(kparams, "user", user.toParams());
	this.client.queueServiceActionCall("user", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUserService.prototype.update = function(callback, userId, user)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.addParam(kparams, "user", user.toParams());
	this.client.queueServiceActionCall("user", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUserService.prototype.get = function(callback, userId)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.queueServiceActionCall("user", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUserService.prototype.delete = function(callback, userId)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.queueServiceActionCall("user", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUserService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("user", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanUserService.prototype.notifyBan = function(callback, userId)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.queueServiceActionCall("user", "notifyBan", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanWidgetService(client)
{
	this.init(client);
}

BorhanWidgetService.prototype = new BorhanServiceBase();

BorhanWidgetService.prototype.add = function(callback, widget)
{

	kparams = new Object();
	this.client.addParam(kparams, "widget", widget.toParams());
	this.client.queueServiceActionCall("widget", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanWidgetService.prototype.update = function(callback, id, widget)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.addParam(kparams, "widget", widget.toParams());
	this.client.queueServiceActionCall("widget", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanWidgetService.prototype.get = function(callback, id)
{

	kparams = new Object();
	this.client.addParam(kparams, "id", id);
	this.client.queueServiceActionCall("widget", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanWidgetService.prototype.cloneAction = function(callback, widget)
{

	kparams = new Object();
	this.client.addParam(kparams, "widget", widget.toParams());
	this.client.queueServiceActionCall("widget", "clone", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanWidgetService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("widget", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanXInternalService(client)
{
	this.init(client);
}

BorhanXInternalService.prototype = new BorhanServiceBase();

BorhanXInternalService.prototype.xAddBulkDownload = function(callback, entryIds, flavorParamsId)
{
	if(!flavorParamsId)
		flavorParamsId = "";

	kparams = new Object();
	this.client.addParam(kparams, "entryIds", entryIds);
	this.client.addParam(kparams, "flavorParamsId", flavorParamsId);
	this.client.queueServiceActionCall("xinternal", "xAddBulkDownload", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanSystemUserService(client)
{
	this.init(client);
}

BorhanSystemUserService.prototype = new BorhanServiceBase();

BorhanSystemUserService.prototype.verifyPassword = function(callback, email, password)
{

	kparams = new Object();
	this.client.addParam(kparams, "email", email);
	this.client.addParam(kparams, "password", password);
	this.client.queueServiceActionCall("systemuser", "verifyPassword", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.generateNewPassword = function(callback)
{

	kparams = new Object();
	this.client.queueServiceActionCall("systemuser", "generateNewPassword", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.setNewPassword = function(callback, userId, password)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.addParam(kparams, "password", password);
	this.client.queueServiceActionCall("systemuser", "setNewPassword", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.add = function(callback, systemUser)
{

	kparams = new Object();
	this.client.addParam(kparams, "systemUser", systemUser.toParams());
	this.client.queueServiceActionCall("systemuser", "add", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.get = function(callback, userId)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.queueServiceActionCall("systemuser", "get", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.getByEmail = function(callback, email)
{

	kparams = new Object();
	this.client.addParam(kparams, "email", email);
	this.client.queueServiceActionCall("systemuser", "getByEmail", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.update = function(callback, userId, systemUser)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.addParam(kparams, "systemUser", systemUser.toParams());
	this.client.queueServiceActionCall("systemuser", "update", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.delete = function(callback, userId)
{

	kparams = new Object();
	this.client.addParam(kparams, "userId", userId);
	this.client.queueServiceActionCall("systemuser", "delete", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

BorhanSystemUserService.prototype.listAction = function(callback, filter, pager)
{
	if(!filter)
		filter = null;
	if(!pager)
		pager = null;

	kparams = new Object();
	if (filter != null)
		this.client.addParam(kparams, "filter", filter.toParams());
	if (pager != null)
		this.client.addParam(kparams, "pager", pager.toParams());
	this.client.queueServiceActionCall("systemuser", "list", kparams);
	if (!this.client.isMultiRequest())
		this.client.doQueue(callback);
};

function BorhanClient(config)
{
	this.init(config);
}

BorhanClient.prototype = new BorhanClientBase()
/**
 * Add & Manage Access Controls
 *
 * @var BorhanAccessControlService
 */
BorhanClient.prototype.accessControl = null;

/**
 * admin console service lets you manage cross partner reports, activity, status and config. 
	 * 
 *
 * @var BorhanAdminconsoleService
 */
BorhanClient.prototype.adminconsole = null;

/**
 * Manage details for the administrative user
 *
 * @var BorhanAdminUserService
 */
BorhanClient.prototype.adminUser = null;

/**
 * Base Entry Service
 *
 * @var BorhanBaseEntryService
 */
BorhanClient.prototype.baseEntry = null;

/**
 * Bulk upload service is used to upload & manage bulk uploads using CSV files
 *
 * @var BorhanBulkUploadService
 */
BorhanClient.prototype.bulkUpload = null;

/**
 * Add & Manage Categories
 *
 * @var BorhanCategoryService
 */
BorhanClient.prototype.category = null;

/**
 * Add & Manage Conversion Profiles
 *
 * @var BorhanConversionProfileService
 */
BorhanClient.prototype.conversionProfile = null;

/**
 * Data service lets you manage data content (textual content)
 *
 * @var BorhanDataService
 */
BorhanClient.prototype.data = null;

/**
 * Retrieve information and invoke actions on Flavor Asset
 *
 * @var BorhanFlavorAssetService
 */
BorhanClient.prototype.flavorAsset = null;

/**
 * Add & Manage Flavor Params
 *
 * @var BorhanFlavorParamsService
 */
BorhanClient.prototype.flavorParams = null;

/**
 * batch service lets you handle different batch process from remote machines.
	 * As oppesed to other ojects in the system, locking mechanism is critical in this case.
	 * For this reason the GetExclusiveXX, UpdateExclusiveXX and FreeExclusiveXX actions are important for the system's intergity.
	 * In general - updating batch object should be done only using the UpdateExclusiveXX which in turn can be called only after 
	 * acuiring a batch objet properly (using  GetExclusiveXX).
	 * If an object was aquired and should be returned to the pool in it's initial state - use the FreeExclusiveXX action 
	 * 
 *
 * @var BorhanJobsService
 */
BorhanClient.prototype.jobs = null;

/**
 * Media service lets you upload and manage media files (images / videos & audio)
 *
 * @var BorhanMediaService
 */
BorhanClient.prototype.media = null;

/**
 * A Mix is an XML unique format invented by Borhan, it allows the user to create a mix of videos and images, in and out points, transitions, text overlays, soundtrack, effects and much more...
	 * Mixing service lets you create a new mix, manage its metadata and make basic manipulations.   
 *
 * @var BorhanMixingService
 */
BorhanClient.prototype.mixing = null;

/**
 * Notification Service
 *
 * @var BorhanNotificationService
 */
BorhanClient.prototype.notification = null;

/**
 * partner service allows you to change/manage your partner personal details and settings as well
 *
 * @var BorhanPartnerService
 */
BorhanClient.prototype.partner = null;

/**
 * Playlist service lets you create,manage and play your playlists
	 * Playlists could be static (containing a fixed list of entries) or dynamic (baseed on a filter)
 *
 * @var BorhanPlaylistService
 */
BorhanClient.prototype.playlist = null;

/**
 * api for getting reports data by the report type and some inputFilter
 *
 * @var BorhanReportService
 */
BorhanClient.prototype.report = null;

/**
 * Search service allows you to search for media in various media providers
	 * This service is being used mostly by the CW component
 *
 * @var BorhanSearchService
 */
BorhanClient.prototype.search = null;

/**
 * Session service
 *
 * @var BorhanSessionService
 */
BorhanClient.prototype.session = null;

/**
 * Stats Service
 *
 * @var BorhanStatsService
 */
BorhanClient.prototype.stats = null;

/**
 * Add & Manage Syndication Feeds
 *
 * @var BorhanSyndicationFeedService
 */
BorhanClient.prototype.syndicationFeed = null;

/**
 * System service is used for internal system helpers & to retrieve system level information
 *
 * @var BorhanSystemService
 */
BorhanClient.prototype.system = null;

/**
 * UiConf service lets you create and manage your UIConfs for the various flash components
	 * This service is used by the BMC-ApplicationStudio
 *
 * @var BorhanUiConfService
 */
BorhanClient.prototype.uiConf = null;

/**
 * Upload service is used to upload files and get the token that can be later used as a reference to the uploaded file
	 * 
 *
 * @var BorhanUploadService
 */
BorhanClient.prototype.upload = null;

/**
 * Manage partner users on Borhan's side
	 * The userId in borhan is the unique Id in the partner's system, and the [partnerId,Id] couple are unique key in borhan's DB
 *
 * @var BorhanUserService
 */
BorhanClient.prototype.user = null;

/**
 * widget service for full widget management
 *
 * @var BorhanWidgetService
 */
BorhanClient.prototype.widget = null;

/**
 * Internal Service is used for actions that are used internally in Borhan applications and might be changed in the future without any notice.
 *
 * @var BorhanXInternalService
 */
BorhanClient.prototype.xInternal = null;

/**
 * System user service
 *
 * @var BorhanSystemUserService
 */
BorhanClient.prototype.systemUser = null;


BorhanClient.prototype.init = function(config)
{
	BorhanClientBase.prototype.init.apply(this, arguments);
	this.accessControl = new BorhanAccessControlService(this);
	this.adminconsole = new BorhanAdminconsoleService(this);
	this.adminUser = new BorhanAdminUserService(this);
	this.baseEntry = new BorhanBaseEntryService(this);
	this.bulkUpload = new BorhanBulkUploadService(this);
	this.category = new BorhanCategoryService(this);
	this.conversionProfile = new BorhanConversionProfileService(this);
	this.data = new BorhanDataService(this);
	this.flavorAsset = new BorhanFlavorAssetService(this);
	this.flavorParams = new BorhanFlavorParamsService(this);
	this.jobs = new BorhanJobsService(this);
	this.media = new BorhanMediaService(this);
	this.mixing = new BorhanMixingService(this);
	this.notification = new BorhanNotificationService(this);
	this.partner = new BorhanPartnerService(this);
	this.playlist = new BorhanPlaylistService(this);
	this.report = new BorhanReportService(this);
	this.search = new BorhanSearchService(this);
	this.session = new BorhanSessionService(this);
	this.stats = new BorhanStatsService(this);
	this.syndicationFeed = new BorhanSyndicationFeedService(this);
	this.system = new BorhanSystemService(this);
	this.uiConf = new BorhanUiConfService(this);
	this.upload = new BorhanUploadService(this);
	this.user = new BorhanUserService(this);
	this.widget = new BorhanWidgetService(this);
	this.xInternal = new BorhanXInternalService(this);
	this.systemUser = new BorhanSystemUserService(this);
}
