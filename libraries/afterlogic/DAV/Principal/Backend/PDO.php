<?php

namespace afterlogic\DAV\Principal\Backend;

use afterlogic\DAV\Constants;

class PDO extends \Sabre\DAVACL\PrincipalBackend\PDO 
{
    /**
     * Sets up the backend.
     * 
     * @param PDO $pdo
     * @param string $tableName 
     */
    public function __construct(\PDO $pdo, $dBPrefix = '')
	{
        parent::__construct($pdo, $dBPrefix.Constants::T_PRINCIPALS, $dBPrefix.Constants::T_GROUPMEMBERS);
    } 

    /**
     * @param string $uri 
     * @return bool
     */
	public function existsPrincipal($uri) {
		
        $bResult = false;
		
		$stmt = $this->pdo->prepare('SELECT count(id) FROM '.$this->tableName.' WHERE uri = ?;');
        $stmt->execute(array($uri));
		$rowCount = $stmt->fetchColumn();
		$stmt->closeCursor();
		
		if ($rowCount !== 0)
		{
			$bResult = true;
		}		
		
		return $bResult;
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
		$sUri = 'principals/'.$sEmail;
		if (!$this->existsPrincipal($sUri))
		{
			$this->createPrincipal($sUri, $sEmail, $sEmail);
		}
		
		return $sEmail;
	}
}
