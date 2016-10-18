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
class ParserMemory extends ParserEmpty implements ParserInterface
{
	/**
	 * @var \MailSo\Mime\Part
	 */
	protected $oCurrentMime = null;

	/**
	 * @param \MailSo\Mime\Part $oMimePart
	 *
	 * @return void
	 */
	public function StartParseMimePart(\MailSo\Mime\Part &$oPart)
	{
		$this->oCurrentMime = $oPart;
	}

	/**
	 * @param string $sBuffer
	 *
	 * @return void
	 */
	public function WriteBody($sBuffer)
	{
		if (null === $this->oCurrentMime->Body)
		{
			$this->oCurrentMime->Body = \MailSo\Base\ResourceRegistry::CreateMemoryResource();
		}

		if (\is_resource($this->oCurrentMime->Body))
		{
			\fwrite($this->oCurrentMime->Body, $sBuffer);
		}
	}
}
