<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Mime\Parser;

/**
 * @category MailSo
 * @package Mime
 * @subpackage Parser
 */
interface ParserInterface
{
	/**
	 * @param \MailSo\Mime\Part $oPart
	 *
	 * @return void
	 */
	public function StartParse(\MailSo\Mime\Part &$oPart);

	/**
	 * @param \MailSo\Mime\Part $oPart
	 *
	 * @return void
	 */
	public function EndParse(\MailSo\Mime\Part &$oPart);

	/**
	 * @param \MailSo\Mime\Part $oPart
	 *
	 * @return void
	 */
	public function StartParseMimePart(\MailSo\Mime\Part &$oPart);

	/**
	 * @param \MailSo\Mime\Part $oMimePart
	 *
	 * @return void
	 */
	public function EndParseMimePart(\MailSo\Mime\Part &$oPart);

	/**
	 * @return void
	 */
	public function InitMimePartHeader();

	/**
	 * @param string $sBuffer
	 * 
	 * @return void
	 */
	public function ReadBuffer($sBuffer);

	/**
	 * @param string $sBuffer
	 * @return void
	 */
	public function WriteBody($sBuffer);
}
