<?php
define('PROC_ERROR_ACCT_CREATE', 'Si è verificato un errore durante la creazione dell\'account');
define('PROC_WRONG_ACCT_PWD', 'Password errata');
define('PROC_CANT_LOG_NONDEF', 'Impossibile entrare nell\'account non predefinito');
define('PROC_CANT_INS_NEW_FILTER', 'Impossibile inserire il nuovo filtro');
define('PROC_FOLDER_EXIST', 'Esiste già una cartella con lo stesso nome');
define('PROC_CANT_CREATE_FLD', 'Impossibile creare la cartella');
define('PROC_CANT_INS_NEW_GROUP', 'Impossibile inserire il nuovo gruppo');
define('PROC_CANT_INS_NEW_CONT', 'Impossibile inserire il nuovo contatto');
define('PROC_CANT_INS_NEW_CONTS', 'Impossibile inserire il nuovo contatto(i)');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Impossibile aggiungere il contatto(i) al gruppo');
define('PROC_ERROR_ACCT_UPDATE', 'Si è verificato un errore durante l\'aggiornamento dell\'account');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Impossibile aggiornare le impostazioni dell\'account');
define('PROC_CANT_GET_SETTINGS', 'Impossibile ricevere le impostazioni');
define('PROC_CANT_UPDATE_ACCT', 'Impossibile aggiornare l\'account');
define('PROC_ERROR_DEL_FLD', 'Si è verificato un errore durante la cancellazione della cartella(e)');
define('PROC_CANT_UPDATE_CONT', 'Impossibile aggiornare il contatto');
define('PROC_CANT_GET_FLDS', 'Impossibile ottenere la lista delle cartelle');
define('PROC_CANT_GET_MSG_LIST', 'Impossibile ottenere la lista dei messaggi');
define('PROC_MSG_HAS_DELETED', 'Questo messaggio è già stato cancellato dal server mail');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Impossibile caricare le impostazioni dei contatti');
define('PROC_CANT_LOAD_SIGNATURE', 'Impossibile caricare la firma dell\'account');
define('PROC_CANT_GET_CONT_FROM_DB', 'Impossibile ottenere il contatto dal database');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Impossibile ottenere il contatto(i) dal database');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Impossibile cancellare l\'account dall\'id');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Impossibile cancellare il filtro dall\'id');
define('PROC_CANT_DEL_CONT_GROUPS', 'Impossibile cancellare il contatto(i) e/o i gruppi');
define('PROC_WRONG_ACCT_ACCESS', 'Rilevato un tentativo di accesso da parte di un utente non autorizzato.');
define('PROC_SESSION_ERROR', 'La sessione precedente è stata terminata a causa di un timeout.');

define('MailBoxIsFull', 'La casella email è piena');
define('WebMailException', 'Si è verificata un\'eccezione della webmail');
define('InvalidUid', 'Messaggio UID non valido');
define('CantCreateContactGroup', 'Impossibile creare gruppo di contatti');
define('CantCreateUser', 'Impossibile creare l\'utente');
define('CantCreateAccount', 'Impossibile creare l\'account');
define('SessionIsEmpty', 'La sessione è occupata');
define('FileIsTooBig', 'Il file è troppo grosso');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Impossibile segnare tutti i messaggi come letti');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Impossibile segnare tutti i messaggi come non letti');
define('PROC_CANT_PURGE_MSGS', 'Impossibile eliminare il messaggio(i)');
define('PROC_CANT_DEL_MSGS', 'Impossibile cancellare il messaggio(i)');
define('PROC_CANT_UNDEL_MSGS', 'Impossibile recuperare il messaggio(i)');
define('PROC_CANT_MARK_MSGS_READ', 'Impossibile segnare il messaggio(i) come letto');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Impossibile segnare il messaggio(i) come non-letto');
define('PROC_CANT_SET_MSG_FLAGS', 'Impossibile impostare la bandierina(e) sul messaggio');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Impossibile rimuovere la bandierina(e) sul messaggio');
define('PROC_CANT_CHANGE_MSG_FLD', 'Impossibile cambiare la cartella del messaggio(i)');
define('PROC_CANT_SEND_MSG', 'Impossibile inviare il messaggio.');
define('PROC_CANT_SAVE_MSG', 'Impossibile salvare il messaggio.');
define('PROC_CANT_GET_ACCT_LIST', 'Impossibile ottenere la lista degli account');
define('PROC_CANT_GET_FILTER_LIST', 'Impossibile ottenere la lista dei filtri');

define('PROC_CANT_LEAVE_BLANK', 'Non puoi lasciare i campi * vuoti');

define('PROC_CANT_UPD_FLD', 'Impossibile aggiornare la cartella');
define('PROC_CANT_UPD_FILTER', 'Impossibile aggiornare il filtro');

define('ACCT_CANT_ADD_DEF_ACCT', 'Questa account non può venire aggiunto in quanto è utilizzato come account predefinito da un altro utente.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Lo stato di questo account non può essere trasformato in account predefinito');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Impossibile creare nuovo account (IMAP4 errore di connessione)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Impossibile cancellare l\'ultimo account predefinito');

define('LANG_LoginInfo', 'Informazioni di login');
define('LANG_Email', 'Email');
define('LANG_Login', 'Login');
define('LANG_Password', 'Password');
define('LANG_IncServer', 'Incoming&nbsp;Mail');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Porta');
define('LANG_OutServer', 'Outgoing&nbsp;Mail');
define('LANG_OutPort', 'Porta');
define('LANG_UseSmtpAuth', 'Use&nbsp;SMTP&nbsp;authentication');
define('LANG_SignMe', 'Accedi automaticamente');
define('LANG_Enter', 'Entra');

// interface strings

define('JS_LANG_TitleLogin', 'Login');
define('JS_LANG_TitleMessagesListView', 'Lista messaggi');
define('JS_LANG_TitleMessagesList', 'Lista messaggi');
define('JS_LANG_TitleViewMessage', 'Guarda messaggio');
define('JS_LANG_TitleNewMessage', 'Nuovo messaggio');
define('JS_LANG_TitleSettings', 'Impostazioni');
define('JS_LANG_TitleContacts', 'Contatti');

define('JS_LANG_StandardLogin', 'Standard&nbsp;Login');
define('JS_LANG_AdvancedLogin', 'Advanced&nbsp;Login');

define('JS_LANG_InfoWebMailLoading', 'Attendi il caricamento della webMail&hellip;');
define('JS_LANG_Loading', 'Caricamento&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Attendi il caricamento della lista dei messaggi');
define('JS_LANG_InfoEmptyFolder', 'La cartella è vuota');
define('JS_LANG_InfoPageLoading', 'La pagina sta caricando&hellip;');
define('JS_LANG_InfoSendMessage', 'Il messaggio è stato inviato');
define('JS_LANG_InfoSaveMessage', 'Il messaggio è stato salvato');
define('JS_LANG_InfoHaveImported', 'Importazione completata');
define('JS_LANG_InfoNewContacts', 'Nuovo contatto(i) nella tua lista dei contatti.');
define('JS_LANG_InfoToDelete', 'Per cancellare');
define('JS_LANG_InfoDeleteContent', 'Eliminare prima il contenuto delle cartelle per cancellarle.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Non è permesso cancellare le cartelle non-vuote. Per cancellare le cartelle non selezionabili, cancella prima il loro contenuto.');
define('JS_LANG_InfoRequiredFields', '* campi richiesti');

define('JS_LANG_ConfirmAreYouSure', 'Sei sicuro?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Il messaggio selezionato(i) sarà PERMANENTEMENTE cancellato! Sei sicuro?');
define('JS_LANG_ConfirmSaveSettings', 'Le impostazioni non sono state salvate. Clicca su OK per salvare.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Le impostazioni dei contatti non sono state salvate. Clicca su OK per salvare.');
define('JS_LANG_ConfirmSaveAcctProp', 'Le proprietà dell\'account non sono state salvate. Clicca su OK per salvare.');
define('JS_LANG_ConfirmSaveFilter', 'Le proprietà dei filtri non sono state salvate. Clicca su OK per salvare.');
define('JS_LANG_ConfirmSaveSignature', 'La firma non è stata salvata. Clicca su OK per salvare.');
define('JS_LANG_ConfirmSavefolders', 'Le cartelle non sono state salvate. Clicca su OK per salvare.');
define('JS_LANG_ConfirmHtmlToPlain', 'Attenzione: Cambiando la formattazione di questo messaggio da HTML a testo, andrà persa ogni formattazione del messaggio. Clicca su OK per continuare.');
define('JS_LANG_ConfirmAddFolder', 'Prima di aggiungere la cartella è necessario applicare i cambiamenti. Clicca su OK per salvare.');
define('JS_LANG_ConfirmEmptySubject', 'Il campo dell\'Oggetto è vuoto. Vuoi continuare?');

define('JS_LANG_WarningEmailBlank', 'Non puoi lasciare il campo<br />Email: vuoto');
define('JS_LANG_WarningLoginBlank', 'Non puoi lasciare il campo<br />Login: vuoto');
define('JS_LANG_WarningToBlank', 'Non puoi lasciare: il campo vuoto');
define('JS_LANG_WarningServerPortBlank', 'Non puoi lasciare i campi POP3 e<br />SMTP server/port vuoti');
define('JS_LANG_WarningEmptySearchLine', 'Campo di ricerca vuoto. Per piacere inserisci la stringa che hai bisogno di trovare');
define('JS_LANG_WarningMarkListItem', 'Per piacere seleziona almeno un oggetto della lista');
define('JS_LANG_WarningFolderMove', 'La cartella non può venire spostata poichè questo è un altro livello');
define('JS_LANG_WarningContactNotComplete', 'Per piacere inserisci l\'email o il nome');
define('JS_LANG_WarningGroupNotComplete', 'Per piacere inserisci il nome del gruppo');

define('JS_LANG_WarningEmailFieldBlank', 'Non puoi lasciare il campo Email vuoto');
define('JS_LANG_WarningIncServerBlank', 'Non puoi lasciare il campo POP3(IMAP4) Server vuoto');
define('JS_LANG_WarningIncPortBlank', 'Non puoi lasciare il campo POP3(IMAP4) Server Port vuoto');
define('JS_LANG_WarningIncLoginBlank', 'Non puoi lasciare il campo POP3(IMAP4) Login vuoto');
define('JS_LANG_WarningIncPortNumber', 'Devi specificare un numero positivo nel campo POP3(IMAP4) port.');
define('JS_LANG_DefaultIncPortNumber', 'Il numero di porta predefinita per il POP3(IMAP4) è 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Non puoi lasciare il campo POP3(IMAP4) Password vuoto');
define('JS_LANG_WarningOutPortBlank', 'Non puoi lasciare il campo SMTP Server Port vuoto');
define('JS_LANG_WarningOutPortNumber', 'Devi specificare un numero positivo nel campo SMTP port.');
define('JS_LANG_WarningCorrectEmail', 'Devi specificare un indirizzo e-mail valido.');
define('JS_LANG_DefaultOutPortNumber', 'La porta predefinita per l\'SMTP è la porta 25.');

define('JS_LANG_WarningCsvExtention', 'L\'estensione deve essere .csv');
define('JS_LANG_WarningImportFileType', 'Per favore seleziona l\'applicazione da cui vuoi copiare i tuoi contatti');
define('JS_LANG_WarningEmptyImportFile', 'Per favore seleziona un file cliccando il bottone browse');

define('JS_LANG_WarningContactsPerPage', 'Il valore dei contatti per pagina è un numero positivo');
define('JS_LANG_WarningMessagesPerPage', 'Il valore dei messaggi per pagina è un numero positivo');
define('JS_LANG_WarningMailsOnServerDays', 'Devi specificare un numero positivo nel campo giorni dei messaggi.');
define('JS_LANG_WarningEmptyFilter', 'Per favore inserisci una stringa');
define('JS_LANG_WarningEmptyFolderName', 'Per favore inserisci il nome della cartella');

define('JS_LANG_ErrorConnectionFailed', 'La connessione non è riuscita');
define('JS_LANG_ErrorRequestFailed', 'Il trasferimento dati non è stato completato');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'L\'oggetto XMLHttpRequest è assente');
define('JS_LANG_ErrorWithoutDesc', 'Si è verificato un errore senza descrizione');
define('JS_LANG_ErrorParsing', 'Si è verificato un errore analizzando XML.');
define('JS_LANG_ResponseText', 'Testo di risposta:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Pacchetto XML vuoto');
define('JS_LANG_ErrorImportContacts', 'Errore durante l\'importazione dei contatti');
define('JS_LANG_ErrorNoContacts', 'Nessun contatto da importare');
define('JS_LANG_ErrorCheckMail', 'La ricezione dei messaggi è terminata a causa di un errore.Probabilmente non tutti i messaggi sono stati ricevuti.');

define('JS_LANG_LoggingToServer', 'Accesso al server&hellip;');
define('JS_LANG_GettingMsgsNum', 'Ricezione del numero dei messaggi in corso');
define('JS_LANG_RetrievingMessage', 'Ricezione messaggio in corso');
define('JS_LANG_DeletingMessage', 'Eliminazione messaggio in corso');
define('JS_LANG_DeletingMessages', 'Eliminazione messaggio(i) in corso');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', 'Connessione');
define('JS_LANG_Charset', 'Charset');
define('JS_LANG_AutoSelect', 'Auto-Selezione');

define('JS_LANG_Contacts', 'Contatti');
define('JS_LANG_ClassicVersion', 'Versione Standard');
define('JS_LANG_Logout', 'Esci');
define('JS_LANG_Settings', 'Impostazioni');

define('JS_LANG_LookFor', 'Cerca: ');
define('JS_LANG_SearchIn', 'Cerca in: ');
define('JS_LANG_QuickSearch', 'Cerca solo nei campi Da, A e Oggetto (modalità veloce).');
define('JS_LANG_SlowSearch', 'Cerca nell\'intero messaggio');
define('JS_LANG_AllMailFolders', 'Tutte le cartelle Email');
define('JS_LANG_AllGroups', 'Tutti i gruppi');

define('JS_LANG_NewMessage', 'Nuovo messaggio');
define('JS_LANG_CheckMail', 'Controlla Arrivo Nuove Mail');
define('JS_LANG_EmptyTrash', 'Svuota cestino');
define('JS_LANG_MarkAsRead', 'Segna come già letto');
define('JS_LANG_MarkAsUnread', 'Segna come non letto');
define('JS_LANG_MarkFlag', 'Segna bandierina');
define('JS_LANG_MarkUnflag', 'Togli bandierina');
define('JS_LANG_MarkAllRead', 'Segna tutti come letti');
define('JS_LANG_MarkAllUnread', 'Segna tutti come non letti');
define('JS_LANG_Reply', 'Rispondi');
define('JS_LANG_ReplyAll', 'Rispondi a tutti');
define('JS_LANG_Delete', 'Cancella');
define('JS_LANG_Undelete', 'Recupera');
define('JS_LANG_PurgeDeleted', 'Cancella i messaggi rigati');
define('JS_LANG_MoveToFolder', 'Sposta nella cartella');
define('JS_LANG_Forward', 'Inoltra');

define('JS_LANG_HideFolders', 'Nascondi cartelle');
define('JS_LANG_ShowFolders', 'Mosta cartelle');
define('JS_LANG_ManageFolders', 'Gestisci cartelle');
define('JS_LANG_SyncFolder', 'Sincronizza cartella');
define('JS_LANG_NewMessages', 'Nuovi Messaggi');
define('JS_LANG_Messages', 'Messaggio(i)');

define('JS_LANG_From', 'Da');
define('JS_LANG_To', 'A');
define('JS_LANG_Date', 'Data');
define('JS_LANG_Size', 'Dimensione');
define('JS_LANG_Subject', 'Oggetto');

define('JS_LANG_FirstPage', 'Prima pagina');
define('JS_LANG_PreviousPage', 'Pagina precedente');
define('JS_LANG_NextPage', 'Pagina successiva');
define('JS_LANG_LastPage', 'Ultima pagina');

define('JS_LANG_SwitchToPlain', 'Visualizza in formato testo');
define('JS_LANG_SwitchToHTML', 'Visualizza in formato HTML');
define('JS_LANG_AddToAddressBook', 'Aggiungi al libro degli indirizzi');
define('JS_LANG_ClickToDownload', 'Clicca per scaricare');
define('JS_LANG_View', 'Visualizza');
define('JS_LANG_ShowFullHeaders', 'Visualizza intestazione completa');
define('JS_LANG_HideFullHeaders', 'Nascondi intestazione completa');

define('JS_LANG_MessagesInFolder', 'Messaggio(i) nella cartella');
define('JS_LANG_YouUsing', 'Stai utilizzando');
define('JS_LANG_OfYour', 'del tuo');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Invia');
define('JS_LANG_SaveMessage', 'Salva');
define('JS_LANG_Print', 'Stampa');
define('JS_LANG_PreviousMsg', 'Messaggio precedente');
define('JS_LANG_NextMsg', 'Messaggio successivo');
define('JS_LANG_AddressBook', 'Libro indirizzi');
define('JS_LANG_ShowBCC', 'Mostra BCC');
define('JS_LANG_HideBCC', 'Nascondi BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Rispondi&nbsp;A');
define('JS_LANG_AttachFile', 'Allega file');
define('JS_LANG_Attach', 'Allega');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Messaggio originale');
define('JS_LANG_Sent', 'Inviato');
define('JS_LANG_Fwd', 'Inoltra');
define('JS_LANG_Low', 'Bassa');
define('JS_LANG_Normal', 'Normale');
define('JS_LANG_High', 'Alta');
define('JS_LANG_Importance', 'Importanza');
define('JS_LANG_Close', 'Chiudi');

define('JS_LANG_Common', 'Generali');
define('JS_LANG_EmailAccounts', 'Account E-mail');

define('JS_LANG_MsgsPerPage', 'Messaggi per pagina');
define('JS_LANG_DisableRTE', 'Disabilita il rich-text editor');
define('JS_LANG_Skin', 'Sfondo');
define('JS_LANG_DefCharset', 'charset Predefinito');
define('JS_LANG_DefCharsetInc', 'Default charset d\'ingresso');
define('JS_LANG_DefCharsetOut', 'Default charset d\'uscita');
define('JS_LANG_DefTimeOffset', 'Time offset');
define('JS_LANG_DefLanguage', 'Lingua predefinita');
define('JS_LANG_DefDateFormat', 'Formato data predefinito');
define('JS_LANG_ShowViewPane', 'Lista messaggi con pannello anteprima');
define('JS_LANG_Save', 'Salva');
define('JS_LANG_Cancel', 'Annulla');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Rimuovi');
define('JS_LANG_AddNewAccount', 'Aggiungi nuovo account');
define('JS_LANG_Signature', 'Firma');
define('JS_LANG_Filters', 'Filtri');
define('JS_LANG_Properties', 'Proprietà');
define('JS_LANG_UseForLogin', 'Usa le proprietà di questo account (login e password) per il login');
define('JS_LANG_MailFriendlyName', 'Tuo nome');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Mail in ingresso');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Porta');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Password');
define('JS_LANG_MailOutHost', 'Mail in uscita');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Login');
define('JS_LANG_MailOutPass', 'SMTP Password');
define('JS_LANG_MailOutAuth1', 'Usa autenticazione SMTP');
define('JS_LANG_MailOutAuth2', '(Puo lasciare i campi SMTP login/password vuoti, se sono uguali ai campi POP3/IMAP4 login/password)');
define('JS_LANG_UseFriendlyNm1', 'Usa il Friendly Name nel campo "Da:"');
define('JS_LANG_UseFriendlyNm2', '(Tuo nome &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Ricevi/Sincronizza Mails nella fase di Login');
define('JS_LANG_MailMode0', 'Cancella dal server i messaggi ricevuti');
define('JS_LANG_MailMode1', 'Tieni i messaggi sul server');
define('JS_LANG_MailMode2', 'Conserva i messaggi sul server per');
define('JS_LANG_MailsOnServerDays', 'Giorno(i)');
define('JS_LANG_MailMode3', 'Cancella i messaggi dal server quando vengono rimossi dal Cestino');
define('JS_LANG_InboxSyncType', 'Tipo di Sincronizzazione della Inbox');

define('JS_LANG_SyncTypeNo', 'Non Sincronizzare');
define('JS_LANG_SyncTypeNewHeaders', 'Nuove Intestazioni');
define('JS_LANG_SyncTypeAllHeaders', 'Tutte le Intestazioni');
define('JS_LANG_SyncTypeNewMessages', 'Nuovi Messaggi');
define('JS_LANG_SyncTypeAllMessages', 'Tutti i Messaggi');
define('JS_LANG_SyncTypeDirectMode', 'Modalità Diretta');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Solo intestazioni');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'I messaggi per intero');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Modalità Diretta');

define('JS_LANG_DeleteFromDb', 'Cancella i messaggi dal database se non sono più presenti sul server');

define('JS_LANG_EditFilter', 'Modifica Filtro');
define('JS_LANG_NewFilter', 'Aggiungi Nuovo Filtro');
define('JS_LANG_Field', 'Campo');
define('JS_LANG_Condition', 'Condizione');
define('JS_LANG_ContainSubstring', 'Contiene la stringa');
define('JS_LANG_ContainExactPhrase', 'Contiene la frase esatta');
define('JS_LANG_NotContainSubstring', 'Non contiene la stringa');
define('JS_LANG_FilterDesc_At', 'A');
define('JS_LANG_FilterDesc_Field', 'Campo');
define('JS_LANG_Action', 'Azione');
define('JS_LANG_DoNothing', 'Non fare niente');
define('JS_LANG_DeleteFromServer', 'Cancella dal server Immediatamente');
define('JS_LANG_MarkGrey', 'Contrassegna in Grigio');
define('JS_LANG_Add', 'Aggiungi');
define('JS_LANG_OtherFilterSettings', 'Altre impostazioni del filtro');
define('JS_LANG_ConsiderXSpam', 'Considera X-Spam intestazioni');
define('JS_LANG_Apply', 'Applica');

define('JS_LANG_InsertLink', 'Inserisci Link');
define('JS_LANG_RemoveLink', 'Rimuovi Link');
define('JS_LANG_Numbering', 'Numerazione');
define('JS_LANG_Bullets', 'Bullets');
define('JS_LANG_HorizontalLine', 'Linea Orizzontale');
define('JS_LANG_Bold', 'Grassetto');
define('JS_LANG_Italic', 'Corsivo');
define('JS_LANG_Underline', 'Sottolineato');
define('JS_LANG_AlignLeft', 'Allinea a Sinistra');
define('JS_LANG_Center', 'Centra');
define('JS_LANG_AlignRight', 'Allinea a Destra');
define('JS_LANG_Justify', 'Giustifica');
define('JS_LANG_FontColor', 'Colore Carattere');
define('JS_LANG_Background', 'Sfondo');
define('JS_LANG_SwitchToPlainMode', 'Converti in formato Plain Text');
define('JS_LANG_SwitchToHTMLMode', 'Converti in formato HTML');

define('JS_LANG_Folder', 'Cartella');
define('JS_LANG_Msgs', 'Messaggi');
define('JS_LANG_Synchronize', 'Sincronizza');
define('JS_LANG_ShowThisFolder', 'Mostra Questa Cartella');
define('JS_LANG_Total', 'Totale');
define('JS_LANG_DeleteSelected', 'Cancella selezionati');
define('JS_LANG_AddNewFolder', 'Aggiungi Nuova Cartella');
define('JS_LANG_NewFolder', 'Nuova Cartella');
define('JS_LANG_ParentFolder', 'Sottocartella');
define('JS_LANG_NoParent', 'No Sottocartella');
define('JS_LANG_FolderName', 'Nome Cartella');

define('JS_LANG_ContactsPerPage', 'Contatti per pagina');
define('JS_LANG_WhiteList', 'Libro dei contatti come White List');

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

define('JS_LANG_DateDefault', 'Default');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Mese (01 Gen)');
define('JS_LANG_DateAdvanced', 'Avanzato');

define('JS_LANG_NewContact', 'Nuovo Contatto');
define('JS_LANG_NewGroup', 'Nuovo Gruppo');
define('JS_LANG_AddContactsTo', 'Aggiungi Contatti a');
define('JS_LANG_ImportContacts', 'Importa Contatti');

define('JS_LANG_Name', 'Nome');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Email Predefinita');
define('JS_LANG_NotSpecifiedYet', 'Non ancora specificato');
define('JS_LANG_ContactName', 'Nome');
define('JS_LANG_Birthday', 'Data di nascita');
define('JS_LANG_Month', 'Mese');
define('JS_LANG_January', 'Gennaio');
define('JS_LANG_February', 'Febbraio');
define('JS_LANG_March', 'Marzo');
define('JS_LANG_April', 'Aprile');
define('JS_LANG_May', 'Maggio');
define('JS_LANG_June', 'Giugno');
define('JS_LANG_July', 'Luglio');
define('JS_LANG_August', 'Agosto');
define('JS_LANG_September', 'Settembre');
define('JS_LANG_October', 'Ottobre');
define('JS_LANG_November', 'Novembre');
define('JS_LANG_December', 'Dicembre');
define('JS_LANG_Day', 'Giorno');
define('JS_LANG_Year', 'Anno');
define('JS_LANG_UseFriendlyName1', 'Usa Friendly Name');
define('JS_LANG_UseFriendlyName2', '(per esempio, John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Personale');
define('JS_LANG_PersonalEmail', 'E-mail Personale');
define('JS_LANG_StreetAddress', 'Indirizzo');
define('JS_LANG_City', 'Città');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Stato/Provincia');
define('JS_LANG_Phone', 'Telefono');
define('JS_LANG_ZipCode', 'CAP');
define('JS_LANG_Mobile', 'Cellulare');
define('JS_LANG_CountryRegion', 'Paese/Regione');
define('JS_LANG_WebPage', 'Pagina Web');
define('JS_LANG_Go', 'Vai');
define('JS_LANG_Home', 'Casa');
define('JS_LANG_Business', 'Lavoro');
define('JS_LANG_BusinessEmail', 'E-mail di Lavoro');
define('JS_LANG_Company', 'Società');
define('JS_LANG_JobTitle', 'Professione');
define('JS_LANG_Department', 'Reparto');
define('JS_LANG_Office', 'Ufficio');
define('JS_LANG_Pager', 'Impaginatore');
define('JS_LANG_Other', 'Altro');
define('JS_LANG_OtherEmail', 'E-mail alternativa');
define('JS_LANG_Notes', 'Note');
define('JS_LANG_Groups', 'Gruppi');
define('JS_LANG_ShowAddFields', 'Mostra campi aggiuntivi');
define('JS_LANG_HideAddFields', 'Nascondi campi aggiuntivi');
define('JS_LANG_EditContact', 'Modifica informazioni di contatto');
define('JS_LANG_GroupName', 'Nome del gruppo');
define('JS_LANG_AddContacts', 'Aggiungi contatti');
define('JS_LANG_CommentAddContacts', '(Se stai per inserire piu di un indirizzo, per favore separali con la virgola)');
define('JS_LANG_CreateGroup', 'Crea Gruppo');
define('JS_LANG_Rename', 'rinomina');
define('JS_LANG_MailGroup', 'Gruppo Mail');
define('JS_LANG_RemoveFromGroup', 'Rimuovi dal Gruppo');
define('JS_LANG_UseImportTo', 'Usa "Importa" per copiare i tuoi contatti da Microsoft Outlook o Microsoft Outlook Express nella tua lista dei contatti.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Seleziona il file (con formato .CSV) che desideri importare');
define('JS_LANG_Import', 'Importa');
define('JS_LANG_ContactsMessage', 'Questa è la pagina dei contatti!!!');
define('JS_LANG_ContactsCount', 'contatto(i)');
define('JS_LANG_GroupsCount', 'gruppo(i)');

// webmail 4.1 constants
define('PicturesBlocked', 'Per una questione di sicurezza le immagini nel messaggio sono state bloccate.');
define('ShowPictures', 'Mostra immagini');
define('ShowPicturesFromSender', 'Mostra sempre le immagini nei messaggi ricevuti da parte di questo mittente');
define('AlwaysShowPictures', 'Mostra sempre le immagini nei messaggi');

define('TreatAsOrganization', 'Considerare come una organizzazione');

define('WarningGroupAlreadyExist', 'Un gruppo con questo nome è già presente. Per favore inserisci un altro nome.');
define('WarningCorrectFolderName', 'E\' necessario specificare un nome corretto per la cartella.');
define('WarningLoginFieldBlank', 'Non è possibile lasciare il campo Login vuoto.');
define('WarningCorrectLogin', 'Devi specificare un nome di Login corretto.');
define('WarningPassBlank', 'Non è possibile lasciare il campo Password vuoto.');
define('WarningCorrectIncServer', 'E\' necessario specificare un indirizzo POP3(IMAP) server corretto.');
define('WarningCorrectSMTPServer', 'E\' necessario specificare un indirizzo di Mail in uscita corretto.');
define('WarningFromBlank', 'Non è possibile lasciare il campo Da: vuoto.');
define('WarningAdvancedDateFormat', 'Per piacere specifica un formato Data-Ora.');

define('AdvancedDateHelpTitle', 'Data Avanzata');
define('AdvancedDateHelpIntro', 'Quando il campo &quot;Avanzate&quot; è selezionato, è possibile utilizzare la casella di testo per impostare il formato della data, che sarà visualizzato sull\'AfterLogic WebMail Pro. Le seguenti opzioni sono utilizzate per questo proposito con il carattere delimitatore \':\' o \'/\':');
define('AdvancedDateHelpConclusion', 'Per esempio, se è stato specificato il valore &quot;mm/dd/yyyy&quot; nella casella di testo &quot;Advanzate&quot; campo, la data viene visualizzata con formato mese/giorno/anno (i.e. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Giorno del mese (da 1 a 31)');
define('AdvancedDateHelpNumericMonth', 'Mese (da 1 a 12)');
define('AdvancedDateHelpTextualMonth', 'Mese (da Gen a Dic)');
define('AdvancedDateHelpYear2', 'Anno, 2 cifre');
define('AdvancedDateHelpYear4', 'Anno, 4 cifre');
define('AdvancedDateHelpDayOfYear', 'Giorno dell\'Anno (da 1 a 366)');
define('AdvancedDateHelpQuarter', 'Quarto');
define('AdvancedDateHelpDayOfWeek', 'Giorno della Settimana (da Lun a Dom)');
define('AdvancedDateHelpWeekOfYear', 'Settimana dell\'Anno (da 1 a 53)');

define('InfoNoMessagesFound', 'Nessun messaggio trovato.');
define('ErrorSMTPConnect', 'Impossibile connettersi al server SMTP. Controlla le impostazioni del server SMTP.');
define('ErrorSMTPAuth', 'Username o Password errate. Autenticazione fallita.');
define('ReportMessageSent', 'Il tuo messaggio è stato inviato.');
define('ReportMessageSaved', 'Il tuo messaggio è stato salvato.');
define('ErrorPOP3Connect', 'Impossibile connettersi al server POP3, controllare le impostazioni del server POP3.');
define('ErrorIMAP4Connect', 'Impossibile connettersi al server IMAP4, controllare le impostazioni del server IMAP4.');
define('ErrorPOP3IMAP4Auth', 'email/login e/o password sbagliata. Autenticazione fallita.');
define('ErrorGetMailLimit', 'Spiacente, la tua casella email è piena.');

define('ReportSettingsUpdatedSuccessfuly', 'Le impostazioni sono state aggiornate correttamente.');
define('ReportAccountCreatedSuccessfuly', 'L\'Account è stato creato correttamente.');
define('ReportAccountUpdatedSuccessfuly', 'L\'Account è stato aggiornato correttamente.');
define('ConfirmDeleteAccount', 'Sei sicuro di voler cancellare l\'Account?');
define('ReportFiltersUpdatedSuccessfuly', 'I filtri sono stati aggiornati correttamente.');
define('ReportSignatureUpdatedSuccessfuly', 'La firma è stata aggiornata correttamente.');
define('ReportFoldersUpdatedSuccessfuly', 'Le cartelle sono state aggiornate correttamente.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Le impostazioni dei contatti sono state aggiornate correttamente.');

define('ErrorInvalidCSV', 'Il file CSV che hai selezionato ha un formato non valido.');
//Il gruppo "guies" è stato aggiunto correttamente.
define('ReportGroupSuccessfulyAdded1', 'Il gruppo');
define('ReportGroupSuccessfulyAdded2', 'E\' stato aggiunto correttamente.');
define('ReportGroupUpdatedSuccessfuly', 'Il gruppo è stato aggiornato correttamente.');
define('ReportContactSuccessfulyAdded', 'Il contatto è stato aggiunto correttamente.');
define('ReportContactUpdatedSuccessfuly', 'Il contatto è stato aggiornato correttamente.');
//Contatto(i) è stato aggiunto al gruppo "friends".
define('ReportContactAddedToGroup', 'Contatto(i) è stato aggiunto al gruppo');
define('AlertNoContactsGroupsSelected', 'Nessun contatto o gruppo selezionato.');

define('InfoListNotContainAddress', 'Se la lista non contiente l\'indirizzo che stai cercando, prova a inserire le iniziali.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Modalità Diretta. La WebMail accede direttamente al Server Mail.');

define('FolderInbox', 'Posta in arrivo');
define('FolderSentItems', 'Posta inviata');
define('FolderDrafts', 'Bozze');
define('FolderTrash', 'Posta eliminata');

define('FileLargerAttachment', 'La dimensione del file supera la dimensione massima dei file allegati.');
define('FilePartiallyUploaded', 'Solo una parte del file è stata caricata a causa di un errore sconosciuto.');
define('NoFileUploaded', 'Nessun file è stato caricato.');
define('MissingTempFolder', 'La cartella temporanea non è presente.');
define('MissingTempFile', 'Il file temporaneo non è presente.');
define('UnknownUploadError', 'Si è verificato un errore di caricamento sconosciuto.');
define('FileLargerThan', 'Errore di caricamento file. Molto probabilmente, il file è più grosso di ');
define('PROC_CANT_LOAD_DB', 'Impossibile connettersi al database.');
define('PROC_CANT_LOAD_LANG', 'Impossibile trovare il file richiesto.');
define('PROC_CANT_LOAD_ACCT', 'L\'account non esiste, potrebbe esser stato cancellato.');

define('DomainDosntExist', 'Il seguente dominio non esiste sul mail server.');
define('ServerIsDisable', 'L\'utilizzo del server mail è stato proibito dall\'amministratore.');

define('PROC_ACCOUNT_EXISTS', 'L\'account non può venir creato in quanto già esistente.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Impossibile ottenere il numero dei messaggi nella cartella.');
define('PROC_CANT_MAIL_SIZE', 'Impossibile ottenere le dimensioni di immagazzinamento dell\'email.');

define('Organization', 'Organizzazione');
define('WarningOutServerBlank', 'Non è possibile lasciare il campo Mail in Uscita vuoto');

//
define('JS_LANG_Refresh', 'Aggiorna');
define('JS_LANG_MessagesInInbox', 'Messaggio(i) in Posta in arrivo');
define('JS_LANG_InfoEmptyInbox', 'Posta in arrivo vuota');

// webmail 4.2 constants
define('BackToList', 'Torna alla Lista');
define('InfoNoContactsGroups', 'Nessun contatto o gruppo.');
define('InfoNewContactsGroups', 'E\' possibile creare i nuovi contatti/gruppi o importarli da un file con estensione .CSV in formato MS Outlook.');
define('DefTimeFormat', 'Formato data/tempo predefinito');
define('SpellNoSuggestions', 'Nessun suggerimento');
define('SpellWait', 'Attendi&hellip;');

define('InfoNoMessageSelected', 'Nessun messaggio selezionato.');
define('InfoSingleDoubleClick', 'Cliccando sul messaggio una volta è possibile visualizzarlo nel formato di anteprima, oppure è sufficiente eseguire un doppio click per visualizzarlo in formato schermo intero.');

// calendar
define('TitleDay', 'Visualizzazione Giorno');
define('TitleWeek', 'Visualizzazione Settimana');
define('TitleMonth', 'Visualizzazione Mese');

define('ErrorNotSupportBrowser', 'Il Calendario AfterLogic non è supportato dal tuo browser. Per piacere usa FireFox 2.0 o una versione successiva , Opera 9.0 o una versione successiva, Internet Explorer 6.0 o una versione successiva, Safari 3.0.2 o una versione successiva.');
define('ErrorTurnedOffActiveX', 'Il supporto ActiveX è disabilitato. <br/>E\' necessario attivarlo per utilizzare questa applicazione.');

define('Calendar', 'Calendario');

define('TabDay', 'Giorno');
define('TabWeek', 'Settimana');
define('TabMonth', 'Mese');

define('ToolNewEvent', 'Nuovo&nbsp;Evento');
define('ToolBack', 'Indietro');
define('ToolToday', 'Oggi');
define('AltNewEvent', 'Nuovo Evento');
define('AltBack', 'Indietro');
define('AltToday', 'Oggi');
define('CalendarHeader', 'Calendario');
define('CalendarsManager', 'Gestione Calendario');

define('CalendarActionNew', 'Nuovo Calendario');
define('EventHeaderNew', 'Nuovo Evento');
define('CalendarHeaderNew', 'Nuovo Calendario');

define('EventSubject', 'Oggetto');
define('EventCalendar', 'Calendario');
define('EventFrom', 'Da');
define('EventTill', 'A');
define('CalendarDescription', 'Descrizione');
define('CalendarColor', 'Colore');
define('CalendarName', 'Nome Calendario');
define('CalendarDefaultName', 'Mio Calendario');

define('ButtonSave', 'Salva');
define('ButtonCancel', 'Annulla');
define('ButtonDelete', 'Elimina');

define('AltPrevMonth', 'Mese Precedente');
define('AltNextMonth', 'Mese Successivo');

define('CalendarHeaderEdit', 'Personalizza Calendario');
define('CalendarActionEdit', 'Personalizza Calendario');
define('ConfirmDeleteCalendar', 'Sei sicuro di voler cancellare il calendario');
define('InfoDeleting', 'Eliminazione in corso&hellip;');
define('WarningCalendarNameBlank', 'Non è possibile lasciare il campo Nome Calendario vuoto.');
define('ErrorCalendarNotCreated', 'Calendario non creato.');
define('WarningSubjectBlank', 'Non è possibile lasciare il campo Oggetto vuoto.');
define('WarningIncorrectTime', 'La data specificata contiene caratteri non supportati.');
define('WarningIncorrectFromTime', 'La data Da non è corretta.');
define('WarningIncorrectTillTime', 'La data A non è corretta.');
define('WarningStartEndDate', 'La data di fine deve essere maggiore o uguale a quella di inizio.');
define('WarningStartEndTime', 'L\'ora di fine deve essere maggiore di quella di inizio.');
define('WarningIncorrectDate', 'La data deve essere corretta.');
define('InfoLoading', 'Caricamento&hellip;');
define('EventCreate', 'Crea evento');
define('CalendarHideOther', 'Nascondi gli altri calendari');
define('CalendarShowOther', 'Visualizza gli altri calendari');
define('CalendarRemove', 'Elimina Calendario');
define('EventHeaderEdit', 'Personalizza Evento');

define('InfoSaving', 'Salvataggio in corso&hellip;');
define('SettingsDisplayName', 'Visualizza Nome');
define('SettingsTimeFormat', 'Formato Ora');
define('SettingsDateFormat', 'Formato Data');
define('SettingsShowWeekends', 'Visualizza fine settimana');
define('SettingsWorkdayStarts', 'Inizio giorno feriale');
define('SettingsWorkdayEnds', 'fine');
define('SettingsShowWorkday', 'Mostra giorno feriale');
define('SettingsWeekStartsOn', 'La settimana inizia il');
define('SettingsDefaultTab', 'Tab Predefinita');
define('SettingsCountry', 'Paese');
define('SettingsTimeZone', 'Fascia oraria');
define('SettingsAllTimeZones', 'Tutte le fasce orarie');

define('WarningWorkdayStartsEnds', 'L\'ora del \'giorno feriale finale\' deve essere maggiore dell\'ora del \'giorno feriale finale\'');
define('ReportSettingsUpdated', 'Le impostazioni sono state aggiornate correttamente.');

define('SettingsTabCalendar', 'Calendario');

define('FullMonthJanuary', 'Gennaio');
define('FullMonthFebruary', 'Febbraio');
define('FullMonthMarch', 'Marzo');
define('FullMonthApril', 'Aprile');
define('FullMonthMay', 'Maggio');
define('FullMonthJune', 'Giugno');
define('FullMonthJuly', 'Luglio');
define('FullMonthAugust', 'Agosto');
define('FullMonthSeptember', 'Settembre');
define('FullMonthOctober', 'Ottobre');
define('FullMonthNovember', 'Novembre');
define('FullMonthDecember', 'Dicembre');

define('ShortMonthJanuary', 'Gen');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Apr');
define('ShortMonthMay', 'Mag');
define('ShortMonthJune', 'Giu');
define('ShortMonthJuly', 'Lug');
define('ShortMonthAugust', 'Ago');
define('ShortMonthSeptember', 'Set');
define('ShortMonthOctober', 'Ott');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dic');

define('FullDayMonday', 'Lunedi');
define('FullDayTuesday', 'Martedi');
define('FullDayWednesday', 'Mercoledi');
define('FullDayThursday', 'Giovedi');
define('FullDayFriday', 'Venerdi');
define('FullDaySaturday', 'Sabato');
define('FullDaySunday', 'Domenica');

define('DayToolMonday', 'Lun');
define('DayToolTuesday', 'Mar');
define('DayToolWednesday', 'Mer');
define('DayToolThursday', 'Gio');
define('DayToolFriday', 'Ven');
define('DayToolSaturday', 'Sab');
define('DayToolSunday', 'Dom');

define('CalendarTableDayMonday', 'L');
define('CalendarTableDayTuesday', 'M');
define('CalendarTableDayWednesday', 'M');
define('CalendarTableDayThursday', 'G');
define('CalendarTableDayFriday', 'V');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'D');

define('ErrorParseJSON', 'La risposta JSON ricevuta dal server non può essere analizzata.');

define('ErrorLoadCalendar', 'Impossibile caricare i calendari');
define('ErrorLoadEvents', 'Impossibile caricare gli eventi');
define('ErrorUpdateEvent', 'Impossibile salvare gli eventi');
define('ErrorDeleteEvent', 'Impossibile cancellare gli eventi');
define('ErrorUpdateCalendar', 'Impossibile salvare il calendario');
define('ErrorDeleteCalendar', 'Impossibile eliminare il calendario');
define('ErrorGeneral', 'Si è verificato un errore sul server. Riprova piu tardi.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Condividi e pubblica calendario');
define('ShareActionEdit', 'Condividi e pubblica calendario');
define('CalendarPublicate', 'Consenti accesso pubblico a questo calendario');
define('CalendarPublicationLink', 'Collegamento');
define('ShareCalendar', 'Condividi questo calendario');
define('SharePermission1', 'E\' possibile effettuare modifiche e gestire la condivisione');
define('SharePermission2', 'E\' possibile personalizzare gli eventi');
define('SharePermission3', 'E\' possibile visualizzare tutti i dettagli degli eventi');
define('SharePermission4', 'E\' possibile visualizzare solo liberi/occupati (nascondi dettagli)');
define('ButtonClose', 'Chiudi');
define('WarningEmailFieldFilling', 'Come prima cosa compilare il campo e-mail');
define('EventHeaderView', 'Visualizza Evento');
define('ErrorUpdateSharing', 'Impossibile salvare i dati condivisi e pubblicati');
define('ErrorUpdateSharing1', 'Impossibile condividere con l\'utente %s in quanto non esiste');
define('ErrorUpdateSharing2', 'Impossibile condividere questo calendario con l\'utente %s');
define('ErrorUpdateSharing3', 'Questo calendario risulta già condiviso con l\'utente %s');
define('Title_MyCalendars', 'Miei Calendari');
define('Title_SharedCalendars', 'Calendari condivisi');
define('ErrorGetPublicationHash', 'Impossibile creare il collegamento di pubblicazione');
define('ErrorGetSharing', 'Impossibile aggiungere condivisione');
define('CalendarPublishedTitle', 'Questo calendario è pubblicato');
define('RefreshSharedCalendars', 'Aggiorna calendari condivisi');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Membri');

define('ReportMessagePartDisplayed', 'Tenere conto del fatto che solo una parte del messaggio viene visualizzato.');
define('ReportViewEntireMessage', 'Per visualizzare il contenuto dell\'intero messaggio,');
define('ReportClickHere', 'Clicca qui');
define('ErrorContactExists', 'Un contatto con lo stesso nome ed indirizzo e-mail risulta già esistente.');

define('Attachments', 'Allegati');

define('InfoGroupsOfContact', 'I gruppi di cui il contatto è membro sono contrassegnati con i checkmarks.');
define('AlertNoContactsSelected', 'Nessun contatto selezionato.');
define('MailSelected', 'Indirizzi Mail selezionati');
define('CaptionSubscribed', 'Sottoscritto');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Non è Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Contatto Mail');
define('ContactViewAllMails', 'Visualizza tutte le email con questo indirizzo');
define('ContactsMailThem', 'Invia loro e-mail');
define('DateToday', 'Oggi');
define('DateYesterday', 'Ieri');
define('MessageShowDetails', 'Visualizza dettagli');
define('MessageHideDetails', 'Nascondi dettagli');
define('MessageNoSubject', 'Nessun oggetto');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'A');
define('SearchClear', 'Ricerca Pulita');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Ricerca per "#s" nella cartella #f:');
define('SearchResultsInAllFolders', 'Risultati della ricerca per "#s" in tutte le cartelle:');
define('AutoresponderTitle', 'Autorisponditore');
define('AutoresponderEnable', 'Abilita autorisponditore');
define('AutoresponderSubject', 'Oggetto');
define('AutoresponderMessage', 'Messaggio');
define('ReportAutoresponderUpdatedSuccessfuly', 'L\'autorisponditore è stato aggiornato correttamente.');
define('FolderQuarantine', 'Quarantena');

//calendar
define('EventRepeats', 'Ripeti');
define('NoRepeats', 'Non ripetere');
define('DailyRepeats', 'Giornalmente');
define('WorkdayRepeats', 'Ogni settimana (Lun. - Ven.)');
define('OddDayRepeats', 'Ogni Lun., Mer. e Ven.');
define('EvenDayRepeats', 'Ogni Mar. e Gio.');
define('WeeklyRepeats', 'Settimanalmente');
define('MonthlyRepeats', 'Mensilmente');
define('YearlyRepeats', 'Annualmente');
define('RepeatsEvery', 'Ripeti ogni');
define('ThisInstance', 'Solo questa istanza');
define('AllEvents', 'Tutti gli eventi della serie');
define('AllFollowing', 'Tutti i seguenti');
define('ConfirmEditRepeatEvent', 'Vuoi cambiare solo questo evento, tutti gli eventi, o questo eventi e gli eventi futuri nella serie?');
define('RepeatEventHeaderEdit', 'Modifica evento ricorrente');
define('First', 'Primo');
define('Second', 'Secondo');
define('Third', 'Terzo');
define('Fourth', 'Quarto');
define('Last', 'Ultimo');
define('Every', 'Tutti');
define('SetRepeatEventEnd', 'Imposta data di fine');
define('NoEndRepeatEvent', 'Nessuna data di fine');
define('EndRepeatEventAfter', 'Finisci dopo');
define('Occurrences', 'Ricorrenze');
define('EndRepeatEventBy', 'Finisci da');
define('EventCommonDataTab', 'Dettagli principali');
define('EventRepeatDataTab', 'Dettagli ricorrenze');
define('RepeatEventNotPartOfASeries', 'Questo evento è cambiato e non fa parte di una serie.');
define('UndoRepeatExclusion', 'Annulla cambiamenti da includere nella serie.');

define('MonthMoreLink', '%d in piu...');
define('NoNewSharedCalendars', 'Nessun nuovo calendario');
define('NNewSharedCalendars', '%d nuovi calendari trovati');
define('OneNewSharedCalendars', '1 nuovo calendario trovato');
define('ConfirmUndoOneRepeat', 'Vuoi ripristinare questo evento nella serie?');

define('RepeatEveryDayInfin', 'Ogni giorno');
define('RepeatEveryDayTimes', 'Ogni giorno, %TIMES% volte');
define('RepeatEveryDayUntil', 'Ogni giorno, fino a %UNTIL%');
define('RepeatDaysInfin', 'Ogni %PERIOD% giorni');
define('RepeatDaysTimes', 'Ogni %PERIOD% giorni, %TIMES% volte');
define('RepeatDaysUntil', 'Ogni %PERIOD% giorno, fino a %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Ogni settimana nei giorni della settimana');
define('RepeatEveryWeekWeekdaysTimes', 'Ogni settimana nei giorni della settimana, %TIMES% volte');
define('RepeatEveryWeekWeekdaysUntil', 'Ogni settimana nei giorni della settimana, fino a %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Ogni %PERIOD% settimane nei giorni della settimana');
define('RepeatWeeksWeekdaysTimes', 'Ogni %PERIOD% settimane nei giorni della settimana, %TIMES% volte');
define('RepeatWeeksWeekdaysUntil', 'Ogni %PERIOD% settimane nei giorni della settimana, fino a %UNTIL%');

define('RepeatEveryWeekInfin', 'Ogni settimana nel giorno di %DAYS%');
define('RepeatEveryWeekTimes', 'Ogni settimana nel giorno di %DAYS%, %TIMES% volte');
define('RepeatEveryWeekUntil', 'Ogni settimana nel giorno di %DAYS%, fino a %UNTIL%');
define('RepeatWeeksInfin', 'Ogni %PERIOD% settimane nel giorno di %DAYS%');
define('RepeatWeeksTimes', 'Ogni %PERIOD% settimane nel giorno di %DAYS%, %TIMES% volte');
define('RepeatWeeksUntil', 'Ogni %PERIOD% settimane nel giorno di %DAYS%, fino a %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Ogni mese nel giorno di %DATE%');
define('RepeatEveryMonthDateTimes', 'Ogni mese nel giorno di %DATE%, %TIMES% volte');
define('RepeatEveryMonthDateUntil', 'Ogni mese nel giorno di %DATE%, fino a %UNTIL%');
define('RepeatMonthsDateInfin', 'Ogni %PERIOD% mesi nel giorno di %DATE%');
define('RepeatMonthsDateTimes', 'Ogni %PERIOD% mesi nel giorno di %DATE%, %TIMES% volte');
define('RepeatMonthsDateUntil', 'Ogni %PERIOD% mesi nel giorno di %DATE%, fino a %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Ogni mese nei giorni %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Ogni mese nei giorni %NUMBER% %DAY%, %TIMES% volte');
define('RepeatEveryMonthWDUntil', 'Ogni mese nei giorni %NUMBER% %DAY%, fino a %UNTIL%');
define('RepeatMonthsWDInfin', 'Ogni %PERIOD% mesi nel giorno di %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Ogni %PERIOD% mesi nel giorno di %NUMBER% %DAY%, %TIMES% volte');
define('RepeatMonthsWDUntil', 'Ogni %PERIOD% mesi nel giorno di %NUMBER% %DAY%, fino a %UNTIL%');

define('RepeatEveryYearDateInfin', 'Ogni anno nel giorno %DATE%');
define('RepeatEveryYearDateTimes', 'Ogni anno nel giorno %DATE%, %TIMES% volte');
define('RepeatEveryYearDateUntil', 'Ogni anno nel giorno %DATE%, fino a %UNTIL%');
define('RepeatYearsDateInfin', 'Ogni %PERIOD% anni nel giorno di %DATE%');
define('RepeatYearsDateTimes', 'Ogni %PERIOD% anni nel giorno di %DATE%, %TIMES% volte');
define('RepeatYearsDateUntil', 'Ogni %PERIOD% anni nel giorno di %DATE%, fino a %UNTIL%');

define('RepeatEveryYearWDInfin', 'Ogni anno nei giorni di %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Ogni anno nei giorni di %NUMBER% %DAY%, %TIMES% volte');
define('RepeatEveryYearWDUntil', 'Ogni anno nei giorni di %NUMBER% %DAY%, fino a %UNTIL%');
define('RepeatYearsWDInfin', 'Ogni %PERIOD% anni nel giorno di %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Ogni %PERIOD% anni nel giorno di  %NUMBER% %DAY%, %TIMES% volte');
define('RepeatYearsWDUntil', 'Ogni %PERIOD% anni nel giorno di  %NUMBER% %DAY%, fino a %UNTIL%');

define('RepeatDescDay', 'giorno');
define('RepeatDescWeek', 'settimana');
define('RepeatDescMonth', 'mese');
define('RepeatDescYear', 'anno');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Per piacere specifica la data di fine ricorrenza');
define('WarningWrongUntilDate', 'La data di fine ricorrenza deve essere successiva alla data di inizio ricorrenza');

define('OnDays', 'Nei giorni');
define('CancelRecurrence', 'Cancella ricorrenza');
define('RepeatEvent', 'Ripeti questo evento');

define('Spellcheck', 'Controlla l\'ortografia');
define('LoginLanguage', 'Lingua');
define('LanguageDefault', 'Default');

// webmail 4.5.x new
define('EmptySpam', 'Svuota Spam');
define('Saving', 'Salvataggio in corso&hellip;');
define('Sending', 'Invio in corso&hellip;');
define('LoggingOffFromServer', 'Disconnessione dal server&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Impossibile contrassegnare il/i messaggio/i come spam');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Impossibile contrassegnare il/i messaggio/i come non-spam');
define('ExportToICalendar', 'Esporta a iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Il Suo account è stato disabilitato in quanto il numero massimo di utenti consentiti dalla licenza è stato superato. Per piacere contatti il suo amministratore di sistema.');
define('RepliedMessageTitle', 'Messaggio risposto');
define('ForwardedMessageTitle', 'Messaggio inoltrato');
define('RepliedForwardedMessageTitle', 'Messaggio risposto e inoltrato');
define('ErrorDomainExist', 'L\'utente non può venir creato in quanto il dominio corrispondente non esiste. E\' necessario creare prima il dominio.');

// webmail 4.7
define('RequestReadConfirmation', 'Richiedi Conferma di Lettura');
define('FolderTypeDefault', 'Default');
define('ShowFoldersMapping', 'Permettimi di utilizzare come cartella di sistema un\'altra cartella (e.g. usa MiaCartella come Posta Inviata)');
define('ShowFoldersMappingNote', 'Ad esempio, per cambiare la posizione della Posta Inviata da Posta Inviata a MiaCartella, specificare "Posta Inviata" in "Utilizzo di" "MiaCartella".');
define('FolderTypeMapTo', 'Utilizzo di');

define('ReminderEmailExplanation', 'Questo messaggio è arrivato al tuo account %EMAIL% in quanto hai impostato la notifica di eventi nel calendario: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Apri Calendario');

define('AddReminder', 'Ricordami questo evento');
define('AddReminderBefore', 'Ricordami % prima di questo evento');
define('AddReminderAnd', 'e % prima');
define('AddReminderAlso', 'e anche % prima');
define('AddMoreReminder', 'Più promemoria');
define('RemoveAllReminders', 'Rimuovi tutti i promemoria');
define('ReminderNone', 'Nessuno');
define('ReminderMinutes', 'minuti');
define('ReminderHour', 'ora');
define('ReminderHours', 'ore');
define('ReminderDay', 'giorno');
define('ReminderDays', 'giorni');
define('ReminderWeek', 'settimana');
define('ReminderWeeks', 'settimane');
define('Allday', 'intero giorno');

define('Folders', 'Cartelle');
define('NoSubject', 'Nessun Oggetto');
define('SearchResultsFor', 'Cerca risultati per');

define('Back', 'Indietro');
define('Next', 'Prossimo');
define('Prev', 'Precedente');

define('MsgList', 'Messaggi');
define('Use24HTimeFormat', 'Use formato ora 24 ore');
define('UseCalendars', 'Usa calendari');
define('Event', 'Evento');
define('CalendarSettingsNullLine', 'Nessun calendario');
define('CalendarEventNullLine', 'Nessun evento');
define('ChangeAccount', 'Cambia account');

define('TitleCalendar', 'Calendario');
define('TitleEvent', 'Evento');
define('TitleFolders', 'Cartelle');
define('TitleConfirmation', 'Conferma');

define('Yes', 'Si');
define('No', 'No');

define('EditMessage', 'Modifica Messaggio');

define('AccountNewPassword', 'Nuova password');
define('AccountConfirmNewPassword', 'Conferma nuova password');
define('AccountPasswordsDoNotMatch', 'Le Password non corrispondono.');

define('ContactTitle', 'Titolo');
define('ContactFirstName', 'Nome');
define('ContactSurName', 'Cognome');
define('ContactNickName', 'Nickname');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'ricarica');
define('CaptchaError', 'Testo Captcha non corretto.');

define('WarningInputCorrectEmails', 'Specificare un indirizzo email corretto.');
define('WrongEmails', 'Email non corretta:');

define('ConfirmBodySize1', 'Spiacenti, è stata raggiunta la lunghezza massima per i messaggi di testo.');
define('ConfirmBodySize2', 'caratteri. Tutto ciò che si trova oltre il limite verrà troncato. Fare clic su "Annulla " se si desidera modificare il messaggio.');
define('BodySizeCounter', 'Counter');
define('InsertImage', 'Inserisci Immagine');
define('ImagePath', 'Percorso Immagine');
define('ImageUpload', 'Inserisci');
define('WarningImageUpload', 'Il file che è stato allegato non è un\'immagine. Selezionare un file di immagine.');

define('ConfirmExitFromNewMessage', 'Le modifiche verranno perse se si abbandona la pagina. Vuoi salvare il progetto prima di abbandonare la pagina?');

define('SensivityConfidential', 'Si prega di considerare questo messaggio come Confidenziale');
define('SensivityPrivate', 'Si prega di considerare questo messaggio come Privato');
define('SensivityPersonal', 'Si prega di considerare questo messaggio come Personale');

define('ReturnReceiptTopText', 'Il mittente di questo messaggio ha richiesto di essere avvisato quando si riceve questo messaggio.');
define('ReturnReceiptTopLink', 'Clicca qui per inviare una notifica al mittente.');
define('ReturnReceiptSubject', 'Ricevuta di ritorno (visualizzata)');
define('ReturnReceiptMailText1', 'Questa è una ricevuta di ritorno per il messaggio che avete inviato a');
define('ReturnReceiptMailText2', 'Nota: questa Ricevuta di Ritorno indica solamente che il messaggio è stato visualizzato sul computer del destinatario. Non vi è alcuna garanzia che il destinatario abbia letto o compreso il contenuto del messaggio.');
define('ReturnReceiptMailText3', 'con oggetto');

define('SensivityMenu', 'Classificazione');
define('SensivityNothingMenu', 'Nessuna');
define('SensivityConfidentialMenu', 'Confidenziale');
define('SensivityPrivateMenu', 'Privato');
define('SensivityPersonalMenu', 'Personale');

define('ErrorLDAPonnect', 'Impossibile connettersi al server LDAP.');

define('MessageSizeExceedsAccountQuota', 'Le dimensioni di questo messaggio superano lo quota consentita per l\'account.');
define('MessageCannotSent', 'Il messaggio non può essere inviato.');
define('MessageCannotSaved', 'Il messaggio non può essere salvato.');

define('ContactFieldTitle', 'Campo');
define('ContactDropDownTO', 'TO');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Il Messaggio(i) non può essere spostato nel cestino. Probabilmente la vostra casella è piena. Volete cancellare questo messaggio(i) senza spostarlo?');

define('WarningFieldBlank', 'Questo campo non può essere vuoto.');
define('WarningPassNotMatch', 'Le Password non corrispondono, prego verificare.');
define('PasswordResetTitle', 'Password recovery - step %d');
define('NullUserNameonReset', 'user');
define('IndexResetLink', 'Password dimenticata?');
define('IndexRegLink', 'Account di Registrazione');

define('RegDomainNotExist', 'Il Dominio non esiste.');
define('RegAnswersIncorrect', 'Risposte non corrette.');
define('RegUnknownAdress', 'Indirizzo email sconosciuto.');
define('RegUnrecoverableAccount', 'Il ripristino della Password non può essere applicato a questo indirizzo email.');
define('RegAccountExist', 'Questo indirizzo è già in uso.');
define('RegRegistrationTitle', 'Registrazione');
define('RegName', 'Nome');
define('RegEmail', 'indirizzo e-mail');
define('RegEmailDesc', 'Per esempio, myname@domain.com. Questa informazione sarà usata per accedere nel sistema.');
define('RegSignMe', 'Ricordami');
define('RegSignMeDesc', 'Non chiedere login e password al prossimo accesso al sistema da questo computer.');
define('RegPass1', 'Password');
define('RegPass2', 'Ripeti password ');
define('RegQuestionDesc', 'Per favore, fornire due domande e risposte segrete che conosce solo lei. In caso di password smarrita è possibile utilizzare queste domande al fine di recuperare la password.');
define('RegQuestion1', 'Domanda segreta 1');
define('RegAnswer1', 'Risposta 1');
define('RegQuestion2', 'Domanda segreta 2');
define('RegAnswer2', 'Risposta 2');
define('RegTimeZone', 'Time zone');
define('RegLang', 'Lingua Interfaccia');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Registra');

define('ResetEmail', 'Per favore, fornire la vostra email');
define('ResetEmailDesc', 'Fornire l\'indirizzo email usato per la registrazione.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Invio');
define('ResetQuestion1', 'Domanda segreta 1');
define('ResetAnswer1', 'Risposta');
define('ResetQuestion2', 'Domanda segreta 2');
define('ResetAnswer2', 'Risposta');
define('ResetSubmitStep2', 'Invio');

define('ResetTopDesc1Step2', 'Fornire indirizzo email');
define('ResetTopDesc2Step2', 'Prego confermare la correttezza dei dati.');

define('ResetTopDescStep3', 'Per favore, indicare di seguito la nuova password per l\'indirizzo email.');

define('ResetPass1', 'Nuova password');
define('ResetPass2', 'Ripeti password');
define('ResetSubmitStep3', 'Invio');
define('ResetDescStep4', 'La vostra password è stata cambiata.');
define('ResetSubmitStep4', 'Ritorno');

define('RegReturnLink', 'Ritorna alla schermata di login');
define('ResetReturnLink', 'Ritorna alla schermata di login');

// Appointments
define('AppointmentAddGuests', 'Aggiungi ospiti');
define('AppointmentRemoveGuests', 'Annulla riunione');
define('AppointmentListEmails', 'Inserire indirizzi email separati da virgole e premere su Salva');
define('AppointmentParticipants', 'Partecipanti');
define('AppointmentRefused', 'Rifiutare');
define('AppointmentAwaitingResponse', 'In attesa di risposta');
define('AppointmentInvalidGuestEmail', 'I seguenti indirizzi email degli ospiti non sono validi:');
define('AppointmentOwner', 'Proprietario');

define('AppointmentMsgTitleInvite', 'Invita all\'evento.');
define('AppointmentMsgTitleUpdate', 'L\'Evento è stato modificato.');
define('AppointmentMsgTitleCancel', 'L\'Evento è stato cancellato.');
define('AppointmentMsgTitleRefuse', 'L\'Ospite %guest% ha rifiutato l\'invito');
define('AppointmentMoreInfo', 'Maggiori informazioni');
define('AppointmentOrganizer', 'Organizer');
define('AppointmentEventInformation', 'Informazioni evento');
define('AppointmentEventWhen', 'Quando');
define('AppointmentEventParticipants', 'Partecipanti');
define('AppointmentEventDescription', 'Descrizione');
define('AppointmentEventWillYou', 'Vuoi partecipare');
define('AppointmentAdditionalParameters', 'Parametri addizionali');
define('AppointmentHaventRespond', 'Non ho ancora risposto');
define('AppointmentRespondYes', 'Desidero partecipare');
define('AppointmentRespondMaybe', 'Non sono ancora sicuro');
define('AppointmentRespondNo', 'Non partecipo');
define('AppointmentGuestsChangeEvent', 'Gli ospiti possono cambiare l\'evento');

define('AppointmentSubjectAddStart', 'Hai ricevuto l\'invito per l\'evento ');
define('AppointmentSubjectAddFrom', ' da ');
define('AppointmentSubjectUpdateStart', 'Modifica l\'eventot ');
define('AppointmentSubjectDeleteStart', 'Cancella l\'evento ');
define('ErrorAppointmentChangeRespond', 'Impossibile modificare la risposta all\'appundamento');
define('SettingsAutoAddInvitation', 'Aggiungi inviti al calendario automaticamente');
define('ReportEventSaved', 'Il tuo evento è stato salvato');
define('ReportAppointmentSaved', ' e le notifiche inviate');
define('ErrorAppointmentSend', 'Impossibile inviare gli inviti.');
define('AppointmentEventName', 'Nome:');

// End appointments

define('ErrorCantUpdateFilters', 'Impossibile aggiornare i fitri');

define('FilterPhrase', 'Se non c\'è %field intestazione %condition %string allora %action');
define('FiltersAdd', 'Aggiungi Filtro');
define('FiltersCondEqualTo', 'uguale a');
define('FiltersCondContainSubstr', 'contiene stringa');
define('FiltersCondNotContainSubstr', 'non contiene stringa');
define('FiltersActionDelete', 'cancella messaggio');
define('FiltersActionMove', 'sposta');
define('FiltersActionToFolder', 'a %folder cartella');
define('FiltersNo', 'Nessun filtro specificato');

define('ReminderEmailFriendly', 'promemoria');
define('ReminderEventBegin', 'inizia alle: ');

define('FiltersLoading', 'Caricamento Filtri...');
define('ConfirmMessagesPermanentlyDeleted', 'Tutti i messaggi in questa cartella saranno eliminati definitivamente.');

define('InfoNoNewMessages', 'Non ci sono nuovi messaggi.');
define('TitleImportContacts', 'Importa Contatti');
define('TitleSelectedContacts', 'Selected Contacts');
define('TitleNewContact', 'Nuovo contatto');
define('TitleViewContact', 'Vedi Contact');
define('TitleEditContact', 'Modifica Contatto');
define('TitleNewGroup', 'Nuovo Gruppo');
define('TitleViewGroup', 'Vedi Gruppo');

define('AttachmentComplete', 'Completo.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Autocheck mail ogni');
define('AutoCheckMailIntervalDisableName', 'Off');
define('ReportCalendarSaved', 'Il Calendario è stato salvato.');

define('ContactSyncError', 'Sincronizzazione fallita');
define('ReportContactSyncDone', 'Sincronizzazione completa');

define('MobileSyncUrlTitle', 'Mobile sync URL');
define('MobileSyncLoginTitle', 'Mobile sync login');

define('QuickReply', 'Risposta rapida');
define('SwitchToFullForm', 'Aprire modulo di risposta completa');
define('SortFieldDate', 'Data');
define('SortFieldFrom', 'Da');
define('SortFieldSize', 'Dimensioni');
define('SortFieldSubject', 'Oggetto');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Allegati');
define('SortOrderAscending', 'Ascendente');
define('SortOrderDescending', 'Discendente');
define('ArrangedBy', 'Disposto da');

define('MessagePaneToRight', 'Il riquadro del messaggio è a destra della lista dei messaggi, piuttosto che di seguito');

define('SettingsTabMobileSync', 'Mobile');

define('MobileSyncContactDataBaseTitle', 'Mobile sync database contatti');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync database calendario');
define('MobileSyncTitleText', 'Se desideri sincronizzare il tuo dispositivo portatile abilitato Sync-ML con la WebMail, è possibile utilizzare questi parametri.<br />"Mobile Sync URL" specificare il percorso del server Sync-ML da utilizzare per la sincronizzazione dei dati, "Mobile Sync Login" è il tuo login sul Sync-ML Data Synchronization Server ed usare la propria password alla richiesta. Inoltre, per alcuni dispositivi è necessaio specificare il nome del database dei contatti e del calendario.<br />Use "Mobile sync database contatti" and "Mobile sync database calendario" rispettivamente.');
define('MobileSyncEnableLabel', 'Attiva mobile sync');

define('SearchInputText', 'Cerca');

define('AppointmentEmailExplanation','Questo messaggio è giunto al tuo account %EMAIL% perchè sei stato invito all\'evento da %ORGANAZER%');

define('Searching', 'Ricerca&hellip;');

define('ButtonSetupSpecialFolders', 'Imposta cartelle speciali');
define('ButtonSaveChanges', 'Salva modifiche');
define('InfoPreDefinedFolders', 'Per le cartelle predefinite, usa queste cartelle IMAP');

define('SaveMailInSentItems', 'Salvare anche in Posta Inviata');

define('CouldNotSaveUploadedFile', 'Impossibile salvare il file caricato');

define('AccountOldPassword', 'Password corrente');
define('AccountOldPasswordsDoNotMatch', 'Le Password correnti non corrispondono.');

define('DefEditor', 'Editor di default');
define('DefEditorRichText', 'Rich Text');
define('DefEditorPlainText', 'Plain Text');

define('Layout', 'Layout');

define('TitleNewMessagesCount', '%count% nuovo messaggio(i)');

define('AltOpenInNewWindow', 'Apri in una nuova finestra');

define('SearchByFirstCharAll', 'Tutto');

define('FolderNoUsageAssigned', 'Nessun utilizzo assegnato');

define('InfoSetupSpecialFolders', 'Per impostare le cartelle speciali (come Posta inviata) ed alcune caselle IMAP, cliccare su Imposta cartelle speciali.');

define('FileUploaderClickToAttach', 'Clicca per allegare un file');
define('FileUploaderOrDragNDrop', 'Oppure è sufficiente trascinare e rilasciare il file qui');

define('AutoCheckMailInterval1Minute', '1 minuto');
define('AutoCheckMailInterval3Minutes', '3 minuti');
define('AutoCheckMailInterval5Minutes', '5 minuti');
define('AutoCheckMailIntervalMinutes', 'minuti');

define('ReadAboutCSVLink', 'Leggi campi dal file CSV');

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
