<?php
namespace afterlogic\DAV\FS;

class Plugin extends \Sabre\DAV\ServerPlugin {

    /**
     * Server class
     *
     * @var \Sabre\DAV\Server
     */
    protected $server;

    /**
     * @var \CAccount
     */
    protected $oAccount = null;
	
	/**
	 * @var \CApiFilestorageManager
	 */
    protected $oApiFilestorage = null;
	
	/**
	 * @var \CApiTenantsManager
	 */
	protected $oApiTenants = null;	
	
	/**
	 * @var \CApiMinManager
	 */
	protected $oApiMin = null;
	
	/**
	 * @var \CApiUsersManager
	 */
	protected $oApiUsers= null;	
	
	protected $sOldPath = null;
	protected $sOldID = null;

	protected $sNewPath = null;
	protected $sNewID = null;

	public function getFilestorageMan()
	{
		if ($this->oApiFilestorage == null)
		{
			$this->oApiFilestorage = \CApi::Manager('filestorage', 'sabredav');
		}
		return $this->oApiFilestorage;
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

	public function getMinMan()
	{
		if ($this->oApiMin == null)
		{
			$this->oApiMin = \CApi::Manager('min');
		}
		return $this->oApiMin;
	}
	
    public function getAccount() {
		if (!isset($this->oAccount) && isset($this->server))
		{
			$this->oAccount = $this->server->getAccount();;
		}
		return $this->oAccount; 
	}
	
	/**
     * Initializes the plugin
     *
     * @param \Sabre\DAV\Server $server
     * @return void
     */
    public function initialize(\Sabre\DAV\Server $server) {

        $this->server = $server;
		$this->server->subscribeEvent('beforeMethod', array($this, 'beforeMethod'));   
		$this->server->subscribeEvent('beforeBind', array($this, 'beforeBind'), 30);
		$this->server->subscribeEvent('afterUnbind', array($this, 'afterUnbind'), 30);
		
	}

    /**
     * Returns a list of supported features.
     *
     * This is used in the DAV: header in the OPTIONS and PROPFIND requests.
     *
     * @return array
     */
    public function getFeatures() {

        return array('files');

    }
	
	protected function getPrivatePath()
	{
		return ltrim(\afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . \afterlogic\DAV\Constants::FILESTORAGE_PATH_PRIVATE, '/');		
	}	
	
	protected function getCorporatePath()
	{
		return ltrim(\afterlogic\DAV\Constants::FILESTORAGE_PATH_ROOT . \afterlogic\DAV\Constants::FILESTORAGE_PATH_CORPORATE, '/');		
	}	

	protected function isFilestoragePrivate($path)
	{
		if (strpos($path, $this->getPrivatePath()) !== false)
		{
			return true;
		}
		return false;
	}
	
	protected function isFilestorageCorporate($path)
	{
		if (strpos($path, $this->getCorporatePath()) !== false)
		{
			return true;
		}
		return false;
	}
	
	protected function getTypeFromPath($path)
	{
		$iResult = \EFileStorageType::Private_;
		if ($this->isFilestoragePrivate($path))
		{
			$iResult = \EFileStorageType::Private_;
		}
		if ($this->isFilestorageCorporate($path))
		{
			$iResult = \EFileStorageType::Corporate;
		}
		return $iResult;
	}

	protected function getFilePathFromPath($path)
	{
		$sPath = '';
		if ($this->isFilestoragePrivate($path))
		{
			$sPath = $this->getPrivatePath();
		}
		if ($this->isFilestorageCorporate($path))
		{
			$sPath = $this->getCorporatePath();
		}
		
		return str_replace($sPath, '', $path);
	}

	function beforeMethod($methodName, $uri) {

	  if ($methodName === 'MOVE')
	  {
		  $GLOBALS['__FILESTORAGE_MOVE_ACTION__'] = true;
	  }
	  return true;

	}

	/**
     * @param string $path
     * @throws \Sabre\DAV\Exception\NotAuthenticated
     * @return bool
     */
    public function beforeBind($path)
    {
		if ($this->isFilestoragePrivate($path) || $this->isFilestorageCorporate($path))
		{
			$oAccount = $this->getAccount();
			if ($oAccount)
			{
				$iType = $this->getTypeFromPath($path);
				$sFilePath = $this->getFilePathFromPath(dirname($path));
				$sFileName = basename($path);

				$this->sNewPath = $path;
				$this->sNewID = implode('|', array($oAccount->IdAccount, $iType, $sFilePath, $sFileName));
			}
		}
		return true;
	}
	
	/**
     * @param string $path
     * @throws \Sabre\DAV\Exception\NotAuthenticated
     * @return bool
     */
    public function afterUnbind($path)
    {
		if ($this->isFilestoragePrivate($path) || $this->isFilestorageCorporate($path))
		{
			$oAccount = $this->getAccount();

			if ($oAccount)
			{
 				$iType = $this->getTypeFromPath($path);
				$sFilePath = $this->getFilePathFromPath(dirname($path));
				$sFileName = basename($path);

				$oMin = $this->getMinMan();
				$this->sOldPath = $path;
				$this->sOldID = implode('|', array($oAccount->IdAccount, $iType, $sFilePath, $sFileName));
				$aData = $oMin->GetMinByID($this->sOldID);
				
				\CApi::Log('OldID: ' . $this->sOldID, \ELogLevel::Full, 'fs-');
				\CApi::Log('NewID: ' . $this->sNewID, \ELogLevel::Full, 'fs-');
				
				if (isset($this->sNewPath))
				{
//					$node = $this->server->tree->getNodeForPath($this->sNewPath);
//					\CApi::LogObject($node, \ELogLevel::Full, 'fs-');
				}
				
				if (isset($this->sNewID) && !empty($aData['__hash__']))
				{
					$aNewData = explode('|', $this->sNewID);
					$aParams = array(
						'Type' => $aNewData[1],
						'Path' => $aNewData[2],
						'Name' => $aNewData[3],
						'Size' => $aData['Size']
					);
					$oMin->UpdateMinByID($this->sOldID, $aParams, $this->sNewID);
				}
				else
				{
					$oMin->DeleteMinByID($this->sOldID);
				}
			}
		}
	    $GLOBALS['__FILESTORAGE_MOVE_ACTION__'] = false;
		return true;
	}
	
}