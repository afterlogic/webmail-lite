<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

namespace afterlogic\DAV\Delegates\Backend;

use afterlogic\DAV\Constants;

class PDO extends AbstractBackend
{

    /**
     * Reference to PDO connection 
     * 
     * @var PDO 
     */
    protected $pdo;

	protected $calendarsTbl;
	protected $principalsTbl;
	protected $delegatesTbl;
	
    /**
     * List of CalDAV properties, and how they map to database fieldnames
     *
     * Add your own properties by simply adding on to this array
     *
     * @var array
     */
    public $propertyMap = array(
        '{DAV:}displayname'                          => 'displayname',
        '{urn:ietf:params:xml:ns:caldav}calendar-description' => 'description',
        '{urn:ietf:params:xml:ns:caldav}calendar-timezone'    => 'timezone',
        '{http://apple.com/ns/ical/}calendar-order'  => 'calendarorder',
        '{http://apple.com/ns/ical/}calendar-color'  => 'calendarcolor',
    );
	
	/**
     * Creates the backend object. 
     *
     * If the filename argument is passed in, it will parse out the specified file fist.
     * 
     * @param string $filename
     * @param string $tableName The PDO table name to use 
     * @return void
     */
    public function __construct(\PDO $pdo, $prefix = '') {

        $this->pdo = $pdo;
		$this->calendarsTbl = $prefix.Constants::T_CALENDARS;
		$this->principalsTbl = $prefix.Constants::T_PRINCIPALS;
		$this->delegatesTbl = $prefix.Constants::T_DELEGATES;
    } 
	
	public function UnsubscribeCalendar($iCalendarId, $sUser)
	{
		$stmt = $this->pdo->prepare(
				'SELECT id FROM '.$this->principalsTbl.' WHERE uri = ?');
		$stmt->execute(
				array(
					'principals/' . $sUser
				)
		);
		$result = $stmt->fetch();
		if (!$result)
		{
			return false;
		}       
		$iPrincipalId = $result['id'];
		
		$stmt = $this->pdo->prepare(
				'DELETE FROM '.$this->delegatesTbl.' WHERE calendarid=? AND principalid=?');
		$stmt->execute(
				array(
					$iCalendarId, 
					$iPrincipalId
				)
		);
	}
	
	
	public function UpdateShare($sCalendarId, $sFromUser, $sToUser, $iMode)
	{
		$stmt = $this->pdo->prepare(
			'SELECT id FROM '.$this->calendarsTbl.' WHERE uri = ? AND principaluri = ?');
		
		$stmt->execute(
				array(
					basename($sCalendarId), 
					'principals/' . $sFromUser
				)
		);
		$result = $stmt->fetch();
		$stmt->closeCursor();
		if (!$result)
		{
			return false;
		}       
		$iCalendarId = $result['id'];
		
		$stmt = $this->pdo->prepare(
				'SELECT id FROM '.$this->principalsTbl.' WHERE uri = ?');
		$stmt->execute(
				array(
					'principals/' . $sToUser
				)
		);
		$result = $stmt->fetch();
		$stmt->closeCursor();
		if (!$result)
		{
			return false;
		}       
		$iPrincipalId = $result['id'];
		
		$stmt = $this->pdo->prepare(
				'DELETE FROM '.$this->delegatesTbl.' WHERE calendarid=? AND principalid=?');
		$stmt->execute(
				array(
					$iCalendarId, 
					$iPrincipalId
				)
		);
		$stmt->closeCursor();
		
		if ($iMode != \ECalendarPermission::RemovePermission)
		{
			$stmt = $this->pdo->prepare(
				'INSERT INTO '.$this->delegatesTbl.' (calendarid, principalid, mode) 
					SELECT ?, '.$this->principalsTbl.'.id, ? 
						FROM '.$this->principalsTbl.' WHERE uri = ?');
			$stmt->execute(
					array(
						$iCalendarId, 
						$iMode, 
						'principals/' . $sToUser
					)
			);
			$stmt->closeCursor();
		}
		return 'delegation/'.$iCalendarId.'/calendar';
	}
	
	public function DeleteAllUsersShares($sToUser)
	{
		$stmt = $this->pdo->prepare(
				'SELECT id FROM '.$this->principalsTbl.' WHERE uri = ?');
		$stmt->execute(
				array(
					'principals/' . $sToUser
				)
		);
		$result = $stmt->fetchAll();
		$stmt->closeCursor();
		if (!$result)
		{
			return false;
		}       
		$iPrincipalId = $result[0]['id'];
		
		$stmt = $this->pdo->prepare(
				'DELETE FROM '.$this->delegatesTbl.' WHERE principalid = ?');
		$stmt->execute(array($iPrincipalId));		
		$stmt->closeCursor();
	}
	
    /**
     * Returns a list of deligated calendars for a principal.
     *
     * @param string $principalUri
     * @return array
     */
	public function getDeligatedCalendarsForUser($principalId)
	{
        $fields = array_values($this->propertyMap);
        $fields[] = $this->calendarsTbl.'.id';
        $fields[] = $this->calendarsTbl.'.uri';
        $fields[] = $this->calendarsTbl.'.ctag';
        $fields[] = $this->calendarsTbl.'.components';
        $fields[] = $this->calendarsTbl.'.principaluri';
        $fields[] = $this->delegatesTbl.'.mode';

        // Making fields a comma-delimited list 
        $fields = implode(', ', $fields);
        $stmt = $this->pdo->prepare(
				'SELECT ' . $fields . ' FROM `'.$this->calendarsTbl.'`, `'.$this->delegatesTbl.'` 
		WHERE '.$this->delegatesTbl.'.calendarid = '.$this->calendarsTbl.'.id AND '.$this->delegatesTbl.'.principalid = ?'); 
        $stmt->execute(array($principalId));

        $calendars = array();
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $components = explode(',',$row['components']);

            $calendar = array(
                'id' => $row['id'],
                'uri' => $row['uri'],
                'principaluri' => $row['principaluri'],
                '{' . \Sabre\CalDAV\Plugin::NS_CALENDARSERVER . '}getctag' => $row['ctag']?$row['ctag']:'0',
                '{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}supported-calendar-component-set' => new \Sabre\CalDAV\Property\SupportedCalendarComponentSet($components),
				'mode' => $row['mode'],
            );
        
            foreach($this->propertyMap as $xmlName=>$dbName) {
                $calendar[$xmlName] = $row[$dbName];
            }

            $calendars[] = $calendar;

        }

        return $calendars;
	}
	
	/*
	 * @param string $email
	 * @param string $calendarUri
     * @return int | bool
	 */
	public function getCalendarForUser($email, $calendarUri)
	{
		$mCalendarId = false;
		$stmt = $this->pdo->prepare(
			'SELECT id FROM `'. $this->calendarsTbl .'` WHERE uri = ? AND principaluri = ?');
		$stmt->execute(
				array(
					basename($calendarUri), 
					'principals/' . $email
				)
		);
		$result = $stmt->fetch();
		if ($result !== false)
		{
			$mCalendarId = (int) $result['id'];
		}       

		return $mCalendarId;
	}	

	/**
	 * @param string $principalUri
	 * @param string $calendarUri
	 * @return array
	 */
	public function getCalendarUsers($principalUri, $calendarUri)	
	{
		$stmt = $this->pdo->prepare('
			SELECT p.uri, d.mode 
				FROM '.$this->delegatesTbl.' AS d
					LEFT JOIN '.$this->principalsTbl.' AS p ON p.id = d.principalid
						LEFT JOIN '.$this->calendarsTbl.' AS c ON c.id = d.calendarid
							WHERE c.principaluri = ? AND c.uri = ?');
		$stmt->execute(
				array(
					$principalUri, 
					$calendarUri
				)
		);

		return $stmt->fetchAll();	
	}	
	
	public function getDelegatesByCalendar($calendarUri)
	{
        $fields = array();
		$fields[] = $this->calendarsTbl.'.id';
        $fields[] = $this->delegatesTbl.'.principalid';

        // Making fields a comma-delimited list 
        $fields = implode(', ', $fields);
        $stmt = $this->pdo->prepare('SELECT ' . $fields . ' FROM `'.$this->calendarsTbl.'`, `'.$this->delegatesTbl.'` 
		WHERE '.$this->delegatesTbl.'.calendarid = '.$this->calendarsTbl.'.id AND '.$this->calendarsTbl.'.uri = ?'); 
        $stmt->execute(array($calendarUri));

        $calendars = array();
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) 
		{
			$stmt1 = $this->pdo->prepare('SELECT uri FROM `'.$this->principalsTbl.'` WHERE id = ?'); 
			$stmt1->execute(array($row['principalid']));
			$row1 = $stmt1->fetch(\PDO::FETCH_ASSOC);
			if ($row1)
			{
				$calendar = array(
					'uri' => 'delegation/' . $row['id'] . '/calendar',
					'user' => basename($row1['uri'])
				);
	            $calendars[] = $calendar;
			}        

        }
        return $calendars;
	}
	
}