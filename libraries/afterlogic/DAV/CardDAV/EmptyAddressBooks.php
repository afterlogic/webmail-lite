<?php

/*
 * Copyright 2004-2014, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class EmptyAddressBooks extends \Sabre\CardDAV\UserAddressBooks{

	/**
     * Returns a list of addressbooks
     *
     * @return array
     */
    public function getChildren() {

        return array();

    }

}
