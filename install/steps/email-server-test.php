<?php

// api
include_once WM_INSTALLER_PATH.'../libraries/afterlogic/api.php';

class CEmailservertestStep extends AInstallerStep
{
	public function DoPost()
	{
		if (isset($_POST['test_btn']))
		{
			$sMessage = '';
			$sHost = CPost::Get('txtHost', '');
			$bCheckSMTP = (bool) CPost::Get('chSMTP', false);
			$bCheckPOP3 = (bool) CPost::Get('chPOP3', false);
			$bCheckIMAP4 = (bool) CPost::Get('chIMAP4', false);

			$_SESSION['wm_install_server_test_host'] = $sHost;
			$_SESSION['wm_install_server_test_ch_smtp'] = $bCheckSMTP;
			$_SESSION['wm_install_server_test_ch_pop3'] = $bCheckPOP3;
			$_SESSION['wm_install_server_test_ch_imap4'] = $bCheckIMAP4;

			if (!empty($sHost))
			{
				if ($bCheckSMTP)
				{
					$iErrN = 0; $sErrorS = '';
					$sRes = @fsockopen($sHost, 25, $iErrN, $sErrorS, 5);
					if (is_resource($sRes))
					{
						@fclose($sRes);
						$sMessage .= '<div class="success">SMTP connection to port 25 successful, sending outgoing e-mail over SMTP should work.</div>';
					}
					else
					{
						$sMessage .= '<div class="error">SMTP connection to port 25 failed: '.$sErrorS.' (Error code: '.$iErrN.')</div>';
					}
				}
				
				if ($bCheckPOP3)
				{
					$iErrN = 0; $sErrorS = '';
					$sRes = @fsockopen($sHost, 110, $iErrN, $sErrorS, 5);
					if (is_resource($sRes))
					{
						@fclose($sRes);
						$sMessage .= '<div class="success">POP3 connection to port 110 successful, checking and downloading incoming e-mail over POP3 should work.</div>';
					}
					else
					{
						$sMessage .= '<div class="error">POP3 connection to port 110 failed: '.$sErrorS.' (Error code: '.$iErrN.')</div>';
					}
				}

				if ($bCheckIMAP4)
				{
					$iErrN = 0; $sErrorS = '';
					$sRes = @fsockopen($sHost, 143, $iErrN, $sErrorS, 5);
					if (is_resource($sRes))
					{
						@fclose($sRes);
						$sMessage .= '<div class="success">IMAP connection to port 143 successful, checking and downloading incoming e-mail over IMAP should work.</div>';
					}
					else
					{
						$sMessage .= '<div class="error">IMAP connection to port 143 failed: '.$sErrorS.' (Error code: '.$iErrN.')</div>';
					}
				}
			}
			else
			{
				$sMessage .= '<div class="error">Host is empty</div>';
			}

			if (!empty($sMessage))
			{
				$_SESSION['wm_install_server_test_message'] = $sMessage;
			}
		}
		else if (isset($_POST['next_btn']))
		{
			return true;
		}
		
		return false;
	}

	public function TemplateValues()
	{
		$sMessage = '';
		if (isset($_SESSION['wm_install_server_test_message']))
		{
			$sMessage = $_SESSION['wm_install_server_test_message'];
			unset($_SESSION['wm_install_server_test_message']);
		}

		if (!isset($_SESSION['wm_install_server_test_ch_smtp']))
		{
			$_SESSION['wm_install_server_test_ch_smtp'] = true;
		}

		return array(
			'Host' => isset($_SESSION['wm_install_server_test_host']) ? $_SESSION['wm_install_server_test_host'] : '127.0.0.1',
			'chSMTP' => (isset($_SESSION['wm_install_server_test_ch_smtp']) && $_SESSION['wm_install_server_test_ch_smtp'])
				? 'checked="checked"' : '',
			'chPOP3' => (isset($_SESSION['wm_install_server_test_ch_pop3']) && $_SESSION['wm_install_server_test_ch_pop3'])
				? 'checked="checked"' : '',
			'chIMAP' => (isset($_SESSION['wm_install_server_test_ch_imap4']) && $_SESSION['wm_install_server_test_ch_imap4'])
				? 'checked="checked"' : '',
			'FootMessage' => $sMessage,
			'LiteScript' => CApi::Manager('licensing') ? '' : 'window.__awm_lite = true;'
		);
	}
}