<?php

namespace ProjectSeven\Exceptions;

/**
 * @category ProjectSeven
 * @package Exceptions
 */
class ClientException extends Exception
{
	public function __construct($iCode, $oPrevious = null)
	{
		parent::__construct('ClientException', $iCode, $oPrevious);
	}
}
