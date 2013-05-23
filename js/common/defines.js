var LEFT = (window.RTL) ? 'right' : 'left';
var RIGHT = (window.RTL) ? 'left' : 'right';

var STR_SEPARATOR = '#@%';

// defines for sections
var SECTION_MAIL = 0;
var SECTION_SETTINGS = 1;
var SECTION_CONTACTS = 2;
var SECTION_CALENDAR = 3;

// defines for screens
var SCREEN_MESSAGE_LIST_TOP_PANE = 0;
var SCREEN_MESSAGE_LIST_CENTRAL_PANE = 1;
var SCREEN_VIEW_MESSAGE = 2;
var SCREEN_NEW_MESSAGE = 3;

var SCREEN_USER_SETTINGS = 4;
	var PART_COMMON_SETTINGS = 0;
	var PART_ACCOUNT_PROPERTIES = 1;
	var PART_SIGNATURE = 2;
	var PART_FILTERS = 3;
	var PART_AUTORESPONDER = 4;
	var PART_FORWARD = 5;
	var PART_MANAGE_FOLDERS = 6;
	var PART_IDENTITIES = 7;
	var PART_CALENDAR_SETTINGS = 8;
	var PART_MOBILE_SYNC = 9;
	var PART_OUTLOOK_SYNC = 10;
	var PART_CUSTOM = 11;

var SCREEN_CONTACTS = 5;
	var PART_CONTACTS = 0;
	var PART_NEW_CONTACT = 1;
	var PART_VIEW_CONTACT = 2;
	var PART_EDIT_CONTACT = 3;
	var PART_NEW_GROUP = 4;
	var PART_VIEW_GROUP = 5;
	var PART_IMPORT_CONTACT = 6;

var SCREEN_CALENDAR = 6;

var Sections = [];
Sections[SECTION_MAIL] = {Scripts: [], Screens: []};
Sections[SECTION_MAIL].Screens[SCREEN_MESSAGE_LIST_TOP_PANE] = 'screen = new CMessageListTopPaneScreen(this.sLookFor);';
Sections[SECTION_MAIL].Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE] = 'screen = new CMessageListCentralPaneScreen(this.sLookFor);';
Sections[SECTION_MAIL].Screens[SCREEN_NEW_MESSAGE] = 'screen = new CNewMessageScreen(false);';

Sections[SECTION_SETTINGS] = {Scripts: [], Screens: []};
Sections[SECTION_SETTINGS].Screens[SCREEN_USER_SETTINGS] = 'screen = new CUserSettingsScreen();';

Sections[SECTION_CONTACTS] = {Scripts: [], Screens: []};
Sections[SECTION_CONTACTS].Screens[SCREEN_CONTACTS] = 'screen = new CContactsScreen();';
Sections[SECTION_CALENDAR] = {Scripts: [], Screens: []};
Sections[SECTION_CALENDAR].Screens[SCREEN_CALENDAR] = 'screen = new CCalendarScreen();';

var Screens = [];
Screens[SCREEN_MESSAGE_LIST_TOP_PANE] = {SectionId: SECTION_MAIL, PreRender: true, showHandler: '', titleLangField: 'TitleMessagesList'};
Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE] = {SectionId: SECTION_MAIL, PreRender: true, showHandler: '', titleLangField: 'TitleMessagesList'};
Screens[SCREEN_NEW_MESSAGE] = {SectionId: SECTION_MAIL, PreRender: true, showHandler: '', titleLangField: 'TitleNewMessage'};
Screens[SCREEN_USER_SETTINGS] = {SectionId: SECTION_SETTINGS, PreRender: true, showHandler: '', titleLangField: 'TitleSettings'};
Screens[SCREEN_CONTACTS] = {SectionId: SECTION_CONTACTS, PreRender: true, showHandler: '', titleLangField: 'TitleContacts'};
Screens[SCREEN_CALENDAR] = {SectionId: SECTION_CALENDAR, PreRender: true, showHandler: '', titleLangField: 'Calendar'};

// defines data types
var TYPE_ACCOUNT_BASE = 0; // includes FOLDER_LIST, MESSAGE_LIST
var TYPE_ACCOUNT_LIST = 1;
var TYPE_ACCOUNT_PROPERTIES = 2;
var TYPE_AUTORESPONDER = 3;
var TYPE_BASE = 4; // includes SETTINGS_LIST, ACCOUNT_LIST, FOLDER_LIST, MESSAGE_LIST
var TYPE_CONTACT = 5;
var TYPE_CONTACTS = 6;
var TYPE_FILTERS = 7;
var TYPE_FOLDERS_BASE = 8; // includes MESSAGE_LISTs for 1 page in several folders
var TYPE_FOLDER_LIST = 9;
var TYPE_FORWARD = 10;
var TYPE_GROUP = 11;
var TYPE_GROUPS = 12;
var TYPE_IDENTITIES = 13;
var TYPE_IDENTITY = 14;
var TYPE_MESSAGE = 15;
var TYPE_MESSAGES_BODIES = 16; // includes MESSAGE for messages with size<=75K in last MESSAGE_LIST
var TYPE_MESSAGES_OPERATION = 17;
var TYPE_MESSAGE_LIST = 18;
var TYPE_MOBILE_SYNC = 19;
var TYPE_SETTINGS_LIST = 20;
var TYPE_SERVER_ATTACHMENT = 21;
var TYPE_SERVER_ATTACHMENT_LIST = 22;
var TYPE_SERVER_BASED_DATA = 23;
var TYPE_UPDATE = 25;
var TYPE_USER_SETTINGS = 26;
var TYPE_CUSTOM = 27;
var TYPE_DOSSIERS_DATA = 28;
var TYPE_GLOBAL_CONTACTS = 29;
var TYPE_GLOBAL_CONTACT = 30;
var TYPE_OUTLOOK_SYNC = 31;
var TYPE_MULTIPLE_CONTACTS = 32;

//defines for folder types
var FOLDER_TYPE_DEFAULT = 0;
var FOLDER_TYPE_INBOX = 1;
var FOLDER_TYPE_SENT = 2;
var FOLDER_TYPE_DRAFTS = 3;
var FOLDER_TYPE_TRASH = 4;
var FOLDER_TYPE_SPAM = 5;
var FOLDER_TYPE_QUARANTINE = 6;
var FOLDER_TYPE_SYSTEM = 9;
var FOLDER_TYPE_DEFAULT_SYNC = 20;
var FOLDER_TYPE_INBOX_SYNC = 21;
var FOLDER_TYPE_SENT_SYNC = 22;
var FOLDER_TYPE_DRAFTS_SYNC = 23;
var FOLDER_TYPE_TRASH_SYNC = 24;
var FOLDER_TYPE_SPAM_SYNC = 25;
var FOLDER_TYPE_QUARANTINE_SYNC = 26;
var FOLDER_TYPE_SYSTEM_SYNC = 29;

var FolderDescriptions = [];
FolderDescriptions[FOLDER_TYPE_DEFAULT] = {x: 0, y: 2};
FolderDescriptions[FOLDER_TYPE_DEFAULT_SYNC] = {x: 1, y: 2};
FolderDescriptions[FOLDER_TYPE_DRAFTS] = {x: 2, y: 2, langField: 'FolderDrafts'};
FolderDescriptions[FOLDER_TYPE_DRAFTS_SYNC] = {x: 3, y: 2, langField: 'FolderDrafts'};
FolderDescriptions[FOLDER_TYPE_INBOX] = {x: 4, y: 2, langField: 'FolderInbox'};
FolderDescriptions[FOLDER_TYPE_INBOX_SYNC] = {x: 5, y: 2, langField: 'FolderInbox'};
FolderDescriptions[FOLDER_TYPE_SENT] = {x: 6, y: 2, langField: 'FolderSentItems'};
FolderDescriptions[FOLDER_TYPE_SENT_SYNC] = {x: 7, y: 2, langField: 'FolderSentItems'};
FolderDescriptions[FOLDER_TYPE_TRASH] = {x: 8, y: 2, langField: 'FolderTrash'};
FolderDescriptions[FOLDER_TYPE_TRASH_SYNC] = {x: 9, y: 2, langField: 'FolderTrash'};
FolderDescriptions[FOLDER_TYPE_SPAM] = {x: 10, y: 2, langField: 'FolderSpam'};
FolderDescriptions[FOLDER_TYPE_SPAM_SYNC] = {x: 11, y: 2, langField: 'FolderSpam'};
FolderDescriptions[FOLDER_TYPE_QUARANTINE] = {x: 0, y: 2, langField: 'FolderQuarantine'};
FolderDescriptions[FOLDER_TYPE_QUARANTINE_SYNC] = {x: 1, y: 2, langField: 'FolderQuarantine'};
FolderDescriptions[FOLDER_TYPE_SYSTEM] = {x: 0, y: 2};
FolderDescriptions[FOLDER_TYPE_SYSTEM_SYNC] = {x: 1, y: 2};

//defines for sync type
var SYNC_TYPE_NO = 0;
var SYNC_TYPE_NEW_HEADERS = 1;
var SYNC_TYPE_ALL_HEADERS = 2;
var SYNC_TYPE_NEW_MSGS = 3;
var SYNC_TYPE_ALL_MSGS = 4;
var SYNC_TYPE_DIRECT_MODE = 5;

var SORT_FIELD_NOTHING = -1;
var SORT_FIELD_DATE = 0;
var SORT_FIELD_FROM = 2;
var SORT_FIELD_TO = 4;
var SORT_FIELD_SIZE = 6;
var SORT_FIELD_SUBJECT = 8;
var SORT_FIELD_ATTACH = 10;
var SORT_FIELD_FLAG = 12;
var SORT_ORDER_DESC = 0;
var SORT_ORDER_ASC = 1;

//defines for inbox headers
var IH_CHECK = 0;
var IH_ATTACHMENTS = 1;
var IH_FLAGGED = 2;
var IH_FROM = 3;
var IH_TO = 4;
var IH_DATE = 5;
var IH_SIZE = 6;
var IH_SUBJECT = 7;
var IH_PRIORITY = 8;
var IH_SENSIVITY = 9;

/*
SortIconPlace values:
	0 - left of content
	1 - instead of content
	2 - right of content
Align values: 'left', 'center', 'right'
*/
var InboxHeaders = [];
InboxHeaders[IH_CHECK] =
{
	DisplayField: 'Check',
	LangField: '',
	Picture: '',
	sortField: SORT_FIELD_NOTHING,
	SortIconPlace: 1,
	Align: 'center',
	Width: 24,
	MinWidth: 24,
	isResize: false,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};
InboxHeaders[IH_ATTACHMENTS] =
{
	DisplayField: 'HasAttachments',
	LangField: '',
	Picture: 'wm_inbox_lines_attachment',
	sortField: SORT_FIELD_ATTACH,
	SortIconPlace: 1,
	Align: 'center',
	Width: 20,
	MinWidth: 20,
	isResize: false,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};
InboxHeaders[IH_PRIORITY] =
{
	DisplayField: 'Importance',
	LangField: '',
	Picture: 'wm_inbox_lines_priority_header',
	sortField: SORT_FIELD_NOTHING,
	SortIconPlace: 1,
	Align: 'center',
	Width: 24,
	MinWidth: 24,
	isResize: false,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};
InboxHeaders[IH_SENSIVITY] =
{
	DisplayField: 'Sensivity',
	LangField: '',
	Picture: 'wm_inbox_lines_sensivity_header',
	sortField: SORT_FIELD_NOTHING,
	SortIconPlace: 1,
	Align: 'center',
	Width: 24,
	MinWidth: 24,
	isResize: false,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};
InboxHeaders[IH_FLAGGED] =
{
	DisplayField: 'Flagged',
	LangField: '',
	Picture: 'wm_inbox_lines_flag',
	sortField: SORT_FIELD_FLAG,
	SortIconPlace: 1,
	Align: 'center',
	Width: 20,
	MinWidth: 20,
	isResize: false,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};
InboxHeaders[IH_FROM] =
{
	DisplayField: 'FromAddr',
	LangField: 'From',
	Picture: '',
	sortField: SORT_FIELD_FROM,
	SortIconPlace: 2,
	Align: window.LEFT,
	Width: 150,
	MinWidth: 100,
	isResize: true,
	PaddingLeftRight: 6,
	PaddingTopBottom: 2
};
InboxHeaders[IH_TO] =
{
	DisplayField: 'ToAddr',
	LangField: 'To',
	Picture: '',
	sortField: SORT_FIELD_TO,
	SortIconPlace: 2,
	Align: window.LEFT,
	Width: 150,
	MinWidth: 100,
	isResize: true,
	PaddingLeftRight: 6,
	PaddingTopBottom: 2
};
InboxHeaders[IH_DATE] =
{
	DisplayField: 'Date',
	LangField: 'Date',
	Picture: '',
	sortField: SORT_FIELD_DATE,
	SortIconPlace: 2,
	Align: 'center',
	Width: 80,
	MinWidth: 80,
	isResize: true,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};
InboxHeaders[IH_SIZE] =
{
	DisplayField: 'Size',
	LangField: 'Size',
	Picture: '',
	sortField: SORT_FIELD_SIZE,
	SortIconPlace: 2,
	Align: 'center',
	Width: 50,
	MinWidth: 40,
	isResize: true,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};
InboxHeaders[IH_SUBJECT] =
{
	DisplayField: 'Subject',
	LangField: 'Subject',
	Picture: '',
	sortField: SORT_FIELD_SUBJECT,
	SortIconPlace: 2,
	Align: window.LEFT,
	Width: 150,
	MinWidth: 100,
	isResize: true,
	PaddingLeftRight: 2,
	PaddingTopBottom: 2
};

//defines for parts of message type
var PART_MESSAGE_HEADERS = 0;
var PART_MESSAGE_HTML = 1;
var PART_MESSAGE_MODIFIED_PLAIN_TEXT = 2;
var PART_MESSAGE_REPLY_HTML = 3;
var PART_MESSAGE_REPLY_PLAIN = 4;
var PART_MESSAGE_FORWARD_HTML = 5;
var PART_MESSAGE_FORWARD_PLAIN = 6;
var PART_MESSAGE_FULL_HEADERS = 7;
var PART_MESSAGE_ATTACHMENTS = 8;
var PART_MESSAGE_UNMODIFIED_PLAIN_TEXT = 9;

// defines for toolbar view mode
var TOOLBAR_VIEW_STANDARD = 0;
var TOOLBAR_VIEW_WITH_CURVE = 1;
var TOOLBAR_VIEW_NEW_MESSAGE = 2;

// defines for toolbar items
var TOOLBAR_NEW_MESSAGE = 1;
var TOOLBAR_CHECK_MAIL = 2;
var TOOLBAR_REPLY = 4;
var TOOLBAR_REPLYALL = 5;
var TOOLBAR_FORWARD = 6;
var TOOLBAR_MARK_READ = 7;
var TOOLBAR_MOVE_TO_FOLDER = 8;
var TOOLBAR_DELETE = 9;
var TOOLBAR_UNDELETE = 10;
var TOOLBAR_PURGE = 11;
var TOOLBAR_EMPTY_TRASH = 12;
var TOOLBAR_IS_SPAM = 13;
var TOOLBAR_NOT_SPAM = 14;
var TOOLBAR_SEARCH = 15;
var TOOLBAR_BIG_SEARCH = 16;
var TOOLBAR_SEARCH_ARROW_DOWN = 17;
var TOOLBAR_SEARCH_ARROW_UP = 18;
var TOOLBAR_ARROW = 19;

//second line in mail.png
var TOOLBAR_BACK_TO_LIST = 20;
var TOOLBAR_SEND_MESSAGE = 21;
var TOOLBAR_SAVE_MESSAGE = 22;
var TOOLBAR_HIGH_IMPORTANCE = 23;
var TOOLBAR_LOW_IMPORTANCE = 24;
var TOOLBAR_NORMAL_IMPORTANCE = 25;
var TOOLBAR_PRINT_MESSAGE = 26;
var TOOLBAR_NEXT_ACTIVE = 27;
//var TOOLBAR_NEXT_INACTIVE = 28;
var TOOLBAR_PREV_ACTIVE = 29;
//var TOOLBAR_PREV_INACTIVE = 30;
var TOOLBAR_NEW_CONTACT = 31;
var TOOLBAR_NEW_GROUP = 32;
var TOOLBAR_ADD_CONTACTS_TO = 33;
var TOOLBAR_IMPORT_CONTACTS = 34;
var TOOLBAR_IMPORTANCE = 35;
var TOOLBAR_CANCEL = 36;

//third line in mail.png
var TOOLBAR_MARK_UNREAD = 37;
var TOOLBAR_FLAG = 38;
var TOOLBAR_UNFLAG = 39;
var TOOLBAR_MARK_ALL_READ = 40;
var TOOLBAR_MARK_ALL_UNREAD = 41;

var TOOLBAR_EMPTY_SPAM = 42;

var TOOLBAR_SENSIVITY = 43;
var TOOLBAR_SENSIVITY_NOTHING = 44;
var TOOLBAR_SENSIVITY_CONFIDENTIAL = 45;
var TOOLBAR_SENSIVITY_PRIVATE = 46;
var TOOLBAR_SENSIVITY_PERSONAL = 47;

var TOOLBAR_NO_MOVE_DELETE = 48;
//var TOOLBAR_COPY_TO_FOLDER = 49;
var TOOLBAR_LIGHT_SEARCH_ARROW_DOWN = 51;
var TOOLBAR_LIGHT_SEARCH_ARROW_UP = 52;
var TOOLBAR_EXPORT_CONTACTS = 53;

var TOOLBAR_TEST = 99;

var OperationTypes = [];
OperationTypes[TOOLBAR_DELETE] = 'delete';
OperationTypes[TOOLBAR_UNDELETE] = 'undelete';
OperationTypes[TOOLBAR_PURGE] = 'purge';
OperationTypes[TOOLBAR_MARK_READ] = 'mark_read';
OperationTypes[TOOLBAR_MARK_UNREAD] = 'mark_unread';
OperationTypes[TOOLBAR_FLAG] = 'flag';
OperationTypes[TOOLBAR_UNFLAG] = 'unflag';
OperationTypes[TOOLBAR_MARK_ALL_READ] = 'mark_all_read';
OperationTypes[TOOLBAR_MARK_ALL_UNREAD] = 'mark_all_unread';
OperationTypes[TOOLBAR_MOVE_TO_FOLDER] = 'move_to_folder';
// OperationTypes[TOOLBAR_COPY_TO_FOLDER] = 'copy_to_folder';
OperationTypes[TOOLBAR_IS_SPAM] = 'spam';
OperationTypes[TOOLBAR_NOT_SPAM] = 'not_spam';
OperationTypes[TOOLBAR_EMPTY_SPAM] = 'clear_spam';
OperationTypes[TOOLBAR_NO_MOVE_DELETE] = 'no_move_delete';

var REDRAW_NOTHING = 0;
var REDRAW_FOLDER = 1;
var REDRAW_HEADER = 2;
var REDRAW_PAGE = 3;

var COOKIE_STORAGE_DAYS = 200;
var FOLDERS_TREES_INT_INDENT = 8;
var FOLDERS_TREES_STR_INDENT = '&nbsp;&nbsp;&nbsp;&nbsp;';
var AUTOSELECT_CHARSET = -1;
var X_ICON_SHIFT = 40;
var Y_ICON_SHIFT = 40;

var POP3_PROTOCOL = 0;
var IMAP4_PROTOCOL = 1;
var POP3_PORT = 110;
var IMAP4_PORT = 143;
var SMTP_PORT = 25;

//defines for contacts headers
var CH_CHECK = 20;
var CH_GROUP = 21;
var CH_NAME = 22;
var CH_EMAIL = 23;

var
	SORT_FIELD_NAME = 1,
	SORT_FIELD_EMAIL = 2,
	SORT_FIELD_USE_FREQ = 3
;

var ContactsHeaders = [];
ContactsHeaders[CH_CHECK] =
{
	DisplayField: 'Check',
	LangField: '',
	Picture: '',
	sortField: SORT_FIELD_NOTHING,
	SortIconPlace: 1,
	Align: 'center',
	Width: 24,
	MinWidth: 24,
	isResize: false
};
ContactsHeaders[CH_GROUP] =
{
	DisplayField: 'IsGroup',
	LangField: '',
	Picture: 'wm_inbox_lines_group',
	sortField: SORT_FIELD_NOTHING,
	SortIconPlace: 1,
	Align: 'center',
	Width: 25,
	MinWidth: 25,
	isResize: false
};
ContactsHeaders[CH_NAME] =
{
	DisplayField: 'Name',
	LangField: 'Name',
	Picture: '',
	sortField: SORT_FIELD_NAME,
	SortIconPlace: 2,
	Align: window.LEFT,
	Width: 150,
	MinWidth: 100,
	isResize: true
};
ContactsHeaders[CH_EMAIL] =
{
	DisplayField: 'Email',
	LangField: 'Email',
	Picture: '',
	sortField: SORT_FIELD_EMAIL,
	SortIconPlace: 2,
	Align: window.LEFT,
	Width: 150,
	MinWidth: 100,
	isResize: true
};

var PRIMARY_HOME_EMAIL = 0;
var PRIMARY_BUS_EMAIL = 1;
var PRIMARY_OTHER_EMAIL = 2;
var PRIMARY_DEFAULT_EMAIL = PRIMARY_HOME_EMAIL;
//var UseCustomContacts = false;
//var UseCustomContacts1 = false;

var
	CONTACT_NAME_FORMAT_FIRSTNAME = 0,
	CONTACT_NAME_FORMAT_LASTNAME = 1
;

var GET_FOLDERS_NOT_CHANGE_ACCT = -1;
var GET_FOLDERS_NOT_SYNC = 0;

var PRIORITY_LOW = 5;
var PRIORITY_NORMAL = 3;
var PRIORITY_HIGH = 1;

var SENSIVITY_NOTHING = 0;
var SENSIVITY_CONFIDENTIAL = 1;
var SENSIVITY_PRIVATE = 2;
var SENSIVITY_PERSONAL = 3;

var FILTER_STATUS_NEW = 'new';
var FILTER_STATUS_UPDATED = 'updated';
var FILTER_STATUS_UNCHANGED = 'unchanged';
var FILTER_STATUS_REMOVED = 'removed';

var AddPriorityHeader = false;
var AddSensivityHeader = false;

var CustomTopLinks = [
	//{ Name: 'Google', Link: 'http://google.com/' }
];

var POPUP_SHOWED = 2;
var POPUP_READY = 1;
var POPUP_HIDDEN = 0;

var MIN_SCREEN_HEIGHT = 400;
var MIN_SCREEN_WIDTH = 600;

var SEND_MODE = 0;
var SAVE_MODE = 1;
var AUTO_SAVE_MODE = 2;

var RESIZE_MODE_ALL = 0;
var RESIZE_MODE_FOLDERS = 1;
var RESIZE_MODE_MSG_WIDTH = 2;
var RESIZE_MODE_MSG_HEIGHT = 3;
var RESIZE_MODE_MSG_PANE = 4;

var SAFETY_NOTHING = 0;
var SAFETY_FULL = 1;
var SAFETY_MESSAGE = 2;

var CONTACTS_SEARCH_TYPE_STANDARD = 0;
var CONTACTS_SEARCH_TYPE_FREQUENCY = 1;

var VALIDATION_FOLDER_NAME_LENGTH = 30;
var VALIDATION_FOLDER_NAME_REGEXP = new RegExp('^.+$', 'g');
// var VALIDATION_FOLDER_NAME_REGEXP = new RegExp('^[a-zA-Z0-9\- ]*$', 'g');

var SAVE_MAIL_HIDDEN = 0;
var SAVE_MAIL_CHECKED = 1;
var SAVE_MAIL_UNCHECKED = 2;

var NON_EXISTENT_ID = -1;

var MESSAGE_LIST_FILTER_NONE = 0;
var MESSAGE_LIST_FILTER_UNSEEN = 1;
var MESSAGE_LIST_FILTER_WITH_ATTACHMENTS = 2;

var STATISTIC_EMOTICON_ADDED = 9;
var STATISTIC_BACKGROUND_CHANGED = 10;
var STATISTIC_BACKGROUND_REMOVED = 11;

/**
 * @enum {string}
 */
var EnumAppointmentType = {
	Request: 'REQUEST',
	Reply: 'REPLY',
	Cancel: 'CANCEL',
	Save: 'SAVE'
};

/**
 * @enum {string}
 */
var EnumAppointmentConfig = {
	Accepted: 'ACCEPTED',
	Declined: 'DECLINED',
	Tentative: 'TENTATIVE',
	NeedAction: 'NEED-ACTION'
};

var DELETE_MESSAGES_FROM_SERVER = 0;
var LEAVE_MESSAGES_ON_SERVER = 1;
var KEEP_MESSAGES_X_DAYS = 2;
var DELETE_MESSAGE_WHEN_REMOVED_FROM_TRASH = 3;
var KEEP_AND_DELETE_WHEN_REMOVED_FROM_TRASH = 4;

var Charsets = [
	{ name: Lang.CharsetDefault, value: '0' },
	{ name: Lang.CharsetArabicAlphabetISO, value: '28596' },
	{ name: Lang.CharsetArabicAlphabet, value: '1256' },
	{ name: Lang.CharsetBalticAlphabetISO, value: '28594' },
	{ name: Lang.CharsetBalticAlphabet, value: '1257' },
	{ name: Lang.CharsetCentralEuropeanAlphabetISO, value: '28592' },
	{ name: Lang.CharsetCentralEuropeanAlphabet, value: '1250' },
	{ name: Lang.CharsetChineseSimplifiedEUC, value: '51936' },
	{ name: Lang.CharsetChineseSimplifiedGB, value: '936' },
	{ name: Lang.CharsetChineseTraditional, value: '950' },
	{ name: Lang.CharsetCyrillicAlphabetISO, value: '28595' },
	{ name: Lang.CharsetCyrillicAlphabetKOI8R, value: '20866' },
	{ name: Lang.CharsetCyrillicAlphabet, value: '1251' },
	{ name: Lang.CharsetGreekAlphabetISO, value: '28597' },
	{ name: Lang.CharsetGreekAlphabet, value: '1253' },
	{ name: Lang.CharsetHebrewAlphabetISO, value: '28598' },
	{ name: Lang.CharsetHebrewAlphabet, value: '1255' },
	{ name: Lang.CharsetJapanese, value: '50220' },
	{ name: Lang.CharsetJapaneseShiftJIS, value: '932' },
	{ name: Lang.CharsetKoreanEUC, value: '949' },
	{ name: Lang.CharsetKoreanISO, value: '50225' },
	{ name: Lang.CharsetLatin3AlphabetISO, value: '28593' },
	{ name: Lang.CharsetTurkishAlphabet, value: '1254' },
	{ name: Lang.CharsetUniversalAlphabetUTF7, value: '65000' },
	{ name: Lang.CharsetUniversalAlphabetUTF8, value: '65001' },
	{ name: Lang.CharsetVietnameseAlphabet, value: '1258'},
	{ name: Lang.CharsetWesternAlphabetISO, value: '28591' },
	{ name: Lang.CharsetWesternAlphabet, value: '1252' }
];

var TimeOffsets = [
	{ name: Lang.TimeDefault, value: '0' },
	{ name: '(GMT -12:00) ' + Lang.TimeEniwetok, value: '1' },
	{ name: '(GMT -11:00) ' + Lang.TimeMidwayIsland, value: '2' },
	{ name: '(GMT -10:00) ' + Lang.TimeHawaii, value: '3' },
	{ name: '(GMT -09:00) ' + Lang.TimeAlaska, value: '4' },
	{ name: '(GMT -08:00) ' + Lang.TimePacific, value: '5' },
	{ name: '(GMT -07:00) ' + Lang.TimeArizona, value: '6' },
	{ name: '(GMT -07:00) ' + Lang.TimeMountain, value: '7' },
	{ name: '(GMT -06:00) ' + Lang.TimeCentralAmerica, value: '8' },
	{ name: '(GMT -06:00) ' + Lang.TimeCentral, value: '9' },
	{ name: '(GMT -06:00) ' + Lang.TimeMexicoCity, value: '10' },
	{ name: '(GMT -06:00) ' + Lang.TimeSaskatchewan, value: '11' },
	{ name: '(GMT -05:00) ' + Lang.TimeIndiana, value: '12' },
	{ name: '(GMT -05:00) ' + Lang.TimeEastern, value: '13' },
	{ name: '(GMT -05:00) ' + Lang.TimeBogota, value: '14' },
	{ name: '(GMT -04:00) ' + Lang.TimeSantiago, value: '15' },
	{ name: '(GMT -04:00) ' + Lang.TimeCaracas, value: '16' },
	{ name: '(GMT -04:00) ' + Lang.TimeAtlanticCanada, value: '17' },
	{ name: '(GMT -03:30) ' + Lang.TimeNewfoundland, value: '18' },
	{ name: '(GMT -03:00) ' + Lang.TimeGreenland, value: '19' },
	{ name: '(GMT -03:00) ' + Lang.TimeBuenosAires, value: '20' },
	{ name: '(GMT -03:00) ' + Lang.TimeBrasilia, value: '21' },
	{ name: '(GMT -02:00) ' + Lang.TimeMidAtlantic, value: '22' },
	{ name: '(GMT -01:00) ' + Lang.TimeCapeVerde, value: '23' },
	{ name: '(GMT -01:00) ' + Lang.TimeAzores, value: '24' },
	{ name: '(GMT) ' + Lang.TimeMonrovia, value: '25' },
	{ name: '(GMT) ' + Lang.TimeGMT, value: '26' },
	{ name: '(GMT +01:00) ' + Lang.TimeBerlin, value: '27' },
	{ name: '(GMT +01:00) ' + Lang.TimePrague, value: '28' },
	{ name: '(GMT +01:00) ' + Lang.TimeParis, value: '29' },
	{ name: '(GMT +01:00) ' + Lang.TimeSarajevo, value: '30' },
	{ name: '(GMT +01:00) ' + Lang.TimeWestCentralAfrica, value: '31' },
	{ name: '(GMT +02:00) ' + Lang.TimeAthens, value: '32' },
	{ name: '(GMT +02:00) ' + Lang.TimeEasternEurope, value: '33' },
	{ name: '(GMT +02:00) ' + Lang.TimeCairo, value: '34' },
	{ name: '(GMT +02:00) ' + Lang.TimeHarare, value: '35' },
	{ name: '(GMT +02:00) ' + Lang.TimeHelsinki, value: '36' },
	{ name: '(GMT +02:00) ' + Lang.TimeIsrael, value: '37' },
	{ name: '(GMT +03:00) ' + Lang.TimeBaghdad, value: '38' },
	{ name: '(GMT +03:00) ' + Lang.TimeArab, value: '39' },
	{ name: '(GMT +03:00) ' + Lang.TimeEastAfrica, value: '40' },
	{ name: '(GMT +03:30) ' + Lang.TimeTehran, value: '41' },
	{ name: '(GMT +04:00) ' + Lang.TimeMoscow, value: '42' },
	{ name: '(GMT +04:00) ' + Lang.TimeAbuDhabi, value: '43' },
	{ name: '(GMT +04:00) ' + Lang.TimeCaucasus, value: '44' },
	{ name: '(GMT +04:30) ' + Lang.TimeKabul, value: '45' },
	{ name: '(GMT +05:00) ' + Lang.TimeIslamabad, value: '46' },
	{ name: '(GMT +05:30) ' + Lang.TimeBombay, value: '47' },
	{ name: '(GMT +05:45) ' + Lang.TimeNepal, value: '48' },
	{ name: '(GMT +06:00) ' + Lang.TimeEkaterinburg, value: '49' },
	{ name: '(GMT +06:00) ' + Lang.TimeAlmaty, value: '50' },
	{ name: '(GMT +06:00) ' + Lang.TimeDhaka, value: '51' },
	{ name: '(GMT +06:00) ' + Lang.TimeSriLanka, value: '52' },
	{ name: '(GMT +06:30) ' + Lang.TimeRangoon, value: '53' },
	{ name: '(GMT +07:00) ' + Lang.TimeBangkok, value: '54' },
	{ name: '(GMT +08:00) ' + Lang.TimeKrasnoyarsk, value: '55' },
	{ name: '(GMT +08:00) ' + Lang.TimeBeijing, value: '56' },
	{ name: '(GMT +08:00) ' + Lang.TimeUlaanBataar, value: '57' },
	{ name: '(GMT +08:00) ' + Lang.TimeSingapore, value: '58' },
	{ name: '(GMT +08:00) ' + Lang.TimePerth, value: '59' },
	{ name: '(GMT +08:00) ' + Lang.TimeTaipei, value: '60' },
	{ name: '(GMT +09:00) ' + Lang.TimeTokyo, value: '61' },
	{ name: '(GMT +09:00) ' + Lang.TimeSeoul, value: '62' },
	{ name: '(GMT +09:30) ' + Lang.TimeAdelaide, value: '63' },
	{ name: '(GMT +09:30) ' + Lang.TimeDarwin, value: '64' },
	{ name: '(GMT +10:00) ' + Lang.TimeYakutsk, value: '65' },
	{ name: '(GMT +10:00) ' + Lang.TimeBrisbane, value: '66' },
	{ name: '(GMT +10:00) ' + Lang.TimeSydney, value: '67' },
	{ name: '(GMT +10:00) ' + Lang.TimeGuam, value: '68' },
	{ name: '(GMT +10:00) ' + Lang.TimeHobart, value: '69' },
	{ name: '(GMT +11:00) ' + Lang.TimeVladivostock, value: '70' },
	{ name: '(GMT +11:00) ' + Lang.TimeSolomonIs, value: '71' },
	{ name: '(GMT +12:00) ' + Lang.TimeWellington, value: '72' },
	{ name: '(GMT +12:00) ' + Lang.TimeFiji, value: '73' },
	{ name: '(GMT +13:00) ' + Lang.TimeTonga, value: '74' }
];

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
