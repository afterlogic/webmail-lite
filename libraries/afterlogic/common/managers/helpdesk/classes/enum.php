<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Helpdesk
 * @subpackage Enum
 */
class EHelpdeskPostType extends AEnumeration
{
	const Normal = 0;
	const Internal = 1;
	const System = 2;
}

/**
 * @package Helpdesk
 * @subpackage Enum
 */
class EHelpdeskPostSystemType extends AEnumeration
{
	const None = 0;
}

/**
 * @package Helpdesk
 * @subpackage Enum
 */
class EHelpdeskThreadType extends AEnumeration
{
	const None = 0;
	const Pending = 1;
	const Waiting = 2;
	const Answered = 3;
	const Resolved = 4;
	const Deferred = 5;
}

/**
 * @package Helpdesk
 * @subpackage Enum
 */
class EHelpdeskThreadFilterType extends AEnumeration
{
	const All = 0;
	const PendingOnly = 1;
	const ResolvedOnly = 2;
	const InWork = 3;
	const Open = 4;
	const Archived = 9;
}
