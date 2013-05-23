/*
 * Functions:
 *  Init()
 *  AllowSaveMessageToSent()
 *  AllowSaveMessageToDrafts()
 *  GetNewMessage()
 *  GetNearMessage(oMsgHeaders, oWindow)
 *  GetNearMessages(iId, sUid, oWindow)
 *  ShowReport(sReport)
 *  PrepareForwardAttachments(oMsg)
 *  IncMessageCountInDrafts(iCount)
 *  MarkMessageAsRead(sMsgId, iMsgSize)
 *	HideCalendar(link, helpParam)
 *	CreateAccountActionFunc(id)
 * Classes:
 *	CWebMail(title, sLookFor, sSearchFolderName)
 *	CSettingsList()
 */

function Init() {
	Browser = new CBrowser();
	if (Browser.ie && Browser.version < 7) {
		try {
			document.execCommand('BackgroundImageCache', false, true);
		} catch(e) {}
	}
	HtmlEditorField.build(!UseDb);
	var DataTypes = [
		new CDataType(TYPE_ACCOUNT_BASE, false, 0, false, {idAcct: 'id_acct', ChangeAcct: 'change_acct'}, 'account_base' ),
		new CDataType(TYPE_ACCOUNT_LIST, false, 0, false, { }, 'accounts' ),
		new CDataType(TYPE_ACCOUNT_PROPERTIES, false, 0, false, {idAcct: 'id_acct'}, 'account' ),
		new CDataType(TYPE_AUTORESPONDER, false, 0, false, {idAcct: 'id_acct'}, 'autoresponder' ),
		new CDataType(TYPE_BASE, false, 0, false, { }, 'base' ),
		new CDataType(TYPE_CONTACT, true, 20, false, {sContactId: 'id_addr'}, 'contact' ),
		new CDataType(TYPE_CONTACTS, true, 5, false, {page: 'page', sortField: 'sort_field', sortOrder: 'sort_order'}, 'contacts_groups' ),
		new CDataType(TYPE_FILTERS, false, 0, false, {idAcct: 'id_acct'}, 'filters' ),
		new CDataType(TYPE_FOLDERS_BASE, false, 0, false, { }, 'folders_base' ),
		new CDataType(TYPE_FOLDER_LIST, true, 10, false, {idAcct: 'id_acct', sync: 'sync'}, 'folders_list' ),
		new CDataType(TYPE_FORWARD, false, 0, false, {idAcct: 'id_acct'}, 'forward' ),
		new CDataType(TYPE_GLOBAL_CONTACT, true, 20, false, {sContactId: 'id_addr'}, 'global_contact' ),
		new CDataType(TYPE_GLOBAL_CONTACTS, true, 5, false, {page: 'page', sortField: 'sort_field', sortOrder: 'sort_order'}, 'global_contacts' ),
		new CDataType(TYPE_GROUP, true, 10, false, {sGroupId: 'id_group'}, 'group' ),
		new CDataType(TYPE_GROUPS, false, 0, false, { }, 'groups' ),
		new CDataType(TYPE_MESSAGE, true, 100, true, {id: 'id', charset: 'charset'}, 'message' ),
		new CDataType(TYPE_MESSAGES_BODIES, false, 0, false, { }, 'messages_bodies' ),
		new CDataType(TYPE_MESSAGES_OPERATION, false, 0, false, { }, '' ),
		new CDataType(TYPE_MESSAGE_LIST, true, 20, false, {idAcct: 'id_acct', page: 'page', sortField: 'sort_field', sortOrder: 'sort_order', iFilter: 'filter'}, 'messages' ),
		new CDataType(TYPE_MOBILE_SYNC, false, 0, false, { }, 'mobile_sync'),
		new CDataType(TYPE_MULTIPLE_CONTACTS, true, 5, false, {page: 'page', sortField: 'sort_field', sortOrder: 'sort_order'}, 'multiple_contacts' ),
		new CDataType(TYPE_OUTLOOK_SYNC, false, 0, false, { }, 'outlook_sync'),
		new CDataType(TYPE_SERVER_ATTACHMENT_LIST, false, 0, false, { }, 'server-attachment-list'),
		new CDataType(TYPE_SETTINGS_LIST, false, 0, false, { }, 'settings_list'),
		new CDataType(TYPE_USER_SETTINGS, false, 0, false, { }, 'settings')
	];

	var xmlText = '';
	var sLookFor = '';

	// search custom
    var paramsArray = new Array();
    var keyValueArray = new Array();
    var getRequestParams = new Array();

    var getRequest = location.search;
    if(getRequest != '')
    {
        paramsArray = (getRequest.substr(1)).split('&');
        for(var i=0; i < paramsArray.length; i++)
        {
            keyValueArray = paramsArray[i].split('=');
            getRequestParams[keyValueArray[0]] = keyValueArray[1];
        }
    }

	var mode = (getRequestParams['mode'] == 1) ? 1 : 0;
	if (getRequestParams['search'] && getRequestParams['search'].length > 0) {
		sLookFor = getRequestParams['search'];
	    xmlText = '<look_for fields="' + mode + '"><![CDATA[' + sLookFor + ']]></look_for>';
	}
	// end search custom

	WebMail = new CWebMail(Title, sLookFor, getRequestParams['folder']);
	WebMail.DataSource = new CDataSource( DataTypes, ActionUrl, ErrorHandler, LoadHandler, TakeDataHandler, ShowLoadingInfoHandler );
	HistoryStorage = new CHistoryStorage('HistoryStorage');

	if (Start) {
		WebMail.SetStartScreen(Start);
	}

	WebMail.DataSource.get(TYPE_BASE, { }, [], xmlText);

	setTimeout(CreateSessionSaver, 20000);

	window.onresize = ResizeBodyHandler;
	document.onkeyup = EventBodyHandler;

	HeaderInfo.init(Title);

	//ff
	$(window).bind('beforeunload', ConfirmBeforeUnload);

	//chrome, ie
	$(document.body).bind('beforeunload', function () {
			RemoveAllFlashes();
			ConfirmBeforeUnload();
		}
	);
}

function AllowSaveMessageToSent()
{
	return WebMail.allowSaveMessageToSent();
}

function AllowSaveMessageToDrafts()
{
	return WebMail.allowSaveMessageToDrafts();
}

function GetNewMessage()
{
	return window.NewMsg;
}

function GetNearMessage(oMsgHeaders, oWindow)
{
	return WebMail.getMessage(oMsgHeaders, oWindow);
}

function GetNearMessages(iId, sUid, oWindow)
{
	return WebMail.getNearMessages(iId, sUid, oWindow);
}

function ShowReport(sReport)
{
	WebMail.showReport(sReport);
}

function PrepareForwardAttachments(oMsg)
{
	if (oMsg.attachments.length > 0) {
		WebMail.requestMessageReplyPart(oMsg, true);
	}
}

function IncMessageCountInDrafts(iCount)
{
	var oListScreen = WebMail.Screens[WebMail.listScreenId];
	if (oListScreen) {
		oListScreen.incMessageCountInDrafts(iCount);
	}
}

function MarkMessageAsRead(sMsgId, iMsgSize)
{
	var oScreen = WebMail.getCurrentListScreen();
	if (oScreen === null || typeof oScreen.markMessageAsRead !== 'function') return;
	oScreen.markMessageAsRead(sMsgId, iMsgSize, true);
}

function HideCalendar(link, helpParam)
{
	switch (link) {
		case 'account':
			WebMail.showMail(helpParam);
		break;
		case 'contacts':
			WebMail.showContacts();
		break;
		case 'settings':
			WebMail.showSettings();
		break;
		case 'logout':
			WebMail.LogOut();
		break;
		case 'error':
			WebMail.LogOut(helpParam);
		break;
	}
}

function CreateAccountActionFunc(id)
{
	return function() {
		SetHistoryHandler(
			{
				ScreenId: WebMail.listScreenId,
				idAcct: id
			}
		);
	};
}

function CWebMail(title, sLookFor, sSearchFolderName){
	this.isBuilded = false;
	this.shown = false;
	this._focused = true;
	this.sLookFor = sLookFor;
	this.sSearchFolderName = sSearchFolderName;

	this.oIdentities = (typeof(CIdentities) === 'function') ? new CIdentities() : null;
	this.folderList = null;
	this.Accounts = null;
	this.SectionId = -1;
	this.Sections = Array();
	this.ScreenId = -1;
	this.Screens = Array();
	this.DataSource = null;
	this.ScriptLoader = new CScriptLoader();
	this.Settings = null;
	this.listScreenId = -1;
	this.startScreen = -1;
	this.ScreenIdForLoad = this.listScreenId;
	this._message = null;
	this._replyAction = -1;
	this._replyText = '';
	this.forEditParams = [];
	this.fromDraftsParams = [];
	this.foldersToUpdate = [];

	this._title = title;
	this.langChanger = new CLanguageChanger();
	this.bIsDemo = false;
	this._defOrder = SORT_ORDER_ASC;

	this._html = document.getElementById('html');
	this._content = document.getElementById('content');
	this.PopupMenus = null;
	this._skinLink = document.getElementById('skin');
	this._newSkinLink = null;
	this._rtlSkinLink = document.getElementById('skin-rtl');
	this._newRtlSkinLink = null;
	this._head = document.getElementsByTagName('head')[0];

	this._logo = null;
	this._accountsBar = null;
	this._accountControl = null;
	this._accountsList = null;
	this._accountNameObject = null;
	this._mailTab = null;
	this._contactsTab = null;
	this._calendarTab = null;
	this._settingsTab = null;

	this.fadeEffect = new CFadeEffect('WebMail.fadeEffect');
	this.InfoContainer = new CInfoContainer(this.fadeEffect);

	this.CheckMail = new CCheckMail();
	this.bHiddenCheckmail = false;
	this.bCheckmail = false;

	this.iAcctId = -1;
	this.bAccountChanged = false;
	this.HistoryArgs = null;
	this.HistoryObj = null;
	this.MailHistoryArgs = null;

	this._allowChangeSettings = true;

	this._gotFoldersMessageList = [];

	this.timer = null;
	this._checkMailInterval = null;
	this._checkRequestsInterval = null;

	this._aObjectsForUpdateFolders = [];
	this._oWaitMsgForNewWindow = null;
	this._oWaitMsgListForFirstMsg = null;
	this._oWaitMsgListForLastMsg = null;

	this.mouseX = 0;
	this.mouseY = 0;
	var obj = this;

	if (this._content) {
		this._content.onmousemove = function (e) {
			if (Browser.ie) { // grab the x-y pos.s if browser is ie
				obj.mouseX = event.clientX + document.body.scrollLeft;
				obj.mouseY = event.clientY + document.body.scrollTop;
			} else {  // grab the x-y pos.s if browser is NS
				obj.mouseX = e.pageX;
				obj.mouseY = e.pageY;
			}
			// catch possible negative values in NS4
			if (obj.mouseX < 0) {obj.mouseX = 0;}
			if (obj.mouseY < 0) {obj.mouseY = 0;}
		};
	}
}

CWebMail.prototype = {
	registerTentativeCalendarEvent: function (sUid)
	{
		if (!$.isArray(this.tentativeCalendarEvents)) {
			this.tentativeCalendarEvents = [];
		}
		this.tentativeCalendarEvents.push(sUid);
	},
	
	deleteTentativeCalendarEvent: function (sUid)
	{
		if ($.isArray(this.tentativeCalendarEvents)) {
			this.tentativeCalendarEvents = $.grep(this.tentativeCalendarEvents, function (sEventUid) {
				return sEventUid != sUid;
			});
		}
	},
	
	existsTentativeCalendarEvent: function (sUid)
	{
		if ($.isArray(this.tentativeCalendarEvents)) {
			return (-1 !== $.inArray(sUid, this.tentativeCalendarEvents));
		}
		return false;
	},
	
	registerCalendarEvent: function (sUid)
	{
		if (!$.isArray(this.existenCalendarEvents)) {
			this.existenCalendarEvents = [];
		}
		this.existenCalendarEvents.push(sUid);
	},
	
	deleteCalendarEvent: function (sUid)
	{
		if ($.isArray(this.existenCalendarEvents)) {
			this.existenCalendarEvents = $.grep(this.existenCalendarEvents, function (sEventUid) {
				return sEventUid != sUid;
			});
		}
		this.deleteTentativeCalendarEvent();
	},
	
	existsCalendarEvent: function (sUid)
	{
		return (-1 !== $.inArray(sUid, this.existenCalendarEvents));
	},
	
	registerForUpdateFolders: function (object)
	{
		this._aObjectsForUpdateFolders.push(object);
	},

	updateFoldersInRegisteredObjects: function (oFolderList)
	{
		for (var i = 0; i < this._aObjectsForUpdateFolders.length; i++) {
			this._aObjectsForUpdateFolders[i].updateFolders(oFolderList);
		}
	},

	_getMessageListFromCache: function (oKeyMsgList)
	{
		var sDataKeys = this.DataSource.getStringDataKeyFromObj(TYPE_MESSAGE_LIST, oKeyMsgList);
		var bExist = this.DataSource.cache.existsData(TYPE_MESSAGE_LIST, sDataKeys);
		if (bExist) {
			return this.DataSource.cache.getData(TYPE_MESSAGE_LIST, sDataKeys);
		}
		return null;
	},

	getNearMessages: function (iId, sUid, oWindow)
	{
		this._oWaitMsgListForFirstMsg = null;
		this._oWaitMsgListForLastMsg = null;
		var oNearMessages = null;
		var oListScreen = this.Screens[this.listScreenId];
		if (oListScreen) {
			var iPage = 1;
			do {
				var oKeyMsgList = oListScreen.oKeyMsgList.getNewByPage(iPage);
				var oMsgList = this._getMessageListFromCache(oKeyMsgList);
				if (oMsgList !== null) {
					oNearMessages = oMsgList.getNearMessages(iId, sUid);
				}
				if (oNearMessages !== null && oNearMessages.bFirstMsg && iPage > 1) {
					oKeyMsgList = oListScreen.oKeyMsgList.getNewByPage(iPage - 1);
					oMsgList = this._getMessageListFromCache(oKeyMsgList);
					if (oMsgList !== null) {
						oNearMessages.oPrevMsg = oMsgList.getLastMessage();
					}
					else {
						this._oWaitMsgListForLastMsg = oKeyMsgList;
						this._oWaitMsgListForLastMsg.oWindow = oWindow;
						GetMessageListHandler(REDRAW_NOTHING, null, oKeyMsgList, false);
					}
				}
				iPage = oKeyMsgList.getNextPage();
				if (oNearMessages !== null && oNearMessages.bLastMsg && iPage !== 0) {
					oKeyMsgList = oListScreen.oKeyMsgList.getNewByPage(iPage);
					oMsgList = this._getMessageListFromCache(oKeyMsgList);
					if (oMsgList !== null) {
						oNearMessages.oNextMsg = oMsgList.getFirstMessage();
					}
					else {
						this._oWaitMsgListForFirstMsg = oKeyMsgList;
						this._oWaitMsgListForFirstMsg.oWindow = oWindow;
						GetMessageListHandler(REDRAW_NOTHING, null, oKeyMsgList, false);
					}
				}
			} while (oMsgList !== null && oNearMessages === null && iPage !== 0)
		}
		if (oNearMessages === null) {
			return {oPrevMsg: null, oNextMsg: null};
		}
		return oNearMessages;
	},

	getMessage: function (oMsgHeaders, oWindow)
	{
		var
			sDataKeys = this.DataSource.getStringDataKeyFromObj(TYPE_MESSAGE, oMsgHeaders),
			aParts = [PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS],
			oListScreen = this.Screens[this.listScreenId],
			sMsgId = oMsgHeaders.getIdForList(oListScreen.id)
		;

		this._oWaitMsgForNewWindow = null;
		if (this.DataSource.cache.existsData(TYPE_MESSAGE, sDataKeys)) {
			if (!oMsgHeaders.read && !oMsgHeaders.isVoice) {
				oListScreen.markMessageAsRead(sMsgId, oMsgHeaders.size, WebMail.DataSource.lastFromCache);
				HeaderInfo.setNewMessagesCount();
			}
			return this.DataSource.cache.getData(TYPE_MESSAGE, sDataKeys);
		}

		this._oWaitMsgForNewWindow = oMsgHeaders;
		this._oWaitMsgForNewWindow.oWindow = oWindow;
		if (oListScreen && oListScreen.isDrafts()) {
			aParts.push(PART_MESSAGE_UNMODIFIED_PLAIN_TEXT);
		}
		GetMessageHandler(oMsgHeaders.id, oMsgHeaders.uid, oMsgHeaders.idFolder,
			oMsgHeaders.folderFullName, aParts, oMsgHeaders.charset, true);

		return null;
	},

	requestMessageListNextPage: function (bBackground)
	{
		var oListScreen = this.Screens[this.listScreenId];
		if (oListScreen) {
			var iPage = oListScreen.oKeyMsgList.getNextPage();
			if (iPage > 0) {
				var histObj = oListScreen.getCurrFolderHistoryObject();
				var oKeyMsgList = histObj.oKeyMsgList;
				var sXml = oKeyMsgList.getInXml();
				var bFromCache = GetHandler(TYPE_MESSAGE_LIST, {idAcct: WebMail.iAcctId, page: iPage, sortField: oKeyMsgList.iSortField,
					sortOrder: oKeyMsgList.iSortOrder, idFolder: oKeyMsgList.iFolderId,
					folderFullName: oKeyMsgList.sFolderFullName, lookFor: oKeyMsgList.sLookFor,
					SearchFields: oKeyMsgList.iSearchMode, iFilter: oKeyMsgList.iFilter}, [], sXml, bBackground );
				return !bFromCache;
			}
		}
		return false;
	},

	_requestOtherAccountsFolders: function ()
	{
		for (var i = (this.Accounts.items.length - 1); i >= 0; i--) {
			var id = this.Accounts.items[i].id;
			if (id != this.iAcctId) {
				var stringDataKey = WebMail.DataSource.getStringDataKey(TYPE_FOLDER_LIST, {idAcct: id});
				if (!WebMail.DataSource.cache.existsData(TYPE_FOLDER_LIST, stringDataKey)) {
					GetHandler(TYPE_ACCOUNT_BASE, {idAcct: id, ChangeAcct: 0}, [], '', true);
				}
			}
		}
	},

	requestMessageReplyPart: function (msg, background)
	{
		var xml = '<param name="uid">' + GetCData(HtmlDecode(msg.uid)) + '</param>';
		xml += '<folder id="' + msg.idFolder + '"><full_name>' + GetCData(msg.folderFullName) + '</full_name></folder>';
		var parts = (msg.hasHtml) ? [PART_MESSAGE_REPLY_HTML] : [PART_MESSAGE_REPLY_PLAIN];
		GetHandler(TYPE_MESSAGE, {id: msg.id, charset: msg.charset, uid: msg.uid,
			idFolder: msg.idFolder, folderFullName: msg.folderFullName}, parts, xml, background);
	},

	_onMessageSending: function (bSentMessageSaveError)
	{
		if (bSentMessageSaveError) {
			WebMail.showError(Lang.WarningSentEmailNotSaved);
		}
		ClearSentAndDraftsHandler();
		var oCurrScreen = this.Screens[this.ScreenId];
		if (oCurrScreen && this.ScreenId === SCREEN_NEW_MESSAGE) {
			SetHistoryHandler(
				{
					ScreenId: this.listScreenId
				}
			);
		}
		else if (oCurrScreen && this.ScreenId === SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
			oCurrScreen.slideAndShowReplyPane(true);
		}
	},

	_onMessageSaving: function (oSaveMsg)
	{
		var
			oCurrScreen = this.Screens[this.ScreenId],
			oDrafts = WebMail.getCurrentDraftsFolder(),
			oArgs = this.MailHistoryArgs
		;
		if (oCurrScreen && (this.ScreenId === SCREEN_NEW_MESSAGE || this.ScreenId === SCREEN_MESSAGE_LIST_CENTRAL_PANE)) {
			this.showReport(Lang.ReportMessageSaved);
			if (this.ScreenId === SCREEN_NEW_MESSAGE) {
				oCurrScreen.setMessageId(oSaveMsg.id, oSaveMsg.uid);
			}
			else if (this.ScreenId === SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
				oCurrScreen.slideAndShowReplyPane(true);
			}
		}
		ClearDraftsAndSetMessageId(oSaveMsg.id, oSaveMsg.uid, true);
		if (oSaveMsg.id === -1 && oSaveMsg.uid === '') {
			WebMail.Settings.disableAutoSave();
			if (this.ScreenId === SCREEN_NEW_MESSAGE && !oSaveMsg.bAutosave) {
				SetHistoryHandler(
					{
						ScreenId: this.listScreenId,
						idFolder: null
					}
				);
			}
		}
		oArgs.fromDrafts = true;
		oArgs.MsgFolderId = oDrafts.id;
		oArgs.MsgFolderFullName = oDrafts.fullName;
		oArgs.MsgId = oSaveMsg.id;
		oArgs.MsgUid = oSaveMsg.uid;
		oArgs.MsgParts = [PART_MESSAGE_HEADERS, PART_MESSAGE_HTML, PART_MESSAGE_UNMODIFIED_PLAIN_TEXT, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS];
		oArgs.MsgCharset = AUTOSELECT_CHARSET;
	},

	placeData: function(data)
	{
		switch (data.type) {
			case TYPE_DOSSIERS_DATA:
				this.Settings.setDigDosData(data);
				if (typeof DigDosDialog === 'object') {
					DigDosDialog.setDossiers(data);
				}
				break;
			case TYPE_MESSAGES_BODIES:
				PreFetch.getMessagesBodies();
				if (!PreFetch.bStartedMessagesBodies) {
					if (!this.requestMessageListNextPage(true)) {
						this.RequestFoldersMessageList();
					}
//					this._requestOtherAccountsFolders();
				}
				break;
			case TYPE_ACCOUNT_LIST:
				if (this.Accounts != null && this.Accounts.count > 0 && data.count > this.Accounts.count) {
					this.showReport(Lang.ReportAccountCreatedSuccessfuly);
				}
				if (this.Accounts != null && data.items.length == 0) {
					document.location = LoginUrl;
				} else {
					if (this.Accounts != null && data.items.length != this.Accounts.items.length ||
					 this.iAcctId != data.currId) {
						var screen = this.Screens[SCREEN_CALENDAR];
						if (screen) screen.needReload();
						screen = this.Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE];
						if (screen) screen.needToRefreshFolders = true;
						screen = this.Screens[SCREEN_MESSAGE_LIST_TOP_PANE];
						if (screen) screen.needToRefreshFolders = true;
					}
					this.Accounts = data;
					if (this.iAcctId !== data.currId) {
						this.bAccountChanged = (this.iAcctId !== -1) ? true : false;
						this.iAcctId = data.currId;
					}
					else {
						this.bAccountChanged = false;
					}
					this.FillAccountsList();
					var screen = this.Screens[SCREEN_USER_SETTINGS];
					if (screen) {
						screen.placeData(data);
					}
				}
				break;
			case TYPE_IDENTITIES:
				this.oIdentities = data;
				var oSettingsScreen = this.Screens[SCREEN_USER_SETTINGS];
				if (oSettingsScreen) {
					oSettingsScreen.fillIdentities();
				}
				break;
			case TYPE_SETTINGS_LIST:
				this.Settings = data;
				this.SetActiveTab();
				this.listScreenId = (this.Settings.layoutSide)
					? SCREEN_MESSAGE_LIST_CENTRAL_PANE : SCREEN_MESSAGE_LIST_TOP_PANE;
				this.startCheckMailInterval();
				if (this.ScreenId == -1) {
					if (this.startScreen != -1) {
						SelectScreenHandler(this.startScreen);
					} else {
						if (this.listScreenId != -1) {
							SelectScreenHandler(this.listScreenId);
						} else {
							SelectScreenHandler(SCREEN_MESSAGE_LIST_CENTRAL_PANE);
						}
					}
				}
				break;
			case TYPE_FILTERS:
				this.Accounts.updateFilters(data);
				var screen = this.Screens[SCREEN_USER_SETTINGS];
				if (screen) {
					screen.placeData(data);
				}
				break;
			case TYPE_UPDATE:
				switch (data.value) {
					case 'account_properties':
						var acctProp = data.accountProperties;
						if (acctProp != null) {
							this.Accounts.setAccountImapQuota(acctProp);
							var listScreen = this.Screens[this.listScreenId];
							if (listScreen) {
								listScreen.FillSpaceInfo();
							}
						}
						break;
					case 'notification_settings':
						this.showReport(Lang.ReportSettingsUpdatedSuccessfuly);
						break;
					case 'settings':
						this.showReport(Lang.ReportSettingsUpdatedSuccessfuly);
						var screen = this.Screens[SCREEN_USER_SETTINGS];
						if (screen) {
							var settings = screen.GetNewSettings();
							this.ApplyNewSettings(settings);
							this.Settings.copy(settings);
						}
						break;
					case 'account':
						this.showReport(Lang.ReportAccountUpdatedSuccessfuly);
						var screen = this.Screens[SCREEN_USER_SETTINGS];
						if (screen) {
							screen.applyNewAccountProperties();
						}
						break;
					case 'autoresponder':
						this.showReport(Lang.ReportAutoresponderUpdatedSuccessfuly);
						var screen = this.Screens[SCREEN_USER_SETTINGS];
						if (screen) {
							screen.SetNewAutoresponder();
						}
						break;
					case 'forward':
						this.showReport(Lang.ReportForwardUpdatedSuccessfuly);
						var screen = this.Screens[SCREEN_USER_SETTINGS];
						if (screen) {
							screen.setNewForward();
						}
						break;
					case 'send_confirmation':
						this.showReport(Lang.ReportMessageSent);
						break;
/*for demo*/
					case 'send_message_demo':
						this.showReport('To prevent abuse, no more than 3 e-mail addresses per message is allowed in this demo. All addresses except the first 3 have been discarded.', 10000);
						this._onMessageSending(data.bSentMessageSaveError);
						break;
/*end for demo*/
					case 'send_message':
						this.showReport(Lang.ReportMessageSent);
						this._onMessageSending(data.bSentMessageSaveError);
						break;
					case 'save_message':
						this._onMessageSaving(data.saveMessage);
						break;
					case 'group':
					case 'sync_contacts':
						var screen = this.Screens[SCREEN_CONTACTS];
						if (screen) screen.placeData(data);
						break;
					case 'save_ics':
					case 'process_appointment':
						if (data.sEventUid.length > 0) {
							this.registerCalendarEvent(data.sEventUid);
							var screen = this.Screens[this.listScreenId];
							if (screen) screen.placeData(data);
						}
						break;
					case 'save_vcf':
						if (data.sContactUid.length > 0) {
							var screen = this.Screens[this.listScreenId];
							if (screen) screen.placeData(data);
							
							screen = this.Screens[SCREEN_CONTACTS];
							screen.clearContactsAndGroups();
						}
						break;
				}
			break;
			case TYPE_FOLDER_LIST:
				this._placeFolderList(data);
				this.updateFoldersInRegisteredObjects(data);
				break;
			case TYPE_MESSAGE_LIST:
				if (this._oWaitMsgListForFirstMsg !== null && this._oWaitMsgListForFirstMsg.isEqualMsgList(data)) {
					var oNextMsg = data.getFirstMessage();
					this._oWaitMsgListForFirstMsg.oWindow.PreviewPane.setNextMessage(oNextMsg);
					this._oWaitMsgListForFirstMsg = null;
					return;
				}
				if (this._oWaitMsgListForLastMsg !== null && this._oWaitMsgListForLastMsg.isEqualMsgList(data)) {
					var oPrevMsg = data.getLastMessage();
					this._oWaitMsgListForLastMsg.oWindow.PreviewPane.setPrevMessage(oPrevMsg);
					this._oWaitMsgListForLastMsg = null;
					return;
				}

				this.Accounts.setAllowForwardedFlag(data.idFolder, data.folderFullName, data.bAllowForwardedFlag);
				if (data.lookFor.length == 0) {
					this.DataSource.cache.setMessagesCount(data.idFolder, data.folderFullName, data.messagesCount, data.newMsgsCount);
				}
				var screen = this.Screens[SCREEN_MESSAGE_LIST_TOP_PANE];
				if (screen) {
					screen.placeData(data);
					if (this.listScreenId == SCREEN_MESSAGE_LIST_TOP_PANE) {
						this._defOrder = screen.GetDefOrder();
						this.Accounts.setAccountDefOrder(this.iAcctId, this._defOrder);
					}
				}
				screen = this.Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE];
				if (screen) {
					screen.placeData(data);
					if (this.listScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
						this._defOrder = screen.GetDefOrder();
						this.Accounts.setAccountDefOrder(this.iAcctId, this._defOrder);
					}
				}
				break;
			case TYPE_MESSAGE:
				if (this._oWaitMsgForNewWindow !== null && this._oWaitMsgForNewWindow.isEqual(data)) {
					this._oWaitMsgForNewWindow.oWindow.PreviewPane.setMessage(data);
					this._oWaitMsgForNewWindow = null;
					break;
				}

				var allowReply = WebMail.Settings.allowReplyMessage;
				var downloadedMessage = (data.downloaded || this.Accounts.isInternal);
				if (allowReply && downloadedMessage) {
					this.requestMessageReplyPart(data, true);
				}
				this._message = data;
				var id = data.id;
				var uid = data.uid;
				var fId = data.idFolder;var fName = data.folderFullName;
				if (this.DataSource.cache.clearMessage(id , uid, fId, fName, data.charset)) {
					this.DataSource.cache.clearMessageList(fId, fName);
					var screen = this.Screens[this.listScreenId];
					if (screen) {
						if (screen.oSelection) {
							var newId = this._message.getIdForList(screen.id);
							screen.oSelection.ChangeLineId(this._message, newId);
						}
					}
				}
				if (!data.isVoice) {
					this.DataSource.set([[{id: id, uid: uid}], fId, fName], 'read', true, false);
				}
				if (3 < this.forEditParams.length && id == this.forEditParams[0] && uid == this.forEditParams[1] &&
				 fId == this.forEditParams[2] && fName == this.forEditParams[3]) {
					if (SCREEN_NEW_MESSAGE == this.ScreenId) {
						this.Screens[SCREEN_NEW_MESSAGE].UpdateMessage(this._message);
					} else {
						Screens[SCREEN_NEW_MESSAGE].showHandler = 'screen.UpdateMessage(this._message);';
						SelectScreenHandler(SCREEN_NEW_MESSAGE);
					}
					this.forEditParams = [];
				} else if (this._replyAction != -1) {
                     var screen = this.Screens[SCREEN_NEW_MESSAGE];
                     if (screen) {
                          screen.UpdateMessageForReply(this._message, this._replyAction, this._replyText);
                     }
                     this._replyAction = -1;
                     this._replyText = '';
                }
				if (this.ScreenId == SCREEN_MESSAGE_LIST_TOP_PANE) {
					this.Screens[SCREEN_MESSAGE_LIST_TOP_PANE].placeData(data);
				}
				if (this.ScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
					this.Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE].placeData(data);
				}
				break;
			case TYPE_MESSAGES_OPERATION:
				this._placeMessagesOperation(data);
				break;
			case TYPE_AUTORESPONDER:
			case TYPE_FORWARD:
			case TYPE_MOBILE_SYNC:
			case TYPE_USER_SETTINGS:
			case TYPE_CUSTOM:
				this._placeSettingsData(data);
				break;
			default:
				if (this.ScreenId != -1) {
					this.Screens[this.ScreenId].placeData(data);
				}
				break;
		}
	},

	_placeFolderList: function (data)
	{
		this.Accounts.setFolderList(data);
		var listScreen = this.Screens[this.listScreenId];
		if (listScreen) {
			listScreen.RepairEmptyTools();
		}
		var settingsScreen = this.Screens[SCREEN_USER_SETTINGS];
		var isChangedFolders = false;
		if (settingsScreen) {
			settingsScreen.placeData(data);
			isChangedFolders = settingsScreen.isChangedFolders;
		}
		if (data.idAcct == this.iAcctId) {
			this.folderList = data;
			if (this.foldersToUpdate.length > 0) {
				for (var i = this.foldersToUpdate.length - 1; i >= 0; i--) {
					this.DataSource.cache.clearMessageList(this.foldersToUpdate[i].id, this.foldersToUpdate[i].fullName);
				}
				this.foldersToUpdate = [];
				GetHandler(TYPE_SETTINGS_LIST, {}, [], '');
			}
			else if (this.Accounts.currMailProtocol == IMAP4_PROTOCOL){
				if (listScreen) {
					for (var key in data.folders) {
						var fld = data.folders[key];
						if (typeof(fld) === 'function') continue;
						var oFldParams = listScreen._foldersParam[fld.id + fld.fullName];
						if (oFldParams && fld.syncType != oFldParams.iSyncType &&
						 (fld.syncType == SYNC_TYPE_DIRECT_MODE || oFldParams.iSyncType == SYNC_TYPE_DIRECT_MODE)) {
							this.DataSource.cache.clearMessageList(fld.id, fld.fullName);
							if (listScreen.oKeyMsgList.isEqualFolder(fld.id, fld.fullName)) {
								listScreen.needToRefreshMessages = true;
							}
						}
					}
				}
			}
			var screen = this.Screens[SCREEN_MESSAGE_LIST_TOP_PANE];
			if (screen) {
				screen.needToRefreshFolders = isChangedFolders;
				screen.placeData(data);
			}
			screen = this.Screens[SCREEN_MESSAGE_LIST_CENTRAL_PANE];
			if (screen) {
				screen.needToRefreshFolders = isChangedFolders;
				screen.placeData(data);
			}
			HeaderInfo.setNewMessagesCount();
		}
	},

	_placeSettingsData: function (data)
	{
		var settingsScreen = this.Screens[SCREEN_USER_SETTINGS];
		if (settingsScreen) {
			settingsScreen.placeData(data);
		}
	},

	_placeDeleteOperation: function (data)
	{
		var listScreen = this.Screens[this.listScreenId];
		if (!listScreen) return;

		var moveErrorHappen = (data.operationInt == TOOLBAR_DELETE && data.isMoveError);
		if (moveErrorHappen) {
			listScreen.ClearDeleteTools();
			listScreen.RevertMessageList();
			Dialog.confirm(Lang.NoMoveDelete, function () {
				RequestMessagesOperationHandler(TOOLBAR_NO_MOVE_DELETE, [], []);
			});
		}
		else {
			this.DataSource.cache.clearMessageList(data.idFolder, data.folderFullName);
			var oKeyMsgList = listScreen.oKeyMsgList;
			if (!listScreen.oKeyMsgList.isEqualFolder(data.idFolder, data.folderFullName)) {
				oKeyMsgList = oKeyMsgList.getNewByFolder(data.idFolder, data.folderFullName);
				GetMessageListHandler(REDRAW_NOTHING, null, oKeyMsgList, true);
			}
			var bReloadTrash = (listScreen.DeleteLikePop3()
				&& !listScreen.isTrash(data.idFolder, data.folderFullName)
				&& !listScreen.isSpam(data.idFolder, data.folderFullName));
			if (bReloadTrash) {
				var oTrash = this.getCurrentTrashFolder();
				if (oTrash !== null) {
					this.DataSource.cache.clearMessageList(oTrash.id, oTrash.fullName);
					if (!listScreen.isTrash()) {
						oKeyMsgList = oKeyMsgList.getNewByFolder(oTrash.id, oTrash.fullName);
						GetMessageListHandler(REDRAW_NOTHING, null, oKeyMsgList, true);
					}
				}
			}
			listScreen.placeData(data);
		}
	},

	_placeMoveAndPurgeOperation: function (data)
	{
		var listScreen = this.Screens[this.listScreenId];
		if (!listScreen) return;

		var moveErrorHappen = ((data.operationInt == TOOLBAR_MOVE_TO_FOLDER
			|| data.operationInt == TOOLBAR_IS_SPAM || data.operationInt == TOOLBAR_NOT_SPAM)
			&& data.isMoveError);
		if (moveErrorHappen) {
			listScreen.ClearDeleteTools();
			listScreen.RevertMessageList();
			if (data.operationInt == TOOLBAR_IS_SPAM) {
				Dialog.confirm(Lang.NoMoveSpamInFullMailbox, function () {
					RequestMessagesOperationHandler(TOOLBAR_NO_MOVE_DELETE, [], []);
				});
			}
			else {
				Dialog.alert(Lang.NoMoveInFullMailbox);
			}
		}
		else {
			this.DataSource.cache.clearMessageList(data.idFolder, data.folderFullName);
			var oKeyMsgList = listScreen.oKeyMsgList;
			var oNewKeyMsgList;
			if (!oKeyMsgList.isEqualFolder(data.idFolder, data.folderFullName)) {
				oNewKeyMsgList = oKeyMsgList.getNewByFolder(data.idFolder, data.folderFullName);
				GetMessageListHandler(REDRAW_NOTHING, null, oNewKeyMsgList, true);
			}
			if (data.idToFolder != -1 || data.toFolderFullName != '') {
				this.DataSource.cache.clearMessageList(data.idToFolder, data.toFolderFullName);
				if (!oKeyMsgList.isEqualFolder(data.idToFolder, data.toFolderFullName)) {
					oNewKeyMsgList = oKeyMsgList.getNewByFolder(data.idToFolder, data.toFolderFullName);
					GetMessageListHandler(REDRAW_NOTHING, null, oNewKeyMsgList, true);
				}
			}
			var spam = this.getCurrentSpamFolder();
			if (spam != null && data.operationInt == TOOLBAR_IS_SPAM) {
				this.DataSource.cache.clearMessageList(spam.id, spam.fullName);
				if (!oKeyMsgList.isEqualFolder(spam.id, spam.fullName)) {
					oNewKeyMsgList = oKeyMsgList.getNewByFolder(spam.id, spam.fullName);
					GetMessageListHandler(REDRAW_NOTHING, null, oNewKeyMsgList, true);
				}
			}
			if (data.operationInt == TOOLBAR_NOT_SPAM) {
				var inbox = this.getCurrentInboxFolder();
				this.DataSource.cache.clearMessageList(inbox.id, inbox.fullName);
				if (!oKeyMsgList.isEqualFolder(inbox.id, inbox.fullName)) {
					oNewKeyMsgList = oKeyMsgList.getNewByFolder(inbox.id, inbox.fullName);
					GetMessageListHandler(REDRAW_NOTHING, null, oNewKeyMsgList, true);
				}
			}
			listScreen.placeData(data);
		}
	},

	_placeMarkAndFlagOperation: function (data)
	{
		if (data.operationField != '') {
			if (data.isAllMess) {
				this.DataSource.set([[], data.idFolder, data.folderFullName], data.operationField, data.operationValue, data.isAllMess);
			}
			else {
				var dict = data.messages;
				var keys = dict.keys();
				for (var i in keys) {
					if (typeof(keys[i]) !== 'string') {
						continue;
					}
					var folder = dict.getVal(keys[i]);
					this.DataSource.set([folder.idArray, folder.idFolder, folder.folderFullName], data.operationField, data.operationValue, data.isAllMess);
				}
			}
		}
		var listScreen = this.Screens[this.listScreenId];
		if (listScreen) {
			listScreen.placeData(data);
		}
	},

	_placeMessagesOperation: function (data)
	{
		switch (data.operationInt) {
			case TOOLBAR_DELETE:
			case TOOLBAR_NO_MOVE_DELETE:
				this._placeDeleteOperation(data);
				break;
			case TOOLBAR_PURGE:
			case TOOLBAR_EMPTY_SPAM:
			case TOOLBAR_MOVE_TO_FOLDER:
			case TOOLBAR_IS_SPAM:
			case TOOLBAR_NOT_SPAM:
				this._placeMoveAndPurgeOperation(data);
				break;
			default:
				this._placeMarkAndFlagOperation(data);
				break;
		}
	},

	getCurrentFolderName: function ()
	{
		var oListScreen = this.Screens[this.listScreenId];
		if (!oListScreen) return '';
		var oKeyMsgList = oListScreen.oKeyMsgList;
		return this.getFolderName(oKeyMsgList.iFolderId, oKeyMsgList.sFolderFullName);
	},

	_getFolderList: function ()
	{
		var oFolderList = this.folderList;
		if (this.folderList === null || this.folderList.idAcct !== this.Accounts.currId) {
			oFolderList = this.Accounts.getCurrentFolderList();
		}
		return oFolderList;
	},

	getNameSpaceFolder: function ()
	{
		var oFolderList = this._getFolderList();
		if (oFolderList === null) {
			return {id: -1, fullName: ''};
		}
		else {
			var iId = oFolderList.getNameSpaceFolderId();
			var sFullName = oFolderList.nameSpace.substr(0, oFolderList.nameSpace.length - 1);
			return {id: iId, fullName: sFullName};
		}
	},

	getFolderName: function (iId, sFullName)
	{
		var oFolderList = this._getFolderList();
		return  (oFolderList != null) ? oFolderList.getFolderName(iId, sFullName) : '';
	},

	getFolderMessagesCount: function (iId, sFullName)
	{
		var oFolderList = this._getFolderList();
		return  (oFolderList != null) ? oFolderList.getFolderMessagesCount(iId, sFullName) : 0;
	},

	getFolderByName: function (sName, bRegister)
	{
		if (typeof sName !== 'string' || sName.length === 0) {
			return null;
		}
		var oFolderList = this._getFolderList();
		return  (oFolderList != null) ? oFolderList.getFolderByName(sName, bRegister) : null;
	},

	getCurrentInboxFolder: function ()
	{
		if (this.folderList != null && this.folderList.idAcct == this.Accounts.currId) {
			return this.folderList.inbox;
		}
		var folderList = this.Accounts.getCurrentFolderList();
		return  (folderList != null) ? folderList.inbox : null;
	},

	getCurrentSentFolder: function ()
	{
		if (this.folderList != null && this.folderList.idAcct == this.Accounts.currId) {
			return this.folderList.sent;
		}
		var folderList = this.Accounts.getCurrentFolderList();
		return  (folderList != null) ? folderList.sent : null;
	},

	allowSaveMessageToSent: function () {
		var sent = this.getCurrentSentFolder();
		return (sent != null);
	},

	getCurrentDraftsFolder: function ()
	{
		if (this.folderList != null && this.folderList.idAcct == this.Accounts.currId) {
			return this.folderList.drafts;
		}
		var folderList = this.Accounts.getCurrentFolderList();
		return  (folderList != null) ? folderList.drafts : null;
	},

	allowSaveMessageToDrafts: function () {
		var drafts = this.getCurrentDraftsFolder();
		return (drafts != null);
	},

	getCurrentTrashFolder: function ()
	{
		if (this.folderList != null && this.folderList.idAcct == this.Accounts.currId) {
			return this.folderList.trash;
		}
		var folderList = this.Accounts.getCurrentFolderList();
		return  (folderList != null) ? folderList.trash : null;
	},

	allowTrashTools: function () {
		var trash = this.getCurrentTrashFolder();
		return (trash != null);
	},

	getCurrentSpamFolder: function ()
	{
		if (this.folderList != null && this.folderList.idAcct == this.Accounts.currId) {
			return this.folderList.spam;
		}
		var folderList = this.Accounts.getCurrentFolderList();
		return  (folderList != null) ? folderList.spam : null;
	},

	allowSpamTools: function (includingLearning) {
		var account = this.Accounts.getCurrentAccount();
		if (!account.allowSpamFolder) return false;
		if (includingLearning && !account.allowSpamLearning) return false;
		var spam = this.getCurrentSpamFolder();
		return (spam != null);
	},

	getCurrentListScreen: function (screenId)
	{
		if (screenId == undefined) screenId = this.listScreenId;
		if (this.ScreenId != screenId) return null;
		return this.Screens[this.ScreenId];
	},

	ClearFilterCache: function ()
	{
		this.Accounts.deleteCurrFilters();
	},

	RequestFoldersMessageList: function ()
	{
		if (this._gotFoldersMessageList[this.iAcctId]) return;
		this._gotFoldersMessageList[this.iAcctId] = true;
		GetHandler(TYPE_FOLDERS_BASE, { }, [], '', true);
	},

	startHiddenCheckMail: function ()
	{
		var
			bOpenRequests = WebMail.DataSource.netLoader.hasOpenRequests(),
			oSettingsScreen = this.Screens[SCREEN_USER_SETTINGS],
			bFoldersPaneOpen = (typeof oSettingsScreen !== 'undefined') && oSettingsScreen.isFoldersPaneOpen(),
			oListScreen = this.Screens[this.listScreenId]
		;

		if (!bOpenRequests && !bFoldersPaneOpen && oListScreen) {
			oListScreen.startCheckMail(true);
		}
	},

	startCheckMailInterval: function ()
	{
		var obj = this;

		if (this._checkMailInterval != null) {
			clearInterval(this._checkMailInterval);
			this._checkMailInterval = null;
		}
		if (this.Settings.autoCheckMailInterval > 0) {
			this._checkMailInterval = setInterval(function() {
				obj.startHiddenCheckMail();
			}, this.Settings.autoCheckMailInterval * 60000);
		}

		if (this._checkRequestsInterval != null) {
			clearInterval(this._checkRequestsInterval);
			this._checkRequestsInterval = null;
		}
		this._checkRequestsInterval = setInterval(function() {
			var hasOpenRequests = WebMail.DataSource.netLoader.hasOpenRequests();
			if (!hasOpenRequests) {
				var screen = WebMail.Screens[WebMail.listScreenId];
				if (screen) {
					screen.enableCheckMailTool();
				}
				WebMail.hideInfo();
			}
		}, 60000);
	},

	GetFolderSyncType: function (id)
	{
		for (var key in this.folderList.folders) {
			var fld = this.folderList.folders[key];
			if (typeof(fld) === 'function') continue;
			if (fld.id == id) {
				return fld.syncType;
			}
		}
		return false;
	},

	SetActiveTab: function ()
	{
		if (!this.isBuilded) return;
		var contactsClass = 'wm_accountslist_contacts';
		if (!this.Settings.allowContacts) {
			contactsClass = 'wm_hide';
		}
		var calendarClass = 'wm_accountslist_contacts';
		if (!this.Settings.allowCalendar || window.Separated || !window.UseDb) {
			calendarClass = 'wm_hide';
		}
		this._mailTab.className = 'wm_accountslist_email';
		this._contactsTab.className = contactsClass;
		this._calendarTab.className = calendarClass;
		this._settingsTab.className = (window.Separated) ? 'wm_hide' : 'wm_accountslist_settings';
		var screen = Screens[this.ScreenId];
		if (screen) {
			switch (screen.SectionId) {
				case SECTION_MAIL:
					this._mailTab.className = 'wm_accountslist_email wm_active_tab';
					break;
				case SECTION_CONTACTS:
					this._contactsTab.className = contactsClass + ' wm_active_tab';
					break;
				case SECTION_CALENDAR:
					this._calendarTab.className = calendarClass + ' wm_active_tab';
					break;
				case SECTION_SETTINGS:
					if (!window.Separated && (window.UseDb || window.UseLdapSettings)) {
						this._settingsTab.className = 'wm_accountslist_settings wm_active_tab';
					}
					break;
			}
		}
	},

	showMail: function (idAcct)
	{
		var
			hasHistArgs = this.MailHistoryArgs != null,
			screen = Screens[this.ScreenId],
			isMailSection = screen && (screen.SectionId == SECTION_MAIL),
			args = {ScreenId: WebMail.listScreenId}
		;
		if (idAcct) {
			if (this.Accounts.hasAccount(idAcct)) {
				args.idAcct = idAcct;
			}
		}
		else if (hasHistArgs && !isMailSection) {
			if (this.Accounts.hasAccount(this.MailHistoryArgs.idAcct)) {
				args = this.MailHistoryArgs;
				if (args.ScreenId == SCREEN_MESSAGE_LIST_TOP_PANE || args.ScreenId == SCREEN_MESSAGE_LIST_CENTRAL_PANE) {
					args.ScreenId = WebMail.listScreenId;
				}
				if (undefined != args.idAcct) delete args.idAcct;
				if (undefined != args.AcctChanged) delete args.AcctChanged;
			}
		}
		SetHistoryHandler(args);
	},

	showContacts: function ()
	{
		var screen = Screens[this.ScreenId];
		var isContactsSection = screen && (screen.SectionId == SECTION_CONTACTS);
		screen = this.Screens[SCREEN_CONTACTS];
		if (screen && screen.HistoryArgs && !isContactsSection) {
			var args = screen.HistoryArgs;
			if (undefined == args.idAcct && undefined == args.AcctChanged)
			{
				SetHistoryHandler(
					{
						ScreenId: SCREEN_CONTACTS,
						Entity: PART_CONTACTS,
						lookFor: ''
					}
				);
			} else {
				if (undefined != args.idAcct) delete args.idAcct;
				if (undefined != args.AcctChanged) delete args.AcctChanged;
				SetHistoryHandler(args);
			}
		} else {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_CONTACTS,
					Entity: PART_CONTACTS,
					lookFor: ''
				}
			);
		}
	},

	showCalendar: function ()
	{
		SetHistoryHandler(
			{
				ScreenId: SCREEN_CALENDAR
			}
		);
	},

	showSettings: function ()
	{
		var screen = this.Screens[SCREEN_USER_SETTINGS];
		if (screen && screen.HistoryArgs != null) {
			var args = screen.HistoryArgs;
			if (undefined != args.idAcct) delete args.idAcct;
			if (undefined != args.AcctChanged) delete args.AcctChanged;
			SetHistoryHandler(args);
		}
		else {
			SetHistoryHandler(
				{
					ScreenId: SCREEN_USER_SETTINGS,
					iEditableAcctId: WebMail.Accounts.editableId,
					iEntity: (window.UseDb || window.UseLdapSettings) ? PART_COMMON_SETTINGS : PART_MANAGE_FOLDERS,
					bNewMode: false
				}
			);
		}
	},

	LogOut: function (errorCode)
	{
		WindowOpener.closeAll();
		var oDocument = (parent) ? parent : document;
		if (errorCode) {
			oDocument.location = LoginUrl + '?error=' + errorCode;
		}
		else {
			Cookies.remove('awm_autologin_subid');
			Cookies.remove('awm_autologin_data');
			Cookies.remove('awm_autologin_id');
			oDocument.location = LoginUrl + '?mode=logout';
		}
	},

	SetStartScreen: function (start)
	{
		var START_NEW_MESSAGE = 1;
		var START_USER_SETTINGS = 2;
		var START_CONTACTS = 3;
		var START_CALENDAR = 4;
		switch (start) {
			case START_NEW_MESSAGE:
				this.startScreen = SCREEN_NEW_MESSAGE;
				Screens[SCREEN_NEW_MESSAGE].showHandler = (ToAddr && ToAddr.length > 0)
					? 'screen.UpdateMessageFromContacts(\'' + EncodeStringForEval(ToAddr) + '\')'
					: 'screen.SetNewMessage()';
				break;
			case START_USER_SETTINGS:
				this.startScreen = SCREEN_USER_SETTINGS;
				break;
			case START_CONTACTS:
				this.startScreen = SCREEN_CONTACTS;
				break;
			case START_CALENDAR:
				this.startScreen = SCREEN_CALENDAR;
				break;
			default:
				this.startScreen = this.listScreenId;
				break;
		}
	},

	showTrial: function ()
	{
		var md = CreateChild(document.body, 'div', [['class', 'wm_tr_message']]);
		var msg = 'Your evaluation period is about to expire.';
		if (window.CSType != true) {
			msg += (window.XType == '1')
				? ' You can <a href="http://www.afterlogic.com/purchase/xmail-server-pro" target="_blank">purchase AfterLogic XMail Server Pro here</a>.'
				: ' You can <a href="http://www.afterlogic.com/purchase/webmail-pro" target="_blank">purchase AfterLogic WebMail Pro here</a>.';
		}
		md.innerHTML = msg;
		var x = CreateChild(md, 'span', [['class', 'wm_close_info_image wm_control']]);
		x.onclick = function() {md.className = 'wm_hide';};
	},

	CheckHistoryObject: function (args, onlyCheck)
	{
		if (!args.idAcct) {
			args.idAcct = this.iAcctId;
		}
		var checked = false; //parameters' set is such as previouse one
		if (null == this.HistoryObj) {
			checked = true;  //another
		}
		if (!checked) {
			switch (args.ScreenId) {
				case SCREEN_MESSAGE_LIST_TOP_PANE:
				case SCREEN_MESSAGE_LIST_CENTRAL_PANE:
					if (args.MsgId != 'undefined' && args.MsgId != null) {
						if (args.idAcct == this.HistoryObj.idAcct && args.MsgFolderId == this.HistoryObj.MsgFolderId &&
						 args.MsgFolderFullName == this.HistoryObj.MsgFolderFullName && args.MsgId == this.HistoryObj.MsgId &&
						 args.MsgUid == this.HistoryObj.MsgUid && args.MsgCharset == this.HistoryObj.MsgCharset &&
						 args.ScreenId == this.HistoryObj.ScreenId) {
							checked = false;
						} else {
							checked = true;
						}
					} else {
						checked = true;
					}
				break;
				case SCREEN_USER_SETTINGS:
					if (args.iEditableAcctId == this.HistoryObj.iEditableAcctId &&
					 args.iEntity == this.HistoryObj.iEntity && args.bNewMode == this.HistoryObj.bNewMode &&
					 args.ScreenId == this.ScreenId) {
						checked = false;
					} else {
						checked = true;
					}
				break;
				default:
					checked = true;
				break;
			}
		}
		if (checked) {
			if (!onlyCheck) {
				this.HistoryObj = args;
			}
			return args;
		}
		else {
			return null;
		}
	},

	restoreFromHistory: function (args)
	{
		if (args.idAcct != this.iAcctId || this.bAccountChanged) {
			var screen = this.Screens[SCREEN_CALENDAR];
			if (screen) {
				screen.needReload();
			}
			args.AcctChanged = true;
			this.iAcctId = args.idAcct;
			this.Accounts.changeCurrAccount(args.idAcct);
			this.FillAccountsList();
			this.bAccountChanged = false;
		}
		else {
			args.AcctChanged = false;
		}
		this.HistoryArgs = args;
		if (Screens[args.ScreenId] && Screens[args.ScreenId].SectionId == SECTION_MAIL) {
			this.MailHistoryArgs = args;
		}
		switch (args.ScreenId) {
			case SCREEN_NEW_MESSAGE:
				if (args.fromDrafts) {
					this.forEditParams = [args.MsgId, args.MsgUid, args.MsgFolderId,
						args.MsgFolderFullName];
					GetMessageHandler(args.MsgId, args.MsgUid, args.MsgFolderId,
						args.MsgFolderFullName, args.MsgParts, args.MsgCharset, true);
				}
				else if (args.ForReply) {
					this._replyAction = args.replyType;
					this._replyText = args.replyText;
					GetMessageHandler(args.MsgId, args.MsgUid, args.MsgFolderId,
						args.MsgFolderFullName, args.MsgParts, args.MsgCharset, true);
				}
				else if (args.fromContacts) {
					if (this.ScreenId == SCREEN_NEW_MESSAGE) {
						this.Screens[SCREEN_NEW_MESSAGE].UpdateMessageFromContacts(args.ToField, args.CcField, args.BccField);
					}
					else {
						Screens[SCREEN_NEW_MESSAGE].showHandler = "screen.UpdateMessageFromContacts('" + EncodeStringForEval(args.ToField) + "', '" + EncodeStringForEval(args.CcField) + "', '" + EncodeStringForEval(args.BccField) + "')";
					}
				}
				else if (args.ConfirmEmail && args.ConfirmEmail.length > 0) {
					if (this.ScreenId == SCREEN_NEW_MESSAGE) {
						this.Screens[SCREEN_NEW_MESSAGE].UpdateMessageFromConfirmation(args.ConfirmEmail);
					}
					else {
						Screens[SCREEN_NEW_MESSAGE].showHandler = "screen.UpdateMessageFromConfirmation('" + EncodeStringForEval(args.ConfirmEmail) + "')";
					}
				}
				else {
					if (this.ScreenId == SCREEN_NEW_MESSAGE) {
						this.Screens[SCREEN_NEW_MESSAGE].SetNewMessage();
					}
					else {
						Screens[SCREEN_NEW_MESSAGE].showHandler = 'screen.SetNewMessage()';
					}
				}
			break;
		}
		if (this.ScreenId != args.ScreenId) {
			SelectScreenHandler(args.ScreenId);
		}
		else {
			var screen = this.Screens[this.ScreenId];
			if (screen) {
				screen.restoreFromHistory(args);
				this.HistoryArgs = null;
			}
			else {
				SelectScreenHandler(args.ScreenId);
			}
		}
	},

	contactsImported: function (count)
	{
		if (count == 0) {
			this.showReport(Lang.ErrorNoContacts);
		}
		if (count > 0) {
			this.showReport(Lang.InfoHaveImported + ' ' + count + ' ' + Lang.InfoNewContacts);
			var screen = this.Screens[SCREEN_CONTACTS];
			if (screen) {
				screen.contactsImported(count);
			}
		}
	},

	_clearMessageList: function (refreshFolders, clearMessages)
	{
		var listScreen = this.Screens[this.listScreenId];
		if (listScreen) {
			listScreen.needToRefreshFolders = refreshFolders ? true : false;
			listScreen.needToRefreshMessages = true;
		}
		if (clearMessages) {
   			this.DataSource.cache.clearAllMessages();
		}
		else {
			this.DataSource.cache.clearMessageList(-1, '');
		}
	},

	ApplyNewSettings: function (newSettings)
	{
		if (null != newSettings.autoCheckMailInterval) {
			this.Settings.autoCheckMailInterval = newSettings.autoCheckMailInterval;
			this.startCheckMailInterval();
		}
		if (null != newSettings.contactsPerPage && this.Settings.contactsPerPage != newSettings.contactsPerPage) {
			var contactsScreen = this.Screens[SCREEN_CONTACTS];
			if (contactsScreen) contactsScreen.clearContactsAndGroups();
		}
		var listScreen;
		if (null != newSettings.layoutSide && this.Settings.layoutSide != newSettings.layoutSide) {
			this.listScreenId = (newSettings.layoutSide)
				? SCREEN_MESSAGE_LIST_CENTRAL_PANE : SCREEN_MESSAGE_LIST_TOP_PANE;
			listScreen = this.Screens[this.listScreenId];
			if (listScreen) {
				listScreen.needToRefreshMessages = true;
				listScreen.needToRefreshFolders = true;
				listScreen.FillSpaceInfo();
			}
		}
		if (null != newSettings.msgsPerPage && this.Settings.msgsPerPage != newSettings.msgsPerPage) {
			this._clearMessageList();
		}
		if (null != newSettings.timeFormat && newSettings.timeFormat != this.Settings.timeFormat) {
			this._clearMessageList();
			var screen = this.Screens[SCREEN_CALENDAR];
			if (screen) screen.needReload();
		}
		if (null != newSettings.timeOffset && newSettings.timeOffset != this.Settings.timeOffset) {
			this._clearMessageList(false, true);
		}
		var calendarScreen = this.Screens[SCREEN_CALENDAR];
		if (null != newSettings.defDateFormat && this.Settings.defDateFormat != newSettings.defDateFormat) {
			if (calendarScreen) calendarScreen.needReload();
		}
		if (null != newSettings.defLang && this.Settings.defLang != newSettings.defLang) {
			if (calendarScreen) calendarScreen.needReload();
			this._clearMessageList(true);
			var newRTL = isRtlLanguage(newSettings.defLang);
			if ((window.RTL && !newRTL) || (!window.RTL && newRTL)) {
				document.location = WebMailUrl;
			}
			else {
				var obj = this;
				var url = LanguageUrl + '?v=' + WmVersion + '&lang=' + newSettings.defLang;
				this.ScriptLoader.load([url], function () {obj.ChangeLang();});
			}
		}
		if (null != newSettings.defSkin && this.Settings.defSkin != newSettings.defSkin) {
			this.Settings.defSkin = newSettings.defSkin;
			this.ChangeSkin();
			if (calendarScreen) calendarScreen.needReload();
			if (this.ScreenId == this.listScreenId) {
				listScreen = this.Screens[this.listScreenId];
				if (listScreen) {
					listScreen.ChangeSkin();
				}
			}
		}
	},

	ChangeLang: function ()
	{
		HeaderInfo.applyData();
		this.langChanger.go();
		HtmlEditorField.changeLang();
		var screen = this.Screens[SCREEN_MESSAGE_LIST_TOP_PANE];
		if (screen) {
			screen.ChangeHeaderFieldsLang();
		}
	},

	setTitle: function (sTitleToAdd)
	{
		HeaderInfo.applyData(sTitleToAdd);
	},

	ChangeSkin: function ()
	{
		this.addNewSkinLink(this.Settings.defSkin);
		setTimeout(function () {WebMail.removeOldSkinLink();}, 200);
	},

	addNewSkinLink: function (newSkin)
	{
		var newLink = document.createElement('link');
		newLink.setAttribute('type', 'text/css');
		newLink.setAttribute('rel', 'stylesheet');
		newLink.href = 'skins/' + newSkin + '/styles.css';
		this._head.appendChild(newLink);

        if (window.RTL) {
		    var newRtlLink = document.createElement('link');
		    newRtlLink.setAttribute('type', 'text/css');
		    newRtlLink.setAttribute('rel', 'stylesheet');
			newRtlLink.href = 'skins/' + newSkin + '/styles-rtl.css';
			this._head.appendChild(newRtlLink);
		}

		this._newSkinLink = newLink;
		if (window.RTL) {
		    this._newRtlSkinLink = newRtlLink;
		}
	},

	/*
	 * don't delete old skin immediately because of blinking screen in ff
	 */
	removeOldSkinLink: function ()
	{
		if (this._newSkinLink != null) {
			this._head.removeChild(this._skinLink);
			this._skinLink = this._newSkinLink;
			this._newSkinLink = null;
		}
		if (window.RTL && this._newRtlSkinLink != null) {
			this._head.removeChild(this._rtlSkinLink);
			this._rtlSkinLink = this._newRtlSkinLink;
			this._newRtlSkinLink = null;
		}
	},

	build: function()
	{
		this.PopupMenus = new CPopupMenus();
		this.buildAccountsList();
		document.body.onclick = ClickBodyHandler;
		document.onkeydown = WebMail.onKeyDown;
		this.isBuilded = true;
	},

	onKeyDown: function (ev)
	{
		if (Dialog.bOpened) {
			return;
		}

		ev = ev ? ev : window.event;
		var clickElem = (Browser.mozilla) ? ev.target : ev.srcElement;
		if (clickElem && (clickElem.tagName == 'INPUT' || clickElem.tagName == 'TEXTAREA')) {
			return true;
		}

		var key = Keys.getCodeFromEvent(ev);
		var currentScreen = WebMail.Screens[WebMail.ScreenId];
		if (currentScreen && currentScreen.onKeyDown) {
			currentScreen.onKeyDown(key, ev);
		}
		if (key == Keys.a && ev.ctrlKey || (key == Keys.up || key == Keys.down) && ev.shiftKey) {
			return false;
		}
		return true;
	},

	resizeProcess: function (sType)
	{
	    if (this.isBuilded &&
			-1 < $.inArray(this.ScreenId,
				[SCREEN_MESSAGE_LIST_TOP_PANE, SCREEN_MESSAGE_LIST_CENTRAL_PANE, SCREEN_VIEW_MESSAGE])
			&& -1 < $.inArray(sType, ['begin', 'end']))
		{
			var oScreen = this.Screens[this.ScreenId];
			if (oScreen.isBuilded && oScreen.shieldShowType) {
				oScreen.shieldShowType('begin' === sType);
			}
		}
	},

	resizeBody: function (mode)
	{
		if (this.isBuilded) {
		    if (this.ScreenId != SCREEN_CONTACTS) {
			    var width = GetWidth();
			    if (Browser.ie && Browser.version < 7) {
				    document.body.style.width = width + 'px';
				}
			}
			else {
			    if (Browser.ie && Browser.version < 7) {
				    document.body.style.width = 'auto';
				}
			}
			if (this.ScreenId != -1) {
				this.Screens[this.ScreenId].resizeBody(mode);
			}
			this.InfoContainer.resize();
		}
	},

	clickBody: function (ev)
	{
		if (this.isBuilded) {
			this.PopupMenus.checkShownItems();
			if (this.ScreenId != -1) {
				this.Screens[this.ScreenId].clickBody(ev);
			}
		}
	},

	replyClick: function (type)
	{

		var msg = this._message;
		var screen = this.Screens[this.ScreenId];
		if (screen && this.ScreenId == this.listScreenId) {
			msg = screen._msgObj;
		}
		this.replyMessageClick(type, msg);
	},

	replyMessageClick: function (type, msg, text)
	{
		if (!WebMail.Settings.allowReplyMessage && (type == TOOLBAR_REPLY || type == TOOLBAR_REPLYALL)) return;
		if (!WebMail.Settings.allowForwardMessage && (type == TOOLBAR_FORWARD)) return;

		if (msg == null) {
			return;
		}
		if (msg.noReply && type == TOOLBAR_REPLY || msg.noReplyAll && type == TOOLBAR_REPLYALL) {
			return;
		}
		if ((msg.sensivity != SENSIVITY_NOTHING || msg.noForward) && type == TOOLBAR_FORWARD) {
			return;
		}
		if (text == undefined) text = '';
		var parts = [PART_MESSAGE_HEADERS, PART_MESSAGE_ATTACHMENTS, PART_MESSAGE_FULL_HEADERS];
		if (type == TOOLBAR_FORWARD) {
			parts.push((msg.hasHtml) ? PART_MESSAGE_FORWARD_HTML : PART_MESSAGE_FORWARD_PLAIN);
		}
		else {
			parts.push((msg.hasHtml) ? PART_MESSAGE_REPLY_HTML : PART_MESSAGE_REPLY_PLAIN);
		}
		SetHistoryHandler(
			{
				ScreenId: SCREEN_NEW_MESSAGE,
				fromDrafts: false,
				ForReply: true,
				replyType: type,
				replyText: text,
				MsgId: msg.id,
				MsgUid: msg.uid,
				MsgFolderId: msg.idFolder,
				MsgFolderFullName: msg.folderFullName,
				MsgCharset: msg.charset,
				MsgSize: msg.size,
				MsgParts: parts
			}
		);
	},

	changeScreen: function (screenId) {
		if (this.ScreenId !== -1) {
			this.Screens[this.ScreenId].hide();
		}
		this.ScreenId = screenId;
		this.SectionId = Screens[screenId].SectionId;
		var screen = this.Screens[screenId];
		if (!screen.isBuilded) {
			screen.build(this._content, this.PopupMenus);
		}

		SetBodyAutoOverflow(screen.bodyAutoOverflow);
		this.show();
		HeaderInfo.applyData();
		eval(Screens[screenId].showHandler);
		switch (screen.id) {
			case SCREEN_MESSAGE_LIST_TOP_PANE:
				screen.ChangeDefOrder(this._defOrder);
				break;
			case SCREEN_MESSAGE_LIST_CENTRAL_PANE:
				screen.ChangeDefOrder(this._defOrder);
				break;
		}
		if (null != this.HistoryArgs && screen.id == this.HistoryArgs.ScreenId) {
			screen.show(this.HistoryArgs);
		}
		else {
			screen.show(null);
		}
		this.SetActiveTab();
		this.HistoryArgs = null;
	},

	showScreen: function(loadHandler)
	{
		var screenId = this.ScreenIdForLoad;
		if (screenId == SCREEN_NEW_MESSAGE && !WebMail.Settings.allowComposeMessage) {
			screenId = this.listScreenId;
		}
		var section;
		var screen = this.Screens[screenId];
		if (screen) {
			if (this.ScreenId === SCREEN_NEW_MESSAGE && this.Screens[this.ScreenId].hasChanges()) {
				Dialog.confirm(Lang.ConfirmExitFromNewMessage, (function (obj, screenId) {
					return function () {
						obj.changeScreen(screenId);
					}
				})(this, screenId))
			}
			else {
				this.changeScreen(screenId);
			}
		}
		else {
			if (!this.isBuilded) {
				this.hide();
				this.build();
			}
			var sectionId = Screens[screenId].SectionId;
			section = this.Sections[sectionId];
			if (section) {
				var sectionScreens = Sections[sectionId].Screens;
				for (var i in sectionScreens) {
					if (typeof(this.Screens[i]) === 'function') continue;
					if (!(screen = this.Screens[i])) {
						eval(sectionScreens[i]);
						if (Screens[i].PreRender) {
							screen.build(this._content, this.PopupMenus, this.Settings);
						}
						this.Screens[i] = screen;
					}
				}
				loadHandler.call(this);
			} else {
				this.Sections[sectionId] = true;
				this.ScriptLoader.load(Sections[sectionId].Scripts, loadHandler);
			}
		}
	},

	show: function ()
	{
		if (!this.shown) {
			this.shown = true;
			this.hideInfo();
			this._content.className = 'wm_content';
			this.FillAccountsList();
		}
	},

	hide: function ()
	{
		this.shown = false;
		this._content.className = 'wm_hide';
	},

	getHeaderHeight: function ()
	{
		return this._accountsBar.offsetHeight + this._logo.offsetHeight;
	},

	buildAccountsList: function()
	{
		var obj = this;
		this._logo = document.getElementById('logo');
		var a, tr, td, div, i;
		this._accountsBar = CreateChild(this._content, 'table');
		this._accountsBar.className = 'wm_accountslist';
		tr = this._accountsBar.insertRow(0);
		td = tr.insertCell(0);
		this._mailTab = CreateChild(td, 'div');
		this._mailTab.className = 'wm_accountslist_email';
		this._accountNameObject = CreateChild(this._mailTab, 'a');
		this._accountNameObject.href = '#';
		this._accountNameObject.onclick = function() {return false;};
		this._accountControl = CreateChild(td, 'div');
		this._accountControl.className = 'wm_accountslist_selection wm_control';

		this._contactsTab = CreateChild(td, 'div');
		this._contactsTab.className = (this.Settings.allowContacts)	? 'wm_accountslist_contacts' : 'wm_hide';

		a = CreateChild(this._contactsTab, 'a', [['href', '#']]);
		a.innerHTML = Lang.Contacts;
		WebMail.langChanger.register('innerHTML', a, 'Contacts', '');
		a.onclick = function() {
			obj.showContacts();
			return false;
		};

		this._calendarTab = CreateChild(td, 'div');
		this._calendarTab.className = (!this.Settings.allowCalendar || window.Separated || !window.UseDb)
			 ? 'wm_hide' : 'wm_accountslist_contacts';

		a = CreateChild(this._calendarTab, 'a', [['href', '#']]);
		a.innerHTML = Lang.Calendar;
		WebMail.langChanger.register('innerHTML', a, 'Calendar', '');
		a.onclick = function() {
			obj.showCalendar();
			return false;
		};

		if (window.CustomTopLinks && window.CustomTopLinks.length > 0) {
			var topLink;
			for (i = 0; i < window.CustomTopLinks.length; i++) {
				topLink = window.CustomTopLinks[i];
				if (topLink && topLink.Link && topLink.Name) {
					div = CreateChild(td, 'div', [['class', 'wm_accountslist_contacts']]);
					a = CreateChild(div, 'a', [['href', topLink.Link], ['target', '_blank']]);
					a.innerHTML = topLink.Name;
				}
			}
		}

		div = CreateChild(td, 'div');
		div.className = (window.Separated) ? 'wm_hide' : 'wm_accountslist_logout';
		a = CreateChild(div, 'a', [['href', '#']]);
		a.innerHTML = Lang.Logout;
		WebMail.langChanger.register('innerHTML', a, 'Logout', '');
		a.onclick = function () {
			obj.LogOut();
			return false;
		};

		this._settingsTab = CreateChild(td, 'div');
		this._settingsTab.className = (window.Separated) ? 'wm_hide' : 'wm_accountslist_settings';
		a = CreateChild(this._settingsTab, 'a', [['href', '#']]);
		a.innerHTML = Lang.Settings;
		this.langChanger.register('innerHTML', a, 'Settings', '');
		a.onclick = function() {
			obj.showSettings();
			return false;
		};

		this._accountsList = CreateChild(document.body, 'div');
		this._accountsList.className = 'wm_hide';
		var accountsListPopupMenu = new CPopupMenu(this._accountsList, this._accountControl, 'wm_account_menu',
			this._mailTab, this._mailTab, '', '', '', '');
		this.PopupMenus.addItem(accountsListPopupMenu);
	},

	FillAccountsList: function()
	{
		if (!this.isBuilded || null == this.Accounts || !this.Accounts.items) return;
		CleanNode(this._accountsList);
		var arrAccounts = this.Accounts.items;
		for(var key in arrAccounts) {
			if (typeof(arrAccounts[key]) === 'function') continue;
			var id = arrAccounts[key].id;
			if (id != this.Accounts.currId) {
				var div1 = CreateChild(this._accountsList, 'div');
				var div = CreateChild(div1, 'div');
				div.className = 'wm_account_item';
				div.onmouseover = function() {this.className = 'wm_account_item_over';};
				div.onmouseout = function() {this.className = 'wm_account_item';};
				div.onclick = CreateAccountActionFunc(id);
				div.innerHTML = arrAccounts[key].email;
			} else {
				var screen = this.Screens[this.listScreenId];
				if (screen) {
					screen.ChangeDefOrder(arrAccounts[key].defOrder);
					screen.FillSpaceInfo();
				}
				this._defOrder = arrAccounts[key].defOrder;
				this._accountNameObject.innerHTML = arrAccounts[key].email;
				var obj = this;
				this._accountNameObject.onclick = function () {
					obj.showMail();
					return false;
				};
			}
		}
		if (this._accountsList.firstChild) {
			this._accountsList.style.width = 'auto';
			this._accountControl.className = 'wm_accountslist_selection';
			this._accountControl.onmouseover = function() {this.className = 'wm_accountslist_selection_over wm_control';};
			this._accountControl.onmousedown = function() {this.className = 'wm_accountslist_selection_press wm_control';};
			this._accountControl.onmouseup = function() {this.className = 'wm_accountslist_selection_over wm_control';};
			this._accountControl.onmouseout = function() {this.className = 'wm_accountslist_selection wm_control';};
		} else {
			this._accountControl.className = 'wm_accountslist_selection_none';
			this._accountControl.onmouseover = function() { };
			this._accountControl.onmousedown = function() { };
			this._accountControl.onmouseup = function() { };
			this._accountControl.onmouseout = function() { };
		}
		this.PopupMenus.hideAllItems();
	},

	hideInfo: function()
	{
		if (this.shown) {
			this.InfoContainer.hideInfo();
		}
	},

	showError: function(errorDesc)
	{
		if (errorDesc.length > 0) {
			this.InfoContainer.showError(errorDesc);
		}
		var screen;
		if (this.ScreenId == SCREEN_NEW_MESSAGE) {
			screen = this.Screens[SCREEN_NEW_MESSAGE];
			if (screen) {
				screen.SetErrorHappen();
			}
		}
		if (this.ScreenId == this.listScreenId) {
			screen = this.Screens[this.listScreenId];
			if (screen) {
				screen.enableTools();
			}
		}
	},

	hideError: function()
	{
		this.InfoContainer.hideError();
	},

	showInfo: function(info)
	{
		if (this.shown) {
			this.InfoContainer.showInfo(info);
		}
	},

	showReport: function(report, priorDelay)
	{
		if (this.shown) {
			this.InfoContainer.showReport(report, priorDelay);
		}
	},

	hideReport: function()
	{
		this.InfoContainer.hideReport();
	},

    SaveChangesAndLogout: function ()
    {
        if (this.ScreenId == SCREEN_NEW_MESSAGE) {
            var screen = this.Screens[this.ScreenId];
            screen.saveChanges(SAVE_MODE);
        }
        this.LogOut();
    },

	startIdleTimer: function()
	{
		this.stopIdleTimer();
		this.timer = setTimeout(
			function()
			{
				WebMail.stopIdleTimer();
				WebMail.SaveChangesAndLogout();
			},
			this.Settings.idleSessionTimeout * 1000
        );
	},

	stopIdleTimer: function()
	{
		if (this.timer != null) {
			clearTimeout(this.timer);
			this.timer = null;
		}
	}
};

function CSettingsList()
{
	this.type = TYPE_SETTINGS_LIST;
	//from attributes
	this.allowAddAccount = false;
	this.allowBodySize = false;
	this.allowCalendar = true;
	this.allowChangeAccountSettings = false;
	this.allowChangeInterfaceSettings = false;
	this.allowComposeMessage = true;
	this.allowContacts = true;
	this.allowFirstCharacterSearch = false;
	this.allowForwardMessage = true;
	this.allowInsertImage = true;
	this.allowReplyMessage = true;
	this.attachmentSizeLimit = 0;
	this.autoCheckMailInterval = 0;
	this.bAllowIdentities = false;
	this.bAllowDigDos = false;
	this.bAllowServerFileupload = false;
	if (typeof(CAttachmentsSelectionPane) === 'function') {
		this.bAllowServerFileupload = true;
	}
	this.bAllowCustomEmailTab = false;
	if (typeof(CCustomPane) === 'function') {
		this.bAllowCustomEmailTab = true;
	}
	this.bAutoSave = true;
	this.bReplyPaneInBothLayouts = false;
	this.bShowGlobalContacts = false;
	this.bShowPersonalContacts = false;
	this.bShowMultipleContacts = false;
	this.contactsPerPage = 20;
	this.idleSessionTimeout = 0;
	this.iSaveMail = SAVE_MAIL_HIDDEN;
	this.iUserId = -1;
	this.layoutSide = true;
	this.maxBodySize = 20;
	this.maxSubjectSize = 255;
	this.mobileSyncEnable = false;
	this.msgsPerPage = 20;
	this.outlookSyncEnable = false;
	this.richDefEditor = false;
	this.timeFormat = 0;
	this.timeOffset = 0;
	this.useImapTrash = false;
	this.sContactNameFormat = CONTACT_NAME_FORMAT_FIRSTNAME;

	//from nodes
	this.defDateFormat = 'MM/DD/YYYY';
	this.defLang = 'English';
	this.defSkin = 'AfterLogic';
	this.sDigDosName = '';
	this.sLastLogin = '';

	//from cookies
	this.hideFolders = false;
	this.horizResizerPos = 200;
	this.msgResizerPos = 720;
	this.vertResizerPos = 125;
	this.iMiniWindowWidth = 0;
	this.iMiniWindowHeight = 0;

	//from browser
	this.iFlashVersion = FlashVersion();
}

CSettingsList.prototype = {
	getStringDataKeys: function()
	{
		return '';
	},

	getFromXml: function(rootElement)
	{
		WebMail.bIsDemo = XmlHelper.getBoolAttributeByName(rootElement, 'is_demo', WebMail.bIsDemo);

		this.allowAddAccount = XmlHelper.getBoolAttributeByName(rootElement, 'allow_add_account', this.allowAddAccount);
		this.allowBodySize = XmlHelper.getBoolAttributeByName(rootElement, 'allow_body_size', this.allowBodySize);
		this.allowCalendar = XmlHelper.getBoolAttributeByName(rootElement, 'allow_calendar', this.allowCalendar);
		this.allowChangeAccountSettings = XmlHelper.getBoolAttributeByName(rootElement, 'allow_change_account_settings', this.allowChangeAccountSettings);
		this.allowChangeInterfaceSettings = XmlHelper.getBoolAttributeByName(rootElement, 'allow_change_interface_settings', this.allowChangeInterfaceSettings);
		this.allowComposeMessage = XmlHelper.getBoolAttributeByName(rootElement, 'allow_compose_message', this.allowComposeMessage);
		this.allowFirstCharacterSearch = XmlHelper.getBoolAttributeByName(rootElement, 'allow_first_character_search', this.allowFirstCharacterSearch);
		this.allowForwardMessage = XmlHelper.getBoolAttributeByName(rootElement, 'allow_forward_message', this.allowForwardMessage);
		this.allowInsertImage = XmlHelper.getBoolAttributeByName(rootElement, 'allow_insert_image', this.allowInsertImage);
		this.allowReplyMessage = XmlHelper.getBoolAttributeByName(rootElement, 'allow_reply_message', this.allowReplyMessage);
		this.attachmentSizeLimit = XmlHelper.getIntAttributeByName(rootElement, 'attachment_size_limit', this.attachmentSizeLimit);
		this.autoCheckMailInterval = XmlHelper.getIntAttributeByName(rootElement, 'auto_checkmail_interval', this.autoCheckMailInterval);
		this.bAllowIdentities = XmlHelper.getBoolAttributeByName(rootElement, 'allow_identities', this.bAllowIdentities);
		this.bAutoSave = XmlHelper.getBoolAttributeByName(rootElement, 'autosave', this.bAutoSave);
		this.bShowGlobalContacts = XmlHelper.getBoolAttributeByName(rootElement, 'show_global_contacts', this.bShowGlobalContacts);
		this.bShowPersonalContacts = XmlHelper.getBoolAttributeByName(rootElement, 'show_personal_contacts', this.bShowPersonalContacts);
		this.bShowMultipleContacts = XmlHelper.getBoolAttributeByName(rootElement, 'show_multiple_contacts', this.bShowMultipleContacts);
		this.allowContacts = this.bShowGlobalContacts || this.bShowPersonalContacts;
		this.contactsPerPage = XmlHelper.getIntAttributeByName(rootElement, 'contacts_per_page', this.contactsPerPage);
		this.iMiniWindowWidth = XmlHelper.getIntAttributeByName(rootElement, 'mini_window_width', this.iMiniWindowWidth);
		this.iMiniWindowHeight = XmlHelper.getIntAttributeByName(rootElement, 'mini_window_height', this.iMiniWindowHeight);
		this.iSaveMail = XmlHelper.getIntAttributeByName(rootElement, 'save_mail', this.iSaveMail);
		this.iUserId = XmlHelper.getIntAttributeByName(rootElement, 'id', this.iUserId);
		this.idleSessionTimeout = XmlHelper.getIntAttributeByName(rootElement, 'idle_session_timeout', this.idleSessionTimeout);
		var layout = XmlHelper.getIntAttributeByName(rootElement, 'layout', 0);
		this.layoutSide = (layout == 0) ? true : false;
		this.maxBodySize = XmlHelper.getIntAttributeByName(rootElement, 'max_body_size', this.maxBodySize);
		this.maxSubjectSize = XmlHelper.getIntAttributeByName(rootElement, 'max_subject_size', this.maxSubjectSize);
		this.mobileSyncEnable = XmlHelper.getBoolAttributeByName(rootElement, 'mobile_sync_enable_system', this.mobileSyncEnable);
		this.msgsPerPage = XmlHelper.getIntAttributeByName(rootElement, 'msgs_per_page', this.msgsPerPage);
		this.outlookSyncEnable = XmlHelper.getBoolAttributeByName(rootElement, 'outlook_sync_enable', this.outlookSyncEnable);
		this.richDefEditor = XmlHelper.getBoolAttributeByName(rootElement, 'def_editor', this.richDefEditor);
		this.timeFormat = XmlHelper.getIntAttributeByName(rootElement, 'time_format', this.timeFormat);
		this.timeOffset = XmlHelper.getIntAttributeByName(rootElement, 'def_timezone', this.timeOffset);
		this.useImapTrash = XmlHelper.getBoolAttributeByName(rootElement, 'imap4_delete_like_pop3', this.useImapTrash);
		this.sContactNameFormat = XmlHelper.getIntAttributeByName(rootElement, 'contact_full_name_format', this.sContactNameFormat);

		var eDateFormat = XmlHelper.getFirstChildNodeByName(rootElement, 'def_date_fmt');
		this.defDateFormat = XmlHelper.getFirstChildValue(eDateFormat, this.defDateFormat);

		var defLangNode = XmlHelper.getFirstChildNodeByName(rootElement, 'def_lang');
		this.defLang = XmlHelper.getFirstChildValue(defLangNode, this.defLang);

		var defSkinNode = XmlHelper.getFirstChildNodeByName(rootElement, 'def_skin');
		this.defSkin = XmlHelper.getFirstChildValue(defSkinNode, this.defSkin);

		var eLastLogin = XmlHelper.getFirstChildNodeByName(rootElement, 'last_login');
		this.sLastLogin = XmlHelper.getFirstChildValue(eLastLogin, this.sLastLogin);

		this._readFromCookies();
	},

	copy: function (newSettings)
	{
		if (null != newSettings.autoCheckMailInterval) {
			this.autoCheckMailInterval = newSettings.autoCheckMailInterval;
		}
		if (null != newSettings.contactsPerPage) {
			this.contactsPerPage = newSettings.contactsPerPage;
		}
		if (null != newSettings.layoutSide) {
			this.layoutSide = newSettings.layoutSide;
		}
		if (null != newSettings.msgsPerPage) {
			this.msgsPerPage = newSettings.msgsPerPage;
		}
		if (null != newSettings.defEditor) {
			this.richDefEditor = (newSettings.defEditor == 1);
		}
		if (null != newSettings.timeFormat) {
			this.timeFormat = newSettings.timeFormat;
		}
		if (null != newSettings.timeOffset) {
			this.timeOffset = newSettings.timeOffset;
		}
		if (null != newSettings.defDateFormat) {
			this.defDateFormat = newSettings.defDateFormat;
		}
		if (null != newSettings.defLang) {
			this.defLang = newSettings.defLang;
		}
		if (null != newSettings.defSkin) {
			this.defSkin = newSettings.defSkin;
		}
	},

	disableAutoSave: function ()
	{
		this.bAutoSave = false;
	},

	setDigDosData: function (oDossiers)
	{
		if (oDossiers.aItems.length > 0 && oDossiers.sFolderName.length > 0) {
			this.bAllowDigDos = true;
			this.sDigDosName = oDossiers.sFolderName;
		}
	},

	getMiniWindowWidth: function ()
	{
		this.iMiniWindowWidth = Cookies.readInt('wm_mini_window_width', this.iMiniWindowWidth);
		return this.iMiniWindowWidth;
	},

	getMiniWindowHeight: function ()
	{
		this.iMiniWindowHeight = Cookies.readInt('wm_mini_window_height', this.iMiniWindowHeight);
		return this.iMiniWindowHeight;
	},

	_readFromCookies: function ()
	{
		var
			iIndex = 0,
			iLen = InboxHeaders.length
		;

		for (; iIndex < iLen; iIndex++) {
			InboxHeaders[iIndex].PermanentWidth = InboxHeaders[iIndex].Width;
			InboxHeaders[iIndex].Width = Cookies.readInt('wm_column_' + iIndex, InboxHeaders[iIndex].Width);
		}

		this.hideFolders = Cookies.readBool('wm_hide_folders', this.hideFolders);
		this.horizResizerPos = Cookies.readInt('wm_horiz_resizer', this.horizResizerPos);
		this.msgResizerPos = Cookies.readInt('wm_msg_resizer', this.msgResizerPos);
		this.vertResizerPos = Cookies.readInt('wm_vert_resizer', this.vertResizerPos);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
