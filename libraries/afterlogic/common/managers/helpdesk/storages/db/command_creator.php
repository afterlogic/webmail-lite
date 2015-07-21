<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @internal
 * 
 * @package Helpdesk
 * @subpackage Storages
 */
class CApiHelpdeskCommandCreator extends api_CommandCreator
{
	/**
	 * @param string $sWhere
	 *
	 * @return string
	 */
	protected function _getUserByWhere($sWhere)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskUser::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_users WHERE %s';

		return sprintf($sSql, implode(', ', $aMap), $this->prefix(), $sWhere);
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iHelpdeskUserId
	 *
	 * @return string
	 */
	public function getUserById($iIdTenant, $iHelpdeskUserId)
	{
		return $this->_getUserByWhere(sprintf('%s = %d AND %s = %d',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('id_helpdesk_user'), $iHelpdeskUserId));
	}

	/**
	 * @param int $iHelpdeskUserId
	 *
	 * @return string
	 */
	public function getUserByIdWithoutTenantID($iHelpdeskUserId)
	{
		return $this->_getUserByWhere(sprintf('%s = %d',
			$this->escapeColumn('id_helpdesk_user'), $iHelpdeskUserId));
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 *
	 * @return string
	 */
	public function getUserByEmail($iIdTenant, $sEmail)
	{
		return $this->_getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('email'), strtolower($this->escapeString($sEmail))));
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 *
	 * @return string
	 */
	public function getUserByNotificationEmail($iIdTenant, $sEmail)
	{
		return $this->_getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('notification_email'), strtolower($this->escapeString($sEmail))));
	}

	public function getUserBySocialId($iIdTenant, $sSocialId)
	{
		return $this->_getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('social_id'), strtolower($this->escapeString($sSocialId))));
	}
	
	/**
	 * @param int $iIdTenant
	 * @param string $sActivateHash
	 *
	 * @return string
	 */
	public function getUserByActivateHash($iIdTenant, $sActivateHash)
	{
		return $this->_getUserByWhere(sprintf('%s = %d AND %s = %s',
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('activate_hash'), $this->escapeString($sActivateHash)));
	}
	
	/**
	 * @param int $iIdTenant
	 *
	 * @return string
	 */
	public function getAgentsEmailsForNotification($iIdTenant)
	{
		return sprintf('SELECT email FROM %sahd_users WHERE id_tenant = %d AND mail_notifications = 1 AND is_agent = 1 AND blocked = 0',
			$this->prefix(), $iIdTenant);
	}

	/**
	 * @return string
	 */
	public function getNextHelpdeskIdForMonitoring($iLimitAddInMin = 5)
	{
		return sprintf('SELECT id_tenant FROM %sawm_tenants WHERE disabled = 0 AND hd_fetcher_type > 0 AND hd_admin_email_account <> \'\' AND (hd_fetcher_timer = 0 OR hd_fetcher_timer < %d)',
			$this->prefix(), time() - $iLimitAddInMin * 60);
	}

	/**
	 * @param int $iIdTenant
	 *
	 * @return string
	 */
	public function updateHelpdeskFetcherTimer($iIdTenant)
	{
		return sprintf('UPDATE %sawm_tenants SET hd_fetcher_timer = %d WHERE id_tenant = %d', $this->prefix(), time(), $iIdTenant);
	}

	/**
	 *  @param int $iIdTenant
	 *  @param string $sEmail
	 *
	 * @return string
	 */
	public function getHelpdeskMailboxLastUid($iIdTenant, $sEmail)
	{
		return sprintf('SELECT last_uid FROM %sahd_fetcher WHERE id_tenant = %d AND email = %s',
			$this->prefix(), $iIdTenant, $this->escapeString(strtolower($sEmail)));
	}
	
	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 * @param int $iLastUid
	 *
	 * @return string
	 */
	public function addHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid)
	{
		return sprintf('INSERT INTO %sahd_fetcher (id_tenant, email, last_uid) VALUES (%d, %s, %d)', $this->prefix(),
			$iIdTenant, $this->escapeString(strtolower($sEmail)), $iLastUid);
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 *
	 * @return string
	 */
	public function clearHelpdeskMailboxLastUid($iIdTenant, $sEmail)
	{
		return sprintf('DELETE FROM %sahd_fetcher WHERE id_tenant = %d AND email = %s', $this->prefix(),
			$iIdTenant, $this->escapeString(strtolower($sEmail)));
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $niExceptUserId Default value is **null**.
	 *
	 * @return string
	 */
	public function isUserExists(CHelpdeskUser $oHelpdeskUser, $niExceptUserId = null)
	{
		$sAddSql = (is_integer($niExceptUserId)) ? ' AND id_helpdesk_user <> '.$niExceptUserId : '';

		$sSql = 'SELECT COUNT(id_helpdesk_user) as item_count FROM %sahd_users WHERE %s = %s AND %s = %s%s';

		return trim(sprintf($sSql, $this->prefix(),
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
	public function userInformation(CHelpdeskUser $oHelpdeskUser, $aIdList)
	{
		$sSql = 'SELECT id_helpdesk_user, email, name, is_agent, notification_email FROM %sahd_users WHERE %s = %d AND %s IN (%s)';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_user'), implode(', ', $aIdList)
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 *
	 * @return string
	 */
	public function createUser(CHelpdeskUser $oHelpdeskUser)
	{
		$aResults = api_AContainer::DbInsertArrays($oHelpdeskUser, $this->oHelper);

		if (!empty($aResults[0]) && !empty($aResults[1]))
		{
			$sSql = 'INSERT INTO %sahd_users ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}
		
		return '';
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 *
	 * @return string
	 */
	public function updateUser(CHelpdeskUser $oHelpdeskUser)
	{
		$aResult = api_AContainer::DbUpdateArray($oHelpdeskUser, $this->oHelper);

		$sSql = 'UPDATE %sahd_users SET %s WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_user'), $oHelpdeskUser->IdHelpdeskUser
		);
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iIdHelpdeskUser
	 *
	 * @return string
	 */
	public function setUserAsBlocked($iIdTenant, $iIdHelpdeskUser)
	{
		$sSql = 'UPDATE %sahd_users SET blocked = 1 WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('id_helpdesk_user'), $iIdHelpdeskUser
		);
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iIdHelpdeskUser
	 *
	 * @return string
	 */
	public function deleteUser($iIdTenant, $iIdHelpdeskUser)
	{
		$sSql = 'DELETE FROM %sahd_users WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $iIdTenant,
			$this->escapeColumn('id_helpdesk_user'), $iIdHelpdeskUser
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 * @param bool $bSetArchive Default value is **true**.
	 *
	 * @return string
	 */
	public function archiveThreads(CHelpdeskUser $oHelpdeskUser, $aThreadIds, $bSetArchive = true)
	{
		$sSql = 'UPDATE %sahd_threads SET %s = %d WHERE %s = %d AND %s IN (%d)';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('archived'), $bSetArchive ? 1 : 0,
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), implode(', ', $aThreadIds)
		);
	}

	/**
	 * @return string
	 */
	public function archiveOutdatedThreads()
	{
		$sSql = 'UPDATE %sahd_threads SET %s = %d WHERE %s < %s';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('archived'), 1,
			$this->escapeColumn('updated'),
			$this->oHelper->TimeStampToDateFormat(time() - (3600 * 24 * 7), true)
		);
	}

	/**
	 * @return string
	 */
	public function nextOutdatedThreadForNotificate()
	{
		$sSql = 'SELECT id_helpdesk_thread, id_tenant, id_owner FROM %sahd_threads WHERE %s = %d AND %s = %d AND id_owner <> last_post_owner_id AND  %s < %s AND %s > %s';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('archived'), 0,
			$this->escapeColumn('notificated'), 0,
			$this->escapeColumn('updated'),
			$this->oHelper->TimeStampToDateFormat(time() - (3600 * 24 * 2), true),
			$this->escapeColumn('updated'),
			$this->oHelper->TimeStampToDateFormat(time() - (3600 * 24 * 7), true)
		);
	}

	/**
	 *  @param int $iIdTenant
	 *  @param int $iIdHelpdeskThread
	 *
	 * @return string
	 */
	public function setOutdatedThreadNotificated($iIdTenant, $iIdHelpdeskThread)
	{
		$sSql = 'UPDATE %sahd_threads SET %s = %d WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(),
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
	public function deletePosts(CHelpdeskUser $oHelpdeskUser, $oThread, $aPostIds)
	{
		$sSql = 'UPDATE %sahd_posts SET deleted = 1 WHERE %s = %d AND %s = %d AND %s IN (%d)';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $oThread->IdHelpdeskThread,
			$this->escapeColumn('id_helpdesk_post'), implode(', ', $aPostIds)
		);
	}

	/**
	 * @return string
	 */
	public function clearUnregistredUsers()
	{
		$sSql = 'DELETE FROM %sahd_users WHERE activated = 0 AND DATE_ADD(%s, INTERVAL %d DAY) > NOW()';
		return sprintf($sSql, $this->prefix(), $this->escapeColumn('created'), 3);
	}

	/**
	 * @param int $iTenantID
	 * @param string $sHash
	 * 
	 * @return string
	 */
	public function getThreadIdByHash($iTenantID, $sHash)
	{
		return sprintf('SELECT id_helpdesk_thread FROM %sahd_threads WHERE %s = %d AND %s = %s',
			$this->prefix(),
			$this->escapeColumn('id_tenant'), $iTenantID,
			$this->escapeColumn('str_helpdesk_hash'), $this->escapeString($sHash)
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iIdThread
	 *
	 * @return string
	 */
	public function getThreadById(CHelpdeskUser $oHelpdeskUser, $iIdThread)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskThread::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		return sprintf('SELECT %s FROM %sahd_threads WHERE %s = %d AND %s = %d',
			implode(', ', $aMap), $this->prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $iIdThread
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return string
	 */
	public function createThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aResults = api_AContainer::DbInsertArrays($oHelpdeskThread, $this->oHelper);

		if (!empty($aResults[0]) && !empty($aResults[1]))
		{
			$sSql = 'INSERT INTO %sahd_threads ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return string
	 */
	public function updateThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aResult = api_AContainer::DbUpdateArray($oHelpdeskThread, $this->oHelper);

		$sSql = 'UPDATE %sahd_threads SET %s WHERE %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(), implode(', ', $aResult),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $oHelpdeskThread->IdHelpdeskThread
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iFilter Default value is **0** EHelpdeskThreadFilterType::All.
	 * @param string $sSearch
	 * @param int $iSearchOwner
	 *
	 * @return array
	 */
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
					sprintf('(SELECT id_helpdesk_user FROM %sahd_users WHERE %s LIKE %s OR %s LIKE %s)', $this->prefix(),
						$this->escapeColumn('email'), $sSearchEscaped,
						$this->escapeColumn('name'), $sSearchEscaped).
				' OR '.
				$this->escapeColumn('id_helpdesk_thread').' IN '.
					sprintf('(SELECT id_helpdesk_thread FROM %sahd_posts WHERE deleted = 0 AND %s LIKE %s)', $this->prefix(),
						$this->escapeColumn('text'), $sSearchEscaped).
				')'
			;
		}

		return $aWhere;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iFilter Default value is **0** EHelpdeskThreadFilterType::All.
	 * @param string $sSearch Default value is empty string.
	 * @param int $iSearchOwner Default value is **0**.
	 *
	 * @return string
	 */
	public function getThreadsCount(CHelpdeskUser $oHelpdeskUser, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '', $iSearchOwner = 0)
	{
		$sSql = 'SELECT COUNT(id_helpdesk_thread) as item_count FROM %sahd_threads';
		$sSql = sprintf($sSql, $this->prefix());

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
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return string
	 */
	public function getThreadsPendingCount($iTenantId)
	{
		$sSql = 'SELECT COUNT(*) as item_pending_count FROM %sahd_threads WHERE type = 1 AND id_tenant = %s AND archived = 0';

		return sprintf($sSql, $this->prefix(), $iTenantId);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iOffset Default value is **0**.
	 * @param int $iLimit Default value is **20**.
	 * @param int $iFilter Default value is **0** EHelpdeskThreadFilterType::All.
	 * @param string $sSearch Default value is empty string.
	 * @param int $iSearchOwner Default value is **0**.
	 *
	 * @return string
	 */
	public function getThreads(CHelpdeskUser $oHelpdeskUser, $iOffset = 0, $iLimit = 20, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '', $iSearchOwner = 0)
	{
		$sSearch = trim($sSearch);

		$aWhere = array();
		$aMap = api_AContainer::DbReadKeys(CHelpdeskThread::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_threads';
		$sSql = sprintf($sSql, implode(', ', $aMap), $this->prefix());

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
	public function getThreadsLastPostIds(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$sSql = 'SELECT DISTINCT id_helpdesk_thread, last_post_id FROM %sahd_reads WHERE %s = %d AND %s = %d AND %s IN (%s)';
		return sprintf($sSql, $this->prefix(),
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
	public function verifyThreadIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$sSql = 'SELECT id_owner, id_helpdesk_thread FROM %sahd_threads WHERE %s IN (%s)';
		return sprintf($sSql, $this->prefix(), $this->escapeColumn('id_helpdesk_thread'), implode(',', $aThreadIds));
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aPostIds
	 *
	 * @return string
	 */
	public function verifyPostIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aPostIds)
	{
		$sSql = 'SELECT id_owner FROM %sahd_posts WHERE %s IN (%s)';
		return sprintf($sSql, $this->prefix(), $this->escapeColumn('id_helpdesk_post'), implode(',', $aPostIds));
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
	public function getPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$sSql = 'SELECT COUNT(id_helpdesk_post) as item_count FROM %sahd_posts WHERE deleted = 0 AND %s = %d AND %s = %d%s';
		
		$aWhere = $this->buildPostsWhere($oHelpdeskUser, $oThread);
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $oThread->IdHelpdeskThread,
			0 < count($aWhere) ? ' AND '.implode(' AND ', $aWhere) : ''
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 *
	 * @return string
	 */
	public function getExtPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$sSql = 'SELECT COUNT(id_helpdesk_post) as item_count FROM %sahd_posts WHERE deleted = 0 AND type = 0 AND %s = %d AND %s = %d%s';

		$aWhere = $this->buildPostsWhere($oHelpdeskUser, $oThread);
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_helpdesk_thread'), $oThread->IdHelpdeskThread,
			0 < count($aWhere) ? ' AND '.implode(' AND ', $aWhere) : ''
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param int $iStartFromId Default value is **0**.
	 * @param int $iLimit Default value is **20**.
	 *
	 * @return string
	 */
	public function getPosts(CHelpdeskUser $oHelpdeskUser, $oThread, $iStartFromId = 0, $iLimit = 20)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskPost::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_posts WHERE deleted = 0';
		$sSql = sprintf($sSql, implode(', ', $aMap), $this->prefix());

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
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return string
	 */
	public function getAttachments(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$aMap = api_AContainer::DbReadKeys(CHelpdeskAttachment::getStaticMap());
		$aMap = array_map(array($this, 'escapeColumn'), $aMap);

		$sSql = 'SELECT %s FROM %sahd_attachments';
		$sSql = sprintf($sSql, implode(', ', $aMap), $this->prefix());

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
	public function addAttachments($aAttachments)
	{
		$sSql = '';
		foreach ($aAttachments as $oItem)
		{
			$aResults = api_AContainer::DbInsertArrays($oItem, $this->oHelper);
			
			if (empty($sSql))
			{
				$sSql = sprintf('INSERT INTO %sahd_attachments ( %s ) VALUES', $this->prefix(), implode(', ', $aResults[0]));
			}

			$sSql .= sprintf('( %s ),', implode(', ', $aResults[1]));
		}

		return trim($sSql, ',');
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskPost $oHelpdeskPost
	 *
	 * @return string
	 */
	public function createPost(CHelpdeskUser $oHelpdeskUser, CHelpdeskPost $oHelpdeskPost)
	{
		$aResults = api_AContainer::DbInsertArrays($oHelpdeskPost, $this->oHelper);

		if (!empty($aResults[0]) && !empty($aResults[1]))
		{
			$sSql = 'INSERT INTO %sahd_posts ( %s ) VALUES ( %s )';
			return sprintf($sSql, $this->prefix(), implode(', ', $aResults[0]), implode(', ', $aResults[1]));
		}

		return '';
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return string
	 */
	public function clearThreadSeen(CHelpdeskUser $oHelpdeskUser, $oHelpdeskThread)
	{
		$sSql = 'DELETE FROM %sahd_reads WHERE %s = %d AND %s = %d AND %s = %d';
		return sprintf($sSql, $this->prefix(),
			$this->escapeColumn('id_tenant'), $oHelpdeskUser->IdTenant,
			$this->escapeColumn('id_owner'), $oHelpdeskUser->IdHelpdeskUser,
			$this->escapeColumn('id_helpdesk_thread'), $oHelpdeskThread->IdHelpdeskThread
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return string
	 */
	public function setThreadSeen(CHelpdeskUser $oHelpdeskUser, $oHelpdeskThread)
	{
		$sSql = 'INSERT INTO %sahd_reads ( id_tenant, id_owner, id_helpdesk_thread, last_post_id ) VALUES ( %d, %d, %d, %d )';
		return sprintf($sSql, $this->prefix(),
			$oHelpdeskUser->IdTenant, $oHelpdeskUser->IdHelpdeskUser,
			$oHelpdeskThread->IdHelpdeskThread, $oHelpdeskThread->LastPostId
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 * @param int $iTimeoutInMin Default value is **5**.
	 *
	 * @return string
	 */
	public function getOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID, $iTimeoutInMin = 5)
	{
		$sSql = 'SELECT * FROM %sahd_online WHERE id_helpdesk_thread = %d AND id_tenant = %d AND ping_time > %d';
		return sprintf($sSql, $this->prefix(), $iThreadID, $oHelpdeskUser->IdTenant,
			time() - $iTimeoutInMin * 60
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 *
	 * @return string
	 */
	public function clearOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$sSql = 'DELETE FROM %sahd_online  WHERE id_helpdesk_user = %d AND id_tenant = %d AND id_helpdesk_thread = %d';
		return sprintf($sSql, $this->prefix(),
			$oHelpdeskUser->IdHelpdeskUser, $oHelpdeskUser->IdTenant, $iThreadID
		);
	}

	/**
	 * @param int $iTimeoutInMin Default value is **15**.
	 *
	 * @return string
	 */
	public function clearAllOnline($iTimeoutInMin = 15)
	{
		$sSql = 'DELETE FROM %sahd_online WHERE ping_time < %d';
		return sprintf($sSql, $this->prefix(),
			time() - $iTimeoutInMin * 60
		);
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 * 
	 * @return string
	 */
	public function setOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$sSql = 'INSERT INTO %sahd_online (id_helpdesk_thread, id_helpdesk_user, id_tenant, name, email, ping_time) VALUES ( %d, %d, %d, %s, %s, %d )';
		return sprintf($sSql, $this->prefix(),
			$iThreadID, $oHelpdeskUser->IdHelpdeskUser, $oHelpdeskUser->IdTenant,
			$this->escapeString($oHelpdeskUser->Name), $this->escapeString($oHelpdeskUser->Email),
			time()
		);
	}
}

/**
 * @package Helpdesk
 * @subpackage Storages
 */
class CApiHelpdeskCommandCreatorMySQL extends CApiHelpdeskCommandCreator
{
}

/**
 * @package Helpdesk
 * @subpackage Storages
 */
class CApiHelpdeskCommandCreatorPostgreSQL extends CApiHelpdeskCommandCreator
{
}
