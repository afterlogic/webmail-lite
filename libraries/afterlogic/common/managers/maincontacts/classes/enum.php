<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Maincontacts
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
