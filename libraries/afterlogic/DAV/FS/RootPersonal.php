<?php
namespace afterlogic\DAV\FS;

class RootPersonal extends Directory{
	
	private $rootPath = null;

	public function initPath() {
		
		$username = \afterlogic\DAV\Auth\Backend::getInstance()->getCurrentUser();
		if ($this->rootPath === null)
		{
			$this->rootPath = $this->path . '/' . $username;
			if (!file_exists($this->rootPath))
			{
				mkdir($this->rootPath, 0777, true);
			}
		}
		$this->path = $this->rootPath;
	}	

    public function getName() {

        return 'personal';

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
