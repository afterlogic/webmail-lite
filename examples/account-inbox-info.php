<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

	// remove the following line for real use
	exit('remove this line');

	// Example of logging into WebMail account using email and password for incorporating into another web application

	// utilizing API
	include_once __DIR__.'/../libraries/afterlogic/api.php';

	if (class_exists('CApi') && CApi::IsValid())
	{
		// data for logging into account
		$sEmail = 'user@domain.com';
		$sPassword = '12345';
		$sFolder = 'INBOX';

		try
		{
			// Getting required API class
			$oApiIntegratorManager = CApi::Manager('integrator');

			// attempting to obtain object for account we're trying to log into
			$oAccount = $oApiIntegratorManager->loginToAccount($sEmail, $sPassword);
			if ($oAccount)
			{
				$oApiMailManager = CApi::Manager('mail');
				$aData = $oApiMailManager->getFolderInformation($oAccount, $sFolder);

				echo '<b>'.$oAccount->Email.':</b><br />';
				if (is_array($aData) && 4 === count($aData))
				{
					echo '<pre>';
					echo 'Folder:   '.$sFolder."\n";
					echo 'Count:    '.$aData[0]."\n";
					echo 'Unread:   '.$aData[1]."\n";
					echo 'UidNext:  '.$aData[2]."\n";
					echo 'Hash:     '.$aData[3];
					echo '</pre>';
				}
			}
			else
			{
				// login error
				echo $oApiIntegratorManager->GetLastErrorMessage();
			}
		}
		catch (Exception $oException)
		{
			// login error
			echo $oException->getMessage();
		}
	}
	else
	{
		echo 'AfterLogic API isn\'t available';
	}