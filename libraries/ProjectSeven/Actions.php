<?php

namespace ProjectSeven;

/**
 * @category ProjectSeven
 */
class Actions extends ActionsBase
{
	/**
	 * @var \CApiUsersManager
	 */
	protected $oApiUsers;

	/**
	 * @var \CApiWebmailManager
	 */
	protected $oApiWebMail;

	/**
	 * @var \CApiIntegratorManager
	 */
	protected $oApiIntegrator;

	/**
	 * @var \CApiMailManager
	 */
	protected $oApiMail;

	/**
	 * @var \CApiFilecacheManager
	 */
	protected $oApiFileCache;

	/**
	 * @var \CApiSieveManager
	 */
	protected $oApiSieve;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		$this->oHttp = null;

		$this->oApiUsers = \CApi::Manager('users');
		$this->oApiWebMail = \CApi::Manager('webmail');
		$this->oApiIntegrator = \CApi::Manager('integrator');
		$this->oApiMail = \CApi::Manager('mail');
		$this->oApiFileCache = \CApi::Manager('filecache');
		$this->oApiSieve = \CApi::Manager('sieve');
	}

	/**
	 * @return \ProjectSeven\Actions
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @return \CApiFilecacheManager
	 */
	public function ApiFileCache()
	{
		return $this->oApiFileCache;
	}

	/**
	 * @return \CApiSieveManager
	 */
	public function ApiSieve()
	{
		return $this->oApiSieve;
	}

	/**
	 * @param string $sToken
	 *
	 * @return bool
	 */
	public function ValidateCsrfToken($sToken)
	{
		return $this->oApiIntegrator->ValidateCsrfToken($sToken);
	}

	/**
	 * @param int $iAccountId
	 * @return CAccount | null
	 */
	public function GetAccount($iAccountId)
	{
		$oResult = null;
		$iUserId = $this->oApiIntegrator->GetLogginedUserId();
		if (0 < $iUserId)
		{
			$oAccount = $this->oApiUsers->GetAccountById($iAccountId);
			$oResult = $oAccount instanceof \CAccount && $oAccount->IdUser === $iUserId ? $oAccount : null;
		}

		return $oResult;
	}

	/**
	 * @return \CAccount | null
	 */
	public function GetDefaultAccount()
	{
		$oResult = null;
		$iUserId = $this->oApiIntegrator->GetLogginedUserId();
		if (0 < $iUserId)
		{
			$iAccountId = $this->oApiUsers->GetDefaultAccountId($iUserId);
			if (0 < $iAccountId)
			{
				$oAccount = $this->oApiUsers->GetAccountById($iAccountId);
				$oResult = $oAccount instanceof \CAccount ? $oAccount : null;
			}
		}

		return $oResult;
	}

	/**
	 * @return \CAccount|null
	 */
	public function GetCurrentAccount($bThrowAuthExceptionOnFalse = true)
	{
		return $this->getAccountFromParam($bThrowAuthExceptionOnFalse);
	}

	/**
	 * @return \CAccount|null
	 */
	protected function getAccountFromParam($bThrowAuthExceptionOnFalse = true)
	{
		$sAccountID = (string) $this->getParamValue('AccountID', '');
		if (0 === strlen($sAccountID) || !is_numeric($sAccountID))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oResult = $this->GetAccount((int) $sAccountID);

		if ($bThrowAuthExceptionOnFalse && !($oResult instanceof \CAccount))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::AuthError);
		}

		return $oResult;
	}

	/**
	 * @return CAccount | null
	 */
	protected function getDefaultAccountFromParam($bThrowAuthExceptionOnFalse = true)
	{
		$oResult = $this->GetDefaultAccount();
		if ($bThrowAuthExceptionOnFalse && !($oResult instanceof \CAccount))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::AuthError);
		}

		return $oResult;
	}

	/**
	 * @return array
	 */
	public function AjaxNoop()
	{
		return $this->TrueResponse(null, 'Noop');
	}

	/**
	 * @return array
	 */
	public function AjaxPing()
	{
		return $this->DefaultResponse(null, 'Ping', 'Pong');
	}

	/**
	 * @return array
	 */
	public function AjaxIsAuth()
	{
		$oAccount = $this->getAccountFromParam(false);
		if ($oAccount)
		{
			$sClientTimeZone = trim($this->getParamValue('ClientTimeZone', ''));
			if ('' !== $sClientTimeZone)
			{
				$oAccount->User->ClientTimeZone = $sClientTimeZone;
				$this->oApiUsers->UpdateAccount($oAccount);
			}
		}

		return $this->DefaultResponse(null, 'IsAuth', !!$oAccount);
	}

	/**
	 * @return array
	 */
	public function AjaxFolderList()
	{
		$oAccount = $this->getAccountFromParam();
		return $this->DefaultResponse($oAccount, 'FolderList', $this->oApiMail->Folders($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxSetupSystemFolders()
	{
		$oAccount = $this->getAccountFromParam();
		
		$sSent = (string) $this->getParamValue('Sent', '');
		$sDrafts = (string) $this->getParamValue('Drafts', '');
		$sTrash = (string) $this->getParamValue('Trash', '');
		$sSpam = (string) $this->getParamValue('Spam', '');

		$aData = array();
		if (0 < strlen(trim($sSent)))
		{
			$aData[$sSent] = \EFolderType::Sent;
		}
		if (0 < strlen(trim($sDrafts)))
		{
			$aData[$sDrafts] = \EFolderType::Drafts;
		}
		if (0 < strlen(trim($sTrash)))
		{
			$aData[$sTrash] = \EFolderType::Trash;
		}
		if (0 < strlen(trim($sSpam)))
		{
			$aData[$sSpam] = \EFolderType::Spam;
		}

		return $this->DefaultResponse($oAccount, 'SetupSystemFolders', $this->oApiMail->SetSystemFolderNames($oAccount, $aData));
	}

	/**
	 * @return array
	 */
	public function AjaxFolderCounts()
	{
		$aFolders = $this->getParamValue('Folders', '');
		if (!is_array($aFolders) || 0 === count($aFolders))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$aResult = array();
		$oAccount = $this->getAccountFromParam();

		foreach ($aFolders as $sFolder)
		{
			$sFolder = (string) $sFolder;

			try
			{
				$aResult[$sFolder] = $this->oApiMail->FolderCounts($oAccount, $sFolder);
			}
			catch (\Exception $oException)
			{
				\CApi::Log((string) $oException);
			}

			if (!isset($aResult[$sFolder]) || !is_array($aResult[$sFolder]))
			{
				$aResult[$sFolder] = array(0, 0, '', '');
			}
		}

		return $this->DefaultResponse($oAccount, 'FolderCounts', $aResult);
	}

	/**
	 * @return array
	 */
	public function AjaxFolderCreate()
	{
		$sFolderNameInUtf8 = trim((string) $this->getParamValue('FolderNameInUtf8', ''));
		$sDelimiter = trim((string) $this->getParamValue('Delimiter', ''));
		$sFolderParentFullNameRaw = (string) $this->getParamValue('FolderParentFullNameRaw', '');

		if (0 === strlen($sFolderNameInUtf8) || 1 !== strlen($sDelimiter))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->FolderCreate($oAccount, $sFolderNameInUtf8, $sDelimiter, $sFolderParentFullNameRaw);

		return $this->TrueResponse($oAccount, 'FolderCreate');
	}
	
	/**
	 * @return array
	 */
	public function AjaxFolderRename()
	{
		$sPrevFolderFullNameRaw = (string) $this->getParamValue('PrevFolderFullNameRaw', '');
		$sNewFolderNameInUtf8 = trim($this->getParamValue('NewFolderNameInUtf8', ''));
		
		if (0 === strlen($sPrevFolderFullNameRaw) || 0 === strlen($sNewFolderNameInUtf8))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->FolderRename($oAccount, $sPrevFolderFullNameRaw, $sNewFolderNameInUtf8);

		return $this->TrueResponse($oAccount, 'FolderRename');
	}

	/**
	 * @return array
	 */
	public function AjaxFolderDelete()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->FolderDelete($oAccount, $sFolderFullNameRaw);

		return $this->TrueResponse($oAccount, 'FolderDelete');
	}

	/**
	 * @return array
	 */
	public function AjaxFolderSubscribe()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$bSetAction = '1' === (string) $this->getParamValue('SetAction', '0');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		if (!$oAccount->IsEnabledExtension(\CAccount::DisableManageSubscribe))
		{
			$this->oApiMail->FolderSubscribe($oAccount, $sFolderFullNameRaw, $bSetAction);
			return $this->TrueResponse($oAccount, 'FolderSubscribe');
		}

		return $this->FalseResponse($oAccount, 'FolderSubscribe');
	}

	/**
	 * @return array
	 */
	public function AjaxFolderListOrderUpdate()
	{
		$aFolderList = $this->getParamValue('FolderList', null);
		if (!is_array($aFolderList))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		return $this->DefaultResponse($oAccount, 'FolderListOrderUpdate',
			$this->oApiMail->FoldersOrderUpdate($oAccount, $aFolderList));
	}

	/**
	 * @return array
	 */
	public function AjaxFolderClear()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->FolderClear($oAccount, $sFolderFullNameRaw);

		return $this->TrueResponse($oAccount, 'FolderClear');
	}

	/**
	 * @return array
	 */
	public function AjaxMessageList()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$sOffset = trim((string) $this->getParamValue('Offset', ''));
		$sLimit = trim((string) $this->getParamValue('Limit', ''));
		$sSearch = trim((string) $this->getParamValue('Search', ''));
		
		$aExistenUids = $this->getParamValue('ExistenUids', array());
		if (!is_array($aExistenUids))
		{
			$aExistenUids = array();
		}

		$iOffset = 0 < strlen($sOffset) && is_numeric($sOffset) ? (int) $sOffset : 0;
		$iLimit = 0 < strlen($sLimit) && is_numeric($sLimit) ? (int) $sLimit : 0;

		if (0 === strlen(trim($sFolderFullNameRaw)) || 0 > $iOffset || 0 >= $iLimit || 200 < $sLimit)
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$oMessageList = $this->oApiMail->MessageList(
			$oAccount, $sFolderFullNameRaw, $iOffset, $iLimit, $sSearch, $aExistenUids);

		return $this->DefaultResponse($oAccount, 'MessageList', $oMessageList);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageMove()
	{
		$sFromFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$sToFolderFullNameRaw = (string) $this->getParamValue('ToFolder', '');
		$aUids = explode(',', (string) $this->getParamValue('Uids', ''));
		$aUids = array_map('trim', $aUids);
		$aUids = array_map('intval', $aUids);

		if (0 === strlen(trim($sFromFolderFullNameRaw)) || 0 === strlen(trim($sToFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		try
		{
			$this->oApiMail->MessageMove(
				$oAccount, $sFromFolderFullNameRaw, $sToFolderFullNameRaw, $aUids);
		}
		catch (\MailSo\Imap\Exceptions\NegativeResponseException $oException)
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::CanNotMoveMessageQuota, $oException);
		}
		catch (\Exceptions $oException)
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::CanNotMoveMessage, $oException);
		}

		return $this->TrueResponse($oAccount, 'MessageMove');
	}

	/**
	 * @return array
	 */
	public function AjaxMessageDelete()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$aUids = explode(',', (string) $this->getParamValue('Uids', ''));
		$aUids = array_map('trim', $aUids);
		$aUids = array_map('intval', $aUids);

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->MessageDelete($oAccount, $sFolderFullNameRaw, $aUids);

		return $this->TrueResponse($oAccount, 'MessageDelete');
	}

	/**
	 * @param \CAccount $oAccount
	 * @param bool $bWithDraftInfo = true
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function buildMessage($oAccount, $bWithDraftInfo = true)
	{
		$sTo = $this->getParamValue('To', '');
		$sCc = $this->getParamValue('Cc', '');
		$sBcc = $this->getParamValue('Bcc', '');
		$sSubject = $this->getParamValue('Subject', '');
		$bTextIsHtml = '1' === $this->getParamValue('IsHtml', '0');
		$sText = $this->getParamValue('Text', '');
		$aAttachments = $this->getParamValue('Attachments', null);

		$aDraftInfo = $this->getParamValue('DraftInfo', null);
		$sInReplyTo = $this->getParamValue('InReplyTo', '');
		$sReferences = $this->getParamValue('References', '');

		$sImportance = $this->getParamValue('Importance', ''); //1 3 5
		$sSensivity = $this->getParamValue('Sensivity', ''); //0 1 2 3 4
		$bReadingConfirmation = '1' === $this->getParamValue('ReadingConfirmation', '0');

		$oMessage = \MailSo\Mime\Message::NewInstance();
		$oMessage->RegenerateMessageId();

		$sXMailer = \CApi::GetConf('webmail.xmailer-value', '');
		if (0 < strlen($sXMailer))
		{
			$oMessage->SetXMailer($sXMailer);
		}

		$sFrom = 0 < strlen($oAccount->FriendlyName) ? '"'.$oAccount->FriendlyName.'"' : '';
		if (0 < strlen($sFrom))
		{
			$sFrom .= ' <'.$oAccount->Email.'>';
		}
		else
		{
			$sFrom .= $oAccount->Email;
		}

		$oMessage
			->SetFrom(\MailSo\Mime\Email::NewInstance($sFrom))
			->SetSubject($sSubject)
		;

		$oToEmails = \MailSo\Mime\EmailCollection::NewInstance($sTo);
		if ($oToEmails && $oToEmails->Count())
		{
			$oMessage->SetTo($oToEmails);
		}

		$oCcEmails = \MailSo\Mime\EmailCollection::NewInstance($sCc);
		if ($oCcEmails && $oCcEmails->Count())
		{
			$oMessage->SetCc($oCcEmails);
		}

		$oBccEmails = \MailSo\Mime\EmailCollection::NewInstance($sBcc);
		if ($oBccEmails && $oBccEmails->Count())
		{
			$oMessage->SetBcc($oBccEmails);
		}

		if ($bWithDraftInfo && is_array($aDraftInfo) && !empty($aDraftInfo[0]) && !empty($aDraftInfo[1]) && !empty($aDraftInfo[2]))
		{
			$oMessage->SetDraftInfo($aDraftInfo[0], $aDraftInfo[1], $aDraftInfo[2]);
		}

		if (0 < strlen($sInReplyTo))
		{
			$oMessage->SetInReplyTo($sInReplyTo);
		}

		if (0 < strlen($sReferences))
		{
			$oMessage->SetReferences($sReferences);
		}

		if (0 < strlen($sReferences))
		{
			$oMessage->SetReferences($sReferences);
		}

		if (0 < strlen($sImportance) && in_array((int) $sImportance, array(
			\MailSo\Mime\Enumerations\MessagePriority::HIGH,
			\MailSo\Mime\Enumerations\MessagePriority::NORMAL,
			\MailSo\Mime\Enumerations\MessagePriority::LOW
		)))
		{
			$oMessage->SetPriority((int) $sImportance);
		}

		if (0 < strlen($sSensivity) && in_array((int) $sSensivity, array(
			\MailSo\Mime\Enumerations\Sensitivity::NOTHING,
			\MailSo\Mime\Enumerations\Sensitivity::CONFIDENTIAL,
			\MailSo\Mime\Enumerations\Sensitivity::PRIVATE_,
			\MailSo\Mime\Enumerations\Sensitivity::PERSONAL,
		)))
		{
			$oMessage->SetSensivity((int) $sSensivity);
		}

		if ($bReadingConfirmation)
		{
			$oMessage->SetReadConfirmation($oAccount->Email);
		}

		$aFoundedCids = array();
		$oMessage->AddText($bTextIsHtml ?
			\MailSo\Base\HtmlUtils::BuildHtml($sText, $aFoundedCids) : $sText, $bTextIsHtml);

		if ($bTextIsHtml)
		{
			$oMessage->AddText(\MailSo\Base\HtmlUtils::ConvertHtmlToPlain($sText), false);
		}

		if (is_array($aAttachments))
		{
			foreach ($aAttachments as $sTempName => $aData)
			{
				if (is_array($aData) && 4 === count($aData) && isset($aData[0], $aData[1], $aData[2], $aData[3]))
				{
					$sFileName = (string) $aData[0];
					$sCID = (string) $aData[1];
					$bIsInline = '1' === (string) $aData[2];
					$bIsLinked = '1' === (string) $aData[3];

					$rResource = $this->ApiFileCache()->GetFile($oAccount, $sTempName);
					if (is_resource($rResource))
					{
						$iFileSize = $this->ApiFileCache()->FileSize($oAccount, $sTempName);

						$sCID = trim(trim($sCID), '<>');
						$bIsFounded = 0 < strlen($sCID) ? in_array($sCID, $aFoundedCids) : false;

						if (!$bIsLinked || $bIsFounded)
						{
							$oMessage->Attachments()->Add(
								\MailSo\Mime\Attachment::NewInstance($rResource, $sFileName, $iFileSize, $bIsInline,
									$bIsLinked, $bIsLinked ? '<'.$sCID.'>' : '')
							);
						}
					}
				}
			}
		}

		return $oMessage;
	}

	/**
	 * @param \CAccount $oAccount
	 *
	 * @return \MailSo\Mime\Message
	 *
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 */
	private function buildConfirmationMessage($oAccount)
	{
		$sConfirmation = $this->getParamValue('Confirmation', '');
		$sSubject = $this->getParamValue('Subject', '');
		$sText = $this->getParamValue('Text', '');

		if (0 === strlen($sConfirmation) || 0 === strlen($sSubject) || 0 === strlen($sText))
		{
			throw new \MailSo\Base\Exceptions\InvalidArgumentException();
		}

		$oMessage = \MailSo\Mime\Message::NewInstance();
		$oMessage->RegenerateMessageId();

		$sXMailer = \CApi::GetConf('webmail.xmailer-value', '');
		if (0 < strlen($sXMailer))
		{
			$oMessage->SetXMailer($sXMailer);
		}

		$oFrom = \MailSo\Mime\EmailCollection::Parse($sConfirmation);
		if (!$oFrom || 0 === $oFrom->Count())
		{
			throw new \MailSo\Base\Exceptions\InvalidArgumentException();
		}

		$sFrom = 0 < strlen($oAccount->FriendlyName) ? '"'.$oAccount->FriendlyName.'"' : '';
		if (0 < strlen($sFrom))
		{
			$sFrom .= ' <'.$oAccount->Email.'>';
		}
		else
		{
			$sFrom .= $oAccount->Email;
		}
		
		$oMessage
			->SetFrom(\MailSo\Mime\Email::NewInstance($sFrom))
			->SetTo($oFrom)
			->SetSubject($sSubject)
		;

		$oMessage->AddText($sText, false);

		return $oMessage;
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSend()
	{
		$oAccount = $this->getAccountFromParam();

		$sSentFolder = $this->getParamValue('SentFolder', '');
		$sDraftFolder = $this->getParamValue('DraftFolder', '');
		$sDraftUid = $this->getParamValue('DraftUid', '');
		$aDraftInfo = $this->getParamValue('DraftInfo', null);

		$oMessage = $this->buildMessage($oAccount, false);
		if ($oMessage)
		{
			$bIsDemo = false;
			\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
			if ($bIsDemo)
			{
				$oRcpt = $oMessage->GetRcpt();
				if ($oRcpt && 0 < $oRcpt->Count())
				{
					$bExternal = false;
					$oRcpt->ForeachList(function (/* @var $oItem \MailSo\Mime\Email */ $oItem) use (&$bExternal) {
						if (!$bExternal && $oItem && 'afterlogic.com' !== strtolower($oItem->GetDomain()))
						{
							$bExternal = true;
						}
					});

					if ($bExternal)
					{
						throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::DemoAccount);
					}
				}
			}

			try
			{
				$mResult = $this->oApiMail->MessageSend($oAccount, $oMessage, $sSentFolder, $sDraftFolder, $sDraftUid);
			}
			catch (\CApiManagerException $oException)
			{
				$iCode = \ProjectSeven\Notifications::CanNotSendMessage;
				switch ($oException->getCode())
				{
					case \Errs::Mail_InvalidRecipients:
						$iCode = \ProjectSeven\Notifications::InvalidRecipients;
						break;
					case \Errs::Mail_CannotSendMessage:
						$iCode = \ProjectSeven\Notifications::CanNotSendMessage;
						break;
				}

				throw new \ProjectSeven\Exceptions\ClientException($iCode, $oException);
			}

			if ($mResult)
			{
				$oApiContacts = $this->ApiContacts();
				if ($oApiContacts)
				{
					$aCollection = $oMessage->GetRcpt();

					$aEmails = array();
					$aCollection->ForeachList(function ($oEmail) use (&$aEmails) {
						$aEmails[strtolower($oEmail->GetEmail())] = trim($oEmail->GetDisplayName());
					});

					if (is_array($aEmails))
					{
						$oApiContacts->UpdateSuggestTable($oAccount->IdUser, $aEmails);
					}
				}
			}

			if (is_array($aDraftInfo) && 3 === count($aDraftInfo))
			{
				$sDraftInfoType = $aDraftInfo[0];
				$sDraftInfoUid = $aDraftInfo[1];
				$sDraftInfoFolder = $aDraftInfo[2];

				try
				{
					switch (strtolower($sDraftInfoType))
					{
						case 'reply':
						case 'reply-all':
							$this->oApiMail->MessageFlag($oAccount,
								$sDraftInfoFolder, array($sDraftInfoUid),
								\MailSo\Imap\Enumerations\MessageFlag::ANSWERED,
								\EMailMessageStoreAction::Add);
							break;
						case 'forward':
							$this->oApiMail->MessageFlag($oAccount,
								$sDraftInfoFolder, array($sDraftInfoUid),
								'$Forwarded',
								\EMailMessageStoreAction::Add);
							break;
					}
				}
				catch (\Exception $oException) {}
			}
		}

		return $this->DefaultResponse($oAccount, 'MessageSend', $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxMessageSendConfirmation()
	{
		$oAccount = $this->getAccountFromParam();
		
		$oMessage = $this->buildConfirmationMessage($oAccount);
		if ($oMessage)
		{
			try
			{
				$mResult = $this->oApiMail->MessageSend($oAccount, $oMessage);
			}
			catch (\CApiManagerException $oException)
			{
				$iCode = \ProjectSeven\Notifications::CanNotSendMessage;
				switch ($oException->getCode())
				{
					case \Errs::Mail_InvalidRecipients:
						$iCode = \ProjectSeven\Notifications::InvalidRecipients;
						break;
					case \Errs::Mail_CannotSendMessage:
						$iCode = \ProjectSeven\Notifications::CanNotSendMessage;
						break;
				}

				throw new \ProjectSeven\Exceptions\ClientException($iCode, $oException);
			}
		}

		return $this->DefaultResponse($oAccount, 'MessageSendConfirmation', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageUploadAttachments()
	{
		$oAccount = $this->getAccountFromParam();

		$mResult = false;
		$self = $this;

		try
		{
			$aAttachments = $this->getParamValue('Attachments', array());
			if (is_array($aAttachments) && 0 < count($aAttachments))
			{
				$mResult = array();
				foreach ($aAttachments as $sAttachment)
				{
					$aValues = \CApi::DecodeKeyValues($sAttachment);
					if (is_array($aValues))
					{
						$sFolder = isset($aValues['Folder']) ? $aValues['Folder'] : '';
						$iUid = (int) isset($aValues['Uid']) ? $aValues['Uid'] : 0;
						$sMimeIndex = (string) isset($aValues['MimeIndex']) ? $aValues['MimeIndex'] : '';

						$sTempName = md5($sAttachment);
						if (!$this->ApiFileCache()->FileExists($oAccount, $sTempName))
						{
							$this->oApiMail->MessageMimeStream($oAccount,
								function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($oAccount, &$mResult, $sTempName, $sAttachment, $self) {
									if (is_resource($rResource))
									{
										$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
										$sFileName = $self->clearFileName($sFileName, $sContentType, $sMimeIndex);

										if ($self->ApiFileCache()->PutFile($oAccount, $sTempName, $rResource))
										{
											$mResult[$sTempName] = $sAttachment;
										}
									}
								}, $sFolder, $iUid, $sMimeIndex);
						}
						else
						{
							$mResult[$sTempName] = $sAttachment;
						}
					}
				}
			}
		}
		catch (\Exception $oException)
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::MailServerError, $oException);
		}

		return $this->DefaultResponse($oAccount, 'MessageUploadAttachments', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSave()
	{
		$mResult = false;

		$oAccount = $this->getAccountFromParam();

		$sDraftFolder = $this->getParamValue('DraftFolder', '');
		$sDraftUid = $this->getParamValue('DraftUid', '');

		if (0 === strlen($sDraftFolder))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oMessage = $this->buildMessage($oAccount);
		if ($oMessage)
		{
			try
			{
				$mResult = $this->oApiMail->MessageSave($oAccount, $oMessage, $sDraftFolder, $sDraftUid);
			}
			catch (\CApiManagerException $oException)
			{
				$iCode = \ProjectSeven\Notifications::CanNotSaveMessage;
				throw new \ProjectSeven\Exceptions\ClientException($iCode, $oException);
			}
		}

		return $this->DefaultResponse($oAccount, 'MessageSave', $mResult);
	}

	/**
	 * @return array
	 */
	private function ajaxMessageSetFlag($sFlagName, $sFunctionName)
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$bSetAction = '1' === (string) $this->getParamValue('SetAction', '0');
		$aUids = explode(',', (string) $this->getParamValue('Uids', ''));
		$aUids = array_map('trim', $aUids);
		$aUids = array_map('intval', $aUids);

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->MessageFlag($oAccount, $sFolderFullNameRaw, $aUids, $sFlagName,
			$bSetAction ? \EMailMessageStoreAction::Add : \EMailMessageStoreAction::Remove);

		return $this->TrueResponse($oAccount, $sFunctionName);
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSetFlagged()
	{
		return $this->ajaxMessageSetFlag(\MailSo\Imap\Enumerations\MessageFlag::FLAGGED, 'MessageSetFlagged');
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSetSeen()
	{
		return $this->ajaxMessageSetFlag(\MailSo\Imap\Enumerations\MessageFlag::SEEN, 'MessageSetSeen');
	}

	/**
	 * @return array
	 */
	public function AjaxMessageSetAllSeen()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$bSetAction = '1' === (string) $this->getParamValue('SetAction', '0');

		if (0 === strlen(trim($sFolderFullNameRaw)))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiMail->MessageFlag($oAccount, $sFolderFullNameRaw, array('1'),
			\MailSo\Imap\Enumerations\MessageFlag::SEEN,
			$bSetAction ? \EMailMessageStoreAction::Add : \EMailMessageStoreAction::Remove, true);

		return $this->TrueResponse($oAccount, 'MessageSetAllSeen');
	}

	/**
	 * @return array
	 */
	public function AjaxQuota()
	{
		$oAccount = $this->getAccountFromParam();

		return $this->DefaultResponse($oAccount, 'Quota', $this->oApiMail->Quota($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxEmailSafety()
	{
		$sEmail = (string) $this->getParamValue('Email', '');
		if (0 === strlen(trim($sEmail)))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$this->oApiUsers->SetSafetySender($oAccount->IdUser, $sEmail);

		return $this->DefaultResponse($oAccount, 'EmailSafety', true);
	}

	/**
	 * @return array
	 */
	public function AjaxMessage()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$sUid = trim((string) $this->getParamValue('Uid', ''));
		$sRfc822SubMimeIndex = trim((string) $this->getParamValue('Rfc822MimeIndex', ''));

		$iUid = 0 < strlen($sUid) && is_numeric($sUid) ? (int) $sUid : 0;

		if (0 === strlen(trim($sFolderFullNameRaw)) || 0 >= $iUid)
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$bParseICal = false;
		$oApiCollaborationManager = /* @var $oApiCollaborationManager CApiCollaborationManager */ \CApi::Manager('collaboration');
		if ($oApiCollaborationManager && $oApiCollaborationManager->IsCalendarAppointmentsSupported())
		{
			$bParseICal = $oAccount->User->GetCapa('MEETINGS');
		}

		$oMessage = $this->oApiMail->Message($oAccount, $sFolderFullNameRaw, $iUid, $sRfc822SubMimeIndex, $bParseICal);
		if (!($oMessage instanceof \CApiMailMessage))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::CanNotGetMessage);
		}

		return $this->DefaultResponse($oAccount, 'Message', $oMessage);
	}

	/**
	 * @return array
	 */
	public function AjaxMessages()
	{
		$sFolderFullNameRaw = (string) $this->getParamValue('Folder', '');
		$aUids = $this->getParamValue('Uids', null);

		if (0 === strlen(trim($sFolderFullNameRaw)) || !is_array($aUids) || 0 === count($aUids))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oAccount = $this->getAccountFromParam();

		$bParseICal = false;
		$oApiCollaborationManager = /* @var $oApiCollaborationManager CApiCollaborationManager */ \CApi::Manager('collaboration');
		if ($oApiCollaborationManager && $oApiCollaborationManager->IsCalendarAppointmentsSupported())
		{
			$bParseICal = $oAccount->User->GetCapa('MEETINGS');
		}

		$aList = array();
		foreach ($aUids as $iUid)
		{
			if (is_numeric($iUid))
			{
				$oMessage = $this->oApiMail->Message($oAccount, $sFolderFullNameRaw, (int) $iUid, '', $bParseICal);

				if ($oMessage instanceof \CApiMailMessage)
				{
					$aList[] = $oMessage;
				}

				unset($oMessage);
			}
		}

		return $this->DefaultResponse($oAccount, 'Messages', $aList);
	}

	/**
	 * @return array
	 */
	public function AjaxAppData()
	{
		$oApiIntegrator = \CApi::Manager('integrator');
		return $this->DefaultResponse(null, __FUNCTION__, $oApiIntegrator ? $oApiIntegrator->AppData() : false);
	}
	
	/**
	 * @return array
	 */
	public function AjaxLoginLanguageUpdate()
	{
		$bResult = false;
		
		$sLanguage = (string) $this->getParamValue('Language', '');
		if (!empty($sLanguage))
		{
			$oApiIntegrator = \CApi::Manager('integrator');
			if ($oApiIntegrator)
			{
				$oApiIntegrator->SetLoginLanguage($sLanguage);
				$bResult = true;
			}
		}
		
		return $this->DefaultResponse(null, __FUNCTION__, $bResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountSignature()
	{
		$oAccount = $this->getAccountFromParam();
		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'Type' => $oAccount->SignatureType,
			'Options' => $oAccount->SignatureOptions,
			'Signature' => $oAccount->Signature
		));
	}

	/**
	 * @return array
	 */
	public function AjaxUpdateAccountSignature()
	{
		$oAccount = $this->getAccountFromParam();

		$oAccount->Signature = (string) $this->oHttp->GetPost('Signature', $oAccount->Signature);
		$oAccount->SignatureType = (string) $this->oHttp->GetPost('Type', $oAccount->SignatureType);
		$oAccount->SignatureOptions = (string) $this->oHttp->GetPost('Options', $oAccount->SignatureOptions);

		return $this->DefaultResponse($oAccount, __FUNCTION__, $this->oApiUsers->UpdateAccount($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxAccountDelete()
	{
		$bResult = false;
		$oAccount = $this->getAccountFromParam();

		$iAccountIDToDelete = (int) $this->oHttp->GetPost('AccountIDToDelete', 0);
		if (0 < $iAccountIDToDelete)
		{
			$oAccountToDelete = null;
			if ($oAccount->IdAccount === $iAccountIDToDelete)
			{
				$oAccountToDelete = $oAccount;
			}
			else
			{
				$oAccountToDelete = $this->oApiUsers->GetAccountById($iAccountIDToDelete);
			}

			if ($oAccountToDelete instanceof \CAccount && $oAccountToDelete->IdUser === $oAccount->IdUser && !$oAccount->IsInternal)
			{
				$bResult = $this->oApiUsers->DeleteAccount($oAccountToDelete);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}

	/**
	 * @param bool $bIsUpdate
	 * @param \CAccount &$oAccount
	 */
	private function populateAccountFromHttpPost($bIsUpdate, &$oAccount)
	{
		if ($bIsUpdate && !$oAccount->Domain->AllowUsersChangeEmailSettings)
		{
			$oAccount->FriendlyName = (string) $this->oHttp->GetPost('FriendlyName', $oAccount->FriendlyName);
		}
		else
		{
			$oAccount->FriendlyName = (string) $this->oHttp->GetPost('FriendlyName', $oAccount->FriendlyName);

			if (!$oAccount->IsInternal)
			{
				$oAccount->IncomingMailPort = (int) $this->oHttp->GetPost('IncomingMailPort');
				$oAccount->OutgoingMailPort = (int) $this->oHttp->GetPost('OutgoingMailPort');
				$oAccount->OutgoingMailAuth = ('2' === (string) $this->oHttp->GetPost('OutgoingMailAuth', '2'))
					? \ESMTPAuthType::AuthCurrentUser : \ESMTPAuthType::NoAuth;

				$oAccount->IncomingMailServer = (string) $this->oHttp->GetPost('IncomingMailServer', '');
				$oAccount->IncomingMailLogin = (string) $this->oHttp->GetPost('IncomingMailLogin', '');

				$oAccount->OutgoingMailServer = (string) $this->oHttp->GetPost('OutgoingMailServer', '');

				if (!$bIsUpdate)
				{
					$oAccount->IncomingMailProtocol = \EMailProtocol::IMAP4;

					$sIncomingMailPassword = (string) $this->oHttp->GetPost('IncomingMailPassword', '');
					if (API_DUMMY !== $sIncomingMailPassword)
					{
						$oAccount->IncomingMailPassword = $sIncomingMailPassword;
					}

					$oAccount->OutgoingMailLogin = (string) $this->oHttp->GetPost('OutgoingMailLogin', '');
					$sOutgoingMailPassword = (string) $this->oHttp->GetPost('OutgoingMailPassword', '');
					if (API_DUMMY !== $sOutgoingMailPassword)
					{
						$oAccount->OutgoingMailPassword = $sOutgoingMailPassword;
					}
				}
			}

			if (!$bIsUpdate)
			{
				$oAccount->Email = (string) $this->oHttp->GetPost('Email', '');
			}
		}
	}

	/**
	 * @return array
	 */
	public function AjaxUpdateAccountPassword()
	{
		$bResult = false;
		$oAccount = $this->getAccountFromParam();

		$sCurrentIncomingMailPassword = (string) $this->oHttp->GetPost('CurrentIncomingMailPassword', '');
		$sNewIncomingMailPassword = (string) $this->oHttp->GetPost('NewIncomingMailPassword', '');

		if ($oAccount->IsEnabledExtension(\CAccount::ChangePasswordExtension) &&
			0 < strlen($sNewIncomingMailPassword) &&
			$sCurrentIncomingMailPassword === $oAccount->IncomingMailPassword)
		{
			$oAccount->PreviousMailPassword = $oAccount->IncomingMailPassword;
			$oAccount->IncomingMailPassword = $sNewIncomingMailPassword;

			$bResult = $this->oApiUsers->UpdateAccount($oAccount);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountCreate()
	{
		$bResult = false;
		$oNewAccount = null;
		$oAccount = $this->getDefaultAccountFromParam();

		$oApiDomains = \CApi::Manager('domains');
		$oDomain = $oApiDomains->GetDefaultDomain();
		if ($oDomain)
		{
			$oNewAccount = new \CAccount($oDomain);
			$oNewAccount->IdUser = $oAccount->IdUser;
			$oNewAccount->IsDefaultAccount = false;

			$this->populateAccountFromHttpPost(false, $oNewAccount);

			// TODO
			$oNewAccount->IncomingMailUseSSL = in_array($oNewAccount->IncomingMailPort, array(993, 995));
			$oNewAccount->OutgoingMailUseSSL = in_array($oNewAccount->OutgoingMailPort, array(465));

			if ($this->oApiUsers->CreateAccount($oNewAccount))
			{
				$bResult = true;
			}
			else
			{
				$iClientErrorCode = \ProjectSeven\Notifications::CanNotCreateAccount;
				$oException = $this->oApiUsers->GetLastException();
				if ($oException)
				{
					switch ($oException->getCode())
					{
						case \Errs::WebMailManager_AccountDisabled:
						case \Errs::UserManager_AccountAuthenticationFailed:
						case \Errs::WebMailManager_AccountAuthentication:
						case \Errs::WebMailManager_NewUserRegistrationDisabled:
						case \Errs::WebMailManager_AccountWebmailDisabled:
							$iClientErrorCode = \ProjectSeven\Notifications::AuthError;
							break;
						case \Errs::UserManager_AccountConnectToMailServerFailed:
						case \Errs::WebMailManager_AccountConnectToMailServerFailed:
							$iClientErrorCode = \ProjectSeven\Notifications::MailServerError;
							break;
						case \Errs::UserManager_LicenseKeyInvalid:
						case \Errs::UserManager_AccountCreateUserLimitReached:
						case \Errs::UserManager_LicenseKeyIsOutdated:
							$iClientErrorCode = \ProjectSeven\Notifications::LicenseProblem;
							break;
						case \Errs::Db_ExceptionError:
							$iClientErrorCode = \ProjectSeven\Notifications::DataBaseError;
							break;
					}
				}

				return $this->FalseResponse($oAccount, __FUNCTION__, $iClientErrorCode);
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__,
			$bResult && $oNewAccount ? $oNewAccount->IdAccount : false);
	}

	/**
	 * @return array
	 */
	public function AjaxAccountSettings()
	{
		$oAccount = $this->getAccountFromParam();
		$aResult = array();

		$aResult['IsLinked'] = 0 < $oAccount->IdDomain;
		$aResult['IsInternal'] = (bool) $oAccount->IsInternal;
		$aResult['IsDefault'] = (bool) $oAccount->IsDefaultAccount;

		$aResult['FriendlyName'] = $oAccount->FriendlyName;
		$aResult['Email'] = $oAccount->Email;

		$aResult['IncomingMailServer'] = $oAccount->IncomingMailServer;
		$aResult['IncomingMailPort'] = $oAccount->IncomingMailPort;
		$aResult['IncomingMailLogin'] = $oAccount->IncomingMailLogin;

		$aResult['OutgoingMailServer'] = $oAccount->OutgoingMailServer;
		$aResult['OutgoingMailPort'] = $oAccount->OutgoingMailPort;
		$aResult['OutgoingMailLogin'] = $oAccount->OutgoingMailLogin;
		$aResult['OutgoingMailAuth'] = $oAccount->OutgoingMailAuth;

		$aResult['Extensions'] = array();

		// extensions
		if ($oAccount->IsEnabledExtension(\CAccount::IgnoreSubscribeStatus) &&
			!$oAccount->IsEnabledExtension(\CAccount::DisableManageSubscribe))
		{
			$oAccount->EnableExtension(\CAccount::DisableManageSubscribe);
		}

		$aExtensions = $oAccount->GetExtensions();
		foreach ($aExtensions as $sExtensionName)
		{
			if ($oAccount->IsEnabledExtension($sExtensionName))
			{
				$aResult['Extensions'][] = $sExtensionName;
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aResult);
	}

	/**
	 * @return array
	 */
	public function AjaxSyncSettings()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$aResult = array(
			'Mobile' => $this->mobileSyncSettings($oAccount),
			'Outlook' => $this->outlookSyncSettings($oAccount)
		);

		return $this->DefaultResponse($oAccount, 'SyncSettings', $aResult);
	}

	/**
	 * @return array
	 */
	public function AjaxUpdateAccountSettings()
	{
		$oAccount = $this->getAccountFromParam();

		$this->populateAccountFromHttpPost(true, $oAccount);

		return $this->DefaultResponse($oAccount, 'UpdateAccountSettings', $this->oApiUsers->UpdateAccount($oAccount));
	}

	/**
	 * @return array
	 */
	public function AjaxUpdateUserSettings()
	{
		$oAccount = $this->getAccountFromParam();

		$iMailsPerPage = (int) $this->oHttp->GetPost('MailsPerPage', $oAccount->User->MailsPerPage);
		if ($iMailsPerPage < 1)
		{
			$iMailsPerPage = 1;
		}

		$iContactsPerPage = (int) $this->oHttp->GetPost('ContactsPerPage', $oAccount->User->ContactsPerPage);
		if ($iContactsPerPage < 1)
		{
			$iContactsPerPage = 1;
		}

		$iAutoCheckMailInterval = (int) $this->oHttp->GetPost('AutoCheckMailInterval', $oAccount->User->AutoCheckMailInterval);
		if (!in_array($iAutoCheckMailInterval, array(0, 1, 3, 5, 10, 15, 20, 30)))
		{
			$iAutoCheckMailInterval = 0;
		}

		$iLayout = (int) $this->oHttp->GetPost('Layout', $oAccount->User->Layout);
		$iDefaultEditor = (int) $this->oHttp->GetPost('DefaultEditor', $oAccount->User->DefaultEditor);

		$sTheme = (string) $this->oHttp->GetPost('DefaultTheme', $oAccount->User->DefaultSkin);
//		$sTheme = $this->validateTheme($sTheme);

		$sLang = (string) $this->oHttp->GetPost('DefaultLanguage', $oAccount->User->DefaultLanguage);
//		$sLang = $this->validateLang($sLang);

		$sDateFormat = (string) $this->oHttp->GetPost('DefaultDateFormat', $oAccount->User->DefaultDateFormat);
		$iTimeFormat = (int) $this->oHttp->GetPost('DefaultTimeFormat', $oAccount->User->DefaultTimeFormat);

		$oAccount->User->MailsPerPage = $iMailsPerPage;
		$oAccount->User->ContactsPerPage = $iContactsPerPage;
		$oAccount->User->Layout = $iLayout;
		$oAccount->User->DefaultSkin = $sTheme;
		$oAccount->User->DefaultEditor = $iDefaultEditor;
		$oAccount->User->DefaultLanguage = $sLang;
		$oAccount->User->DefaultDateFormat = $sDateFormat;
		$oAccount->User->DefaultTimeFormat = $iTimeFormat;
		$oAccount->User->AutoCheckMailInterval = $iAutoCheckMailInterval;

		// calendar
		$oCalUser = $this->oApiUsers->GetOrCreateCalUserByUserId($oAccount->IdUser);
		if ($oCalUser)
		{
			$oCalUser->ShowWeekEnds = (bool) $this->oHttp->GetPost('ShowWeekEnds', $oCalUser->ShowWeekEnds);
			$oCalUser->ShowWorkDay = (bool) $this->oHttp->GetPost('ShowWorkDay', $oCalUser->ShowWorkDay);
			$oCalUser->WorkDayStarts = (int) $this->oHttp->GetPost('WorkDayStarts', $oCalUser->WorkDayStarts);
			$oCalUser->WorkDayEnds = (int) $this->oHttp->GetPost('WorkDayEnds', $oCalUser->WorkDayEnds);
			$oCalUser->WeekStartsOn = (int) $this->oHttp->GetPost('WeekStartsOn', $oCalUser->WeekStartsOn);
			$oCalUser->DefaultTab = (int) $this->oHttp->GetPost('DefaultTab', $oCalUser->DefaultTab);
		}

		return $this->DefaultResponse($oAccount, 'UpdateUserSettings', $this->oApiUsers->UpdateAccount($oAccount) &&
			$oCalUser && $this->oApiUsers->UpdateCalUser($oCalUser));
	}

	/**
	 * @return array
	 */
	public function AjaxLogin()
	{
		$sEmail = trim((string) $this->getParamValue('Email', ''));
		$sIncLogin = (string) $this->getParamValue('IncLogin', '');
		$sIncPassword = (string) $this->getParamValue('IncPassword', '');
		$sLanguage = (string) $this->getParamValue('Language', '');

		$bSignMe = '1' === (string) $this->getParamValue('SignMe', '0');

		// TODO
		// $sCaptcha = (string) $this->getParamValue('Captcha', '');

		if (0 === strlen($sIncPassword) || 0 === strlen($sEmail.$sIncLogin))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		try
		{
			if (0 === strlen($sLanguage))
			{
				$sLanguage = $this->oApiIntegrator->GetLoginLanguage();
			}

			$oAccount = $this->oApiIntegrator->LoginToAccount(
				$sEmail, $sIncPassword, $sIncLogin, $sLanguage
			);
		}
		catch (\Exception $oException)
		{
			$iErrorCode = \ProjectSeven\Notifications::UnknownError;
			if ($oException instanceof \CApiManagerException)
			{
				switch ($oException->getCode())
				{
					case \Errs::WebMailManager_AccountDisabled:
					case \Errs::WebMailManager_AccountWebmailDisabled:
						$iErrorCode = \ProjectSeven\Notifications::AuthError;
						break;
					case \Errs::UserManager_AccountAuthenticationFailed:
					case \Errs::WebMailManager_AccountAuthentication:
					case \Errs::WebMailManager_NewUserRegistrationDisabled:
					case \Errs::WebMailManager_AccountCreateOnLogin:
						$iErrorCode = \ProjectSeven\Notifications::AuthError;
						break;
					case \Errs::UserManager_AccountConnectToMailServerFailed:
					case \Errs::WebMailManager_AccountConnectToMailServerFailed:
						$iErrorCode = \ProjectSeven\Notifications::MailServerError;
						break;
					case \Errs::UserManager_LicenseKeyInvalid:
					case \Errs::UserManager_AccountCreateUserLimitReached:
					case \Errs::UserManager_LicenseKeyIsOutdated:
					case \Errs::TenantsManager_AccountCreateUserLimitReached:
						$iErrorCode = \ProjectSeven\Notifications::LicenseProblem;
						break;
					case \Errs::Db_ExceptionError:
						$iErrorCode = \ProjectSeven\Notifications::DataBaseError;
						break;
				}
			}

			throw new \ProjectSeven\Exceptions\ClientException($iErrorCode);
		}

		if ($oAccount instanceof \CAccount)
		{
			$this->oApiIntegrator->SetAccountAsLoggedIn($oAccount, $bSignMe);
			return $this->TrueResponse($oAccount, 'Login');
		}

		return $this->FalseResponse(null, 'Login');
	}

	/**
	 * @return array
	 */
	public function AjaxContact()
	{
		$oContact = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$sContactId = (string) $this->getParamValue('ContactId', '');

			$oContact = $oApiContacts->GetContactById($oAccount->IdUser, $sContactId);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact);
	}
	
	/**
	 * @return array
	 */
	public function AjaxContactByEmail()
	{
		$oContact = false;
		$oAccount = $this->getAccountFromParam();
		
		$sEmail = (string) $this->getParamValue('Email', '');

		if ($oAccount->User->AllowContacts)
		{
			if ($oAccount->User->GetCapa('PAB'))
			{
				$oApiContacts = $this->ApiContacts();
				if ($oApiContacts)
				{
					$oContact = $oApiContacts->GetContactByEmail($oAccount->IdUser, $sEmail);
				}
			}

			if (!$oContact && $oAccount->User->GetCapa('GAB'))
			{
				$oApiGContacts = $this->ApiGContacts();
				if ($oApiGContacts)
				{
					$oContact = $oApiGContacts->GetContactByEmail($oAccount, $sEmail);
				}
			}
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact);
	}

	/**
	 * @return array
	 */
	public function AjaxAddContactsToGroup()
	{
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$bGlobal = '1' === (string) $this->getParamValue('Global', '0');
			$sGroupId = (string) $this->getParamValue('GroupId', '');

			$aContactsId = explode(',', $this->getParamValue('ContactsId', ''));
			$aContactsId = array_map('trim', $aContactsId);

			$oGroup = $oApiContacts->GetGroupById($oAccount->IdUser, $sGroupId);
			if ($oGroup)
			{
				if ($bGlobal)
				{
					return $this->DefaultResponse($oAccount, __FUNCTION__,
						$oApiContacts->AddGlobalContactsToGroup($oAccount, $oGroup, $aContactsId));
				}
				else
				{
					return $this->DefaultResponse($oAccount, __FUNCTION__,
						$oApiContacts->AddContactsToGroup($oGroup, $aContactsId));
				}
			}

			return $this->DefaultResponse($oAccount, __FUNCTION__, false);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, false);
	}

	/**
	 * @return array
	 */
	public function AjaxRemoveContactsFromGroup()
	{
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$sGroupId = (string) $this->getParamValue('GroupId', '');

			$aContactsId = explode(',', $this->getParamValue('ContactsId', ''));
			$aContactsId = array_map('trim', $aContactsId);

			$oGroup = $oApiContacts->GetGroupById($oAccount->IdUser, $sGroupId);
			if ($oGroup)
			{
				return $this->DefaultResponse($oAccount, __FUNCTION__,
					$oApiContacts->RemoveContactsFromGroup($oGroup, $aContactsId));
			}

			return $this->DefaultResponse($oAccount, __FUNCTION__, false);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, false);
	}

	/**
	 * @return array
	 */
	public function AjaxDoServerInitializations()
	{
		$oAccount = $this->getAccountFromParam();

		$bResult = false;

		$oApiIntegrator = \CApi::Manager('integrator');
		if ($oAccount && $oApiIntegrator)
		{
			$oApiIntegrator->ResetCookies();
		}

		$oApiContacts = $this->ApiContacts();
		if ($oApiContacts && method_exists($oApiContacts, 'SynchronizeExternalContacts'))
		{
			$bResult = $oApiContacts->SynchronizeExternalContacts($oAccount);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $bResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxProcessAppointment()
	{
		$oAccount = $this->getAccountFromParam();
		
		$mResult = false;

		$sCalendarId = (string) $this->getParamValue('CalendarId', '');
		$sTempFile = (string) $this->getParamValue('File', '');
		$sAction = (string) $this->getParamValue('AppointmentAction', '');
		
		if (empty($sTempFile) || empty($sAction) || empty($sCalendarId))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oApiCollaborationManager = /* @var $oApiCollaborationManager CApiCollaborationManager */ \CApi::Manager('collaboration');
		$bProcessAppointment = $oApiCollaborationManager && $oApiCollaborationManager->IsCalendarAppointmentsSupported();
		if ($bProcessAppointment)
		{
			$bProcessAppointment = $oAccount->User->GetCapa('MEETINGS');
		}

		if ($bProcessAppointment)
		{
			$oApiFileCache = /* @var $oApiFileCache CApiFilecacheManager */ \CApi::Manager('filecache');
			$sData = $oApiFileCache->Get($oAccount, $sTempFile);
			if (!empty($sData))
			{
				$oCalendarApi = \CApi::Manager('calendar');
				if ($oCalendarApi)
				{
					$mProcessResult = $oCalendarApi->ProcessICS($oAccount, $sAction, $sCalendarId, $sData);
					if ($mProcessResult)
					{
						$mResult = array(
							'Uid' => $mProcessResult
						);
					}
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}
	
	/**
	 * @return array
	 */
	public function AjaxSaveIcs()
	{
		$oAccount = $this->getAccountFromParam();

		$mResult = false;

		$sCalendarId = (string) $this->getParamValue('CalendarId', '');
		$sTempFile = (string) $this->getParamValue('File', '');

		if (empty($sCalendarId) || empty($sTempFile))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oApiFileCache = /* @var $oApiFileCache CApiFilecacheManager */ \CApi::Manager('filecache');
		$sData = $oApiFileCache->Get($oAccount, $sTempFile);
		if (!empty($sData))
		{
			$oCalendarApi = \CApi::Manager('calendar');
			if ($oCalendarApi)
			{
				$mCreateEventResult = $oCalendarApi->CreateEventData($oAccount, $sCalendarId, null, $sData);
				if ($mCreateEventResult)
				{
					$mResult = array(
						'Uid' => (string) $mCreateEventResult
					);
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxSaveVcf()
	{
		$oAccount = $this->getAccountFromParam();

		$mResult = false;

		$sTempFile = (string) $this->getParamValue('File', '');
		if (empty($sTempFile))
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
		}

		$oApiFileCache = /* @var $oApiFileCache CApiFilecacheManager */ \CApi::Manager('filecache');
		$sData = $oApiFileCache->Get($oAccount, $sTempFile);
		if (!empty($sData))
		{
			$oContactsApi = $this->ApiContacts();
			if ($oContactsApi)
			{
				$oContact = new \CContact();
				$oContact->InitFromVCardStr($oAccount->IdUser, $sData);
				
				if ($oContactsApi->CreateContact($oContact))
				{
					$mResult = array(
						'Uid' => $oContact->IdContact
					);
				}
			}
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxGlobalContact()
	{
		$oContact = false;
		$oAccount = $this->getAccountFromParam();

		$oApiCollaborationManager = /* @var $oApiCollaborationManager CApiCollaborationManager */ \CApi::Manager('collaboration');

		$oSettings =& \CApi::GetSettings();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('GAB') &&
			$oSettings->GetConf('Contacts/ShowGlobalContactsInAddressBook') &&
			$oApiCollaborationManager && $oApiCollaborationManager->IsContactsGlobalSupported())
		{
			$oApiGContacts = $this->ApiGContacts();

			$sContactId = (string) $this->getParamValue('ContactId', '');

			$oContact = $oApiGContacts->GetContactById($oAccount, $sContactId);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact);
	}

	/**
	 * @return array
	 */
	public function AjaxGroup()
	{
		$oGroup = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$sGroupId = (string) $this->getParamValue('GroupId', '');

			$oGroup = $oApiContacts->GetGroupById($oAccount->IdUser, $sGroupId);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $oGroup);
	}

	/**
	 * @return array
	 */
	public function AjaxGroupFullList()
	{
		$oAccount = $this->getAccountFromParam();

		$aList = false;
		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$aList = $oApiContacts->GetGroupItems($oAccount->IdUser,
				\EContactSortField::Name, \ESortOrder::ASC, 0, 999);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, $aList);
	}

	private function populateSortParams(&$sSortField, &$iSortOrder)
	{
		$sSortField = (string) $this->getParamValue('SortField', 'Email');
		$iSortOrder = '1' === (string) $this->getParamValue('SortOrder', '0') ?
			\ESortOrder::ASC : \ESortOrder::DESC;

		$iSortField = \EContactSortField::EMail;
		switch (strtolower($sSortField))
		{
			case 'email':
				$iSortField = \EContactSortField::EMail;
				break;
			case 'name':
				$iSortField = \EContactSortField::Name;
				break;
			case 'frequency':
				$iSortField = \EContactSortField::Frequency;
				break;
		}
	}

	/**
	 * @return array
	 */
	public function AjaxContactSuggestions()
	{
		$oAccount = $this->getAccountFromParam();
		$oApiContacts = $this->ApiContacts();

		$sSearch = (string) $this->getParamValue('Search', '');

		$aList = array();
		
		$aContacts = $oApiContacts ? $oApiContacts->GetSuggestItems($oAccount, $sSearch, \CApi::GetConf('webmail.suggest-contacts-limit', 20)) : null;

		if (is_array($aContacts))
		{
			$aList = $aContacts;
		}

		$aCounts = array(0, 0);
		
		\CApi::Plugin()->RunHook('webmail.change-suggest-list', array($oAccount, $sSearch, &$aList, &$aCounts));

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'Search' => $sSearch,
			'List' => $aList
		));
	}

	/**
	 * @return array
	 */
	public function AjaxContactList()
	{
		$oAccount = $this->getAccountFromParam();
		$oApiContacts = $this->ApiContacts();

		$iOffset = (int) $this->getParamValue('Offset', 0);
		$iLimit = (int) $this->getParamValue('Limit', 20);
		$sGroupId = (string) $this->getParamValue('GroupId', '');
		$sSearch = (string) $this->getParamValue('Search', '');
		$sFirstCharacter = (string) $this->getParamValue('FirstCharacter', '');

		$iSortField = \EContactSortField::EMail;
		$iSortOrder = \ESortOrder::ASC;
		$this->populateSortParams($iSortField, $iSortOrder);

		$iCount = 0;
		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			if ('' === $sGroupId)
			{
				$iCount = $oApiContacts->GetContactItemsCount($oAccount->IdUser, $sSearch, $sFirstCharacter);
			}
			else
			{
				$iCount = $oApiContacts->GetContactItemsCount($oAccount->IdUser, $sSearch, $sFirstCharacter, $sGroupId);
			}

			$aList = array();
			if (0 < $iCount)
			{
				$aContacts = $oApiContacts->GetContactItems(
					$oAccount->IdUser, $iSortField, $iSortOrder, $iOffset,
					$iLimit, $sSearch, $sFirstCharacter, $sGroupId);

				if (is_array($aContacts))
				{
					$aList = $aContacts;
				}
			}
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'ContactCount' => $iCount,
			'GroupId' => $sGroupId,
			'Search' => $sSearch,
			'FirstCharacter' => $sFirstCharacter,
			'List' => $aList
		));
	}

	/**
	 * @return array
	 */
	public function AjaxGlobalContactList()
	{
		$oAccount = $this->getAccountFromParam();
		$oApiGContacts = $this->ApiGContacts();

		$oApiCollaborationManager = /* @var $oApiCollaborationManager CApiCollaborationManager */ \CApi::Manager('collaboration');

		$oSettings =& \CApi::GetSettings();

		$iOffset = (int) $this->getParamValue('Offset', 0);
		$iLimit = (int) $this->getParamValue('Limit', 20);
		$sSearch = (string) $this->getParamValue('Search', '');

		$iSortField = \EContactSortField::EMail;
		$iSortOrder = \ESortOrder::ASC;

		$this->populateSortParams($iSortField, $iSortOrder);

		$iCount = 0;
		$aList = array();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('GAB') &&
			$oSettings->GetConf('Contacts/ShowGlobalContactsInAddressBook') &&
			$oApiCollaborationManager && $oApiCollaborationManager->IsContactsGlobalSupported())
		{
			$iCount = $oApiGContacts->GetContactItemsCount($oAccount, $sSearch);

			if (0 < $iCount)
			{
				$aContacts = $oApiGContacts->GetContactItems(
					$oAccount, $iSortField, $iSortOrder, $iOffset,
					$iLimit, $sSearch);

				if (is_array($aContacts))
				{
					$aList = $aContacts;
				}
			}
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->DefaultResponse($oAccount, __FUNCTION__, array(
			'ContactCount' => $iCount,
			'Search' => $sSearch,
			'List' => $aList
		));
	}

	/**
	 * @return array
	 */
	public function AjaxGetAutoresponder()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();
		
		if ($oAccount && $oAccount->IsEnabledExtension(\CAccount::AutoresponderExtension))
		{
			$aAutoResponderValue = $this->ApiSieve()->GetAutoresponder($oAccount);
			if (isset($aAutoResponderValue['subject'], $aAutoResponderValue['body'], $aAutoResponderValue['enabled']))
			{
				$mResult = array(
					'Enable' => (bool) $aAutoResponderValue['enabled'],
					'Subject' => (string) $aAutoResponderValue['subject'],
					'Message' => (string) $aAutoResponderValue['body']
				);
			}
		}

		return $this->DefaultResponse($oAccount, 'GetAutoresponder', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxUpdateAutoresponder()
	{
		$bIsDemo = false;
		$mResult = false;
		$oAccount = $this->getAccountFromParam();
		if ($oAccount && $oAccount->IsEnabledExtension(\CAccount::AutoresponderExtension))
		{
			\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
			if (!$bIsDemo)
			{
				$bIsEnabled = '1' === $this->getParamValue('Enable', '0');
				$sSubject = (string) $this->getParamValue('Subject', '');
				$sMessage = (string) $this->getParamValue('Message', '');

				$mResult = $this->ApiSieve()->SetAutoresponder($oAccount, $sSubject, $sMessage, $bIsEnabled);
			}
			else
			{
				throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::DemoAccount);
			}
		}

		return $this->DefaultResponse($oAccount, 'UpdateAutoresponder', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxGetForward()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();
		
		if ($oAccount && $oAccount->IsEnabledExtension(\CAccount::ForwardExtension))
		{
			$aForwardValue = /* @var $aForwardValue array */  $this->ApiSieve()->GetForward($oAccount);
			if (isset($aForwardValue['email'], $aForwardValue['enabled']))
			{
				$mResult = array(
					'Enable' => (bool) $aForwardValue['enabled'],
					'Email' => (string) $aForwardValue['email']
				);
			}
		}

		return $this->DefaultResponse($oAccount, 'GetForward', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxUpdateForward()
	{
		$mResult = false;
		$bIsDemo = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount && $oAccount->IsEnabledExtension(\CAccount::ForwardExtension))
		{
			\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));
			if (!$bIsDemo)
			{
				$bIsEnabled = '1' === $this->getParamValue('Enable', '0');
				$sForwardEmail = (string) $this->getParamValue('Email', '');
		
				$mResult = $this->ApiSieve()->SetForward($oAccount, $sForwardEmail, $bIsEnabled);
			}
			else
			{
				throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::DemoAccount);
			}
		}

		return $this->DefaultResponse($oAccount, 'UpdateForward', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxGetSieveFilters()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount && $oAccount->IsEnabledExtension(\CAccount::SieveFiltersExtension))
		{
			$mResult = $this->ApiSieve()->GetSieveFilters($oAccount);
		}

		return $this->DefaultResponse($oAccount, 'GetSieveFilters', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxUpdateSieveFilters()
	{
		$mResult = false;
		$oAccount = $this->getAccountFromParam();

		if ($oAccount && $oAccount->IsEnabledExtension(\CAccount::SieveFiltersExtension))
		{
			$aFilters = $this->getParamValue('Filters', array());
			$aFilters = is_array($aFilters) ? $aFilters : array();

			$mResult = array();
			foreach ($aFilters as $aItem)
			{
				$oFilter = new \CFilter($oAccount);
				$oFilter->Enable = '1' === (string) (isset($aItem['Enable']) ? $aItem['Enable'] : '1');
				$oFilter->Field = (int) (isset($aItem['Field']) ? $aItem['Field'] : \EFilterFiels::From);
				$oFilter->Filter = (string) (isset($aItem['Filter']) ? $aItem['Filter'] : '');
				$oFilter->Condition = (int) (isset($aItem['Condition']) ? $aItem['Condition'] : \EFilterCondition::ContainSubstring);
				$oFilter->Action = (int) (isset($aItem['Action']) ? $aItem['Action'] : \EFilterAction::DoNothing);
				$oFilter->FolderFullName = (string) (isset($aItem['FolderFullName']) ? $aItem['FolderFullName'] : '');

				$mResult[] = $oFilter;
			}

			$mResult = $this->ApiSieve()->UpdateSieveFilters($oAccount, $mResult);
		}

		return $this->DefaultResponse($oAccount, 'UpdateSieveFilters', $mResult);
	}

	/**
	 * @param string $sParamName
	 * @param mixed $oObject
	 *
	 * @return void
	 */
	private function paramToObject($sParamName, &$oObject, $sType = 'string')
	{
		switch ($sType)
		{
			default:
			case 'string':
				$oObject->{$sParamName} = (string) $this->getParamValue($sParamName, $oObject->{$sParamName});
				break;
			case 'int':
				$oObject->{$sParamName} = (int) $this->getParamValue($sParamName, $oObject->{$sParamName});
				break;
			case 'bool':
				$oObject->{$sParamName} = '1' === (string) $this->getParamValue($sParamName, $oObject->{$sParamName} ? '1' : '0');
				break;
		}
	}

	/**
	 * @param mixed $oObject
	 * @param array $aParamsNames
	 */
	private function paramsStrToObjectHelper(&$oObject, $aParamsNames)
	{
		foreach ($aParamsNames as $sName)
		{
			$this->paramToObject($sName, $oObject);
		}
	}

	/**
	 * @param \CContact $oContact
	 */
	private function populateContactObject(&$oContact)
	{
		$iPrimaryEmail = $oContact->PrimaryEmail;
		switch (strtolower($this->getParamValue('PrimaryEmail', '')))
		{
			case 'home':
			case 'personal':
				$iPrimaryEmail = \EPrimaryEmailType::Home;
				break;
			case 'business':
				$iPrimaryEmail = \EPrimaryEmailType::Business;
				break;
			case 'other':
				$iPrimaryEmail = \EPrimaryEmailType::Other;
				break;
		}

		$oContact->PrimaryEmail = $iPrimaryEmail;

		$this->paramToObject('UseFriendlyName', $oContact, 'bool');

		$this->paramsStrToObjectHelper($oContact, array(
			'Title', 'FullName', 'FirstName', 'LastName', 'NickName',

			'HomeEmail', 'HomeStreet', 'HomeCity', 'HomeState', 'HomeZip',
			'HomeCountry', 'HomeFax', 'HomePhone', 'HomeMobile', 'HomeWeb',

			'BusinessEmail', 'BusinessCompany', 'BusinessJobTitle', 'BusinessDepartment',
			'BusinessOffice', 'BusinessStreet', 'BusinessCity', 'BusinessState',  'BusinessZip',
			'BusinessCountry', 'BusinessFax','BusinessPhone', 'BusinessMobile',  'BusinessWeb',

			'OtherEmail', 'Notes', 'ETag'
		));

		$this->paramToObject('BirthdayDay', $oContact, 'int');
		$this->paramToObject('BirthdayMonth', $oContact, 'int');
		$this->paramToObject('BirthdayYear', $oContact, 'int');

		$aGroupsIds = $this->getParamValue('GroupsIds');
		$oContact->GroupsIds = is_array($aGroupsIds) ? array_unique($aGroupsIds) : array();
	}

	/**
	 * @param \CGroup $oGroup
	 */
	private function populateGroupObject(&$oGroup)
	{
		$this->paramsStrToObjectHelper($oGroup, array(
			'Name'
		));
	}

	/**
	 * @return array
	 */
	public function AjaxContactCreate()
	{
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$oContact = new \CContact();
			$oContact->IdUser = $oAccount->IdUser;

			$this->populateContactObject($oContact);

			$oApiContacts->CreateContact($oContact);
			return $this->DefaultResponse($oAccount, __FUNCTION__, $oContact ? array(
				'IdContact' => $oContact->IdContact
			) : false);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxGroupCreate()
	{
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$oGroup = new \CGroup();
			$oGroup->IdUser = $oAccount->IdUser;

			$this->populateGroupObject($oGroup);

			$oApiContacts->CreateGroup($oGroup);
			return $this->DefaultResponse($oAccount, __FUNCTION__, $oGroup ? array(
				'IdGroup' => $oGroup->IdGroup
			) : false);
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxContactDelete()
	{
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$aContactsId = explode(',', $this->getParamValue('ContactsId', ''));
			$aContactsId = array_map('trim', $aContactsId);

			return $this->DefaultResponse($oAccount, __FUNCTION__,
				$oApiContacts->DeleteContacts($oAccount->IdUser, $aContactsId));
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxGroupDelete()
	{
		$oAccount = $this->getAccountFromParam();

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oApiContacts = $this->ApiContacts();

			$sGroupId = $this->getParamValue('GroupId', '');

			return $this->DefaultResponse($oAccount, __FUNCTION__,
				$oApiContacts->DeleteGroup($oAccount->IdUser, $sGroupId));
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxContactUpdate()
	{
		$oAccount = $this->getAccountFromParam();
		$oApiContacts = $this->ApiContacts();

		$sContactId = $this->getParamValue('ContactId', '');

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oContact = $oApiContacts->GetContactById($oAccount->IdUser, $sContactId);
			if ($oContact)
			{
				$this->populateContactObject($oContact);

				if ($oApiContacts->UpdateContact($oContact))
				{
					return $this->TrueResponse($oAccount, __FUNCTION__);
				}
				else
				{
					switch ($oApiContacts->GetLastErrorCode())
					{
						case \Errs::Sabre_PreconditionFailed:
							throw new \ProjectSeven\Exceptions\ClientException(
								\ProjectSeven\Notifications::ContactDataHasBeenModifiedByAnotherApplication);
					}
				}
			}
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxGroupUpdate()
	{
		$oAccount = $this->getAccountFromParam();
		$oApiContacts = $this->ApiContacts();

		$sGroupId = $this->getParamValue('GroupId', '');

		if ($oAccount->User->AllowContacts && $oAccount->User->GetCapa('PAB'))
		{
			$oGroup = $oApiContacts->GetGroupById($oAccount->IdUser, $sGroupId);
			if ($oGroup)
			{
				$this->populateGroupObject($oGroup);

				if ($oApiContacts->UpdateGroup($oGroup))
				{
					return $this->TrueResponse($oAccount, __FUNCTION__);
				}
				else
				{
					switch ($oApiContacts->GetLastErrorCode())
					{
						case \Errs::Sabre_PreconditionFailed:
							throw new \ProjectSeven\Exceptions\ClientException(
								\ProjectSeven\Notifications::ContactDataHasBeenModifiedByAnotherApplication);
					}
				}
			}
		}
		else
		{
			throw new \ProjectSeven\Exceptions\ClientException(
				\ProjectSeven\Notifications::ContactsNotAllowed);
		}

		return $this->FalseResponse($oAccount, __FUNCTION__);
	}

	/**
	 * @return array
	 */
	public function AjaxCalendarList()
	{
		$oAccount = $this->getAccountFromParam();

		$mResult = false;
		$oApiCalendarManager = \CApi::Manager('calendar');
		if ($oApiCalendarManager)
		{
			$mResult = $oApiCalendarManager->GetCalendars($oAccount);
		}

		return $this->DefaultResponse($oAccount, 'CalendarList', $mResult);
	}

	/**
	 * @return array
	 */
	public function AjaxLogout()
	{
		$oAccount = $this->getAccountFromParam(false);
		return $this->DefaultResponse($oAccount, 'Logout', $this->oApiIntegrator->LogoutAccount());
	}

	/**
	 * @param string $iError
	 *
	 * @return string
	 */
	public function convertUploadErrorToString($iError)
	{
		$sError = 'unknown';
		switch ($iError)
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$sError = 'size';
				break;
		}

		return $sError;
	}

	/**
	 * @return array
	 */
	public function RawContacts()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if ($oAccount)
		{
			$oApiContactsManager = $this->ApiContacts();
			if ($oApiContactsManager)
			{
				$sOutput = $oApiContactsManager->Export($oAccount->IdUser, 'csv');
				if (false !== $sOutput)
				{
					header('Pragma: public');
					header('Content-Type: text/csv');
					header('Content-Disposition: attachment; filename="export.csv";');
					header('Content-Transfer-Encoding: binary');

					echo $sOutput;
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @todo
	 * @return array
	 */
	public function RawCalendars()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		if ($oAccount)
		{
			$sRawKey = (string) $this->getParamValue('RawKey', '');
			$aValues = \CApi::DecodeKeyValues($sRawKey);

			if (isset($aValues['CalendarId']))
			{
				$sCalendarId = $aValues['CalendarId'];

				$oApiCalendarManager = /* @var $oApiCalendarManager CApiCalendarManager */ \CApi::Manager('calendar');
				$sOutput = $oApiCalendarManager->ExportCalendarToIcs($oAccount, $sCalendarId);
				if (false !== $sOutput)
				{
					header('Pragma: public');
					header('Content-Type: text/calendar');
					header('Content-Disposition: attachment; filename="'.$sCalendarId.'.ics";');
					header('Content-Transfer-Encoding: binary');

					echo $sOutput;
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function UploadContacts()
	{
		$oAccount = $this->getDefaultAccountFromParam();
		$aFileData = $this->getParamValue('FileData', null);

		$sError = '';
		$aResponse = array(
			'ImportedCount' => 0,
			'ParsedCount' => 0
		);

		if ($oAccount)
		{
			if (is_array($aFileData))
			{
				$sSavedName = 'import-post-'.md5($aFileData['name'].$aFileData['tmp_name']);
				if ($this->ApiFileCache()->MoveUploadedFile($oAccount, $sSavedName, $aFileData['tmp_name']))
				{
					$oApiContactsManager = $this->ApiContacts();
					if ($oApiContactsManager)
					{
						$iParsedCount = 0;

						$iImportedCount = $oApiContactsManager->ImportEx(
							$oAccount->IdUser,
							'csv',
							$this->ApiFileCache()->GenerateFullFilePath($oAccount, $sSavedName),
							$iParsedCount
						);
					}

					if (false !== $iImportedCount && -1 !== $iImportedCount)
					{
						$aResponse['ImportedCount'] = $iImportedCount;
						$aResponse['ParsedCount'] = $iParsedCount;
					}
					else
					{
						$sError = 'unknown';
					}

					$this->ApiFileCache()->Clear($oAccount, $sSavedName);
				}
				else
				{
					$sError = 'unknown';
				}
			}
			else
			{
				$sError = 'unknown';
			}
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, 'UploadContacts', $aResponse);
	}

	/**
	 * @return array
	 */
	public function UploadAttachment()
	{
		$oAccount = $this->getAccountFromParam();
		$oSettings =& \CApi::GetSettings();
		$aFileData = $this->getParamValue('FileData', null);

		$iSizeLimit = !!$oSettings->GetConf('WebMail/EnableAttachmentSizeLimit', false) ?
			((int) $oSettings->GetConf('WebMail/AttachmentSizeLimit', 0)) * 1024 * 1024 : 0;

		$sError = '';
		$aResponse = array();

		if ($oAccount)
		{
			if (is_array($aFileData))
			{
				if (0 < $iSizeLimit && $iSizeLimit < (int) $aFileData['size'])
				{
					$sError = 'size';
				}
				else
				{
					$sSavedName = 'upload-post-'.md5($aFileData['name'].$aFileData['tmp_name']);
					if ($this->ApiFileCache()->MoveUploadedFile($oAccount, $sSavedName, $aFileData['tmp_name']))
					{
						$sUploadName = $aFileData['name'];
						$iSize = $aFileData['size'];
						$sMimeType = \MailSo\Base\Utils::MimeContentType($sUploadName);

						$aResponse['Attachment'] = array(
							'Name' => $sUploadName,
							'TempName' => $sSavedName,
							'MimeType' => $sMimeType,
							'Size' =>  (int) $iSize,
							'Hash' => \CApi::EncodeKeyValues(array(
								'TempFile' => true,
								'AccountID' => $oAccount->IdAccount,
								'Name' => $sUploadName,
								'TempName' => $sSavedName
							))
						);
					}
					else
					{
						$sError = 'unknown';
					}
				}
			}
			else
			{
				$sError = 'unknown';
			}
		}
		else
		{
			$sError = 'auth';
		}

		if (0 < strlen($sError))
		{
			$aResponse['Error'] = $sError;
		}

		return $this->DefaultResponse($oAccount, 'UploadAttachment', $aResponse);
	}

	/**
	 * @return array
	 */
//	public function Upload111()
//	{
//		$oAccount = $this->getAccountFromParam();
//		$oSettings =& \CApi::GetSettings();
//
//		$sInputName = 'jua-uploader';
//		$aResponse = array();
//
//		$sError = '';
//		$iSizeLimit = !!$oSettings->GetConf('WebMail/EnableAttachmentSizeLimit', false) ?
//			((int) $oSettings->GetConf('WebMail/AttachmentSizeLimit', 0)) * 1024 * 1024 : 0;
//
//		if ($oAccount)
//		{
//			$iError = UPLOAD_ERR_OK;
//			$_FILES = isset($_FILES) ? $_FILES : null;
//			if (isset($_FILES, $_FILES[$sInputName], $_FILES[$sInputName]['name'], $_FILES[$sInputName]['tmp_name'], $_FILES[$sInputName]['size'], $_FILES[$sInputName]['type']))
//			{
//				$iError = (isset($_FILES[$sInputName]['error'])) ? (int) $_FILES[$sInputName]['error'] : UPLOAD_ERR_OK;
//				if (UPLOAD_ERR_OK === $iError)
//				{
//					if (0 < $iSizeLimit && $iSizeLimit < (int) $_FILES[$sInputName]['size'])
//					{
//						$sError = 'size';
//					}
//					else
//					{
//						$sSavedName = 'upload-post-'.md5($_FILES[$sInputName]['name'].$_FILES[$sInputName]['tmp_name']);
//						if ($this->ApiFileCache()->MoveUploadedFile($oAccount, $sSavedName, $_FILES[$sInputName]['tmp_name']))
//						{
//							$sUploadName = $_FILES[$sInputName]['name'];
//							$iSize = $_FILES[$sInputName]['size'];
//							$sMimeType = \MailSo\Base\Utils::MimeContentType($sUploadName);
//
//							$aResponse['Attachment'] = array(
//								'Name' => $sUploadName,
//								'TempName' => $sSavedName,
//								'MimeType' => $sMimeType,
//								'Size' =>  (int) $iSize,
//								'Hash' => \CApi::EncodeKeyValues(array(
//									'TempFile' => true,
//									'AccountID' => $oAccount->IdAccount,
//									'Name' => $sUploadName,
//									'TempName' => $sSavedName
//								))
//							);
//						}
//						else
//						{
//							$sError = 'unknown';
//						}
//					}
//				}
//				else
//				{
//					$sError = $this->convertUploadErrorToString($iError);
//				}
//			}
//			else if (!isset($_FILES) || !is_array($_FILES) || 0 === count($_FILES))
//			{
//				$sError = 'size';
//			}
//			else
//			{
//				$sError = 'unknown';
//			}
//		}
//
//		if (0 < strlen($sError))
//		{
//			$aResponse['Error'] = $sError;
//		}
//
//		return $this->DefaultResponse($oAccount, 'Upload', $aResponse);
//	}

	/**
	 * @param bool $bDownload
	 * @param string $sContentType
	 * @param string $sFileName
	 *
	 * @return bool
	 */
	public function RawOutputHeaders($bDownload, $sContentType, $sFileName)
	{
		if ($bDownload)
		{
			header('Content-Type: '.$sContentType, true);
		}
		else
		{
			$aParts = explode('/', $sContentType, 2);
			if (in_array(strtolower($aParts[0]), array('image', 'video', 'audio')))
			{
				header('Content-Type: '.$sContentType, true);
			}
			else
			{
				header('Content-Type: text/plain', true);
			}
		}

		header('Content-Disposition: '.($bDownload ? 'attachment' : 'inline' ).'; filename="'.$sFileName.'"; charset=utf-8', true);
		header('Accept-Ranges: none', true);
		header('Content-Transfer-Encoding: binary');
	}

	/**
	 * @return bool
	 */
	private function raw($bDownload = true)
	{
		$oAccount = $this->getAccountFromParam();

		$sRawKey = (string) $this->getParamValue('RawKey', '');
		$aValues = \CApi::DecodeKeyValues($sRawKey);

		$sFolder = '';
		$iUid = 0;
		$sMimeIndex = '';

		if (!$oAccount || $aValues['AccountID'] !== $oAccount->IdAccount)
		{
			return false;
		}

		if (isset($aValues['TempFile'], $aValues['TempName'], $aValues['AccountID'], $aValues['Name']))
		{
			if (!$bDownload)
			{
				$this->verifyCacheByKey($sRawKey);
			}

			$bResult = false;
			$mResult = $this->ApiFileCache()->GetFile($oAccount, $aValues['TempName']);
			if (is_resource($mResult))
			{
				if (!$bDownload)
				{
					$this->cacheByKey($sRawKey);
				}

				$bResult = true;
				$sFileName = $aValues['Name'];
				$sContentType = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
				$sFileName = $this->clearFileName($sFileName, $sContentType);
				$this->RawOutputHeaders($bDownload, $sContentType, $sFileName);

				\MailSo\Base\Utils::FpassthruWithTimeLimitReset($mResult);
			}

			return $bResult;
		}
		else
		{
			$sFolder = isset($aValues['Folder']) ? $aValues['Folder'] : '';
			$iUid = (int) (isset($aValues['Uid']) ? $aValues['Uid'] : 0);
			$sMimeIndex = (string) (isset($aValues['MimeIndex']) ? $aValues['MimeIndex'] : '');
		}

		if (!$bDownload && 0 < strlen($sFolder) && 0 < $iUid)
		{
			$this->verifyCacheByKey($sRawKey);
		}

		$sContentTypeIn = (string) isset($aValues['MimeType']) ? $aValues['MimeType'] : '';
		$sFileNameIn = (string) isset($aValues['FileName']) ? $aValues['FileName'] : '';

		$self = $this;
		return $this->oApiMail->MessageMimeStream($oAccount,
			function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($self, $sRawKey, $bDownload, $sContentTypeIn, $sFileNameIn) {
				if (is_resource($rResource))
				{
					$sContentTypeOut = $sContentTypeIn;
					if (empty($sContentTypeOut))
					{
						$sContentTypeOut = $sContentType;
						if (empty($sContentTypeOut))
						{
							$sContentTypeOut = (empty($sFileName)) ? 'text/plain' : \MailSo\Base\Utils::MimeContentType($sFileName);
						}
					}					

					$sFileNameOut = $sFileNameIn;
					if (empty($sFileNameOut))
					{
						$sFileNameOut = $sFileName;
					}

					$sFileNameOut = $self->clearFileName($sFileNameOut, $sContentType, $sMimeIndex);

					$self->RawOutputHeaders($bDownload, $sContentTypeOut, $sFileNameOut);

					if (!$bDownload)
					{
						$self->cacheByKey($sRawKey);
					}

					\MailSo\Base\Utils::FpassthruWithTimeLimitReset($rResource);
				}
			}, $sFolder, $iUid, $sMimeIndex);
	}

	/**
	 * @return bool
	 */
	public function RawView()
	{
		return $this->raw(false);
	}

	/**
	 * @return bool
	 */
	public function RawDownload()
	{
		return $this->raw(true);
	}

	/**
	 * @param string $sFileName
	 * @param string $sContentType
	 * @param string $sMimeIndex = ''
	 *
	 * @return string
	 */
	public function clearFileName($sFileName, $sContentType, $sMimeIndex = '')
	{
		$sFileName = 0 === strlen($sFileName) ? preg_replace('/[^a-zA-Z0-9]/', '.', (empty($sMimeIndex) ? '' : $sMimeIndex.'.').$sContentType) : $sFileName;
		$sClearedFileName = preg_replace('/[\s]+/', ' ', preg_replace('/[\.]+/', '.', $sFileName));
		$sExt = \MailSo\Base\Utils::GetFileExtension($sClearedFileName);

		$iSize = 50;
		if ($iSize < strlen($sClearedFileName) - strlen($sExt))
		{
			$sClearedFileName = substr($sClearedFileName, 0, $iSize).(empty($sExt) ? '' : '.'.$sExt);
		}

		return \MailSo\Base\Utils::ClearFileName(\MailSo\Base\Utils::Utf8Clear($sClearedFileName));
	}

	/**
	 * @param string $sKey
	 *
	 * @return void
	 */
	public function cacheByKey($sKey)
	{
		if (!empty($sKey))
		{
			$iUtcTimeStamp = time();
			$iExpireTime = 3600 * 24 * 5;

			header('Cache-Control: private', true);
			header('Pragma: private', true);
			header('Etag: '.md5('Etag:'.md5($sKey)), true);
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $iUtcTimeStamp - $iExpireTime).' UTC', true);
			header('Expires: '.gmdate('D, j M Y H:i:s', $iUtcTimeStamp + $iExpireTime).' UTC', true);
		}
	}

	/**
	 * @param string $sKey
	 *
	 * @return void
	 */
	public function verifyCacheByKey($sKey)
	{
		if (!empty($sKey))
		{
			$sIfModifiedSince = $this->oHttp->GetHeader('If-Modified-Since', '');
			if (!empty($sIfModifiedSince))
			{
				$this->oHttp->StatusHeader(304);
				$this->cacheByKey($sKey);
				exit();
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array|null
	 */
	private function mobileSyncSettings($oAccount)
	{
		$mResult = null;
		if ($oAccount)
		{
			$oApiDavManager = \CApi::Manager('dav');

			$oApiCalendarManager = \CApi::Manager('calendar');

			$oSettings =& \CApi::GetSettings();

			$bEnableActiveSync = $oSettings->GetConf('WebMail/ActiveSync', false);
			$bEnableMobileSync = $oApiDavManager->IsMobileSyncEnabled();

			$mResult = array();

			$mResult['EnableDav'] = $bEnableMobileSync;
			$mResult['EnableActiveSync'] = $bEnableActiveSync;

			$sDavLogin = $oApiDavManager->GetLogin($oAccount);
			$sDavServer = $oApiDavManager->GetServerUrl();

			$mResult['Dav'] = null;
			$mResult['ActiveSync'] = null;
			$mResult['DavError'] = '';

			$oException = $oApiDavManager->GetLastException();
			if (!$oException)
			{
				if ($bEnableMobileSync)
				{
					$mResult['Dav'] = array();
					$mResult['Dav']['Login'] = $sDavLogin;
					$mResult['Dav']['Server'] = $sDavServer;
					$mResult['Dav']['PrincipalUrl'] = '';

					$sPrincipalUrl = $oApiDavManager->GetPrincipalUrl($oAccount);
					if ($sPrincipalUrl)
					{
						$mResult['Dav']['PrincipalUrl'] = $sPrincipalUrl;
					}

					$mResult['Dav']['Calendars'] = array();

					$aCalendars = $oApiCalendarManager ? $oApiCalendarManager->GetCalendars($oAccount) : null;
					if (isset($aCalendars['user']) && is_array($aCalendars['user']))
					{
						foreach($aCalendars['user'] as $aCalendar)
						{
							if (isset($aCalendar['name']) && isset($aCalendar['url']))
							{
								$mResult['Dav']['Calendars'][] = array(
									'Name' => $aCalendar['name'],
									'Url' => $sDavServer.$aCalendar['url']
								);
							}
						}
					}

					$mResult['Dav']['PersonalContactsUrl'] = $sDavServer.'/addressbooks/'.$sDavLogin.'/Default';
					$mResult['Dav']['CollectedAddressesUrl'] = $sDavServer.'/addressbooks/'.$sDavLogin.'/Collected';
					$mResult['Dav']['GlobalAddressBookUrl'] = $sDavServer.'/public/globals';
				}

				if ($bEnableActiveSync)
				{
					$mResult['ActiveSync'] = array();
					$mResult['ActiveSync']['Login'] = $sDavLogin;
					$mResult['ActiveSync']['Server'] = $oSettings->GetConf('WebMail/ExternalHostNameOfActiveSyncServer');
				}
			}
			else
			{
				$mResult['DavError'] = $oException->getMessage();
			}
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array|null
	 */
	private function outlookSyncSettings($oAccount)
	{
		/* @var $oApiCollaborationManager \CApiCollaborationManager */
		$oApiCollaborationManager = \CApi::Manager('collaboration');

		$mResult = null;
		if ($oAccount && $oApiCollaborationManager && $oAccount->User->GetCapa('OUTLOOK_SYNC'))
		{
			/* @var $oApiDavManager \CApiDavManager */
			$oApiDavManager = \CApi::Manager('dav');

			$sLogin = $oApiDavManager->GetLogin($oAccount);
			$sServerUrl = $oApiDavManager->GetServerUrl();

			$mResult = array();
			$mResult['Login'] = '';
			$mResult['Server'] = '';
			$mResult['DavError'] = '';

			$oException = $oApiDavManager->GetLastException();
			if (!$oException)
			{
				$mResult['Login'] = $sLogin;
				$mResult['Server'] = $sServerUrl;
			}
			else
			{
				$mResult['DavError'] = $oException->getMessage();
			}
		}

		return $mResult;
	}
}
