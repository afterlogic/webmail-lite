<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package base
 */
class api_Social
{
	public static $Debug = true;

	public static function Facebook($oTenant)
	{
		$bResult = false;
		$oUser = null;

		$bFacebookAllow = $oTenant->SocialFacebookAllow;
		$sFacebookId = $oTenant->SocialFacebookId;
		$sFacebookSecret = $oTenant->SocialFacebookSecret;
		$sRedirectUrl = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?facebook';

		if ($bFacebookAllow)
		{
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->debug = self::$Debug;
			$oClient->debug_http = self::$Debug;
			$oClient->server = 'Facebook';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $sFacebookId; $application_line = __LINE__;
			$oClient->client_secret = $sFacebookSecret;

			if(strlen($oClient->client_id) == 0 || strlen($oClient->client_secret) == 0)
			{
				$bResult = false;
				exit('Please go to Facebook Apps page https://developers.facebook.com/apps , create an application, and in the line '.$application_line.' set the client_id to App ID/API Key and client_secret with App Secret');
			}

			$oClient->scope = 'email';
			if(($success = $oClient->Initialize()))
			{
				if(($success = $oClient->Process()))
				{
					if (strlen($oClient->access_token))
					{
						$success = $oClient->CallAPI(
							'https://graph.facebook.com/me',
							'GET',
							array(),
							array('FailOnAccessError' => true),
							$oUser
						);
					}
				}

				$success = $oClient->Finalize($success);
			}

			if($oClient->exit)
			{
				$bResult = false;
				exit;
			}

			if ($success && $oUser)
			{
				$oClient->ResetAccessToken();

				$aSocial = array(
					'type' => 'facebook',
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : ''
				);

				\CApi::Log('social_user_facebook');
				\CApi::LogObject($oUser);

				$bResult = $aSocial;
			}
			else
			{
				$oClient->ResetAccessToken();
				self::_socialError($oClient->error, 'facebook');
				$bResult = false;
			}
		}
		
		return $bResult;
	}

	public static function Google($oTenant)
	{
		$bResult = false;
		$oUser = null;

		$bGoogleAllow = $oTenant->SocialGoogleAllow;
		$sGoogleId = $oTenant->SocialGoogleId;
		$sGoogleSecret = $oTenant->SocialGoogleSecret;
		$sRedirectUrl = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?social=google';

		if($bGoogleAllow)
		{
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->offline = true;
			$oClient->debug = self::$Debug;
			$oClient->debug_http = self::$Debug;
			$oClient->server = 'Google';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $sGoogleId; $application_line = __LINE__;
			$oClient->client_secret = $sGoogleSecret;

			if(strlen($oClient->client_id) == 0 || strlen($oClient->client_secret) == 0)
			{
				$bResult = false;
				exit('Please go to Google APIs console page http://code.google.com/apis/console in the API access tab, create a new client ID, and in the line '.$application_line.' set the client_id to Client ID and client_secret with Client Secret. The callback URL must be '.$oClient->redirect_uri.' but make sure the domain is valid and can be resolved by a public DNS.');
			}

			$oClient->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/drive';
			if(($success = $oClient->Initialize()))
			{
				if(($success = $oClient->Process()))
				{
					if(strlen($oClient->access_token))
					{
						$success = $oClient->CallAPI(
							'https://www.googleapis.com/oauth2/v1/userinfo',
							'GET',
							array(),
							array('FailOnAccessError'=>true),
							$oUser
						);
					}
					else
					{
						$oClient->error = $oClient->authorization_error;
						$success = false;
					}
				}
				$success = $oClient->Finalize($success);
			}

			if($oClient->exit)
			{
				$bResult = false;
				exit;
			}

			if($success && $oUser)
			{
				// if you need re-ask user for permission
				$oClient->ResetAccessToken();
				
				$iExpiresIn = 3600;
				$dAccessTokenExpiry = new DateTime($oClient->access_token_expiry);
				$aAccessToken = json_encode(array(
					'access_token' => $oClient->access_token,
					'created' => ($dAccessTokenExpiry->getTimestamp() - $iExpiresIn),
					'expires_in' => $iExpiresIn
				));
				
				$aSocial = array(
					'type' => 'google',
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : '',
					'access_token' => $aAccessToken,
					'refresh_token' => $oClient->refresh_token
				);

				\CApi::Log('social_user_google');
				\CApi::LogObject($oUser);

				$bResult = $aSocial;
			}
			else
			{
				$bResult = false;

				$oClient->ResetAccessToken();
				self::_socialError($oClient->error, 'google');
			}
		}
		return $bResult;
	}

	public static function Twitter($oTenant)
	{
		$bResult = false;
		$oUser = null;

		$bTwitterAllow = $oTenant->SocialTwitterAllow;
		$sTwitterId = $oTenant->SocialTwitterId;
		$sTwitterSecret = $oTenant->SocialTwitterSecret;
		$sRedirectUrl = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?twitter';

		if($bTwitterAllow)
		{
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->debug = self::$Debug;
			$oClient->debug_http = self::$Debug;
			$oClient->server = 'Twitter';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $sTwitterId; $application_line = __LINE__;
			$oClient->client_secret = $sTwitterSecret;

			if(strlen($oClient->client_id) == 0 || strlen($oClient->client_secret) == 0)
			{
				$bResult = false;
				exit('Please go to Twitter Apps page https://dev.twitter.com/apps/new , '.'create an application, and in the line '.$application_line.' set the client_id to Consumer key and client_secret with Consumer secret. '.'The Callback URL must be '.$oClient->redirect_uri.' If you want to post to '.'the user timeline, make sure the application you create has write permissions');
			}

			if(($success = $oClient->Initialize()))
			{
				if(($success = $oClient->Process()))
				{
					if(strlen($oClient->access_token))
					{
						$success = $oClient->CallAPI(
							'https://api.twitter.com/1.1/account/verify_credentials.json',
							'GET',
							array(),
							array('FailOnAccessError'=>true),
							$oUser
						);
					}
				}
				$success = $oClient->Finalize($success);
			}

			if($oClient->exit)
			{
				$bResult = false;
				exit;
			}

			if($success && $oUser)
			{
				// if you need re-ask user for permission
				//$oClient->ResetAccessToken();

				$aSocial = array(
					'type' => 'twitter',
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : ''
				);

				\CApi::Log('social_user_twitter');
				\CApi::LogObject($oUser);
				$bResult = $aSocial;
			}
			else
			{
				$bResult = false;
				$oClient->ResetAccessToken();
				self::_socialError($oClient->error, 'twitter');
			}
		}
		return $bResult;
	}
	
	public static function Dropbox($oTenant)
	{
		$bResult = false;
		$oUser = null;

		$bDropboxAllow = $oTenant->SocialDropboxAllow;
		
		$sDropboxId = $oTenant->SocialDropboxKey;
		$sDropboxSecret = $oTenant->SocialDropboxSecret;
		
		$sRedirectUrl = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?dropbox';
		if (!strpos($sRedirectUrl, '://localhost'))
		{
			$sRedirectUrl = str_replace('http:', 'https:', $sRedirectUrl);
		}
		if($bDropboxAllow)
		{
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->debug = self::$Debug;
			$oClient->debug_http = self::$Debug;
			$oClient->server = 'Dropbox2';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $sDropboxId; $application_line = __LINE__;
			$oClient->client_secret = $sDropboxSecret;
			$oClient->configuration_file = PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/'.$oClient->configuration_file;

			if(strlen($oClient->client_id) == 0 || strlen($oClient->client_secret) == 0)
			{
				$bResult = false;
				exit('Please go to Dropbox Apps page https://www.dropbox.com/developers/apps , '.
				'create an application, and in the line '.$application_line.
				' set the client_id to Consumer key and client_secret with Consumer secret. '.
				'The Callback URL must be '.$oClient->redirect_uri).' Make sure this URL is '.
				'not in a private network and accessible to the Dropbox site.';
			}

			if(($success = $oClient->Initialize()))
			{
				if(($success = $oClient->Process()))
				{
					if(strlen($oClient->access_token))
					{
						$success = $oClient->CallAPI(
							'https://api.dropbox.com/1/account/info', 
							'GET', array(), array('FailOnAccessError'=>true), $oUser);
					}
				}
				$success = $oClient->Finalize($success);
			}

			if($oClient->exit)
			{
				$bResult = false;
				exit;
			}

			if($success && $oUser)
			{
				// if you need re-ask user for permission
				//$oClient->ResetAccessToken();

				$aSocial = array(
					'type' => 'dropbox',
					'id' => $oUser->uid,
					'name' => $oUser->display_name,
					'email' => isset($oUser->email) ? $oUser->email : '',
					'access_token' => $oClient->access_token
				);

				\CApi::Log('social_user_dropbox');
				\CApi::LogObject($oUser);
				$bResult = $aSocial;
			}
			else
			{
				$bResult = false;
				$oClient->ResetAccessToken();
				self::_socialError($oClient->error, 'dropbox');
			}
		}
		return $bResult;
	}	

	public static function Init(&$aAppData, $sTenantHash)
	{
		@setcookie('p7tenantHash', $sTenantHash);
		$oTenant = self::GetTenantFromCookieOrHash($sTenantHash);
		$oApiIntegratorManager = \CApi::Manager('integrator');

		$bFacebookAllow = $bGoogleAllow = $bTwitterAllow = $bDropboxAllow = false;
		$sGoogleId = $sGoogleApiKey = $sDropboxKey = $sDropboxSecret = '';
		if ($oTenant)
		{
			$bFacebookAllow = $oTenant->SocialFacebookAllow;
			$bGoogleAllow = $oTenant->SocialGoogleAllow;
			$sGoogleId = $oTenant->SocialGoogleId;
			$sGoogleApiKey = $oTenant->SocialGoogleApiKey;
			$bTwitterAllow = $oTenant->SocialTwitterAllow;
			$bDropboxAllow = $oTenant->SocialDropboxAllow;
			$sDropboxKey = $oTenant->SocialDropboxKey;
			$sDropboxSecret = $oTenant->SocialDropboxSecret;
		}

		$aAppData['SocialFacebook'] = $bFacebookAllow;
		$aAppData['SocialGoogle'] = $bGoogleAllow;
		$aAppData['SocialGoogleId'] = $sGoogleId;
		$aAppData['SocialGoogleApiKey'] = $sGoogleApiKey;
		$aAppData['SocialTwitter'] = $bTwitterAllow;
		$aAppData['SocialDropbox'] = $bDropboxAllow;
		$aAppData['SocialDropboxKey'] = $sDropboxKey;
		$aAppData['SocialDropboxSecret'] = $sDropboxSecret;

		if(isset($_COOKIE['p7social']))
		{
			$aSocial = \CApi::DecodeKeyValues($_COOKIE['p7social']);
			$oUser = $oApiIntegratorManager->GetAhdSocialUser($sTenantHash, $aSocial['id']);

			if(strlen($aSocial['email']))
			{
				$sSocialType = $aSocial['type'];
				$sSocialId = $aSocial['id'];
				$sSocialName = $aSocial['name'];
				$sNotificationEmail = $aSocial['email'];

				if(!$oUser)
				{
					$mIdTenant = $oApiIntegratorManager->GetTenantIdByHash($sTenantHash);
					if (!is_int($mIdTenant))
					{
						throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidInputParameter);
					}
					$bResult = false;
					try
					{
						$bResult = $oApiIntegratorManager->RegisterSocialAccount($mIdTenant, $sTenantHash, $sNotificationEmail, $sSocialId, $sSocialType, $sSocialName);
					}
					catch (\Exception $oException)
					{
						$iErrorCode = \ProjectSeven\Notifications::UnknownError;
						if ($oException instanceof \CApiManagerException)
						{
							switch ($oException->getCode())
							{
								case \Errs::HelpdeskManager_UserAlreadyExists:
									$iErrorCode = \ProjectSeven\Notifications::HelpdeskUserAlreadyExists;
									break;
								case \Errs::HelpdeskManager_UserCreateFailed:
									$iErrorCode = \ProjectSeven\Notifications::CanNotCreateHelpdeskUser;
									break;
								case \Errs::Db_ExceptionError:
									$iErrorCode = \ProjectSeven\Notifications::DataBaseError;
									break;
							}
						}

						throw new \ProjectSeven\Exceptions\ClientException($iErrorCode);
					}
				}

				$oUser = $oApiIntegratorManager->GetAhdSocialUser($sTenantHash, $aSocial['id']);
			}

			if ($oUser)
			{
				$oApiIntegratorManager->SetHelpdeskUserAsLoggedIn($oUser, false);
				@setcookie ('p7social', '', time() - 1);
			}
			else
			{
				$aAppData['SocialEmail'] = $aSocial['email'];
				$aAppData['SocialIsLoggined'] = true;
			}
		}
	}

	public static function GetTenantHashFromCookie()
	{
		return isset($_COOKIE['p7tenantHash']) ? $_COOKIE['p7tenantHash'] : '';
	}
	
	public static function GetTenantFromCookieOrHash($sTenantHash='')
	{
		$oTenant = null;
		$sTenantHash = $sTenantHash ? $sTenantHash : self::GetTenantHashFromCookie();
		$oApiTenantsManager = /* @var $oApiTenantsManager \CApiTenantsManager */ \CApi::Manager('tenants');
		if ($oApiTenantsManager)
		{
			if ($sTenantHash)
			{
				$oTenant = $oApiTenantsManager->GetTenantByHash($sTenantHash);
			}
			else
			{
				$oAccount /* @var $oAccount \CAccount */ = \api_Utils::GetDefaultAccount();
				if ($oAccount && 0 < $oAccount->IdTenant)
				{
					$oTenant = $oApiTenantsManager->GetTenantById($oAccount->IdTenant);
				}
				else
				{
					$oTenant = $oApiTenantsManager->GetDefaultGlobalTenant();
				}
			}
		}
		return $oTenant;
	}

	public static function Process($mResult)
	{
		$sSocialRedirect = '';
		if (isset($_COOKIE["SocialRedirect"]))
		{
			$sSocialRedirect = $_COOKIE["SocialRedirect"];
			@setcookie('SocialRedirect', null);
		}
		if ($sSocialRedirect === 'helpdesk')
		{
			self::SetValuesToCookie($mResult);
			$sTenantHash = self::GetTenantHashFromCookie();
			if ($sTenantHash)
			{
				\CApi::Location('./?helpdesk='.$sTenantHash);
			}
			else
			{
				\CApi::Location('./?helpdesk');
			}
		}
		else
		{
			$oAccount = \api_Utils::GetDefaultAccount();
			$oApiSocial = /* @var $oApiSocial \CApiSocialManager */ \CApi::Manager('social');
			$oSocial = new \CSocial();
			switch ($mResult['type'])
			{
				case 'google':
					$oSocial->Type = \ESocialType::Google;
					$oSocial->AccessToken = $mResult['access_token'];
					$oSocial->RefreshToken = $mResult['refresh_token'];
					break;
				case 'dropbox':
					$oSocial->Type = \ESocialType::Dropbox;
					$oSocial->AccessToken = $mResult['access_token'];
					break;
			}

			$oSocial->IdSocial = $mResult['id'];
			$oSocial->Name = $mResult['name'];
			
			if ($sSocialRedirect === 'login')
			{
				$oSocial->Scopes = 'LOGIN';
				$oApiUsers = /* @var $oApiUsers \CApiUsersManager */ \CApi::Manager('users');
				
				if (!$oApiSocial->SocialExists($oSocial->Type, $oSocial->IdSocial))
				{
					$oAccount = $oApiUsers->GetAccountOnLogin($mResult['email']);
					if ($oAccount)
					{
						$oSocial->IdAccount = $oAccount->IdAccount;
						$oApiSocial->CreateSocial($oSocial);
					}
					else
					{
						// Account does not exist
					}
				}
				else
				{
					$oSocial = $oApiSocial->GetSocialById($oSocial->IdSocial, $oSocial->Type);
					if ($oSocial && $oSocial->IssetScope('auth'))
					{
						$oAccount = $oApiUsers->GetAccountById($oSocial->IdAccount);
					}
					else
					{
						// Account already exist
					}
				}
				
				$oApiIntegrator = \CApi::Manager('integrator');
				$oApiIntegrator->SetAccountAsLoggedIn($oAccount, true);
				
				\CApi::Location('./');
			}
			else
			{
				if ($oAccount)
				{
					$oSocial->IdAccount = $oAccount->IdAccount;
					$bCreateResult = $oApiSocial->CreateSocial($oSocial);
					echo 
					"<script>"
						. "if (typeof(window.opener.servicesSettingsViewModelCallback) !== 'undefined')"
						. "{"
						.		"window.opener.servicesSettingsViewModelCallback('".$mResult['type']."', ".$bCreateResult.");"
						.		"window.close();"
						. "}"
					. "</script>";
				}
			}
		}
	}

	public static function SetValuesToCookie($aValues)
	{
		@setcookie("p7social", \CApi::EncodeKeyValues($aValues));
	}
	
	public static function ClearValuesFromCookie()
	{
		@setcookie("p7social", null);
	}

	private static function _socialError($oClientError, $sSocialName)
	{
		\CApi::Log($sSocialName, ' error');
		\CApi::LogObject($oClientError);
	}

}
