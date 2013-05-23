<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 *
 */

/**
 * @property int $IdUser
 * @property int $MailsPerPage
 * @property int $ContactsPerPage
 * @property int $AutoCheckMailInterval
 * @property int $LastLogin
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
 * @property bool $AllowWebmail
 * @property bool $AllowContacts
 * @property bool $AllowCalendar
 * @property bool $UseCapa
 * @property string $Capa
 * @property mixed $CustomFields
 * @property int $ClientTimeOffset
 *
 * @package Users
 * @subpackage Classes
 */
class CUser extends api_AContainer
{
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

		$this->__USE_TRIM_IN_STRINGS__ = true;

		$this->SetDefaults(array(
			'IdUser' => 0,

			'MailsPerPage'			=> $oDomain->MailsPerPage,
			'ContactsPerPage'		=> $oDomain->ContactsPerPage,
			'AutoCheckMailInterval'	=> $oDomain->AutoCheckMailInterval,

			'LastLogin'		=> 0,
			'LoginsCount'	=> 0,

			'DefaultSkin'		=> $oDomain->DefaultSkin,
			'DefaultLanguage'	=> $oDomain->DefaultLanguage,
			'DefaultEditor'		=> EUserHtmlEditor::Html,
			'SaveMail'			=> $iSaveMail,
			'Layout'			=> $oDomain->Layout,

			'DefaultTimeZone'	=> $oDomain->DefaultTimeZone,
			'DefaultTimeFormat'	=> $oDomain->DefaultTimeFormat,
			'DefaultDateFormat'	=> $oDomain->DefaultDateFormat,

			'DefaultIncomingCharset' => CApi::GetConf('webmail.default-inc-charset', 'iso-8859-1'),

			'Question1'	=> '',
			'Question2'	=> '',
			'Answer1'	=> '',
			'Answer2'	=> '',

			'AllowWebmail'		=> $oDomain->AllowWebMail,
			'AllowContacts'		=> $oDomain->AllowContacts,
			'AllowCalendar'		=> $oDomain->AllowCalendar,

			'Capa'				=> '',
			'ClientTimeOffset'	=> 0,
			'CustomFields'		=> ''
		));

		CApi::Plugin()->RunHook('api-user-construct', array(&$this));
	}

	/**
	 * @param string $sCapaName
	 *
	 * @return bool
	 */
	public function GetCapa($sCapaName)
	{
		if (!CApi::GetConf('capa', false))
		{
			return true;
		}

		$sCapaName = preg_replace('/[^A-Z0-9_]/', '', strtoupper($sCapaName));

		$aCapa = explode(' ', $this->Capa);

		if (!in_array('ALL', $aCapa))
		{
			return in_array($sCapaName, $aCapa);
		}

		return true;
	}

	/**
	 * @return void
	 */
	public function AllowAllCapas()
	{
		$this->Capa = 'ALL';
	}

	/**
	 * @return void
	 */
	public function RemoveAllCapas()
	{
		$this->Capa = '';
	}

	/**
	 * @param string $sCapaName
	 * @param bool $bValue
	 *
	 * @return void
	 */
	public function SetCapa($sCapaName, $bValue)
	{
		$sCapaName = preg_replace('/[^A-Z0-9_]/', '', strtoupper($sCapaName));

		$aCapa = explode(' ', $this->Capa);

		if ($bValue)
		{
			$aCapa[] = $sCapaName;
		}
		else
		{
			$aCapa = array_diff($aCapa, array($sCapaName));
		}

		$aCapa = array_unique($aCapa);
		$aCapa = array_values($aCapa);

		$this->Capa = 0 < count($aCapa) ? implode(' ', $aCapa) : '';
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

			'IdUser' => array('int', 'id_user'),

			'MailsPerPage'			=> array('int', 'msgs_per_page'),
			'ContactsPerPage'		=> array('int', 'contacts_per_page'),
			'AutoCheckMailInterval'	=> array('int', 'auto_checkmail_interval'),

			'LastLogin'			=> array('datetime', 'last_login', true, false),
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
			'ClientTimeOffset'	=> array('int', 'client_timeoffset'),

			'Question1'	=> array('string(255)', 'question_1'),
			'Question2'	=> array('string(255)', 'question_2'),
			'Answer1'	=> array('string(255)', 'answer_1'),
			'Answer2'	=> array('string(255)', 'answer_2'),

			'AllowWebmail'		=> array('bool'),
			'AllowContacts'		=> array('bool'),
			'AllowCalendar'		=> array('bool'),

			'Capa'				=> array('string(255)', 'capa'),
			'CustomFields'		=> array('serialize', 'custom_fields')
		);
	}
}
