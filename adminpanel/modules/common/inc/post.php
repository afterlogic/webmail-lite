<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class CCommonPostAction extends ap_CoreModuleHelper
{

	public function SystemDb()
	{
		$this->oSettings->SetConf('Common/DBType', EDbType::MySQL);
		if (CPost::Has('txtSqlLogin'))
		{
			$this->oSettings->SetConf('Common/DBLogin', CPost::Get('txtSqlLogin'));
		}
		if (CPost::Has('txtSqlPassword') &&
			AP_DUMMYPASSWORD !== (string) CPost::Get('txtSqlPassword'))
		{
			$this->oSettings->SetConf('Common/DBPassword', CPost::Get('txtSqlPassword'));
		}
		if (CPost::Has('txtSqlName'))
		{
			$this->oSettings->SetConf('Common/DBName', CPost::Get('txtSqlName'));
		}
		if (CPost::Has('txtSqlSrc'))
		{
			$this->oSettings->SetConf('Common/DBHost', CPost::Get('txtSqlSrc'));
		}

		if (CPost::GetCheckBox('isTestConnection'))
		{
			CDbCreator::ClearStatic();

			$aConnections =& CDbCreator::CreateConnector($this->oSettings);
			$oConnect = $aConnections[0];
			if ($oConnect)
			{
				$this->LastError = AP_LANG_CONNECTUNSUCCESSFUL;
				try
				{
					if ($oConnect->Connect())
					{
						$this->LastMessage = AP_LANG_CONNECTSUCCESSFUL;
						$this->LastError = '';
					}
				}
				catch (CApiDbException $oException)
				{
					$this->LastError .=
						"\r\n".$oException->getMessage().' ('.((int) $oException->getCode()).')';
				}
			}
			else
			{
				$this->LastError = AP_LANG_CONNECTUNSUCCESSFUL;
			}
			
			$this->oSettings->SaveToXml();
		}
		else
		{
			$this->saveSettingsXmlWithMessage();
		}
		
		return '';
	}

	public function SystemSecurity()
	{
		$bDoSave = true;
		if (CPost::Has('txtNewPassword') && CPost::Has('txtConfirmNewPassword'))
		{
			if ((string) CPost::Get('txtNewPassword') !== (string) CPost::Get('txtConfirmNewPassword'))
			{
				$bDoSave = false;
				$this->LastError = CM_PASSWORDS_NOT_MATCH;
			}
			else if (AP_DUMMYPASSWORD !== (string) CPost::Get('txtNewPassword'))
			{
				$this->oSettings->SetConf('Common/AdminPassword', md5(trim(CPost::Get('txtNewPassword'))));
			}
			
			if ($bDoSave)
			{
				if (CPost::Has('txtUserName'))
				{
					$this->oSettings->SetConf('Common/AdminLogin', CPost::Get('txtUserName'));
				}
				
				$this->saveSettingsXmlWithMessage();
			}
		}
	}
}