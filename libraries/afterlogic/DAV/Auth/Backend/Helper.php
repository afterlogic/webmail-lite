<?php

namespace afterlogic\DAV\Auth\Backend;

use afterlogic\DAV\Constants;

class Helper
{
    public function __construct(\PDO $pdo, $dBPrefix = '')
	{
        $this->pdo = $pdo;
		$this->dbPrefix = $dBPrefix;
    }

	public static function ValidateClient($sClient)
	{
		$bIsSync = false;
		if (isset($GLOBALS['server']) && $GLOBALS['server'] instanceof \Sabre\DAV\Server)
		{
			$aHeaders = $GLOBALS['server']->httpRequest->getHeaders();
			if (isset($aHeaders['user-agent']))
			{
				$sUserAgent = $aHeaders['user-agent'];
				if (strpos(strtolower($sUserAgent), 'afterlogic ' . strtolower($sClient)) !== false)
				{
					$bIsSync = true;
				}
			}
		}
		return $bIsSync;
	}

	public function CheckPrincipals($sUserName)
	{
		$sPrincipal = 'principals/' . $sUserName;

		$stmt = $this->pdo->prepare(
				'SELECT id FROM `'.$this->dbPrefix.Constants::T_PRINCIPALS.'`
					WHERE uri = ? LIMIT 1'
		);
		$stmt->execute(
				array(
					$sPrincipal
				)
		);
		$result = $stmt->fetchAll();
		if(count($result) === 0)
		{
			$stmt = $this->pdo->prepare(
					'INSERT INTO `'.$this->dbPrefix.Constants::T_PRINCIPALS.'`
						(uri,email,displayname) VALUES (?, ?, ?)'
			);
			$stmt->execute(
					array(
						$sPrincipal,
						$sUserName,
						''
					)
			);
		}

		$stmt = $this->pdo->prepare(
				'SELECT principaluri FROM `'.$this->dbPrefix.Constants::T_CALENDARS.'`
					WHERE principaluri = ? and uri = ? LIMIT 1'
		);
		$stmt->execute(
				array(
					$sPrincipal,
					Constants::CALENDAR_DEFAULT_NAME
				)
		);
		$result = $stmt->fetchAll();
		if (count($result) === 0)
		{
			$stmt = $this->pdo->prepare(
					'INSERT INTO `'.$this->dbPrefix.Constants::T_CALENDARS.'`
						(principaluri, displayname, uri, description, components, ctag, calendarcolor)
						VALUES (?, ?, ?, ?, ?, 1, ?)'
			);
			$stmt->execute(
					array(
						$sPrincipal,
						Constants::CalendarDefaultName,
						Constants::CALENDAR_DEFAULT_NAME,
						'',
						'VEVENT,VTODO',
						Constants::CALENDAR_DEFAULT_COLOR
					)
			);
		}

		$stmt = $this->pdo->prepare(
				'SELECT principaluri FROM `'.$this->dbPrefix.Constants::T_ADDRESSBOOKS.'`
					WHERE principaluri = ? and uri = ? LIMIT 1'
		);

		$stmt->execute(
				array(
					$sPrincipal,
					Constants::ADDRESSBOOK_DEFAULT_NAME
				)
		);
		$result = $stmt->fetchAll();
		$hasDefaultAddressbooks = (count($result) != 0);

		$stmt->execute(
				array(
					$sPrincipal,
					Constants::ADDRESSBOOK_COLLECTED_NAME
				)
		);
		$result = $stmt->fetchAll();
		$hasCollectedAddressbooks = (count($result) != 0);

		$stmt = $this->pdo->prepare(
				'INSERT INTO `'.$this->dbPrefix.Constants::T_ADDRESSBOOKS.'`
					(principaluri, displayname, uri, description, ctag)
					VALUES (?, ?, ?, ?, 1)'
		);
		if (!$hasDefaultAddressbooks)
		{
			$stmt->execute(
					array(
						$sPrincipal,
						Constants::ADDRESSBOOK_DEFAULT_DISPLAY_NAME,
						Constants::ADDRESSBOOK_DEFAULT_NAME,
						Constants::ADDRESSBOOK_DEFAULT_DISPLAY_NAME
					)
			);
		}
		if (!$hasCollectedAddressbooks)
		{
			$stmt->execute(
					array(
						$sPrincipal,
						Constants::ADDRESSBOOK_COLLECTED_DISPLAY_NAME,
						Constants::ADDRESSBOOK_COLLECTED_NAME,
						Constants::ADDRESSBOOK_COLLECTED_DISPLAY_NAME
					)
			);
		}
	}

}