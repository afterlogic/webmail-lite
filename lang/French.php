<?php
define('PROC_ERROR_ACCT_CREATE', 'Il y a eu une erreur à la création du compte');
define('PROC_WRONG_ACCT_PWD', 'Mauvais mot de passe');
define('PROC_CANT_LOG_NONDEF', 'Impossible de se connecter avec un autre compte que celui par défaut.');
define('PROC_CANT_INS_NEW_FILTER', 'Impossible d\'ajouter un filtre');
define('PROC_FOLDER_EXIST', 'Le dossier existe déjà');
define('PROC_CANT_CREATE_FLD', 'Impossible de créer le dossier');
define('PROC_CANT_INS_NEW_GROUP', 'Impossible d\'insérer de nouveaux groupes');
define('PROC_CANT_INS_NEW_CONT', 'Impossible d\'ajouter un nouveau contact');
define('PROC_CANT_INS_NEW_CONTS', 'Impossible d\'ajouter un(des) nouveau(x) contact(s)');
define('PROC_CANT_ADD_NEW_CONT_TO_GRP', 'Impossible d\'ajouter un(des) nouveau(x) contact(s) au groupe');
define('PROC_ERROR_ACCT_UPDATE', 'Il y a eu une erreur à la mise à jour du compte');
define('PROC_CANT_UPDATE_CONT_SETTINGS', 'Impossible de mettre à jour les paramètres');
define('PROC_CANT_GET_SETTINGS', 'Impossible d\'obtenir les paramètres');
define('PROC_CANT_UPDATE_ACCT', 'Impossible de mettre à jour le compte');
define('PROC_ERROR_DEL_FLD', 'Il y a eu une erreur lors de la suppression du dossier');
define('PROC_CANT_UPDATE_CONT', 'Impossible de mettre à jour le contact');
define('PROC_CANT_GET_FLDS', 'Impossible d\'obtenir l\'arborescence des dossiers');
define('PROC_CANT_GET_MSG_LIST', 'Impossible d\'obtenir la liste des dossiers');
define('PROC_MSG_HAS_DELETED', 'The message a été supprimé du serveur de mail');
define('PROC_CANT_LOAD_CONT_SETTINGS', 'Impossible de charger les paramètres du contact');
define('PROC_CANT_LOAD_SIGNATURE', 'Impossible de charger la signature du compte');
define('PROC_CANT_GET_CONT_FROM_DB', 'Impossible de charger le contact depuis la Base de Données');
define('PROC_CANT_GET_CONTS_FROM_DB', 'Impossible de charger le(s) contact(s) depuis la Base de Données');
define('PROC_CANT_DEL_ACCT_BY_ID', 'Impossible d\'effacer le compte par son numéro');
define('PROC_CANT_DEL_FILTER_BY_ID', 'Impossible d\'effacer le filtre par son numéro');
define('PROC_CANT_DEL_CONT_GROUPS', 'Impossible d\'effacer le(s) contact(s) et/ou le(s) group(s)');
define('PROC_WRONG_ACCT_ACCESS', 'Une tentative non autorisée d\'accès à un autre compte  utilisateur a été détecté');
define('PROC_SESSION_ERROR', 'La précédente session a été terminée ŕ cause d\'un délai dépassé.');

define('MailBoxIsFull', 'La Boite mail est pleine');
define('WebMailException', 'Une exception WEBMAIL est survenue');
define('InvalidUid', 'Invalid Message UID');
define('CantCreateContactGroup', 'Impossible de créer le groupe de contacts');
define('CantCreateUser', 'Impossible de créer l\'utilisateur');
define('CantCreateAccount', 'Impossible de créer le compte');
define('SessionIsEmpty', 'La session est vide');
define('FileIsTooBig', 'Le fichier est trop gros');

define('PROC_CANT_MARK_ALL_MSG_READ', 'Impossible de marquer les messages comme lus');
define('PROC_CANT_MARK_ALL_MSG_UNREAD', 'Impossible de marquer les messages comme non-lus');
define('PROC_CANT_PURGE_MSGS', 'Impossible de nettoyer les message(s)');
define('PROC_CANT_DEL_MSGS', 'Impossible d\'effacer message(s)');
define('PROC_CANT_UNDEL_MSGS', 'Impossible de reprendre le(s) message(s) effacé(s)');
define('PROC_CANT_MARK_MSGS_READ', 'Impossible de marquer les messages comme lus');
define('PROC_CANT_MARK_MSGS_UNREAD', 'Impossible de marquer les messages comme non-lus');
define('PROC_CANT_SET_MSG_FLAGS', 'Impossible d\'obtenir le statut des messages');
define('PROC_CANT_REMOVE_MSG_FLAGS', 'Impossible de supprimer le statut du ou des message(s)');
define('PROC_CANT_CHANGE_MSG_FLD', 'Impossible de changer le dossier du ou des message(s)');
define('PROC_CANT_SEND_MSG', 'Impossible d\'envoyer le message.');
define('PROC_CANT_SAVE_MSG', 'Impossible de sauvegarder le message.');
define('PROC_CANT_GET_ACCT_LIST', 'Impossible d\'obtenir la liste des comptes');
define('PROC_CANT_GET_FILTER_LIST', 'Impossible d\'obtenir la liste des filtres');

define('PROC_CANT_LEAVE_BLANK', 'Vous ne pouvez pas laisser le champ * vide');

define('PROC_CANT_UPD_FLD', 'Impossible de mettre ŕ jour le dossier');
define('PROC_CANT_UPD_FILTER', 'Impossible de mettre ŕ jour le filtre');

define('ACCT_CANT_ADD_DEF_ACCT', 'Impossible de rajouter ce compte car il est déjŕ utilize comme compte par défaut par un autre utilisateur.');
define('ACCT_CANT_UPD_TO_DEF_ACCT', 'Ce statut de ce compte ne peut pas ętre changé comme compte par défaut.');
define('ACCT_CANT_CREATE_IMAP_ACCT', 'Impossible de créer ce nouveau compte (IMAP4 erreur de connexion)');
define('ACCT_CANT_DEL_LAST_DEF_ACCT', 'Impossible d\'effacer le dernier compte par défaut ');

define('LANG_LoginInfo', 'Information de l\'identifiant');
define('LANG_Email', 'Email');
define('LANG_Login', 'Identifiant');
define('LANG_Password', 'Mot de passe');
define('LANG_IncServer', 'Mail entrant');
define('LANG_PopProtocol', 'POP3');
define('LANG_ImapProtocol', 'IMAP4');
define('LANG_IncPort', 'Port');
define('LANG_OutServer', 'SMTP Server');
define('LANG_OutPort', 'Port');
define('LANG_UseSmtpAuth', 'Utiliser l\'authentification SMTP');
define('LANG_SignMe', 'Connectez moi automatiquement');
define('LANG_Enter', 'Entrer');

// interface strings

define('JS_LANG_TitleLogin', 'Identifiant');
define('JS_LANG_TitleMessagesListView', 'Liste des Messages');
define('JS_LANG_TitleMessagesList', 'Liste des Messages');
define('JS_LANG_TitleViewMessage', 'Voir un Message');
define('JS_LANG_TitleNewMessage', 'Nouveau Message');
define('JS_LANG_TitleSettings', 'Paramètres');
define('JS_LANG_TitleContacts', 'Contacts');

define('JS_LANG_StandardLogin', 'Identification&nbsp;Standard');
define('JS_LANG_AdvancedLogin', 'Identification&nbsp;avancée');

define('JS_LANG_InfoWebMailLoading', 'Veuillez patienter pendant le chargement de WEBMAIL &hellip;');
define('JS_LANG_Loading', 'Chargement &hellip;');
define('JS_LANG_InfoMessagesLoad', 'Veuillez patienter pendant que WEBMAIL récupère la liste des messages');
define('JS_LANG_InfoEmptyFolder', 'Le dossier est vide.');
define('JS_LANG_InfoPageLoading', 'La page est toujours en train de charger &hellip;');
define('JS_LANG_InfoSendMessage', 'Le message a été envoyé');
define('JS_LANG_InfoSaveMessage', 'Le message a été enregistré');
define('JS_LANG_InfoHaveImported', 'Vous avez importés');
define('JS_LANG_InfoNewContacts', 'nouveau(x) contact(s) dans votre liste de contacts.');
define('JS_LANG_InfoToDelete', 'à effacer');
define('JS_LANG_InfoDeleteContent', 'dossier, vous devriez effacer son contenu d\'abord.');
define('JS_LANG_InfoDeleteNotEmptyFolders', 'Effacer des dossiers non vides est impossible. Merci de vider d\'abord le contenu des dossiers cochés avant de les supprimer.');
define('JS_LANG_InfoRequiredFields', '* champs requis');

define('JS_LANG_ConfirmAreYouSure', 'Etes vous sûr ?');
define('JS_LANG_ConfirmDirectModeAreYouSure', 'Le(s) message(s) sélectionnés vont être définitivement effacés ! Etes vous sûr ?');
define('JS_LANG_ConfirmSaveSettings', 'Les paramètres n\'ont pas été enregistrés. Appuyez sur OK pour les sauvegarder.');
define('JS_LANG_ConfirmSaveContactsSettings', 'Les paramètres du contact n\'ont pas été enregistrés. Appuyez sur OK pour les sauvegarder.');
define('JS_LANG_ConfirmSaveAcctProp', 'Les propriétés du compte n\'ont pas été enregistrées. Appuyez sur OK pour les sauvegarder.');
define('JS_LANG_ConfirmSaveFilter', 'Les propriétés des filtres n\'ont pas été enregistrés. Appuyez sur OK pour les sauvegarder.');
define('JS_LANG_ConfirmSaveSignature', 'La signature n\'a pas été enregistré. Appuyez sur OK pour la sauvegarder.');
define('JS_LANG_ConfirmSavefolders', 'Le dossier n\'a pas été enregistré. Appuyez sur OK pour le sauvegarder.');
define('JS_LANG_ConfirmHtmlToPlain', 'Attention : Le fait de changer le formatage de ce message d\'HTML en texte simple, vous perdrez toute mise en page. Appuyez sur OK pour continuer.');
define('JS_LANG_ConfirmAddFolder', 'Avant d\'ajouter ce dossier, il est nécessaire de valider les changements. Appuyez sur OK pour sauvegarder.');
define('JS_LANG_ConfirmEmptySubject', 'Le sujet est vide. Voulez-vous continuer ?');

define('JS_LANG_WarningEmailBlank', 'Vous ne pouvez pas laisser le champ<br />Email: champ vide');
define('JS_LANG_WarningLoginBlank', 'Vous ne pouvez pas laisser le champ<br />Identifiant: champ vide');
define('JS_LANG_WarningToBlank', 'Vous ne pouvez pas laisser le champ: zone vide');
define('JS_LANG_WarningServerPortBlank', 'Vous ne pouvez pas laisser le champ POP3 et<br />SMTP server/port champ vides.');
define('JS_LANG_WarningEmptySearchLine', 'Ligne de recherche vide. Veuillez taper la partie de texte que vous souhaitez rechercher.');
define('JS_LANG_WarningMarkListItem', 'Merci de choisir au moins un élément dans la liste.');
define('JS_LANG_WarningFolderMove', 'Le dossier ne peut être déplacé car ici c\'est un autre niveau');
define('JS_LANG_WarningContactNotComplete', 'Merci de saisir votre email ou votre nom');
define('JS_LANG_WarningGroupNotComplete', 'Merci de saisir le nom du group');

define('JS_LANG_WarningEmailFieldBlank', 'Vous ne pouvez pas laisser le champ Email vide');
define('JS_LANG_WarningIncServerBlank', 'Vous ne pouvez pas laisser le champ Serveur POP3(IMAP4) vide');
define('JS_LANG_WarningIncPortBlank', 'Vous ne pouvez pas laisser le champ port du serveur POP3(IMAP4) vide');
define('JS_LANG_WarningIncLoginBlank', 'Vous ne pouvez pas laisser le champ Identifiant POP3(IMAP4) vide');
define('JS_LANG_WarningIncPortNumber', 'Vous devez spécifier une valeur positive pour le port POP3(IMAP4)');
define('JS_LANG_DefaultIncPortNumber', 'Le numéro de port par défaut pour POP3(IMAP4) est 110(143).');
define('JS_LANG_WarningIncPassBlank', 'Vous ne pouvez pas laisser le champ mot de passe POP3(IMAP4) vide');
define('JS_LANG_WarningOutPortBlank', 'Vous ne pouvez pas laisser le champ port du serveur SMTP vide');
define('JS_LANG_WarningOutPortNumber', 'Vous devez spécifier une valeur positive pour le port SMTP.');
define('JS_LANG_WarningCorrectEmail', 'Vous devez spécifier l\'adresse email correctement.');
define('JS_LANG_DefaultOutPortNumber', 'Le numéro du port SMTP par défaut est 25.');

define('JS_LANG_WarningCsvExtention', 'L\'extension doit être de la forme .csv');
define('JS_LANG_WarningImportFileType', 'Veuillez choisir le programme depuis lequel vous souhaitez copier vos contacts');
define('JS_LANG_WarningEmptyImportFile', 'Merci de sélection un fichier en appuyant sur le bouton parcourir');

define('JS_LANG_WarningContactsPerPage', 'Le nombre de contacts par page doit être un nombre positif');
define('JS_LANG_WarningMessagesPerPage', 'Le nombre de messages par page doit être un nombre positif');
define('JS_LANG_WarningMailsOnServerDays', 'Vous devez spécifier un nombre positif pour le champ du nombre de jours sur le serveur');
define('JS_LANG_WarningEmptyFilter', 'Veuillez entrer une sous-chaîne ');
define('JS_LANG_WarningEmptyFolderName', 'Veuillez saisir le nom du dossier');

define('JS_LANG_ErrorConnectionFailed', 'Impossible de se connecter');
define('JS_LANG_ErrorRequestFailed', 'Le transfert des données ne s\'est pas terminé');
define('JS_LANG_ErrorAbsentXMLHttpRequest', 'L\'objet XMLHttpRequest n\'est pas présent.');
define('JS_LANG_ErrorWithoutDesc', 'Une erreur inconnue et sans description est survenue');
define('JS_LANG_ErrorParsing', 'Une erreur, lors de l\'analyse du fichier XML est survenue.');
define('JS_LANG_ResponseText', 'Texte de réponse :');
define('JS_LANG_ErrorEmptyXmlPacket', 'Les paquets de données XML sont vides');
define('JS_LANG_ErrorImportContacts', 'Erreur pendant l\'import des contacts');
define('JS_LANG_ErrorNoContacts', 'Aucun contact à importer');
define('JS_LANG_ErrorCheckMail', 'La réception des messages s\'est achevée avec une erreur. Apparemment, tous les messages n\'ont pas été reçus.');

define('JS_LANG_LoggingToServer', 'Connexion au serveur &hellip;');
define('JS_LANG_GettingMsgsNum', 'Réception du nombre de messages');
define('JS_LANG_RetrievingMessage', 'Réception du message');
define('JS_LANG_DeletingMessage', 'Effacement du message');
define('JS_LANG_DeletingMessages', 'Effacement du (des) message(s)');
define('JS_LANG_Of', 'de');
define('JS_LANG_Connection', 'Connexion');
define('JS_LANG_Charset', 'Caractère');
define('JS_LANG_AutoSelect', 'sélection automatique');

define('JS_LANG_Contacts', 'Contacts');
define('JS_LANG_ClassicVersion', 'Version Classique');
define('JS_LANG_Logout', 'Déconnexion');
define('JS_LANG_Settings', 'Paramètres');

define('JS_LANG_LookFor', 'Chercher : ');
define('JS_LANG_SearchIn', 'Chercher dans : ');
define('JS_LANG_QuickSearch', 'Chercher dans De , A et dans le sujet du email seulement (plus rapide).');
define('JS_LANG_SlowSearch', 'Chercher dans tout le message');
define('JS_LANG_AllMailFolders', 'Tous les dossiers');
define('JS_LANG_AllGroups', 'Tous les groupes');

define('JS_LANG_NewMessage', 'Nouveau Message');
define('JS_LANG_CheckMail', 'Vérifier les emails');
define('JS_LANG_EmptyTrash', 'Vider la poubelle');
define('JS_LANG_MarkAsRead', 'Marquer comme lu');
define('JS_LANG_MarkAsUnread', 'Marquer comme non lu');
define('JS_LANG_MarkFlag', 'Marquer avec drapeau');
define('JS_LANG_MarkUnflag', 'Ne pas marquer avec un drapeau');
define('JS_LANG_MarkAllRead', 'Marquer comme lu');
define('JS_LANG_MarkAllUnread', 'Marquer comme non lu');
define('JS_LANG_Reply', 'Répondre');
define('JS_LANG_ReplyAll', 'Répondre à tous');
define('JS_LANG_Delete', 'Effacer');
define('JS_LANG_Undelete', 'Reprendre');
define('JS_LANG_PurgeDeleted', 'Purger les fichiers effacés');
define('JS_LANG_MoveToFolder', 'Déplacer dans le dossier');
define('JS_LANG_Forward', 'Transférer');

define('JS_LANG_HideFolders', 'Cacher les dossiers');
define('JS_LANG_ShowFolders', 'Montrer les dossiers');
define('JS_LANG_ManageFolders', 'Gérer les dossiers');
define('JS_LANG_SyncFolder', 'Synchroniser les dossiers');
define('JS_LANG_NewMessages', 'Nouveaux Messages');
define('JS_LANG_Messages', 'Message(s)');

define('JS_LANG_From', 'De');
define('JS_LANG_To', 'A');
define('JS_LANG_Date', 'Date');
define('JS_LANG_Size', 'Taille');
define('JS_LANG_Subject', 'Sujet');

define('JS_LANG_FirstPage', 'Première Page');
define('JS_LANG_PreviousPage', 'Page Précédente');
define('JS_LANG_NextPage', 'Page Suivante');
define('JS_LANG_LastPage', 'Dernière Page');

define('JS_LANG_SwitchToPlain', 'Basculer vers du texte simple ');
define('JS_LANG_SwitchToHTML', 'Basculer vers du texte HTML');
define('JS_LANG_AddToAddressBook', 'Rajouter à l\'annuaire');
define('JS_LANG_ClickToDownload', 'Cliquer pour télécharger ');
define('JS_LANG_View', 'Voir');
define('JS_LANG_ShowFullHeaders', 'Montrer l\'intégralité des entêtes du Email');
define('JS_LANG_HideFullHeaders', 'Masquer les entêtes du Email');

define('JS_LANG_MessagesInFolder', 'Messages dans le dossier');
define('JS_LANG_YouUsing', 'Vous utilisez');
define('JS_LANG_OfYour', 'de votre');
define('JS_LANG_Mb', 'MB');
define('JS_LANG_Kb', 'KB');
define('JS_LANG_B', 'B');

define('JS_LANG_SendMessage', 'Envoyer');
define('JS_LANG_SaveMessage', 'Enregistrer');
define('JS_LANG_Print', 'Imprimer');
define('JS_LANG_PreviousMsg', 'Message Précédent');
define('JS_LANG_NextMsg', 'Message Suivant');
define('JS_LANG_AddressBook', 'Annuaire');
define('JS_LANG_ShowBCC', 'Montrer BCC');
define('JS_LANG_HideBCC', 'Cacher BCC');
define('JS_LANG_CC', 'CC');
define('JS_LANG_BCC', 'BCC');
define('JS_LANG_ReplyTo', 'Répondre&nbsp;à');
define('JS_LANG_AttachFile', 'Attacher une pièce jointe');
define('JS_LANG_Attach', 'Attacher');
define('JS_LANG_Re', 'Re');
define('JS_LANG_OriginalMessage', 'Message Original');
define('JS_LANG_Sent', 'Envoyer');
define('JS_LANG_Fwd', 'Transférer');
define('JS_LANG_Low', 'Basse');
define('JS_LANG_Normal', 'Normal');
define('JS_LANG_High', 'Haute');
define('JS_LANG_Importance', 'Importance');
define('JS_LANG_Close', 'Fermer');

define('JS_LANG_Common', 'Commun');
define('JS_LANG_EmailAccounts', 'Comptes mails');

define('JS_LANG_MsgsPerPage', 'Messages par page');
define('JS_LANG_DisableRTE', 'Désactiver l\'édition de texte avancée (Rich-text)');
define('JS_LANG_Skin', 'Thème');
define('JS_LANG_DefCharset', 'Caractère par défaut');
define('JS_LANG_DefCharsetInc', 'Réception de caractères par défaut ');
define('JS_LANG_DefCharsetOut', 'Envoie de caractères par défaut');
define('JS_LANG_DefTimeOffset', 'Temps par défaut');
define('JS_LANG_DefLanguage', 'Langue par défaut');
define('JS_LANG_DefDateFormat', 'Format de la date par défaut');
define('JS_LANG_ShowViewPane', 'Liste des messages dans la fenêtre de prévisualisation');
define('JS_LANG_Save', 'Enregistrer');
define('JS_LANG_Cancel', 'Annuler');
define('JS_LANG_OK', 'OK');

define('JS_LANG_Remove', 'Supprimer');
define('JS_LANG_AddNewAccount', 'Rajouter un compte');
define('JS_LANG_Signature', 'Signature');
define('JS_LANG_Filters', 'Filtres');
define('JS_LANG_Properties', 'Paramètres');
define('JS_LANG_UseForLogin', 'Utilisez les Paramètres de ce compte (identifiant et mot de passe) pour vous connecter');
define('JS_LANG_MailFriendlyName', 'Votre nom');
define('JS_LANG_MailEmail', 'Email');
define('JS_LANG_MailIncHost', 'Serveur de Mail entrant');
define('JS_LANG_Imap4', 'IMAP4');
define('JS_LANG_Pop3', 'POP3');
define('JS_LANG_MailIncPort', 'Port');
define('JS_LANG_MailIncLogin', 'Identifiant');
define('JS_LANG_MailIncPass', 'Mot de passe');
define('JS_LANG_MailOutHost', 'Serveur SMTP ');
define('JS_LANG_MailOutPort', 'Port');
define('JS_LANG_MailOutLogin', 'Identifiant SMTP');
define('JS_LANG_MailOutPass', 'Mot de passe SMTP');
define('JS_LANG_MailOutAuth1', 'Utiliser l\'authentification SMTP');
define('JS_LANG_MailOutAuth2', '(Vous pouvez laisser à vide les identifiants et mots de passe si ce sont les mêmes que les identifiants / Mots de passe POP3/IMAP4)');
define('JS_LANG_UseFriendlyNm1', 'Utiliser un nom étendu à la place du email dans le champ "De:"');
define('JS_LANG_UseFriendlyNm2', '(Votre nom &lt;sender@mail.com&gt;)');
define('JS_LANG_GetmailAtLogin', 'Récupérer / Synchroniser les emails à la connexion');
define('JS_LANG_MailMode0', 'Effacer les messages reçus du serveur');
define('JS_LANG_MailMode1', 'Laisser les messages sur le serveur');
define('JS_LANG_MailMode2', 'Laisser les messages sur le serveur ');
define('JS_LANG_MailsOnServerDays', 'jour(s)');
define('JS_LANG_MailMode3', 'Effacer les messages du serveur quand vous videz la poubelle');
define('JS_LANG_InboxSyncType', 'Type de Synchronisation');

define('JS_LANG_SyncTypeNo', 'ne pas synchroniser');
define('JS_LANG_SyncTypeNewHeaders', 'Nouvel Entête');
define('JS_LANG_SyncTypeAllHeaders', 'Tous les Entêtes');
define('JS_LANG_SyncTypeNewMessages', 'Nouveaux Messages');
define('JS_LANG_SyncTypeAllMessages', 'Tous les Messages');
define('JS_LANG_SyncTypeDirectMode', 'Mode direct');

define('JS_LANG_Pop3SyncTypeEntireHeaders', 'Tous les Entêtes');
define('JS_LANG_Pop3SyncTypeEntireMessages', 'Tous les Messages');
define('JS_LANG_Pop3SyncTypeDirectMode', 'Mode direct');

define('JS_LANG_DeleteFromDb', 'Effacer les messages de la Base de Données lorsqu\'ils n\'existent plus sur le serveur de Mail');

define('JS_LANG_EditFilter', 'Modifier un filtre');
define('JS_LANG_NewFilter', 'Ajouter un filtre');
define('JS_LANG_Field', 'zone');
define('JS_LANG_Condition', 'Condition');
define('JS_LANG_ContainSubstring', 'Contient une partie de la sous-chaine de caractères');
define('JS_LANG_ContainExactPhrase', 'Contient exactement la sous-chaine de caractères');
define('JS_LANG_NotContainSubstring', 'Ne contient pas de sous-chaine de caractères');
define('JS_LANG_FilterDesc_At', 'ŕ');
define('JS_LANG_FilterDesc_Field', 'champ');
define('JS_LANG_Action', 'Action');
define('JS_LANG_DoNothing', 'Ne rien faire');
define('JS_LANG_DeleteFromServer', 'Effacer immédiatement du Serveur');
define('JS_LANG_MarkGrey', 'Marquer comme gris');
define('JS_LANG_Add', 'Ajouter');
define('JS_LANG_OtherFilterSettings', 'Autres paramètres du filtre');
define('JS_LANG_ConsiderXSpam', 'Considérer comme un entête X-Spam');
define('JS_LANG_Apply', 'Appliquer');

define('JS_LANG_InsertLink', 'Insérer un lien');
define('JS_LANG_RemoveLink', 'Supprimer un lien');
define('JS_LANG_Numbering', 'Numérotation');
define('JS_LANG_Bullets', 'Signets');
define('JS_LANG_HorizontalLine', 'Ligne Horizontale');
define('JS_LANG_Bold', 'Gras');
define('JS_LANG_Italic', 'Italique');
define('JS_LANG_Underline', 'Souligner');
define('JS_LANG_AlignLeft', 'Aligner à gauche');
define('JS_LANG_Center', 'Centrer');
define('JS_LANG_AlignRight', 'Aligner à gauche');
define('JS_LANG_Justify', 'Justifier');
define('JS_LANG_FontColor', 'Couleur de la police de caractères');
define('JS_LANG_Background', 'Fond');
define('JS_LANG_SwitchToPlainMode', 'Basculer vers du texte brut');
define('JS_LANG_SwitchToHTMLMode', 'Basculer vers du texte HTML');

define('JS_LANG_Folder', 'Dossier');
define('JS_LANG_Msgs', 'Mes\'s,');
define('JS_LANG_Synchronize', 'Synchroniser');
define('JS_LANG_ShowThisFolder', 'Montrer ce dossier');
define('JS_LANG_Total', 'Total');
define('JS_LANG_DeleteSelected', 'Effacer les sélectionnés');
define('JS_LANG_AddNewFolder', 'Rajouter un dossier');
define('JS_LANG_NewFolder', 'Nouveau dossier');
define('JS_LANG_ParentFolder', 'Dossier parent');
define('JS_LANG_NoParent', 'Pas de dossier Parent');
define('JS_LANG_FolderName', 'Nom du dossier');

define('JS_LANG_ContactsPerPage', 'Contacts par page');
define('JS_LANG_WhiteList', 'Annuaire comme liste blanche');

define('JS_LANG_CharsetDefault', 'défaut');
define('JS_LANG_CharsetArabicAlphabetISO', 'Alphabet arabe(ISO)');
define('JS_LANG_CharsetArabicAlphabet', 'Alphabet arabe (Windows)');
define('JS_LANG_CharsetBalticAlphabetISO', 'Alphabet baltique (ISO)');
define('JS_LANG_CharsetBalticAlphabet', 'Alphabet baltique (Windows)');
define('JS_LANG_CharsetCentralEuropeanAlphabetISO', 'Alphabet de l\'Europe centrale (ISO)');
define('JS_LANG_CharsetCentralEuropeanAlphabet', 'Alphabet de l\'Europe centrale (Windows)');
define('JS_LANG_CharsetChineseSimplifiedEUC', 'Chinese Simplified (EUC)');
define('JS_LANG_CharsetChineseSimplifiedGB', 'Chinese Simplified (GB2312)');
define('JS_LANG_CharsetChineseTraditional', 'Chinois Traditionnel (Big5)');
define('JS_LANG_CharsetCyrillicAlphabetISO', 'Alphabet cyrillique (ISO)');
define('JS_LANG_CharsetCyrillicAlphabetKOI8R', 'Alphabet cyrillique (KOI8-R)');
define('JS_LANG_CharsetCyrillicAlphabet', 'Alphabet cyrillique (Windows)');
define('JS_LANG_CharsetGreekAlphabetISO', 'Alphabet Grec (ISO)');
define('JS_LANG_CharsetGreekAlphabet', 'Alphabet Grec (Windows)');
define('JS_LANG_CharsetHebrewAlphabetISO', 'Alphabet hébreu (ISO)');
define('JS_LANG_CharsetHebrewAlphabet', 'Alphabet hébreu (Windows)');
define('JS_LANG_CharsetJapanese', 'Japonais');
define('JS_LANG_CharsetJapaneseShiftJIS', 'Japonais (Shift-JIS)');
define('JS_LANG_CharsetKoreanEUC', 'Coréen  (EUC)');
define('JS_LANG_CharsetKoreanISO', 'Coréen  (ISO)');
define('JS_LANG_CharsetLatin3AlphabetISO', 'Alphabet Latin 3(ISO)');
define('JS_LANG_CharsetTurkishAlphabet', 'Alphabet Turc');
define('JS_LANG_CharsetUniversalAlphabetUTF7', 'Alphabet Universel (UTF-7)');
define('JS_LANG_CharsetUniversalAlphabetUTF8', 'Alphabet Universel (UTF-8)');
define('JS_LANG_CharsetVietnameseAlphabet', 'Alphabet vietnamien (Windows)');
define('JS_LANG_CharsetWesternAlphabetISO', 'Alphabet Occidental (ISO)');
define('JS_LANG_CharsetWesternAlphabet', 'Alphabet Occidental (Windows)');

define('JS_LANG_TimeDefault', 'défaut');
define('JS_LANG_TimeEniwetok', 'Eiwetok, Kwajalein, ligne de changement de date Temps');
define('JS_LANG_TimeMidwayIsland', 'Midway Island, Samoa');
define('JS_LANG_TimeHawaii', 'Hawaii');
define('JS_LANG_TimeAlaska', 'Alaska');
define('JS_LANG_TimePacific', 'Temps Pacifique (les USA et le Canada) ; Tijuana');
define('JS_LANG_TimeArizona', 'Arizona');
define('JS_LANG_TimeMountain', 'Mountain Time (US & Canada)');
define('JS_LANG_TimeCentralAmerica', 'L\'Amérique Centrale');
define('JS_LANG_TimeCentral', 'Temps central (les USA et le Canada)');
define('JS_LANG_TimeMexicoCity', 'Mexico, Tegucigalpa');
define('JS_LANG_TimeSaskatchewan', 'Saskatchewan');
define('JS_LANG_TimeIndiana', 'L\'Indiana (Est)');
define('JS_LANG_TimeEastern', 'Temps oriental (les USA et le Canada)');
define('JS_LANG_TimeBogota', 'Bogota, Lima, Quito');
define('JS_LANG_TimeSantiago', 'Santiago');
define('JS_LANG_TimeCaracas', 'Caracas, La Paz');
define('JS_LANG_TimeAtlanticCanada', 'Temps atlantique (Canada)');
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

define('JS_LANG_DateDefault', 'défaut');
define('JS_LANG_DateDDMMYY', 'DD/MM/YY');
define('JS_LANG_DateMMDDYY', 'MM/DD/YY');
define('JS_LANG_DateDDMonth', 'DD Month (01 Janv)');
define('JS_LANG_DateAdvanced', 'Avancé');

define('JS_LANG_NewContact', 'Nouveau Contact');
define('JS_LANG_NewGroup', 'Nouveau Group');
define('JS_LANG_AddContactsTo', 'Rajouter des contacts au');
define('JS_LANG_ImportContacts', 'Importer des Contacts');

define('JS_LANG_Name', 'Nom');
define('JS_LANG_Email', 'Email');
define('JS_LANG_DefaultEmail', 'Email par défaut');
define('JS_LANG_NotSpecifiedYet', 'non spécifié pour le moment');
define('JS_LANG_ContactName', 'Nom');
define('JS_LANG_Birthday', 'Anniversaire');
define('JS_LANG_Month', 'Mois');
define('JS_LANG_January', 'Janvier');
define('JS_LANG_February', 'Février');
define('JS_LANG_March', 'Mars');
define('JS_LANG_April', 'Avril');
define('JS_LANG_May', 'Mai');
define('JS_LANG_June', 'Juin');
define('JS_LANG_July', 'Juillet');
define('JS_LANG_August', 'Aout');
define('JS_LANG_September', 'Septembre');
define('JS_LANG_October', 'Octobre');
define('JS_LANG_November', 'Novembre');
define('JS_LANG_December', 'Decembre');
define('JS_LANG_Day', 'Jour');
define('JS_LANG_Year', 'Année');
define('JS_LANG_UseFriendlyName1', 'Utiliser un nom étendu à la place du email dans le champ "De:"');
define('JS_LANG_UseFriendlyName2', '(Votre nom &lt;sender@mail.com&gt;)');
define('JS_LANG_Personal', 'Personnel');
define('JS_LANG_PersonalEmail', 'E-mail Personnel');
define('JS_LANG_StreetAddress', 'Addresse');
define('JS_LANG_City', 'Ville');
define('JS_LANG_Fax', 'Fax');
define('JS_LANG_StateProvince', 'Etat');
define('JS_LANG_Phone', 'Téléphone');
define('JS_LANG_ZipCode', 'Code Postal');
define('JS_LANG_Mobile', 'Portable');
define('JS_LANG_CountryRegion', 'Pays/Région');
define('JS_LANG_WebPage', 'Page Web');
define('JS_LANG_Go', 'Go');
define('JS_LANG_Home', 'Début');
define('JS_LANG_Business', 'Travail');
define('JS_LANG_BusinessEmail', 'E-mail du travail');
define('JS_LANG_Company', 'Entreprise');
define('JS_LANG_JobTitle', 'Poste');
define('JS_LANG_Department', 'Départment');
define('JS_LANG_Office', 'Bureau');
define('JS_LANG_Pager', 'Pager');
define('JS_LANG_Other', 'Autre');
define('JS_LANG_OtherEmail', 'Autre E-mail');
define('JS_LANG_Notes', 'Notes');
define('JS_LANG_Groups', 'Groupes');
define('JS_LANG_ShowAddFields', 'Montrer les champs supplémentaires');
define('JS_LANG_HideAddFields', 'Masquer les champs supplémentaires');
define('JS_LANG_EditContact', 'Modifier les informations du contact');
define('JS_LANG_GroupName', 'Nom du groupe');
define('JS_LANG_AddContacts', 'Rajouter des contacts');
define('JS_LANG_CommentAddContacts', '(Si vous allez indiquer plus d\'une adresse, merci de les séparer par des virgules)');
define('JS_LANG_CreateGroup', 'Créer un Groupe');
define('JS_LANG_Rename', 'Renommer');
define('JS_LANG_MailGroup', 'Groupe Mail');
define('JS_LANG_RemoveFromGroup', 'Supprimer du Groupe');
define('JS_LANG_UseImportTo', 'Utilisez l\'importation pour copier vos contacts de Microsoft Outlook, Microsoft Outlook Express vers vos contacts WebMail');
define('JS_LANG_Outlook1', 'Microsoft Outlook 2000/XP/2003');
define('JS_LANG_Outlook2', 'Microsoft Outlook Express 6');
define('JS_LANG_SelectImportFile', 'Sélectionnez le fichier (au format .CSV) que vous souhaitez importer');
define('JS_LANG_Import', 'Importer');
define('JS_LANG_ContactsMessage', 'Ceci est la page de contacts !');
define('JS_LANG_ContactsCount', 'contact(s)');
define('JS_LANG_GroupsCount', 'groupe(s)');

// webmail 4.1 constants
define('PicturesBlocked', 'Des images de ce message ont été bloquées pour votre sécurité.');
define('ShowPictures', 'Afficher les images');
define('ShowPicturesFromSender', 'Toujours afficher les images des messages de cet émetteur');
define('AlwaysShowPictures', 'Toujours afficher les images contenues dans les messages');

define('TreatAsOrganization', 'Traiter comme une organisation');

define('WarningGroupAlreadyExist', 'Un group avec le même nom existe déjà. Veuillez choisir un autre nom.');
define('WarningCorrectFolderName', 'Vous devriez spécifier un nom dossier correct.');
define('WarningLoginFieldBlank', 'Vous ne pouvez pas laisser le champ Login (nom d\'utilisateur) vide.');
define('WarningCorrectLogin', 'Vous devriez spécifier un nom d\'utilisateur (Login) correct.');
define('WarningPassBlank', 'Vous ne pouvez pas laissez le champ Mot de Passe vide.');
define('WarningCorrectIncServer', 'Vous devriez spécifier une adresse de serveur POP3(IMAP) correcte.');
define('WarningCorrectSMTPServer', 'Vous devriez spécifier une adresse de serveur SMTP correcte.');
define('WarningFromBlank', 'Vous ne pouvez pas laisser le champ DE vide.');
define('WarningAdvancedDateFormat', 'Merci de spécifier un format de date.');

define('AdvancedDateHelpTitle', 'Date avancée');
define('AdvancedDateHelpIntro', 'Qaund le champ &quot;Avancé&quot; est selectionné, vous pouvez utiliser la boite de texte pour définir votre propre format de date, qui sera affiché dans AfterLogic WebMail Pro. Les options suivantes sont utilisées pour cela avec \':\' ou \'/\' comme caractère de délimitation:');
define('AdvancedDateHelpConclusion', 'Par exemple, si vous spécifiez les valeurs &quot;mm/dd/yyyy&quot; dans le boite de texte &quot;Advancée&quot; , la date affichée sera mois/jour/année (i.e. 11/23/2005)');
define('AdvancedDateHelpDayOfMonth', 'Jour du mois (1 à 31)');
define('AdvancedDateHelpNumericMonth', 'Mois (1 à 12)');
define('AdvancedDateHelpTextualMonth', 'Mois (Jan à Dec)');
define('AdvancedDateHelpYear2', 'Année, 2 chiffres');
define('AdvancedDateHelpYear4', 'Année, 4 chiffres');
define('AdvancedDateHelpDayOfYear', 'Jour de l\'année (1 à 366)');
define('AdvancedDateHelpQuarter', 'Trimestre');
define('AdvancedDateHelpDayOfWeek', 'Jour de la semaine (Lun à Dim)');
define('AdvancedDateHelpWeekOfYear', 'Week of year (1 through 53)');

define('InfoNoMessagesFound', 'Aucun messages trouvés.');
define('ErrorSMTPConnect', 'Impossible de se connecter au serveur SMTP. Vérifiez les paramètres de votre serveur SMTP.');
define('ErrorSMTPAuth', 'Mauvais nom d\'utilisateur ou mot de passe. L\'authentification a échoué.');
define('ReportMessageSent', 'Votre message a été envoyé.');
define('ReportMessageSaved', 'Votre message a été enregistré.');
define('ErrorPOP3Connect', 'Impossible de se connecter au serveur POP3, vérifiez les paramètres du serveur POP3.');
define('ErrorIMAP4Connect', 'Impossible de se connecter au serveur IMAP4, vérifiez les paramètres du serveur IMAP4.');
define('ErrorPOP3IMAP4Auth', 'Mauvais email/nom d\'utilisateur ou mot de passe. l\'authentification a échoué.');
define('ErrorGetMailLimit', 'Désolé, votre boite mail dépasse la taille limite.');

define('ReportSettingsUpdatedSuccessfuly', 'Les paramètres ont été mis à jour avec succès.');
define('ReportAccountCreatedSuccessfuly', 'Le compte a été créé avec succès.');
define('ReportAccountUpdatedSuccessfuly', 'Le compte a été mis à jour avec succès.');

define('ConfirmDeleteAccount', 'Etes-vous sur de vouloir supprimer ce compte ?');

define('ReportFiltersUpdatedSuccessfuly', 'Les filtres ont été mis à jour avec succès.');
define('ReportSignatureUpdatedSuccessfuly', 'Signature has been updated successfully.');
define('ReportFoldersUpdatedSuccessfuly', 'Folders have been updated successfully.');
define('ReportContactsSettingsUpdatedSuccessfuly', 'Contacts\' settings have been updated successfully.');

define('ErrorInvalidCSV', 'le fichier CSV que vous avez sélectionné a un format invalide.');
//The group "guies" was successfully added.
define('ReportGroupSuccessfulyAdded1', 'Le groupe');
define('ReportGroupSuccessfulyAdded2', 'a été ajouté avec succès.');
define('ReportGroupUpdatedSuccessfuly', 'Le groupe a été mis à jour avec succès.');
define('ReportContactSuccessfulyAdded', 'Le contact a été ajouté avec succès.');
define('ReportContactUpdatedSuccessfuly', 'Le contact a été mis à jour avec succès.');
//Contact(s) was added to group "friends".
define('ReportContactAddedToGroup', 'Contact(s) ont été ajoutés au groupe');
define('AlertNoContactsGroupsSelected', 'Aucun contacts ou groupes sélectionnés.');

define('InfoListNotContainAddress', 'Si la liste ne contient pas l\'adresse que vous cherchez, essayez avec sa première lettre.');

define('DirectAccess', 'D');
define('DirectAccessTitle', 'Mode Direct. WebMail accède aux messages directement sur le serveur de mail.');

define('FolderInbox', 'Boite de réception');
define('FolderSentItems', 'Eléments envoyés');
define('FolderDrafts', 'Brouillon');
define('FolderTrash', 'Poubelle');

define('FileLargerAttachment', 'Le fichier attaché dépasse la taille maximum autorisée.');
define('FilePartiallyUploaded', 'Seulement une partie du fichier a été télécharger à cause d\'une erreur.');
define('NoFileUploaded', 'Aucun fichier n\'a été télécharger.');
define('MissingTempFolder', 'Le répertoire temporaire est manquant.');
define('MissingTempFile', 'Le fichier temporaire est manquant.');
define('UnknownUploadError', 'Une erreur inattendue est survenue lors du téléchargement du fichier.');
define('FileLargerThan', 'Erreur de téléchargement. Vraisemblablement, le fichier est plus grand que');
define('PROC_CANT_LOAD_DB', 'Impossible de se connecter à la base de données.');
define('PROC_CANT_LOAD_LANG', 'Impossible de trouver le fichier de langue nécessaire.');
define('PROC_CANT_LOAD_ACCT', 'Le compte n\'existe pas, peut-être a t\'il été effacé.');

define('DomainDosntExist', 'Ce nom de domaine n\'existe pas sur le serveur de mails.');
define('ServerIsDisable', 'L\'utilisation du serveur de mail par un administrateur est interdite.');

define('PROC_ACCOUNT_EXISTS', 'Le compte ne peut-être créé car il existe déjà.');
define('PROC_CANT_GET_MESSAGES_COUNT', 'Impossible d\'obtenir le nombre de messages du dossier.');
define('PROC_CANT_MAIL_SIZE', 'Impossible d\'obtenir la taille du message.');

define('Organization', 'Organisation');
define('WarningOutServerBlank', 'Vous ne pouvez pas laisser le champ Serveur SMTP vide');

//
define('JS_LANG_Refresh', 'Actualiser');
define('JS_LANG_MessagesInInbox', 'Message(s) dans la boite de réception');
define('JS_LANG_InfoEmptyInbox', 'La boite de réception est vide');

// webmail 4.2 constants
define('BackToList', 'Retour à la liste');
define('InfoNoContactsGroups', 'Pas de contacts ou de groupes.');
define('InfoNewContactsGroups', 'Vous pouvez soit créer des nouveaux contacts/groupes ou importer des contacts depuis un fichier .CSV au format MS Outlook.');
define('DefTimeFormat', 'Format heures par défaut');
define('SpellNoSuggestions', 'Pas de suggestions');
define('SpellWait', 'Merci de patienter&hellip;');

define('InfoNoMessageSelected', 'Aucun message sélectionné.');
define('InfoSingleDoubleClick', 'Vous pouvez soit cliquer une seule fois sur un message de la liste pour en voir l\'aperçu ici ou double cliquer pour le voir en affichage plein écran.');

// calendar
define('TitleDay', 'Affichage par jour');
define('TitleWeek', 'Affichage par semaine');
define('TitleMonth', 'Affichage par mois');

define('ErrorNotSupportBrowser', 'Le Calendrier AfterLogic ne supporte pas votre navigateur Internet. Merci d\'utiliser FireFox 2.0 ou supérieur, Opera 9.0 ou supérieur, Internet Explorer 6.0 ou supérieur, Safari 3.0.2 ou supérieur.');
define('ErrorTurnedOffActiveX', 'Le Support ActiveX est désactivé. <br/>Vous devez l\'activer pour pouvoir utiliser cette application.');

define('Calendar', 'Calendrier');

define('TabDay', 'Jour');
define('TabWeek', 'Semaine');
define('TabMonth', 'Mois');

define('ToolNewEvent', 'Nouvel&nbsp;évènement');
define('ToolBack', 'Retour');
define('ToolToday', 'Aujourd\'hui');
define('AltNewEvent', 'Nouvel évènement');
define('AltBack', 'Retour');
define('AltToday', 'Aujourd\'hui');
define('CalendarHeader', 'Calendrier');
define('CalendarsManager', 'Gestionnaire de Calendriers');

define('CalendarActionNew', 'Nouveau calendrier');
define('EventHeaderNew', 'Nouvel évènement');
define('CalendarHeaderNew', 'Nouveau calendrier');

define('EventSubject', 'Sujet');
define('EventCalendar', 'Calendrier');
define('EventFrom', 'De');
define('EventTill', 'jusqu\'à');
define('CalendarDescription', 'Description');
define('CalendarColor', 'Couleur');
define('CalendarName', 'Nom du Calendrier');
define('CalendarDefaultName', 'Mon Calendrier');

define('ButtonSave', 'Enregistrer');
define('ButtonCancel', 'Annuler');
define('ButtonDelete', 'Effacer');

define('AltPrevMonth', 'Mois précédent');
define('AltNextMonth', 'Mois suivant');

define('CalendarHeaderEdit', 'Modifier Calendrier');
define('CalendarActionEdit', 'Modifier Calendrier');
define('ConfirmDeleteCalendar', 'Etes-vous sûr de vouloir supprimer ce calendrier ?');
define('InfoDeleting', 'Effacement en cours&hellip;');
define('WarningCalendarNameBlank', 'Vous ne pouvez-vous pas laisser le nom du calendrier vide.');
define('ErrorCalendarNotCreated', 'Calendrier non créé.');
define('WarningSubjectBlank', 'Vous ne pouvez-vous pas laisser le sujet vide.');
define('WarningIncorrectTime', 'L\'heure spécifiée contient des caractères illégaux.');
define('WarningIncorrectFromTime', 'La valeur du champ \'De\' est incorrecte.');
define('WarningIncorrectTillTime', 'La valeur du champ \'jusqu\'à\' incorrecte.');
define('WarningStartEndDate', 'La valeur \'Heure de fin\' doit être supérieure ou égale à \'Heure de départ\'');
define('WarningStartEndTime', 'La valeur \'Heure de fin\' doit être supérieure à \'Heure de départ\'');
define('WarningIncorrectDate', 'Le Format de la date doit être correct.');
define('InfoLoading', 'Chargement&hellip;');
define('EventCreate', 'Créer un évènement');
define('CalendarHideOther', 'Masquer les autres calendriers');
define('CalendarShowOther', 'Afficher les autres calendriers');
define('CalendarRemove', 'Effacer le Calendrier');
define('EventHeaderEdit', 'Modifier un évènement');

define('InfoSaving', 'Enregistrement&hellip;');
define('SettingsDisplayName', 'Nom affiché');
define('SettingsTimeFormat', 'Format de l\'Heure');
define('SettingsDateFormat', 'Format de la Date');
define('SettingsShowWeekends', 'Afficher les Week-Ends');
define('SettingsWorkdayStarts', 'Début par jour ouvré');
define('SettingsWorkdayEnds', 'Fin');
define('SettingsShowWorkday', 'Afficher les journées de travail');
define('SettingsWeekStartsOn', 'Les Week-Ends commencent le ');
define('SettingsDefaultTab', 'Onglet par défaut');
define('SettingsCountry', 'Pays');
define('SettingsTimeZone', 'Fuseau horaire');
define('SettingsAllTimeZones', 'Tous les fuseaux horaires');

define('WarningWorkdayStartsEnds', 'L\'heure de fin des \'jours ouvrés\' doit être supérieure à l\'heure de départ des \'jours ouvrés\'');
define('ReportSettingsUpdated', 'Vos paramêtres ont été enregistrés correctement.');

define('SettingsTabCalendar', 'Calendrier');

define('FullMonthJanuary', 'Janvier');
define('FullMonthFebruary', 'Fevrier');
define('FullMonthMarch', 'Mars');
define('FullMonthApril', 'Avril');
define('FullMonthMay', 'Mai');
define('FullMonthJune', 'Juin');
define('FullMonthJuly', 'Juillet');
define('FullMonthAugust', 'Aout');
define('FullMonthSeptember', 'Septembre');
define('FullMonthOctober', 'Octobre');
define('FullMonthNovember', 'Novembre');
define('FullMonthDecember', 'Decembre');

define('ShortMonthJanuary', 'Jan');
define('ShortMonthFebruary', 'Fev');
define('ShortMonthMarch', 'Mar');
define('ShortMonthApril', 'Avr');
define('ShortMonthMay', 'Mai');
define('ShortMonthJune', 'Jun');
define('ShortMonthJuly', 'Jul');
define('ShortMonthAugust', 'Aou');
define('ShortMonthSeptember', 'Sep');
define('ShortMonthOctober', 'Oct');
define('ShortMonthNovember', 'Nov');
define('ShortMonthDecember', 'Dec');

define('FullDayMonday', 'Lundi');
define('FullDayTuesday', 'Mardi');
define('FullDayWednesday', 'Mercredi');
define('FullDayThursday', 'Jeudi');
define('FullDayFriday', 'Vendredi');
define('FullDaySaturday', 'Samedi');
define('FullDaySunday', 'Dimanche');

define('DayToolMonday', 'Lun');
define('DayToolTuesday', 'Mar');
define('DayToolWednesday', 'Mer');
define('DayToolThursday', 'Jeu');
define('DayToolFriday', 'Ven');
define('DayToolSaturday', 'Sam');
define('DayToolSunday', 'Dim');

define('CalendarTableDayMonday', 'L');
define('CalendarTableDayTuesday', 'M');
define('CalendarTableDayWednesday', 'M');
define('CalendarTableDayThursday', 'J');
define('CalendarTableDayFriday', 'V');
define('CalendarTableDaySaturday', 'S');
define('CalendarTableDaySunday', 'D');

define('ErrorParseJSON', 'La réponse \'JSON\' fournie par le serveur ne peut être analysée.');

define('ErrorLoadCalendar', 'Impossible de charger les calendriers');
define('ErrorLoadEvents', 'Impossible de charger les évčnements');
define('ErrorUpdateEvent', 'Impossible d\'enregistrer l\'évčnements');
define('ErrorDeleteEvent', 'Impossible d\'effacer l\'évčnements');
define('ErrorUpdateCalendar', 'Impossible d\'enregistrer le calendrier');
define('ErrorDeleteCalendar', 'Impossible d\'effacer le calendrier');
define('ErrorGeneral', 'Une erreur est survenue sur le serveur. Merci d\'essayer ultérieurement.');

// webmail 4.3 constants
define('SharedTitleEmail', 'E-mail');
define('ShareHeaderEdit', 'Partager et publier un calendrier');
define('ShareActionEdit', 'Partager et publier un calendrier');
define('CalendarPublicate', 'Rendre l\'accès public à ce calendrier');
define('CalendarPublicationLink', 'Lier');
define('ShareCalendar', 'Partager ce calendrier');
define('SharePermission1', 'Peut faire des changement et gérer le partage');
define('SharePermission2', 'Peut faire des changements sur les évènements');
define('SharePermission3', 'Peut voir tous les détails d\'un évènement');
define('SharePermission4', 'Ne peut voir uniquement les libre/occupé (masquer les détails)');
define('ButtonClose', 'fermer');
define('WarningEmailFieldFilling', 'Vous devriez remplir le champ email en premier');
define('EventHeaderView', 'Voir un évènement');
define('ErrorUpdateSharing', 'Impossible d\'enregistrer les données partagées et publiées');
define('ErrorUpdateSharing1', 'Impossible de partager à %s user car il n\'existe pas');
define('ErrorUpdateSharing2', 'Impossible de partager ce calendrier à l\'utilisateur %s');
define('ErrorUpdateSharing3', 'Ce calendrier est déjà partagé par l\'utilisateur %s');
define('Title_MyCalendars', 'Mes calendriers');
define('Title_SharedCalendars', 'Calendriers partagés');
define('ErrorGetPublicationHash', 'Impossible de créer le lien de publication');
define('ErrorGetSharing', 'Impossible d\'ajouter le partage');
define('CalendarPublishedTitle', 'Ce calendrier est publié');
define('RefreshSharedCalendars', 'Actualiser les calendriers partagés');
define('Title_CheckSharedCalendars', 'Reload Calendars');

define('GroupMembers', 'Membres');

define('ReportMessagePartDisplayed', 'N\'oubliez pas que juste une partie de ce message est affichée.');
define('ReportViewEntireMessage', 'Pour voir ce message en entier,');
define('ReportClickHere', 'cliquez ici');
define('ErrorContactExists', 'Un contact avec ce nom et cette adresse e-mail existe déjà.');

define('Attachments', 'Attachements');

define('InfoGroupsOfContact', 'Le groupe dont le contact est membre est marqué avec une coche.');
define('AlertNoContactsSelected', 'Aucun contact sélectionné.');
define('MailSelected', 'Adresses Mail selectionnées');
define('CaptionSubscribed', 'Abonné');

define('OperationSpam', 'Pourriel');
define('OperationNotSpam', 'Pas du pourriel');
define('FolderSpam', 'Pourriel');

// webmail 4.4 contacts
define('ContactMail', 'Contact de l\'email');
define('ContactViewAllMails', 'Voir tous les emails avec ce contact');
define('ContactsMailThem', 'Leur envoyer un mail');
define('DateToday', 'Aujourd\'hui');
define('DateYesterday', 'Hier');
define('MessageShowDetails', 'Montrer détails');
define('MessageHideDetails', 'Cacher détails');
define('MessageNoSubject', 'Pas de sujet');
// john@gmail.com à nadine@gmail.com
define('MessageForAddr', 'à');
define('SearchClear', 'Effacer la recherche');
// Search results for "search string" in Inbox folder:
// Search results for "search string" in all mail folders:
define('SearchResultsInFolder', 'Résultats de la recherche de "#s" dans #f folder:');
define('SearchResultsInAllFolders', 'Résultats de la recherche de "#s" dans l\'ensemble des dossiers mails:');
define('AutoresponderTitle', 'Réponse automatique');
define('AutoresponderEnable', 'Activer la réponse automatique');
define('AutoresponderSubject', 'Sujet');
define('AutoresponderMessage', 'Message');
define('ReportAutoresponderUpdatedSuccessfuly', 'La réponse automatique a été mise à jour avec succès..');
define('FolderQuarantine', 'Quarantaine');

//calendar
define('EventRepeats', 'Répétés');
define('NoRepeats', 'Ne se répète pas');
define('DailyRepeats', 'Journalièrement');
define('WorkdayRepeats', 'Chaque jour de semaine (Lundi à Dimanche)');
define('OddDayRepeats', 'Chaque Lundi, Mercredi et Dimanche.');
define('EvenDayRepeats', 'Chaque Mardi et Jeudi');
define('WeeklyRepeats', 'par semaine');
define('MonthlyRepeats', 'par mois');
define('YearlyRepeats', 'par an');
define('RepeatsEvery', 'se répète chaque');
define('ThisInstance', 'Uniquement cette occurrence');
define('AllEvents', 'Tous les évènements de la série');
define('AllFollowing', 'Tous les suivants');
define('ConfirmEditRepeatEvent', 'Souhaitez-vous modifier uniquement cet événement, tous les événements, ou celui-ci ainsi que tous les futurs événements de la série?');
define('RepeatEventHeaderEdit', 'Modifier l\'événement récurrent');
define('First', 'Premier');
define('Second', 'Deuxième');
define('Third', 'Troisième');
define('Fourth', 'Quatrième');
define('Last', 'Dernier');
define('Every', 'Chaque');
define('SetRepeatEventEnd', 'Définir la date de fin');
define('NoEndRepeatEvent', 'Pas de date de fin');
define('EndRepeatEventAfter', 'Fin après');
define('Occurrences', 'occurrences');
define('EndRepeatEventBy', 'Fin vers');
define('EventCommonDataTab', 'Principaux détails');
define('EventRepeatDataTab', 'Détails récurrents');
define('RepeatEventNotPartOfASeries', 'Cet événement a été modifié et ne fait plus partie d\'une série.');
define('UndoRepeatExclusion', 'Annuler les modifications à inclure dans la série.');

define('MonthMoreLink', '%d plus...');
define('NoNewSharedCalendars', 'Pas de nouveaux calendriers');
define('NNewSharedCalendars', '%d nouveaux calendriers trouvés');
define('OneNewSharedCalendars', '1 nouveau calendrier trouvé');
define('ConfirmUndoOneRepeat', 'Voulez-vous rétablir cet événement dans la série ?');

define('RepeatEveryDayInfin', 'Chaque jour');
define('RepeatEveryDayTimes', 'Chaque jour, %TIMES% heures');
define('RepeatEveryDayUntil', 'Chaque jour, jusqu\'au %UNTIL%');
define('RepeatDaysInfin', 'Chaque %PERIOD% jours');
define('RepeatDaysTimes', 'Chaque %PERIOD% jours, %TIMES% heures');
define('RepeatDaysUntil', 'Chaque %PERIOD% jours, jusqu\'au %UNTIL%');

define('RepeatEveryWeekWeekdaysInfin', 'Chaque semaine les jours de semaine');
define('RepeatEveryWeekWeekdaysTimes', 'Chaque semaine, les jours de semaine, %TIMES% heures');
define('RepeatEveryWeekWeekdaysUntil', 'Chaque semaine, les jours de semaine, jusqu\'au %UNTIL%');
define('RepeatWeeksWeekdaysInfin', 'Chaque %PERIOD% semaines les jours de semaine');
define('RepeatWeeksWeekdaysTimes', 'Chaque %PERIOD% semaines les jours de semaine, %TIMES% heures');
define('RepeatWeeksWeekdaysUntil', 'Chaque %PERIOD% semaines les jours de semaine, jusqu\'au %UNTIL%');

define('RepeatEveryWeekInfin', 'Chaque semaine le %DAYS%');
define('RepeatEveryWeekTimes', 'Chaque semaine le %DAYS%, %TIMES% heures');
define('RepeatEveryWeekUntil', 'Every week on %DAYS%, until %UNTIL%');
define('RepeatWeeksInfin', 'Chaque %PERIOD% semaines le %DAYS%');
define('RepeatWeeksTimes', 'Chaque %PERIOD% semaines le %DAYS%, %TIMES% heures');
define('RepeatWeeksUntil', 'Chaque %PERIOD% semaines le %DAYS%, jusqu\'au %UNTIL%');

define('RepeatEveryMonthDateInfin', 'Chaque mois le jour%DATE%');
define('RepeatEveryMonthDateTimes', 'Chaque mois le jour %DATE%, %TIMES% heures');
define('RepeatEveryMonthDateUntil', 'Chaque mois le jour %DATE%, jusqu\'au %UNTIL%');
define('RepeatMonthsDateInfin', 'Chaque %PERIOD% mois le jour %DATE%');
define('RepeatMonthsDateTimes', 'Chaque %PERIOD% mois le jour %DATE%, %TIMES% heures');
define('RepeatMonthsDateUntil', 'Chaque %PERIOD% mois le jour %DATE%, jusqu\'au %UNTIL%');

define('RepeatEveryMonthWDInfin', 'Chaque mois le  %NUMBER% %DAY%');
define('RepeatEveryMonthWDTimes', 'Chaque mois le  %NUMBER% %DAY%, %TIMES% heures');
define('RepeatEveryMonthWDUntil', 'Chaque mois le  %NUMBER% %DAY%, jusqu\'au %UNTIL%');
define('RepeatMonthsWDInfin', 'Chaque %PERIOD% mois le %NUMBER% %DAY%');
define('RepeatMonthsWDTimes', 'Chaque %PERIOD% mois le %NUMBER% %DAY%, %TIMES% heures');
define('RepeatMonthsWDUntil', 'Chaque %PERIOD% mois le %NUMBER% %DAY%, jusqu\'au %UNTIL%');

define('RepeatEveryYearDateInfin', 'Chaque année le jour %DATE%');
define('RepeatEveryYearDateTimes', 'Chaque année le jour %DATE%, %TIMES% heures');
define('RepeatEveryYearDateUntil', 'Chaque année le jour %DATE%, jusqu\'au %UNTIL%');
define('RepeatYearsDateInfin', 'Chaque %PERIOD% années le jour %DATE%');
define('RepeatYearsDateTimes', 'Chaque %PERIOD% années le jour %DATE%, %TIMES% heures');
define('RepeatYearsDateUntil', 'Chaque %PERIOD% années le jour %DATE%, jusqu\'au %UNTIL%');

define('RepeatEveryYearWDInfin', 'Chaque année le %NUMBER% %DAY%');
define('RepeatEveryYearWDTimes', 'Chaque année le %NUMBER% %DAY%, %TIMES% heure');
define('RepeatEveryYearWDUntil', 'Chaque année le %NUMBER% %DAY%, jusqu\'au %UNTIL%');
define('RepeatYearsWDInfin', 'Chaque %PERIOD% années le %NUMBER% %DAY%');
define('RepeatYearsWDTimes', 'Chaque %PERIOD% années le %NUMBER% %DAY%, %TIMES% heures');
define('RepeatYearsWDUntil', 'Chaque %PERIOD% années le %NUMBER% %DAY%, jusqu\'au %UNTIL%');

define('RepeatDescDay', 'jour');
define('RepeatDescWeek', 'semaine');
define('RepeatDescMonth', 'mois');
define('RepeatDescYear', 'année');

// webmail 4.5 contacts
define('WarningUntilDateBlank', 'Merci de spécifier une date de fin pour la récurrence');
define('WarningWrongUntilDate', 'La date de fin de récurrence doit être après la date de début de récurrence');

define('OnDays', 'Sur les jours');
define('CancelRecurrence', 'Effacer la récurrence');
define('RepeatEvent', 'Répéter cet évènement');

define('Spellcheck', 'Vérifier l\'orthographe');
define('LoginLanguage', 'Langue');
define('LanguageDefault', 'défaut');

// webmail 4.5.x new
define('EmptySpam', 'Vider le Courrier Indésirable');
define('Saving', 'Enregistrement&hellip;');
define('Sending', 'Envoi&hellip;');
define('LoggingOffFromServer', 'Se déconnecter du Server&hellip;');

//webmail 4.6
define('PROC_CANT_SET_MSG_AS_SPAM', 'Impossible de marquer le(s) message(s) commme courrier(s) indésirable(s).');
define('PROC_CANT_SET_MSG_AS_NOTSPAM', 'Impossible de marquer le(s) message(s) commme courrier(s) non indésirable(s).');
define('ExportToICalendar', 'Exporter vers iCalendar');
define('ErrorMaximumUsersLicenseIsExceeded', 'Votre compte a été désactivé car le nombre maximum d\'utilisateurs autorisés par votre licence a été dépassé. Merci de contacter votre  administrateur système.');
define('RepliedMessageTitle', 'Message Répondu');
define('ForwardedMessageTitle', 'Message transmis');
define('RepliedForwardedMessageTitle', 'Message répondu et transmis');
define('ErrorDomainExist', 'L\'utilisateur ne peut pas être créé parce que le domaine correspondant n\'existe pas. Vous devriez créer le domaine en premier.');

// webmail 4.6.x or 4.7
define('RequestReadConfirmation', 'Reading confirmation');
define('FolderTypeDefault', 'Default');
define('ShowFoldersMapping', 'Let me use another folder as a system folder (e.g. use MyFolder as Sent Items)');
define('ShowFoldersMappingNote', 'For instance, to change Sent Items location from Sent Items to MyFolder, specify "Sent Items" in "Use for" dropdown of "MyFolder".');
define('FolderTypeMapTo', 'Use for');

define('ReminderEmailExplanation', 'This message arrived to your %EMAIL% account because you ordered event notification in your calendar: %CALENDAR_NAME%');
define('ReminderOpenCalendar', 'Open calendar');

define('AddReminder', 'Remind me about this event');
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
