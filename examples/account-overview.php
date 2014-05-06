<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
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
		$iOffset = 0;
		$iLimit = 5;

		$oCollection = null;

		try
		{
			// Getting required API class
			$oApiIntegratorManager = CApi::Manager('integrator');

			// attempting to obtain object for account we're trying to log into
			$oAccount = $oApiIntegratorManager->LoginToAccount($sEmail, $sPassword);
			if ($oAccount)
			{
				$oApiMailManager = CApi::Manager('mail');
				$oCollection =  $oApiMailManager->MessageList($oAccount, $sFolder, $iOffset, $iLimit);

				/* @var $oCollection CApiMailMessageCollection */
				if ($oCollection)
				{

					echo '<b>'.$oAccount->Email.':</b><br />';
					echo '<pre>';
					echo 'Folder:   '.$sFolder."\n";
					echo 'Count:    '.$oCollection->MessageCount."\n"; // $oCollection->MessageResultCount
					echo 'Unread:   '.$oCollection->MessageUnseenCount."\n";
					echo 'List:   '."\n";

					$oCollection->ForeachList(function (/* @var $oMessage CApiMailMessage */ $oMessage) {
						$oFrom = /* @var $oFrom \MailSo\Mime\EmailCollection */ $oMessage->From();
						echo "\t".htmlentities($oMessage->Uid().') '.$oMessage->Subject().($oFrom ? ' ('.$oFrom->ToString().')' : ''))."\n";
					});

					echo '</pre>';
				}
				else
				{
					echo $oApiMailManager->GetLastErrorMessage();
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