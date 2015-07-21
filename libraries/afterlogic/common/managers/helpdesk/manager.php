<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiHelpdeskManager class summary
 *
 * @package Helpdesk
 */
class CApiHelpdeskManager extends AApiManagerWithStorage
{
	/**
	 * @var $oApiMail CApiMailManager
	 */
	private $oApiMail;

	/**
	 * @var $oApiUsers CApiUsersManager
	 */
	private $oApiUsers;

	/**
	 * @var $oApiTenants CApiTenantsManager
	 */
	private $oApiTenants;
	
	/**
	 * @param CApiGlobalManager &$oManager
	 * @param string $sForcedStorage Default value is empty string.
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
	 * Creates a new instance of Users object.
	 *
	 * @return CApiUsersManager
	 */
	private function _getApiUsers()
	{
		if (null === $this->oApiUsers)
		{
			$this->oApiUsers = CApi::Manager('users');
		}

		return $this->oApiUsers;
	}

	/**
	 * Creates a new instance of Mail object.
	 *
	 * @return CApiMailManager
	 */
	private function _getApiMail()
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
	 *
	 * @return string
	 */
	private function _getMessageTemplate($sPath, &$sSubject, $fCallback)
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

	/**
	 * @param string $sPath
	 * @param \MailSo\Mime\Message $oMessage Message object
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param string $sSiteName
	 * @param string $sFrom
	 */
	private function _addHtmlBodyAndSubjectForUserMessage($sPath, &$oMessage, $oHelpdeskUser, $sSiteName, $sFrom)
	{
		$sSubject = '';
		$oApiUsers = $this->_getApiUsers();
		
		$sData = $this->_getMessageTemplate($sPath, $sSubject, function ($sData) use ($oHelpdeskUser, $sSiteName, $sFrom, $oApiUsers) {
			
			$oAccount = $oApiUsers->getAccountByEmail($oHelpdeskUser->resultEmail());
			$sHelpdeskSiteName = strlen($sSiteName) === 0 ? 'Helpdesk' : $sSiteName;

			return strtr($sData, array(
				'{{HELPDESK/FORGOT_SUBJECT}}' => ':SUBJECT: ' . \CApi::ClientI18N('HELPDESK/MAIL_FORGOT_SUBJECT', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/FORGOT_CONFIRM}}' => \CApi::ClientI18N('HELPDESK/MAIL_FORGOT_CONFIRM', null, array("EMAIL" => $oHelpdeskUser->resultEmail(), "SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/FORGOT_PROCEED_LINK}}' => \CApi::ClientI18N('HELPDESK/MAIL_FORGOT_PROCEED_LINK'),
				'{{HELPDESK/FORGOT_LINK}}' => $oHelpdeskUser->forgotLink(),
				'{{HELPDESK/FORGOT_DISREGARD}}' => \CApi::ClientI18N('HELPDESK/MAIL_FORGOT_DISREGARD', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/FORGOT_NOT_REPLY}}' => \CApi::ClientI18N('HELPDESK/MAIL_FORGOT_NOT_REPLY'),
				'{{HELPDESK/FORGOT_REGARDS}}' => \CApi::ClientI18N('HELPDESK/MAIL_FORGOT_REGARDS'),
				'{{HELPDESK/FORGOT_SITE}}' => \CApi::ClientI18N('HELPDESK/MAIL_FORGOT_SITE', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/REG_SUBJECT}}' => ':SUBJECT: ' . \CApi::ClientI18N('HELPDESK/MAIL_REG_SUBJECT', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/REG_CONFIRM}}' => \CApi::ClientI18N('HELPDESK/MAIL_REG_CONFIRM', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/REG_PROCEED_LINK}}' => \CApi::ClientI18N('HELPDESK/MAIL_REG_PROCEED_LINK'),
				'{{HELPDESK/REG_ACTIVATION_LINK}}' => $oHelpdeskUser->activationLink(),
				'{{HELPDESK/REG_DISREGARD}}' => \CApi::ClientI18N('HELPDESK/MAIL_REG_DISREGARD'),
				'{{HELPDESK/REG_NOT_REPLY}}' => \CApi::ClientI18N('HELPDESK/MAIL_REG_NOT_REPLY'),
				'{{HELPDESK/REG_REGARDS}}' => \CApi::ClientI18N('HELPDESK/MAIL_REG_REGARDS'),
				'{{HELPDESK/REG_SITE}}' => \CApi::ClientI18N('HELPDESK/MAIL_REG_SITE', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/FETCHER_SUBJECT}}' => ':SUBJECT: ' . \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_SUBJECT', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/FETCHER_CONFIRM}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_CONFIRM', null, array("EMAIL" => $oHelpdeskUser->resultEmail(), "FROM" => $sFrom, "SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/FETCHER_NAME}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_NAME', null, array("EMAIL" => $oHelpdeskUser->resultEmail())),
				'{{HELPDESK/FETCHER_PASSWORD}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_PASSWORD', null, array("PASSWORD" => $oHelpdeskUser->NotificationPassword)),
				'{{HELPDESK/FETCHER_PROCEED_LINK}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_PROCEED_LINK'),
				'{{HELPDESK/FETCHER_ACTIVATION_LINK}}' => $oHelpdeskUser->activationLink(),
				'{{HELPDESK/FETCHER_HELPDESK_LINK}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_HELPDESK_LINK', null, array("LINK" => $oHelpdeskUser->helpdeskLink())),
				'{{HELPDESK/FETCHER_DISREGARD}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_DISREGARD', null, array("FROM" => $sFrom)),
				'{{HELPDESK/FETCHER_REGARDS}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_REGARDS'),
				'{{HELPDESK/FETCHER_SITE}}' => \CApi::ClientI18N('HELPDESK/MAIL_FETCHER_SITE', null, array("SITE" => $sHelpdeskSiteName)),
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
	 * @param string $sPath
	 * @param \MailSo\Mime\Message $oMessage Message object
	 * @param CHelpdeskUser $oHelpdeskThreadOwnerUser Helpdesk user object
	 * @param CHelpdeskUser $oHelpdeskPostOwnerUser Helpdesk user object
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 * @param CHelpdeskPost $oPost Helpdesk post object
	 * @param string $sSiteName
	 */
	private function _addHtmlBodyAndSubjectForPostMessage($sPath, &$oMessage, $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName)
	{
		$sSubject = '';
		$oApiUsers = $this->_getApiUsers();
			
		$sData = $this->_getMessageTemplate($sPath, $sSubject, function ($sData) use ($oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName, $oApiUsers)
		{
			$oAccount = $oApiUsers->getAccountByEmail($oHelpdeskPostOwnerUser->resultEmail());
			$sPostOwner = \MailSo\Mime\Email::NewInstance($oHelpdeskPostOwnerUser->resultEmail(), $oHelpdeskPostOwnerUser->Name)->ToString();

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

			$sHelpdeskSiteName = strlen($sSiteName) === 0 ? 'Helpdesk' : $sSiteName;
			$sThreadOwner = $oHelpdeskThreadOwnerUser && \strlen($oHelpdeskThreadOwnerUser->Name) > 0 ? ' '.$oHelpdeskThreadOwnerUser->Name : '';

			return strtr($sData, array(
				'{{HELPDESK/POST_AGENT_SUBJECT}}' => ':SUBJECT: ' . \CApi::ClientI18N('HELPDESK/MAIL_POST_AGENT_SUBJECT', null, array("OWNER" => $sPostOwner)),
				'{{HELPDESK/POST_AGENT_HTML}}' => $oPost ? \MailSo\Base\HtmlUtils::ConvertPlainToHtml($oPost->Text) : '',
				'{{HELPDESK/POST_AGENT_ATTACHMENTS}}' => $sAttachments,
				'{{HELPDESK/POST_AGENT_THREAD_LINK}}' => $oThread->threadLink(),
				'{{HELPDESK/POST_USER_SUBJECT}}' => $sSubjectPrefix.$oThread->Subject,
				'{{HELPDESK/POST_USER_GREET}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_USER_GREET', null, array("OWNER" => $sPostOwner)),
				'{{HELPDESK/POST_USER_REMIND}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_USER_REMIND', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/POST_USER_THREAD_SUBJECT_LABEL}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_USER_THREAD_SUBJECT_LABEL'),
				'{{HELPDESK/POST_USER_THREAD_SUBJECT}}' => $oThread->Subject,
				'{{HELPDESK/POST_USER_HTML}}' => $oPost ? \MailSo\Base\HtmlUtils::ConvertPlainToHtml($oPost->Text) : '',
				'{{HELPDESK/POST_USER_NOT_REPLY}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_USER_NOT_REPLY'),
				'{{HELPDESK/POST_USER_CLICK_LINK}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_USER_CLICK_LINK'),
				'{{HELPDESK/POST_USER_THREAD_LINK}}' => $oThread->threadLink(),
				'{{HELPDESK/POST_NEW_SUBJECT}}' => ':SUBJECT: ' . \CApi::ClientI18N('HELPDESK/MAIL_POST_NEW_SUBJECT'),
				'{{HELPDESK/POST_NEW_GREET}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NEW_GREET', null, array("OWNER" => $sThreadOwner)),
				'{{HELPDESK/POST_NEW_REMIND}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NEW_REMIND', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/POST_NEW_HTML}}' => $oPost ? \MailSo\Base\HtmlUtils::ConvertPlainToHtml($oPost->Text) : '',
				'{{HELPDESK/POST_NEW_NOT_REPLY}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NEW_NOT_REPLY'),
				'{{HELPDESK/POST_NEW_CLICK_LINK}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NEW_CLICK_LINK'),
				'{{HELPDESK/POST_NEW_THREAD_LINK}}' => $oThread->threadLink(),
				'{{HELPDESK/POST_NOTIFICATION_SUBJECT}}' => ':SUBJECT: ' . \CApi::ClientI18N('HELPDESK/MAIL_POST_NOTIFICATION_SUBJECT'),
				'{{HELPDESK/POST_NOTIFICATION_GREET}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NOTIFICATION_GREET', null, array("OWNER" => $sThreadOwner)),
				'{{HELPDESK/POST_NOTIFICATION_REMIND}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NOTIFICATION_REMIND', null, array("SITE" => $sHelpdeskSiteName)),
				'{{HELPDESK/POST_NOTIFICATION_QUESTIONS}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NOTIFICATION_QUESTIONS'),
				'{{HELPDESK/POST_NOTIFICATION_CLOSE}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NOTIFICATION_CLOSE'),
				'{{HELPDESK/POST_NOTIFICATION_NOT_REPLY}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NOTIFICATION_NOT_REPLY'),
				'{{HELPDESK/POST_NOTIFICATION_CLICK_LINK}}' => \CApi::ClientI18N('HELPDESK/MAIL_POST_NOTIFICATION_CLICK_LINK'),
				'{{HELPDESK/POST_NOTIFICATION_THREAD_LINK}}' => $oThread->threadLink(),
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
	 * @param string $sCc Default value is empty string.
	 * @param string $sBcc Default value is empty string.
	 * @param string $sMessageID Default value is empty string.
	 * @param string $sReferences Default value is empty string.
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function _buildMail($sFrom, $sTo, $sSubject, $sCc = '', $sBcc = '', $sMessageID = '', $sReferences = '')
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

	/**
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 * @param string $sMessageID
	 * @param string $sReferences
	 */
	private function _initMessageIdAndReferences($oThread, &$sMessageID, &$sReferences)
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
	 * @param string $sPath
	 * @param string $sFrom
	 * @param string $sTo
	 * @param string $sSubject
	 * @param string $sCc
	 * @param string $sBcc
	 * @param CHelpdeskUser $oHelpdeskThreadOwnerUser Helpdesk user object
	 * @param CHelpdeskUser $oHelpdeskPostOwnerUser Helpdesk user object
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 * @param CHelpdeskPost $oPost Helpdesk post object
	 * @param string $sSiteName
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function _buildPostMail($sPath, $sFrom, $sTo, $sSubject, $sCc, $sBcc, $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName)
	{
		$sMessageID = '';
		$sReferences = '';

		$this->_initMessageIdAndReferences($oThread, $sMessageID, $sReferences);

		$oMessage = $this->_buildMail($sFrom, $sTo, $sSubject, $sCc, $sBcc, $sMessageID, $sReferences);

		$this->_addHtmlBodyAndSubjectForPostMessage($sPath, $oMessage, $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName);

		return $oMessage;
	}

	/**
	 * @param string $sPath
	 * @param string $sFrom
	 * @param string $sTo
	 * @param string $sSubject
	 * @param string $sCc
	 * @param string $sBcc
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param string $sSiteName
	 *
	 * @return \MailSo\Mime\Message
	 */
	private function _buildUserMailMail($sPath, $sFrom, $sTo, $sSubject, $sCc, $sBcc, $oHelpdeskUser, $sSiteName)
	{
		$oMessage = $this->_buildMail($sFrom, $sTo, $sSubject, $sCc, $sBcc);
		
		$this->_addHtmlBodyAndSubjectForUserMessage($sPath, $oMessage, $oHelpdeskUser, $sSiteName, $sFrom);

		return $oMessage;
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sSearch
	 *
	 * @return int
	 */
	private function _getOwnerFromSearch($iIdTenant, &$sSearch)
	{
		$aMatch = array();
		$sSearch = trim($sSearch);
		if (0 < strlen($sSearch) && preg_match('/owner:[\s]?([^\s]+@[^\s]+)/', $sSearch, $aMatch) && !empty($aMatch[0]) && !empty($aMatch[1]))
		{
			$sSearch = trim(str_replace($aMatch[0], '', $sSearch));
			$sEmail = trim($aMatch[1]);
			$oUser = $this->getUserByEmail($iIdTenant, $sEmail);
			if (!$oUser)
			{
				$oUser = $this->getUserByNotificationEmail($iIdTenant, $sEmail);
			}

			return $oUser ? $oUser->IdHelpdeskUser : 0;
		}

		return 0;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param bool $bCreateFromFetcher Default value is **false**.
	 * 
	 * @return bool
	 */
	public function createUser(CHelpdeskUser &$oHelpdeskUser, $bCreateFromFetcher = false)
	{
		$bResult = false;
		try
		{
			if ($oHelpdeskUser->validate())
			{
				if (!$this->isUserExists($oHelpdeskUser))
				{
					if (!$this->oStorage->createUser($oHelpdeskUser))
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
	public function getUserById($iIdTenant, $iHelpdeskUserId)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->getUserById($iIdTenant, $iHelpdeskUserId);
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
	public function getUserByIdWithoutTenantID($iHelpdeskUserId)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->getUserByIdWithoutTenantID($iHelpdeskUserId);
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
	public function getUserByActivateHash($iIdTenant, $sActivateHash)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->getUserByActivateHash($iIdTenant, $sActivateHash);
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
	public function getHelpdeskMainSettings($iIdTenant)
	{
		$oApiTenant = CApi::Manager('tenants');
		$oTenant = /* @var $oTenant CTenant */ $oApiTenant ? 
			(0 < $iIdTenant ? $oApiTenant->getTenantById($iIdTenant) : $oApiTenant->getDefaultGlobalTenant()) : null;

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
			$sStyleText = $oTenant->getHelpdeskStyleText();

			$iHelpdeskFetcherType = $oTenant->HelpdeskFetcherType;
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
	public function getAgentsEmailsForNotification($iIdTenant, $aExcludeEmails = array())
	{
		$aResult = array();
		try
		{
			$aResult = $this->oStorage->getAgentsEmailsForNotification($iIdTenant, $aExcludeEmails);
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
	public function getUserByEmail($iIdTenant, $sEmail)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->getUserByEmail($iIdTenant, $sEmail);
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
	public function getUserByNotificationEmail($iIdTenant, $sEmail)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->getUserByNotificationEmail($iIdTenant, $sEmail);
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
	 * @param string $sSocialId
	 *
	 * @return CHelpdeskUser|null|false
	 */
	public function getUserBySocialId($iIdTenant, $sSocialId)
	{
		$oUser = null;
		try
		{
			$oUser = $this->oStorage->getUserBySocialId($iIdTenant, $sSocialId);
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
	 *
	 * @return bool
	 */
	public function forgotUser($oHelpdeskUser)
	{
		$this->NotifyForgot($oHelpdeskUser);
		return true;
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 *
	 * @return bool
	 */
	public function isUserExists(CHelpdeskUser $oHelpdeskUser)
	{
		$bResult = false;
		if(!$oHelpdeskUser->SocialId)
		{
			try
			{
				$bResult = $this->oStorage->isUserExists($oHelpdeskUser);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param array $aIdList
	 *
	 * @return array|bool
	 */
	public function userInformation(CHelpdeskUser $oHelpdeskUser, $aIdList)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->userInformation($oHelpdeskUser, $aIdList);
		}
		catch (CApiBaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
     *
	 * @return bool
	 */
	public function updateUser(CHelpdeskUser $oHelpdeskUser)
	{
		$bResult = false;
		try
		{
			if ($oHelpdeskUser->validate())
			{
				$bResult = $this->oStorage->updateUser($oHelpdeskUser);
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
	 *
	 * @return bool
	 */
	public function setUserAsBlocked($iIdTenant, $iIdHelpdeskUser)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->setUserAsBlocked($iIdTenant, $iIdHelpdeskUser);
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
	 *
	 * @return bool
	 */
	public function deleteUser($iIdTenant, $iIdHelpdeskUser)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->deleteUser($iIdTenant, $iIdHelpdeskUser);
			/*if ($bResult)
			{
				//TODO
			}*/
		}
		catch (CApiBaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 * @param array $aPostIds
	 *
	 * @return bool
	 */
	public function deletePosts(CHelpdeskUser $oHelpdeskUser, $oThread, $aPostIds)
	{
		$bResult = false;
		try
		{
			if ($oThread instanceof CHelpdeskThread && 0 < count($aPostIds))
			{
				$bResult = $this->oStorage->deletePosts($oHelpdeskUser, $oThread, $aPostIds);
				if ($bResult)
				{
					$oThread->PostCount = $this->getPostsCount($oHelpdeskUser, $oThread);
					$bResult = $this->updateThread($oHelpdeskUser, $oThread);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param array $aThreadIds
	 *
	 * @return bool
	 */
	public function verifyThreadIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$bResult = false;
		try
		{
			if (0 < count($aThreadIds))
			{
				$bResult = $this->oStorage->verifyThreadIdsBelongToUser($oHelpdeskUser, $aThreadIds);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param array $aPostIds
	 *
	 * @return bool
	 */
	public function verifyPostIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aPostIds)
	{
		$bResult = false;
		try
		{
			if (0 < count($aPostIds))
			{
				$bResult = $this->oStorage->verifyPostIdsBelongToUser($oHelpdeskUser, $aPostIds);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param array $aThreadIds
	 * @param bool $bSetArchive = true
	 *
	 * @return bool
	 */
	public function archiveThreads(CHelpdeskUser $oHelpdeskUser, $aThreadIds, $bSetArchive = true)
	{
		$bResult = false;
		try
		{
			if (0 < count($aThreadIds))
			{
				$bResult = $this->oStorage->archiveThreads($oHelpdeskUser, $aThreadIds, $bSetArchive);
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
	public function archiveOutdatedThreads()
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->archiveOutdatedThreads();
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
	public function notificateOutdatedThreads()
	{
		$bResult = false;
		try
		{
			$iIdOwner = 0;
			$iIdHelpdeskThread = $this->oStorage->notificateOutdatedThreadID($iIdOwner);
			if ($iIdHelpdeskThread && $iIdOwner)
			{
				$oHelpdeskUser = $this->getUserByIdWithoutTenantID($iIdOwner);
				if ($oHelpdeskUser)
				{
					$oThread = $this->getThreadById($oHelpdeskUser, $iIdHelpdeskThread);
					if ($oThread)
					{
						$this->notifyOutdated($oThread);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param int $iIdThread
	 *
	 * @return CHelpdeskThread|false
	 */
	public function getThreadById($oHelpdeskUser, $iIdThread)
	{
		$oThread = null;
		try
		{
			$oThread = $this->oStorage->getThreadById($oHelpdeskUser, $iIdThread);
			if ($oThread)
			{
				$aThreadLastPostIds = $this->getThreadsLastPostIds($oHelpdeskUser, array($oThread->IdHelpdeskThread));
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
	public function getThreadIdByHash($iTenantID, $sHash)
	{
		$iThreadID = 0;
		try
		{
			$iThreadID = $this->oStorage->getThreadIdByHash($iTenantID, $sHash);
		}
		catch (CApiBaseException $oException)
		{
			$iThreadID = 0;
			$this->setLastException($oException);
		}
		return $iThreadID;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oHelpdeskThread Helpdesk thread object
	 *
	 * @return bool
	 */
	public function createThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread &$oHelpdeskThread)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->createThread($oHelpdeskUser, $oHelpdeskThread);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oHelpdeskThread Helpdesk thread object
	 *
	 * @return bool
	 */
	public function updateThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->updateThread($oHelpdeskUser, $oHelpdeskThread);
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
	public function getNextHelpdeskIdForMonitoring()
	{
		$mResult = false;
		if (CApi::GetConf('helpdesk', false))
		{
			if (CApi::GetConf('tenant', false))
			{
				try
				{
					$mResult = $this->oStorage->getNextHelpdeskIdForMonitoring(
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
	public function startHelpdeskMailboxMonitor()
	{
		$iIdTenant = $this->getNextHelpdeskIdForMonitoring();
		if (false !== $iIdTenant)
		{
			$this->oStorage->updateHelpdeskFetcherTimer($iIdTenant);
			$this->startMailboxMonitor($iIdTenant);
		}

		return true;
	}

	/**
	 * @return int
	 */
	public function getHelpdeskMailboxLastUid($iIdTenant, $sEmail)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getHelpdeskMailboxLastUid($iIdTenant, $sEmail);
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
	public function setHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->setHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param int $iIdTenant
	 *
	 * @return bool
	 */
	public function startMailboxMonitor($iIdTenant)
	{
		$aMainSettingsData = $this->getHelpdeskMainSettings($iIdTenant);
		if (!empty($aMainSettingsData['AdminEmailAccount']) && 0 < $aMainSettingsData['HelpdeskFetcherType'])
		{
			$oApiUsers = $this->_getApiUsers();
			$oApiMail = $this->_getApiMail();
			
			$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
			$oApiFilestorage = /* @var $oApiFileCache \CApiFilestorageManager */ \CApi::Manager('filestorage');
			$oApiIntegrator = /* @var $oApiIntegrator \CApiIntegratorManager */ \CApi::Manager('integrator');
			
			if ($oApiUsers && $oApiMail && $oApiFileCache)
			{
				$oAccount = $oApiUsers->getAccountByEmail($aMainSettingsData['AdminEmailAccount']);
				if ($oAccount)
				{
					$iPrevLastUid = $this->getHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email));
					
					$iLastUid = 0;
					$aData = $oApiMail->getMessagesForHelpdeskSynch($oAccount, 0 < $iPrevLastUid ? $iPrevLastUid + 1 : 0, $iLastUid);
					if (0 < $iLastUid)
					{
						$this->setHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email), $iLastUid);
					}

					if (is_array($aData) && 0 < count($aData))
					{
						foreach ($aData as $oMessage)
						{
							$aMatch = array();
							$oFrom = $oMessage->getFrom();
							$aFrom = $oFrom ? $oFrom->GetAsArray() : array();
							$oAttachments = $oMessage->getAttachments();
							$aAttachments = $oAttachments ? $oAttachments->GetAsArray() : array();

							$sSubject = $oMessage->getSubject();
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
									$oHelpdeskUser = $this->getUserByEmail($iIdTenant, $sEmail);
									if (!$oHelpdeskUser)
									{
										$sPassword = md5(microtime(true));
										$oHelpdeskUser = $oApiIntegrator->registerHelpdeskAccount($iIdTenant, $sEmail, '', $sPassword, true);
									}
								
									if ($oHelpdeskUser)
									{
										$oThread = null;
										if (!empty($sThreadHash))
										{
											$iThreadID = $this->getThreadIdByHash($iIdTenant, $sThreadHash);
											if (0 < $iThreadID)
											{
												$oThread = $this->getThreadById($oHelpdeskUser, $iThreadID);
											}
										}
										else
										{
											$oThread = new \CHelpdeskThread();
											$oThread->IdTenant = $iIdTenant;
											$oThread->IdOwner = $oHelpdeskUser->IdHelpdeskUser;
											$oThread->Type = \EHelpdeskThreadType::Pending;
											$oThread->Subject = $sSubject;

											if (!$this->createThread($oHelpdeskUser, $oThread))
											{
												$oThread = null;
											}
										}

										if ($oThread)
										{
											$sText = trim($oMessage->getHtml());
											if (0 === strlen($sText))
											{
												$sText = trim($oMessage->getPlain());
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
													$sUploadName = $oAttachment->getFileName(true);
													$sTempName = md5($sUploadName.rand(1000, 9999));

													$oApiMail->directMessageToStream($oAccount,
														function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($oHelpdeskUser, &$sTempName, $oApiFileCache) {

															if (!$oApiFileCache->putFile($oHelpdeskUser, $sTempName, $rResource))
															{
																$sTempName = '';
															}

														}, $oAttachment->getFolder(), $oAttachment->getUid(), $oAttachment->MimeIndex());


													$rData = 0 < \strlen($sTempName) ? $oApiFileCache->getFile($oHelpdeskUser, $sTempName) : null;
													if ($rData)
													{
														$iFileSize = $oApiFileCache->fileSize($oHelpdeskUser, $sTempName);

														$sThreadID = (string) $oThread->IdHelpdeskThread;
														$sThreadID = str_pad($sThreadID, 2, '0', STR_PAD_LEFT);
														$sThreadIDSubFolder = substr($sThreadID, 0, 2);

														$sThreadFolderName = API_HELPDESK_PUBLIC_NAME.'/'.$sThreadIDSubFolder.'/'.$sThreadID;

														$oApiFilestorage->createFolder($oHelpdeskUser, \EFileStorageTypeStr::Corporate, '',
															$sThreadFolderName);

														$oApiFilestorage->createFile($oHelpdeskUser,
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
														$oAttachment->encodeHash($oHelpdeskUser, $sThreadFolderName);

														$oApiFileCache->clear($oHelpdeskUser, $sTempName);

														$aResultAttachment[] = $oAttachment;
													}
												}

												if (is_array($aResultAttachment) && 0 < count($aResultAttachment))
												{
													$oPost->Attachments = $aResultAttachment;
												}
											}

											$this->createPost($oHelpdeskUser, $oThread, $oPost, false, false);
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
	 * @param int $iIdTenant
	 *
	 * @return bool
	 */
	public function startMailboxMonitorPrev($iIdTenant)
	{
		$aData = $this->getHelpdeskMainSettings($iIdTenant);
		if (!empty($aData['AdminEmailAccount']) && 0 < $aData['HelpdeskFetcherType'])
		{
			$oApiUsers = $this->_getApiUsers();
			$oApiMail = $this->_getApiMail();
			$oApiFileCache = /* @var $oApiFileCache \CApiFilecacheManager */ \CApi::Manager('filecache');
			$oApiFilestorage = /* @var $oApiFileCache \CApiFilestorageManager */ \CApi::Manager('filestorage');

			if ($oApiUsers && $oApiMail && $oApiFileCache)
			{
				$oAccount = $oApiUsers->getAccountByEmail($aData['AdminEmailAccount']);
				if ($oAccount)
				{
					$iPrevLastUid = $this->getHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email));

					$iLastUid = 0;
					$aData = $oApiMail->getMessagesForHelpdeskSynch($oAccount, 0 < $iPrevLastUid ? $iPrevLastUid + 1 : 0, $iLastUid);
					if (0 < $iLastUid)
					{
						$this->setHelpdeskMailboxLastUid($iIdTenant, \strtolower($oAccount->Email), $iLastUid);
					}

					if (is_array($aData) && 0 < count($aData))
					{
						foreach ($aData as $oMessage)
						{
							$aMatch = array();
							$oFrom = $oMessage->getFrom();
							$aFrom = $oFrom ? $oFrom->GetAsArray() : array();
							$oAttachments = $oMessage->getAttachments();
							$aAttachments = $oAttachments ? $oAttachments->GetAsArray() : array();

							$sSubject = $oMessage->getSubject();
							if (is_array($aFrom) && 0 < count($aFrom) && !empty($sSubject) &&
								preg_match('/\[#([a-zA-Z0-9]+)#\]/', $sSubject, $aMatch) && !empty($aMatch[1]))
							{
								$oEmail = $aFrom[0];
								$sEmail = $oEmail ? $oEmail->GetEmail() : '';
								if (0 < \strlen($sEmail))
								{
									$oHelpdeskUser = $this->getUserByEmail($iIdTenant, $sEmail);
									if ($oHelpdeskUser)
									{
										$sThreadHash = (string) $aMatch[1];
										if (!empty($sThreadHash))
										{
											$iThreadID = $this->getThreadIdByHash($iIdTenant, $sThreadHash);
											if (0 < $iThreadID)
											{
												$oThread = $this->getThreadById($oHelpdeskUser, $iThreadID);
												if ($oThread)
												{
													$sText = trim($oMessage->getHtml());
													if (0 === strlen($sText))
													{
														$sText = trim($oMessage->getPlain());
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
															$sUploadName = $oAttachment->getFileName(true);
															$sTempName = md5($sUploadName.rand(1000, 9999));

															$oApiMail->directMessageToStream($oAccount,
																function($rResource, $sContentType, $sFileName, $sMimeIndex = '') use ($oHelpdeskUser, &$sTempName, $oApiFileCache) {

																	if (!$oApiFileCache->putFile($oHelpdeskUser, $sTempName, $rResource))
																	{
																		$sTempName = '';
																	}

																}, $oAttachment->getFolder(), $oAttachment->getUid(), $oAttachment->MimeIndex());


															$rData = 0 < \strlen($sTempName) ? $oApiFileCache->getFile($oHelpdeskUser, $sTempName) : null;
															if ($rData)
															{
																$iFileSize = $oApiFileCache->fileSize($oHelpdeskUser, $sTempName);

																$sThreadID = (string) $oThread->IdHelpdeskThread;
																$sThreadID = str_pad($sThreadID, 2, '0', STR_PAD_LEFT);
																$sThreadIDSubFolder = substr($sThreadID, 0, 2);

																$sThreadFolderName = API_HELPDESK_PUBLIC_NAME.'/'.$sThreadIDSubFolder.'/'.$sThreadID;

																$oApiFilestorage->createFolder($oHelpdeskUser, \EFileStorageType::Corporate, '',
																	$sThreadFolderName);

																$oApiFilestorage->createFile($oHelpdeskUser,
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
																$oAttachment->encodeHash($oHelpdeskUser, $sThreadFolderName);

																$oApiFileCache->clear($oHelpdeskUser, $sTempName);

																$aResultAttachment[] = $oAttachment;
															}
														}

														if (is_array($aResultAttachment) && 0 < count($aResultAttachment))
														{
															$oPost->Attachments = $aResultAttachment;
														}
													}

													$this->createPost($oHelpdeskUser, $oThread, $oPost, false, false);
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

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param int $iFilter Default value is **0** EHelpdeskThreadFilterType::All.
	 * @param string $sSearch = ''
	 * 
	 * @return int
	 */
	public function getThreadsCount(CHelpdeskUser $oHelpdeskUser, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '')
	{
		$iResult = 0;
		try
		{
			$iSearchOwner = $this->_getOwnerFromSearch($oHelpdeskUser->IdTenant, $sSearch);
			$iResult = $this->oStorage->getThreadsCount($oHelpdeskUser, $iFilter, $sSearch, $iSearchOwner);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return int
	 */
	public function getThreadsPendingCount($iTenantId)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getThreadsPendingCount($iTenantId);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param int $iOffset Default value is **0**.
	 * @param int $iLimit Default value is **20**.
	 * @param int $iFilter Default value is **0** EHelpdeskThreadFilterType::All
	 * @param string $sSearch Default value is empty string.
	 *
	 * @return array|bool
	 */
	public function getThreads(CHelpdeskUser $oHelpdeskUser, $iOffset = 0, $iLimit = 20, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '')
	{
		$aResult = null;
		try
		{
			$iSearchOwner = $this->_getOwnerFromSearch($oHelpdeskUser->IdTenant, $sSearch);
			$aResult = $this->oStorage->getThreads($oHelpdeskUser, $iOffset, $iLimit, $iFilter, $sSearch, $iSearchOwner);
			if (is_array($aResult) && 0 < count($aResult))
			{
				$aThreadsIdList = array();
				foreach ($aResult as $oItem)
				{
					$aThreadsIdList[] = $oItem->IdHelpdeskThread;
				}
				
				$aThreadLastPostIds = $this->getThreadsLastPostIds($oHelpdeskUser, $aThreadsIdList);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 *
	 * @return int
	 */
	public function getPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$iResult = 0;
		try
		{
			$iResult = $this->oStorage->getPostsCount($oHelpdeskUser, $oThread);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $iResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param array $aThreadIds
	 *
	 * @return array|bool
	 */
	public function getThreadsLastPostIds(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getThreadsLastPostIds($oHelpdeskUser, $aThreadIds);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oHelpdeskThread Helpdesk thread object
	 *
	 * @return array|bool
	 */
	public function getAttachments(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aResult = null;
		try
		{
			$aResult = $this->oStorage->getAttachments($oHelpdeskUser, $oHelpdeskThread);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 * @param int $iStartFromId Default value is **0**.
	 * @param int $iLimit Default value is **20**.
	 *
	 * @return array|bool
	 */
	public function getPosts(CHelpdeskUser $oHelpdeskUser, $oThread, $iStartFromId = 0, $iLimit = 20)
	{
		$aResult = null;
		try
		{
			$aResult = $this->oStorage->getPosts($oHelpdeskUser, $oThread, $iStartFromId, $iLimit);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 *
	 * @return array|bool
	 */
	public function getExtPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$aResult = null;
		try
		{
			$aResult = $this->oStorage->getExtPostsCount($oHelpdeskUser, $oThread);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $aResult;
	}

	/**
	 * @param int $iTimeoutInMin Default value is **15**.
	 *
	 * @return bool
	 */
	public function clearAllOnline($iTimeoutInMin = 15)
	{
		$bResult = false;
		if (0 < $iTimeoutInMin)
		{
			try
			{
				$bResult = $this->oStorage->clearAllOnline($iTimeoutInMin);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param int $iThreadID
	 *
	 * @return array|bool
	 */
	public function getOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$aResult = false;
		if ($oHelpdeskUser && $oHelpdeskUser->IsAgent)
		{
			try
			{
				$aResult = $this->oStorage->getOnline($oHelpdeskUser, $iThreadID);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $aResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param int $iThreadID
	 *
	 * @return bool
	 */
	public function setOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$bResult = false;
		if ($oHelpdeskUser)
		{
			try
			{
				$bResult = $this->oStorage->setOnline($oHelpdeskUser, $iThreadID);
			}
			catch (CApiBaseException $oException)
			{
				$this->setLastException($oException);
			}
		}

		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 */
	public function NotifyForgot($oHelpdeskUser)
	{
		if ($oHelpdeskUser)
		{
			$oFromAccount = null;
			$aData = $this->getHelpdeskMainSettings($oHelpdeskUser->IdTenant);
			if (!empty($aData['AdminEmailAccount']))
			{
				$oApiUsers = $this->_getApiUsers();
				if ($oApiUsers)
				{
					$oFromAccount = $oApiUsers->getAccountByEmail($aData['AdminEmailAccount']);
				}
			}

			$sSiteName = isset($aData['SiteName']) ? $aData['SiteName'] : '';

			if ($oFromAccount)
			{
				$oApiMail = $this->_getApiMail();
				if ($oApiMail)
				{
					$sEmail = $oHelpdeskUser->resultEmail();
					if (!empty($sEmail))
					{
						$oFromEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
						$oToEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oHelpdeskUser->Name);

						$oUserMessage = $this->_buildUserMailMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.forgot.html',
							$oFromEmail->ToString(), $oToEmail->ToString(),
							'Forgot', '', '', $oHelpdeskUser, $sSiteName);

						$oApiMail->sendMessage($oFromAccount, $oUserMessage);
					}
				}
			}
		}
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param bool $bCreateFromFetcher Default value is **false**.
	 *
	 * @return bool
	 */
	public function NotifyRegistration($oHelpdeskUser, $bCreateFromFetcher = false)
	{
		if ($oHelpdeskUser)
		{
			$oFromAccount = null;
			$aData = $this->getHelpdeskMainSettings($oHelpdeskUser->IdTenant);
			if (!empty($aData['AdminEmailAccount']))
			{
				$oApiUsers = $this->_getApiUsers();
				if ($oApiUsers)
				{
					$oFromAccount = $oApiUsers->getAccountByEmail($aData['AdminEmailAccount']);
				}
			}

			$sSiteName = isset($aData['SiteName']) ? $aData['SiteName'] : '';

			if ($oFromAccount)
			{
				$oApiMail = $this->_getApiMail();
				if ($oApiMail)
				{
					$sEmail = $oHelpdeskUser->resultEmail();
					if (!empty($sEmail))
					{
						$oFromEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
						$oToEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oHelpdeskUser->Name);

						$oUserMessage = $this->_buildUserMailMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.registration'.($bCreateFromFetcher ? '.fetcher' : '').'.html',
							$oFromEmail->ToString(), $oToEmail->ToString(), 'Registration', '', '', $oHelpdeskUser, $sSiteName);

						$oApiMail->sendMessage($oFromAccount, $oUserMessage);
					}
				}
			}
		}
	}

	/**
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 * @param CHelpdeskPost $oPost Helpdesk post object
	 * @param bool $bIsNew Default value is **false**.
	 * @param string $sCc Default value is empty string.
	 * @param string $sBcc Default value is empty string.
	 */
	public function sendPostNotify($oThread, $oPost, $bIsNew = false, $sCc, $sBcc)
	{
		if ($oThread && $oPost)
		{
			$oFromAccount = null;

			$aData = $this->getHelpdeskMainSettings($oPost->IdTenant);
			if (!empty($aData['AdminEmailAccount']))
			{
				$oApiUsers = $this->_getApiUsers();
				if ($oApiUsers)
				{
					$oFromAccount = $oApiUsers->getAccountByEmail($aData['AdminEmailAccount']);
				}
			}

			$sSiteName = isset($aData['SiteName']) ? $aData['SiteName'] : '';

			$oHelpdeskThreadOwnerUser = $this->getUserById($oThread->IdTenant, $oThread->IdOwner);

			// mail notifications
			if ($oFromAccount && $oHelpdeskThreadOwnerUser)
			{
				$oApiMail = $this->_getApiMail();
				if ($oApiMail)
				{
					$oHelpdeskPostOwnerUser = $this->getUserById($oPost->IdTenant, $oPost->IdOwner);

					$aDeMail = array();
					$sEmail = $oHelpdeskThreadOwnerUser->resultEmail();
					if (!empty($sEmail))
					{
						$oHelpdeskSenderEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
						$oThreadOwnerEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oHelpdeskThreadOwnerUser->Name);

						if (EHelpdeskPostType::Normal === $oPost->Type && ($bIsNew || $oHelpdeskThreadOwnerUser->IdHelpdeskUser !== $oPost->IdOwner))
						{
							$oUserMessage = $this->_buildPostMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.post'.($bIsNew ? '.new' : '').'.html',
								$oHelpdeskSenderEmail->ToString(), $oThreadOwnerEmail->ToString(),
								'New Post', $sCc, $sBcc, $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName);

							if ($oUserMessage)
							{
								$aDeMail[] = $oHelpdeskThreadOwnerUser->resultEmail();
								$oApiMail->sendMessage($oFromAccount, $oUserMessage);
							}
						}

						if (EHelpdeskPostType::Internal === $oPost->Type || $oHelpdeskThreadOwnerUser->IdHelpdeskUser === $oPost->IdOwner)
						{
							$aDeMail[] = $oHelpdeskThreadOwnerUser->resultEmail();
						}

						if (0 < count($aDeMail))
						{
							$aDeMail = array_unique($aDeMail);
						}

						$aAgents = $this->getAgentsEmailsForNotification($oPost->IdTenant, $aDeMail);
						if (is_array($aAgents) && 0 < count($aAgents))
						{
							$oAgentMessage = $this->_buildPostMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/agent.post.html',
								$oHelpdeskSenderEmail->ToString(), is_array($aAgents) && 0 < count($aAgents) ? implode(', ', $aAgents) : '',
								'New Post', $sCc, $sBcc, $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, $oPost, $sSiteName);

							if ($oAgentMessage)
							{
								$oApiMail->sendMessage($oFromAccount, $oAgentMessage);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 */
	public function notifyOutdated($oThread)
	{
		if ($oThread)
		{
			$oFromAccount = null;

			$aData = $this->getHelpdeskMainSettings($oThread->IdTenant);
			if (!empty($aData['AdminEmailAccount']))
			{
				$oApiUsers = $this->_getApiUsers();
				if ($oApiUsers)
				{
					$oFromAccount = $oApiUsers->getAccountByEmail($aData['AdminEmailAccount']);
				}
			}

			$sSiteName = isset($aData['SiteName']) ? $aData['SiteName'] : '';

			$oHelpdeskThreadOwnerUser = $this->getUserById($oThread->IdTenant, $oThread->IdOwner);

			// mail notifications
			if ($oFromAccount && $oHelpdeskThreadOwnerUser)
			{
				$oApiMail = $this->_getApiMail();
				if ($oApiMail)
				{
					$oHelpdeskPostOwnerUser = $this->getUserById($oThread->IdTenant, $oThread->IdOwner);

					$sEmail = $oHelpdeskThreadOwnerUser->resultEmail();
					if (!empty($sEmail))
					{
						$oHelpdeskSenderEmail = \MailSo\Mime\Email::NewInstance($oFromAccount->Email, $sSiteName);
						$oThreadOwnerEmail = \MailSo\Mime\Email::NewInstance($sEmail, $oHelpdeskThreadOwnerUser->Name);

						if ($oHelpdeskThreadOwnerUser->IdHelpdeskUser === $oThread->IdOwner)
						{
							$oUserMessage = $this->_buildPostMail(PSEVEN_APP_ROOT_PATH.'templates/helpdesk/user.post.notification.html',
								$oHelpdeskSenderEmail->ToString(), $oThreadOwnerEmail->ToString(),
								'New Post', '', '', $oHelpdeskThreadOwnerUser, $oHelpdeskPostOwnerUser, $oThread, null, $sSiteName);

							if ($oUserMessage)
							{
								$oApiMail->sendMessage($oFromAccount, $oUserMessage);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oThread Helpdesk thread object
	 * @param CHelpdeskPost $oPost Helpdesk post object
	 * @param bool $bIsNew Default value is **false**.
	 * @param bool $bSendNotify Default value is **true**.
	 * @param string $sCc Default value is empty string.
	 * @param string $sBcc Default value is empty string.
	 *
	 * @return bool
	 */
	public function createPost(CHelpdeskUser $oHelpdeskUser, $oThread, CHelpdeskPost $oPost, $bIsNew = false, $bSendNotify = true, $sCc = '', $sBcc = '')
	{
		$bResult = false;
		try
		{
			if ($oPost->validate())
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

				$bResult = $this->oStorage->createPost($oHelpdeskUser, $oPost);
				if (!$bResult)
				{
					$this->moveStorageExceptionToManager();
					throw new CApiManagerException(Errs::HelpdeskManager_PostCreateFailed);
				}
				else
				{
					if (is_array($oPost->Attachments) && 0 < count($oPost->Attachments))
					{
						$this->oStorage->addAttachments($oHelpdeskUser, $oThread, $oPost, $oPost->Attachments);
					}

					$oThread->Updated = time();
					$oThread->PostCount = $this->getPostsCount($oHelpdeskUser, $oThread);
					$oThread->LastPostId = $oPost->IdHelpdeskPost;
					$oThread->LastPostOwnerId = $oPost->IdOwner;
					$oThread->Notificated = false;

					if (!$oThread->HasAttachments)
					{
						$oThread->HasAttachments = is_array($oPost->Attachments) && 0 < count($oPost->Attachments);
					}

					$bResult = $this->updateThread($oHelpdeskUser, $oThread);
					$this->setThreadSeen($oHelpdeskUser, $oThread);

					if ($bSendNotify)
					{
						$this->sendPostNotify($oThread, $oPost, $bIsNew, $sCc, $sBcc);
					}

					if (!empty($sCc) || !empty($sBcc))
					{
						//$this->sendPostCopy($oThread, $oPost, $bIsNew, $sCc, $sBcc);
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
	 * @param CHelpdeskUser $oHelpdeskUser Helpdesk user object
	 * @param CHelpdeskThread $oHelpdeskThread Helpdesk thread object
	 *
	 * @return bool
	 */
	public function setThreadSeen(CHelpdeskUser $oHelpdeskUser, $oHelpdeskThread)
	{
		$bResult = false;
		try
		{
			$bResult = $this->oStorage->setThreadSeen($oHelpdeskUser, $oHelpdeskThread);
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
	public function clearUnregistredUsers()
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->clearUnregistredUsers();
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}

		return $mResult;
	}
}
