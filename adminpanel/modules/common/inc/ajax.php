<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CCommonAjaxAction extends ap_CoreModuleHelper
{

	public function DomainsEdit_Pre()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if (!$oDomain && CPost::Has('hiddenDomainId'))
		{
			$iDomainId = (int) CPost::get('hiddenDomainId', 0);
			if ($this->oAdminPanel->HasAccessDomain($iDomainId))
			{
				$oDomain = $this->oModule->getDomain($iDomainId);
				if ($oDomain)
				{
					$this->oAdminPanel->SetMainObject('domain_edit', $oDomain);
				}
			}
		}
	}

	public function DomainsEdit()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if ($oDomain)
		{
			$this->initUpdateDomainByPost($oDomain);
		}
	}

	public function DomainsEdit_Post()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if ($oDomain)
		{
			$this->oAdminPanel->DeleteMainObject('domain_edit');

			if ($this->oModule->updateDomain($oDomain))
			{
				$this->checkBolleanWithMessage(true);
				$this->Ref = '?edit&tab=domains&uid='.$oDomain->IdDomain;
			}
			else
			{
				if (0 < $this->oModule->getLastErrorCode())
				{
					$this->LastError = $this->oModule->GetLastErrorMessage();
				}
				else
				{
					$this->checkBolleanWithMessage(false);
				}
			}
		}
	}

	protected function initUpdateDomainByPost(CDomain &$oDomain)
	{
		$oDomain->OverrideSettings = 0 < $oDomain->IdTenant ? true : CPost::GetCheckBox('chOverrideSettings');

		if ($oDomain->OverrideSettings)
		{
			// Regional settings and domain branding (moved from "webmail" module)

			$oDomain->SiteName = CPost::get('txtSiteName', $oDomain->SiteName);
			$oDomain->AllowUsersChangeInterfaceSettings = CPost::GetCheckBox('chAllowUsersAccessInterfaveSettings');

			$sSelSkin = CPost::get('selSkin', '');
			if (!empty($sSelSkin))
			{
				$aSkins = $this->oModule->GetSkinList();
				if (is_array($aSkins) && in_array($sSelSkin, $aSkins))
				{
					$oDomain->DefaultSkin = $sSelSkin;
				}
			}

			$sSelTab = CPost::get('selTab', '');
			if (!empty($sSelTab))
			{
				$aTabs = $this->oModule->getTabList($oDomain);
				if (is_array($aTabs) && in_array($sSelTab, array_keys($aTabs)))
				{
					$oDomain->DefaultTab = $sSelTab;
				}
			}

			$sSelLanguage = CPost::get('selLanguage', '');
			if (!empty($sSelLanguage))
			{
				$aLangs = $this->oModule->GetLangsList();
				if (is_array($aLangs) && in_array($sSelLanguage, $aLangs))
				{
					$oDomain->DefaultLanguage = $sSelLanguage;
				}
			}

			$sSelTimeZone = CPost::get('selTimeZone', null);
			if (null !== $sSelTimeZone)
			{
				$aTimeZones = $this->oModule->GetTimeZoneList();
				if (is_array($aTimeZones) && isset($aTimeZones[(int) $sSelTimeZone]))
				{
					$oDomain->DefaultTimeZone = $sSelTimeZone;
				}
			}

			if (CPost::Has('radioTimeFormat'))
			{
				$oDomain->DefaultTimeFormat =
					EnumConvert::FromPost(CPost::get('radioTimeFormat'), 'ETimeFormat');
			}

			if (CPost::Has('selDateformat'))
			{
				$oDomain->DefaultDateFormat = CPost::get('selDateformat');
			}
		}
	}
}
