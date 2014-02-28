<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Filestorage
 */
class CApiFilestorageManager extends AApiManagerWithStorage
{
	protected $oMinMan = null;
	
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager, $sForcedStorage = '')
	{
		parent::__construct('filestorage', $oManager, $sForcedStorage);
	}

	public function GetMinManager()
	{
		if ($this->oMinMan === null)
		{
			$this->oMinMan = \CApi::Manager('min');
		}
		return $this->oMinMan;
	}
	
	public function FileExists($oAccount, $iType, $sPath, $sName)
	{
		return $this->oStorage->FileExists($oAccount, $iType, $sPath, $sName);
	}

	public function GetSharedFile($oAccount, $iType, $sPath, $sName)
	{
		return $this->oStorage->GetSharedFile($oAccount, $iType, $sPath, $sName);
	}

	public function GetFile($oAccount, $iType, $sPath, $sName)
	{
		return $this->oStorage->GetFile($oAccount, $iType, $sPath, $sName);
	}

	public function GetFiles($oAccount, $iType, $sPath, $sPattern = '')
	{
		return $this->oStorage->GetFiles($oAccount, $iType, $sPath, $sPattern);
	}

	public function CreateFolder($oAccount, $iType, $sPath, $sFolderName)
	{
		return $this->oStorage->CreateFolder($oAccount, $iType, $sPath, $sFolderName);
	}
	
	public function CreateFile($oAccount, $iType, $sPath, $sFileName, $mData)
	{
		return $this->oStorage->CreateFile($oAccount, $iType, $sPath, $sFileName, $mData);
	}
	
	public function Delete($oAccount, $iType, $sPath, $sName)
	{
		$oResult = $this->oStorage->Delete($oAccount, $iType, $sPath, $sName);
		if ($oAccount && $oAccount instanceof CAccount)
		{
			$oMin = $this->GetMinManager();
			if ($oMin)
			{
				$oMin->DeleteMinByID($this->oStorage->GenerateShareHash($oAccount, $iType, $sPath, $sName));
			}
		}
		
		return $oResult;
	}

	private function generateMinArray($oAccount, $iType, $sPath, $sNewName, $iSize)
	{
		$aData = null;
		if ($oAccount)
		{
			$aData = array(
				'AccountType' => $oAccount instanceof CAccount ? 'wm' : '',
				'Account' => 0,
				'Type' => $iType,
				'Path' => $sPath,
				'Name' => $sNewName,
				'Size' => $iSize
			);

			if (empty($aData['AccountType']) && $oAccount instanceof CHelpdeskUser)
			{
				$aData['AccountType'] = 'hd';
			}

			if ('wm' === $aData['AccountType'])
			{
				$aData['Account'] = $oAccount->IdAccount;
			}
			else if ('hd' === $aData['AccountType'])
			{
				$aData['Account'] = $oAccount->IdHelpdeskUser;
			}
		}

		return $aData;
	}
	
	public function Rename($oAccount, $iType, $sPath, $sName, $sNewName)
	{
		$oResult = $this->oStorage->Rename($oAccount, $iType, $sPath, $sName, $sNewName);
		if ($oResult)
		{
			$sID = $this->oStorage->GenerateShareHash($oAccount, $iType, $sPath, $sName);
			$sNewID = $this->oStorage->GenerateShareHash($oAccount, $iType, $sPath, $sNewName);

			$oMin = $this->GetMinManager();

			$mData = $oMin->GetMinByID($sID);
			if ($mData && $oAccount)
			{
				$aData = $this->generateMinArray($oAccount, $iType, $sPath, $sNewName, $mData['Size']);
				if ($aData)
				{
					$oMin->UpdateMinByID($sID, $aData, $sNewID);
				}
			}
		}
		return $oResult;
	}
	
	public function Move($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName)
	{
		$GLOBALS['__FILESTORAGE_MOVE_ACTION__'] = true;
		$oResult = $this->oStorage->Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName, true);
		$GLOBALS['__FILESTORAGE_MOVE_ACTION__'] = false;
		if ($oResult)
		{
			$sID = $this->oStorage->GenerateShareHash($oAccount, $iFromType, $sFromPath, $sName);
			$sNewID = $this->oStorage->GenerateShareHash($oAccount, $iToType, $sToPath, $sNewName);

			$oMin = $this->GetMinManager();

			$mData = $oMin->GetMinByID($sID);
			if ($mData)
			{
				$aData = $this->generateMinArray($oAccount, $iToType, $sToPath, $sNewName, $mData['Size']);
				if ($aData)
				{
					$oMin->UpdateMinByID($sID, $aData, $sNewID);
				}
			}
		}
		return $oResult;
	}

	public function Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName = null)
	{
		return $this->oStorage->Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName);
	}

	public function GetRealQuota($oAccount, $iType = EFileStorageType::Private_)
	{
		return $this->oStorage->GetRealQuota($oAccount, $iType);
	}
	
	public function GetQuota($oAccount)
	{
		$iUsageSize = 0;
		$iFreeSize = 0;
		
		$oApiTenants = \CApi::Manager('tenants');
		$oTenant = $oApiTenants ? $oApiTenants->GetTenantById($oAccount->IdTenant) : null;
		if ($oTenant)
		{
			$iUsageSize = $oTenant->FilesUsageInMB * 1024 * 1024;
			$iFreeSize = ($oTenant->FilesUsageDynamicQuotaInMB * 1024 * 1024) - $iUsageSize;
		}
		
		return array($iUsageSize, $iFreeSize);
	}
	
	public function GetNonExistingFileName($oAccount, $iType, $sPath, $sFileName)
	{
		return $this->oStorage->GetNonExistingFileName($oAccount, $iType, $sPath, $sFileName);
	}	
	
	public function ClearPrivateFiles($oAccount)
	{
		$this->oStorage->ClearPrivateFiles($oAccount);
	}

	public function ClearCorporateFiles($oAccount)
	{
		$this->oStorage->ClearPrivateFiles($oAccount);
	}

	public function ClearAllFiles($oAccount)
	{
		$this->ClearPrivateFiles($oAccount);
		$this->ClearCorporateFiles($oAccount);
	}
}
