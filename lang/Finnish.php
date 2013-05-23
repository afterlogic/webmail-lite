<?php
define('PROC_ERROR_ACCT_CREATE', 'Virhe tiliä luotaessa');
define('PROC_WRONG_ACCT_PWD', 'Väärä salasana');
define('PROC_CANT_LOG_NONDEF', 'Ei oletustiliin ei pystytä kirjautumaan');
define('PROC_CANT_INS_NEW_FILTER', 'Uutta suodatinta ei voi lisätä');
define('PROC_FOLDER_EXIST', 'Kansio on jo olemassa');
define('PROC_CANT_CREATE_FLD', 'Kansiota ei voida luoda');
define('PROC_CANT_INS_NEW_GROUP', 'Uutta ryhmää ei voida lisätä');
define('PROC_CANT_INS_NEW_CONT', 'Uutta yhteystietoa ei voida lisätä ');
define('PROC_CANT_INS_NEW_CONTS', 'Uusia yhteystietoja ei voida lisätä');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Yhteystietoja ei voida lisätä ryhmään');
define('PROC_ERROR_ACCT_UPDATE', 'Virhe tiliä päivitettäessä');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Yhteystietoasetuksia ei voida päivittää');
define('PROC_CANT_GET_SETTINGS', 'Virhe asetuksia haettaessa');
define('PROC_CANT_UPDATE_ACCT', 'Tiliä ei voida päivittää');
define('PROC_ERROR_DEL_FLD', 'Kansiota poistetaessa tapahtui virhe');
define('PROC_CANT_UPDATE_CONT', 'Yhteystietoja ei voida päivittää');
define('PROC_CANT_GET_FLDS', 'Hakemistopuuta ei löydy');
define('PROC_CANT_GET_MSG_LIST', 'Ei saada haettua viestilistausta');
define('PROC_MSG_HAS_DELETED', 'Viesti on jo poistettu postipalvelimelta');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Yhteystietoasetuksia ei voitu ladata');
define('PROC_CANT_LOAD_SIGNATURE', 'Allekirjoitusta ei voitu ladata');
define('PROC_CANT_GET_CONT_FROM_DB', 'Tietokannasta ei saada yhteystietoja');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Tietokannasta ei saada yhteystietoja');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Tiliä ei voida poistaa');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Suodatinta ei voida poistaa');
define('PROC_CANT_DEL_CONT_GROUPS', 'Yhteystietoja ja/tai ryhmiä ei voida poistaa');
define('PROC_WRONG_ACCT_ACCESS', 'Havaittu luvaton yhteydenotto.');
define('PROC_SESSION_ERROR', 'Aikakatkaisu oli päättänyt edellisen istunnon.');

define('MailBoxIsFull', 'Sähköposti on täynnä');
define('WebMailException', 'Sisäinen järjestelmävirhe. Ole hyvä, ota yhteyttä järjestelmänvalvojaan ja kerro ongelmasta.');
define('InvalidUid', 'Väärä viestin UID');
define('CantCreateContactGroup', 'Yhteystietoryhmää voi voi luoda');
define('CantCreateUser', 'Käyttäjää ei pysty luomaan');
define('CantCreateAccount', 'Tiliä ei pysty luomaan');
define('SessionIsEmpty', 'Istunto on tyhjä');
define('FileIsTooBig', 'Tiedosto liian suuri');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Kaikkia viestejä ei voi merkitä luetuiksi');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Kaikkia viestejä ei voi merkitä ei luetuksi');
define('PROC_CANT_PURGE_MSGS', 'Viestejä ei voida tuhota');
define('PROC_CANT_DEL_MSGS', 'Viestiä ei voida poistaa');
define('PROC_CANT_UNDEL_MSGS', 'Viestiä ei voida palauttaa');
define('PROC_CANT_MARK_MSGS_READ', 'Viestiä ei voi merkitä luetuksi');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Viestiä ei voi merkitä ei luetuksi');
define('PROC_CANT_SET_MSG_FLAGS', 'Can\'t set message flag(s)');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Ei voi poistaa viestin flag(s)');
define('PROC_CANT_CHANGE_MSG_FLD', 'Kansiota ei voida vaihtaa');
define('PROC_CANT_SEND_MSG', 'Viestiä ei voida lähettää.');
define('PROC_CANT_SAVE_MSG', 'Viestiä ei voida tallentaa.');
define('PROC_CANT_GET_ACCT_LIST', 'Tililistausta ei saada haettua');
define('PROC_CANT_GET_FILTER_LIST', 'Suodatin listaa ei saada haettua');

define('PROC_CANT_LEAVE_BLANK', 'Et voi jättää kenttää * tyhjäksi');

define('PROC_CANT_UPD_FLD', 'Kansiota ei voida päivittää');
define('PROC_CANT_UPD_FILTER', 'Suodatinta ei voida päivittää');

define('ACCT_CANT_ADD_DEF_ACCT', 'Tiliä ei voi lisätä, se on jo toisen käyttäjän oletustili	.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Tilin tilaa ei voi muuttaa oletukseksi.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Et voi luoda uutta tiliä (IMAP4 yhteys virhe)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Et voi poistaa viimeistä oletustiliä');

define('LANG_LoginInfo', 'Kirjautumistiedot');
define('LANG_Email', 'S.postiosoite');
define('LANG_Login', 'Käyttäjätunnus');
define('LANG_Password', 'Salasana');
define('LANG_IncServer', 'Saapuva&nbsp;posti');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Portti');
define('LANG_OutServer', 'Lähtevä&nbsp;posti');
define('LANG_OutPort', 'Portti');
define('LANG_UseSmtpAuth', 'Use&nbsp;SMTP&nbsp;authentication');
define('LANG_SignMe', 'Muista kirjautumiseni');
define('LANG_Enter', 'Ok');

// interface strings

define('JS_LANG_TitleLogin', 'Kirjaudu');
define('JS_LANG_TitleMessagesListView', 'Viestilista');
define('JS_LANG_TitleMessagesList', 'Viestilista');
define('JS_LANG_TitleViewMessage', 'Näytä viesti');
define('JS_LANG_TitleNewMessage', 'Uusi viesti');
define('JS_LANG_TitleSettings', 'Asetukset');
define('JS_LANG_TitleContacts', 'Yhteystiedot');

define('JS_LANG_StandardLogin', 'Piilota lisätiedot');
define('JS_LANG_AdvancedLogin', 'Lisätiedot');

define('JS_LANG_InfoWebMailLoading', 'WebMail latautuu&hellip;');
define('JS_LANG_Loading', 'Latautuu&hellip;');
define('JS_LANG_InfoMessagesLoad', 'WebMail lataa viestilistaa');
define('JS_LANG_InfoEmptyFolder', 'Kansio on tyhjä');
define('JS_LANG_InfoPageLoading', 'Sivu latautumassa&hellip;');
define('JS_LANG_InfoSendMessage', 'Viesti on lähetetty');
define('JS_LANG_InfoSaveMessage', 'Viesti on tallennettu');
define('JS_LANG_InfoHaveImported', 'Olet tuonut');
define('JS_LANG_InfoNewContacts', 'Uusi osoite osoitekirjaan.');
define('JS_LANG_InfoToDelete', 'Poistaaksesi ');
define('JS_LANG_InfoDeleteContent', 'Kansion, tyhjennä ensin sen sisältö.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Tyhjennä kansion sisältö ensin.');
define('JS_LANG_InfoRequiredFields', '* Vaaditut kentät');

define('JS_LANG_ConfirmAreYouSure', 'Oletko varma?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Valitut viestit poistetaan LOPULLISESTI! Oletko varma?');
define('JS_LANG_ConfirmSaveSettings', 'Asetuksia ei tallennettu. Valitse OK tallentaaksesi.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Yhteystietoasetuksia ei tallennettu. Valitse OK tallentaaksesi.');
define('JS_LANG_ConfirmSaveAcctProp', 'Tilin ominaisuuksia ei tallennettu. Valitse OK tallentaaksesi.');
define('JS_LANG_ConfirmSaveFilter', 'Suodatin ominaisuuksia ei tallennettu. Valitse OK tallentaaksesi.');
define('JS_LANG_ConfirmSaveSignature', 'Allekirjoitusta ei tallennettu. Valitse OK tallentaaksesi.');
define('JS_LANG_ConfirmSavefolders', 'Kansioitaei tallennettu. Valitse OK tallentaaksesi.');
define('JS_LANG_ConfirmHtmlToPlain', 'Varoitus: Muuttamalla muotoilua  HTML:stä plain tekstiin, menetät nykyisen muotoilun viestissä. Valitse OK jatkaaksesi.');
define('JS_LANG_ConfirmAddFolder', 'Muutokset tulee tallentaa ennenkuin lisää/siirtää kansiota. Valitse OK tallentaaksesi.');
define('JS_LANG_ConfirmEmptySubject', 'Aihe - kenttä on tyhjä. Haluatko jatkaa?');

define('JS_LANG_WarningEmailBlank', 'Sähköpostiosoite on pakollinen tieto.');
define('JS_LANG_WarningLoginBlank', 'Et voi jättää<br />"Käyttäjätunnus"-kenttää tyhjäksi.');
define('JS_LANG_WarningToBlank', 'Et voi jättää "Vastaanottaja" -kenttää tyhjäksi');
define('JS_LANG_WarningServerPortBlank', 'Et voi jättää POP3 ja<br />SMTP palvelin/portti kenttiä tyhjäksi.');
define('JS_LANG_WarningEmptySearchLine', 'Tyhjä hakurivi. Anna hakuteksti, jota haluat hakea.');
define('JS_LANG_WarningMarkListItem', 'Valitse vähintään yksi kohde luettelosta.');
define('JS_LANG_WarningFolderMove', 'Kansiota ei voi siirtää, koska se on toisella tasolla.');
define('JS_LANG_WarningContactNotComplete', 'Anna joko nimi tai osoite.');
define('JS_LANG_WarningGroupNotComplete', 'Anna ryhmänimi.');

define('JS_LANG_WarningEmailFieldBlank', 'Sähköpostiosoite on pakollinen tieto.');
define('JS_LANG_WarningIncServerBlank', 'Et voi jättää POP3(IMAP4) -palvelin kenttää tyhjäksi.');
define('JS_LANG_WarningIncPortBlank', 'Et voi jättää POP3(IMAP4) -palvelin kenttää tyhjäksi.');
define('JS_LANG_WarningIncLoginBlank', 'Et voi jättää POP3(IMAP4) kirjautumistunnusta tyhjäksi.');
define('JS_LANG_WarningIncPortNumber', 'Anna positiivinen numero POP3(IMAP4) portiksi.');
define('JS_LANG_DefaultIncPortNumber', 'Oletus POP3(IMAP4) portin numeroksi on 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Et voi jättää POP3(IMAP4) salasanaa tyhjäksi.');
define('JS_LANG_WarningOutPortBlank', 'Et voi jättää SMTP Serveri Porttia tyhjäksi.');
define('JS_LANG_WarningOutPortNumber', 'Anna positiivinen numero SMTP portti kenttään.');
define('JS_LANG_WarningCorrectEmail', 'Tarkista osoite.');
define('JS_LANG_DefaultOutPortNumber', 'Oletus SMTP portin  numero on 25.');

define('JS_LANG_WarningCsvExtention', 'Tiedostopääte tulee olla .csv');
define('JS_LANG_WarningImportFileType', 'Valitse sovellus, jonka haluat kopioida yhteystiedoistasi');
define('JS_LANG_WarningEmptyImportFile', 'Valitse tiedosto napsauttamalla Selaa-painiketta');

define('JS_LANG_WarningContactsPerPage', 'Yhteystiedot per sivu arvo on positiivinen numero');
define('JS_LANG_WarningMessagesPerPage', 'Viestit per sivu arvo on positiivinen numero');
define('JS_LANG_WarningMailsOnServerDays', 'Anna positiivinen arvo pvm kenttään viestit palvelimella.');
define('JS_LANG_WarningEmptyFilter', 'Anna hakuteksti');
define('JS_LANG_WarningEmptyFolderName', 'Anna kansiolle nimi');

define('JS_LANG_ErrorConnectionFailed', 'Yhdistäminen epäonnistui');
define('JS_LANG_ErrorRequestFailed', 'Tiedonsiirtoa ei ole saatu päätökseen');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Objekti XMLHttpRequest on poissa');
define('JS_LANG_ErrorWithoutDesc', 'Tapahtui virhe ilman kuvausta');
define('JS_LANG_ErrorParsing', 'Virhe XML: n jäsentämiseen.');
define('JS_LANG_ResponseText', 'Vastausteksti:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Tyhjä XML paketti');
define('JS_LANG_ErrorImportContacts', 'Virhe yhteystietoja tuotaessa');
define('JS_LANG_ErrorNoContacts', 'Yhteystietoja ei löydy.');
define('JS_LANG_ErrorCheckMail', 'Tapahtui virhe viestejä haettaessa, välttämättä kaikkia viestejä ei tuotu.');
define('JS_LANG_LoggingToServer', 'Kirjautuminen palvelimelle&hellip;');
define('JS_LANG_GettingMsgsNum', 'Haetaan viestien määrää');
define('JS_LANG_RetrievingMessage', 'Viestiä haetaan');
define('JS_LANG_DeletingMessage', 'Viesti poistetaan');
define('JS_LANG_DeletingMessages', 'Viestejä poistetaan');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', 'Yhteys');
define('JS_LANG_Charset', 'Merkistö');
define('JS_LANG_AutoSelect', 'Automaattinen valinta');

define('JS_LANG_Contacts', 'Osoitekirja');
define('JS_LANG_ClassicVersion', 'Classic Versio');
define('JS_LANG_Logout', 'Kirjaudu ulos');
define('JS_LANG_Settings', 'Asetukset');

define('JS_LANG_LookFor', 'Hae: ');
define('JS_LANG_SearchIn', 'Etsi mistä: ');
define('JS_LANG_QuickSearch', 'Hae vain "Lähettäjä", "Vastaanottaja" ja "Aihe" -kentistä.');
define('JS_LANG_SlowSearch', 'Hae kaikki viestit');
define('JS_LANG_AllMailFolders', 'Kaikki kansiot');
define('JS_LANG_AllGroups', 'Kaikki ryhmät');

define('JS_LANG_NewMessage', 'Uusi viesti');
define('JS_LANG_CheckMail', 'Tarkista posti');
define('JS_LANG_EmptyTrash', 'Tyhjennä roskakori');
define('JS_LANG_MarkAsRead', 'Merkitse luetuksi');
define('JS_LANG_MarkAsUnread', 'Merkitse lukemattomaksi');
define('JS_LANG_MarkFlag', 'Flag');
define('JS_LANG_MarkUnflag', 'Unflag');
define('JS_LANG_MarkAllRead', 'Merkitse kaikki luetuksi');
define('JS_LANG_MarkAllUnread', 'Merkitse kaikki lukemattomaksi');
define('JS_LANG_Reply', 'Vastaa');
define('JS_LANG_ReplyAll', 'Vastaa kaikille');
define('JS_LANG_Delete', 'Poista');
define('JS_LANG_Undelete', 'Älä poista');
define('JS_LANG_PurgeDeleted', 'Tuhoa poistetut');
define('JS_LANG_MoveToFolder', 'Siirrä kansioon');
define('JS_LANG_Forward', 'Lähetä eteenpäin');

define('JS_LANG_HideFolders', 'Piilota kansiot');
define('JS_LANG_ShowFolders', 'Näytä kansiot');
define('JS_LANG_ManageFolders', 'Hallinnoi kansioita');
define('JS_LANG_SyncFolder', 'Kansio synkronoitu');
define('JS_LANG_NewMessages', 'Uudet viestit');
define('JS_LANG_Messages', 'Viesti(t)');

define('JS_LANG_From', 'Lähettäjä');
define('JS_LANG_To', 'Vastaanottaja');
define('JS_LANG_Date', 'Pvm');
define('JS_LANG_Size', 'Koko');
define('JS_LANG_Subject', 'Aihe');

define('JS_LANG_FirstPage', 'Ensimmäinen sivu');
define('JS_LANG_PreviousPage', 'Edellinen sivu');
define('JS_LANG_NextPage', 'Seuraava sivu');
define('JS_LANG_LastPage', 'Viimeinen sivu');

define('JS_LANG_SwitchToPlain', 'Vaihda Teksti - näkymään');
define('JS_LANG_SwitchToHTML', 'Vaihda HTML näkymään');
define('JS_LANG_AddToAddressBook', 'Lisää osoitekirjaan');
define('JS_LANG_ClickToDownload', 'Lataa');
define('JS_LANG_View', 'Näytä');
define('JS_LANG_ShowFullHeaders', 'Näytä täydet otsikot');
define('JS_LANG_HideFullHeaders', 'Piilota täydet otsikot');

define('JS_LANG_MessagesInFolder', 'Viesti kansiossa');
define('JS_LANG_YouUsing', 'Olet käyttämässä');
define('JS_LANG_OfYour', 'sinun');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Lähetä');
define('JS_LANG_SaveMessage', 'Tallenna');
define('JS_LANG_Print', 'Tulosta');
define('JS_LANG_PreviousMsg', 'Edellinen viesti');
define('JS_LANG_NextMsg', 'Seuraava viesti');
define('JS_LANG_AddressBook', 'Osoitekirja');
define('JS_LANG_ShowBCC', 'Näytä BCC');
define('JS_LANG_HideBCC', 'Piilota BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Vastaa&nbsp;Vastaanottaja');
define('JS_LANG_AttachFile', 'Liitetiedosto');
define('JS_LANG_Attach', 'Liitä');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Alkuperäinen viesti');
define('JS_LANG_Sent', 'Lähetetty');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Pieni');
define('JS_LANG_Normal', 'Normaali');
define('JS_LANG_High', 'Suuri');
define('JS_LANG_Importance', 'Tärkeys');
define('JS_LANG_Close', 'sulje');

define('JS_LANG_Common', 'Yleiset');
define('JS_LANG_EmailAccounts', 'Tilien asetukset');

define('JS_LANG_MsgsPerPage', 'Viestejä sivulla');
define('JS_LANG_DisableRTE', 'Poista Rich Text-editori');
define('JS_LANG_Skin', 'Malli');
define('JS_LANG_DefCharset', 'Oletus merkistö');
define('JS_LANG_DefCharsetInc', 'Oletusmerkistö saapuvaan viestiin');
define('JS_LANG_DefCharsetOut', 'Oletusmerkistö viestn kirjoitukseen');
define('JS_LANG_DefTimeOffset', 'Aikavyöhyke');
define('JS_LANG_DefLanguage', 'Oletuskieli');
define('JS_LANG_DefDateFormat', 'Ajan esitysmuoto');
define('JS_LANG_ShowViewPane', 'Viestilista esikatseluruudussa');
define('JS_LANG_Save', 'Tallenna');
define('JS_LANG_Cancel', 'Peruuta');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Poista');
define('JS_LANG_AddNewAccount', 'Lisää uusi tili');
define('JS_LANG_Signature', 'Allekirjoitus');
define('JS_LANG_Filters', 'Suodattimet');
define('JS_LANG_Properties', 'Ominaisuudet');
define('JS_LANG_UseForLogin', 'Käytä tämän tilin ominaisuudet (tunnus ja salasana) kirjautumiseen');
define('JS_LANG_MailFriendlyName', 'Lempinimi');
define('JS_LANG_MailEmail', 'Sähköposti');
define('JS_LANG_MailIncHost', 'Saapuvan postin palvelin');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Portti');
define('JS_LANG_MailIncLogin', 'Tunnus');
define('JS_LANG_MailIncPass', 'Salasana');
define('JS_LANG_MailOutHost', 'Lähtevän postin palvelin');
define('JS_LANG_MailOutPort', 'Portti');
define('JS_LANG_MailOutLogin', 'SMTP Käyttäjätunnus');
define('JS_LANG_MailOutPass', 'SMTP salasana');
define('JS_LANG_MailOutAuth1', 'Käytä SMTP tunnistusta');
define('JS_LANG_MailOutAuth2', '(Voit jättää SMTP tunnus/salasana kentät tyhjäksi, jos samatkuin POP3/IMAP4 tunnus/salasana)');
define('JS_LANG_UseFriendlyNm1', 'Käytä lempinimeä "Lähettäjä:" Kentässä');
define('JS_LANG_UseFriendlyNm2', '(Lempinimi &lt;etu.sukunimi@mail.fi&gt;)');
define('JS_LANG_GetmailAtLogin', 'Kirjauduttaessa hae/synkronoi viestit');
define('JS_LANG_MailMode0', 'Poista vastaanotetut viestit palvelimelta');
define('JS_LANG_MailMode1', 'Jätä viestit palvelimelle');
define('JS_LANG_MailMode2', 'Jätä viestit palvelimelle');
define('JS_LANG_MailsOnServerDays', 'päiväksi');
define('JS_LANG_MailMode3', 'Poista viesti palvelimelta, kun se poistetaan roskakorista');
define('JS_LANG_InboxSyncType', 'Saapuneen postin synkronointitapa');

define('JS_LANG_SyncTypeNo', 'Älä synkronoi');
define('JS_LANG_SyncTypeNewHeaders', 'Uudet otsikot');
define('JS_LANG_SyncTypeAllHeaders', 'Kaikki otsikot');
define('JS_LANG_SyncTypeNewMessages', 'Uudet viestit');
define('JS_LANG_SyncTypeAllMessages', 'Kaikki viestit');
define('JS_LANG_SyncTypeDirectMode', 'Suoraan palvelimelle');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Vain otsikot');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Koko viestit');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Suoraan palvelimella');

define('JS_LANG_DeleteFromDb', 'Poista viesti tietokannasta, jos se ei ole enää olemassa postipalvelimella');

define('JS_LANG_EditFilter', 'Muokkaa&nbsp;suodatin');
define('JS_LANG_NewFilter', 'Lisää uusi suodatin');
define('JS_LANG_Field', 'Kenttä');
define('JS_LANG_Condition', 'Ehto');
define('JS_LANG_ContainSubstring', 'Sisältää tekstin');
define('JS_LANG_ContainExactPhrase', 'Sisältää tarkalleen');
define('JS_LANG_NotContainSubstring', 'Ei sisällä tekstiä');
define('JS_LANG_FilterDesc_At', '');
define('JS_LANG_FilterDesc_Field', 'kentässä');
define('JS_LANG_Action', 'Toiminto');
define('JS_LANG_DoNothing', 'Älä tee mitään');
define('JS_LANG_DeleteFromServer', 'Poista heti palvelimelta');
define('JS_LANG_MarkGrey', 'Merkitse harmaaksi');
define('JS_LANG_Add', 'Lisää');
define('JS_LANG_OtherFilterSettings', 'Muut suodatin asetukset');
define('JS_LANG_ConsiderXSpam', 'Harkitse X-Spam otsikot');
define('JS_LANG_Apply', 'Käytä');

define('JS_LANG_InsertLink', 'Lisää linkki');
define('JS_LANG_RemoveLink', 'Poista linkki');
define('JS_LANG_Numbering', 'Numerointi');
define('JS_LANG_Bullets', 'Pallukkalista');
define('JS_LANG_HorizontalLine', 'Vaakaviiva');
define('JS_LANG_Bold', 'Tummennus');
define('JS_LANG_Italic', 'Italic');
define('JS_LANG_Underline', 'Alleviivaa');
define('JS_LANG_AlignLeft', 'Tasaa vasemmalle');
define('JS_LANG_Center', 'Keskitä');
define('JS_LANG_AlignRight', 'Tasaa oikealle');
define('JS_LANG_Justify', 'Perustella');
define('JS_LANG_FontColor', 'Fontin väri');
define('JS_LANG_Background', 'Tausta');
define('JS_LANG_SwitchToPlainMode', 'Vaihda tekstimuotoon');
define('JS_LANG_SwitchToHTMLMode', 'Vaihda HTML Muotoon');

define('JS_LANG_Folder', 'Kansio');
define('JS_LANG_Msgs', 'Viesti');
define('JS_LANG_Synchronize', 'Synkronoi');
define('JS_LANG_ShowThisFolder', 'Näytä kansio');
define('JS_LANG_Total', 'Yhteensä');
define('JS_LANG_DeleteSelected', 'Poista valitut');
define('JS_LANG_AddNewFolder', 'Lisää uusi kansio');
define('JS_LANG_NewFolder', 'Uusi kansio');
define('JS_LANG_ParentFolder', 'Yläkansio');
define('JS_LANG_NoParent', 'Ei yläkansiota');
define('JS_LANG_FolderName', 'Kansion nimi');

define('JS_LANG_ContactsPerPage', 'Yhteyshenkilöitä Sivulla');
define('JS_LANG_WhiteList', 'Näytä viestit vain osoitekirjassa olevilta');

define('JS_LANG_CharsetDefault', 'Oletusmerkistö');
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

define('JS_LANG_TimeDefault', 'Oletus');
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
define('JS_LANG_TimeSarajevo', 'Sarajevo, Skopje, Sofija, Vilnius, Warsaw, Zagreb');
define('JS_LANG_TimeWestCentralAfrica', 'West Central Africa');
define('JS_LANG_TimeAthens', 'Athens, Istanbul, Minsk');
define('JS_LANG_TimeEasternEurope', 'Bucharest');
define('JS_LANG_TimeCairo', 'Cairo');
define('JS_LANG_TimeHarare', 'Harare, Pretoria');
define('JS_LANG_TimeHelsinki', 'Helsinki, Riga, Tallinn');
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

define('JS_LANG_DateDefault', 'Oletus');
define('JS_LANG_DateDDMMYY', 'PP/KK/VV');
define('JS_LANG_DateMMDDYY', 'KK/PP/VV');
define('JS_LANG_DateDDMonth', 'PP Kuukausi (01 Tam)');
define('JS_LANG_DateAdvanced', 'Lisäehdot');

define('JS_LANG_NewContact', 'Uusi yhteyshenkilö');
define('JS_LANG_NewGroup', 'Uusi ryhmä');
define('JS_LANG_AddContactsTo', 'Yhteystietojen lisääminen');
define('JS_LANG_ImportContacts', 'Tuo yhteystietoja');

define('JS_LANG_Name', 'Nimi');
define('JS_LANG_Email', 'Sähköposti');
define('JS_LANG_DefaultEmail', 'Sähköpostiosoite');
define('JS_LANG_NotSpecifiedYet', 'Ei määritelty');
define('JS_LANG_ContactName', 'Nimi');
define('JS_LANG_Birthday', 'Syntymäpäivä');
define('JS_LANG_Month', 'Kuukausi');
define('JS_LANG_January', 'Tammikuu');
define('JS_LANG_February', 'Helmikuu');
define('JS_LANG_March', 'Maaliskuu');
define('JS_LANG_April', 'Huhtikuu');
define('JS_LANG_May', 'Toukokuu');
define('JS_LANG_June', 'Kesäkuu');
define('JS_LANG_July', 'Heinäkuu');
define('JS_LANG_August', 'Elokuu');
define('JS_LANG_September', 'Syyskuu');
define('JS_LANG_October', 'Lokakuu');
define('JS_LANG_November', 'Marraskuu');
define('JS_LANG_December', 'Joulukuu');
define('JS_LANG_Day', 'Pvm');
define('JS_LANG_Year', 'Vuosi');
define('JS_LANG_UseFriendlyName1', 'Käytä lempinimeä');
define('JS_LANG_UseFriendlyName2', '(esim. Antti Ahkera &lt;antti.ahkera@mail.com&gt;)');
define('JS_LANG_Personal', 'Henkilökohtainen');
define('JS_LANG_PersonalEmail', 'Henkilökohtainen sähköposti');
define('JS_LANG_StreetAddress', 'Katuosoite');
define('JS_LANG_City', 'Kaupunki');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Kaupunki');
define('JS_LANG_Phone', 'Puhelin');
define('JS_LANG_ZipCode', 'Postitoimipaikka');
define('JS_LANG_Mobile', 'Matkapuhelin');
define('JS_LANG_CountryRegion', 'Maa');
define('JS_LANG_WebPage', 'Kotisivut');
define('JS_LANG_Go', 'Ok');
define('JS_LANG_Home', 'Koti');
define('JS_LANG_Business', 'Työ');
define('JS_LANG_BusinessEmail', 'Työsähköposti');
define('JS_LANG_Company', 'Yritys');
define('JS_LANG_JobTitle', 'Ammattinimike');
define('JS_LANG_Department', 'Osasto');
define('JS_LANG_Office', 'Toimisto');
define('JS_LANG_Pager', 'Hakulaite');
define('JS_LANG_Other', 'Muu');
define('JS_LANG_OtherEmail', 'Toinen sähköpostiosoite');
define('JS_LANG_Notes', 'Huomautuksia');
define('JS_LANG_Groups', 'Ryhmät');
define('JS_LANG_ShowAddFields', 'Näytä lisätiedot kentät');
define('JS_LANG_HideAddFields', 'Piilota lisätiedot kentät');
define('JS_LANG_EditContact', 'Muokkaa yhteystietoja');
define('JS_LANG_GroupName', 'Ryhmän nimi');
define('JS_LANG_AddContacts', 'Lisää yhteystieto');
define('JS_LANG_CommentAddContacts', '(Käytä pilkkua (,) erotin merkkinä osoitteiden välissä)');
define('JS_LANG_CreateGroup', 'Luo ryhmä');
define('JS_LANG_Rename', 'nimeä uudelleen');
define('JS_LANG_MailGroup', 'Lähetä ryhmälle');
define('JS_LANG_RemoveFromGroup', 'Poista ryhmästä');
define('JS_LANG_UseImportTo', 'Käytä Tuontia kopioidaksesi yhteystiedot Microsoft Outlookista tai Microsoft Outlook Expressistä sinun WebMail-yhteystietolistallesi.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Valitse tiedosto (.CSV päätteinen) jonka haluat tuoda');
define('JS_LANG_Import', 'Tuonti');
define('JS_LANG_ContactsMessage', 'Tämä sivu on yhteystiedot!!!');
define('JS_LANG_ContactsCount', 'Yhteystiedot');
define('JS_LANG_GroupsCount', 'ryhmä');

// webmail 4.1 constants
define('PicturesBlocked', 'Tietoturvan vuoksi viestissä oleva kuva estetty.');
define('ShowPictures', 'Näytä kuvat');
define('ShowPicturesFromSender', 'Näytä aina tämän lähettäjän kuvat');
define('AlwaysShowPictures', 'Näytä aina kuvat viestissä');

define('TreatAsOrganization', 'Yritys');

define('WarningGroupAlreadyExist', 'Kansion nimi jo käytössä, anna uusi.');
define('WarningCorrectFolderName', 'Sinun pitäisi määrittää oikea kansio nimi.');
define('WarningLoginFieldBlank', 'Pakollinen tieto "Käyttäjätunnus" .');
define('WarningCorrectLogin', 'Sinun pitäisi määrittää oikea kirjautumistunnus.');
define('WarningPassBlank', 'Pakollinen tieto "Salasana".');
define('WarningCorrectIncServer', 'Anna oikea POP3(IMAP) palvelinosoite.');
define('WarningCorrectSMTPServer', 'Sinun pitäisi määrittää oikea lähtevän postin osoite.');
define('WarningFromBlank', 'Pakollinen tieto "Lähettäjä".');
define('WarningAdvancedDateFormat', 'Ilmoitathan päivä-aikamuoto.');

define('AdvancedDateHelpTitle', 'Pvm lisätiedot');
define('AdvancedDateHelpIntro', 'Kun &quot;Advanced&quot;-kenttä on valittu, voit käyttää tekstikenttää asettaaksesi oman ajanesitystapasi, jota käytetään WebMailissa. Seuraavia avainsanoja voidaan käyttää tähän tarkoitukseen erotinmerkin \':\' tai \'/\' kanssa:');
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

define('InfoNoMessagesFound', 'Viestejä ei löydy.');
define('ErrorSMTPConnect', 'Kirjautuminen SMTP palvelimelle epäonnistui. Tarkista palvelinasetukset.');
define('ErrorSMTPAuth', 'Virheellinen käyttäjätunnus ja/tai salasana. Tunnistus epäonnistui.');
define('ReportMessageSent', 'Viesti lähetetty.');
define('ReportMessageSaved', 'Viesti tallennettu.');
define('ErrorPOP3Connect', 'Kirjautuminen POP3 palvelimelle epäonnistui. Tarkista palvelinasetukset.');
define('ErrorIMAP4Connect', 'Kirjautuminen IMAP4 palvelimelle epäonnistui. Tarkista palvelinasetukset.');
define('ErrorPOP3IMAP4Auth', 'Väärä sähköpostiosoite / tunnus ja / tai salasana. Todennus epäonnistui.');
define('ErrorGetMailLimit', 'Anteeksi, postilaatikon koko ylittyy.');

define('ReportSettingsUpdatedSuccessfuly', 'Asetukset päivitetty.');
define('ReportAccountCreatedSuccessfuly', 'Tili luotu.');
define('ReportAccountUpdatedSuccessfuly', 'Tili päivitetty.');
define('ConfirmDeleteAccount', 'Oletko varma, että haluat poistaa tilin?');
define('ReportFiltersUpdatedSuccessfuly', 'Suodattimet päivitetty.');
define('ReportSignatureUpdatedSuccessfuly', 'Allekirjoitus päivitetty.');
define('ReportFoldersUpdatedSuccessfuly', 'Kansiot päivitetty.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Yhteystietoasetukset päivitetty.');

define('ErrorInvalidCSV', 'Valittu .CSV tiedosto on väärää muotoa.');
define('ReportGroupSuccessfulyAdded1', 'Ryhmä');
define('ReportGroupSuccessfulyAdded2', 'lisätty.');
define('ReportGroupUpdatedSuccessfuly', 'Ryhmä päivitetty.');
define('ReportContactSuccessfulyAdded', 'Yhteystieto lisätty.');
define('ReportContactUpdatedSuccessfuly', 'Yhteystieto päivitetty.');
define('ReportContactAddedToGroup', 'Yhteystieto lisätty ryhmään');
define('AlertNoContactsGroupsSelected', 'Yhteystietoa tai ryhmää ei valittu.');

define('InfoListNotContainAddress', 'Jos osoitetta ei löydy, anna sen ensimmäinen kirjain.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direct Mode. Suoraan palvelimelle.');

define('FolderInbox', 'Saapuneet');
define('FolderSentItems', 'Lähetetyt');
define('FolderDrafts', 'Luonnokset');
define('FolderTrash', 'Roskakori');

define('FileLargerAttachment', 'Liian suuri liitetiedosto.');
define('FilePartiallyUploaded', 'Vain osa tiedostosta latautui, tuntematon virhe.');
define('NoFileUploaded', 'Tiedostoa ei ladattu.');
define('MissingTempFolder', 'Väliaikainen kansio puuttuu.');
define('MissingTempFile', 'Tilapäinen tiedosto puuttuu.');
define('UnknownUploadError', 'Tuntematon tiedostonlähetysvirhe.');
define('FileLargerThan', 'Ladattavan tiedoston virhe. Todennäköisesti tiedosto on suurempi kuin ');
define('PROC_CANT_LOAD_DB', 'Yhteyttä tietokantaan ei saada.');
define('PROC_CANT_LOAD_LANG', 'Vaadittavaa kielitiedostoa ei löydy.');
define('PROC_CANT_LOAD_ACCT', 'Tili ei ole olemassa.');

define('DomainDosntExist', 'Tällainen domain ei löydy sähköpostipalvelimelta.');
define('ServerIsDisable', 'Ylläpitäjä estänyt postipalvelimen käytön.');

define('PROC_ACCOUNT_EXISTS', 'Tili on jo perustettu.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Kansion viestin määrää ei saada laskettua.');
define('PROC_CANT_MAIL_SIZE', 'Postin kokoa ei saada määritettyä.');

define('Organization', 'Organisaatio');
define('WarningOutServerBlank', 'Et voi jättää "Lähtevä posti"-kenttä tyhjäksi.');

define('JS_LANG_Refresh', 'Päivitä');
define('JS_LANG_MessagesInInbox', 'Viestejä saapuneet kansiossa');
define('JS_LANG_InfoEmptyInbox', 'Saapuneet kansio tyhjä');

// webmail 4.2 constants
define('BackToList', 'Takaisin päänäkymään');
define('InfoNoContactsGroups', 'Ei yhteystietoja tai ryhmää.');
define('InfoNewContactsGroups', 'Voit joko luoda uusia kontakteja / ryhmiä tai tuoda yhteystietoja. CSV MS Outlook-muodossa.');
define('DefTimeFormat', 'Ajan oletusesitys');
define('SpellNoSuggestions', 'Ei ehdotuksia');
define('SpellWait', 'Odota&hellip;');

define('InfoNoMessageSelected', 'Viestiä ei valittu.');
define('InfoSingleDoubleClick', 'Voit joko yhdellä napsautuksella  esikatsella viestiä listamuodossa tai kaksoisnapsauttamalla nähdäksesi täysikokoisena.');

// calendar
define('TitleDay', 'Päivänäkymä');
define('TitleWeek', 'viikkonäkymä');
define('TitleMonth', 'Kuukausinäkymä');

define('ErrorNotSupportBrowser', 'Kalenteri ei tue selaintasi. Käytä FireFox 2.0 tai uudempaa, Opera 9.0 tai uudempaa, Internet Explorer 6.0 tai uudempaa, Safari 3.0.2 tai uudempaa.');
define('ErrorTurnedOffActiveX', 'ActiveX tuki on poispäältä . <br/>Laita se päälle voidaksesi käyttää sovellusta.');

define('Calendar', 'Kalenteri');

define('TabDay', 'Päivä');
define('TabWeek', 'Viikko');
define('TabMonth', 'Kuukausi');

define('ToolNewEvent', 'Uusi&nbsp;Tapahtuma');
define('ToolBack', 'Takaisin');
define('ToolToday', 'Tänään');
define('AltNewEvent', 'Uusi tapahtuma');
define('AltBack', 'Takaisin');
define('AltToday', 'Tänään');
define('CalendarHeader', 'Kalenteri');
define('CalendarsManager', 'Kalenterin hallinnoija');

define('CalendarActionNew', 'Uusi kalenteri');
define('EventHeaderNew', 'Uusi tapahtuma');
define('CalendarHeaderNew', 'Uusi kalenteri');

define('EventSubject', 'Aihe');
define('EventCalendar', 'Kalenteri');
define('EventFrom', 'alkaa');
define('EventTill', 'päättyy');
define('CalendarDescription', 'Kuvaus');
define('CalendarColor', 'Väri');
define('CalendarName', 'Kalenterin nimi');
define('CalendarDefaultName', 'Oma kalenteri');

define('ButtonSave', 'Tallenna');
define('ButtonCancel', 'Peruuta');
define('ButtonDelete', 'Poista');

define('AltPrevMonth', 'Edell.kuukausi');
define('AltNextMonth', 'seuraava kuukausi');

define('CalendarHeaderEdit', 'Muokkaa kalenteria');
define('CalendarActionEdit', 'Muokkaa kalenteria');
define('ConfirmDeleteCalendar', 'Haluatko varmasti poistaa kalenterin');
define('InfoDeleting', 'Poistaa&hellip;');
define('WarningCalendarNameBlank', 'Kalenterin nimi pakollinen.');
define('ErrorCalendarNotCreated', 'Kalenteria ei luotu.');
define('WarningSubjectBlank', 'Aihe on pakollinen.');
define('WarningIncorrectTime', 'Aika sisältää kiellettyjä merkkejä.');
define('WarningIncorrectFromTime', '"Alkaa" aika väärin.');
define('WarningIncorrectTillTime', '"Päättyy" aika väärin.');
define('WarningStartEndDate', 'Päättymispäivä tulee olla suurempi tai yhtäsuuri kuin aloituspäivä.');
define('WarningStartEndTime', 'Päättymisaika on oltava suurempi kuin aloitusaika.');
define('WarningIncorrectDate', 'Tarkista päivämäärä.');
define('InfoLoading', 'Ladataan&hellip;');
define('EventCreate', 'Luo tapahtuma');
define('CalendarHideOther', 'Piilota muiden kalenterit');
define('CalendarShowOther', 'Näytä muiden kalenterit');
define('CalendarRemove', 'Poista kalenteri');
define('EventHeaderEdit', 'Muokkaa tapahtumaa');

define('InfoSaving', 'Tallentaa&hellip;');
define('SettingsDisplayName', 'Näyttönimi');
define('SettingsTimeFormat', 'Aikamuoto');
define('SettingsDateFormat', 'Päivämäärän muoto');
define('SettingsShowWeekends', 'Näytä viikonloput');
define('SettingsWorkdayStarts', 'työpäivä alkaa');
define('SettingsWorkdayEnds', 'päättyy');
define('SettingsShowWorkday', 'Näytä työpäivä');
define('SettingsWeekStartsOn', 'Viikko alkaa');
define('SettingsDefaultTab', 'Oletusnäkymä');
define('SettingsCountry', 'Maa');
define('SettingsTimeZone', 'Aikavyöhyke');
define('SettingsAllTimeZones', 'Kaikki aikavyöhykkeet');

define('WarningWorkdayStartsEnds', '\'Päättyy\' aika tulee olla suurempi kuin \'työpäivä alkaa\'');
define('ReportSettingsUpdated', 'Asetukset päivitetty.');

define('SettingsTabCalendar', 'Kalenteri');

define('FullMonthJanuary', 'Tammikuu');
define('FullMonthFebruary', 'Helmikuu');
define('FullMonthMarch', 'Maaliskuu');
define('FullMonthApril', 'Huhtikuu');
define('FullMonthMay', 'Toukokuu');
define('FullMonthJune', 'Kesäkuu');
define('FullMonthJuly', 'Heinäkuu');
define('FullMonthAugust', 'Elokuu');
define('FullMonthSeptember', 'Syyskuu');
define('FullMonthOctober', 'Lokaku');
define('FullMonthNovember', 'Marraskuu');
define('FullMonthDecember', 'Joulukuu');

define('ShortMonthJanuary', 'Tam');
define('ShortMonthFebruary', 'Helmi');
define('ShortMonthMarch', 'Maalis');
define('ShortMonthApril', 'Huhti');
define('ShortMonthMay', 'Touko');
define('ShortMonthJune', 'Kesä');
define('ShortMonthJuly', 'Heinä');
define('ShortMonthAugust', 'Elo');
define('ShortMonthSeptember', 'Syys');
define('ShortMonthOctober', 'Loka');
define('ShortMonthNovember', 'Marras');
define('ShortMonthDecember', 'Joulu');

define('FullDayMonday', 'Maanantai');
define('FullDayTuesday', 'Tiistai');
define('FullDayWednesday', 'Keskiviikko');
define('FullDayThursday', 'Torstai');
define('FullDayFriday', 'Perjantai');
define('FullDaySaturday', 'Lauantai');
define('FullDaySunday', 'Sunnuntai');

define('DayToolMonday', 'Ma');
define('DayToolTuesday', 'Ti');
define('DayToolWednesday', 'Ke');
define('DayToolThursday', 'To');
define('DayToolFriday', 'Pe');
define('DayToolSaturday', 'La');
define('DayToolSunday', 'Su');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'Ti');
define('CalendarTableDayWednesday', 'K');
define('CalendarTableDayThursday', 'To');
define('CalendarTableDayFriday', 'P');
define('CalendarTableDaySaturday', 'L');
define('CalendarTableDaySunday', 'S');

define('ErrorParseJSON', 'The JSON response returned by the server cannot be parsed.');

define('ErrorLoadCalendar', 'Kalenterien lataaminen ei onnistu');
define('ErrorLoadEvents', 'Tapahtumien lataaminen ei onnistu');
define('ErrorUpdateEvent', 'Tapahtumaa ei pysty tallentamaan');
define('ErrorDeleteEvent', 'Tapahtumaa ei pysty poistamaan');
define('ErrorUpdateCalendar', 'Kalenteria ei pysty päivittämään');
define('ErrorDeleteCalendar', 'Kalenteria ei pysty poistamaan');
define('ErrorGeneral', 'Palvelimella tapahtui virhe, yritä hetken kuluttua uudelleen.');

// webmail 4.3 constants
define('SharedTitleEmail', 'Sähköposti');
define('ShareHeaderEdit', 'Jaa ja julkaise kalenteri');
define('ShareActionEdit', 'Jaa ja julkaise kalenteri');
define('CalendarPublicate', 'Tehdään julkisesta netistä käyttöoikeus tähän kalenteriin');
define('CalendarPublicationLink', 'Linkki');
define('ShareCalendar', 'Jaa kalenteri');
define('SharePermission1', 'Voi tehdä muutoksia JA hallita jakamista');
define('SharePermission2', 'Voi tehdä muutoksia tapahtumiin');
define('SharePermission3', 'Voi nähdä kaikkien tapahtumien yksityiskohdat');
define('SharePermission4', 'Voi nähdä vain vapaa / varattu (piilota tiedot)');
define('ButtonClose', 'Sulje');
define('WarningEmailFieldFilling', 'Anna sähköpostiosoite ensin');
define('EventHeaderView', 'Näytä tapahtuma');
define('ErrorUpdateSharing', 'Jakamis ja julkaisemistietoja ei pystytty tallentamaan');
define('ErrorUpdateSharing1', 'Ei voi jakaa  %s käyttäjälle, käyttäjä puuttuu');
define('ErrorUpdateSharing2', 'Mahdotonta jakaa kalenteria käyttäjälle %s');
define('ErrorUpdateSharing3', 'Kalenteri on jo jaettu käyttäjälle %s');
define('Title_MyCalendars', 'Omat kalenterit');
define('Title_SharedCalendars', 'Jaetut kalenterit');
define('ErrorGetPublicationHash', 'Julkaisulinkkiä ei voi luoda');
define('ErrorGetSharing', 'Jakoa ei voida tehdä');
define('CalendarPublishedTitle', 'Tämä kalenteri on julkaistu');
define('RefreshSharedCalendars', 'Päivitä jaetut kalenterit');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Jäsenet');

define('ReportMessagePartDisplayed', 'Huomioi, että vain osa tulee näkyviin.');
define('ReportViewEntireMessage', 'Jos haluat nähdä koko viestin,');
define('ReportClickHere', 'klikkaa tästä');
define('ErrorContactExists', 'Yhteyshenkilö on jo perustettu.');

define('Attachments', 'Liitteet');

define('InfoGroupsOfContact', 'Ryhmät,joissa yhteyshenkilö on jäsenenä, on merkitty valintamerkillä.');
define('AlertNoContactsSelected', 'Ei valittua yhteyshenkilöä.');
define('MailSelected', 'Lähetä postia valituille');
define('CaptionSubscribed', 'Tilatut');

define('OperationSpam', 'Roskaposti');
define('OperationNotSpam', 'Ei roskapostia');
define('FolderSpam', 'Roskaposti');

// webmail 4.4 contacts
define('ContactMail', 'Luo uusi viesti');
define('ContactViewAllMails', 'Näytä yhteyshenkilön kaikki viestit');
define('ContactsMailThem', 'Lähetä postia');
define('DateToday', 'Tänään');
define('DateYesterday', 'Eilen');
define('MessageShowDetails', 'Näytä yksityiskohdat');
define('MessageHideDetails', 'Piilota yksityiskohdat');
define('MessageNoSubject', 'Ei aihetta');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'to');
define('SearchClear', 'Tyhjennä haku');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Hae tuloksia "#s" kansiosta #f:');
define('SearchResultsInAllFolders', 'Hae tuloksia "#s" kaikista kansioista:');
define('AutoresponderTitle', 'Automaattivastaaja');
define('AutoresponderEnable', 'Ota automaattivastaaja');
define('AutoresponderSubject', 'Aihe');
define('AutoresponderMessage', 'Viesti');
define('ReportAutoresponderUpdatedSuccessfuly', 'Automaattivastaaja päivitetty.');
define('FolderQuarantine', 'Karanteeni');

//calendar
define('EventRepeats', 'Toistuu');
define('NoRepeats', 'Ei toistu');
define('DailyRepeats', 'Päivittäin');
define('WorkdayRepeats', 'Joka viikko (Ma. - Pe.)');
define('OddDayRepeats', 'Joka Ma., Ke. ja Pe.');
define('EvenDayRepeats', 'Joka Ti. ja To.');
define('WeeklyRepeats', 'Viikottain');
define('MonthlyRepeats', 'Kuukausittain');
define('YearlyRepeats', 'Vuosittain');
define('RepeatsEvery', 'Toistuu joka');
define('ThisInstance', 'Vain tässä tapauksessa');
define('AllEvents', 'Kaikki tapahtumat sarjassa');
define('AllFollowing', 'Kaikki seuraavat');
define('ConfirmEditRepeatEvent', 'Haluatko muuttaa vain tämän tapahtuman, kaikki tapahtumat, tai tämä ja kaikki tulevat tapahtumat sarjassa?');
define('RepeatEventHeaderEdit', 'Muokkaa Toistuva tapahtuma');
define('First', 'Ensimmäinen');
define('Second', 'Toinen');
define('Third', 'Kolmas');
define('Fourth', 'Neljäs');
define('Last', 'Viimeinen');
define('Every', 'Joka');
define('SetRepeatEventEnd', 'Aseta loppupvm');
define('NoEndRepeatEvent', 'Ei loppupvm.ä');
define('EndRepeatEventAfter', 'Lopeta ');
define('Occurrences', 'kerran jälkeen');
define('EndRepeatEventBy', 'Loppuen');
define('EventCommonDataTab', 'Tärkeimmät tiedot');
define('EventRepeatDataTab', 'Toistuvat tiedot');
define('RepeatEventNotPartOfASeries', 'Tämä tapahtuma on muuttunut eikä ole enää osa
sarjaa.');
define('UndoRepeatExclusion', 'Kumoa muutokset ja sisällytä sarjaan.');

define('MonthMoreLink', '%d lisää...');
define('NoNewSharedCalendars', 'Ei uusia kalentereita');
define('NNewSharedCalendars', '%d uusia kalentereita löydetty');
define('OneNewSharedCalendars', '1 uusi kalenteri löydetty');
define('ConfirmUndoOneRepeat', 'Haluatko palauttaa tämän tapahtuman sarjaan?');

define('RepeatEveryDayInfin', 'Joka päivä');
define('RepeatEveryDayTimes', 'Joka päivä, %TIMES% kertaa');
define('RepeatEveryDayUntil', 'Joka päivä, %UNTIL% asti');
define('RepeatDaysInfin', 'Joka %PERIOD% päivä');
define('RepeatDaysTimes', 'Joka %PERIOD% päivä, %TIMES% kertaa');
define('RepeatDaysUntil', 'Joka %PERIOD% päivä,  %UNTIL% asti');

define('RepeatEveryWeekWeekdaysInfin', 'Joka viikko arkisin');
define('RepeatEveryWeekWeekdaysTimes', 'Joka viikko arkisin, %TIMES% kertaa');
define('RepeatEveryWeekWeekdaysUntil', 'Joka viikko arkisin, %UNTIL% asti');
define('RepeatWeeksWeekdaysInfin', 'Joka %PERIOD% viikko arkisin');
define('RepeatWeeksWeekdaysTimes', 'Joka %PERIOD% viikko arkisin, %TIMES% kertaa');
define('RepeatWeeksWeekdaysUntil', 'Joka %PERIOD% viikko arkisin, until %UNTIL%');

define('RepeatEveryWeekInfin', 'Joka viikko  %DAYS%');
define('RepeatEveryWeekTimes', 'Joka viikko %DAYS%, %TIMES% kertaa');
define('RepeatEveryWeekUntil', 'Joka viikko %DAYS%, %UNTIL% asti');
define('RepeatWeeksInfin', 'Joka %PERIOD% viikko %DAYS%');
define('RepeatWeeksTimes', 'Joka %PERIOD% viikko %DAYS%, %TIMES% kertaa');
define('RepeatWeeksUntil', 'Joka %PERIOD% viikko %DAYS%, %UNTIL% asti');

define('RepeatEveryMonthDateInfin', 'Joka kuukausi  %DATE%');
define('RepeatEveryMonthDateTimes', 'Joka kuukausi %DATE%, %TIMES% kertaa');
define('RepeatEveryMonthDateUntil', 'Joka kuukausi %DATE%, %UNTIL% asti');
define('RepeatMonthsDateInfin', 'Joka %PERIOD% kuukausi %DATE%');
define('RepeatMonthsDateTimes', 'Joka %PERIOD% kuukausi %DATE%, %TIMES% kertaa');
define('RepeatMonthsDateUntil', 'Joka %PERIOD% kuukausi %DATE%, %UNTIL% asti');

define('RepeatEveryMonthWDInfin', 'Joka  %NUMBER% kuukausi %DAY% päivä');
define('RepeatEveryMonthWDTimes', 'Joka %NUMBER% kuukausi %DAY% päivä, %TIMES% kertaa');
define('RepeatEveryMonthWDUntil', 'Joka %NUMBER% kuukausi %DAY%, %UNTIL% asti');
define('RepeatMonthsWDInfin', 'Joka %PERIOD%  %NUMBER% kuukausi %DAY% päivä');
define('RepeatMonthsWDTimes', 'Joka %PERIOD% kuukausi %NUMBER% %DAY% päivä, %TIMES% kertaa');
define('RepeatMonthsWDUntil', 'Joka %PERIOD% months on %NUMBER% %DAY%, until %UNTIL%');

define('RepeatEveryYearDateInfin', 'Joka vuosi %DATE% päivä');
define('RepeatEveryYearDateTimes', 'Joka vuosi %DATE% päivä, %TIMES% kertaa');
define('RepeatEveryYearDateUntil', 'Joka vuosi %DATE% päivä, %UNTIL% asti');
define('RepeatYearsDateInfin', 'Joka %PERIOD% vuosi %DATE% päivä');
define('RepeatYearsDateTimes', 'Joka %PERIOD% vuosi %DATE% päivä, %TIMES% kertaa');
define('RepeatYearsDateUntil', 'Joka %PERIOD% vuosi %DATE% päivä,  %UNTIL% asti');

define('RepeatEveryYearWDInfin', 'Joka vuosi %NUMBER% %DAY% päivä');
define('RepeatEveryYearWDTimes', 'Joka vuosi %NUMBER% %DAY% päivä, %TIMES% kertaa');
define('RepeatEveryYearWDUntil', 'Joka vuosi %NUMBER% %DAY% päivä, %UNTIL% asti');
define('RepeatYearsWDInfin', 'Joka %PERIOD% vuosi %NUMBER% %DAY% päivä');
define('RepeatYearsWDTimes', 'Joka %PERIOD% vuosi %NUMBER% %DAY% päivä, %TIMES% kertaa');
define('RepeatYearsWDUntil', 'Joka %PERIOD% vuosi %NUMBER% %DAY% päivä, %UNTIL% asti');

define('RepeatDescDay', 'päivä');
define('RepeatDescWeek', 'viikko');
define('RepeatDescMonth', 'kuukausi');
define('RepeatDescYear', 'vuosi');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Ilmoitathan toiston päättymispäivän');
define('WarningWrongUntilDate', 'Toiston päättymispäivä oltava suurempi kuin aloituspäivä');

define('OnDays', 'päivinä');
define('CancelRecurrence', 'Peruuta toisto');
define('RepeatEvent', 'Toista tapahtuma');

define('Spellcheck', 'Tarkista oikeinkirjoitus');
define('LoginLanguage', 'Kieli');
define('LanguageDefault', 'Oletus');

// webmail 4.5.x new
define('EmptySpam', 'Tyhjennä roskaposti');
define('Saving', 'Tallentaa&hellip;');
define('Sending', 'Lähettää&hellip;');
define('LoggingOffFromServer', 'Kirjaudutaan palvelimelta&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Viestiä ei voida merkitä roskapostiksi');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Viestiä ei voida merkitä EI -roskapostiksi');
define('ExportToICalendar', 'Vie iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Käyttäjää ei voitu luoda, koska käyttäjälisenssien max-määrä ylittynyt.');
define('RepliedMessageTitle', 'Vastattu viesti');
define('ForwardedMessageTitle', 'Eteenpäin lähetetty viesti');
define('RepliedForwardedMessageTitle', 'Vastattu ja eteenpäin lähetetty viesti');
define('ErrorDomainExist', 'Käyttäjää ei voitu luoda, koska vastaava verkkotunnusta ei ole olemassa. Sinun tulee luoda verkkotunnus ensin.');

// webmail 4.7
define('RequestReadConfirmation', 'Vastaanottokuittaus');
define('FolderTypeDefault', 'Oletus');
define('ShowFoldersMapping', 'Haluan käyttää toista kansiota järjestelmäkansiona (esim Oma kansio Lähetetyt kansiona)');
define('ShowFoldersMappingNote', 'Esim, Muuttaaksesi lähetetyt kansion paikkaa Oma kansion lähetetyt, muuta paikka omakansion alla Lähetetyt".');
define('FolderTypeMapTo', 'Käytä');

define('ReminderEmailExplanation', 'Viesti tullut osoitteeseen %EMAIL% koska tilasit tapahtumailmoituksen kalenterissasi: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Avaa kalenteri');

define('AddReminder', 'Muistuta minua tästä tapahtumasta');
define('AddReminderBefore', 'Muistuta % ennen tapahtumaa');
define('AddReminderAnd', 'ja  % ennen');
define('AddReminderAlso', 'sekä myös % ennen');
define('AddMoreReminder', 'Lisää muistutuksia');
define('RemoveAllReminders', 'Poista kaikki muistutukset');
define('ReminderNone', 'Ei mitään');
define('ReminderMinutes', 'minuuttia');
define('ReminderHour', 'tunti');
define('ReminderHours', 'tunteja');
define('ReminderDay', 'päivä');
define('ReminderDays', 'päiviä');
define('ReminderWeek', 'viikko');
define('ReminderWeeks', 'viikkoja');
define('Allday', 'Koko päivän');

define('Folders', 'Kansiot');
define('NoSubject', 'Ei aihetta');
define('SearchResultsFor', 'Hakutulokset');

define('Back', 'Paluu');
define('Next', 'Seuraava');
define('Prev', 'Edellinen');

define('MsgList', 'Viestejä');
define('Use24HTimeFormat', 'Käytä 24 tunnin aikamuotoa');
define('UseCalendars', 'Käytä kalenteria');
define('Event', 'Tapahtuma');
define('CalendarSettingsNullLine', 'Ei kalenteria');
define('CalendarEventNullLine', 'Ei tapahtumia');
define('ChangeAccount', 'Muuta tili');

define('TitleCalendar', 'Kalenteri');
define('TitleEvent', 'Tapahtuma');
define('TitleFolders', 'Kansiot');
define('TitleConfirmation', 'Vahvistus');

define('Yes', 'Ok');
define('No', 'Ei');

define('EditMessage', 'Muokkaa viestiä');

define('AccountNewPassword', 'Uusi salasana');
define('AccountConfirmNewPassword', 'Vahvista salasana');
define('AccountPasswordsDoNotMatch', 'Salasanat eivät täsmää.');

define('ContactTitle', 'Titteli');
define('ContactFirstName', 'Etunimi');
define('ContactSurName', 'Sukunimi');

define('ContactNickName', 'Nimimerkki');

define('CaptchaTitle', 'Kuvatunniste');
define('CaptchaReloadLink', 'lataa uudelleen');
define('CaptchaError', 'Kuvatunnisteteksti väärin.');

define('WarningInputCorrectEmails', 'Tarkista sähköpostiosoitteet.');
define('WrongEmails', 'Väärät osoitteet:');

define('ConfirmBodySize1', 'Tekstiviestien maksimipituus on');
define('ConfirmBodySize2', 'merkkiä. Kaikki tämän rajan yli menevät merkit poistetaan. Napauta "Peruuta", jos haluat muokata viestiä.');
define('BodySizeCounter', 'laskuri');
define('InsertImage', 'Lisää kuva');
define('ImagePath', 'Tiedostopolku');
define('ImageUpload', 'Liitä');
define('WarningImageUpload', 'Tiedosto ei ole kuva, valitse kuvatiedosto.');

define('ConfirmExitFromNewMessage', 'Jos siirryt pois tältä sivulta tallentamatta, menetät kaikki  edellisen tallennuksen jälkeiset muutokset. Napsauta Peruuta pysyäksesi nykyisellä sivulla.');

define('SensivityConfidential', 'Luottamuksellinen');
define('SensivityPrivate', 'Yksityinen');
define('SensivityPersonal', 'Henkilökohtainen');

define('ReturnReceiptTopText', 'Lähettäjä on pyytänyt saada ilmoituksen, kun saat tämän viestin.');
define('ReturnReceiptTopLink', 'Klikkaa tästä ilmoituksesi lähettäjälle.');
define('ReturnReceiptSubject', 'Vastaanottokuittaus (näytetään)');
define('ReturnReceiptMailText1', 'Tämä on Vastaanottokuittaus');
define('ReturnReceiptMailText2', 'Huom: Tämä Vastaanottokuittaus vain toteaa, että viesti oli esillä vastaanottajan tietokoneella. Ei ole mitään takeita siitä, että vastaanottaja on lukenut tai ymmärtänyt viestin sisällön.');
define('ReturnReceiptMailText3', 'Aiheella');

define('SensivityMenu', 'Yksityisyys');
define('SensivityNothingMenu', 'Ei mitään');
define('SensivityConfidentialMenu', 'Luottamuksellinen');
define('SensivityPrivateMenu', 'Yksityinen');
define('SensivityPersonalMenu', 'Henkilökohtainen');

define('ErrorLDAPonnect', 'Ldap - palvelimeen ei saada yhteyttä');

define('MessageSizeExceedsAccountQuota', 'Viestin koko ylittää tilisi kiintiön.');
define('MessageCannotSent', 'Viestiä ei voida lähettää.');
define('MessageCannotSaved', 'Viestiä ei voida tallentaa.');

define('ContactFieldTitle', 'Kenttä');
define('ContactDropDownTO', 'Vastaanottaja');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Viestiä ei voida siirtää roskakoriin. Todennäköisesti roskakori on täynnä. Poistetaanko tämä viesti?');

define('WarningFieldBlank', 'Kenttä ei voi olla tyhjä.');
define('WarningPassNotMatch', 'Salasana väärin, tarkista.');
define('PasswordResetTitle', 'Salasanan palautus - vaihe %d');
define('NullUserNameonReset', 'Käyttäjä');
define('IndexResetLink', 'Unohditko Salasanan?');
define('IndexRegLink', 'Tilin rekisteröinti');

define('RegDomainNotExist', 'Verkkotunnusta ei ole olemassa.');
define('RegAnswersIncorrect', 'Vastaukset väärin.');
define('RegUnknownAdress', 'Tuntematon sähköpostiosoite.');
define('RegUnrecoverableAccount', 'Salasanan palautusta ei voida tehdä tälle tunnukselle.');
define('RegAccountExist', 'Tämä osoite on jo käytössä.');
define('RegRegistrationTitle', 'Rekisteröinti');
define('RegName', 'Nimi');
define('RegEmail', 'Sähköpostiosoite');
define('RegEmailDesc', 'Esimerkiksi, etu.sukunimi@domain.fi.');
define('RegSignMe', 'Muista minut');
define('RegSignMeDesc', 'Älä kysy käyttäjätunnusta ja salasanaa seuraavalla kerralla tällä koneella.');
define('RegPass1', 'Salasana');
define('RegPass2', 'Toista salasana ');
define('RegQuestionDesc', 'Ole hyvä, anna kaksi salaista kysymystä ja vastausta, jotka  vain sinä tiedät.Jos salasanasi on kadonnut, voit käyttää näitä kysymyksiä saadaksesi takaisin salasanasi.');
define('RegQuestion1', 'Kysymys 1');
define('RegAnswer1', 'Vastaus 1');
define('RegQuestion2', 'Kysymys 2');
define('RegAnswer2', 'Vastaus 2');
define('RegTimeZone', 'Aikavyöhyke');
define('RegLang', 'Käyttöliittymän kieli');
define('RegCaptcha', 'Kuvatunniste');
define('RegSubmitButtonValue', 'Rekisteri');

define('ResetEmail', 'Anna sähköpostiosoitteesi');
define('ResetEmailDesc', 'Anna rekisteröintiä varten sähköpostiosoite.');
define('ResetCaptcha', 'KUVATUNNISTE');
define('ResetSubmitStep1', 'Lähetä');
define('ResetQuestion1', 'Kysymys 1');
define('ResetAnswer1', 'Vastaus');
define('ResetQuestion2', 'Kysymys 2');
define('ResetAnswer2', 'Vastaus');
define('ResetSubmitStep2', 'Lähetä');

define('ResetTopDesc1Step2', 'Anna sähköpostiosoitteesi');
define('ResetTopDesc2Step2', 'Vahvista oikeellisuus.');

define('ResetTopDescStep3', 'Anna uusi salasanasi.');

define('ResetPass1', 'Uusi salasana');
define('ResetPass2', 'Toista salasana');
define('ResetSubmitStep3', 'Lähetä');
define('ResetDescStep4', 'Salasana vaihdettu.');
define('ResetSubmitStep4', 'Palaa');

define('RegReturnLink', 'Palaa kirjautumissivulle');
define('ResetReturnLink', 'Palaa takaisin kirjautumissivulle');

// Appointments
define('AppointmentAddGuests', 'Lisää osallistujia');
define('AppointmentRemoveGuests', 'Peruuta kokous');
define('AppointmentListEmails', 'Anna sähköpostiosoitteet toisistaan pilkulla erotettuna ja paina Tallenna');
define('AppointmentParticipants', 'Osanottajat');
define('AppointmentRefused', 'Kieltäytyneet');
define('AppointmentAwaitingResponse', 'Odotetaan vastausta');
define('AppointmentInvalidGuestEmail', 'Seuraavat sähköpostiosoitteet ovat virheellisiä:');
define('AppointmentOwner', 'Omistaja');

define('AppointmentMsgTitleInvite', 'Kutsu tapahtumaan.');
define('AppointmentMsgTitleUpdate', 'Tapahtuma oli muutettu.');
define('AppointmentMsgTitleCancel', 'Tapahtuma oli peruttu.');
define('AppointmentMsgTitleRefuse', 'Ystäväsi %guest% on kieltäytynyt kutsusta');
define('AppointmentMoreInfo', 'Lisäinfo');
define('AppointmentOrganizer', 'Järjestäjä');
define('AppointmentEventInformation', 'Tapahtuman info');
define('AppointmentEventWhen', 'Aika');
define('AppointmentEventParticipants', 'Osanottajat');
define('AppointmentEventDescription', 'Kuvaus');
define('AppointmentEventWillYou', 'Osallistutko?');
define('AppointmentAdditionalParameters', 'Lisätiedot');
define('AppointmentHaventRespond', 'Ei vielä vastannut');
define('AppointmentRespondYes', 'Aion osallistua');
define('AppointmentRespondMaybe', 'Ei vielä varma');
define('AppointmentRespondNo', 'Ei osallistu');
define('AppointmentGuestsChangeEvent', 'Asiakkaat voivat vaihtaa tapahtumaa');

define('AppointmentSubjectAddStart', 'Olet saanut kutsun tapahtumaan ');
define('AppointmentSubjectAddFrom', ' Lähettäjä ');
define('AppointmentSubjectUpdateStart', 'Tapahtuman muuttaminen ');
define('AppointmentSubjectDeleteStart', 'Tapahtuman peruuttaminen ');
define('ErrorAppointmentChangeRespond', 'Tapahtuman vastausta ei voi muuttaa');
define('SettingsAutoAddInvitation', 'Lisää kutsuja  kalenteriin automaattisesti');
define('ReportEventSaved', 'Tapahtuma tallennettu');
define('ReportAppointmentSaved', ' kutsut lähetetty');
define('ErrorAppointmentSend', 'Kutsuja ei voi lähettää.');
define('AppointmentEventName', 'Nimi:');

// End appointments

define('ErrorCantUpdateFilters', 'Suodattimia ei voida päivittää');

define('FilterPhrase', 'Jos %field-kenttä %condition %string niin %action');
define('FiltersAdd', 'Lisää suodatin');
define('FiltersCondEqualTo', 'yhtä');
define('FiltersCondContainSubstr', 'sisältää tekstin');
define('FiltersCondNotContainSubstr', 'ei sisällä tekstiä');
define('FiltersActionDelete', 'poista viesti');
define('FiltersActionMove', 'siirrä');
define('FiltersActionToFolder', 'kansioon %folder');
define('FiltersNo', 'Suodattimia ei ole määritelty vielä');

define('ReminderEmailFriendly', 'muistutus');
define('ReminderEventBegin', 'Alkaa: ');

define('FiltersLoading', 'Suodattimia luodaan...');
define('ConfirmMessagesPermanentlyDeleted', 'Kansion kaikki viestit poistetaan.');

define('InfoNoNewMessages', 'Ei uusia viestejä.');
define('TitleImportContacts', 'Tuo yhteystietoja');
define('TitleSelectedContacts', 'Valitse yhteystietoja');
define('TitleNewContact', 'Uusi yhteystieto');
define('TitleViewContact', 'Näytä yhteystieto');
define('TitleEditContact', 'Muokkaa yhteystietoa');
define('TitleNewGroup', 'Uusi ryhmä');
define('TitleViewGroup', 'Näytä ryhmä');

define('AttachmentComplete', 'valmis.');

define('TestButton', 'TESTI');
define('AutoCheckMailIntervalLabel', 'Saapuneiden tarkistuksen aikaväli');
define('AutoCheckMailIntervalDisableName', 'Poista');

define('ReportCalendarSaved', 'Kalenteri tallennettu.');

define('ContactSyncError', 'synkronointi epäonnistui');
define('ReportContactSyncDone', 'Synkronointi valmis');

define('MobileSyncUrlTitle', 'Mobile synkr URL');
define('MobileSyncLoginTitle', 'Mobile synkr login');

define('QuickReply', 'Pikavastaus');
define('SwitchToFullForm', 'Siirry viestin kirjoitukseen');
define('SortFieldDate', 'Pvm');
define('SortFieldFrom', 'Lähettäjä');
define('SortFieldSize', 'Koko');
define('SortFieldSubject', 'Aihe');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Liitteet');
define('SortOrderAscending', 'Nouseva');
define('SortOrderDescending', 'Laskeva');
define('ArrangedBy', 'Järjestetty');

define('MessagePaneToRight', 'Viesti näkyy viestilistan oikealla puolella, eikä alapuolella');

define('SettingsTabMobileSync', 'Mobile');

define('MobileSyncContactDataBaseTitle', 'Mobile sync contact database');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync calendar database');
define('MobileSyncTitleText', 'If you\'d like to synchronize your SyncML-enabled handheld device with WebMail, you can use these parameters.<br />"Mobile Sync URL" specifies path to SyncML Data Synchronization server, "Mobile Sync Login" is your login on SyncML Data Synchronization Server and use your own password upon request. Also, some devices need to specify database name for contact and calendar data.<br />Use "Mobile sync contact database" and "Mobile sync calendar database" respectively.');
define('MobileSyncEnableLabel', 'Mahdollista mobile sync');

define('SearchInputText', 'Haku');

define('AppointmentEmailExplanation','Tämä viesti on tullut sinulle %EMAIL% , koska  %ORGANAZER% on kutsunut sinut tapahtumaan');

define('Searching', 'hakee&hellip;');

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
