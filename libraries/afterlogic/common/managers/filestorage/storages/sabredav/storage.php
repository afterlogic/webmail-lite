<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Filestorage
 */
class CApiFilestorageSabredavStorage extends CApiFilestorageStorage
{
	/**
	 * @var \afterlogic\DAV\Server
	 */
	protected $Server;
	
	/**
	 * @var \Sabre\DAV\Auth\Plugin
	 */
	protected $authPlugin;
	
	/**
	 * @var bool
	 */
	protected $initialized;
	
	/**
	 * @var \CApiMinManager
	 */
	protected $oMinMan = null;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('sabredav', $oManager);
		
		$this->initialized = false;
		$this->Server = new \afterlogic\DAV\Server();
		$this->authPlugin = $this->Server->getPlugin('auth');
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param int $iType
	 * @param string $sPath
	 * @param string $sFileName
	 * @return string
	 */
	public function GenerateShareHash($oAccount, $iType, $sPath, $sFileName)
	{
		$sId = '';
		if ($oAccount instanceof CAccount)
		{
			$sId = $oAccount->IdAccount;
		}
		else if ($oAccount instanceof CHelpdeskUser)
		{
			$sId = 'hd/'.$oAccount->IdHelpdeskUser;
		}

		return implode('|', array($sId, $iType, $sPath, $sFileName));
	}
	
	public function GetMinManager()
	{
		if ($this->oMinMan === null)
		{
			$this->oMinMan = \CApi::Manager('min');
		}
		
		return $this->oMinMan;
	}
	
	/**
	 * @param CAccount $oAccount
	 */
	public function Init($oAccount)
	{
		$bResult = false;
		if ($oAccount)
		{
			if (!$this->initialized)
			{
				$this->authPlugin->setCurrentAccount($oAccount);
				\afterlogic\DAV\Utils::CheckPrincipals($oAccount->Email);
				$this->initialized = true;
			}
			$bResult = true;
		}
		
		return $bResult;
	}	

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param inc $iType
	 * @param bool $bUser
	 * @return string
	 */
	protected function getRootPath($oAccount, $iType, $bUser = false)
	{
		$sRootPath = null;
		if ($oAccount)
		{
			$sUser = $bUser ? '/' . $oAccount->Email : '';
			$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_PRIVATE . $sUser;

			if ($iType == \EFileStorageType::Corporate)
			{
				$iTenantId = $oAccount ? $oAccount->IdTenant : 0;

				$sTenant = $bUser ? $sTenant = '/' . $iTenantId : '';
				$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_CORPORATE . $sTenant;
			}
			else if ($iType == \EFileStorageType::Shared)
			{
				$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_SHARED . $sUser;
			}
		}		
		return $sRootPath;
	}
	
	protected function getDirectory($oAccount, $iType, $sPath = '')
	{
		$oDirectory = null;
		
		if ($oAccount)
		{
			$sRootPath = $this->getRootPath($oAccount, $iType);
			if ($iType == EFileStorageType::Private_)
			{
				$oDirectory = new \afterlogic\DAV\FS\PrivateRoot($this->authPlugin, $sRootPath);
			}
			if ($iType == EFileStorageType::Corporate)
			{
				$oDirectory = new \afterlogic\DAV\FS\PublicRoot($this->authPlugin, $sRootPath);
			}	
			if ($iType == EFileStorageType::Shared)
			{
				$oDirectory = new \afterlogic\DAV\FS\SharedRoot($this->authPlugin, $sRootPath);
			}	
			if ($oDirectory && $sPath !== '')
			{
				$oDirectory = $oDirectory->getChild($sPath);
			}
		}		
		return $oDirectory;
	}
	
	public function FileExists($oAccount, $iType, $sPath, $sName)
	{
		$bResult = false;
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $iType, $sPath);
			if ($oDirectory !== null)
			{
				if($oDirectory->childExists($sName))
				{
					$oItem = $oDirectory->getChild($sName);
					if ($oItem instanceof \afterlogic\DAV\FS\File)
					{
						$bResult = true;
					}
				}
			}
		}
		
		return $bResult;
	}	
	
	public function GetSharedFile($oAccount, $iType, $sPath, $sName)
	{
		$sResult = null;
		if ($this->Init($oAccount))
		{
			$sRootPath = $this->getRootPath($oAccount, $iType, true);
			$FilePath = $sRootPath . '/' . $sPath . '/' . $sName;
			if (file_exists($FilePath))
			{
				$sResult = fopen($FilePath, 'r');
			}
		}
		
		return $sResult;
	}

	public function GetFile($oAccount, $iType, $sPath, $sName)
	{
		$sResult = null;
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $iType, $sPath);
			if ($oDirectory !== null)
			{
				$oItem = $oDirectory->getChild($sName);
				if ($oItem !== null)
				{
					$sResult = $oItem->get();
				}
			}
		}
		return $sResult;
	}

	public function GetFiles($oAccount, $iType = EFileStorageType::Private_, $sPath = '', $sPattern = '')
	{
		$oDirectory = null;
		$aItems = array();
		$aResult = array();
		$oMin = $this->GetMinManager();
		
		if ($this->Init($oAccount))
		{
			$sRootPath = $this->getRootPath($oAccount, $iType, true);
			$oDirectory = $this->getDirectory($oAccount, $iType, $sPath);
			if ($oDirectory !== null)
			{
				if (!empty($sPattern) || is_numeric($sPattern))
				{
					$aItems = $oDirectory->Search($sPattern);
				}
				else
				{
					$aItems = $oDirectory->getChildren();
				}

				$iThumbnailLimit = 1024 * 1024 * 2; // 2MB

				foreach ($aItems as $oValue) 
				{
					$sFilePath = str_replace($sRootPath, '', dirname($oValue->getPath()));
					$aItem = array(
						'Type' => $iType,
						'Path' => $sFilePath,
						'Name' => $oValue->getName()
					);
					$sID = '';
					if ($oValue instanceof \afterlogic\DAV\FS\Directory)
					{
						$sID = $this->GenerateShareHash($oAccount, $iType, $sFilePath, $oValue->getName());
						$aItem['IsFolder'] = true;
					}

					if ($oValue instanceof \afterlogic\DAV\FS\File)
					{
						$sID = $this->GenerateShareHash($oAccount, $iType, $sFilePath, $oValue->getName());
						$aItem['IsFolder'] = false;

						$aItem['Size'] = $oValue->getSize();
						$aItem['LastModified'] = $oValue->getLastModified();
						$aItem['ContentType'] = $oValue->getContentType();
						if (!$aItem['ContentType'])
						{
							$aItem['ContentType'] = \api_Utils::MimeContentType($aItem['Name']);
						}

						$aItem['Thumb'] = \CApi::GetConf('labs.allow-thumbnail', true) &&
							$aItem['Size'] < $iThumbnailLimit &&
							\api_Utils::IsGDImageMimeTypeSuppoted($aItem['ContentType'], $aItem['Name']);
						
						$aItem['Hash'] = \CApi::EncodeKeyValues(array(
							'Type' => $iType,
							'Path' => $sFilePath,
							'Name' => $oValue->getName(),
							'Size' => $oValue->getSize()
						));
					}
					$mMin = $oMin->GetMinByID($sID);

					$aProps = $oValue->getProperties(array('Owner', 'Shared'));
					$aItem['Shared'] = isset($aProps['Shared']) ? $aProps['Shared'] : empty($mMin['__hash__']) ? false : true;
					$aItem['Owner'] = isset($aProps['Owner']) ? $aProps['Owner'] : $oAccount->Email;
					
					$aResult[] = $aItem;
				}
			}
		}
		
		return $aResult;
	}
	
	public function CreateFolder($oAccount, $iType, $sPath, $sFolderName)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $iType, $sPath);

			if ($oDirectory !== null)
			{
				$oDirectory->createDirectory($sFolderName);
			}
		}
	}
	
	public function CreateFile($oAccount, $iType, $sPath, $sFileName, $sData)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $iType, $sPath);

			if ($oDirectory !== null)
			{
				$oDirectory->createFile($sFileName, $sData);
			}
		}
	}
	
	public function Delete($oAccount, $iType, $sPath, $sName)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $iType, $sPath);
			$oItem = $oDirectory->getChild($sName);
			if ($oItem !== null)
			{
				if ($oItem instanceof \afterlogic\DAV\FS\Directory)
				{
					$this->UpdateMin($oAccount, $iType, $sPath, $sName, $sName, $oItem, true);
				}
				$oItem->delete();
				return true;
			}
		}
		return false;
	}

	public function UpdateMin($oAccount, $iType, $sPath, $sName, $sNewName, $oItem, $bDelete = false)
	{
		if ($oAccount)
		{
			$oMinMan = $this->GetMinManager();

			$sRootPath = $this->getRootPath($oAccount, $iType, true);

			$sOldPath = $sPath . '/' . $sName;
			$sNewPath = $sPath . '/' . $sNewName;

			if ($oItem instanceof \afterlogic\DAV\FS\Directory)
			{
				foreach ($oItem->getChildren() as $oChild)
				{
					if ($oChild instanceof \afterlogic\DAV\FS\File)
					{
						$sChildPath = substr(dirname($oChild->getPath()), strlen($sRootPath));
						$sID = $this->GenerateShareHash($oAccount, $iType, $sChildPath, $oChild->getName());
						if ($bDelete)
						{
							$oMinMan->DeleteMinByID($sID);
						}
						else
						{
							$mMin = $oMinMan->GetMinByID($sID);
							if (!empty($mMin['__hash__']))
							{
								$sNewChildPath = $sNewPath . substr($sChildPath, strlen($sOldPath));
								$sNewID = $this->GenerateShareHash($oAccount, $iType, $sNewChildPath, $oChild->getName());
								$mMin['Path'] = $sNewChildPath;
								$oMinMan->UpdateMinByID($sID, $mMin, $sNewID);
							}					
						}
					}
					if ($oChild instanceof \afterlogic\DAV\FS\Directory)
					{
						$this->UpdateMin($oAccount, $iType, $sPath, $sName, $sNewName, $oChild, $bDelete);
					}
				}
			}
		}
	}

	public function Rename($oAccount, $iType, $sPath, $sName, $sNewName)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $iType, $sPath);
			$oItem = $oDirectory->getChild($sName);
			if ($oItem !== null)
			{
				$this->UpdateMin($oAccount, $iType, $sPath, $sName, $sNewName, $oItem);
				$oItem->setName($sNewName);
				return true;
			}
		}
		return false;
	}
	
	public function Copy($oAccount, $iFromType, $iToType, $sFromPath, $sToPath, $sName, $sNewName, $bMove = false)
	{
		if ($this->Init($oAccount))
		{
			$oMinMan = $this->GetMinManager();

			if (empty($sNewName) && !is_numeric($sNewName))
			{
				$sNewName = $sName;
			}

			$sFromRootPath = $this->getRootPath($oAccount, $iFromType, true);
			$sToRootPath = $this->getRootPath($oAccount, $iToType, true);

			$oFromDirectory = $this->getDirectory($oAccount, $iFromType, $sFromPath);
			$oToDirectory = $this->getDirectory($oAccount, $iToType, $sToPath);

			if ($oToDirectory && $oFromDirectory)
			{
				$oItem = $oFromDirectory->getChild($sName);
				if ($oItem !== null)
				{
					if ($oItem instanceof \afterlogic\DAV\FS\File)
					{
						$oToDirectory->createFile($sNewName, $oItem->get());

						$oItemNew = $oToDirectory->getChild($sNewName);
						$aProps = $oItem->getProperties(array());
						if (!$bMove)				
						{
							$aProps['Owner'] = $oAccount->Email;
						}
						else
						{
							$sChildPath = substr(dirname($oItem->getPath()), strlen($sFromRootPath));
							$sID = $this->GenerateShareHash($oAccount, $iFromType, $sChildPath, $oItem->getName());

							$sNewChildPath = substr(dirname($oItemNew->getPath()), strlen($sToRootPath));

							$mMin = $oMinMan->GetMinByID($sID);
							if (!empty($mMin['__hash__']))
							{
								$sNewID = $this->GenerateShareHash($oAccount, $iToType, $sNewChildPath, $oItemNew->getName());

								$mMin['Path'] = $sNewChildPath;
								$mMin['Type'] = $iToType;
								$mMin['Name'] = $oItemNew->getName();

								$oMinMan->UpdateMinByID($sID, $mMin, $sNewID);
							}					
						}
						$oItemNew->updateProperties($aProps);
					}
					if ($oItem instanceof \afterlogic\DAV\FS\Directory)
					{
						$oToDirectory->createDirectory($sNewName);
						$oChildren = $oItem->getChildren();
						foreach ($oChildren as $oChild)
						{
							$sChildNewName = $this->GetNonExistingFileName($oAccount, $iToType, $sToPath . '/' . $sNewName, $oChild->getName());
							$this->Copy($oAccount, $iFromType, $iToType, $sFromPath . '/' . $sName, $sToPath . '/' . $sNewName, $oChild->getName(), $sChildNewName, $bMove);
						}
					}
					if ($bMove)
					{
						$oItem->delete();
					}
					return true;
				}
			}
		}
		return false;
	}

	public function GetRealQuota($oAccount, $iType)
	{
		$iUsageSize = 0;
		$iFreeSize = 0;
		
		if ($oAccount)
		{
			$sRootPath = $this->getRootPath($oAccount, EFileStorageType::Private_, true);
			$aSize = \api_Utils::GetDirectorySize($sRootPath);
			$iUsageSize += (int) $aSize['size'];

			$sRootPath = $this->getRootPath($oAccount, EFileStorageType::Corporate, true);
			$aSize = \api_Utils::GetDirectorySize($sRootPath);
			$iUsageSize += (int) $aSize['size'];

			$oApiTenants = \CApi::Manager('tenants');
			if ($oApiTenants)
			{
				$oTenant = $oApiTenants->GetTenantById($oAccount->IdTenant);
				if ($oTenant)
				{
					$iFreeSize = ($oTenant->FilesUsageDynamicQuotaInMB * 1024 * 1024) - $iUsageSize;
				}
			}
		}		
		return array($iUsageSize, $iFreeSize);
	}
	
	public function GetNonExistingFileName($oAccount, $iType, $sPath, $sFileName)
	{
		$iIndex = 0;
		$sFileNamePathInfo = pathinfo($sFileName);
		$sUploadNameExt = '';
		$sUploadNameWOExt = $sFileName;
		if (isset($sFileNamePathInfo['extension']))
		{
			$sUploadNameExt = '.'.$sFileNamePathInfo['extension'];
		}

		if (isset($sFileNamePathInfo['filename']))
		{
			$sUploadNameWOExt = $sFileNamePathInfo['filename'];
		}

		while ($this->FileExists($oAccount, $iType, $sPath, $sFileName))
		{
			$sFileName = $sUploadNameWOExt.'_'.$iIndex.$sUploadNameExt;
			$iIndex++;
		}

		return $sFileName;
	}
	
	public function ClearPrivateFiles($oAccount)
	{
		if ($oAccount)
		{
			$sRootPath = $this->getRootPath($oAccount, EFileStorageType::Private_, true);
			api_Utils::RecRmdir($sRootPath);
		}
	}

	public function ClearCorporateFiles($oAccount)
	{
	}
}

