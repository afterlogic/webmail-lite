<?php

namespace ProjectSeven;

/**
 * @category ProjectSeven
 */
class Notifications
{
	const InvalidToken = 101;
	const AuthError = 102;
	const InvalidInputParameter = 103;
	const DataBaseError = 104;
	const LicenseProblem = 105;
	const DemoAccount = 106;

	const CanNotGetMessageList = 201;
	const CanNotGetMessage = 202;
	const CanNotDeleteMessage = 203;
	const CanNotMoveMessage = 204;
	const CanNotMoveMessageQuota = 205;

	const CanNotSaveMessage = 301;
	const CanNotSendMessage = 302;
	const InvalidRecipients = 303;

	const CanNotCreateFolder = 401;
	const CanNotDeleteFolder = 402;
	const CanNotSubscribeFolder = 403;
	const CanNotUnsubscribeFolder = 404;

	const CanNotSaveSettings = 501;

	const CanNotCreateContact = 601;
	const CanNotCreateGroup = 602;
	const CanNotUpdateContact = 603;
	const CanNotUpdateGroup = 604;
	const ContactDataHasBeenModifiedByAnotherApplication = 605;
	const ContactsNotAllowed = 606;

	const CanNotCreateAccount = 701;

	const MailServerError = 901;
	const UnknownError = 999;
}