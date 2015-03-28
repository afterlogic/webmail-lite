<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

if(!defined('DAV_ROOT')) define('DAV_ROOT', 'dav/server.php/');
if(!defined('DAV_EMAIL_DEV')) define('DAV_EMAIL_DEV', '@');

class CDAVClient 
{
	const PROP_RESOURCETYPE = '{DAV:}resourcetype';
	const PROP_DISPLAYNAME = '{DAV:}displayname';
	const PROP_CALENDAR_DESCRIPTION = '{urn:ietf:params:xml:ns:caldav}calendar-description';
    const PROP_CALENDAR_ORDER = '{http://apple.com/ns/ical/}calendar-order';
    const PROP_CALENDAR_COLOR = '{http://apple.com/ns/ical/}calendar-color';
    const PROP_CALENDAR_DATA = '{urn:ietf:params:xml:ns:caldav}calendar-data';
    const PROP_ADDRESSBOOK_DATA = '{urn:ietf:params:xml:ns:carddav}address-data';
    const PROP_OWNER = '{DAV:}owner';
	const PROP_CTAG = '{http://calendarserver.org/ns/}getctag';
	const PROP_ETAG = '{DAV:}getetag';
	const PROP_CURRENT_USER_PRINCIPAL = '{DAV:}current-user-principal';
	const PROP_CALENDAR_HOME_SET = '{urn:ietf:params:xml:ns:caldav}calendar-home-set';
	const PROP_ADDRESSBOOK_HOME_SET = '{urn:ietf:params:xml:ns:carddav}addressbook-home-set';
	const PROP_GROUP_MEMBERSHIP = '{DAV:}group-membership';
	const PROP_GROUP_MEMBER_SET = '{DAV:}group-member-set';
	
	
	/**
	* @var string
	*/
	public $baseUrl;

	/**
	* @var string
	*/
	protected $user;

	/**
	* @var bool
	*/
	protected $connected;

	/**
	* @var string
	*/
	protected $protocol = 'http';

	/**
	* @var string
	*/
	protected $port = 80;

	/**
	* @var string
	*/
	protected $server;

	/**
	* @var bool
	*/
	public $isCustomServer = false;
	
	/**
	* @var \afterlogic\DAV\Client
	*/
	public $client;
	
	/**
	* @param string $baseUrl
	* @param string $user
	* @param string $pass
	*/
	public function __construct($baseUrl, $user, $pass)
 	{
		$this->client = new \afterlogic\DAV\Client(
				array(
					'baseUri' => $baseUrl,
					'userName' => $user,
					'password' => $pass
				)
		);
		
		$this->user = $user;
		
		$aUrlParts = parse_url($baseUrl);
		
		if (isset($aUrlParts['port']))
		{
			$this->port = $aUrlParts['port'];
		}

		$matches = array();
		if (preg_match('#^(https?)://([a-z0-9.-]+)(:([0-9]+))?(/.*)$#', $baseUrl, $matches)) 
		{
			$this->protocol = $matches[1];
			$this->server = $matches[2];
			$this->baseUrl = $matches[5];
		}
		
		$this->connected = false;
	}
	
	/**
	* @return bool
	*/
	public function Connect() 
	{
		if ($this->connected === false)
		{
			$this->connected = $this->TestConnection();
		}
		return $this->connected;
	}	
	
	/**
	* @return bool
	*/
	public function TestConnection() 
	{
		$res = $this->client->options_ex();
		$this->isCustomServer = $res['custom-server'];
		
		return true;
	}	

	/**
	* @return string
	*/
	public function GetBaseUrl() 
	{
		return $this->baseUrl;
	}

	/**
	* @return string
	*/
	public function GetRootUrl($url) 
	{
		$urlParts = parse_url($url);
		$path = '/';
		if (isset($urlParts['path']))
		{
			$path = $urlParts['path'];
		}
		return $path;
	}

	/**
	* @return string
	*/
	public function GetUser() 
	{
		return $this->user;
	}

	/**
	* @return string
	*/
	public function GetServer() 
	{
		return $this->server;
	}

	/**
	* @return string
	*/
	public function GetServerUrl() 
	{
		$sPort = '';
		if ((int)$this->port != 80)
		{
			$sPort = ':' . $this->port;
		}
		return $this->protocol . '://' . $_SERVER['SERVER_NAME'] . $sPort;
	}

	/**
	* @param string $sUrl
	* @param string $sData
	*
	* @return string
	*/
	function CreateItem($sUrl, $sData) 
	{
		return $this->client->request(
				'PUT', 
				$sUrl, 
				$sData, 
				array(
					'If-None-Match' => '*'
				)
		);
	}
	
	/**
	* @param string $sUrl
	* @param string $sData
	*
	* @return string
	*/
	function UpdateItem($sUrl, $sData, $sEtag = '*') 
	{
		return $this->client->request(
				'PUT', 
				$sUrl, 
				$sData, 
				array(
//					'If-Match' => '"'.$sEtag.'"'
				)
		);
	}
	
	/**
	* @param string $url
	* @param string $icalData
	*
	* @return string
	*/
	function MoveItem($url, $newUrl) 
	{
		return $this->client->request(
				'MOVE', 
				$url, 
				'', 
				array(
					'destination' => $newUrl
				)
		);
	}

	/**
	* @param string $sSystemName
	* @param string $sDisplayName
	* @param string $sDescription
	* @param int	$iOrder
	* @param string $sColor
	* @param string $sCalendarHome
	*
	* @return string
	*/
	function CreateCalendar($sSystemName, $sDisplayName, $sDescription, $iOrder, $sColor, 
			$sCalendarHome)
	{
		$xml = 
'<?xml version="1.0" encoding="UTF-8" ?>
<c:mkcalendar xmlns:c="'.\Sabre\CalDAV\Plugin::NS_CALDAV.'" xmlns:d="DAV:" xmlns:ic="http://apple.com/ns/ical/">
	<d:set>
		<d:prop>
			<d:displayname>'.$sDisplayName.'</d:displayname>
			<c:calendar-description>'.$sDescription.'</c:calendar-description>
			<ic:calendar-order>'.$iOrder.'</ic:calendar-order>
			<ic:calendar-color>'.$sColor.'</ic:calendar-color>
		</d:prop>
	</d:set>
</c:mkcalendar>';
		
		return $this->client->request(
				'MKCALENDAR', 
				$sCalendarHome.$sSystemName, 
				$xml, 
				array(
					'Content-Type' => 'application/xml', 
					'Depth' => '1'
				)
		);
	}	

	/**
	* @param string $sCalendarId
	* @param string $sDisplayName
	* @param string $sDescription
	* @param int	 $iOrder
	* @param string $sColor
	*
	* @return array
	*/
	function UpdateCalendar($sCalendarId, $sDisplayName, $sDescription, $iOrder, $sColor)
	{
		return $this->client->propPatch(
				$sCalendarId, 
				array(
					self::PROP_DISPLAYNAME => $sDisplayName,
					self::PROP_CALENDAR_DESCRIPTION => $sDescription,
					self::PROP_CALENDAR_ORDER => $iOrder,
					self::PROP_CALENDAR_COLOR => $sColor
				)
		);
	}
	
	/**
	* @param string $sCalendarId
	* @param string $sColor
	*
	* @return array
	*/
	function UpdateCalendarColor($sCalendarId, $sColor)
	{
		return $this->client->propPatch(
				$sCalendarId, 
				array(
					self::PROP_CALENDAR_COLOR => $sColor
				)
		);
	}

	/**
	* @param string $url
	*/
	function DeleteItem($url)
	{
		return $this->client->request('DELETE', $url);		
	}

	/**
	* @param string $filter
	* @param string $url
	*
	* @return array
	*/
	function QueryCal($filter, $url = '') 
	{
		$xml = 
'<?xml version="1.0" encoding="utf-8" ?>
<c:calendar-query xmlns:d="DAV:" xmlns:c="'.\Sabre\CalDAV\Plugin::NS_CALDAV.'">
	<d:prop>
		<d:getetag/>
        <c:calendar-data/>
	</d:prop>
	'.$filter.'
</c:calendar-query>';

		$res = array();
		try
		{
			$res = $this->client->request(
					'REPORT', 
					$url, 
					$xml, 
					array(
						'Content-Type' => 'application/xml', 
						'Depth' => '1'
					)
			);
		}
		catch(\Sabre\DAV\Exception $ex)
		{
			return false;
		}
		
		$aStatus = $this->client->parseMultiStatus($res['body']);

        $report = array();
		foreach($aStatus as $key => $props) 
		{
			$response = array();
			if (count($props) > 0)
			{
				$response['url'] = $url;
				$response['href'] = basename($key);
				$response['etag'] = isset($props[200]) ? preg_replace('/^"?([^"]+)"?/', '$1', $props[200][self::PROP_ETAG]) : '';
				$response['data'] = isset($props[200]) ? $props[200][self::PROP_CALENDAR_DATA] : '';

				$report[] = $response;
			}
        }		
		return $report;
	}
	
	/**
	* @param string $filter
	* @param string $url
	*
	* @return array
	*/
	function QueryCard($filter, $url = '') 
	{
		$xml = 
'<?xml version="1.0" encoding="utf-8" ?>
<c:addressbook-query xmlns:d="DAV:" xmlns:c="'.\Sabre\CardDAV\Plugin::NS_CARDDAV.'">
	<d:prop>
		<d:getetag/>
		<c:address-data>
			<c:allprop />
		</c:address-data>
	</d:prop>
	'.$filter.'
</c:addressbook-query>';

		$res = array();
		try
		{
			$res = $this->client->request(
					'REPORT', 
					$url, 
					$xml, 
					array(
						'Content-Type' => 'application/xml', 
						'Depth' => '1'
					)
			);
		}
		catch(\Sabre\DAV\Exception $ex)
		{
			return false;
		}

		$aStatus = $this->client->parseMultiStatus($res['body']);

        $report = array();
		foreach($aStatus as $key => $props) 
		{
			$response = array();
			$response['href'] = basename($key);
			$response['etag'] = isset($props[200]) ? preg_replace('/^"?([^"]+)"?/', '$1', $props[200][self::PROP_ETAG]) : '';
			$response['data'] = isset($props[200]) ? $props[200][self::PROP_ADDRESSBOOK_DATA] : '';
			
			$report[] = $response;
        }		
		return $report;
	}

	function GetVcards($url = '', $sSearch = '', $sGroupId = '') 
	{    
		$sFilter = '';
		$sGroupFilter = '';
		$sSearchFilter = '';
		
		if (!empty($sGroupId))
		{
			$sGroupFilter =
'	<c:prop-filter name="CATEGORIES">
		<c:text-match icollation="i;octet" match-type="contains">'.$sGroupId.'</c:text-match>
	</c:prop-filter>';			
		}
		
		if (!empty($sSearch))
		{
			$sSearchFilter = 
'	<c:prop-filter name="FN">
		<c:text-match icollation="i;octet" match-type="contains">'.$sSearch.'</c:text-match>
	</c:prop-filter>
	<c:prop-filter name="N">
		<c:text-match icollation="i;octet" match-type="contains">'.$sSearch.'</c:text-match>
	</c:prop-filter>
	<c:prop-filter name="EMAIL">
		<c:text-match icollation="i;octet" match-type="contains">'.$sSearch.'</c:text-match>
	</c:prop-filter>
	<c:prop-filter name="NICKNAME">
		<c:text-match icollation="i;octet" match-type="contains">'.$sSearch.'</c:text-match>
	</c:prop-filter>';
		}
		
		if (!empty($sSearch) || !empty($sGroupId))
		{
$sFilter = 
' <c:filter>
' . $sSearchFilter 
  . $sGroupFilter . '
  </c:filter>';			
		}
		
		return $this->QueryCard($sFilter, $url);
	}
	
	/**
	* @param timestamp $start
	* @param timestamp $end
	*
	* @return string
	*/
	function GetTimeRange($start = null, $end = null) 
	{
		$timeRange = '';
		$startRange = '';
		$endRange = '';
		if (isset($start) || isset($end))
		{
			if (isset($start))
			{
				$startRange = 'start="'.$start.'"';
			}
			if (isset($end))
			{
				$endRange = 'end="'.$end.'"';
			}
			$timeRange = sprintf('<c:time-range %s %s/>', $startRange, $endRange);
		}
		return $timeRange;
	}
	
	/**
	* @param string    $url
	* @param timestamp $start
	* @param timestamp $finish
	*
	* @return array
	*/
	function GetEvents($url = '', $start = null, $finish = null) 
	{
		$timeRange = $this->GetTimeRange($start, $finish);
		$url = rtrim($url, '/') . '/';

		$filter = 
'<c:filter>
	<c:comp-filter name="VCALENDAR">
		<c:comp-filter name="VEVENT">
			'.$timeRange.'
		</c:comp-filter>
	</c:comp-filter>
</c:filter>';
		return $this->QueryCal($filter, $url);
	}

	/**
	* @param string $sCalendarUrl
	* @param string $sUid
	*
	* @return array
	*/
	function GetEventByUid($sCalendarUrl, $sUid) 
	{
		$filter = 
' <c:filter>
    <c:comp-filter name="VCALENDAR">
          <c:comp-filter name="VEVENT">
                <c:prop-filter name="UID">
                        <c:text-match icollation="i;octet">'.$sUid.'</c:text-match>
                </c:prop-filter>
          </c:comp-filter>
    </c:comp-filter>
  </c:filter>';
		$result = $this->QueryCal($filter, $sCalendarUrl);
		if ($result !== false)
		{
			return current($result);
		}
		else
		{
			return false;
		}
	}

	/**
	* @param string    $url
	*
	* @return string
	*/
	function GetItem($url = '') 
	{
		$res = $this->clietn->request('GET', $url);
		if ($res !== false)
		{
			return $res['body'];
		}
		return $res;
	}
	
	/**
	* @param string    $url
	* @param timestamp $start
	* @param timestamp $finish
	*
	* @return array
	*/
	function GetAlarms($url = '', $start = null, $finish = null) 
	{
		$timeRange = $this->GetTimeRange($start, $finish);
		$url = rtrim($url, '/') . '/';

		$filter = 
'<c:filter>
	<c:comp-filter name="VCALENDAR">
		<c:comp-filter name="VEVENT">
			<c:comp-filter name="VALARM">
				'.$timeRange.'
			</c:comp-filter>
		</c:comp-filter>
	</c:comp-filter>
</c:filter>';
//		$recurrenceSet = '<c:limit-recurrence-set start="'.$start.'" end="'.$finish.'"/>';
	
		return $this->QueryCal($filter, $url);
	}
	
	
	/**
	* @param timestamp $start
	* @param timestamp $finish
	* @param boolean   $completed
	* @param boolean   $cancelled
	* @param string    $url
	*
	* @return array
	*/
	function GetTodos($start, $finish, $completed = false, $cancelled = false, $url = '') 
	{
		$timeRange = $this->GetTimeRange($start, $finish);

		// Warning!  May contain traces of double negatives...
		$negateCancelled = ($cancelled === true ? 'no' : 'yes');
		$negateCompleted = ($cancelled === true ? 'no' : 'yes');

		$filter = 
'<c:filter>
	<c:comp-filter name="VCALENDAR">
		<c:comp-filter name="VTODO">
			<c:prop-filter name="STATUS">
				<c:text-match negate-condition="'.$negateCompleted.'">COMPLETED</c:text-match>
			</c:prop-filter>
			<c:prop-filter name="STATUS">
				<C:text-match negate-condition="'.$negateCancelled.'">CANCELLED</c:text-match>
			</c:prop-filter>
			' . $timeRange . '
		</c:comp-filter>
	</c:comp-filter>
</c:filter>';

		return $this->QueryCal($filter, $url);
	}

	/**
	* @param string url
	*
	* @return array
	*/
	function GetCalendar($url = '') 
	{
		$filter = 
'<c:filter>
	<c:comp-filter name="VCALENDAR"></c:comp-filter>
</c:filter>';

		return $this->QueryCal($filter, $url);
	}
	
	function GetCurrentPrincipal()
	{
		$res = $this->client->propFind('', array(self::PROP_CURRENT_USER_PRINCIPAL));
			
		return $res[self::PROP_CURRENT_USER_PRINCIPAL];
	}	
	
	/**
	* @param string $principal
	*/
	function GetPrincipalMembers($principal = '')
	{
		$res = array();
		try
		{
			$res = $this->client->propFind(
					$principal, 
					array(
						self::PROP_GROUP_MEMBERSHIP
					), 
					1
			);
		}
		catch(Exception $ex)
		{
			return array();
		}

		return $res[self::PROP_GROUP_MEMBERSHIP];
	}	
	
	/**
	* @param $principalUrl string
	*/
	function GetCalendarHomeSet($principalUrl = '')
	{
		$props = $this->client->propFind(
				$principalUrl, 
				array(
					self::PROP_CALENDAR_HOME_SET
				)
		);
		return $props[self::PROP_CALENDAR_HOME_SET];
	}

	/**
	* @param $principalUrl string
	*/
	function GetAddressBookHomeSet($principalUrl = '')
	{
		$props = $this->client->propFind(
				$principalUrl, 
				array(
					self::PROP_ADDRESSBOOK_HOME_SET
				)
		);
		return $props[self::PROP_ADDRESSBOOK_HOME_SET];
	}

	function GetAddressBooks($url = '')
	{
		$aProps = $this->client->propFind(
				$url, 
				array(
					self::PROP_RESOURCETYPE,
					self::PROP_DISPLAYNAME
				), 
				1
		);
		return $aProps;
	}
	
	function GetCalendars($url = '')
	{
		$calendars = array();
				
		$aProps = $this->client->propFind(
				$url, 
				array(
					self::PROP_RESOURCETYPE,
					self::PROP_DISPLAYNAME,
					self::PROP_OWNER,
					self::PROP_CTAG, 
					self::PROP_CALENDAR_DESCRIPTION, 
					self::PROP_CALENDAR_COLOR, 
					self::PROP_CALENDAR_ORDER
				), 
				1
		);
		
		foreach($aProps as $key=>$props)
		{
			if ($props['{DAV:}resourcetype']->is('{'.\Sabre\CalDAV\Plugin::NS_CALDAV.'}calendar')) 
			{
				$calendar = new CCalendar($key);

				$calendar->CTag = $props[self::PROP_CTAG];
				$calendar->DisplayName = $props[self::PROP_DISPLAYNAME]; 
				$calendar->Principals[] = isset($props[self::PROP_OWNER])?$props[self::PROP_OWNER]:'';
				$calendar->Description = isset($props[self::PROP_CALENDAR_DESCRIPTION])?$props[self::PROP_CALENDAR_DESCRIPTION]:'';
				$calendar->Color = isset($props[self::PROP_CALENDAR_COLOR])?$props[self::PROP_CALENDAR_COLOR]:'';
				if (strlen($calendar->Color) > 7)
				{
					$calendar->Color = substr($calendar->Color, 0, 7);
				}

				if (isset($props[self::PROP_CALENDAR_ORDER]))
				{
					$calendar->Order = $props[self::PROP_CALENDAR_ORDER];
				}

				$calendars[$calendar->Id] = $calendar;
			}
		}

		return $calendars;
	}	
	
	function GetVcardByUid($uid, $url = '') 
	{
		$filter = "";

		if ($uid) 
		{
			$filter = 
'<c:filter>
	<c:prop-filter name="UID">
		<c:text-match collation="i;unicode-casemap" match-type="equals">'.$uid.'</c:text-match>
	</c:prop-filter>
</c:filter>';
		}

		return $this->QueryCard($filter, $url);
	}
	
	function GetProxies($sProxy) 
	{
		$res = array();
		try
		{
			$res = $this->client->propFind(
					$sProxy, 
					array(
						self::PROP_GROUP_MEMBER_SET
					), 
					1
			);
		}
		catch(Exception $ex)
		{
			return array();
		}

		return $res[self::PROP_GROUP_MEMBER_SET];
	}

	function AddProxy($proxy, $to)
	{
		$sProxyStr = '';
		$aCurrentProxies = array();
		$duplicate = false;
		
		$aCurrentProxies = $this->GetProxies($proxy);

		if($aCurrentProxies)
		{
			foreach($aCurrentProxies as $sCurrentProxy => $val)
			{
				$sCurrentProxy = ltrim($sCurrentProxy, DAV_ROOT);
				if($sCurrentProxy == $proxy)
				{
					$duplicate = true;
				}
				$sProxyStr .= '<d:href>' . $sCurrentProxy . '</d:href>';
			}
		}
		if($duplicate)
		{
			return false;		
		}

		$sProxyStr .= '<d:href>' . \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $to . '</d:href>';

		return $this->client->propPatch($proxy, array('group-member-set'=>$sProxyStr));
	}

	function DeleteProxy($proxy, $to)
	{
		$aCurrentProxies = $this->GetProxies($proxy);

		$sProxyStr = "";
		
		if($aCurrentProxies)
		{
			foreach($aCurrentProxies as $sCurrentProxy => $val)
			{
				$sCurrentProxy = ltrim($sCurrentProxy, DAV_ROOT);
				$sProxyStr .= '<d:href>' . $sCurrentProxy . '</d:href>';
			}
		}

		$sProxyStr = str_replace('<d:href>' . \afterlogic\DAV\Constants::PRINCIPALS_PREFIX . '/' . $to . '</d:href>', '', $sProxyStr);
		
		return $this->client->propPatch(
				$proxy, 
				array(
					'group-member-set'=>$sProxyStr
				)
		);
	}	
	
    /**
     * Returns a list of calendar homes for principals the current
     * user has access to through the calendar-proxy functionality.
     * 
     * @return array 
     */
    public function GetCalendarProxiedFor($principalUrl) 
	{
        $body = 
'<?xml version="1.0"?>
<d:expand-property xmlns:d="DAV:">
    <d:property name="calendar-proxy-read-for" namespace="'.\Sabre\CalDAV\Plugin::NS_CALENDARSERVER.'">
        <d:property name="calendar-home-set" namespace="'.\Sabre\CalDAV\Plugin::NS_CALDAV.'" />
    </d:property>
    <d:property name="calendar-proxy-write-for" namespace="'.\Sabre\CalDAV\Plugin::NS_CALENDARSERVER.'">
        <d:property name="calendar-home-set" namespace="'.\Sabre\CalDAV\Plugin::NS_CALDAV.'" />
    </d:property>
</d:expand-property>';

		$res = $this->client->request(
				'REPORT', 
				$principalUrl, 
				$body, 
				array(
					'Content-Type' => 'application/xml', 
					'Depth' => '1'
				)
		);
		
		if(isset($res['body']))
		{
			$data = new DOMDocument();
			
			$data->loadXML($res['body'], LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NSCLEAN);
			$xp = new DOMXPath($data);

			$xp->registerNamespace('c', \Sabre\CalDAV\Plugin::NS_CALDAV);
			$xp->registerNamespace('cs', \Sabre\CalDAV\Plugin::NS_CALENDARSERVER);
			$xp->registerNamespace('d', 'urn:dav');
			$values = array();
			
			$result = $xp->query("/d:multistatus/d:response/d:propstat/d:prop/cs:calendar-proxy-read-for/d:response/d:propstat/d:prop/c:calendar-home-set/d:href");
			foreach($result as $elem)
			{
				$values[] = array(
					'href' => $elem->nodeValue, 
					'mode' => 'read'
				);
			}
			
			$result = $xp->query("/d:multistatus/d:response/d:propstat/d:prop/cs:calendar-proxy-write-for/d:response/d:propstat/d:prop/c:calendar-home-set/d:href");
			foreach($result as $elem)
			{
				$values[] = array(
					'href' => $elem->nodeValue, 
					'mode' => 'write'
				);
			}

			return $values;
		}
		return array();
    }
	
}
