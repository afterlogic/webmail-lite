<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

class CWebMailPopulateData extends ap_CoreModuleHelper
{
	public function ServicesLogging(ap_Standard_Screen &$oScreen)
	{
		$this->oSettings->GetConf('Common/LoggingLevel');
		$this->oSettings->GetConf('Common/EnableEventLogging');

		$oScreen->Data->SetValue('ch_EnableDebugLogging', $this->oSettings->GetConf('Common/EnableLogging'));

		$iLogLevel = $this->oSettings->GetConf('Common/LoggingLevel');
		$oScreen->Data->SetValue('selVerbosityFull', ELogLevel::Full === $iLogLevel);
		$oScreen->Data->SetValue('selVerbosityWarning', ELogLevel::Warning === $iLogLevel);
		$oScreen->Data->SetValue('selVerbosityError', ELogLevel::Error === $iLogLevel);
		$oScreen->Data->SetValue('selVerbositySpec', ELogLevel::Spec === $iLogLevel);

		$oScreen->Data->SetValue('ch_EnableUserActivityLogging', $this->oSettings->GetConf('Common/EnableEventLogging'));

		$aSize = $this->oModule->GetLogsSize();
		$oScreen->Data->SetValue('DownloadLogSize', '('.api_Utils::GetFriendlySize($aSize[0]).')');
		$oScreen->Data->SetValue('DownloadUserActivityLogSize', '('.api_Utils::GetFriendlySize($aSize[1]).')');

		$oScreen->Data->SetValue('MaxViewSize', CApi::GetConf('log.max-view-size', 100).'KB');
	}


	public function DomainsMainEdit(ap_Table_Screen &$oScreen)
	{
		$iAutocheckMail = 0;
		$sDomainSkin = $sDomainLang = $sDomainZone = '';
		$iMessagesPerPage = $iContactsPerPage = 20;
		$iContactsGABVisibility = EContactsGABVisibility::Off;

		/* @var $oDomain CDomain */
		$oDomain = $this->oAdminPanel->GetMainObject('domain_edit');
		if ($oDomain)
		{
			$bHideProtocol = true;
			if ($oDomain->IsDefaultDomain ||
				(CSession::Has(AP_SESS_DOMAIN_NEXT_EDIT_ID) &&
					$oDomain->IdDomain === CSession::Get(AP_SESS_DOMAIN_NEXT_EDIT_ID, null)))
			{
				$oScreen->Data->SetValue('classHideIncomingMailProtocol', '');
			}

			if ($bHideProtocol)
			{
				$oScreen->Data->SetValue('classHideIncomingMailProtocol', 'wm_hide');

//				$oScreen->Data->SetValue('textIncomingMailProtocol',
//					$oDomain->IncomingMailProtocol === EMailProtocol::IMAP4 ? '(IMAP4)' : '(POP3)');
				
				$oScreen->Data->SetValue('textIncomingMailProtocol', '');
			}

			$oScreen->Data->SetValue('txtFilteHrefAdd', '&filter='.$oDomain->IdDomain);

			$oScreen->Data->SetValue('radioAuthTypeAuthCurrentUser', true);
			$oScreen->Data->SetValue('classHideSsl', $this->oModule->HasSslSupport() ? '' : 'wm_hide');

			$oScreen->Data->SetValue('txtWebDomain', $oDomain->Url);

			$oScreen->Data->SetValue('chAllowUsersAccessInterfaveSettings',
				$oDomain->AllowUsersChangeInterfaceSettings);
			$oScreen->Data->SetValue('chAllowUsersAccessAccountsSettings',
				$oDomain->AllowUsersChangeEmailSettings);

			$oScreen->Data->SetValue('chEnableWebmail', $oDomain->AllowWebMail);

			$oScreen->Data->SetValue('chEnableAddressBook', $oDomain->AllowContacts);

			$iMessagesPerPage = $oDomain->MailsPerPage;
			$iContactsPerPage = $oDomain->ContactsPerPage;
			$iAutocheckMail = $oDomain->AutoCheckMailInterval;


			$iIncomingMailProtocol = $oDomain->IncomingMailProtocol;
			$oScreen->Data->SetValue('optIncomingProtocolIMAP', EMailProtocol::IMAP4 === $iIncomingMailProtocol);
			$oScreen->Data->SetValue('optIncomingProtocolPOP3',	EMailProtocol::POP3 === $iIncomingMailProtocol);

			$oScreen->Data->SetValue('txtIncomingMailHost', $oDomain->IncomingMailServer);
			$oScreen->Data->SetValue('txtIncomingMailPort', $oDomain->IncomingMailPort);
			$oScreen->Data->SetValue('chIncomingUseSSL', $oDomain->IncomingMailUseSSL);

			$oScreen->Data->SetValue('txtOutgoingMailHost', $oDomain->OutgoingMailServer);
			$oScreen->Data->SetValue('txtOutgoingMailPort', $oDomain->OutgoingMailPort);
			$oScreen->Data->SetValue('chOutgoingUseSSL', $oDomain->OutgoingMailUseSSL);

//			$oScreen->Data->SetValue('txtExternalHostNameOfDAVServer', $oDomain->ExternalHostNameOfDAVServer);
//			$oScreen->Data->SetValue('txtExternalHostNameOfLocalImap', $oDomain->ExternalHostNameOfLocalImap);
//			$oScreen->Data->SetValue('txtExternalHostNameOfLocalSmtp', $oDomain->ExternalHostNameOfLocalSmtp);

			$sOutPassword = $oDomain->OutgoingMailPassword;
			$oScreen->Data->SetValue('txtOutgoingMailLogin', $oDomain->OutgoingMailLogin);
			$oScreen->Data->SetValue('txtOutgoingMailPassword', empty($sOutPassword) ? '' : AP_DUMMYPASSWORD);

			$iAuthType = $oDomain->OutgoingMailAuth;
			$oScreen->Data->SetValue('radioAuthTypeNoAuth', $iAuthType === ESMTPAuthType::NoAuth);
			$oScreen->Data->SetValue('radioAuthTypeAuthSpecified', $iAuthType === ESMTPAuthType::AuthSpecified);
			$oScreen->Data->SetValue('radioAuthTypeAuthCurrentUser', $iAuthType === ESMTPAuthType::AuthCurrentUser);

			$oScreen->Data->SetValue('chAllowNewUsersRegister', !$oDomain->AllowNewUsersRegister);
			$oScreen->Data->SetValue('IsDefaultDomain', $oDomain->IsDefaultDomain);
			$oScreen->Data->SetValue('domainIsInternal', $oDomain->IsInternal);

			$iLayout = $oDomain->Layout;
			$oScreen->Data->SetValue('radioLayoutSide', $iLayout === ELayout::Side);
			$oScreen->Data->SetValue('radioLayoutBottom', $iLayout === ELayout::Bottom);
		}

		$sMessagesPerPageOptions = '';
		$aMessagesPerPageList = array(5, 10, 20, 25, 50, 75, 100);
		foreach ($aMessagesPerPageList as $iMessageCount)
		{
			$sSelected = ($iMessageCount === $iMessagesPerPage) ? ' selected="selected"' : '';
			$sMessagesPerPageOptions .= '<option value="'.$iMessageCount
				.'"'.$sSelected.'>'.$iMessageCount.'</option>';
		}
		$oScreen->Data->SetValue('selMessagesPerPageOptions', $sMessagesPerPageOptions);

		$sAutocheckMailOptions = '';
		$aAutocheckMailList = array(0, 1, 3, 5, 10, 15, 20, 30);
		foreach ($aAutocheckMailList as $iAutocheckMailValue)
		{
			$sSelected = ($iAutocheckMail === $iAutocheckMailValue) ? ' selected="selected"' : '';
			$sAutocheckMailView = CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_AUTO_OFF');
			if (0 < $iAutocheckMailValue)
			{
				$sAutocheckMailView = (1 === $iAutocheckMailValue)
					? $iAutocheckMailValue.' '.CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_AUTO_MIN') : $iAutocheckMailValue.' '.CApi::I18N('ADMIN_PANEL/DOMAINS_WEBMAIL_AUTO_MINS');
			}
			$sAutocheckMailOptions .= '<option value="'.$iAutocheckMailValue
				.'"'.$sSelected.'>'.$sAutocheckMailView.'</option>';
		}
		$oScreen->Data->SetValue('selAutocheckMailOptions', $sAutocheckMailOptions);

		$sContactsPerPageOptions = '';
		$aContactsPerPageList = array(5, 10, 20, 25, 50, 75, 100);
		foreach ($aContactsPerPageList as $iContactsCount)
		{
			$sSelected = ($iContactsPerPage === $iContactsCount) ? ' selected="selected"' : '';
			$sContactsPerPageOptions .= '<option value="'.$iContactsCount
				.'"'.$sSelected.'>'.$iContactsCount.'</option>';
		}
		$oScreen->Data->SetValue('selContactsPerPageOptions', $sContactsPerPageOptions);

//		$sSkinsOptions = '';
//		$aSkins = $this->oModule->GetSkinList();
//		if (is_array($aSkins))
//		{
//			foreach ($aSkins as $sSkin)
//			{
//				$sSelected = ($sSkin === $sDomainSkin) ? ' selected="selected"' : '';
//				$sSkinsOptions .= '<option value="'.ap_Utils::AttributeQuote($sSkin)
//					.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($sSkin).'</option>';
//			}
//			$oScreen->Data->SetValue('selSkinsOptions', $sSkinsOptions);
//		}
//
//		$sLanguageOptions = '';
//		$aLangs = $this->oModule->GetLangsList();
//		if (is_array($aLangs))
//		{
//			foreach ($aLangs as $sLang)
//			{
//				$sSelected = ($sLang === $sDomainLang) ? ' selected="selected"' : '';
//				$sLanguageOptions .= '<option value="'.ap_Utils::AttributeQuote($sLang)
//					.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($sLang).'</option>';
//			}
//			$oScreen->Data->SetValue('selLanguageOptions', $sLanguageOptions);
//		}
//
//		$sTimeZoneOptions = '';
//		$aTimeZones = $this->oModule->GetTimeZoneList();
//		if (is_array($aTimeZones))
//		{
//			foreach ($aTimeZones as $iIndex => $aTimeZone)
//			{
//				$sSelected = ((int) $sDomainZone === (int) $iIndex) ? ' selected="selected"' : '';
//				$sTimeZoneOptions .= '<option value="'.ap_Utils::AttributeQuote($iIndex)
//					.'"'.$sSelected.'>'.ap_Utils::EncodeSpecialXmlChars($aTimeZone).'</option>';
//			}
//			$oScreen->Data->SetValue('selTimeZone', $sTimeZoneOptions);
//		}
	}
}
