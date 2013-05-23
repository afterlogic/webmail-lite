<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
	include_once WM_ROOTPATH.'application/include.php';
	$oInput = new api_Http();

	include_once WM_ROOTPATH.'application/server.php';

	require_once WM_ROOTPATH.'common/inc_constants.php';
	require_once WM_ROOTPATH.'common/class_convertutils.php';
	require_once WM_ROOTPATH.'common/class_mailprocessor.php';
	require_once WM_ROOTPATH.'common/class_folders.php';
	require_once WM_ROOTPATH.'common/class_smtp.php';

	@ob_start();
	@header('Content-type: text/xml; charset=utf-8');

	$sXml = $oInput->GetPost('xml', '');

	$oRequestXml = new CXmlDocument();
	$oRequestXml->ParseFromString($sXml);

	CApi::Log('POST[xml] = '.$sXml);
	unset($sXml);

	$oResultXml = new CXmlDocument();
	$oResultXml->CreateElement('webmail');

	$sAction = $oRequestXml->GetParamValueByName('action');
	$sRequest = $oRequestXml->GetParamValueByName('request');

	$oDomain = null;
	$sLanguageName = CSession::Get(APP_SESSION_LANG, null);
	if (null === $sLanguageName)
	{
		/* @var $oDomain CDomain */
		$oDomain = AppGetDomain();
		$sLanguageName = $oDomain->DefaultLanguage;
	}

	AppIncludeLanguage($sLanguageName);

	$oServer = new CAppServer($oInput, $oRequestXml, $oResultXml);
	$oServer->UseMethod($sAction, $sRequest);

	$sResultXml = $oServer->ResultXml();

	$iMaxLen = 5000;
	$sResultLog = null;
	if ($iMaxLen * 3 < strlen($sResultXml))
	{
		$sResultLog = substr($sResultXml, 0, $iMaxLen).
			API_CRLF.'----------------------- cut -----------------------'.API_CRLF.
			substr($sResultXml, -$iMaxLen);
	}

	$sOutputError = @ob_get_clean();
	if (false !== $sOutputError && 0 < strlen(trim($sOutputError)))
	{
		$sOutputError = strip_tags(trim($sOutputError));
		CApi::Log('OUT[Error] >'.$sOutputError, ELogLevel::Error);
		echo $oServer->GetErrorResponseAsString($sOutputError);
		exit();
	}

	CApi::Log('XML Result >'.API_CRLF.((null !== $sResultLog) ? $sResultLog : $sResultXml));
	echo $sResultXml;
