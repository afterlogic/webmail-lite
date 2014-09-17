<?php
namespace afterlogic\DAV\FS;

class RootPublic extends Directory {
	
	private $rootPath = null;

    public function initPath() {
		
		if ($this->rootPath === null)
		{
			$sUserName = \afterlogic\DAV\Auth\Backend::getInstance()->getCurrentUser();
			if (isset($sUserName))
			{
				$oAccount = \afterlogic\DAV\Utils::GetAccountByLogin($sUserName);
				if ($oAccount)
				{
					$this->rootPath = $this->path . '/' . $oAccount->IdTenant;
					if (!file_exists($this->rootPath))
					{
						mkdir($this->rootPath, 0777, true);
					}
				}
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
