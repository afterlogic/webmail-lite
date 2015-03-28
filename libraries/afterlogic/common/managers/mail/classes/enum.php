<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Mail
 * @subpackage Enum
 */
class EMailMessageListSortType extends AEnumeration
{
	const Date = 0;
	const From_ = 1;
	const To_ = 2;
	const Subject = 3;
	const Size = 4;
}

/**
 * @package Mail
 * @subpackage Enum
 */
class EMailMessageStoreAction extends AEnumeration
{
	const Add = 0;
	const Remove = 1;
	const Set = 2;
}

/**
 * @package Mail
 * @subpackage Enum
 */
class EMailMessageFlag extends AEnumeration
{
	const Recent = '\Recent';
	const Seen = '\Seen';
	const Deleted = '\Deleted';
	const Flagged = '\Flagged';
	const Answered = '\Answered';
	const Draft = '\Draft';
}
