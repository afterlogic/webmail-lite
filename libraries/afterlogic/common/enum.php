<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
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

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'MySQL' => self::MySQL
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
	const MMDDYYYY = 'MM/DD/YYYY';
	const DDMMYYYY = 'DD/MM/YYYY';
	const MMDDYY = 'MM/DD/YY';
	const DDMMYY = 'DD/MM/YY';

	/**
	 * @var array
	 */
	protected $aConsts = array(
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
	const LoginAtDomainDropdown = 1;
	const LoginAtDomain = 2;
	const Login = 3;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Email' => self::Email,
		'LoginAtDomainDropdown' => self::LoginAtDomainDropdown,
		'LoginAtDomain' => self::LoginAtDomain,
		'Login' => self::Login,
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
class EContactsPABMode extends AEnumeration
{
	const Off = 0;
	const Sql = 1;
	const Ldap = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Off' => self::Off,
		'Sql' => self::Sql,
		'Ldap' => self::Ldap
	);
}

/**
 * @package Api
 * @subpackage Enum
 */
class EContactsGABMode extends AEnumeration
{
	const Off = 0;
	const Sql = 1;
	const Ldap = 2;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Off' => self::Off,
		'Sql' => self::Sql,
		'Ldap' => self::Ldap
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
	const RealmWide = 3;

	/**
	 * @var array
	 */
	protected $aConsts = array(
		'Off' => self::Off,
		'DomainWide' => self::DomainWide,
		'SystemWide' => self::SystemWide,
		'RealmWide' => self::RealmWide
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
	const Trash = 4;
	const Spam = 5;
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
		'Trash' => self::Trash,
		'Spam' => self::Spam,
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
class ECalendarPermission extends AEnumeration
{
	const RemovePermission = -1;
	const Full = 0;
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
