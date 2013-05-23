<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

class CCommonAjaxAction extends ap_CoreModuleHelper
{

	public function DomainsEdit_Pre()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if (!$oDomain && CPost::Has('hiddenDomainId'))
		{
			$iDomainId = (int) CPost::Get('hiddenDomainId', 0);
			if ($this->oAdminPanel->HasAccessDomain($iDomainId))
			{
				$oDomain = $this->oModule->GetDomain($iDomainId);
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

			if ($this->oModule->UpdateDomain($oDomain))
			{
				$this->checkBolleanWithMessage(true);
				$this->Ref = '?edit&tab=domains&uid='.$oDomain->IdDomain;
			}
			else
			{
				if (0 < $this->oModule->GetLastErrorCode())
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
		$oDomain->OverrideSettings = CPost::GetCheckBox('chOverrideSettings');

		if ($oDomain->OverrideSettings)
		{
			// Regional settings and domain branding (moved from "webmail" module)

			$oDomain->SiteName = CPost::Get('txtSiteName', $oDomain->SiteName);
			$oDomain->AllowUsersChangeInterfaceSettings = CPost::GetCheckBox('chAllowUsersAccessInterfaveSettings');

			$sSelSkin = CPost::Get('selSkin', '');
			if (!empty($sSelSkin))
			{
				$aSkins = $this->oModule->GetSkinList();
				if (is_array($aSkins) && in_array($sSelSkin, $aSkins))
				{
					$oDomain->DefaultSkin = $sSelSkin;
				}
			}

			$sSelLanguage = CPost::Get('selLanguage', '');
			if (!empty($sSelLanguage))
			{
				$aLangs = $this->oModule->GetLangsList();
				if (is_array($aLangs) && in_array($sSelLanguage, $aLangs))
				{
					$oDomain->DefaultLanguage = $sSelLanguage;
				}
			}

			$sSelTimeZone = CPost::Get('selTimeZone', null);
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
				EnumConvert::FromPost(CPost::Get('radioTimeFormat'), 'ETimeFormat');
			}

			if (CPost::Has('selDateformat'))
			{
				$oDomain->DefaultDateFormat = CPost::Get('selDateformat');
			}
		}
	}
}
