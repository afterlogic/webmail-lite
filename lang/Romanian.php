<?php
// translated by Idesys Networks www.idesys.ro
define('PROC_ERROR_ACCT_CREATE', 'A apărut o eroare în timpul creării contului');
define('PROC_WRONG_ACCT_PWD', 'Parolă greșită!');
define('PROC_CANT_LOG_NONDEF', 'Logare imposibilă în cont');
define('PROC_CANT_INS_NEW_FILTER', 'Imposibil de adăugat filtru nou');
define('PROC_FOLDER_EXIST', 'Numele dosarului exista deja');
define('PROC_CANT_CREATE_FLD', 'Imposibil de creat dosar');
define('PROC_CANT_INS_NEW_GROUP', 'Nu se poate adăuga un grup nou');
define('PROC_CANT_INS_NEW_CONT', 'Nu se poate adăuga un contact nou');
define('PROC_CANT_INS_NEW_CONTS', 'Nu se pot adăuga contacte noi');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Nu se pot adăuga contacte în acest grup');
define('PROC_ERROR_ACCT_UPDATE', 'Eroare la modificarea contului');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Nu se pot modifica setările pentru contacte');
define('PROC_CANT_GET_SETTINGS', 'Nu se pot accesa setările');
define('PROC_CANT_UPDATE_ACCT', 'Nu se poate improspăta contul');
define('PROC_ERROR_DEL_FLD', 'A apărut o eroare la ştergerea dosar(e)');
define('PROC_CANT_UPDATE_CONT', 'Nu se poate actualiza contact');
define('PROC_CANT_GET_FLDS', 'Nu se poate prelua lista de dosare');
define('PROC_CANT_GET_MSG_LIST', 'Nu se poate prelua lista de mesaje');
define('PROC_MSG_HAS_DELETED', 'Acest mesaj a fost deja eliminat din serverul de poştă electronică');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Nu se pot accesa setările pentru contacte');
define('PROC_CANT_LOAD_SIGNATURE', 'Nu se poate încărca semnatura cont');
define('PROC_CANT_GET_CONT_FROM_DB', 'Nu se poate lua contact din Baza de Date');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Nu se pot lua contact din Baza de Date');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Nu se poate șterge contul');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Nu se poate șterge filtrul');
define('PROC_CANT_DEL_CONT_GROUPS', 'Nu se pot șterge contacte și/sau grupuri');
define('PROC_WRONG_ACCT_ACCESS', 'Accesul la acest cont nu este permis');
define('PROC_SESSION_ERROR', 'Sesiunea precedentă a fost încheiată din cauza unei erori interne sau timeout.');

define('MailBoxIsFull', 'Cutia poştală este plină');
define('WebMailException', 'Eroare de server internă. Vă rugăm, contactaţi administratorul de sistem pentru a raporta problema.');
define('InvalidUid', 'Mesaj invalid');
define('CantCreateContactGroup', 'Nu se poate crea grup de contacte');
define('CantCreateUser', 'Nu se poate crea utilizator');
define('CantCreateAccount', 'Nu se poate crea cont');
define('SessionIsEmpty', 'Sesiunea este goală');
define('FileIsTooBig', 'Fişierul este prea mare');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Nu se pot marca toate mesajele ca citite');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Nu se pot marca toate mesajele ca necitite');
define('PROC_CANT_PURGE_MSGS', 'Nu se pot elimina mesajele');
define('PROC_CANT_DEL_MSGS', 'Nu se pot șterge mesajele');
define('PROC_CANT_UNDEL_MSGS', 'Nu se poate anula ștergerea mesajelor');
define('PROC_CANT_MARK_MSGS_READ', 'Nu se poate marca mesajul/mesajele ca citite');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Nu se poate marca mesajul/mesajele ca necitite');
define('PROC_CANT_SET_MSG_FLAGS', 'Nu se pot stabili indicatorii mesajului');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Nu se pot înlătura indicatorii mesajului');
define('PROC_CANT_CHANGE_MSG_FLD', 'Nu se poate schimba dosarul pentru mesaje');
define('PROC_CANT_SEND_MSG', 'Nu se poate trimite mesajul.');
define('PROC_CANT_SAVE_MSG', 'Nu se poate salva mesajul.');
define('PROC_CANT_GET_ACCT_LIST', 'Nu se poate prelua lista de conturi');
define('PROC_CANT_GET_FILTER_LIST', 'Nu se poate prelua lista de dosare');

define('PROC_CANT_LEAVE_BLANK', 'Vă rugăm să completați toate câmpurile marcate cu *');

define('PROC_CANT_UPD_FLD', 'Nu se poate actualiza dosarul');
define('PROC_CANT_UPD_FILTER', 'Nu se poate actualiza filtrul');

define('ACCT_CANT_ADD_DEF_ACCT', 'Acest cont nu poate fi adăugat, deoarece este folosit ca un cont implicit de către un alt utilizator.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Această stare a contului nu poate fi schimbata în modul standard.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Nu se poate crea un cont nou (IMAP4 eroare conexiune)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Nu se poate șterge ultimul cont implicit');

define('LANG_LoginInfo', 'Informații Conectare');
define('LANG_Email', 'Email');
define('LANG_Login', 'Acces');
define('LANG_Password', 'Parola');
define('LANG_IncServer', 'Sosire&nbsp;Mail');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'Plecare&nbsp;Mail');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Folosește&nbsp;autentificare&nbsp;SMTP');
define('LANG_SignMe', 'Conectează-mă automat');
define('LANG_Enter', 'Acces');

// interface strings

define('JS_LANG_TitleLogin', 'Autentificare');
define('JS_LANG_TitleMessagesListView', 'Vizualizare Listă Mesaje');
define('JS_LANG_TitleMessagesList', 'Listă Mesaje');
define('JS_LANG_TitleViewMessage', 'Vizualizare Mesaj');
define('JS_LANG_TitleNewMessage', 'Mesaj Nou');
define('JS_LANG_TitleSettings', 'Opțiuni');
define('JS_LANG_TitleContacts', 'Contacte');

define('JS_LANG_StandardLogin', 'Autentificare&nbsp;Standard');
define('JS_LANG_AdvancedLogin', 'Autentificare&nbsp;Avansată');

define('JS_LANG_InfoWebMailLoading', 'WebMail se incarcă&hellip;');
define('JS_LANG_Loading', 'Încărcare&hellip;');
define('JS_LANG_InfoMessagesLoad', 'WebMail încarcă lista de mesaje');
define('JS_LANG_InfoEmptyFolder', 'Aces dosar este gol');
define('JS_LANG_InfoPageLoading', 'Pagina se încarcă&hellip;');
define('JS_LANG_InfoSendMessage', 'Mesaj Trimis');
define('JS_LANG_InfoSaveMessage', 'Mesaj Salvat');
define('JS_LANG_InfoHaveImported', 'Ați importat');
define('JS_LANG_InfoNewContacts', 'noi contacte în lista dumneavoastră.');
define('JS_LANG_InfoToDelete', 'Pentru ștergere ');
define('JS_LANG_InfoDeleteContent', 'dosar trebuie să ștergeți mai întâi conținutul.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Ștergerea dosarelor cu conținut nu este permisă. Pentru a șterge dosare de acest fel (căsuță de selectare inactivă), ștergeți mai întâi conținutul.');
define('JS_LANG_InfoRequiredFields', '* câmpuri necesare');

define('JS_LANG_ConfirmAreYouSure', 'Confirmați?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Mesajul/mesajele selectat/e vor fi ȘTERSE PERMANENT! Confirmați?');
define('JS_LANG_ConfirmSaveSettings', 'Setările nu au fost salvate. Apasă OK pentru salvare.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Setările contactelor nu au fost salvate. Apasă OK pentru salvare.');
define('JS_LANG_ConfirmSaveAcctProp', 'Setările contului nu au fost salvate. Apasă OK pentru salvare.');
define('JS_LANG_ConfirmSaveFilter', 'Setările filtrelor nu au fost salvate. Apasă OK pentru salvare.');
define('JS_LANG_ConfirmSaveSignature', 'Semnătura nu a fost salvată. Apasă OK pentru salvare.');
define('JS_LANG_ConfirmSavefolders', 'Dosarele nu au fost salvate. Apasă OK pentru salvare.');
define('JS_LANG_ConfirmHtmlToPlain', 'Atenționare: Schimbând formatarea mesajului din HTML în text simplu, veți pierde orice formatare a mesajului. Apasă OK pentru continuare.');
define('JS_LANG_ConfirmAddFolder', 'Înainte de adăugare/eliminare dosar este necesară aplicarea modificărilor. Apasă OK pentru salvare.');
define('JS_LANG_ConfirmEmptySubject', 'Câmpul pentru subiect este gol. Doriți să continuați?');

define('JS_LANG_WarningEmailBlank', 'Nu puteți lăsa<br />câmpul "Email" gol.');
define('JS_LANG_WarningLoginBlank', 'Nu puteți lăsa<br />câmpul "Acces" gol.');
define('JS_LANG_WarningToBlank', 'Nu puteți lăsa câmpul "Către" gol');
define('JS_LANG_WarningServerPortBlank', 'Nu puteți lăsa câmpurile<br />POP3 și SMTP goale.');
define('JS_LANG_WarningEmptySearchLine', 'Linie de căutare goală. Vă rugăm să introduceți un șir de caractere pentru căutare.');
define('JS_LANG_WarningMarkListItem', 'Vă rugăm sa marcați cel puțin un element din listă.');
define('JS_LANG_WarningFolderMove', 'Dosarul nu poate fi mutat deoarece acesta este un alt nivel.');
define('JS_LANG_WarningContactNotComplete', 'Vă rugăm să introduceți adresa de email sau numele.');
define('JS_LANG_WarningGroupNotComplete', 'Vă rugăm să introduceți numele grupului.');

define('JS_LANG_WarningEmailFieldBlank', 'Nu puteți lăsa câmpul "Email" gol.');
define('JS_LANG_WarningIncServerBlank', 'Nu puteți lăsa câmpurile POP3(IMAP4) Server goale.');
define('JS_LANG_WarningIncPortBlank', 'Nu puteți lăsa câmpurile POP3(IMAP4) Server Port goale.');
define('JS_LANG_WarningIncLoginBlank', 'Nu puteți lăsa câmpurile POP3(IMAP4) Acces goale.');
define('JS_LANG_WarningIncPortNumber', 'Trebuie să specificați un număr pentru câmpul POP3(IMAP4).');
define('JS_LANG_DefaultIncPortNumber', 'Portul implicit POP3(IMAP4) este 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Nu puteți lăsa câmpul Parolă POP3(IMAP4) gol.');
define('JS_LANG_WarningOutPortBlank', 'Nu puteți lăsa câmpul SMTP Server Port gol.');
define('JS_LANG_WarningOutPortNumber', 'Trebuie să specificați un număr pentru câmpul SMTP.');
define('JS_LANG_WarningCorrectEmail', 'Trebuie să specificați o adresă corectă de e-mail.');
define('JS_LANG_DefaultOutPortNumber', 'Portul implicit SMTP este 25.');

define('JS_LANG_WarningCsvExtention', 'Extensia trebuie să fie .csv');
define('JS_LANG_WarningImportFileType', 'Vă rugăm sa alegeți aplicația din care vreți să copiați contactele');
define('JS_LANG_WarningEmptyImportFile', 'Vă rugăm să alegeți un fișier prin apăsarea butonului browse');

define('JS_LANG_WarningContactsPerPage', 'Persoane de contact per pagină este număr pozitiv');
define('JS_LANG_WarningMessagesPerPage', 'Mesaje per pagină este număr pozitiv');
define('JS_LANG_WarningMailsOnServerDays', 'Trebuie să specificaţi un număr pozitiv, în câmpul Zile Mesaje de pe Server.');
define('JS_LANG_WarningEmptyFilter', 'Introduceți șir caractere');
define('JS_LANG_WarningEmptyFolderName', 'Introduceți nume dosar');

define('JS_LANG_ErrorConnectionFailed', 'Eroare de conexiune');
define('JS_LANG_ErrorRequestFailed', 'Transferul datelor nu a fost finalizat');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'Obiectul XMLHttpRequest nu este prezent');
define('JS_LANG_ErrorWithoutDesc', 'S-a întâmpinat o eroare');
define('JS_LANG_ErrorParsing', 'Eroare la prelucrarea XML.');
define('JS_LANG_ResponseText', 'Text răspuns:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Pachet XML gol');
define('JS_LANG_ErrorImportContacts', 'Eroare la imporul contactelor');
define('JS_LANG_ErrorNoContacts', 'Nu există contacte de importat.');
define('JS_LANG_ErrorCheckMail', 'Primirea mesajelor a fost oprită din cauza unei erori. Este probabil ca unele mesaje să nu fie primite.');

define('JS_LANG_LoggingToServer', 'Autentificare la server&hellip;');
define('JS_LANG_GettingMsgsNum', 'Preluare număr mesaje');
define('JS_LANG_RetrievingMessage', 'Preluare mesaje');
define('JS_LANG_DeletingMessage', 'Ștergere');
define('JS_LANG_DeletingMessages', 'Ștergere');
define('JS_LANG_Of', 'din');
define('JS_LANG_Connection', 'Conexiunea');
define('JS_LANG_Charset', 'Set caractere');
define('JS_LANG_AutoSelect', 'Auto-Select');

define('JS_LANG_Contacts', 'Contacte');
define('JS_LANG_ClassicVersion', 'Veriunea Clasică');
define('JS_LANG_Logout', 'Ieșire');
define('JS_LANG_Settings', 'Preferințe');

define('JS_LANG_LookFor', 'Caută după: ');
define('JS_LANG_SearchIn', 'Caută în: ');
define('JS_LANG_QuickSearch', 'Caută după "De la", "La" și "Subiect" (rapid).');
define('JS_LANG_SlowSearch', 'Caută în toate mesajele');
define('JS_LANG_AllMailFolders', 'Toate dosarele de Mail');
define('JS_LANG_AllGroups', 'Toate Grupurile');

define('JS_LANG_NewMessage', 'Mesaj Nou');
define('JS_LANG_CheckMail', 'Verifică Mail');
define('JS_LANG_EmptyTrash', 'Golire Coș Gunoi');
define('JS_LANG_MarkAsRead', 'Marchează ca Citit');
define('JS_LANG_MarkAsUnread', 'Marchează ca Necitit');
define('JS_LANG_MarkFlag', 'Flag');
define('JS_LANG_MarkUnflag', 'Unflag');
define('JS_LANG_MarkAllRead', 'Marchează Toate ca Citite');
define('JS_LANG_MarkAllUnread', 'Marchează Toate ca Necitite');
define('JS_LANG_Reply', 'Răspunde');
define('JS_LANG_ReplyAll', 'Răspunde la Toți');
define('JS_LANG_Delete', 'Șterge');
define('JS_LANG_Undelete', 'Anulează Ștergerea');
define('JS_LANG_PurgeDeleted', 'Curăță mesaje Șterse');
define('JS_LANG_MoveToFolder', 'Mută la Dosar');
define('JS_LANG_Forward', 'Trimite mai departe');

define('JS_LANG_HideFolders', 'Ascunde Dosare');
define('JS_LANG_ShowFolders', 'Arată Dosare');
define('JS_LANG_ManageFolders', 'Organizează Dosare');
define('JS_LANG_SyncFolder', 'Sincronizează Dosar');
define('JS_LANG_NewMessages', 'Mesaj Nou');
define('JS_LANG_Messages', 'Mesaje');

define('JS_LANG_From', 'De la');
define('JS_LANG_To', 'La');
define('JS_LANG_Date', 'Data');
define('JS_LANG_Size', 'Dimensiune');
define('JS_LANG_Subject', 'Subiect');

define('JS_LANG_FirstPage', 'Prima Pagină');
define('JS_LANG_PreviousPage', 'Pagina Anterioară');
define('JS_LANG_NextPage', 'Pagina Următoare');
define('JS_LANG_LastPage', 'Ultima Pagină');

define('JS_LANG_SwitchToPlain', 'Comutare la Vizualizare Text Standard');
define('JS_LANG_SwitchToHTML', 'Comutare la Vizualizare HTML');
define('JS_LANG_AddToAddressBook', 'Adaugă la Contacte');
define('JS_LANG_ClickToDownload', 'Apasă pentru descărcare ');
define('JS_LANG_View', 'Vizualizare');
define('JS_LANG_ShowFullHeaders', 'Arată Full Headers');
define('JS_LANG_HideFullHeaders', 'Ascunde Full Headers');

define('JS_LANG_MessagesInFolder', 'Mesaje din Dosar');
define('JS_LANG_YouUsing', 'Ați folosit');
define('JS_LANG_OfYour', 'din');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Trimite');
define('JS_LANG_SaveMessage', 'Salvează');
define('JS_LANG_Print', 'Imprimă');
define('JS_LANG_PreviousMsg', 'Mesaj Anterior');
define('JS_LANG_NextMsg', 'Mesaj Următor');
define('JS_LANG_AddressBook', 'Agendă');
define('JS_LANG_ShowBCC', 'Arată BCC');
define('JS_LANG_HideBCC', 'Ascunde BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Răspunde&nbsp;La');
define('JS_LANG_AttachFile', 'Adaugă Fișier');
define('JS_LANG_Attach', 'Atașament');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Mesaj Original');
define('JS_LANG_Sent', 'Trimis');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Scăzută');
define('JS_LANG_Normal', 'Normală');
define('JS_LANG_High', 'Mare');
define('JS_LANG_Importance', 'Importanță');
define('JS_LANG_Close', 'Închide');

define('JS_LANG_Common', 'Comun');
define('JS_LANG_EmailAccounts', 'Conturi Email');

define('JS_LANG_MsgsPerPage', 'Mesaje per pagină');
define('JS_LANG_DisableRTE', 'Dezactivare editor text');
define('JS_LANG_Skin', 'Interfață');
define('JS_LANG_DefCharset', 'Set Caractere implicit');
define('JS_LANG_DefCharsetInc', 'Set Caractere implicit pentru primire');
define('JS_LANG_DefCharsetOut', 'Set Caractere implicit pentru trimitere');
define('JS_LANG_DefTimeOffset', 'Diferență Timp');
define('JS_LANG_DefLanguage', 'Limbă');
define('JS_LANG_DefDateFormat', 'Format Dată');
define('JS_LANG_ShowViewPane', 'Listă mesaje cu panou de Previzualizare');
define('JS_LANG_Save', 'Salvează');
define('JS_LANG_Cancel', 'Anulează');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Înlătură');
define('JS_LANG_AddNewAccount', 'Adaugă Cont Nou');
define('JS_LANG_Signature', 'Semnătură');
define('JS_LANG_Filters', 'Filtre');
define('JS_LANG_Properties', 'Proprietăți');
define('JS_LANG_UseForLogin', 'Folosește proprietățile acestui cont (login și parolă) pentru autentificare');
define('JS_LANG_MailFriendlyName', 'Nume');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Primire Mail');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Parolă');
define('JS_LANG_MailOutHost', 'Trimitere Mail');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'SMTP Login');
define('JS_LANG_MailOutPass', 'SMTP Parolă');
define('JS_LANG_MailOutAuth1', 'Folosește autentificare SMTP');
define('JS_LANG_MailOutAuth2', '(You may leave SMTP login/password fields blank, if they\'re the same as POP3/IMAP4 login/password)');
define('JS_LANG_UseFriendlyNm1', 'Folosește Nume în Câmpul "De la:" ');
define('JS_LANG_UseFriendlyNm2', '(Numele tău &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Primește/Sincronizează Mail la login');
define('JS_LANG_MailMode0', 'Șterge mesajele primite de pe server');
define('JS_LANG_MailMode1', 'Lasă mesajele pe server');
define('JS_LANG_MailMode2', 'Păstreză mesajele pe server pentru');
define('JS_LANG_MailsOnServerDays', 'zile');
define('JS_LANG_MailMode3', 'Șterge mesajul de pe server atunci când este înlăturat din Coșul de Gunoi');
define('JS_LANG_InboxSyncType', 'Tip de Sincronizare Inbox');

define('JS_LANG_SyncTypeNo', 'Nu sincroniza');
define('JS_LANG_SyncTypeNewHeaders', 'New Headers');
define('JS_LANG_SyncTypeAllHeaders', 'All Headers');
define('JS_LANG_SyncTypeNewMessages', 'New Messages');
define('JS_LANG_SyncTypeAllMessages', 'All Messages');
define('JS_LANG_SyncTypeDirectMode', 'Mod Direct');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Headers Only');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Entire Messages');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Direct Mode');

define('JS_LANG_DeleteFromDb', 'Șterge mesajul din baza de date dacă nu mai exista pe serverul de mail');

define('JS_LANG_EditFilter', 'Modifică&nbsp;filtru');
define('JS_LANG_NewFilter', 'Adaugă Filtru Nou');
define('JS_LANG_Field', 'Câmp');
define('JS_LANG_Condition', 'Condiție');
define('JS_LANG_ContainSubstring', 'Conține șir de caractere');
define('JS_LANG_ContainExactPhrase', 'Conține frază exactă');
define('JS_LANG_NotContainSubstring', 'Nu conține șir de caractere');
define('JS_LANG_FilterDesc_At', 'la');
define('JS_LANG_FilterDesc_Field', 'câmpul');
define('JS_LANG_Action', 'Acțiune');
define('JS_LANG_DoNothing', 'Nu se face nimic');
define('JS_LANG_DeleteFromServer', 'Șterge imediat de pe server');
define('JS_LANG_MarkGrey', 'Marcheazp gri');
define('JS_LANG_Add', 'Adaugă');
define('JS_LANG_OtherFilterSettings', 'Alte setări pentru filtre');
define('JS_LANG_ConsiderXSpam', 'Verifică X-Spam headers');
define('JS_LANG_Apply', 'Aplică');

define('JS_LANG_InsertLink', 'Adaugă Link');
define('JS_LANG_RemoveLink', 'Înlătură Link');
define('JS_LANG_Numbering', 'Numerotare');
define('JS_LANG_Bullets', 'Bullets');
define('JS_LANG_HorizontalLine', 'Horizontal Line');
define('JS_LANG_Bold', 'Bold');
define('JS_LANG_Italic', 'Italic');
define('JS_LANG_Underline', 'Underline');
define('JS_LANG_AlignLeft', 'Align Left');
define('JS_LANG_Center', 'Center');
define('JS_LANG_AlignRight', 'Align Right');
define('JS_LANG_Justify', 'Justify');
define('JS_LANG_FontColor', 'Font Color');
define('JS_LANG_Background', 'Background');
define('JS_LANG_SwitchToPlainMode', 'Modifică în mod text simplu');
define('JS_LANG_SwitchToHTMLMode', 'Modifică în mod HTML');

define('JS_LANG_Folder', 'Dosar');
define('JS_LANG_Msgs', 'Mesaj(e)');
define('JS_LANG_Synchronize', 'Sincronizează');
define('JS_LANG_ShowThisFolder', 'Arată acest Dosar');
define('JS_LANG_Total', 'Total');
define('JS_LANG_DeleteSelected', 'Șterge selectate');
define('JS_LANG_AddNewFolder', 'Adaugă Dosar Nou');
define('JS_LANG_NewFolder', 'Dosar Nou');
define('JS_LANG_ParentFolder', 'Dosar Părinte');
define('JS_LANG_NoParent', 'Nu exista Părinte');
define('JS_LANG_FolderName', 'Nume Dosar');

define('JS_LANG_ContactsPerPage', 'Contacte per pagină');
define('JS_LANG_WhiteList', 'Agendă Contacte ca și Listă Permisă');

define('JS_LANG_CharsetDefault', 'Implicit');
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

define('JS_LANG_TimeDefault', 'Implicit');
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

define('JS_LANG_DateDefault', 'Implicit');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Lună (01 Ian)');
define('JS_LANG_DateAdvanced', 'Avansat');

define('JS_LANG_NewContact', 'Contact Nou');
define('JS_LANG_NewGroup', 'Grup Nou');
define('JS_LANG_AddContactsTo', 'Adaugă Contacte la');
define('JS_LANG_ImportContacts', 'Importă Contacte');

define('JS_LANG_Name', 'Nume');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Email');
define('JS_LANG_NotSpecifiedYet', 'Nespecificat');
define('JS_LANG_ContactName', 'Nume');
define('JS_LANG_Birthday', 'Zi Naștere');
define('JS_LANG_Month', 'Lună');
define('JS_LANG_January', 'Ianuarie');
define('JS_LANG_February', 'Februarie');
define('JS_LANG_March', 'Martie');
define('JS_LANG_April', 'Aprilie');
define('JS_LANG_May', 'Mai');
define('JS_LANG_June', 'Iunie');
define('JS_LANG_July', 'Iulie');
define('JS_LANG_August', 'August');
define('JS_LANG_September', 'Septembrie');
define('JS_LANG_October', 'Octombrie');
define('JS_LANG_November', 'Noiembrie');
define('JS_LANG_December', 'Decembrie');
define('JS_LANG_Day', 'Zi');
define('JS_LANG_Year', 'An');
define('JS_LANG_UseFriendlyName1', 'Folosește Nume');
define('JS_LANG_UseFriendlyName2', '(de exemplu, John Doe &lt;johndoe@mail.com&gt;)');
define('JS_LANG_Personal', 'Personal');
define('JS_LANG_PersonalEmail', 'Personal E-mail');
define('JS_LANG_StreetAddress', 'Stradă');
define('JS_LANG_City', 'Oraș');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Județ');
define('JS_LANG_Phone', 'Telefon');
define('JS_LANG_ZipCode', 'Cod Poștal');
define('JS_LANG_Mobile', 'Mobil');
define('JS_LANG_CountryRegion', 'Țară/Regiune');
define('JS_LANG_WebPage', 'Pagină Web');
define('JS_LANG_Go', 'Mergi');
define('JS_LANG_Home', 'Acasă');
define('JS_LANG_Business', 'Business');
define('JS_LANG_BusinessEmail', 'Business E-mail');
define('JS_LANG_Company', 'Companie');
define('JS_LANG_JobTitle', 'Meserie');
define('JS_LANG_Department', 'Departament');
define('JS_LANG_Office', 'Birou');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Altele');
define('JS_LANG_OtherEmail', 'Alt E-mail');
define('JS_LANG_Notes', 'Note');
define('JS_LANG_Groups', 'Grupuri');
define('JS_LANG_ShowAddFields', 'Arată câmpuri adiționale');
define('JS_LANG_HideAddFields', 'Ascunde câmpuri adiționale');
define('JS_LANG_EditContact', 'Editează informații contact');
define('JS_LANG_GroupName', 'Nume Grup');
define('JS_LANG_AddContacts', 'Adaugă Contacte');
define('JS_LANG_CommentAddContacts', '(Dacă specificați mai multe adrese, vă rugăm să le separați prin virgulă)');
define('JS_LANG_CreateGroup', 'Creează Group');
define('JS_LANG_Rename', 'redenumește');
define('JS_LANG_MailGroup', 'Grup Mail');
define('JS_LANG_RemoveFromGroup', 'Înlătură din grup');
define('JS_LANG_UseImportTo', 'Folosiți Import pentru a copia contactele dumneavoastră din Microsoft Outlook, Microsoft Outlook Express în lista de contacte WebMail.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Selectați fișierul (.CSV format) pe care vreți să îl importați');
define('JS_LANG_Import', 'Import');
define('JS_LANG_ContactsMessage', 'Aceasa este padina de contacte!!!');
define('JS_LANG_ContactsCount', 'contact(e)');
define('JS_LANG_GroupsCount', 'grup(uri)');

// webmail 4.1 constants
define('PicturesBlocked', 'Pozele din acest mesaj au fost blocate pentru securitatea dumneavoastră.');
define('ShowPictures', 'Arată poze');
define('ShowPicturesFromSender', 'Arată mereu pozele de la acest expeditor');
define('AlwaysShowPictures', 'Arată mereu pozele in mesaje');
define('TreatAsOrganization', 'Tratează ca pe o organizație');

define('WarningGroupAlreadyExist', 'Există deja acest grup. Specificați alt nume.');
define('WarningCorrectFolderName', 'Trebuie să specificați un dosar corect.');
define('WarningLoginFieldBlank', 'Nu puteți lăsa câmpul "Login" gol.');
define('WarningCorrectLogin', 'Introduceți un login corect.');
define('WarningPassBlank', 'Nu puteți lăsa câmpul "Parola" gol.');
define('WarningCorrectIncServer', 'Specificați o adresă corectă pentru serverul POP3(IMAP)');
define('WarningCorrectSMTPServer', 'Specificați o adresă corectă pentru Trimitere Mail.');
define('WarningFromBlank', 'Completați câmpul "De la"');
define('WarningAdvancedDateFormat', 'Specificați format data-timp.');

define('AdvancedDateHelpTitle', 'Dată avansată');
define('AdvancedDateHelpIntro', 'Când câmpul &quot;Dată avansată&quot; este selectat, puteți folosi căsuța de text pentru a specifica un mod personalizat al datei, ce va apare în WebMail.');
define('AdvancedDateHelpConclusion', 'De exemplu dacă ați specificat &quot;mm/dd/yyyy&quot; valoarea în căsuța de text a &quot;Dată avansată&quot; atunci data este afișată month/day/year (ex. 11/23/2011)');
define('AdvancedDateHelpDayOfMonth', 'Zi din lună (1 până la 31)');
define('AdvancedDateHelpNumericMonth', 'Lună (1 până la 12)');
define('AdvancedDateHelpTextualMonth', 'Lună (Jan până la Dec)');
define('AdvancedDateHelpYear2', 'An, 2 cifre');
define('AdvancedDateHelpYear4', 'An, 4 cifre');
define('AdvancedDateHelpDayOfYear', 'Zi din an (1 până la 366)');
define('AdvancedDateHelpQuarter', 'Sfert');
define('AdvancedDateHelpDayOfWeek', 'Zi din săptămână (Lun până la Dum)');
define('AdvancedDateHelpWeekOfYear', 'Săptămână din An (1 până la 53)');

define('InfoNoMessagesFound', 'Niciun mesaj găsit.');
define('ErrorSMTPConnect', 'Conexiune server SMTP cu probleme, verifică setări.');
define('ErrorSMTPAuth', 'Nume sau parolă gresită. Nu se permite accesul în sistem.');
define('ReportMessageSent', 'Mesajul a fost trimis.');
define('ReportMessageSaved', 'Mesajul a fost salvat.');
define('ErrorPOP3Connect', 'Conexiune server POP3 cu probleme, verifică setări.');
define('ErrorIMAP4Connect', 'Conexiune server IMAP cu probleme, verifică setări.');
define('ErrorPOP3IMAP4Auth', 'Nume sau parolă gresită. Nu se permite accesul în sistem.');
define('ErrorGetMailLimit', 'Ne pare rău, ați depășit valoarea căsuței de mail.');

define('ReportSettingsUpdatedSuccessfuly', 'Setările au fost făcute.');
define('ReportAccountCreatedSuccessfuly', 'Contul a fost creeat.');
define('ReportAccountUpdatedSuccessfuly', 'Contul a fost modificat.');
define('ConfirmDeleteAccount', 'Siguri dotiți ștergerea contului?');
define('ReportFiltersUpdatedSuccessfuly', 'Filtrele au fost modificate.');
define('ReportSignatureUpdatedSuccessfuly', 'Semnatură modificată.');
define('ReportFoldersUpdatedSuccessfuly', 'Dosare modificate.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Contacte\' setări modificate.');

define('ErrorInvalidCSV', 'CSV invalid.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Grupul');
define('ReportGroupSuccessfulyAdded2', 'a fost adăugat.');
define('ReportGroupUpdatedSuccessfuly', 'Grup modificat.');
define('ReportContactSuccessfulyAdded', 'Contact adăugat.');
define('ReportContactUpdatedSuccessfuly', 'Contact modificat.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Contact(e) a fost adăugat la Grup');
define('AlertNoContactsGroupsSelected', 'Nu s-au selectat contacte sau grupuri.');

define('InfoListNotContainAddress', 'Dacă lista nu conține adresa pe care o căutați, scrieți primele caractere.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Mod Direct. WebMail va accesa mesajele direct de pe serverul de mail.');

define('FolderInbox', 'Primite');
define('FolderSentItems', 'Mesaje Trimise');
define('FolderDrafts', 'Ciorne');
define('FolderTrash', 'Coș Gunoi');

define('FileLargerAttachment', 'Fișierul este mai decât limita admisă.');
define('FilePartiallyUploaded', 'Doar o parte din fișier a fost încărcată, a intervenit o eroare.');
define('NoFileUploaded', 'Niciun fișier încărcat.');
define('MissingTempFolder', 'Dosarul temporar nu există.');
define('MissingTempFile', 'Fișierul temporar nu există.');
define('UnknownUploadError', 'Eroare la încărcarea fișierului.');
define('FileLargerThan', 'Eroare la încărcarea fișierului. Probabil fișierul este mai mare decât ');
define('PROC_CANT_LOAD_DB', 'Eroare conexiune baza de date.');
define('PROC_CANT_LOAD_LANG', 'Nu găsesc fișier limbă.');
define('PROC_CANT_LOAD_ACCT', 'Contul nu există, poate a fost șters...');

define('DomainDosntExist', 'Nu există domeniul pe serverul de mail.');
define('ServerIsDisable', 'Adminul a tăiat creanga la server..');

define('PROC_ACCOUNT_EXISTS', 'Contul nu poate fi creeat pentru că există deja.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Nu pot prelua numărul mesajelor.');
define('PROC_CANT_MAIL_SIZE', 'Nu pot prelua dimensiunea căsuței de mail.');

define('Organization', 'Organizatie');
define('WarningOutServerBlank', 'Nu puteți lăsa câmpul "Trimitere Mail" gol.');

define('JS_LANG_Refresh', 'Refresh');
define('JS_LANG_MessagesInInbox', 'Mesaj(e) în Primite');
define('JS_LANG_InfoEmptyInbox', 'Nu aveți mesaje');

// webmail 4.2 constants
define('BackToList', 'Înapoi la listă');
define('InfoNoContactsGroups', 'Niciun contact sau grup.');
define('InfoNewContactsGroups', 'Puteți creea sau importa contacte/grupuri din fisier de tip .CSV format MS Outlook.');
define('DefTimeFormat', 'Format Timp');
define('SpellNoSuggestions', 'Nicio sugestie');
define('SpellWait', 'Așteptați&hellip;');

define('InfoNoMessageSelected', 'Niciun mesaj selectat.');
define('InfoSingleDoubleClick', 'Click pe mesaj din lista pentru previzualizare sau dubluclick pentru deschidere.');

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

define('SettingsTabCalendar', 'Calendar');

define('FullMonthJanuary', 'January');
define('FullMonthFebruary', 'February');
define('FullMonthMarch', 'March');
define('FullMonthApril', 'April');
define('FullMonthMay', 'May');
define('FullMonthJune', 'June');
define('FullMonthJuly', 'July');
define('FullMonthAugust', 'August');
define('FullMonthSeptember', 'September');
define('FullMonthOctober', 'October');
define('FullMonthNovember', 'November');
define('FullMonthDecember', 'December');

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

define('FullDayMonday', 'Monday');
define('FullDayTuesday', 'Tuesday');
define('FullDayWednesday', 'Wednesday');
define('FullDayThursday', 'Thursday');
define('FullDayFriday', 'Friday');
define('FullDaySaturday', 'Saturday');
define('FullDaySunday', 'Sunday');

define('DayToolMonday', 'Mon');
define('DayToolTuesday', 'Tue');
define('DayToolWednesday', 'Wed');
define('DayToolThursday', 'Thu');
define('DayToolFriday', 'Fri');
define('DayToolSaturday', 'Sat');
define('DayToolSunday', 'Sun');

define('CalendarTableDayMonday', 'M');
define('CalendarTableDayTuesday', 'T');
define('CalendarTableDayWednesday', 'W');
define('CalendarTableDayThursday', 'T');
define('CalendarTableDayFriday', 'F');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'S');

define('ErrorParseJSON', 'The JSON response returned by the server cannot be parsed.');

define('ErrorLoadCalendar', 'Unable to load calendars');
define('ErrorLoadEvents', 'Unable to load events');
define('ErrorUpdateEvent', 'Unable to save event');
define('ErrorDeleteEvent', 'Unable to delete event');
define('ErrorUpdateCalendar', 'Unable to save calendar');
define('ErrorDeleteCalendar', 'Unable to delete calendar');
define('ErrorGeneral', 'An error occured on the server. Try again later.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Share and publish calendar');
define('ShareActionEdit', 'Share and publish calendar');
define('CalendarPublicate', 'Make public web access to this calendar');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Share this calendar');
define('SharePermission1', 'Can make changes and manage sharing');
define('SharePermission2', 'Can make changes to events');
define('SharePermission3', 'Can see all event details');
define('SharePermission4', 'Can see only free/busy (hide details)');
define('ButtonClose', 'Close');
define('WarningEmailFieldFilling', 'You should fill e-mail field first');
define('EventHeaderView', 'View Event');
define('ErrorUpdateSharing', 'Unable to save sharing and publication data');
define('ErrorUpdateSharing1', 'Not possible to share to %s user as it doesn\'t exist');
define('ErrorUpdateSharing2', 'Imposible to share this calendar to user %s');
define('ErrorUpdateSharing3', 'This calendar already shared to user %s');
define('Title_MyCalendars', 'My calendars');
define('Title_SharedCalendars', 'Shared calendars');
define('ErrorGetPublicationHash', 'Unable to create publication link');
define('ErrorGetSharing', 'Unable to add sharing');
define('CalendarPublishedTitle', 'This calendar is published');
define('RefreshSharedCalendars', 'Refresh Shared Calendars');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Membri');

define('ReportMessagePartDisplayed', 'Este afișată doar o parte din mesaj.');
define('ReportViewEntireMessage', 'Pentru a vedea întregul mesaj,');
define('ReportClickHere', 'click aici');
define('ErrorContactExists', 'Există deja un contact cu acest nume si această adresă de mail.');

define('Attachments', 'Atașamente');

define('InfoGroupsOfContact', 'Grupurile din care face parte contactul sunt marcate.');
define('AlertNoContactsSelected', 'Niciun contact selectat.');
define('MailSelected', 'Adrese Mail selectate');
define('CaptionSubscribed', 'Abonat');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Nu este Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Trimite mail');
define('ContactViewAllMails', 'Vizualizare toate mailurile de la acest contact');
define('ContactsMailThem', 'Trimiteți-le Mail');
define('DateToday', 'Astăzi');
define('DateYesterday', 'Ieri');
define('MessageShowDetails', 'Arată detalii');
define('MessageHideDetails', 'Ascunde detalii');
define('MessageNoSubject', 'Fără subiect');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'către');
define('SearchClear', 'Anulează căutare');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Rezultatele căutării pentru "#s" în dosar #f :');
define('SearchResultsInAllFolders', 'Rezultatele căutării pentru "#s" în toate dosarele:');
define('AutoresponderTitle', 'Autorăspuns');
define('AutoresponderEnable', 'Activează autorăspuns');
define('AutoresponderSubject', 'Subiect');
define('AutoresponderMessage', 'Mesaj');
define('ReportAutoresponderUpdatedSuccessfuly', 'Autorăspuns modficicat.');
define('FolderQuarantine', 'Carantină');

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
define('LanguageDefault', 'Default');

// webmail 4.5.x new
define('EmptySpam', 'Golește Spam');
define('Saving', 'Salvez&hellip;');
define('Sending', 'Trimitere&hellip;');
define('LoggingOffFromServer', 'Ieșire de pe server&hellip;');

// webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Nu pot marca mesaj(e) ca spam');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Nu pot marca mesaj(e) ca non-spam');
define('ExportToICalendar', 'Export la iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'User couldn\'t be created because max number of users allowed by your license exceeded.');
define('RepliedMessageTitle', 'Mesaj cu răspuns');
define('ForwardedMessageTitle', 'Mesaj trimis mai departe');
define('RepliedForwardedMessageTitle', 'Răspuns și trimis mai departe');
define('ErrorDomainExist', 'The user cannot be created because corresponding domain doesn\'t exist. You should create the domain first.');

// webmail 4.7
define('RequestReadConfirmation', 'Confirmare de citire');
define('FolderTypeDefault', 'Implicit');
define('ShowFoldersMapping', 'Folosire alt dosar ca dosar de sistem (ex. folosesc DosarulMeu ca Mesaje Trimise)');
define('ShowFoldersMappingNote', 'De exemplu, pentru a schimba locația Mesajelor Trimise din Mesaje Trimise in DosarulMeu, specificați "Mesaje Trimise" în meniul "Folosiți pentru" al "DosarulMeu".');
define('FolderTypeMapTo', 'Folosiți pentru');

define('ReminderEmailExplanation', 'Aces mesaj a venit pe contrul %EMAIL% deoarece ați dorit notificare în calendar: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Deschide calendar');

define('AddReminder', 'Amintește-mi de acest eveniment');
define('AddReminderBefore', 'Amintește-mi cu % înainte de acest eveniment');
define('AddReminderAnd', 'și % înainte');
define('AddReminderAlso', 'și deasemenea % înainte');
define('AddMoreReminder', 'Mai multe alarme');
define('RemoveAllReminders', 'Înlătură alarme');
define('ReminderNone', 'Niciuna');
define('ReminderMinutes', 'minute');
define('ReminderHour', 'oră');
define('ReminderHours', 'ore');
define('ReminderDay', 'zi');
define('ReminderDays', 'zile');
define('ReminderWeek', 'săptămână');
define('ReminderWeeks', 'săptămâni');
define('Allday', 'Toată ziua');

define('Folders', 'Dosare');
define('NoSubject', 'Niciun subiect');
define('SearchResultsFor', 'Caută rezultate pentru');

define('Back', 'Înapoi');
define('Next', 'Înainte');
define('Prev', 'Anterior');

define('MsgList', 'Mesaje');
define('Use24HTimeFormat', 'Folosește format 24 ore');
define('UseCalendars', 'Folosește calendar');
define('Event', 'Eveniment');
define('CalendarSettingsNullLine', 'Niciun calendar');
define('CalendarEventNullLine', 'Niciun eveniment');
define('ChangeAccount', 'Modificare cont');

define('TitleCalendar', 'Calendar');
define('TitleEvent', 'Eveniment');
define('TitleFolders', 'Dosare');
define('TitleConfirmation', 'Confirmări');

define('Yes', 'Da');
define('No', 'Nu');

define('EditMessage', 'Editează Mesaj');

define('AccountNewPassword', 'Parolă Nouă');
define('AccountConfirmNewPassword', 'Confirmare parolă nouă');
define('AccountPasswordsDoNotMatch', 'Parolele nu corespund.');

define('ContactTitle', 'Titlu');
define('ContactFirstName', 'Nume');
define('ContactSurName', 'Prenume');

define('ContactNickName', 'Poreclă');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'din nou..');
define('CaptchaError', 'Captcha nu este corect...');

define('WarningInputCorrectEmails', 'Specificați mail corect.');
define('WrongEmails', 'Mail incorect:');

define('ConfirmBodySize1', 'Ne pare rău, mesajele text sunt de maxim');
define('ConfirmBodySize2', 'caractere. Ce este peste această limită va fi redus. Apasă "Anulare" dacă se dorește editarea mesajului.');
define('BodySizeCounter', 'Numărătoare');
define('InsertImage', 'Adaugă imagine');
define('ImagePath', 'Cale Imagine');
define('ImageUpload', 'Adăugare');
define('WarningImageUpload', 'Fișierul ce se ataşează nu este o imagine. Va rugăm selectaţi un fişier imagine.');

define('ConfirmExitFromNewMessage', 'Dacă navigaţi de pe această pagină fără să salvaţi, veti pierde toate modificarile făcute de la ultima salvare. Dați click pe Anulare pentru a rămâne pe pagina curentă.');

define('SensivityConfidential', 'Vă rugăm să trataţi acest mesaj ca fiind Confidential');
define('SensivityPrivate', 'Vă rugăm să trataţi acest mesaj ca fiind Privat');
define('SensivityPersonal', 'Vă rugăm să trataţi acest mesaj ca fiind Personal');

define('ReturnReceiptTopText', 'Expeditorul acestui mesaj doreşte să fie anunţat când primiţi acest mesaj.');
define('ReturnReceiptTopLink', 'Dați click aici pentru a anunţa expeditorul.');
define('ReturnReceiptSubject', 'Confirmare de primire (afişată)');
define('ReturnReceiptMailText1', 'Aceasta este o confirmare de primire pentru mail-ul pe care l-ati trimis');
define('ReturnReceiptMailText2', 'Notă: Această confirmare de primire recunoaşte numai că mesajul a fost afişat pe computerul destinatarului. Nu există nici o garanţie că destinatarul a citit sau înţeles conţinutul mesajului.');
define('ReturnReceiptMailText3', 'cu subiect');

define('SensivityMenu', 'Sensibilitate');
define('SensivityNothingMenu', 'Nimic');
define('SensivityConfidentialMenu', 'Confidential');
define('SensivityPrivateMenu', 'Privat');
define('SensivityPersonalMenu', 'Personal');

define('ErrorLDAPonnect', 'Eroare conexiune server ldap.');

define('MessageSizeExceedsAccountQuota', 'Dimensiunea acestui mesaj depăşeşte cota acestui cont.');
define('MessageCannotSent', 'Mesajul nu poate fi trimis.');
define('MessageCannotSaved', 'Mesajul nu poate fi salvat.');

define('ContactFieldTitle', 'Câmp');
define('ContactDropDownTO', 'Către');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Mesajul nu poate fi mutat în coșul de gunoi. Cel mai probabil căsuţa dumneavoastră de mesaje este plină. Acest mesaj nemutat să fie sters? ');

define('WarningFieldBlank', 'Acest câmp nu poate fi gol.');
define('WarningPassNotMatch', 'Parolele nu corespund, vă rugăm verificaţi.');
define('PasswordResetTitle', 'Recuperarea Parolei  - pasul %d');
define('NullUserNameonReset', 'utilizator');
define('IndexResetLink', 'Aţi uitat parola?');
define('IndexRegLink', 'Înregistrarea contului');

define('RegDomainNotExist', 'Domeniul nu există.');
define('RegAnswersIncorrect', 'Răspunsurile sunt  incorecte.');
define('RegUnknownAdress', 'Adresă de e-mail necunoscută.');
define('RegUnrecoverableAccount', 'Recuperarea parolei nu poate fi aplicată pentru această adresă de e-mail.');
define('RegAccountExist', 'Această adresă este deja folosită.');
define('RegRegistrationTitle', 'Înregistrare');
define('RegName', 'Nume');
define('RegEmail', 'adresa de e-mail ');
define('RegEmailDesc', 'De exemplu, numelemeu@domeniu.ro. Această informaţie va fi folosită pentru intrarea în sistem.');
define('RegSignMe', 'Ţine-mă minte');
define('RegSignMeDesc', 'Nu se mai cere Login si parola la  urmatoarea accesare de pe acest PC.');
define('RegPass1', 'Parolă');
define('RegPass2', 'Repetaţi parolă ');
define('RegQuestionDesc', 'Vă rugăm, furnizaţi două întrebări şi răspunsuri secrete cunoscute doar de dumneavoastră. În caz de pierdere a parolei puteţi folosi aceste întrebări pentru a vă putea recupera parola.');
define('RegQuestion1', 'Întrebare secretă 1');
define('RegAnswer1', 'Răspuns 1');
define('RegQuestion2', 'Întrebare secretă 2');
define('RegAnswer2', 'Răspuns 2');
define('RegTimeZone', 'Fus Orar');
define('RegLang', 'Limbaj interfaţă');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Înregistrare');

define('ResetEmail', 'Vă rugăm furnizaţi adresa dumneavoastră de e-mail');
define('ResetEmailDesc', 'Furnizaţi adresa de e-mail folosită pentru înregistrare.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Trimite');
define('ResetQuestion1', 'Întrebare secretă 1');
define('ResetAnswer1', 'Răspuns');
define('ResetQuestion2', 'Întrebare secretă 2');
define('ResetAnswer2', 'Răspuns');
define('ResetSubmitStep2', 'Trimite');

define('ResetTopDesc1Step2', 'Adresa de mail furnizată');
define('ResetTopDesc2Step2', 'Rugăm confirmaţi corectitudinea.');

define('ResetTopDescStep3', 'vă rugăm specificaţi mai jos noua parolă pentru e-mail-ul dumneavoastră.');

define('ResetPass1', 'Parola nouă');
define('ResetPass2', 'Repetă parola');
define('ResetSubmitStep3', 'Trimite');
define('ResetDescStep4', 'Parola dumneavoastră a fost schimbată.');
define('ResetSubmitStep4', 'Întoarcere');

define('RegReturnLink', 'Întoarcere la pagina de access');
define('ResetReturnLink', 'Întoarcere la pagina de access');

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

define('ErrorCantUpdateFilters', 'Nu se pot actualiza filtrele');

define('FilterPhrase', 'Dacă %field este %condition %string atunci %action');
define('FiltersAdd', 'Adăugaţi Filtru');
define('FiltersCondEqualTo', 'egal cu');
define('FiltersCondContainSubstr', 'conţine şir de caractere');
define('FiltersCondNotContainSubstr', 'nu conţine şir de caractere ');
define('FiltersActionDelete', 'ştergere mesaj');
define('FiltersActionMove', 'mută');
define('FiltersActionToFolder', 'la %folder dosar');
define('FiltersNo', 'Nici un filtru specificat încă');

define('ReminderEmailFriendly', 'memento');
define('ReminderEventBegin', 'începe la: ');

define('FiltersLoading', 'Încărcare filtre...');
define('ConfirmMessagesPermanentlyDeleted', 'Toate mesajele din acest dosar vor fi  sterse permanent.');

define('InfoNoNewMessages', 'Nu sunt mesaje noi.');
define('TitleImportContacts', 'Import Contacte');
define('TitleSelectedContacts', 'Contacte Selectate');
define('TitleNewContact', 'Contact Nou');
define('TitleViewContact', 'Vizualizare Contact');
define('TitleEditContact', 'Editare Contact');
define('TitleNewGroup', 'Grup Nou');
define('TitleViewGroup', 'Vizualizare Grup');

define('AttachmentComplete', 'Complet.');

define('TestButton', 'TEST');
define('AutoCheckMailIntervalLabel', 'Autoverificare mail la fiecare');
define('AutoCheckMailIntervalDisableName', 'Închis');

define('ReportCalendarSaved', 'Calendarul a fost salvat.');

define('ContactSyncError', 'Sincronizare eşuată');
define('ReportContactSyncDone', 'Sincronizare  completă');

define('MobileSyncUrlTitle', 'URL Sincronizare Mobil');
define('MobileSyncLoginTitle', 'Autentificare Sincronizare Mobil');

define('QuickReply', 'Răspuns rapid');
define('SwitchToFullForm', 'Deschide forma completă de răspuns');
define('SortFieldDate', 'Data');
define('SortFieldFrom', 'De la');
define('SortFieldSize', 'Dimensiune');
define('SortFieldSubject', 'Subiect');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Ataşamente');
define('SortOrderAscending', 'Ascendent');
define('SortOrderDescending', 'Descendent');
define('ArrangedBy', 'Aranjat de');

define('MessagePaneToRight', 'Panoul de mesaje este mai degrabă în dreapta listei de mesaje, decât în jos');

define('SettingsTabMobileSync', 'Sincronizare Mobil');

define('MobileSyncContactDataBaseTitle', 'Sincronizare bază de date Contacte Mobil');
define('MobileSyncCalendarDataBaseTitle', ' Sincronizare bază de date Calendar  Mobil ');
define('MobileSyncTitleText', 'If you\'d like to synchronize your SyncML-enabled handheld device with WebMail, you can use these parameters.<br />"Mobile Sync URL" specifies path to SyncML Data Synchronization server, "Mobile Sync Login" is your login on SyncML Data Synchronization Server and use your own password upon request. Also, some devices need to specify database name for contact and calendar data.<br />Use "Mobile sync contact database" and "Mobile sync calendar database" respectively.');
define('MobileSyncEnableLabel', 'Activează sincronizare mobil');

define('SearchInputText', 'Căutare');

define('AppointmentEmailExplanation', 'Acest mesaj a ajuns în contul dvs. %EMAIL% deoarece ați fost invitat la eveniment de către %ORGANAZER%');

define('Searching', 'Căutare&hellip;');

define('ButtonSetupSpecialFolders', 'Setati  dosare speciale');
define('ButtonSaveChanges', 'Salvaţi modificările');
define('InfoPreDefinedFolders', 'Pentru  dosare pre-definite, utilizaţi aceste căsuţe poştale de IMAP');

define('SaveMailInSentItems', 'Salvează în Mesaje Trimise');

define('CouldNotSaveUploadedFile', 'Nu a putut fi salvat fişierul încărcat.');

define('AccountOldPassword', 'Parola curentă');
define('AccountOldPasswordsDoNotMatch', 'Parolele curente nu se potrivesc.');

define('DefEditor', 'Editor implicit');
define('DefEditorRichText', 'Text Bogat');
define('DefEditorPlainText', 'Text Simplu');

define('Layout', 'Aspect');

define('TitleNewMessagesCount', '%count% mesaj(e) nou(noi)');

define('AltOpenInNewWindow', 'Deschide în fereastră nouă');

define('SearchByFirstCharAll', 'Tot');

define('FolderNoUsageAssigned', 'Nici o utilizare alocată ');

define('InfoSetupSpecialFolders', 'Pentru a potrivi un dosar special (precum Mesaje Trimise) și o anumită căsuță IMAP, dați click pe Setările dosarului special.');

define('FileUploaderClickToAttach', 'Click pentru a atașa fisier');
define('FileUploaderOrDragNDrop', 'Sau trage (drag/drop) fișierul aici');

define('AutoCheckMailInterval1Minute', '1 minut');
define('AutoCheckMailInterval3Minutes', '3 minute');
define('AutoCheckMailInterval5Minutes', '5 minute');
define('AutoCheckMailIntervalMinutes', 'minute');

define('ReadAboutCSVLink', 'Citiţi despre câmpurile fişierelor CSV');

define('VoiceMessageSubj', 'Mesaje Vocale');
define('VoiceMessageTranscription', 'Transcripţie');
define('VoiceMessageReceived', 'Primit');
define('VoiceMessageDownload', 'Descarcă');
define('VoiceMessageUpgradeFlashPlayer', 'Adobe Flash Player trebuie actualizat pentru a putea reda mesajele vocale.<br />Actualizaţi la Flash Player de la <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'Aceasta cheie de licenţă este învechită, vă rugăm să ne contactaţi pentru a actualiza cheia de licenţă');
define('LicenseProblem', 'Problemă de licenţiere. Administratorul de sistem trebuie să acceseze panoul de administrare pentru a verifica detaliile.');

define('AccountOldPasswordNotCorrect', 'Parola curentă nu este corcectă.');
define('AccountNewPasswordUpdateError', 'Nu se poate salva parolă nouă.');
define('AccountNewPasswordRejected', 'Nu se poate salva parolă nouă. Poate, este prea simplă.');

define('CantCreateIdentity', 'Nu se poate crea identitate');
define('CantUpdateIdentity', 'Nu se poate actualiza identitate');
define('CantDeleteIdentity', 'Nu se poate şterge identitate');

define('AddIdentity', 'Adăugaţi identitate');
define('SettingsTabIdentities', 'Identităţi');
define('NoIdentities', 'Nicio identitate');
define('NoSignature', 'Nicio semnătură');
define('Account', 'Cont');
define('TabChangePassword', 'Parolă');
define('SignatureEnteringHere', 'Începeţi să introduceţi semnătura dvs. aici');

define('CantConnectToMailServer', 'Eroare conexiune cu serverul de poştă electronică');

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
