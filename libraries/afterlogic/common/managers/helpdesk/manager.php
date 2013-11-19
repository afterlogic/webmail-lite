<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Helpdesk
 */
class CApiHelpdeskManager extends AApiManagerWithStorage
{
	/**
	 * @var CApiMailManager
	 */
	private $oApiMail;

	/**
	 * @var CApiUsersManager
	 */
	private $oApiUsers;

	/**
	 * @var CApiTenantsManager
	 */
	private $oApiTenants;
	
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('helpdesk', $oManager, $sForcedStorage);

		$this->inc('classes.enum');
		$this->inc('classes.user');
		$this->inc('classes.attachment');
		$this->inc('classes.post');
		$this->inc('classes.thread');

		$this->oApiMail = null;
		$this->oApiUsers = null;
		$this->oApiTenants = null;
	}

	/**
	 * @return CApiUsersManager
	 */
	private function apiUsers()
	{
		if (null === $this->oApiUsers)
		{
			$this->oApiUsers = CApi::Manager('users');
		}

		return $this->oApiUsers;
	}

	/**
	 * @return CApiMailManager
	 */
	private function apiMail()
	{
		if (null === $this->oApiMail)
		{
			$this->oApiMail = CApi::Manager('mail');
		}
		
		return $this->oApiMail;
	}

	/**
	 * @param string $sPath
	 * @param string $sSubject
	 */
	private function getMessageTemplate($sPath, &$sSubject, $fCallback)
	{
		$sData = @file_get_contents($sPath);
		if (is_string($sData) && 0 < strlen($sData))
		{
			$aMatch = array();
			$sData = trim($sData);

			if ($fCallback)
			{
				$sData = call_user_func($fCallback, $sData);
			}
			
			if (preg_match('/^:SUBJECT:([^\n]+)/', $sData, $aMatch) && !empty($aMatch[1]))
			{
				$sSubject = trim($aMatch[1]);
				$sData = trim(preg_replace('/^:SUBJECT:[^\n]+/', '', $sData));
			}

			return $sData;
		}

		return '';
	}

	private function addHtmlBodyAndSubjectForUserMessage($sPath, &$oMessage, $oHelpdeskUser, $sSiteName)
	{
		$sSubject = '';
		$sData = $this->getMessageTemplate($sPath, $sSubject, function ($sData) use ($oHelpdeskUser, $sSiteName) {
			return strtr($sData, array(
				'{{HELPDESK_SITE_NAME}}' => 0 === strlen($sSiteName) ? 'Helpdesk' : $sSiteName,
				'{{USER_EMAIL}}' => $oHelpdeskUser->Email,
				'{{FORGOT_LINK}}' => $oHelpdeskUser->ForgotLink(),
				'{{ACTIVATION_LINK}}' => $oHelpdeskUser->ActivationLink()
			));
		});
		
		if (0 < strlen($sSubject))
		{
			$oMessage->SetSubject($sSubject);
		}

		if (is_string($sData) && 0 < strlen($sData))
		{
			$oMessage->AddText(\MailSo\Base\HtmlUtils::ConvertHtmlToPlain($sData), false);
			$oMessage->AddHtml($sData, true);
		}
	}
	
	private function addHtmlBodyAndSubjectForPostMessage($sPath, &$oMessage,
		$oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName)
	{
		$sSubject = '';
		$sData = $this->getMessageTemplate($sPath, $sSubject, function ($sData) use
			($oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName) {

			$sPostOwner = \MailSo\Mime\Email::NewInstance($oHelpdeskPostOwnerUser->Email, $oHelpdeskPostOwnerUser->Name)->ToString();

			$sSubjectPrefix = '';
			if ($oThread && 0 < $oThread->PostCount - 1)
			{
				$sSubjectPrefix = 'Re'.(2 < $oThread->PostCount ? '['.($oThread->PostCount - 1).']' : '').': ';
			}

			$sAttachments = '';
			if ($oPost && is_array($oPost->Attachments) && 0 < count($oPost->Attachments))
			{
				$sAttachmentsNames = array();
				foreach ($oPost->Attachments as $oAttachment)
				{
					if ($oAttachment)
					{
						$sAttachmentsNames[] = $oAttachment->FileName;
					}
				}

				$sAttachments = '<br /><br />Attachments: '.implode(', ', $sAttachmentsNames).'<br />';
			}

			return strtr($sData, array(
				'{{HELPDESK_SITE_NAME}}' => 0 === strlen($sSiteName) ? 'Helpdesk' : $sSiteName,
				'{{MESSAGE_SUBJECT}}' => $sSubjectPrefix.$oThread->Subject,
				'{{THREAD_SUBJECT}}' => $oThread->Subject,
				'{{THREAD_OWNER}}' => $oHelpdeskThreadOwnerUser && 0 < \strlen($oHelpdeskThreadOwnerUser->Name) ?
					' '.$oHelpdeskThreadOwnerUser->Name : '',
				'{{POST_OWNER}}' => $sPostOwner,
				'{{THREAD_LINK}}' => $oThread->ThreadLink(),
				'{{THREAD_ID}}' => $oThread->IdHelpdeskThread,
				'{{THREAD_HASH}}' => $oThread->StrHelpdeskThreadHash,
				'{{POST_ATTACHMENTS}}' => $sAttachments,
				'{{LOGIN_LINK}}' => $oThread->LoginLink(),
				'{{POST_TEXT}}' => $oPost->Text,
				'{{POST_HTML}}' => \MailSo\Base\HtmlUtils::ConvertPlainToHtml($oPost->Text)
			));
		});

		if (0 < strlen($sSubject))
		{
			$oMessage->SetSubject($sSubject);
		}

		if (is_string($sData) && 0 < strlen($sData))
		{
			$oMessage->AddText(\MailSo\Base\HtmlUtils::ConvertHtmlToPlain($sData), false);
			$oMessage->AddHtml($sData, true);
		}
	}


	/**
	 * @param string $sFrom
	 * @param string $sTo
	 * @param string $sSubject
	 * @param string $sCc = ''
	 * @param string $sBcc = ''
	 * @param string $sMessageID = ''
	 * @param string $sReferences = ''
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function buildMail($sFrom, $sTo, $sSubject, $sCc = '', $sBcc = '', $sMessageID = '', $sReferences = '')
	{
		$oMessage = \MailSo\Mime\Message::NewInstance();

		if (empty($sMessageID))
		{
			$oMessage->RegenerateMessageId();
		}
		else
		{
			$oMessage->SetMessageId($sMessageID);
		}

		if (!empty($sReferences))
		{
			$oMessage->SetReferences($sReferences);
		}

		$sXMailer = \CApi::GetConf('webmail.xmailer-value', '');
		if (0 < strlen($sXMailer))
		{
			$oMessage->SetXMailer($sXMailer);
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

		return $oMessage;
	}
	
	private function initMessageIdAndReferences($oThread, &$sMessageID, &$sReferences)
	{
		if ($oThread && 0 < $oThread->PostCount)
		{
			$sReferences = '';
			if (1 < $oThread->PostCount)
			{
				for ($iIndex = 1, $iLen = $oThread->PostCount; $iIndex < $iLen; $iIndex++)
				{
					$sReferences .= ' <'.md5($oThread->IdHelpdeskThread.$oThread->IdTenant.$iIndex).'@hdsystem>';
				}
			}

			$sReferences = trim($sReferences);
			$sMessageID = '<'.md5($oThread->IdHelpdeskThread.$oThread->IdTenant.$oThread->PostCount).'@hdsystem>';
		}
	}

	/**
	 * @param string $sFrom
	 * @param string $sTo
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function buildPostMail($sPath, $sFrom, $sTo, $sSubject, $sCc, $sBcc,
		$oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName)
	{
		$sMessageID = '';
		$sReferences = '';

		$this->initMessageIdAndReferences($oThread, $sMessageID, $sReferences);

		$oMessage = $this->buildMail($sFrom, $sTo, $sSubject, $sCc, $sBcc, $sMessageID, $sReferences);

		$this->addHtmlBodyAndSubjectForPostMessage($sPath,
			$oMessage, $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName
		);

		return $oMessage;
	}

	/**
	 * @param string $sFrom
	 * @param string $sTo
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function buildUserMail($sPath, $sFrom, $sTo, $sSubject, $sCc, $sBcc, $oHelpdeskUser, $sSiteName)
	{
		$oMessage = $this->buildMail($sFrom, $sTo, $sSubject, $sCc, $sBcc);
		
		$this->addHtmlBodyAndSubjectForUserMessage($sPath, $oMessage, $oHelpdeskUser, $sSiteName);

		return $oMessage;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return bool
	 */
	public function CreateUser(CHelpdeskUser &$oHelpdeskUser)
	{
		$bResult = false;
		try
		{
			if ($oHelpdeskUser->Validate())
			{
				if (!$this->UserExists($oHelpdeskUser))
				{
					if (!$this->oStorage->CreateUser($oHelpdeskUser))
					{
						throw new CApiManagerException(Errs::HelpdeskManager_UserCreateFailed);
					}
					else if (!$oHelpdeskUser->Activated)
					{
						$this->NotifyRegistration($oHelpdeskUser);
					}
				}
				else
				{
					throw new CApiManagerException(Errs::HelpdeskManager_UserAlreadyExists);
				}
			}

			$bResult = true;
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iHelpdeskUserId
	 * 
	 * @return CHelpdeskUser|false
	 */
	public function GetUserById($iIdTenant, $iHelpdeskUserId)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->GetUserById($iIdTenant, $iHelpdeskUserId);
		}
		catch (CApiBaseException $oException)
		{
			$oUser = false;
			$this->setLastException($oException);
		}
		return $oUser;
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sActivateHash
	 *
	 * @return CHelpdeskUser|false
	 */
	public function GetUserByActivateHash($iIdTenant, $sActivateHash)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->GetUserByActivateHash($iIdTenant, $sActivateHash);
		}
		catch (CApiBaseException $oException)
		{
			$oUser = false;
			$this->setLastException($oException);
		}
		return $oUser;
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iHelpdeskUserId
	 *
	 * @return CHelpdeskUser|false
	 */
	public function GetHelpdesMainSettings($iIdTenant)
	{
		$oApiTenant = 0 < $iIdTenant ? CApi::Manager('tenants') : null;
		$oTenant = $oApiTenant ? $oApiTenant->GetTenantById($iIdTenant) : null;

		$sClientIframeUrl = '';
		$sAdminEmailAccount = '';
		$sAgentIframeUrl = '';
		$sSiteName = '';

		if (0 < $iIdTenant && $oTenant)
		{
			$sAdminEmailAccount = $oTenant->HelpdeskAdminEmailAccount;
			$sClientIframeUrl = $oTenant->HelpdeskClientIframeUrl;
			$sAgentIframeUrl = $oTenant->HelpdeskAgentIframeUrl;
			$sSiteName = $oTenant->HelpdeskSiteName;
		}
		else
		{
			$sAdminEmailAccount = $this->oSettings->GetConf('Helpdesk/AdminEmailAccount');
			$sClientIframeUrl = $this->oSettings->GetConf('Helpdesk/ClientIframeUrl');
			$sAgentIframeUrl = $this->oSettings->GetConf('Helpdesk/AgentIframeUrl');
			$sSiteName = $this->oSettings->GetConf('Helpdesk/SiteName');
		}

		return array(
			'AdminEmailAccount' => $sAdminEmailAccount,
			'ClientIframeUrl' => $sClientIframeUrl,
			'AgentIframeUrl' => $sAgentIframeUrl,
			'SiteName' => $sSiteName
		);
	}

	/**
	 * @param int $iIdTenant
	 * @param array $aExcludeEmails = array()
	 * 
	 * @return array
	 */
	public function GetAgentsEmailsForNotification($iIdTenant, $aExcludeEmails = array())
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->GetAgentsEmailsForNotification($iIdTenant, $aExcludeEmails);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $aResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 * 
	 * @return CHelpdeskUser|null|false
	 */
	public function GetUserByEmail($iIdTenant, $sEmail)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->GetUserByEmail($iIdTenant, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$oUser = false;
			$this->setLastException($oException);
		}
		return $oUser;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return bool
	 */
	public function ForgotUser($oHelpdeskUser)
	{
		$this->NotifyForgot($oHelpdeskUser);
		return true;
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return bool
	 */
	public function UserExists(CHelpdeskUser $oHelpdeskUser)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->UserExists($oHelpdeskUser);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aIdList
	 *
	 * @return array|bool
	 */
	public function UserInformation(CHelpdeskUser $oHelpdeskUser, $aIdList)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->UserInformation($oHelpdeskUser, $aIdList);
		}
		catch (CApiBaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return bool
	 */
	public function UpdateUser(CHelpdeskUser $oHelpdeskUser)
	{
		$bResult = false;
		try
		{
			if ($oHelpdeskUser->Validate())
			{
				$bResult = $this->oStorage->UpdateUser($oHelpdeskUser);
				if (!$bResult)
				{
					$this->moveStorageExceptionToManager();
					throw new CApiManagerException(Errs::HelpdeskManager_UserUpdateFailed);
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iIdHelpdeskUser
	 * @return bool
	 */
	public function SetUserAsBlocked($iIdTenant, $iIdHelpdeskUser)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SetUserAsBlocked($iIdTenant, $iIdHelpdeskUser);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return bool
	 */
	public function DeleteUser(CHelpdeskUser $oHelpdeskUser)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->DeleteUser($oHelpdeskUser);
//			if ($bResult)
//			{
//				// TODO
//			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param array $aPostIds
	 *
	 * @return bool
	 */
	public function DeletePosts(CHelpdeskUser $oHelpdeskUser, $oThread, $aPostIds)
	{
		$bResult = false;
		try
		{
			if ($oThread instanceof CHelpdeskThread && 0 < count($aPostIds))
			{
				$bResult = $this->oStorage->DeletePosts($oHelpdeskUser, $oThread, $aPostIds);
				if ($bResult)
				{
					$oThread->PostCount = $this->GetPostsCount($oHelpdeskUser, $oThread);
					$bResult = $this->UpdateThread($oHelpdeskUser, $oThread);
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 *
	 * @return bool
	 */
	public function VerifyThreadIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$bResult = false;
		try
		{
			if (0 < count($aThreadIds))
			{
				$bResult = $this->oStorage->VerifyThreadIdsBelongToUser($oHelpdeskUser, $aThreadIds);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aPostIds
	 *
	 * @return bool
	 */
	public function VerifyPostIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aPostIds)
	{
		$bResult = false;
		try
		{
			if (0 < count($aPostIds))
			{
				$bResult = $this->oStorage->VerifyPostIdsBelongToUser($oHelpdeskUser, $aPostIds);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 * @param bool $bSetArchive = true
	 *
	 * @return bool
	 */
	public function ArchiveThreads(CHelpdeskUser $oHelpdeskUser, $aThreadIds, $bSetArchive = true)
	{
		$bResult = false;
		try
		{
			if (0 < count($aThreadIds))
			{
				$bResult = $this->oStorage->ArchiveThreads($oHelpdeskUser, $aThreadIds, $bSetArchive);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iIdThread
	 *
	 * @return CHelpdeskThread|false
	 */
	public function GetThreadById($oHelpdeskUser, $iIdThread)
	{
		$oThread = null;
		try
		{
			$oThread = $this->oStorage->GetThreadById($oHelpdeskUser, $iIdThread);
			if ($oThread)
			{
				$aThreadLastPostIds = $this->GetThreadsLastPostIds($oHelpdeskUser, array($oThread->IdHelpdeskThread));
				if (isset($aThreadLastPostIds[$oThread->IdHelpdeskThread]) &&
					$oThread->LastPostId === $aThreadLastPostIds[$oThread->IdHelpdeskThread])
				{
					$oThread->IsRead = true;
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$oThread = false;
			$this->setLastException($oException);
		}
		return $oThread;
	}
	
	/**
	 * @param int $iTenantID
	 * @param string $sHash
	 *
	 * @return int
	 */
	public function GetThreadIdByHash($iTenantID, $sHash)
	{
		$iThreadID = 0;
		try
		{
			$iThreadID = $this->oStorage->GetThreadIdByHash($iTenantID, $sHash);
		}
		catch (CApiBaseException $oException)
		{
			$iThreadID = 0;
			$this->setLastException($oException);
		}
		return $iThreadID;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 * @return bool
	 */
	public function CreateThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread &$oHelpdeskThread)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->CreateThread($oHelpdeskUser, $oHelpdeskThread);
			if (!$bResult)
			{
				$this->moveStorageExceptionToManager();
				throw new CApiManagerException(Errs::HelpdeskManager_ThreadCreateFailed);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 * @return bool
	 */
	public function UpdateThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->UpdateThread($oHelpdeskUser, $oHelpdeskThread);
			if (!$bResult)
			{
				$this->moveStorageExceptionToManager();
				throw new CApiManagerException(Errs::HelpdeskManager_ThreadUpdateFailed);
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function StartMailboxMonitor()
	{
		return true;
	}

	private function getOwnerFromSearch($iIdTenant, &$sSearch)
	{
		$aMatch = array();
		$sSearch = trim($sSearch);
		if (0 < strlen($sSearch) && preg_match('/owner:[\s]?([^\s]+@[^\s]+)/', $sSearch, $aMatch) && !empty($aMatch[0]) && !empty($aMatch[1]))
		{
			$sSearch = trim(str_replace($aMatch[0], '', $sSearch));
			$oUser = $this->GetUserByEmail($iIdTenant, trim($aMatch[1]));
			if ($oUser)
			{
				return $oUser->IdHelpdeskUser;
			}
		}

		return 0;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iFilter = EHelpdeskThreadFilterType::All
	 * @param string $sSearch = ''
	 * 
	 * @return int
	 */
	public function GetThreadsCount(CHelpdeskUser $oHelpdeskUser, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '')
	{
		$iResult = 0;
		try
		{
			$iSearchOwner = $this->getOwnerFromSearch($oHelpdeskUser->IdTenant, $sSearch);
			$iResult = $this->oStorage->GetThreadsCount($oHelpdeskUser, $iFilter, $sSearch, $iSearchOwner);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iOffset = 0
	 * @param int $iLimit = 20
	 * @param int $iFilter = EHelpdeskThreadFilterType::All
	 * @param string $sSearch = ''
	 *
	 * @return array|bool
	 */
	public function GetThreads(CHelpdeskUser $oHelpdeskUser, $iOffset = 0, $iLimit = 20, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '')
	{
		$aResult = null;
		try
		{
			$iSearchOwner = $this->getOwnerFromSearch($oHelpdeskUser->IdTenant, $sSearch);
			$aResult = $this->oStorage->GetThreads($oHelpdeskUser, $iOffset, $iLimit, $iFilter, $sSearch, $iSearchOwner);
			if (is_array($aResult) && 0 < count($aResult))
			{
				$aThreadsIdList = array();
				foreach ($aResult as $oItem)
				{
					$aThreadsIdList[] = $oItem->IdHelpdeskThread;
				}
				
				$aThreadLastPostIds = $this->GetThreadsLastPostIds($oHelpdeskUser, $aThreadsIdList);
				if (is_array($aThreadLastPostIds) && 0 < count($aThreadLastPostIds))
				{
					foreach ($aResult as &$oItem)
					{
						if (isset($aThreadLastPostIds[$oItem->IdHelpdeskThread]) &&
							$oItem->LastPostId === $aThreadLastPostIds[$oItem->IdHelpdeskThread])
						{
							$oItem->IsRead = true;
						}
					}
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $aResult;
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 *
	 * @return int
	 */
	public function GetPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetPostsCount($oHelpdeskUser, $oThread);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 *
	 * @return array|bool
	 */
	public function GetThreadsLastPostIds(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->GetThreadsLastPostIds($oHelpdeskUser, $aThreadIds);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return array|bool
	 */
	public function GetAttachments(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aResult = null;
		try
		{
			$aResult = $this->oStorage->GetAttachments($oHelpdeskUser, $oHelpdeskThread);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param int $iStartFromId = 0
	 * @param int $iLimit = 20
	 *
	 * @return array|bool
	 */
	public function GetPosts(CHelpdeskUser $oHelpdeskUser, $oThread, $iStartFromId = 0, $iLimit = 20)
	{
		$aResult = null;
		try
		{
			$aResult = $this->oStorage->GetPosts($oHelpdeskUser, $oThread, $iStartFromId, $iLimit);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param CHelpdeskUser $oUser
	 *
	 * @return bool
	 */
	public function NotifyForgot($oUser)
	{
		if ($oUser)
		{
			$oFromAccount = null;
			$aData = $this->GetHelpdesMainSettings($oUser->IdTenant);
			if (!empty($aData['AdminEmailAccount']))
			{
				$oApiUsers = $this->apiUsers();
				if ($oApiUsers)
				{
					$oFromAccount = $oApiUsers->GetAccountOnLogin($aData['AdminEmailAccount']);
				}
			}

			$sSiteName = isset($aData['SiteName']) ? $aData['SiteName'] : $sSiteName;

			if ($oFromAccount)
			{
				$oApiMail = $this->apiMail();
				if ($oApiMail)
				{
					$oFromEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
					$oToEmail = \MailSo\Mime\Email::NewInstance($oUser->Email, $oUser->Name);

					$oUserMessage = $this->buildUserMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.forgot.html',
						$oFromEmail->ToString(), $oToEmail->ToString(),
						'Forgot', '', '', $oUser, $sSiteName);

					$oApiMail->MessageSend($oFromAccount, $oUserMessage);
				}
			}
		}
	}
	
	/**
	 * @param CHelpdeskUser $oUser
	 *
	 * @return bool
	 */
	public function NotifyRegistration($oUser)
	{
		if ($oUser)
		{
			$oFromAccount = null;
			$aData = $this->GetHelpdesMainSettings($oUser->IdTenant);
			if (!empty($aData['AdminEmailAccount']))
			{
				$oApiUsers = $this->apiUsers();
				if ($oApiUsers)
				{
					$oFromAccount = $oApiUsers->GetAccountOnLogin($aData['AdminEmailAccount']);
				}
			}

			$sSiteName = isset($aData['SiteName']) ? $aData['SiteName'] : $sSiteName;

			if ($oFromAccount)
			{
				$oApiMail = $this->apiMail();
				if ($oApiMail)
				{
					$oFromEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
					$oToEmail = \MailSo\Mime\Email::NewInstance($oUser->Email, $oUser->Name);

					$oUserMessage = $this->buildUserMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.registration.html',
						$oFromEmail->ToString(), $oToEmail->ToString(),
						'Registration', '', '', $oUser, $sSiteName);

					$oApiMail->MessageSend($oFromAccount, $oUserMessage);
				}
			}
		}
	}

	/**
	 * @param CHelpdeskThread $oThread
	 * @param CHelpdeskPost $oPost
	 * @param bool $bIsNew = false
	 *
	 * @return bool
	 */
	public function NotifyPost($oThread, $oPost, $bIsNew = false)
	{
		if ($oThread && $oPost)
		{
			$oFromAccount = null;

			$aData = $this->GetHelpdesMainSettings($oPost->IdTenant);
			if (!empty($aData['AdminEmailAccount']))
			{
				$oApiUsers = $this->apiUsers();
				if ($oApiUsers)
				{
					$oFromAccount = $oApiUsers->GetAccountOnLogin($aData['AdminEmailAccount']);
				}
			}

			$sSiteName = isset($aData['SiteName']) ? $aData['SiteName'] : '';

			$oHelpdeskThreadOwnerUser = $this->GetUserById($oThread->IdTenant, $oThread->IdOwner);

			// mail notifications
			if ($oFromAccount && $oHelpdeskThreadOwnerUser)
			{
				$oApiMail = $this->apiMail();
				if ($oApiMail)
				{
					$oHelpdeskPostOwnerUser = $this->GetUserById($oPost->IdTenant, $oPost->IdOwner);

					$aDeMail = array();

					$oHelpdeskSenderEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
					$oThreadOwnerEmail = \MailSo\Mime\Email::NewInstance($oHelpdeskThreadOwnerUser->Email, $oHelpdeskThreadOwnerUser->Name);

					if (EHelpdeskPostType::Normal === $oPost->Type && ($bIsNew || $oHelpdeskThreadOwnerUser->IdHelpdeskUser !== $oPost->IdOwner))
					{
						$oUserMessage = $this->buildPostMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.post'.($bIsNew ? '.new' : '').'.html',
							$oHelpdeskSenderEmail->ToString(), $oThreadOwnerEmail->ToString(),
							'New Post', '', '', $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName);

						if ($oUserMessage)
						{
							$aDeMail[] = $oHelpdeskThreadOwnerUser->Email;
							$oApiMail->MessageSend($oFromAccount, $oUserMessage);
						}
					}

					if (EHelpdeskPostType::Internal === $oPost->Type || $oHelpdeskThreadOwnerUser->IdHelpdeskUser === $oPost->IdOwner)
					{
						$aDeMail[] = $oHelpdeskThreadOwnerUser->Email;
					}

					if (0 < count($aDeMail))
					{
						$aDeMail = array_unique($aDeMail);
					}

					$aAgents = $this->GetAgentsEmailsForNotification($oPost->IdTenant, $aDeMail);
					if (is_array($aAgents) && 0 < count($aAgents))
					{
						$oAgentMessage = $this->buildPostMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/agent.post.html',
							$oHelpdeskSenderEmail->ToString(), is_array($aAgents) && 0 < count($aAgents) ? implode(', ', $aAgents) : '',
							'New Post', '', '', $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName);

						if ($oAgentMessage)
						{
							$oApiMail->MessageSend($oFromAccount, $oAgentMessage);
						}
					}
				}
			}
		}
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param CHelpdeskPost $oPost
	 * @param bool $bIsNew = false
	 *
	 * @return bool
	 */
	public function CreatePost(CHelpdeskUser $oHelpdeskUser, $oThread, CHelpdeskPost $oPost, $bIsNew = false)
	{
		$bResult = false;
		try
		{
			if ($oPost->Validate())
			{
				if ($oPost->Type === EHelpdeskPostType::Internal && !$oHelpdeskUser->IsAgent)
				{
					$oPost->Type = EHelpdeskPostType::Normal;
				}

				if ($oHelpdeskUser->IsAgent && !$bIsNew && $oHelpdeskUser->IdHelpdeskUser !== $oThread->IdOwner)
				{
					if ($oPost->Type !== EHelpdeskPostType::Internal)
					{
						$oThread->Type = EHelpdeskThreadType::Answered;
					}
				}
				else
				{
					$oThread->Type = EHelpdeskThreadType::Pending;
				}

				$bResult = $this->oStorage->CreatePost($oHelpdeskUser, $oPost);
				if (!$bResult)
				{
					$this->moveStorageExceptionToManager();
					throw new CApiManagerException(Errs::HelpdeskManager_PostCreateFailed);
				}
				else
				{
					if (is_array($oPost->Attachments) && 0 < count($oPost->Attachments))
					{
						$this->oStorage->AddAttachments($oHelpdeskUser, $oThread, $oPost, $oPost->Attachments);
					}

					$oThread->Updated = time();
					$oThread->PostCount = $this->GetPostsCount($oHelpdeskUser, $oThread);
					$oThread->LastPostId = $oPost->IdHelpdeskPost;

					if (!$oThread->HasAttachments)
					{
						$oThread->HasAttachments = is_array($oPost->Attachments) && 0 < count($oPost->Attachments);
					}

					$bResult = $this->UpdateThread($oHelpdeskUser, $oThread);

					$this->SetThreadSeen($oHelpdeskUser, $oThread);

					$this->NotifyPost($oThread, $oPost, $bIsNew);
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return bool
	 */
	public function SetThreadSeen(CHelpdeskUser $oHelpdeskUser, $oHelpdeskThread)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SetThreadSeen($oHelpdeskUser, $oHelpdeskThread);
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function ClearUnregistredUsers()
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->ClearUnregistredUsers();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $mResult;
	}
}
