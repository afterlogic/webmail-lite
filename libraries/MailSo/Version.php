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
final class Version
{
	/**
	 * @var string
	 */
	const APP_VERSION = '1.3.3';

	/**
	 * @var string
	 */
	const MIME_X_MAILER = 'MailSo';

	/**
	 * @return string
	 */
	public static function AppVersion()
	{
		return \MailSo\Version::APP_VERSION;
	}

	/**
	 * @return string
	 */
	public static function XMailer()
	{
		return \MailSo\Version::MIME_X_MAILER.'/'.\MailSo\Version::APP_VERSION;
	}

	/**
	 * @return string
	 */
	public static function Signature()
	{
		$sSignature = '';
		if (\defined('MAILSO_LIBRARY_USE_PHAR'))
		{
			$oPhar = new \Phar('mailso.phar');
			$sSignature = $oPhar->getSignature();
		}
		
		return $sSignature;
	}
}
