<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV\Locks\Backend;

use afterlogic\DAV\Constants;

class PDO extends \Sabre\DAV\Locks\Backend\PDO {

    /**
     * Constructor 
     */
    public function __construct() {

		$oPdo = \CApi::GetPDO();
		$dbPrefix = \CApi::GetSettings()->GetConf('Common/DBPrefix');
		
		parent::__construct($oPdo, $dbPrefix.Constants::T_LOCKS);

    }
}
