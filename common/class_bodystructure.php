<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__) . '/../'));

define('BODYSTRUCTURE_TYPE_NONE', 0);
define('BODYSTRUCTURE_TYPE_TEXT_PLAIN', 1);
define('BODYSTRUCTURE_TYPE_TEXT_HTML', 2);
define('BODYSTRUCTURE_TYPE_ATTACHMENT', 3);

define('BODYSTRUCTURE_KEY_CHARSET', 'charset');
define('BODYSTRUCTURE_KEY_NAME', 'name');
define('BODYSTRUCTURE_KEY_FULLNAME', 'fullname');
define('BODYSTRUCTURE_KEY_ENCODE_TYPE', 'encode_type');

class CBodyStructureObject
{
	/**
	 * @var string
	 */
	var $_responseSourse;

	/**
	 * @var array
	 */
	var $_responseArray;

	/**
	 * @var array
	 */
	var $_bodyParts;

	/**
	 * @var array
	 */
	var $_plainIndexs;

	/**
	 * @var array
	 */
	var $_htmlIndexs;

	/**
	 * @var array
	 */
	var $_attachmentIndexs;

	/**
	 * @var string
	 */
	var $_fullHeaders;

	/**
	 * @var string
	 */
	var $_html;

	/**
	 * @var string
	 */
	var $_plain;

	/**
	 * @var int
	 */
	var $_size;

	/**
	 * @var int
	 */
	var $_uid;

	/**
	 * @var string
	 */
	var $_flags;

	function CBodyStructureObject($bodyStructureResponse)
	{
		$this->_responseArray = null;
		$this->_plainIndexs = array();
		$this->_htmlIndexs = array();
		$this->_attachmentIndexs = array();
		$this->_bodyParts = array();
		$this->_parseResponse($bodyStructureResponse);
	}

	function _parseResponse($bodyStructureResponse)
	{
		$this->_responseSourse = $bodyStructureResponse;

		$endOfline = strpos($this->_responseSourse, "\n");
		$firstLine = (false === $endOfline) ? $this->_responseSourse : substr($this->_responseSourse, 0, $endOfline);

		$aFlags = $aUid = $aSize = array();
		preg_match('/FLAGS \(([^\)]*)\)/', $firstLine, $aFlags);
		preg_match('/UID ([\d]+)/', $firstLine, $aUid);
		preg_match('/RFC822\.SIZE ([\d]+)/', $firstLine, $aSize);

		$this->_flags = isset($aFlags[1]) ? trim(trim($aFlags[1]), '()') : '';
		$this->_uid = isset($aUid[1]) ? (int) $aUid[1] : -1;
		$this->_size = isset($aSize[1]) ? (int) $aSize[1] : 0;

		$clearResponseA = explode('BODYSTRUCTURE', $this->_responseSourse, 2);
		if (count($clearResponseA) == 2)
		{
			$clearResponseS = substr(trim($clearResponseA[1]), 0, -1);
			$this->_responseArray = CBodyStructureParser::GetArray($clearResponseS);
			$this->_fillIndexs();
		}
	}

	function _fillIndexs()
	{
		if (is_array($this->_responseArray))
		{
			$this->_plainIndexs = array();
			$this->_htmlIndexs = array();
			$this->_attachmentIndexs = array();
			$this->_recFillIndexs($this->_responseArray);
		}
	}

	function _recFillIndexs($array, $parentKey = null)
	{
		foreach ($array as $key => $variable)
		{
			if (isset($variable[0]))
			{
				$idx = (null !== $parentKey) ? $parentKey.'.'.($key + 1) : (string) ($key + 1);
				if (is_array($variable[0]))
				{
					$this->_recFillIndexs($variable, $idx);
				}
				else
				{
					$type = CBodyStructureParser::GetBodyStructurePartType($variable);
					switch ($type)
					{
						case BODYSTRUCTURE_TYPE_TEXT_PLAIN:
							$this->_plainIndexs[] = $idx;
							break;
						case BODYSTRUCTURE_TYPE_TEXT_HTML:
							$this->_htmlIndexs[] = $idx;
							break;
						case BODYSTRUCTURE_TYPE_ATTACHMENT:
							$this->_attachmentIndexs[] = $idx;
							break;
					}
				}
			}
		}
	}

	/**
	* @return int
	*/
	function ClassType()
	{
		return (int) (count($this->_htmlIndexs) > 0) << 1 | (int) (count($this->_plainIndexs) > 0);
	}

	function GetPartByKey($key)
	{
		$return = null;
		$keyArray = explode('.', $key);
		if (is_array($keyArray))
		{
			$searchArray = $this->_responseArray;
			foreach ($keyArray as $idx)
			{
				$idx = (int) $idx;
				if ($idx > 0)
				{
					$idx--;
				}
				$return = $searchArray[$idx];
				$searchArray = $return;
			}
		}

		return $return;
	}

	/**
	 * @return array
	 */
	function GetPlainBodyTextIndexs()
	{
		return $this->_plainIndexs;
	}

	/**
	 * @return array
	 */
	function GetHtmlBodyTextIndexs()
	{
		return $this->_htmlIndexs;
	}

	/**
	 * @return array
	 */
	function GetAttachmentIndexs()
	{
		return $this->_attachmentIndexs;
	}

	function GetRequestByMode($mode)
	{
		$request = '';
		if (($mode & 1) == 1 || ($mode & 128) == 128)
		{
			$request .= ' BODY.PEEK[HEADER]';
		}

		$arrH = $this->GetHtmlBodyTextIndexs();
		$arrP = $this->GetPlainBodyTextIndexs();

		$isHtmlExist = count($arrH) > 0;
		$isPlainExist = count($arrP) > 0;

		if ($isPlainExist && (($mode & 4) == 4 || ($mode & 16) == 16 || ($mode & 64) == 64 || (($mode & 2) == 2 && !$isHtmlExist)))
		{
			foreach ($arrP as $idx)
			{
				$request .= ' BODY.PEEK['.$idx.']';
			}
		}

		if ($isHtmlExist && (($mode & 2) == 2 || ($mode & 8) == 8 || ($mode & 16) == 16 || ($mode & 32) == 32))
		{
			foreach ($arrH as $idx)
			{
				$request .= ' BODY.PEEK['.$idx.']';
			}
		}

		if (false)
		{
			$arr = $this->GetAttachmentIndexs();
			if (count($arr) > 0)
			{
				foreach ($arr as $idx)
				{
					$request .= ' BODY.PEEK['.$idx.']';
				}
			}
		}

		return trim($request);
	}

	function GetSize()
	{
		return $this->_size;
	}

	function GetFlags()
	{
		return $this->_flags;
	}

	function GetUid()
	{
		return $this->_uid;
	}

	function GetFullHeaders()
	{
		return $this->_fullHeaders;
	}

	function SetFullHeaders($headers)
	{
		$this->_fullHeaders = $headers;
	}

	function SetBodyPart($key, $text)
	{
		$this->_bodyParts[$key] = $text;
	}

	function GetBodyPart($key)
	{
		return isset($this->_bodyParts[$key]) ? $this->_bodyParts[$key] : '';
	}

	function &GetBodyPartsAsArray()
	{
		return $this->_bodyParts;
	}
}

class CBodyStructureParser
{
	static function GetCharsetFromPart($arr)
	{
		return CBodyStructureParser::_getParamFromPart($arr, 'charset', 2);
	}

	static function GetDurationFromPart($arr)
	{
		$duration = CBodyStructureParser::_getParamFromPart($arr, 'duration', 2);
		if (empty($duration))
		{
			$duration = CBodyStructureParser::_getParamFromPart($arr, 'duration', 8);
		}
		return empty($duration) ? 0 : (int) $duration;
	}

	static function GetNameFromPart($arr)
	{
		$name = CBodyStructureParser::_getParamFromPart($arr, 'name', 2);
		if (strlen($name) == 0 && !CBodyStructureParser::IsMessageRfc822Part($arr))
		{
			$name = CBodyStructureParser::_getParamFromPart($arr, 'name', 8);
		}
		return $name;
	}

	static function GetFileNameFromPart($arr)
	{
		$fileName = CBodyStructureParser::_getParamFromPart($arr, 'filename', 2);
		if (strlen($fileName) == 0 && !CBodyStructureParser::IsMessageRfc822Part($arr))
		{
			$fileName = CBodyStructureParser::_getParamFromPart($arr, 'filename', 8);
		}
		return $fileName;
	}

	static function GetEncodeFromPart($arr)
	{
		$return = CBodyStructureParser::_getRootParamFromPart($arr, 5);
		if (null !== $return)
		{
			return $return;
		}

		return '';
	}

	static function GetContentIdFromPart($arr)
	{
		$return = CBodyStructureParser::_getRootParamFromPart($arr, 3);
		if (null !== $return)
		{
			return $return;
		}

		return '';
	}

	/**
	 * @param array $arr
	 * @return bool
	 */
	static function IsInlinePart($arr)
	{
		$bResult = false;
		if (isset($arr[8]) && isset($arr[8][0]) && is_string($arr[8][0]))
		{
			$bResult = 'inline' === strtolower($arr[8][0]);
			if ($bResult)
			{
				$part_contentId = CBodyStructureParser::GetContentIdFromPart($arr);
				if (empty($part_contentId) || (is_array($arr) && isset($arr[0], $arr[1]) &&
					'image' !== strtolower($arr[0])))
				{
					$bResult = false;
				}
			}
		}

		return $bResult;
	}

	static function IsMessageRfc822Part($arr)
	{
		if (isset($arr[0], $arr[1]))
		{
			return (strtolower($arr[0]) == 'message' && strtolower($arr[1]) == 'rfc822');
		}
		return false;
	}

	static function GetSizeFromPart($arr)
	{
		$size = CBodyStructureParser::GetEncodedSizeFromPart($arr);
		$encode = CBodyStructureParser::GetEncodeFromPart($arr);
		if ($encode)
		{
			$type = ConvertUtils::GetBodyStructureEncodeType($encode);
			if ($type == IMAP_BS_ENCODETYPE_BASE64)
			{
				$size = $size * 0.75;
			}
			else if ($encode == IMAP_BS_ENCODETYPE_QPRINTABLE)
			{
				$size = $size * 0.44;
			}
		}

		return round($size);
	}

	static function GetEncodedSizeFromPart($arr)
	{
		$return = CBodyStructureParser::_getRootParamFromPart($arr, 6);
		if (null !== $return)
		{
			return (int) $return;
		}

		return 0;
	}

	static function _getRootParamFromPart($arr, $index)
	{
		if (is_array($arr) && isset($arr[$index]))
		{
			if (is_string($arr[$index]) && 'NIL' !== $arr[$index])
			{
				return $arr[$index];
			}
		}

		return null;
	}

	static function _getParamFromPart($arr, $paramName, $paramIndex = 2)
	{
		$return = '';
		if (is_array($arr) && isset($arr[$paramIndex]))
		{
			if (is_array($arr[$paramIndex]))
			{
				CBodyStructureParser::_searchNextParamValue($arr[$paramIndex], $paramName, $return);
			}
		}

		return $return;
	}

	static function _searchNextParamValue($array, $paramName, &$return)
	{
		$paramName = strtolower($paramName);
		$returnNext = false;
		foreach ($array as $param)
		{
			if ($returnNext)
			{
				$return = $param;
				break;
			}

			if (is_array($param))
			{
				CBodyStructureParser::_searchNextParamValue($param, $paramName, $return);
			}
			else
			{
				$lparam = strtolower($param);
				if ($lparam == $paramName)
				{
					$returnNext = true;
				}
			}
		}
	}

	static function GetNullNameByType($arr)
	{
		if (is_array($arr) && isset($arr[0], $arr[1]))
		{
			$ext = strtolower($arr[1]);
			switch(strtolower($arr[0]))
			{
				case 'image';
					return 'image.'.$ext;
				case 'message';
					return 'message.eml';
			}

			if ('text' === strtolower($arr[0]) && 'calendar' === $ext)
			{
				return 'calendar.ics';
			}
		}

		return 'attachment.dat';
	}

	static function GetBodyStructurePartType($arr)
	{
		$type = BODYSTRUCTURE_TYPE_NONE;
		if (is_array($arr) && count($arr) > 3)
		{
			$lower_0 = isset($arr[0]) && is_string($arr[0]) ? strtolower($arr[0]) : '';
			$lower_1 = isset($arr[1]) && is_string($arr[1]) ? strtolower($arr[1]) : '';
			if ($lower_0 == 'text')
			{
				$type = BODYSTRUCTURE_TYPE_TEXT_PLAIN;
				if ($lower_1 == 'html')
				{
					$type = BODYSTRUCTURE_TYPE_TEXT_HTML;
				}
				else if ($lower_1 == 'calendar')
				{
					$type = BODYSTRUCTURE_TYPE_ATTACHMENT;
				}

				if (is_array($arr[2]))
				{
					foreach ($arr[2] as $param)
					{
						$lparam = is_string($param) ? strtolower($param) : '';
						if ($lparam == 'name' || $lparam == 'filename')
						{
							$type = BODYSTRUCTURE_TYPE_ATTACHMENT;
						}
					}
				}
			}
			else if (($lower_0 == 'message' && $lower_1 == 'rfc822') || ($lower_0 == 'image' && strlen($arr[3]) > 0))
			{
				$type = BODYSTRUCTURE_TYPE_ATTACHMENT;
			}
			else
			{
				if (is_array($arr[2]))
				{
					foreach ($arr[2] as $param)
					{
						$lparam = is_string($param) ? strtolower($param) : '';
						if ($lparam == 'name' || $lparam == 'filename')
						{
							$type = BODYSTRUCTURE_TYPE_ATTACHMENT;
						}
					}
				}

				if (isset($arr[8]) && is_array($arr[8]))
				{
					foreach ($arr[8] as $param)
					{
						if (is_array($param))
						{
							foreach($param as $value)
							{
								$lparam = is_string($value) ? strtolower($value) : '';
								if ($lparam == 'name' || $lparam == 'filename')
								{
									$type = BODYSTRUCTURE_TYPE_ATTACHMENT;
								}
							}
						}
						else
						{
							$lparam = is_string($param) ? strtolower($param) : '';
							if ($lparam == 'attachment')
							{
								$type = BODYSTRUCTURE_TYPE_ATTACHMENT;
							}
						}
					}
				}
			}
		}
		return $type;
	}

	static function ClosingPos($str, $start)
	{
		$level = $isQuote = 0;
		$len = strlen($str);

		for ($i = $start; $i < $len; $i++)
		{
			if ($str{$i} == '"' && $str{$i - 1} != '\\')
			{
				$isQuote = ($isQuote + 1) % 2;
			}

			if (!$isQuote)
			{
				if ($str[$i] == '(')
				{
					$level++;
				}
				else if (($level > 0) && ($str{$i} == ')'))
				{
					$level--;
				}
				else if (($level == 0) && ($str{$i} == ')'))
				{
					return $i;
				}
			}
		}
	}

	static function Parse($str)
	{
		$id = $isQuote = 0;
		$a = array();
		$len = strlen($str);

		for ($i = 0; $i < $len; $i++)
		{
			$ch = $str{$i};
			if ($ch == '{')
			{
				$literalStrValue = '';
				$endPos = strpos($str, '}'."\r\n", $i);
				if (false !== $endPos && $endPos - $i > 0 && $endPos - $i < 10)
				{
					$literalStrValue = substr($str, $i + 1, $endPos - $i - 1);
					if (!preg_match('/^[0-9]+$/', $literalStrValue))
					{
						$literalStrValue = '';
					}
				}

				$literalIntValue = (int) $literalStrValue;
				if ($literalIntValue > 0)
				{
					$i = $i + strlen($literalStrValue) + 4;
					$literalLine = substr($str, $i, $literalIntValue);
					$i = $i + $literalIntValue;
					$a[$id] = isset($a[$id]) ? $a[$id].$literalLine : $literalLine;
				}
				else
				{
					$a[$id] = isset($a[$id]) ? $a[$id].$ch : $ch;
				}
			}
			else if ($ch == '"')
			{
				$isQuote = ($isQuote + 1) % 2;
			}
			else if (!$isQuote)
			{
				if ($ch == ' ')
				{
					$id++;
					while (@$str{$i + 1} == ' ')
					{
						$i++;
					}
				}
				else if ($ch == '(')
				{
					$i++;
					$endPos = CBodyStructureParser::ClosingPos($str, $i);
					$partLen = $endPos - $i;
					if ($partLen < 0)
					{
						break;
					}
					$a[$id] = CBodyStructureParser::Parse(substr($str, $i, $partLen));
					$i = $endPos;
				}
				else
				{
					$a[$id] = isset($a[$id]) ? $a[$id].$ch : $ch;
				}
			}
			else if ($isQuote)
			{
				if ($ch == '\\')
				{
					$i++;
					if ($ch == '"' || $ch == '\\')
					{
						$a[$id] = isset($a[$id]) ? $a[$id].$ch : $ch;
					}
				}
				else
				{
					$a[$id] = isset($a[$id]) ? $a[$id].$ch : $ch;
				}
			}
		}

		reset($a);
		if (isset($a[0]) && is_string($a[0]) && !isset($a[1]))
		{
			$a[1] = 'text' === $a[0] ? 'plain' : '';
		}
		return $a;
	}

	static function GetArray($str)
	{
		$line = trim($str);
		$line = substr($line, 1, strlen($line) - 2);
		$line = str_replace(')(', ') (', $line);

		$struct = CBodyStructureParser::Parse($line);
		if (is_array($struct) && isset($struct[0], $struct[1]) && is_string($struct[0]) && is_string($struct[1]))
		{
			$struct = array($struct);
		}
		return $struct;
	}
}
