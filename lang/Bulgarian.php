<?php
// Translation into Bulgarian by Assist. Prof. Dr. Rossen Radonov, MEng. from the Technical University of Sofia, August - September 2012
define('PROC_ERROR_ACCT_CREATE', 'Възникна грешка при създаването на акаунта');
define('PROC_WRONG_ACCT_PWD', 'Грешна парола');
define('PROC_CANT_LOG_NONDEF', 'Не може да се влезе в акаунт, който не е по подразбиране');
define('PROC_CANT_INS_NEW_FILTER', 'Не може да се вмъкне нов филтър');
define('PROC_FOLDER_EXIST', 'Името на папката вече съществува');
define('PROC_CANT_CREATE_FLD', 'Папката не може да бъде създадена');
define('PROC_CANT_INS_NEW_GROUP', 'Не може да се вмъкне нова група');
define('PROC_CANT_INS_NEW_CONT', 'Не може да се вмъкне нов контакт');
define('PROC_CANT_INS_NEW_CONTS', 'Не може да се вмъкне нов(и) контакт(и)');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Не може да се добави контакт(и) към групата');
define('PROC_ERROR_ACCT_UPDATE', 'Грешка при актуализиране на акаунта');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Не може да се актуализират настройките на контактите');
define('PROC_CANT_GET_SETTINGS', 'Не може да се получат настройките');
define('PROC_CANT_UPDATE_ACCT', 'Не може да се актуализира акаунта');
define('PROC_ERROR_DEL_FLD', 'Грешка при изтриване на папката(ите)');
define('PROC_CANT_UPDATE_CONT', 'Не може да се актуализира контакта');
define('PROC_CANT_GET_FLDS', 'Не може да се получи дървото с папките');
define('PROC_CANT_GET_MSG_LIST', 'Не може да се получи списъка с писмата');
define('PROC_MSG_HAS_DELETED', 'Това писмо вече е изтрито на сървъра');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Не може да се заредят настройките на контактите');
define('PROC_CANT_LOAD_SIGNATURE', 'Не може да се зареди подписа на акаунта');
define('PROC_CANT_GET_CONT_FROM_DB', 'Не може да се зареди контакта от базата данни');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Не може да се зареди контакта(те) от базата данни');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Не може да се изтрие акаунтът');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Не може да се изтрие филтърът');
define('PROC_CANT_DEL_CONT_GROUPS', 'Акаунтите и/или групите не може да бъдат изтрити');
define('PROC_WRONG_ACCT_ACCESS', 'Достъпът до този акаунт е забранен');
define('PROC_SESSION_ERROR', 'Предишната сесия е прекратена поради вътрешна грешка или изтичане на времето.');

define('MailBoxIsFull', 'Пощенската кутия е пълна');
define('WebMailException', 'Вътрешна сървърна грешка. Моля, свържете се със системния администратор.');
define('InvalidUid', 'Невалидно UID на писмо');
define('CantCreateContactGroup', 'Не може да се създаде групата с контакти');
define('CantCreateUser', 'Не може да се създаде потребителят');
define('CantCreateAccount', 'Не може да се създаде акаунтът');
define('SessionIsEmpty', 'Сесията е празна');
define('FileIsTooBig', 'Файлът е твърде голям!');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Не може да се маркират всички писма като прочетени');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Не може да се маркират всички писма като непрочетени');
define('PROC_CANT_PURGE_MSGS', 'Не може да се прочистят писмата');
define('PROC_CANT_DEL_MSGS', 'Не може да се изтрият писмата');
define('PROC_CANT_UNDEL_MSGS', 'Не може да се възстановят писмата');
define('PROC_CANT_MARK_MSGS_READ', 'Не може да се маркират писмата като прочетени');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Не може да се маркират писмата като непрочетени');
define('PROC_CANT_SET_MSG_FLAGS', 'Не може да се постави флагът на писмото');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Не може да се махне флагът на писмото');
define('PROC_CANT_CHANGE_MSG_FLD', 'Не може да се смени папката на писмото');
define('PROC_CANT_SEND_MSG', 'Писмото не може да бъде изпратено.');
define('PROC_CANT_SAVE_MSG', 'Писмото не може да бъде записано.');
define('PROC_CANT_GET_ACCT_LIST', 'Не може да се прочете списъка с акаунтите');
define('PROC_CANT_GET_FILTER_LIST', 'не може да се прочете списъка с филтрите');

define('PROC_CANT_LEAVE_BLANK', 'Моля, попълнете всички полета, отбелязани с *');

define('PROC_CANT_UPD_FLD', 'Не може да се актуализира папката');
define('PROC_CANT_UPD_FILTER', 'Не може да се актуализира филтърът');

define('ACCT_CANT_ADD_DEF_ACCT', 'Този акаунт не може да бъде добавен понеже се използва от друг потребител.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Този акаунт не може да бъде направен да е по подразбиране.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Не може да бъде създаден нов акаунт (грешка при връзката към IMAP4)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Последният акаунт по подразбиране не може да се изтрие');

define('LANG_LoginInfo', 'Вход към пощата');
define('LANG_Email', 'Потребител');
define('LANG_Login', 'Потр. име');
define('LANG_Password', 'Парола');
define('LANG_IncServer', 'Входяща&nbsp;поща');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Порт');
define('LANG_OutServer', 'Изходяща&nbsp;поща');
define('LANG_OutPort', 'Порт');
define('LANG_UseSmtpAuth', 'Използване&nbsp;на&nbsp;SMTP&nbsp;проверка');
define('LANG_SignMe', 'Автоматичен вход');
define('LANG_Enter', 'Вход');

// interface strings

define('JS_LANG_TitleLogin', 'Вход');
define('JS_LANG_TitleMessagesListView', 'Списък с писма');
define('JS_LANG_TitleMessagesList', 'Списък с писма');
define('JS_LANG_TitleViewMessage', 'Преглед на писмо');
define('JS_LANG_TitleNewMessage', 'Ново писмо');
define('JS_LANG_TitleSettings', 'Настройки');
define('JS_LANG_TitleContacts', 'Контакти');

define('JS_LANG_StandardLogin', 'Стандартен&nbsp;вход');
define('JS_LANG_AdvancedLogin', 'Специален&nbsp;вход');

define('JS_LANG_InfoWebMailLoading', 'WebMail се зарежда ...');
define('JS_LANG_Loading', 'Зареждане ...');
define('JS_LANG_InfoMessagesLoad', 'WebMail зарежда списък с писмата');
define('JS_LANG_InfoEmptyFolder', 'Тази папка е празна');
define('JS_LANG_InfoPageLoading', 'Страницата все още се зарежда ...');
define('JS_LANG_InfoSendMessage', 'Писмото беше изпратено');
define('JS_LANG_InfoSaveMessage', 'Писмото беше записано');
define('JS_LANG_InfoHaveImported', 'Вмъкнахте');
define('JS_LANG_InfoNewContacts', 'нов(и) контакт(и) към списъка си.');
define('JS_LANG_InfoToDelete', 'За да изтриете папката ');
define('JS_LANG_InfoDeleteContent', 'първо трябва да изтриете съдържанието.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Пълните папки не може да се изтриват. Първо изтрийте съдържанието им.');
define('JS_LANG_InfoRequiredFields', '* задължителни полета');

define('JS_LANG_ConfirmAreYouSure', 'Моля, потвърдете!');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Избраните писма ще бъдат изтрити завинаги! Моля, потвърдете');
define('JS_LANG_ConfirmSaveSettings', 'Настройките не бяха записани. Изберете OK за запис.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Настройките на контактите не бяха записани. Изберете OK за запис.');
define('JS_LANG_ConfirmSaveAcctProp', 'Настройките на този акаунт не са записани. Изберете OK за запис.');
define('JS_LANG_ConfirmSaveFilter', 'Настройките на филтрите не бяха записани. Изберете OK за запис.');
define('JS_LANG_ConfirmSaveSignature', 'Подписът не е записан. Изберете OK за запис.');
define('JS_LANG_ConfirmSavefolders', 'Папките не са запазени. Изберете OK за запис.');
define('JS_LANG_ConfirmHtmlToPlain', 'Внимание: Ако промените редактора от HTML на обикновен текст ще загубите форматирането на текста. Моля потвърдете!');
define('JS_LANG_ConfirmAddFolder', 'Преди добавяне/премахване на папка е необходимо да приложите промените. Изберете OK за запис.');
define('JS_LANG_ConfirmEmptySubject', 'Не сте задали тема! Желаете ли да продължите?');

define('JS_LANG_WarningEmailBlank', 'Полето <br />"Потребител" не може да е празно.');
define('JS_LANG_WarningLoginBlank', 'Не може да оставите полето<br />"Потр. име" празно.');
define('JS_LANG_WarningToBlank', 'Полето "До" не може да е празно');
define('JS_LANG_WarningServerPortBlank', 'Не може да оставите полетата POP3 и<br />SMTP сървър/порт празни.');
define('JS_LANG_WarningEmptySearchLine', 'Полето за търсене е празно. Моля задайте низ.');
define('JS_LANG_WarningMarkListItem', 'Моля, маркирайте поне една позиция от списъка.');
define('JS_LANG_WarningFolderMove', 'Папката не може да бъде преместена, защото това е друго ниво.');
define('JS_LANG_WarningContactNotComplete', 'Моля, въведете email или име.');
define('JS_LANG_WarningGroupNotComplete', 'Моля, въведете име на групата.');

define('JS_LANG_WarningEmailFieldBlank', 'Полето "Потребител" не може да е празно.');
define('JS_LANG_WarningIncServerBlank', 'Полето POP3(IMAP4) сървър не може да е празно.');
define('JS_LANG_WarningIncPortBlank', 'Полето порт на POP3(IMAP4) сървър не може да е празно.');
define('JS_LANG_WarningIncLoginBlank', 'Полето потребител за POP3(IMAP4) не може да е празно.');
define('JS_LANG_WarningIncPortNumber', 'POP3(IMAP4) портът трябва да е положително число.');
define('JS_LANG_DefaultIncPortNumber', 'Стандартният POP3(IMAP4) порт е 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Полето Парола за POP3(IMAP4) сървър не може да е празно.');
define('JS_LANG_WarningOutPortBlank', 'Полето порт на SMTP сървъра не може да е празно.');
define('JS_LANG_WarningOutPortNumber', 'SMTP портът трябва да е положително число.');
define('JS_LANG_WarningCorrectEmail', 'Задали сте грешен e-mail.');
define('JS_LANG_DefaultOutPortNumber', 'Стандартният SMTP порт е 25.');

define('JS_LANG_WarningCsvExtention', 'Разширението трябва да е .csv');
define('JS_LANG_WarningImportFileType', 'Моля, изберете приложението, от което искате да копирате контактите си');
define('JS_LANG_WarningEmptyImportFile', 'Моля изберете файл като кликнете на бутона Browse/Преглед');

define('JS_LANG_WarningContactsPerPage', 'Броят контакти на страница трябва да е по-голям от нула!');
define('JS_LANG_WarningMessagesPerPage', 'Броят писма на страница трябва да е по-голям от нула!');
define('JS_LANG_WarningMailsOnServerDays', 'Броят дни трябва да е по-голям от нула.');
define('JS_LANG_WarningEmptyFilter', 'Моля, въведете низ!');
define('JS_LANG_WarningEmptyFolderName', 'Моля, въведете име на папката!');

define('JS_LANG_ErrorConnectionFailed', 'Неуспешна връзка');
define('JS_LANG_ErrorRequestFailed', 'Прехвърлянето на данни не е завършило');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Обектът XMLHttpRequest не е наличен!');
define('JS_LANG_ErrorWithoutDesc', 'Получи се неустановена грешка!');
define('JS_LANG_ErrorParsing', 'Грешка при обработка на XML.');
define('JS_LANG_ResponseText', 'Текст на отговора:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Празен XML пакет');
define('JS_LANG_ErrorImportContacts', 'Грешка при вмъкване на контактите');
define('JS_LANG_ErrorNoContacts', 'Няма контакти за вмъкване.');
define('JS_LANG_ErrorCheckMail', 'Четенето на писмата беше преустановено поради грешка. Вероятно нв всички са изтеглени.');

define('JS_LANG_LoggingToServer', 'Свързване към сървъра ...');
define('JS_LANG_GettingMsgsNum', 'Получаване на броя писма');
define('JS_LANG_RetrievingMessage', 'Изтегляне на писмо');
define('JS_LANG_DeletingMessage', 'Изтриване на писмо');
define('JS_LANG_DeletingMessages', 'Изтриване на писмо(а)');
define('JS_LANG_Of', 'от');
define('JS_LANG_Connection', 'Свързване');
define('JS_LANG_Charset', 'Кодова таблица');
define('JS_LANG_AutoSelect', 'Автоматичен избор');

define('JS_LANG_Contacts', 'Контакти');
define('JS_LANG_ClassicVersion', 'Класическа версия');
define('JS_LANG_Logout', 'Изход');
define('JS_LANG_Settings', 'Настройки');

define('JS_LANG_LookFor', 'Търсене на: ');
define('JS_LANG_SearchIn', 'Търсене в: ');
define('JS_LANG_QuickSearch', 'Търсене само в полета "От", "До" и "Тема" (става по-бързо).');
define('JS_LANG_SlowSearch', 'Търсене в текста на писмата');
define('JS_LANG_AllMailFolders', 'Всички папки');
define('JS_LANG_AllGroups', 'Всички групи');

define('JS_LANG_NewMessage', 'Ново писмо');
define('JS_LANG_CheckMail', 'Проверка на пощата');
define('JS_LANG_EmptyTrash', 'Изпразване на кошчето');
define('JS_LANG_MarkAsRead', 'Прочетено');
define('JS_LANG_MarkAsUnread', 'Непрочетено');
define('JS_LANG_MarkFlag', 'Слагане на флаг');
define('JS_LANG_MarkUnflag', 'Махане на флаг');
define('JS_LANG_MarkAllRead', 'Всички прочетени');
define('JS_LANG_MarkAllUnread', 'Всички непрочетени');
define('JS_LANG_Reply', 'Отговор');
define('JS_LANG_ReplyAll', 'Отговор до всички');
define('JS_LANG_Delete', 'Изтриване');
define('JS_LANG_Undelete', 'Възстановяване');
define('JS_LANG_PurgeDeleted', 'Изчистване на изтритите');
define('JS_LANG_MoveToFolder', 'Преместване в папка');
define('JS_LANG_Forward', 'Препращане');

define('JS_LANG_HideFolders', 'Скриване на папки');
define('JS_LANG_ShowFolders', 'Показване на папки');
define('JS_LANG_ManageFolders', 'Настройка папки');
define('JS_LANG_SyncFolder', 'Синхронизирана папка');
define('JS_LANG_NewMessages', 'Нови писма');
define('JS_LANG_Messages', 'Писмо(а)');

define('JS_LANG_From', 'От');
define('JS_LANG_To', 'До');
define('JS_LANG_Date', 'Дата');
define('JS_LANG_Size', 'Размер');
define('JS_LANG_Subject', 'Тема');

define('JS_LANG_FirstPage', 'Първа страница');
define('JS_LANG_PreviousPage', 'Предишна страница');
define('JS_LANG_NextPage', 'Следваща страница');
define('JS_LANG_LastPage', 'Последна страница');

define('JS_LANG_SwitchToPlain', 'В обикновен текстов режим');
define('JS_LANG_SwitchToHTML', 'В разширен HTML режим');
define('JS_LANG_AddToAddressBook', 'Добавяне към контактите');
define('JS_LANG_ClickToDownload', 'Свалете ');
define('JS_LANG_View', 'Преглед');
define('JS_LANG_ShowFullHeaders', 'Показване на пълния хедър');
define('JS_LANG_HideFullHeaders', 'Скриване на пълния хедър');

define('JS_LANG_MessagesInFolder', 'писмо(а) в папката');
define('JS_LANG_YouUsing', 'Използвате');
define('JS_LANG_OfYour', 'от Вашата');
define('JS_LANG_Mb', ' MB');
define('JS_LANG_Kb', ' KB');
define('JS_LANG_B', ' B');

define('JS_LANG_SendMessage', 'Изпращане');
define('JS_LANG_SaveMessage', 'Запис');
define('JS_LANG_Print', 'Печат');
define('JS_LANG_PreviousMsg', 'Предишно писмо');
define('JS_LANG_NextMsg', 'Следващо писмо');
define('JS_LANG_AddressBook', 'Бележник');
define('JS_LANG_ShowBCC', 'Показване СК');
define('JS_LANG_HideBCC', 'Скриване СК');
define('JS_LANG_CC', 'ЯК');
define('JS_LANG_BCC', 'СК');
define('JS_LANG_ReplyTo', 'Отговор&nbsp;до');
define('JS_LANG_AttachFile', 'Прикачване на файл');
define('JS_LANG_Attach', 'Прикачване');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Оригинално писмо');
define('JS_LANG_Sent', 'Изпратено');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Нисък');
define('JS_LANG_Normal', 'Нормален');
define('JS_LANG_High', 'Висок');
define('JS_LANG_Importance', 'Приоритет');
define('JS_LANG_Close', 'Затваряне');

define('JS_LANG_Common', 'Общи');
define('JS_LANG_EmailAccounts', 'Email акаунти');

define('JS_LANG_MsgsPerPage', 'Брой писма на страница');
define('JS_LANG_DisableRTE', 'Без HTML редактор');
define('JS_LANG_Skin', 'Облик');
define('JS_LANG_DefCharset', 'Кодова таблица');
define('JS_LANG_DefCharsetInc', 'Кодова таблица на входящи');
define('JS_LANG_DefCharsetOut', 'Кодова таблица на изходящи');
define('JS_LANG_DefTimeOffset', 'Часова зона');
define('JS_LANG_DefLanguage', 'Език');
define('JS_LANG_DefDateFormat', 'Формат на датата');
define('JS_LANG_ShowViewPane', 'Списък с писмата с поле за преглед');
define('JS_LANG_Save', 'Запис');
define('JS_LANG_Cancel', 'Отказ');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Премахване');
define('JS_LANG_AddNewAccount', 'Добавяне на нов акаунт');
define('JS_LANG_Signature', 'Подпис');
define('JS_LANG_Filters', 'Филтри');
define('JS_LANG_Properties', 'Свойства');
define('JS_LANG_UseForLogin', 'Да се използват данните на този акаунт за вход (потребител и парола)');
define('JS_LANG_MailFriendlyName', 'Вашите имена');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Сървър за входяща поща');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Порт');
define('JS_LANG_MailIncLogin', 'Потребителско име');
define('JS_LANG_MailIncPass', 'Парола');
define('JS_LANG_MailOutHost', 'Сървър за изходяща поща');
define('JS_LANG_MailOutPort', 'Порт');
define('JS_LANG_MailOutLogin', 'Потребителско име за SMTP');
define('JS_LANG_MailOutPass', 'Парола за SMTP');
define('JS_LANG_MailOutAuth1', 'Използване на SMTP проверка');
define('JS_LANG_MailOutAuth2', '(Може да оставите полето SMTP потребител/парола празно, ако съвпадат тези за POP3/IMAP4 вход');
define('JS_LANG_UseFriendlyNm1', 'Използване на кратко име в полето "От:"');
define('JS_LANG_UseFriendlyNm2', '(Вашето име &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Изтегляне/синхронизиране на пощата при влизане');
define('JS_LANG_MailMode0', 'Изтриване на получените писма на сървъра');
define('JS_LANG_MailMode1', 'Оставяне на писмата на сървъра');
define('JS_LANG_MailMode2', 'Оставяне на писмата на сървъра за');
define('JS_LANG_MailsOnServerDays', 'ден(дни)');
define('JS_LANG_MailMode3', 'Изтриване на писмо от сървъра, когато се изтрие от Кошче');
define('JS_LANG_InboxSyncType', 'Вид синхронизация на Входящи');

define('JS_LANG_SyncTypeNo', 'Да не се синхронизира');
define('JS_LANG_SyncTypeNewHeaders', 'Нови хедъри');
define('JS_LANG_SyncTypeAllHeaders', 'Всички хедъри');
define('JS_LANG_SyncTypeNewMessages', 'Нови писма');
define('JS_LANG_SyncTypeAllMessages', 'Всички писма');
define('JS_LANG_SyncTypeDirectMode', 'Директен режим');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Само хедърите');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Целите писма');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Директен режим');

define('JS_LANG_DeleteFromDb', 'Изтриване на писмото от базата данни, ако вече го няма на сървъра');

define('JS_LANG_EditFilter', 'Редактиране&nbsp;на&nbsp;филтър');
define('JS_LANG_NewFilter', 'Добавяне на нов филтър');
define('JS_LANG_Field', 'Поле');
define('JS_LANG_Condition', 'Условие');
define('JS_LANG_ContainSubstring', 'Съдържа низ');
define('JS_LANG_ContainExactPhrase', 'Съдържа точна фраза');
define('JS_LANG_NotContainSubstring', 'Не съдържа низ');
define('JS_LANG_FilterDesc_At', 'в');
define('JS_LANG_FilterDesc_Field', 'поле');
define('JS_LANG_Action', 'Действие');
define('JS_LANG_DoNothing', 'Не прави нищо');
define('JS_LANG_DeleteFromServer', 'Незабавно изтриване от сървъра');
define('JS_LANG_MarkGrey', 'Маркиране в сиво');
define('JS_LANG_Add', 'Добавяне');
define('JS_LANG_OtherFilterSettings', 'Други настройки на филтри');
define('JS_LANG_ConsiderXSpam', 'Да се има предвид X-Spam хедърите');
define('JS_LANG_Apply', 'Прилагане');

define('JS_LANG_InsertLink', 'Вмъкване на връзка');
define('JS_LANG_RemoveLink', 'Премахване на връзка');
define('JS_LANG_Numbering', 'Номериране');
define('JS_LANG_Bullets', 'Тире');
define('JS_LANG_HorizontalLine', 'Хоризонтална черта');
define('JS_LANG_Bold', 'Удебеляване');
define('JS_LANG_Italic', 'Наклоняване');
define('JS_LANG_Underline', 'Подчертаване');
define('JS_LANG_AlignLeft', 'Ляво подравняване');
define('JS_LANG_Center', 'Центриране');
define('JS_LANG_AlignRight', 'Дясно подравняване');
define('JS_LANG_Justify', 'Подравняване от двете страни');
define('JS_LANG_FontColor', 'Цвят на шрифта');
define('JS_LANG_Background', 'Цвят на фона');
define('JS_LANG_SwitchToPlainMode', 'Към обикновен текстов редактор');
define('JS_LANG_SwitchToHTMLMode', 'Към HTML редактор');

define('JS_LANG_Folder', 'Папка');
define('JS_LANG_Msgs', 'Писма');
define('JS_LANG_Synchronize', 'Синхронизиране');
define('JS_LANG_ShowThisFolder', 'Показване на папката');
define('JS_LANG_Total', 'Общо');
define('JS_LANG_DeleteSelected', 'Изтриване на избраните');
define('JS_LANG_AddNewFolder', 'Добавяне на нова папка');
define('JS_LANG_NewFolder', 'Нова папка');
define('JS_LANG_ParentFolder', 'Като подпапка на');
define('JS_LANG_NoParent', 'без');
define('JS_LANG_FolderName', 'Име на папката');

define('JS_LANG_ContactsPerPage', 'Брой контакти на страница');
define('JS_LANG_WhiteList', 'Бележникът като бял списък');

define('JS_LANG_CharsetDefault', 'по подразбиране');
define('JS_LANG_CharsetArabicAlphabetISO', 'Арабска азбука (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Арабска азбука (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Baltic азбука (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Балтийска азбука (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Централно-европейска азбука (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Централно-европейска азбука (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Опростен китайски (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Опростен китайски (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Традиционен китайски (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Кирилица (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Кирилица (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Кирилица (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Гръцка азбука (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Гръцка азбука (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Еврейска азбука (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'HebrЕврейскаew азбука (Windows)');
define('JS_LANG_CharsetJapanese', 'Японски');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Японски (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Корейски (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Korean (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Кодировка Latin 3 (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Турска азбука');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Уникод (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Уникод (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Виетнамска азбука (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Западна азбука (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Западна азбука (Windows)');

define('JS_LANG_TimeDefault', 'по подразбиране');
define('JS_LANG_TimeEniwetok', 'Ениветок, Кваялейн, Линия на времето');
define('JS_LANG_TimeMidwayIsland', 'Остров Мидуей, Самоа');
define('JS_LANG_TimeHawaii', 'Хаваи');
define('JS_LANG_TimeAlaska', 'Аляска');
define('JS_LANG_TimePacific', 'Тихоокенаско време (САЩ и Канада), Тихуана');
define('JS_LANG_TimeArizona', 'Аризона');
define('JS_LANG_TimeMountain', 'Планинско време (САЩ и Канада)');
define('JS_LANG_TimeCentralAmerica', 'Централна Америка');
define('JS_LANG_TimeCentral', 'Централно време ((САЩ и Канада))');
define('JS_LANG_TimeMexicoCity', 'Мексико ситу, Тегусигалпа');
define('JS_LANG_TimeSaskatchewan', 'Саскачуан');
define('JS_LANG_TimeIndiana', 'Индиана (източна)');
define('JS_LANG_TimeEastern', 'Източно време (САЩ и Канада)');
define('JS_LANG_TimeBogota', 'Богота, Лима, Кито');
define('JS_LANG_TimeSantiago', 'Сантяго');
define('JS_LANG_TimeCaracas', 'Каракас, Ла Пас');
define('JS_LANG_TimeAtlanticCanada', 'Атлантическо време (канада)');
define('JS_LANG_TimeNewfoundland', 'Нюфаундленд');
define('JS_LANG_TimeGreenland', 'Гренландия');
define('JS_LANG_TimeBuenosAires', 'Буенос Айрес, Джорджтаун');
define('JS_LANG_TimeBrasilia', 'Бразилия');
define('JS_LANG_TimeMidAtlantic', 'Среден Атлантик');
define('JS_LANG_TimeCapeVerde', 'о. Капо Верде');
define('JS_LANG_TimeAzores', 'Азорски о-ви');
define('JS_LANG_TimeMonrovia', 'Казабланка, Монровия');
define('JS_LANG_TimeGMT', 'Дъблин, Единбург, Лисабон, Лондон');
define('JS_LANG_TimeBerlin', 'Амстердам, Берлин, Берн, Рим, Стокхолм, Виена');
define('JS_LANG_TimePrague', 'Белград, Братислава, Будапеща, Любляна, Прага');
define('JS_LANG_TimeParis', 'Брюксел, Копенхаген, Мадрид, Париж');
define('JS_LANG_TimeSarajevo', 'Сараево, Скопие, София, Варшава, Загреб');
define('JS_LANG_TimeWestCentralAfrica', 'Западна централна Африка');
define('JS_LANG_TimeAthens', 'Атина, Истанбул, Минск');
define('JS_LANG_TimeEasternEurope', 'Букурещ');
define('JS_LANG_TimeCairo', 'Кайро');
define('JS_LANG_TimeHarare', 'Хараре, Преториа');
define('JS_LANG_TimeHelsinki', 'Хелзинки, Рига, Талин, Вилнюс');
define('JS_LANG_TimeIsrael', 'Израел, Йерусалимско стандартно време');
define('JS_LANG_TimeBaghdad', 'Багдад');
define('JS_LANG_TimeArab', 'Кувйт, Риад');
define('JS_LANG_TimeMoscow', 'Москва, Петербург, Волгоград');
define('JS_LANG_TimeEastAfrica', 'Източна Африка, Найроби');
define('JS_LANG_TimeTehran', 'Техеран');
define('JS_LANG_TimeAbuDhabi', 'Абу Даби, Мускат');
define('JS_LANG_TimeCaucasus', 'Баку, Тбилиси, Ереван');
define('JS_LANG_TimeKabul', 'Кабул');
define('JS_LANG_TimeEkaterinburg', 'Екатеринбург');
define('JS_LANG_TimeIslamabad', 'Исламабад, Карачи, Свердловск, Ташкент');
define('JS_LANG_TimeBombay', 'Калкута, Ченай, Мумбай, Ню Делхи, Индийско стандартно време');
define('JS_LANG_TimeNepal', 'Катманду, Непал');
define('JS_LANG_TimeAlmaty', 'Алмаата, Северно-Централна Азия');
define('JS_LANG_TimeDhaka', 'Сатана, Дака');
define('JS_LANG_TimeSriLanka', 'Шри Яваденпура, Шри Ланка');
define('JS_LANG_TimeRangoon', 'Ранкун');
define('JS_LANG_TimeBangkok', 'Банкок, Новосибирск, Ханой, Джакарта');
define('JS_LANG_TimeKrasnoyarsk', 'Красноярск');
define('JS_LANG_TimeBeijing', 'Пекин, Чонгкинг, Хонг Конг, Оромчи');
define('JS_LANG_TimeUlaanBataar', 'Улан Батор');
define('JS_LANG_TimeSingapore', 'Куала Лумпур, Сингапур');
define('JS_LANG_TimePerth', 'Пърт, Северна Австралия');
define('JS_LANG_TimeTaipei', 'Тайпе');
define('JS_LANG_TimeTokyo', 'Осака, Сапоро, Токио, Иркутск');
define('JS_LANG_TimeSeoul', 'Сеул, Корейско стандартно време');
define('JS_LANG_TimeYakutsk', 'Якутск');
define('JS_LANG_TimeAdelaide', 'Аделаида, Централна Австралия');
define('JS_LANG_TimeDarwin', 'Дарвин');
define('JS_LANG_TimeBrisbane', 'Бризбвйн, Източна Австралия');
define('JS_LANG_TimeSydney', 'Канбера, Мелбърн, Сидни, Хобарт');
define('JS_LANG_TimeGuam', 'Гуам, Порт Моресби');
define('JS_LANG_TimeHobart', 'Хобарт, Тасмания');
define('JS_LANG_TimeVladivostock', 'Владивосток');
define('JS_LANG_TimeSolomonIs', 'Соломонови о-ви, Нова Каледония');
define('JS_LANG_TimeWellington', 'Оукланд, Уелингтън, Магадан');
define('JS_LANG_TimeFiji', 'о-ви Фиджи, Камчатка, Маршалови о-ви.');
define('JS_LANG_TimeTonga', 'Нукуалофа, Тонга');

define('JS_LANG_DateDefault', 'по подразбиране');
define('JS_LANG_DateDDMMYY', 'дд/мм/гг');
define('JS_LANG_DateMMDDYY', 'мм/дд/гг');
define('JS_LANG_DateDDMonth', 'дд месец (01 Яну.)');
define('JS_LANG_DateAdvanced', 'Разширено');

define('JS_LANG_NewContact', 'Нов контакт');
define('JS_LANG_NewGroup', 'Нова група');
define('JS_LANG_AddContactsTo', 'Добавяне контакти към');
define('JS_LANG_ImportContacts', 'Внасяне на контакти');

define('JS_LANG_Name', 'Име');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Email');
define('JS_LANG_NotSpecifiedYet', 'Още не е указано');
define('JS_LANG_ContactName', 'Име');
define('JS_LANG_Birthday', 'Дата на раждане');
define('JS_LANG_Month', 'Месец');
define('JS_LANG_January', 'януари');
define('JS_LANG_February', 'февруари');
define('JS_LANG_March', 'март');
define('JS_LANG_April', 'април');
define('JS_LANG_May', 'май');
define('JS_LANG_June', 'юни');
define('JS_LANG_July', 'юли');
define('JS_LANG_August', 'август');
define('JS_LANG_September', 'септември');
define('JS_LANG_October', 'октомври');
define('JS_LANG_November', 'ноември');
define('JS_LANG_December', 'декември');
define('JS_LANG_Day', 'Ден');
define('JS_LANG_Year', 'Година');
define('JS_LANG_UseFriendlyName1', 'Използване на кратко име');
define('JS_LANG_UseFriendlyName2', '(например, Иван Георгиев &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Личен');
define('JS_LANG_PersonalEmail', 'Личен E-mail');
define('JS_LANG_StreetAddress', 'Адрес');
define('JS_LANG_City', 'Град');
define('JS_LANG_Fax', 'Факс');
define('JS_LANG_StateProvince', 'Щат');
define('JS_LANG_Phone', 'Стационарен');
define('JS_LANG_ZipCode', 'П.К.');
define('JS_LANG_Mobile', 'Мобилен');
define('JS_LANG_CountryRegion', 'Страна/област');
define('JS_LANG_WebPage', 'Уеб страница');
define('JS_LANG_Go', 'Посетете');
define('JS_LANG_Home', 'Домашен');
define('JS_LANG_Business', 'Служебен');
define('JS_LANG_BusinessEmail', 'Служебен E-mail');
define('JS_LANG_Company', 'Компания');
define('JS_LANG_JobTitle', 'Длъжност');
define('JS_LANG_Department', 'Отдел');
define('JS_LANG_Office', 'Кабинет');
define('JS_LANG_Pager', 'Пейджър');
define('JS_LANG_Other', 'Други');
define('JS_LANG_OtherEmail', 'Друг E-mail адрес');
define('JS_LANG_Notes', 'Бележки');
define('JS_LANG_Groups', 'Групи');
define('JS_LANG_ShowAddFields', 'Показване на допълнителни полета');
define('JS_LANG_HideAddFields', 'Скриване на допълнителните полета');
define('JS_LANG_EditContact', 'Редактиране на информацията за контакта');
define('JS_LANG_GroupName', 'Име на групата');
define('JS_LANG_AddContacts', 'Добавяне на контакти');
define('JS_LANG_CommentAddContacts', '(Ако зададете повече от един адрес, моля разделете ги със запети)');
define('JS_LANG_CreateGroup', 'Създаване на група');
define('JS_LANG_Rename', 'преименуване');
define('JS_LANG_MailGroup', 'Група писма');
define('JS_LANG_RemoveFromGroup', 'Премахване от групата');
define('JS_LANG_UseImportTo', 'Използвайте Вмъкване, за да копирате контактите си от Microsoft Outlook и Microsoft Outlook Express в списъка си на AfterLogic WebMail.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Изберете файла (във формат .CSV ), който ще вмъквате');
define('JS_LANG_Import', 'Вмъкване');
define('JS_LANG_ContactsMessage', 'Това е страницата с контактите!!!');
define('JS_LANG_ContactsCount', 'контакт(и)');
define('JS_LANG_GroupsCount', 'група/и');

// webmail 4.1 constants
define('PicturesBlocked', 'Изображенията в това писмо са блокирани от съображения за сигурност.');
define('ShowPictures', 'Показване на изображенията');
define('ShowPicturesFromSender', 'Винаги да се показват изображенията в писмата от този подател');
define('AlwaysShowPictures', 'Винаги да се показват изображенията в писмата');
define('TreatAsOrganization', 'Да се счита за организация');

define('WarningGroupAlreadyExist', 'Вече има такава група. Моля задайте друго име.');
define('WarningCorrectFolderName', 'Трябва да зададете правилно име на папка.');
define('WarningLoginFieldBlank', 'Полето "Потр. име" не може да е празно.');
define('WarningCorrectLogin', 'Дайте правилно потребителско име.');
define('WarningPassBlank', 'Полето "Парола" не може да е празно.');
define('WarningCorrectIncServer', 'Задайте правилен адрес на POP3(IMAP) сървър.');
define('WarningCorrectSMTPServer', 'Задайте правилен адрес на сървъра за изходяща поща.');
define('WarningFromBlank', 'Полето "От" не може да е празно.');
define('WarningAdvancedDateFormat', 'Моля задайте формат за дата и време.');

define('AdvancedDateHelpTitle', 'Потребителска дата');
define('AdvancedDateHelpIntro', 'Когато е избрано полето &quot;Разширено&quot;, можете да използвате опцията за задаване на ваш формат за дата. Следните опции са валидни заедно с разделителите \':\' или \'/\':');
define('AdvancedDateHelpConclusion', 'Например, ако зададете &quot;mm/dd/yyyy&quot; при режим &quot;Разширено&quot;, датата се показва като месец/ден/година (т.е. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Ден от месеца (1 до 31)');
define('AdvancedDateHelpNumericMonth', 'Месец (1 до 12)');
define('AdvancedDateHelpTextualMonth', 'Месец (яну. до дек.)');
define('AdvancedDateHelpYear2', 'Година, 2 цифри');
define('AdvancedDateHelpYear4', 'Година, 4 цифри');
define('AdvancedDateHelpDayOfYear', 'Ден от годината (1 до 366)');
define('AdvancedDateHelpQuarter', 'Тримесечие');
define('AdvancedDateHelpDayOfWeek', 'Ден от седмицата (пон. до нед.)');
define('AdvancedDateHelpWeekOfYear', 'Седмица от годината (1 до 53)');

define('InfoNoMessagesFound', 'Няма намерени писма.');
define('ErrorSMTPConnect', 'Няма връзка със SMTP сървъра. Моля, проверете настройките.');
define('ErrorSMTPAuth', 'Грешно потребителско име или парола.');
define('ReportMessageSent', 'Писмото Ви беше изпратено.');
define('ReportMessageSaved', 'Писмото Ви беше записано.');
define('ErrorPOP3Connect', 'Няма връзка със POP3 сървъра. Моля, проверете настройките.');
define('ErrorIMAP4Connect', 'Няма връзка със IMAP4 сървъра. Моля, проверете настройките.');
define('ErrorPOP3IMAP4Auth', 'Грешно потребителско име или парола.');
define('ErrorGetMailLimit', 'За съжаление сте надхвърлили допустимия размер на пощенската си кутия.');

define('ReportSettingsUpdatedSuccessfuly', 'Настройките бяха записани успешно.');
define('ReportAccountCreatedSuccessfuly', 'Акаунтът е създаден успешно.');
define('ReportAccountUpdatedSuccessfuly', 'Акаунтът е актуализиран успешно.');
define('ConfirmDeleteAccount', 'Моля, потвърдете изтриването!');
define('ReportFiltersUpdatedSuccessfuly', 'Филтрите са актуализирани успешно.');
define('ReportSignatureUpdatedSuccessfuly', 'Подписът е актуализиран успешно.');
define('ReportFoldersUpdatedSuccessfuly', 'Папките са актуализирани успешно.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Контактите са актуализирани успешно.');

define('ErrorInvalidCSV', 'Файлът не е в .CSV формат.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Групата');
define('ReportGroupSuccessfulyAdded2', 'беше добавена успешно.');
define('ReportGroupUpdatedSuccessfuly', 'Групата беше актуализирана успешно.');
define('ReportContactSuccessfulyAdded', 'Контактът беше добавен успешно.');
define('ReportContactUpdatedSuccessfuly', 'Контактът беше актуализиран успешно.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Контактът/ите беше добавен към групата');
define('AlertNoContactsGroupsSelected', 'Не са избрани контакти или групи.');

define('InfoListNotContainAddress', 'Ако списъкът не съдържа адрес, който търсите, продължете с писането.');

define('DirectAccess', 'Д');
define('DirectAccessTitle', 'Директен режим. WebMail работи с писмата директно на сървъра.');

define('FolderInbox', 'Входящи');
define('FolderSentItems', 'Изпратени');
define('FolderDrafts', 'Чернови');
define('FolderTrash', 'Кошче');

define('FileLargerAttachment', 'Размерът на файла надхвърля позволения размер.');
define('FilePartiallyUploaded', 'поради неустановена грешка само част от файла беше качен.');
define('NoFileUploaded', 'Не беше качен файл.');
define('MissingTempFolder', 'Липсва временната папка.');
define('MissingTempFile', 'Липсва временният файл.');
define('UnknownUploadError', 'Получи се неизвестна грешка при качване на файла.');
define('FileLargerThan', 'Грешка при качване. Вероятно файлът е по-голям от ');
define('PROC_CANT_LOAD_DB', 'Няма връзка с базата данни.');
define('PROC_CANT_LOAD_LANG', 'Не може да се намери необходимия езиков файл.');
define('PROC_CANT_LOAD_ACCT', 'Акаунтът не съществува, вероятно е бил изтрит скоро.');

define('DomainDosntExist', 'Няма такъв домейн на пощенския сървър.');
define('ServerIsDisable', 'Използването на пощенския сървър е забранено от администратора.');

define('PROC_ACCOUNT_EXISTS', 'Акаунтът не вече съществува.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Папките с писмата не може да бъдат преброени.');
define('PROC_CANT_MAIL_SIZE', 'Не може да се получи обема на пощата.');

define('Organization', 'Организация');
define('WarningOutServerBlank', 'Полето "Изходящ сървър" не може да е празно.');

define('JS_LANG_Refresh', 'Опресняване');
define('JS_LANG_MessagesInInbox', 'Писма във Входящи');
define('JS_LANG_InfoEmptyInbox', 'Папката Входящи е празна');

// webmail 4.2 constants
define('BackToList', 'Към писмата');
define('InfoNoContactsGroups', 'Няма контакти или групи.');
define('InfoNewContactsGroups', 'Създайте нови контакти/групи или ги внесете от .CSV файл във формат на MS Outlook.');
define('DefTimeFormat', 'Формат на часа');
define('SpellNoSuggestions', 'Няма предложения');
define('SpellWait', 'Моля, изчакайте ...');

define('InfoNoMessageSelected', 'Не е избрано писмо.');
define('InfoSingleDoubleClick', 'Кликнете върху някое писмо от списъка, за да се покаже тук или кликнете два пъти върху него, за да го видите в нов прозорец.');

// calendar
define('TitleDay', 'Преглед по дни');
define('TitleWeek', 'Преглед по седмици');
define('TitleMonth', 'Преглед по месеци');

define('ErrorNotSupportBrowser', 'Календарът на AfterLogic не се поддържа от Вашия браузър. Моля използвайте FireFox 2.0 или по висока версия, Opera 9.0 или по-висока, Internet Explorer 6.0 или по-висока версия, Safari 3.0.2 или по-висока версия.');
define('ErrorTurnedOffActiveX', 'Поддръжката на ActiveX е изключена . <br/>За да използвате приложението трябва да я включите.');

define('Calendar', 'Календар');

define('TabDay', 'Ден');
define('TabWeek', 'Седмица');
define('TabMonth', 'Месец');

define('ToolNewEvent', 'Ново&nbsp;Събитие');
define('ToolBack', 'Назад');
define('ToolToday', 'Днес');
define('AltNewEvent', 'Ново събитие');
define('AltBack', 'Назад');
define('AltToday', 'Днес');
define('CalendarHeader', 'Календар');
define('CalendarsManager', 'Управление на календара');

define('CalendarActionNew', 'Нов календар');
define('EventHeaderNew', 'Ново събитие');
define('CalendarHeaderNew', 'Нов Календар');

define('EventSubject', 'Тема');
define('EventCalendar', 'Календар');
define('EventFrom', 'От');
define('EventTill', 'до');
define('CalendarDescription', 'Описание');
define('CalendarColor', 'Цвят');
define('CalendarName', 'Име на календара');
define('CalendarDefaultName', 'Моят календар');

define('ButtonSave', 'Запис');
define('ButtonCancel', 'Отказ');
define('ButtonDelete', 'Изтриване');

define('AltPrevMonth', 'Предишен месец');
define('AltNextMonth', 'Следващ месец');

define('CalendarHeaderEdit', 'Редактиране на календар');
define('CalendarActionEdit', 'Редактиране на календар');
define('ConfirmDeleteCalendar', 'Моля, потвърдете изтриването на календара');
define('InfoDeleting', 'Изтриване ...');
define('WarningCalendarNameBlank', 'Името на календара на може да е празно.');
define('ErrorCalendarNotCreated', 'Календарът не беше създаден.');
define('WarningSubjectBlank', 'Не можа да оставите темата празна.');
define('WarningIncorrectTime', 'Указаното време съдържа непозволени символи.');
define('WarningIncorrectFromTime', 'Полето за време "От" е грешно.');
define('WarningIncorrectTillTime', 'Полето за време "До" е грешно.');
define('WarningStartEndDate', 'Крайната дата трябва да е по-голяма или равна на началната.');
define('WarningStartEndTime', 'Крайното време трябва да е по-голямо от началното.');
define('WarningIncorrectDate', 'Грешна дата.');
define('InfoLoading', 'Зареждане ...');
define('EventCreate', 'Създаване на събитие');
define('CalendarHideOther', 'Скриване на другите календари');
define('CalendarShowOther', 'Показване на другите календари');
define('CalendarRemove', 'Премахване на календар');
define('EventHeaderEdit', 'Редактиране на събитие');

define('InfoSaving', 'Записване ...');
define('SettingsDisplayName', 'Показване на името');
define('SettingsTimeFormat', 'Формат за време');
define('SettingsDateFormat', 'Формат за дата');
define('SettingsShowWeekends', 'Показване на уикендите');
define('SettingsWorkdayStarts', 'Работния ден започва');
define('SettingsWorkdayEnds', 'свършва');
define('SettingsShowWorkday', 'Показване на работните дни');
define('SettingsWeekStartsOn', 'Седмицата започва в');
define('SettingsDefaultTab', 'Да се показва');
define('SettingsCountry', 'Страна');
define('SettingsTimeZone', 'Часова зона');
define('SettingsAllTimeZones', 'Всички часови зони');

define('WarningWorkdayStartsEnds', 'Стойността в полето \'Работния ден свършва\' трябва да е поголяма от тази в \'Работния ден започва\'');
define('ReportSettingsUpdated', 'Настройките са актуализирани успешно.');

define('SettingsTabCalendar', 'Календар');

define('FullMonthJanuary', 'януари');
define('FullMonthFebruary', 'февруари');
define('FullMonthMarch', 'март');
define('FullMonthApril', 'април');
define('FullMonthMay', 'май');
define('FullMonthJune', 'юни');
define('FullMonthJuly', 'юли');
define('FullMonthAugust', 'август');
define('FullMonthSeptember', 'септември');
define('FullMonthOctober', 'октомври');
define('FullMonthNovember', 'ноември');
define('FullMonthDecember', 'декември');

define('ShortMonthJanuary', 'яну.');
define('ShortMonthFebruary', 'фев.');
define('ShortMonthMarch', 'март');
define('ShortMonthApril', 'апр.');
define('ShortMonthMay', 'май');
define('ShortMonthJune', 'юни');
define('ShortMonthJuly', 'юли');
define('ShortMonthAugust', 'авг.');
define('ShortMonthSeptember', 'сеп.');
define('ShortMonthOctober', 'окт.');
define('ShortMonthNovember', 'ное.');
define('ShortMonthDecember', 'дек.');

define('FullDayMonday', 'понеделник');
define('FullDayTuesday', 'вторник');
define('FullDayWednesday', 'сряда');
define('FullDayThursday', 'четвъртък');
define('FullDayFriday', 'петък');
define('FullDaySaturday', 'събота');
define('FullDaySunday', 'неделя');

define('DayToolMonday', 'пон.');
define('DayToolTuesday', 'втор.');
define('DayToolWednesday', 'сря.');
define('DayToolThursday', 'чет.');
define('DayToolFriday', 'пет.');
define('DayToolSaturday', 'съб.');
define('DayToolSunday', 'нед.');

define('CalendarTableDayMonday', 'п');
define('CalendarTableDayTuesday', 'в');
define('CalendarTableDayWednesday', 'с');
define('CalendarTableDayThursday', 'ч');
define('CalendarTableDayFriday', 'п');
define('CalendarTableDaySaturday', 'с');
define('CalendarTableDaySunday', 'н');

define('ErrorParseJSON', 'JSON отговорът от сървъра не може да бъде разбран.');

define('ErrorLoadCalendar', 'Календарите не може да бъдат заредени');
define('ErrorLoadEvents', 'Събитията не може да бъдат заредени');
define('ErrorUpdateEvent', 'Събитието не може да бъде записано');
define('ErrorDeleteEvent', 'Събитието не може да бъде изтрито');
define('ErrorUpdateCalendar', 'Календарът не може да бъде записан');
define('ErrorDeleteCalendar', 'Календарът не може да бъде изтрит');
define('ErrorGeneral', 'Сървърна грешка. Моля, опитайте по-късно.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Споделяне и публикуване на календар');
define('ShareActionEdit', 'Споделяне и публикуване на календар');
define('CalendarPublicate', 'Календарът да бъде публично достъпен');
define('CalendarPublicationLink', 'Връзка');
define('ShareCalendar', 'Споделяне на този календар');
define('SharePermission1', 'Може да се правят промени и управлява споделянето');
define('SharePermission2', 'Може да се правят промени на събитията');
define('SharePermission3', 'Може да се виждат детайлите на всички събития');
define('SharePermission4', 'Може да се вижда само свободно/заето (скриване на детайлите)');
define('ButtonClose', 'Затваряне');
define('WarningEmailFieldFilling', 'Полето e-mail трябва да бъде попълнено');
define('EventHeaderView', 'Преглед на събитие');
define('ErrorUpdateSharing', 'Данните за споделяне и публикуване не може да бъдат записани');
define('ErrorUpdateSharing1', 'Споделянето с потребителя %s е невъзможно, тъй като той не съществува');
define('ErrorUpdateSharing2', 'Този календар не може да се сподели с потребителя %s');
define('ErrorUpdateSharing3', 'Този календар вече е споделен с потребителя %s');
define('Title_MyCalendars', 'Моите календари');
define('Title_SharedCalendars', 'Споделени календари');
define('ErrorGetPublicationHash', 'Не може да се създаде връзка за публикацията');
define('ErrorGetSharing', 'Не може да се добави споделяне');
define('CalendarPublishedTitle', 'Този календар е публикуван');
define('RefreshSharedCalendars', 'Опресняване на споделените календари');
define('Title_CheckSharedCalendars', 'Презареждане на календарите');

define('GroupMembers', 'Членове');

define('ReportMessagePartDisplayed', '<i>Забележка:</i> показана е само част от писмото.');
define('ReportViewEntireMessage', 'За да прегледате цялото писмо,');
define('ReportClickHere', 'кликнете тук');
define('ErrorContactExists', 'контакт с това име и e-mail вече съществува.');

define('Attachments', 'Прикачени файлове');

define('InfoGroupsOfContact', 'Групите на този контакт са маркирани.');
define('AlertNoContactsSelected', 'Не са избрани контакти.');
define('MailSelected', 'Изпратете писмо до избраните адреси');
define('CaptionSubscribed', 'Абониран');

define('OperationSpam', 'Спам');
define('OperationNotSpam', 'Не е спам');
define('FolderSpam', 'Спам');

// webmail 4.4 contacts
define('ContactMail', 'Изпращане на писмо');
define('ContactViewAllMails', 'Показване на всички писма от този контакт');
define('ContactsMailThem', 'За писма');
define('DateToday', 'Днес');
define('DateYesterday', 'Вчера');
define('MessageShowDetails', 'Показване на подробности');
define('MessageHideDetails', 'Скриване на подробности');
define('MessageNoSubject', 'Няма тема');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'до');
define('SearchClear', 'Изчистване на търсенето');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Резултати от търсенето на "#s" в папка #f:');
define('SearchResultsInAllFolders', 'Резултати от търсенето на "#s" във всички папки:');
define('AutoresponderTitle', 'Автоматичен отговор');
define('AutoresponderEnable', 'Използване на автоматичен отговор');
define('AutoresponderSubject', 'Тема');
define('AutoresponderMessage', 'Писмо');
define('ReportAutoresponderUpdatedSuccessfuly', 'Автоматичният отговор е актуализиран успешно.');
define('FolderQuarantine', 'Карантина');

//calendar
define('EventRepeats', 'Повторения');
define('NoRepeats', 'Не се повтаря');
define('DailyRepeats', 'Дневно');
define('WorkdayRepeats', 'Всеки работен ден (пон. - пет.)');
define('OddDayRepeats', 'Всеки пон., сря. и пет.');
define('EvenDayRepeats', 'Всеки втор. и четв.');
define('WeeklyRepeats', 'Седмично');
define('MonthlyRepeats', 'Месечно');
define('YearlyRepeats', 'Годишно');
define('RepeatsEvery', 'Повтаря се всеки');
define('ThisInstance', 'Само това');
define('AllEvents', 'Всички събития в поредицата');
define('AllFollowing', 'Всички от следните');
define('ConfirmEditRepeatEvent', 'Искате да се промени само това събитие, всички събития или това и всички бъдещи събития от поредицата??');
define('RepeatEventHeaderEdit', 'Редактиране на повтарящи се събития');
define('First', 'Първо');
define('Second', 'Второ');
define('Third', 'Трето');
define('Fourth', 'Четвърто');
define('Last', 'Последно');
define('Every', 'Всеки');
define('SetRepeatEventEnd', 'Крайна дата');
define('NoEndRepeatEvent', 'Без крайна дата');
define('EndRepeatEventAfter', 'Край след');
define('Occurrences', 'повторение');
define('EndRepeatEventBy', 'Край до');
define('EventCommonDataTab', 'Главни детайли');
define('EventRepeatDataTab', 'Повтарящи се детайли');
define('RepeatEventNotPartOfASeries', 'Това събитие е променено и вече не е част от поредицата.');
define('UndoRepeatExclusion', 'Отмяна на промените за включване в поредицата.');

define('MonthMoreLink', 'още %d...');
define('NoNewSharedCalendars', 'Няма нови календари');
define('NNewSharedCalendars', 'Намерени са %d нови календара');
define('OneNewSharedCalendars', 'Намерен е 1 нов календар');
define('ConfirmUndoOneRepeat', 'Искате ли да се върне това събитие към поредиците?');

define('RepeatEveryDayInfin', 'Всеки ден');
define('RepeatEveryDayTimes', 'Всеки ден, %TIMES% пъти');
define('RepeatEveryDayUntil', 'Всеки ден, до %UNTIL%');
define('RepeatDaysInfin', 'Всеки %PERIOD% дни');
define('RepeatDaysTimes', 'Всеки %PERIOD% дни, %TIMES% пъти');
define('RepeatDaysUntil', 'Всеки %PERIOD% дни, до %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Всяка седмица в работни дни');
define('RepeatEveryWeekWeekdaysTimes', 'Всяка седмица в работни дни, %TIMES% пъти');
define('RepeatEveryWeekWeekdaysUntil', 'Всяка седмица в работни дни, до %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Всяка %PERIOD% седмица през работните дни');
define('RepeatWeeksWeekdaysTimes', 'Всяка %PERIOD% седмица през работните дни, %TIMES% пъти');
define('RepeatWeeksWeekdaysUntil', 'Всяка %PERIOD% седмица през работните дни, до %UNTIL%');

define('RepeatEveryWeekInfin', 'Всяка седмица в %DAYS%');
define('RepeatEveryWeekTimes', 'Всяка седмица в %DAYS%, %TIMES% пъти');
define('RepeatEveryWeekUntil', 'Всяка седмица в %DAYS%, до %UNTIL%');
define('RepeatWeeksInfin', 'Всяка %PERIOD% седмица в %DAYS%');
define('RepeatWeeksTimes', 'Всяка %PERIOD% седмица в %DAYS%, %TIMES% пъти');
define('RepeatWeeksUntil', 'Всяка %PERIOD% седмица в %DAYS%, до %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Всеки месец на %DATE%');
define('RepeatEveryMonthDateTimes', 'Всеки месец на %DATE%, %TIMES% пъти');
define('RepeatEveryMonthDateUntil', 'Всеки месец на %DATE%, до %UNTIL%');
define('RepeatMonthsDateInfin', 'Всеки %PERIOD% месец на %DATE%');
define('RepeatMonthsDateTimes', 'Всеки %PERIOD% месец на %DATE%, %TIMES% пъти');
define('RepeatMonthsDateUntil', 'Всеки %PERIOD% месец на %DATE%, до %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Всеки месец в %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Всеки месец в %NUMBER% %DAY%, %TIMES% пъти');
define('RepeatEveryMonthWDUntil', 'Всеки месец в %NUMBER% %DAY%, до %UNTIL%');
define('RepeatMonthsWDInfin', 'Всеки %PERIOD% месец в %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Всеки %PERIOD% месец в %NUMBER% %DAY%, %TIMES% пъти');
define('RepeatMonthsWDUntil', 'Всеки %PERIOD% месец в %NUMBER% %DAY%, до %UNTIL%');

define('RepeatEveryYearDateInfin', 'Всяка година на %DATE%');
define('RepeatEveryYearDateTimes', 'Всяка година на %DATE%, %TIMES% пъти');
define('RepeatEveryYearDateUntil', 'Всяка година на %DATE%, до %UNTIL%');
define('RepeatYearsDateInfin', 'Всяка %PERIOD% година на %DATE%');
define('RepeatYearsDateTimes', 'Всяка %PERIOD% година на %DATE%, %TIMES% пъти');
define('RepeatYearsDateUntil', 'Всяка %PERIOD% година на %DATE%, до %UNTIL%');

define('RepeatEveryYearWDInfin', 'Всяка година в %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Всяка година в %NUMBER% %DAY%, %TIMES% пъти');
define('RepeatEveryYearWDUntil', 'Всяка година в %NUMBER% %DAY%, до %UNTIL%');
define('RepeatYearsWDInfin', 'Всяка %PERIOD% година в %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Всяка %PERIOD% година в %NUMBER% %DAY%, %TIMES% пъти');
define('RepeatYearsWDUntil', 'Всяка %PERIOD% година в %NUMBER% %DAY%, до %UNTIL%');

define('RepeatDescDay', 'ден');
define('RepeatDescWeek', 'седмица');
define('RepeatDescMonth', 'месец');
define('RepeatDescYear', 'година');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Моля, задайте дата за повторение');
define('WarningWrongUntilDate', 'Крайната дата за повторение трябва да е по-голяма от началната');

define('OnDays', 'В дните');
define('CancelRecurrence', 'Отмяна на повторението');
define('RepeatEvent', 'Повторение на това събитие');

define('Spellcheck', 'Ппроверка на правописа');
define('LoginLanguage', 'Език');
define('LanguageDefault', 'по подразбиране');

// webmail 4.5.x new
define('EmptySpam', 'Изтриване на спама');
define('Saving', 'Записване ...');
define('Sending', 'Изпращане ...');
define('LoggingOffFromServer', 'Прекратяване на връзката със сървъра ...');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Писмото не може да се маркира като спам');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Писмата не може да се махнат от спама');
define('ExportToICalendar', 'Извеждане към iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Потребителят не може да бъде създаден, защото максималният брой потребители за Вашия лиценз е достигнат.');
define('RepliedMessageTitle', 'На писмото е отговорено');
define('ForwardedMessageTitle', 'Писмото е препратено');
define('RepliedForwardedMessageTitle', 'На писмото е отговорено и е препратено');
define('ErrorDomainExist', 'Потребителят не може да бъде създаден, защото съответният домейн не съществува. Първо създайте домейна.');

// webmail 4.7
define('RequestReadConfirmation', 'Потвърждение при прочитане');
define('FolderTypeDefault', 'по подразбиране');
define('ShowFoldersMapping', 'Искам да избера друга папка като системна (напр.  "Изходящи" за изпратените писма)');
define('ShowFoldersMappingNote', 'Например, за да промените мястото на Изпратени в "Моята папка" задайте "Моята папка" да се използва като "Изпратени" от падащия списък.');
define('FolderTypeMapTo', 'Да се използва за');

define('ReminderEmailExplanation', 'Получавате това писмо на акаунта си %EMAIL%, защото сте заявили уведомление за събитие в календара Ви: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Отваряне на календар');

define('AddReminder', 'Напомнете ме за това събитие');
define('AddReminderBefore', 'Напомнете ме % преди това събитие');
define('AddReminderAnd', 'и % след това');
define('AddReminderAlso', 'и също % преди него');
define('AddMoreReminder', 'Още напомняния');
define('RemoveAllReminders', 'Премахване на всички напомняния');
define('ReminderNone', 'Няма');
define('ReminderMinutes', 'минути');
define('ReminderHour', 'час');
define('ReminderHours', 'часа');
define('ReminderDay', 'ден');
define('ReminderDays', 'дни');
define('ReminderWeek', 'седмица');
define('ReminderWeeks', 'седмици');
define('Allday', 'Всички дни');

define('Folders', 'Папки');
define('NoSubject', 'Без тема');
define('SearchResultsFor', 'Резултати от търсенето за');

define('Back', 'Назад');
define('Next', 'Следващ');
define('Prev', 'Предишен');

define('MsgList', 'Писма');
define('Use24HTimeFormat', 'Използване на 24-часов формат');
define('UseCalendars', 'Използване на календари');
define('Event', 'Събитие');
define('CalendarSettingsNullLine', 'Няма календари');
define('CalendarEventNullLine', 'Няма събития');
define('ChangeAccount', 'промяна на акаунт');

define('TitleCalendar', 'Календар');
define('TitleEvent', 'Събитие');
define('TitleFolders', 'Папки');
define('TitleConfirmation', 'Потвърждение');

define('Yes', 'Да');
define('No', 'Не');

define('EditMessage', 'Редактиране на писмо');

define('AccountNewPassword', 'Нова парола');
define('AccountConfirmNewPassword', 'Потвърдете новата парола');
define('AccountPasswordsDoNotMatch', 'Паролите на съвпадат.');

define('ContactTitle', 'Заглавие');
define('ContactFirstName', 'Име');
define('ContactSurName', 'Фамилия');

define('ContactNickName', 'Прякор');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'презареждане');
define('CaptchaError', 'Текстът на Captcha не е верен.');

define('WarningInputCorrectEmails', 'Моля задайте верни email адреси.');
define('WrongEmails', 'Грешни email адреси:');

define('ConfirmBodySize1', 'За съжаление текстовото съобщение е дълго макс.');
define('ConfirmBodySize2', 'символа. Всичко след това ще бъде изтрити. Изберете "Отказ", ако искате да го редактирате.');
define('BodySizeCounter', 'брояч');
define('InsertImage', 'Вмъкване на изображение');
define('ImagePath', 'Път до изображението');
define('ImageUpload', 'Вмъкване');
define('WarningImageUpload', 'Прикачваният файл не е изображение. Моля изберете файл с изображение.');

define('ConfirmExitFromNewMessage', 'Ако излезете от тази страница без да запишете писмото ще загубите всички промени от последния запис. Моля, потвърдете!.');

define('SensivityConfidential', 'Това писмо е поверително!');
define('SensivityPrivate', 'Това писмо е за частна употреба!');
define('SensivityPersonal', 'Това писмо е лично!');

define('ReturnReceiptTopText', 'Изпращачът иска потвърждение, че сте отворили писмото.');
define('ReturnReceiptTopLink', 'Кликнете тук, за да изпратите потвърждение на изпращача.');
define('ReturnReceiptSubject', 'Обратна разписка <br />Return Receipt (displayed)');
define('ReturnReceiptMailText1', 'Това е обратна разписка на писмото, което изпратихте<br />This is a Return Receipt for the mail that you sent to <br />');
define('ReturnReceiptMailText2', 'Забележка: Тази обратна разписка само показва, че писмото е визуализирано на екрана на получателя. Няма гаранция, че той го е прочел и/или разбрал съдържанието му.<br />Note: This Return Receipt only acknowledges that the message was displayed on the recipient\'s computer. There is no guarantee that the recipient has read or understood the message contents.');
define('ReturnReceiptMailText3', 'с тема<br />with subject<br />');

define('SensivityMenu', 'Поверителност');
define('SensivityNothingMenu', 'Няма');
define('SensivityConfidentialMenu', 'Поверително');
define('SensivityPrivateMenu', 'Частно');
define('SensivityPersonalMenu', 'Лично');

define('ErrorLDAPonnect', 'Няма връзка с ldap сървъра.');

define('MessageSizeExceedsAccountQuota', 'Размера на писмото надхвърля Вашата квота.');
define('MessageCannotSent', 'Писмото не може да бъде изпратено.');
define('MessageCannotSaved', 'Писмото не може да бъде записано.');

define('ContactFieldTitle', 'Поле');
define('ContactDropDownTO', 'До');
define('ContactDropDownCC', 'ЯК');
define('ContactDropDownBCC', 'СК');

// 4.9
define('NoMoveDelete', 'Съобщенията не могат да бъдат преместени в Кошчето. Най-вероятно е превишена квотата Ви. Да бъдат ли изтрити завинаги?');

define('WarningFieldBlank', 'Това поле не може да е празно.');
define('WarningPassNotMatch', 'Паролите не съвпадат, моля проверете.');
define('PasswordResetTitle', 'Възстановяване на парола - стъпка %d');
define('NullUserNameonReset', 'потребител');
define('IndexResetLink', 'Забравена парола?');
define('IndexRegLink', 'Регистриране на акаунт');

define('RegDomainNotExist', 'Домейнът не съществува.');
define('RegAnswersIncorrect', 'Отговорите са грешни.');
define('RegUnknownAdress', 'Непознат email адрес.');
define('RegUnrecoverableAccount', 'Възстановяването на паролата не е възможно за този email адрес.');
define('RegAccountExist', 'Този адрес вече се използва.');
define('RegRegistrationTitle', 'Регистриране');
define('RegName', 'Име');
define('RegEmail', 'e-mail адрес');
define('RegEmailDesc', 'Например, myname@domain.com. Тази информация ще се използва за вход в системата.');
define('RegSignMe', 'Да бъда запомнен');
define('RegSignMeDesc', 'Да не се искат потр. име и парола при следващото влизане в системата от този коммпютър.');
define('RegPass1', 'Парола');
define('RegPass2', 'Повторете паролата ');
define('RegQuestionDesc', 'Моля, дайте два тайни въпроса и отговорите им, които знаете само Вие. Ако забравите паролата си може да я възстановите само чрез тях.');
define('RegQuestion1', 'Таен въпрос 1');
define('RegAnswer1', 'Отговор 1');
define('RegQuestion2', 'Таен въпрос 2');
define('RegAnswer2', 'Отговор 2');
define('RegTimeZone', 'Часова зона');
define('RegLang', 'Език');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Регистриране');

define('ResetEmail', 'Моля, дайте Вашия email');
define('ResetEmailDesc', 'Дайте email адрес за регистрация.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Изпращане');
define('ResetQuestion1', 'Таен въпрос 1');
define('ResetAnswer1', 'Отговор');
define('ResetQuestion2', 'Таен въпрос 2');
define('ResetAnswer2', 'Отговор');
define('ResetSubmitStep2', 'Изпращане');

define('ResetTopDesc1Step2', 'Подаден email адрес');
define('ResetTopDesc2Step2', 'Моля, потвърдете верността.');

define('ResetTopDescStep3', 'моля дайте отдолу новата си парола.');

define('ResetPass1', 'Нова парола');
define('ResetPass2', 'Повторете паролата');
define('ResetSubmitStep3', 'Изпращане');
define('ResetDescStep4', 'Паролата Ви е сменена.');
define('ResetSubmitStep4', 'Връщане');

define('RegReturnLink', 'Връщане към входа');
define('ResetReturnLink', 'Връщане към входа');

// Appointments
define('AppointmentAddGuests', 'Добавяне на гости');
define('AppointmentRemoveGuests', 'Отмяна на среща');
define('AppointmentListEmails', 'Въведете email адреси, разделени със запетая и натиснете Запис');
define('AppointmentParticipants', 'Участници');
define('AppointmentRefused', 'Отказано');
define('AppointmentAwaitingResponse', 'Очаква отговор');
define('AppointmentInvalidGuestEmail', 'Следните email адреси на гости са грешни:');
define('AppointmentOwner', 'Собственик');

define('AppointmentMsgTitleInvite', 'Покана за събитие.');
define('AppointmentMsgTitleUpdate', 'Събитието е променено.');
define('AppointmentMsgTitleCancel', 'Събитието е отменено.');
define('AppointmentMsgTitleRefuse', 'Гостът %guest% отказва поканата');
define('AppointmentMoreInfo', 'Още информация');
define('AppointmentOrganizer', 'Организатор');
define('AppointmentEventInformation', 'Информация за събитието');
define('AppointmentEventWhen', 'Кога');
define('AppointmentEventParticipants', 'Участници');
define('AppointmentEventDescription', 'Описание');
define('AppointmentEventWillYou', 'Ще участвате ли');
define('AppointmentAdditionalParameters', 'Допълнителни параметри');
define('AppointmentHaventRespond', 'Още не е отговорено');
define('AppointmentRespondYes', 'Ще участвам');
define('AppointmentRespondMaybe', 'Още не е сигурно');
define('AppointmentRespondNo', 'Няма да участва');
define('AppointmentGuestsChangeEvent', 'Гостите могат да променят събития');

define('AppointmentSubjectAddStart', 'Получили сте покана за събитие ');
define('AppointmentSubjectAddFrom', ' от ');
define('AppointmentSubjectUpdateStart', 'Редактиране на събитие ');
define('AppointmentSubjectDeleteStart', 'Отмяна на събитие ');
define('ErrorAppointmentChangeRespond', 'Не може да се промени отговора за срещата');
define('SettingsAutoAddInvitation', 'Автоматично добавяне на покани в календара');
define('ReportEventSaved', 'Събитието е записано');
define('ReportAppointmentSaved', ' и уведомленията са изпратени');
define('ErrorAppointmentSend', 'Поканата не може да бъде изпратена.');
define('AppointmentEventName', 'Име:');

// End appointments

define('ErrorCantUpdateFilters', 'Филтрите не могат да бъдат актуализирани');

define('FilterPhrase', 'Ако полето %field %condition %string тогава %action');
define('FiltersAdd', 'Добавяне на филтър');
define('FiltersCondEqualTo', 'е равно на');
define('FiltersCondContainSubstr', 'съдържа низ');
define('FiltersCondNotContainSubstr', 'не съдържа низ');
define('FiltersActionDelete', 'изтриване на писмото');
define('FiltersActionMove', 'преместване');
define('FiltersActionToFolder', 'в папка %folder');
define('FiltersNo', 'Няма зададени филтри');

define('ReminderEmailFriendly', 'напомняне');
define('ReminderEventBegin', 'започва в: ');

define('FiltersLoading', 'Зареждане на филтри...');
define('ConfirmMessagesPermanentlyDeleted', 'Всички писма в тази папка ще бъдат изтрити завинаги!');

define('InfoNoNewMessages', 'Няма нови писма.');
define('TitleImportContacts', 'Внасяне на контакти');
define('TitleSelectedContacts', 'избраните контакти');
define('TitleNewContact', 'Нов контакт');
define('TitleViewContact', 'Преглед на контакта');
define('TitleEditContact', 'Редактиране на контакта');
define('TitleNewGroup', 'Нова група');
define('TitleViewGroup', 'Преглед на групата');

define('AttachmentComplete', 'Прикачен.');

define('TestButton', 'ТЕСТ');
define('AutoCheckMailIntervalLabel', 'Автоматична проверка на пощата през');
define('AutoCheckMailIntervalDisableName', 'изключено');

define('ReportCalendarSaved', 'Календарът е записан.');

define('ContactSyncError', 'Синхронизацията не беше успешна!');
define('ReportContactSyncDone', 'Синхронизацията е завършена');

define('MobileSyncUrlTitle', 'URL за мобилна синхронизация');
define('MobileSyncLoginTitle', 'Потр. име за мобилна синхронизация');

define('QuickReply', 'Бърз отговор');
define('SwitchToFullForm', 'Отваряне в цял прозорец');
define('SortFieldDate', 'дата');
define('SortFieldFrom', 'подател');
define('SortFieldSize', 'размер');
define('SortFieldSubject', 'тема');
define('SortFieldFlag', 'флаг');
define('SortFieldAttachments', 'Прикачени файлове');
define('SortOrderAscending', 'възходящо');
define('SortOrderDescending', 'низходящо');
define('ArrangedBy', 'Сортиране');

define('MessagePaneToRight', 'Писмата се визуализират в дясно от списъка с писма, а не отдолу');

define('SettingsTabMobileSync', 'Мобилни');

define('MobileSyncContactDataBaseTitle', 'Синхронизиране с мобилните контакти');
define('MobileSyncCalendarDataBaseTitle', 'Синхронизиране с мобилния календар');
define('MobileSyncTitleText', 'Ако искате да синхронизирате мобилното си устройство с WebMail, може да използвате следните параметри<br />"URL за мобилна синхронизация" указва пътя до сървъра за синхронизиране на SyncML данни, "Потр. име за мобилна синхронизация" е потребителското Ви име за същия сървър. Също така трябва да използвате и паролата си за него. Освен това на някой устройство трябва да се укаже база данни за контакти и календар.<br />За това използвайте полетата "Мобилна база данни за контакти" и "Мобилна база данни за календар".');
define('MobileSyncEnableLabel', 'Разрешаване на мобилна синхронизация');

define('SearchInputText', 'търсене');

define('AppointmentEmailExplanation','Вие получавате това писмо на акаунта си %EMAIL%, защото сте поканени на събитието от %ORGANAZER%');

define('Searching', 'Търсене ...');

define('ButtonSetupSpecialFolders', 'Настройка на специални папки');
define('ButtonSaveChanges', 'Запис');
define('InfoPreDefinedFolders', 'За предварително дефинирани папки използвайте следните IMAP пощенски кутии');

define('SaveMailInSentItems', 'Запис в изпратени');

define('CouldNotSaveUploadedFile', 'Файлът не можа да бъде записан!.');

define('AccountOldPassword', 'Текуща парола');
define('AccountOldPasswordsDoNotMatch', 'Текущата парола не съвпада.');

define('DefEditor', 'Редактор по подразбиране');
define('DefEditorRichText', 'HTML текст');
define('DefEditorPlainText', 'обикновен текст');

define('Layout', 'Разположение');

define('TitleNewMessagesCount', '%count% нов/и писмо(а)');

define('AltOpenInNewWindow', 'Отваряне в нов прозорец');

define('SearchByFirstCharAll', 'Всичко');

define('FolderNoUsageAssigned', 'Не се използва');

define('InfoSetupSpecialFolders', 'За да свържете една специална папка (напр. Изпратени) с дадена папка от IMAP пощенската кутия, използвайте настройката на специални папки.');

define('FileUploaderClickToAttach', 'Кликнете, за да прикачите файл');
define('FileUploaderOrDragNDrop', 'или хванете файл и го пуснете тук');

define('AutoCheckMailInterval1Minute', '1 мин.');
define('AutoCheckMailInterval3Minutes', '3 мин.');
define('AutoCheckMailInterval5Minutes', '5 мин.');
define('AutoCheckMailIntervalMinutes', 'минути');

define('ReadAboutCSVLink', 'Научете повече за полетата във файловете с формат .CSV');

define('VoiceMessageSubj', 'Гласово съобщение');
define('VoiceMessageTranscription', 'Транскрипция');
define('VoiceMessageReceived', 'Получено');
define('VoiceMessageDownload', 'Сваляне');
define('VoiceMessageUpgradeFlashPlayer', 'Трябва да си актуализирате Вашия Adobe Flash Player, за да прослушвате гласови съобщения.<br />Това може да стане от <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'Лицензът е остарял, моля свържете се с AfterLogic за осъвременяването му');
define('LicenseProblem', 'Проблем с лиценза. Системния администратор трябва да го провери в административния панел.');

define('AccountOldPasswordNotCorrect', 'Текущата Ви парола не е вярна');
define('AccountNewPasswordUpdateError', 'Не може да се запише новата парола.');
define('AccountNewPasswordRejected', 'Не може да се запише новата парола. Вероятно е твърде проста.');

define('CantCreateIdentity', 'Не може да се създаде самоличността');
define('CantUpdateIdentity', 'Не може да се актуализира самоличността');
define('CantDeleteIdentity', 'Не може да се изтрие самоличността');

define('AddIdentity', 'Добавяне на самоличност');
define('SettingsTabIdentities', 'Самоличности');
define('NoIdentities', 'Няма самоличности');
define('NoSignature', 'Без подпис');
define('Account', 'Акаунт');
define('TabChangePassword', 'Парола');
define('SignatureEnteringHere', 'Въведете подписа си тук');

define('CantConnectToMailServer', 'Няма връзка с пощенския сървър');

define('DomainNameNotSpecified', 'Името на домейна не е указано.');

define('Open', 'Отваряне');
define('FolderUsedAs', 'използвана като');
define('ForwardTitle', 'Препращане');
define('ForwardEnable', 'Използване на препращане');
define('ReportForwardUpdatedSuccessfuly', 'Препращането беше актуализирано успешно.');

define('DialogAttachHeaderResume', 'Прикачете Вашето резюме');
define('DialogAttachHeaderLetter', 'Прикачете Вашето придружително писмо');
define('DialogAttachName', 'Изберете резюме');
define('DialogAttachType', 'Изберете формат');
define('DialogAttachTypePdf', 'Adobe PDF (.pdf)');
define('DialogAttachTypeHtml', 'Уеб страница (.html)');
define('DialogAttachTypeRtf', 'Обогатен текст (.rtf)');
define('DialogAttachTypeTxt', 'Обикновен текст (.txt)');
define('DialogAttachTypeDoc', 'MS Word (.doc)');
define('DialogAttachButton', 'Прикачване');
define('DialogAttachResume', 'Прикачване на резюме');
define('DialogAttachLetter', 'Прикачване на придружително писмо');
define('DialogAttachAnother', 'Прикачване на друг файл');
define('DialogAttachAddToBody', 'Добавяне на обикновен текст към тялото на писмото (Препоръчва се)');
define('DialogAttachTypeNo', 'Няма прикачен файл');
define('DialogAttachSelectLetter', 'Изберете придружително писмо');
define('DialogAttachTypePdfRecom', 'Adobe PDF (.pdf) (Препоръчва се)');
define('DialogAttachTypeTextInBody', 'Обикновен текст в тялото на писмото - препоръчва се');
define('DialogAttachTypeTxtAttach', 'Прикачен файл Обикновен текст (.txt)');
define('CustomTitle', 'Препращане');
define('ForwardingNotificationsTo', 'Изпращане на уведомление до <b>%email</b>');
define('ForwardingForwardTo', 'Препращане на писмо до <b>%email</b>');
define('ForwardingNothing', 'Няма уведомяване за email или препращане');
define('ForwardingChange', 'промяна');

define('ConfirmSaveForward', 'Настройките за препращане не са записани. Натиснете OK, за да ги запишете.');
define('ConfirmSaveAutoresponder', 'Настройките за автоматичен отговор не са записани. Натиснете OK, за да ги запишете.');

define('DigDosMenuItem', 'DigDos');
define('DigDosTitle', 'Изберете обект');

define('LastLoginTitle', 'Последно влизане');
define('ExportContacts', 'Изнасяне на контакти');

define('JS_LANG_Gb', ' GB');

define('ContactsTabGlobal', 'глобално');
define('ContactsTabPersonal', 'персонално');
define('InfoLoadingContacts', 'WebMail зарежда списъка с контактите');

define('TheAccessToThisAccountIsDisabled', 'Достъпът до този акаунт е забранен');

define('MobileSyncDavServerURL', 'URL на DAV сървър');
define('MobileSyncPrincipalURL', 'Главен URL');
define('MobileSyncHintDesc', 'Използване на тези настройки за синхронизиране на календара с мобилно устройство, което поддържа протокола CalDAV. При iPhone, например, обикновено Ви трябва URL на DAV сървър, потребителско име и парола.');

define('MobileGetIOSSettings', 'Доставка на настройките за e-mail и календар на вашето iOS устройство');
define('IOSLoginHeadTitle', 'Инсталиране на iOS профил');
define('IOSLoginHelloAppleTitle', 'Здравейте,');
define('IOSLoginHelpDesc1', 'Може автоматично да получите настройките за e-mail, контакти и календар на Вашето iOS устройство.');
define('IOSLoginHelpDesc2', 'Винаги можете да ги получите по-късно,');
define('IOSLoginHelpDesc3', 'в секцията Настройки/Мобилни.');
define('IOSLoginButtonYesPlease', 'Да, моля.');
define('IOSLoginButtonSkip', 'Прескачане на това и влизане');
define('IOSLoginPage2HelloAppleTitle', 'Акаунтът Ви е готов!');
define('IOSLoginPage2HelpDesc1', 'С новия профил можете да синхронизирате e-mail и календар на Вашето iOS устройство чрез неговите приложения.');
define('IOSLoginPage2HelpDesc2', 'Ако желаете, можете също така да използвате webmail за това.');
define('IOSLoginPage2ButtonOpenWebMail', 'Отваряне на webmail');

define('LoginBrowserWarning', 'За съжаление този браузър не се поддържа.<br/>Препоръчваме Ви един от следните:<br/><a href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Internet Explorer 7</a>, <a href="http://www.firefox.com/">Mozilla Firefox 2</a>, <a href="http://www.apple.com/safari/download/">Safari 2</a>, <a href="http://www.opera.com/">Opera 9</a> или техни по-нови версии.');

define('AppointmentInvitation', 'Покана');
define('AppointmentAccepted', 'приета');
define('AppointmentDeclined', 'отказана');
define('AppointmentTentativelyAccepted', 'колебливо приета');
define('AppointmentLocation', 'Местоположение');
define('AppointmentCalendar', 'Календар');
define('AppointmentWhen', 'Кога');
define('AppointmentDescription', 'Описание');
define('AppointmentButtonAccept', 'Приемане');
define('AppointmentButtonTentative', 'С колебание');
define('AppointmentButtonDecline', 'Отказ');

define('ContactDisplayName', 'Име');

define('WarningCreatingGroupRequiresContacts', 'Създаването на група изисква добавяне на поне един контакт в нея.');
define('WarningRemovingAllContactsFromGroup', 'Премахването на всички контакти от групата премахва и самата група. Моля, потвърдете!');
define('WarningSendEmailToDemoOnly', 'От съображения за сигурност демонстрационният акаунт може да изпраща писма само на демонстрационни акаунти.');

define('SettingsTabOutlookSync', 'Синхронизиране с Outlook');

define('OutlookSyncServerURL', 'Сървър');

define('OutlookSyncHintDesc', 'За да синхронизирате Вашия Outlook календар, задайте следните настройки в плъгина за синхронизиране на Outlook:');

define('WarningMailboxAlmostFull', 'Пощенската Ви кутия е почти пълна.');
define('WarningCouldNotSaveDraftAsYourMailboxIsOverQuota', 'Черновата не може да се запише, понеже пощенската Ви кутия е достигнала квотата си.');
define('WarningSentEmailNotSaved', 'Писмото е изпратено, но не беше записано в "Изпратени", защото сте достигнали квотата си.');

define('DavSyncHeading', 'DAV синхронизация чрез един линк (за Apple клиенти)');
define('DavSyncHint', 'Използвайте долния линк, за да синхронизирате календара и контактите си с Apple iCal или мобилно устройство като iPhone или iPad (те поддържат синхронизация на множество CalDAV или CardDAV чрез единствен линк). Между другото можете да вземете автоматично Вашия iOS профил, ако ползвате тази поща от подобно устройство!');
define('DavSyncServer', 'DAV сървър');
define('DavSyncHeadingLogin', 'Също така ви трябва потребителско име и парола:');
define('DavSyncLogin', 'Потребителско име за мобилно синхронизиране');
define('DavSyncPasswordTitle', 'Парола');
define('DavSyncPasswordValue', 'Парола на акаунта Ви');
define('DavSyncSeparateUrlsHeading', 'DAV синхронизиране чрез отделни линкове');
define('DavSyncHintUrls', 'Ако Вашите CalDAV или CardDAV клиенти изискват отделни линкове за календар и контакт (напр. Mozilla Thunderbird Lightning или Evolution), използвайте следните линкове.');
define('DavSyncHeadingCalendar', 'CalDAV достъп за календар');
define('DavSyncHeadingContacts', 'CardDAV достъп за контакти');
define('DavSyncPersonalContacts', 'Лични контакти');
define('DavSyncCollectedAddresses', 'Събрани контакти');
define('DavSyncGlobalAddressBook', 'Глобални контакти');

define('ActiveSyncHeading', 'ActiveSync');
define('ActiveSyncHint', 'За да синхронизирате Вашия e-mail, контакти и календар чрез EAS (Exchange ActiveSync), използвайте следните настройки:');
define('ActiveSyncServer', 'Сървър');
define('ActiveSyncLogin', 'Потр. име');
define('ActiveSyncPasswordTitle', 'Парола');
define('ActiveSyncPasswordValue', 'Парола за акаунта Ви');

define('SearchStop', 'Стоп търсене');
define('ErrorDuringSearch', 'Получи се грешка при търсенето');
define('ErrorRetrievingMessages', 'Получи се грешка при извличането на списъка с писма');

define('AppointmentCanceled', '%SENDER% прекратено');

define('CalendarDavUrl', 'DAV линк');
define('CalendarIcsLink', 'линк към .ics');
define('CalendarIcsDownload', 'Сваляне');

define('DavSyncDemoPasswordValue', 'demo');

define('ActiveSyncDemoPasswordValue', 'demo');

define('ConfirmUnsubscribeCalendar', 'Сигурни ли сте, че не искате да ползвате повече календара');
define('CalendarUnsubscribe', 'Да се спре');
define('InfoUnsubscribing', 'Спиране ...');

define('ErrorDataTransferFailed', 'Неуспешно прехвърляне на данни, вероятно поради сървърна грешка. Моля, свържете се със системния администратор.');
define('ErrorCantReachServer', 'Сървърът е недостъпен.');
define('RetryGettingMessageList', 'Отново');
define('BackToMessageList', 'Обратно към писмата');

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
