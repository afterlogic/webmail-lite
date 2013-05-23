<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__) . '/'));

require_once (WM_ROOTPATH.'libraries/other/class_imap.php');
require_once (WM_ROOTPATH.'common/class_webmailmessages.php');
require_once (WM_ROOTPATH.'common/class_folders.php');
require_once (WM_ROOTPATH.'common/class_mailstorage.php');
require_once (WM_ROOTPATH.'common/class_bodystructure.php');

class ImapStorage extends MailServerStorage
{
	/**
	 * @access private
	 * @var IMAPMAIL
	 */
	var $_imapMail;

	/**
	 * @param CAccount $account
	 * @return ImapStorage
	 */
	function ImapStorage(&$account, &$mp)
	{
		$this->mailproc =& $mp;
		MailServerStorage::MailServerStorage($account);

		$this->_imapMail = new IMAPMAIL();
		$this->_imapMail->host = $account->IncomingMailServer;
		$this->_imapMail->port = $account->IncomingMailPort;
		$this->_imapMail->user = $account->IncomingMailLogin;
		$this->_imapMail->password = $account->IncomingMailPassword;
	}

	/**
	 * @return	string
	 */
	function GetNameSpacePrefix()
	{
		if ($this->_imapMail->IsNameSpaceSupport())
		{
			return $this->_imapMail->GetNameSpacePrefix();
		}
		return '';
	}

	/**
	 * @param $arg[optional] = false
	 * @return bool
	 */
	function Connect()
	{
		if($this->_imapMail->connection != false)
		{
			return true;
		}

		@register_shutdown_function(array(&$this, 'Disconnect'));
		if (!$this->_imapMail->open())
		{
			setGlobalError(ErrorIMAP4Connect);
			return false;
		}

		$bPlain = ((bool) CApi::GetConf('login.enable-plain-auth', false)) && $this->IsPlainLoginSupport();

		if ($bPlain)
		{
			if (!$this->_imapMail->authenticate('PLAIN',
				"\0".$this->Account->IncomingMailLogin."\0".$this->Account->IncomingMailPassword))
			{
				setGlobalError(ErrorPOP3IMAP4Auth);
				return false;
			}
		}
		else
		{
			// if (!$this->_imapMail->login($this->Account->IncomingMailLogin, $this->Account->IncomingMailPassword, $this->Account->MailIncProxyLogin))
			if (!$this->_imapMail->login($this->Account->IncomingMailLogin, $this->Account->IncomingMailPassword))
			{
				setGlobalError(ErrorPOP3IMAP4Auth);
				return false;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	function Disconnect()
	{
		if ($this->_imapMail->connection == false)
		{
			return true;
		}

		return $this->_imapMail->close();
	}

	/**
	 * @param Array $messageIndexSet
	 * @param bool $indexAsUid
	 * @param Folder $folder
	 * @return WebMailMessageCollection
	 */
	function LoadMessages(&$messageIndexSet, $indexAsUid, &$folder, $imapUids = null, $imapUidFlags = null, $imapUidSizes = null)
	{
		$messageCollection = null;
		if ($this->_imapMail->open_mailbox($folder->FullName, false))
		{
			$_imapUids = array();
			$_imapUidFlags = array();
			$_imapUidSizes = array();

			if ($imapUids == null)
			{
				//Get uid, flags and size from imap Server
				$paramsMessages = $this->_imapMail->getParamsMessages();
				if (!is_array($paramsMessages))
				{
					return $messageCollection;
				}

				foreach($paramsMessages as $key => $value)
				{
					$_imapUids[$key] = $value["uid"];
					$_imapUidFlags[$value["uid"]] = $value["flag"];
					$_imapUidSizes[$value["uid"]] = $value["size"];
				}
			}

			$messageCollection = new WebMailMessageCollection();
			foreach($messageIndexSet as $idx)
			{
				$response = $this->_imapMail->get_message($idx, $indexAsUid);
				if ($response)
				{
					$msg = new WebMailMessage();
					$msg->LoadMessageFromRawBody($response, true);
					if($indexAsUid)
					{
						$msg->Uid = $idx;
					}
					else
					{
						if ($imapUids == null)
						{
							$imapUids = $_imapUids;
						}
						$msg->Uid = $imapUids[$idx];
					}

					if ($imapUidSizes == null)
					{
						$imapUidSizes = $_imapUidSizes;
					}

					$msg->Size = $imapUidSizes[$msg->Uid];

					if ($imapUidFlags == null)
					{
						$imapUidFlags = $_imapUidSizes;
					}

					$this->_setMessageFlags($msg, $imapUidFlags[$idx]);
					$messageCollection->Add($msg);
					unset($msg);
				}
			}
			if ($messageCollection->Count() > 0)
			{
				return $messageCollection;
			}
		}
		return $messageCollection;
	}

	function GetBodyPartByIndex($bsIndex, $messageUid, $folder)
	{
		$out = '';
		if ($this->_imapMail->open_mailbox($folder->FullName, false))
		{
			$out = $this->_imapMail->getBodyPartByIndex($bsIndex, $messageUid);
		}
		return $out;
	}

	/**
	 * @param string $messageUid
	 * @param bool $indexAsUid
	 * @param Folder $folder
	 * @return WebMailMessage
	 */
	function LoadMessage($messageUid, $indexAsUid, &$folder, $mode = null)
	{
		$msg = null;
		if ($indexAsUid && $this->_imapMail->open_mailbox($folder->FullName, false))
		{
			if (null !== $mode)
			{
				$bodyStructureObject = $this->_imapMail->getMessageBodyStructure($messageUid);
				if ($bodyStructureObject && $bodyStructureObject->GetSize() > CApi::GetConf('webmail.bodystructure-message-size-limit', 20000))
				{
					$this->_imapMail->FillBodyStructureByMode($messageUid, $mode, $bodyStructureObject);

					$msg = new WebMailMessage();
					$msg->FillByBodyStructure($bodyStructureObject, $this->Account->User->DefaultIncomingCharset);
					$msg->Uid = $messageUid;
					$msg->Size = $bodyStructureObject->GetSize();
					$this->_setMessageFlags($msg, $bodyStructureObject->GetFlags());
				}
			}

			if (null === $msg)
			{
				$responseArray = $this->_imapMail->getMessageWithFlag($messageUid);
				if ($responseArray && count($responseArray) == 2)
				{
					$msg = new WebMailMessage();
					$msg->LoadMessageFromRawBody($responseArray[0], true);
					$msg->Uid = $messageUid;
					$msg->Size = strlen($responseArray[0]);
					$this->_setMessageFlags($msg, $responseArray[1]);
				}
				else
				{
					setGlobalError(PROC_MSG_HAS_DELETED);
				}
			}
		}
		return $msg;
	}

	/**
	 * @param int $pageNumber
	 * @param Folder $folder
	 * @param string $condition
	 * @param bool $inHeadersOnly
	 * @param int $iFilter = APP_MESSAGE_LIST_FILTER_NONE
	 * @return WebMailMessageCollection | null | bool
	 */
	function &DmImapSearchMessages($pageNumber, &$folder, $condition, $inHeadersOnly, &$refMsgCount, $iFilter = APP_MESSAGE_LIST_FILTER_NONE)
	{
		$webMailMessageCollection = $paramsMessages = null;
		if ($this->_imapMail->open_mailbox($folder->FullName, false))
		{
			$condition = $this->_imapMail->IsGmail()
				? '{'.strlen($condition).'+}'."\r\n".$condition
				: '"'.$this->_imapMail->quote($condition).'"';

			$searchRequest = 'OR (OR (OR (FROM '.$condition.') TO '.$condition.') SUBJECT '.$condition.') CC '.$condition;
			if (!$inHeadersOnly)
			{
//				$searchRequest = 'OR ('.$searchRequest.') BODY '.$condition;
				$searchRequest = 'TEXT '.$condition;
			}

			$oSettings =& CApi::GetSettings();
			$isSortSupport = $this->_imapMail->IsSortSupport();

			$order_by = null;
			if ($isSortSupport && !(!$oSettings->GetConf('WebMail/UseSortImapForDateMode') &&
				in_array($this->Account->DefaultOrder, array(EAccountDefaultOrder::DescDate, EAccountDefaultOrder::AscDate))))
			{
				$order_by = $this->GetOrderByForImapSort();
			}

			$sAddSearchCri = ConvertUtils::GetIMAPFilterSearchCri($iFilter);
			if (!empty($sAddSearchCri))
			{
				$searchRequest = '('.$searchRequest.') '.$sAddSearchCri;
			}

			$iMin = 5;
			$iLimit = $iMin + 2;
			$bSearchError = false;
			$iTime = time();
			$this->_imapMail->incSokectTimeout(60);

			ignore_user_abort(true);

			$searchMessagesIndexsValues = false;
			$bContinueOnRead = false;
			while (true)
			{
				$searchMessagesIndexsValues = $this->_imapMail->search_mailbox($searchRequest, 'UTF-8', $order_by, $bContinueOnRead);
				if (!is_array($searchMessagesIndexsValues))
				{
					CApi::Log('Search result = false.');
				}

				$bContinueOnRead = true;

				if (connection_status() !== CONNECTION_NORMAL)
				{
					CApi::Log('Search connection aborted.');
					$searchMessagesIndexsValues = false;
					break;
				}

				if (is_array($searchMessagesIndexsValues))
				{
					break;
				}
				else if (60 * $iMin < time() - $iTime || --$iLimit < 0)
				{
					$bSearchError = true;
					break;
				}

				ConvertUtils::SetLimits();
			}

			ignore_user_abort(false);

			$msgCount = is_array($searchMessagesIndexsValues) ? count($searchMessagesIndexsValues) : 0;
			if ($searchMessagesIndexsValues == false || $msgCount == 0)
			{
				$newcoll = new WebMailMessageCollection();
				$newcoll->Error = $bSearchError;
				return $newcoll;
			}

			$this->resortIndexSetArray($searchMessagesIndexsValues, false, $this->Account->DefaultOrder);
			$searchMessagesIndexsValues = array_reverse($searchMessagesIndexsValues);

			$pages = ceil($msgCount / $this->Account->User->MailsPerPage);

			if ($pageNumber > $pages)
			{
				$pageNumber = 1;
			}

			$refMsgCount = $msgCount;

			$messageIndexSet = array();
			$start = ($pageNumber - 1) * $this->Account->User->MailsPerPage;
			$messageIndexSet = array_slice($searchMessagesIndexsValues, $start, $this->Account->User->MailsPerPage);
			$webMailMessageCollection =& $this->LoadMessageHeadersInOneRequest($folder, $messageIndexSet);
		}

		return $webMailMessageCollection;
	}

	/**
	 * @param Folder $folder
	 * @param string $condition
	 * @param int $iFilter = APP_MESSAGE_LIST_FILTER_NONE
	 * @return array|false
	 */
	function HeadersBodyImapSearchMessagesUids($folder, $condition, $iFilter = APP_MESSAGE_LIST_FILTER_NONE)
	{
		$uids = false;
		if ($this->_imapMail->open_mailbox($folder->FullName, false))
		{
			$condition = $this->_imapMail->IsGmail()
				? '{'.strlen($condition).'+}'."\r\n".$condition
				: '"'.$this->_imapMail->quote($condition).'"';

			$searchRequest = 'OR (OR FROM '.$condition.' TO '.$condition.') SUBJECT '.$condition;
			$searchRequest = 'OR ('.$searchRequest.') BODY '.$condition;

			$oSettings =& CApi::GetSettings();
			$isSortSupport = $this->_imapMail->IsSortSupport();

			$order_by = null;
			if ($isSortSupport && !(!$oSettings->GetConf('WebMail/UseSortImapForDateMode') &&
				in_array($this->Account->DefaultOrder, array(EAccountDefaultOrder::DescDate, EAccountDefaultOrder::AscDate))))
			{
				$order_by = $this->GetOrderByForImapSort();
			}

			$sAddSearchCri = ConvertUtils::GetIMAPFilterSearchCri($iFilter);
			if (!empty($sAddSearchCri))
			{
				$searchRequest = '('.$searchRequest.') '.$sAddSearchCri;
			}

			$uids = $this->_imapMail->uid_search_mailbox($searchRequest, 'UTF-8', $order_by);

			if (!$isSortSupport)
			{
				$this->resortIndexSetArray($uids, true, $this->Account->DefaultOrder);
			}
		}

		return $uids;
	}

	/**
	 * @param array $aIndexSet
	 * @param bool $bSortedByUid = false
	 * @return array
	 */
	function resortIndexSetArray(&$aIndexSet, $bSortedByUid = false, $iDefaultOrder = EAccountDefaultOrder::DescDate)
	{
		$oSettings =& CApi::GetSettings();
		if (in_array($iDefaultOrder, array(EAccountDefaultOrder::AscDate, EAccountDefaultOrder::DescDate)) &&
			$oSettings->GetConf('WebMail/UseSortImapForDateMode') && is_array($aIndexSet) && 0 < count($aIndexSet))
		{
			$aSortedIndexSet = $this->_imapMail->get_sorted_by_internaldate_indexs();

			$aResultArray = array();
			if (is_array($aSortedIndexSet) && count($aIndexSet) <= count($aSortedIndexSet))
			{
				foreach ($aSortedIndexSet as $iIndex => $sUid)
				{
					if ($bSortedByUid)
					{
						if (in_array($sUid, $aIndexSet))
						{
							$aResultArray[] = $sUid;
						}
					}
					else
					{
						if (in_array($iIndex, $aIndexSet))
						{
							$aResultArray[] = $iIndex;
						}
					}
				}

				if (count($aIndexSet) === count($aResultArray))
				{
					if (EAccountDefaultOrder::AscDate === $this->Account->DefaultOrder)
					{
						$aResultArray = array_reverse($aResultArray);
					}

					$aIndexSet = $aResultArray;
				}
			}

			return $aSortedIndexSet;
		}

		return false;
	}

	/**
	 * @param int $pageNumber
	 * @param Folder $folder
	 * @param int $iFilter = APP_MESSAGE_LIST_FILTER_NONE
	 * @return WebMailMessageCollection
	 */
	function &LoadMessageHeaders($pageNumber, &$folder, $iFilter = APP_MESSAGE_LIST_FILTER_NONE)
	{
		$webMailMessageCollection = null;
		if ($this->_imapMail->open_mailbox($folder->FullName, false))
		{
			if ($folder->SyncType == FOLDERSYNC_DirectMode)
			{
				if ($folder->MessageCount < 1)
				{
					$newcoll = new WebMailMessageCollection();
					return $newcoll;
				}

				$msgCount = $folder->MessageCount;
				$messageSortedIndexSet = null;
				$messageIndexSet = array();

				$oSettings =& CApi::GetSettings();
				$isSortSupport = $this->_imapMail->IsSortSupport();

				if (($isSortSupport && !(!$oSettings->GetConf('WebMail/UseSortImapForDateMode')
						&& in_array($this->Account->DefaultOrder, array(
							EAccountDefaultOrder::DescDate, EAccountDefaultOrder::AscDate))))
					|| APP_MESSAGE_LIST_FILTER_NONE !== $iFilter)
				{
					$order_by = null;
					if ($isSortSupport)
					{
						$order_by = $this->GetOrderByForImapSort();
					}

					$sSearchCri = ConvertUtils::GetIMAPFilterSearchCri($iFilter);
					$messageSortedIndexSet = $this->_imapMail->search_mailbox($sSearchCri, '', $order_by);
					$msgCount = count($messageSortedIndexSet);

					if (EAccountDefaultOrder::DescDate === $this->Account->DefaultOrder &&
						(!$isSortSupport || !$oSettings->GetConf('WebMail/UseSortImapForDateMode')))
					{
						$messageSortedIndexSet = array_reverse($messageSortedIndexSet);
					}

					if (!$isSortSupport || APP_MESSAGE_LIST_FILTER_NONE !== $iFilter)
					{
						$this->resortIndexSetArray($messageSortedIndexSet, false,
							$this->Account->DefaultOrder);
					}
				}
				else
				{
					$isSortSupport = false;

					if (0 < $msgCount)
					{
						$messageSortedIndexSet = (1 < $msgCount) ? range(1, $msgCount) : array(1);
						if (EAccountDefaultOrder::AscDate === $this->Account->DefaultOrder)
						{
							$messageSortedIndexSet = array_reverse($messageSortedIndexSet);
						}

						$this->resortIndexSetArray($messageSortedIndexSet, false,
							$this->Account->DefaultOrder);
					}
				}

				if (0 < $msgCount && is_array($messageSortedIndexSet))
				{
					$iStart = ($pageNumber - 1) * $this->Account->User->MailsPerPage;

					$messageSortedIndexSet = array_reverse($messageSortedIndexSet);
					$messageIndexSet = array_splice($messageSortedIndexSet,
						$iStart, $this->Account->User->MailsPerPage);
				}

				$webMailMessageCollection =& $this->LoadMessageHeadersInOneRequest($folder, $messageIndexSet);
				if (APP_MESSAGE_LIST_FILTER_NONE !== $iFilter)
				{
					$webMailMessageCollection->FilteredCount = $msgCount;
				}
			}
			else
			{
				$paramsMessages = $this->_imapMail->getParamsMessages();
				$imapFlags = array();
				$imapUids = array();
				$imapSizes = array();
				if (!is_array($paramsMessages))
				{
					return $webMailMessageCollection;
				}

				foreach($paramsMessages as $key => $value)
				{
					$imapFlags[$key] = $value["flag"];
					$imapUids[$key] = $value["uid"];
					$imapSizes[$key] = $value["size"];
				}

				if(count($paramsMessages) < 1)
				{
					$newcoll = new WebMailMessageCollection();
					return $newcoll;
				}

				$msgCount = count($imapUids);
				$messageIndexSet = array();
				//$imapNFlags = $imapNSizes = array();
				for($i = $msgCount - ($pageNumber - 1) * $this->Account->User->MailsPerPage;
					$i > $msgCount - $pageNumber * $this->Account->User->MailsPerPage; $i--)
				{
					if ($i == 0) break;
					$messageIndexSet[] = $imapUids[$i];
					//$imapNFlags[$imapUids[$i]] = $imapFlags[$i];
					//$imapNSizes[$imapUids[$i]] = $imapSizes[$i];
				}
				$webMailMessageCollection =& $this->LoadMessageHeadersInOneRequest($folder, $messageIndexSet, true);
			}
		}
		return $webMailMessageCollection;
	}

	/**
	 * @param FolderCollection $folders
	 * @return bool
	 */
	function SynchronizeFolder(&$folder)
	{
		$dbStorage = &DbStorageCreator::CreateDatabaseStorage($this->Account);
		if ($dbStorage->Connect())
		{
			return $this->_synchronizeFolderWithOpenDbConnection($folder, $dbStorage);
		}
		return false;
	}

	/**
	 * @param FolderCollection $folders
	 * @return bool
	 */
	function Synchronize(&$folders)
	{
		$result = true;
		$dbStorage =& DbStorageCreator::CreateDatabaseStorage($this->Account);
		if ($dbStorage->Connect() && $folders)
		{
			$folderList = $folders->CreateFolderListFromTree(); //copy tree object here
			for ($i = 0, $icount = $folderList->Count(); $i < $icount; $i++)
			{
				$folder =& $folderList->Get($i);
				$result &= $this->_synchronizeFolderWithOpenDbConnection($folder, $dbStorage);
				unset($folder);

				if (!$result)
				{
					break;
				}
			}
			return $result;
		}
		return false;
	}

	/**
	 * @param string $sFolderName
	 * @param CAccount $oAccount
	 */
	function createOnSyncFolder($sFolderName, $oAccount)
	{
		if (0 < strlen($oAccount->Namespace))
		{
			$sFolderName = $oAccount->Namespace.$sFolderName;
		}

		$aSeporatedNames = explode($oAccount->Delimiter, rtrim($sFolderName, $oAccount->Delimiter));

		$oFolder = new Folder($oAccount->IdAccount, -1, $sFolderName, $aSeporatedNames[count($aSeporatedNames) - 1]);

		return $this->CreateFolder($oFolder);
	}

	/**
	 * @param object $dbFoldersList
	 * @param CAccount $oAccount
	 * @return bool
	 */
	function createFoldersOnSync($dbFoldersList, $serverFoldersList, $oAccount, $bWithOutDbFolderCheck = false)
	{
		$bResult = false;
		if (($dbFoldersList && 0 === $dbFoldersList->Count()) || $bWithOutDbFolderCheck)
		{
			if ($oAccount && CApi::GetConf('webmail.create-imap-system-folders', true))
			{
				$aMap =& $oAccount->Domain->GetFoldersMap();
				foreach ($aMap as $iFolderType => $mFolderName)
				{
					$sName = is_array($mFolderName) && 0 < count($mFolderName) ? $mFolderName[0] : $mFolderName;
					if (in_array($iFolderType, array(
						EFolderType::Sent,
						EFolderType::Drafts,
						EFolderType::Trash,
						EFolderType::Spam,
						EFolderType::Virus,
					)) && !$serverFoldersList->GetFolderByType($iFolderType))
					{
						$bResult = true;
						$this->createOnSyncFolder($sName, $oAccount);
					}
					else if (in_array($iFolderType, array(EFolderType::Custom, EFolderType::System))
						&& !$serverFoldersList->GetFolderByNameWithNamespace($sName, $oAccount->Namespace))
					{
						$bResult = true;
						$this->createOnSyncFolder($sName, $oAccount);
					}
				}
			}
		}
		else if ($dbFoldersList && 0 < $dbFoldersList->Count())
		{
			$aMap =& $oAccount->Domain->GetFoldersMap();
			foreach ($aMap as $iFolderType => $mFolderName)
			{
				$sName = is_array($mFolderName) && 0 < count($mFolderName) ? $mFolderName[0] : $mFolderName;
				if (in_array($iFolderType, array(
					EFolderType::Sent,
					EFolderType::Drafts,
					EFolderType::Trash,
					EFolderType::Spam,
					EFolderType::Virus,
					EFolderType::System
				)))
				{
					$oFolder = $dbFoldersList->GetFolderByType($iFolderType);
					if ($oFolder && null === $serverFoldersList->GetFolderByFullName($oFolder->FullName))
					{
						$bResult = true;
						$this->CreateFolder($oFolder);
					}
				}
			}
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	function SynchronizeFolders()
	{
		$result = true;
		if (!USE_DB)
		{
			$serverFL = null;
			$serverFL = $this->GetFolders();
			$this->createFoldersOnSync(null, $serverFL, $this->Account, true);
			return $result;
		}

		$dbStorage =& DbStorageCreator::CreateDatabaseStorage($this->Account);
		$serverFoldersTree = $this->GetFolders();
		$serverFoldersTree = $serverFoldersTree->SortRootTree($this->Account);
		if ($serverFoldersTree && $dbStorage->Connect())
		{
			$dbFoldersTree =& $dbStorage->GetFolders();
			$serverFoldersList =& $serverFoldersTree->CreateFolderListFromTree();

			$dbFoldersList = null;
			if ($dbFoldersTree)
			{
				$dbFoldersList =& $dbFoldersTree->CreateFolderListFromTree();
			}

			$bIsFirstSynchronization = !($dbFoldersList && 0 < $dbFoldersList->Count());

			if ($this->createFoldersOnSync($dbFoldersList, $serverFoldersList,
				$this->Account, (bool) CApi::GetConf('webmail.system-folders-sync-on-each-login', false)))
			{
				$serverFoldersTree = $this->GetFolders();
				$serverFoldersTree = $serverFoldersTree->SortRootTree($this->Account);
				$serverFoldersList =& $serverFoldersTree->CreateFolderListFromTree();
			}

			$delimiter = $this->Account->Delimiter;
			$mailFolder = null;
			$serverFoldersListKeys = array_keys($serverFoldersList->Instance());

			foreach ($serverFoldersListKeys as $mkey)
			{
				$mailFolder =& $serverFoldersList->Get($mkey);
				$folderExist = false;
				if ($dbFoldersList)
				{
					$dbFoldersListKeys = array_keys($dbFoldersList->Instance());
					foreach ($dbFoldersListKeys as $skey)
					{
						$dbFolder =& $dbFoldersList->Get($skey);

						if (trim($mailFolder->FullName, $delimiter) == trim($dbFolder->FullName, $delimiter))
						{
							$bNeedUpdate = false;
							$folderExist = true;

							$mailFolder->IdDb = $dbFolder->IdDb;
							$mailFolder->SetParentIdToSubFolders();

							if ($dbFolder->Flags != $mailFolder->Flags)
							{
								$dbFolder->Flags = $mailFolder->Flags;
								$bNeedUpdate = true;
							}

							if ($dbFolder->Hide != $mailFolder->Hide && $dbFolder->SyncType != FOLDERSYNC_DontSync)
							{
								$dbFolder->Hide = $mailFolder->Hide;
								$bNeedUpdate = true;
							}

							if ($bNeedUpdate)
							{
								$dbStorage->UpdateFolder($dbFolder);
							}

							break;
						}

						unset($dbFolder);
					}
				}

				if (!$folderExist && $mailFolder)
				{
					$mailFolder->SyncType = FOLDERSYNC_DirectMode;
					if (FOLDERTYPE_Custom !== $mailFolder->Type && $bIsFirstSynchronization)
					{
						$searchFolder =& $dbFoldersList->GetFolderByType($mailFolder->Type);
						if (null != $searchFolder)
						{
							$mailFolder->Type = FOLDERTYPE_Custom;
						}
					}
					else
					{
						$mailFolder->Type = FOLDERTYPE_Custom;
					}

					$result &= $dbStorage->CreateFolder($mailFolder);
					$mailFolder->SetParentIdToSubFolders();
				}
			}

			$dbFoldersListKeys = array_keys($dbFoldersList->Instance());
			foreach ($dbFoldersListKeys as $skey)
			{
				$dbFolder =& $dbFoldersList->Get($skey);
				$folderExist = false;
				$serverFoldersListKeys = array_keys($serverFoldersList->Instance());
				foreach ($serverFoldersListKeys as $mkey)
				{
					$mailFolder =& $serverFoldersList->Get($mkey);
					if (trim($mailFolder->FullName, $delimiter) == trim($dbFolder->FullName, $delimiter))
					{
						$folderExist = true;
						break;
					}
					unset($mailFolder);
				}

				if (!$folderExist && $dbFolder->SyncType != FOLDERSYNC_DontSync)
				{
//					if ($dbFolder->SyncType == FOLDERSYNC_DirectMode && $dbFolder->MessageCount == 0)
//					{
//						$dbStorage->DeleteFolder($dbFolder);
//					}
//					else
//					{
//						$dbStorage->DeleteFolder($dbFolder);
//					}
					$dbStorage->DeleteFolder($dbFolder);
				}
			}
		}

		return $result;
	}

	/**
	 * @param array $paramsMessages
	 * @param array $imapUids
	 * @param array $imapSizes
	 * @param array $imapUidFlags
	 * @param array $imapUidSizes
	 */
	function _imapArrayForeach(&$paramsMessages, &$imapUids, &$imapSizes, &$imapUidFlags, &$imapUidSizes)
	{
		foreach ($paramsMessages as $key => $value)
		{
			$imapUids[$key] = $value["uid"];
			$imapSizes[$key] = $value["size"];
			$imapUidFlags[$value["uid"]] = $value["flag"];
			$imapUidSizes[$value["uid"]] = $value["size"];
		}
	}

	/**
	 * @param Folder $folders
	 * @param DbStorage $dbStorage
	 * @param int $lastIdMsg
	 * @return bool
	 */
	function _synchronizeFolderWithOpenDbConnection(&$folder, &$dbStorage)
	{
		$result = true;
		if ($folder->SyncType == FOLDERSYNC_DontSync || $folder->SyncType == FOLDERSYNC_DirectMode || $folder->Hide)
		{
			if ($this->UpdateFolderHandler != null && $folder->SyncType == FOLDERSYNC_DirectMode)
			{
				call_user_func_array($this->UpdateFolderHandler, array($folder->IdDb, $folder->FullName));
			}
			return true;
		}

		$foldername = '';
		if ($this->DownloadedMessagesHandler != null)
		{
			$foldername = $folder->GetFolderName();
			call_user_func_array($this->DownloadedMessagesHandler, array($foldername, 0));
		}

		if (!$this->_imapMail->open_mailbox($folder->FullName, false, true))
		{
			return true;
		}

		$_isAllUpdate = ($folder->SyncType == FOLDERSYNC_AllHeadersOnly || $folder->SyncType == FOLDERSYNC_AllEntireMessages);
		$_isUidsOnly = ($folder->SyncType == FOLDERSYNC_NewHeadersOnly);

		/* get uid, flags and size from IMAP4 Server */
		$start = microtime(true);
		$paramsMessages = $this->_imapMail->getParamsMessages();
		CApi::Log('IMAP4: getParamsMessages()='.(microtime(true) - $start));

		if (!is_array($paramsMessages))
		{
			return false;
		}

		$imapUids = $imapSizes = $imapUidFlags = $imapUidSizes = array();
		$this->_imapArrayForeach($paramsMessages, $imapUids, $imapSizes, $imapUidFlags, $imapUidSizes);
		unset($paramsMessages);

		$dbUidsIdMsgsFlags =& $dbStorage->SelectIdMsgAndUidByIdMsgDesc($folder);

		$dbUids = $dbUidsFlag = array();
		foreach ($dbUidsIdMsgsFlags as $value)
		{
			$dbUids[] = $value[1];
			$dbUidsFlag[$value[1]] = $value[2];
		}
		unset($dbUidsIdMsgsFlags);

		/* array need added to DB */
		//$newUids = array_diff($imapUids, $dbUids);
		$newUids = array();
		foreach ($imapUids as $_imUid)
		{
			if (!isset($dbUidsFlag[$_imUid]))
			{
				$newUids[] = $_imUid;
			}
		}

		if ($this->DownloadedMessagesHandler != null && count($newUids) > 0)
		{
			call_user_func_array($this->DownloadedMessagesHandler, array($foldername, count($newUids)));
		}

		if ($_isAllUpdate)
		{
			/* update flags */
			$_flags4Update = array();
			/* intersect uids */
			foreach ($imapUids as $_imUid)
			{
				if (isset($dbUidsFlag[$_imUid]))
				{
					$flagBD = (int) $dbUidsFlag[$_imUid];
					$flagImap = (int) $this->getIntFlags($imapUidFlags[$_imUid]);
					/* update messages whith different flags */
					if ($flagBD != $flagImap)
					{
						$_flags4Update[$flagImap][] = $_imUid;
					}
				}
			}

			if (count($_flags4Update) > 0)
			{
				foreach ($_flags4Update as $_flag => $_uidArray)
				{
					if (is_array($_uidArray))
					{
						$dbStorage->UpdateMessageFlags($_uidArray, true, $folder, $_flag, $this->Account);
					}
				}
				if ($this->UpdateFolderHandler != null)
				{
					call_user_func_array($this->UpdateFolderHandler, array($folder->IdDb, $folder->FullName));
				}
			}

			/* delete from DB */

			//$uidsToDelete = array_diff($dbUids, $imapUids);
			$uidsToDelete = array();
			foreach ($dbUids as $_dbUid)
			{
				if (!isset($imapUidFlags[$_dbUid]))
				{
					//$dbUidsFlag[$_dbUid] = $value[2];
					$uidsToDelete[] = $_dbUid;
				}
			}
			if (count($uidsToDelete) > 0)
			{
				if ($this->UpdateFolderHandler != null)
				{
					call_user_func_array($this->UpdateFolderHandler, array($folder->IdDb, $folder->FullName));
				}

				// $result &= $dbStorage->SetMessagesFlags($uidsToDelete, true, $folder, MESSAGEFLAGS_Deleted, ACTION_Set);
				$result &= $dbStorage->DeleteMessages($uidsToDelete, true, $folder);
//				$result &= $dbStorage->UpdateMailboxSize();
			}
		}

		$maxEnvelopesPerSession = 1;

		/* get size all messages in DB */
//		$mailBoxesSize = $dbStorage->SelectMailboxesSize();

		$filters = new FilterCollection();

		$syncCycles = ceil(count($newUids) / $maxEnvelopesPerSession);
		for ($i = 0; $i < $syncCycles; $i++)
		{
//			$mailBoxesSize += $imapSizes[$i + 1];

			if (!$this->_imapMail->open_mailbox($folder->FullName))
			{
				return true;
			}

			$listPartToDownload = ($i != $syncCycles - 1) ? array_slice($newUids, $i * $maxEnvelopesPerSession, $maxEnvelopesPerSession) : array_slice($newUids, $i * $maxEnvelopesPerSession);

			if ($this->DownloadedMessagesHandler != null && function_exists($this->DownloadedMessagesHandler))
			{
				call_user_func($this->DownloadedMessagesHandler);
			}

			$mailMessageCollection = null;
			$mailMessageCollection =& $this->LoadMessages($listPartToDownload, true, $folder, $imapUids, $imapUidFlags, $imapUidSizes);

			if ($mailMessageCollection && $mailMessageCollection->Count() > 0)
			{
				$message =& $mailMessageCollection->Get(0);
				if (!$this->ApplyFilters($message, $dbStorage, $folder, $filters))
				{
					$result = false;
					break;
				}
			}
		}

//		$result &= $dbStorage->UpdateMailboxSize();
		return $result;
	}

	/**
	 * @param array $messageIdUidSet
	 * @param Folder $fromFolder
	 * @return bool
	 */
	function SpamMessages($messageIdUidSet, $fromFolder, $isSpam = true)
	{
		return true;
	}

	/**
	 * @param array $indexs
	 * @param bool $indexsAsUid = false
	 * @return WebMailMessageCollection | null
	 */
	function &LoadMessageHeadersInOneRequest_Old($folder, $indexs, $indexsAsUid = false, $imapFlags = null, $imapSizes = null)
	{
		$messageCollection = null;
		$indexsStr = trim(implode(',', $indexs));
		$preText = ' ';
		if (null === $imapFlags)
		{
			$preText .= 'FLAGS ';
		}
		if (null === $imapSizes)
		{
			$preText .= 'RFC822.SIZE ';
		}

		$rString = 'FETCH '.$indexsStr.' (UID'.$preText.'BODY.PEEK[HEADER])';
//		$rString = 'FETCH '.$indexsStr.' (UID'.$preText.'BODY.PEEK[HEADER.FIELDS (RETURN-PATH RECEIVED MIME-VERSION FROM TO CC DATE SUBJECT X-MSMAIL-PRIORITY IMPORTANCE X-PRIORITY CONTENT-TYPE)])';
		if ($indexsAsUid)
		{
			$rString = 'UID '.$rString;
		}

		$responseArray = $this->_imapMail->getResponseAsArray($rString);
		if (is_array($responseArray))
		{
			$messageCollection = new WebMailMessageCollection();
			$headersString = implode('', $responseArray);

			$pieces = preg_split('/\* [\d]+ FETCH /', $headersString);
			foreach ($pieces as $key => $text)
			{
				$uid = $size = $flags = null;
				$lines = explode("\n", trim($text));
				$firstline = array_shift($lines);
				$lastline = array_pop($lines);
				$matchUid = $matchSize = $matchFlags = array();

				preg_match('/UID (\d+)/', $firstline, $matchUid);
				if (isset($matchUid[1]))
				{
					$uid = (int) $matchUid[1];
				}

				if (null === $imapFlags)
				{
					preg_match('/FLAGS \(([^\)]*)\)/', $firstline, $matchFlags);
					if (isset($matchFlags[1]))
					{
						$flags = trim(trim($matchFlags[1]), '()');
					}
				}
				else if (isset($imapFlags[$uid]))
				{
					$flags = $imapFlags[$uid];
				}

				if (null === $imapSizes)
				{
					preg_match('/RFC822\.SIZE ([\d]+)/', $firstline, $matchSize);
					if (isset($matchSize[1]))
					{
						$size = (int) $matchSize[1];
					}
				}
				else if (isset($imapSizes[$uid]))
				{
					$size = (int) $imapSizes[$uid];
				}

				if (null === $uid)
				{
					$match = array();
					preg_match('/UID (\d+)/', $lastline, $match);
					if (isset($match[1]))
					{
						$uid = (int) $match[1];
					}
				}

				$text = implode("\n", $lines);
				$pieces[$key] = array($uid, trim($text), $size, $flags);
			}

			if (!$this->_imapMail->IsSortSupport())
			{
				arsort($pieces);
			}

			foreach ($pieces as $headerArray)
			{
				if (is_array($headerArray) && count($headerArray) == 4 && $headerArray[0] > 0 && strlen($headerArray[1]) > 10)
				{
					$msg = new WebMailMessage();
					$msg->LoadMessageFromRawBody($headerArray[1]);
					$msg->IdFolder = $folder->IdDb;
					$msg->IdMsg = $headerArray[0];
					$msg->Uid = $headerArray[0];
					$msg->Size = (int) $headerArray[2];
					$this->_setMessageFlags($msg, $headerArray[3]);
					$messageCollection->Add($msg);
					unset($msg);
				}
			}
		}

		return $messageCollection;
	}

	/**
	 * @param array $indexs
	 * @param bool $indexsAsUid = false
	 * @return WebMailMessageCollection | null
	 */
	function &LoadMessageHeadersInOneRequest($folder, $indexs, $indexsAsUid = false, $imapFlags = null, $imapSizes = null)
	{
		$messageCollection = null;
		if (is_array($indexs) && 0 === count($indexs))
		{
			$messageCollection = new WebMailMessageCollection();
			return $messageCollection;
		}

		$indexsStr = trim(implode(',', $indexs));
		$preText = ' ';
		if (null === $imapFlags)
		{
			$preText .= 'FLAGS ';
		}
		if (null === $imapSizes)
		{
			$preText .= 'RFC822.SIZE ';
		}

		$rString = 'FETCH '.$indexsStr.' (UID'.$preText.'BODY.PEEK[HEADER])';
//		$rString = 'FETCH '.$indexsStr.' (UID'.$preText.'BODY.PEEK[HEADER.FIELDS (RETURN-PATH RECEIVED MIME-VERSION FROM TO CC DATE SUBJECT X-MSMAIL-PRIORITY IMPORTANCE X-PRIORITY CONTENT-TYPE)])';
		if ($indexsAsUid)
		{
			$rString = 'UID '.$rString;
		}

		$responseArray = $this->_imapMail->getResponseAsArray($rString);
		if (is_array($responseArray))
		{
			$messageCollection = new WebMailMessageCollection();
			$headersString = implode('', $responseArray);
			unset($responseArray);

			$piecesOut = array();
			$pieces = preg_split('/\* [\d]+ FETCH /', $headersString);

			$tmpArray = array();
			preg_match_all('/\* ([\d]+) FETCH /', $headersString, $tmpArray);
			$piecesFetchId = (isset($tmpArray[1])) ? $tmpArray[1] : array();
			unset($tmpArray, $headersString);

			foreach ($pieces as $key => $text)
			{
				if (isset($piecesFetchId[$key - 1]))
				{
					$index = $piecesFetchId[$key - 1];
					$uid = $size = $flags = null;
					$lines = explode("\n", trim($text));
					$firstline = array_shift($lines);
					$lastline = array_pop($lines);
					$matchUid = $matchSize = $matchFlags = array();

					preg_match('/UID (\d+)/', $firstline, $matchUid);
					if (isset($matchUid[1]))
					{
						$uid = (int) $matchUid[1];
					}

					if (null === $imapFlags)
					{
						preg_match('/FLAGS \(([^\)]*)\)/', $firstline, $matchFlags);
						if (isset($matchFlags[1]))
						{
							$flags = trim(trim($matchFlags[1]), '()');
						}
					}
					else if (isset($imapFlags[$uid]))
					{
						$flags = $imapFlags[$uid];
					}

					if (null === $imapSizes)
					{
						preg_match('/RFC822\.SIZE ([\d]+)/', $firstline, $matchSize);
						if (isset($matchSize[1]))
						{
							$size = (int) $matchSize[1];
						}
					}
					else if (isset($imapSizes[$uid]))
					{
						$size = (int) $imapSizes[$uid];
					}

					if (null === $uid)
					{
						$match = array();
						preg_match('/UID (\d+)/', $lastline, $match);
						if (isset($match[1]))
						{
							$uid = (int) $match[1];
						}
					}

					if (null === $flags)
					{
						$match = array();
						preg_match('/FLAGS \(([^\)]*)\)/', $lastline, $match);
						if (isset($match[1]))
						{
							$flags = trim(trim($match[1]), '()');
						}
					}

					if (null === $size)
					{
						$match = array();
						preg_match('/RFC822\.SIZE ([\d]+)/', $lastline, $match);
						if (isset($match[1]))
						{
							$size = (int) $match[1];
						}
					}

					$piecesOut[($indexsAsUid) ? $uid : $index] = array($uid, trim(implode("\n", $lines)), $size, $flags);
				}
			}

			unset($pieces);

			foreach ($indexs as $value)
			{
				if (isset($piecesOut[$value]))
				{
					$headerArray = $piecesOut[$value];
					if (is_array($headerArray) && count($headerArray) == 4 && $headerArray[0] > 0 && strlen($headerArray[1]) > 10)
					{
						$msg = new WebMailMessage();
						$msg->LoadMessageFromRawBody($headerArray[1]);
						$msg->IdFolder = $folder->IdDb;
						$msg->IdMsg = $headerArray[0];
						$msg->Uid = $headerArray[0];
						$msg->Size = (int) $headerArray[2];
						$this->_setMessageFlags($msg, $headerArray[3]);
						$messageCollection->Add($msg);
						unset($msg);
					}
				}
			}
		}

		return $messageCollection;
	}

	/**
	 * @return FolderCollection
	 */
	function GetFolders()
	{
		ConvertUtils::SetLimits();

		$lastD = $this->Account->Delimiter;
		$folderCollection = new FolderCollection();

		$currD = $this->Account->Delimiter;
		$flags = array();

		$folders =& $this->_imapMail->list_mailbox($currD, '', '*', $flags,
			$this->Account->DetectSpecialFoldersWithXList);

		$this->Account->Delimiter = $currD;
		$subsScrFolders = $this->_imapMail->list_subscribed_mailbox($currD);

		$existsIndex = array();
		$folderCollection = $this->GetFolderCollectionFromArrays($folders, $subsScrFolders, $currD, $existsIndex, $flags);

		$oApiUsersManager = null;
		if ($lastD != $currD)
		{
			/* @var $oApiUsersManager CApiUsersManager */
			$oApiUsersManager = CApi::Manager('users');
			$oApiUsersManager->UpdateAccount($this->Account);
		}

		CApi::Plugin()->RunHook('webmail-imap-change-server-folders', array(&$folderCollection));

		return $folderCollection;
	}

	/**
	 * @return FolderCollection
	 */
	function GetLSubFolders()
	{
		ConvertUtils::SetLimits();

		$sDelimiter = $this->Account->Delimiter;
		$subsScrFolders = $this->_imapMail->list_subscribed_mailbox($sDelimiter);
		$this->Account->Delimiter = $sDelimiter;

		return $subsScrFolders;
	}

	/**
	 * @param Folder $folder
	 * @return bool
	 */
	function CreateFolder(&$folder)
	{
		if ($folder)
		{
			if ($this->_imapMail->create_mailbox($folder->FullName))
			{
				if (!$folder->Hide)
				{
					$this->_imapMail->subscribe_mailbox($folder->FullName);
				}
				return true;
			}
		}
		return false;
	}

	function SubscribeFolder(&$folder, $isHide = false)
	{
		if ($isHide)
		{
			return (USE_LSUB) ? false : $this->_imapMail->unsubscribe_mailbox($folder->FullName);
		}
		else
		{
			return $this->_imapMail->subscribe_mailbox($folder->FullName);
		}
	}

	/**
	 * @param Folder $folder
	 * @return bool
	 */
	function DeleteFolder(&$folder)
	{
		if ($this->_imapMail->delete_mailbox($folder->FullName))
		{
			$this->_imapMail->unsubscribe_mailbox($folder->FullName);
			return true;
		}
		return false;;
	}

	/**
	 * @param Folder $folder
	 * @param string $newName
	 * @param array $aLsubFolder
	 * @return bool
	 */
	function RenameFolder(&$folder, $newName, $aLsubFolder, $sDelimiter = '/')
	{
		if ($folder && $folder->FullName != $newName && $this->_imapMail->rename_mailbox($folder->FullName, $newName))
		{
			if (is_array($aLsubFolder))
			{
				foreach ($aLsubFolder as $sLSubFullName)
				{
					if (0 === strpos($sLSubFullName, $folder->FullName.$sDelimiter))
					{
						$this->_imapMail->unsubscribe_mailbox($sLSubFullName);
						$sNewFullName = $newName.$sDelimiter
							.substr($sLSubFullName, strlen($folder->FullName.$sDelimiter));
						$this->_imapMail->subscribe_mailbox($sNewFullName);
					}
				}
			}

			$this->_imapMail->unsubscribe_mailbox($folder->FullName);

			if (!$folder->Hide)
			{
				$this->_imapMail->subscribe_mailbox($newName);
			}

			return true;
		}
		return false;
	}

	/**
	 * @return bool
	 */
	function IsQuotaSupport()
	{
		return $this->_imapMail->IsQuotaSupport();
	}

	/**
	 * @return bool
	 */
	function IsSortSupport()
	{
		return $this->_imapMail->IsSortSupport();
	}

	/**
	 * @return bool
	 */
	function IsPlainLoginSupport()
	{
		return $this->_imapMail->IsPlainLoginSupport();
	}

	/**
	 * @return bool
	 */
	function IsLastSelectedFolderSupportForwardedFlag()
	{
		return $this->_imapMail->IsLastSelectedFolderSupportForwardedFlag();
	}

	/**
	 * @return int | false
	 */
	function GetQuota()
	{
		return $this->_imapMail->get_quota();
	}

	/**
	 * @return int | false
	 */
	function GetUsedQuota()
	{
		return $this->_imapMail->get_used_quota();
	}

	/**
	 * @param WebMailMessage $message
	 * @param Folder $folder
	 * @return bool
	 */
	function SaveMessage(&$message, &$folder)
	{
		$bResult = false;
		if ($message && $folder)
		{
			$flagsStr = '';
			if(($message->Flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen)
			{
				$flagsStr .= ' \Seen';
			}
			if(($message->Flags & MESSAGEFLAGS_Flagged) == MESSAGEFLAGS_Flagged)
			{
				$flagsStr .= ' \Flagged';
			}
			if(($message->Flags & MESSAGEFLAGS_Deleted) == MESSAGEFLAGS_Deleted)
			{
				$flagsStr .= ' \Deleted';
			}
			if(($message->Flags & MESSAGEFLAGS_Answered) == MESSAGEFLAGS_Answered)
			{
				$flagsStr .= ' \Answered';
			}

			$sNewUid = null;
			$sMessageId = $message->Headers->GetHeaderValueByName(MIMEConst_MessageID);
			$bResult = $this->_imapMail->append_mail($folder->FullName, $flagsStr, $message->TryToGetOriginalMailMessage(), $sNewUid);
			if ($bResult)
			{
				if (null === $sNewUid || empty($sNewUid) && !empty($sMessageId))
				{
					$sNewUid = $this->_imapMail->getUidByMessageId($folder->FullName, $sMessageId);
				}

				if (null !== $sNewUid && !empty($sNewUid))
				{
					$message->IdMsg = $sNewUid;
					$message->Uid = $sNewUid;
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param WebMailMessageCollection $messages
	 * @param Folder $folder
	 * @return bool
	 */
	function SaveMessages(&$messages, &$folder)
	{
		$result = true;
		for ($i = 0, $c = $messages->Count(); $i < $c; $i++)
		{
			$result &= $this->SaveMessage($messages->Get($i), $folder);
		}
		return $result;
	}

	/**
	 * @access private
	 * @param Array $uidList
	 * @param string $uid
	 * @return int
	 */
	function _getMessageIndexFromUid(&$uidList, $uid)
	{
		$searchKey = -1;
		if ($uidList)
		{
			$searchKey = array_search($uid, $uidList);
			if ($searchKey === null || $searchKey === false)
			{
				$searchKey = -1;
			}
		}
		return $searchKey;
	}

	/**
	 * @param string $messageUid
	 * @param Folder $folder
	 * @param int $flags
	 * @param short $action
	 * @return bool
	 */
	function SetMessagesFlag($messageUid, &$folder, $flags, $action)
	{
		$messageUidSet = array($messageUid);
		return $this->SetMessagesFlags($messageUidSet, true, $folder, $flags, $action);
	}

	/**
	 * return bool
	 */
	function IsMailBoxEmpty()
	{
		return $this->_imapMail->isMailBoxEmpty();
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
		if ($this->_imapMail->open_mailbox($folder->FullName, false))
		{
			$flagsStr = '';
			if(($flags & MESSAGEFLAGS_Seen) == MESSAGEFLAGS_Seen)
			{
				$flagsStr .= ' \Seen';
			}
			if(($flags & MESSAGEFLAGS_Flagged) == MESSAGEFLAGS_Flagged)
			{
				$flagsStr .= ' \Flagged';
			}
			if(($flags & MESSAGEFLAGS_Deleted) == MESSAGEFLAGS_Deleted)
			{
				$flagsStr .= ' \Deleted';
			}
			if (($flags & MESSAGEFLAGS_Answered) == MESSAGEFLAGS_Answered)
			{
				$flagsStr .= ' \Answered';
			}

			if (0 < strlen(CApi::GetConf('webmail.forwarded-flag-name', '')))
			{
				if (($flags & MESSAGEFLAGS_Forwarded) == MESSAGEFLAGS_Forwarded)
				{
					$flagsStr .= ' '.CApi::GetConf('webmail.forwarded-flag-name', '');
				}
			}

			$messageIndexes = $actionName = null;
			switch($action)
			{
				case ACTION_Set:
					$actionName = '+FLAGS';
					break;
				case ACTION_Remove:
					$actionName = '-FLAGS';
					break;
			}

			if ($messageIndexSet == null)
			{
				$messageIndexes = '1:*';
				$indexAsUid = false;
				if ($this->isMailBoxEmpty())
				{
					return true;
				}
			}
			else
			{
				$messageIndexes = implode(',', $messageIndexSet);
			}

			if (null !== $actionName && '' !== $flagsStr)
			{
				if ($indexAsUid)
				{
					return $this->_imapMail->uid_store_mail_flag($messageIndexes, $actionName, $flagsStr);
				}
				else
				{
					return $this->_imapMail->store_mail_flag($messageIndexes, $actionName, $flagsStr);
				}
			}
		}
		return false;
	}

	/**
	 * @param Array $messageIndexSet
	 * @param bool $indexAsUid
	 * @param Folder $folder
	 * @return bool
	 */
	function DeleteMessages(&$messageIndexSet, $indexAsUid, &$folder)
	{
		return $this->SetMessagesFlags($messageIndexSet, $indexAsUid, $folder, MESSAGEFLAGS_Deleted, ACTION_Set);
	}

	/**
	 * @param Array $messageIndexSet
	 * @param Folder $folder
	 * @return bool
	 */
	function SetDeleteFlagAndPurgeByUids(&$messageUidSet, &$folder)
	{
		return $this->SetMessagesFlags($messageUidSet, true, $folder, MESSAGEFLAGS_Deleted, ACTION_Set) &&
			$this->PurgeUidOrFolder($folder, $messageUidSet);
	}

	/**
	 * @param Array $messageIndexSet
	 * @param bool $indexAsUid
	 * @param Folder $folder
	 * @param Folder $toFolder
	 * @return bool
	 */
	function MoveMessages(&$messageIndexSet, $indexAsUid, &$folder, &$toFolder)
	{
		if ($folder->IdDb != $toFolder->IdDb)
		{
			return $this->CopyMessages($messageIndexSet, $indexAsUid, $folder, $toFolder) &
				$this->DeleteMessages($messageIndexSet, $indexAsUid, $folder) &
				$this->PurgeFolder($folder);
		}
		return true;
	}

	/**
	 * @param Folder $folder
	 * @return bool
	 */
	function PurgeFolder(&$folder)
	{
		return $this->_imapMail->open_mailbox($folder->FullName, false) && $this->_imapMail->expunge_mailbox();
	}

	/**
	 * @param Folder $folder
	 * @param Array $arrayUids
	 */
	function PurgeUidFolder(&$folder, $arrayUids)
	{
		if (is_array($arrayUids) && count($arrayUids) > 0)
		{
			$strUids = implode(',', $arrayUids);
			return $this->_imapMail->open_mailbox($folder->FullName, false) && $this->_imapMail->expunge_uid_mailbox($strUids);
		}

		return true;
	}

	/**
	 * @param Folder $folder
	 * @param Array $arrayUids
	 */
	function PurgeUidOrFolder(&$folder, $arrayUids)
	{
		if (is_array($arrayUids) && count($arrayUids) > 0)
		{
			$strUids = implode(',', $arrayUids);
			if ($this->_imapMail->open_mailbox($folder->FullName, false))
			{
				return $this->_imapMail->expunge_uid_or_not_mailbox($strUids);
			}
		}

		return false;
	}

	/**
	 * @param Array $messageIndexSet
	 * @param bool $indexAsUid
	 * @param Folder $fromFolder
	 * @param Folder $toFolder
	 * @return bool
	 */
	function CopyMessages(&$messageIndexSet, $indexAsUid, &$fromFolder, &$toFolder)
	{
		$messageIndexes = implode(',', $messageIndexSet);
		if ($this->_imapMail->open_mailbox($fromFolder->FullName, false))
		{
			return ($indexAsUid)
				? $this->_imapMail->uid_copy_mail($messageIndexes, $toFolder->FullName)
				: $this->_imapMail->copy_mail($messageIndexes, $toFolder->FullName);
		}
		return false;
	}

	/**
	 * @param Folder $folder
	 * @return bool
	 */
	function GetFolderMessageCount(&$folder)
	{
		$countArray = $this->_imapMail->get_all_and_unnread_msg_count($folder->FullName);
		if($countArray == null)
		{
			return false;
		}

		$folder->MessageCount = $countArray[HKC_ALL_MSG];
		$folder->UnreadMessageCount = $countArray[HKC_UNSEEN_MSG];
		return true;
	}

	/**
	 * @access private
	 * @param WebMailMessage $message
	 * @param string $flags
	 */
	function _setMessageFlags(&$message, $flags)
	{
		$message->Flags = $this->getIntFlags($flags);
	}

	/**
	 * @param String $strFlags
	 * @return Integer
	 */
	function getIntFlags($flags)
	{
		$intFlags = 0;
		$flags = explode(' ', strtolower($flags));
		foreach($flags as $flag)
		{
			switch(trim($flag))
			{
				case '\seen':		$intFlags |= MESSAGEFLAGS_Seen;		break;
				case '\answered':	$intFlags |= MESSAGEFLAGS_Answered;	break;
				case '\flagged':	$intFlags |= MESSAGEFLAGS_Flagged;	break;
				case '\deleted':	$intFlags |= MESSAGEFLAGS_Deleted;	break;
				case '\draft':		$intFlags |= MESSAGEFLAGS_Draft;	break;
				case '\recent':		$intFlags |= MESSAGEFLAGS_Recent;	break;
				default:
					$sForwarded = strtolower(CApi::GetConf('webmail.forwarded-flag-name', ''));
					if (0 < strlen($sForwarded) && trim($flag) === $sForwarded)
					{
						$intFlags |= MESSAGEFLAGS_Forwarded;
					}
					break;
			}
		}
		return $intFlags;
	}

	function GetOrderByForImapSort()
	{
		$result = '';
		switch ($this->Account->DefaultOrder)
		{
			case EAccountDefaultOrder::DescFrom:		$result = 'FROM';				break;
			case EAccountDefaultOrder::AscFrom:			$result = 'REVERSE FROM';		break;
			case EAccountDefaultOrder::DescTo:			$result = 'TO';					break;
			case EAccountDefaultOrder::AscTo:			$result = 'REVERSE TO';			break;
			case EAccountDefaultOrder::DescSubject:		$result = 'SUBJECT';			break;
			case EAccountDefaultOrder::AscSubject:		$result = 'REVERSE SUBJECT';	break;
			case EAccountDefaultOrder::DescDate:		$result = 'ARRIVAL';			break;
			case EAccountDefaultOrder::AscDate:			$result = 'REVERSE ARRIVAL';	break;
			case EAccountDefaultOrder::DescSize:		$result = 'SIZE';				break;
			case EAccountDefaultOrder::AscSize:			$result = 'REVERSE SIZE';		break;
		}
		return $result;
	}

}
