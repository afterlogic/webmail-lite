<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace ProjectCore;

/**
 * @category ProjectCore
 */
abstract class ActionsBase
{
	/**
	 * @var \MailSo\Base\Http
	 */
	protected $oHttp;

	/**
	 * @var array
	 */
	protected $aCurrentActionParams = array();

	/**
	 * @var \CApiContactsManager
	 */
	private $oApiContacts = null;

	/**
	 * @var \CApiGcontactsManager
	 */
	private $oApiGcontacts = null;

	/**
	 * @return \CApiEcontactsManager
	 */
	public function ApiContacts()
	{
		if (null === $this->oApiContacts)
		{
			$this->oApiContacts = \CApi::Manager('contacts');
		}

		return $this->oApiContacts;
	}

	/**
	 * @return \CApiGcontactsManager
	 */
	public function ApiGContacts()
	{
		if (null === $this->oApiGcontacts )
		{
			$this->oApiGcontacts  = \CApi::Manager('gcontacts');
		}

		return $this->oApiGcontacts;
	}

	/**
	 * @param \CAccount $oAccount
	 * @param string $sActionName
	 * @param mixed $mResult = false
	 *
	 * @return array
	 */
	public function DefaultResponse($oAccount, $sActionName, $mResult = false)
	{
		$sActionName = 'Ajax' === substr($sActionName, 0, 4)
			? substr($sActionName, 4) : $sActionName;

		$aResult = array('Action' => $sActionName);
		if ($oAccount instanceof \CAccount)
		{
			$aResult['AccountID'] = $oAccount->IdAccount;
		}

		$aResult['Result'] = $this->responseObject($oAccount, $mResult, $sActionName);
		$aResult['@Time'] = microtime(true) - PSEVEN_APP_START;
		return $aResult;
	}

	/**
	 * @param \CAccount $oAccount
	 * @param string $sActionName
	 *
	 * @return array
	 */
	public function TrueResponse($oAccount, $sActionName)
	{
		return $this->DefaultResponse($oAccount, $sActionName, true);
	}

	/**
	 * @param \CAccount $oAccount
	 * @param string $sActionName
	 * @param int $iErrorCode
	 * @param string $sErrorMessage
	 * @param array $aAdditionalParams = null
	 *
	 * @return array
	 */
	public function FalseResponse($oAccount, $sActionName, $iErrorCode = null, $sErrorMessage = null, $aAdditionalParams = null)
	{
		$aResponseItem = $this->DefaultResponse($oAccount, $sActionName, false);

		if (null !== $iErrorCode)
		{
			$aResponseItem['ErrorCode'] = (int) $iErrorCode;
			if (null !== $sErrorMessage)
			{
				$aResponseItem['ErrorMessage'] = null === $sErrorMessage ? '' : (string) $sErrorMessage;
			}
		}

		if (is_array($aAdditionalParams))
		{
			foreach ($aAdditionalParams as $sKey => $mValue)
			{
				$aResponseItem[$sKey] = $mValue;
			}
		}

		return $aResponseItem;
	}

	/**
	 * @param \CAccount $oAccount
	 * @param string $sActionName
	 * @param \Exception $oException
	 * @param array $aAdditionalParams = null
	 *
	 * @return array
	 */
	public function ExceptionResponse($oAccount, $sActionName, $oException, $aAdditionalParams = null)
	{
		$iErrorCode = null;
		$sErrorMessage = null;

		$bShowError = \CApi::GetConf('labs.webmail.display-server-error-information', false);

		if ($oException instanceof \ProjectCore\Exceptions\ClientException)
		{
			$iErrorCode = $oException->getCode();
			$sErrorMessage = null;
			if ($bShowError)
			{
				$sErrorMessage = $oException->getMessage();
				if (empty($sErrorMessage) || 'ClientException' === $sErrorMessage)
				{
					$sErrorMessage = null;
				}
			}
		}
		else if ($bShowError && $oException instanceof \MailSo\Imap\Exceptions\ResponseException)
		{
			$iErrorCode = \ProjectCore\Notifications::MailServerError;
			
			$oResponse = /* @var $oResponse \MailSo\Imap\Response */ $oException->GetLastResponse();
			if ($oResponse instanceof \MailSo\Imap\Response)
			{
				$sErrorMessage = $oResponse instanceof \MailSo\Imap\Response ?
					$oResponse->Tag.' '.$oResponse->StatusOrIndex.' '.$oResponse->HumanReadable : null;
			}
		}
		else
		{
			$iErrorCode = \ProjectCore\Notifications::UnknownError;
//			$sErrorMessage = $oException->getCode().' - '.$oException->getMessage();
		}

		return $this->FalseResponse($oAccount, $sActionName, $iErrorCode, $sErrorMessage, $aAdditionalParams);
	}

	/**
	 * @param \MailSo\Base\Http $oHttp
	 *
	 * @return void
	 */
	public function SetHttp($oHttp)
	{
		$this->oHttp = $oHttp;
	}

	/**
	 * @param array $aCurrentActionParams
	 *
	 * @return void
	 */
	public function SetActionParams($aCurrentActionParams)
	{
		$this->aCurrentActionParams = $aCurrentActionParams;
	}

	/**
	 * @return array
	 */
	public function GetActionParams()
	{
		return $this->aCurrentActionParams;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 *
	 * @return void
	 */
	public function setParamValue($sKey, $mValue)
	{
		$this->aCurrentActionParams[$sKey] = $mValue;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mDefaul = null
	 *
	 * @return mixed
	 */
	public function getParamValue($sKey, $mDefaul = null)
	{
		return is_array($this->aCurrentActionParams) && isset($this->aCurrentActionParams[$sKey])
			? $this->aCurrentActionParams[$sKey] : $mDefaul;
	}

	/**
	 * @param string $sObjectName
	 *
	 * @return string
	 */
	protected function objectNames($sObjectName)
	{
		$aList = array(
			'CApiMailMessageCollection' => 'MessageCollection',
			'CApiMailMessage' => 'Message',
			'CApiMailFolderCollection' => 'FolderCollection',
			'CApiMailFolder' => 'Folder',
			'Email' => 'Email'
		);

		return !empty($aList[$sObjectName]) ? $aList[$sObjectName] : $sObjectName;
	}
	/**
	 * @param \CAccount $oAccount
	 * @param object $oData
	 * @param string $sParent
	 *
	 * @return array | false
	 */
	protected function objectWrapper($oAccount, $oData, $sParent, $aParameters)
	{
		$mResult = false;
		if (is_object($oData))
		{
			$aNames = explode('\\', get_class($oData));
			$sObjectName = end($aNames);

			$mResult = array(
				'@Object' => $this->objectNames($sObjectName)
			);

			if ($oData instanceof \MailSo\Base\Collection)
			{
				$mResult['@Object'] = 'Collection/'.$mResult['@Object'];
				$mResult['@Count'] = $oData->Count();
				$mResult['@Collection'] = $this->responseObject($oAccount, $oData->CloneAsArray(), $sParent, $aParameters);
			}
			else
			{
				$mResult['@Object'] = 'Object/'.$mResult['@Object'];
			}
		}

		return $mResult;
	}

	/**
	 * @param \CAccount $oAccount
	 * @param mixed $mResponse
	 * @param string $sParent
	 * @param array $aParameters = array()
	 *
	 * @return mixed
	 */
	protected function responseObject($oAccount, $mResponse, $sParent, $aParameters = array())
	{
		$mResult = $mResponse;

		if (is_object($mResponse))
		{
			$sClassName = get_class($mResponse);
			if ('CApiMailMessage' === $sClassName)
			{
				$iTrimmedLimit = \CApi::GetConf('labs.message-body-size-limit', 0);

				$oAttachments = $mResponse->getAttachments();

				$iInternalTimeStampInUTC = $mResponse->getInternalTimeStamp();
				$iReceivedOrDateTimeStampInUTC = $mResponse->getReceivedOrDateTimeStamp();

				$aFlags = $mResponse->getFlagsLowerCase();
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Folder' => $mResponse->getFolder(),
					'Uid' => $mResponse->getUid(),
					'Subject' => $mResponse->getSubject(),
					'MessageId' => $mResponse->getMessageId(),
					'Size' => $mResponse->getSize(),
					'TextSize' => $mResponse->getTextSize(),
					'InternalTimeStampInUTC' => $iInternalTimeStampInUTC,
					'ReceivedOrDateTimeStampInUTC' => $iReceivedOrDateTimeStampInUTC,
					'TimeStampInUTC' =>	\CApi::GetConf('labs.use-date-from-headers', false) && 0 < $iReceivedOrDateTimeStampInUTC ?
						$iReceivedOrDateTimeStampInUTC : $iInternalTimeStampInUTC,
					'From' => $this->responseObject($oAccount, $mResponse->getFrom(), $sParent, $aParameters),
					'To' => $this->responseObject($oAccount, $mResponse->getTo(), $sParent, $aParameters),
					'Cc' => $this->responseObject($oAccount, $mResponse->getCc(), $sParent, $aParameters),
					'Bcc' => $this->responseObject($oAccount, $mResponse->getBcc(), $sParent, $aParameters),
					'Sender' => $this->responseObject($oAccount, $mResponse->getSender(), $sParent, $aParameters),
					'ReplyTo' => $this->responseObject($oAccount, $mResponse->getReplyTo(), $sParent, $aParameters),
					'IsSeen' => in_array('\\seen', $aFlags),
					'IsFlagged' => in_array('\\flagged', $aFlags),
					'IsAnswered' => in_array('\\answered', $aFlags),
					'IsForwarded' => false,
					'HasAttachments' => $oAttachments && $oAttachments->hasNotInlineAttachments(),
					'HasVcardAttachment' => $oAttachments && $oAttachments->hasVcardAttachment(),
					'HasIcalAttachment' => $oAttachments && $oAttachments->hasIcalAttachment(),
					'Priority' => $mResponse->getPriority(),
					'DraftInfo' => $mResponse->getDraftInfo(),
					'Sensitivity' => $mResponse->getSensitivity()
				));

				$mResult['TrimmedTextSize'] = $mResult['TextSize'];
				if (0 < $iTrimmedLimit && $mResult['TrimmedTextSize'] > $iTrimmedLimit)
				{
					$mResult['TrimmedTextSize'] = $iTrimmedLimit;
				}

				$sLowerForwarded = strtolower(\CApi::GetConf('webmail.forwarded-flag-name', ''));
				if (!empty($sLowerForwarded))
				{
					$mResult['IsForwarded'] = in_array($sLowerForwarded, $aFlags);
				}

				$mResult['Hash'] = \CApi::EncodeKeyValues(array(
					'AccountID' => $oAccount ? $oAccount->IdAccount : 0,
					'Folder' => $mResult['Folder'],
					'Uid' => $mResult['Uid'],
					'MimeType' => 'message/rfc822',
					'FileName' => $mResult['Subject'].'.eml'
				));

				if ('MessageGet' === $sParent || 'MessagesGetBodies' === $sParent)
				{
					$mResult['Headers'] = \MailSo\Base\Utils::Utf8Clear($mResponse->getHeaders());
					$mResult['InReplyTo'] = $mResponse->getInReplyTo();
					$mResult['References'] = $mResponse->getReferences();
					$mResult['ReadingConfirmation'] = $mResponse->getReadingConfirmation();
					
					if (!empty($mResult['ReadingConfirmation']) && in_array('$readconfirm', $aFlags))
					{
						$mResult['ReadingConfirmation'] = '';
					}

					$bHasExternals = false;
					$aFoundedCIDs = array();

					$sPlain = '';
					$sHtml = trim($mResponse->getHtml());
					
					if (0 === strlen($sHtml))
					{
						$sPlain = $mResponse->getPlain();
					}

					$aContentLocationUrls = array();
					$aFoundedContentLocationUrls = array();

					if ($oAttachments && 0 < $oAttachments->Count())
					{
						$aList =& $oAttachments->GetAsArray();
						foreach ($aList as /* @var \afterlogic\common\managers\mail\classes\attachment */ $oAttachment)
						{
							if ($oAttachment)
							{
								$sContentLocation = $oAttachment->getContentLocation();
								if ($sContentLocation && 0 < \strlen($sContentLocation))
								{
									$aContentLocationUrls[] = $oAttachment->getContentLocation();
								}
							}
						}
					}

					$iTextSizeLimit = 500000;
					if ($iTextSizeLimit < \strlen($sHtml))
					{
						$iSpacePost = \strpos($sHtml, ' ', $iTextSizeLimit);
						$sHtml = \substr($sHtml, 0, (false !== $iSpacePost && $iSpacePost > $iTextSizeLimit) ? $iSpacePost : $iTextSizeLimit);
					}

					if ($iTextSizeLimit < \strlen($sPlain))
					{
						$iSpacePost = \strpos($sPlain, ' ', $iTextSizeLimit);
						$sPlain = \substr($sPlain, 0, (false !== $iSpacePost && $iSpacePost > $iTextSizeLimit) ? $iSpacePost : $iTextSizeLimit);
					}

					if (0 < \strlen($sHtml) && \CApi::GetConf('labs.webmail.display-inline-css', false))
					{
						include_once PSEVEN_APP_ROOT_PATH.'libraries/other/CssToInlineStyles.php';

						$oCssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($sHtml);
						$oCssToInlineStyles->setEncoding('utf-8');
						$oCssToInlineStyles->setUseInlineStylesBlock(true);

						$mResult['Html'] = \MailSo\Base\HtmlUtils::ClearHtml($oCssToInlineStyles->convert(), $bHasExternals, $aFoundedCIDs,
							$aContentLocationUrls, $aFoundedContentLocationUrls, false, true);
					}
					else
					{
						$mResult['Html'] = 0 === strlen($sHtml) ? '' :
							\MailSo\Base\HtmlUtils::ClearHtml($sHtml, $bHasExternals, $aFoundedCIDs,
								$aContentLocationUrls, $aFoundedContentLocationUrls, false, true);
					}

					$mResult['Trimmed'] = false;
					$mResult['Plain'] = 0 === strlen($sPlain) ? '' : \MailSo\Base\HtmlUtils::ConvertPlainToHtml($sPlain);
					$mResult['PlainRaw'] = \trim($sPlain);
					$mResult['Rtl'] = 0 < \strlen($mResult['Plain']) ? \MailSo\Base\Utils::IsRTL($mResult['Plain']) : false;

					if (0 < $iTrimmedLimit && 'Messages' === $sParent)
					{
						if ($iTrimmedLimit < strlen($mResult['Plain']))
						{
							$iPos = strpos($mResult['Plain'], ' ', $iTrimmedLimit);
							if (false !== $iPos && $iTrimmedLimit <= $iPos)
							{
								$mResult['Plain'] = substr($mResult['Plain'], 0, $iPos);
								$mResult['Trimmed'] = true;
							}
						}

						if ($iTrimmedLimit < strlen($mResult['Html']))
						{
							$iPos = strpos($mResult['Html'], ' <', $iTrimmedLimit);
							if (false !== $iPos && $iTrimmedLimit <= $iPos)
							{
								$mResult['Html'] = substr($mResult['Html'], 0, $iPos).'<!-- cutted -->';
								$mResult['Trimmed'] = true;
							}
						}
					}

					$mResult['ICAL'] = $this->responseObject($oAccount, $mResponse->getExtend('ICAL'), $sParent, $aParameters);
					$mResult['VCARD'] = $this->responseObject($oAccount, $mResponse->getExtend('VCARD'), $sParent, $aParameters);
					
					$mResult['Safety'] = $mResponse->getSafety();
					$mResult['HasExternals'] = $bHasExternals;
					$mResult['FoundedCIDs'] = $aFoundedCIDs;
					$mResult['FoundedContentLocationUrls'] = $aFoundedContentLocationUrls;
					$mResult['Attachments'] = $this->responseObject($oAccount, $oAttachments, $sParent, array_merge($aParameters, array(
						'FoundedCIDs' => $aFoundedCIDs,
						'FoundedContentLocationUrls' => $aFoundedContentLocationUrls
					)));

//					$mResult['Html'] = \MailSo\Base\Utils::Utf8Clear($mResult['Html']);
//					$mResult['Plain'] = \MailSo\Base\Utils::Utf8Clear($mResult['Plain']);
				}
				else
				{
					$mResult['@Object'] = 'Object/MessageListItem';
					$mResult['Threads'] = $mResponse->getThreads();
				}

				$mResult['Custom'] = $this->responseObject($oAccount, $mResponse->getCustomList(), $sParent, $aParameters);
				$mResult['Subject'] = \MailSo\Base\Utils::Utf8Clear($mResult['Subject']);
			}
			else if ('CApiMailIcs' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Uid' => $mResponse->Uid,
					'Sequence' => $mResponse->Sequence,
					'Attendee' => $mResponse->Attendee,
					'File' => $mResponse->File,
					'Type' => $mResponse->Type,
					'Location' => $mResponse->Location,
					'Description' => \MailSo\Base\LinkFinder::NewInstance()
						->Text($mResponse->Description)
						->UseDefaultWrappers(true)
						->CompileText(),
					'When' => $mResponse->When,
					'CalendarId' => $mResponse->CalendarId
				));
			}
			else if ('CApiMailVcard' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Uid' => $mResponse->Uid,
					'File' => $mResponse->File,
					'Name' => $mResponse->Name,
					'Email' => $mResponse->Email,
					'Exists' => $mResponse->Exists
				));
			}
			else if ('CFilter' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Enable' => $mResponse->Enable,
					'Field' => $mResponse->Field,
					'Filter' => $mResponse->Filter,
					'Condition' => $mResponse->Condition,
					'Action' => $mResponse->Action,
					'FolderFullName' => $mResponse->FolderFullName,
				));
			}
			else if ('CHelpdeskThread' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdHelpdeskThread' => $mResponse->IdHelpdeskThread,
					'ThreadHash' => $mResponse->StrHelpdeskThreadHash,
					'IdOwner' => $mResponse->IdOwner,
					'Owner' => $mResponse->Owner,
					'Type' => $mResponse->Type,
					'Subject' => $mResponse->Subject,
					'IsRead' => $mResponse->IsRead,
					'IsArchived' => $mResponse->IsArchived,
					'ItsMe' => $mResponse->ItsMe,
					'HasAttachments' => $mResponse->HasAttachments,
					'PostCount' => $mResponse->PostCount,
					'Created' => $mResponse->Created,
					'Updated' => $mResponse->Updated
				));
			}
			else if ('CHelpdeskPost' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdHelpdeskPost' => $mResponse->IdHelpdeskPost,
					'IdHelpdeskThread' => $mResponse->IdHelpdeskThread,
					'IdOwner' => $mResponse->IdOwner,
					'Owner' => $mResponse->Owner,
					'Attachments' => $this->responseObject($oAccount, $mResponse->Attachments, $sParent),
					'IsThreadOwner' => $mResponse->IsThreadOwner,
					'ItsMe' => $mResponse->ItsMe,
					'Type' => $mResponse->Type,
					'SystemType' => $mResponse->SystemType,
					'Text' => \MailSo\Base\HtmlUtils::ConvertPlainToHtml($mResponse->Text),
					'Created' => $mResponse->Created
				));
			}
			else if ('CHelpdeskAttachment' === $sClassName)
			{
				$iThumbnailLimit = 1024 * 1024 * 2; // 2MB

				/* @var $mResponse CHelpdeskAttachment */
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdHelpdeskAttachment' => $mResponse->IdHelpdeskAttachment,
					'IdHelpdeskPost' => $mResponse->IdHelpdeskPost,
					'IdHelpdeskThread' => $mResponse->IdHelpdeskThread,
					'SizeInBytes' => $mResponse->SizeInBytes,
					'FileName' => $mResponse->FileName,
					'MimeType' => \MailSo\Base\Utils::MimeContentType($mResponse->FileName),
					'Thumb' => \CApi::GetConf('labs.allow-thumbnail', true) &&
						$mResponse->SizeInBytes < $iThumbnailLimit &&
						\api_Utils::IsGDImageMimeTypeSuppoted(
							\MailSo\Base\Utils::MimeContentType($mResponse->FileName), $mResponse->FileName),
					'Hash' => $mResponse->Hash,
					'Content' => $mResponse->Content,
					'Created' => $mResponse->Created
				));
			}
			else if ('CFetcher' === $sClassName)
			{
				/* @var $mResponse \CFetcher */
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdFetcher' => $mResponse->IdFetcher,
					'IdAccount' => $mResponse->IdAccount,
					'IsEnabled' => $mResponse->IsEnabled,
					'IsLocked' => $mResponse->IsLocked,
					'Folder' => $mResponse->Folder,
					'Name' => $mResponse->Name,
					'Email' => $mResponse->Email,
					'Signature' => $mResponse->Signature,
					'SignatureOptions' => $mResponse->SignatureOptions,
					'LeaveMessagesOnServer' => $mResponse->LeaveMessagesOnServer,
					'IncomingMailServer' => $mResponse->IncomingMailServer,
					'IncomingMailPort' => $mResponse->IncomingMailPort,
					'IncomingMailLogin' => $mResponse->IncomingMailLogin,
					'IsOutgoingEnabled' => $mResponse->IsOutgoingEnabled,
					'OutgoingMailServer' => $mResponse->OutgoingMailServer,
					'OutgoingMailPort' => $mResponse->OutgoingMailPort,
					'OutgoingMailAuth' => $mResponse->OutgoingMailAuth,
					'IncomingMailSsl' => $mResponse->IncomingMailSecurity === \MailSo\Net\Enumerations\ConnectionSecurityType::SSL,
					'OutgoingMailSsl' => $mResponse->OutgoingMailSecurity === \MailSo\Net\Enumerations\ConnectionSecurityType::SSL
				));
			}
			else if ('CApiMailFolder' === $sClassName)
			{
				$aExtended = null;
				$mStatus = $mResponse->getStatus();
				if (is_array($mStatus) && isset($mStatus['MESSAGES'], $mStatus['UNSEEN'], $mStatus['UIDNEXT']))
				{
					$aExtended = array(
						'MessageCount' => (int) $mStatus['MESSAGES'],
						'MessageUnseenCount' => (int) $mStatus['UNSEEN'],
						'UidNext' => (string) $mStatus['UIDNEXT'],
						'Hash' => \api_Utils::GenerateFolderHash(
							$mResponse->getRawFullName(), $mStatus['MESSAGES'], $mStatus['UNSEEN'], $mStatus['UIDNEXT']
						)
					);
				}

				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Type' => $mResponse->getType(),
					'Name' => $mResponse->getName(),
					'FullName' => $mResponse->getFullName(),
					'FullNameRaw' => $mResponse->getRawFullName(),
					'FullNameHash' => md5($mResponse->getRawFullName()),
					'Delimiter' => $mResponse->getDelimiter(),
					'IsSubscribed' => $oAccount->isExtensionEnabled(\CAccount::IgnoreSubscribeStatus) ? true : $mResponse->isSubscribed(),
					'IsSelectable' => $mResponse->isSelectable(),
					'Exists' => $mResponse->exists(),
					'Extended' => $aExtended,
					'SubFolders' => $this->responseObject($oAccount, $mResponse->getSubFolders(), $sParent, $aParameters)
				));
			}
			else if ('CApiMailAttachment' === $sClassName)
			{
				$mFoundedCIDs = isset($aParameters['FoundedCIDs']) && is_array($aParameters['FoundedCIDs'])
					? $aParameters['FoundedCIDs'] : null;

				$mFoundedContentLocationUrls = isset($aParameters['FoundedContentLocationUrls']) &&
					\is_array($aParameters['FoundedContentLocationUrls']) &&
					0 < \count($aParameters['FoundedContentLocationUrls']) ?
						$aParameters['FoundedContentLocationUrls'] : null;

				if ($mFoundedCIDs || $mFoundedContentLocationUrls)
				{
					$aFoundedCIDs = \array_merge($mFoundedCIDs ? $mFoundedCIDs : array(),
						$mFoundedContentLocationUrls ? $mFoundedContentLocationUrls : array());

					$aFoundedCIDs = 0 < \count($mFoundedCIDs) ? $mFoundedCIDs : null;
				}

				$sMimeType = strtolower(trim($mResponse->getMimeType()));
				$sMimeIndex = strtolower(trim($mResponse->getMimeIndex()));
				$sContentTransferEncoding = strtolower(trim($mResponse->getEncoding()));

				$sFileName = $mResponse->getFileName(true);
				$iEstimatedSize = $mResponse->getEstimatedSize();
				$iThumbnailLimit = 1024 * 1024 * 2; // 2MB //TODO

				if (in_array($sMimeType, array('application/octet-stream')))
				{
					$sMimeType = \MailSo\Base\Utils::MimeContentType($sFileName);
				}

				$sCid = \trim(\trim($mResponse->getCid()), '<>');
				
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'FileName' => $sFileName,
					'MimeType' => $sMimeType,
					'MimePartIndex' => ('message/rfc822' === $sMimeType && ('base64' === $sContentTransferEncoding || 'quoted-printable' === $sContentTransferEncoding))
						? '' :  $sMimeIndex,
					'EstimatedSize' => $iEstimatedSize,
					'CID' => $sCid,
					'ContentLocation' => $mResponse->getContentLocation(),
					'Thumb' => \CApi::GetConf('labs.allow-thumbnail', true) &&
						$iEstimatedSize < $iThumbnailLimit &&
						\api_Utils::IsGDImageMimeTypeSuppoted($sMimeType, $sFileName),
					'Expand' =>\CApi::isExpandMimeTypeSupported($sMimeType, $sFileName),
					'Iframed' =>\CApi::isIframedMimeTypeSupported($sMimeType, $sFileName),
					'Content' => $mResponse->getContent(),
					'IsInline' => $mResponse->isInline(),
					'IsLinked' => (!empty($sCid) && $mFoundedCIDs && \in_array($sCid, $mFoundedCIDs)) ||
						($mFoundedContentLocationUrls && \in_array(\trim($mResponse->getContentLocation()), $mFoundedContentLocationUrls))
				));

				$mResult['Hash'] = \CApi::EncodeKeyValues(array(
					'Iframed' => $mResult['Iframed'],
					'AccountID' => $oAccount ? $oAccount->IdAccount : 0,
					'Folder' => $mResponse->getFolder(),
					'Uid' => $mResponse->getUid(),
					'MimeIndex' => $sMimeIndex,
					'MimeType' =>  $sMimeType,
					'FileName' => $mResponse->getFileName(true)
				));

			}
			else if ('MailSo\Mime\Email' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'DisplayName' => \MailSo\Base\Utils::Utf8Clear($mResponse->GetDisplayName()),
					'Email' => \MailSo\Base\Utils::Utf8Clear($mResponse->GetEmail())
				));
			}
			else if ('CApiMailMessageCollection' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Uids' => $mResponse->Uids,
					'UidNext' => $mResponse->UidNext,
					'FolderHash' => $mResponse->FolderHash,
					'MessageCount' => $mResponse->MessageCount,
					'MessageUnseenCount' => $mResponse->MessageUnseenCount,
					'MessageResultCount' => $mResponse->MessageResultCount,
					'FolderName' => $mResponse->FolderName,
					'Offset' => $mResponse->Offset,
					'Limit' => $mResponse->Limit,
					'Search' => $mResponse->Search,
					'Filters' => $mResponse->Filters,
					'New' => $mResponse->New
				));
			}
			else if ('CIdentity' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdIdentity' => $mResponse->IdIdentity,
					'IdAccount' => $mResponse->IdAccount,
					'Default' => $mResponse->Default,
					'Enabled' => $mResponse->Enabled,
					'Email' => $mResponse->Email,
					'FriendlyName' => $mResponse->FriendlyName,
					'UseSignature' => $mResponse->UseSignature,
					'Signature' => $mResponse->Signature
				));
			}
			else if ('CApiMailFolderCollection' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Namespace' => $mResponse->GetNamespace()
				));
			}
			else if ('CContactListItem' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdUser' => $oAccount->IdUser,
					'Id' => $mResponse->Id,
					'Name' => $mResponse->Name,
					'Email' => $mResponse->Email,
					'Emails' => $mResponse->Emails,
					'Phones' => $mResponse->Phones,
					'UseFriendlyName' => $mResponse->UseFriendlyName,
					'IsGroup' => $mResponse->IsGroup,
					'IsOrganization' => $mResponse->IsOrganization,
					'ReadOnly' => $mResponse->ReadOnly,
					'ItsMe' => $mResponse->ItsMe,
					'Global' => $mResponse->Global,
					'ForSharedToAll' => $mResponse->ForSharedToAll,
					'SharedToAll' => $mResponse->SharedToAll,
					'Frequency' => $mResponse->Frequency,
					'AgeScore' => $mResponse->AgeScore
				));
			}
			else if ('CContact' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdUser' => $mResponse->IdUser,
					'IdContact' => $mResponse->IdContact,
					'IdContactStr' => $mResponse->IdContactStr,

					'Global' => $mResponse->Global,
					'ItsMe' => $mResponse->ItsMe,
					
					'PrimaryEmail' => $mResponse->PrimaryEmail,
					'UseFriendlyName' => $mResponse->UseFriendlyName,

					'GroupsIds' => $mResponse->GroupsIds,

					'FullName' => $mResponse->FullName,
					'Title' => $mResponse->Title,
					'FirstName' => $mResponse->FirstName,
					'LastName' => $mResponse->LastName,
					'NickName' => $mResponse->NickName,
					'Skype' => $mResponse->Skype,
					'Facebook' => $mResponse->Facebook,

					'HomeEmail' => $mResponse->HomeEmail,
					'HomeStreet' => $mResponse->HomeStreet,
					'HomeCity' => $mResponse->HomeCity,
					'HomeState' => $mResponse->HomeState,
					'HomeZip' => $mResponse->HomeZip,
					'HomeCountry' => $mResponse->HomeCountry,
					'HomePhone' => $mResponse->HomePhone,
					'HomeFax' => $mResponse->HomeFax,
					'HomeMobile' => $mResponse->HomeMobile,
					'HomeWeb' => $mResponse->HomeWeb,

					'BusinessEmail' => $mResponse->BusinessEmail,
					'BusinessCompany' => $mResponse->BusinessCompany,
					'BusinessStreet' => $mResponse->BusinessStreet,
					'BusinessCity' => $mResponse->BusinessCity,
					'BusinessState' => $mResponse->BusinessState,
					'BusinessZip' => $mResponse->BusinessZip,
					'BusinessCountry' => $mResponse->BusinessCountry,
					'BusinessJobTitle' => $mResponse->BusinessJobTitle,
					'BusinessDepartment' => $mResponse->BusinessDepartment,
					'BusinessOffice' => $mResponse->BusinessOffice,
					'BusinessPhone' => $mResponse->BusinessPhone,
					'BusinessMobile' => $mResponse->BusinessMobile,
					'BusinessFax' => $mResponse->BusinessFax,
					'BusinessWeb' => $mResponse->BusinessWeb,

					'OtherEmail' => $mResponse->OtherEmail,
					'Notes' => $mResponse->Notes,

					'BirthdayDay' => $mResponse->BirthdayDay,
					'BirthdayMonth' => $mResponse->BirthdayMonth,
					'BirthdayYear' => $mResponse->BirthdayYear,
					'ReadOnly' => $mResponse->ReadOnly,
					'ETag' => $mResponse->ETag,
					'SharedToAll' => $mResponse->SharedToAll
				));
			}
			else if ('CGroup' === $sClassName)
			{
				$aContacts = $this->ApiContacts()->getContactItems(
					$mResponse->IdUser, \EContactSortField::Name, \ESortOrder::ASC, 0, 299, '', '', $mResponse->IdGroup);

				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdUser' => $mResponse->IdUser,
					'IdGroup' => $mResponse->IdGroup,
					'IdGroupStr' => $mResponse->IdGroupStr,
					'Name' => $mResponse->Name,

					'IsOrganization' => $mResponse->IsOrganization,
					'Email'		=> $mResponse->Email,
					'Company'	=> $mResponse->Company,
					'Street'	=> $mResponse->Street,
					'City'		=> $mResponse->City,
					'State'		=> $mResponse->City,
					'Zip'		=> $mResponse->Zip,
					'Country'	=> $mResponse->Country,
					'Phone'		=> $mResponse->Phone,
					'Fax'		=> $mResponse->Fax,
					'Web'		=> $mResponse->Web,
					
					'Contacts' => $this->responseObject($oAccount, $aContacts, $sParent, $aParameters)
				));
			}
			else if ($mResponse instanceof \MailSo\Base\Collection)
			{
				$aCollection = $mResponse->GetAsArray();
				if (150 < \count($aCollection) && $mResponse instanceof \MailSo\Mime\EmailCollection)
				{
					$aCollection = \array_slice($aCollection, 0, 150);
				}
				
				$mResult = $this->responseObject($oAccount, $aCollection, $sParent, $aParameters);
				unset($aCollection);
			}
			else if ('CSocial' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), $mResponse->toArray());
			}			
			else if ('CFileStorageItem' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Id' => $mResponse->Id,
					'Type' => $mResponse->TypeStr,
					'Path' => $mResponse->Path,
					'FullPath' => $mResponse->FullPath,
					'Name' => $mResponse->Name,
					'Size' => $mResponse->Size,
					'IsFolder' => $mResponse->IsFolder,
					'IsLink' => $mResponse->IsLink,
					'LinkType' => $mResponse->LinkType,
					'LinkUrl' => $mResponse->LinkUrl,
					'LastModified' => $mResponse->LastModified,
					'ContentType' => $mResponse->ContentType,
					'Iframed' => $mResponse->Iframed,
					'Thumb' => $mResponse->Thumb,
					'ThumbnailLink' => $mResponse->ThumbnailLink,
					'OembedHtml' => $mResponse->OembedHtml,
					'Hash' => $mResponse->Hash,
					'Shared' => $mResponse->Shared,
					'Owner' => $mResponse->Owner,
					'Content' => $mResponse->Content,
					'IsExternal' => $mResponse->IsExternal
				));
			}			
			else
			{
				$mResult = '['.$sClassName.']';
			}
		}
		else if (is_array($mResponse))
		{
			foreach ($mResponse as $iKey => $oItem)
			{
				$mResponse[$iKey] = $this->responseObject($oAccount, $oItem, $sParent, $aParameters);
			}

			$mResult = $mResponse;
		}

		unset($mResponse);
		return $mResult;
	}
}
