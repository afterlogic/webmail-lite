<?php
define('PROC_ERROR_ACCT_CREATE', 'Hiba a felhasználói fiók létrehozása közben!');
define('PROC_WRONG_ACCT_PWD', 'Hibás jelszó');
define('PROC_CANT_LOG_NONDEF', 'Nem lehetséges a belépés a nem szabványos fiókba');
define('PROC_CANT_INS_NEW_FILTER', 'Nem lehet új szűrőt létrehozni');
define('PROC_FOLDER_EXIST', 'A könyvtár már létezik');
define('PROC_CANT_CREATE_FLD', 'Nem lehet könyvtárat létrehozni');
define('PROC_CANT_INS_NEW_GROUP', 'Nem lehet új csoportot létrehozni');
define('PROC_CANT_INS_NEW_CONT', 'Nem lehet új névjegyet létrehozni');
define('PROC_CANT_INS_NEW_CONTS', 'Nem lehet új névjegye(ke)t létrehozni');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Nem lehet a névjegyeket a csoportba helyezni');
define('PROC_ERROR_ACCT_UPDATE', 'Hiba a fiók frissítése közben');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Nem lehet frissíteni a névjegyek beállításait');
define('PROC_CANT_GET_SETTINGS', 'Nem lehet a beállításokat lekérni');
define('PROC_CANT_UPDATE_ACCT', 'Nem lehet frissíteni a fiókot');
define('PROC_ERROR_DEL_FLD', 'Hiba a mappa(ák) törlése közben');
define('PROC_CANT_UPDATE_CONT', 'Nem lehet frissíteni a névjegyet');
define('PROC_CANT_GET_FLDS', 'Nem lehet kiolvasni a mappák listáját');
define('PROC_CANT_GET_MSG_LIST', 'Nem lehet lekérni az üzenetek listáját');
define('PROC_MSG_HAS_DELETED', 'Ez az üzenet már törölve lett a szerverről');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Nem lehet betölteni a névjegyek beállítását');
define('PROC_CANT_LOAD_SIGNATURE', 'Nem lehet betölteni az aláírást');
define('PROC_CANT_GET_CONT_FROM_DB', 'Nem lehet lekérdezi a névjegyet az adatbázisból');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Nem lehet lekérdezni a névjegye(ke)t az adatbázisból');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Nem lehet törölni a fiókot');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Nem lehet törölni a szűrőt');
define('PROC_CANT_DEL_CONT_GROUPS', 'Nem lehet törölni a névjegye(ke)t és/vagy a csoportokat');
define('PROC_WRONG_ACCT_ACCESS', 'Azonosítatlan hozzáféési kísérlet észlelve a fiókhoz.');
define('PROC_SESSION_ERROR', 'Az előző munkamenet megszakítva időtúllépés miatt.');

define('MailBoxIsFull', 'A postafiók megtelt.');
define('WebMailException', 'WebMail kivételes hiba történt');
define('InvalidUid', 'Érvénytelen üzenet azonosító');
define('CantCreateContactGroup', 'Nem lehet létrehozni a csoportot');
define('CantCreateUser', 'Nem lehet a felhasználót létrehozni');
define('CantCreateAccount', 'Nem lehet a fiókot létrehozni');
define('SessionIsEmpty', 'A munkamenet üres');
define('FileIsTooBig', 'Túl nagy méretű fájl');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Nem lehet az összes üzenetet megjelölni olvasottként');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Nem lehet az összes üzenetet megjelölni olvasatlanként');
define('PROC_CANT_PURGE_MSGS', 'Nem lehet véglegesen törölni az üzenete(ke)t');
define('PROC_CANT_DEL_MSGS', 'Nem lehet törölni az üzenete(ke)t');
define('PROC_CANT_UNDEL_MSGS', 'Nem lehet visszavonni a törlését az üzenet(ek)nek');
define('PROC_CANT_MARK_MSGS_READ', 'Nem lehet megjelölni ovasottként az üzenete(ke)t');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Nem lehet megjelölni olvasatlanként az üzenete(ke)t');
define('PROC_CANT_SET_MSG_FLAGS', 'Nem lehet beállítani az üzenethez megjelölést');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Nem lehet eltávolítani az üzenethez a megjelölést');
define('PROC_CANT_CHANGE_MSG_FLD', 'Nem lehet az mappát váltani az üzenet(ek)hez');
define('PROC_CANT_SEND_MSG', 'Nem lehet elküldeni az üzenetet.');
define('PROC_CANT_SAVE_MSG', 'Nem lehet elmenteni az üzenetet');
define('PROC_CANT_GET_ACCT_LIST', 'Nem lehet lekérni a mappák listáját');
define('PROC_CANT_GET_FILTER_LIST', 'Nem lehet lekérni a szűrők listáját');

define('PROC_CANT_LEAVE_BLANK', 'Nem hagyhatja üresen a *-al jelölt mezőket');

define('PROC_CANT_UPD_FLD', 'Nem lehet frissíteni a mappát');
define('PROC_CANT_UPD_FILTER', 'Nem lehet frissíteni a szűrőt');

define('ACCT_CANT_ADD_DEF_ACCT', 'Nem lehet hozzáadni ezt a fiókot, mert másik felhasználhó már használja alapértelmezettként.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Nem lehet beállítani a fiókot alapértelmezettnek.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Nem lehet új fiókot létrehozni (IMAP4 kapcsolódási hiba)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Nem lehet törölni az utolsó alapértelmezett fiókot');

define('LANG_LoginInfo', 'Belépési információk');
define('LANG_Email', 'E-mail cím');
define('LANG_Login', 'Postafiók');
define('LANG_Password', 'Jelszó');
define('LANG_IncServer', 'Bejövő Üzenet');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'SMTP Szerver');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'SMTP hitelesítés használata');
define('LANG_SignMe', 'Automatikus beléptetés');
define('LANG_Enter', 'Belépés');

define('JS_LANG_TitleLogin', 'Belépés');
define('JS_LANG_TitleMessagesListView', 'Üzenetek listája');
define('JS_LANG_TitleMessagesList', 'Üzenetek listája');
define('JS_LANG_TitleViewMessage', 'Üzenet megtekintése');
define('JS_LANG_TitleNewMessage', 'Új üzenet');
define('JS_LANG_TitleSettings', 'Beállítások');
define('JS_LANG_TitleContacts', 'Címjegyzék');

define('JS_LANG_StandardLogin', 'Egyszerűsített&nbsp;Belépés');
define('JS_LANG_AdvancedLogin', 'Bővített&nbsp;Belépés');

define('JS_LANG_InfoWebMailLoading', 'Kérem várjon amíg a WebMail töltődik&hellip;');
define('JS_LANG_Loading', 'Töltés&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Kérjem várjon amíg a WebMail az üzenetek listáját tölti');
define('JS_LANG_InfoEmptyFolder', 'A mappa üres');
define('JS_LANG_InfoPageLoading', 'Az oldal még töltődik&hellip;');
define('JS_LANG_InfoSendMessage', 'Az üzenet elküldve');
define('JS_LANG_InfoSaveMessage', 'Az üzenet elmentve');
// You have imported 3 new contact(s) into your contacts list.
define('JS_LANG_InfoHaveImported', 'Ön ');
define('JS_LANG_InfoNewContacts', 'új névjegyet sikeresen importált.');
define('JS_LANG_InfoToDelete', 'A(z) ');
define('JS_LANG_InfoDeleteContent', 'mappa törléséhez először törölnie kell a teljes tartalmát.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Nem üres mappák törlése nem lehetséges. A törléshez először ürítse a tartalmukat.');
define('JS_LANG_InfoRequiredFields', '* kötelező mezők');

define('JS_LANG_ConfirmAreYouSure', 'Biztos benne?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'A kijelölt üzenetek VÉGLEGESEN törlődnek! Biztos benne?');
define('JS_LANG_ConfirmSaveSettings', 'A beállítások még nem kerültek mentésre. Kattintson az OK-ra a mentéshez.');
define('JS_LANG_ConfirmSaveContactsSettings', 'A névjegy beállítások még nem kerültek mentésre. Kattintson az OK-ra a mentéshez.');
define('JS_LANG_ConfirmSaveAcctProp', 'A fiók tulajdonságai még nem kerültek mentésre. Kattintson az OK-ra a mentéshez.');
define('JS_LANG_ConfirmSaveFilter', 'A szűrő tulajdonságai még nem merültek mentésre. Kattintson az OK-ra a mentéshez.');
define('JS_LANG_ConfirmSaveSignature', 'Az aláírás nem lett elmentve. Kattintson az OK-ra a mentéshez.');
define('JS_LANG_ConfirmSavefolders', 'A mappák nem lettek elmentve. Kattintson az OK-ra a mentéshez.');
define('JS_LANG_ConfirmHtmlToPlain', 'Figyelmeztetés: azzal, hogy megváltoztatja a formátumát az üzenetnek HTML-ről sima szövegre, az formátum elveszik. Kattintson az OK-ra a folytatáshoz.');
define('JS_LANG_ConfirmAddFolder', 'Mielőtt mappát adna hozzá a változásokat mentenie kell. Kattintson az OK-ra a mentéshez.');
define('JS_LANG_ConfirmEmptySubject', 'A tárgy mező üres, biztosan folytatja?');

define('JS_LANG_WarningEmailBlank', 'Nem hagyhatja az<br />E-mail cím: mezőt üresen');
define('JS_LANG_WarningLoginBlank', 'Nem hagyhatja a<br />Postafiók: mezőt üresen');
define('JS_LANG_WarningToBlank', 'Nem hagyhatja a Címzett: mezőt üresen');
define('JS_LANG_WarningServerPortBlank', 'Nem hagyhatja a POP3<br />SMTP szerver/port mezőket üresen');
define('JS_LANG_WarningEmptySearchLine', 'Üres keresés. Kérjük adja meg a keresett szöveget');
define('JS_LANG_WarningMarkListItem', 'Kérjük jelöljön meg legalább egy üzenetet a listában');
define('JS_LANG_WarningFolderMove', 'A mappa nem helyezhető át, mert más szinten van');
define('JS_LANG_WarningContactNotComplete', 'Kérjük adjon meg e-mail címet vagy nevet');
define('JS_LANG_WarningGroupNotComplete', 'Kérjük adjon meg egy csoportnevet');

define('JS_LANG_WarningEmailFieldBlank', 'Nem hagyhatja üresen az E-mail mezőt');
define('JS_LANG_WarningIncServerBlank', 'Nem hagyhatja üresen a POP3(IMAP4) Szerver mezőt');
define('JS_LANG_WarningIncPortBlank', 'Nem hagyhatja üresen a POP3(IMAP4) Szerver Port mezőt');
define('JS_LANG_WarningIncLoginBlank', 'Nem hagyhatja üresen a POP3(IMAP4) Azonosító mezőt');
define('JS_LANG_WarningIncPortNumber', 'Kérjük adjon meg pozitív számot a POP3(IMAP4) port mezőben.');
define('JS_LANG_DefaultIncPortNumber', 'Alapértelmezett POP3(IMAP4) portszám a 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Nem hagyhatja üresen a POP3(IMAP4) Jelszó mezőt');
define('JS_LANG_WarningOutPortBlank', 'Nem hagyhatja üresen a SMTP Szerver Port mezőt');
define('JS_LANG_WarningOutPortNumber', 'Kérjük adjon meg pozitív számot az SMTP port mezőben.');
define('JS_LANG_WarningCorrectEmail', 'Kérjük adjon meg valós e-mail címet.');
define('JS_LANG_DefaultOutPortNumber', 'Az alapértelmezett SMTP port a 25.');

define('JS_LANG_WarningCsvExtention', 'A kiterjesztésnek .csv-nek kell lennie');
define('JS_LANG_WarningImportFileType', 'Kérjük válassza ki azt az alkalmazást ahonnan az adatokat importálni szeretné');
define('JS_LANG_WarningEmptyImportFile', 'Kérjük válassza ki a fájt a Tallózás gombra kattintva');

define('JS_LANG_WarningContactsPerPage', 'A névjegyek száma oldalanként értékének pozitívnak kell lennie');
define('JS_LANG_WarningMessagesPerPage', 'Az üzenetek száma oldalanként értékének pozitívnak kell lennie');
define('JS_LANG_WarningMailsOnServerDays', 'Kérjük adjon meg pozitív számot az Üzenetek tárolása a szervere mezőben.');
define('JS_LANG_WarningEmptyFilter', 'Adjon meg egy karatkerláncot');
define('JS_LANG_WarningEmptyFolderName', 'Adjon meg a mappa nevét');

define('JS_LANG_ErrorConnectionFailed', 'Sikertelen kapcsolódás');
define('JS_LANG_ErrorRequestFailed', 'Az adatok lekérése sikertelen');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Az XMLHttpRequest objektum hibás');
define('JS_LANG_ErrorWithoutDesc', 'Leírás nélküli hiba történt');
define('JS_LANG_ErrorParsing', 'Hiba az XML fájl olvasása közben.');
define('JS_LANG_ResponseText', 'Válasz szöveg:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Üres XML csomag');
define('JS_LANG_ErrorImportContacts', 'Hiba a névjegyek importálása közben');
define('JS_LANG_ErrorNoContacts', 'Nincs importálandó névjegy.');
define('JS_LANG_ErrorCheckMail', 'Az üzenetek fogadása hiba miatt megszakadt. Lehetséges, hogy nem minden üzenet lett lekérve.');

define('JS_LANG_LoggingToServer', 'Kapcsolódás a szerverhez&hellip;');
define('JS_LANG_GettingMsgsNum', 'Az üzenetek számának lekérése');
define('JS_LANG_RetrievingMessage', 'Üzenetek fogadása');
define('JS_LANG_DeletingMessage', 'Üzenet törlése');
define('JS_LANG_DeletingMessages', 'Üzenet(ek) törlése');
define('JS_LANG_Of', '');
define('JS_LANG_Connection', 'Kapcsolat');
define('JS_LANG_Charset', 'Karakterkészlet');
define('JS_LANG_AutoSelect', 'Automatikus választás');

define('JS_LANG_Contacts', 'Címjegyzék');
define('JS_LANG_ClassicVersion', 'Klasszikus nézet');
define('JS_LANG_Logout', 'Kilépés');
define('JS_LANG_Settings', 'Beállítások');

define('JS_LANG_LookFor', 'Keresés');
define('JS_LANG_SearchIn', 'Keresés');
define('JS_LANG_QuickSearch', 'Csak a Feladó, Címzett, Tárgy mezőkben keressen (gyorsabb).');
define('JS_LANG_SlowSearch', 'Keresés a teljes üzenetben');
define('JS_LANG_AllMailFolders', 'Össszes mappa');
define('JS_LANG_AllGroups', 'Összes csoport');

define('JS_LANG_NewMessage', 'Új üzenet');
define('JS_LANG_CheckMail', 'Fogadás');
define('JS_LANG_EmptyTrash', 'Szemetesláda ürítése');
define('JS_LANG_MarkAsRead', 'Megjelöl olvasottként');
define('JS_LANG_MarkAsUnread', 'Megjelöl olvasatlanként');
define('JS_LANG_MarkFlag', 'Megjelöl');
define('JS_LANG_MarkUnflag', 'Megjelölés törlése');
define('JS_LANG_MarkAllRead', 'Az összes megjelölése olvasottként');
define('JS_LANG_MarkAllUnread', 'Az összes megjelölése olvasatlanként');
define('JS_LANG_Reply', 'Válasz');
define('JS_LANG_ReplyAll', 'Válasz mindenkinek');
define('JS_LANG_Delete', 'Törlés');
define('JS_LANG_Undelete', 'Visszaállítás');
define('JS_LANG_PurgeDeleted', 'A törölt üzenetek megsemmisítése');
define('JS_LANG_MoveToFolder', 'Mozgatás másik mappába');
define('JS_LANG_Forward', 'Továbbítás');

define('JS_LANG_HideFolders', 'Mappák elrejtése');
define('JS_LANG_ShowFolders', 'Mappák megjelenítése');
define('JS_LANG_ManageFolders', 'Mappák kezelése');
define('JS_LANG_SyncFolder', 'Szinkronizált mappa');
define('JS_LANG_NewMessages', 'Új üzenetek');
define('JS_LANG_Messages', 'Üzenet(ek)');

define('JS_LANG_From', 'Feladó');
define('JS_LANG_To', 'Címzett');
define('JS_LANG_Date', 'Dátum');
define('JS_LANG_Size', 'Méret');
define('JS_LANG_Subject', 'Tárgy');

define('JS_LANG_FirstPage', 'Első oldal');
define('JS_LANG_PreviousPage', 'Előző oldal');
define('JS_LANG_NextPage', 'Következő oldal');
define('JS_LANG_LastPage', 'Utolsó oldal');

define('JS_LANG_SwitchToPlain', 'Váltás sima szövegre');
define('JS_LANG_SwitchToHTML', 'Váltás HTML-re');
define('JS_LANG_AddToAddressBook', 'Hozzáadás a címjegyzékhez');
define('JS_LANG_ClickToDownload', 'Kattintson ide a letöltéshez');
define('JS_LANG_View', 'Megtekint');
define('JS_LANG_ShowFullHeaders', 'Üzenet fejléc megtekintése');
define('JS_LANG_HideFullHeaders', 'Üzenet fejléc elrejtése');

define('JS_LANG_MessagesInFolder', 'üzenet a mappában');
define('JS_LANG_YouUsing', 'Felhasznált adat: ');
define('JS_LANG_OfYour', ', a teljeses rendelkezésre állóból: ');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Elküld');
define('JS_LANG_SaveMessage', 'Mentés');
define('JS_LANG_Print', 'Nyomtatás');
define('JS_LANG_PreviousMsg', 'Előző üzenet');
define('JS_LANG_NextMsg', 'Következő üzenet');
define('JS_LANG_AddressBook', 'Címlista');
define('JS_LANG_ShowBCC', 'Titkos másolat megjelenítése');
define('JS_LANG_HideBCC', 'Titkos másolat elrejtése');
define('JS_LANG_CC', 'Másolatot kap');
define('JS_LANG_BCC', 'Titkos másolat');
define('JS_LANG_ReplyTo', 'Válasz mint');
define('JS_LANG_AttachFile', 'Fájl csatolása');
define('JS_LANG_Attach', 'Csatolás');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Eredeti üzenet');
define('JS_LANG_Sent', 'Elküldve');
define('JS_LANG_Fwd', 'Továbbítva');
define('JS_LANG_Low', 'Alacsony');
define('JS_LANG_Normal', 'Normál');
define('JS_LANG_High', 'Magas');
define('JS_LANG_Importance', 'Fontosság');
define('JS_LANG_Close', 'Bezár');

define('JS_LANG_Common', 'Általános');
define('JS_LANG_EmailAccounts', 'E-mail fiókok');

define('JS_LANG_MsgsPerPage', 'Üzenet oldalanként');
define('JS_LANG_DisableRTE', 'A szövegszerkesztő kikapcsolása');
define('JS_LANG_Skin', 'Téma');
define('JS_LANG_DefCharset', 'Alapértelmezett karakterkészlet');
define('JS_LANG_DefCharsetInc', 'Alapértelmezett karakterkészlet fogadásnál');
define('JS_LANG_DefCharsetOut', 'Alapértelmezett karakterkészlet küldésnél');
define('JS_LANG_DefTimeOffset', 'Alapértelmezett időzóna');
define('JS_LANG_DefLanguage', 'Alapértelmezett nyelv');
define('JS_LANG_DefDateFormat', 'Alapértelmezett dátum formátum');
define('JS_LANG_ShowViewPane', 'Betekintő nézet használata');
define('JS_LANG_Save', 'Mentés');
define('JS_LANG_Cancel', 'Mégsem');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Eltávolít');
define('JS_LANG_AddNewAccount', 'Új fiók létrehozása');
define('JS_LANG_Signature', 'Aláírás');
define('JS_LANG_Filters', 'Szűrők');
define('JS_LANG_Properties', 'Tulajdonságok');
define('JS_LANG_UseForLogin', 'Ennek a mappának a tulajdonságainak használata (felhasználónév és jelszó) belépéshez');
define('JS_LANG_MailFriendlyName', 'Az Ön neve');
define('JS_LANG_MailEmail', 'E-mail');
define('JS_LANG_MailIncHost', 'Beérkező üzenet');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Postafiók');
define('JS_LANG_MailIncPass', 'Jelszó');
define('JS_LANG_MailOutHost', 'SMTP Szerver');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP azonosító');
define('JS_LANG_MailOutPass', 'SMTP jelszó');
define('JS_LANG_MailOutAuth1', 'SMTP kiszolgáló hitelesítést igényel');
define('JS_LANG_MailOutAuth2', '(Az SMTP azonosító/jelszó mezőket üresen hagyhatja ha azok megegyeznek a POP3/IMAP4 beállításokkal)');
define('JS_LANG_UseFriendlyNm1', 'Felhasználóbarát megjelenés a "Feladó:" mezőben');
define('JS_LANG_UseFriendlyNm2', '(Az Ön Neve &lt;emailcim@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Levelek letöltése bejelentkezéskor');
define('JS_LANG_MailMode0', 'A letöltött üzenetek törlése a szerverről');
define('JS_LANG_MailMode1', 'Az üzenetek tárolása a szerveren');
define('JS_LANG_MailMode2', 'Az üzenetek megőrzése a szerveren');
define('JS_LANG_MailsOnServerDays', 'napig');
define('JS_LANG_MailMode3', 'Az üzenet törlése a szerverről amennyiben törölésre kerül a Lomtárból');
define('JS_LANG_InboxSyncType', 'A Beérkezett üzenetek mappa szinkronizálásának típusa');

define('JS_LANG_SyncTypeNo', 'Ne szinkronizáljon');
define('JS_LANG_SyncTypeNewHeaders', 'Új fejlécek');
define('JS_LANG_SyncTypeAllHeaders', 'Összes fejléc');
define('JS_LANG_SyncTypeNewMessages', 'Új üzenetek');
define('JS_LANG_SyncTypeAllMessages', 'Összes üzenet');
define('JS_LANG_SyncTypeDirectMode', 'Direkt mód');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Csak a fejléceket');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Teljes üzeneteket');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direkt mód');

define('JS_LANG_DeleteFromDb', 'Az üzenet törlése az adatbázisból amennyiben már nem létezik a szervern');

define('JS_LANG_EditFilter', 'Szűrő&nbsp;szerkesztése');
define('JS_LANG_NewFilter', 'Új szűrő létrehozása');
define('JS_LANG_Field', 'Mező');
define('JS_LANG_Condition', 'Feltétel');
define('JS_LANG_ContainSubstring', 'Szövegrészt tartalmaz');
define('JS_LANG_ContainExactPhrase', 'Pontos szövegrész');
define('JS_LANG_NotContainSubstring', 'Nem tartalmazza a szövegrészt');
define('JS_LANG_FilterDesc_At', '');
define('JS_LANG_FilterDesc_Field', 'mező');
define('JS_LANG_Action', 'Tevékenység');
define('JS_LANG_DoNothing', 'Ne csináljon semmit');
define('JS_LANG_DeleteFromServer', 'Azonall törölje a szerverről');
define('JS_LANG_MarkGrey', 'Jelenítse meg szürkén');
define('JS_LANG_Add', 'Hozzáadás');
define('JS_LANG_OtherFilterSettings', 'Egyéb szűrő beállítás');
define('JS_LANG_ConsiderXSpam', 'Vegye figyelembe az X-Spam fejlécet');
define('JS_LANG_Apply', 'Alkalmaz');

define('JS_LANG_InsertLink', 'Link beszúrása');
define('JS_LANG_RemoveLink', 'Link törlése');
define('JS_LANG_Numbering', 'Számozás');
define('JS_LANG_Bullets', 'Felsorolás');
define('JS_LANG_HorizontalLine', 'Vízszintes vonal');
define('JS_LANG_Bold', 'Félkövér');
define('JS_LANG_Italic', 'Dőlt');
define('JS_LANG_Underline', 'Aláhúzs');
define('JS_LANG_AlignLeft', 'Balra rendezett');
define('JS_LANG_Center', 'Középre rendezett');
define('JS_LANG_AlignRight', 'Jobbra rendezett');
define('JS_LANG_Justify', 'Sorkizárt');
define('JS_LANG_FontColor', 'Betű színe');
define('JS_LANG_Background', 'Háttér');
define('JS_LANG_SwitchToPlainMode', 'Normál szöveg nézetre váltás');
define('JS_LANG_SwitchToHTMLMode', 'HTML szövegre váltás');

define('JS_LANG_Folder', 'Mappa');
define('JS_LANG_Msgs', 'Üzenetek');
define('JS_LANG_Synchronize', 'Szinkronizálás');
define('JS_LANG_ShowThisFolder', 'A mappa megjelenítése');
define('JS_LANG_Total', 'Összes');
define('JS_LANG_DeleteSelected', 'Kijelöltek törlése');
define('JS_LANG_AddNewFolder', 'Új mappa hozzáadása');
define('JS_LANG_NewFolder', 'Új mappa');
define('JS_LANG_ParentFolder', 'Szülő mappa');
define('JS_LANG_NoParent', 'Nincs szülő');
define('JS_LANG_FolderName', 'Mappa neve');

define('JS_LANG_ContactsPerPage', 'Névjegyek oldalanként');
define('JS_LANG_WhiteList', 'Címjegyzék mint fehér-lista');

define('JS_LANG_CharsetDefault', 'Alapértelmezett');
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

define('JS_LANG_TimeDefault', 'Alapértelmezett');
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

define('JS_LANG_DateDefault', 'Alapértelmezett');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Jan)');
define('JS_LANG_DateAdvanced', 'Bővített');

define('JS_LANG_NewContact', 'Új névjegy');
define('JS_LANG_NewGroup', 'Új csoport');
define('JS_LANG_AddContactsTo', 'A névjegy hozzáadása');
define('JS_LANG_ImportContacts', 'Névjegyek importálása');

define('JS_LANG_Name', 'Név');
define('JS_LANG_Email', 'E-mail');
define('JS_LANG_DefaultEmail', 'Alapértelmezett e-mail');
define('JS_LANG_NotSpecifiedYet', 'Nem megadott');
define('JS_LANG_ContactName', 'Név');
define('JS_LANG_Birthday', 'Születésnap');
define('JS_LANG_Month', 'Hónap');
define('JS_LANG_January', 'Január');
define('JS_LANG_February', 'Február');
define('JS_LANG_March', 'Március');
define('JS_LANG_April', 'Április');
define('JS_LANG_May', 'Május');
define('JS_LANG_June', 'Június');
define('JS_LANG_July', 'Július');
define('JS_LANG_August', 'Augusztus');
define('JS_LANG_September', 'Szeptember');
define('JS_LANG_October', 'Október');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'December');
define('JS_LANG_Day', 'Nap');
define('JS_LANG_Year', 'Év');
define('JS_LANG_UseFriendlyName1', 'Olvasható név használata');
define('JS_LANG_UseFriendlyName2', '(például, John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Személyes');
define('JS_LANG_PersonalEmail', 'Személyes e-mail');
define('JS_LANG_StreetAddress', 'Utca');
define('JS_LANG_City', 'Város');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Megye/Tartomány');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'Irányítószám');
define('JS_LANG_Mobile', 'Mobil');
define('JS_LANG_CountryRegion', 'Ország/Régió');
define('JS_LANG_WebPage', 'Weboldal');
define('JS_LANG_Go', 'Ugrás');
define('JS_LANG_Home', 'Otthon');
define('JS_LANG_Business', 'Céges');
define('JS_LANG_BusinessEmail', 'Céges e-mail');
define('JS_LANG_Company', 'Cégnév');
define('JS_LANG_JobTitle', 'Beosztás');
define('JS_LANG_Department', 'Részegység');
define('JS_LANG_Office', 'Iroda');
define('JS_LANG_Pager', 'Személyhívó');
define('JS_LANG_Other', 'Egyéb');
define('JS_LANG_OtherEmail', 'Egyéb e-mail');
define('JS_LANG_Notes', 'Megjegyzések');
define('JS_LANG_Groups', 'Csoportok');
define('JS_LANG_ShowAddFields', 'További mezők megjelenítése');
define('JS_LANG_HideAddFields', 'További mezők elrejtése');
define('JS_LANG_EditContact', 'Névjegy szerkesztése');
define('JS_LANG_GroupName', 'Csoport neve');
define('JS_LANG_AddContacts', 'Névjegyek hozzáadása');
define('JS_LANG_CommentAddContacts', '(Ha több címet kíván megadni, használjon vesszőt az elválasztáshoz)');
define('JS_LANG_CreateGroup', 'Csoport hozzáadása');
define('JS_LANG_Rename', 'átnevezés');
define('JS_LANG_MailGroup', 'Levelező csoport');
define('JS_LANG_RemoveFromGroup', 'Eltávolitás a csoportból');
define('JS_LANG_UseImportTo', 'Az Importálás használata elősegíti, hogy a címjegyzékét áthozza Microsoft Outlook, Microsoft Outlook Express levelezőkből a WebMail címjegyzékébe.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Válassza ki a fájlt (.CSV formátumban) melyet importálni szeretne');
define('JS_LANG_Import', 'Importálás');
define('JS_LANG_ContactsMessage', 'Ez a névjegyek oldala!!!');
define('JS_LANG_ContactsCount', 'névjegy');
define('JS_LANG_GroupsCount', 'csoport');

// webmail 4.1 constants
define('PicturesBlocked', 'A képek ebben az üzenetben blokkolva vannak az Ön biztonsága érdekében.');
define('ShowPictures', 'Képek megjelenítése');
define('ShowPicturesFromSender', 'A képek megjelenítése minden esetben ettől a feladótól');
define('AlwaysShowPictures', 'A képek megjelenítése minden esetben');

define('TreatAsOrganization', 'Kezelés szervezetként');

define('WarningGroupAlreadyExist', 'Ilyen nevű csoport már létezik. Kérjük adjon meg más nevet.');
define('WarningCorrectFolderName', 'Kérjük adjon meg helyes mappa nevet.');
define('WarningLoginFieldBlank', 'Nem hagyhatja a Belépés mezőt üresen.');
define('WarningCorrectLogin', 'Kérjük töltse ki helyesen a Belépés mezőt.');
define('WarningPassBlank', 'Nem hagyhatja a Jelszó mezőt üresen.');
define('WarningCorrectIncServer', 'Kérjük adjon meg helyes POP3(IMAP) szerver címet.');
define('WarningCorrectSMTPServer', 'Kérjük adjon meg helyes SMTP szerver címet.');
define('WarningFromBlank', 'Nem hagyhatja a Feladó mezőt üresen.');
define('WarningAdvancedDateFormat', 'Kérjük adja meg a dátum/idő formátumát.');

define('AdvancedDateHelpTitle', 'Bővített dátum formátum');
define('AdvancedDateHelpIntro', 'Amikor a &quot;Bővített formátum&quot; mezőt kiválasztja, megadhatja a dátum megjelenítésének formátumát szabadon. A következő lehetőségeket használhatjah \':\' vagy \'/\' elválasztó karakterekkel:');
define('AdvancedDateHelpConclusion', 'Például ha ezt adja meg a szöveg mezőben &quot;mm/dd/yyyy&quot;, a dátum ebben a formában fog megjelenni: hónap/nap/év (pl.: 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'A hónap napja (1-től 31-ig)');
define('AdvancedDateHelpNumericMonth', 'Hónap (1-től 12-ig)');
define('AdvancedDateHelpTextualMonth', 'Hónap (Jan..Dec)');
define('AdvancedDateHelpYear2', 'Év, 2 számjegy');
define('AdvancedDateHelpYear4', 'Év, 4 számjegy');
define('AdvancedDateHelpDayOfYear', 'Az év napja (1-től 366-ig)');
define('AdvancedDateHelpQuarter', 'Negyedév');
define('AdvancedDateHelpDayOfWeek', 'A hét napja (Hét..Vas)');
define('AdvancedDateHelpWeekOfYear', 'Hét (1..53)');

define('InfoNoMessagesFound', 'Nincs új üzenet.');
define('ErrorSMTPConnect', 'Nem lehet csatlakozni az SMTP kiszolgálóhoz. Kérjük ellenőrizze a beállításokat.');
define('ErrorSMTPAuth', 'Hibás felhasználónév vagy jelszó. Sikertelen SMTP hitelesítés.');
define('ReportMessageSent', 'Az üzenet elküldve.');
define('ReportMessageSaved', 'Az üzenet elmentve.');
define('ErrorPOP3Connect', 'Sikertelen kapcsolódás a POP3 kiszolgálóhoz. Kérjük ellenőrizze a beállításokat.');
define('ErrorIMAP4Connect', 'Sikertelen kapcsolódás az IMAP4 kiszolgálóhoz. Kérjük ellenőrizze a beállításokat.');
define('ErrorPOP3IMAP4Auth', 'Hibás e-mail cím/postafiók és/vagy jelszó. Sikertelen belépés.');
define('ErrorGetMailLimit', 'A postafiókja megtelt.');

define('ReportSettingsUpdatedSuccessfuly', 'A beállítások sikeresen elmentve.');
define('ReportAccountCreatedSuccessfuly', 'A fiók sikeresen létrehozva.');
define('ReportAccountUpdatedSuccessfuly', 'A fiók sikeresen frissítve.');
define('ConfirmDeleteAccount', 'Biztosan törli a fiókot?');
define('ReportFiltersUpdatedSuccessfuly', 'A szűrő beállításai sikeresen elmentve.');
define('ReportSignatureUpdatedSuccessfuly', 'Az aláírás sikeresen elmentve.');
define('ReportFoldersUpdatedSuccessfuly', 'A mappa beállítások sikeresen elmentve.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Címjegyzék beállítások sikeresen elmentve.');

define('ErrorInvalidCSV', 'A kiválasztott CSV fájl formátuma hibás.');
// The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'A csoport');
define('ReportGroupSuccessfulyAdded2', 'sikeresen hozzáadva.');
define('ReportGroupUpdatedSuccessfuly', 'A csoport adatai sikeresen elmentve.');
define('ReportContactSuccessfulyAdded', 'A névjegy sikeresen hozzáadva.');
define('ReportContactUpdatedSuccessfuly', 'A névjegy változásai sikeresen elmentve.');
// Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'A névjegy hozzáadva a következő csoporthoz');
define('AlertNoContactsGroupsSelected', 'Nincs kiválasztva névjegy vagy csoport.');

define('InfoListNotContainAddress', 'Ha a lista nem tartalmazz a névjegyet amit keres, folytassa a kezdőbetűk begépelését');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direkt mód. WebMail direktben használja a szerver üzeneteket.');

define('FolderInbox', 'Beérkezett üzenetek');
define('FolderSentItems', 'Elküldött elemek');
define('FolderDrafts', 'Piszkozatok');
define('FolderTrash', 'Lomtár');

define('FileLargerAttachment', 'A csatolás mérete túl nagy.');
define('FilePartiallyUploaded', 'A csatolás csak egy része került feltöltésre hiba miatt.');
define('NoFileUploaded', 'A csatolás nem lett feltöltve.');
define('MissingTempFolder', 'Az átmeneti tároló könyvtár hiányzik.');
define('MissingTempFile', 'Az átmeneti fájl hiányzik.');
define('UnknownUploadError', 'Ismeretlen fájl feltöltési hiba.');
define('FileLargerThan', 'Fájl feltöltési hiba. Valószínű, hogy a fájl mérete nagyobb, mint ');
define('PROC_CANT_LOAD_DB', 'Nem lehet csatlakozni az adatbázishoz.');
define('PROC_CANT_LOAD_LANG', 'Nem létező nyelvi fájl.');
define('PROC_CANT_LOAD_ACCT', 'A fiók nem létezik, valószínűleg törölésre került.');

define('DomainDosntExist', 'Ilyen domain név nem létezik a szerveren.');
define('ServerIsDisable', 'A levelező kiszolgáló használatát az adminisztrátor megtiltotta.');

define('PROC_ACCOUNT_EXISTS', 'A fiók nem hozható létre, mert már létezik.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Nem lehet lekérdezni a mappában található üzenetek számát.');
define('PROC_CANT_MAIL_SIZE', 'Nem lehet lekérdezni a levél méretét.');

define('Organization', 'Szervezet');
define('WarningOutServerBlank', 'Nem hagyhatja az SMTP Szerver mezőt üresen');

//
define('JS_LANG_Refresh', 'Frissítés');
define('JS_LANG_MessagesInInbox', 'Üzenet a beérkezett üzenetekben');
define('JS_LANG_InfoEmptyInbox', 'Nincs üzenet');

// webmail 4.2 constants
define('BackToList', 'Vissza a listához');
define('InfoNoContactsGroups', 'Nincsenek névjegyek vagy csoportok.');
define('InfoNewContactsGroups', 'Létrehozhat új névjegyeket/csoportokat vagy beimportálhatja azokat .CSV fájlból MS Outlook formátumban.');
define('DefTimeFormat', 'Alapértelmezett idő formátum');
define('SpellNoSuggestions', 'Nincs javaslat');
define('SpellWait', 'Kérem várjon&hellip;');

define('InfoNoMessageSelected', 'Nincs kiválasztott üzenet.');
define('InfoSingleDoubleClick', 'Kattintson akármelyik üzenetre egyet az előnézetért, kettőt pedig a teljes megjelenítésért.');

// calendar
define('TitleDay', 'Napi nézet');
define('TitleWeek', 'Heti nézet');
define('TitleMonth', 'Havi nézet');

define('ErrorNotSupportBrowser', 'AfterLogic Calendar nem támogatja az Ön böngészőjét. Kérem használjon FireFox 2.0 vagy jobb, Opera 9.0 vagy jobb, Internet Explorer 6.0 vagy jobb, Safari 3.0.2 vagy jobb böngészőket');
define('ErrorTurnedOffActiveX', 'ActiveX támogatás kikapcsolva . <br/>Ennek bekapcsolása szükséges a program használatához.');

define('Calendar', 'Naptár');

define('TabDay', 'Nap');
define('TabWeek', 'Hét');
define('TabMonth', 'Hónap');

define('ToolNewEvent', 'Új&nbsp;Esemény');
define('ToolBack', 'Vissza');
define('ToolToday', 'Ma');
define('AltNewEvent', 'Új esemény');
define('AltBack', 'Vissza');
define('AltToday', 'Ma');
define('CalendarHeader', 'Naptár');
define('CalendarsManager', 'Naptár kezelő');

define('CalendarActionNew', 'Új naptár');
define('EventHeaderNew', 'Új esemény');
define('CalendarHeaderNew', 'Új Naptár');

define('EventSubject', 'Tárgy');
define('EventCalendar', 'Naptár');
define('EventFrom', 'Kezdete');
define('EventTill', 'Vége');
define('CalendarDescription', 'Leírás');
define('CalendarColor', 'Szín');
define('CalendarName', 'Naptár neve');
define('CalendarDefaultName', 'Az én naptáram');

define('ButtonSave', 'Ment');
define('ButtonCancel', 'Mégsem');
define('ButtonDelete', 'Törlés');

define('AltPrevMonth', 'Előző Hónap');
define('AltNextMonth', 'Köv. Hónap');

define('CalendarHeaderEdit', 'Naptár szerkesztés');
define('CalendarActionEdit', 'Naptár szerkesztés');
define('ConfirmDeleteCalendar', 'Biztosan törli a naptárat');
define('InfoDeleting', 'Törlés&hellip;');
define('WarningCalendarNameBlank', 'Nem hagyhatja a naptár nevét üresen.');
define('ErrorCalendarNotCreated', 'A naptár nem lett létrehozva');
define('WarningSubjectBlank', 'Nem hagyhatja a tárgyat üresen.');
define('WarningIncorrectTime', 'A megadott időpont hibás karaktereket is tarmalaz.');
define('WarningIncorrectFromTime', 'A kezdete időpont hibás.');
define('WarningIncorrectTillTime', 'A vége időpont hibás.');
define('WarningStartEndDate', 'A vége dátumnak nagyobbnak vagy egyenlőnek kell lenni a kezdetnél.');
define('WarningStartEndTime', 'A végének később kell lennie a kezdetnél.');
define('WarningIncorrectDate', 'A dátumnak valósnak kell lennie.');
define('InfoLoading', 'Töltés&hellip;');
define('EventCreate', 'Új esemény');
define('CalendarHideOther', 'A többi naptár elrejtése');
define('CalendarShowOther', 'A többi naptár megjelenítése');
define('CalendarRemove', 'Naptár eltávolítása');
define('EventHeaderEdit', 'Esemény szerkesztése');

define('InfoSaving', 'Mentés&hellip;');
define('SettingsDisplayName', 'Név megjelenítése');
define('SettingsTimeFormat', 'Idő formátum');
define('SettingsDateFormat', 'Dátum formátum');
define('SettingsShowWeekends', 'Hétvégék megjelenítése');
define('SettingsWorkdayStarts', 'A munkanapok kezdődnek');
define('SettingsWorkdayEnds', 'végződnek');
define('SettingsShowWorkday', 'Munkanap megjelenítése');
define('SettingsWeekStartsOn', 'A hét kezdődik');
define('SettingsDefaultTab', 'Alapértelmezett fül');
define('SettingsCountry', 'Ország');
define('SettingsTimeZone', 'Időzóna');
define('SettingsAllTimeZones', 'Összes időzóna');

define('WarningWorkdayStartsEnds', 'A \'Munkanapok végződnek\' időpontnak későbbinek kell lennie, mint a \'Munkanapok kezdődnek\'');
define('ReportSettingsUpdated', 'A beállítások sikeresen elmentve.');

define('SettingsTabCalendar', 'Naptár');

define('FullMonthJanuary', 'Január');
define('FullMonthFebruary', 'Február');
define('FullMonthMarch', 'Március');
define('FullMonthApril', 'Április');
define('FullMonthMay', 'Május');
define('FullMonthJune', 'Június');
define('FullMonthJuly', 'Július');
define('FullMonthAugust', 'Augusztus');
define('FullMonthSeptember', 'Szeptember');
define('FullMonthOctober', 'Október');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'December');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Már');
define('ShortMonthApril', 'Ápr');
define('ShortMonthMay', 'Máj');
define('ShortMonthJune', 'Jún');
define('ShortMonthJuly', 'Júl');
define('ShortMonthAugust', 'Aug');
define('ShortMonthSeptember', 'Szep');
define('ShortMonthOctober', 'Okt');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dec');

define('FullDayMonday', 'Hétfő');
define('FullDayTuesday', 'Kedd');
define('FullDayWednesday', 'Szerda');
define('FullDayThursday', 'Csütörtök');
define('FullDayFriday', 'Péntek');
define('FullDaySaturday', 'Szombat');
define('FullDaySunday', 'Vasárnap');

define('DayToolMonday', 'Hét');
define('DayToolTuesday', 'Ked');
define('DayToolWednesday', 'Szer');
define('DayToolThursday', 'Csüt');
define('DayToolFriday', 'Pén');
define('DayToolSaturday', 'Szo');
define('DayToolSunday', 'Vas');

define('CalendarTableDayMonday', 'H');
define('CalendarTableDayTuesday', 'K');
define('CalendarTableDayWednesday', 'SZ');
define('CalendarTableDayThursday', 'CS');
define('CalendarTableDayFriday', 'P');
define('CalendarTableDaySaturday', 'SZo');
define('CalendarTableDaySunday', 'V');

define('ErrorParseJSON', 'A JSON rendszer kimenetének értelmezése közben hiba lépett fel.');

define('ErrorLoadCalendar', 'Nem lehetséges a naptárak betöltése');
define('ErrorLoadEvents', 'Nem lehetséges az események betöltése');
define('ErrorUpdateEvent', 'Nem lehetséges az esemény mentése');
define('ErrorDeleteEvent', 'Nem lehetséges az esemény törlése');
define('ErrorUpdateCalendar', 'Nem lehetséges a naptár elmentése');
define('ErrorDeleteCalendar', 'Nem lehetséges a naptár törlése');
define('ErrorGeneral', 'Hiba történt a szerveren, próbálkozzon később.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Naptár megosztása és publikálása');
define('ShareActionEdit', 'Naptár megosztása és publikálása');
define('CalendarPublicate', 'Nyilvános elérés készítése ehhez a naptárhoz');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'A naptár megosztása');
define('SharePermission1', 'Tud változtatni és megosztást kezelni');
define('SharePermission2', 'Tud változtatni eseményeket');
define('SharePermission3', 'Láthatja az összes részletét az eseményeknek');
define('SharePermission4', 'Csak a szabad/foglalt jelzést láthatja (részletek elrejtése)');
define('ButtonClose', 'Bezár');
define('WarningEmailFieldFilling', 'Elsőként az e-mail mezőt kell kitöltenie');
define('EventHeaderView', 'Esemény megtekintése');
define('ErrorUpdateSharing', 'Nem lehet elmenteni a megosztási és publikálási adatot');
define('ErrorUpdateSharing1', 'Nem lehet megosztani a(z) %s felhasználónak, mivel nem létezik');
define('ErrorUpdateSharing2', 'Nem lehet megosztani ezt a naptárat a(z) %s felhasználónak');
define('ErrorUpdateSharing3', 'Ez a naptár már meg van osztva ennek a felhasználónak: %s');
define('Title_MyCalendars', 'Naptáraim');
define('Title_SharedCalendars', 'Megosztott naptárak');
define('ErrorGetPublicationHash', 'Nem lehet létrehozni a publikációs linket');
define('ErrorGetSharing', 'Nem lehet megosztást hozzáadni');
define('CalendarPublishedTitle', 'Ez a naptár már publikálva van');
define('RefreshSharedCalendars', 'Megosztott Naptárak Frissítése');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Tagok');

define('ReportMessagePartDisplayed', 'Az üzenet csak egy része került megjelenítésre');
define('ReportViewEntireMessage', 'A teljes üzenet megtekintéséhez');
define('ReportClickHere', 'kattintson ide');
define('ErrorContactExists', 'Névjegy ezzel a névvel és e-mail címmel már létezik.');

define('Attachments', 'Melléklet');

define('InfoGroupsOfContact', 'A csoport tagjai pipákkal jelölve');
define('AlertNoContactsSelected', 'Nincsenek kijelölt névjegyek');
define('MailSelected', 'Levél küldése a kijelölt címekre');
define('CaptionSubscribed', 'Feliratkozva');

define('OperationSpam', 'Levélszemét');
define('OperationNotSpam', 'Nem levélszemét');
define('FolderSpam', 'Levélszemét');

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
define('LanguageDefault', 'Alapértelmezett');

// webmail 4.5.x new
define('EmptySpam', 'Levélszemét ürítése');
define('Saving', 'Mentés&hellip;');
define('Sending', 'Küldés&hellip;');
define('LoggingOffFromServer', 'Kijelentkezés a szerverről&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Nem lehet megjelölni az üzenetet levélszemétnek');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Nem lehet megjelölni az üzenetetn nem-levélszemétnek');
define('ExportToICalendar', 'Exportálás iCalendar-ba');
define('ErrorMaximumUsersLicenseIsExceeded', 'A postafiókját átmenetileg kikapcsoltuk, mert a maximális felhasználószámot elérte a program licensze. Kérjük vegye fel a kapcsolatot az adminisztrátorával.');
define('RepliedMessageTitle', 'Megválaszolt Üzenet');
define('ForwardedMessageTitle', 'Továbbított Üzenet');
define('RepliedForwardedMessageTitle', 'Megválaszolt és Továbbított Üzenet');
define('ErrorDomainExist', 'A felhasználót nem lehet létrehozni, mivel a domain nem létezik. Először hozza létre a domaint!');

// webmail 4.7
define('RequestReadConfirmation', 'Olvasás visszaigazolás kérése');
define('FolderTypeDefault', 'Alapértelmezett');
define('ShowFoldersMapping', 'Engedjen használni más mappákat rendszer mappaként (pl. használja a MyFolder-t, mint az Elküldött elemeket)');
define('ShowFoldersMappingNote', 'Például az "Elküldött üzenetek" mappa helyét úgy tudja megváltoztatni, hogy a MyFolder "Használat erre" legödrülő menüjében adja meg "Elküldött üzenetek"-nek.');
define('FolderTypeMapTo', 'Használat erre');

define('ReminderEmailExplanation', 'Ez az üzenet azért érkezett a(z) %EMAIL% fiókjába, mert emlékeztetőt kért róla a naptárában: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Naptár megnyitása');

define('AddReminder', 'Értesítsen erről az eseményről');
define('AddReminderBefore', 'Értesítés az esemény előtt %');
define('AddReminderAnd', 'és utána %');
define('AddReminderAlso', 'és még előtte %');
define('AddMoreReminder', 'Emlékzetetők');
define('RemoveAllReminders', 'Az összes emlékzetető eltávolítása');
define('ReminderNone', 'Nincs');
define('ReminderMinutes', 'perc');
define('ReminderHour', 'óra');
define('ReminderHours', 'órák');
define('ReminderDay', 'nap');
define('ReminderDays', 'napok');
define('ReminderWeek', 'hét');
define('ReminderWeeks', 'hetek');
define('Allday', 'Egész nap');

define('Folders', 'Mappák');
define('NoSubject', 'Nincs tárgy');
define('SearchResultsFor', 'Keresési eredmények: ');

define('Back', 'Vissza');
define('Next', 'Következő');
define('Prev', 'Előző');

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
