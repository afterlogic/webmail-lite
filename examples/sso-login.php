<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

	// remove the following line for real use
	exit('remove this line');

	// Example of logging into WebMail account using email and password for incorporating into another web application (SSO)

	// utilizing API
	include_once __DIR__.'../libraries/afterlogic/api.php';

	if (class_exists('CApi') && CApi::IsValid())
	{
		header('Location: ../?sso&hash='.CApi::GenerateSsoToken('test@domain.com', 'password'));
	}
	else
	{
		echo 'AfterLogic API isn\'t available';
	}