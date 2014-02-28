<?php

/**
 * @package base
 */
class api_Social
{
	public function Facebook()
	{
		$sTenantHash = isset($_COOKIE["TenantHash"]) ? $_COOKIE["TenantHash"] : '';
		$oTenant = self::_getTenant($sTenantHash);

		$bFacebookAllow = $oTenant->SocialFacebookAllow;
		$sFacebookId = $oTenant->SocialFacebookId;
		$sFacebookSecret = $oTenant->SocialFacebookSecret;
		$sRedirectUrl = $_SERVER["HTTP_HOST"] === 'localhost' ? "http://".$_SERVER["HTTP_HOST"].'/p7loc/?facebook' : "http://".$_SERVER["HTTP_HOST"].'/?facebook';

		if($bFacebookAllow)
		{
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->debug = 1;
			$oClient->debug_http = 1;
			$oClient->server = 'Facebook';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $sFacebookId; $application_line = __LINE__;
			$oClient->client_secret = $sFacebookSecret;
//			$oClient->client_id = '372151616255770'; $application_line = __LINE__;
//			$oClient->client_secret = 'e215b82662d313348069c5eb8fd78c2a';

			if(strlen($oClient->client_id) == 0 || strlen($oClient->client_secret) == 0)
			{
				exit('Please go to Facebook Apps page https://developers.facebook.com/apps , create an application, and in the line '.$application_line.' set the client_id to App ID/API Key and client_secret with App Secret');
			}

			$oClient->scope = 'email';
			if(($success = $oClient->Initialize()))
			{
				if(($success = $oClient->Process()))
				{
					if(strlen($oClient->access_token))
					{
						$success = $oClient->CallAPI(
							'https://graph.facebook.com/me',
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
				exit;
			}

			if($success)
			{
				$oClient->ResetAccessToken();

				$aSocial = array(
					'type' => 'facebook',
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : ''
				);
				@setcookie("Social", \CApi::EncodeKeyValues($aSocial));

				self::_goToHelpdesk($sTenantHash);
			}
			else
			{
				self::_socialError($oClient, 'facebook');
			}
		}
	}

	public function Google()
	{
		$sTenantHash = isset($_COOKIE["TenantHash"]) ? $_COOKIE["TenantHash"] : '';
		$oTenant = self::_getTenant($sTenantHash);

		$bGoogleAllow = $oTenant->SocialGoogleAllow;
		$sGoogleId = $oTenant->SocialGoogleId;
		$sGoogleSecret = $oTenant->SocialGoogleSecret;
		$sRedirectUrl = $_SERVER["HTTP_HOST"] === 'localhost' ? "http://".$_SERVER["HTTP_HOST"].'/p7loc/?google' : "http://".$_SERVER["HTTP_HOST"].'/?google';

		if($bGoogleAllow)
		{
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->offline = true;
			$oClient->debug = false;
			$oClient->debug_http = true;
			$oClient->server = 'Google';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $sGoogleId; $application_line = __LINE__;
			$oClient->client_secret = $sGoogleSecret;

			if(strlen($oClient->client_id) == 0 || strlen($oClient->client_secret) == 0)
			{
				exit('Please go to Google APIs console page http://code.google.com/apis/console in the API access tab, create a new client ID, and in the line '.$application_line.' set the client_id to Client ID and client_secret with Client Secret. The callback URL must be '.$oClient->redirect_uri.' but make sure the domain is valid and can be resolved by a public DNS.');
			}

			$oClient->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
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
				exit;
			}

			if($success)
			{
				// if you need re-ask user for permission
				//$oClient->ResetAccessToken();

				$aSocial = array(
					'type' => 'google',
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : ''
				);
				@setcookie("Social", \CApi::EncodeKeyValues($aSocial));

				self::_goToHelpdesk($sTenantHash);
			}
			else
			{
				self::_socialError($oClient, 'google');
			}
		}
	}

	public function Twitter()
	{
		$sTenantHash = isset($_COOKIE["TenantHash"]) ? $_COOKIE["TenantHash"] : '';
		$oTenant = self::_getTenant($sTenantHash);

		$bTwitterAllow = $oTenant->SocialTwitterAllow;
		$sTwitterId = $oTenant->SocialTwitterId;
		$sTwitterSecret = $oTenant->SocialTwitterSecret;
		$sRedirectUrl = $_SERVER["HTTP_HOST"] === 'localhost' ? "http://".$_SERVER["HTTP_HOST"].'/p7loc/?twitter' : "http://".$_SERVER["HTTP_HOST"].'/?twitter';

		if($bTwitterAllow)
		{
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->debug = 1;
			$oClient->debug_http = 1;
			$oClient->server = 'Twitter';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $sTwitterId; $application_line = __LINE__;
			$oClient->client_secret = $sTwitterSecret;

			if(strlen($oClient->client_id) == 0 || strlen($oClient->client_secret) == 0)
			{
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
				exit;
			}

			if($success)
			{
				// if you need re-ask user for permission
				//$oClient->ResetAccessToken();

				$aSocial = array(
					'type' => 'twitter',
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : ''
				);
				@setcookie("Social", \CApi::EncodeKeyValues($aSocial));

				self::_goToHelpdesk($sTenantHash);
			}
			else
			{
				self::_socialError($oClient, 'twitter');
			}
		}
	}

	public function Init($aAppData, $sTenantHash)
	{
		$oTenant = self::_getTenant($sTenantHash);
		$oApiIntegratorManager = \CApi::Manager('integrator');

		$bFacebookAllow = $bGoogleAllow = $bTwitterAllow = false;
		if ($oTenant)
		{
			$bFacebookAllow = $oTenant->SocialFacebookAllow;
			$bGoogleAllow = $oTenant->SocialGoogleAllow;
			$bTwitterAllow = $oTenant->SocialTwitterAllow;
		}

		$aAppData['SocialFacebook'] = $bFacebookAllow;
		$aAppData['SocialGoogle'] = $bGoogleAllow;
		$aAppData['SocialTwitter'] = $bTwitterAllow;

		@setcookie("TenantHash", $sTenantHash);

		if(isset($_COOKIE["Social"]))
		{
			$aSocial = \CApi::DecodeKeyValues($_COOKIE["Social"]);
			$oUser = $oApiIntegratorManager->GetAhdSocialUser($sTenantHash, $aSocial['id']);
//			@setcookie ("Social", "", time() - 1);

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
				@setcookie ("Social", "", time() - 1);
			}
			else
			{
				$aAppData['SocialEmail'] = $aSocial['email'];
				$aAppData['SocialIsLoggined'] = true;
			}
		}

		return $aAppData;
	}



	private function _getTenant($sTenantHash)
	{
		$oApiTenantsManager = /* @var $oApiTenantsManager \CApiTenantsManager */ \CApi::Manager('tenants');
		if($sTenantHash)
		{
			$oTenant = $oApiTenantsManager->GetTenantByHash($_COOKIE["TenantHash"]);
		}
		else
		{
			$oTenant = $oApiTenantsManager->GetDefaultGlobalTenant();
		}

		return $oTenant;
	}

	private function _goToHelpdesk($sTenantHash)
	{
		if($sTenantHash)
		{
			\CApi::Location('./?helpdesk='.$sTenantHash);
		}
		else
		{
			\CApi::Location('./?helpdesk');
		}
	}

	private function _socialError($oClient, $sSocialName)
	{
		$oClient->ResetAccessToken();
		\CApi::Log($sSocialName,' error');
		\CApi::LogObject($oClient->error);
	}
}
