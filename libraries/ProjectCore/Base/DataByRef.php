<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectCore\Base;

/**
 * @category ProjectCore
 * @package Base
 */
class DataByRef
{
	protected $aData;
	
	public static function createInstance($mData = null)
	{
		$oResult = new DataByRef();
		$oResult->aData = $mData;
		
		return $oResult;
	}
	
	public function getData()
	{
		return $this->aData;
	}

	public function setData($mData)
	{
		$this->aData = $mData;
	}
}
