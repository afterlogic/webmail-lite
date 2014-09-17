<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	// remove the following line for real use
	exit('remove this line');

	// utilizing API
	include_once __DIR__.'/../libraries/afterlogic/api.php';
	
	if (class_exists('CApi') && CApi::IsValid())
	{
		// Getting required API class
		$oApiDbManager = CApi::Manager('db');

		$oSettings =& CApi::GetSettings();
		$oSettings->SetConf('Common/DBPrefix', '');

		$sSql = $oApiDbManager->GetSqlSchemaAsString(true);
//		file_put_contents('../SQL-'.gmdate('Y-m-d_H-i-s').'.txt', str_replace("\r", '', $sSql));
		echo $sSql;
	}
	else
	{
		echo 'AfterLogic API isn\'t available';
	}
