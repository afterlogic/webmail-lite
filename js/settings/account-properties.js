/*
 * Classes:
 *  CAccountTabsSwitcher(eParent)
 *  CAccountPropertiesPane(oParent)
 */

function CAccountTabsSwitcher(eParent)
{
	this._eAutoresponderTab = null;
	this._eContainer = null;
	this._eFiltersTab = null;
	this._eFoldersTab = null;
	this._eForwardTab = null;
	this._ePropertiesTab = null;
	this._eCustomTab = null;

	this._iCurrAccountTab = PART_ACCOUNT_PROPERTIES;

	this._build(eParent);
}

CAccountTabsSwitcher.prototype = {
	hide: function (iCurrAccountTab) {
		if (iCurrAccountTab !== undefined) {
			this._iCurrAccountTab = iCurrAccountTab;
		}
		this._eContainer.className = 'wm_hide';
	},

	show: function(bAllowFilters, bAllowAutoresponder, sTabLangField, iCurrAccountTab, bAllowForward) {
		if (iCurrAccountTab === undefined) {
			iCurrAccountTab = this._iCurrAccountTab;
		}
		else {
			this._iCurrAccountTab = iCurrAccountTab;
		}
		this._eContainer.className = 'wm_settings_accounts_info';

		if (WebMail.Settings.bAllowCustomEmailTab) {
			this._showAccountTab(this._eCustomTab, (iCurrAccountTab == PART_CUSTOM),
				PART_CUSTOM, 'CustomTitle');
		}
		else {
			this._eCustomTab.className = 'wm_hide';
		}
		
		this._showAccountTab(this._eFoldersTab, (iCurrAccountTab == PART_MANAGE_FOLDERS),
			PART_MANAGE_FOLDERS, 'ManageFolders');

		if (bAllowForward && (window.UseDb || window.UseLdapSettings)) {
			this._showAccountTab(this._eForwardTab, (iCurrAccountTab == PART_FORWARD),
				PART_FORWARD, 'ForwardTitle');
		}
		else {
		    this._eForwardTab.className = 'wm_hide';
		}

		if (bAllowAutoresponder && (window.UseDb || window.UseLdapSettings)) {
			this._showAccountTab(this._eAutoresponderTab, (iCurrAccountTab == PART_AUTORESPONDER),
				PART_AUTORESPONDER, 'AutoresponderTitle');
		}
		else {
		    this._eAutoresponderTab.className = 'wm_hide';
		}

		if (bAllowFilters && window.UseDb) {
			this._showAccountTab(this._eFiltersTab, (iCurrAccountTab == PART_FILTERS),
				PART_FILTERS, 'Filters');
		}
		else {
		    this._eFiltersTab.className = 'wm_hide';
		}

		if (window.UseDb && !WebMail.Settings.bAllowIdentities) {
			this._showAccountTab(this._eSignatureTab, (iCurrAccountTab == PART_SIGNATURE),
				PART_SIGNATURE, 'Signature');
		}
		else {
		    this._eSignatureTab.className = 'wm_hide';
		}

		if ((window.UseDb || window.UseLdapSettings) && sTabLangField !== '') {
			this._showAccountTab(this._ePropertiesTab, (iCurrAccountTab == PART_ACCOUNT_PROPERTIES),
				PART_ACCOUNT_PROPERTIES, sTabLangField);
		}
		else {
		    this._ePropertiesTab.className = 'wm_hide';
		}
	},

	_showAccountTab: function (eTab, bSelected, iEntity, sLangField)
	{
		eTab.innerHTML = '';
		if (bSelected) {
			eTab.className = 'wm_settings_switcher_select_item';
			eTab.innerHTML = Lang[sLangField];
		}
		else {
			eTab.className = 'wm_settings_switcher_item';
			var eSelectLink = CreateChild(eTab, 'a', [['href', '#']]);
			eSelectLink.innerHTML = Lang[sLangField];
			eSelectLink.onclick = function() {
				SetHistoryHandler(
					{
						ScreenId: SCREEN_USER_SETTINGS,
						iEditableAcctId: WebMail.Accounts.editableId,
						iEntity: iEntity,
						bNewMode: false
					}
				);
				return false;
			};
		}
	},

	_build: function (eParent)
	{
		this._eContainer = CreateChild(eParent, 'div', [['class', 'wm_hide']]);
		CreateChild(this._eContainer, 'div', [['class', 'wm_settings_switcher_indent']]);
		this._eFoldersTab = CreateChild(this._eContainer, 'div');
		this._eForwardTab = CreateChild(this._eContainer, 'div');
		this._eAutoresponderTab = CreateChild(this._eContainer, 'div');
		this._eFiltersTab = CreateChild(this._eContainer, 'div');
		this._eSignatureTab = CreateChild(this._eContainer, 'div');
		this._ePropertiesTab = CreateChild(this._eContainer, 'div');
		this._eCustomTab = CreateChild(this._eContainer, 'div');
	}
}

function CAccountPropertiesPane(oParent)
{
	this._oParent = oParent;

	this._oAcctProp = new CAccountProperties();
	this._oNewAcctProp = null;

	this.hasChanges = false;
	this.hasForAccountsChanges = false;
	this.shown = false;
	this._mainForm = null;
	this._parent = null;
	this._inboxSyncType = SYNC_TYPE_ALL_MSGS;
	this._protocol = IMAP4_PROTOCOL;

	this._accountInternalHide = null;
	this._friendlyNmObj = null;
	this._eFriendlyNameCont = null;
	this._emailObj = null;
	this._emailCont = null;
	
	this._mailIncPassCont = null;
	this._newPassObj = null;
	this._newPassCont = null;
	this._curPassObj = null;
	this._confirmPassObj = null;
	
	this._mailIncLoginCont = null;
	this._mailIncHostObj = null;
	this._mailMode0Obj = null;
	this._mailMode1Obj = null;
	this._mailMode2Obj = null;
	this._mailMode3Obj = null;
	this._mailsOnServerDaysObj = null;
	this._mailProtocolSpan = null;
	this._mailProtocolObj = null;
	this._mailModeCont = null;
	this._mailIncPortObj = null;
	this._mailIncLoginObj = null;
	this._mailIncPassObj = null;
	this._mailOutHostObj = null;
	this._mailOutPortObj = null;
	this._mailOutAuthObj = null;
	this._getmailAtLoginObj = null;
	this._getmailAtLoginCont = null;
	this._requiredFieldsCont = null;
	this._btnCancel = null;

	this._mailOutAuthCont = null;
	this._mailIncHostCont = null;
	this._mailOutHostCont = null;
}

CAccountPropertiesPane.prototype = {
	show: function(bNewMode)
	{
		this.shown = true;
		this._mainForm.className = (window.UseDb || window.UseLdapSettings) ? '' : 'wm_hide';
		this.hasChanges = false;
		this.fill(bNewMode);
	},

	hide: function()
	{
		this.shown = false;
		if (this.hasChanges) {
			Dialog.confirm(
				Lang.ConfirmSaveAcctProp,
				(function (obj) {
					return function () {
						obj.saveChanges();
					};
				})(this)
			);
		}
		this.hasChanges = false;
		this._mainForm.className = 'wm_hide';
	},

	applyNewAccountProperties: function ()
	{
		WebMail.Accounts.aplyNewAccountProperties(this._oNewAcctProp);
		WebMail.oIdentities.applyNewAccountName(this._oNewAcctProp);
		this.fill(false);
	},

	fill: function (bNewMode)
	{
		if (!this.shown) {
			return;
		}

		var oAcctProp = (bNewMode) ? new CAccountProperties() : WebMail.Accounts.getEditableAccount();
		this._oAcctProp.copy(oAcctProp);
		this.setProtocol(oAcctProp.mailProtocol);

		this._friendlyNmObj.value = HtmlDecode(oAcctProp.friendlyName);
		
		this._emailObj.value = HtmlDecode(oAcctProp.email);
		this._mailIncHostObj.value = HtmlDecode(oAcctProp.mailIncHost);
		this._mailIncPassObj.value = '';
		if (oAcctProp.bNew) {
			CleanNode(this._mailProtocolObj);
			var pop3Opt = CreateChild(this._mailProtocolObj, 'option', [['value', POP3_PROTOCOL]]);
			pop3Opt.innerHTML = Lang.Pop3;
			var imap4Opt = CreateChild(this._mailProtocolObj, 'option', [['value', IMAP4_PROTOCOL]]);
			imap4Opt.innerHTML = Lang.Imap4;
			imap4Opt.selected = true;
		}
		else {
			this._mailProtocolSpan.innerHTML = (this._protocol == POP3_PROTOCOL) ? Lang.Pop3 : Lang.Imap4;
		}
		this._mailIncPortObj.value = oAcctProp.mailIncPort;
		this._mailIncLoginObj.value = HtmlDecode(oAcctProp.mailIncLogin);
		this._curPassObj.value = '';
		this._newPassObj.value = '';
		this._confirmPassObj.value = '';

		this._mailOutHostObj.value = HtmlDecode(oAcctProp.mailOutHost);
		this._mailOutPortObj.value = oAcctProp.mailOutPort;
		this._mailOutAuthObj.checked = oAcctProp.mailOutAuth;
		this._getmailAtLoginObj.checked = oAcctProp.getMailAtLogin;
		switch (oAcctProp.mailMode) {
			case DELETE_MESSAGES_FROM_SERVER:
				this._mailMode0Obj.checked = true;
				this._mailMode1Obj.checked = false;
				this._mailMode2Obj.checked = false;
				this._mailMode3Obj.checked = false;
				break;
			case LEAVE_MESSAGES_ON_SERVER:
				this._mailMode0Obj.checked = false;
				this._mailMode1Obj.checked = true;
				this._mailMode2Obj.checked = false;
				this._mailMode3Obj.checked = false;
				break;
			case KEEP_MESSAGES_X_DAYS:
				this._mailMode0Obj.checked = false;
				this._mailMode1Obj.checked = true;
				this._mailMode2Obj.checked = true;
				this._mailMode3Obj.checked = false;
				break;
			case DELETE_MESSAGE_WHEN_REMOVED_FROM_TRASH:
				this._mailMode0Obj.checked = false;
				this._mailMode1Obj.checked = true;
				this._mailMode2Obj.checked = false;
				this._mailMode3Obj.checked = true;
				break;
			case KEEP_AND_DELETE_WHEN_REMOVED_FROM_TRASH:
				this._mailMode0Obj.checked = false;
				this._mailMode1Obj.checked = true;
				this._mailMode2Obj.checked = true;
				this._mailMode3Obj.checked = true;
				break;
		}
		this._mailsOnServerDaysObj.value = isNum(oAcctProp.mailsOnServerDays) ? oAcctProp.mailsOnServerDays : '7';

		this.setInboxSyncType(oAcctProp.folderList);
		this._setElementsHidding(oAcctProp.linked, oAcctProp.bNew);
		this.hasChanges = false;

		if (this._oParent) {
			this._oParent.resizeBody();
		}
	},//fill

	setInboxSyncType: function (folderList)
	{
		var inboxSyncType = SYNC_TYPE_ALL_MSGS;
		if (folderList != null && folderList.idAcct == this._oAcctProp.id) {
			inboxSyncType = folderList.inboxSyncType;
		}
		if (this._inboxSyncType == inboxSyncType) return;

		this._inboxSyncType = inboxSyncType;
		this._setDisabling();
		this._setHiddingPop3SyncSettings();
	},

	setProtocol: function (protocol)
	{
		if (this._protocol == protocol) return;
		
		this._protocol = protocol;
		if (this._protocol == POP3_PROTOCOL) {
			this._mailIncPortObj.value = POP3_PORT;
		}
		else {
			this._mailIncPortObj.value = IMAP4_PORT;
		}
		this._setHiddingPop3SyncSettings();
	},

	getTabLangField: function (bNewMode)
	{
		if (bNewMode) return '';
		var oAcctProp = WebMail.Accounts.getEditableAccount();
		if (WebMail.Settings.bAllowIdentities && (!WebMail.Settings.allowChangeAccountSettings || oAcctProp.linked)) {
			if (oAcctProp.allowChangePassword || WebMail.bIsDemo) {
				return 'TabChangePassword';
			}
			else {
				return '';
			}
		}
		return 'Properties';
	},

	_setElementsHidding: function (linked, newAccount)
	{
		this._emailCont.className = 'wm_hide';
		this._eFriendlyNameCont.className = 'wm_hide';
		this._mailIncLoginCont.className = 'wm_hide';
		this._mailIncPassCont.className = 'wm_hide';
		this._newPassCont.className = 'wm_hide';
		this._mailOutAuthCont.className = 'wm_hide';
		this._mailIncHostCont.className = 'wm_hide';
		this._mailOutHostCont.className = 'wm_hide';
		this._btnCancel.className = 'wm_hide';
		this._mailProtocolSpan.className = 'wm_hide';
		this._mailProtocolObj.className = 'wm_hide';
		this._requiredFieldsCont.className = 'wm_hide';

		if (!WebMail.Settings.bAllowIdentities) {
			this._eFriendlyNameCont.className = '';
		}
		if (!newAccount && (this._oAcctProp.allowChangePassword || WebMail.bIsDemo)) {
			this._newPassCont.className = '';
		}
		if (!newAccount && !WebMail.Settings.allowChangeAccountSettings) {
			return;
		}

		if (!linked) {
			this._mailIncLoginCont.className = '';
			this._mailIncHostCont.className = '';
			this._mailOutHostCont.className = '';
			this._mailOutAuthCont.className = '';
			this._requiredFieldsCont.className = 'wm_secondary_info';
		}
		if (newAccount || !linked) {
			this._mailIncPassCont.className = '';
		}
		if (newAccount) {
			this._emailCont.className = '';
			this._btnCancel.className = 'wm_button';
			this._mailProtocolObj.className = '';
		}
		else {
			this._mailProtocolSpan.className = '';
		}
	},

	_setHiddingPop3SyncSettings: function ()
	{
		this._mailModeCont.className = 'wm_hide';
		this._getmailAtLoginCont.className = 'wm_hide';
		if (!WebMail.Settings.allowChangeAccountSettings) return;
		
		var type = this._inboxSyncType;
		var msgsSync = (type == SYNC_TYPE_NEW_MSGS || type == SYNC_TYPE_ALL_MSGS);
		var headersSync = (type == SYNC_TYPE_NEW_HEADERS || type == SYNC_TYPE_ALL_HEADERS);
		if (this._protocol == POP3_PROTOCOL) {
			if (msgsSync) {
				this._mailModeCont.className = '';
			}
			if (msgsSync || headersSync) {
				this._getmailAtLoginCont.className = '';
			}
		}
	},

	_setDisabling: function ()
	{
		this._mailMode0Obj.disabled = true;
		this._mailMode1Obj.disabled = true;
		this._mailMode2Obj.disabled = true;
		this._mailMode3Obj.disabled = true;
		this._mailsOnServerDaysObj.disabled = true;
		var type = this._inboxSyncType;
		var msgsSync = (type == SYNC_TYPE_NEW_MSGS || type == SYNC_TYPE_ALL_MSGS);
		var headersSync = (type == SYNC_TYPE_NEW_HEADERS || type == SYNC_TYPE_ALL_HEADERS);
		if (headersSync || msgsSync) {
			if (msgsSync) {
				this._mailMode0Obj.disabled = false;
			}
			else {
				this._mailMode1Obj.checked = true;
			}
			this._mailMode1Obj.disabled = false;
			if (this._mailMode1Obj.checked) {
				this._mailMode2Obj.disabled = false;
				this._mailMode3Obj.disabled = false;
				if (this._mailMode2Obj.checked) {
					this._mailsOnServerDaysObj.disabled = false;
				}
			}
		}
	},

	_setInputKeyPress: function (inp)
	{
	    var obj = this;
		inp.onkeypress = function (ev) { if (isEnter(ev)) obj.saveChanges(); };
	},

	_getNewAccountProperties: function ()
	{
		var newAcctProp = new CAccountProperties();
	    newAcctProp.copy(this._oAcctProp);
		var bPasswordChanging = false;
		if (!newAcctProp.bNew && newAcctProp.allowChangePassword && !WebMail.bIsDemo) {
			newAcctProp.curPass = this._curPassObj.value;
			newAcctProp.mailIncPass = this._newPassObj.value;
			newAcctProp.confirmPass = this._confirmPassObj.value;
			bPasswordChanging = true;
		}

		newAcctProp.friendlyName = this._friendlyNmObj.value;

        if (WebMail.Settings.allowChangeAccountSettings) {
			newAcctProp.email = Trim(this._emailObj.value);

			newAcctProp.getMailAtLogin = this._getmailAtLoginObj.checked;

			newAcctProp.mailIncHost = Trim(this._mailIncHostObj.value);
			newAcctProp.mailIncPort = parseInt(Trim(this._mailIncPortObj.value));
			newAcctProp.mailIncLogin = Trim(this._mailIncLoginObj.value);
			if (newAcctProp.bNew || !bPasswordChanging && !newAcctProp.linked) {
				newAcctProp.mailIncPass = this._mailIncPassObj.value;
			}
			if (newAcctProp.bNew) {
				newAcctProp.mailProtocol = parseInt(this._mailProtocolObj.value);
			}

			newAcctProp.mailOutHost = Trim(this._mailOutHostObj.value);
			newAcctProp.mailOutPort = parseInt(Trim(this._mailOutPortObj.value));
			newAcctProp.mailOutAuth = this._mailOutAuthObj.checked;

			if (this._mailMode1Obj.checked && this._mailMode2Obj.checked) {
				newAcctProp.mailsOnServerDays = parseInt(Trim(this._mailsOnServerDaysObj.value));
				if (isNaN(newAcctProp.mailsOnServerDays) || newAcctProp.mailsOnServerDays < 1) {
					this._mailsOnServerDaysObj.value = 7;
				}
			}
			if (this._mailMode0Obj.checked) {
				newAcctProp.mailMode = 0;
			}
			else {
				if (this._mailMode2Obj.checked && this._mailMode3Obj.checked) {
					newAcctProp.mailMode = 4;
				}
				else if (this._mailMode3Obj.checked) {
					newAcctProp.mailMode = 3;
				}
				else if (this._mailMode2Obj.checked) {
					newAcctProp.mailMode = 2;
				}
				else {
					newAcctProp.mailMode = 1;
				}
			}
        }
		return newAcctProp;
	},

	_isCorrectDataForSaving: function (newAcctProp)
	{
		if (!newAcctProp.bNew && newAcctProp.allowChangePassword && !WebMail.bIsDemo) {
			var bPassChanged = (newAcctProp.mailIncPass.length > 0 || newAcctProp.confirmPass.length > 0);
			if (bPassChanged) {
				if (newAcctProp.curPass.length === 0) {
					Dialog.alert(Lang.AccountOldPasswordsDoNotMatch);
					return false;
				}
				if (newAcctProp.mailIncPass !== newAcctProp.confirmPass) {
					Dialog.alert(Lang.AccountPasswordsDoNotMatch);
					return false;
				}
			}
		}
		if (!WebMail.Settings.allowChangeAccountSettings) return true;

		if (Validator.isEmpty(newAcctProp.email)) {
			Dialog.alert(Lang.WarningEmailFieldBlank);
			return false;
		}
		if (!Validator.isCorrectEmail(newAcctProp.email)) {
			Dialog.alert(Lang.WarningCorrectEmail);
			return false;
		}

		if (Validator.isEmpty(newAcctProp.mailIncHost)) {
			Dialog.alert(Lang.WarningIncServerBlank);
			return false;
		}
		if (!Validator.isCorrectServerName(newAcctProp.mailIncHost)) {
			Dialog.alert(Lang.WarningCorrectIncServer);
			return false;
		}

		if (!Validator.isPort(newAcctProp.mailIncPort)) {
			Dialog.alert(Lang.WarningIncPortNumber + Lang.DefaultIncPortNumber);
			return false;
		}

		if (Validator.isEmpty(newAcctProp.mailIncLogin)) {
			Dialog.alert(Lang.WarningLoginFieldBlank);
			return false;
		}

		if (Validator.isEmpty(newAcctProp.mailOutHost)) {
			Dialog.alert(Lang.WarningOutServerBlank);
			return false;
		}
		if (!Validator.isCorrectServerName(newAcctProp.mailOutHost)) {
			Dialog.alert(Lang.WarningCorrectSMTPServer);
			return false;
		}

		if (!Validator.isPort(newAcctProp.mailOutPort)) {
			Dialog.alert(Lang.WarningOutPortNumber + Lang.DefaultOutPortNumber);
			return false;
		}

		if (newAcctProp.bNew) {
			if (Validator.isEmpty(newAcctProp.mailIncPass)) {
				Dialog.alert(Lang.WarningPassBlank);
				return false;
			}
		}

		return true;
	},

	saveChanges: function ()
	{
		var oNewAcctProp = this._getNewAccountProperties();
		var bCorrectData = this._isCorrectDataForSaving(oNewAcctProp);
		if (!bCorrectData) return;

		this._oNewAcctProp = oNewAcctProp;
		var sXml = oNewAcctProp.getInXml();
		var sRequestName = (oNewAcctProp.bNew) ? 'new' : 'update';
		RequestHandler(sRequestName, 'account', sXml);
		
		this.hasChanges = false;
	},

	buildPasswordTable: function (cont)
	{
		var obj = this;
		var div = CreateChild(cont, 'div', [['class', 'wm_settings_pass_frame']]);
		var tbl = CreateChild(div, 'table');
		var rowIndex = 0;
		
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td.style.width = '130px';
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.AccountOldPassword;
		WebMail.langChanger.register('innerHTML', td, 'AccountOldPassword', '', '');
		td = tr.insertCell(1);
		td.style.width = '368px';
		var inp = CreateChild(td, 'input', [['class', 'wm_input wm_settings_input'], ['type', 'password'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._curPassObj = inp;
		
		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.style.width = '130px';
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.AccountNewPassword;
		WebMail.langChanger.register('innerHTML', td, 'AccountNewPassword', '', '');
		td = tr.insertCell(1);
		td.style.width = '368px';
		inp = CreateChild(td, 'input', [['class', 'wm_input wm_settings_input'], ['type', 'password'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._newPassObj = inp;

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.AccountConfirmNewPassword;
		WebMail.langChanger.register('innerHTML', td, 'AccountConfirmNewPassword', '', '');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['class', 'wm_input wm_settings_input'], ['type', 'password'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._confirmPassObj = inp;
    },

	build: function(container, parent)
	{
		var span;
		this._parent = parent;
		var obj = this;
		this._mainForm = CreateChild(container, 'form');
		this._mainForm.onsubmit = function () { return false; };
		this._mainForm.className = 'wm_hide';
		var eEditZone = CreateChild(this._mainForm, 'div', [['class', 'wm_email_settings_edit_zone']]);
		var tbl = CreateChild(eEditZone, 'table');
		tbl.className = 'wm_settings_properties';

		var rowIndex = 0;
		var tr = tbl.insertRow(rowIndex++);
		var td = tr.insertCell(0);
		td.style.width = '145px';
		td = tr.insertCell(1);
		td.style.width = '280px';
		td = tr.insertCell(2);
		td.style.width = '95px';

		tr = tbl.insertRow(rowIndex++);
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = Lang.MailFriendlyName;
		WebMail.langChanger.register('innerHTML', td, 'MailFriendlyName', '');
		td = tr.insertCell(1);
		td.colSpan = 2;
		var inp = CreateChild(td, 'input', [['class', 'wm_input wm_settings_input'], ['type', 'text'], ['maxlength', '65']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; obj.hasForAccountsChanges = true; };
		this._friendlyNmObj = inp;
		this._eFriendlyNameCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = '* ' + Lang.MailEmail;
		WebMail.langChanger.register('innerHTML', td, 'MailEmail', '', '* ');
		td = tr.insertCell(1);
		td.colSpan = 2;
		inp = CreateChild(td, 'input', [['class', 'wm_input wm_settings_input'], ['type', 'text'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; obj.hasForAccountsChanges = true; };
		this._emailObj = inp;
		this._emailCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = '* ' + Lang.MailIncHost;
		WebMail.langChanger.register('innerHTML', td, 'MailIncHost', '', '* ');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['class', 'wm_input'], ['type', 'text'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._mailIncHostObj = inp;
		span = CreateChild(td, 'span');
		span.innerHTML = '&nbsp;';
		var sel = CreateChild(td, 'select');
		sel.className = 'wm_hide';
		sel.onchange = function () {
			obj.setProtocol(this.value - 0);
			obj.hasChanges = true;
		};
		this._mailProtocolObj = sel;
		span = CreateChild(td, 'span');
		span.className = 'wm_hide';
		this._mailProtocolSpan = span;
		td = tr.insertCell(2);
		span = CreateChild(td, 'span');
		span.innerHTML = '* ' + Lang.MailIncPort;
		WebMail.langChanger.register('innerHTML', span, 'MailIncPort', '', '* ');
		inp = CreateChild(td, 'input', [['class', 'wm_input wm_port_input'], ['type', 'text'], ['size', '3'], ['maxlength', '5']]);
		this._setInputKeyPress(inp);
		this._mailIncPortObj = inp;
		this._mailIncHostCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = '* ' + Lang.MailIncLogin;
		WebMail.langChanger.register('innerHTML', td, 'MailIncLogin', '', '* ');
		td = tr.insertCell(1);
		td.colSpan = 2;
		inp = CreateChild(td, 'input', [['class', 'wm_input wm_settings_input'], ['type', 'text'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._mailIncLoginObj = inp;
		this._mailIncLoginCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = '* ' + Lang.MailIncPass + ':';
		WebMail.langChanger.register('innerHTML', td, 'MailIncPass', ':', '* ');
		td = tr.insertCell(1);
		td.colSpan = 2;
		inp = CreateChild(td, 'input', [['class', 'wm_input wm_settings_input'], ['type', 'password'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onchange = function () { obj.hasChanges = true; };
		this._mailIncPassObj = inp;
		this._mailIncPassCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.colSpan = 3;
		this.buildPasswordTable(td);
		this._newPassCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.className = 'wm_settings_title';
		td.innerHTML = '* ' + Lang.MailOutHost;
		WebMail.langChanger.register('innerHTML', td, 'MailOutHost', '', '*');
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['class', 'wm_input'], ['type', 'text'], ['maxlength', '255']]);
		this._setInputKeyPress(inp);
		inp.onfocus = function () { if (this.value.length == 0) { this.value = obj._mailIncHostObj.value; this.select(); } };
		inp.onchange = function () { obj.hasChanges = true; };
		this._mailOutHostObj = inp;
		td = tr.insertCell(2);
		span = CreateChild(td, 'span');
		span.innerHTML = '* ' + Lang.MailOutPort;
		WebMail.langChanger.register('innerHTML', span, 'MailOutPort', '', '* ');
		inp = CreateChild(td, 'input', [['class', 'wm_input wm_port_input'], ['type', 'text'], ['size', '3'], ['maxlength', '5']]);
		this._setInputKeyPress(inp);
		this._mailOutPortObj = inp;
		this._mailOutHostCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.colSpan = 3;
		inp = CreateChild(td, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['id', 'mail_out_auth'], ['value', '1']]);
		var lbl = CreateChild(td, 'label', [['for', 'mail_out_auth']]);
		lbl.innerHTML = Lang.MailOutAuth1;
		WebMail.langChanger.register('innerHTML', lbl, 'MailOutAuth1', '', '');
		CreateChild(td, 'br');
		//removed
		//lbl = CreateChild(td, 'label');
		//lbl.innerHTML = Lang.MailOutAuth2;
		//lbl.className = 'wm_secondary_info wm_nextline_info';
		//WebMail.langChanger.register('innerHTML', lbl, 'MailOutAuth2', '', '');
		inp.onchange = function () { obj.hasChanges = true; };
		this._mailOutAuthObj = inp;
		this._mailOutAuthCont = tr;

		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.colSpan = 3;
		inp = CreateChild(td, 'input', [['class', 'wm_checkbox'], ['type', 'checkbox'], ['id', 'getmail_at_login'], ['value', '1']]);
		lbl = CreateChild(td, 'label', [['for', 'getmail_at_login']]);
		lbl.innerHTML = Lang.GetmailAtLogin;
		WebMail.langChanger.register('innerHTML', lbl, 'GetmailAtLogin', '', '');
		inp.onchange = function () { obj.hasChanges = true; };
		this._getmailAtLoginObj = inp;
		this._getmailAtLoginCont = tr;
		
		tr = tbl.insertRow(rowIndex++);
		tr.className = 'wm_hide';
		td = tr.insertCell(0);
		td.colSpan = 3;
		inp = CreateChild(td, 'input', [['class', 'wm_checkbox'], ['type', 'radio'], ['id', 'mail_mode_0'], ['name', 'mail_mode'], ['value', '1']]);
		lbl = CreateChild(td, 'label', [['for', 'mail_mode_0']]);
		lbl.innerHTML = Lang.MailMode0;
		WebMail.langChanger.register('innerHTML', lbl, 'MailMode0', '', '');
		inp.onclick = function () {
			if (this.checked) {
				obj._mailMode2Obj.disabled = true;
				obj._mailMode3Obj.disabled = true;
				obj._mailsOnServerDaysObj.disabled = true;
			}
			obj.hasChanges = true;
		};
		this._mailMode0Obj = inp;

		CreateChild(td, 'br');
		inp = CreateChild(td, 'input', [['class', 'wm_checkbox'], ['type', 'radio'], ['id', 'mail_mode_1'], ['name', 'mail_mode'], ['value', '1']]);
		lbl = CreateChild(td, 'label', [['for', 'mail_mode_1']]);
		lbl.innerHTML = Lang.MailMode1;
		WebMail.langChanger.register('innerHTML', lbl, 'MailMode1', '', '');
		inp.onclick = function () {
			if (this.checked) {
				obj._mailMode2Obj.disabled = false;
				if (obj._mailMode2Obj.checked) {
					obj._mailsOnServerDaysObj.disabled = false;
				}
				else {
					obj._mailsOnServerDaysObj.disabled = true;
				}
				obj._mailMode3Obj.disabled = false;
			}
			obj.hasChanges = true;
		};
		this._mailMode1Obj = inp;

		CreateChild(td, 'br');
		inp = CreateChild(td, 'input', [['class', 'wm_checkbox wm_settings_para'], ['type', 'checkbox'], ['id', 'mail_mode_2'], ['value', '1']]);
		lbl = CreateChild(td, 'label', [['for', 'mail_mode_2']]);
		lbl.innerHTML = Lang.MailMode2 + ' ';
		WebMail.langChanger.register('innerHTML', lbl, 'MailMode2', ' ', '');
		inp.onclick = function () {
			if (this.checked) {
				obj._mailsOnServerDaysObj.disabled = false;
			}
			else {
				obj._mailsOnServerDaysObj.disabled = true;
				if (!isNum(obj._mailsOnServerDaysObj.value) || obj._mailsOnServerDaysObj.value < 1) {
					obj._mailsOnServerDaysObj.value = '7';
				}
			}
			obj.hasChanges = true;
		};
		this._mailMode2Obj = inp;
		inp = CreateChild(td, 'input', [['class', 'wm_input'], ['type', 'text'], ['size', '1'], ['maxlength', '6']]);
		this._setInputKeyPress(inp);
		span = CreateChild(td, 'span');
		span.innerHTML = ' ' + Lang.MailsOnServerDays;
		WebMail.langChanger.register('innerHTML', span, 'MailsOnServerDays', '', ' ');
		inp.onchange = function () { obj.hasChanges = true; };
		this._mailsOnServerDaysObj = inp;
		this._mailsOnServerDaysObj.value = '7';
		CreateChild(td, 'br');
		inp = CreateChild(td, 'input', [['class', 'wm_checkbox wm_settings_para'], ['type', 'checkbox'], ['id', 'mail_mode_3'], ['value', '1']]);
		lbl = CreateChild(td, 'label', [['for', 'mail_mode_3']]);
		lbl.innerHTML = Lang.MailMode3;
		WebMail.langChanger.register('innerHTML', lbl, 'MailMode3', '', '');
		inp.onchange = function () { obj.hasChanges = true; };
		this._mailMode3Obj = inp;
		this._mailModeCont = tr;

		tbl = CreateChild(eEditZone, 'table');
		tbl.className = 'wm_settings_buttons';
		tr = tbl.insertRow(0);
		td = tr.insertCell(0);
		td.className = 'wm_secondary_info';
		td.innerHTML = Lang.InfoRequiredFields;
		WebMail.langChanger.register('innerHTML', td, 'InfoRequiredFields', '');
		this._requiredFieldsCont = td;
		td = tr.insertCell(1);
		inp = CreateChild(td, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Save]]);
		WebMail.langChanger.register('value', inp, 'Save', '', '');
		inp.onclick = function () {
			obj.saveChanges();
		};
		span = CreateChild(td, 'span');
		span.innerHTML = ' ';
		span.className = 'wm_hide';
		inp = CreateChild(span, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.Cancel]]);
		WebMail.langChanger.register('value', inp, 'Cancel', '', '');
		inp.onclick = function () {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_USER_SETTINGS,
					iEditableAcctId: WebMail.Accounts.editableId,
					iEntity: PART_ACCOUNT_PROPERTIES,
					bNewMode: false
				}
			);
		};
		this._btnCancel = span;
	}//build
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
