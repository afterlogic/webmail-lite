<?php
define('PROC_ERROR_ACCT_CREATE', 'Er is een fout gebeurd bij het maken van uw account');
define('PROC_WRONG_ACCT_PWD', 'Verkeerd paswoord');
define('PROC_CANT_LOG_NONDEF', 'Kan niet inloggen in niet-standaard account');
define('PROC_CANT_INS_NEW_FILTER', 'Kan nieuwe filter niet opslaan');
define('PROC_FOLDER_EXIST', 'Mapnaam bestaat al');
define('PROC_CANT_CREATE_FLD', 'Kan map niet maken');
define('PROC_CANT_INS_NEW_GROUP', 'Kan nieuwe groep niet opslaan');
define('PROC_CANT_INS_NEW_CONT', 'Kan nieuw contact niet opslaan');
define('PROC_CANT_INS_NEW_CONTS', 'Kan nieuwe contact(en) niet opslaan');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Kan contact(en) niet toevoegen aan groep');
define('PROC_ERROR_ACCT_UPDATE', 'Er is een fout gebeurd bij het opslaan van uw account');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Kan de contactinstellingen niet opslaan');
define('PROC_CANT_GET_SETTINGS', 'Kan de instellingen niet vinden');
define('PROC_CANT_UPDATE_ACCT', 'Kan de account niet opslaan');
define('PROC_ERROR_DEL_FLD', 'Er is een fout gebeurd bij het verwijderen van de map(pen)');
define('PROC_CANT_UPDATE_CONT', 'Kan contact niet opslaan');
define('PROC_CANT_GET_FLDS', 'Kan mappenweergave niet ophalen');
define('PROC_CANT_GET_MSG_LIST', 'Kan berichtenlijst niet ophalen');
define('PROC_MSG_HAS_DELETED', 'Dit bericht is al verwijderd van de mailserver');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Kan contactinstellingen niet ophalen');
define('PROC_CANT_LOAD_SIGNATURE', 'Kan account onderschrift niet laden');
define('PROC_CANT_GET_CONT_FROM_DB', 'Kan contact niet ophalen uit de database');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Kan contact(en) niet ophalen uit de database');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Kan account niet verwijderen');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Kan filter niet verwijderen');
define('PROC_CANT_DEL_CONT_GROUPS', 'Kan contact(en) of groep(en) niet verwijderen');
define('PROC_WRONG_ACCT_ACCESS', 'Een poging tot niet-geauthoriseerde toegang tot andermans account werd gedetecteerd.');
define('PROC_SESSION_ERROR', 'De vorige sessie is beëindigd wegens een timeout.');

define('MailBoxIsFull', 'Mailbox is vol');
define('WebMailException', 'WebMail fout gebeurd');
define('InvalidUid', 'Ongeldig Bericht UID');
define('CantCreateContactGroup', 'Kan contactgroep niet maken');
define('CantCreateUser', 'Kan gebruiker niet maken');
define('CantCreateAccount', 'Kan account niet maken');
define('SessionIsEmpty', 'Sessie is leeg');
define('FileIsTooBig', 'Het bestand is te groot');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Kan niet alle berichten als gelezen markeren');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Kan niet alle berichten als ongelezen markeren');
define('PROC_CANT_PURGE_MSGS', 'Kan bericht(en) niet definitief verwijderen');
define('PROC_CANT_DEL_MSGS', 'Kan bericht(en) niet verwijderen');
define('PROC_CANT_UNDEL_MSGS', 'Kan verwijderen niet ongedaan maken voor bericht(en)');
define('PROC_CANT_MARK_MSGS_READ', 'Kan bericht(en) niet als gelezen markeren');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Kan bericht(en) niet als ongelezen markeren');
define('PROC_CANT_SET_MSG_FLAGS', 'Kan berichten-vlag niet zetten');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Kan berichten-vlag niet verwijderen');
define('PROC_CANT_CHANGE_MSG_FLD', 'Kan map niet wijzigen');
define('PROC_CANT_SEND_MSG', 'Kan bericht niet verzenden.');
define('PROC_CANT_SAVE_MSG', 'Kan bericht niet opslaan.');
define('PROC_CANT_GET_ACCT_LIST', 'Kan accountlijst niet ophalen');
define('PROC_CANT_GET_FILTER_LIST', 'Kan filterlijst niet ophalen');

define('PROC_CANT_LEAVE_BLANK', 'Gelieve alle velden gemarkeerd met * in te vullen');

define('PROC_CANT_UPD_FLD', 'Kan map niet opslaan');
define('PROC_CANT_UPD_FILTER', 'Kan filter niet opslaan');

define('ACCT_CANT_ADD_DEF_ACCT', 'De account kan niet worden toegevoegd omdat hij gebruikt wordt als standaard account door een andere gebruiker.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Deze account kan niet als standaard gezet worden.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Kan nieuwe account niet maken (IMAP4 verbindingsfout)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Kan laatste default account niet verwijderen');

define('LANG_LoginInfo', 'Login Informatie');
define('LANG_Email', 'Email');
define('LANG_Login', 'Login');
define('LANG_Password', 'Paswoord');
define('LANG_IncServer', 'Inkomende Mail');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'SMTP Server');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Gebruik SMTP authenticatie');
define('LANG_SignMe', 'Automatisch inloggen');
define('LANG_Enter', 'Enter');

define('JS_LANG_TitleLogin', 'Login');
define('JS_LANG_TitleMessagesListView', 'Berichtenlijst');
define('JS_LANG_TitleMessagesList', 'Berichtenlijst');
define('JS_LANG_TitleViewMessage', 'Bericht bekijken');
define('JS_LANG_TitleNewMessage', 'Nieuw bericht');
define('JS_LANG_TitleSettings', 'Instellingen');
define('JS_LANG_TitleContacts', 'Contacten');

define('JS_LANG_StandardLogin', 'Standaard&nbsp;Login');
define('JS_LANG_AdvancedLogin', 'Geavanceerde&nbsp;Login');

define('JS_LANG_InfoWebMailLoading', 'Even geduld, bezig met laden&hellip;');
define('JS_LANG_Loading', 'Bezig met laden&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Even geduld, bezig met ophalen van berichten');
define('JS_LANG_InfoEmptyFolder', 'De map is leeg');
define('JS_LANG_InfoPageLoading', 'De pagina is nog steeds aan het laden&hellip;');
define('JS_LANG_InfoSendMessage', 'Het bericht is verzonden');
define('JS_LANG_InfoSaveMessage', 'Het bericht is opgeslagen');
// You have imported 3 new contact(s) into your contacts list.
define('JS_LANG_InfoHaveImported', 'U hebt');
define('JS_LANG_InfoNewContacts', 'nieuwe contact(en) geïmporteerd.');
define('JS_LANG_InfoToDelete', 'Om de map');
define('JS_LANG_InfoDeleteContent', 'te verwijderen moet u eerst al zijn inhoud verwijderen.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Niet-lege mappen verwijderen is niet mogelijk. Gelieve eerst de inhoud van deze mappen te verwijderen.');
define('JS_LANG_InfoRequiredFields', '* verplichte velden');

define('JS_LANG_ConfirmAreYouSure', 'Bent u zeker?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'De geselecteerde bericht(en) zullen PERMANENT verwijderd worden. Bent u zeker?');
define('JS_LANG_ConfirmSaveSettings', 'De instellingen zijn niet opgeslagen. Klik OK om op te slaan.');
define('JS_LANG_ConfirmSaveContactsSettings', 'De contactinstellingen zijn niet opgeslagen. Klik OK om op te slaan.');
define('JS_LANG_ConfirmSaveAcctProp', 'De contact-instellingen zijn niet opgeslagen. Klik OK om op te slaan.');
define('JS_LANG_ConfirmSaveFilter', 'De filter-instellingen zijn niet opgeslagen. Klik OK om op te slaan.');
define('JS_LANG_ConfirmSaveSignature', 'Het onderschrift is niet opgeslagen. Klik OK om op te slaan.');
define('JS_LANG_ConfirmSavefolders', 'De mappen zijn niet opgeslagen. Klik OK om op te slaan.');
define('JS_LANG_ConfirmHtmlToPlain', 'Waarschuwing: Door het formaat van dit bericht te wijzigen van HTML naar plain text, zal u alle opmaak verliezen. Klik OK om verder te gaan.');
define('JS_LANG_ConfirmAddFolder', 'Voordat u deze map kunt toevoegen moet u eerst opslaan. Klik OK om op te slaan.');
define('JS_LANG_ConfirmEmptySubject', 'Het onderwerp-veld is leeg. Bent u zeker dat u wil verdergaan?');

define('JS_LANG_WarningEmailBlank', 'U kan het <br />Email: veld niet leeg laten');
define('JS_LANG_WarningLoginBlank', 'U kan het leave<br />Login: veld niet leeg laten');
define('JS_LANG_WarningToBlank', 'U kan het Aan: veld niet leeg laten');
define('JS_LANG_WarningServerPortBlank', 'U kan de POP3 en<br />SMTP server/poort velden niet leeg laten');
define('JS_LANG_WarningEmptySearchLine', 'Leeg zoekveld. Gelieve in te vullen wat u wilt zoeken');
define('JS_LANG_WarningMarkListItem', 'Gelieve minstens één item te markeren in de lijst');
define('JS_LANG_WarningFolderMove', 'De map kan niet verplaatst worden omdat dit een ander niveau is');
define('JS_LANG_WarningContactNotComplete', 'Gelieve email of naam in te vullen');
define('JS_LANG_WarningGroupNotComplete', 'Gelieve een groepnaam in te vullen');

define('JS_LANG_WarningEmailFieldBlank', 'U kan het Email veld niet leeg laten');
define('JS_LANG_WarningIncServerBlank', 'U kan het POP3(IMAP4) Server veld niet leeg laten');
define('JS_LANG_WarningIncPortBlank', 'U kan het POP3(IMAP4) Server Port veld niet leeg laten');
define('JS_LANG_WarningIncLoginBlank', 'U kan het POP3(IMAP4) Login veld niet leeg laten');
define('JS_LANG_WarningIncPortNumber', 'Gelieve een positief getal in het POP3(IMAP4) poort veld in te vullen.');
define('JS_LANG_DefaultIncPortNumber', 'Standaard POP3(IMAP4) poort nummer is 110(143).');
define('JS_LANG_WarningIncPassBlank', 'U kan het POP3(IMAP4) Paswoord veld niet leeg laten');
define('JS_LANG_WarningOutPortBlank', 'U kan het SMTP Server Port veld niet leeg laten');
define('JS_LANG_WarningOutPortNumber', 'Gelieve een positief getal in het SMTP poort veld in te vullen.');
define('JS_LANG_WarningCorrectEmail', 'Gelieve een correct emailadres in te vullen.');
define('JS_LANG_DefaultOutPortNumber', 'Standaard SMTP poort nummer is 25.');

define('JS_LANG_WarningCsvExtention', 'Extensie moet .csv zijn');
define('JS_LANG_WarningImportFileType', 'Gelieve de applicatie vanwaaruit u contacten wil importeren te selecteren');
define('JS_LANG_WarningEmptyImportFile', 'Gelieve een bestand te selecteren door op de knop \'Bladeren\' te klikken');

define('JS_LANG_WarningContactsPerPage', 'Aantal contacten per pagina moet positief zijn');
define('JS_LANG_WarningMessagesPerPage', 'Aantal berichten per pagina moet positief zijn');
define('JS_LANG_WarningMailsOnServerDays', 'U moet een positief getal invullen in \'Aantal berichten op server\' veld.');
define('JS_LANG_WarningEmptyFilter', 'Gelieve een substring in te vullen');
define('JS_LANG_WarningEmptyFolderName', 'Gelieve een mapnaam in te vullen');

define('JS_LANG_ErrorConnectionFailed', 'Verbinding mislukt');
define('JS_LANG_ErrorRequestFailed', 'De dataoverdracht is niet voltooid');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Het object XMLHttpRequest bestaat niet');
define('JS_LANG_ErrorWithoutDesc', 'Een onbekende fout is gebeurd.');
define('JS_LANG_ErrorParsing', 'Fout bij het interpreteren van de XML.');
define('JS_LANG_ResponseText', 'Antwoordtekst:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Leeg XML pakket');
define('JS_LANG_ErrorImportContacts', 'Fout bij het importeren van contacten');
define('JS_LANG_ErrorNoContacts', 'Geen contacten gevonden om te importeren.');
define('JS_LANG_ErrorCheckMail', 'Het ophalen van berichten is afgebroken wegens een fout. Waarschijnlijk zijn niet alle berichten ontvangen.');

define('JS_LANG_LoggingToServer', 'Bezig met inloggen op de server&hellip;');
define('JS_LANG_GettingMsgsNum', 'Ophalen van aantal berichten');
define('JS_LANG_RetrievingMessage', 'Bericht ophalen');
define('JS_LANG_DeletingMessage', 'Bericht verwijderen');
define('JS_LANG_DeletingMessages', 'Bericht(en) verwijderen');
define('JS_LANG_Of', 'van');
define('JS_LANG_Connection', 'Verbinding');
define('JS_LANG_Charset', 'Karakterset');
define('JS_LANG_AutoSelect', 'Auto-selectie');

define('JS_LANG_Contacts', 'Contacten');
define('JS_LANG_ClassicVersion', 'Klassieke versie');
define('JS_LANG_Logout', 'Afmelden');
define('JS_LANG_Settings', 'Instellingen');

define('JS_LANG_LookFor', 'Zoeken naar');
define('JS_LANG_SearchIn', 'Zoeken in');
define('JS_LANG_QuickSearch', 'Zoeken in Van, Naar en Onderwerp velden alleen (sneller).');
define('JS_LANG_SlowSearch', 'Zoeken in volledig bericht');
define('JS_LANG_AllMailFolders', 'Alle mailmappen');
define('JS_LANG_AllGroups', 'Alle groepen');

define('JS_LANG_NewMessage', 'Nieuw bericht');
define('JS_LANG_CheckMail', 'Mail checken');
define('JS_LANG_EmptyTrash', 'Prullenbak leegmaken');
define('JS_LANG_MarkAsRead', 'Als gelezen markeren');
define('JS_LANG_MarkAsUnread', 'Als ongelezen markeren');
define('JS_LANG_MarkFlag', 'Vlaggen');
define('JS_LANG_MarkUnflag', 'Uitvlaggen');
define('JS_LANG_MarkAllRead', 'Allemaal als gelezen markeren');
define('JS_LANG_MarkAllUnread', 'Allemaal als ongelezen markeren');
define('JS_LANG_Reply', 'Antwoorden');
define('JS_LANG_ReplyAll', 'Allen antwoorden');
define('JS_LANG_Delete', 'Verwijderen');
define('JS_LANG_Undelete', 'Verwijderen ongedaan maken');
define('JS_LANG_PurgeDeleted', 'Definitief verwijderen');
define('JS_LANG_MoveToFolder', 'Verplaatsen');
define('JS_LANG_Forward', 'Doorsturen');

define('JS_LANG_HideFolders', 'Mappen verbergen');
define('JS_LANG_ShowFolders', 'Mappen weergeven');
define('JS_LANG_ManageFolders', 'Mappen beheren');
define('JS_LANG_SyncFolder', 'Gesynchroniseerde map');
define('JS_LANG_NewMessages', 'Nieuwe berichten');
define('JS_LANG_Messages', 'Bericht(en)');

define('JS_LANG_From', 'Van');
define('JS_LANG_To', 'Aan');
define('JS_LANG_Date', 'Datum');
define('JS_LANG_Size', 'Grootte');
define('JS_LANG_Subject', 'Onderwerp');

define('JS_LANG_FirstPage', 'Eerste pagina');
define('JS_LANG_PreviousPage', 'Vorige pagina');
define('JS_LANG_NextPage', 'Volgende pagina');
define('JS_LANG_LastPage', 'Laatste pagina');

define('JS_LANG_SwitchToPlain', 'Naar plain text overschakelen');
define('JS_LANG_SwitchToHTML', 'Naar HTML overschakelen');
define('JS_LANG_AddToAddressBook', 'Toevoegen aan adresboek');
define('JS_LANG_ClickToDownload', 'Klik om te downloaden');
define('JS_LANG_View', 'Weergeven');
define('JS_LANG_ShowFullHeaders', 'Volledige headers tonen');
define('JS_LANG_HideFullHeaders', 'Volledige headers verbergen');

define('JS_LANG_MessagesInFolder', 'Bericht(en) in map');
define('JS_LANG_YouUsing', 'U gebruikt');
define('JS_LANG_OfYour', 'van uw');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Verzenden');
define('JS_LANG_SaveMessage', 'Opslaan');
define('JS_LANG_Print', 'Afdrukken');
define('JS_LANG_PreviousMsg', 'Vorig bericht');
define('JS_LANG_NextMsg', 'Volgend bericht');
define('JS_LANG_AddressBook', 'Adresboek');
define('JS_LANG_ShowBCC', 'BCC weergeven');
define('JS_LANG_HideBCC', 'BCC verbergen');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Antwoorden aan');
define('JS_LANG_AttachFile', 'Bestand koppelen');
define('JS_LANG_Attach', 'Koppelen');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Origineel bericht');
define('JS_LANG_Sent', 'Verzonden');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Laag');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Hoog');
define('JS_LANG_Importance', 'Prioriteit');
define('JS_LANG_Close', 'Sluiten');

define('JS_LANG_Common', 'Gemeenschappelijk');
define('JS_LANG_EmailAccounts', 'Email Accounts');

define('JS_LANG_MsgsPerPage', 'Berichten per pagina');
define('JS_LANG_DisableRTE', 'Uitgebreide editor uitschakelen');
define('JS_LANG_Skin', 'Skin');
define('JS_LANG_DefCharset', 'Standaard karakterset');
define('JS_LANG_DefCharsetInc', 'Standaard inkomende karakterset');
define('JS_LANG_DefCharsetOut', 'Standaard uitgaande karakterset');
define('JS_LANG_DefTimeOffset', 'Standaard tijdzone');
define('JS_LANG_DefLanguage', 'Standaard taal');
define('JS_LANG_DefDateFormat', 'Standaard datumformaat');
define('JS_LANG_ShowViewPane', 'Berichtenlijst met voorbeeldweergave');
define('JS_LANG_Save', 'Opslaan');
define('JS_LANG_Cancel', 'Annuleren');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Verwijderen');
define('JS_LANG_AddNewAccount', 'Nieuwe account toevoegen');
define('JS_LANG_Signature', 'Onderschrift');
define('JS_LANG_Filters', 'Filters');
define('JS_LANG_Properties', 'Eigenschappen');
define('JS_LANG_UseForLogin', 'Gebruik deze accounteigenschappen (login en paswoord) om in te loggen');
define('JS_LANG_MailFriendlyName', 'Uw naam');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Inkomende mail');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Poort');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Paswoord');
define('JS_LANG_MailOutHost', 'SMTP Server');
define('JS_LANG_MailOutPort', 'Poort');
define('JS_LANG_MailOutLogin', 'SMTP Login');
define('JS_LANG_MailOutPass', 'SMTP Paswoord');
define('JS_LANG_MailOutAuth1', 'Gebruik SMTP authenticatie');
define('JS_LANG_MailOutAuth2', '(U mag SMTP login/paswoord velden leeg laten als ze hetzelfde zijn als de POP3/IMAP login/paswoorden)');
define('JS_LANG_UseFriendlyNm1', 'Gebruik Friendly Name in "Van:" veld');
define('JS_LANG_UseFriendlyNm2', '(Uw naam &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Mails ophalen bij login');
define('JS_LANG_MailMode0', 'Ontvangen berichten verwijderen van server');
define('JS_LANG_MailMode1', 'Berichten op server laten staan');
define('JS_LANG_MailMode2', 'Berichten op server laten staan voor');
define('JS_LANG_MailsOnServerDays', 'dag(en)');
define('JS_LANG_MailMode3', 'Bericht verwijderen op server als het uit de prullenbak verwijderd wordt');
define('JS_LANG_InboxSyncType', 'Type van synchronisatie');

define('JS_LANG_SyncTypeNo', 'Niet synchroniseren');
define('JS_LANG_SyncTypeNewHeaders', 'Nieuwe headers');
define('JS_LANG_SyncTypeAllHeaders', 'Alle headers');
define('JS_LANG_SyncTypeNewMessages', 'Nieuwe berichten');
define('JS_LANG_SyncTypeAllMessages', 'Alle berichten');
define('JS_LANG_SyncTypeDirectMode', 'Direct');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Alleen headers');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Volledige berichten');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direct');

define('JS_LANG_DeleteFromDb', 'Berichten verwijderen van database als ze niet meer op de server bestaan');

define('JS_LANG_EditFilter', 'Filter bewerken');
define('JS_LANG_NewFilter', 'Nieuwe filter toevoegen');
define('JS_LANG_Field', 'Veld');
define('JS_LANG_Condition', 'Voorwaarde');
define('JS_LANG_ContainSubstring', 'Bevat substring');
define('JS_LANG_ContainExactPhrase', 'Bevat exact woord');
define('JS_LANG_NotContainSubstring', 'Bevat substring niet');
define('JS_LANG_FilterDesc_At', 'op');
define('JS_LANG_FilterDesc_Field', 'veld');
define('JS_LANG_Action', 'Actie');
define('JS_LANG_DoNothing', 'Doe niets');
define('JS_LANG_DeleteFromServer', 'Verwijder onmiddellijk van server');
define('JS_LANG_MarkGrey', 'Markeer grijs');
define('JS_LANG_Add', 'Toevoegen');
define('JS_LANG_OtherFilterSettings', 'Andere filterinstellingen');
define('JS_LANG_ConsiderXSpam', 'X-Spam headers bekijken');
define('JS_LANG_Apply', 'Toepassen');

define('JS_LANG_InsertLink', 'Link toevoegen');
define('JS_LANG_RemoveLink', 'Link verwijderen');
define('JS_LANG_Numbering', 'Nummeren');
define('JS_LANG_Bullets', 'Items');
define('JS_LANG_HorizontalLine', 'Horizontale lijn');
define('JS_LANG_Bold', 'Vet');
define('JS_LANG_Italic', 'Cursief');
define('JS_LANG_Underline', 'Onderlijnen');
define('JS_LANG_AlignLeft', 'Links uitlijnen');
define('JS_LANG_Center', 'Centreren');
define('JS_LANG_AlignRight', 'Rechts uitlijnen');
define('JS_LANG_Justify', 'Uitvullen');
define('JS_LANG_FontColor', 'Lettertype kleur');
define('JS_LANG_Background', 'Achtergrond');
define('JS_LANG_SwitchToPlainMode', 'Schakel over naar Plain Text Mode');
define('JS_LANG_SwitchToHTMLMode', 'Schakel over naar HTML Mode');

define('JS_LANG_Folder', 'Map');
define('JS_LANG_Msgs', 'Berichten');
define('JS_LANG_Synchronize', 'Synchronizeren');
define('JS_LANG_ShowThisFolder', 'Toon deze map');
define('JS_LANG_Total', 'Totaal');
define('JS_LANG_DeleteSelected', 'Verwijder geselecteerde');
define('JS_LANG_AddNewFolder', 'Nieuwe map toevoegen');
define('JS_LANG_NewFolder', 'Nieuwe map');
define('JS_LANG_ParentFolder', 'Hoofdmap');
define('JS_LANG_NoParent', 'Geen hoofdmap');
define('JS_LANG_FolderName', 'Mapnaam');

define('JS_LANG_ContactsPerPage', 'Contacten per pagina');
define('JS_LANG_WhiteList', 'Addresboek vanuit een lege lijst');

define('JS_LANG_CharsetDefault', 'Standaard');
define('JS_LANG_CharsetArabicAlphabetISO', 'Arabisch Alfabet (ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Arabisch Alfabet (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Baltisch Alfabet (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Baltisch Alfabet (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Centraal Europees Alfabet (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Centraal Europees Alfabet (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Chinese Simplified (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Chinese Simplified (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Chinees Traditioneel');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Cyrillisch Alfabet (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Cyrillisch Alfabet (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Cyrillisch Alfabet (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Grieks Alfabet (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Grieks Alfabet (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Hebreeuws Alfabet (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'Hebreeuws Alfabet (Windows)');
define('JS_LANG_CharsetJapanese', 'Japans');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japans (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Koreaans (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Koreaans (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Latin 3 Alphabet (ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Turks Alfabet');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Universeel Alfabet (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Universeel Alfabet (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Vietnamees Alfabet (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Westers Alfabet (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Westers Alfabet (Windows)');

define('JS_LANG_TimeDefault', 'Standaard');
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

define('JS_LANG_DateDefault', 'Standaard');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Jan)');
define('JS_LANG_DateAdvanced', 'Geavanceerd');

define('JS_LANG_NewContact', 'Nieuw contact');
define('JS_LANG_NewGroup', 'Nieuwe groep');
define('JS_LANG_AddContactsTo', 'Contacten toevoegen aan');
define('JS_LANG_ImportContacts', 'Contacten importeren');

define('JS_LANG_Name', 'Naam');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Standaard Email');
define('JS_LANG_NotSpecifiedYet', 'Nog niet aangepast');
define('JS_LANG_ContactName', 'Naam');
define('JS_LANG_Birthday', 'Verjaardag');
define('JS_LANG_Month', 'Maand');
define('JS_LANG_January', 'Januari');
define('JS_LANG_February', 'Februari');
define('JS_LANG_March', 'Maart');
define('JS_LANG_April', 'April');
define('JS_LANG_May', 'Mei');
define('JS_LANG_June', 'Juni');
define('JS_LANG_July', 'Juli');
define('JS_LANG_August', 'Augustus');
define('JS_LANG_September', 'September');
define('JS_LANG_October', 'Oktober');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'December');
define('JS_LANG_Day', 'Dag');
define('JS_LANG_Year', 'Jaar');
define('JS_LANG_UseFriendlyName1', 'Gebruik aangepaste naam');
define('JS_LANG_UseFriendlyName2', '(bv, John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Persoonlijk');
define('JS_LANG_PersonalEmail', 'Persoonlijke e-mail');
define('JS_LANG_StreetAddress', 'Straat');
define('JS_LANG_City', 'Stad');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Provincie');
define('JS_LANG_Phone', 'Telefoon');
define('JS_LANG_ZipCode', 'Postcode');
define('JS_LANG_Mobile', 'Mobiel');
define('JS_LANG_CountryRegion', 'Land/Regio');
define('JS_LANG_WebPage', 'Webpagina');
define('JS_LANG_Go', 'Ga');
define('JS_LANG_Home', 'Home');
define('JS_LANG_Business', 'Werk');
define('JS_LANG_BusinessEmail', 'Werk e-mail');
define('JS_LANG_Company', 'Bedrijf');
define('JS_LANG_JobTitle', 'Job Titel');
define('JS_LANG_Department', 'Afdeling');
define('JS_LANG_Office', 'Kantoor');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Ander');
define('JS_LANG_OtherEmail', 'Ander e-mail');
define('JS_LANG_Notes', 'Notities');
define('JS_LANG_Groups', 'Groepen');
define('JS_LANG_ShowAddFields', 'Toon extra velden');
define('JS_LANG_HideAddFields', 'Verberg extra velden');
define('JS_LANG_EditContact', 'Wijzig contact informatie');
define('JS_LANG_GroupName', 'Groepsnaam');
define('JS_LANG_AddContacts', 'Contacten toevoegen');
define('JS_LANG_CommentAddContacts', '(Bij meerdere adressen, gelieve deze te scheiden met een komma)');
define('JS_LANG_CreateGroup', 'Groep aanmaken');
define('JS_LANG_Rename', 'Hernoemen');
define('JS_LANG_MailGroup', 'Mailgroep');
define('JS_LANG_RemoveFromGroup', 'Uit groep verwijderen');
define('JS_LANG_UseImportTo', 'Gebruik importeren om je contacten van Microsoft Outlook en Microsoft Outlook Express in je webmail contactenlijst te importeren.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Selecteer het bestand (.CSV formaat) dat je wenst te importeren');
define('JS_LANG_Import', 'Importeer');
define('JS_LANG_ContactsMessage', 'Dit is de contactenpagina!!!');
define('JS_LANG_ContactsCount', 'contact(en)');
define('JS_LANG_GroupsCount', 'groep(en)');

// webmail 4.1 constants
define('PicturesBlocked', 'Afbeeldingen in dit bericht werden voor uw veiligheid geblokkeerd.');
define('ShowPictures', 'Toon afbeeldingen');
define('ShowPicturesFromSender', 'Toon altijd afbeeldingen in berichten van deze afzender');
define('AlwaysShowPictures', 'Toon altijd afbeeldingen in berichten');

define('TreatAsOrganization', 'Behandel als een bedrijf');

define('WarningGroupAlreadyExist', 'Groep met deze naam bestaat al. Gelieve de naam aan te passen.');
define('WarningCorrectFolderName', 'Kies een correcte mapnaam.');
define('WarningLoginFieldBlank', 'Login veld kan niet leeg zijn.');
define('WarningCorrectLogin', 'Kies een correcte login.');
define('WarningPassBlank', 'Paswoord veld kan niet leeg zijn.');
define('WarningCorrectIncServer', 'Kies een correct POP3(IMAP) server adres.');
define('WarningCorrectSMTPServer', 'Kies een correct SMTP server adres.');
define('WarningFromBlank', 'Vanm veld kan niet leeg zijn.');
define('WarningAdvancedDateFormat', 'Kies een datum-tijd formaat.');

define('AdvancedDateHelpTitle', 'Geavanceerde datum');
define('AdvancedDateHelpIntro', 'Wanneer het veld &quot;geavanceerd&quot; geselecteerd is, kan je de textbox gebruiken om je eigen datumformaat te kiezen, dat zal getoond worden in de webmail. De volgende opties worden gebruikt \':\' of \'/\' delimiter char:');
define('AdvancedDateHelpConclusion', 'Bv, als je kiest voor &quot;mm/dd/yyyy&quot; in de textbox &quot;geavanceerd&quot;, dan wordt de datum weergegeven als maand/dag/jaar (bv. 14/07/2007)');
define('AdvancedDateHelpDayOfMonth', 'Dag (van 1 tot 31)');
define('AdvancedDateHelpNumericMonth', 'Maand (1 tot 12)');
define('AdvancedDateHelpTextualMonth', 'Maand (Jan tot Dec)');
define('AdvancedDateHelpYear2', 'Jaar, 2 karakters');
define('AdvancedDateHelpYear4', 'Jaar, 4 karakters');
define('AdvancedDateHelpDayOfYear', 'Dag (1 tot 366)');
define('AdvancedDateHelpQuarter', 'Seizoen');
define('AdvancedDateHelpDayOfWeek', 'Weekdag (Maandag tot Zondag)');
define('AdvancedDateHelpWeekOfYear', 'Weeknr (1 tot 53)');

define('InfoNoMessagesFound', 'Geen berichten gevonden.');
define('ErrorSMTPConnect', 'Kan niet verbinden met de SMTP server. Controleer de SMTP server instellingen.');
define('ErrorSMTPAuth', 'Verkeerde gebruikersnaam en/of paswoord. Aanmelden mislukt.');
define('ReportMessageSent', 'Uw bericht is verzonden.');
define('ReportMessageSaved', 'Uw bericht werd opgeslagen.');
define('ErrorPOP3Connect', 'Kan niet verbinden met de POP3 server, Controleer de POP3 server instellingen.');
define('ErrorIMAP4Connect', 'Kan niet verbinden met de IMAP4 server, Controleer de IMAP4 server instellingen.');
define('ErrorPOP3IMAP4Auth', 'Verkeerde email/login en/of paswoord. Aanmelden mislukt.');
define('ErrorGetMailLimit', 'Sorry, de limiet van uw mailbox werd bereikt.');

define('ReportSettingsUpdatedSuccessfuly', 'Instellingen zijn met succes bewaard.');
define('ReportAccountCreatedSuccessfuly', 'Account is met succes aangemaakt.');
define('ReportAccountUpdatedSuccessfuly', 'Account is met succes aangepast.');
define('ConfirmDeleteAccount', 'Ben je zeker dat je deze account wil verwijderen?');
define('ReportFiltersUpdatedSuccessfuly', 'Filters werden met succes aangepast.');
define('ReportSignatureUpdatedSuccessfuly', 'Handtekening is met succes aangepast.');
define('ReportFoldersUpdatedSuccessfuly', 'Mappen zijn met succes aangepast.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Contacten\' zijn met succes aangepast.');

define('ErrorInvalidCSV', 'CSV bestand dat je selecteerde heeft een verkeerde indeling.');
// The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'De groep');
define('ReportGroupSuccessfulyAdded2', 'met succes toegevoegd.');
define('ReportGroupUpdatedSuccessfuly', 'Groep is met succes aangepast.');
define('ReportContactSuccessfulyAdded', 'Contact is met succes aangepast.');
define('ReportContactUpdatedSuccessfuly', 'Contact is met succes aangepast.');
// Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Contact(s) werd toegevoegd aan de groep');
define('AlertNoContactsGroupsSelected', 'Geen contacten of groepen geselecteerd.');

define('InfoListNotContainAddress', 'Als deze lijst niet het gezochte adres bevat, typ dan de eerste karakters.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Directe modus. Webmail benadert de berichten rechtstreeks op de mailserver.');

define('FolderInbox', 'Inbox');
define('FolderSentItems', 'Verzonden berichten');
define('FolderDrafts', 'Concepten');
define('FolderTrash', 'Prullenbak');

define('FileLargerAttachment', 'De bestandsgrootte is hoger dan toegelaten.');
define('FilePartiallyUploaded', 'Enkel een gedeelte van het bestand werd geuploaded, te wijten aan een onbekende fout.');
define('NoFileUploaded', 'Geen bestand geuploaded.');
define('MissingTempFolder', 'De tijdelijke map is onbestaande.');
define('MissingTempFile', 'Het tijdelijk bestand is onbestaande.');
define('UnknownUploadError', 'Een onbekende fout is opgetreden.');
define('FileLargerThan', 'Fout bij het uploaden. Waarschijnlijk is het bestand groter dan ');
define('PROC_CANT_LOAD_DB', 'Kan niet verbinden met de database.');
define('PROC_CANT_LOAD_LANG', 'Kan het gevraagde taalbestand niet vinden.');
define('PROC_CANT_LOAD_ACCT', 'Deze account bestaat niet, misschien werd ze juist verwijderd.');

define('DomainDosntExist', 'Dit domain bestaat niet op de mailserver.');
define('ServerIsDisable', 'Deze mailserver gebruiken werd verboden door de beheerder.');

define('PROC_ACCOUNT_EXISTS', 'Deze account kan niet worden aangemaakt omdat het al bestaat.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Kan het aantal berichten niet ophalen.');
define('PROC_CANT_MAIL_SIZE', 'Kan bestandsgrootte niet ophalen.');

define('Organization', 'Bedrijf');
define('WarningOutServerBlank', 'Het veld SMTP Server kan niet leeg zijn');

//
define('JS_LANG_Refresh', 'Vernieuwen');
define('JS_LANG_MessagesInInbox', 'Bericht(en) in Postvak IN');
define('JS_LANG_InfoEmptyInbox', 'Postvak IN is leeg');

// webmail 4.2 constants
define('BackToList', 'Terug naar lijst');
define('InfoNoContactsGroups', 'Geen contacten of groepen.');
define('InfoNewContactsGroups', 'U kan nieuwe contacten of groepen aanmaken, of u kan contacten importeren uit een .CSV bestand of in MS Outlook formaat.');
define('DefTimeFormat', 'Standaard tijdsformaat');
define('SpellNoSuggestions', 'Geen suggesties');
define('SpellWait', 'Even geduld&hellip;');

define('InfoNoMessageSelected', 'Geen berichten geselecteerd.');
define('InfoSingleDoubleClick', 'U kan klikken op een bericht in de lijst om het voorbeeld te bekijken, of dubbelklikken om de volledige versie te bekijken.');

// calendar
define('TitleDay', 'Dagweergave');
define('TitleWeek', 'Weekweergave');
define('TitleMonth', 'Maandweergave');

define('ErrorNotSupportBrowser', 'AfterLogic Calendar ondersteunt uw browser niet. Gelieve over te schakelen naar FireFox 2.0 of nieuwer, Opera 9.0 of nieuwer, Internet Explorer 6.0 of nieuwer, Safari 3.0.2 of nieuwer.');
define('ErrorTurnedOffActiveX', 'ActiveX is uitgeschakeld. <br/>Gelieve dit in te schakelen om deze toepassing te gebruiken.');

define('Calendar', 'Kalender');

define('TabDay', 'Dag');
define('TabWeek', 'Week');
define('TabMonth', 'Maand');

define('ToolNewEvent', 'Nieuwe&nbsp;gebeurtenis');
define('ToolBack', 'Terug');
define('ToolToday', 'Vandaag');
define('AltNewEvent', 'Nieuwe gebeurtenis');
define('AltBack', 'Terug');
define('AltToday', 'Vandaag');
define('CalendarHeader', 'Agenda');
define('CalendarsManager', 'Agendabeheer');

define('CalendarActionNew', 'Nieuwe agenda');
define('EventHeaderNew', 'Nieuwe gebeurtenis');
define('CalendarHeaderNew', 'Nieuwe agenda');

define('EventSubject', 'Onderwerp');
define('EventCalendar', 'Agenda');
define('EventFrom', 'Van');
define('EventTill', 'tot');
define('CalendarDescription', 'Beschrijving');
define('CalendarColor', 'Kleur');
define('CalendarName', 'Agendanaam');
define('CalendarDefaultName', 'Mijn agenda');

define('ButtonSave', 'Opslaan');
define('ButtonCancel', 'Annuleren');
define('ButtonDelete', 'Verwijderen');

define('AltPrevMonth', 'Vorige maand');
define('AltNextMonth', 'Volgende maand');

define('CalendarHeaderEdit', 'Agenda aanpassen');
define('CalendarActionEdit', 'Agenda aanpassen');
define('ConfirmDeleteCalendar', 'Bent u zeker dat u deze agenda wil verwijderen');
define('InfoDeleting', 'Bezig met verwijderen&hellip;');
define('WarningCalendarNameBlank', 'U kan de agendanaam niet leeg laten.');
define('ErrorCalendarNotCreated', 'Agenda niet gemaakt.');
define('WarningSubjectBlank', 'U kan het onderwerp niet leeg laten.');
define('WarningIncorrectTime', 'Het uur bevat sommige ongeldige karakters.');
define('WarningIncorrectFromTime', 'De starttijd is ongeldig.');
define('WarningIncorrectTillTime', 'De eindtijd is ongeldig.');
define('WarningStartEndDate', 'De einddatum moet groter dan of gelijk zijn aan de startdatum.');
define('WarningStartEndTime', 'De eindtijd moet groter dan of gelijk zijn aan de starttijd.');
define('WarningIncorrectDate', 'De datum moet correct zijn.');
define('InfoLoading', 'Bezig met laden&hellip;');
define('EventCreate', 'Maak gebeurtenis');
define('CalendarHideOther', 'Verberg andere agenda\'s');
define('CalendarShowOther', 'Toon andere agenda\'s');
define('CalendarRemove', 'Verwijder agenda');
define('EventHeaderEdit', 'Gebeurtenis aanpassen');

define('InfoSaving', 'Bezig met opslaan&hellip;');
define('SettingsDisplayName', 'Weergavenaam');
define('SettingsTimeFormat', 'Tijdsformaat');
define('SettingsDateFormat', 'Datumformaat');
define('SettingsShowWeekends', 'Weekends tonen');
define('SettingsWorkdayStarts', 'Werkdag start op');
define('SettingsWorkdayEnds', 'eindigt op');
define('SettingsShowWorkday', 'Werkdagen tonen');
define('SettingsWeekStartsOn', 'Week start op');
define('SettingsDefaultTab', 'Standaard-tab');
define('SettingsCountry', 'Land');
define('SettingsTimeZone', 'Tijdzone');
define('SettingsAllTimeZones', 'Alle tijdzones');

define('WarningWorkdayStartsEnds', 'De \'Werkdag eindigt op\' tijd moet groter zijn dan de \'Werkdag start op\' tijd');
define('ReportSettingsUpdated', 'Instellingen succesvol opgeslagen');

define('SettingsTabCalendar', 'Agenda');

define('FullMonthJanuary', 'Januari');
define('FullMonthFebruary', 'Februari');
define('FullMonthMarch', 'Maart');
define('FullMonthApril', 'April');
define('FullMonthMay', 'Mei');
define('FullMonthJune', 'Juni');
define('FullMonthJuly', 'Juli');
define('FullMonthAugust', 'Augustus');
define('FullMonthSeptember', 'September');
define('FullMonthOctober', 'Oktober');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'December');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Maa');
define('ShortMonthApril', 'Apr');
define('ShortMonthMay', 'Mei');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Aug');
define('ShortMonthSeptember', 'Sep');
define('ShortMonthOctober', 'Okt');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dec');

define('FullDayMonday', 'Maandag');
define('FullDayTuesday', 'Dinsdag');
define('FullDayWednesday', 'Woensday');
define('FullDayThursday', 'Donderday');
define('FullDayFriday', 'Vrijday');
define('FullDaySaturday', 'Zaterdag');
define('FullDaySunday', 'Zondag');

define('DayToolMonday', 'Maa');
define('DayToolTuesday', 'Din');
define('DayToolWednesday', 'Woe');
define('DayToolThursday', 'Don');
define('DayToolFriday', 'Vri');
define('DayToolSaturday', 'Zat');
define('DayToolSunday', 'Zon');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'D');
define('CalendarTableDayWednesday', 'W');
define('CalendarTableDayThursday', 'D');
define('CalendarTableDayFriday', 'V');
define('CalendarTableDaySaturday', 'Z');
define('CalendarTableDaySunday', 'Z');

define('ErrorParseJSON', 'Het JSON antwoord van de server kan niet gelezen worden.');

define('ErrorLoadCalendar', 'Kan agenda niet laden');
define('ErrorLoadEvents', 'Kan gebeurtenissen niet laden');
define('ErrorUpdateEvent', 'Kan gebeurtenis niet opslaan');
define('ErrorDeleteEvent', 'Kan gebeurtenis niet verwijderen');
define('ErrorUpdateCalendar', 'Kan agenda niet opslaan');
define('ErrorDeleteCalendar', 'Kan agenda niet verwijderen');
define('ErrorGeneral', 'Er is een fout gebeurd op de server. Probeer het later opnieuw.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Agenda delen en publiceren');
define('ShareActionEdit', 'Agenda delen en publiceren');
define('CalendarPublicate', 'Publiceer deze agenda op internet');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Deel deze agenda');
define('SharePermission1', 'Wijzigingen maken aan items en agenda delen beheren');
define('SharePermission2', 'Wijzigingen maken aan items');
define('SharePermission3', 'Items bekijken');
define('SharePermission4', 'Enkel vrij/bezet bekijken (details verbergen)');
define('ButtonClose', 'Sluiten');
define('WarningEmailFieldFilling', 'Gelieve eerst e-mail in te vullen');
define('EventHeaderView', 'Bekijk item');
define('ErrorUpdateSharing', 'Kan instellingen voor delen en publiceren niet opslaan');
define('ErrorUpdateSharing1', 'Kan agenda niet delen met gebruiker %s omdat deze niet bestaat');
define('ErrorUpdateSharing2', 'Kan agenda niet delen met gebruiker %s ');
define('ErrorUpdateSharing3', 'Deze agenda is al gedeeld met gebruiker %s');
define('Title_MyCalendars', 'Mijn agenda\'s');
define('Title_SharedCalendars', 'Gedeelde agenda\'s');
define('ErrorGetPublicationHash', 'Kan publicatie-link niet maken');
define('ErrorGetSharing', 'Kan niet delen');
define('CalendarPublishedTitle', 'Deze agenda is gedeeld');
define('RefreshSharedCalendars', 'Vernieuwen Gedeelde Agenda\'s');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Leden');

define('ReportMessagePartDisplayed', 'Merk op dat slechts een deel van het bericht weergegeven is');
define('ReportViewEntireMessage', 'Om het hele bericht te zien,');
define('ReportClickHere', 'klik hier');
define('ErrorContactExists', 'Een contact met deze naam en e-mail bestaat reeds.');

define('Attachments', 'Attachments');

define('InfoGroupsOfContact', 'De groepen waarvan het contact reeds lid is, zijn aangevinkt.');
define('AlertNoContactsSelected', 'Geen contacten geselecteerd.');
define('MailSelected', 'Mail geselecteerd adres');
define('CaptionSubscribed', 'Geabonneerd');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Geen spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Mail contactpersoon');
define('ContactViewAllMails', 'Alle mails van deze contactpersoon bekijken');
define('ContactsMailThem', 'Mail contactpersonen');
define('DateToday', 'Vandaag');
define('DateYesterday', 'Gisteren');
define('MessageShowDetails', 'Meer details');
define('MessageHideDetails', 'Verberg details');
define('MessageNoSubject', 'Geen onderwerp');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'aan');
define('SearchClear', 'Zoektermen wissen');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Zoekresultaten voor "#s" in map #f:');
define('SearchResultsInAllFolders', 'Zoekresultaten voor "#s" in alle mappen:');
define('AutoresponderTitle', 'Bericht bij afwezigheid');
define('AutoresponderEnable', 'Bericht bij afwezigheid instellen');
define('AutoresponderSubject', 'Onderwerp');
define('AutoresponderMessage', 'Bericht');
define('ReportAutoresponderUpdatedSuccessfuly', 'Bericht succesvol ingesteld.');
define('FolderQuarantine', 'Quarantaine');

//calendar
define('EventRepeats', 'Terugkerend');
define('NoRepeats', 'Niet terugkerend');
define('DailyRepeats', 'Dagelijks');
define('WorkdayRepeats', 'Elke weekdag (Ma. - Vr.)');
define('OddDayRepeats', 'Elke Ma., Wo. and Vr.');
define('EvenDayRepeats', 'Elke Di. and Do.');
define('WeeklyRepeats', 'Wekelijks');
define('MonthlyRepeats', 'Maandelijks');
define('YearlyRepeats', 'Jaarlijks');
define('RepeatsEvery', 'Keert terug elke');
define('ThisInstance', 'Enkel deze gebeurtenis');
define('AllEvents', 'Alle gebeurtenissen');
define('AllFollowing', 'Alle toekomstige gebeurtenissen');
define('ConfirmEditRepeatEvent', 'Bent u zeker dat u enkel deze gebeurtenis, alle gebeurtenissen of alle toekomstige gebeurtenissen in de reeks?');
define('RepeatEventHeaderEdit', 'Terugkerende gebeurtenis aanpassen');
define('First', 'Eerste');
define('Second', 'Tweede');
define('Third', 'Derde');
define('Fourth', 'Vierde');
define('Last', 'Laatste');
define('Every', 'Elke');
define('SetRepeatEventEnd', 'Einddatum');
define('NoEndRepeatEvent', 'Geen einddatum');
define('EndRepeatEventAfter', 'Einde na');
define('Occurrences', 'gebeurtenissen');
define('EndRepeatEventBy', 'Eindigen op');
define('EventCommonDataTab', 'Algemene details');
define('EventRepeatDataTab', 'Terugkeer-details');
define('RepeatEventNotPartOfASeries', 'Deze gebeurtenis is gewijzigd en maakt niet langer deel uit van een reeks.');
define('UndoRepeatExclusion', 'Opname in reeks ongedaan maken.');

define('MonthMoreLink', '%d meer...');
define('NoNewSharedCalendars', 'Geen nieuwe agenda\'s');
define('NNewSharedCalendars', '%d agenda\'s gevonden');
define('OneNewSharedCalendars', '1 nieuwe agenda gevonden');
define('ConfirmUndoOneRepeat', 'Wil u deze gebeurtenis in de reeks herstellen?');

define('RepeatEveryDayInfin', 'Elke dag');
define('RepeatEveryDayTimes', 'Elke dag, %TIMES% keer');
define('RepeatEveryDayUntil', 'Elke dag, tot %UNTIL%');
define('RepeatDaysInfin', 'Elke %PERIOD% dagen');
define('RepeatDaysTimes', 'Elke %PERIOD% dagen, %TIMES% keer');
define('RepeatDaysUntil', 'Elke %PERIOD% dagen, tot %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Elke week op weekdagen');
define('RepeatEveryWeekWeekdaysTimes', 'Elke week op weekdagen, %TIMES% keer');
define('RepeatEveryWeekWeekdaysUntil', 'Elke week op weekdagen, tot %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Elke %PERIOD% weken op weekdagen');
define('RepeatWeeksWeekdaysTimes', 'Elke %PERIOD% weken op weekdagen, %TIMES% keer');
define('RepeatWeeksWeekdaysUntil', 'Elke %PERIOD% weken op weekdagen, tot %UNTIL%');

define('RepeatEveryWeekInfin', 'Elke week on %DAYS%');
define('RepeatEveryWeekTimes', 'Elke week on %DAYS%, %TIMES% times');
define('RepeatEveryWeekUntil', 'Elke week on %DAYS%, tot %UNTIL%');
define('RepeatWeeksInfin', 'Elke %PERIOD% weken op %DAYS%');
define('RepeatWeeksTimes', 'Elke %PERIOD% weken op %DAYS%, %TIMES% keer');
define('RepeatWeeksUntil', 'Elke %PERIOD% weken op %DAYS%, tot %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Elke month op dag %DATE%');
define('RepeatEveryMonthDateTimes', 'Elke month op dag %DATE%, %TIMES% keer');
define('RepeatEveryMonthDateUntil', 'Elke month op dag %DATE%, tot %UNTIL%');
define('RepeatMonthsDateInfin', 'Elke %PERIOD% months op dag %DATE%');
define('RepeatMonthsDateTimes', 'Elke %PERIOD% months op dag %DATE%, %TIMES% keer');
define('RepeatMonthsDateUntil', 'Elke %PERIOD% months op dag %DATE%, tot %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Elke maand op %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Elke maand op %NUMBER% %DAY%, %TIMES% keer');
define('RepeatEveryMonthWDUntil', 'Elke maand op %NUMBER% %DAY%, tot %UNTIL%');
define('RepeatMonthsWDInfin', 'Elke %PERIOD% maanden op %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Elke %PERIOD% maanden op %NUMBER% %DAY%, %TIMES% keer');
define('RepeatMonthsWDUntil', 'Elke %PERIOD% maanden op %NUMBER% %DAY%, tot %UNTIL%');

define('RepeatEveryYearDateInfin', 'Elk jaar op dag %DATE%');
define('RepeatEveryYearDateTimes', 'Elk jaar op dag %DATE%, %TIMES% keer');
define('RepeatEveryYearDateUntil', 'Elk jaar op dag %DATE%, tot %UNTIL%');
define('RepeatYearsDateInfin', 'Elke %PERIOD% jaren op dag %DATE%');
define('RepeatYearsDateTimes', 'Elke %PERIOD% jaren op dag %DATE%, %TIMES% keer');
define('RepeatYearsDateUntil', 'Elke %PERIOD% jaren op dag %DATE%, tot %UNTIL%');

define('RepeatEveryYearWDInfin', 'Elk jaar op %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Elk jaar op %NUMBER% %DAY%, %TIMES% keer');
define('RepeatEveryYearWDUntil', 'Elk jaar op %NUMBER% %DAY%, tot %UNTIL%');
define('RepeatYearsWDInfin', 'Elke %PERIOD% jaren op %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Elke %PERIOD% jaren op %NUMBER% %DAY%, %TIMES% keer');
define('RepeatYearsWDUntil', 'Elke %PERIOD% jaren op %NUMBER% %DAY%, tot %UNTIL%');

define('RepeatDescDay', 'dag');
define('RepeatDescWeek', 'week');
define('RepeatDescMonth', 'maand');
define('RepeatDescYear', 'jaar');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Gelieve de einddatum aan te duiden');
define('WarningWrongUntilDate', 'Einddatum moet later zijn dan startdatum');

define('OnDays', 'Op dagen');
define('CancelRecurrence', 'Annuleer herhaalde gebeurtenis');
define('RepeatEvent', 'Deze gebeurtenis herhalen');

define('Spellcheck', 'Controge', 'Taals');
define('LoginLanguage', 'Language');
define('LanguageDefault', 'Standaard');

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
define('RequestReadConfirmation', 'Vraag leesbevestiging');
define('FolderTypeDefault', 'Standaard');
define('ShowFoldersMapping', 'Toestaan een andere map te gebruiken als systeem-map (b.v. gebruik MijnMap als Verzonden Items)');
define('ShowFoldersMappingNote', 'Bijvoorbeeld, om Verzonden items te wijzigen van Verzonden Items naar MijnMap, kies "Verzonden Items" in "Gebruik voor" van het menu van "MijnMap".');
define('FolderTypeMapTo', 'Gebruik voor');

define('ReminderEmailExplanation', 'Dit bericht is naar uw account %EMAIL% verzonden omdat u koos om verwittigd te worden in uw agenda: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Open agenda');

define('AddReminder', 'Herinner me voor deze gebeurtenis');
define('AddReminderBefore', 'Herinner me % voor deze gebeurtenis');
define('AddReminderAnd', 'en % voor');
define('AddReminderAlso', 'en ook % voor');
define('AddMoreReminder', 'Meer herinneringen');
define('RemoveAllReminders', 'Verwijder alle herinneringen');
define('ReminderNone', 'Geen');
define('ReminderMinutes', 'minuten');
define('ReminderHour', 'uur');
define('ReminderHours', 'uren');
define('ReminderDay', 'dag');
define('ReminderDays', 'dagen');
define('ReminderWeek', 'week');
define('ReminderWeeks', 'weken');
define('Allday', 'Hele dag');

define('Folders', 'Mappen');
define('NoSubject', 'Geen onderwerp');
define('SearchResultsFor', 'Zoekresultaten voor');

define('Back', 'Terug');
define('Next', 'Volgende');
define('Prev', 'Vorige');

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
