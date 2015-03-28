<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	 * @param int $iForceConnectTimeOut = 0
	 * @param int $iForceSocketTimeOut = 0
	 *
	 * @return \MailSo\Imap\ImapClient|null
	 */
	protected function &getImapClient(CAccount $oAccount, $iForceConnectTimeOut = 0, $iForceSocketTimeOut = 0)
	{
		$oResult = null;
		if ($oAccount)
		{
			$sCacheKey = $oAccount->Email;
			if (!isset($this->aImapClientCache[$sCacheKey]))
			{
				$iConnectTimeOut = CApi::GetConf('socket.connect-timeout', 10);
				$iSocketTimeOut = CApi::GetConf('socket.get-timeout', 20);
				$bVerifySsl = !!CApi::GetConf('socket.verify-ssl', false);

				if (0 < $iForceConnectTimeOut)
				{
					$iConnectTimeOut = $iForceConnectTimeOut;
				}
				
				if (0 < $iForceSocketTimeOut)
				{
					$iSocketTimeOut = $iForceSocketTimeOut;
				}

				CApi::Plugin()->RunHook('webmail-imap-update-socket-timeouts',
					array(&$iConnectTimeOut, &$iSocketTimeOut));

				$this->aImapClientCache[$sCacheKey] = \MailSo\Imap\ImapClient::NewInstance();
				$this->aImapClientCache[$sCacheKey]->SetTimeOuts($iConnectTimeOut, $iSocketTimeOut); // TODO
				$this->aImapClientCache[$sCacheKey]->SetLogger(\CApi::MailSoLogger());
			}

			$oResult =& $this->aImapClientCache[$sCacheKey];
			if (!$oResult->IsConnected())
			{
				$oResult->Connect($oAccount->IncomingMailServer, $oAccount->IncomingMailPort,
					$oAccount->IncomingMailUseSSL
						? \MailSo\Net\Enumerations\ConnectionSecurityType::SSL
						: \MailSo\Net\Enumerations\ConnectionSecurityType::NONE, $bVerifySsl);
			}

			if (!$oResult->IsLoggined())
			{
				$sProxyAuthUser = !empty($oAccount->CustomFields['ProxyAuthUser'])
					? $oAccount->CustomFields['ProxyAuthUser'] : '';

				$oResult->Login($oAccount->IncomingMailLogin, $oAccount->IncomingMailPassword, $sProxyAuthUser);
			}
		}

		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return \MailSo\Imap\ImapClient|null
	 * @throws CApiManagerException
	 */
	public function &ImapClient(CAccount $oAccount, $iForceConnectTimeOut = 0, $iForceSocketTimeOut = 0)
	{
		$oImap = false;
		try
		{
			$oImap =& $this->getImapClient($oAccount, $iForceConnectTimeOut, $iForceSocketTimeOut);
		}
		catch (\Exception $oException)
		{
		}

		return $oImap;
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
			throw new CApiManagerException(Errs::Mail_AccountConnectToMailServerFailed, $oException);
		}
		catch (\MailSo\Imap\Exceptions\LoginBadCredentialsException $oException)
		{
			throw new CApiManagerException(Errs::Mail_AccountAuthentication, $oException);
		}
		catch (\Exception $oException)
		{
			throw new CApiManagerException(Errs::Mail_AccountLoginFailed, $oException);
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

			if (!$oAccount->IsEnabledExtension(CAccount::SpamFolderExtension) && isset($aFoldersMap[EFolderType::Spam]))
			{
				unset($aFoldersMap[EFolderType::Spam]);
			}

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
	 * @param bool $bCreateUnExistenSystemFolders = true
	 *
	 * @return CApiMailFolderCollection
	 */
	public function Folders($oAccount, $bCreateUnExistenSystemFolders = true)
	{
		$oFolderCollection = false;

		$sParent = '';
		$sListPattern = '*';

		$oImapClient =& $this->getImapClient($oAccount);

		$oNamespace = $oImapClient->GetNamespace();

		$aFolders = $oImapClient->FolderList($sParent, $sListPattern);
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

			if ($this->initSystemFolders($oAccount, $oFolderCollection, $bCreateUnExistenSystemFolders) && $bCreateUnExistenSystemFolders)
			{
				$oFolderCollection = $this->Folders($oAccount, false);
			}
		}

		if ($oFolderCollection && $oNamespace)
		{
			$oFolderCollection->SetNamespace($oNamespace->GetPersonalNamespace());
		}

		$aFoldersOrderList = null;
		if (!$oAccount->IsEnabledExtension(CAccount::DisableFoldersManualSort))
		{
			$aFoldersOrderList = $this->FoldersOrder($oAccount);
			$aFoldersOrderList = is_array($aFoldersOrderList) && 0 < count($aFoldersOrderList) ? $aFoldersOrderList : null;
		}

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

		if (null === $aFoldersOrderList &&
			!$oAccount->IsEnabledExtension(CAccount::DisableFoldersManualSort))
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

		// gmail hack
		$oFolder = $oImapClient->FolderCurrentInformation();
		if ($oFolder && null !== $oFolder->Exists && $oFolder->FolderName === $sFolderFullNameRaw)
		{
			$iSubCount = (int) $oFolder->Exists;
			if (0 < $iSubCount && $iSubCount < $iStatusMessageCount)
			{
				$iStatusMessageCount = $iSubCount;
			}
		}

		return array($iStatusMessageCount, $iStatusMessageUnseenCount, $sStatusUidNext,
			api_Utils::GenerateFolderHash($sFolderFullNameRaw, $iStatusMessageCount, $iStatusMessageUnseenCount, $sStatusUidNext));
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sExtentionName
	 *
	 * @return bool
	 */
	public function IsSupported($oAccount, $sExtentionName)
	{
		if (0 === strlen($sExtentionName))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);
		return $oImapClient->IsSupported($sExtentionName);
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
	 * @param string $sFolderFullNameRaw
	 * @param string $sUidnext
	 *
	 * @return array
	 */
	public function GetNextMessages($oAccount, $sFolderFullNameRaw, $sUidnext)
	{
		if (0 === strlen($sFolderFullNameRaw) || 0 === strlen($sFolderFullNameRaw))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aResult = array();
		$aFetchResponse = $oImapClient->Fetch(array(
				\MailSo\Imap\Enumerations\FetchType::INDEX,
				\MailSo\Imap\Enumerations\FetchType::UID,
				\MailSo\Imap\Enumerations\FetchType::FLAGS,
				\MailSo\Imap\Enumerations\FetchType::BuildBodyCustomHeaderRequest(array(
					\MailSo\Mime\Enumerations\Header::FROM_,
					\MailSo\Mime\Enumerations\Header::SUBJECT,
					\MailSo\Mime\Enumerations\Header::CONTENT_TYPE
				))
			), $sUidnext.':*', true);

		if (\is_array($aFetchResponse) && 0 < \count($aFetchResponse))
		{
			foreach ($aFetchResponse as /* @var $oFetchResponse \MailSo\Imap\FetchResponse */ $oFetchResponse)
			{
				$aFlags = \array_map('strtolower', $oFetchResponse->GetFetchValue(
					\MailSo\Imap\Enumerations\FetchType::FLAGS));

				if (!\in_array(\strtolower(\MailSo\Imap\Enumerations\MessageFlag::SEEN), $aFlags))
				{
					$sUid = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::UID);
					$sHeaders = $oFetchResponse->GetHeaderFieldsValue();

					$oHeaders = \MailSo\Mime\HeaderCollection::NewInstance()->Parse($sHeaders);

					$sContentTypeCharset = $oHeaders->ParameterValue(
						\MailSo\Mime\Enumerations\Header::CONTENT_TYPE,
						\MailSo\Mime\Enumerations\Parameter::CHARSET
					);

					$sCharset = '';
					if (0 < \strlen($sContentTypeCharset))
					{
						$sCharset = $sContentTypeCharset;
					}

					if (0 < \strlen($sCharset))
					{
						$oHeaders->SetParentCharset($sCharset);
					}

					$aResult[] = array(
						'Folder' => $sFolderFullNameRaw,
						'Uid' => $sUid,
						'Subject' => $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::SUBJECT, 0 === \strlen($sCharset)),
						'From' => $oHeaders->GetAsEmailCollection(\MailSo\Mime\Enumerations\Header::FROM_, 0 === \strlen($sCharset))
					);
				}
			}
		}

		return $aResult;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param array $aFolderFullNamesRaw
	 * @param array $aNewInboxData
	 * @param string $sInboxUidnext = ''
	 *
	 * @return array
	 */
	public function FolderCountsFromArray($oAccount, $aFolderFullNamesRaw, &$aNewInboxData, $sInboxUidnext = '')
	{
		if (!is_array($aFolderFullNamesRaw) || 0 === count($aFolderFullNamesRaw))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$aResult = array();
		if (2 < count($aFolderFullNamesRaw) && $oImapClient->IsSupported('LIST-STATUS'))
		{
			$aFolders = $oImapClient->FolderStatusList();

			if (is_array($aFolders))
			{
				foreach ($aFolders as /* @var $oImapFolder \MailSo\Imap\Folder */ $oImapFolder)
				{
					$oFolder = CApiMailFolder::NewInstance($oImapFolder, true);
					if ($oFolder)
					{
						$mStatus = $oFolder->Status();
						if (is_array($mStatus) && isset($mStatus['MESSAGES'], $mStatus['UNSEEN'], $mStatus['UIDNEXT']))
						{
							$aResult[$oFolder->FullNameRaw()] = array(
								(int) $mStatus['MESSAGES'],
								(int) $mStatus['UNSEEN'],
								(string) $mStatus['UIDNEXT'],
								\api_Utils::GenerateFolderHash(
									$oFolder->FullNameRaw(), $mStatus['MESSAGES'], $mStatus['UNSEEN'], $mStatus['UIDNEXT'])
							);
						}
					}

					unset($oFolder);
				}
			}
		}
		else
		{
			foreach ($aFolderFullNamesRaw as $sFolderFullNameRaw)
			{
				$sFolderFullNameRaw = (string) $sFolderFullNameRaw;

				try
				{
					$aResult[$sFolderFullNameRaw] = $this->folderInformation($oImapClient, $sFolderFullNameRaw);
				}
				catch (\Exception $oException) {}
			}
		}

		if (0 < strlen($sInboxUidnext) && isset($aResult['INBOX'], $aResult['INBOX'][2]) && $aResult['INBOX'][2] !== $sInboxUidnext)
		{
			$aNewInboxData = $this->GetNextMessages($oAccount, 'INBOX', $sInboxUidnext);
		}

		return $aResult;
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
	 * @return string
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

		return $sNewFolderFullNameRaw;
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

		if ($oImapClient->IsSupported('MOVE'))
		{
			$oImapClient->MessageMove($sToFolderFullNameRaw, implode(',', $aUids), true);
		}
		else
		{
			$oImapClient->MessageCopy($sToFolderFullNameRaw, implode(',', $aUids), true);
			$this->MessageDelete($oAccount, $sFromFolderFullNameRaw, $aUids);
		}
	}

	public function MessageCopy($oAccount, $sFromFolderFullNameRaw, $sToFolderFullNameRaw, $aUids)
	{
		if (0 === strlen($sFromFolderFullNameRaw) || 0 === strlen($sToFolderFullNameRaw) ||
			!is_array($aUids) || 0 === count($aUids))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderSelect($sFromFolderFullNameRaw);

		$oImapClient->MessageCopy($sToFolderFullNameRaw, implode(',', $aUids), true);
	}

	/**
	 * @param CAccount $oAccount
	 * @param \MailSo\Mime\Message $oMessage
	 * @param CFetcher $oFetcher = null
	 * @param string $sSentFolder = ''
	 * @param string $sDraftFolder = ''
	 * @param string $sDraftUid = ''
	 *
	 * @return array|bool
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageSend($oAccount, $oMessage, $oFetcher = null, $sSentFolder = '', $sDraftFolder = '', $sDraftUid = '')
	{
		if (!$oAccount || !$oMessage)
		{
			throw new CApiInvalidArgumentException();
		}
		
		$oImapClient =& $this->getImapClient($oAccount);

		$rMessageStream = \MailSo\Base\ResourceRegistry::CreateMemoryResource();
		
		$iMessageStreamSize = \MailSo\Base\Utils::MultipleStreamWriter(
			$oMessage->ToStream(true), array($rMessageStream), 8192, true, true, true);

		$mResult = false;
		if (false !== $iMessageStreamSize && is_resource($rMessageStream))
		{
			$oRcpt = $oMessage->GetRcpt();
			if ($oRcpt && 0 < $oRcpt->Count())
			{
				$sRcptEmail = '';
				try
				{
					$iConnectTimeOut = CApi::GetConf('socket.connect-timeout', 5);
					$iSocketTimeOut = CApi::GetConf('socket.get-timeout', 5);
					$bVerifySsl = !!CApi::GetConf('socket.verify-ssl', false);

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
					if ($oFetcher)
					{
						$iSecure = $oFetcher->OutgoingMailSecurity;
					}
					else if ($oAccount->OutgoingMailUseSSL)
					{
						$iSecure = \MailSo\Net\Enumerations\ConnectionSecurityType::SSL;
					}

					$sOutgoingMailLogin = '';
					if ($oFetcher)
					{
						$sOutgoingMailLogin = $oFetcher->IncomingMailLogin;
					}

					if (0 === strlen($sOutgoingMailLogin))
					{
						$sOutgoingMailLogin = $oAccount->OutgoingMailLogin;
						$sOutgoingMailLogin = 0 < strlen($sOutgoingMailLogin) ? $sOutgoingMailLogin : $oAccount->IncomingMailLogin;
					}

					$sOutgoingMailPassword = '';
					if ($oFetcher)
					{
						$sOutgoingMailPassword = $oFetcher->IncomingMailPassword;
					}

					if (0 === strlen($sOutgoingMailPassword))
					{
						$sOutgoingMailPassword = $oAccount->OutgoingMailPassword;
						$sOutgoingMailPassword = 0 < strlen($sOutgoingMailPassword) ? $sOutgoingMailPassword : $oAccount->IncomingMailPassword;
					}

					$sEhlo = \MailSo\Smtp\SmtpClient::EhloHelper();
					CApi::Plugin()->RunHook('api-smtp-send-ehlo', array($oAccount, &$sEhlo));

					if ($oFetcher)
					{
						$oSmtpClient->Connect($oFetcher->OutgoingMailServer, $oFetcher->OutgoingMailPort, $sEhlo, $iSecure, $bVerifySsl);
					}
					else
					{
						$oSmtpClient->Connect($oAccount->OutgoingMailServer, $oAccount->OutgoingMailPort, $sEhlo, $iSecure, $bVerifySsl);
					}
					
					if (($oFetcher && $oFetcher->OutgoingMailAuth) || (!$oFetcher && $oAccount->OutgoingMailAuth))
					{
						$oSmtpClient->Login($sOutgoingMailLogin, $sOutgoingMailPassword);
					}

					$oSmtpClient->MailFrom($oFetcher ? $oFetcher->Email : $oAccount->Email, (string) $iMessageStreamSize);

					$aRcpt =& $oRcpt->GetAsArray();
					CApi::Plugin()->RunHook('api-smtp-send-rcpt', array($oAccount, &$aRcpt));

					foreach ($aRcpt as /* @var $oEmail \MailSo\Mime\Email */ $oEmail)
					{
						$sRcptEmail = $oEmail->GetEmail();
						$oSmtpClient->Rcpt($sRcptEmail);
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
				catch (\MailSo\Smtp\Exceptions\MailboxUnavailableException $oException)
				{
					throw new \CApiManagerException(Errs::Mail_MailboxUnavailable, 
							$oException, array(), array('Mailbox' => $sRcptEmail));
				}

				if (0 < strlen($sSentFolder))
				{
					try
					{
						if (!$oMessage->GetBcc())
						{
							if (is_resource($rMessageStream))
							{
								rewind($rMessageStream);
							}

							$oImapClient->MessageAppendStream(
								$sSentFolder, $rMessageStream, $iMessageStreamSize, array(
									\MailSo\Imap\Enumerations\MessageFlag::SEEN
								));
						}
						else
						{
							$rAppendMessageStream = \MailSo\Base\ResourceRegistry::CreateMemoryResource();

							$iAppendMessageStreamSize = \MailSo\Base\Utils::MultipleStreamWriter(
								$oMessage->ToStream(), array($rAppendMessageStream), 8192, true, true, true);

							$oImapClient->MessageAppendStream(
								$sSentFolder, $rAppendMessageStream, $iAppendMessageStreamSize, array(
									\MailSo\Imap\Enumerations\MessageFlag::SEEN
								));

							if (is_resource($rAppendMessageStream))
							{
								@fclose($rAppendMessageStream);
							}
						}
					}
					catch (\Exception $oException)
					{
						throw new \CApiManagerException(Errs::Mail_CannotSaveMessageInSentItems, $oException);
					}

					if (is_resource($rMessageStream))
					{
						@fclose($rMessageStream);
					}
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
	 * @param string $sDraftUid = ''
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
			$oMessage->ToStream(), array($rMessageStream), 8192, true, true);

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
	 * @param string $sMessageFileName
	 * @param string $sFolderToAppend
	 *
	 * @return void
	 */
	public function MessageAppendFile($oAccount, $sMessageFileName, $sFolderToAppend)
	{
		$oImapClient =& $this->getImapClient($oAccount);
		$oImapClient->MessageAppendFile($sMessageFileName, $sFolderToAppend);
	}	

	/**
	 * @param CAccount $oAccount
	 * @param string $rMessage
	 * @param resource $sFolder
	 * @param int $iStreamSize
	 *
	 * @return void
	 */
	public function MessageAppendStream($oAccount, $rMessage, $sFolder, $iStreamSize)
	{
		$oImapClient =& $this->getImapClient($oAccount);
		$oImapClient->MessageAppendStream($sFolder, $rMessage, $iStreamSize);
	}	

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param array $aUids
	 * @param string $sFlagString
	 * @param int $iAction = EMailMessageStoreAction::Add
	 * @param bool $bSetToAll = false
	 * @param bool $bSkipNonPermanentsFlags = false
	 *
	 * @return true
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageFlag($oAccount, $sFolderFullNameRaw, $aUids, $sFlagString,
		$iAction = EMailMessageStoreAction::Add, $bSetToAll = false, $bSkipNonPermanentsFlags = false)
	{
		if (0 === strlen($sFolderFullNameRaw) || (!$bSetToAll && (!is_array($aUids) || 0 === count($aUids))))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderSelect($sFolderFullNameRaw);

		$aUids = is_array($aUids) ? $aUids : array();
		$sUids = implode(',', $aUids);

		$oInfo = $oImapClient->FolderCurrentInformation();
		if ($bSetToAll)
		{
			$sUids = '1:*';
			if ($oInfo && 0 === $oInfo->Exists)
			{
				return true;
			}
		}

		$aFlagsOut = array();
		$aFlags = explode(' ', $sFlagString);
		if ($bSkipNonPermanentsFlags && $oInfo)
		{
			if (!\in_array('\\*', $oInfo->PermanentFlags))
			{
				foreach ($aFlags as $sFlag)
				{
					$sFlag = \trim($sFlag);
					if (\in_array($sFlag, $oInfo->PermanentFlags))
					{
						$aFlagsOut[] = $sFlag;
					}
				}
			}
			else
			{
				$aFlagsOut = $aFlags;
			}

			if (0 === \count($aFlagsOut))
			{
				return true;
			}
		}
		else
		{
			$aFlagsOut = $aFlags;
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

		$oImapClient->MessageStoreFlag($sUids, $bSetToAll ? false : true, $aFlags, $sResultAction);
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

		$aUids = $oImapClient->MessageSimpleSearch('HEADER Message-ID '.$sMessageId, true);

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
	 * @param bool $bParseICalAndVcard = false
	 * @param bool $bParseAsc = false
	 * @param int $iBodyTextLimit = 0
	 *
	 * @return CApiMailMessage
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function Message($oAccount, $sFolderFullNameRaw, $iUid, $sRfc822SubMimeIndex = '', $bParseICalAndVcard = false, $bParseAsc = false, $iBodyTextLimit = 0)
	{
		if (0 === strlen($sFolderFullNameRaw) || !is_numeric($iUid) || 0 >= (int) $iUid)
		{
			throw new CApiInvalidArgumentException();
		}

		$iUid = (int) $iUid;

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$oMessage = false;

		$sICalMimeIndex = '';
		$sVCardMimeIndex = '';
		$aTextMimeIndexes = array();
		$aAscPartsIds = array();

		$aFetchResponse = $oImapClient->Fetch(array(
			\MailSo\Imap\Enumerations\FetchType::BODYSTRUCTURE), $iUid, true);

		$oBodyStructure = (0 < count($aFetchResponse)) ? $aFetchResponse[0]->GetFetchBodyStructure($sRfc822SubMimeIndex) : null;
		
		if ($oBodyStructure)
		{
			$aTextParts = $oBodyStructure->SearchHtmlOrPlainParts();
			if (is_array($aTextParts) && 0 < count($aTextParts))
			{
				foreach ($aTextParts as $oPart)
				{
					$aTextMimeIndexes[] = array($oPart->PartID(), $oPart->Size());
				}
			}

			if ($bParseICalAndVcard)
			{
				$aICalPart = $oBodyStructure->SearchByContentType('text/calendar');
				$oICalPart = is_array($aICalPart) && 0 < count($aICalPart) ? $aICalPart[0] : null;
				$sICalMimeIndex = $oICalPart ? $oICalPart->PartID() : '';

				$aVCardPart = $oBodyStructure->SearchByContentType('text/vcard');
				$aVCardPart = $aVCardPart ? $aVCardPart : $oBodyStructure->SearchByContentType('text/x-vcard');
				$oVCardPart = is_array($aVCardPart) && 0 < count($aVCardPart) ? $aVCardPart[0] : null;
				$sVCardMimeIndex = $oVCardPart ? $oVCardPart->PartID() : '';
			}

			if ($bParseAsc)
			{
				$aAscParts = $oBodyStructure->SearchByCallback(function (/* @var $oPart \MailSo\Imap\BodyStructure */ $oPart) {
					return '.asc' === \strtolower(\substr(\trim($oPart->FileName()), -4));
				});

				if (is_array($aAscParts) && 0 < count($aAscParts))
				{
					foreach ($aAscParts as $oPart)
					{
						$aAscPartsIds[] = $oPart->PartID();
					}
				}
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

		if (0 < count($aTextMimeIndexes))
		{
			if (0 < strlen($sRfc822SubMimeIndex) && is_numeric($sRfc822SubMimeIndex))
			{
				$sLine = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$aTextMimeIndexes[0][0].'.1]';
				if (\is_numeric($iBodyTextLimit) && 0 < $iBodyTextLimit && $iBodyTextLimit < $aTextMimeIndexes[0][1])
				{
					$sLine .= '<0.'.((int) $iBodyTextLimit).'>';
				}

				$aFetchItems[] = $sLine;
			}
			else
			{
				foreach ($aTextMimeIndexes as $aTextMimeIndex)
				{
					$sLine = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$aTextMimeIndex[0].']';
					if (\is_numeric($iBodyTextLimit) && 0 < $iBodyTextLimit && $iBodyTextLimit < $aTextMimeIndex[1])
					{
						$sLine .= '<0.'.((int) $iBodyTextLimit).'>';
					}
					
					$aFetchItems[] = $sLine;
				}
			}
		}
		
		if (0 < strlen($sICalMimeIndex))
		{
			$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sICalMimeIndex.']';
		}
		
		if (0 < strlen($sVCardMimeIndex))
		{
			$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sVCardMimeIndex.']';
		}

		if (0 < count($aAscPartsIds))
		{
			foreach ($aAscPartsIds as $sPartID)
			{
				$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODY_PEEK.'['.$sPartID.']';
			}
		}

		if (!$oBodyStructure)
		{
			$aFetchItems[] = \MailSo\Imap\Enumerations\FetchType::BODYSTRUCTURE;
		}

		$aFetchResponse = $oImapClient->Fetch($aFetchItems, $iUid, true);
		if (0 < count($aFetchResponse))
		{
			$oMessage = CApiMailMessage::NewFetchResponseInstance($sFolderFullNameRaw, $aFetchResponse[0], $oBodyStructure, $sRfc822SubMimeIndex, $aAscPartsIds);
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

			if ($bParseAsc && 0 < count($aAscPartsIds))
			{
				
			}
			
			if ($bParseICalAndVcard)
			{
				$oApiCapa = /* @var CApiCapabilityManager */ CApi::Manager('capability');
				$oApiFileCache = /* @var CApiFilecacheManager */ CApi::Manager('filecache');
				
				// ICAL
				$sICal = $oMessage->GetExtend('ICAL_RAW');
				if (!empty($sICal) && $oApiCapa->IsCalendarSupported($oAccount))
				{
					$oApiCalendarManager = CApi::Manager('calendar');
					if ($oApiCalendarManager)
					{
						$mResult = $oApiCalendarManager->ProcessICS($oAccount, trim($sICal), $sFromEmail);
						if (is_array($mResult) && !empty($mResult['Action']) && !empty($mResult['Body']))
						{
							$sTemptFile = md5($mResult['Body']).'.ics';
							if ($oApiFileCache && $oApiFileCache->Put($oAccount, $sTemptFile, $mResult['Body']))
							{
								$oIcs = CApiMailIcs::NewInstance();

								$oIcs->Uid = $mResult['UID'];
								$oIcs->File = $sTemptFile;
								$oIcs->Attendee = isset($mResult['Attendee']) ? $mResult['Attendee'] : null;
								$oIcs->Type = $mResult['Action'];
								$oIcs->Location = !empty($mResult['Location']) ? $mResult['Location'] : '';
								$oIcs->Description = !empty($mResult['Description']) ? $mResult['Description'] : '';
								$oIcs->When = !empty($mResult['When']) ? $mResult['When'] : '';
								$oIcs->CalendarId = !empty($mResult['CalendarId']) ? $mResult['CalendarId'] : '';

								if (!$oApiCapa->IsCalendarAppointmentsSupported($oAccount))
								{
									$oIcs->Type = 'SAVE';
								}

								// TODO
//								$oIcs->Calendars = array();
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
				if (!empty($sVCard) && $oApiCapa->IsContactsSupported($oAccount))
				{
					$oApiContactsManager = CApi::Manager('contacts');
					$oContact = new CContact();
					$oContact->InitFromVCardStr($oAccount->IdUser, $sVCard);
					$oContact->InitBeforeChange();

					$oContact->IdContact = 0;

					$bContactExists = false;
					if (0 < strlen($oContact->ViewEmail))
					{
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
					$sSubject = trim($oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::SUBJECT));
					$sFileName = (empty($sSubject) ? 'message-'.$iUid : trim($sSubject)).'.eml';
					$sFileName = '.eml' === $sFileName ? 'message.eml' : $sFileName;
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
	 * @param bool $bDetectGmail = true
	 *
	 * @return string
	 */
	private function escapeSearchString($oImapClient, $sSearch, $bDetectGmail = true)
	{
		return ($bDetectGmail && 'ssl://imap.gmail.com' === strtolower($oImapClient->GetConnectedHost())) // gmail
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
	 * @return string
	 */
	private function getImapSearchString($oImapClient, $sSearch, $iTimeZoneOffset = 0, $aFilters = array())
	{
		$aImapSearchResult = array();
		$sSearch = trim($sSearch);

		if (0 === strlen($sSearch) && 0 === count($aFilters))
		{
			return 'ALL';
		}
		
		$bFilterFlagged = false;
		$bFilterUnseen = false;

		$bIsGmail = $oImapClient->IsSupported('X-GM-EXT-1');
		$sGmailRawSearch = '';

		if (0 < strlen($sSearch))
		{
			$aLines = $this->parseSearchString($sSearch);

			if (1 === count($aLines) && isset($aLines['OTHER']))
			{
				if (true) // TODO
				{
					$sValue = $this->escapeSearchString($oImapClient, $aLines['OTHER']);

					$aImapSearchResult[] = 'OR OR OR';
					$aImapSearchResult[] = 'FROM';
					$aImapSearchResult[] = $sValue;
					$aImapSearchResult[] = 'TO';
					$aImapSearchResult[] = $sValue;
					$aImapSearchResult[] = 'CC';
					$aImapSearchResult[] = $sValue;
					$aImapSearchResult[] = 'SUBJECT';
					$aImapSearchResult[] = $sValue;
				}
				else
				{
					if ($bIsGmail)
					{
						$sGmailRawSearch .= ' '.$aLines['OTHER'];
					}
					else
					{
						$aImapSearchResult[] = 'TEXT';
						$aImapSearchResult[] = $this->escapeSearchString($oImapClient, $aLines['OTHER']);
					}
				}
			}
			else
			{
				if (isset($aLines['EMAIL']))
				{
					$aEmails = explode(',', $aLines['EMAIL']);
					foreach ($aEmails as $sEmail)
					{
						$sEmail = trim($sEmail);
						if (0 < strlen($sEmail))
						{
							$sValue = $this->escapeSearchString($oImapClient, $sEmail);

							$aImapSearchResult[] = 'OR OR OR';
							$aImapSearchResult[] = 'FROM';
							$aImapSearchResult[] = $sValue;
							$aImapSearchResult[] = 'TO';
							$aImapSearchResult[] = $sValue;
							$aImapSearchResult[] = 'CC';
							$aImapSearchResult[] = $sValue;
							$aImapSearchResult[] = 'BCC';
							$aImapSearchResult[] = $sValue;
						}
					}
					
					unset($aLines['EMAIL']);
				}

				if (isset($aLines['TO']))
				{
					$sValue = $this->escapeSearchString($oImapClient, $aLines['TO']);

					$aImapSearchResult[] = 'OR';
					$aImapSearchResult[] = 'TO';
					$aImapSearchResult[] = $sValue;
					$aImapSearchResult[] = 'CC';
					$aImapSearchResult[] = $sValue;
					
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
							$aImapSearchResult[] = 'FROM';
							$aImapSearchResult[] = $sValue;
							break;
						case 'SUBJECT':
							$aImapSearchResult[] = 'SUBJECT';
							$aImapSearchResult[] = $sValue;
							break;
						case 'OTHER':
						case 'BODY':
						case 'TEXT':
							if ($bIsGmail)
							{
								$sGmailRawSearch .= ' '.$sRawValue;
							}
							else
							{
								$sMainText .= ' '.$sRawValue;
							}
							break;
						case 'HAS':
							if (false !== strpos($sRawValue, 'attach'))
							{
								if ($bIsGmail)
								{
									$sGmailRawSearch .= ' has:attachment';
								}
								else
								{
									$aImapSearchResult[] = 'OR OR OR';
									$aImapSearchResult[] = 'HEADER Content-Type application/';
									$aImapSearchResult[] = 'HEADER Content-Type multipart/m';
									$aImapSearchResult[] = 'HEADER Content-Type multipart/signed';
									$aImapSearchResult[] = 'HEADER Content-Type multipart/report';
								}
							}
							if (false !== strpos($sRawValue, 'flag'))
							{
								$bFilterFlagged = true;
								$aImapSearchResult[] = 'FLAGGED';
							}
							if (false !== strpos($sRawValue, 'unseen'))
							{
								$bFilterUnseen = true;
								$aImapSearchResult[] = 'UNSEEN';
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
								$aImapSearchResult[] = 'SINCE';
								$aImapSearchResult[] = gmdate('j-M-Y', $iDateStampFrom);
							}

							if (0 < $iDateStampTo)
							{
								$aImapSearchResult[] = 'BEFORE';
								$aImapSearchResult[] = gmdate('j-M-Y', $iDateStampTo);
							}
							break;
					}
				}

				if ('' !== trim($sMainText))
				{
					$sMainText = trim(trim(preg_replace('/[\s]+/', ' ', $sMainText)), '"\'');
					if ($bIsGmail)
					{
						$sGmailRawSearch .= ' '.$sRawValue;
					}
					else
					{
						$aImapSearchResult[] = 'BODY';
						$aImapSearchResult[] = $this->escapeSearchString($oImapClient, $sMainText);
					}
				}
			}
		}

		if (0 < count($aFilters))
		{
			foreach ($aFilters as $sFilter)
			{
				if ('flagged' === $sFilter && !$bFilterFlagged)
				{
					$aImapSearchResult[] = 'FLAGGED';
				}
				else if ('unseen' === $sFilter && !$bFilterUnseen)
				{
					$aImapSearchResult[] = 'UNSEEN';
				}
			}
		}

		$sGmailRawSearch = \trim($sGmailRawSearch);
		if ($bIsGmail && 0 < \strlen($sGmailRawSearch))
		{
			$aImapSearchResult[] = 'X-GM-RAW';
			$aImapSearchResult[] = $this->escapeSearchString($oImapClient, $sGmailRawSearch, false);
		}

		$sImapSearchResult = \trim(\implode(' ', $aImapSearchResult));
		if ('' === $sImapSearchResult)
		{
			$sImapSearchResult = 'ALL';
		}

		return $sImapSearchResult;
	}

	/**
	 * @param array $aThreads
	 * @return array
	 */
	private function threadArrayReverseRec($aThreads)
	{
		$aThreads = array_reverse($aThreads);
		foreach ($aThreads as &$mItem)
		{
			if (is_array($mItem))
			{
				$mItem = $this->threadArrayReverseRec($mItem);
			}
		}
		return $aThreads;
	}

	/**
	 * @param array $aThreads
	 * @return array
	 */
	private function threadArrayMap($aThreads)
	{
		$aNew = array();
		foreach ($aThreads as $mItem)
		{
			if (!is_array($mItem))
			{
				$aNew[] = $mItem;
			}
			else
			{
				$mMap = $this->threadArrayMap($mItem);
				if (is_array($mMap) && 0 < count($mMap))
				{
					$aNew = array_merge($aNew, $mMap);
				}
			}
		}

		sort($aNew, SORT_NUMERIC);
		return $aNew;
	}

	public function __sortHelper($a, $b, $aSortUidsFlipped)
	{
		if ($a === $b)
		{
			return 0;
		}

		$iAIndex = $iBIndex = -1;
		if (isset($aSortUidsFlipped[$a]))
		{
			$iAIndex = $aSortUidsFlipped[$a];
		}

		if (isset($aSortUidsFlipped[$b]))
		{
			$iBIndex = $aSortUidsFlipped[$b];
		}

		if ($iAIndex === $iBIndex)
		{
			return 0;
		}

		return ($iAIndex < $iBIndex) ? -1 : 1;
	}

	/**
	 * @param array $aInput
	 * @param array $aSortUidsFlipped
	 */
	private function sortArrayByArray(&$aInput, $aSortUidsFlipped)
	{
		$self = $this;

		\usort($aInput, function ($a, $b) use ($self, $aSortUidsFlipped) {
			return $self->__sortHelper($a, $b, $aSortUidsFlipped);
		});
	}

	/**
	 * @param array $aThreads
	 * @param array $aSortUidsFlipped
	 */
	private function sortArrayKeyByArray(&$aThreads, $aSortUidsFlipped)
	{
		$self = $this;

		\uksort($aThreads, function ($a, $b) use ($self, $aSortUidsFlipped) {
			return $self->__sortHelper($a, $b, $aSortUidsFlipped);
		});
	}

	/**
	 * @param array $aThreads
	 * @param bool $bIncludeBottom = true
	 *
	 * @return array
	 */
	private function chunkThreadArray(&$aThreads, $bIncludeBottom = true)
	{
		$iLimit = 200;
		foreach ($aThreads as $iKey => $mData)
		{
			if (is_array($mData) && 1 < count($mData))
			{
				if ($iLimit < count($mData))
				{
					$aChunks = array_chunk($mData, $iLimit);
					foreach ($aChunks as $iIndex => $aPart)
					{
						if (0 === $iIndex)
						{
							$aThreads[$iKey] = $aPart;
						}
						else
						{
							$iFirst = array_shift($aPart);
							if (0 < count($aPart))
							{
								$aThreads[$iFirst] = $aPart;
							}
							else
							{
								$aThreads[$iFirst] = $iFirst;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param array $aThreads
	 * @param array $aSortUids
	 *
	 * @return array
	 */
	private function resortThreadArray($aThreads, $aSortUids)
	{
		$aSortUidsFlipped = array_flip($aSortUids);

		foreach ($aThreads as $iKey => $mData)
		{
			if (is_array($mData) && 1 < count($mData))
			{
				$this->sortArrayByArray($mData, $aSortUidsFlipped);
				$aThreads[$iKey] = $mData;
			}
		}

		$this->chunkThreadArray($aThreads);

		$this->sortArrayKeyByArray($aThreads, $aSortUidsFlipped);

		return $aThreads;
	}

	/**
	 * @param array $aThreads
	 *
	 * @return array
	 */
	private function compileThreadArray($aThreads)
	{
		$aThreads = $this->threadArrayReverseRec($aThreads);

		$aResult = array();
		foreach ($aThreads as $mItem)
		{
			if (is_array($mItem))
			{
				$aMap = $this->threadArrayMap($mItem);
				if (is_array($aMap))
				{
					if (1 < count($aMap))
					{
						$iMax = array_pop($aMap);
						rsort($aMap, SORT_NUMERIC);
						$aResult[(int) $iMax] = $aMap;
					}
					else if (0 < count($aMap))
					{
						$aResult[(int) $aMap[0]] = $aMap[0];
					}
				}
			}
			else
			{
				$aResult[(int) $mItem] = $mItem;
			}
		}

		krsort($aResult, SORT_NUMERIC);
		return $aResult;
	}

	/**
	 * @param Object $oImapClient
	 * @param string $sIndexRange
	 * @param function $fItemCallback
	 * @param bool $bRangeAsUids = false
	 *
	 * @return array
	 *
	 * @throws CApiInvalidArgumentException
	 */
	private function specialSubRequest($oImapClient, $sIndexRange, $fItemCallback, $bRangeAsUids = false)
	{
		$aResult = array();

		$aFetchResponse = $oImapClient->Fetch(array(
			\MailSo\Imap\Enumerations\FetchType::INDEX,
			\MailSo\Imap\Enumerations\FetchType::UID,
			\MailSo\Imap\Enumerations\FetchType::RFC822_SIZE,
			\MailSo\Imap\Enumerations\FetchType::INTERNALDATE,
			\MailSo\Imap\Enumerations\FetchType::FLAGS,
			\MailSo\Imap\Enumerations\FetchType::BODYSTRUCTURE
		), $sIndexRange, $bRangeAsUids);

		if (is_array($aFetchResponse) && 0 < count($aFetchResponse))
		{
			$oFetchResponseItem = null;
			foreach ($aFetchResponse as /* @var $oFetchResponseItem \MailSo\Imap\FetchResponse */ &$oFetchResponseItem)
			{
				$sUid = $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::UID);
				$sSize = $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::RFC822_SIZE);
				$sInternalDate = $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::INTERNALDATE);
				$aFlags = $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::FLAGS);
				$aFlagsLower = array_map('strtolower', $aFlags);

				$oBodyStructure = $oFetchResponseItem->GetFetchBodyStructure();

				if ($oBodyStructure)
				{
					if (call_user_func_array($fItemCallback, array(
						$oBodyStructure, $sSize, $sInternalDate, $aFlagsLower, $sUid
					)))
					{
						$aResult[] = $sUid;
					}
				}

				unset($oBodyStructure);
				unset($oFetchResponseItem);
			}
		}

		return $aResult;
	}

	/**
	 * @param Object $oImapClient
	 * @param function $fItemCallback
	 * @param string $sFolderFullNameRaw
	 *
	 * @return array
	 *
	 * @throws CApiInvalidArgumentException
	 */
	private function specialIndexSearch($oImapClient, $fItemCallback, $sFolderFullNameRaw)
	{
		if (0 === strlen($sFolderFullNameRaw) ||
			!is_callable($fItemCallback))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aResult = array();

		$aList = $this->folderInformation($oImapClient, $sFolderFullNameRaw);
		$iCount = $aList[0];
		
		if (0 < $iCount)
		{
			$iInc = 0;
			$iTopLimit = 100;

			$aIndexes = range(1, $iCount);
			$aRequestIndexes = array();
			
			foreach ($aIndexes as $iIndex)
			{
				$iInc++;
				$aRequestIndexes[] = $iIndex;

				if ($iInc > $iTopLimit)
				{
					$aSubResult = $this->specialSubRequest($oImapClient,
						implode(',', $aRequestIndexes), $fItemCallback, false);

					$aResult = array_merge($aResult, $aSubResult);

					$aRequestIndexes = array();
					$iInc = 0;
				}
			}

			if (0 < count($aRequestIndexes))
			{
				$aSubResult = $this->specialSubRequest($oImapClient,
					implode(',', $aRequestIndexes), $fItemCallback, false);

				$aResult = array_merge($aResult, $aSubResult);
			}

			rsort($aResult, SORT_NUMERIC);
		}

		return is_array($aResult) ? $aResult : array();
	}

	/**
	 * @param Object $oImapClient
	 * @param function $fItemCallback
	 * @param string $sFolderFullNameRaw
	 * @param array $aUids
	 *
	 * @return array
	 *
	 * @throws CApiInvalidArgumentException
	 */
	private function specialUidsSearch($oImapClient, $fItemCallback, $sFolderFullNameRaw, $aUids)
	{
		if (0 === strlen($sFolderFullNameRaw) || !is_callable($fItemCallback) || 
			!is_array($aUids) || 0 === count($aUids))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aResult = array();

		$iInc = 0;
		$iTopLimit = 100;

		$aRequestUids = array();
		foreach ($aUids as $iUid)
		{
			$iInc++;
			$aRequestUids[] = $iUid;

			if ($iInc > $iTopLimit)
			{
				$aSubResult = $this->specialSubRequest($oImapClient,
					implode(',', $aRequestUids), $fItemCallback, true);

				$aResult = array_merge($aResult, $aSubResult);

				$aRequestUids = array();
				$iInc = 0;
			}
		}

		if (0 < count($aRequestUids))
		{
			$aSubResult = $this->specialSubRequest($oImapClient,
				implode(',', $aRequestUids), $fItemCallback, true);

			$aResult = array_merge($aResult, $aSubResult);
		}

		rsort($aResult, SORT_NUMERIC);
		return $aResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param int $iOffset = 0
	 * @param int $iLimit = 20
	 * @param string $sSearch = ''
	 * @param bool $bUseThreads = false
	 * @param array $aFilters = array()
	 * @param string $sInboxUidnext = ''
	 *
	 * @return CApiMailMessageCollection
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageList($oAccount, $sFolderFullNameRaw, $iOffset = 0, $iLimit = 20,
		$sSearch = '', $bUseThreads = false, $aFilters = array(), $sInboxUidnext = '')
	{
		if (0 === strlen($sFolderFullNameRaw) || 0 > $iOffset || 0 >= $iLimit || 999 < $iLimit)
		{
			throw new CApiInvalidArgumentException();
		}

		$oMessageCollection = false;

		$oSettings =& CApi::GetSettings();
		$oImapClient =& $this->getImapClient($oAccount, 20, 60 * 2);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aList = $this->folderInformation($oImapClient, $sFolderFullNameRaw);

		$iMessageCount = $aList[0];
		$iRealMessageCount = $aList[0];
		$iMessageUnseenCount = $aList[1];
		$sUidNext = $aList[2];

		$oMessageCollection = CApiMailMessageCollection::NewInstance();

		$oMessageCollection->FolderName = $sFolderFullNameRaw;
		$oMessageCollection->Offset = $iOffset;
		$oMessageCollection->Limit = $iLimit;
		$oMessageCollection->Search = $sSearch;
		$oMessageCollection->UidNext = $sUidNext;
		$oMessageCollection->Filters = implode(',', $aFilters);

		$aThreads = array();
		$bUseThreadsIfSupported = !!$oSettings->GetConf('WebMail/UseThreadsIfSupported');
		if ($bUseThreadsIfSupported)
		{
			$bUseThreadsIfSupported = $bUseThreads;
		}

		$oMessageCollection->FolderHash = $aList[3];

		$bSearch = false;
		if (0 < $iRealMessageCount)
		{
			$bIndexAsUid = false;
			$aIndexOrUids = array();

			$bUseSortIfSupported = !!$oSettings->GetConf('WebMail/UseSortImapForDateMode');
			if ($bUseSortIfSupported)
			{
				$bUseSortIfSupported = $oImapClient->IsSupported('SORT');
			}
			
			if ($bUseThreadsIfSupported)
			{
				$bUseThreadsIfSupported = $oImapClient->IsSupported('THREAD=REFS') || $oImapClient->IsSupported('THREAD=REFERENCES') || $oImapClient->IsSupported('THREAD=ORDEREDSUBJECT');
			}

			if (0 < strlen($sSearch) || 0 < count($aFilters))
			{
				$sCutedSearch = $sSearch;

				$sCutedSearch = \preg_replace('/[\s]+/', ' ', $sCutedSearch);
				$sCutedSearch = \preg_replace('/attach[ ]?:[ ]?/i', 'attach:', $sCutedSearch);

				$bSearchAttachments = false;
				$fAttachmentSearchCallback = null;
				$aMatch = array();

				if ((CApi::GetConf('labs.use-body-structures-for-has-attachments-search', false) && \preg_match('/has[ ]?:[ ]?attachments/i', $sSearch)) ||
					\preg_match('/attach:([^\s]+)/i', $sSearch, $aMatch))
				{
					$bSearchAttachments = true;
					$sAttachmentName = isset($aMatch[1]) ? trim($aMatch[1]) : '';
					$sAttachmentRegs = !empty($sAttachmentName) && '*' !== $sAttachmentName ?
						'/[^>]*'.str_replace('\\*', '[^>]*', preg_quote(trim($sAttachmentName, '*'), '/')).'[^>]*/ui' : '';

					if (CApi::GetConf('labs.use-body-structures-for-has-attachments-search', false))
					{	
						$sCutedSearch = trim(preg_replace('/has[ ]?:[ ]?attachments/i', '', $sCutedSearch));
					}
					
					$sCutedSearch = trim(preg_replace('/attach:([^\s]+)/', '', $sCutedSearch));

					$fAttachmentSearchCallback = function ($oBodyStructure, $sSize, $sInternalDate, $aFlagsLower, $sUid) use ($sFolderFullNameRaw, $sAttachmentRegs) {

						$bResult = false;
						if ($oBodyStructure)
						{
							$aAttachmentsParts = $oBodyStructure->SearchAttachmentsParts();
							if ($aAttachmentsParts && 0 < count($aAttachmentsParts))
							{
								$oAttachments = CApiMailAttachmentCollection::NewInstance();
								foreach ($aAttachmentsParts as /* @var $oAttachmentItem \MailSo\Imap\BodyStructure */ $oAttachmentItem)
								{
									$oAttachments->Add(
										CApiMailAttachment::NewBodyStructureInstance($sFolderFullNameRaw, $sUid, $oAttachmentItem)
									);
								}

								$bResult = $oAttachments->HasNonInlineAttachments();
								if ($bResult && !empty($sAttachmentRegs))
								{
									$aList = $oAttachments->FilterList(function ($oAttachment) use ($sAttachmentRegs) {
										if ($oAttachment && !$oAttachment->IsInline() && !$oAttachment->Cid())
										{
											return !!preg_match($sAttachmentRegs, $oAttachment->FileName());
										}

										return false;
									});

									return is_array($aList) ? 0 < count($aList) : false;
								}
							}
						}

						unset($oBodyStructure);

						return $bResult;
					};
				}

				if (0 < strlen($sCutedSearch) || 0 < count($aFilters))
				{
					$bSearch = true;
					$sSearchCriterias = $this->getImapSearchString($oImapClient, $sCutedSearch,
						$oAccount->GetDefaultTimeOffset() * 60, $aFilters);

					$bIndexAsUid = true;
					$aIndexOrUids = null;

					if ($bUseSortIfSupported)
					{
						$aIndexOrUids = $oImapClient->MessageSimpleSort(array('REVERSE ARRIVAL'), $sSearchCriterias, $bIndexAsUid);
					}
					else
					{
						if (!\MailSo\Base\Utils::IsAscii($sCutedSearch))
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

					if ($bSearchAttachments && is_array($aIndexOrUids) && 0 < count($aIndexOrUids))
					{
						$aIndexOrUids = $this->specialUidsSearch(
							$oImapClient, $fAttachmentSearchCallback, $sFolderFullNameRaw, $aIndexOrUids, $iOffset, $iLimit);
					}
				}
				else if ($bSearchAttachments)
				{
					$bIndexAsUid = true;
					$aIndexOrUids = $this->specialIndexSearch(
						$oImapClient, $fAttachmentSearchCallback, $sFolderFullNameRaw, $iOffset, $iLimit);
				}
			}
			else
			{
				if ($bUseThreadsIfSupported && 1 < $iMessageCount)
				{
					$bIndexAsUid = true;
					$aThreadUids = array();
					try
					{
						$aThreadUids = $oImapClient->MessageSimpleThread();
					}
					catch (\MailSo\Imap\Exceptions\RuntimeException $oException)
					{
						$aThreadUids = array();
					}

					$aThreads = $this->compileThreadArray($aThreadUids);
					if ($bUseSortIfSupported)
					{
						$aThreads = $this->resortThreadArray($aThreads,
							$oImapClient->MessageSimpleSort(array('REVERSE ARRIVAL'), 'ALL', true));
					}
					else
					{
//						$this->chunkThreadArray($aThreads);
					}
					
					$aIndexOrUids = array_keys($aThreads);
					$iMessageCount = count($aIndexOrUids);
				}
				else if ($bUseSortIfSupported && 1 < $iMessageCount)
				{
					$bIndexAsUid = true;
					$aIndexOrUids = $oImapClient->MessageSimpleSort(array('REVERSE ARRIVAL'), 'ALL', $bIndexAsUid);
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

			if (is_array($aIndexOrUids))
			{
				$oMessageCollection->MessageCount = $iRealMessageCount;
				$oMessageCollection->MessageUnseenCount = $iMessageUnseenCount;
				$oMessageCollection->MessageResultCount = 0 < strlen($sSearch) || 0 < count($aFilters)
					? count($aIndexOrUids) : $iMessageCount;

				if (0 < count($aIndexOrUids))
				{
					$iOffset = (0 > $iOffset) ? 0 : $iOffset;
					$aRequestIndexOrUids = array_slice($aIndexOrUids, $iOffset, $iLimit);

					if ($bIndexAsUid)
					{
						$oMessageCollection->Uids = $aRequestIndexOrUids;
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

									$oMessageCollection->Add($oMailMessage);
									unset($oMailMessage);
								}
							}
						}
					}
				}
			}
		}

		if (!$bSearch && $bUseThreadsIfSupported && 0 < count($aThreads))
		{
			$oMessageCollection->ForeachList(function (/* @var $oMessage CApiMailMessage */ $oMessage) use ($aThreads) {
				$iUid = $oMessage->Uid();
				if (isset($aThreads[$iUid]) && is_array($aThreads[$iUid]))
				{
					$oMessage->SetThreads($aThreads[$iUid]);
				}
			});
		}

		if (0 < strlen($sInboxUidnext) &&
			'INBOX' === $oMessageCollection->FolderName &&
			$sInboxUidnext !== $oMessageCollection->UidNext)
		{
			$oMessageCollection->New = $this->GetNextMessages($oAccount, 'INBOX', $sInboxUidnext);
		}

		return $oMessageCollection;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param array $aUids
	 *
	 * @return CApiMailMessageCollection
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageListByUids($oAccount, $sFolderFullNameRaw, $aUids)
	{
		if (0 === strlen($sFolderFullNameRaw) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new CApiInvalidArgumentException();
		}

		$oMessageCollection = false;

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aList = $this->folderInformation($oImapClient, $sFolderFullNameRaw);

		$iMessageRealCount = $aList[0];
		$iMessageUnseenCount = $aList[1];
		$sUidNext = $aList[2];

		$oMessageCollection = CApiMailMessageCollection::NewInstance();

		$oMessageCollection->FolderName = $sFolderFullNameRaw;
		$oMessageCollection->Offset = 0;
		$oMessageCollection->Limit = 0;
		$oMessageCollection->Search = '';
		$oMessageCollection->UidNext = $sUidNext;

		if (0 < $iMessageRealCount)
		{
			$bIndexAsUid = true;
			$aIndexOrUids = $aUids;
			
			if (is_array($aIndexOrUids))
			{
				$oMessageCollection->MessageCount = $iMessageRealCount;
				$oMessageCollection->MessageUnseenCount = $iMessageUnseenCount;
				$oMessageCollection->MessageSearchCount = $oMessageCollection->MessageCount;
				$oMessageCollection->MessageResultCount = $oMessageCollection->MessageCount;

				if (0 < count($aIndexOrUids))
				{
					$aRequestIndexOrUids = $aIndexOrUids;

					if ($bIndexAsUid)
					{
						$oMessageCollection->Uids = $aRequestIndexOrUids;
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

									$oMessageCollection->Add($oMailMessage);
									unset($oMailMessage);
								}
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sFolderFullNameRaw
	 * @param array $aUids
	 *
	 * @return CApiMailMessageCollection
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function MessageFlags($oAccount, $sFolderFullNameRaw, $aUids)
	{
		if (0 === strlen($sFolderFullNameRaw) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new CApiInvalidArgumentException();
		}

		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aList = $this->folderInformation($oImapClient, $sFolderFullNameRaw);

		$iMessageRealCount = $aList[0];

		$mResult = array();
		if (0 < $iMessageRealCount)
		{
			$aFetchResponse = $oImapClient->Fetch(array(
				\MailSo\Imap\Enumerations\FetchType::INDEX,
				\MailSo\Imap\Enumerations\FetchType::UID,
				\MailSo\Imap\Enumerations\FetchType::FLAGS
			), implode(',', $aUids), true);

			if (is_array($aFetchResponse) && 0 < count($aFetchResponse))
			{
				$oFetchResponseItem = null;
				foreach ($aFetchResponse as /* @var $oFetchResponseItem \MailSo\Imap\FetchResponse */ &$oFetchResponseItem)
				{
					$sUid = $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::UID);
					$aFlags = $oFetchResponseItem->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::FLAGS);
					if (is_array($aFlags))
					{
						$mResult[$sUid] = array_map('strtolower', $aFlags);
					}
				}
			}
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iUidFrom
	 * @param int $iLastUid = 0
	 * @param int $iLimit = 5
	 *
	 * @return array
	 *
	 * @throws CApiInvalidArgumentException
	 */
	public function HelpdeskMessagesHelper($oAccount, $iUidFrom, &$iLastUid = 0, $iLimit = 5)
	{
		if (0 > $iUidFrom)
		{
			throw new CApiInvalidArgumentException();
		}

		$sFolderFullNameRaw = 'INBOX';
		$oImapClient =& $this->getImapClient($oAccount);

		$oImapClient->FolderExamine($sFolderFullNameRaw);

		$aList = $this->folderInformation($oImapClient, $sFolderFullNameRaw);

		$iMessageRealCount = $aList[0];
		$aUidsForRequest = array();
		$aResult = array();

		if (0 === $iUidFrom)
		{
			$iLastUid = (int) $aList[2];
			if (1 < $iLastUid)
			{
				$iLastUid--;
			}
		}
		else if (0 < $iMessageRealCount)
		{
			$aFetchResponse = $oImapClient->Fetch(array(
				\MailSo\Imap\Enumerations\FetchType::INDEX,
				\MailSo\Imap\Enumerations\FetchType::UID,
				\MailSo\Imap\Enumerations\FetchType::BODYSTRUCTURE,
				\MailSo\Imap\Enumerations\FetchType::BODY_HEADER_PEEK
			), $iUidFrom.':*', true);

			if (is_array($aFetchResponse) && 0 < count($aFetchResponse))
			{
				$oFetchResponseItem = null;
				foreach ($aFetchResponse as /* @var $oFetchResponseItem \MailSo\Imap\FetchResponse */ &$oFetchResponseItem)
				{
					$iLimit--;
					if (0 > $iLimit)
					{
						break;
					}

					$oMailMessage = CApiMailMessage::NewFetchResponseInstance($sFolderFullNameRaw, $oFetchResponseItem);
					if ($oMailMessage)
					{
						$iCurUid = $oMailMessage->Uid();
						if ($iCurUid && $iCurUid >= $iUidFrom)
						{
							if ($iCurUid > $iLastUid)
							{
								$iLastUid = $iCurUid;
							}

							$aUidsForRequest[] = $iCurUid;
						}
						
						unset($oMailMessage);
					}
				}
			}

			if (0 < count($aUidsForRequest))
			{
				foreach ($aUidsForRequest as $iUid)
				{
					$aResult[] = $this->Message($oAccount, $sFolderFullNameRaw, $iUid);
				}
			}
		}

		return $aResult;
	}
}
