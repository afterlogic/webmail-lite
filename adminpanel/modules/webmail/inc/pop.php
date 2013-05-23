<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

class CWebMailPopAction extends ap_CoreModuleHelper
{
	public function Services()
	{
		$sType = isset($_GET['type']) ? $_GET['type'] : '';
		$sAction = isset($_GET['action']) ? $_GET['action'] : '';

		$iLimit = CApi::GetConf('log.max-view-size', 100) * 1024;
		if (('log' === $sType && 'view' === $sAction) ||
			('useractivity' === $sType && 'view' === $sAction))
		{
			/* @var $oApiLoggerManager CApiLoggerManager */
			$oApiLoggerManager = CApi::Manager('logger');
			$iSize = 0;
			$rLog = ('log' === $sType)
				? $oApiLoggerManager->GetCurrentLogStream($iSize)
				: $oApiLoggerManager->GetCurrentUserActivityLogStream($iSize);

			@header('Content-type: text/plain; charset=utf-8');
			if ($rLog && false !== $iSize)
			{
				if (0 === $iSize)
				{
					echo 'Log file empty';
				}
				else
				{
					if ($iLimit < $iSize)
					{
						@fseek($rLog, $iSize - $iLimit);
					}

					@fpassthru($rLog);
				}
			}
			else
			{
				echo 'Log file can\'t be read';
			}

			if ($rLog)
			{
				@fclose($rLog);
			}
		}
		else if ('dllog' === $sType || 'dluseractivity' === $sType)
		{
			/* @var $oApiLoggerManager CApiLoggerManager */
			$oApiLoggerManager = CApi::Manager('logger');
			$iSize = 0;
			$rLog = ('dllog' === $sType)
				? $oApiLoggerManager->GetCurrentLogStream($iSize)
				: $oApiLoggerManager->GetCurrentUserActivityLogStream($iSize);

			// IE
			@header('Expires: 0', true);
			@header('Cache-Control: must-revalidate, post-check=0, pre-check=0', true);
			@header('Pragma: public', true);

			$sName = ('dllog' === $sType)
				? $oApiLoggerManager->LogName()
				: $oApiLoggerManager->CurrentUserActivityLogName();

			@header('Accept-Ranges: bytes', true);
			@header('Content-Disposition: attachment; filename="'.urlencode($sName).'"; charset=utf-8');
			@header('Content-Transfer-Encoding: binary', true);
			@header('Content-Length: '.$iSize);

			@header('Content-type: text/plain; charset=utf-8', true);

			if ($rLog && false !== $iSize)
			{
				@fpassthru($rLog);
			}
			
			if ($rLog)
			{
				@fclose($rLog);
			}
		}
	}
}