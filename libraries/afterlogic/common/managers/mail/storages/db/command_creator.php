<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * CApiMailCommandCreator class for generating query strings.
 * 
 * @internal
 * 
 * @package Mail
 * @subpackage Storages
 */
class CApiMailCommandCreator extends api_CommandCreator
{
	/**
	 * Returns query for obtaining folders order from wm_folders_order_names table.
	 * 
	 * @ignore
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return string
	 */
	public function getSelectFoldersOrderNamesQuery($oAccount)
	{
		$sSql = 'SELECT real_name, order_name FROM %sawm_folders_order_names WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount);
	}

	/**
	 * Returns query for clearing folders order in wm_folders_order_names table.
	 * 
	 * @ignore
	 * 
	 * @param CAccount $oAccount Account object.
	 * @param string|null $sRealName Real name of folder.
	 *
	 * @return string
	 */
	public function getClearFoldersOrderNamesQuery($oAccount, $sRealName = null)
	{
		$sSql = 'DELETE FROM %sawm_folders_order_names WHERE id_acct = %d';
		if (null !== $sRealName)
		{
			$sSql = 'DELETE FROM %sawm_folders_order_names WHERE id_acct = %d AND real_name = %s';
			return sprintf($sSql, $this->prefix(), $oAccount->IdAccount, $this->oHelper->EscapeString($sRealName));
		}
		
		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount);
	}

	/**
	 * Returns query for updating folders order in wm_folders_order_names table.
	 * 
	 * @ignore
	 * 
	 * @param CAccount $oAccount Account object.
	 * @param string $sRealName Real name of folder.
	 * @param string $sOrderName Order name of folder.
	 *
	 * @return string
	 */
	public function getUpdateFoldersOrderNamesQuery($oAccount, $sRealName, $sOrderName)
	{
		$sSql = 'INSERT INTO %sawm_folders_order_names (id_acct, real_name, order_name) VALUES (%d, %s, %s)';
		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount,
			$this->oHelper->EscapeString($sRealName), $this->oHelper->EscapeString($sOrderName));
	}

	/**
	 * Returns query for selection folders order.
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return string
	 */
	public function getSelectFoldersOrderQuery($oAccount)
	{
		$sSql = 'SELECT folders_order FROM %sawm_folders_order WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount);
	}

	/**
	 * Returns query for clearing folders order.
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return string
	 */
	public function getClearFoldersOrderQuery($oAccount)
	{
		$sSql = 'DELETE FROM %sawm_folders_order WHERE id_acct = %d';
		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount);
	}

	/**
	 * Returns query for updating folders order.
	 * 
	 * @param CAccount $oAccount Account object.
	 * @param string $sOrder New folders order.
	 *
	 * @return string
	 */
	public function getUpdateFoldersOrderQuery($oAccount, $sOrder)
	{
		$sSql = 'INSERT INTO %sawm_folders_order (id_acct, folders_order) VALUES (%d, %s)';
		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount, $this->oHelper->EscapeString($sOrder));
	}
	
	/**
	 * Returns query for selection information about system folders.
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return string
	 */
	public function getSelectSystemFoldersQuery($oAccount)
	{
		$sSql = 'SELECT folder_full_name, system_type FROM %sawm_system_folders WHERE id_acct = %d';

		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount);
	}

	/**
	 * Returns query for clearing system folders information in database.
	 * 
	 * @param CAccount $oAccount Account object.
	 *
	 * @return string
	 */
	public function getClearSystemFoldersQuery($oAccount)
	{
		$sSql = 'DELETE FROM %sawm_system_folders WHERE id_acct = %d';

		return sprintf($sSql, $this->prefix(), $oAccount->IdAccount);
	}

	/**
	 * Returns query for updating information about system folder in database.
	 * 
	 * @param CAccount $oAccount Account object.
	 * @param array $aSystemNames List of information about system folders: [FolderFullName => FolderType, ...].
	 *
	 * @return string
	 */
	public function getUpdateSystemFoldersQuery($oAccount, $aSystemNames)
	{
		$sSql = 'INSERT INTO %sawm_system_folders
(id_acct, id_user, folder_full_name, system_type)
VALUES';

		$aValues = array();
		$sSql = sprintf($sSql, $this->prefix());

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
 * CApiMailCommandCreatorMySQL class for generating query strings for MySQL database.
 * 
 * @package Mail
 * @subpackage Storages
 */
class CApiMailCommandCreatorMySQL extends CApiMailCommandCreator
{
}

/**
 * CApiMailCommandCreatorPostgreSQL class for generating query strings for PostgreSQL database.
 * 
 * @package Mail
 * @subpackage Storages
 */
class CApiMailCommandCreatorPostgreSQL extends CApiMailCommandCreator
{
}
