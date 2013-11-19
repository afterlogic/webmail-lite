<?php
namespace afterlogic\DAV\FS;

class Directory extends \Sabre\DAV\FSExt\Directory {
    
	/**
	 * @var \Sabre\DAV\Auth\Plugin
	 */
	public $authPlugin;
	
	/**
	 * @var \CApiTenantsManager
	 */
	protected $oApiTenants = null;	
	
	/**
	 * @var \CApiUsersManager
	 */
	protected $oApiUsers = null;	
	
	/**
	 * @var \CAccount
	 */
	protected $oAccount = null;	
	
	/**
	 * @var \CTenant
	 */
	protected $oTenant = null;
	
	/**
     * Constructor
     *
     * @param \Sabre\DAV\Auth\Plugin $authPlugin
     * @param string $path
     */
    public function __construct(\Sabre\DAV\Auth\Plugin $authPlugin, $path) {

		$this->authPlugin = $authPlugin;
		$this->path = $path;
    }
	
	public function getTenantsMan()
	{
		if ($this->oApiTenants == null)
		{
			$this->oApiTenants = \CApi::Manager('tenants');
		}
		return $this->oApiTenants;
	}

	public function getUsersMan()
	{
		if ($this->oApiUsers == null)
		{
			$this->oApiUsers = \CApi::Manager('users');
		}
		return $this->oApiUsers;
	}
	
	public function getAccount()
	{
		if ($this->oAccount == null)
		{
			$sUser = $this->authPlugin->getCurrentUser();
			$oUsersMan = $this->getUsersMan();
			$this->oAccount = $oUsersMan->GetAccountOnLogin($sUser);
		}
		
		return $this->oAccount;
	}
	
	public function getTenant()
	{
		if ($this->oTenant == null)
		{
			$oAccount = $this->getAccount();
			if ($oAccount !== null)
			{
				$oApiTenants = $this->getTenantsMan();
				if ($oApiTenants)
				{
					$this->oTenant = $oApiTenants->GetTenantById($oAccount->IdTenant);
				}
			}
		}
		
		return $this->oTenant;
	}
	
	public function initPath() {
		
    }

	public function getPath() {

        return $this->path;

    }

    public function createDirectory($name) {

		$this->initPath();
		
        if ($name=='.' || $name=='..') throw new DAV\Exception\Forbidden('Permission denied to . and ..');
        $newPath = $this->path . '/' . $name;
		
		if (!is_dir($newPath))
		{
			mkdir($newPath, 0777, true);
		}
    }

	public function createFile($name, $data = null) {

		$this->initPath();
		
		parent::createFile($name, $data);

		$oFile = $this->getChild($name);
		$aProps = $oFile->getProperties(array('Owner'));
		
		if (!isset($aProps['Owner']))
		{
			$oAccount = $this->getAccount();
			if ($oAccount)
			{
				$aProps['Owner'] = $oAccount->Email;
			}
		}
		
		$oFile->updateProperties($aProps);

		if (!$this->updateQuota())
		{
			$oFile->delete();
			throw new \Sabre\DAV\Exception\InsufficientStorage();
		}
    }

    public function getChild($name) {

		$this->initPath();
		
        $path = $this->path . '/' . trim($name, '/');

        if (!file_exists($path)) throw new \Sabre\DAV\Exception\NotFound('File with name ' . $path . ' could not be located');

        if (is_dir($path)) {

            return new Directory($this->authPlugin, $path);

        } else {

            return new File($this->authPlugin, $path);

        }

    }	
	
	public function getChildren() {

		$this->initPath();
		
		$nodes = array();
		
		if(!file_exists($this->path))
		{
			mkdir($this->path);
		}
		
        foreach(scandir($this->path) as $node) 
			if($node!='.' && $node!='..' && $node!== '.sabredav' && $node!== API_HELPDESK_PUBLIC_NAME) 
			{
				$nodes[] = $this->getChild($node);
			}
        return $nodes;

    }
	
    public function childExists($name) {

		$this->initPath();
		
		return parent::childExists($name);

    }

    public function delete() {

		$this->initPath();
		
		parent::delete();
		
		$this->updateQuota();
    }	
	
	public function Search($pattern, $path = null) {

		$this->initPath();
		
		$aResult = array();
		if ($path === null)	
		{
			$path = $this->path;
		}
		$aItems = \api_Utils::SearchFiles($path, $pattern);
		if ($aItems)
		{
			foreach ($aItems as $sItem)
			{
				if (is_dir($sItem))
				{
					$aResult[] = new Directory($this->authPlugin, $sItem);
				}
				else
				{
					$aResult[] = new File($this->authPlugin, $sItem);
				}
			}
		}
		
		return $aResult;
	}
	
	protected function getRootPath($iType)
	{
		$sRootPath = '';
		$oAccount = $this->getAccount();
		if ($oAccount)
		{
			if ($iType === \EFileStorageType::Corporate)
			{
				$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
					\afterlogic\DAV\Constants::FILESTORAGE_PATH_CORPORATE . '/' . $oAccount->IdTenant;
			}		
			else
			{
				$sRootPath = \CApi::DataPath() . \afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . 
						\afterlogic\DAV\Constants::FILESTORAGE_PATH_PRIVATE . '/' . $oAccount->Email;
			}
		}
		
		return $sRootPath;
	}
	
	public function getFullQuotaInfo()
	{
		$iUsageSize = 0;
		$iFreeSize = 0;

		$sRootPath = $this->getRootPath(\EFileStorageType::Private_);
		$aSize = \api_Utils::GetDirectorySize($sRootPath);
		$iUsageSize = (int) $aSize['size'] + $iUsageSize;

		$sRootPath = $this->getRootPath(\EFileStorageType::Corporate);
		$aSize = \api_Utils::GetDirectorySize($sRootPath);
		$iUsageSize = (int) $aSize['size'] + $iUsageSize;

		$oAccount = $this->getAccount();
		if ($oAccount)
		{
			$oTenant = $this->getTenant();
			if ($oTenant)
			{
				$iFreeSize = ($oTenant->FilesUsageDynamicQuotaInMB * 1024 * 1024) - $iUsageSize;
			}
		}
		
		return array($iUsageSize, $iFreeSize);
	}
	
	public function updateQuota()
	{
		if (isset($GLOBALS['__FILESTORAGE_MOVE_ACTION__']) && $GLOBALS['__FILESTORAGE_MOVE_ACTION__']) return true;
		
		$iSizeUsage = 0;
		$aQuota = $this->getFullQuotaInfo();
		if (isset($aQuota[0]))
		{
			$iSizeUsage = $aQuota[0];
		}
		$oTenant = $this->getTenant();
		if (!isset($oTenant))
		{
			return true;
		}
		else
		{
			$oTenantsMan = $this->getTenantsMan();
			return $oTenantsMan->TryToAllocateFileUsage($oTenant, $iSizeUsage);
		}
	}

}