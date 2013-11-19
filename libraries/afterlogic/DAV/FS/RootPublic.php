<?php
namespace afterlogic\DAV\FS;

class RootPublic extends Directory {
	
	private $rootPath = null;

	/**
	 * @var \CApiUsersManager
	 */
	protected $oApiUsers= null;
	
	public function getUsersMan()
	{
		if ($this->oApiUsers == null)
		{
			$this->oApiUsers = \CApi::Manager('users');
		}
		return $this->oApiUsers;
	}
	
    public function initPath() {
		
		if ($this->rootPath === null)
		{
			$oAccount = $this->authPlugin->getCurrentAccount();
			if (!isset($oAccount))
			{
				$sUserName = $this->authPlugin->getCurrentUser();
				if (isset($sUserName))
				{
					$oUsersMan = $this->getUsersMan();
					$oAccount = $oUsersMan->GetAccountOnLogin($sUserName);
				}
			}
			
			if ($oAccount)
			{
				$this->rootPath = $this->path . '/' . $oAccount->IdTenant;
			}
		}
		$this->path = $this->rootPath;
	}	
	
    public function getName() {

        return 'corporate';

    }	

	public function setName($name) {

		throw new \Sabre\DAV\Exception\Forbidden();

	}

	public function delete() {

		throw new \Sabre\DAV\Exception\Forbidden();

	} 	
	
    public function getQuotaInfo() {

        $Size = 0;
		$aResult = \api_Utils::GetDirectorySize($this->path);
		if ($aResult && $aResult['size'])
		{
			$Size = (int) $aResult['size'];
		}
		return array(
            $Size,
            0
        );	
	}
	
}
