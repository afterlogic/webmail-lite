<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiMailDbStorage class for work with database.
 * 
 * @internal
 * 
 * @package Mail
 * @subpackage Storages
 */
class CApiMailDbStorage extends CApiMailStorage
{
	/**
	 * Object for work with database connection.
	 * 
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
	 * Object for generating query strings.
	 * 
	 * @var CApiMailCommandCreator
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
				EDbType::MySQL => 'CApiMailCommandCreatorMySQL',
				EDbType::PostgreSQL => 'CApiMailCommandCreatorPostgreSQL'
			)
		);
	}

	/**
	 * Gets information about system folders of the account.
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return array|bool
	 */
	public function getSystemFolderNames($oAccount)
	{
		$mSystemNames = false;
		if ($this->oConnection->Execute($this->oCommandCreator->getSelectSystemFoldersQuery($oAccount)))
		{
			$mSystemNames = array();

			$oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$mSystemNames[$oRow->folder_full_name] = (int) $oRow->system_type;
			}
		}

		$this->throwDbExceptionIfExist();
		return $mSystemNames;
	}

	/**
	 * Updates information on system folders use.
	 * 
	 * @param CAccount $oAccount Account object.
	 * @param array $aSystemNames Array containing mapping of folder types and their actual IMAP names: [FolderFullName => FolderType, ...].
	 *
	 * @return bool
	 */
	public function setSystemFolderNames($oAccount, $aSystemNames)
	{
		$this->oConnection->Execute($this->oCommandCreator->getClearSystemFoldersQuery($oAccount));
		$aSystemNames = is_array($aSystemNames) && 0 < count($aSystemNames) ? $aSystemNames : array();
		$aSystemNames['INBOX'] = 'INBOX';

		$this->oConnection->Execute($this->oCommandCreator->getUpdateSystemFoldersQuery($oAccount, $aSystemNames));
		$this->throwDbExceptionIfExist();
		
		return true;
	}
	
	/**
	 * Obtains folders order.
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return array
	 */
	public function getFoldersOrder($oAccount)
	{
		$aList = array();
		if ($this->oConnection->Execute($this->oCommandCreator->getSelectFoldersOrderQuery($oAccount)))
		{
			$oRow = $this->oConnection->GetNextRecord();
			if ($oRow)
			{
				$sOrder = $oRow->folders_order;
				if (!empty($sOrder))
				{
					$aOrder = @json_decode($sOrder, 3);
					if (is_array($aOrder) && 0 < count($aOrder))
					{
						$aList = $aOrder;
					}
				}
			}

			$this->oConnection->FreeResult();
		}

		$this->throwDbExceptionIfExist();
		return $aList;
	}

	/**
	 * Clears information about folders order.
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return bool
	 */
	public function clearFoldersOrder($oAccount)
	{
		$this->oConnection->Execute($this->oCommandCreator->getClearFoldersOrderQuery($oAccount));
		$this->throwDbExceptionIfExist();
		return true;
	}
	
	/**
	 * Updates information about folders order.
	 * 
	 * @param CAccount $oAccount Account object.
	 * @param array $aOrder New folders order.
	 *
	 * @return bool
	 */
	public function updateFoldersOrder($oAccount, $aOrder)
	{
		if (!is_array($aOrder))
		{
			return false;
		}
		
		$this->clearFoldersOrder($oAccount);
		
		$this->oConnection->Execute($this->oCommandCreator->getUpdateFoldersOrderQuery($oAccount, @json_encode($aOrder)));
		$this->throwDbExceptionIfExist();
		
		return true;
	}
}