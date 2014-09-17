<?php

// api
include_once WM_INSTALLER_PATH.'../libraries/afterlogic/api.php';

class CDbStep extends AInstallerStep
{
	/**
	 * @var api_Settings
	 */
	protected $oSettings;

	public function __construct()
	{
		$this->oSettings =& CApi::GetSettings();
	}

	protected function initDbSettings()
	{
		$this->oSettings->SetConf('Common/DBType', 
			'PostgreSQL' === CPost::Get('chSqlType') ? EDbType::PostgreSQL : EDbType::MySQL);
		
		if (CPost::Has('txtSqlLogin'))
		{
			$this->oSettings->SetConf('Common/DBLogin', CPost::Get('txtSqlLogin'));
		}
		if (CPost::Has('txtSqlPassword') &&
			API_DUMMY !== (string) CPost::Get('txtSqlPassword'))
		{
			$this->oSettings->SetConf('Common/DBPassword', CPost::Get('txtSqlPassword'));
		}
		if (CPost::Has('txtSqlName'))
		{
			$this->oSettings->SetConf('Common/DBName', CPost::Get('txtSqlName'));
		}
		if (CPost::Has('txtSqlSrc'))
		{
			$this->oSettings->SetConf('Common/DBHost', CPost::Get('txtSqlSrc'));
		}
		if (CPost::Has('prefixString'))
		{
			$this->oSettings->SetConf('Common/DBPrefix', CPost::Get('prefixString'));
		}

		$this->oSettings->SaveToXml();
	}
	
	public function DoPost()
	{
		if (isset($_POST['test_btn']))
		{
			$this->initDbSettings();

			$sError = $sMessage = '';
			CDbCreator::ClearStatic();

			$aConnections =& CDbCreator::CreateConnector($this->oSettings);
			$oConnect = $aConnections[0];
			if ($oConnect)
			{
				$sError = 'Failed to connect.';
				try
				{
					if ($oConnect->Connect())
					{
						$sMessage = 'Connected successfully.';
						$sError = '';
					}
				}
				catch (CApiDbException $oException)
				{
					$sError .=
						"\r\n".$oException->getMessage().' ('.((int) $oException->getCode()).')';
				}
			}
			else
			{
				$sError = 'Failed to connect.';
			}

			if (!empty($sError))
			{
				$_SESSION['wm_install_db_test_error'] = $sError;
			}
			else if (!empty($sMessage))
			{
				$_SESSION['wm_install_db_test_message'] = $sMessage;
			}
		}
		else if (isset($_POST['create_db_btn']))
		{
			$this->initDbSettings();

			$sError = '';

			/* @var $oApiDbManager CApiDbManager */
			$oApiDbManager = CApi::Manager('db');
			
			if ($oApiDbManager->TryToCreateDatabase($sError))
			{
				$_SESSION['wm_install_db_name_create_message'] = 'Database created successfully.';
			}
			else
			{
				$_SESSION['wm_install_db_name_create_error'] = $sError;
			}
		}
		else if (isset($_POST['next_btn']))
		{
			$this->initDbSettings();

			$bResult = true;
			if (isset($_POST['chNotCreate']) && 1 === (int) $_POST['chNotCreate'])
			{
				/* @var $oApiDbManager CApiDbManager */
				$oApiDbManager = CApi::Manager('db');
				if ($oApiDbManager->AUsersTableExists())
				{
					$_SESSION['wm_install_db_foot_error'] = 'The data tables already exist. To proceed, specify another prefix or delete the existing tables.';
					$bResult = false;
				}
				else
				{
					$bResult = $oApiDbManager->SyncTables();
					if (!$bResult)
					{
						$_SESSION['wm_install_db_foot_error'] = $oApiDbManager->GetLastErrorMessage();
					}
				}
			}

			$_SESSION['wm-install-create-db'] = true;
			return $bResult;
		}
		
		return false;
	}

	public function TemplateValues()
	{
		$sTestDbText = '';
		if (isset($_SESSION['wm_install_db_test_error']))
		{
			$sTestDbText = '<span class="error">'.$_SESSION['wm_install_db_test_error'].'</span>';
			unset($_SESSION['wm_install_db_test_error']);
		}
		else if (isset($_SESSION['wm_install_db_test_message']))
		{
			$sTestDbText = '<span class="success">'.$_SESSION['wm_install_db_test_message'].'</span>';
			unset($_SESSION['wm_install_db_test_message']);
		}

		$sCreateDBNameText = '';
		if (isset($_SESSION['wm_install_db_name_create_error']))
		{
			$sCreateDBNameText = '<span class="error">'.$_SESSION['wm_install_db_name_create_error'].'</span>';
			unset($_SESSION['wm_install_db_name_create_error']);
		}
		else if (isset($_SESSION['wm_install_db_name_create_message']))
		{
			$sCreateDBNameText = '<span class="success">'.$_SESSION['wm_install_db_name_create_message'].'</span>';
			unset($_SESSION['wm_install_db_name_create_message']);
		}

		$sMainFootText = '';
		if (isset($_SESSION['wm_install_db_foot_error']))
		{
			$sMainFootText = '<span class="error">'.$_SESSION['wm_install_db_foot_error'].'</span>';
			unset($_SESSION['wm_install_db_foot_error']);
		}
		
		return array(
			'SqlTypeMySQLCheched' => EDbType::MySQL === $this->oSettings->GetConf('Common/DBType')  ? 'checked="cheched"' : '',
			'SqlTypePostgreSQLCheched' => EDbType::PostgreSQL === $this->oSettings->GetConf('Common/DBType') ? 'checked="cheched"' : '',
			'Login' => $this->oSettings->GetConf('Common/DBLogin'),
			'Password' => API_DUMMY,
			'DbName' => $this->oSettings->GetConf('Common/DBName'),
			'Host' => $this->oSettings->GetConf('Common/DBHost'),
			'Prefix' => $this->oSettings->GetConf('Common/DBPrefix'),
			'CreateDbCheched' => (isset($_SESSION['wm-install-create-db'])) ? '' : 'checked="cheched"',
			'CreateDBNameText' => $sCreateDBNameText,
			'TestDBText' => $sTestDbText,
			'MainFootText' => $sMainFootText
		);
	}
}