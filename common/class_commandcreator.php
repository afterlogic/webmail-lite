<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));
	
	define('QUOTE_ESCAPE', 1);
	define('QUOTE_DOUBLE', 2);
	
	class CommandCreator
	{
		/**
		 * @access private
		 * @var short
		 */
		var $_escapeType;
		
		/**
		 * @access private
		 * @var short
		 */
		var $_sPrefix;
		
		/**
		 * Class Constructor
		 *
		 * @return CommandCreator
		 */
		function CommandCreator($escapeType, $prefix)
		{
			$this->_escapeType = $prefix;
			$this->_sPrefix = $escapeType;
		}

		function GetPrefix()
		{
			return $this->_escapeType;
		}
		
		/**
		 * @access protected
		 * @param string $str
		 * @return string
		 */
		function _escapeString($str)
		{
			if ($str === '' || $str === null) return "''";
			$str = ConvertUtils::ClearUtf8($str);
			switch ($this->_escapeType)
			{
				default:
				case QUOTE_ESCAPE:
					return "'".addslashes($str)."'";
				case QUOTE_DOUBLE:
					return "'".str_replace("'", "''", $str)."'";
			}
		}
		
		/**
		 * @access protected
		 * @param string $bin
		 * @return string
		 */
		function _escapeBin($bin)
		{
			return $bin;
		}
		
		/**
		 * @param array $_array
		 * @return string
		 */
		function _inOrNot($_array)
		{
			 $_return = 'IN (%s)';
			 if (is_array($_array) && count($_array) == 1)
			 {
			 	$_return = '= %s';
			 }
			 return $_return;
		}
		
		/**
		 * @access protected
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param short $mailProtocol
		 * @return string
		 */
		function _quoteUids($messageIndexSet, $indexAsUid, $mailProtocol)
		{
			/* prepare struids */
			if ($indexAsUid && EMailProtocol::POP3 === $mailProtocol)
			{
				$messageIndexSet = array_map(array(&$this, '_escapeString'), $messageIndexSet);
				return implode(',', $messageIndexSet);
			}
			
			return implode(',', $messageIndexSet);
		}
		
		/**
		 * @access protected
		 * @param bool $indexAsUid
		 * @param short $mailProtocol
		 * @return string
		 */
		function _getMsgIdUidFieldName($indexAsUid, $mailProtocol)
		{
			if (!$indexAsUid)
			{
				return 'id_msg';
			}
			switch ($mailProtocol)
			{
				default:
					return 'str_uid';
				case EMailProtocol::IMAP4:
					return 'int_uid';
			}
		}
		
		/**
		 * @access protected
		 * @param int $order
		 * @param string $filter
		 * @param bool $asc
		 */
		function _setSortOrder($order, &$filter, &$asc)
		{
			switch ($order)
			{
				case EAccountDefaultOrder::AscDate:
					$filter = 'msg_date';
					$asc = true;
					break;
				case EAccountDefaultOrder::DescDate:
					$filter = 'msg_date';
					$asc = false;
					break;
				case EAccountDefaultOrder::AscFrom:
					$filter = 'from_msg';
					$asc = true;
					break;
				case EAccountDefaultOrder::DescFrom:
					$filter = 'from_msg';
					$asc = false;
					break;
				case EAccountDefaultOrder::AscTo:
					$filter = 'to_msg';
					$asc = true;
					break;
				case EAccountDefaultOrder::DescTo:
					$filter = 'to_msg';
					$asc = false;
					break;
				case EAccountDefaultOrder::AscSize:
					$filter = 'size';
					$asc = true;
					break;
				case EAccountDefaultOrder::DescSize:
					$filter = 'size';
					$asc = false;
					break;
				case EAccountDefaultOrder::AscSubject:
					$filter = 'subject';
					$asc = true;
					break;
				case EAccountDefaultOrder::DescSubject:
					$filter = 'subject';
					$asc = false;
					break;
				case EAccountDefaultOrder::AscAttachment:
					$filter = 'attachments';
					$asc = true;
					break;
				case EAccountDefaultOrder::DescAttachment:
					$filter = 'attachments';
					$asc = false;
					break;
				case EAccountDefaultOrder::AscFlag:
					$filter = 'flagged';
					$asc = true;
					break;
				case EAccountDefaultOrder::DescFlag:
					$filter = 'flagged';
					$asc = false;
					break;
			}
		}

		function DeleteFunambolContacts($userid)
		{
			$sql = 'DELETE FROM fnbl_pim_contact WHERE userid=%s';

			return sprintf($sql, $this->_escapeString($userid));
		}

		function DeleteFunambolEvents($userid)
		{
			$sql = 'DELETE FROM fnbl_pim_calendar WHERE userid=%s';

			return sprintf($sql, $this->_escapeString($userid));
		}

		/**
		 * @param string $email
		 * @return string
		 */
		function SelectSendersByEmail($email, $idUser)
		{
			$sql = 'SELECT safety FROM %sawm_senders WHERE id_user = %d AND email = %s';
			
			return sprintf($sql, $this->GetPrefix(), $idUser, $this->_escapeString($email));
		}
		
		/**
		 * @param string $email
		 * @param int $safety
		 * @return string
		 */
		function UpdateSenders($email, $safety, $idUser)
		{
			$sql = 'UPDATE %sawm_senders 
						SET safety = %d
						WHERE id_user = %d AND email = %s';
			
			return sprintf($sql, $this->GetPrefix(), $safety, $idUser, $this->_escapeString($email));
		}
		
		/**
		 * @param string $email
		 * @param int $safety
		 * @return string
		 */
		function InsertSenders($email, $safety, $idUser)
		{
			$sql = 'INSERT INTO %sawm_senders (id_user, email, safety) VALUES (%d, %s, %d)';
			
			return sprintf($sql, $this->GetPrefix(), $idUser, $this->_escapeString($email), (int) $safety);			
		}

		/**
		 * @param Folder $folder
		 * @return string
		 */
		function GetFolderMessageCountAll($folder)
		{
			$sql = 'SELECT COUNT(id) AS message_count FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d';
			
			return sprintf($sql, $this->GetPrefix(), $folder->IdAcct, $folder->IdDb);			
		}

		/**
		 * @param Folder $folder
		 * @return string
		 */
		function GetFolderMessageCountUnread($folder)
		{
			$sql = 'SELECT COUNT(id) AS unread_message_count FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d AND seen = 0';
			
			return sprintf($sql, $this->GetPrefix(), $folder->IdAcct, $folder->IdDb);			
		}
		
		/**
		 * @param int $accountId
		 * @return string
		 */
		function SelectAccountData($accountOrUserId, $accountByUserId = false)
		{
			$sql = '';
			if($accountByUserId)
			{
				$sql = 'SELECT
							id_acct, acct.id_user as id_user, def_acct, deleted, email, mail_protocol,
							mail_inc_host, mail_inc_login, mail_inc_pass, mail_inc_port, mail_out_host,
							mail_out_login, mail_out_pass, mail_out_port, mail_out_auth, friendly_nm,
							use_friendly_nm, def_order,	getmail_at_login, mail_mode, mails_on_server_days,
							signature_type, signature_opt, delimiter, personal_namespace,
							msgs_per_page, white_listing, x_spam, %s as last_login, logins_count, def_skin, def_editor,
							def_lang, def_charset_inc, def_charset_out, def_timezone, def_date_fmt,
							hide_folders, mailbox_limit, mailbox_size, id_domain, mailing_list,
							allow_change_settings, allow_dhtml_editor, allow_direct_mode, hide_contacts, db_charset,
							horiz_resizer, vert_resizer, mark, reply, contacts_per_page, view_mode, imap_quota,
							question_1, answer_1, question_2, answer_2, auto_checkmail_interval, enable_fnbl_sync,
							allow_contacts, allow_compose, allow_reply, allow_forward
						FROM %sawm_accounts AS acct
						INNER JOIN %sawm_settings AS sett ON acct.id_user = sett.id_user
						WHERE acct.id_user = %d AND acct.def_acct = 1 AND acct.deleted = 0 AND acct.mailing_list = 0
						ORDER BY acct.email
						LIMIT 1;
				';
			}
			else
			{
				$sql = 'SELECT id_acct, acct.id_user as id_user, def_acct, deleted, email, mail_protocol,
							mail_inc_host, mail_inc_login, mail_inc_pass, mail_inc_port, mail_out_host,
							mail_out_login, mail_out_pass, mail_out_port, mail_out_auth, friendly_nm,
							use_friendly_nm, def_order,	getmail_at_login, mail_mode, mails_on_server_days,
							signature_type, signature_opt, delimiter, personal_namespace,
							msgs_per_page, white_listing, x_spam, %s as last_login, logins_count, def_skin, def_editor,
							def_lang, def_charset_inc, def_charset_out, def_timezone, def_date_fmt,
							hide_folders, mailbox_limit, mailbox_size, id_domain, mailing_list,
							allow_change_settings, allow_dhtml_editor, allow_direct_mode, hide_contacts, db_charset,
							horiz_resizer, vert_resizer, mark, reply, contacts_per_page, view_mode, imap_quota,
							question_1, answer_1, question_2, answer_2, auto_checkmail_interval, enable_fnbl_sync,
							allow_contacts, allow_compose, allow_reply, allow_forward
						FROM %sawm_accounts AS acct
						INNER JOIN %sawm_settings AS sett ON acct.id_user = sett.id_user
						WHERE id_acct = %d AND mailing_list = 0';
			}
			return sprintf($sql, $this->GetDateFormat('last_login'), $this->GetPrefix(), $this->GetPrefix(), $accountOrUserId);
		}
		
		/** 
		 * @param string $email
		 * @param string $login
		 * @return string
		 */
		function SelectAccountFullDataByLogin($email, $login)
		{
			$sql = 'SELECT id_acct, acct.id_user as id_user, def_acct, deleted, email, mail_protocol,
						mail_inc_host, mail_inc_login, mail_inc_pass, mail_inc_port, mail_out_host,
						mail_out_login, mail_out_pass, mail_out_port, mail_out_auth, friendly_nm,
						use_friendly_nm, def_order, getmail_at_login, mail_mode, mails_on_server_days,
						signature_type, signature_opt, delimiter, personal_namespace,
						msgs_per_page, white_listing, x_spam, %s as last_login, logins_count, def_skin, def_editor,
						def_lang, def_charset_inc, def_charset_out, def_timezone, def_date_fmt,
						hide_folders, mailbox_limit, mailbox_size, id_domain, mailing_list,
						allow_change_settings, allow_dhtml_editor, allow_direct_mode, hide_contacts, db_charset,
						horiz_resizer, vert_resizer, mark, reply, contacts_per_page, view_mode, imap_quota
					FROM %sawm_accounts AS acct
					INNER JOIN %sawm_settings AS sett ON acct.id_user = sett.id_user
					WHERE email = %s AND mail_inc_login = %s AND mailing_list = 0';
			
			return sprintf($sql, $this->GetDateFormat('last_login'), $this->GetPrefix(), $this->GetPrefix(),
								$this->_escapeString($email), $this->_escapeString($login));
		}

		/**
		 * @param int $accountId
		 * @return string
		 */
		function SelectSignature($accountId)
		{
			$sql = 'SELECT id_acct, signature FROM %sawm_accounts WHERE id_acct = %d';
			return sprintf($sql, $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function LoadMessagesFromDB($msgArray, $account)
		{
			$sql = 'SELECT id_msg, msg
					FROM %sawm_messages_body
					WHERE id_acct = %d AND id_msg '.$this->_inOrNot($msgArray);

			return sprintf($sql, $this->GetPrefix(), $account->IdAccount,
					$this->_quoteUids($msgArray, false, $account->IncomingMailProtocol));
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function PreLoadMessagesFromDB($messageIndes, $indexAsUid, $folder, $account)
		{
			$sql = 'SELECT id_msg, %s AS uid, priority, flags, downloaded, size
					FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d AND %s '.$this->_inOrNot($messageIndes);

			return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
							$this->GetPrefix(),
							$account->IdAccount, $folder->IdDb, $this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
							$this->_quoteUids($messageIndes, $indexAsUid, $account->IncomingMailProtocol));
		}
				
		/**
		 * @param int $idUser
		 * @return string
		 */
		function SelectAccountColumnsData($idUser)
		{
			$sql = 'SELECT id_column, column_value FROM %sawm_columns WHERE id_user = %d';
			return sprintf($sql, $this->GetPrefix(), $idUser);
		}
		
		/**
		 * @param int $idUser
		 * @param int $id_column
		 * @param int $value_column
		 * @return string
		 */
		function UpdateColumnData($idUser, $id_column, $value_column)
		{
			$sql = 'UPDATE %sawm_columns SET column_value = %d
						WHERE id_user = %d AND id_column = %d';
			return sprintf($sql, $this->GetPrefix(), $value_column, $idUser, $id_column);			
		}
		
		/**
		 * @param int $idUser
		 * @param int $id_column
		 * @param int $value_column
		 * @return string
		 */		
		function InsertColumnData($idUser, $id_column, $value_column)
		{
			$sql = 'INSERT INTO %sawm_columns (id_user, id_column, column_value)
						VALUES (%d, %d, %d)';
			return sprintf($sql, $this->GetPrefix(), $idUser, $id_column, $value_column);
		}

		/** 
		 * @param string $email
		 * @param string $login
		 * @return string
		 */
		function SelectDefAccountDataByLogin($email, $login)
		{
			$sql = 'SELECT id_acct, id_user, mail_inc_pass, def_acct, deleted
					FROM %sawm_accounts
					WHERE email = %s AND mailing_list = 0 AND mail_inc_login = %s AND def_acct = 1';
			
			return sprintf($sql, $this->GetPrefix(),
									$this->_escapeString($email),
									$this->_escapeString($login));
		}
			
		/** 
		 * @param string $email
		 * @param string $login
		 * @return string
		 */
		function SelectDefAccountsCountByLogin($email, $login, $idAcct = null)
		{
			$temp = ($idAcct !== null) ? ' AND id_acct <> '.(int) $idAcct : '';
			
			$sql = 'SELECT COUNT(id_acct) AS acct_count
					FROM %sawm_accounts
					WHERE email = %s AND mailing_list = 0 AND mail_inc_login = %s AND def_acct = 1' . $temp;
			
			return sprintf($sql, $this->GetPrefix(),
									$this->_escapeString($email),
									$this->_escapeString($login)); 
		}
		
		/**
		 * @param int $userId
		 * @return string
		 */
		function SelectSetings($userId)
		{
			$sql = 'SELECT * FROM %sawm_settings WHERE id_user = %d';
			return sprintf($sql, $this->GetPrefix(), $userId);		
		}
		
		/**
		 * @param CAccount $account
		 * @param int $msgId
		 * @param int $charset
		 * @param WebMailMessage $message
		 * @return string
		 */
		function UpdateMessageCharset(&$account, $msgId, $charset, &$message)
		{
			$sql = 'UPDATE %sawm_messages
				SET charset = %d, from_msg = %s, to_msg = %s, cc_msg = %s, bcc_msg = %s,
					subject = %s, attachments = %d, size = %d
				WHERE id_acct = %d AND id_msg = %d';
			
			return sprintf($sql, $this->GetPrefix(), (int) $charset,
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetFromAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetToAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetCcAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetBccAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetSubject(), 255)),
									$message->HasAttachments(),
									$message->GetMailSize(),
									(int) $account->IdAccount, (int) $msgId);
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param Boolean $indexAsUid
		 * @param Folder $folder
		 * @param int $flags
		 * @param CAccount $account
		 * @return String
		 */
		function UpdateMessageFlags($messageIndexSet, $indexAsUid, $folder, $flags, $account)
		{
			$sql = 'UPDATE %sawm_messages
					SET flags = %d, seen = %d, flagged = %d, deleted = %d, replied = %d, forwarded = %d, grayed = %d';

			$sql = sprintf($sql, $this->GetPrefix(), $flags,
				(int) (($flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen),
				(int) (($flags & MESSAGEFLAGS_Flagged) == MESSAGEFLAGS_Flagged),
				(int) (($flags & MESSAGEFLAGS_Deleted) == MESSAGEFLAGS_Deleted),
				(int) (($flags & MESSAGEFLAGS_Answered) == MESSAGEFLAGS_Answered),
				(int) (($flags & MESSAGEFLAGS_Forwarded) == MESSAGEFLAGS_Forwarded),
				(int) (($flags & MESSAGEFLAGS_Grayed) == MESSAGEFLAGS_Grayed)
			);
			
			if ($messageIndexSet != null)
			{
				$sql .= ' WHERE id_acct = %d AND id_folder_db = %d AND %s '.$this->_inOrNot($messageIndexSet);
				
				return sprintf($sql, $account->IdAccount, $folder->IdDb,
								$this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
								$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
			}
			
			$sql .= ' WHERE id_acct = %d AND id_folder_db = %d';
			return sprintf($sql, $account->IdAccount, $folder->IdDb);

		}
		
		/**
		 * @param int $id_user
		 * @return string
		 */
		function IsSettingsExists($id_user)
		{
			$sql = 'SELECT COUNT(id_user) as cnt FROM %sawm_settings WHERE id_user = %d';
			return sprintf($sql, $this->GetPrefix(), $id_user);
		}

		/**
		 * @param Folder $folder
		 * @return string
		 */
		function CreateFolderTree($folder)
		{
			$sql = 'INSERT INTO %sawm_folders_tree (id_folder, id_parent, folder_level)	
					VALUES (%d, %d, 0)';
			
			return sprintf($sql, $this->GetPrefix(), $folder->IdDb, $folder->IdDb);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function CreateSelectFolderTree($folder)
		{
			$sql = 'INSERT INTO %sawm_folders_tree (id_folder, id_parent, folder_level)	
					VALUES (%d, %d, %d)';
			
			return sprintf($sql, $this->GetPrefix(), $folder->IdDb,
									$folder->IdParent, $folder->Level);			
		}		
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function SelectForCreateFolderTree($folder)
		{
			$sql = 'SELECT id_parent, folder_level
					FROM %sawm_folders_tree
					WHERE id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(), $folder->IdParent);		
		}
		
		/**
		 * @param Folder $folder
		 * @param string $newName
		 * @return string
		 */
		function RenameFolder($folder, $newName)
		{
			$sql = 'UPDATE %sawm_folders
					SET full_path = %s
					WHERE id_acct = %d AND id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(), $this->_escapeString($newName.'#'),
								$folder->IdAcct, $folder->IdDb);
		}
		
		/**
		 * @param Folder $folder
		 * @param Array $foldersId
		 * @param string $newName
		 * @return string
		 */
		function RenameSubFoldersPath($folder, $foldersId, $newSubPath)
		{
			$sql = 'UPDATE %sawm_folders
					SET full_path = CONCAT("%s", SUBSTRING(full_path, %d))
					WHERE id_acct = %d AND id_folder '.$this->_inOrNot($foldersId).' AND id_folder <> %d';
			
			return sprintf($sql, $this->GetPrefix(), $newSubPath, strlen($folder->FullName) + 1,
								$folder->IdAcct, implode(',', $foldersId), $folder->IdDb);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function DeleteFolder($folder)
		{
			$sql = 'DELETE FROM %sawm_folders
					WHERE %sawm_folders.id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(), $this->GetPrefix(), $folder->IdDb);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function DeleteFolderTree($folder)
		{
			$sql = 'DELETE FROM %sawm_folders_tree
					WHERE %sawm_folders_tree.id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(), $this->GetPrefix(), $folder->IdDb);
		}
		
		/**
		 * @param int $id
		 * @param int $id_acct
		 * @return string
		 */
		function GetFolderFullName($id, $id_acct)
		{
			$sql = 'SELECT full_path FROM %sawm_folders WHERE id_acct = %d AND id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(), $id_acct, $id);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function GetFolderInfo($folder)
		{
			$sql = 'SELECT full_path, name, type, sync_type, hide, fld_order, id_parent, flags FROM %sawm_folders
					WHERE id_acct = %d AND id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(), $folder->IdAcct, $folder->IdDb);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function GetFolderChildCount($folder)
		{
			$sql = 'SELECT COUNT(child.id_folder) AS child_count
					FROM %sawm_folders AS parent
					INNER JOIN %sawm_folders AS child ON parent.id_folder = child.id_parent
					WHERE parent.id_acct = %d AND parent.id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(),
								$this->GetPrefix(), $folder->IdAcct, $folder->IdDb);
		}

		/**
		 * @param Folder $folder
		 * @return string
		 */
		function UpdateFolder($folder)
		{
			$sql = 'UPDATE %sawm_folders
					SET name = %s, type = %d, sync_type = %d, hide = %d, fld_order = %d, flags = %s
					WHERE id_acct = %d AND id_folder = %d';
			
			return sprintf($sql, $this->GetPrefix(), 
								$this->_escapeString($folder->Name.'#'),
								$folder->Type, $folder->SyncType,
								$folder->Hide, $folder->FolderOrder,
								$this->_escapeString($folder->Flags),
								$folder->IdAcct, $folder->IdDb);
		}

		/**
		 * @param int $accountId
		 * @param short $type
		 * @return string
		 */
		function GetFolderSyncType($accountId, $type)
		{
			$sql = 'SELECT sync_type FROM %sawm_folders WHERE id_acct = %d AND type = %d';
			return sprintf($sql, $this->GetPrefix(), $accountId, $type);
		}
		
		/**
		 * @param WebMailMessage $message
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function UpdateMessageHeader($message, $folder, $account)
		{
			$sql = 'UPDATE %sawm_messages SET
			 			from_msg = %s, to_msg = %s, cc_msg = %s, bcc_msg = %s, subject = %s,
						msg_date = %s, attachments = %d, size = %d, x_spam = %d,
						seen = %d, flagged = %d, deleted = %d, replied = %d, grayed = %d,
						flags= %d, priority = %d, body_text = %s
					WHERE id_msg = %d AND id_folder_db = %d AND id_acct = %d';
			
			$date = $message->GetDate();
			return sprintf($sql, $this->GetPrefix(),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetFromAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetToAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetCcAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetBccAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetSubject(), 255)),
									
									$this->UpdateDateFormat($date->ToANSI()),
									(int) $message->HasAttachments(),
									$message->GetMailSize(),
									(int) $message->GetXSpamStatus(),
									(($message->Flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen),
									(($message->Flags & MESSAGEFLAGS_Flagged) == MESSAGEFLAGS_Flagged),
									(($message->Flags & MESSAGEFLAGS_Deleted) == MESSAGEFLAGS_Deleted),
									(($message->Flags & MESSAGEFLAGS_Answered) == MESSAGEFLAGS_Answered),
									(($message->Flags & MESSAGEFLAGS_Grayed) == MESSAGEFLAGS_Grayed),
									$message->Flags,
									$message->GetPriorityStatus(),
									$this->_escapeString(substr($message->GetPlainLowerCaseBodyText(), 0, 500000)),
									$message->IdMsg, $folder->IdDb, $account->IdAccount);
		}
		
		/**
		 * @param WebMailMessage $message
		 * @param Folder $folder
		 * @param bool $downloaded
		 * @param CAccount $account
		 * @return string
		 */
		function SaveMessageHeader($message, $folder, $downloaded, $account)
		{
			/* save message header */
			$sql = 'INSERT INTO %sawm_messages (id_msg, id_acct, id_folder_srv, id_folder_db,
								str_uid, int_uid, from_msg, to_msg, cc_msg, bcc_msg, subject,
								msg_date, attachments, size, downloaded, x_spam,
								seen, flagged, rtl, deleted, replied, grayed, flags,
								priority, body_text, forwarded, charset, sensitivity)
					VALUES (%d, %d,	%d, %d, %s, %d, %s, %s, %s, %s, %s, %s, %d, %d, %d,	%d, %d, %d, %d, %d, %d, %d, %d, %d, %s, 0, -1, %d)';
			
			$date = $message->GetDate();

			$str_uid = $int_uid = null;
			if ($account->IncomingMailProtocol == EMailProtocol::IMAP4)
			{
				$str_uid = '';
				$int_uid = $message->Uid;
			}
			else
			{
				$str_uid = $message->Uid;
				$int_uid = 0;
			}

			return sprintf($sql, $this->GetPrefix(),
									$message->IdMsg,
									$account->IdAccount,
									$folder->IdDb, $folder->IdDb,
									$this->_escapeString($str_uid), $int_uid,
									
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetFromAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetToAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetCcAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetBccAsString(), 255)),
									$this->_escapeString(api_Utils::Utf8Truncate($message->GetSubject(), 255)),
									
									$this->UpdateDateFormat($date->ToANSI()),
									(int) $message->HasAttachments(),
									$message->GetMailSize(),
									(int) $downloaded,
									(int) $message->GetXSpamStatus(),
									(($message->Flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen),
									(($message->Flags & MESSAGEFLAGS_Flagged) == MESSAGEFLAGS_Flagged),
									0,
									(($message->Flags & MESSAGEFLAGS_Deleted) == MESSAGEFLAGS_Deleted),
									(($message->Flags & MESSAGEFLAGS_Answered) == MESSAGEFLAGS_Answered),
									(($message->Flags & MESSAGEFLAGS_Grayed) == MESSAGEFLAGS_Grayed),
									$message->Flags,
									$message->GetPriorityStatus(),
									$this->_escapeString(substr($message->GetPlainLowerCaseBodyText(), 0, 500000)),
									$message->GetSensitivity()
								);
		}
		
		
		function GetMessageSize($message, $folder, $accountId)
		{
			$sql = 'SELECT size FROM %sawm_messages
					WHERE id_msg = %d AND id_folder_db = %d AND id_acct = %d';
			
			return sprintf($sql, $this->GetPrefix(), $message->IdMsg, $folder->IdDb, $accountId);
		}
		
		/**
		 * @param WebMailMessage $message
		 * @param string $accountId
		 * @return string
		 */
		function SaveBody(&$message, $accountId)
		{
			$sql = 'INSERT INTO %sawm_messages_body (id_acct, id_msg, msg)
					VALUES (%d, %d, %s)';
				
			return sprintf($sql, $this->GetPrefix(), $accountId,
										$message->IdMsg, $this->_escapeBin($message->TryToGetOriginalMailMessage()));
		}				

		/**
		 * @param WebMailMessage $message
		 * @param string $accountId
		 * @return string
		 */
		function UpdateBody(&$message, $accountId)
		{
			$sql = 'UPDATE %sawm_messages_body SET msg = %s
					WHERE id_acct = %d AND id_msg = %d';
				
			return sprintf($sql, $this->GetPrefix(),
						$this->_escapeBin($message->TryToGetOriginalMailMessage()),
						$accountId,	$message->IdMsg);
		}

		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function LoadMessagesFromFileSystem($messageIndexSet, $indexAsUid, $folder, $account)
		{
			/* read messages from the file system */
			$sql = 'SELECT id_msg, %s AS uid, priority, flags
					FROM %sawm_messages AS msg
					WHERE id_acct = %d AND id_folder_db = %d AND %s '.$this->_inOrNot($messageIndexSet);

			return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol), $this->GetPrefix(), $account->IdAccount,
						$folder->IdDb, $this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
						$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
		}
		
		/**
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function SelectIdMsgAndUid($folder, $account)
		{
			$sql = 'SELECT id_msg, %s AS uid, flags AS flag
					FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d';
			
			return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
							$this->GetPrefix(), $account->IdAccount, $folder->IdDb);
		}
		
		/**
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function _SelectIdMsgAndUid($folder, $account)
		{
			$sql = 'SELECT id_msg, %s AS uid, flags AS flag
					FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_srv = %d
					ORDER BY id_msg DESC';
			
			return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
							$this->GetPrefix(), $account->IdAccount, $folder->IdDb);
		}
		
		/**
		 * @param int $accountId
		 * @return string
		 */
		function SelectLastIdMsg($accountId)
		{
			$sql = 'SELECT MAX(id_msg) AS nid_msg
					FROM %sawm_messages
					WHERE id_acct = %d';

			return sprintf($sql, $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param int $messageId
		 * @param Folder $folder
		 * @param int $accountId
		 * @return string
		 */
		function GetMessageDownloadedFlag($messageId, $folder, $accountId)
		{
			$sql = 'SELECT downloaded FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d AND id_msg = %d';
			
			return sprintf($sql, $this->GetPrefix(), $accountId, $folder->IdDb, $messageId);
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function DeleteMessagesHeaders($messageIndexSet, $indexAsUid, $folder, $account)
		{
			$sql = 'DELETE FROM %sawm_messages 
					WHERE id_acct = %d AND id_folder_db = %d AND %s '.$this->_inOrNot($messageIndexSet);
			
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount, $folder->IdDb,
							$this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
							$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
		}

		/**
		 * @param Folder $folder
		 * @return string
		 */
		function DeleteMessagesHeadersFromFolder($folder)
		{
			$sql = 'DELETE FROM %sawm_messages WHERE id_acct = %d AND id_folder_db = %d';

			return sprintf($sql, $this->GetPrefix(), $folder->IdAcct, $folder->IdDb);
		}
		
		/**
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function ClearDbFolder($folder, $account)
		{
			$sql = 'DELETE FROM %sawm_messages 
					WHERE id_acct = %d AND id_folder_db = %d';
			
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount, $folder->IdDb);
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $fromFolder
		 * @param Folder $toFolder
		 * @param CAccount $account
		 * @return string
		 */
		function MoveMessages($messageIndexSet, $indexAsUid, $fromFolder, $toFolder, $account)
		{
			$sql = 'UPDATE %sawm_messages
					SET id_folder_db = %d
					WHERE id_acct = %d AND id_folder_db = %d  AND %s '.$this->_inOrNot($messageIndexSet);
			
			return sprintf($sql, $this->GetPrefix(), $toFolder->IdDb,
								$account->IdAccount, $fromFolder->IdDb,
								$this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
								$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
								
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $fromFolder
		 * @param Folder $toFolder
		 * @param CAccount $account
		 * @return string
		 */
		function FullMoveMessages($messageIndexSet, $indexAsUid, $fromFolder, $toFolder, $account)
		{
			$sql = 'UPDATE %sawm_messages
					SET id_folder_db = %d, id_folder_srv = %d
					WHERE id_acct = %d AND id_folder_db = %d  AND %s '.$this->_inOrNot($messageIndexSet);
			
			return sprintf($sql, $this->GetPrefix(), $toFolder->IdDb, $toFolder->IdDb,
								$account->IdAccount, $fromFolder->IdDb,
								$this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
								$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
								
		}
		
		function MoveMessageWithUidUpdate($_id, $_uid, $fromFolder, $toFolder)
		{
			$sql = 'UPDATE %sawm_messages
					SET id_folder_db = %d, id_folder_srv = %d, str_uid = %s
					WHERE id_folder_db = %d AND id_msg = %d';
			
			return sprintf($sql, $this->GetPrefix(), $toFolder->IdDb, $toFolder->IdDb, 
								$this->_escapeString($_uid), $fromFolder->IdDb, $_id);
		}

		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @param int $flags
		 * @param short $action
		 * @param CAccount $account
		 * @return string
		 */
		function SetMessagesFlags($messageIndexSet, $indexAsUid, $folder, $flags, $action, $account)
		{
			switch ($action)
			{
				case ACTION_Set:
					$sql = 'UPDATE %sawm_messages
							SET flags = (flags | %d) & ~768'; /* remove non-Imap flags */

					if (($flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen)
					{
						$sql .= ', seen = 1';
					}
					if (($flags & MESSAGEFLAGS_Flagged) == MESSAGEFLAGS_Flagged)
					{
						$sql .= ', flagged = 1';
					}
					if (($flags & MESSAGEFLAGS_Deleted) == MESSAGEFLAGS_Deleted)
					{
						$sql .= ', deleted = 1';
					}
					if (($flags & MESSAGEFLAGS_Answered) == MESSAGEFLAGS_Answered)
					{
						$sql .= ', replied = 1';
					}
					if (($flags & MESSAGEFLAGS_Forwarded) == MESSAGEFLAGS_Forwarded)
					{
						$sql .= ', forwarded = 1';
					}
					if (($flags & MESSAGEFLAGS_Grayed) == MESSAGEFLAGS_Grayed)
					{
						$sql .= ', grayed = 1';
					}
					break;

				case ACTION_Remove:
					$sql = 'UPDATE %sawm_messages
							SET flags = (flags & ~%d) & ~768'; /* remove non-Imap flags */
					
					if (($flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen)
					{
						$sql .= ', seen = 0';
					}
					if (($flags & MESSAGEFLAGS_Flagged) == MESSAGEFLAGS_Flagged)
					{
						$sql .= ', flagged = 0';
					}
					if (($flags & MESSAGEFLAGS_Deleted) == MESSAGEFLAGS_Deleted)
					{
						$sql .= ', deleted = 0';
					}
					if (($flags & MESSAGEFLAGS_Answered) == MESSAGEFLAGS_Answered)
					{
						$sql .= ', replied = 0';
					}
					if (($flags & MESSAGEFLAGS_Forwarded) == MESSAGEFLAGS_Forwarded)
					{
						$sql .= ', forwarded = 0';
					}
					if (($flags & MESSAGEFLAGS_Grayed) == MESSAGEFLAGS_Grayed)
					{
						$sql .= ', grayed = 0';
					}
					break;
			}
			
			if ($messageIndexSet != null)
			{
				$sql .= ' WHERE id_acct = %d AND id_folder_db = %d AND %s '.$this->_inOrNot($messageIndexSet);
				return sprintf($sql, $this->GetPrefix(), $flags, $account->IdAccount, $folder->IdDb,
								$this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
								$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
			}
			
			$sql .= ' WHERE id_acct = %d AND id_folder_db = %d';
			return sprintf($sql, $this->GetPrefix(), $flags, $account->IdAccount, $folder->IdDb);

		}
		
		/**
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function SelectAllDeletedMsgId($folder, $account, $pop3EmptyTrash = false)
		{
			$sql = 'SELECT id_msg FROM %sawm_messages
							WHERE id_acct = -%d AND id_folder_db = %d AND downloaded = 1';
			
			switch ($account->IncomingMailProtocol)
			{
				case EMailProtocol::POP3:
					$sql = 'SELECT id_msg FROM %sawm_messages
							WHERE id_acct = %d AND id_folder_db = %d AND downloaded = 1';
					break;
					
				case EMailProtocol::IMAP4:
					if ($pop3EmptyTrash)
					{
						$sql = 'SELECT id_msg FROM %sawm_messages
								WHERE id_acct = %d AND id_folder_db = %d AND downloaded = 1';
					}
					else
					{
						$sql = 'SELECT id_msg FROM %sawm_messages
								WHERE id_acct = %d AND id_folder_db = %d AND
									deleted = 1 AND downloaded = 1';
					}
					break;
			}
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount, $folder->IdDb);
		}

		/**
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function PurgeAllMessageHeaders($folder, $account, $pop3EmptyTrash = false)
		{
			$sql = 'DELETE FROM %sawm_messages WHERE id_acct = -%d AND id_folder_db = %d';
			switch ($account->IncomingMailProtocol)
			{
				case EMailProtocol::POP3:
					$sql = 'DELETE FROM %sawm_messages WHERE id_acct = %d AND id_folder_db = %d';
					break;
					
				case EMailProtocol::IMAP4:
					if ($pop3EmptyTrash)
					{
						$sql = 'DELETE FROM %sawm_messages WHERE id_acct = %d AND id_folder_db = %d';
					}
					else
					{
						$sql = 'DELETE FROM %sawm_messages WHERE id_acct = %d AND id_folder_db = %d AND deleted = 1';
					}
					break;
			}
			
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount, $folder->IdDb);
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param CAccount $account
		 * @return string
		 */
		function SelectDownloadedMessagesIdSet($messageIndexSet, $indexAsUid, $account)
		{
			$sql = 'SELECT id_msg FROM %sawm_messages
					WHERE id_acct = %d AND downloaded = 1 AND %s '.$this->_inOrNot($messageIndexSet);
			
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount,
									$this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
									$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
		}
		
		/**
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function SelectAllMessagesUidSetByFolder($folder, $account)
		{
			$sql = 'SELECT %s AS uid FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d';
			return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
									$this->GetPrefix(), $account->IdAccount, $folder->IdDb);
		}
		
		/**
		 * @param int $accountId
		 * @return string
		 */
		function SelectFilters($accountId)
		{
			$sql = 'SELECT id_filter, field, condition, filter, action, id_folder, applied
					FROM %sawm_filters
					WHERE id_acct = %d
					ORDER BY action';
			return sprintf($sql, $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param Filter $filter
		 * @return string
		 */
		function InsertFilter($filter)
		{
			$sql = 'INSERT INTO %sawm_filters (id_acct, field, condition, filter, action, id_folder, applied)
					VALUES (%d, %d, %d, %s, %d, %d, %d)';
					
			return sprintf($sql, $this->GetPrefix(), $filter->IdAcct,
									$filter->Field, $filter->Condition,
									$this->_escapeString($filter->Filter),
									$filter->Action, $filter->IdFolder, $filter->Applied);
		}
		
		/**
		 * @param Filter $filter
		 * @return string
		 */
		function UpdateFilter($filter)
		{
			$sql = 'UPDATE %sawm_filters SET
						field = %d, condition = %d, filter = %s, action = %d,
						id_folder = %d, applied= %d
					WHERE id_filter = %d AND id_acct = %d';

			return sprintf($sql, $this->GetPrefix(), $filter->Field,
									$filter->Condition,	$this->_escapeString($filter->Filter),
									$filter->Action, $filter->IdFolder, $filter->Applied,
									$filter->Id, $filter->IdAcct);			
		}

		/**
		 * @param int $filterId
		 * @param int $accountId
		 * @return string
		 */
		function DeleteFilter($filterId, $accountId)
		{
			$sql = 'DELETE FROM %sawm_filters
					WHERE id_filter = %d AND id_acct = %d';
			
			return sprintf($sql, $this->GetPrefix(), $filterId, $accountId);
		}
		
		/**
		 * @param int $folderId
		 * @param int $accountId
		 * @return string
		 */
		function DeleteFolderFilters($folderId, $accountId)
		{
			$sql = 'DELETE FROM %sawm_filters
					WHERE id_folder = %d AND id_acct = %d';
			
			return sprintf($sql, $this->GetPrefix(), $folderId, $accountId);
		}

		/**
		 * @param int $accountId
		 * @return string
		 */
		function SelectReadsRecords($accountId)
		{
			$sql = 'SELECT str_uid AS uid
					FROM %sawm_reads
					WHERE id_acct = %d';

			return sprintf($sql, $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param int $accountId
		 * @param string $pop3Uid
		 * @return string
		 */
		function InsertReadsRecord($accountId, $pop3Uid)
		{
			$sql = 'INSERT INTO %sawm_reads (id_acct, str_uid, tmp) VALUES(%d, %s, 0)';
			return sprintf($sql, $this->GetPrefix(), $accountId, $this->_escapeString($pop3Uid));
		}
		
		/**
		 * @param int $accountId
		 * @return string
		 */
		function DeleteReadsRecords($accountId)
		{
			$sql = 'DELETE FROM %sawm_reads WHERE id_acct = %d';

			return sprintf($sql, $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param int $accountId
		 * @param array $uids
		 * @return string
		 */
		function DeleteReadsRecordsByUid($accountId, $uids)
		{
			$uids = array_map(array(&$this, '_escapeString'), $uids);
			$str_uids = implode(',', $uids);
			$sql = 'DELETE FROM %sawm_reads WHERE id_acct = %d AND str_uid '.$this->_inOrNot($uids);

			return sprintf($sql, $this->GetPrefix(), $accountId, $str_uids);
		}

		
		/**
		 * @param int $accountId
		 * @return string
		 */
		function CountMailboxSize($accountId)
		{
			$sql = 'SELECT SUM(size) AS mailbox_size
					FROM %sawm_messages WHERE id_acct = %d';
			
			return sprintf($sql, $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param int $size
		 * @param int $accountId
		 * @return string
		 */
		function UpdateMailboxSize($size, $accountId)
		{
			$sql = 'UPDATE %sawm_accounts
					SET mailbox_size = %d
					WHERE id_acct = %d AND mailing_list = 0';
			
			return sprintf($sql, $this->GetPrefix(), $size, $accountId);
		}

		/**
		 * @param int $accountId
		 * @return string
		 */
		function SelectMailboxesSize($userId)
		{
			$sql = 'SELECT SUM(mailbox_size) AS mailboxes_size
					FROM %sawm_accounts WHERE id_user = %d AND mailing_list = 0';
			
			return sprintf($sql, $this->GetPrefix(), $userId);
		}
		
		/**
		 * @param string $fieldName
		 * @return string
		 */
		function GetDateFormat($fieldName)
		{
			return CDateTime::GetMySqlDateFormat($fieldName);
		}
		
		/**
		 * @param array $intUids
		 * @param Folder $folder
		 * @param int $sAccountId
		 * @return string
		 */
		function LoadMessageHeadersByIntUids($intUids, $folder, $sAccountId)
		{
			$sql = 'SELECT id_msg, int_uid AS uid, id_folder_db, from_msg, to_msg, cc_msg,
							bcc_msg, subject, %s AS nmsg_date, size, priority, x_spam,
							attachments, seen, flagged, deleted, replied, forwarded, grayed, charset, sensitivity
					FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d 
						AND int_uid '.$this->_inOrNot($intUids);

			$str_intUids = implode(',', $intUids);

			return sprintf($sql, CDateTime::GetMySqlDateFormat('msg_date'),
								$this->GetPrefix(), $sAccountId, $folder->IdDb, $str_intUids);
		}

		/**
		 * @param string $condition
		 * @param FolderCollection $folders
		 * @param bool $inHeadersOnly
		 * @param CAccount $account
		 * @return string
		 */
		function SearchMessagesCount($condition, $folders, $inHeadersOnly, $account)
		{
			$foldersId = array();
			$_foldersKeys = array_keys($folders->Instance());
			foreach ($_foldersKeys as $key)
			{
				$folder =& $folders->Get($key);
				if (!$folder->Hide)
				{
					$foldersId[] = $folder->IdDb;
				}
				unset($folder);
			}
			unset($folders);
			
	  		$filter = '';
	  		$asc = true;
	  		
	  		$this->_setSortOrder($account->DefaultOrder, $filter, $asc);
			
	  		$condition = $this->_escapeString('%'.$condition.'%');
	  		
	  		$str_foldersId = implode(',', $foldersId);
	  		
			if ($inHeadersOnly)
			{
				$sql = 'SELECT COUNT(id) AS msg_count
						FROM %sawm_messages
						WHERE id_acct = %d AND id_folder_db '.$this->_inOrNot($foldersId).' AND	
							(from_msg LIKE %s OR to_msg LIKE %s OR cc_msg LIKE %s OR bcc_msg
							LIKE %s OR subject LIKE %s)';
				
				return sprintf($sql, $this->GetPrefix(),
									$account->IdAccount, $str_foldersId,
									$condition, $condition, $condition, $condition, $condition);
			}
			else
			{
				$sql = 'SELECT COUNT(id) AS msg_count
						FROM %sawm_messages
						WHERE id_acct = %d AND id_folder_db '.$this->_inOrNot($foldersId).' AND	
							(from_msg LIKE %s OR to_msg LIKE %s OR cc_msg LIKE %s OR bcc_msg
							LIKE %s OR subject LIKE %s OR body_text LIKE %s)';
				
				return sprintf($sql, $this->GetPrefix(),
									$account->IdAccount, $str_foldersId,
									$condition, $condition, $condition, $condition, $condition, $condition);
			}
		}
		
		/**
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function SelectDeletedMessagesId($folder, $account, $pop3EmptyTrash = false)
		{
			$sql = 'SELECT id_msg FROM %sawm_messages
							WHERE id_acct = -%d AND id_folder_db = %d';
			
			switch ($account->IncomingMailProtocol)
			{
				
				case EMailProtocol::POP3:
					$sql = 'SELECT id_msg FROM %sawm_messages
							WHERE id_acct = %d AND id_folder_db = %d';
					break;
					
				case EMailProtocol::IMAP4:
					if ($pop3EmptyTrash)
					{
						$sql = 'SELECT id_msg FROM %sawm_messages
								WHERE id_acct = %d AND id_folder_db = %d';
					}
					else
					{
						$sql = 'SELECT id_msg FROM %sawm_messages
								WHERE id_acct = %d AND id_folder_db = %d AND deleted = 1';
					}
					break;
			}
			
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount, $folder->IdDb);
		}
		
		/**
		 * @param int $accountId
		 * @param array $msgIds
		 * @return string
		 */
		function PurgeAllMessagesBody($msgIds, $accountId)
		{
			$sql = 'DELETE FROM %sawm_messages_body WHERE id_acct = %d AND id_msg '.$this->_inOrNot($msgIds);
			
			return sprintf($sql, $this->GetPrefix(), $accountId, implode(',', $msgIds));
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function SelectForCreateFolder($folder)
		{
			$sql = 'SELECT MAX(fld_order) AS norder
					FROM %sawm_folders
					WHERE id_acct = %d AND id_parent = %d';
       
			return sprintf($sql, $this->GetPrefix(), $folder->IdAcct, $folder->IdParent);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function CreateFolder($folder)
		{
			$sql = 'INSERT INTO %sawm_folders (id_acct, id_parent, type, name, full_path, 
							sync_type, hide, fld_order, flags)
					VALUES (%d, %d, %d, %s, %s, %d, %d, %d, %s)';

			return sprintf($sql, $this->GetPrefix(), $folder->IdAcct,
									$folder->IdParent, $folder->Type,
									$this->_escapeString($folder->Name.'#'),
									$this->_escapeString($folder->FullName.'#'),
									$folder->SyncType, $folder->Hide,
									$folder->FolderOrder,
									$this->_escapeString($folder->Flags));
		}

		/**
		 *
		 * @return <type> 
		 */
		function SelectLastFunabolCronRun()
		{
			$sql = 'SELECT run_date FROM %sacal_awm_fnbl_runs ORDER BY id_run DESC LIMIT 1';
			return sprintf($sql, $this->GetPrefix());
		}

		function WriteLastFunambolCronRun( $date )
		{
			$sql = 'INSERT INTO %sacal_awm_fnbl_runs (run_date) VALUES (%s)';
			return sprintf($sql, $this->GetPrefix(),$this->_escapeString($date));
		}
	}
		
	class MySqlCommandCreator extends CommandCreator
	{
		function MySqlCommandCreator($prefix)
		{
			CommandCreator::CommandCreator(QUOTE_ESCAPE, $prefix);
		}
		
		/**
		 * @access protected
		 * @param string $bin
		 * @return string
		 */
		function _escapeBin($bin)
		{
			return function_exists('mysql_real_escape_string')
				? '\''.@mysql_real_escape_string($bin).'\'' 
				: '\''.addslashes($bin).'\'';
		}
		
		/**
		 * @param string $fieldName
		 * @return string
		 */
		function GetDateFormat($fieldName)
		{
			return CDateTime::GetMySqlDateFormat($fieldName);
		}

		function UpdateDateFormat($fieldValue)
		{
			return $this->_escapeString($fieldValue);
		}
		
		/**
		 * @param Filter $filter
		 * @return string
		 */
		function InsertFilter($filter)
		{
			$sql = 'INSERT INTO %sawm_filters (id_acct, `field`, `condition`, filter, `action`, id_folder, applied)
					VALUES (%d, %d, %d, %s, %d, %d, %d)';
					
			return sprintf($sql, $this->GetPrefix(), $filter->IdAcct,
									$filter->Field, $filter->Condition,
									$this->_escapeString($filter->Filter),
									$filter->Action, $filter->IdFolder, $filter->Applied);
		}
		
		/**
		 * @param Filter $filter
		 * @return string
		 */
		function UpdateFilter($filter)
		{
			$sql = 'UPDATE %sawm_filters SET
						`field` = %d, `condition` = %d, filter = %s, `action` = %d, `applied` = %d,	id_folder = %d
					WHERE id_filter = %d AND id_acct = %d';

			return sprintf($sql, $this->GetPrefix(), $filter->Field,
									$filter->Condition,	$this->_escapeString($filter->Filter),
									$filter->Action, $filter->Applied, $filter->IdFolder,
									$filter->Id, $filter->IdAcct);			
		}
		
		/**
		 * @param string $accountId
		 * @return string
		 */
		function GetFolders($accountId)
		{
			$sql = 'SELECT p.id_folder, p.id_parent, p.type, p.name, p.full_path, p.sync_type, p.hide, p.fld_order, p.flags,
	COUNT(messages.id) AS message_count, COUNT(messages_unread.seen) AS unread_message_count,
	SUM(messages.size) AS folder_size, MAX(folder_level) AS level
FROM (%sawm_folders as n, %sawm_folders_tree as t, %sawm_folders as p)
LEFT OUTER JOIN %sawm_messages AS messages ON p.id_folder = messages.id_folder_db
LEFT OUTER JOIN %sawm_messages AS messages_unread ON
	p.id_folder = messages_unread.id_folder_db AND
	messages.id = messages_unread.id AND messages_unread.seen = 0
WHERE n.id_parent = -1
	AND n.id_folder = t.id_parent
	AND t.id_folder = p.id_folder
	AND p.id_acct = %d
GROUP BY p.id_folder
ORDER BY p.fld_order';			
			
			return sprintf($sql, $this->GetPrefix(), $this->GetPrefix(), $this->GetPrefix(),
									$this->GetPrefix(), $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param Folder $folder
		 * @return string
		 */
		function SelectSubFoldersId($folder)
		{
			$sql = 'SELECT c.id_folder
					FROM (%sawm_folders AS n, %sawm_folders_tree AS t, %sawm_folders AS c)
					WHERE n.id_folder = %d AND n.id_folder = t.id_parent AND t.id_folder = c.id_folder';

			return sprintf($sql, $this->GetPrefix(), $this->GetPrefix(), 
									$this->GetPrefix(), $folder->IdDb);
		}
		
		/**
		 * @param int $pageNumber
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function LoadMessageHeaders($pageNumber, $folder, $account)
		{
	  		$filter = '';
	  		$asc = true;
	  		
	  		$this->_setSortOrder($account->DefaultOrder, $filter, $asc);
	  		
			/* read messages from db */
			$sql = 'SELECT id_msg, %s AS uid, id_folder_db, from_msg, to_msg, cc_msg,
							bcc_msg, subject, %s AS nmsg_date, size, priority, x_spam,
							attachments, seen, flagged, deleted, replied, forwarded, grayed, charset, sensitivity
					FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d
					ORDER BY %s %s
					LIMIT %d, %d';

			return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
								CDateTime::GetMySqlDateFormat('msg_date'),
								$this->GetPrefix(),
								$account->IdAccount, $folder->IdDb,
								$filter, ($asc)?'ASC':'DESC',
								($pageNumber - 1) * $account->User->MailsPerPage, $account->User->MailsPerPage);
		}

		/**
		 *
		 * @param string $condition
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function SearchMessagesUids($condition, $folder, $account)
		{
			$filter = '';
	  		$asc = true;

	  		$this->_setSortOrder($account->DefaultOrder, $filter, $asc);

			$sql = 'SELECT %s AS uid FROM %sawm_messages
						WHERE id_acct = %d AND id_folder_db = %d
						ORDER BY %s %s';

			return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
								$this->GetPrefix(),
								$account->IdAccount, $folder->IdDb,
								$filter, ($asc) ? 'ASC' : 'DESC');
		}
		
		/**
		 * @param int $pageNumber
		 * @param string $condition
		 * @param FolderCollection $folders
		 * @param bool $inHeadersOnly
		 * @param CAccount $account
		 * @return string
		 */
		function SearchMessages($pageNumber, $condition, $folders, $inHeadersOnly, $account)
		{
			$foldersId = array();
			$_foldersKeys = array_keys($folders->Instance());
			foreach ($_foldersKeys as $key)
			{
				$folder =& $folders->Get($key);
				if (!$folder->Hide)
				{
					$foldersId[] = $folder->IdDb;
				}
				unset($folder);
			}
			unset($folders, $_foldersKeys);
			
	  		$filter = '';
	  		$asc = true;
	  		
	  		$this->_setSortOrder($account->DefaultOrder, $filter, $asc);
			
	  		$condition = $this->_escapeString('%'.$condition.'%');
	  		$str_foldersId = implode(',', $foldersId);
	  		
			if ($inHeadersOnly)
			{
				$sql = 'SELECT id_msg, %s AS uid, id_folder_db, from_msg, to_msg, cc_msg,
							bcc_msg, subject, %s AS nmsg_date, size, priority, x_spam,
							attachments, seen, flagged, deleted, replied, forwarded, grayed, charset, sensitivity
						FROM %sawm_messages
						WHERE id_acct = %d AND id_folder_db '.$this->_inOrNot($foldersId).' AND	
							(from_msg LIKE %s OR to_msg LIKE %s OR cc_msg LIKE %s OR bcc_msg
							LIKE %s OR subject LIKE %s)
						ORDER BY %s %s
						LIMIT %d, %d';
				
				return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
									CDateTime::GetMySqlDateFormat('msg_date'),
									$this->GetPrefix(),
									$account->IdAccount, $str_foldersId,
									$condition, $condition, $condition, $condition, $condition,
									$filter, ($asc)?'ASC':'DESC',
									($pageNumber - 1) * $account->User->MailsPerPage, $account->User->MailsPerPage);
			}
			else
			{
				$sql = 'SELECT id_msg, %s AS uid, id_folder_db, from_msg, to_msg, cc_msg,
							bcc_msg, subject, %s AS nmsg_date, size, priority, x_spam,
							attachments, seen, flagged, deleted, replied, forwarded, grayed, charset, sensitivity
						FROM %sawm_messages
						WHERE id_acct = %d AND id_folder_db '.$this->_inOrNot($foldersId).' AND	
							(from_msg LIKE %s OR to_msg LIKE %s OR cc_msg LIKE %s OR bcc_msg
							LIKE %s OR subject LIKE %s OR body_text LIKE %s)
						ORDER BY %s %s
						LIMIT %d, %d';
				
				return sprintf($sql, $this->_getMsgIdUidFieldName(true, $account->IncomingMailProtocol),
									CDateTime::GetMySqlDateFormat('msg_date'),
									$this->GetPrefix(),
									$account->IdAccount, $str_foldersId,
									$condition, $condition, $condition, $condition, $condition, $condition,
									$filter, ($asc)?'ASC':'DESC',
									($pageNumber - 1) * $account->User->MailsPerPage, $account->User->MailsPerPage);
			}
		}
		
		/**
		 * @param int $accountId
		 * @return string
		 */
		function SelectFilters($accountId)
		{
			$sql = 'SELECT id_filter, `field`, `condition`, `filter`, `action`, id_folder, `applied`
					FROM `%sawm_filters`
					WHERE id_acct = %d
					ORDER BY `action`';
			return sprintf($sql, $this->GetPrefix(), $accountId);
		}
		
		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @param CAccount $account
		 * @return string
		 */
		function DeleteMessagesBody($messageIndexSet, $indexAsUid, $folder, $account)
		{
			$sql = 'DELETE %sawm_messages_body
					FROM %sawm_messages_body, %sawm_messages
					WHERE %sawm_messages.id_acct = %d AND %sawm_messages.id_folder_db = %d 
							AND %sawm_messages_body.id_acct = %sawm_messages.id_acct 
							AND %sawm_messages_body.id_msg = %sawm_messages.id_msg 
							AND %sawm_messages.%s '.$this->_inOrNot($messageIndexSet);
			
			return sprintf($sql, $this->GetPrefix(), $this->GetPrefix(),
								 $this->GetPrefix(), $this->GetPrefix(),
								$account->IdAccount, $this->GetPrefix(), $folder->IdDb,
								$this->GetPrefix(), $this->GetPrefix(),
								$this->GetPrefix(), $this->GetPrefix(),
								$this->GetPrefix(),
								$this->_getMsgIdUidFieldName($indexAsUid, $account->IncomingMailProtocol),
								$this->_quoteUids($messageIndexSet, $indexAsUid, $account->IncomingMailProtocol));
		}
		
		/**
		 * @param int $id
		 * @return string
		 */
		function DeleteFolderTreeById($id)
		{
			$sql = 'DELETE %1$sawm_folders_tree 
						FROM %1$sawm_folders, %1$sawm_folders_tree
						WHERE %1$sawm_folders.id_folder = %1$sawm_folders_tree.id_folder 
						AND %1$sawm_folders.id_acct = %2$d';

			return sprintf($sql, $this->GetPrefix(), $id);
		}
		
		/**
		 * @param int $id
		 * @return string
		 */
		function DeleteCalendarEvents($id)
		{
			$sql = 'DELETE %1$sacal_events
						FROM %1$sacal_events, %1$sacal_calendars
						WHERE %1$sacal_events.calendar_id = %1$sacal_calendars.calendar_id
						AND %1$sacal_calendars.user_id = %2$d';

			return sprintf($sql, $this->GetPrefix(), $id);
		}
		
		/**
		 * @param CAccount $account
		 * @return string
		 */
		function SelectExpiredMessageUids($account)
		{
			$sql = 'SELECT str_uid FROM %sawm_messages
					WHERE id_acct = %d AND DATE_ADD(msg_date, INTERVAL %d DAY) < CURDATE()';
			
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount, $account->MailsOnServerDays);
		}
		
		/**
		 * @param CAccount $account
		 * @param Folder $folder
		 * @param int $_day_cnt
		 * @return string
		 */
		function SelectExpiredMessageUidsInFolder($account, $folder, $_day_cnt)
		{
			$sql = 'SELECT id_msg, str_uid FROM %sawm_messages
					WHERE id_acct = %d AND id_folder_db = %d AND DATE_ADD(msg_date, INTERVAL %d DAY) < CURDATE()';
			
			return sprintf($sql, $this->GetPrefix(), $account->IdAccount, $folder->IdDb, $_day_cnt);
		}
	}
