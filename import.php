<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/'));
	include_once WM_ROOTPATH.'application/include.php';
	$oInput = new api_Http();

	require_once(WM_ROOTPATH.'common/inc_constants.php');
	require_once(WM_ROOTPATH.'common/class_mailprocessor.php');
	require_once(WM_ROOTPATH.'common/class_validate.php');
	require_once(WM_ROOTPATH.'common/class_convertutils.php');

	header('Content-type: text/html; charset=utf-8');
	
 	$Error_Desc = '';
	$ErrorInt = 1;
	$iParsedCount = $iImportedCount = 0;
	$fs = $attfolder = null;

	@ob_start();

	/* @var $oAccount CAccount */
	$oAccount = AppGetAccount(CSession::Get(APP_SESSION_ACCOUNT_ID, false));
	if (!$oAccount)
	{
		$Error_Desc = UnknownUploadError;
	}
	else
	{
		AppIncludeLanguage($oAccount->User->DefaultLanguage);
		
		$fs = new FileSystem(INI_DIR.'/temp', strtolower($oAccount->Email), $oAccount->IdAccount);
		$attfolder = new Folder($oAccount->IdAccount, -1, GetSessionAttachDir());
	}

	define('FILE_DATA_KEY', 'Filedata');

	$filesize = 0;
	$tempname = '';
	$isNullFile = false;
	
	if (empty($Error_Desc) && $oAccount && $fs && $attfolder)
	{
		if (isset($_FILES[FILE_DATA_KEY]))
		{
			$tempname = 'import_'.basename($_FILES[FILE_DATA_KEY]['tmp_name']);
			
			$fs->CreateFolder($attfolder);
			if (!@move_uploaded_file($_FILES[FILE_DATA_KEY]['tmp_name'], $fs->GetFolderFullPath($attfolder).'/'.$tempname))
			{
				switch ($_FILES[FILE_DATA_KEY]['error'])
				{
					case 1:
					case 2:
						$Error_Desc = FileIsTooBig;
						break;
					case 3:
						$Error_Desc = FilePartiallyUploaded;
						break;
					case 4:
						$Error_Desc = NoFileUploaded;
						break;
					case 6:
						$Error_Desc = MissingTempFolder;
						break;
					default:
						$Error_Desc = UnknownUploadError;
						break;
				}
			} 
			else
			{
				$filesize = @filesize($fs->GetFolderFullPath($attfolder).'/'.$tempname);
				if ($filesize === false)
				{
					$Error_Desc = MissingTempFile;	
				}
			}
		}
		else 
		{
			$postsize = @ini_get('upload_max_filesize');
			$Error_Desc = ($postsize) ? FileLargerThan.$postsize : FileIsTooBig;
		}
	
		if (empty($Error_Desc))
		{
			ConvertUtils::SetLimits();

			$isNullFile = !(isset($filesize) && $filesize > 0);
			
			/* @var $oApiContactsManager CApiContactsManager */
			$oApiContactsManager = CApi::Manager('contacts');
			$iImportedCount = $oApiContactsManager->ImportEx(
				$oAccount->IdUser, 'csv', $fs->GetFolderFullPath($attfolder).'/'.$tempname,
				$iParsedCount);
			
			if (-1 !== $iImportedCount)
			{
				if ($iImportedCount > 0)
				{
					if ($iImportedCount === $iParsedCount)
					{
						CSession::Set('action_report',
							JS_LANG_InfoHaveImported.' '.$iImportedCount.' '.JS_LANG_InfoNewContacts);
					}
					else
					{
						$ErrorInt = 0;
					}
				}
				else
				{
					if ($isNullFile)
					{
						$ErrorInt = ($ErrorInt == 1) ? 2 : $ErrorInt;
					}
					else
					{
						$ErrorInt = 2;
					}
				}
			}
			else
			{
				$ErrorInt = 0;
			}

			// delete import file
			@unlink($fs->GetFolderFullPath($attfolder).'/'.$tempname);
		}
	}
	else 
	{
		die('<script type="text/javascript">alert("'.ConvertUtils::ClearJavaScriptString($Error_Desc, '"').'");</script>');
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title></title>
</head>
<body>
	<script type="text/javascript">
		parent.ImportContactsHandler(<?php echo $ErrorInt;?>, <?php echo $iImportedCount; ?>);
	</script>
</body>
</html>
<?php @ob_end_flush();