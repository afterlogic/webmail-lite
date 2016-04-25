<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CCompatibilityStep extends AInstallerStep
{
	/**
	 * @var array
	 */
	var $aCompatibility;

	function CCompatibilityStep()
	{
		include_once WM_INSTALLER_PATH . '/../libraries/afterlogic/api.php';		
		
		$this->aCompatibility = array();
		$this->Validate();
	}

	function DoPost()
	{
		if (isset($_POST['next_btn']))
		{
			return true;
		}
		else if (isset($_POST['retry_btn']))
		{
			header('Location: index.php');
			exit();
		}
		return false;
	}

	function Validate()
	{
		$this->aCompatibility['php.version'] = phpversion();
		$this->aCompatibility['php.version.valid'] = (int) (version_compare($this->aCompatibility['php.version'], '5.3.0') > -1);

		$this->aCompatibility['safe-mode'] = @ini_get('safe_mode');
		$this->aCompatibility['safe-mode.valid'] = is_numeric($this->aCompatibility['safe-mode'])
			? !((bool) $this->aCompatibility['safe-mode'])
			: ('off' === strtolower($this->aCompatibility['safe-mode']) || empty($this->aCompatibility['safe-mode']));

		$this->aCompatibility['mysql.valid'] = (int) extension_loaded('mysql');
		$this->aCompatibility['pdo.valid'] = (int)
			((bool) extension_loaded('pdo') && (bool) extension_loaded('pdo_mysql'));
		
		$this->aCompatibility['socket.valid'] = (int) function_exists('fsockopen');
		$this->aCompatibility['iconv.valid'] = (int) function_exists('iconv');
		$this->aCompatibility['curl.valid'] = (int) function_exists('curl_init');
		$this->aCompatibility['mbstring.valid'] = (int) function_exists('mb_detect_encoding');
		$this->aCompatibility['openssl.valid'] = (int) extension_loaded('openssl');
		$this->aCompatibility['xml.valid'] = (int) class_exists('DOMDocument');
		$this->aCompatibility['json.valid'] = (int) function_exists('json_decode');

		$this->aCompatibility['ini-get.valid'] = (int) function_exists('ini_get');
		$this->aCompatibility['ini-set.valid'] = (int) function_exists('ini_set');
		$this->aCompatibility['set-time-limit.valid'] = (int) function_exists('set_time_limit');

		$this->aCompatibility['session.valid'] = (int) (function_exists('session_start') && isset($_SESSION['checksessionindex']));

		$dataPath = 'data';
		if (@file_exists(WM_INSTALLER_PATH.'../inc_settings_path.php'))
		{
			include WM_INSTALLER_PATH.'../inc_settings_path.php';
		}

		$sRealDataPath = '';
		if (isset($dataPath) && null !== $dataPath)
		{
			$sRealDataPath = $this->getFullPath($dataPath, WM_INSTALLER_PATH.'..');
		}

		$this->aCompatibility['data.dir'] = $sRealDataPath;
		$this->aCompatibility['data.dir.valid'] = (int) (@is_dir($this->aCompatibility['data.dir']) && @is_writable($this->aCompatibility['data.dir']));

		$sTempPathName = '_must_be_deleted_'.md5(time());

		$this->aCompatibility['data.dir.create'] =
			(int) @mkdir($this->aCompatibility['data.dir'].'/'.$sTempPathName);
		$this->aCompatibility['data.file.create'] =
			(int) (bool) @fopen($this->aCompatibility['data.dir'].'/'.$sTempPathName.'/'.$sTempPathName.'.test', 'w+');
		$this->aCompatibility['data.file.delete'] =
			(int) (bool) @unlink($this->aCompatibility['data.dir'].'/'.$sTempPathName.'/'.$sTempPathName.'.test');
		$this->aCompatibility['data.dir.delete'] =
			(int) @rmdir($this->aCompatibility['data.dir'].'/'.$sTempPathName);

		$this->aCompatibility['settings.file'] = $this->aCompatibility['data.dir'].\api_Settings::XML_FILE_NAME;
		
		if (!@file_exists($this->aCompatibility['settings.file']))
		{
			$oSettings = new \api_Settings($this->aCompatibility['data.dir']);
		}
		
		$this->aCompatibility['settings.file.exist'] = (int) @file_exists($this->aCompatibility['settings.file']);
		$this->aCompatibility['settings.file.read'] = (int) @is_readable($this->aCompatibility['settings.file']);
		$this->aCompatibility['settings.file.write'] = (int) @is_writable($this->aCompatibility['settings.file']);

		$this->aCompatibility['compatibility'] = (int)
			$this->aCompatibility['php.version.valid'] &&
			$this->aCompatibility['safe-mode.valid'] &&
			$this->aCompatibility['pdo.valid'] &&
			$this->aCompatibility['iconv.valid'] &&
			$this->aCompatibility['mbstring.valid'] &&
			$this->aCompatibility['curl.valid'] &&
			$this->aCompatibility['json.valid'] &&
			$this->aCompatibility['xml.valid'] &&
			$this->aCompatibility['socket.valid'] &&
			$this->aCompatibility['session.valid'] &&
			$this->aCompatibility['data.dir.valid'] &&
			$this->aCompatibility['data.dir.create'] &&
			$this->aCompatibility['data.file.create'] &&
			$this->aCompatibility['data.file.delete'] &&
			$this->aCompatibility['data.dir.delete'] &&
			$this->aCompatibility['settings.file.exist'] &&
			$this->aCompatibility['settings.file.read'] &&
			$this->aCompatibility['settings.file.write'];
	}

	function TemplateValues()
	{
		return array(
			'PhpVersion' => ($this->aCompatibility['php.version.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue($this->aCompatibility['php.version'].' detected, 5.3.0 or above required.',
'You need to upgrade PHP engine installed on your server.
If it\'s a dedicated or your local server, you can download the latest version of PHP from its
<a href="http://php.net/downloads.php" target="_blank">official site</a> and install it yourself.
In case of a shared hosting, you need to ask your hosting provider to perform the upgrade.'),
			
			'SafeMode' => ($this->aCompatibility['safe-mode.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, safe_mode is enabled.',
'You need to <a href="http://php.net/manual/en/ini.sect.safe-mode.php" target="_blank">disable it in your php.ini</a>
or contact your hosting provider and ask to do this.'),

//			'MySQL' => ($this->aCompatibility['mysql.valid'])
//				? $this->getSuccessHtmlValue('OK')
//				: $this->getErrorHtmlValue('Error, PHP MySQL extension not detected.',
//'You need to install this PHP extension or enable it in php.ini file.'),
			
			'PDO MySQL' => ($this->aCompatibility['pdo.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, PHP PDO MySQL extension not detected.',
'You need to install this PHP extension or enable it in php.ini file.'),
			
			'iconv' => ($this->aCompatibility['iconv.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, iconv extension not detected.',
'You need to install this PHP extension or enable it in php.ini file.'),
			
			'curl' => ($this->aCompatibility['curl.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, curl extension not detected.',
'You need to install this PHP extension or enable it in php.ini file.'),

			'xml' => ($this->aCompatibility['xml.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, xml (DOM) extension not detected.',
'You need to install this PHP extension or enable it in php.ini file.'),

			'json' => ($this->aCompatibility['json.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, JSON extension not detected.',
'You need to install this PHP extension or enable it in php.ini file.'),

			'mb_string' => ($this->aCompatibility['mbstring.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, mb_string extension not detected.',
'You need to install this PHP extension or enable it in php.ini file.'),

			'Session' => ($this->aCompatibility['session.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, session support in PHP must be enabled.',
'To enable sessions, you should make sure the correct location is specified in session.save_path directive
of your php.ini file and PHP is allowed to write into that location.
You can learn more from <a href="http://php.net/manual/en/session.installation.php" target="_blank">official PHP documentation</a>.
In case of a shared hosting, you need to ask your hosting provider to do this.'),

			'Sockets' => ($this->aCompatibility['socket.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, creating network sockets must be enabled.', '
To enable sockets, you should remove fsockopen function from the list of prohibited functions in disable_functions directive of your php.ini file.
In case of a shared hosting, you need to ask your hosting provider to do this.'),

			'SSL' => ($this->aCompatibility['openssl.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getWarningHtmlValue('SSL connections (like Gmail) will not be available. ', '
You need to enable OpenSSL support in your PHP configuration and make sure OpenSSL library is installed on your server.
For instructions, please refer to the official PHP documentation. In case of a shared hosting,
you need to ask your hosting provider to enable OpenSSL support.
You may ignore this if you\'re not going to connect to SSL-only mail servers (like Gmail).'),

			'MemoryLimits' => ($this->aCompatibility['ini-get.valid'] && $this->aCompatibility['ini-set.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getWarningHtmlValue('Opening large e-mails may fail.', '
You need to enable setting memory limits in your PHP configuration, i.e. remove ini_get and ini_set functions
from the list of prohibited functions in disable_functions directive of your php.ini file.
In case of a shared hosting, you need to ask your hosting provider to do this.'),

			'ScriptTimeout' => ($this->aCompatibility['set-time-limit.valid'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getWarningHtmlValue('Downloading large mailboxes may fail.', '
To enable setting script timeout, you should remove set_time_limit function from the list
of prohibited functions in disable_functions directive of your php.ini file.
In case of a shared hosting, you need to ask your hosting provider to do this.'),

			'WebMailDataFolder' => ($this->aCompatibility['data.dir.valid'])
				? $this->getSuccessHtmlValue('Found')
				: $this->getErrorHtmlValue('Error, data folder path discovery failure.'),

			'CreatingDeletingFolders' => ($this->aCompatibility['data.dir.create'] && $this->aCompatibility['data.dir.delete'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, can\'t create/delete sub-folders in the data folder.', '
You need to grant read/write permission over data folder and all its contents to your web server user.
For instructions, please refer to this section of documentation and our
<a href="http://www.afterlogic.com/support/faq-webmail-pro-php#3.1" target="_blank">FAQ</a>.'),

			'CreatingDeletingFiles' => ($this->aCompatibility['data.file.create'] && $this->aCompatibility['data.file.delete'])
				? $this->getSuccessHtmlValue('OK')
				: $this->getErrorHtmlValue('Error, can\'t create/delete files in the data folder.', '
You need to grant read/write permission over data folder and all its contents to your web server user.
For instructions, please refer to this section of documentation and our
<a href="http://www.afterlogic.com/support/faq-webmail-pro-php#3.1" target="_blank">FAQ</a>.'),
			
			'WebMailSettingsFile' => ($this->aCompatibility['settings.file.exist'])
				? $this->getSuccessHtmlValue('Found')
				: $this->getErrorHtmlValue('Not Found, can\'t find "'.$this->aCompatibility['settings.file'].'" file.', '
Make sure you completely copied the data folder with all its contents from installation package.
By default, the data folder is webmail subfolder, and if it\'s not the case make sure its location matches one specified in inc_settings_path.php file.'),
			
			'ReadWriteSettingsFile' => ($this->aCompatibility['settings.file.read'] && $this->aCompatibility['settings.file.write'])
				? $this->getSuccessHtmlValue('OK / OK')
				: $this->getErrorHtmlValue('Not Found, can\'t find "'.$this->aCompatibility['settings.file'].'" file.', '
You should grant read/write permission over settings file to your web server user.
For instructions, please refer to this section of documentation and our
<a href="http://www.afterlogic.com/support/faq-webmail-pro-php#3.1" target="_blank">FAQ</a>.'),

			'Result' => ($this->aCompatibility['compatibility']) ?
					'The current server environment meets all the requirements. Click Next to proceed.' :
					'Please make sure that all the requirements are met and click Retry.',

			'ResultClassSuffix' => ($this->aCompatibility['compatibility']) ? '_ok' : '_error',
			'NextButtonName' => ($this->aCompatibility['compatibility']) ? 'next_btn' : 'retry_btn',
			'NextButtonValue' => ($this->aCompatibility['compatibility']) ? 'Next' : 'Retry'
		);
	}

	function getSuccessHtmlValue($sValue)
	{
		return '<span class="state_ok">'.$sValue.'</span>';
	}

	function getErrorHtmlValue($sError, $sErrorHelp = '')
	{
		$sResult = '<span class="state_error">'.$sError.'</span>';
		if (!empty($sErrorHelp))
		{
			$sResult .= '<span class="field_description">'.$sErrorHelp.'</span>';
		}
		return $sResult;
	}

	function getWarningHtmlValue($sVarning, $sVarningHelp = '')
	{
		$sResult = '<span class="state_warning"><img src="./images/alarm.png"> Not detected. <br />'.$sVarning.'</span>';
		if (!empty($sVarningHelp))
		{
			$sResult .= '<span class="field_description">'.$sVarningHelp.'</span>';
		}
		return $sResult;
	}
	
	function getFullPath($sPath, $sPrefix = null)
	{
		if ($sPrefix !== null && !@is_dir(realpath($sPath)))
		{
			if (!$this->isFullPath($sPath))
			{
				$sPath = $sPrefix.'/'.$sPath;
			}
		}

		if (@is_dir($sPath))
		{
			$sPath = rtrim(str_replace('\\', '/', realpath($sPath)), '/');
		}

		return $sPath;
	}

	/**
	 * @param string $sPpath
	 * @return bool
	 */
	function isFullPath($sPpath)
	{
		if (strlen($sPpath) > 0)
		{
			return (($sPpath{0} == '/' || $sPpath{0} == '\\') || (strlen($sPpath) > 1 && (defined('PHP_OS') && 'WIN' === strtoupper(substr(PHP_OS, 0, 3))) && $sPpath{1} == ':'));
		}
		return false;
	}
}
