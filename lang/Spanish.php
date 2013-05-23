<?php
define('PROC_ERROR_ACCT_CREATE', 'Hubo un error durante la creación de la cuenta');
define('PROC_WRONG_ACCT_PWD', 'Contraseña incorrecta');
define('PROC_CANT_LOG_NONDEF', 'No puede ingresar a la cuenta no predeterminada');
define('PROC_CANT_INS_NEW_FILTER', 'No puede agregar nuevo filtro');
define('PROC_FOLDER_EXIST', 'Nombre de carpeta existente');
define('PROC_CANT_CREATE_FLD', 'No se puede crear la carpeta');
define('PROC_CANT_INS_NEW_GROUP', 'No se puede agregar el nuevo grupo');
define('PROC_CANT_INS_NEW_CONT', 'No se puede agregar el nuevo contacto');
define('PROC_CANT_INS_NEW_CONTS', 'No se puede agregar el nuevo contacto(s)');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'No se puede agregar contacto(s) al grupo');
define('PROC_ERROR_ACCT_UPDATE', 'Hubo un error al actualizar la cuenta');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'No se puede actualizar la configuración de contactos');
define('PROC_CANT_GET_SETTINGS', 'No se puede obtener configuración');
define('PROC_CANT_UPDATE_ACCT', 'No se puede actualizar la configuración');
define('PROC_ERROR_DEL_FLD', 'Hubo un error durante el borrado de la(s) carpeta(s)');
define('PROC_CANT_UPDATE_CONT', 'No se puede actualizar el contacto');
define('PROC_CANT_GET_FLDS', 'No se puede obtener el árbol de carpetas');
define('PROC_CANT_GET_MSG_LIST', 'No se puede obtener la lista de mensajes');
define('PROC_MSG_HAS_DELETED', 'Este mensaje ya ha sido borrado del servidor de correo');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'No se pueden cargar las configuraciones de contactos');
define('PROC_CANT_LOAD_SIGNATURE', 'No se puede cargar la firma de la cuenta');
define('PROC_CANT_GET_CONT_FROM_DB', 'No se puede obtener contacto de la base de datos');
define('PROC_CANT_GET_CONTS_FROM_DB', 'No se pueden obtener los contacto(s) de la base de datos');
define('PROC_CANT_DEL_ACCT_BY_ID', 'No se puede eliminar la cuenta por id');
define('PROC_CANT_DEL_FILTER_BY_ID', 'No se puede eliminar el filtro por id');
define('PROC_CANT_DEL_CONT_GROUPS', 'No se pueden eliminar contacto(s) y/o grupos');
define('PROC_WRONG_ACCT_ACCESS', 'Se ha detectado un intento de acceso no autorizado a la cuenta de otro usuario');
define('PROC_SESSION_ERROR', 'La última sesión fue terminada debido a que se ha excedido el tiempo de espera.');

define('MailBoxIsFull', 'Buzón lleno');
define('WebMailException', 'Ocurrió una excepción');
define('InvalidUid', 'UID de mensaje inválido');
define('CantCreateContactGroup', 'No se puede crear el grupo de contactos');
define('CantCreateUser', 'No se puede crear el usuario');
define('CantCreateAccount', 'No se puede crear la cuenta');
define('SessionIsEmpty', 'Sesión vacia');
define('FileIsTooBig', 'El archivo es demasiado grande');

define('PROC_CANT_MARK_ALL_MSG_READ', 'No se pueden marcar todos los mensajes como leídos');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'No se pueden marcar todos los mensajes como no leídos');
define('PROC_CANT_PURGE_MSGS', 'No se pueden depurar mensaje(s)');
define('PROC_CANT_DEL_MSGS', 'No se pueden eliminar mensaje(s)');
define('PROC_CANT_UNDEL_MSGS', 'No se puede deshacer la eliminación de mensaje(s)');
define('PROC_CANT_MARK_MSGS_READ', 'No se pueden marcar el/los mensaje(s) como leídos');
define('PROC_CANT_MARK_MSGS_UNREAD', 'No se pueden marcar el/los mensaje(s) como no leídos');
define('PROC_CANT_SET_MSG_FLAGS', 'No se pueden habilitar las marcas de los mensaje(s)');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'No se puede eliminar las marcas del mensaje(s)');
define('PROC_CANT_CHANGE_MSG_FLD', 'No se puede cambiar la carpeta de los mensaje(s)');
define('PROC_CANT_SEND_MSG', 'No se puede enviar el mensaje.');
define('PROC_CANT_SAVE_MSG', 'No se puede guardar el mensaje.');
define('PROC_CANT_GET_ACCT_LIST', 'No se puede obtener la lista de cuentas');
define('PROC_CANT_GET_FILTER_LIST', 'No se puede obtener la lista de filtros');

define('PROC_CANT_LEAVE_BLANK', 'No se pueden dejar en blanco los campos con *');

define('PROC_CANT_UPD_FLD', 'No se puede actualizar la carpeta');
define('PROC_CANT_UPD_FILTER', 'No se puede actualizar el filtro');

define('ACCT_CANT_ADD_DEF_ACCT', 'Esta cuenta no puede ser agregada porque está siendo usada como la cuenta predeterminada poor otro usuario.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'El estado de esta cuenta no puede ser cambiado a predeterminado.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'No se puede crear una nueva cuenta (error de conexión IMAP4)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'No se puede eliminar la última cuenta predeterminada');

define('LANG_LoginInfo', 'Información de Usuario');
define('LANG_Email', 'Email');
define('LANG_Login', 'Usuario');
define('LANG_Password', 'Clave');
define('LANG_IncServer', 'Mail&nbsp;Entrante');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Puerto');
define('LANG_OutServer', 'Mail&nbsp;Saliente');
define('LANG_OutPort', 'Puerto');
define('LANG_UseSmtpAuth', 'Usar&nbsp;Autenticación&nbsp;SMTP');
define('LANG_SignMe', 'Recordarme en este equipo');
define('LANG_Enter', 'Ingresar');

// interface strings

define('JS_LANG_TitleLogin', 'Ingresar');
define('JS_LANG_TitleMessagesListView', 'Lista Mensajes');
define('JS_LANG_TitleMessagesList', 'Lista Mensajes');
define('JS_LANG_TitleViewMessage', 'Ver Mensaje');
define('JS_LANG_TitleNewMessage', 'Nuevo Mensaje');
define('JS_LANG_TitleSettings', 'Configuraciones');
define('JS_LANG_TitleContacts', 'Contactos');

define('JS_LANG_StandardLogin', 'Ingreso&nbsp;Standard');
define('JS_LANG_AdvancedLogin', 'Ingreso&nbsp;Avanzado');

define('JS_LANG_InfoWebMailLoading', 'Cargando mails&hellip;por favor esperar&hellip;');
define('JS_LANG_Loading', 'Cargando&hellip;');
define('JS_LANG_InfoMessagesLoad', 'Cargando lista de mensajes&hellip;por favor esperar');
define('JS_LANG_InfoEmptyFolder', 'Carpeta vacia');
define('JS_LANG_InfoPageLoading', 'La página está aún cargando&hellip;');
define('JS_LANG_InfoSendMessage', 'El mensaje fue enviado');
define('JS_LANG_InfoSaveMessage', 'El mensaje fue grabado');
define('JS_LANG_InfoHaveImported', 'Usted ha importado');
define('JS_LANG_InfoNewContacts', 'nuevos contactos(s) en su lista de contactos.');
define('JS_LANG_InfoToDelete', 'A Borrar');
define('JS_LANG_InfoDeleteContent', 'carpeta que tiene que borrar todo su contenido primero.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'No se permite borrar carpetas no vacias. Para borrar carpetas borre su contenido primero.');
define('JS_LANG_InfoRequiredFields', '* campo requerido');

define('JS_LANG_ConfirmAreYouSure', 'Esta Seguro?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Los mensaje(s) seleccionado(s) será eliminados DEFINITIVAMENTE! Está seguro?');
define('JS_LANG_ConfirmSaveSettings', 'Las configuraciones no fueron guardadas. Seleccione OK para guardarlas.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Las configuraciones de contactos no fueron guardadas. Seleccione OK para guardarlas.');
define('JS_LANG_ConfirmSaveAcctProp', 'Las propiedades de la cuenta no fueron guardadas. Seleccione OK para guardarlas.');
define('JS_LANG_ConfirmSaveFilter', 'Las propiedades de filtros no fueron guardadas. Seleccione OK para guardarlas.');
define('JS_LANG_ConfirmSaveSignature', 'La firma no fue guardada. Seleccione OK para guardarlas.');
define('JS_LANG_ConfirmSavefolders', 'Las carpetas no fueron guardadas. Seleccione OK para guardarlas.');
define('JS_LANG_ConfirmHtmlToPlain', 'Advertencia: Cambiando el formato de este mensaje de HTML a texto plano, perderá cualquier formato que posea en el mensaje. Seleccione OK para continuar.');
define('JS_LANG_ConfirmAddFolder', 'Antes de agregar una carpeta es necesario aplicar los cambios. Seleccione OK para guardarlas.');
define('JS_LANG_ConfirmEmptySubject', 'El campo asunto esta vacio. Desea continuar ?');

define('JS_LANG_WarningEmailBlank', 'No puede dejar el campo<br />Email: vacio');
define('JS_LANG_WarningLoginBlank', 'No puede dejar el campo<br />Usuario: vacio');
define('JS_LANG_WarningToBlank', 'No puede dejar el campo Para: vacio');
define('JS_LANG_WarningServerPortBlank', 'No puede dejar los campos POP3 y<br />servidor/puerto SMTP vacios');
define('JS_LANG_WarningEmptySearchLine', 'Linea búsqueda vacia. Por favor ingrese una porción del texto que necesita buscar');
define('JS_LANG_WarningMarkListItem', 'Por favor, marcar al menos un elemento de la lista');
define('JS_LANG_WarningFolderMove', 'La carpeta no puede ser movida porque está en otro nivel');
define('JS_LANG_WarningContactNotComplete', 'Por favor ingrese mail o nombre');
define('JS_LANG_WarningGroupNotComplete', 'Por favor ingrese un nombre de grupo');

define('JS_LANG_WarningEmailFieldBlank', 'No puede dejar el campo Email vacio');
define('JS_LANG_WarningIncServerBlank', 'No puede dejar el campo Servidor POP3(IMAP4) vacio');
define('JS_LANG_WarningIncPortBlank', 'No puede dejar el campo Puerto Servidor POP3(IMAP4) vacio');
define('JS_LANG_WarningIncLoginBlank', 'No puede dejar el campo Usuario POP3(IMAP4) vacio');
define('JS_LANG_WarningIncPortNumber', 'Debería especificar un número positivo en el campo puerto POP3(IMAP4).');
define('JS_LANG_DefaultIncPortNumber', 'El número de puerto POP3(IMAP4) predeterminado es 110(143).');
define('JS_LANG_WarningIncPassBlank', 'No puede dejar el campo Clave POP3(IMAP4) vacio');
define('JS_LANG_WarningOutPortBlank', 'No puede dejar el Puerto del Servidor SMTP vacio');
define('JS_LANG_WarningOutPortNumber', 'Debería especificar un número positivo en el campo puerto SMTP.');
define('JS_LANG_WarningCorrectEmail', 'Debe especificar una e-mail válido.');
define('JS_LANG_DefaultOutPortNumber', 'El puerto SMTP predeterminado es el 25.');

define('JS_LANG_WarningCsvExtention', 'La extensión debe ser .csv');
define('JS_LANG_WarningImportFileType', 'Por favor seleccione la aplicación desde la que desea copiar sus contactos');
define('JS_LANG_WarningEmptyImportFile', 'Por favor seleccione un archivo presionando el botón Seleccionar');

define('JS_LANG_WarningContactsPerPage', 'El valor contactos por página es un número positivo');
define('JS_LANG_WarningMessagesPerPage', 'El valor mensajes por página es un número positivo');
define('JS_LANG_WarningMailsOnServerDays', 'Debe especificar un número positivo en el campo Mensajes en servidor (días).');
define('JS_LANG_WarningEmptyFilter', 'Por favor ingrese un texto');
define('JS_LANG_WarningEmptyFolderName', 'Por favor ingrese un nombre de carpeta');

define('JS_LANG_ErrorConnectionFailed', 'Conexión fallida');
define('JS_LANG_ErrorRequestFailed', 'La transferencia de datos no ha sido completada');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'El objeto XMLHttpRequest está ausente');
define('JS_LANG_ErrorWithoutDesc', 'Ocurrió un error sin descripción');
define('JS_LANG_ErrorParsing', 'Error al parsear XML.');
define('JS_LANG_ResponseText', 'Texto Respuesta:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Paquete XML vacio');
define('JS_LANG_ErrorImportContacts', 'Error al importar contactos');
define('JS_LANG_ErrorNoContacts', 'Sin contactos para importar');
define('JS_LANG_ErrorCheckMail', 'Recepción de mensajes terminada debido a un error. Probablemente, no todos los mensajes se han recibido.');

define('JS_LANG_LoggingToServer', 'Ingresando al servidor&hellip;');
define('JS_LANG_GettingMsgsNum', 'Obteniendo cantidad de mensajes');
define('JS_LANG_RetrievingMessage', 'Obteniendo mensajes');
define('JS_LANG_DeletingMessage', 'Borrando mensajes');
define('JS_LANG_DeletingMessages', 'Borrando mensaje(s)');
define('JS_LANG_Of', 'de');
define('JS_LANG_Connection', 'Conexión');
define('JS_LANG_Charset', 'Juego de Caracteres');
define('JS_LANG_AutoSelect', 'Seleccionar Automáticamente');

define('JS_LANG_Contacts', 'Contactos');
define('JS_LANG_ClassicVersion', 'Versión clásica');
define('JS_LANG_Logout', 'Salir');
define('JS_LANG_Settings', 'Configuración');

define('JS_LANG_LookFor', 'Buscar: ');
define('JS_LANG_SearchIn', 'Buscan en: ');
define('JS_LANG_QuickSearch', 'Buscar en campos De, Para y Asunto solamente (rápido).');
define('JS_LANG_SlowSearch', 'Buscar mensaje completo');
define('JS_LANG_AllMailFolders', 'Todas las Carpetas');
define('JS_LANG_AllGroups', 'Todos los Grupos');

define('JS_LANG_NewMessage', 'Nuevo Mensaje');
define('JS_LANG_CheckMail', 'Revisar Mail');
define('JS_LANG_EmptyTrash', 'Papelera Vacia');
define('JS_LANG_MarkAsRead', 'Marcar como Leído');
define('JS_LANG_MarkAsUnread', 'Marcar como No Leído');
define('JS_LANG_MarkFlag', 'Marcar');
define('JS_LANG_MarkUnflag', 'Desmarcar');
define('JS_LANG_MarkAllRead', 'Marcar Todos Leídos');
define('JS_LANG_MarkAllUnread', 'Marcar Todos No Leídos');
define('JS_LANG_Reply', 'Responder');
define('JS_LANG_ReplyAll', 'Responder a todos');
define('JS_LANG_Delete', 'Borrar');
define('JS_LANG_Undelete', 'Deshacer');
define('JS_LANG_PurgeDeleted', 'Depurar borrados');
define('JS_LANG_MoveToFolder', 'Mover a Carpeta');
define('JS_LANG_Forward', 'Reenviar');

define('JS_LANG_HideFolders', 'Ocultar Carpetas');
define('JS_LANG_ShowFolders', 'Ver Carpetas');
define('JS_LANG_ManageFolders', 'Administrar Carpetas');
define('JS_LANG_SyncFolder', 'Sincronizar Carpetas');
define('JS_LANG_NewMessages', 'Nuevo Mensaje');
define('JS_LANG_Messages', 'Mensaje(s)');

define('JS_LANG_From', 'De');
define('JS_LANG_To', 'Para');
define('JS_LANG_Date', 'Fecha');
define('JS_LANG_Size', 'Tamaño');
define('JS_LANG_Subject', 'Asunto');

define('JS_LANG_FirstPage', 'Primera Página');
define('JS_LANG_PreviousPage', 'Página Anterior');
define('JS_LANG_NextPage', 'Siguiente Página');
define('JS_LANG_LastPage', 'Última Página');

define('JS_LANG_SwitchToPlain', 'Cambiar a vista Plana');
define('JS_LANG_SwitchToHTML', 'Cambiar a vista HTML');
define('JS_LANG_AddToAddressBook', 'Agregar a Agenda de Contactos');
define('JS_LANG_ClickToDownload', 'Click para descargar');
define('JS_LANG_View', 'Vista');
define('JS_LANG_ShowFullHeaders', 'Ver Encabezados Completos');
define('JS_LANG_HideFullHeaders', 'Ocultar Encabezados Completos');

define('JS_LANG_MessagesInFolder', 'Mensajes(s) en Carpeta');
define('JS_LANG_YouUsing', 'Estás usando');
define('JS_LANG_OfYour', 'de tus');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Enviar');
define('JS_LANG_SaveMessage', 'Guardar');
define('JS_LANG_Print', 'Imprimir');
define('JS_LANG_PreviousMsg', 'Mensaje Anterior');
define('JS_LANG_NextMsg', 'Mensaje Siguiente');
define('JS_LANG_AddressBook', 'Agenda Contactos');
define('JS_LANG_ShowBCC', 'Mostrar BCC');
define('JS_LANG_HideBCC', 'Ocultar BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Responder&nbsp;A');
define('JS_LANG_AttachFile', 'Adjuntar Archivo');
define('JS_LANG_Attach', 'Adjuntar');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Mensaje Original');
define('JS_LANG_Sent', 'Enviado');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Baja');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Alta');
define('JS_LANG_Importance', 'Importante');
define('JS_LANG_Close', 'Cerrar');

define('JS_LANG_Common', 'Común');
define('JS_LANG_EmailAccounts', 'Cuentas EMail');

define('JS_LANG_MsgsPerPage', 'Mensajes por página');
define('JS_LANG_DisableRTE', 'Desabilitar editor texto enriquecido');
define('JS_LANG_Skin', 'Skin');
define('JS_LANG_DefCharset', 'Juego caracteres predeterminado');
define('JS_LANG_DefCharsetInc', 'Juego caracteres entrantes predeterminado');
define('JS_LANG_DefCharsetOut', 'Juego caracteres salientes predeterminado');
define('JS_LANG_DefTimeOffset', 'Zona horaria predeterminada');
define('JS_LANG_DefLanguage', 'Lenguaje predeterminado');
define('JS_LANG_DefDateFormat', 'Formato fecha predeterminado');
define('JS_LANG_ShowViewPane', 'Lista de mensajes con panel de previsualización');
define('JS_LANG_Save', 'Guardar');
define('JS_LANG_Cancel', 'Cancelar');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Eliminar');
define('JS_LANG_AddNewAccount', 'Agregar Nueva Cuenta');
define('JS_LANG_Signature', 'Firma');
define('JS_LANG_Filters', 'Filtros');
define('JS_LANG_Properties', 'Propiedades');
define('JS_LANG_UseForLogin', 'Usar propiedades de esta cuenta (usuario y clave) para ingresar');
define('JS_LANG_MailFriendlyName', 'Tu nombre');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Correo Entrante');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Puerto');
define('JS_LANG_MailIncLogin', 'Usuario');
define('JS_LANG_MailIncPass', 'Clave');
define('JS_LANG_MailOutHost', 'Correo Saliente');
define('JS_LANG_MailOutPort', 'Puerto');
define('JS_LANG_MailOutLogin', 'Usuario SMTP');
define('JS_LANG_MailOutPass', 'Clave SMTP');
define('JS_LANG_MailOutAuth1', 'Usar autenticación SMTP');
define('JS_LANG_MailOutAuth2', '(Debe dejar los campos usuario/clave SMTP, si son las mismas que para POP3/IMAP4)');
define('JS_LANG_UseFriendlyNm1', 'Usar Nombre Amigable en el campo "De:"');
define('JS_LANG_UseFriendlyNm2', '(Tu nombre &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Obtener/sincronizar mails al ingresar');
define('JS_LANG_MailMode0', 'Eliminar mensajes recibidos del servidor');
define('JS_LANG_MailMode1', 'Dejar mensajes en el servidor');
define('JS_LANG_MailMode2', 'Mantener mensajes en servidor por');
define('JS_LANG_MailsOnServerDays', 'día(s)');
define('JS_LANG_MailMode3', 'Eliminar mensaje del servidor cuando es eliminado de Papelera');
define('JS_LANG_InboxSyncType', 'Tipo de Sincronización de Bandeja de Entrada');

define('JS_LANG_SyncTypeNo', 'No Sincronizar');
define('JS_LANG_SyncTypeNewHeaders', 'Nuevos Encabezados');
define('JS_LANG_SyncTypeAllHeaders', 'Todos los Encabezados');
define('JS_LANG_SyncTypeNewMessages', 'Nuevos Mensajes');
define('JS_LANG_SyncTypeAllMessages', 'Todos los Mensajes');
define('JS_LANG_SyncTypeDirectMode', 'Modo Directo');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Encabezados Solamente');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Mensajes Completos');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Modo Directo');

define('JS_LANG_DeleteFromDb', 'Borrar mensajes de la base de datos si no existen más en el servidor');

define('JS_LANG_EditFilter', 'Editar filtro');
define('JS_LANG_NewFilter', 'Agregar nuevo filtro');
define('JS_LANG_Field', 'Campo');
define('JS_LANG_Condition', 'Condición');
define('JS_LANG_ContainSubstring', 'Contener texto');
define('JS_LANG_ContainExactPhrase', 'Contener frase exacta');
define('JS_LANG_NotContainSubstring', 'No conteniendo texto');
define('JS_LANG_FilterDesc_At', 'a');
define('JS_LANG_FilterDesc_Field', 'campo');
define('JS_LANG_Action', 'Acción');
define('JS_LANG_DoNothing', 'Hacer Nada');
define('JS_LANG_DeleteFromServer', 'Borrar del server inmediatamente');
define('JS_LANG_MarkGrey', 'Marcar gris');
define('JS_LANG_Add', 'Agregar');
define('JS_LANG_OtherFilterSettings', 'Otras configuraciones de filtros');
define('JS_LANG_ConsiderXSpam', 'Considerar encabezados X-Spam');
define('JS_LANG_Apply', 'Aplicar');

define('JS_LANG_InsertLink', 'Insertar Link');
define('JS_LANG_RemoveLink', 'Eliminar Link');
define('JS_LANG_Numbering', 'Enumerar');
define('JS_LANG_Bullets', 'Viñetas');
define('JS_LANG_HorizontalLine', 'Linea Horizontal');
define('JS_LANG_Bold', 'Negrita');
define('JS_LANG_Italic', 'Italica');
define('JS_LANG_Underline', 'Subrayar');
define('JS_LANG_AlignLeft', 'Alinear Izquierda');
define('JS_LANG_Center', 'Centrar');
define('JS_LANG_AlignRight', 'Alinear Derecha');
define('JS_LANG_Justify', 'Justificar');
define('JS_LANG_FontColor', 'Color Letra');
define('JS_LANG_Background', 'Fondo');
define('JS_LANG_SwitchToPlainMode', 'Cambiar a Modo Texto');
define('JS_LANG_SwitchToHTMLMode', 'Cambiar a Modo HTML');

define('JS_LANG_Folder', 'Carpeta');
define('JS_LANG_Msgs', 'Msg\'s');
define('JS_LANG_Synchronize', 'Sincronizar');
define('JS_LANG_ShowThisFolder', 'Ver esta Carpeta');
define('JS_LANG_Total', 'Total');
define('JS_LANG_DeleteSelected', 'Borrar Seleccionados');
define('JS_LANG_AddNewFolder', 'Agregar Nueva Carpeta');
define('JS_LANG_NewFolder', 'Nueva Carpeta');
define('JS_LANG_ParentFolder', 'Carpeta Padre');
define('JS_LANG_NoParent', 'Sin Padres');
define('JS_LANG_FolderName', 'Nombre Carpeta');

define('JS_LANG_ContactsPerPage', 'Contactos por Página');
define('JS_LANG_WhiteList', 'Lista de Contactos como Lista Blanca');

define('JS_LANG_CharsetDefault', 'Predeterminado');
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

define('JS_LANG_TimeDefault', 'Predeterminado');
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

define('JS_LANG_DateDefault', 'Predeterminado');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Mes (01 Ene)');
define('JS_LANG_DateAdvanced', 'Avanzado');

define('JS_LANG_NewContact', 'Nuevo Contacto');
define('JS_LANG_NewGroup', 'Nuevo Grupo');
define('JS_LANG_AddContactsTo', 'Agregar Contactos A');
define('JS_LANG_ImportContacts', 'Importar Contactos');

define('JS_LANG_Name', 'Nombre');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Email Predeterminado');
define('JS_LANG_NotSpecifiedYet', 'No Especificado');
define('JS_LANG_ContactName', 'Nombre');
define('JS_LANG_Birthday', 'Cumpleaños');
define('JS_LANG_Month', 'Mes');
define('JS_LANG_January', 'Enero');
define('JS_LANG_February', 'Febrero');
define('JS_LANG_March', 'Marzo');
define('JS_LANG_April', 'Abril');
define('JS_LANG_May', 'Mayo');
define('JS_LANG_June', 'Junio');
define('JS_LANG_July', 'Julio');
define('JS_LANG_August', 'Agosto');
define('JS_LANG_September', 'Septiembre');
define('JS_LANG_October', 'Octubre');
define('JS_LANG_November', 'Noviembre');
define('JS_LANG_December', 'Diciembre');
define('JS_LANG_Day', 'Día');
define('JS_LANG_Year', 'Año');
define('JS_LANG_UseFriendlyName1', 'Usar Nombre Amigable');
define('JS_LANG_UseFriendlyName2', '(por ejemplo, Juan Garcia &lt;juangarcia@mail.com&gt;)');
define('JS_LANG_Personal', 'Personal');
define('JS_LANG_PersonalEmail', 'E-mail Personal');
define('JS_LANG_StreetAddress', 'Calle');
define('JS_LANG_City', 'Ciudad');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Estado/Provincia');
define('JS_LANG_Phone', 'Tel.');
define('JS_LANG_ZipCode', 'Cod. Postal');
define('JS_LANG_Mobile', 'Celular');
define('JS_LANG_CountryRegion', 'País/Región');
define('JS_LANG_WebPage', 'Página Web');
define('JS_LANG_Go', 'Ir');
define('JS_LANG_Home', 'Casa');
define('JS_LANG_Business', 'Trabajo');
define('JS_LANG_BusinessEmail', 'E-mail Laboral');
define('JS_LANG_Company', 'Empresa');
define('JS_LANG_JobTitle', 'Cargo');
define('JS_LANG_Department', 'Departamento');
define('JS_LANG_Office', 'Oficina');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Otros');
define('JS_LANG_OtherEmail', 'Otro E-mail');
define('JS_LANG_Notes', 'Notas');
define('JS_LANG_Groups', 'Grupos');
define('JS_LANG_ShowAddFields', 'Ver campos adicionales');
define('JS_LANG_HideAddFields', 'Ocultar campos adicionales');
define('JS_LANG_EditContact', 'Editar información Contacto');
define('JS_LANG_GroupName', 'Nombre Grupo');
define('JS_LANG_AddContacts', 'Agregar Contactos');
define('JS_LANG_CommentAddContacts', '(Si agregará más de una dirección, por favor separarla con comas)');
define('JS_LANG_CreateGroup', 'Crear Grupo');
define('JS_LANG_Rename', 'renombrar');
define('JS_LANG_MailGroup', 'Grupo Mails');
define('JS_LANG_RemoveFromGroup', 'Eliminar del grupo');
define('JS_LANG_UseImportTo', 'Usar importar para copiar tus contactos desde Microsoft Outlook, Microsoft Outlook Express en tu lista de contactos.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Seleccionar archivo  (formato .CSV) que quieres importar');
define('JS_LANG_Import', 'Importar');
define('JS_LANG_ContactsMessage', 'Esta es una página de Contactos!!!');
define('JS_LANG_ContactsCount', 'contacto(s)');
define('JS_LANG_GroupsCount', 'grupo(s)');

// webmail 4.1 constants
define('PicturesBlocked', 'Las imágenes en este mensaje han sido bloqueadas por su seguridad.');
define('ShowPictures', 'Mostrar Imágenes');
define('ShowPicturesFromSender', 'Siempre mostrar imágenes en mensajes de este emisor');
define('AlwaysShowPictures', 'Siempre mostrar imágenes en mensajes');

define('TreatAsOrganization', 'Tratar como una Organización');

define('WarningGroupAlreadyExist', 'Ya existe un grupo con este nombre, por favor especificar otro nombre.');
define('WarningCorrectFolderName', 'Debe especificar un nombre de carpeta correcto.');
define('WarningLoginFieldBlank', 'No puede dejar el campo Usuario en blanco.');
define('WarningCorrectLogin', 'Debe especificar un usuario correcto.');
define('WarningPassBlank', 'No puede dejar el campo Clave en blanco.');
define('WarningCorrectIncServer', 'Debe especificar un servidor POP3(IMAP) válido.');
define('WarningCorrectSMTPServer', 'Debe especificar una dirección de correo saliente correcta.');
define('WarningFromBlank', 'No puede dejar el campo De: vacio.');
define('WarningAdvancedDateFormat', 'Por favor especifique un formato fecha-hora.');

define('AdvancedDateHelpTitle', 'Fecha Avanzada');
define('AdvancedDateHelpIntro', 'Cuando el campo &quot;Avanzado&quot; está seleccionado, puede usar el campo de texto para configurar su propio formato de fecha. Las siguientes opciones son utilizadas para éste propósito junto con los delimitadores \':\' o \'/\':');
define('AdvancedDateHelpConclusion', 'Por ejemplo, si especifica el valor &quot;mm/dd/yyyy&quot; en el campo de texto de &quot;Avanzado&quot;, la fecha es visualizada como mes/día/año (ej. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Día del mes (1 a 31)');
define('AdvancedDateHelpNumericMonth', 'Mes (1 a 12)');
define('AdvancedDateHelpTextualMonth', 'Mes (Ene a Dic)');
define('AdvancedDateHelpYear2', 'Año, 2 dígitos');
define('AdvancedDateHelpYear4', 'Año, 4 dígitos');
define('AdvancedDateHelpDayOfYear', 'Día del año (1 a 366)');
define('AdvancedDateHelpQuarter', 'Trimestre');
define('AdvancedDateHelpDayOfWeek', 'Día de semana (Lun a Dom)');
define('AdvancedDateHelpWeekOfYear', 'Semana del año (1 a 53)');

define('InfoNoMessagesFound', 'No se encontraron mensajes.');
define('ErrorSMTPConnect', 'No se puede contactar al servidor SMTP. Revise la configuración del servidor SMTP.');
define('ErrorSMTPAuth', 'Usuario y/o clave incorrectos. Autenticación fallida.');
define('ReportMessageSent', 'Su mensaje ha sido enviadp.');
define('ReportMessageSaved', 'Su mensake ha sido guardado.');
define('ErrorPOP3Connect', 'No se puede contactar al servidor POP3, revise la configuración el servidor POP3.');
define('ErrorIMAP4Connect', 'No se puede conectar al servidor IMAP4, revise la configuración el servidor IMAP4.');
define('ErrorPOP3IMAP4Auth', 'Email/usuario y/o clave incorrectos. Autenticación fallida.');
define('ErrorGetMailLimit', 'Su buzón de correo ha excedido el tamaño límite.');

define('ReportSettingsUpdatedSuccessfuly', 'Configuraciones actualizadas satisfactoriamente.');
define('ReportAccountCreatedSuccessfuly', 'Cuenta creada satisfactoriamente.');
define('ReportAccountUpdatedSuccessfuly', 'Cuenta creada satisfactoriamente.');
define('ConfirmDeleteAccount', 'Está seguro de eliminar la cuenta?');
define('ReportFiltersUpdatedSuccessfuly', 'Filtros actualizados satisfactoriamente.');
define('ReportSignatureUpdatedSuccessfuly', 'Firma actualizada satisfactoriamente.');
define('ReportFoldersUpdatedSuccessfuly', 'Carpetas actualizadas satisfactoriamente.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Configuración de contactos actualizada satisfactoriamente.');

define('ErrorInvalidCSV', 'El archivo CSV seleccionado tiene un formato inválido.');
//El grupo "guies" fue agregado satisfactoriamente.
define('ReportGroupSuccessfulyAdded1', 'El grupo');
define('ReportGroupSuccessfulyAdded2', 'fue satisfactoriamente agregado.');
define('ReportGroupUpdatedSuccessfuly', 'Grupo actualizado satisfactoriamente.');
define('ReportContactSuccessfulyAdded', 'Contacto agregado satisfactoriamente.');
define('ReportContactUpdatedSuccessfuly', 'Contacto actualizado satisfactoriamente.');
//Contacto(s) agregados al grupo "amigos".
define('ReportContactAddedToGroup', 'Contacto(s) agregados al grupo');
define('AlertNoContactsGroupsSelected', 'No hay contactos o grupos seleccionados.');

define('InfoListNotContainAddress', 'Si la lista no contiene la dirección que está buscando, pruebe digitando sus primeras letras.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Modo Directo. WebMail accede a los mensajes directamente desde el servidor.');

define('FolderInbox', 'Bandeja de Entrada');
define('FolderSentItems', 'Elementos Enviados');
define('FolderDrafts', 'Borrador');
define('FolderTrash', 'Papelera');

define('FileLargerAttachment', 'El tamaño del archivo excede el límite máximo permitido para adjuntos.');
define('FilePartiallyUploaded', 'Solo una parte del archivo fue subida debido a un error desconocido.');
define('NoFileUploaded', 'Los archivos no fueron archivos.');
define('MissingTempFolder', 'Falta la carpeta temporal.');
define('MissingTempFile', 'Falta el archivo temporal.');
define('UnknownUploadError', 'Ocurrido un error desconocido al subir archivos.');
define('FileLargerThan', 'Error al subir archivo. Muy probablemente, el archivo es mayor a ');
define('PROC_CANT_LOAD_DB', 'No se puede contactar a la base de datos.');
define('PROC_CANT_LOAD_LANG', 'No se encuentra archivo de lenguaje requerido.');
define('PROC_CANT_LOAD_ACCT', 'La cuenta no existe, quizás, fue simplemente eliminada.');

define('DomainDosntExist', 'Dominio inexistente en el servidor de correo.');
define('ServerIsDisable', 'Usar servidor de correo está prohibido por el administrador.');

define('PROC_ACCOUNT_EXISTS', 'La cuenta no puede ser creada porque ya existe.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'No se puede obtener la cantidad de mensajes de la carpeta.');
define('PROC_CANT_MAIL_SIZE', 'No se puede obtener el tamaño de almacenamiento de mails.');

define('Organization', 'Organización');
define('WarningOutServerBlank', 'No puede dejar el campo Correo Saliente en blanco');

//
define('JS_LANG_Refresh', 'Refrescar');
define('JS_LANG_MessagesInInbox', 'Mensajes(s) en Bandeja de Entrada');
define('JS_LANG_InfoEmptyInbox', 'Bandeja de Entrada vacia');

// webmail 4.2 constants
define('BackToList', 'Volver a Mails');
define('InfoNoContactsGroups', 'No hay Contactos ni Grupos.');
define('InfoNewContactsGroups', 'Puede crear un nuevos contactos/grupos o importar contactos de un archivo .CSV en formato MS Outlook.');
define('DefTimeFormat', 'Formato de hora predeterminado');
define('SpellNoSuggestions', 'Sin sugerencias');
define('SpellWait', 'Por favor espere&hellip;');

define('InfoNoMessageSelected', 'Sin mensajes seleccionados.');
define('InfoSingleDoubleClick', 'Usted puede hacer un click en cualquier mensaje de la lista para visualizarlo o doble click para visualizarlo en tamaño completo.	');

// calendar
define('TitleDay', 'Vista Diaria');
define('TitleWeek', 'Vista Semanal');
define('TitleMonth', 'Vista Mensual');

define('ErrorNotSupportBrowser', 'Calendario no soportado por su navegador. Por favor use FireFox 2.0 or superior, Opera 9.0 o superior, Internet Explorer 8.0 or superior, Safari 3.0.2 o superior.');
define('ErrorTurnedOffActiveX', 'Soporte ActiveX deshabilitado. <br/>Debe habilitarlo para poder utilizar esta aplicación.');

define('Calendar', 'Calendario');

define('TabDay', 'Día');
define('TabWeek', 'Semana');
define('TabMonth', 'Mes');

define('ToolNewEvent', 'Nuevo&nbsp;Evento');
define('ToolBack', 'Atrás');
define('ToolToday', 'Hoy');
define('AltNewEvent', 'Nuevo Evento');
define('AltBack', 'Atrás');
define('AltToday', 'Hoy');
define('CalendarHeader', 'Calendario');
define('CalendarsManager', 'Administrar Calendarios');

define('CalendarActionNew', 'Nuevo Calendario');
define('EventHeaderNew', 'Nuevo Evento');
define('CalendarHeaderNew', 'Nuevo Calendario');

define('EventSubject', 'Asunto');
define('EventCalendar', 'Calendario');
define('EventFrom', 'Desde');
define('EventTill', 'hasta');
define('CalendarDescription', 'Descripcion');
define('CalendarColor', 'Color');
define('CalendarName', 'Nombre Calendario');
define('CalendarDefaultName', 'Mi Calendario');

define('ButtonSave', 'Guardar');
define('ButtonCancel', 'Cancelar');
define('ButtonDelete', 'Borrar');

define('AltPrevMonth', 'Mes Anterior');
define('AltNextMonth', 'Mes Siguiente');

define('CalendarHeaderEdit', 'Editar Calendario');
define('CalendarActionEdit', 'Editar Calendario');
define('ConfirmDeleteCalendar', 'Está seguro que desea borrar el calendario');
define('InfoDeleting', 'Borrando&hellip;');
define('WarningCalendarNameBlank', 'No puede dejar el nombre de calendario en blanco.');
define('ErrorCalendarNotCreated', 'Calendario no creado.');
define('WarningSubjectBlank', 'No puede dejar el asunto en blanco.');
define('WarningIncorrectTime', 'La hora especificada contiene caracteres no válidos.');
define('WarningIncorrectFromTime', 'La hora desde es incorrecta.');
define('WarningIncorrectTillTime', 'La hora hasta es incorrecta.');
define('WarningStartEndDate', 'La fecha de fin debe ser mayor o igual a la fecha de inicio.');
define('WarningStartEndTime', 'La hora de fin debe ser mayor o igual a la hora de inicio.');
define('WarningIncorrectDate', 'La fecha debe ser correcta.');
define('InfoLoading', 'Cargando&hellip;');
define('EventCreate', 'Crear Evento');
define('CalendarHideOther', 'Ocultar otros Calendarios');
define('CalendarShowOther', 'Ver otros Calendarios');
define('CalendarRemove', 'Eliminar Calendario');
define('EventHeaderEdit', 'Editar Evento');

define('InfoSaving', 'Guardando&hellip;');
define('SettingsDisplayName', 'Mostrar Nombre');
define('SettingsTimeFormat', 'Formato Hora');
define('SettingsDateFormat', 'Formato Fecha');
define('SettingsShowWeekends', 'Mostrar semanas');
define('SettingsWorkdayStarts', 'Día laborable inicia');
define('SettingsWorkdayEnds', 'finaliza');
define('SettingsShowWorkday', 'Mostrar día laborable');
define('SettingsWeekStartsOn', 'Semana comienza el');
define('SettingsDefaultTab', 'Ficha Predeterminada');
define('SettingsCountry', 'País');
define('SettingsTimeZone', 'Zona Horaria');
define('SettingsAllTimeZones', 'Todas las zonas horarias');

define('WarningWorkdayStartsEnds', 'El \'Día laborable finaliza\' debe ser mayor que el \'Día laborable inicia\'');
define('ReportSettingsUpdated', 'Configuraciones actualizadas satisfactoriamente.');

define('SettingsTabCalendar', 'Calendario');

define('FullMonthJanuary', 'Enero');
define('FullMonthFebruary', 'Febrero');
define('FullMonthMarch', 'Marzo');
define('FullMonthApril', 'Abrirl');
define('FullMonthMay', 'Mayo');
define('FullMonthJune', 'Junio');
define('FullMonthJuly', 'Julio');
define('FullMonthAugust', 'Agosto');
define('FullMonthSeptember', 'Septiembre');
define('FullMonthOctober', 'Octubre');
define('FullMonthNovember', 'Noviembre');
define('FullMonthDecember', 'Diciembre');

define('ShortMonthJanuary', 'Ene');
define('ShortMonthFebruary', 'Feb');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Abr');
define('ShortMonthMay', 'May');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Ago');
define('ShortMonthSeptember', 'Sep');
define('ShortMonthOctober', 'Oct');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dic');

define('FullDayMonday', 'Lunes');
define('FullDayTuesday', 'Martes');
define('FullDayWednesday', 'Miercoles');
define('FullDayThursday', 'Jueves');
define('FullDayFriday', 'Viernes');
define('FullDaySaturday', 'Sabado');
define('FullDaySunday', 'Domingo');

define('DayToolMonday', 'Lun');
define('DayToolTuesday', 'Mar');
define('DayToolWednesday', 'Mie');
define('DayToolThursday', 'Jue');
define('DayToolFriday', 'Vie');
define('DayToolSaturday', 'Sab');
define('DayToolSunday', 'Dom');

define('CalendarTableDayMonday', 'L');
define('CalendarTableDayTuesday', 'M');
define('CalendarTableDayWednesday', 'M');
define('CalendarTableDayThursday', 'J');
define('CalendarTableDayFriday', 'V');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'D');

define('ErrorParseJSON', 'La respuesta JSON devuelta por el servidor no puede ser parseada.');

define('ErrorLoadCalendar', 'No se pudo cargar calendarios');
define('ErrorLoadEvents', 'No se pudo cargar eventos');
define('ErrorUpdateEvent', 'No se pudo guardar evento');
define('ErrorDeleteEvent', 'No se pudo eliminar evento');
define('ErrorUpdateCalendar', 'No se pudo guardar calendario');
define('ErrorDeleteCalendar', 'No se pudo eliminar calendario');
define('ErrorGeneral', 'Un error ha ocurrido en el servidor. Trate nuevamente más tarde.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Compartir y Publicar calendario');
define('ShareActionEdit', 'Compartir y Publicar calendario');
define('CalendarPublicate', 'Hacer de público acceso a este calendario');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Compartir éste calendario');
define('SharePermission1', 'Puede hacer cambios y administrar compartidos');
define('SharePermission2', 'Puede hacer cambios a eventos');
define('SharePermission3', 'Puede ver todos los detalles de los eventos');
define('SharePermission4', 'Puede ver solo libre/ocupado (ocultar detalles)');
define('ButtonClose', 'Cerrar');
define('WarningEmailFieldFilling', 'Debe completar el campo e-mail primero');
define('EventHeaderView', 'Ver Evento');
define('ErrorUpdateSharing', 'No se puede guardar datos compartidos y publicaciones');
define('ErrorUpdateSharing1', 'No es posible compartir al usuario %s dado que no existe');
define('ErrorUpdateSharing2', 'Imposible compartir el calendario al usuario %s');
define('ErrorUpdateSharing3', 'Calendario ya compartido al usuario %s');
define('Title_MyCalendars', 'Mis Calendarios');
define('Title_SharedCalendars', 'Calendarios Compartidos');
define('ErrorGetPublicationHash', 'No se puede crear un link de publicación');
define('ErrorGetSharing', 'No se puede compartir');
define('CalendarPublishedTitle', 'Este calendario es publicado');
define('RefreshSharedCalendars', 'Refrescar calendarios compartidos');
define('Title_CheckSharedCalendars', 'Refrescar Calendarios');

define('GroupMembers', 'Miembros');

define('ReportMessagePartDisplayed', 'Note que solo una parte del mensaje es visualizada.');
define('ReportViewEntireMessage', 'Para ver el mensaje entero,');
define('ReportClickHere', 'clickear aquí');
define('ErrorContactExists', 'Un contacto con ese nombre e e-mail ya existe.');

define('Attachments', 'Adjuntos');

define('InfoGroupsOfContact', 'Los grupos a los cuales pertenece el contacto son marcados con tildes.');
define('AlertNoContactsSelected', 'Sin contactos seleccionados.');
define('MailSelected', 'Enviar mail a direcciones seleccionadas');
define('CaptionSubscribed', 'Suscripto');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'No Spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contacts
define('ContactMail', 'Mail contacto');
define('ContactViewAllMails', 'Ver todos los mails con este contacto');
define('ContactsMailThem', 'Enviarle un Mail');
define('DateToday', 'Hoy');
define('DateYesterday', 'Ayer');
define('MessageShowDetails', 'Ver detalles');
define('MessageHideDetails', 'Ocultar detalles');
define('MessageNoSubject', 'Sin Asunto');
// john@gmail.com a nadine@gmail.com
define('MessageForAddr', 'para');
define('SearchClear', 'Borrar Búsqueda');
// Resultados de búsqueda para "buscar texto" en bandeja de entrada:
// Resultados de búsqueda para "buscar texto" en todas las carpetas:
define('SearchResultsInFolder', 'Resultados de la búsqueda para "#s" en la carpeta #f:');
define('SearchResultsInAllFolders', 'Resultados de la búsqueda para "#s" en todas las carpetas:');
define('AutoresponderTitle', 'Autorespuesta');
define('AutoresponderEnable', 'Habilitar autorespuesta');
define('AutoresponderSubject', 'Asuento');
define('AutoresponderMessage', 'Mensaje');
define('ReportAutoresponderUpdatedSuccessfuly', 'Autorrespuesta ha sido actualizada satisfactoriamente.');
define('FolderQuarantine', 'Cuarentena');

//calendar
define('EventRepeats', 'Repeticiones');
define('NoRepeats', 'No repetir');
define('DailyRepeats', 'Diariamente');
define('WorkdayRepeats', 'Cada Semana (Lun. - Vie.)');
define('OddDayRepeats', 'Cada Lun., Mie. y Vie.');
define('EvenDayRepeats', 'Every Mar. y Jue.');
define('WeeklyRepeats', 'Semanalmente');
define('MonthlyRepeats', 'Mensualmente');
define('YearlyRepeats', 'Anualmente');
define('RepeatsEvery', 'Repetir cada');
define('ThisInstance', 'Solo esta ocurrencia');
define('AllEvents', 'Todos los eventos en la serie');
define('AllFollowing', 'Todos los siguientes');
define('ConfirmEditRepeatEvent', 'Desea cambiar solo este evento, todos los eventos, o este y todos los futuros eventos de esta serie?');
define('RepeatEventHeaderEdit', 'Editar Evento Recurrente');
define('First', 'Primero');
define('Second', 'Segundo');
define('Third', 'Tercero');
define('Fourth', 'Cuarto');
define('Last', '&Uacute;ltimo');
define('Every', 'Cada');
define('SetRepeatEventEnd', 'Fecha fin');
define('NoEndRepeatEvent', 'Sin fecha fin');
define('EndRepeatEventAfter', 'Finalizar luego');
define('Occurrences', 'ocurrencias');
define('EndRepeatEventBy', 'Finalizar el');
define('EventCommonDataTab', 'Detalles principales');
define('EventRepeatDataTab', 'Detalles recurrentes');
define('RepeatEventNotPartOfASeries', 'Este evento ha sido cambiado y no es más parte de esta serie.');
define('UndoRepeatExclusion', 'Deshacer cambios a incluir en la serie.');

define('MonthMoreLink', '%d más...');
define('NoNewSharedCalendars', 'Sin nuevos calendarios');
define('NNewSharedCalendars', '%d nuevos calendarios encontrados');
define('OneNewSharedCalendars', '1 nuevo calendario encontrado');
define('ConfirmUndoOneRepeat', 'Desea restaurar este evento en la serie?');

define('RepeatEveryDayInfin', 'Diariamente');
define('RepeatEveryDayTimes', 'Diariamente, %TIMES% veces');
define('RepeatEveryDayUntil', 'Diariamente, hasta %UNTIL%');
define('RepeatDaysInfin', 'Cada %PERIOD% días');
define('RepeatDaysTimes', 'Cada %PERIOD% días, %TIMES% veces');
define('RepeatDaysUntil', 'Cada %PERIOD% días, hasta %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Semanalmente');
define('RepeatEveryWeekWeekdaysTimes', 'Semanalmente, %TIMES% veces');
define('RepeatEveryWeekWeekdaysUntil', 'Semanalmente, hasta %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Cada %PERIOD% semanas');
define('RepeatWeeksWeekdaysTimes', 'Cada %PERIOD% semanas, %TIMES% veces');
define('RepeatWeeksWeekdaysUntil', 'Cada %PERIOD% semanas, hasta %UNTIL%');

define('RepeatEveryWeekInfin', 'Semanalmente los %DAYS%');
define('RepeatEveryWeekTimes', 'Semanalmente los %DAYS%, %TIMES% veces');
define('RepeatEveryWeekUntil', 'Semanalmente los %DAYS%, hasta %UNTIL%');
define('RepeatWeeksInfin', 'Cada %PERIOD% semanas %DAYS%');
define('RepeatWeeksTimes', 'Cada %PERIOD% semanas %DAYS%, %TIMES% veces');
define('RepeatWeeksUntil', 'Cada %PERIOD% semanas %DAYS%, hasta %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Mensualmente el día %DATE%');
define('RepeatEveryMonthDateTimes', 'Mensualmente el día %DATE%, %TIMES% veces');
define('RepeatEveryMonthDateUntil', 'Mensualmente el día %DATE%, hasta %UNTIL%');
define('RepeatMonthsDateInfin', 'Cada %PERIOD% meses el día %DATE%');
define('RepeatMonthsDateTimes', 'Cada %PERIOD% meses el día %DATE%, %TIMES% veces');
define('RepeatMonthsDateUntil', 'Cada %PERIOD% meses el día %DATE%, hasta %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Mensualmente el %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Mensualmente el %NUMBER% %DAY%, %TIMES% veces');
define('RepeatEveryMonthWDUntil', 'Mensualmente el %NUMBER% %DAY%, hasta %UNTIL%');
define('RepeatMonthsWDInfin', 'Cada %PERIOD% meses el %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Cada %PERIOD% meses el %NUMBER% %DAY%, %TIMES% veces');
define('RepeatMonthsWDUntil', 'Cada %PERIOD% meses el %NUMBER% %DAY%, hasta %UNTIL%');

define('RepeatEveryYearDateInfin', 'Anualmente el día %DATE%');
define('RepeatEveryYearDateTimes', 'Anualmente el día %DATE%, %TIMES% veces');
define('RepeatEveryYearDateUntil', 'Anualmente el día %DATE%, hasta %UNTIL%');
define('RepeatYearsDateInfin', 'Cada %PERIOD% años el día %DATE%');
define('RepeatYearsDateTimes', 'Cada %PERIOD% años el día %DATE%, %TIMES% veces');
define('RepeatYearsDateUntil', 'Cada %PERIOD% años el día %DATE%, hasta %UNTIL%');

define('RepeatEveryYearWDInfin', 'Anualmente el %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Anualmente el %NUMBER% %DAY%, %TIMES% veces');
define('RepeatEveryYearWDUntil', 'Anualmente el %NUMBER% %DAY%, hasta %UNTIL%');
define('RepeatYearsWDInfin', 'Cada %PERIOD% años el %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Cada %PERIOD% años el %NUMBER% %DAY%, %TIMES% veces');
define('RepeatYearsWDUntil', 'Cada %PERIOD% años el %NUMBER% %DAY%, hasta %UNTIL%');

define('RepeatDescDay', 'día');
define('RepeatDescWeek', 'semana');
define('RepeatDescMonth', 'mes');
define('RepeatDescYear', 'año');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Por favor especificar días de finalización de la repetición');
define('WarningWrongUntilDate', 'El día de finalización de la repetición debe ser posterior al día de comienzo');

define('OnDays', 'Los días');
define('CancelRecurrence', 'Cancelar repetición');
define('RepeatEvent', 'Repetir éste evento');

define('Spellcheck', 'Revisar Ortografía');
define('LoginLanguage', 'Idioma');
define('LanguageDefault', 'Predeterminado');

// webmail 4.5.x new
define('EmptySpam', 'Vaciar carpeta de Spam');
define('Saving', 'Guardando&hellip;');
define('Sending', 'Enviando&hellip;');
define('LoggingOffFromServer', 'Cerrando sesión del servidor&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Lo(s) mensajes no se pueden marcar como Spam');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Lo(s) mensajes no se pueden marcar como legítimos');
define('ExportToICalendar', 'Exportar a iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Su cuenta está deshabilitada porque el número de usuarios permitidos por su licencia se ha excedido. Por favor contacte al adminsitrador.');
define('RepliedMessageTitle', 'Mensaje Respondido');
define('ForwardedMessageTitle', 'Mensaje Reenviado');
define('RepliedForwardedMessageTitle', 'Mensaje Respondido y Reenviado');
define('ErrorDomainExist', 'El usuario no se puede crear debido a que el el dominio correspondiente no existe. Usted debe crear su dominio primero.');

// webmail 4.6.x or 4.7
define('RequestReadConfirmation', 'Confirmación de Lectura');
define('FolderTypeDefault', 'Prefeterminado');
define('ShowFoldersMapping', 'Dejarme utiliza otra carpeta como carpeta del sistema (por ejemplo usar MiCarpeta como Elementos Enviados)');
define('ShowFoldersMappingNote', 'Por ejemplo, para cambiar la ubicació de Elementos Enviados de la carpeta Elmentos Enviados a MiCarpeta, especifique "Elementos Enviados" en el listado "Usar como" de "MiCarpeta".');
define('FolderTypeMapTo', 'Usar como');

define('ReminderEmailExplanation', 'Este correo ha lleago a su cuenta %EMAIL% porque usted ha solicitado una notificación de evento en su calendario: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Abrir calendario');

define('AddReminder', 'Recordarme acerca de este evento');
define('AddReminderBefore', 'Recordarme % antes de este evento');
define('AddReminderAnd', 'y % después');
define('AddReminderAlso', 'y también % después');
define('AddMoreReminder', 'Más recordatorios');
define('RemoveAllReminders', 'Eliminar todos los recordatorios');
define('ReminderNone', 'Ninguno');
define('ReminderMinutes', 'minutos');
define('ReminderHour', 'hora');
define('ReminderHours', 'horas');
define('ReminderDay', 'día');
define('ReminderDays', 'días');
define('ReminderWeek', 'semana');
define('ReminderWeeks', 'semanas');
define('Allday', 'Todo el día');

define('Folders', 'Carpetas');
define('NoSubject', 'Sin Asunto');
define('SearchResultsFor', 'Buscar resultados por');

define('Back', 'Atrás');
define('Next', 'Siguiente');
define('Prev', 'Anterior');

define('MsgList', 'Mensajes');
define('Use24HTimeFormat', 'Usar formato de 24 horas');
define('UseCalendars', 'Usar calendarios');
define('Event', 'Evento');
define('CalendarSettingsNullLine', 'No hay calendarios');
define('CalendarEventNullLine', 'No hay eventos');
define('ChangeAccount', 'Cambiar cuenta');

define('TitleCalendar', 'Calendario');
define('TitleEvent', 'Evento');
define('TitleFolders', 'Carpetas');
define('TitleConfirmation', 'Confirmación');

define('Yes', 'Si');
define('No', 'No');

define('EditMessage', 'Editar Mensaje');

define('AccountNewPassword', 'Nueva contraseña');
define('AccountConfirmNewPassword', 'Confirmar contraseña nueva');
define('AccountPasswordsDoNotMatch', 'Las contraseñas no coinciden');

define('ContactTitle', 'Título');
define('ContactFirstName', 'Primer nombre');
define('ContactSurName', 'Segundo nombre');
define('ContactNickName', 'Apodo');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'recargar');
define('CaptchaError', 'El texto del Captcha es incorrecto.');

define('WarningInputCorrectEmails', 'Por favor especifique los correos correctos.');
define('WrongEmails', 'Correos incorrectos:');

define('ConfirmBodySize1', 'Lo sentimos, pero el texto del mensaje se ha excedido.');
define('ConfirmBodySize2', 'caracteres de largo. Todo despu&eeacute;s del límite será cortado. Click en "Cancelar" si desea editar el mensaje.');
define('BodySizeCounter', 'Contador');
define('InsertImage', 'Insertar Imagen');
define('ImagePath', 'Ruta de la Imagen');
define('ImageUpload', 'Insertar');
define('WarningImageUpload', 'El archivo que está subiendo no es una imagen. Por favor seleccione una imagen.');

define('ConfirmExitFromNewMessage', 'Los cambios se perderán si usted sale de la página. Desea guardar como borrador antes de salir de la página?');

define('SensivityConfidential', 'Por favor tratar este mensaje como Confidencial');
define('SensivityPrivate', 'Por favor tratar este mensaje como Privado');
define('SensivityPersonal', 'Por favor tratar este mensaje como Personal');

define('ReturnReceiptTopText', 'El remitente ha solicitado que sea notificado cuando usted reciba este mensaje.');
define('ReturnReceiptTopLink', 'De click aquí para notificar al remitente.');
define('ReturnReceiptSubject', 'Acuse de Recibo (mostrado)');
define('ReturnReceiptMailText1', 'Este es el Acuse de Recibo del correo que usted envió o');
define('ReturnReceiptMailText2', 'Nota: Este Acuse de Recibo solo reconoce que el mensaje fue mostrado en el computador del destinatario. No existe ninguna garantía que el mensaje fue leido o que el contenido haya sido comprendido.');
define('ReturnReceiptMailText3', 'con asunto');

define('SensivityMenu', 'Sensibilidad');
define('SensivityNothingMenu', 'Ninguna');
define('SensivityConfidentialMenu', 'Confidencial');
define('SensivityPrivateMenu', 'Privado');
define('SensivityPersonalMenu', 'Personal');

define('ErrorLDAPonnect', 'No fue posible conectarse con el servidor LDAP.');

define('MessageSizeExceedsAccountQuota', 'El tamaño de este mensaje excede el límite de espacio de su cuenta');
define('MessageCannotSent', 'Este mensaje no se puede enviar.');
define('MessageCannotSaved', 'Este mensaje no se puede guardar.');

define('ContactFieldTitle', 'Campo');
define('ContactDropDownTO', 'TO');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', 'Los mensajes no se pueden mover a la Papelera. Posiblemente porque su buzón se llenó. Desea que esto(s) mensaje(s) sean borrados?');

define('WarningFieldBlank', 'Este campo no puede estar vacio.');
define('WarningPassNotMatch', 'Las contraseñas no coinciden, por favor verifiquelas.');
define('PasswordResetTitle', 'Recuperación de Contraseña - Etapa %d');
define('NullUserNameonReset', 'usuario');
define('IndexResetLink', 'Olvidó su contraseña?');
define('IndexRegLink', 'Registro de Cuenta');

define('RegDomainNotExist', 'El Dominio no existe.');
define('RegAnswersIncorrect', 'Las respuestas son incorrectas.');
define('RegUnknownAdress', 'Dirección de correo desconocida.');
define('RegUnrecoverableAccount', 'La recuperación de contraseñ no puede utilizarse en esta dirección de correo.');
define('RegAccountExist', 'Esta dirección ya está en uso.');
define('RegRegistrationTitle', 'Registro');
define('RegName', 'Nombre');
define('RegEmail', 'dirección de correo');
define('RegEmailDesc', 'Por ejemplo, juangarcia@mail.com. Esta información será usada para ingresar al sistema.');
define('RegSignMe', 'Recordarme');
define('RegSignMeDesc', 'No preguntar por el usuario y la contraseña la próxima vez que ingrese al sistema en este computador.');
define('RegPass1', 'Contraseña');
define('RegPass2', 'Repetir contraseña');
define('RegQuestionDesc', 'Por favor, ingrese dos preguntas secretas y sus respuestas las cuales solo usted sepa. En el caso de que pierda su contraseña usted podrá usar estas preguntas para poder recuperar su contraseña.');
define('RegQuestion1', 'Pregunta Secreta 1');
define('RegAnswer1', 'Respuesta 1');
define('RegQuestion2', 'Pregunta Secreta 2');
define('RegAnswer2', 'Respuesta 2');
define('RegTimeZone', 'Zona horaria');
define('RegLang', 'Lenguaje de la interfaz');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Registrar');

define('ResetEmail', 'Por favor ingrese su dirección de correo');
define('ResetEmailDesc', 'Ingrese las direcciones de correo usadas en el registro.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Enviado');
define('ResetQuestion1', 'Pregunta Secreta 1');
define('ResetAnswer1', 'Respuesta');
define('ResetQuestion2', 'Pregunta Secreta 2');
define('ResetAnswer2', 'Respuesta');
define('ResetSubmitStep2', 'Enviado');

define('ResetTopDesc1Step2', 'Por favor ingrese su dirección de correo');
define('ResetTopDesc2Step2', 'Por favor confirme la exactitud de los datos.');

define('ResetTopDescStep3', 'por favor ingrese abajo la nueva contraseña de su correo.');

define('ResetPass1', 'Nueva contraseña');
define('ResetPass2', 'Repetir contraseña');
define('ResetSubmitStep3', 'Enviado');
define('ResetDescStep4', 'Su contraseña se ha cambiado.');
define('ResetSubmitStep4', 'Regresar');

define('RegReturnLink', 'Regresar a la pantalla de ingreso');
define('ResetReturnLink', 'Regresar a la pantalla de ingreso');

// Appointments
define('AppointmentAddGuests', 'Añadir invitados');
define('AppointmentRemoveGuests', 'Cancelar reunión');
define('AppointmentListEmails', 'Ingrese las direcciones de correo separas por comas y presione Guardar');
define('AppointmentParticipants', 'Participantes');
define('AppointmentRefused', 'Rechazar');
define('AppointmentAwaitingResponse', 'Esperando respuesta');
define('AppointmentInvalidGuestEmail', 'Las siguientes direcciones de correo de los invitados son inválidas:');
define('AppointmentOwner', 'Propietaario');

define('AppointmentMsgTitleInvite', 'Invitar al evento.');
define('AppointmentMsgTitleUpdate', 'El evento fue modificado.');
define('AppointmentMsgTitleCancel', 'El evento fue cancelado.');
define('AppointmentMsgTitleRefuse', 'El invitado %guest% rechazó la invitación');
define('AppointmentMoreInfo', 'Más información');
define('AppointmentOrganizer', 'Organizador');
define('AppointmentEventInformation', 'Información del Evento');
define('AppointmentEventWhen', 'Cuando');
define('AppointmentEventParticipants', 'Participantes');
define('AppointmentEventDescription', 'Descripción');
define('AppointmentEventWillYou', 'Usted participará');
define('AppointmentAdditionalParameters', 'Parámetros adicionales');
define('AppointmentHaventRespond', 'Aún no responde');
define('AppointmentRespondYes', 'Yo voy a participar');
define('AppointmentRespondMaybe', 'Aún no se');
define('AppointmentRespondNo', 'No participará');
define('AppointmentGuestsChangeEvent', 'Los invitados pueden cambiar el evento');

define('AppointmentSubjectAddStart', 'Usted ha recibido una invitación al evento ');
define('AppointmentSubjectAddFrom', ' de ');
define('AppointmentSubjectUpdateStart', 'Modificación del evento ');
define('AppointmentSubjectDeleteStart', 'Cancelación del evento ');
define('ErrorAppointmentChangeRespond', 'No fue posible cambiar la respuesta de la cita');
define('SettingsAutoAddInvitation', 'Añadir invitaciones dentro del calendario automáticamente');
define('ReportEventSaved', 'Su evento de ha guardado');
define('ReportAppointmentSaved', ' y las notificaciones fueron enviadas');
define('ErrorAppointmentSend', 'No se pueden enviar las invitaciones.');
define('AppointmentEventName', 'Nombre:');

// End appointments

define('ErrorCantUpdateFilters', 'No se pueden actualizar los filtros');

define('FilterPhrase', 'Si hay un encabezado %field %condition %string entonces %action');
define('FiltersAdd', 'Añadir Filtro');
define('FiltersCondEqualTo', 'igual a');
define('FiltersCondContainSubstr', 'contiene la cadena');
define('FiltersCondNotContainSubstr', 'no contiene la cadena');
define('FiltersActionDelete', 'eliminar mensaje');
define('FiltersActionMove', 'mover');
define('FiltersActionToFolder', 'a la carpeta %folder');
define('FiltersNo', 'No se han especificado filtros aán');

define('ReminderEmailFriendly', 'recordatorio');
define('ReminderEventBegin', 'empieza con: ');

define('FiltersLoading', 'Cargando Filtros...');
define('ConfirmMessagesPermanentlyDeleted', 'Todos los mensajes en este folder serán eliminados permanentemente.');

define('InfoNoNewMessages', 'No hay nuevos mensajes.');
define('TitleImportContacts', 'Importar Contactos');
define('TitleSelectedContacts', 'Seleccionar Contactos');
define('TitleNewContact', 'Nuevo Contacto');
define('TitleViewContact', 'Ver Contacto');
define('TitleEditContact', 'Editar Contacto');
define('TitleNewGroup', 'Nuevo Grupo');
define('TitleViewGroup', 'Ver Grupo');

define('AttachmentComplete', 'Completo.');

define('TestButton', 'PROBAR');
define('AutoCheckMailIntervalLabel', 'Verificar correos cada');
define('AutoCheckMailIntervalDisableName', 'Apagado');
define('ReportCalendarSaved', 'El calendario se ha guardado.');

define('ContactSyncError', 'La sincronización falló');
define('ReportContactSyncDone', 'Sincronización completada');

define('MobileSyncUrlTitle', 'URL de sincronización móvil');
define('MobileSyncLoginTitle', 'Usuario de sincronización móvil');

define('QuickReply', 'Respuesta Rápida');
define('SwitchToFullForm', 'Abrir formulario de respuesta completo');
define('SortFieldDate', 'Fecha');
define('SortFieldFrom', 'Desde');
define('SortFieldSize', 'Tamaño');
define('SortFieldSubject', 'Asunto');
define('SortFieldFlag', 'Bandera');
define('SortFieldAttachments', 'Adjuntos');
define('SortOrderAscending', 'Ascendente');
define('SortOrderDescending', 'Descendente');
define('ArrangedBy', 'Organizado por');

define('MessagePaneToRight', 'El panel de mensajes está a la derecha de la lista de mensajes, en lugar de por debajo');

define('SettingsTabMobileSync', 'Móvil');

define('MobileSyncContactDataBaseTitle', 'Nombre de la base de datos para contactos');
define('MobileSyncCalendarDataBaseTitle', 'Nombre de la base de datos para calendarios');
define('MobileSyncTitleText', 'Si usted desea sincronizar su dispositivo habilitado para SyncML con el WebMail, usted puede usar estos parámetros.<br />"URL de sincronización móvil" especifica la ruta al servidor de sincronización SyncML, "Usuario de sincronización móvil" es su usuario en el servidor SyncML y utilice su contraseña  cuando le sea solicitada. También, algunos dispositivos necesitan que se les sea especificado el nombre para contactos y calendario.<br />Use "Nombre de la base de datos para contactos" y "Nombre de la base de datos para calendarios');
define('MobileSyncEnableLabel', 'Habilitar sincronización móvil');

define('SearchInputText', 'buscar');

define('AppointmentEmailExplanation','Este mensaje le ha llegado a su cuenta %EMAIL% porque usted ha sido inviatado al evento por %ORGANAZER%');

define('Searching', 'Buscando&hellip;');

define('ButtonSetupSpecialFolders', 'Configurar carpetas especiales');
define('ButtonSaveChanges', 'Guardar cambios');
define('InfoPreDefinedFolders', 'Para carpetas predefinidas, utilice estos buzones IMAP');

define('SaveMailInSentItems', 'También guardar en Elementos Enviados');

define('CouldNotSaveUploadedFile', 'No se guardó el archivo subido.');

define('AccountOldPassword', 'Contraseña actual');
define('AccountOldPasswordsDoNotMatch', 'Las contraseñas actuales no coinciden.');

define('DefEditor', 'Editor Predeterminado');
define('DefEditorRichText', 'Texto Enriquecido');
define('DefEditorPlainText', 'Texto Plano');

define('Layout', 'Interfaz');

define('TitleNewMessagesCount', '%count% nuevo(s) mensaje(s)');

define('AltOpenInNewWindow', 'Abrir en una nueva ventana');

define('SearchByFirstCharAll', 'Todo');

define('FolderNoUsageAssigned', 'Sin uso asignado');

define('InfoSetupSpecialFolders', 'Para que coincida una carpeta especial, como (Elementos Enviados) y algunos buzones IMAP. De click en Configurar carpetas especiales.');

define('FileUploaderClickToAttach', 'Click para adjuntar archivos');
define('FileUploaderOrDragNDrop', 'O arrastre y suelte los archivos aquí');

define('AutoCheckMailInterval1Minute', '1 minuto');
define('AutoCheckMailInterval3Minutes', '3 minutos');
define('AutoCheckMailInterval5Minutes', '5 minutos');
define('AutoCheckMailIntervalMinutes', 'minutos');

define('ReadAboutCSVLink', 'Ver má acerca de los campos de los archivos .CSV');

define('VoiceMessageSubj', 'Mensaje de Voz');
define('VoiceMessageTranscription', 'Transcripción');
define('VoiceMessageReceived', 'Recibido');
define('VoiceMessageDownload', 'Descargar');
define('VoiceMessageUpgradeFlashPlayer', 'Usted necesita actualizar su Adobe Flash Player para escuchar los mensajes de voz.<br />Actualice su Flash Player 10 en <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'La licencia está vencida, por favor contáctenos para actualizar su licencia');
define('LicenseProblem', 'Problema de licenciamiento. Su administrador debe ir al Panel de Administración y verificar los detalles.');

define('AccountOldPasswordNotCorrect', 'La contraseña actual no es correcta');
define('AccountNewPasswordUpdateError', 'No se puede guardar la nueva contraseña.');
define('AccountNewPasswordRejected', 'No se puede guardar la nueva contraseña. Tal vez, porque es muy sencilla.');

define('CantCreateIdentity', 'No se puede crear la identidad');
define('CantUpdateIdentity', 'No se puede actualizar la identidad');
define('CantDeleteIdentity', 'No se puede eliminar la identidad');

define('AddIdentity', 'Añadir Identidad');
define('SettingsTabIdentities', 'Identidades');
define('NoIdentities', 'Sin identidades');
define('NoSignature', 'Sin firma');
define('Account', 'Cuenta');
define('TabChangePassword', 'Contraseña');
define('SignatureEnteringHere', 'Ingrese su firma aquí');

define('CantConnectToMailServer', 'No se puede conectar al servidor de correo');

define('DomainNameNotSpecified', 'El nombre de daminio no se especificó.');

define('Open', 'Abrir');
define('FolderUsedAs', 'usado como');
define('ForwardTitle', 'Reenviar');
define('ForwardEnable', 'Habilitar reenvío');
define('ReportForwardUpdatedSuccessfuly', 'El reenvío ha sido actualizado satisfactoriamente.');

define('DialogAttachHeaderResume', 'Adjunte su Resumen');
define('DialogAttachHeaderLetter', 'Adjunte su Carta de Presentación');
define('DialogAttachName', 'Seleccionar Resumen');
define('DialogAttachType', 'Seleccione el Formato');
define('DialogAttachTypePdf', 'Adobe PDF (.pdf)');
define('DialogAttachTypeHtml', 'Web Page (.html)');
define('DialogAttachTypeRtf', 'Rich Text (.rtf)');
define('DialogAttachTypeTxt', 'Plain Text (.txt)');
define('DialogAttachTypeDoc', 'MS Word (.doc)');
define('DialogAttachButton', 'Adjuntar');
define('DialogAttachResume', 'Adjuntar el Resumen');
define('DialogAttachLetter', 'Adjuntear Carta de Presentación');
define('DialogAttachAnother', 'Adjuntar otro archivo');
define('DialogAttachAddToBody', 'Añadir versión de texto plano al cuerpo del mensaje (Recomendado)');
define('DialogAttachTypeNo', 'Sin Adjuntos');
define('DialogAttachSelectLetter', 'Seeccionar Carta de Presentación');
define('DialogAttachTypePdfRecom', 'Adobe PDF (.pdf) (Recomendado)');
define('DialogAttachTypeTextInBody', 'Texto plano en el cuerpo del mensaje - recomendado');
define('DialogAttachTypeTxtAttach', 'Adjunto de Texto Plano (.txt)');
define('CustomTitle', 'Reenviando');
define('ForwardingNotificationsTo', 'Enviar notificación de correo a <b>%email</b>');
define('ForwardingForwardTo', 'Reenviar correo a <b>%email</b>');
define('ForwardingNothing', 'No hay notificaciones o reenvío');
define('ForwardingChange', 'cambiar');

define('ConfirmSaveForward', 'Las configuraciones de reenvío no fueron grabadas. Click en OK para guardar.');
define('ConfirmSaveAutoresponder', 'Las configuraciones de autorespuesta no fueron grabadas. Click en OK para guardar.');

define('DigDosMenuItem', 'DigDos');
define('DigDosTitle', 'Seleccionar un objeto');

define('LastLoginTitle', 'Anterior Usuario');
define('ExportContacts', 'Exportar Contactos');

define('JS_LANG_Gb', 'GB');

define('ContactsTabGlobal', 'global');
define('ContactsTabPersonal', 'personal');
define('InfoLoadingContacts', 'WebMail está cargando la lista de mensajes');

define('TheAccessToThisAccountIsDisabled', 'El acceso a esta cuenta está deshabilitado');

define('MobileSyncDavServerURL', 'URL del servidor DAV');
define('MobileSyncPrincipalURL', 'URL Principal');
define('MobileSyncHintDesc', 'Use these settings to sync your calendars and contacts with a mobile device which supports CalDAV or CardDAV protocols.<br /><br />With iOS devices like iPhone, you\'ll usually need DAV server URL, mobile sync login, and your password. Or, you can get your iOS profile automatically if you access this webmail from your such device.<br /><br />Some software like Mozilla Thunderbird require separate URL to each calendar of yours. To get this URL, select Share and Publish option for the given calendar in Calendars Manager.');

define('MobileGetIOSSettings', 'Deliver e-mail, contacts and calendar settings on your iOS device');
define('IOSLoginHeadTitle', 'Instalar Perfil iOS');
define('IOSLoginHelloAppleTitle', 'Hola,');
define('IOSLoginHelpDesc1', 'Nosotros podemos entregar automáticamente su correo y su calendario en su dispositivo iOS.');
define('IOSLoginHelpDesc2', 'Usted siempre lo podrá tener despueés,');
define('IOSLoginHelpDesc3', 'en la sección Configuració/Móvil.');
define('IOSLoginButtonYesPlease', 'Si, por favor');
define('IOSLoginButtonSkip', 'Saltar esto y dejarme ingresar');
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
