<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CProPopulateData extends ap_CoreModuleHelper
{
	public function CommonBranding(ap_Standard_Screen &$oScreen)
	{
		$oTenant = $this->oModule->GetTenantAdminObject();
		/* @var $oTenant CTenant */
		if ($oTenant)
		{
			$oScreen->Data->SetValue('txtLoginStyleImage', $oTenant->LoginStyleImage);
			$oScreen->Data->SetValue('txtAppStyleImage', $oTenant->AppStyleImage);
		}
	}
	
	public function CommonHelpdesk(ap_Standard_Screen &$oScreen)
	{
		$oApiCapa = CApi::Manager('capability');
		if ($oApiCapa && $oApiCapa->IsHelpdeskSupported() && (
			$this->oAdminPanel->IsTenantAuthType() ||
			($this->oAdminPanel->IsSuperAdminAuthType() && !$oApiCapa->IsTenantsSupported())
		))
		{
			$oHttp = \MailSo\Base\Http::SingletonInstance();
			$sUrl = preg_replace('/\/adminpanel.+$/i', '/?helpdesk', $oHttp->GetFullUrl());

			$sEmail = '';
			$oTenant = $this->oModule->GetTenantAdminObject();
			
			/* @var $oTenant CTenant */
			if ($oTenant && $oTenant->IsHelpdeskSupported())
			{
				$sEmail = $oTenant->HelpdeskAdminEmailAccount;
				if (!$oTenant->IsDefault)
				{
					$sUrl.= '='.substr(md5($oTenant->IdTenant.CApi::$sSalt), 0, 8);
				}

				$oScreen->Data->SetValue('txtClientsHelpdeskURL', $sUrl);
				$oScreen->Data->SetValue('txtAdminEmailAccount', $sEmail);
				$oScreen->Data->SetValue('chHelpdeskAllowFetcher', $oTenant->HelpdeskAllowFetcher);
				$oScreen->Data->SetValue('txtClientIframeUrl', $oTenant->HelpdeskClientIframeUrl);
				$oScreen->Data->SetValue('txtAgentIframeUrl', $oTenant->HelpdeskAgentIframeUrl);
				$oScreen->Data->SetValue('txtHelpdeskSiteName', $oTenant->HelpdeskSiteName);
				$oScreen->Data->SetValue('chHelpdeskStyleAllow', $oTenant->HelpdeskStyleAllow);
				$oScreen->Data->SetValue('txtHelpdeskStyleImage', $oTenant->HelpdeskStyleImage);
				$oScreen->Data->SetValue('txtHelpdeskStyleText', $oTenant->GetHelpdeskStyleText());

				$oScreen->Data->SetValue('radioHelpdeskFetcherTypeNone', EHelpdeskFetcherType::NONE === $oTenant->HelpdeskFetcherType);
				$oScreen->Data->SetValue('radioHelpdeskFetcherTypeReply', EHelpdeskFetcherType::REPLY === $oTenant->HelpdeskFetcherType);
				$oScreen->Data->SetValue('radioHelpdeskFetcherTypeAll', EHelpdeskFetcherType::ALL === $oTenant->HelpdeskFetcherType);

				$oScreen->Data->SetValue('chHelpdeskFacebookAllow', $oTenant->HelpdeskFacebookAllow);
				$oScreen->Data->SetValue('txtHelpdeskFacebookId', $oTenant->HelpdeskFacebookId);
				$oScreen->Data->SetValue('txtHelpdeskFacebookSecret', $oTenant->HelpdeskFacebookSecret);
				$oScreen->Data->SetValue('chHelpdeskGoogleAllow', $oTenant->HelpdeskGoogleAllow);
				$oScreen->Data->SetValue('txtHelpdeskGoogleId', $oTenant->HelpdeskGoogleId);
				$oScreen->Data->SetValue('txtHelpdeskGoogleSecret', $oTenant->HelpdeskGoogleSecret);
				$oScreen->Data->SetValue('chHelpdeskTwitterAllow', $oTenant->HelpdeskTwitterAllow);
				$oScreen->Data->SetValue('txtHelpdeskTwitterId', $oTenant->HelpdeskTwitterId);
				$oScreen->Data->SetValue('txtHelpdeskTwitterSecret', $oTenant->HelpdeskTwitterSecret);
			}
				
			$oScreen->Data->SetValue('classInfoEmptyEmail', 'wm_hide');
			$oScreen->Data->SetValue('classInfoUnknownEmail', 'wm_hide');

			if (0 < strlen($sEmail))
			{
				if (!$this->oModule->ValidateHelpdeskEmail($sEmail))
				{
					$oScreen->Data->SetValue('classInfoUnknownEmail', '');
				}
			}
			else
			{
				$oScreen->Data->SetValue('classInfoEmptyEmail', '');
			}
		}
	}

	public function CommonSip(ap_Standard_Screen &$oScreen)
	{
		$oApiCapa = CApi::Manager('capability');
		/* @var $oApiCapa CApiCapabilityManager */

		if ($oApiCapa && $oApiCapa->IsSipSupported())
		{
			$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();
			if ($oTenant && $oTenant->IsSipSupported() &&
				($oTenant->IsDefault || $oTenant->SipAllowConfiguration))
			{
				$oScreen->Data->SetValue('chAllowSip', $oTenant->SipAllow);
				$oScreen->Data->SetValue('txtSipRealm', $oTenant->SipRealm);
				$oScreen->Data->SetValue('txtSipWebsocketProxyUrl', $oTenant->SipWebsocketProxyUrl);
				$oScreen->Data->SetValue('txtSipOutboundProxyUrl', $oTenant->SipOutboundProxyUrl);
				$oScreen->Data->SetValue('txtSipCallerID', $oTenant->SipCallerID);
			}
		}
	}
	
	public function CommonTwilio(ap_Standard_Screen &$oScreen)
	{
		$oApiCapa = CApi::Manager('capability');
		/* @var $oApiCapa CApiCapabilityManager */

		if ($oApiCapa && $oApiCapa->IsTwilioSupported())
		{
			$oTenant = /* @var $oTenant CTenant */  $this->oModule->GetTenantAdminObject();
			if ($oTenant && $oTenant->IsTwilioSupported() &&
				($oTenant->IsDefault || $oTenant->TwilioAllowConfiguration))
			{
				$oScreen->Data->SetValue('chAllowTwilio', $oTenant->TwilioAllow);
				$oScreen->Data->SetValue('txtTwilioPhoneNumber', $oTenant->TwilioPhoneNumber);
				$oScreen->Data->SetValue('txtTwilioAccountSID', $oTenant->TwilioAccountSID);
				$oScreen->Data->SetValue('txtTwilioAuthToken', $oTenant->TwilioAuthToken);
				$oScreen->Data->SetValue('txtTwilioAppSID', $oTenant->TwilioAppSID);
			}
		}
	}

	public function CommonResourceUsage(ap_Standard_Screen &$oScreen)
	{
		if ($this->oAdminPanel->IsTenantAuthType())
		{
			$oTenant = $this->oModule->GetTenantAdminObject();
			if ($oTenant && !$oTenant->IsDefault)
			{
				$iUsedUsers = $oTenant->GetUserCount();
				if (0 < $oTenant->UserCountLimit)
				{
					$oScreen->Data->SetValue('txtUsers', $iUsedUsers.' '.
						CApi::I18N('ADMIN_PANEL/RESOURCES_USERS_MAX', array(
							'USERS' => $oTenant->UserCountLimit
						))
					);
				}
				else
				{
					$oScreen->Data->SetValue('txtUsers', $iUsedUsers.' of available');
				}

				$iUsed = 0;
				$sInfo = '';
				if (0 < $oTenant->QuotaInMB)
				{
					$iUsed = floor(($oTenant->AllocatedSpaceInMB / $oTenant->QuotaInMB) * 100);
					$sInfo = $iUsed.'% ('.$oTenant->AllocatedSpaceInMB.' MB) '.CApi::I18N('ADMIN_PANEL/RESOURCES_DISK_OF').' '.$oTenant->QuotaInMB.' MB '.CApi::I18N('ADMIN_PANEL/RESOURCES_DISK_ALLOC');
				}
				else
				{
					$sInfo = $oTenant->AllocatedSpaceInMB.' MB '.CApi::I18N('ADMIN_PANEL/RESOURCES_DISK_ALLOC');
				}

				$oScreen->Data->SetValue('txtDiskSpace', $sInfo);

				$sSubscriptions = '';
// TODO subscriptions
//				if (CApi::GetConf('capa', false))
//				{
//					$oSubscriptionsApi = CApi::Manager('subscriptions');
//					$oTenantsApi = CApi::Manager('tenants');
//
//					if ($oSubscriptionsApi && $oTenantsApi)
//					{
//						$aSubscriptions = $oSubscriptionsApi->GetSubscriptions($oTenant->IdTenant);
//						if (is_array($aSubscriptions) && 0 < count($aSubscriptions))
//						{
//							$oScreen->Data->SetValue('subscriptionsSupported', true);
//
//							$aLimits = $oTenantsApi->GetSubscriptionUserUsage($oTenant->IdTenant);
//
//							foreach ($aSubscriptions as $oSubscription)
//							{
//								/* @var $oSubscription CSubscription */
//
//								$sSubscriptions .= $oSubscription->Name.' ('.
//									(isset($aLimits[$oSubscription->IdSubscription]) ? $aLimits[$oSubscription->IdSubscription] : 0).
//									' user of '.
//									(0 === $oSubscription->Limit ? 'unlim' : $oSubscription->Limit).' available)'.'<br />';
//							}
//
//							$oScreen->Data->SetValue('txtSubscriptionPlans', $sSubscriptions);
//						}
//					}
//				}

				if (empty($sSubscriptions))
				{
					$oScreen->Data->SetValue('hideClassForSubscription', 'wm_hide');
				}
			}
		}
	}
	public function CommonTenant(ap_Standard_Screen &$oScreen)
	{
		if ($this->oAdminPanel->IsTenantAuthType())
		{
			$oTenant = $this->oModule->GetTenantAdminObject();
			if (/* @var $oTenant CTenant */$oTenant && !$oTenant->IsDefault)
			{
				$oScreen->Data->SetValue('txtTenantName', $oTenant->Login);
				$oScreen->Data->SetValue('txtTenantAdminEmailText',	0 === strlen($oTenant->Email) ? '(empty)' : $oTenant->Email);
				$oScreen->Data->SetValue('txtTenantAdminEmailInput', $oTenant->Email);
				$oScreen->Data->SetValue('txtTenantPassword', API_DUMMY);

				$oScreen->Data->SetValue('classTenantPasswordHideClass', $oTenant->AllowChangeAdminPassword ? '' : 'wm_hide');
				$oScreen->Data->SetValue('classTenantEmailTextHideClass', $oTenant->AllowChangeAdminEmail ? 'wm_hide' : '');
				$oScreen->Data->SetValue('classTenantEmailInputHideClass', $oTenant->AllowChangeAdminEmail ? '' : 'wm_hide');
			}
		}
	}
	
	public function UsersMainNew(ap_Table_Screen &$oScreen)
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_filter');
		if ($oDomain)
		{
			$oScreen->Data->SetValue('hiddenDomainId', $oDomain->IdDomain);

			if ($oDomain->IsDefaultDomain)
			{
				$oScreen->Data->SetValue('optIncomingProtocolIMAP', EMailProtocol::IMAP4 === $oDomain->IncomingMailProtocol);
				$oScreen->Data->SetValue('optIncomingProtocolPOP3', EMailProtocol::POP3 === $oDomain->IncomingMailProtocol);

				$oScreen->Data->SetValue('txtIncomingMailHost', $oDomain->IncomingMailServer);
				$oScreen->Data->SetValue('txtIncomingMailPort', $oDomain->IncomingMailPort);
				$oScreen->Data->SetValue('chIncomingUseSSL', $oDomain->IncomingMailUseSSL);

				$oScreen->Data->SetValue('txtOutgoingMailHost', $oDomain->OutgoingMailServer);
				$oScreen->Data->SetValue('txtOutgoingMailPort', $oDomain->OutgoingMailPort);
				$oScreen->Data->SetValue('chOutgoingUseSSL', $oDomain->OutgoingMailUseSSL);

				$oScreen->Data->SetValue('chOutgoingAuth',
					ESMTPAuthType::NoAuth !== $oDomain->OutgoingMailAuth);
			}

			$oScreen->Data->SetValue('txtEditStorageQuota', 0);

			if (0 < $oDomain->IsInternal)
			{
				$oScreen->Data->SetValue('domainIsInternal', true);
			}
			
			if (0 < $oDomain->IdTenant)
			{
				$oScreen->Data->SetValue('domainInTenant', true);

				$oTenantsApi = CApi::Manager('tenants');
				/* @var $oTenantsApi CApiTenantsManager */

				if ($oTenantsApi)
				{
					$oTenant = $oTenantsApi->GetTenantById($oDomain->IdTenant);
					if ($oTenant && 0 < $oTenant->QuotaInMB)
					{
						if (0 < $oTenant->UserCountLimit)
						{
							$oScreen->Data->SetValue('txtEditStorageQuota', ceil($oTenant->QuotaInMB / $oTenant->UserCountLimit));
						}
						else
						{
							$oScreen->Data->SetValue('txtEditStorageQuota', 1000);
						}
					}
				}

// TODO subscriptions
//				if (CApi::GetConf('capa', false))
//				{
//					$oSubscriptionsApi = CApi::Manager('subscriptions');
//					if ($oSubscriptionsApi)
//					{
//						$aSubscriptions = $oSubscriptionsApi->GetSubscriptions($oDomain->IdTenant);
//						if (is_array($aSubscriptions) && 0 < count($aSubscriptions))
//						{
//							$oScreen->Data->SetValue('subscriptionsSupported', true);
//
//							$sSubscriptionOptions = '<option value="0" selected="selected">Default</option>';
//							foreach ($aSubscriptions as $oSubscription)
//							{
//								/* @var $oSubscription CSubscription */
//								$sSubscriptionOptions .= '<option value="'.ap_Utils::AttributeQuote($oSubscription->IdSubscription).
//									'">'.ap_Utils::EncodeSpecialXmlChars($oSubscription->Name).'</option>';
//							}
//
//							if (0 < strlen($sSubscriptionOptions))
//							{
//								$sSubscriptionOptions = '<select name="selSubscribtions" id="selSubscribtions" class="wm_select override">'.
//									$sSubscriptionOptions.'</select>';
//							}
//
//							$oScreen->Data->SetValue('selSubscribtionsOptions', $sSubscriptionOptions);
//						}
//					}
//				}
			}
		}
	}

	public function UsersMainEdit(ap_Table_Screen &$oScreen)
	{
		/* @var $oAccount CAccount */
		$oAccount =& $this->oAdminPanel->GetMainObject('account_edit');
		if ($oAccount)
		{
			$oScreen->Data->SetValue('hiddenAccountId', $oAccount->IdAccount);
			$oScreen->Data->SetValue('hiddenUserId', $oAccount->IdUser);
			$oScreen->Data->SetValue('hiddenDomainId', $oAccount->IdDomain);
			$oScreen->Data->SetValue('chEnableUser', !$oAccount->IsDisabled);
			$oScreen->Data->SetValue('txtEditLogin', $oAccount->IncomingMailLogin);
			$oScreen->Data->SetValue('txtEditPassword', AP_DUMMYPASSWORD);

			if ($oAccount->IsInternal)
			{
				$oScreen->Data->SetValue('domainIsInternal', true);
			}

			$oCapabilityApi = CApi::Manager('capability');
			/* @var $oCapabilityApi CApiCapabilityManager */

			if (0 < $oAccount->IdTenant)
			{
				$oScreen->Data->SetValue('domainInTenant', true);

				// TODO subscriptions
//				if (CApi::GetConf('capa', false))
//				{
//					$oTenantsApi = CApi::Manager('tenants');
//					/* @var $oTenantsApi CApiTenantsManager */
//
//
//					if ($oTenantsApi && $oCapabilityApi)
//					{
//						$oTenant = $oTenantsApi->GetTenantById($oAccount->IdTenant);
//						if ($oTenant)
//						{
//							$oCurrentSubscription = null;
//
//							$oSubscriptionsApi = CApi::Manager('subscriptions');
//							/* @var $oSubscriptionsApi CApiSubscriptionsManager */
//
//							$aSubscriptions = $oSubscriptionsApi ? $oSubscriptionsApi->GetSubscriptions($oAccount->IdTenant) : array();
//							if (is_array($aSubscriptions) && 0 < count($aSubscriptions))
//							{
//								$oScreen->Data->SetValue('subscriptionsSupported', true);
//
//								$sSubscriptionText = '';
//								$sSubscriptionOptions = '<option value="0">Default</option>';
//
//								foreach ($aSubscriptions as $oSubscription)
//								{
//									/* @var $oSubscription CSubscription */
//
//									if (!$oCurrentSubscription && $oAccount->User->IdSubscription === $oSubscription->IdSubscription)
//									{
//										$oCurrentSubscription = $oSubscription;
//									}
//
//									$sSelected = ($oAccount->User->IdSubscription === $oSubscription->IdSubscription) ? ' selected="selected"' : '';
//									$sSubscriptionOptions .= '<option value="'.ap_Utils::AttributeQuote($oSubscription->IdSubscription)
//										.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($oSubscription->Name).'</option>';
//								}
//
//								if (0 < strlen($sSubscriptionOptions))
//								{
//									$sSubscriptionOptions = '<select name="selSubscribtions" id="selSubscribtions" class="wm_select override">'.
//										$sSubscriptionOptions.'</select>';
//								}
//
//								$oScreen->Data->SetValue('selSubscribtionsOptions', 0 < strlen($sSubscriptionOptions)
//									? $sSubscriptionOptions : $sSubscriptionText);
//							}
//
//							$sTenantCapa = '' === $oTenant->Capa ? $oCapabilityApi->GetSystemCapaAsString() : $oTenant->Capa;
//
//							$sSubscriptionCapa = $oCurrentSubscription && '' === $oCurrentSubscription->Capa ? $oCapabilityApi->GetSystemCapaAsString() :
//								($oCurrentSubscription ? $oCurrentSubscription->Capa : $oCapabilityApi->GetSystemCapaAsString());
//
//							$sUserCapa = '' === $oAccount->User->Capa ? $oCapabilityApi->GetSystemCapaAsString() : $oAccount->User->Capa;
//
//							$bGAB = false !== strpos($sTenantCapa, ECapa::GAB);
//							$bSubscriptionGAB = $bGAB ? false !== strpos($sSubscriptionCapa, ECapa::GAB) && $oAccount->Domain->AllowContacts : false;
//							$bUserGAB = $bSubscriptionGAB ? false !== strpos($sUserCapa, ECapa::GAB) : false;
//							$bFiles = false !== strpos($sTenantCapa, ECapa::FILES);
//							$bSubscriptionFiles = $bFiles ? false !== strpos($sSubscriptionCapa, ECapa::FILES) && $oAccount->Domain->AllowFiles : false;
//							$bUserFiles = $bSubscriptionFiles ? false !== strpos($sUserCapa, ECapa::FILES) : false;
//							$bHelpdesk = false !== strpos($sTenantCapa, ECapa::HELPDESK);
//							$bSubscriptionHelpdesk = $bHelpdesk ? false !== strpos($sSubscriptionCapa, ECapa::HELPDESK) && $oAccount->Domain->AllowHelpdesk : false;
//							$bUserHelpdesk = $bSubscriptionHelpdesk ? false !== strpos($sUserCapa, ECapa::HELPDESK) : false;
//
//							$sChsSubscribtions = '';
//							if ($bGAB)
//							{
//								$sChsSubscribtions .= '<input class="wm_checkbox" id="chExtGAB" name="chExtGAB" type="checkbox"/ value="1"'.
//									($bSubscriptionGAB ? '' : ' disabled="disabled"').
//									($bUserGAB ? ' checked="checked"' : '').
//									' /><label for="chExtGAB" id="chExtGAB_label" style="'.($bSubscriptionGAB ? '' : 'color:#aaa;cursor:default').'">Global Address Book</label><br /><br />';
//							}
//
//							if ($bFiles)
//							{
//								$sChsSubscribtions .= '<input class="wm_checkbox" id="chExtFiles" name="chExtFiles" type="checkbox"/ value="1"'.
//									($bSubscriptionFiles ? '' : ' disabled="disabled"').
//									($bUserFiles ? ' checked="checked"' : '').
//									' /><label for="chExtFiles" id="chExtFiles_label" style="'.($bSubscriptionFiles ? '' : 'color:#aaa;cursor:default').'">Files</label><br /><br />';
//							}
//
//							if ($bHelpdesk)
//							{
//								$sChsSubscribtions .= '<input class="wm_checkbox" id="chExtHelpdesk" name="chExtHelpdesk" type="checkbox"/ value="1"'.
//									($bSubscriptionHelpdesk ? '' : ' disabled="disabled"').
//									($bUserHelpdesk ? ' checked="checked"' : '').
//									' /><label for="chExtHelpdesk" id="chExtHelpdesk_label" style="'.($bSubscriptionHelpdesk ? '' : 'color:#aaa;cursor:default').'">Helpdesk</label><br /><br />';
//							}
//
//							$oScreen->Data->SetValue('chsSubscribtions', $sChsSubscribtions);
//						}
//					}
//				}
			}

			$sUsedDesc = api_Utils::GetFriendlySize($oAccount->StorageUsedSpace * 1024);
			if (0 < $oAccount->StorageUsedSpace && 0 < $oAccount->StorageQuota)
			{
				$iUsed = floor(($oAccount->StorageUsedSpace / $oAccount->StorageQuota) * 100);
				$sUsedDesc .= ' ('.$iUsed.'% used)';
			}

			$oScreen->Data->SetValue('txtEditStorageQuota', ceil($oAccount->StorageQuota / 1024));
			$oScreen->Data->SetValue('txtUsedSpaceDesc', $sUsedDesc);

			$oScreen->Data->SetValue('chSipEnable', $oAccount->User->SipEnable);
			$oScreen->Data->SetValue('txtSipImpi', $oAccount->User->SipImpi);
			$oScreen->Data->SetValue('txtSipPassword', API_DUMMY);
			$oScreen->Data->SetValue('txtTwilioNumber', $oAccount->User->TwilioNumber);
			$oScreen->Data->SetValue('chTwilioEnable', $oAccount->User->TwilioEnable);
			$oScreen->Data->SetValue('chTwilioDefaultNumber', $oAccount->User->	TwilioDefaultNumber);

			$oScreen->Data->SetValue('txtFullName', $oAccount->FriendlyName);

			if ($oCapabilityApi->IsGlobalContactsSupported($oAccount, false))
			{
				$oScreen->Data->SetValue('isGlobalContactsSupported', true);
				$oScreen->Data->SetValue('chHideInGAB', $oAccount->HideInGAB);
			}

//			if ($this->oAdminPanel->IsSuperAdminAuthType())
//			{
//				$oScreen->Data->SetValue('hrefLoginToAccount', AP_INDEX_FILE.'?blank&type=login&id='.$oAccount->IdAccount);
//			}
//			else
//			{
				$oScreen->Data->SetValue('classLoginToAccount', 'wm_hide');
//			}
		}
	}

	public function DomainsMainEdit(ap_Table_Screen &$oScreen)
	{
		$iContactsGABVisibility = EContactsGABVisibility::Off;

		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_edit');
		if ($oDomain)
		{
			$oScreen->Data->SetValue('chEnableCalendar', $oDomain->AllowCalendar);
			$oScreen->Data->SetValue('chShowWeekends', $oDomain->CalendarShowWeekEnds);
			$oScreen->Data->SetValue('chShowWorkday', $oDomain->CalendarShowWorkDay);

			$oScreen->Data->SetValue('chEnableFiles', $oDomain->AllowFiles);
			$oScreen->Data->SetValue('chEnableHelpdesk', $oDomain->AllowHelpdesk);

			$oScreen->Data->SetValue('optWeekStartsOnSaturday',
				ECalendarWeekStartOn::Saturday === $oDomain->CalendarWeekStartsOn);
			$oScreen->Data->SetValue('optWeekStartsOnSunday',
				ECalendarWeekStartOn::Sunday === $oDomain->CalendarWeekStartsOn);
			$oScreen->Data->SetValue('optWeekStartsOnMonday',
				ECalendarWeekStartOn::Monday === $oDomain->CalendarWeekStartsOn);

			$oScreen->Data->SetValue('radioDefaultTabDay',
				ECalendarDefaultTab::Day === $oDomain->CalendarDefaultTab);
			$oScreen->Data->SetValue('radioDefaultTabWeek',
				ECalendarDefaultTab::Week === $oDomain->CalendarDefaultTab);
			$oScreen->Data->SetValue('radioDefaultTabMonth',
				ECalendarDefaultTab::Month === $oDomain->CalendarDefaultTab);

			$iWorkdayStarts = $oDomain->CalendarWorkdayStarts;
			$iWorkdayEnds = $oDomain->CalendarWorkdayEnds;

			$sWorkdayStartsOptions = '';
			$aWorkdayStartsList = range(0, 23);
			foreach ($aWorkdayStartsList as $iWorkdayStartsCount)
			{
				$sWorkdayStartsView = (9 < $iWorkdayStartsCount)
				? $iWorkdayStartsCount.':00' : '0'.$iWorkdayStartsCount.':00';
				$sSelected = ($iWorkdayStartsCount === $iWorkdayStarts) ? ' selected="selected"' : '';
				$sWorkdayStartsOptions .= '<option value="'.$iWorkdayStartsCount
				.'"'.$sSelected.'>'.$sWorkdayStartsView.'</option>';
			}
			$oScreen->Data->SetValue('selWorkdayStartsOptions', $sWorkdayStartsOptions);

			$sWorkdayEndsOptions = '';
			$aWorkdayEndsList = range(0, 23);
			foreach ($aWorkdayEndsList as $iWorkdayEndsCount)
			{
				$sWorkdayEndsView = (9 < $iWorkdayEndsCount)
				? $iWorkdayEndsCount.':00' : '0'.$iWorkdayEndsCount.':00';
				$sSelected = ($iWorkdayEndsCount === $iWorkdayEnds) ? ' selected="selected"' : '';
				$sWorkdayEndsOptions .= '<option value="'.$iWorkdayEndsCount
				.'"'.$sSelected.'>'.$sWorkdayEndsView.'</option>';
			}
			$oScreen->Data->SetValue('selWorkdayEndsOptions', $sWorkdayEndsOptions);

			$oScreen->Data->SetValue('bRType', $this->oAdminPanel->RType());

			$oScreen->Data->SetValue('optGlobalAddressBookOff', true);

			$iContactsGABVisibility = $oDomain->GlobalAddressBook;
			$oScreen->Data->SetValue('optGlobalAddressBookOff',
				EContactsGABVisibility::Off === $iContactsGABVisibility);
			$oScreen->Data->SetValue('optGlobalAddressBookDomain',
				EContactsGABVisibility::DomainWide === $iContactsGABVisibility);
			$oScreen->Data->SetValue('optGlobalAddressBookTenant',
					EContactsGABVisibility::TenantWide === $iContactsGABVisibility);
			$oScreen->Data->SetValue('optGlobalAddressBookSystem',
				EContactsGABVisibility::SystemWide === $iContactsGABVisibility);

			$oScreen->Data->SetValue('classHideUseThreads', 
				$oDomain->IsDefaultDomain || !$this->oSettings->GetConf('WebMail/UseThreadsIfSupported') ? 'wm_hide' : '');
			
			$oScreen->Data->SetValue('chUseThreads', $oDomain->UseThreads);
			$oScreen->Data->SetValue('chAllowUsersAddNewAccounts', $oDomain->AllowUsersAddNewAccounts);
			$oScreen->Data->SetValue('chAllowOpenPGP', $oDomain->AllowOpenPGP);
		}

	}

	public function SystemLicensing(ap_Standard_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('txtLicenseKey',
			$this->oAdminPanel->IsOnlyReadAuthType() ? PRO_DEMO_LKEY : $this->oModule->GetLicenseKey());

		$oScreen->Data->SetValue('txtCurrentNumberOfUsers', $this->oModule->GetCurrentNumberOfUsers());
		$oScreen->Data->SetValue('txtLicenseType', $this->oModule->GetUserNumberLimit());
		$oScreen->Data->SetValue('classHideTrialText', $this->oModule->IsTrial() ? '' : 'wm_hide');

		if ($this->oAdminPanel->AType)
		{
			$oScreen->Data->SetValue('linkLicensePurchase', 'http://www.afterlogic.com/purchase/aurora');
		}
		else
		{
			$oScreen->Data->SetValue('linkLicensePurchase', 'http://www.afterlogic.com/purchase/webmail-pro');
		}
	}

	public function TenantsMainNew(ap_Table_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('chTenantEnabled', true);
		$oScreen->Data->SetValue('chTenantSipConfiguration', false);
		$oScreen->Data->SetValue('chTenantTwilioConfiguration', false);
		$oScreen->Data->SetValue('hideClassForNewTenant', 'wm_hide');
		$oScreen->Data->SetValue('hideClassForSubscription', 'wm_hide');
		$oScreen->Data->SetValue('hideClassForEditTenant', '');
		$oScreen->Data->SetValue('txtQuota', 0);
		$oScreen->Data->SetValue('txtUserLimit', 0);

		$oScreen->Data->SetValue('classCapa', 'wm_hide');
		if (CApi::GetConf('capa', false))
		{
			$oTenant = $this->oModule->GetTenantAdminObject();
			if ($oTenant && $oTenant->IsDefault)
			{
				$oScreen->Data->SetValue('classCapa', '');
				$oScreen->Data->SetValue('txtCapa', $oTenant->Capa);
			}
		}
	}

	public function ChannelsMainNew(ap_Table_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('hideClassForNewChannel', 'wm_hide');
		$oScreen->Data->SetValue('hideClassForEditChannel', '');

		/* @var $oChannel CChannel */
		$oChannel =& $this->oAdminPanel->GetMainObject('channel_edit');
		if ($oChannel)
		{
			$oScreen->Data->SetValue('intChannelId', $oChannel->IdChannel);
			$oScreen->Data->SetValue('txtLogin', $oChannel->Login);
			$oScreen->Data->SetValue('txtPassword', $oChannel->Password);
			$oScreen->Data->SetValue('txtDescription', $oChannel->Description);
		}
	}

	public function TenantsMainEdit(ap_Table_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('hideClassForNewTenant', '');
		$oScreen->Data->SetValue('hideClassForEditTenant', 'wm_hide');

		/* @var $oTenant CTenant */
		$oTenant =& $this->oAdminPanel->GetMainObject('tenant_edit');
		if ($oTenant && 0 < $oTenant->IdTenant)
		{
			$oScreen->Data->SetValue('intTenantId', $oTenant->IdTenant);
			$oScreen->Data->SetValue('txtLogin', $oTenant->Login);
			$oScreen->Data->SetValue('txtEmail', $oTenant->Email);
			$oScreen->Data->SetValue('txtDescription', $oTenant->Description);
			$oScreen->Data->SetValue('txtPassword',
				0 === strlen($oTenant->PasswordHash) ? '' : AP_DUMMYPASSWORD);

			$oScreen->Data->SetValue('chTenantEnabled', !$oTenant->IsDisabled);
			$oScreen->Data->SetValue('chTenantSipConfiguration', !!$oTenant->SipAllowConfiguration);
			$oScreen->Data->SetValue('chTenantTwilioConfiguration', !!$oTenant->TwilioAllowConfiguration);
			$oScreen->Data->SetValue('chEnableAdminLogin', $oTenant->IsEnableAdminPanelLogin);

			$oScreen->Data->SetValue('classCapa', 'wm_hide');
			if (CApi::GetConf('capa', false))
			{
				$oScreen->Data->SetValue('classCapa', '');
				$oScreen->Data->SetValue('txtCapa', $oTenant->Capa);
			}

			if (0 < $oTenant->IdChannel)
			{
				$oChannelsApi = CApi::Manager('channels');
				if ($oChannelsApi)
				{
					$oChannel = $oChannelsApi->GetChannelById($oTenant->IdChannel);
					if ($oChannel)
					{
						$oScreen->Data->SetValue('txtChannelAdd', '('.$oChannel->Login.')');
					}
				}
			}

			$sSubscriptions = '';
			// TODO subscriptions
//			if (CApi::GetConf('capa', false))
//			{
//				$oSubscriptionsApi = CApi::Manager('subscriptions');
//				$oTenantsApi = CApi::Manager('tenants');
//
//				if ($oSubscriptionsApi && $oTenantsApi)
//				{
//					$aSubscriptions = $oSubscriptionsApi->GetSubscriptions($oTenant->IdTenant);
//					if (is_array($aSubscriptions) && 0 < count($aSubscriptions))
//					{
//						$oScreen->Data->SetValue('subscriptionsSupported', true);
//
//						$aLimits = $oTenantsApi->GetSubscriptionUserUsage($oTenant->IdTenant);
//
//						foreach ($aSubscriptions as $oSubscription)
//						{
//							/* @var $oSubscription CSubscription */
//
//							$sSubscriptions .= $oSubscription->Name.' ('.
//								(isset($aLimits[$oSubscription->IdSubscription]) ? $aLimits[$oSubscription->IdSubscription] : 0).
//								' user of '.
//								(0 === $oSubscription->Limit ? 'unlim' : $oSubscription->Limit).' available)'.'<br />';
//						}
//
//						$oScreen->Data->SetValue('txtSubscriptionPlans', $sSubscriptions);
//					}
//				}
//			}

			if (empty($sSubscriptions))
			{
				$oScreen->Data->SetValue('hideClassForSubscription', 'wm_hide');
			}

			$oScreen->Data->SetValue('txtUserLimit', $oTenant->UserCountLimit);
			$oScreen->Data->SetValue('txtQuota', $oTenant->QuotaInMB);

			$iUsed = 0;
			if (0 < $oTenant->QuotaInMB)
			{
				$iUsed = floor(($oTenant->AllocatedSpaceInMB / $oTenant->QuotaInMB) * 100);
				$oScreen->Data->SetValue('txtUsedText', '('.$iUsed.'% '.CApi::I18N('ADMIN_PANEL/TENANTS_RESOURCES_DISK_ALLOC').')');
			}

			if (0 < $oTenant->UserCountLimit)
			{
				$oScreen->Data->SetValue('txtUserLimitDesk', '('.$oTenant->GetUserCount().' '.CApi::I18N('ADMIN_PANEL/TENANTS_USER_USED').')');
			}

			$aDomainsArray = $this->oModule->GetTenantDomains($oTenant->IdTenant);

			$sDomainOptions = '';
			if (is_array($aDomainsArray) && count($aDomainsArray) > 0)
			{
				foreach ($aDomainsArray as $iDomainId => $sDomainName)
				{
					$sDomainOptions .= '<option value="'.$iDomainId.'">'.$sDomainName.'</option>';
				}
			}

			$oScreen->Data->SetValue('selDomains', $sDomainOptions);
		}
	}

	public function ChannelsMainEdit(ap_Table_Screen &$oScreen)
	{
		$oScreen->Data->SetValue('hideClassForNewChannel', '');
		$oScreen->Data->SetValue('hideClassForEditChannel', 'wm_hide');

		/* @var $oChannel CChannel */
		$oChannel =& $this->oAdminPanel->GetMainObject('channel_edit');
		if ($oChannel)
		{
			$oScreen->Data->SetValue('intChannelId', $oChannel->IdChannel);
			$oScreen->Data->SetValue('txtLogin', $oChannel->Login);
			$oScreen->Data->SetValue('txtPassword', $oChannel->Password);
			$oScreen->Data->SetValue('txtDescription', $oChannel->Description);
		}
	}

	public function CommonDav(ap_Standard_Screen &$oScreen)
	{
		/* @var $oApiDavManager CApiDavManager */
		$oApiDavManager = CApi::Manager('dav');
		if ($oApiDavManager)
		{
			$oScreen->Data->SetValue('ch_EnableMobileSync',
				(bool) $oApiDavManager->IsMobileSyncEnabled());

			$oScreen->Data->SetValue('text_DAVUrl', $this->oSettings->GetConf('WebMail/ExternalHostNameOfDAVServer'));
			$oScreen->Data->SetValue('text_IMAPHostName', $this->oSettings->GetConf('WebMail/ExternalHostNameOfLocalImap'));
			$oScreen->Data->SetValue('text_SMTPHostName', $this->oSettings->GetConf('WebMail/ExternalHostNameOfLocalSmtp'));

			if ($this->oAdminPanel->AType)
			{
				$oScreen->Data->SetValue('text_WikiHref',
					'http://www.afterlogic.com/wiki/DAV_server_configuration_(Aurora)');
			}
			else
			{
				$oScreen->Data->SetValue('text_WikiHref',
					'http://www.afterlogic.com/wiki/DAV_server_configuration_(WebMail_Pro)');
			}
		}
	}

}
