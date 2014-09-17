<?php

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
