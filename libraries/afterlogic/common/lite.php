<?php

/**
 * Copyright (C) 2002-2013 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in LICENSE
 */

	$this->SetConf('WebMail/AllowUsersAddNewAccounts', false);
	$this->SetConf('Calendar/AllowCalendar', false);
	$this->SetConf('Contacts/GlobalAddressBook/Mode', EContactsGABMode::Off);
	$this->SetConf('Contacts/GlobalAddressBook/Sql/Visibility', EContactsGABVisibility::Off);
	
	