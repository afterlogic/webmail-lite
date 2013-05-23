<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class CWebMailPostAction extends ap_CoreModuleHelper
{
	public function ServicesLogging()
	{
		if (isset($_POST['btnClearLog']) || isset($_POST['btnUserActivityClearLog']))
		{
			/* @var $oApiLoggerManager CApiLoggerManager */
			$oApiLoggerManager = CApi::Manager('logger');

			$bResult = false;
			if (isset($_POST['btnClearLog']))
			{
				$bResult = $oApiLoggerManager->DeleteCurrentLog();
			}
			else
			{
				$bResult = $oApiLoggerManager->DeleteCurrentUserActivityLog();
			}

			if ($bResult)
			{
				$this->LastMessage = WM_INFO_LOGCLEARSUCCESSFUL;
			}
			else
			{
				$this->LastError = AP_LANG_ERROR;
			}
		}
		else if ($this->isStandartSubmit())
		{
			$this->oSettings->SetConf('Common/EnableLogging', CPost::GetCheckBox('ch_EnableDebugLogging'));
			$this->oSettings->SetConf('Common/EnableEventLogging', CPost::GetCheckBox('ch_EnableUserActivityLogging'));

			$this->oSettings->SetConf('Common/LoggingLevel', EnumConvert::FromPost(CPost::Get('selVerbosity', ''), 'ELogLevel'));

			$this->checkBolleanWithMessage($this->oSettings->SaveToXml());
		}
	}
	
}