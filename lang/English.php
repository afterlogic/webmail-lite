<?php
define('PROC_ERROR_ACCT_CREATE', 'There was an error while creating account');
define('PROC_WRONG_ACCT_PWD', 'Wrong account password');
define('PROC_CANT_LOG_NONDEF', 'Can\'t login into non-default account');
define('PROC_CANT_INS_NEW_FILTER', 'Can\'t insert new filter');
define('PROC_FOLDER_EXIST', 'Folder name already exist');
define('PROC_CANT_CREATE_FLD', 'Can\'t create folder');
define('PROC_CANT_INS_NEW_GROUP', 'Can\'t insert new group');
define('PROC_CANT_INS_NEW_CONT', 'Can\'t insert new contact');
define('PROC_CANT_INS_NEW_CONTS', 'Can\'t insert new contact(s)');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Can\'t add contact(s) into group');
define('PROC_ERROR_ACCT_UPDATE', 'There was an error while updating account');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Can\'t update contacts settings');
define('PROC_CANT_GET_SETTINGS', 'Can\'t get settings');
define('PROC_CANT_UPDATE_ACCT', 'Can\'t update account');
define('PROC_ERROR_DEL_FLD', 'There was an error while deleting folder(s)');
define('PROC_CANT_UPDATE_CONT', 'Can\'t update contact');
define('PROC_CANT_GET_FLDS', 'Can\'t get folders tree');
define('PROC_CANT_GET_MSG_LIST', 'Can\'t get message list');
define('PROC_MSG_HAS_DELETED', 'This message has already been deleted from the mail server');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Can\'t load contacts settings');
define('PROC_CANT_LOAD_SIGNATURE', 'Can\'t load account signature');
define('PROC_CANT_GET_CONT_FROM_DB', 'Can\'t get contact from DB');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Can\'t get contact(s) from DB');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Can\'t delete account');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Can\'t delete filter');
define('PROC_CANT_DEL_CONT_GROUPS', 'Can\'t delete contact(s) and/or groups');
define('PROC_WRONG_ACCT_ACCESS', 'Access to this account is not allowed');
define('PROC_SESSION_ERROR', 'The previous session was ended due to an internal error or timeout.');

define('MailBoxIsFull', 'Mailbox is full');
define('WebMailException', 'Internal Server Error. Please, contact your system administrator in order to report the problem.');
define('InvalidUid', 'Invalid Message UID');
define('CantCreateContactGroup', 'Can\'t create contact group');
define('CantCreateUser', 'Can\'t create user');
define('CantCreateAccount', 'Can\'t create account');
define('SessionIsEmpty', 'Session is empty');
define('FileIsTooBig', 'The file is too big');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Can\'t mark all messages as read');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Can\'t mark all messages as unread');
define('PROC_CANT_PURGE_MSGS', 'Can\'t purge message(s)');
define('PROC_CANT_DEL_MSGS', 'Can\'t delete message(s)');
define('PROC_CANT_UNDEL_MSGS', 'Can\'t undelete message(s)');
define('PROC_CANT_MARK_MSGS_READ', 'Can\'t mark message(s) as read');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Can\'t mark message(s) as unread');
define('PROC_CANT_SET_MSG_FLAGS', 'Can\'t set message flag(s)');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Can\'t remove message flag(s)');
define('PROC_CANT_CHANGE_MSG_FLD', 'Can\'t change message(s) folder');
define('PROC_CANT_SEND_MSG', 'Can\'t send message.');
define('PROC_CANT_SAVE_MSG', 'Can\'t save message.');
define('PROC_CANT_GET_ACCT_LIST', 'Can\'t get account list');
define('PROC_CANT_GET_FILTER_LIST', 'Can\'t get filters list');

define('PROC_CANT_LEAVE_BLANK', 'Please fill all fields marked with *');

define('PROC_CANT_UPD_FLD', 'Can\'t update folder');
define('PROC_CANT_UPD_FILTER', 'Can\'t update filter');

define('ACCT_CANT_ADD_DEF_ACCT', 'This account cannot be added because it\'s used as a default account by another user.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'This account status cannot be changed to default.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Can\'t create new account (IMAP4 connection error)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Can\'t delete last default account');

define('LANG_LoginInfo', 'Login Information');
define('LANG_Email', 'Email');
define('LANG_Login', 'Login');
define('LANG_Password', 'Password');
define('LANG_IncServer', 'Incoming&nbsp;Mail');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'Outgoing&nbsp;Mail');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Use&nbsp;SMTP&nbsp;authentication');
define('LANG_SignMe', 'Sign me in automatically');
define('LANG_Enter', 'Enter');

// interface strings

define('JS_LANG_TitleLogin', 'Login');
define('JS_LANG_TitleMessagesListView', 'Message List');
define('JS_LANG_TitleMessagesList', 'Message List');
define('JS_LANG_TitleViewMessage', 'View Message');
define('JS_LANG_TitleNewMessage', 'New Message');
define('JS_LANG_TitleSettings', 'Settings');
define('JS_LANG_TitleContacts', 'Contacts');

define('JS_LANG_StandardLogin', 'Standard&nbsp;Login');
define('JS_LANG_AdvancedLogin', 'Advanced&nbsp;Login');

define('JS_LANG_InfoWebMailLoading', 'WebMail is loading&hellip;');
define('JS_LANG_Loading', 'Loading&hellip;');
define('JS_LANG_InfoMessagesLoad', 'WebMail is loading message list');
define('JS_LANG_InfoEmptyFolder', 'The folder is empty');
define('JS_LANG_InfoPageLoading', 'The page is still loading&hellip;');
define('JS_LANG_InfoSendMessage', 'The message was sent');
define('JS_LANG_InfoSaveMessage', 'The message was saved');
define('JS_LANG_InfoHaveImported', 'You have imported');
define('JS_LANG_InfoNewContacts', 'new contact(s) into your contacts list.');
define('JS_LANG_InfoToDelete', 'To delete ');
define('JS_LANG_InfoDeleteContent', 'folder you should delete all its contents first.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Deleting non-empty folders is not allowed. To delete such a folder (it has disabled checkbox), delete its contents first.');
define('JS_LANG_InfoRequiredFields', '* required fields');

define('JS_LANG_ConfirmAreYouSure', 'Are you sure?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'The selected message(s) will be PERMANENTLY deleted! Are you sure?');
define('JS_LANG_ConfirmSaveSettings', 'The settings were not saved. Select OK to save.');
define('JS_LANG_ConfirmSaveContactsSettings', 'The contacts settings were not saved. Select OK to save.');
define('JS_LANG_ConfirmSaveAcctProp', 'The account properties were not saved. Select OK to save.');
define('JS_LANG_ConfirmSaveFilter', 'The filters properties were not saved. Select OK to save.');
define('JS_LANG_ConfirmSaveSignature', 'The signature was not saved. Select OK to save.');
define('JS_LANG_ConfirmSavefolders', 'The folders were not saved. Select OK to save.');
define('JS_LANG_ConfirmHtmlToPlain', 'Warning: By changing the formatting of this message from HTML to plain text, you will lose any current formatting in the message. Select OK to continue.');
define('JS_LANG_ConfirmAddFolder', 'Before adding/removing folder it is necessary to apply changes. Select OK to save.');
define('JS_LANG_ConfirmEmptySubject', 'The subject field is empty. Do you wish to continue?');

define('JS_LANG_WarningEmailBlank', 'You cannot leave<br />"Email" field blank.');
define('JS_LANG_WarningLoginBlank', 'You cannot leave<br />"Login" field blank.');
define('JS_LANG_WarningToBlank', 'You cannot leave "To" field blank');
define('JS_LANG_WarningServerPortBlank', 'You cannot leave POP3 and<br />SMTP server/port fields blank.');
define('JS_LANG_WarningEmptySearchLine', 'Empty search line. Please enter substring you need to find.');
define('JS_LANG_WarningMarkListItem', 'Please mark at least one item in the list.');
define('JS_LANG_WarningFolderMove', 'The folder can\'t be moved because this is another level.');
define('JS_LANG_WarningContactNotComplete', 'Please enter email or name.');
define('JS_LANG_WarningGroupNotComplete', 'Please enter group name.');

define('JS_LANG_WarningEmailFieldBlank', 'You cannot leave "Email" field blank.');
define('JS_LANG_WarningIncServerBlank', 'You cannot leave POP3(IMAP4) Server field blank.');
define('JS_LANG_WarningIncPortBlank', 'You cannot leave POP3(IMAP4) Server Port field blank.');
define('JS_LANG_WarningIncLoginBlank', 'You cannot leave POP3(IMAP4) Login field blank.');
define('JS_LANG_WarningIncPortNumber', 'You should specify a positive number in POP3(IMAP4) port field.');
define('JS_LANG_DefaultIncPortNumber', 'Default POP3(IMAP4) port number is 110(143).');
define('JS_LANG_WarningIncPassBlank', 'You cannot leave POP3(IMAP4) Password field blank.');
define('JS_LANG_WarningOutPortBlank', 'You cannot leave SMTP Server Port field blank.');
define('JS_LANG_WarningOutPortNumber', 'You should specify a positive number in SMTP port field.');
define('JS_LANG_WarningCorrectEmail', 'You should specify a correct e-mail.');
define('JS_LANG_DefaultOutPortNumber', 'Default SMTP port number is 25.');

define('JS_LANG_WarningCsvExtention', 'Extention should be .csv');
define('JS_LANG_WarningImportFileType', 'Please select the application that you want to copy your contacts from');
define('JS_LANG_WarningEmptyImportFile', 'Please select a file by clicking the browse button');

define('JS_LANG_WarningContactsPerPage', 'Contacts per page value is positive number');
define('JS_LANG_WarningMessagesPerPage', 'Messages per page value is positive number');
define('JS_LANG_WarningMailsOnServerDays', 'You should specify a positive number in Messages on server days field.');
define('JS_LANG_WarningEmptyFilter', 'Please enter substring');
define('JS_LANG_WarningEmptyFolderName', 'Please enter folder name');

define('JS_LANG_ErrorConnectionFailed', 'Failed to connect');
define('JS_LANG_ErrorRequestFailed', 'The data transfer has not been completed');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'The object XMLHttpRequest is absent');
define('JS_LANG_ErrorWithoutDesc', 'The error without description occured');
define('JS_LANG_ErrorParsing', 'Error while parsing XML.');
define('JS_LANG_ResponseText', 'Response text:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Empty XML packet');
define('JS_LANG_ErrorImportContacts', 'Error while importing contacts');
define('JS_LANG_ErrorNoContacts', 'No contacts for import.');
define('JS_LANG_ErrorCheckMail', 'Receiving messages terminated due to an error. Probably, not all the messages were received.');

define('JS_LANG_LoggingToServer', 'Logging on to server&hellip;');
define('JS_LANG_GettingMsgsNum', 'Getting number of messages');
define('JS_LANG_RetrievingMessage', 'Retrieving message');
define('JS_LANG_DeletingMessage', 'Deleting message');
define('JS_LANG_DeletingMessages', 'Deleting message(s)');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', 'Connection');
define('JS_LANG_Charset', 'Charset');
define('JS_LANG_AutoSelect', 'Auto-Select');

define('JS_LANG_Contacts', 'Contacts');
define('JS_LANG_ClassicVersion', 'Classic Version');
define('JS_LANG_Logout', 'Logout');
define('JS_LANG_Settings', 'Settings');

define('JS_LANG_LookFor', 'Look for: ');
define('JS_LANG_SearchIn', 'Search in: ');
define('JS_LANG_QuickSearch', 'Search the "From", "To" and "Subject" fields only (quicker).');
define('JS_LANG_SlowSearch', 'Search entire messages');
define('JS_LANG_AllMailFolders', 'All Mail Folders');
define('JS_LANG_AllGroups', 'All Groups');

define('JS_LANG_NewMessage', 'New Message');
define('JS_LANG_CheckMail', 'Check Mail');
define('JS_LANG_EmptyTrash', 'Empty Trash');
define('JS_LANG_MarkAsRead', 'Mark As Read');
define('JS_LANG_MarkAsUnread', 'Mark As Unread');
define('JS_LANG_MarkFlag', 'Flag');
define('JS_LANG_MarkUnflag', 'Unflag');
define('JS_LANG_MarkAllRead', 'Mark All Read');
define('JS_LANG_MarkAllUnread', 'Mark All Unread');
define('JS_LANG_Reply', 'Reply');
define('JS_LANG_ReplyAll', 'Reply to All');
define('JS_LANG_Delete', 'Delete');
define('JS_LANG_Undelete', 'Undelete');
define('JS_LANG_PurgeDeleted', 'Purge deleted');
define('JS_LANG_MoveToFolder', 'Move To Folder');
define('JS_LANG_Forward', 'Forward');

define('JS_LANG_HideFolders', 'Hide Folders');
define('JS_LANG_ShowFolders', 'Show Folders');
define('JS_LANG_ManageFolders', 'Manage Folders');
define('JS_LANG_SyncFolder', 'Synchronized folder');
define('JS_LANG_NewMessages', 'New Messages');
define('JS_LANG_Messages', 'Message(s)');

define('JS_LANG_From', 'From');
define('JS_LANG_To', 'To');
define('JS_LANG_Date', 'Date');
define('JS_LANG_Size', 'Size');
define('JS_LANG_Subject', 'Subject');

define('JS_LANG_FirstPage', 'First Page');
define('JS_LANG_PreviousPage', 'Previous Page');
define('JS_LANG_NextPage', 'Next Page');
define('JS_LANG_LastPage', 'Last Page');

define('JS_LANG_SwitchToPlain', 'Switch to Plain Text View');
define('JS_LANG_SwitchToHTML', 'Switch to HTML View');
define('JS_LANG_AddToAddressBook', 'Add to Contacts');
define('JS_LANG_ClickToDownload', 'Click to download ');
define('JS_LANG_View', 'View');
define('JS_LANG_ShowFullHeaders', 'Show Full Headers');
define('JS_LANG_HideFullHeaders', 'Hide Full Headers');

define('JS_LANG_MessagesInFolder', 'Message(s) in Folder');
define('JS_LANG_YouUsing', 'You are using');
define('JS_LANG_OfYour', 'of your');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Send');
define('JS_LANG_SaveMessage', 'Save');
define('JS_LANG_Print', 'Print');
define('JS_LANG_PreviousMsg', 'Previous Message');
define('JS_LANG_NextMsg', 'Next Message');
define('JS_LANG_AddressBook', 'Address Book');
define('JS_LANG_ShowBCC', 'Show BCC');
define('JS_LANG_HideBCC', 'Hide BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Reply&nbsp;To');
define('JS_LANG_AttachFile', 'Attach File');
define('JS_LANG_Attach', 'Attach');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Original Message');
define('JS_LANG_Sent', 'Sent');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Low');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'High');
define('JS_LANG_Importance', 'Importance');
define('JS_LANG_Close', 'Close');

define('JS_LANG_Common', 'Common');
define('JS_LANG_EmailAccounts', 'Email Accounts');

define('JS_LANG_MsgsPerPage', 'Messages per page');
define('JS_LANG_DisableRTE', 'Disable rich-text editor');
define('JS_LANG_Skin', 'Skin');
define('JS_LANG_DefCharset', 'Default charset');
define('JS_LANG_DefCharsetInc', 'Default incoming charset');
define('JS_LANG_DefCharsetOut', 'Default outgoing charset');
define('JS_LANG_DefTimeOffset', 'Time offset');
define('JS_LANG_DefLanguage', 'Language');
define('JS_LANG_DefDateFormat', 'Date format');
define('JS_LANG_ShowViewPane', 'Message list with preview pane');
define('JS_LANG_Save', 'Save');
define('JS_LANG_Cancel', 'Cancel');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Remove');
define('JS_LANG_AddNewAccount', 'Add New Account');
define('JS_LANG_Signature', 'Signature');
define('JS_LANG_Filters', 'Filters');
define('JS_LANG_Properties', 'Properties');
define('JS_LANG_UseForLogin', 'Use this account properties (login and password) for login');
define('JS_LANG_MailFriendlyName', 'Your name');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Incoming Mail');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Password');
define('JS_LANG_MailOutHost', 'Outgoing Mail');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Login');
define('JS_LANG_MailOutPass', 'SMTP Password');
define('JS_LANG_MailOutAuth1', 'Use SMTP authentication');
define('JS_LANG_MailOutAuth2', '(You may leave SMTP login/password fields blank, if they\'re the same as POP3/IMAP4 login/password)');
define('JS_LANG_UseFriendlyNm1', 'Use Friendly Name in "From:" field');
define('JS_LANG_UseFriendlyNm2', '(Your name &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Get/Synchronize Mails at login');
define('JS_LANG_MailMode0', 'Delete received messages from server');
define('JS_LANG_MailMode1', 'Leave messages on server');
define('JS_LANG_MailMode2', 'Keep messages on server for');
define('JS_LANG_MailsOnServerDays', 'day(s)');
define('JS_LANG_MailMode3', 'Delete message from server when it is removed from Trash');
define('JS_LANG_InboxSyncType', 'Type of Inbox Synchronization');

define('JS_LANG_SyncTypeNo', 'Don\'t Synchronize');
define('JS_LANG_SyncTypeNewHeaders', 'New Headers');
define('JS_LANG_SyncTypeAllHeaders', 'All Headers');
define('JS_LANG_SyncTypeNewMessages', 'New Messages');
define('JS_LANG_SyncTypeAllMessages', 'All Messages');
define('JS_LANG_SyncTypeDirectMode', 'Direct Mode');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Headers Only');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Entire Messages');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direct Mode');

define('JS_LANG_DeleteFromDb', 'Delete message from database if it no longer exists on mail server');

define('JS_LANG_EditFilter', 'Edit&nbsp;filter');
define('JS_LANG_NewFilter', 'Add New Filter');
define('JS_LANG_Field', 'Field');
define('JS_LANG_Condition', 'Condition');
define('JS_LANG_ContainSubstring', 'Contain substring');
define('JS_LANG_ContainExactPhrase', 'Contain exact phrase');
define('JS_LANG_NotContainSubstring', 'Not contain substring');
define('JS_LANG_FilterDesc_At', 'at');
define('JS_LANG_FilterDesc_Field', 'field');
define('JS_LANG_Action', 'Action');
define('JS_LANG_DoNothing', 'Do nothing');
define('JS_LANG_DeleteFromServer', 'Delete from server Immediately');
define('JS_LANG_MarkGrey', 'Mark grey');
define('JS_LANG_Add', 'Add');
define('JS_LANG_OtherFilterSettings', 'Other filter settings');
define('JS_LANG_ConsiderXSpam', 'Consider X-Spam headers');
define('JS_LANG_Apply', 'Apply');

define('JS_LANG_InsertLink', 'Insert Link');
define('JS_LANG_RemoveLink', 'Remove Link');
define('JS_LANG_Numbering', 'Numbering');
define('JS_LANG_Bullets', 'Bullets');
define('JS_LANG_HorizontalLine', 'Horizontal Line');
define('JS_LANG_Bold', 'Bold');
define('JS_LANG_Italic', 'Italic');
define('JS_LANG_Underline', 'Underline');
define('JS_LANG_AlignLeft', 'Align Left');
define('JS_LANG_Center', 'Center');
define('JS_LANG_AlignRight', 'Align Right');
define('JS_LANG_Justify', 'Justify');
define('JS_LANG_FontColor', 'Font Color');
define('JS_LANG_Background', 'Background');
define('JS_LANG_SwitchToPlainMode', 'Switch to Plain Text Mode');
define('JS_LANG_SwitchToHTMLMode', 'Switch to HTML Mode');

define('JS_LANG_Folder', 'Folder');
define('JS_LANG_Msgs', 'Msg\'s');
define('JS_LANG_Synchronize', 'Synchronize');
define('JS_LANG_ShowThisFolder', 'Show This Folder');
define('JS_LANG_Total', 'Total');
define('JS_LANG_DeleteSelected', 'Delete Selected');
define('JS_LANG_AddNewFolder', 'Add New Folder');
define('JS_LANG_NewFolder', 'New Folder');
define('JS_LANG_ParentFolder', 'Parent Folder');
define('JS_LANG_NoParent', 'No Parent');
define('JS_LANG_FolderName', 'Folder Name');

define('JS_LANG_ContactsPerPage', 'Contacts per page');
define('JS_LANG_WhiteList', 'Address Book as White List');

define('JS_LANG_CharsetDefault', 'Default');
define('JS_LANG_CharsetArabicAlphabetISO', 'Arabic Alphabet (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Arabic Alphabet (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Baltic Alphabet (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Baltic Alphabet (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Central European Alphabet (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Central European Alphabet (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Chinese Simplified (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Chinese Simplified (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Chinese Traditional (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Cyrillic Alphabet (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Cyrillic Alphabet (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Cyrillic Alphabet (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Greek Alphabet (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Greek Alphabet (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Hebrew Alphabet (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'Hebrew Alphabet (Windows)');
define('JS_LANG_CharsetJapanese', 'Japanese');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japanese (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Korean (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Korean (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latin 3 Alphabet (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Turkish Alphabet');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Universal Alphabet (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Universal Alphabet (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Vietnamese Alphabet (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Western Alphabet (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Western Alphabet (Windows)');

define('JS_LANG_TimeDefault', 'Default');
define('JS_LANG_TimeEniwetok', 'Eniwetok, Kwajalein, Dateline Time');
define('JS_LANG_TimeMidwayIsland', 'Midway Island, Samoa');
define('JS_LANG_TimeHawaii', 'Hawaii');
define('JS_LANG_TimeAlaska', 'Alaska');
define('JS_LANG_TimePacific', 'Pacific Time (US & Canada); Tijuana');
define('JS_LANG_TimeArizona', 'Arizona');
define('JS_LANG_TimeMountain', 'Mountain Time (US & Canada)');
define('JS_LANG_TimeCentralAmerica', 'Central America');
define('JS_LANG_TimeCentral', 'Central Time (US & Canada)');
define('JS_LANG_TimeMexicoCity', 'Mexico City, Tegucigalpa');
define('JS_LANG_TimeSaskatchewan', 'Saskatchewan');
define('JS_LANG_TimeIndiana', 'Indiana (East)');
define('JS_LANG_TimeEastern', 'Eastern Time (US & Canada)');
define('JS_LANG_TimeBogota', 'Bogota, Lima, Quito');
define('JS_LANG_TimeSantiago', 'Santiago');
define('JS_LANG_TimeCaracas', 'Caracas, La Paz');
define('JS_LANG_TimeAtlanticCanada', 'Atlantic Time (Canada)');
define('JS_LANG_TimeNewfoundland', 'Newfoundland');
define('JS_LANG_TimeGreenland', 'Greenland');
define('JS_LANG_TimeBuenosAires', 'Buenos Aires, Georgetown');
define('JS_LANG_TimeBrasilia', 'Brasilia');
define('JS_LANG_TimeMidAtlantic', 'Mid-Atlantic');
define('JS_LANG_TimeCapeVerde', 'Cape Verde Is.');
define('JS_LANG_TimeAzores', 'Azores');
define('JS_LANG_TimeMonrovia', 'Casablanca, Monrovia');
define('JS_LANG_TimeGMT', 'Dublin, Edinburgh, Lisbon, London');
define('JS_LANG_TimeBerlin', 'Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna');
define('JS_LANG_TimePrague', 'Belgrade, Bratislava, Budapest, Ljubljana, Prague');
define('JS_LANG_TimeParis', 'Brussels, Copenhagen, Madrid, Paris');
define('JS_LANG_TimeSarajevo', 'Sarajevo, Skopje, Sofija, Warsaw, Zagreb');
define('JS_LANG_TimeWestCentralAfrica', 'West Central Africa');
define('JS_LANG_TimeAthens', 'Athens, Istanbul, Minsk');
define('JS_LANG_TimeEasternEurope', 'Bucharest');
define('JS_LANG_TimeCairo', 'Cairo');
define('JS_LANG_TimeHarare', 'Harare, Pretoria');
define('JS_LANG_TimeHelsinki', 'Helsinki, Riga, Tallinn, Vilnius');
define('JS_LANG_TimeIsrael', 'Israel, Jerusalem Standard Time');
define('JS_LANG_TimeBaghdad', 'Baghdad');
define('JS_LANG_TimeArab', 'Arab, Kuwait, Riyadh');
define('JS_LANG_TimeMoscow', 'Moscow, St. Petersburg, Volgograd');
define('JS_LANG_TimeEastAfrica', 'East Africa, Nairobi');
define('JS_LANG_TimeTehran', 'Tehran');
define('JS_LANG_TimeAbuDhabi', 'Abu Dhabi, Muscat');
define('JS_LANG_TimeCaucasus', 'Baku, Tbilisi, Yerevan');
define('JS_LANG_TimeKabul', 'Kabul');
define('JS_LANG_TimeEkaterinburg', 'Ekaterinburg');
define('JS_LANG_TimeIslamabad', 'Islamabad, Karachi, Sverdlovsk, Tashkent');
define('JS_LANG_TimeBombay', 'Calcutta, Chennai, Mumbai, New Delhi, India Standard Time');
define('JS_LANG_TimeNepal', 'Kathmandu, Nepal');
define('JS_LANG_TimeAlmaty', 'Almaty, North Central Asia');
define('JS_LANG_TimeDhaka', 'Astana, Dhaka');
define('JS_LANG_TimeSriLanka', 'Sri Jayawardenepura, Sri Lanka');
define('JS_LANG_TimeRangoon', 'Rangoon');
define('JS_LANG_TimeBangkok', 'Bangkok, Novosibirsk, Hanoi, Jakarta');
define('JS_LANG_TimeKrasnoyarsk', 'Krasnoyarsk');
define('JS_LANG_TimeBeijing', 'Beijing, Chongqing, Hong Kong SAR, Urumqi');
define('JS_LANG_TimeUlaanBataar', 'Ulaan Bataar');
define('JS_LANG_TimeSingapore', 'Kuala Lumpur, Singapore');
define('JS_LANG_TimePerth', 'Perth, Western Australia');
define('JS_LANG_TimeTaipei', 'Taipei');
define('JS_LANG_TimeTokyo', 'Osaka, Sapporo, Tokyo, Irkutsk');
define('JS_LANG_TimeSeoul', 'Seoul, Korea Standard time');
define('JS_LANG_TimeYakutsk', 'Yakutsk');
define('JS_LANG_TimeAdelaide', 'Adelaide, Central Australia');
define('JS_LANG_TimeDarwin', 'Darwin');
define('JS_LANG_TimeBrisbane', 'Brisbane, East Australia');
define('JS_LANG_TimeSydney', 'Canberra, Melbourne, Sydney, Hobart');
define('JS_LANG_TimeGuam', 'Guam, Port Moresby');
define('JS_LANG_TimeHobart', 'Hobart, Tasmania');
define('JS_LANG_TimeVladivostock', 'Vladivostok');
define('JS_LANG_TimeSolomonIs', 'Solomon Is., New Caledonia');
define('JS_LANG_TimeWellington', 'Auckland, Wellington, Magadan');
define('JS_LANG_TimeFiji', 'Fiji Islands, Kamchatka, Marshall Is.');
define('JS_LANG_TimeTonga', 'Nuku\'alofa, Tonga');

define('JS_LANG_DateDefault', 'Default');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Jan)');
define('JS_LANG_DateAdvanced', 'Advanced');

define('JS_LANG_NewContact', 'New Contact');
define('JS_LANG_NewGroup', 'New Group');
define('JS_LANG_AddContactsTo', 'Add Contacts to');
define('JS_LANG_ImportContacts', 'Import Contacts');

define('JS_LANG_Name', 'Name');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Email');
define('JS_LANG_NotSpecifiedYet', 'Not specified yet');
define('JS_LANG_ContactName', 'Name');
define('JS_LANG_Birthday', 'Birthday');
define('JS_LANG_Month', 'Month');
define('JS_LANG_January', 'January');
define('JS_LANG_February', 'February');
define('JS_LANG_March', 'March');
define('JS_LANG_April', 'April');
define('JS_LANG_May', 'May');
define('JS_LANG_June', 'June');
define('JS_LANG_July', 'July');
define('JS_LANG_August', 'August');
define('JS_LANG_September', 'September');
define('JS_LANG_October', 'October');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'December');
define('JS_LANG_Day', 'Day');
define('JS_LANG_Year', 'Year');
define('JS_LANG_UseFriendlyName1', 'Use Friendly Name');
define('JS_LANG_UseFriendlyName2', '(for example, John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Personal');
define('JS_LANG_PersonalEmail', 'Personal E-mail');
define('JS_LANG_StreetAddress', 'Street Address');
define('JS_LANG_City', 'City');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'State/Province');
define('JS_LANG_Phone', 'Phone');
define('JS_LANG_ZipCode', 'Zip Code');
define('JS_LANG_Mobile', 'Mobile');
define('JS_LANG_CountryRegion', 'Country/Region');
define('JS_LANG_WebPage', 'Web Page');
define('JS_LANG_Go', 'Go');
define('JS_LANG_Home', 'Home');
define('JS_LANG_Business', 'Business');
define('JS_LANG_BusinessEmail', 'Business E-mail');
define('JS_LANG_Company', 'Company');
define('JS_LANG_JobTitle', 'Job Title');
define('JS_LANG_Department', 'Department');
define('JS_LANG_Office', 'Office');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Other');
define('JS_LANG_OtherEmail', 'Other E-mail');
define('JS_LANG_Notes', 'Notes');
define('JS_LANG_Groups', 'Groups');
define('JS_LANG_ShowAddFields', 'Show additional fields');
define('JS_LANG_HideAddFields', 'Hide additional fields');
define('JS_LANG_EditContact', 'Edit contact information');
define('JS_LANG_GroupName', 'Group Name');
define('JS_LANG_AddContacts', 'Add Contacts');
define('JS_LANG_CommentAddContacts', '(If you\'re going to specify more than one address, please separate them with commas)');
define('JS_LANG_CreateGroup', 'Create Group');
define('JS_LANG_Rename', 'rename');
define('JS_LANG_MailGroup', 'Mail Group');
define('JS_LANG_RemoveFromGroup', 'Remove from group');
define('JS_LANG_UseImportTo', 'Use Import to copy your contacts from Microsoft Outlook, Microsoft Outlook Express into your AfterLogic WebMail contacts list.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Select the file (.CSV format) that you want to import');
define('JS_LANG_Import', 'Import');
define('JS_LANG_ContactsMessage', 'This is contacts page!!!');
define('JS_LANG_ContactsCount', 'contact(s)');
define('JS_LANG_GroupsCount', 'group(s)');

// webmail 4.1 constants
define('PicturesBlocked', 'Pictures in this message have been blocked for your safety.');
define('ShowPictures', 'Show pictures');
define('ShowPicturesFromSender', 'Always show pictures in messages from this sender');
define('AlwaysShowPictures', 'Always show pictures in messages');
define('TreatAsOrganization', 'Treat as an organization');

define('WarningGroupAlreadyExist', 'Group with such name already exists. Please specify another name.');
define('WarningCorrectFolderName', 'You should specify a correct folder name.');
define('WarningLoginFieldBlank', 'You cannot leave "Login" field blank.');
define('WarningCorrectLogin', 'You should specify a correct login.');
define('WarningPassBlank', 'You cannot leave "Password" field blank.');
define('WarningCorrectIncServer', 'You should specify a correct POP3(IMAP) server address.');
define('WarningCorrectSMTPServer', 'You should specify a correct Outgoing Mail address.');
define('WarningFromBlank', 'You cannot leave "From" field blank.');
define('WarningAdvancedDateFormat', 'Please specify a date-time format.');

define('AdvancedDateHelpTitle', 'Advanced Date');
define('AdvancedDateHelpIntro', 'When the &quot;Advanced&quot; field is selected, you can use the text box to set your own date format, which would be displayed in AfterLogic WebMail Pro. The following options are used for this purpose along with \':\' or \'/\' delimiter char:');
define('AdvancedDateHelpConclusion', 'For instance, if you\'ve specified &quot;mm/dd/yyyy&quot; value in the text box of &quot;Advanced&quot; field, the date is displayed as month/day/year (i.e. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Day of month (1 through 31)');
define('AdvancedDateHelpNumericMonth', 'Month (1 through 12)');
define('AdvancedDateHelpTextualMonth', 'Month (Jan through Dec)');
define('AdvancedDateHelpYear2', 'Year, 2 digits');
define('AdvancedDateHelpYear4', 'Year, 4 digits');
define('AdvancedDateHelpDayOfYear', 'Day of year (1 through 366)');
define('AdvancedDateHelpQuarter', 'Quarter');
define('AdvancedDateHelpDayOfWeek', 'Day of week (Mon through Sun)');
define('AdvancedDateHelpWeekOfYear', 'Week of year (1 through 53)');

define('InfoNoMessagesFound', 'No messages found.');
define('ErrorSMTPConnect', 'Can\'t connect to SMTP server. Check SMTP server settings.');
define('ErrorSMTPAuth', 'Wrong username and/or password. Authentication failed.');
define('ReportMessageSent', 'Your message has been sent.');
define('ReportMessageSaved', 'Your message has been saved.');
define('ErrorPOP3Connect', 'Can\'t connect to POP3 server, check POP3 server settings.');
define('ErrorIMAP4Connect', 'Can\'t connect to IMAP4 server, check IMAP4 server settings.');
define('ErrorPOP3IMAP4Auth', 'The username or password you entered is incorrect.');
define('ErrorGetMailLimit', 'Sorry, your mailbox size limit is exceeded.');

define('ReportSettingsUpdatedSuccessfuly', 'Settings have been updated successfully.');
define('ReportAccountCreatedSuccessfuly', 'Account has been created successfully.');
define('ReportAccountUpdatedSuccessfuly', 'Account has been updated successfully.');
define('ConfirmDeleteAccount', 'Are you sure you want to delete account?');
define('ReportFiltersUpdatedSuccessfuly', 'Filters have been updated successfully.');
define('ReportSignatureUpdatedSuccessfuly', 'Signature has been updated successfully.');
define('ReportFoldersUpdatedSuccessfuly', 'Folders have been updated successfully.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Contacts\' settings have been updated successfully.');

define('ErrorInvalidCSV', 'CSV file you selected has invalid format.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'The group');
define('ReportGroupSuccessfulyAdded2', 'was successfully added.');
define('ReportGroupUpdatedSuccessfuly', 'Group has been updated successfully.');
define('ReportContactSuccessfulyAdded', 'Contact was successfully added.');
define('ReportContactUpdatedSuccessfuly', 'Contact has been updated successfully.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Contact(s) was added to group');
define('AlertNoContactsGroupsSelected', 'No contacts or groups selected.');

define('InfoListNotContainAddress', 'If the list doesn\'t contain the address you\'re looking for, keep typing its first chars.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direct Mode. WebMail accesses messages directly on mail server.');

define('FolderInbox', 'Inbox');
define('FolderSentItems', 'Sent Items');
define('FolderDrafts', 'Drafts');
define('FolderTrash', 'Trash');

define('FileLargerAttachment', 'The file size exceeds Attachment Size limit.');
define('FilePartiallyUploaded', 'Only a part of the file was uploaded due to an unknown error.');
define('NoFileUploaded', 'No file was uploaded.');
define('MissingTempFolder', 'The temporary folder is missing.');
define('MissingTempFile', 'The temporary file is missing.');
define('UnknownUploadError', 'An unknown file upload error occurred.');
define('FileLargerThan', 'File upload error. Most probably, the file is larger than ');
define('PROC_CANT_LOAD_DB', 'Can\'t connect to database.');
define('PROC_CANT_LOAD_LANG', 'Can\'t find required language file.');
define('PROC_CANT_LOAD_ACCT', 'The account doesn\'t exist, perhaps, it has just been deleted.');

define('DomainDosntExist', 'Such domain doesn\'t exist on mail server.');
define('ServerIsDisable', 'Using mail server is prohibited by administrator.');

define('PROC_ACCOUNT_EXISTS', 'The account cannot be created because it already exists.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Can\'t get folder message count.');
define('PROC_CANT_MAIL_SIZE', 'Can\'t get mail storage size.');

define('Organization', 'Organization');
define('WarningOutServerBlank', 'You cannot leave "Outgoing Mail" field blank.');

define('JS_LANG_Refresh', 'Refresh');
define('JS_LANG_MessagesInInbox', 'Message(s) in Inbox');
define('JS_LANG_InfoEmptyInbox', 'Inbox is empty');

// webmail 4.2 constants
define('BackToList', 'Back to List');
define('InfoNoContactsGroups', 'No contacts or groups.');
define('InfoNewContactsGroups', 'Create new contacts/groups or import contacts from a .CSV file in MS Outlook format.');
define('DefTimeFormat', 'Time format');
define('SpellNoSuggestions', 'No suggestions');
define('SpellWait', 'Please wait&hellip;');

define('InfoNoMessageSelected', 'No message selected.');
define('InfoSingleDoubleClick', 'Click any message in the list to preview it here or double-click to view it full size.');

// calendar
define('TitleDay', 'Day View');
define('TitleWeek', 'Week View');
define('TitleMonth', 'Month View');

define('ErrorNotSupportBrowser', 'AfterLogic Calendar doesn\'t support your browser. Please use FireFox 2.0 or higher, Opera 9.0 or      higher, Internet Explorer 6.0 or higher, Safari 3.0.2 or higher.');
define('ErrorTurnedOffActiveX', 'ActiveX support is turned off . <br/>You should turn it on in order to use this application.');

define('Calendar', 'Calendar');

define('TabDay', 'Day');
define('TabWeek', 'Week');
define('TabMonth', 'Month');

define('ToolNewEvent', 'New&nbsp;Event');
define('ToolBack', 'Back');
define('ToolToday', 'Today');
define('AltNewEvent', 'New Event');
define('AltBack', 'Back');
define('AltToday', 'Today');
define('CalendarHeader', 'Calendar');
define('CalendarsManager', 'Calendars Manager');

define('CalendarActionNew', 'New calendar');
define('EventHeaderNew', 'New Event');
define('CalendarHeaderNew', 'New Calendar');

define('EventSubject', 'Subject');
define('EventCalendar', 'Calendar');
define('EventFrom', 'From');
define('EventTill', 'till');
define('CalendarDescription', 'Description');
define('CalendarColor', 'Color');
define('CalendarName', 'Calendar Name');
define('CalendarDefaultName', 'My Calendar');

define('ButtonSave', 'Save');
define('ButtonCancel', 'Cancel');
define('ButtonDelete', 'Delete');

define('AltPrevMonth', 'Prev Month');
define('AltNextMonth', 'Next Month');

define('CalendarHeaderEdit', 'Edit Calendar');
define('CalendarActionEdit', 'Edit Calendar');
define('ConfirmDeleteCalendar', 'Are you sure you want to delete calendar');
define('InfoDeleting', 'Deleting&hellip;');
define('WarningCalendarNameBlank', 'You cannot leave the calendar name blank.');
define('ErrorCalendarNotCreated', 'Calendar not created.');
define('WarningSubjectBlank', 'You cannot leave the subject blank.');
define('WarningIncorrectTime', 'The specified time contains illegal characters.');
define('WarningIncorrectFromTime', 'The "From" time is incorrect.');
define('WarningIncorrectTillTime', 'The "Till" time is incorrect.');
define('WarningStartEndDate', 'The end date must be greater or equal to the start date.');
define('WarningStartEndTime', 'The end time must be greater than the start time.');
define('WarningIncorrectDate', 'The date must be correct.');
define('InfoLoading', 'Loading&hellip;');
define('EventCreate', 'Create event');
define('CalendarHideOther', 'Hide other calendars');
define('CalendarShowOther', 'Show other calendars');
define('CalendarRemove', 'Remove Calendar');
define('EventHeaderEdit', 'Edit Event');

define('InfoSaving', 'Saving&hellip;');
define('SettingsDisplayName', 'Display Name');
define('SettingsTimeFormat', 'Time Format');
define('SettingsDateFormat', 'Date Format');
define('SettingsShowWeekends', 'Show weekends');
define('SettingsWorkdayStarts', 'Workday starts');
define('SettingsWorkdayEnds', 'ends');
define('SettingsShowWorkday', 'Show workday');
define('SettingsWeekStartsOn', 'Week starts on');
define('SettingsDefaultTab', 'Default Tab');
define('SettingsCountry', 'Country');
define('SettingsTimeZone', 'Time Zone');
define('SettingsAllTimeZones', 'All time zones');

define('WarningWorkdayStartsEnds', 'The \'Workday ends\' time must be greater than the \'Workday starts\' time');
define('ReportSettingsUpdated', 'Settings have been updated successfully.');

define('SettingsTabCalendar', 'Calendar');

define('FullMonthJanuary', 'January');
define('FullMonthFebruary', 'February');
define('FullMonthMarch', 'March');
define('FullMonthApril', 'April');
define('FullMonthMay', 'May');
define('FullMonthJune', 'June');
define('FullMonthJuly', 'July');
define('FullMonthAugust', 'August');
define('FullMonthSeptember', 'September');
define('FullMonthOctober', 'October');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'December');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Apr');
define('ShortMonthMay', 'May');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Aug');
define('ShortMonthSeptember', 'Sep');
define('ShortMonthOctober', 'Oct');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dec');

define('FullDayMonday', 'Monday');
define('FullDayTuesday', 'Tuesday');
define('FullDayWednesday', 'Wednesday');
define('FullDayThursday', 'Thursday');
define('FullDayFriday', 'Friday');
define('FullDaySaturday', 'Saturday');
define('FullDaySunday', 'Sunday');

define('DayToolMonday', 'Mon');
define('DayToolTuesday', 'Tue');
define('DayToolWednesday', 'Wed');
define('DayToolThursday', 'Thu');
define('DayToolFriday', 'Fri');
define('DayToolSaturday', 'Sat');
define('DayToolSunday', 'Sun');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'T');
define('CalendarTableDayWednesday', 'W');
define('CalendarTableDayThursday', 'T');
define('CalendarTableDayFriday', 'F');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'S');

define('ErrorParseJSON', 'The JSON response returned by the server cannot be parsed.');

define('ErrorLoadCalendar', 'Unable to load calendars');
define('ErrorLoadEvents', 'Unable to load events');
define('ErrorUpdateEvent', 'Unable to save event');
define('ErrorDeleteEvent', 'Unable to delete event');
define('ErrorUpdateCalendar', 'Unable to save calendar');
define('ErrorDeleteCalendar', 'Unable to delete calendar');
define('ErrorGeneral', 'An error occured on the server. Try again later.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Share and publish calendar');
define('ShareActionEdit', 'Share and publish calendar');
define('CalendarPublicate', 'Make public web access to this calendar');
define('CalendarPublicationLink', 'Web link');
define('ShareCalendar', 'Share this calendar');
define('SharePermission1', 'Can make changes and manage sharing');
define('SharePermission2', 'Can make changes to events');
define('SharePermission3', 'Can see all event details');
define('SharePermission4', 'Can see only free/busy (hide details)');
define('ButtonClose', 'Close');
define('WarningEmailFieldFilling', 'You should fill e-mail field first');
define('EventHeaderView', 'View Event');
define('ErrorUpdateSharing', 'Unable to save sharing and publication data');
define('ErrorUpdateSharing1', 'Not possible to share to %s user as it doesn\'t exist');
define('ErrorUpdateSharing2', 'Imposible to share this calendar to user %s');
define('ErrorUpdateSharing3', 'This calendar already shared to user %s');
define('Title_MyCalendars', 'My calendars');
define('Title_SharedCalendars', 'Shared calendars');
define('ErrorGetPublicationHash', 'Unable to create publication link');
define('ErrorGetSharing', 'Unable to add sharing');
define('CalendarPublishedTitle', 'This calendar is published');
define('RefreshSharedCalendars', 'Refresh Shared Calendars');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Members');

define('ReportMessagePartDisplayed', 'Take note that just a part of the message is displayed.');
define('ReportViewEntireMessage', 'To view the entire message,');
define('ReportClickHere', 'click here');
define('ErrorContactExists', 'A contact with such name and e-mail already exists.');

define('Attachments', 'Attachments');

define('InfoGroupsOfContact', 'The groups the contact is member of are marked with checkmarks.');
define('AlertNoContactsSelected', 'No contacts selected.');
define('MailSelected', 'Mail selected addresses');
define('CaptionSubscribed', 'Subscribed');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Not Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Send mail');
define('ContactViewAllMails', 'View all mails with this contact');
define('ContactsMailThem', 'Mail them');
define('DateToday', 'Today');
define('DateYesterday', 'Yesterday');
define('MessageShowDetails', 'Show details');
define('MessageHideDetails', 'Hide details');
define('MessageNoSubject', 'No subject');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'to');
define('SearchClear', 'Clear search');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Search results for "#s" in #f folder:');
define('SearchResultsInAllFolders', 'Search results for "#s" in all mail folders:');
define('AutoresponderTitle', 'Autoresponder');
define('AutoresponderEnable', 'Enable autoresponder');
define('AutoresponderSubject', 'Subject');
define('AutoresponderMessage', 'Message');
define('ReportAutoresponderUpdatedSuccessfuly', 'Autoresponder has been updated successfully.');
define('FolderQuarantine', 'Quarantine');

//calendar
define('EventRepeats', 'Repeats');
define('NoRepeats', 'Does not repeat');
define('DailyRepeats', 'Daily');
define('WorkdayRepeats', 'Every weekday (Mon. - Fri.)');
define('OddDayRepeats', 'Every Mon., Wed. and Fri.');
define('EvenDayRepeats', 'Every Tues. and Thurs.');
define('WeeklyRepeats', 'Weekly');
define('MonthlyRepeats', 'Monthly');
define('YearlyRepeats', 'Yearly');
define('RepeatsEvery', 'Repeats every');
define('ThisInstance', 'Only this instance');
define('AllEvents', 'All events in the series');
define('AllFollowing', 'All following');
define('ConfirmEditRepeatEvent', 'Would you like to change only this event or all events in the series?');
define('RepeatEventHeaderEdit', 'Edit Recurring Event');
define('First', 'First');
define('Second', 'Second');
define('Third', 'Third');
define('Fourth', 'Fourth');
define('Last', 'Last');
define('Every', 'Every');
define('SetRepeatEventEnd', 'Set end date');
define('NoEndRepeatEvent', 'No end date');
define('EndRepeatEventAfter', 'End after');
define('Occurrences', 'occurrences');
define('EndRepeatEventBy', 'End by');
define('EventCommonDataTab', 'Main details');
define('EventRepeatDataTab', 'Recurrence details');
define('RepeatEventNotPartOfASeries', 'This event has been changed and is no longer part of a series.');
define('UndoRepeatExclusion', 'Undo changes to include in the series.');

define('MonthMoreLink', '%d more...');
define('NoNewSharedCalendars', 'No new calendars');
define('NNewSharedCalendars', '%d new calendars found');
define('OneNewSharedCalendars', '1 new calendar found');
define('ConfirmUndoOneRepeat', 'Would you like to restore this event in the series?');

define('RepeatEveryDayInfin', 'Every day');
define('RepeatEveryDayTimes', 'Every day, %TIMES% times');
define('RepeatEveryDayUntil', 'Every day, until %UNTIL%');
define('RepeatDaysInfin', 'Every %PERIOD% days');
define('RepeatDaysTimes', 'Every %PERIOD% days, %TIMES% times');
define('RepeatDaysUntil', 'Every %PERIOD% days, until %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Every week on weekdays');
define('RepeatEveryWeekWeekdaysTimes', 'Every week on weekdays, %TIMES% times');
define('RepeatEveryWeekWeekdaysUntil', 'Every week on weekdays, until %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Every %PERIOD% weeks on weekdays');
define('RepeatWeeksWeekdaysTimes', 'Every %PERIOD% weeks on weekdays, %TIMES% times');
define('RepeatWeeksWeekdaysUntil', 'Every %PERIOD% weeks on weekdays, until %UNTIL%');

define('RepeatEveryWeekInfin', 'Every week on %DAYS%');
define('RepeatEveryWeekTimes', 'Every week on %DAYS%, %TIMES% times');
define('RepeatEveryWeekUntil', 'Every week on %DAYS%, until %UNTIL%');
define('RepeatWeeksInfin', 'Every %PERIOD% weeks on %DAYS%');
define('RepeatWeeksTimes', 'Every %PERIOD% weeks on %DAYS%, %TIMES% times');
define('RepeatWeeksUntil', 'Every %PERIOD% weeks on %DAYS%, until %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Every month on day %DATE%');
define('RepeatEveryMonthDateTimes', 'Every month on day %DATE%, %TIMES% times');
define('RepeatEveryMonthDateUntil', 'Every month on day %DATE%, until %UNTIL%');
define('RepeatMonthsDateInfin', 'Every %PERIOD% months on day %DATE%');
define('RepeatMonthsDateTimes', 'Every %PERIOD% months on day %DATE%, %TIMES% times');
define('RepeatMonthsDateUntil', 'Every %PERIOD% months on day %DATE%, until %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Every month on %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Every month on %NUMBER% %DAY%, %TIMES% times');
define('RepeatEveryMonthWDUntil', 'Every month on %NUMBER% %DAY%, until %UNTIL%');
define('RepeatMonthsWDInfin', 'Every %PERIOD% months on %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Every %PERIOD% months on %NUMBER% %DAY%, %TIMES% times');
define('RepeatMonthsWDUntil', 'Every %PERIOD% months on %NUMBER% %DAY%, until %UNTIL%');

define('RepeatEveryYearDateInfin', 'Every year on day %DATE%');
define('RepeatEveryYearDateTimes', 'Every year on day %DATE%, %TIMES% times');
define('RepeatEveryYearDateUntil', 'Every year on day %DATE%, until %UNTIL%');
define('RepeatYearsDateInfin', 'Every %PERIOD% years on day %DATE%');
define('RepeatYearsDateTimes', 'Every %PERIOD% years on day %DATE%, %TIMES% times');
define('RepeatYearsDateUntil', 'Every %PERIOD% years on day %DATE%, until %UNTIL%');

define('RepeatEveryYearWDInfin', 'Every year on %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Every year on %NUMBER% %DAY%, %TIMES% times');
define('RepeatEveryYearWDUntil', 'Every year on %NUMBER% %DAY%, until %UNTIL%');
define('RepeatYearsWDInfin', 'Every %PERIOD% years on %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Every %PERIOD% years on %NUMBER% %DAY%, %TIMES% times');
define('RepeatYearsWDUntil', 'Every %PERIOD% years on %NUMBER% %DAY%, until %UNTIL%');

define('RepeatDescDay', 'day');
define('RepeatDescWeek', 'week');
define('RepeatDescMonth', 'month');
define('RepeatDescYear', 'year');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Please specify end recurrence date');
define('WarningWrongUntilDate', 'End recurrence date must be later than the start recurrence date');

define('OnDays', 'On days');
define('CancelRecurrence', 'Cancel recurrence');
define('RepeatEvent', 'Repeat this event');

define('Spellcheck', 'Check Spelling');
define('LoginLanguage', 'Language');
define('LanguageDefault', 'Default');

// webmail 4.5.x new
define('EmptySpam', 'Empty Spam');
define('Saving', 'Saving&hellip;');
define('Sending', 'Sending&hellip;');
define('LoggingOffFromServer', 'Logging off from server&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Can\'t mark message(s) as spam');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Can\'t mark message(s) as non-spam');
define('ExportToICalendar', 'Export to iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'User couldn\'t be created because max number of users allowed by your license exceeded.');
define('RepliedMessageTitle', 'Replied Message');
define('ForwardedMessageTitle', 'Forwarded Message');
define('RepliedForwardedMessageTitle', 'Replied and Forwarded Message');
define('ErrorDomainExist', 'The user cannot be created because corresponding domain doesn\'t exist. You should create the domain first.');

// webmail 4.7
define('RequestReadConfirmation', 'Reading confirmation');
define('FolderTypeDefault', 'Default');
define('ShowFoldersMapping', 'Let me use another folder as a system folder (e.g. use MyFolder as Sent Items)');
define('ShowFoldersMappingNote', 'For instance, to change Sent Items location from Sent Items to MyFolder, specify "Sent Items" in "Use for" dropdown of "MyFolder".');
define('FolderTypeMapTo', 'Use for');

define('ReminderEmailExplanation', 'This message arrived to your %EMAIL% account because you ordered event notification in your %CALENDAR_NAME% calendar.');
define('ReminderOpenCalendar', 'Open calendar');

define('AddReminder', 'Remind me about this event');
define('AddReminderBefore', 'Remind me % before this event');
define('AddReminderAnd', 'and % before');
define('AddReminderAlso', 'and also % before');
define('AddMoreReminder', 'More reminders');
define('RemoveAllReminders', 'Remove all reminders');
define('ReminderNone', 'None');
define('ReminderMinutes', 'minutes');
define('ReminderHour', 'hour');
define('ReminderHours', 'hours');
define('ReminderDay', 'day');
define('ReminderDays', 'days');
define('ReminderWeek', 'week');
define('ReminderWeeks', 'weeks');
define('Allday', 'All day');

define('Folders', 'Folders');
define('NoSubject', 'No subject');
define('SearchResultsFor', 'Search results for');

define('Back', 'Back');
define('Next', 'Next');
define('Prev', 'Prev');

define('MsgList', 'Messages');
define('Use24HTimeFormat', 'Use 24 hour time format');
define('UseCalendars', 'Use calendars');
define('Event', 'Event');
define('CalendarSettingsNullLine', 'No calendars');
define('CalendarEventNullLine', 'No events');
define('ChangeAccount', 'Change account');

define('TitleCalendar', 'Calendar');
define('TitleEvent', 'Event');
define('TitleFolders', 'Folders');
define('TitleConfirmation', 'Confirmation');

define('Yes', 'Yes');
define('No', 'No');

define('EditMessage', 'Edit Message');

define('AccountNewPassword', 'New password');
define('AccountConfirmNewPassword', 'Confirm new password');
define('AccountPasswordsDoNotMatch', 'Passwords do not match.');

define('ContactTitle', 'Title');
define('ContactFirstName', 'First name');
define('ContactSurName', 'Last name');

define('ContactNickName', 'Nickname');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'reload');
define('CaptchaError', 'Captcha text is incorrect.');

define('WarningInputCorrectEmails', 'Please specify correct emails.');
define('WrongEmails', 'Incorrect emails:');

define('ConfirmBodySize1', 'Sorry, but text messages are max.');
define('ConfirmBodySize2', 'characters long. Everything beyond the limit will be truncated. Click "Cancel" if you want to edit the message.');
define('BodySizeCounter', 'Counter');
define('InsertImage', 'Insert Image');
define('ImagePath', 'Image Path');
define('ImageUpload', 'Insert');
define('WarningImageUpload', 'The file being attached is not an image. Please choose an image file.');

define('ConfirmExitFromNewMessage', 'If you navigate away from this page without saving, you will lose all changes made since your last save. Click cancel to stay on the current page.');

define('SensivityConfidential', 'Please treat this message as Confidential');
define('SensivityPrivate', 'Please treat this message as Private');
define('SensivityPersonal', 'Please treat this message as Personal');

define('ReturnReceiptTopText', 'The sender of this message has asked to be notified when you receive this message.');
define('ReturnReceiptTopLink', 'Click here to notify the sender.');
define('ReturnReceiptSubject', 'Return Receipt (displayed)');
define('ReturnReceiptMailText1', 'This is a Return Receipt for the mail that you sent to');
define('ReturnReceiptMailText2', 'Note: This Return Receipt only acknowledges that the message was displayed on the recipient\'s computer. There is no guarantee that the recipient has read or understood the message contents.');
define('ReturnReceiptMailText3', 'with subject');

define('SensivityMenu', 'Sensitivity');
define('SensivityNothingMenu', 'Nothing');
define('SensivityConfidentialMenu', 'Confidential');
define('SensivityPrivateMenu', 'Private');
define('SensivityPersonalMenu', 'Personal');

define('ErrorLDAPonnect', 'Can\'t connect to ldap server.');

define('MessageSizeExceedsAccountQuota', 'This message size exceeds your account quota.');
define('MessageCannotSent', 'The message cannot be sent.');
define('MessageCannotSaved', 'The message cannot be saved.');

define('ContactFieldTitle', 'Field');
define('ContactDropDownTO', 'TO');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Message(s) can\'t be moved to Trash. Most likely your message box is full. Should this unmoved message(s) be deleted?');

define('WarningFieldBlank', 'This field cannot be empty.');
define('WarningPassNotMatch', 'Passwords do not match, please check.');
define('PasswordResetTitle', 'Password recovery - step %d');
define('NullUserNameonReset', 'user');
define('IndexResetLink', 'Forgot password?');
define('IndexRegLink', 'Account Registration');

define('RegDomainNotExist', 'Domain does not exist.');
define('RegAnswersIncorrect', 'Answers are incorrect.');
define('RegUnknownAdress', 'Unknown email address.');
define('RegUnrecoverableAccount', 'Password recovery cannot be applied for this email address.');
define('RegAccountExist', 'This address is already used.');
define('RegRegistrationTitle', 'Registration');
define('RegName', 'Name');
define('RegEmail', 'e-mail address');
define('RegEmailDesc', 'For example, myname@domain.com. This information will be used to enter the system.');
define('RegSignMe', 'Remember me');
define('RegSignMeDesc', 'Do not ask for login and password on next login to the system on this PC.');
define('RegPass1', 'Password');
define('RegPass2', 'Repeat password ');
define('RegQuestionDesc', 'Please, provide two secret questions and answers which know only you. In case of password lost you can use these questions in order to recover the password.');
define('RegQuestion1', 'Secret question 1');
define('RegAnswer1', 'Answer 1');
define('RegQuestion2', 'Secret question 2');
define('RegAnswer2', 'Answer 2');
define('RegTimeZone', 'Time zone');
define('RegLang', 'Interface language');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Register');

define('ResetEmail', 'Please provide your email');
define('ResetEmailDesc', 'Provide emails address used for registration.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Send');
define('ResetQuestion1', 'Secret question 1');
define('ResetAnswer1', 'Answer');
define('ResetQuestion2', 'Secret question 2');
define('ResetAnswer2', 'Answer');
define('ResetSubmitStep2', 'Send');

define('ResetTopDesc1Step2', 'Providede email address');
define('ResetTopDesc2Step2', 'Please confirm correctness.');

define('ResetTopDescStep3', 'please specify below new password for your email.');

define('ResetPass1', 'New password');
define('ResetPass2', 'Repeat password');
define('ResetSubmitStep3', 'Send');
define('ResetDescStep4', 'Your password has been changed.');
define('ResetSubmitStep4', 'Return');

define('RegReturnLink', 'Return to login screen');
define('ResetReturnLink', 'Return to login screen');

// Appointments
define('AppointmentAddGuests', 'Add guests');
define('AppointmentRemoveGuests', 'Cancel Meeting');
define('AppointmentListEmails', 'Enter email addresses separated by commas and press Save');
define('AppointmentParticipants', 'Participants');
define('AppointmentRefused', 'Refused');
define('AppointmentAwaitingResponse', 'Awaiting response');
define('AppointmentInvalidGuestEmail', 'The following guest email addresses are invalid:');
define('AppointmentOwner', 'Owner');

define('AppointmentMsgTitleInvite', 'Invite to event.');
define('AppointmentMsgTitleUpdate', 'Event was modified.');
define('AppointmentMsgTitleCancel', 'Event was cancelled.');
define('AppointmentMsgTitleRefuse', 'Guest %guest% is refuse invitation');
define('AppointmentMoreInfo', 'More info');
define('AppointmentOrganizer', 'Organizer');
define('AppointmentEventInformation', 'Event information');
define('AppointmentEventWhen', 'When');
define('AppointmentEventParticipants', 'Participants');
define('AppointmentEventDescription', 'Description');
define('AppointmentEventWillYou', 'Will you participate');
define('AppointmentAdditionalParameters', 'Additional parameters');
define('AppointmentHaventRespond', 'Not responded yet');
define('AppointmentRespondYes', 'I will participate');
define('AppointmentRespondMaybe', 'Not sure yet');
define('AppointmentRespondNo', 'Will not participate');
define('AppointmentGuestsChangeEvent', 'Guests can change event');

define('AppointmentSubjectAddStart', 'You\'ve received invitation to event ');
define('AppointmentSubjectAddFrom', ' from ');
define('AppointmentSubjectUpdateStart', 'Modification of event ');
define('AppointmentSubjectDeleteStart', 'Cancellation of event ');
define('ErrorAppointmentChangeRespond', 'Unable to change appointment respond');
define('SettingsAutoAddInvitation', 'Add invitations into calendar automatically');
define('ReportEventSaved', 'Your event has been saved');
define('ReportAppointmentSaved', ' and notifications were sent');
define('ErrorAppointmentSend', 'Can\'t send invitations.');
define('AppointmentEventName', 'Name:');

// End appointments

define('ErrorCantUpdateFilters', 'Can\'t update filters');

define('FilterPhrase', 'If there\'s %field header %condition %string then %action');
define('FiltersAdd', 'Add Filter');
define('FiltersCondEqualTo', 'equal to');
define('FiltersCondContainSubstr', 'containing substring');
define('FiltersCondNotContainSubstr', 'not containing substring');
define('FiltersActionDelete', 'delete message');
define('FiltersActionMove', 'move');
define('FiltersActionToFolder', 'to %folder folder');
define('FiltersNo', 'No filters specified yet');

define('ReminderEmailFriendly', 'reminder');
define('ReminderEventBegin', 'starts at: ');

define('FiltersLoading', 'Loading Filters...');
define('ConfirmMessagesPermanentlyDeleted', 'All messages in this folder will be permanently deleted.');

define('InfoNoNewMessages', 'There are no new messages.');
define('TitleImportContacts', 'Import Contacts');
define('TitleSelectedContacts', 'Selected Contacts');
define('TitleNewContact', 'New Contact');
define('TitleViewContact', 'View Contact');
define('TitleEditContact', 'Edit Contact');
define('TitleNewGroup', 'New Group');
define('TitleViewGroup', 'View Group');

define('AttachmentComplete', 'Complete.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Autocheck mail every');
define('AutoCheckMailIntervalDisableName', 'Off');

define('ReportCalendarSaved', 'Calendar has been saved.');

define('ContactSyncError', 'Sync failed');
define('ReportContactSyncDone', 'Sync complete');

define('MobileSyncUrlTitle', 'Mobile sync URL');
define('MobileSyncLoginTitle', 'Mobile sync login');

define('QuickReply', 'Quick Reply');
define('SwitchToFullForm', 'Open full reply form');
define('SortFieldDate', 'Date');
define('SortFieldFrom', 'From');
define('SortFieldSize', 'Size');
define('SortFieldSubject', 'Subject');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Attachments');
define('SortOrderAscending', 'Ascending');
define('SortOrderDescending', 'Descending');
define('ArrangedBy', 'Arranged by');

define('MessagePaneToRight', 'The message pane is to the right of the message list, rather than below');

define('SettingsTabMobileSync', 'Mobile Sync');

define('MobileSyncContactDataBaseTitle', 'Mobile sync contact database');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync calendar database');
define('MobileSyncTitleText', 'If you\'d like to synchronize your SyncML-enabled handheld device with WebMail, you can use these parameters.<br />"Mobile Sync URL" specifies path to SyncML Data Synchronization server, "Mobile Sync Login" is your login on SyncML Data Synchronization Server and use your own password upon request. Also, some devices need to specify database name for contact and calendar data.<br />Use "Mobile sync contact database" and "Mobile sync calendar database" respectively.');
define('MobileSyncEnableLabel', 'Enable mobile sync');

define('SearchInputText', 'search');

define('AppointmentEmailExplanation','This message arrived to your %EMAIL% account because you was invited to the event by %ORGANAZER%');

define('Searching', 'Searching&hellip;');

define('ButtonSetupSpecialFolders', 'Setup special folders');
define('ButtonSaveChanges', 'Save changes');
define('InfoPreDefinedFolders', 'For pre-defined folders, use these IMAP mailboxes');

define('SaveMailInSentItems', 'Also save in Sent Items');

define('CouldNotSaveUploadedFile', 'Could not save uploaded file.');

define('AccountOldPassword', 'Current password');
define('AccountOldPasswordsDoNotMatch', 'Current Passwords do not match.');

define('DefEditor', 'Default editor');
define('DefEditorRichText', 'Rich Text');
define('DefEditorPlainText', 'Plain Text');

define('Layout', 'Layout');

define('TitleNewMessagesCount', '%count% new message(s)');

define('AltOpenInNewWindow', 'Open in new window');

define('SearchByFirstCharAll', 'All');

define('FolderNoUsageAssigned', 'No usage assigned');

define('InfoSetupSpecialFolders', 'To match a special folder (like Sent Items) and certain IMAP mailbox, click Setup special folders.');

define('FileUploaderClickToAttach', 'Click to attach a file');
define('FileUploaderOrDragNDrop', 'Or just drag and drop files here');

define('AutoCheckMailInterval1Minute', '1 minute');
define('AutoCheckMailInterval3Minutes', '3 minutes');
define('AutoCheckMailInterval5Minutes', '5 minutes');
define('AutoCheckMailIntervalMinutes', 'minutes');

define('ReadAboutCSVLink', 'Learn more on .CSV file fields');

define('VoiceMessageSubj', 'Voice Message');
define('VoiceMessageTranscription', 'Transcription');
define('VoiceMessageReceived', 'Received');
define('VoiceMessageDownload', 'Download');
define('VoiceMessageUpgradeFlashPlayer', 'You need to upgrade your Adobe Flash Player to play voice messages.<br />Upgrade to Flash Player 10 from <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'This license key is outdated, please contact us to upgrade your license key');
define('LicenseProblem', 'Licensing problem. System administrator should go in Admin Panel to check the details.');

define('AccountOldPasswordNotCorrect', 'Current password is not correct');
define('AccountNewPasswordUpdateError', 'Can\'t save new password.');
define('AccountNewPasswordRejected', 'Can\'t save new password. Perhaps, it\'s too simple.');

define('CantCreateIdentity', 'Can\'t create identity');
define('CantUpdateIdentity', 'Can\'t update identity');
define('CantDeleteIdentity', 'Can\'t delete identity');

define('AddIdentity', 'Add Identity');
define('SettingsTabIdentities', 'Identities');
define('NoIdentities', 'No identities');
define('NoSignature', 'No signature');
define('Account', 'Account');
define('TabChangePassword', 'Password');
define('SignatureEnteringHere', 'Start entering your signature here');

define('CantConnectToMailServer', 'Can\'t connect to mail server');

define('DomainNameNotSpecified', 'Domain name not specified.');

define('Open', 'Open');
define('FolderUsedAs', 'used as');
define('ForwardTitle', 'Forward');
define('ForwardEnable', 'Enable forward');
define('ReportForwardUpdatedSuccessfuly', 'Forward has been updated successfully.');

define('DialogAttachHeaderResume', 'Attach Your Resume');
define('DialogAttachHeaderLetter', 'Attach Your Cover Letter');
define('DialogAttachName', 'Select Resume');
define('DialogAttachType', 'Choose Format');
define('DialogAttachTypePdf', 'Adobe PDF (.pdf)');
define('DialogAttachTypeHtml', 'Web Page (.html)');
define('DialogAttachTypeRtf', 'Rich Text (.rtf)');
define('DialogAttachTypeTxt', 'Plain Text (.txt)');
define('DialogAttachTypeDoc', 'MS Word (.doc)');
define('DialogAttachButton', 'Attach');
define('DialogAttachResume', 'Attach a resume');
define('DialogAttachLetter', 'Attach a cover letter');
define('DialogAttachAnother', 'Attach another file');
define('DialogAttachAddToBody', 'Add plain text version to email body (Recommended)');
define('DialogAttachTypeNo', 'No Attachment');
define('DialogAttachSelectLetter', 'Select cover letter');
define('DialogAttachTypePdfRecom', 'Adobe PDF (.pdf) (Recommended)');
define('DialogAttachTypeTextInBody', 'Plain text in email body - recommended');
define('DialogAttachTypeTxtAttach', 'Plain Text (.txt) attachment');
define('CustomTitle', 'Forwarding');
define('ForwardingNotificationsTo', 'Send email notifications to <b>%email</b>');
define('ForwardingForwardTo', 'Forward email to <b>%email</b>');
define('ForwardingNothing', 'No email notifications or forwarding');
define('ForwardingChange', 'change');

define('ConfirmSaveForward', 'The forward settings were not saved. Click OK to save.');
define('ConfirmSaveAutoresponder', 'The autoresponder settings were not saved. Click OK to save.');

define('DigDosMenuItem', 'DigDos');
define('DigDosTitle', 'Select an object');

define('LastLoginTitle', 'Last login');
define('ExportContacts', 'Export Contacts');

define('JS_LANG_Gb', 'GB');

define('ContactsTabGlobal', 'global');
define('ContactsTabPersonal', 'personal');
define('InfoLoadingContacts', 'WebMail is loading contact list');

define('TheAccessToThisAccountIsDisabled', 'The access to this account is disabled');

define('MobileSyncDavServerURL', 'DAV server URL');
define('MobileSyncPrincipalURL', 'Principal URL');
define('MobileSyncHintDesc', 'Use these settings to sync your calendars and contacts with a mobile device which supports CalDAV or CardDAV protocols.<br /><br />With iOS devices like iPhone, you\'ll usually need DAV server URL, mobile sync login, and your password. Or, you can get your iOS profile automatically if you access this webmail from your such device.<br /><br />Some software like Mozilla Thunderbird require separate URL to each calendar of yours. To get this URL, select Share and Publish option for the given calendar in Calendars Manager.');

define('MobileGetIOSSettings', 'Deliver e-mail, contacts and calendar settings on your iOS device');
define('IOSLoginHeadTitle', 'Install iOS Profile');
define('IOSLoginHelloAppleTitle', 'Hello,');
define('IOSLoginHelpDesc1', 'We can automatically deliver your e-mail, contacts and calendar settings on your iOS device.');
define('IOSLoginHelpDesc2', 'You can always get them later,');
define('IOSLoginHelpDesc3', 'in Settings/Mobile section.');
define('IOSLoginButtonYesPlease', 'Yes, please');
define('IOSLoginButtonSkip', 'Skip this and let me in');
define('IOSLoginPage2HelloAppleTitle', 'Your account is ready!');
define('IOSLoginPage2HelpDesc1', 'With the new profile, you can sync e-mail and calendars on your iOS device using its native e-mail and calendar application.');
define('IOSLoginPage2HelpDesc2', 'If you wish, you can also use webmail for that.');
define('IOSLoginPage2ButtonOpenWebMail', 'Open webmail');

define('LoginBrowserWarning', 'Sorry, this web browser is not supported.<br/>We recommend to use one of the following browsers:<br/><a href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Internet Explorer 7</a>, <a href="http://www.firefox.com/">Mozilla Firefox 2</a>, <a href="http://www.apple.com/safari/download/">Safari 2</a>, <a href="http://www.opera.com/">Opera 9</a> or newer versions of these browsers.');

define('AppointmentInvitation', 'Invitation');
define('AppointmentAccepted', 'accepted');
define('AppointmentDeclined', 'declined');
define('AppointmentTentativelyAccepted', 'tentatively accepted');
define('AppointmentLocation', 'Location');
define('AppointmentCalendar', 'Calendar');
define('AppointmentWhen', 'When');
define('AppointmentDescription', 'Description');
define('AppointmentButtonAccept', 'Accept');
define('AppointmentButtonTentative', 'Tentative');
define('AppointmentButtonDecline', 'Decline');

define('ContactDisplayName', 'Display name');

define('WarningCreatingGroupRequiresContacts', 'Creating a group requires adding at least one contact to it.');
define('WarningRemovingAllContactsFromGroup', 'Removing all contacts from the group removes the group itself. Do you want to proceed?');
define('WarningSendEmailToDemoOnly', 'For security purposes, this demo account is allowed to send e-mail to demo accounts only.');

define('SettingsTabOutlookSync', 'Outlook Sync');

define('OutlookSyncServerURL', 'Server');

define('OutlookSyncHintDesc', 'To sync your Outlook calendar, specify these values in Outlook Sync plugin:');

define('WarningMailboxAlmostFull', 'Your mailbox is almost full.');
define('WarningCouldNotSaveDraftAsYourMailboxIsOverQuota', 'Could not save draft as your mailbox is over quota.');
define('WarningSentEmailNotSaved', 'The e-mail has been sent but could not save it in Sent Items as your mailbox is over quota.');

define('DavSyncHeading', 'DAV Sync via single URL (for Apple clients)');
define('DavSyncHint', 'Use the URL below to sync calendars and contacts with Apple iCal or a mobile device like iPhone or iPad (they all support syncing multiple CalDAV or CardDAV folders via a single URL). By the way, you can get your iOS profile automatically if you access this webmail from such device!');
define('DavSyncServer', 'DAV server');
define('DavSyncHeadingLogin', 'You\'ll also need the login and password:');
define('DavSyncLogin', 'Mobile sync login');
define('DavSyncPasswordTitle', 'Password');
define('DavSyncPasswordValue', 'Your account\'s password');
define('DavSyncSeparateUrlsHeading', 'DAV Sync via separate URLs');
define('DavSyncHintUrls', 'If your CalDAV or CardDAV client requires separate URLs for each calendar or address book of yours (such as Mozilla Thunderbird Lightning or Evolution), use the URLs below.');
define('DavSyncHeadingCalendar', 'CalDAV access to your calendars');
define('DavSyncHeadingContacts', 'CardDAV access to your address books');
define('DavSyncPersonalContacts', 'Personal contacts');
define('DavSyncCollectedAddresses', 'Collected addresses');
define('DavSyncGlobalAddressBook', 'Global address book');

define('ActiveSyncHeading', 'ActiveSync');
define('ActiveSyncHint', 'To sync your e-mail, contacts and calendar via EAS (Exchange ActiveSync), use the settings below:');
define('ActiveSyncServer', 'Server');
define('ActiveSyncLogin', 'Login');
define('ActiveSyncPasswordTitle', 'Password');
define('ActiveSyncPasswordValue', 'Your account\'s password');

define('SearchStop', 'Stop search');
define('ErrorDuringSearch', 'An error occured during search');
define('ErrorRetrievingMessages', 'An error occured when retrieving message list');

define('AppointmentCanceled', '%SENDER% canceled this meeting.');

define('CalendarDavUrl', 'DAV URL');
define('CalendarIcsLink', 'link to .ics');
define('CalendarIcsDownload', 'Download');

define('DavSyncDemoPasswordValue', 'demo');

define('ActiveSyncDemoPasswordValue', 'demo');

define('ConfirmUnsubscribeCalendar', 'Are you sure you want to unsubscribe from calendar');
define('CalendarUnsubscribe', 'Unsubscribe');
define('InfoUnsubscribing', 'Unsubscribing&hellip;');

define('ErrorDataTransferFailed', 'Data transfer has failed, probably due to server error. Please contact system administrator.');
define('ErrorCantReachServer', 'Can\'t reach the server.');
define('RetryGettingMessageList', 'Retry');
define('BackToMessageList', 'Back to messages list');

define('ErrorTitle', 'Error');
define('ErrorUnableToLogIntoAccount', 'Unable to log into account.');
define('ErrorUnableToLocateMessage', 'Unable to locate email message.');

define('ConfirmEditRepeatEventNotDaily', 'This way, you can set the date for a single occurrence only. To change the date for the entire series, now click Cancel and then set the date in the event properties.');

define('WarningMailboxIsFull', 'Your mailbox is full.');

define('ErrorVCardHasInvalidFormat', 'This vCard has invalid format and cannot be parsed.');

define('AddToContacts', 'Add to contacts');
define('AddToCalendar', 'Add to calendar');
define('EventAlreadyExistsInCalendar', 'Event already exists in calendar');
define('ContactAlreadyExistsInAddressBook', 'Contact already exists in address book');
define('NoMoveSpamInFullMailbox', 'Message(s) can\'t be moved to Spam. Most likely your message box is full. Should this unmoved message(s) be deleted?');
define('NoMoveInFullMailbox', 'Message(s) can\'t be moved. Most likely your message box is full.');
	
define('TopMultipleContactsSearchResults', 'Top of search results for "%SEARCHSTR%" in global and personal address books:');
define('ContactsSearchResults', 'Search results for "%SEARCHSTR%":');
define('ContactsSearchResultsInGroup', 'Search results for "%SEARCHSTR%" in group %GROUPNAME%:');
define('NoContactsFound', 'No contacts found.');
define('ContactsTypeTitle', 'Address book type:');
define('ContactsTypeMultiple', 'Personal and Global');
define('ContactsTypePersonal', 'Personal');
define('ContactsTypeGlobal', 'Global');
