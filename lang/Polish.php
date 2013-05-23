<?php
define('PROC_ERROR_ACCT_CREATE', 'Podczas tworzenia konta wystąpił błąd');
define('PROC_WRONG_ACCT_PWD', 'Błędne hasło');
define('PROC_CANT_LOG_NONDEF', 'Nie mogę zalogować na niedomyślne konto');
define('PROC_CANT_INS_NEW_FILTER', 'Nie mogę dodać nowego filtra');
define('PROC_FOLDER_EXIST', 'Katalog o tej nazwie już istnieje');
define('PROC_CANT_CREATE_FLD', 'Nie mogę utworzyć katalogu');
define('PROC_CANT_INS_NEW_GROUP', 'Nie mogę dodać nowej grupy');
define('PROC_CANT_INS_NEW_CONT', 'Nie mogę dodać nowego kontaktu');
define('PROC_CANT_INS_NEW_CONTS', 'Nie mogę dodać nowych kontaktów');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Nie mogę dodać kontaktów do grupy');
define('PROC_ERROR_ACCT_UPDATE', 'Podczas aktualizacji wystąpił błąd');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Nie mogę zaktualizować ustawień kontaktu');
define('PROC_CANT_GET_SETTINGS', 'Nie mogę wyświetlić ustawień');
define('PROC_CANT_UPDATE_ACCT', 'Nie mogę uaktualnić konta');
define('PROC_ERROR_DEL_FLD', 'Podczas usuwania folderów wystąpił błąd');
define('PROC_CANT_UPDATE_CONT', 'Nie mogę uaktualnić kontaktu');
define('PROC_CANT_GET_FLDS', 'Nie mogę pobrać drzewa katalogów');
define('PROC_CANT_GET_MSG_LIST', 'Nie mogę pobrać listy wiadomości');
define('PROC_MSG_HAS_DELETED', 'Ta wiadomość została już usunięta z serwera');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Nie mogę załadować ustawień kontaktu');
define('PROC_CANT_LOAD_SIGNATURE', 'Nie mogę załadować podpisu');
define('PROC_CANT_GET_CONT_FROM_DB', 'Brak odpowiedzi bazy danych');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Nie mogę pobrać kontaktów z bazy danych');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Nie mogę usunąć konta');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Nie mogę usunąć filtra');
define('PROC_CANT_DEL_CONT_GROUPS', 'Nie mogę usunąć kontaktów i/lub grup');
define('PROC_WRONG_ACCT_ACCESS', 'Wykryto próbę uzyskania nieautoryzwanego dostępu przez innego użytkownika');
define('PROC_SESSION_ERROR', 'Poprzednia sesja wygasła');

define('MailBoxIsFull', 'Skrzynka jest przepełniona');
define('WebMailException', 'Wystąpił wyjątek WebMail');
define('InvalidUid', 'Nieprawidłowy UID wiadomości');
define('CantCreateContactGroup', 'Nie mogę utworzyć grupy kontaktów');
define('CantCreateUser', 'Nie mogę utworzyć użytkownika');
define('CantCreateAccount', 'Nie mogę utworzyć konta');
define('SessionIsEmpty', 'Sesja jest pusta');
define('FileIsTooBig', 'Plik jest za duży');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Nie mogę oznaczyć wszystkich wiadomości jako przeczytanych');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Nie mogę oznaczyć wszystkich wiadomości jako nieprzeczytanych');
define('PROC_CANT_PURGE_MSGS', 'Nie mogę wyczyścić wiadomości');
define('PROC_CANT_DEL_MSGS', 'Nie mogę usunąć wiadomości');
define('PROC_CANT_UNDEL_MSGS', 'Nie mogę cofnąć usunięcia wiadomości');
define('PROC_CANT_MARK_MSGS_READ', 'Nie mogę oznaczyć wiadomości jako przeczytanych');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Nie mogę oznaczyć wiadomości jako nieprzeczytanych');
define('PROC_CANT_SET_MSG_FLAGS', 'Nie mogę ustawić flagi wiadomości');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Nie mogę usunąć flagi wiadomości');
define('PROC_CANT_CHANGE_MSG_FLD', 'Nie mogę zmienić katalogu wiadomości');
define('PROC_CANT_SEND_MSG', 'Nie mogę wysłać wiadomości: ');
define('PROC_CANT_SAVE_MSG', 'Nie mogę zapisać wiadomości');
define('PROC_CANT_GET_ACCT_LIST', 'Nie mogę pobrać listy kont');
define('PROC_CANT_GET_FILTER_LIST', 'Nie mogę pobrać listy filtrów');

define('PROC_CANT_LEAVE_BLANK', 'Pola oznaczone gwiazdką nie mogą pozostać puste');

define('PROC_CANT_UPD_FLD', 'Nie mogę uaktualnić folderu');
define('PROC_CANT_UPD_FILTER', 'Nie mogę uaktualnić filtra');

define('ACCT_CANT_ADD_DEF_ACCT', 'To konto nie może być dodane, ponieważ jest używane jako domyślne przez innego użytkownika');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Status tego konta nie może być zmieniony na domyślne');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Nie mogę utworzyć nowego konta (błąd połączenia IMAP)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Nie mogę usunąć ostatniego domyślnego konta');

define('LANG_LoginInfo', 'Informacje');
define('LANG_Email', 'E-mail');
define('LANG_Login', 'Login');
define('LANG_Password', 'Hasło');
define('LANG_IncServer', 'Poczta przychodząca');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'Serwer SMTP');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Użyj uwierzytelniania SMTP');
define('LANG_SignMe', 'Zaloguj mnie automatycznie');
define('LANG_Enter', 'Wprowadź');

define('JS_LANG_TitleLogin', 'Login');
define('JS_LANG_TitleMessagesListView', 'Lista wiadomości');
define('JS_LANG_TitleMessagesList', 'Lista wiadomości');
define('JS_LANG_TitleViewMessage', 'Zobacz wiadomość');
define('JS_LANG_TitleNewMessage', 'Nowa wiadomość');
define('JS_LANG_TitleSettings', 'Ustawienia');
define('JS_LANG_TitleContacts', 'Kontakty');

define('JS_LANG_StandardLogin', 'Standardowe&nbsp;Logowanie');
define('JS_LANG_AdvancedLogin', 'Zaawansowane&nbsp;Logowanie');

define('JS_LANG_InfoWebMailLoading', 'Proszę czekać na załadowanie WebMail &hellip;');
define('JS_LANG_Loading', 'Ładowanie &hellip;');
define('JS_LANG_InfoMessagesLoad', 'Proszę czekać, trwa ładowanie wiadomości ');
define('JS_LANG_InfoEmptyFolder', 'Katalog jest pusty');
define('JS_LANG_InfoPageLoading', 'Strona nadal się ładuje &hellip;');
define('JS_LANG_InfoSendMessage', 'Wiadomość została wysłana');
define('JS_LANG_InfoSaveMessage', 'Wiadomość została zapisana');
// You have imported 3 new contact(s) into your contacts list.
define('JS_LANG_InfoHaveImported', 'Zaimportowano');
define('JS_LANG_InfoNewContacts', 'nowe kontakty do Twojej listy.');
define('JS_LANG_InfoToDelete', 'Aby usunąć');
define('JS_LANG_InfoDeleteContent', 'katalog, trzeba usunąć najpierw jego zawartość.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Usuwanie niepustych folderów jest niemożliwe. Proszę usunąć najpierw ich zawartość.');
define('JS_LANG_InfoRequiredFields', '* pola obowiązkowe');

define('JS_LANG_ConfirmAreYouSure', 'Jesteś pewny?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Zaznaczone wiadomości zostaną definitywnie skasowane! Jesteś pewny?');
define('JS_LANG_ConfirmSaveSettings', 'Ustawienia nie zostały zapisane. Kliknij OK, aby zapisać.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Ustawienia kontaktów nie zostały zapisane. Kliknij OK, aby zapisać.');
define('JS_LANG_ConfirmSaveAcctProp', 'Ustawienia konta nie zostały zapisane. Kliknij OK, aby zapisać.');
define('JS_LANG_ConfirmSaveFilter', 'Ustawienia filtra nie zostały zapisane. Kliknij OK, aby zapisać.');
define('JS_LANG_ConfirmSaveSignature', 'Podpis nie został zapisany. Kliknij OK, aby zapisać.');
define('JS_LANG_ConfirmSavefolders', 'Foldery nie zostały zapisane. Wybierz OK, aby zapisać.');
define('JS_LANG_ConfirmHtmlToPlain', 'Ostrzeżenie: Poprzez zmianę formatu tej wiadomości z HTML do zwykłego tekstu, utracisz ustalone formatowanie. Kliknij OK, aby kontynuować.');
define('JS_LANG_ConfirmAddFolder', 'Przed dodaniem folderu należy zastosować zmiany. Kliknij OK, aby zapisać.');
define('JS_LANG_ConfirmEmptySubject', 'Pole tematu jest puste. Czy chcesz kontynuować?');

define('JS_LANG_WarningEmailBlank', 'Nie możesz zostawić<br />pustego pola E-mail');
define('JS_LANG_WarningLoginBlank', 'Nie możesz zostawić<br />pustego pola Login');
define('JS_LANG_WarningToBlank', 'Nie możesz zostawić pustego pola Do');
define('JS_LANG_WarningServerPortBlank', 'Nie możesz zostawić pustych<br />pól serwer/port POP3 i SMTP');
define('JS_LANG_WarningEmptySearchLine', 'Pusty ciąg znaków. Proszę wpisać poszukiwany ciąg, aby dokonać wyszukania.');
define('JS_LANG_WarningMarkListItem', 'Proszę zaznaczyć przynajmniej jeden element listy');
define('JS_LANG_WarningFolderMove', 'Folder nie może zostać przeniesiony');
define('JS_LANG_WarningContactNotComplete', 'Proszę wpisać e-mail lub nazwę');
define('JS_LANG_WarningGroupNotComplete', 'Proszę wpisać nazwę grupy');

define('JS_LANG_WarningEmailFieldBlank', 'Nie możesz zostawić pola E-mail pustego');
define('JS_LANG_WarningIncServerBlank', 'Nie możesz zostawić pola Serwer POP3(IMAP4) pustego');
define('JS_LANG_WarningIncPortBlank', 'Nie możesz zostawić pola Port serwera POP3(IMAP4) pustego');
define('JS_LANG_WarningIncLoginBlank', 'Nie możesz zostawić pola Login POP3(IMAP4) pustego');
define('JS_LANG_WarningIncPortNumber', 'Musisz wpisać dodatnią liczbę w polu port POP3(IMAP4).');
define('JS_LANG_DefaultIncPortNumber', 'Domyślny numer portu POP3(IMAP4) to 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Nie możesz zostawić pola hasło POP3(IMAP4) pustego');
define('JS_LANG_WarningOutPortBlank', 'Nie możesz zostawić pola port serwera SMTP pustego');
define('JS_LANG_WarningOutPortNumber', 'Powinieneś wpisać dodatnią liczbę w polu port SMTP.');
define('JS_LANG_WarningCorrectEmail', 'Powinieneś wpisać poprawny adres e-mail.');
define('JS_LANG_DefaultOutPortNumber', 'Domyślny numer portu SMTP to 25.');

define('JS_LANG_WarningCsvExtention', 'Rozszerzenie powinno być .csv');
define('JS_LANG_WarningImportFileType', 'Proszę wybrać aplikację, z której chcesz skopiować kontakty');
define('JS_LANG_WarningEmptyImportFile', 'Proszę wybrać plik, klikając na przycisk Przeglądaj');

define('JS_LANG_WarningContactsPerPage', 'Liczba kontaktów na stronę musi być dodatnia');
define('JS_LANG_WarningMessagesPerPage', 'Liczba wiadomości na stronę musi być dodatnia');
define('JS_LANG_WarningMailsOnServerDays', 'Powinieneś wpisać dodatnią liczbę w polu Liczba dni przechowywania wiadomości na serwerze.');
define('JS_LANG_WarningEmptyFilter', 'Proszę wpisać ciąg znaków');
define('JS_LANG_WarningEmptyFolderName', 'Proszę wpisać nazwę folderu');

define('JS_LANG_ErrorConnectionFailed', 'Połączenie nieudane');
define('JS_LANG_ErrorRequestFailed', 'Transfer danych nie powiódł się');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Objekt XMLHttpRequest nie występuje');
define('JS_LANG_ErrorWithoutDesc', 'Nastąpił błąd bez opisu');
define('JS_LANG_ErrorParsing', 'Błąd parsowania XML.');
define('JS_LANG_ResponseText', 'Tekst odpowiedzi:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Pusty pakiet XML');
define('JS_LANG_ErrorImportContacts', 'Błąd importowania kontaktów');
define('JS_LANG_ErrorNoContacts', 'Brak kontaktów do zaimportowania');
define('JS_LANG_ErrorCheckMail', 'Odbieranie wiadomości przerwane przez błąd. Prawdopodobnie nie pobrano wszystkich wiadomości.');

define('JS_LANG_LoggingToServer', 'Logowanie na serwer &hellip;');
define('JS_LANG_GettingMsgsNum', 'Pobieranie liczby wiadomości');
define('JS_LANG_RetrievingMessage', 'Pobieranie wiadomości');
define('JS_LANG_DeletingMessage', 'Usuwanie wiadomości');
define('JS_LANG_DeletingMessages', 'Usuwanie wiadomości');
define('JS_LANG_Of', 'z');
define('JS_LANG_Connection', 'Połączenie');
define('JS_LANG_Charset', 'Typ kodowania');
define('JS_LANG_AutoSelect', 'Auto-Wybór');

define('JS_LANG_Contacts', 'Kontakty');
define('JS_LANG_ClassicVersion', 'Wersja klasyczna');
define('JS_LANG_Logout', 'Wyloguj');
define('JS_LANG_Settings', 'Ustawienia');

define('JS_LANG_LookFor', 'Szukaj');
define('JS_LANG_SearchIn', 'Szukaj w');
define('JS_LANG_QuickSearch', 'Szukaj wyłącznie w polach Od, Do i temat (szybciej).');
define('JS_LANG_SlowSearch', 'Szukaj w całych wiadomościach');
define('JS_LANG_AllMailFolders', 'Wszystkie foldery');
define('JS_LANG_AllGroups', 'Wszystkie grupy');

define('JS_LANG_NewMessage', 'Nowa wiadomość');
define('JS_LANG_CheckMail', 'Sprawdź pocztę');
define('JS_LANG_EmptyTrash', 'Opróżnij kosz');
define('JS_LANG_MarkAsRead', 'Oznacz jako przeczytane');
define('JS_LANG_MarkAsUnread', 'Oznacz jako nieprzeczytane');
define('JS_LANG_MarkFlag', 'Oflaguj');
define('JS_LANG_MarkUnflag', 'Odflaguj');
define('JS_LANG_MarkAllRead', 'Oznacz jako przeczytane');
define('JS_LANG_MarkAllUnread', 'Oznacz jako nieprzeczytane');
define('JS_LANG_Reply', 'Odpowiedz');
define('JS_LANG_ReplyAll', 'Odpowiedz wszystkim');
define('JS_LANG_Delete', 'Usuń');
define('JS_LANG_Undelete', 'Cofnij usunięcie');
define('JS_LANG_PurgeDeleted', 'Wyczyść usunięte');
define('JS_LANG_MoveToFolder', 'Przenieś do katalogu');
define('JS_LANG_Forward', 'Przekaż');

define('JS_LANG_HideFolders', 'Ukryj katalogi');
define('JS_LANG_ShowFolders', 'Pokaż katalogi');
define('JS_LANG_ManageFolders', 'Zarządzaj katalogami');
define('JS_LANG_SyncFolder', 'Zsynchronizowane foldery');
define('JS_LANG_NewMessages', 'Nowe wiadomości');
define('JS_LANG_Messages', 'Wiadomości');

define('JS_LANG_From', 'Od');
define('JS_LANG_To', 'Do');
define('JS_LANG_Date', 'Data');
define('JS_LANG_Size', 'Rozmiar');
define('JS_LANG_Subject', 'Temat');

define('JS_LANG_FirstPage', 'Pierwsza strona');
define('JS_LANG_PreviousPage', 'Poprzednia');
define('JS_LANG_NextPage', 'Następna');
define('JS_LANG_LastPage', 'Ostatnia strona');

define('JS_LANG_SwitchToPlain', 'Przełącz widok na zwykły tekst');
define('JS_LANG_SwitchToHTML', 'Przełącz widok na HTML');
define('JS_LANG_AddToAddressBook', 'Dodaj do książki adresowej');
define('JS_LANG_ClickToDownload', 'Kliknij, aby pobrać');
define('JS_LANG_View', 'Wyświetl');
define('JS_LANG_ShowFullHeaders', 'Pokaż pełne nagłówki');
define('JS_LANG_HideFullHeaders', 'Ukryj pełne nagłówki');

define('JS_LANG_MessagesInFolder', 'Wiadomości w katalogu');
define('JS_LANG_YouUsing', 'Używasz');
define('JS_LANG_OfYour', 'Twojego');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Wyślij');
define('JS_LANG_SaveMessage', 'Zapisz');
define('JS_LANG_Print', 'Drukuj');
define('JS_LANG_PreviousMsg', 'Poprzednia wiadomość');
define('JS_LANG_NextMsg', 'Następna wiadomość');
define('JS_LANG_AddressBook', 'Książka adresowa');
define('JS_LANG_ShowBCC', 'Pokaż BCC');
define('JS_LANG_HideBCC', 'Ukryj BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Adres zwrotny');
define('JS_LANG_AttachFile', 'Dołącz plik');
define('JS_LANG_Attach', 'Dołącz');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Oryginalna wiadomość');
define('JS_LANG_Sent', 'Wysłane');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Niski');
define('JS_LANG_Normal', 'Normalny');
define('JS_LANG_High', 'Wysoki');
define('JS_LANG_Importance', 'Priorytet');
define('JS_LANG_Close', 'Zamknij');

define('JS_LANG_Common', 'Ogólne');
define('JS_LANG_EmailAccounts', 'Konta e-mail');

define('JS_LANG_MsgsPerPage', 'Wiadomości na stronę');
define('JS_LANG_DisableRTE', 'Dezaktywuj rozbudowany edytor');
define('JS_LANG_Skin', 'Skórka');
define('JS_LANG_DefCharset', 'Domyślny typ kodowania');
define('JS_LANG_DefCharsetInc', 'Domyślny typ kodowania wiadomości przychodzących');
define('JS_LANG_DefCharsetOut', 'Domyślny typ kodowania wiadomości wychodzących');
define('JS_LANG_DefTimeOffset', 'Domyślny offset');
define('JS_LANG_DefLanguage', 'Domyślny język');
define('JS_LANG_DefDateFormat', 'Domyślny format daty');
define('JS_LANG_ShowViewPane', 'Lista wiadomości z panelem podglądu');
define('JS_LANG_Save', 'Zapisz');
define('JS_LANG_Cancel', 'Anuluj');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Usuń');
define('JS_LANG_AddNewAccount', 'Dodaj nowe konto');
define('JS_LANG_Signature', 'Podpis');
define('JS_LANG_Filters', 'Filtry');
define('JS_LANG_Properties', 'Właściwości');
define('JS_LANG_UseForLogin', 'Użyj właściwości tego konta (login i hasło) do logowania');
define('JS_LANG_MailFriendlyName', 'Twoje imię i nazwisko');
define('JS_LANG_MailEmail', 'E-mail');
define('JS_LANG_MailIncHost', 'Poczta przychodząca');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Hasło');
define('JS_LANG_MailOutHost', 'Serwer SMTP');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Login');
define('JS_LANG_MailOutPass', 'SMTP Hasło');
define('JS_LANG_MailOutAuth1', 'Używaj uwierzytelniania SMTP');
define('JS_LANG_MailOutAuth2', '(Możesz zostawić pola login i hasło SMTP puste, jeżeli są takie same jak dla POP3/IMAP)');
define('JS_LANG_UseFriendlyNm1', 'Używaj imienia i nazwiska w polu "Od:"');
define('JS_LANG_UseFriendlyNm2', '(Imię i nazwisko &lt;ja@mojadres.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Pobieraj wiadomości po zalogowaniu');
define('JS_LANG_MailMode0', 'Usuwaj pobrane wiadomości z serwera');
define('JS_LANG_MailMode1', 'Zostawiaj wiadomości na serwerze');
define('JS_LANG_MailMode2', 'Zostawiaj wiadomości na serwerze przez');
define('JS_LANG_MailsOnServerDays', 'dni');
define('JS_LANG_MailMode3', 'Usuwaj wiadomości z serwera, gdy są usuwane z Kosza');
define('JS_LANG_InboxSyncType', 'Typ synchronizacji skrzynki');

define('JS_LANG_SyncTypeNo', 'Nie synchronizuj');
define('JS_LANG_SyncTypeNewHeaders', 'Nowe nagłówki');
define('JS_LANG_SyncTypeAllHeaders', 'Wszystkie nagłówki');
define('JS_LANG_SyncTypeNewMessages', 'Nowe wiadomości');
define('JS_LANG_SyncTypeAllMessages', 'Wszystkie wiadomości');
define('JS_LANG_SyncTypeDirectMode', 'Tryb bezpośredni');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Pełne nagłówki');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Pełne wiadomości');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Tryb bezpośredni');

define('JS_LANG_DeleteFromDb', 'Usuwaj wiadomości z bazy danych jeżeli nie ma ich na serwerze');

define('JS_LANG_EditFilter', 'Edytuj&nbsp;filtr');
define('JS_LANG_NewFilter', 'Dodaj nowy filtr');
define('JS_LANG_Field', 'Pole');
define('JS_LANG_Condition', 'Warunek');
define('JS_LANG_ContainSubstring', 'Zawiera ciąg znaków');
define('JS_LANG_ContainExactPhrase', 'Zawiera frazę');
define('JS_LANG_NotContainSubstring', 'Nie zawiera ciągu znaków');
define('JS_LANG_FilterDesc_At', 'w');
define('JS_LANG_FilterDesc_Field', 'pole');
define('JS_LANG_Action', 'Akcja');
define('JS_LANG_DoNothing', 'Nie rób nic');
define('JS_LANG_DeleteFromServer', 'Usuń z serwera');
define('JS_LANG_MarkGrey', 'Oznacz szarym kolorem');
define('JS_LANG_Add', 'Dodaj');
define('JS_LANG_OtherFilterSettings', 'Inne ustawienia filtra');
define('JS_LANG_ConsiderXSpam', 'Rozważaj nagłówki X-spam');
define('JS_LANG_Apply', 'Zastosuj');

define('JS_LANG_InsertLink', 'Wstaw link');
define('JS_LANG_RemoveLink', 'Usuń link');
define('JS_LANG_Numbering', 'Numeracja');
define('JS_LANG_Bullets', 'Wypunktowanie');
define('JS_LANG_HorizontalLine', 'Pozioma linia');
define('JS_LANG_Bold', 'Pogrubienie');
define('JS_LANG_Italic', 'Kursywa');
define('JS_LANG_Underline', 'Podkreślenie');
define('JS_LANG_AlignLeft', 'Wyrównaj do lewej');
define('JS_LANG_Center', 'Wyśrodkuj');
define('JS_LANG_AlignRight', 'Wyrównaj do prawej');
define('JS_LANG_Justify', 'Wyjustowanie');
define('JS_LANG_FontColor', 'Kolor czcionki');
define('JS_LANG_Background', 'Tło');
define('JS_LANG_SwitchToPlainMode', 'Przełącz na zwykły tekst');
define('JS_LANG_SwitchToHTMLMode', 'Przełącz na HTML');

define('JS_LANG_Folder', 'Katalog');
define('JS_LANG_Msgs', 'Wiadomości');
define('JS_LANG_Synchronize', 'Synchronizuj');
define('JS_LANG_ShowThisFolder', 'Wyświetl ten folder');
define('JS_LANG_Total', 'W sumie');
define('JS_LANG_DeleteSelected', 'Usuń zaznaczone');
define('JS_LANG_AddNewFolder', 'Dodaj nowy folder');
define('JS_LANG_NewFolder', 'Nowy folder');
define('JS_LANG_ParentFolder', 'Folder nadrzędny');
define('JS_LANG_NoParent', 'Bez nadrzędnego folderu');
define('JS_LANG_FolderName', 'Nazwa folderu');

define('JS_LANG_ContactsPerPage', 'Liczba kontaktów na stronę');
define('JS_LANG_WhiteList', 'Kontakty z książki adresowej na białej liście');

define('JS_LANG_CharsetDefault', 'Domyślny');
define('JS_LANG_CharsetArabicAlphabetISO', 'Arabski (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Arabski (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Bałtycki (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Bałtycki (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Środkowoeuropejski (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Środkowoeuropejski (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Chinese Simplified (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Chinese Simplified (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Chiński tradycyjny (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Cyrylica (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Cyrylica (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Cyrylica (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Grecki (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Grecki (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Hebrajski (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'Hebrajski (Windows)');
define('JS_LANG_CharsetJapanese', 'Japoński');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japoński (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Koreański (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Koreański (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latin 3 (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Turecki');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Unicode (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Unicode (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Wietnamski (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Zachodni (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Zachodni (Windows)');

define('JS_LANG_TimeDefault', 'Domyślny');
define('JS_LANG_TimeEniwetok', 'Strefa czasowa Eniwetok, Kwajalein');
define('JS_LANG_TimeMidwayIsland', 'Midway Island, Samoa');
define('JS_LANG_TimeHawaii', 'Hawaje');
define('JS_LANG_TimeAlaska', 'Alaska');
define('JS_LANG_TimePacific', 'Pacyficzny (US & Canada); Tijuana');
define('JS_LANG_TimeArizona', 'Arizona');
define('JS_LANG_TimeMountain', 'Mountain Time (US & Canada)');
define('JS_LANG_TimeCentralAmerica', 'Centralne USA');
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
define('JS_LANG_TimeSarajevo', 'Sarajevo, Skopje, Sofija, Warszawa, Zagreb');
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

define('JS_LANG_DateDefault', 'Domyślny');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Miesiąc (01 Jan)');
define('JS_LANG_DateAdvanced', 'Zaawansowany');

define('JS_LANG_NewContact', 'Nowy kontakt');
define('JS_LANG_NewGroup', 'Nowa grupa');
define('JS_LANG_AddContactsTo', 'Dodaj kontakty do');
define('JS_LANG_ImportContacts', 'Importuj kontakty');

define('JS_LANG_Name', 'Nazwa');
define('JS_LANG_Email', 'E-mail');
define('JS_LANG_DefaultEmail', 'Domyślny Email');
define('JS_LANG_NotSpecifiedYet', 'Nie określony');
define('JS_LANG_ContactName', 'Nazwa');
define('JS_LANG_Birthday', 'Urodziny');
define('JS_LANG_Month', 'Miesiąc');
define('JS_LANG_January', 'Styczeń');
define('JS_LANG_February', 'Luty');
define('JS_LANG_March', 'Marzec');
define('JS_LANG_April', 'Kwiecień');
define('JS_LANG_May', 'Maj');
define('JS_LANG_June', 'Czerwiec');
define('JS_LANG_July', 'Lipiec');
define('JS_LANG_August', 'Sierpień');
define('JS_LANG_September', 'Wrzesień');
define('JS_LANG_October', 'Październik');
define('JS_LANG_November', 'Listopad');
define('JS_LANG_December', 'Grudzień');
define('JS_LANG_Day', 'Dzień');
define('JS_LANG_Year', 'Rok');
define('JS_LANG_UseFriendlyName1', 'Używaj imienia w polu Od');
define('JS_LANG_UseFriendlyName2', '(Jan Kowlaski &lt;jan@kowalski.com&gt;)');
define('JS_LANG_Personal', 'Osobiste');
define('JS_LANG_PersonalEmail', 'Osobiste E-maile');
define('JS_LANG_StreetAddress', 'Ulica');
define('JS_LANG_City', 'Miasto');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Województwo');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'Kod pocztowy');
define('JS_LANG_Mobile', 'Telefon komórkowy');
define('JS_LANG_CountryRegion', 'Kraj/region');
define('JS_LANG_WebPage', 'Strona www');
define('JS_LANG_Go', 'Idź');
define('JS_LANG_Home', 'Dom');
define('JS_LANG_Business', 'Biznes');
define('JS_LANG_BusinessEmail', 'E-maile biznesowe');
define('JS_LANG_Company', 'Firma');
define('JS_LANG_JobTitle', 'Stanowisko');
define('JS_LANG_Department', 'Dział');
define('JS_LANG_Office', 'Biuro');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Inne');
define('JS_LANG_OtherEmail', 'Inne E-maile');
define('JS_LANG_Notes', 'Notatki');
define('JS_LANG_Groups', 'Grupy');
define('JS_LANG_ShowAddFields', 'Pokaż dodatkowe pola');
define('JS_LANG_HideAddFields', 'Ukryj dodatkowe pola');
define('JS_LANG_EditContact', 'Edytuj informacje kontaktu');
define('JS_LANG_GroupName', 'Nazwa grupy');
define('JS_LANG_AddContacts', 'Dodaj kontakty');
define('JS_LANG_CommentAddContacts', '(Jeżeli chcesz wpisać więcej adresów, rozdziel je przecinkami)');
define('JS_LANG_CreateGroup', 'Utwórz grupę');
define('JS_LANG_Rename', 'zmień nazwę');
define('JS_LANG_MailGroup', 'Grupa E-maili');
define('JS_LANG_RemoveFromGroup', 'Usuń z grupy');
define('JS_LANG_UseImportTo', 'Użyj Importuj by skopiować kontakty z Microsoft Outlook, Microsoft Outlook Express do listy kontaktów WebMail.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Wybierz plik (w formacje .CSV) który chcesz zaimportować');
define('JS_LANG_Import', 'Importuj');
define('JS_LANG_ContactsMessage', 'To jest strona kontaktów!');
define('JS_LANG_ContactsCount', 'Kontakt(y)');
define('JS_LANG_GroupsCount', 'Grupa(y)');

// webmail 4.1 constants
define('PicturesBlocked', 'Obrazki w tej wiadomości zostały zablokowane dla Twojego bezpieczeństwa.');
define('ShowPictures', 'Pokaż obrazki');
define('ShowPicturesFromSender', 'Zawsze pokazuj obrazki w wiadomościach od tego nadawcy');
define('AlwaysShowPictures', 'Zawsze pokazuj obrazki w wiadomościach');

define('TreatAsOrganization', 'Traktuj jako organizację');

define('WarningGroupAlreadyExist', 'Grupa o takiej nazwie już istnieje. Proszę podać inną nazwę.');
define('WarningCorrectFolderName', 'Musisz określić poprawną nazwę folderu.');
define('WarningLoginFieldBlank', 'Nie można pozostawić pola Login pustego.');
define('WarningCorrectLogin', 'Musisz określić poprawny login.');
define('WarningPassBlank', 'Nie można pozostawić pola Hasło pustego.');
define('WarningCorrectIncServer', 'Musisz określić poprawny adres serwera POP3(IMAP).');
define('WarningCorrectSMTPServer', 'Musisz określić poprawny adres serwera SMTP.');
define('WarningFromBlank', 'Nie można pozostawić pola Od: pustego');
define('WarningAdvancedDateFormat', 'Proszę określić format daty/czasu.');

define('AdvancedDateHelpTitle', 'Zaawansowana Data');
define('AdvancedDateHelpIntro', 'Kiedy pole &quot;Zaawansowana&quot; jest wybrane, możesz wpisać własny format daty/czasu, który będzie wyświetlany. Następujące znaki mogą zostać użyte do tego celu \':\' lub \'/\'');
define('AdvancedDateHelpConclusion', 'Na przykład, jeżeli określisz wartość &quot;mm/dd/yyyy&quot; w polu &quot;Zaawansowana&quot;, data będzie wyświetlana jako miesiąc/dzień/rok (np. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Dzień miesiąca (od 1 do 31)');
define('AdvancedDateHelpNumericMonth', 'Month (od 1 do 12)');
define('AdvancedDateHelpTextualMonth', 'Month (od Jan do Dec)');
define('AdvancedDateHelpYear2', 'Rok, 2 cyfry');
define('AdvancedDateHelpYear4', 'Rok, 4 cyfry');
define('AdvancedDateHelpDayOfYear', 'Dzień roku (od 1 do 366)');
define('AdvancedDateHelpQuarter', 'Kwartał');
define('AdvancedDateHelpDayOfWeek', 'Dzień tygodnia (od Mon do Sun)');
define('AdvancedDateHelpWeekOfYear', 'Tydzień roku (od 1 do 53)');

define('InfoNoMessagesFound', 'Nie znaleziono żadnych wiadomości.');
define('ErrorSMTPConnect', 'Nie można połączyć się z serwerem SMTP. Sprawdź ustawienia SMTP.');
define('ErrorSMTPAuth', 'Zły login lub hasło. Logowanie zakończone niepowodzeniem.');
define('ReportMessageSent', 'Wiadomość została wysłana.');
define('ReportMessageSaved', 'Wiadomość została zapisana.');
define('ErrorPOP3Connect', 'Nie można połączyć z serwerem POP3, sprawdź ustawienia POP3.');
define('ErrorIMAP4Connect', 'Nie można połączyć z serwerem IMAP4, sprawdź ustawienia IMAP4.');
define('ErrorPOP3IMAP4Auth', 'Błędny e-mail/login lub hasło. Logowanie nie powiodło się.');
define('ErrorGetMailLimit', 'Limit objętości Twojej skrzynki został przekroczony.');

define('ReportSettingsUpdatedSuccessfuly', 'Ustawienia zostały nadpisane.');
define('ReportAccountCreatedSuccessfuly', 'Konto zostało utworzone.');
define('ReportAccountUpdatedSuccessfuly', 'Konto zostało uaktualnione.');
define('ConfirmDeleteAccount', 'Na pewno chcesz usunąć konto?');
define('ReportFiltersUpdatedSuccessfuly', 'Filtry zostały zaktualizowane.');
define('ReportSignatureUpdatedSuccessfuly', 'Sygnatura została zapisana.');
define('ReportFoldersUpdatedSuccessfuly', 'Foldery zostały zaktualizowane.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Ustawienia kontaktów zostały zapisane.');

define('ErrorInvalidCSV', 'Plik CSV który wybrałeś ma niewłaściwy format.');
// The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Grupa');
define('ReportGroupSuccessfulyAdded2', 'została dodana.');
define('ReportGroupUpdatedSuccessfuly', 'Grupa została zaktualizowana.');
define('ReportContactSuccessfulyAdded', 'Kontakt został dodany.');
define('ReportContactUpdatedSuccessfuly', 'Kontakt został zaktualizowany.');
// Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Kontakt(y) został(y) dodany(e).');
define('AlertNoContactsGroupsSelected', 'Nie zaznaczono kontaktów ani grup.');

define('InfoListNotContainAddress', 'Jeżeli lista nie zawiera adresu, którego szukasz - kontynuuj pisanie jego pierwszych liter.');

define('DirectAccess', 'B');
define('DirectAccessTitle', 'Tryb bezpośredni. WebMail uzyskuje wiadomości bezpośrednio z serwera poczty.');

define('FolderInbox', 'Odebrane');
define('FolderSentItems', 'Wysłane');
define('FolderDrafts', 'Brudnopis');
define('FolderTrash', 'Kosz');

define('FileLargerAttachment', 'Rozmiar załacznika przekracza dozwolony limit.');
define('FilePartiallyUploaded', 'Jedynie część pliku została załadowana w wyniku nieokreślonego błędu.');
define('NoFileUploaded', 'Plik nie został załadowany.');
define('MissingTempFolder', 'Folder tymczasowy nie istnieje.');
define('MissingTempFile', 'Plik tymczasowy nie istnieje.');
define('UnknownUploadError', 'Wystąpił nieznany błąd uploadu pliku.');
define('FileLargerThan', 'Błąd ładowania pliku. Prawdopodobnie rozmiar pliku jest większy niż ');
define('PROC_CANT_LOAD_DB', 'Nie można połączyć się z bazą danych.');
define('PROC_CANT_LOAD_LANG', 'Nie można odnaleźć odpowiedniego pliku językowego.');
define('PROC_CANT_LOAD_ACCT', 'Konto nie istnieje, prawdopodobnie zostało skasowane.');

define('DomainDosntExist', 'Taka domena nie została podłączona do serwera poczty.');
define('ServerIsDisable', 'Używanie serwera poczty jest zabronione przez administratora.');

define('PROC_ACCOUNT_EXISTS', 'Konto nie może zostać utworzone, ponieważ już istnieje.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Nie mogę podliczyć liczby wiadomości.');
define('PROC_CANT_MAIL_SIZE', 'Nie mogę uzyskać łącznego rozmiaru wiadomości.');

define('Organization', 'Organizacja');
define('WarningOutServerBlank', 'Nie można pozostawić pola Serwer SMTP pustego');

//
define('JS_LANG_Refresh', 'Odświerz');
define('JS_LANG_MessagesInInbox', 'Wiadomości w skrzynce odbiorczej');
define('JS_LANG_InfoEmptyInbox', 'Skrzynka odbiorcza jest pusta');

// webmail 4.2 constants
define('BackToList', 'Wróć do listy');
define('InfoNoContactsGroups', 'Brak kontaktów lub grup.');
define('InfoNewContactsGroups', 'Możesz również utworzyć nowe kontakty/grupy lub importować kontakty z pliku .CSV w formacie MS Outlook.');
define('DefTimeFormat', 'Domyślny format daty');
define('SpellNoSuggestions', 'Brak sugestii');
define('SpellWait', 'Proszę czekać&hellip;');

define('InfoNoMessageSelected', 'Nie wybrano wiadomości.');
define('InfoSingleDoubleClick', 'Możesz również kliknąć jeden raz na dowolną wiadomość na liście, żeby zobaczyć ją tutaj, lub kliknąć dwa razy, żeby zobaczyć ją w pełnym rozmiarze.');

// calendar
define('TitleDay', 'Widok Dzienny');
define('TitleWeek', 'Widok Tygodniowy');
define('TitleMonth', 'Widok Miesięczny');

define('ErrorNotSupportBrowser', 'AfterLogic Calendar nie wspiera Twojej przeglądarki internetowej. Proszę użyć przeglądarki FireFox 2.0 lub nowszej, Opera 9.0 lub nowszej, Internet Explorer 6.0 lub nowszej, Safari 3.0.2 lub nowszej.');
define('ErrorTurnedOffActiveX', 'Obsługa ActiveX jest wyłączona . <br/>Proszę włączyć obsługę ActiveX przed uruchomieniem tej aplikacji.');

define('Calendar', 'Kalendarz');

define('TabDay', 'Dzień');
define('TabWeek', 'Tydzień');
define('TabMonth', 'Miesiąc');

define('ToolNewEvent', 'Nowe&nbsp;Zdarzenie');
define('ToolBack', 'Wstecz');
define('ToolToday', 'Dzisiaj');
define('AltNewEvent', 'Nowe Zdarzenie');
define('AltBack', 'Wstecz');
define('AltToday', 'Dzisiaj');
define('CalendarHeader', 'Kalendarz');
define('CalendarsManager', 'Menedżer Kalendarzy');

define('CalendarActionNew', 'Nowy kalendarz');
define('EventHeaderNew', 'Nowe Zdarzenie');
define('CalendarHeaderNew', 'Nowy Kalendarz');

define('EventSubject', 'Temat');
define('EventCalendar', 'Kalendarz');
define('EventFrom', 'Od');
define('EventTill', 'Do');
define('CalendarDescription', 'Opis');
define('CalendarColor', 'Kolor');
define('CalendarName', 'Nazwa Kalendarza');
define('CalendarDefaultName', 'Mój Kalendarz');

define('ButtonSave', 'Zapisz');
define('ButtonCancel', 'Anuluj');
define('ButtonDelete', 'Usuń');

define('AltPrevMonth', 'Poprzedni Miesiąc');
define('AltNextMonth', 'Następny Miesiąc');

define('CalendarHeaderEdit', 'Edytuj Kalendarz');
define('CalendarActionEdit', 'Edytuj Kalendarz');
define('ConfirmDeleteCalendar', 'Czy jesteś pewny, że chcesz usunąc kalendarz?');
define('InfoDeleting', 'Usuwanie&hellip;');
define('WarningCalendarNameBlank', 'Nie możesz pozostawić pustej nazwy kalendarza.');
define('ErrorCalendarNotCreated', 'Nie utworzono kalendarza.');
define('WarningSubjectBlank', 'Nie możesz pozostawić pustego tematu.');
define('WarningIncorrectTime', 'Wprowadzony czas zawiera nieprawidłowe znaki.');
define('WarningIncorrectFromTime', 'Czas Od jest nieprawidłowy.');
define('WarningIncorrectTillTime', 'Czas Do jest nieprawidłowy.');
define('WarningStartEndDate', 'Data zakończenia musi być większa lub równa Dacie rozpoczęcia.');
define('WarningStartEndTime', 'Czas zakończenia musi być większy niż Czas rozpoczęcia.');
define('WarningIncorrectDate', 'Data musi być poprawna.');
define('InfoLoading', 'Wczytywanie&hellip;');
define('EventCreate', 'Utwórz zdarzenie');
define('CalendarHideOther', 'Ukryj inne kalendarze');
define('CalendarShowOther', 'Pokaż inne kalendarze');
define('CalendarRemove', 'Usuń kalendarz');
define('EventHeaderEdit', 'Edytuj zdarzenie');

define('InfoSaving', 'Zapisywanie&hellip;');
define('SettingsDisplayName', 'Nazwa Wyświetlana');
define('SettingsTimeFormat', 'Format Czasu');
define('SettingsDateFormat', 'Format Daty');
define('SettingsShowWeekends', 'Pokaż weekendy');
define('SettingsWorkdayStarts', 'Początek dnia roboczego');
define('SettingsWorkdayEnds', 'Koniec dnia roboczego');
define('SettingsShowWorkday', 'Pokaż dni robocze');
define('SettingsWeekStartsOn', 'Tydzień zaczyna się od');
define('SettingsDefaultTab', 'Domyślna Zakładka');
define('SettingsCountry', 'Państwo');
define('SettingsTimeZone', 'Strefa Czasowa');
define('SettingsAllTimeZones', 'Wszystkie strefy czasowe');

define('WarningWorkdayStartsEnds', 'Czas końca dnia roboczego musi być większy niż Czas początku dnia roboczego');
define('ReportSettingsUpdated', 'Ustawienia zostały zaktualizowane.');

define('SettingsTabCalendar', 'Kalendarz');

define('FullMonthJanuary', 'Styczeń');
define('FullMonthFebruary', 'Luty');
define('FullMonthMarch', 'Marzec');
define('FullMonthApril', 'Kwiecień');
define('FullMonthMay', 'Maj');
define('FullMonthJune', 'Czerwiec');
define('FullMonthJuly', 'Lipiec');
define('FullMonthAugust', 'Sierpień');
define('FullMonthSeptember', 'Wrzesień');
define('FullMonthOctober', 'Październik');
define('FullMonthNovember', 'Listopad');
define('FullMonthDecember', 'Grudzień');

define('ShortMonthJanuary', 'Sty');
define('ShortMonthFebruary', 'Lut');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Kwi');
define('ShortMonthMay', 'Maj');
define('ShortMonthJune', 'Cze');
define('ShortMonthJuly', 'Lip');
define('ShortMonthAugust', 'Sie');
define('ShortMonthSeptember', 'Wrz');
define('ShortMonthOctober', 'Paź');
define('ShortMonthNovember', 'Lis');
define('ShortMonthDecember', 'Gru');

define('FullDayMonday', 'Poniedziałek');
define('FullDayTuesday', 'Wtorek');
define('FullDayWednesday', 'Środa');
define('FullDayThursday', 'Czwartek');
define('FullDayFriday', 'Piątek');
define('FullDaySaturday', 'Sobota');
define('FullDaySunday', 'Niedziela');

define('DayToolMonday', 'Pn');
define('DayToolTuesday', 'Wt');
define('DayToolWednesday', 'Śr');
define('DayToolThursday', 'Cz');
define('DayToolFriday', 'Pt');
define('DayToolSaturday', 'So');
define('DayToolSunday', 'N');

define('CalendarTableDayMonday', 'Pn');
define('CalendarTableDayTuesday', 'W');
define('CalendarTableDayWednesday', 'Ś');
define('CalendarTableDayThursday', 'C');
define('CalendarTableDayFriday', 'Pt');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'N');

define('ErrorParseJSON', 'Odpowiedź JSON zwrócona przez serwer nie może zostąć przetworzona.');

define('ErrorLoadCalendar', 'Nie można wczytać kalendarzy');
define('ErrorLoadEvents', 'Nie można wczytać zdarzeń');
define('ErrorUpdateEvent', 'Nie można zapisać zdarzenia');
define('ErrorDeleteEvent', 'Nie można usunąć zdarzenie');
define('ErrorUpdateCalendar', 'Nie można zapisać kalendarza');
define('ErrorDeleteCalendar', 'Nie można usunąć kalendarza');
define('ErrorGeneral', 'Wystąpił błąd serwera. Proszę spróbować później.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Udostępnij i opublikuj kalendarz');
define('ShareActionEdit', 'Udostępnij i opublikuj kalendarz');
define('CalendarPublicate', 'Umożliw zdalny dostęp do tego kalendarza');
define('CalendarPublicationLink', 'Odnośnik');
define('ShareCalendar', 'Udostępnij ten kalendarz');
define('SharePermission1', 'Można zmieniać zasady udostępniania');
define('SharePermission2', 'Można zmieniać zdarzenia');
define('SharePermission3', 'Można zobaczyć wszystkie szczegóły zdarzenia');
define('SharePermission4', 'Można zobaczyć tylko czy wolny/zajęty (ukryj szczegóły)');
define('ButtonClose', 'Zamknij');
define('WarningEmailFieldFilling', 'Powinieneś najpierw wypełnić pole e-mail');
define('EventHeaderView', 'Pokaż Zdarzenie');
define('ErrorUpdateSharing', 'Nie można zapisać zasad udostępniania');
define('ErrorUpdateSharing1', 'Nie można udostępnić kalendarza, ponieważ użytkownik %s nie istnieje');
define('ErrorUpdateSharing2', 'Nie można udostępnić tego kalendarza użytkownikowi %s');
define('ErrorUpdateSharing3', 'Ten kalendarz jest już udostępniony użytkownikowi %s');
define('Title_MyCalendars', 'Moje kalendarze');
define('Title_SharedCalendars', 'Udostępnione kalendarze');
define('ErrorGetPublicationHash', 'Nie można utworzyć odnośnika do publikacji');
define('ErrorGetSharing', 'Nie można dodać udostępniania');
define('CalendarPublishedTitle', 'Ten kalendarz jest opublikowany');
define('RefreshSharedCalendars', 'Odświerz Udostępniane Kalendarze');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Członkowie grupy');

define('ReportMessagePartDisplayed', 'Zwróć uwagę, że część wiadomości jest już wyświetlana.');
define('ReportViewEntireMessage', 'Pokaż całą wiadomość,');
define('ReportClickHere', 'kliknij tutaj');
define('ErrorContactExists', 'Kontakt o takiej nazwie i takim adresie e-mail już istnieje.');

define('Attachments', 'Załączniki');

define('InfoGroupsOfContact', 'Grupy do których należy ten kontakt są zaznaczone ');
define('AlertNoContactsSelected', 'Nie wybrano kontaktów.');
define('MailSelected', 'Wyślij pod wybrane adresy');
define('CaptionSubscribed', 'Subskrybowany');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Not Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Mail contact');
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
define('LanguageDefault', 'Domyślny');

// webmail 4.5.x new
define('EmptySpam', 'Empty Spam');
define('Saving', 'Saving&hellip;');
define('Sending', 'Sending&hellip;');
define('LoggingOffFromServer', 'Logging off from server&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Can\'t mark message(s) as spam');
    define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Can\'t mark message(s) as non-spam');
define('ExportToICalendar', 'Export to iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Your account is disabled because maximum number of users allowed by license is exceeded. Please contact your system administrator.');
define('RepliedMessageTitle', 'Replied Message');
define('ForwardedMessageTitle', 'Forwarded Message');
define('RepliedForwardedMessageTitle', 'Replied and Forwarded Message');
define('ErrorDomainExist', 'The user cannot be created because corresponding domain doesn\'t exist. You should create the domain first.');

// webmail 4.7
define('RequestReadConfirmation', 'Wymagane potwierdzenie przeczytania');
define('FolderTypeDefault', 'Domyślny');
define('ShowFoldersMapping', 'Wybierz inny folder jako folder systemowy (np. użyj "MójFolder" jako "Wysłane")');
define('ShowFoldersMappingNote', 'Na przykład, żeby zmienić lokalizację Wysłanych rzeczy z "Wysłane" na "MójFolder", wybierz "Wysłane" w rozwijanym polu "MójFolder".'); //I don't know - is there any sense in this sentence?
define('FolderTypeMapTo', 'Użyj');

define('ReminderEmailExplanation', 'Ta wiadomość została wysłana na twoje konto %EMAIL% ponieważ ustawiłeś powiadomienie o zdarzeniu w kalendarzu: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Otworz kalendarz');

define('AddReminder', 'Przypomnij mi o tym zdarzeniu');
define('AddReminderBefore', 'Przypomnij % przed tym zdarzeniem');
define('AddReminderAnd', 'i % przed');
define('AddReminderAlso', 'i również % przed');
define('AddMoreReminder', 'Wiecej powiadomień');
define('RemoveAllReminders', 'Usuń wszystkie powiadomienia');
define('ReminderNone', 'Brak');
define('ReminderMinutes', 'minut');
define('ReminderHour', 'godzina');
define('ReminderHours', 'godzin');
define('ReminderDay', 'dzień');
define('ReminderDays', 'dni');
define('ReminderWeek', 'tydzień');
define('ReminderWeeks', 'tygodni');
define('Allday', 'Wszystkie dni');

define('Folders', 'Foldery');
define('NoSubject', 'Brak tematu');
define('SearchResultsFor', 'Szukaj wyników dla');

define('Back', 'Wstecz');
define('Next', 'Dalej');
define('Prev', 'Poprzedni');

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
define('ContactSurName', 'Surname');
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

define('ConfirmExitFromNewMessage', 'Changes will be lost if you leave the page. Would you like to save draft before leaving the page?');

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
define('AppointmentRefused', 'Refuse');
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

define('SettingsTabMobileSync', 'Mobile');

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
