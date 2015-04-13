<?php

include_once __DIR__.'/libraries/afterlogic/api.php';
include_once CApi::LibrariesPath().'/ProjectSeven/Notifications.php';

$sContents = file_get_contents('php://input');
$aInputData = array();
if (strlen($sContents) > 0)
{
	parse_str($sContents, $aInputData);
}
else
{
	$aInputData = isset($_REQUEST) && is_array($_REQUEST) ? $_REQUEST : array();
}

//$sMethod = isset($aInputData['method']) ? $aInputData['method'] : '';
$sMethod = strlen($_SERVER['PATH_INFO']) > 0 ? $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['PATH_INFO'] : '';
$sToken = isset($aInputData['token']) ? $aInputData['token'] : '';
$aSecret = CApi::DecodeKeyValues($sToken);
$bMethod = in_array($sMethod, array(
	'GET /token',
	'POST /account',
	'PUT /account/update',
	'DELETE /account',
	'PUT /account/enable',
	'PUT /account/disable',
	'PUT /account/password',
	'GET /account/list',
	'GET /account/exists',
	'GET /account',
	'POST /domain',
	'PUT /domain/update',
	'DELETE /domain',
	'GET /domain/list',
	'GET /domain/exists',
	'GET /domain'
));

$aResult = array(
	'method' => $sMethod
);

if (!CApi::GetConf('labs.rest', true))
{
	$aResult['message'] = 'rest api disabled';
	$aResult['errorCode'] = \ProjectSeven\Notifications::RestApiDisabled;
	$aResult['result'] = false;
}
else if (class_exists('CApi') && CApi::IsValid() && $bMethod)
{
	/* @var $oApiDomainsManager CApiDomainsManager */
	$oApiDomainsManager = CApi::Manager('domains');

	/* @var $oApiTenantsManager CApiTenantsManager */
	$oApiTenantsManager = CApi::Manager('tenants');

	/* @var $oApiUsersManager CApiUsersManager */
	$oApiUsersManager = CApi::Manager('users');

	/* @var $oApiIntegratorManager CApiIntegratorManager */
	$oApiIntegratorManager = CApi::Manager('integrator');

	if ($sMethod === 'GET /token')
	{
		$oSettings = CApi::GetSettings();

		$sLogin = isset($aInputData['login']) ? $aInputData['login'] : '';
		$sPassword = isset($aInputData['password']) ? $aInputData['password'] : '';

		if (0 < strlen($sLogin) && 0 < strlen($sPassword) && $oApiTenantsManager && $oSettings)
		{
			$sSettingsLogin = $oSettings->GetConf('Common/AdminLogin');
			$sSettingsPassword = $oSettings->GetConf('Common/AdminPassword');

			$iTenantId = $oApiTenantsManager->GetTenantIdByLogin($sLogin, $sPassword);

			if (!((($sSettingsLogin === $sLogin) && ($sSettingsPassword === md5($sPassword))) || $iTenantId > 0))
			{
				$aResult['message'] = getErrorMessage('incorrect login or password', $oApiUsersManager);
				$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidCredentials;
			}
			else
			{
				$aResult['result'] = CApi::EncodeKeyValues(array
					(
						'login' => $sLogin,
						'password' => $sPassword,
						'tenantId' => $iTenantId,
						'timestamp' => time()
					));
			}
		}
		else
		{
			$aResult['message'] = 'invalid input parameters';
			$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
		}
	}
	else if (!(isset($aSecret['login']) && isset($aSecret['password'])))
	{
		$aResult['message'] = 'invalid token';
		$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidToken;
	}
	else if (!isset($aSecret['timestamp']) || ((time() - $aSecret['timestamp']) > 3600 /*1h*/))
	//else if (!isset($aSecret['timestamp']))
	{
		$aResult['message'] = 'token expired';
		$aResult['errorCode'] = \ProjectSeven\Notifications::RestTokenExpired;
	}
	else
	{
		$iAuthTenantId = isset($aSecret['tenantId']) ? $aSecret['tenantId'] : 0; //Jedi remember - tenant has less power than superadmin

		switch ($sMethod)
		{
			case 'POST /account':

				$oDefaultDomain = $oApiDomainsManager->GetDefaultDomain();

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';
				$sPassword = isset($aInputData['password']) ? trim($aInputData['password']) : '';
				$sParent = isset($aInputData['parent']) ? trim($aInputData['parent']) : '';

				$sFriendlyName = isset($aInputData['friendlyName']) ? trim($aInputData['friendlyName']) : '';
				$sIncomingMailLogin = isset($aInputData['incomingMailLogin']) ? trim($aInputData['incomingMailLogin']) : $sEmail;
				$sIncomingMailServer = isset($aInputData['incomingMailServer']) ? trim($aInputData['incomingMailServer']) : $oDefaultDomain->IncomingMailServer;
				$iIncomingMailPort = isset($aInputData['incomingMailPort']) ? (int) trim($aInputData['incomingMailPort']) : $oDefaultDomain->IncomingMailPort;
				$iIncomingMailUseSSL = isset($aInputData['incomingMailUseSSL']) ? (int) trim($aInputData['incomingMailUseSSL']) : $oDefaultDomain->IncomingMailUseSSL;
				$sOutgoingMailLogin = isset($aInputData['outgoingMailLogin']) ? trim($aInputData['outgoingMailLogin']) : '';
				$sOutgoingMailServer = isset($aInputData['outgoingMailServer']) ? trim($aInputData['outgoingMailServer']) : $oDefaultDomain->OutgoingMailServer;
				$iOutgoingMailPort = isset($aInputData['outgoingMailPort']) ? (int) trim($aInputData['outgoingMailPort']) : $oDefaultDomain->OutgoingMailPort;
				$iOutgoingMailUseSSL = isset($aInputData['outgoingMailUseSSL']) ? (int) trim($aInputData['outgoingMailUseSSL']) : $oDefaultDomain->OutgoingMailUseSSL;
				$iOutgoingMailAuth = isset($aInputData['outgoingMailAuth']) && $aInputData['outgoingMailAuth'] ? \ESMTPAuthType::AuthCurrentUser : $oDefaultDomain->OutgoingMailAuth;
				$sOutgoingMailPassword = isset($aInputData['outgoingMailPassword']) ? trim($aInputData['outgoingMailPassword']) : $oDefaultDomain->OutgoingMailPassword;

				if (strlen($sEmail) > 0 && strlen($sPassword) > 0 && $oApiUsersManager && $oApiDomainsManager)
				{
					$sDomainName = api_Utils::GetDomainFromEmail($sEmail);
					$oDomain = /* @var $oDomain CDomain */ $oApiDomainsManager->GetDomainByName($sDomainName);

					if (!$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						$oAccount = new CAccount($oDomain ? $oDomain : $oDefaultDomain);

						if (strlen($sParent) > 0)
						{
							$oParentAccount = $oApiUsersManager->GetAccountOnLogin($sParent);
							$oAccount->IdUser = $oParentAccount->IdUser;
						}

						$oAccount->IsDefaultAccount = strlen($sParent) > 0 ? false : true;

						$oAccount->Email = $sEmail;
						$oAccount->IncomingMailPassword = $sPassword;

						$oAccount->FriendlyName = $sFriendlyName;
						$oAccount->IncomingMailLogin = $oDomain ? $sEmail : $sIncomingMailLogin;
						$oAccount->IncomingMailServer = $oDomain ? $oDomain->IncomingMailServer : $sIncomingMailServer;
						$oAccount->IncomingMailPort = $oDomain ? $oDomain->IncomingMailPort : $iIncomingMailPort;
						$oAccount->IncomingMailUseSSL = $oDomain ? $oDomain->IncomingMailUseSSL : $iIncomingMailUseSSL;
						$oAccount->OutgoingMailLogin = $oDomain ? $oDomain->OutgoingMailLogin : $sOutgoingMailLogin;
						$oAccount->OutgoingMailServer = $oDomain ? $oDomain->OutgoingMailServer : $sOutgoingMailServer;
						$oAccount->OutgoingMailPort = $oDomain ? $oDomain->OutgoingMailPort : $iOutgoingMailPort;
						$oAccount->OutgoingMailUseSSL = $oDomain ? $oDomain->OutgoingMailUseSSL : $iOutgoingMailUseSSL;
						$oAccount->OutgoingMailAuth = $oDomain ? $oDomain->OutgoingMailAuth : $iOutgoingMailAuth;
						$oAccount->OutgoingMailPassword = $sOutgoingMailPassword;

						$bNotInDomainRequiredInputParameters = (strlen($sEmail) > 0 && strlen($sPassword) > 0 && strlen($sIncomingMailLogin) > 0 && strlen($sIncomingMailServer) > 0 && $iIncomingMailPort > 0 && strlen($sOutgoingMailServer) > 0 && $iOutgoingMailPort > 0);

						if(!$oDomain && !$bNotInDomainRequiredInputParameters)
						{
							$aResult['result'] = false;
							$aResult['message'] = getErrorMessage('invalid input parameters', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
						}
						else
						{
							$aResult['result'] = $oApiUsersManager->CreateAccount($oAccount, !$oAccount->IsInternal);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot create account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
							}
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot create account', $oApiDomainsManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/update':

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);
					if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
					{

						//TODO fields to update
						if (isset($aInputData['friendlyName'])) {
							$oAccount->FriendlyName = (string)$aInputData['friendlyName'];
						}
						if (isset($aInputData['incomingMailLogin'])) {
							$oAccount->IncomingMailLogin = (string)$aInputData['incomingMailLogin'];
						}
						if (isset($aInputData['incomingMailServer'])) {
							$oAccount->IncomingMailServer = (string)$aInputData['incomingMailServer'];
						}
						if (isset($aInputData['incomingMailPort'])) {
							$oAccount->IncomingMailPort = (int)$aInputData['incomingMailPort'];
						}
						if (isset($aInputData['outgoingMailServer'])) {
							$oAccount->OutgoingMailServer = (string)$aInputData['outgoingMailServer'];
						}
						if (isset($aInputData['outgoingMailPort'])) {
							$oAccount->OutgoingMailPort = (int) $aInputData['outgoingMailPort'];
						}
						if (isset($aInputData['outgoingMailAuth'])) {
							$oAccount->OutgoingMailAuth = (string) $aInputData['outgoingMailAuth'];
						}
						if (isset($aInputData['signature'])) {
							$oAccount->Signature = (string) $aInputData['signature'];
						}
						if (isset($aInputData['signatureType'])) {
							$oAccount->SignatureType = strtolower ((string) $aInputData['signatureType']) === 'plain' ? EAccountSignatureType::Plain : EAccountSignatureType::Html;
						}
						if (isset($aInputData['signatureOptions'])) {
							$oAccount->SignatureOptions = (bool) $aInputData['signatureOptions'] ? 1 : 0;
						}
						if (isset($aInputData['socialEmail'])) {
							$oAccount->SocialEmail = (string) $aInputData['socialEmail'];
						}

						$aResult['result'] = $oApiUsersManager->UpdateAccount($oAccount);
						if (!$aResult['result'])
						{
							$aResult['message'] = getErrorMessage('cannot update account', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot update account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'DELETE /account':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';
				$sParent = isset($aInputData['parent']) ? $aInputData['parent'] : '';
				$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);

				if (strlen($sEmail) > 0 && $oApiUsersManager)
				{
					if (strlen($sParent) > 0)
					{
						$oParentAccount = $oApiUsersManager->GetAccountOnLogin($sParent);
						$iAccountToDeleteId = $oApiUsersManager->GetUserAccountId($oParentAccount->IdUser, $sEmail);
					}
					else
					{
						$iAccountToDeleteId = $oAccount->IdAccount;
					}

					if ($iAccountToDeleteId)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$aResult['result'] = $oApiUsersManager->DeleteAccountById($iAccountToDeleteId);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot delete account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot delete account, cannot find account in your tenant', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/enable':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);
					if ($oAccount)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$aResult['result'] = $oApiUsersManager->EnableAccounts(array($oAccount->IdAccount), false);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot enable account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot enable account', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/disable':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);
					if ($oAccount)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$aResult['result'] = $oApiUsersManager->EnableAccounts(array($oAccount->IdAccount), true);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot disable account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot disable account', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/password':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';
				$sNewPassword = isset($aInputData['password']) ? $aInputData['password'] : '';

				if (0 < strlen($sEmail) && 0 < strlen($sNewPassword) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);

					if ($oAccount)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$oAccount->IncomingMailPassword = $sNewPassword;

							$aResult['result'] = $oApiUsersManager->UpdateAccount($oAccount);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot change account password', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot change account password', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /account/list':

				$iPage = isset($aInputData['page']) ? (int) trim ($aInputData['page']) : 1;
				$iUsersPerPage = isset($aInputData['usersPerPage']) ? (int) trim ($aInputData['usersPerPage']) : 100;
				$sOrderBy = isset($aInputData['orderBy']) ? strtolower (trim ($aInputData['orderBy'])) : 'email';
				$bOrderType = isset($aInputData['orderType']) ? (bool) trim ($aInputData['orderType']) : true;
				$sSearchDesc = isset($aInputData['searchDesc']) ? trim ($aInputData['searchDesc']) : '';
				$sDomain = isset($aInputData['domain']) ? trim ($aInputData['domain']) : '';

				$oDomain = $oApiDomainsManager->GetDomainByName($sDomain);
				$iDomainId = $oDomain ? $oDomain->IdDomain : 0;

				if (!$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
				{
					$aResult['result'] = $oApiUsersManager->GetUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy = 'email', $bOrderType = true, $sSearchDesc = '');
					if (!$aResult['result'])
					{
						$aResult['message'] = getErrorMessage('cannot get account list', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
					}
					else
					{
						$aList = array();

						foreach ($aResult['result'] as $iKey => $mValue){
							$aList[] = array(
								"Id" => $iKey,
								"Email" => $mValue[1],
								"FriendlyName" => $mValue[2]
							);
						}
						$aResult['result'] = $aList;
					}
				}
				else
				{
					$aResult['message'] = getErrorMessage('cannot get account list', $oApiUsersManager);
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
				}

				break;

			case 'GET /account/exists':

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);

					if ($oAccount && !$iAuthTenantId || $oAccount && $oAccount->IdTenant === $iAuthTenantId)
					{
						$aResult['result'] = true;
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /account':

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->GetAccountOnLogin($sEmail);

					if ($oAccount && !$iAuthTenantId || $oAccount && $oAccount->IdTenant === $iAuthTenantId)
					{
						$aResult['result'] = array(
							'friendlyName' => $oAccount->FriendlyName,
							'incomingMailLogin' => $oAccount->IncomingMailLogin,
							'incomingMailServer' => $oAccount->IncomingMailServer,
							'incomingMailPort' => $oAccount->IncomingMailPort,
							'outgoingMailServer' => $oAccount->OutgoingMailServer,
							'outgoingMailPort' => $oAccount->OutgoingMailPort,
							'outgoingMailAuth' => $oAccount->OutgoingMailAuth,
							'signature' => $oAccount->Signature,
							'signatureType' => $oAccount->SignatureType ? 'html' : 'plain',
							'signatureOptions' => $oAccount->SignatureOptions,
							'socialEmail' => $oAccount->SocialEmail
						);
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'POST /domain':

				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';
				$sTenant = isset($aInputData['tenant']) ? trim($aInputData['tenant']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager && $oApiTenantsManager)
				{
					$oDomain = new CDomain();
					$oDomain->Name = trim($sDomain);

					if (strlen($sTenant) > 0)
					{
						$iIdTenant = $oApiTenantsManager->GetTenantIdByLogin($sTenant);

						if ($iIdTenant > 0)
						{
							if (!$iAuthTenantId || $iIdTenant === $iAuthTenantId)
							{
								$oDomain->IdTenant = $iIdTenant;
								$aResult['result'] = $oApiDomainsManager->CreateDomain($oDomain);
								if (!$aResult['result'])
								{
									$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
									$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
								}
							}
							else
							{
								$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
								$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot find tenant', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestTenantFindFailed;
						}
					}
					else
					{
						$aResult['result'] = $oApiDomainsManager->CreateDomain($oDomain);
						if (!$aResult['result'])
						{
							$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /domain/update':

				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->GetDomainByName($sDomain);

					if ($oDomain && !$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						//TODO fields to update
						if (isset($aInputData['allowUsersChangeEmailSettings'])) //Allow users to access accounts settings
						{
							$oDomain->AllowUsersChangeEmailSettings = (bool) $aInputData['allowUsersChangeEmailSettings'];
						}
						if (isset($aInputData['allowWebMail']))
						{
							$oDomain->AllowWebMail = (bool) $aInputData['allowWebMail'];
						}
						if (isset($aInputData['allowContacts']))
						{
							$oDomain->AllowContacts = (bool) $aInputData['allowContacts'];
						}
						if (isset($aInputData['allowCalendar']))
						{
							$oDomain->AllowCalendar = (bool) $aInputData['allowCalendar'];
						}
						if (isset($aInputData['allowFiles']))
						{
							$oDomain->AllowFiles = (bool) $aInputData['allowFiles'];
						}
						if (isset($aInputData['allowHelpdesk']))
						{
							$oDomain->AllowHelpdesk = (bool) $aInputData['allowHelpdesk'];
						}
						if (isset($aInputData['allowNewUsersRegister'])) { //Only already registered users can access WebMail.
							$oDomain->AllowNewUsersRegister = (bool) $aInputData['allowNewUsersRegister'];
						}
						if (isset($aInputData['siteName'])) { //Site name
							$oDomain->SiteName = (string) $aInputData['siteName'];
						}
						if (isset($aInputData['url'])) { //Web domain
							$oDomain->Url = $aInputData['url'];
						}
						if (isset($aInputData['defaultSkin'])) { //Skin
							$sSelSkin = (string) $aInputData['defaultSkin'];
							$aSkins = $oApiIntegratorManager->GetThemeList();
							if (is_array($aSkins) && in_array($sSelSkin, $aSkins))
							{
								$oDomain->DefaultSkin = $sSelSkin;
							}
						}
						if (isset($aInputData['defaultTab'])) { //Default tab
							$sSelTab = (string) $aInputData['defaultTab'];
							$aTabs = $oApiIntegratorManager->GetTabList($oDomain);
							if (is_array($aTabs) && in_array($sSelTab, array_keys($aTabs)))
							{
								$oDomain->DefaultTab = $sSelTab;
							}
						}
						if (isset($aInputData['allowUsersChangeInterfaceSettings'])) { //Allow users to access interface settings
							$oDomain->AllowUsersChangeInterfaceSettings = (bool) $aInputData['allowUsersChangeInterfaceSettings'];
						}
						if (isset($aInputData['defaultLanguage'])) { //Language
							$sSelLanguage = strtolower ((string) $aInputData['defaultLanguage']);
							$aLangs = $oApiIntegratorManager->GetLanguageList();
							if (is_array($aLangs) && in_array($sSelLanguage, $aLangs))
							{
								$oDomain->DefaultLanguage = $sSelLanguage;
							}
						}
						if (isset($aInputData['allowUsersAddNewAccounts'])) { //Allow users to add external mailboxes
							$oDomain->AllowUsersAddNewAccounts = (bool) $aInputData['allowUsersAddNewAccounts'];
						}
						if (isset($aInputData['useThreads'])) { //Use threading
							$oDomain->UseThreads = (bool) $aInputData['useThreads'];
						}

						$aResult['result'] = $oApiDomainsManager->UpdateDomain($oDomain);
						if (!$aResult['result'])
						{
							$aResult['message'] = getErrorMessage('cannot update domain', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = 'cannot find domain';
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestDomainFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'DELETE /domain':

				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->GetDomainByName($sDomain);

					if ($oDomain && !$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						$aResult['result'] = $oApiDomainsManager->DeleteDomainByName($sDomain);
						if (!$aResult['result']) {
							$aResult['message'] = getErrorMessage('cannot delete domain', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = 'cannot find domain';
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestDomainFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /domain/list':

				$iPage = isset($aInputData['page']) ? (int) trim ($aInputData['page']) : 1;
				$iDomainsPerPage = isset($aInputData['domainsPerPage']) ? (int) trim ($aInputData['domainsPerPage']) : 100;
				$sOrderBy = isset($aInputData['orderBy']) ? strtolower (trim ($aInputData['orderBy'])) : 'name';
				$bOrderType = isset($aInputData['orderType']) ? (bool) trim ($aInputData['orderType']) : true;
				$sSearchDesc = isset($aInputData['searchDesc']) ? trim ($aInputData['searchDesc']) : '';
				$sTenant = isset($aInputData['tenant']) ? trim ($aInputData['tenant']) : '';

				$iIdTenant = $oApiTenantsManager->GetTenantIdByLogin($sTenant);

				if (!$iAuthTenantId || $iIdTenant === $iAuthTenantId)
				{
					$aResult['result'] = $oApiDomainsManager->GetDomainsList($iPage, $iDomainsPerPage, $sOrderBy, $bOrderType, $sSearchDesc, $iIdTenant);
					if (!$aResult['result'])
					{
						$aResult['message'] = getErrorMessage('cannot get domain list', $oApiDomainsManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
					}
					else
					{
						$aList = array();

						foreach ($aResult['result'] as $iKey => $mValue) {
							$aList[] = array(
								"Id" => $iKey,
								"IsInternal" => $mValue[0],
								"Name" => $mValue[1]
							);
						}
						$aResult['result'] = $aList;
					}
				}
				else
				{
					$aResult['message'] = getErrorMessage('cannot get domain list', $oApiDomainsManager);
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
				}

				break;

			case 'GET /domain/exists':

				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->GetDomainByName($sDomain);

					if ($oDomain && !$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						//$aResult['result'] = $oApiDomainsManager->DomainExists($sDomain);
						$aResult['result'] = true;
					}
					else
					{
						$aResult['message'] = getErrorMessage('domain does not exist', $oApiDomainsManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestOtherError;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /domain':
				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->GetDomainByName($sDomain);

					if ($oDomain && !$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						$aResult['result'] = array(
							'allowUsersChangeEmailSettings' => $oDomain->AllowUsersChangeEmailSettings, //Allow users to access accounts settings
							'allowWebMail' => $oDomain->AllowWebMail,
							'allowContacts' => $oDomain->AllowContacts,
							'allowCalendar' => $oDomain->AllowCalendar,
							'allowFiles' => $oDomain->AllowFiles,
							'allowHelpdesk' => $oDomain->AllowHelpdesk,
							'allowNewUsersRegister' => $oDomain->AllowNewUsersRegister, //Only already registered users can access WebMail.
							'siteName' => $oDomain->SiteName, //Site name
							'url' => $oDomain->Url, //Web domain
							'defaultSkin' => $oDomain->DefaultSkin, //Skin
							'defaultTab' => $oDomain->DefaultTab, //Default tab
							'allowUsersChangeInterfaceSettings' => $oDomain->AllowUsersChangeInterfaceSettings, //Allow users to access interface settings
							'defaultLanguage' => $oDomain->DefaultLanguage, //Language
							'allowUsersAddNewAccounts' => $oDomain->AllowUsersAddNewAccounts, //Allow users to add external mailboxes
							'useThreads' => $oDomain->UseThreads //Use threading
						);
					}
					else
					{
						$aResult['message'] = getErrorMessage('domain does not exist', $oApiDomainsManager);
						$aResult['errorCode'] = \ProjectSeven\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectSeven\Notifications::RestInvalidParameters;
				}

				break;

				break;
		}
	}
}

if (isset($aResult['result']) && $aResult['result'])
{
	$aResult['message'] = 'ok';
}
if (!isset($aResult['message']))
{
	$aResult['message'] = $bMethod ? 'error' : 'unknown method';
	$aResult['errorCode'] = $bMethod ? \ProjectSeven\Notifications::RestOtherError : \ProjectSeven\Notifications::RestUnknownMethod;
	$aResult['result'] = false;
}

function getErrorMessage ($sMessage, $oManager)
{
	$sResultMessage = $oManager ? $oManager->GetLastErrorMessage() : null;
	return empty($sResultMessage) ? $sMessage : $sResultMessage;
}

//@header('Content-Type: text/html; charset=utf-8');
//echo '<script>console.log('.json_encode($aResult, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0).');</script>';

@header('Content-Type: application/json; charset=utf-8');
echo json_encode($aResult, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0);