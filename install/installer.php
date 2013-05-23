<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

defined('WM_INSTALLER_PATH') || define('WM_INSTALLER_PATH', (dirname(__FILE__).'/'));

class CInstaller
{
	/**
	 * @var string
	 */
	var $_sState;

	/**
	 * @var array
	 */
	var $_aMenu;

	/**
	 * @var array
	 */
	var $_aTemplateCache;

	/**
	 * @var array
	 */
	var $_aSteps = array(
		'compatibility' => 'Compatibility Test',
		'license' => 'License Agreement',
		'license-key' => 'License Key',
		'db' => 'Database Settings',
		'dav' => 'Mobile Settings',
		'admin-panel' => 'Admin Panel Settings',
		'email-server-test' => 'E-mail Server Test',
		'completed' => 'Completed',
	);

	function CInstaller()
	{
		$sName = 'WM_INSTALLER';
		if (@session_name() !== $sName)
		{
			if (@session_name())
			{
				@session_write_close();
				if (isset($GLOBALS['PROD_NAME']) && false !== strpos($GLOBALS['PROD_NAME'], 'Plesk')) // Plesk
				{
					@session_module_name('files');
				}
			}

			@session_set_cookie_params(0);
			@session_name($sName);
			@session_start();
		}

		$this->_sState = isset($_GET['step']) ? $_GET['step'] : 'index';

		$this->_aTemplateCache = array();
		$this->_aMenu = array(
			'compatibility', 'license', 'license-key', 'db',
			'dav', 'admin-panel', 'email-server-test', 'completed'
		);

		if (@file_exists(WM_INSTALLER_PATH.'../libraries/afterlogic/common/lite.php'))
		{
			// Lite version steps
			unset($this->_aMenu[2]); // 'license-key'
			unset($this->_aMenu[4]); // 'dav'
			unset($this->_aSteps['license-key']);
			unset($this->_aSteps['dav']);
		}
		else if (version_compare(phpversion(), '5.3.0') <= -1)
		{
			unset($this->_aMenu[4]); // 'dav'
			unset($this->_aSteps['dav']);
		}
	}

	function Run()
	{
		if (isset($_GET['post']))
		{
			$this->Post();
			exit();
		}

		if ('index' === $this->_sState)
		{
			$_SESSION['checksessionindex'] = true;
			header('Location: index.php?step=compatibility');
			exit();
		}

		$sState = in_array($this->_sState, $this->_aMenu) ? $this->_sState : 'compatibility';

		$sMenu = '';
		$sBlockedClass = '';
		$iStepNum = $iStepCount = 0;

		foreach ($this->_aMenu as $sMenuType)
		{
			if (isset($this->_aSteps[$sMenuType]))
			{
				$iStepCount++;
				$sUrl = ('compatibility' === $sMenuType) ? 'index.php' : 'index.php?step='.$sMenuType;
				$sMenu .= $this->template('nav-line', array(
					'Title' =>
						empty($sBlockedClass)
							? '<a href="'.$sUrl.'">'.$this->_aSteps[$sMenuType].'</a>'
							: $this->_aSteps[$sMenuType],

					'Class' => $sBlockedClass,
				));
			}

			if ($sState === $sMenuType)
			{
				$sBlockedClass = 'blocked';
				if (0 === $iStepNum)
				{
					$iStepNum = $iStepCount;
				}
			}
		}

		$oStepObject = null;
		if (@file_exists(WM_INSTALLER_PATH.'steps/'.$sState.'.php'))
		{
			include_once WM_INSTALLER_PATH.'steps/'.$sState.'.php';
			$oCurrentStateStepClass = 'C'.ucfirst(preg_replace('/[^a-z]/', '', $sState)).'Step';
			$oStepObject = new $oCurrentStateStepClass;
		}

		$sMain = '';
		$sSrc  = './images/wmp-php-install-logo.png';
		if (null !== $oStepObject)
		{
			$sMain = '<div>Step '.$iStepNum.' of '.$iStepCount.'</div>';
			$sMain .= '<input type="hidden" name="state" value="'.$sState.'" />';
			$sMain .= $this->template($sState, $oStepObject->TemplateValues());

			$sKey = @file_exists(WM_INSTALLER_PATH.'KEY') ? @file_get_contents(WM_INSTALLER_PATH.'KEY') : '';
			$sSrc = 'http://afterlogic.com/img/wmp-php-install-logo.png?key='.
				$sKey.'&step='.$this->getStepNum($sState).'&rnd='.((int) rand(100000, 999999));
		}
		else
		{
			$sMain = $this->template('error-step');
		}

//		$sSrc  = './images/wmp-php-install-logo.png';
		$sOut = $this->template('main', array(
			'TopImgSrc' => $sSrc,
			'Menu' => $sMenu,
			'Main' => $sMain
		));

		echo $sOut;
	}

	function Post()
	{
		$sState = $_POST['state'];

		if (isset($_POST['back_btn']))
		{
			header('Location: '.'index.php?step='.$this->getBackStep($sState));
		}
		else
		{
			$oStepObject = null;
			if (@file_exists(WM_INSTALLER_PATH.'steps/'.$sState.'.php'))
			{
				include_once WM_INSTALLER_PATH.'steps/'.$sState.'.php';
				$oCurrentStateStepClass = 'C'.ucfirst(preg_replace('/[^a-z]/', '', $sState)).'Step';
				$oStepObject = new $oCurrentStateStepClass;

				$sUrl = 'index.php?step='.$sState;
				if ($oStepObject->DoPost())
				{
					$sUrl = 'index.php?step='.$this->getNextStep($sState);
				}

				header('Location: '.$sUrl);
			}
			else
			{
				echo 'State error';
			}
		}
	}

	function getStepNum($sState)
	{
		$iResult = $iCount = 0;
		foreach ($this->_aMenu as $sMenuType)
		{
			if ($sState === $sMenuType)
			{
				$iResult = $iCount;
				break;
			}

			$iCount++;
		}

		return $iResult;
	}

	function getBackStep($sCurrentStep)
	{
		$sResult = false;
		foreach ($this->_aMenu as $sMenuType)
		{
			if ($sCurrentStep === $sMenuType)
			{
				break;
			}

			$sResult = $sMenuType;
		}

		return $sResult;
	}

	function getNextStep($sCurrentStep)
	{
		$sResult = false;
		$bGetNext = false;
		foreach ($this->_aMenu as $sMenuType)
		{
			if ($bGetNext)
			{
				$sResult = $sMenuType;
				$bGetNext = false;
				break;
			}

			if ($sCurrentStep === $sMenuType)
			{
				$bGetNext = true;
			}
		}

		if ($bGetNext && false === $sResult)
		{
			$sResult = $sCurrentStep;
		}

		return $sResult;
	}

	function template($sName, $aValues = array())
	{
		$sResult = false;
		if (isset($this->_aTemplateCache[$sName]))
		{
			$sResult = $this->_aTemplateCache[$sName];
			$aKeys = array_keys($aValues);
			$aKeys = array_map('UpdateTemplateNames', $aKeys);
			$sResult = str_replace($aKeys, array_values($aValues), $sResult);
		}
		if (@file_exists(WM_INSTALLER_PATH.'templates/'.$sName.'.html'))
		{
			$sResult = @file_get_contents(WM_INSTALLER_PATH.'templates/'.$sName.'.html');
			if (false !== $sResult)
			{
				$this->_aTemplateCache[$sName] = $sResult;
				$aKeys = array_keys($aValues);
				$aKeys = array_map('UpdateTemplateNames', $aKeys);
				$sResult = str_replace($aKeys, array_values($aValues), $sResult);
			}
		}

		return $sResult;
	}
}

function UpdateTemplateNames($sName)
{
	return '{{'.$sName.'}}';
}

class AInstallerStep
{
	function TemplateValues()
	{
		return array();
	}
}