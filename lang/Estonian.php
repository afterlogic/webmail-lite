<?php
// Translated by Andres Aule
// Version 2011-01-20

define('PROC_ERROR_ACCT_CREATE', 'Konto loomisel tekkis viga.');
define('PROC_WRONG_ACCT_PWD', 'Vale parool');
define('PROC_CANT_LOG_NONDEF', 'Mitte-vaikekontole ei saa sisse logida.');
define('PROC_CANT_INS_NEW_FILTER', 'Uut filtrit ei õnnestunud lisada.');
define('PROC_FOLDER_EXIST', 'Sellenimeline kataloog on juba olemas.');
define('PROC_CANT_CREATE_FLD', 'Kataloogi ei õnnestunud luua.');
define('PROC_CANT_INS_NEW_GROUP', 'Uut rühma ei õnnestunud lisada.');
define('PROC_CANT_INS_NEW_CONT', 'Uut kontakti ei õnnestunud lisada.');
define('PROC_CANT_INS_NEW_CONTS', 'Kontakti(de) lisamine ei õnnestunud.');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Kontakti(de) lisamine rühma ei õnnestunud.');
define('PROC_ERROR_ACCT_UPDATE', 'Konto andmete uuendamisel tekkis viga.');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Kontaktide seadete uuendamisel tekkis viga.');
define('PROC_CANT_GET_SETTINGS', 'Seadeid ei õnnestunud serverist saada.');
define('PROC_CANT_UPDATE_ACCT', 'Konto andmeid ei õnnestunud uuendada.');
define('PROC_ERROR_DEL_FLD', 'Kataloogi(de) kustutamine ei õnnestunud.');
define('PROC_CANT_UPDATE_CONT', 'Kontakti andmeid ei õnnestunud uuendada.');
define('PROC_CANT_GET_FLDS', 'Kataloogipuud ei õnnestunud serverist saada.');
define('PROC_CANT_GET_MSG_LIST', 'Kirjade loetelu ei õnnestunud serverist saada.');
define('PROC_MSG_HAS_DELETED', 'See kiri on postiserverist juba kustutatud.');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Kontaktide seadeid ei õnnestunud serverist saada.');
define('PROC_CANT_LOAD_SIGNATURE', 'Konto signatuuri ei õnnestunud laadida.');
define('PROC_CANT_GET_CONT_FROM_DB', 'Kontakti ei õnnestunud andmebaasist laadida.');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Kontakti(de) laadimine andmebaasist ei õnnestunud.');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Kontot ei õnnestunud kustutada.');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Filtrit ei õnnestunud kustutada.');
define('PROC_CANT_DEL_CONT_GROUPS', 'Kontakti(de) ja/või rühma(de) kustutamine ei õnnestunud.');
define('PROC_WRONG_ACCT_ACCESS', 'Katse loata siseneda teise kasutaja kontole');
define('PROC_SESSION_ERROR', 'Eelmine sessioon lõppes aegumise tõttu.');

define('MailBoxIsFull', 'Postkast on täis.');
define('WebMailException', 'Serverisisene viga. Palun teatage probleemist administraatorile.');
define('InvalidUid', 'Vigane kirjaidentifikaator');
define('CantCreateContactGroup', 'Kontaktide rühma ei õnnestunud luua.');
define('CantCreateUser', 'Kasutajat ei õnnestunud luua.');
define('CantCreateAccount', 'Kontot ei õnnestunud luua.');
define('SessionIsEmpty', 'Sessioon on tühi.');
define('FileIsTooBig', 'Fail on liiga suur.');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Kõikide kirjade loetuks märkimine ei õnnestunud.');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Kõikide kirjade lugemata kirjadeks märkimine ei õnnestunud.');
define('PROC_CANT_PURGE_MSGS', 'Kirja(de) tühjendamine ei õnnestunud.');
define('PROC_CANT_DEL_MSGS', 'Kirja(de) kustutamine ei õnnestunud.');
define('PROC_CANT_UNDEL_MSGS', 'Kirja(de) taastamine ei õnnestunud.');
define('PROC_CANT_MARK_MSGS_READ', 'Kirja(de) loetuks märkimine ei õnnestunud.');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Kirja(de) märkimine lugemata kirja(de)ks ei õnnestunud.');
define('PROC_CANT_SET_MSG_FLAGS', 'Kirja(de) tähistamine ei õnnestunud.');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Kirja(de)lt tähis(t)e eemaldamine ei õnnestunud.');
define('PROC_CANT_CHANGE_MSG_FLD', 'Kirja(de) kataloogi muutmine ei õnnestunud.');
define('PROC_CANT_SEND_MSG', 'Kirja ei õnnestunud saata.');
define('PROC_CANT_SAVE_MSG', 'Kirja ei õnnestunud salvestada.');
define('PROC_CANT_GET_ACCT_LIST', 'Kontode loetelu ei õnnestunud serverist saada.');
define('PROC_CANT_GET_FILTER_LIST', 'Filtrite loetelu ei õnnestunud serverist saada.');

define('PROC_CANT_LEAVE_BLANK', 'Märgiga * välju ei tohi tühjaks jätta.');

define('PROC_CANT_UPD_FLD', 'Kataloogi ei õnnestunud uuendada.');
define('PROC_CANT_UPD_FILTER', 'Filtrit ei õnnestunud uuendada.');

define('ACCT_CANT_ADD_DEF_ACCT', 'Seda kontot ei saa lisada, sest see on teise kasutaja oma.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Seda kontostaatust ei saa vaikestaatuseks muuta.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Uut kontot ei õnnestunud luua (viga IMAP4-ühenduses.');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Viimatist vaikekontot ei õnnestunud kustutada.');

define('LANG_LoginInfo', 'Sisselogimine');
define('LANG_Email', 'E-post');
define('LANG_Login', 'Kasutajatunnus');
define('LANG_Password', 'Parool');
define('LANG_IncServer', 'Saabuv&nbsp;post');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'Väljuv&nbsp;post');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Kasutatakse&nbsp;SMTP&nbsp;autentimist');
define('LANG_SignMe', 'Automaatne sisselogimine');
define('LANG_Enter', 'Sisenen');

// interface strings

define('JS_LANG_TitleLogin', 'Kasutajatunnus');
define('JS_LANG_TitleMessagesListView', 'Kirjade loetelu');
define('JS_LANG_TitleMessagesList', 'Kirjade loetelu');
define('JS_LANG_TitleViewMessage', 'Kirja vaatamine');
define('JS_LANG_TitleNewMessage', 'Uus kiri');
define('JS_LANG_TitleSettings', 'Seaded');
define('JS_LANG_TitleContacts', 'Kontaktid');

define('JS_LANG_StandardLogin', 'Tavaline&nbsp;sisselogimine');
define('JS_LANG_AdvancedLogin', 'Täpsemate&nbsp;seadetega&nbsp;sisselogimine');

define('JS_LANG_InfoWebMailLoading', 'WebMail käivitub&hellip;');
define('JS_LANG_Loading', 'Laadimine&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Kirjade loetelu laadimine');
define('JS_LANG_InfoEmptyFolder', 'See kataloog on tühi.');
define('JS_LANG_InfoPageLoading', 'Lehekülg on jätkuvalt laadimisel&hellip;');
define('JS_LANG_InfoSendMessage', 'Kiri on saadetud.');
define('JS_LANG_InfoSaveMessage', 'Kiri on salvestatud.');
define('JS_LANG_InfoHaveImported', 'Olete importinud');
define('JS_LANG_InfoNewContacts', 'uut kontakti.');
define('JS_LANG_InfoToDelete', 'Kataloogi ');
define('JS_LANG_InfoDeleteContent', 'kustutamiseks tuleb kõigepealt kustutada kogu selle kataloogi sisu.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Tühjendamata katalooge ei ole lubatud kustutada. Sellise kataloogi kustutamiseks kustutage kõigepealt selle sisu.');
define('JS_LANG_InfoRequiredFields', '* Tärniga tähistatud väljad peavad olema täidetud.');

define('JS_LANG_ConfirmAreYouSure', 'Kas olete kindel?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Valitud kiri/kirjad kustutatakse JÄÄDAVALT. Kas soovite seda teha?');
define('JS_LANG_ConfirmSaveSettings', 'Seaded ei ole veel salvestatud. Salvestamiseks valige OK.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Kontaktide seaded ei ole veel salvestatud. Salvestamiseks valige OK.');
define('JS_LANG_ConfirmSaveAcctProp', 'Konto seaded ei ole veel salvestatud. Salvestamiseks valige OK.');
define('JS_LANG_ConfirmSaveFilter', 'Filtrite seaded ei ole veel salvestatud. Salvestamiseks valige OK.');
define('JS_LANG_ConfirmSaveSignature', 'Signatuur ei ole veel salvestatud. Salvestamiseks valige OK.');
define('JS_LANG_ConfirmSavefolders', 'Kataloogid ei ole veel ei salvestatud. Salvestamiseks valige OK.');
define('JS_LANG_ConfirmHtmlToPlain', 'Hoiatus: Lihttekstis kirjal ei ole erivorminguid (värvi, šrifti, kursiivi jne.). Jätkamiseks valige OK.');
define('JS_LANG_ConfirmAddFolder', 'Enne kataloogi lisamist/kustutamist tuleb muudatused rakendada. Salvestamiseks valige OK.');
define('JS_LANG_ConfirmEmptySubject', 'Teemaväli on tühi. Kas soovite jätkata?');

define('JS_LANG_WarningEmailBlank', 'Aadressivälja<br />ei saa tühjaks jätta.');
define('JS_LANG_WarningLoginBlank', 'Kasutajatunnuse<br />välja ei saa tühjaks jätta.');
define('JS_LANG_WarningToBlank', 'Adressaadivälja ei saa tühjaks jätta.');
define('JS_LANG_WarningServerPortBlank', 'POP3- ja SMTP-serveri ja -portide<br />välju ei saa tühjaks jätta.');
define('JS_LANG_WarningEmptySearchLine', 'Otsingurida on tühi. Sisestage otsitav tekst.');
define('JS_LANG_WarningMarkListItem', 'Valige loetelust vähemalt üks rida.');
define('JS_LANG_WarningFolderMove', 'Kataloogi asukohta ei saa muuta, sest see on teine tasand.');
define('JS_LANG_WarningContactNotComplete', 'Please enter email or name.');
define('JS_LANG_WarningGroupNotComplete', 'Sisestage rühma nimi.');

define('JS_LANG_WarningEmailFieldBlank', 'Aadressivälja ei saa tühjaks jätta.');
define('JS_LANG_WarningIncServerBlank', 'POP3- (või IMAP4)-serveri välja ei saa tühjaks jätta.');
define('JS_LANG_WarningIncPortBlank', 'POP3- (või IMAP4)-pordi välja ei saa tühjaks jätta.');
define('JS_LANG_WarningIncLoginBlank', 'POP3- (või IMAP4)-serveri kasutajatunnuse välja ei saa tühjaks jätta.');
define('JS_LANG_WarningIncPortNumber', 'POP3- (või IMAP4)-pordi väljale tuleks kirjutada positiivne täisarv.');
define('JS_LANG_DefaultIncPortNumber', 'POP3- (või IMAP4)-port on vaikimisi 110 (143).');
define('JS_LANG_WarningIncPassBlank', 'POP3- (või IMAP4)-serveri parooli välja ei saa tühjaks jätta.');
define('JS_LANG_WarningOutPortBlank', 'SMTP-pordi välja ei saa tühjaks jätta.');
define('JS_LANG_WarningOutPortNumber', 'SMTP-pordi väljale tuleks kirjutada positiivne täisarv.');
define('JS_LANG_WarningCorrectEmail', 'Märkige õige e-postiaadress.');
define('JS_LANG_DefaultOutPortNumber', 'SMTP-port on vaikimisi 25.');

define('JS_LANG_WarningCsvExtention', 'Faili laiend peab olema .csv.');
define('JS_LANG_WarningImportFileType', 'Valige rakendus, kust soovite kontakte kopeerida.');
define('JS_LANG_WarningEmptyImportFile', 'Faili valimiseks kasutage valikunuppu.');

define('JS_LANG_WarningContactsPerPage', 'Kontaktide arv lehel peab olema positiivne täisarv.');
define('JS_LANG_WarningMessagesPerPage', 'Kirjade arv lehel peab olema positiivne täisarv.');
define('JS_LANG_WarningMailsOnServerDays', 'Kirjade serveris alleshoidmise päevade arv peab olema positiivne täisarv.');
define('JS_LANG_WarningEmptyFilter', 'Sisestage tekst');
define('JS_LANG_WarningEmptyFolderName', 'Sisestage kataloogi nimi');

define('JS_LANG_ErrorConnectionFailed', 'Ühendumine ei õnnestunud.');
define('JS_LANG_ErrorRequestFailed', 'Andmeedastust ei õnnestunud lõpule viia.');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Objekt XMLHttpRequest puudub.');
define('JS_LANG_ErrorWithoutDesc', 'Tekkis viga, mille kohta kirjeldus puudub.');
define('JS_LANG_ErrorParsing', 'XMLi parsimisel tekkis viga.');
define('JS_LANG_ResponseText', 'Vastus:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Tühi XML-pakett');
define('JS_LANG_ErrorImportContacts', 'Viga kontaktide importimisel');
define('JS_LANG_ErrorNoContacts', 'Pole kontakte, mida importida.');
define('JS_LANG_ErrorCheckMail', 'Kirjade vastuvõtt katkes vea tõttu. Tõenäoliselt ei õnnestunud kõiki kirju vastu võtta.');

define('JS_LANG_LoggingToServer', 'Sisselogimine&hellip;');
define('JS_LANG_GettingMsgsNum', 'Kirjade arvu laadimine');
define('JS_LANG_RetrievingMessage', 'Kirja tõmbamine');
define('JS_LANG_DeletingMessage', 'Kirja kustutamine');
define('JS_LANG_DeletingMessages', 'Kirja(de) kustutamine');
define('JS_LANG_Of', ' ');
define('JS_LANG_Connection', 'Ühendus');
define('JS_LANG_Charset', 'Tähestik');
define('JS_LANG_AutoSelect', 'Automaatne');

define('JS_LANG_Contacts', 'Kontaktid');
define('JS_LANG_ClassicVersion', 'Klassikaline versioon');
define('JS_LANG_Logout', 'Väljun');
define('JS_LANG_Settings', 'Seaded');

define('JS_LANG_LookFor', 'Otsitav tekst: ');
define('JS_LANG_SearchIn', 'Otsitava asukoht: ');
define('JS_LANG_QuickSearch', 'Otsing ainult saatjatest, adressaatidest ja teemadest (kiirem)');
define('JS_LANG_SlowSearch', 'Otsing kirjadest tervikuna');
define('JS_LANG_AllMailFolders', 'Kõik postikataloogid');
define('JS_LANG_AllGroups', 'Kõik rühmad');

define('JS_LANG_NewMessage', 'Uus kiri');
define('JS_LANG_CheckMail', 'Tõmba uued kirjad');
define('JS_LANG_EmptyTrash', 'Tühjenda prügikast');
define('JS_LANG_MarkAsRead', 'Märgi loetuks');
define('JS_LANG_MarkAsUnread', 'Märgi lugemata kirja(de)ks');
define('JS_LANG_MarkFlag', 'Tähista');
define('JS_LANG_MarkUnflag', 'Eemalda tähis(ed)');
define('JS_LANG_MarkAllRead', 'Märgi kõik loetuks');
define('JS_LANG_MarkAllUnread', 'Märgi kõik lugemata kirjadeks');
define('JS_LANG_Reply', 'Vastus');
define('JS_LANG_ReplyAll', 'Vastus kõigile');
define('JS_LANG_Delete', 'Kustuta');
define('JS_LANG_Undelete', 'Taasta');
define('JS_LANG_PurgeDeleted', 'Tühjenda kustutatud kirjad');
define('JS_LANG_MoveToFolder', 'Tõsta teise kataloogi');
define('JS_LANG_Forward', 'Edasta');

define('JS_LANG_HideFolders', 'Peida kataloogid');
define('JS_LANG_ShowFolders', 'Näita katalooge');
define('JS_LANG_ManageFolders', 'Kataloogide haldamine');
define('JS_LANG_SyncFolder', 'Sünkroniseeritud kataloog');
define('JS_LANG_NewMessages', 'Uued kirjad');
define('JS_LANG_Messages', '(kirjade arv)');

define('JS_LANG_From', 'Saatja');
define('JS_LANG_To', 'Adressaat');
define('JS_LANG_Date', 'Kuupäev');
define('JS_LANG_Size', 'Suurus');
define('JS_LANG_Subject', 'Teema');

define('JS_LANG_FirstPage', 'Esimene');
define('JS_LANG_PreviousPage', 'Eelmine');
define('JS_LANG_NextPage', 'Järgmine');
define('JS_LANG_LastPage', 'Viimane');

define('JS_LANG_SwitchToPlain', 'Plaintext-vaaterežiim');
define('JS_LANG_SwitchToHTML', 'HTML-vaaterežiim');
define('JS_LANG_AddToAddressBook', 'Lisa kontaktidesse');
define('JS_LANG_ClickToDownload', 'Allalaadimiseks klõpsake');
define('JS_LANG_View', 'Vaade');
define('JS_LANG_ShowFullHeaders', 'Näita kirjade päiseid täielikult');
define('JS_LANG_HideFullHeaders', 'Ära näita kirjade päiseid täielikult');

define('JS_LANG_MessagesInFolder', 'kirja/kiri (selles kataloogis)');
define('JS_LANG_YouUsing', 'Kirjade maht postkastis on');
define('JS_LANG_OfYour', 'lubatud mahust, milleks on');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Saada');
define('JS_LANG_SaveMessage', 'Salvesta');
define('JS_LANG_Print', 'Prindi');
define('JS_LANG_PreviousMsg', 'Eelmine kiri');
define('JS_LANG_NextMsg', 'Järgmine kiri');
define('JS_LANG_AddressBook', 'Aadressiraamat');
define('JS_LANG_ShowBCC', 'Näita pimekoopiavälja (BCC)');
define('JS_LANG_HideBCC', 'Peida pimekoopiaväli');
define('JS_LANG_CC', 'Koopia');
define('JS_LANG_BCC', 'Pimekoopia');
define('JS_LANG_ReplyTo', 'Vastuse&nbsp;adressaat');
define('JS_LANG_AttachFile', 'Faili lisamine kirjale');
define('JS_LANG_Attach', 'Lisan');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Originaalkiri');
define('JS_LANG_Sent', 'Saadetud');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Vähetähtis');
define('JS_LANG_Normal', 'Tavaline');
define('JS_LANG_High', 'Tähtis');
define('JS_LANG_Importance', 'Tähtsus');
define('JS_LANG_Close', 'Sulgen');

define('JS_LANG_Common', 'Üldseaded');
define('JS_LANG_EmailAccounts', 'E-postikontod');

define('JS_LANG_MsgsPerPage', 'Kirjade arv lehel');
define('JS_LANG_DisableRTE', 'Erivorminguredaktorit ei kasutata (kirjad kirjutatakse vormindamata tekstis)');
define('JS_LANG_Skin', 'Välimus');
define('JS_LANG_DefCharset', 'Vaikimisi kasutatav tähestik');
define('JS_LANG_DefCharsetInc', 'Vaikimisi tähestik saabuvate kirjade jaoks');
define('JS_LANG_DefCharsetOut', 'Vaikimisi tähestik saadetavate kirjade jaoks');
define('JS_LANG_DefTimeOffset', 'Ajavöönd');
define('JS_LANG_DefLanguage', 'Keel');
define('JS_LANG_DefDateFormat', 'Vaikimisi kasutatav kuupäevavorming');
define('JS_LANG_ShowViewPane', 'Kirjade loetelu koos eelvaatekastiga');
define('JS_LANG_Save', 'Salvesta');
define('JS_LANG_Cancel', 'Loobun');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Kustuta');
define('JS_LANG_AddNewAccount', 'Uue konto lisamine');
define('JS_LANG_Signature', 'Signatuur');
define('JS_LANG_Filters', 'Filtrid');
define('JS_LANG_Properties', 'Seaded');
define('JS_LANG_UseForLogin', 'Kasutan sisselogimiseks selle konto seadeid (kasutajatunnust ja parooli)');
define('JS_LANG_MailFriendlyName', 'Teie pärisnimi');
define('JS_LANG_MailEmail', 'E-post');
define('JS_LANG_MailIncHost', 'Saabuv post');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Kasutajatunnus');
define('JS_LANG_MailIncPass', 'Parool');
define('JS_LANG_MailOutHost', 'Väljuv post ehk SMTP');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'Kasutajatunnus väljuva posti ehk SMTP serveris');
define('JS_LANG_MailOutPass', 'Parool väljuva posti ehk SMTP serveris');
define('JS_LANG_MailOutAuth1', 'Kasutatakse SMTP autentimist');
define('JS_LANG_MailOutAuth2', '(Võite väljuva posti serveri kasutajatunnuse ja parooli siia kirjutamata jätta, kui need on samad mis saabuva posti serveris.)');
define('JS_LANG_UseFriendlyNm1', 'Adressaadiväljal näidatakse ka inimeste nimesid');
define('JS_LANG_UseFriendlyNm2', '(Nt. Madis Tamm &lt;madis.tamm@mail.ee&gt;)');
define('JS_LANG_GetmailAtLogin', 'Sisselogimisel tõmmatakse uued kirjad ja saadetakse saatmata kirjad');
define('JS_LANG_MailMode0', 'Vastuvõetud kirjad kustutatakse serverist');
define('JS_LANG_MailMode1', 'Kirjad jäävad serverisse alles');
define('JS_LANG_MailMode2', 'Kirju hoitakse serveris alles');
define('JS_LANG_MailsOnServerDays', 'päev(a)');
define('JS_LANG_MailMode3', 'Vastuvõetud kirjad kustutatakse serverist ära siis, kui mina need oma prügikastist kustutan');
define('JS_LANG_InboxSyncType', 'Postkasti sünkroniseerimise moodus');

define('JS_LANG_SyncTypeNo', 'Ei sünkroniseerita');
define('JS_LANG_SyncTypeNewHeaders', 'Uute kirjade päised');
define('JS_LANG_SyncTypeAllHeaders', 'Kõikide kirjade päised');
define('JS_LANG_SyncTypeNewMessages', 'Uued kirjad');
define('JS_LANG_SyncTypeAllMessages', 'Kõik kirjad');
define('JS_LANG_SyncTypeDirectMode', 'Otserežiim');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Ainult päised');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Kirjad tervikuna');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Otserežiim');

define('JS_LANG_DeleteFromDb', 'Kiri kustutatakse andmebaasist, kui seda kirja enam serveris ei ole');

define('JS_LANG_EditFilter', 'Filtri&nbsp;redigeerimine');
define('JS_LANG_NewFilter', 'Uus filter');
define('JS_LANG_Field', 'Väli');
define('JS_LANG_Condition', 'Tingimus');
define('JS_LANG_ContainSubstring', 'sisaldab teksti');
define('JS_LANG_ContainExactPhrase', 'sisaldab fraasi');
define('JS_LANG_NotContainSubstring', 'ei sisalda teksti');
define('JS_LANG_FilterDesc_At', '');
define('JS_LANG_FilterDesc_Field', 'väljal');
define('JS_LANG_Action', 'Toiming');
define('JS_LANG_DoNothing', 'ära tee midagi');
define('JS_LANG_DeleteFromServer', 'kustuta kiri kohe serverist');
define('JS_LANG_MarkGrey', 'märgi halliks');
define('JS_LANG_Add', 'Lisa');
define('JS_LANG_OtherFilterSettings', 'Muud filtriseaded');
define('JS_LANG_ConsiderXSpam', 'Arvesta X-Spami päiseid');
define('JS_LANG_Apply', 'Rakenda');

define('JS_LANG_InsertLink', 'Lisan lingi');
define('JS_LANG_RemoveLink', 'Eemaldan lingi');
define('JS_LANG_Numbering', 'Numbrid');
define('JS_LANG_Bullets', 'Täpid');
define('JS_LANG_HorizontalLine', 'Horisontaaljoon');
define('JS_LANG_Bold', 'Poolpaks kiri');
define('JS_LANG_Italic', 'Kursiivkiri');
define('JS_LANG_Underline', 'Valitud teksti allajoonimine');
define('JS_LANG_AlignLeft', 'Vasakjoondus');
define('JS_LANG_Center', 'Keskjoondus');
define('JS_LANG_AlignRight', 'Paremjoondus');
define('JS_LANG_Justify', 'Rööpjoondus');
define('JS_LANG_FontColor', 'Šrifti värvus');
define('JS_LANG_Background', 'Taust');
define('JS_LANG_SwitchToPlainMode', 'Redigeerimine lihttekstis (<i>plaintext</i>)');
define('JS_LANG_SwitchToHTMLMode', 'Redigeerimine vormindatud tekstis (HTML)');

define('JS_LANG_Folder', 'Kataloogi nimi');
define('JS_LANG_Msgs', 'Kirjade arv');
define('JS_LANG_Synchronize', 'Sünkroniseerimine');
define('JS_LANG_ShowThisFolder', 'Seda kataloogi näidatakse');
define('JS_LANG_Total', 'Kokku');
define('JS_LANG_DeleteSelected', 'Kustuta valitud');
define('JS_LANG_AddNewFolder', 'Uue kataloogi loomine');
define('JS_LANG_NewFolder', 'Uus kataloog');
define('JS_LANG_ParentFolder', 'Emakataloog');
define('JS_LANG_NoParent', 'Emakataloogi ei ole');
define('JS_LANG_FolderName', 'Kataloogi nimi');

define('JS_LANG_ContactsPerPage', 'Kontaktide arv lehel');
define('JS_LANG_WhiteList', 'Aadressraamatus olijate kirju rämpspostiks ei loeta');

define('JS_LANG_CharsetDefault', 'Vaikimisi');
define('JS_LANG_CharsetArabicAlphabetISO', 'araabia (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'araabia (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Läti ja Leedu (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Eesti jt. Baltimaad (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Kesk-Euroopa (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Kesk-Euroopa (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'hiina lihtsustatud (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'hiina lihtsustatud (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'hiina traditsiooniline (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'kirillitsa (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'kirillitsa (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'kirillitsa (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'kreeka (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'kreeka (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'heebrea (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'heebrea (Windows)');
define('JS_LANG_CharsetJapanese', 'jaapani');
define('JS_LANG_CharsetJapaneseShiftJIS', 'jaapani (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'korea (EUC)');
define('JS_LANG_CharsetKoreanISO', 'korea (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latin 3 (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'türgi');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'universaalne (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'universaalne (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'vietnami (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Western (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Western (Windows)');

define('JS_LANG_TimeDefault', 'Vaikimisi');
define('JS_LANG_TimeEniwetok', 'Eniwetok, Kwajalein, Kuupäevaraja');
define('JS_LANG_TimeMidwayIsland', 'Midway saar, Samoa');
define('JS_LANG_TimeHawaii', 'Hawaii');
define('JS_LANG_TimeAlaska', 'Alaska');
define('JS_LANG_TimePacific', 'Pacific Time (USA ja Kanada); Tijuana');
define('JS_LANG_TimeArizona', 'Arizona');
define('JS_LANG_TimeMountain', 'Mountain Time (USA ja Kanada)');
define('JS_LANG_TimeCentralAmerica', 'Kesk-Ameerika');
define('JS_LANG_TimeCentral', 'Central Time (USA ja Kanada)');
define('JS_LANG_TimeMexicoCity', 'México, Tegucigalpa');
define('JS_LANG_TimeSaskatchewan', 'Saskatchewan');
define('JS_LANG_TimeIndiana', 'Indiana (East) (USA)');
define('JS_LANG_TimeEastern', 'Eastern Time (USA ja Kanada)');
define('JS_LANG_TimeBogota', 'Bogotá, Lima, Quito');
define('JS_LANG_TimeSantiago', 'Santiago');
define('JS_LANG_TimeCaracas', 'Caracas, La Paz');
define('JS_LANG_TimeAtlanticCanada', 'Atlantic Time (Kanada)');
define('JS_LANG_TimeNewfoundland', 'Newfoundland');
define('JS_LANG_TimeGreenland', 'Gröönimaa');
define('JS_LANG_TimeBuenosAires', 'Buenos Aires, Georgetown');
define('JS_LANG_TimeBrasilia', 'Brasília');
define('JS_LANG_TimeMidAtlantic', 'Kesk-Atlandi');
define('JS_LANG_TimeCapeVerde', 'Roheneemesaared');
define('JS_LANG_TimeAzores', 'Assoorid');
define('JS_LANG_TimeMonrovia', 'Casablanca, Monrovia');
define('JS_LANG_TimeGMT', 'Dublin, Edinburgh, Lissabon, London');
define('JS_LANG_TimeBerlin', 'Amsterdam, Berliin, Bern, Rooma, Stockholm, Viin');
define('JS_LANG_TimePrague', 'Belgrad, Bratislava, Budapest, Ljubljana, Praha');
define('JS_LANG_TimeParis', 'Brüssel, Kopenhaagen, Madrid, Pariis');
define('JS_LANG_TimeSarajevo', 'Sarajevo, Skopje, Sofia, Varssavi, Zagreb');
define('JS_LANG_TimeWestCentralAfrica', 'Lääne-Kesk-Aafrika');
define('JS_LANG_TimeAthens', 'Ateena, Istanbul, Minsk');
define('JS_LANG_TimeEasternEurope', 'Bukarest');
define('JS_LANG_TimeCairo', 'Kairo');
define('JS_LANG_TimeHarare', 'Harare, Pretoria');
define('JS_LANG_TimeHelsinki', 'Helsingi, Riia, Tallinn, Vilnius');
define('JS_LANG_TimeIsrael', 'Iisrael, Jeruusalemma standardaeg');
define('JS_LANG_TimeBaghdad', 'Bagdad');
define('JS_LANG_TimeArab', 'Araabia poolsaar, Kuveit, Ar-Riyād');
define('JS_LANG_TimeMoscow', 'Moskva, Peterburi, Volgograd');
define('JS_LANG_TimeEastAfrica', 'Ida-Aafrika, Nairobi');
define('JS_LANG_TimeTehran', 'Teheran');
define('JS_LANG_TimeAbuDhabi', 'Abu Dhabi, Masqat');
define('JS_LANG_TimeCaucasus', 'Bakuu, Tbilisi, Jerevan');
define('JS_LANG_TimeKabul', 'Kabul');
define('JS_LANG_TimeEkaterinburg', 'Jekaterinburg');
define('JS_LANG_TimeIslamabad', 'Islamabad, Karachi, Sverdlovsk, Taškent');
define('JS_LANG_TimeBombay', 'Kolkata, Chennai, Mumbai, New Delhi, India standardaeg');
define('JS_LANG_TimeNepal', 'Katmandu, Nepaal');
define('JS_LANG_TimeAlmaty', 'Almatõ, Põhja-Kesk-Aasia');
define('JS_LANG_TimeDhaka', 'Astana, Dhaka');
define('JS_LANG_TimeSriLanka', 'Sri Jayawardenepura, Sri Lanka');
define('JS_LANG_TimeRangoon', 'Rangoon');
define('JS_LANG_TimeBangkok', 'Bangkok, Novosibirsk, Hanoi, Jakarta');
define('JS_LANG_TimeKrasnoyarsk', 'Krasnojarsk');
define('JS_LANG_TimeBeijing', 'Peking, Chongqing, Hongkong, Urumqi');
define('JS_LANG_TimeUlaanBataar', 'Ulaanbaatar');
define('JS_LANG_TimeSingapore', 'Kuala Lumpur, Singapur');
define('JS_LANG_TimePerth', 'Perth, Lääne-Australia');
define('JS_LANG_TimeTaipei', 'Taipei');
define('JS_LANG_TimeTokyo', 'Ōsaka, Sapporo, Tōkyō, Irkutsk');
define('JS_LANG_TimeSeoul', 'Sŏul, Korea standardaeg');
define('JS_LANG_TimeYakutsk', 'Jakutsk');
define('JS_LANG_TimeAdelaide', 'Adelaide, Kesk-Australia');
define('JS_LANG_TimeDarwin', 'Darwin');
define('JS_LANG_TimeBrisbane', 'Brisbane, Ida-Australia');
define('JS_LANG_TimeSydney', 'Canberra, Melbourne, Sydney, Hobart');
define('JS_LANG_TimeGuam', 'Guam, Port Moresby');
define('JS_LANG_TimeHobart', 'Hobart, Tasmaania');
define('JS_LANG_TimeVladivostock', 'Vladivostok');
define('JS_LANG_TimeSolomonIs', 'Saalomoni saared, Uus-Kaledonia');
define('JS_LANG_TimeWellington', 'Auckland, Wellington, Magadan');
define('JS_LANG_TimeFiji', 'Fidži saared, Kamtšatka, Marshalli saared');
define('JS_LANG_TimeTonga', 'Nuku\'alofa, Tonga');

define('JS_LANG_DateDefault', 'Vaikimisi');
define('JS_LANG_DateDDMMYY', 'PP/KK/AA');
define('JS_LANG_DateMMDDYY', 'KK/PP/AA');
define('JS_LANG_DateDDMonth', 'PP kuu (01 jaanuar)');
define('JS_LANG_DateAdvanced', 'Täpsem');

define('JS_LANG_NewContact', 'Uus kontakt');
define('JS_LANG_NewGroup', 'Uus rühm');
define('JS_LANG_AddContactsTo', 'Lisa kontaktid rühma');
define('JS_LANG_ImportContacts', 'Kontaktide import');

define('JS_LANG_Name', 'Nimi');
define('JS_LANG_Email', 'E-post');
define('JS_LANG_DefaultEmail', 'E-post');
define('JS_LANG_NotSpecifiedYet', 'veel määramata');
define('JS_LANG_ContactName', 'Nimi');
define('JS_LANG_Birthday', 'Sünnipäev');
define('JS_LANG_Month', 'Kuu');
define('JS_LANG_January', 'jaanuar');
define('JS_LANG_February', 'veebruar');
define('JS_LANG_March', 'märts');
define('JS_LANG_April', 'aprill');
define('JS_LANG_May', 'mai');
define('JS_LANG_June', 'juuni');
define('JS_LANG_July', 'juuli');
define('JS_LANG_August', 'august');
define('JS_LANG_September', 'september');
define('JS_LANG_October', 'oktoober');
define('JS_LANG_November', 'november');
define('JS_LANG_December', 'detsember');
define('JS_LANG_Day', 'Päev');
define('JS_LANG_Year', 'Aasta');
define('JS_LANG_UseFriendlyName1', 'Kuvatakse ka nimi');
define('JS_LANG_UseFriendlyName2', '(näiteks: Madis Tamm &lt;madis.tamm@mail.ee&gt;)');
define('JS_LANG_Personal', 'Isiklik');
define('JS_LANG_PersonalEmail', 'E-post');
define('JS_LANG_StreetAddress', 'Aadress');
define('JS_LANG_City', 'Linn');
define('JS_LANG_Fax', 'Faks');
define('JS_LANG_StateProvince', 'Maakond');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'Postiindeks');
define('JS_LANG_Mobile', 'Mobiiltelefon');
define('JS_LANG_CountryRegion', 'Riik');
define('JS_LANG_WebPage', 'Veeb');
define('JS_LANG_Go', 'Näita');
define('JS_LANG_Home', 'Isiklik');
define('JS_LANG_Business', 'Töö');
define('JS_LANG_BusinessEmail', 'E-post tööl');
define('JS_LANG_Company', 'Firma');
define('JS_LANG_JobTitle', 'Amet');
define('JS_LANG_Department', 'Osakond');
define('JS_LANG_Office', 'Büroo');
define('JS_LANG_Pager', 'Piipar');
define('JS_LANG_Other', 'Muu');
define('JS_LANG_OtherEmail', 'Muu e-post');
define('JS_LANG_Notes', 'Märkused');
define('JS_LANG_Groups', 'Rühmad');
define('JS_LANG_ShowAddFields', 'Lisaväljad');
define('JS_LANG_HideAddFields', 'Peida lisaväljad');
define('JS_LANG_EditContact', 'Kontakti andmete muutmine');
define('JS_LANG_GroupName', 'Rühma nimi');
define('JS_LANG_AddContacts', 'Kontaktide lisamine');
define('JS_LANG_CommentAddContacts', '(Mitme aadressi korral pange aadresside vahele koma.)');
define('JS_LANG_CreateGroup', 'Loo rühm');
define('JS_LANG_Rename', 'nime muutmine');
define('JS_LANG_MailGroup', 'Kiri rühmale');
define('JS_LANG_RemoveFromGroup', 'Kõrvalda rühmast');
define('JS_LANG_UseImportTo', 'Importida saab kontakte programmidest Microsoft Outlook ja Outlook Express.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Valige imporditav (CSV-vormingus) fail');
define('JS_LANG_Import', 'Impordi');
define('JS_LANG_ContactsMessage', 'See on kontaktide lehekülg!');
define('JS_LANG_ContactsCount', 'kontakt(i)');
define('JS_LANG_GroupsCount', 'rühm(a)');

// webmail 4.1 constants
define('PicturesBlocked', 'Selles kirjas olevad pildid on turvalisuse huvides blokeeritud.');
define('ShowPictures', 'Näita pilte');
define('ShowPicturesFromSender', 'Selle saatja kirjades olevaid pilte näidatakse alati');
define('AlwaysShowPictures', 'Kirjades olevaid pilte näidatakse alati');

define('TreatAsOrganization', 'Seda rühma käsitletakse kui organisatsiooni');

define('WarningGroupAlreadyExist', 'Sellise nimega rühm on juba olemas. Palun valige muu nimi.');
define('WarningCorrectFolderName', 'Kirjutage õige katalooginimi.');
define('WarningLoginFieldBlank', 'Kasutajatunnuse välja ei saa tühjaks jätta.');
define('WarningCorrectLogin', 'Kirjutage õige kasutajatunnus.');
define('WarningPassBlank', 'Paroolivälja ei saa tühjaks jätta.');
define('WarningCorrectIncServer', 'Kirjutage õige POP3- (IMAP4)-serveri aadress.');
define('WarningCorrectSMTPServer', 'Kirjutage õige väljuva posti (SMTP) serveri aadress.');
define('WarningFromBlank', 'Saatja välja ei saa tühjaks jätta.');
define('WarningAdvancedDateFormat', 'Määrake kuupäeva ja kellaaja kuvamise vorming.');

define('AdvancedDateHelpTitle', 'Kuupäevaseaded täpsemalt');
define('AdvancedDateHelpIntro', 'Kui on valitud täpsemad kuupäevaseaded, saate kasutada tekstikasti omaenda kuupäevaformaadi määramiseks WebMail Pro rakenduses. Selleks kasutatakse järgmisi valikuid koos sümboliga \':\' või \'/\' eristav sümbol:');
define('AdvancedDateHelpConclusion', 'Näiteks kui olete määranud tekstikastis &quot;mm/dd/yyyy&quot; kuvatakse kuupäev kujul kuu/kuupäev/aasta (11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Kuupäev (1-31)');
define('AdvancedDateHelpNumericMonth', 'Kuu (1-12)');
define('AdvancedDateHelpTextualMonth', 'Kuu (jaanuar-detsember)');
define('AdvancedDateHelpYear2', 'Aasta kahekohalisena');
define('AdvancedDateHelpYear4', 'Aasta neljakohalisena');
define('AdvancedDateHelpDayOfYear', 'Päeva number aastas (1-366)');
define('AdvancedDateHelpQuarter', 'Kvartal');
define('AdvancedDateHelpDayOfWeek', 'Päev (E-P)');
define('AdvancedDateHelpWeekOfYear', 'Nädala number (1-53)');

define('InfoNoMessagesFound', 'Ühtegi kirja ei õnnestunud leida.');
define('ErrorSMTPConnect', 'Ühendus SMTP-serveriga ebaõnnestus. Kontrollige väljuva posti seadeid.');
define('ErrorSMTPAuth', 'Vale kasutajatunnus ja/või parool. Autentimine ebaõnnestus.');
define('ReportMessageSent', 'Kiri on saadetud.');
define('ReportMessageSaved', 'Kiri on salvestatud.');
define('ErrorPOP3Connect', 'Ühendus POP3-serveriga ebaõnnestus. Kontrollige saabuva posti (POP3) seadeid.');
define('ErrorIMAP4Connect', 'Ühendus IMAP4-serveriga ebaõnnestus. Kontrollige IMAP4 seadeid.');
define('ErrorPOP3IMAP4Auth', 'Vale aadress/kasutajatunnus ja/või parool. Autentimine ebaõnnestus.');
define('ErrorGetMailLimit', 'Teie postkasti lubatud maht on täis. (Kustutage kirju või tõstke postkastist mujale.)');

define('ReportSettingsUpdatedSuccessfuly', 'Seaded on uuendatud.');
define('ReportAccountCreatedSuccessfuly', 'Konto on loodud.');
define('ReportAccountUpdatedSuccessfuly', 'Konto andmed on uuendatud.');
define('ConfirmDeleteAccount', 'Kas kustutada see konto?');
define('ReportFiltersUpdatedSuccessfuly', 'Filtrid on uuendatud.');
define('ReportSignatureUpdatedSuccessfuly', 'Signatuur on uuendatud.');
define('ReportFoldersUpdatedSuccessfuly', 'Kataloogid on uuendatud.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Kontaktide seaded on uuendatud.');

define('ErrorInvalidCSV', 'Valitud CSV-faili vorming on vigane.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Rühm');
define('ReportGroupSuccessfulyAdded2', 'on lisatud.');
define('ReportGroupUpdatedSuccessfuly', 'Rühma andmed on uuendatud.');
define('ReportContactSuccessfulyAdded', 'Kontakt on lisatud.');
define('ReportContactUpdatedSuccessfuly', 'Kontakti andmed on uuendatud.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'kontakt(i) on rühma lisatud');
define('AlertNoContactsGroupsSelected', 'Ühtegi kontakti ega rühma ei ole valitud.');

define('InfoListNotContainAddress', 'Kui nimekirjas ei ole otsitavat aadressi, jätkake selle aadressi esimeste tähtede sisestamist.');

define('DirectAccess', 'O');
define('DirectAccessTitle', 'Otse serveris. WebMail töötab kirjadega otse postiserveris.');

define('FolderInbox', 'Postkast');
define('FolderSentItems', 'Saadetud');
define('FolderDrafts', 'Pooleli');
define('FolderTrash', 'Prügikast');

define('FileLargerAttachment', 'Üleslaaditav fail on lubatust suurem.');
define('FilePartiallyUploaded', 'Tundmatu vea tõttu õnnestus üles laadida ainult osa failist.');
define('NoFileUploaded', 'Ühtegi faili üles ei laaditud.');
define('MissingTempFolder', 'Ajutine kataloog on puudu.');
define('MissingTempFile', 'Ajutine fail on puudu.');
define('UnknownUploadError', 'Faili üleslaadimisel tekkis tundmatu viga.');
define('FileLargerThan', 'Viga faili üleslaadimisel. Tõenäoliselt on see fail suurem kui ');
define('PROC_CANT_LOAD_DB', 'Ühendus andmebaasiga ei õnnestunud.');
define('PROC_CANT_LOAD_LANG', 'Vajalikku keelefaili ei õnnestunud leida.');
define('PROC_CANT_LOAD_ACCT', 'Kontot ei ole. Võimalik, et see on kustutatud.');

define('DomainDosntExist', 'Niisugust domeeni postiserveris ei ole.');
define('ServerIsDisable', 'Administraator on postiserveri kasutamise keelanud.');

define('PROC_ACCOUNT_EXISTS', 'Seda kontot ei saa luua, sest see on juba olemas.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Kirjade arvu kataloogis ei õnnestunud kindlaks teha.');
define('PROC_CANT_MAIL_SIZE', 'Posti mahu suurust ei õnnestunud kindlaks teha.');

define('Organization', 'Organisatsioon');
define('WarningOutServerBlank', 'Väljuva posti välja ei saa tühjaks jätta.');

define('JS_LANG_Refresh', 'Värskenda vaadet');
define('JS_LANG_MessagesInInbox', ' kirja (postkastis)');
define('JS_LANG_InfoEmptyInbox', 'Postkast on tühi.');

// webmail 4.2 constants
define('BackToList', 'Tagasi kirjade loetellu');
define('InfoNoContactsGroups', 'Kontakte ega rühmasid ei ole.');
define('InfoNewContactsGroups', 'Saate luua ise uusi kontakte/rühmasid või importida kontakte .CSV-failist tarkvara MS Outlook vormingus.');
define('DefTimeFormat', 'Kellaaeg esitatakse kujul');
define('SpellNoSuggestions', 'Õigekirjasoovitusi ei kuvata');
define('SpellWait', 'Palun oodake&hellip;');

define('InfoNoMessageSelected', 'Ühtegi kirja ei ole valitud.');
define('InfoSingleDoubleClick', 'Loetelus tehke kirja eelvaatamiseks kirjal üks klõps ja täisvaatamiseks topeltklõps.');

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

define('SettingsTabCalendar', 'Kalender');

define('FullMonthJanuary', 'jaanuar');
define('FullMonthFebruary', 'veebruar');
define('FullMonthMarch', 'märts');
define('FullMonthApril', 'aprill');
define('FullMonthMay', 'mai');
define('FullMonthJune', 'juuni');
define('FullMonthJuly', 'juuli');
define('FullMonthAugust', 'august');
define('FullMonthSeptember', 'september');
define('FullMonthOctober', 'oktoober');
define('FullMonthNovember', 'november');
define('FullMonthDecember', 'detsember');

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

define('FullDayMonday', 'esmaspäev');
define('FullDayTuesday', 'teisipäev');
define('FullDayWednesday', 'kolmapäev');
define('FullDayThursday', 'neljapäev');
define('FullDayFriday', 'reede');
define('FullDaySaturday', 'laupäev');
define('FullDaySunday', 'pühapäev');

define('DayToolMonday', 'E');
define('DayToolTuesday', 'T');
define('DayToolWednesday', 'K');
define('DayToolThursday', 'N');
define('DayToolFriday', 'R');
define('DayToolSaturday', 'L');
define('DayToolSunday', 'P');

define('CalendarTableDayMonday', 'E');
define('CalendarTableDayTuesday', 'T');
define('CalendarTableDayWednesday', 'K');
define('CalendarTableDayThursday', 'N');
define('CalendarTableDayFriday', 'R');
define('CalendarTableDaySaturday', 'L');
define('CalendarTableDaySunday', 'P');

define('ErrorParseJSON', 'The JSON response returned by the server cannot be parsed.');

define('ErrorLoadCalendar', 'Unable to load calendars');
define('ErrorLoadEvents', 'Unable to load events');
define('ErrorUpdateEvent', 'Unable to save event');
define('ErrorDeleteEvent', 'Unable to delete event');
define('ErrorUpdateCalendar', 'Unable to save calendar');
define('ErrorDeleteCalendar', 'Unable to delete calendar');
define('ErrorGeneral', 'An error occured on the server. Try again later.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-post');
define('ShareHeaderEdit', 'Kalendri jagamine ja avaldamine');
define('ShareActionEdit', 'Kalendri jagamine ja avaldamine');
define('CalendarPublicate', 'Kalendri avaldamine veebis');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Kalendri jagamine');
define('SharePermission1', 'saab teha muudatusi ja hallata jagamist');
define('SharePermission2', 'saab muuta sündmusi');
define('SharePermission3', 'saab vaadata kõiki sündmuste andmeid');
define('SharePermission4', 'saab näha ainult vaba/hõivatud olekut (üksikasjad peidetakse)');
define('ButtonClose', 'Sulgen');
define('WarningEmailFieldFilling', 'Täitke kõigepealt e-posti väli.');
define('EventHeaderView', 'Sündmuse vaatamine');
define('ErrorUpdateSharing', 'Kalendrite jagamise ja avaldamise andmeid ei õnnestunud salvestada.');
define('ErrorUpdateSharing1', 'Seda kalendrit ei saa kasutajaga %s jagada, sest seda pole olemas.');
define('ErrorUpdateSharing2', 'Seda kalendrit ei saa kasutajaga %s jagadaa.');
define('ErrorUpdateSharing3', 'See kalender on kasutajaga  %s on juba jagatud.');
define('Title_MyCalendars', 'Minu kalendrid');
define('Title_SharedCalendars', 'Jagatud kalendrid');
define('ErrorGetPublicationHash', 'Avaldamislinki ei õnnestunud luua.');
define('ErrorGetSharing', 'Kalendrit ei õnnestunud jagada.');
define('CalendarPublishedTitle', 'Kalender on avaldatud.');
define('RefreshSharedCalendars', 'Värskenda jagatud kalendreid');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Liikmed');

define('ReportMessagePartDisplayed', 'NB\! Kiri on kuvatud ainult osaliselt.');
define('ReportViewEntireMessage', 'Kogu kirja vaatamiseks');
define('ReportClickHere', 'klõpsake siia');
define('ErrorContactExists', 'Sellise nime ja aadressiga kontakt on juba olemas.');

define('Attachments', 'Lisatud failid');

define('InfoGroupsOfContact', 'Rühmad, kuhu see kontakt kuulub, on märgistatud.');
define('AlertNoContactsSelected', 'Ühtegi kontakti ei ole valitud.');
define('MailSelected', 'Kiri valitud aadressidel');
define('CaptionSubscribed', 'Tellitud');

define('OperationSpam', 'Rämpspostiks');
define('OperationNotSpam', 'Ei ole rämpspost');
define('FolderSpam', 'Rämpspost');

// webmail 4.4 contacts
define('ContactMail', 'Kiri talle');
define('ContactViewAllMails', 'Kogu kirjavahetus selle kontaktiga');
define('ContactsMailThem', 'Neile kirja saatmine');
define('DateToday', 'Täna');
define('DateYesterday', 'Eile');
define('MessageShowDetails', 'Näita üksikasju');
define('MessageHideDetails', 'Peida üksikasjad');
define('MessageNoSubject', 'Teema puudub');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', '– Saaja:');
define('SearchClear', 'Tühjenda otsing');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Otsi tulemustes teksti "#s" kataloogist #f:');
define('SearchResultsInAllFolders', 'Otsi tulemustes teksti "#s" kõikidest postikataloogidest:');
define('AutoresponderTitle', 'Automaatvastaja');
define('AutoresponderEnable', 'Automaatvastaja on sisse lülitatud');
define('AutoresponderSubject', 'Teema');
define('AutoresponderMessage', 'Kiri');
define('ReportAutoresponderUpdatedSuccessfuly', 'Automaatvastaja andmed on uuendatud.');
define('FolderQuarantine', 'Karantiin');

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
define('WarningUntilDateBlank', 'Märkige kordumise lõpu kuupäev.');
define('WarningWrongUntilDate', 'Kordumise lõpu kuupäev peab olema varasem kui kordumise alguse kuupäev.');

define('OnDays', 'Päevadel');
define('CancelRecurrence', 'Tühista kordumine');
define('RepeatEvent', 'Korda seda sündmust');

define('Spellcheck', 'Õigekirjakontroll');
define('LoginLanguage', 'Keel');
define('LanguageDefault', 'Vaikimisi');

// webmail 4.5.x new
define('EmptySpam', 'Tühjenda rämpsposti kataloog');
define('Saving', 'Salvestamine&hellip;');
define('Sending', 'Saatmine&hellip;');
define('LoggingOffFromServer', 'Logimine&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Kirja(de) rämpspostiks märkimine ei õnnestunud.');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Kirja(de) märkimine soovitud postiks ei õnnestunud.');
define('ExportToICalendar', 'Eksportimine tarkvara iCalendar vormingusse');
define('ErrorMaximumUsersLicenseIsExceeded', 'Kasutaja loomine ebaõnnestus, sest Teie litsentsiga lubatud kasutajate arv on täis.');
define('RepliedMessageTitle', 'Vastatud kiri');
define('ForwardedMessageTitle', 'Edastatud kiri');
define('RepliedForwardedMessageTitle', 'Vastatud ja edastatud kiri');
define('ErrorDomainExist', 'Kasutajat ei saa luua domeeni puudumise tõttu. Kõigepealt looge domeen.');

// webmail 4.7
define('RequestReadConfirmation', 'Soovin adressaadilt (või adressaatidelt) kättesaamiskinnitust.');
define('FolderTypeDefault', 'Vaikimisi');
define('ShowFoldersMapping', 'Soovin kasutada muid katalooge peale süsteemikataloogide (nt. kataloogi MinuKataloog saadetud posti jaoks)');
define('ShowFoldersMappingNote', 'Näiteks selleks, et kasutada saadetud kirjade jaoks isetehtud kataloogi, valige selle isetehtud kataloogi kõrval rippmenüüst \'Kasuta kataloogina\' funktsioon \'Saadetud\'.');
define('FolderTypeMapTo', 'Kasuta kataloogina');

define('ReminderEmailExplanation', 'See kiri on saabunud teie kontole %EMAIL% sellepärast, et tellisite oma kalendris %CALENDAR_NAME% sündmusest teatamise.');
define('ReminderOpenCalendar', 'Ava kalender');

define('AddReminder', 'Saada selle sündmuse kohta meenutus');
define('AddReminderBefore', 'Remind me % before this event');
define('AddReminderAnd', 'ja % varem');
define('AddReminderAlso', 'ja samuti % varem');
define('AddMoreReminder', 'Rohkem meenutusi');
define('RemoveAllReminders', 'Kustuta kõik meenutused');
define('ReminderNone', 'Mitte ühtegi');
define('ReminderMinutes', 'minut(it)');
define('ReminderHour', 'tund');
define('ReminderHours', 'tundi');
define('ReminderDay', 'päev');
define('ReminderDays', 'päeva');
define('ReminderWeek', 'nädal');
define('ReminderWeeks', 'nädalat');
define('Allday', 'All day');

define('Folders', 'Kataloogid');
define('NoSubject', 'Teema puudub');
define('SearchResultsFor', 'Otsi tulemustest');

define('Back', 'Tagasi');
define('Next', 'Järgmine');
define('Prev', 'Eelmine');

define('MsgList', 'Messages');
define('Use24HTimeFormat', '24tunnine formaat');
define('UseCalendars', 'Kasutusel on kalendrid');
define('Event', 'Sündmus');
define('CalendarSettingsNullLine', 'Kalendreid ei ole');
define('CalendarEventNullLine', 'Sündmusi ei ole');
define('ChangeAccount', 'Konto muutmine');

define('TitleCalendar', 'Kalender');
define('TitleEvent', 'Sündmus');
define('TitleFolders', 'Kataloogid');
define('TitleConfirmation', 'Kinnitus');

define('Yes', 'Jah');
define('No', 'Ei');

define('EditMessage', 'Kirja muutmine');

define('AccountNewPassword', 'Uus parool');
define('AccountConfirmNewPassword', 'Uus parool uuesti');
define('AccountPasswordsDoNotMatch', 'Paroolid ei klapi omavahel.');

define('ContactTitle', 'Pealkiri');
define('ContactFirstName', 'Eesnimi');
define('ContactSurName', 'Perekonnanimi');

define('ContactNickName', 'Hüüdnimi');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'lae uuesti');
define('CaptchaError', 'Captcha väljale sisestatud tekst ei ole õige.');

define('WarningInputCorrectEmails', 'Palun kirjutage õiged e-postiaadressid.');
define('WrongEmails', 'Ebakorrektsed aadressid:');

define('ConfirmBodySize1', 'Tekstisõnumid saavad olla kuni');
define('ConfirmBodySize2', 'sümboli pikkused. Ülejäänu kärbitakse. Sõnumi muutmiseks valige Cancel.');
define('BodySizeCounter', 'Loendur');
define('InsertImage', 'Pildi lisamine');
define('ImagePath', 'Pildi asukoht');
define('ImageUpload', 'Lisan');
define('WarningImageUpload', 'Lisamiseks valitud fail ei ole pildifail. Palun valige pildifail.');

define('ConfirmExitFromNewMessage', 'Kui sellelt lehelt salvestamata lahkuda, kaovad alates viimasest salvestamisest tehtud muudatused. Siia lehele jäämiseks valige Cancel.');

define('SensivityConfidential', 'Palun lugeda see kiri konfidentsiaalseks.');
define('SensivityPrivate', 'Palun lugeda see kiri privaatseks.');
define('SensivityPersonal', 'Palun lugeda see kiri isiklikuks.');

define('ReturnReceiptTopText', 'Selle kirja saatja on palunud kinnitust kirja kättesaamise kohta.');
define('ReturnReceiptTopLink', 'Saatjale kättesaamiskinnituse saatmiseks klõpsake siia.');
define('ReturnReceiptSubject', 'Kättesaamiskinnitus');
define('ReturnReceiptMailText1', 'This is a Return Receipt for the mail that you sent to');
define('ReturnReceiptMailText2', 'Note: This Return Receipt only acknowledges that the message was displayed on the recipient\'s computer. There is no guarantee that the recipient has read or understood the message contents.');
define('ReturnReceiptMailText3', 'with subject');

define('SensivityMenu', 'Tundlikkus');
define('SensivityNothingMenu', 'Tavaline kiri');
define('SensivityConfidentialMenu', 'Konfidentsiaalne kiri');
define('SensivityPrivateMenu', 'Privaatne kiri');
define('SensivityPersonalMenu', 'Isiklik kiri');

define('ErrorLDAPonnect', 'Ühendus LDAP-serveriga ei õnnestunud.');

define('MessageSizeExceedsAccountQuota', 'Selle kirja suurus ületab lubatud mahtu.');
define('MessageCannotSent', 'Seda kirja ei saa ära saata.');
define('MessageCannotSaved', 'Seda kirja ei saa salvestada.');

define('ContactFieldTitle', 'Väli');
define('ContactDropDownTO', 'Adressaat');
define('ContactDropDownCC', 'Koopia');
define('ContactDropDownBCC', 'Pimekoopia');

// 4.9
define('NoMoveDelete', 'Kirja(de) prügikasti tõstmine ei õnnestunud. Kõige tõenäolisemalt on Teie postkast täis. Kas kustutada ümber tõstmata jäänud kiri/kirjad?');

define('WarningFieldBlank', 'See väli ei saa tühjaks jääda.');
define('WarningPassNotMatch', 'Paroolid ei klapi, palun kontrollige.');
define('PasswordResetTitle', 'Parooli taastamine - etapp %d');
define('NullUserNameonReset', 'kasutaja');
define('IndexResetLink', 'Unustasite parooli?');
define('IndexRegLink', 'Konto registreerimine');

define('RegDomainNotExist', 'Sellist domeeni ei ole.');
define('RegAnswersIncorrect', 'Vastused ei ole õiged.');
define('RegUnknownAdress', 'Tundmatu e-postiaadress.');
define('RegUnrecoverableAccount', 'Selle e-postiaadressiga seotud parooli ei saa taastada.');
define('RegAccountExist', 'See aadress on juba kasutusel.');
define('RegRegistrationTitle', 'Registreerimine');
define('RegName', 'Nimi');
define('RegEmail', 'e-postiaadress');
define('RegEmailDesc', 'Näiteks minunimi@minudomeen.ee. Seda infot kasutatakse süsteemi sisenemiseks.');
define('RegSignMe', 'Edaspidi automaatselt');
define('RegSignMeDesc', 'Järgmisel sellest arvutist sisselogimisel ei ole vaja kasutajatunnust ega parooli küsida.');
define('RegPass1', 'Parool');
define('RegPass2', 'Parool uuesti ');
define('RegQuestionDesc', 'Palun kirjutage siia kaks salaküsimust ja nende vastused, mida ainult Teie teate. Parooli kadumamineku korral saate nende vastuste abil parooli taastada.');
define('RegQuestion1', 'Esimene salaküsimus');
define('RegAnswer1', 'Vastus esimesele küsimusele');
define('RegQuestion2', 'Teine salaküsimus');
define('RegAnswer2', 'Vastus teisele küsimusele');
define('RegTimeZone', 'Ajavöönd');
define('RegLang', 'Kasutajaliidese keel');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Registreerun');

define('ResetEmail', 'Palun kirjutage oma e-postiaadress.');
define('ResetEmailDesc', 'Kirjutage e-postiaadress, mida kasutasite registreerumisel.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Saadan');
define('ResetQuestion1', 'Esimene salaküsimus');
define('ResetAnswer1', 'Vastus');
define('ResetQuestion2', 'Teine salaküsimus');
define('ResetAnswer2', 'Vastus');
define('ResetSubmitStep2', 'Saadan');

define('ResetTopDesc1Step2', 'Kirjutage e-postiaadress');
define('ResetTopDesc2Step2', 'Palun kinnitage.');

define('ResetTopDescStep3', 'Kirjutage allapoole uus e-posti parool.');

define('ResetPass1', 'Uus parool');
define('ResetPass2', 'Parool uuesti');
define('ResetSubmitStep3', 'Saadan');
define('ResetDescStep4', 'Teie parool on muudetud.');
define('ResetSubmitStep4', 'Tagasi');

define('RegReturnLink', 'Tagasi sisselogimise lehele');
define('ResetReturnLink', 'Tagasi sisselogimise lehele');

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

define('ErrorCantUpdateFilters', 'Filtreid ei õnnestunud uuendada.');

define('FilterPhrase', 'Kui väli %field %condition %string siis %action');
define('FiltersAdd', 'Filtri lisamine');
define('FiltersCondEqualTo', 'on');
define('FiltersCondContainSubstr', 'sisaldab teksti');
define('FiltersCondNotContainSubstr', 'ei sisalda teksti');
define('FiltersActionDelete', 'kiri kustutatakse');
define('FiltersActionMove', 'tõstetakse kiri automaatselt');
define('FiltersActionToFolder', 'kataloogi %folder');
define('FiltersNo', 'Ühtegi filtrit ei ole veel määratud.');

define('ReminderEmailFriendly', 'meeldetuletus');
define('ReminderEventBegin', 'algusaeg: ');

define('FiltersLoading', 'Filtrite laadimine...');
define('ConfirmMessagesPermanentlyDeleted', 'Kõik selles kataloogis olevad kirjad kustutatakse jäädavalt.');

define('InfoNoNewMessages', 'Uusi kirju ei ole.');
define('TitleImportContacts', 'Kontaktide importimine');
define('TitleSelectedContacts', 'Valitud kontaktid');
define('TitleNewContact', 'Uue kontakti sisestamine');
define('TitleViewContact', 'Kontakti andmed');
define('TitleEditContact', 'Kontakti muutmine');
define('TitleNewGroup', 'Uue rühma loomine');
define('TitleViewGroup', 'Rühma andmed');

define('AttachmentComplete', 'Valmis.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Automaatkontrolli intervall');
define('AutoCheckMailIntervalDisableName', 'Välja lülitatud');
define('ReportCalendarSaved', 'Kalender on salvestatud.');

define('ContactSyncError', 'Sünkroniseerimine ebaõnnestus');
define('ReportContactSyncDone', 'Sünkroniseerimine on lõppenud');

define('MobileSyncUrlTitle', 'Mobiilse sünkroneerimise aadress');
define('MobileSyncLoginTitle', 'Mobiilse sünkroneerimise kasutajatunnus');

define('QuickReply', 'Kiirvastus');
define('SwitchToFullForm', 'Täisredaktor');
define('SortFieldDate', 'kuupäeva järgi');
define('SortFieldFrom', 'saatja järgi');
define('SortFieldSize', 'suuruse järgi');
define('SortFieldSubject', 'teema järgi');
define('SortFieldFlag', 'tähtsuse järgi');
define('SortFieldAttachments', 'lisatud failide järgi');
define('SortOrderAscending', 'alt üles');
define('SortOrderDescending', 'alt üles');
define('ArrangedBy', 'Järjestus');

define('MessagePaneToRight', 'Kirja eelvaade on kirjade loetelust paremal, mitte loetelu all');

define('SettingsTabMobileSync', 'Mobiilne sünkroniseerimine');

define('MobileSyncContactDataBaseTitle', 'Mobiilselt sünkroniseeritav kontaktide andmebaas');
define('MobileSyncCalendarDataBaseTitle', 'Mobiilselt sünkroniseeritav kalendriandmebaas');
define('MobileSyncTitleText', 'Kui soovite oma SyncMLi-võimelist taskuseadet WebMailiga sünkroniseerida, saate kasutada neid parameetreid.<br />Mobiilse sünkroniseerimise URL on SyncMLi andmesünkroniseerimise serveri aadress; mobiilse sünkroniseerimise kasutajatunnus on Teie kasutajatunnus SyncMLi andmesünkroniseerimise serveris ja seejuures kasutate omaenda parooli.<br />Samuti on mõnes seadmes vaja märkida kontaktide ja kalendriandmete andmebaasi nimi.<br />Selleks kasutage vastavalt seadeid \'Mobiilselt sünkroniseeritav kontaktide andmebaas\' ja \'Mobiilselt sünkroniseeritav kalendriandmebaas\'.');
define('MobileSyncEnableLabel', 'Mobiilne sünkroniseerimine on lubatud');

define('SearchInputText', 'otsing');

define('AppointmentEmailExplanation','See kiri on saabunud Teie kontole %EMAIL% sellepärast, et %ORGANAZER% saatis Teile kutse.');

define('Searching', 'Otsimine&hellip;');

define('ButtonSetupSpecialFolders', 'Setup special folders');
define('ButtonSaveChanges', 'Save changes');
define('InfoPreDefinedFolders', 'For pre-defined folders, use these IMAP mailboxes');

define('SaveMailInSentItems', 'Salvesta ka saadetud kirjade alla');

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
