<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE.txt
 * 
 */

/**
 * @package Contacts
 */
class CApiContactsLdapConfig
{
	/**
	 * @var string
	 */
	protected $sHost;

	/**
	 * @var int
	 */
	protected $iPort;

	/**
	 * @var string
	 */
	protected $sSearchDn;

	/**
	 * @var string
	 */
	protected $sBindDn;

	/**
	 * @var string
	 */
	protected $sBindPassword;

	public function __construct($sHost, $iPort, $sSearchDn, $sBindDn, $sBindPassword)
	{
		$this->sHost = $sHost;
		$this->iPort = $iPort;
		$this->sSearchDn = $sSearchDn;
		$this->sBindDn = $sBindDn;
		$this->sBindPassword = $sBindPassword;
	}

	/**
	 * @return string
	 */
	public function Host()
	{
		return $this->sHost;
	}

	/**
	 * @return int
	 */
	public function Port()
	{
		return $this->iPort;
	}

	/**
	 * @return string
	 */
	public function SearchDn()
	{
		return $this->sSearchDn;
	}

	/**
	 * @return string
	 */
	public function BindDn()
	{
		return $this->sBindDn;
	}

	/**
	 * @return string
	 */
	public function BindPassword()
	{
		return $this->sBindPassword;
	}
}
