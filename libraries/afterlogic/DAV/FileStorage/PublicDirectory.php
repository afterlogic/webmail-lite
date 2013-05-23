<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\FileStorage;

class PublicDirectory extends \Sabre\DAV\FS\Directory {

  private $name;

  function __construct($path, $name = null) {
    
	 $this->name = $name;
	 parent::__construct($path);

  }

  function getName() {

      if (isset($this->name))
	  {
		return $this->name;
	  }
	  else
	  {
		  return basename($this->path);
	  }

  }

}