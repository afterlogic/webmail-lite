<?php
define('PROC_ERROR_ACCT_CREATE', 'Došlo k chybě při vytváření účtu');
define('PROC_WRONG_ACCT_PWD', 'Špatné heslo k účtu');
define('PROC_CANT_LOG_NONDEF', 'nelze se přihlásit do jiného než defaultního účtu');
define('PROC_CANT_INS_NEW_FILTER', 'Nelze vložit nový filter');
define('PROC_FOLDER_EXIST', 'Název složky již existuje');
define('PROC_CANT_CREATE_FLD', 'Nelze vytvořit složku');
define('PROC_CANT_INS_NEW_GROUP', 'Nelze vytvořit novou skupinu');
define('PROC_CANT_INS_NEW_CONT', 'Nelze vytvořit nový kontakt');
define('PROC_CANT_INS_NEW_CONTS', 'Nelze vytvořit nový kontakt(y)');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Nelze přidat kontakt do skupiny');
define('PROC_ERROR_ACCT_UPDATE', 'Došlo k chybě při aktualizaci účtu');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Nelze aktualizovat nastavení kontaktů');
define('PROC_CANT_GET_SETTINGS', 'Nelze získat nastavení');
define('PROC_CANT_UPDATE_ACCT', 'Nelze aktualizovat účet');
define('PROC_ERROR_DEL_FLD', 'Nelze vymazat složky');
define('PROC_CANT_UPDATE_CONT', 'Nelze aktualizovat kontakt');
define('PROC_CANT_GET_FLDS', 'Nelze získat strom složek');
define('PROC_CANT_GET_MSG_LIST', 'Nelze získat seznam zpráv');
define('PROC_MSG_HAS_DELETED', 'Tato zpráva již byla ze serveru odstraněna');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Nelze našíst nastavení kontaktů');
define('PROC_CANT_LOAD_SIGNATURE', 'Nelze načíst podpis');
define('PROC_CANT_GET_CONT_FROM_DB', 'Nelze otevřít kontakty z databáze');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Nelze otevřít kontakty z databáze');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Nelze odstranit účet');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Nelze odstranit filter');
define('PROC_CANT_DEL_CONT_GROUPS', 'Nelze odstranit kontakt nebo skupinu');
define('PROC_WRONG_ACCT_ACCESS', 'Přístup k účtu není povolen');
define('PROC_SESSION_ERROR', 'Vaše sezení vypršelo (časový limit)');

define('MailBoxIsFull', 'Schránka je plná');
define('WebMailException', 'Interní chyba serveru. Prosím, kontaktujte vašeho administrátora a sdělte mu chybu.');
define('InvalidUid', 'Chybná zpráva UID');
define('CantCreateContactGroup', 'Nelze vytvořit skupinu kontaktů');
define('CantCreateUser', 'Nelze vytvořit uživatele');
define('CantCreateAccount', 'Nelze vytvořit účet');
define('SessionIsEmpty', 'Relace je prázdná');
define('FileIsTooBig', 'Soubor je příliš velký');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Nelze označit vše jako přečtené');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Nelze označit vše jako nepřečtené');
define('PROC_CANT_PURGE_MSGS', 'Chyba');
define('PROC_CANT_DEL_MSGS', 'Nelze vymazat zprávy');
define('PROC_CANT_UNDEL_MSGS', 'Nelze obnovit zprávy');
define('PROC_CANT_MARK_MSGS_READ', 'Nelze označit jako přečtené');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Nelze označit jako nepřečtené');
define('PROC_CANT_SET_MSG_FLAGS', 'Chyba');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Nelze odebrat praporek');
define('PROC_CANT_CHANGE_MSG_FLD', 'Nelze změnit složku');
define('PROC_CANT_SEND_MSG', 'Nelze odeslat zprávu.');
define('PROC_CANT_SAVE_MSG', 'Nelze uložit zprávu.');
define('PROC_CANT_GET_ACCT_LIST', 'Nelze vypsat seznam účtů');
define('PROC_CANT_GET_FILTER_LIST', 'Nelze vytvořit filtr');

define('PROC_CANT_LEAVE_BLANK', 'Prosím vyplňte vše s *');

define('PROC_CANT_UPD_FLD', 'Nelze aktualizovat složku');
define('PROC_CANT_UPD_FILTER', 'Nelze aktualizovat filtr');

define('ACCT_CANT_ADD_DEF_ACCT', 'Tento účet nelze přidat, protože se používá jako účet jiného uživatele.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Tento stav účtu nelze změnit na výchozí.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Nelze vytvořit nový účet (IMAP4 chyba připojení)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Nelze vymazat poslední defaultní účet');

define('LANG_LoginInfo', 'Přihlašování');
define('LANG_Email', 'Email');
define('LANG_Login', 'Jméno');
define('LANG_Password', 'Heslo');
define('LANG_IncServer', 'Příchozí&nbsp;mail');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'Odchozí&nbsp;mail');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Použít&nbsp;SMTP&nbsp;autentizaci');
define('LANG_SignMe', 'Přihlašovat se automaticky');
define('LANG_Enter', 'Přihlásit');

// interface strings

define('JS_LANG_TitleLogin', 'Jméno');
define('JS_LANG_TitleMessagesListView', 'Seznam zpráv');
define('JS_LANG_TitleMessagesList', 'Seznam zpráv');
define('JS_LANG_TitleViewMessage', 'Zobrazit zprávu');
define('JS_LANG_TitleNewMessage', 'Nová zpráva');
define('JS_LANG_TitleSettings', 'Nastavení');
define('JS_LANG_TitleContacts', 'Kontakty');

define('JS_LANG_StandardLogin', 'Standardní&nbsp;login');
define('JS_LANG_AdvancedLogin', 'Pokročilý&nbsp;login');

define('JS_LANG_InfoWebMailLoading', 'Načítám webmail&hellip;');
define('JS_LANG_Loading', 'Načítám&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Načítání seznamu zpráv');
define('JS_LANG_InfoEmptyFolder', 'Složka je prázdná');
define('JS_LANG_InfoPageLoading', 'Stránka je načítána&hellip;');
define('JS_LANG_InfoSendMessage', 'Zpráva odeslána');
define('JS_LANG_InfoSaveMessage', 'Zpráva uložena');
define('JS_LANG_InfoHaveImported', 'Importováno');
define('JS_LANG_InfoNewContacts', 'nový kontakt(y) do adresáře.');
define('JS_LANG_InfoToDelete', 'K odstranění');
define('JS_LANG_InfoDeleteContent', 'odstranit prvně celý obsah složky');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Není dovoleno odstranit plné složky. Odstraňte nejdříve obsah složky.');
define('JS_LANG_InfoRequiredFields', '* povinná pole');

define('JS_LANG_ConfirmAreYouSure', 'Jste si jisti?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Opravdu úplně odstranit označené zprávy?');
define('JS_LANG_ConfirmSaveSettings', 'Nastavení není uloženo. Stiskněte OK pro uložení.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Nastavení kontaktů není uloženo. Stiskněte OK pro uložení.');
define('JS_LANG_ConfirmSaveAcctProp', 'Předvolby účtu nejsou uloženy. Stiskněte OK pro uložení.');
define('JS_LANG_ConfirmSaveFilter', 'Předvolby filtrů nejsou uloženy. Stiskněte OK pro uložení.');
define('JS_LANG_ConfirmSaveSignature', 'Podpis není uložen. Stiskněte OK pro uložení.');
define('JS_LANG_ConfirmSavefolders', 'Složky nejsou uloženy. Stiskněte OK pro uložení.');
define('JS_LANG_ConfirmHtmlToPlain', 'Upozornění: Pře změně formátování této zprávy z HTML na prostý text, ztratíte veškeré formátování. Stiskněte ok pro pokračování.');
define('JS_LANG_ConfirmAddFolder', 'Změny nejsou uloženy. Stiskněte OK pro uložení.');
define('JS_LANG_ConfirmEmptySubject', 'Není vyplněn předmět. Chcete pokračovat?');

define('JS_LANG_WarningEmailBlank', 'Nelze odejít<br />"Email" prázdné pole.');
define('JS_LANG_WarningLoginBlank', 'Nelze odejít<br />"Jméno" prázdné pole.');
define('JS_LANG_WarningToBlank', 'Pole "Komu" nemůže být prázdné.');
define('JS_LANG_WarningServerPortBlank', 'POP3, SMTP a port<br />nemůže být prázdné.');
define('JS_LANG_WarningEmptySearchLine', 'Prázdné políčko hledání. Zadejte procím co hledat.');
define('JS_LANG_WarningMarkListItem', 'Vyberte prosím alespoň jednu položku ze seznamu.');
define('JS_LANG_WarningFolderMove', 'Složka nemůže být přesunuta, protože je v další úrovni.');
define('JS_LANG_WarningContactNotComplete', 'Prosmím vložte jméno a e-mail.');
define('JS_LANG_WarningGroupNotComplete', 'Zadejte jméno skupiny.');

define('JS_LANG_WarningEmailFieldBlank', '"Email" nemůže být prázdný');
define('JS_LANG_WarningIncServerBlank', 'POP3(IMAP4) nemůže být prázdný.');
define('JS_LANG_WarningIncPortBlank', 'POP3(IMAP4) port nemůže být prázdný.');
define('JS_LANG_WarningIncLoginBlank', 'POP3(IMAP4) login nemůže být prázdný.');
define('JS_LANG_WarningIncPortNumber', 'Zadejte správné číslo portu pro POP3 či IMAP');
define('JS_LANG_DefaultIncPortNumber', 'Defaultní POP3(IMAP4) port je 110(143).');
define('JS_LANG_WarningIncPassBlank', 'POP3(IMAP4) heslo nemůže být prázdný.');
define('JS_LANG_WarningOutPortBlank', 'SMTP port nemůže být prázdný.');
define('JS_LANG_WarningOutPortNumber', 'Zadejte správný SMTP port.');
define('JS_LANG_WarningCorrectEmail', 'Zadejte správný e-mail.');
define('JS_LANG_DefaultOutPortNumber', 'Defaultní SMTP je 25.');

define('JS_LANG_WarningCsvExtention', 'Musí být .csv');
define('JS_LANG_WarningImportFileType', 'Prosím vyberte z jaké aplikace chcete importovat.');
define('JS_LANG_WarningEmptyImportFile', 'Prosím vyberte soubor.');

define('JS_LANG_WarningContactsPerPage', 'Kontakty na stránce jsou v pořádku');
define('JS_LANG_WarningMessagesPerPage', 'Zprávy na stránce jsou v pořádku');
define('JS_LANG_WarningMailsOnServerDays', 'Zadejte správné číslo ve zprávách na serveru.');
define('JS_LANG_WarningEmptyFilter', 'Prosím zadejte podsložku');
define('JS_LANG_WarningEmptyFolderName', 'Zadejte jméno složky');

define('JS_LANG_ErrorConnectionFailed', 'Chyba komunikace');
define('JS_LANG_ErrorRequestFailed', 'Přenos dat není dokončen');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'The object XMLHttpRequest is absent');
define('JS_LANG_ErrorWithoutDesc', 'K chybě došlo bez popisu');
define('JS_LANG_ErrorParsing', 'Chyba XML.');
define('JS_LANG_ResponseText', 'Text odpovědi:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Prázdný XML packet');
define('JS_LANG_ErrorImportContacts', 'Chyba importu kontaktů');
define('JS_LANG_ErrorNoContacts', 'Žádné kontakty pro import.');
define('JS_LANG_ErrorCheckMail', 'Příjem zpráv ukončen kvůli chybě. Nejspíše nebyli všechny přijaté.');

define('JS_LANG_LoggingToServer', 'Přihlašování k serveru&hellip;');
define('JS_LANG_GettingMsgsNum', 'Chci číslo ze zprávy');
define('JS_LANG_RetrievingMessage', 'Načítá se zpráva');
define('JS_LANG_DeletingMessage', 'Mažu zprávu');
define('JS_LANG_DeletingMessages', 'Mažu zprávu(y)');
define('JS_LANG_Of', 'z');
define('JS_LANG_Connection', 'Připojení');
define('JS_LANG_Charset', 'Kódování');
define('JS_LANG_AutoSelect', 'Automaticky');

define('JS_LANG_Contacts', 'Kontakty');
define('JS_LANG_ClassicVersion', 'Klasická verze');
define('JS_LANG_Logout', 'Odhlásit');
define('JS_LANG_Settings', 'Nastavení');

define('JS_LANG_LookFor', 'Hledat: ');
define('JS_LANG_SearchIn', 'Hledat v: ');
define('JS_LANG_QuickSearch', 'Hledat pouze "Od", "Komu" a "Předmět" (rychlejší).');
define('JS_LANG_SlowSearch', 'Hledat v celých zprávách');
define('JS_LANG_AllMailFolders', 'Všechny zprávy ve složce');
define('JS_LANG_AllGroups', 'Všechny skupiny');

define('JS_LANG_NewMessage', 'Nová zpráva');
define('JS_LANG_CheckMail', 'Zkontrolovat poštu');
define('JS_LANG_EmptyTrash', 'Vyprázdnit koš');
define('JS_LANG_MarkAsRead', 'Označit jako přečtené');
define('JS_LANG_MarkAsUnread', 'Označit jako nepřečtené');
define('JS_LANG_MarkFlag', 'Praporek');
define('JS_LANG_MarkUnflag', 'Bez praporku');
define('JS_LANG_MarkAllRead', 'Označit vše jako přečtené');
define('JS_LANG_MarkAllUnread', 'Označit vše jako nepřečtené');
define('JS_LANG_Reply', 'Odpovědět');
define('JS_LANG_ReplyAll', 'Odpovědět všem');
define('JS_LANG_Delete', 'Odstranit');
define('JS_LANG_Undelete', 'Vrátit odstranění');
define('JS_LANG_PurgeDeleted', 'Vymazat odstraněné');
define('JS_LANG_MoveToFolder', 'Přesunout do složky');
define('JS_LANG_Forward', 'Přeposlat');

define('JS_LANG_HideFolders', 'Skrýt složky');
define('JS_LANG_ShowFolders', 'Zobrazit složky');
define('JS_LANG_ManageFolders', 'Správa složek');
define('JS_LANG_SyncFolder', 'Synchronizovat složky');
define('JS_LANG_NewMessages', 'Nová zpráva');
define('JS_LANG_Messages', 'Zpráva(y)');

define('JS_LANG_From', 'Od');
define('JS_LANG_To', 'Komu');
define('JS_LANG_Date', 'Datum');
define('JS_LANG_Size', 'Velikost');
define('JS_LANG_Subject', 'Předmět');

define('JS_LANG_FirstPage', 'První stránka');
define('JS_LANG_PreviousPage', 'Předchozí stránka');
define('JS_LANG_NextPage', 'Další stránka');
define('JS_LANG_LastPage', 'Poslední stránka');

define('JS_LANG_SwitchToPlain', 'Klasický text');
define('JS_LANG_SwitchToHTML', 'HTML text');
define('JS_LANG_AddToAddressBook', 'Přidat do kontaktů');
define('JS_LANG_ClickToDownload', 'Stáhnout ');
define('JS_LANG_View', 'Zobrazit');
define('JS_LANG_ShowFullHeaders', 'Zobrazit celou hlavičku');
define('JS_LANG_HideFullHeaders', 'Skrýt celou hlavičku');

define('JS_LANG_MessagesInFolder', 'Zpráv ve složce');
define('JS_LANG_YouUsing', 'Používáte');
define('JS_LANG_OfYour', 'z');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Odeslat');
define('JS_LANG_SaveMessage', 'Uložit');
define('JS_LANG_Print', 'Tisk');
define('JS_LANG_PreviousMsg', 'Předchozí zpráva');
define('JS_LANG_NextMsg', 'Další zpráva');
define('JS_LANG_AddressBook', 'Adresář');
define('JS_LANG_ShowBCC', 'Zobrazit BCC');
define('JS_LANG_HideBCC', 'Skrýt BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Odpovědet&nbsp;Komu');
define('JS_LANG_AttachFile', 'Příložený soubor');
define('JS_LANG_Attach', 'Příloha');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Původní zpráva');
define('JS_LANG_Sent', 'Odeslané');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Nízká');
define('JS_LANG_Normal', 'Normální');
define('JS_LANG_High', 'Vysoká');
define('JS_LANG_Importance', 'Důležitost');
define('JS_LANG_Close', 'Zavřít');

define('JS_LANG_Common', 'Obyčejný');
define('JS_LANG_EmailAccounts', 'Emailové účty');

define('JS_LANG_MsgsPerPage', 'Zprávy na stránku');
define('JS_LANG_DisableRTE', 'DVypnout HTML editor');
define('JS_LANG_Skin', 'Vzhled');
define('JS_LANG_DefCharset', 'Výchozí kódování');
define('JS_LANG_DefCharsetInc', 'Výchozí příchozí kódování');
define('JS_LANG_DefCharsetOut', 'Výchozí odchozí kódování');
define('JS_LANG_DefTimeOffset', 'Časové pásmo');
define('JS_LANG_DefLanguage', 'Jazyk');
define('JS_LANG_DefDateFormat', 'Formát data');
define('JS_LANG_ShowViewPane', 'Seznam zpráv s náhledem');
define('JS_LANG_Save', 'Uložit');
define('JS_LANG_Cancel', 'Zavřít');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Vyjmout');
define('JS_LANG_AddNewAccount', 'Přidat nový účet');
define('JS_LANG_Signature', 'Podpis');
define('JS_LANG_Filters', 'Filtry');
define('JS_LANG_Properties', 'Předvolby');
define('JS_LANG_UseForLogin', 'Předvolby tohoto účtu (jméno a heslo) pro přihlášení.');
define('JS_LANG_MailFriendlyName', 'Vaše jméno');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Příchozý pošta');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Jméno');
define('JS_LANG_MailIncPass', 'Heslo');
define('JS_LANG_MailOutHost', 'Odchozí e-mail');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP jméno');
define('JS_LANG_MailOutPass', 'SMTP heslo');
define('JS_LANG_MailOutAuth1', 'použít SMTP ověřování');
define('JS_LANG_MailOutAuth2', '(Můžete nechat SMTP jméno/heslo prázdné, když\'je stejný jako POP3 (IMAP) jméno a heslo)');
define('JS_LANG_UseFriendlyNm1', 'Použít jméno propole "Od"');
define('JS_LANG_UseFriendlyNm2', '(Vaše jméno &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Synchronizovat emaily pro přihlášení');
define('JS_LANG_MailMode0', 'Smazat přijaté e-maily ze serveru');
define('JS_LANG_MailMode1', 'Ponechat zprávy na serveru');
define('JS_LANG_MailMode2', 'Mít zprávy na serveru');
define('JS_LANG_MailsOnServerDays', 'den(dny)');
define('JS_LANG_MailMode3', 'Odstranit zprávu ze serveru, pokud ji odstraním z koše');
define('JS_LANG_InboxSyncType', 'Typ synchronizace');

define('JS_LANG_SyncTypeNo', 'Žádná');
define('JS_LANG_SyncTypeNewHeaders', 'Nové hlavičky');
define('JS_LANG_SyncTypeAllHeaders', 'Všechny hlavičky');
define('JS_LANG_SyncTypeNewMessages', 'Nová zpráva');
define('JS_LANG_SyncTypeAllMessages', 'Všechny zprávy');
define('JS_LANG_SyncTypeDirectMode', 'Přímý režim');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Pouze hlavičky');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Celé zprávy');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Přímý režim');

define('JS_LANG_DeleteFromDb', 'Odstranit zprávu z databáze v případě, že již neexistuje na serveru.');

define('JS_LANG_EditFilter', 'Upravit&nbsp;filtr');
define('JS_LANG_NewFilter', 'Přidat nový filtr');
define('JS_LANG_Field', 'Pole');
define('JS_LANG_Condition', 'Podmínka');
define('JS_LANG_ContainSubstring', 'Obsahuje');
define('JS_LANG_ContainExactPhrase', 'Přesně');
define('JS_LANG_NotContainSubstring', 'Neobsahuje');
define('JS_LANG_FilterDesc_At', 'pak');
define('JS_LANG_FilterDesc_Field', 'pole');
define('JS_LANG_Action', 'Akce');
define('JS_LANG_DoNothing', 'Nedělat nic');
define('JS_LANG_DeleteFromServer', 'Odstranit ihned ze serveru');
define('JS_LANG_MarkGrey', 'Označit šedě');
define('JS_LANG_Add', 'Přidat');
define('JS_LANG_OtherFilterSettings', 'Ostatní nastavení filtru');
define('JS_LANG_ConsiderXSpam', 'Rozpoznávat X-Spam hlavičky');
define('JS_LANG_Apply', 'Použít');

define('JS_LANG_InsertLink', 'Vložit odkaz');
define('JS_LANG_RemoveLink', 'Odebrat odkaz');
define('JS_LANG_Numbering', 'Číslování');
define('JS_LANG_Bullets', 'Odrážky');
define('JS_LANG_HorizontalLine', 'Vodorovná čára');
define('JS_LANG_Bold', 'Tučně');
define('JS_LANG_Italic', 'Kurzíva');
define('JS_LANG_Underline', 'Podržené');
define('JS_LANG_AlignLeft', 'Doleva');
define('JS_LANG_Center', 'Na střed');
define('JS_LANG_AlignRight', 'Doprava');
define('JS_LANG_Justify', 'Roztáhnout');
define('JS_LANG_FontColor', 'Barva texu');
define('JS_LANG_Background', 'Pozadí');
define('JS_LANG_SwitchToPlainMode', 'Prostý text');
define('JS_LANG_SwitchToHTMLMode', 'Formátovaný HTML text');

define('JS_LANG_Folder', 'Složka');
define('JS_LANG_Msgs', 'Zpráv');
define('JS_LANG_Synchronize', 'Synchronizovat');
define('JS_LANG_ShowThisFolder', 'Zobrazit tuto složku');
define('JS_LANG_Total', 'Celkem');
define('JS_LANG_DeleteSelected', 'Vymazat vybrané');
define('JS_LANG_AddNewFolder', 'Přidat novou složku');
define('JS_LANG_NewFolder', 'Nová složka');
define('JS_LANG_ParentFolder', 'Nadřazená složka');
define('JS_LANG_NoParent', 'Nenadřazená');
define('JS_LANG_FolderName', 'Název složky');

define('JS_LANG_ContactsPerPage', 'Kontakty na stránku');
define('JS_LANG_WhiteList', 'Adresář kontaktů jako bílá listina pro spam');

define('JS_LANG_CharsetDefault', 'Defaultní');
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

define('JS_LANG_TimeDefault', 'Defaultní');
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

define('JS_LANG_DateDefault', 'Defaultní');
define('JS_LANG_DateDDMMYY', 'DD/MM/RR');
define('JS_LANG_DateMMDDYY', 'MM/DD/RR');
define('JS_LANG_DateDDMonth', 'DD Měsíc (01 Leden)');
define('JS_LANG_DateAdvanced', 'Pokročilé');

define('JS_LANG_NewContact', 'Nový kontakt');
define('JS_LANG_NewGroup', 'Nová skupina');
define('JS_LANG_AddContactsTo', 'Přidat kontakt do');
define('JS_LANG_ImportContacts', 'Importovat kontakty');

define('JS_LANG_Name', 'Jméno');
define('JS_LANG_Email', 'E-mail');
define('JS_LANG_DefaultEmail', 'E-mail');
define('JS_LANG_NotSpecifiedYet', 'Nebylo doposud definováno');
define('JS_LANG_ContactName', 'Jméno');
define('JS_LANG_Birthday', 'Narození');
define('JS_LANG_Month', 'Měsíc');
define('JS_LANG_January', 'Leden');
define('JS_LANG_February', 'Únor');
define('JS_LANG_March', 'Březen');
define('JS_LANG_April', 'Duben');
define('JS_LANG_May', 'Květen');
define('JS_LANG_June', 'Červen');
define('JS_LANG_July', 'Červenec');
define('JS_LANG_August', 'Srpen');
define('JS_LANG_September', 'Září');
define('JS_LANG_October', 'Říjen');
define('JS_LANG_November', 'Listopad');
define('JS_LANG_December', 'Prosinec');
define('JS_LANG_Day', 'Den');
define('JS_LANG_Year', 'Rok');
define('JS_LANG_UseFriendlyName1', 'Název');
define('JS_LANG_UseFriendlyName2', '(například, John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Osobní');
define('JS_LANG_PersonalEmail', 'Osobní E-mail');
define('JS_LANG_StreetAddress', 'Ulice');
define('JS_LANG_City', 'Město');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Kraj');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'PSČ');
define('JS_LANG_Mobile', 'Mobil');
define('JS_LANG_CountryRegion', 'Stát');
define('JS_LANG_WebPage', 'WWW stránky');
define('JS_LANG_Go', 'Pokračovat');
define('JS_LANG_Home', 'Domů');
define('JS_LANG_Business', 'Pracovní');
define('JS_LANG_BusinessEmail', 'Pracovní E-mail');
define('JS_LANG_Company', 'Společnost');
define('JS_LANG_JobTitle', 'Pozice');
define('JS_LANG_Department', 'Oddělení');
define('JS_LANG_Office', 'Kancelář');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Ostatní');
define('JS_LANG_OtherEmail', 'Ostatní E-mail');
define('JS_LANG_Notes', 'Poznámka');
define('JS_LANG_Groups', 'Skupina');
define('JS_LANG_ShowAddFields', 'Zobrazit další pole');
define('JS_LANG_HideAddFields', 'Skrýt další pole');
define('JS_LANG_EditContact', 'Upravit informace o kontaktu');
define('JS_LANG_GroupName', 'Název skupiny');
define('JS_LANG_AddContacts', 'Přidat kontakt');
define('JS_LANG_CommentAddContacts', '(Pokud chcete zadat více adres, tak je oddělte čárkami.)');
define('JS_LANG_CreateGroup', 'Vytvořit skupinu');
define('JS_LANG_Rename', 'přejmenovat');
define('JS_LANG_MailGroup', 'Mail skupina');
define('JS_LANG_RemoveFromGroup', 'Odebrat ze skupiny');
define('JS_LANG_UseImportTo', 'Použijte import kontaktů z aplikací Microsoft Outlook nebo Microsoft Outlook Express do webmailu.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Vyberte soubor (.CSV formát) který chcete naimportovat.');
define('JS_LANG_Import', 'Importovat');
define('JS_LANG_ContactsMessage', 'Toto je stránky s kontaky!!!');
define('JS_LANG_ContactsCount', 'kontakt(y)');
define('JS_LANG_GroupsCount', 'skupina(y)');

// webmail 4.1 constants
define('PicturesBlocked', 'Obrázky byli zablokovány z bezpečnostních důvodů.');
define('ShowPictures', 'Zobrazit obrázky');
define('ShowPicturesFromSender', 'Vždy zobrazovat obrázky od tohoto odesílatele');
define('AlwaysShowPictures', 'Vždy zobrazit obrázky ve zprávě');
define('TreatAsOrganization', 'Zacházet jako s organizací');

define('WarningGroupAlreadyExist', 'Skupina s tímto názvem již existuje. Zadejte jiný.');
define('WarningCorrectFolderName', 'Zadejte správný název složky');
define('WarningLoginFieldBlank', '"Jméno" nemůže být prázdné.');
define('WarningCorrectLogin', 'Zadejte správně jméno.');
define('WarningPassBlank', '"Heslo" nemůže být prázdné.');
define('WarningCorrectIncServer', 'Zadejte správně POP3(IMAP) adresu.');
define('WarningCorrectSMTPServer', 'Zadejte správně adresu odchozího e-mailu.');
define('WarningFromBlank', '"Od" nemůže být prázdné.');
define('WarningAdvancedDateFormat', 'Vyberte formát data.');

define('AdvancedDateHelpTitle', 'Rozšířený datum');
define('AdvancedDateHelpIntro', 'Když &quot;Pokročilé&quot; je vybráno, můžete nastavit vlastní formát data, která by byla zobrazena ve webmailu. Následující možnosti jsou k tomuto účelu pro použití s \':\' nebo \'/\' oddělovací znak:');
define('AdvancedDateHelpConclusion', 'Například, pokud jste\'zadali &quot;mm/dd/yyyy&quot; hodnotu v textovém poli &quot;Pokročilé&quot; pole, se zobrazuje datum měsíc/den/rok (11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Den z měsíce (1 až 31)');
define('AdvancedDateHelpNumericMonth', 'Měsíc (1 až 12)');
define('AdvancedDateHelpTextualMonth', 'Měsíc (Leden až Prosinec)');
define('AdvancedDateHelpYear2', 'Rok, 2 číslice');
define('AdvancedDateHelpYear4', 'Rok, 4 číslice');
define('AdvancedDateHelpDayOfYear', 'Den z roku (1 až 366)');
define('AdvancedDateHelpQuarter', 'Čtvrtletí');
define('AdvancedDateHelpDayOfWeek', 'Den z týdne (Pondělí až Neděle)');
define('AdvancedDateHelpWeekOfYear', 'Týden z roku (1 až 53)');

define('InfoNoMessagesFound', 'Nenalezeny žádné zprávy');
define('ErrorSMTPConnect', 'Nelze připojit k SMTP server. Zkontrolujte nastavení SMTP serveru.');
define('ErrorSMTPAuth', 'Špatné jméno nebo heslo. Chyba přihlášení');
define('ReportMessageSent', 'Vaše zpráva byla odeslána.');
define('ReportMessageSaved', 'Vaše zpráva byla uložena.');
define('ErrorPOP3Connect', 'Nelze připojit k POP3 serveru. Zkontrolujte nastavení.');
define('ErrorIMAP4Connect', 'Nelze připojit k IMAP4 serveru. Zkontrolujte nastavení.');
define('ErrorPOP3IMAP4Auth', 'Špatné jméno nebo heslo.');
define('ErrorGetMailLimit', 'Omlouváme se, ale schránka je plná.');

define('ReportSettingsUpdatedSuccessfuly', 'Nastavení byla úspěšně aktualizována.');
define('ReportAccountCreatedSuccessfuly', 'Účet vytvořen v pořádku.');
define('ReportAccountUpdatedSuccessfuly', 'Účet aktualizován v pořádku.');
define('ConfirmDeleteAccount', 'Chcete opravdu smazat účet?');
define('ReportFiltersUpdatedSuccessfuly', 'Filter je úspěšně aktualizovaný.');
define('ReportSignatureUpdatedSuccessfuly', 'Podpis byl úspěšně aktualizován.');
define('ReportFoldersUpdatedSuccessfuly', 'Folders have been updated successfully.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Kontakty\' nastavení bylo aktualizováno.');

define('ErrorInvalidCSV', 'CSV soubor je neplatný.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Skupina');
define('ReportGroupSuccessfulyAdded2', 'byla úspěšně přidána.');
define('ReportGroupUpdatedSuccessfuly', 'Skupina byla aktualizována v pořádku.');
define('ReportContactSuccessfulyAdded', 'Kontakt byl úspěšně přidán.');
define('ReportContactUpdatedSuccessfuly', 'Kontakt byl úspěšně aktualizován.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Kontakt(y) přidán(y) do skupiny');
define('AlertNoContactsGroupsSelected', 'Nevybrány žádné kontakty či skupiny.');

define('InfoListNotContainAddress', 'Seznam neobsahuje adresu, kteru jste hledali.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Přímý režim. Webmail přistupuje ke zprávám přímo na poštovním serveru.');

define('FolderInbox', 'Doručená pošta');
define('FolderSentItems', 'Odeslaná pošta');
define('FolderDrafts', 'Rozepsaná pošta');
define('FolderTrash', 'Koš');

define('FileLargerAttachment', 'Velikost souboru překračuje povolený limit.');
define('FilePartiallyUploaded', 'Pouze část souboru byla nahrána kvůli neznámé chybě.');
define('NoFileUploaded', 'Nebyl nahrán žádný soubor.');
define('MissingTempFolder', 'Dočasné složky chybí.');
define('MissingTempFile', 'Dočasný soubor chybí.');
define('UnknownUploadError', 'Nahrán neznámý soubor. Došlo k chybě.');
define('FileLargerThan', 'Chyba při nahrávání souboru. Nejpravděpodobnější je, že soubor je větší než ');
define('PROC_CANT_LOAD_DB', 'Nelze se připojit k databázi.');
define('PROC_CANT_LOAD_LANG', 'Nelze najít požadovaný jazyk.');
define('PROC_CANT_LOAD_ACCT', 'Účet neexistuje a nebo byl zrušen.');

define('DomainDosntExist', 'Taková doména na serveru neexistuje.');
define('ServerIsDisable', 'Použití poštovního serveru je zakázáno administrátorem.');

define('PROC_ACCOUNT_EXISTS', 'Tento účet nelze vytvořit, protože již existuje.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Nelze získat počet zpráv ze složky.');
define('PROC_CANT_MAIL_SIZE', 'Nelze zjistit velikost úložiště.');

define('Organization', 'Organizace');
define('WarningOutServerBlank', '"Odchozí pošta" nemůže být prázdná');

define('JS_LANG_Refresh', 'Obnovit');
define('JS_LANG_MessagesInInbox', 'Zpráva(y) v Doručená pošta');
define('JS_LANG_InfoEmptyInbox', 'Doručená pošta je prázdná');

// webmail 4.2 constants
define('BackToList', 'Zpět na seznam');
define('InfoNoContactsGroups', 'Žádná kontakt či skupina.');
define('InfoNewContactsGroups', 'Můžete buď vytvořit nové kontakyt/skupiny, import kontaktů ze souboru. CSV ve formátu MS Outlook.');
define('DefTimeFormat', 'Formát času');
define('SpellNoSuggestions', 'Žádné návrhy');
define('SpellWait', 'Prosím čekejte&hellip;');

define('InfoNoMessageSelected', 'Není vybrána žádná zpráva.');
define('InfoSingleDoubleClick', 'Klikněte na libovolnou zprávu v seznamu a zobrazí se vám zde náhled. Při dvojkliku na zprávu se vám otevře v novém okně.');

// calendar
define('TitleDay', 'Zobrazit den');
define('TitleWeek', 'Zobrazit týden');
define('TitleMonth', 'Zobrazit měsíc');

define('ErrorNotSupportBrowser', 'AfterLogic Kalendář nepodporuje váš prohlížeč. Prosím používejte FireFox 2.0 nebo vyšší, Opera 9.0 nebo vyšší, Internet Explorer 6.0 nebo vyšší, Safari 3.0.2 nebo vyšší.');
define('ErrorTurnedOffActiveX', 'ActiveX podpora je vypnuta . <br/>Zapněte ji prosím.');

define('Calendar', 'Kalendář');

define('TabDay', 'Den');
define('TabWeek', 'Týden');
define('TabMonth', 'Měsíc');

define('ToolNewEvent', 'Nový&nbsp;záznam');
define('ToolBack', 'Zpět');
define('ToolToday', 'Dnes');
define('AltNewEvent', 'Nová událost');
define('AltBack', 'Zpět');
define('AltToday', 'Dnes');
define('CalendarHeader', 'Kalendář');
define('CalendarsManager', 'Kalendář manažer');

define('CalendarActionNew', 'Nový kalendář');
define('EventHeaderNew', 'Nová událost');
define('CalendarHeaderNew', 'Nový kalendář');

define('EventSubject', 'Předmět');
define('EventCalendar', 'Kalendář');
define('EventFrom', 'Od');
define('EventTill', 'Do');
define('CalendarDescription', 'Popis');
define('CalendarColor', 'Barva');
define('CalendarName', 'Název kalendáře');
define('CalendarDefaultName', 'Můj kalendář');

define('ButtonSave', 'Uložit');
define('ButtonCancel', 'Zavřít');
define('ButtonDelete', 'Odstranit');

define('AltPrevMonth', 'Předchozí měsíc');
define('AltNextMonth', 'Další měsíc');

define('CalendarHeaderEdit', 'Upravit kalednář');
define('CalendarActionEdit', 'Upravit kalednář');
define('ConfirmDeleteCalendar', 'Chcete opravdu smazat kalendář?');
define('InfoDeleting', 'Mazání&hellip;');
define('WarningCalendarNameBlank', 'Název kalendáře nemůže být prázdný.');
define('ErrorCalendarNotCreated', 'Kalendář nelze vytvořit.');
define('WarningSubjectBlank', 'Předmět nemůže být prázdný.');
define('WarningIncorrectTime', 'Nepovolené znaky');
define('WarningIncorrectFromTime', '"Od" nesprávný čas.');
define('WarningIncorrectTillTime', '"Do" nesprávný čas.');
define('WarningStartEndDate', 'Datum ukončení musí být větší nebo rovno datu zahájení.');
define('WarningStartEndTime', 'Času ukončení musí být větší než počáteční čas.');
define('WarningIncorrectDate', 'Datum musí být správný');
define('InfoLoading', 'Načítám e-maily&hellip;');
define('EventCreate', 'Vytvořit událost');
define('CalendarHideOther', 'Skrýt ostatní kalendáře');
define('CalendarShowOther', 'Zobrazit ostatní kalendáře');
define('CalendarRemove', 'Odstranit kalendář');
define('EventHeaderEdit', 'Upravit událost');

define('InfoSaving', 'Ukládám&hellip;');
define('SettingsDisplayName', 'Zobrazované jméno');
define('SettingsTimeFormat', 'Formát času');
define('SettingsDateFormat', 'Formát data');
define('SettingsShowWeekends', 'Zobrazit víkendy');
define('SettingsWorkdayStarts', 'Pracovní den začíná');
define('SettingsWorkdayEnds', 'končí');
define('SettingsShowWorkday', 'Zobrazit pracovní dny');
define('SettingsWeekStartsOn', 'Týden začíná');
define('SettingsDefaultTab', 'Výchozí karta');
define('SettingsCountry', 'Země');
define('SettingsTimeZone', 'Časová zóna');
define('SettingsAllTimeZones', 'Všechny časové zóny');

define('WarningWorkdayStartsEnds', '\'Konce pracovních dní\' doba musí být větší než \'Pracovní dny start\' time');
define('ReportSettingsUpdated', 'Nastavení byla aktualizována úspěšně');

define('SettingsTabCalendar', 'Kalendář');

define('FullMonthJanuary', 'Leden');
define('FullMonthFebruary', 'Únor');
define('FullMonthMarch', 'Březen');
define('FullMonthApril', 'Duben');
define('FullMonthMay', 'Květen');
define('FullMonthJune', 'Červen');
define('FullMonthJuly', 'Červenec');
define('FullMonthAugust', 'Srpen');
define('FullMonthSeptember', 'Září');
define('FullMonthOctober', 'Říjen');
define('FullMonthNovember', 'Listopad');
define('FullMonthDecember', 'Prosinec');

define('ShortMonthJanuary', 'Led');
define('ShortMonthFebruary', 'Uno');
define('ShortMonthMarch', 'Bře');
define('ShortMonthApril', 'Dub');
define('ShortMonthMay', 'Kvě');
define('ShortMonthJune', 'Čer');
define('ShortMonthJuly', 'Čnc');
define('ShortMonthAugust', 'Srp');
define('ShortMonthSeptember', 'Zář');
define('ShortMonthOctober', 'Říj');
define('ShortMonthNovember', 'Lis');
define('ShortMonthDecember', 'Pro');

define('FullDayMonday', 'Pondělí');
define('FullDayTuesday', 'Úterý');
define('FullDayWednesday', 'Středa');
define('FullDayThursday', 'Čtvrtek');
define('FullDayFriday', 'Pátek');
define('FullDaySaturday', 'Sobota');
define('FullDaySunday', 'Neděle');

define('DayToolMonday', 'Po');
define('DayToolTuesday', 'Út');
define('DayToolWednesday', 'St');
define('DayToolThursday', 'Čt');
define('DayToolFriday', 'Pá');
define('DayToolSaturday', 'So');
define('DayToolSunday', 'Ne');

define('CalendarTableDayMonday', 'P');
define('CalendarTableDayTuesday', 'Ú');
define('CalendarTableDayWednesday', 'S');
define('CalendarTableDayThursday', 'Č');
define('CalendarTableDayFriday', 'P');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'N');

define('ErrorParseJSON', 'JSON nelze analyzovat.');

define('ErrorLoadCalendar', 'Nelze načíst kalendář');
define('ErrorLoadEvents', 'Nelze načíst událost');
define('ErrorUpdateEvent', 'Nelze uložit událost');
define('ErrorDeleteEvent', 'Nelze smazat událost');
define('ErrorUpdateCalendar', 'Nelze uložit kalendář');
define('ErrorDeleteCalendar', 'Nelze smazat kalednář');
define('ErrorGeneral', 'Došlo k chybě na serveru. Zkuste to znovu později.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Sdílet a publikovat kalendář');
define('ShareActionEdit', 'Sdílet a publikovat kalendář');
define('CalendarPublicate', 'Zveřejnit webový přístup do tohoto kalendáře');
define('CalendarPublicationLink', 'Odkaz');
define('ShareCalendar', 'Sdílet tento kalendář');
define('SharePermission1', 'Mohou provádět změny a řídit sdílení');
define('SharePermission2', 'Může provádět změny na události');
define('SharePermission3', 'Zobrazit všechny detaily události');
define('SharePermission4', 'Mohou vidět pouze volný/zaneprázdněný (skrýt detaily)');
define('ButtonClose', 'Zavřít');
define('WarningEmailFieldFilling', 'Měli byste vyplnit prvně e-mail');
define('EventHeaderView', 'Zobrazit událost');
define('ErrorUpdateSharing', 'Nelze uložit sdílení a publikování');
define('ErrorUpdateSharing1', 'Není možné, aby %s uživatel neexistoval.');
define('ErrorUpdateSharing2', 'Sdílet tento kalendář pouze pro uživatele %s');
define('ErrorUpdateSharing3', 'Tento kalendář je sdílen s uživateli %s');
define('Title_MyCalendars', 'Moje kalendáře');
define('Title_SharedCalendars', 'Sdílené kalendáře');
define('ErrorGetPublicationHash', 'Nelze vytvořit odkaz pro publikování');
define('ErrorGetSharing', 'Nelze přidat sdílení');
define('CalendarPublishedTitle', 'Tento kalendář je publikován');
define('RefreshSharedCalendars', 'Obnovit sdílení kalendáře');
define('Title_CheckSharedCalendars', 'Zkontrolovat sdílení kalendářů');

define('GroupMembers', 'Členové');

define('ReportMessagePartDisplayed', 'Vezměte na vědomí, že jen část zprávy se zobrazí.');
define('ReportViewEntireMessage', 'Chcete-li zobrazit celou zprávu,');
define('ReportClickHere', 'klikněte zde');
define('ErrorContactExists', 'Kontakt s tímto jménem a hesla již existuje.');

define('Attachments', 'Přílohy');

define('InfoGroupsOfContact', 'Členové jsou označeny.');
define('AlertNoContactsSelected', 'Žádné vybrané kontakty.');
define('MailSelected', 'Vybrané e-mailové adresy');
define('CaptionSubscribed', 'Označeno');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Není spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Odeslat e-mail');
define('ContactViewAllMails', 'Zobrazit všechny e-maily s tímto kontaktem');
define('ContactsMailThem', 'Mailem');
define('DateToday', 'Dnes');
define('DateYesterday', 'Včera');
define('MessageShowDetails', 'Zobrazit detaily');
define('MessageHideDetails', 'Skrýt detaily');
define('MessageNoSubject', 'Žádný předmět');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'komu');
define('SearchClear', 'Vymazat hledání');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Výsledky hledání "#s" ve #f složce:');
define('SearchResultsInAllFolders', 'Výsledky hledání "#s" ve všech složkách:');
define('AutoresponderTitle', 'Automatická odpověď');
define('AutoresponderEnable', 'Zapnout automatickou odpověď');
define('AutoresponderSubject', 'Předmět');
define('AutoresponderMessage', 'Zpráva');
define('ReportAutoresponderUpdatedSuccessfuly', 'Automatiská odpověď byla v pořádku nahrána.');
define('FolderQuarantine', 'Karanténa');

//calendar
define('EventRepeats', 'Opakovat');
define('NoRepeats', 'Neopakovta');
define('DailyRepeats', 'Deně');
define('WorkdayRepeats', 'Každý týden (Po. - Pá.)');
define('OddDayRepeats', 'Každý St. a Pá.');
define('EvenDayRepeats', 'Každý Út. a Čt.');
define('WeeklyRepeats', 'Týdně');
define('MonthlyRepeats', 'Měsíčně');
define('YearlyRepeats', 'Ročně');
define('RepeatsEvery', 'Opakovat každé');
define('ThisInstance', 'Pouze tato instance');
define('AllEvents', 'Všechny události v sérii');
define('AllFollowing', 'Všechny tyto');
define('ConfirmEditRepeatEvent', 'Chcete změnit pouze tuto událost, všechny události a to i budoucí události v této sérii?');
define('RepeatEventHeaderEdit', 'Upravit opakování události');
define('First', 'První');
define('Second', 'Druhý');
define('Third', 'třetí');
define('Fourth', 'Čtvrtý');
define('Last', 'Poslední');
define('Every', 'Každý');
define('SetRepeatEventEnd', 'Nastavit datum konce');
define('NoEndRepeatEvent', 'Bez data ukončení');
define('EndRepeatEventAfter', 'Konec po');
define('Occurrences', 'události');
define('EndRepeatEventBy', 'Končit');
define('EventCommonDataTab', 'Moje - podrobnosti');
define('EventRepeatDataTab', 'Opakování - podrobnosti');
define('RepeatEventNotPartOfASeries', 'Tato událost se změnila a již není součástí série.');
define('UndoRepeatExclusion', 'Provedené změny jsou v v sérii.');

define('MonthMoreLink', '%d více...');
define('NoNewSharedCalendars', 'Žádné nové kalendáře');
define('NNewSharedCalendars', '%d nový kalendář nenalezen');
define('OneNewSharedCalendars', '1 nový kalendář nalezen');
define('ConfirmUndoOneRepeat', 'Chcete obnovit tuto událost v sérii?');

define('RepeatEveryDayInfin', 'Každý den');
define('RepeatEveryDayTimes', 'Každý den, %TIMES% čas');
define('RepeatEveryDayUntil', 'Každý den, do %UNTIL%');
define('RepeatDaysInfin', 'Každé %PERIOD% dny');
define('RepeatDaysTimes', 'Každé %PERIOD% dny, %TIMES% čas');
define('RepeatDaysUntil', 'Každé %PERIOD% dny, do %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Každý týden o víkendu');
define('RepeatEveryWeekWeekdaysTimes', 'Každý týden o víkendu, %TIMES% čas');
define('RepeatEveryWeekWeekdaysUntil', 'Každý týden o víkendu, do %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Každé %PERIOD% týdny o víkendu');
define('RepeatWeeksWeekdaysTimes', 'Každé %PERIOD% týdny o víkendu, %TIMES% čas');
define('RepeatWeeksWeekdaysUntil', 'Každé %PERIOD% týdny o víkendu, do %UNTIL%');

define('RepeatEveryWeekInfin', 'Každý týden o %DAYS%');
define('RepeatEveryWeekTimes', 'Každý týden o %DAYS%, %TIMES% čas');
define('RepeatEveryWeekUntil', 'Každý týden o %DAYS%, until %UNTIL%');
define('RepeatWeeksInfin', 'Každé %PERIOD% týden o %DAYS%');
define('RepeatWeeksTimes', 'Každé %PERIOD% týden o %DAYS%, %TIMES% čas');
define('RepeatWeeksUntil', 'Každé %PERIOD% týden o %DAYS%, do %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Každý měsíc dne %DATE%');
define('RepeatEveryMonthDateTimes', 'Každý měsíc dne %DATE%, %TIMES% čas');
define('RepeatEveryMonthDateUntil', 'Everý měsíc dne %DATE%, do %UNTIL%');
define('RepeatMonthsDateInfin', 'Každý %PERIOD% měsíc dne %DATE%');
define('RepeatMonthsDateTimes', 'Každý %PERIOD% měsíc dne %DATE%, %TIMES% čas');
define('RepeatMonthsDateUntil', 'Každý %PERIOD% měsíc dne %DATE%, do %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Každý měsíc o %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Každý měsíc o %NUMBER% %DAY%, %TIMES% čas');
define('RepeatEveryMonthWDUntil', 'Každý měsíc o %NUMBER% %DAY%, do %UNTIL%');
define('RepeatMonthsWDInfin', 'Každý %PERIOD% měsíc o %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Každý %PERIOD% měsíc o %NUMBER% %DAY%, %TIMES% čas');
define('RepeatMonthsWDUntil', 'Každý %PERIOD% měsíc o %NUMBER% %DAY%, do %UNTIL%');

define('RepeatEveryYearDateInfin', 'Každý rok dne %DATE%');
define('RepeatEveryYearDateTimes', 'Každý rok dne %DATE%, %TIMES% čas');
define('RepeatEveryYearDateUntil', 'Každý rok dne %DATE%, do %UNTIL%');
define('RepeatYearsDateInfin', 'Každé %PERIOD% rok dne %DATE%');
define('RepeatYearsDateTimes', 'Každé %PERIOD% rok dne %DATE%, %TIMES% čas');
define('RepeatYearsDateUntil', 'Každé %PERIOD% rok dne %DATE%, do %UNTIL%');

define('RepeatEveryYearWDInfin', 'Každý rok %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Každý rok o %NUMBER% %DAY%, %TIMES% čas');
define('RepeatEveryYearWDUntil', 'Každý rok o %NUMBER% %DAY%, do %UNTIL%');
define('RepeatYearsWDInfin', 'Každý %PERIOD% rok o %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Každý %PERIOD% rok o %NUMBER% %DAY%, %TIMES% čas');
define('RepeatYearsWDUntil', 'Každý %PERIOD% rok o %NUMBER% %DAY%, do %UNTIL%');

define('RepeatDescDay', 'den');
define('RepeatDescWeek', 'týden');
define('RepeatDescMonth', 'měsíc');
define('RepeatDescYear', 'rok');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'uveďte datum konce opakování.');
define('WarningWrongUntilDate', 'Konec opakování musí být pozdější než datum začátku.');

define('OnDays', 'Ve dnech');
define('CancelRecurrence', 'Zrušit opakování');
define('RepeatEvent', 'Opakovat tuto událost');

define('Spellcheck', 'Kontrola pravopisu');
define('LoginLanguage', 'Jazyk');
define('LanguageDefault', 'Defaultní');

// webmail 4.5.x new
define('EmptySpam', 'Vymazat spamy');
define('Saving', 'Ukládám&hellip;');
define('Sending', 'Odesílám&hellip;');
define('LoggingOffFromServer', 'Odhlašování ze serveru&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Nelze označit jako spam.');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Nelze označit že není spam.');
define('ExportToICalendar', 'Eport do iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Uživatel nemůže být vytvořen, protože je dosažen maximální počet v licenci.');
define('RepliedMessageTitle', 'Odpověďi');
define('ForwardedMessageTitle', 'Přeposláno');
define('RepliedForwardedMessageTitle', 'Odpovědět a přposlat zprávu');
define('ErrorDomainExist', 'uživatel nemůže být vytvořen, protože příslušná doména neexistuje. Zadejte nejdříve doménu.');

// webmail 4.7
define('RequestReadConfirmation', 'Potvrdit přečtení');
define('FolderTypeDefault', 'Defaultní');
define('ShowFoldersMapping', 'Dovolte mi použít jinou složku jako systémovou složku (např. použít MojeSložka jako Odeslaná pošta)');
define('ShowFoldersMappingNote', 'Například pro změnu "Odeslaná pošta" do "Moje složka" zadejte "Odeslaná posšta" v "Používá se pro", rozbalit a vybrat "Moje složka".');
define('FolderTypeMapTo', 'Použít pro');

define('ReminderEmailExplanation', 'Tato zpráva přišla na váš účet %EMAIL% proto, že jste si objednali upozornění na událost v kalendáři: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Otevřít kalendář');

define('AddReminder', 'Upozornit mě na tuto událost');
define('AddReminderBefore', 'Upozornit mě % před touto událostí');
define('AddReminderAnd', 'a % před');
define('AddReminderAlso', 'a také % před');
define('AddMoreReminder', 'Více připomínek');
define('RemoveAllReminders', 'Odebrat všechny připomínky');
define('ReminderNone', 'Žádný');
define('ReminderMinutes', 'minuty');
define('ReminderHour', 'hodina');
define('ReminderHours', 'hodiny');
define('ReminderDay', 'den');
define('ReminderDays', 'dny');
define('ReminderWeek', 'týden');
define('ReminderWeeks', 'týdny');
define('Allday', 'Všechny dny');

define('Folders', 'Složky');
define('NoSubject', 'Žádný předmět');
define('SearchResultsFor', 'Výsledky hledání');

define('Back', 'Zpět');
define('Next', 'Další');
define('Prev', 'Předchozí');

define('MsgList', 'Zprávy');
define('Use24HTimeFormat', 'Použít 24h formát');
define('UseCalendars', 'Použít kalendáře');
define('Event', 'Událost');
define('CalendarSettingsNullLine', 'Žádné kalendáře');
define('CalendarEventNullLine', 'Žádná událost');
define('ChangeAccount', 'Změnit účet');

define('TitleCalendar', 'Kalendář');
define('TitleEvent', 'Událost');
define('TitleFolders', 'Složky');
define('TitleConfirmation', 'Potvrzení');

define('Yes', 'Ano');
define('No', 'Ne');

define('EditMessage', 'Upravit zprávu');

define('AccountNewPassword', 'Nové heslo');
define('AccountConfirmNewPassword', 'Potvrdit nové heslo');
define('AccountPasswordsDoNotMatch', 'hesla se neshodují.');

define('ContactTitle', 'Titul');
define('ContactFirstName', 'Jméno');
define('ContactSurName', 'Příjmení');

define('ContactNickName', 'Přezdívka');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'obnovit');
define('CaptchaError', 'Captcha text je špatně.');

define('WarningInputCorrectEmails', 'Prosím zadejte správně e-mail.');
define('WrongEmails', 'Špatný e-mail:');

define('ConfirmBodySize1', 'Omlouváme se, ale textové zprávy s maximálně');
define('ConfirmBodySize2', 'znaky. Vše mimo hranice bude zkráceno. Klepněte na tlačítko "Zavřít", pokud chcete zprávu upravit.');
define('BodySizeCounter', 'Kontrola');
define('InsertImage', 'Vložit obrázek');
define('ImagePath', 'Cesta k obrázku');
define('ImageUpload', 'Vložit');
define('WarningImageUpload', 'Soubor není obrázek. Vyberte jiný soubor, který je obrázek.');

define('ConfirmExitFromNewMessage', 'Opustíte-li tuto stránku bez uložení, ztratíte tak všechny provedené změny. Klepnutím na tlačítko Storno zůstanete na aktuální stránce.');

define('SensivityConfidential', 'Zacházejte s touto zprávou jak s důvěrnou.');
define('SensivityPrivate', 'Zacházejte s touto zprávou jako se soukromou.');
define('SensivityPersonal', 'Zacházejte s touto zprávou jako s osobní');

define('ReturnReceiptTopText', 'Odesílatel této zprávy žádná o odeslání potvrzení o přečtení.');
define('ReturnReceiptTopLink', 'Klkněte zde pro odeslání potvrzení.');
define('ReturnReceiptSubject', 'Vrátit doručenku (zobrazení)');
define('ReturnReceiptMailText1', 'Jedná se o doručenku na e-mail ');
define('ReturnReceiptMailText2', 'Poznámka: Tato doručenka bere pouze na vědomí, že zpráva byla zobrazena příjemcem. Neexistuje žádná záruka, že příjemce zprávu přečetl a nebo pochopil obsah zprávy.');
define('ReturnReceiptMailText3', 's předmětem');

define('SensivityMenu', 'Důležitost');
define('SensivityNothingMenu', 'Nízká');
define('SensivityConfidentialMenu', 'Důvěrná');
define('SensivityPrivateMenu', 'Soukromá');
define('SensivityPersonalMenu', 'Osobní');

define('ErrorLDAPonnect', 'Nelze se připojit k LDAP serveru');

define('MessageSizeExceedsAccountQuota', 'Tato zpráva přesahuje velikost.');
define('MessageCannotSent', 'Zpráva nemůže být odeslána.');
define('MessageCannotSaved', 'Zpráva nemůže být uložena.');

define('ContactFieldTitle', 'Pole');
define('ContactDropDownTO', 'Komu');
define('ContactDropDownCC', 'Kopie');
define('ContactDropDownBCC', 'Skrytá kopie');

// 4.9
define('NoMoveDelete', 'Zpráva/zprávy nemohou být přesunuty do koše. Koš je nejspíše plný.');

define('WarningFieldBlank', 'Toto pole nemůže být prázdné.');
define('WarningPassNotMatch', 'Hesla se neshodují.');
define('PasswordResetTitle', 'Obnovení hesla - krok %d');
define('NullUserNameonReset', 'uživatel');
define('IndexResetLink', 'Zapomněli jste heslo?');
define('IndexRegLink', 'Registrace účtu');

define('RegDomainNotExist', 'Doména neexistuje.');
define('RegAnswersIncorrect', 'Odpovědi nejsou správné.');
define('RegUnknownAdress', 'Neznámá e-mailová adresa.');
define('RegUnrecoverableAccount', 'Obnovení hesla nelze použít pro tuto adresu.');
define('RegAccountExist', 'Tato adresa se již používá.');
define('RegRegistrationTitle', 'Registrace');
define('RegName', 'Jméno');
define('RegEmail', 'e-mailová adresa');
define('RegEmailDesc', 'Například myname@domain.com. Tyto informace budou použity pro přihlášení do systému.');
define('RegSignMe', 'Pamatovat si mě');
define('RegSignMeDesc', 'Neptat se na login a heslo do dalšího přihlášení do systému na PC.');
define('RegPass1', 'Heslo');
define('RegPass2', 'Heslo znovu ');
define('RegQuestionDesc', 'Neptat se na jméno a heslo do salšího přihlášení do systému na PC.');
define('RegQuestion1', 'Bezpečnostní otázka 1');
define('RegAnswer1', 'Odpověď 1');
define('RegQuestion2', 'Bezpečnostní otázka 2');
define('RegAnswer2', 'Odpověď 2');
define('RegTimeZone', 'Časová zóna');
define('RegLang', 'Jazyk rozhraní');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Registrovat');

define('ResetEmail', 'Uveďte svůj e-mail');
define('ResetEmailDesc', 'Poskytnout e-mailovou adresu použitou při registraci.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Odeslat');
define('ResetQuestion1', 'Bezpečnostní otázka 1');
define('ResetAnswer1', 'Odpověď');
define('ResetQuestion2', 'Bezpečnostní otázka 2');
define('ResetAnswer2', 'Odpověď');
define('ResetSubmitStep2', 'Odeslat');

define('ResetTopDesc1Step2', 'Uveďte svůj e-mail');
define('ResetTopDesc2Step2', 'Prosíme o potvrzení správnosti.');

define('ResetTopDescStep3', 'prosím uveďte nové heslo do e-mailu.');

define('ResetPass1', 'Nové heslo');
define('ResetPass2', 'Znovu heslo');
define('ResetSubmitStep3', 'Odeslat');
define('ResetDescStep4', 'Vaše heslo bylo změněno.');
define('ResetSubmitStep4', 'Návrat');

define('RegReturnLink', 'Návrat na přihlašovací stránku.');
define('ResetReturnLink', 'Návrat na přihlašovací stránku.');

// Appointments
define('AppointmentAddGuests', 'Přidat hosta');
define('AppointmentRemoveGuests', 'Zrušit schůzku');
define('AppointmentListEmails', 'Zadejte e-mailové adresy oddělené čárkami a stiskněte tlačítko "Uložit".');
define('AppointmentParticipants', 'Účastníci');
define('AppointmentRefused', 'Odmítnutí');
define('AppointmentAwaitingResponse', 'Čekající na odpověď');
define('AppointmentInvalidGuestEmail', 'Tyto adresy jsou neplatné:');
define('AppointmentOwner', 'Vlastník');

define('AppointmentMsgTitleInvite', 'Pozvat n audálost');
define('AppointmentMsgTitleUpdate', 'Událost byla upravena.');
define('AppointmentMsgTitleCancel', 'událost byla zrušena.');
define('AppointmentMsgTitleRefuse', 'Host %guest% odmítl pozvání.');
define('AppointmentMoreInfo', 'Dalčí informace');
define('AppointmentOrganizer', 'Organizér');
define('AppointmentEventInformation', 'Informace o události');
define('AppointmentEventWhen', 'Kdy');
define('AppointmentEventParticipants', 'Účastníci');
define('AppointmentEventDescription', 'Popis');
define('AppointmentEventWillYou', 'Budete se účastnit');
define('AppointmentAdditionalParameters', 'Další parametry');
define('AppointmentHaventRespond', 'Ještě neodpověděl');
define('AppointmentRespondYes', 'Budu se účastnit');
define('AppointmentRespondMaybe', 'Nezávazně');
define('AppointmentRespondNo', 'Nebudu se účastnit');
define('AppointmentGuestsChangeEvent', 'Hosté mohou změnit událost');

define('AppointmentSubjectAddStart', 'Dostali jste poszvání na událost ');
define('AppointmentSubjectAddFrom', ' od ');
define('AppointmentSubjectUpdateStart', 'Úprava události ');
define('AppointmentSubjectDeleteStart', 'Zrušení události ');
define('ErrorAppointmentChangeRespond', 'Nelze změnit jmenované reakce');
define('SettingsAutoAddInvitation', 'Přidat pozvánky do kalendáře automaticky');
define('ReportEventSaved', 'Vaše událost byla uložena');
define('ReportAppointmentSaved', ' a oznámení bylo odesláno');
define('ErrorAppointmentSend', 'Nelze odeslat pozvánku.');
define('AppointmentEventName', 'Jméno:');

// End appointments

define('ErrorCantUpdateFilters', 'Nelze aktualizovat filter.');

define('FilterPhrase', 'Pokud se %field hlavička %condition %string potom %action');
define('FiltersAdd', 'Přidat filter');
define('FiltersCondEqualTo', 've výši');
define('FiltersCondContainSubstr', 'obshaující řetězec');
define('FiltersCondNotContainSubstr', 'neobsahující řetězec');
define('FiltersActionDelete', 'vamazat zprávu');
define('FiltersActionMove', 'přesunout');
define('FiltersActionToFolder', 'do %folder složky');
define('FiltersNo', 'Nejsou definovány žádné filtry.');

define('ReminderEmailFriendly', 'upomínka');
define('ReminderEventBegin', 'začíná na: ');

define('FiltersLoading', 'Načítám filtry...');
define('ConfirmMessagesPermanentlyDeleted', 'Všechny zprávy v této složce budou trvale odstraněny.');

define('InfoNoNewMessages', 'Nejsou žádné nové zprávy.');
define('TitleImportContacts', 'Import kontaktů');
define('TitleSelectedContacts', 'Vybrat kontakty');
define('TitleNewContact', 'Nový kontakt');
define('TitleViewContact', 'Zobrazit kontakt');
define('TitleEditContact', 'Upravit kontakt');
define('TitleNewGroup', 'Nová skupina');
define('TitleViewGroup', 'Zobrazit skupinu');

define('AttachmentComplete', 'Kompletní.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Kontrolovat poštu každých');
define('AutoCheckMailIntervalDisableName', 'Vypnuto');

define('ReportCalendarSaved', 'Kalendář byl uložen');

define('ContactSyncError', 'Sync - chyba');
define('ReportContactSyncDone', 'Sync kopletní');

define('MobileSyncUrlTitle', 'Mobile sync URL');
define('MobileSyncLoginTitle', 'Mobile sync jméno');

define('QuickReply', 'Rychlá odpověď');
define('SwitchToFullForm', 'Otevřít úplnou odpověď formou');
define('SortFieldDate', 'Datum');
define('SortFieldFrom', 'Od');
define('SortFieldSize', 'Velikost');
define('SortFieldSubject', 'Předmět');
define('SortFieldFlag', 'Praporek');
define('SortFieldAttachments', 'Přílohy');
define('SortOrderAscending', 'Vzestupně');
define('SortOrderDescending', 'Sestupně');
define('ArrangedBy', 'Dohodl');

define('MessagePaneToRight', 'Zprávy jsou v panelu napravo od seznamu.');

define('SettingsTabMobileSync', 'Mobile');

define('MobileSyncContactDataBaseTitle', 'Mobile sync databáze kontaktů');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync databáze kalendáře');
define('MobileSyncTitleText', 'Pokud chcete synchronizovat s podporou SyncML mobilní zařízení s Webmailem, použijte tyto Mobile Sync URL.');
define('MobileSyncEnableLabel', 'Aktivovat mobile sync');

define('SearchInputText', 'hledat');

define('AppointmentEmailExplanation','tato zpráva přišla do e-mailového účtu %EMAIL% protože jste byl pozván na událost %ORGANAZER%');

define('Searching', 'Hledání&hellip;');

define('ButtonSetupSpecialFolders', 'nastavení speciálních složek');
define('ButtonSaveChanges', 'Uložit změny');
define('InfoPreDefinedFolders', 'U předem definovaných složek, tyto IMAP schránky');

define('SaveMailInSentItems', 'Uložit v Odeslaná pošta');

define('CouldNotSaveUploadedFile', 'Nelze uložit nahraný soubor.');

define('AccountOldPassword', 'Aktuální heslo');
define('AccountOldPasswordsDoNotMatch', 'Aktuální hesla se neshodují.');

define('DefEditor', 'Defaultní editor');
define('DefEditorRichText', 'Formátovaný text');
define('DefEditorPlainText', 'Prostý text');

define('Layout', 'Rozvržení');

define('TitleNewMessagesCount', '%count% nová zpráva');

define('AltOpenInNewWindow', 'Otevřít v novém oně');

define('SearchByFirstCharAll', 'Vše');

define('FolderNoUsageAssigned', 'Není přiřazeno žádné použití');

define('InfoSetupSpecialFolders', 'Chcete-li přiřadit zvláštní složky (jako Odeslaná pošta) a některé IMAP schránky, klepněte na tlačítko Nastavení speciálních složek.');

define('FileUploaderClickToAttach', 'Kliknutím přidáte přílohu');
define('FileUploaderOrDragNDrop', 'Souborů můžete vybrat i více najednou.');

define('AutoCheckMailInterval1Minute', '1 minuta');
define('AutoCheckMailInterval3Minutes', '3 minuty');
define('AutoCheckMailInterval5Minutes', '5 minut');
define('AutoCheckMailIntervalMinutes', 'minuty');

define('ReadAboutCSVLink', 'Pouze CSV soubory');

define('VoiceMessageSubj', 'Hlasové zprávy');
define('VoiceMessageTranscription', 'Přepis');
define('VoiceMessageReceived', 'Přijmout');
define('VoiceMessageDownload', 'Stáhnout');
define('VoiceMessageUpgradeFlashPlayer', 'Musíte aktualizovat svůj Adobe Flash Player pro přehrávání hlasových zpráv.<br />Aktualizujte Flash Player 10 z <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'Tento licenční klíč je zastaralý, prosím kontaktujte nás na aktualizaci svého licenčního klíče.');
define('LicenseProblem', 'Licenční problém. Správce systému by měl jít do Admin panelu pro kontrolu detailů.');

define('AccountOldPasswordNotCorrect', 'Aktuální heslo není správné');
define('AccountNewPasswordUpdateError', 'Nelze uložit nové heslo.');
define('AccountNewPasswordRejected', 'Nelze uložit nové heslo. Možná je příliš jednoduché.');

define('CantCreateIdentity', 'Nelze vytvořit identifikaci');
define('CantUpdateIdentity', 'Nelze aktualizovat identifikaci');
define('CantDeleteIdentity', 'Nelze vymazat identifikaci');

define('AddIdentity', 'Přidat identifikaci');
define('SettingsTabIdentities', 'Identifikace');
define('NoIdentities', 'Bez identifikaze');
define('NoSignature', 'Bez podpisu');
define('Account', 'Účet');
define('TabChangePassword', 'Heslo');
define('SignatureEnteringHere', 'Vložte svůj podpis zde');

define('CantConnectToMailServer', 'Nelze připojit k serveru');

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
