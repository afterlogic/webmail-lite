<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CCommonPopulateData extends ap_CoreModuleHelper
{
	public function SystemDb(ap_Standard_Screen &$oScreen)
	{
		if (CApi::GetConf('mailsuite', false))
		{
			$oScreen->Data->SetValue('classSqlTypeVisibility', 'wm_hide');
			$oScreen->Data->SetValue('radioSqlTypeMySQL', true);
		}
		else
		{
			$oScreen->Data->SetValue('radioSqlTypeMySQL', EDbType::MySQL === $this->oSettings->GetConf('Common/DBType'));
			$oScreen->Data->SetValue('radioSqlTypePostgreSQL', EDbType::PostgreSQL === $this->oSettings->GetConf('Common/DBType'));
		}
		
		$oScreen->Data->SetValue('txtSqlLogin', $this->oSettings->GetConf('Common/DBLogin'));
		if (0 < strlen($this->oSettings->GetConf('Common/DBLogin')))
		{
			$oScreen->Data->SetValue('txtSqlPassword', AP_DUMMYPASSWORD);
		}

		$oScreen->Data->SetValue('txtSqlName', $this->oSettings->GetConf('Common/DBName'));
		$oScreen->Data->SetValue('txtSqlSrc', $this->oSettings->GetConf('Common/DBHost'));
		$oScreen->Data->SetValue('txtToken', CApi::GetCsrfToken('p7admToken'));

		$this->oModule->JsAddFile('db.js');
	}

	public function SystemSecurity(ap_Standard_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('txtUserName', $this->oSettings->GetConf('Common/AdminLogin'));
		$oScreen->Data->SetValue('txtOldPassword', AP_DUMMYPASSWORD);
		$oScreen->Data->SetValue('txtToken', CApi::GetCsrfToken('p7admToken'));
		$oScreen->Data->SetValue('txtNewPassword', AP_DUMMYPASSWORD);
		$oScreen->Data->SetValue('txtConfirmNewPassword', AP_DUMMYPASSWORD);
	}

	public function CommonSocial(ap_Standard_Screen &$oScreen)
	{
		$oApiCapa = CApi::Manager('capability');
		/* @var $oApiCapa CApiCapabilityManager */

		if ($oApiCapa && !$this->oAdminPanel->IsOnlyReadAuthType())
		{
			$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();
			if ($oTenant)
			{
				$oScreen->Data->SetValue('Socials', $oTenant->GetSocials());
			}
		}
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
			$oScreen->Data->SetValue('classHideOverrideSettings', 0 < $oDomain->IdTenant ? 'wm_hide' : '');
			$oScreen->Data->SetValue('classHideSsl', $this->oModule->HasSslSupport() ? '' : 'wm_hide');
			$oScreen->Data->SetValue('chOverrideSettings', $oDomain->OverrideSettings);

			$sDomainSkin = $oDomain->DefaultSkin;
			$sDomainLang = $oDomain->DefaultLanguage;
			$sDomainZone = $oDomain->DefaultTimeZone;
			$sDefaultTab = $oDomain->DefaultTab;

			$oScreen->Data->SetValue('txtSiteName', $oDomain->SiteName);

			$oScreen->Data->SetValue('radioTimeFormat12', ETimeFormat::F12 === $oDomain->DefaultTimeFormat);
			$oScreen->Data->SetValue('radioTimeFormat24', ETimeFormat::F24 === $oDomain->DefaultTimeFormat);

			$oScreen->Data->SetValue('optDateFormatDDMonthYYYY', EDateFormat::DD_MONTH_YYYY === $oDomain->DefaultDateFormat);
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

		$sTabsOptions = '';
		$aTabs = $this->oModule->GetTabList($oDomain);
		if (is_array($aTabs))
		{
			foreach ($aTabs as $sKeyTab => $sValueTab)
			{
				$sSelected = ($sKeyTab === $sDefaultTab) ? ' selected="selected"' : '';
				$sTabsOptions .= '<option value="'.ap_Utils::AttributeQuote($sKeyTab)
					.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($sValueTab).'</option>';
			}
			$oScreen->Data->SetValue('selTabsOptions', $sTabsOptions);
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
