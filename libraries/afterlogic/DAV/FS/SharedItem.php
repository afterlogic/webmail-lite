<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\FS;

class SharedItem{
	
	protected $path;
	
    public function __construct($path) {

        $this->path = $path;

    }
	
	public function getRootPath(){
		
		return \CApi::DataPath() . '/' . Plugin::getPersonalPath() . '/' . $this->getOwner();
		
	}		
	
	public function getPath() {

        return $this->getRootPath() . '/' . $this->getLink();

    }
	
	public function getOwner() {

        return $this->getProperty('owner');

    }

	public function getAccess() {

        return $this->getProperty('access');

    }

	public function getLink() {

        $link = $this->getProperty('link');
		return $link;

    }

	public function isDirectory() {

        return $this->getProperty('directory');

    }
	
    public function getName() {

        list(, $name)  = \Sabre\DAV\URLUtil::splitPath($this->getLink());
        return $name;

    }	
	
	public function exists() {
		
		return file_exists($this->getPath());
		
	}

	/**
     * @return array
     */
    protected function getData() 
	{

        if (!file_exists($this->path)) return array();

        // opening up the file, and creating a shared lock
        $handle = fopen($this->path,'r');
        flock($handle,LOCK_SH);
        $data = '';

        // Reading data until the eof
        while(!feof($handle)) {
            $data.=fread($handle,8192);
        }

        // We're all good
        fclose($handle);

        // Unserializing and checking if the resource file contains data for this file
        $data = unserialize($data);
        if (!isset($data)) {
            return array();
        }

        if (!isset($data)) 
		{
			$data = array();
		}
        return $data;

    }	
	
    /**
     * Updates the resource information
     *
     * @param array $newData
     * @return void
     */
    protected function putData(array $newData) 
	{

        // opening up the file, and creating a shared lock
        $handle = fopen($this->path,'w+');
        flock($handle,LOCK_EX);

        fwrite($handle,serialize($newData));
        fclose($handle);

    }	
	
    public function updateProperties($properties) {

        $data = $this->getData();

        foreach($properties as $propertyName=>$propertyValue) {

            // If it was null, we need to delete the property
            if (is_null($propertyValue)) {
                if (isset($data[$propertyName])) {
                    unset($data[$propertyName]);
                }
            } else {
                $data[$propertyName] = $propertyValue;
            }

        }

        $this->putData($data);
        return true;
    }

	public function getProperty($sName)
	{
		$aData = $this->getData();
		return isset($aData[$sName]) ? $aData[$sName] : null;
	}
	
	public function setProperty($sName, $mValue)
	{
		$aData = $this->getData();
		$aData[$sName] = $mValue;
		$this->putData($aData);
	}	
	
	public function delete()
	{

		unlink($this->path);
		
	}	
	
	public function getItem()
	{
		if ($this->getProperty('directory'))
		{
			return new SharedDirectory($this->path, $this, true);
		}
		else
		{
			return new SharedFile($this->path, $this, true);
		}
	}
	
}

