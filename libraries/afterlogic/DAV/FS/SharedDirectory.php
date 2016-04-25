<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\FS;

class SharedDirectory extends Directory {
    
	protected $linkPath;
	
	protected $sharedItem;

	protected $isLink;

	/**
     * Constructor
     *
     * @param string $path
     */
    public function __construct($path, $sharedItem, $isLink = false) {

		parent::__construct($sharedItem->getPath());

		$this->linkPath = $path;
		$this->sharedItem = $sharedItem;
		$this->isLink = $isLink;

    }
	
	public function getRootPath($sType = \EFileStorageTypeStr::Personal) {

		return $this->path;

    }

	public function getPath() {

		return $this->linkPath;

    }

	public function getName() {

        if ($this->isLink)
		{
			return $this->sharedItem->getName();
		}
		else 
		{
	        list(, $name)  = \Sabre\DAV\URLUtil::splitPath($this->linkPath);
		    return $name;
		}

    }

	public function createDirectory($name) {

		throw new DAV\Exception\Forbidden('Permission denied');
		
    }

	public function createFile($name, $data = null) {

		throw new DAV\Exception\Forbidden('Permission denied');

    }

    public function getChild($name) {

		$this->initPath();
		
        $path = $this->path . '/' . trim($name, '/');

        if (!file_exists($path)) throw new \Sabre\DAV\Exception\NotFound('File with name ' . $path . ' could not be located');

        if (is_dir($path)) {

            return new SharedDirectory($path, $this->sharedItem);

        } else {

            return new SharedFile($path, $this->sharedItem);

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
					$aResult[] = new Directory($sItem);
				}
				else
				{
					$aResult[] = new File($sItem);
				}
			}
		}
		
		return $aResult;
	}
}