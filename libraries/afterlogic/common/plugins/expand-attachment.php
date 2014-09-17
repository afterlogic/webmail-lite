<?php

/*
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 *
 */

/**
 * @package Api
 * @deprecated since 7.0.0
 */
abstract class AApiExpandAttachmentPlugin extends AApiPlugin
{
	/**
	 * @param string $sVersion
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct($sVersion, CApiPluginManager $oPluginManager)
	{
		parent::__construct($sVersion, $oPluginManager);

		$this->AddHook('webmail.supports-expanding-attachments', 'WebmailSupportsExpandingAttachments');
		$this->AddHook('webmail.expand-attachment', 'WebmailExpandAttachment');
	}

	abstract public function IsMimeTypeSupported($sMimeType, $sFileName = '');

	abstract public function ExpandAttachment($oAccount, $sMimeType, $sFullFilePath, $oApiFileCache);

	/**
	 * @param type $oAccount
	 * @param string $sMimeType
	 * @param type $mResult
	 * @param type $oApiFileCache
	 */
	public function WebmailSupportsExpandingAttachments(&$bResult, $sMimeType, $sFileName)
	{
		if (!$bResult)
		{
			$bResult = $this->IsMimeTypeSupported($sMimeType, $sFileName);
		}
	}

	/**
	 * @param type $oAccount
	 * @param string $sMimeType
	 * @param type $mResult
	 * @param type $oApiFileCache
	 */
	public function WebmailExpandAttachment($oAccount, $sMimeType, $sFileName, $sFullFilePath, &$mResult, $oApiFileCache)
	{
		if ($oAccount && $this->IsMimeTypeSupported($sMimeType, $sFileName) &&
			\file_exists($sFullFilePath) && \is_array($mResult) && $oApiFileCache)
		{
			$aNew = $this->ExpandAttachment($oAccount, $sMimeType, $sFullFilePath, $oApiFileCache);
			if (is_array($aNew))
			{
				foreach ($aNew as $aItem)
				{
					$mResult[] = $aItem;
				}
			}
		}
	}
}
