<?php
// Danish translation by Mike Johnsen/Jeppe Richardt/Andreas Christensen
// Last edited 25-01-2011: AC
define('PROC_ERROR_ACCT_CREATE', 'Der opstod en fejl ved oprettelsen af konto');
define('PROC_WRONG_ACCT_PWD', 'Forkert konto adgangskode');
define('PROC_CANT_LOG_NONDEF', 'Kan ikke logge ind i ikke-standard konto');
define('PROC_CANT_INS_NEW_FILTER', 'Kan ikke oprette nyt filter');
define('PROC_FOLDER_EXIST', 'En mappe med det navn eksisterer allerede');
define('PROC_CANT_CREATE_FLD', 'Kan ikke oprette mappe');
define('PROC_CANT_INS_NEW_GROUP', 'Kan ikke oprette ny gruppe');
define('PROC_CANT_INS_NEW_CONT', 'Kan ikke oprette ny kontakt');
define('PROC_CANT_INS_NEW_CONTS', 'Kan ikke oprette nye kontakter');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Kan ikke tilføje kontakt(er) til gruppe');
define('PROC_ERROR_ACCT_UPDATE', 'Der opstod en fejl ved opdatering af konto');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Kan ikke opdatere kontakt indstillinger');
define('PROC_CANT_GET_SETTINGS', 'Kan ikke hente indstillinger');
define('PROC_CANT_UPDATE_ACCT', 'Kan ikke opdatere indstillinger');
define('PROC_ERROR_DEL_FLD', 'Der opstod en fejl ved sletning af mappe(r)');
define('PROC_CANT_UPDATE_CONT', 'Kan ikke opdatere kontakt');
define('PROC_CANT_GET_FLDS', 'Kan ikke læse mappe træ');
define('PROC_CANT_GET_MSG_LIST', 'Kan ikke læse email liste');
define('PROC_MSG_HAS_DELETED', 'Denne email er allerede blevet slettet fra mail serveren');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Kan ikke hente kontakt indstillinger');
define('PROC_CANT_LOAD_SIGNATURE', 'Kan ikke hente konto signatur');
define('PROC_CANT_GET_CONT_FROM_DB', 'Kan ikke hente kontakt fra DB');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Kan ikke hente kontakt(er) fra DB');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Kan ikke slette konto');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Kan ikke slette filter');
define('PROC_CANT_DEL_CONT_GROUPS', 'Kan ikke slette kontakt(er) og/eller gruppe(r)');
define('PROC_WRONG_ACCT_ACCESS', 'Et forsøg på uautoriseret adgang til en konto tilhørende en anden bruger er blevet opdaget.');
define('PROC_SESSION_ERROR', 'Den tidligere session blev lukket pga en timeout.');

define('MailBoxIsFull', 'Mailbox er fuld');
define('WebMailException', 'Webmail undtagelse opstod');
define('InvalidUid', 'Ugyldig email UID');
define('CantCreateContactGroup', 'Kan ikke oprette kontakt gruppe');
define('CantCreateUser', 'Kan ikke oprette bruger');
define('CantCreateAccount', 'Kan ikke oprette konto');
define('SessionIsEmpty', 'Session er tom');
define('FileIsTooBig', 'Filen er for stor');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Kan ikke markere alle emails som læst');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Kan ikke markere alle emails som ulæst');
define('PROC_CANT_PURGE_MSGS', 'Kan ikke rense email(s)');
define('PROC_CANT_DEL_MSGS', 'Kan ikke slette email(s)');
define('PROC_CANT_UNDEL_MSGS', 'Kan ikke fortryde sletning af email(s)');
define('PROC_CANT_MARK_MSGS_READ', 'Kan ikke markere email(s) som læst');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Kan ikke markere email(s) som ulæst');
define('PROC_CANT_SET_MSG_FLAGS', 'Kan ikke sætte email flag');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Kan ikke fjerne email flag');
define('PROC_CANT_CHANGE_MSG_FLD', 'Kan ikke ændre email mappe');
define('PROC_CANT_SEND_MSG', 'Kan ikke sende email.');
define('PROC_CANT_SAVE_MSG', 'Kan ikke gemme email.');
define('PROC_CANT_GET_ACCT_LIST', 'Kan ikke hente konto oversigten');
define('PROC_CANT_GET_FILTER_LIST', 'Kan ikke hente filter oversigten');

define('PROC_CANT_LEAVE_BLANK', 'Du kan ikke lade * felter stå tomme');

define('PROC_CANT_UPD_FLD', 'Kan ikke opdatere mappe');
define('PROC_CANT_UPD_FILTER', 'Kan ikke opdatere filter');

define('ACCT_CANT_ADD_DEF_ACCT', 'Denne konto kan ikke blive tilføjet fordi den bliver brugt at en anden bruger.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Konto status kan ikke ændres til standard.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Kan ikke oprette ny konto (IMAP4 forbindelses fejl)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Kan ikke slette sidste standard konto');

define('LANG_LoginInfo', 'Login Information');
define('LANG_Email', 'Email');
define('LANG_Login', 'Brugernavn');
define('LANG_Password', 'Adgangskode');
define('LANG_IncServer', 'Indgående Mail');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'SMTP Server');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Brug SMTP godkendelse');
define('LANG_SignMe', 'Automatisk login');
define('LANG_Enter', 'Log ind');

// interface strings
define('JS_LANG_TitleLogin', 'Login');
define('JS_LANG_TitleMessagesListView', 'Email liste');
define('JS_LANG_TitleMessagesList', 'Email liste');
define('JS_LANG_TitleViewMessage', 'Vis email');
define('JS_LANG_TitleNewMessage', 'Ny email');
define('JS_LANG_TitleSettings', 'Indstillinger');
define('JS_LANG_TitleContacts', 'Kontakter');

define('JS_LANG_StandardLogin', 'Standard&nbsp;Login');
define('JS_LANG_AdvancedLogin', 'Avanceret&nbsp;Login');

define('JS_LANG_InfoWebMailLoading', 'Vent venligst mens webmail henter indstillingerne&hellip;');
define('JS_LANG_Loading', 'Loader&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Vent venligst mens webmail henter email listen');
define('JS_LANG_InfoEmptyFolder', 'Mappen er tom');
define('JS_LANG_InfoPageLoading', 'Siden loader stadig&hellip;');
define('JS_LANG_InfoSendMessage', 'Emailen blev sendt');
define('JS_LANG_InfoSaveMessage', 'Emailen blev gemt');
define('JS_LANG_InfoHaveImported', 'Du har importeret');
define('JS_LANG_InfoNewContacts', 'ny(e) kontakt(er) i din kontakt liste.');
define('JS_LANG_InfoToDelete', 'For at slette');
define('JS_LANG_InfoDeleteContent', 'mappen skal du slette al dens indhold først.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Det er ikke tilladt at slette mapper med indhold. For at slette ikke markerbare mapper, slet deres indhold først.');
define('JS_LANG_InfoRequiredFields', '* skal udfyldes');

define('JS_LANG_ConfirmAreYouSure', 'Er du sikker?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'De valgte email(s) vil blive PERMANENT slettet! Er du sikker?');
define('JS_LANG_ConfirmSaveSettings', 'Indstillingerne er ikke gemt. Klik OK for at gemme.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Kontakt indstillingerne er ikke gemt. Klik OK for at gemme.');
define('JS_LANG_ConfirmSaveAcctProp', 'Konto indstillingerne er ikke gemt. Klik OK for at gemme.');
define('JS_LANG_ConfirmSaveFilter', 'Filter indstillingerne er ikke gemt. Klik OK for at gemme.');
define('JS_LANG_ConfirmSaveSignature', 'Signaturen er ikke gemt. Klik OK for at gemme.');
define('JS_LANG_ConfirmSavefolders', 'Mapperne er ikke gemt. Klik OK for at gemme.');
define('JS_LANG_ConfirmHtmlToPlain', 'Advarsel: Ved at ændre formateringen af denne email fra HTML til ren tekst vil du tabe al nuværende formatering i din nuværende email. Klik OK for at fortsætte.');
define('JS_LANG_ConfirmAddFolder', 'For at tilføje en mappe er det nødvendigt at gemme ændringerne. Klik OK for at gemme.');
define('JS_LANG_ConfirmEmptySubject', 'Emne feltet er tomt. Vil du fortsætte?');

define('JS_LANG_WarningEmailBlank', 'Email: feltet skal<br />udfyldes');
define('JS_LANG_WarningLoginBlank', 'Brugernavn: feltet skal<br />udfyldes');
define('JS_LANG_WarningToBlank', 'Til: feltet skal udfyldes');
define('JS_LANG_WarningServerPortBlank', 'POP3 og SMTP port nummeret skal udfyldes');
define('JS_LANG_WarningEmptySearchLine', 'Tom søge linje. Indtast det ord du vil søge efter.');
define('JS_LANG_WarningMarkListItem', 'Marker mindst en ting i listen');
define('JS_LANG_WarningFolderMove', 'Mappen kan ikke flyttes da den ligger på et andet niveau');
define('JS_LANG_WarningContactNotComplete', 'Indtast email eller navn');
define('JS_LANG_WarningGroupNotComplete', 'Indtast gruppe navn');

define('JS_LANG_WarningEmailFieldBlank', 'Email feltet skal udfyldes');
define('JS_LANG_WarningIncServerBlank', 'POP3(IMAP4) feltet skal udfyldes');
define('JS_LANG_WarningIncPortBlank', 'POP3(IMAP4) port nummer skal udfyldes');
define('JS_LANG_WarningIncLoginBlank', 'POP3(IMAP4) brugernavnet skal udfyldes');
define('JS_LANG_WarningIncPortNumber', 'Feltet POP3(IMAP4) skal indeholde tal.');
define('JS_LANG_DefaultIncPortNumber', 'Standard POP3(IMAP4) port nummer er 110(143).');
define('JS_LANG_WarningIncPassBlank', 'POP3(IMAP4) adgangskoden skal udfyldes');
define('JS_LANG_WarningOutPortBlank', 'SMTP Server port skal udfyldes');
define('JS_LANG_WarningOutPortNumber', 'SMTP port feltet skal indeholde tal.');
define('JS_LANG_WarningCorrectEmail', 'Indtast korrekt email.');
define('JS_LANG_DefaultOutPortNumber', 'Standard SMTP port nummer er 25.');

define('JS_LANG_WarningCsvExtention', 'Endelse skal være .csv');
define('JS_LANG_WarningImportFileType', 'Vælg hvilket program du til kopiere dine kontakter fra');
define('JS_LANG_WarningEmptyImportFile', 'Vælg en fil ved at klikke på gennemse knappen');

define('JS_LANG_WarningContactsPerPage', 'Kontakter pr. side værdi er et positivt nummer');
define('JS_LANG_WarningMessagesPerPage', 'Emails pr. side er et positivt nummer');
define('JS_LANG_WarningMailsOnServerDays', 'Indtast et tal i feltet Beskeder på server dage.');
define('JS_LANG_WarningEmptyFilter', 'Indtast søgeord');
define('JS_LANG_WarningEmptyFolderName', 'Indtast mappe navn');

define('JS_LANG_ErrorConnectionFailed', 'Forbindelse mislykkedes');
define('JS_LANG_ErrorRequestFailed', 'Data overførslen er ikke gennemført');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Objektet XMLHttpRequest mangler');
define('JS_LANG_ErrorWithoutDesc', 'Fejl uden beskrivelse opstod');
define('JS_LANG_ErrorParsing', 'Fejl ved gennemgang af XML.');
define('JS_LANG_ResponseText', 'Svar tekst:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Tøm XML pakke');
define('JS_LANG_ErrorImportContacts', 'Fejl ved importering af kontakter');
define('JS_LANG_ErrorNoContacts', 'Ingen kontakter til importering');
define('JS_LANG_ErrorCheckMail', 'Der opstod en fejl ved modtagelse af emails.');
define('JS_LANG_LoggingToServer', 'Logger på serveren&hellip;');
define('JS_LANG_GettingMsgsNum', 'Henter antallet af beskeder');
define('JS_LANG_RetrievingMessage', 'Modtager post');
define('JS_LANG_DeletingMessage', 'Sletter email');
define('JS_LANG_DeletingMessages', 'Sletter emails');
define('JS_LANG_Of', 'af');
define('JS_LANG_Connection', 'Forbindelse');
define('JS_LANG_Charset', 'Charset');
define('JS_LANG_AutoSelect', 'Auto-Vælg');

define('JS_LANG_Contacts', 'Kontakter');
define('JS_LANG_ClassicVersion', 'Klassisk version');
define('JS_LANG_Logout', 'Logud');
define('JS_LANG_Settings', 'Indstillinger');

define('JS_LANG_LookFor', 'Søg efter: ');
define('JS_LANG_SearchIn', 'Søg i: ');
define('JS_LANG_QuickSearch', 'Søg kun i Fra, Til og Emne felterne (Hurtigere).');
define('JS_LANG_SlowSearch', 'Søg i hele emailen');
define('JS_LANG_AllMailFolders', 'Alle mapper');
define('JS_LANG_AllGroups', 'Alle grupper');

define('JS_LANG_NewMessage', 'Ny email');
define('JS_LANG_CheckMail', 'Tjek email');
define('JS_LANG_EmptyTrash', 'Tøm papirkurv');
define('JS_LANG_MarkAsRead', 'Marker som læst');
define('JS_LANG_MarkAsUnread', 'Marker som ulæst');
define('JS_LANG_MarkFlag', 'Sæt flag');
define('JS_LANG_MarkUnflag', 'Fjern flag');
define('JS_LANG_MarkAllRead', 'Marker alle som læst');
define('JS_LANG_MarkAllUnread', 'Marker alle som ulæst');
define('JS_LANG_Reply', 'Besvar');
define('JS_LANG_ReplyAll', 'Besvar alle');
define('JS_LANG_Delete', 'Slet');
define('JS_LANG_Undelete', 'Genskab');
define('JS_LANG_PurgeDeleted', 'Rens slettede');
define('JS_LANG_MoveToFolder', 'Flyt til mappe');
define('JS_LANG_Forward', 'Videresend');

define('JS_LANG_HideFolders', 'Skjul mapper');
define('JS_LANG_ShowFolders', 'Vis mapper');
define('JS_LANG_ManageFolders', 'Mapper');
define('JS_LANG_SyncFolder', 'Synkroniser mappe');
define('JS_LANG_NewMessages', 'Nye emails');
define('JS_LANG_Messages', 'Email(s)');

define('JS_LANG_From', 'Fra');
define('JS_LANG_To', 'Til');
define('JS_LANG_Date', 'Dato');
define('JS_LANG_Size', 'Størrelse');
define('JS_LANG_Subject', 'Emne');

define('JS_LANG_FirstPage', 'Første side');
define('JS_LANG_PreviousPage', 'Forrige side');
define('JS_LANG_NextPage', 'Næste side');
define('JS_LANG_LastPage', 'Sidste side');

define('JS_LANG_SwitchToPlain', 'Skift til ren tekst');
define('JS_LANG_SwitchToHTML', 'Skift til HTML');
define('JS_LANG_AddToAddressBook', 'Tilføj til kontakter');
define('JS_LANG_ClickToDownload', 'Klik for at hente');
define('JS_LANG_View', 'Vis');
define('JS_LANG_ShowFullHeaders', 'Vis hele brevhovedet');
define('JS_LANG_HideFullHeaders', 'Skjul brevhovedet');

define('JS_LANG_MessagesInFolder', 'E-mails i mappen');
define('JS_LANG_YouUsing', 'Du bruger');
define('JS_LANG_OfYour', 'af dine');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Send');
define('JS_LANG_SaveMessage', 'Gem');
define('JS_LANG_Print', 'Udskriv');
define('JS_LANG_PreviousMsg', 'Forrige email');
define('JS_LANG_NextMsg', 'Næste email');
define('JS_LANG_AddressBook', 'Kontakter');
define('JS_LANG_ShowBCC', 'Vis BCC');
define('JS_LANG_HideBCC', 'Skjul BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Besvar til');
define('JS_LANG_AttachFile', 'Vedhæft fil');
define('JS_LANG_Attach', 'Vedhæft');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Original email');
define('JS_LANG_Sent', 'Sendt');
define('JS_LANG_Fwd', 'Vds');
define('JS_LANG_Low', 'Lav');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Høj');
define('JS_LANG_Importance', 'Vigtighed');
define('JS_LANG_Close', 'Luk');

define('JS_LANG_Common', 'Generelt');
define('JS_LANG_EmailAccounts', 'Email konti');

define('JS_LANG_MsgsPerPage', 'Emails pr side');
define('JS_LANG_DisableRTE', 'Deaktiver formateret-tekst editor');
define('JS_LANG_Skin', 'Tema');
define('JS_LANG_DefCharset', 'Standard tegnsæt');
define('JS_LANG_DefCharsetInc', 'Standard indgående tegnsæt');
define('JS_LANG_DefCharsetOut', 'Standard udgående tegnsæt');
define('JS_LANG_DefTimeOffset', 'Standard tids forskel');
define('JS_LANG_DefLanguage', 'Standard sprog');
define('JS_LANG_DefDateFormat', 'Standard dato format');
define('JS_LANG_ShowViewPane', 'Email liste med læseområde');
define('JS_LANG_Save', 'Gem');
define('JS_LANG_Cancel', 'Annuller');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Fjern');
define('JS_LANG_AddNewAccount', 'Tilføj ny konto');
define('JS_LANG_Signature', 'Signatur');
define('JS_LANG_Filters', 'Filtre');
define('JS_LANG_Properties', 'Egenskaber');
define('JS_LANG_UseForLogin', 'Brug disse konto egenskaber(brugernavn og adgangskode) til at logge ind på webmailen');
define('JS_LANG_MailFriendlyName', 'Dit navn');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Indgående email server');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Brugernavn');
define('JS_LANG_MailIncPass', 'Adgangskode');
define('JS_LANG_MailOutHost', 'SMTP Server');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP brugernavn');
define('JS_LANG_MailOutPass', 'SMTP adgangskode');
define('JS_LANG_MailOutAuth1', 'Brug SMTP godkendelse');
define('JS_LANG_MailOutAuth2', '(Du kan lade felterne SMTP brugernavn/adgangskode stå tomme, hvis de er det samme som  POP3/IMAP4 brugernavn/adgangskode)');
define('JS_LANG_UseFriendlyNm1', 'Brug venligt navn i "Fra:" feltet');
define('JS_LANG_UseFriendlyNm2', '(Dit navn &lt;afsender@mail.dk&gt;)');
define('JS_LANG_GetmailAtLogin', 'Hent/Synkroniser emails ved log ind');
define('JS_LANG_MailMode0', 'Slet modtagne emails fra serveren');
define('JS_LANG_MailMode1', 'Lad emails blive på serveren');
define('JS_LANG_MailMode2', 'Gem emails på serveren');
define('JS_LANG_MailsOnServerDays', 'dag(e)');
define('JS_LANG_MailMode3', 'Slet emails på serveren når de bliver slettet fra papirkurven');
define('JS_LANG_InboxSyncType', 'Type af indbakke synkronisering');

define('JS_LANG_SyncTypeNo', 'Synkroniser ikke');
define('JS_LANG_SyncTypeNewHeaders', 'Nye brevhoveder');
define('JS_LANG_SyncTypeAllHeaders', 'Alle brevhoveder');
define('JS_LANG_SyncTypeNewMessages', 'Nye emails');
define('JS_LANG_SyncTypeAllMessages', 'Alle emails');
define('JS_LANG_SyncTypeDirectMode', 'Direkte tilstand');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Alle brevhoveder');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Alle emails');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direkte tilstand');

define('JS_LANG_DeleteFromDb', 'Slet emails fra databasen hvis de ikke længere eksisterer på serveren');

define('JS_LANG_EditFilter', 'Rediger&nbsp;filter');
define('JS_LANG_NewFilter', 'Tilføj nyt filter');
define('JS_LANG_Field', 'Felt');
define('JS_LANG_Condition', 'Betingelse');
define('JS_LANG_ContainSubstring', 'Indeholder tekst');
define('JS_LANG_ContainExactPhrase', 'Indeholder sætning');
define('JS_LANG_NotContainSubstring', 'Indeholder ikke tekst');
define('JS_LANG_FilterDesc_At', 'på');
define('JS_LANG_FilterDesc_Field', 'felt');
define('JS_LANG_Action', 'Handling');
define('JS_LANG_DoNothing', 'Gør intet');
define('JS_LANG_DeleteFromServer', 'Slet fra serveren med det samme');
define('JS_LANG_MarkGrey', 'Marker gråt');
define('JS_LANG_Add', 'Tilføj');
define('JS_LANG_OtherFilterSettings', 'Andre filter indstillinger');
define('JS_LANG_ConsiderXSpam', 'Overvej X-Spam brevhoveder');
define('JS_LANG_Apply', 'Anvend');

define('JS_LANG_InsertLink', 'Indsæt Link');
define('JS_LANG_RemoveLink', 'Fjern Link');
define('JS_LANG_Numbering', 'Tal opstilling');
define('JS_LANG_Bullets', 'Punkttegns opstilling');
define('JS_LANG_HorizontalLine', 'Horisontal Linje');
define('JS_LANG_Bold', 'Fed');
define('JS_LANG_Italic', 'Kursiv');
define('JS_LANG_Underline', 'Understreget');
define('JS_LANG_AlignLeft', 'Venstre stillet');
define('JS_LANG_Center', 'Center');
define('JS_LANG_AlignRight', 'Højre stillet');
define('JS_LANG_Justify', 'Lige marginer');
define('JS_LANG_FontColor', 'Skriftfarve');
define('JS_LANG_Background', 'Baggrund');
define('JS_LANG_SwitchToPlainMode', 'Skift til ren tekst tilstand');
define('JS_LANG_SwitchToHTMLMode', 'Skift til HTML tilstand');

define('JS_LANG_Folder', 'Mappe');
define('JS_LANG_Msgs', 'Emails');
define('JS_LANG_Synchronize', 'Synkroniser');
define('JS_LANG_ShowThisFolder', 'Vis denne mappe');
define('JS_LANG_Total', 'Total');
define('JS_LANG_DeleteSelected', 'Slet markerede');
define('JS_LANG_AddNewFolder', 'Tilføj ny mappe');
define('JS_LANG_NewFolder', 'Ny mappe');
define('JS_LANG_ParentFolder', 'Hovedmappe');
define('JS_LANG_NoParent', 'Ingen hovedmappe');
define('JS_LANG_FolderName', 'Mappenavn');

define('JS_LANG_ContactsPerPage', 'Kontakter pr side');
define('JS_LANG_WhiteList', 'Kontakter som White List');

define('JS_LANG_CharsetDefault', 'Standard');
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
define('JS_LANG_DateDDMonth', 'DD Måned (01 Jan)');
define('JS_LANG_DateAdvanced', 'Avanceret');

define('JS_LANG_NewContact', 'Ny kontakt');
define('JS_LANG_NewGroup', 'Ny gruppe');
define('JS_LANG_AddContactsTo', 'Tilføj kontakter til');
define('JS_LANG_ImportContacts', 'Importer kontakter');

define('JS_LANG_Name', 'Navn');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Standard Email');
define('JS_LANG_NotSpecifiedYet', 'Endnu ikke specificeret');
define('JS_LANG_ContactName', 'Navn');
define('JS_LANG_Birthday', 'Fødselsdag');
define('JS_LANG_Month', 'Måned');
define('JS_LANG_January', 'Januar');
define('JS_LANG_February', 'Februar');
define('JS_LANG_March', 'Marts');
define('JS_LANG_April', 'April');
define('JS_LANG_May', 'Maj');
define('JS_LANG_June', 'Juni');
define('JS_LANG_July', 'Juli');
define('JS_LANG_August', 'August');
define('JS_LANG_September', 'September');
define('JS_LANG_October', 'Oktober');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'December');
define('JS_LANG_Day', 'Dag');
define('JS_LANG_Year', 'år');
define('JS_LANG_UseFriendlyName1', 'Brug venligt navn');
define('JS_LANG_UseFriendlyName2', '(Fx, John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Personlig');
define('JS_LANG_PersonalEmail', 'Personlig email');
define('JS_LANG_StreetAddress', 'Vejnavn');
define('JS_LANG_City', 'By');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Stat/Landsdel');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'Postnummer');
define('JS_LANG_Mobile', 'Mobil');
define('JS_LANG_CountryRegion', 'Land/Region');
define('JS_LANG_WebPage', 'Website');
define('JS_LANG_Go', 'Go');
define('JS_LANG_Home', 'Hjem');
define('JS_LANG_Business', 'Firma');
define('JS_LANG_BusinessEmail', 'Firma email');
define('JS_LANG_Company', 'Firma');
define('JS_LANG_JobTitle', 'Job titel');
define('JS_LANG_Department', 'Afdeling');
define('JS_LANG_Office', 'Kontor');
define('JS_LANG_Pager', 'Personsøger');
define('JS_LANG_Other', 'Andet');
define('JS_LANG_OtherEmail', 'Anden email');
define('JS_LANG_Notes', 'Noter');
define('JS_LANG_Groups', 'Grupper');
define('JS_LANG_ShowAddFields', 'Vis ekstra felter');
define('JS_LANG_HideAddFields', 'Skjul ekstra felter');
define('JS_LANG_EditContact', 'Rediger kontakt information');
define('JS_LANG_GroupName', 'Gruppenavn');
define('JS_LANG_AddContacts', 'Tilføj kontakter');
define('JS_LANG_CommentAddContacts', '(Hvis du vil angive mere end en adresse, adskil dem med komma)');
define('JS_LANG_CreateGroup', 'Opret gruppe');
define('JS_LANG_Rename', 'omdøb');
define('JS_LANG_MailGroup', 'Email gruppe');
define('JS_LANG_RemoveFromGroup', 'Fjern fra gruppe');
define('JS_LANG_UseImportTo', 'Brug import for at kopiere kontakter fra Microsoft Outlook, Microsoft Outlook Express ind i din webmail kontaktliste.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Vælg den fil (.CSV format) som du vil importere');
define('JS_LANG_Import', 'Importer');
define('JS_LANG_ContactsMessage', 'Dette er kontaktlisten!');
define('JS_LANG_ContactsCount', 'kontakt(er)');
define('JS_LANG_GroupsCount', 'gruppe(r)');

// webmail 4.1 constants
define('PicturesBlocked', 'Billeder i denne email er blevet blokeret.');
define('ShowPictures', 'Vis billeder');
define('ShowPicturesFromSender', 'Vis altid billeder i emails fra denne afsender');
define('AlwaysShowPictures', 'Vis altid billeder i emails');

define('TreatAsOrganization', 'Behandl som en organisation');

define('WarningGroupAlreadyExist', 'En gruppe med det navn eksisterer allerede. Vælg et andet navn.');
define('WarningCorrectFolderName', 'Angiv korrekt mappenavn.');
define('WarningLoginFieldBlank', 'Brugernavnet skal udfyldes.');
define('WarningCorrectLogin', 'Du skal angive et korrekt login.');
define('WarningPassBlank', 'Adgangskoden skal udfyldes.');
define('WarningCorrectIncServer', 'Du skal angive en korrekt POP3(IMAP) server adresse.');
define('WarningCorrectSMTPServer', 'Du skal angive en korrekt SMTP server adresse.');
define('WarningFromBlank', 'Fra felt skal udfyldes');
define('WarningAdvancedDateFormat', 'Vælg et dato-tids format.');

define('AdvancedDateHelpTitle', 'Avanceret Dato');
define('AdvancedDateHelpIntro', 'Når &quot;Avanceret&quot; feltet er valg, kan du bruge tekst boksen til at angive dit eget dato format, som vil blive vist i Awzum.dk Webmail. Følgende muligheder er brugt i denne sammenhæng sammen med \':\' eller \'/\':');
define('AdvancedDateHelpConclusion', 'Fx. hvis du har specificeret &quot;mm/dd/yyyy&quot; værdien i tekst boksen &quot;Avanceret&quot;, bliver datoen vist som måned/dag/år (Fx. 11/23/2011)');
define('AdvancedDateHelpDayOfMonth', 'Dag i måneden (1 til 31)');
define('AdvancedDateHelpNumericMonth', 'Måned (1 til 12)');
define('AdvancedDateHelpTextualMonth', 'Måned (Jan til Dec)');
define('AdvancedDateHelpYear2', 'år, 2 tal');
define('AdvancedDateHelpYear4', 'år, 4 tal');
define('AdvancedDateHelpDayOfYear', 'Dag i året (1 til 366)');
define('AdvancedDateHelpQuarter', 'Kvartal');
define('AdvancedDateHelpDayOfWeek', 'Dag i ugen (Man til Søn)');
define('AdvancedDateHelpWeekOfYear', 'Uge i året (1 til 53)');

define('InfoNoMessagesFound', 'Ingen emails fundet.');
define('ErrorSMTPConnect', 'Kan ikke oprette forbindelse til SMTP serveren. Tjek SMTP server indstillinger.');
define('ErrorSMTPAuth', 'Forkert brugernavn og/eller adgangskode. Godkendelse mislykkedes.');
define('ReportMessageSent', 'Din email er blevet sendt.');
define('ReportMessageSaved', 'Din email er blevet gemt.');
define('ErrorPOP3Connect', 'Kan ikke oprette forbindelse til POP3 serveren. Tjek POP3 server indstillinger.');
define('ErrorIMAP4Connect', 'Kan ikke oprette forbindelse til IMAP4 serveren. Tjek IMAP4 server indstillinger.');
define('ErrorPOP3IMAP4Auth', 'Forkert email/brugernavn og/eller adgangskode. Godkendelse mislykkedes.');
define('ErrorGetMailLimit', 'Din mailboks kapacitet er opbrugt.');

define('ReportSettingsUpdatedSuccessfuly', 'Indstillinger er blevet opdateret.');
define('ReportAccountCreatedSuccessfuly', 'Konto er blevet oprettet.');
define('ReportAccountUpdatedSuccessfuly', 'Konto er blevet opdateret.');
define('ConfirmDeleteAccount', 'Er du sikker på du vil slette kontoen?');
define('ReportFiltersUpdatedSuccessfuly', 'Filter er blevet opdateret.');
define('ReportSignatureUpdatedSuccessfuly', 'Signatur er blevet opdateret.');
define('ReportFoldersUpdatedSuccessfuly', 'Mapper er blevet opdateret.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Kontakt indstillinger er blevet opdateret.');

define('ErrorInvalidCSV', 'CSV filen du valgte er i forkert format.');
// The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Gruppen');
define('ReportGroupSuccessfulyAdded2', 'blev oprettet.');
define('ReportGroupUpdatedSuccessfuly', 'Gruppen er blevet opdateret.');
define('ReportContactSuccessfulyAdded', 'Kontakten er blevet oprettet.');
define('ReportContactUpdatedSuccessfuly', 'Kontakt er blevet opdateret.');
// Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Kontakt(er) blev tilføjet til gruppen');
define('AlertNoContactsGroupsSelected', 'Ingen kontakter eller grupper valgt.');

define('InfoListNotContainAddress', 'Hvis listen ikke indeholder adressen du leder efter så bliv ved med at indtaste de første bogstaver.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direkte Tilstand. WebMail tilgår emails direkte på mailserveren.');

define('FolderInbox', 'Indbakke');
define('FolderSentItems', 'Sendt post');
define('FolderDrafts', 'Kladder');
define('FolderTrash', 'Papirkurv');

define('FileLargerAttachment', 'Fil størrelsen er for stor til at den kan vedhæftes.');
define('FilePartiallyUploaded', 'Kun en del af filen blev uploadet pga. en ukendt fejl.');
define('NoFileUploaded', 'Ingen filer blev uploadet.');
define('MissingTempFolder', 'Den midlertidige mappe mangler.');
define('MissingTempFile', 'Den midlertidige fil mangler.');
define('UnknownUploadError', 'En ukendt fejl opstod under upload.');
define('FileLargerThan', 'En ukendt fejl opstod under fil upload. Filene er sikkert større end ');
define('PROC_CANT_LOAD_DB', 'Kan ikke forbinde til databasen.');
define('PROC_CANT_LOAD_LANG', 'Kan ikke finde den påkrævede sprog fil.');
define('PROC_CANT_LOAD_ACCT', 'Kontoen eksisterer ikke, måske er den blevet slettet.');

define('DomainDosntExist', 'Det valgte domænenavn eksisterer ikke på serveren.');
define('ServerIsDisable', 'Brug af mailserveren er blevet deaktiveret af administrator.');

define('PROC_ACCOUNT_EXISTS', 'Kontoen kan ikke oprettes da den allerede eksisterer.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Kan ikke fange email antal.');
define('PROC_CANT_MAIL_SIZE', 'Kan ikke fange mail data størrelse.');

define('Organization', 'Organisation');
define('WarningOutServerBlank', 'Du kan ikke lade SMTP server feltet være blank');

define('JS_LANG_Refresh', 'Opdater');
define('JS_LANG_MessagesInInbox', 'Email(s) i Indbakken');
define('JS_LANG_InfoEmptyInbox', 'Indbakken er tom');

// webmail 4.2 constants
define('BackToList', 'Tilbage til listen');
define('InfoNoContactsGroups', 'Ingen kontakter eller grupper.');
define('InfoNewContactsGroups', 'Du kan enten oprette nye kontakter/grupper eller importere kontakter fra en .CSV fil i MS Outlook format.');
define('DefTimeFormat', 'Standard tids format');
define('SpellNoSuggestions', 'Ingen forslag');
define('SpellWait', 'Vent venligst&hellip;');

define('InfoNoMessageSelected', 'Ingen email valgt.');
define('InfoSingleDoubleClick', 'Du kan enten klikke på en e-mail i listen for at se den, eller dobbeltklikke på den for at se den i fuld størrelse.');

// calendar
define('TitleDay', 'Dags visning');
define('TitleWeek', 'Uge visning');
define('TitleMonth', 'Måneds visning');

define('ErrorNotSupportBrowser', 'Awzum.dk kalender understøtter ikke din browser. Benyt Firefox 2.0 eller nyere, Opera 9.0 eller nyere, Internet Explorer 6.0 eller nyere, Safari 3.0.2 eller nyere.');
define('ErrorTurnedOffActiveX', 'ActiveX support er slået fra . <br/>Du skal slå det til for at benytte dette program.');

define('Calendar', 'Kalender');

define('TabDay', 'Dag');
define('TabWeek', 'Uge');
define('TabMonth', 'Måned');

define('ToolNewEvent', 'Ny&nbsp;begivenhed');
define('ToolBack', 'Tilbage');
define('ToolToday', 'I dag');
define('AltNewEvent', 'Ny begivenhed');
define('AltBack', 'Tilbage');
define('AltToday', 'I dag');
define('CalendarHeader', 'Kalender');
define('CalendarsManager', 'Kalender Manager');

define('CalendarActionNew', 'Ny kalender');
define('EventHeaderNew', 'Ny begivenhed');
define('CalendarHeaderNew', 'Ny Kalender');

define('EventSubject', 'Emne');
define('EventCalendar', 'Kalender');
define('EventFrom', 'Fra');
define('EventTill', 'til');
define('CalendarDescription', 'Beskrivelse');
define('CalendarColor', 'Farve');
define('CalendarName', 'Kalender navn');
define('CalendarDefaultName', 'Min kalender');

define('ButtonSave', 'Gem');
define('ButtonCancel', 'Annuller');
define('ButtonDelete', 'Slet');

define('AltPrevMonth', 'Forrige måned');
define('AltNextMonth', 'Næste måned');

define('CalendarHeaderEdit', 'Rediger kalender');
define('CalendarActionEdit', 'Rediger kalender');
define('ConfirmDeleteCalendar', 'Er du sikker på du vil slette kalender');
define('InfoDeleting', 'Sletter&hellip;');
define('WarningCalendarNameBlank', 'Kalender navnet skal udfyldes.');
define('ErrorCalendarNotCreated', 'Kalender ikke oprettet.');
define('WarningSubjectBlank', 'Emne skal udfyldes.');
define('WarningIncorrectTime', 'Den angivne tid indeholder ugyldige tegn.');
define('WarningIncorrectFromTime', 'Fra tidspunktet er ikke korrekt.');
define('WarningIncorrectTillTime', 'Til tidspunktet er ikke korrekt.');
define('WarningStartEndDate', 'Slut datoen skal være større end start datoen.');
define('WarningStartEndTime', 'Slut tidspunktet skal være større end start tidspunktet.');
define('WarningIncorrectDate', 'Datoen skal være korrekt.');
define('InfoLoading', 'Arbejder&hellip;');
define('EventCreate', 'Opret begivenhed');
define('CalendarHideOther', 'Skjul andre kalendere');
define('CalendarShowOther', 'Vis andre kalendere');
define('CalendarRemove', 'Fjern kalender');
define('EventHeaderEdit', 'Rediger begivenhed');

define('InfoSaving', 'Gemmer&hellip;');
define('SettingsDisplayName', 'Vist navn');
define('SettingsTimeFormat', 'Tids format');
define('SettingsDateFormat', 'Dato format');
define('SettingsShowWeekends', 'Vis weekender');
define('SettingsWorkdayStarts', 'Arbejdsdag starter');
define('SettingsWorkdayEnds', 'slutter');
define('SettingsShowWorkday', 'Vis arbejdsdag');
define('SettingsWeekStartsOn', 'Uge starter');
define('SettingsDefaultTab', 'Standard fane');
define('SettingsCountry', 'Land');
define('SettingsTimeZone', 'Tidszone');
define('SettingsAllTimeZones', 'Alle tidszoner');

define('WarningWorkdayStartsEnds', '\'Arbejdsdagen slutter\' tidspunkt skal være større end \'Arbejdsdag starter\' tidspunkt');
define('ReportSettingsUpdated', 'Indstillingerne er blevet opdateret.');

define('SettingsTabCalendar', 'Kalender');

define('FullMonthJanuary', 'Januar');
define('FullMonthFebruary', 'Februar');
define('FullMonthMarch', 'Marts');
define('FullMonthApril', 'April');
define('FullMonthMay', 'Maj');
define('FullMonthJune', 'Juni');
define('FullMonthJuly', 'Juli');
define('FullMonthAugust', 'August');
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

define('FullDayMonday', 'Mandag');
define('FullDayTuesday', 'Tirsdag');
define('FullDayWednesday', 'Onsdag');
define('FullDayThursday', 'Torsdag');
define('FullDayFriday', 'Fredag');
define('FullDaySaturday', 'Lørdag');
define('FullDaySunday', 'Søndag');

define('DayToolMonday', 'Man');
define('DayToolTuesday', 'Tir');
define('DayToolWednesday', 'Ons');
define('DayToolThursday', 'Tor');
define('DayToolFriday', 'Fre');
define('DayToolSaturday', 'Lør');
define('DayToolSunday', 'Søn');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'T');
define('CalendarTableDayWednesday', 'O');
define('CalendarTableDayThursday', 'T');
define('CalendarTableDayFriday', 'F');
define('CalendarTableDaySaturday', 'L');
define('CalendarTableDaySunday', 'S');

define('ErrorParseJSON', 'JSON svar retunerede fra serveren can ikke blive parsed.');

define('ErrorLoadCalendar', 'Kan ikke hente kalendere');
define('ErrorLoadEvents', 'Kan ikke hente begivenheder');
define('ErrorUpdateEvent', 'Kan ikke gemme begivenhed');
define('ErrorDeleteEvent', 'Kan ikke slette begivenhed');
define('ErrorUpdateCalendar', 'Kan ikke opdatere kalender');
define('ErrorDeleteCalendar', 'Kan ikke slette kalender');
define('ErrorGeneral', 'Der opstod en fejl på serveren. Prøv igen senere.');

// webmail 4.3 constants
define('SharedTitleEmail', 'Email');
define('ShareHeaderEdit', 'Del/udgiv kalender');
define('ShareActionEdit', 'Del og udgiv kalender');
define('CalendarPublicate', 'Offentligør kalender');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Del denne kalender');
define('SharePermission1', 'Kan lave ændringer og redigere deling');
define('SharePermission2', 'Kan ændre begivenheder');
define('SharePermission3', 'Kan se alle detaljer for begivenheder');
define('SharePermission4', 'Kan kun se fri/optaget (skjul detaljer)');
define('ButtonClose', 'Luk');
define('WarningEmailFieldFilling', 'Du skal udfylde email feltet først');
define('EventHeaderView', 'Vis begivenhed');
define('ErrorUpdateSharing', 'Kan ikke gemme deling og udgivelses data');
define('ErrorUpdateSharing1', 'Det er ikke muligt at dele til %s bruger da de ikke eksisterer');
define('ErrorUpdateSharing2', 'Umuligt at dele denne kalender til brugeren %s');
define('ErrorUpdateSharing3', 'Denne kalender er allerede delt med brugeren %s');
define('Title_MyCalendars', 'Mine kalendere');
define('Title_SharedCalendars', 'Delte kalendere');
define('ErrorGetPublicationHash', 'Kan ikke oprette udgivelses link');
define('ErrorGetSharing', 'Kan ikke tilføje deling');
define('CalendarPublishedTitle', 'Denne kalender er udgivet');
define('RefreshSharedCalendars', 'Opdater Delte Kalendere');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Medlemmer');

define('ReportMessagePartDisplayed', 'Bemærk at kun en del af beskeden er vist.');
define('ReportViewEntireMessage', 'For at vise hele beskeden,');
define('ReportClickHere', 'klik her');
define('ErrorContactExists', 'En kontakt med det navn og email eksisterer allerede.');

define('Attachments', 'Vedhæftninger');

define('InfoGroupsOfContact', 'Grupperne som kontakten er medlem af er markeret.');
define('AlertNoContactsSelected', 'Ingen kontakter er valgt.');
define('MailSelected', 'Email valgte adresser');
define('CaptionSubscribed', 'Tilmeldt');

define('OperationSpam', 'Uønsket');
define('OperationNotSpam', 'Ikke uønsket');
define('FolderSpam', 'Uønsket post');

// webmail 4.4 Kontakter
define('ContactMail', 'Kontakter');
define('ContactViewAllMails', 'Vis alle mails mht. denne person');
define('ContactsMailThem', 'Mail dem');
define('DateToday', 'I dag');
define('DateYesterday', 'I går');
define('MessageShowDetails', 'Vis detaljer');
define('MessageHideDetails', 'Skjul detaljer');

define('MessageNoSubject', 'Intet emne');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'Til');
define('SearchClear', 'Rens søg');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Søg efter "#s" i mappen #f:');
define('SearchResultsInAllFolders', 'Søg efter "#s" i alle mapper:');
define('AutoresponderTitle', 'Autosvarer');
define('AutoresponderEnable', 'Slå autosvarer til');
define('AutoresponderSubject', 'Emne');
define('AutoresponderMessage', 'Besked');
define('ReportAutoresponderUpdatedSuccessfuly', 'Autosvarer blev korrekt opdateret.');
define('FolderQuarantine', 'Karantæne');

// calendar
define('EventRepeats', 'Gentag');
define('NoRepeats', 'Ingen gentagelse');
define('DailyRepeats', 'Dagligt');
define('WorkdayRepeats', 'Hver uge (man. - fre.)');
define('OddDayRepeats', 'Hver mandag, onsdag og fredag.');
define('EvenDayRepeats', 'Hver tirsdag og torsdag.');
define('WeeklyRepeats', 'Ugentligt');
define('MonthlyRepeats', 'Månedligt');
define('YearlyRepeats', 'årligt');
define('RepeatsEvery', 'Gentag hver');
define('ThisInstance', 'Kun denne gang');
define('AllEvents', 'Alle begivenheder i denne serie');
define('AllFollowing', 'Alle følgende');
define('ConfirmEditRepeatEvent', 'Vil du ændre denne begivenhed, alle begivenheder, eller denne samt alle fremtidige begivenheder i serien?');
define('RepeatEventHeaderEdit', 'ændre gentagende begivenhed');
define('First', 'Første');
define('Second', 'Anden');
define('Third', 'Tredje');
define('Fourth', 'Fjerde');
define('Last', 'Sidste');
define('Every', 'Hver');
define('SetRepeatEventEnd', 'Sæt slut dato');
define('NoEndRepeatEvent', 'Ingen slut dato');
define('EndRepeatEventAfter', 'Slut efter');
define('Occurrences', 'forekomster');
define('EndRepeatEventBy', 'Slut med');
define('EventCommonDataTab', 'Grunddetaljer');
define('EventRepeatDataTab', 'Gengående detaljer');
define('RepeatEventNotPartOfASeries', 'Denne begivenhed er blevet ændret og er ikke mere en del af en serie.');
define('UndoRepeatExclusion', 'Fortryd ændringer til at inkludere denne serie.');

define('MonthMoreLink', '%d flere...');
define('NoNewSharedCalendars', 'Ingen nye kalendere');
define('NNewSharedCalendars', '%d nye kalendere fundet');
define('OneNewSharedCalendars', '1 ny kalender fundet');
define('ConfirmUndoOneRepeat', 'ønsker du at genskabe denne begivenhed i serien?');

define('RepeatEveryDayInfin', 'Hver dag');
define('RepeatEveryDayTimes', 'Hver dag, %TIMES% gange');
define('RepeatEveryDayUntil', 'Hver dag, indtil %UNTIL%');
define('RepeatDaysInfin', 'Hver %PERIOD% dag');
define('RepeatDaysTimes', 'Hver %PERIOD% dag(e), %TIMES% gange');
define('RepeatDaysUntil', 'Hver %PERIOD% dage, indtil %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Hver uge på hverdage');
define('RepeatEveryWeekWeekdaysTimes', 'Hver uge på hverdage, %TIMES% gange');
define('RepeatEveryWeekWeekdaysUntil', 'Hver uge på hverdage, indtil %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Hver %PERIOD% uge på hverdage');
define('RepeatWeeksWeekdaysTimes', 'Hver %PERIOD% uge på hverdage, %TIMES% gange');
define('RepeatWeeksWeekdaysUntil', 'Hver %PERIOD% uge på hverdage, indtil %UNTIL%');

define('RepeatEveryWeekInfin', 'Hver dag om %DAYS%');
define('RepeatEveryWeekTimes', 'Hver dag om %DAYS%, %TIMES% gange');
define('RepeatEveryWeekUntil', 'Hver dag om %DAYS%, indtil %UNTIL%');
define('RepeatWeeksInfin', 'Hver %PERIOD% uge om %DAYS%en');
define('RepeatWeeksTimes', 'Hver %PERIOD% uge den %DAYS%, %TIMES% gange');
define('RepeatWeeksUntil', 'Hver %PERIOD% uge den %DAYS%, indtil %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Hver måned den %DATE%');
define('RepeatEveryMonthDateTimes', 'Hver måned den %DATE%, %TIMES% gange');
define('RepeatEveryMonthDateUntil', 'Hver måned den %DATE%, indtil %UNTIL%');
define('RepeatMonthsDateInfin', 'Hver %PERIOD% måned den %DATE%');
define('RepeatMonthsDateTimes', 'Hver %PERIOD% måned den %DATE%, %TIMES% gange');
define('RepeatMonthsDateUntil', 'Hver %PERIOD% måned den %DATE%, indtil %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Hver måned den %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Hver måned den %NUMBER% %DAY%, %TIMES% gange');
define('RepeatEveryMonthWDUntil', 'Hver måned den %NUMBER% %DAY%, indtil %UNTIL%');
define('RepeatMonthsWDInfin', 'Hver %PERIOD% måned den %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Hver %PERIOD% måned den %NUMBER% %DAY%, %TIMES% gange');
define('RepeatMonthsWDUntil', 'Hver %PERIOD% måned den %NUMBER% %DAY%, indtil %UNTIL%');

define('RepeatEveryYearDateInfin', 'Hvert år den %DATE%');
define('RepeatEveryYearDateTimes', 'Hvert år den %DATE%, %TIMES% gange');
define('RepeatEveryYearDateUntil', 'Hvert år den %DATE%, indtil %UNTIL%');
define('RepeatYearsDateInfin', 'Hver %PERIOD% år den %DATE%');
define('RepeatYearsDateTimes', 'Hver %PERIOD% år den %DATE%, %TIMES% gange');
define('RepeatYearsDateUntil', 'Hver %PERIOD% år den %DATE%, indtil %UNTIL%');

define('RepeatEveryYearWDInfin', 'Hvert år den %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Hvert år den %NUMBER% %DAY%, %TIMES% gange');
define('RepeatEveryYearWDUntil', 'Hvert år den %NUMBER% %DAY%, indtil %UNTIL%');
define('RepeatYearsWDInfin', 'Hver %PERIOD% år den %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Hver %PERIOD% år den %NUMBER% %DAY%, %TIMES% gange');
define('RepeatYearsWDUntil', 'Hver %PERIOD% år den %NUMBER% %DAY%, indtil %UNTIL%');

define('RepeatDescDay', 'dag');
define('RepeatDescWeek', 'uge');
define('RepeatDescMonth', 'måned');
define('RepeatDescYear', 'år');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Angiv venligst gentagelsens slut dato');
define('WarningWrongUntilDate', 'Gentagelsens slut dato skal være senere end start gentagelses dato');

define('OnDays', 'På dage');
define('CancelRecurrence', 'Annuller gentagelse');
define('RepeatEvent', 'Gentag denne begivenhed');

define('Spellcheck', 'Stavekontrol');
define('LoginLanguage', 'Sprog');
define('LanguageDefault', 'Standard');

// webmail 4.5.x new
define('EmptySpam', 'Tøm Uønsket post');
define('Saving', 'Gemmer&hellip;');
define('Sending', 'Sender&hellip;');
define('LoggingOffFromServer', 'Logger på serveren&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Kan ikke markere meddelelsen/erne som uønsket');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Kan ikke markere meddelelsen/erne som uønsket');
define('ExportToICalendar', 'Eksporter til iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Din konto er blevet deaktiveret fordi det maksimale antal brugere tiladt af licensen er overskredet. Kontakt venligst din systemadministrator.');

define('RepliedMessageTitle', 'Besvarede meddelelse');
define('ForwardedMessageTitle', 'Videresendte meddelelse');
define('RepliedForwardedMessageTitle', 'Besvarede og videresendte meddelselse');
define('ErrorDomainExist', 'Brugeren kan ikke oprettets, fordi det angivne domæne ikke eksisterer. Du skal oprette domænet først.');

// webmail 4.7
define('RequestReadConfirmation', 'Anmodning om læse bekræftelse');
define('FolderTypeDefault', 'Standard');
define('ShowFoldersMapping', 'Lad mig bruge en anden mappe som systemmappe (fx brug MinMappe istedet for Sendt post)');
define('ShowFoldersMappingNote', 'For eksempel, for at skifte Sendt post lokationen til MinMappe, vælg "Sendt post" i "Brug som" dropdown-boksen ved "MinMappe".');
define('FolderTypeMapTo', 'Brug som');

define('ReminderEmailExplanation', 'Denne meddelelse er sendt til din konto %EMAIL% fordi du har valgt begivenhed notifikation i din kalender: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'åben kalender');

define('AddReminder', 'Påmind mig om denne begivenhed');
define('AddReminderBefore', 'Påmind mig % før denne begivenhed');
define('AddReminderAnd', 'og % før');
define('AddReminderAlso', 'og også % før');
define('AddMoreReminder', 'Flere påmindelser');
define('RemoveAllReminders', 'Fjern alle påmindelser');
define('ReminderNone', 'Ingen');
define('ReminderMinutes', 'minuter');
define('ReminderHour', 'time');
define('ReminderHours', 'timer');
define('ReminderDay', 'dag');
define('ReminderDays', 'dage');
define('ReminderWeek', 'uge');
define('ReminderWeeks', 'uger');
define('Allday', 'Hele dagen');

define('Folders', 'Mapper');
define('NoSubject', 'Intet emne');
define('SearchResultsFor', 'Søgeresultater for');

define('Back', 'Tilbage');
define('Next', 'Næste');
define('Prev', 'Forrige');

define('MsgList', 'Beskeder');
define('Use24HTimeFormat', 'Brug 24 timers format');
define('UseCalendars', 'Brug kalendere');
define('Event', 'Begivenhed');
define('CalendarSettingsNullLine', 'Ingen kalendere');
define('CalendarEventNullLine', 'Ingen begivenheder');
define('ChangeAccount', 'ændre konto');

define('TitleCalendar', 'Kalender');
define('TitleEvent', 'Begivenhed');
define('TitleFolders', 'Mapper');
define('TitleConfirmation', 'Bekræftelse');

define('Yes', 'Ja');
define('No', 'Nej');

define('EditMessage', 'Rediger besked');

define('AccountNewPassword', 'Nyt kodeord');
define('AccountConfirmNewPassword', 'Bekræft nyt kodeord');
define('AccountPasswordsDoNotMatch', 'Kodeord er ikke ens');

define('ContactTitle', 'Titel');
define('ContactFirstName', 'Fornavn');
define('ContactSurName', 'Efternavn');
define('ContactNickName', 'Kælenavn');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'genindlæs');
define('CaptchaError', 'Captcha teksten er ikke korrekt.');

define('WarningInputCorrectEmails', 'Angiv korrekt email.');
define('WrongEmails', 'Forkerte emails:');

define('ConfirmBodySize1', 'Beklager, men tekst beskeder er max.');
define('ConfirmBodySize2', 'tegn langt. Alt over denne grænse bliver skåret af. Klik "annuller" hvis du vil redigere besked.');
define('BodySizeCounter', 'Tæller');
define('InsertImage', 'Indsæt billede');
define('ImagePath', 'Billede sti');
define('ImageUpload', 'Indsæt');
define('WarningImageUpload', 'Filen du vil vedhæfte er ikke et billede. Vælg en billede fil.');

define('ConfirmExitFromNewMessage', 'ændringer vil gå tabt hvis du forlader siden. Du skal gemme en kladde før du forlader siden. Klik annuller for at afbryde og gemme en Kladde.');

define('SensivityConfidential', 'Behandl denne besked som fortrolig');
define('SensivityPrivate', 'Behandl denne besked som privat');
define('SensivityPersonal', 'Behandl denne besked som personlig');

define('ReturnReceiptTopText', 'Afsenderen af denne besked har bedt om at blive underrettet når du modtager denne besked.');
define('ReturnReceiptTopLink', 'Klik her for at underrette afsender.');
define('ReturnReceiptSubject', 'Kvittering (vist)');
define('ReturnReceiptMailText1', 'Dette er en kvittering for den email du sendte til');
define('ReturnReceiptMailText2', 'Note: Kvitteringen er kun et bevis for at beskeden blev vist på modtageres computer. Der er ingen garanti for at modtageren har læst eller forstået beskeden.');
define('ReturnReceiptMailText3', 'med emnet');

define('SensivityMenu', 'Vigtighed');
define('SensivityNothingMenu', 'Ingen');
define('SensivityConfidentialMenu', 'Fortrolig');
define('SensivityPrivateMenu', 'Privat');
define('SensivityPersonalMenu', 'Personlig');

define('ErrorLDAPonnect', 'Kan ikke forbinde til ldap server.');

define('MessageSizeExceedsAccountQuota', 'Denne besked størrelse overskrider din konto kvote.');
define('MessageCannotSent', 'Denne besked kan ikke sendes.');
define('MessageCannotSaved', 'Denne besked kan ikke gemmes.');

define('ContactFieldTitle', 'Felt');
define('ContactDropDownTO', 'TIL');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Beskeden/erne kan ikke flyttes til papirkurven. Typisk er det fordi denne er fyldt. Skal denne besked slettes?');

define('WarningFieldBlank', 'Dette felt skal udfyldes.');
define('WarningPassNotMatch', 'Kodeord er ikke ens.');
define('PasswordResetTitle', 'Kodeord genskabelse - trin %d');
define('NullUserNameonReset', 'bruger');
define('IndexResetLink', 'Glemt kodeord?');
define('IndexRegLink', 'Konto oprettelse');

define('RegDomainNotExist', 'Domæne eksisterer ikke.');
define('RegAnswersIncorrect', 'Svar er ikke korrekt.');
define('RegUnknownAdress', 'Ukendt email adresse.');
define('RegUnrecoverableAccount', 'Kodeord genskabelse kan ikke aktiveres for denne email adresse.');
define('RegAccountExist', 'Denne adresse eksisterer allerede.');
define('RegRegistrationTitle', 'Registrering');
define('RegName', 'Navn');
define('RegEmail', 'email adresse');
define('RegEmailDesc', 'Fx., mitnavn@domæne.dk. Denne information bliver brugt til at logge ind i systemet.');
define('RegSignMe', 'Husk mig');
define('RegSignMeDesc', 'Spørg ikke efter login og kodeord ved næste login fra denne PC.');
define('RegPass1', 'Kodeord');
define('RegPass2', 'Gentag kodeord ');
define('RegQuestionDesc', 'Angiv to hemmelige spørgsmål og svar som kun du kender. Hvis du glemmer dit kodeord kan du bruge disse spørgsmål og svar for at genskabe dit kodroed.');
define('RegQuestion1', 'Hemmelig spørgsmål 1');
define('RegAnswer1', 'Svar 1');
define('RegQuestion2', 'Hemmelig spørgsmål 2');
define('RegAnswer2', 'Svar 2');
define('RegTimeZone', 'Tidszone');
define('RegLang', 'System sprog');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Registrer');

define('ResetEmail', 'Angiv din email adresse');
define('ResetEmailDesc', 'Angiv email adresse som du brugte ved registreringen.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Send');
define('ResetQuestion1', 'Hemmelig spørgsmål 1');
define('ResetAnswer1', 'Svar');
define('ResetQuestion2', 'Hemmelig spørgsmål 2');
define('ResetAnswer2', 'Svar');
define('ResetSubmitStep2', 'Send');

define('ResetTopDesc1Step2', 'Angiv email adresse');
define('ResetTopDesc2Step2', 'Verificer email gyldighed.');

define('ResetTopDescStep3', 'angiv herunder nyt kodeord for din email.');

define('ResetPass1', 'Nyt kodeord');
define('ResetPass2', 'Gentag kodeord');
define('ResetSubmitStep3', 'Send');
define('ResetDescStep4', 'Dit kodeord er blevet ændret.');
define('ResetSubmitStep4', 'Tilbage');

define('RegReturnLink', 'Gå tilbage til login siden');
define('ResetReturnLink', 'Gå tilbage til login siden');

// Appointments
define('AppointmentAddGuests', 'Tilføj gæster');
define('AppointmentRemoveGuests', 'Annuller møde');
define('AppointmentListEmails', 'Angiv email adresser adskilt af komma og klik på gem');
define('AppointmentParticipants', 'Deltagere');
define('AppointmentRefused', 'Afvis');
define('AppointmentAwaitingResponse', 'Venter på svar');
define('AppointmentInvalidGuestEmail', 'Følgende gæste email adresse er ugyldige:');
define('AppointmentOwner', 'Ejer');

define('AppointmentMsgTitleInvite', 'Inviter til begivenhed.');
define('AppointmentMsgTitleUpdate', 'Begivenhed blev opdateret.');
define('AppointmentMsgTitleCancel', 'Begivenhed blev annulleret.');
define('AppointmentMsgTitleRefuse', 'Gæst %guest% har afvist invitationen');
define('AppointmentMoreInfo', 'Mere info');
define('AppointmentOrganizer', 'Organisere');
define('AppointmentEventInformation', 'Begivenhed information');
define('AppointmentEventWhen', 'Hvornår');
define('AppointmentEventParticipants', 'Deltagere');
define('AppointmentEventDescription', 'Beskrivelse');
define('AppointmentEventWillYou', 'Vil du deltage');
define('AppointmentAdditionalParameters', 'Ekstra parametre');
define('AppointmentHaventRespond', 'Endnu ikke besvaret');
define('AppointmentRespondYes', 'Jeg vil deltage');
define('AppointmentRespondMaybe', 'Ikke sikker endnu');
define('AppointmentRespondNo', 'Vil ikke deltage');
define('AppointmentGuestsChangeEvent', 'Gæster kan ændre begivenheden');

define('AppointmentSubjectAddStart', 'Du har modtaget en invitation til en begivenhed ');
define('AppointmentSubjectAddFrom', 'fra ');
define('AppointmentSubjectUpdateStart', 'Opdater begivenhed ');
define('AppointmentSubjectDeleteStart', 'Annuller begivenhed ');
define('ErrorAppointmentChangeRespond', 'Kan ikke ændre møde besvarelse');
define('SettingsAutoAddInvitation', 'Tilføj invitationer til kalenderen automatisk');
define('ReportEventSaved', 'Din begivenhed er blevet gemt');
define('ReportAppointmentSaved', 'og bekendtgørelser blev afsendt');
define('ErrorAppointmentSend', 'Kan ikke afsende invitationer.');
define('AppointmentEventName', 'Navn:');

// End appointments

define('ErrorCantUpdateFilters', 'Kan ikke opdatere filtre');

define('FilterPhrase', 'Hvis der er %field header %condition %string så %action');
define('FiltersAdd', 'Opret filter');
define('FiltersCondEqualTo', 'ligmed');
define('FiltersCondContainSubstr', 'indeholder understreng');
define('FiltersCondNotContainSubstr', 'indeholder ikke understreng');
define('FiltersActionDelete', 'slet besked');
define('FiltersActionMove', 'flyt');
define('FiltersActionToFolder', 'til %folder mappe');
define('FiltersNo', 'Ingen filtre specificeret');

define('ReminderEmailFriendly', 'påmindelse');
define('ReminderEventBegin', 'starter: ');

define('FiltersLoading', 'Henter filtre...');
define('ConfirmMessagesPermanentlyDeleted', 'Alle beskeder i denne mappe vil blive slettet permanent.');

define('InfoNoNewMessages', 'Der er ikke nogen nye beskeder.');
define('TitleImportContacts', 'Importer kontakter');
define('TitleSelectedContacts', 'Valgte kontakter');
define('TitleNewContact', 'Ny kontakt');
define('TitleViewContact', 'Se kontakt');
define('TitleEditContact', 'ændre kontakt');
define('TitleNewGroup', 'Ny gruppe');
define('TitleViewGroup', 'Se gruppe');

define('AttachmentComplete', 'Udført.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Autotjek interval');
define('AutoCheckMailIntervalDisableName', 'Slået fra');
define('ReportCalendarSaved', 'Kalenderen er blevet gemt.');

define('ContactSyncError', 'Synkronisering fejlede');
define('ReportContactSyncDone', 'Synkronisering fuldført');

define('MobileSyncUrlTitle', 'Mobil synk URL');
define('MobileSyncLoginTitle', 'Mobil synk login');

define('QuickReply', 'Hurtigt svar');
define('SwitchToFullForm', 'Skift til fuld form');
define('SortFieldDate', 'Dato');
define('SortFieldFrom', 'Fra');
define('SortFieldSize', 'Størrelse');
define('SortFieldSubject', 'Emne');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Vedhæftede filer');
define('SortOrderAscending', 'Stigende');
define('SortOrderDescending', 'Faldende');
define('ArrangedBy', 'Arrangeret efter');

define('MessagePaneToRight', 'Vis beskedpanelet til højre for beskedlisten fremfor under beskedlisten.');

define('SettingsTabMobileSync', 'Mobil Synk');

define('MobileSyncContactDataBaseTitle', 'Mobil synk kontakt database');
define('MobileSyncCalendarDataBaseTitle', 'Mobil synk kalender database');
define('MobileSyncTitleText', 'Hvis du gerne vil synkronisere din SyncML-aktiveret håndholdte enhed med WebMail, kan du bruge disse parametre.<br />"Mobil Synk URL" angiver stien til SyncML-datasynkronisering server, "Mobil Synk Log ind" er dit log ind på SyncML Data Synkronisering Server og bruge din egen adgangskode efter anmodning. Der ud over skal nogle enheder også angive databasenavn til kontakt-og kalenderdatabasen.<br />Brug hhv. "Mobil synk kontakt database" og "Mobil synkronisere kalender database".');
define('MobileSyncEnableLabel', 'Slå mobil synk til');

define('SearchInputText', 'søg');

define('AppointmentEmailExplanation','Denne besked er kommet til din konto %EMAIL% fordi du er blevet inviteret til begivenheden af %ORGANAZER%');

define('Searching', 'Søger&hellip;');

define('ButtonSetupSpecialFolders', 'Setup special folders');
define('ButtonSaveChanges', 'Save changes');
define('InfoPreDefinedFolders', 'For pre-defined folders, use these IMAP mailboxes');

define('SaveMailInSentItems', 'Gem også i Sendt post');

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
