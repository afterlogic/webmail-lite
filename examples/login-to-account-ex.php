<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	// remove the following line for real use
	exit('remove this line');

	// Example of logging into WebMail account using email and password for incorporating into another web application

	// determining main directory
	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	// utilizing WebMail Pro API
	include_once WM_ROOTPATH.'libraries/afterlogic/api.php';
	if (class_exists('CApi') && CApi::IsValid())
	{
		// data for logging into account
		$sEmail = 'test@domain.com';
		$sLogin = 'test@domain.com';
		$sPassword = 'password';

		// Getting required API class

		/* @var $oApiWebMailManager CApiWebmailManager */
		$oApiWebMailManager = CApi::Manager('webmail');

		// attempting to obtain object for account we're trying to log into
		$oAccount = $oApiWebMailManager->LoginToAccountEx($sEmail, $sLogin, $sPassword,
			EMailProtocol::IMAP4, 'imap.dom.local', 143, 'smtp.dom.local', 25, true);
		
		if ($oAccount)
		{
			// populating session data from the account
			$oAccount->FillSession();

			// redirecting to WebMail
			$oApiWebMailManager->JumpToWebMail('../webmail.php?check=1');
		}
		else
		{
			// login error
			echo $oApiWebMailManager->GetLastErrorMessage();
		}
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}