<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once WM_ROOTPATH.'common/class_collectionbase.php';
	require_once WM_ROOTPATH.'common/class_systemfolders.php';
	require_once WM_ROOTPATH.'common/class_validate.php';

	define('FOLDERTYPE_Inbox', 1);
	define('FOLDERTYPE_SentItems', 2);
	define('FOLDERTYPE_Drafts', 3);
	define('FOLDERTYPE_Trash', 4);
	define('FOLDERTYPE_Spam', 5);
	define('FOLDERTYPE_Virus', 6);
	define('FOLDERTYPE_System', 9);
	define('FOLDERTYPE_Custom', 10);

	define('FOLDERSYNC_DontSync', 0);
	define('FOLDERSYNC_NewHeadersOnly', 1);
	define('FOLDERSYNC_AllHeadersOnly', 2);
	define('FOLDERSYNC_NewEntireMessages', 3);
	define('FOLDERSYNC_AllEntireMessages', 4);
	define('FOLDERSYNC_DirectMode', 5);

	define('FOLDERNAME_Inbox', 'Inbox');
	define('FOLDERNAME_SentItems', 'Sent Items');
	define('FOLDERNAME_Sent', 'Sent');
	define('FOLDERNAME_Sent_Items', 'Sent-Items');
	define('FOLDERNAME_Drafts', 'Drafts');
	define('FOLDERNAME_Trash', 'Trash');
	define('FOLDERNAME_Spam', 'Spam');
	define('FOLDERNAME_Virus', 'Quarantine');

	define('FOLDERNAME_SharedSpam', 'blacklist');
	define('FOLDERNAME_SharedUnSpam', 'whitelist');

	define('FOLDERFULLNAME_SharedSpam', 'shared.spam.blacklist');
	define('FOLDERFULLNAME_SharedUnSpam', 'shared.spam.whitelist');

	class Folder
	{
		/**
		 * @var int
		 */
		var $IdDb;

		/**
		 * @var int
		 */
		var $IdAcct;

		/**
		 * @var int
		 */
		var $IdParent = -1;

		/**
		 * @var short
		 */
		var $Type;

		/**
		 * @var string
		 */
		var $Name;

		/**
		 * @var string
		 */
		var $FullName;

		/**
		 * @var short
		 */
		var $SyncType;

		/**
		 * @var bool
		 */
		var $Hide = false;

		/**
		 * @var bool
		 */
		var $XListType = false;

		/**
		 * @var int
		 */
		var $FolderOrder;

		/**
		 * @var int
		 */
		var $MessageCount = 0;

		/**
		 * @var int
		 */
		var $UnreadMessageCount = 0;

		/**
		 * @var int
		 */
		var $Size = 0;

		/**
		 * @var FolderCollection
		 */
		var $SubFolders = null;

		/**
		 * @var int
		 */
		var $Level;

		/**
		 * @var bool
		 */
		var $ToFolder = false;

		/**
		 * @var string
		 */
		var $Flags;

		/**
		 * @param string $name
		 * @param string $fullName
		 * @param string $name optional
		 * @return Folder
		 */
		function Folder($idAcct, $idDb, $fullName, $name = null, $syncType = FOLDERSYNC_DontSync, $forceType = null)
		{
			$this->IdAcct = (int) $idAcct;
			$this->IdDb = (int) $idDb;
			$this->FullName = $fullName;
			$this->Flags = '';

			if ($name != null)
			{
				$this->Name = $name;

				$this->SyncType = $syncType;

				if (null !== $forceType)
				{
					$this->Type = $forceType;
				}
				else
				{
					switch(strtolower($name))
					{
						case strtolower(FOLDERNAME_Inbox):
							$this->Type = FOLDERTYPE_Inbox;
							break;
						case strtolower(FOLDERNAME_Sent):
						case strtolower(FOLDERNAME_SentItems):
						case strtolower(FOLDERNAME_Sent_Items):
							$this->Type = FOLDERTYPE_SentItems;
							break;
						case strtolower(FOLDERNAME_Drafts):
							$this->Type = FOLDERTYPE_Drafts;
							break;
						case strtolower(FOLDERNAME_Trash):
							$this->Type = FOLDERTYPE_Trash;
							break;
						case strtolower(FOLDERNAME_Spam):
							$this->Type = FOLDERTYPE_Spam;
							break;
						case strtolower(FOLDERNAME_Virus):
							$this->Type = FOLDERTYPE_Virus;
							break;
						default:
							$this->Type = FOLDERTYPE_Custom;
					}
				}
			}
		}

		/**
		 * @return string/bool
		 */
		function ValidateData()
		{
			if (empty($this->Name))
			{
				return JS_LANG_WarningEmptyFolderName;
			}
			elseif(!ConvertUtils::CheckDefaultWordsFileName($this->Name) || Validate::HasSpecSymbols($this->Name))
			{
				return WarningCorrectFolderName;
			}

			return true;
		}

		function GetFolderName($sDefaultIncCharset = CPAGE_ISO8859_1)
		{
			$foldername = $this->Name;
			switch($this->Type)
			{
				case FOLDERTYPE_Inbox:
					$foldername = FolderInbox;
					break;
				case FOLDERTYPE_SentItems:
					$foldername = FolderSentItems;
					break;
				case FOLDERTYPE_Drafts:
					$foldername = FolderDrafts;
					break;
				case FOLDERTYPE_Trash:
					$foldername = FolderTrash;
					break;
				case FOLDERTYPE_Spam:
					$foldername = FolderSpam;
					break;
				default:
					$foldername = ConvertUtils::IsLatin($this->Name)
						? ConvertUtils::ConvertEncoding($this->Name, CPAGE_UTF7_Imap, CPAGE_UTF8)
						: ConvertUtils::ConvertEncoding($this->Name, $sDefaultIncCharset, CPAGE_UTF8);
					break;
			}

			return $foldername;
		}

		function GetFolderFullName($sDefaultIncCharset = CPAGE_ISO8859_1)
		{
			return ConvertUtils::IsLatin($this->FullName)
				? ConvertUtils::ConvertEncoding($this->FullName, CPAGE_UTF7_Imap, CPAGE_UTF8)
				: ConvertUtils::ConvertEncoding($this->FullName, $sDefaultIncCharset, CPAGE_UTF8);
		}

		function SetFolderSync($syncType = FOLDERSYNC_DontSync)
		{
			$this->SyncType = $syncType;
		}

		/**
		 * @return bool
		 */
		function IsNoSelect()
		{
			return (false !== strpos(strtolower($this->Flags), '\noselect'));
		}

		function SetParentIdToSubFolders()
		{
			if ($this->SubFolders && 0 < $this->SubFolders->Count())
			{
				for ($i = 0, $c = $this->SubFolders->Count(); $i < $c; $i++)
				{
					$folder =& $this->SubFolders->Get($i);
					if ($folder)
					{
						$folder->IdParent = $this->IdDb;
					}
				}
			}
		}
	}

	class FolderCollection extends CollectionBase
	{
		function FolderCollection()
		{
			CollectionBase::CollectionBase();
		}

		/**
		 * @param Folder $folder
		 */
		function Add(&$folder)
		{
			$this->List->Add($folder);
		}

		/**
		 * @param Folder $folder
		 */
		function AddCopy($folder)
		{
			$this->List->Add($folder);
		}

		/**
		 * @param int $index
		 * @return Folder
		 */
		function &Get($index)
		{
			return $this->List->Get($index);
		}

		/**
		 * @param short $type
		 * @return Folder
		 */
		function &GetFolderByType($type, $bOnlyInRoot = false)
		{
			$null = null;
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder = null;
				$folder =& $this->Get($i);
				if ($folder->Type == $type)
				{
					return $folder;
				}

				if (!$bOnlyInRoot && $folder->SubFolders)
				{
					$SearchSubFolder =& $folder->SubFolders->GetFolderByType($type);
					if ($SearchSubFolder)
					{
						return $SearchSubFolder;
					}
				}

				unset($folder);
			}

			return $null;
		}

		/**
		 * @param short $type
		 */
		function RemoveSystemFlag($type)
		{
			$null = null;
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				if ($folder->Type == $type)
				{
					$folder->Type = FOLDERTYPE_Custom;
				}

				if ($folder->SubFolders)
				{
					$folder->SubFolders->RemoveSystemFlag($type);
				}
			}

			return $null;
		}

		/**
		 * @param CAccount $account
		 */
		function InitSystemFolders()
		{
			$systemFolders = SystemFolders::StaticGetSystemFoldersNames();
			$systemFolders = array_map('strtolower', $systemFolders);

			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				if ($folder->Type == FOLDERTYPE_Custom)
				{
					$loverCaseName = strtolower($folder->GetFolderFullName());
					if (in_array($loverCaseName, $systemFolders))
					{
						$folder->Type = FOLDERTYPE_System;
					}
				}

				if ($folder->SubFolders != null)
				{
					$folder->SubFolders->InitSystemFolders();
				}
			}
		}

		/**
		 * @param string $name
		 * @return Folder
		 */
		function &GetFolderByName($name)
		{
			$null = null;
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				if ($folder->Name == $name)
				{
					return $folder;
				}
			}

			return $null;
		}

		/**
		 * @param string $sName
		 * @param string $sNameSpace
		 * @return Folder
		 */
		function &GetFolderByNameWithNamespace($sName, $sNameSpace)
		{
			$sNameSpaceFolder = empty($sNameSpace) ? '' : substr($sNameSpace, 0, -1);
			$oSearch = null;
			$sSubFolders = null;

			if (!empty($sNameSpaceFolder))
			{
				$oSearch =& $this->GetFolderByFullName($sNameSpaceFolder);
				if ($oSearch)
				{
					$sSubFolders = $oSearch->SubFolders;
				}
			}
			else
			{
				$sSubFolders =& $this;
			}

			$null = $folder = null;
			if ($sSubFolders)
			{
				for ($i = 0, $c = $sSubFolders->Count(); $i < $c; $i++)
				{
					$folder =& $sSubFolders->Get($i);
					if ($folder->Name == $sName)
					{
						return $folder;
					}
				}
			}

			return $null;
		}

		/**
		 * @param string $fullName
		 * @return Folder
		 */
		function &GetFolderByFullName($fullName)
		{
			$null = null;
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				if ($folder->FullName == $fullName)
				{
					return $folder;
				}

				if ($folder->SubFolders)
				{
					$SearchSubFolder =& $folder->SubFolders->GetFolderByFullName($fullName);
					if ($SearchSubFolder)
					{
						return $SearchSubFolder;
					}
				}
				unset($folder);
			}

			return $null;
		}

		/**
		 * @param short $type
		 * @return Folder
		 */
		function &GetFirstNotHideFolder()
		{
			$null = null;
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder = &$this->Get($i);
				if (!$folder->Hide)
				{
					return $folder;
				}
			}

			return $null;
		}

		/**
		 * @param int $type
		 * @return Folder
		 */
		function &GetFolderById($id)
		{
			$null = null;
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				if ($folder->IdDb == $id)
				{
					return $folder;
				}

				if ($folder->SubFolders)
				{
					$SearchSubFolder =& $folder->SubFolders->GetFolderById($id);
					if ($SearchSubFolder)
					{
						return $SearchSubFolder;
					}
				}
				unset($folder);
			}

			return $null;
		}

		/**
		 * @return FolderCollection
		 */
		function &CreateFolderListFromTree()
		{
			$folderList = new FolderCollection();
			$this->_createFolderListFromTree($folderList, -1);
			return $folderList;
		}

		/**
		 * @access private
		 * @param FolderCollection $folderList
		 */
		function _createFolderListFromTree(&$folderList, $iIdParent)
		{
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				$folder->IdParent = $iIdParent;
				$folderList->Add($folder);

				if ($folder->SubFolders)
				{
					$folder->SubFolders->_createFolderListFromTree($folderList, $folder->IdDb);
//					$folder->SubFolders = null;
				}

				unset($folder);
			}
		}

		function SaveToSession($folders)
		{
			CSession::Set(ACCOUNT_FOLDERS, base64_encode(serialize($folders)));
		}

		function GetFromSession()
		{
			$sFolders = CSession::Get(ACCOUNT_FOLDERS, '');
			if (!empty($sFolders))
			{
				return unserialize(base64_decode($sFolders));
			}

			return null;
		}

		function SetSyncTypeToAll($syncType)
		{
			$folder = null;
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				$folder->SyncType = $syncType;

				if (!is_null($folder->SubFolders) && $folder->SubFolders->Count() > 0)
				{
					$folder->SubFolders->SetSyncTypeToAll($syncType);
				}
			}
		}

		function GetDMFolderCountsToAll(&$mailStorage)
		{
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				if ($folder && $folder->SyncType == FOLDERSYNC_DirectMode)
				{
					$mailStorage->GetFolderMessageCount($folder);
				}
				if (!is_null($folder->SubFolders) && $folder->SubFolders->Count() > 0)
				{
					$folder->SubFolders->GetDMFolderCountsToAll($mailStorage);
				}
			}
		}

		function SetDMFolderIds($acctId, $start = true, $parentId = -1)
		{
			static $f_ids;
			if ($start)
			{
				$f_ids = 1;
			}

			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				$folder->IdAcct = $acctId;
				$folder->IdDb = ++$f_ids;
				$folder->IdParent = $parentId;

				if (!is_null($folder->SubFolders) && $folder->SubFolders->Count() > 0)
				{
					$folder->SubFolders->SetDMFolderIds($acctId, false, $folder->IdDb);
				}
			}
		}

		/**
		 * @param Folder $folder
		 */
		function InitToFolder(&$folder)
		{
			$sent =& $this->GetFolderByType(FOLDERTYPE_SentItems);
			if ($sent)
			{
				if ($sent->IdDb == $folder->IdDb)
				{
					$folder->ToFolder = true;
					return;
				}
				else if ($sent->SubFolders && $sent->SubFolders->Count() > 0)
				{
					$sent->SubFolders->_setToFolderInSentDrafts($folder);
				}
			}

			$drafts =& $this->GetFolderByType(FOLDERTYPE_Drafts);
			if ($drafts)
			{
				if ($drafts->IdDb == $folder->IdDb)
				{
					$folder->ToFolder = true;
					return;
				}
				else if ($drafts->SubFolders && $drafts->SubFolders->Count() > 0)
				{
					$drafts->SubFolders->_setToFolderInSentDrafts($folder);
				}
			}
		}

		/**
		 * @param Folder $initFolder
		 */
		function _setToFolderInSentDrafts(&$initFolder)
		{
			for ($i = 0, $c = $this->Count(); $i < $c; $i++)
			{
				$folder =& $this->Get($i);
				if ($folder)
				{
					if ($initFolder->IdDb == $folder->IdDb)
					{
						$initFolder->ToFolder = true;
						return;
					}
					else if ($folder->SubFolders && $folder->SubFolders->Count() > 0)
					{
						$folder->SubFolders->_setToFolderInSentDrafts($initFolder);
					}
				}
			}
		}

		/**
		 * @return FolderCollection $folders
		 */
		function SortRootTree($oAccount)
		{
			return $this->_sortFolderCollection($oAccount, true);
		}

		/**
		 * @param CAccount $oAccount
		 * @param bool $sortSpecialFolders[optional] = false
		 * @return FolderCollection
		 */
		function _sortFolderCollection($oAccount, $sortSpecialFolders = false)
		{
			$newFoldersArray = $topArray = $footArray = array();
			$newFolders = new FolderCollection();

			foreach ($this->Instance() as $folder)
			{
				if (strlen($folder->Name) > 0 && $folder->Name[0] == '&')
				{
					$footArray[] = $folder->Name;
				}
				else
				{
					$topArray[] = $folder->Name;
				}
			}
			unset($folder);

			natcasesort($topArray);

			foreach ($topArray as $value)
			{
				//$newFoldersArray[strtolower($value)] = $value;  // TODO
				$newFoldersArray[$value] = $value;
			}
			foreach ($footArray as $value)
			{
				//$newFoldersArray[strtolower($value)] = $value; // TODO
				$newFoldersArray[$value] = $value;
			}
			unset($topArray, $footArray);

			$aNewTop = array();
			if ($sortSpecialFolders)
			{
				$aMap =& $oAccount->Domain->GetFoldersMap();
				$aMapKeys = array_keys($aMap);
				foreach ($aMapKeys as $iFolderType)
				{
					$oFolder = null;
					if (EFolderType::Custom !== $iFolderType)
					{
						$oFolder =& $this->GetFolderByType($iFolderType, true);
						if ($oFolder && $iFolderType === $oFolder->Type && isset($newFoldersArray[$oFolder->Name]))
						{
							unset($newFoldersArray[$oFolder->Name]);
							$aNewTop[] = $oFolder->Name;
						}
					}
					unset($oFolder);
				}

				if (0 < count($aNewTop))
				{
					$aNewTop = array_reverse($aNewTop);
					foreach ($aNewTop as $sValue)
					{
						array_unshift($newFoldersArray, $sValue);
					}
					unset($aNewTop);
				}
			}

			foreach ($newFoldersArray as $folderName)
			{
				$folder =& $this->GetFolderByName($folderName);
				if ($folder)
				{
					if ($folder->SubFolders && $folder->SubFolders->Count() > 0)
					{
						$folder->SubFolders = $folder->SubFolders->_sortFolderCollection($oAccount,
							EFolderType::Inbox === $folder->Type || '[Gmail]' === $folder->Name);
					}

					$newFolders->Add($folder);
				}
				unset($folder);
			}

			return $newFolders;
		}

		function _localSortFunction($oAccount, $iFolderType, $sFolderName, &$aNewFoldersArray,
			&$oNewFolders, $bUseSubSort = false)
		{
			if (EFolderType::Custom !== $iFolderType)
			{
				$oFolder =& $this->GetFolderByType($iFolderType);
				if ($oFolder && isset($aNewFoldersArray[$oFolder->Name]))
				{
					unset($aNewFoldersArray[$oFolder->Name]);

					if ($oFolder->SubFolders && $oFolder->SubFolders->Count() > 0)
					{
						$oFolder->SubFolders = $oFolder->SubFolders->_sortFolderCollection($oAccount, $bUseSubSort);
					}

					$oNewFolders->Add($oFolder);
					unset($oFolder);
				}
			}
			else if (isset($aNewFoldersArray[$sFolderName]))
			{
				$oFolder =& $this->GetFolderByName($aNewFoldersArray[$sFolderName]);
				if ($oFolder)
				{
					unset($aNewFoldersArray[$sFolderName]);

					if ($oFolder->SubFolders && $oFolder->SubFolders->Count() > 0)
					{
						$oFolder->SubFolders = $oFolder->SubFolders->_sortFolderCollection($oAccount, $bUseSubSort);
					}

					$oNewFolders->Add($oFolder);
					unset($oFolder);
				}
			}
		}

		function _localSortFunction_old($oAccount, $folderName, &$newFoldersArray, &$newFolders, $subSort = false)
		{
			//if (isset($newFoldersArray[strtolower($folderName)])) // TODO
			if (isset($newFoldersArray[$folderName]))
			{
				//$folder =& $this->GetFolderByName($newFoldersArray[strtolower($folderName)]); //TODO
				$folder =& $this->GetFolderByName($newFoldersArray[$folderName]);
				if ($folder)
				{
					if ($folder->SubFolders && $folder->SubFolders->Count() > 0)
					{
						$folder->SubFolders = $folder->SubFolders->_sortFolderCollection($oAccount, $subSort);
					}
					$newFolders->Add($folder);
					//unset($newFoldersArray[strtolower($folderName)]); // TODO
					unset($newFoldersArray[$folderName]);
					unset($folder);
				}
			}
		}
	}