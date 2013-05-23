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
		$oApiWebMailManager = CApi::Manager('webmail');

		@ini_set('max_execution_time', 120);
		@ini_set('memory_limit', '50M');
		@set_time_limit(120);
		@ini_set('display_errors', 'Off');
		@ignore_user_abort(true);

		// removing outdated files
		echo ($oApiWebMailManager->ClearTempFiles()) ? '0' : '1';
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}