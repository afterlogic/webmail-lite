<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Imap\Enumerations;

/**
 * @category MailSo
 * @package Imap
 * @subpackage Enumerations
 */
class ResponseStatus
{
	const OK = 'OK';
	const NO = 'NO';
	const BAD = 'BAD';
	const BYE = 'BYE';
	const PREAUTH = 'PREAUTH';
}
