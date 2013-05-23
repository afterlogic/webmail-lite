<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	// remove the following line for real use
	exit('remove this line');

	// Example of removing a domain

	// determining main directory
	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	// utilizing WebMail Pro API
	include_once WM_ROOTPATH.'libraries/afterlogic/api.php';
	if (class_exists('CApi') && CApi::IsValid())
	{
		// Getting required API class
		$oApiDomainsManager = CApi::Manager('domains');

		$sDomainToDelete = 'domain.com';
		if ($oApiDomainsManager->DeleteDomainByName($sDomainToDelete))
		{
			echo 'Domain '.$sDomainToDelete.' is deleted successfully.';
		}
		else
		{
			// failed to delete domain
			echo $oApiDomainsManager->GetLastErrorMessage();
		}
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}