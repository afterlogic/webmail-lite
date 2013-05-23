<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	// remove the following line for real use
	exit('remove this line');

	// determining main directory
	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	// utilizing WebMail Pro API
	include_once WM_ROOTPATH.'libraries/afterlogic/api.php';
	if (class_exists('CApi') && CApi::IsValid())
	{
		// Getting required API class
		$oApiDomainsManager = /* @var $oApiDomainsManager CApiDomainsManager */ CApi::Manager('domains');
		
		// Getting required API class
		$oApiUsersManager = /* @var $oApiUsersManager CApiUsersManager */ CApi::Manager('users');

		// Creating domain object
		$oDomain = /* @var $oDomain CDomain */ $oApiDomainsManager->GetDomainByName('domain.com');
		if ($oDomain)
		{
			$oAccount = new CAccount($oDomain);
			
			$oAccount->Email = 'test@domain.com';
			$oAccount->IncomingMailLogin = 'test@domain.com';
			$oAccount->IncomingMailPassword = 'password';
			
			if ($oApiUsersManager->CreateAccount($oAccount, !$oAccount->IsInternal))
			{
				echo 'Account '.$oAccount->Email.' is created successfully.';
			}
			else
			{
				echo $oApiUsersManager->GetLastErrorMessage();
			}
		}
		else
		{
			echo 'Domain doesn\'t exist';
		}
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}
	