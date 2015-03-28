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
class CApiMailCommandCreator extends api_CommandCreator
{
	/**
	 * @todo
	 * @param CAccount $oAccount
	 *
	 * @return string
	 */
	public function FoldersOrderNames($oAccount)
	{
		$sSql = 'SELECT real_name, order_name FROM %sawm_folders_order_names WHERE id_acct = %d';
		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount);
	}

	/**
	 * @todo
	 * @param CAccount $oAccount
	 *
	 * @return string
	 */
	public function FoldersOrderNamesClear($oAccount, $sRealName = null)
	{
		$sSql = 'DELETE FROM %sawm_folders_order_names WHERE id_acct = %d';
		if (null !== $sRealName)
		{
			$sSql = 'DELETE FROM %sawm_folders_order_names WHERE id_acct = %d AND real_name = %s';
			return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount, $this->oHelper->EscapeString($sRealName));
		}
		
		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount);
	}

	/**
	 * @todo
	 * @param CAccount $oAccount
	 * @param string $sRealName
	 * @param string $sOrderName
	 *
	 * @return string
	 */
	public function FoldersOrderNamesUpdate($oAccount, $sRealName, $sOrderName)
	{
		$sSql = 'INSERT INTO %sawm_folders_order_names (id_acct, real_name, order_name) VALUES (%d, %s, %s)';
		return sprintf($sSql, $this->Prefix(), $oAccount->IdAccount,
			$this->oHelper->EscapeString($sRealName), $this->oHelper->EscapeString($sOrderName));
	}

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

/**
 * @package Mail
 */
class CApiMailCommandCreatorPostgreSQL extends CApiMailCommandCreator
{
}
