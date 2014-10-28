<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CBundlePopulateData extends ap_CoreModuleHelper
{
	/**
	 * @param ap_Table_Screen $oScreen
	 */
	public function UsersMainNew(ap_Table_Screen &$oScreen)
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_filter');
		if ($oDomain && $oDomain->IsInternal)
		{
			$oScreen->Data->SetValue('domainIsInternal', true);
		}
	}

	/**
	 * @param ap_Table_Screen $oScreen
	 */
	public function UsersMainEdit(ap_Table_Screen &$oScreen)
	{
		/* @var $oMailingList CMailingList */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_edit');
		if ($oAccount)
		{
			$oScreen->Data->SetValue('DomainName', $oAccount->Domain->Name);
		}

		/* @var $oMailingList CMailingList */
		$oMailingList =& $this->oAdminPanel->GetMainObject('mailinglist_edit');
		if ($oMailingList)
		{
			$oScreen->Data->SetValue('hiddenMailingListId', $oMailingList->IdMailingList);
			$oScreen->Data->SetValue('hiddenDomainId', $oMailingList->IdDomain);
			$oScreen->Data->SetValue('txtMailingListFriendlyName', $oMailingList->Name);

			$sMembersOptions = '';
			if (0 < count($oMailingList->Members))
			{
				foreach ($oMailingList->Members as $sMember)
				{
					$sMembersOptions .= '<option value="'.ap_Utils::AttributeQuote($sMember).'">'.$sMember.'</option>';
				}
			}
			$oScreen->Data->SetValue('selListMembersDDL', $sMembersOptions);
		}

		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_edit');
		if ($oAccount && $oAccount->Domain && $oAccount->Domain->IsInternal)
		{
			$oScreen->Data->SetValue('domainIsInternal', true);

			$sLogin = $oScreen->Data->GetValue('txtEditLogin');
			if (!empty($sLogin) && false !== strpos($sLogin, '@'))
			{
				$oScreen->Data->SetValue('txtEditLogin', api_Utils::GetAccountNameFromEmail($sLogin));
			}
		}

		/* @var $oMailAliases CMailAliases */
		$oMailAliases =& $this->oAdminPanel->GetMainObject('aliases_edit');
		if ($oMailAliases)
		{
			$oScreen->Data->SetValue('hiddenAccountId', $oMailAliases->IdAccount);

			$sMembersOptions = '';
			if (0 < count($oMailAliases->Aliases))
			{
				foreach ($oMailAliases->Aliases as $sMember)
				{
					$sMembersOptions .= '<option value="'.ap_Utils::AttributeQuote($sMember).'">'.$sMember.'</option>';
				}
			}
			$oScreen->Data->SetValue('selAliasesDDL', $sMembersOptions);
		}

		/* @var $oMailForwards CMailForwards */
		$oMailForwards =& $this->oAdminPanel->GetMainObject('forwards_edit');
		if ($oMailForwards)
		{
			$oScreen->Data->SetValue('hiddenMailingListId', $oMailForwards->IdAccount);

			$sMembersOptions = '';
			if (0 < count($oMailForwards->Forwards))
			{
				foreach ($oMailForwards->Forwards as $sMember)
				{
					$sMembersOptions .= '<option value="'.ap_Utils::AttributeQuote($sMember).'">'.$sMember.'</option>';
				}
			}
			$oScreen->Data->SetValue('selForwardsDDL', $sMembersOptions);
		}
	}

	/**
	 * @param ap_Table_Screen $oScreen
	 */
	public function UsersMainList(ap_Table_Screen &$oScreen)
	{
		$mDomainIndex = (int) $oScreen->GetFilterIndex();
		if (0 < $mDomainIndex)
		{
			$aFilter = $oScreen->GetFilterItem($mDomainIndex);

			$oScreen->Data->SetValue('hiddenDomainId', $mDomainIndex);
			$oScreen->Data->SetValue('txtNewMailingListDomain', isset($aFilter[0]) ? $aFilter[0] : '');
		}
	}

	/**
	 * @param ap_Table_Screen $oScreen
	 */
	public function DomainsMainEdit(ap_Table_Screen &$oScreen)
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if ($oDomain)
		{
			$oScreen->Data->SetValue('txtFilteHrefAdd', '&filter='.$oDomain->IdDomain);
			$oScreen->Data->SetValue('chEnableSignUp', $oDomain->AllowRegistration);
			$oScreen->Data->SetValue('chAllowUsersResetPassword', $oDomain->AllowPasswordReset);
		}
	}
}
