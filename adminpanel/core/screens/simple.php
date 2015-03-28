<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

class ap_Simple_Screen extends ap_Screen
{
	/**
	 * @param CAdminPanel $oAdminPanel
	 * @return ap_Simple_Screen
	 */
	public function __construct(CAdminPanel &$oAdminPanel, $sGlobalTemplateName, $aData = array())
	{
		parent::__construct($oAdminPanel, CAdminPanel::RootPath().'core/templates/'.$sGlobalTemplateName);

		foreach ($aData as $sKey => $sText)
		{
			$this->Data->SetValue($sKey, $sText);
		}
	}
}
