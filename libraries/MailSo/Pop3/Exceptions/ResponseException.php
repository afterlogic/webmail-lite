<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Pop3\Exceptions;

/**
 * @category MailSo
 * @package Pop3
 * @subpackage Exceptions
 */
class ResponseException extends \MailSo\Pop3\Exceptions\Exception
{
	/**
	 * @var array
	 */
	private $aResponses;

	/**
	 * @param array $aResponses = array
	 * @param string $sMessage = ''
	 * @param int $iCode = 0
	 * @param \Exception $oPrevious = null
	 */
	public function __construct($aResponses = array(), $sMessage = '', $iCode = 0, $oPrevious = null)
	{
		parent::__construct($sMessage, $iCode, $oPrevious);

		if (is_array($aResponses))
		{
			$this->aResponses = $aResponses;
		}
	}

	/**
	 * @return array
	 */
	public function GetResponses()
	{
		return $this->aResponses;
	}

	/**
	 * @return \MailSo\Pop3\Response | null
	 */
	public function GetLastResponse()
	{
		return 0 < count($this->aResponses) ? $this->aResponses[count($this->aResponses) - 1] : null;
	}
}
