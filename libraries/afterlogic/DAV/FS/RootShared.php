<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\FS;

class RootShared extends RootPersonal{
	
    public function getName() {

        return 'shared';

    }	
	
    public function getChild($name) {

		$this->initPath();
		
        $path = $this->path . '/' . trim($name, '/');

        if (!file_exists($path)) throw new \Sabre\DAV\Exception\NotFound('File with name ' . $path . ' could not be located');

		if (!is_dir($path))
		{
			$item = new SharedItem($this->authPlugin, $path);
			
			if (!$item->exists())
			{
				$item->delete();
			}
/*
			$item->updateProperties(array(
				'owner' => 'test1@localhost',
				'access' => \ECalendarPermission::Write,
				'link' => 'folder',
				'directory' => true
			));
*/		
			return $item->getItem();
		}
		else 
		{
			return false;
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
		{
			if($node!=='.' && $node!=='..' && $node!== '.sabredav' && $node!== API_HELPDESK_PUBLIC_NAME) 
			{
				$child = $this->getChild($node);
				if ($child)
				{
					$nodes[] = $child;
				}
			}
		}
        return $nodes;

    }	
	
}
