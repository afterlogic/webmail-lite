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

	protected $oTwilio;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		$this->oHttp = \MailSo\Base\Http::NewInstance();
		$this->oActions = Actions::NewInstance();
		$this->oActions->SetHttp($this->oHttp);
		$this->oTwilio = \api_Twilio::NewInstance();

		\CApi::Plugin()->SetActions($this->oActions);
		
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
	 * @param string $sFileStoragePubHash = ''
	 * @param bool $bMobile = false
	 * @return string
	 */
	private function indexHTML($bHelpdesk = false, $sHelpdeskHash = '', $sCalendarPubHash = '', $sFileStoragePubHash = '', $bMobile = false)
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

						$oApiIntegrator->SkipMobileCheck();
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
				@\header('Content-Type: text/html; charset=utf-8', true);
				@\header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
				@\header('Last-Modified: '.\gmdate('D, d M Y H:i:s').' GMT');
				@\header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
				@\header('Cache-Control: post-check=0, pre-check=0', false);
				@\header('Pragma: no-cache');

				$sResult = strtr($sResult, array(
					'{{AppVersion}}' => PSEVEN_APP_VERSION,
					'{{IntegratorDir}}' => $oApiIntegrator->GetAppDirValue(),
					'{{IntegratorLinks}}' => $oApiIntegrator->BuildHeadersLink('.', $bHelpdesk,
						$mHelpdeskIdTenant, $sHelpdeskHash, $sCalendarPubHash, $sFileStoragePubHash, $bMobile),
					'{{IntegratorBody}}' => $oApiIntegrator->BuildBody('.', $bHelpdesk,
						$mHelpdeskIdTenant, $sHelpdeskHash, $sCalendarPubHash, $sFileStoragePubHash, $bMobile)
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

		$sPathInfo = \trim(\trim($this->oHttp->GetServer('PATH_INFO', '')), ' /');
		if (!empty($sPathInfo))
		{
			if ('dav' === \substr($sPathInfo, 0, 3))
			{
				$this->oActions->PathInfoDav();
				return '';
			}
		}

		/* @var $oApiIntegrator \CApiIntegratorManager */
		$oApiIntegrator = \CApi::Manager('integrator');

		$sResult = '';

		$sQuery = \trim(\trim($this->oHttp->GetServer('QUERY_STRING', '')), ' /');
		$iPos = \strpos($sQuery, '&');
		if (0 < $iPos)
		{
			$sQuery = \substr($sQuery, 0, $iPos);
		}

		$aPaths = explode('/', $sQuery);
		if (0 < count($aPaths) && !empty($aPaths[0]))
		{
			$sFirstPart = strtolower($aPaths[0]);
			
			if ('ping' === $sFirstPart)
			{
				@header('Content-Type: text/plain; charset=utf-8');
				$sResult = 'Pong';
			}
			else if (('ajax' === $sFirstPart))
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
							$this->oActions->SetActionParams($this->oHttp->GetPostAsArray());
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

					\CApi::LogException($oException);

					$sAction = empty($sAction) ? 'Unknown' : $sAction;
					$aResponseItem = $this->oActions->ExceptionResponse(null, $sAction, $oException);
				}

				@header('Content-Type: application/json; charset=utf-8');

				\CApi::Plugin()->RunHook('ajax.response-result', array($sAction, &$aResponseItem));

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
			else if ('autodiscover' === $sFirstPart)
			{
				$oSettings =& \CApi::GetSettings();

				$sInput = \file_get_contents('php://input');
//$sInput = '<?'.'xml version="1.0" encoding="utf-8"?'.'><Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/requestschema/2006"><Request><EMailAddress>test@afterlogic.com</EMailAddress><AcceptableResponseSchema>http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a</AcceptableResponseSchema></Request></Autodiscover>';

				\CApi::Log('#autodiscover:');
				\CApi::LogObject($sInput);

				$aMatches = array();
				$aEmailAddress = array();
				\preg_match("/\<AcceptableResponseSchema\>(.*?)\<\/AcceptableResponseSchema\>/i", $sInput, $aMatches);
				\preg_match("/\<EMailAddress\>(.*?)\<\/EMailAddress\>/", $sInput, $aEmailAddress);
				if (!empty($aMatches[1]) && !empty($aEmailAddress[1]))
				{
					$sIncMailServer = $oSettings->GetConf('WebMail/ExternalHostNameOfLocalImap');
					$sOutMailServer = $oSettings->GetConf('WebMail/ExternalHostNameOfLocalSmtp');

					if (0 < \strlen($sIncMailServer) && 0 < \strlen($sOutMailServer))
					{
							$sResult = \implode("\n", array(
'<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">',
'	<Response xmlns="'.$aMatches[1].'">',
'		<Account>',
'			<AccountType>email</AccountType>',
'			<Action>settings</Action>',
'			<Protocol>',
'				<Type>IMAP</Type>',
'				<Server>'.$sIncMailServer.'</Server>',
'				<LoginName>'.$aEmailAddress[1].'</LoginName>',
'				<Port>143</Port>',
'				<SSL>off</SSL>',
'				<SPA>off</SPA>',
'				<AuthRequired>on</AuthRequired>',
'			</Protocol>',
'			<Protocol>',
'				<Type>SMTP</Type>',
'				<Server>'.$sOutMailServer.'</Server>',
'				<LoginName>'.$aEmailAddress[1].'</LoginName>',
'				<Port>25</Port>',
'				<SSL>off</SSL>',
'				<SPA>off</SPA>',
'				<AuthRequired>on</AuthRequired>',
'			</Protocol>',
'		</Account>',
'	</Response>',
'</Autodiscover>'));
					}
				}

				if (empty($sResult))
				{
					$usec = $sec = 0;
					list($usec, $sec) = \explode(' ', microtime());
					$sResult = \implode("\n", array('<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">',
(empty($aMatches[1]) ?
'	<Response>' :
'	<Response xmlns="'.$aMatches[1].'">'
),
'		<Error Time="'.\gmdate('H:i:s', $sec).\substr($usec, 0, \strlen($usec) - 2).'" Id="2477272013">',
'			<ErrorCode>600</ErrorCode>',
'			<Message>Invalid Request</Message>',
'			<DebugData />',
'		</Error>',
'	</Response>',
'</Autodiscover>'));
				}

				header('Content-Type: text/xml');
				$sResult = '<'.'?xml version="1.0" encoding="utf-8"?'.'>'."\n".$sResult;

				\CApi::Log('');
				\CApi::Log($sResult);
			}
			else if ('profile' === $sFirstPart)
			{
				/* @var $oApiIosManager \CApiIos2Manager */
				$oApiIosManager = \CApi::Manager('ios2');

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
			else if ($this->oHttp->HasQuery('facebook'))
			{
				\api_Social::Facebook();
			}
			else if ($this->oHttp->HasQuery('google'))
			{
				\api_Social::Google();
			}
			else if ($this->oHttp->HasQuery('twitter'))
			{
				\api_Social::Twitter();
			}
			else if ($this->oHttp->HasQuery('helpdesk'))
			{
				$sResult = $this->indexHTML(true, $this->oHttp->GetQuery('helpdesk'));
			}
			else if ($this->oHttp->HasQuery('invite'))
			{
				$aInviteValues = \CApi::DecodeKeyValues($this->oHttp->GetQuery('invite'));
				
				$oApiUsersManager = \CApi::Manager('users');
				$oApiCalendarManager = \CApi::Manager('calendar');
				if (isset($aInviteValues['organizer']))
				{
					$oAccountOrganizer = $oApiUsersManager->GetAccountOnLogin($aInviteValues['organizer']);
					if (isset($oAccountOrganizer, $aInviteValues['attendee'], $aInviteValues['calendarId'], $aInviteValues['eventId'], $aInviteValues['action']))
					{
						$oAccountAttendee = $oApiUsersManager->GetAccountOnLogin($aInviteValues['attendee']);
						if ($oAccountAttendee)
						{
							$oApiCalendarManager->UpdateAppointmentExt($oAccountOrganizer, $oAccountAttendee, $aInviteValues['calendarId'], $aInviteValues['eventId'], $aInviteValues['action']);
						}
					}
				}
			}
			else if ($this->oHttp->HasQuery('calendar-pub') && 0 < strlen($this->oHttp->GetQuery('calendar-pub')))
			{
				$sResult = $this->indexHTML(false, '', $this->oHttp->GetQuery('calendar-pub'));
			}
			else if ($this->oHttp->HasQuery('files-pub') && 0 < strlen($this->oHttp->GetQuery('files-pub')))
			{
				$sResult = $this->indexHTML(false, '', '', $this->oHttp->GetQuery('files-pub'));
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
				$sResult = $this->oTwilio->Init($aPaths, $this->oHttp);
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
			else if ('postlogin' === $sFirstPart && \CApi::GetConf('labs.allow-post-login', false))
			{
				$oSettings =& \CApi::GetSettings();

				$sEmail = trim((string) $this->oHttp->GetRequest('Email', ''));
				$sLogin = (string) $this->oHttp->GetRequest('Login', '');
				$sPassword = (string) $this->oHttp->GetRequest('Password', '');

				$sAtDomain = trim($oSettings->GetConf('WebMail/LoginAtDomainValue'));
				if (\ELoginFormType::Login === (int) $oSettings->GetConf('WebMail/LoginFormType') && 0 < strlen($sAtDomain))
				{
					$sEmail = \api_Utils::GetAccountNameFromEmail($sLogin).'@'.$sAtDomain;
					$sLogin = $sEmail;
				}

				if (0 !== strlen($sPassword) && 0 !== strlen($sEmail.$sLogin))
				{
					$oAccount = $oApiIntegrator->LoginToAccount(
						$sEmail, $sPassword, $sLogin
					);

					if ($oAccount instanceof \CAccount)
					{
						$oApiIntegrator->SetAccountAsLoggedIn($oAccount);
					}
				}

				\CApi::Location('./');
			}
			else if ('mobile' === $sFirstPart)
			{
				if ($oApiIntegrator)
				{
					$oApiIntegrator->SetMobile(true);
				}

				\CApi::Location('./');
			}
			else if ('demo' === $sFirstPart)
			{
				if ($oApiIntegrator && 1 === $oApiIntegrator->IsMobile())
				{
					$sResult = $this->indexHTML(false, '', '', '', true);
				}
				else
				{
					$sResult = $this->indexHTML();
				}
			}
			else
			{
				\CApi::Plugin()->RunServiceHandle($sFirstPart, $aPaths);
			}
		}
		else
		{
			if ($oApiIntegrator && 1 === $oApiIntegrator->IsMobile())
			{
				$sResult = $this->indexHTML(false, '', '', '', true);
			}
			else
			{
				$sResult = $this->indexHTML();
			}
		}

		// Output result
		echo $sResult;
	}
}
