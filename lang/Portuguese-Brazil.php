<?php
// Translation by Nixon Girard - <nixon@metaweb.com.br>
define('PROC_ERROR_ACCT_CREATE', 'Ocorreu um erro ao criar a conta');
define('PROC_WRONG_ACCT_PWD', 'A senha da conta está errada');
define('PROC_CANT_LOG_NONDEF', 'Não foi possível realizar o login na conta não padrão');
define('PROC_CANT_INS_NEW_FILTER', 'Não foi possível inserir filtro');
define('PROC_FOLDER_EXIST', 'Nome da pasta já existe');
define('PROC_CANT_CREATE_FLD', 'Não foi possível criar a pasta');
define('PROC_CANT_INS_NEW_GROUP', 'Não foi possível inserir novo grupo');
define('PROC_CANT_INS_NEW_CONT', 'Não foi possível inserir novo contato');
define('PROC_CANT_INS_NEW_CONTS', 'Não foi possível inserir novos contatos');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Não foi possível inserir contato no grupo');
define('PROC_ERROR_ACCT_UPDATE', 'Ocorreu um erro ao tentar atualizar a conta');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Não foi possível atualizar as informações do contato');
define('PROC_CANT_GET_SETTINGS', 'Não foi possível carregar as configurações');
define('PROC_CANT_UPDATE_ACCT', 'Não foi possível atualizar a conta');
define('PROC_ERROR_DEL_FLD', 'Ocorreu um erro ao tentar excluir a pasta');
define('PROC_CANT_UPDATE_CONT', 'Não foi possível atualizar contato');
define('PROC_CANT_GET_FLDS', 'Não foi possível carregar estrutura de pastas');
define('PROC_CANT_GET_MSG_LIST', 'Não foi possível carregar a lista de mensagens');
define('PROC_MSG_HAS_DELETED', 'Esta mensagem já tinha sido excluída do servidor de e-mail');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Não foi possível carregar informações dos contatos');
define('PROC_CANT_LOAD_SIGNATURE', 'Não foi possível carregar a assinatura da conta');
define('PROC_CANT_GET_CONT_FROM_DB', 'Não foi possível carregar contato da base de dados');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Não foi possível carregar contato da base de dados');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Não foi possível excluir a conta com o código');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Não foi possível o filtro com o código');
define('PROC_CANT_DEL_CONT_GROUPS', 'Não foi possível excluir contato(s) e/ou grupo(s)');
define('PROC_WRONG_ACCT_ACCESS', 'Foi detectada uma tentativa de acesso não autorizado na conta de outro usuário.');
define('PROC_SESSION_ERROR', 'A sessão foi terminada devido ao longo período de inatividade na conta.');

define('MailBoxIsFull', 'Caixa postal está cheia');
define('WebMailException', 'Ocorreu um erro desconhecido');
define('InvalidUid', 'UID Inválido');
define('CantCreateContactGroup', 'Não foi possível criar grupo de contato');
define('CantCreateUser', 'Não foi possível criar usuário');
define('CantCreateAccount', 'Não foi possível criar conta de usuário');
define('SessionIsEmpty', 'A sessão está vazia');
define('FileIsTooBig', 'O arquivo é muito grande');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Não foi possível marcar todas as mensagens como lidas');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Não foi possível marcar todas as mensagens como não lidas');
define('PROC_CANT_PURGE_MSGS', 'Não foi possível remover a(s) mensagem(s)');
define('PROC_CANT_DEL_MSGS', 'Não foi possível excluir a(s) mensagem(s)');
define('PROC_CANT_UNDEL_MSGS', 'Não foi possível restaurar a(s) mensagem(s)');
define('PROC_CANT_MARK_MSGS_READ', 'Não foi possível marcar a(s) mensagem(s) como lida');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Não foi possível marcar a(s) mensagem(s) como não lida');
define('PROC_CANT_SET_MSG_FLAGS', 'Não foi possível marcar a(s) mensagem(s)');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Não foi possível desmarcar a(s) mensagem(s)');
define('PROC_CANT_CHANGE_MSG_FLD', 'Não foi possível mudar a pasta da(s) mensagem(s)');
define('PROC_CANT_SEND_MSG', 'Não foi possível enviar mensagem:');
define('PROC_CANT_SAVE_MSG', 'Não foi possível salvar mensagem');
define('PROC_CANT_GET_ACCT_LIST', 'Não foi possível carregar relação de conta(s)');
define('PROC_CANT_GET_FILTER_LIST', 'Não foi possível carregar relação do(s) filtro(s)');

define('PROC_CANT_LEAVE_BLANK', 'Você não pode deixar em branco os campos assinalados com *');

define('PROC_CANT_UPD_FLD', 'Não foi possível atualizar pasta(s)');
define('PROC_CANT_UPD_FILTER', 'Não foi possível atualizar filtro(s)');

define('ACCT_CANT_ADD_DEF_ACCT', 'Esta conta não pode ser adicionada porque está sendo usada como uma conta padrão por outro usuário.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'O status dessa conta não pode ser alterado para padrão.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Não foi possível criar uma nova conta (IMAP4 erro de conexão)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Não foi possível excluir a última conta padrão.');

define('LANG_LoginInfo', 'Informações para o Login');
define('LANG_Email', 'Email');
define('LANG_Login', 'Login');
define('LANG_Password', 'Senha');
define('LANG_IncServer', 'Servidor de entrada');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Porta');
define('LANG_OutServer', 'Servidor de saída');
define('LANG_OutPort', 'Porta');
define('LANG_UseSmtpAuth', 'Autenticação');
define('LANG_SignMe', 'Entrar automaticamente');
define('LANG_Enter', 'Entrar');

// interface strings

define('JS_LANG_TitleLogin', 'Login');
define('JS_LANG_TitleMessagesListView', 'Lista de Mensagens');
define('JS_LANG_TitleMessagesList', 'Lista de Mensagens');
define('JS_LANG_TitleViewMessage', 'Exibir Mensagem');
define('JS_LANG_TitleNewMessage', 'Nova Mensagem');
define('JS_LANG_TitleSettings', 'Configurações');
define('JS_LANG_TitleContacts', 'Contatos');

define('JS_LANG_StandardLogin', 'Login&nbsp;padrão');
define('JS_LANG_AdvancedLogin', 'Login&nbsp;avançado');

define('JS_LANG_InfoWebMailLoading', 'Por favor aguarde, carregando &hellip;');
define('JS_LANG_Loading', 'Carregando &hellip;');
define('JS_LANG_InfoMessagesLoad', 'Por favor aguarde, carregando lista de mensagens');
define('JS_LANG_InfoEmptyFolder', 'A pasta está vazia');
define('JS_LANG_InfoPageLoading', 'A página está sendo carregada &hellip;');
define('JS_LANG_InfoSendMessage', 'A mensagem foi enviada');
define('JS_LANG_InfoSaveMessage', 'A mensagem foi salva');
define('JS_LANG_InfoHaveImported', 'Você já importou');
define('JS_LANG_InfoNewContacts', 'novo(s) contato(s) em sua lista.');
define('JS_LANG_InfoToDelete', 'Excluir ');
define('JS_LANG_InfoDeleteContent', 'primeiro exclua o(s) conteúdo(s) da pasta.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'A exclusão de pasta(s) cheia(s) não é pertimida. Você deve primeiro eliminar o conteúdo dela(s).');
define('JS_LANG_InfoRequiredFields', '* campos requeridos');

define('JS_LANG_ConfirmAreYouSure', 'Você tem certeza?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'A mensagem(s) selecionada(s) será excluída! Tem certeza que deseja continuar?');
define('JS_LANG_ConfirmSaveSettings', 'As configurações não foram salvas. Pressione OK para salvar.');
define('JS_LANG_ConfirmSaveContactsSettings', 'As configurações do(s) contato(s) não foram salvas. Pressione OK para salvar.');
define('JS_LANG_ConfirmSaveAcctProp', 'As propriedades da(s) conta(s)  não foram salvas. Pressione OK para salvar.');
define('JS_LANG_ConfirmSaveFilter', 'As propriedades dos filtros não foram salvas. Pressione OK para salvar.');
define('JS_LANG_ConfirmSaveSignature', 'A assinatura não foi salva. Pressione OK para salvar.');
define('JS_LANG_ConfirmSavefolders', 'As pastas não foram salvas. Pressione OK para salvar.');
define('JS_LANG_ConfirmHtmlToPlain', 'Atenção: ao mudar a formato dessa mensagem HTML para texto, você perderá qualquer formatação atual da mensagem. Pressione OK para continuar.');
define('JS_LANG_ConfirmAddFolder', 'Antes de adicionar uma pasta é necessário aplicar as mudanças. Pressione OK para salvar.');
define('JS_LANG_ConfirmEmptySubject', 'O campo assunto está em branco. Tem certeza que deseja continuar?');

define('JS_LANG_WarningEmailBlank', 'Você não pode deixar<br />o campo e-mail em branco');
define('JS_LANG_WarningLoginBlank', 'Você não pode deixar<br />o campo login em branco');
define('JS_LANG_WarningToBlank', 'Você não pode deixar: Campos em branco');
define('JS_LANG_WarningServerPortBlank', 'Preencha os campos POP3 e SMTP server');
define('JS_LANG_WarningEmptySearchLine', 'Campo de busca vazio. Digite a palavra que deseja procurar');
define('JS_LANG_WarningMarkListItem', 'Por favor, selecione pelo menos um ítem');
define('JS_LANG_WarningFolderMove', 'A pasta não pôde ser');
define('JS_LANG_WarningContactNotComplete', 'Insira o nome ou e-mail');
define('JS_LANG_WarningGroupNotComplete', 'Insira o nome do grupo');

define('JS_LANG_WarningEmailFieldBlank', 'Você deve preencher o campo E-mail');
define('JS_LANG_WarningIncServerBlank', 'Você deve preencher o campo POP3(IMAP4) Server');
define('JS_LANG_WarningIncPortBlank', 'Você deve preencher o campo POP3(IMAP4) Server Port');
define('JS_LANG_WarningIncLoginBlank', 'Você deve preencher o campo POP3(IMAP4) Login');
define('JS_LANG_WarningIncPortNumber', 'Você deve especificar um número positivo no campo porta POP3(IMAP4).');
define('JS_LANG_DefaultIncPortNumber', 'A porta padrão POP3(IMAP4) é 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Você deve preencher o campo senha POP3(IMAP4)');
define('JS_LANG_WarningOutPortBlank', 'Você deve preencher o campo porta SMTP Server');
define('JS_LANG_WarningOutPortNumber', 'Você deve especificar um número positivo no campo porta SMTP.');
define('JS_LANG_WarningCorrectEmail', 'Informe um e-mail válido.');
define('JS_LANG_DefaultOutPortNumber', 'A porta padrão SMTP é 25.');

define('JS_LANG_WarningCsvExtention', 'A extensão deve ser .csv');
define('JS_LANG_WarningImportFileType', 'Por favor, selecione a aplicação da qual você deseja copiar seus contatos');
define('JS_LANG_WarningEmptyImportFile', 'Selecione um arquivo clicando no botão "browse"');

define('JS_LANG_WarningContactsPerPage', 'O número de contatos por página deve ser positivo.');
define('JS_LANG_WarningMessagesPerPage', 'O número de mensagens por página deve ser positivo.');
define('JS_LANG_WarningMailsOnServerDays', 'Você deve especificar um número positivo em  Messages on server days field.');
define('JS_LANG_WarningEmptyFilter', 'Digite a palavra');
define('JS_LANG_WarningEmptyFolderName', 'Digite o nome da pasta');

define('JS_LANG_ErrorConnectionFailed', 'Não foi possível conectar');
define('JS_LANG_ErrorRequestFailed', 'A transferência dos dados não foi completada');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'O objeto XMLHttpRequest está ausente');
define('JS_LANG_ErrorWithoutDesc', 'Ocorreu um erro desconhecido');
define('JS_LANG_ErrorParsing', 'Erro durante análise XML.');
define('JS_LANG_ResponseText', 'Texto de Resposta:');
define('JS_LANG_ErrorEmptyXmlPacket', 'Pacote XML vazio');
define('JS_LANG_ErrorImportContacts', 'Erro durante importação de contatos');
define('JS_LANG_ErrorNoContacts', 'Não foram encontrados contatos para importar');
define('JS_LANG_ErrorCheckMail', 'Erro no recebimento de mensagens. Provavelmente, nem todas as mensagens foram recebidas.');

define('JS_LANG_LoggingToServer', 'Conectando no servidor  &hellip;');
define('JS_LANG_GettingMsgsNum', 'Recebendo total de mensagens');
define('JS_LANG_RetrievingMessage', 'Baixando mensagem');
define('JS_LANG_DeletingMessage', 'Excluindo mensagem');
define('JS_LANG_DeletingMessages', 'Excluindo mensagem(s)');
define('JS_LANG_Of', 'of');
define('JS_LANG_Connection', 'Conexão');
define('JS_LANG_Charset', 'Charset');
define('JS_LANG_AutoSelect', 'Auto seleção');

define('JS_LANG_Contacts', 'Contatos');
define('JS_LANG_ClassicVersion', 'Versão clássica');
define('JS_LANG_Logout', 'Sair');
define('JS_LANG_Settings', 'Configurações');

define('JS_LANG_LookFor', 'Localizar: ');
define('JS_LANG_SearchIn', 'Procurar em: ');
define('JS_LANG_QuickSearch', 'Procura nos campos De, Para e Assunto (rapidamente).');
define('JS_LANG_SlowSearch', 'Procurar na mensagem inteira');
define('JS_LANG_AllMailFolders', 'Todas as pastas');
define('JS_LANG_AllGroups', 'Todos os grupos');

define('JS_LANG_NewMessage', 'Nova mensagem');
define('JS_LANG_CheckMail', 'Verificar mensagens');
define('JS_LANG_EmptyTrash', 'Limpar lixeira');
define('JS_LANG_MarkAsRead', 'Marcar como lida');
define('JS_LANG_MarkAsUnread', 'Marcar como não lida');
define('JS_LANG_MarkFlag', 'Marcar');
define('JS_LANG_MarkUnflag', 'Desmarcar');
define('JS_LANG_MarkAllRead', 'Marcar todas como lida');
define('JS_LANG_MarkAllUnread', 'Marcar todas como não lida');
define('JS_LANG_Reply', 'Responder');
define('JS_LANG_ReplyAll', 'Responder para todos');
define('JS_LANG_Delete', 'Excluir');
define('JS_LANG_Undelete', 'Restaurar');
define('JS_LANG_PurgeDeleted', 'Purge deleted');
define('JS_LANG_MoveToFolder', 'Mover');
define('JS_LANG_Forward', 'Encaminhar');

define('JS_LANG_HideFolders', 'Esconder pastas');
define('JS_LANG_ShowFolders', 'Exibir pastas');
define('JS_LANG_ManageFolders', 'Gerenciar pastas');
define('JS_LANG_SyncFolder', 'Sincronizar pasta');
define('JS_LANG_NewMessages', 'Novas mensagens');
define('JS_LANG_Messages', 'Mensagem(s)');

define('JS_LANG_From', 'De');
define('JS_LANG_To', 'Para');
define('JS_LANG_Date', 'Data');
define('JS_LANG_Size', 'Tamanho');
define('JS_LANG_Subject', 'Assunto');

define('JS_LANG_FirstPage', 'Primeira página');
define('JS_LANG_PreviousPage', 'Página anterior');
define('JS_LANG_NextPage', 'Próxima página');
define('JS_LANG_LastPage', 'Ultima página');

define('JS_LANG_SwitchToPlain', 'Exibir em modo texto');
define('JS_LANG_SwitchToHTML', 'Exibir em modo HTML');
define('JS_LANG_AddToAddressBook', 'Adicionar no catálogo de endereços');
define('JS_LANG_ClickToDownload', 'Clique para copiar');
define('JS_LANG_View', 'Exibir');
define('JS_LANG_ShowFullHeaders', 'Mostrar Cabeçalho completo');
define('JS_LANG_HideFullHeaders', 'Ocultar Cabeçalho completo');

define('JS_LANG_MessagesInFolder', 'Mensagens na pasta');
define('JS_LANG_YouUsing', 'Você está usando');
define('JS_LANG_OfYour', 'of your');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Enviar');
define('JS_LANG_SaveMessage', 'Salvar');
define('JS_LANG_Print', 'Imprimir');
define('JS_LANG_PreviousMsg', 'Mensagens anterior');
define('JS_LANG_NextMsg', 'Próxima mensagem');
define('JS_LANG_AddressBook', 'Catálogo de endereços');
define('JS_LANG_ShowBCC', 'Mostrar Bcc');
define('JS_LANG_HideBCC', 'Ocultar Bcc');
define('JS_LANG_CC', 'Cc');
define('JS_LANG_BCC', 'Bcc');
define('JS_LANG_ReplyTo', 'Responder&nbsp;para');
define('JS_LANG_AttachFile', 'Anexar arquivo');
define('JS_LANG_Attach', 'Anexar');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Mensagem original');
define('JS_LANG_Sent', 'Enviada');
define('JS_LANG_Fwd', 'Fwd');
define('JS_LANG_Low', 'Baixa');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Alta');
define('JS_LANG_Importance', 'Urgente');
define('JS_LANG_Close', 'Fechar');

define('JS_LANG_Common', 'Padrão');
define('JS_LANG_EmailAccounts', 'Contas de E-mail');

define('JS_LANG_MsgsPerPage', 'Mensagens por página');
define('JS_LANG_DisableRTE', 'Desativar editor de texto');
define('JS_LANG_Skin', 'Skin');
define('JS_LANG_DefCharset', 'Charset Padrão');
define('JS_LANG_DefCharsetInc', 'Charset de entrada padrão');
define('JS_LANG_DefCharsetOut', 'Charset de saída padrão');
define('JS_LANG_DefTimeOffset', 'Formato de horário padrão');
define('JS_LANG_DefLanguage', 'Linguagem padrão');
define('JS_LANG_DefDateFormat', 'Formato de data padrão');
define('JS_LANG_ShowViewPane', 'Lista de mensagens com pré-exibição');
define('JS_LANG_Save', 'Salvar');
define('JS_LANG_Cancel', 'Cancelar');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Remover');
define('JS_LANG_AddNewAccount', 'Adicionar nova conta');
define('JS_LANG_Signature', 'Assinatura');
define('JS_LANG_Filters', 'Filtros');
define('JS_LANG_Properties', 'Propriedades');
define('JS_LANG_UseForLogin', 'Use as propriedades dessa conta para o login');
define('JS_LANG_MailFriendlyName', 'Seu nome');
define('JS_LANG_MailEmail', 'E-mail');
define('JS_LANG_MailIncHost', 'Servidor de entrada');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Porta');
define('JS_LANG_MailIncLogin', 'Login');
define('JS_LANG_MailIncPass', 'Senha');
define('JS_LANG_MailOutHost', 'Servidor SMTP');
define('JS_LANG_MailOutPort', 'Porta');
define('JS_LANG_MailOutLogin', 'Login SMTP');
define('JS_LANG_MailOutPass', 'Senha SMTP');
define('JS_LANG_MailOutAuth1', 'Usar autenticação SMTP');
define('JS_LANG_MailOutAuth2', '(você pode deixar os campos Login e Senha SMTP em branco, caso os dados forem os mesmos dos campos Login e Senha POP3/IMAP4)');
define('JS_LANG_UseFriendlyNm1', 'Usar nome do contato no campo "Para:"');
define('JS_LANG_UseFriendlyNm2', '(Nome &lt;email@servidor.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Baixar/Sincronizar e-mails no login');
define('JS_LANG_MailMode0', 'Excluir mensagens recebidas do servidor');
define('JS_LANG_MailMode1', 'Deixar mensagens no servidor');
define('JS_LANG_MailMode2', 'Deixar mensagens no servidor por');
define('JS_LANG_MailsOnServerDays', 'dia(s)');
define('JS_LANG_MailMode3', 'Excluir mensagens do servidor enquanto são excluídas da Lixeira');
define('JS_LANG_InboxSyncType', 'Type of Inbox Synchronize');

define('JS_LANG_SyncTypeNo', 'Não sincronizar');
define('JS_LANG_SyncTypeNewHeaders', 'Novo cabeçalho');
define('JS_LANG_SyncTypeAllHeaders', 'Todos cabeçalhos');
define('JS_LANG_SyncTypeNewMessages', 'Nova mensagem');
define('JS_LANG_SyncTypeAllMessages', 'Todas as mensagens');
define('JS_LANG_SyncTypeDirectMode', 'Modo direto');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Cabeçalho inteiro');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Mensagem inteira');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Modo direto');

define('JS_LANG_DeleteFromDb', 'Apagar mensagens do Banco de Dados, caso ela não exista por um longo período no servidor');

define('JS_LANG_EditFilter', 'Editar filtro');
define('JS_LANG_NewFilter', 'Adicionar novo filtro');
define('JS_LANG_Field', 'Campo');
define('JS_LANG_Condition', 'Condição');
define('JS_LANG_ContainSubstring', 'Contenha a palavra-chave');
define('JS_LANG_ContainExactPhrase', 'Contenha a frase exata');
define('JS_LANG_NotContainSubstring', 'Não contenha a palavra-chave');
define('JS_LANG_FilterDesc_At', 'no');
define('JS_LANG_FilterDesc_Field', 'campo');
define('JS_LANG_Action', 'Ação');
define('JS_LANG_DoNothing', 'Não faça nada');
define('JS_LANG_DeleteFromServer', 'Excluir imediatamente do servidor');
define('JS_LANG_MarkGrey', 'Marcar (cor cinza)');
define('JS_LANG_Add', 'Adicionar');
define('JS_LANG_OtherFilterSettings', 'Outras configurações do filtro');
define('JS_LANG_ConsiderXSpam', 'Considerar campo X-Spam no cabeçalho');
define('JS_LANG_Apply', 'Aplicar');

define('JS_LANG_InsertLink', 'Inserir link');
define('JS_LANG_RemoveLink', 'Remover link');
define('JS_LANG_Numbering', 'Numeral');
define('JS_LANG_Bullets', 'Marcadores');
define('JS_LANG_HorizontalLine', 'Linha horizontal');
define('JS_LANG_Bold', 'Negrito');
define('JS_LANG_Italic', 'Itálico');
define('JS_LANG_Underline', 'Sublinhado');
define('JS_LANG_AlignLeft', 'Alinhar à esquerda');
define('JS_LANG_Center', 'Centralizar');
define('JS_LANG_AlignRight', 'Alinhar à direita');
define('JS_LANG_Justify', 'Justificar');
define('JS_LANG_FontColor', 'Cor da Fonte');
define('JS_LANG_Background', 'Plano de fundo');
define('JS_LANG_SwitchToPlainMode', 'Exibir em modo texto');
define('JS_LANG_SwitchToHTMLMode', 'Exibir em modo HTML');

define('JS_LANG_Folder', 'Pasta');
define('JS_LANG_Msgs', 'Msg\'s,');
define('JS_LANG_Synchronize', 'Sincronizar');
define('JS_LANG_ShowThisFolder', 'Mostrar esta pasta');
define('JS_LANG_Total', 'Total');
define('JS_LANG_DeleteSelected', 'Excluir selecionada');
define('JS_LANG_AddNewFolder', 'Adicionar nova pasta');
define('JS_LANG_NewFolder', 'Nova pasta');
define('JS_LANG_ParentFolder', 'Pasta Principal');
define('JS_LANG_NoParent', 'Sem pasta principal');
define('JS_LANG_FolderName', 'Nome da pasta');

define('JS_LANG_ContactsPerPage', 'Contatos por página');
define('JS_LANG_WhiteList', 'Catálogo de Endereços como "White List"');

define('JS_LANG_CharsetDefault', 'Padrão');
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

define('JS_LANG_TimeDefault', 'Padrão');
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

define('JS_LANG_DateDefault', 'Padrão');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Mês (01 Jan)');
define('JS_LANG_DateAdvanced', 'Avançado');

define('JS_LANG_NewContact', 'Novo Contato');
define('JS_LANG_NewGroup', 'Novo Grupo');
define('JS_LANG_AddContactsTo', 'Adicionar contatos em');
define('JS_LANG_ImportContacts', 'Importar contatos');

define('JS_LANG_Name', 'Nome');
define('JS_LANG_Email', 'E-mail');
define('JS_LANG_DefaultEmail', 'E-mail padrão');
define('JS_LANG_NotSpecifiedYet', 'Ainda não especificado');
define('JS_LANG_ContactName', 'Nome');
define('JS_LANG_Birthday', 'Data Nascimento');
define('JS_LANG_Month', 'Mês');
define('JS_LANG_January', 'Janeiro');
define('JS_LANG_February', 'Fevereiro');
define('JS_LANG_March', 'Março');
define('JS_LANG_April', 'Abril');
define('JS_LANG_May', 'Maio');
define('JS_LANG_June', 'Junho');
define('JS_LANG_July', 'Julho');
define('JS_LANG_August', 'Agosto');
define('JS_LANG_September', 'Setembro');
define('JS_LANG_October', 'Outubro');
define('JS_LANG_November', 'Novembro');
define('JS_LANG_December', 'Dezembro');
define('JS_LANG_Day', 'Dia');
define('JS_LANG_Year', 'Ano');
define('JS_LANG_UseFriendlyName1', 'Usar nome do contato');
define('JS_LANG_UseFriendlyName2', '(por exemplo, Paulo Silva &lt;paulo@servidor.com&gt;)');
define('JS_LANG_Personal', 'Pessoal');
define('JS_LANG_PersonalEmail', 'E-mail pessoal');
define('JS_LANG_StreetAddress', 'Rua');
define('JS_LANG_City', 'Cidade');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Bairro');
define('JS_LANG_Phone', 'Telefone');
define('JS_LANG_ZipCode', 'Cep');
define('JS_LANG_Mobile', 'Celular');
define('JS_LANG_CountryRegion', 'País/Estado');
define('JS_LANG_WebPage', 'Site');
define('JS_LANG_Go', 'Acessar');
define('JS_LANG_Home', 'Residencial');
define('JS_LANG_Business', 'Profissional');
define('JS_LANG_BusinessEmail', 'E-mail');
define('JS_LANG_Company', 'Empresa');
define('JS_LANG_JobTitle', 'Cargo');
define('JS_LANG_Department', 'Departamento');
define('JS_LANG_Office', 'Escritório');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Outros');
define('JS_LANG_OtherEmail', 'Outro E-mail');
define('JS_LANG_Notes', 'Anotações');
define('JS_LANG_Groups', 'Grupos');
define('JS_LANG_ShowAddFields', 'Exibir campos adicionais');
define('JS_LANG_HideAddFields', 'Ocultar campos adicionais');
define('JS_LANG_EditContact', 'Editar Informações do Contato');
define('JS_LANG_GroupName', 'Nome do Grupo');
define('JS_LANG_AddContacts', 'Adicionar Contatos');
define('JS_LANG_CommentAddContacts', '(Se você precisar informar mais do que um endereço, por favor separe por vírgulas)');
define('JS_LANG_CreateGroup', 'Criar Grupo');
define('JS_LANG_Rename', 'Renomear');
define('JS_LANG_MailGroup', 'Mail Grupo');
define('JS_LANG_RemoveFromGroup', 'Remover do grupo');
define('JS_LANG_UseImportTo', 'Utilize esta ferramenta para importar uma cópia dos seus contatos do Microsoft Outlook ou Microsoft Outlook Express dentro da lista de contatos do Webmail.');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Selecione o arquivo (formato: .CSV) que você quer importar');
define('JS_LANG_Import', 'Importar');
define('JS_LANG_ContactsMessage', 'Esta é a página de contatos!!!');
define('JS_LANG_ContactsCount', 'Contato(s)');
define('JS_LANG_GroupsCount', 'grupo(s)');

// webmail 4.1 constants
define('PicturesBlocked', 'As imagens desta mensagem foram bloqueadas por segurança.');
define('ShowPictures', 'Exibir imagens');
define('ShowPicturesFromSender', 'Sempre mostrar as imagens deste remetente.');
define('AlwaysShowPictures', 'Sempre mostrar imagens nas mensagens.');

define('TreatAsOrganization', 'Tratar como uma organização');

define('WarningGroupAlreadyExist', 'O nome do grupo escolhido já existe. Por favor informe outro nome.');
define('WarningCorrectFolderName', 'Você precisa especificar um nome correto para a pasta.');
define('WarningLoginFieldBlank', 'Você não pode deixar o campo login em branco.');
define('WarningCorrectLogin', 'Você deve especificar um login correto.');
define('WarningPassBlank', 'Você não pode deixar o campo senha em branco.');
define('WarningCorrectIncServer', 'Você deve especificar um servidor POP3(IMAP) correto.');
define('WarningCorrectSMTPServer', 'Você deve especificar um servidor SMTP correto.');
define('WarningFromBlank', 'Você não pode deixar o campo De em branco.');
define('WarningAdvancedDateFormat', 'Por favor, informe o formato da data.');

define('AdvancedDateHelpTitle', 'Data avançada');
define('AdvancedDateHelpIntro', 'Quando o campo &quot;Avançado&quot; estiver selecionado, você pode especificar seu próprio formato de data, que será mostrado no Webmail. As seguintes opções são usadas para este fim juntamente com os delimitadores \':\' ou \'/\':');
define('AdvancedDateHelpConclusion', 'Por exemplo, se você especificar o valor &quot;mm/dd/yyyy&quot;, a data será exibida como mês/dia/ano (Ex. 12/21/2007)');
define('AdvancedDateHelpDayOfMonth', 'Dia do Mês (1 até 31)');
define('AdvancedDateHelpNumericMonth', 'Mês (1 até 12)');
define('AdvancedDateHelpTextualMonth', 'Mês (Jan até Dez)');
define('AdvancedDateHelpYear2', 'Ano, 2 dígitos');
define('AdvancedDateHelpYear4', 'Ano, 4 dígitos');
define('AdvancedDateHelpDayOfYear', 'Dia do ano (1 até 366)');
define('AdvancedDateHelpQuarter', 'Quarto');
define('AdvancedDateHelpDayOfWeek', 'Dia da semana (Seg até Sex)');
define('AdvancedDateHelpWeekOfYear', 'Semana do ano (1 até 53)');

define('InfoNoMessagesFound', 'Nenhuma mensagem encontrada.');
define('ErrorSMTPConnect', 'Não foi possível conectar no servidor SMTP. Verifique suas configurações.');
define('ErrorSMTPAuth', 'Login ou senha inválidos. Falha na autenticação.');
define('ReportMessageSent', 'Sua mensagem foi enviada com sucesso.');
define('ReportMessageSaved', 'Sua mensagem foi salva com sucesso.');
define('ErrorPOP3Connect', 'Não foi possível conectar no servidor POP3. Verifique suas configurações.');
define('ErrorIMAP4Connect', 'Não foi possível conectar no servidor IMAP4. Verifique suas configurações.');
define('ErrorPOP3IMAP4Auth', 'Login ou senha inválidos. Falha na autenticação.');
define('ErrorGetMailLimit', 'Desculpe, seu limite de caixa postal excedeu.');

define('ReportSettingsUpdatedSuccessfuly', 'As configurações foram atualizadas com sucesso.');
define('ReportAccountCreatedSuccessfuly', 'A conta foi criada com sucesso.');
define('ReportAccountUpdatedSuccessfuly', 'A conta foi atualizada com sucesso.');
define('ConfirmDeleteAccount', 'Você tem certeza que deseja excluir esta conta?');
define('ReportFiltersUpdatedSuccessfuly', 'Os filtros foram atualizados com sucesso.');
define('ReportSignatureUpdatedSuccessfuly', 'A assinatura foi atualizada com sucesso.');
define('ReportFoldersUpdatedSuccessfuly', 'As pastas foram atualizadas com sucesso.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'As configurações dos contatos foram salvas com sucesso.');

define('ErrorInvalidCSV', 'Arquivo CSV que você escolheu está com formato errado.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'O grupo');
define('ReportGroupSuccessfulyAdded2', 'foi adicionado com sucesso.');
define('ReportGroupUpdatedSuccessfuly', 'Grupo foi salvo com sucesso.');
define('ReportContactSuccessfulyAdded', 'Contato foi salvo com sucesso.');
define('ReportContactUpdatedSuccessfuly', 'Contato foi atualizado com sucesso.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Contato(s) foi adicionado(s) ao grupo');
define('AlertNoContactsGroupsSelected', 'Nenhum grupo ou contato selecionado');

define('InfoListNotContainAddress', 'If the list doesn\'t contain the address you\'re looking for, keep typing its first chars.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Modo Direto. Webmail acessa diretamente as mensagens no servidor.');

define('FolderInbox', 'Caixa de Entrada');
define('FolderSentItems', 'Enviadas');
define('FolderDrafts', 'Rascunho');
define('FolderTrash', 'Lixeira');

define('FileLargerAttachment', 'O arquivo em anexo excedeu o limite permitido.');
define('FilePartiallyUploaded', 'Somente uma parte do arquivo foi anexado devido a um erro desconhecido.');
define('NoFileUploaded', 'Nenhum arquivo foi anexado.');
define('MissingTempFolder', 'A pasta temporária está faltando.');
define('MissingTempFile', 'O arquivo temporário está faltando.');
define('UnknownUploadError', 'Um erro desconhecido ocorreu ao anexar o arquivo.');
define('FileLargerThan', 'Erro anexando arquivo. provavelmente, o arquivo é maior do que  ');
define('PROC_CANT_LOAD_DB', 'Não foi possível conectar ao banco de dados.');
define('PROC_CANT_LOAD_LANG', 'Não foi possível encontrar o arquivo de linguagem requerido.');
define('PROC_CANT_LOAD_ACCT', 'A conta não existe, talvez, ela tenha sido excluída.');

define('DomainDosntExist', 'O domínio não existe no servidor.');
define('ServerIsDisable', 'O uso do servidor está proibido pelo administrador.');

define('PROC_ACCOUNT_EXISTS', 'A conta não pode ser criada pois ela já existe.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Não foi possível pegar o total de mensagens da pasta.');
define('PROC_CANT_MAIL_SIZE', 'Não foi possível pegar o tamanho da mensagem.');

define('Organization', 'Organização');
define('WarningOutServerBlank', 'Você não pode deixar o campo SMTP em branco.');

//
define('JS_LANG_Refresh', 'Atualizar');
define('JS_LANG_MessagesInInbox', 'Mensagem(ens) na Caixa de Entrada');
define('JS_LANG_InfoEmptyInbox', 'Caixa de Entrada vazia');

// webmail 4.2 constants
define('BackToList', 'Voltar para a Lista');
define('InfoNoContactsGroups', 'Nenhum contato ou grupo.');
define('InfoNewContactsGroups', 'Você  já pode criar novos contatos/grupos ou importar contatos de um arquivo .CSV (formato MS Outlook).');
define('DefTimeFormat', 'Formato de horário padro');
define('SpellNoSuggestions', 'Sem sugestões');
define('SpellWait', 'Por favor, aguarde !');

define('InfoNoMessageSelected', 'Nenhuma mensagem foi selecionada.');
define('InfoSingleDoubleClick', 'Clique uma única vez para visualizar qualquer mensagem da lista ou dê um clique duplo para visualizar a mensagem em tela cheia.');

// calendar (agenda)
define('TitleDay', 'Visualizar Dia');
define('TitleWeek', 'Visualizar Semana');
define('TitleMonth', 'Visualizar Mês');

define('ErrorNotSupportBrowser', 'Agenda não  suportada pelo seu navegador. Utilize o FireFox 2.0 ou superior, Opera 9.0 ou superior, Internet Explorer 6.0 ou superior, Safari 3.0.2 ou superior.');
define('ErrorTurnedOffActiveX', 'O suporte  Activex está desligado . <br/>Você deveria ativá-lo antes de utilizar esta aplicação.');

define('Calendar', 'Agenda');

define('TabDay', 'Dia');
define('TabWeek', 'Semana');
define('TabMonth', 'Mês');

define('ToolNewEvent', 'Novo&nbsp;Evento');
define('ToolBack', 'Voltar');
define('ToolToday', 'Hoje');
define('AltNewEvent', 'Novo Evento');
define('AltBack', 'Voltar');
define('AltToday', 'Hoje');
define('CalendarHeader', 'Agenda');
define('CalendarsManager', 'Gerenciador de Agendas');

define('CalendarActionNew', 'Nova agenda');
define('EventHeaderNew', 'Novo Evento');
define('CalendarHeaderNew', 'Nova Agenda');

define('EventSubject', 'Assunto');
define('EventCalendar', 'Agenda');
define('EventFrom', 'De');
define('EventTill', 'até');
define('CalendarDescription', 'Descrição');
define('CalendarColor', 'Cor');
define('CalendarName', 'Nome da Agenda');
define('CalendarDefaultName', 'Minha Agenda');

define('ButtonSave', 'Salvar');
define('ButtonCancel', 'Cancelar');
define('ButtonDelete', 'Apagar');

define('AltPrevMonth', 'Mês Anterior');
define('AltNextMonth', 'Prx. Mês');

define('CalendarHeaderEdit', 'Editar Agenda');
define('CalendarActionEdit', 'Editar Agenda');
define('ConfirmDeleteCalendar', 'Tem certeza que deseja apagar a agenda ?');
define('InfoDeleting', 'Apagando&hellip;');
define('WarningCalendarNameBlank', 'Não  permitido deixar o nome da agenda em branco.');
define('ErrorCalendarNotCreated', 'Agenda não foi criada.');
define('WarningSubjectBlank', 'Não  permitido deixar o campo assunto em branco.');
define('WarningIncorrectTime', 'O horário informado contém caracteres inválidos.');
define('WarningIncorrectFromTime', 'O horário inicial está incorreto.');
define('WarningIncorrectTillTime', 'O horário final está incorreto.');
define('WarningStartEndDate', 'A data final deve ser igual ou superior a data inicial.');
define('WarningStartEndTime', 'O horário final deve ser superior ao horário inicial.');
define('WarningIncorrectDate', 'A data deve estar correta.');
define('InfoLoading', 'Carregando&hellip;');
define('EventCreate', 'Criar evento');
define('CalendarHideOther', 'Ocultar outras agendas');
define('CalendarShowOther', 'Exibir outras agendas');
define('CalendarRemove', 'Apagar Agenda');
define('EventHeaderEdit', 'Editar Evento');

define('InfoSaving', 'Salvando&hellip;');
define('SettingsDisplayName', 'Exibir Nome');
define('SettingsTimeFormat', 'Formato da Hora');
define('SettingsDateFormat', 'Formato da Data');
define('SettingsShowWeekends', 'Exibir semanas');
define('SettingsWorkdayStarts', 'Dia de trabalho começa');
define('SettingsWorkdayEnds', 'termina');
define('SettingsShowWorkday', 'Exibir dia de trabalho');
define('SettingsWeekStartsOn', 'Semana inicia em');
define('SettingsDefaultTab', 'Guia Padrão');
define('SettingsCountry', 'Pas');
define('SettingsTimeZone', 'Fuso Horário');
define('SettingsAllTimeZones', 'Todos os fusos horários');

define('WarningWorkdayStartsEnds', 'O horário de término do dia de trabalho deve ser superior ao horário de início.');
define('ReportSettingsUpdated', 'Configurações foram atualizadas com sucesso.');

define('SettingsTabCalendar', 'Agenda');

define('FullMonthJanuary', 'Janeiro');
define('FullMonthFebruary', 'Fevereiro');
define('FullMonthMarch', 'Março');
define('FullMonthApril', 'Abril');
define('FullMonthMay', 'Maio');
define('FullMonthJune', 'Junho');
define('FullMonthJuly', 'Julho');
define('FullMonthAugust', 'Agosto');
define('FullMonthSeptember', 'Setembro');
define('FullMonthOctober', 'Outubro');
define('FullMonthNovember', 'Novembro');
define('FullMonthDecember', 'Dezembro');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Fev');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Abr');
define('ShortMonthMay', 'Mai');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Ago');
define('ShortMonthSeptember', 'Set');
define('ShortMonthOctober', 'Out');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dez');

define('FullDayMonday', 'Segunda');
define('FullDayTuesday', 'Terça');
define('FullDayWednesday', 'Quarta');
define('FullDayThursday', 'Quinta');
define('FullDayFriday', 'Sexta');
define('FullDaySaturday', 'Sábado');
define('FullDaySunday', 'Domingo');

define('DayToolMonday', 'Seg');
define('DayToolTuesday', 'Ter');
define('DayToolWednesday', 'Qua');
define('DayToolThursday', 'Qui');
define('DayToolFriday', 'Sex');
define('DayToolSaturday', 'Sb');
define('DayToolSunday', 'Dom');

define('CalendarTableDayMonday', 'S');
define('CalendarTableDayTuesday', 'T');
define('CalendarTableDayWednesday', 'Q');
define('CalendarTableDayThursday', 'Q');
define('CalendarTableDayFriday', 'S');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'D');

define('ErrorParseJSON', 'A resposta JSON devolvida pelo servidor não pode ser analisada.');

define('ErrorLoadCalendar', 'Não foi possível carregar agendas');
define('ErrorLoadEvents', 'Não foi possível carregar eventos');
define('ErrorUpdateEvent', 'Não foi possível salvar o evento');
define('ErrorDeleteEvent', 'Não foi possível remover o evento');
define('ErrorUpdateCalendar', 'Não foi possível salvar a agenda');
define('ErrorDeleteCalendar', 'Não foi possível remover a agenda');
define('ErrorGeneral', 'Ocorreu um erro no servidor. Tente novamente mais tarde.');

// webmail 4.3 Constantes
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Compartilhar e publicar agenda');
define('ShareActionEdit', 'Compartilhar e publicar agenda');
define('CalendarPublicate', 'Tornar público o acesso a esta agenda na web');
define('CalendarPublicationLink', 'Link');
define('ShareCalendar', 'Compartilhar esta agenda');
define('SharePermission1', 'Pode efetuar alterações e gerenciar compartilhamento');
define('SharePermission2', 'Pode efetuar alterações nos eventos');
define('SharePermission3', 'Pode ver todos os detalhes do evento');
define('SharePermission4', 'Somente pode ver livre/ocupado (ocultar detalhes)');
define('ButtonClose', 'Fechar');
define('WarningEmailFieldFilling', 'Você deve preencher primeiro o campo de e-mail');
define('EventHeaderView', 'Exibir Eventos');
define('ErrorUpdateSharing', 'Não foi possível salvar a publicação e compartilhamento dos dados');
define('ErrorUpdateSharing1', 'Não  possível compartilhar para usuário %s (visto que não existe)');
define('ErrorUpdateSharing2', 'Impossível compartilhar esta agenda para o usuário %s');
define('ErrorUpdateSharing3', 'Esta agenda já está compartilhada para o usuário %s');

define('Title_MyCalendars', 'Minhas agendas');
define('Title_SharedCalendars', 'Compartilhar agendas');
define('ErrorGetPublicationHash', 'Não foi possível criar o link de publicação');
define('ErrorGetSharing', 'Impossível adicionar compartilhamento');
define('CalendarPublishedTitle', 'Agenda publicada');
define('RefreshSharedCalendars', 'Atualizar Agendas Compartilhadas');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Membros');

define('ReportMessagePartDisplayed', 'Observe que somente uma parte da mensagem está sendo exibida.');
define('ReportViewEntireMessage', 'Para visualizar todo o conteúdo da mensagem,');
define('ReportClickHere', 'clique aqui');
define('ErrorContactExists', 'Já existe um contato com o mesmo nome e e-mail.');

define('Attachments', 'Anexos');

define('InfoGroupsOfContact', 'Os grupos em que o contato membro estão marcados.');
define('AlertNoContactsSelected', 'Nenhum contato foi selecionado.');
define('MailSelected', 'Endereços de e-mail selecionados');
define('CaptionSubscribed', 'Inscritos');

define('OperationSpam', 'Spam');
define('OperationNotSpam', 'Não é spam');
define('FolderSpam', 'Spam');

// webmail 4.4 contatos
define('ContactMail', 'Contato');
define('ContactViewAllMails', 'Exibir todas as mensagens deste contato');
define('ContactsMailThem', 'Enviar mensagem para eles');
define('DateToday', 'Hoje');
define('DateYesterday', 'Ontem');
define('MessageShowDetails', 'Exibir detalhes');
define('MessageHideDetails', 'Ocultar detalhes');
define('MessageNoSubject', 'Sem assunto');
// john@gmail.com to nadine@gmail.com
define('MessageForAddr', 'para');
define('SearchClear', 'Limpar pesquisa');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Procurar resultados de "#s" na pasta #f:');
define('SearchResultsInAllFolders', 'Procurar resultados de "#s" em todas as pastas:');
define('AutoresponderTitle', 'Autoresposta');
define('AutoresponderEnable', 'Habilitar autoresposta');
define('AutoresponderSubject', 'Assunto');
define('AutoresponderMessage', 'Mensagem');
define('ReportAutoresponderUpdatedSuccessfuly', 'Autoresposta atualizada com êxito.');
define('FolderQuarantine', 'Quarentena');

//calendar (agenda)
define('EventRepeats', 'Repetir');
define('NoRepeats', 'Não repetir');
define('DailyRepeats', 'Diariamente');
define('WorkdayRepeats', 'Em dias da semana (Seg. - Sex.)');
define('OddDayRepeats', 'Toda Seg., Qua. e Sex.');
define('EvenDayRepeats', 'Toda Ter. e Qui.');
define('WeeklyRepeats', 'Semanalmente');
define('MonthlyRepeats', 'Mensalmente');
define('YearlyRepeats', 'Anualmente');
define('RepeatsEvery', 'Repetir a cada');
define('ThisInstance', 'Apenas esta ocorrência');
define('AllEvents', 'Todos os eventos na série');
define('AllFollowing', 'Todos os seguintes');
define('ConfirmEditRepeatEvent', 'Gostaria de alterar somente este evento, todos os eventos, ou este e todos os futuros eventos na série ?');
define('RepeatEventHeaderEdit', 'Editar evento recorrente');
define('First', 'Primeiro');
define('Second', 'Segundo');
define('Third', 'Terceiro');
define('Fourth', 'Quarto');
define('Last', 'último');
define('Every', 'Cada');
define('SetRepeatEventEnd', 'Configurar data final');
define('NoEndRepeatEvent', 'Sem data final');
define('EndRepeatEventAfter', 'Terminar após');
define('Occurrences', 'ocorrências');
define('EndRepeatEventBy', 'término');
define('EventCommonDataTab', 'Detalhes principais');
define('EventRepeatDataTab', 'Detalhes recorrentes');
define('RepeatEventNotPartOfASeries', 'Este evento foi alterado e já não faz parte de uma série.');
define('UndoRepeatExclusion', 'Desfazer alterações para incluir na série.');

define('MonthMoreLink', '%d mais...');
define('NoNewSharedCalendars', 'Nenhuma nova agenda');
define('NNewSharedCalendars', '%d novas agendas encontradas');
define('OneNewSharedCalendars', '1 nova agenda encontrada');
define('ConfirmUndoOneRepeat', 'Gostaria de restaurar este evento nas series?');

define('RepeatEveryDayInfin', 'Todo dia');
define('RepeatEveryDayTimes', 'Todo dia, %TIMES% vezes');
define('RepeatEveryDayUntil', 'Todo dia, até %UNTIL%');
define('RepeatDaysInfin', 'A cada %PERIOD% dias');
define('RepeatDaysTimes', 'A cada %PERIOD% dia(s), %TIMES% vezes');
define('RepeatDaysUntil', 'A cada %PERIOD% dias, até %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Toda semana em dias da semana');
define('RepeatEveryWeekWeekdaysTimes', 'Toda semana em dias da semana, %TIMES% vezes');
define('RepeatEveryWeekWeekdaysUntil', 'Toda semana em dias da semana, até %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'A cada %PERIOD% semanas em dias da semana');
define('RepeatWeeksWeekdaysTimes', 'A cada %PERIOD% semanas em dias da semana, %TIMES% vezes');
define('RepeatWeeksWeekdaysUntil', 'A cada %PERIOD% semanas em dias da semana, até %UNTIL%');

define('RepeatEveryWeekInfin', 'Toda semana no %DAYS%');
define('RepeatEveryWeekTimes', 'Toda semana no %DAYS%, %TIMES% vezes');
define('RepeatEveryWeekUntil', 'Toda semana no %DAYS%, até %UNTIL%');
define('RepeatWeeksInfin', 'A cada %PERIOD% semanas no %DAYS%');
define('RepeatWeeksTimes', 'A cada %PERIOD% semanas no %DAYS%, %TIMES% vezes');
define('RepeatWeeksUntil', 'A cada %PERIOD% semanas no %DAYS%, at %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Todo mês no dia %DATE%');
define('RepeatEveryMonthDateTimes', 'Todo mês no dia %DATE%, %TIMES% vezes');
define('RepeatEveryMonthDateUntil', 'Todo mês no dia %DATE%, até %UNTIL%');
define('RepeatMonthsDateInfin', 'A cada %PERIOD% meses no dia %DATE%');
define('RepeatMonthsDateTimes', 'A cada %PERIOD% meses no dia %DATE%, %TIMES% vezes');
define('RepeatMonthsDateUntil', 'A cada %PERIOD% meses no dia %DATE%, até %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Todo mês no %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Todo mês no dia %NUMBER% %DAY%, %TIMES% vezes');
define('RepeatEveryMonthWDUntil', 'Todo mês no %NUMBER% %DAY%, até %UNTIL%');
define('RepeatMonthsWDInfin', 'A cada %PERIOD% meses no %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'A cada %PERIOD% meses no %NUMBER% %DAY%, %TIMES% vezes');
define('RepeatMonthsWDUntil', 'A cada %PERIOD% meses no %NUMBER% %DAY%, até %UNTIL%');

define('RepeatEveryYearDateInfin', 'Todo ano no dia %DATE%');
define('RepeatEveryYearDateTimes', 'Todo ano no dia %DATE%, %TIMES% vezes');
define('RepeatEveryYearDateUntil', 'Todo ano no dia %DATE%, até %UNTIL%');
define('RepeatYearsDateInfin', 'A cada %PERIOD% anos no dia %DATE%');
define('RepeatYearsDateTimes', 'A cada %PERIOD% anos no dia %DATE%, %TIMES% vezes');
define('RepeatYearsDateUntil', 'A cada %PERIOD% anos no dia %DATE%, até %UNTIL%');

define('RepeatEveryYearWDInfin', 'Todo ano no %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Todo ano no %NUMBER% %DAY%, %TIMES% vezes');
define('RepeatEveryYearWDUntil', 'Todo ano no %NUMBER% %DAY%, at %UNTIL%');
define('RepeatYearsWDInfin', 'A cada %PERIOD% anos no %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'A cada %PERIOD% anos no %NUMBER% %DAY%, %TIMES% vezes');
define('RepeatYearsWDUntil', 'A cada %PERIOD% anos no %NUMBER% %DAY%, até %UNTIL%');

define('RepeatDescDay', 'dia');
define('RepeatDescWeek', 'semana');
define('RepeatDescMonth', 'mês');
define('RepeatDescYear', 'ano');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Especificar data final de referência');
define('WarningWrongUntilDate', 'Data final de referência deve ser posterior a data do início ');

define('OnDays', 'Em dias');
define('CancelRecurrence', 'Cancelar referência');
define('RepeatEvent', 'Repita este evento');

define('Spellcheck', 'Verificar Ortografia');
define('LoginLanguage', 'Idioma');
define('LanguageDefault', 'Padrão');

// webmail 4.5.x new
define('EmptySpam', 'Repositório de spam vazio');
define('Saving', 'Salvando&hellip;');
define('Sending', 'Enviando&hellip;');
define('LoggingOffFromServer', 'Desconectando do servidor&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Impossível marcar mensagem(ens) como spam');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Impossível marcar mensagem(ens) como não-spam');
define('ExportToICalendar', 'Exportar para Agenda');
define('ErrorMaximumUsersLicenseIsExceeded', 'Sua conta está desabilitada porque o número máximo de usuários permitido pela licença foi excedido. Entre em contato com o administrador do sistema.');
define('RepliedMessageTitle', 'Mensagem Respondida');
define('ForwardedMessageTitle', 'Mensagem Encaminhada');
define('RepliedForwardedMessageTitle', 'Mensagem Respondida e Encaminhada');
define('ErrorDomainExist', 'Usuário não pode ser criado, porque o domínio correspondente não existe. Crie o domínio primeiro.');

// webmail 4.7
define('RequestReadConfirmation', 'Solicitar confirmação de leitura');
define('FolderTypeDefault', 'Padrão');
define('ShowFoldersMapping', 'Permita-me usar outra pasta como uma pasta de sistema (ex. usar Meus Documentos como Itens enviados)');
define('ShowFoldersMappingNote', 'Por exemplo, para alterar a localização de Itens enviados da pasta Itens enviados para a pasta Meus Documentos, informe "Itens enviados" em "Utilizar para" e selecione "Meus Documentos" na lista de opções.');
define('FolderTypeMapTo', 'Utilizar para');

define('ReminderEmailExplanation', 'Esta mensagem foi enviada para sua conta %EMAIL% porque você solicitou a notificação de um evento na sua agenda: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Abrir agenda');

define('AddReminder', 'Lembrar-me sobre este evento');
define('AddReminderBefore', 'Lembrar-me % antes deste evento');
define('AddReminderAnd', 'e % antes');
define('AddReminderAlso', 'e também % antes');
define('AddMoreReminder', 'Mais lembretes');
define('RemoveAllReminders', 'Remover todos os lembretes');
define('ReminderNone', 'Nenhum');
define('ReminderMinutes', 'minutos');
define('ReminderHour', 'hora');
define('ReminderHours', 'horas');
define('ReminderDay', 'dia');
define('ReminderDays', 'dias');
define('ReminderWeek', 'semana');
define('ReminderWeeks', 'semanas');
define('Allday', 'Todos os dias');

define('Folders', 'Pastas');
define('NoSubject', 'Sem assunto');
define('SearchResultsFor', 'Resultados da pesquisa para');

define('Back', 'Voltar');
define('Next', 'Próximo');
define('Prev', 'Anterior');

define('MsgList', 'Mensagens');
define('Use24HTimeFormat', ' Usar formato 24 horas');
define('UseCalendars', 'Usar calendários');
define('Event', 'Evento');
define('CalendarSettingsNullLine', 'Sem calendários');
define('CalendarEventNullLine', 'Sem eventos');
define('ChangeAccount', 'Mudar conta');

define('TitleCalendar', 'Calendário');
define('TitleEvent', 'Evento');
define('TitleFolders', 'Pastas');
define('TitleConfirmation', 'Confirmação');

define('Yes', 'Yes');
define('No', 'Não');

define('EditMessage', 'Editar mensagem');

define('AccountNewPassword', 'Nova Senha');
define('AccountConfirmNewPassword', 'Confirmar nova senha');
define('AccountPasswordsDoNotMatch', 'As senhas não coincidem.');

define('ContactTitle', 'Título');
define('ContactFirstName', 'Primeiro nome');
define('ContactSurName', 'Apelido');
define('ContactNickName', 'Nickname');

define('CaptchaTitle', 'Captcha');
define('CaptchaReloadLink', 'recarregar');
define('CaptchaError', 'Captcha texto está errado.');

define('WarningInputCorrectEmails', 'Por favor especifique e-mails corretos.');
define('WrongEmails', 'E-mails incorretos:');

define('ConfirmBodySize1', 'Desculpe, texto muito longo.');
define('ConfirmBodySize2', 'Texto comprido. Texto além do limite será truncado. Clique em "Cancelar" se você quiser editar a mensagem.');
define('BodySizeCounter', 'Contador');
define('InsertImage', 'Inserir Imagem');
define('ImagePath', 'Imagem caminho');
define('ImageUpload', 'Inserir');
define('WarningImageUpload', 'O arquivo a ser anexado não é uma imagem. Por favor, escolha um arquivo de imagem.');

define('ConfirmExitFromNewMessage', 'Alterações serão perdidas se você sair da página. Deseja salvar rascunho antes de deixar a página?');

define('SensivityConfidential', 'Por favor, trate esta mensagem como confidencial');
define('SensivityPrivate', 'Por favor, trate esta mensagem como privada');
define('SensivityPersonal', 'Por favor, trate esta mensagem como pessoal');

define('ReturnReceiptTopText', 'O remetente desta mensagem pediu para ser notificado quando você recebe-la.');
define('ReturnReceiptTopLink', 'Clique aqui para notificar o remetente.');
define('ReturnReceiptSubject', 'Retorno Recepção (exibido)');
define('ReturnReceiptMailText1', ' Este é um comprovante de retorno do e-mail que você enviou para');
define('ReturnReceiptMailText2', ' Nota: Este retorno de recepção, apenas reconhece que a mensagem foi exibida no computador do destinatário. Não há garantia de que o destinatário tenha lido ou entendido o conteúdo da mensagem.');
define('ReturnReceiptMailText3', 'com o assunto');

define('SensivityMenu', 'Sensibilidade');
define('SensivityNothingMenu', 'Nenhuma');
define('SensivityConfidentialMenu', 'Confidencial');
define('SensivityPrivateMenu', 'Privativo');
define('SensivityPersonalMenu', 'Pessoal');

define('ErrorLDAPonnect', 'Can\'t connect to ldap server.');

define('MessageSizeExceedsAccountQuota', 'Esse tamanho de mensagem excede sua cota de conta.');
define('MessageCannotSent', 'A mensagem não pode ser enviada.');
define('MessageCannotSaved', 'A mensagem não pode ser salva.');

define('ContactFieldTitle', 'Campo');
define('ContactDropDownTO', 'PARA');
define('ContactDropDownCC', 'CC');
define('ContactDropDownBCC', 'BCC');

// 4.9
define('NoMoveDelete', ' Mensagem (s) não pode(m) ser movido(s) para a lixeira. Provavelmente sua caixa de mensagens está cheia. Deseja apagar em definitivo está mensagem?');

define('WarningFieldBlank', 'Este campo não pode ser vazio');
define('WarningPassNotMatch', 'Senha errada, por favor verifique.');
define('PasswordResetTitle', 'Recuperação de senha – etapa %d');
define('NullUserNameonReset', 'usuário');
define('IndexResetLink', 'Perdeu sua senha?');
define('IndexRegLink', 'Registrar conta');

define('RegDomainNotExist', 'Domínio não existe.');
define('RegAnswersIncorrect', 'As respostas estão incorretas.');
define('RegUnknownAdress', 'E-mail desconhecido.');
define('RegUnrecoverableAccount', 'Recuperação de senha não pode ser aplicado para este endereço de email');
define('RegAccountExist', 'Este endereço já está sendo usado.');
define('RegRegistrationTitle', 'Registro');
define('RegName', 'Nome');
define('RegEmail', 'e-mail endereço');
define('RegEmailDesc', 'Por exemplo ,meunome@dominio .com.br. Esta informação será usada para entrar no sistema.');
define('RegSignMe', 'Lembrar-me');
define('RegSignMeDesc', ' Não pedir login e senha no próximo login no sistema em seu PC.');
define('RegPass1', 'Senha');
define('RegPass2', 'Repita a senha');
define('RegQuestionDesc', ' Por favor, forneça duas perguntas secretas e respostas que só você sabe. Em caso de perda da senha, você pode usar essas perguntas, a fim de recupera-la.');
define('RegQuestion1', 'Pergunta secreta 1');
define('RegAnswer1', 'Resposta 1');
define('RegQuestion2', 'Pergunta secreta 2');
define('RegAnswer2', 'Resposta 2');
define('RegTimeZone', 'Time zone');
define('RegLang', 'Interface linguagem');
define('RegCaptcha', 'Captcha');
define('RegSubmitButtonValue', 'Registrar');

define('ResetEmail', 'Por favor, forneça seu e-mail ');
define('ResetEmailDesc', 'Forneça endereço de e-mail usado para registro.');
define('ResetCaptcha', 'CAPTCHA');
define('ResetSubmitStep1', 'Send');
define('ResetQuestion1', 'Pergunta secreta 1');
define('ResetAnswer1', 'Resposta');
define('ResetQuestion2', 'Pergunta secreta 2');
define('ResetAnswer2', 'Resposta');
define('ResetSubmitStep2', 'Send');

define('ResetTopDesc1Step2', 'Informe seu e-mail');
define('ResetTopDesc2Step2', 'Por favor, confirme a correção.');

define('ResetTopDescStep3', 'por favor especifique uma nova senha para seu e-mail.');

define('ResetPass1', 'Nova senha');
define('ResetPass2', 'Repita a senha');
define('ResetSubmitStep3', 'Send');
define('ResetDescStep4', 'Sua senha foi alterada.');
define('ResetSubmitStep4', 'Return');

define('RegReturnLink', 'Return to login screen');
define('ResetReturnLink', 'Return to login screen');

// Appointments
define('AppointmentAddGuests', 'Adicionar convidados');
define('AppointmentRemoveGuests', 'Cancelar reunião');
define('AppointmentListEmails', 'Informe os e-mails separados por virgula.');
define('AppointmentParticipants', 'Participantes');
define('AppointmentRefused', 'Recusar');
define('AppointmentAwaitingResponse', 'Aguardando resposta');
define('AppointmentInvalidGuestEmail', 'Os seguintes endereços de e-mail dos convidados são inválidos:');
define('AppointmentOwner', 'Proprietário');

define('AppointmentMsgTitleInvite', 'Convite para o evento.');
define('AppointmentMsgTitleUpdate', 'Evento modificado.');
define('AppointmentMsgTitleCancel', 'Evento cancelado.');
define('AppointmentMsgTitleRefuse', 'Convidado %guest% recusou o convite');
define('AppointmentMoreInfo', 'Mais informações');
define('AppointmentOrganizer', 'Organização');
define('AppointmentEventInformation', 'Evento informação');
define('AppointmentEventWhen', 'Quando');
define('AppointmentEventParticipants', 'Participantes');
define('AppointmentEventDescription', 'Descrição');
define('AppointmentEventWillYou', 'Você pode participar');
define('AppointmentAdditionalParameters', 'Parâmetros adicionais');
define('AppointmentHaventRespond', 'Não respondeu ainda ');
define('AppointmentRespondYes', 'Eu vou participar’');
define('AppointmentRespondMaybe', 'Não tenho certeza ainda');
define('AppointmentRespondNo', 'Eu não vou participar');
define('AppointmentGuestsChangeEvent', ' Os convidados podem mudar de eventos');

define('AppointmentSubjectAddStart', 'Você recebeu convite para evento');
define('AppointmentSubjectAddFrom', 'de ');
define('AppointmentSubjectUpdateStart', 'Modificação do evento');
define('AppointmentSubjectDeleteStart', 'Cancelamento do evento');
define('ErrorAppointmentChangeRespond', 'Não é possível alterar o compromisso responder ');
define('SettingsAutoAddInvitation', 'Adicionar automaticamente convites para o calendário');
define('ReportEventSaved', 'O evento foi salvo');
define('ReportAppointmentSaved', ' e os avisos foram enviados');
define('ErrorAppointmentSend', ' Impossível enviar convites.');
define('AppointmentEventName', 'Nome:');

// End appointments

define('ErrorCantUpdateFilters', 'Impossível atualizar filtros');

define('FilterPhrase', 'Se não houver %field header %condition %string então %action');
define('FiltersAdd', 'Adicionar Filtro');
define('FiltersCondEqualTo', 'igual a');
define('FiltersCondContainSubstr', 'Contendo substring ');
define('FiltersCondNotContainSubstr', 'Não contém substring');
define('FiltersActionDelete', 'apagar messagem');
define('FiltersActionMove', 'mover');
define('FiltersActionToFolder', 'para pasta %folder');
define('FiltersNo', 'Filtros não especificados ainda');

define('ReminderEmailFriendly', 'Lembrete');
define('ReminderEventBegin', 'começa em: ');

define('FiltersLoading', 'Carregando filtros...');
define('ConfirmMessagesPermanentlyDeleted', 'Todas as mensagens nesta pasta serão excluídas permanentemente.');

define('InfoNoNewMessages', ' Não há novas mensagens.');
define('TitleImportContacts', 'Importar Contatos');
define('TitleSelectedContacts', 'Selecionar Contatos');
define('TitleNewContact', 'Novo Contato');
define('TitleViewContact', 'Ver Contato');
define('TitleEditContact', 'Editar Contato');
define('TitleNewGroup', 'Novo Grupo');
define('TitleViewGroup', 'Ver Grupo');

define('AttachmentComplete', 'Completado.');

define('TestButton', 'TESTE');
define('AutoCheckMailIntervalLabel', 'Autochecagem intervalo');
define('AutoCheckMailIntervalDisableName', 'Desabilitado');
define('ReportCalendarSaved', 'Calendário foi salvo.');

define('ContactSyncError', 'Sync falha');
define('ReportContactSyncDone', 'Sync completado');

define('MobileSyncUrlTitle', 'Mobile sync URL');

define('MobileSyncLoginTitle', 'Mobile sync login');

define('QuickReply', 'Resposta rápida');
define('SwitchToFullForm', 'Alternar para form completo');
define('SortFieldDate', 'Data');
define('SortFieldFrom', 'De');
define('SortFieldSize', 'Tamanho');
define('SortFieldSubject', 'Assunto');
define('SortFieldFlag', 'Flag');
define('SortFieldAttachments', 'Anexos');
define('SortOrderAscending', 'Crescente');
define('SortOrderDescending', ' Decrescente');
define('ArrangedBy', 'Ordenar por');

define('MessagePaneToRight', ' O painel de visualização da mensagem é a direita da lista de mensagens, ao invés de abaixo');

define('SettingsTabMobileSync', 'Mobile');

define('MobileSyncContactDataBaseTitle', 'Mobile sync contato database');
define('MobileSyncCalendarDataBaseTitle', 'Mobile sync calendário database');
define('MobileSyncTitleText', 'If you\'d like to synchronize your SyncML-enabled handheld device with WebMail, you can use these parameters.<br />"Mobile Sync URL" specifies path to SyncML Data Synchronization server, "Mobile Sync Login" is your login on SyncML Data Synchronization Server and use your own password upon request. Also, some devices need to specify database name for contact and calendar data.<br />Use "Mobile sync contact database" and "Mobile sync calendar database" respectively.');
define('MobileSyncEnableLabel', 'Habilitar mobile sync');

define('SearchInputText', 'pesquisar');

define('AppointmentEmailExplanation','This message arrived to your %EMAIL% account because you was invited to the event by %ORGANAZER%');

define('Searching', 'Searching&hellip;');

define('ButtonSetupSpecialFolders', 'Configurar pastas especiais');
define('ButtonSaveChanges', 'Salvar mudanças');
define('InfoPreDefinedFolders', 'Para pastas pré-definidas, use estas caixas de IMAP');

define('SaveMailInSentItems', 'Salvo em Itens Enviados');

define('CouldNotSaveUploadedFile', 'Não foi possível salvar arquivo enviado.');

define('AccountOldPassword', 'Current password');
define('AccountOldPasswordsDoNotMatch', 'Current Passwords do not match.');

define('DefEditor', 'Editor padrão');
define('DefEditorRichText', 'Rich Text');
define('DefEditorPlainText', 'Plain Text');

define('Layout', 'Layout');

define('TitleNewMessagesCount', '%count% nova(s) menssagem(ns)');

define('AltOpenInNewWindow', 'Abrir em nova janela');

define('SearchByFirstCharAll', 'Todos');

define('FolderNoUsageAssigned', 'Uso não atribudo');

define('InfoSetupSpecialFolders', 'Para associar uma pasta especial (como Itens Enviados) a caixa de entrada correta do IMAP, clique em Configurar pastas especiais.');

define('FileUploaderClickToAttach', 'Clique para anexar um arquivo');
define('FileUploaderOrDragNDrop', 'Ou basta arrastar e soltar arquivos aqui');

define('AutoCheckMailInterval1Minute', '1 minuto');
define('AutoCheckMailInterval3Minutes', '3 minutos');
define('AutoCheckMailInterval5Minutes', '5 minutos');
define('AutoCheckMailIntervalMinutes', 'minutos');

define('ReadAboutCSVLink', 'Saiba mais sobre os campos de arquivo .CSV');

define('VoiceMessageSubj', 'Mensagem de Voz');
define('VoiceMessageTranscription', 'Transcrição');
define('VoiceMessageReceived', 'Recebido');
define('VoiceMessageDownload', 'Download');
define('VoiceMessageUpgradeFlashPlayer', 'Você precisa atualizar o seu Adobe Flash Player para reproduzir mensagens de voz.<br />Atualize para Flash Player 10 0 em <a href="http://www.adobe.com/go/getflashplayer/" target="_blank">Adobe</a>.');

define('LicenseKeyIsOutdated', 'Esta chave de licença está desatualizada, entre em contato conosco para atualizar sua chave de licença');
define('LicenseProblem', 'Problema de licenciamento. Administrador do sistema deve ir no Painel de Administração para verificar os detalhes.');

define('AccountOldPasswordNotCorrect', 'Senha atual não está correta');
define('AccountNewPasswordUpdateError', 'Não foi possível salvar a nova senha.');
define('AccountNewPasswordRejected', 'Não foi possível salvar a nova senha. Talvez ela seja muito simples.');

define('CantCreateIdentity', 'Não foi possível criar identidade');
define('CantUpdateIdentity', 'Não foi possível atualizar a identidade');
define('CantDeleteIdentity', 'Não foi possível excluir identidade');

define('AddIdentity', 'Adicionar Identidade');
define('SettingsTabIdentities', 'Identidade');
define('NoIdentities', 'Não há identidades');
define('NoSignature', 'Sem assinatura');
define('Account', 'Conta');
define('TabChangePassword', 'Senha');
define('SignatureEnteringHere', 'Digite a sua assinatura aqui');

define('CantConnectToMailServer', 'Não é possível conectar ao servidor de e-mail');

define('DomainNameNotSpecified', 'Nome de domínio não especificado.');

define('Open', 'Abrir');
define('FolderUsedAs', 'usado como');
define('ForwardTitle', 'Encaminhar');
define('ForwardEnable', 'Habilitar encaminhamento');
define('ReportForwardUpdatedSuccessfuly', 'Encaminhamento atualizado com sucesso.');

define('DialogAttachHeaderResume', 'Anexar Your Resume');
define('DialogAttachHeaderLetter', 'Anexar Your Cover Letter');
define('DialogAttachName', 'Select Resume');
define('DialogAttachType', 'Escolha um formato');
define('DialogAttachTypePdf', 'Adobe PDF (.pdf)');
define('DialogAttachTypeHtml', 'Web Page (.html)');
define('DialogAttachTypeRtf', 'Rich Text (.rtf)');
define('DialogAttachTypeTxt', 'Plain Text (.txt)');
define('DialogAttachTypeDoc', 'MS Word (.doc)');
define('DialogAttachButton', 'Anexar');
define('DialogAttachResume', 'Anexar a resume');
define('DialogAttachLetter', 'Anexar a cover letter');
define('DialogAttachAnother', 'Anexar outro arquivo');
define('DialogAttachAddToBody', 'Adicionar versão em texto simples no corpo de e-mail (recomendado)');
define('DialogAttachTypeNo', 'Nenhum anexo');
define('DialogAttachSelectLetter', 'Select cover letter');
define('DialogAttachTypePdfRecom', 'Adobe PDF (.pdf) (Recomendado)');
define('DialogAttachTypeTextInBody', 'Texto simples no corpo do email - recomendado');
define('DialogAttachTypeTxtAttach', 'Plain Text (.txt) anexo');
define('CustomTitle', 'Redirecionamento');
define('ForwardingNotificationsTo', 'Enviar notificações por e-mail para <b>%email</b>');
define('ForwardingForwardTo', 'Encaminhar e-mail para <b>%email</b>');
define('ForwardingNothing', 'Sem notificações por e-mail ou encaminhamento');
define('ForwardingChange', 'mudar');

define('ConfirmSaveForward', 'As configurações para encaminhamento não foram salvas. Clique em OK para salvar.');
define('ConfirmSaveAutoresponder', 'As configurações da autoresposta não foram salvas. Clique em OK para salvar.');

define('DigDosMenuItem', 'DigDos');
define('DigDosTitle', 'Selecione um objeto');

define('LastLoginTitle', 'Último login');
define('ExportContacts', 'Exportar Contatos');

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
