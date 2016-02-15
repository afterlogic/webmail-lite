<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @internal
 * 
 * @package Filestorage
 * @subpackage Storages
 */
class CApiFilestorageSabredavStorage extends CApiFilestorageStorage
{
	/**
	 * @var bool
	 */
	protected $initialized;
	
	/**
	 * @var $oApiMinManager \CApiMinManager
	 */
	protected $oApiMinManager = null;

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
	 * 
	 * @return string
	 */
	public function generateShareHash($oAccount, $sType, $sPath, $sFileName)
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
	
	public function getApiMinManager()
	{
		if ($this->oApiMinManager === null)
		{
			$this->oApiMinManager = \CApi::Manager('min');
		}
		
		return $this->oApiMinManager;
	}
	
	/**
	 * @param CAccount $oAccount
	 *
	 * @return bool
	 */
	public function init($oAccount)
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
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param bool $bUser
	 *
	 * @return string|null
	 */
	protected function getRootPath($oAccount, $sType, $bUser = false)
	{
		$sRootPath = null;
		if ($oAccount)
		{
			$sUser = $bUser ? '/' . $oAccount->Email : '';
			$sRootPath = \afterlogic\DAV\FS\Plugin::GetFilesPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_PERSONAL . $sUser;

			if ($sType === \EFileStorageTypeStr::Corporate)
			{
				$iTenantId = $oAccount ? $oAccount->IdTenant : 0;

				$sTenant = $bUser ? $sTenant = '/' . $iTenantId : '';
				$sRootPath = \afterlogic\DAV\FS\Plugin::GetFilesPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_CORPORATE . $sTenant;
			}
			else if ($sType === \EFileStorageTypeStr::Shared)
			{
				$sRootPath = \afterlogic\DAV\FS\Plugin::GetFilesPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_SHARED . $sUser;
			}
		}

		return $sRootPath;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 *
	 * @return afterlogic\DAV\FS\Directory|null
	 */
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function isFileExists($oAccount, $sType, $sPath, $sName)
	{
		$bResult = false;
		if ($this->init($oAccount))
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 *
	 * @return string|null
	 */
	public function getSharedFile($oAccount, $sType, $sPath, $sName)
	{
		$sResult = null;
		if ($this->init($oAccount))
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 *
	 * @return CFileStorageItem|null
	 */
	public function getFileInfo($oAccount, $sType, $sPath, $sName)
	{
		$oResult = null;
		if ($this->init($oAccount))
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
				}
			}
		}

		return $oResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 *
	 * @return afterlogic\DAV\FS\Directory|null
	 */
	public function getDirectoryInfo($oAccount, $sType, $sPath)
	{
		$sResult = null;
		if ($this->init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			if ($oDirectory !== null && $oDirectory instanceof afterlogic\DAV\FS\Directory)
			{
				$sResult = $oDirectory->getChildrenProperties();
			}
		}

		return $sResult;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 *
	 * @return afterlogic\DAV\FS\File|null
	 */
	public function getFile($oAccount, $sType, $sPath, $sName)
	{
		$sResult = null;
		if ($this->init($oAccount))
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 *
	 * @return string|false
	 */
	public function createPublicLink($oAccount, $sType, $sPath, $sName, $sSize, $bIsFolder)
	{
		$mResult = false;

		$sID = implode('|', array($oAccount->IdAccount,	$sType, $sPath, $sName));
		$oMin = $this->getApiMinManager();
		$mMin = $oMin->getMinByID($sID);
		if (!empty($mMin['__hash__']))
		{
			$mResult = $mMin['__hash__'];
		}
		else
		{
			$mResult = $oMin->createMin($sID, array(
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function deletePublicLink($oAccount, $sType, $sPath, $sName)
	{
		$sID = implode('|', array($oAccount->IdAccount,	$sType, $sPath, $sName));

		$oMin = $this->getApiMinManager();

		return $oMin->deleteMinByID($sID);
	}
	
	public function parseIniString($sIniString) 
	{
		$aResult = array(); 
		foreach (explode("\n", $sIniString) as $sLine) 
		{
			$aValues = explode("=", $sLine, 2);
			if (isset($aValues[0], $aValues[1]))
			{
				$aResult[$aValues[0]] = trim(rtrim($aValues[1], "\r"), "\"");
			}
		}
		return $aResult;
	}	

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sPattern
	 *
	 * @return array
	 */
	public function getFiles($oAccount, $sType = \EFileStorageTypeStr::Personal, $sPath = '', $sPattern = '')
	{
		$oDirectory = null;
		$aItems = array();
		$aResult = array();
		$oMin = $this->getApiMinManager();
		
		if ($oAccount && $this->init($oAccount))
		{
			$oTenant = null;
			$oApiTenants = \CApi::Manager('tenants');
			if ($oApiTenants)
			{
				$oTenant = (0 < $oAccount->IdTenant) ? $oApiTenants->getTenantById($oAccount->IdTenant) :
					$oApiTenants->getDefaultGlobalTenant();
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
					$aProps = $oValue->getProperties(array('Owner', 'Shared'));
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
						$sID = $this->generateShareHash($oAccount, $sType, $sFilePath, $oValue->getName());
						$oItem->IsFolder = true;
					}

					if ($oValue instanceof \afterlogic\DAV\FS\File)
					{
						$sID = $this->generateShareHash($oAccount, $sType, $sFilePath, $oValue->getName());
						$oItem->IsFolder = false;
						$oItem->Size = $oValue->getSize();
						$oFileInfo = null;
						$oEmbedFileInfo = null;
								
						
						$aPathInfo = pathinfo($oItem->Name);
						if (isset($aPathInfo['extension']) && strtolower($aPathInfo['extension']) === 'url')
						{
							$aUrlFileInfo = $this->parseIniString(stream_get_contents($oValue->get()));
							if ($aUrlFileInfo && isset($aUrlFileInfo['URL']))
							{
								$oItem->IsLink = true;
								$iLinkType = api_Utils::GetLinkType($aUrlFileInfo['URL']);
								$oItem->LinkType = $iLinkType;
								$oItem->LinkUrl = $aUrlFileInfo['URL'];
								if (isset($iLinkType) && $oTenant)
								{
									$oEmbedFileInfo = \api_Utils::GetOembedFileInfo($oItem->LinkUrl);
									if(\EFileStorageLinkType::GoogleDrive === $iLinkType)
									{
										$oSocial = $oTenant->getSocialByName('google');
										if ($oSocial)
										{
											$oFileInfo = \api_Utils::GetGoogleDriveFileInfo($aUrlFileInfo['URL'], $oSocial->SocialApiKey);
											if ($oFileInfo)
											{
												$oItem->Name = isset($oFileInfo->title) ? $oFileInfo->title : $oItem->Name;
												$oItem->Size = isset($oFileInfo->fileSize) ? $oFileInfo->fileSize : $oItem->Size;
											}
										}
									}
									else if ($oEmbedFileInfo)
									{
										$oFileInfo = $oEmbedFileInfo;
										$oItem->Name = isset($oFileInfo->title) ? $oFileInfo->title : $oItem->Name;
										$oItem->Size = isset($oFileInfo->fileSize) ? $oFileInfo->fileSize : $oItem->Size;
										$oItem->OembedHtml = isset($oFileInfo->html) ? $oFileInfo->html : $oItem->OembedHtml;
									}
									else/* if (\EFileStorageLinkType::DropBox === (int)$aProps['LinkType'])*/
									{
										if (\EFileStorageLinkType::DropBox === $iLinkType)
										{
											$aUrlFileInfo['URL'] = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $aUrlFileInfo['URL']);
										}

										$oItem->Name = isset($aPathInfo['filename']) ? $aPathInfo['filename'] : basename($aUrlFileInfo['URL']);
										$aRemoteFileInfo = \api_Utils::GetRemoteFileInfo($aUrlFileInfo['URL']);

										$oItem->Size = $aRemoteFileInfo['size'];
									}
								}
							}
							else
							{
								$oItem->IsLink = false;
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
							$iItemLinkType = $oItem->LinkType;
							if ($oItem->IsLink && $iItemLinkType === \EFileStorageLinkType::GoogleDrive && isset($oFileInfo) && isset($oFileInfo->thumbnailLink))
							{
								$oItem->Thumb = true;
								$oItem->ThumbnailLink = $oFileInfo->thumbnailLink;
							}
							else if ($oItem->IsLink && $oEmbedFileInfo)
							{
								$oItem->Thumb = true;
								$oItem->ThumbnailLink = $oFileInfo->thumbnailLink;
							}
							else 
							{
								$oItem->Thumb = $oItem->Size < $iThumbnailLimit && \api_Utils::IsGDImageMimeTypeSuppoted($oItem->ContentType, $oItem->Name);
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
					
					$mMin = $oMin->getMinByID($sID);

					$oItem->Shared = isset($aProps['Shared']) ? $aProps['Shared'] : empty($mMin['__hash__']) ? false : true;
					$oItem->Owner = isset($aProps['Owner']) ? $aProps['Owner'] : $oAccount->Email;
					
					if ($oItem && '.asc' === \strtolower(\substr(\trim($oItem->Name), -4)))
					{
						$mResult = $this->getFile($oAccount, $oItem->Type, $oItem->Path, $oItem->Name);

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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sFolderName
	 *
	 * @return bool
	 */
	public function createFolder($oAccount, $sType, $sPath, $sFolderName)
	{
		if ($this->init($oAccount))
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sLink
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function createLink($oAccount, $sType, $sPath, $sLink, $sName)
	{
		$iLinkType = \api_Utils::GetLinkType($sLink);
		if (/*\EFileStorageLinkType::Unknown !== $iLinkType && */$this->init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);

			if ($oDirectory !== null)
			{
				$sFileName = /*\Sabre\VObject\UUIDUtil::getUUID()*/ $sName . '.url';
				
				$oDirectory->createFile($sFileName, "[InternetShortcut]\r\nURL=\"" . $sLink . "\"\r\n");
				$oItem = $oDirectory->getChild($sFileName);
				$oItem->updateProperties(array(
					'Owner' => $oAccount->Email
				));
				
				return true;
			}
		}

		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sFileName
	 * @param string $sData
	 *
	 * @return bool
	 */
	public function createFile($oAccount, $sType, $sPath, $sFileName, $sData)
	{
		if ($this->init($oAccount))
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function delete($oAccount, $sType, $sPath, $sName)
	{
		if ($this->init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			$oItem = $oDirectory->getChild($sName);
			if ($oItem !== null)
			{
				if ($oItem instanceof \afterlogic\DAV\FS\Directory)
				{
					$this->updateMin($oAccount, $sType, $sPath, $sName, $sName, $oItem, true);
				}
				$oItem->delete();
				return true;
			}
		}

		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 * @param string $sNewName
	 * @param afterlogic\DAV\FS\File|afterlogic\DAV\FS\Directory
	 * @param bool $bDelete Default value is **false**.
	 *
	 * @return bool
	 */
	public function updateMin($oAccount, $sType, $sPath, $sName, $sNewName, $oItem, $bDelete = false)
	{
		if ($oAccount)
		{
			$oApiMinManager = $this->getApiMinManager();

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
						$sID = $this->generateShareHash($oAccount, $sType, $sChildPath, $oChild->getName());
						if ($bDelete)
						{
							$oApiMinManager->deleteMinByID($sID);
						}
						else
						{
							$mMin = $oApiMinManager->getMinByID($sID);
							if (!empty($mMin['__hash__']))
							{
								$sNewChildPath = $sNewPath . substr($sChildPath, strlen($sOldPath));
								$sNewID = $this->generateShareHash($oAccount, $sType, $sNewChildPath, $oChild->getName());
								$mMin['Path'] = $sNewChildPath;
								$oApiMinManager->updateMinByID($sID, $mMin, $sNewID);
							}					
						}
					}
					if ($oChild instanceof \afterlogic\DAV\FS\Directory)
					{
						$this->updateMin($oAccount, $sType, $sPath, $sName, $sNewName, $oChild, $bDelete);
					}
				}
			}
		}
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 * @param string $sNewName
	 *
	 * @return bool
	 */
	public function rename($oAccount, $sType, $sPath, $sName, $sNewName)
	{
		if ($this->init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			$oItem = $oDirectory->getChild($sName);
			if ($oItem !== null)
			{
				$this->updateMin($oAccount, $sType, $sPath, $sName, $sNewName, $oItem);
				$oItem->setName($sNewName);
				return true;
			}
		}
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 * @param string $sPath
	 * @param string $sName
	 * @param string $sNewName
	 *
	 * @return bool
	 */
	public function renameLink($oAccount, $sType, $sPath, $sName, $sNewName)
	{
		if ($this->init($oAccount))
		{
			$oDirectory = $this->getDirectory($oAccount, $sType, $sPath);
			$oItem = $oDirectory->getChild($sName);
				
			if ($oItem)
			{
				$oItem->setName($sNewName . '.url');
				return true;
			}
		}
		return false;
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sFromType
	 * @param string $sToType
	 * @param string $sFromPath
	 * @param string $sToPath
	 * @param string $sName
	 * @param string $sNewName
	 * @param bool $bMove Default value is **false**.
	 *
	 * @return bool
	 */
	public function copy($oAccount, $sFromType, $sToType, $sFromPath, $sToPath, $sName, $sNewName, $bMove = false)
	{
		if ($this->init($oAccount))
		{
			$oApiMinManager = $this->getApiMinManager();

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
							$sID = $this->generateShareHash($oAccount, $sFromType, $sChildPath, $oItem->getName());

							$sNewChildPath = substr(dirname($oItemNew->getPath()), strlen($sToRootPath));

							$mMin = $oApiMinManager->getMinByID($sID);
							if (!empty($mMin['__hash__']))
							{
								$sNewID = $this->generateShareHash($oAccount, $sToType, $sNewChildPath, $oItemNew->getName());

								$mMin['Path'] = $sNewChildPath;
								$mMin['Type'] = $sToType;
								$mMin['Name'] = $oItemNew->getName();

								$oApiMinManager->updateMinByID($sID, $mMin, $sNewID);
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
							$sChildNewName = $this->getNonExistingFileName($oAccount, $sToType, $sToPath . '/' . $sNewName, $oChild->getName());
							$this->copy($oAccount, $sFromType, $sToType, $sFromPath . '/' . $sName, $sToPath . '/' . $sNewName, $oChild->getName(), $sChildNewName, $bMove);
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

	/**
	 * @param CAccount $oAccount
	 * @param string $sType
	 *
	 * @return array
	 */
	public function getRealQuota($oAccount, $sType)
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
				$oTenant = $oApiTenants->getTenantById($oAccount->IdTenant);
				if ($oTenant)
				{
					$iFreeSize = ($oTenant->FilesUsageDynamicQuotaInMB * 1024 * 1024) - $iUsageSize;
				}
			}
		}		
		return array($iUsageSize, $iFreeSize);
	}

	/**
	 * @param CAccount $oAccount
	 * @param int $iType
	 * @param string $sPath
	 * @param string $sFileName
	 *
	 * @return string
	 */
	public function getNonExistingFileName($oAccount, $iType, $sPath, $sFileName)
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

		while ($this->isFileExists($oAccount, $iType, $sPath, $sFileName))
		{
			$sFileName = $sUploadNameWOExt.'_'.$iIndex.$sUploadNameExt;
			$iIndex++;
		}

		return $sFileName;
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function clearPrivateFiles($oAccount)
	{
		if ($oAccount)
		{
			$sRootPath = $this->getRootPath($oAccount, \EFileStorageTypeStr::Personal, true);
			api_Utils::RecRmdir($sRootPath);
		}
	}

	/**
	 * @param CAccount $oAccount
	 */
	public function clearCorporateFiles($oAccount)
	{
	}
}

