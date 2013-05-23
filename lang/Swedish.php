<?php
//Swedish Translation by Peter Strömblad, http://webbhotell.praktit.se
//Rev. 2011-04-17,2010-08-29,2008-04-29,2007-10-31

define('PROC_ERROR_ACCT_CREATE', 'Ett fel uppstod vid skapandet av kontot');
define('PROC_WRONG_ACCT_PWD', 'Fel lösenord');
define('PROC_CANT_LOG_NONDEF', 'Kan ej logga in på annat än default konto');
define('PROC_CANT_INS_NEW_FILTER', 'Kan ej infoga nytt filter');
define('PROC_FOLDER_EXIST', 'Mapp finns redan');
define('PROC_CANT_CREATE_FLD', 'Kan ej skapa mapp');
define('PROC_CANT_INS_NEW_GROUP', 'Kan ej skapa ny grupp');
define('PROC_CANT_INS_NEW_CONT', 'Kan ej infoga ny kontakt');
define('PROC_CANT_INS_NEW_CONTS', 'Kan ej infoga nya kontakter');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Kan ej skapa nya kontakt/er i grupp');
define('PROC_ERROR_ACCT_UPDATE', 'Ett fel uppstod vid uppdatering av kontot');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Kunde ej uppdatera kontakts inställningar');
define('PROC_CANT_GET_SETTINGS', 'Kunde ej hämta inställningar');
define('PROC_CANT_UPDATE_ACCT', 'Kunde ej uppdatera kontot');
define('PROC_ERROR_DEL_FLD', 'Ett fel uppstod vid radering av mapp');
define('PROC_CANT_UPDATE_CONT', 'Kunde ej uppdatera kontakt');
define('PROC_CANT_GET_FLDS', 'Kunde ej hämta mappstruktur');
define('PROC_CANT_GET_MSG_LIST', 'Kunde ej hämta meddelandelista');
define('PROC_MSG_HAS_DELETED', 'Detta meddelande har redan raderats från e-postservern');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Kan ej hämta kontakts inställningar');
define('PROC_CANT_LOAD_SIGNATURE', 'Kan ej hämta kontosignatur');
define('PROC_CANT_GET_CONT_FROM_DB', 'Kan ej hämta kontakt från databas');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Kan ej hämta kontakter från databas');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Kan ej radera konto med ID');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Kan ej radera filter med id');
define('PROC_CANT_DEL_CONT_GROUPS', 'Kan ej radera kontakt/er och/eller grupper');
define('PROC_WRONG_ACCT_ACCESS', 'Ett intrångsförsök mot annans konto upptäcktes.');
define('PROC_SESSION_ERROR', 'Föregående session avbröts pga tidsgräns.');

define('MailBoxIsFull', 'Brevlådan är full');
define('WebMailException', 'WebbMail undantagsfel uppstod');
define('InvalidUid', 'Ogiltigt meddelande UID (unik identifierare)');
define('CantCreateContactGroup', 'Kan ej skapa kontaktgrupp');
define('CantCreateUser', 'Kan ej skapa användare');
define('CantCreateAccount', 'Kan ej skapa konto');
define('SessionIsEmpty', 'Sessionen är tom');
define('FileIsTooBig', 'Filen är för stor');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Kan ej markera alla meddelanden som lästa');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Kan ej markera alla meddelanden som ej lästa');
define('PROC_CANT_PURGE_MSGS', 'Kan ej radera meddelande/n');
define('PROC_CANT_DEL_MSGS', 'Kan ej ta bort meddelande/n');
define('PROC_CANT_UNDEL_MSGS', 'Kan ej återta meddelande/n');
define('PROC_CANT_MARK_MSGS_READ', 'Kan ej markera meddelande/n som lästa');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Kan ej markera meddelande/n som olästa');
define('PROC_CANT_SET_MSG_FLAGS', 'Kan ej sätta meddelandeflagga');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Kan ej ta bort meddelandeflagga');
define('PROC_CANT_CHANGE_MSG_FLD', 'Kan ej ändra meddelandemapp');
define('PROC_CANT_SEND_MSG', 'kan ej skicka meddelande.');
define('PROC_CANT_SAVE_MSG', 'Kan ej spara meddelande');
define('PROC_CANT_GET_ACCT_LIST', 'Kan ej hämta kontoförteckning');
define('PROC_CANT_GET_FILTER_LIST', 'Kan ej hämta filterförteckning');

define('PROC_CANT_LEAVE_BLANK', 'Fält med * måste fyllas i');

define('PROC_CANT_UPD_FLD', 'Kan ej uppdatera mapp');
define('PROC_CANT_UPD_FILTER', 'Kan ej uppdatera filter');

define('ACCT_CANT_ADD_DEF_ACCT', 'Detta konto kan ej läggas till eftersom det används som standardkonto av annan användare.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Detta kontos status kan ej ändras till standardkonto.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Kan ej skapa nytt konto (IMAP4 förbindelsefel)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Kan ej radera standardkonto');

define('LANG_LoginInfo', 'Login information');
define('LANG_Email', 'Epostadress');
define('LANG_Login', 'Login');
define('LANG_Password', 'Lösenord');
define('LANG_IncServer', 'Inkommande Epostserver');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'Utgående Epostserver (SMTP)');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Använd SMTP autentisering');
define('LANG_SignMe', 'Logga in mig automatiskt');
define('LANG_Enter', 'Enter');

// interface strings

define('JS_LANG_TitleLogin', 'Login');
define('JS_LANG_TitleMessagesListView', 'Meddelandelista');
define('JS_LANG_TitleMessagesList', 'Meddelandelista');
define('JS_LANG_TitleViewMessage', 'Visa Meddelande');
define('JS_LANG_TitleNewMessage', 'Nytt Meddelande');
define('JS_LANG_TitleSettings', 'Inställningar');
define('JS_LANG_TitleContacts', 'Kontakter');

define('JS_LANG_StandardLogin', 'Standard Inloggning');
define('JS_LANG_AdvancedLogin', 'Avancerad Inloggning');

define('JS_LANG_InfoWebMailLoading', 'Vänligen vänta, laddar&hellip;');
define('JS_LANG_Loading', 'Laddar&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Vänligen vänta, laddar meddelandelista');
define('JS_LANG_InfoEmptyFolder', 'Mappen är tom');
define('JS_LANG_InfoPageLoading', 'Sidan laddas&hellip;');
define('JS_LANG_InfoSendMessage', 'Meddelandet har skickats');
define('JS_LANG_InfoSaveMessage', 'Meddelandet har sparats');
define('JS_LANG_InfoHaveImported', 'Du har importerat');
define('JS_LANG_InfoNewContacts', 'nya kontakt/er i din kontaktlista.');
define('JS_LANG_InfoToDelete', 'För att radera ');
define('JS_LANG_InfoDeleteContent', 'mappen måste du tömma dess innehåll först.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Att radera mappar med innehåll tillåts ej. För att radera omarkerade mappar, töm deras innehåll först.');
define('JS_LANG_InfoRequiredFields', '* fält som krävs');

define('JS_LANG_ConfirmAreYouSure', 'Är du säker?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Valda meddelande/n kommer att raderas permanent! Är du säker?');
define('JS_LANG_ConfirmSaveSettings', 'Inställningarna sparades ej. Välj OK för att spara.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Kontaktinställningarna sparades ej. Välj OK för att spara.');
define('JS_LANG_ConfirmSaveAcctProp', 'Kontots inställningar sparades ej. Välj OK för att spara.');
define('JS_LANG_ConfirmSaveFilter', 'Filterinställningarna sparades ej. Välj OK för att spara.');
define('JS_LANG_ConfirmSaveSignature', 'Signaturen sparades ej. Välj OK för att spara.');
define('JS_LANG_ConfirmSavefolders', 'Mappen/arna sparades ej. Välj OK för att spara.');
define('JS_LANG_ConfirmHtmlToPlain', 'Varning: Genom att ändra meddelandeformatet från HTML till text, så förloras nuvarande utformning. Välj OK för att verkställa.');
define('JS_LANG_ConfirmAddFolder', 'Före mapp kan läggas till är det nödvändigt att verkställa förändringar. Välj OK för att spara.');
define('JS_LANG_ConfirmEmptySubject', 'Titelraden är tom. Vill du fortsätta?');

define('JS_LANG_WarningEmailBlank', 'Avsändarfältet får ej vara tomt');
define('JS_LANG_WarningLoginBlank', 'Inloggningsfältet får ej vara tomt');
define('JS_LANG_WarningToBlank', 'Till-fältet får ej vara tomt');
define('JS_LANG_WarningServerPortBlank', 'POP3 och SMTP/Port fälten får ej vara tomma');
define('JS_LANG_WarningEmptySearchLine', 'Söksträng tom. Vänligen fyll i söksträng');
define('JS_LANG_WarningMarkListItem', 'Vänligen markera minst en i listan');
define('JS_LANG_WarningFolderMove', 'Mappen kan ej flyttas pga nivå');
define('JS_LANG_WarningContactNotComplete', 'Fyll i namn eller epostadress');
define('JS_LANG_WarningGroupNotComplete', 'Fyll i gruppens namn');

define('JS_LANG_WarningEmailFieldBlank', 'Fältet Epost kan ej vara tomt');
define('JS_LANG_WarningIncServerBlank', 'Fältet POP3(IMAP4) Server får ej vara tomt');
define('JS_LANG_WarningIncPortBlank', 'Fältet POP3(IMAP4) Server Port får ej vara tomt');
define('JS_LANG_WarningIncLoginBlank', 'Fältet POP3(IMAP4) inloggning kan ej vara tomt');
define('JS_LANG_WarningIncPortNumber', 'Fältet POP3(IMAP4) Server Port måste vara positivt heltal.');
define('JS_LANG_DefaultIncPortNumber', 'Standardport för POP3(IMAP4) är 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Fältet POP3(IMAP4) lösenord får ej vara tomt.');
define('JS_LANG_WarningOutPortBlank', 'Fältet SMTP Server Port får ej vara blankt.');
define('JS_LANG_WarningOutPortNumber', 'Fältet SMTP Server Port måste vara positivt heltal.');
define('JS_LANG_WarningCorrectEmail', 'Du måste ange korrekt epostadress.');
define('JS_LANG_DefaultOutPortNumber', 'Standardport för SMTP är 25.');

define('JS_LANG_WarningCsvExtention', 'Filändelsen ska vara .csv');
define('JS_LANG_WarningImportFileType', 'Välj det program som du vill kopiera dina kontakter från.');
define('JS_LANG_WarningEmptyImportFile', 'välj en fil genom att klicka på sök-knappen');

define('JS_LANG_WarningContactsPerPage', 'Kontakter per sida ska vara ett positivt heltal');
define('JS_LANG_WarningMessagesPerPage', 'Meddelanden per sida ska vara ett positivt heltal');
define('JS_LANG_WarningMailsOnServerDays', 'Du måste ange ett positivt heltal för Meddelanden på servern per dag.');
define('JS_LANG_WarningEmptyFilter', 'Ange substräng');
define('JS_LANG_WarningEmptyFolderName', 'Ange mappens namn');

define('JS_LANG_ErrorConnectionFailed', 'Förbindelsen fallerade');
define('JS_LANG_ErrorRequestFailed', 'Dataöverföringen har inte fullförts');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Objektet XMLHttpRequest saknas');
define('JS_LANG_ErrorWithoutDesc', 'Okänt fel');
define('JS_LANG_ErrorParsing', 'Fel vid tolkning av XML.');
define('JS_LANG_ResponseText', 'Svarsmeddelande:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Tomt XML paket');
define('JS_LANG_ErrorImportContacts', 'Fel vid import av kontakter');
define('JS_LANG_ErrorNoContacts', 'Inga kontakter att importera');
define('JS_LANG_ErrorCheckMail', 'Hämtning av meddelanden avbröts pga ett fel. Förmodligen hämtades ej alla meddelanden.');

define('JS_LANG_LoggingToServer', 'Loggar in på server&hellip;');
define('JS_LANG_GettingMsgsNum', 'Hämtar antal meddelanden');
define('JS_LANG_RetrievingMessage', 'Hämtar meddelande');
define('JS_LANG_DeletingMessage', 'Raderar meddelande');
define('JS_LANG_DeletingMessages', 'Raderar meddelanden');
define('JS_LANG_Of', 'av');
define('JS_LANG_Connection', 'Förbindelse');
define('JS_LANG_Charset', 'Charset');
define('JS_LANG_AutoSelect', 'Auto-val');

define('JS_LANG_Contacts', 'Kontakter');
define('JS_LANG_ClassicVersion', 'Klassisk Version');
define('JS_LANG_Logout', 'Logga ut');
define('JS_LANG_Settings', 'Inställningar');

define('JS_LANG_LookFor', 'Sök efter: ');
define('JS_LANG_SearchIn', 'Sök i: ');
define('JS_LANG_QuickSearch', 'Sök enbart i fälten Från, Till och ämnesrad (snabbast).');
define('JS_LANG_SlowSearch', 'Sök hela meddelanden');
define('JS_LANG_AllMailFolders', 'Alla mappar');
define('JS_LANG_AllGroups', 'Alla grupper');

define('JS_LANG_NewMessage', 'Nytt Meddelande');
define('JS_LANG_CheckMail', 'Hämta Meddelanden');
define('JS_LANG_EmptyTrash', 'Töm papperskorgen');
define('JS_LANG_MarkAsRead', 'Markera som läst');
define('JS_LANG_MarkAsUnread', 'Markera som ej läst');
define('JS_LANG_MarkFlag', 'Flagga');
define('JS_LANG_MarkUnflag', 'Ta bort flagga');
define('JS_LANG_MarkAllRead', 'Markera alla som lästa');
define('JS_LANG_MarkAllUnread', 'Markera alla som olästa');
define('JS_LANG_Reply', 'Svara');
define('JS_LANG_ReplyAll', 'Svara alla');
define('JS_LANG_Delete', 'Radera');
define('JS_LANG_Undelete', 'Återta');
define('JS_LANG_PurgeDeleted', 'Ta bort raderade');
define('JS_LANG_MoveToFolder', 'Flytta till mapp');
define('JS_LANG_Forward', 'Vidarebefordra');

define('JS_LANG_HideFolders', 'Göm mappar');
define('JS_LANG_ShowFolders', 'visa mappar');
define('JS_LANG_ManageFolders', 'Hantera mappar');
define('JS_LANG_SyncFolder', 'Synkroniserad mapp');
define('JS_LANG_NewMessages', 'Nya Meddelanden');
define('JS_LANG_Messages', 'Meddelande/n');

define('JS_LANG_From', 'Från');
define('JS_LANG_To', 'Till');
define('JS_LANG_Date', 'Datum');
define('JS_LANG_Size', 'Storlek');
define('JS_LANG_Subject', 'Ämne');

define('JS_LANG_FirstPage', 'Första sidan');
define('JS_LANG_PreviousPage', 'Föregående sida');
define('JS_LANG_NextPage', 'Nästa sida');
define('JS_LANG_LastPage', 'Sista sidan');

define('JS_LANG_SwitchToPlain', 'Visa som Oformaterad Text');
define('JS_LANG_SwitchToHTML', 'Visa som HTML');
define('JS_LANG_AddToAddressBook', 'Lägg till i adressboken');
define('JS_LANG_ClickToDownload', 'Klicka för att hämta');
define('JS_LANG_View', 'Visa');
define('JS_LANG_ShowFullHeaders', 'Visa fullständigt brevhuvud');
define('JS_LANG_HideFullHeaders', 'Dölj fullständigt brevhuvud');

define('JS_LANG_MessagesInFolder', 'Meddelanden i mapp');
define('JS_LANG_YouUsing', 'Du använder');
define('JS_LANG_OfYour', 'av dina');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Skicka');
define('JS_LANG_SaveMessage', 'Spara');
define('JS_LANG_Print', 'Skriv ut');
define('JS_LANG_PreviousMsg', 'Föregående meddelande');
define('JS_LANG_NextMsg', 'Nästa meddelande');
define('JS_LANG_AddressBook', 'Adressbok');
define('JS_LANG_ShowBCC', 'Visa Hemlig kopia');
define('JS_LANG_HideBCC', 'Dölj Hemlig kopia');
define('JS_LANG_CC', 'Kopia');
define('JS_LANG_BCC', 'Hemlig Kopia');
define('JS_LANG_ReplyTo', 'Svara till');
define('JS_LANG_AttachFile', 'Bifoga fil');
define('JS_LANG_Attach', 'Bifoga');
define('JS_LANG_Re', 'Sv');
define('JS_LANG_OriginalMessage', 'Ursprungligt meddelande');
define('JS_LANG_Sent', 'Skickat');
define('JS_LANG_Fwd', 'Vb');
define('JS_LANG_Low', 'Låg');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Hög');
define('JS_LANG_Importance', 'Prioritet');
define('JS_LANG_Close', 'Stäng');

define('JS_LANG_Common', 'Vanliga');
define('JS_LANG_EmailAccounts', 'Epostkonton');

define('JS_LANG_MsgsPerPage', 'Meddelanden per sida');
define('JS_LANG_DisableRTE', 'Deaktivera rich-text editor');
define('JS_LANG_Skin', 'Utseende');
define('JS_LANG_DefCharset', 'Ordinarie typsnitt');
define('JS_LANG_DefCharsetInc', 'Ordinarie inkommande typsnitt');
define('JS_LANG_DefCharsetOut', 'Ordinarie utgående typsnitt');
define('JS_LANG_DefTimeOffset', 'Ordinare tidszon');
define('JS_LANG_DefLanguage', 'Ordinarie språk');
define('JS_LANG_DefDateFormat', 'Ordinarie datumformat');
define('JS_LANG_ShowViewPane', 'Meddelanden visas med förhandsgranskning');
define('JS_LANG_Save', 'Spara');
define('JS_LANG_Cancel', 'Ångra');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Ta bort');
define('JS_LANG_AddNewAccount', 'Lägg till nytt konto');
define('JS_LANG_Signature', 'Signatur');
define('JS_LANG_Filters', 'Filter');
define('JS_LANG_Properties', 'Inställningar');
define('JS_LANG_UseForLogin', 'Använd detta kontos inställningar (login och lösenord) för inloggning');
define('JS_LANG_MailFriendlyName', 'Ditt namn');
define('JS_LANG_MailEmail', 'Epost');
define('JS_LANG_MailIncHost', 'Inkommande Epost');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Lösenord');
define('JS_LANG_MailOutHost', 'SMTP Server');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Login');
define('JS_LANG_MailOutPass', 'SMTP Lösenord');
define('JS_LANG_MailOutAuth1', 'Använd SMTP autentisering');
define('JS_LANG_MailOutAuth2', '(Lämna fälten login/lösen för att använda samma som POP3/IMAP4 login/lösen)');
define('JS_LANG_UseFriendlyNm1', 'Använd ditt namn för att forma Från:');
define('JS_LANG_UseFriendlyNm2', '(Ditt namn &lt;adress@din_doman.se&gt;)');
define('JS_LANG_GetmailAtLogin', 'Hämta/Synkronisera meddelanden vid inloggning');
define('JS_LANG_MailMode0', 'Radera hämtade meddelande från servern');
define('JS_LANG_MailMode1', 'Låt meddelanden vara kvar på servern');
define('JS_LANG_MailMode2', 'Låt meddelanden vara kvar på servern i ');
define('JS_LANG_MailsOnServerDays', 'dag/ar');
define('JS_LANG_MailMode3', 'Radera meddelanden från servern när papperskorgen töms.');
define('JS_LANG_InboxSyncType', 'Inkorgens synkroniseringsmetod');

define('JS_LANG_SyncTypeNo', 'Synkronisera ej');
define('JS_LANG_SyncTypeNewHeaders', 'Nya meddelanderubriker');
define('JS_LANG_SyncTypeAllHeaders', 'Alla meddelanderubriker');
define('JS_LANG_SyncTypeNewMessages', 'Nya meddelanden');
define('JS_LANG_SyncTypeAllMessages', 'Alla meddelanden');
define('JS_LANG_SyncTypeDirectMode', 'Transparent läge');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Fullständiga brevhuvuden');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Hela meddelanden');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Transparent läge');

define('JS_LANG_DeleteFromDb', 'Radera meddelanden från databasen om de ej finns kvar på servern');

define('JS_LANG_EditFilter', 'Redigera filter');
define('JS_LANG_NewFilter', 'Skapa nytt filter');
define('JS_LANG_Field', 'Fält');
define('JS_LANG_Condition', 'Villkor');
define('JS_LANG_ContainSubstring', 'Innehåller');
define('JS_LANG_ContainExactPhrase', 'Exakt fras');
define('JS_LANG_NotContainSubstring', 'Ej innehåller');
define('JS_LANG_FilterDesc_At', 'vid');
define('JS_LANG_FilterDesc_Field', 'fält');
define('JS_LANG_Action', 'Utför');
define('JS_LANG_DoNothing', 'Gör ingenting');
define('JS_LANG_DeleteFromServer', 'Radera från servern omedelbart');
define('JS_LANG_MarkGrey', 'Gråmarkera');
define('JS_LANG_Add', 'Lägg till');
define('JS_LANG_OtherFilterSettings', 'Andra filterinställningar');
define('JS_LANG_ConsiderXSpam', 'Ta hänsyn till X-Spam flaggor');
define('JS_LANG_Apply', 'Verkställ');

define('JS_LANG_InsertLink', 'Infoga länk');
define('JS_LANG_RemoveLink', 'Ta bort länk');
define('JS_LANG_Numbering', 'Numrering');
define('JS_LANG_Bullets', 'Punkter');
define('JS_LANG_HorizontalLine', 'Horisontell linje');
define('JS_LANG_Bold', 'Fet');
define('JS_LANG_Italic', 'Kursiv');
define('JS_LANG_Underline', 'Stryk under');
define('JS_LANG_AlignLeft', 'Vänsterjustera');
define('JS_LANG_Center', 'Centrera');
define('JS_LANG_AlignRight', 'Högerjustera');
define('JS_LANG_Justify', 'Anpassa');
define('JS_LANG_FontColor', 'Fontfärg');
define('JS_LANG_Background', 'Bakgrund');
define('JS_LANG_SwitchToPlainMode', 'Byt till oformaterat text-läge');
define('JS_LANG_SwitchToHTMLMode', 'Byt till HTML-läge');

define('JS_LANG_Folder', 'Mapp');
define('JS_LANG_Msgs', 'Meddelanden,');
define('JS_LANG_Synchronize', 'Synkronisera');
define('JS_LANG_ShowThisFolder', 'Visa mapp');
define('JS_LANG_Total', 'Totalt');
define('JS_LANG_DeleteSelected', 'Radera markerade');
define('JS_LANG_AddNewFolder', 'Lägg till mapp');
define('JS_LANG_NewFolder', 'Ny mapp');
define('JS_LANG_ParentFolder', 'Överordnad mapp');
define('JS_LANG_NoParent', 'Överordnad mapp saknas');
define('JS_LANG_FolderName', 'Mappnamn');

define('JS_LANG_ContactsPerPage', 'Kontakter per sida');
define('JS_LANG_WhiteList', 'Adressbok som "vitlistad"');

define('JS_LANG_CharsetDefault', 'Default');
define('JS_LANG_CharsetArabicAlphabetISO', 'Arabiskt alfabet (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Arabiskt alfabet (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Baltiskt alfabet (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Baltiskt alfabet (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Central Europeiskt alfabet (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Central Europeiskt alfabet (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Chinese Simplified (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Chinese Simplified (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Kinesiskt traditionellt (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Cyrilliskt alfabet (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Cyrilliskt alfabet (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Cyrilliskt alfabet (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Grekiskt alfabet (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Grekiskt alfabet (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Hebreeiskt alfabet (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'Hebreeiskt alfabet (Windows)');
define('JS_LANG_CharsetJapanese', 'Japanese');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japanese (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Koreanskt (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Koreanskt (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latin 3 alfabet (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Turkiskt alfabet');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Universal alfabet (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Universal alfabet (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Vietnamesiskt alfabet (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Western alfabet (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Western alfabet (Windows)');

define('JS_LANG_TimeDefault', 'Standard');
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

define('JS_LANG_DateDefault', 'Standard');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Månad (01 Jan)');
define('JS_LANG_DateAdvanced', 'Avancerad');

define('JS_LANG_NewContact', 'Ny kontakt');
define('JS_LANG_NewGroup', 'Ny grupp');
define('JS_LANG_AddContactsTo', 'Lägg till kontakter till');
define('JS_LANG_ImportContacts', 'Importera kontakter');

define('JS_LANG_Name', 'Namn');
define('JS_LANG_Email', 'Epost');
define('JS_LANG_DefaultEmail', 'Ordinarie epost');
define('JS_LANG_NotSpecifiedYet', 'Ej angiven');
define('JS_LANG_ContactName', 'Namn');
define('JS_LANG_Birthday', 'Födelsedag');
define('JS_LANG_Month', 'Månad');
define('JS_LANG_January', 'Januari');
define('JS_LANG_February', 'Februari');
define('JS_LANG_March', 'Mars');
define('JS_LANG_April', 'April');
define('JS_LANG_May', 'Maj');
define('JS_LANG_June', 'Juni');
define('JS_LANG_July', 'Juli');
define('JS_LANG_August', 'Augusti');
define('JS_LANG_September', 'September');
define('JS_LANG_October', 'Oktober');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'December');
define('JS_LANG_Day', 'Dag');
define('JS_LANG_Year', 'År');
define('JS_LANG_UseFriendlyName1', 'Använd personens namn');
define('JS_LANG_UseFriendlyName2', '(t.ex. Peter S &lt;peter@din_doman.se&gt;)');
define('JS_LANG_Personal', 'Privat');
define('JS_LANG_PersonalEmail', 'Privat Epostadress');
define('JS_LANG_StreetAddress', 'Gata');
define('JS_LANG_City', 'Stad');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Stat/Provins');
define('JS_LANG_Phone', 'Telefno');
define('JS_LANG_ZipCode', 'Postnr');
define('JS_LANG_Mobile', 'Mobil');
define('JS_LANG_CountryRegion', 'Land');
define('JS_LANG_WebPage', 'Hemsida');
define('JS_LANG_Go', 'Öppna');
define('JS_LANG_Home', 'Hem');
define('JS_LANG_Business', 'Arbete');
define('JS_LANG_BusinessEmail', 'Företags Epostadress');
define('JS_LANG_Company', 'Företag');
define('JS_LANG_JobTitle', 'Titel');
define('JS_LANG_Department', 'Avdelning');
define('JS_LANG_Office', 'Kontor');
define('JS_LANG_Pager', 'Sökare');
define('JS_LANG_Other', 'Annat');
define('JS_LANG_OtherEmail', 'Annan Epostadress');
define('JS_LANG_Notes', 'Anteckningar');
define('JS_LANG_Groups', 'Grupper');
define('JS_LANG_ShowAddFields', 'Visa ytterligare fält');
define('JS_LANG_HideAddFields', 'Dölj ytterligare fält');
define('JS_LANG_EditContact', 'redigera kontaktinformation');
define('JS_LANG_GroupName', 'Gruppnamn');
define('JS_LANG_AddContacts', 'Lägg till kontakter');
define('JS_LANG_CommentAddContacts', '(Om du vill ange mer än en adress, separera dem med kommatecken)');
define('JS_LANG_CreateGroup', 'Skapa grupp');
define('JS_LANG_Rename', 'Döp om');
define('JS_LANG_MailGroup', 'Epostgrupp');
define('JS_LANG_RemoveFromGroup', 'Ta bort från grupp');
define('JS_LANG_UseImportTo', 'Använd import för att läsa in dina kontakter från Microsoft Outlook, Microsoft Outlook Express till din kontaktlista.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Välj fil (.CSV format) som du vill importera från');
define('JS_LANG_Import', 'Importera');
define('JS_LANG_ContactsMessage', 'Detta är kontaktsidan.');
define('JS_LANG_ContactsCount', 'kontakt/er');
define('JS_LANG_GroupsCount', 'grupp/er');

// webmail 4.1 constants
define('PicturesBlocked', 'Bilder har i detta meddelande blockerats för din säkerhet');
define('ShowPictures', 'Visa bilder');
define('ShowPicturesFromSender', 'Visa alltid bilder i meddelanden från denna avsändare');
define('AlwaysShowPictures', 'Visa alltid bilder i meddelanden');

define('TreatAsOrganization', 'Behandla som en organisation');

define('WarningGroupAlreadyExist', 'Grupp med detta namn finns redan. Uppge ett annat namn.');
define('WarningCorrectFolderName', 'Du måste ange ett korrekt namn för mappen.');
define('WarningLoginFieldBlank', 'You cannot leave Login field blank.');
define('WarningCorrectLogin', 'Du måste ange en korrekt inloggning');
define('WarningPassBlank', 'Du kan inte låta lösenordsfältet vara tomt.');
define('WarningCorrectIncServer', 'Du måste ange en giltig POP3(IMAP) serveradress.');
define('WarningCorrectSMTPServer', 'Du måste ange en korrekt SMTP serveradress.');
define('WarningFromBlank', 'Du kan inte lämna fältet Från tomt.');
define('WarningAdvancedDateFormat', 'Uppge ett tids och datumformat.');

define('AdvancedDateHelpTitle', 'Avancerade datuminställningar');
define('AdvancedDateHelpIntro', 'När &quot;avancerade datuminställningar&quot; är valda, kan du ange ett eget datumformat, vilket anges som med \':\' och \'/\' som avskiljare:');
define('AdvancedDateHelpConclusion', 'Till exempel, om du anger &quot;yyyy/mm/dd&quot; visas datum som år/månad/dag (ex.vis 2007/10/30).');
define('AdvancedDateHelpDayOfMonth', 'Dag i månaden (1 till 31)');
define('AdvancedDateHelpNumericMonth', 'Månad (1 till 12)');
define('AdvancedDateHelpTextualMonth', 'Månad (Jan till Dec)');
define('AdvancedDateHelpYear2', 'År, 2 siffror');
define('AdvancedDateHelpYear4', 'År, 4 siffror');
define('AdvancedDateHelpDayOfYear', 'Dag på året (1 till 366)');
define('AdvancedDateHelpQuarter', 'Kvartal');
define('AdvancedDateHelpDayOfWeek', 'Veckodag (1 till 7)');
define('AdvancedDateHelpWeekOfYear', 'Kalendervecka (1 till 53)');

define('InfoNoMessagesFound', 'Inga meddelanden funna');
define('ErrorSMTPConnect', 'Kan ej ansluta till SMTP-server. Kontrollera SMTP-inställningarna.');
define('ErrorSMTPAuth', 'Fel användarnanm och/eller lösenord. Autentisering misslyckades.');
define('ReportMessageSent', 'Ditt meddelande har skickats.');
define('ReportMessageSaved', 'Ditt meddelande har sparats.');
define('ErrorPOP3Connect', 'Kan ej ansluta till POP3-servern. Kontrollera POP3-inställningarna.');
define('ErrorIMAP4Connect', 'Kan ej ansluta till IMAP4-servern. Kontrollera IMAP4-inställningarna.');
define('ErrorPOP3IMAP4Auth', 'Fel epost/login och/eller lösenord. Autentisering misslyckades.');
define('ErrorGetMailLimit', 'Förlåt, din brevlåda är full.');

define('ReportSettingsUpdatedSuccessfuly', 'Inställningarna har uppdaterats.');
define('ReportAccountCreatedSuccessfuly', 'Kontot har skapats.');
define('ReportAccountUpdatedSuccessfuly', 'Kontot har uppdaterats.');
define('ConfirmDeleteAccount', 'Är du säker på att du vill ta bort kontot?');
define('ReportFiltersUpdatedSuccessfuly', 'Filterinställningar har uppdaterats.');
define('ReportSignatureUpdatedSuccessfuly', 'Signaturen har uppdaterats.');
define('ReportFoldersUpdatedSuccessfuly', 'Mappar har uppdaterats.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Kontaktens inställningar har uppdaterats.');

define('ErrorInvalidCSV', '.CSV filen du angav har felaktigt format.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Gruppen');
define('ReportGroupSuccessfulyAdded2', 'lades till.');
define('ReportGroupUpdatedSuccessfuly', 'Gruppen lades till.');
define('ReportContactSuccessfulyAdded', 'Kontakt lades till.');
define('ReportContactUpdatedSuccessfuly', 'Kontakten har uppdaterats.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Kontakt/er lades till gruppen');
define('AlertNoContactsGroupsSelected', 'Inga kontakter eller grupper valda.');

define('InfoListNotContainAddress', 'Om listan inte innehåller adressen du letar efter, försök med de inledande bokstäverna.');

define('DirectAccess', 'T');
define('DirectAccessTitle', 'Transparent läge. Webbmail hanterar meddelanden direkt på e-postservern.');

define('FolderInbox', 'Inkorgen');
define('FolderSentItems', 'Skickat');
define('FolderDrafts', 'Utkast');
define('FolderTrash', 'Papperskorgen');

define('FileLargerAttachment', 'Filen är större än tillåten storlek för bilagor.');
define('FilePartiallyUploaded', 'Filen bifogades inte i sin helhet pga ett okänt fel.');
define('NoFileUploaded', 'Ingen fil bifogades.');
define('MissingTempFolder', 'Temporär katalog saknas.');
define('MissingTempFile', 'Temporär fil saknas.');
define('UnknownUploadError', 'Ett okänt fel inträffade vid hämtning av bifogad fil.');
define('FileLargerThan', 'Fel vid bifoga fil. Troligen pga att filen är större än');
define('PROC_CANT_LOAD_DB', 'Kan ej ansluta till databasen.');
define('PROC_CANT_LOAD_LANG', 'Kan ej hitta begärd språkfil.');
define('PROC_CANT_LOAD_ACCT', 'Kontot finns inte, troligen har det raderats.');

define('DomainDosntExist', 'Domänen finns ej på servern.');
define('ServerIsDisable', 'E-postservern är tillfälligt stängd av administratören.');

define('PROC_ACCOUNT_EXISTS', 'Kontot kan ej skapas eftersom det redan existerar.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Fel i hämtning av antalet meddelanden.');
define('PROC_CANT_MAIL_SIZE', 'Fel i hämtning av utrymmesbegränsning.');

define('Organization', 'Organisation');
define('WarningOutServerBlank', 'Fältet SMTP Server får ej vara tomt');

define('JS_LANG_Refresh', 'Friska upp');
define('JS_LANG_MessagesInInbox', 'Meddelanden');
define('JS_LANG_InfoEmptyInbox', 'Nej meddelandena');

// webmail 4.2 constants
define('BackToList', 'Tillbaka till listan.');
define('InfoNoContactsGroups', 'Inga kontakter eller grupper.');
define('InfoNewContactsGroups', 'Du kan skapa nya kontakter/grupper eller importera kontakter från en .CSV-fil i Outlook format.');
define('DefTimeFormat', 'Standard tidsformat');
define('SpellNoSuggestions', 'Inga förslag');
define('SpellWait', 'Vänligen väntat&hellip;');

define('InfoNoMessageSelected', 'Inga meddelanden valda.');
define('InfoSingleDoubleClick', 'Enkelklicka för att förhandsgranska eller dubbelklicka för att se meddelandet i full storlek.');

// calendar
define('TitleDay', 'Dagsvy');
define('TitleWeek', 'Veckovy');
define('TitleMonth', 'Månadsvy');

define('ErrorNotSupportBrowser', 'AfterLogics kalender stödjer inte din läsare. Använd lägst FireFox 2.0/Opera 9.0/Internet Explorer 6.0/Safari 3.0.2 eller senare version.');
define('ErrorTurnedOffActiveX', 'Stöd för ActiveX är avstängt. <br/>För att använda denna tillämpning måste ActiveX tillåtas.');

define('Calendar', 'Kalender');

define('TabDay', 'Dag');
define('TabWeek', 'Vecka');
define('TabMonth', 'Månad');

define('ToolNewEvent', 'Ny&nbsp;avtalad&nbsp;tid');
define('ToolBack', 'Tillbaka');
define('ToolToday', 'Idag');
define('AltNewEvent', 'Ny avtalad tid');
define('AltBack', 'Tillbaka');
define('AltToday', 'Idag');
define('CalendarHeader', 'Kalender');
define('CalendarsManager', 'Kalenderansvarig');

define('CalendarActionNew', 'Ny kalender');
define('EventHeaderNew', 'Ny avtalad tid');
define('CalendarHeaderNew', 'Ny kalender');

define('EventSubject', 'Ämne');
define('EventCalendar', 'Kalender');
define('EventFrom', 'Från');
define('EventTill', 'till');
define('CalendarDescription', 'Beskrivning');
define('CalendarColor', 'Färg');
define('CalendarName', 'Kalendernamn');
define('CalendarDefaultName', 'Min kalender');

define('ButtonSave', 'Spara');
define('ButtonCancel', 'Ångra');
define('ButtonDelete', 'Radera');

define('AltPrevMonth', 'Föregående månad');
define('AltNextMonth', 'Nästa månad');

define('CalendarHeaderEdit', 'Redigera kalender');
define('CalendarActionEdit', 'Redigera kalender');
define('ConfirmDeleteCalendar', 'Är du säker på att du vill radera kalendern');
define('InfoDeleting', 'Raderar&hellip;');
define('WarningCalendarNameBlank', 'Kalendernamnet får ej vara tomt.');
define('ErrorCalendarNotCreated', 'Kalender skapades ej.');
define('WarningSubjectBlank', 'Ämnet kan ej vara blankt.');
define('WarningIncorrectTime', 'Specificerad tid består av ogiltiga tidstecken.');
define('WarningIncorrectFromTime', 'Från-tiden är felaktig.');
define('WarningIncorrectTillTime', 'Till-tiden är felaktig.');
define('WarningStartEndDate', 'Slutdatum måste vara efter eller lika med startdatum.');
define('WarningStartEndTime', 'Sluttid måste vara senare än starttid.');
define('WarningIncorrectDate', 'Datum är felaktig.');
define('InfoLoading', 'Laddar&hellip;');
define('EventCreate', 'Skapa avtalad tid');
define('CalendarHideOther', 'Göm andra kalendrar');
define('CalendarShowOther', 'Visa andra kalendrar');
define('CalendarRemove', 'Ta bort kalender');
define('EventHeaderEdit', 'Redigera avtalad tid');

define('InfoSaving', 'Sparar&hellip;');
define('SettingsDisplayName', 'Visningsnamn');
define('SettingsTimeFormat', 'Tidsformat');
define('SettingsDateFormat', 'Datumformat');
define('SettingsShowWeekends', 'Visa helger');
define('SettingsWorkdayStarts', 'Arbetsdag startar');
define('SettingsWorkdayEnds', 'slutar');
define('SettingsShowWorkday', 'Visa arbetsdagar');
define('SettingsWeekStartsOn', 'Vecka startar på');
define('SettingsDefaultTab', 'Standardflik');
define('SettingsCountry', 'Land');
define('SettingsTimeZone', 'Tidszon');
define('SettingsAllTimeZones', 'Alla tidszoner');

define('WarningWorkdayStartsEnds', 'Tid för \'Arbetsdag slutar\' måste vara större än tiden då arbetsdag börjar');
define('ReportSettingsUpdated', 'Inställningarna har sparats.');

define('SettingsTabCalendar', 'Kalender');

define('FullMonthJanuary', 'Januari');
define('FullMonthFebruary', 'Februari');
define('FullMonthMarch', 'Mars');
define('FullMonthApril', 'April');
define('FullMonthMay', 'Maj');
define('FullMonthJune', 'Juni');
define('FullMonthJuly', 'Juli');
define('FullMonthAugust', 'Augusti');
define('FullMonthSeptember', 'September');
define('FullMonthOctober', 'Oktober');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'December');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Apr');
define('ShortMonthMay', 'Maj');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Aug');
define('ShortMonthSeptember', 'Sep');
define('ShortMonthOctober', 'Okt');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dec');

define('FullDayMonday', 'Måndag');
define('FullDayTuesday', 'Tisdag');
define('FullDayWednesday', 'Onsdag');
define('FullDayThursday', 'Torsdag');
define('FullDayFriday', 'Fredag');
define('FullDaySaturday', 'Lördag');
define('FullDaySunday', 'Söndag');

define('DayToolMonday', 'Mån');
define('DayToolTuesday', 'Tis');
define('DayToolWednesday', 'Ons');
define('DayToolThursday', 'Tor');
define('DayToolFriday', 'Fre');
define('DayToolSaturday', 'Lör');
define('DayToolSunday', 'Sön');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'T');
define('CalendarTableDayWednesday', 'O');
define('CalendarTableDayThursday', 'T');
define('CalendarTableDayFriday', 'F');
define('CalendarTableDaySaturday', 'L');
define('CalendarTableDaySunday', 'S');

define('ErrorParseJSON', 'JSON-svar från servern kan ej tolkas.');

define('ErrorLoadCalendar', 'Kan ej hämta kalender');
define('ErrorLoadEvents', 'Kan ej hämta möte');
define('ErrorUpdateEvent', 'Kan ej spara möte');
define('ErrorDeleteEvent', 'Kan ej radera möte');
define('ErrorUpdateCalendar', 'Kan ej spara kalender');
define('ErrorDeleteCalendar', 'Kan ej radera kalender');
define('ErrorGeneral', 'Ett fel inträffade på servern. Vsv försök senare.');

// webmail 4.3 constants
define('SharedTitleEmail', 'Epost');
define('ShareHeaderEdit', 'Dela ut och publicera kalender');
define('ShareActionEdit', 'Dela ut och publicera kalender');
define('CalendarPublicate', 'Tillåt publik åtkomst till denna kalender');
define('CalendarPublicationLink', 'Länk');
define('ShareCalendar', 'Dela ut denna kalender');
define('SharePermission1', 'Kan ändra händelser och hantera utdelning');
define('SharePermission2', 'Kan ändra händelser');
define('SharePermission3', 'Kan se alla händelser');
define('SharePermission4', 'Kan enbart se fri/upptaget (dölj detaljer)');
define('ButtonClose', 'Stäng');
define('WarningEmailFieldFilling', 'Du måste fylla i epostfältet först');
define('EventHeaderView', 'Visa händelse');
define('ErrorUpdateSharing', 'Kan ej spara utdelade publiceringsdata');
define('ErrorUpdateSharing1', 'Kan ej dela ut till användare %s som inte existerar');
define('ErrorUpdateSharing2', 'Kan ej dela ut denna kalender till användare %s');
define('ErrorUpdateSharing3', 'Denna kalender delas redan med användare %s');
define('Title_MyCalendars', 'Mina kalendrar');
define('Title_SharedCalendars', 'Delade kalendrar');
define('ErrorGetPublicationHash', 'Kan ej skapa publiceringslänk');
define('ErrorGetSharing', 'Kan ej lägga till utdelning');
define('CalendarPublishedTitle', 'Denna kalender är publicerad');
define('RefreshSharedCalendars', 'Uppdatera Delade Kalendrar');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Medlemmar');

define('ReportMessagePartDisplayed', 'Notera att meddelandet enbart visas delvis.');
define('ReportViewEntireMessage', 'Visa hela meddelandet,');
define('ReportClickHere', 'Klicka här');
define('ErrorContactExists', 'En kontakt med det namnet och epostadress finns redan.');

define('Attachments', 'Bilagor');

define('InfoGroupsOfContact', 'Grupptillhörighet visas med bockmarkering.');
define('AlertNoContactsSelected', 'Inga kontakter valda.');
define('MailSelected', 'Eposta valda adresser');
define('CaptionSubscribed', 'Prenumererar');

define('OperationSpam', 'Skräppost');
define('OperationNotSpam', 'Ej skräppost');
define('FolderSpam', 'Skräppost');

// webmail 4.4 contacts
define('ContactMail', 'Epostkontakt');
define('ContactViewAllMails', 'Visa alla brev för denna kontakt');
define('ContactsMailThem', 'Skicka till dessa');
define('DateToday', 'Idag');
define('DateYesterday', 'Igår');
define('MessageShowDetails', 'Visa detaljer');
define('MessageHideDetails', 'Göm detaljer');
define('MessageNoSubject', 'Ämne saknas');
// john@gmail.com till nadine@gmail.com
define('MessageForAddr', 'till');
define('SearchClear', 'Rensa sök');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Sökresultat för "#s" i #f mapp:');
define('SearchResultsInAllFolders', 'Sökresultat för "#s" i alla mappar:');
define('AutoresponderTitle', 'Autosvar');
define('AutoresponderEnable', 'Aktivera autosvar');
define('AutoresponderSubject', 'Ämne');
define('AutoresponderMessage', 'Meddelande');
define('ReportAutoresponderUpdatedSuccessfuly', 'Autosvar har uppdaterats');
define('FolderQuarantine', 'Karantän');

//calendar
define('EventRepeats', 'Upprepepa');
define('NoRepeats', 'Upprepa Ej');
define('DailyRepeats', 'Dagligen');
define('WorkdayRepeats', 'Varje veckodag (må-fre)');
define('OddDayRepeats', 'Varje må, ons & fre');
define('EvenDayRepeats', 'Varje tisdag & torsdag');
define('WeeklyRepeats', 'Veckovis');
define('MonthlyRepeats', 'Månadsvis');
define('YearlyRepeats', 'Årsvis');
define('RepeatsEvery', 'Upprepa varje');
define('ThisInstance', 'Endast denna instans');
define('AllEvents', 'Alla händelser i serien');
define('AllFollowing', 'Alla följande');
define('ConfirmEditRepeatEvent', 'Vill du ändra denna händelse, alla, eller denna och framtida händelser i serien?');
define('RepeatEventHeaderEdit', 'Ändra återkommande händelse');
define('First', 'Första');
define('Second', 'Andra');
define('Third', 'Tredje');
define('Fourth', 'Fjärde');
define('Last', 'Sista');
define('Every', 'Varje');
define('SetRepeatEventEnd', 'Sätt slutdatum');
define('NoEndRepeatEvent', 'Inget slutdatum');
define('EndRepeatEventAfter', 'Upphör efter');
define('Occurrences', 'Händelser');
define('EndRepeatEventBy', 'Upphör vid');
define('EventCommonDataTab', 'Egenskaper');
define('EventRepeatDataTab', 'Upprepning');
define('RepeatEventNotPartOfASeries', 'Denna händelse har ändrats och ingår inte längre i serien.');
define('UndoRepeatExclusion', 'Ångra ändringar som ingår i serien.');

define('MonthMoreLink', '%d mer...');
define('NoNewSharedCalendars', 'Inga nya kalendrar');
define('NNewSharedCalendars', '%d nya kalendrar funna');
define('OneNewSharedCalendars', '1 ny kalender funnen');
define('ConfirmUndoOneRepeat', 'önskar du återställa denna händelse i serien?');

define('RepeatEveryDayInfin', 'Varje dag');
define('RepeatEveryDayTimes', 'Varje dag, %TIMES% gånger');
define('RepeatEveryDayUntil', 'Varje dag, till %UNTIL%');
define('RepeatDaysInfin', 'Varje %PERIOD% dagar');
define('RepeatDaysTimes', 'Varje %PERIOD% dagar, %TIMES% gånger');
define('RepeatDaysUntil', 'Varje %PERIOD% dagar, till %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Varje vecka och veckodag');
define('RepeatEveryWeekWeekdaysTimes', 'Varje vecka och veckodag, %TIMES% gånger');
define('RepeatEveryWeekWeekdaysUntil', 'Varje vecka och veckodag till %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Varje veckodag, Var %PERIOD%e vecka');
define('RepeatWeeksWeekdaysTimes', 'Var %PERIOD% vecka på veckodag, %TIMES% gånger');
define('RepeatWeeksWeekdaysUntil', 'Var %PERIOD% vecka på veckodag, till %UNTIL%');

define('RepeatEveryWeekInfin', 'Varje veckoa på %DAYS%');
define('RepeatEveryWeekTimes', 'Varje vecka på %DAYS%, %TIMES% gånger');
define('RepeatEveryWeekUntil', 'Varje vecka på %DAYS%, till %UNTIL%');
define('RepeatWeeksInfin', 'Varje %PERIOD% veckor på %DAYS%');
define('RepeatWeeksTimes', 'Varje %PERIOD% veckor på %DAYS%, %TIMES% gånger');
define('RepeatWeeksUntil', 'Varje %PERIOD% veckor på %DAYS%, till %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Varje månad på %DATE%');
define('RepeatEveryMonthDateTimes', 'Varje månad på %DATE%, %TIMES% gånger');
define('RepeatEveryMonthDateUntil', 'Varje månad på %DATE%, until %UNTIL%');
define('RepeatMonthsDateInfin', 'Varje %PERIOD% månad på %DATE%');
define('RepeatMonthsDateTimes', 'Varje %PERIOD% månad på %DATE%, %TIMES% gånger');
define('RepeatMonthsDateUntil', 'Varje %PERIOD% månad på %DATE%, till %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Varje månad på %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Varje månad på %NUMBER% %DAY%, %TIMES% gånger');
define('RepeatEveryMonthWDUntil', 'Varje månad på %NUMBER% %DAY%, till %UNTIL%');
define('RepeatMonthsWDInfin', 'Varje %PERIOD% månad på %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Varje %PERIOD% månad på %NUMBER% %DAY%, %TIMES% gånger');
define('RepeatMonthsWDUntil', 'Varje %PERIOD% månad på %NUMBER% %DAY%, till %UNTIL%');

define('RepeatEveryYearDateInfin', 'Varje år på %DATE%');
define('RepeatEveryYearDateTimes', 'Varje år på %DATE%, %TIMES% gånger');
define('RepeatEveryYearDateUntil', 'Varje år på %DATE%, till %UNTIL%');
define('RepeatYearsDateInfin', 'Varje %PERIOD% år på %DATE%');
define('RepeatYearsDateTimes', 'Varje %PERIOD% år på %DATE%, %TIMES% gånger');
define('RepeatYearsDateUntil', 'Varje %PERIOD% år på %DATE%, till %UNTIL%');

define('RepeatEveryYearWDInfin', 'Varje år på %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Varje år på %NUMBER% %DAY%, %TIMES% gånger');
define('RepeatEveryYearWDUntil', 'Varje år på %NUMBER% %DAY%, till %UNTIL%');
define('RepeatYearsWDInfin', 'Varje %PERIOD% år på %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Varje %PERIOD% år på %NUMBER% %DAY%, %TIMES% gånger');
define('RepeatYearsWDUntil', 'Varje %PERIOD% år på %NUMBER% %DAY%, till %UNTIL%');

define('RepeatDescDay', 'dag');
define('RepeatDescWeek', 'vecka');
define('RepeatDescMonth', 'månad');
define('RepeatDescYear', 'år');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Vänligen ange slutdatum för upprepning');
define('WarningWrongUntilDate', 'Slutdatum för upprepning måste vara senare än startdatum för upprepning');

define('OnDays', 'dagar');
define('CancelRecurrence', 'Avbryt upprepning');
define('RepeatEvent', 'Upprepa denna händelse');

define('Spellcheck', 'Stavningskontroll');
define('LoginLanguage', 'Språk');
define('LanguageDefault', 'Standard');

// webmail 4.5.x new
define('EmptySpam', 'Töm skräppost');
define('Saving', 'Sparar&hellip;');
define('Sending', 'Skickar&hellip;');
define('LoggingOffFromServer', 'Loggar ut från server&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Kan inte markera meddelande(n) som skräppost');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Kan inte markera meddelande(n) som INTE-skräppost');
define('ExportToICalendar', 'Exportera till iCalendar format');
define('ErrorMaximumUsersLicenseIsExceeded', 'Ditt konto är deaktiverat för att max antal användare enligt licensvillkoren har uppnåtts. Kontakata systemansvarig.');
define('RepliedMessageTitle', 'Besvarat meddelande');
define('ForwardedMessageTitle', 'Vidarebefordrat meddelande');
define('RepliedForwardedMessageTitle', 'Besvarat och vidarebefordrat meddelande');
define('ErrorDomainExist', 'Användaren kan inte skapas eftersom tillhörande domän saknas. Du måste skapa domänen först.');

// webmail 4.7
define('RequestReadConfirmation', 'Begär läs bekräftelse');
define('FolderTypeDefault', 'Standard');
define('ShowFoldersMapping', 'Låt mig välja annan folder som systemfolder (ex.vis MinFolder som Skickat)');
define('ShowFoldersMappingNote', 'Till exempel, för att ändra Skickat till MinFolder, ange "Skickat" som "använd" för "MinFolder".');
define('FolderTypeMapTo', 'använd');

define('ReminderEmailExplanation', 'Detta meddelande har levererats till din adress %EMAIL% för att du har ställt in påminnelse i din kalender: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Öppna kalender');

define('AddReminder', 'Påminn mig om denna händelse');
define('AddReminderBefore', 'Påminn mig % före denna händelse');
define('AddReminderAnd', 'och % före');
define('AddReminderAlso', 'och även % före');
define('AddMoreReminder', 'Fler påminnelser');
define('RemoveAllReminders', 'Ta bort alla påminnelser');
define('ReminderNone', 'Inga');
define('ReminderMinutes', 'minuter');
define('ReminderHour', 'timme');
define('ReminderHours', 'timmar');
define('ReminderDay', 'dag');
define('ReminderDays', 'dagar');
define('ReminderWeek', 'vecka');
define('ReminderWeeks', 'veckor');
define('Allday', 'Hela dagen');

define('Folders', 'Mapp');
define('NoSubject', 'Inget ämne');
define('SearchResultsFor', 'Sökresultat för');

define('Back', 'Tillbaka');
define('Next', 'Nästa');
define('Prev', 'Föregående');

define('MsgList', 'Meddelanden');
define('Use24HTimeFormat', '24-timmars format');
define('UseCalendars', 'Använd kalendrar');
define('Event', 'Avtalad tid');
define('CalendarSettingsNullLine', 'Inga kalendrar');
define('CalendarEventNullLine', 'Inga händelser');
define('ChangeAccount', 'Ändra konto');

define('TitleCalendar', 'Kalender');
define('TitleEvent', 'Avtalad tid');
define('TitleFolders', 'Folders');
define('TitleConfirmation', 'Confirmation');

define('Yes', 'Ja');
define('No', 'Nej');

define('EditMessage', 'Redigera meddelande');

define('AccountNewPassword', 'Nytt lösenord');
define('AccountConfirmNewPassword', 'Bekräfta nytt lösenord');
define('AccountPasswordsDoNotMatch', 'Lösenorden är olika');

define('ContactTitle', 'Titel');
define('ContactFirstName', 'Förnamn');
define('ContactSurName', 'Efternamn');

define('ContactNickName', 'Smeknamn');

define('CaptchaTitle', 'Bekräftelsekod (captcha)');
define('CaptchaReloadLink', 'uppdatera');
define('CaptchaError', 'Felaktig bekräftelsekod.');

define('WarningInputCorrectEmails', 'Ange giltig epostadress.');
define('WrongEmails', 'Felaktig/a epostadress/er:');

define('ConfirmBodySize1', 'Förlåt, men max storlek är');
define('ConfirmBodySize2', 'tecken. Allt utöver försvinner. Tryck "Ångra" om du vill redigera meddelandet');
define('BodySizeCounter', 'Räknare');
define('InsertImage', 'Infoga bild');
define('ImagePath', 'Sökväg');
define('ImageUpload', 'Infoga');
define('WarningImageUpload', 'Den bifogade filen är inte en bild. Välj en giltig bildfil.');

define('ConfirmExitFromNewMessage', 'Förändringar förloas om du lämnar denna sida. Vill du spara innan du går vidare?');

define('SensivityConfidential', 'Detta meddelande är konfidentiellt');
define('SensivityPrivate', 'Detta meddelande är privat');
define('SensivityPersonal', 'Detta meddelande är personligt');

define('ReturnReceiptTopText', 'Avsändaren har bett att bli varskodd när du fått detta meddelande.');
define('ReturnReceiptTopLink', 'Klicka här för att varsko avsändaren.');
define('ReturnReceiptSubject', 'Mottagningsbekräftelse (visad)');
define('ReturnReceiptMailText1', 'Detta är en mottagningsbekräftelse på det brev du skickat till');
define('ReturnReceiptMailText2', 'Vsv notera: Mottagningsbekräftelsen bekräftar enbart att meddelandet visades på mottagarens dator. Det går inte att bekräfta att meddelandet faktiskt har lästs eller förståtts.');
define('ReturnReceiptMailText3', 'med ämne');

define('SensivityMenu', 'Känslighet');
define('SensivityNothingMenu', 'Normalt');
define('SensivityConfidentialMenu', 'Konfidentiellt');
define('SensivityPrivateMenu', 'Privat');
define('SensivityPersonalMenu', 'Personligt');

define('ErrorLDAPonnect', 'Kan ej kommunicera med LDAP-Server.');

define('MessageSizeExceedsAccountQuota', 'Detta meddelandes storlek överskrider din kontogräns.');
define('MessageCannotSent', 'Meddelandet kan inte skickas.');
define('MessageCannotSaved', 'Meddelandet kan inte sparas.');

define('ContactFieldTitle', 'Fält');
define('ContactDropDownTO', 'Till');
define('ContactDropDownCC', 'Kopia');
define('ContactDropDownBCC', 'Hemlig kopia');

// 4.9
define('NoMoveDelete', 'Meddelande/na kan inte flyttas till papperskorgen. Troligen är ditt konto fullt. Ska meddelande/na raderas helt i stället?');

define('WarningFieldBlank', 'Detta fält får ej vara tomt.');
define('WarningPassNotMatch', 'Lösenorden matchar inte varandra.');
define('PasswordResetTitle', 'Lösenordsåterställning - steg %d');
define('NullUserNameonReset', 'användare');
define('IndexResetLink', 'Glömt lösenordet?');
define('IndexRegLink', 'Registrera konto');

define('RegDomainNotExist', 'Domänen finns inte.');
define('RegAnswersIncorrect', 'Felaktigt svar.');
define('RegUnknownAdress', 'Okänd e-postadress.');
define('RegUnrecoverableAccount', 'Lösenordsåterställning kan ej verkställas för denna e-postadress.');
define('RegAccountExist', 'Adressen används redan.');
define('RegRegistrationTitle', 'Registrering');
define('RegName', 'Namn');
define('RegEmail', 'e-postadress');
define('RegEmailDesc', 'Exempelvis, mittnamn@mindomän.se. Denna information används för åtkomst.');
define('RegSignMe', 'Kom ihåg mig');
define('RegSignMeDesc', 'Fråga inte efter användarnamn och lösen vid nästa inloggning från denna dator.');
define('RegPass1', 'Lösenord');
define('RegPass2', 'Upprepa lösenord ');
define('RegQuestionDesc', 'Vänligen lägg in två privata hemliga frågor som enbart du känner svaren på. Förlorar du ditt lösenord används dessa för lösenordsåterställning.');
define('RegQuestion1', 'Hemlig fråga 1');
define('RegAnswer1', 'Svar nr 1');
define('RegQuestion2', 'Hemlig fråga 2');
define('RegAnswer2', 'Svar nr 2');
define('RegTimeZone', 'Tidszon');
define('RegLang', 'Språk');
define('RegCaptcha', 'Bekräftelsekod (captcha)');
define('RegSubmitButtonValue', 'Registrera');

define('ResetEmail', 'Uppge din e-postadress');
define('ResetEmailDesc', 'Uppge de e-postadresser som du har registrerat.');
define('ResetCaptcha', 'Bekräftelskod (captcha)');
define('ResetSubmitStep1', 'Skicka');
define('ResetQuestion1', 'Hemlig fråga 1');
define('ResetAnswer1', 'Svar');
define('ResetQuestion2', 'Hemlig fråga 2');
define('ResetAnswer2', 'Svar');
define('ResetSubmitStep2', 'Skicka');

define('ResetTopDesc1Step2', 'Uppge e-postadress');
define('ResetTopDesc2Step2', 'Bekräfta riktigheten.');

define('ResetTopDescStep3', 'Uppge nytt lösenord för e-post.');

define('ResetPass1', 'Nytt lösenord');
define('ResetPass2', 'Upprepa lösenord');
define('ResetSubmitStep3', 'Skicka');
define('ResetDescStep4', 'Ditt lösenord har ändrats.');
define('ResetSubmitStep4', 'Skicka');

define('RegReturnLink', 'Återgå till inloggning');
define('ResetReturnLink', 'Återgå till inloggning');

// Appointments
define('AppointmentAddGuests', 'Lägg till deltagare');
define('AppointmentRemoveGuests', 'Ångra');
define('AppointmentListEmails', 'Uppge e-postadresser åtskildja med komma och tryck sedan på spara');
define('AppointmentParticipants', 'Deltagare');
define('AppointmentRefused', 'Neka');
define('AppointmentAwaitingResponse', 'Avvaktar svar');
define('AppointmentInvalidGuestEmail', 'Följande deltagares e-postadresser är ogiltiga:');
define('AppointmentOwner', 'Ägare');

define('AppointmentMsgTitleInvite', 'Bjud in till möte.');
define('AppointmentMsgTitleUpdate', 'Mötet har ändrats.');
define('AppointmentMsgTitleCancel', 'Mötet har avbokats.');
define('AppointmentMsgTitleRefuse', 'Inbjuden %guest% har nekat inbjudan till möte');
define('AppointmentMoreInfo', 'Mer information');
define('AppointmentOrganizer', 'Arrangör');
define('AppointmentEventInformation', 'Mötesinformation');
define('AppointmentEventWhen', 'När');
define('AppointmentEventParticipants', 'Deltagare');
define('AppointmentEventDescription', 'Beskrivning');
define('AppointmentEventWillYou', 'ska du delta');
define('AppointmentAdditionalParameters', 'ytterligare parametrar');
define('AppointmentHaventRespond', 'Har inte svarat än');
define('AppointmentRespondYes', 'Kommer delta');
define('AppointmentRespondMaybe', 'Osäker');
define('AppointmentRespondNo', 'Kommer EJ delta');
define('AppointmentGuestsChangeEvent', 'Deltagare kan redigera mötet');

define('AppointmentSubjectAddStart', 'Du har bjudits in till möte ');
define('AppointmentSubjectAddFrom', 'från ');
define('AppointmentSubjectUpdateStart', 'Redigera möte');
define('AppointmentSubjectDeleteStart', 'Ställ in möte');
define('ErrorAppointmentChangeRespond', 'Kan ej redigera möte');
define('SettingsAutoAddInvitation', 'Acceptera inbjudningar och lägg till i kalendern automatiskt');
define('ReportEventSaved', 'Ditt möte har sparats');
define('ReportAppointmentSaved', 'och inbjudningar har skickats');
define('ErrorAppointmentSend', 'Kan ej skicka inbjudan.');
define('AppointmentEventName', 'Namn:');

// End appointments

define('ErrorCantUpdateFilters', 'Kan ej uppdatera filter');

define('FilterPhrase', 'För fält %field med %condition %string utför %action');
define('FiltersAdd', 'Lägg till filter');
define('FiltersCondEqualTo', 'lika med');
define('FiltersCondContainSubstr', 'innehåller text');
define('FiltersCondNotContainSubstr', 'men innehåller ej text');
define('FiltersActionDelete', 'radera meddelande');
define('FiltersActionMove', 'flytta');
define('FiltersActionToFolder', 'till %folder mapp');
define('FiltersNo', 'Inga filter definierade');

define('ReminderEmailFriendly', 'påminnelse');
define('ReminderEventBegin', 'startar: ');

define('FiltersLoading', 'Läser in filter...');
define('ConfirmMessagesPermanentlyDeleted', 'Alla meddelanden i denna mapp kommer raderas permanent.');

define('InfoNoNewMessages', 'Inga nya meddelanden');
define('TitleImportContacts', 'Importera kontakter');
define('TitleSelectedContacts', 'valda kontakter');
define('TitleNewContact', 'Ny kontakt');
define('TitleViewContact', 'Visa kontakt');
define('TitleEditContact', 'Redigera kontakt');
define('TitleNewGroup', 'Ny grupp');
define('TitleViewGroup', 'Visa grupp');

define('AttachmentComplete', 'Färdig.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'intervall för automatisk uppdatering');
define('AutoCheckMailIntervalDisableName', 'Deaktivera');

define('ReportCalendarSaved', 'Kalender har sparats.');

define('ContactSyncError', 'Synkronisering misslyckades');
define('ReportContactSyncDone', 'Synkronisering klar');

define('MobileSyncUrlTitle', 'Mobil synklänk');
define('MobileSyncLoginTitle', 'Mobil synkinloggning');

define('QuickReply', 'snabbsvar');
define('SwitchToFullForm', 'Växla till helformat');
define('SortFieldDate', 'Datum');
define('SortFieldFrom', 'Från');
define('SortFieldSize', 'Storlek');
define('SortFieldSubject', 'Ämne');
define('SortFieldFlag', 'Flagga');
define('SortFieldAttachments', 'Bilagor');
define('SortOrderAscending', 'Stigande');
define('SortOrderDescending', 'Sjunkande');
define('ArrangedBy', 'Arrangerad av');

define('MessagePaneToRight', 'Meddelandefönster till höger om meddelandelista, i stället för nedanför');

define('SettingsTabMobileSync', 'Mobil Synkronising');

define('MobileSyncContactDataBaseTitle', 'Mobil synkkontaktdatabas');
define('MobileSyncCalendarDataBaseTitle', 'Mobil synkkalenderdatabas');
define('MobileSyncTitleText', 'Om du önskar synkronisera din SyncML-kapabla mobila enhet med Webbmail, kan du använda dessa parametrar.<br />"Mobil synklänk" är adressen till SyncML synkroniseringsserver. "Mobil synkinloggning" är din inloggning för synkronisering, och du ska ange ditt personliga lösenord när det efterfrågas. Några mobila enheter kräver att man uppger databasnamn för kontakt- och kalenderdata.<br />Använd i så fall "Mobil synkkontaktdatabas" respektive "Mobil synkkalenderdatabas".');
define('MobileSyncEnableLabel', 'Aktivera mobilsynk');

define('SearchInputText', 'sök');

define('AppointmentEmailExplanation', 'Detta meddelande kom till ditt konto %EMAIL% för du bjöds till händelsen av %ORGANAZER%');

define('Searching', 'Söker&hellip;');

define('ButtonSetupSpecialFolders', 'Hantera specialmappar');
define('ButtonSaveChanges', 'Spara ändringar');
define('InfoPreDefinedFolders', 'För för-definierade mappar, använd dessa IMAP mappar');

define('SaveMailInSentItems', 'Spara även i mappen Skickat');

define('CouldNotSaveUploadedFile', 'Kunde EJ spara mottagen fil.');

define('AccountOldPassword', 'Nuvarande lösenord');
define('AccountOldPasswordsDoNotMatch', 'Nuvarande Lösenord felaktigt');

define('DefEditor', 'Standard textbehandlare');
define('DefEditorRichText', 'Rik Text');
define('DefEditorPlainText', 'Simpel Text');

define('Layout', 'Layout');

define('TitleNewMessagesCount', '%count% nya meddelande');

define('AltOpenInNewWindow', 'Öppna i nytt fönster');

define('SearchByFirstCharAll', 'Alla');

define('FolderNoUsageAssigned', 'Ingen dedikerad användning');

define('InfoSetupSpecialFolders', 'För att matcha specialmappar (ex.vis. skickat) klicka på "Hantera specialmappar"');

define('FileUploaderClickToAttach', 'Klicka för att bifoga fil');
define('FileUploaderOrDragNDrop', 'eller dra och släpp filer här');

define('AutoCheckMailInterval1Minute', '1 minut');
define('AutoCheckMailInterval3Minutes', '3 minuter');
define('AutoCheckMailInterval5Minutes', '5 minuter');
define('AutoCheckMailIntervalMinutes', 'minuter');

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
