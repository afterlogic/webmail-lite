<?php

namespace afterlogic\DAV\Principal\Backend;

use afterlogic\DAV\Constants;

class PDO extends \Sabre\DAVACL\PrincipalBackend\PDO {


	/**
     * Read mode
     */
    const READ = 2;

    /**
     * Read-write mode
     */
    const WRITE = 1;

    /**
     * PDO table name for 'delegates' 
     * 
     * @var string 
     */
    protected $delegatesTableName;

    /**
     * Sets up the backend.
     * 
     * @param PDO $pdo
     * @param string $tableName 
     */
    public function __construct(\PDO $pdo, $dBPrefix = '')
	{
		$this->delegatesTableName = $dBPrefix.Constants::T_DELEGATES;
        parent::__construct($pdo, $dBPrefix.Constants::T_PRINCIPALS, $dBPrefix.Constants::T_GROUPMEMBERS);
    } 

    /**
     * Returns the list of principals in a group.
     *
     * This is only supported for 
     * caldlg/###/principal/calendar-proxy-* urls.
     * 
     * @param string $principal 
     * @return array 
     */
    public function getGroupMemberSet($principal) {

        // We only ever allow fetching the group-member-set for
        // delegation principals
        if (!preg_match('|^delegation/([0-9]+)/principal/calendar-proxy-(read|write)$|', $principal, $matches)) {
            return array();
        }

        $stmt = $this->pdo->prepare('SELECT '.$this->TableName.'.uri as uri FROM `'.$this->delegatesTableName.'` 
		LEFT JOIN `'.$this->TableName.'` ON '.$this->delegatesTableName.'.principalid = '.$this->TableName.'.id WHERE calendarid = ? AND mode = ?');

        $stmt->execute(array(
            $matches[1], 
            $matches[2]==='read'?self::READ:self::WRITE
        ));
        $result = $stmt->fetchAll();

        $response = array();
        foreach($result as $row) {
            $response[] = $row['uri'];
        }

        return $response;

    }

    /**
     * Returns the list of groups a principal belongs to 
     * 
     * @param string $principal 
     * @return array 
     */
    public function getGroupMembership($principal) {

        $principal = $this->getPrincipalByPath($principal);
        if (!$principal) return array();

        $stmt = $this->pdo->prepare('SELECT calendarid, mode FROM `'.$this->delegatesTableName.'` WHERE principalid = ?');
        $stmt->execute(array($principal['id']));
        $result = $stmt->fetchAll();

        $response = array();
        foreach($result as $row) {
            if ($row['mode'] == self::READ) {
                $response[] = 'delegation/' . $row['calendarid'] . '/principal/calendar-proxy-read';
            } else if ($row['mode'] == self::WRITE) {
                $response[] = 'delegation/' . $row['calendarid'] . '/principal/calendar-proxy-write';
            } else {
                throw new \Sabre\DAV\Exception('Incorrect mode for principal in `'.$this->delegatesTableName.'` table');
            }
        }
        return $response;

    }

    /**
     * Updates the list of members for a group-principal
     * 
     * @param string $principal 
     * @param array $groupMembers 
     * @return void
     */
    public function setGroupMemberSet($principal, array $groupMembers) {

        // We only ever allow setting the group-member-set for
        // calendar delegation principals
        if (!preg_match('|^delegation/([0-9]+)/principal/calendar-proxy-(read|write)$|')) {
            throw new \Sabre\DAV\Exception\Forbidden('We don\'t allow setting the group-member-set for this principal');
        }
        throw new \Sabre\DAV\Exception\NotImplemented('This method is currently not implemented');

    }
	
    // ---------------------------------------------------------------------------------------------- //
	
    /**
     * @param string $uri 
     * @param string $email 
     * @param string $displayname 
     * @return void
     */
	public function createPrincipal($uri, $email = '', $displayname = '') {

        $stmt = $this->pdo->prepare('SELECT count(id) FROM `'.$this->tableName.'` WHERE uri = ?;');
        $stmt->execute(array($uri));
		$rowCount = $stmt->fetchColumn();
		$stmt->closeCursor();
		
		if ($rowCount == 0)
		{
			$stmt = $this->pdo->prepare('INSERT INTO `'.$this->tableName.'` (uri, email, displayname) VALUES (?, ?, ?);');
			
			$stmt->execute(array($uri, $email, $displayname));
			$stmt->execute(array($uri . "/calendar-proxy-write", "", ""));
			$stmt->execute(array($uri . "/calendar-proxy-read", "", ""));
		}
    }
	
    /**
     * @param string $uri 
     * @return void
     */
	public function deletePrincipal($uri) {

        $stmt = $this->pdo->prepare('DELETE FROM `'.$this->tableName.'` WHERE uri = ?;');
        
		$stmt->execute(array($uri));
        $stmt->execute(array($uri . "/calendar-proxy-write"));
        $stmt->execute(array($uri . "/calendar-proxy-read"));
    } 	

    /**
     * @return array
     */
	public function getAllPrincipals() {

        $stmt = $this->pdo->prepare('SELECT * FROM `'.$this->tableName.'`;');
		$stmt->execute();
        return $stmt->fetchAll();
    } 	
	
	public function getOrCreatePublicPrincipal()
	{
		$this->createPrincipal(
				'principals/'.Constants::DAV_PUBLIC_PRINCIPAL, 
				Constants::DAV_PUBLIC_PRINCIPAL, 
				Constants::DAV_PUBLIC_PRINCIPAL);
		
		return Constants::DAV_PUBLIC_PRINCIPAL;
	}
	
}
