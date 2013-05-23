/*
 * Classes:
 *	CUserSettingsScreen()
 */

var SETTINGS_TAB_COMMON = 0;
var SETTINGS_TAB_ACCOUNTS = 1;
var SETTINGS_TAB_IDENTITIES = 2;
var SETTINGS_TAB_CALENDAR = 3;
var SETTINGS_TAB_MOBILE_SYNC = 4;
var SETTINGS_TAB_OUTLOOK_SYNC = 5;

var SettingsTabDescription = [];
SettingsTabDescription[SETTINGS_TAB_COMMON] = {x: 5 * X_ICON_SHIFT, y: 4 * Y_ICON_SHIFT,
	iEntity: PART_COMMON_SETTINGS, sLangField: 'Common', bDefault: true};
SettingsTabDescription[SETTINGS_TAB_ACCOUNTS] = {x: 3 * X_ICON_SHIFT, y: 4 * Y_ICON_SHIFT,
	iEntity: (window.UseDb || window.UseLdapSettings) ? PART_ACCOUNT_PROPERTIES : PART_MANAGE_FOLDERS, sLangField: 'EmailAccounts', bDefault: false};
SettingsTabDescription[SETTINGS_TAB_IDENTITIES] = {x: 9 * X_ICON_SHIFT, y: 5 * Y_ICON_SHIFT,
	iEntity: PART_IDENTITIES, sLangField: 'SettingsTabIdentities', bDefault: false};
SettingsTabDescription[SETTINGS_TAB_CALENDAR] = {x: 4 * X_ICON_SHIFT, y: 4 * Y_ICON_SHIFT,
	iEntity: PART_CALENDAR_SETTINGS, sLangField: 'SettingsTabCalendar', bDefault: false};
SettingsTabDescription[SETTINGS_TAB_MOBILE_SYNC] = {x: 8 * X_ICON_SHIFT, y: 5 * Y_ICON_SHIFT,
	iEntity: PART_MOBILE_SYNC, sLangField: 'SettingsTabMobileSync', bDefault: false};
SettingsTabDescription[SETTINGS_TAB_OUTLOOK_SYNC] = {x: 10 * X_ICON_SHIFT, y: 5 * Y_ICON_SHIFT,
	iEntity: PART_OUTLOOK_SYNC, sLangField: 'SettingsTabOutlookSync', bDefault: false};

function CUserSettingsScreen()
{
	this.id = SCREEN_USER_SETTINGS;
	this.isBuilded = false;
	this.bodyAutoOverflow = true;
	this.HistoryArgs = null;

	this._allowAutoresponder = true;
	this._allowFilters = true;
	this._allowForward = true;

	this.Settings = null;
	this.NewSettings = this.Settings;

	this.Autoresponders = [];
	this.Forwards = [];

	this._mainContainer = null;
	this._nav = null;
	this._cont = null;
	this._addAccountTbl = null;

	this._oAccountsTab = null;
	this._oIdentitiesTab = null;
	this._oCalendarTab = null;
	this._oCommonTab = null;
	this._oMobileSyncTab = null;
	this._oOutlookSyncTab = null;

	this._oCommonPane = new CCommonSettingsPane(this);
	if (WebMail.Settings.bAllowCustomEmailTab) {
		this._oCustomPane = new CCustomPane(this);
	}
	this._oAccountPropertiesPane = new CAccountPropertiesPane(this);
	this._oSignaturePane = new CSignaturePane(this);
	this._oFiltersPane = new CFiltersPane(this);
	this._oAutoresponderPane = new CAutoresponderPane(this);
	this._oForwardPane = new CForwardPane(this);
	this._oFoldersPane = new CManageFoldersPane(this);
	this._oAccountsPane = new CAccountListPane(this, this._oFoldersPane);
	this._oIdentitiesPane = new CIdentitiesPane(this);
	this._oCalendarPane = new CCalendarSettingsPane(this);
	this._oMobileSyncPane = new CMobileSyncSettingsPane(this);
	this._oOutlookSyncPane = new COutlookSyncSettingsPane(this);
	this._currPart = this._oCommonPane;

	this._oAccountTabsSwitcher = null;

	this.bNewMode = false;
}

CUserSettingsScreen.prototype = {
	placeData: function(data) {
		if (data) {
			switch (data.type) {
				case TYPE_ACCOUNT_LIST:
					this.bNewMode = false;
					var oAccount = WebMail.Accounts.getEditableAccount();
					this._allowFilters = oAccount.bSieveFilters;
					this._allowAutoresponder = oAccount.allowAutoresponder;
					this._allowForward = oAccount.allowForward;
					if (this._oAccountsPane.shown) {
						this._oAccountTabsSwitcher.show(this._allowFilters, this._allowAutoresponder, 
							this._oAccountPropertiesPane.getTabLangField(this.bNewMode), undefined, this._allowForward);
					}
					this._changeAccountId(data.editableId);
					if (data.addedId !== -1 && !WebMail.Settings.bAllowIdentities) {
						WebMail.oIdentities.addNewIdentity(WebMail.Accounts.getAccountById(data.addedId));
					}
					break;
				case TYPE_FOLDER_LIST:
					this._oAccountPropertiesPane.setInboxSyncType(data);
					this._oFiltersPane.FillFolders(data.folders);
					this.isChangedFolders = this._oFoldersPane.isChangedFolders;
					this._oFoldersPane.UpdateFolders(data);
					var oAccount = WebMail.Accounts.getEditableAccount();
					this._allowFilters = (data.inboxSyncType !== SYNC_TYPE_DIRECT_MODE
						|| oAccount.bSieveFilters);
					this._allowAutoresponder = oAccount.allowAutoresponder;
					this._allowForward = oAccount.allowForward;
					if (this._oAccountsPane.shown) {
						this._oAccountTabsSwitcher.show(this._allowFilters, this._allowAutoresponder, 
							this._oAccountPropertiesPane.getTabLangField(this.bNewMode), undefined, this._allowForward);
					}
					break;
				case TYPE_MOBILE_SYNC:
					this._oMobileSyncPane.SetSettings(data);
					break;
				case TYPE_OUTLOOK_SYNC:
					this._oOutlookSyncPane.SetSettings(data);
					break;
				case TYPE_USER_SETTINGS:
					this.Settings = data;
					this._oCommonPane.SetSettings(data);
					break;
				case TYPE_FILTERS:
					this._oFiltersPane.SetFilters(data.items);
					break;
				case TYPE_AUTORESPONDER:
					this.Autoresponders[data.idAcct] = data;
					if (data.idAcct === WebMail.Accounts.editableId) {
						this._oAutoresponderPane.SetAutoresponder(data);
					}
					break;
				case TYPE_FORWARD:
					this.Forwards[data.idAcct] = data;
					if (data.idAcct === WebMail.Accounts.editableId) {
						this._oForwardPane.setForward(data);
					}
					break;
				case TYPE_CUSTOM:
					this._oCustomPane.fill(data);
					break;
			} //switch
		}
	},
	
	isFoldersPaneOpen: function ()
	{
		return this._oFoldersPane.shown;
	},

	fillIdentities: function ()
	{
		if (WebMail.Settings.bAllowIdentities) {
			this._oIdentitiesPane.fill(false);
		}
		else {
			WebMail.showReport(Lang.ReportSignatureUpdatedSuccessfuly);
		}
	},

	applyNewAccountProperties: function() {
		this._oAccountPropertiesPane.applyNewAccountProperties();
	},

	GetNewSettings: function() {
		this.Settings = this._oCommonPane.GetNewSettings();
		return this.Settings;
	},

	SetNewAutoresponder: function() {
		var autoresponder = this._oAutoresponderPane.GetNewAutoresponder();
		this.Autoresponders[autoresponder.idAcct] = autoresponder;
	},

	setNewForward: function() {
		var oForward = this._oForwardPane.getNewForward();
		this.Forwards[oForward.idAcct] = oForward;
	},

	clickBody: function(ev) {
		this._oIdentitiesPane.clickBody();
		this._oSignaturePane.clickBody();
		this._oFiltersPane.clickBody(ev);
	},

	resizeBody: function() {
		if (this.isBuilded) {
			if (this._oIdentitiesPane.shown) {
				this._oIdentitiesPane.replaceHtmlEditorField();
			}
			if (this._oSignaturePane.shown) {
				this._oSignaturePane.replaceHtmlEditorField();
			}
			var iHeight = GetHeight() - WebMail.getHeaderHeight();
			if (iHeight < 300) {
				iHeight = 300;
			}
			if (this._cont.offsetHeight > iHeight) {
				iHeight = this._cont.offsetHeight;
			}
			else {
				iHeight -= GetTopBorderWidth(this._mainTable);
			}
			this._mainTable.style.height = iHeight + 'px';
		}
	},

	_showTabs: function (currentTabId, iCurrAccountTab)
	{
		var showCommon = ((window.UseDb || window.UseLdapSettings)
			&& WebMail.Settings.allowChangeInterfaceSettings);
        var showIdentities = (window.UseDb && WebMail.Settings.bAllowIdentities);
        var showCalendar = (window.UseDb && WebMail.Settings.allowCalendar
			&& WebMail.Settings.allowChangeInterfaceSettings);
        var showMobileSync = (WebMail.Settings.mobileSyncEnable);
        var showOutlookSync = (WebMail.Settings.outlookSyncEnable);

		this._oCommonTab.show(showCommon);
		this._oAccountsTab.show(true);
		this._oIdentitiesTab.show(showIdentities);
		this._oCalendarTab.show(showCalendar);
		this._oMobileSyncTab.show(showMobileSync);
		this._oOutlookSyncTab.show(showOutlookSync);

		if (!showCommon && currentTabId == SETTINGS_TAB_COMMON
			|| !showCalendar && currentTabId == SETTINGS_TAB_CALENDAR
			|| !showMobileSync && currentTabId == SETTINGS_TAB_MOBILE_SYNC
			|| !showOutlookSync && currentTabId == SETTINGS_TAB_OUTLOOK_SYNC) {
			currentTabId = SETTINGS_TAB_ACCOUNTS;
			iCurrAccountTab = PART_ACCOUNT_PROPERTIES;
		}

		var sAcctPropTabLangField = this._oAccountPropertiesPane.getTabLangField(this.bNewMode);
		if (currentTabId == SETTINGS_TAB_ACCOUNTS) {
			if (!this._allowFilters && iCurrAccountTab == PART_FILTERS
					|| !this._allowAutoresponder && iCurrAccountTab == PART_AUTORESPONDER) {
				iCurrAccountTab = PART_ACCOUNT_PROPERTIES;
			}
			if (!this.bNewMode && sAcctPropTabLangField === '' && iCurrAccountTab === PART_ACCOUNT_PROPERTIES) {
				iCurrAccountTab = PART_MANAGE_FOLDERS;
			}
		}

		var newPart;
		var showAccountsList = false;
		var showSettingsSwitcher = false;
		switch (currentTabId) {
			case SETTINGS_TAB_COMMON:
				this._oCommonTab.select();
				newPart = this._oCommonPane;
				break;
			case SETTINGS_TAB_ACCOUNTS:
				this._oAccountsTab.select();
				showAccountsList = true;
				showSettingsSwitcher = !this.bNewMode;
				switch (iCurrAccountTab) {
					case PART_ACCOUNT_PROPERTIES:
						newPart = this._oAccountPropertiesPane;
						break;
					case PART_SIGNATURE:
						newPart = this._oSignaturePane;
						break;
					case PART_FILTERS:
						newPart = this._oFiltersPane;
						break;
					case PART_AUTORESPONDER:
						newPart = this._oAutoresponderPane;
						break;
					case PART_FORWARD:
						newPart = this._oForwardPane;
						break;
					case PART_MANAGE_FOLDERS:
						newPart = this._oFoldersPane;
						break;
					case PART_CUSTOM:
						newPart = this._oCustomPane;
						break;
				}
				break;
			case SETTINGS_TAB_IDENTITIES:
				this._oIdentitiesTab.select();
				newPart = this._oIdentitiesPane;
				break;
			case SETTINGS_TAB_CALENDAR:
				this._oCalendarTab.select();
				newPart = this._oCalendarPane;
				break;
			case SETTINGS_TAB_MOBILE_SYNC:
				this._oMobileSyncTab.select();
				newPart = this._oMobileSyncPane;
				break;
			case SETTINGS_TAB_OUTLOOK_SYNC:
				this._oOutlookSyncTab.select();
				newPart = this._oOutlookSyncPane;
				break;
		}
		if (newPart == null) return;

		if (showSettingsSwitcher) {
			this._oAccountTabsSwitcher.show(this._allowFilters, this._allowAutoresponder, sAcctPropTabLangField, iCurrAccountTab, this._allowForward);
		}
		else {
			this._oAccountTabsSwitcher.hide(iCurrAccountTab);
		}
        if (showAccountsList) {
    		this._oAccountsPane.show();
            var addAccountClass = (WebMail.Settings.allowAddAccount && WebMail.Settings.allowChangeAccountSettings && window.UseDb)
                ? 'wm_settings_add_account_button' : 'wm_hide';
    		this._addAccountTbl.className = addAccountClass;
        }
        else {
    		this._oAccountsPane.hide();
    		this._addAccountTbl.className = 'wm_hide';
        }
		if (this._currPart != newPart) {
			this._currPart.hide();
		}
		this._currPart = newPart;
		this._currPart.show(this.bNewMode);
		
		if (currentTabId === SETTINGS_TAB_ACCOUNTS) {
			this._getAccountData();
		}
	},

	_getAccountData: function ()
	{
		var oAcctProp = WebMail.Accounts.getEditableAccount();
		if (oAcctProp === null) return;

		this._allowForward = oAcctProp.allowForward;
		if (this.Forwards[oAcctProp.id]) {
			this._oForwardPane.setForward(this.Forwards[oAcctProp.id]);
		}

		this._allowAutoresponder = oAcctProp.allowAutoresponder;
		if (this.Autoresponders[oAcctProp.id]) {
			this._oAutoresponderPane.SetAutoresponder(this.Autoresponders[oAcctProp.id]);
		}
		if (this._oAutoresponderPane && this._allowAutoresponder) {
			this._oAutoresponderPane.setSubjectView(false);
		}
	},

	_changeAccountId: function(id) {
		var oAcctProp = WebMail.Accounts.changeEditableAccount(id);
		this._showTabs(SETTINGS_TAB_ACCOUNTS, PART_ACCOUNT_PROPERTIES);
		if (oAcctProp === null) return;

		this._getAccountData();
		
		if (this._oFoldersPane.shown) {
			this._oFoldersPane.show(id);
		}
		GetHandler(TYPE_FOLDER_LIST, {idAcct: id, sync: GET_FOLDERS_NOT_CHANGE_ACCT}, [], '');
	},

	show: function(historyArgs) {
		if (this.isBuilded) {
			this._mainContainer.className = '';
			if (null != historyArgs) {
				if (historyArgs.bNewMode) { 
					historyArgs.bNewMode = false;
					historyArgs.iEditableAcctId = WebMail.Accounts.editableId;
				}
				this.restoreFromHistory(historyArgs);
			}
			else {
				if (window.UseDb || window.UseLdapSettings) {
					this._showTabs(SETTINGS_TAB_COMMON);
				}
				else {
					this._showTabs(SETTINGS_TAB_ACCOUNTS, PART_MANAGE_FOLDERS);
				}
			}
		}
		GetHandler(TYPE_FOLDER_LIST, {idAcct: WebMail.Accounts.editableId, sync: GET_FOLDERS_NOT_CHANGE_ACCT}, [], '');
	},

	restoreFromHistory: function(historyArgs) {
		this.HistoryArgs = historyArgs;
		switch (historyArgs.iEntity) {
			case PART_COMMON_SETTINGS:
				this._showTabs(SETTINGS_TAB_COMMON);
				break;
			case PART_ACCOUNT_PROPERTIES:
				this._changeAccountId(historyArgs.iEditableAcctId);
				this.bNewMode = historyArgs.bNewMode;
				this._showTabs(SETTINGS_TAB_ACCOUNTS, PART_ACCOUNT_PROPERTIES);
				break;
			case PART_SIGNATURE:
			case PART_FILTERS:
			case PART_MANAGE_FOLDERS:
			case PART_AUTORESPONDER:
			case PART_FORWARD:
			case PART_CUSTOM:
				this._changeAccountId(historyArgs.iEditableAcctId);
				this._showTabs(SETTINGS_TAB_ACCOUNTS, historyArgs.iEntity);
				break;
			case PART_IDENTITIES:
				this.bNewMode = historyArgs.bNewMode;
				this._showTabs(SETTINGS_TAB_IDENTITIES);
				break;
			case PART_CALENDAR_SETTINGS:
				this._showTabs(SETTINGS_TAB_CALENDAR);
				break;
			case PART_MOBILE_SYNC:
				this._showTabs(SETTINGS_TAB_MOBILE_SYNC);
				break;
			case PART_OUTLOOK_SYNC:
				this._showTabs(SETTINGS_TAB_OUTLOOK_SYNC);
				break;
		}
		if (Browser.mozilla) {
			var navHeight = this._nav.offsetHeight;
			var contHeight = this._cont.offsetHeight;
			if (navHeight > contHeight) {
				this._cont.style.height = navHeight + 'px';
			}
			else if (navHeight != contHeight) {
				this._nav.style.height = contHeight + 'px';
			}
			this._cont.style.height = 'auto';
			this._nav.style.height = 'auto';
		}
	},

	hide: function() {
		if (!this.isBuilded) return;
		
		this._oCommonPane.hide();
		if (WebMail.Settings.bAllowCustomEmailTab) {
			this._oCustomPane.hide();
		}
		this._oAccountsPane.hide();
		this._oCalendarPane.hide();
		this._oIdentitiesPane.hide();
		this._oAccountPropertiesPane.hide();
		this._oSignaturePane.hide();
		this._oFiltersPane.hide();
		this._oAutoresponderPane.hide();
		this._oForwardPane.hide();
		this._oFoldersPane.hide();
		this._oMobileSyncPane.hide();
		this._oOutlookSyncPane.hide();
		this._oAccountTabsSwitcher.hide();
		this._mainContainer.className = 'wm_hide';
		this._addAccountTbl.className = 'wm_hide';
	},

	build: function(container)
	{
		this._mainContainer = CreateChild(container, 'div');
		this._mainContainer.className = 'wm_hide';

		this._mainTable = CreateChild(this._mainContainer, 'div', [['class', 'wm_settings']]);
		var eRow = CreateChild(this._mainTable, 'div', [['class', 'wm_settings_row']]);
		this._cont = CreateChild(eRow, 'div', [['class', 'wm_settings_cont']]);
		this._nav = CreateChild(eRow, 'div', [['class', 'wm_settings_nav']]);
		CreateChild(eRow, 'div', [['class', 'clear']]);

		this._oCommonTab = new CNavigationTab(this._nav, SETTINGS_TAB_COMMON);
		this._oAccountsTab = new CNavigationTab(this._nav, SETTINGS_TAB_ACCOUNTS);
		this._oIdentitiesTab = new CNavigationTab(this._nav, SETTINGS_TAB_IDENTITIES);
		this._oCalendarTab = new CNavigationTab(this._nav, SETTINGS_TAB_CALENDAR);
		this._oMobileSyncTab = new CNavigationTab(this._nav, SETTINGS_TAB_MOBILE_SYNC);
		this._oOutlookSyncTab = new CNavigationTab(this._nav, SETTINGS_TAB_OUTLOOK_SYNC);

		var td = this._cont;
		this._oCommonPane.build(td);

		this._oAccountsPane.build(td);

		this._buildAddButtons(td);
		this._oAccountTabsSwitcher = new CAccountTabsSwitcher(td);
		
		var obj = this;
		if (WebMail.Settings.bAllowCustomEmailTab) {
			this._oCustomPane.build(td);
		}
		this._oAccountPropertiesPane.build(td, obj);
		this._oSignaturePane.build(td);
		this._oFiltersPane.build(td);
		this._oAutoresponderPane.build(td);
		this._oForwardPane.build(td);
		this._oFoldersPane.build(td);

		this._oIdentitiesPane.build(td);

		this._oCalendarPane.build(td);

		this._oMobileSyncPane.build(td);

		this._oOutlookSyncPane.build(td);

		this.isBuilded = true;

		this.resizeBody();
	},

	_buildAddButtons: function (eCont)
	{
		var tbl = CreateChild(eCont, 'table', [['class', 'wm_hide']]);
		this._addAccountTbl = tbl;
		var tr = tbl.insertRow(0);
		var td = tr.insertCell(0);

		var eAddAccountButton = CreateChild(td, 'input', [['class', 'wm_button'], ['type', 'button'], ['value', Lang.AddNewAccount]]);
		WebMail.langChanger.register('value', eAddAccountButton, 'AddNewAccount', '');
		eAddAccountButton.onclick = function() {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_USER_SETTINGS,
					iEditableAcctId: WebMail.Accounts.editableId,
					iEntity: PART_ACCOUNT_PROPERTIES,
					bNewMode: true
				}
			);
		};
	}
};

function CNavigationTab(eContainer, iItemId) {
	this._eItem = null;
	this._bShown = false;
	
	this._build(eContainer, iItemId);
}

CNavigationTab.prototype =
{
	show: function (bShow) {
		this._eItem.className = (bShow) ? 'wm_settings_item' : 'wm_hide';
		this._bShown = bShow;
	},

	select: function () {
		if (this._bShown) {
			this._eItem.className = 'wm_selected_settings_item';
		}
	},

	_build: function(eContainer, iItemId) {
		var oTabDesc = SettingsTabDescription[iItemId];

		this._eItem = CreateChild(eContainer, 'div');
		this._eItem.className = (oTabDesc.bDefault) ? 'wm_selected_settings_item' : 'wm_settings_item';

		var eIcon = CreateChild(this._eItem, 'span');
		eIcon.style.backgroundPosition = '-' + oTabDesc.x + 'px -' + oTabDesc.y + 'px';

		var eLink = CreateChild(this._eItem, 'a', [['href', 'javascript:void(0)']]);
		eLink.innerHTML = Lang[oTabDesc.sLangField];
		WebMail.langChanger.register('innerHTML', eLink, oTabDesc.sLangField, '');
		eLink.onclick = function () {
			SetHistoryHandler({
				ScreenId: SCREEN_USER_SETTINGS,
				iEditableAcctId: WebMail.Accounts.editableId,
				iEntity: oTabDesc.iEntity,
				bNewMode: false
			});
		};
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
