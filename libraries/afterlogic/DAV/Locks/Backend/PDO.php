<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Locks\Backend;

use afterlogic\DAV\Constants;

class PDO extends \Sabre\DAV\Locks\Backend\PDO {

    /**
     * Constructor 
     * 
     * @param \PDO $pdo
     * @param string $tableName 
     */
    public function __construct(\PDO $pdo, $dBPrefix = '') {

        $this->pdo = $pdo;
        $this->tableName = $dBPrefix.Constants::T_LOCKS;

    }
}
