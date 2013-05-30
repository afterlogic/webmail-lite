<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Mail
 */
class CApiMailManager extends AApiManagerWithStorage
{
	/**
	 * @var array
	 */
	protected $aImapClientCache;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('mail', $oManager);

		$this->inc('classes.enum');
		$this->inc('classes.folder');
		$this->inc('classes.folder-collection');
		$this->inc('classes.message');
		$this->inc('classes.message-collection');
		$this->inc('classes.attachment');
		$this->inc('classes.attachment-collection');
		$this->inc('classes.ics');
		$this->inc('classes.vcard');

		$this->aImapClientCache = array();
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return \MailSo\Imap\ImapClient | null
	 */
	protected function &getImapClient(CAccount $oAccount)
	{
		$oResult = null;
		if ($oAccount)
		{
			$sCacheKey = $oAccount->Email;
			if (!isset($this->aImapClientCache[$sCacheKey]))
			{
				$oLogger = \MailSo\Log\Logger::NewInstance()
					->Add(
						\MailSo\Log\Drivers\Callback::NewInstance(function ($sDesc) {
							CApi::Log($sDesc);
						})->DisableTimePrefix()
					)
				;

				$iConnectTimeOut = CApi::GetConf('socket.connect-timeout', 5);
				$iSocketTimeOut = CApi::GetConf('socket.get-timeout', 5);

				CApi::Plugin()->RunHook('webmail-imap-update-socket-timeouts',
					array(&$iConnectTimeOut, &$iSocketTimeOut));

				$this->aImapClientCache[$sCacheKey] = \MailSo\Imap\ImapClient::NewInstance();
				$this->aImapClientCache[$sCacheKey]->SetTimeOuts($iConnectTimeOut, $iSocketTimeOut); // TODO
				$this->aImapClientCache[$sCacheKey]->SetLogger($oLogger);
			}

			$oResult =& $this->aImapClientCache[$sCacheKey];
			if (!$oResult->IsConnected())
			{
				$oResult->Connect($oAccount->IncomingMailServer, $oAccount->IncomingMailPort,
					$oAccount->IncomingMailUseSSL
						? \MailSo\Net\Enumerations\ConnectionSecurityType::SSL
						: \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
			}

			if (!$oResult->IsLoggined())
			{
				$oResult->Login($oAccount->IncomingMailLogin, $oAccount->IncomingMailPassword);
			}
		}

		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @throws CApiManagerException
	 */
	public function ValidateAccountConnection($oAccount)
	{
		try
		{
			$oImapClient = null;
			$oImapClient =& $this->getImapClient($oAccount);
		}
		catch (\MailSo\Net\Exceptions\SocketCanNotConnectToHostException $oException)
		{
			throw new CApiManagerException(Errs::Mail_AccountConnectToMailServerFailed);
		}
		catch (\MailSo\Imap\Exceptions\LoginBadCredentialsException $oException)
		{
			throw new CApiManagerException(Errs::Mail_AccountAuthentication);
		}
		catch (\Exception $oException)
		{
			throw new CApiManagerException(Errs::Mail_AccountLoginFailed);
		}
	}

	/**
	 * @param CAccount $oAccount
	 * @param array $aSystemNames [FolderFullName => FolderType, ...]
	 *
	 * @return void
	 */
	public function SetSystemFolderNames($oAccount, $aSystemNames)
	{
		return $this->oStorage->SetSystemFolderNames($oAccount, $aSystemNames);
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array
	 */
	public function GetSystemFolderNames($oAccount)
	{
		return $this->oStorage->GetSystemFolderNames($oAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @param CApiMailFolderCollection $oFolderCollection
	 * @param bool $bCreateUnExistenSystemFilders
	 *
	 * @return bool
	 */
	private function initSystemFolders($oAccount, &$oFolderCollection, $bCreateUnExistenSystemFilders)
	{
		$bAddSystemFolder = false;
		try
		{
			$aFoldersMap = $oAccount->Domain->GetFoldersMap();
			unset($aFoldersMap[EFolderType::Inbox]);

			$aTypes = array_keys($aFoldersMap);

			$aUnExistenSystemNames = array();
			$aSystemNames = $this->GetSystemFolderNames($oAccount);

			$oInbox = $oFolderCollection->GetByFullNameRaw('INBOX');
			$oInbox->SetType(EFolderType::Inbox);

			if (is_array($aSystemNames) && 0 < count($aSystemNames))
			{
				unset($aSystemNames['INBOX']);
				$aUnExistenSystemNames = $aSystemNames;

				foreach ($aSystemNames as $sSystemFolderFullName => $iFolderType)
				{
					$iKey = array_search($iFolderType, $aTypes);
					if (false !== $iKey)
					{
						$oFolder = /* @var $oFolder CApiMailFolder */ $oFolderCollection->GetByFullNameRaw($sSystemFolderFullName, true);
						if ($oFolder)
						{
							unset($aTypes[$iKey]);
							unset($aFoldersMap[$iKey]);
							unset($aUnExistenSystemNames[$sSystemFolderFullName]);
							
							$oFolder->SetType($iFolderType);
						}
					}
				}
			}
			else
			{
				// set system type from flags
				$oFolderCollection->ForeachListWithSubFolders(function (/* @var $oFolder CApiMailFolder */ $oFolder) use (&$aTypes, &$aFoldersMap) {
						$iXListType = $oFolder->GetFolderXListType();
						$iKey = array_search($iXListType, $aTypes);

						if (false !== $iKey && EFolderType::Custom === $oFolder->Type() && isset($aFoldersMap[$iXListType]))
						{
							unset($aTypes[$iKey]);
							unset($aFoldersMap[$iXListType]);
							
							$oFolder->SetType($iXListType);
						}
					}
				);

				// set system type from domain settings
				if (is_array($aFoldersMap) && 0 < count($aFoldersMap))
				{
					$oFolderCollection->ForeachListOnRootInboxAndGmailSubFolder(
						function (/* @var $oFolder CApiMailFolder */ $oFolder) use (&$aFoldersMap) {
							if (EFolderType::Custom === $oFolder->Type())
							{
								foreach ($aFoldersMap as $iFolderType => $aFoldersNames)
								{
									$aList = array();
									if (is_array($aFoldersNames))
									{
										$aList = $aFoldersNames;
									}
									else if (is_string($aFoldersNames))
									{
										$aList = array($aFoldersNames);
									}

									if (is_array($aList) && 0 < count($aList))
									{
										if (in_array($oFolder->NameRaw(), $aList) || in_array($oFolder->Name(), $aList))
										{
											unset($aFoldersMap[$iFolderType]);

											$oFolder->SetType($iFolderType);
										}
									}
								}
							}
						}
					);
				}

				if (is_array($aFoldersMap) && 0 < count($aFoldersMap))
				{
					$sNamespace = $oFolderCollection->GetNamespace();
					foreach ($aFoldersMap as $iFolderType => $mFolderName)
					{
						$sFolderFullName = is_array($mFolderName) &&
							isset($mFolderName[0]) && is_string($mFolderName[0]) && 0 < strlen($mFolderName[0]) ?
								$mFolderName[0] : (is_string($mFolderName) && 0 < strlen($mFolderName) ? $mFolderName : '');

						if (0 < strlen($sFolderFullName))
						{
							$aUnExistenSystemNames[$sNamespace.$sFolderFullName] = $iFolderType;
						}
					}
				}
			}

			if ($bCreateUnExistenSystemFilders && is_array($aUnExistenSystemNames) && 0 < count($aUnExistenSystemNames))
			{
				foreach ($aUnExistenSystemNames as $sFolderFullName => $iFolderType)
				{
					$this->FolderCreateFromFullNameRaw($oAccount, $sFolderFullName);
					$bAddSystemFolder = true;
				}
			}
		}
		catch (Exception $oException)
		{
			$bAddSystemFolder = false;
		}

		return $bAddSystemFolder;
	}

	/**
	 * @param CAccount $oAccount
	 * @param bool $bCreateUnExistenSystemFilders = true
	 *
	 * @return CApiMailFolderCollection
	 */
	public function Folders($oAccount, $bCreateUnExistenSystemFilders = true)
	{
		$oFolderCollection = false;

		$sParent = '';
		$sListPattern = '*';

		$oImapClient =& $this->getImapClient($oAccount);

		$oNamespace = $oImapClient->GetNamespace();

		$aFolders = $oImapClient->FolderList($sParent, $sListPattern, true);
		$aSubscribedFolders = $oImapClient->FolderSubscribeList($sParent, $sListPattern);

		$aImapSubscribedFoldersHelper = array();
		if (is_array($aSubscribedFolders))
		{
			foreach ($aSubscribedFolders as /* @var $oImapFolder \MailSo\Imap\Folder */ $oImapFolder)
			{
				$aImapSubscribedFoldersHelper[] = $oImapFolder->FullNameRaw();
			}
		}

		$aMailFoldersHelper = null;
		if (is_array($aFolders))
		{
			$aMailFoldersHelper = array();

			foreach ($aFolders as /* @var $oImapFolder \MailSo\Imap\Folder */ $oImapFolder)
			{
				$aMailFoldersHelper[] = CApiMailFolder::NewInstance($oImapFolder,
					in_array($oImapFolder->FullNameRaw(), $aImapSubscribedFoldersHelper) || $oImapFolder->IsInbox()
				);
			}
		}

		if (is_array($aMailFoldersHelper))
		{
			$oFolderCollection = CApiMailFolderCollection::NewInstance();

			if ($oNamespace)
			{
				$oFolderCollection->SetNamespace($oNamespace->GetPersonalNamespace());
			}

			$oFolderCollection->InitByUnsortedMailFolderArray($aMailFoldersHelper);

			if ($this->initSystemFolders($oAccount, $oFolderCollection, $bCreateUnExistenSystemFilders) && $bCreateUnExistenSystemFilders)
			{
				$oFolderCollection = $this->Folders($oAccount, false);
			}
		}

		if ($oFolderCollection && $oNamespace)
		{
			$oFolderCollection->SetNamespace($oNamespace->GetPersonalNamespace());
		}

		$aFoldersOrderList = $this->FoldersOrder($oAccount);
		$aFoldersOrderList = is_array($aFoldersOrderList) && 0 < count($aFoldersOrderList) ? $aFoldersOrderList : null;

		$oFolderCollection->SortByCallback(function ($oFolderA, $oFolderB) use ($aFoldersOrderList) {

			if (!$aFoldersOrderList)
			{
				if (EFolderType::Custom !== $oFolderA->Type() || EFolderType::Custom !== $oFolderB->Type())
				{
					if ($oFolderA->Type() === $oFolderB->Type())
					{
						return 0;
					}

					return $oFolderA->Type() < $oFolderB->Type() ? -1 : 1;
				}
			}
			else
			{
				$iPosA = array_search($oFolderA->FullNameRaw(), $aFoldersOrderList);
				$iPosB = array_search($oFolderB->FullNameRaw(), $aFoldersOrderList);
				if (is_int($iPosA) && is_int($iPosB))
				{
					return $iPosA < $iPosB ? -1 : 1;
				}
				else if (is_int($iPosA))
				{
					return -1;
				}
				else if (is_int($iPosB))
				{
					return 1;
				}
			}

			return strnatcmp($oFolderA->FullName(), $oFolderB->FullName());
		});
		
		if (null === $aFoldersOrderList)
		{
			$aNewFoldersOrderList = array();
			$oFolderCollection->ForeachListWithSubFolders(function (/* @var $oFolder CApiMailFolder */ $oFolder) use (&$aNewFoldersOrderList) {
				if ($oFolder)
				{
					$aNewFoldersOrderList[] = $oFolder->FullNameRaw();
				}
			});

			if (0 < count($aNewFoldersOrderList))
			{
				$this->FoldersOrderUpdate($oAccount, $aNewFoldersOrderList);
			}
		}

//		if ($oAccount && $oFolderCollection && $oAccount->IsGmailAccount())
//		{
//			$oFolder = null;
//			$oFolder =& $oFolderCollection->GetByFullNameRaw('[Gmail]');
//			if ($oFolder)
//			{
//				$oFolder->SetGmailFolder(true);
//			}
//		}

		return $oFolderCollection;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array
	 */
	public function FoldersOrder($oAccount)
	{
		return $this->oStorage->FoldersOrder($oAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @param array $aOrder
	 *
	 * @return array
	 */
	public function FoldersOrderUpdate($oAccount, $aOrder)
	{
		return $this->oStorage->FoldersOrderUpdate($oAccount, $aOrder);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param bool $bSubscribeOnCreation = true
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function FolderCreateFromFullNameRaw($oAccount, $sFolderFullNameRaw, $bSubscribeOnCreation = true)
	{
		if (0 === strlen($sFolderFullNameRaw))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderCreate($sFolderFullNameRaw);

		if ($bSubscribeOnCreation)
		{
			$oImapClient->FolderSubscribe($sFolderFullNameRaw);
		}
	}

	/**
	 * @param type $oImapClient
	 * @param type $sFolderFullNameRaw
	 *
	 * @return array [$iMessageCount, $iMessageUnseenCount, $sUidNext]
	 */
	private function folderInformation($oImapClient, $sFolderFullNameRaw)
	{
		$aFolderStatus = $oImapClient->FolderStatus($sFolderFullNameRaw, array(
			\MailSo\Imap\Enumerations\FolderResponseStatus::MESSAGES,
			\MailSo\Imap\Enumerations\FolderResponseStatus::UNSEEN,
			\MailSo\Imap\Enumerations\FolderResponseStatus::UIDNEXT
		));

		$iStatusMessageCount = isset($aFolderStatus[\MailSo\Imap\Enumerations\FolderResponseStatus::MESSAGES])
			? (int) $aFolderStatus[\MailSo\Imap\Enumerations\FolderResponseStatus::MESSAGES] : 0;

		$iStatusMessageUnseenCount = isset($aFolderStatus[\MailSo\Imap\Enumerations\FolderResponseStatus::UNSEEN])
			? (int) $aFolderStatus[\MailSo\Imap\Enumerations\FolderResponseStatus::UNSEEN] : 0;

		$sStatusUidNext = isset($aFolderStatus[\MailSo\Imap\Enumerations\FolderResponseStatus::UIDNEXT])
				? (string) $aFolderStatus[\MailSo\Imap\Enumerations\FolderResponseStatus::UIDNEXT] : '0';

		if (0 === strlen($sStatusUidNext))
		{
			$sStatusUidNext = '0';
		}

		return array($iStatusMessageCount, $iStatusMessageUnseenCount, $sStatusUidNext,
			api_Utils::GenerateFolderHash($sFolderFullNameRaw, $iStatusMessageCount, $iStatusMessageUnseenCount, $sStatusUidNext));
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 *
	 * @return array
	 */
	public function FolderCounts($oAccount, $sFolderFullNameRaw)
	{
		if (0 === strlen($sFolderFullNameRaw))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		return $this->folderInformation($oImapClient, $sFolderFullNameRaw);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderNameInUtf8
	 * @param string $sDelimiter
	 * @param string $sFolderParentFullNameRaw = ''
	 * @param bool $bSubscribeOnCreation = true
	 *
	 * @throws CApiInvalidArgumentException
	 * @throws CApiBaseException
	 */
	public function FolderCreate($oAccount, $sFolderNameInUtf8, $sDelimiter, $sFolderParentFullNameRaw = '', $bSubscribeOnCreation = true)
	{
		if (0 === strlen($sFolderNameInUtf8) || 0 === strlen($sDelimiter))
		{
			throw new CApiInvalidArgumentException();
		}

		$sFolderNameInUtf8 = trim($sFolderNameInUtf8);

		if (0 < strlen($sFolderParentFullNameRaw))
		{
			$sFolderParentFullNameRaw .= $sDelimiter;
		}

		$sNameToCreate = \MailSo\Base\Utils::ConvertEncoding($sFolderNameInUtf8,
			\MailSo\Base\Enumerations\Charset::UTF_8,
			\MailSo\Base\Enumerations\Charset::UTF_7_IMAP);

		if (0 < strlen($sDelimiter) && false !== strpos($sNameToCreate, $sDelimiter))
		{
			// TODO
			throw new CApiBaseException(Errs::Mail_FolderNameContainDelimiter);
		}

		$this->FolderCreateFromFullNameRaw(
			$oAccount, $sFolderParentFullNameRaw.$sNameToCreate, $bSubscribeOnCreation);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param bool $bUnsubscribeOnDeletion = true
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function FolderDelete($oAccount, $sFolderFullNameRaw, $bUnsubscribeOnDeletion = true)
	{
		if (0 === strlen($sFolderFullNameRaw))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		if ($bUnsubscribeOnDeletion)
		{
			$oImapClient->FolderUnSubscribe($sFolderFullNameRaw);
		}

		$oImapClient->FolderDelete($sFolderFullNameRaw);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sPrevFolderFullNameRaw
	 * @param string $sNewTopFolderNameInUtf8 = ''
	 *
	 * @throws CApiInvalidArgumentException
	 * @throws CApiBaseException
	 */
	public function FolderRename($oAccount, $sPrevFolderFullNameRaw, $sNewTopFolderNameInUtf8)
	{
		$sNewTopFolderNameInUtf8 = trim($sNewTopFolderNameInUtf8);
		if (0 === strlen($sPrevFolderFullNameRaw) || 0 === strlen($sNewTopFolderNameInUtf8))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$aFolders = $oImapClient->FolderList('', $sPrevFolderFullNameRaw);
		if (!is_array($aFolders) || !isset($aFolders[0]))
		{
			// TODO
			throw new CApiBaseException(Errs::Mail_CannotRenameNonExistenFolder);
		}

		$sDelimiter = $aFolders[0]->Delimiter();
		$iLast = strrpos($sPrevFolderFullNameRaw, $sDelimiter);
		$sFolderParentFullNameRaw = false === $iLast ? '' : substr($sPrevFolderFullNameRaw, 0, $iLast + 1);

		$aSubscribeFolders = $oImapClient->FolderSubscribeList($sPrevFolderFullNameRaw, '*');
		if (is_array($aSubscribeFolders) && 0 < count($aSubscribeFolders))
		{
			foreach ($aSubscribeFolders as /* @var $oFolder \MailSo\Imap\Folder */ $oFolder)
			{
				$oImapClient->FolderUnSubscribe($oFolder->FullNameRaw());
			}
		}

		$sNewFolderFullNameRaw = \MailSo\Base\Utils::ConvertEncoding($sNewTopFolderNameInUtf8,
			\MailSo\Base\Enumerations\Charset::UTF_8,
			\MailSo\Base\Enumerations\Charset::UTF_7_IMAP);

		if (0 < strlen($sDelimiter) && false !== strpos($sNewFolderFullNameRaw, $sDelimiter))
		{
			// TODO
			throw new CApiBaseException(Errs::Mail_FolderNameContainDelimiter);
		}

		$sNewFolderFullNameRaw = $sFolderParentFullNameRaw.$sNewFolderFullNameRaw;

		$oImapClient->FolderRename($sPrevFolderFullNameRaw, $sNewFolderFullNameRaw);

		if (is_array($aSubscribeFolders) && 0 < count($aSubscribeFolders))
		{
			foreach ($aSubscribeFolders as /* @var $oFolder \MailSo\Imap\Folder */ $oFolder)
			{
				$sFolderFullNameRawForResubscrine = $oFolder->FullNameRaw();
				if (0 === strpos($sFolderFullNameRawForResubscrine, $sPrevFolderFullNameRaw))
				{
					$sNewFolderFullNameRawForResubscrine = $sNewFolderFullNameRaw.
						substr($sFolderFullNameRawForResubscrine, strlen($sPrevFolderFullNameRaw));

					$oImapClient->FolderSubscribe($sNewFolderFullNameRawForResubscrine);
				}
			}
		}

		$aOrders = $this->oStorage->FoldersOrder($oAccount);
		if (is_array($aOrders))
		{
			foreach ($aOrders as &$sName)
			{
				if ($sPrevFolderFullNameRaw === $sName)
				{
					$sName = $sNewFolderFullNameRaw;
					$this->oStorage->FoldersOrderUpdate($oAccount, $aOrders);
					break;
				}
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param bool $bSubscribeAction = true
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function FolderSubscribe($oAccount, $sFolderFullNameRaw, $bSubscribeAction = true)
	{
		if (0 === strlen($sFolderFullNameRaw))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		if ($bSubscribeAction)
		{
			$oImapClient->FolderSubscribe($sFolderFullNameRaw);
		}
		else
		{
			$oImapClient->FolderUnSubscribe($sFolderFullNameRaw);
		}
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function FolderClear($oAccount, $sFolderFullNameRaw)
	{
		if (0 === strlen($sFolderFullNameRaw))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderSelect($sFolderFullNameRaw);

		$oInfo = $oImapClient->FolderCurrentInformation();
		if ($oInfo && 0 < $oInfo->Exists)
		{
			$oImapClient->MessageStoreFlag('1:*', false,
				array(\MailSo\Imap\Enumerations\MessageFlag::DELETED),
				\MailSo\Imap\Enumerations\StoreAction::ADD_FLAGS_SILENT
			);
		}

		$oImapClient->MessageExpunge();
	}

	/**
	 * @param int $iSortOrder
	 *
	 * @return array
	 */
	protected function getSortTypesByTypeAndOrder($iSortOrder)
	{
		$aResult = array('ARRIVAL');

		if (ESortOrder::DESC === $iSortOrder && 0 < count($aResult))
		{
			array_unshift($aResult, 'REVERSE');
		}

		return $aResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param array $aUids
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageDelete($oAccount, $sFolderFullNameRaw, $aUids)
	{
		if (0 === strlen($sFolderFullNameRaw) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderSelect($sFolderFullNameRaw);

		$sUidsRange = implode(',', $aUids);

		$oImapClient->MessageStoreFlag($sUidsRange, true,
			array(\MailSo\Imap\Enumerations\MessageFlag::DELETED),
			\MailSo\Imap\Enumerations\StoreAction::ADD_FLAGS_SILENT
		);

		$oImapClient->MessageExpunge($sUidsRange, true);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFromFolderFullNameRaw
	 * @param string $sToFolderFullNameRaw
	 * @param array $aUids
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageMove($oAccount, $sFromFolderFullNameRaw, $sToFolderFullNameRaw, $aUids)
	{
		if (0 === strlen($sFromFolderFullNameRaw) || 0 === strlen($sToFolderFullNameRaw) ||
			!is_array($aUids) || 0 === count($aUids))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderSelect($sFromFolderFullNameRaw);

		$oImapClient->MessageCopy($sToFolderFullNameRaw, implode(',', $aUids), true);

		$this->MessageDelete($oAccount, $sFromFolderFullNameRaw, $aUids);
	}

	/**
	 * @param CAccount $oAccount
	 * @param \MailSo\Mime\Message $oMessage
	 * @param string $sSentFolder = ''
	 * @param string $sDraftFolder = ''
	 * @param string $sDraftUid = ''
	 *
	 * @return array|bool
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageSend($oAccount, $oMessage, $sSentFolder = '', $sDraftFolder = '', $sDraftUid = '')
	{
		if (!$oAccount || !$oMessage)
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$rMessageStream = \MailSo\Base\ResourceRegistry::CreateMemoryResource();

		$iMessageStreamSize = \MailSo\Base\Utils::MultipleStreamWriter(
			$oMessage->ToStream(true), array($rMessageStream), 8192, true, true);

		$mResult = false;
		if (false !== $iMessageStreamSize && is_resource($rMessageStream))
		{
			rewind($rMessageStream);

			$oRcpt = $oMessage->GetRcpt();
			if ($oRcpt && 0 < $oRcpt->Count())
			{
				try
				{
					$iConnectTimeOut = CApi::GetConf('socket.connect-timeout', 5);
					$iSocketTimeOut = CApi::GetConf('socket.get-timeout', 5);

					CApi::Plugin()->RunHook('webmail-smtp-update-socket-timeouts',
						array(&$iConnectTimeOut, &$iSocketTimeOut));

					$oSmtpClient = \MailSo\Smtp\SmtpClient::NewInstance();
					$oSmtpClient->SetTimeOuts($iConnectTimeOut, $iSocketTimeOut);

					$oLogger = $oImapClient->Logger();
					if ($oLogger)
					{
						$oSmtpClient->SetLogger($oLogger);
					}

					$iSecure = \MailSo\Net\Enumerations\ConnectionSecurityType::AUTO_DETECT;
					if ($oAccount->OutgoingMailUseSSL)
					{
						$iSecure = \MailSo\Net\Enumerations\ConnectionSecurityType::SSL;
					}

					$sOutgoingMailLogin = $oAccount->OutgoingMailLogin;
					$sOutgoingMailLogin = 0 <  strlen($sOutgoingMailLogin) ? $sOutgoingMailLogin : $oAccount->IncomingMailLogin;

					$sOutgoingMailPassword = $oAccount->OutgoingMailPassword;
					$sOutgoingMailPassword = 0 < strlen($sOutgoingMailPassword) ? $sOutgoingMailPassword : $oAccount->IncomingMailPassword;

					$oSmtpClient->Connect($oAccount->OutgoingMailServer, $oAccount->OutgoingMailPort, 'localhost', $iSecure);
					if ($oAccount->OutgoingMailAuth)
					{
						$oSmtpClient->Login($sOutgoingMailLogin, $sOutgoingMailPassword);
					}

					$oSmtpClient->MailFrom($oAccount->Email, (string) $iMessageStreamSize);

					$aRcpt =& $oRcpt->GetAsArray();
					foreach ($aRcpt as /* @var $oEmail \MailSo\Mime\Email */ $oEmail)
					{
						$oSmtpClient->Rcpt($oEmail->GetEmail());
					}

					$oSmtpClient->DataWithStream($rMessageStream);

					$oSmtpClient->LogoutAndDisconnect();
				}
				catch (\MailSo\Net\Exceptions\ConnectionException $oException)
				{
					throw new \CApiManagerException(Errs::Mail_AccountConnectToMailServerFailed, $oException);
				}
				catch (\MailSo\Smtp\Exceptions\LoginException $oException)
				{
					throw new \CApiManagerException(Errs::Mail_AccountLoginFailed, $oException);
				}
				catch (\MailSo\Smtp\Exceptions\NegativeResponseException $oException)
				{
					throw new \CApiManagerException(Errs::Mail_CannotSendMessage, $oException);
				}

				rewind($rMessageStream);

				if (0 < strlen($sSentFolder))
				{
					try
					{
						$oImapClient->MessageAppendStream(
							$sSentFolder, $rMessageStream, $iMessageStreamSize, array(
								\MailSo\Imap\Enumerations\MessageFlag::SEEN
							));
					}
					catch (\Exception $oException) {}
				}

				if (0 < strlen($sDraftFolder) && 0 < strlen($sDraftUid))
				{
					try
					{
						$this->MessageDelete($oAccount, $sDraftFolder, array($sDraftUid));
					}
					catch (\Exception $oException) {}
				}

				$mResult = true;
			}
			else
			{
				throw new \CApiManagerException(Errs::Mail_InvalidRecipients);
			}
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param \MailSo\Mime\Message $oMessage
	 * @param string $sDraftFolder
	 * @param string $sMessageUid = ''
	 *
	 * @return array|bool
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageSave($oAccount, $oMessage, $sDraftFolder, $sDraftUid = '')
	{
		if (!$oAccount || !$oMessage || 0 === strlen($sDraftFolder))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$rMessageStream = \MailSo\Base\ResourceRegistry::CreateMemoryResource();

		$iMessageStreamSize = \MailSo\Base\Utils::MultipleStreamWriter(
			$oMessage->ToStream(false), array($rMessageStream), 8192, true, true);

		$mResult = false;
		if (false !== $iMessageStreamSize && is_resource($rMessageStream))
		{
			rewind($rMessageStream);

			$iNewUid = 0;

			$oImapClient->MessageAppendStream(
				$sDraftFolder, $rMessageStream, $iMessageStreamSize, array(
					\MailSo\Imap\Enumerations\MessageFlag::SEEN
				), $iNewUid);

			if (null === $iNewUid || 0 === $iNewUid)
			{
				$sMessageId = $oMessage->MessageId();
				if (0 < strlen($sMessageId))
				{
					$iNewUid = $this->FindMessageUidByMessageId($oAccount, $sDraftFolder, $sMessageId);
				}
			}

			$mResult = true;

			if (0 < strlen($sDraftFolder) && 0 < strlen($sDraftUid))
			{
				$this->MessageDelete($oAccount, $sDraftFolder, array($sDraftUid));
			}

			if (null !== $iNewUid && 0 < $iNewUid)
			{
				$mResult = array(
					'NewFolder' => $sDraftFolder,
					'NewUid' => $iNewUid
				);
			}
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param array $aUids
	 * @param string $sFlagString
	 * @param int $iAction = EMailMessageStoreAction::Add
	 * @param bool $bSetToAll = false
	 *
	 * @return true
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageFlag($oAccount, $sFolderFullNameRaw, $aUids, $sFlagString, $iAction = EMailMessageStoreAction::Add, $bSetToAll = false)
	{
		if (0 === strlen($sFolderFullNameRaw) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderSelect($sFolderFullNameRaw);

		$sUids = implode(',', $aUids);

		if ($bSetToAll)
		{
			$sUids = '1:*';
			$oInfo = $oImapClient->FolderCurrentInformation();
			if ($oInfo && 0 === $oInfo->Exists)
			{
				return true;
			}
		}

		$sResultAction = \MailSo\Imap\Enumerations\StoreAction::ADD_FLAGS_SILENT;
		switch ($iAction)
		{
			case EMailMessageStoreAction::Add:
				$sResultAction = \MailSo\Imap\Enumerations\StoreAction::ADD_FLAGS_SILENT;
				break;
			case EMailMessageStoreAction::Remove:
				$sResultAction = \MailSo\Imap\Enumerations\StoreAction::REMOVE_FLAGS_SILENT;
				break;
			case EMailMessageStoreAction::Set:
				$sResultAction = \MailSo\Imap\Enumerations\StoreAction::SET_FLAGS_SILENT;
				break;
		}

		$oImapClient->MessageStoreFlag($sUids, $bSetToAll ? false : true, explode(' ', $sFlagString), $sResultAction);
		return true;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderName
	 * @param string $sMessageId
	 *
	 * @return int|null
	 */
	public function FindMessageUidByMessageId($oAccount, $sFolderName, $sMessageId)
	{
		if (0 === strlen($sFolderName) || 0 === strlen($sMessageId))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderName);

		$sSearchCriterias = \MailSo\Imap\SearchBuilder::NewInstance()
			->AddAnd('HEADER MESSAGE-ID', $sMessageId)
			->Complete();

		$aUids = $oImapClient->MessageSimpleSearch($sSearchCriterias, true);

		return is_array($aUids) && 1 === count($aUids) && is_numeric($aUids[0]) ? (int) $aUids[0] : null;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array|bool
	 */
	public function Quota($oAccount)
	{
		$oImapClient =& $this->getImapClient($oAccount);
		
		return $oImapClient->Quota();
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param int $iUid
	 * @param string $sRfc822SubMimeIndex = ''
	 * @param bool $bParseICal = false
	 *
	 * @return CApiMailMessage
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function Message($oAccount, $sFolderFullNameRaw, $iUid, $sRfc822SubMimeIndex = '', $bParseICal = false)
	{
		if (0 === strlen($sFolderFullNameRaw) || !is_numeric($iUid) || 0 >= (int) $iUid)
		{
			throw new CApiInvalidArgumentException();
		}

		$iUid = (int) $iUid;

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$oMessage = false;

		$sTextMimeIndex = '';
		$sICalMimeIndex = '';
		$sVCardMimeIndex = '';

		$aFetchResponse = $oImapClient->Fetch(array(
			\MailSo\Imap\Enumerations\FetchType::BODYSTRUCTURE), $iUid, true);
		
		$oBodyStructure = (0 < count($aFetchResponse)) ? $aFetchResponse[0]->GetFetchBodyStructure($sRfc822SubMimeIndex) : null;
		
		if ($oBodyStructure)
		{
			$oTextPart = $oBodyStructure->SearchHtmlOrPlainPart();
			$sTextMimeIndex = $oTextPart ? $oTextPart->PartID() : '';
			
			if ($bParseICal)
			{
				$aICalPart = $oBodyStructure->SearchByContentType('text/calendar');
				$oICalPart = is_array($aICalPart) && 0 < count($aICalPart) ? $aICalPart[0] : null;
				$sICalMimeIndex = $oICalPart ? $oICalPart->PartID() : '';

				$aVCardPart = $oBodyStructure->SearchByContentType('text/vcard');
				$aVCardPart = $aVCardPart ? $aVCardPart : $oBodyStructure->SearchByContentType('text/x-vcard');
				$oVCardPart = is_array($aVCardPart) && 0 < count($aVCardPart) ? $aVCardPart[0] : null;
				$sVCardMimeIndex = $oVCardPart ? $oVCardPart->PartID() : '';
			}
		}

		$aFetchItems = array(
			\MailSo\Imap\Enumerations\FetchType::INDEX,
			\MailSo\Imap\Enumerations\FetchType::UID,
			\MailSo\Imap\Enumerations\FetchType::RFC822_SIZE,
			\MailSo\Imap\Enumerations\FetchType::INTERNALDATE,
			\MailSo\Imap\Enumerations\FetchType::FLAGS,
			0 < strlen($sRfc822SubMimeIndex)
				? \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sRfc822SubMimeIndex.'.HEADER]'
				: \MailSo\Imap\Enumerations\FetchType::BODY_HEADER_PEEK
		);

		if (0 < strlen($sTextMimeIndex))
		{
			$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sTextMimeIndex.']';
		}
		
		if (0 < strlen($sICalMimeIndex))
		{
			$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sICalMimeIndex.']';
		}
		
		if (0 < strlen($sVCardMimeIndex))
		{
			$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sVCardMimeIndex.']';
		}

		if (!$oBodyStructure)
		{
			$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODYSTRUCTURE;
		}

		$aFetchResponse = $oImapClient->Fetch($aFetchItems, $iUid, true);
		if (0 < count($aFetchResponse))
		{
			$oMessage = CApiMailMessage::NewFetchResponseInstance($sFolderFullNameRaw, $aFetchResponse[0], $oBodyStructure, $sRfc822SubMimeIndex);
		}

		if ($oMessage)
		{
			$sFromEmail = '';
			$oFromCollection = $oMessage->From();
			if ($oFromCollection && 0 < $oFromCollection->Count())
			{
				$oFrom =& $oFromCollection->GetByIndex(0);
				if ($oFrom)
				{
					$sFromEmail = trim($oFrom->GetEmail());
				}
			}

			if (0 < strlen($sFromEmail))
			{
				$oApiUsersManager = /* @var CApiUsersManager */ CApi::Manager('users');
				$oMessage->SetSafety($oApiUsersManager->GetSafetySender($oAccount->IdUser, $sFromEmail, true));
			}

			if ($bParseICal)
			{
				$oApiFileCache = /* @var $oApiFileCache CApiFilecacheManager */ CApi::Manager('filecache');
				$oApiCollaboration = /* @var $oApiCollaboration CApiCollaborationManager */ CApi::Manager('collaboration');
				$oApiLicensing = /* @var $oApiLicensing CApiLicensingManager */ CApi::Manager('licensing');

				// ICAL
				$sICal = $oMessage->GetExtend('ICAL_RAW');
				if (!empty($sICal))
				{
					$oApiCalendarManager = CApi::Manager('calendar');
					if ($oApiCalendarManager)
					{
						$mResult = $oApiCalendarManager->PreprocessICS($oAccount, trim($sICal), $sFromEmail);
						if (is_array($mResult) && !empty($mResult['Action']) && !empty($mResult['Body']))
						{
							$sTemptFile = md5($mResult['Body']).'.ics';
							if ($oApiFileCache && $oApiFileCache->Put($oAccount, $sTemptFile, $mResult['Body']))
							{
								$oIcs = CApiMailIcs::NewInstance();

								$oIcs->Uid = $mResult['UID'];
								$oIcs->File = $sTemptFile;
								$oIcs->Type = $mResult['Action'];
								$oIcs->Location = !empty($mResult['Location']) ? $mResult['Location'] : '';
								$oIcs->Description = !empty($mResult['Description']) ? $mResult['Description'] : '';
								$oIcs->When = !empty($mResult['When']) ? $mResult['When'] : '';
								$oIcs->CalendarId = !empty($mResult['CalendarId']) ? $mResult['CalendarId'] : '';
//								$oIcs->Calendars = array();

								if (!$oApiLicensing || !$oApiCollaboration || !$oApiCollaboration->IsCalendarAppointmentsSupported())
								{
									$oIcs->Type = '';
								}

//								if (isset($mResult['Calendars']) && is_array($mResult['Calendars']) && 0 < count($mResult['Calendars']))
//								{
//									foreach ($mResult['Calendars'] as $sUid => $sName)
//									{
//										$oIcs->Calendars[$sUid] = $sName;
//									}
//								}

								$oMessage->AddExtend('ICAL', $oIcs);
							}
							else
							{
								CApi::Log('Can\'t save temp file "'.$sTemptFile.'"', ELogLevel::Error);
							}
						}
					}
				}

				// VCARD
				$sVCard = $oMessage->GetExtend('VCARD_RAW');
				if (!empty($sVCard))
				{
					$oContact = new CContact();
					$oContact->InitFromVCardStr($oAccount->IdUser, $sVCard);
					$oContact->InitBeforeChange();

					$oContact->IdContact = 0;

					$bContactExists = false;
					if (0 < strlen($oContact->ViewEmail))
					{
						$oApiContactsManager = CApi::Manager('contacts');
						if ($oApiContactsManager)
						{
							$oLocalContact = $oApiContactsManager->GetContactByEmail($oAccount->IdUser, $oContact->ViewEmail);
							if ($oLocalContact)
							{
								$oContact->IdContact = $oLocalContact->IdContact;
								$bContactExists = true;
							}
						}
					}

					$sTemptFile = md5($sVCard).'.vcf';
					if ($oApiFileCache && $oApiFileCache->Put($oAccount, $sTemptFile, $sVCard))
					{
						$oVcard = CApiMailVcard::NewInstance();

						$oVcard->Uid = $oContact->IdContact;
						$oVcard->File = $sTemptFile;
						$oVcard->Exists = !!$bContactExists;
						$oVcard->Name = $oContact->FullName;
						$oVcard->Email = $oContact->ViewEmail;

						$oMessage->AddExtend('VCARD', $oVcard);
					}
					else
					{
						CApi::Log('Can\'t save temp file "'.$sTemptFile.'"', ELogLevel::Error);
					}					
				}
			}
		}

		return $oMessage;
	}

	/**
	 * @param CAccount $oAccount
	 * @param mixed $mCallback
	 * @param string $sFolderName
	 * @param int $iUid
	 * @param string $sMimeIndex = ''
	 *
	 * @return bool
	 *
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 * @throws \MailSo\Net\Exceptions\Exception
	 * @throws \MailSo\Imap\Exceptions\Exception
	 */
	public function MessageMimeStream($oAccount, $mCallback, $sFolderName, $iUid, $sMimeIndex = '')
	{
		if (!is_callable($mCallback))
		{
			throw new \MailSo\Base\Exceptions\InvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderName);

		$sFileName = '';
		$sContentType = '';
		$sMailEncodingName = '';

		$sMimeIndex = trim($sMimeIndex);
		$aFetchResponse = $oImapClient->Fetch(array(
			0 === strlen($sMimeIndex)
				? \MailSo\Imap\Enumerations\FetchType::BODY_HEADER_PEEK
				: \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sMimeIndex.'.MIME]'
		), $iUid, true);

		if (0 < count($aFetchResponse))
		{
			$sMime = $aFetchResponse[0]->GetFetchValue(
				0 === strlen($sMimeIndex)
					? \MailSo\Imap\Enumerations\FetchType::BODY_HEADER
					: \MailSo\Imap\Enumerations\FetchType::BODY.'['.$sMimeIndex.'.MIME]'
			);

			if (!empty($sMime))
			{
				$oHeaders = \MailSo\Mime\HeaderCollection::NewInstance()->Parse($sMime);

				if (!empty($sMimeIndex))
				{
					$sFileName = $oHeaders->ParameterValue(
						\MailSo\Mime\Enumerations\Header::CONTENT_DISPOSITION,
						\MailSo\Mime\Enumerations\Parameter::FILENAME);

					if (empty($sFileName))
					{
						$sFileName = $oHeaders->ParameterValue(
							\MailSo\Mime\Enumerations\Header::CONTENT_TYPE,
							\MailSo\Mime\Enumerations\Parameter::NAME);
					}

					$sMailEncodingName = $oHeaders->ValueByName(
						\MailSo\Mime\Enumerations\Header::CONTENT_TRANSFER_ENCODING);

					$sContentType = $oHeaders->ValueByName(
						\MailSo\Mime\Enumerations\Header::CONTENT_TYPE);
				}
				else
				{
					$sSubject = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::SUBJECT);
					$sFileName = (empty($sSubject) ? (string) $iUid : $sSubject).'.eml';
					$sContentType = 'message/rfc822';
				}
			}
		}

		$aFetchResponse = $oImapClient->Fetch(array(
			array(\MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sMimeIndex.']',
				function ($sParent, $sLiteralAtomUpperCase, $rImapLiteralStream) use ($mCallback, $sMimeIndex, $sMailEncodingName, $sContentType, $sFileName)
				{
					if (!empty($sLiteralAtomUpperCase))
					{
						if (is_resource($rImapLiteralStream) && 'FETCH' === $sParent)
						{
							$rMessageMimeIndexStream = (empty($sMailEncodingName))
								? $rImapLiteralStream
								: \MailSo\Base\StreamWrappers\Binary::CreateStream($rImapLiteralStream,
									\MailSo\Base\StreamWrappers\Binary::GetInlineDecodeOrEncodeFunctionName(
										$sMailEncodingName, true));

							call_user_func($mCallback, $rMessageMimeIndexStream, $sContentType, $sFileName, $sMimeIndex);
						}
					}
				}
			)), $iUid, true);

		return ($aFetchResponse && 1 === count($aFetchResponse));
	}
	
	/**
	 * @param string $sSearch
	 *
	 * @return string
	 */
	private function escapeSearchString($oImapClient, $sSearch)
	{
		return ('ssl://imap.gmail.com' === strtolower($oImapClient->GetConnectedHost())) // gmail
			? '{'.strlen($sSearch).'+}'."\r\n".$sSearch
			: $oImapClient->EscapeString($sSearch);
	}

	/**
	 * @param string $sDate
	 * @param int $iTimeZoneOffset
	 *
	 * @return int
	 */
	private function parseSearchDate($sDate, $iTimeZoneOffset)
	{
		$iResult = 0;
		if (0 < strlen($sDate))
		{
			$oDateTime = \DateTime::createFromFormat('Y.m.d', $sDate, \MailSo\Base\DateTimeHelper::GetUtcTimeZoneObject());
			return $oDateTime ? $oDateTime->getTimestamp() - $iTimeZoneOffset : 0;
		}

		return $iResult;
	}

	/**
	 * @param string $sSearch
	 *
	 * @return array
	 */
	private function parseSearchString($sSearch)
	{
		$aResult = array(
			'OTHER' => ''
		);

		$aCache = array();

		$sSearch = \trim(\preg_replace('/[\s]+/', ' ', $sSearch));
		$sSearch = \trim(\preg_replace('/(e?mail|from|to|subject|has|date|text|body): /i', '\\1:', $sSearch));

		$mMatch = array();
		\preg_match_all('/".*?(?<!\\\)"/', $sSearch, $mMatch);
		if (\is_array($mMatch) && isset($mMatch[0]) && \is_array($mMatch[0]) && 0 < \count($mMatch[0]))
		{
			foreach ($mMatch[0] as $sItem)
			{
				do
				{
					$sKey = \md5(\mt_rand(10000, 90000).\microtime(true));
				}
				while (isset($aCache[$sKey]));

				$aCache[$sKey] = \stripcslashes($sItem);
				$sSearch = \str_replace($sItem, $sKey, $sSearch);
			}
		}

		\preg_match_all('/\'.*?(?<!\\\)\'/', $sSearch, $mMatch);
		if (\is_array($mMatch) && isset($mMatch[0]) && \is_array($mMatch[0]) && 0 < \count($mMatch[0]))
		{
			foreach ($mMatch[0] as $sItem)
			{
				do
				{
					$sKey = \md5(\mt_rand(10000, 90000).\microtime(true));
				}
				while (isset($aCache[$sKey]));

				$aCache[$sKey] = \stripcslashes($sItem);
				$sSearch = \str_replace($sItem, $sKey, $sSearch);
			}
		}

		$mMatch = array();
		\preg_match_all('/(e?mail|from|to|subject|has|date|text|body):([^\s]*)/i', $sSearch, $mMatch);
		if (\is_array($mMatch) && isset($mMatch[1]) && \is_array($mMatch[1]) && 0 < \count($mMatch[1]))
		{
			if (\is_array($mMatch[0]))
			{
				foreach ($mMatch[0] as $sToken)
				{
					$sSearch = \str_replace($sToken, '', $sSearch);
				}

				$sSearch = \trim(\preg_replace('/[\s]+/', ' ', $sSearch));
			}

			foreach ($mMatch[1] as $iIndex => $sName)
			{
				if (isset($mMatch[2][$iIndex]) && 0 < \strlen($mMatch[2][$iIndex]))
				{
					$sName = \strtoupper($sName);
					$sValue = $mMatch[2][$iIndex];
					switch ($sName)
					{
						case 'TEXT':
						case 'BODY':
						case 'EMAIL':
						case 'MAIL':
						case 'FROM':
						case 'TO':
						case 'SUBJECT':
						case 'HAS':
						case 'DATE':
							if ('MAIL' === $sName)
							{
								$sName = 'EMAIL';
							}
							$aResult[$sName] = $sValue;
							break;
					}
				}
			}
		}

		$aResult['OTHER'] = $sSearch;
		foreach ($aResult as $sName => $sValue)
		{
			if (isset($aCache[$sValue]))
			{
				$aResult[$sName] = trim($aCache[$sValue], '"\' ');
			}
		}

		return $aResult;
	}

	/**
	 * @param Object $oImapClient
	 * @param string $sSearch
	 * @param int $iTimeZoneOffset = 0
	 *
	 * @return \MailSo\Imap\SearchBuilder
	 */
	private function getSearchBuilder($oImapClient, $sSearch, $iTimeZoneOffset = 0)
	{
		$oSearchBuilder = \MailSo\Imap\SearchBuilder::NewInstance();

		$aLines = $this->parseSearchString($sSearch);

		if (1 === count($aLines) && isset($aLines['OTHER']))
		{
			if (true)
			{
				$sValue = $this->escapeSearchString($oImapClient, $aLines['OTHER']);
				
				$oSearchBuilder->AddOr('FROM', $sValue);
				$oSearchBuilder->AddOr('TO', $sValue);
				$oSearchBuilder->AddOr('CC', $sValue);
				$oSearchBuilder->AddOr('SUBJECT', $sValue);
			}
			else
			{
				$oSearchBuilder->AddOr('TEXT', $this->escapeSearchString($oImapClient, $aLines['OTHER']));
			}
		}
		else
		{
			if (isset($aLines['EMAIL']))
			{
				$sValue = $this->escapeSearchString($oImapClient, $aLines['EMAIL']);
				$oSearchBuilder->AddOr('FROM', $sValue);
				$oSearchBuilder->AddOr('TO', $sValue);
				$oSearchBuilder->AddOr('CC', $sValue);
				unset($aLines['EMAIL']);
			}

			if (isset($aLines['TO']))
			{
				$sValue = $this->escapeSearchString($oImapClient, $aLines['TO']);
				$oSearchBuilder->AddAnd('TO', $sValue);
				$oSearchBuilder->AddOr('CC', $sValue);
				unset($aLines['TO']);
			}

			$sMainText = '';
			foreach ($aLines as $sName => $sRawValue)
			{
				if ('' === \trim($sRawValue))
				{
					continue;
				}

				$sValue = $this->escapeSearchString($oImapClient, $sRawValue);
				switch ($sName)
				{
					case 'FROM':
						$oSearchBuilder->AddAnd('FROM', $sValue);
						break;
					case 'SUBJECT':
						$oSearchBuilder->AddAnd('SUBJECT', $sValue);
						break;
					case 'OTHER':
					case 'BODY':
					case 'TEXT':
						$sMainText .= ' '.$sRawValue;
						break;
					case 'HAS':
						if (false !== strpos($sRawValue, 'attach'))
						{
							$oSearchBuilder->AddAnd('HEADER CONTENT-TYPE', '"MULTIPART/MIXED"');
						}
						if (false !== strpos($sRawValue, 'flag'))
						{
							$oSearchBuilder->AddAnd('FLAGGED');
						}
						if (false !== strpos($sRawValue, 'unseen'))
						{
							$oSearchBuilder->AddAnd('UNSEEN');
						}
						break;
					case 'DATE':
						$iDateStampFrom = $iDateStampTo = 0;

						$sDate = $sRawValue;
						$aDate = explode('/', $sDate);

						if (is_array($aDate) && 2 === count($aDate))
						{
							if (0 < strlen($aDate[0]))
							{
								$iDateStampFrom = $this->parseSearchDate($aDate[0], $iTimeZoneOffset);
							}

							if (0 < strlen($aDate[1]))
							{
								$iDateStampTo = $this->parseSearchDate($aDate[1], $iTimeZoneOffset);
								$iDateStampTo += 60 * 60 * 24;
							}
						}
						else
						{
							if (0 < strlen($sDate))
							{
								$iDateStampFrom = $this->parseSearchDate($sDate, $iTimeZoneOffset);
								$iDateStampTo = $iDateStampFrom + 60 * 60 * 24;
							}
						}

						if (0 < $iDateStampFrom)
						{
							$oSearchBuilder->AddAnd('SINCE', gmdate('j-M-Y', $iDateStampFrom));
						}

						if (0 < $iDateStampTo)
						{
							$oSearchBuilder->AddAnd('BEFORE', gmdate('j-M-Y', $iDateStampTo));
						}
						break;
				}
			}

			if ('' !== trim($sMainText))
			{
				$sMainText = trim(trim(preg_replace('/[\s]+/', ' ', $sMainText)), '"\'');
				$oSearchBuilder->AddAnd('TEXT', $this->escapeSearchString($oImapClient, $sMainText));
			}
		}

		return $oSearchBuilder;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param int $iOffset = 0
	 * @param int $iLimit = 20
	 * @param string $sSearch = ''
	 * @param array $aExistenUids = array()
	 *
	 * @return CApiMailMessageCollection
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageList($oAccount, $sFolderFullNameRaw, $iOffset = 0, $iLimit = 20, $sSearch = '', $aExistenUids = array())
	{
		if (0 === strlen($sFolderFullNameRaw) || 0 > $iOffset || 0 >= $iLimit || 999 < $iLimit)
		{
			throw new CApiInvalidArgumentException();
		}

		$oMessageCollection = false;

		$oSettings =& CApi::GetSettings();
		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aList = $this->folderInformation($oImapClient, $sFolderFullNameRaw);

		$iMessageCount = $aList[0];
		$iMessageUnseenCount = $aList[1];
		$sUidNext = $aList[2];

		$oMessageCollection = CApiMailMessageCollection::NewInstance();

		$oMessageCollection->FolderName = $sFolderFullNameRaw;
		$oMessageCollection->Offset = $iOffset;
		$oMessageCollection->Limit = $iLimit;
		$oMessageCollection->Search = $sSearch;
		$oMessageCollection->UidNext = $sUidNext;

		if (0 < $iMessageCount)
		{
			$bIndexAsUid = false;
			$aIndexOrUids = array();

			$bUseSortIfSupported = !!$oSettings->GetConf('WebMail/UseSortImapForDateMode');
			if ($bUseSortIfSupported)
			{
				$bUseSortIfSupported = $oImapClient->IsSupported('SORT');
			}

			if (0 < strlen($sSearch))
			{
				$sSearchCriterias = $this->getSearchBuilder($oImapClient, $sSearch, $oAccount->GetDefaultTimeOffset() * 60)->Complete();

				$bIndexAsUid = true;
				$aIndexOrUids = null;

				if ($bUseSortIfSupported)
				{
					$aIndexOrUids = $oImapClient->MessageSimpleSort(array('ARRIVAL'), $sSearchCriterias, $bIndexAsUid);
//					$aIndexOrUids = $oImapClient->MessageSimpleSort(array('REVERSE', 'ARRIVAL'), $sSearchCriterias, $bIndexAsUid);
				}
				else
				{
					if (!\MailSo\Base\Utils::IsAscii($sSearch))
					{
						try
						{
							$aIndexOrUids = $oImapClient->MessageSimpleSearch($sSearchCriterias, $bIndexAsUid, 'UTF-8');
						}
						catch (\MailSo\Imap\Exceptions\NegativeResponseException $oException)
						{
							// Charset is not supported. Skip and try request without charset.
							$aIndexOrUids = null;
						}
					}

					if (null === $aIndexOrUids)
					{
						$aIndexOrUids = $oImapClient->MessageSimpleSearch($sSearchCriterias, $bIndexAsUid);
					}
				}
			}
			else
			{
				if ($bUseSortIfSupported)
				{
					$bIndexAsUid = true;
					$aIndexOrUids = $oImapClient->MessageSimpleSort(array('ARRIVAL'),
//					$aIndexOrUids = $oImapClient->MessageSimpleSort(array('REVERSE', 'ARRIVAL'),
						\MailSo\Imap\SearchBuilder::NewInstance()->Complete(), $bIndexAsUid);
				}
				else
				{
					$bIndexAsUid = false;
					$aIndexOrUids = array(1);
					if (1 < $iMessageCount)
					{
						$aIndexOrUids = array_reverse(range(1, $iMessageCount));
					}
				}
			}

			if (0 < count($aIndexOrUids))
			{
				$oMessageCollection->MessageCount = $iMessageCount;
				$oMessageCollection->MessageUnseenCount = $iMessageUnseenCount;
				$oMessageCollection->MessageSearchCount = 0 < strlen($sSearch)
					? count($aIndexOrUids) : $oMessageCollection->MessageCount;

				$iOffset = (0 > $iOffset) ? 0 : $iOffset;
				$aRequestIndexOrUids = array_slice($aIndexOrUids, $iOffset, $iLimit);

				if ($bIndexAsUid)
				{
					$oMessageCollection->Uids = $aRequestIndexOrUids;

					if (0 < count($aExistenUids))
					{
						$aNew = array();
						foreach ($aRequestIndexOrUids as $mItemUid)
						{
							if (!in_array($mItemUid, $aExistenUids))
							{
								$aNew[] = $mItemUid;
							}
						}

						$aRequestIndexOrUids = $aNew;
					}
				}

				if (is_array($aRequestIndexOrUids) && 0 < count($aRequestIndexOrUids))
				{
					$aFetchResponse = $oImapClient->Fetch(array(
						\MailSo\Imap\Enumerations\FetchType::INDEX,
						\MailSo\Imap\Enumerations\FetchType::UID,
						\MailSo\Imap\Enumerations\FetchType::RFC822_SIZE,
						\MailSo\Imap\Enumerations\FetchType::INTERNALDATE,
						\MailSo\Imap\Enumerations\FetchType::FLAGS,
						\MailSo\Imap\Enumerations\FetchType::BODYSTRUCTURE,
						\MailSo\Imap\Enumerations\FetchType::BODY_HEADER_PEEK
					), implode(',', $aRequestIndexOrUids), $bIndexAsUid);

					if (is_array($aFetchResponse) && 0 < count($aFetchResponse))
					{
						$aFetchIndexArray = array();
						$oFetchResponseItem = null;
						foreach ($aFetchResponse as /* @var $oFetchResponseItem \MailSo\Imap\FetchResponse */ &$oFetchResponseItem)
						{
							$aFetchIndexArray[($bIndexAsUid)
								? $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::UID)
								: $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::INDEX)] =& $oFetchResponseItem;

							unset($oFetchResponseItem);
						}

						foreach ($aRequestIndexOrUids as $iFUid)
						{
							if (isset($aFetchIndexArray[$iFUid]))
							{
								$oMailMessage = CApiMailMessage::NewFetchResponseInstance(
									$oMessageCollection->FolderName, $aFetchIndexArray[$iFUid]);

								if (!$bIndexAsUid)
								{
									$oMessageCollection->Uids[] = $oMailMessage->Uid();
								}

								if (!in_array($oMailMessage->Uid(), $aExistenUids))
								{
									$oMessageCollection->Add($oMailMessage);
								}

								unset($oMailMessage);
							}
						}
					}
				}
			}
		}

		$oMessageCollection->FolderHash = api_Utils::GenerateFolderHash($sFolderFullNameRaw,
			$oMessageCollection->MessageCount,
			$oMessageCollection->MessageUnseenCount,
			$oMessageCollection->UidNext);

		return $oMessageCollection;
	}
}
