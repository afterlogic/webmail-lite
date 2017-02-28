<?php

class CExternalServicesConnectorGoogle  extends CExternalServicesConnector
{
	public static $ConnectorName = 'google';
	
	public static function GetSupportedScopes()
	{
		return array('auth', 'filestorage');
	}

	public static function HasApiKey()
	{
		return true;
	}

	public static function CreateClient($oTenant = null)
	{
		$oClient = null;
		$oSocial = $oTenant->getSocialByName(self::$ConnectorName);
		
		if(isset($oSocial) && $oSocial->SocialAllow)
		{
			$sRedirectUrl = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?external-services='.self::$ConnectorName;
			
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->offline = true;
			$oClient->debug = self::$Debug;
			$oClient->debug_http = self::$Debug;
			$oClient->server = 'Google';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $oSocial->SocialId;
			$oClient->client_secret = $oSocial->SocialSecret;

			$oClient->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
			if (in_array('filestorage', self::$Scopes))
			{
				$oClient->scope = $oClient->scope . ' https://www.googleapis.com/auth/drive';
			}
		}
		
		return $oClient;
	}

	public static function Init($oTenant = null)
	{
		parent::Init($oTenant);
		
		$bResult = false;
		$oUser = null;

		$oClient = self::CreateClient($oTenant);
		if($oClient)
		{
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
					'type' => self::$ConnectorName,
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : '',
					'access_token' => $aAccessToken,
					'refresh_token' => $oClient->refresh_token,
					'scopes' => self::$Scopes
				);

				\CApi::Log('social_user_'.self::$ConnectorName);
				\CApi::LogObject($oUser);

				$bResult = $aSocial;
			}
			else
			{
				$bResult = false;

				$oClient->ResetAccessToken();
				self::_socialError($oClient->error, self::$ConnectorName);
			}
		}
		
		return $bResult;
	}
}