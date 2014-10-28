<?php

class CApiMailMessage
{
	/**
	 * @var string
	 */
	protected $sFolder;

	/**
	 * @var int
	 */
	protected $iUid;

	/**
	 * @var string
	 */
	protected $sSubject;

	/**
	 * @var string
	 */
	protected $sMessageId;

	/**
	 * @var string
	 */
	protected $sContentType;

	/**
	 * @var int
	 */
	protected $iSize;

	/**
	 * @var int
	 */
	protected $iTextSize;

	/**
	 * @var int
	 */
	protected $iInternalTimeStampInUTC;

	/**
	 * @var int
	 */
	protected $iReceivedOrDateTimeStampInUTC;

	/**
	 * @var array
	 */
	protected $aFlags;

	/**
	 * @var array
	 */
	protected $aFlagsLowerCase;

	/**
	 * @var \MailSo\Mime\EmailCollection
	 */
	protected $oFrom;

	/**
	 * @var \MailSo\Mime\EmailCollection
	 */
	protected $oSender;

	/**
	 * @var \MailSo\Mime\EmailCollection
	 */
	protected $oReplyTo;

	/**
	 * @var \MailSo\Mime\EmailCollection
	 */
	protected $oTo;

	/**
	 * @var \MailSo\Mime\EmailCollection
	 */
	protected $oCc;

	/**
	 * @var \MailSo\Mime\EmailCollection
	 */
	protected $oBcc;

	/**
	 * @var string
	 */
	protected $sInReplyTo;

	/**
	 * @var string
	 */
	protected $sReferences;

	/**
	 * @var string
	 */
	protected $sPlain;

	/**
	 * @var string
	 */
	protected $sHtml;

	/**
	 * @var int
	 */
	protected $iSensitivity;

	/**
	 * @var int
	 */
	protected $iPriority;

	/**
	 * @var string
	 */
	protected $sReadingConfirmation;

	/**
	 * @var bool
	 */
	protected $bSafety;

	/**
	 * @var CApiMailAttachmentCollection
	 */
	protected $oAttachments;

	/**
	 * @var array
	 */
	private $aDraftInfo;

	/**
	 * @var array
	 */
	private $aExtend;

	/**
	 * @var array
	 */
	private $aCustom;

	/**
	 * @var array
	 */
	private $aThreads;

	/**
	 * @return void
	 */
	protected function __construct()
	{
		$this->Clear();
	}

	/**
	 * @return CApiMailMessage
	 */
	public function Clear()
	{
		$this->sFolder = '';
		$this->iUid = 0;
		$this->sSubject = '';
		$this->sMessageId = '';
		$this->sContentType = '';
		$this->iSize = 0;
		$this->iTextSize = 0;
		$this->iInternalTimeStampInUTC = 0;
		$this->iReceivedOrDateTimeStampInUTC = 0;
		$this->aFlags = array();
		$this->aFlagsLowerCase = array();

		$this->oFrom = null;
		$this->oSender = null;
		$this->oReplyTo = null;
		$this->oTo = null;
		$this->oCc = null;
		$this->oBcc = null;

		$this->sInReplyTo = '';
		$this->sReferences = '';

		$this->sHeaders = '';
		$this->sPlain = '';
		$this->sHtml = '';

		$this->iSensitivity = \MailSo\Mime\Enumerations\Sensitivity::NOTHING;
		$this->iPriority = \MailSo\Mime\Enumerations\MessagePriority::NORMAL;
		$this->bSafety = false;
		$this->sReadingConfirmation = '';

		$this->oAttachments = null;
		$this->aDraftInfo = null;
		$this->aExtend = null;
		$this->aCustom = array();

		$this->aThreads = array();

		return $this;
	}

	/**
	 * @return CApiMailMessage
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @return string
	 */
	public function Headers()
	{
		return $this->sHeaders;
	}

	/**
	 * @param string $sName
	 * @param mixed $mValue
	 *
	 * @return CApiMailMessage
	 */
	public function AddExtend($sName, $mValue)
	{
		if (!is_array($this->aExtend))
		{
			$this->aExtend = array();
		}

		$this->aExtend[$sName] = $mValue;
		
		return $this;
	}

	/**
	 * @param string $sName
	 *
	 * @return mixed
	 */
	public function GetExtend($sName)
	{
		return isset($this->aExtend[$sName]) ? $this->aExtend[$sName] : null;
	}

	/**
	 * @param string $sName
	 * @param mixed $mValue
	 *
	 * @return CApiMailMessage
	 */
	public function AddCustom($sName, $mValue)
	{
		$this->aCustom[$sName] = $mValue;

		return $this;
	}

	/**
	 * @return array
	 */
	public function Custom()
	{
		return $this->aCustom;
	}

	/**
	 * @return string
	 */
	public function Plain()
	{
		return $this->sPlain;
	}

	/**
	 * @return string
	 */
	public function Html()
	{
		return $this->sHtml;
	}

	/**
	 * @return string
	 */
	public function Folder()
	{
		return $this->sFolder;
	}

	/**
	 * @return int
	 */
	public function Uid()
	{
		return $this->iUid;
	}

	/**
	 * @return string
	 */
	public function MessageId()
	{
		return $this->sMessageId;
	}

	/**
	 * @return string
	 */
	public function Subject()
	{
		return $this->sSubject;
	}

	/**
	 * @return string
	 */
	public function ContentType()
	{
		return $this->sContentType;
	}

	/**
	 * @return int
	 */
	public function Size()
	{
		return $this->iSize;
	}

	/**
	 * @return int
	 */
	public function TextSize()
	{
		return $this->iTextSize;
	}

	/**
	 * @return int
	 */
	public function InternalTimeStampInUTC()
	{
		return $this->iInternalTimeStampInUTC;
	}

	/**
	 * @return int
	 */
	public function ReceivedOrDateTimeStampInUTC()
	{
		return $this->iReceivedOrDateTimeStampInUTC;
	}

	/**
	 * @return array
	 */
	public function Flags()
	{
		return $this->aFlags;
	}

	/**
	 * @return array
	 */
	public function FlagsLowerCase()
	{
		return $this->aFlagsLowerCase;
	}

	/**
	 * @return \MailSo\Mime\EmailCollection
	 */
	public function From()
	{
		return $this->oFrom;
	}

	/**
	 * @return \MailSo\Mime\EmailCollection
	 */
	public function Sender()
	{
		return $this->oSender;
	}

	/**
	 * @return \MailSo\Mime\EmailCollection
	 */
	public function ReplyTo()
	{
		return $this->oReplyTo;
	}

	/**
	 * @return \MailSo\Mime\EmailCollection
	 */
	public function To()
	{
		return $this->oTo;
	}

	/**
	 * @return \MailSo\Mime\EmailCollection
	 */
	public function Cc()
	{
		return $this->oCc;
	}

	/**
	 * @return \MailSo\Mime\EmailCollection
	 */
	public function Bcc()
	{
		return $this->oBcc;
	}

	/**
	 * @return string
	 */
	public function InReplyTo()
	{
		return $this->sInReplyTo;
	}

	/**
	 * @return string
	 */
	public function References()
	{
		return $this->sReferences;
	}

	/**
	 * @return int
	 */
	public function Sensitivity()
	{
		return $this->iSensitivity;
	}

	/**
	 * @return int
	 */
	public function Priority()
	{
		return $this->iPriority;
	}

	/**
	 * @return bool
	 */
	public function Safety()
	{
		return $this->bSafety;
	}

	/**
	 * @return string
	 */
	public function ReadingConfirmation()
	{
		return $this->sReadingConfirmation;
	}

	/**
	 * @param bool $bSafety
	 * @return void
	 */
	public function SetSafety($bSafety)
	{
		$this->bSafety = $bSafety;
	}

	/**
	 * @return CApiMailAttachmentCollection
	 */
	public function Attachments()
	{
		return $this->oAttachments;
	}

	/**
	 * @return array | null
	 */
	public function DraftInfo()
	{
		return $this->aDraftInfo;
	}

	/**
	 * @return array
	 */
	public function Threads()
	{
		return $this->aThreads;
	}

	/**
	 * @param array $aThreads
	 */
	public function SetThreads($aThreads)
	{
		$this->aThreads = \is_array($aThreads) ? $aThreads : array();
	}

	/**
	 * @param string $sRawFolderFullName
	 * @param \MailSo\Imap\FetchResponse $oFetchResponse
	 * @param \MailSo\Imap\BodyStructure $oBodyStructure = null
	 * @param array $aAscPartsIds = array()
	 *
	 * @return CApiMailMessage
	 */
	public static function NewFetchResponseInstance($sRawFolderFullName, $oFetchResponse, $oBodyStructure = null, $sRfc822SubMimeIndex = '', $aAscPartsIds = array())
	{
		return self::NewInstance()->InitByFetchResponse($sRawFolderFullName, $oFetchResponse, $oBodyStructure, $sRfc822SubMimeIndex, $aAscPartsIds);
	}

	/**
	 * @param string $sRawFolderFullName
	 * @param \MailSo\Imap\FetchResponse $oFetchResponse
	 * @param \MailSo\Imap\BodyStructure $oBodyStructure = null
	 * @param array $aAscPartsIds = array()
	 *
	 * @return CApiMailMessage
	 */
	public function InitByFetchResponse($sRawFolderFullName, $oFetchResponse, $oBodyStructure = null, $sRfc822SubMimeIndex = '', $aAscPartsIds = array())
	{
		if (!$oBodyStructure)
		{
			$oBodyStructure = $oFetchResponse->GetFetchBodyStructure();
		}

		$aTextParts = $oBodyStructure ? $oBodyStructure->SearchHtmlOrPlainParts() : array();

		$aICalPart = $oBodyStructure ? $oBodyStructure->SearchByContentType('text/calendar') : null;
		$oICalPart = is_array($aICalPart) && 0 < count($aICalPart) ? $aICalPart[0] : null;

		$aVCardPart = $oBodyStructure ? $oBodyStructure->SearchByContentType('text/vcard') : null;
		$aVCardPart = $aVCardPart ? $aVCardPart : ($oBodyStructure ? $oBodyStructure->SearchByContentType('text/x-vcard') : null);
		$oVCardPart = is_array($aVCardPart) && 0 < count($aVCardPart) ? $aVCardPart[0] : null;

		$sUid = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::UID);
		$sSize = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::RFC822_SIZE);
		$sInternalDate = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::INTERNALDATE);
		$aFlags = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::FLAGS);

		$this->sFolder = $sRawFolderFullName;
		$this->iUid = is_numeric($sUid) ? (int) $sUid : 0;
		$this->iSize = is_numeric($sSize) ? (int) $sSize : 0;
		$this->iTextSize = 0;
		$this->aFlags = is_array($aFlags) ? $aFlags : array();
		$this->aFlagsLowerCase = array_map('strtolower', $this->aFlags);

		$this->iInternalTimeStampInUTC =
			\MailSo\Base\DateTimeHelper::ParseInternalDateString($sInternalDate);

		if ($oICalPart)
		{
			$sICal = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::BODY.'['.$oICalPart->PartID().']');
			if (!empty($sICal))
			{
				$sICal = \MailSo\Base\Utils::DecodeEncodingValue($sICal, $oICalPart->MailEncodingName());
				$sICal = \MailSo\Base\Utils::ConvertEncoding($sICal,
					\MailSo\Base\Utils::NormalizeCharset($oICalPart->Charset(), true),
					\MailSo\Base\Enumerations\Charset::UTF_8);

				if (!empty($sICal) && false !== strpos($sICal, 'BEGIN:VCALENDAR'))
				{
					$sICal = preg_replace('/(.*)(BEGIN[:]VCALENDAR(.+)END[:]VCALENDAR)(.*)/ms', '$2', $sICal);
				}
				else
				{
					$sICal = '';
				}
				
				if (!empty($sICal))
				{
					$this->AddExtend('ICAL_RAW', $sICal);
				}
			}
		}

		if ($oVCardPart)
		{
			$sVCard = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::BODY.'['.$oVCardPart->PartID().']');
			if (!empty($sVCard))
			{
				$sVCard = \MailSo\Base\Utils::DecodeEncodingValue($sVCard, $oVCardPart->MailEncodingName());
				$sVCard = \MailSo\Base\Utils::ConvertEncoding($sVCard,
					\MailSo\Base\Utils::NormalizeCharset($oVCardPart->Charset(), true),
					\MailSo\Base\Enumerations\Charset::UTF_8);

				if (!empty($sVCard) && false !== strpos($sVCard, 'BEGIN:VCARD'))
				{
					$sVCard = preg_replace('/(.*)(BEGIN\:VCARD(.+)END\:VCARD)(.*)/ms', '$2', $sVCard);
				}
				else
				{
					$sVCard = '';
				}

				if (!empty($sVCard))
				{
					$this->AddExtend('VCARD_RAW', $sVCard);
				}
			}
		}

		$sCharset = $oBodyStructure ? $oBodyStructure->SearchCharset() : '';
		$sCharset = \MailSo\Base\Utils::NormalizeCharset($sCharset);

		$this->sHeaders = trim($oFetchResponse->GetHeaderFieldsValue($sRfc822SubMimeIndex));
		if (!empty($this->sHeaders))
		{
			$oHeaders = \MailSo\Mime\HeaderCollection::NewInstance()->Parse($this->sHeaders, false, $sCharset);

			$sContentTypeCharset = $oHeaders->ParameterValue(
				\MailSo\Mime\Enumerations\Header::CONTENT_TYPE,
				\MailSo\Mime\Enumerations\Parameter::CHARSET
			);

			if (!empty($sContentTypeCharset))
			{
				$sCharset = $sContentTypeCharset;
				$sCharset = \MailSo\Base\Utils::NormalizeCharset($sCharset);
			}

			if (!empty($sCharset))
			{
				$oHeaders->SetParentCharset($sCharset);
			}

			$bCharsetAutoDetect = 0 === \strlen($sCharset);

			$this->sSubject = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::SUBJECT, $bCharsetAutoDetect);
			$this->sMessageId = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::MESSAGE_ID);
			$this->sContentType = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::CONTENT_TYPE);

			$aReceived = $oHeaders->ValuesByName(\MailSo\Mime\Enumerations\Header::RECEIVED);
			$sReceived = !empty($aReceived[0]) ? trim($aReceived[0]) : '';

			$sDate = '';
			if (!empty($sReceived))
			{
				$aParts = explode(';', $sReceived);
				if (0 < count($aParts))
				{
					$aParts = array_reverse($aParts);
					foreach ($aParts as $sReceiveLine)
					{
						$sReceiveLine = trim($sReceiveLine);
						if (preg_match('/[\d]{4} [\d]{2}:[\d]{2}:[\d]{2} /', $sReceiveLine))
						{
							$sDate = $sReceiveLine;
							break;
						}
					}
				}
			}

			if (empty($sDate))
			{
				$sDate = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::DATE);
			}

			if (!empty($sDate))
			{
				$this->iReceivedOrDateTimeStampInUTC =
					\MailSo\Base\DateTimeHelper::ParseRFC2822DateString($sDate);
			}

			$this->oFrom = $oHeaders->GetAsEmailCollection(\MailSo\Mime\Enumerations\Header::FROM_, $bCharsetAutoDetect);
			$this->oTo = $oHeaders->GetAsEmailCollection(\MailSo\Mime\Enumerations\Header::TO_, $bCharsetAutoDetect);
			$this->oCc = $oHeaders->GetAsEmailCollection(\MailSo\Mime\Enumerations\Header::CC, $bCharsetAutoDetect);
			$this->oBcc = $oHeaders->GetAsEmailCollection(\MailSo\Mime\Enumerations\Header::BCC, $bCharsetAutoDetect);
			$this->oSender = $oHeaders->GetAsEmailCollection(\MailSo\Mime\Enumerations\Header::SENDER, $bCharsetAutoDetect);
			$this->oReplyTo = $oHeaders->GetAsEmailCollection(\MailSo\Mime\Enumerations\Header::REPLY_TO, $bCharsetAutoDetect);

			$this->sInReplyTo = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::IN_REPLY_TO);
			$this->sReferences = \preg_replace('/[\s]+/', ' ',
				$oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::REFERENCES));

			// Sensitivity
			$this->iSensitivity = \MailSo\Mime\Enumerations\Sensitivity::NOTHING;
			$sSensitivity = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::SENSITIVITY);
			switch (strtolower($sSensitivity))
			{
				case 'personal':
					$this->iSensitivity = \MailSo\Mime\Enumerations\Sensitivity::PERSONAL;
					break;
				case 'private':
					$this->iSensitivity = \MailSo\Mime\Enumerations\Sensitivity::PRIVATE_;
					break;
				case 'company-confidential':
					$this->iSensitivity = \MailSo\Mime\Enumerations\Sensitivity::CONFIDENTIAL;
					break;
			}

			// Priority
			$this->iPriority = \MailSo\Mime\Enumerations\MessagePriority::NORMAL;
			$sPriority = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::X_MSMAIL_PRIORITY);
			if (0 === strlen($sPriority))
			{
				$sPriority = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::IMPORTANCE);
			}
			if (0 === strlen($sPriority))
			{
				$sPriority = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::X_PRIORITY);
			}
			if (0 < strlen($sPriority))
			{
				switch (str_replace(' ', '', strtolower($sPriority)))
				{
					case 'high':
					case '1(highest)':
					case '2(high)':
					case '1':
					case '2':
						$this->iPriority = \MailSo\Mime\Enumerations\MessagePriority::HIGH;
						break;

					case 'low':
					case '4(low)':
					case '5(lowest)':
					case '4':
					case '5':
						$this->iPriority = \MailSo\Mime\Enumerations\MessagePriority::LOW;
						break;
				}
			}

			// ReadingConfirmation
			$this->sReadingConfirmation = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::DISPOSITION_NOTIFICATION_TO);
			if (0 === strlen($this->sReadingConfirmation))
			{
				$this->sReadingConfirmation = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::X_CONFIRM_READING_TO);
			}

			$this->sReadingConfirmation = trim($this->sReadingConfirmation);

			$sDraftInfo = $oHeaders->ValueByName(\MailSo\Mime\Enumerations\Header::X_DRAFT_INFO);
			if (0 < strlen($sDraftInfo))
			{
				$sType = '';
				$sFolder = '';
				$sUid = '';

				\MailSo\Mime\ParameterCollection::NewInstance($sDraftInfo)
					->ForeachList(function ($oParameter) use (&$sType, &$sFolder, &$sUid) {

						switch (strtolower($oParameter->Name()))
						{
							case 'type':
								$sType = $oParameter->Value();
								break;
							case 'uid':
								$sUid = $oParameter->Value();
								break;
							case 'folder':
								$sFolder = base64_decode($oParameter->Value());
								break;
						}
					})
				;

				if (0 < strlen($sType) && 0 < strlen($sFolder) && 0 < strlen($sUid))
				{
					$this->aDraftInfo = array($sType, $sUid, $sFolder);
				}
			}

			\CApi::Plugin()->RunHook('api-mail-message-headers-parse', array(&$this, $oHeaders));
		}

		if (is_array($aTextParts) && 0 < count($aTextParts))
		{
			if (0 === \strlen($sCharset))
			{
				$sCharset = \MailSo\Base\Enumerations\Charset::UTF_8;
			}
			
			$sHtmlParts = array();
			$sPlainParts = array();

			$iHtmlSize = 0;
			$iPlainSize = 0;
			foreach ($aTextParts as /* @var $oPart \MailSo\Imap\BodyStructure */ $oPart)
			{
				if ($oPart)
				{
					if ('text/html ' === $oPart->ContentType())
					{
						$iHtmlSize += $oPart->EstimatedSize();
					}
					else
					{
						$iPlainSize += $oPart->EstimatedSize();
					}
				}

				$sText = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::BODY.'['.$oPart->PartID().
					('' !== $sRfc822SubMimeIndex && is_numeric($sRfc822SubMimeIndex) ? '.1' : '').']');

//				if (null === $sText)
//				{
//					$sText = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::BODY.'['.$oPart->PartID().
//						('' !== $sRfc822SubMimeIndex && is_numeric($sRfc822SubMimeIndex) ? '.1' : '').']<0>');
//				}

				if (is_string($sText) && 0 < strlen($sText))
				{
					$sTextCharset = $oPart->Charset();
					if (empty($sTextCharset))
					{
						$sTextCharset = $sCharset;
					}

					$sTextCharset = \MailSo\Base\Utils::NormalizeCharset($sTextCharset, true);

					$sText = \MailSo\Base\Utils::DecodeEncodingValue($sText, $oPart->MailEncodingName());
					$sText = \MailSo\Base\Utils::ConvertEncoding($sText, $sTextCharset, \MailSo\Base\Enumerations\Charset::UTF_8);
					$sText = \MailSo\Base\Utils::Utf8Clear($sText);

					if ('text/html' === $oPart->ContentType())
					{
						$sHtmlParts[] = $sText;
					}
					else
					{
						$sPlainParts[] = $sText;
					}
				}
			}

			if (0 < count($sHtmlParts))
			{
				$this->sHtml = trim(implode('<br />', $sHtmlParts));
				$this->iTextSize = strlen($this->sHtml);
			}
			else
			{
				$this->sPlain = trim(implode("\n", $sPlainParts));
				$this->iTextSize = strlen($this->sPlain);
			}

			if (0 === $this->iTextSize)
			{
				$this->iTextSize = 0 < $iHtmlSize ? $iHtmlSize : $iPlainSize;
			}

			unset($sHtmlParts, $sPlainParts);
		}

		if ($oBodyStructure)
		{
			$aAttachmentsParts = $oBodyStructure->SearchAttachmentsParts();
			if ($aAttachmentsParts && 0 < count($aAttachmentsParts))
			{
				$this->oAttachments = CApiMailAttachmentCollection::NewInstance();
				foreach ($aAttachmentsParts as /* @var $oAttachmentItem \MailSo\Imap\BodyStructure */ $oAttachmentItem)
				{
					$this->oAttachments->Add(
						CApiMailAttachment::NewBodyStructureInstance($this->sFolder, $this->iUid, $oAttachmentItem)
					);
				}
				
				$this->oAttachments->ForeachList(function ($oAttachment) use ($aAscPartsIds, $oFetchResponse) {

					if ($oAttachment && in_array($oAttachment->MimeIndex(), $aAscPartsIds))
					{
						$mContent = $oFetchResponse->GetFetchValue(\MailSo\Imap\Enumerations\FetchType::BODY.'['.$oAttachment->MimeIndex().']');
						if (is_string($mContent))
						{
							$oAttachment->SetContent(
								\MailSo\Base\Utils::DecodeEncodingValue($mContent, $oAttachment->ContentTransferEncoding()));
						}
					}
				});
			}
		}

		\CApi::Plugin()->RunHook('api-mail-message-parse', array(&$this, $oFetchResponse, $oBodyStructure, $sRfc822SubMimeIndex));

		return $this;
	}
}
