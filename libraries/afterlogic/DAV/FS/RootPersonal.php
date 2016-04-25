<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

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
