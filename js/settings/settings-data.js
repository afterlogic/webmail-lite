/*
 * Classes:
 *  CSettings()
 *  CAccountProperties()
 *  CFilters()
 *  CFilterProperties(idFolder, folderName) 
 *  CAutoresponderData()
 *  CForwardData()
 *  CMobileSyncData()
 */

function CSettings()
{
	this.type = TYPE_USER_SETTINGS;
	this.autoCheckMailInterval = null;
	this.contactsPerPage = null;
	this.layoutSide = null;
	this.msgsPerPage = null;
	this.defEditor = null;
	this.timeFormat = null;
	this.timeOffset = null;
	
	this.dateFormats = [];
	this.langs = Array();
	this.defDateFormat = null;
	this.defLang = null;
	this.defSkin = null;
	this.skins = Array();
}

CSettings.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},
	
	getInXml: function ()
	{
		var
			attrs = '',
			nodes = ''
		;
		
		if (this.autoCheckMailInterval != null) {
			attrs += ' auto_checkmail_interval="' + this.autoCheckMailInterval + '"';
		}
		if (this.contactsPerPage != null) {
			attrs += ' contacts_per_page="' + this.contactsPerPage + '"';
		}
		if (this.layoutSide != null) {
			attrs += ' layout="' + (this.layoutSide ? 0 : 1) + '"';
		}
		if (this.msgsPerPage != null) {
			attrs += ' msgs_per_page="' + this.msgsPerPage + '"';
		}
		if (this.defEditor != null) {
			attrs += ' def_editor="' + this.defEditor + '"';
		}
		if (this.timeFormat != null) {
			attrs += ' time_format="' + this.timeFormat + '"';
		}
		if (this.timeOffset != null) {
			attrs += ' def_timezone="' + this.timeOffset + '"';
		}
		
		if (this.defDateFormat != null) {
			nodes += '<def_date_fmt>' + GetCData(this.defDateFormat) + '</def_date_fmt>';
		}
		if (this.defLang != null) {
			nodes += '<def_lang>' + GetCData(this.defLang) + '</def_lang>';
		}
		if (this.defSkin != null) {
			nodes += '<def_skin>' + GetCData(this.defSkin) + '</def_skin>';
		}
		return '<settings' + attrs + '>' + nodes + '</settings>';
	},
	
	_readSettingsList: function (eRoot, sListName, sItemName)
	{
		var
			eList = XmlHelper.getFirstChildNodeByName(eRoot, sListName),
			iListIndex = 0,
			iListLen = 0,
			eItem = null,
			bDefItem = false,
			sItem = '',
			sDefItem = '',
			aItems = []
		;
		
		if (eList != null) {
			iListLen = eList.childNodes.length;
			for (; iListIndex < iListLen; iListIndex++) {
				eItem = eList.childNodes[iListIndex];
				if (eItem.tagName === sItemName) {
					bDefItem = XmlHelper.getBoolAttributeByName(eItem, 'def', false);
					sItem = XmlHelper.getFirstChildValue(eItem, '');
					if (sItem.length > 0) {
						aItems.push(sItem);
						if (bDefItem) {
							sDefItem = sItem;
						}
					}
				}
			}
			if (sDefItem.length === 0 && aItems.length > 0) {
				sDefItem = aItems[0];
			}
		}
		
		return { sDefItem: sDefItem, aItems: aItems }
	},
	
	getFromXml: function(rootElement)
	{
		var
			iLayout = 0,
			oSettingsList = null
		;
		
		this.autoCheckMailInterval = XmlHelper.getIntAttributeByName(rootElement, 'auto_checkmail_interval', this.autoCheckMailInterval);
		this.contactsPerPage = XmlHelper.getIntAttributeByName(rootElement, 'contacts_per_page', this.contactsPerPage);
		iLayout = XmlHelper.getIntAttributeByName(rootElement, 'layout', iLayout);
		this.layoutSide = (iLayout === 0) ? true : false;
		this.msgsPerPage = XmlHelper.getIntAttributeByName(rootElement, 'msgs_per_page', this.msgsPerPage);
		this.defEditor = XmlHelper.getIntAttributeByName(rootElement, 'def_editor', this.defEditor);
		this.timeFormat = XmlHelper.getIntAttributeByName(rootElement, 'time_format', this.timeFormat);
		this.timeOffset = XmlHelper.getIntAttributeByName(rootElement, 'def_timezone', this.timeOffset);
		this.dateFormat = XmlHelper.getIntAttributeByName(rootElement, 'def_date_fmt', this.dateFormat);

		oSettingsList = this._readSettingsList(rootElement, 'def_date_fmts', 'format');
		if (oSettingsList.aItems.length > 0) {
			this.dateFormats = oSettingsList.aItems;
			this.defDateFormat = oSettingsList.sDefItem;
		}
		
		oSettingsList = this._readSettingsList(rootElement, 'langs', 'lang');
		if (oSettingsList.aItems.length > 0) {
			this.langs = oSettingsList.aItems;
			this.defLang = oSettingsList.sDefItem;
		}
		
		oSettingsList = this._readSettingsList(rootElement, 'skins', 'skin');
		if (oSettingsList.aItems.length > 0) {
			this.skins = oSettingsList.aItems;
			this.defSkin = oSettingsList.sDefItem;
		}
	}
};

function CAccountProperties()
{
	this.type = TYPE_ACCOUNT_PROPERTIES;
//from attributes
	this.bAllowSorting = true;
	this.bNew = true;
	this.defOrder = SORT_ORDER_DESC;
	this.getMailAtLogin = true;
	this.id = -1;
	this.imapQuota = 0;
	this.imapQuotaLimit = 0;
	this.isInternal = false;
	this.linked = false;
	this.mailIncPort = IMAP4_PORT;
	this.mailMode = DELETE_MESSAGE_WHEN_REMOVED_FROM_TRASH;
	this.mailOutPort = SMTP_PORT;
	this.mailOutAuth = true;
	this.mailProtocol = IMAP4_PROTOCOL;
	this.mailsOnServerDays = 1;
//from nodes
	this.email = '';
	this.friendlyName = '';
	this.mailIncHost = '';
	this.mailIncLogin = '';
	this.mailOutHost = '';
//extensions
	this.allowSpamFolder = false;
	this.allowSpamLearning = false;
	this.allowChangePassword = false;
	this.allowAutoresponder = false;
	this.allowForward = false;
	this.bDisableManageSubscribe = false;
	this.bIgnoreSubscribeStatus = false;
	this.bSieveFilters = false;
//other
	this.mailIncPass = '';
	this.curPass = '';
	this.filters = null;
	this.folderList = null;
}

CAccountProperties.prototype = {
	getStringDataKeys: function ()
	{
		return this.id;
	},

	setImapQuota: function (acctImapQuota)
	{
		this.imapQuota = acctImapQuota.imapQuota;
		this.imapQuotaLimit = acctImapQuota.imapQuotaLimit;
	},
	
	getInXml: function ()
	{
		var sAttrs = '';
		if (this.id != -1) sAttrs += ' id="' + this.id + '"';
		sAttrs += (this.getMailAtLogin) ? ' getmail_at_login="1"' : ' getmail_at_login="0"';
		sAttrs += (this.isInternal) ? ' is_internal="1"' : ' is_internal="0"';
		sAttrs += ' mail_inc_port="' + this.mailIncPort + '"';
		sAttrs += ' mail_mode="' + this.mailMode + '"';
		sAttrs += ' mail_out_port="' + this.mailOutPort + '"';
		sAttrs += (this.mailOutAuth) ? ' mail_out_auth="1"' : ' mail_out_auth="0"';
		sAttrs += ' mail_protocol="' + this.mailProtocol + '"';
		sAttrs += ' mails_on_server_days="' + this.mailsOnServerDays + '"';

		var sNodes = '';
		sNodes += '<email>' + GetCData(this.email) + '</email>';
		sNodes += '<friendly_nm>' + GetCData(this.friendlyName) + '</friendly_nm>';
		sNodes += '<mail_inc_host>' + GetCData(this.mailIncHost) + '</mail_inc_host>';
		sNodes += '<mail_inc_login>' + GetCData(this.mailIncLogin) + '</mail_inc_login>';
		sNodes += '<mail_inc_pass>' + GetCData(this.mailIncPass) + '</mail_inc_pass>';
		sNodes += '<mail_out_host>' + GetCData(this.mailOutHost) + '</mail_out_host>';
		sNodes += '<cur_pass>' + GetCData(this.curPass) + '</cur_pass>';

		var sXml = '<account' + sAttrs + '>' + sNodes + '</account>';
		return sXml;
	},

	getFromXml: function (rootElement)
	{
		this.bNew = false;
		this.id = XmlHelper.getIntAttributeByName(rootElement, 'id', this.id);

		var emailNode = XmlHelper.getFirstChildNodeByName(rootElement, 'email');
		this.email = XmlHelper.getFirstChildValue(emailNode, this.email);

		var friendlyNmNode = XmlHelper.getFirstChildNodeByName(rootElement, 'friendly_name');
		this.friendlyName = XmlHelper.getFirstChildValue(friendlyNmNode, this.friendlyName);

		this.bAllowSorting = XmlHelper.getBoolAttributeByName(rootElement, 'allow_sorting', this.bAllowSorting);
		this.defOrder = XmlHelper.getIntAttributeByName(rootElement, 'def_order', this.defOrder);
		this.getMailAtLogin = XmlHelper.getBoolAttributeByName(rootElement, 'getmail_at_login', this.getMailAtLogin);
		this.isInternal = XmlHelper.getBoolAttributeByName(rootElement, 'is_internal', this.isInternal);
		this.linked = XmlHelper.getBoolAttributeByName(rootElement, 'linked', this.linked);
		this.mailIncPort = XmlHelper.getIntAttributeByName(rootElement, 'mail_inc_port', this.mailIncPort);
		this.mailMode = XmlHelper.getIntAttributeByName(rootElement, 'mail_mode', this.mailMode);
		this.mailOutPort = XmlHelper.getIntAttributeByName(rootElement, 'mail_out_port', this.mailOutPort);
		this.mailOutAuth = XmlHelper.getBoolAttributeByName(rootElement, 'mail_out_auth', this.mailOutAuth);
		this.mailProtocol = XmlHelper.getIntAttributeByName(rootElement, 'mail_protocol', this.mailProtocol);
		this.mailsOnServerDays = XmlHelper.getIntAttributeByName(rootElement, 'mails_on_server_days', this.mailsOnServerDays);

		var mailIncHostNode = XmlHelper.getFirstChildNodeByName(rootElement, 'mail_inc_host');
		this.mailIncHost = XmlHelper.getFirstChildValue(mailIncHostNode, this.mailIncHost);

		var mailIncLoginNode = XmlHelper.getFirstChildNodeByName(rootElement, 'mail_inc_login');
		this.mailIncLogin = XmlHelper.getFirstChildValue(mailIncLoginNode, this.mailIncLogin);

		var mailOutHostNode = XmlHelper.getFirstChildNodeByName(rootElement, 'mail_out_host');
		this.mailOutHost = XmlHelper.getFirstChildValue(mailOutHostNode, this.mailOutHost);

		var extensionsNode = XmlHelper.getFirstChildNodeByName(rootElement, 'extensions');
		this.allowSpamFolder = XmlHelper.getBoolAttributeByName(extensionsNode, 'AllowSpamFolderExtension', this.allowSpamFolder);
		this.allowSpamLearning = XmlHelper.getBoolAttributeByName(extensionsNode, 'AllowSpamLearningExtension', this.allowSpamLearning);
		this.allowChangePassword = XmlHelper.getBoolAttributeByName(extensionsNode, 'AllowChangePasswordExtension', this.allowChangePassword);
		this.allowAutoresponder = XmlHelper.getBoolAttributeByName(extensionsNode, 'AllowAutoresponderExtension', this.allowAutoresponder);
		this.allowForward = XmlHelper.getBoolAttributeByName(extensionsNode, 'AllowForwardExtension', this.allowForward);
		this.bDisableManageSubscribe = XmlHelper.getBoolAttributeByName(extensionsNode, 'DisableManageSubscribe', this.bDisableManageSubscribe);
		this.bIgnoreSubscribeStatus = XmlHelper.getBoolAttributeByName(extensionsNode, 'IgnoreSubscribeStatus', this.bIgnoreSubscribeStatus);
		if (this.bIgnoreSubscribeStatus) {
			this.bDisableManageSubscribe = true;
		}
		this.bSieveFilters = XmlHelper.getBoolAttributeByName(extensionsNode, 'AllowSieveFiltersExtension', this.bSieveFilters);
	},
	
	copy: function (acctProp)
	{
		this.bNew = acctProp.bNew;
		
		this.defOrder = acctProp.defOrder;
	    this.getMailAtLogin = acctProp.getMailAtLogin;
	    this.id = acctProp.id;
		this.isInternal = acctProp.isInternal;
	    this.linked = acctProp.linked;
	    this.mailIncPort = acctProp.mailIncPort;
	    this.mailMode = acctProp.mailMode;
	    this.mailOutPort = acctProp.mailOutPort;
	    this.mailOutAuth = acctProp.mailOutAuth;
		this.mailProtocol = acctProp.mailProtocol;
	    this.mailsOnServerDays = acctProp.mailsOnServerDays;

	    this.email = acctProp.email;
	    this.friendlyName = acctProp.friendlyName;
	    this.mailIncHost = acctProp.mailIncHost;
	    this.mailIncLogin = acctProp.mailIncLogin;
	    this.mailOutHost = acctProp.mailOutHost;

		this.allowChangePassword = acctProp.allowChangePassword;
	}
};

function CFilters() {
	this.type = TYPE_FILTERS;
	this.id = -1;
	this.items = [];
}

CFilters.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},

	getFromXml: function(rootElement)
	{
		this.id = XmlHelper.getIntAttributeByName(rootElement, 'id_acct', this.id);

		var filters = rootElement.childNodes;
		for (var i = 0; i < filters.length; i++) {
			var filterProp = new CFilterProperties();
			filterProp.getFromXml(filters[i]);
			this.items.push(filterProp);
		}
	}
};

function CFilterProperties(idFolder, folderName) {
//from attributes
	this.action = 3;
	this.applied = true;
	this.condition = 0;
	this.field = 0;
	this.id = -1;
	this.idFolder = -1;
	if (idFolder != undefined) {
		this.idFolder = idFolder;
	}
//from nodes
	this.value = '';
//other
	this.folderName = '';
	if (folderName != undefined) {
		this.folderName = folderName;
	}
	this.status = FILTER_STATUS_NEW;
}

CFilterProperties.prototype = {
	getStringDataKeys: function()
	{
		return this.id;
	},
	
	getInXml: function ()
	{
		if (this.status == FILTER_STATUS_REMOVED && this.id == -1) {
			return '';
		}
	
		var attrs = '';
		var value = '';
		if (this.status != FILTER_STATUS_NEW) {
			attrs += ' id="' + this.id + '"';
		}
		attrs += ' status="' + this.status + '"';
		if (this.status != FILTER_STATUS_REMOVED) {
			attrs += ' action="' + this.action + '"';
			attrs += ' applied="' + (this.applied ? '1' : '0') + '"';
			attrs += ' condition="' + this.condition + '"';
			attrs += ' field="' + this.field + '"';
			attrs += ' id_folder="' + this.idFolder + '"';
			value = GetCData(this.value);
		}

		var xml = '<filter' + attrs + '>' + value + '</filter>';
		return xml;
	},

	getFromXml: function (rootElement)
	{
		this.action = XmlHelper.getIntAttributeByName(rootElement, 'action', this.action);
		this.applied = XmlHelper.getBoolAttributeByName(rootElement, 'applied', this.applied);
		this.condition = XmlHelper.getIntAttributeByName(rootElement, 'condition', this.condition);
		this.field = XmlHelper.getIntAttributeByName(rootElement, 'field', this.field);
		this.id = XmlHelper.getIntAttributeByName(rootElement, 'id', this.id);
		this.idFolder = XmlHelper.getIntAttributeByName(rootElement, 'id_folder', this.idFolder);

		this.value = XmlHelper.getFirstChildValue(rootElement, this.value);

		this.status = FILTER_STATUS_UNCHANGED;
	}
};

function CAutoresponderData() {
	this.type = TYPE_AUTORESPONDER;
	this.enable = false;
	this.idAcct = -1;
	this.message = '';
	this.subject = '';
}

CAutoresponderData.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},

	getInXml: function ()
	{
		var attrs = (this.enable) ? ' enable="1"' : ' enable="0"';
		var nodes = '<subject>' + GetCData(this.subject) + '</subject>';
		nodes += '<message>' + GetCData(this.message) + '</message>';
		var xml = '<param name="id_acct" value="' + this.idAcct + '"/>';
		xml += '<autoresponder' + attrs + '>' + nodes + '</autoresponder>';
		return xml;
	},

	getFromXml: function (rootElement)
	{
		this.idAcct = XmlHelper.getIntAttributeByName(rootElement, 'id_acct', this.idAcct);
		this.enable = XmlHelper.getBoolAttributeByName(rootElement, 'enable', this.enable);

		var messageNode = XmlHelper.getFirstChildNodeByName(rootElement, 'message');
		this.message = HtmlDecode(XmlHelper.getFirstChildValue(messageNode, this.message));

		var subjectNode = XmlHelper.getFirstChildNodeByName(rootElement, 'subject');
		this.subject = HtmlDecode(XmlHelper.getFirstChildValue(subjectNode, this.subject));
	},

	copy: function (autoresponder)
	{
		this.enable = autoresponder.enable;
		this.subject = autoresponder.subject;
		this.message = autoresponder.message;
		this.idAcct = autoresponder.idAcct;
	}
};

function CForwardData() {
	this.type = TYPE_FORWARD;
	this.enable = false;
	this.idAcct = -1;
	this.email = '';
}

CForwardData.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},

	getInXml: function ()
	{
		var attrs = (this.enable) ? ' enable="1"' : ' enable="0"';
		var nodes = '<email>' + GetCData(this.email) + '</email>';
		var xml = '<param name="id_acct" value="' + this.idAcct + '"/>';
		xml += '<forward' + attrs + '>' + nodes + '</forward>';
		return xml;
	},

	getFromXml: function (rootElement)
	{
		this.idAcct = XmlHelper.getIntAttributeByName(rootElement, 'id_acct', this.idAcct);
		this.enable = XmlHelper.getBoolAttributeByName(rootElement, 'enable', this.enable);

		var emailNode = XmlHelper.getFirstChildNodeByName(rootElement, 'email');
		this.email = HtmlDecode(XmlHelper.getFirstChildValue(emailNode, this.email));

	},

	copy: function (autoresponder)
	{
		this.enable = autoresponder.enable;
		this.email = autoresponder.email;
		this.idAcct = autoresponder.idAcct;
	}
};
function CMobileSyncData()
{
	this.type = TYPE_MOBILE_SYNC;
	
	this.davEnable = false;
	this.davUrl = '';
	this.davLogin = '';
	this.davCalendars = [];
	this.davPersonalContacts = '';
	this.davCollectedAddresses = '';
	this.davGlobalAddressBook = '';
	
	this.activeEnable = false;
	this.activeServer = '';
	this.activeLogin = '';
}

CMobileSyncData.prototype = {
	getStringDataKeys: function ()
	{
		return '';
	},

	getFromXml: function(eRoot)
	{
		var
			eDavUrl = XmlHelper.getFirstChildNodeByName(eRoot, 'DavServer'),
			eDavLogin = XmlHelper.getFirstChildNodeByName(eRoot, 'DavLogin'),
			eDavPersonalContacts = XmlHelper.getFirstChildNodeByName(eRoot, 'DavPersonalContacts'),
			eDavCollectedAddresses = XmlHelper.getFirstChildNodeByName(eRoot, 'DavCollectedAddresses'),
			eDavGlobalAddressBook = XmlHelper.getFirstChildNodeByName(eRoot, 'DavGlobalAddressBook'),
			eActiveServer = XmlHelper.getFirstChildNodeByName(eRoot, 'ActiveServer'),
			eActiveLogin = XmlHelper.getFirstChildNodeByName(eRoot, 'ActiveSLogin')
		;
		
		this.davEnable = XmlHelper.getBoolAttributeByName(eRoot, 'enable_dav', this.davEnable);
		this.davUrl = XmlHelper.getFirstChildValue(eDavUrl, this.davUrl);
		this.davLogin = XmlHelper.getFirstChildValue(eDavLogin, this.davLogin);
		this._readCalendars(eRoot);
		this.davPersonalContacts = XmlHelper.getFirstChildValue(eDavPersonalContacts, this.davPersonalContacts);
		this.davCollectedAddresses = XmlHelper.getFirstChildValue(eDavCollectedAddresses, this.davCollectedAddresses);
		this.davGlobalAddressBook = XmlHelper.getFirstChildValue(eDavGlobalAddressBook, this.davGlobalAddressBook);
		
		this.activeEnable = XmlHelper.getBoolAttributeByName(eRoot, 'enable_activesync', this.activeEnable);
		this.activeServer = XmlHelper.getFirstChildValue(eActiveServer, this.activeSyncServer);
		this.activeLogin = XmlHelper.getFirstChildValue(eActiveLogin, this.activeSyncLogin);
	},
	
	_readCalendars: function (eRoot)
	{
		var
			eDavCalendars = XmlHelper.getFirstChildNodeByName(eRoot, 'DavCalendars'),
			aCalendars = (eDavCalendars !== null) ? eDavCalendars.childNodes : [],
			iIndex = 0,
			iLen = aCalendars.length,
			eCalendar = null,
			eName = null,
			sName = '',
			eUrl = null,
			sUrl = ''
		;
		
		this.davCalendars = [];
		for (; iIndex < iLen; iIndex++) {
			eCalendar = aCalendars[iIndex];
			eName = XmlHelper.getFirstChildNodeByName(eCalendar, 'Name'),
			sName = XmlHelper.getFirstChildValue(eName, '');
			eUrl = XmlHelper.getFirstChildNodeByName(eCalendar, 'Url'),
			sUrl = XmlHelper.getFirstChildValue(eUrl, '');
			this.davCalendars.push({ Name: sName, Url: sUrl });
		}
	}
};

//function CMobileSyncData()
//{
//	this.type = TYPE_MOBILE_SYNC;
//	this.login = '';
//	this.davUrl = '';
//	this.principalUrl = '';
//	this.bEmpty = true;
//}
//
//CMobileSyncData.prototype = {
//	getStringDataKeys: function ()
//	{
//		return '';
//	},
//
//    copy: function (mobileSync)
//    {
//		this.login = mobileSync.login;
//		this.davUrl = mobileSync.davUrl;
//		this.principalUrl = mobileSync.principalUrl;
//    },
//
//	getInXml: function ()
//	{
//		return '<mobile_sync></mobile_sync>';
//	},
//
//	getFromXml: function(rootElement)
//	{
//		var loginNode = XmlHelper.getFirstChildNodeByName(rootElement, 'login');
//		this.login = XmlHelper.getFirstChildValue(loginNode, this.login);
//		
//		var davUrlNode = XmlHelper.getFirstChildNodeByName(rootElement, 'dav_url');
//		this.davUrl = XmlHelper.getFirstChildValue(davUrlNode, this.davUrl);
//		
//		var principalUrlNode = XmlHelper.getFirstChildNodeByName(rootElement, 'principal_url');
//		this.principalUrl = XmlHelper.getFirstChildValue(principalUrlNode, this.principalUrl);
//		
//		this.bEmpty = (this.login === '' && this.davUrl === '' && this.principalUrl === '');
//	}
//};

function COutlookSyncData()
{
	this.type = TYPE_OUTLOOK_SYNC;
	this.login = '';
	this.davUrl = '';
	this.bEmpty = true;
}

COutlookSyncData.prototype = {
	getStringDataKeys: function ()
	{
		return '';
	},

    copy: function (outlookSync)
    {
		this.login = outlookSync.login;
		this.davUrl = outlookSync.davUrl;
    },

	getInXml: function ()
	{
		return '<outlook_sync></putlook_sync>';
	},

	getFromXml: function(rootElement)
	{
		var loginNode = XmlHelper.getFirstChildNodeByName(rootElement, 'login');
		this.login = XmlHelper.getFirstChildValue(loginNode, this.login);
		
		var davUrlNode = XmlHelper.getFirstChildNodeByName(rootElement, 'dav_url');
		this.davUrl = XmlHelper.getFirstChildValue(davUrlNode, this.davUrl);
		
		this.bEmpty = (this.login === '' && this.davUrl === '');
	}
};


if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
