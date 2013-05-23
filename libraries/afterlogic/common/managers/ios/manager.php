<?php

/*
 * Copyright (C) 2002-2011  AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package IOS
 */
class CApiIosManager extends AApiManager
{
	/**
	 * @var DOMDocument
	 */
	private $oXmlDocument;

	/*
	 * @var CApiUsersManager
	 */
	private $oApiUsersManager;

	/*
	 * @var CApiCalendarManager
	 */
	private $oApiCalendarManager;

	/*
	 * @var $oApiDavManager CApiDavManager
	 */
	private $oApiDavManager;

	/**
	 * @var CAccout
	 */
	private $oAccount;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('ios', $oManager);

		$oDomImplementation = new DOMImplementation();
		$oDocumentType = $oDomImplementation->createDocumentType(
			'plist',
			'-//Apple//DTD PLIST 1.0//EN',
			'http://www.apple.com/DTDs/PropertyList-1.0.dtd'
		);

		$this->oXmlDocument = $oDomImplementation->createDocument('', '', $oDocumentType);
		$this->oXmlDocument->xmlVersion = '1.0';
		$this->oXmlDocument->encoding = 'UTF-8';
		$this->oXmlDocument->formatOutput = true;

		$this->oApiUsersManager = CApi::Manager('users');
		$this->oApiCalendarManager = CApi::Manager('calendar');
		/*
		 * @var $oApiDavManager CApiDavManager
		 */
		$this->oApiDavManager = CApi::Manager('dav');

		$this->oAccount = null;
		$iUserId = CSession::Get(APP_SESSION_USER_ID);
		if (0 < $iUserId)
		{
			$iAccountId = $this->oApiUsersManager->GetDefaultAccountId($iUserId);
			if (0 < $iAccountId)
			{
				$this->oAccount = $this->oApiUsersManager->GetAccountById($iAccountId);
			}
		}
	}

	/**
	 * @return DOMElement
	 */
	private function generateDict($aPayload)
	{
		$oDictElement = $this->oXmlDocument->createElement('dict');

		foreach ($aPayload as $sKey => $mValue)
		{
			$oDictElement->appendChild($this->oXmlDocument->createElement('key', $sKey));

			if (is_int($mValue))
			{
				$oDictElement->appendChild($this->oXmlDocument->createElement('integer', $mValue));
			}
			else if (is_bool($mValue))
			{
				$oDictElement->appendChild($this->oXmlDocument->createElement($mValue ? 'true': 'false'));
			}
			else
			{
				$oDictElement->appendChild($this->oXmlDocument->createElement('string', $mValue));
			}
		}
		return $oDictElement;
	}

	/**
	 * @param string $sPayloadId
	 * @param CAccount $oAccount
	 * @return DOMElement | bool
	 */
	private function generateEmailDict($sPayloadId, $oAccount)
	{
		$bIsDemo = false;
		CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));

		$oSettings = CApi::GetSettings();

		$sIncMailServer = $oAccount->IncomingMailServer;
		if ($sIncMailServer == 'localhost' || $sIncMailServer == '127.0.0.1')
		{
			$sIncMailServer = $oSettings->GetConf('WebMail/ExternalHostNameOfLocalImap');
		}
		$sOutMailServer = $oAccount->OutgoingMailServer;
		if ($sOutMailServer == 'localhost' || $sOutMailServer == '127.0.0.1')
		{
			$sOutMailServer = $oSettings->GetConf('WebMail/ExternalHostNameOfLocalSmtp');
		}

		if (empty($sIncMailServer) || empty($sOutMailServer))
		{
			return false;
		}

		$aEmail = array(
			'PayloadVersion'					=> 1,
			'PayloadUUID'						=> \Sabre\DAV\UUIDUtil::getUUID(),
			'PayloadType'						=> 'com.apple.mail.managed',
			'PayloadIdentifier'					=> $sPayloadId.'.email',
			'PayloadDisplayName'				=> 'Email Account',
			'PayloadOrganization'				=> $oAccount->Domain->SiteName,
			'PayloadDescription'				=> 'Configures email account',
			'EmailAddress'						=> $oAccount->Email,
			'EmailAccountType'					=> EMailProtocol::IMAP4 === $oAccount->IncomingMailProtocol
				? 'EmailTypeIMAP' : 'EmailTypePOP',
			'EmailAccountDescription'			=> $oAccount->Email,
			'EmailAccountName'					=> 0 === strlen($oAccount->FriendlyName)
				? $oAccount->Email : $oAccount->FriendlyName,
			'IncomingMailServerHostName'		=> $sIncMailServer,
			'IncomingMailServerPortNumber'		=> $oAccount->IncomingMailPort,
			'IncomingMailServerUseSSL'			=> $oAccount->IncomingMailUseSSL,
			'IncomingMailServerUsername'		=> $oAccount->IncomingMailLogin,
			'IncomingPassword'					=> $oAccount->IncomingMailPassword,
			'IncomingMailServerAuthentication'	=> 'EmailAuthPassword',
			'OutgoingMailServerHostName'		=> $sOutMailServer,
			'OutgoingMailServerPortNumber'		=> $oAccount->OutgoingMailPort,
			'OutgoingMailServerUseSSL'			=> $oAccount->OutgoingMailUseSSL,
			'OutgoingMailServerUsername'		=> 0 === strlen($oAccount->OutgoingMailLogin)
				? $oAccount->IncomingMailLogin : $oAccount->OutgoingMailLogin,
			'OutgoingPassword'					=> $bIsDemo ? 'password' : (0 === strlen($oAccount->OutgoingMailPassword)
				? $oAccount->IncomingMailPassword : $oAccount->OutgoingMailPassword),
			'OutgoingMailServerAuthentication'	=> ESMTPAuthType::NoAuth === $oAccount->OutgoingMailAuth
				? 'EmailAuthNone' : 'EmailAuthPassword',
		);

		return $this->generateDict($aEmail);
	}

	/**
	 * @param string $sPayloadId
	 * @return DOMElement
	 */
	private function generateCaldavDict($sPayloadId)
	{
		$aCaldav = array(
			'PayloadVersion'			=> 1,
			'PayloadUUID'				=> \Sabre\DAV\UUIDUtil::getUUID(),
			'PayloadType'				=> 'com.apple.caldav.account',
			'PayloadIdentifier'			=> $sPayloadId.'.caldav',
			'PayloadDisplayName'		=> 'CalDAV Account',
			'PayloadOrganization'		=> $this->oAccount->Domain->SiteName,
			'PayloadDescription'		=> 'Configures CalDAV Account',
			'CalDAVAccountDescription'	=> $this->oAccount->Domain->SiteName.' Calendars',
			'CalDAVHostName'			=> $this->oApiDavManager ? $this->oApiDavManager->GetServerHost($this->oAccount) : '',
			'CalDAVUsername'			=> $this->oAccount->Email,
			'CalDAVPassword'			=> $this->oAccount->IncomingMailPassword,
			'CalDAVUseSSL'				=> $this->oApiDavManager ? $this->oApiDavManager->IsUseSsl($this->oAccount) : '',
			'CalDAVPort'				=> $this->oApiDavManager ? $this->oApiDavManager->GetServerPort($this->oAccount) : '',
			'CalDAVPrincipalURL'		=> $this->oApiDavManager ? $this->oApiDavManager->GetPrincipalUrl($this->oAccount) : '',
		);

		return $this->generateDict($aCaldav);
	}

	/**
	 * @param string $sPayloadId
	 * @return DOMElement
	 */
	private function generateCarddavDict($sPayloadId)
	{
		$aCarddav = array(
			'PayloadVersion'			=> 1,
			'PayloadUUID'				=> \Sabre\DAV\UUIDUtil::getUUID(),
			'PayloadType'				=> 'com.apple.carddav.account',
			'PayloadIdentifier'			=> $sPayloadId.'.carddav',
			'PayloadDisplayName'		=> 'CardDAV Account',
			'PayloadOrganization'		=> $this->oAccount->Domain->SiteName,
			'PayloadDescription'		=> 'Configures CardDAV Account',
			'CardDAVAccountDescription'	=> $this->oAccount->Domain->SiteName.' Contacts',
			'CardDAVHostName'			=> $this->oApiDavManager ? $this->oApiDavManager->GetServerHost($this->oAccount) : '',
			'CardDAVUsername'			=> $this->oAccount->Email,
			'CardDAVPassword'			=> $this->oAccount->IncomingMailPassword,
			'CardDAVUseSSL'				=> $this->oApiDavManager ? $this->oApiDavManager->IsUseSsl($this->oAccount) : '',
			'CardDAVPort'				=> $this->oApiDavManager ? $this->oApiDavManager->GetServerPort($this->oAccount) : '',
			'CardDAVPrincipalURL'		=> $this->oApiDavManager ? $this->oApiDavManager->GetPrincipalUrl($this->oAccount) : '',
		);

		return $this->generateDict($aCarddav);
	}

	/**
	 * @return string
	 */
	public function GenerateXMLProfile()
	{
		$mResult = false;
		if (isset($this->oAccount))
		{
			$oPlist = $this->oXmlDocument->createElement('plist');
			$oPlist->setAttribute('version', '1.0');

			$sPayloadId = $this->oApiDavManager ? 'afterlogic.'.$this->oApiDavManager->GetServerHost($this->oAccount) : '';
			$aPayload = array(
				'PayloadVersion'			=> 1,
				'PayloadUUID'				=> \Sabre\DAV\UUIDUtil::getUUID(),
				'PayloadType'				=> 'Configuration',
				'PayloadRemovalDisallowed'	=> false,
				'PayloadIdentifier'			=> $sPayloadId,
				'PayloadOrganization'		=> $this->oAccount->Domain->SiteName,
				'PayloadDescription'		=> $this->oAccount->Domain->SiteName.' Mobile',
				'PayloadDisplayName'		=> $this->oAccount->Domain->SiteName.' Mobile Profile',
			);

			$oArrayElement = $this->oXmlDocument->createElement('array');

			// Emails
			$oAccounts = AppGetAccounts($this->oAccount);
			foreach ($oAccounts as $oAccount)
			{
				$oEmailDictElement = $this->generateEmailDict($sPayloadId, $oAccount);
				if ($oEmailDictElement === false)
				{
					return false;
				}
				else
				{
					$oArrayElement->appendChild($oEmailDictElement);
				}
			}

			// TODO
			if (true || ($this->oApiDavManager && $this->oApiDavManager->TestConnection($this->oAccount)))
			{
				// Calendars
				$oCaldavDictElement = $this->generateCaldavDict($sPayloadId);
				$oArrayElement->appendChild($oCaldavDictElement);

				// Contacts
				$oCarddavDictElement = $this->generateCarddavDict($sPayloadId);
				$oArrayElement->appendChild($oCarddavDictElement);
			}

			$oDictElement = $this->generateDict($aPayload);
			$oPayloadContentElement = $this->oXmlDocument->createElement('key', 'PayloadContent');
			$oDictElement->appendChild($oPayloadContentElement);
			$oDictElement->appendChild($oArrayElement);
			$oPlist->appendChild($oDictElement);

			$this->oXmlDocument->appendChild($oPlist);
			$mResult = $this->oXmlDocument->saveXML();
		}

		return $mResult;
	}
}
