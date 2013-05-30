<?php

namespace MailSo;

/**
 * @category MailSo
 */
final class Version
{
	/**
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * @var string
	 */
	const MIME_X_MAILER = 'MailSo';

	/**
	 * @return string
	 */
	public static function Version()
	{
		return \MailSo\Version::VERSION;
	}

	/**
	 * @return string
	 */
	public static function XMailer()
	{
		return \MailSo\Version::MIME_X_MAILER.'/'.\MailSo\Version::VERSION;
	}

	/**
	 * @return string
	 */
	public static function Signature()
	{
		$oPhar = new \Phar('mailso.phar');
		return $oPhar->getSignature();
	}
}
