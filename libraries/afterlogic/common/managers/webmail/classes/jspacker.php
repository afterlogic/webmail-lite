<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package WebMail
 * @subpackage Classes
 */
class CJsPacker
{
	/**
	 * @param array $aFiles
	 * @return string
	 */
	public function JsFilesCompress($aFiles)
	{
		$aResult = array();
		foreach ($aFiles as $sFile)
		{
			$aResult[] = $this->jsCompress($sFile);
		}
		return implode('', $aResult);
	}

	/**
	 * @param string $sFileName
	 * @return string
	 */
	protected function jsCompress($sFileName)
	{
		$sReturn = '';
		if (@file_exists($sFileName))
		{
			$sReturn = $this->jsClear(@file_get_contents($sFileName))."\r\n";
		}
		return $sReturn;
	}

	/**
	 * @param string $sText
	 * @return string
	 */
	protected function jsClear($sText)
	{
		return $sText;
	}
}