<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

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
		$this->oTwilio = $this->oActions->GetTwilio();

		\CApi::Plugin()->SetActions($this->oActions);
		
//		\MailSo\Config::$FixIconvByMbstring = false;
		\MailSo\Config::$SystemLogger = \CApi::MailSoLogger();
		\MailSo\Config::$PreferStartTlsIfAutoDetect = !!\CApi::GetConf('labs.prefer-starttls', true);
	}

	/**
	 * @return \ProjectSeven\Service
	 */
	public static function NewInstance()
	{
		return new self();
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
					$sThreadAction = $this->oHttp->GetQuery('action');
					if (0 < strlen($sThread))
					{
						if ($oApiHelpdesk)
						{
							$iThreadID = $oApiHelpdesk->GetThreadIdByHash($mHelpdeskIdTenant, $sThread);
							if (0 < $iThreadID)
							{
								$oApiIntegrator->SetThreadIdFromRequest($iThreadID, $sThreadAction);
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
				@\header('Last-Modified: '.\gmdate('D, d M Y H:i:s').' GMT');
				
				if ((\CApi::GetConf('labs.cache-ctrl', true) && isset($_COOKIE['aft-cache-ctrl'])))
				{
					setcookie('aft-cache-ctrl', '1', time()-3600);
					if (function_exists('http_response_code'))
					{
						\http_response_code(304);
					}
					else
					{
						\header("HTTP/1.1 304 Not Modified", true, 304);
					}
					exit;
				}
				
				$sFrameOptions = \CApi::GetConf('labs.x-frame-options', '');
				if (0 < \strlen($sFrameOptions))
				{
					@\header('X-Frame-Options: '.$sFrameOptions);
				}

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
		
		// ------ Redirect to HTTPS
		$iLogginedUserId = $oApiIntegrator->GetLogginedUserId();
		$oSettings =& \CApi::GetSettings();
		$bRedirectToHttps = $oSettings->GetConf('Common/RedirectToHttps');
		
		$bHttps = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off") || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == "443"));
		if ($bRedirectToHttps && !$bHttps)
		{
			header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		}
		// ------

		/* @var $oApiCapability \CApiCapabilityManager */
		$oApiCapability = \CApi::Manager('capability');

		$sResult = '';

//		$sQuery = \trim(\trim(urldecode($this->oHttp->GetServer('QUERY_STRING', ''))), ' /');
		$sQuery = \trim(\trim($this->oHttp->GetServer('QUERY_STRING', '')), ' /');
		
		\CApi::Plugin()->RunQueryHandle($sQuery);
		
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
					if ('SystemGetAppData' !== $sAction &&
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
					
					$aAdditionalParams = null;
					if ($oException instanceof \ProjectSeven\Exceptions\ClientException)
					{
						$aAdditionalParams = $oException->GetObjectParams();
					}
					
					$aResponseItem = $this->oActions->ExceptionResponse(null, $sAction, $oException, $aAdditionalParams);
				}

				@header('Content-Type: application/json; charset=utf-8');

				\CApi::Plugin()->RunHook('ajax.response-result', array($sAction, &$aResponseItem));

				$sResult = \MailSo\Base\Utils::Php2js($aResponseItem, \CApi::MailSoLogger());
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
				@header('Content-Type: text/html; charset=utf-8');
//				if ('iframe' === $this->oHttp->GetPost('jua-post-type', ''))
//				{
//					@header('Content-Type: text/html; charset=utf-8');
//				}
//				else
//				{
//					@header('Content-Type: application/json; charset=utf-8');
//				}

				$sResult = \MailSo\Base\Utils::Php2js($aResponseItem);
			}
			else if ('speclogon' === $sFirstPart || 'speclogoff' === $sFirstPart)
			{
				\CApi::SpecifiedUserLogging('speclogon' === $sFirstPart);
				\CApi::Location('./');
			}
			else if ('sso' === $sFirstPart)
			{
				$oApiIntegratorManager = \CApi::Manager('integrator');

				try
				{
					$sHash = $this->oHttp->GetRequest('hash');
					if (!empty($sHash))
					{
						$sData = \CApi::Cacher()->Get('SSO:'.$sHash, true);
						$aData = \CApi::DecodeKeyValues($sData);

						if (!empty($aData['Email']) && isset($aData['Password'], $aData['Login']))
						{
							$oAccount = $oApiIntegratorManager->LoginToAccount($aData['Email'], $aData['Password'], $aData['Login']);
							if ($oAccount)
							{
								$oApiIntegratorManager->SetAccountAsLoggedIn($oAccount);
							}
						}
					}
					else
					{
						$oApiIntegratorManager->LogoutAccount();
					}
				}
				catch (\Exception $oExc)
				{
					\CApi::LogException($oExc);
				}
				
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
					$sIncMailServer = trim($oSettings->GetConf('WebMail/ExternalHostNameOfLocalImap'));
					$sOutMailServer = trim($oSettings->GetConf('WebMail/ExternalHostNameOfLocalSmtp'));

					if (0 < \strlen($sIncMailServer) && 0 < \strlen($sOutMailServer))
					{
						$iIncMailPort = 143;
						$iOutMailPort = 25;
						
						$aMatch = array();
						if (\preg_match('/:([\d]+)$/', $sIncMailServer, $aMatch) && !empty($aMatch[1]) && is_numeric($aMatch[1]))
						{
							$sIncMailServer = preg_replace('/:[\d]+$/', $sIncMailServer, '');
							$iIncMailPort = (int) $aMatch[1];
						}

						$aMatch = array();
						if (\preg_match('/:([\d]+)$/', $sOutMailServer, $aMatch) && !empty($aMatch[1]) && is_numeric($aMatch[1]))
						{
							$sOutMailServer = preg_replace('/:[\d]+$/', $sOutMailServer, '');
							$iOutMailPort = (int) $aMatch[1];
						}

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
'				<Port>'.$iIncMailPort.'</Port>',
'				<SSL>'.(993 === $iIncMailPort ? 'on' : 'off').'</SSL>',
'				<SPA>off</SPA>',
'				<AuthRequired>on</AuthRequired>',
'			</Protocol>',
'			<Protocol>',
'				<Type>SMTP</Type>',
'				<Server>'.$sOutMailServer.'</Server>',
'				<LoginName>'.$aEmailAddress[1].'</LoginName>',
'				<Port>'.$iOutMailPort.'</Port>',
'				<SSL>'.(465 === $iOutMailPort ? 'on' : 'off').'</SSL>',
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
				/* @var $oApiIosManager \CApiIosManager */
				$oApiIosManager = \CApi::Manager('ios');

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
						'{{IOS/HELLO}}' => \CApi::ClientI18N('IOS/HELLO', $oAccount),
						'{{IOS/DESC_P1}}' => \CApi::ClientI18N('IOS/DESC_P1', $oAccount),
						'{{IOS/DESC_P2}}' => \CApi::ClientI18N('IOS/DESC_P2', $oAccount),
						'{{IOS/DESC_P3}}' => \CApi::ClientI18N('IOS/DESC_P3', $oAccount),
						'{{IOS/DESC_P4}}' => \CApi::ClientI18N('IOS/DESC_P4', $oAccount),
						'{{IOS/DESC_P5}}' => \CApi::ClientI18N('IOS/DESC_P5', $oAccount),
						'{{IOS/DESC_P6}}' => \CApi::ClientI18N('IOS/DESC_P6', $oAccount),
						'{{IOS/DESC_P7}}' => \CApi::ClientI18N('IOS/DESC_P7', $oAccount),
						'{{IOS/DESC_BUTTON_YES}}' => \CApi::ClientI18N('IOS/DESC_BUTTON_YES', $oAccount),
						'{{IOS/DESC_BUTTON_SKIP}}' => \CApi::ClientI18N('IOS/DESC_BUTTON_SKIP', $oAccount),
						'{{IOS/DESC_BUTTON_OPEN}}' => \CApi::ClientI18N('IOS/DESC_BUTTON_OPEN', $oAccount),
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
			else if ('post' === $sFirstPart)
			{
				$sAction = $this->oHttp->GetPost('Action');
				try
				{
					if (!empty($sAction))
					{
						$sMethodName =  'Post'.$sAction;
						if (method_exists($this->oActions, $sMethodName) &&
							is_callable(array($this->oActions, $sMethodName)))
						{
							$this->oActions->SetActionParams($this->oHttp->GetPostAsArray());
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
						$oCalendar = $oApiCalendarManager->GetCalendar($oAccountOrganizer, $aInviteValues['calendarId']);							
						if ($oCalendar)
						{
							$oEvent = $oApiCalendarManager->GetEvent($oAccountOrganizer, $aInviteValues['calendarId'], $aInviteValues['eventId']);							
							if ($oEvent && is_array($oEvent) && 0 < count ($oEvent) && isset($oEvent[0]))
							{
								if (is_string($sResult))
								{
									$sResult = file_get_contents(PSEVEN_APP_ROOT_PATH.'templates/InviteExternal.html');

									$dt = new \DateTime();
									$dt->setTimestamp($oEvent[0]['startTS']);
									if (!$oEvent[0]['allDay'])
									{
										$sDefaultTimeZone = new \DateTimeZone($oAccountOrganizer->GetDefaultStrTimeZone());
										$dt->setTimezone($sDefaultTimeZone);
									}

									$sAction = $aInviteValues['action'];
									$sActionColor = 'green';
									$sActionText = '';
									switch (strtoupper($sAction))
									{
										case 'ACCEPTED':
											$sActionColor = 'green';
											$sActionText = 'Accepted';
											break;
										case 'DECLINED':
											$sActionColor = 'red';
											$sActionText = 'Declined';
											break;
										case 'TENTATIVE':
											$sActionColor = '#A0A0A0';
											$sActionText = 'Tentative';
											break;
									}

									$sDateFormat = 'm/d/Y';
									$sTimeFormat = 'h:i A';
									switch ($oAccountOrganizer->User->DefaultDateFormat)
									{
										case \EDateFormat::DDMMYYYY:
											$sDateFormat = 'd/m/Y';
											break;
										case \EDateFormat::DD_MONTH_YYYY:
											$sDateFormat = 'd/m/Y';
											break;
										default:
											$sDateFormat = 'm/d/Y';
											break;
									}
									switch ($oAccountOrganizer->User->DefaultTimeFormat)
									{
										case \ETimeFormat::F24:
											$sTimeFormat = 'H:i';
											break;
										case \EDateFormat::DD_MONTH_YYYY:
											\ETimeFormat::F12;
											$sTimeFormat = 'h:i A';
											break;
										default:
											$sTimeFormat = 'h:i A';
											break;
									}
									$sDateTime = $dt->format($sDateFormat.' '.$sTimeFormat);

									$mResult = array(
										'{{COLOR}}' => $oCalendar->Color,
										'{{EVENT_NAME}}' => $oEvent[0]['subject'],
										'{{EVENT_BEGIN}}' => ucfirst(\CApi::ClientI18N('REMINDERS/EVENT_BEGIN', $oAccountOrganizer)),
										'{{EVENT_DATE}}' => $sDateTime,
										'{{CALENDAR}}' => ucfirst(\CApi::ClientI18N('REMINDERS/CALENDAR', $oAccountOrganizer)),
										'{{CALENDAR_NAME}}' => $oCalendar->DisplayName,
										'{{EVENT_DESCRIPTION}}' => $oEvent[0]['description'],
										'{{EVENT_ACTION}}' => $sActionText,
										'{{ACTION_COLOR}}' => $sActionColor,
									);

									$sResult = strtr($sResult, $mResult);
								}
								else
								{
									\CApi::Log('Empty template.', \ELogLevel::Error);
								}
							}
							else
							{
								\CApi::Log('Event not found.', \ELogLevel::Error);
							}
						}
						else
						{
							\CApi::Log('Calendar not found.', \ELogLevel::Error);
						}
						$sAttendee = $aInviteValues['attendee'];
						if (!empty($sAttendee))
						{
							$oApiCalendarManager->UpdateAppointment($oAccountOrganizer, $aInviteValues['calendarId'], $aInviteValues['eventId'], $sAttendee, $aInviteValues['action']);
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
				$sResult = $this->oTwilio->getTwiML($aPaths, $this->oHttp);
			}
			else if ('plugins' === $sFirstPart)
			{
				$sType = !empty($aPaths[1]) ? trim($aPaths[1]) : '';
				if ('js' === $sType)
				{
					@header('Content-Type: application/javascript; charset=utf-8');
					$sResult = \CApi::Plugin()->CompileJs();
				}
				else if ('images' === $sType)
				{
					if (!empty($aPaths[2]) && !empty($aPaths[3]))
					{
						$oPlugin = \CApi::Plugin()->GetPluginByName($aPaths[2]);
						if ($oPlugin)
						{
							echo $oPlugin->GetImage($aPaths[3]);exit;
						}
					}
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
					try
					{
						$oAccount = $oApiIntegrator->LoginToAccount($sEmail, $sPassword, $sLogin);
					}
					catch (\Exception $oException)
					{
						$iErrorCode = \ProjectSeven\Notifications::UnknownError;
						if ($oException instanceof \CApiManagerException)
						{
							switch ($oException->getCode())
							{
								case \Errs::WebMailManager_AccountDisabled:
								case \Errs::WebMailManager_AccountWebmailDisabled:
									$iErrorCode = \ProjectSeven\Notifications::AuthError;
									break;
								case \Errs::UserManager_AccountAuthenticationFailed:
								case \Errs::WebMailManager_AccountAuthentication:
								case \Errs::WebMailManager_NewUserRegistrationDisabled:
								case \Errs::WebMailManager_AccountCreateOnLogin:
								case \Errs::Mail_AccountAuthentication:
								case \Errs::Mail_AccountLoginFailed:
									$iErrorCode = \ProjectSeven\Notifications::AuthError;
									break;
								case \Errs::UserManager_AccountConnectToMailServerFailed:
								case \Errs::WebMailManager_AccountConnectToMailServerFailed:
								case \Errs::Mail_AccountConnectToMailServerFailed:
									$iErrorCode = \ProjectSeven\Notifications::MailServerError;
									break;
								case \Errs::UserManager_LicenseKeyInvalid:
								case \Errs::UserManager_AccountCreateUserLimitReached:
								case \Errs::UserManager_LicenseKeyIsOutdated:
								case \Errs::TenantsManager_AccountCreateUserLimitReached:
									$iErrorCode = \ProjectSeven\Notifications::LicenseProblem;
									break;
								case \Errs::Db_ExceptionError:
									$iErrorCode = \ProjectSeven\Notifications::DataBaseError;
									break;
							}
						}
						$sReditectUrl = \CApi::GetConf('labs.post-login-error-redirect-url', './');
						\CApi::Location($sReditectUrl . '?error=' . $iErrorCode);
						exit;
					}

					if ($oAccount instanceof \CAccount)
					{
						$oApiIntegrator->SetAccountAsLoggedIn($oAccount);
					}
				}

				\CApi::Location('./');
			}
			else if ('mobile' === $sFirstPart)
			{
				if ($oApiIntegrator && $oApiCapability && $oApiCapability->IsNotLite())
				{
					$oApiIntegrator->SetMobile(true);
				}

				\CApi::Location('./');
			}
			else
			{
				@ob_start();
				\CApi::Plugin()->RunServiceHandle($sFirstPart, $aPaths);
				$sResult = @ob_get_clean();
				
				if (0 === strlen($sResult))
				{
					$sResult = $this->getIndexHTML();
				}
			}
		}
		else
		{
			$sResult = $this->getIndexHTML();
		}

		// Output result
		echo $sResult;
	}

	/**
	 * @return string
	 */
	private function getIndexHTML()
	{
		/* @var $oApiIntegrator \CApiIntegratorManager */
		$oApiIntegrator = \CApi::Manager('integrator');

		/* @var $oApiCapability \CApiCapabilityManager */
		$oApiCapability = \CApi::Manager('capability');
		
		if ($oApiIntegrator && $oApiCapability && $oApiCapability->IsNotLite() && 1 === $oApiIntegrator->IsMobile())
		{
			return $this->indexHTML(false, '', '', '', true);
		}
		else
		{
			return $this->indexHTML();
		}
	}
}
