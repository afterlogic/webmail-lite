<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Users
 * @subpackage Enum
 */
class EAccountSessKey extends AEnumeration
{
	const IdAccount = 'id_account';
	const IdUser = 'AUserId';
	const Lang = 'session_lang';
	const LastLogin = 'session_last_login';
	const AdminLogin = 'awm_admin_login';
}

/**
 * @package Users
 * @subpackage Enum
 */
class EAccountMailMode extends AEnumeration
{
	const DeleteMessagesFromServer = 0;
	const LeaveMessagesOnServer = 1;
	const KeepMessagesOnServer = 2;
	const DeleteMessageWhenItsRemovedFromTrash = 3;
	const KeepMessagesOnServerAndDeleteMessageWhenItsRemovedFromTrash = 4;
}

/**
 * @package Users
 * @subpackage Enum
 */
class EAccountSignatureType extends AEnumeration
{
	const Plain = 0;
	const Html = 1;
}

/**
 * @package Users
 * @subpackage Enum
 */
class EAccountDefaultOrder extends AEnumeration
{
	const DescDate = 0;
	const AscDate = 1;
	const DescFrom = 2;
	const AscFrom = 3;
	const DescTo = 4;
	const AscTo = 5;
	const DescSize = 6;
	const AscSize = 7;
	const DescSubject = 8;
	const AscSubject = 9;
	const DescAttachment = 10;
	const AscAttachment = 11;
	const DescFlag = 12;
	const AscFlag = 13;
}

/**
 * @package Users
 * @subpackage Enum
 */
class EAccountSignatureOptions extends AEnumeration
{
	const DontAdd = 0;
	const AddToAll = 1;
	const AddToNewOnly = 2;
}

/**
 * @package Users
 * @subpackage Enum
 */
class EUserHtmlEditor extends AEnumeration
{
	const Plain = 0;
	const Html = 1;
}

/**
 * @package Users
 * @subpackage Enum
 */
class EIdentityType extends AEnumeration
{
	const Normal = 0;
	const Virtual = 1;
}
