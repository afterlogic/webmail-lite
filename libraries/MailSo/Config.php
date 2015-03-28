<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo;

/**
 * @category MailSo
 */
class Config
{
	/**
	 * @var bool
	 */
	public static $ICONV = true;

	/**
	 * @var bool
	 */
	public static $MBSTRING = true;

	/**
	 * @var bool
	 */
	public static $FixIconvByMbstring = true;

	/**
	 * @var int
	 */
	public static $MessageListFastSimpleSearch = true;

	/**
	 * @var int
	 */
	public static $MessageListCountLimitTrigger = 0;

	/**
	 * @var int
	 */
	public static $MessageListDateFilter = 0;

	/**
	 * @var int
	 */
	public static $LargeThreadLimit = 100;

	/**
	 * @var bool
	 */
	public static $LogSimpleLiterals = false;

	/**
	 * @var bool
	 */
	public static $PreferStartTlsIfAutoDetect = true;

	/**
	 * @var \MailSo\Log\Logger|null
	 */
	public static $SystemLogger = null;
}
