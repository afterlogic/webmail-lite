<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
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
