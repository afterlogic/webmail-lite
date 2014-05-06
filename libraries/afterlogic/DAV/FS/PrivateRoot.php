<?php
namespace afterlogic\DAV\FS;

class PrivateRoot extends Directory{
	
	private $rootPath = null;

	public function initPath() {
		
		$username = $this->authPlugin->getCurrentUser();
		if ($this->rootPath === null)
		{
			$this->rootPath = $this->path . '/' . $username;
		}
		$this->path = $this->rootPath;
	}	

    public function getName() {

        return 'private';

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
