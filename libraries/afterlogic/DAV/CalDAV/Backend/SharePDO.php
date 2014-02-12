<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 * 
 */

namespace afterlogic\DAV\CalDAV\Backend;

use afterlogic\DAV\Constants;
use Sabre\CalDAV\Backend as SabreBackend;

class SharePDO extends SabreBackend\PDO implements SabreBackend\SharingSupport
{
	/**
	 * The table name that will be used for calendar shares
	 *
	 * @var string
	 */
	protected $calendarSharesTableName;
        
	/**
	 * The table name that will be used for principals
	 *
	 * @var string
	 */
	protected $principalsTableName;
        
	/**
	 * The table name that will be used for notifications
	 * 
	 * @var string
	 */
	protected $notificationsTableName;
	
	/**
	 * @var string
	 */
	protected $dBPrefix;
	
	/**
	 * List of properties for the calendar shares table
	 * This list maps exactly to the field names in the db table
	 */
	public $sharesProperties = array(
		'calendarid',
		'member',
		'status',
		'readonly',
		'summary',
		'displayname',
		'color'
	);
	
	/**
	 * Creates the backend
	 *
	 * @param \PDO $pdo
	 * @param string $dBPrefix 
	 */
	public function __construct(\PDO $pdo, $dBPrefix) 
	{
		$this->dBPrefix = $dBPrefix;
		parent::__construct($pdo, $dBPrefix.Constants::T_CALENDARS, $dBPrefix.Constants::T_CALENDAROBJECTS);
		
		$this->calendarSharesTableName = $dBPrefix.Constants::T_CALENDARSHARES;
		$this->principalsTableName = $dBPrefix.Constants::T_PRINCIPALS;
		$this->notificationsTableName = $dBPrefix.Constants::T_NOTIFICATIONS;

	}
        
	/**
	 * Setter method for the calendarShares table name
	 */
	public function setCalendarSharesTableName($name)
	{
		$this->calendarSharesTableName = $name;
	}
	
	/**
	 * Updates the list of shares.
	 *
	 * The first array is a list of people that are to be added to the
	 * calendar.
	 *
	 * Every element in the add array has the following properties:
	 *   * href - A url. Usually a mailto: address
	 *   * commonname - Usually a first and last name, or false
	 *   * summary - A description of the share, can also be false
	 *   * readonly - A boolean value
	 *
	 * Every element in the remove array is just the address string.
	 *
	 * Note that if the calendar is currently marked as 'not shared' by and
	 * this method is called, the calendar should be 'upgraded' to a shared
	 * calendar.
	 *
	 * @param mixed $mCalendarId
	 * @param array $aAdd
	 * @param array $aRemove
	 * @return void
	 */
	function updateShares($mCalendarId, array $aAdd, array $aRemove = array())
	{ 
		$bAddResult = true;
		$bRemoveResult = true;
		
		if(count($aAdd)>0) 
		{
			foreach ($aAdd as $aAddItem)
			{
				$aFieldNames = array();
				$aFields = array();

				$aFieldNames[] = 'calendarid';
				$aFields[':calendarid'] = $mCalendarId; 

				// get the principal based on the supplied email address
				$aPrincipal = $this->getPrincipalByEmail($aAddItem['href']);

				$aFieldNames[] = 'member';
				$aFields[':member'] = $aPrincipal['id'];

				$aFieldNames[] = 'status';
				$aFields[':status'] = \Sabre\CalDAV\SharingPlugin::STATUS_NORESPONSE;


				// check we have all the required fields
				foreach($this->sharesProperties as $sField) 
				{
					if(isset($aAddItem[$sField])) 
					{
						$aFieldNames[] = $sField;
						$aFields[':'.$sField] = $aAddItem[$sField];
					}
				} 

				$stmt = $this->pdo->prepare("SELECT calendarid FROM ".$this->calendarSharesTableName." WHERE calendarid = ? and member = ?");
				$stmt->execute(array($mCalendarId, $aPrincipal['id']));

				if (count($stmt->fetchAll()) === 0)
				{
					$stmt = $this->pdo->prepare("INSERT INTO ".$this->calendarSharesTableName." (".implode(', ', $aFieldNames).") VALUES (".implode(', ',array_keys($aFields)).")");
					$bAddResult = $stmt->execute($aFields);
				}
				else
				{
					$aUpdateFields = array();
					foreach ($aFieldNames as $sFieldName)
					{
						$aUpdateFields[] = $sFieldName . '= :'. $sFieldName;
					}
					$stmt = $this->pdo->prepare("UPDATE ".$this->calendarSharesTableName." SET ".implode(', ', $aUpdateFields)." WHERE calendarid = :calendarid and member = :member");
					$bAddResult = $stmt->execute($aFields);
				}
			}
		}

// 		are we removing any shares?
		if(count($aRemove)>0) 
		{
			$aParams = array($mCalendarId);
			$aMembers = array();
			foreach($aRemove as $sRemoveItem) 
			{
				// get the principalid
				$oPrincipal = $this->getPrincipalByEmail($sRemoveItem);
				$aMembers[] = $oPrincipal['id'];
			}	
			$aParams[] = implode(',', $aMembers);
			$stmt = $this->pdo->prepare("DELETE FROM ".$this->calendarSharesTableName." WHERE calendarid = ? and member IN (?)");
			$bRemoveResult = $stmt->execute($aParams);
		}
		
		return $bAddResult && $bRemoveResult;
	}
	
	/**
	 * Returns the list of people whom this calendar is shared with.
	 *
	 * Every element in this array should have the following properties:
	 *   * href - Often a mailto: address
	 *   * commonname - Optional, for example a first + last name
	 *   * status - See the Sabre\CalDAV\SharingPlugin::STATUS_ constants.
	 *   * readonly - boolean
	 *   * summary - Optional, a description for the share
	 *
	 * @return array
	 */
	public function getShares($mCalendarId) {
		
//		$fields = implode(', ', $this->sharesProperties);
		$stmt = $this->pdo->prepare("SELECT * FROM ".$this->calendarSharesTableName." AS calendarShares LEFT JOIN ".$this->principalsTableName."  AS principals ON calendarShares.member = principals.id WHERE calendarShares.calendarid = ? ORDER BY calendarShares.calendarid ASC");
		$stmt->execute(array($mCalendarId));

		$aShares = array();
		while($aRow = $stmt->fetch(\PDO::FETCH_ASSOC)) 
		{ 
			$aShare = array(	
				'calendarid'=>$aRow['calendarid'],
				'principalpath' => $aRow['uri'],
				'readonly'=>$aRow['readonly'],
				'summary'=>$aRow['summary'],
				'href'=>$aRow['email'],
				'commonname' => $aRow['displayname'],
				'displayname'=>$aRow['displayname'],
				'status'=>$aRow['status'],
				'color'=>$aRow['color']
			);
			
			// add it to main array
			$aShares[] = $aShare;
		}
	
		return $aShares;
	}
	
	/**
	 * Returns a list of calendars for a principal.
	 *
	 * Every project is an array with the following keys:
	 *  * id, a unique id that will be used by other functions to modify the
	 *    calendar. This can be the same as the uri or a database key.
	 *  * uri, which the basename of the uri with which the calendar is
	 *    accessed.
	 *  * principaluri. The owner of the calendar. Almost always the same as
	 *    principalUri passed to this method.
	 *
	 * Furthermore it can contain webdav properties in clark notation. A very
	 * common one is '{DAV:}displayname'.
	 * 
	 * MODIFIED: THIS METHOD NOW NEEDS TO BE ABLE TO RETRIEVE SHARED CALENDARS
	 *
	 * @param string $principalUri
	 * @return array
	 */
	public function getCalendarsForUser($principalUri) {
	
		$fields = array_values($this->propertyMap);
		$fields[] = 'id';
		$fields[] = 'uri';
		$fields[] = 'ctag';
		$fields[] = 'components';
		$fields[] = 'principaluri';
		$fields[] = 'transparent';

		// Making fields a comma-delimited list
		$fields_list = implode(', ', $fields);
		$stmt = $this->pdo->prepare("SELECT " . $fields_list . " FROM ".$this->calendarTableName." WHERE principaluri = ? ORDER BY calendarorder ASC");
		$stmt->execute(array($principalUri));

		$calendars = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

			$components = array();
			if ($row['components']) {
				$components = explode(',',$row['components']);
			}

			$calendar = array(
					'id' => $row['id'],
					'uri' => $row['uri'],
					'principaluri' => $row['principaluri'],
					'{' . \Sabre\CalDAV\Plugin::NS_CALENDARSERVER . '}getctag' => $row['ctag']?$row['ctag']:'0',
					'{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}supported-calendar-component-set' => new \Sabre\CalDAV\Property\SupportedCalendarComponentSet($components),
					'{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}schedule-calendar-transp' => new \Sabre\CalDAV\Property\ScheduleCalendarTransp($row['transparent']?'transparent':'opaque'),
			);


			foreach($this->propertyMap as $xmlName=>$dbName) {
				$calendar[$xmlName] = $row[$dbName];
			}

			$calendars[] = $calendar;

		}

		// now let's get any shared calendars
		$shareFields = implode(', ', $this->sharesProperties);

		// get the principal id
		$principalBackend = $this->getPrincipalBackend();
		$principal = $principalBackend->getPrincipalByPath($principalUri);

		$shareStmt = $this->pdo->prepare("SELECT ". $shareFields . " FROM ".$this->calendarSharesTableName." WHERE member = ?");
		$shareStmt->execute(array($principal['id']));
		while($shareRow = $shareStmt->fetch(\PDO::FETCH_ASSOC)) {
			// get the original calendar
			$calStmt = $this->pdo->prepare("SELECT " . $fields_list . " FROM ".$this->calendarTableName." WHERE id = ? ORDER BY calendarorder ASC LIMIT 1");
			$calStmt->execute(array($shareRow['calendarid']));

			while($calendarShareRow = $calStmt->fetch(\PDO::FETCH_ASSOC)) {

				$shareComponents = array();
				if ($calendarShareRow['components']) {
					$shareComponents = explode(',',$calendarShareRow['components']);
				}

				$sharedCalendar = array(
						'id' => $calendarShareRow['id'],
						'uri' => $calendarShareRow['uri'],
						'principaluri' => $principalUri,
						'{' . \Sabre\CalDAV\Plugin::NS_CALENDARSERVER . '}getctag' => $calendarShareRow['ctag']?$calendarShareRow['ctag']:'0',
						'{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}supported-calendar-component-set' => new \Sabre\CalDAV\Property\SupportedCalendarComponentSet($shareComponents),
						'{' . \Sabre\CalDAV\Plugin::NS_CALDAV . '}schedule-calendar-transp' => new \Sabre\CalDAV\Property\ScheduleCalendarTransp($calendarShareRow['transparent']?'transparent':'opaque'),
				);
				// some specific properies for shared calendars
				$sharedCalendar['{http://calendarserver.org/ns/}shared-url'] = $calendarShareRow['uri'];
				$sharedCalendar['{http://sabredav.org/ns}owner-principal'] = $calendarShareRow['principaluri'];
				$sharedCalendar['{http://sabredav.org/ns}read-only'] = $shareRow['readonly'];
				$sharedCalendar['{http://calendarserver.org/ns/}summary'] = $shareRow['summary'];

				foreach($this->propertyMap as $xmlName=>$dbName) {

 					if($xmlName == '{DAV:}displayname') 
					{ 
 						$sharedCalendar[$xmlName] = $shareRow['displayname'] == null ? $calendarShareRow['displayname'] : $shareRow['displayname'];
 					} 
					elseif($xmlName == '{http://apple.com/ns/ical/}calendar-color') 
					{
 						$sharedCalendar[$xmlName] = $shareRow['color'] == null ? $calendarShareRow['calendarcolor'] : $shareRow['color'];
 					} 
					else 
					{
						$sharedCalendar[$xmlName] = $calendarShareRow[$dbName];
 					}
				}

				$calendars[] = $sharedCalendar;

			}
		}

		return $calendars;
	}
	
	/**
	 * This method is called when a user replied to a request to share.
	 *
	 * If the user chose to accept the share, this method should return the
	 * newly created calendar url.
	 *
	 * @param string href The sharee who is replying (often a mailto: address)
	 * @param int status One of the SharingPlugin::STATUS_* constants
	 * @param string $calendarUri The url to the calendar thats being shared
	 * @param string $inReplyTo The unique id this message is a response to
	 * @param string $summary A description of the reply
	 * @return null|string
	 */
	function shareReply($href, $status, $calendarUri, $inReplyTo, $summary = null) {}
	
	/**
	 * Marks this calendar as published.
	 *
	 * Publishing a calendar should automatically create a read-only, public,
	 * subscribable calendar.
	 *
	 * @param bool $value
	 * @return void
	 */
	public function setPublishStatus($calendarId, $value) {}
	
	/**
	 * Returns a list of notifications for a given principal url.
	 *
	 * The returned array should only consist of implementations of
	 * \Sabre\CalDAV\Notifications\INotificationType.
	 *
	 * @param string $principalUri
	 * @return array
	 */
	public function getNotificationsForPrincipal($principalUri)
	{ 
            
		// get ALL notifications for the user NB. Any read or out of date notifications should be already deleted.
		$stmt = $this->pdo->prepare("SELECT * FROM ".$this->notificationsTableName." WHERE principaluri = ? ORDER BY dtstamp ASC");
		$stmt->execute(array($principalUri));

		$aNotifications = array();
		while($aRow = $stmt->fetch(\PDO::FETCH_ASSOC)) 
		{
			// we need to return the correct type of notification
			switch($aRow['notification']) 
			{
				case 'Invite':
					$aValues = array();
					// sort out the required data
					if($aRow['id']) 
					{
						$aValues['id'] = $aRow['id'];
					}
					if($aRow['etag']) 
					{
						$aValues['etag'] = $aRow['etag'];
					}
					if($aRow['principaluri']) 
					{
						$aValues['href'] = $aRow['principaluri'];
					}
					if($aRow['dtstamp']) 
					{
						$aValues['dtstamp'] = $aRow['dtstamp'];
					}
					if($aRow['type']) 
					{
						$aValues['type'] = $aRow['type'];
					}
					if($aRow['readonly']) 
					{
						$aValues['readonly'] = $aRow['readonly'];
					}
					if($aRow['hosturl']) 
					{
						$aValues['hosturl'] = $aRow['hosturl'];
					}
					if($aRow['organizer']) 
					{
						$aValues['organizer'] = $aRow['organizer'];
					}
					if($aRow['commonname']) 
					{
						$aValues['commonName'] = $aRow['commonname'];
					}
					if($aRow['firstname']) 
					{
						$aValues['firstname'] = $aRow['firstname'];
					}
					if($aRow['lastname']) 
					{
						$aValues['lastname'] = $aRow['lastname'];
					}
					if($aRow['summary']) 
					{
						$aValues['summary'] = $aRow['summary'];
					}

					$aNotifications[] = new \Sabre\CalDAV\Notifications\Notification\Invite($aValues);
					break;

				case 'InviteReply':
					break;
				case 'SystemStatus':
					break;
			}

		}

		return $aNotifications;
	}
	
	/**
	 * This deletes a specific notifcation.
	 *
	 * This may be called by a client once it deems a notification handled.
	 *
	 * @param string $sPrincipalUri
	 * @param \Sabre\CalDAV\Notifications\INotificationType $oNotification
	 * @return void
	 */
	public function deleteNotification($sPrincipalUri, \Sabre\CalDAV\Notifications\INotificationType $oNotification){ }

	private function getPrincipalByEmail($sEmail) 
	{
		$oPrincipalBackend = $this->getPrincipalBackend();
		$mPrincipalPath = $oPrincipalBackend->searchPrincipals('principals', array('{http://sabredav.org/ns}email-address'=>$sEmail));
		if($mPrincipalPath == 0) 
		{
			throw new \Exception("Unknown email address");
		}
	// use the path to get the principal
		return $oPrincipalBackend->getPrincipalByPath($mPrincipalPath[0]);
	}
        
	private function getPrincipalBackend() 
	{
		return new \afterlogic\DAV\Principal\Backend\PDO($this->pdo, $this->dBPrefix);
	}
}