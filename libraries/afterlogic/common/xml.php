<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @package Api
 */
class CXmlDomNode
{
	/**
	 * @var	string
	 */
	public $TagName;

	/**
	 * @var	string
	 */
	public $Value;

	/**
	 * @var	string
	 */
	public $Comment;

	/**
	 * @var	array
	 */
	public $Attributes;

	/**
	 * @var	array
	 */
	public $Children;

	/**
	 * @param string $sTagName
	 * @param string $sValue = null
	 * @param bool $bIsCDATA = false
	 * @param bool $bIsSimpleCharsCode = false
	 * @param string $sNodeComment = ''
	 */
	public function __construct($sTagName, $sValue = null, $bIsCDATA = false, $bIsSimpleCharsCode = false, $sNodeComment = '')
	{
		$this->Attributes = array();
		$this->Children = array();

		$this->TagName = $sTagName;
		$this->Value = ($bIsCDATA && null !== $sValue)
			? '<![CDATA['.
				(($bIsSimpleCharsCode) ?
					api_Utils::EncodeSimpleSpecialXmlChars($sValue) : api_Utils::EncodeSpecialXmlChars($sValue))
			.']]>' : $sValue;

		$this->Comment = $sNodeComment;
	}

	/**
	 * @param CXmlDomNode &$oNode
	 */
	public function AppendChild(&$oNode)
	{
		if ($oNode)
		{
			$this->Children[] =& $oNode;
		}
	}

	/**
	 * @param CXmlDomNode &$oNode
	 */
	public function PrependChild(&$oNode)
	{
		if ($oNode)
		{
			array_unshift($this->Children, $oNode);
		}
	}

	/**
	 * @param string $sName
	 * @param string $sValue
	 */
	public function AppendAttribute($sName, $sValue)
	{
		$this->Attributes[$sName] = $sValue;
	}

	/**
	 * @param string $sTagName
	 * @return &CXmlDomNode
	 */
	public function &GetChildNodeByTagName($sTagName)
	{
		$iNodeKey = null;
		$oCXmlDomNode = null;
		$aNodeKeys = array_keys($this->Children);
		foreach ($aNodeKeys as $iNodeKey)
		{
			if ($this->Children[$iNodeKey] && $this->Children[$iNodeKey]->TagName === $sTagName)
			{
				$oCXmlDomNode =& $this->Children[$iNodeKey];
				break;
			}
		}
		return $oCXmlDomNode;
	}

	/**
	 * @param string $sTagName
	 * @return string
	 */
	public function GetChildValueByTagName($sTagName)
	{
		$sResult = '';
		$oNode =& $this->GetChildNodeByTagName($sTagName);
		if (null !== $oNode)
		{
			$sResult = api_Utils::DecodeSpecialXmlChars($oNode->Value);
		}
		return $sResult;
	}

	/**
	 * @param bool $bSplitLines = false
	 * @return string
	 */
	public function ToString($bSplitLines = false)
	{
		$sAttributes = '';
		foreach ($this->Attributes as $sName => $sValue)
		{
			$sName = htmlspecialchars($sName);
			$sValue = htmlspecialchars($sValue);
			$sAttributes .= ' '.$sName.'="'.$sValue.'"';
		}

		$sChilds = '';
		$iKeyIndex = null;
		if (0 < count($this->Children))
		{
			foreach (array_keys($this->Children) as $iKeyIndex)
			{
				$sChilds .= $this->Children[$iKeyIndex]->ToString($bSplitLines);
				if ($bSplitLines)
				{
					$sChilds .= "\r\n";
				}
			}

			if ($bSplitLines)
			{
				$aLines = explode("\r\n", $sChilds);
				$sChilds = '';
				foreach ($aLines as $sLine)
				{
					$sChilds .= ($sLine !== '') ? sprintf("\t%s\r\n", $sLine) : '';
				}
			}
		}

		$sCommentPart = (empty($this->Comment)) ? '' : "<!-- ".$this->Comment." -->\r\n";

		if ($sChilds === '' && null === $this->Value)
		{
			$sOutStr = sprintf('<%s%s />', $this->TagName, $sAttributes);
			if ($bSplitLines)
			{
				$sOutStr .= "\r\n";
			}

			return $sCommentPart.$sOutStr;
		}

		$sValue = (null !== $this->Value) ? trim($this->Value) : '';

		if ($bSplitLines)
		{
			if ($sValue !== '' && $sChilds === '')
			{
				return $sCommentPart.sprintf('<%s%s>%s</%s>', $this->TagName, $sAttributes, $sValue, $this->TagName);
			}
			if ($sValue === '' && $sChilds === '' )
			{
				return $sCommentPart.sprintf('<%s%s />', $this->TagName, $sAttributes);
			}

			return $sCommentPart.sprintf("<%s%s>%s\r\n%s</%s>\r\n", $this->TagName, $sAttributes, $sValue, $sChilds, $this->TagName);
		}

		return $sCommentPart.sprintf('<%s%s>%s%s</%s>', $this->TagName, $sAttributes, $sValue, $sChilds, $this->TagName);
	}

	/**
	 * @param string $sName
	 * @param string $sDefault = null
	 * @return string
	 */
	public function GetAttribute($sName, $sDefault = null)
	{
		return isset($this->Attributes[$sName]) ? api_Utils::DecodeSpecialXmlChars($this->Attributes[$sName]) : $sDefault;
	}
}

/**
 * @package Api
 */
class CXmlDocument
{
	/**
	 * @var CXmlDomNode
	 */
	public $XmlRoot = null;

	/**
	 * @param string $sName
	 * @param string $sValue
	 */
	public function CreateElement($sName, $sValue = null)
	{
		$this->XmlRoot = new CXmlDomNode($sName, $sValue);
	}

	/**
	 * @param string $sXmlText
	 * @return bool
	 */
	public function ParseFromString($sXmlText)
	{
		$bResult = false;
		if (!empty($sXmlText))
		{
			$oParser = xml_parser_create();
			xml_parser_set_option($oParser, XML_OPTION_CASE_FOLDING, false);
			xml_parser_set_option($oParser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
//			xml_parser_set_option($oParser, XML_OPTION_SKIP_WHITE, true);

			xml_set_element_handler($oParser,
				array(&$this, '_startElement'), array(&$this, '_endElement'));

			xml_set_character_data_handler($oParser, array(&$this, '_charData'));

			$bResult = xml_parse($oParser, $sXmlText);
			if (!$bResult)
			{
				$sError = xml_error_string( xml_get_error_code($oParser));
			}
			xml_parser_free($oParser);
		}

		return (bool) $bResult;
	}

	/**
	 * @param bool $bSplitLines
	 * @return string
	 */
	public function ToString($bSplitLines = false)
	{
		$sOutStr = '<'.'?'.'xml version="1.0" encoding="utf-8"?'.'>';
		if ($bSplitLines)
		{
			$sOutStr .= "\r\n";
		}

		if (null !== $this->XmlRoot)
		{
			$sOutStr .= $this->XmlRoot->ToString($bSplitLines);
		}

		return $sOutStr;
	}

	/**
	 * @param string $sFileName
	 * @return bool
	 */
	public function LoadFromFile($sFileName)
	{
		$sXmlData = @file_get_contents($sFileName);
		if (false !== $sXmlData)
		{
			return $this->ParseFromString($sXmlData);
		}
		return false;
	}

	/**
	 * @param string $sFileName
	 * @return bool
	 */
	public function SaveToFile($sFileName)
	{
		$bResult = false;
		$rFilePointer = @fopen($sFileName, 'wb');
		if ($rFilePointer)
		{
			$bResult = (false !== @fwrite($rFilePointer, $this->ToString(true)));
			$bResult = @fclose($rFilePointer);
		}

		return $bResult;
	}

	/**
	 * @param string $sName
	 * @return string
	 */
	public function GetParamValueByName($sName)
	{
		$oParam =& $this->getParamNodeByName($sName);
		return (null !== $oParam && isset($oParam->Attributes['value']))
			? api_Utils::DecodeSpecialXmlChars($oParam->Attributes['value']) : '';
	}

	/**
	 * @param string $sName
	 * @return string
	 */
	public function GetParamTagValueByName($sName)
	{
		$oParam =& $this->getParamNodeByName($sName);
		return (null !== $oParam) ? api_Utils::DecodeSpecialXmlChars($oParam->Value) : '';
	}

	/**
	 * @param string $sName
	 * @return object
	 */
	protected function &getParamNodeByName($sName)
	{
		$iNodeKey = null;
		$oNull = null;
		if ($this->XmlRoot && is_array($this->XmlRoot->Children))
		{
			$aNodeKeys = array_keys($this->XmlRoot->Children);
			foreach ($aNodeKeys as $iNodeKey)
			{
				if ($this->XmlRoot->Children[$iNodeKey]->TagName == 'param' &&
					isset($this->XmlRoot->Children[$iNodeKey]->Attributes['name']) &&
					$this->XmlRoot->Children[$iNodeKey]->Attributes['name'] == $sName)
				{
					return $this->XmlRoot->Children[$iNodeKey];
				}
			}
		}
		return $oNull;
	}

	/**
	 * @access private
	 * @param object $oParser
	 * @param string $sName
	 * @param array $aAttributes
	 */
	public function _startElement($oParser, $sName, $aAttributes)
	{
		$this->_nullFunction($oParser);
		$oNode = new CXmlDomNode($sName);
		$oNode->Attributes = $aAttributes;
		if ($this->XmlRoot == null)
		{
			$this->XmlRoot =& $oNode;
		}
		else
		{
			$oRootNode = null;
			$oRootNode =& $this->_stack[count($this->_stack) - 1];
			$oRootNode->Children[] =& $oNode;
		}

		$this->_stack[] =& $oNode;
	}

	/**
	 * @access private
	 */
	public function _endElement()
	{
		array_pop($this->_stack);
	}

	/**
	 * @access private
	 * @param object $oParser
	 * @param string $sText
	 */
	function _charData($oParser, $sText)
	{
		$oNode = null;
		$this->_nullFunction($oParser);
		$oNode =& $this->_stack[count($this->_stack) - 1];
		if ($oNode->Value == null)
		{
			$oNode->Value = '';
		}

		if ($sText == '>')
		{
			$oNode->Value .= '&gt;';
		}
		else if ($sText == '<')
		{
			$oNode->Value .= '&lt;';
		}
		else
		{
			$oNode->Value .= $sText;
		}
	}

	/**
	 * @access private
	 * @return bool
	 */
	public function _nullFunction()
	{
		return true;
	}
}
