<?php

class CExternalServicesConnectorFacebook extends CExternalServicesConnector
{
	public static $ConnectorName = 'facebook';
	
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
			$sRedirectUrl = rtrim(\MailSo\Base\Http::SingletonInstance()->GetFullUrl(), '\\/ ').'/?external-services='.self::$ConnectorName;
			
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/http.php');
			require(PSEVEN_APP_ROOT_PATH.'libraries/OAuthClient/oauth_client.php');

			$oClient = new \oauth_client_class;
			$oClient->debug = self::$Debug;
			$oClient->debug_http = self::$Debug;
			$oClient->server = 'Facebook';
			$oClient->redirect_uri = $sRedirectUrl;
			$oClient->client_id = $oSocial->SocialId;
			$oClient->client_secret = $oSocial->SocialSecret;
			$oClient->scope = 'email';
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
				$oClient->ResetAccessToken();
				self::_socialError($oClient->error, self::$ConnectorName);
				$bResult = false;
			}
		}
		
		return $bResult;
	}
}