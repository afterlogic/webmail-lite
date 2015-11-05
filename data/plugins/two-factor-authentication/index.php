<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class_exists('CApi') or die();
CApi::Inc('common.plugins.two-factor-auth');

include_once CApi::LibrariesPath() . 'PHPGangsta/GoogleAuthenticator.php';

class TwoFactorAuthenticationPlugin extends AApiTwoFactorAuthPlugin
{
    protected $logs = false;
    protected $discrepancy = 2;

    public static $setAccountIsLoggedIn = false;

    /**
     * @param string $sText
     */
    private function _writeLogs($sText)
    {
        if ($this->logs === true)
        {
            $this->Log($sText);
        }
    }

    /**
     * @param CApiPluginManager $oPluginManager
     */
    public function __construct(CApiPluginManager $oPluginManager)
    {
        parent::__construct('1.0', $oPluginManager);

        $this->AddHook('api-integrator-login-to-account-result', 'getUserApplicationKey');

        $this->AddJsonHook('AjaxVerifyToken', 'AjaxVerifyUserToken');
    }

    public function Init()
    {
        parent::Init();

        $this->SetI18N(true);

        $this->AddJsFile('js/include.js');
        $this->AddCssFile('css/style.css');

        $this->AddJsFile('js/VerifyTokenPopup.js');
        $this->AddTemplate('VerifyTokenPopup', 'templates/VerifyTokenPopup.html', 'Layout', 'Screens-Middle', 'popup');

        $this->AddJsFile('js/ValidatePasswordPopup.js');
        $this->AddTemplate('ValidatePasswordPopup', 'templates/ValidatePasswordPopup.html', 'Layout', 'Screens-Middle', 'popup');

        $this->AddJsonHook('AjaxValidatePassword', 'AjaxValidatePassword');

        $this->AddJsFile('js/CAuthenticationViewModel.js');
        $this->AddTemplate('AuthenticationTemplate', 'templates/AuthenticationTemplate.html');

        $this->AddJsonHook('AjaxTwoFactorOnRouteAuthenticationSettings', 'AjaxTwoFactorOnRouteAuthenticationSettings');
        $this->AddJsonHook('AjaxTwoFactorAuthenticationSave', 'AjaxTwoFactorAuthenticationSave');
        $this->AddJsonHook('AjaxTwoFactorAuthenticationSettings', 'AjaxTwoFactorAuthenticationSettings');

        $this->AddFontFile('afterlogic-two-factor-authentication.eot', 'css/fonts/afterlogic-two-factor-authentication.eot');

        $mConfig = \CApi::GetConf('plugins.two-factor-authentication.config', false);

        if ($mConfig)
        {
            $this->logs = $mConfig['logs'];
            $this->discrepancy = $mConfig['discrepancy'];
        }
    }

    /**
     * @param $oServer
     * @return mixed
     */
    public function AjaxTwoFactorOnRouteAuthenticationSettings($oServer)
    {
        $iAccountId = $oServer->GetDefaultAccount()->IdAccount;
        $oApiUsers = /* @var $oApiUsers \CApiUsersManager */
            \CApi::Manager('users');
        $oAccount = $oApiUsers->getAccountById($iAccountId);

        $sSecret = $this->getCode($oAccount);

        $aResult['Action'] = 'TwoFactorOnRouteAuthenticationSettings';

        $oException = $this->oApiTwofactorauth->GetLastException();

        if ($oException)
        {
            $aResult['Result'] = false;
            $aResult['ErrorMessage'] = $oException->getMessage();

            $this->_writeLogs($oException->getMessage() . '(' . $oException->getPrevious()->getMessage() . ')');

            return $aResult;
        }

        if ($sSecret)
        {
            $aResult['Result'] = true;
        }
        else
        {
            $aResult['Result'] = false;
        }

        return $aResult;
    }

    /**
     * @param $oServer
     * @return mixed
     */
    public function AjaxValidatePassword($oServer)
    {
        $iAccountId = $oServer->GetDefaultAccount()->IdAccount;
        $oApiUsers = /* @var $oApiUsers \CApiUsersManager */
            \CApi::Manager('users');
        $oAccount = $oApiUsers->getAccountById($iAccountId);

        $sPassword = trim(stripcslashes($oServer->getParamValue('Password', null)));
        $oWebMailApi = \CApi::Manager('integrator');

        $bStatus = $oWebMailApi->LoginToAccount(
            $oAccount->Email, $sPassword, $oAccount->Email
        );

        $aResult['Action'] = 'ValidatePassword';

        if ($bStatus)
        {
            $aResult['Result'] = true;
        }
        else
        {
            $aResult['Result'] = false;
        }

        return $aResult;
    }

    /**
     * @param $oServer
     * @return mixed
     */
    public function AjaxTwoFactorAuthenticationSettings ($oServer)
    {
        $bStatus = trim(stripcslashes($oServer->getParamValue('Enable', 'false'))) === 'true' ? true : false;

        $iAccountId = $oServer->GetDefaultAccount()->IdAccount;
        $oApiUsers = /* @var $oApiUsers \CApiUsersManager */
            \CApi::Manager('users');
        $oAccount = $oApiUsers->getAccountById($iAccountId);

        $aResult['Action'] = 'TwoFactorAuthenticationSettings';

        if ($bStatus === true)
        {
            $oGoogle = new PHPGangsta_GoogleAuthenticator();
            $sSecret = $this->getCode($oAccount) ? $this->getCode($oAccount) : $oGoogle->createSecret();

            $aResult['Result'] = array(
                'Code' => $sSecret,
                'QRcode' => $oGoogle->getQRCodeGoogleUrl($_SERVER['SERVER_NAME'], $sSecret),
                'Enabled' => $this->getCode($oAccount) ? true : false
            );
        }
        else
        {
            $this->removeDataValue($oAccount);

            $aResult['Result'] = false;
        }

        return $aResult;
    }

    /**
     * @param $oServer
     * @return mixed
     * @throws \ProjectCore\Exceptions\ClientException
     */
    public function AjaxTwoFactorAuthenticationSave($oServer)
    {
        $sPin = trim(stripcslashes($oServer->getParamValue('Pin', null)));
        $sSecret = str_replace(' ', '', trim(stripcslashes($oServer->getParamValue('Code', null))));

        $iAccountId = $oServer->GetDefaultAccount()->IdAccount;
        $oApiUsers = /* @var $oApiUsers \CApiUsersManager */
            \CApi::Manager('users');
        $oAccount = $oApiUsers->getAccountById($iAccountId);

        $oGoogle = new PHPGangsta_GoogleAuthenticator();
        $oStatus = $oGoogle->verifyCode($sSecret, $sPin, $this->discrepancy);

        $aResult['Action'] = 'TwoFactorAuthenticationSave';

        if ($oStatus === true)
        {
            $this->createDataValue($oAccount, 1, $sSecret, true);

            $aResult['Result'] = true;
        }
        else
        {
            $aResult['Result'] = false;
        }

        return $aResult;
    }

    /**
     * @param $sAction
     * @param $aResult
     */
    public function AjaxResponseResult($sAction, &$aResult)
    {
        if ($sAction === 'SystemLogin')
        {
            $aResult['ContinueAuth'] = true;
        }
    }

    /**
     * @param $oServer
     * @return mixed
     */
    public function AjaxVerifyUserToken($oServer)
    {
        $sEmail = trim(stripcslashes($oServer->getParamValue('Email', null)));
        $sCode = intval(trim(stripcslashes($oServer->getParamValue('Code', null))));
        $bSignMe = $oServer->getParamValue('SignMe') === 'true' ? true : false;
		$oSettings =& \CApi::GetSettings();
		
		if (\ELoginFormType::Login === (int) $oSettings->GetConf('WebMail/LoginFormType'))
		{
			$sIncLogin = trim(stripcslashes($oServer->getParamValue('Login', null)));
			$sAtDomain = trim($oSettings->GetConf('WebMail/LoginAtDomainValue'));
			$sEmail = \api_Utils::GetAccountNameFromEmail($sIncLogin).'@'.$sAtDomain;
		}

        try
		{
            $oApiUsers = /* @var $oApiUsers \CApiUsersManager */
                \CApi::Manager('users');
            $oAccount = $oApiUsers->getAccountByEmail($sEmail);

            $sDataValue = $this->getCode($oAccount);
            $oGoogle = new PHPGangsta_GoogleAuthenticator();
            $oStatus = $oGoogle->verifyCode($sDataValue, $sCode, $this->discrepancy);

            if ($oStatus)
            {
                $this->_writeLogs($sDataValue . ' is valid');

                $oApiIntegratorManager = /* @var $oApiIntegratorManager \CApiIntegratorManager */
                    \CApi::Manager('integrator');
                $oApiIntegratorManager->SetAccountAsLoggedIn($oAccount, $bSignMe);

                $aResult['Result'] = true;
            }
            else
            {
                $this->_writeLogs($sDataValue . ' is not valid');

                $aResult['Result'] = false;
                $aResult['ErrorMessage'] = $this->I18N('AUTHENTICATION_PLUGIN/WRONG_CODE');
            }

        } catch (Exception $oEx) {
            $aResult['Result'] = false;
            $aResult['ErrorMessage'] = $oEx->getMessage();
        }

        return $aResult;
    }

    /**
     * @param CAccount $oAccount
     */
    public function getUserApplicationKey(&$oAccount)
    {
        $iAccountExists = $this->isAccountExists($oAccount);

        if ($iAccountExists === false)
        {
            $this->AddHook('ajax.response-result', 'AjaxResponseResult');
            self::$setAccountIsLoggedIn = true;
        }
        else
        {
            self::$setAccountIsLoggedIn = false;

            $sDataValue = $this->getCode($oAccount);
            if (is_null($sDataValue) || !isset($sDataValue) || empty($sDataValue))
            {
                $this->AddHook('ajax.response-result', 'AjaxResponseResult');
            }
            else if (isset($sDataValue) && !is_null($sDataValue))
            {
                $this->_writeLogs('account id: ' . $oAccount->IdAccount . ' code: ' . $sDataValue);
            }
        }
    }

    /**
     * @param CAccount $oAccount
     * @return null | string
     */
    public function getCode($oAccount)
    {
        $sDataValue = null;

        /* @var $oApiManager \CApiTwofactorauthManager */
        $oApiManager = $this->getTwofactorauthManager();

        $oResult = $oAccount ? $oApiManager->getAccountById($oAccount->IdAccount, ETwofaType::AUTH_TYPE_GOOGLE) : null;

        if ($oResult)
        {
            $aResult = $oResult->ToArray();

            if (is_array($aResult) && isset($aResult['DataValue']))
            {
                $sDataValue = $aResult['DataValue'];
            }
        }

        return $sDataValue;
    }

    /**
     * @param CAccount | null $oAccount = null
     * @param int $iDataType = 0
     * @param string $sDataValue = ''
     * @param bool $bEnableUpdate = true
     * @return bool
     * @throws \ProjectCore\Exceptions\ClientException
     */
    public function createDataValue($oAccount = null, $iDataType = 0, $sDataValue = '', $bEnableUpdate = true)
    {
        /* @var $oApiManager \CApiTwofactorauthManager */
        $oApiManager = $this->getTwofactorauthManager();

        $iAccountId = $oAccount->IdAccount;
        $iAccountExists = $this->isAccountExists($oAccount);
        $bResponse = false;

        if ($iAccountExists === true)
        {
            if ($bEnableUpdate === false)
            {
                $this->_writeLogs('account is exists: ' . $iAccountId);
                throw new \ProjectCore\Exceptions\ClientException(\ProjectCore\Notifications::AccountExists);
            }
            else
            {
                $this->_writeLogs('update ' . $iAccountId);

                $oApiManager->updateAccount($oAccount, ETwofaType::AUTH_TYPE_GOOGLE, $iDataType, $sDataValue);
                $bResponse = true;
            }
        }
        else
        {
            $this->_writeLogs('insert ' . $iAccountId);

            $oApiManager->createAccount($oAccount, ETwofaType::AUTH_TYPE_GOOGLE, $iDataType, $sDataValue);
            $bResponse = true;
        }

        return $bResponse;
    }

    /**
     * @param CAccount | null $oAccount = null
     * @return bool
     */
    public function removeDataValue($oAccount = null)
    {
        if ($oAccount instanceof \CAccount)
        {
            /* @var $oApiManager \CApiTwofactorauthManager */
            $oApiManager = $this->getTwofactorauthManager();

            $iAccountId = $oAccount->IdAccount;
            $oApiManager->deleteAccountByAccountId($iAccountId);

            $this->_writeLogs('delete account_id: ' . $iAccountId . ' success');

            $bResponse = true;
        }
        else
        {
            $bResponse = false;
        }

        return $bResponse;
    }

    /**
     * @param CAccount $oAccount
     * @return bool
     */
    public function isAccountExists($oAccount)
    {
        $bResult = false;

        /* @var $oApiManager \CApiTwofactorauthManager */
        $oApiManager = $this->getTwofactorauthManager();

        if ($oAccount instanceof \CAccount)
        {
            $bResult = $oApiManager->isAccountExists(ETwofaType::AUTH_TYPE_GOOGLE, $oAccount->IdAccount);
        }

        return $bResult;
    }

    /**
     * @param ref $bResult
     */
    public function setAccountIsLoggedIn(&$bResult)
    {
        $bResult = self::$setAccountIsLoggedIn;
    }
}

return new TwoFactorAuthenticationPlugin($this);