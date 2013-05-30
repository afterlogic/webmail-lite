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
			if ('Ping' === $aPaths[0])
			{
				@header('Content-Type: text/plain; charset=utf-8');
				$sResult = 'Pong';
			}
			else if (('Ajax' === $aPaths[0]))
			{
				@ob_start();

				$aResponseItem = null;
				$sAction = $this->oHttp->GetPost('Action', null);
				try
				{
					\CApi::Log('AJAX: Action: '.$sAction);

					if ('AppData' !== $sAction && !$this->validateToken())
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
					if ($oException instanceof \ProjectSeven\Exceptions\ClientException &&
						\ProjectSeven\Notifications::AuthError === $oException->getCode())
					{
						$oApiIntegrator = /* @var $oApiIntegrator CApiIntegratorManager */ \CApi::Manager('integrator');

						$oApiIntegrator->SetLastErrorCode(\ProjectSeven\Notifications::AuthError);
						$oApiIntegrator->LogoutAccount();
					}

					$aResponseItem = $this->oActions->ExceptionResponse(
						null, empty($sAction) ? 'Unknown' : $sAction, $oException);
				}

				@header('Content-Type: application/json; charset=utf-8');

				$sResult = \ProjectSeven\Base\Utils::JsonEncode($aResponseItem);
//				\CApi::Log('AJAX: Response: '.$sResult);
			}
			else if ('Upload' === $aPaths[0])
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
									'Token' => $this->oHttp->GetPost('Token', '')
								));

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

					if (!is_array($aResponseItem))
					{
						throw new \ProjectSeven\Exceptions\ClientException(\ProjectSeven\Notifications::UnknownError);
					}
				}
				catch (\Exception $oException)
				{
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
			else if ('Profile' === $aPaths[0])
			{
				/* @var $oApiIosManager CApiIos2Manager */
				$oApiIosManager = \CApi::Manager('ios2');

				/* @var $oApiIntegrator CApiIntegratorManager */
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
					CApi::Location('./?IOS/Error');
				}
			}
			else if ('ios' === strtolower($aPaths[0]))
			{
				$sResult = file_get_contents(PSEVEN_APP_ROOT_PATH.'templates/Ios.html');

				/* @var $oApiIntegrator CApiIntegratorManager */
				$oApiIntegrator = \CApi::Manager('integrator');

				$iUserId = $oApiIntegrator->GetLogginedUserId();
				if (0 < $iUserId)
				{
					$oAccount = $oApiIntegrator->GetLogginedDefaultAccount();
					$bError = isset($aPaths[1]) && 'error' === strtolower($aPaths[1]); // TODO

					$sResult = strtr($sResult, array(
						'{{AppVersion}}' => PSEVEN_APP_VERSION,
						'{{IntegratorLinks}}' => $oApiIntegrator->BuildHeadersLink()
					));
				}
				else
				{
					CApi::Location('./');
				}
			}
			else if ('Raw' === $aPaths[0])
			{
				$sRawError = '';
				$sAction = empty($aPaths[1]) ? '' : $aPaths[1];
				try
				{
					if (!empty($sAction))
					{
						$sMethodName = 'Raw'.$sAction;
						if (method_exists($this->oActions, $sMethodName))
						{
							$this->oActions->SetActionParams(array(
								'AccountID' => empty($aPaths[2]) || '0' === (string) $aPaths[2] ? '' : $aPaths[2],
								'RawKey' => empty($aPaths[3]) ? '' : $aPaths[3]
							));

							if (!call_user_func(array($this->oActions, $sMethodName)))
							{
								$sRawError = 'False result.';
							}
						}
						else
						{
							$sRawError = 'Invalid action.';
						}
					}
					else
					{
						$sRawError = 'Empty action.';
					}
				}
				catch (\Exception $oException)
				{
					/* @var $oException \Exception */
//					$sRawError = $oException->getMessage();
//					$sRawError = $oException->getTraceAsString();
					$sRawError = 'Exception as result.';
				}

				$sResult = $sRawError;
			}
		}
		else
		{
			$sResult = file_get_contents(PSEVEN_APP_ROOT_PATH.'templates/Index.html');

			$oApiIntegrator = \CApi::Manager('integrator');

			$sResult = strtr($sResult, array(
				'{{AppVersion}}' => PSEVEN_APP_VERSION,
				'{{IntegratorDir}}' => $oApiIntegrator->GetAppDirValue(),
				'{{IntegratorLinks}}' => $oApiIntegrator->BuildHeadersLink(),
				'{{IntegratorBody}}' => $oApiIntegrator->BuildBody()
			));
		}

		// Output result
		echo $sResult;
	}
}
