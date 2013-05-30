<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @package Mail
 */
class CApiMailCommandCreator extends api_CommandCreator
{
	/**
	 * @param CAccount $oAccount
	 *
	 * @return string
	 */
	public function FoldersOrder($oAccount)
	{
		$sSql = 'SELECT folders_order FROM %sawm_folders_order WHERE id_acct = %d';

		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount);
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return string
	 */
	public function FoldersOrderClear($oAccount)
	{
		$sSql = 'DELETE FROM %sawm_folders_order WHERE id_acct = %d';

		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @param string $sOrder
	 *
	 * @return string
	 */
	public function FoldersOrderUpdate($oAccount, $sOrder)
	{
		$sSql = 'INSERT INTO %sawm_folders_order (id_acct, folders_order) VALUES (%d, %s)';

		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount, $this->oHelper->EscapeString($sOrder));
	}
	
	/**
	 * @param CAccount $oAccount
	 *
	 * @return string
	 */
	public function GetSystemFolderNames($oAccount)
	{
		$sSql = 'SELECT folder_full_name, system_type FROM %sawm_system_folders WHERE id_acct = %d';

		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount);
	}

	/**
	 * @param CAccount $oAccount
	 *
	 * @return string
	 */
	public function ClearSystemFolderNames($oAccount)
	{
		$sSql = 'DELETE FROM %sawm_system_folders WHERE id_acct = %d';

		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount);
	}

	/**
	 * @param CAccount $oAccount
	 * @param array $aSystemNames [FolderFullName => FolderType, ...]
	 *
	 * @return string
	 */
	public function SetSystemFolderNames($oAccount, $aSystemNames)
	{
		$sSql = 'INSERT INTO %sawm_system_folders
(id_acct, id_user, folder_full_name, system_type)
VALUES';

		$aValues = array();
		$sSql = sprintf($sSql, $this->Prefix());

		foreach ($aSystemNames as $sFolderFullName => $iFolderType)
		{
			$aValues[] = '('.((int) $oAccount->IdAccount).', '.((int) $oAccount->IdUser).', '.
				($this->oHelper->EscapeString($sFolderFullName)).', '.((int) $iFolderType).')';
		}

		if (0 < count($aValues))
		{
			$sSql .= ' '.implode(', ', $aValues);
		}

		return $sSql;
	}
}

/**
 * @package Mail
 */
class CApiMailCommandCreatorMySQL extends CApiMailCommandCreator
{

}
