<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', dirname(__FILE__).'/');
	
	@header('Content-type: application/x-javascript; charset=utf-8');
	require_once WM_ROOTPATH.'common/last_modified.php';
	require_once WM_ROOTPATH.'application/include.php';
	require_once WM_ROOTPATH.'common/inc_constants.php';

	@ob_start(CApi::GetConf('js.use-js-gzip', false) ? 'obStartGzip' : 'obStartNoGzip');
	
	/* @var $oApiWebMailManager CApiWebmailManager */
	$oApiWebMailManager = CApi::Manager('webmail');
	$oInput = new api_Http();

	$sType = $oInput->GetQuery('t', '');
	if (!empty($sType))
	{
		echo $oApiWebMailManager->GetJsSource($sType);
	}
