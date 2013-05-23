<?php
define('PROC_ERROR_ACCT_CREATE', 'Произошла ошибка во время создания аккаунта.');
define('PROC_WRONG_ACCT_PWD', 'Неправильный пароль.');
define('PROC_CANT_LOG_NONDEF', 'Попытка залогиниться в недефолтный аккаунт.');
define('PROC_CANT_INS_NEW_FILTER', 'Ошибка при создании фильтра.');
define('PROC_FOLDER_EXIST', 'Папка с указанным именем уже существует.');
define('PROC_CANT_CREATE_FLD', 'Ошибка при создании папки.');
define('PROC_CANT_INS_NEW_GROUP', 'Ошибка при добавлении новой группы');
define('PROC_CANT_INS_NEW_CONT', 'Ошибка при создании контакта.');
define('PROC_CANT_INS_NEW_CONTS', 'Ошибка при добавлении нового(ых) контакта(ов).');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Ошибка при добавить контакта(ов) в группу.');
define('PROC_ERROR_ACCT_UPDATE', 'Произошла ошибка во время обновления аккаунта.');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Ошибка при обновлении настроек контактов.');
define('PROC_CANT_GET_SETTINGS', 'Ошибка при получении настроек');
define('PROC_CANT_UPDATE_ACCT', 'Ошибка при обновлении аккаунта.');
define('PROC_ERROR_DEL_FLD', 'Произошла ошибка во время удаления папки(ок).');
define('PROC_CANT_UPDATE_CONT', 'Ошибка при обновлении контакта.');
define('PROC_CANT_GET_FLDS', 'Ошибка при получении дерева папок.');
define('PROC_CANT_GET_MSG_LIST', 'Ошибка при получении списка папок.');
define('PROC_MSG_HAS_DELETED', 'Возможно, сообщение было удалено с сервера.');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Ошибка при загрузке настроек контактов.');
define('PROC_CANT_LOAD_SIGNATURE', 'Ошибка при загрузке подписи аккаунта.');
define('PROC_CANT_GET_CONT_FROM_DB', 'Ошибка при получении контакта из базы данных.');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Ошибка при получении контакта(ов) из базы данных.');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Ошибка при удалении аккаунта по идентификатору.');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Ошибка при удалении фильтра по идентификатору');
define('PROC_CANT_DEL_CONT_GROUPS', 'Ошибка при удалении контакта(ов) и/или групп(ы).');
define('PROC_WRONG_ACCT_ACCESS', 'Обнаружена попытка несанкционированного доступа к аккаунту другим пользователем.');
define('PROC_SESSION_ERROR', 'Предыдущая сессия была завершена по тайм-ауту.');

define('MailBoxIsFull', 'Почтовый ящик полон.');
define('WebMailException', 'WebMail: произошла ошибка.');
define('InvalidUid', 'Неправильный UID сообщения.');
define('CantCreateContactGroup', 'Ошибка при создании группы контактов.');
define('CantCreateUser', 'Ошибка при создании пользователя.');
define('CantCreateAccount', 'Ошибка при создании аккаунта.');
define('SessionIsEmpty', 'Пустая сессия.');
define('FileIsTooBig', 'Файл слишком большой.');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Невозможно пометить все сообщения прочитанными.');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Невозможно пометить все сообщения непрочитанными.');
define('PROC_CANT_PURGE_MSGS', 'Невозможно удалить помеченные на удаление сообщения(й).');
define('PROC_CANT_DEL_MSGS', 'Ошибка при удалении сообщения(й).');
define('PROC_CANT_UNDEL_MSGS', 'Невозможно пометить неудаленными сообщение(я).');
define('PROC_CANT_MARK_MSGS_READ', 'Невозможно пометить сообщение(я) прочитанными.');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Невозможно пометить сообщение(я) непрочитанными.');
define('PROC_CANT_SET_MSG_FLAGS', 'Невозможно выставить флаг сообщению(ям).');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Невозможно снять флаг сообщению(ям).');
define('PROC_CANT_CHANGE_MSG_FLD', 'Ошибка при перемещении письма(ем) в другую папку.');
define('PROC_CANT_SEND_MSG', 'Ошибка при отправлении письма.');
define('PROC_CANT_SAVE_MSG', 'Ошибка при сохранении письма.');
define('PROC_CANT_GET_ACCT_LIST', 'Ошибка при получении списка аккаунтов.');
define('PROC_CANT_GET_FILTER_LIST', 'Ошибка при получении списка фильтров.');

define('PROC_CANT_LEAVE_BLANK', 'Поля, помеченные *, обязательны для заполнения.');

define('PROC_CANT_UPD_FLD', 'Ошибка при обновлении папки.');
define('PROC_CANT_UPD_FILTER', 'Ошибка при обновлении фильтра.');

define('ACCT_CANT_ADD_DEF_ACCT', 'Этот аккаунт невозможно добавить, потому что он используется как дефолтный другим пользователем.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Невозможно изменить статус данного аккаунта на дефолтный.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Ошибка при создании аккаунта (подключение к IMAP4-серверу).');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Невозможно удалить последний дефолтный аккаунт.');

define('LANG_LoginInfo', 'Информация для входа');
define('LANG_Email', 'Электропочта');
define('LANG_Login', 'Логин');
define('LANG_Password', 'Пароль');
define('LANG_IncServer', 'Входящая почта');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Порт');
define('LANG_OutServer', 'SMTP-сервер');
define('LANG_OutPort', 'Порт');
define('LANG_UseSmtpAuth', 'Использовать SMTP-аутентификацию');
define('LANG_SignMe', 'Запомнить меня');
define('LANG_Enter', 'Войти');

define('JS_LANG_TitleLogin', 'Логин');
define('JS_LANG_TitleMessagesListView', 'Список писем');
define('JS_LANG_TitleMessagesList', 'Список писем');
define('JS_LANG_TitleViewMessage', 'Просмотр сообщения');
define('JS_LANG_TitleNewMessage', 'Новое сообщение');
define('JS_LANG_TitleSettings', 'Настройки');
define('JS_LANG_TitleContacts', 'Контакты');

define('JS_LANG_StandardLogin', 'Стандартный&nbsp;логин');
define('JS_LANG_AdvancedLogin', 'Расширенный&nbsp;логин');

define('JS_LANG_InfoWebMailLoading', 'Подождите, пока WebMail загрузится&hellip;');
define('JS_LANG_Loading', 'Загрузка&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Подождите, пока WebMail загрузит список сообщений&hellip;');
define('JS_LANG_InfoEmptyFolder', 'В папке нет сообщений');
define('JS_LANG_InfoPageLoading', 'Страница грузится&hellip;');
define('JS_LANG_InfoSendMessage', 'Сообщение успешно отправлено.');
define('JS_LANG_InfoSaveMessage', 'Сообщение успешно сохранено.');
//You have imported 3 new contact(s) into your contacts list.
define('JS_LANG_InfoHaveImported', 'Было импортировано');
define('JS_LANG_InfoNewContacts', 'новых контактов в список контактов.');
define('JS_LANG_InfoToDelete', 'Чтобы удалить папку');
define('JS_LANG_InfoDeleteContent', ', необходимо сначала удалить все ее содержимое.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Удаление непустых папок недоступно. Предварительно удалите все их содержимое.');
define('JS_LANG_InfoRequiredFields', '* обязательные поля');

define('JS_LANG_ConfirmAreYouSure', 'Вы уверены?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Выделенные сообщения будут НАВСЕГДА удалены! Вы уверены?');
define('JS_LANG_ConfirmSaveSettings', 'Настройки не были сохранены. Выберите OK для сохранения.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Настройки контактов не были сохранены. Выберите OK для сохранения.');
define('JS_LANG_ConfirmSaveAcctProp', 'Свойства аккаунта не были сохранены. Выберите OK для сохранения.');
define('JS_LANG_ConfirmSaveFilter', 'Свойства фильтра не были сохранены. Выберите OK для сохранения.');
define('JS_LANG_ConfirmSaveSignature', 'Подпись не была сохранена. Выберите OK для сохранения.');
define('JS_LANG_ConfirmSavefolders', 'Папки не были сохранены. Выберите OK для сохранения.');
define('JS_LANG_ConfirmHtmlToPlain', 'Предупреждение: При изменении форматирования сообщения с HTML на простой текст текущее форматирование будет потеряно. Выберите OK для продолжения.');
define('JS_LANG_ConfirmAddFolder', 'Перед добавлением папки необходимо применить изменения. Выберите OK для сохранения.');
define('JS_LANG_ConfirmEmptySubject', 'Поле темы пустое. Хотите продолжить?');

define('JS_LANG_WarningEmailBlank', 'Необходимо заполнить поле Электропочта.');
define('JS_LANG_WarningLoginBlank', 'Необходимо заполнить поле Логин.');
define('JS_LANG_WarningToBlank', 'Необходимо заполнить поле Кому');
define('JS_LANG_WarningServerPortBlank', 'Необходимо заполнить поля POP3 и<br />SMTP сервера/порта');
define('JS_LANG_WarningEmptySearchLine', 'Строка поиска пустая. Введите, пожалуйста подстроку, которую необходимо найти.');
define('JS_LANG_WarningMarkListItem', 'Выберите, пожалуйста, хотя бы один элемент в списке.');
define('JS_LANG_WarningFolderMove', 'Невозможно переместить папку, потому что она другого уровня.');
define('JS_LANG_WarningContactNotComplete', 'Введите, пожалуйста, электропочту или имя.');
define('JS_LANG_WarningGroupNotComplete', 'Введите, пожалуйста, имя группы.');

define('JS_LANG_WarningEmailFieldBlank', 'Необходимо заполнить поле Электропочта.');
define('JS_LANG_WarningIncServerBlank', 'Необходимо заполнить поле POP3(IMAP4) сервера.');
define('JS_LANG_WarningIncPortBlank', 'Необходимо заполнить поле порта POP3(IMAP4) сервера.');
define('JS_LANG_WarningIncLoginBlank', 'Необходимо заполнить поле POP3(IMAP4) логина.');
define('JS_LANG_WarningIncPortNumber', 'Необходимо указать положительное число в поле порта POP3(IMAP4) сервера.');
define('JS_LANG_DefaultIncPortNumber', 'Значение порта POP3(IMAP4) по умолчанию - 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Необходимо заполнить поле POP3(IMAP4) пароля.');
define('JS_LANG_WarningOutPortBlank', 'Необходимо заполнить поле порта SMTP сервера.');
define('JS_LANG_WarningOutPortNumber', 'Необходимо указать положительное число в поле порта SMTP сервера.');
define('JS_LANG_WarningCorrectEmail', 'Необходимо указать корректное значение электропочты.');
define('JS_LANG_DefaultOutPortNumber', 'Значение порта SMTP по умолчанию - 25.');

define('JS_LANG_WarningCsvExtention', 'Расширение файла должно быть - .csv');
define('JS_LANG_WarningImportFileType', 'Выберите, пожалуйста, приложение, из которого вы хотите импортировать контакты.');
define('JS_LANG_WarningEmptyImportFile', 'Выберите, пожалуйста, файл.');

define('JS_LANG_WarningContactsPerPage', 'Значение поля Контактов на страницу должно быть положительным числом.');
define('JS_LANG_WarningMessagesPerPage', 'Значение поля Сообщений на страницу должно быть положительным числом.');
define('JS_LANG_WarningMailsOnServerDays', 'Необходимо указать положительное число в поле Количество дней хранения сообщений на сервере.');
define('JS_LANG_WarningEmptyFilter', 'Введите подстроку, пожалуйста.');
define('JS_LANG_WarningEmptyFolderName', 'Введите, пожалуйста, имя папки.');

define('JS_LANG_ErrorConnectionFailed', 'Неудачное соединение.');
define('JS_LANG_ErrorRequestFailed', 'Загрузка данных не была завершена.');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Объект XMLHttpRequest отсутствует.');
define('JS_LANG_ErrorWithoutDesc', 'Произошла неизвестная ошибка.');
define('JS_LANG_ErrorParsing', 'Ошибка разбора XML.');
define('JS_LANG_ResponseText', 'Текст ответа:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Пустой XML пакет.');
define('JS_LANG_ErrorImportContacts', 'Произошла ошибка во время импорта контактов.');
define('JS_LANG_ErrorNoContacts', 'Нет контактов для импорта.');
define('JS_LANG_ErrorCheckMail', 'Получение сообщений прекращено из-за ошибки. Возможно, не все сообщения были приняты.');

define('JS_LANG_LoggingToServer', 'Соединение с сервером&hellip;');
define('JS_LANG_GettingMsgsNum', 'Получение количества сообщений');
define('JS_LANG_RetrievingMessage', 'Получение сообщения');
define('JS_LANG_DeletingMessage', 'Удаление сообщения');
define('JS_LANG_DeletingMessages', 'Удаление сообщения(й)');
define('JS_LANG_Of', 'из');
define('JS_LANG_Connection', 'Соединение');
define('JS_LANG_Charset', 'Кодировка');
define('JS_LANG_AutoSelect', 'Автоматический выбор');

define('JS_LANG_Contacts', 'Контакты');
define('JS_LANG_ClassicVersion', 'Классическая версия');
define('JS_LANG_Logout', 'Выход');
define('JS_LANG_Settings', 'Настройки');

define('JS_LANG_LookFor', 'Строка поиска');
define('JS_LANG_SearchIn', 'Искать в');
define('JS_LANG_QuickSearch', 'Искать только в полях От, Кому и Тема (быстрый поиск).');
define('JS_LANG_SlowSearch', 'Искать во всем сообщении.');
define('JS_LANG_AllMailFolders', 'Все папки');
define('JS_LANG_AllGroups', 'Все группы');

define('JS_LANG_NewMessage', 'Новое сообщение');
define('JS_LANG_CheckMail', 'Проверить почту');
define('JS_LANG_EmptyTrash', 'Очистить корзину');
define('JS_LANG_MarkAsRead', 'Пометить прочитанным');
define('JS_LANG_MarkAsUnread', 'Пометить непрочитанным');
define('JS_LANG_MarkFlag', 'Выставить флаг');
define('JS_LANG_MarkUnflag', 'Снять флаг');
define('JS_LANG_MarkAllRead', 'Пометить все письма прочитанными');
define('JS_LANG_MarkAllUnread', 'Пометить все письма непрочитанными');
define('JS_LANG_Reply', 'Ответить');
define('JS_LANG_ReplyAll', 'Ответить всем');
define('JS_LANG_Delete', 'Удалить');
define('JS_LANG_Undelete', 'Отменить удаление');
define('JS_LANG_PurgeDeleted', 'Удалить помеченные');
define('JS_LANG_MoveToFolder', 'Переместить в папку');
define('JS_LANG_Forward', 'Переслать');

define('JS_LANG_HideFolders', 'Скрыть папки');
define('JS_LANG_ShowFolders', 'Показать папки');
define('JS_LANG_ManageFolders', 'Настройка папок');
define('JS_LANG_SyncFolder', 'Синхронизируемая папка');
define('JS_LANG_NewMessages', 'Новые сообщения');
define('JS_LANG_Messages', 'Сообщение(й)');

define('JS_LANG_From', 'От');
define('JS_LANG_To', 'Кому');
define('JS_LANG_Date', 'Дата');
define('JS_LANG_Size', 'Размер');
define('JS_LANG_Subject', 'Тема');

define('JS_LANG_FirstPage', 'Первая страница');
define('JS_LANG_PreviousPage', 'Предыдущая страница');
define('JS_LANG_NextPage', 'Следующая страница');
define('JS_LANG_LastPage', 'Последняя страница');

define('JS_LANG_SwitchToPlain', 'Переключить на простой текст');
define('JS_LANG_SwitchToHTML', 'Переключить на HTML');
define('JS_LANG_AddToAddressBook', 'Добавить в адресную книгу');
define('JS_LANG_ClickToDownload', 'Кликните для загрузки');
define('JS_LANG_View', 'Просмотр');
define('JS_LANG_ShowFullHeaders', 'Показать все заголовки');
define('JS_LANG_HideFullHeaders', 'Скрыть все заголовки');

define('JS_LANG_MessagesInFolder', 'сообщение(й) в папке');
define('JS_LANG_YouUsing', 'Вы используете');
define('JS_LANG_OfYour', 'из');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Отправить');
define('JS_LANG_SaveMessage', 'Сохранить');
define('JS_LANG_Print', 'Печать');
define('JS_LANG_PreviousMsg', 'Предыдущее сообщение');
define('JS_LANG_NextMsg', 'Следующее сообщение');
define('JS_LANG_AddressBook', 'Адресная книга');
define('JS_LANG_ShowBCC', 'Показать скрытые копии');
define('JS_LANG_HideBCC', 'Спрятать скрытые копии');
define('JS_LANG_CC', 'Копии');
define('JS_LANG_BCC', 'Скрытые копии');
define('JS_LANG_ReplyTo', 'Обратный адрес');
define('JS_LANG_AttachFile', 'Прикрепить файл');
define('JS_LANG_Attach', 'Загрузить');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Пересылаемое сообщение');
define('JS_LANG_Sent', 'Отправлено');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Низкий');
define('JS_LANG_Normal', 'Обычный');
define('JS_LANG_High', 'Высокий');
define('JS_LANG_Importance', 'Приоритет');
define('JS_LANG_Close', 'Закрыть');

define('JS_LANG_Common', 'Общие');
define('JS_LANG_EmailAccounts', 'Аккаунты');

define('JS_LANG_MsgsPerPage', 'Сообщений на странице');
define('JS_LANG_DisableRTE', 'Запретить HTML редактор');
define('JS_LANG_Skin', 'Скин');
define('JS_LANG_DefCharset', 'Кодировка');
define('JS_LANG_DefCharsetInc', 'Входящая кодировка по умолчанию');
define('JS_LANG_DefCharsetOut', 'Исходящая кодировка по умолчанию');
define('JS_LANG_DefTimeOffset', 'Часовой пояс');
define('JS_LANG_DefLanguage', 'Язык');
define('JS_LANG_DefDateFormat', 'Формат даты');
define('JS_LANG_ShowViewPane', 'Список писем с просмотром письма');
define('JS_LANG_Save', 'Сохранить');
define('JS_LANG_Cancel', 'Отмена');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Удалить');
define('JS_LANG_AddNewAccount', 'Добавить новый аккаунт');
define('JS_LANG_Signature', 'Подпись');
define('JS_LANG_Filters', 'Фильтры');
define('JS_LANG_Properties', 'Свойства');
define('JS_LANG_UseForLogin', 'Использовать настройки данного аккаунта (логин и пароль) для входа');
define('JS_LANG_MailFriendlyName', 'Ваше имя');
define('JS_LANG_MailEmail', 'Электропочта');
define('JS_LANG_MailIncHost', 'Сервер входящей почты');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Порт');
define('JS_LANG_MailIncLogin', 'Логин');
define('JS_LANG_MailIncPass', 'Пароль');
define('JS_LANG_MailOutHost', 'SMTP сервер');
define('JS_LANG_MailOutPort', 'Порт');
define('JS_LANG_MailOutLogin', 'SMTP логин');
define('JS_LANG_MailOutPass', 'SMTP пароль');
define('JS_LANG_MailOutAuth1', 'Использовать SMTP аутентификацию');
define('JS_LANG_MailOutAuth2', '(Вы можете оставить поля SMTP логина и/или пароля пустыми, если они совпадают с полями POP3(IMAP4) логина и/или пароля)');
define('JS_LANG_UseFriendlyNm1', 'Использовать дружественное имя в поле От');
define('JS_LANG_UseFriendlyNm2', '(Ваше имя &lt;sender@mail.ru&gt;)');
define('JS_LANG_GetmailAtLogin', 'Получать/синхронизировать почту при входе');
define('JS_LANG_MailMode0', 'Удалять принятые письма с почтового сервера');
define('JS_LANG_MailMode1', 'Оставлять почту на сервере');
define('JS_LANG_MailMode2', 'Сохранять почту на сервере');
define('JS_LANG_MailsOnServerDays', 'день(дней)');
define('JS_LANG_MailMode3', 'Удалять сообщения на почтовом сервере при удалении их из Корзины');
define('JS_LANG_InboxSyncType', 'Тип синхронизации папки Входящие');

define('JS_LANG_SyncTypeNo', 'Не синхронизировать');
define('JS_LANG_SyncTypeNewHeaders', 'Новые заголовки');
define('JS_LANG_SyncTypeAllHeaders', 'Все заголовки');
define('JS_LANG_SyncTypeNewMessages', 'Новые сообщения');
define('JS_LANG_SyncTypeAllMessages', 'Все сообщения');
define('JS_LANG_SyncTypeDirectMode', 'Режим прямого доступа');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Заголовки');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Сообщения');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Режим прямого доступа');

define('JS_LANG_DeleteFromDb', 'Удалять сообщения из базы данных, если они не существуют на почтовом сервере');

define('JS_LANG_EditFilter', 'Редактировать');
define('JS_LANG_NewFilter', 'Создать фильтр');
define('JS_LANG_Field', 'Поле');
define('JS_LANG_Condition', 'Условие');
define('JS_LANG_ContainSubstring', 'Содержит подстроку');
define('JS_LANG_ContainExactPhrase', 'Содержит точную фразу');
define('JS_LANG_NotContainSubstring', 'Не содержит подстроку');
define('JS_LANG_FilterDesc_At', 'в поле');
define('JS_LANG_FilterDesc_Field', '');
define('JS_LANG_Action', 'Действие');
define('JS_LANG_DoNothing', 'Ничего не делать');
define('JS_LANG_DeleteFromServer', 'Удалить с сервера немедленно');
define('JS_LANG_MarkGrey', 'Пометить серым');
define('JS_LANG_Add', 'Добавить');
define('JS_LANG_OtherFilterSettings', 'Другие настройки фильтров');
define('JS_LANG_ConsiderXSpam', 'Разбирать X-Spam заголовки');
define('JS_LANG_Apply', 'Применить');

define('JS_LANG_InsertLink', 'Вставить ссылку');
define('JS_LANG_RemoveLink', 'Убрать ссылку');
define('JS_LANG_Numbering', 'Нумерация');
define('JS_LANG_Bullets', 'Список');
define('JS_LANG_HorizontalLine', 'Горизонтальная линия');
define('JS_LANG_Bold', 'Полужирный');
define('JS_LANG_Italic', 'Курсив');
define('JS_LANG_Underline', 'Подчеркнутый');
define('JS_LANG_AlignLeft', 'По левому краю');
define('JS_LANG_Center', 'По центру');
define('JS_LANG_AlignRight', 'По правому краю');
define('JS_LANG_Justify', 'По ширине');
define('JS_LANG_FontColor', 'Цвет текста');
define('JS_LANG_Background', 'Цвет фона');
define('JS_LANG_SwitchToPlainMode', 'Переключить в текстовый режим');
define('JS_LANG_SwitchToHTMLMode', 'Переключить в HTML режим');

define('JS_LANG_Folder', 'Папка');
define('JS_LANG_Msgs', 'Сообщений');
define('JS_LANG_Synchronize', 'Синхронизация');
define('JS_LANG_ShowThisFolder', 'Показывать папку');
define('JS_LANG_Total', 'Всего');
define('JS_LANG_DeleteSelected', 'Удалить выбранные');
define('JS_LANG_AddNewFolder', 'Добавить новую папку');
define('JS_LANG_NewFolder', 'Новая папка');
define('JS_LANG_ParentFolder', 'Родительская папка');
define('JS_LANG_NoParent', 'Нет родителя');
define('JS_LANG_FolderName', 'Имя папки');

define('JS_LANG_ContactsPerPage', 'Контактов на странице');
define('JS_LANG_WhiteList', 'Использовать адресную книгу как Белый список');

define('JS_LANG_CharsetDefault', 'По умолчанию');
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

define('JS_LANG_TimeDefault', 'По умолчанию');
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
define('JS_LANG_TimeTonga', 'Nuku\'alofa, Tonga,');

define('JS_LANG_DateDefault', 'По умолчанию');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Янв)');
define('JS_LANG_DateAdvanced', 'Другой');

define('JS_LANG_NewContact', 'Новый контакт');
define('JS_LANG_NewGroup', 'Новая группа');
define('JS_LANG_AddContactsTo', 'Добавить контакт(ы) в');
define('JS_LANG_ImportContacts', 'Импорт контактов');

define('JS_LANG_Name', 'Имя');
define('JS_LANG_Email', 'Электропочта');
define('JS_LANG_DefaultEmail', 'Электропочта');
define('JS_LANG_NotSpecifiedYet', 'Еще не указана');
define('JS_LANG_ContactName', 'Имя');
define('JS_LANG_Birthday', 'День Рождения');
define('JS_LANG_Month', 'Месяц');
define('JS_LANG_January', 'Январь');
define('JS_LANG_February', 'Февраль');
define('JS_LANG_March', 'Март');
define('JS_LANG_April', 'Апрель');
define('JS_LANG_May', 'Май');
define('JS_LANG_June', 'Июнь');
define('JS_LANG_July', 'Июль');
define('JS_LANG_August', 'Август');
define('JS_LANG_September', 'Сентябрь');
define('JS_LANG_October', 'Октябрь');
define('JS_LANG_November', 'Ноябрь');
define('JS_LANG_December', 'Декабрь');
define('JS_LANG_Day', 'День');
define('JS_LANG_Year', 'Год');
define('JS_LANG_UseFriendlyName1', 'Использовать имя');
define('JS_LANG_UseFriendlyName2', '(например, Вася Пупкин &lt;vasya@mail.ru&gt;)');
define('JS_LANG_Personal', 'Дом');
define('JS_LANG_PersonalEmail', 'Домашняя электропочта');
define('JS_LANG_StreetAddress', 'Адрес');
define('JS_LANG_City', 'Город');
define('JS_LANG_Fax', 'Факс');
define('JS_LANG_StateProvince', 'Регион');
define('JS_LANG_Phone', 'Телефон');
define('JS_LANG_ZipCode', 'Индекс');
define('JS_LANG_Mobile', 'Мобильный');
define('JS_LANG_CountryRegion', 'Страна');
define('JS_LANG_WebPage', 'Web');
define('JS_LANG_Go', 'Проверить');
define('JS_LANG_Home', 'Дом');
define('JS_LANG_Business', 'Работа');
define('JS_LANG_BusinessEmail', 'Рабочая электропочта');
define('JS_LANG_Company', 'Компания');
define('JS_LANG_JobTitle', 'Название работы');
define('JS_LANG_Department', 'Департамент');
define('JS_LANG_Office', 'Офис');
define('JS_LANG_Pager', 'Пейджер');
define('JS_LANG_Other', 'Остальное');
define('JS_LANG_OtherEmail', 'Дополнительная электропочта');
define('JS_LANG_Notes', 'Заметки');
define('JS_LANG_Groups', 'Группы');
define('JS_LANG_ShowAddFields', 'Показать дополнительные поля');
define('JS_LANG_HideAddFields', 'Скрыть дополнительные поля');
define('JS_LANG_EditContact', 'Редактировать информацию по контакту');
define('JS_LANG_GroupName', 'Имя группы');
define('JS_LANG_AddContacts', 'Добавить контакты');
define('JS_LANG_CommentAddContacts', '(Если вы хотите указать больше одного адреса, разделяйте их запятыми)');
define('JS_LANG_CreateGroup', 'Создать группу');
define('JS_LANG_Rename', 'Переименовать');
define('JS_LANG_MailGroup', 'Написать группе');
define('JS_LANG_RemoveFromGroup', 'Удалить из группы');
define('JS_LANG_UseImportTo', 'Используйте импорт для копирования контактов из Microsoft Outlook, Microsoft Outlook Express в ваш список контактов.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Выберите файл (.CSV формата), который желаете импортировать');
define('JS_LANG_Import', 'Импортировать');
define('JS_LANG_ContactsMessage', 'Страница контактов');
define('JS_LANG_ContactsCount', 'контакт(ов)');
define('JS_LANG_GroupsCount', 'групп(а)');

//webmail 4.1 constants
define('PicturesBlocked', 'Картинки в сообщении блокированы из соображений безопасности.');
define('ShowPictures', 'Показать картинки');
define('ShowPicturesFromSender', 'Всегда показывать картинки в сообщениях от данного отправителя');
define('AlwaysShowPictures', 'Всегда показывать картинки в сообщениях');

define('TreatAsOrganization', 'Рассматривать как организацию');

define('WarningGroupAlreadyExist', 'Группа с таким именем уже существует. Укажите другое имя, пожалуйста.');
define('WarningCorrectFolderName', 'Необходимо указать корректное имя папки.');
define('WarningLoginFieldBlank', 'Необходимо ввести значение в поле Логин.');
define('WarningCorrectLogin', 'Необходимо указать корректное значение поля Логин.');
define('WarningPassBlank', 'Необходимо указать значение поля Пароль.');
define('WarningCorrectIncServer', 'Необходимо указать корректное значение поля POP3(IMAP) сервер.');
define('WarningCorrectSMTPServer', 'Необходимо указать корректное значение поля SMTP сервер.');
define('WarningFromBlank', 'Необходимо указать значение поля Кому.');
define('WarningAdvancedDateFormat', 'Укажите, пожалуйста, формат даты.');

define('AdvancedDateHelpTitle', 'Расширенная дата');
define('AdvancedDateHelpIntro', 'Если выбрано поле &quot;Другая&quot;, то вы можете использовать текстовое поле ввода для собственного формата даты, в котором будет отображаться дата в списке сообщений. Следующие опции можно использовать, разделяя их знаками \':\' или \'/\':');
define('AdvancedDateHelpConclusion', 'Например, если вы укажете &quot;mm/dd/yyyy&quot; в текстовом поле, дата будет отображаться как месяц/день/год (i.e. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'День в месяце (от 1 до 31)');
define('AdvancedDateHelpNumericMonth', 'Месяц (от 1 до 12)');
define('AdvancedDateHelpTextualMonth', 'Месяц (от Jan до Dec)');
define('AdvancedDateHelpYear2', 'Год, 2 цифры');
define('AdvancedDateHelpYear4', 'Год, 4 цифры');
define('AdvancedDateHelpDayOfYear', 'День в году (от 1 до 366)');
define('AdvancedDateHelpQuarter', 'Квартал');
define('AdvancedDateHelpDayOfWeek', 'День в неделе (от Mon до Sun)');
define('AdvancedDateHelpWeekOfYear', 'Неделя в году (от 1 до 53)');

define('InfoNoMessagesFound', 'Ни одно сообщение не найдено.');
define('ErrorSMTPConnect', 'Ошибка соединения с SMTP сервером. Проверьте настройки SMTP сервера.');
define('ErrorSMTPAuth', 'Неправильные логин и/или пароль. Неудачная аутентификация.');
define('ReportMessageSent', 'Ваше сообщение отправлено.');
define('ReportMessageSaved', 'Ваше сообщение сохранено.');
define('ErrorPOP3Connect', 'Ошибка соединения с POP3 сервером, проверьте настройки POP3 сервера.');
define('ErrorIMAP4Connect', 'Ошибка соединения с IMAP4 сервером, проверьте настройки IMAP4 сервера.');
define('ErrorPOP3IMAP4Auth', 'Неправильные электропочта, логин и/или пароль. Неудачная аутентификация.');
define('ErrorGetMailLimit', 'Извините, превышен лимит использования вашего ящика.');

define('ReportSettingsUpdatedSuccessfuly', 'Настройки успешно обновлены.');
define('ReportAccountCreatedSuccessfuly', 'Аккаунт успешно создан.');
define('ReportAccountUpdatedSuccessfuly', 'Аккаунт успешно обновлен.');
define('ConfirmDeleteAccount', 'Вы действительно хотите удалить аккаунт?');
define('ReportFiltersUpdatedSuccessfuly', 'Фильтры успешно обновлены.');
define('ReportSignatureUpdatedSuccessfuly', 'Подпись успешно обновлена.');
define('ReportFoldersUpdatedSuccessfuly', 'Папки успешно обновлены.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Настройки контактов успешно обновлены.');

define('ErrorInvalidCSV', 'Выбранный CSV файл имеет неправильный формат.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Группа');
define('ReportGroupSuccessfulyAdded2', 'была успешно добавлена.');
define('ReportGroupUpdatedSuccessfuly', 'Группа успешно обновлена.');
define('ReportContactSuccessfulyAdded', 'Контакт успешно добавлен.');
define('ReportContactUpdatedSuccessfuly', 'Контакт успешно обновлен.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Контакт(ы) добавлены в группу');
define('AlertNoContactsGroupsSelected', 'Никакие группы или контакты не выбраны.');

define('InfoListNotContainAddress', 'Если в списке нет адреса, который вы ищете, продолжайте набирать его первые буквы.');

define('DirectAccess', 'П');
define('DirectAccessTitle', 'Режим прямого доступа. WebMail получает доступ к письмам напрямую с почтового сервера.');

define('FolderInbox', 'Входящие');
define('FolderSentItems', 'Отправленные');
define('FolderDrafts', 'Черновики');
define('FolderTrash', 'Корзина');

define('FileLargerAttachment', 'Размер файла превышает Attachment Size limit.');
define('FilePartiallyUploaded', 'Произошла неизвестная ошибка. Загружена только часть файла.');
define('NoFileUploaded', 'Никакой файл не был загружен.');
define('MissingTempFolder', 'Временная папка отсутствует.');
define('MissingTempFile', 'Временный файл отсутствует.');
define('UnknownUploadError', 'Произошла неизвестная ошибка загрузки файла.');
define('FileLargerThan', 'Ошибка загрузки файла. Возможно, файл больше, чем ');
define('PROC_CANT_LOAD_DB', 'Ошибка соединения с базой данных.');
define('PROC_CANT_LOAD_LANG', 'Языковой файл отсутствует.');
define('PROC_CANT_LOAD_ACCT', 'Аккаунт не существует, возможно, он только что был удален.');

define('DomainDosntExist', 'Такой домен отсутствует в почтовом сервере.');
define('ServerIsDisable', 'Использование почтового сервера запрещено администратором.');

define('PROC_ACCOUNT_EXISTS', 'Аккаунт не может быть создан, потому что он уже существует.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Ошибка при попытке получить количество сообщений.');
define('PROC_CANT_MAIL_SIZE', 'Ошибка при попытке получить размер почтового ящика.');

define('Organization', 'Организация');
define('WarningOutServerBlank', 'Необходимо заполнить поле SMTP сервера.');

//
define('JS_LANG_Refresh', 'Обновить');
define('JS_LANG_MessagesInInbox', 'сообщение(й)');
define('JS_LANG_InfoEmptyInbox', 'Нет сообщений');

// webmail 4.2 constants
define('BackToList', 'Назад к списку писем');
define('InfoNoContactsGroups', 'Список контактов и групп пуст.');
define('InfoNewContactsGroups', 'Вы можете создать новые контакты/группы или импортировать контакты из .CSV файла в формате MS Outlook.');
define('DefTimeFormat', 'Формат времени');
define('SpellNoSuggestions', 'нет вариантов');
define('SpellWait', 'подождите, пожалуйста&hellip;');

define('InfoNoMessageSelected', 'Не выбрано ни одно сообщение.');
define('InfoSingleDoubleClick', 'Вы можете одним щелчком мыши на сообщении из списка просмотреть его здесь или двойным щелчком - в полноэкранном режиме.');

// calendar
define('TitleDay', 'Просмотр дня');
define('TitleWeek', 'Просмотр недели');
define('TitleMonth', 'Просмотр месяца');

define('ErrorNotSupportBrowser', 'AfterLogic Calendar не поддерживает ваш браузер. Пожалуйста, используйте FireFox 2.0 и выше, Opera 9.0 и выше, Internet Explorer 6.0 и выше, Safari 3.0.2 и выше.');
define('ErrorTurnedOffActiveX', 'Возможно, у вас отключена поддержка ActiveX в Internet Explorer. Необходимо включить их для использования Календаря.');

define('Calendar', 'Календарь');

define('TabDay', 'День');
define('TabWeek', 'Неделя');
define('TabMonth', 'Месяц');

define('ToolNewEvent', 'Новое&nbsp;событие');
define('ToolBack', 'Назад');
define('ToolToday', 'Сегодня');
define('AltNewEvent', 'Новое событие');
define('AltBack', 'Назад');
define('AltToday', 'Сегодня');
define('CalendarHeader', 'Календарь');
define('CalendarsManager', 'Менеджер календарей');

define('CalendarActionNew', 'Новый календарь');
define('EventHeaderNew', 'Новое событие');
define('CalendarHeaderNew', 'Новый календарь');

define('EventSubject', 'Тема');
define('EventCalendar', 'Календарь');
define('EventFrom', 'От');
define('EventTill', 'до');
define('CalendarDescription', 'Описание');
define('CalendarColor', 'Цвет');
define('CalendarName', 'Имя календаря');
define('CalendarDefaultName', 'Мой календарь');

define('ButtonSave', 'Сохранить');
define('ButtonCancel', 'Отменить');
define('ButtonDelete', 'Удалить');

define('AltPrevMonth', 'Предыдущий месяц');
define('AltNextMonth', 'Следующий месяц');

define('CalendarHeaderEdit', 'Редактирование календаря');
define('CalendarActionEdit', 'Редактировать календарь');
define('ConfirmDeleteCalendar', 'Вы уверены, что хотите удалить календарь');
define('InfoDeleting', 'Удаление&hellip;');
define('WarningCalendarNameBlank', 'Нельзя оставить поле имени календаря пустым.');
define('ErrorCalendarNotCreated', 'Календарь не создан.');
define('WarningSubjectBlank', 'Нельзя оставить поле темы пустым.');
define('WarningIncorrectTime', 'Указанное время содержит недопустимые символы.');
define('WarningIncorrectFromTime', 'От время некорректное.');
define('WarningIncorrectTillTime', 'До время некорректное.');
define('WarningStartEndDate', 'Конечная дата должна быть больше или такая же, как начальная.');
define('WarningStartEndTime', 'Конечное время должно быть больше начального.');
define('WarningIncorrectDate', 'Некорректно указана дата.');
define('InfoLoading', 'Загрузка&hellip;');
define('EventCreate', 'Создать событие');
define('CalendarHideOther', 'Скрыть другие календари');
define('CalendarShowOther', 'Показать другие календари');
define('CalendarRemove', 'Удалить календарь');
define('EventHeaderEdit', 'Редактирование события');

define('InfoSaving', 'Сохранение&hellip;');
define('SettingsDisplayName', 'Отображаемое имя');
define('SettingsTimeFormat', 'Формат времени');
define('SettingsDateFormat', 'Формат даты');
define('SettingsShowWeekends', 'Показывать выходные');
define('SettingsWorkdayStarts', 'Рабочий день начинается');
define('SettingsWorkdayEnds', 'заканчивается');
define('SettingsShowWorkday', 'Выделять рабочий день');
define('SettingsWeekStartsOn', 'Неделя начинается в');
define('SettingsDefaultTab', 'Таб по умолчанию');
define('SettingsCountry', 'Страна');
define('SettingsTimeZone', 'Временная зона');
define('SettingsAllTimeZones', 'Все временные зоны');

define('WarningWorkdayStartsEnds', 'Время окончания рабочего дня должно быть больше чем время начала рабочего дня');
define('ReportSettingsUpdated', 'Настройки успешно сохранены.');

define('SettingsTabCalendar', 'Календарь');

define('FullMonthJanuary', 'Январь');
define('FullMonthFebruary', 'Февраль');
define('FullMonthMarch', 'Март');
define('FullMonthApril', 'Апрель');
define('FullMonthMay', 'Май');
define('FullMonthJune', 'Июнь');
define('FullMonthJuly', 'Июль');
define('FullMonthAugust', 'Август');
define('FullMonthSeptember', 'Сентябрь');
define('FullMonthOctober', 'Октябрь');
define('FullMonthNovember', 'Ноябрь');
define('FullMonthDecember', 'Декабрь');

define('ShortMonthJanuary', 'Янв');
define('ShortMonthFebruary', 'Фев');
define('ShortMonthMarch', 'Мар');
define('ShortMonthApril', 'Апр');
define('ShortMonthMay', 'Май');
define('ShortMonthJune', 'Июн');
define('ShortMonthJuly', 'Июл');
define('ShortMonthAugust', 'Авг');
define('ShortMonthSeptember', 'Сен');
define('ShortMonthOctober', 'Окт');
define('ShortMonthNovember', 'Ноя');
define('ShortMonthDecember', 'Дек');

define('FullDayMonday', 'Понедельник');
define('FullDayTuesday', 'Вторник');
define('FullDayWednesday', 'Среда');
define('FullDayThursday', 'Четверг');
define('FullDayFriday', 'Пятница');
define('FullDaySaturday', 'Суббота');
define('FullDaySunday', 'Воскресенье');

define('DayToolMonday', 'Пн');
define('DayToolTuesday', 'Вт');
define('DayToolWednesday', 'Ср');
define('DayToolThursday', 'Чт');
define('DayToolFriday', 'Пт');
define('DayToolSaturday', 'Сб');
define('DayToolSunday', 'Вс');

define('CalendarTableDayMonday', 'Пн');
define('CalendarTableDayTuesday', 'Вт');
define('CalendarTableDayWednesday', 'Ср');
define('CalendarTableDayThursday', 'Чт');
define('CalendarTableDayFriday', 'Пт');
define('CalendarTableDaySaturday', 'Сб');
define('CalendarTableDaySunday', 'Вс');

define('ErrorParseJSON', 'Произошла ошибка парсинга JSON ответа сервера.');

define('ErrorLoadCalendar', 'Невозможно загрузить календари.');
define('ErrorLoadEvents', 'Невозможно загрузить события.');
define('ErrorUpdateEvent', 'Невозможно сохранить событие.');
define('ErrorDeleteEvent', 'Невозможно удалить событие.');
define('ErrorUpdateCalendar', 'Невозможно сохранить календарь.');
define('ErrorDeleteCalendar', 'Невозможно удалить календарь.');
define('ErrorGeneral', 'На сервере произошла ошибка. Попробуйте позже.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Открытие доступа');
define('ShareActionEdit', 'Открыть доступ');
define('CalendarPublicate', 'Открыть общий доступ к этому календарю');
define('CalendarPublicationLink', 'Ссылка');
define('ShareCalendar', 'Общий доступ для отдельных пользователей');
define('SharePermission1', 'Вносить изменения и предоставлять доступ');
define('SharePermission2', 'Вносить изменения');
define('SharePermission3', 'Просматривать все сведения о мероприятиях');
define('SharePermission4', 'Просматривать информацию только о свободном и занятом времени');
define('ButtonClose', 'Закрыть');
define('WarningEmailFieldFilling', 'Необходимо заполнить поле E-mail');
define('EventHeaderView', 'Просмотр события');
define('ErrorUpdateSharing', 'Невозможно сохранить данные об общем доступе');
define('ErrorUpdateSharing1', 'Невозможно предоставить доступ к календарю пользователю %s, т.к. он не зарегистрирован в системе');
define('ErrorUpdateSharing2', 'Невозможно предоставить доступ к календарю пользователю %s');
define('ErrorUpdateSharing3', 'Пользователю %s уже предоставлен доступ к данному календарю');
define('Title_MyCalendars', 'Мои календари');
define('Title_SharedCalendars', 'Другие календари');
define('ErrorGetPublicationHash', 'Невозможно предоставить общий доступ к этому календарю');
define('ErrorGetSharing', 'Невозможно предоставить доступ к этому календарю');
define('CalendarPublishedTitle', 'Этот календарь опубликован');
define('RefreshSharedCalendars', 'Обновить другие календари');
define('Title_CheckSharedCalendars', 'Обновить календари');

define('GroupMembers', 'Участники');

define('ReportMessagePartDisplayed', 'Обратите внимание, что письмо отображено не полностью.');
define('ReportViewEntireMessage', 'Вы можете просмотреть его целиком,');
define('ReportClickHere', 'кликнув здесь');
define('ErrorContactExists', 'Контакт с таким именем и емейлом уже существует.');

define('Attachments', 'Вложения');

define('InfoGroupsOfContact', 'Контакт является участником тех групп, которые отмечены.');
define('AlertNoContactsSelected', 'Ни один контакт не выбран.');
define('MailSelected', 'Написать выбранным адресатам');
define('CaptionSubscribed', 'Подписка');

define('OperationSpam', 'Спам');
define('OperationNotSpam', 'Не спам');
define('FolderSpam', 'Спам');

// webmail 4.4 contacts
define('ContactMail', 'Написать');
define('ContactViewAllMails', 'Смотреть письма с этим контактом');
define('ContactsMailThem', 'Написать');
define('DateToday', 'Сегодня');
define('DateYesterday', 'Вчера');
define('MessageShowDetails', 'Показать детали');
define('MessageHideDetails', 'Скрыть детали');
define('MessageNoSubject', 'Без темы');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'для');
define('SearchClear', 'Отменить поиск');
// Search results for "search string" in Inbox folder:
define('SearchResultsInFolder', 'Результаты поиска для "#s" в папке #f:');
// Search results for "search string" in all mail folders:
define('SearchResultsInAllFolders', 'Результаты поиска для "#s" во всех папках:');
define('AutoresponderTitle', 'Автоответчик');
define('AutoresponderEnable', 'Включить автоответчик');
define('AutoresponderSubject', 'Тема');
define('AutoresponderMessage', 'Сообщение');
define('ReportAutoresponderUpdatedSuccessfuly', 'Автоответчик успешно обновлен.');
define('FolderQuarantine', 'Карантин');

// calendar
define('EventRepeats', 'Повтор');
define('NoRepeats', 'Не повторяется');
define('DailyRepeats', 'Каждый день');
define('WorkdayRepeats', 'Каждую неделю (Пн. - Пт.)');
define('OddDayRepeats', 'Каждый Пн., Ср. и Пт.');
define('EvenDayRepeats', 'Каждый Вт. и Чт.');
define('WeeklyRepeats', 'Каждую неделю');
define('MonthlyRepeats', 'Каждый месяц');
define('YearlyRepeats', 'Каждый год');
define('RepeatsEvery', 'Повторять каждый');
define('ThisInstance', 'Только в этот раз');
define('AllEvents', 'Все мероприятия в этой серии');
define('AllFollowing', 'Все следующие');
define('ConfirmEditRepeatEvent', 'Изменить только это мероприятие или все мероприятия в этой серии?');
define('RepeatEventHeaderEdit', 'Изменить повторяющееся мероприятие');
define('First', 'Первый');
define('Second', 'Второй');
define('Third', 'Третий');
define('Fourth', 'Четвертый');
define('Last', 'Последний');
define('Every', 'Каждый');
define('SetRepeatEventEnd', 'Окончание');
define('NoEndRepeatEvent', 'Нет даты окончания');
define('EndRepeatEventAfter', 'Закончить после');
define('Occurrences', 'повторов');
define('EndRepeatEventBy', 'Закончить');
define('EventCommonDataTab', 'Основные детали');
define('EventRepeatDataTab', 'Детали повторения');
define('RepeatEventNotPartOfASeries', 'Это мероприятие было изменено и больше не входит в серию.');
define('UndoRepeatExclusion', 'Отмените изменения, чтобы включить его в серию.');

define('MonthMoreLink', 'ещё %d...');
define('NoNewSharedCalendars', 'Нет новых календарей');
define('NNewSharedCalendars', 'Найдено %d новых календаря');
define('OneNewSharedCalendars', 'Найден 1 новый календарь');
define('ConfirmUndoOneRepeat', 'Восстановить событие в серии повторений?');

define('RepeatEveryDayInfin', 'Каждый день');
define('RepeatEveryDayTimes', 'Каждый день %TIMES% раз(а)');
define('RepeatEveryDayUntil', 'Каждый день по %UNTIL%');
define('RepeatDaysInfin', 'Каждый %PERIOD% день');
define('RepeatDaysTimes', 'Каждый %PERIOD% день %TIMES% раз(а)');
define('RepeatDaysUntil', 'Каждый %PERIOD% день по %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Каждую неделю в рабочие дни');
define('RepeatEveryWeekWeekdaysTimes', 'Каждую неделю в рабочие дни, %TIMES% раз(а)');
define('RepeatEveryWeekWeekdaysUntil', 'Каждую неделю в рабочие дни по %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Каждую %PERIOD% неделю в рабочие дни');
define('RepeatWeeksWeekdaysTimes', 'Каждую %PERIOD% неделю в рабочие дни %TIMES% раз(а)');
define('RepeatWeeksWeekdaysUntil', 'Каждую %PERIOD% неделю в рабочие дни по %UNTIL%');

define('RepeatEveryWeekInfin', 'Каждую неделю по %DAYS%');
define('RepeatEveryWeekTimes', 'Каждую неделю по %DAYS% %TIMES% раз(а)');
define('RepeatEveryWeekUntil', 'Каждую неделю по %DAYS% по %UNTIL%');
define('RepeatWeeksInfin', 'Каждую %PERIOD% неделю по %DAYS%');
define('RepeatWeeksTimes', 'Каждую %PERIOD% неделю по %DAYS%, %TIMES% раз(а)');
define('RepeatWeeksUntil', 'Каждую %PERIOD% неделю по %DAYS% по %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Каждый месяц %DATE% числа');
define('RepeatEveryMonthDateTimes', 'Каждый месяц %DATE% числа %TIMES% раз(а)');
define('RepeatEveryMonthDateUntil', 'Каждый месяц %DATE% числа по %UNTIL%');
define('RepeatMonthsDateInfin', 'Каждый %PERIOD% месяц %DATE% числа');
define('RepeatMonthsDateTimes', 'Каждый %PERIOD% месяц %DATE% числа %TIMES% раз(а)');
define('RepeatMonthsDateUntil', 'Каждый %PERIOD% месяц %DATE% числа по %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Каждый %NUMBER% %DAY% месяца');
define('RepeatEveryMonthWDTimes', 'Каждый %NUMBER% %DAY% месяца %TIMES% раз(а)');
define('RepeatEveryMonthWDUntil', 'Каждый %NUMBER% %DAY% месяца по %UNTIL%');
define('RepeatMonthsWDInfin', 'Каждый %PERIOD% месяца %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Каждый %PERIOD% месяца %NUMBER% %DAY% %TIMES% раз(а)');
define('RepeatMonthsWDUntil', 'Каждый %PERIOD% месяца %NUMBER% %DAY% по %UNTIL%');

define('RepeatEveryYearDateInfin', 'Каждый год %DATE%');
define('RepeatEveryYearDateTimes', 'Каждый год %DATE%, %TIMES% раз(а)');
define('RepeatEveryYearDateUntil', 'Каждый год %DATE%, по %UNTIL%');
define('RepeatYearsDateInfin', 'Каждый %PERIOD% год %DATE%');
define('RepeatYearsDateTimes', 'Каждый %PERIOD% год %DATE% %TIMES% раз(а)');
define('RepeatYearsDateUntil', 'Каждый %PERIOD% год %DATE% по %UNTIL%');

define('RepeatEveryYearWDInfin', 'Каждый год по %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Каждый год по %NUMBER% %DAY%, %TIMES% раз(а)');
define('RepeatEveryYearWDUntil', 'Каждый год по %NUMBER% %DAY%, по %UNTIL%');
define('RepeatYearsWDInfin', 'Каждый %PERIOD% год по %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Каждый %PERIOD% год по %NUMBER% %DAY% %TIMES% раз(а)');
define('RepeatYearsWDUntil', 'Каждый %PERIOD% год по %NUMBER% %DAY% по %UNTIL%');

define('RepeatDescDay', 'день');
define('RepeatDescWeek', 'неделя');
define('RepeatDescMonth', 'месяц');
define('RepeatDescYear', 'год');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Пожалуйста, укажите дату окончания повторяющегося события');
define('WarningWrongUntilDate', 'Пожалуйста, укажите дату окончания повторяющегося события позже даты начала повторяющегося события');

define('OnDays', 'По дням');
define('CancelRecurrence', 'Отменить повторяемость');
define('RepeatEvent', 'Повторять это событие');

define('Spellcheck', 'Проверка Правописания');
define('LoginLanguage', 'Язык');
define('LanguageDefault', 'По умолчанию');

// webmail 4.5.x new
define('EmptySpam', 'Очистить спам');
define('Saving', 'Сохранение&hellip;');
define('Sending', 'Отправка письма&hellip;');
define('LoggingOffFromServer', 'Отключение от сервера&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Не получается пометить письмо как SPAM');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Не получается пометить письмо как не SPAM');
define('ExportToICalendar', 'Экспортировать в iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Ваш аккаунт заблокирован, т.к. превышено максимально допустимое количество пользователей в данном типе лицензии. Пожалуйста свяжитесь с вашим системным администратором.');
define('RepliedMessageTitle', 'Отвеченное сообщение');
define('ForwardedMessageTitle', 'Сообщение было переслано');
define('RepliedForwardedMessageTitle', 'Сообщение было переслано и отвечено');
define('ErrorDomainExist', 'The user cannot be created because corresponding domain doesn\'t exist. You should create the domain first.');

// webmail 4.6.x or 4.7
define('RequestReadConfirmation', 'Подтверждение прочтения');
define('FolderTypeDefault', 'Стандартная');
define('ShowFoldersMapping', 'Переопределить системные папки (например использовать MyFolder как Sent Items)');
define('ShowFoldersMappingNote', 'Например, для того чтобы сменить папку для хранения отосланных писем выберите "Вашей Папке Отосланных" тип Sent Items.');
define('FolderTypeMapTo', 'Тип');
define('ReminderEmailExplanation', 'Это письмо пришло на ваш email %EMAIL%, т.к. в календаре "%CALENDAR_NAME%" было выбрана опция напомнить о событии');
define('ReminderOpenCalendar', 'Открыть календарь');

define('AddReminder', 'Напомнить мне об этом событии');
define('AddReminderBefore', 'Напомнить мне за % ');
define('AddReminderAnd', 'и % за');
define('AddReminderAlso', 'и еще % за');
define('AddMoreReminder', 'Напомнить мне дополнительно');
define('RemoveAllReminders', 'Удалить все напоминания');
define('ReminderNone', 'None');
define('ReminderMinutes', 'минут');
define('ReminderHour', 'час');
define('ReminderHours', 'часа');
define('ReminderDay', 'день');
define('ReminderDays', 'дня');
define('ReminderWeek', 'неделя');
define('ReminderWeeks', 'недели');
define('Allday', 'Весь день');

define('Folders', 'Папки');
define('NoSubject', 'Без темы');
define('SearchResultsFor', 'Результаты поиска для');

define('Back', 'Назад');
define('Next', 'Следующий');
define('Prev', 'Предыдущий');

define('MsgList', 'Сообщения');
define('Use24HTimeFormat', 'Использовать 24 часовой формат времени');
define('UseCalendars', 'Использовать календари');
define('Event', 'Событие');
define('CalendarSettingsNullLine', 'Нет календарей');
define('CalendarEventNullLine', 'Нет событий');
define('ChangeAccount', 'Сменить аккаунт');

define('TitleCalendar', 'Календарь');
define('TitleEvent', 'Событие');
define('TitleFolders', 'Папки');
define('TitleConfirmation', 'Подтверждение');

define('Yes', 'Да');
define('No', 'Нет');

define('EditMessage', 'Редактирование сообщения');

define('AccountNewPassword', 'Новый пароль');
define('AccountConfirmNewPassword', 'Повтор нового пароля');
define('AccountPasswordsDoNotMatch', 'Введенные пароли не совпадают.');

define('ContactTitle', 'Звание');
define('ContactFirstName', 'Имя');
define('ContactSurName', 'Фамилия');
define('ContactNickName', 'Ник');

define('CaptchaTitle', 'Код на картинке');
define('CaptchaReloadLink', 'обновить');
define('CaptchaError', 'Код с картинки введен неверно.');

define('WarningInputCorrectEmails', 'Пожалуйста, укажите корректные адреса электропочты.');
define('WrongEmails', 'Неверные адреса электропочты:');

define('ConfirmBodySize1', 'Размер сообщения превысил максимально допустимый лимит в ');
define('ConfirmBodySize2', 'символов. Все больше этого лимита будет обрезано. Нажмите "Отмена" для того чтобы продолжить редактирование сообщения.');
define('BodySizeCounter', 'Счетчик');
define('InsertImage', 'Вставить картинку');
define('ImagePath', 'Путь к картинке');
define('ImageUpload', 'Вставить');
define('WarningImageUpload', 'Выбранный файл не является картинкой. Пожалуйста выберите картинку.');

define('ConfirmExitFromNewMessage', 'Несохраненные данные будут утеряны. Нажмите отмену для того, чтобы остаться на этой странице.');

define('SensivityConfidential', 'Это сообщение помечено как конфиденциальное');
define('SensivityPrivate', 'Это сообщение помечено как частное');
define('SensivityPersonal', 'Это сообщение помечено как личное');

define('ReturnReceiptTopText', 'Отправитель этого письма запрашивает подтверждения получения сообщения.');
define('ReturnReceiptTopLink', 'Нажмите здесь чтобы отправить подтверждение.');
define('ReturnReceiptSubject', 'Return Receipt (displayed)');
define('ReturnReceiptMailText1', 'This is a Return Receipt for the mail that you sent to');
define('ReturnReceiptMailText2', 'Note: This Return Receipt only acknowledges that the message was displayed on the recipient\'s computer. There is no guarantee that the recipient has read or understood the message contents.');
define('ReturnReceiptMailText3', 'with subject');

define('SensivityMenu', 'Пометка');
define('SensivityNothingMenu', 'Обычное');
define('SensivityConfidentialMenu', 'Конфиденциальное');
define('SensivityPrivateMenu', 'Частное');
define('SensivityPersonalMenu', 'Личное');

define('ErrorLDAPonnect', 'Нет соединения с LDAP сервером.');

define('MessageSizeExceedsAccountQuota', 'Размер сообщения превысил вашу квоту.');
define('MessageCannotSent', 'Сообщение не может быть отослано.');
define('MessageCannotSaved', 'Сообщение не может быть сохранено.');

define('ContactFieldTitle', 'Поле');
define('ContactDropDownTO', 'Кому');
define('ContactDropDownCC', 'Копии');
define('ContactDropDownBCC', 'Скрытые копии');

// 4.9
define('NoMoveDelete', 'Сообщения не могут быть перемещены в Trash. Скорее всего переполнен ящик. Удалить эти сообщения?');
define('WarningFieldBlank', 'Заполните данное поле.');
define('WarningPassNotMatch', 'Пароли не совпадают.');
define('PasswordResetTitle', 'Восстановление пароля - шаг %d');
define('NullUserNameonReset', 'пользователь');
define('IndexResetLink', 'Забыли пароль?');
define('IndexRegLink', 'Регистрация почтового аккаунта');

define('RegDomainNotExist', 'Домен не существует.');
define('RegAnswersIncorrect', 'Ответы не верны.');
define('RegUnknownAdress', 'Неизвестный почтовый адрес.');
define('RegUnrecoverableAccount', 'Данный почтовый адрес не поддерживает функцию восстановления пароля.');
define('RegAccountExist', 'Данный аккаунт уже зарегистрирован.');
define('RegRegistrationTitle', 'Регистрация');
define('RegName', 'Ваше имя');
define('RegEmail', 'Электронный адрес');
define('RegEmailDesc', 'Например, myname@domain.com. Эта информация будет использоваться для входа в систему.');
define('RegSignMe', 'Запомнить меня');
define('RegSignMeDesc', 'Не требовать ввода логина и пароля при следующем входе в систему на данном компьютере.');
define('RegPass1', 'Пароль');
define('RegPass2', 'Повторите пароль');
define('RegQuestionDesc', 'Укажите 2 секретных вопроса и ответы на них, которые знаете только вы. В случае утери Вами пароля, вы сможете использовать эти вопросы и ответы чтобы восстановить пароль.');
define('RegQuestion1', 'Секретный вопрос 1');
define('RegAnswer1', 'Ответ 1');
define('RegQuestion2', 'Секретный вопрос 2');
define('RegAnswer2', 'Ответ 2');
define('RegTimeZone', 'Часовой пояс');
define('RegLang', 'Язык интерфейса');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Зарегистрироваться');

define('ResetEmail', 'Пожалуйста введите ваш email');
define('ResetEmailDesc', 'Введите адрес электронной почты полученный Вами при регистрации.');
define('ResetCaptcha', 'Captcha');
define('ResetSubmitStep1', 'Отправить');
define('ResetQuestion1', 'Секретный вопрос 1');
define('ResetAnswer1', 'Ответ');
define('ResetQuestion2', 'Секретный вопрос 2');
define('ResetAnswer2', 'Ответ');
define('ResetSubmitStep2', 'Отправить');

define('ResetTopDesc1Step2', 'Вы указали почтовый адрес');
define('ResetTopDesc2Step2', 'Пожалуйста подтвердите его правильность.');

define('ResetTopDescStep3', 'пожалуйста укажите ниже новый пароль для вашей электронной почты.');

define('ResetPass1', 'Новый пароль');
define('ResetPass2', 'Повторите пароль');
define('ResetSubmitStep3', 'Отправить');
define('ResetDescStep4', 'Ваш пароль был успешно изменен.');
define('ResetSubmitStep4', 'Вернуться');

define('RegReturnLink', 'Вернуться на экран логина');
define('ResetReturnLink', 'Вернуться на экран логина');

// Appointments
define('AppointmentAddGuests', 'Добавить гостей');
define('AppointmentRemoveGuests', 'Отменить встречу');
define('AppointmentListEmails', 'Введите email адреса через запятую и нажмите Сохранить');
define('AppointmentParticipants', 'Участники');
define('AppointmentRefused', 'Отказались');
define('AppointmentAwaitingResponse', 'Пока не ответили');
define('AppointmentInvalidGuestEmail', 'Следующие email адреса были введены некорректно:');
define('AppointmentOwner', 'Владелец');

define('AppointmentMsgTitleInvite', 'Приглашение на событие.');
define('AppointmentMsgTitleUpdate', 'Событие было изменено.');
define('AppointmentMsgTitleCancel', 'Событие было отменено.');
define('AppointmentMsgTitleRefuse', 'Гость %guest% отказался от приглашения');
define('AppointmentMoreInfo', 'Подробнее');
define('AppointmentOrganizer', 'Организатор');
define('AppointmentEventInformation', 'Информация о событии');
define('AppointmentEventWhen', 'Когда');
define('AppointmentEventParticipants', 'Участники');
define('AppointmentEventDescription', 'Описание');
define('AppointmentEventWillYou', 'Примите ли Вы участие');
define('AppointmentAdditionalParameters', 'Дополнительные параметры');
define('AppointmentHaventRespond', 'Еще не ответил');
define('AppointmentRespondYes', 'Приду');
define('AppointmentRespondMaybe', 'Не уверен');
define('AppointmentRespondNo', 'Не смогу прийти');
define('AppointmentGuestsChangeEvent', 'Гости могут изменить мероприятие');

define('AppointmentSubjectAddStart', 'Вы получили приглашение на ');
define('AppointmentSubjectAddFrom', ' от ');
define('AppointmentSubjectUpdateStart', 'Изменено мероприятие ');
define('AppointmentSubjectDeleteStart', 'Отменено мероприятие ');
define('ErrorAppointmentChangeRespond', 'Невозможно изменить событие');
define('SettingsAutoAddInvitation', 'Автоматически добавлять приглашения в календарь');
define('ReportEventSaved', 'Событие было сохранено');
define('ReportAppointmentSaved', ' и уведомления были высланы');
define('ErrorAppointmentSend', 'Приглашения не были высланы из-за ошибки.');
define('AppointmentEventName', 'Название:');

// End appointments

define('ErrorCantUpdateFilters', 'Ошибка модификации фильтров');

define('FilterPhrase', 'Если поле %field %condition %string тогда %action');
define('FiltersAdd', 'Добавить фильтр');
define('FiltersCondEqualTo', 'равно');
define('FiltersCondContainSubstr', 'содержит слово');
define('FiltersCondNotContainSubstr', 'не содержит слово');
define('FiltersActionDelete', 'удалить сообщение');
define('FiltersActionMove', 'переместить');
define('FiltersActionToFolder', 'в папку %folder');
define('FiltersNo', 'Нет активных фильтров');

define('ReminderEmailFriendly', 'напоминание');
define('ReminderEventBegin', 'начало: ');

define('FiltersLoading', 'Загрузка фильтров...');
define('ConfirmMessagesPermanentlyDeleted', 'Все сообщения в этой папке будут удалены.');

define('InfoNoNewMessages', 'Нет новых писем.');
define('TitleImportContacts', 'Импорт контактов');
define('TitleSelectedContacts', 'Выбранные контакты');
define('TitleNewContact', 'Новый контакт');
define('TitleViewContact', 'Просмотр контакта');
define('TitleEditContact', 'Редактирование контакта');
define('TitleNewGroup', 'Новая группа');
define('TitleViewGroup', 'Просмотр группы');

define('AttachmentComplete', 'Готово.');

define('TestButton', 'ТЕСТ');
define('AutoCheckMailIntervalLabel', 'Автопроверка почты каждую(ые)');
define('AutoCheckMailIntervalDisableName', 'отключена');
define('ReportCalendarSaved', 'Календарь сохранен.');

define('ContactSyncError', 'Синхронизация не была завершена из-за ошибок');
define('ReportContactSyncDone', 'Синхронизация завершена успешно');

define('MobileSyncUrlTitle', 'URL синхронизации');
define('MobileSyncLoginTitle', 'Логин синхронизации');

define('QuickReply', 'Быстрый ответ');
define('SwitchToFullForm', 'Открыть полную форму ответа');
define('SortFieldDate', 'Дата');
define('SortFieldFrom', 'От');
define('SortFieldSize', 'Размер');
define('SortFieldSubject', 'Тема');
define('SortFieldFlag', 'Флаг');
define('SortFieldAttachments', 'Вложение');
define('SortOrderAscending', 'Возрастание');
define('SortOrderDescending', 'Убывание');
define('ArrangedBy', 'Сортировка');

define('MessagePaneToRight', 'Панель сообщений располагается справа, а не снизу.');

define('SettingsTabMobileSync', 'Синхронизация');

define('MobileSyncContactDataBaseTitle', 'База данных контактов');
define('MobileSyncCalendarDataBaseTitle', 'База данных календарей');
define('MobileSyncTitleText', 'Если вы хотите синхронизировать ваше SyncML-устройство с WebMail, вам надо использовать следующие параметры. "'.MobileSyncUrlTitle.'" путь к SyncML серверу, "'.MobileSyncLoginTitle.'" ваш логин на SyncML сервера и по запросу используйте ваш собственный пароль. Так же в некоторых устройствах необходимо указать имя базы данных для контактов и календарей.<br /> Для этого используйте "'.MobileSyncContactDataBaseTitle.'" и "'.MobileSyncCalendarDataBaseTitle.'" соответственно.');

define('MobileSyncEnableLabel', 'Включить мобильную синхронизацию');

define('SearchInputText', 'поиск');

define('AppointmentEmailExplanation', 'Это письмо пришло на ваш %EMAIL% т.к. вы приглашены на событие от %ORGANAZER%');

define('Searching', 'Поиск&hellip;');

define('ButtonSetupSpecialFolders', 'Назначить стандартные папки');
define('ButtonSaveChanges', 'Сохранить изменения');
define('InfoPreDefinedFolders', 'Для предопределенных папок используйте эти IMAP папки');

define('SaveMailInSentItems', 'Сохранить в Отправленных');

define('CouldNotSaveUploadedFile', 'Невозможно сохранить загруженный файл.');

define('AccountOldPassword', 'Текущий пароль');
define('AccountOldPasswordsDoNotMatch', 'Введен неверный пароль.');

define('DefEditor', 'Редактор по умолчанию');
define('DefEditorRichText', 'Расширенное форматирование');
define('DefEditorPlainText', 'Простой текст');

define('Layout', 'Расположение');

define('TitleNewMessagesCount', 'Новых сообщений: %count%');

define('AltOpenInNewWindow', 'Открыть в новом окне');

define('SearchByFirstCharAll', 'Все');

define('FolderNoUsageAssigned', 'Не назначена');

define('InfoSetupSpecialFolders', 'Чтобы сопоставить стандартную папку (Отправленные,Черновики) с определенной IMAP папкой, нажмите Назначить стандартные папки.');

define('FileUploaderClickToAttach', 'Нажмите, чтобы прикрепить файл');
define('FileUploaderOrDragNDrop', 'Или просто перетащите файлы сюда');

define('AutoCheckMailInterval1Minute', '1 минуту');
define('AutoCheckMailInterval3Minutes', '3 минуты');
define('AutoCheckMailInterval5Minutes', '5 минут');
define('AutoCheckMailIntervalMinutes', 'минут');

define('ReadAboutCSVLink', 'Прочитать о полях CSV файла');

define('VoiceMessageSubj', 'Голосовое сообщение');
define('VoiceMessageTranscription', 'Транскрипция');
define('VoiceMessageReceived', 'Получено');
define('VoiceMessageDownload', 'Скачать');
define('VoiceMessageUpgradeFlashPlayer', 'Необходимо обновить Adobe Flash Player для проигрывания голосовых сообщений.<br />Обновите до Flash Player 10 с <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'Лицензионный ключ устарел. Пожалуйста, свяжитесь с нами для обновления лицензионного ключа.');
define('LicenseProblem', 'Проблемы с лицензией. Системному администратору необходимо проверить информацию в Admin Panel.');

define('AccountOldPasswordNotCorrect', 'Текущий пароль неверный');
define('AccountNewPasswordUpdateError', 'Невозможно сохранить новый пароль.');
define('AccountNewPasswordRejected', 'Невозможно сохранить новый пароль. Возможно, он слишком простой.');

define('CantCreateIdentity', 'Невозможно создать профиль');
define('CantUpdateIdentity', 'Невозможно обновить профиль');
define('CantDeleteIdentity', 'Невозможно удалить профиль');

define('AddIdentity', 'Новый профиль');
define('SettingsTabIdentities', 'Профили');
define('NoIdentities', 'Нет профилей');
define('NoSignature', 'Без подписи');
define('Account', 'Аккаунт');
define('TabChangePassword', 'Пароль');
define('SignatureEnteringHere', 'Вводите подпись здесь');

define('CantConnectToMailServer', 'Невозможно подключиться к почтовому серверу.');

define('DomainNameNotSpecified', 'Имя домена не указано.');

define('Open', 'Открыть');
define('FolderUsedAs', 'используется как');
define('ForwardTitle', 'Пересылка');
define('ForwardEnable', 'Разрешить пересылку');
define('ReportForwardUpdatedSuccessfuly', 'Настройки пересылки сохранены успешно.');

define('DialogAttachHeaderResume', 'Прикрепить Ваше резюме');
define('DialogAttachHeaderLetter', 'Прикрепить Ваше сопроводительное письмо');
define('DialogAttachName', 'Выберите резюме');
define('DialogAttachType', 'Выберите формат');
define('DialogAttachTypePdf', 'Adobe PDF (.pdf)');
define('DialogAttachTypeHtml', 'Web Page (.html)');
define('DialogAttachTypeRtf', 'Rich Text (.rtf)');
define('DialogAttachTypeTxt', 'Plain Text (.txt)');
define('DialogAttachTypeDoc', 'MS Word (.doc)');
define('DialogAttachButton', 'Прикрепить');
define('DialogAttachResume', 'Прикрепить резюме');
define('DialogAttachLetter', 'Прикрепить сопроводительное письмо');
define('DialogAttachAnother', 'Прикрепить другой файл');
define('DialogAttachAddToBody', 'Добавить текстовую версию в тело письма (рекомендуется)');
define('DialogAttachTypeNo', 'Без вложения');
define('DialogAttachSelectLetter', 'Выберите сопроводительное письмо');
define('DialogAttachTypePdfRecom', 'Adobe PDF (.pdf) (рекомендуется)');
define('DialogAttachTypeTextInBody', 'Текст в тело письма - рекомендуется');
define('DialogAttachTypeTxtAttach', 'Plain Text (.txt) вложение');
define('CustomTitle', 'Пересылка');
define('ForwardingNotificationsTo', 'Отправлять почтовые уведомления на <b>%email</b>');
define('ForwardingForwardTo', 'Пересылать сообщения на <b>%email</b>');
define('ForwardingNothing', 'Без почтовых уведомлений и пересылки');
define('ForwardingChange', 'изменить');

define('ConfirmSaveForward', 'Настройки пересылки не сохранены. Нажмите OK для сохранения.');
define('ConfirmSaveAutoresponder', 'Настройки автоответчика не сохранены. Нажмите OK для сохранения.');

define('DigDosMenuItem', 'DigDos');
define('DigDosTitle', 'Select an object');

define('LastLoginTitle', 'Последний логин');
define('ExportContacts', 'Экспорт контактов');

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

define('AppointmentInvitation', 'Приглашение');
define('AppointmentAccepted', 'принял');
define('AppointmentDeclined', 'отклонил');
define('AppointmentTentativelyAccepted', 'предварительно принял');
define('AppointmentLocation', 'Место проведения');
define('AppointmentCalendar', 'Календарь');
define('AppointmentWhen', 'Когда');
define('AppointmentDescription', 'Описание');
define('AppointmentButtonAccept', 'Принять');
define('AppointmentButtonTentative', 'Предварительно принять');
define('AppointmentButtonDecline', 'Отклонить');

define('ContactDisplayName', 'Display name');

define('WarningCreatingGroupRequiresContacts', 'Creating a group requires adding at least one contact to it.');
define('WarningRemovingAllContactsFromGroup', 'Removing all contacts from the group removes the group itself. Do you want to proceed?');
define('WarningSendEmailToDemoOnly', 'По соображениям безопасности, с данного демо аккаунта можно отправлять почту только на демо аккаунты.');

define('SettingsTabOutlookSync', 'Outlook Sync');

define('OutlookSyncServerURL', 'Сервер');

define('OutlookSyncHintDesc', 'Для синхронизации с календарем в Outlook, укажите следующие данные в настройках плагина Outlook Sync:');

define('WarningMailboxAlmostFull', 'Почтовый ящик близок к переполнению.');
define('WarningCouldNotSaveDraftAsYourMailboxIsOverQuota', 'Черновик сохранить не удалось по причине переполнения ящика.');
define('WarningSentEmailNotSaved', 'Сообщение было отправлено, но не сохранено в папке Отправленные по причине переполнения ящика.');

define('DavSyncHeading', 'DAV синхронизация по общему URL (для клиентов Apple)');
define('DavSyncHint', 'Используйте приведенный ниже URL для синхронизации календарей и контактов с Apple iCal или мобильным устройством iPhone или iPad (все они поддерживают синхронизацию нескольких папок CalDAV или CardDAV через общий адрес URL). Кстати, Вы также можете автоматически получить Ваш iOS-профиль, если войдете в этот вебмейл-интерфейс с такого устройства!');
define('DavSyncServer', 'DAV сервер');
define('DavSyncHeadingLogin', 'Вам также потребуются логин и пароль:');
define('DavSyncLogin', 'Логин для синхронизации');
define('DavSyncPasswordTitle', 'Пароль');
define('DavSyncPasswordValue', 'Ваш пароль учетной записи');
define('DavSyncSeparateUrlsHeading', 'DAV синхронизация по отдельному URL');
define('DavSyncHintUrls', 'Если ваш клиент CalDAV или CardDAV требует отдельный URL для календаря или адресной книги (например, Mozilla Thunderbird Lightning или Evolution), используйте URL из списка ниже.');
define('DavSyncHeadingCalendar', 'CalDAV-доступ к Вашим календарям');
define('DavSyncHeadingContacts', 'CardDAV-доступ к Вашим контактам');
define('DavSyncPersonalContacts', 'Личные контакты');
define('DavSyncCollectedAddresses', 'Автоматические контакты');
define('DavSyncGlobalAddressBook', 'Глобальная адресная книга');

define('ActiveSyncHeading', 'ActiveSync');
define('ActiveSyncHint', 'Для синхронизации почты, контактов и календарей через EAS (Exchange ActiveSync), используйте следующие настройки:');
define('ActiveSyncServer', 'Сервер');
define('ActiveSyncLogin', 'Логин');
define('ActiveSyncPasswordTitle', 'Пароль');
define('ActiveSyncPasswordValue', 'Ваш пароль учетной записи');

define('SearchStop', 'Прекратить поиск');
define('ErrorDuringSearch', 'Во время поиска произошла ошибка');
define('ErrorRetrievingMessages', 'При получении списка сообщений произошла ошибка');

define('AppointmentCanceled', '%SENDER% отменил встречу.');

define('CalendarDavUrl', 'DAV URL');
define('CalendarIcsLink', 'Ссылка на .ics');
define('CalendarIcsDownload', 'Загрузить');

define('DavSyncDemoPasswordValue', 'demo');

define('ActiveSyncDemoPasswordValue', 'demo');

define('ConfirmUnsubscribeCalendar', 'Вы уверены, что хотите отменить подписку на календарь');
define('CalendarUnsubscribe', 'Отменить подписку');
define('InfoUnsubscribing', 'Отмена подписки&hellip;');

define('ErrorDataTransferFailed', 'Передача данных была прервана, вероятно в связи с серверной ошибкой. Свяжитесь с администратором системы.');
define('ErrorCantReachServer', 'Ошибка связи с сервером.');
define('RetryGettingMessageList', 'Повторить');
define('BackToMessageList', 'Вернуться в список сообщений');

define('ErrorTitle', 'Ошибка');
define('ErrorUnableToLogIntoAccount', 'Не удалось войти в учетную запись.');
define('ErrorUnableToLocateMessage', 'Не удалось найти сообщение.');

define('ConfirmEditRepeatEventNotDaily', 'Так можно установить дату только для одного из событий серии. Чтобы сменить даты для всей серии, нажмите Отмена и установите дату в свойствах события.');

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
