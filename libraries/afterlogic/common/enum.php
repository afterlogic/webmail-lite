<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 * @subpackage Enum
 */
abstract class AEnumeration
{
	/**
	 * @var array
	 */
	protected $aConsts = array();

	/**
	 *
	 * @return array
	 */
	public function GetMap()
	{
		return $this->aConsts;
	}
}

/**
 * @package Api
 * @subpackage Enum
 */
class EnumConvert
{
	/**
	 * @staticvar array $aClasses
	 * @param string $sClassName
	 * @return array
	 */
	protected static function GetInst($sClassName)
	{
		static $aClasses = array();

		if (!isset($aClasses[$sClassName]) && class_exists($sClassName))
		{
			$aClasses[$sClassName] = new $sClassName;
		}

		return (isset($aClasses[$sClassName])) ? $aClasses[$sClassName]->GetMap() : array();
	}

	/**
	 * @param mixed $mValue
	 * @param string $sClassName
	 * @return int
	 */
	static function Validate($mValue, $sClassName)
	{
		$aConsts = EnumConvert::GetInst($sClassName);

		$sResult = null;
		foreach ($aConsts as $mEnumValue)
		{
			if ($mValue === $mEnumValue)
			{
				$sResult = $mValue;
				break;
			}
		}
		return $sResult;
	}

	/**
	 * @param mixed $mValue
	 * @param string $sClassName
	 * @return int
	 */
	public static function FromXml($sXmlValue, $sClassName)
	{
		$aConsts = EnumConvert::GetInst($sClassName);

		$niResult = null;
		if (isset($aConsts[$sXmlValue]))
		{
			$niResult = $aConsts[$sXmlValue];
		}

		return EnumConvert::Validate($niResult, $sClassName);
	}

	/**
	 * @param mixed $mValue
	 * @param string $sClassName
	 * @return int
	 */
	public static function FromPost($sXmlValue, $sClassName)
	{
		return self::FromXml($sXmlValue, $sClassName);
	}

	/**
	 * @param mixed $mValue
	 * @param string $sClassName
	 * @return string
	 */
	public static function ToXml($mValue, $sClassName)
	{
		$aConsts = EnumConvert::GetInst($sClassName);

		$sResult = '';
		foreach ($aConsts as $sKey => $mEnumValue)
		{
			if ($mValue === $mEnumValue)
			{
				$sResult = $sKey;
				break;
			}
		}
		return $sResult;
	}

	/**
	 * @param mixed $mValue
	 * @param string $sClassName
	 * @return string
	 */
	public static function ToPost($mValue, $sClassName)
	{
		return self::ToXml($mValue, $sClassName);
	}
}

/**
 * @package Api
 * @subpackage Enum
 */
class ELogLevel extends AEnumeration
{
	const Full = 100;
	const Warning = 50;
	const Error = 20;
	const Spec = 10;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Full' => self::Full,
		'Warning' => self::Warning,
		'Error' => self::Error,
		'Spec' => self::Spec,
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EDbType extends AEnumeration
{
	const MySQL = 3;
	const PostgreSQL = 4;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'MySQL' => self::MySQL,
		'PostgreSQL' => self::PostgreSQL
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EHelpdeskFetcherType extends AEnumeration
{
	const NONE = 0;
	const REPLY = 1;
	const ALL = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'NONE' => self::NONE,
		'REPLY' => self::REPLY,
		'ALL' => self::ALL
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EMailProtocol extends AEnumeration
{
	const POP3 = 0;
	const IMAP4 = 1;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'POP3' => self::POP3,
		'IMAP4' => self::IMAP4
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ESMTPAuthType extends AEnumeration
{
	const NoAuth = 0;
	const AuthSpecified = 1;
	const AuthCurrentUser = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'NoAuth' => self::NoAuth,
		'AuthSpecified' => self::AuthSpecified,
		'AuthCurrentUser' => self::AuthCurrentUser
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ESendingMethod extends AEnumeration
{
	const Local = 0;
	const Specified = 1;
	const PhpMail = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Local' => self::Local,
		'Specified' => self::Specified,
		'PhpMail' => self::PhpMail
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ETimeFormat extends AEnumeration
{
	const F12 = 1;
	const F24 = 0;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'F12' => self::F12,
		'F24' => self::F24
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EDateFormat extends AEnumeration
{
	const DD_MONTH_YYYY = 'DD Month YYYY';
	const MMDDYYYY = 'MM/DD/YYYY';
	const DDMMYYYY = 'DD/MM/YYYY';
	const MMDDYY = 'MM/DD/YY';
	const DDMMYY = 'DD/MM/YY';

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'DD Month YYYY' => self::DD_MONTH_YYYY,
		'MM/DD/YYYY' => self::MMDDYYYY,
		'DD/MM/YYYY' => self::DDMMYYYY,
		'MM/DD/YY' => self::MMDDYY,
		'DD/MM/YY' => self::DDMMYY
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ELayout extends AEnumeration
{
	const Side = 0;
	const Bottom = 1;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Side' => self::Side,
		'Bottom' => self::Bottom
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ESaveMail extends AEnumeration
{
	const Always = 0;
	const DefaultOn = 1;
	const DefaultOff = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Always' => self::Always,
		'DefaultOn' => self::DefaultOn,
		'DefaultOff' => self::DefaultOff
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ELoginFormType extends AEnumeration
{
	const Email = 0;
	const Login = 3;
	const Both = 4;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Email' => self::Email,
		'Login' => self::Login,
		'Both' => self::Both
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ELoginSignMeType extends AEnumeration
{
	const DefaultOff = 0;
	const DefaultOn = 1;
	const Unuse = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'DefaultOff' => self::DefaultOff,
		'DefaultOn' => self::DefaultOn,
		'Unuse' => self::Unuse
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EImapSortUsage extends AEnumeration
{
	const Always = 0;
	const DateOnly = 1;
	const Never = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Always' => self::Always,
		'DateOnly' => self::DateOnly,
		'Never' => self::Never
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EContactsGABVisibility extends AEnumeration
{
	const Off = 0;
	const DomainWide = 1;
	const SystemWide = 2;
	const TenantWide = 3;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Off' => self::Off,
		'DomainWide' => self::DomainWide,
		'SystemWide' => self::SystemWide,
		'TenantWide' => self::TenantWide
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ECalendarDefaultWorkDay
{
	const Starts = 9;
	const Ends = 17;
}

/**
 * @package Api
 * @subpackage Enum
 */
class ECalendarWeekStartOn extends AEnumeration
{
	const Saturday = 6;
	const Sunday = 0;
	const Monday = 1;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Saturday' => self::Saturday,
		'Sunday' => self::Sunday,
		'Monday' => self::Monday
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ECalendarDefaultTab extends AEnumeration
{
	const Day = 1;
	const Week = 2;
	const Month = 3;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Day' => self::Day,
		'Week' => self::Week,
		'Month' => self::Month
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EFolderType extends AEnumeration
{
	const Inbox = 1;
	const Sent = 2;
	const Drafts = 3;
	const Spam = 4;
	const Trash = 5;
	const Virus = 6;
	const System = 9;
	const Custom = 10;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Inbox' => self::Inbox,
		'Sent' => self::Sent,
		'Drafts' => self::Drafts,
		'Spam' => self::Spam,
		'Trash' => self::Trash,
		'Quarantine' => self::Virus,
		'System' => self::System,
		'Custom' => self::Custom
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EPrimaryEmailType extends AEnumeration
{
	const Home = 0;
	const Business = 1;
	const Other = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Home' => self::Home,
		'Business' => self::Business,
		'Other' => self::Other
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ESortOrder extends AEnumeration
{
	const ASC = 0;
	const DESC = 1;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'ASC' => self::ASC,
		'DESC' => self::DESC
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ECapa extends AEnumeration
{
	const WEBMAIL = 'WEBMAIL';
	const CALENDAR = 'CALENDAR';
	const CAL_SHARING = 'CAL_SHARING';
	const CONTACTS_SHARING = 'CONTACTS_SHARING';
	const MEETINGS = 'MEETINGS';
	const PAB = 'PAB';
	const GAB = 'GAB';
	const FILES = 'FILES';
	const VOICE = 'VOICE';
	const SIP = 'SIP';
	const TWILIO = 'TWILIO';
	const HELPDESK = 'HELPDESK';
	const MOBILE_SYNC = 'MOBILE_SYNC';
	const OUTLOOK_SYNC = 'OUTLOOK_SYNC';
	
	const NO = 'NO';
}

/**
 * @package Api
 * @subpackage Enum
 */
class ETenantCapa extends AEnumeration
{
	const SIP = 'SIP';
	const TWILIO = 'TWILIO';
	const FILES = 'FILES';
	const HELPDESK = 'HELPDESK';
}

/**
 * @package Api
 * @subpackage Enum
 */
class ECalendarPermission extends AEnumeration
{
	const RemovePermission = -1;
	const Write = 1;
	const Read = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'RemovePermission' => self::RemovePermission,
		'Write' => self::Write,
		'Read' => self::Read

	);
}
	
/**
 * @package Api
 * @subpackage Enum
 */
class EFileStorageType extends AEnumeration
{
	const Personal = 0;
	const Corporate = 1;
	const Shared = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Personal' => self::Personal,
		'Corporate' => self::Corporate,
		'Shared' => self::Shared

	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EFileStorageTypeStr extends AEnumeration
{
	const Personal = 'personal';
	const Corporate = 'corporate';
	const Shared = 'shared';

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Personal' => self::Personal,
		'Corporate' => self::Corporate,
		'Shared' => self::Shared

	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EPeriodStr extends AEnumeration
{
	const Secondly = 'secondly';
	const Minutely = 'minutely';
	const Hourly   = 'hourly';
	const Daily	   = 'daily';
	const Weekly   = 'weekly';
	const Monthly  = 'monthly';
	const Yearly   = 'yearly';

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Secondly' => self::Secondly,
		'Minutely' => self::Minutely,
		'Hourly'   => self::Hourly,
		'Daily'	   => self::Daily,
		'Weekly'   => self::Weekly,
		'Monthly'  => self::Monthly,
		'Yearly'   => self::Yearly
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EPeriod extends AEnumeration
{
	const Never   = 0;
	const Daily	   = 1;
	const Weekly   = 2;
	const Monthly  = 3;
	const Yearly   = 4;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Never'		=> self::Never,
		'Daily'		=> self::Daily,
		'Weekly'	=> self::Weekly,
		'Monthly'	=> self::Monthly,
		'Yearly'	=> self::Yearly
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class ERepeatEnd extends AEnumeration
{
	const Never		= 0;
	const Count		= 1;
	const Date		= 2;
	const Infinity	= 3;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Never'		=> self::Never,
		'Count'		=> self::Count,
		'Date'		=> self::Date,
		'Infinity'	=> self::Infinity
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EAttendeeStatus extends AEnumeration
{
	const Unknown = 0;
	const Accepted = 1;
	const Declined = 2;
	const Tentative = 3;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Unknown' => self::Unknown,
		'Accepted' => self::Accepted,
		'Declined' => self::Declined,
		'Tentative'   => self::Tentative
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EEvents extends AEnumeration
{
	const LoginSuccess = 'login-success';
	const LoginFailed = 'login-failed';
	const Logout = 'logout';
	const MessageSend = 'message-send';
}

/**
 * @package Api
 * @subpackage Enum
 */
class EFileStorageLinkType extends AEnumeration
{
	const Unknown = 0;
	const GoogleDrive = 1;
	const DropBox = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Unknown' => self::Unknown,
		'GoogleDrive' => self::GoogleDrive,
		'DropBox' => self::DropBox
	);	
}

/**
 * @package Api
 * @subpackage Enum
 */
class ESocialType extends AEnumeration
{
	const Unknown   = 0;
	const Google    = 1;
	const Dropbox   = 2;
	const Facebook  = 3;
	const Twitter   = 4;
	const Vkontakte = 5;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Unknown'   => self::Unknown,
		'Google'    => self::Google,
		'Dropbox'   => self::Dropbox,
		'Facebook'  => self::Facebook,
		'Twitter'   => self::Twitter,
		'Vkontakte' => self::Vkontakte
	);	
}

/**
 * @package Api
 * @subpackage Enum
 */
class ESocialTypeStr extends AEnumeration
{
	const Unknown   = '';
	const Google    = 'google';
	const Dropbox   = 'dropbox';
	const Facebook  = 'faceboobk';
	const Twitter   = 'twitter';
	const Vkontakte = 'vkontakte';

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Unknown'   => self::Unknown,
		'Google'    => self::Google,
		'Dropbox'   => self::Dropbox,
		'Facebook'  => self::Facebook,
		'Twitter'   => self::Twitter,
		'Vkontakte' => self::Vkontakte
	);	
}

/**
 * @package Api
 * @subpackage Enum
 */
class EContactFileType extends AEnumeration
{
	const CSV = 'csv';
	const VCF = 'vcf';
}	

/**
 * @package Api
 * @subpackage Enum
 */
class EContactSortField extends AEnumeration
{
	const Name = 1;
	const EMail = 2;
	const Frequency = 3;

	/**
	 * @param int $iValue
	 * @return string
	 */
	public static function GetContactDbField($iValue)
	{
		$sResult = 'view_email';
		switch ($iValue)
		{
			case self::Name:
				$sResult = 'fullname';
				break;
			case self::EMail:
				$sResult = 'view_email';
				break;
			case self::Frequency:
				$sResult = 'use_frequency';
				break;
		}
		return $sResult;
	}

	/**
	 * @param int $iValue
	 * @return string
	 */
	public static function GetGlobalContactDbField($iValue)
	{
		$sResult = 'email';
		switch ($iValue)
		{
			case self::Name:
				$sResult = 'friendly_nm';
				break;
			case self::EMail:
				$sResult = 'email';
				break;
		}
		return $sResult;
	}

	/**
	 * @param int $iValue
	 * @return string
	 */
	public static function GetGroupDbField($iValue)
	{
		$sResult = 'group_nm';
		switch ($iValue)
		{
			case self::Name:
				$sResult = 'group_nm';
				break;
			case self::Frequency:
				$sResult = 'use_frequency';
				break;
		}
		return $sResult;
	}
}

/**
 * @package Contacts
 * @subpackage Enum
 */
class EContactType extends AEnumeration
{
    const Personal = 0;
    const Global_ = 1;
    const GlobalAccounts = 2;
    const GlobalMailingList = 3;
}

/**
 * @package Contacts
 * @subpackage Enum
 */
class ETwofaType extends AEnumeration
{
    CONST AUTH_TYPE_AUTHY = 'authy';
    CONST DATA_TYPE_AUTHY_ID = 1;
}

