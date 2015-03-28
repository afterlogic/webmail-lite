<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

/**
 * @property string $Id
 * @property int $Type
 * @property string $TypeStr
 * @property string $Path
 * @property string $FullPath
 * @property string $Name
 * @property int $Size
 * @property bool $IsFolder
 * @property bool $IsLink
 * @property int $LinkType
 * @property string $LinkUrl
 * @property bool $LastModified
 * @property string $ContentType
 * @property bool $Thumb
 * @property bool $Iframed
 * @property string $ThumbnailLink
 * @property string $Hash
 * @property bool $Shared
 * @property string $Owner
 * @property string $Content
 * @property bool $IsExternal
 * 
 * @package FileStorage
 * @subpackage Classes
 */
class CFileStorageItem  extends api_AContainer
{
	public function __construct()
	{
		parent::__construct(get_class($this));

		$this->SetDefaults(array(
			'Id' => '',
			'Type' => \EFileStorageType::Personal,
			'TypeStr' => \EFileStorageTypeStr::Personal,
			'Path' => '',
			'FullPath' => '',
			'Name' => '',
			'Size' => 0,
			'IsFolder' => false,
			'IsLink' => false,
			'LinkType' => EFileStorageLinkType::Unknown,
			'LinkUrl' => '',
			'LastModified' => 0,
			'ContentType' => '',
			'Thumb' => false,
			'Iframed' => false,
			'ThumbnailLink' => '',
			'Hash' => '',
			'Shared' => false,
			'Owner' => '',
			'Content' => '',
			'IsExternal' => false
		));
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
			'Id' => array('string'),
			'Type' => array('int'),
			'TypeStr' => array('string'),
			'FullPath' => array('string'),
			'Path' => array('string'),
			'Name' => array('string'),
			'Size' => array('int'),
			'IsFolder' => array('bool'),
			'IsLink' => array('bool'),
			'LinkType' => array('int'),
			'LinkUrl' => array('string'),
			'LastModified' => array('int'),
			'ContentType' => array('string'),
			'Thumb' => array('bool'),
			'Iframed' => array('bool'),
			'ThumbnailLink' => array('string'),
			'Hash' => array('string'),
			'Shared' => array('bool'),
			'Owner' => array('string'),		
			'Content' => array('string'),
			'IsExternal' => array('bool')
		);
	}
}
