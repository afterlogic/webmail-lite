<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectSeven\Exceptions;

/**
 * @category ProjectSeven
 * @package Exceptions
 */
class ClientException extends Exception
{
	/**
	 * @param type $iCode
	 * @param type $oPrevious
	 * @param type $sMessage
	 */
	public function __construct($iCode, $oPrevious = null, $sMessage = '')
	{
		parent::__construct('' === $sMessage ? 'ClientException' : $sMessage, $iCode, $oPrevious);
	}
}
