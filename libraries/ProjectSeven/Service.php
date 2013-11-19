<?php

namespace ProjectSeven;

/**
 * @category ProjectSeven
 */
class Service
{
	/**
	 * @var \MailSo\Base\Http
	 */
	protected $oHttp;

	/**
	 * @var \ProjectSeven\Actions
	 */
	protected $oActions;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		$this->oHttp = \MailSo\Base\Http::NewInstance();
		$this->oActions = Actions::NewInstance();
		$this->oActions->SetHttp($this->oHttp);

		@\set_error_handler(array(&$this, 'LogPhpError'));
	}

	/**
	 * @return \ProjectSeven\Service
	 */
	public static function NewInstance()
	{
		return new self();
	}

	public static function LogPhpError($errno, $errstr, $errfile, $errline)
	{
		$iLogLevel = \ELogLevel::Warning;
		switch ($errno)
		{
			 case E_USER_ERROR:
			 case E_USER_WARNING:
				 $iLogLevel = \ELogLevel::Error;
				 break;
		}

		\CApi::Log('[PHP] '.$errfile.' (line:'.$errline.', code:'.$errno.')', $iLogLevel);
		\CApi::Log('[PHP] Error: '.$errstr, $iLogLevel);

		return false;
	}

	/**
	 * @return bool
	 */
	protected function validateToken()
	{
		return $this->oHttp->IsPost() ? $this->oActions->ValidateCsrfToken($this->oHttp->GetPost('Token')) : true;
	}

	/**
	 * @param bool $bHelpdesk = false
	 * @param string $sHelpdeskHash = ''
	 * @param string $sCalendarPubHash = ''
	 * @return string
	 */
	private function indexHTML($bHelpdesk = false, $sHelpdeskHash = '', $sCalendarPubHash = '')
	{
		$sResult = '';
		$mHelpdeskIdTenant = false;
		
		$oApiIntegrator = \CApi::Manager('integrator');
		if ($oApiIntegrator)
		{
			if ($bHelpdesk)
			{
				$oApiHelpdesk = \CApi::Manager('helpdesk');
				if ($oApiHelpdesk)
				{
					$oLogginedAccount = $this->oActions->GetDefaultAccount();

					$oApiCapability = \CApi::Manager('capability');

					$mHelpdeskIdTenant = $oApiIntegrator->GetTenantIdByHash($sHelpdeskHash);
					if (!is_int($mHelpdeskIdTenant))
					{
						\CApi::Location('./');
						return '';
					}

					$bDoId = false;
					$sThread = $this->oHttp->GetQuery('thread');
					if (0 < strlen($sThread))
					{
						if ($oApiHelpdesk)
						{
							$iThreadID = $oApiHelpdesk->GetThreadIdByHash($mHelpdeskIdTenant, $sThread);
							if (0 < $iThreadID)
							{
								$oApiIntegrator->SetThreadIdFromRequest($iThreadID);
								$bDoId = true;
							}
						}
					}

					$sActivateHash = $this->oHttp->GetQuery('activate');
					if (0 < strlen($sActivateHash) && !$this->oHttp->HasQuery('forgot'))
					{
						$bRemove = true;
						$oUser = $oApiHelpdesk->GetUserByActivateHash($mHelpdeskIdTenant, $sActivateHash);
						/* @var $oUser \CHelpdeskUser */
						if ($oUser)
						{
							if (!$oUser->Activated)
							{
								$oUser->Activated = true;
								$oUser->RegenerateActivateHash();

								if ($oApiHelpdesk->UpdateUser($oUser))
								{
									$bRemove = false;
									$oApiIntegrator->SetUserAsActivated($oUser);
								}
							}
						}

						if ($bRemove)
						{
							$oApiIntegrator->RemoveUserAsActivated();
						}
					}
					
					if ($oLogginedAccount && $oApiCapability && $oApiCapability->IsHelpdeskSupported($oLogginedAccount) &&
						$oLogginedAccount->IdTenant === $mHelpdeskIdTenant)
					{
						if (!$bDoId)
						{
							$oApiIntegrator->SetThreadIdFromRequest(0);
						}
						
						\CApi::Location('./');
						return '';
					}
				}
				else
				{
					\CApi::Location('./');
					return '';
				}
			}

			$sResult = file_get_contents(PSEVEN_APP_ROOT_PATH.'templates/Index.html');
			if (is_string($sResult))
			{
				@header('Content-Type: text/html; charset=utf-8', true);
				
				$sResult = strtr($sResult, array(
					'{{AppVersion}}' => PSEVEN_APP_VERSION,
					'{{IntegratorDir}}' => $oApiIntegrator->GetAppDirValue(),
					'{{IntegratorLinks}}' => $oApiIntegrator->BuildHeadersLink('.', $bHelpdesk, $sCalendarPubHash),
					'{{IntegratorBody}}' => $oApiIntegrator->BuildBody('.', $bHelpdesk, $mHelpdeskIdTenant, $sHelpdeskHash, $sCalendarPubHash)
				));
			}
		}
		else
		{
			$sResult = '';
		}

		return $sResult;
	}

	/**
	 * @return void
	 */
	public function Handle()
	{
		$sVersion = file_get_contents(PSEVEN_APP_ROOT_PATH.'VERSION');
		define('PSEVEN_APP_VERSION', $sVersion);

		if (!class_exists('MailSo\Version'))
		{
			echo 'MailSo';
			return '';
		}
		else if (!class_exists('\\CApi') || !\CApi::IsValid())
		{
			echo 'AfterLogic API';
			return '';
		}

		$sResult = '';
		$aPaths = explode('/', trim(trim($this->oHttp->GetServer('QUERY_STRING', '')), '/'));
		if (0 < count($aPaths) && !empty($aPaths[0]))
		{
			$sFirstPart = strtolower($aPaths[0]);
			
			if ('ping' === $sFirstPart)
			{
				@header('Content-Type: text/plain; charset=utf-8');
				$sResult = 'Pong';
			}
			else if (('ajax' ===$sFirstPart))
			{
				@ob_start();

				$aResponseItem = null;
				$sAction = $this->oHttp->GetPost('Action', null);
				try
				{
					\CApi::Log('AJAX: Action: '.$sAction);
					if ('AppData' !== $sAction &&
						\CApi::GetConf('labs.webmail.csrftoken-protection', true) &&
						!$this->validateToken())
					{
						throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::InvalidToken);
					}
					else if (!empty($sAction))
					{
						$sMethodName = 'Ajax'.$sAction;
						if (method_exists($this->oActions, $sMethodName) &&
							is_callable(array($this->oActions, $sMethodName)))
						{
							$this->oActions->SetActionParams($this->oHttp->GetPostAsArray());
							$aResponseItem = call_user_func(array($this->oActions, $sMethodName));
						}
						else if (\CApi::Plugin()->JsonHookExists($sMethodName))
						{
							$aResponseItem = \CApi::Plugin()->RunJsonHook($this->oActions, $sMethodName);
						}
					}

					if (!is_array($aResponseItem))
					{
						throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::UnknownError);
					}
				}
				catch (\Exception $oException)
				{
//					if ($oException instanceof \ProjectSeven\Exceptions\ClientException &&
//						\ProjectSeven\Notifications::AuthError === $oException->getCode())
//					{
//						$oApiIntegrator = /* @var $oApiIntegrator \CApiIntegratorManager */ \CApi::Manager('integrator');
//						$oApiIntegrator->SetLastErrorCode(\ProjectSeven\Notifications::AuthError);
//						$oApiIntegrator->LogoutAccount();
//					}

					$sAction = empty($sAction) ? 'Unknown' : $sAction;

					$aAdd = null;
					if ('Login' === $sAction && isset($GLOBALS['P7_CAPTCHA_ATTRIBUTE_ON_ERROR']) && $GLOBALS['P7_CAPTCHA_ATTRIBUTE_ON_ERROR'])
					{
						$aAdd = array(
							'Captcha' => true
						);
					}

					$aResponseItem = $this->oActions->ExceptionResponse(null, $sAction, $oException, $aAdd);
				}

				@header('Content-Type: application/json; charset=utf-8');

				$sResult = \ProjectSeven\Base\Utils::JsonEncode($aResponseItem);
//				\CApi::Log('AJAX: Response: '.$sResult);
			}
			else if ('upload' === $sFirstPart)
			{
				@ob_start();
				$aResponseItem = null;
				$sAction = empty($aPaths[1]) ? '' : $aPaths[1];
				try
				{
					$sMethodName = 'Upload'.$sAction;
					if (method_exists($this->oActions, $sMethodName) &&
						is_callable(array($this->oActions, $sMethodName)))
					{
						$sError = '';
						$sInputName = 'jua-uploader';

						$iError = UPLOAD_ERR_OK;
						$_FILES = isset($_FILES) ? $_FILES : null;
						if (isset($_FILES, $_FILES[$sInputName], $_FILES[$sInputName]['name'], $_FILES[$sInputName]['tmp_name'], $_FILES[$sInputName]['size'], $_FILES[$sInputName]['type']))
						{
							$iError = (isset($_FILES[$sInputName]['error'])) ? (int) $_FILES[$sInputName]['error'] : UPLOAD_ERR_OK;
							if (UPLOAD_ERR_OK === $iError)
							{
								$this->oActions->SetActionParams(array(
									'AccountID' => $this->oHttp->GetPost('AccountID', ''),
									'FileData' => $_FILES[$sInputName],
									'AdditionalData' => $this->oHttp->GetPost('AdditionalData', null),
									'IsExt' => '1' === (string) $this->oHttp->GetPost('IsExt', '0') ? '1' : '0',
									'TenantHash' => (string) $this->oHttp->GetPost('TenantHash', ''),
									'Token' => $this->oHttp->GetPost('Token', '')
								));

								\CApi::LogObject($this->oActions->GetActionParams());

								$aResponseItem = call_user_func(array($this->oActions, $sMethodName));
							}
							else
							{
								$sError = $this->oActions->convertUploadErrorToString($iError);
							}
						}
						else if (!isset($_FILES) || !is_array($_FILES) || 0 === count($_FILES))
						{
							$sError = 'size';
						}
						else
						{
							$sError = 'unknown';
						}
					}

					if (!is_array($aResponseItem) && empty($sError))
					{
						throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::UnknownError);
					}
				}
				catch (\Exception $oException)
				{
					\CApi::LogException($oException);
					$aResponseItem = $this->oActions->ExceptionResponse(null, 'Upload', $oException);
					$sError = 'exception';
				}

				if (0 < strlen($sError))
				{
					$aResponseItem['Error'] = $sError;
				}

				@ob_get_clean();
				if ('iframe' === $this->oHttp->GetPost('jua-post-type', ''))
				{
					@header('Content-Type: text/html; charset=utf-8');
				}
				else
				{
					@header('Content-Type: application/json; charset=utf-8');
				}

				$sResult = \ProjectSeven\Base\Utils::JsonEncode($aResponseItem);
			}
			else if ('speclogon' === $sFirstPart || 'speclogoff' === $sFirstPart)
			{
				\CApi::SpecifiedUserLogging('speclogon' === $sFirstPart);
				\CApi::Location('./');
			}
			else if ('profile' === $sFirstPart)
			{
				/* @var $oApiIosManager \CApiIos2Manager */
				$oApiIosManager = \CApi::Manager('ios2');

				/* @var $oApiIntegrator \CApiIntegratorManager */
				$oApiIntegrator = \CApi::Manager('integrator');

				$oAccount = $oApiIntegrator->GetLogginedDefaultAccount();

				$mResultProfile = $oApiIosManager && $oAccount ? $oApiIosManager->GenerateXMLProfile($oAccount) : false;

				if ($mResultProfile !== false)
				{
					header('Content-type: application/x-apple-aspen-config; chatset=utf-8');
					header('Content-Disposition: attachment; filename="afterlogic.mobileconfig"');
					echo $mResultProfile;
				}
				else
				{
					\CApi::Location('./?IOS/Error');
				}
			}
			else if ('ios' === $sFirstPart)
			{
				$sResult = file_get_contents(PSEVEN_APP_ROOT_PATH.'templates/Ios.html');

				/* @var $oApiIntegrator \CApiIntegratorManager */
				$oApiIntegrator = \CApi::Manager('integrator');

				$iUserId = $oApiIntegrator->GetLogginedUserId();
				if (0 < $iUserId)
				{
					$oAccount = $oApiIntegrator->GetLogginedDefaultAccount();
					$bError = isset($aPaths[1]) && 'error' === strtolower($aPaths[1]); // TODO

					@setcookie('skip_ios', '1', time() + 3600 * 3600, '/', null, null, true);
					
					$sResult = strtr($sResult, array(
						'{{IOS/HELLO}}' => \CApi::I18N('IOS/HELLO'),
						'{{IOS/DESC_P1}}' => \CApi::I18N('IOS/DESC_P1'),
						'{{IOS/DESC_P2}}' => \CApi::I18N('IOS/DESC_P2'),
						'{{IOS/DESC_P3}}' => \CApi::I18N('IOS/DESC_P3'),
						'{{IOS/DESC_P4}}' => \CApi::I18N('IOS/DESC_P4'),
						'{{IOS/DESC_P5}}' => \CApi::I18N('IOS/DESC_P5'),
						'{{IOS/DESC_P6}}' => \CApi::I18N('IOS/DESC_P6'),
						'{{IOS/DESC_P7}}' => \CApi::I18N('IOS/DESC_P7'),
						'{{IOS/DESC_BUTTON_YES}}' => \CApi::I18N('IOS/DESC_BUTTON_YES'),
						'{{IOS/DESC_BUTTON_SKIP}}' => \CApi::I18N('IOS/DESC_BUTTON_SKIP'),
						'{{IOS/DESC_BUTTON_OPEN}}' => \CApi::I18N('IOS/DESC_BUTTON_OPEN'),
						'{{AppVersion}}' => PSEVEN_APP_VERSION,
						'{{IntegratorLinks}}' => $oApiIntegrator->BuildHeadersLink()
					));
				}
				else
				{
					\CApi::Location('./');
				}
			}
			else if ('raw' === $sFirstPart)
			{
				$sAction = empty($aPaths[1]) ? '' : $aPaths[1];
				try
				{
					if (!empty($sAction))
					{
						$sMethodName =  'Raw'.$sAction;
						if (method_exists($this->oActions, $sMethodName))
						{
							$this->oActions->SetActionParams(array(
								'AccountID' => empty($aPaths[2]) || '0' === (string) $aPaths[2] ? '' : $aPaths[2],
								'RawKey' => empty($aPaths[3]) ? '' : $aPaths[3],
								'IsExt' => empty($aPaths[4]) ? '0' : ('1' === (string) $aPaths[4] ? '1' : 0),
								'TenantHash' => empty($aPaths[5]) ? '' : $aPaths[5]
							));

							if (!call_user_func(array($this->oActions, $sMethodName)))
							{
								\CApi::Log('False result.', \ELogLevel::Error);
							}
						}
						else
						{
							\CApi::Log('Invalid action.', \ELogLevel::Error);
						}
					}
					else
					{
						\CApi::Log('Empty action.', \ELogLevel::Error);
					}
				}
				catch (\Exception $oException)
				{
					\CApi::LogException($oException, \ELogLevel::Error);
				}
			}
			else if ($this->oHttp->HasQuery('helpdesk'))
			{
				$sResult = $this->indexHTML(true, $this->oHttp->GetQuery('helpdesk'));
			}
			else if ($this->oHttp->HasQuery('calendar-pub') && 0 < strlen($this->oHttp->GetQuery('calendar-pub')))
			{
				$sResult = $this->indexHTML(false, '', $this->oHttp->GetQuery('calendar-pub'));
			}
			else if ('min' === $sFirstPart || 'window' === $sFirstPart)
			{
				$sAction = empty($aPaths[1]) ? '' : $aPaths[1];
				try
				{
					if (!empty($sAction))
					{
						$sMethodName =  $aPaths[0].$sAction;
						if (method_exists($this->oActions, $sMethodName))
						{
							if ('Min' === $aPaths[0])
							{
								$oMinManager = /* @var $oMinManager \CApiMinManager */ \CApi::Manager('min');
								$mHashResult = $oMinManager->GetMinByHash(empty($aPaths[2]) ? '' : $aPaths[2]);

								$this->oActions->SetActionParams(array(
									'Result' => $mHashResult,
									'Hash' => empty($aPaths[2]) ? '' : $aPaths[2],
								));
							}
							else
							{
								$this->oActions->SetActionParams(array(
									'AccountID' => empty($aPaths[2]) || '0' === (string) $aPaths[2] ? '' : $aPaths[2],
									'RawKey' => empty($aPaths[3]) ? '' : $aPaths[3]
								));
							}

							$mResult = call_user_func(array($this->oActions, $sMethodName));
							$sTemplate = isset($mResult['Template']) && !empty($mResult['Template']) &&
								is_string($mResult['Template']) ? $mResult['Template'] : null;

							if (!empty($sTemplate) && is_array($mResult) && file_exists(PSEVEN_APP_ROOT_PATH.$sTemplate))
							{
								$sResult = file_get_contents(PSEVEN_APP_ROOT_PATH.$sTemplate);
								if (is_string($sResult))
								{
									$sResult = strtr($sResult, $mResult);
								}
								else
								{
									\CApi::Log('Empty template.', \ELogLevel::Error);
								}
							}
							else if (!empty($sTemplate))
							{
								\CApi::Log('Empty template.', \ELogLevel::Error);
							}
							else if (true === $mResult)
							{
								$sResult = '';
							}
							else
							{
								\CApi::Log('False result.', \ELogLevel::Error);
							}
						}
						else
						{
							\CApi::Log('Invalid action.', \ELogLevel::Error);
						}
					}
					else
					{
						\CApi::Log('Empty action.', \ELogLevel::Error);
					}
				}
				catch (\Exception $oException)
				{
					\CApi::LogException($oException);
				}
			}
			else if ('twilio' === $sFirstPart)
			{
				\CApi::Log('twilio_xml');
				
				
				\CApi::LogObject($aPaths);
				\CApi::LogObject($_REQUEST);
				
				$bDirection = $this->oHttp->GetRequest('Direction') === 'inbound' ? true : false;
				$sDigits = $this->oHttp->GetRequest('Digits');
				
				$sTenantId = $aPaths[1];
				
				$bAllowTwilio = false;

				if (is_numeric($sTenantId)) {
					$oApiTenants = \CApi::Manager('tenants');
					$oTenant = $oApiTenants->GetTenantById($sTenantId);

					$bAllowTwilio = $oTenant->TwilioAllow && $oTenant->TwilioAllowConfiguration;
				} else {
					// TODO
					$bAllowTwilio = true;
				}

				@header('Content-type: text/xml');
				$aResult = array('<?xml version="1.0" encoding="UTF-8"?>');
				$aResult[] = '<Response>';
				
				\CApi::Log($bAllowTwilio);
				
				if ($bAllowTwilio) {
					
					if ($bDirection) // inbound
					{
						if (!$sDigits)
						{
							$aResult[] = '<Gather timeout="10" numDigits="4">';
							$aResult[] = '<Say>Please enter the extension number or stay on the line to talk to an operator</Say>';
							$aResult[] = '</Gather>';
							$aResult[] = '<Say>You will be connected with an operator</Say>';
							$aResult[] = '<Dial><Client>TwilioAftId_'.$sTenantId.'_0</Client></Dial>';
						}
						else
						{
//							$aResult[] = '<Dial><Client>TwilioAftId_'.$sTenantId.'_'.$sDigits.'</Client></Dial>';
							$aResult[] = '<Dial><Client>TwilioAftId_'.$sDigits.'</Client></Dial>';
						}
					}
					else //Outbound
					{
						/* @var $oApiCapability \CApiCapabilityManager */
						$oApiCapability = \CApi::Manager('capability');

							/* @var $oApiIntegrator \CApiIntegratorManager */
						$oApiIntegrator = \CApi::Manager('integrator');

						$oAccount = $oApiIntegrator->GetLogginedDefaultAccount();
						\CApi::LogObject($oAccount);
						\CApi::Log($oApiCapability->IsVoiceSupported($oAccount));
						if ($oApiCapability->IsVoiceSupported($oAccount))
						{
							$sPhoneNumber = $this->oHttp->GetRequest('PhoneNumber');
							$aResult[] = '<Say>Call to phone number '.$sPhoneNumber.'</Say>';
							$aResult[] = '<Dial callerId="17064030887">'.$sPhoneNumber.'</Dial>';
						}
					}
				} else {
					$aResult[] = '<Say>This functionality doesn\'t allowed</Say>';
				}
					
				$aResult[] = '</Response>';
	
				$sResult = implode("\r\n", $aResult);
				
				\CApi::Log('twilio_end_xml');

			}
			else if ('plugins' === $sFirstPart)
			{
				$sType = !empty($aPaths[1]) ? trim($aPaths[1]) : '';
				if ('js' === $sType)
				{
					@header('Content-Type: application/javascript; charset=utf-8');
					$sResult = \CApi::Plugin()->CompileJs();
				}
			}
			else if ('demo' === $sFirstPart)
			{
				$sResult = $this->indexHTML();
			}
			else
			{
				\CApi::Plugin()->RunServiceHandle($sFirstPart, $aPaths);
			}
		}
		else
		{
			$sResult = $this->indexHTML();
		}

		// Output result
		echo $sResult;
	}
}
