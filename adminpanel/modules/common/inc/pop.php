<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class CCommonPopAction extends ap_CoreModuleHelper
{
	const COLOR_RED = 'red';
	const COLOR_GREEN = 'green';

	public function System()
	{
		$sType = isset($_GET['type']) ? $_GET['type'] : '';
		$sAction = isset($_GET['action']) ? $_GET['action'] : '';

		if ('db' === $sType && ('create' === $sAction || 'update' === $sAction))
		{
			echo '<html><head><title>Update</title></head><body><font color="black" size="3" style="font-family: Tahoma, Verdana;"><h3>AfterLogic Db Script:</h3>';

			/* @var $oApiDbManager CApiDbManager */
			$oApiDbManager = CApi::Manager('db');

			echo '<hr style="border: 1px solid grey" />';
			if ('create' === $sAction && $oApiDbManager->AUsersTableExists())
			{
				echo 'The data tables already exist. To proceed, specify another prefix or delete the existing tables.<br /><br />';
				echo '<font color="'.CCommonPopAction::COLOR_RED.'"><b>Failed!</b></font></font>';
			}
			else if ($oApiDbManager->SyncTables(array(&$this, 'fSystemTablesSync')))
			{
				echo '<br /><br /><font color="'.CCommonPopAction::COLOR_GREEN.'"><b>Done!</b></font>';
			}
			else
			{
				echo '<br /><br /><font color="'.CCommonPopAction::COLOR_RED.'"><b>Failed!</b></font></font>';
			}

			echo '</font></body></html>';
		}
		
	}

	/**
	 * @param int $iType
	 * @param bool $bResult
	 * @param string $sTable
	 * @param array $aFields = array()
	 * @param string $sError = ''
	 * @return void
	 */
	public function fSystemTablesSync($iType, $bResult, $sTable, $aFields = array(), $sError = '')
	{
		echo '<br />';

		switch ($iType)
		{
			case ESyncVerboseType::CreateTable:
				echo 'Create <b>'.$sTable.'</b> table:';
				break;
			case ESyncVerboseType::CreateField:
				echo 'Add <b>'.implode(', ', $aFields).'</b> column(s) in '.$sTable.' table:';
				break;
			case ESyncVerboseType::CreateIndex:
				echo 'Add index(s) on '.implode(', ', $aFields).' column(s) in '.$sTable.' table:';
				break;
			case ESyncVerboseType::DeleteField:
				echo 'Delete <b>'.implode(', ', $aFields).'</b> column(s) in '.$sTable.' table:';
				break;
			case ESyncVerboseType::DeleteIndex:
				echo 'Delete index(s) on '.implode(', ', $aFields).' in '.$sTable.' table:';
				break;
		}

		if ($bResult)
		{
			echo ' <font color="'.CCommonPopAction::COLOR_GREEN.'"><b>done!</b></font>';
		}
		else
		{
			$sError = empty($sError) ? 'unknown error' : $sError;
			echo '<font color="'.CCommonPopAction::COLOR_RED.'"><b>error!</b><br /><br />'.$sError.'</font><br /><br />';
		}
	}
}

