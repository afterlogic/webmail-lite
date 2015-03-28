<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace afterlogic\DAV\Principal\Backend;

use afterlogic\DAV\Constants;

class PDO extends \Sabre\DAVACL\PrincipalBackend\PDO 
{
    /**
     * Sets up the backend.
     */
    public function __construct()
	{
		$oPdo = \CApi::GetPDO();
		$dbPrefix = \CApi::GetSettings()->GetConf('Common/DBPrefix');

		parent::__construct($oPdo, $dbPrefix.Constants::T_PRINCIPALS, $dbPrefix.Constants::T_GROUPMEMBERS);
    } 

    /**
     * @param string $uri 
     * @return bool
     */
	public function existsPrincipal($uri) {
		
		$stmt = $this->pdo->prepare('SELECT count(id) FROM '.$this->tableName.' WHERE uri = ?;');
        $stmt->execute(array($uri));
		$rowCount = $stmt->fetchColumn();
		$stmt->closeCursor();
		
		return ((int)$rowCount !== 0);
	}
	
    /**
     * @param string $uri 
     * @param string $email 
     * @param string $displayname 
     * @return void
     */
	public function createPrincipal($uri, $email = '', $displayname = '') {

		$stmt = $this->pdo->prepare('INSERT INTO '.$this->tableName.' (uri, email, displayname) VALUES (?, ?, ?);');
		$stmt->execute(array($uri, $email, $displayname));
    }
	
    /**
     * @param string $uri 
     * @return void
     */
	public function deletePrincipal($uri) {

        $stmt = $this->pdo->prepare('DELETE FROM '.$this->tableName.' WHERE uri = ?;');
		$stmt->execute(array($uri));
    } 	
	
    /**
     * @param string $sEmail 
     * @return string
     */
	public function getPrincipalByEmail($sEmail)
	{
		$sUri = \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' .$sEmail;
		if (!$this->existsPrincipal($sUri))
		{
			$this->createPrincipal($sUri, $sEmail, $sEmail);
		}
		
		return $sEmail;
	}
}
