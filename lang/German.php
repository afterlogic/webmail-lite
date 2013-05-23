<?php
define('PROC_ERROR_ACCT_CREATE', 'Während der Kontoerstellung ist ein Fehler aufgetreten');
define('PROC_WRONG_ACCT_PWD', 'Falsches Kennwort');
define('PROC_CANT_LOG_NONDEF', 'Login in das Standard-Konto ist fehlgeschlagen');
define('PROC_CANT_INS_NEW_FILTER', 'Neuer Filter kann nicht hinzugefügt werden');
define('PROC_FOLDER_EXIST', 'Ordnername existiert bereits');
define('PROC_CANT_CREATE_FLD', 'Kann den Ordner nicht erstellen');
define('PROC_CANT_INS_NEW_GROUP', 'Kann die neue Gruppe nicht hinzufügen');
define('PROC_CANT_INS_NEW_CONT', 'Kann den Kontakt nicht hinzufügen');
define('PROC_CANT_INS_NEW_CONTS', 'Der Kontakt konnte nicht hinzugefügt werden');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Kann den Kontakt nicht in die Gruppe einfügen');
define('PROC_ERROR_ACCT_UPDATE', 'Während der Konto-Aklualisierung ist ein Fehler aufgetreten');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Kann die Kontakteinstellungen nicht aktualisieren');
define('PROC_CANT_GET_SETTINGS', 'Keine Einstellungen vorhanden');
define('PROC_CANT_UPDATE_ACCT', 'Kann das Konto nicht aktualisieren');
define('PROC_ERROR_DEL_FLD', 'Während der Ordner-Löschung ist ein Fehler aufgetreten');
define('PROC_CANT_UPDATE_CONT', 'Kann den Kontakt nicht aktualisieren');
define('PROC_CANT_GET_FLDS', 'Fehler beim Lesen des Kontaktverzeichnisses');
define('PROC_CANT_GET_MSG_LIST', 'Fehler beim Lesen der Nachrichten-Liste');
define('PROC_MSG_HAS_DELETED', 'Diese Nachricht wurde bereits vom Mail-Server gelöscht');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Fehler beim Laden der Kontakt-Einstellungen');
define('PROC_CANT_LOAD_SIGNATURE', 'Kann die Konto-Signatur nicht laden');
define('PROC_CANT_GET_CONT_FROM_DB', 'Fehler beim Auslesen des Kontakts aus der DB');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Fehler beim Auslesen der Kontakte aus der DB');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Konnte das Konto anhand der ID nicht löschen');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Konnte den Filter anhand der ID nicht löschen');
define('PROC_CANT_DEL_CONT_GROUPS', 'Der Kontakt oder die Gruppe konnte nicht gelöscht werden');
define('PROC_WRONG_ACCT_ACCESS', 'Ein unberechtigter Zugriffsversuch auf das Konto eines andern Benutzers festgestellt');
define('PROC_SESSION_ERROR', 'Die vorherige Sitzung wurde wegen Zeitüberschreitung beendet.');

define('MailBoxIsFull', 'Die Mailbox ist voll');
define('WebMailException', 'WebMail Fehler aufgetreten');
define('InvalidUid', 'Falsche Nachrichten UID');
define('CantCreateContactGroup', 'Kann Kontakt-Gruppe nicht erstellen');
define('CantCreateUser', 'Kann den User nicht erstellen');
define('CantCreateAccount', 'Kann das Konto nicht erstellen');
define('SessionIsEmpty', 'Session ist leer');
define('FileIsTooBig', 'Die Datei ist zu gross');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Es konnten nicht alle Nachrichten als gelesen markiert werden');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Es konnten nicht alle Nachrichten als ungelesen markiert werden');
define('PROC_CANT_PURGE_MSGS', 'Die Nachrichten konnten nicht abgeglichen werden');
define('PROC_CANT_DEL_MSGS', 'Konnte Nachricht/en nicht löschen');
define('PROC_CANT_UNDEL_MSGS', 'Konnte Nachricht/en nicht wiederherstellen');
define('PROC_CANT_MARK_MSGS_READ', 'Die Nachricht/en konnten nicht als gelesen markiert werden');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Die Nachricht/en konnten nicht als ungelesen markiert werden');
define('PROC_CANT_SET_MSG_FLAGS', 'Nachrichten-Flags konnten nicht gesetzt werden');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Nachrichten-Flags konnten nicht entfernt werden');
define('PROC_CANT_CHANGE_MSG_FLD', 'Nachrichtenordner konnte nicht geändert werden');
define('PROC_CANT_SEND_MSG', 'Nachricht konnte nicht gesendet werden');
define('PROC_CANT_SAVE_MSG', 'Nachricht konnte nicht gespeichert werden');
define('PROC_CANT_GET_ACCT_LIST', 'Kann Konto-Liste nicht laden');
define('PROC_CANT_GET_FILTER_LIST', 'Kann Filter-Liste nicht laden');

define('PROC_CANT_LEAVE_BLANK', 'Felder mit * müssen ausgefüllt sein');

define('PROC_CANT_UPD_FLD', 'Kann Ordner nicht aktualisieren');
define('PROC_CANT_UPD_FILTER', 'Kann Filter nicht aktualisieren');

define('ACCT_CANT_ADD_DEF_ACCT', 'Dieses Konto kann nicht hinzugefügt werden, da es als Standard Konto eines andern Users verwendet wird.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Dieser Konto-Status kann nicht auf Standard gesetzt werden');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Kann Konto nicht erstellen (IMAP4 Verbindungs Fehler)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Kann letztes Standard-Konto nicht löschen');

define('LANG_LoginInfo', 'Anmeldung');
define('LANG_Email', 'E-Mail-Adresse');
define('LANG_Login', 'Benutzername');
define('LANG_Password', 'Kennwort');
define('LANG_IncServer', 'Posteingangsserver Mail');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'SMTP&nbsp;Server');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', '&nbsp;SMTP&nbsp;Authentifizierung');
define('LANG_SignMe', 'In Zukunft automatisch anmelden');
define('LANG_Enter', 'Anmelden');

// interface strings

define('JS_LANG_TitleLogin', 'Anmelden');
define('JS_LANG_TitleMessagesListView', 'Nachrichten-Liste');
define('JS_LANG_TitleMessagesList', 'Nachrichten Liste');
define('JS_LANG_TitleViewMessage', 'Zeige Nachricht');
define('JS_LANG_TitleNewMessage', 'Neue Nachricht');
define('JS_LANG_TitleSettings', 'Einstellungen');
define('JS_LANG_TitleContacts', 'Kontakte');

define('JS_LANG_StandardLogin', 'Standard&nbsp;Login');
define('JS_LANG_AdvancedLogin', 'Erweitertes&nbsp;Login');

define('JS_LANG_InfoWebMailLoading', 'Bitte warten - laden&hellip;');
define('JS_LANG_Loading', 'Laden&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Bitte warten - Laden der Nachrichten-Liste');
define('JS_LANG_InfoEmptyFolder', 'Dieser Ordner ist leer');
define('JS_LANG_InfoPageLoading', 'Die Seite wird geladen&hellip;');
define('JS_LANG_InfoSendMessage', 'Die Nachricht wurde gesendet');
define('JS_LANG_InfoSaveMessage', 'Die Nachricht wurde gespeichert');
// You have imported 3 new contact(s) into your contacts list.
define('JS_LANG_InfoHaveImported', 'Sie haben importiert');
define('JS_LANG_InfoNewContacts', 'Neuer Kontakt in Ihrer Kontakt-Liste');
define('JS_LANG_InfoToDelete', 'Zum Löschen ');
define('JS_LANG_InfoDeleteContent', 'Ordner, dessen Inhalt Sie zuerst löschen sollten.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Löschen eines NICHT-Leeren-Ornders ist nicht möglich. Zum löschen von non-checkable Ordner, löschen Sie zuerst dessen Inhalt.');
define('JS_LANG_InfoRequiredFields', '* benötigte Felder');

define('JS_LANG_ConfirmAreYouSure', 'Sind Sie sicher?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Die markierte Nachricht wird unwiederruflich gelöscht! Sind Sie sicher?');
define('JS_LANG_ConfirmSaveSettings', 'Die Einstellungen wurden noch nicht gespeichert. Klicken Sie OK zum Speichern.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Die Kontakt-Einstellungen wurden noch nicht gespeichert. Klicken Sie OK zum Speichern.');
define('JS_LANG_ConfirmSaveAcctProp', 'Die Kontoeinstellungen wurden noch nicht gespeichert. Klicken Sie OK zum Speichern.');
define('JS_LANG_ConfirmSaveFilter', 'Die Filtereinstellungen wurden noch nicht gespeichert. Klicken Sie OK zum Speichern.');
define('JS_LANG_ConfirmSaveSignature', 'Die Signatur wurde noch nicht gespeichert. Klicken SIe OK zum Speichern.');
define('JS_LANG_ConfirmSavefolders', 'Der Ordner wurde noch nicht gespeichert. Klicken Sie OK zum Speichern.');
define('JS_LANG_ConfirmHtmlToPlain', 'Warnung: Wenn Sie das Format dieser Nachricht von HTML auf Nur-Text ändern verlieren Sie die aktuelle Formatierung. Klicken Sie OK für Weiter.');
define('JS_LANG_ConfirmAddFolder', 'Bevor Sie einen Ordner erstellen können müssen die Einstellungen gespeichert werden. Klicken Sie OK zum Speichern.');
define('JS_LANG_ConfirmEmptySubject', 'Das Betreff-Feld ist leer. Möchten Sie trotzem weiterfahren?');

define('JS_LANG_WarningEmailBlank', 'Das Feld <br />Email: wird benötigt');
define('JS_LANG_WarningLoginBlank', 'Das Feld <br />Login: wird benötigt');
define('JS_LANG_WarningToBlank', 'Das Feld To: wird benötigt');
define('JS_LANG_WarningServerPortBlank', 'Die Felder POP3 and<br />SMTP server/port werden benötigt');
define('JS_LANG_WarningEmptySearchLine', 'Keine Suchbegriffe. Bitte geben Sie einen Begriff ein');
define('JS_LANG_WarningMarkListItem', 'Bitte Markieren Sie mindestens ein Objekt in der Liste');
define('JS_LANG_WarningFolderMove', 'Der Ordner kann nicht verschoben werden weil er auf einer anderen Stufe ist');
define('JS_LANG_WarningContactNotComplete', 'Bitte geben Sie Ihren Namen oder E-Mail-Adresse ein');
define('JS_LANG_WarningGroupNotComplete', 'Bitte geben Sie einen Gruppennamen ein');

define('JS_LANG_WarningEmailFieldBlank', 'Das Feld E-Mail darf nicht leer sein');
define('JS_LANG_WarningIncServerBlank', 'Das Feld POP3(IMAP4) Server darf nicht leer sein');
define('JS_LANG_WarningIncPortBlank', 'Das Feld POP3(IMAP4) Server Port darf nicht leer sein');
define('JS_LANG_WarningIncLoginBlank', 'Das Feld POP3(IMAP4) Login darf nicht leer sein');
define('JS_LANG_WarningIncPortNumber', 'Sie sollten einen positiven Wert in POP3(IMAP4) port eingeben.');
define('JS_LANG_DefaultIncPortNumber', 'Standard POP3(IMAP4) port nummer ist 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Das Feld POP3(IMAP4) Kennwort darf nicht leer sein');
define('JS_LANG_WarningOutPortBlank', 'Das Feld SMTP Server Port darf nicht leer sein');
define('JS_LANG_WarningOutPortNumber', 'Sie sollten einen positiven Wert in  SMTP port eingeben.');
define('JS_LANG_WarningCorrectEmail', 'Eine gültige E-Mail-Adresse wird benötigt.');
define('JS_LANG_DefaultOutPortNumber', 'Standard SMTP port nummer ist 25.');

define('JS_LANG_WarningCsvExtention', 'Die Dateiendung sollte  .csv  sein');
define('JS_LANG_WarningImportFileType', 'Bitte wählen Sie das Programm von welchem importiert werden soll');
define('JS_LANG_WarningEmptyImportFile', 'Bitte wählen Sie eine Datei in dem Sie auf durchsuchen klicken');

define('JS_LANG_WarningContactsPerPage', 'Kontakte pro Seite - Muss positiver Wert sein');
define('JS_LANG_WarningMessagesPerPage', 'Nachrichten pro Seite - Muss positiver Wert sein');
define('JS_LANG_WarningMailsOnServerDays', 'Sie sollten einen positiven Wert im Feld Nachrichten auf dem Server Tage definieren');
define('JS_LANG_WarningEmptyFilter', 'Bitte geben Sie einen Wert ein');
define('JS_LANG_WarningEmptyFolderName', 'Bitte geben Sie einen Ordnername ein');

define('JS_LANG_ErrorConnectionFailed', 'Verbindung fehlgeschlagen');
define('JS_LANG_ErrorRequestFailed', 'Die Datenübertragung konnte nicht abgeschlossen werden');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Das Objekt XMLHttpRequest ist absent');
define('JS_LANG_ErrorWithoutDesc', 'Es ist ein unbekannter Fehler aufgetreten');
define('JS_LANG_ErrorParsing', 'Error während parsing XML.');
define('JS_LANG_ResponseText', 'Antwort text:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Leeres XML Paket');
define('JS_LANG_ErrorImportContacts', 'Fehler während Kontakte-Import');
define('JS_LANG_ErrorNoContacts', 'Es wurden keine Kontakte für den Import gefunden');
define('JS_LANG_ErrorCheckMail', 'Während dem Abruf der Mails ist ein Fehler aufgetreten');

define('JS_LANG_LoggingToServer', 'Verbinde zum Server&hellip;');
define('JS_LANG_GettingMsgsNum', 'Beziehe Anzahl der Nachrichten');
define('JS_LANG_RetrievingMessage', 'Empfange Nachricht');
define('JS_LANG_DeletingMessage', 'Lösche Nachricht');
define('JS_LANG_DeletingMessages', 'Lösche Nachricht/en');
define('JS_LANG_Of', 'von');
define('JS_LANG_Connection', 'Verbindung');
define('JS_LANG_Charset', 'Charset');
define('JS_LANG_AutoSelect', 'Auto-Select');

define('JS_LANG_Contacts', 'Kontakte');
define('JS_LANG_ClassicVersion', 'Klassische Version');
define('JS_LANG_Logout', 'Abmelden');
define('JS_LANG_Settings', 'Einstellungen');

define('JS_LANG_LookFor', 'Suchen nach: ');
define('JS_LANG_SearchIn', 'Suche in: ');
define('JS_LANG_QuickSearch', 'Nur in Von, An und Betreff Feldern suchen (schneller).');
define('JS_LANG_SlowSearch', 'Ganze Nachrichten durchsuchen');
define('JS_LANG_AllMailFolders', 'Alle Mail Ordner');
define('JS_LANG_AllGroups', 'Alle Gruppen');

define('JS_LANG_NewMessage', 'Neue Nachricht');
define('JS_LANG_CheckMail', 'Mails abrufen');
define('JS_LANG_EmptyTrash', 'Papierkorb leeren');
define('JS_LANG_MarkAsRead', 'Als gelesen markieren');
define('JS_LANG_MarkAsUnread', 'Als ungelesen markieren');
define('JS_LANG_MarkFlag', 'Flag');
define('JS_LANG_MarkUnflag', 'Unflag');
define('JS_LANG_MarkAllRead', 'Alle als gelesen markieren');
define('JS_LANG_MarkAllUnread', 'Alle als ungelesen markieren');
define('JS_LANG_Reply', 'Antworten');
define('JS_LANG_ReplyAll', 'Allen antworten');
define('JS_LANG_Delete', 'löschen');
define('JS_LANG_Undelete', 'wiederherstellen');
define('JS_LANG_PurgeDeleted', 'gelöschte abgleichen');
define('JS_LANG_MoveToFolder', 'Verschieben nach');
define('JS_LANG_Forward', 'Weiterleiten');

define('JS_LANG_HideFolders', 'Ordner ausblenden');
define('JS_LANG_ShowFolders', 'Ordner anzeigen');
define('JS_LANG_ManageFolders', 'Ordner bearbeiten');
define('JS_LANG_SyncFolder', 'synchronisierter Ordner');
define('JS_LANG_NewMessages', 'Neue Nachrichten');
define('JS_LANG_Messages', 'Nachricht/en');

define('JS_LANG_From', 'Von');
define('JS_LANG_To', 'An');
define('JS_LANG_Date', 'Datum');
define('JS_LANG_Size', 'Grösse');
define('JS_LANG_Subject', 'Betreff');

define('JS_LANG_FirstPage', 'erste Seite');
define('JS_LANG_PreviousPage', 'vorige Seite');
define('JS_LANG_NextPage', 'nächste Seite');
define('JS_LANG_LastPage', 'letzte Seite');

define('JS_LANG_SwitchToPlain', 'Wechseln zur Nur-Text Ansicht');
define('JS_LANG_SwitchToHTML', 'Wechsle zu HTML Ansicht');
define('JS_LANG_AddToAddressBook', 'Zum Adressbuch hinzufügen');
define('JS_LANG_ClickToDownload', 'Klicken zum downloaden');
define('JS_LANG_View', 'Ansicht');
define('JS_LANG_ShowFullHeaders', 'Vollständige Kopfzeilen anzeigen');
define('JS_LANG_HideFullHeaders', 'Vollständige Kopfzeilen verbergen');

define('JS_LANG_MessagesInFolder', 'Nachricht/en im Ordner');
define('JS_LANG_YouUsing', 'Sie benutzen');
define('JS_LANG_OfYour', 'von');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Senden');
define('JS_LANG_SaveMessage', 'Speichern');
define('JS_LANG_Print', 'Drucken');
define('JS_LANG_PreviousMsg', 'Vorige Nachricht');
define('JS_LANG_NextMsg', 'Nächste Nachricht');
define('JS_LANG_AddressBook', 'Adressbuch');
define('JS_LANG_ShowBCC', 'BCC anzeigen');
define('JS_LANG_HideBCC', 'BCC verbergen');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Reply&nbsp;To');
define('JS_LANG_AttachFile', 'Datei anhängen');
define('JS_LANG_Attach', 'anhängen');
define('JS_LANG_Re', 'AW');
define('JS_LANG_OriginalMessage', 'Original Nachricht');
define('JS_LANG_Sent', 'gesendet');
define('JS_LANG_Fwd', 'WG');
define('JS_LANG_Low', 'Niedrig');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Hoch');
define('JS_LANG_Importance', 'Dringlichkeit');
define('JS_LANG_Close', 'Schliessen');

define('JS_LANG_Common', 'Allgemein');
define('JS_LANG_EmailAccounts', 'E-Mail Konten');

define('JS_LANG_MsgsPerPage', 'Nachrichten pro Seite');
define('JS_LANG_DisableRTE', 'Rich-text editor deaktivieren');
define('JS_LANG_Skin', 'Skin');
define('JS_LANG_DefCharset', 'Standard charset');
define('JS_LANG_DefCharsetInc', 'Standard eingehende charset');
define('JS_LANG_DefCharsetOut', 'Standard ausgehende charset');
define('JS_LANG_DefTimeOffset', 'Standard Zeit offset');
define('JS_LANG_DefLanguage', 'Standard Sprache');
define('JS_LANG_DefDateFormat', 'Standard Datum Format');
define('JS_LANG_ShowViewPane', 'Nachrichtenliste mit Vorschaufunktion anzeigen');
define('JS_LANG_Save', 'Speichern');
define('JS_LANG_Cancel', 'Abbrechen');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Ausschneiden');
define('JS_LANG_AddNewAccount', 'Neues Konto hinzufügen');
define('JS_LANG_Signature', 'Signatur');
define('JS_LANG_Filters', 'Filter');
define('JS_LANG_Properties', 'Voreinstellungen');
define('JS_LANG_UseForLogin', 'Verwende diese Kontoeinstellungen (login and password) für login');
define('JS_LANG_MailFriendlyName', 'Ihr Name');
define('JS_LANG_MailEmail', 'E-Mail');
define('JS_LANG_MailIncHost', 'Eingehende Mail');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Kennwort');
define('JS_LANG_MailOutHost', 'SMTP Server');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Login');
define('JS_LANG_MailOutPass', 'SMTP Kennwort');
define('JS_LANG_MailOutAuth1', 'Benutze SMTP authentikation');
define('JS_LANG_MailOutAuth2', '(Lassen Sie die SMTP login/password Felder leer, wenn diese gleich sind wie POP3/IMAP4 login/password)');
define('JS_LANG_UseFriendlyNm1', 'Benutzerfreundliche Namen im "Von:" field');
define('JS_LANG_UseFriendlyNm2', '(Ihr Name &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Nachrichten beim Login abrufen/synchronisieren');
define('JS_LANG_MailMode0', 'Abgerufene Nachrichten vom Mail-Server löschen');
define('JS_LANG_MailMode1', 'Nachrichten auf dem Mailserver belassen');
define('JS_LANG_MailMode2', 'Lasse Nachrichten auf dem Server für');
define('JS_LANG_MailsOnServerDays', 'Tag/e');
define('JS_LANG_MailMode3', 'Lösche Nachrichten vom Mail-Server wenn Sie im Papierkorb-Ordner sind');
define('JS_LANG_InboxSyncType', 'Typ der Posteingang Synchronisierung');

define('JS_LANG_SyncTypeNo', 'Nicht Synchronisieren');
define('JS_LANG_SyncTypeNewHeaders', 'Neue Kopfzeile');
define('JS_LANG_SyncTypeAllHeaders', 'Alle Kopfzeilen');
define('JS_LANG_SyncTypeNewMessages', 'Neue Nachrichten');
define('JS_LANG_SyncTypeAllMessages', 'Alle Nachrichten');
define('JS_LANG_SyncTypeDirectMode', 'Direkt Modus');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Nur Kopfzeilen');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Entire Messages');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direkt Modus');

define('JS_LANG_DeleteFromDb', 'Lösche Nachrichten aus der Datenbank falls auf dem Mail-Server nicht mehr vorhanden');

define('JS_LANG_EditFilter', 'Filter bearbeiten');
define('JS_LANG_NewFilter', 'Neuen Filter hinzufügen');
define('JS_LANG_Field', 'Feld');
define('JS_LANG_Condition', 'Einstellungen');
define('JS_LANG_ContainSubstring', 'enthält Wort');
define('JS_LANG_ContainExactPhrase', 'exakter Wortlauf/Satz');
define('JS_LANG_NotContainSubstring', 'enthält keine Teilsätze');
define('JS_LANG_FilterDesc_At', 'an');
define('JS_LANG_FilterDesc_Field', 'Feld');
define('JS_LANG_Action', 'Action');
define('JS_LANG_DoNothing', 'Nichts tun');
define('JS_LANG_DeleteFromServer', 'Sofort vom Server löschen');
define('JS_LANG_MarkGrey', 'Grau markieren');
define('JS_LANG_Add', 'hinzufügen');
define('JS_LANG_OtherFilterSettings', 'Andere Filter Einstellungen');
define('JS_LANG_ConsiderXSpam', 'Betrachte X-Spam Kopfzeilen');
define('JS_LANG_Apply', 'Übernehmen');

define('JS_LANG_InsertLink', 'Link einfügen');
define('JS_LANG_RemoveLink', 'Link entfernen');
define('JS_LANG_Numbering', 'Nummerierung');
define('JS_LANG_Bullets', 'Bullets');
define('JS_LANG_HorizontalLine', 'Horizontale Linie');
define('JS_LANG_Bold', 'Fett');
define('JS_LANG_Italic', 'Kursiv');
define('JS_LANG_Underline', 'Unterstrichen');
define('JS_LANG_AlignLeft', 'Linksbündig');
define('JS_LANG_Center', 'Zentriert');
define('JS_LANG_AlignRight', 'Rechtsbündig');
define('JS_LANG_Justify', 'Berichtigen');
define('JS_LANG_FontColor', 'Schriftfarbe');
define('JS_LANG_Background', 'Hintergrund');
define('JS_LANG_SwitchToPlainMode', 'Wechsle in Nur-Text Modus');
define('JS_LANG_SwitchToHTMLMode', 'Wechsle in HTML Modus');

define('JS_LANG_Folder', 'Ordner');
define('JS_LANG_Msgs', 'Nachr.');
define('JS_LANG_Synchronize', 'Synchronisieren');
define('JS_LANG_ShowThisFolder', 'Diesen Ordner anzeigen');
define('JS_LANG_Total', 'Total');
define('JS_LANG_DeleteSelected', 'markierte löschen');
define('JS_LANG_AddNewFolder', 'Neuen Ordner hinzufügen');
define('JS_LANG_NewFolder', 'Neuer Ordner');
define('JS_LANG_ParentFolder', 'Parent Folder');
define('JS_LANG_NoParent', 'No Parent');
define('JS_LANG_FolderName', 'Ordnername');

define('JS_LANG_ContactsPerPage', 'Kontakte pro Seite');
define('JS_LANG_WhiteList', 'Adressbuch als Whitelist verwenden');

define('JS_LANG_CharsetDefault', 'Default');
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

define('JS_LANG_TimeDefault', 'Default');
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
define('JS_LANG_DateDDMMYY', 'TT/MM/JJ');
define('JS_LANG_DateMMDDYY', 'MM/TT/JJ');
define('JS_LANG_DateDDMonth', 'TT Monat (01 Jan)');
define('JS_LANG_DateAdvanced', 'Erweitert');

define('JS_LANG_NewContact', 'Neuer Kontakt');
define('JS_LANG_NewGroup', 'Neue Gruppe');
define('JS_LANG_AddContactsTo', 'Kontakt hinzufügen zu');
define('JS_LANG_ImportContacts', 'Kontakte importieren');

define('JS_LANG_Name', 'Name');
define('JS_LANG_Email', 'E-mail');
define('JS_LANG_DefaultEmail', 'Standard E-mail');
define('JS_LANG_NotSpecifiedYet', 'nicht definiert');
define('JS_LANG_ContactName', 'Name');
define('JS_LANG_Birthday', 'Geburtsdatum');
define('JS_LANG_Month', 'Monat');
define('JS_LANG_January', 'Januar');
define('JS_LANG_February', 'Februar');
define('JS_LANG_March', 'März');
define('JS_LANG_April', 'April');
define('JS_LANG_May', 'Mai');
define('JS_LANG_June', 'Juni');
define('JS_LANG_July', 'Juli');
define('JS_LANG_August', 'August');
define('JS_LANG_September', 'September');
define('JS_LANG_October', 'Oktober');
define('JS_LANG_November', 'November');
define('JS_LANG_December', 'Dezember');
define('JS_LANG_Day', 'Tag');
define('JS_LANG_Year', 'Jahr');
define('JS_LANG_UseFriendlyName1', 'Benutzerfreundliche Namen');
define('JS_LANG_UseFriendlyName2', '(z.B. John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Privat');
define('JS_LANG_PersonalEmail', 'Private E-mail');
define('JS_LANG_StreetAddress', 'Strasse');
define('JS_LANG_City', 'Stadt');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Kanton/Bundesland');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'PLZ');
define('JS_LANG_Mobile', 'Mobile');
define('JS_LANG_CountryRegion', 'Land');
define('JS_LANG_WebPage', 'Webseite');
define('JS_LANG_Go', 'Go');
define('JS_LANG_Home', 'Home');
define('JS_LANG_Business', 'Business');
define('JS_LANG_BusinessEmail', 'Business E-mail');
define('JS_LANG_Company', 'Firma');
define('JS_LANG_JobTitle', 'Funktion');
define('JS_LANG_Department', 'Abteilung');
define('JS_LANG_Office', 'Büro');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Andere');
define('JS_LANG_OtherEmail', 'zusätzliche E-mail');
define('JS_LANG_Notes', 'Bemerkungen');
define('JS_LANG_Groups', 'Gruppen');
define('JS_LANG_ShowAddFields', 'zusätzliche Felder anzeigen');
define('JS_LANG_HideAddFields', 'zusätzliche Felder verbergen');
define('JS_LANG_EditContact', 'Kontaktinformationen bearbeiten');
define('JS_LANG_GroupName', 'Gruppen Name');
define('JS_LANG_AddContacts', 'Kontakt hinzufügen');
define('JS_LANG_CommentAddContacts', '(Möchten Sie mehr als eine Adresse eingeben, bitte mit Stichkomma trennen)');
define('JS_LANG_CreateGroup', 'Gruppe erstellen');
define('JS_LANG_Rename', 'Umbenennen');
define('JS_LANG_MailGroup', 'Mail Gruppe');
define('JS_LANG_RemoveFromGroup', 'Aus Gruppe entfernen');
define('JS_LANG_UseImportTo', 'Benutzen Sie Import um Ihre Kontakte aus Microsoft Outlook, Microsoft Outlook Express in Ihr Webmail zu importieren.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Markieren sie die Datei (.CSV format) welche Sie importieren möchten.');
define('JS_LANG_Import', 'Import');
define('JS_LANG_ContactsMessage', 'Das ist die Kontakte Seite!!!');
define('JS_LANG_ContactsCount', 'Kontakt/e');
define('JS_LANG_GroupsCount', 'Gruppe/n');

// webmail 4.1 constants
define('PicturesBlocked', 'Bilder in dieser Nachricht wurden aus Sicherheitsgründen blockiert.');
define('ShowPictures', 'Bilder anzeigen');
define('ShowPicturesFromSender', 'Bilder in Nachrichten dieses Absenders immer anzeigen');
define('AlwaysShowPictures', 'Bilder in Nachrichten immer anzeigen');

define('TreatAsOrganization', 'Als Organisation behandeln');

define('WarningGroupAlreadyExist', 'Es existiert bereits eine Gruppe unter diesem Namen. Bitte wählen Sie einen anderen.');
define('WarningCorrectFolderName', 'Sie müssen einen gültigen Ordnernamen angeben');
define('WarningLoginFieldBlank', 'Login Feld wird benötigt.');
define('WarningCorrectLogin', 'Sie müssen einen gültigen Login angeben.');
define('WarningPassBlank', 'Das Kennwort Feld darf nicht leer sein.');
define('WarningCorrectIncServer', 'Sie müssen eine gültige POP3(IMAP) server addresse angeben.');
define('WarningCorrectSMTPServer', 'Sie müssen eine gültige SMTP server addresse angeben.');
define('WarningFromBlank', 'Das Feld Von: darf nicht leer sein.');
define('WarningAdvancedDateFormat', 'Bitte wählen Sie ein Datum/Zeit Format.');

define('AdvancedDateHelpTitle', 'Erweiterte Datumsanzeige');
define('AdvancedDateHelpIntro', 'Wenn das &quot;Erweiterte&quot; Feld markiert ist, können Sie im Textfeld Ihr Datumsformat eingeben, welches dann in AfterLogic WebMail Pro angezeigt wird. Folgende Optionen sind möglich\':\' or \'/\' um die Zeichen zu trennen:');
define('AdvancedDateHelpConclusion', 'Wenn Sie &quot;mm/dd/yyyy&quot; im Textfeld &quot;Erweitert&quot; eingeben, wird das Datum folgendermassen angezeigt month/day/year (i.e. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Tag des Monats (1 bis 31)');
define('AdvancedDateHelpNumericMonth', 'Monat (1 bis 12)');
define('AdvancedDateHelpTextualMonth', 'Monat (Jan bis Dec)');
define('AdvancedDateHelpYear2', 'Jahr, 2 Zeichen');
define('AdvancedDateHelpYear4', 'Jahr, 4 Zeichen');
define('AdvancedDateHelpDayOfYear', 'Tag des Jahres (1 bis 366)');
define('AdvancedDateHelpQuarter', 'Quartal');
define('AdvancedDateHelpDayOfWeek', 'Wochentag (Mon bis Son)');
define('AdvancedDateHelpWeekOfYear', 'Woche des Jahres (1 bis 53)');

define('InfoNoMessagesFound', 'Keine Nachricht gefunden.');
define('ErrorSMTPConnect', 'Kann nicht zum SMTP server verbinden. Überprüfen Sie die SMTP server Einstellungen.');
define('ErrorSMTPAuth', 'Falscher Benutzername/Kennwort. Authentifikation fehlgeschlagen.');
define('ReportMessageSent', 'Ihre Nachricht wurde gesendet.');
define('ReportMessageSaved', 'Ihre Nachricht wurde gespeichert.');
define('ErrorPOP3Connect', 'Kann nicht zum POP3 server verbinden, Überprüfen Sie die POP3 server Einstellungen.');
define('ErrorIMAP4Connect', 'Kann nicht zum IMAP4 server verbinden, überprüfen Sie die IMAP4 server Einstellungen.');
define('ErrorPOP3IMAP4Auth', 'Falsche E-Mail/Benutzername und/oder Kennwort. Authentifikation fehlgeschlagen.');
define('ErrorGetMailLimit', 'Sorry, Ihre Mailbox-Limite ist erreicht.');

define('ReportSettingsUpdatedSuccessfuly', 'Einstellungen wurden erfolgreich aktualisiert.');
define('ReportAccountCreatedSuccessfuly', 'Konto wurde erfolgreich angelegt.');
define('ReportAccountUpdatedSuccessfuly', 'Konto wurde erfolgreich aktualisiert.');
define('ConfirmDeleteAccount', 'Sind Sie sicher dass Sie dieses Konto löschen wollen?');
define('ReportFiltersUpdatedSuccessfuly', 'Filter wurden erfolgreich aktualisiert.');
define('ReportSignatureUpdatedSuccessfuly', 'Signatur wurde erfolgreich aktualisiert.');
define('ReportFoldersUpdatedSuccessfuly', 'Ordner wurden erfolgreich aktualisiert.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Kontakt Einstellungen wurden erfolgreich aktualisiert.');

define('ErrorInvalidCSV', 'Die CSV-Datei welche Sie gewählt haben hat ein falsches Format.');
// The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Die Gruppe');
define('ReportGroupSuccessfulyAdded2', 'wurde erfolgreich angelegt.');
define('ReportGroupUpdatedSuccessfuly', 'Gruppe wurde erfolgreich aktualisiert.');
define('ReportContactSuccessfulyAdded', 'Kontakt wurde erfolgreich hinzugefügt.');
define('ReportContactUpdatedSuccessfuly', 'Kontakt wurde erfolgreich aktualisiert.');
// Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Kontakt/e wurde hinzugefügt zu Gruppe');
define('AlertNoContactsGroupsSelected', 'Keine Kontakte oder Gruppen markiert.');

define('InfoListNotContainAddress', 'Falls die Liste nicht die gewünschte Adresse enthält, geben Sie die ersten Zeichen der Adresse ein');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Direkt Modus. WebMail behandelt die Mails direkt auf dem Mailserver.');

define('FolderInbox', 'Posteingang');
define('FolderSentItems', 'Gesendet');
define('FolderDrafts', 'Entwürfe');
define('FolderTrash', 'Papierkorb');

define('FileLargerAttachment', 'Die Datei überschreitet das Limit.');
define('FilePartiallyUploaded', 'Aus unbekannten Fehlern wurde nur ein Teil der Datei hochgeladen.');
define('NoFileUploaded', 'Keine Dateien hochgeladen.');
define('MissingTempFolder', 'Der temporäre Ordner fehlt.');
define('MissingTempFile', 'Die temporäre Datei fehlt.');
define('UnknownUploadError', 'Ein unbekannter Datei-Upload-Fehler ist aufgetreten.');
define('FileLargerThan', 'Datei-Upload-Fehler. Wahrscheinlich ist die Datei grösser als ');
define('PROC_CANT_LOAD_DB', 'Kann nicht zur Datenbank verbinden.');
define('PROC_CANT_LOAD_LANG', 'Kann die benötigte Sprachdatei nicht finden.');
define('PROC_CANT_LOAD_ACCT', 'Das Konto existiert nicht, oder wurde gelöscht.');

define('DomainDosntExist', 'Diese Domain existiert nicht auf dem Mail-Server.');
define('ServerIsDisable', 'Zugriff wurde vom Administrator untersagt/gesperrt.');

define('PROC_ACCOUNT_EXISTS', 'Das Konto kann nicht angelegt werden, da es bereits existiert.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Anzeige der vorhandenen Anzahl von Nachrichten fehlgeschlagen.');
define('PROC_CANT_MAIL_SIZE', 'Kann den verfügbaren Speicherplatz nicht abrufen.');

define('Organization', 'Organisation');
define('WarningOutServerBlank', 'Das Feld SMTP Server wird benötigt');

//
define('JS_LANG_Refresh', 'Aktualisieren');
define('JS_LANG_MessagesInInbox', 'Nachricht(en) im Posteingang');
define('JS_LANG_InfoEmptyInbox', 'Posteingang ist leer');

// webmail 4.2 constants
define('BackToList', 'Zurück zur Liste');
define('InfoNoContactsGroups', 'Keine Kontakte oder Gruppen.');
define('InfoNewContactsGroups', 'Sie können neue Kontakte/Gruppen erstellen oder Kontakte von einer .csv-Datei im MS Outlook Format importieren.');
define('DefTimeFormat', 'Standard Zeit-Format');
define('SpellNoSuggestions', 'Keine Vorschläge');
define('SpellWait', 'Please wait&hellip;');

define('InfoNoMessageSelected', 'Keine Nachricht markiert.');
define('InfoSingleDoubleClick', 'Sie können mit einem einfachen Klick auf eine Nachricht in der Liste eine Vorschau anzeigen oder mit einem Doppelklick in Vollansicht anzeigen lassen');

// calendar
define('TitleDay', 'Tagesansicht');
define('TitleWeek', 'Wochenansicht');
define('TitleMonth', 'Monatsansicht');

define('ErrorNotSupportBrowser', 'AfterLogic Kalender unterstützt Ihren Browser nicht. Bitte verwenden Sie FireFox 2.0 oder höher, Opera 9.0 oder höher, Internet Explorer 6.0 oder höher, Safari 3.0.2 oder höher.');
define('ErrorTurnedOffActiveX', 'ActiveX Unterstützung ist ausgeschaltet . <br/>Sie sollten diese einschalten um diese Aplikation zu nutzen.');

define('Calendar', 'Kalender');

define('TabDay', 'Tag');
define('TabWeek', 'Woche');
define('TabMonth', 'Monat');

define('ToolNewEvent', 'Neuer&nbsp;Termin');
define('ToolBack', 'Zurück');
define('ToolToday', 'Heute');
define('AltNewEvent', 'Neuer Termin');
define('AltBack', 'Zurück');
define('AltToday', 'Heute');
define('CalendarHeader', 'Kalender');
define('CalendarsManager', 'Kalender Manager');

define('CalendarActionNew', 'Neuer Kalender');
define('EventHeaderNew', 'Neuer Termin');
define('CalendarHeaderNew', 'Neuer Kalender');

define('EventSubject', 'Betreff');
define('EventCalendar', 'Kalender');
define('EventFrom', 'Von');
define('EventTill', 'Bis');
define('CalendarDescription', 'Beschreibung');
define('CalendarColor', 'Farbe');
define('CalendarName', 'Kalendername');
define('CalendarDefaultName', 'Mein Kalender');

define('ButtonSave', 'Speichern');
define('ButtonCancel', 'Abbrechen');
define('ButtonDelete', 'Löschen');

define('AltPrevMonth', 'Vorheriger Monat');
define('AltNextMonth', 'Nächster Monat');

define('CalendarHeaderEdit', 'Kalender bearbeiten');
define('CalendarActionEdit', 'Kalender bearbeiten');
define('ConfirmDeleteCalendar', 'Sind Sie sicher dass Sie den Kalender löschen wollen');
define('InfoDeleting', 'Lösche&hellip;');
define('WarningCalendarNameBlank', 'Sie können den Kalender Namen nicht leer lassen.');
define('ErrorCalendarNotCreated', 'Kalender wurde nicht angelegt.');
define('WarningSubjectBlank', 'Betreff darf nicht leer sein.');
define('WarningIncorrectTime', 'Die angegebene Zeit enhält ungültige Zeichen.');
define('WarningIncorrectFromTime', 'Die Von Zeit ist inkorrekt.');
define('WarningIncorrectTillTime', 'Die Bis Zeit ist inkorrekt.');
define('WarningStartEndDate', 'Das Zieldatum muss grösser oder gleich dem Startdatum sein.');
define('WarningStartEndTime', 'Die Endzeit muss grösser als die Startzeit sein.');
define('WarningIncorrectDate', 'Das Datum muss korrekt sein.');
define('InfoLoading', 'Lade&hellip;');
define('EventCreate', 'Termin erstellen');
define('CalendarHideOther', 'Verberge andere Kalender');
define('CalendarShowOther', 'Zeige andere Kalender');
define('CalendarRemove', 'Kalender entfernen');
define('EventHeaderEdit', 'Termin bearbeiten');

define('InfoSaving', 'Speichern&hellip;');
define('SettingsDisplayName', 'Name anzeigen');
define('SettingsTimeFormat', 'Zeit Format');
define('SettingsDateFormat', 'Datum Format');
define('SettingsShowWeekends', 'Zeige Wochenenden');
define('SettingsWorkdayStarts', 'Werktage');
define('SettingsWorkdayEnds', 'Ende');
define('SettingsShowWorkday', 'Zeige Werktage');
define('SettingsWeekStartsOn', 'Wochen starten am');
define('SettingsDefaultTab', 'Standart Tab');
define('SettingsCountry', 'Land');
define('SettingsTimeZone', 'Zeit Zone');
define('SettingsAllTimeZones', 'Alle Zeit Zonen');

define('WarningWorkdayStartsEnds', 'Das \'Arbeitstag Ende\' muss grösser sein als \'Arbeitstag Beginn\'');
define('ReportSettingsUpdated', 'Einstellungen wurden erfolgreich aktualisiert.');

define('SettingsTabCalendar', 'Kalender');
define('FullMonthJanuary', 'Januar');
define('FullMonthFebruary', 'Februar');
define('FullMonthMarch', 'März');
define('FullMonthApril', 'April');
define('FullMonthMay', 'Mai');
define('FullMonthJune', 'Juni');
define('FullMonthJuly', 'Juli');
define('FullMonthAugust', 'August');
define('FullMonthSeptember', 'September');
define('FullMonthOctober', 'Oktober');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'Dezember');

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
define('ShortMonthDecember', 'Dez');

define('FullDayMonday', 'Montag');
define('FullDayTuesday', 'Dienstag');
define('FullDayWednesday', 'Mittwoch');
define('FullDayThursday', 'Donnerstag');
define('FullDayFriday', 'Freitag');
define('FullDaySaturday', 'Samstag');
define('FullDaySunday', 'Sonntag');

define('DayToolMonday', 'Mo');
define('DayToolTuesday', 'Di');
define('DayToolWednesday', 'Mi');
define('DayToolThursday', 'Do');
define('DayToolFriday', 'Fr');
define('DayToolSaturday', 'Sa');
define('DayToolSunday', 'So');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'D');
define('CalendarTableDayWednesday', 'M');
define('CalendarTableDayThursday', 'D');
define('CalendarTableDayFriday', 'F');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'S');

define('ErrorParseJSON', 'Die JSON Antwort vom server kann nicht analysiert werden.');

define('ErrorLoadCalendar', 'Kann den Kalender nicht laden');
define('ErrorLoadEvents', 'Kann Termine nicht laden');
define('ErrorUpdateEvent', 'Termin konnte nicht gespeichert werden');
define('ErrorDeleteEvent', 'Termin konnte nicht gelöscht werden');
define('ErrorUpdateCalendar', 'Kalender konnte nicht gespeichert werden');
define('ErrorDeleteCalendar', 'Kalender konnte nicht gelöscht werden');
define('ErrorGeneral', 'Es ist ein Server-Fehler aufgetreten. Bitte versuchen Sie es später nochmal.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-Mail');
define('ShareHeaderEdit', 'Kalender freigeben und veröffentlichen');
define('ShareActionEdit', 'Kalender freigeben und veröffentlichen');
define('CalendarPublicate', 'Kalender öffentlich zugänglich machen');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Diesen Kalender freigeben');
define('SharePermission1', 'Kann Änderungen vornehmen und Rechte vergeben');
define('SharePermission2', 'Kann Termine ändern');
define('SharePermission3', 'Kann alle Termindetails sehen');
define('SharePermission4', 'Kann nur den Status Frei/Besetzt sehen (Details verborgen)');
define('ButtonClose', 'Schliessen');
define('WarningEmailFieldFilling', 'Sie sollten zuerst das E-Mail-Feld ausfüllen');
define('EventHeaderView', 'Termine anzeigen');
define('ErrorUpdateSharing', 'Kann die Freigabe- und Veröffentlichungs-Daten nicht anzeigen');
define('ErrorUpdateSharing1', 'Es ist nicht möglich die Daten für %s user freizugeben, da er nicht existiert');
define('ErrorUpdateSharing2', 'Unmöglich den Kalender für den Benutzer %s freizugeben');
define('ErrorUpdateSharing3', 'Dieser Kalender ist bereits für Benutzer %s freigegeben');
define('Title_MyCalendars', 'Meine Kalender');
define('Title_SharedCalendars', 'Freigegebene Kalender');
define('ErrorGetPublicationHash', 'Konnte den Veröffentlichungs-Link nicht erstellen');
define('ErrorGetSharing', 'Freigabe konnte nicht hinzugefügt werden');
define('CalendarPublishedTitle', 'Dieser Kalender ist veröffentlicht');
define('RefreshSharedCalendars', 'Aktualisiere Freigegebene Kalender');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Mitglieder');

define('ReportMessagePartDisplayed', 'Beachten Sie, dass nur ein Teil der Nachrichten angezeigt wird.');
define('ReportViewEntireMessage', 'um die ganze Nachricht anzuzeigen,');
define('ReportClickHere', 'hier klicken');
define('ErrorContactExists', 'Ein Kontakt mit diesem Namen und E-Mail existiert bereits.');

define('Attachments', 'Anhang');

define('InfoGroupsOfContact', 'Die Gruppe, der dieser Kontakt angehört ist mit Kontollzeichen markiert.');
define('AlertNoContactsSelected', 'Kein Kontakt markiert.');
define('MailSelected', 'Maile markierte Adressen');
define('CaptionSubscribed', 'Angemeldet');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Kein Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Mail Kontakt');
define('ContactViewAllMails', 'Alle Mails dieses Kontakts anzeigen');
define('ContactsMailThem', 'Diesem Kontakt Mail senden');
define('DateToday', 'Heute');
define('DateYesterday', 'Gestern');
define('MessageShowDetails', 'Details anzeigen');
define('MessageHideDetails', 'Details verbergen');
define('MessageNoSubject', 'Kein Betreff');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'An');
define('SearchClear', 'Suche löschen');
// Suchergebnisse für "search string" in Posteingang:
// Suchergebnisse für "search string" in allen Ordnern:
define('SearchResultsInFolder', 'Suchergebnisse für "#s" in #f Ordner:');
define('SearchResultsInAllFolders', 'Suchergebnisse für "#s" in allen Ordnern:');
define('AutoresponderTitle', 'Automatische Antwort');
define('AutoresponderEnable', 'automatische Antwort aktivieren');
define('AutoresponderSubject', 'Betreff');
define('AutoresponderMessage', 'Nachricht');
define('ReportAutoresponderUpdatedSuccessfuly', 'Automatische Antwort wurde erfolgreich aktualisiert.');
define('FolderQuarantine', 'Quarantäne');

//calendar
define('EventRepeats', 'Wiederholungen');
define('NoRepeats', 'keine Wiederholung');
define('DailyRepeats', 'Täglich');
define('WorkdayRepeats', 'jede Woche (Mon. - Fri.)');
define('OddDayRepeats', 'jeden Monat, Wed. and Fri.');
define('EvenDayRepeats', 'Jeden Don. and Don.');
define('WeeklyRepeats', 'Wöchentlich');
define('MonthlyRepeats', 'Monatlich');
define('YearlyRepeats', 'Jährlich');
define('RepeatsEvery', 'jeden Wiederholen');
define('ThisInstance', 'Nur diese Instanz');
define('AllEvents', 'Alle Termine in der Serie');
define('AllFollowing', 'jeden folgende');
define('ConfirmEditRepeatEvent', 'Möchten Sie nur diesen Termin, jeden Termin, oder diesen und alle Folgenden Termine in dieser Serie ändern?');
define('RepeatEventHeaderEdit', 'Sich wiederholende Termine bearbeiten');
define('First', 'Erster');
define('Second', 'Zweiter');
define('Third', 'Dritter');
define('Fourth', 'Vierter');
define('Last', 'Letzter');
define('Every', 'Jeder');
define('SetRepeatEventEnd', 'Enddatum setzen');
define('NoEndRepeatEvent', 'Kein Enddatum');
define('EndRepeatEventAfter', 'Ende nach');
define('Occurrences', 'Ereignisse');
define('EndRepeatEventBy', 'Ende durch');
define('EventCommonDataTab', 'Haupt-Details');
define('EventRepeatDataTab', 'Wiederholungs Details');
define('RepeatEventNotPartOfASeries', 'Dieser Termin hat geändert und ist nicht mehr in dieser Serie.');
define('UndoRepeatExclusion', 'Machen Sie die Änderung rückgängig um in dieser Serie einzuschliessen.');

define('MonthMoreLink', '%d mehr...');
define('NoNewSharedCalendars', 'Keine neuen Kalender');
define('NNewSharedCalendars', '%d neue Kalender gefunden');
define('OneNewSharedCalendars', '1 neuer Kalender gefunden');
define('ConfirmUndoOneRepeat', 'Möchten Sie den Termin in dieser Serie wiederherstellen?');

define('RepeatEveryDayInfin', 'Jeden Tag');
define('RepeatEveryDayTimes', 'Jeden Tag, %TIMES% Zeit');
define('RepeatEveryDayUntil', 'Jeden Tag, bis %UNTIL%');
define('RepeatDaysInfin', 'Jeden %PERIOD% Tag');
define('RepeatDaysTimes', 'Jeden %PERIOD% Tag, %TIMES% Uhrzeit');
define('RepeatDaysUntil', 'Jeden %PERIOD% Tag, bis %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Jede Woche an Wochentagen');
define('RepeatEveryWeekWeekdaysTimes', 'Jede Woche an Wochentagen, %TIMES% Uhrzeit');
define('RepeatEveryWeekWeekdaysUntil', 'Jede Woche an Wochentagen, bis %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Jede %PERIOD%Wwoche an Wochentagen');
define('RepeatWeeksWeekdaysTimes', 'Jede %PERIOD% Woche an Wochentagen, %TIMES% Uhrzeit');
define('RepeatWeeksWeekdaysUntil', 'Jede %PERIOD% Woche an Wochentagen, bis %UNTIL%');

define('RepeatEveryWeekInfin', 'Jede Woche am %DAYS%');
define('RepeatEveryWeekTimes', 'Jede Woche am %DAYS%, %TIMES% Uhrzeit');
define('RepeatEveryWeekUntil', 'Jede Woche am %DAYS%, bis %UNTIL%');
define('RepeatWeeksInfin', 'Jede Woche %PERIOD% wöchentlich an %DAYS%');
define('RepeatWeeksTimes', 'Jede Woche %PERIOD% wöchentlich an %DAYS%, %TIMES% Uhrzeit');
define('RepeatWeeksUntil', 'Jede Woche %PERIOD% wöchentlich an %DAYS%, bis %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Jeden Monat am %DATE%');
define('RepeatEveryMonthDateTimes', 'Jeden Monat am %DATE%, %TIMES% Uhrzeit');
define('RepeatEveryMonthDateUntil', 'Jeden Monat am %DATE%, bis %UNTIL%');
define('RepeatMonthsDateInfin', 'Jeden Monat %PERIOD% monatlich am %DATE%');
define('RepeatMonthsDateTimes', 'Jeden Monat %PERIOD% monatlich am %DATE%, %TIMES% Uhrzeit');
define('RepeatMonthsDateUntil', 'Jeden Monat%PERIOD% monatlich am %DATE%, bis %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Jeden Monat an %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Jeden Monat an %NUMBER% %DAY%, %TIMES% Uhrzeit');
define('RepeatEveryMonthWDUntil', 'Every month on %NUMBER% %DAY%, until %UNTIL%');
define('RepeatMonthsWDInfin', 'Jeden %PERIOD% Monat an %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Jeden %PERIOD% Monat an%NUMBER% %DAY%, %TIMES% Uhrzeit');
define('RepeatMonthsWDUntil', 'Jeden %PERIOD% Monat an %NUMBER% %DAY%, until %UNTIL%');

define('RepeatEveryYearDateInfin', 'Jährlich am %DATE%');
define('RepeatEveryYearDateTimes', 'Jährlich am %DATE%, %TIMES% Uhrzeit');
define('RepeatEveryYearDateUntil', 'Every year on day %DATE%, until %UNTIL%');
define('RepeatYearsDateInfin', 'jährlich %PERIOD% am %DATE%');
define('RepeatYearsDateTimes', 'jährlich %PERIOD% am %DATE%, %TIMES% Uhrzeit');
define('RepeatYearsDateUntil', 'jährlich %PERIOD% am %DATE%, bis %UNTIL%');

define('RepeatEveryYearWDInfin', 'jährlich an %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'jährlich an %NUMBER% %DAY%, %TIMES% Uhrzeit');
define('RepeatEveryYearWDUntil', 'jährlich an %NUMBER% %DAY%, bis %UNTIL%');
define('RepeatYearsWDInfin', 'jährlich %PERIOD% an %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'jährlich %PERIOD% an %NUMBER% %DAY%, %TIMES% Uhrzeit');
define('RepeatYearsWDUntil', 'jährlich %PERIOD% an %NUMBER% %DAY%, bis %UNTIL%');

define('RepeatDescDay', 'Tag');
define('RepeatDescWeek', 'Woche');
define('RepeatDescMonth', 'Monat');
define('RepeatDescYear', 'Jahr');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Bitte spezifizieren Sie das Ende der Wiederholung');
define('WarningWrongUntilDate', 'Das Datum des Endes der Wiederholung muss später sein als das Anfangsdatum');

define('OnDays', 'An Tagen');
define('CancelRecurrence', 'Wiederholung abbrechen');
define('RepeatEvent', 'Wiederhole diesen Termin');

define('Spellcheck', 'prüfe Rechtschreibung');
define('LoginLanguage', 'Sprache');
define('LanguageDefault', 'Default');

// webmail 4.5.x new
define('EmptySpam', 'Spam leeren');
define('Saving', 'Speichere&hellip;');
define('Sending', 'Sende&hellip;');
define('LoggingOffFromServer', 'Ausloggen vom Server&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Kann die Nachricht(en) nicht als Spam markieren');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Kann die Nachricht(en) nicht als Kein-Spam markieren');
define('ExportToICalendar', 'Zu iCalendar exportieren');
define('ErrorMaximumUsersLicenseIsExceeded', 'Ihr Konto wurde deaktiviert, da die Anzahl Benutzer Ihre Lizenz übersteigt. Bitte kontaktieren Sie den System Administrator.');
define('RepliedMessageTitle', 'Beantwortete Nachricht');
define('ForwardedMessageTitle', 'Weitergeleitete Nachricht');
define('RepliedForwardedMessageTitle', 'Beantwortete und weitergeleitete Nachricht');
define('ErrorDomainExist', 'Der Benutzer kann nicht angelegt werden, da die Domain nicht existiert. Sie sollten zuerst die Domain erstellen.');

// webmail 4.7
define('RequestReadConfirmation', 'Lesebestätigung anfordern');
define('FolderTypeDefault', 'Standard');
define('ShowFoldersMapping', 'Eigene Ordner verwenden statt die Systemordner (z.B. MeinOrdner für gesendete Nachrichten)');
define('ShowFoldersMappingNote', 'Um den Speicherort für gesendete Mails auf einen eigenen Ordner zu ändern, definieren sie "gesendete Mails" im "Verwenden für" dropdown von "Eigene Ordner".');
define('FolderTypeMapTo', 'Verwenden fuer');

define('ReminderEmailExplanation', 'Diese Nachricht wurde gesendet %EMAIL% da Sie in Ihrem Kalender eine Benachrichtigung angeforder haben: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Öffne Kalender');

define('AddReminder', 'Errinerung für diesen Termin');
define('AddReminderBefore', 'Erinnerung % vor diesem Termin');
define('AddReminderAnd', 'und % vor');
define('AddReminderAlso', 'und auch % vor');
define('AddMoreReminder', 'Mehr Benachrichtigungen');
define('RemoveAllReminders', 'Alle Benachrichtigungen entfernen');
define('ReminderNone', 'Keine');
define('ReminderMinutes', 'Minuten');
define('ReminderHour', 'Stunde');
define('ReminderHours', 'Stunden');
define('ReminderDay', 'Tag');
define('ReminderDays', 'Tage');
define('ReminderWeek', 'Woche');
define('ReminderWeeks', 'Wochen');
define('Allday', 'Alle Tage');

define('Folders', 'Ordner');
define('NoSubject', 'Kein Betreff');
define('SearchResultsFor', 'Suchergebnisse für');

define('Back', 'Zurück');
define('Next', 'Nächste');
define('Prev', 'Vorherige');

define('MsgList', 'Nachrichten');
define('Use24HTimeFormat', 'Benutze das 24-Stunden Format');
define('UseCalendars', 'Benutze Kalender');
define('Event', 'Termin');
define('CalendarSettingsNullLine', 'Kein Kalender');
define('CalendarEventNullLine', 'Keine Termine');
define('ChangeAccount', 'Wechse Benutzerkonto');

define('TitleCalendar', 'Kalender');
define('TitleEvent', 'Termin');
define('TitleFolders', 'Ordner');
define('TitleConfirmation', 'Bestätigung');

define('Yes', 'Ja');
define('No', 'Nein');

define('EditMessage', 'Nachricht bearbeiten');

define('AccountNewPassword', 'Neues Passwort');
define('AccountConfirmNewPassword', 'Passwort bestätigen');
define('AccountPasswordsDoNotMatch', 'Passwörter stimmen nicht überein.');

define('ContactTitle', 'Titel');
define('ContactFirstName', 'Vorname');
define('ContactSurName', 'Nachname');
define('ContactNickName', 'Nick');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'erneut laden');
define('CaptchaError', 'Captcha-Text ist inkorrekt.');

define('WarningInputCorrectEmails', 'Bitte korrekte E-Mails spezifizieren.');
define('WrongEmails', 'Inkorrekte E-Mails:');

define('ConfirmBodySize1', 'Sorry, aber Textmitteilungen sind Maximum.');
define('ConfirmBodySize2', 'characters long. Alles was diese Limite übersteigt wird gekürzt werden. Klicken Sie auf Abbrechen um die Nachricht zu bearbeiten.');
define('BodySizeCounter', 'Zähler');
define('InsertImage', 'Bild einfügen');
define('ImagePath', 'Pfad zum Bild');
define('ImageUpload', 'Einfügen');
define('WarningImageUpload', 'Die angefügte Datei ist kein Bild. Bitte wählen Sie eine Bilddatei.');

define('ConfirmExitFromNewMessage', 'Wenn Sie die Seite verlassen gehen alle Änderungen verloren. Möchten Sie Entwürfe vor dem verlassen der Seite speichern?');

define('SensivityConfidential', 'Bitte behandeln Sie diese Nachricht als vertraulich');
define('SensivityPrivate', 'Bitte behandeln Sie diese Nachricht als Privat');
define('SensivityPersonal', 'Bitte behandeln Sie diese Nachricht als Persönlich');

define('ReturnReceiptTopText', 'Der Absender dieser Nachricht hat um eine Lesebestätigung gebeten.');
define('ReturnReceiptTopLink', 'Hier klicken zum Senden der Lesebestätigung.');

define('ReturnReceiptSubject', 'Lesebestätigung (angezeigt)');
define('ReturnReceiptMailText1', 'Dies ist eine Lesebestätigung für die Nachricht an:');
define('ReturnReceiptMailText2', 'Beachten Sie: Dies ist nur eine Bestätigung, dass die Nachricht am PC des Empfängers angezeigt wurde. Dies ist keine Garantie dafür, dass der Empfänger den Inhalt Ihrer Nachricht gelesen oder verstanden hat.');
define('ReturnReceiptMailText3', 'mit Betreff');

define('SensivityMenu', 'Sensibilität');
define('SensivityNothingMenu', 'Keine');
define('SensivityConfidentialMenu', 'Vertraulich');
define('SensivityPrivateMenu', 'Privat');
define('SensivityPersonalMenu', 'Persönlich');

define('ErrorLDAPonnect', 'Kann nicht zum LDAP Server verbinden.');

define('MessageSizeExceedsAccountQuota', 'Diese Nachricht überschreitet Ihre Kontolimite.');
define('MessageCannotSent', 'Die Nachricht kann nicht gesendet werden.');
define('MessageCannotSaved', 'Die Nachricht kann nicht gespeichert werden.');

define('ContactFieldTitle', 'Feld');
define('ContactDropDownTO', 'TO');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Nachricht/en konnten nicht in den Papierkorb verschoben werden. Soll/en diese Nachrichte/n gelöscht werden?');

define('WarningFieldBlank', 'Dieses Feld darf nicht leer sein.');
define('WarningPassNotMatch', 'Passwort stimmt nicht überein. Bitte prüfen Sie.');
define('PasswordResetTitle', 'Passwortwiederherstellung - Schritt %d');
define('NullUserNameonReset', 'Benutzer');
define('IndexResetLink', 'Passwort vergessen?');
define('IndexRegLink', 'Benutzer Registration');

define('RegDomainNotExist', 'Domain existiert nicht.');
define('RegAnswersIncorrect', 'Antworten sind falsch.');
define('RegUnknownAdress', 'Unbekannte E-Mail Adresse.');
define('RegUnrecoverableAccount', 'Passwortwiederherstellung kann für diese E-Mail-Adresse nicht angewendet werden.');
define('RegAccountExist', 'Diese Adresse wird bereits verwendet.');
define('RegRegistrationTitle', 'Registration');
define('RegName', 'Name');
define('RegEmail', 'E-Mail Adresse');
define('RegEmailDesc', 'Zum Beispiel, meinname@domain.com. Diese Information wird für die Anmeldung am System benutzt.');
define('RegSignMe', 'In Zukunft automatisch anmelden');
define('RegSignMeDesc', 'Keine erneute Eingabe des Benutzernamens und des Passwortest bei einer erneuter Anmeldung am System von diesem PC aus.');
define('RegPass1', 'Passwort');
define('RegPass2', 'Passwort wiederholen ');
define('RegQuestionDesc', 'Bitte geben Sie zwei geheime Fragen ein, von denen ausschliesslich Sie die Antwort kennen. Im Falle eines Verlustes Ihres Passworts, können Sie dieses mithilfe dieser Fragen und Antworten wiederherstellen.');
define('RegQuestion1', 'Geheime Frage 1');
define('RegAnswer1', 'Anwort 1');
define('RegQuestion2', 'Geheime Frage 2');
define('RegAnswer2', 'Anwort 2');
define('RegTimeZone', 'Zeitzone');
define('RegLang', 'Interface Sprache');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'registrieren');

define('ResetEmail', 'Bitte geben Sie Ihre E-Mail Adresse ein.');
define('ResetEmailDesc', 'Geben Sie Ihre E-Mail Adresse ein welche Sie zur Registration benutzt haben.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Senden');
define('ResetQuestion1', 'Geheime Frage 1');
define('ResetAnswer1', 'Antwort');
define('ResetQuestion2', 'Geheime Frage 2');
define('ResetAnswer2', 'Antwort');
define('ResetSubmitStep2', 'Senden');

define('ResetTopDesc1Step2', 'Bereitgestellte E-Mail Adresse');
define('ResetTopDesc2Step2', 'Bitte bestätigen Sie die Korrektheit.');

define('ResetTopDescStep3', 'Bitte geben Sie unten ein neues Passwort für Ihr Benutzerkonto ein.');

define('ResetPass1', 'Neues Passowrt');
define('ResetPass2', 'Passwort wiederholen');
define('ResetSubmitStep3', 'Senden');
define('ResetDescStep4', 'Ihr Passwort wurde erfolgreich geändert.');
define('ResetSubmitStep4', 'Zürück');

define('RegReturnLink', 'Zurück zur Anmeldeseite');
define('ResetReturnLink', 'Zurück zur Anmeldeseite');

// Appointments
define('AppointmentAddGuests', 'Gast hinzufügen');
define('AppointmentRemoveGuests', 'Meeting abbrechen');
define('AppointmentListEmails', 'Geben Sie E-Mail Adressen getrennt durch Kommas ein und klicken Sie auf Speichern');
define('AppointmentParticipants', 'Teilnehmer');
define('AppointmentRefused', 'Refuse');
define('AppointmentAwaitingResponse', 'warte auf Antwort');
define('AppointmentInvalidGuestEmail', 'Die folgenden Gast-E-Mail Adressen sind fehlerhaft:');
define('AppointmentOwner', 'Besitzer');

define('AppointmentMsgTitleInvite', 'Zum Termin einladen.');
define('AppointmentMsgTitleUpdate', 'Termin wurde geändert.');
define('AppointmentMsgTitleCancel', 'Termin wurde abgesagt.');
define('AppointmentMsgTitleRefuse', 'Gast %guest% is refuse invitation');
define('AppointmentMoreInfo', 'Weitere Info');
define('AppointmentOrganizer', 'Organisator');
define('AppointmentEventInformation', 'Termin Information');
define('AppointmentEventWhen', 'Wenn');
define('AppointmentEventParticipants', 'Teilnehmer');
define('AppointmentEventDescription', 'Beschreibung');
define('AppointmentEventWillYou', 'Sie werden teilnehmen');
define('AppointmentAdditionalParameters', 'Zusätzliche Parameter');
define('AppointmentHaventRespond', 'Bis jetzt nicht beantwortet');
define('AppointmentRespondYes', 'Ich werde teilnehmen');
define('AppointmentRespondMaybe', 'Noch nicht sicher');
define('AppointmentRespondNo', 'Ich werde nicht teilnehmen');
define('AppointmentGuestsChangeEvent', 'Gast kann Änderungen vornehmen');

define('AppointmentSubjectAddStart', 'Sie haben Termineinladungen erhalten ');
define('AppointmentSubjectAddFrom', ' von ');
define('AppointmentSubjectUpdateStart', 'Anderungen von Termin ');
define('AppointmentSubjectDeleteStart', 'Absage von Termin ');
define('ErrorAppointmentChangeRespond', 'Terminänderung konnte nicht geändert werden');
define('SettingsAutoAddInvitation', 'Einladungen automatisch zum Kalender hinzufügen');
define('ReportEventSaved', 'Ihr Termin wurde gespeichert');
define('ReportAppointmentSaved', ' and notifications were sent');
define('ErrorAppointmentSend', 'Einladungen konnten nicht gesendet werden.');
define('AppointmentEventName', 'Name:');

// End appointments

define('ErrorCantUpdateFilters', 'Kann Filter nicht aktualisieren');

define('FilterPhrase', 'Wenn %field header %condition %string dann %action');
define('FiltersAdd', 'Filter hinzufügen');
define('FiltersCondEqualTo', 'gleich wie');
define('FiltersCondContainSubstr', 'enthält');
define('FiltersCondNotContainSubstr', 'enthält nicht');
define('FiltersActionDelete', 'Nachricht löschen');
define('FiltersActionMove', 'verschieben');
define('FiltersActionToFolder', 'nach %folder Ordner');
define('FiltersNo', 'Es wurden keine Filter spezifiziert');

define('ReminderEmailFriendly', 'Erinnerungen');
define('ReminderEventBegin', 'startet am: ');

define('FiltersLoading', 'Lade Filter...');
define('ConfirmMessagesPermanentlyDeleted', 'Alle Nachrichten in diesem Ordner werden permanent gelöscht.');

define('InfoNoNewMessages', 'Keine neuen Nachrichten.');
define('TitleImportContacts', 'Kontakte importieren');
define('TitleSelectedContacts', 'markierte Kontakte');
define('TitleNewContact', 'Neuer Kontakt');
define('TitleViewContact', 'Kontakt anzeigen');
define('TitleEditContact', 'Kontakt bearbeiten');
define('TitleNewGroup', 'Neue Gruppe');
define('TitleViewGroup', 'Gruppe anzeigen');

define('AttachmentComplete', 'Abgeschlossen.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Mails automatisch abrufen alle');
define('AutoCheckMailIntervalDisableName', 'Aus');
define('ReportCalendarSaved', 'Kalender wurde gespeichert.');

define('ContactSyncError', 'Sync fehlgeschlagen');
define('ReportContactSyncDone', 'Sync abgeschlossen');

define('MobileSyncUrlTitle', 'Mobile sync URL');
define('MobileSyncLoginTitle', 'Mobile sync login');

define('QuickReply', 'Kurze Antwort');
define('SwitchToFullForm', 'Öffne ganzes Formular');
define('SortFieldDate', 'Datum');
define('SortFieldFrom', 'Von');
define('SortFieldSize', 'Grösse');
define('SortFieldSubject', 'Betreff');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Anhang');
define('SortOrderAscending', 'Aufsteigend');
define('SortOrderDescending', 'Absteigend');
define('ArrangedBy', 'Arrangiert von');

define('MessagePaneToRight', 'Die Nachrichten Anzeige ist auf der rechten Seite der Nachrichtenliste, statt unten');

define('SettingsTabMobileSync', 'Mobile');

define('MobileSyncContactDataBaseTitle', 'Mobile sync Kontakt Datenbank');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync Kalender Datenbank');
define('MobileSyncTitleText', 'Wenn Sie Ihre SyncML Mobilgeräte mit dem Webmail synchronisieren möchten, können Sie diese Parameter verwenden.<br />"Mobile Sync URL" gibt den Pfad zum SyncML Synchronisations-Server an, "Mobile Sync Login" sind Ihre Anmeldedaten für den SyncML Synchronisations-Server is your login on SyncML Data Synchronization Server und verwendet Ihr eigenes Passwort auf Anfrage. Einige Geräte benötigen den Datenbank Namen für Kontakte und Kalender.<br />Benutzen Sie "Mobile sync Kontakt Datenbank" und "Mobile sync Kalender Datenbank".');
define('MobileSyncEnableLabel', 'Aktiviere mobile sync');

define('SearchInputText', 'Suchen');

define('AppointmentEmailExplanation','Diese Nachricht kam in Ihr Konto %EMAIL% weil Sie von %ORGANAZER% zu diesem Termin eingeladen wurden');

define('Searching', 'Searching&hellip;');

define('ButtonSetupSpecialFolders', 'Erstelle spezifische Ordner');
define('ButtonSaveChanges', 'Änderungen speichern');
define('InfoPreDefinedFolders', 'Für voreingestellte Ordner, benutze diese IMAP Kontten');

define('SaveMailInSentItems', 'Zusätzlich in Gesendete Nachrichten speichern');

define('CouldNotSaveUploadedFile', 'Die hochgeladene Datei kann nicht gespeichert werden.');

define('AccountOldPassword', 'Aktuelles Passwort');
define('AccountOldPasswordsDoNotMatch', 'Aktuelle Passwörter stimmen nicht überein');

define('DefEditor', 'Standardeditor');
define('DefEditorRichText', 'Rich Text');
define('DefEditorPlainText', 'Standard Text');

define('Layout', 'Layout');

define('TitleNewMessagesCount', '%count% Neue Nachricht(en)');

define('AltOpenInNewWindow', 'In neuem Fenster öffnen');

define('SearchByFirstCharAll', 'Alle');

define('FolderNoUsageAssigned', 'Kein Einsatz zugewiesen');

define('InfoSetupSpecialFolders', 'To match a special folder (like Sent Items) and certain IMAP mailbox, click Setup special folders.');

define('FileUploaderClickToAttach', 'Klicken um eine Datei anzuhängen');
define('FileUploaderOrDragNDrop', 'Oder ziehen Sie die Datei mit der Maus hierhin');

define('AutoCheckMailInterval1Minute', '1 Minute');
define('AutoCheckMailInterval3Minutes', '3 Minuten');
define('AutoCheckMailInterval5Minutes', '5 Minuten');
define('AutoCheckMailIntervalMinutes', 'Minuten');

define('ReadAboutCSVLink', 'Lesen Sie mehr über CSV-Datei Felder');

define('VoiceMessageSubj', 'Sprachnachricht');
define('VoiceMessageTranscription', 'Transcription');
define('VoiceMessageReceived', 'Empfangen');
define('VoiceMessageDownload', 'Herunterladen');
define('VoiceMessageUpgradeFlashPlayer', 'Um Sprachnachrichten abzuspielen müssen Sie ihren Adobe Flash Player aktualisieren.<br />Aktualisieren Sie auf Flash Player 10 über folgenden Link <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

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
