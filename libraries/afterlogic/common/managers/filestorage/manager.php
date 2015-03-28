<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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

		$this->inc('classes.item');
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
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.file-exists', array($oAccount, $iType, $sPath, $sName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->FileExists($oAccount, $iType, $sPath, $sName);
		}
		return $bResult;
	}

	public function GetSharedFile($oAccount, $iType, $sPath, $sName)
	{
		return $this->oStorage->GetSharedFile($oAccount, $iType, $sPath, $sName);
	}

	public function GetFileInfo($oAccount, $iType, $sPath, $sName)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.get-file-info', array($oAccount, $iType, $sPath, $sName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->GetFileInfo($oAccount, $iType, $sPath, $sName);
		}
		return $bResult;
	}

	public function GetDirectoryInfo($oAccount, $iType, $sPath)
	{
		return $this->oStorage->GetDirectoryInfo($oAccount, $iType, $sPath);
	}

	public function GetFile($oAccount, $iType, $sPath, $sName)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.get-file', array($oAccount, $iType, $sPath, $sName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->GetFile($oAccount, $iType, $sPath, $sName);
		}
		return $bResult;
	}

	public function CreatePublicLink($oAccount, $iType, $sPath, $sName, $sSize, $bIsFolder)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.create-public-link', array($oAccount, $iType, $sPath, $sName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->CreatePublicLink($oAccount, $iType, $sPath, $sName, $sSize, $bIsFolder);
		}
		return $bResult;
	}

	public function DeletePublicLink($oAccount, $iType, $sPath, $sName)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.delete-public-link', array($oAccount, $iType, $sPath, $sName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->DeletePublicLink($oAccount, $iType, $sPath, $sName);
		}
		return $bResult;
	}

	public function GetFiles($oAccount, $sType, $sPath, $sPattern = '')
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.get-files', array($oAccount, $sType, $sPath, $sPattern, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->GetFiles($oAccount, $sType, $sPath, $sPattern);
		}
		return $bResult;
	}

	public function CreateFolder($oAccount, $iType, $sPath, $sFolderName)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.create-folder', array($oAccount, $iType, $sPath, $sFolderName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->CreateFolder($oAccount, $iType, $sPath, $sFolderName);
		}
		return $bResult;
	}
	
	public function CreateFile($oAccount, $iType, $sPath, $sFileName, $mData, $bOverride = true)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.create-file', array($oAccount, $iType, $sPath, $sFileName, $mData, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			if (!$bOverride)
			{
				$sFileName = $this->oStorage->GetNonExistingFileName($oAccount, $iType, $sPath, $sFileName);
			}
			$bResult = $this->oStorage->CreateFile($oAccount, $iType, $sPath, $sFileName, $mData);
		}
		return $bResult;
	}
	
	public function CreateLink($oAccount, $iType, $sPath, $sLink, $sName)
	{
		return $this->oStorage->CreateLink($oAccount, $iType, $sPath, $sLink, $sName);
	}	
	
	public function Delete($oAccount, $iType, $sPath, $sName)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.delete', array($oAccount, $iType, $sPath, $sName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->Delete($oAccount, $iType, $sPath, $sName);
			if ($oAccount && $oAccount instanceof CAccount)
			{
				$oMin = $this->GetMinManager();
				if ($oMin)
				{
					$oMin->DeleteMinByID($this->oStorage->GenerateShareHash($oAccount, $iType, $sPath, $sName));
				}
			}
		}
		
		return $bResult;
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
	
	public function Rename($oAccount, $iType, $sPath, $sName, $sNewName, $bIsLink)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.rename', array($oAccount, $iType, $sPath, $sName, $sNewName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $bIsLink ? $this->oStorage->RenameLink($oAccount, $iType, $sPath, $sName, $sNewName) : $this->oStorage->Rename($oAccount, $iType, $sPath, $sName, $sNewName);
			if ($bResult)
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
		}
		return $bResult;
	}
	
	public function Move($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.move', array($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$GLOBALS['__FILESTORAGE_MOVE_ACTION__'] = true;
			$bResult = $this->oStorage->Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName, true);
			$GLOBALS['__FILESTORAGE_MOVE_ACTION__'] = false;
			if ($bResult)
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
		}
		return $bResult;
	}

	public function Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName = null)
	{
		$bResult = false;
		$bBreak = false;
		\CApi::Plugin()->RunHook('filestorage.copy', array($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName, &$bResult, &$bBreak));
		if (!$bBreak)
		{
			$bResult = $this->oStorage->Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName);
		}
		return $bResult;
	}

	public function GetRealQuota($oAccount, $sType = EFileStorageTypeStr::Personal)
	{
		return $this->oStorage->GetRealQuota($oAccount, $sType);
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
