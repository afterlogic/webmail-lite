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
class FolderResponseStatus
{
	const MESSAGES = 'MESSAGES';
	const RECENT = 'RECENT';
	const UNSEEN = 'UNSEEN';
	const UIDNEXT = 'UIDNEXT';
	const UIDVALIDITY = 'UIDVALIDITY';
}
