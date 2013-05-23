<?php

/*
 * Copyright (C) 2002-2012 AfterLogic Corp. (www.afterlogic.com)
 * Distributed under the terms of the license described in COPYING
 *
 */

	$CHARSETS = array(
		array('-1', 'Default'),
		array('iso-8859-6', 'Arabic Alphabet (ISO)'),
		array('windows-1256', 'Arabic Alphabet (Windows)'),
		array('iso-8859-4', 'Baltic Alphabet (ISO)'),
		array('windows-1257', 'Baltic Alphabet (Windows)'),
		array('iso-8859-2', 'Central European Alphabet (ISO)'),
		array('windows-1250', 'Central European Alphabet (Windows)'),
		array('euc-cn', 'Chinese Simplified (EUC)'),
		array('gb2312', 'Chinese Simplified (GB2312)'),
		array('big5', 'Chinese Traditional (Big5)'),
		array('iso-8859-5', 'Cyrillic Alphabet (ISO)'),
		array('koi8-r', 'Cyrillic Alphabet (KOI8-R)'),
		array('windows-1251', 'Cyrillic Alphabet (Windows)'),
		array('iso-8859-7', 'Greek Alphabet (ISO)'),
		array('windows-1253', 'Greek Alphabet (Windows)'),
		array('iso-8859-8', 'Hebrew Alphabet (ISO)'),
		array('windows-1255', 'Hebrew Alphabet (Windows)'),
		array('iso-2022-jp', 'Japanese'),
		array('shift-jis', 'Japanese (Shift-JIS)'),
		array('euc-kr', 'Korean (EUC)'),
		array('iso-2022-kr', 'Korean (ISO)'),
		array('iso-8859-3', 'Latin 3 Alphabet (ISO)'),
		array('windows-1254', 'Turkish Alphabet'),
		array('utf-7', 'Universal Alphabet (UTF-7)'),
		array('utf-8', 'Universal Alphabet (UTF-8)'),
		array('windows-1258', 'Vietnamese Alphabet (Windows)'),
		array('iso-8859-1', 'Western Alphabet (ISO)'),
		array('windows-1252', 'Western Alphabet (Windows)')
	);

	$TIMEZONE = array(
		'Default', #0
		'(GMT -12:00) Eniwetok, Kwajalein, Dateline Time', #1
		'(GMT -11:00) Midway Island, Samoa', #2
		'(GMT -10:00) Hawaii', #3
		'(GMT -09:00) Alaska', #4
		'(GMT -08:00) Pacific Time (US & Canada); Tijuana', #5
		'(GMT -07:00) Arizona', #6
		'(GMT -07:00) Mountain Time (US & Canada)', #7
		'(GMT -06:00) Central America', #8
		'(GMT -06:00) Central Time (US & Canada)', #9
		'(GMT -06:00) Mexico City, Tegucigalpa', #10
		'(GMT -06:00) Saskatchewan', #11
		'(GMT -05:00) Indiana (East)', #12
		'(GMT -05:00) Eastern Time (US & Canada)', #13
		'(GMT -05:00) Bogota, Lima, Quito', #14
		'(GMT -04:00) Santiago', #15
		'(GMT -04:00) Caracas, La Paz', #16
		'(GMT -04:00) Atlantic Time (Canada)', #17
		'(GMT -03:30) Newfoundland', #18
		'(GMT -03:00) Greenland', #19
		'(GMT -03:00) Buenos Aires, Georgetown', #20
		'(GMT -03:00) Brasilia', #21
		'(GMT -02:00) Mid-Atlantic', #22
		'(GMT -01:00) Cape Verde Is.', #23
		'(GMT -01:00) Azores', #24
		'(GMT) Casablanca, Monrovia', #25
		'(GMT) Dublin, Edinburgh, Lisbon, London', #26
		'(GMT +01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', #27
		'(GMT +01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', #28
		'(GMT +01:00) Brussels, Copenhagen, Madrid, Paris', #29
		'(GMT +01:00) Sarajevo, Skopje, Sofija, Warsaw, Zagreb', #30
		'(GMT +01:00) West Central Africa', #31
		'(GMT +02:00) Athens, Istanbul, Minsk', #32
		'(GMT +02:00) Bucharest', #33
		'(GMT +02:00) Cairo', #34
		'(GMT +02:00) Harare, Pretoria', #35
		'(GMT +02:00) Helsinki, Riga, Tallinn, Vilnius', #36
		'(GMT +02:00) Israel, Jerusalem Standard Time', #37
		'(GMT +03:00) Baghdad', #38
		'(GMT +03:00) Arab, Kuwait, Riyadh', #39
		'(GMT +03:00) East Africa, Nairobi', #40
		'(GMT +03:30) Tehran', #41
		'(GMT +04:00) Moscow, St. Petersburg, Volgograd', #42
		'(GMT +04:00) Abu Dhabi, Muscat', #43
		'(GMT +04:00) Baku, Tbilisi, Yerevan', #44
		'(GMT +04:30) Kabul', #45
		'(GMT +05:00) Islamabad, Karachi, Sverdlovsk, Tashkent', #46
		'(GMT +05:30) Calcutta, Chennai, Mumbai, New Delhi, India Standard Time', #47
		'(GMT +05:45) Kathmandu, Nepal', #48
		'(GMT +06:00) Ekaterinburg', #49
		'(GMT +06:00) Almaty, North Central Asia', #50
		'(GMT +06:00) Astana, Dhaka', #51
		'(GMT +06:00) Sri Jayawardenepura, Sri Lanka', #52
		'(GMT +06:30) Rangoon', #53
		'(GMT +07:00) Bangkok, Novosibirsk, Hanoi, Jakarta', #54
		'(GMT +08:00) Krasnoyarsk', #55
		'(GMT +08:00) Beijing, Chongqing, Hong Kong SAR, Urumqi', #56
		'(GMT +08:00) Ulaan Bataar', #57
		'(GMT +08:00) Kuala Lumpur, Singapore', #58
		'(GMT +08:00) Perth, Western Australia', #59
		'(GMT +08:00) Taipei', #60
		'(GMT +09:00) Osaka, Sapporo, Tokyo, Irkutsk', #61
		'(GMT +09:00) Seoul, Korea Standard time', #62
		'(GMT +09:30) Adelaide, Central Australia', #63
		'(GMT +09:30) Darwin', #64
		'(GMT +10:00) Yakutsk', #65
		'(GMT +10:00) Brisbane, East Australia', #66
		'(GMT +10:00) Canberra, Melbourne, Sydney, Hobart', #67
		'(GMT +10:00) Guam, Port Moresby', #68
		'(GMT +10:00) Hobart, Tasmania', #69
		'(GMT +11:00) Vladivostok', #70
		'(GMT +11:00) Solomon Is., New Caledonia', #71
		'(GMT +12:00) Auckland, Wellington, Magadan', #72
		'(GMT +12:00) Fiji Islands, Kamchatka, Marshall Is.', #73
		'(GMT +13:00) Nuku\'alofa, Tonga' #74
	);
