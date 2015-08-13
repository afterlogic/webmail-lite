<?php

class CExternalServicesConnectorTwitter extends CExternalServicesConnector
{
	public static $ConnectorName = 'twitter';
	
	public static function GetSupportedScopes()
	{
		return array('auth');
	}

	public static function CreateClient($oTenant)
	{
		$oClient = null;
		$oSocial = $oTenant->getSocialByName(self::$ConnectorName);
		
		if(isset($oSocial) && $oSocial->SocialAllow)
		{
			$sRedirectUrl = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?external-services=' . self::$ConnectorName;
			if (!strpos($sRedirectUrl, '://localhost'))
			{
				$sRedirectUrl = str_replace('http:', 'https:', $sRedirectUrl);
			}
			
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->debug = self::$Debug;
			$oClient->debug_http = self::$Debug;
			$oClient->server = 'Twitter';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $oSocial->SocialId;
			$oClient->client_secret = $oSocial->SocialSecret;
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
					'type' => self::$ConnectorName,
					'id' => $oUser->id,
					'name' => $oUser->name,
					'email' => isset($oUser->email) ? $oUser->email : '',
					'access_token' => $oClient->access_token,
					'scopes' => self::$Scopes
				);

				\CApi::Log('social_user_' . self::$ConnectorName);
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