<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	// remove the following line for real use
	exit('remove this line');

	// Example of removing an account

	// determining main directory
	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	// utilizing WebMail Pro API
	include_once WM_ROOTPATH.'libraries/afterlogic/api.php';
	if (class_exists('CApi') && CApi::IsValid())
	{
		// data for deleting the account
		$sAccountToDelete = 'test@localhost';

		// Getting required API class
		$oApiUsersManager = CApi::Manager('users');

		if ($oApiUsersManager->DeleteAccountByEmail($sAccountToDelete))
		{
			echo 'Account '.$sAccountToDelete.' was removed successfully.';
		}
		else
		{
			// failed to delete account
			echo $oApiUsersManager->GetLastErrorMessage();
		}
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}