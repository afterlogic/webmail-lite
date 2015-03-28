<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Mail
 */
class CApiMailDbStorage extends CApiMailStorage
{
	/**
	 * @var CDbStorage $oConnection
	 */
	protected $oConnection;

	/**
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
	 * @param CAccount $oAccount
	 *
	 * @return array | bool
	 */
	public function GetSystemFolderNames($oAccount)
	{
		$aSystemNames = false;
		if ($this->oConnection->Execute($this->oCommandCreator->GetSystemFolderNames($oAccount)))
		{
			$aSystemNames = array();

			$oRow = null;
			while (false !== ($oRow = $this->oConnection->GetNextRecord()))
			{
				$aSystemNames[$oRow->folder_full_name] = (int) $oRow->system_type;
			}
		}

		$this->throwDbExceptionIfExist();
		return $aSystemNames;
	}

	/**
	 * @param CAccount $oAccount
	 * @param array $aSystemNames [FolderFullName => FolderType, ...]
	 *
	 * @return array | bool
	 */
	public function SetSystemFolderNames($oAccount, $aSystemNames)
	{
		$this->oConnection->Execute($this->oCommandCreator->ClearSystemFolderNames($oAccount));
		$aSystemNames = is_array($aSystemNames) && 0 < count($aSystemNames) ? $aSystemNames : array();
		$aSystemNames['INBOX'] = 'INBOX';

		$this->oConnection->Execute($this->oCommandCreator->SetSystemFolderNames($oAccount, $aSystemNames));
		$this->throwDbExceptionIfExist();
		return true;
	}
	
	/**
	 * @param CAccount $oAccount
	 *
	 * @return array
	 */
	public function FoldersOrder($oAccount)
	{
		$aList = array();
		if ($this->oConnection->Execute($this->oCommandCreator->FoldersOrder($oAccount)))
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
	 * @param CAccount $oAccount
	 *
	 * @return bool
	 */
	public function FoldersOrderClear($oAccount)
	{
		$this->oConnection->Execute($this->oCommandCreator->FoldersOrderClear($oAccount));
		$this->throwDbExceptionIfExist();
		return true;
	}
	
	/**
	 * @param CAccount $oAccount
	 * @param array $aOrder
	 *
	 * @return bool
	 */
	public function FoldersOrderUpdate($oAccount, $aOrder)
	{
		if (!is_array($aOrder))
		{
			return false;
		}
		
		$this->FoldersOrderClear($oAccount);
		
		$this->oConnection->Execute($this->oCommandCreator->FoldersOrderUpdate($oAccount, @json_encode($aOrder)));
		$this->throwDbExceptionIfExist();
		return true;
	}
}