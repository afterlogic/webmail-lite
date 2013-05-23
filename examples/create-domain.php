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
		$oApiDomainsManager = CApi::Manager('domains');

		// Creating domain object
		$oDomain = new CDomain('domain.com');

		// Additional modification of domain details
		$oDomain->IncomingMailProtocol = EMailProtocol::IMAP4;
		$oDomain->IncomingMailServer = 'imap.domain.com';
		$oDomain->OutgoingMailServer = 'smtp.domain.com';

		if ($oApiDomainsManager->CreateDomain($oDomain))
		{
			echo 'Domain '.$oDomain->Name.' is created successfully.';
		}
		else
		{
			echo $oApiDomainsManager->GetLastErrorMessage();
		}
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}