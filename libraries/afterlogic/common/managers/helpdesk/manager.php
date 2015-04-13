<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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

	private function addHtmlBodyAndSubjectForUserMessage($sPath, &$oMessage, $oHelpdeskUser, $sSiteName, $sFrom)
	{
		$sSubject = '';
		$sData = $this->getMessageTemplate($sPath, $sSubject, function ($sData) use ($oHelpdeskUser, $sSiteName, $sFrom) {
			return strtr($sData, array(
				'{{HELPDESK_SITE_NAME}}' => 0 === strlen($sSiteName) ? 'Helpdesk' : $sSiteName,
				'{{HELPDESK_LINK}}' => $oHelpdeskUser->HelpdeskLink(),
				'{{FROM_EMAIL}}' => $sFrom,
				'{{USER_EMAIL}}' => $oHelpdeskUser->ResultEmail(),
				'{{USER_PASSWORD}}' => $oHelpdeskUser->NotificationPassword,
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

			$sPostOwner = \MailSo\Mime\Email::NewInstance($oHelpdeskPostOwnerUser->ResultEmail(), $oHelpdeskPostOwnerUser->Name)->ToString();

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
				'{{POST_TEXT}}' => $oPost ? $oPost->Text : '',
				'{{POST_HTML}}' => $oPost ? \MailSo\Base\HtmlUtils::ConvertPlainToHtml($oPost->Text) : ''
			));
		});

		if (0 < strlen($sSubject))
		{
			$oMessage->SetSubject($sSubject.' [#'.$oThread->StrHelpdeskThreadHash.'#]');
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
		
		$this->addHtmlBodyAndSubjectForUserMessage($sPath, $oMessage, $oHelpdeskUser, $sSiteName, $sFrom);

		return $oMessage;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param bool $bCreateFromFetcher = false
	 * @return bool
	 */
	public function CreateUser(CHelpdeskUser &$oHelpdeskUser, $bCreateFromFetcher = false)
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
						$this->NotifyRegistration($oHelpdeskUser, $bCreateFromFetcher);
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
	 * @param int $iHelpdeskUserId
	 *
	 * @return CHelpdeskUser|false
	 */
	public function GetUserByIdWithoutTenantID($iHelpdeskUserId)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->GetUserByIdWithoutTenantID($iHelpdeskUserId);
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
	 *
	 * @return CHelpdeskUser|false
	 */
	public function GetHelpdeskMainSettings($iIdTenant)
	{
		$oApiTenant = CApi::Manager('tenants');
		$oTenant = /* @var $oTenant CTenant */ $oApiTenant ? 
			(0 < $iIdTenant ? $oApiTenant->GetTenantById($iIdTenant) : $oApiTenant->GetDefaultGlobalTenant()) : null;

		$sClientIframeUrl = '';
		$sAdminEmailAccount = '';
		$sAgentIframeUrl = '';
		$sSiteName = '';
		$bStyleAllow = false;
		$sStyleImage = '';
		$sStyleText = '';

		$bFacebookAllow = false;
		$sFacebookId = '';
		$sFacebookSecret = '';
		$bGoogleAllow = false;
		$sGoogleId = '';
		$sGoogleSecret = '';
		$bTwitterAllow = false;
		$sTwitterId = '';
		$sTwitterSecret = '';

		$iHelpdeskFetcherType = 0;

		if ($oTenant)
		{
			$sAdminEmailAccount = $oTenant->HelpdeskAdminEmailAccount;
			$sClientIframeUrl = $oTenant->HelpdeskClientIframeUrl;
			$sAgentIframeUrl = $oTenant->HelpdeskAgentIframeUrl;
			$sSiteName = $oTenant->HelpdeskSiteName;
			$bStyleAllow = $oTenant->HelpdeskStyleAllow;
			$sStyleImage = $oTenant->HelpdeskStyleImage;
			$sStyleText = $oTenant->GetHelpdeskStyleText();

			$iHelpdeskFetcherType = $oTenant->HelpdeskFetcherType;
/*
			$bFacebookAllow = $oTenant->SocialFacebookAllow;
			$sFacebookId = $oTenant->SocialFacebookId;
			$sFacebookSecret = $oTenant->SocialFacebookSecret;
			$bGoogleAllow = $oTenant->SocialGoogleAllow;
			$sGoogleId = $oTenant->SocialGoogleId;
			$sGoogleSecret = $oTenant->SocialGoogleSecret;
			$bTwitterAllow = $oTenant->SocialTwitterAllow;
			$sTwitterId = $oTenant->SocialTwitterId;
			$sTwitterSecret = $oTenant->SocialTwitterSecret;
 * 
 */
		}

		return array(
			'AdminEmailAccount' => $sAdminEmailAccount,
			'ClientIframeUrl' => $sClientIframeUrl,
			'AgentIframeUrl' => $sAgentIframeUrl,
			'SiteName' => $sSiteName,
			'StyleAllow' => $bStyleAllow,
			'StyleImage' => $sStyleImage,
			'StyleText' => $sStyleText,

			'HelpdeskFetcherType' => $iHelpdeskFetcherType,

			'FacebookAllow' => $bFacebookAllow,
			'FacebookId' => $sFacebookId,
			'FacebookSecret' => $sFacebookSecret,
			'GoogleAllow' => $bGoogleAllow,
			'GoogleId' => $sGoogleSecret,
			'GoogleSecret' => $sGoogleId,
			'TwitterAllow' => $bTwitterAllow,
			'TwitterId' => $sTwitterId,
			'TwitterSecret' => $sTwitterSecret,
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
	 * @param int $iIdTenant
	 * @param string $sEmail
	 *
	 * @return CHelpdeskUser|null|false
	 */
	public function GetUserByNotificationEmail($iIdTenant, $sEmail)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->GetUserByNotificationEmail($iIdTenant, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$oUser = false;
			$this->setLastException($oException);
		}
		return $oUser;
	}

	public function GetUserBySocialId($iIdTenant, $sSocialId)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->GetUserBySocialId($iIdTenant, $sSocialId);
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
		if(!$oHelpdeskUser->SocialId)
		{
			try
			{
				$bResult = $this->oStorage->UserExists($oHelpdeskUser);
			}
			catch (CApiBaseException $oException)
			{
				$bResult = false;
				$this->setLastException($oException);
			}
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
	 * @return bool
	 */
	public function ArchiveOutdatedThreads()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->ArchiveOutdatedThreads();
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
	public function NotificateOutdatedThreads()
	{
		$bResult = false;
		try
		{
			$iIdOwner = 0;
			$iIdHelpdeskThread = $this->oStorage->NotificateOutdatedThreadID($iIdOwner);
			if ($iIdHelpdeskThread && $iIdOwner)
			{
				$oHelpdeskUser = $this->GetUserByIdWithoutTenantID($iIdOwner);
				if ($oHelpdeskUser)
				{
					$oThread = $this->GetThreadById($oHelpdeskUser, $iIdHelpdeskThread);
					if ($oThread)
					{
						$this->NotifyOutdated($oThread);
					}
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
	 * @return bool|int
	 */
	public function GetNextHelpdeskIdForMonitoring()
	{
		$mResult = false;
		if (CApi::GetConf('helpdesk', false))
		{
			if (CApi::GetConf('tenant', false))
			{
				try
				{
					$mResult = $this->oStorage->GetNextHelpdeskIdForMonitoring(
						(int) CApi::GetConf('helpdesk.fetcher-time-limit-in-min', 5));
					
					if (0 >= $mResult)
					{
						$mResult = false;
					}
				}
				catch (CApiBaseException $oException)
				{
					$this->setLastException($oException);
				}
			}
			else
			{
				$mResult = 0;
			}
		}

		return $mResult;
	}

	/**
	 * @return bool
	 */
	public function StartHelpdesksMailboxMonitor()
	{
		$iIdTenant = $this->GetNextHelpdeskIdForMonitoring();
		if (false !== $iIdTenant)
		{
			$this->oStorage->UpdateHelpdeskFetcherTimer($iIdTenant);
			$this->StartMailboxMonitor($iIdTenant);
		}

		return true;
	}

	/**
	 * @return int
	 */
	public function GetHelpdeskMailboxLastUid($iIdTenant, $sEmail)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetHelpdeskMailboxLastUid($iIdTenant, $sEmail);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @return bool
	 */
	public function SetHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->SetHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @todo
	 * @return bool
	 */
	public function StartMailboxMonitor($iIdTenant)
	{
		$aMainSettingsData = $this->GetHelpdeskMainSettings($iIdTenant);
		if (!empty($aMainSettingsData['AdminEmailAccount']) && 0 < $aMainSettingsData['HelpdeskFetcherType'])
		{
			$oApiUsers = $this->apiUsers();
			$oApiMail = $this->apiMail();
			
			$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
			$oApiFilestorage = /* @var $oApiFileCache \CApiFilestorageManager */ \CApi::Manager('filestorage');
			$oApiIntegrator = /* @var $oApiIntegrator \CApiIntegratorManager */ \CApi::Manager('integrator');
			
			if ($oApiUsers && $oApiMail && $oApiFileCache)
			{
				$oAccount = $oApiUsers->GetAccountOnLogin($aMainSettingsData['AdminEmailAccount']);
				if ($oAccount)
				{
					$iPrevLastUid = $this->GetHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email));
					
					$iLastUid = 0;
					$aData = $oApiMail->HelpdeskMessagesHelper($oAccount, 0 < $iPrevLastUid ? $iPrevLastUid + 1 : 0, $iLastUid);
					if (0 < $iLastUid)
					{
						$this->SetHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email), $iLastUid);
					}

					if (is_array($aData) && 0 < count($aData))
					{
						foreach ($aData as $oMessage)
						{
							$aMatch = array();
							$oFrom = $oMessage->From();
							$aFrom = $oFrom ? $oFrom->GetAsArray() : array();
							$oAttachments = $oMessage->Attachments();
							$aAttachments = $oAttachments ? $oAttachments->GetAsArray() : array();

							$sSubject = $oMessage->Subject();
							if (
								is_array($aFrom) && 0 < count($aFrom) && (
								(EHelpdeskFetcherType::REPLY === $aMainSettingsData['HelpdeskFetcherType'] && !empty($sSubject) && preg_match('/\[#([a-zA-Z0-9]+)#\]/', $sSubject, $aMatch))
									||
								(EHelpdeskFetcherType::ALL === $aMainSettingsData['HelpdeskFetcherType'])
							))
							{
								$sThreadHash = '';
								$aMatch = array();
								if (preg_match('/\[#([a-zA-Z0-9]+)#\]/', $sSubject, $aMatch) && !empty($aMatch[1]))
								{
									$sThreadHash = (string) $aMatch[1];
								}

								$oEmail = $aFrom[0];
								$sEmail = $oEmail ? $oEmail->GetEmail() : '';
								$oHelpdeskUser = null;
								
								if (0 < \strlen($sEmail))
								{
									$oHelpdeskUser = $this->GetUserByEmail($iIdTenant, $sEmail);
									if (!$oHelpdeskUser)
									{
										$sPassword = md5(microtime(true));
										$oHelpdeskUser = $oApiIntegrator->RegisterHelpdeskAccount($iIdTenant, $sEmail, '', $sPassword, true);
									}
								
									if ($oHelpdeskUser)
									{
										$oThread = null;
										if (!empty($sThreadHash))
										{
											$iThreadID = $this->GetThreadIdByHash($iIdTenant, $sThreadHash);
											if (0 < $iThreadID)
											{
												$oThread = $this->GetThreadById($oHelpdeskUser, $iThreadID);
											}
										}
										else
										{
											$oThread = new \CHelpdeskThread();
											$oThread->IdTenant = $iIdTenant;
											$oThread->IdOwner = $oHelpdeskUser->IdHelpdeskUser;
											$oThread->Type = \EHelpdeskThreadType::Pending;
											$oThread->Subject = $sSubject;

											if (!$this->CreateThread($oHelpdeskUser, $oThread))
											{
												$oThread = null;
											}
										}

										if ($oThread)
										{
											$sText = trim($oMessage->Html());
											if (0 === strlen($sText))
											{
												$sText = trim($oMessage->Plain());
											}
											else
											{
												$sText = \MailSo\Base\HtmlUtils::ConvertHtmlToPlain($sText);
											}

											$oPost = new \CHelpdeskPost();
											$oPost->IdTenant = $oHelpdeskUser->IdTenant;
											$oPost->IdOwner = $oHelpdeskUser->IdHelpdeskUser;
											$oPost->IdHelpdeskThread = $oThread->IdHelpdeskThread;
											$oPost->Type = \EHelpdeskPostType::Normal;
											$oPost->SystemType = \EHelpdeskPostSystemType::None;
											$oPost->Text = $sText;

											$aResultAttachment = array();
											if (is_array($aAttachments) && 0 < count($aAttachments))
											{
												foreach ($aAttachments as /* @var $oAttachment CApiMailAttachment */ $oAttachment)
												{
													$sUploadName = $oAttachment->FileName(true);
													$sTempName = md5($sUploadName.rand(1000, 9999));

													$oApiMail->MessageMimeStream($oAccount,
														function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($oHelpdeskUser, &$sTempName, $oApiFileCache) {

															if (!$oApiFileCache->PutFile($oHelpdeskUser, $sTempName, $rResource))
															{
																$sTempName = '';
															}

														}, $oAttachment->Folder(), $oAttachment->Uid(), $oAttachment->MimeIndex());


													$rData = 0 < \strlen($sTempName) ? $oApiFileCache->GetFile($oHelpdeskUser, $sTempName) : null;
													if ($rData)
													{
														$iFileSize = $oApiFileCache->FileSize($oHelpdeskUser, $sTempName);

														$sThreadID = (string) $oThread->IdHelpdeskThread;
														$sThreadID = str_pad($sThreadID, 2, '0', STR_PAD_LEFT);
														$sThreadIDSubFolder = substr($sThreadID, 0, 2);

														$sThreadFolderName = API_HELPDESK_PUBLIC_NAME.'/'.$sThreadIDSubFolder.'/'.$sThreadID;

														$oApiFilestorage->CreateFolder($oHelpdeskUser, \EFileStorageTypeStr::Corporate, '',
															$sThreadFolderName);

														$oApiFilestorage->CreateFile($oHelpdeskUser,
															\EFileStorageTypeStr::Corporate, $sThreadFolderName, $sUploadName, $rData, false);

														if (is_resource($rData))
														{
															@fclose($rData);
														}

														$oAttachment = new \CHelpdeskAttachment();
														$oAttachment->IdHelpdeskThread = $oThread->IdHelpdeskThread;
														$oAttachment->IdHelpdeskPost = $oPost->IdHelpdeskPost;
														$oAttachment->IdOwner = $oHelpdeskUser->IdHelpdeskUser;
														$oAttachment->IdTenant = $oHelpdeskUser->IdTenant;

														$oAttachment->FileName = $sUploadName;
														$oAttachment->SizeInBytes = $iFileSize;
														$oAttachment->EncodeHash($oHelpdeskUser, $sThreadFolderName);

														$oApiFileCache->Clear($oHelpdeskUser, $sTempName);

														$aResultAttachment[] = $oAttachment;
													}
												}

												if (is_array($aResultAttachment) && 0 < count($aResultAttachment))
												{
													$oPost->Attachments = $aResultAttachment;
												}
											}

											$this->CreatePost($oHelpdeskUser, $oThread, $oPost, false, false);
										}
									}
								}
							}
							
							unset($oMessage);
						}
					}
				}
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function StartMailboxMonitorPrev($iIdTenant)
	{
		$aData = $this->GetHelpdeskMainSettings($iIdTenant);
		if (!empty($aData['AdminEmailAccount']) && 0 < $aData['HelpdeskFetcherType'])
		{
			$oApiUsers = $this->apiUsers();
			$oApiMail = $this->apiMail();
			$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
			$oApiFilestorage = /* @var $oApiFileCache \CApiFilestorageManager */ \CApi::Manager('filestorage');

			if ($oApiUsers && $oApiMail && $oApiFileCache)
			{
				$oAccount = $oApiUsers->GetAccountOnLogin($aData['AdminEmailAccount']);
				if ($oAccount)
				{
					$iPrevLastUid = $this->GetHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email));

					$iLastUid = 0;
					$aData = $oApiMail->HelpdeskMessagesHelper($oAccount, 0 < $iPrevLastUid ? $iPrevLastUid + 1 : 0, $iLastUid);
					if (0 < $iLastUid)
					{
						$this->SetHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email), $iLastUid);
					}

					if (is_array($aData) && 0 < count($aData))
					{
						foreach ($aData as $oMessage)
						{
							$aMatch = array();
							$oFrom = $oMessage->From();
							$aFrom = $oFrom ? $oFrom->GetAsArray() : array();
							$oAttachments = $oMessage->Attachments();
							$aAttachments = $oAttachments ? $oAttachments->GetAsArray() : array();

							$sSubject = $oMessage->Subject();
							if (is_array($aFrom) && 0 < count($aFrom) && !empty($sSubject) &&
								preg_match('/\[#([a-zA-Z0-9]+)#\]/', $sSubject, $aMatch) && !empty($aMatch[1]))
							{
								$oEmail = $aFrom[0];
								$sEmail = $oEmail ? $oEmail->GetEmail() : '';
								if (0 < \strlen($sEmail))
								{
									$oHelpdeskUser = $this->GetUserByEmail($iIdTenant, $sEmail);
									if ($oHelpdeskUser)
									{
										$sThreadHash = (string) $aMatch[1];
										if (!empty($sThreadHash))
										{
											$iThreadID = $this->GetThreadIdByHash($iIdTenant, $sThreadHash);
											if (0 < $iThreadID)
											{
												$oThread = $this->GetThreadById($oHelpdeskUser, $iThreadID);
												if ($oThread)
												{
													$sText = trim($oMessage->Html());
													if (0 === strlen($sText))
													{
														$sText = trim($oMessage->Plain());
													}
													else
													{
														$sText = \MailSo\Base\HtmlUtils::ConvertHtmlToPlain($sText);
													}

													$oPost = new \CHelpdeskPost();
													$oPost->IdTenant = $oHelpdeskUser->IdTenant;
													$oPost->IdOwner = $oHelpdeskUser->IdHelpdeskUser;
													$oPost->IdHelpdeskThread = $oThread->IdHelpdeskThread;
													$oPost->Type = \EHelpdeskPostType::Normal;
													$oPost->SystemType = \EHelpdeskPostSystemType::None;
													$oPost->Text = $sText;

													$aResultAttachment = array();
													if (is_array($aAttachments) && 0 < count($aAttachments))
													{
														foreach ($aAttachments as /* @var $oAttachment CApiMailAttachment */ $oAttachment)
														{
															$sUploadName = $oAttachment->FileName(true);
															$sTempName = md5($sUploadName.rand(1000, 9999));

															$oApiMail->MessageMimeStream($oAccount,
																function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($oHelpdeskUser, &$sTempName, $oApiFileCache) {

																	if (!$oApiFileCache->PutFile($oHelpdeskUser, $sTempName, $rResource))
																	{
																		$sTempName = '';
																	}

																}, $oAttachment->Folder(), $oAttachment->Uid(), $oAttachment->MimeIndex());


															$rData = 0 < \strlen($sTempName) ? $oApiFileCache->GetFile($oHelpdeskUser, $sTempName) : null;
															if ($rData)
															{
																$iFileSize = $oApiFileCache->FileSize($oHelpdeskUser, $sTempName);

																$sThreadID = (string) $oThread->IdHelpdeskThread;
																$sThreadID = str_pad($sThreadID, 2, '0', STR_PAD_LEFT);
																$sThreadIDSubFolder = substr($sThreadID, 0, 2);

																$sThreadFolderName = API_HELPDESK_PUBLIC_NAME.'/'.$sThreadIDSubFolder.'/'.$sThreadID;

																$oApiFilestorage->CreateFolder($oHelpdeskUser, \EFileStorageType::Corporate, '',
																	$sThreadFolderName);

																$oApiFilestorage->CreateFile($oHelpdeskUser,
																	\EFileStorageTypeStr::Corporate, $sThreadFolderName, $sUploadName, $rData, false);

																if (is_resource($rData))
																{
																	@fclose($rData);
																}

																$oAttachment = new \CHelpdeskAttachment();
																$oAttachment->IdHelpdeskThread = $oThread->IdHelpdeskThread;
																$oAttachment->IdHelpdeskPost = $oPost->IdHelpdeskPost;
																$oAttachment->IdOwner = $oHelpdeskUser->IdHelpdeskUser;
																$oAttachment->IdTenant = $oHelpdeskUser->IdTenant;

																$oAttachment->FileName = $sUploadName;
																$oAttachment->SizeInBytes = $iFileSize;
																$oAttachment->EncodeHash($oHelpdeskUser, $sThreadFolderName);

																$oApiFileCache->Clear($oHelpdeskUser, $sTempName);

																$aResultAttachment[] = $oAttachment;
															}
														}

														if (is_array($aResultAttachment) && 0 < count($aResultAttachment))
														{
															$oPost->Attachments = $aResultAttachment;
														}
													}

													$this->CreatePost($oHelpdeskUser, $oThread, $oPost, false, false);
												}
											}
										}
									}
								}
							}

							unset($oMessage);
						}
					}
				}
			}
		}

		return true;
	}

	private function getOwnerFromSearch($iIdTenant, &$sSearch)
	{
		$aMatch = array();
		$sSearch = trim($sSearch);
		if (0 < strlen($sSearch) && preg_match('/owner:[\s]?([^\s]+@[^\s]+)/', $sSearch, $aMatch) && !empty($aMatch[0]) && !empty($aMatch[1]))
		{
			$sSearch = trim(str_replace($aMatch[0], '', $sSearch));
			$sEmail = trim($aMatch[1]);
			$oUser = $this->GetUserByEmail($iIdTenant, $sEmail);
			if (!$oUser)
			{
				$oUser = $this->GetUserByNotificationEmail($iIdTenant, $sEmail);
			}

			return $oUser ? $oUser->IdHelpdeskUser : 0;
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
	 * @param int $iTenantId = 0
	 *
	 * @return int
	 */
	public function GetThreadsPendingCount($iTenantId)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->GetThreadsPendingCount($iTenantId);
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
	 * @param int $iTimeoutInMin = 15
	 *
	 * @return bool
	 */
	public function ClearAllOnline($iTimeoutInMin = 15)
	{
		$bResult = false;
		if (0 < $iTimeoutInMin)
		{
			try
			{
				$bResult = $this->oStorage->ClearAllOnline($iTimeoutInMin);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 *
	 * @return array|bool
	 */
	public function GetOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$aResult = false;
		if ($oHelpdeskUser && $oHelpdeskUser->IsAgent)
		{
			try
			{
				$aResult = $this->oStorage->GetOnline($oHelpdeskUser, $iThreadID);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $aResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 *
	 * @return bool
	 */
	public function SetOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$bResult = false;
		if ($oHelpdeskUser)
		{
			try
			{
				$bResult = $this->oStorage->SetOnline($oHelpdeskUser, $iThreadID);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $bResult;
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
			$aData = $this->GetHelpdeskMainSettings($oUser->IdTenant);
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
					$sEmail = $oUser->ResultEmail();
					if (empty($sEmail))
					{
						return false;
					}
					
					$oFromEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
					$oToEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oUser->Name);

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
	 * @param bool $bCreateFromFetcher = false
	 *
	 * @return bool
	 */
	public function NotifyRegistration($oUser, $bCreateFromFetcher = false)
	{
		if ($oUser)
		{
			$oFromAccount = null;
			$aData = $this->GetHelpdeskMainSettings($oUser->IdTenant);
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
					$sEmail = $oUser->ResultEmail();
					if (empty($sEmail))
					{
						return false;
					}

					$oFromEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
					$oToEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oUser->Name);

					$oUserMessage = $this->buildUserMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.registration'.($bCreateFromFetcher ? '.fetcher' : '').'.html',
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

			$aData = $this->GetHelpdeskMainSettings($oPost->IdTenant);
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
					$sEmail = $oHelpdeskThreadOwnerUser->ResultEmail();
					if (empty($sEmail))
					{
						return false;
					}

					$oHelpdeskSenderEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
					$oThreadOwnerEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oHelpdeskThreadOwnerUser->Name);

					if (EHelpdeskPostType::Normal === $oPost->Type && ($bIsNew || $oHelpdeskThreadOwnerUser->IdHelpdeskUser !== $oPost->IdOwner))
					{
						$oUserMessage = $this->buildPostMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.post'.($bIsNew ? '.new' : '').'.html',
							$oHelpdeskSenderEmail->ToString(), $oThreadOwnerEmail->ToString(),
							'New Post', '', '', $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName);

						if ($oUserMessage)
						{
							$aDeMail[] = $oHelpdeskThreadOwnerUser->ResultEmail();
							$oApiMail->MessageSend($oFromAccount, $oUserMessage);
						}
					}

					if (EHelpdeskPostType::Internal === $oPost->Type || $oHelpdeskThreadOwnerUser->IdHelpdeskUser === $oPost->IdOwner)
					{
						$aDeMail[] = $oHelpdeskThreadOwnerUser->ResultEmail();
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
	 * @param CHelpdeskThread $oThread
	 * 
	 * @return bool
	 */
	public function NotifyOutdated($oThread)
	{
		if ($oThread)
		{
			$oFromAccount = null;

			$aData = $this->GetHelpdeskMainSettings($oThread->IdTenant);
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
					$oHelpdeskPostOwnerUser = $this->GetUserById($oThread->IdTenant, $oThread->IdOwner);

					$sEmail = $oHelpdeskThreadOwnerUser->ResultEmail();
					if (empty($sEmail))
					{
						return false;
					}

					$oHelpdeskSenderEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
					$oThreadOwnerEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oHelpdeskThreadOwnerUser->Name);

					if ($oHelpdeskThreadOwnerUser->IdHelpdeskUser === $oThread->IdOwner)
					{
						$oUserMessage = $this->buildPostMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.post.notification.html',
							$oHelpdeskSenderEmail->ToString(), $oThreadOwnerEmail->ToString(),
							'New Post', '', '', $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, null, $sSiteName);

						if ($oUserMessage)
						{
							$oApiMail->MessageSend($oFromAccount, $oUserMessage);
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
	 * @param bool $bSendNotify = true
	 *
	 * @return bool
	 */
	public function CreatePost(CHelpdeskUser $oHelpdeskUser, $oThread, CHelpdeskPost $oPost, $bIsNew = false, $bSendNotify = true)
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
					$oThread->LastPostOwnerId = $oPost->IdOwner;
					$oThread->Notificated = false;

					if (!$oThread->HasAttachments)
					{
						$oThread->HasAttachments = is_array($oPost->Attachments) && 0 < count($oPost->Attachments);
					}

					$bResult = $this->UpdateThread($oHelpdeskUser, $oThread);
					$this->SetThreadSeen($oHelpdeskUser, $oThread);

					if ($bSendNotify)
					{
						$this->NotifyPost($oThread, $oPost, $bIsNew);
					}
				}
			}
		}
		catch (CApiBaseException $oException)
		{
			if ($oException->getCode() !== \Errs::Mail_MailboxUnavailable)
			{
				$bResult = false;
				$this->setLastException($oException);
			}
			else
			{
				$bResult = true;
			}
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
