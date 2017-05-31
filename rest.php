<?php

include_once __DIR__.'/libraries/afterlogic/api.php';
include_once CApi::LibrariesPath().'/ProjectCore/Notifications.php';

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
	'POST /channel',
	'DELETE /channel',
	'POST /tenant',
	'PATCH /tenant',
	'DELETE /tenant',
	'GET /tenant/exists',
	'GET /tenant/list',
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
	$aResult['errorCode'] = \ProjectCore\Notifications::RestApiDisabled;
	$aResult['result'] = false;
}
else if (class_exists('CApi') && CApi::IsValid() && $bMethod)
{
	/* @var $oApiDomainsManager CApiDomainsManager */
	$oApiDomainsManager = CApi::Manager('domains');

	/* @var $oApiTenantsManager CApiTenantsManager */
	$oApiTenantsManager = CApi::Manager('tenants');

	/* @var $oApiChannelsManager CApiChannelsManager */
	$oApiChannelsManager = CApi::Manager('channels');

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

			$iTenantId = $oApiTenantsManager->getTenantIdByLogin($sLogin, $sPassword);

			if (!((($sSettingsLogin === $sLogin) && ($sSettingsPassword === crypt($sPassword, CApi::$sSalt))) || $iTenantId > 0))
			{
				$aResult['message'] = getErrorMessage('incorrect login or password', $oApiUsersManager);
				$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidCredentials;
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
			$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
		}
	}
	else if (!(isset($aSecret['login']) && isset($aSecret['password'])))
	{
		$aResult['message'] = 'invalid token';
		$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidToken;
	}
	else if (!isset($aSecret['timestamp']) || ((time() - $aSecret['timestamp']) > 3600 /*1h*/))
	//else if (!isset($aSecret['timestamp']))
	{
		$aResult['message'] = 'token expired';
		$aResult['errorCode'] = \ProjectCore\Notifications::RestTokenExpired;
	}
	else
	{
		$iAuthTenantId = isset($aSecret['tenantId']) ? $aSecret['tenantId'] : 0; //Jedi remember - tenant has less power than superadmin

		switch ($sMethod)
		{
			case 'POST /tenant':
				$sLogin = isset($aInputData['tenantLogin']) ? trim($aInputData['tenantLogin']) : null;

				$sPassword = isset($aInputData['tenantPassword']) ? trim($aInputData['tenantPassword']) : null;

				$sDescription = isset($aInputData['tenantDescription']) ? trim($aInputData['tenantDescription']) : null;

				$sAdminEmail = isset($aInputData['tenantAdminEmail']) ? trim($aInputData['tenantAdminEmail']) : null;

				$sChannel = isset($aInputData['tenantChannel']) ? trim($aInputData['tenantChannel']) : null;

				// ToDo : Get a give a default && max limit for user count
				$iUserCountLimit = isset($aInputData['tenantUserCountLimit']) ? trim($aInputData['tenantUserCountLimit']) : 1;
				// ToDo : Give a default && max limit for quota
				$iQuotaInMB = isset($aInputData['tenantQuotaInMB']) ? trim($aInputData['tenantQuotaInMB']) : 1;

				if (!isset($sLogin))
				{
					$aResult['message'] = 'Tenant : Required parameters cannot be empty';
					$aResult['errorCode'] = 400;
					break;
				}

				if (!isset($oApiTenantsManager))
				{
					$aResult['message'] = 'Tenant : Required manager not found';
					$aResult['errorCode'] = 500;
					break;
				}

				if (!class_exists('CTenant'))
				{
					$aResult['message'] = 'Tenant : Required classes not found';
					$aResult['errorCode'] = 500;
					break;
				}

				// try get tenant by taken tenant login & password
				$iTenantId = $oApiTenantsManager->getTenantIdByLogin($sLogin);
				if ($iTenantId > 0)
				{
					$aResult['message'] = 'Tenant : Already exist';
					$aResult['errorCode'] = 400;
					break;
				}

				/* @var $oTenant CTenant */
				$oTenant = new CTenant();
				$oTenant->Login = $sLogin;

				// Nullable properties
				$oTenant->setPassword($sPassword);
				$oTenant->Description = $sDescription;
				$oTenant->Email = $sAdminEmail;

				// Non-nullable properties
				$oTenant->UserCountLimit = $oTenant->UserCountLimit = $iUserCountLimit;
				$oTenant->QuotaInMB = $iQuotaInMB;

				if (!isset($oApiChannelsManager))
				{
					$aResult['message'] = 'Channel : Required manager not found';
					$aResult['errorCode'] = 500;
					break;
				}

				// Is tenant related with a channel
				if (isset($sChannel))
				{
					// if Channel input isn't integer try get by Login
					if (!is_numeric($sChannel))
					{
						$iChannelId = $oApiChannelsManager->getChannelIdByLogin($sChannel);
						if ($iChannelId == 0)
						{
							$aResult['message'] = 'Channel : Not found';
							$aResult['errorCode'] = 404;
							break;
						}
						else
						{
							// Set founded channel id to tenant instance
							$oTenant->IdChannel = $iChannelId;
						}
						// If Channel input is a integer try to get it
					}
					else if (is_numeric($sChannel))
					{
						$iChannelId = intval($sChannel);

						// ToDo : Investigate return object
						if (!isset($iChannelId) || $iChannelId<=0)
						{
							$aResult['message'] = 'Channel : Not valid channel id';
							$aResult['errorCode'] = 404;
							break;
						}

						$oChannel = $oApiChannelsManager->getChannelById($iChannelId);

						if ($oChannel->IdChannel == 0)
						{
							$aResult['message'] = 'Channel : Not found';
							$aResult['errorCode'] = 404;
							break;
						}
						else
						{
							// Valid channel id set it to tenant
							$oTenant->IdChannel = $iChannelId;
						}
					}
				}

				// Try create a tenant
				$bIsTenantCreated = $oApiTenantsManager->createTenant($oTenant);

				if (!$bIsTenantCreated)
				{
					$aResult['message'] = 'Tenant Manager : Not created';
					$aResult['errorCode'] = 500;
					break;
				}

				// Try get created tenant id
				$iTenantId = $oApiTenantsManager->getTenantIdByLogin($sLogin);
				if ($iTenantId == 0)
				{
					$aResult['message'] = 'Tenant Manager : created but not found';
					$aResult['errorCode'] = 500;
					break;
				}

				// Tenant created and ready
				$aResult['result'] = array(
					'channelId' => isset($iChannelId) ? $iChannelId : -1,
					'tenantId'  => $iTenantId
				);
				break;

			case 'PATCH /tenant':
				$sLogin = isset($aInputData['tenantLogin']) ? trim($aInputData['tenantLogin']) : null;

				if (!isset($aInputData['tenantUserCountLimit']) && !isset($aInputData['tenantQuotaInMB']) && !isset($aInputData['IsDisabled']) && !isset($aInputData['tenantPassword']))
				{
					$aResult['message'] = getErrorMessage('Tenant : Nothing to update', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					break;
				}

				// is required properties empty
				if (!isset($sLogin))
				{
					$aResult['message'] = getErrorMessage('Tenant : Required parameters cannot be empty', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					break;
				}

				if (!isset($oApiTenantsManager))
				{
					$aResult['message'] = getErrorMessage('Tenant : Required manager cant loaded', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					break;
				}

				if (!class_exists('CTenant'))
				{
					$aResult['message'] = getErrorMessage('Tenant : Required classes not found', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					break;
				}

				// Try get tenant by taken tenant login
				$iTenantId = $oApiTenantsManager->getTenantIdByLogin($sLogin);
				if ($iTenantId == 0)
				{
					$aResult['message'] = getErrorMessage('Tenant : Required classes not found', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestTenantFindFailed;
					break;
				}

				/* @var $oTenant CTenant */
				$oTenant = $oApiTenantsManager->getTenantById($iTenantId);

				if (isset($aInputData['tenantUserCountLimit']))
				{
					$oTenant->UserCountLimit = (int) trim($aInputData['tenantUserCountLimit']);
				}
				if (isset($aInputData['tenantQuotaInMB']))
				{
					$oTenant->QuotaInMB = (int) trim($aInputData['tenantQuotaInMB']);
				}
				if (isset($aInputData['IsDisabled']))
				{
					$oTenant->IsDisabled = filter_var($aInputData['IsDisabled'], FILTER_VALIDATE_BOOLEAN);
				}
				if (isset($aInputData['tenantPassword']))
				{
					$oTenant->setPassword(trim($aInputData['tenantPassword']));
				}

				// Try to update a tenant
				$isTenantUpdated = $oApiTenantsManager->updateTenant($oTenant);

				if (!$isTenantUpdated)
				{
					$aResult['message'] = getErrorMessage('Tenant Manager : Not updated', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestTenantFindFailed;
					break;
				}

				// Tenant updated
				$aResult['result'] = array(
					'tenantId' => $iTenantId
				);

				break;

			case 'DELETE /tenant':
				$sLogin = isset($aInputData['tenantLogin']) ? trim($aInputData['tenantLogin']) : null;

				if (!isset($sLogin))
				{

					$aResult['message'] = 'Tenant : Required parameters cannot be empty';
					$aResult['errorCode'] = 400;
					break;
				}

				// Try get tenant id by login
				$iTenantId = $oApiTenantsManager->getTenantIdByLogin($sLogin);
				if ($iTenantId == 0)
				{
					$aResult['message'] = 'Tenant : not found';
					$aResult['errorCode'] = 404;
					break;
				}

				$oTenant = new CTenant();
				$oTenant->IdTenant = $iTenantId;
				$bIsTenantDeleted = $oApiTenantsManager->deleteTenant($oTenant);

				if (!$bIsTenantDeleted)
				{
					$aResult['message'] = 'Tenant : not deleted';
					$aResult['errorCode'] = 500;
					break;
				}

				// it's success
				$aResult['result'] = array(
					'tenantId' => $iTenantId
				);

				break;

			case 'GET /tenant/exists':

				$sLogin = isset($aInputData['tenantLogin']) ? trim($aInputData['tenantLogin']) : null;

				// is required properties empty
				if (!isset($sLogin))
				{
					$aResult['message'] = getErrorMessage('invalid input parameters', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
					break;
				}

				if (!isset($oApiTenantsManager))
				{
					$aResult['message'] = getErrorMessage('Tenant : Required manager cant loaded', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					break;
				}

				if (!class_exists('CTenant'))
				{
					$aResult['message'] = getErrorMessage('Tenant : Required classes not found', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					break;
				}

				// Try get tenant by taken tenant login
				$iTenantId = $oApiTenantsManager->getTenantIdByLogin($sLogin);
				if ($iTenantId > 0)
				{
					$aResult['result'] = true;
				}
				else
				{
					$aResult['message'] = getErrorMessage('cannot find tenant', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestTenantFindFailed;
					break;
				}


				break;

			case 'GET /tenant/list':

				$iPage = isset($aInputData['page']) ? (int) trim ($aInputData['page']) : 1;
				$iTenantsPerPage = isset($aInputData['tenantsPerPage']) ? (int) trim ($aInputData['tenantsPerPage']) : 100;
				$sOrderBy = isset($aInputData['orderBy']) ? strtolower (trim ($aInputData['orderBy'])) : 'Login';
				$bOrderType = (isset($aInputData['orderType']) && trim($aInputData['orderType']) === 'false') ? false : true;
				$sSearchDesc = isset($aInputData['searchDesc']) ? trim ($aInputData['searchDesc']) : '';

				if (!class_exists('CTenant'))
				{
					$aResult['message'] = getErrorMessage('Tenant : Required classes not found', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					break;
				}

				
				$aResult['result'] = $oApiTenantsManager->getTenantList($iPage, $iTenantsPerPage, $sOrderBy, $bOrderType, $sSearchDesc);
				if (!$aResult['result'])
				{
					$aResult['message'] = getErrorMessage('cannot get tenant list', $oApiTenantsManager);
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
				}
				else
				{
					$aList = array();

					foreach ($aResult['result'] as $iKey => $mValue){
						$aList[] = array(
							"Id" => $iKey,
							"Login" => $mValue[0],
							"Description" => $mValue[1]
						);
					}
					$aResult['result'] = $aList;
				}

				break;

			case 'POST /channel':
				$sChannelLogin = isset($aInputData['channelLogin']) ? trim($aInputData['channelLogin']) : null;

				// Login inputs are required,check both of them if one of them null return error
				if (!isset($sChannelLogin))
				{
					$aResult['message'] = 'Channel : Invalid input parameters';
					$aResult['errorCode'] = 500;
					break;
				}

				// is required managers are ready
				if (!isset($oApiChannelsManager))
				{
					$aResult['message'] = 'Channel : Required manager not loaded';
					$aResult['errorCode'] = 500;
					break;
				}

				if (!class_exists('CChannel'))
				{
					$aResult['message'] = 'Channel : Required classes not found';
					$aResult['errorCode'] = 500;
					break;
				}

				// Channel object
				$oChannel = new CChannel();

				$oChannel->Login = $sChannelLogin;

				// try get channel by taken id, if it's already exist return a error
				$iChannelId = $oApiChannelsManager->getChannelIdByLogin($oChannel->Login);

				if ($iChannelId > 0)
				{
					$aResult['message'] = 'Channel Manager : Already exist';
					$aResult['errorCode'] = 400;
					break;
				}

				// channel not exist lets validate is new channel login name is valid
				$isChannelValid = $oChannel->validate();

				if (!$isChannelValid)
				{
					$aResult['message'] = 'Channel : non-valid channel login value';
					$aResult['errorCode'] = 400;
					break;
				}

				// channel name is valid lets break some eggs and make a channel
				$bIsChannelCreated = $oApiChannelsManager->createChannel($oChannel);

				if (!$bIsChannelCreated)
				{
					$aResult['message'] = 'Channel Manager : not created';
					$aResult['errorCode'] = 500;
					break;
				}

				// Get created channel's id
				$iChannelId = $oApiChannelsManager->getChannelIdByLogin($oChannel->Login);

				if ($iChannelId == 0)
				{
					$aResult['message'] = 'channel created but not found';
					$aResult['errorCode'] = 500;
					break;

				}

				// Channel is created and ready
				$aResult['result'] = array(
					'channelId' => $iChannelId
				);

				break;

			case 'DELETE /channel':
				$sChannelLogin = isset($aInputData['channelLogin']) ? trim($aInputData['channelLogin']) : null;

				if (!isset($sChannelLogin))
				{
					$aResult['message'] = 'Channel : Required parameters cannot be empty';
					$aResult['errorCode'] = 400;
					break;
				}

				// is required managers are ready
				if (!isset($oApiChannelsManager))
				{
					$aResult['message'] = 'Channel : Required manager not loaded';
					$aResult['errorCode'] = 500;
					break;
				}

				if (!class_exists('CChannel'))
				{
					$aResult['message'] = 'Channel : required classes not found';
					$aResult['errorCode'] = 500;
					break;
				}

				$oChannel = new CChannel();
				$oChannel->Login = $sChannelLogin;
				$iChannelId = $oApiChannelsManager->getChannelIdByLogin($sChannelLogin);

				if ($iChannelId == 0)
				{
					$aResult['message'] = 'Channel not found';
					$aResult['errorCode'] = 404;
					break;
				}

				$oChannel->IdChannel = $iChannelId;
				$bIsDeleted = $oApiChannelsManager->deleteChannel($oChannel);

				if (!$bIsDeleted)
				{
					$aResult['message'] = 'Channel Manager : Channel not deleted';
					$aResult['errorCode'] = 500;
					break;
				}

				$aResult['result'] = array(
					'channelId' => $iChannelId
				);
				break;

			case 'POST /domain':
				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';
				$sTenant = isset($aInputData['tenant']) ? trim($aInputData['tenant']) : '';

				# HEO CHANGES
				$sIncomingMailServer = isset($aInputData['incomingMailServer']) ? trim($aInputData['incomingMailServer']) : null;

				$sOutgoingMailServer = isset($aInputData['outgoingMailServer']) ? trim($aInputData['outgoingMailServer']) : null;

				$sAllowNewUsersRegister = isset($aInputData['allowNewUsersRegister']) ? trim($aInputData['allowNewUsersRegister']) : null;

				$sSiteName = isset($aInputData['siteName']) ? trim($aInputData['siteName']) : null;
				#HEO CHANGES END

				if (0 < strlen($sDomain) && $oApiDomainsManager && $oApiTenantsManager)
				{
					$oDomain = new CDomain();
					$oDomain->Name = trim($sDomain);

					# HEO CHANGES
					if( isset($sIncomingMailServer) )
					{
						$oDomain->IncomingMailServer = $sIncomingMailServer;
					}
					if( isset($sOutgoingMailServer) )
					{
						$oDomain->OutgoingMailServer = $sOutgoingMailServer;
					}
					if ( isset($sAllowNewUsersRegister) )
					{
						$bAllowNewUsersRegister = filter_var($sAllowNewUsersRegister, FILTER_VALIDATE_BOOLEAN);
						$oDomain->AllowNewUsersRegister = $bAllowNewUsersRegister;
					}

					$oDomain->SiteName = $sSiteName;
					# HEO CHANGES END

					if (strlen($sTenant) > 0)
					{
						$iIdTenant = $oApiTenantsManager->getTenantIdByLogin($sTenant);

						if ($iIdTenant > 0)
						{
							if (!$iAuthTenantId || $iIdTenant === $iAuthTenantId) {
								$oDomain->IdTenant = $iIdTenant;
								$aResult['result'] = $oApiDomainsManager->createDomain($oDomain);
								if (!$aResult['result']) {
									$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
									$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
								}
							}
							else
							{
								$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
								$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot find tenant', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestTenantFindFailed;
						}
					}
					else
					{
						$aResult['result'] = $oApiDomainsManager->createDomain($oDomain);
						if (!$aResult['result'])
						{
							$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'POST /account':

				$oDefaultDomain = $oApiDomainsManager->getDefaultDomain();

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';
				$sPassword = isset($aInputData['password']) ? trim($aInputData['password']) : '';
				$sParent = isset($aInputData['parent']) ? trim($aInputData['parent']) : '';
				$sCapa = isset($aInputData['capa']) ? trim($aInputData['capa']) : '';
				# HEO CHANGES
				$sQuota = isset($aInputData['tenantQuotaInMB']) ? trim($aInputData['tenantQuotaInMB']) : '0';
				#HEO CHANGES END

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
					$oDomain = /* @var $oDomain CDomain */ $oApiDomainsManager->getDomainByName($sDomainName);

					if (!$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						$oAccount = new CAccount($oDomain ? $oDomain : $oDefaultDomain);

						if (strlen($sParent) > 0)
						{
							$oParentAccount = $oApiUsersManager->getAccountByEmail($sParent);
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

						$oAccount->User->Capa = $sCapa;
						# HEO CHANGES
						if(isset($sQuota) && is_numeric($sQuota)){
							$oAccount->StorageQuota = (int)$sQuota * 1024;
						}
						# HEO CHANGES END

						$bNotInDomainRequiredInputParameters = (strlen($sEmail) > 0 && strlen($sPassword) > 0 && strlen($sIncomingMailLogin) > 0 && strlen($sIncomingMailServer) > 0 && $iIncomingMailPort > 0 && strlen($sOutgoingMailServer) > 0 && $iOutgoingMailPort > 0);

						if(!$oDomain && !$bNotInDomainRequiredInputParameters)
						{
							$aResult['result'] = false;
							$aResult['message'] = getErrorMessage('invalid input parameters', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
						}
						else
						{
							$aResult['result'] = $oApiUsersManager->createAccount($oAccount, !$oAccount->IsInternal);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot create account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
							}
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot create account', $oApiDomainsManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/update':

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);
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
						if (isset($aInputData['capa'])) {
							$oAccount->User->Capa = (string) $aInputData['capa'];
						}

						$aResult['result'] = $oApiUsersManager->updateAccount($oAccount);
						if (!$aResult['result'])
						{
							$aResult['message'] = getErrorMessage('cannot update account', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot update account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'DELETE /account':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';
				$sParent = isset($aInputData['parent']) ? $aInputData['parent'] : '';
				$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);

				if (strlen($sEmail) > 0 && $oApiUsersManager)
				{
					if (strlen($sParent) > 0)
					{
						$oParentAccount = $oApiUsersManager->getAccountByEmail($sParent);
						$iAccountToDeleteId = $oApiUsersManager->getUserAccountId($oParentAccount->IdUser, $sEmail);
					}
					else
					{
						$iAccountToDeleteId = $oAccount->IdAccount;
					}

					if ($iAccountToDeleteId)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$aResult['result'] = $oApiUsersManager->deleteAccountById($iAccountToDeleteId);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot delete account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot delete account, cannot find account in your tenant', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/enable':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);
					if ($oAccount)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$aResult['result'] = $oApiUsersManager->enableAccounts(array($oAccount->IdAccount), false);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot enable account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot enable account', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/disable':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);
					if ($oAccount)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$aResult['result'] = $oApiUsersManager->enableAccounts(array($oAccount->IdAccount), true);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot disable account', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot disable account', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /account/password':

				$sEmail = isset($aInputData['email']) ? $aInputData['email'] : '';
				$sNewPassword = isset($aInputData['password']) ? $aInputData['password'] : '';

				if (0 < strlen($sEmail) && 0 < strlen($sNewPassword) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);

					if ($oAccount)
					{
						if (!$iAuthTenantId || $oAccount->IdTenant === $iAuthTenantId)
						{
							$oAccount->IncomingMailPassword = $sNewPassword;

							$aResult['result'] = $oApiUsersManager->updateAccount($oAccount);
							if (!$aResult['result'])
							{
								$aResult['message'] = getErrorMessage('cannot change account password', $oApiUsersManager);
								$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot change account password', $oApiUsersManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /account/list':

				$iPage = isset($aInputData['page']) ? (int) trim ($aInputData['page']) : 1;
				$iUsersPerPage = isset($aInputData['usersPerPage']) ? (int) trim ($aInputData['usersPerPage']) : 100;
				$sOrderBy = isset($aInputData['orderBy']) ? strtolower (trim ($aInputData['orderBy'])) : 'email';
				$bOrderType = (isset($aInputData['orderType']) && trim($aInputData['orderType']) === 'false') ? false : true;
				$sSearchDesc = isset($aInputData['searchDesc']) ? trim ($aInputData['searchDesc']) : '';
				$sDomain = isset($aInputData['domain']) ? trim ($aInputData['domain']) : '';

				$oDomain = $oApiDomainsManager->getDomainByName($sDomain);
				$iDomainId = $oDomain ? $oDomain->IdDomain : 0;

				if (!$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
				{
					$aResult['result'] = $oApiUsersManager->getUserList($iDomainId, $iPage, $iUsersPerPage, $sOrderBy, $bOrderType, $sSearchDesc = '');
					if (!$aResult['result'])
					{
						$aResult['message'] = getErrorMessage('cannot get account list', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
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
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
				}

				break;

			case 'GET /account/exists':

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);

					if ($oAccount && !$iAuthTenantId || $oAccount && $oAccount->IdTenant === $iAuthTenantId)
					{
						$aResult['result'] = true;
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /account':

				$sEmail = isset($aInputData['email']) ? trim($aInputData['email']) : '';

				if (0 < strlen($sEmail) && $oApiUsersManager)
				{
					$oAccount = $oApiUsersManager->getAccountByEmail($sEmail);

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
							'signatureOptions' => $oAccount->SignatureOptions
						);
					}
					else
					{
						$aResult['message'] = getErrorMessage('cannot find account', $oApiUsersManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
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
						$iIdTenant = $oApiTenantsManager->getTenantIdByLogin($sTenant);

						if ($iIdTenant > 0)
						{
							if (!$iAuthTenantId || $iIdTenant === $iAuthTenantId)
							{
								$oDomain->IdTenant = $iIdTenant;
								$aResult['result'] = $oApiDomainsManager->createDomain($oDomain);
								if (!$aResult['result'])
								{
									$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
									$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
								}
							}
							else
							{
								$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
								$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
							}
						}
						else
						{
							$aResult['message'] = getErrorMessage('cannot find tenant', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestTenantFindFailed;
						}
					}
					else
					{
						$aResult['result'] = $oApiDomainsManager->createDomain($oDomain);
						if (!$aResult['result'])
						{
							$aResult['message'] = getErrorMessage('cannot create domain', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'PUT /domain/update':

				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->getDomainByName($sDomain);

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
							$aSkins = $oApiIntegratorManager->getThemeList();
							if (is_array($aSkins) && in_array($sSelSkin, $aSkins))
							{
								$oDomain->DefaultSkin = $sSelSkin;
							}
						}
						if (isset($aInputData['defaultTab'])) { //Default tab
							$sSelTab = (string) $aInputData['defaultTab'];
							$aTabs = $oApiIntegratorManager->getTabList($oDomain);
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
							$aLangs = $oApiIntegratorManager->getLanguageList();
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

						$aResult['result'] = $oApiDomainsManager->updateDomain($oDomain);
						if (!$aResult['result'])
						{
							$aResult['message'] = getErrorMessage('cannot update domain', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = 'cannot find domain';
						$aResult['errorCode'] = \ProjectCore\Notifications::RestDomainFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'DELETE /domain':

				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->getDomainByName($sDomain);

					if ($oDomain && !$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						$aResult['result'] = $oApiDomainsManager->deleteDomainByName($sDomain);
						if (!$aResult['result']) {
							$aResult['message'] = getErrorMessage('cannot delete domain', $oApiDomainsManager);
							$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
						}
					}
					else
					{
						$aResult['message'] = 'cannot find domain';
						$aResult['errorCode'] = \ProjectCore\Notifications::RestDomainFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /domain/list':

				$iPage = isset($aInputData['page']) ? (int) trim ($aInputData['page']) : 1;
				$iDomainsPerPage = isset($aInputData['domainsPerPage']) ? (int) trim ($aInputData['domainsPerPage']) : 100;
//				$sOrderBy = isset($aInputData['orderBy']) ? strtolower (trim ($aInputData['orderBy'])) : 'name';
				$bOrderType = (isset($aInputData['orderType']) && trim($aInputData['orderType']) === 'false') ? false : true;
				$sSearchDesc = isset($aInputData['searchDesc']) ? trim ($aInputData['searchDesc']) : '';
				$sTenant = isset($aInputData['tenant']) ? trim ($aInputData['tenant']) : '';

				$iIdTenant = $oApiTenantsManager->getTenantIdByLogin($sTenant);

				if (!$iAuthTenantId || $iIdTenant === $iAuthTenantId)
				{
					$aResult['result'] = $oApiDomainsManager->getDomainsList($iPage, $iDomainsPerPage, 'name', $bOrderType, $sSearchDesc, $iIdTenant);
					if (!$aResult['result'])
					{
						$aResult['message'] = getErrorMessage('cannot get domain list', $oApiDomainsManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
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
					$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
				}

				break;

			case 'GET /domain/exists':

				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->getDomainByName($sDomain);

					if ($oDomain && !$iAuthTenantId || $oDomain && $oDomain->IdTenant === $iAuthTenantId)
					{
						//$aResult['result'] = $oApiDomainsManager->domainExists($sDomain);
						$aResult['result'] = true;
					}
					else
					{
						$aResult['message'] = getErrorMessage('domain does not exist', $oApiDomainsManager);
						$aResult['errorCode'] = \ProjectCore\Notifications::RestOtherError;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			case 'GET /domain':
				$sDomain = isset($aInputData['domain']) ? trim($aInputData['domain']) : '';

				if (0 < strlen($sDomain) && $oApiDomainsManager)
				{
					$oDomain = $oApiDomainsManager->getDomainByName($sDomain);

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
						$aResult['errorCode'] = \ProjectCore\Notifications::RestAccountFindFailed;
					}
				}
				else
				{
					$aResult['message'] = 'invalid input parameters';
					$aResult['errorCode'] = \ProjectCore\Notifications::RestInvalidParameters;
				}

				break;

			default:
				$aResult['message'] = 'Invalid Endpoint';
				$aResult['errorCode'] = 400;
				break;
		}
	}
}

$aResult['$sContents'] = $sContents;

if (isset($aResult['result']) && $aResult['result'])
{
	$aResult['message'] = 'ok';
}
if (!isset($aResult['message']))
{
	$aResult['message'] = $bMethod ? 'error' : 'unknown method';
	$aResult['errorCode'] = $bMethod ? \ProjectCore\Notifications::RestOtherError : \ProjectCore\Notifications::RestUnknownMethod;
	$aResult['result'] = false;
}

function getErrorMessage ($sMessage, $oManager)
{
	$sResultMessage = $oManager ? $oManager->GetLastErrorMessage() : null;
	return empty($sResultMessage) ? $sMessage : $sResultMessage;
}

/*@header('Content-Type: text/html; charset=utf-8');
echo '<script>console.log('.json_encode($aResult, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0).');</script>';*/
@header('Content-Type: application/json; charset=utf-8');
echo json_encode($aResult, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0);