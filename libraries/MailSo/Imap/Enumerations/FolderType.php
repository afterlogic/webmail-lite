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
class FolderType
{
	const USER = 0;
	const INBOX = 1;
	const SENT = 2;
	const DRAFTS = 3;
	const JUNK = 4;
	const TRASH = 5;
	const IMPORTANT = 10;
	const FLAGGED = 11;
	const ALL = 12;
}
