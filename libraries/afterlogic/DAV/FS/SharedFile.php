<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\FS;

class SharedFile extends File{
	
	protected $linkPath;

	protected $sharedItem;
    
	protected $isLink;

	public function __construct($path, $sharedItem, $isLink = false) {

		parent::__construct($sharedItem->getPath());

		$this->sharedItem = $sharedItem;
		$this->linkPath = $path;
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

	public function getOwner() {

        return $this->sharedItem->getOwner();

    }

	public function getAccess() {

        return $this->sharedItem->getAccess();

    }

	public function getLink() {

        return $this->sharedItem->getLink();

    }

	public function isDirectory() {

        return $this->sharedItem->isDirectory();

    }

	public function getDirectory() {
		
		return new Directory(dirname($this->path));
		
	}
	
    /**
     * Returns the data
     *
     * @return resource
     */
    public function get() {

        \CApi::Log($this->path, \ELogLevel::Full, 'file-');
		return fopen($this->path,'r');

    }	
	
	public function delete() {

        parent::delete();
		
		$oDirectory = $this->getDirectory();
		$oDirectory->updateQuota();

    }
}

