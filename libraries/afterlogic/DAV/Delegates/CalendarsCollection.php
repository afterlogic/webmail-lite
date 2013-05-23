<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Delegates;

class CalendarsCollection extends \Sabre\DAV\Collection implements \Sabre\DAV\ICollection {

	private $principalInfo;

	function __construct(array $principalInfo) {

		$this->principalInfo = $principalInfo;

	}

	function getName() {

		list(, $name) = \Sabre\DAV\URLUtil::splitPath($this->principalInfo['uri']);
		return $name;

	}

	function getChildren() {

		return array(
            new CalendarsReadable($this->principalInfo),
            new CalendarsWriteable($this->principalInfo),
        );

	}

}

?>
