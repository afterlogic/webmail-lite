<?php
define('PROC_ERROR_ACCT_CREATE', 'Den skjedde en feil under oppretting av konto');
define('PROC_WRONG_ACCT_PWD', 'Feil passord');
define('PROC_CANT_LOG_NONDEF', 'Kan ikke logge inn på en ikke-standard konto');
define('PROC_CANT_INS_NEW_FILTER', 'Kan ikke lage nytt filter');
define('PROC_FOLDER_EXIST', 'Mappenavn eksisterer');
define('PROC_CANT_CREATE_FLD', 'Kan ikke opprette mappe ');
define('PROC_CANT_INS_NEW_GROUP', 'Kan ikke lage ny gruppe');
define('PROC_CANT_INS_NEW_CONT', 'Kan ikke lage ny kontakt');
define('PROC_CANT_INS_NEW_CONTS', 'Kan ikke lage ny(e) kontakt(er)');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Kan ikke legge inn kontakt(ene) i gruppe');
define('PROC_ERROR_ACCT_UPDATE', 'Det skjedde en feil under oppdatering av kontakt ');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Kan ikke oppdatere kontaktinnstillinger');
define('PROC_CANT_GET_SETTINGS', 'Kan ikke hente inn innstillinger');
define('PROC_CANT_UPDATE_ACCT', 'Kan ikke oppdatere konto');
define('PROC_ERROR_DEL_FLD', 'Det skjedde en feil under sletting av mappe(r)');
define('PROC_CANT_UPDATE_CONT', 'Kan ikke oppdatere kontakt');
define('PROC_CANT_GET_FLDS', 'Kan ikke hente mappestruktur');
define('PROC_CANT_GET_MSG_LIST', 'Kan ikke hente meldingsliste');
define('PROC_MSG_HAS_DELETED', 'Denne meldingen har alt blitt slettet fra eposttjeneren');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Kan ikke laste inn kontaktinnstillinger ');
define('PROC_CANT_LOAD_SIGNATURE', 'Kan ikke laste epostsignatur');
define('PROC_CANT_GET_CONT_FROM_DB', 'Kan ikke hente kontakt fra DB');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Kan ikke hente kontakt(er) fra DB');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Kan ikke slette konto med id');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Kan ikke slette filter med id');
define('PROC_CANT_DEL_CONT_GROUPS', 'Kan ikke sletter kontakt(er) og/eller grupp(er)');
define('PROC_WRONG_ACCT_ACCESS', 'Et forsøkt på uautorisert tilgang til kontoen av en annen bruker ble oppdaget.');
define('PROC_SESSION_ERROR', 'Den forrige sesjonen ble avsluttet grunnet for lang inaktivitet. ');

define('MailBoxIsFull', 'Epostkontoen er full ');
define('WebMailException', 'WebMail feil oppstod ');
define('InvalidUid', 'Feil meldings-UID');
define('CantCreateContactGroup', 'Kan ikke opprette kontaktgruppe ');
define('CantCreateUser', 'Kan ikke opprette bruker');
define('CantCreateAccount', 'Kan ikke opprette konto');
define('SessionIsEmpty', 'Sesjonen er tom');
define('FileIsTooBig', 'Den valgte filen er for stor');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Kan ikke merke alle meldingene som lest');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Kan ikke merke alle meldingene som ulest');
define('PROC_CANT_PURGE_MSGS', 'Kunne ikke fjerne meldinger');
define('PROC_CANT_DEL_MSGS', 'Kan ikke slette meldinger');
define('PROC_CANT_UNDEL_MSGS', 'Kan ikke gjennopprette meldinger');
define('PROC_CANT_MARK_MSGS_READ', 'Kan ikke sette melding(ene) som lest');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Kan ikke sette melding(ene) som ulest');
define('PROC_CANT_SET_MSG_FLAGS', 'Kan ikke merke melding(ene)');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Kan ikke fjerne merking(er)');
define('PROC_CANT_CHANGE_MSG_FLD', 'Kan ikke endre meldingsmappe');
define('PROC_CANT_SEND_MSG', 'Kan ikke sende melding(ene)');
define('PROC_CANT_SAVE_MSG', 'Kan ikke lagre melding(ene)');
define('PROC_CANT_GET_ACCT_LIST', 'Kan ikke hente konto liste');
define('PROC_CANT_GET_FILTER_LIST', 'Kan ikke hente filter liste');

define('PROC_CANT_LEAVE_BLANK', 'Kan ikke la felt merket med * stå tomme ');

define('PROC_CANT_UPD_FLD', 'Kan ikke oppdatere mappe');
define('PROC_CANT_UPD_FILTER', 'Kan ikke oppdatere filter');

define('ACCT_CANT_ADD_DEF_ACCT', 'Denne kontoen kan ikke bli lagt til. Dette fordi den blir brukt som standard konto av en annen bruker. ');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Denne kontostatusen kan ikke bli endret til standard. ');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Kan ikke opprette ny konto. (IMAP4 tilkobling feilet) ');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Kan ikke slette siste standardkonto');

define('LANG_LoginInfo', 'Logg inn informasjon');
define('LANG_Email', 'Epost');
define('LANG_Login', 'Logg inn');
define('LANG_Password', 'Passord');
define('LANG_IncServer', 'Innkommende&nbsp;Epost');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'SMTP&nbsp;Server');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Benytt&nbsp;SMTP&nbsp;godkjenning');
define('LANG_SignMe', 'Logg meg inn automatisk');
define('LANG_Enter', 'Logg inn');

// interface strings

define('JS_LANG_TitleLogin', 'Logg inn');
define('JS_LANG_TitleMessagesListView', 'Epostliste');
define('JS_LANG_TitleMessagesList', 'Epostliste');
define('JS_LANG_TitleViewMessage', 'Vis epost');
define('JS_LANG_TitleNewMessage', 'Ny Epost');
define('JS_LANG_TitleSettings', 'Innstillinger');
define('JS_LANG_TitleContacts', 'Kontakter');

define('JS_LANG_StandardLogin', 'Standard&nbsp;Logg Inn');
define('JS_LANG_AdvancedLogin', 'Avansert&nbsp;Logg Inn');

define('JS_LANG_InfoWebMailLoading', 'Vennligst vent mens WebMail laster&hellip;');
define('JS_LANG_Loading', 'Laster&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Vennligst vent mens WebMail laster epostliste ');
define('JS_LANG_InfoEmptyFolder', 'Mappen er tom');
define('JS_LANG_InfoPageLoading', 'Siden laster fortsatt&hellip;');
define('JS_LANG_InfoSendMessage', 'Beskjeden ble ikke sendt');
define('JS_LANG_InfoSaveMessage', 'Beskjeden ble lagret');
define('JS_LANG_InfoHaveImported', 'Du har importert');
define('JS_LANG_InfoNewContacts', 'ny(e) kontakt(er) inn til din kontakt liste.');
define('JS_LANG_InfoToDelete', 'Til sletting');
define('JS_LANG_InfoDeleteContent', 'mappe innhold burde slettes først.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Slette mapper med innhold er ikke tillatt. For og slette ikke merkbare mapper, slett innholdet i mappen først. ');
define('JS_LANG_InfoRequiredFields', '* påkrevde felter');

define('JS_LANG_ConfirmAreYouSure', 'Er du sikker?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'De(n) valgte melding(en) vil bli PERMANENT slettet! Er du sikker? ');
define('JS_LANG_ConfirmSaveSettings', 'Innstillingene ble ikke lagret. Klikk OK for lagring. ');
define('JS_LANG_ConfirmSaveContactsSettings', 'Kontakt innstillingene ble ikke lagret. Klikk OK for lagring.');
define('JS_LANG_ConfirmSaveAcctProp', 'Konto egenskapene ble ikke lagret. Klikk OK for lagring.');
define('JS_LANG_ConfirmSaveFilter', 'Filterets egenskaper ble ikke lagret. Klikk OK for lagring.');
define('JS_LANG_ConfirmSaveSignature', 'Signaturen ble ikke lagret. Klikk OK for lagring.');
define('JS_LANG_ConfirmSavefolders', 'Mappen ble ikke lagret. Klikk OK for lagring.');
define('JS_LANG_ConfirmHtmlToPlain', 'ADVARSEL: Å endre formateringen av denne meldingen fra HTML til ren tekst, vil gjøre at du mister nåværende formatering på eposten. Velg OK for å fortsette.');
define('JS_LANG_ConfirmAddFolder', 'Før du legger til mappe(r) er det nødvendig å godkjenne endringer. Velg OK for å lagre.');
define('JS_LANG_ConfirmEmptySubject', 'Emne ble ikke skrevet. Ønsker du å fortsette?');

define('JS_LANG_WarningEmailBlank', 'Du kan ikke la<br />Epost: være tomt');
define('JS_LANG_WarningLoginBlank', 'Du kan ikke la <br />Login: være tomt');
define('JS_LANG_WarningToBlank', 'Du kan ikke la To: være tomt');
define('JS_LANG_WarningServerPortBlank', 'Du kan ikke la POP3 og<br />SMTP server/port feltene være tomme');
define('JS_LANG_WarningEmptySearchLine', 'Tom søkelinje. Skriv inn det/de ord(ene) du ønsker å finne ');
define('JS_LANG_WarningMarkListItem', 'Vær vennlig og mer minst ett element i listen '); //FIXME
define('JS_LANG_WarningFolderMove', 'Mappene kan ikke bli flyttet, da dette er et annet nivå ');
define('JS_LANG_WarningContactNotComplete', 'Vær vennlig og skriv inn epostadresse eller navn ');
define('JS_LANG_WarningGroupNotComplete', 'Vær vennlig og skriv inn gruppenavn ');

define('JS_LANG_WarningEmailFieldBlank', 'Du kan ikke la Epost-feltet være blankt');
define('JS_LANG_WarningIncServerBlank', 'Du kan ikke la POP3(IMAP4) Server feltet være blankt');
define('JS_LANG_WarningIncPortBlank', 'Du kan ikke la POP3(IMAP4) Server Port feltet være blankt');
define('JS_LANG_WarningIncLoginBlank', 'Du kan ikke la POP3(IMAP4) Logg inn feltet være blankt ');
define('JS_LANG_WarningIncPortNumber', 'Du burde spesifisere et positivt nummer i POP3(IMAP4) port feltet. ');
define('JS_LANG_DefaultIncPortNumber', 'Standard POP3(IMAP4) port nummer er 110(143). ');
define('JS_LANG_WarningIncPassBlank', 'Du kan ikke la POP3(IMAP4) Passord feltet være blankt ');
define('JS_LANG_WarningOutPortBlank', 'Du kan ikke la SMTP Server Port feltet være blankt ');
define('JS_LANG_WarningOutPortNumber', 'Du burde spesifisere et positivt nummer i SMTP port feltet.');
define('JS_LANG_WarningCorrectEmail', 'Du burde spesifisere en korrekt epostadresse.');
define('JS_LANG_DefaultOutPortNumber', 'Standard SMTP port nummer er 25. ');

define('JS_LANG_WarningCsvExtention', 'Utvidelsen skal være .csv');
define('JS_LANG_WarningImportFileType', 'Vennligst velg den applikasjonen som du ønsker å kopiere dine kontakter fra ');
define('JS_LANG_WarningEmptyImportFile', 'Vær vennlig og velg fil ved å klikke bla gjennom knappen ');

define('JS_LANG_WarningContactsPerPage', 'Kontakter per side verdien er ett prositivt nummer ');
define('JS_LANG_WarningMessagesPerPage', 'Meldinger per side verdien er ett positivt nummer');
define('JS_LANG_WarningMailsOnServerDays', 'Du burde spesifisere et positivt nummer i meldinger på server i dager feltet.');
define('JS_LANG_WarningEmptyFilter', 'Vær vennlig og legg inn under verdi');
define('JS_LANG_WarningEmptyFolderName', 'Vær vennlig og skriv inn mappenavn');

define('JS_LANG_ErrorConnectionFailed', 'Tilkopling mislyktes');
define('JS_LANG_ErrorRequestFailed', 'Overføringen av data ble ikke fullført');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Objektet XMLHttpRequest er fraværende');
define('JS_LANG_ErrorWithoutDesc', 'En feil uten beskrivelse fant sted');
define('JS_LANG_ErrorParsing', 'Feil under parsing av XML.');
define('JS_LANG_ResponseText', 'Svar tekst:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Tom XML pakke');
define('JS_LANG_ErrorImportContacts', 'Feil under importering av kontakter');
define('JS_LANG_ErrorNoContacts', 'Ingen kontakter til importering');
define('JS_LANG_ErrorCheckMail', 'Mottak av meldinger ble avbrutt grunnet en feil. Trolig ble ikke alle dine meldinger mottatt.');

define('JS_LANG_LoggingToServer', 'Logger inn på server&hellip;');
define('JS_LANG_GettingMsgsNum', 'Mottar antall meldinger');
define('JS_LANG_RetrievingMessage', 'Mottar melding');
define('JS_LANG_DeletingMessage', 'Sletter melding');
define('JS_LANG_DeletingMessages', 'Sletter melding(er)');
define('JS_LANG_Of', 'av');
define('JS_LANG_Connection', 'Tilkobling');
define('JS_LANG_Charset', 'Tegnsett');
define('JS_LANG_AutoSelect', 'Auto valg');

define('JS_LANG_Contacts', 'Kontakter');
define('JS_LANG_ClassicVersion', 'Klassisk Versjon');
define('JS_LANG_Logout', 'Logg Ut');
define('JS_LANG_Settings', 'Innstillinger');

define('JS_LANG_LookFor', 'Let etter: ');
define('JS_LANG_SearchIn', 'Søk i: ');
define('JS_LANG_QuickSearch', 'Søk kun i fra, til og emne feltene (raskere søk).');
define('JS_LANG_SlowSearch', 'Søk i hele melding(ene)');
define('JS_LANG_AllMailFolders', 'Alle epost mapper');
define('JS_LANG_AllGroups', 'Alle Grupper');

define('JS_LANG_NewMessage', 'Ny epost');
define('JS_LANG_CheckMail', 'Sjekk epost');
define('JS_LANG_EmptyTrash', 'Tøm søppelbøtte');
define('JS_LANG_MarkAsRead', 'Marker som lest');
define('JS_LANG_MarkAsUnread', 'Marker som ulest');
define('JS_LANG_MarkFlag', 'Merk');
define('JS_LANG_MarkUnflag', 'Fjern Merking');
define('JS_LANG_MarkAllRead', 'Marker alle som lest');
define('JS_LANG_MarkAllUnread', 'Marker alle som ulest');
define('JS_LANG_Reply', 'Svar');
define('JS_LANG_ReplyAll', 'Svar til alle');
define('JS_LANG_Delete', 'Slett');
define('JS_LANG_Undelete', 'Gjennopprett');
define('JS_LANG_PurgeDeleted', 'Fjern slettede');
define('JS_LANG_MoveToFolder', 'Flytt til mappe');
define('JS_LANG_Forward', 'Videresend');

define('JS_LANG_HideFolders', 'Skjul Mapper');
define('JS_LANG_ShowFolders', 'Vis mapper');
define('JS_LANG_ManageFolders', 'Administrer Mapper');
define('JS_LANG_SyncFolder', 'Synkronisert mappe');
define('JS_LANG_NewMessages', 'Ny(e) melding(er)');
define('JS_LANG_Messages', 'Beskjed(er)');

define('JS_LANG_From', 'Fra');
define('JS_LANG_To', 'Til');
define('JS_LANG_Date', 'Dato');
define('JS_LANG_Size', 'Størrelse');
define('JS_LANG_Subject', 'Emne');

define('JS_LANG_FirstPage', 'Første Side');
define('JS_LANG_PreviousPage', 'Forrige Side');
define('JS_LANG_NextPage', 'Neste Side');
define('JS_LANG_LastPage', 'Siste Side');

define('JS_LANG_SwitchToPlain', 'Bytt til ren tekstvisning');
define('JS_LANG_SwitchToHTML', 'Bytt til HTML-visning');
define('JS_LANG_AddToAddressBook', 'Legg til i adressebok');
define('JS_LANG_ClickToDownload', 'Klikk for å laste ned');
define('JS_LANG_View', 'Vis');
define('JS_LANG_ShowFullHeaders', 'Vis meldingshode');
define('JS_LANG_HideFullHeaders', 'Skjul meldingshode');

define('JS_LANG_MessagesInFolder', 'Beskjed(er) i mappe');
define('JS_LANG_YouUsing', 'Du benytter');
define('JS_LANG_OfYour', 'av din');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Send');
define('JS_LANG_SaveMessage', 'Lagre');
define('JS_LANG_Print', 'Skriv Ut');
define('JS_LANG_PreviousMsg', 'Forrige');
define('JS_LANG_NextMsg', 'Neste');
define('JS_LANG_AddressBook', 'Adressebok');
define('JS_LANG_ShowBCC', 'Vis Blindkopi');
define('JS_LANG_HideBCC', 'Skjul Blindkopi');
define('JS_LANG_CC', 'Kopi');
define('JS_LANG_BCC', 'BK');
define('JS_LANG_ReplyTo', 'Svar&nbsp;Til');
define('JS_LANG_AttachFile', 'Legg Ved Fil');
define('JS_LANG_Attach', 'Legg Ved');
define('JS_LANG_Re', 'Sv');
define('JS_LANG_OriginalMessage', 'Original Beskjed');
define('JS_LANG_Sent', 'Sent');
define('JS_LANG_Fwd', 'Vds');
define('JS_LANG_Low', 'Lav');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Høy');
define('JS_LANG_Importance', 'Viktighet');
define('JS_LANG_Close', 'Steng');

define('JS_LANG_Common', 'Generelt');
define('JS_LANG_EmailAccounts', 'Epostkontoer');

define('JS_LANG_MsgsPerPage', 'Meldinger per side');
define('JS_LANG_DisableRTE', 'Deaktiver rik tekst redigering');
define('JS_LANG_Skin', 'Utseende');
define('JS_LANG_DefCharset', 'Standard tegnsett');
define('JS_LANG_DefCharsetInc', 'Standard innkommende tegnsett');
define('JS_LANG_DefCharsetOut', 'Standard utgående tegnsett');
define('JS_LANG_DefTimeOffset', 'Standard tidsinnstilling');
define('JS_LANG_DefLanguage', 'Standard Språk');
define('JS_LANG_DefDateFormat', 'Standard datoformat');
define('JS_LANG_ShowViewPane', 'Meldinger med forhåndsvisning');
define('JS_LANG_Save', 'Lagre');
define('JS_LANG_Cancel', 'Avbryt');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Fjern');
define('JS_LANG_AddNewAccount', 'Legg Til Ny Konto');
define('JS_LANG_Signature', 'Signatur');
define('JS_LANG_Filters', 'Filter(e)');
define('JS_LANG_Properties', 'Egenskaper');
define('JS_LANG_UseForLogin', 'Benytt disse kontoegenskapene (brukernavn og passord) for å logge inn');
define('JS_LANG_MailFriendlyName', 'Ditt Navn');
define('JS_LANG_MailEmail', 'Epost');
define('JS_LANG_MailIncHost', 'Innkommende epost');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Logg Inn');
define('JS_LANG_MailIncPass', 'Passord');
define('JS_LANG_MailOutHost', 'SMTP Server');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Logg Inn');
define('JS_LANG_MailOutPass', 'SMTP Passord');
define('JS_LANG_MailOutAuth1', 'Benytt SMTP godkjenning');
define('JS_LANG_MailOutAuth2', '(Du kan la SMTP logg inn/passord feltene være blanke dersom det er det samme som benyttes på POP3/IMAP4 for logg inn og passord)');
define('JS_LANG_UseFriendlyNm1', 'Bruk vennlig navn i "Fra:" feltet');
define('JS_LANG_UseFriendlyNm2', '(Ditt navn &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Motta/Synkroniser meldinger ved innlogging');
define('JS_LANG_MailMode0', 'Slett mottatte meldinger fra server');
define('JS_LANG_MailMode1', 'Behold kopi på server');
define('JS_LANG_MailMode2', 'Behold meldinger på server i');
define('JS_LANG_MailsOnServerDays', 'dag(er)');
define('JS_LANG_MailMode3', 'Slett melding fra server når den er fjernet fra søppelbøtten');
define('JS_LANG_InboxSyncType', 'Type innboks-synkronisering');

define('JS_LANG_SyncTypeNo', 'Ikke Synkroniser');
define('JS_LANG_SyncTypeNewHeaders', 'Nye headere');
define('JS_LANG_SyncTypeAllHeaders', 'Alle headere');
define('JS_LANG_SyncTypeNewMessages', 'Nye Meldinger');
define('JS_LANG_SyncTypeAllMessages', 'Alle Meldinger');
define('JS_LANG_SyncTypeDirectMode', 'Direkte Modus');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Kun Headere');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Hele Meldinger');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direkte Modus');

define('JS_LANG_DeleteFromDb', 'Slett melding fra database dersom den ikke ekisterer på eposttjeneren');

define('JS_LANG_EditFilter', 'Rediger filter');
define('JS_LANG_NewFilter', 'Legg til filter');
define('JS_LANG_Field', 'Felt');
define('JS_LANG_Condition', 'Betingelse');
define('JS_LANG_ContainSubstring', 'Inneholder substring');
define('JS_LANG_ContainExactPhrase', 'Inneholder nøyaktig frase');
define('JS_LANG_NotContainSubstring', 'Ikke inkluder substring');
define('JS_LANG_FilterDesc_At', 'at');
define('JS_LANG_FilterDesc_Field', 'felt');
define('JS_LANG_Action', 'Handling');
define('JS_LANG_DoNothing', 'Ikke gjør noe');
define('JS_LANG_DeleteFromServer', 'Slett fra server umiddelbart');
define('JS_LANG_MarkGrey', 'Marker grå');
define('JS_LANG_Add', 'Legg Til');
define('JS_LANG_OtherFilterSettings', 'andre filter innstillinger');
define('JS_LANG_ConsiderXSpam', 'Vurder X-Spam headers');
define('JS_LANG_Apply', 'Bruk');

define('JS_LANG_InsertLink', 'Sett inn link');
define('JS_LANG_RemoveLink', 'Fjern link');
define('JS_LANG_Numbering', 'Nummerering');
define('JS_LANG_Bullets', 'Kuler');
define('JS_LANG_HorizontalLine', 'Horisontal linje');
define('JS_LANG_Bold', 'Fet');
define('JS_LANG_Italic', 'Kursiv');
define('JS_LANG_Underline', 'Understrek');
define('JS_LANG_AlignLeft', 'Venstrejuster');
define('JS_LANG_Center', 'Sentrer');
define('JS_LANG_AlignRight', 'Høyrejuster');
define('JS_LANG_Justify', 'Justify');
define('JS_LANG_FontColor', 'Skriftfarge');
define('JS_LANG_Background', 'Bakgrunn');
define('JS_LANG_SwitchToPlainMode', 'Bytt til enkel tekst modus');
define('JS_LANG_SwitchToHTMLMode', 'Bytt til HTML modus');
define('JS_LANG_Folder', 'Mappe');
define('JS_LANG_Msgs', 'Msg\'s');
define('JS_LANG_Synchronize', 'Synkroniser');
define('JS_LANG_ShowThisFolder', 'Vis denne mappen');
define('JS_LANG_Total', 'Totalt');
define('JS_LANG_DeleteSelected', 'Slett Markert(e)');
define('JS_LANG_AddNewFolder', 'Legg til ny mappe');
define('JS_LANG_NewFolder', 'Ny mappe');
define('JS_LANG_ParentFolder', 'Forrige mappe');
define('JS_LANG_NoParent', 'Ingen Forrige');
define('JS_LANG_FolderName', 'Mappenavn');

define('JS_LANG_ContactsPerPage', 'Kontakter per side');
define('JS_LANG_WhiteList', 'Adressebok som hvitliste');

define('JS_LANG_CharsetDefault', 'Standard');
define('JS_LANG_CharsetArabicAlphabetISO', 'Arabisk Alfabet (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Arabisk Alfabet (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Baltisk Alfabet (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Baltisk Alfabet(Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Sentral Europeisk Alfabet (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Sentral Europeisk Alfabet (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Kinesisk, Enkelt (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Kinesisk, Enkelt (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Kinesisk, Tradisjonelt (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Cyrillic Alfabet (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Cyrillic Alfabet (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Cyrillic Alfabet (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Gresk Alfabet (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Greek Alfabet (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Hebrisk Alfabet (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'Hebrisk Alfabet (Windows)');
define('JS_LANG_CharsetJapanese', 'Japansk');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japansk (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Koreansk (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Koreansk (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latinsk 3 Alfabet (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Tyrkisk Alfabet');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Universalt Alfabet (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Universalt Alfabet (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Vietnamisk Alfabet (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Western Alfabet (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Western Alfabet (Windows)');

define('JS_LANG_TimeDefault', 'Standard');
define('JS_LANG_TimeEniwetok', 'Eniwetok, Kwajalein, Datolinje Tid');
define('JS_LANG_TimeMidwayIsland', 'Midway Island, Samoa');
define('JS_LANG_TimeHawaii', 'Hawaii');
define('JS_LANG_TimeAlaska', 'Alaska');
define('JS_LANG_TimePacific', 'Pasifisk Tid (US & Canada); Tijuana');
define('JS_LANG_TimeArizona', 'Arisona');
define('JS_LANG_TimeMountain', 'Fjell Tid (US & Canada)');
define('JS_LANG_TimeCentralAmerica', 'Sentral Amerika');
define('JS_LANG_TimeCentral', 'Sentral Tid (US & Canada)');
define('JS_LANG_TimeMexicoCity', 'Mexico City, Tegucigalpa');
define('JS_LANG_TimeSaskatchewan', 'Saskatchewan');
define('JS_LANG_TimeIndiana', 'Indiana (Øst)');
define('JS_LANG_TimeEastern', 'Øst Tid (US & Canada)');
define('JS_LANG_TimeBogota', 'Bogota, Lima, Quito');
define('JS_LANG_TimeSantiago', 'Santiago');
define('JS_LANG_TimeCaracas', 'Caracas, La Paz');
define('JS_LANG_TimeAtlanticCanada', 'Atlantisk Tid (Canada)');
define('JS_LANG_TimeNewfoundland', 'Newfoundland');
define('JS_LANG_TimeGreenland', 'Grønnland');
define('JS_LANG_TimeBuenosAires', 'Buenos Aires, Georgetown');
define('JS_LANG_TimeBrasilia', 'Brasil');
define('JS_LANG_TimeMidAtlantic', 'Mid-Atlantic');
define('JS_LANG_TimeCapeVerde', 'Cape Verde Is.');
define('JS_LANG_TimeAzores', 'Asorene');
define('JS_LANG_TimeMonrovia', 'Casablanca, Monrovia');
define('JS_LANG_TimeGMT', 'Dublin, Edinburgh, Lisboa, London');
define('JS_LANG_TimeBerlin', 'Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna');
define('JS_LANG_TimePrague', 'Belgrade, Bratislava, Budapest, Ljubljana, Prague');
define('JS_LANG_TimeParis', 'Brussels, Kjøbenhavn, Madrid, Paris');
define('JS_LANG_TimeSarajevo', 'Sarajevo, Skopje, Sofija, Warsaw, Zagreb');
define('JS_LANG_TimeWestCentralAfrica', 'Vest Sentral Afrika');
define('JS_LANG_TimeAthens', 'Aten, Istanbul, Minsk');
define('JS_LANG_TimeEasternEurope', 'Bucharest');
define('JS_LANG_TimeCairo', 'Cairo');
define('JS_LANG_TimeHarare', 'Harare, Pretoria');
define('JS_LANG_TimeHelsinki', 'Helsinki, Riga, Tallinn, Vilnius');
define('JS_LANG_TimeIsrael', 'Israel, Jerusalem Standard Tid');
define('JS_LANG_TimeBaghdad', 'Baghdad');
define('JS_LANG_TimeArab', 'Arab, Kuwait, Riyadh');
define('JS_LANG_TimeMoscow', 'Moscow, St. Petersburg, Volgograd');
define('JS_LANG_TimeEastAfrica', 'Øst Afrika, Nairobi');
define('JS_LANG_TimeTehran', 'Tehran');
define('JS_LANG_TimeAbuDhabi', 'Abu Dhabi, Muscat');
define('JS_LANG_TimeCaucasus', 'Baku, Tbilisi, Yerevan');
define('JS_LANG_TimeKabul', 'Kabul');
define('JS_LANG_TimeEkaterinburg', 'Ekaterinburg');
define('JS_LANG_TimeIslamabad', 'Islamabad, Karachi, Sverdlovsk, Tashkent');
define('JS_LANG_TimeBombay', 'Calcutta, Chennai, Mumbai, New Delhi, India Standard Tid');
define('JS_LANG_TimeNepal', 'Kathmandu, Nepal');
define('JS_LANG_TimeAlmaty', 'Almaty, Nord Sentral Asia');
define('JS_LANG_TimeDhaka', 'Astana, Dhaka');
define('JS_LANG_TimeSriLanka', 'Sri Jayawardenepura, Sri Lanka');
define('JS_LANG_TimeRangoon', 'Rangoon');
define('JS_LANG_TimeBangkok', 'Bangkok, Novosibirsk, Hanoi, Jakarta');
define('JS_LANG_TimeKrasnoyarsk', 'Krasnoyarsk');
define('JS_LANG_TimeBeijing', 'Beijing, Chongqing, Hong Kong SAR, Urumqi');
define('JS_LANG_TimeUlaanBataar', 'Ulaan Bataar');
define('JS_LANG_TimeSingapore', 'Kuala Lumpur, Singapore');
define('JS_LANG_TimePerth', 'Perth, Vest Australia');
define('JS_LANG_TimeTaipei', 'Taipei');
define('JS_LANG_TimeTokyo', 'Osaka, Sapporo, Tokyo, Irkutsk');
define('JS_LANG_TimeSeoul', 'Seoul, Korea Standard tid');
define('JS_LANG_TimeYakutsk', 'Yakutsk');
define('JS_LANG_TimeAdelaide', 'Adelaide, Sentral Australia');
define('JS_LANG_TimeDarwin', 'Darwin');
define('JS_LANG_TimeBrisbane', 'Brisbane, Øst Australia');
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
define('JS_LANG_DateAdvanced', 'Avansert');

define('JS_LANG_NewContact', 'Ny Kontakt');
define('JS_LANG_NewGroup', 'Ny Gruppe');
define('JS_LANG_AddContactsTo', 'Legg Kontakter Til');
define('JS_LANG_ImportContacts', 'Importer kontakter');

define('JS_LANG_Name', 'Navn');
define('JS_LANG_Email', 'Epost');
define('JS_LANG_DefaultEmail', 'Standard Epostadresse');
define('JS_LANG_NotSpecifiedYet', 'Enda Ikke Spesifisert');
define('JS_LANG_ContactName', 'Navn');
define('JS_LANG_Birthday', 'Bursdag');
define('JS_LANG_Month', 'Måned');
define('JS_LANG_January', 'Januar');
define('JS_LANG_February', 'Februar');
define('JS_LANG_March', 'Mars');
define('JS_LANG_April', 'April');
define('JS_LANG_May', 'Mai');
define('JS_LANG_June', 'Juni');
define('JS_LANG_July', 'Juli');
define('JS_LANG_August', 'August');
define('JS_LANG_September', 'September');
define('JS_LANG_October', 'Oktober');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'Desember');
define('JS_LANG_Day', 'Dag');
define('JS_LANG_Year', 'År');
define('JS_LANG_UseFriendlyName1', 'Benytt Vennlig Navn');
define('JS_LANG_UseFriendlyName2', '(f.Eks., Ola Nordmann &lt;olanordmann@fastname.no&gt;)');
define('JS_LANG_Personal', 'Personlig');
define('JS_LANG_PersonalEmail', 'Personlig Epost');
define('JS_LANG_StreetAddress', 'Adresse');
define('JS_LANG_City', 'By');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Fylke');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'Postnummer');
define('JS_LANG_Mobile', 'Mobil');
define('JS_LANG_CountryRegion', 'Land');
define('JS_LANG_WebPage', 'Webside');
define('JS_LANG_Go', 'Gå');
define('JS_LANG_Home', 'Hjem');
define('JS_LANG_Business', 'Jobb');
define('JS_LANG_BusinessEmail', 'Jobb Epost');
define('JS_LANG_Company', 'Firma');
define('JS_LANG_JobTitle', 'Tittel');
define('JS_LANG_Department', 'Avdeling');
define('JS_LANG_Office', 'Kontor');
define('JS_LANG_Pager', 'Personsøker');
define('JS_LANG_Other', 'Annet');
define('JS_LANG_OtherEmail', 'Annen Epost');
define('JS_LANG_Notes', 'Notater');
define('JS_LANG_Groups', 'Grupper');
define('JS_LANG_ShowAddFields', 'Vis tilleggsfelt');
define('JS_LANG_HideAddFields', 'Skjul tilleggsfelt');
define('JS_LANG_EditContact', 'Rediger kontaktinformasjon');
define('JS_LANG_GroupName', 'Gruppe Navn');
define('JS_LANG_AddContacts', 'Legg Til Kontakter');
define('JS_LANG_CommentAddContacts', '(Dersom du skal spesifisere mer enn en adresse, vær vennlig og skill de med komme (,))');
define('JS_LANG_CreateGroup', 'Opprett Gruppe');
define('JS_LANG_Rename', 'Gi Nytt Navn');
define('JS_LANG_MailGroup', 'Send e-post til gruppe');
define('JS_LANG_RemoveFromGroup', 'Fjern fra gruppe');
define('JS_LANG_UseImportTo', 'Benytt importer funksjonen til og kopiere kontakter i fra Microsoft Outlook, Microsoft Outlook Express inn til WebMailens adressebok.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Velg den filen du ønsker å importere (.CSV format)');
define('JS_LANG_Import', 'Importer');
define('JS_LANG_ContactsMessage', 'Dette er kontakt siden!!!');
define('JS_LANG_ContactsCount', 'kontakt(er)');
define('JS_LANG_GroupsCount', 'grupp(er)');

// webmail 4.1 constants
define('PicturesBlocked', 'Bilder i denne meldingen er blitt blokert for din sikkerhet.');
define('ShowPictures', 'Vis Bilder');
define('ShowPicturesFromSender', 'Alltid vis bilder i meldinger fra denne avsender');
define('AlwaysShowPictures', 'Alltid vis bilder i meldinger');

define('TreatAsOrganization', 'Oppfatt som en organisasjon');

define('WarningGroupAlreadyExist', 'En gruppe med dette navnet eksisterer alt. Velg et annet navn.');
define('WarningCorrectFolderName', 'Du burde velge et korrekt navn på gruppen');
define('WarningLoginFieldBlank', 'Du kan ikke la Logg Inn felt være blanke');
define('WarningCorrectLogin', 'Du må ha korrekt Logg Inn');
define('WarningPassBlank', 'Du kan ikke la passord feltet være tomt.');
define('WarningCorrectIncServer', 'Du burde spesifisere en korrekt adresse til server for POP3(IMAP).');
define('WarningCorrectSMTPServer', 'Du burde spesifisere korrekt adresse til server for SMTP.');
define('WarningFromBlank', 'Du kan ikke la Fra: feltet være blankt.');
define('WarningAdvancedDateFormat', 'Vær vennlig og spesifiser dato- og tidsformat.');

define('AdvancedDateHelpTitle', 'Avansert Dato');
define('AdvancedDateHelpIntro', 'Når &quot;Advanced&quot; feltet er valgt, kan du benytte tekstboksen til og spesifisere ditt eget datoformat, som da vil bli vist i WebMail Pro. Følgende valg er benyttet i denne mening sammen med \':\' eller \'/\':');
define('AdvancedDateHelpConclusion', 'F.eks., dersom du har spesifisert &quot;mm/dd/yyyy&quot; verdier i tekstboksen &quot;Advanced&quot; felter, blir datoen vist som måned/dag/år (11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Dag i måneden (1 til 31)');
define('AdvancedDateHelpNumericMonth', 'Måned (1 til 12)');
define('AdvancedDateHelpTextualMonth', 'Måned (Jan til Des)');
define('AdvancedDateHelpYear2', 'År, 2 tall');
define('AdvancedDateHelpYear4', 'År, 4 tall');
define('AdvancedDateHelpDayOfYear', 'Dag i året (1 ttil 366)');
define('AdvancedDateHelpQuarter', 'Kvartal');
define('AdvancedDateHelpDayOfWeek', 'Dag i uken (Man ttil Søn)');
define('AdvancedDateHelpWeekOfYear', 'Uke i året (1 til 53)');

define('InfoNoMessagesFound', 'Ingen Meldinger Funnet.');
define('ErrorSMTPConnect', 'Kan ikke koble til SMTP server. Kontroller dine SMTP server innstillinger.');
define('ErrorSMTPAuth', 'Feil brukenavn og/eller passord. Godkjenning feilet.');
define('ReportMessageSent', 'Din melding er sendt.');
define('ReportMessageSaved', 'Din melding er blitt lagret.');
define('ErrorPOP3Connect', 'Kan ikke koble til POP3 server, sjekk POP3 severinnstillinger.');
define('ErrorIMAP4Connect', 'Kan ikke koble til IMAP4 server, sjekk IMAP4 serverinnstillinger.');
define('ErrorPOP3IMAP4Auth', 'Feil epost/logg inn og/eller passord. Godkjenning feilet.');
define('ErrorGetMailLimit', 'Beklager, din innboks er full.');

define('ReportSettingsUpdatedSuccessfuly', 'Dine innstillinger har blitt vellykket oppdatert.');
define('ReportAccountCreatedSuccessfuly', 'Konto har blitt opprett vellykket.');
define('ReportAccountUpdatedSuccessfuly', 'Din konto ble vellykket oppdatert.');
define('ConfirmDeleteAccount', 'Er du sikker på du vil slette denne kontoen?');
define('ReportFiltersUpdatedSuccessfuly', 'Filtere har blitt vellykket oppdatert.');
define('ReportSignatureUpdatedSuccessfuly', 'Signaturen har blitt vellykket oppdatert.');
define('ReportFoldersUpdatedSuccessfuly', 'Mapper har blitt vellykket oppdatert.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Kontaktinnstillinger har blitt vellykket oppdatert.');

define('ErrorInvalidCSV', 'CSV filen du valgte har feil format.');
//Gruppen "guies" ble vellykket lagt til.
define('ReportGroupSuccessfulyAdded1', 'Gruppen');
define('ReportGroupSuccessfulyAdded2', 'Ble vellykket lagt til.');
define('ReportGroupUpdatedSuccessfuly', 'Gruppen har blitt vellykket oppdatert.');
define('ReportContactSuccessfulyAdded', 'Kontakt ble lagt til.');
define('ReportContactUpdatedSuccessfuly', 'Kontakten har vellykket blitt oppdatert.');
//Kontakt(er) ble lagt til gruppen "venner".
define('ReportContactAddedToGroup', 'Kontakt(er) ble lagt til gruppen');
define('AlertNoContactsGroupsSelected', 'Ingen Kontakt eller grupper valgt.');

define('InfoListNotContainAddress', 'Dersom listen ikke inneholder adressen du leter etter, forsøk å skriv inn dens første tegn.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direkte Modus. WebMail leser meldingen direkte fra server.');

define('FolderInbox', 'Innboks');
define('FolderSentItems', 'Sendte Elementer');
define('FolderDrafts', 'Utkast');
define('FolderTrash', 'Søppel');

define('FileLargerAttachment', 'Filen overskriver Vedleggets maksimalt tillatte størrelse.');
define('FilePartiallyUploaded', 'Bare en del av filen ble lastet opp grunnet en ukjent feil.');
define('NoFileUploaded', 'Ingen fil ble lastet opp.');
define('MissingTempFolder', 'Den midlertidige mappen mangler.');
define('MissingTempFile', 'Den midlertidige filen mangler.');
define('UnknownUploadError', 'En ukjent opplastingsfeil inntraff.');
define('FileLargerThan', 'Filopplastningsfeil. Mest trolig er filen større enn ');
define('PROC_CANT_LOAD_DB', 'Kan ikke koble til database');
define('PROC_CANT_LOAD_LANG', 'Kan ikke finne den nødvendige språkfilen.');
define('PROC_CANT_LOAD_ACCT', 'Kontoen eksisterer ikke, kanskje har den blitt slettet.');

define('DomainDosntExist', 'Dette domenet eksisterer ikke på vår server.');
define('ServerIsDisable', 'Benyttelse av eporttjener er begrenset til Administrator.');

define('PROC_ACCOUNT_EXISTS', 'Kontoen kan ikke bli opprettet. Den ekisterer fra før.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Kan ikke hente mappe og antall meldinger.');
define('PROC_CANT_MAIL_SIZE', 'Kan ikke hente ut epostkvote.');

define('Organization', 'Organisasjon');
define('WarningOutServerBlank', 'Du kan ikke la SMTP serverfeltet være blankt');

//
define('JS_LANG_Refresh', 'Oppdater');
define('JS_LANG_MessagesInInbox', 'Beskjed(er) i innboksen');
define('JS_LANG_InfoEmptyInbox', 'Din innboks er tom');

// webmail 4.2 constants
define('BackToList', 'Tilbake til liste');
define('InfoNoContactsGroups', 'Ingen kontakter eller grupper.');
define('InfoNewContactsGroups', 'Du kan lage nye kontakter/grupper eller importere kontakter i fra en .CSV fil i MS Outlook format.');
define('DefTimeFormat', 'Standard tidsformat');
define('SpellNoSuggestions', 'Ingen forslag');
define('SpellWait', 'Vennligst vent&hellip;');

define('InfoNoMessageSelected', 'Ingen meldinger valgt.');
define('InfoSingleDoubleClick', 'Du kan enkelt klikke på meldingen for forhåndsvisningen her, eller dobbelklikke for å se den i full størrelse.	');

// calendar
define('TitleDay', 'Dag Visning');
define('TitleWeek', 'Ukes Visning');
define('TitleMonth', 'Månedlig Visning');

define('ErrorNotSupportBrowser', 'Etter logisk kalender støtter ikke din nettleser. Vennligst benytt 2.0 eller nyere, Opera 9.0 eller      nyere, Internet Explorer 6.0 eller nyere, Safari 3.0.2 eller nyere.');
define('ErrorTurnedOffActiveX', 'ActiveX-støtte er deaktivert. <br/>Du må skru på dette for å benytte denne applikasjonen.');

define('Calendar', 'Kalender');

define('TabDay', 'Dag');
define('TabWeek', 'Uke');
define('TabMonth', 'Måned');

define('ToolNewEvent', 'Ny&nbsp;hendelse');
define('ToolBack', 'tilbake');
define('ToolToday', 'I Dag');
define('AltNewEvent', 'Ny hendelse');
define('AltBack', 'Tilbake');
define('AltToday', 'I Dag');
define('CalendarHeader', 'Kalender');
define('CalendarsManager', 'Administrer Kalender');

define('CalendarActionNew', 'Ny Kalender');
define('EventHeaderNew', 'Ny Hendelse');
define('CalendarHeaderNew', 'Ny Kalender');

define('EventSubject', 'Emne');
define('EventCalendar', 'Kalender');
define('EventFrom', 'Fra');
define('EventTill', 'til');
define('CalendarDescription', 'Beskrivelse');
define('CalendarColor', 'Farge');
define('CalendarName', 'Kalender navn');
define('CalendarDefaultName', 'Min Kalender');

define('ButtonSave', 'Lagre');
define('ButtonCancel', 'Avbryt');
define('ButtonDelete', 'Slett');

define('AltPrevMonth', 'Forrige måned');
define('AltNextMonth', 'Neste måned');

define('CalendarHeaderEdit', 'Rediger kalender');
define('CalendarActionEdit', 'Rediger Kalender');
define('ConfirmDeleteCalendar', 'Er du sikker på at du ønsker å slette kalenderen');
define('InfoDeleting', 'Sletter&hellip;');
define('WarningCalendarNameBlank', 'Du kan ikke la kalendernavnet være blankt.');
define('ErrorCalendarNotCreated', 'Kalender ikke opprettet');
define('WarningSubjectBlank', 'Du kan ikke la emnet være blankt.');
define('WarningIncorrectTime', 'Den spesifiserte tiden inneholder ikke tillatte tegn.');
define('WarningIncorrectFromTime', 'Fra tiden er feil.');
define('WarningIncorrectTillTime', 'Til tiden er feil.');
define('WarningStartEndDate', 'Sluttdatoen må være større eller lik startdatoen.');
define('WarningStartEndTime', 'Sluttiden må være større eller lik starttiden.');
define('WarningIncorrectDate', 'Datoen må være korrekt.');
define('InfoLoading', 'Laster&hellip;');
define('EventCreate', 'Opprett hendelse');
define('CalendarHideOther', 'Skjul andre kalendere');
define('CalendarShowOther', 'Vis andre kalendere');
define('CalendarRemove', 'Fjern kalender');
define('EventHeaderEdit', 'Rediger hendelse');

define('InfoSaving', 'Lagrer&hellip;');
define('SettingsDisplayName', 'Vis Navn');
define('SettingsTimeFormat', 'Tids format');
define('SettingsDateFormat', 'Dato format');
define('SettingsShowWeekends', 'Vis helger');
define('SettingsWorkdayStarts', 'Arbeidsdagen starter');
define('SettingsWorkdayEnds', 'slutter');
define('SettingsShowWorkday', 'Vis arbeidsdag');
define('SettingsWeekStartsOn', 'Uke starter på');
define('SettingsDefaultTab', 'Standard Tab');
define('SettingsCountry', 'Land');
define('SettingsTimeZone', 'Tidssone');
define('SettingsAllTimeZones', 'Alle tidssoner');

define('WarningWorkdayStartsEnds', 'Arbeidsdagen slutter tiden må være større enn arbeidsdagen starter tiden.');
define('ReportSettingsUpdated', 'Innstillinger ble vellykket oppdatert.');

define('SettingsTabCalendar', 'Kalender');

define('FullMonthJanuary', 'Januar');
define('FullMonthFebruary', 'Februar');
define('FullMonthMarch', 'Mars');
define('FullMonthApril', 'April');
define('FullMonthMay', 'Mai');
define('FullMonthJune', 'Juni');
define('FullMonthJuly', 'Juli');
define('FullMonthAugust', 'August');
define('FullMonthSeptember', 'September');
define('FullMonthOctober', 'Oktober');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'Desember');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Apr');
define('ShortMonthMay', 'Mai');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Aug');
define('ShortMonthSeptember', 'Sep');
define('ShortMonthOctober', 'Okt');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Des');

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

define('ErrorParseJSON', 'JSON svaret fra server kan ikke bli parset.');

define('ErrorLoadCalendar', 'Kan ikke laste kalender(e)');
define('ErrorLoadEvents', 'Kan ikke laste hendelse(r)');
define('ErrorUpdateEvent', 'Kan ikke lagre hendelse(r)');
define('ErrorDeleteEvent', 'Kan ikke slette hendelse(r)');
define('ErrorUpdateCalendar', 'Kan ikke lagre kalender');
define('ErrorDeleteCalendar', 'Kan ikke slette kalender');
define('ErrorGeneral', 'En feil skjedde på server. Prøv igjen senere.');

// webmail 4.3 constants
define('SharedTitleEmail', 'Epost');
define('ShareHeaderEdit', 'Del og publiser kalender');
define('ShareActionEdit', 'Del og publiser kalender');
define('CalendarPublicate', 'Gi tilgang til kalender over internett');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Del denne kalenderen');
define('SharePermission1', 'Kan gjøre endringer og administrere deling');
define('SharePermission2', 'Kan gjøre endringer i hendelser');
define('SharePermission3', 'Kan se alle hendelsesdetaljer');
define('SharePermission4', 'Kan kun se opptatt/ledig (skjul detaljer)');
define('ButtonClose', 'Lukk');
define('WarningEmailFieldFilling', 'Du må fylle ut epostfeltet først');
define('EventHeaderView', 'Se hendelser');
define('ErrorUpdateSharing', 'Kan ikke lagre data');
define('ErrorUpdateSharing1', 'Kan ikke dele til %s brukeren da den ikke eksisterer');
define('ErrorUpdateSharing2', 'Kan ikke dele denne kalenderen med bruker %s');
define('ErrorUpdateSharing3', 'Denne kalenderen er allerede delt med bruker %s');
define('Title_MyCalendars', 'Mine kalendere');
define('Title_SharedCalendars', 'Delte kalendere');
define('ErrorGetPublicationHash', 'Kan ikke opprette link');
define('ErrorGetSharing', 'Kan ikke legge til deling');
define('CalendarPublishedTitle', 'Denne kalenderen er publisert');
define('RefreshSharedCalendars', 'Oppdater Delte Kalendere');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Medlemmer');

define('ReportMessagePartDisplayed', 'Merk: Kun deler av meldingen er vist.');
define('ReportViewEntireMessage', 'For å se hele meldingen,');
define('ReportClickHere', 'klikk her');
define('ErrorContactExists', 'En kontakt med ønsket navn/epostadresse eksisterer.');

define('Attachments', 'Vedlegg');

define('InfoGroupsOfContact', 'Gruppene kontakten er medlem av er avmerket.');
define('AlertNoContactsSelected', 'Ingen kontakter valgt.');
define('MailSelected', 'Send epost til valgt adresse');
define('CaptionSubscribed', 'Abonnert til');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Ikke Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Send e-post til kontakt');
define('ContactViewAllMails', 'Se all korrespondanse med kontakt');
define('ContactsMailThem', 'Send e-post');
define('DateToday', 'I dag');
define('DateYesterday', 'Yesterday');
define('MessageShowDetails', 'Vis detaljer');
define('MessageHideDetails', 'Skjul detaljer');
define('MessageNoSubject', 'Intet emne');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'til');
define('SearchClear', 'Fjern søk');
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
define('RepeatEvent', 'Gjenta denne hendelsen');

define('Spellcheck', 'Check Spelling');
define('LoginLanguage', 'Language');
define('LanguageDefault', 'Standard');

// webmail 4.5.x new
define('EmptySpam', 'Tøm Spam');
define('Saving', 'Lagrer&hellip;');
define('Sending', 'Sender&hellip;');
define('LoggingOffFromServer', 'Logger av server&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Avmerkede meldinger kan ikke settes som spam');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Avmerkede meldinger kan ikke settes som hvitelistet');
define('ExportToICalendar', 'Eksporter til iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Din konto er deaktivert grunnet antall brukere i henhold til lisensavtale. Vennligst kontakt Systemadministrator.');

define('RepliedMessageTitle', 'Besvart melding');
define('ForwardedMessageTitle', 'Videresendt melding');
define('RepliedForwardedMessageTitle', 'Besvart og videresendt melding');
define('ErrorDomainExist', 'Brukeren kan ikke opprettes fordi domenet ikke eksisterer. Opprett domenet først');

// webmail 4.6.x or 4.7
define('RequestReadConfirmation', 'Lesebekreftelse');
define('FolderTypeDefault', 'Default');
define('ShowFoldersMapping', 'Let me use another folder as a system folder (e.g. use MyFolder as Sent Items)');
define('ShowFoldersMappingNote', 'For instance, to change Sent Items location from Sent Items to MyFolder, specify "Sent Items" in "Use for" dropdown of "MyFolder".');
define('FolderTypeMapTo', 'Use for');

define('ReminderEmailExplanation', 'This message arrived to your %EMAIL% account because you ordered event notification in your calendar: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Open calendar');

define('AddReminder', 'Minn meg på denne hendelsen');
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
define('NoSubject', 'No Subject');
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
define('InsertImage', 'Sett inn bilde');
define('ImagePath', 'Image Path');
define('ImageUpload', 'Insert');
define('WarningImageUpload', 'The file being attached is not an image. Please choose an image file.');

define('ConfirmExitFromNewMessage', 'Endringer vil gå tapt dersom du forlater denne siden. Trykk avbryt og så lagre. Ellers trykker du ok.');

define('SensivityConfidential', 'Please treat this message as Confidential');
define('SensivityPrivate', 'Please treat this message as Private');
define('SensivityPersonal', 'Please treat this message as Personal');

define('ReturnReceiptTopText', 'The sender of this message has asked to be notified when you receive this message.');
define('ReturnReceiptTopLink', 'Click here to notify the sender.');
define('ReturnReceiptSubject', 'Return Receipt (displayed)');
define('ReturnReceiptMailText1', 'This is a Return Receipt for the mail that you sent to');
define('ReturnReceiptMailText2', 'Note: This Return Receipt only acknowledges that the message was displayed on the recipient\'s computer. There is no guarantee that the recipient has read or understood the message contents.');
define('ReturnReceiptMailText3', 'with subject');

define('SensivityMenu', 'Sensitivitet');
define('SensivityNothingMenu', 'Ingen');
define('SensivityConfidentialMenu', 'Konfidensiell');
define('SensivityPrivateMenu', 'Privat');
define('SensivityPersonalMenu', 'Personlig');

define('ErrorLDAPonnect', 'Can\'t connect to ldap server.');

define('MessageSizeExceedsAccountQuota', 'This message size exceeds your account quota.');
define('MessageCannotSent', 'The message cannot be sent.');
define('MessageCannotSaved', 'The message cannot be saved.');

define('ContactFieldTitle', 'Felt');
define('ContactDropDownTO', 'Til');
define('ContactDropDownCC', 'Kopi');
define('ContactDropDownBCC', 'BK');

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
define('AppointmentAddGuests', 'Legg til gjester');
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
define('SettingsAutoAddInvitation', 'Legg invitasjoner automatisk inn i kalenderen');
define('ReportEventSaved', 'Your event has been saved');
define('ReportAppointmentSaved', ' and notifications were sent');
define('ErrorAppointmentSend', 'Can\'t send invitations.');
define('AppointmentEventName', 'Name:');

// End appointments

define('ErrorCantUpdateFilters', 'Can\'t update filters');

define('FilterPhrase', 'Dersom %field feltet i meldingshodet %condition %string så %action');
define('FiltersAdd', 'Legg til filter');
define('FiltersCondEqualTo', 'er lik');
define('FiltersCondContainSubstr', 'inneholder teksten');
define('FiltersCondNotContainSubstr', 'inneholder ikke teksten');
define('FiltersActionDelete', 'Slett melding');
define('FiltersActionMove', 'flytt');
define('FiltersActionToFolder', 'til %folder folder');
define('FiltersNo', 'Ingen filter angitt');

define('ReminderEmailFriendly', 'reminder');
define('ReminderEventBegin', 'starts at: ');

define('FiltersLoading', 'Laster filter...');
define('ConfirmMessagesPermanentlyDeleted', 'Alle meldingene i denne mappen vil bli slettet permanent.');

define('InfoNoNewMessages', 'Ingen nye meldinger.');
define('TitleImportContacts', 'Importer Kontakter');
define('TitleSelectedContacts', 'Valgte kontakter');
define('TitleNewContact', 'Ny Kontakt');
define('TitleViewContact', 'Kontaktvisning');
define('TitleEditContact', 'Endre Kontakt');
define('TitleNewGroup', 'Ny Gruppe');
define('TitleViewGroup', 'Visning, gruppe');

define('AttachmentComplete', 'Complete.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Sjekk automatisk for e-post  hver');
define('AutoCheckMailIntervalDisableName', 'Av');
define('ReportCalendarSaved', 'Kalenderen er lagret.');

define('ContactSyncError', 'Sync failed');
define('ReportContactSyncDone', 'Sync complete');

define('MobileSyncUrlTitle', 'Mobile sync URL');
define('MobileSyncLoginTitle', 'Mobile sync login');

define('QuickReply', 'Hurtigsvar');
define('SwitchToFullForm', 'Åpne fullt svarvindu');
define('SortFieldDate', 'Dato');
define('SortFieldFrom', 'Fra');
define('SortFieldSize', 'St�rrelse');
define('SortFieldSubject', 'Emne');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Vedlegg');
define('SortOrderAscending', 'Stigende');
define('SortOrderDescending', 'Synkende');
define('ArrangedBy', 'Satt opp etter');

define('MessagePaneToRight', 'The message pane is to the right of the message list, rather than below');

define('SettingsTabMobileSync', 'Mobile');

define('MobileSyncContactDataBaseTitle', 'Mobile sync contact database');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync calendar database');
define('MobileSyncTitleText', 'If you\'d like to synchronize your SyncML-enabled handheld device with WebMail, you can use these parameters.<br />"Mobile Sync URL" specifies path to SyncML Data Synchronization server, "Mobile Sync Login" is your login on SyncML Data Synchronization Server and use your own password upon request. Also, some devices need to specify database name for contact and calendar data.<br />Use "Mobile sync contact database" and "Mobile sync calendar database" respectively.');
define('MobileSyncEnableLabel', 'Enable mobile sync');

define('SearchInputText', 'Søk');

define('AppointmentEmailExplanation','This message arrived to your %EMAIL% account because you was invited to the event by %ORGANAZER%');

define('Searching', 'Searching&hellip;');

define('ButtonSetupSpecialFolders', 'Setup special folders');
define('ButtonSaveChanges', 'Lagre endringer');
define('InfoPreDefinedFolders', 'For pre-defined folders, use these IMAP mailboxes');

define('SaveMailInSentItems', 'Also save in Sent Items');

define('CouldNotSaveUploadedFile', 'Kunne ikke lagre opplastet fil.');

define('AccountOldPassword', 'Current password');
define('AccountOldPasswordsDoNotMatch', 'Angitt passord er feil.');

define('DefEditor', 'Standard tekstformat');
define('DefEditorRichText', 'Rik Tekst');
define('DefEditorPlainText', 'Enkel Tekst');

define('Layout', 'Layout');

define('TitleNewMessagesCount', '%count% new message(s)');

define('AltOpenInNewWindow', 'Nytt vindu');

define('SearchByFirstCharAll', 'All');

define('FolderNoUsageAssigned', 'No usage assigned');

define('InfoSetupSpecialFolders', 'To match a special folder (like Sent Items) and certain IMAP mailbox, click Setup special folders.');

define('FileUploaderClickToAttach', 'Klikk for å legge ved fil');
define('FileUploaderOrDragNDrop', 'Eller bare dra og slipp filer her');

define('AutoCheckMailInterval1Minute', '1 minutt');
define('AutoCheckMailInterval3Minutes', '3 minutter');
define('AutoCheckMailInterval5Minutes', '5 minutter');
define('AutoCheckMailIntervalMinutes', 'minutter');

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
