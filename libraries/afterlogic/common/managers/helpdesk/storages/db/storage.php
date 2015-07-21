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
class CApiHelpdeskDbStorage extends CApiHelpdeskStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * @var CApiHelpdeskCommandCreatorMySQL
	 */
	protected $oCommandCreator;

	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(CApiGlobalManager &$oManager)
	{
		parent::__construct('db', $oManager);

		$this->oConnection =& $oManager->GetConnection();
		$this->oCommandCreator =& $oManager->GetCommandCreator(
			$this, array(
				EDbType::MySQL => 'CApiHelpdeskCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiHelpdeskCommandCreatorPostgreSQL'
			)
		);
	}

	/**
	 * @param string $sSql
	 *
	 * @return CHelpdeskUser|false
	 */
	protected function _getUserBySql($sSql)
	{
		$oUser = false;
		if ($this->oConnection->Execute($sSql))
		{
			$oUser = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oUser = new CHelpdeskUser();
				$oUser->InitByDbRow($oRow);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oUser;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 *
	 * @return bool
	 */
	public function createUser(CHelpdeskUser &$oHelpdeskUser)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createUser($oHelpdeskUser)))
		{
			$oHelpdeskUser->IdHelpdeskUser = $this->oConnection->GetLastInsertId('ahd_users', 'id_helpdesk_user');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iHelpdeskUserId
	 *
	 * @return CHelpdeskUser|false
	 */
	public function getUserById($iIdTenant, $iHelpdeskUserId)
	{
		return $this->_getUserBySql($this->oCommandCreator->getUserById($iIdTenant, $iHelpdeskUserId));
	}

	/**
	 * @param int $iHelpdeskUserId
	 *
	 * @return CHelpdeskUser|false
	 */
	public function getUserByIdWithoutTenantID($iHelpdeskUserId)
	{
		return $this->_getUserBySql($this->oCommandCreator->getUserByIdWithoutTenantID($iHelpdeskUserId));
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 *
	 * @return CHelpdeskUser|null|false
	 */
	public function getUserByEmail($iIdTenant, $sEmail)
	{
		return $this->_getUserBySql($this->oCommandCreator->getUserByEmail($iIdTenant, $sEmail));
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 *
	 * @return CHelpdeskUser|null|false
	 */
	public function getUserByNotificationEmail($iIdTenant, $sEmail)
	{
		return $this->_getUserBySql($this->oCommandCreator->getUserByNotificationEmail($iIdTenant, $sEmail));
	}

	public function getUserBySocialId($iIdTenant, $sSocialId)
	{
		return $this->_getUserBySql($this->oCommandCreator->getUserBySocialId($iIdTenant, $sSocialId));
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sActivateHash
	 *
	 * @return CHelpdeskUser|false
	 */
	public function getUserByActivateHash($iIdTenant, $sActivateHash)
	{
		return $this->_getUserBySql($this->oCommandCreator->getUserByActivateHash($iIdTenant, $sActivateHash));
	}

	/**
	 * @param int $iIdTenant
	 * @param array $aExcludeEmails Default value is empty array.
	 *
	 * @return array
	 */
	public function getAgentsEmailsForNotification($iIdTenant, $aExcludeEmails = array())
	{
		$aResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getAgentsEmailsForNotification($iIdTenant)))
		{
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				if ($oRow && !in_array(strtolower($oRow->email), $aExcludeEmails))
				{
					$aResult[] = $oRow->email;
				}
			}
		}
		$this->throwDbExceptionIfExist();
		return $aResult;
	}

	/**
	 * @param int $iLimitAddInMin Default value is **5**.
	 *
	 * @return int|bool
	 */
	public function getNextHelpdeskIdForMonitoring($iLimitAddInMin = 5)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getNextHelpdeskIdForMonitoring($iLimitAddInMin)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$mResult = (int) $oRow->id_tenant;
			}

			$this->oConnection->FreeResult();
		}
		
		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 *
	 * @return int
	 */
	public function getHelpdeskMailboxLastUid($iIdTenant, $sEmail)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getHelpdeskMailboxLastUid($iIdTenant, $sEmail)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->last_uid;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param string $sEmail
	 * @param int $iLastUid
	 *
	 * @return bool
	 */
	public function setHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid)
	{
		$this->oConnection->Execute($this->oCommandCreator->clearHelpdeskMailboxLastUid($iIdTenant, $sEmail));
		$bResult = $this->oConnection->Execute($this->oCommandCreator->addHelpdeskMailboxLastUid($iIdTenant, $sEmail, $iLastUid));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}


	/**
	 * @param int $iIdTenant
	 *
	 * @return bool
	 */
	public function updateHelpdeskFetcherTimer($iIdTenant)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateHelpdeskFetcherTimer($iIdTenant));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 *
	 * @return bool
	 */
	public function isUserExists(CHelpdeskUser $oHelpdeskUser)
	{
		$bResult = false;

		if ($this->oConnection->Execute($this->oCommandCreator->isUserExists($oHelpdeskUser)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow && 0 < (int) $oRow->item_count)
			{
				$bResult = true;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aIdList
	 *
	 * @return array|bool
	 */
	public function userInformation(CHelpdeskUser $oHelpdeskUser, $aIdList)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->userInformation($oHelpdeskUser, $aIdList)))
		{
			$mResult = array();
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				if ($oRow && 0 < (int) $oRow->id_helpdesk_user)
				{
					$mResult[(int) $oRow->id_helpdesk_user] = array(
						$oRow->email, $oRow->name, '1' === (string) $oRow->is_agent, $oRow->notification_email);
				}
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 *
	 * @return bool
	 */
	public function updateUser(CHelpdeskUser $oHelpdeskUser)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateUser($oHelpdeskUser));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iIdHelpdeskUser
	 *
	 * @return bool
	 */
	public function setUserAsBlocked($iIdTenant, $iIdHelpdeskUser)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->setUserAsBlocked($iIdTenant, $iIdHelpdeskUser));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iIdTenant
	 * @param int $iIdHelpdeskUser
	 *
	 * @return bool
	 */
	public function deleteUser($iIdTenant, $iIdHelpdeskUser)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deleteUser($iIdTenant, $iIdHelpdeskUser));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param array $aPostIds
	 *
	 * @return bool
	 */
	public function deletePosts(CHelpdeskUser $oHelpdeskUser, $oThread, $aPostIds)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->deletePosts($oHelpdeskUser, $oThread, $aPostIds));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function clearUnregistredUsers()
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->clearUnregistredUsers());
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 * @param CHelpdeskPost $oHelpdeskPost
	 * @param array $aAttachments
	 *
	 * @return bool
	 */
	public function addAttachments(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread, CHelpdeskPost $oHelpdeskPost, $aAttachments)
	{
		foreach ($aAttachments as &$oItem)
		{
			$oItem->IdHelpdeskThread = $oHelpdeskThread->IdHelpdeskThread;
			$oItem->IdHelpdeskPost = $oHelpdeskPost->IdHelpdeskPost;
			$oItem->IdOwner = $oHelpdeskUser->IdHelpdeskUser;
		}

		$bResult = $this->oConnection->Execute($this->oCommandCreator->addAttachments($aAttachments));
		
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 *
	 * @return bool
	 */
	public function verifyThreadIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->verifyThreadIdsBelongToUser($oHelpdeskUser, $aThreadIds)))
		{
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				if ((int) $oHelpdeskUser->IdHelpdeskUser !== (int) $oRow->id_owner)
				{
					$mResult = false;
					break;
				}
				else
				{
					$mResult = true;
				}
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aPostIds
	 *
	 * @return bool
	 */
	public function verifyPostIdsBelongToUser(CHelpdeskUser $oHelpdeskUser, $aPostIds)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->verifyPostIdsBelongToUser($oHelpdeskUser, $aPostIds)))
		{
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				if ((int) $oHelpdeskUser->IdHelpdeskUser !== (int) $oRow->id_owner)
				{
					$mResult = false;
					break;
				}
				else
				{
					$mResult = true;
				}
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 * @param bool $bSetArchive Default value is **true**.
	 *
	 * @return bool
	 */
	public function archiveThreads(CHelpdeskUser $oHelpdeskUser, $aThreadIds, $bSetArchive = true)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->archiveThreads($oHelpdeskUser, $aThreadIds, $bSetArchive));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @return bool
	 */
	public function archiveOutdatedThreads()
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->archiveOutdatedThreads());
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iIdOwner
	 *
	 * @return bool
	 */
	public function notificateOutdatedThreadID(&$iIdOwner)
	{
		$mResult = false;
		$iIdOwner = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->nextOutdatedThreadForNotificate()))
		{
			$iIdHelpdeskThread = 0;
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow && isset($oRow->id_helpdesk_thread))
			{
				$iIdHelpdeskThread = (int) $oRow->id_helpdesk_thread;
				$iIdTenant = (int) $oRow->id_tenant;
				$iIdOwner = (int) $oRow->id_owner;
			}

			$this->oConnection->FreeResult();

			if (0 < $iIdHelpdeskThread)
			{
				$this->oConnection->Execute($this->oCommandCreator->setOutdatedThreadNotificated($iIdTenant, $iIdHelpdeskThread));
				$mResult = $iIdHelpdeskThread;
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iIdThread
	 *
	 * @return CHelpdeskThread|false
	 */
	public function getThreadById($oHelpdeskUser, $iIdThread)
	{
		$oThread = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getThreadById($oHelpdeskUser, $iIdThread)))
		{
			$oThread = null;

			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$oThread = new CHelpdeskThread();
				$oThread->InitByDbRow($oRow);
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $oThread;
	}
	
	/**
	 * @param int $iTenantID
	 * @param string $sHash
	 *
	 * @return int
	 */
	public function getThreadIdByHash($iTenantID, $sHash)
	{
		$iThreadID = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getThreadIdByHash($iTenantID, $sHash)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iThreadID = (int) $oRow->id_helpdesk_thread;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iThreadID;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return bool
	 */
	public function createThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread &$oHelpdeskThread)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createThread($oHelpdeskUser, $oHelpdeskThread)))
		{
			$oHelpdeskThread->IdHelpdeskThread = $this->oConnection->GetLastInsertId('ahd_threads', 'id_helpdesk_thread');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return bool
	 */
	public function updateThread(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->updateThread($oHelpdeskUser, $oHelpdeskThread));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iFilter Default value is **0** EHelpdeskThreadFilterType::All.
	 * @param string $sSearch Default value is empty string.
	 * @param int $iSearchOwner Default value is **0**.
	 *
	 * @return int
	 */
	public function getThreadsCount(CHelpdeskUser $oHelpdeskUser, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '', $iSearchOwner = 0)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getThreadsCount($oHelpdeskUser, $iFilter, $sSearch, $iSearchOwner)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->item_count;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResult;
	}

	/**
	 * @param int $iTenantId Default value is **0**.
	 *
	 * @return int
	 */
	public function getThreadsPendingCount($iTenantId)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getThreadsPendingCount($iTenantId)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->item_pending_count;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iOffset Default value is **0**.
	 * @param int $iLimit Default value is **20**.
	 * @param int $iFilter Default value is **0** EHelpdeskThreadFilterType::All.
	 * @param string $sSearch Default value is empty string.
	 * @param int $iSearchOwner Default value is **0**.
	 *
	 * @return array|bool
	 */
	public function getThreads(CHelpdeskUser $oHelpdeskUser, $iOffset = 0, $iLimit = 20, $iFilter = EHelpdeskThreadFilterType::All, $sSearch = '', $iSearchOwner = 0)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getThreads($oHelpdeskUser, $iOffset, $iLimit, $iFilter, $sSearch, $iSearchOwner)))
		{
			$oRow = null;
			$mResult = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oHelpdeskThread = new CHelpdeskThread();
				$oHelpdeskThread->InitByDbRow($oRow);
				$oHelpdeskThread->ItsMe = $oHelpdeskThread->IdOwner === $oHelpdeskUser->IdHelpdeskUser;

				$mResult[] = $oHelpdeskThread;
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskPost $oThread
	 *
	 * @return int
	 */
	public function getPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getPostsCount($oHelpdeskUser, $oThread)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->item_count;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskPost $oThread
	 *
	 * @return int
	 */
	public function getExtPostsCount(CHelpdeskUser $oHelpdeskUser, $oThread)
	{
		$iResult = 0;
		if ($this->oConnection->Execute($this->oCommandCreator->getExtPostsCount($oHelpdeskUser, $oThread)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$iResult = (int) $oRow->item_count;
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $iResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param array $aThreadIds
	 *
	 * @return array|bool
	 */
	public function getThreadsLastPostIds(CHelpdeskUser $oHelpdeskUser, $aThreadIds)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getThreadsLastPostIds($oHelpdeskUser, $aThreadIds)))
		{
			$oRow = null;
			$mResult = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$mResult[(int) $oRow->id_helpdesk_thread] = (int) $oRow->last_post_id;
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return array|bool
	 */
	public function getAttachments(CHelpdeskUser $oHelpdeskUser, CHelpdeskThread $oHelpdeskThread)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getAttachments($oHelpdeskUser, $oHelpdeskThread)))
		{
			$oRow = null;
			$mResult = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oHelpdeskPost = new CHelpdeskAttachment();
				$oHelpdeskPost->InitByDbRow($oRow);

				if (!isset($mResult[$oHelpdeskPost->IdHelpdeskPost]))
				{
					$mResult[$oHelpdeskPost->IdHelpdeskPost] = array();
				}

				$mResult[$oHelpdeskPost->IdHelpdeskPost][] = $oHelpdeskPost;
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}
	
	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oThread
	 * @param int $iStartFromId Default value is **0**.
	 * @param int $iLimit Default value is **20**.
	 *
	 * @return array|bool
	 */
	public function getPosts(CHelpdeskUser $oHelpdeskUser, $oThread, $iStartFromId = 0, $iLimit = 20)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getPosts($oHelpdeskUser, $oThread, $iStartFromId, $iLimit)))
		{
			$oRow = null;
			$mResult = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$oHelpdeskPost = new CHelpdeskPost();
				$oHelpdeskPost->InitByDbRow($oRow);

				$mResult[] = $oHelpdeskPost;
			}
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskPost $oPost
	 *
	 * @return bool
	 */
	public function createPost(CHelpdeskUser $oHelpdeskUser, CHelpdeskPost &$oPost)
	{
		$bResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->createPost($oHelpdeskUser, $oPost)))
		{
			$oPost->IdHelpdeskPost = $this->oConnection->GetLastInsertId('ahd_posts', 'id_helpdesk_post');
			$bResult = true;
		}

		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param int $iThreadID
	 *
	 * @return array|bool
	 */
	public function getOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$mResult = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getOnline($oHelpdeskUser, $iThreadID, 5)))
		{
			$oRow = null;
			$mResult = array();

			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				if ($oRow && isset($oRow->id_helpdesk_user) && isset($oRow->name) &&
					isset($oRow->email))
				{
					if ((string) $oRow->id_helpdesk_user !== (string) $oHelpdeskUser->IdHelpdeskUser)
					{
						$mResult[$oRow->id_helpdesk_user] = array((string) $oRow->name, (string) $oRow->email);
					}
				}
			}

			$mResult = array_values($mResult);
		}

		$this->throwDbExceptionIfExist();
		return $mResult;
	}

	/**
	 * @param int $iTimeoutInMin Default value is **15**.
	 *
	 * @return bool
	 */
	public function clearAllOnline($iTimeoutInMin = 15)
	{
		$bResult = $this->oConnection->Execute($this->oCommandCreator->clearAllOnline($iTimeoutInMin));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param int $iThreadID
	 *
	 * @return bool
	 */
	public function setOnline(CHelpdeskUser $oHelpdeskUser, $iThreadID)
	{
		$this->oConnection->Execute($this->oCommandCreator->clearOnline($oHelpdeskUser, $iThreadID));

		$bResult = $this->oConnection->Execute($this->oCommandCreator->setOnline($oHelpdeskUser, $iThreadID));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}

	/**
	 * @param CHelpdeskUser $oHelpdeskUser
	 * @param CHelpdeskThread $oHelpdeskThread
	 *
	 * @return bool
	 */
	public function setThreadSeen(CHelpdeskUser $oHelpdeskUser, $oHelpdeskThread)
	{
		$this->oConnection->Execute($this->oCommandCreator->clearThreadSeen($oHelpdeskUser, $oHelpdeskThread));
		$bResult = $this->oConnection->Execute($this->oCommandCreator->setThreadSeen($oHelpdeskUser, $oHelpdeskThread));
		$this->throwDbExceptionIfExist();
		return $bResult;
	}
}
