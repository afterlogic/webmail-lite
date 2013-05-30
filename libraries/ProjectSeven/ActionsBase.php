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
	 *
	 * @return array
	 */
	public function FalseResponse($oAccount, $sActionName, $iErrorCode = null, $sErrorMessage = null)
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

		return $aResponseItem;
	}

	/**
	 * @param string $sActionName
	 * @param \Exception $oException
	 *
	 * @return array
	 */
	public function ExceptionResponse($oAccount, $sActionName, $oException)
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

		return $this->FalseResponse($oAccount, $sActionName, $iErrorCode, $sErrorMessage);
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
			if ($mResponse instanceof \CApiMailMessage)
			{
				$oAttachments = $mResponse->Attachments();

				$aFlags = $mResponse->FlagsLowerCase();
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Folder' => $mResponse->Folder(),
					'Uid' => $mResponse->Uid(),
					'Subject' => $mResponse->Subject(),
					'MessageId' => $mResponse->MessageId(),
					'TextPartID' => $mResponse->TextPartID(),
					'Size' => $mResponse->Size(),
					'TextSize' => $mResponse->TextSize(),
					'InternalTimeStampInUTC' => $mResponse->InternalTimeStampInUTC(),
					'From' => $this->responseObject($oAccount, $mResponse->From(), $sParent, $aParameters),
					'To' => $this->responseObject($oAccount, $mResponse->To(), $sParent, $aParameters),
					'Cc' => $this->responseObject($oAccount, $mResponse->Cc(), $sParent, $aParameters),
					'Bcc' => $this->responseObject($oAccount, $mResponse->Bcc(), $sParent, $aParameters),
					'Sender' => $this->responseObject($oAccount, $mResponse->Sender(), $sParent, $aParameters),
					'IsSeen' => in_array('\\seen', $aFlags),
					'IsFlagged' => in_array('\\flagged', $aFlags),
					'IsAnswered' => in_array('\\answered', $aFlags),
					'IsForwarded' => false,
					'HasAttachments' => $oAttachments && 0 < $oAttachments->Count(),
					'IsAnswered' => in_array('\\answered', $aFlags),
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

					$mResult['Html'] = \MailSo\Base\HtmlUtils::ClearHtml($mResponse->Html(), $bHasExternals, $aFoundedCIDs);
					$mResult['Plain'] = \MailSo\Base\HtmlUtils::ConvertPlainToHtml($mResponse->Plain());

					$mResult['ICAL'] = $this->responseObject($oAccount, $mResponse->GetExtend('ICAL'), $sParent, $aParameters);
					$mResult['VCARD'] = $this->responseObject($oAccount, $mResponse->GetExtend('VCARD'), $sParent, $aParameters);
					
					$mResult['Safety'] = $mResponse->Safety();
					$mResult['HasExternals'] = $bHasExternals;
					$mResult['FoundedCIDs'] = $aFoundedCIDs;
					$mResult['Attachments'] = $this->responseObject($oAccount, $oAttachments, $sParent, array_merge($aParameters, array(
						'FoundedCIDs' => $aFoundedCIDs
					)));
				}
				else
				{
					$mResult['@Object'] = 'Object/MessageListItem';
				}
			}
			else if ($mResponse instanceof \CApiMailIcs)
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
			else if ($mResponse instanceof \CApiMailVcard)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Uid' => $mResponse->Uid,
					'File' => $mResponse->File,
					'Name' => $mResponse->Name,
					'Email' => $mResponse->Email,
					'Exists' => $mResponse->Exists
				));
			}
			else if ($mResponse instanceof \CFilter)
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
			else if ($mResponse instanceof \CApiMailFolder)
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
							$mResponse->FullNameRaw(), $mStatus['MESSAGES'], $mStatus['UNSEEN'], $mStatus['UIDNEXT'])
					);
				}

				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Type' => $mResponse->Type(),
					'Name' => $mResponse->Name(),
					'FullName' => $mResponse->FullName(),
					'FullNameRaw' => $mResponse->FullNameRaw(),
					'Delimiter' => $mResponse->Delimiter(),
					'IsSubscribed' => $oAccount->IsEnabledExtension(\CAccount::IgnoreSubscribeStatus) ? true : $mResponse->IsSubscribed(),
					'IsSelectable' => $mResponse->IsSelectable(),
					'IsExisten' => $mResponse->IsExisten(),
					'Extended' => $aExtended,
					'NamespaceFolder' => $mResponse->NamespaceFolder(),
					'SubFolders' => $this->responseObject($oAccount, $mResponse->SubFolders(), $sParent, $aParameters)
				));
			}
			else if ($mResponse instanceof \CApiMailAttachment)
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
			else if ($mResponse instanceof \MailSo\Mime\Email)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'DisplayName' => $mResponse->GetDisplayName(),
					'Email' => $mResponse->GetEmail()
				));
			}
			else if ($mResponse instanceof \CApiMailMessageCollection)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Uids' => $mResponse->Uids,
					'UidNext' => $mResponse->UidNext,
					'FolderHash' => $mResponse->FolderHash,
					'MessageCount' => $mResponse->MessageCount,
					'MessageUnseenCount' => $mResponse->MessageUnseenCount,
					'MessageSearchCount' => $mResponse->MessageSearchCount,
					'FolderName' => $mResponse->FolderName,
					'Offset' => $mResponse->Offset,
					'Limit' => $mResponse->Limit,
					'Search' => $mResponse->Search
				));
			}
			else if ($mResponse instanceof \CApiMailFolderCollection)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'Namespace' => $mResponse->GetNamespace()
				));
			}
			else if ($mResponse instanceof \CContactListItem)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdUser' => $oAccount->IdUser,
					'Id' => $mResponse->Id,
					'Name' => $mResponse->Name,
					'Email' => $mResponse->Email,
					'UseFriendlyName' => $mResponse->UseFriendlyName,
					'IsGroup' => $mResponse->IsGroup,
					'ReadOnly' => $mResponse->ReadOnly,
					'Frequency' => $mResponse->Frequency
				));
			}
			else if ($mResponse instanceof \CContact)
			{
				$mResult = array_merge($this->objectWrapper($oAccount, $mResponse, $sParent, $aParameters), array(
					'IdUser' => $mResponse->IdUser,
					'IdContact' => $mResponse->IdContact,
					'IdContactStr' => $mResponse->IdContactStr,

					'PrimaryEmail' => $mResponse->PrimaryEmail,
					'UseFriendlyName' => $mResponse->UseFriendlyName,

					'GroupsIds' => $mResponse->GroupsIds,

					'FullName' => $mResponse->FullName,
					'Title' => $mResponse->Title,
					'FirstName' => $mResponse->FirstName,
					'LastName' => $mResponse->LastName,
					'NickName' => $mResponse->NickName,
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
			else if ($mResponse instanceof \CGroup)
			{
				$aContacts = $this->ApiContacts()->GetContactItems(
					$mResponse->IdUser, \EContactSortField::Name, \ESortOrder::ASC, 0, 99, '', '', $mResponse->IdGroup);

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
				$mResult = '['.get_class($mResponse).']';
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
