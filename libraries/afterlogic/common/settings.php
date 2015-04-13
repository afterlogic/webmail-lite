<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 */
class api_Settings
{
	const XML_FILE_NAME = '/settings/settings.xml';

	#<editor-fold defaultstate="collapsed" desc="protected">
	/**
	 * @var array
	 */
	protected $aMap;

	/**
	 * @var array
	 */
	protected $aObjectsMap;

	/**
	 * @var array
	 */
	protected $aLowerMap;

	/**
	 * @var array
	 */
	protected $aContainer;

	/**
	 * @var string
	 */
	protected $sPath;
	#</editor-fold>

	/**
	 * @param string $sSettingsPath
	 *
	 * @return api_Settings
	 */
	public function __construct($sSettingsPath)
	{
		$this->aMap = array();
		$this->aLowerMap = array();
		$this->aObjectsMap = array();
		$this->aContainer = array();
		$this->sPath = $sSettingsPath;

		$this->initDefaultValues();
		
		if (!$this->LoadFromXml($this->sPath.api_Settings::XML_FILE_NAME))
		{
			if (!$this->SaveToXml())
			{
				throw new CApiBaseException(Errs::Main_SettingLoadError);
			}
		}

		if (!api_Utils::HasSslSupport())
		{
			$this->SetConf('WebMail/IncomingMailUseSSL', false);
			$this->SetConf('WebMail/OutgoingMailUseSSL', false);
		}

		if (file_exists(CApi::RootPath().'common/lite.php'))
		{
			include_once CApi::RootPath().'common/lite.php';
		}

		$this->SetConf('WebMail/IncomingMailProtocol', EMailProtocol::IMAP4);
	}

	/**
	 * @param string $sKey
	 *
	 * @return mixed
	 */
	public function GetConf($sKey)
	{
		$mResult = null;
		$sKey = strtolower($sKey);
		if (array_key_exists($sKey, $this->aContainer))
		{
			$mResult = is_string($this->aContainer[$sKey])
				? trim(api_Utils::DecodeSpecialXmlChars($this->aContainer[$sKey]))
				: $this->aContainer[$sKey];
		}

		return $mResult;
	}
	
	/**
	 * @param string $sKey
	 * @param string $sItemKey
	 *
	 * @return mixed
	 */
	public function GetConfArrayItem($sKey, $sItemKey)
	{
		$mResult = null;
		$oItem = $this->GetConf($sKey);
		if ($oItem && array_key_exists($sItemKey, $oItem))
		{
			$mResult = is_string($oItem[$sItemKey])
				? trim(api_Utils::DecodeSpecialXmlChars($oItem[$sItemKey]))
				: $oItem[$sItemKey];
		}

		return $mResult;
	}
	

	/**
	 * @param string $sKey
	 * @param mixed $mDefault = null
	 *
	 * @return bool
	 */
	public function SetConf($sKey, $mValue)
	{
		$bResult = false;
		$sKey = strtolower($sKey);
		if (isset($this->aLowerMap[$sKey]))
		{
			$aType = $this->aLowerMap[$sKey];
			switch ($aType[1])
			{
				default:
					$mValue = null;
					break;
				case 'string':
					$mValue = (string) $mValue;
					break;
				case 'int':
					$mValue = (int) $mValue;
					break;
				case 'bool':
					$mValue = (bool) $mValue;
					break;
				case 'spec':
					$mValue = $this->specValidate($sKey, $mValue);
					break;
				case 'array':
					$mValue = $mValue;
					break;
			}

			if (null !== $mValue)
			{
				$bResult = true;
				$this->aContainer[$sKey] = $mValue;
			}
		}

		return $bResult;
	}

	/**
	 * @param string $sXmlFile
	 *
	 * @return bool
	 */
	public function LoadFromXml($sXmlFile)
	{
		$oXmlDocument = new CXmlDocument();
		if ($oXmlDocument->LoadFromFile($sXmlFile))
		{
			$this->parseXml($oXmlDocument->XmlRoot);
			return true;
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function SaveToXml()
	{
		$oXmlDocument = new CXmlDocument();
		$oXmlDocument->CreateElement('Settings');
		$oXmlDocument->XmlRoot->AppendAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
		$oXmlDocument->XmlRoot->AppendAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		$aCache = array();
		$aAddCache = array();
		$aMapKeys = array_keys($this->aMap);
		foreach ($aMapKeys as $sKey)
		{
			$sLine = $sLineParent = '';
			$aExplodeName = explode('/', $sKey);
			foreach ($aExplodeName as $sName)
			{
				$sLineParent = $sLine;
				$sLine .= $sName;
				if (!isset($aCache[$sLine]))
				{
					$aCache[$sLine] = new CXmlDomNode($sName);
				}

				if (!empty($sLineParent) && !isset($aAddCache[$sLine]) && isset($aCache[$sLineParent]))
				{
					$aAddCache[$sLine] = true;
					if (isset($this->aLowerMap[strtolower($sKey)]))
					{
						$aCache[$sLine]->Value = $this->parseGetConf($sKey);
						$aCache[$sLine]->Comment = $this->parseGetComment($sKey);
					}
					$aCache[$sLineParent]->Value = '';
					$aCache[$sLineParent]->AppendChild($aCache[$sLine]);
				}
				else if (empty($sLineParent) && !isset($aAddCache[$sLine]))
				{
					if (isset($this->aLowerMap[strtolower($sKey)]) && $this->aLowerMap[strtolower($sKey)][1] === 'array')
					{
						$this->parseGetArrayConf($sKey, $aCache[$sLine]);
					}
					$aAddCache[$sLine] = true;
					$oXmlDocument->XmlRoot->AppendChild($aCache[$sLine]);
				}
			}
		}

		return (bool) $oXmlDocument->SaveToFile($this->sPath.api_Settings::XML_FILE_NAME);
	}

	/**
	 * @param CXmlDomNode $oXmlTree
	 * @param string $sParentNode = ''
	 *
	 * @return void
	 */
	protected function parseXml(CXmlDomNode &$oXmlTree, $sParentNode = '')
	{
		$sParentNode = empty($sParentNode) ? '' : $sParentNode.'/';
		foreach ($oXmlTree->Children as $oNode)
		{
			$sTag = $sParentNode.strtolower($oNode->TagName);
			
			if (0 < count($oNode->Children))
			{
				if (isset($this->aLowerMap[$sTag]) && ($this->aLowerMap[$sTag][1] === 'array'))
				{
					$this->parseXmlToArray($oNode, $sTag);
				}
				else
				{
					$this->parseXml($oNode, $sTag);
				}
			}
			else
			{
				$this->parseSetConf($sTag, $oNode->Value);
			}
		}
	}
	
	/**
	 * @param CXmlDomNode $oXmlTree
	 * @param string $sParentNode = ''
	 *
	 * @return void
	 */
	protected function parseXmlToArray(CXmlDomNode &$oXmlTree, $sParentNode = '')
	{
		$oXml = new SimpleXMLElement($oXmlTree->ToString());
		$this->aContainer[$sParentNode] = array();
		
		foreach ($oXml as $oElement)
		{
			$aElement = array();
			foreach ($oElement as $oValue)
			{
				$aElement[$oValue->getName()] = (string) $oValue;
			}
			$this->aContainer[$sParentNode][$oElement->getName()] = $aElement;
		}
	}	

	/**
	 * @staticvar array $aValues
	 * @param string $sXmlPath
	 *
	 * @return string | null
	 */
	protected function xmlPathToEnumName($sXmlPath)
	{
		static $aValues = array(
			'common/dbtype'								=> 'EDbType',
			'common/defaulttimeformat'					=> 'ETimeFormat',
			'common/defaultdateformat'					=> 'EDateFormat',
			'common/logginglevel'						=> 'ELogLevel',
			'webmail/incomingmailprotocol'				=> 'EMailProtocol',
			'webmail/outgoingmailauth'					=> 'ESMTPAuthType',
			'webmail/outgoingsendingmethod'				=> 'ESendingMethod',
			'webmail/layout'							=> 'ELayout',
			'webmail/savemail'							=> 'ESaveMail',
			'webmail/loginformtype'						=> 'ELoginFormType',
			'webmail/loginsignmetype'					=> 'ELoginSignMeType',
			'contacts/globaladdressbookvisibility'		=> 'EContactsGABVisibility',
			'calendar/weekstartson'						=> 'ECalendarWeekStartOn',
			'calendar/defaulttab'						=> 'ECalendarDefaultTab',
			'helpdesk/fetchertype'						=> 'EHelpdeskFetcherType',
		);

		$sXmlPath = strtolower($sXmlPath);
		return isset($aValues[$sXmlPath]) ? $aValues[$sXmlPath] : null;
	}

	/**
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return string
	 */
	protected function specValidate($sKey, $sValue)
	{
		$mResult = null;
		$sEnumName = $this->xmlPathToEnumName($sKey);
		if (null !== $sEnumName)
		{
			$mResult = EnumConvert::Validate($sValue, $sEnumName);
		}
		return $mResult;
	}

	/**
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return string
	 */
	protected function specConver($sKey, $sValue)
	{
		$mResult = $sValue;
		$sEnumName = $this->xmlPathToEnumName($sKey);
		if (null !== $sEnumName)
		{
			$mResult = EnumConvert::FromXml($sValue, $sEnumName);
		}

		return $this->specValidate($sKey, $mResult);
	}

	/**
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return string
	 */
	protected function specBackConver($sKey, $sValue)
	{
		$mResult = $sValue;
		$sEnumName = $this->xmlPathToEnumName($sKey);
		if (null !== $sEnumName)
		{
			$mResult = EnumConvert::ToXml($sValue, $sEnumName);
		}

		return $mResult;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 *
	 * @return void
	 */
	protected function parseSetConf($sKey, $mValue)
	{
		$sKey = strtolower($sKey);
		if (isset($this->aLowerMap[$sKey]))
		{
			$aTypeArray = $this->aLowerMap[$sKey];
			switch ($aTypeArray[1])
			{
				default:
					$mValue = null;
					break;
				case 'string':
					$mValue = trim(api_Utils::DecodeSpecialXmlChars((string) $mValue));
					break;
				case 'int':
					$mValue = (int) $mValue;
					break;
				case 'bool':
					$mValue = ('on' === strtolower($mValue) || '1' === (string) $mValue);
					break;
				case 'spec':
					$mValue = $this->specConver($sKey, $mValue);
					break;
			}

			if (null !== $mValue)
			{
				$this->aContainer[$sKey] = $mValue;
			}
		}
	}

	/**
	 * @param string $sKey
	 *
	 * @return mixed
	 */
	protected function parseGetConf($sKey)
	{
		$mValue = null;
		$sKey = strtolower($sKey);
		if (isset($this->aLowerMap[$sKey]))
		{
			if (array_key_exists($sKey, $this->aContainer))
			{
				$mValue = $this->aContainer[$sKey];
			}
			else
			{
				$mValue = $this->aLowerMap[$sKey][0];
			}

			$aType = $this->aLowerMap[$sKey];
			switch ($aType[1])
			{
				case 'string':
					$mValue = api_Utils::EncodeSpecialXmlChars((string) $mValue);
					break;
				case 'int':
					$mValue = (int) $mValue;
					break;
				case 'bool':
					$mValue = ((bool) $mValue) ? 'On' : 'Off';
					break;
				case 'spec':
					$mValue = $this->specBackConver($sKey, $mValue);
					break;
			}
		}

		return $mValue;
	}
	
	/**
	 * @param string $sKey
	 *
	 * @return mixed
	 */
	protected function parseGetArrayConf($sKey, $oNode)
	{
		$sKey = strtolower($sKey);
		if (isset($this->aLowerMap[$sKey]))
		{
			$oNode->Value = '';
			if (array_key_exists($sKey, $this->aContainer) && is_array($this->aContainer[$sKey]))
			{
				$aNodeItems = array();
				foreach($this->aContainer[$sKey] as $sArrayKey => $aValue)
				{
					$aNodeItems[$sArrayKey] = new CXmlDomNode($sArrayKey);
					if (is_array($aValue))
					{
						foreach ($aValue as $sSubKey => $sSubValue)
						{
							$aNodeItems[$sArrayKey]->AppendChild(new CXmlDomNode($sSubKey, $sSubValue));
						}
					}
					$oNode->AppendChild($aNodeItems[$sArrayKey]);
				}
			}
		}
	}	

	/**
	 * @param string $sKey
	 *
	 * @return mixed
	 */
	protected function parseGetComment($sKey)
	{
		$mValue = null;
		$sKey = strtolower($sKey);

		if (isset($this->aLowerMap[$sKey]) && is_array($this->aLowerMap[$sKey])
			&& !empty($this->aLowerMap[$sKey][2]))
		{
			$mValue = trim($this->aLowerMap[$sKey][2]);
		}

		return $mValue;
	}

	/**
	 * @return void
	 */
	protected function initDefaultValues()
	{
		$this->aMap = array(

			// Common
			'Common/SiteName' => array('AfterLogic', 'string',
				'Default title that will be shown in browser\'s header (Default domain settings).'),

			'Common/LicenseKey' => array('', 'string',
				'License key is supplied here.'),

			'Common/AdminLogin' => array('mailadm', 'string'),
			'Common/AdminPassword' => array('827ccb0eea8a706c4c34a16891f84e7b', 'string'),

			'Common/DBType' => array(EDbType::MySQL, 'spec'),
			'Common/DBPrefix' => array('', 'string'),

			'Common/DBHost' => array('127.0.0.1', 'string'),
			'Common/DBName' => array('', 'string'),
			'Common/DBLogin' => array('root', 'string'),
			'Common/DBPassword' => array('', 'string'),

			'Common/UseSlaveConnection' => array(false, 'bool'),
			'Common/DBSlaveHost' => array('127.0.0.1', 'string'),
			'Common/DBSlaveName' => array('', 'string'),
			'Common/DBSlaveLogin' => array('root', 'string'),
			'Common/DBSlavePassword' => array('', 'string'),

			'Common/DefaultLanguage' => array('English', 'string'),
			'Common/DefaultTimeZone' => array(0, 'int'), //TODO Magic
			'Common/DefaultTimeFormat' => array(ETimeFormat::F12, 'spec'),
			'Common/DefaultDateFormat' => array(EDateFormat::MMDDYYYY, 'spec'),
			'Common/AllowRegistration' => array(false, 'bool'),
			'Common/RegistrationDomains' => array('', 'string'),
			'Common/RegistrationQuestions' => array('', 'string'),
			'Common/AllowPasswordReset' => array(false, 'bool'),
			'Common/EnableLogging' => array(false, 'bool'),
			'Common/EnableEventLogging' => array(false, 'bool'),
			'Common/LoggingLevel' => array(ELogLevel::Full, 'spec'),
			'Common/EnableMobileSync' => array(false, 'bool'),

			'Common/TenantGlobalCapa' => array('', 'string'),

			'Common/LoginStyleImage' => array('', 'string'),
			'Common/AppStyleImage' => array('', 'string'),
			
			'Common/DefaultTab' => array('', 'string'),
			'Common/RedirectToHttps' => array(false, 'bool'),

			'Common/PasswordMinLength' => array(0, 'int'),
			'Common/PasswordMustBeComplex' => array(false, 'bool'),

			// WebMail
			'WebMail/AllowWebMail' => array(true, 'bool'),
			'WebMail/IncomingMailProtocol' => array(EMailProtocol::IMAP4, 'spec'),
			'WebMail/IncomingMailServer' => array('127.0.0.1', 'string'),
			'WebMail/IncomingMailPort' => array(API_INC_PROTOCOL_IMAP4_DEF_PORT, 'int'),
			'WebMail/IncomingMailUseSSL' => array(false, 'bool'),
			'WebMail/OutgoingMailServer' => array('127.0.0.1', 'string'),
			'WebMail/OutgoingMailPort' => array(API_INC_PROTOCOL_SMTP_DEF_PORT, 'int'),
			'WebMail/OutgoingMailAuth' => array(ESMTPAuthType::AuthCurrentUser, 'spec'),
			'WebMail/OutgoingMailLogin' => array('', 'string'),
			'WebMail/OutgoingMailPassword' => array('', 'string'),
			'WebMail/OutgoingMailUseSSL' => array(false, 'bool'),
			'WebMail/OutgoingSendingMethod' => array(ESendingMethod::Specified, 'spec'),
			'WebMail/UserQuota' => array(0, 'int'),
			'WebMail/ShowQuotaBar' => array(false, 'bool'),
			'WebMail/AutoCheckMailInterval' => array(0, 'int'),
			'WebMail/DefaultSkin' => array(API_DEFAULT_SKIN, 'string'),
			'WebMail/MailsPerPage' => array(20, 'int'),
			'WebMail/AllowUsersChangeInterfaceSettings' => array(true, 'bool'),
			'WebMail/AllowUsersChangeEmailSettings' => array(true, 'bool'),
			'WebMail/EnableAttachmentSizeLimit' => array(false, 'bool'),
			'WebMail/AttachmentSizeLimit' => array(0, 'int'),
			'WebMail/ImageUploadSizeLimit' => array(0, 'int'),
			'WebMail/AllowLanguageOnLogin' => array(true, 'bool'),
			'WebMail/FlagsLangSelect' => array(false, 'bool'),

			'WebMail/LoginFormType' => array(ELoginFormType::Email, 'spec'),
			'WebMail/LoginSignMeType' => array(ELoginSignMeType::DefaultOn, 'spec'),
			'WebMail/LoginAtDomainValue' => array('', 'string'),
			'WebMail/UseLoginWithoutDomain' => array(false, 'bool'),

			'WebMail/AllowNewUsersRegister' => array(false, 'bool'),
			'WebMail/AllowUsersAddNewAccounts' => array(true, 'bool'),
			'WebMail/AllowOpenPGP' => array(false, 'bool'),
			'WebMail/AllowIdentities' => array(true, 'bool'),
			'WebMail/AllowInsertImage' => array(true, 'bool'),
			'WebMail/AllowBodySize' => array(false, 'bool'),
			'WebMail/MaxBodySize' => array(600, 'int'),  //TODO Magic
			'WebMail/MaxSubjectSize' => array(255, 'int'),  //TODO Magic
			'WebMail/Layout' => array(ELayout::Side, 'spec'),
			'WebMail/AlwaysShowImagesInMessage' => array(false, 'bool'),
			'WebMail/SaveMail' => array(ESaveMail::Always, 'spec'),
			'WebMail/IdleSessionTimeout' => array(0, 'int'),
			'WebMail/UseSortImapForDateMode' => array(true, 'bool'),
			'WebMail/UseThreadsIfSupported' => array(true, 'bool'),
			'WebMail/DetectSpecialFoldersWithXList' => array(true, 'bool'),
			'WebMail/EnableLastLoginNotification' => array(false, 'bool'),

			'WebMail/ExternalHostNameOfLocalImap' => array('', 'string'),
			'WebMail/ExternalHostNameOfLocalSmtp' => array('', 'string'),
			'WebMail/ExternalHostNameOfDAVServer' => array('', 'string'),

			// Calendar
			'Calendar/AllowCalendar' => array(true, 'bool'),
			'Calendar/ShowWeekEnds' => array(false, 'bool'),
			'Calendar/WorkdayStarts' => array(ECalendarDefaultWorkDay::Starts, 'int'),
			'Calendar/WorkdayEnds' => array(ECalendarDefaultWorkDay::Ends, 'int'),
			'Calendar/ShowWorkDay' => array(true, 'bool'),
			'Calendar/WeekStartsOn' => array(ECalendarWeekStartOn::Sunday, 'spec'),
			'Calendar/DefaultTab' => array(ECalendarDefaultTab::Month, 'spec'),
			'Calendar/AllowReminders' => array(true, 'bool'),

			// Contacts
			'Contacts/AllowContacts' => array(true, 'bool'),
			'Contacts/ContactsPerPage' => array(20, 'int'),
			'Contacts/ShowGlobalContactsInAddressBook' => array(false, 'bool'),
			'Contacts/GlobalAddressBookVisibility' => array(EContactsGABVisibility::DomainWide, 'spec'),

			// Files
			'Files/AllowFiles' => array(true, 'bool'),
			'Files/EnableSizeLimit' => array(false, 'bool'),
			'Files/SizeLimit' => array(0, 'int'),

			// Sip
			'Sip/AllowSip' => array(false, 'bool'),
			'Sip/Realm' => array('', 'string'),
			'Sip/WebsocketProxyUrl' => array('', 'string'),
			'Sip/OutboundProxyUrl' => array('', 'string'),
			'Sip/CallerID' => array('', 'string'),
			
			// Twilio
			'Twilio/AllowTwilio' => array(false, 'bool'),
			'Twilio/PhoneNumber' => array('', 'string'),
			'Twilio/AccountSID' => array('', 'string'),
			'Twilio/AuthToken' => array('', 'string'),
			'Twilio/AppSID' => array('', 'string'),

			// Social
			'Socials' => array(array(), 'array'),

			// Helpdesk
			'Helpdesk/AllowHelpdesk' => array(true, 'bool'),
			'Helpdesk/AdminEmailAccount' => array('', 'string'),
			'Helpdesk/ClientIframeUrl' => array('', 'string'),
			'Helpdesk/AgentIframeUrl' => array('', 'string'),
			'Helpdesk/SiteName' => array('', 'string'),
			'Helpdesk/StyleAllow' => array(false, 'bool'),
			'Helpdesk/StyleImage' => array('', 'string'),
			'Helpdesk/StyleText' => array('', 'string'),
			
			'Helpdesk/FetcherType' => array(EHelpdeskFetcherType::NONE, 'spec'),

			'Helpdesk/FacebookAllow' => array(false, 'bool'),
			'Helpdesk/FacebookId' => array('', 'string'),
			'Helpdesk/FacebookSecret' => array('', 'string'),
			'Helpdesk/GoogleAllow' => array(false, 'bool'),
			'Helpdesk/GoogleId' => array('', 'string'),
			'Helpdesk/GoogleSecret' => array('', 'string'),
			'Helpdesk/TwitterAllow' => array(false, 'bool'),
			'Helpdesk/TwitterId' => array('', 'string'),
			'Helpdesk/TwitterSecret' => array('', 'string'),
		);
		foreach ($this->aMap as $sKey => $aField)
		{
			$this->aLowerMap[strtolower($sKey)] = $aField;
			$this->SetConf($sKey, $aField[0]);
		}
	
		$this->aObjectsMap = array(
			'Socials' => 'CTenantSocials'
		);
		foreach ($this->aObjectsMap as $sKey => $sClass)
		{
			$this->aLowerObjectsMap[strtolower($sKey)] = $sClass;
		}
	}
}

/**
 * @package Api
 */
class api_SettingsException extends Exception {}
