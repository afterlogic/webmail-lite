<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	/**
	 * @return string
	 */
	function getGlobalError()
	{
		return isset($GLOBALS[ErrorDesc]) ? $GLOBALS[ErrorDesc] : '';
	}

	/**
	 * @param string $errorString
	 */
	function setGlobalError($errorString)
	{
		$GLOBALS[ErrorDesc]	= $errorString;
	}

	/**
	 * @return string
	 */
	function GetSessionAttachDir()
	{
		if (!CSession::Has(ATTACH_DIR))
		{
			CSession::Set(ATTACH_DIR, md5(CSession::Id()));
		}
		
		return CSession::Get(ATTACH_DIR);
	}

	/**
	 * @param	string	$output
	 * @return	string
	 */
	function obStartGzip($output)
	{
		if (api_Utils::IsGzipSupported() && !ini_get('zlib.output_compression'))
		{
			$output = gzencode($output);
			/* $output = myGZip($output); */
			if ($output !== false)
			{
				@header('Content-Encoding: gzip');
			}
		}
		return $output;
	}

	/**
	 * @param	string		$data
	 * @return	string | false
	 */
	function myGZip($data)
	{
		if (function_exists('gzcompress'))
		{
			$size = strlen($data);
			$crc = crc32($data);
			$data = gzcompress($data, 2);
			if (false === $data)
			{
				return false;
			}

			$content = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
			$data = substr($data, 0, strlen($data) - 4);
			$content .= $data;
			$content .= (pack('V', $crc));
			$content .= (pack('V', $size));
			return $content;
		}
		return false;
	}

	/**
	 * @param	string	$output
	 * @return	string
	 */
	function obStartNoGzip($output)
	{
		return $output;
	}
	
	/**
	 * @return	string
	 */
	function GetCurrentHost()
	{
		$host = isset($_SERVER['HTTP_HOST']) ? strtolower(trim($_SERVER['HTTP_HOST'])) : '';
		if (substr($host, 0, 4) === 'www.')
		{
			$host = substr($host, 4);
		}
		
		return $host;
	}

	class CMessageInfo
	{
		var $id;
		var $uid;
		var $folderId;
		var $folderFullName;

		/**
		 * @return string
		 */
		function GetUrl()
		{
			return 'msg_id='.urlencode($this->id).'&msg_uid='.urlencode($this->uid).'&folder_id='.urlencode($this->folderId).'&folder_fname='.urlencode($this->folderFullName);
		}

		/**
		 * @return string
		 */
		function GetShortUrl()
		{
			return 'msg_id='.urlencode($this->id).'&msg_uid='.urlencode($this->uid);
		}

		function SetInfo($id, $uid, $folderId = '', $folderFullName = '')
		{
			$this->id = $id;
			$this->uid = $uid;
			$this->folderId = $folderId;
			$this->folderFullName = $folderFullName;
		}

		function Id()
		{
			return (int) $this->id;
		}

		function Uid()
		{
			return $this->uid;
		}

		function FolderId()
		{
			return (int) $this->folderId;
		}

		function FolderFullName()
		{
			return $this->folderFullName;
		}
	}

	/* timezone fix code */
	if (defined('SERVER_TIME_ZONE') && function_exists('date_default_timezone_set'))
	{
		@date_default_timezone_set(SERVER_TIME_ZONE);
	}
	
	/**
	 * @return	string
	 */
	function buildInfoCont($errorClass = null, $errorDesc = null)
	{
		$html = '<table class="'. $errorClass . '" style="position:static;" id="info">
	<tr style="position:relative;z-index:20">
		<td class="wm_shadow" style="width:2px;font-size:1px;"></td>
		<td>
			<div id="info_message" class="wm_info_message">
				<span class="wm_info_image"></span><span class="wm_info_text">' . $errorDesc . '</span>
			</div>
			<div class="a">&nbsp;</div>
			<div class="b">&nbsp;</div>
		</td>
		<td class="wm_shadow" style="width:2px;font-size:1px;"></td>
	</tr>
	<tr>
		<td colspan="3" class="wm_shadow" style="height:2px;background:none;">
			<div class="a">&nbsp;</div>
			<div class="b">&nbsp;</div>
		</td>
	</tr>
	<tr style="position:relative;z-index:19">
		<td colspan="3" style="height:2px;">
			<div class="a wm_shadow" style="margin:0px 2px;height:2px; top:-4px; position:relative; border:0px;background:#555;">&nbsp;</div>
		</td>
	</tr>
</table>';

		return $html;
	}
