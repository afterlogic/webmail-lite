<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
	 * 
	 * @param CApiGlobalManager $oManager
	 * @param type $sForcedStorage
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('dav', $oManager);
		CApi::Inc('common.dav.client');

		$this->aDavClients = array();
	}

	/**
	 * @param CAccount $oAccount
	 * @return CDAVClient|false
	 */
	protected function &_getDAVClient($oAccount)
	{
		$mResult = false;
		if (!isset($this->aDavClients[$oAccount->Email]))
		{
			$this->aDavClients[$oAccount->Email] = new CDAVClient(
				$this->getServerUrl($oAccount), $oAccount->Email, $oAccount->IncomingMailPassword);
		}

		if (isset($this->aDavClients[$oAccount->Email]))
		{
			$mResult =& $this->aDavClients[$oAccount->Email];
		}

		return $mResult;
	}

	/**
	 * @param CAccount $oAccount Default null
	 * 
	 * @return string
	 */
	public function getServerUrl($oAccount = null)
	{
		$oSettings =& CApi::GetSettings();
		return rtrim($oAccount
			? $oAccount->Domain->ExternalHostNameOfDAVServer
			: $oSettings->GetConf('WebMail/ExternalHostNameOfDAVServer'), '/');
	}

	/**
	 * @return string
	 */
	public function getCalendarStorageType()
	{
		return $this->oManager->GetStorageByType('calendar');
	}

	/**
	 * @return string
	 */
	public function getContactsStorageType()
	{
		return $this->oManager->GetStorageByType('contactsmain');
	}

	/**
	 * @param CAccount $oAccount Default null
	 * 
	 * @return string
	 */
	public function getServerHost($oAccount = null)
	{
		$mResult = '';
		$sServerUrl = $this->getServerUrl($oAccount);
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
	 * @param CAccount $oAccount Default null
	 * 
	 * @return bool
	 */
	public function isUseSsl($oAccount = null)
	{
		$bResult = false;
		$sServerUrl = $this->getServerUrl($oAccount);
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
	 * @param CAccount $oAccount Default null
	 * 
	 * @return int
	 */
	public function getServerPort($oAccount)
	{
		$iResult = 80;
		if ($this->isUseSsl($oAccount))
		{
			$iResult = 443;
		}
			
		$sServerUrl = $this->getServerUrl($oAccount);
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
	 * 
	 * @return string
	 */
	public function getPrincipalUrl($oAccount)
	{
		$mResult = false;
		try
		{
			$sServerUrl = $this->getServerUrl($oAccount);
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

					if ($this->getCalendarStorageType() === 'caldav' || $this->getContactsStorageType() === 'carddav')
					{
						$oDav =& $this->_getDAVClient($oAccount);
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
	 * 
	 * @return string
	 */
	public function getLogin($oAccount)
	{
		return $oAccount->Email;
	}

	/**
	 * @return bool
	 */
	public function isMobileSyncEnabled()
	{
		$oSettings =& CApi::GetSettings();
		return (bool) $oSettings->GetConf('Common/EnableMobileSync');
	}

	/**
	 * 
	 * @param bool $bMobileSyncEnable
	 * 
	 * @return bool
	 */
	public function setMobileSyncEnable($bMobileSyncEnable)
	{
		$oSettings =& CApi::GetSettings();
		$oSettings->SetConf('Common/EnableMobileSync', $bMobileSyncEnable);
		return (bool) $oSettings->SaveToXml();
	}

	/**
	 * @param CAccount $oAccount
	 * 
	 * @return bool
	 */
	public function testConnection($oAccount)
	{
		$bResult = false;
		$oDav =& $this->_getDAVClient($oAccount);
		if ($oDav && $oDav->Connect())
		{
			$bResult = true;
		}
		return $bResult;
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function deletePrincipal($oAccount)
	{
		$oPrincipalBackend = \afterlogic\DAV\Backend::Principal();
		$oPrincipalBackend->deletePrincipal(\afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $oAccount->Email);
	}

	/**
	 * @param string $sData
	 * @return mixed
	 */
	public function getVCardObject($sData)
	{
		return \Sabre\VObject\Reader::read($sData, \Sabre\VObject\Reader::OPTION_IGNORE_INVALID_LINES);
	}
}
