<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiFetchersManager class summary
 *
 * @package Fetchers
 */
class CApiFetchersManager extends AApiManagerWithStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('fetchers', $oManager, $sForcedStorage);

		$this->inc('classes.fetcher');
	}

	/**
	 * @param CFetcher $oFetcher
	 *
	 * @return \MailSo\Pop3\Pop3Client|null
	 */
	private function getTestPop3Client($oFetcher)
	{
		$oPop3Client = null;
		if ($oFetcher)
		{
			$oPop3Client = \MailSo\Pop3\Pop3Client::NewInstance();
			$oPop3Client->SetTimeOuts(5, 5);
			$oPop3Client->SetLogger(\CApi::MailSoLogger());
			
			if (!$oPop3Client->IsConnected())
			{
				$oPop3Client->Connect($oFetcher->IncomingMailServer, $oFetcher->IncomingMailPort, $oFetcher->IncomingMailSecurity);
			}

			if (!$oPop3Client->IsLoggined())
			{
				$oPop3Client->Login($oFetcher->IncomingMailLogin, $oFetcher->IncomingMailPassword);
			}
		}

		return $oPop3Client;
	}

	/**
	 * @param CAccount $oAccount
	 * @param CFetcher $oFetcher
	 *
	 * @return bool
	 */
	public function createFetcher($oAccount, &$oFetcher)
	{
		$mResult = false;
		try
		{
			$this->getTestPop3Client($oFetcher);
			$mResult = $this->oStorage->createFetcher($oAccount, $oFetcher);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		catch (\MailSo\Net\Exceptions\ConnectionException $oException)
		{
			$this->setLastException(new CApiBaseException(CApiErrorCodes::Fetcher_ConnectToMailServerFailed, $oException));
		}
		catch (\MailSo\Pop3\Exceptions\LoginBadCredentialsException $oException)
		{
			$this->setLastException(new CApiBaseException(CApiErrorCodes::Fetcher_AuthError, $oException));
		}
		catch (Exception $oException)
		{
			$this->setLastException(new CApiBaseException(CApiErrorCodes::Fetcher_AuthError, $oException));
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return array|bool
	 */
	public function getFetchers($oAccount)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->getFetchers($oAccount);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iFetcherID
	 *
	 * @return bool
	 */
	public function deleteFetcher($oAccount, $iFetcherID)
	{
		$mResult = false;
		try
		{
			$mResult = $this->oStorage->deleteFetcher($oAccount, $iFetcherID);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param CFetcher $oFetcher
	 *
	 * @return bool
	 */
	public function updateFetcher($oAccount, $oFetcher)
	{
		$mResult = false;
		try
		{
			$this->getTestPop3Client($oFetcher);
			$mResult = $this->oStorage->updateFetcher($oAccount, $oFetcher);
		}
		catch (CApiBaseException $oException)
		{
			$this->setLastException($oException);
		}
		catch (\MailSo\Net\Exceptions\ConnectionException $oException)
		{
			$this->setLastException(new CApiBaseException(CApiErrorCodes::Fetcher_ConnectToMailServerFailed, $oException));
		}
		catch (\MailSo\Pop3\Exceptions\LoginBadCredentialsException $oException)
		{
			$this->setLastException(new CApiBaseException(CApiErrorCodes::Fetcher_AuthError, $oException));
		}
		catch (Exception $oException)
		{
			$this->setLastException(new CApiBaseException(CApiErrorCodes::Fetcher_AuthError, $oException));
		}
		return $mResult;
	}
}
