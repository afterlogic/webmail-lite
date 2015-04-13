<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CWebMailPostAction extends ap_CoreModuleHelper
{
	public function SystemLogging()
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