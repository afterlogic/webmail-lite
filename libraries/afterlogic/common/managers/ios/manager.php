<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package IOS
 */
class CApiIosManager extends AApiManager
{
	/*
	 * @var $oApiUsersManager CApiUsersManager
	 */
	private $oApiUsersManager;

	/*
	 * @var $oApiDavManager CApiDavManager
	 */
	private $oApiDavManager;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('ios', $oManager);

		/*
		 * @var $oApiUsersManager CApiUsersManager
		 */
		$this->oApiUsersManager = CApi::Manager('users');

		/*
		 * @var $oApiDavManager CApiDavManager
		 */
		$this->oApiDavManager = CApi::Manager('dav');
	}

	/**
	 * @return DOMElement
	 */
	private function generateDict($oXmlDocument, $aPayload)
	{
		$oDictElement = $oXmlDocument->createElement('dict');

		foreach ($aPayload as $sKey => $mValue)
		{
			$oDictElement->appendChild($oXmlDocument->createElement('key', $sKey));

			if (is_int($mValue))
			{
				$oDictElement->appendChild($oXmlDocument->createElement('integer', $mValue));
			}
			else if (is_bool($mValue))
			{
				$oDictElement->appendChild($oXmlDocument->createElement($mValue ? 'true': 'false'));
			}
			else
			{
				$oDictElement->appendChild($oXmlDocument->createElement('string', $mValue));
			}
		}
		return $oDictElement;
	}

	/**
	 * @param string $sPayloadId
	 * @param CAccount $oAccount
	 * @return DOMElement | bool
	 */
	private function generateEmailDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo = false)
	{
		$oSettings = CApi::GetSettings();

		$sIncMailServer = $oAccount->IncomingMailServer;
		$iIncMailPort = $oAccount->IncomingMailPort;

		if ($sIncMailServer == 'localhost' || $sIncMailServer == '127.0.0.1')
		{
			$sIncMailServer = $oSettings->GetConf('WebMail/ExternalHostNameOfLocalImap');
			
			$aParsedUrl = parse_url($sIncMailServer);
			if (isset($aParsedUrl['host']))
			{
				$sIncMailServer = $aParsedUrl['host'];
			}
			if (isset($aParsedUrl['port']))
			{
				$iIncMailPort = $aParsedUrl['port'];
			}
		}

		$sOutMailServer = $oAccount->OutgoingMailServer;
		$iOutMailPort = $oAccount->OutgoingMailPort;

		if ($sOutMailServer == 'localhost' || $sOutMailServer == '127.0.0.1')
		{
			$sOutMailServer = $oSettings->GetConf('WebMail/ExternalHostNameOfLocalSmtp');
			
			$aParsedUrl = parse_url($sOutMailServer);
			if (isset($aParsedUrl['host']))
			{
				$sOutMailServer = $aParsedUrl['host'];
			}
			if (isset($aParsedUrl['port']))
			{
				$iOutMailPort = $aParsedUrl['port'];
			}
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
			'IncomingMailServerPortNumber'		=> $iIncMailPort,
			'IncomingMailServerUseSSL'			=> 993 === $iIncMailPort,
			'IncomingMailServerUsername'		=> $oAccount->IncomingMailLogin,
			'IncomingPassword'					=> $bIsDemo ? 'demo' : $oAccount->IncomingMailPassword,
			'IncomingMailServerAuthentication'	=> 'EmailAuthPassword',
			'OutgoingMailServerHostName'		=> $sOutMailServer,
			'OutgoingMailServerPortNumber'		=> $iOutMailPort,
			'OutgoingMailServerUseSSL'			=> 465 === $iIncMailPort,
			'OutgoingMailServerUsername'		=> 0 === strlen($oAccount->OutgoingMailLogin)
				? $oAccount->IncomingMailLogin : $oAccount->OutgoingMailLogin,
			'OutgoingPassword'					=> $bIsDemo ? 'demo' : (0 === strlen($oAccount->OutgoingMailPassword)
				? $oAccount->IncomingMailPassword : $oAccount->OutgoingMailPassword),
			'OutgoingMailServerAuthentication'	=> ESMTPAuthType::NoAuth === $oAccount->OutgoingMailAuth
				? 'EmailAuthNone' : 'EmailAuthPassword',
		);

		return $this->generateDict($oXmlDocument, $aEmail);
	}

	/**
	 * @param string $sPayloadId
	 * @return DOMElement
	 */
	private function generateCaldavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo = false)
	{
		$aCaldav = array(
			'PayloadVersion'			=> 1,
			'PayloadUUID'				=> \Sabre\DAV\UUIDUtil::getUUID(),
			'PayloadType'				=> 'com.apple.caldav.account',
			'PayloadIdentifier'			=> $sPayloadId.'.caldav',
			'PayloadDisplayName'		=> 'CalDAV Account',
			'PayloadOrganization'		=> $oAccount->Domain->SiteName,
			'PayloadDescription'		=> 'Configures CalDAV Account',
			'CalDAVAccountDescription'	=> $oAccount->Domain->SiteName.' Calendars',
			'CalDAVHostName'			=> $this->oApiDavManager ? $this->oApiDavManager->GetServerHost($oAccount) : '',
			'CalDAVUsername'			=> $oAccount->Email,
			'CalDAVPassword'			=> $bIsDemo ? 'demo' : $oAccount->IncomingMailPassword,
			'CalDAVUseSSL'				=> $this->oApiDavManager ? $this->oApiDavManager->IsUseSsl($oAccount) : '',
			'CalDAVPort'				=> $this->oApiDavManager ? $this->oApiDavManager->GetServerPort($oAccount) : '',
			'CalDAVPrincipalURL'		=> $this->oApiDavManager ? $this->oApiDavManager->GetPrincipalUrl($oAccount) : '',
		);

		return $this->generateDict($oXmlDocument, $aCaldav);
	}

	/**
	 * @param string $sPayloadId
	 * @return DOMElement
	 */
	private function generateCarddavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo = false)
	{
		$aCarddav = array(
			'PayloadVersion'			=> 1,
			'PayloadUUID'				=> \Sabre\DAV\UUIDUtil::getUUID(),
			'PayloadType'				=> 'com.apple.carddav.account',
			'PayloadIdentifier'			=> $sPayloadId.'.carddav',
			'PayloadDisplayName'		=> 'CardDAV Account',
			'PayloadOrganization'		=> $oAccount->Domain->SiteName,
			'PayloadDescription'		=> 'Configures CardDAV Account',
			'CardDAVAccountDescription'	=> $oAccount->Domain->SiteName.' Contacts',
			'CardDAVHostName'			=> $this->oApiDavManager ? $this->oApiDavManager->GetServerHost($oAccount) : '',
			'CardDAVUsername'			=> $oAccount->Email,
			'CardDAVPassword'			=> $bIsDemo ? 'demo' : $oAccount->IncomingMailPassword,
			'CardDAVUseSSL'				=> $this->oApiDavManager ? $this->oApiDavManager->IsUseSsl($oAccount) : '',
			'CardDAVPort'				=> $this->oApiDavManager ? $this->oApiDavManager->GetServerPort($oAccount) : '',
			'CardDAVPrincipalURL'		=> $this->oApiDavManager ? $this->oApiDavManager->GetPrincipalUrl($oAccount) : '',
		);

		return $this->generateDict($oXmlDocument, $aCarddav);
	}

	/**
	 * @param \CAccount $oAccount
	 * @return string
	 */
	public function GenerateXMLProfile($oAccount)
	{
		$mResult = false;

		if ($oAccount)
		{
			$oDomImplementation = new DOMImplementation();
			$oDocumentType = $oDomImplementation->createDocumentType(
				'plist',
				'-//Apple//DTD PLIST 1.0//EN',
				'http://www.apple.com/DTDs/PropertyList-1.0.dtd'
			);

			$oXmlDocument = $oDomImplementation->createDocument('', '', $oDocumentType);
			$oXmlDocument->xmlVersion = '1.0';
			$oXmlDocument->encoding = 'UTF-8';
			$oXmlDocument->formatOutput = true;

			$oPlist = $oXmlDocument->createElement('plist');
			$oPlist->setAttribute('version', '1.0');

			$sPayloadId = $this->oApiDavManager ? 'afterlogic.'.$this->oApiDavManager->GetServerHost($oAccount) : '';
			$aPayload = array(
				'PayloadVersion'			=> 1,
				'PayloadUUID'				=> \Sabre\DAV\UUIDUtil::getUUID(),
				'PayloadType'				=> 'Configuration',
				'PayloadRemovalDisallowed'	=> false,
				'PayloadIdentifier'			=> $sPayloadId,
				'PayloadOrganization'		=> $oAccount->Domain->SiteName,
				'PayloadDescription'		=> $oAccount->Domain->SiteName.' Mobile',
				'PayloadDisplayName'		=> $oAccount->Domain->SiteName.' Mobile Profile',
//				'ConsentText'				=> 'AfterLogic Profile @ConsentText',
			);

			$oArrayElement = $oXmlDocument->createElement('array');

			$bIsDemo = false;
			\CApi::Plugin()->RunHook('plugin-is-demo-account', array(&$oAccount, &$bIsDemo));

			if (!$bIsDemo)
			{
				$aInfo = $this->oApiUsersManager->GetUserAccountListInformation($oAccount->IdUser);
				if (is_array($aInfo) && 0 < count($aInfo))
				{
					foreach (array_keys($aInfo) as $iIdAccount)
					{
						if ($oAccount->IdAccount === $iIdAccount)
						{
							$oAccountItem = $oAccount;
						}
						else
						{
							$oAccountItem = $this->oApiUsersManager->GetAccountById($iIdAccount);
						}

						$oEmailDictElement = $this->generateEmailDict($oXmlDocument, $sPayloadId, $oAccountItem, $bIsDemo);
						if ($oEmailDictElement === false)
						{
							return false;
						}
						else
						{
							$oArrayElement->appendChild($oEmailDictElement);
						}

						unset($oAccountItem);
						unset($oEmailDictElement);
					}
				}
				else
				{
					return false;
				}
			}

			// Calendars
			$oCaldavDictElement = $this->generateCaldavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo);
			$oArrayElement->appendChild($oCaldavDictElement);

			// Contacts
			$oCarddavDictElement = $this->generateCarddavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo);
			$oArrayElement->appendChild($oCarddavDictElement);

			$oDictElement = $this->generateDict($oXmlDocument, $aPayload);
			$oPayloadContentElement = $oXmlDocument->createElement('key', 'PayloadContent');
			$oDictElement->appendChild($oPayloadContentElement);
			$oDictElement->appendChild($oArrayElement);
			$oPlist->appendChild($oDictElement);

			$oXmlDocument->appendChild($oPlist);
			$mResult = $oXmlDocument->saveXML();
		}

		return $mResult;
	}
}
