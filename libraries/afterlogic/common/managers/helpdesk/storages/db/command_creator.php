<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Helpdesk
 */
class CApiHelpdeskCommandCreator extends api_CommandCreator
{
	/**
	 * @param string $sWhere
	 * @return string
	 */
	protected function getUserByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskUser::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_users WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->Prefix(), $sWhere);
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iHelpdeskUserId
	 * @return string
	 */
	public function GetUserById($iIdTenant, $iHelpdeskUserId)
	{
		return $this->getUserByWhere(sprintf('%s = %d AND %s = %d',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('id_helpdesk_user'), $iHelpdeskUserId));
	}

	/**
	 * @param int $iHelpdeskUserId
	 * @return string
	 */
	public function GetUserByIdWithoutTenantID($iHelpdeskUserId)
	{
		return $this->getUserByWhere(sprintf('%s = %d',
			$this->escapeColumn('id_helpdesk_user'), $iHelpdeskUserId));
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 * @return string
	 */
	public function GetUserByEmail($iIdTenant, $sEmail)
	{
		return $this->getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('email'), strtolower($this->escapeString($sEmail))));
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 * @return string
	 */
	public function GetUserByNotificationEmail($iIdTenant, $sEmail)
	{
		return $this->getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('notification_email'), strtolower($this->escapeString($sEmail))));
	}

	public function GetUserBySocialId($iIdTenant, $sSocialId)
	{
		return $this->getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('social_id'), strtolower($this->escapeString($sSocialId))));
	}
	
	/**
	 * @param int $iIdTenant
	 * @param string $sActivateHash
	 * @return string
	 */
	public function GetUserByActivateHash($iIdTenant, $sActivateHash)
	{
		return $this->getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('activate_hash'), $this->escapeString($sActivateHash)));
	}
	
	/**
	 * @param int $iIdTenant
	 * @return string
	 */
	public function GetAgentsEmailsForNotification($iIdTenant)
	{
		return sprintf('SELECT email FROM %sahd_users WHERE id_tenant = %d AND mail_notifications = 1 AND is_agent = 1 AND blocked = 0',
			$this->Prefix(), $iIdTenant);
	}

	/**
	 * @return string
	 */
	public function GetNextHelpdeskIdForMonitoring($iLimitAddInMin = 5)
	{
		return sprintf('SELECT id_tenant FROM %sawm_tenants WHERE disabled = 0 AND '.
			'hd_fetcher_type > 0 AND hd_admin_email_account <> \'\' AND (hd_fetcher_timer = 0 OR hd_fetcher_timer < %d)',
			$this->Prefix(), time() - $iLimitAddInMin * 60);
	}

	/**
	 * @param int $iIdTenant
	 * @return string
	 */
	public function UpdateHelpdeskFetcherTimer($iIdTenant)
	{
		return sprintf('UPDATE %sawm_tenants SET hd_fetcher_timer = %d WHERE id_tenant = %d', $this->Prefix(), time(), $iIdTenant);
	}

	/**
	 * @return string
	 */
	public function GetHelpdeskMailboxLastUid($iIdTenant, $sEmail)
	{
		return sprintf('SELECT last_uid FROM %sahd_fetcher WHERE id_tenant = %d AND email = %s',
			$this->Prefix(), $iIdTenant, $this->escapeString(strtolower($sEmail)));
	}
	
	/**
	 * @return string
	 */
	public function AddHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid)
	{
		return sprintf('INSERT INTO %sahd_fetcher (id_tenant, email, last_uid) VALUES (%d, %s, %d)', $this->Prefix(),
			$iIdTenant, $this->escapeString(strtolower($sEmail)), $iLastUid);
	}

	/**
	 * @return string
	 */
	public function ClearHelpdeskMailboxLastUid($iIdTenant, $sEmail)
	{
		return sprintf('DELETE FROM %sahd_fetcher WHERE id_tenant = %d AND email = %s', $this->Prefix(),
			$iIdTenant, $this->escapeString(strtolower($sEmail)));
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $niExceptUserId = null
	 * @return string
	 */
	public function UserExists(CHelpdeskUser $oHelpdeskUser, $niExceptUserId = null)
	{
		$sAddSql = (is_integer($niExceptUserId)) ? ' AND id_helpdesk_user <> '.$niExceptUserId : '';

		$sSql = 'SELECT COUNT(id_helpdesk_user) as item_count FROM %sahd_users
WHERE %s = %s AND %s = %s%s';

		return trim(sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('email'), $this->escapeString(strtolower($oHelpdeskUser->Email)),
			$sAddSql
		));
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aIdList
	 *
	 * @return string
	 */
	public function UserInformation(CHelpdeskUser $oHelpdeskUser, $aIdList)
	{
		$sSql = 'SELECT id_helpdesk_user, email, name, is_agent, notification_email FROM %sahd_users WHERE %s = %d AND %s IN (%s)';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_user'), implode(', ', $aIdList)
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return string
	 */
	public function CreateUser(CHelpdeskUser $oHelpdeskUser)
	{
		$aResults = api_AContainer::DbInsertArrays($oHelpdeskUser, $this->oHelper);

		if (!empty($aResults[0]) && !empty($aResults[1]))
		{
			$sSql = 'INSERT INTO %sahd_users ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		
		return '';
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return string
	 */
	public function UpdateUser(CHelpdeskUser $oHelpdeskUser)
	{
		$aResult = api_AContainer::DbUpdateArray($oHelpdeskUser, $this->oHelper);

		$sSql = 'UPDATE %sahd_users SET %s WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_user'), $oHelpdeskUser->IdHelpdeskUser
		);
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iIdHelpdeskUser
	 * @return string
	 */
	public function SetUserAsBlocked($iIdTenant, $iIdHelpdeskUser)
	{
		$sSql = 'UPDATE %sahd_users SET blocked = 1 WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('id_helpdesk_user'), $iIdHelpdeskUser
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @return string
	 */
	public function DeleteUser(CHelpdeskUser $oHelpdeskUser)
	{
		$sSql = 'DELETE FROM %sahd_users WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_user'), $oHelpdeskUser->IdHelpdeskUser
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 * @param bool $bSetArchive = true
	 *
	 * @return string
	 */
	public function ArchiveThreads(CHelpdeskUser $oHelpdeskUser, $aThreadIds, $bSetArchive = true)
	{
		$sSql = 'UPDATE %sahd_threads SET %s = %d WHERE %s = %d AND %s IN (%d)';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('archived'), $bSetArchive ? 1 : 0,
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), implode(', ', $aThreadIds)
		);
	}

	/**
	 * @return string
	 */
	public function ArchiveOutdatedThreads()
	{
		$sSql = 'UPDATE %sahd_threads SET %s = %d WHERE %s < %s';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('archived'), 1,
			$this->escapeColumn('updated'),
			$this->oHelper->TimeStampToDateFormat(time() - (3600 * 24 * 7), true)
		);
	}

	/**
	 * @return string
	 */
	public function NextOutdatedThreadForNotificate()
	{
		$sSql = 'SELECT id_helpdesk_thread, id_tenant, id_owner FROM %sahd_threads'.
			' WHERE %s = %d AND %s = %d AND id_owner <> last_post_owner_id AND  %s < %s AND %s > %s';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('archived'), 0,
			$this->escapeColumn('notificated'), 0,
			$this->escapeColumn('updated'),
			$this->oHelper->TimeStampToDateFormat(time() - (3600 * 24 * 2), true),
			$this->escapeColumn('updated'),
			$this->oHelper->TimeStampToDateFormat(time() - (3600 * 24 * 7), true)
		);
	}

	/**
	 * @return string
	 */
	public function SetOutdatedThreadNotificated($iIdTenant, $iIdHelpdeskThread)
	{
		$sSql = 'UPDATE %sahd_threads SET %s = %d WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('notificated'), 1,
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $iIdHelpdeskThread
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param array $aPostIds
	 *
	 * @return string
	 */
	public function DeletePosts(CHelpdeskUser $oHelpdeskUser, $oThread, $aPostIds)
	{
		$sSql = 'UPDATE %sahd_posts SET deleted = 1 WHERE %s = %d AND %s = %d AND %s IN (%d)';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $oThread->IdHelpdeskThread,
			$this->escapeColumn('id_helpdesk_post'), implode(', ', $aPostIds)
		);
	}

	/**
	 * @return string
	 */
	public function ClearUnregistredUsers()
	{
		$sSql = 'DELETE FROM %sahd_users WHERE activated = 0 AND DATE_ADD(%s, INTERVAL %d DAY) > NOW()';
		return sprintf($sSql, $this->Prefix(), $this->escapeColumn('created'), 3);
	}

	/**
	 * @param int $iTenantID
	 * @param string $sHash
	 * 
	 * @return string
	 */
	public function GetThreadIdByHash($iTenantID, $sHash)
	{
		return sprintf('SELECT id_helpdesk_thread FROM %sahd_threads WHERE %s = %d AND %s = %s',
			$this->Prefix(),
			$this->escapeColumn('id_tenant'), $iTenantID,
			$this->escapeColumn('str_helpdesk_hash'), $this->escapeString($sHash)
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iIdThread
	 * @return string
	 */
	public function GetThreadById(CHelpdeskUser $oHelpdeskUser, $iIdThread)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskThread::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		return sprintf('SELECT %s FROM %sahd_threads WHERE %s = %d AND %s = %d',
			implode(', ', $aMap), $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $iIdThread
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 * @return string
	 */
	public function CreateThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aResults = api_AContainer::DbInsertArrays($oHelpdeskThread, $this->oHelper);

		if (!empty($aResults[0]) && !empty($aResults[1]))
		{
			$sSql = 'INSERT INTO %sahd_threads ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 * @return string
	 */
	public function UpdateThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aResult = api_AContainer::DbUpdateArray($oHelpdeskThread, $this->oHelper);

		$sSql = 'UPDATE %sahd_threads SET %s WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(), implode(', ', $aResult),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $oHelpdeskThread->IdHelpdeskThread
		);
	}

	private function buildThreadsWhere(CHelpdeskUser $oHelpdeskUser, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '', $iSearchOwner = 0)
	{
		$aWhere = array();

		$aWhere[] = $this->escapeColumn('id_tenant').' = '.$oHelpdeskUser->IdTenant;

		$aWhere[] = $this->escapeColumn('archived').' = '.(EHelpdeskThreadFilterType::Archived === $iFilter ? 1 : 0);
		if (EHelpdeskThreadFilterType::Archived !== $iFilter)
		{
			switch ($iFilter)
			{
				case EHelpdeskThreadFilterType::PendingOnly:
					$aWhere[] = $this->escapeColumn('type').' IN ('.implode(',', array(
						EHelpdeskThreadType::Pending,
						EHelpdeskThreadType::Deferred
					)).')';
					break;
				case EHelpdeskThreadFilterType::ResolvedOnly:
					$aWhere[] = $this->escapeColumn('type').' = '.EHelpdeskThreadType::Resolved;
					break;
				case EHelpdeskThreadFilterType::Open:
					if ($oHelpdeskUser->IsAgent)
					{
						$aWhere[] = '(('.
							$this->escapeColumn('type').' IN ('.implode(',', array(
								EHelpdeskThreadType::Pending,
								EHelpdeskThreadType::Deferred,
								EHelpdeskThreadType::Waiting
							)).')'.
						') OR ('.
							$this->escapeColumn('id_owner').' = '.$oHelpdeskUser->IdHelpdeskUser.' AND '.
							$this->escapeColumn('type').' IN ('.implode(',', array(
								EHelpdeskThreadType::Answered
							)).')'.
						'))';
					}
					else
					{
						$aWhere[] = $this->escapeColumn('type').' IN ('.implode(',', array(
							EHelpdeskThreadType::Waiting,
							EHelpdeskThreadType::Answered,
							EHelpdeskThreadType::Pending,
							EHelpdeskThreadType::Deferred
						)).')';
					}
					break;
				case EHelpdeskThreadFilterType::InWork:
					$aWhere[] = $this->escapeColumn('type').' IN ('.implode(',', array(
							EHelpdeskThreadType::Waiting,
							EHelpdeskThreadType::Answered,
							EHelpdeskThreadType::Pending,
							EHelpdeskThreadType::Deferred
						)).')';
					break;
			}
		}

		if (!$oHelpdeskUser->IsAgent)
		{
			$aWhere[] = $this->escapeColumn('id_owner').' = '.$oHelpdeskUser->IdHelpdeskUser;
		}

		if (0 < $iSearchOwner)
		{
			$aWhere[] = $this->escapeColumn('id_owner').' = '.((int) $iSearchOwner);
		}

		$sSearch = trim($sSearch);
		if (0 < strlen($sSearch))
		{
			$sSearchEscaped = '\'%'.$this->escapeString($sSearch, true, true).'%\'';

			$aWhere[] = '('.
				$this->escapeColumn('id_owner').' IN '.
					sprintf('(SELECT id_helpdesk_user FROM %sahd_users WHERE %s LIKE %s OR %s LIKE %s)', $this->Prefix(),
						$this->escapeColumn('email'), $sSearchEscaped,
						$this->escapeColumn('name'), $sSearchEscaped).
				' OR '.
				$this->escapeColumn('id_helpdesk_thread').' IN '.
					sprintf('(SELECT id_helpdesk_thread FROM %sahd_posts WHERE deleted = 0 AND %s LIKE %s)', $this->Prefix(),
						$this->escapeColumn('text'), $sSearchEscaped).
				')'
			;
		}

		return $aWhere;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iFilter = EHelpdeskThreadFilterType::All
	 * @param string $sSearch = ''
	 * @param int $iSearchOwner = 0
	 *
	 * @return string
	 */
	public function GetThreadsCount(CHelpdeskUser $oHelpdeskUser, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '', $iSearchOwner = 0)
	{
		$sSql = 'SELECT COUNT(id_helpdesk_thread) as item_count FROM %sahd_threads';
		$sSql = sprintf($sSql, $this->Prefix());

		$aWhere = $this->buildThreadsWhere($oHelpdeskUser, $iFilter, $sSearch, $iSearchOwner);
		if (is_array($aWhere) && 0 < count($aWhere))
		{
			$sSql .= ' WHERE '.implode(' AND ', $aWhere);
		}
		else
		{
			$sSql .= ' WHERE 1 = 0';
		}
		
		return $sSql;
	}

	/**
	 * @param int $iTenantId = 0
	 *
	 * @return string
	 */
	public function GetThreadsPendingCount($iTenantId)
	{
		$sSql = 'SELECT COUNT(*) as item_pending_count FROM %sahd_threads WHERE type = 1 AND id_tenant = %s AND archived = 0';

		return sprintf($sSql, $this->Prefix(), $iTenantId);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iOffset = 0
	 * @param int $iLimit = 20
	 * @param int $iFilter = EHelpdeskThreadFilterType::All
	 * @param string $sSearch = ''
	 * @param int $iSearchOwner = 0
	 *
	 * @return string
	 */
	public function GetThreads(CHelpdeskUser $oHelpdeskUser, $iOffset = 0, $iLimit = 20, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '', $iSearchOwner = 0)
	{
		$sSearch = trim($sSearch);

		$aWhere = array();
		$aMap = api_AContainer::DbReadKeys(CHelpdeskThread::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_threads';
		$sSql = sprintf($sSql, implode(', ', $aMap), $this->Prefix());

		$aWhere = $this->buildThreadsWhere($oHelpdeskUser, $iFilter, $sSearch, $iSearchOwner);
		if (is_array($aWhere) && 0 < count($aWhere))
		{
			$sSql .= ' WHERE '.implode(' AND ', $aWhere);
		}
		else
		{
			$sSql .= ' WHERE 1 = 0';
		}

		$sSql .= ' ORDER BY updated desc LIMIT '.((int) $iLimit).'  OFFSET '.((int) $iOffset);

		return $sSql;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 *
	 * @return string
	 */
	public function GetThreadsLastPostIds(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$sSql = 'SELECT DISTINCT id_helpdesk_thread, last_post_id FROM %sahd_reads WHERE %s = %d AND %s = %d AND %s IN (%s)';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_owner'), $oHelpdeskUser->IdHelpdeskUser,
			$this->escapeColumn('id_helpdesk_thread'), implode(',', $aThreadIds)
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 *
	 * @return string
	 */
	public function VerifyThreadIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$sSql = 'SELECT id_owner, id_helpdesk_thread FROM %sahd_threads WHERE %s IN (%s)';
		return sprintf($sSql, $this->Prefix(), $this->escapeColumn('id_helpdesk_thread'), implode(',', $aThreadIds));
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aPostIds
	 *
	 * @return string
	 */
	public function VerifyPostIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aPostIds)
	{
		$sSql = 'SELECT id_owner FROM %sahd_posts WHERE %s IN (%s)';
		return sprintf($sSql, $this->Prefix(), $this->escapeColumn('id_helpdesk_post'), implode(',', $aPostIds));
	}

	/**
	 *
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * 
	 * @return array
	 */
	private function buildPostsWhere(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$aWhere = array();
		
		$aWhere[] = $this->escapeColumn('id_tenant').' = '.$oHelpdeskUser->IdTenant;

		if (!$oHelpdeskUser->IsAgent || $oThread->IdOwner === $oHelpdeskUser->IdHelpdeskUser)
		{
			$aWhere[] = $this->escapeColumn('type').' <> '.EHelpdeskPostType::Internal;
		}

		$aWhere[] = $this->escapeColumn('id_helpdesk_thread').' = '.$oThread->IdHelpdeskThread;

		return $aWhere;
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 *
	 * @return string
	 */
	public function GetPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$sSql = 'SELECT COUNT(id_helpdesk_post) as item_count FROM %sahd_posts WHERE deleted = 0 AND %s = %d AND %s = %d%s';
		
		$aWhere = $this->buildPostsWhere($oHelpdeskUser, $oThread);
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $oThread->IdHelpdeskThread,
			0 < count($aWhere) ? ' AND '.implode(' AND ', $aWhere) : ''
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param int $iStartFromId = 0
	 * @param int $iLimit = 20
	 *
	 * @return string
	 */
	public function GetPosts(CHelpdeskUser $oHelpdeskUser, $oThread, $iStartFromId = 0, $iLimit = 20)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskPost::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_posts WHERE deleted = 0';
		$sSql = sprintf($sSql, implode(', ', $aMap), $this->Prefix());

		$aWhere = $this->buildPostsWhere($oHelpdeskUser, $oThread);

		if (0 < $iStartFromId)
		{
			$aWhere[] = $this->escapeColumn('id_helpdesk_post').' < '.$iStartFromId;
		}

		if (0 < count($aWhere))
		{
			$sSql .= ' AND '.implode(' AND ', $aWhere);
		}

		$sSql .= ' ORDER BY id_helpdesk_post desc LIMIT '.$iLimit;

		return $sSql;
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iPostId
	 *
	 * @return string
	 */
	public function GetAttachments(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskAttachment::GetStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_attachments';
		$sSql = sprintf($sSql, implode(', ', $aMap), $this->Prefix());

		$aWhere = array();
		if (0 < $oHelpdeskUser->IdTenant)
		{
			$aWhere[] = $this->escapeColumn('id_tenant').' = '.$oHelpdeskUser->IdTenant;
		}

		$aWhere[] = $this->escapeColumn('id_helpdesk_thread').' = '.$oHelpdeskThread->IdHelpdeskThread;

		return $sSql.' WHERE '.implode(' AND ', $aWhere);
	}

	/**
	 * @param array $aAttachments
	 *
	 * @return string
	 */
	public function AddAttachments($aAttachments)
	{
		$sSql = '';
		foreach ($aAttachments as $oItem)
		{
			$aResults = api_AContainer::DbInsertArrays($oItem, $this->oHelper);
			
			if (empty($sSql))
			{
				$sSql = sprintf('INSERT INTO %sahd_attachments ( %s ) VALUES', $this->Prefix(), implode(', ', $aResults[0]));
			}

			$sSql .= sprintf('( %s ),', implode(', ', $aResults[1]));
		}

		return trim($sSql, ',');
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskPost $oHelpdeskPost
	 * @return string
	 */
	public function CreatePost(CHelpdeskUser $oHelpdeskUser, CHelpdeskPost $oHelpdeskPost)
	{
		$aResults = api_AContainer::DbInsertArrays($oHelpdeskPost, $this->oHelper);

		if (!empty($aResults[0]) && !empty($aResults[1]))
		{
			$sSql = 'INSERT INTO %sahd_posts ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->Prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 * @return string
	 */
	public function ClearThreadSeen(CHelpdeskUser $oHelpdeskUser, $oHelpdeskThread)
	{
		$sSql = 'DELETE FROM %sahd_reads WHERE %s = %d AND %s = %d AND %s = %d';
		return sprintf($sSql, $this->Prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_owner'), $oHelpdeskUser->IdHelpdeskUser,
			$this->escapeColumn('id_helpdesk_thread'), $oHelpdeskThread->IdHelpdeskThread
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 * @return string
	 */
	public function SetThreadSeen(CHelpdeskUser $oHelpdeskUser, $oHelpdeskThread)
	{
		$sSql = 'INSERT INTO %sahd_reads ( id_tenant, id_owner, id_helpdesk_thread, last_post_id ) VALUES ( %d, %d, %d, %d )';
		return sprintf($sSql, $this->Prefix(),
			$oHelpdeskUser->IdTenant, $oHelpdeskUser->IdHelpdeskUser,
			$oHelpdeskThread->IdHelpdeskThread, $oHelpdeskThread->LastPostId
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 * @param int $iTimeoutInMin = 5
	 *
	 * @return string
	 */
	public function GetOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID, $iTimeoutInMin = 5)
	{
		$sSql = 'SELECT * FROM %sahd_online WHERE id_helpdesk_thread = %d AND id_tenant = %d AND ping_time > %d';
		return sprintf($sSql, $this->Prefix(), $iThreadID, $oHelpdeskUser->IdTenant,
			time() - $iTimeoutInMin * 60
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 *
	 * @return string
	 */
	public function ClearOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$sSql = 'DELETE FROM %sahd_online  WHERE id_helpdesk_user = %d AND id_tenant = %d AND id_helpdesk_thread = %d';
		return sprintf($sSql, $this->Prefix(),
			$oHelpdeskUser->IdHelpdeskUser, $oHelpdeskUser->IdTenant, $iThreadID
		);
	}

	/**
	 * @param int $iTimeoutInMin = 15
	 *
	 * @return string
	 */
	public function ClearAllOnline($iTimeoutInMin = 15)
	{
		$sSql = 'DELETE FROM %sahd_online WHERE ping_time < %d';
		return sprintf($sSql, $this->Prefix(),
			time() - $iTimeoutInMin * 60
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 * 
	 * @return string
	 */
	public function SetOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$sSql = 'INSERT INTO %sahd_online (id_helpdesk_thread, id_helpdesk_user, id_tenant, name, email, ping_time) VALUES ( %d, %d, %d, %s, %s, %d )';
		return sprintf($sSql, $this->Prefix(),
			$iThreadID, $oHelpdeskUser->IdHelpdeskUser, $oHelpdeskUser->IdTenant,
			$this->escapeString($oHelpdeskUser->Name), $this->escapeString($oHelpdeskUser->Email),
			time()
		);
	}
}

/**
 * @package Helpdesk
 */
class CApiHelpdeskCommandCreatorMySQL extends CApiHelpdeskCommandCreator
{
}

/**
 * @package Helpdesk
 */
class CApiHelpdeskCommandCreatorPostgreSQL extends CApiHelpdeskCommandCreator
{
}
