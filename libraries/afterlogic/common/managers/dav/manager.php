<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Dav
 */
class CApiDavManager extends AApiManager
{
	/**
	 * @var array
	 */
	protected $aDavClients;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('dav', $oManager);
		CApi::Inc('common.dav.client');

		$this->aDavClients = array();
	}

	/**
	 * @param CAccount $oAccount
	 * @return DAVClient | false
	 */
	protected function &getDAVClient($oAccount)
	{
		$mResult = false;
		if (!isset($this->aDavClients[$oAccount->Email]))
		{
			$this->aDavClients[$oAccount->Email] = new DAVClient(
				$this->GetServerUrl($oAccount), $oAccount->Email, $oAccount->IncomingMailPassword);
		}

		if (isset($this->aDavClients[$oAccount->Email]))
		{
			$mResult =& $this->aDavClients[$oAccount->Email];
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return string
	 */
	public function GetServerUrl($oAccount = null)
	{
		$oSettings =& CApi::GetSettings();
		return rtrim($oAccount
			? $oAccount->Domain->ExternalHostNameOfDAVServer
			: $oSettings->GetConf('WebMail/ExternalHostNameOfDAVServer'), '/');
	}

	/**
	 * @return string
	 */
	public function GetCalendarStorageType()
	{
		return $this->oManager->GetStorageByType('calendar');
	}

	/**
	 * @return string
	 */
	public function GetContactsStorageType()
	{
		return $this->oManager->GetStorageByType('maincontacts');
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return string
	 */
	public function GetServerHost($oAccount = null)
	{
		$mResult = '';
		$sServerUrl = $this->GetServerUrl($oAccount);
		if (!empty($sServerUrl))
		{
			$aUrlParts = parse_url($sServerUrl);
			if (!empty($aUrlParts['host']))
			{
				$mResult = $aUrlParts['host'];
			}
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return bool
	 */
	public function IsUseSsl($oAccount = null)
	{
		$bResult = false;
		$sServerUrl = $this->GetServerUrl($oAccount);
		if (!empty($sServerUrl))
		{
			$aUrlParts = parse_url($sServerUrl);
			if (!empty($aUrlParts['port']) && $aUrlParts['port'] === 443)
			{
				$bResult = true;
			}
			if (!empty($aUrlParts['scheme']) && $aUrlParts['scheme'] === 'https')
			{
				$bResult = true;
			}
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount = null
	 * @return string
	 */
	public function GetServerPort($oAccount)
	{
		$iResult = 80;
		$sServerUrl = $this->GetServerUrl($oAccount);
		if (!empty($sServerUrl))
		{
			$aUrlParts = parse_url($sServerUrl);
			if (!empty($aUrlParts['port']))
			{
				$iResult = (int) $aUrlParts['port'];
			}
		}
		return $iResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return string
	 */
	public function GetPrincipalUrl($oAccount)
	{
		$mResult = false;
		try
		{
			$sServerUrl = $this->GetServerUrl($oAccount);
			if (!empty($sServerUrl))
			{
				$aUrlParts = parse_url($sServerUrl);
				$sPort = $sPath = '';
				if (!empty($aUrlParts['port']) && (int)$aUrlParts['port'] !== 80)
				{
					$sPort = ':'.$aUrlParts['port'];
				}
				if (!empty($aUrlParts['path']))
				{
					$sPath = $aUrlParts['path'];
				}

				if (!empty($aUrlParts['scheme']) && !empty($aUrlParts['host']))
				{
					$sServerUrl = $aUrlParts['scheme'].'://'.$aUrlParts['host'].$sPort;

					if ($this->GetCalendarStorageType() === 'caldav' || $this->GetContactsStorageType() === 'carddav')
					{
						$oDav =& $this->getDAVClient($oAccount);
						if ($oDav && $oDav->Connect())
						{
							$mResult = $sServerUrl.$oDav->GetCurrentPrincipal();
						}
					}
					else
					{
						$mResult = $sServerUrl . $sPath .'/principals/' . $oAccount->Email;
					}
				}
			}
		}
		catch (Exception $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return string
	 */
	public function GetLogin($oAccount)
	{
		return $oAccount->Email;
	}

	/**
	 * @return bool
	 */
	public function IsMobileSyncEnabled()
	{
		$oSettings =& CApi::GetSettings();
		return (bool) $oSettings->GetConf('Common/EnableMobileSync');
	}

	/**
	 * @return bool
	 */
	public function SetMobileSyncEnable($bMobileSyncEnable)
	{
		$oSettings =& CApi::GetSettings();
		$oSettings->SetConf('Common/EnableMobileSync', $bMobileSyncEnable);
		return (bool) $oSettings->SaveToXml();
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function TestConnection($oAccount)
	{
		$mResult = false;
		$oDav =& $this->getDAVClient($oAccount);
		if ($oDav && $oDav->Connect())
		{
			$mResult = true;
		}
		return $mResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @return bool
	 */
	public function DeletePrincipal($oAccount)
	{
		$oPdo = CApi::GetPDO();
		$oSettings =& CApi::GetSettings();
		$sDbPrefix = $oSettings->GetConf('Common/DBPrefix');

		if ($oPdo)
		{
			$oPrincipalBackend = new \afterlogic\DAV\Principal\Backend\PDO($oPdo, $sDbPrefix);
			$oPrincipalBackend->deletePrincipal('principals/' . $oAccount->Email);
		}
	}

	/**
	 * @param string $sData
	 * @return mixed
	 */
	public function VObjectReaderRead($sData)
	{
		return \Sabre\VObject\Reader::read($sData);
	}

	/**
	 * @param string $sName
	 * @param string $sValue
	 * @return \Sabre\VObject\Property
	 */
	public function CreateVObjectProperty($sName, $sValue)
	{
		return new \Sabre\VObject\Property($sName, $sValue);
	}
}
