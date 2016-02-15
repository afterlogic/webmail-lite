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
	 * @param string $sForcedStorage
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
	 * 
	 * @param type $oXmlDocument
	 * @param array $aPayload
	 * 
	 * @return DOMElement
	 */
	private function _generateDict($oXmlDocument, $aPayload)
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
	 * 
	 * @param type $oXmlDocument
	 * @param string $sPayloadId
	 * @param \CAccount $oAccount
	 * @param bool $bIsDemo Default false
	 * 
	 * @return boolean
	 */
	private function _generateEmailDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo = false)
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
			'IncomingPassword'					=> $bIsDemo ? 'demo' : (CApi::GetConf('labs.ios-profile.include-password', false) ? $oAccount->IncomingMailPassword : ''),
			'IncomingMailServerAuthentication'	=> 'EmailAuthPassword',
			'OutgoingMailServerHostName'		=> $sOutMailServer,
			'OutgoingMailServerPortNumber'		=> $iOutMailPort,
			'OutgoingMailServerUseSSL'			=> 465 === $iOutMailPort,
			'OutgoingMailServerUsername'		=> 0 === strlen($oAccount->OutgoingMailLogin)
				? $oAccount->IncomingMailLogin : $oAccount->OutgoingMailLogin,
			'OutgoingPassword'					=> $bIsDemo ? 'demo' : (CApi::GetConf('labs.ios-profile.include-password', false) ? (0 === strlen($oAccount->OutgoingMailPassword)
				? $oAccount->IncomingMailPassword : $oAccount->OutgoingMailPassword) : ''),
			'OutgoingMailServerAuthentication'	=> ESMTPAuthType::NoAuth === $oAccount->OutgoingMailAuth
				? 'EmailAuthNone' : 'EmailAuthPassword',
		);

		return $this->_generateDict($oXmlDocument, $aEmail);
	}

	/**
	 * 
	 * @param type $oXmlDocument
	 * @param string $sPayloadId
	 * @param \CAccount $oAccount
	 * @param bool $bIsDemo Default false
	 * 
	 * @return DOMElement
	 */
	private function _generateCaldavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo = false)
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
			'CalDAVHostName'			=> $this->oApiDavManager ? $this->oApiDavManager->getServerHost($oAccount) : '',
			'CalDAVUsername'			=> $oAccount->Email,
			'CalDAVPassword'			=> $bIsDemo ? 'demo' : (CApi::GetConf('labs.ios-profile.include-password', false) ? $oAccount->IncomingMailPassword : ''),
			'CalDAVUseSSL'				=> $this->oApiDavManager ? $this->oApiDavManager->isUseSsl($oAccount) : '',
			'CalDAVPort'				=> $this->oApiDavManager ? $this->oApiDavManager->getServerPort($oAccount) : '',
			'CalDAVPrincipalURL'		=> $this->oApiDavManager ? $this->oApiDavManager->getPrincipalUrl($oAccount) : '',
		);

		return $this->_generateDict($oXmlDocument, $aCaldav);
	}

	/**
	 * 
	 * @param type $oXmlDocument
	 * @param string $sPayloadId
	 * @param \CAccount $oAccount
	 * @param bool $bIsDemo Default false
	 * 
	 * @return DOMElement
	 */
	
	private function _generateCarddavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo = false)
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
			'CardDAVHostName'			=> $this->oApiDavManager ? $this->oApiDavManager->getServerHost($oAccount) : '',
			'CardDAVUsername'			=> $oAccount->Email,
			'CardDAVPassword'			=> $bIsDemo ? 'demo' : (CApi::GetConf('labs.ios-profile.include-password', false) ? $oAccount->IncomingMailPassword : ''),
			'CardDAVUseSSL'				=> $this->oApiDavManager ? $this->oApiDavManager->isUseSsl($oAccount) : '',
			'CardDAVPort'				=> $this->oApiDavManager ? $this->oApiDavManager->getServerPort($oAccount) : '',
			'CardDAVPrincipalURL'		=> $this->oApiDavManager ? $this->oApiDavManager->getPrincipalUrl($oAccount) : '',
		);

		return $this->_generateDict($oXmlDocument, $aCarddav);
	}

	/**
	 * @param \CAccount $oAccount
	 * @return string
	 */
	public function generateXMLProfile($oAccount)
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

			$sPayloadId = $this->oApiDavManager ? 'afterlogic.'.$this->oApiDavManager->getServerHost($oAccount) : '';
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
				$aInfo = $this->oApiUsersManager->getUserAccounts($oAccount->IdUser);
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
							$oAccountItem = $this->oApiUsersManager->getAccountById($iIdAccount);
						}

						$oEmailDictElement = $this->_generateEmailDict($oXmlDocument, $sPayloadId, $oAccountItem, $bIsDemo);
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
			$oCaldavDictElement = $this->_generateCaldavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo);
			$oArrayElement->appendChild($oCaldavDictElement);

			// Contacts
			$oCarddavDictElement = $this->_generateCarddavDict($oXmlDocument, $sPayloadId, $oAccount, $bIsDemo);
			$oArrayElement->appendChild($oCarddavDictElement);

			$oDictElement = $this->_generateDict($oXmlDocument, $aPayload);
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
