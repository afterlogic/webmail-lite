<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV\CardDAV;

class SharedCard extends \Sabre\CardDAV\Card {

   protected $principalUri;
	
	/**
     * Constructor
     *
     * @param \Sabre\CardDAV\Backend\BackendInterface $carddavBackend
     * @param array $addressBookInfo
     * @param array $cardData
     */
    public function __construct(\Sabre\CardDAV\Backend\BackendInterface $carddavBackend,array $addressBookInfo,array $cardData,$principalUri) {

        parent::__construct($carddavBackend, $addressBookInfo, $cardData);
		$this->principalUri = $principalUri;
		
    }
	
    public function getACL() {

        return array(
            array(
                'privilege' => '{DAV:}read',
                'principal' => $this->principalUri,
                'protected' => true,
            ),
            array(
                'privilege' => '{DAV:}write',
                'principal' => $this->principalUri,
                'protected' => true,
            ),
        );

    }	
	
 }
