<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 * 
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	require_once(WM_ROOTPATH.'common/mime/inc_constants.php');
	require_once(WM_ROOTPATH.'common/mime/class_headercollection.php');
	require_once(WM_ROOTPATH.'common/mime/class_headerparametercollection.php');
	require_once(WM_ROOTPATH.'common/mime/class_mimepartcollection.php');
	require_once(WM_ROOTPATH.'common/class_convertutils.php');

	class MimePart
	{
		/**
		 * @var HeaderCollection
		 */
		var $Headers = null;

		/**
		 * @access private
		 * @var string
		 */
		var $_body = '';
		
		/**
		 * @access private
		 * @var MimePartCollection
		 */
		var $_subParts = null;
		
		/**
		 * @access private
		 * @var string
		 */
		var $_sourceCharset;
		
		/**
		 * @var string
		 */
		var $OriginalHeaders;

		/**
		 * @var string
		 */
		var $BodyStructureIndex = null;

		/**
		 * @var string
		 */
		var $BodyStructureSize = null;

		/**
		 * @var string
		 */
		var $BodyStructureEncode = null;
		
		/**
		 * @var int
		 */
		var $BodyStructureDuration = null;
		
		/**
		 * @param int $len[optional] = 0
		 * @return string
		 */
		function GetBinaryBody($len = 0)
		{
			$body = $this->_body;
			$bodyLen = strlen($body);
			if ($bodyLen == 0)
			{
				return '';
			}
			
			if (!isset($GLOBALS[MIMEConst_DoNotUseMTrim]) && $len > 0 && $bodyLen > $len)
			{
				$body = substr($body, 0, $len);
				$GLOBALS[MIMEConst_IsBodyTrim] = true;
			}
			
			$dtype = strtolower($this->Headers->GetHeaderValueByName(MIMEConst_ContentTransferEncodingLower));
			return ConvertUtils::DecodeBodyByType($body, $dtype);
		}
		
		/**
		 * @param int $len[optional] = 0
		 * @return string
		 */
		function GetBody($len = 0)
		{
			return ConvertUtils::ConvertEncoding($this->GetBinaryBody($len), $this->_sourceCharset, $GLOBALS[MailOutputCharset]);
		}
		
		/**
		 * @return int
		 */
		function BodyLen()
		{
			return strlen($this->_body);
		}
		
		/**
		 * @return bool
		 */
		function IsMimePartAttachment($bIsAlternative = false)
		{
			if ($this->Headers != null)
			{
				$contentTypeHeader = new HeaderParameterCollection($this->GetContentType());
				$contentDispositionHeader = new HeaderParameterCollection($this->GetDisposition());
				$contentTypeHeaderValue = $this->GetContentType();
				$contentIDValue = $this->GetContentID();
				$contentDispositionHeaderValue = $this->GetDisposition();
				
				if ($contentTypeHeader != null)
				{
					if (0 === strpos($contentTypeHeaderValue, 'text/calendar'))
					{
						return true;
					}
					
					if ($bIsAlternative && false !== strpos($contentTypeHeaderValue, 'text'))
					{
						return false;
					}

					if (strpos($contentTypeHeaderValue, 'ms-tnef') !== false)
					{
						return true;
					}
					
					$temp = $contentTypeHeader->GetByName(MIMEConst_NameLower);
					if ($temp && strlen($temp->Value) != 0)
					{
						return true;
					}
				}
				
				if ($contentDispositionHeader != null)
				{
					$temp = $contentDispositionHeader->GetByName(MIMEConst_FilenameLower);
					if ($temp && strlen($temp->Value) != 0)
					{
						return true;
					}
				}
				
				if ($contentTypeHeaderValue != null)
				{
					if (strpos(strtolower($contentTypeHeaderValue), MIMEConst_MessageLower) !== false)
					{
						return true;
					}	
				}
				
				if (strpos(strtolower($contentTypeHeaderValue), 'image') !== false)
				{
					return true;
				}
			
				if ($contentDispositionHeaderValue != null)
				{
					if (strpos(strtolower($contentDispositionHeaderValue), MIMEConst_AttachmentLower) !== false)
					{
						return true;
					}	
				}
				
				if (false !== strpos($contentTypeHeaderValue, 'text'))
				{
					return false;
				}
				
				if (strlen($contentIDValue) > 3)
				{
					return true;
				}	

				/*if ($contentDispositionHeaderValue != null)
				{
					if (strpos(strtolower($contentDispositionHeaderValue), MIMEConst_InlineLower) !== false)
					{
						return true;
					}	
				}*/
			}
			return false;
		}
		
		/**
		 * @return bool
		 */
		function IsMimePartTextBody()
		{
			if ($this->Headers == null || $this->Headers->Count() == 0)
			{
				return true;
			}
			
			$contType = strtolower($this->GetContentType());
			$contDist = $this->GetDisposition();
			
			if (!$contType)
			{
				return false;
			}

			$contentTypeHeader =  new HeaderParameterCollection($contType);
			$contentDispositionHeader = ($contDist) ? new HeaderParameterCollection($contDist) : null;

			$attach = $filename = null;
			if ($contentDispositionHeader)
			{
				$attach = $contentDispositionHeader->GetByName(MIMEConst_AttachmentLower); 
				$filename = $contentDispositionHeader->GetByName(MIMEConst_FilenameLower); 
			} 

			$name = ($contentTypeHeader) ? $contentTypeHeader->GetByName(MIMEConst_NameLower) : null; 
			
			if ($attach != null || $filename != null || $name != null)
			{
				return false;
			}
			
			$filenameVal = ($filename) ? $filename->Value : '';
			$nameVal = ($name) ? $name->Value : '';
			
			if (strlen($filenameVal) != 0 || strlen($nameVal) != 0)
			{
				return false;
			}
			if (strpos($contType, MIMETypeConst_TextHtml) !== false || strpos($contType, MIMETypeConst_TextPlain) !== false)
			{
				return true;
			}
			return false;
		}
		
		/**
		 * @return MimePartCollection
		 */
		function GetSubParts()
		{
			return $this->_subParts;
		}
		
		/**
		 * @return string
		 */
		function GetContentID()
		{
			return trim($this->Headers->GetHeaderValueByName(MIMEConst_ContentIDLower), '<>');
		}
		
		/**
		 * @return string
		 */
		function GetContentLocation()
		{
			return $this->Headers->GetHeaderValueByName(MIMEConst_ContentLocationLower);
		}
		
		/**
		 * @return string
		 */
		function GetContentType()
		{
			return $this->Headers->GetHeaderDecodedValueByName(MIMEConst_ContentTypeLower);
		}

		/**
		 * @return string
		 */
		function GetSensitivityAsString()
		{
			return $this->Headers->GetHeaderValueByName(MIMEConst_SensitivityLower);
		}

		/**
		 * @return int
		 */
		function GetSensitivityAsInt()
		{
			$return = MIME_SENSIVITY_NOTHING;
			$sensitivity = strtolower($this->GetSensitivityAsString());
			switch ($sensitivity)
			{
				case 'personal':
					$return = MIME_SENSIVITY_PERSONAL;
					break;
				case 'private':
					$return = MIME_SENSIVITY_PRIVATE;
					break;
				case 'company-confidential':
					$return = MIME_SENSIVITY_CONFIDENTIAL;
					break;
			}
			
			return $return;
		}

		/**
		 * @return string
		 */
		function GetDescription()
		{
			return $this->Headers->GetHeaderValueByName(MIMEConst_ContentDescriptionLower);
		}
		
		/**
		 * @return string
		 */
		function GetDisposition()
		{
			return $this->Headers->GetHeaderDecodedValueByName(MIMEConst_ContentDispositionLower);
		}
		
		/**
		 * @return string
		 */
		function GetContentTransferEncoding()
		{
			$header = &$this->Headers->GetHeaderByName(MIMEConst_ContentTransferEncodingLower);
			return $header->Value;
		}
		
		/**
		 * @return string
		 */
		function GetContentTypeCharset()
		{
			$content = $this->GetContentType();
			$charset = new HeaderParameterCollection($content);
			$charset = $charset->GetByName(MIMEConst_CharsetLower);
			return ($charset) ? $charset->Value : '';
		}
		
		/**
		 * @return string
		 */
		function GetFilename()
		{
			$contentDispositionHeader = $this->Headers->GetHeaderDecodedValueByName(MIMEConst_ContentDispositionLower);
				
			if ($contentDispositionHeader)
			{
				$headerParameters = new HeaderParameterCollection($contentDispositionHeader);
				$param = $headerParameters->GetByName(MIMEConst_FilenameLower);
				if ($param)
				{
					return $param->Value;
				}
			}
			return '';
		}
		
		/**
		 * @return string
		 */
		function GetContentTypeName()
		{
			$contentTypeHeader = $this->Headers->GetHeaderDecodedValueByName(MIMEConst_ContentTypeLower);
				
			if ($contentTypeHeader)
			{
				$headerParameters = new HeaderParameterCollection($contentTypeHeader);
				$param = $headerParameters->GetByName(MIMEConst_NameLower);
				if ($param)
				{
					return $param->Value;
				}
			}
			return '';
		}
		
		/**
		 * @return string
		 */
		function GetSourceCharset()
		{
			return $this->_sourceCharset;
		}
		
		/**
		 * @param string $rawData optional
		 * @return MimePart
		 */
		function MimePart($rawData = null)
		{
			if ($rawData != null)
			{
				$this->Parse($rawData);
			}
			else
			{
				$this->Headers = new HeaderCollection();
			}
		}
		
		function &MailExplode(&$rawData)
		{
			$rawData = ltrim($rawData);
			/*
			$first = '';
			if (strlen($rawData) > 0)
			{
				$first = $rawData{0};
			}
			
			if ($first == "\r" 0 || $first == "\n")
			{
				$rawData = substr($rawData, 1);
			}*/
			
			$result = array('', '');
			$headerEnding = $this->_indexOfHeadersSectionEnding($rawData);
			$result[0] = trim(substr($rawData, 0, $headerEnding));
			$body = trim(substr($rawData, $headerEnding));
			if ($body)
			{
				$result[1] =& $body;
			}
			return $result;
		}
		
		function _indexOfHeadersSectionEnding_old(&$rawData)
		{
			$len = strlen($rawData);
			$isHeader = false;
			$isLineEnd = false;
			
			for ($i = 0; $i < $len; $i++)
			{
				$char = $rawData{$i};
				if ($char == "\r")
				{
					if (isset($rawData{$i + 1}) && $rawData{$i + 1} == "\n") $i++;
					$isLineEnd = true;
				}
				elseif($char == "\n")
				{
					if (isset($rawData{$i + 1}) && $rawData{$i + 1} == "\r") $i++;					
					$isLineEnd = true;
				}
				else
				{
					$isHeader = true;
				}
				
				if ($isHeader && $isLineEnd)
				{
					$isHeader = false;
					$isLineEnd = false;
					continue;
				}
				
				if (!$isHeader && $isLineEnd)
				{
					return $i;
				}
			}
			return $len;
		}
		
		function _indexOfHeadersSectionEnding(&$rawData)
		{
			$add = 4;
			$result = strpos($rawData, "\r\n\r\n");
			if (false === $result)
			{
				$add = 2;
				$result = strpos($rawData, "\n\n");
				if (false === $result)
				{
					$result = strpos($rawData, "\r\r");
				}
			}

			return (false === $result) ? strlen($rawData) : $result + $add;
			
			$result = 0;
			$headerStartIndex = 0;
			$headerLength = 0;
			$headerEndIndex = 0;

			while(true)
			{
				$result = strpos($rawData, "\n", $headerStartIndex);
				if ($result !== false)
				{
					$headerEndIndex = $result;
					$headerLength = $headerEndIndex - $headerStartIndex;
					switch ($headerLength)
					{
						case 0:
							return $result;
							break;
						case 1:
							if ($result > 0)
							{
								if ($rawData{$result - 1} == "\r")
								{
									return $result;
								}
							}
							break;
					}
					$headerStartIndex = $result + 1;
				}
				else
				{
					return strlen($rawData);
				}
			}
			
			return strlen($rawData);
		}
		
		function Parse(&$rawData)
		{
			$parts =& $this->MailExplode($rawData);
			unset($rawData);
			
			$this->Headers = new HeaderCollection($parts[0]);
			$this->OriginalHeaders = $parts[0];
			
			/* charset parsing */
			$contentTypeHeader =& $this->Headers->GetHeaderByName(MIMEConst_ContentTypeLower);
			if ($contentTypeHeader != null)
			{
				$headerParameters = new HeaderParameterCollection($contentTypeHeader->Value);
				$param = $headerParameters->GetByName(MIMEConst_CharsetLower);
//				if ($param != null && strtolower($param->Value) !== 'us-ascii')
				if ($param != null)
				{
					$this->_sourceCharset = $param->Value;
					if (!isset($GLOBALS[MailInputCharset]) || $GLOBALS[MailInputCharset] === '')
					{
						$GLOBALS[MailInputCharset] = $param->Value;
					}
				}
				else
				{
					$this->_sourceCharset = $GLOBALS[MailDefaultCharset];
				}
			}
			else 
			{
				$this->_sourceCharset = $GLOBALS[MailDefaultCharset];
			}

			if (empty($this->_sourceCharset) && isset($GLOBALS[MailInputCharset]) && !empty($GLOBALS[MailInputCharset]))
			{
				$this->_sourceCharset = $GLOBALS[MailInputCharset];
			}
			
			if (count($parts) == 2)
			{
				$bound = $this->GetBoundary();
								
				if ($bound != '')
				{
					$mimePos = strpos($parts[1], '--'.$bound);
					$subParts = explode('--'.$bound, substr($parts[1], $mimePos));

					/* if (count($subParts]) > 2) */ 
					if (count($subParts) >= 2) /* fix for not closed boundary */
					{
						$this->_body = substr($parts[1], 0, $mimePos);
						$this->_subParts = new MimePartCollection($this);
						for ($i = 1, $c = count($subParts); $i < $c; $i++)
						{
							if ($subParts[$i] == '--' || !$subParts[$i])
							{
								continue;
							}
							$this->_subParts->Add(new MimePart($subParts[$i]));
						}
					}
					else
					{
						$this->_body = $parts[1];
					}
				}
				else
				{
					$this->_body = $parts[1];
				}
			}
		}
		
		/**
		 * @return string
		 */
		function GetBoundary()
		{
			$contentTypeHeader =& $this->Headers->GetHeaderByName(MIMEConst_ContentTypeLower);
			if ($contentTypeHeader != null)
			{
				$headerParameters = new HeaderParameterCollection($contentTypeHeader->Value);
				$param = $headerParameters->GetByName(MIMEConst_BoundaryLower);
				if ($param != null)
				{
					return $param->Value;
				}
			}

			return '';
		}

		/**
		 * @return string
		 */
		function SetEncodedBodyFromText($body, $charset = '', $sCustomContentTransferEncoding = null)
		{
			/* if ($charset == '') $charset = $GLOBALS[MailDefaultCharset]; */
			if ($charset === '' || $charset === null) 
			{
				$charset = $GLOBALS[MailInputCharset];
			}

			$body = preg_replace('/(<meta\s.*)(charset\s?=)([^"\'>\s]*)/i', '$1$2'.$GLOBALS[MailOutputCharset], $body);	

			$body = ConvertUtils::ConvertEncoding($body, $charset, $GLOBALS[MailOutputCharset]);

			$ContentTransferEncoding = null === $sCustomContentTransferEncoding ? 
				MIMEConst_QuotedPrintable : $sCustomContentTransferEncoding;
			
			$ContentTransferEncoding = strtolower($ContentTransferEncoding);
			
			if ($ContentTransferEncoding == MIMEConst_QuotedPrintableLower)
			{
				$this->Headers->SetHeaderByName(MIMEConst_ContentTransferEncoding, MIMEConst_QuotedPrintable);
				$this->_body = ConvertUtils::quotedPrintableWithLinebreak($body);
			}
			elseif($ContentTransferEncoding == MIMEConst_Base64Lower)
			{
				$this->Headers->SetHeaderByName(MIMEConst_ContentTransferEncoding, MIMEConst_Base64);
				$this->_body = ConvertUtils::base64WithLinebreak($body);
			}
			else 
			{
				if (!empty($sCustomContentTransferEncoding))
				{
					$this->Headers->SetHeaderByName(MIMEConst_ContentTransferEncoding, $sCustomContentTransferEncoding);
				}
				
				$this->_body = $body;
			}
		}
		
		/**
		 * @return string
		 */
		function ToString($withoutBcc = false)
		{
			$retval = $this->Headers->ToString($withoutBcc).CRLF;

			$retval .= $this->_body;
				
			if ($this->_subParts != null)
			{
				$retval .= $this->_subParts->ToString();
			}
			return $retval.CRLF;
		}
	}