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
class CApiFilestorageSabredavStorage extends CApiFilestorageStorage
{
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
	}

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sFileName
	 * @return string
	 */
	public function GenerateShareHash($oAccount, $sType, $sPath, $sFileName)
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

		return implode('|', array($sId, $sType, $sPath, $sFileName));
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
				\afterlogic\DAV\Auth\Backend::getInstance()->setCurrentUser($oAccount->Email);
				\afterlogic\DAV\Utils::CheckPrincipals($oAccount->Email);
				$this->initialized = true;
			}
			$bResult = true;
		}
		
		return $bResult;
	}	

	/**
	 * @param CAccount|CHelpdeskUser $oAccount
	 * @param string $sType
	 * @param bool $bUser
	 * @return string
	 */
	protected function getRootPath($oAccount, $sType, $bUser = false)
	{
		$sRootPath = null;
		if ($oAccount)
		{
			$sUser = $bUser ? '/' . $oAccount->Email : '';
			$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_PERSONAL . $sUser;

			if ($sType === \EFileStorageTypeStr::Corporate)
			{
				$iTenantId = $oAccount ? $oAccount->IdTenant : 0;

				$sTenant = $bUser ? $sTenant = '/' . $iTenantId : '';
				$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_CORPORATE . $sTenant;
			}
			else if ($sType === \EFileStorageTypeStr::Shared)
			{
				$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_SHARED . $sUser;
			}
		}		
		return $sRootPath;
	}
	
	protected function getDirectory($oAccount, $sType, $sPath = '')
	{
		$oDirectory = null;
		
		if ($oAccount)
		{
			$sRootPath = $this->getRootPath($oAccount, $sType);
			
			if ($sType === \EFileStorageTypeStr::Personal)
			{
				$oDirectory = new \afterlogic\DAV\FS\RootPersonal($sRootPath);
			}
			if ($sType === \EFileStorageTypeStr::Corporate)
			{
				$oDirectory = new \afterlogic\DAV\FS\RootPublic($sRootPath);
			}	
			if ($sType === \EFileStorageTypeStr::Shared)
			{
				$oDirectory = new \afterlogic\DAV\FS\RootShared($sRootPath);
			}	
			if ($oDirectory && $sPath !== '')
			{
				$oDirectory = $oDirectory->getChild($sPath);
			}
		}		
		return $oDirectory;
	}
	
	public function FileExists($oAccount, $sType, $sPath, $sName)
	{
		$bResult = false;
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
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
	
	public function GetSharedFile($oAccount, $sType, $sPath, $sName)
	{
		$sResult = null;
		if ($this->Init($oAccount))
		{
			$sRootPath = $this->getRootPath($oAccount, $sType, true);
			$FilePath = $sRootPath . '/' . $sPath . '/' . $sName;
			if (file_exists($FilePath))
			{
				$sResult = fopen($FilePath, 'r');
			}
		}
		
		return $sResult;
	}

	public function GetFileInfo($oAccount, $sType, $sPath, $sName)
	{
		$oResult = null;
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			if ($oDirectory !== null)
			{
				$oItem = $oDirectory->getChild($sName);
				if ($oItem !== null)
				{
					$aProps = $oItem->getProperties(false);
					$oResult = new \CFileStorageItem();
					if (isset($aProps['Owner']))
					{
						$oResult->Owner = $aProps['Owner'];
					}
					$oResult->Path = $sPath;
					$oResult->Type = $sType;
					$oResult->Name = $sName;
					if (isset($aProps['Link']))
					{
        				$oResult->Name = isset($aProps['Name']) ? $aProps['Name'] : $oResult->Name;
						$oResult->IsLink = true;
						$oResult->LinkUrl = $aProps['Link'];
						$oResult->LinkType = (int) $aProps['LinkType'];
					}
				}
			}
		}
		return $oResult;
	}
	
	public function GetDirectoryInfo($oAccount, $sType, $sPath)
	{
		$sResult = null;
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			if ($oDirectory !== null && $oDirectory instanceof afterlogic\DAV\FS\Directory)
			{
				$sResult = $oDirectory->getChildrenProperties();
			}
		}
		return $sResult;
	}

	public function GetFile($oAccount, $sType, $sPath, $sName)
	{
		$sResult = null;
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
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

	public function CreatePublicLink($oAccount, $sType, $sPath, $sName, $sSize, $bIsFolder)
	{
		$sID = implode('|', array($oAccount->IdAccount,	$sType, $sPath, $sName));

		$mResult = false;
		
		$oMin = $this->GetMinManager();
		$mMin = $oMin->GetMinByID($sID);
		if (!empty($mMin['__hash__']))
		{
			$mResult = $mMin['__hash__'];
		}
		else
		{
			$mResult = $oMin->CreateMin($sID, array(
					'Account' => $oAccount->IdAccount,
					'Type' => $sType, 
					'Path' => $sPath, 
					'Name' => $sName,
					'Size' => $sSize,
					'IsFolder' => $bIsFolder
				)
			);
		}
		
		$bServerUseUrlRewrite = \CApi::GetConf('labs.server-use-url-rewrite', false);
		$sUrl =	$bIsFolder ? '?files-pub=' : ($bServerUseUrlRewrite ? 'share/' : '?/Min/Share/');

		return \api_Utils::GetAppUrl() . $sUrl . $mResult;
	}	
	
	public function DeletePublicLink($oAccount, $sType, $sPath, $sName)
	{
		$sID = implode('|', array($oAccount->IdAccount,	$sType, $sPath, $sName));

		$oMin = $this->GetMinManager();

		return $oMin->DeleteMinByID($sID);
	}
	
	public function GetFiles($oAccount, $sType = \EFileStorageTypeStr::Personal, $sPath = '', $sPattern = '')
	{
		$oDirectory = null;
		$aItems = array();
		$aResult = array();
		$oMin = $this->GetMinManager();
		
		if ($oAccount && $this->Init($oAccount))
		{
			$oTenant = null;
			$oApiTenants = \CApi::Manager('tenants');
			if ($oApiTenants)
			{
				$oTenant = (0 < $oAccount->IdTenant) ? $oApiTenants->GetTenantById($oAccount->IdTenant) :
					$oApiTenants->GetDefaultGlobalTenant();
			}

			$sRootPath = $this->getRootPath($oAccount, $sType, true);
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			if ($oDirectory !== null)
			{
				if (!empty($sPattern) || is_numeric($sPattern))
				{
					$aItems = $oDirectory->Search($sPattern);
					$aDirectoryInfo = $oDirectory->getChildrenProperties();
					foreach ($aDirectoryInfo as $oDirectoryInfo)
					{
						if (isset($oDirectoryInfo['Link']) && strpos($oDirectoryInfo['Name'], $sPattern) !== false)
						{
							$aItems[] = new \afterlogic\DAV\FS\File($oDirectory->getPath() . '/' . $oDirectoryInfo['@Name']);
						}
					}
				}
				else
				{
					$aItems = $oDirectory->getChildren();
				}

				$iThumbnailLimit = 1024 * 1024 * 2; // 2MB

				foreach ($aItems as $oValue) 
				{
					$sFilePath = str_replace($sRootPath, '', dirname($oValue->getPath()));
					$aProps = $oValue->getProperties(array('Owner', 'Shared', 'Name' ,'Link', 'LinkType'));
					$oItem /*@var $oItem \CFileStorageItem */ = new  \CFileStorageItem();
					
					$oItem->Type = $sType;
					$oItem->TypeStr = $sType;
					$oItem->Path = $sFilePath;
					$oItem->Name = $oValue->getName();
					$oItem->Id = $oValue->getName();
					$oItem->FullPath = $oItem->Name !== '' ? $oItem->Path . '/' . $oItem->Name : $oItem->Path ;

					$sID = '';
					if ($oValue instanceof \afterlogic\DAV\FS\Directory)
					{
						$sID = $this->GenerateShareHash($oAccount, $sType, $sFilePath, $oValue->getName());
						$oItem->IsFolder = true;
					}

					if ($oValue instanceof \afterlogic\DAV\FS\File)
					{
						$sID = $this->GenerateShareHash($oAccount, $sType, $sFilePath, $oValue->getName());
						$oItem->IsFolder = false;
						$oItem->Size = $oValue->getSize();
						$oFileInfo = null;
								
						if (isset($aProps['Link']))
						{
							$oItem->IsLink = true;
							$iLinkType = api_Utils::GetLinkType($aProps['Link']);
							$oItem->LinkType = $iLinkType;
							$oItem->LinkUrl = $aProps['Link'];
							if (isset($iLinkType) && $oTenant)
							{
								if(\EFileStorageLinkType::GoogleDrive === $iLinkType)
								{
									$oSocial = $oTenant->GetSocialByName('google');
									if ($oSocial)
									{
										$oFileInfo = \api_Utils::GetGoogleDriveFileInfo($aProps['Link'], $oSocial->SocialApiKey);
										if ($oFileInfo)
										{
											$oItem->Name = isset($oFileInfo->title) ? $oFileInfo->title : $oItem->Name;
											$oItem->Size = isset($oFileInfo->fileSize) ? $oFileInfo->fileSize : $oItem->Size;
										}
									}
								}
								else/* if (\EFileStorageLinkType::DropBox === (int)$aProps['LinkType'])*/
								{
									if (\EFileStorageLinkType::DropBox === $iLinkType)
									{
										$aProps['Link'] = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $aProps['Link']);
									}
									
									$oItem->Name = isset($aProps['Name']) ? $aProps['Name'] : basename($aProps['Link']);
									$aRemoteFileInfo = \api_Utils::GetRemoteFileInfo($aProps['Link']);

									$oItem->Size = $aRemoteFileInfo['size'];
								}
							}
						}
						else
						{
							$oItem->IsLink = false;
						}
						
						$oItem->LastModified = $oValue->getLastModified();
						$oItem->ContentType = $oValue->getContentType();
						if (!$oItem->ContentType)
						{
							$oItem->ContentType = \api_Utils::MimeContentType($oItem->Name);
						}

						if (\CApi::GetConf('labs.allow-thumbnail', true))
						{
							if ($oItem->IsLink && $oItem->LinkType === \EFileStorageLinkType::GoogleDrive 
									&& isset($oFileInfo) && isset($oFileInfo->thumbnailLink))
							{
								$oItem->Thumb = true;
								$oItem->ThumbnailLink = $oFileInfo->thumbnailLink;
							}
							else 
							{
								$oItem->Thumb = $oItem->Size < $iThumbnailLimit &&
									\api_Utils::IsGDImageMimeTypeSuppoted($oItem->ContentType, $oItem->Name);
							}
						}

						$oItem->Iframed = !$oItem->IsFolder && !$oItem->IsLink &&
							\CApi::isIframedMimeTypeSupported($oItem->ContentType, $oItem->Name);

						$oItem->Hash = \CApi::EncodeKeyValues(array(
							'Type' => $sType,
							'Path' => $sFilePath,
							'Name' => $oValue->getName(),
							'FileName' => $oValue->getName(),
							'MimeType' => $oItem->ContentType,
							'Size' => $oValue->getSize(),
							'Iframed' => $oItem->Iframed
						));
					}
					
					$mMin = $oMin->GetMinByID($sID);

					$oItem->Shared = isset($aProps['Shared']) ? $aProps['Shared'] : empty($mMin['__hash__']) ? false : true;
					$oItem->Owner = isset($aProps['Owner']) ? $aProps['Owner'] : $oAccount->Email;
					
					if ($oItem && '.asc' === \strtolower(\substr(\trim($oItem->Name), -4)))
					{
						$mResult = $this->GetFile($oAccount, $oItem->Type, $oItem->Path, $oItem->Name);

						if (is_resource($mResult))
						{
							$oItem->Content = stream_get_contents($mResult);
						}
					}
					
					$aResult[] = $oItem;
				}
			}
		}
		
		return $aResult;
	}
	
	public function CreateFolder($oAccount, $sType, $sPath, $sFolderName)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);

			if ($oDirectory !== null)
			{
				$oDirectory->createDirectory($sFolderName);
				return true;
			}
		}
		return false;
	}
	
	public function CreateLink($oAccount, $sType, $sPath, $sLink, $sName)
	{
		$iLinkType = \api_Utils::GetLinkType($sLink);
		if (/*\EFileStorageLinkType::Unknown !== $iLinkType && */$this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);

			if ($oDirectory !== null)
			{
				$sFileName = \Sabre\VObject\UUIDUtil::getUUID();
				$oDirectory->createFile($sFileName);
				$oItem = $oDirectory->getChild($sFileName);
				$oItem->updateProperties(array(
					'Owner' => $oAccount->Email,
					'Name' => $sName,
					'Link' => $sLink,
					'LinkType' => $iLinkType
				));
				
				return true;
			}
		}
		return false;
	}
	
	public function CreateFile($oAccount, $sType, $sPath, $sFileName, $sData)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);

			if ($oDirectory !== null)
			{
				$oDirectory->createFile($sFileName, $sData);
				return true;
			}
		}
		return false;
	}

	public function Delete($oAccount, $sType, $sPath, $sName)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			$oItem = $oDirectory->getChild($sName);
			if ($oItem !== null)
			{
				if ($oItem instanceof \afterlogic\DAV\FS\Directory)
				{
					$this->UpdateMin($oAccount, $sType, $sPath, $sName, $sName, $oItem, true);
				}
				$oItem->delete();
				return true;
			}
		}
		return false;
	}

	public function UpdateMin($oAccount, $sType, $sPath, $sName, $sNewName, $oItem, $bDelete = false)
	{
		if ($oAccount)
		{
			$oMinMan = $this->GetMinManager();

			$sRootPath = $this->getRootPath($oAccount, $sType, true);

			$sOldPath = $sPath . '/' . $sName;
			$sNewPath = $sPath . '/' . $sNewName;

			if ($oItem instanceof \afterlogic\DAV\FS\Directory)
			{
				foreach ($oItem->getChildren() as $oChild)
				{
					if ($oChild instanceof \afterlogic\DAV\FS\File)
					{
						$sChildPath = substr(dirname($oChild->getPath()), strlen($sRootPath));
						$sID = $this->GenerateShareHash($oAccount, $sType, $sChildPath, $oChild->getName());
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
								$sNewID = $this->GenerateShareHash($oAccount, $sType, $sNewChildPath, $oChild->getName());
								$mMin['Path'] = $sNewChildPath;
								$oMinMan->UpdateMinByID($sID, $mMin, $sNewID);
							}					
						}
					}
					if ($oChild instanceof \afterlogic\DAV\FS\Directory)
					{
						$this->UpdateMin($oAccount, $sType, $sPath, $sName, $sNewName, $oChild, $bDelete);
					}
				}
			}
		}
	}

	public function Rename($oAccount, $sType, $sPath, $sName, $sNewName)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			$oItem = $oDirectory->getChild($sName);
			if ($oItem !== null)
			{
				$this->UpdateMin($oAccount, $sType, $sPath, $sName, $sNewName, $oItem);
				$oItem->setName($sNewName);
				return true;
			}
		}
		return false;
	}

	public function RenameLink($oAccount, $sType, $sPath, $sName, $sNewName)
	{
		if ($this->Init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			$oItem = $oDirectory->getChild($sName);

			if ($oItem)
			{
				$oItem->updateProperties(array(
					'Name' => $sNewName
				));
				return true;
			}
		}
		return false;
	}
	
	public function Copy($oAccount, $sFromType, $sToType, $sFromPath, $sToPath, $sName, $sNewName, $bMove = false)
	{
		if ($this->Init($oAccount))
		{
			$oMinMan = $this->GetMinManager();

			if (empty($sNewName) && !is_numeric($sNewName))
			{
				$sNewName = $sName;
			}

			$sFromRootPath = $this->getRootPath($oAccount, $sFromType, true);
			$sToRootPath = $this->getRootPath($oAccount, $sToType, true);

			$oFromDirectory = $this->getDirectory($oAccount, $sFromType, $sFromPath);
			$oToDirectory = $this->getDirectory($oAccount, $sToType, $sToPath);

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
							$sID = $this->GenerateShareHash($oAccount, $sFromType, $sChildPath, $oItem->getName());

							$sNewChildPath = substr(dirname($oItemNew->getPath()), strlen($sToRootPath));

							$mMin = $oMinMan->GetMinByID($sID);
							if (!empty($mMin['__hash__']))
							{
								$sNewID = $this->GenerateShareHash($oAccount, $sToType, $sNewChildPath, $oItemNew->getName());

								$mMin['Path'] = $sNewChildPath;
								$mMin['Type'] = $sToType;
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
							$sChildNewName = $this->GetNonExistingFileName($oAccount, $sToType, $sToPath . '/' . $sNewName, $oChild->getName());
							$this->Copy($oAccount, $sFromType, $sToType, $sFromPath . '/' . $sName, $sToPath . '/' . $sNewName, $oChild->getName(), $sChildNewName, $bMove);
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

	public function GetRealQuota($oAccount, $sType)
	{
		$iUsageSize = 0;
		$iFreeSize = 0;
		
		if ($oAccount)
		{
			$sRootPath = $this->getRootPath($oAccount, \EFileStorageTypeStr::Personal, true);
			$aSize = \api_Utils::GetDirectorySize($sRootPath);
			$iUsageSize += (int) $aSize['size'];

			$sRootPath = $this->getRootPath($oAccount, \EFileStorageTypeStr::Corporate, true);
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
			$sRootPath = $this->getRootPath($oAccount, \EFileStorageTypeStr::Personal, true);
			api_Utils::RecRmdir($sRootPath);
		}
	}

	public function ClearCorporateFiles($oAccount)
	{
	}
}

