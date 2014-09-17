<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
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
