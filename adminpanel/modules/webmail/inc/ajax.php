<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CWebMailAjaxAction extends ap_CoreModuleHelper
{

	public function DomainsNew()
	{
		/* @var $oDomain CDomain */
		$oDomain =& $this->oAdminPanel->GetMainObject('domain_new');
		if ($oDomain)
		{
			$this->initNewDomainByPost($oDomain);
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

	protected function initUpdateDomainByPost(CDomain &$oDomain)
	{
		$oDomain->OverrideSettings = CPost::GetCheckBox('chOverrideSettings');

		if (CPost::Has('txtIncomingMailHost') && CPost::Has('txtOutgoingMailHost')
			&& CPost::Has('txtIncomingMailPort') && CPost::Has('txtOutgoingMailPort'))
		{
			$oDomain->IncomingMailServer = CPost::Get('txtIncomingMailHost');
			$oDomain->IncomingMailPort = CPost::Get('txtIncomingMailPort');
			$oDomain->IncomingMailUseSSL = CPost::GetCheckBox('chIncomingUseSSL');

			$oDomain->OutgoingMailServer = CPost::Get('txtOutgoingMailHost');
			$oDomain->OutgoingMailPort = CPost::Get('txtOutgoingMailPort');
			$oDomain->OutgoingMailUseSSL = CPost::GetCheckBox('chOutgoingUseSSL');
		}

		if (CPost::Has('radioAuthType'))
		{
			$oDomain->OutgoingMailAuth =
				EnumConvert::FromPost(CPost::Get('radioAuthType'), 'ESMTPAuthType');
		}

		if (CPost::Has('txtOutgoingMailLogin') && CPost::Has('txtOutgoingMailPassword'))
		{
			$oDomain->OutgoingMailLogin = CPost::Get('txtOutgoingMailLogin');
			if ((string) AP_DUMMYPASSWORD !== (string) CPost::Get('txtOutgoingMailPassword'))
			{
				$oDomain->OutgoingMailPassword = CPost::Get('txtOutgoingMailPassword', '');
			}
		}

		if (CPost::Has('selIncomingMailProtocol'))
		{
			$oDomain->IncomingMailProtocol = EnumConvert::FromPost(
				CPost::Get('selIncomingMailProtocol'), 'EMailProtocol');
		}

//		if ($oDomain->OverrideSettings || $oDomain->IsDefaultDomain)
//		{
//			$oDomain->ExternalHostNameOfDAVServer = CPost::Get('txtExternalHostNameOfDAVServer', $oDomain->ExternalHostNameOfDAVServer);
//			$oDomain->ExternalHostNameOfLocalImap = CPost::Get('txtExternalHostNameOfLocalImap', $oDomain->ExternalHostNameOfLocalImap);
//			$oDomain->ExternalHostNameOfLocalSmtp = CPost::Get('txtExternalHostNameOfLocalSmtp', $oDomain->ExternalHostNameOfLocalSmtp);
//		}

		if ($oDomain->OverrideSettings)
		{
			// General
			$oDomain->Url = (string) CPost::Get('txtWebDomain', $oDomain->Url);
			$oDomain->AllowUsersChangeEmailSettings = CPost::GetCheckBox('chAllowUsersAccessAccountsSettings');
			$oDomain->AllowNewUsersRegister = !CPost::GetCheckBox('chAllowNewUsersRegister');

			// Webmail
			$oDomain->AllowWebMail = CPost::GetCheckBox('chEnableWebmail');

			$oDomain->MailsPerPage = CPost::Get('selMessagesPerPage', $oDomain->MailsPerPage);
			$oDomain->AutoCheckMailInterval = CPost::Get('selAutocheckMail', $oDomain->AutoCheckMailInterval);

			if (CPost::Has('radioLayout'))
			{
				$oDomain->Layout = EnumConvert::FromPost(CPost::Get('radioLayout'), 'ELayout');
			}

			// Address Book
			$oDomain->AllowContacts = CPost::GetCheckBox('chEnableAddressBook');

			$oDomain->ContactsPerPage = CPost::Get('selContactsPerPage', $oDomain->ContactsPerPage);

		}
	}

	protected function initNewDomainByPost(CDomain &$oDomain)
	{
		/* @var $oApiDomainsManager CApiDomainsManager */
		$oApiDomainsManager = CApi::Manager('domains');

		/* @var $oDefaultDomain CDomain */
		$oDefaultDomain = $oApiDomainsManager->GetDefaultDomain();

		$oDomain->IncomingMailProtocol = $oDefaultDomain->IncomingMailProtocol;
		$oDomain->IncomingMailServer = $oDefaultDomain->IncomingMailServer;
		$oDomain->IncomingMailPort = $oDefaultDomain->IncomingMailPort;
		$oDomain->OutgoingMailServer = $oDefaultDomain->OutgoingMailServer;
		$oDomain->OutgoingMailPort = $oDefaultDomain->OutgoingMailPort;

		$oDomain->ExternalHostNameOfDAVServer = $oDefaultDomain->ExternalHostNameOfDAVServer;
		$oDomain->ExternalHostNameOfLocalImap = $oDefaultDomain->ExternalHostNameOfLocalImap;
		$oDomain->ExternalHostNameOfLocalSmtp = $oDefaultDomain->ExternalHostNameOfLocalSmtp;
	}
}
