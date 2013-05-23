<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

class CCommonPopulateData extends ap_CoreModuleHelper
{
	public function SystemDb(ap_Standard_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('txtSqlLogin', $this->oSettings->GetConf('Common/DBLogin'));
		if (0 < strlen($this->oSettings->GetConf('Common/DBLogin')))
		{
			$oScreen->Data->SetValue('txtSqlPassword', AP_DUMMYPASSWORD);
		}

		$oScreen->Data->SetValue('txtSqlName', $this->oSettings->GetConf('Common/DBName'));
		$oScreen->Data->SetValue('txtSqlSrc', $this->oSettings->GetConf('Common/DBHost'));

		$this->oModule->JsAddFile('db.js');
	}

	public function SystemSecurity(ap_Standard_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('txtUserName', $this->oSettings->GetConf('Common/AdminLogin'));
		$oScreen->Data->SetValue('txtNewPassword', AP_DUMMYPASSWORD);
		$oScreen->Data->SetValue('txtConfirmNewPassword', AP_DUMMYPASSWORD);
	}

	public function DomainsMainEdit(ap_Table_Screen &$oScreen)
	{
		$sDomainSkin = $sDomainLang = $sDomainZone = '';

		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if ($oDomain)
		{
			$oScreen->Data->SetValue('hiddenDomainId', $oDomain->IdDomain);

			$oScreen->Data->SetValue('classHideDefault', $oDomain->IsDefaultDomain ? 'wm_hide' : '');
			$oScreen->Data->SetValue('classHideNotDefault', $oDomain->IsDefaultDomain ? '' : 'wm_hide');
			$oScreen->Data->SetValue('chOverrideSettings', $oDomain->OverrideSettings);
			$oScreen->Data->SetValue('classHideSsl', $this->oModule->HasSslSupport() ? '' : 'wm_hide');

			$sDomainSkin = $oDomain->DefaultSkin;
			$sDomainLang = $oDomain->DefaultLanguage;
			$sDomainZone = $oDomain->DefaultTimeZone;

			$oScreen->Data->SetValue('txtSiteName', $oDomain->SiteName);

			$oScreen->Data->SetValue('radioTimeFormat12', ETimeFormat::F12 === $oDomain->DefaultTimeFormat);
			$oScreen->Data->SetValue('radioTimeFormat24', ETimeFormat::F24 === $oDomain->DefaultTimeFormat);

			$oScreen->Data->SetValue('optDateFormatMMDDYY', EDateFormat::MMDDYY === $oDomain->DefaultDateFormat);
			$oScreen->Data->SetValue('optDateFormatDDMMYY', EDateFormat::DDMMYY === $oDomain->DefaultDateFormat);
			$oScreen->Data->SetValue('optDateFormatMMDDYYYY', EDateFormat::MMDDYYYY === $oDomain->DefaultDateFormat);
			$oScreen->Data->SetValue('optDateFormatDDMMYYYY', EDateFormat::DDMMYYYY === $oDomain->DefaultDateFormat);
		}

		$sSkinsOptions = '';
		$aSkins = $this->oModule->GetSkinList();
		if (is_array($aSkins))
		{
			foreach ($aSkins as $sSkin)
			{
				$sSelected = ($sSkin === $sDomainSkin) ? ' selected="selected"' : '';
				$sSkinsOptions .= '<option value="'.ap_Utils::AttributeQuote($sSkin)
					.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($sSkin).'</option>';
			}
			$oScreen->Data->SetValue('selSkinsOptions', $sSkinsOptions);
		}

		$sLanguageOptions = '';
		$aLangs = $this->oModule->GetLangsList();
		if (is_array($aLangs))
		{
			foreach ($aLangs as $sLang)
			{
				$sSelected = ($sLang === $sDomainLang) ? ' selected="selected"' : '';
				$sLanguageOptions .= '<option value="'.ap_Utils::AttributeQuote($sLang)
					.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($sLang).'</option>';
			}
			$oScreen->Data->SetValue('selLanguageOptions', $sLanguageOptions);
		}

		$sTimeZoneOptions = '';
		$aTimeZones = $this->oModule->GetTimeZoneList();
		if (is_array($aTimeZones))
		{
			foreach ($aTimeZones as $iIndex => $aTimeZone)
			{
				$sSelected = ((int) $sDomainZone === (int) $iIndex) ? ' selected="selected"' : '';
				$sTimeZoneOptions .= '<option value="'.ap_Utils::AttributeQuote($iIndex)
				.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($aTimeZone).'</option>';
			}
			$oScreen->Data->SetValue('selTimeZone', $sTimeZoneOptions);
		}
	}
}
