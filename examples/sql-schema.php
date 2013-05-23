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
		$oApiDbManager = CApi::Manager('db');

		$oSettings =& CApi::GetSettings();
		$oSettings->SetConf('Common/DBPrefix', '');

		$sSql = $oApiDbManager->GetSqlSchemaAsString(true);
//		file_put_contents('../build/sql/SQL-'.gmdate('Y-m-d_H-i-s').'.txt', str_replace("\r", '', $sSql));
		echo $sSql;
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}
