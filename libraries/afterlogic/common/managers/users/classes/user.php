<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property int $IdUser
 * @property int $IdSubscription
 * @property int $IdHelpdeskUser
 * @property int $MailsPerPage
 * @property int $ContactsPerPage
 * @property int $AutoCheckMailInterval
 * @property int $CreatedTime
 * @property int $LastLogin
 * @property int $LastLoginNow
 * @property int $LoginsCount
 * @property string $DefaultSkin
 * @property string $DefaultLanguage
 * @property int $DefaultEditor
 * @property int $SaveMail
 * @property int $Layout
 * @property string $DefaultIncomingCharset
 * @property int $DefaultTimeZone
 * @property int $DefaultTimeFormat
 * @property string $DefaultDateFormat
 * @property string $Question1
 * @property string $Question2
 * @property string $Answer1
 * @property string $Answer2
 * @property string $Capa
 * @property string $ClientTimeZone
 * @property bool $UseThreads
 * @property bool $SaveRepliedMessagesToCurrentFolder
 * @property bool $DesktopNotifications
 * @property bool $AllowChangeInputDirection
 * @property bool $EnableOpenPgp
 * @property bool $AllowAutosaveInDrafts
 * @property bool $AutosignOutgoingEmails
 * @property bool $AllowHelpdeskNotifications
 * @property mixed $CustomFields
 * @property bool $SipEnable
 * @property string $SipImpi
 * @property string $SipPassword
 * @property string $TwilioNumber
 * @property bool $TwilioEnable
 * @property bool $TwilioDefaultNumber
 * @property bool $FilesEnable
 *
 * @package Users
 * @subpackage Classes
 */
class CUser extends api_AContainer
{
	/**
	 * @var CSubscription
	 */
	private $oSubCache;

	/**
	 * @return void
	 */
	public function __construct(CDomain $oDomain)
	{
		parent::__construct(get_class($this), 'IdUser');

		$oSettings =& CApi::GetSettings();
		$iSaveMail = $oSettings->GetConf('WebMail/SaveMail');
		$iSaveMail = ESaveMail::Always !== $iSaveMail
			? $oSettings->GetConf('WebMail/SaveMail') : ESaveMail::DefaultOn;

		$this->oSubCache = null;
		
		$this->__USE_TRIM_IN_STRINGS__ = true;
		$this->SetUpper(array('Capa'));

		$this->SetDefaults(array(
			'IdUser' => 0,
			'IdSubscription' => 0,
			'IdHelpdeskUser' => 0,

			'MailsPerPage'			=> $oDomain->MailsPerPage,
			'ContactsPerPage'		=> $oDomain->ContactsPerPage,
			'AutoCheckMailInterval'	=> $oDomain->AutoCheckMailInterval,

			'CreatedTime'	=> 0,
			'LastLogin'		=> 0,
			'LastLoginNow'	=> 0,
			'LoginsCount'	=> 0,

			'DefaultSkin'		=> $oDomain->DefaultSkin,
			'DefaultLanguage'	=> $oDomain->DefaultLanguage,
			'DefaultEditor'		=> EUserHtmlEditor::Html,
			'SaveMail'			=> $iSaveMail,
			'Layout'			=> $oDomain->Layout,

			'DefaultTimeZone'	=> 0, // $oDomain->DefaultTimeZone, // TODO
			'DefaultTimeFormat'	=> $oDomain->DefaultTimeFormat,
			'DefaultDateFormat'	=> $oDomain->DefaultDateFormat,

			'DefaultIncomingCharset' => CApi::GetConf('webmail.default-inc-charset', 'iso-8859-1'),

			'Question1'	=> '',
			'Question2'	=> '',
			'Answer1'	=> '',
			'Answer2'	=> '',

			'TwilioNumber'	=> '',
			'TwilioEnable'	=> true,
			'TwilioDefaultNumber'	=> false,
			'SipEnable'		=> true,
			'SipImpi'		=> '',
			'SipPassword'	=> '',

			'Capa'				=> '',
			'ClientTimeZone'	=> '',
			'UseThreads'		=> $oDomain->UseThreads,
			'SaveRepliedMessagesToCurrentFolder' => false,
			'DesktopNotifications' => false,
			'AllowChangeInputDirection' => false,
			'EnableOpenPgp' => false,
			'AllowAutosaveInDrafts' => true,
			'AutosignOutgoingEmails' => false,
			'AllowHelpdeskNotifications' => false,
			'CustomFields'		=> '',
			
			'FilesEnable'	=> true
		));

		CApi::Plugin()->RunHook('api-user-construct', array(&$this));
	}

	/**
	 * @todo
	 * @param string $sCapaName
	 *
	 * @return bool
	 */
	public function GetCapa($sCapaName)
	{
		return true;
		// TODO

		if (!CApi::GetConf('capa', false) || '' === $this->Capa ||
			0 === $this->IdSubscription)
		{
			return true;
		}

		$sCapaName = preg_replace('/[^A-Z0-9_=]/', '', strtoupper($sCapaName));

		$aCapa = explode(' ', $this->Capa);

		return in_array($sCapaName, $aCapa);
	}

	/**
	 * @return void
	 */
	public function AllowAllCapas()
	{
		$this->Capa = '';
	}

	/**
	 * @return void
	 */
	public function RemoveAllCapas()
	{
		$this->Capa = ECapa::NO;
	}

	/**
	 * @param CTenant $oTenant
	 * @param string $sCapaName
	 * @param bool $bValue
	 *
	 * @return bool
	 */
	public function SetCapa($oTenant, $sCapaName, $bValue)
	{
		if (!CApi::GetConf('capa', false) || !$oTenant)
		{
			return true;
		}

		// TODO subscriptions
//		$oSub = null;
//		if (0 < $this->IdSubscription)
//		{
//			if ($this->oSubCache && $this->IdSubscription === $this->oSubCache->IdSubscription)
//			{
//				$oSub = $this->oSubCache;
//			}
//			else
//			{
//				$oApiSubscriptionsManager = /* @var $oApiSubscriptionsManager CApiSubscriptionsManager */ CApi::Manager('subscriptions');
//				if ($oApiSubscriptionsManager)
//				{
//					$oSub = $oApiSubscriptionsManager->GetSubscriptionById($this->IdSubscription);
//					$oSub = $oSub && $this->IdSubscription === $oSub->IdSubscription ? $oSub : null;
//					if ($oSub)
//					{
//						$this->oSubCache = $oSub;
//					}
//				}
//			}
//		}
//
//		$sSubCapa = $oSub ? $oSub->Capa : $oTenant->Capa;
//
//		$sCapaName = preg_replace('/[^A-Z0-9_]/', '', strtoupper($sCapaName));
//		if ('' === $sSubCapa || false !== strpos($sSubCapa, $sCapaName))
//		{
//			if ($bValue && '' === $this->Capa)
//			{
//				$this->Capa = '';
//			}
//			else if ($bValue && 0 < strlen($this->Capa))
//			{
//				$aCapa = explode(' ', $this->Capa);
//				$aCapa[] = $sCapaName;
//				$this->Capa = 0 < count($aCapa) ? implode(' ', $aCapa) : ECapa::NO;
//			}
//			else if (!$bValue && '' === $this->Capa)
//			{
//				$aCapa = array();
//				if ('' === $sSubCapa)
//				{
//					$oApiTenantsManager = /* @var $oApiTenantsManager CApiTenantsManager */ CApi::Manager('tenants');
//					if ($oApiTenantsManager)
//					{
//						$oTenant = $oApiTenantsManager->GetTenantById($oTenant->IdTenant);
//						if ($oTenant)
//						{
//							if ('' === $oTenant->Capa)
//							{
//								$oApiCapabilityManager = /* @var $oApiCapabilityManager CApiCapabilityManager */ CApi::Manager('capability');
//								if ($oApiCapabilityManager)
//								{
//									$aCapa = explode(' ', $oApiCapabilityManager->GetSystemCapaAsString());
//								}
//							}
//							else
//							{
//								$aCapa = explode(' ', $oTenant->Capa);
//							}
//						}
//					}
//				}
//				else
//				{
//					$aCapa = explode(' ', $sSubCapa);
//				}
//
//				$aCapa = array_diff($aCapa, array($sCapaName));
//				$this->Capa = 0 < count($aCapa) ? implode(' ', $aCapa) : ECapa::NO;
//			}
//			else if (!$bValue && 0 < strlen($this->Capa))
//			{
//				$aCapa = explode(' ', $this->Capa);
//				$aCapa = array_diff($aCapa, array($sCapaName));
//				$this->Capa = 0 < count($aCapa) ? implode(' ', $aCapa) : ECapa::NO;
//			}
//		}
//		else
//		{
//			return false;
//		}
//
//		if ('' !== $this->Capa && ECapa::NO !== $this->Capa)
//		{
//			$aResult = array();
//			$aCapa = explode(' ', $this->Capa);
//			foreach ($aCapa as $sItem)
//			{
//				if ('' === $sSubCapa || false !== strpos($sSubCapa, $sItem))
//				{
//					$aResult[] = $sItem;
//				}
//			}
//
//			$aResult = array_unique($aResult);
//			$aResult = array_values($aResult);
//			$this->Capa = 0 < count($aResult) ? implode(' ', $aResult) : ECapa::NO;
//		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function Validate()
	{
		switch (true)
		{
			case false:
				throw new CApiValidationException(Errs::Validation_FieldIsEmpty, null, array(
					'{{ClassName}}' => 'CUser', '{{ClassField}}' => 'Error'));
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function GetMap()
	{
		return self::GetStaticMap();
	}

	/**
	 * @return array
	 */
	public static function GetStaticMap()
	{
		return array(

			'IdUser'			=> array('int', 'id_user'),
			'IdSubscription'	=> array('int', 'id_subscription'),
			'IdHelpdeskUser'	=> array('int', 'id_helpdesk_user'),

			'MailsPerPage'			=> array('int', 'msgs_per_page'),
			'ContactsPerPage'		=> array('int', 'contacts_per_page'),
			'AutoCheckMailInterval'	=> array('int', 'auto_checkmail_interval'),

			'CreatedTime'		=> array('datetime', 'created_time'),
			'LastLogin'			=> array('datetime', 'last_login', true, false),
			'LastLoginNow'		=> array('datetime', 'last_login_now', true, false),
			'LoginsCount'		=> array('int', 'logins_count', true, false),

			'DefaultSkin'		=> array('string(255)', 'def_skin'),
			'DefaultLanguage'	=> array('string(255)', 'def_lang'),
			'DefaultEditor'		=> array('int', 'def_editor'),
			'SaveMail'			=> array('int', 'save_mail'),
			'Layout'			=> array('int', 'layout'),

			'DefaultIncomingCharset'	=> array('string(30)', 'incoming_charset'),

			'DefaultTimeZone'	=> array('int', 'def_timezone'),
			'DefaultTimeFormat'	=> array('int', 'def_time_fmt'),
			'DefaultDateFormat'	=> array('string(100)', 'def_date_fmt'),
			'ClientTimeZone'	=> array('string(100)', 'client_timezone'),

			'Question1'	=> array('string(255)', 'question_1'),
			'Question2'	=> array('string(255)', 'question_2'),
			'Answer1'	=> array('string(255)', 'answer_1'),
			'Answer2'	=> array('string(255)', 'answer_2'),

			'SipEnable'				=> array('bool', 'sip_enable'),
			'SipImpi'				=> array('string', 'sip_impi'),
			'SipPassword'			=> array('password', 'sip_password'),
			'TwilioNumber'			=> array('string', 'twilio_number'),
			'TwilioEnable'			=> array('bool', 'twilio_enable'),
			'TwilioDefaultNumber'	=> array('bool', 'twilio_default_number'),

			'UseThreads'							=> array('bool', 'use_threads'),
			'SaveRepliedMessagesToCurrentFolder'	=> array('bool', 'save_replied_messages_to_current_folder'),
			'DesktopNotifications'					=> array('bool', 'desktop_notifications'),
			'AllowChangeInputDirection'				=> array('bool', 'allow_change_input_direction'),
			'AllowHelpdeskNotifications'			=> array('bool', 'allow_helpdesk_notifications'),

			'EnableOpenPgp'				=> array('bool', 'enable_open_pgp'),
			'AllowAutosaveInDrafts'		=> array('bool', 'allow_autosave_in_drafts'),
			'AutosignOutgoingEmails'	=> array('bool', 'autosign_outgoing_emails'),

			'Capa'				=> array('string(255)', 'capa'),
			'CustomFields'		=> array('serialize', 'custom_fields'),
			
			'FilesEnable'		=> array('bool', 'files_enable')
		);
	}
}
