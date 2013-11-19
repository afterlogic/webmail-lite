<?php

namespace ProjectSeven;

/**
 * @category ProjectSeven
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

		if ($oException instanceof \ProjectSeven\Exceptions\ClientException)
		{
			$iErrorCode = $oException->getCode();
			$sErrorMessage = null;
		}
		else
		{
			$iErrorCode = \ProjectSeven\Notifications::UnknownError;
			$sErrorMessage = $oException->getCode().' - '.$oException->getMessage();
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
	 * @param string $sMimeType
	 * @return bool
	 */
	private function isImageMimeTypeSuppoted($sMimeType)
	{
		$bResult = function_exists('gd_info');
		if ($bResult)
		{
			$bResult = false;
			switch (strtolower($sMimeType))
			{
				case 'image/jpg':
				case 'image/jpeg':
					$bResult = function_exists('imagecreatefromjpeg');
					break;
				case 'image/png':
					$bResult = function_exists('imagecreatefrompng');
					break;
			}
		}

		return $bResult;
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
				$oAttachments = $mResponse->Attachments();

				$iInternalTimeStampInUTC = $mResponse->InternalTimeStampInUTC();
				$iReceivedOrDateTimeStampInUTC = $mResponse->ReceivedOrDateTimeStampInUTC();

				$aFlags = $mResponse->FlagsLowerCase();
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Folder' => $mResponse->Folder(),
					'Uid' => $mResponse->Uid(),
					'Subject' => $mResponse->Subject(),
					'MessageId' => $mResponse->MessageId(),
					'Size' => $mResponse->Size(),
					'TextSize' => $mResponse->TextSize(),
					'InternalTimeStampInUTC' => $iInternalTimeStampInUTC,
					'ReceivedOrDateTimeStampInUTC' => $iReceivedOrDateTimeStampInUTC,
					'TimeStampInUTC' =>	\CApi::GetConf('labs.use-date-from-headers', false) && 0 < $iReceivedOrDateTimeStampInUTC ?
						$iReceivedOrDateTimeStampInUTC : $iInternalTimeStampInUTC,
					'From' => $this->responseObject($oAccount, $mResponse->From(), $sParent, $aParameters),
					'To' => $this->responseObject($oAccount, $mResponse->To(), $sParent, $aParameters),
					'Cc' => $this->responseObject($oAccount, $mResponse->Cc(), $sParent, $aParameters),
					'Bcc' => $this->responseObject($oAccount, $mResponse->Bcc(), $sParent, $aParameters),
					'Sender' => $this->responseObject($oAccount, $mResponse->Sender(), $sParent, $aParameters),
					'ReplyTo' => $this->responseObject($oAccount, $mResponse->ReplyTo(), $sParent, $aParameters),
					'IsSeen' => in_array('\\seen', $aFlags),
					'IsFlagged' => in_array('\\flagged', $aFlags),
					'IsAnswered' => in_array('\\answered', $aFlags),
					'IsForwarded' => false,
					'HasAttachments' => $oAttachments && 0 < $oAttachments->Count(),
					'HasVcardAttachment' => $oAttachments && $oAttachments->HasVcardAttachment(),
					'HasIcalAttachment' => $oAttachments && $oAttachments->HasIcalAttachment(),
					'Priority' => $mResponse->Priority(),
					'DraftInfo' => $mResponse->DraftInfo(),
					'Sensitivity' => $mResponse->Sensitivity()
				));

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

				if ('Message' === $sParent || 'Messages' === $sParent)
				{
//					$mResult['Headers'] = $mResponse->Headers();
					$mResult['InReplyTo'] = $mResponse->InReplyTo();
					$mResult['References'] = $mResponse->References();
					$mResult['ReadingConfirmation'] = $mResponse->ReadingConfirmation();

					$bHasExternals = false;
					$aFoundedCIDs = array();

					$bRtl = false;
					$sPlain = '';
					$sHtml = $mResponse->Html();
					
					if (0 === strlen($sHtml))
					{
						$sPlain = $mResponse->Plain();
						$bRtl = \MailSo\Base\Utils::IsRTL($sPlain);
					}
					else
					{
						$bRtl = \MailSo\Base\Utils::IsRTL($sHtml);
					}

					$mResult['Html'] = 0 === strlen($sHtml) ? '' : \MailSo\Base\HtmlUtils::ClearHtml($sHtml, $bHasExternals, $aFoundedCIDs);
					$mResult['Plain'] = 0 === strlen($sPlain) ? '' : \MailSo\Base\HtmlUtils::ConvertPlainToHtml($sPlain);
					$mResult['Rtl'] = $bRtl;

					$mResult['ICAL'] = $this->responseObject($oAccount, $mResponse->GetExtend('ICAL'), $sParent, $aParameters);
					$mResult['VCARD'] = $this->responseObject($oAccount, $mResponse->GetExtend('VCARD'), $sParent, $aParameters);
					
					$mResult['Safety'] = $mResponse->Safety();
					$mResult['HasExternals'] = $bHasExternals;
					$mResult['FoundedCIDs'] = $aFoundedCIDs;
					$mResult['Attachments'] = $this->responseObject($oAccount, $oAttachments, $sParent, array_merge($aParameters, array(
						'FoundedCIDs' => $aFoundedCIDs
					)));

//					$mResult['Html'] = \MailSo\Base\Utils::Utf8Clear($mResult['Html']);
//					$mResult['Plain'] = \MailSo\Base\Utils::Utf8Clear($mResult['Plain']);
				}
				else
				{
					$mResult['@Object'] = 'Object/MessageListItem';
					$mResult['Threads'] = $mResponse->Threads();
				}

				$mResult['Custom'] = $this->responseObject($oAccount, $mResponse->Custom(), $sParent, $aParameters);
				$mResult['Subject'] = \MailSo\Base\Utils::Utf8Clear($mResult['Subject']);
			}
			else if ('CApiMailIcs' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Uid' => $mResponse->Uid,
					'File' => $mResponse->File,
					'Type' => $mResponse->Type,
					'Location' => $mResponse->Location,
					'Description' => \MailSo\Base\LinkFinder::NewInstance()
						->Text($mResponse->Description)
						->UseDefaultWrappers(true)
						->CompileText(true, false),
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
				/* @var $mResponse CHelpdeskAttachment */
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdHelpdeskAttachment' => $mResponse->IdHelpdeskAttachment,
					'IdHelpdeskPost' => $mResponse->IdHelpdeskPost,
					'IdHelpdeskThread' => $mResponse->IdHelpdeskThread,
					'SizeInBytes' => $mResponse->SizeInBytes,
					'FileName' => $mResponse->FileName,
					'MimeType' => \MailSo\Base\Utils::MimeContentType($mResponse->FileName),
					'Hash' => $mResponse->Hash,
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
					'LeaveMessagesOnServer' => $mResponse->LeaveMessagesOnServer,
					'IncomingMailServer' => $mResponse->IncomingMailServer,
					'IncomingMailPort' => $mResponse->IncomingMailPort,
					'IncomingMailLogin' => $mResponse->IncomingMailLogin,
					'IsOutgoingEnabled' => $mResponse->IsOutgoingEnabled,
					'OutgoingMailServer' => $mResponse->OutgoingMailServer,
					'OutgoingMailPort' => $mResponse->OutgoingMailPort,
					'OutgoingMailAuth' => $mResponse->OutgoingMailAuth
				));
			}
			else if ('CApiMailFolder' === $sClassName)
			{
				$aExtended = null;
				$mStatus = $mResponse->Status();
				if (is_array($mStatus) && isset($mStatus['MESSAGES'], $mStatus['UNSEEN'], $mStatus['UIDNEXT']))
				{
					$aExtended = array(
						'MessageCount' => (int) $mStatus['MESSAGES'],
						'MessageUnseenCount' => (int) $mStatus['UNSEEN'],
						'UidNext' => (string) $mStatus['UIDNEXT'],
						'Hash' => \api_Utils::GenerateFolderHash(
							$mResponse->FullNameRaw(), $mStatus['MESSAGES'], $mStatus['UNSEEN'], $mStatus['UIDNEXT']
						)
					);
				}

				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Type' => $mResponse->Type(),
					'Name' => $mResponse->Name(),
					'FullName' => $mResponse->FullName(),
					'FullNameRaw' => $mResponse->FullNameRaw(),
					'FullNameHash' => md5($mResponse->FullNameRaw()),
					'Delimiter' => $mResponse->Delimiter(),
					'IsSubscribed' => $oAccount->IsEnabledExtension(\CAccount::IgnoreSubscribeStatus) ? true : $mResponse->IsSubscribed(),
					'IsSelectable' => $mResponse->IsSelectable(),
					'IsExisten' => $mResponse->IsExisten(),
					'Extended' => $aExtended,
					'NamespaceFolder' => $mResponse->NamespaceFolder(),
					'SubFolders' => $this->responseObject($oAccount, $mResponse->SubFolders(), $sParent, $aParameters)
				));
			}
			else if ('CApiMailAttachment' === $sClassName)
			{
				$aFoundedCIDs = isset($aParameters['FoundedCIDs']) && is_array($aParameters['FoundedCIDs'])
					? $aParameters['FoundedCIDs'] : array();

				$sMimeType = strtolower(trim($mResponse->MimeType()));
				$sMimeIndex = strtolower(trim($mResponse->MimeIndex()));
				$sContentTransferEncoding = strtolower(trim($mResponse->ContentTransferEncoding()));

				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'FileName' => $mResponse->FileName(true),
					'MimeType' => $sMimeType,
					'MimePartIndex' => ('message/rfc822' === $sMimeType && ('base64' === $sContentTransferEncoding || 'quoted-printable' === $sContentTransferEncoding))
						? '' :  $sMimeIndex,
					'EstimatedSize' => $mResponse->EstimatedSize(),
					'CID' => $mResponse->Cid(),
					'Thumb' => $this->isImageMimeTypeSuppoted($sMimeType),
					'IsInline' => $mResponse->IsInline(),
					'IsLinked' => in_array(trim(trim($mResponse->Cid()), '<>'), $aFoundedCIDs)
				));

				$mResult['Hash'] = \CApi::EncodeKeyValues(array(
					'AccountID' => $oAccount ? $oAccount->IdAccount : 0,
					'Folder' => $mResponse->Folder(),
					'Uid' => $mResponse->Uid(),
					'MimeIndex' => $sMimeIndex,
					'MimeType' =>  $sMimeType,
					'FileName' => $mResponse->FileName(true)
				));

			}
			else if ('MailSo\Mime\Email' === $sClassName)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'$sClassName' => $sClassName,
					'DisplayName' => $mResponse->GetDisplayName(),
					'Email' => $mResponse->GetEmail()
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
					'Filters' => $mResponse->Filters
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
					'UseFriendlyName' => $mResponse->UseFriendlyName,
					'IsGroup' => $mResponse->IsGroup,
					'ReadOnly' => $mResponse->ReadOnly,
					'ItsMe' => $mResponse->ItsMe,
					'Frequency' => $mResponse->Frequency
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
					'ETag' => $mResponse->ETag
				));
			}
			else if ('CGroup' === $sClassName)
			{
				$aContacts = $this->ApiContacts()->GetContactItems(
					$mResponse->IdUser, \EContactSortField::Name, \ESortOrder::ASC, 0, 299, '', '', $mResponse->IdGroup);

				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdUser' => $mResponse->IdUser,
					'IdGroup' => $mResponse->IdGroup,
					'IdGroupStr' => $mResponse->IdGroupStr,

					'Name' => $mResponse->Name,

					'Contacts' => $this->responseObject($oAccount, $aContacts, $sParent, $aParameters)
				));
			}
			else if ($mResponse instanceof \MailSo\Base\Collection)
			{
				$mResult = $this->responseObject($oAccount, $mResponse->GetAsArray(), $sParent, $aParameters);
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
