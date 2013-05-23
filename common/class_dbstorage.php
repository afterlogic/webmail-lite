<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/class_mailstorage.php');
	require_once(WM_ROOTPATH.'common/class_commandcreator.php');
	require_once(WM_ROOTPATH.'common/class_filesystem.php');
	require_once(WM_ROOTPATH.'common/class_filters.php');
	require_once(WM_ROOTPATH.'common/class_convertutils.php');
	require_once(WM_ROOTPATH.'common/class_objectcache.php');

	define('DBTABLE_A_USERS', 'a_users');
	define('DBTABLE_AWM_SETTINGS', 'awm_settings');
	define('DBTABLE_AWM_MESSAGES', 'awm_messages');
	define('DBTABLE_AWM_MESSAGES_BODY', 'awm_messages_body');
	define('DBTABLE_AWM_READS', 'awm_reads');
	define('DBTABLE_AWM_ACCOUNTS', 'awm_accounts');
	define('DBTABLE_AWM_ADDR_GROUPS', 'awm_addr_groups');
	define('DBTABLE_AWM_ADDR_BOOK', 'awm_addr_book');
	define('DBTABLE_AWM_ADDR_GROUPS_CONTACTS', 'awm_addr_groups_contacts');
	define('DBTABLE_AWM_FOLDERS', 'awm_folders');
	define('DBTABLE_AWM_FOLDERS_TREE', 'awm_folders_tree');
	define('DBTABLE_AWM_FILTERS', 'awm_filters');
	define('DBTABLE_AWM_TEMP', 'awm_temp');
	define('DBTABLE_AWM_SENDERS', 'awm_senders');
	define('DBTABLE_AWM_COLUMNS', 'awm_columns');
	define('DBTABLE_AWM_TEMPFILES', 'awm_tempfiles');
	define('DBTABLE_AWM_LOGS', 'awm_logs');
	define('DBTABLE_AWM_MAILALIASES', 'awm_mailaliases');
	define('DBTABLE_AWM_MAILINGLISTS', 'awm_mailinglists');

	define('DBTABLE_CAL_USERS_DATA', 'acal_users_data');
	define('DBTABLE_CAL_CALENDARS', 'acal_calendars');
	define('DBTABLE_CAL_EVENTS', 'acal_events');
	define('DBTABLE_CAL_SHARING', 'acal_sharing');
	define('DBTABLE_CAL_PUBLICATIONS', 'acal_publications');

	define('DBTABLE_AWM_MESSAGES_INDEX', 'awm_messages_index');
	define('DBTABLE_AWM_MESSAGES_BODY_INDEX', 'awm_messages_body_index');

	/**
	 * @abstract
	 */
	class DbStorage extends MailStorage
	{
		/**
		 * @access private
		 * @var short
		 */
		var $_escapeType;

		/**
		 * @access protected
		 * @var DbMySql
		 */
		var $_dbConnection;

		/**
		 * @access protected
		 * @var MySqlCommandCreator
		 */
		var $_commandCreator;

		/**
		 * @param CAccount $account
		 * @param api_settings $settings = null
		 * @return MailServerStorage
		 */
		function DbStorage(&$account, $settings = null)
		{
			MailStorage::MailStorage($account, $settings);
		}

		/**
		 * @return bool
		 */
		function Connect()
		{
			if (!USE_DB || $this->_dbConnection->IsConnected())
			{
				return true;
			}

			if ($this->_dbConnection->Connect())
			{
				return true;
			}
			else
			{
				setGlobalError(defined('PROC_CANT_LOAD_DB') ? PROC_CANT_LOAD_DB : 'Can\'t connect to database.');
				return false;
			}
		}

		/**
		 * @return bool
		 */
		function Disconnect()
		{
			return USE_DB ? $this->_dbConnection->Disconnect() : true;
		}

		/**
		 * @param array $emailsString
		 * @return array/bool
		 */
		function SelectExistEmails(&$account, $emailsArray)
		{
			$returnArray = array();

			if (!$this->_dbConnection->Execute($this->_commandCreator->SelectExistEmails($account, $emailsArray)))
			{
				return false;
			}

			while (false !== ($row = $this->_dbConnection->GetNextRecord()))
			{
				if ($row)
				{
					if (!empty($row->h_email))
					{
						$returnArray[] = $row->h_email;
					}
					if (!empty($row->b_email))
					{
						$returnArray[] = $row->b_email;
					}
					if (!empty($row->other_email))
					{
						$returnArray[] = $row->other_email;
					}
				}
			}
			return $returnArray;
		}

		/**
		 * @param	string	$email
		 * @param	int		$idUser
		 * @return	bool
		 */
		function SelectSenderSafetyByEmail($email, $idUser)
		{
			static $safetyCache = array();

			if (isset($safetyCache[$email.$idUser]))
			{
				return $safetyCache[$email.$idUser];
			}
			else if ($this->_dbConnection->Execute($this->_commandCreator->SelectSendersByEmail($email, $idUser)))
			{
				$row = $this->_dbConnection->GetNextRecord();
				if ($row)
				{
					$safetyCache[$email.$idUser] = (bool) $row->safety;
					return (bool) $row->safety;
				}
				$safetyCache[$email.$idUser] = false;
			}

			return false;
		}

		/**
		 * @param	string	$email
		 * @param	bool	$safety
		 * @param	int		$idUser
		 * @return	bool
		 */
		function SetSenders($email, $safety, $idUser)
		{
			if (!$this->_dbConnection->Execute($this->_commandCreator->SelectSendersByEmail($email, $idUser)))
			{
				return false;
			}

			if ($this->_dbConnection->ResultCount() > 0)
			{
				$row = &$this->_dbConnection->GetNextRecord();
				if (is_object($row) && isset($row->safety) && $row->safety != $safety)
				{
					if (!$this->_dbConnection->Execute($this->_commandCreator->UpdateSenders($email, $safety, $idUser)))
					{
						return false;
					}
				}
			}
			else
			{
				if (!$this->_dbConnection->Execute($this->_commandCreator->InsertSenders($email, $safety, $idUser)))
				{
					return false;
				}
			}

			return true;
		}

		/**
		 * @param Array $messageIndexSet
		 * @param Boolean $indexAsUid
		 * @param Folder $folder
		 * @param Int $flags
		 * @param Account $account
		 * @return unknown
		 */
		function UpdateMessageFlags($messageIndexSet, $indexAsUid, &$folder, $flags, &$account)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->UpdateMessageFlags($messageIndexSet, $indexAsUid, $folder, $flags, $account));
		}

		/**
		 * @param FolderCollection $folders
		 * @return bool
		 */
		function CreateFolders(&$folders)
		{
			$result = true;
			if ($folders == null)
			{
				return $result;
			}

			for ($i = 0, $count = $folders->Count(); $i < $count; $i++)
			{
				$folder =& $folders->Get($i);

				$result &= $this->CreateFolder($folder);

				if (!is_null($folder->SubFolders))
				{
					for ($j = 0, $cc = $folder->SubFolders->Count(); $j < $cc; $j++)
					{
						$subFolder =& $folder->SubFolders->Get($j);
						$subFolder->IdParent = $folder->IdDb;
						unset($subFolder);
					}
					$result &= $this->CreateFolders($folder->SubFolders);
				}

				unset($folder);
			}

			return $result;
		}

		/**
		 * @return FolderCollection
		 */
		function &GetFolders()
		{
			if (!$this->_dbConnection->Execute($this->_commandCreator->GetFolders($this->Account->IdAccount)))
			{
				$null = null;
				return $null;
			}

			$row = null;
			$folders = array();
			while (false !== ($row = $this->_dbConnection->GetNextRecord()))
			{
				$folder = new Folder($this->Account->IdAccount, (int) $row->id_folder,
										substr($row->full_path, 0, -1), substr($row->name, 0, -1));

				$folder->IdParent = $row->id_parent;
				$folder->Type = (int) $row->type;
				$folder->SyncType = (int) $row->sync_type;
				$folder->Hide = (bool) abs($row->hide);
				$folder->FolderOrder = (int) $row->fld_order;
				$folder->MessageCount = (int) $row->message_count;
				$folder->UnreadMessageCount = (int) $row->unread_message_count;
				$folder->Size = api_Utils::GetGoodBigInt($row->folder_size);
				$folder->Level = (int) $row->level;
				$folder->Flags = (string) $row->flags;
				$folders[] =& $folder;
				unset($folder);
			}

			$folderCollection = new FolderCollection();
			$this->_addLevelToFolderTree($folderCollection, $folders);

			return $folderCollection;
		}

		/**
		 * @param Folder $folder
		 * @return bool
		 */
		function DeleteFolder(&$folder)
		{
			$result = true;
			$result &= $this->_dbConnection->Execute($this->_commandCreator->DeleteFolder($folder));
			$result &= $this->_dbConnection->Execute($this->_commandCreator->DeleteFolderTree($folder));
			$result &= $this->_dbConnection->Execute($this->_commandCreator->DeleteMessagesHeadersFromFolder($folder));
			return $result;
		}

		/**
		 * @param Folder $folder
		 * @param string $newName
		 * @return bool
		 */
		function RenameFolder(&$folder, $newName)
		{
			$result = $this->_dbConnection->Execute($this->_commandCreator->RenameFolder($folder, $newName));

			$foldersId = array();
			if ($this->_dbConnection->Execute($this->_commandCreator->SelectSubFoldersId($folder)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$foldersId[] = $row->id_folder;
				}
			}

			if (count($foldersId) > 0)
			{
				$result &= $this->_dbConnection->Execute($this->_commandCreator->RenameSubFoldersPath($folder, $foldersId, $newName));
			}

			return $result;
		}

		/**
		 * @param Folder $folder
		 * @return bool
		 */
		function UpdateFolder(&$folder)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->UpdateFolder($folder));
		}

		/**
		 * @param Folder $folder
		 * @return bool
		 */
		function GetFolderMessageCount(&$folder)
		{
			if (!$this->_dbConnection->Execute($this->_commandCreator->GetFolderMessageCountAll($folder)))
			{
				return false;
			}

			$row = $this->_dbConnection->GetNextRecord();
			if ($row)
			{
				$folder->MessageCount = ($row->message_count > 0) ? $row->message_count : 0;
			}
			else
			{
				$folder->MessageCount = 0;
			}

			if (!$this->_dbConnection->Execute($this->_commandCreator->GetFolderMessageCountUnread($folder)))
			{
				return false;
			}

			$row = $this->_dbConnection->GetNextRecord();
			if ($row)
			{
				$folder->UnreadMessageCount = ($row->unread_message_count > 0) ? $row->unread_message_count : 0;
			}
			else
			{
				$folder->UnreadMessageCount = 0;
			}

			return true;
		}

		/**
		 * @param int $id
		 * @param int $id_acct
		 * @return string|false
		 */
		function GetFolderFullName($id, $id_acct)
		{
			if ($this->_dbConnection->Execute($this->_commandCreator->GetFolderFullName($id, $id_acct)))
			{
				$row = $this->_dbConnection->GetNextRecord();
				if ($row)
				{
					return substr($row->full_path, 0, -1);
				}
			}
			return false;
		}

		/**
		 * @param Folder $folder
		 * @param bool $useCache = false
		 */
		function GetFolderInfo(&$folder, $useCache = false)
		{
			if ($folder)
			{
				$row = null;
				$_sql = $this->_commandCreator->GetFolderInfo($folder);

				$_cacher =& CObjectCache::CreateInstance();
				if ($useCache && $_cacher->Has('sql='.$_sql))
				{
					$row =& $_cacher->Get('sql='.$_sql);
				}
				else if ($this->_dbConnection->Execute($_sql))
				{
					$row = $this->_dbConnection->GetNextRecord();
					$_cacher->Set('sql='.$_sql, $row);
				}

				if ($row)
				{
					$folder->FullName = substr($row->full_path, 0, -1);
					$folder->Name = substr($row->name, 0, -1);
					$folder->Type = $row->type;
					$folder->SyncType = $row->sync_type;
					$folder->Hide = (bool) abs($row->hide);
					$folder->FolderOrder = (int) $row->fld_order;
					$folder->Flags = (string) $row->flags;
					$folder->IdParent = (int) $row->id_parent;
					return true;
				}
			}
			return false;
		}

		/**
		 * @param Folder $folder
		 * @return int
		 */
		function GetFolderChildCount(&$folder)
		{
			$result = -1;
			if ($this->_dbConnection->Execute($this->_commandCreator->GetFolderChildCount($folder)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$result = ($row->child_count != null) ? $row->child_count : 0;
				}
			}

			return $result;
		}

		/**
		 * @param short $type
		 * @return short
		 */
		function GetFolderSyncType($type)
		{
			$result = -1;
			if (!$this->_dbConnection->Execute($this->_commandCreator->GetFolderSyncType($this->Account->IdAccount, $type)))
			{
				return $result;
			}

			$row = $this->_dbConnection->GetNextRecord();
			if ($row)
			{
				$result = $row->sync_type;
			}
			return $result;
		}

		/**
		 * @param short $type
		 * @return short
		 */
		function GetFolderSyncTypeByIdAcct($idAcct, $type)
		{
			$result = -1;
			if (!$this->_dbConnection->Execute($this->_commandCreator->GetFolderSyncType($idAcct, $type)))
			{
				return $result;
			}

			$row = $this->_dbConnection->GetNextRecord();
			if ($row)
			{
				$result = $row->sync_type;
			}
			return $result;
		}

		/**
		 * @access private
		 * @param FolderCollection $folderCollection
		 * @param Array $folders
		 * @param string $rootPrefix optional
		 */
		function _addLevelToFolderTree(&$folderCollection, &$folders, $rootPrefix = '', $isToFolder = false)
		{
			$prefixLen = strlen($rootPrefix);
			$foldersCount = count($folders);
			for ($i = 0; $i < $foldersCount; $i++)
			{
				$folderFullName = $folders[$i]->FullName;
				if ($rootPrefix != $folderFullName && strlen($folderFullName) > $prefixLen &&
					substr($folderFullName, 0, $prefixLen) == $rootPrefix &&
					strpos($folderFullName, $this->Account->Delimiter, $prefixLen + 1) === false)
				{
					$folderObj =& $folders[$i];
					$isTo = ($isToFolder || $folderObj->Type == FOLDERTYPE_Drafts || $folderObj->Type == FOLDERTYPE_SentItems);

					$folderObj->ToFolder = $isTo;
					$folderCollection->Add($folderObj);

					$newCollection = new FolderCollection();
					$this->_addLevelToFolderTree($newCollection, $folders, $folderFullName.$this->Account->Delimiter, $isTo);
					if ($newCollection->Count() > 0)
					{
						$folderObj->SubFolders = $newCollection;
					}
					unset($folderObj, $newCollection);
				}
			}
		}

		/**
		 * @param WebMailMessage $message
		 * @param Folder $folder
		 * @param bool $downloaded
		 * @return bool
		 */
		function SaveMessageHeader(&$message, &$folder, $downloaded, $_id_msg = null)
		{
			if (null === $_id_msg)
			{
				$_id_msg = $this->SelectLastIdMsg();
			}

			$message->IdMsg = ($_id_msg) ? $_id_msg : 1;
			$result = $this->_dbConnection->Execute(
				$this->_commandCreator->SaveMessageHeader($message, $folder, $downloaded, $this->Account));

			$message->IdDb = $this->_dbConnection->GetLastInsertId();

			return $result;
		}

		/**
		 * @param WebMailMessage $message
		 * @param Folder $folder
		 * @return bool
		 */
		function UpdateMessageHeader(&$message, &$folder)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->UpdateMessageHeader($message, $folder, $this->Account));
		}

		/**
		 * @param WebMailMessageCollection $messages
		 * @param Folder $folder
		 * @param bool $downloaded
		 * @return bool
		 */
		function SaveMessageHeaders(&$messages, &$folder, $downloaded)
		{
			$result = true;
			for ($i = 0, $count = $messages->Count(); $i < $count; $i++)
			{
				$msg =& $messages->Get($i);
				$result &= $this->SaveMessageHeader($msg, $folder, $downloaded);
				unset($msg);
			}
			return $result;
		}

		/**
		 * @param WebMailMessage $message
		 * @param Folder $folder
		 * @return int|false
		 */
		function MessageSize(&$message, &$folder)
		{
			$result = -1;
			if (!$this->_dbConnection->Execute($this->_commandCreator->GetMessageSize($message, $folder, $this->Account->IdAccount)))
			{
				return false;
			}

			$row = $this->_dbConnection->GetNextRecord();
			if ($row)
			{
				$result = $row->size;
			}

			return $result;
		}

		/**
		 * @param WebMailMessage $message
		 * @param Folder $folder
		 * @return bool
		 */
		function UpdateMessage(&$message, &$folder)
		{
			if (!$this->UpdateMessageHeader($message, $folder, true))
			{
				return false;
			}

			$result = true;

			if ($this->_settings->GetConf('WebMail/StoreMailsInDb'))
			{
				$result = $this->_dbConnection->Execute($this->_commandCreator->UpdateBody($message, $this->Account->IdAccount));
			}
			else
			{
				$fs = new FileSystem(INI_DIR.'/mail', strtolower($this->Account->Email), $this->Account->IdAccount);
				$result = $fs->UpdateMessage($message, $folder);
			}

			if (!$result)
			{
				setGlobalError(PROC_CANT_SAVE_MSG);
			}

			return $result;
		}

		/**
		 * @param WebMailMessage $message
		 * @param Folder $folder
		 * @return bool
		 */
		function SaveMessage(&$message, &$folder)
		{
			if (!$this->SaveMessageHeader($message, $folder, true))
			{
				return false;
			}

			$result = true;

			if ($this->_settings->GetConf('WebMail/StoreMailsInDb'))
			{
				/* save body */
				$result = $this->_dbConnection->Execute($this->_commandCreator->SaveBody($message, $this->Account->IdAccount));
			}
			else
			{
				$fs = new FileSystem(INI_DIR.'/mail', strtolower($this->Account->Email), $this->Account->IdAccount);
				$result = $fs->SaveMessage($message, $folder);
			}

			if (!$result)
			{
				setGlobalError(PROC_CANT_SAVE_MSG);
				$tempArray = array($message->IdMsg);
				$this->DeleteMessages($tempArray, false, $folder);
			}

			return $result;
		}

		/**
		 * @param WebMailMessageCollection $messages
		 * @param Folder $folder
		 * @return bool
		 */
		function SaveMessages(&$messages, &$folder)
		{
			$result = true;
			for ($i = 0, $count = $messages->Count(); $i < $count; $i++)
			{
				$mess =& $messages->Get($i);
				if ($mess)
				{
					$result &= $this->SaveMessage($mess, $folder);
				}
				else
				{
					$result = false;
				}
			}
			return $result;
		}

		/**
		 *
		 * @param array $intUids
		 * @param Folder $folder
		 * @return WebMailMessageCollection
		 */
		function &LoadMessageHeadersByIntUids($intUids, $folder)
		{
			$mailCollection = $msg = null;
			if (!$this->_dbConnection->Execute($this->_commandCreator->LoadMessageHeadersByIntUids($intUids, $folder, $this->Account->IdAccount)))
			{
				return $mailCollection;
			}

			$mailCollection = new WebMailMessageCollection();
			$pre_array = array();

			while (false !== ($row = $this->_dbConnection->GetNextRecord()))
			{
				$msg =& $this->_rowToWebMailMessage($row);
				$pre_array[$row->uid] =& $msg;
				unset($msg);
			}

			foreach ($intUids as $uid)
			{
				if (isset($pre_array[$uid]))
				{
					$mailCollection->Add($pre_array[$uid]);
				}
			}

			unset($pre_array);

			return $mailCollection;
		}

		/**
		 * @param int $pageNumber
		 * @param Folder $folder
		 * @return WebMailMessageCollection
		 */
		function &LoadMessageHeaders($pageNumber, &$folder)
		{
			$mailCollection = null;
			if (!$this->_dbConnection->Execute($this->_commandCreator->LoadMessageHeaders($pageNumber, $folder, $this->Account)))
			{
				return $mailCollection;
			}

			$mailCollection = new WebMailMessageCollection();

			while (false !== ($row = $this->_dbConnection->GetNextRecord()))
			{
				$msg =& $this->_rowToWebMailMessage($row);
				$mailCollection->Add($msg);
				unset($msg);
			}

			return $mailCollection;
		}

		/**
		 * @param obj $row
		 * return WebMailMessage|null
		 */
		function &_rowToWebMailMessage($row)
		{
			$msg = null;
			if ($row)
			{
				$msg = new WebMailMessage();
				$msg->SetFromAsString($row->from_msg);
				$msg->SetToAsString($row->to_msg);
				$msg->SetCcAsString($row->cc_msg);
				$msg->SetBccAsString($row->bcc_msg);

				$date = new CDateTime();
				$date->SetFromANSI($row->nmsg_date);
				$msg->SetDate($date);

				$msg->SetSubject($row->subject);

				$msg->IdMsg = $row->id_msg;
				$msg->IdFolder = $row->id_folder_db;
				$msg->Uid = $row->uid;
				$msg->Size = $row->size;
				$msg->DbPriority = $row->priority;
				$msg->DbXSpam = (bool) abs($row->x_spam);

				$msg->DbHasAttachments = $row->attachments;

				$msg->Sensitivity = $row->sensitivity;

				$msg->Flags = 0;

				if ($row->seen)
				{
					$msg->Flags |= MESSAGEFLAGS_Seen;
				}
				if ($row->flagged)
				{
					$msg->Flags |= MESSAGEFLAGS_Flagged;
				}
				if ($row->deleted)
				{
					$msg->Flags |= MESSAGEFLAGS_Deleted;
				}
				if ($row->replied)
				{
					$msg->Flags |= MESSAGEFLAGS_Answered;
				}
				if ($row->forwarded)
				{
					$msg->Flags |= MESSAGEFLAGS_Forwarded;
				}
				if ($row->grayed)
				{
					$msg->Flags |= MESSAGEFLAGS_Grayed;
				}

				$msg->Charset = $row->charset;
			}

			return $msg;
		}

		function PreLoadMessagesFromDB($messageIndexSet, $indexAsUid, &$folder)
		{
			$_preData = array();
			if (!$this->_dbConnection->Execute($this->_commandCreator->PreLoadMessagesFromDB($messageIndexSet, $indexAsUid, $folder, $this->Account)))
			{
				return null;
			}

			while (false !== ($row = $this->_dbConnection->GetNextRecord()))
			{
				$_preData[$row->id_msg] = array($row->uid, $row->priority, $row->flags, $row->downloaded, $row->size);
			}

			return $_preData;
		}

		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @return WebMailMessageCollection
		 */
		function &LoadMessages(&$messageIndexSet, $indexAsUid, &$folder, $_preData = null)
		{
			$mailCollection = new WebMailMessageCollection();
			if ($this->_settings->GetConf('WebMail/StoreMailsInDb'))
			{
				if (null === $_preData)
				{
					$_preData = $this->PreLoadMessagesFromDB($messageIndexSet, $indexAsUid, $folder);
				}

				$_msgArray = array_keys($_preData);

				/* read messages from db */
				if (!$this->_dbConnection->Execute($this->_commandCreator->LoadMessagesFromDB($_msgArray, $this->Account->IdAccount)))
				{
					return null;
				}

				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					if ($row)
					{
						if (isset($_preData[$row->id_msg]))
						{
							$msg = new WebMailMessage();
							$msg->LoadMessageFromRawBody($row->msg);
							$msg->IdMsg = $row->id_msg;
							$msg->IdFolder = $folder->IdDb;
							$msg->Uid = $_preData[$row->id_msg][0];
							$msg->DbPriority = $_preData[$row->id_msg][1];
							$msg->Flags = $_preData[$row->id_msg][2];
							$msg->Size = strlen($row->msg);
							$mailCollection->Add($msg);
							unset($msg);
						}
					}
				}
			}
			else
			{
				$fs = new FileSystem(INI_DIR.'/mail', strtolower($this->Account->Email), $this->Account->IdAccount);

				if (null === $_preData)
				{
					$_preData = $this->PreLoadMessagesFromDB($messageIndexSet, $indexAsUid, $folder);
				}

				foreach ($_preData as $_id_msg => $_varArray)
				{
					$msg =& $fs->LoadMessage($_id_msg, $folder);
					if ($msg !== null)
					{
						$msg->IdMsg = $_id_msg;
						$msg->Uid = $_varArray[0];
						$msg->DbPriority = $_varArray[1];
						$msg->Flags = $_varArray[2];
						$msg->Size = $_varArray[4];

						$mailCollection->Add($msg);
					}
					unset($msg);

				}
			}

			return $mailCollection;
		}

		/**
		 * @param string $messageIndex
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @return WebMailMessage
		 */
		function &LoadMessage($messageIndex, $indexAsUid, &$folder, $_preData = null)
		{
			$messageIndexArray = array($messageIndex);
			if ($this->_settings->GetConf('WebMail/StoreMailsInDb'))
			{
				if (null === $_preData)
				{
					$_preData = $this->PreLoadMessagesFromDB($messageIndexArray, $indexAsUid, $folder);
				}

				$_msgArray = array_keys($_preData);

				$message = null;

				//read messages from db
				if (!$this->_dbConnection->Execute($this->_commandCreator->LoadMessagesFromDB($_msgArray, $this->Account)))
				{
					return $message;
				}

				$row = $this->_dbConnection->GetNextRecord();
				if ($row && isset($_preData[$row->id_msg]))
				{
					$message = new WebMailMessage();
					$message->LoadMessageFromRawBody($row->msg);
					$message->IdMsg = $row->id_msg;
					$message->IdFolder = $folder->IdDb;
					$message->Uid = $_preData[$row->id_msg][0];
					$message->DbPriority = $_preData[$row->id_msg][1];
					$message->Flags = $_preData[$row->id_msg][2];
					$message->Size = strlen($row->msg);
					$message->Downloaded = true;
				}
				else
				{
					setGlobalError(PROC_MSG_HAS_DELETED);
				}
			}
			else
			{
				$fs = new FileSystem(INI_DIR.'/mail', strtolower($this->Account->Email), $this->Account->IdAccount);

				if (null === $_preData)
				{
					$_preData = $this->PreLoadMessagesFromDB($messageIndexArray, $indexAsUid, $folder);
				}

				foreach ($_preData as $_id_msg => $_varArray)
				{
					$message =& $fs->LoadMessage($_id_msg, $folder);
					if ($message != null && is_array($_varArray))
					{
						$message->IdMsg = $_id_msg;
						$message->Uid = $_varArray[0];
						$message->IdFolder = $folder->IdDb;
						$message->DbPriority = $_varArray[1];
						$message->Flags = $_varArray[2];
						$message->Size = $_varArray[4];
						$message->Downloaded = true;
					}
					else
					{
						setGlobalError(PROC_MSG_HAS_DELETED);
					}
					break;
				}
			}

			return $message;
		}

		/**
		 * @param Folder $folder
		 * @return Array
		 */
		function &SelectIdMsgAndUidByIdMsgDesc(&$folder)
		{
			$outData = array();
			if (!$this->_dbConnection->Execute($this->_commandCreator->SelectIdMsgAndUid($folder, $this->Account)))
			{
				return $outData;
			}

			while (($row = $this->_dbConnection->GetNextRecord()) != false)
			{
				$outData[] = array($row->id_msg, $row->uid, $row->flag);
			}

			return $outData;
		}


		/**
		 * @return int
		 */
		function SelectLastIdMsg()
		{
			$idMsg = null;
			if ($this->_dbConnection->Execute($this->_commandCreator->SelectLastIdMsg($this->Account->IdAccount)))
			{
				$row = $this->_dbConnection->GetNextRecord();
				if ($row)
				{
					$idMsg = $row->nid_msg;
				}
			}

			return ($idMsg == null) ? 0 : $idMsg + rand(1, 5);
		}

		/**
		 * @param int $messageId
		 * @param Folder $folder
		 * @return bool
		 */
		function GetMessageDownloadedFlag($messageId, &$folder)
		{
			$downloaded = false;
			if ($this->_dbConnection->Execute($this->_commandCreator->GetMessageDownloadedFlag($messageId, $folder, $this->Account->IdAccount)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$downloaded = (bool) abs($row->downloaded);
				}
			}
			return $downloaded;
		}

		/**
		 * @param int $msgId
		 * @param int $charset
		 * @param WebMailMessage $message
		 * @return bool
		 */
		function UpdateMessageCharset($msgId, $charset, &$message)
		{
			$this->_dbConnection->Execute(
				$this->_commandCreator->UpdateMessageCharset($this->Account, $msgId, $charset, $message));
		}

		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder
		 * @param int $flags
		 * @param short $action
		 * @return bool
		 */
		function SetMessagesFlags(&$messageIndexSet, $indexAsUid, &$folder, $flags, $action)
		{
			return $this->_dbConnection->Execute(
					$this->_commandCreator->SetMessagesFlags($messageIndexSet, $indexAsUid, $folder,
																$flags, $action, $this->Account));
		}

		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $fromFolder
		 * @param Folder $toFolder
		 * @return bool
		 */
		function MoveMessages(&$messageIndexSet, $indexAsUid, &$fromFolder, &$toFolder)
		{
			$result = true;
			if (!$this->_settings->GetConf('WebMail/StoreMailsInDb') &&
					$this->_dbConnection->Execute(
						$this->_commandCreator->SelectDownloadedMessagesIdSet($messageIndexSet, $indexAsUid, $this->Account)))
			{
				$downloadedMsgIdSet = array();

				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$downloadedMsgIdSet[] = $row->id_msg;
				}

				if (count($downloadedMsgIdSet) > 0)
				{
					$fs = new FileSystem(INI_DIR.'/mail', strtolower($this->Account->Email), $this->Account->IdAccount);
					if (!$fs->MoveMessages($downloadedMsgIdSet, $fromFolder, $toFolder))
					{
						CApi::Log('Can\'t move message on file system', ELogLevel::Error);
						//$this->_log->WriteLine('ERROR: Can\'t move message on file system', LOG_LEVEL_ERROR);
						// return false;
					}

				}
			}

			if ($result)
			{
				$result = $this->_dbConnection->Execute(
							$this->_commandCreator->MoveMessages($messageIndexSet, $indexAsUid, $fromFolder,
																	$toFolder, $this->Account));
			}
			else
			{
				CApi::Log('Can\'t save message to DB', ELogLevel::Error);
				// $this->_log->WriteLine('ERROR: Can\'t save message to DB', LOG_LEVEL_ERROR);
			}

			return $result;
		}

		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $fromFolder
		 * @param Folder $toFolder
		 * @return bool
		 */
		function FullMoveMessages(&$messageIndexSet, $indexAsUid, &$fromFolder, &$toFolder)
		{
			if(!$this->_dbConnection->Execute(
				$this->_commandCreator->FullMoveMessages($messageIndexSet, $indexAsUid, $fromFolder, $toFolder, $this->Account)))
			{
				$this->_log->WriteLine('ERROR: Can\'t save message to DB', LOG_LEVEL_ERROR);
				return false;
			}
			return true;
		}

		function MoveMessagesWithUidUpdate(&$messageIndexUidSet, &$fromFolder, &$toFolder)
		{
			$result = true;
			foreach ($messageIndexUidSet as $_id => $_uid)
			{
				if(!$this->_dbConnection->Execute(
					$this->_commandCreator->MoveMessageWithUidUpdate($_id, $_uid, $fromFolder, $toFolder)))
				{
					$this->_log->WriteLine('ERROR: Can\'t move message with uid update', LOG_LEVEL_ERROR);
					$result = false;
				}
			}

			return $result;
		}


		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @param Folder $folder optional
		 * @return bool
		 */
		function DeleteMessages(&$messageIndexSet, $indexAsUid, &$folder)
		{
			if ($this->_settings->GetConf('WebMail/StoreMailsInDb'))
			{
				$this->_dbConnection->Execute(
					$this->_commandCreator->DeleteMessagesBody($messageIndexSet, $indexAsUid, $folder, $this->Account));
			}
			else
			{
				$fs = new FileSystem(INI_DIR.'/mail', strtolower($this->Account->Email), $this->Account->IdAccount);
				$fs->DeleteMessages($messageIndexSet, $folder, true);
			}

			return $this->_dbConnection->Execute($this->_commandCreator->DeleteMessagesHeaders($messageIndexSet, $indexAsUid, $folder, $this->Account));
		}

		/**
		 * @param Folder $folder
		 * @param Account $account
		 * @return bool
		 */
		function ClearDbFolder($folder, $account)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->ClearDbFolder($folder, $account));
		}

		/**
		 * @param Folder $folder
		 * @return bool
		 */
		function PurgeFolder(&$folder, $pop3EmptyTrash = false)
		{
			$result = true;

			if ($this->_settings->GetConf('WebMail/StoreMailsInDb'))
			{
				/* remove messages from db, read messages from file system */
				if (!$this->_dbConnection->Execute($this->_commandCreator->SelectDeletedMessagesId($folder, $this->Account, $pop3EmptyTrash)))
				{
					return false;
				}

				$msgIdSet = array();
				while (false !== ($row =  $this->_dbConnection->GetNextRecord()))
				{
					$msgIdSet[] = $row->id_msg;
				}

				if(count($msgIdSet) > 0)
				{
					$result &= $this->_dbConnection->Execute(
										$this->_commandCreator->PurgeAllMessagesBody($msgIdSet, $this->Account->IdAccount));
					$result &= $this->_dbConnection->Execute(
										$this->_commandCreator->PurgeAllMessageHeaders($folder, $this->Account, $pop3EmptyTrash));

					return $result;
				}
				else
				{
					return true;
				}
			}

			/* read messages from file system */
			if (!$this->_dbConnection->Execute($this->_commandCreator->SelectAllDeletedMsgId($folder, $this->Account, $pop3EmptyTrash)))
			{
				return false;
			}

			$messageIdSet = array();
			while (false !== ($row =  $this->_dbConnection->GetNextRecord()))
			{
				$messageIdSet[] = $row->id_msg;
			}

			if (count($messageIdSet) > 0)
			{
				$fs = new FileSystem(INI_DIR.'/mail', strtolower($this->Account->Email), $this->Account->IdAccount);
				$result &= $fs->DeleteMessages($messageIdSet, $folder);
			}

			return $result && $this->_dbConnection->Execute(
						$this->_commandCreator->PurgeAllMessageHeaders($folder, $this->Account, $pop3EmptyTrash));
		}

		/**
		 * @param Array $messageIndexSet
		 * @param bool $indexAsUid
		 * @return string
		 */
		function &SelectDownloadedMessagesIdSet($messageIndexSet, $indexAsUid)
		{
			$messagesIdSet = array();
			if ($this->_dbConnection->Execute(
					$this->_commandCreator->SelectDownloadedMessagesIdSet($messageIndexSet, $indexAsUid, $this->Account)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$messagesIdSet[] = $row->id_msg;
				}
			}

			return $messagesIdSet;
		}

		/**
		 * @param Folder $folder
		 * @return Array
		 */
		function &SelectAllMessagesUidSetByFolder(&$folder)
		{
			$messagesUidSet = array();
			if ($this->_dbConnection->Execute(
					$this->_commandCreator->SelectAllMessagesUidSetByFolder($folder, $this->Account)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$messagesUidSet[] = $row->uid;
				}
			}

			return $messagesUidSet;
		}

		/**
		 * @param int $accountId
		 * @return FilterCollection
		 */
		function &SelectFilters($accountId, $useCache = false)
		{
			$filters = null;
			$_sql = $this->_commandCreator->SelectFilters($accountId);

			$_cacher =& CObjectCache::CreateInstance();
			if ($useCache && $_cacher->Has('sql='.$_sql))
			{
				$filters =& $_cacher->Get('sql='.$_sql);
			}
			else if ($this->_dbConnection->Execute($_sql, true))
			{
				$filters = new FilterCollection();
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$filter = new Filter();
					$filter->Id = $row->id_filter;
					$filter->IdAcct = $accountId;
					$filter->Field = $row->field;
					$filter->Condition = $row->condition;
					$filter->Filter = $row->filter;
					$filter->Action = $row->action;
					$filter->IdFolder = $row->id_folder;
					$filter->Applied = $row->applied;

					$filters->Add($filter);
					unset($filter);
				}

				if ($useCache)
				{
					$_cacher->Set('sql='.$_sql, $filters);
				}
			}

			return $filters;
		}

		/**
		 * @param Filter $filter
		 * @return bool
		 */
		function InsertFilter(&$filter)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->InsertFilter($filter));
		}

		/**
		 * @param Filter $filter
		 * @return bool
		 */
		function UpdateFilter(&$filter)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->UpdateFilter($filter));
		}

		/**
		 * @param int $filterId
		 * @param int $accountId
		 * @return bool
		 */
		function DeleteFilter($filterId, $accountId)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->DeleteFilter($filterId, $accountId));
		}

		/**
		 * @param int $folderId
		 * @param int $accountId
		 * @return bool
		 */
		function DeleteFolderFilters($folderId, $accountId)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->DeleteFolderFilters($folderId, $accountId));
		}

		/**
		 * @param string $condition
		 * @param Folder $folders
		 * @return array|false
		 */
		function SearchMessagesUids($condition, &$folder)
		{
			$uids = false;

			if ($this->_dbConnection->Execute(
					$this->_commandCreator->SearchMessagesUids($condition, $folder, $this->Account)))
			{
				$uids = array();
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$uids[] = (int) $row->uid;
				}
			}

			return $uids;
		}

		/**
		 * @param string $condition
		 * @param FolderCollection $folders
		 * @param bool $inHeadersOnly
		 * @return int
		 */
		function SearchMessagesCount($condition, &$folders, $inHeadersOnly)
		{
			$mailCollectionCount = 0;

			if ($this->_dbConnection->Execute(
					$this->_commandCreator->SearchMessagesCount(
						$condition, $folders->CreateFolderListFromTree(), $inHeadersOnly, $this->Account)))
			{

				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$mailCollectionCount = $row->msg_count;
				}
			}

			return $mailCollectionCount;
		}

		/**
		 * @param int $pageNumber
		 * @param string $condition
		 * @param FolderCollection $folders
		 * @param bool $inHeadersOnly
		 * @return WebMailMessageCollection
		 */
		function &SearchMessages($pageNumber, $condition, &$folders, $inHeadersOnly)
		{
			$mailCollection = null;
			if ($this->_dbConnection->Execute(
					$this->_commandCreator->SearchMessages(
						$pageNumber, $condition, $folders->CreateFolderListFromTree(), $inHeadersOnly, $this->Account)))
			{
				$mailCollection = new WebMailMessageCollection();

				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$msg =& $this->_rowToWebMailMessage($row);
					$mailCollection->Add($msg);
					unset($msg);
				}
			}

			return $mailCollection;
		}

		/**
		 * @return Array
		 */
		function &SelectReadsRecords()
		{
			$readsRecords = array();
			if ($this->_dbConnection->Execute($this->_commandCreator->SelectReadsRecords($this->Account->IdAccount)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$readsRecords[$row->uid] = '';
				}
			}

			return $readsRecords;
		}

		/**
		 * @return bool
		 */
		function DeleteReadsRecords()
		{
			return $this->_dbConnection->Execute($this->_commandCreator->DeleteReadsRecords($this->Account->IdAccount));
		}

		/**
		 * @param array $uids
		 * @return bool
		 */
		function DeleteReadsRecordsByUids($uids)
		{
			return $this->_dbConnection->Execute($this->_commandCreator->DeleteReadsRecordsByUid($this->Account->IdAccount, $uids));
		}

		/**
		 * @param bool $sortOrder
		 * @return bool
		 */
		function InsertReadsRecords($uidArray)
		{
			$result = true;

			foreach ($uidArray as $uid)
			{
				$result &= $this->_dbConnection->Execute($this->_commandCreator->InsertReadsRecord($this->Account->IdAccount, $uid));
			}
			return $result;
		}

		/**
		 * @return bool
		 */
		function UpdateMailboxSize()
		{
			$mailBoxSize = 0;
			if ($this->_dbConnection->Execute($this->_commandCreator->CountMailboxSize($this->Account->IdAccount)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$mailBoxSize = $row->mailbox_size;
				}
			}

			return $this->_dbConnection->Execute(
				$this->_commandCreator->UpdateMailboxSize($mailBoxSize, $this->Account->IdAccount));
		}

		/**
		 * @return int
		 */
		function SelectMailboxesSize()
		{
			$mailBoxesSize = 0;
			if ($this->_dbConnection->Execute($this->_commandCreator->SelectMailboxesSize($this->Account->IdUser)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$mailBoxesSize = api_Utils::GetGoodBigInt($row->mailboxes_size);
				}
			}

			return $mailBoxesSize;
		}

		/**
		 * @return Array
		 */
		function &SelectExpiredMessageUids()
		{
			$expiredUids = array();

			if ($this->_dbConnection->Execute($this->_commandCreator->SelectExpiredMessageUids($this->Account)))
			{
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$expiredUids[] = $row->str_uid;
				}
			}

			return $expiredUids;
		}

		/**
		 * @param Folder $folder
		 * @return bool
		 */
		function CreateFolder(&$folder)
		{
			if (!$this->_dbConnection->Execute($this->_commandCreator->SelectForCreateFolder($folder)))
			{
				return false;
			}
			else
			{
				$row = $this->_dbConnection->GetNextRecord();
				$folder->FolderOrder = ($row && isset($row->norder)) ? (int) $row->norder + 1 : 0;
			}

			if (!$this->_dbConnection->Execute($this->_commandCreator->CreateFolder($folder)))
			{
				return false;
			}

			$folder->IdDb = $this->_dbConnection->GetLastInsertId();

			if (!$this->_dbConnection->Execute($this->_commandCreator->CreateFolderTree($folder)))
			{
				return false;
			}

			if (!$this->_dbConnection->Execute($this->_commandCreator->SelectForCreateFolderTree($folder)))
			{
				return false;
			}
			else
			{
				$result = array();
				while (false !== ($row = $this->_dbConnection->GetNextRecord()))
				{
					$IdParent = ($row && isset($row->id_parent)) ? (int) $row->id_parent : -1;
					$Level = ($row && isset($row->folder_level)) ? (int) $row->folder_level + 1 : 0;

					$result[] = array($IdParent, $Level);
				}

				if ($result && count($result) > 0)
				{
					foreach ($result as $folderData)
					{
						$cfolder = clone $folder;
						$cfolder->IdParent = $folderData[0];
						$cfolder->Level = $folderData[1];
						if (!$this->_dbConnection->Execute($this->_commandCreator->CreateSelectFolderTree($cfolder)))
						{
							return false;
						}

						unset($cfolder);
					}

				}
			}

			return true;
		}

		function SelectLastFunabolCronRun()
		{
			$run_date = null;
			if (!$this->_dbConnection->Execute($this->_commandCreator->SelectLastFunabolCronRun()))
			{
				return false;
			}

			$row = $this->_dbConnection->GetNextRecord();
			if ($row)
			{
				$run_date = $row->run_date;
			}

			return $run_date;
		}

		function WriteLastFunabolCronRun( $date )
		{
			return $this->_dbConnection->Execute($this->_commandCreator->WriteLastFunambolCronRun( $date ));
		}
	}

	class MySqlStorage extends DbStorage
	{
		/**
		 * @param CAccount $account
		 * @param api_Settings $settings = null
		 * @return MySqlStorage
		 */
		function MySqlStorage(&$account, $settings = null)
		{
			DbStorage::DbStorage($account, $settings);

			$this->_escapeType = QUOTE_ESCAPE;
			$this->_commandCreator = new MySqlCommandCreator($settings->GetConf('Common/DBPrefix'));
			$this->_dbConnection =& CApi::GetManager()->GetConnection();
		}
	}
