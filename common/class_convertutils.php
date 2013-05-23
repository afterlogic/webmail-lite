<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	defined('WM_ROOTPATH') || define('WM_ROOTPATH', (dirname(__FILE__).'/../'));

	include_once(WM_ROOTPATH.'common/inc_constants.php');
	include_once(WM_ROOTPATH.'common/mime/inc_constants.php');
	include_once(WM_ROOTPATH.'common/class_datetime.php');

	define('GL_WITHIMG', 'imagesIsReplace');

	$aPrepearPlainStringUrls = array();

	function FindLinksInPlainTextCallback ($aMatch) {

		global $aPrepearPlainStringUrls;

		if (is_array($aMatch) && 6 < count($aMatch))
		{
			while (in_array($sChar = substr($aMatch[3], -1), array(']', ')')))
			{
				if (substr_count($aMatch[3], ']' === $sChar ? '[': '(') - substr_count($aMatch[3], $sChar) < 0)
				{
					$aMatch[3] = substr($aMatch[3], 0, -1);
					$aMatch[6] = (']' === $sChar ? ']': ')').$aMatch[6];
				}
				else
				{
					break;
				}
			}

			$sHrefPrefix = '';
			if (0 === strpos($aMatch[2].$aMatch[3], 'www.'))
			{
				$sHrefPrefix = 'http://';
			}

			$aPrepearPlainStringUrls[] =
				stripslashes('<a target="_blank" href="'.$sHrefPrefix.$aMatch[2].$aMatch[3].'">'.$aMatch[2].$aMatch[3].'</a>');

			return $aMatch[1].'@#@link{'.(count($aPrepearPlainStringUrls) - 1).'}link@#@'.$aMatch[6];
		}

		return '';

	}

	/**
	 * @static
	 */
	class ConvertUtils
	{
		/**
		 * @return bool
		 */
		public static function IsIE()
		{
			return (strpos(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 'MSIE') !== false);
		}

		/**
		 * @param string $byteSize
		 * @return string
		 */
		public static function GetFriendlySize($byteSize)
		{
			$size = ceil($byteSize / 1024);
			$mbSize = $size / 1024;
			if ($mbSize >= 100)
			{
				$size = ceil($mbSize*10/10).''.JS_LANG_Mb;
			}
			else if ($mbSize > 1)
			{
				$size = (ceil($mbSize*10)/10).''.JS_LANG_Mb;
			}
			else
			{
				$size = $size.''.JS_LANG_Kb;
			}

			return $size;
		}

		/**
		 * @param	string	$_str
		 * @return	string
		 */
		public static function ShowCRLF($_str)
		{
			return str_replace(array("\r", "\n", "\t"), array('\r', '\n', '\t'), $_str);
		}

		/**
		 * @param	string $version
		 * @return	string
		 */
		public static function ClearVersion($version)
		{
			return preg_replace('/[^0-9a-z]/', '', $version);
		}

		/**
		 * @return	string
		 */
		public static function GetJsVersion()
		{
			return ConvertUtils::ClearVersion(WMVERSION);
		}

		/**
		 * @param string $sText
		 * @return string
		 */
		public static function FindLinksInPlainText($sText)
		{
			global $aPrepearPlainStringUrls;

			$aPrepearPlainStringUrls = array();

			$sPattern = '/([\W]|^)((?:https?:\/\/)|(?:svn:\/\/)|(?:git:\/\/)|(?:s?ftps?:\/\/)|(?:www\.))((\S+?)(\\/)?)((?:&gt;)?|[^\w\=\\/;\(\)\[\]]*?)(?=<|\s|$)/im';
			$sText = preg_replace_callback($sPattern, 'FindLinksInPlainTextCallback', $sText);

			$sText = htmlspecialchars($sText);

			for ($i = 0, $c = count($aPrepearPlainStringUrls); $i < $c; $i++)
			{
				$sText = str_replace('@#@link{'.$i.'}link@#@', $aPrepearPlainStringUrls[$i], $sText);
			}

			return $sText;
		}

		/**
		 * @param string $_htmlBody
		 * @param bool $bCheckRtl = false
		 * @return string
		 */
		public static function AddHtmlTagToHtmlBody($_htmlBody, $bCheckRtl = false)
		{
			return '<html'.($bCheckRtl && self::IsHebUtf8($_htmlBody) ? ' dir="rtl"' : '').'><body>'.$_htmlBody.'</body></html>';
		}

		public static function SetLimits()
		{
			if (CApi::Plugin()->HookExist('webmail-set-limits'))
			{
				CApi::Plugin()->RunHook('webmail-set-limits');
			}
			else
			{
				@ini_set('memory_limit', MEMORYLIMIT);
				@set_time_limit(TIMELIMIT);
			}
		}

		/**
		 * @param	string	$_prefix
		 * @return	string
		 */
		public static function ClearPrefix($_prefix)
		{
			$_new = preg_replace('/[^a-z0-9_]/i', '_', $_prefix);
			if ($_new !== $_prefix)
			{
				$_new = preg_replace('/[_]+/', '_', $_new);
			}
			return $_new;
		}

		/**
		 * @param string $email
		 * @return array
		 */
		public static function ParseEmail($email)
		{
			$arr = explode('@', $email, 2);

			if (count($arr) == 2 && strlen($arr[0]) > 0 && strlen($arr[1]) > 0)
			{
				return $arr;
			}
			else
			{
				return false;
			}
		}

		/**
		 * @param string $sString
		 * @param string $sFromEncoding
		 * @param string $sToEncoding
		 * @return string
		 */
		public static function ConvertEncoding($sString, $sFromEncoding, $sToEncoding)
		{
			return api_Utils::ConvertEncoding($sString, $sFromEncoding, $sToEncoding);
		}

		/**
		 * @param string $utf8str
		 * @param int $strlen
		 * @return array
		 */
		public static function utf8chunk_split($utf8str, $strlen)
		{
			$start = 0;
			$textlen = $strlen;
			$out = array();
			while (true)
			{
				$Offset = 6;
				$Kod = @ord($utf8str{$start + $textlen}) >> $Offset;
				while ($Kod == 2)
				{
					$textlen--;
					$Kod = @ord($utf8str{$start + $textlen}) >> $Offset;
				}
				$temp = substr($utf8str,$start,$textlen);

				if (!$temp && $temp !== '0') break;
				$out[] = $temp;
				$start += $textlen;
			}
			return $out;
		}

		public static function utf8_substr($_str, $_from, $_len)
		{
			return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$_from.'}'.
						'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$_len.'}).*#s',
						'$1', $_str);
		}

		/**
		 * @param string $str
		 * @param int $strlen
		 * @return array
		 */
		public static function chunk_split($str, $strlen)
		{
			$str = chunk_split($str, $strlen);
			$temp = explode(CRLF, $str);
			if ($temp[count($temp)-1] === '') array_pop($temp);
			return $temp;
		}

		/**
		* @param string $sString
		* @return string
		*/
		public static function Base64Decode($sString)
		{
			$sResultString = base64_decode($sString, true);
			if (false === $sResultString)
			{
				$sString = str_replace(array(' ', "\r", "\n", "\t"), '', trim($sString));
				if (false !== strpos(trim(trim($sString), '='), '='))
				{
					$sString = preg_replace('/=([^=])/', '= $1', $sString);
					$aStrings = explode(' ', $sString);
					foreach ($aStrings as $iIndex => $sParts)
					{
						$aStrings[$iIndex] = base64_decode($sParts);
					}

					$sResultString = implode('', $aStrings);
				}
				else
				{
					$sResultString = base64_decode($sString);
				}
			}

			return $sResultString;
		}

		public static function DecodeBodyByType($body, $type)
		{
			switch (strtolower($type))
			{
				case 'quoted-printable':
					$body = quoted_printable_decode($body);
					break;
				case 'base64':
					$pos1 = strpos($body, '*');
					$pos2 = @strpos($body, '*', $pos1+1);
					if ($pos2 !== false)
					{
						$body = @substr($body, $pos2+1);
					}
					$body = self::Base64Decode($body);
					break;
				case 'x-uue':
					$body = ConvertUtils::UuDecode($body);
					break;
			}

			return $body;
		}

		public static function GetBodyStructureEncodeType($str)
		{
			$return = IMAP_BS_ENCODETYPE_NONE;
			switch (strtolower($str))
			{
				case 'base64':
					$return = IMAP_BS_ENCODETYPE_BASE64;
					break;
				case 'quoted-printable':
					$return = IMAP_BS_ENCODETYPE_QPRINTABLE;
					break;
				case 'x-uue':
					$return = IMAP_BS_ENCODETYPE_XUUE;
					break;
			}

			return $return;
		}

		public static function GetBodyStructureEncodeString($type)
		{
			$return = 'none';
			switch ($type)
			{
				case IMAP_BS_ENCODETYPE_BASE64:
					$return = 'base64';
					break;
				case IMAP_BS_ENCODETYPE_QPRINTABLE:
					$return = 'quoted-printable';
					break;
				case IMAP_BS_ENCODETYPE_XUUE:
					$return = 'x-uue';
					break;
			}

			return $return;
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function QuotedPrintableEncode($str)
		{
			if (function_exists('imap_8bit') && USE_IMAP)
			{
				return imap_8bit($str);
			}

			$hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
			$lines = preg_split("/(?:\r\n|\r|\n)/", $str);
			$output = $line = '';

			while (list(, $line) = each($lines))
			{
				$linlen = strlen($line);
				$newline = '';
				for($i = 0; $i < $linlen; $i++)
				{
					$c = substr($line, $i, 1);
					$dec = ord($c);
					if (($dec == 32) && ($i == ($linlen - 1)))
					{ // convert space at eol only
						$c = '=20';
					} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) )
					{ // always encode "\t", which is *not* required
						$c = '='.$hex[floor($dec/16)].$hex[floor($dec%16)];
					}
					$newline .= $c;
				} // end of for
				$output .= $newline.CRLF;
			}
			return trim($output);

		}

		/**
		 * @param string $string
		 * @return string
		 */
		public static function quotedPrintableWithLinebreak($string, $dontBreake = false)
		{
			$linelen = 0;
			$breaklen = 0;
			$encodecrlf = false;
			$hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
			$linebreak = ($dontBreake) ? '' : '='.CRLF;
			$len = strlen($string);
			$result = '';

			for($i = 0; $i < $len; $i++)
			{
				if ($linelen >= MIMEConst_LineLengthLimit)
				{ // break lines over 76 characters, and put special QP linebreak
					$linelen = $breaklen;
					$result.= $linebreak;
				}
				$c = ord($string{$i});
				if (($c==0x3d) || ($c>=0x80) || ($c<0x20))
				{ // in this case, we encode...
					if ((($c==0x0A) || ($c==0x0D)) && (!$encodecrlf))
					{ // but not for linebreaks
						$result.=chr($c);
						$linelen = 0;
						continue;
					}
					//$result.='='.str_pad(strtoupper(dechex($c)), 2, '0');
					$result .= '='.$hex[floor($c/16)].$hex[floor($c%16)];
					$linelen += 3;
					continue;
				}
				$result.=chr($c); // normal characters aren't encoded
				$linelen++;
			}

			return $result;
		}

		/**
		 * @param string $string
		 * @return string
		 */
		public static function base64WithLinebreak($string)
		{
			return chunk_split(base64_encode($string), MIMEConst_LineLengthLimit);
		}

		/**
		 * @param string $string
		 * @return string
		 */
		public static function UuEncode($string)
		{
			if (function_exists('convert_uuencode'))
			{
				return convert_uuencode($string);
			}

		    $u = 0;
		    $encoded = '';

		    while (($c = count($bytes = unpack('c*', substr($string, $u, 45)))) != false)
		    {
		        $u += 45;
		        $encoded .= pack('c', $c + 0x20);

		        while ($c % 3)
		        {
		            $bytes[++$c] = 0;
		        }

		        foreach (array_chunk($bytes, 3) as $b)
		        {
		            $b0 = ($b[0] & 0xFC) >> 2;
		            $b1 = (($b[0] & 0x03) << 4) + (($b[1] & 0xF0) >> 4);
		            $b2 = (($b[1] & 0x0F) << 2) + (($b[2] & 0xC0) >> 6);
		            $b3 = $b[2] & 0x3F;

		            $b0 = $b0 ? $b0 + 0x20 : 0x60;
		            $b1 = $b1 ? $b1 + 0x20 : 0x60;
		            $b2 = $b2 ? $b2 + 0x20 : 0x60;
		            $b3 = $b3 ? $b3 + 0x20 : 0x60;

		            $encoded .= pack('c*', $b0, $b1, $b2, $b3);
		        }

		        $encoded .= CRLF;
		    }

		    // Add termination characters
		    $encoded .= "\x60".CRLF;

		    return $encoded;
		}

		/**
		 * @param string $string
		 * @return string
		 */
		public static function UuDecode($string)
		{
			$string = trim($string);
			if (strtolower(substr($string, 0, 5)) == 'begin')
			{
				$string = substr($string, strpos($string, CRLF) + strlen(CRLF));
				$string = substr($string, 0, strlen($string)-3);
				$string = trim($string);
			}

			if (function_exists('convert_uudecode'))
			{
				return convert_uudecode($string);
			}

			if (strlen($string) < 8)
			{
				return ''; // The given parameter is not a valid uuencoded string
			}

			$decoded = '';
			foreach (explode("\n", $string) as $line)
			{
				$c = count($bytes = unpack('c*', substr(trim($line), 1)));

				while ($c % 4)
				{
					$bytes[++$c] = 0;
				}

				foreach (array_chunk($bytes, 4) as $b)
				 {
					$b0 = $b[0] == 0x60 ? 0 : $b[0] - 0x20;
					$b1 = $b[1] == 0x60 ? 0 : $b[1] - 0x20;
					$b2 = $b[2] == 0x60 ? 0 : $b[2] - 0x20;
					$b3 = $b[3] == 0x60 ? 0 : $b[3] - 0x20;

					$b0 <<= 2;
					$b0 |= ($b1 >> 4) & 0x03;
					$b1 <<= 4;
					$b1 |= ($b2 >> 2) & 0x0F;
					$b2 <<= 6;
					$b2 |= $b3 & 0x3F;

					$decoded .= pack('c*', $b0, $b1, $b2);
				}
			}

			return rtrim($decoded, "\0");
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function DecodeHeaderString($str, $fromCharset, $toCharset, $withSpecialParameters = false)
		{
			//CApi::LogObject($value, ELogLevel::Full, 'test-');

			$bool = false;

			$newStr = ($withSpecialParameters) ? ConvertUtils::WordExtensionsDecode($str) : $str;
			$bool = ($newStr != $str);

			$str = preg_replace('/\?=[\s]+=\?/m','?==?', $newStr);
			$str = str_replace("\r", '', str_replace("\t", '', str_replace("\n", '', $str)));
			$str = preg_replace('/\s+/',' ', $str);

			$encodeArray = ConvertUtils::SearchEncodedPlaces($str);

			$c = count($encodeArray);
			for ($i = 0; $i < $c; $i++)
			{
				$tempArr = ConvertUtils::DecodeString($encodeArray[$i], $fromCharset, $toCharset);
				$str = str_replace($encodeArray[$i], ConvertUtils::ConvertEncoding($tempArr[1], $tempArr[0], $toCharset), $str);
				$bool = true;
			}
			$str = preg_replace('/[;]([a-zA-Z])/', '; $1', $str);
			return ($bool) ? $str : ConvertUtils::ConvertEncoding($str, $fromCharset, $toCharset);
		}

		/**
		 * @param string $string
		 * @return array
		 */
		public static function SearchEncodedPlaces($string)
		{
			$match = array('');
			//preg_match_all('/=\?[^\?]+\?[Q|B]\?[^\?\n\r$]*(\?=|\n|\r|$)/i', $string, $match);
			preg_match_all('/=\?[^\?]+\?[Q|B]\?[^\?]*(\?=)/i', $string, $match);

			for ($i = 0, $c = count($match[0]); $i < $c; $i++)
			{
				$pos = @strpos($match[0][$i], '*');
				if ($pos !== false)
				{
					$match[0][$i][0] = substr($match[0][$i][0], 0, $pos);
				}
			}
			return $match[0];
		}

		/**
		 * @param string $str
		 * @return array // array[0] - charset, array[1] - string
		 */
		public static function DecodeString($str)
		{
			$out = array('', $str);
			if (substr(trim($str), 0, 2) == '=?')
			{
				$pos = strpos($str, '?', 2);
				$out[0] = substr($str, 2, $pos - 2);
				$encType = strtoupper($str{$pos+1});
				switch ($encType)
				{
					case 'Q':
						$str = str_replace('_', ' ', $str);
						$out[1] = quoted_printable_decode(substr($str, $pos + 3, strlen($str)-$pos-5));
						break;
					case 'B':
						$out[1] = base64_decode(substr($str, $pos + 3, strlen($str)-$pos-5));
						break;
				}
			}

			return $out;
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function WordExtensionsDecode($str)
		{
			$newArray = array();
			$match = array('');
			preg_match_all('/([\w]+\*[^=]{0,3})=([^;\t\n]+)[;]?/', $str, $match);
			$fpos = $spos = 0;
			if (count($match[0]) > 0)
			{
				$charArray = array();
				for ($i = 0, $c = count($match[0]); $i < $c; $i++)
				{
					$temp = array('n' => '', 's' => '');

					$temp['n'] = substr($match[1][$i], 0, strpos($match[1][$i], '*'));

					if ($match[1][$i]{strlen($match[1][$i])-1} == '*')
					{
						$fpos = strpos($match[2][$i], '\'');
						$spos = strpos($match[2][$i], '\'', $fpos+1);
					}
					else
					{
						$fpos = false;
					}
					if ($fpos !== false)
					{
						$charset = substr($match[2][$i], 0, $fpos);
						$lang = substr($match[2][$i], $fpos+1, $spos-$fpos-1);
						if ($charset)
						{
							$charArray[$temp['n']]['c'] = $charset;
						}
						if ($lang)
						{
							$charArray[$temp['n']]['l'] = $lang;
						}

						$temp['s'] = (isset($charArray[$temp['n']]['c']))
							? ConvertUtils::ConvertEncoding(urldecode(trim(substr($match[2][$i], $spos+1), '\'"')), $charArray[$temp['n']]['c'], $GLOBALS[MailOutputCharset])
							: urldecode(trim(substr($match[2][$i], $spos+1), '\'"'));
					}
					else
					{
						//$temp['s'] = urldecode(trim($match[2][$i],'\'"'));
						$temp['s'] = (isset($charArray[$temp['n']]['c']))
							? ConvertUtils::ConvertEncoding(urldecode(trim($match[2][$i],'\'"')), $charArray[$temp['n']]['c'], $GLOBALS[MailOutputCharset])
							: urldecode(trim($match[2][$i],'\'"'));
					}
					$newArray[] = $temp;
				}

				for ($i = 0, $c = count($match[0]); $i < $c; $i++)
				{
					$str = str_replace($match[0][$i], '', $str);
				}

				$newMass = array();

				for ($i = 0, $c = count($newArray); $i < $c; $i++)
				{
					if (isset($newMass[$newArray[$i]['n']]))
					{
						$newMass[$newArray[$i]['n']] .= $newArray[$i]['s'];
					}
					else
					{
						$newMass[$newArray[$i]['n']] = $newArray[$i]['s'];
					}
				}

				if (count($newMass) > 0)
				{
					$str = trim(trim($str), ';');
				}

				foreach ($newMass as $k => $v)
				{
					$str .= '; '.$k.'="'.$v.'"';
				}
				return trim($str);
			}

			return $str;
		}

		/**
		 * @param string $str
		 * @param string $toCharset
		 * @return array
		 */
		public static function EncodeString($str, $toCharset)
		{
			$outarray = array();
			/* $factor = (MIMEConst_DefaultQB == MIMEConst_QuotedPrintableShort) ? 0.35 : 0.7; */

			$outarray = (strtolower($toCharset) == 'utf-8')
				? ConvertUtils::SmartChunk($str, MIMEConst_DefaultQB, $toCharset, true)
				: ConvertUtils::SmartChunk($str, MIMEConst_DefaultQB, $toCharset, false);

			if (!ConvertUtils::IsLatin($str))
			{
				for ($i = 0, $c = count($outarray); $i < $c; $i++)
				{
					if (MIMEConst_DefaultQB == MIMEConst_QuotedPrintableShort)
					{
						$outarray[$i] = '=?'.strtolower($toCharset).'?Q?'.str_replace('?', '=3F', str_replace(' ','_', str_replace('_', '=5F', ConvertUtils::quotedPrintableWithLinebreak($outarray[$i], true)))).'?=';
					}
					else if (MIMEConst_DefaultQB == MIMEConst_Base64Short)
					{
						$outarray[$i] = '=?'.strtolower($toCharset).'?B?'.base64_encode($outarray[$i]).'?=';
					}
				}
			}
			return $outarray;
		}

		/**
		 * @param string $str
		 * @param string $transferEncoding
		 * @param string $toCharset
		 * @param unknown_type $isUtf8
		 * @return array
		 */
		public static function SmartChunk($str, $transferEncoding, $toCharset, $isUtf8 = false)
		{
			$outArray = array();
			if ($isUtf8)
			{
				$offset = 6;
				$count = 5;
				$newstr = '';
				for ($i = 0, $c = strlen($str); $i < $c; $i++)
				{
					$ch = ord($str{$i});
					$count += (($ch >= 0x80) || ($ch < 0x20)) ? ($transferEncoding == MIMEConst_Base64Short) ? 2 : 3 : 1;
					$newstr .= $str{$i};

					if ($count >= MIMEConst_LineLengthLimit - strlen($toCharset) - 7)
					{
						if ($str{$i} == ' ')
						{
							$outArray[] = $newstr;
							$count = 0;
							$newstr = '';
						}
					}
				}

				if (strlen($newstr) > 0)
				{
					$outArray[] = $newstr;
				}
			}
			else
			{
				$count = 5;
				$newstr = '';
				for ($i = 0, $c = strlen($str); $i < $c; $i++)
				{
					$ch = ord($str{$i});
					$count += (($ch >= 0x80) || ($ch < 0x20)) ? ($transferEncoding == MIMEConst_Base64Short) ? 2 : 3 : 1;
					$newstr .= $str{$i};

					if ($count >= MIMEConst_LineLengthLimit - strlen($toCharset) - 7)
					{
						if ($str{$i} == ' ')
						{
							$outArray[] = $newstr;
							$count = 0;
							$newstr = '';
						}
					}
				}

				if (strlen($newstr) > 0)
				{
					$outArray[] = $newstr;
				}
			}
			return $outArray;
		}

		/**
		 * @param string $str
		 * @param string $fromCharset
		 * @param string $toCharset
		 * @param bool $changeCharset
		 * @return string
		 */
		public static function EncodeHeaderString($str, $fromCharset, $toCharset, $changeCharset = true)
		{
			$out = '';
			if ($changeCharset)
			{
				$str = ConvertUtils::ConvertEncoding($str, $fromCharset, $toCharset);
			}

			$array = ConvertUtils::EncodeString($str, $toCharset);
			for ($i = 0, $c = count($array); $i < $c; $i++)
			{
				if ($i > 0)
				{
					if (strlen($array[$i]) > 0)
					{
						$out .= $array[$i]{0} == ' ' ? CRLF.$array[$i] : CRLF."\t".$array[$i];
					}
				}
				else
				{
					$out .= $array[$i];
				}
			}
			return trim($out);
		}

		/**
		 * @param string $value
		 * @return bool
		 */
		public static function IsLatin($value)
		{
			return !preg_match('/[^\x09\x10\x13\x0A\x0D\x20-\x7E]/', $value);
		}

		/**
		 * @param string $value
		 * @return bool
		 */
		public static function IsHebUtf8($value)
		{
			return 0 < (int) @preg_match('/[\p{Hebrew}]/u', $value);
		}

		/**
		 * @param int $codePageNum
		 * @return string
		 */
		public static function GetCodePageName($codePageNum)
		{
			static $mapping = array(
						51936 => 'euc-cn',
						936 => 'gb2312',
						950 => 'big5',
						946 => 'euc-kr',
						50225 => 'iso-2022-kr',
						50220 => 'iso-2022-jp',
						932 => 'shift-jis',
						65000 => 'utf-7',
						65001 => 'utf-8',
						1250 => 'windows-1250',
						1251 => 'windows-1251',
						1252 => 'windows-1252',
						1253 => 'windows-1253',
						1254 => 'windows-1254',
						1255 => 'windows-1255',
						1256 => 'windows-1256',
						1257 => 'windows-1257',
						1258 => 'windows-1258',
						20866 => 'koi8-r',
						28591 => 'iso-8859-1',
						28592 => 'iso-8859-2',
						28593 => 'iso-8859-3',
						28594 => 'iso-8859-4',
						28595 => 'iso-8859-5',
						28596 => 'iso-8859-6',
						28597 => 'iso-8859-7',
						28598 => 'iso-8859-8');

			if (isset($mapping[$codePageNum]))
			{
				return $mapping[$codePageNum];
			}
			return '';
		}

		/**
		 * @param string $codePageName
		 * @return int
		 */
		public static function GetCodePageNumber($codePageName)
		{
			static $mapping = array(
						'euc-cn' => 51936,
						'gb2312' => 936,
						'big5' => 950,
						'euc-kr' => 949,
						'iso-2022-kr' => 50225,
						'iso-2022-jp' => 50220,
						'shift-jis' => 932,
						'utf-7' => 65000,
						'utf-8' => 65001,
						'windows-1250' => 1250,
						'windows-1251' => 1251,
						'windows-1252' => 1252,
						'windows-1253' => 1253,
						'windows-1254' => 1254,
						'windows-1255' => 1255,
						'windows-1256' => 1256,
						'windows-1257' => 1257,
						'windows-1258' => 1258,
						'koi8-r' => 20866,
						'iso-8859-1' => 28591,
						'iso-8859-2' => 28592,
						'iso-8859-3' => 28593,
						'iso-8859-4' => 28594,
						'iso-8859-5' => 28595,
						'iso-8859-6' => 28596,
						'iso-8859-7' => 28597,
						'iso-8859-8' => 28598);

			if (isset($mapping[$codePageName]))
			{
				return $mapping[$codePageName];
			}
			return 0;
		}

		/**
		 * @param string $filename
		 * @return string
		 */
		public static function GetContentTypeFromFileName($filename)
		{
			return api_Utils::MimeContentType($filename);
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function WMHtmlSpecialChars($str)
		{
			return str_replace('>', '&gt;', str_replace('<', '&lt;', str_replace('&', '&amp;', $str)));
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function WMBackHtmlSpecialChars($str)
		{
			return str_replace('&gt;', '>', str_replace('&lt;', '<', str_replace('&amp;', '&', $str)));
			// return str_replace('&amp;', '&', str_replace('&lt;', '<', str_replace('&gt;', '>', $str)));
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function WMHtmlNewCode($str)
		{
			return str_replace(']]>','&#93;&#93;&gt;', $str);
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function WMBackHtmlNewCode($str)
		{
			return str_replace('&#93;&#93;&gt;', ']]>', $str);
		}

		/**
		 * @param string $str
		 * @param bool $isQuote
		 * @return string
		 */
		public static function AttributeQuote($str, $isQuote = true)
		{
			return ($isQuote) ? str_replace('"', '&quot;', $str) : str_replace('\'', '&#039;', $str);
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function ClearUtf8($str)
		{
			return (true)
				? ConvertUtils::mainClear($str)
				: ConvertUtils::ClearUtf8Long($str);
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function ClearUtf8Long($str)
		{
			if (IS_SUPPORT_ICONV)
			{
				$str = @iconv('UTF-8', 'UTF-8//IGNORE', $str);
			}

			$matches = array();
			$replace = '?';
			$UTF8_BAD =
				'([\x00-\x7F]'.                          # ASCII (including control chars)
				'|[\xC2-\xDF][\x80-\xBF]'.               # non-overlong 2-byte
				'|\xE0[\xA0-\xBF][\x80-\xBF]'.           # excluding overlongs
				'|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'.    # straight 3-byte
				'|\xED[\x80-\x9F][\x80-\xBF]'.           # excluding surrogates
				'|\xF0[\x90-\xBF][\x80-\xBF]{2}'.        # planes 1-3
				'|[\xF1-\xF3][\x80-\xBF]{3}'.            # planes 4-15
				'|\xF4[\x80-\x8F][\x80-\xBF]{2}'.        # plane 16
				'|(.{1}))';                              # invalid byte
			ob_start();
			while (preg_match('/'.$UTF8_BAD.'/S', $str, $matches))
			{
				echo (!isset($matches[2])) ? $matches[0] : $replace;
				$str = substr($str, strlen($matches[0]));
			}
			$result = @ob_get_contents();
			@ob_end_clean();

			return $result;
		}

		/**
		 * @param string $str
		 * @return string
		 */
		public static function miniClear($str)
		{
			return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
		}

		/**
		 * @param string $sUtf8String
		 * @return string
		 */
		public static function mainClear($sUtf8String)
		{
			if (IS_SUPPORT_ICONV)
			{
				$sUtf8String = @iconv('UTF-8', 'UTF-8//IGNORE', $sUtf8String);
			}

			$sUtf8String = preg_replace(
				'/[\x00-\x08\x10\x0B\x0C\x0E-\x1F\x7F]'.
				'|[\x00-\x7F][\x80-\xBF]+'.
				'|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
				'|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
				'|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
				'', $sUtf8String);

			$sUtf8String = preg_replace(
				'/\xE0[\x80-\x9F][\x80-\xBF]'.
				'|\xED[\xA0-\xBF][\x80-\xBF]/S', '', $sUtf8String);

			return $sUtf8String;
		}

		/**
		 * @param string $password
		 * @return string
		 */
		public static function EncodePassword($password)
		{
			if ($password === '')
			{
				return $password;
			}

			$plainBytes = $password;

			$encodeByte = $plainBytes{0};
			$result = bin2hex($encodeByte);

			for ($i = 1, $icount = strlen($plainBytes); $i < $icount; $i++)
			{
				$plainBytes{$i} = ($plainBytes{$i} ^ $encodeByte);
				$result .= bin2hex($plainBytes{$i});
			}

            return $result;
		}

		/**
		 * @param string $password
		 * @return string
		 */
		public static function DecodePassword($password)
		{
			$result = '';
			$passwordLen = strlen($password);

			if (strlen($password) > 0 && strlen($password) % 2 == 0)
			{
				$decodeByte = chr(hexdec(substr($password, 0, 2)));
				$plainBytes = $decodeByte;
				$startIndex = 2;
				$currentByte = 1;

				do
				{
					$hexByte = substr($password, $startIndex, 2);
					$plainBytes .= (chr(hexdec($hexByte)) ^ $decodeByte);

					$startIndex += 2;
					$currentByte++;
				}
				while ($startIndex < $passwordLen);

				$result = $plainBytes;
			}
			return $result;
		}

		/**
		 * @staticvar DateTimeZone $oDateTimeZone
		 *
		 * @return DateTimeZone
		 */
		private static function getUtcTimeZoneObject()
		{
			static $oDateTimeZone = null;
			if (null === $oDateTimeZone)
			{
				$oDateTimeZone = new DateTimeZone('UTC');
			}
			return $oDateTimeZone;
		}

		/**
		 * Parse date string formated as "Thu, 10 Jun 2010 08:58:33 -0700 (PDT)"
		 * RFC2822
		 *
		 * @param string $sDateTime
		 *
		 * @return int
		 */
		public static function ParseRFC2822DateStringOld($sDateTime)
		{
			$sDateTime = preg_replace('/ \([a-zA-Z0-9]+\)$/', '', trim($sDateTime));
			$oDateTime = DateTime::createFromFormat('D, d M Y H:i:s O', $sDateTime, ConvertUtils::getUtcTimeZoneObject());
			return $oDateTime ? $oDateTime->getTimestamp() : 0;
		}

		/**
		 * Parse date string formated as "Thu, 10 Jun 2010 08:58:33 -0700 (PDT)"
		 * RFC2822
		 *
		 * @param string $sDateTime
		 *
		 * @return int
		 */
		public static function ParseRFC2822DateString($sDateTime)
		{
			$matches = array();
			$zone = null;
			$off = null;

			$datePattern = '/^(([a-z]*),[\s]*){0,1}(\d{1,2}).([a-z]*).(\d{2,4})[\s]*(\d{1,2}).(\d{1,2}).(\d{1,2})([\s]+([+-]?\d{1,4}))?([\s]*(\(?(\w+)\)?))?/i';

			$dt = 0;
			if (preg_match($datePattern, $sDateTime, $matches))
			{
				$year = $matches[5];
				$month = ConvertUtils::GetMonthIndex(strtolower($matches[4]));
				if ($month == -1) $month = 1;
				$day = $matches[3];
				$hour = $matches[6];
				$minute = $matches[7];
				$second = $matches[8];

				$dt = gmmktime($hour, $minute, $second, $month, $day, $year);
				if (isset($matches[13]))
				{
					$zone = strtolower($matches[13]);
				}
				if (isset($matches[10]))
				{
					$off = strtolower($matches[10]);
				}

				$dt = ConvertUtils::ApplyOffsetForDate($dt, $off, $zone);
			}

			return $dt;
		}

		/**
		 * @param string $month
		 * @return int
		 */
		public static function GetMonthIndex($month)
		{
			switch (strtolower($month))
			{
				case 'jan':	return 1;
				case 'feb':	return 2;
				case 'mar':	return 3;
				case 'apr':	return 4;
				case 'may':	return 5;
				case 'jun':	return 6;
				case 'jul':	return 7;
				case 'aug':	return 8;
				case 'sep':	return 9;
				case 'oct':	return 10;
				case 'nov':	return 11;
				case 'dec':	return 12;
				default:	return -1;
			}
		}

		/**
		 * @param int $dt
		 * @param int $offset
		 * @param string $zone
		 * @return int
		 */
		public static function ApplyOffsetForDate($dt, $offset, $zone)
		{
			$result = $dt;

			if (strlen($offset) != 0)
			{
				$result -= api_Utils::GetTimeOffsetFromHoursString($offset);
			}
			else if (($zone != null) && (strlen($zone) != 0))
			{
				$zone = trim($zone, ' ()');

				switch($zone)
				{
					case 'ut':
					case 'gmt':
					case 'z':
					{
						break;
					}
					case 'est':
					case 'cdt':
					{
						$dt -= 5*60*60;
						break;
					}
					case 'edt':
					{
						$dt -= 4*60*60;
						break;
					}
					case 'cst':
					case 'mdt':
					{
						$dt -= 6*60*60;
						break;
					}
					case 'mst':
					case 'pdt':
					{
						$dt -= 7*60*60;
						break;
					}
					case 'pst':
					{
						$dt -= 8*60*60;
						break;
					}
				}
			}

			return $result;
		}

		/**
		 * @param string $string
		 * @return string
		 */
		public static function ReplaceJSMethod($string)
		{
			/*
			$ToReplaceArray = array (
				"'onActivate'si",
				"'onAfterPrint'si",
				"'onBeforePrint'si",
				"'onAfterUpdate'si",
				"'onBeforeUpdate'si",
				"'onErrorUpdate'si",
				"'onAbort'si",
				"'onBeforeDeactivate'si",
				"'onDeactivate'si",
				"'onBeforeCopy'si",
				"'onBeforeCut'si",
				"'onBeforeEditFocus'si",
				"'onBeforePaste'si",
				"'onBeforeUnload'si",
				"'onBlur'si",
				"'onBounce'si",
				"'onChange'si",
				"'onClick'si",
				"'onControlSelect'si",
				"'onCopy'si",
				"'onCut'si",
				"'onDblClick'si",
				"'onDrag'si",
				"'onDragEnter'si",
				"'onDragLeave'si",
				"'onDragOver'si",
				"'onDragStart'si",
				"'onDrop'si",
				"'onFilterChange'si",
				"'onDragDrop'si",
				"'onError'si",
				"'onFilterChange'si",
				"'onFinish'si",
				"'onFocus'si",
				"'onHelp'si",
				"'onKeyDown'si",
				"'onKeyPress'si",
				"'onKeyUp'si",
				"'onLoad'si",
				"'onLoseCapture'si",
				"'onMouseDown'si",
				"'onMouseEnter'si",
				"'onMouseLeave'si",
				"'onMouseMove'si",
				"'onMouseOut'si",
				"'onMouseOver'si",
				"'onMouseUp'si",
				"'onMove'si",
				"'onPaste'si",
				"'onPropertyChange'si",
				"'onReadyStateChange'si",
				"'onReset'si",
				"'onResize'si",
				"'onResizeEnd'si",
				"'onResizeStart'si",
				"'onScroll'si",
				"'onSelectStart'si",
				"'onSelect'si",
				"'onSelectionChange'si",
				"'onStart'si",
				"'onStop'si",
				"'onSubmit'si",
				"'onUnload'si");
			*/
			$ToReplaceArray = array (
				"'onBlur'si",
				"'onChange'si",
				"'onClick'si",
				"'onDblClick'si",
				"'onError'si",
				"'onFocus'si",
				"'onKeyDown'si",
				"'onKeyPress'si",
				"'onKeyUp'si",
				"'onLoad'si",
				"'onMouseDown'si",
				"'onMouseEnter'si",
				"'onMouseLeave'si",
				"'onMouseMove'si",
				"'onMouseOut'si",
				"'onMouseOver'si",
				"'onMouseUp'si",
				"'onMove'si",
				"'onResize'si",
				"'onResizeEnd'si",
				"'onResizeStart'si",
				"'onScroll'si",
				"'onSelect'si",
				"'onSubmit'si",
				"'onUnload'si");

				return preg_replace($ToReplaceArray, "X_\$0", $string);

		}

		/**
		 * @param string $strFileName
		 * @return string
		 */
		public static function ClearFileName($strFileName)
		{
			return str_replace(array('"', '/', '\\', '*','?', '<', '>', '|', ':', "\r", "\n", "\t"), '', $strFileName);
		}

		/**
		 * @param string $strFileName
		 * @return bool
		 */
		public static function CheckFileName($strFileName)
		{
			if (strpos($strFileName, '"') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, '/') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, '\\') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, '*') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, '?') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, '<') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, '>') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, '|') !== false)
			{
				return false;
			}
			elseif (strpos($strFileName, ':') !== false)
			{
				return false;
			}
			return true;
		}

		/**
		 * @param string $strFileName
		 * @return bool
		 */
		public static function CheckDefaultWordsFileName($strFileName)
		{
			$words = array('CON', 'AUX', 'COM1', 'COM2', 'COM3', 'COM4', 'LPT1', 'LPT2', 'LPT3', 'PRN', 'NUL');
			foreach ($words as $value)
			{
				if (strtoupper($strFileName) == $value)
				{
					return false;
				}
			}
			return true;
		}

		/**
		 * @param string $strHtmlContent
		 * @return string
		 */
		public static function HtmlBodyWithoutImages($strHtmlContent)
		{
			if (30000 < strlen($strHtmlContent))
			{
				ini_set('pcre.backtrack_limit', 1000000); // #400
			}

			// #703
//			$strHtmlContent = preg_replace_callback('/<[^>]+(background)/im', 'matches3Replace', $strHtmlContent);
			$strHtmlContent = preg_replace_callback('/<[^>]+(background)([^\-\s\>]+)/im', 'matches3Replace', $strHtmlContent);
			$strHtmlContent = preg_replace_callback('/<[^>]+(src)([^\s>]+)/im', 'matches1Replace', $strHtmlContent);
			$strHtmlContent = preg_replace_callback('/<[^>]+(url\([^)]+)/im', 'matches2Replace', $strHtmlContent);
			return $strHtmlContent;
		}

		/**
		 * @param string $strHtmlContent
		 * @return string
		 */
		public static function BackImagesToHtmlBody($strHtmlContent)
		{
			return str_replace(array('wmx_src', 'wmx_url('), array('src', 'url('), $strHtmlContent);
		}

		public static function AddToLinkMailToCheck($html)
		{
			$html = str_replace('<a ', '<a onclick="return checkLinkHref(this.href);" ', $html);
			return $html;
		}

		/**
		 * @param string $strPass
		 * @return string
		 */
		public static function WmServerCrypt($strPassword)
		{
			$out = '';
			for ($i = 0, $c = strlen($strPassword); $i < $c; $i++)
			{
				$out .= sprintf("%02x", (ord($strPassword{$i}) ^ 101) & 0xff);
			}
			return $out;
		}

		/**
		 * @param string $strPass
		 * @return string
		 */
		public static function WmServerDeCrypt($strPassword)
		{
			$return = '';
			$len = strlen($strPassword);

			if ($len > 0 && $len % 2 == 0)
			{
				$startIndex = 0;
				while($startIndex < $len)
				{
					$temp = (int) hexdec(substr($strPassword, $startIndex, 2));
					$return .= chr(($temp & 0xFF) ^ 101);
					$startIndex += 2;
				}
			}

			return $return;
		}

		/**
		 * @param string $jsString
		 * @return string
		 */
		public static function ClearJavaScriptString($jsString, $deq = null)
		{
			$jsString = str_replace('\\', '\\\\', $jsString);
			if ($deq !== null && strlen($deq) == 1)
			{
				$jsString = str_replace($deq, '\\'.$deq, $jsString);
			}

			$jsString = str_replace(array("\r", "\n"), ' ', trim($jsString));
			return str_replace(array('</script>'), '<\/script>', $jsString);
		}

		/**
		 * @param string $jsString
		 * @return string
		 */
		public static function ReBuildStringToJavaScript($jsString, $deq = null)
		{
			$jsString = str_replace('\\', '\\\\', $jsString);
			if ($deq !== null && strlen($deq) == 1)
			{
				$jsString = str_replace($deq, '\\'.$deq, $jsString);
			}

			$jsString = str_replace(array("\r", "\n", "\t"), array('\r', '\n', '\t'), trim($jsString));
			return str_replace(array('</script>'), '<\/script>', $jsString);
		}

		public static function GetIMAPFilterSearchCri($iFilter = APP_MESSAGE_LIST_FILTER_NONE)
		{
			$sAddSearchCri = '';
			switch ($iFilter)
			{
				case APP_MESSAGE_LIST_FILTER_UNSEEN:
					$sAddSearchCri = 'UNSEEN';
					break;
				case APP_MESSAGE_LIST_FILTER_WITH_ATTACHMENTS:
					$sAddSearchCri = 'HEADER CONTENT-TYPE "MULTIPART/MIXED"';
					break;
			}

			return $sAddSearchCri;
		}

		/**
		 * @param string $pabUri
		 * @return array
		 */
		public static function LdapUriParse($pabUri)
		{
			$return  = array(
				'host' => '127.0.0.1',
				'port' => 389,
				'search_dn' => '',
			);

			$pabUriLower = strtolower($pabUri);
			if ('ldap://' === substr($pabUriLower, 0, 7))
			{
				$pabUriLower = substr($pabUriLower, 7);
			}

			$pabUriLowerExplode = explode('/', $pabUriLower, 2);
			$return['search_dn'] = isset($pabUriLowerExplode[1]) ? $pabUriLowerExplode[1] : '';

			if (isset($pabUriLowerExplode[0]))
			{
				$pabUriLowerHostPortExplode = explode(':', $pabUriLowerExplode[0], 2);
				$return['host'] = isset($pabUriLowerHostPortExplode[0]) ? $pabUriLowerHostPortExplode[0] : $return['host'];
				$return['port'] = isset($pabUriLowerHostPortExplode[1]) ? (int) $pabUriLowerHostPortExplode[1] : $return['port'];
			}

			return $return;
		}

		/**
		 * @param array $array
		 * @return array
		 */
		public static function SortAccoutArray($array)
		{
			$return = array();
			foreach ($array as $key => $value)
			{
				/* $arr = explode('@', $value[4], 2); */
				$return[$key] = $value[4];
			}

			asort($return);

			foreach ($return as $key => $value)
			{
				$return[$key] = $array[$key];
			}

			return $return;
		}
	}

	/**
	 * @param array $matches
	 * @return array
	 */
	function matches1Replace($matches)
	{
		if (count($matches) > 2 && false === strpos($matches[2], 'attach.php'))
		{
			$GLOBALS[GL_WITHIMG] = true;
			return preg_replace('/<([^>]+)src/im', '<\\1wmx_src', $matches[0]);
		}
		return $matches[0];
	}

	/**
	 * @param array $matches
	 * @return array
	 */
	function matches2Replace($matches)
	{
		if (count($matches) > 1 && false === strpos($matches[1], 'attach.php'))
		{
			$GLOBALS[GL_WITHIMG] = true;
			return preg_replace('/url\(/im', 'wmx_url(', $matches[0]);
		}
		return $matches[0];
	}

	/**
	 * @param array $matches
	 * @return array
	 */
	function matches3Replace($matches)
	{
		if (count($matches) > 2 && false === strpos($matches[2], 'attach.php'))
		{
			$GLOBALS[GL_WITHIMG] = true;
			return preg_replace('/\sbackground/im', ' wmx_background', $matches[0]);
		}
		return $matches[0];
	}
