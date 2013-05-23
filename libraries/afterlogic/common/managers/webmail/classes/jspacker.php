<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
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