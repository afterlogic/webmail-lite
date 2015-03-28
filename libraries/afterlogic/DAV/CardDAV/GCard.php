<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\CardDAV;

class GCard extends \Sabre\DAV\File implements \Sabre\CardDAV\ICard {

    /**
     * Contact info
     * 
     * @var array 
     */
    private $_cardInfo;

    /**
     * Constructor
     * 
     * @param array $cardInfo 
     */
    public function __construct(array $cardInfo) {

        $this->_cardInfo = $cardInfo;

    }

    /**
     * Returns the node name
     *
     * @return void
     */
    public function getName() {

        return $this->_cardInfo['uri'];

    }
	
    /**
     * Returns the mime content-type
     *
     * @return string
     */
    public function getContentType() {

        return 'text/x-vcard';

    }	

    /**
     * Returns the vcard 
     * 
     * @return string 
     */
    public function get() {

        return $this->_cardInfo['carddata'];

    }

    /**
     * Returns the last modification timestamp
     * 
     * @return int 
     */
    public function getLastModified() {

        return $this->_cardInfo['lastmodified'];

    }

    /**
     * Returns the size of the vcard
     * 
     * @return int 
     */
    public function getSize() {

        return strlen($this->_cardInfo['carddata']);

    }

}
