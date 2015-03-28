
/*!
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commerical version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

(function ($, window, ko, crossroads, hasher) {

'use strict';

var
	/**
	 * @type {Object}
	 */
	Consts = {},

	/**
	 * @type {Object}
	 */
	Enums = {},

	/**
	 * @type {Object.<Function>}
	 */
	Utils = {},

	/**
	 * @type {Object}
	 */
	I18n = window.pSevenI18N || {},

	/**
	 * @type {CApp|Object}
	 */
	App = {},
	
	/**
	 * @type {Object.<Function>}
	 */
	AfterLogicApi = {},

	/**
	 * @type {AjaxAppDataResponse|Object}
	 */
	AppData = window.pSevenAppData || {},

	/**
	 * @type {boolean}
	 */
	bExtApp = false,
			
	/**
	 * @type {boolean}
	 */
	bMobileApp = false,

	$html = $('html'),

	/**
	 * @type {boolean}
	 */
	bIsIosDevice = -1 < navigator.userAgent.indexOf('iPhone') ||
		-1 < navigator.userAgent.indexOf('iPod') ||
		-1 < navigator.userAgent.indexOf('iPad'),

	/**
	 * @type {boolean}
	 */
	bIsAndroidDevice = -1 < navigator.userAgent.toLowerCase().indexOf('android'),

	/**
	 * @type {boolean}
	 */
	bMobileDevice = bIsIosDevice || bIsAndroidDevice,

	aViewMimeTypes = [
		'image/jpeg', 'image/png', 'image/gif',
		'text/html', 'text/plain', 'text/css',
		'text/rfc822-headers', 'message/delivery-status',
		'application/x-httpd-php', 'application/javascript',
		'application/pdf'
	]
;

if (window.Modernizr && navigator)
{
	// v = 15;
	window.Modernizr.addTest('pdf', function() {
		var aMimes = navigator.mimeTypes, iIndex = 0, iLen = aMimes.length;
		for (; iIndex < iLen; iIndex++)
		{
			if ('application/pdf' === aMimes[iIndex].type)
			{
				return true;
			}
		}
		
		return false;
	});
}

if (!Date.now)
{
	Date.now = function () {
		return (new Date()).getTime();
	};
}
bMobileApp = true;

if (window.Modernizr && navigator)
{
	window.Modernizr.addTest('native-android-browser', function() {
		return navigator.userAgent === 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.34 Safari/534.24';
	});
}


/**
 * @constructor
 */
function CBrowser()
{
	this.ie11 = !!navigator.userAgent.match(/Trident.*rv[ :]*11\./);
	this.ie = (/msie/.test(navigator.userAgent.toLowerCase()) && !window.opera) || this.ie11;
	this.ieVersion = this.getIeVersion();
	this.ie8AndBelow = this.ie && this.ieVersion <= 8;
	this.ie9AndBelow = this.ie && this.ieVersion <= 9;
	this.ie10AndAbove = this.ie && this.ieVersion >= 10;
	this.opera = !!window.opera || /opr/.test(navigator.userAgent.toLowerCase());
	this.firefox = /firefox/.test(navigator.userAgent.toLowerCase());
	this.chrome = /chrome/.test(navigator.userAgent.toLowerCase()) && !/opr/.test(navigator.userAgent.toLowerCase());
	this.chromeIos = /crios/.test(navigator.userAgent.toLowerCase());
	this.safari = /safari/.test(navigator.userAgent.toLowerCase()) && !this.chromeIos;
}

CBrowser.prototype.getIeVersion = function ()
{
	var
		sUa = navigator.userAgent.toLowerCase(),
		iVersion = Utils.pInt(sUa.slice(sUa.indexOf('msie') + 4, sUa.indexOf(';', sUa.indexOf('msie') + 4)))
	;
	
	if (this.ie11)
	{
		iVersion = 11;
	}
	
	return iVersion;
};


/**
 * @constructor
 */
function CAjax()
{
	this.sUrl = '?/Ajax/';
	this.requests = ko.observableArray([]);
	// not "computed", because "reguests" is frequently updated
	this.openedRequestsCount = ko.observable(0);
	this.requests.subscribe(function () {
		this.openedRequestsCount(this.requests().length);
	}, this);
	
	this.aActionsWithoutAuthForSend = ['SystemLogin', 'SystemUpdateLanguageOnLogin', 'SystemLogout', 'AccountCreate', 
		'SystemSetMobile', 'AccountRegister', 'AccountGetForgotQuestion', 'AccountValidateForgotQuestion', 'AccountChangeForgotPassword'];
	
	this.aActionsWithoutAuthForSendExt = ['SocialRegister', 'HelpdeskRegister', 'HelpdeskForgot', 
			'HelpdeskLogin', 'HelpdeskForgotChangePassword', 'SystemLogout', 'CalendarList',
			'CalendarEventList', 'FilesPub'];
}

/**
 * @param {string=} sAction = ''
 */
CAjax.prototype.AddActionsWithoutAuthForSendExt = function (sAction)
{
	sAction = Utils.isUnd(sAction) ? '' : sAction;
	if (sAction !== '')
	{
		if (_.indexOf(this.aActionsWithoutAuthForSendExt, sAction) === -1)
		{
			this.aActionsWithoutAuthForSendExt.push(sAction);
		}
	}
};

/**
 * @param {string=} sAction = ''
 */
CAjax.prototype.AddActionsWithoutAuthForSend = function (sAction)
{
	sAction = Utils.isUnd(sAction) ? '' : sAction;
	if (sAction !== '')
	{
		if (_.indexOf(this.aActionsWithoutAuthForSend, sAction) === -1)
		{
			this.aActionsWithoutAuthForSend.push(sAction);
		}
	}
};

/**
 * @param {string=} sAction = ''
 * @returns {Boolean}
 */
CAjax.prototype.hasOpenedRequests = function (sAction)
{
	sAction = Utils.isUnd(sAction) ? '' : sAction;
	
	this.requests(_.filter(this.requests(), function (oReq) {
		var
			bComplete = oReq && oReq.Xhr.readyState === 4,
			bAbort = !oReq || oReq.Xhr.readyState === 0 && oReq.Xhr.statusText === 'abort',
			bSameAction = (sAction === '') || oReq && (oReq.Parameters.Action === sAction)
		;
		
		return oReq && !bComplete && !bAbort && bSameAction;
	}));
	
	return this.requests().length > 0;
};

/**
 * @return {boolean}
 */
CAjax.prototype.isSearchMessages = function ()
{
	var bSearchMessages = false;
	
	_.each(this.requests(), function (oReq) {
		if (oReq && oReq.Parameters && oReq.Parameters.Action === 'MessagesGetList' && oReq.Parameters.Search !== '')
		{
			bSearchMessages = true;
		}
	}, this);
	
	return bSearchMessages;
};

/**
 * @param {string} sAction
 */
CAjax.prototype.isAllowedActionWithoutAuth = function (sAction)
{
	return _.indexOf(this.aActionsWithoutAuthForSend, sAction) !== -1;
};

CAjax.prototype.isAllowedExtAction = function (sAction)
{
	return sAction === 'SocialRegister' || sAction === 'HelpdeskRegister' || sAction === 'HelpdeskForgot' || sAction === 'HelpdeskLogin' || sAction === 'SystemLogout';
};

/**
 * @param {Object} oParameters
 * @param {Function=} fResponseHandler
 * @param {Object=} oContext
 * @param {Function=} fDone
 */
CAjax.prototype.doSend = function (oParameters, fResponseHandler, oContext, fDone)
{
	var
		doneFunc = _.bind((fDone || null), this, oParameters, fResponseHandler, oContext),
		failFunc = _.bind(this.fail, this, oParameters, fResponseHandler, oContext),
		alwaysFunc = _.bind(this.always, this, oParameters),
		oXhr = null
	;
	
	if (AfterLogicApi.runPluginHook)
	{
		AfterLogicApi.runPluginHook('ajax-default-request', [oParameters.Action, oParameters]);
	}
	
	if (AppData.Token)
	{
		oParameters.Token = AppData.Token;
	}

	this.abortRequests(oParameters);
	
	Utils.log('Ajax request send', oParameters.Action, oParameters);
	
	oXhr = $.ajax({
		url: this.sUrl,
		type: 'POST',
		async: true,
		dataType: 'json',
		data: oParameters,
		success: doneFunc,
		error: failFunc,
		complete: alwaysFunc
	});
	
	this.requests().push({Parameters: oParameters, Xhr: oXhr});
};

/**
 * @param {Object} oParameters
 * @param {Function=} fResponseHandler
 * @param {Object=} oContext
 */
CAjax.prototype.send = function (oParameters, fResponseHandler, oContext)
{
	var
		bCurrentAccountId = oParameters.AccountID === undefined,
		bAccountExists = bCurrentAccountId || AppData.Accounts.hasAccountWithId(oParameters.AccountID)
	;
	
	if (oParameters && (AppData.Auth && bAccountExists || this.isAllowedActionWithoutAuth(oParameters.Action)))
	{
		if (bCurrentAccountId && oParameters.Action !== 'Login')
		{
			oParameters.AccountID = AppData.Accounts.currentId();
		}
		
		this.doSend(oParameters, fResponseHandler, oContext, this.done);
	}
};

/**
 * @param {Object} oParameters
 * @param {Function=} fResponseHandler
 * @param {Object=} oContext
 */
CAjax.prototype.sendExt = function (oParameters, fResponseHandler, oContext)
{	
	var
		bAllowWithoutAuth = _.indexOf(this.aActionsWithoutAuthForSendExt, oParameters.Action) !== -1
	;
	
	if (oParameters && (AppData.Auth || bAllowWithoutAuth))
	{
		if (AppData.TenantHash)
		{
			oParameters.TenantHash = AppData.TenantHash;
		}
		
		this.doSend(oParameters, fResponseHandler, oContext, this.doneExt);
	}
};

/**
 * @param {Object} oParameters
 */
CAjax.prototype.abortRequests = function (oParameters)
{
	switch (oParameters.Action)
	{
		case 'MessageMove':
		case 'MessageDelete':
			this.abortRequestByActionName('MessagesGetList', {'Folder': oParameters.Folder});
			this.abortRequestByActionName('MessageGet');
			break;
		case 'MessagesGetList':
		case 'MessageSetSeen':
		case 'MessageSetFlagged':
			this.abortRequestByActionName('MessagesGetList', {'Folder': oParameters.Folder});
			break;
		case 'MessagesSetAllSeen':
			this.abortRequestByActionName('MessagesGetList', {'Folder': oParameters.Folder});
			this.abortRequestByActionName('MessagesGetListByUids', {'Folder': oParameters.Folder});
			break;
		case 'FolderClear':
			this.abortRequestByActionName('MessagesGetList', {'Folder': oParameters.Folder});
			
			// FoldersGetRelevantInformation-request aborted during folder cleaning, not to get the wrong information.
			this.abortRequestByActionName('FoldersGetRelevantInformation');
			break;
		case 'ContactList':
		case 'ContactGlobalList':
			this.abortRequestByActionName('ContactList');
			this.abortRequestByActionName('ContactGlobalList');
			break;
		case 'ContactGet':
		case 'ContactGlobal':
			this.abortRequestByActionName('ContactGet');
			this.abortRequestByActionName('ContactGlobal');
			break;
		case 'CalendarEventUpdate':
			this.abortRequestByActionName('CalendarEventUpdate', {'calendarId': oParameters.calendarId, 'uid': oParameters.uid});
			break;
		case 'CalendarList':
			this.abortRequestByActionName('CalendarList');
			break;
		case 'CalendarEventList':
			this.abortRequestByActionName('CalendarEventList');
			break;
	}
};

/**
 * @param {string} sAction
 * @param {Object=} oParameters
 */
CAjax.prototype.abortRequestByActionName = function (sAction, oParameters)
{
	var bDoAbort;
	
	_.each(this.requests(), function (oReq, iIndex) {
		bDoAbort = false;
		
		if (oReq && oReq.Parameters.Action === sAction)
		{
			switch (sAction)
			{
				case 'MessagesGetList':
					if (oParameters.Folder === oReq.Parameters.Folder)
					{
						bDoAbort = true;
					}
					break;
				case 'CalendarEventUpdate':
					if (oParameters.calendarId === oReq.Parameters.calendarId && 
							oParameters.uid === oReq.Parameters.uid)
					{
						bDoAbort = true;
					}
					break;
				default:
					bDoAbort = true;
					break;
			}
		}
		if (bDoAbort)
		{
			oReq.Xhr.abort();
			this.requests()[iIndex] = undefined;
		}
	}, this);
	
	this.requests(_.compact(this.requests()));
};

CAjax.prototype.abortAllRequests = function ()
{
	_.each(this.requests(), function (oReq) {
		if (oReq)
		{
			oReq.Xhr.abort();
		}
	}, this);
	
	this.requests([]);
};

/**
 * @param {Object} oParameters
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 * @param {{Result:boolean}} oData
 * @param {string} sType
 * @param {Object} oXhr
 */
CAjax.prototype.done = function (oParameters, fResponseHandler, oContext, oData, sType, oXhr)
{
	var
		bAllowedActionWithoutAuth = this.isAllowedActionWithoutAuth(oParameters.Action),
		bAccountExists = AppData.Accounts.hasAccountWithId(oParameters.AccountID),
		bDefaultAccount = (oParameters.AccountID === AppData.Accounts.defaultId())
	;
	
	Utils.log('Ajax request done', oParameters.Action, sType, Utils.getAjaxDataForLog(oParameters.Action, oData), oParameters);
	
	if (bAllowedActionWithoutAuth || bAccountExists)
	{
		if (oData && !oData.Result)
		{
			switch (oData.ErrorCode)
			{
				case Enums.Errors.InvalidToken:
					if (!bAllowedActionWithoutAuth)
					{
						App.tokenProblem();
					}
					break;
				case Enums.Errors.AuthError:
					if (bDefaultAccount && !bAllowedActionWithoutAuth)
					{
						this.abortAllRequests();
						App.authProblem();
					}
					break;
			}
		}

		this.executeResponseHandler(fResponseHandler, oContext, oData, oParameters);
	}
};

/**
 * @param {Object} oParameters
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 * @param {{Result:boolean}} oData
 * @param {string} sType
 * @param {Object} oXhr
 */
CAjax.prototype.doneExt = function (oParameters, fResponseHandler, oContext, oData, sType, oXhr)
{
	this.executeResponseHandler(fResponseHandler, oContext, oData, oParameters);
};

/**
 * @param {Object} oParameters
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 * @param {Object} oXhr
 * @param {string} sType
 * @param {string} sErrorText
 */
CAjax.prototype.fail = function (oParameters, fResponseHandler, oContext, oXhr, sType, sErrorText)
{
	var oData = {'Result': false, 'ErrorCode': 0};
	
	Utils.log('Ajax request fail', oParameters.Action, sType, oParameters);
	
	switch (sType)
	{
		case 'abort':
			oData = {'Result': false, 'ErrorCode': Enums.Errors.NotDisplayedError};
			break;
		default:
		case 'error':
		case 'parseerror':
			if (sErrorText === '')
			{
				oData = {'Result': false, 'ErrorCode': Enums.Errors.NotDisplayedError};
			}
			else
			{
				oData = {'Result': false, 'ErrorCode': Enums.Errors.DataTransferFailed};
			}
			break;
	}
	
	this.executeResponseHandler(fResponseHandler, oContext, oData, oParameters);
};

/**
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 * @param {Object} oData
 * @param {Object} oParameters
 */
CAjax.prototype.executeResponseHandler = function (fResponseHandler, oContext, oData, oParameters)
{
	if (!oData)
	{
		oData = {'Result': false, 'ErrorCode': 0};
	}
	
	if (AfterLogicApi.runPluginHook)
	{
		AfterLogicApi.runPluginHook('ajax-default-response', [oParameters.Action, oData]);
	}
	
	if (typeof fResponseHandler === 'function' && !oData['StopExecuteResponse'])
	{
		fResponseHandler.apply(oContext, [oData, oParameters]);
	}
};

/**
 * @param {Object} oXhr
 * @param {string} sType
 * @param {{Action:string}} oParameters
 */
CAjax.prototype.always = function (oParameters, oXhr, sType)
{
	_.each(this.requests(), function (oReq, iIndex) {
		if (oReq && _.isEqual(oReq.Parameters, oParameters))
		{
			this.requests()[iIndex] = undefined;
		}
	}, this);
	
	this.requests(_.compact(this.requests()));

	Utils.checkConnection(oParameters.Action, sType);
	
	if (App.Prefetcher && sType !== 'abort' && sType !== 'parsererror' && !this.hasOpenedRequests())
	{
		App.Prefetcher.start();
	}
};



/**
 * @enum {string}
 */
Enums.Screens = {
	'Login': 'login',
	'Information': 'information',
	'Header': 'header',
	'Mailbox': 'mailbox',
	'SingleMessageView': 'single-message-view',
	'Compose': 'compose',
	'SingleCompose': 'single-compose',
	'Settings': 'settings',
	'Contacts': 'contacts',
	'Calendar': 'calendar',
	'FileStorage': 'files',
	'Helpdesk': 'helpdesk',
	'SingleHelpdesk': 'single-helpdesk'
};

/**
 * @enum {number}
 */
Enums.CalendarDefaultTab = {
	'Day': 1,
	'Week': 2,
	'Month': 3
};

/**
 * @enum {number}
 */
Enums.TimeFormat = {
	'F24': '0',
	'F12': '1'
};

/**
 * @enum {number}
 */
Enums.Errors = {
	'InvalidToken': 101,
	'AuthError': 102,
	'DataBaseError': 104,
	'LicenseProblem': 105,
	'DemoLimitations': 106,
	'Captcha': 107,
	'AccessDenied': 108,
	'CanNotGetMessage': 202,
	'ImapQuota': 205,
	'NotSavedInSentItems': 304,
	'NoRequestedMailbox': 305,
	'CanNotChangePassword': 502,
	'AccountOldPasswordNotCorrect': 503,
	'FetcherIncServerNotAvailable': 702,
	'FetcherLoginNotCorrect': 703,
	'HelpdeskThrowInWebmail': 805,
	'HelpdeskUserNotExists': 807,
	'HelpdeskUserNotActivated': 808,
	'IncorrectFileExtension': 811,
	'MailServerError': 901,
	'DataTransferFailed': 1100,
	'NotDisplayedError': 1155
};

/**
 * @enum {number}
 */
Enums.FolderTypes = {
	'Inbox': 1,
	'Sent': 2,
	'Drafts': 3,
	'Spam': 4,
	'Trash': 5,
	'Virus': 6,
	'Starred': 7,
	'System': 9,
	'User': 10
};

/**
 * @enum {string}
 */
Enums.FolderFilter = {
	'Flagged': 'flagged',
	'Unseen': 'unseen'
};

/**
 * @enum {number}
 */
Enums.LoginFormType = {
	'Email': 0,
	'Login': 3,
	'Both': 4
};

/**
 * @enum {number}
 */
Enums.LoginSignMeType = {
	'DefaultOff': 0,
	'DefaultOn': 1,
	'Unuse': 2
};

/**
 * @enum {string}
 */
Enums.ReplyType = {
	'Reply': 'reply',
	'ReplyAll': 'reply-all',
	'Resend': 'resend',
	'Forward': 'forward'
};

/**
 * @enum {number}
 */
Enums.Importance = {
	'Low': 5,
	'Normal': 3,
	'High': 1
};

/**
 * @enum {number}
 */
Enums.Sensivity = {
	'Nothing': 0,
	'Confidential': 1,
	'Private': 2,
	'Personal': 3
};

/**
 * @enum {string}
 */
Enums.ContactEmailType = {
	'Personal': 'Personal',
	'Business': 'Business',
	'Other': 'Other'
};

/**
 * @enum {string}
 */
Enums.ContactPhoneType = {
	'Mobile': 'Mobile',
	'Personal': 'Personal',
	'Business': 'Business'
};

/**
 * @enum {string}
 */
Enums.ContactAddressType = {
	'Personal': 'Personal',
	'Business': 'Business'
};

/**
 * @enum {string}
 */
Enums.ContactSortType = {
	'Email': 'Email',
	'Name': 'Name',
	'Frequency': 'Frequency'
};

/**
 * @enum {number}
 */
Enums.SaveMail = {
	'Hidden': 0,
	'Checked': 1,
	'Unchecked': 2
};

/**
 * @enum {string}
 */
Enums.SettingsTab = {
	'Common': 'common',
	'EmailAccounts': 'accounts',
	'Calendar': 'calendar',
	'MobileSync': 'mobile_sync',
	'OutLookSync': 'outlook_sync',
	'Helpdesk': 'helpdesk',
	'Pgp': 'pgp',
	'Services': 'services'
};

/**
 * @enum {string}
 */
Enums.AccountSettingsTab = {
	'Properties': 'properties',
	'Signature': 'signature',
	'Filters': 'filters',
	'Autoresponder': 'autoresponder',
	'Forward': 'forward',
	'Folders': 'folders',
	'FetcherInc': 'fetcher-inc',
	'FetcherOut': 'fetcher-out',
	'FetcherSig': 'fetcher-sig',
	'IdentityProperties': 'identity-properties',
	'IdentitySignature': 'identity-signature'
};
/**
 * @enum {number}
 */
Enums.ContactsGroupListType = {
	'Personal': 0,
	'SubGroup': 1,
	'Global': 2,
	'SharedToAll': 3,
	'All': 4
};

/**
 * @enum {string}
 */
Enums.IcalType = {
	Request: 'REQUEST',
	Reply: 'REPLY',
	Cancel: 'CANCEL',
	Save: 'SAVE'
};

/**
 * @enum {string}
 */
Enums.IcalConfig = {
	Accepted: 'ACCEPTED',
	Declined: 'DECLINED',
	Tentative: 'TENTATIVE',
	NeedsAction: 'NEEDS-ACTION'
};

/**
 * @enum {number}
 */
Enums.IcalConfigInt = {
	Accepted: 1,
	Declined: 2,
	Tentative: 3,
	NeedsAction: 0
};

/**
 * @enum {number}
 */
Enums.Key = {
	'Tab': 9,
	'Enter': 13,
	'Shift': 16,
	'Ctrl': 17,
	'Esc': 27,
	'Space': 32,
	'PageUp': 33,
	'PageDown': 34,
	'End': 35,
	'Home': 36,
	'Up': 38,
	'Down': 40,
	'Left': 37,
	'Right': 39,
	'Del': 46,
	'Six': 54,
	'a': 65,
	'b': 66,
	'c': 67,
	'f': 70,
	'i': 73,
	'k': 75,
	'n': 78,
	'p': 80,
	'q': 81,
	'r': 82,
	's': 83,
	'u': 85,
	'v': 86,
	'y': 89,
	'z': 90,
	'F5': 116,
	'Comma': 188,
	'Dot': 190,
	'Dash': 192,
	'Apostrophe': 222
};

Enums.MouseKey = {
	'Left': 0,
	'Middle': 1,
	'Right': 2
};

/**
 * @enum {number}
 */
Enums.FileStorageType = {
	'Personal': 'personal',
	'Corporate': 'corporate',
	'Shared': 'shared',
	'GoogleDrive': 'google',
	'Dropbox': 'dropbox'
};

/**
 * @enum {number}
 */
Enums.FileStorageLinkType = {
	'Unknown': 0,
	'GoogleDrive': 1,
	'Dropbox': 2
};

/**
 * @enum {number}
 */
Enums.HelpdeskThreadStates = {
	'None': 0,
	'Pending': 1,
	'Waiting': 2,
	'Answered': 3,
	'Resolved': 4,
	'Deferred': 5
};

/**
 * @enum {number}
 */
Enums.HelpdeskPostType = {
	'Normal': 0,
	'Internal': 1,
	'System': 2
};

/**
 * @enum {number}
 */
Enums.HelpdeskFilters = {
	'All': 0,
	'Pending': 1,
	'Resolved': 2,
	'InWork': 3,
	'Open': 4,
	'Archived': 9
};

/**
 * @enum {number}
 */
Enums.CalendarAccess = {
	'Full': 0,
	'Write': 1,
	'Read': 2
};

/**
 * @enum {number}
 */
Enums.CalendarEditRecurrenceEvent = {
	'None': 0,
	'OnlyThisInstance': 1,
	'AllEvents': 2
};

/**
 * @enum {number}
 */
Enums.CalendarRepeatPeriod = {
	'None': 0,
	'Daily': 1,
	'Weekly': 2,
	'Monthly': 3,
	'Yearly': 4
};

Enums.PhoneAction = {
	'Offline': 'offline',
	'OfflineError': 'offline_error',
	'OfflineInit': 'offline_init',
	'OfflineActive': 'offline_active',
	'Online': 'online',
	'OnlineActive': 'online_active',
	'Incoming': 'incoming',
	'IncomingConnect': 'incoming_connect',
	'Outgoing': 'outgoing',
	'OutgoingConnect': 'outgoing_connect',
	'Settings': 'settings'
};

Enums.HtmlEditorImageSizes = {
	'Small': 'small',
	'Medium': 'medium',
	'Large': 'large',
	'Original': 'original'
};

Enums.MobilePanel = {
	'Groups': 1,
	'Items': 2,
	'View': 3
};

Enums.PgpAction = {
	'Import': 'import',
	'Generate': 'generate',
	'Encrypt': 'encrypt',
	'Sign': 'sign',
	'EncryptSign': 'encrypt-sign',
	'Verify': 'ferify',
	'DecryptVerify': 'decrypt-ferify'
};

Enums.SocialType = {
	'Unknown': 0,
	'Google': 1,
	'Dropbox': 2,
	'Facebook': 3,
	'Twitter': 4,
	'Vkontakte': 5
};

Enums.notificationPermission = {
	'Granted': 'granted',
	'Denied': 'denied',
	'Default': 'default'
};

Enums.AnotherMessageComposedAnswer = {
	'Discard': 'Discard',
	'SaveAsDraft': 'SaveAsDraft',
	'Cancel': 'Cancel'
};


/**
 * @type {Function}
 */
Utils.inArray = $.inArray;

/**
 * @type {Function}
 */
Utils.isFunc = $.isFunction;

/**
 * @type {Function}
 */
Utils.trim = $.trim;

/**
 * @type {Function}
 */
Utils.emptyFunction = function () {};

/**
 * @param {*} mValue
 * 
 * @return {boolean}
 */
Utils.isUnd = function (mValue)
{
	return undefined === mValue;
};

/**
 * @param {*} oValue
 * 
 * @return {boolean}
 */
Utils.isNull = function (oValue)
{
	return null === oValue;
};

/**
 * @param {*} oValue
 * 
 * @return {boolean}
 */
Utils.isNormal = function (oValue)
{
	return !Utils.isUnd(oValue) && !Utils.isNull(oValue);
};

/**
 * @param {(string|number)} mValue
 * 
 * @return {boolean}
 */
Utils.isNumeric = function (mValue)
{
	return Utils.isNormal(mValue) ? (/^[1-9]+[0-9]*$/).test(mValue.toString()) : false;
};

/**
 * @param {*} mValue
 * 
 * @return {number}
 */
Utils.pInt = function (mValue)
{
	var iValue = window.parseInt(mValue, 10);
	if (isNaN(iValue))
	{
		iValue = 0;
	}
	return iValue;
};

/**
 * @param {*} mValue
 * 
 * @return {string}
 */
Utils.pString = function (mValue)
{
	return Utils.isNormal(mValue) ? mValue.toString() : '';
};

/**
 * @param {*} aValue
 * @param {number=} iArrayLen
 * 
 * @return {boolean}
 */
Utils.isNonEmptyArray = function (aValue, iArrayLen)
{
	iArrayLen = iArrayLen || 1;
	
	return _.isArray(aValue) && iArrayLen <= aValue.length;
};

/**
 * @param {Object} oObject
 * @param {string} sName
 * @param {*} mValue
 */
Utils.pImport = function (oObject, sName, mValue)
{
	oObject[sName] = mValue;
};

/**
 * @param {Object} oObject
 * @param {string} sName
 * @param {*} mDefault
 * @return {*}
 */
Utils.pExport = function (oObject, sName, mDefault)
{
	return Utils.isUnd(oObject[sName]) ? mDefault : oObject[sName];
};

/**
 * @param {string} sText
 * 
 * @return {string}
 */
Utils.encodeHtml = function (sText)
{
	return (sText) ? sText.toString()
		.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;').replace(/'/g, '&#039;') : '';
};

/**
 * @param {string} sKey
 * @param {?Object=} oValueList
 * @param {?string=} sDefaultValue
 * @param {number=} nPluralCount
 * 
 * @return {string}
 */
Utils.i18n = function (sKey, oValueList, sDefaultValue, nPluralCount) {

	var
		sValueName = '',
		sResult = Utils.isUnd(I18n[sKey]) ? (Utils.isNormal(sDefaultValue) ? sDefaultValue : sKey) : I18n[sKey]
	;

	if (!Utils.isUnd(nPluralCount))
	{
		sResult = (function (nPluralCount, sResult) {
			var
				nPlural = Utils.getPlural(AppData.User.DefaultLanguage, nPluralCount),
				aPluralParts = sResult.split('|')
			;

			return (aPluralParts && aPluralParts[nPlural]) ? aPluralParts[nPlural] : (
				aPluralParts && aPluralParts[0] ? aPluralParts[0] : sResult);

		}(nPluralCount, sResult));
	}

	if (Utils.isNormal(oValueList))
	{
		for (sValueName in oValueList)
		{
			if (oValueList.hasOwnProperty(sValueName))
			{
				sResult = sResult.replace('%' + sValueName + '%', oValueList[sValueName]);
			}
		}
	}

	return sResult;
};

/**
 * @param {number} iNum
 * @param {number} iDec
 * 
 * @return {number}
 */
Utils.roundNumber = function (iNum, iDec)
{
	return Math.round(iNum * Math.pow(10, iDec)) / Math.pow(10, iDec);
};

/**
 * @param {(number|string)} iSizeInBytes
 * 
 * @return {string}
 */
Utils.friendlySize = function (iSizeInBytes)
{
	var
		iBytesInKb = 1024,
		iBytesInMb = iBytesInKb * iBytesInKb,
		iBytesInGb = iBytesInKb * iBytesInKb * iBytesInKb
	;

	iSizeInBytes = Utils.pInt(iSizeInBytes);

	if (iSizeInBytes >= iBytesInGb)
	{
		return Utils.roundNumber(iSizeInBytes / iBytesInGb, 1) + Utils.i18n('MAIN/GIGABYTES');
	}
	else if (iSizeInBytes >= iBytesInMb)
	{
		return Utils.roundNumber(iSizeInBytes / iBytesInMb, 1) + Utils.i18n('MAIN/MEGABYTES');
	}
	else if (iSizeInBytes >= iBytesInKb)
	{
		return Utils.roundNumber(iSizeInBytes / iBytesInKb, 0) + Utils.i18n('MAIN/KILOBYTES');
	}

	return iSizeInBytes + Utils.i18n('MAIN/BYTES');
};

Utils.timeOutAction = (function () {

	var oTimeOuts = {};

	return function (sAction, fFunction, iTimeOut) {
		if (Utils.isUnd(oTimeOuts[sAction]))
		{
			oTimeOuts[sAction] = 0;
		}

		window.clearTimeout(oTimeOuts[sAction]);
		oTimeOuts[sAction] = window.setTimeout(fFunction, iTimeOut);
	};
}());

Utils.$log = null;
Utils.aLog = [];

Utils.log = function ()
{
	if (!AppData || !AppData.ClientDebug || !App.browser || App.browser.ie9AndBelow)
	{
		return;
	}
	
	function fCensor(mKey, mValue) {
		if (typeof(mValue) === 'string' && mValue.length > 50)
		{
			return mValue.substring(0, 50);
		}

		return mValue;
	}
	
	var
		$log = Utils.$log || $('<div style="display: none;"></div>').appendTo('body'),
		aNewRow = []
	;
	
	_.each(arguments, function (mArg) {
		var sRowPart = typeof(mArg) === 'string' ? mArg : JSON.stringify(mArg, fCensor);
		if (aNewRow.length === 0)
		{
			sRowPart = ' *** ' + sRowPart + ' *** ';
		}
		aNewRow.push(sRowPart);
	});
	
	aNewRow.push(moment().format(' *** D MMMM, YYYY, HH:mm:ss *** '));
	
	Utils.$log = $log;
	
	if (Utils.aLog.length > 200)
	{
		Utils.aLog.shift();
	}
	
	Utils.aLog.push(Utils.encodeHtml(aNewRow.join(', ')));
	
	$log.html(Utils.aLog.join('<br /><br />'));
};

/**
 * @param {string} sAction
 * @param {Object} oData
 * 
 * @returns {Object}
 */
Utils.getAjaxDataForLog = function (sAction, oData)
{
	var oDataForLog = oData;
	
	if (oData && oData.Result)
	{
		switch (sAction)
		{
			case 'MessagesGetList':
			case 'MessagesGetListByUids':
				oDataForLog = {
					'Result': {
						'Uids': oData.Result.Uids,
						'UidNext': oData.Result.UidNext,
						'FolderHash': oData.Result.FolderHash,
						'MessageCount': oData.Result.MessageCount,
						'MessageUnseenCount': oData.Result.MessageUnseenCount,
						'MessageResultCount': oData.Result.MessageResultCount
					}
				};
				break;
			case 'MessageGet':
				oDataForLog = {
					'Result': {
						'Folder': oData.Result.Folder,
						'Uid': oData.Result.Uid,
						'Subject': oData.Result.Subject,
						'Size': oData.Result.Size,
						'TextSize': oData.Result.TextSize,
						'From': oData.Result.From,
						'To': oData.Result.To
					}
				};
				break;
			case 'MessagesGetBodies':
				oDataForLog = {
					'Result': _.map(oData.Result, function (oMessage) {
						return {
							'Uid': oMessage.Uid,
							'Subject': oMessage.Subject
						};
					})
				};
				break;
		}
	}
	else if (oData)
	{
		oDataForLog = {
			'Result': oData.Result,
			'ErrorCode': oData.ErrorCode
		};
	}
	
	return oDataForLog;
};

/**
 * @param {string} sFullEmail
 * 
 * @return {Object}
 */
Utils.getEmailParts = function (sFullEmail)
{
	var
		iQuote1Pos = sFullEmail.indexOf('"'),
		iQuote2Pos = sFullEmail.indexOf('"', iQuote1Pos + 1),
		iLeftBrocketPos = sFullEmail.indexOf('<', iQuote2Pos),
		iPrevLeftBroketPos = -1,
		iRightBrocketPos = -1,
		sName = '',
		sEmail = ''
	;

	while (iLeftBrocketPos !== -1)
	{
		iPrevLeftBroketPos = iLeftBrocketPos;
		iLeftBrocketPos = sFullEmail.indexOf('<', iLeftBrocketPos + 1);
	}

	iLeftBrocketPos = iPrevLeftBroketPos;
	iRightBrocketPos = sFullEmail.indexOf('>', iLeftBrocketPos + 1);

	if (iLeftBrocketPos === -1)
	{
		sEmail = Utils.trim(sFullEmail);
	}
	else
	{
		sName = (iQuote1Pos === -1) ?
			Utils.trim(sFullEmail.substring(0, iLeftBrocketPos)) :
			Utils.trim(sFullEmail.substring(iQuote1Pos + 1, iQuote2Pos));

		sEmail = Utils.trim(sFullEmail.substring(iLeftBrocketPos + 1, iRightBrocketPos));
	}

	return {
		'name': sName,
		'email': sEmail,
		'FullEmail': sFullEmail
	};
};

/**
 * @param {string} sValue
 * 
 * @return {boolean}
 */
Utils.isCorrectEmail = function (sValue)
{
	return !!(sValue.match(/^[A-Z0-9\"!#\$%\^\{\}`~&'\+\-=_\.]+@[A-Z0-9\.\-]+$/i));
};

/**
 * @param {string} sAddresses
 * 
 * @return {Array}
 */
Utils.getIncorrectEmailsFromAddressString = function (sAddresses)
{
	var
		aEmails = sAddresses.replace(/"[^"]*"/g, '').replace(/;/g, ',').split(','),
		aIncorrectEmails = [],
		iIndex = 0,
		iLen = aEmails.length,
		sFullEmail = '',
		oEmailParts = null
	;

	for (; iIndex < iLen; iIndex++)
	{
		sFullEmail = Utils.trim(aEmails[iIndex]);
		if (sFullEmail.length > 0)
		{
			oEmailParts = Utils.getEmailParts(Utils.trim(aEmails[iIndex]));
			if (!Utils.isCorrectEmail(oEmailParts.email))
			{
				aIncorrectEmails.push(oEmailParts.email);
			}
		}
	}

	return aIncorrectEmails;
};

/**
 * @param {string} sRecipients
 * @param {boolean} bIncludeIncorrectEmails
 * 
 * return {Array}
 */
Utils.getArrayRecipients = function (sRecipients, bIncludeIncorrectEmails)
{
	if (!sRecipients)
	{
		return [];
	}

	var
		aRecipients = [],
		sWorkingRecipients = Utils.trim(sRecipients) + ' ',

		emailStartPos = 0,
		emailEndPos = 0,

		isInQuotes = false,
		chQuote = '"',
		isInAngleBrackets = false,
		isInBrackets = false,

		currentPos = 0,

		sWorkingRecipientsLen = sWorkingRecipients.length,

		currentChar = '',
		str = '',
		oRecipient = null,
		inList = false,
		jCount = 0,
		j = 0
	;

	while (currentPos < sWorkingRecipientsLen) {
		currentChar = sWorkingRecipients.substring(currentPos, currentPos+1);
		switch (currentChar) {
			case '\'':
			case '"':
				if (isInQuotes) {
					if (chQuote === currentChar) {
						isInQuotes = false;
					}
				}
				else {
					if (!isInAngleBrackets && !isInBrackets) {
						chQuote = currentChar;
						isInQuotes = true;
					}
				}
			break;
			case '<':
				if (!isInQuotes && !isInAngleBrackets && !isInBrackets) {
					isInAngleBrackets = true;
				}
			break;
			case '>':
				if (isInAngleBrackets) {
					isInAngleBrackets = false;
				}
			break;
			case '(':
				if (!isInQuotes && !isInAngleBrackets && !isInBrackets) {
					isInBrackets = true;
				}
			break;
			case ')':
				if (isInBrackets) {
					isInBrackets = false;
				}
			break;
			default:
				if (currentChar !== ',' && currentChar !== ';' && currentPos !== (sWorkingRecipientsLen - 1))
				{
					break;
				}
				if (!isInAngleBrackets && !isInBrackets && !isInQuotes)
				{
					emailEndPos = (currentPos !== (sWorkingRecipientsLen-1)) ? currentPos : sWorkingRecipientsLen;
					str = sWorkingRecipients.substring(emailStartPos, emailEndPos);
					
					if (Utils.trim(str).length > 0)
					{
						oRecipient = Utils.getEmailParts(str);
						inList = false;
						jCount = aRecipients.length;
						for (j = 0; j < jCount; j++)
						{
							if (aRecipients[j].email === oRecipient.email)
							{
								inList = true;
							}
						}
						if (!inList && (bIncludeIncorrectEmails || Utils.isCorrectEmail(oRecipient.email)))
						{
							aRecipients.push(oRecipient);
						}
					}
					
					emailStartPos = currentPos + 1;
				}
			break;
		}
		currentPos++;
	}
	return aRecipients;
};

/**
 * Gets link for contacts inport.
 *
 * @return {string}
 */
Utils.getImportContactsLink = function ()
{
	return '?/ImportContacts/';
};

/**
 * Gets link for contacts export.
 *
 * @param {string} $sSyncType
 * @return {string}
 */
Utils.getExportContactsLink = function ($sSyncType)
{
	return '?/Raw/Contacts' + $sSyncType + '/';
};

/**
 * Gets link for calendar export by hash.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * 
 * @return {string}
 */
Utils.getExportCalendarLinkByHash = function (iAccountId, sHash)
{
	return '?/Raw/Calendar/' + iAccountId + '/' + sHash;
};

/**
 * Gets link for download by hash.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * @param {boolean=} bIsExt = false
 * @param {string=} sTenatHash = ''
 * 
 * @return {string}
 */
Utils.getDownloadLinkByHash = function (iAccountId, sHash, bIsExt, sTenatHash)
{
	bIsExt = Utils.isUnd(bIsExt) ? false : !!bIsExt;
	sTenatHash = Utils.isUnd(sTenatHash) ? '' : sTenatHash;

	return '?/Raw/Download/' + iAccountId + '/' + sHash + '/' + (bIsExt ? '1' : '0') + ('' === sTenatHash ? '' : '/' + sTenatHash);
};

/**
 * Gets link for view by hash.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * @param {boolean=} bIsExt = false
 * @param {string=} sTenatHash = ''
 * 
 * @return {string}
 */
Utils.getViewLinkByHash = function (iAccountId, sHash, bIsExt, sTenatHash)
{
	bIsExt = Utils.isUnd(bIsExt) ? false : !!bIsExt;
	sTenatHash = Utils.isUnd(sTenatHash) ? '' : sTenatHash;
	
	return '?/Raw/View/' + iAccountId + '/' + sHash + '/' + (bIsExt ? '1' : '0') + ('' === sTenatHash ? '' : '/' + sTenatHash);
};

/**
 * Gets link for view by hash in iframe.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * @param {boolean=} bIsExt = false
 * @param {string=} sTenatHash = ''
 *
 * @return {string}
 */
Utils.getIframeLinkByHash = function (iAccountId, sHash, bIsExt, sTenatHash)
{
	bIsExt = Utils.isUnd(bIsExt) ? false : !!bIsExt;
	sTenatHash = Utils.isUnd(sTenatHash) ? '' : sTenatHash;

	return '?/Raw/Iframe/' + iAccountId + '/' + sHash + '/' + (bIsExt ? '1' : '0') + ('' === sTenatHash ? '' : '/' + sTenatHash);
};

/**
 * Gets link for view by hash in iframe.
 *
 * @param {number} iAccountId
 * @param {string} sUrl
 *
 * @return {string}
 */
Utils.getIframeWrappwer = function (iAccountId, sUrl)
{
	return '?/Raw/Iframe/' + iAccountId + '/' + window.encodeURIComponent(sUrl) + '/';
};

/**
 * Gets link for thumbnail by hash.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * @param {boolean=} bIsExt = false
 * @param {string=} sTenatHash = ''
 *
 * @return {string}
 */
Utils.getViewThumbnailLinkByHash = function (iAccountId, sHash, bIsExt, sTenatHash)
{
	bIsExt = Utils.isUnd(bIsExt) ? false : !!bIsExt;
	sTenatHash = Utils.isUnd(sTenatHash) ? '' : sTenatHash;
	
	return '?/Raw/Thumbnail/' + iAccountId + '/' + sHash + '/' + (bIsExt ? '1' : '0') + ('' === sTenatHash ? '' : '/' + sTenatHash);
};

/**
 * Gets link for download by hash.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * @param {string=} sPublicHash
 * 
 * @return {string}
 */
Utils.getFilestorageDownloadLinkByHash = function (iAccountId, sHash, sPublicHash)
{
	var sUrl = '?/Raw/FilesDownload/' + iAccountId + '/' + sHash;
	if (!Utils.isUnd(sPublicHash))
	{
		sUrl = sUrl + '/0/' + sPublicHash;
	}
	return sUrl;
};

/**
 * Gets link for download by hash.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * @param {string=} sPublicHash
 * 
 * @return {string}
 */
Utils.getFilestorageViewLinkByHash = function (iAccountId, sHash, sPublicHash)
{
	var sUrl = '?/Raw/FilesView/' + iAccountId + '/' + sHash;
	if (!Utils.isUnd(sPublicHash))
	{
		sUrl = sUrl + '/0/' + sPublicHash;
	}
	return sUrl;
};

/**
 * Gets link for thumbnail by hash.
 *
 * @param {number} iAccountId
 * @param {string} sHash
 * @param {string} sPublicHash
 *
 * @return {string}
 */
Utils.getFilestorageViewThumbnailLinkByHash = function (iAccountId, sHash, sPublicHash)
{
	var sUrl = '?/Raw/FilesThumbnail/' + iAccountId + '/' + sHash;
	if (!Utils.isUnd(sPublicHash))
	{
		sUrl = sUrl + '/0/' + sPublicHash;
	}
	return sUrl;
};

/**
 * Gets link for public by hash.
 *
 * @param {string} sHash
 * 
 * @return {string}
 */
Utils.getFilestoragePublicViewLinkByHash = function (sHash)
{
	return '?/Window/Files/0/' + sHash;
};

/**
 * Gets link for public by hash.
 *
 * @param {string} sHash
 * 
 * @return {string}
 */
Utils.getFilestoragePublicDownloadLinkByHash = function (sHash)
{
	return '?/Raw/FilesPub/0/' + sHash;
};

/**
 * @param {number} iMonth
 * @param {number} iYear
 * 
 * @return {number}
 */
Utils.daysInMonth = function (iMonth, iYear)
{
	if (0 < iMonth && 13 > iMonth && 0 < iYear)
	{
		return new Date(iYear, iMonth, 0).getDate();
	}

	return 31;
};

/** 
 * @return {string}
 */
Utils.getAppPath = function ()
{
	return window.location.protocol + '//' + window.location.host + window.location.pathname;
};

Utils.WindowOpener = {

	_iDefaultRatio: 0.8,
	_aOpenedWins: [],
	
	/**
	 * @param {{folder:Function, uid:Function}} oMessage
	 * @param {boolean=} bDrafts
	 */
	openMessage: function (oMessage, bDrafts)
	{
		if (oMessage)
		{
			var
				sFolder = oMessage.folder(),
				sUid = oMessage.uid(),
				sHash = '',
				oWin = null
			;
			
			if (bDrafts)
			{
				sHash = App.Routing.buildHashFromArray([Enums.Screens.SingleCompose, 'drafts', sFolder, sUid]);
			}
			else
			{
				sHash = App.Routing.buildHashFromArray([Enums.Screens.SingleMessageView, sFolder, 'msg' + sUid]);
			}

			oWin = this.openTab(sHash);
		}
	},

	/**
	 * @param {string} sUrl
	 * @param {string=} sWinName
	 * 
	 * @return Object
	 */
	openTab: function (sUrl, sWinName)
	{
		$.cookie('aft-cache-ctrl', '1');
		var oWin = null;

		oWin = window.open(sUrl, '_blank');
		oWin.focus();
		oWin.name = sWinName ? sWinName : (AppData.Accounts ? AppData.Accounts.currentId() : 0);

		this._aOpenedWins.push(oWin);
		
		return oWin;
	},
	
	/**
	 * @param {string} sUrl
	 * @param {string} sPopupName
	 * @param {boolean=} bMenubar = false
	 * 
	 * @return Object
	 */
	open: function (sUrl, sPopupName, bMenubar)
	{
		var
			sMenubar = (bMenubar) ? ',menubar=yes' : ',menubar=no',
			sParams = 'location=no,toolbar=no,status=no,scrollbars=yes,resizable=yes' + sMenubar,
			oWin = null
		;

		sPopupName = sPopupName.replace(/\W/g, ''); // forbidden characters in the name of the window for ie
		sParams += this._getSizeParameters();

		oWin = window.open(sUrl, sPopupName, sParams);
		oWin.focus();
		oWin.name = AppData.Accounts ? AppData.Accounts.currentId() : 0; //no Accounts in client helpdesk

		this._aOpenedWins.push(oWin);
		
		return oWin;
	},
	
	/**
	 * @returns {Array}
	 */
	getOpenedDraftUids: function ()
	{
		this._aOpenedWins = _.filter(this._aOpenedWins, function (oWin) {
			return !oWin.closed;
		});
		
		var aDraftUids = _.map(this._aOpenedWins, function (oWin) {
			return oWin.App ? oWin.App.MailCache.editedDraftUid() : '';
		});
		
		if (App.Screens.hasOpenedMinimizedPopups())
		{
			aDraftUids.push(App.MailCache.editedDraftUid());
		}
		
		return _.uniq(_.compact(aDraftUids));
	},
	
	/**
	 * @param {string} aUids
	 */
	closeComposesWithDraftUids: function (aUids)
	{
		_.each(this._aOpenedWins, function (oWin) {
			if (oWin.App && -1 !== Utils.inArray(oWin.App.MailCache.editedDraftUid(), aUids))
			{
				oWin.close();
			}
		});
		
		if (-1 !== Utils.inArray(App.MailCache.editedDraftUid(), aUids))
		{
			App.Api.closeComposePopup();
		}
	},

	closeAll: function ()
	{
		var
			iLen = this._aOpenedWins.length,
			iIndex = 0,
			oWin = null
		;

		for (; iIndex < iLen; iIndex++)
		{
			oWin = this._aOpenedWins[iIndex];
			if (!oWin.closed)
			{
				oWin.close();
			}
		}

		this._aOpenedWins = [];
	},

	/**
	 * @return string
	 */
	_getSizeParameters: function ()
	{
		var
			iScreenWidth = window.screen.width,
			iWidth = Math.ceil(iScreenWidth * this._iDefaultRatio),
			iLeft = Math.ceil((iScreenWidth - iWidth) / 2),

			iScreenHeight = window.screen.height,
			iHeight = Math.ceil(iScreenHeight * this._iDefaultRatio),
			iTop = Math.ceil((iScreenHeight - iHeight) / 2)
		;

		return ',width=' + iWidth + ',height=' + iHeight + ',top=' + iTop + ',left=' + iLeft;
	}
};

/**
 * @param {?} oObject
 * @param {string} sDelegateName
 * @param {Array=} aParameters
 */
Utils.delegateRun = function (oObject, sDelegateName, aParameters)
{
	if (oObject && oObject[sDelegateName])
	{
		oObject[sDelegateName].apply(oObject, _.isArray(aParameters) ? aParameters : []);
	}
};

/**
 * @param {string} input
 * @param {number} multiplier
 * @return {string}
 */
Utils.strRepeat = function (input, multiplier)
{
	return (new Array(multiplier + 1)).join(input);
};


Utils.deferredUpdate = function (element, state, duration, callback) {
	
	if (!element.__interval && !!state)
	{
		element.__state = true;
		callback(element, true);

		element.__interval = window.setInterval(function () {
			if (!element.__state)
			{
				callback(element, false);
				window.clearInterval(element.__interval);
				element.__interval = null;
			}
		}, duration);
	}
	else if (!state)
	{
		element.__state = false;
	}
};

Utils.draggableMessages = function ()
{
	return $('<div class="draggable draggableMessages"><div class="content"><span class="count-text"></span></div></div>').appendTo('#pSevenHidden');
};

Utils.draggableContacts = function ()
{
	return $('<div class="draggable draggableContacts"><div class="content"><span class="count-text"></span></div></div>').appendTo('#pSevenHidden');
};

Utils.removeActiveFocus = function ()
{
	if (document && document.activeElement && document.activeElement.blur)
	{
		var oA = $(document.activeElement);
		if (oA.is('input') || oA.is('textarea'))
		{
			document.activeElement.blur();
		}
	}
};

Utils.uiDropHelperAnim = function (oEvent, oUi)
{
	var
		iLeft = 0,
		iTop = 0,
		iNewLeft = 0,
		iNewTop = 0,
		iWidth = 0,
		iHeight = 0,
		helper = oUi.helper.clone().appendTo('#pSevenHidden'),
		target = $(oEvent.target).find('.animGoal'),
		position = null
	;

	target = target[0] ? $(target[0]) : $(oEvent.target);
	position = target && target[0] ? target.offset() : null;

	if (position)
	{
		iLeft = window.Math.round(position.left);
		iTop = window.Math.round(position.top);

		iWidth = target.width();
		iHeight = target.height();

		iNewLeft = iLeft;
		if (0 < iWidth)
		{
			iNewLeft += window.Math.round(iWidth / 2);
		}

		iNewTop = iTop;
		if (0 < iHeight)
		{
			iNewTop += window.Math.round(iHeight / 2);
		}

		helper.animate({
			'left': iNewLeft + 'px',
			'top': iNewTop + 'px',
			'font-size': '0px',
			'opacity': 0
		}, 800, 'easeOutQuint', function() {
			$(this).remove();
		});
	}
};

Utils.isTextFieldFocused = function ()
{
	var
		mTag = document && document.activeElement ? document.activeElement : null,
		mTagName = mTag ? mTag.tagName : null,
		mTagType = mTag && mTag.type ? mTag.type.toLowerCase() : null,
		mContentEditable = mTag ? mTag.contentEditable : null
	;
	
	return ('INPUT' === mTagName && (mTagType === 'text' || mTagType === 'password' || mTagType === 'email')) ||
		'TEXTAREA' === mTagName || 'IFRAME' === mTagName || mContentEditable === 'true';
};

Utils.removeSelection = function ()
{
	if (window.getSelection)
	{
		window.getSelection().removeAllRanges();
	}
	else if (document.selection)
	{
		document.selection.empty();
	}
};

Utils.getMonthNamesArray = function ()
{
	var
		aMonthes = Utils.i18n('DATETIME/MONTH_NAMES').split(' '),
		iLen = 12,
		iIndex = aMonthes.length
	;
	
	for (; iIndex < iLen; iIndex++)
	{
		aMonthes[iIndex] = '';
	}
	
	return aMonthes;
};

/**
 * http://docs.translatehouse.org/projects/localization-guide/en/latest/l10n/pluralforms.html?id=l10n/pluralforms
 * 
 * @param {string} sLang
 * @param {number} iNumber
 * 
 * @return {number}
 */
Utils.getPlural = function (sLang, iNumber)
{
	var iResult = 0;
	iNumber = Utils.pInt(iNumber);

	switch (sLang)
	{
		case 'Arabic':
			iResult = (iNumber === 0 ? 0 : iNumber === 1 ? 1 : iNumber === 2 ? 2 : iNumber % 100 >= 3 && iNumber % 100 <= 10 ? 3 : iNumber % 100 >= 11 ? 4 : 5);
			break;
		case 'Bulgarian':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Chinese-Simplified':
			iResult = 0;
			break;
		case 'Chinese-Traditional':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Czech':
			iResult = (iNumber === 1) ? 0 : (iNumber >= 2 && iNumber <= 4) ? 1 : 2;
			break;
		case 'Danish':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Dutch':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'English':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Estonian':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Finish':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'French':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'German':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Greek':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Hebrew':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Hungarian':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Italian':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Japanese':
			iResult = 0;
			break;
		case 'Korean':
			iResult = 0;
			break;
		case 'Latvian':
			iResult = (iNumber % 10 === 1 && iNumber % 100 !== 11 ? 0 : iNumber !== 0 ? 1 : 2);
			break;
		case 'Lithuanian':
			iResult = (iNumber % 10 === 1 && iNumber % 100 !== 11 ? 0 : iNumber % 10 >= 2 && (iNumber % 100 < 10 || iNumber % 100 >= 20) ? 1 : 2);
			break;
		case 'Norwegian':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Persian':
			iResult = 0;
			break;
		case 'Polish':
			iResult = (iNumber === 1 ? 0 : iNumber % 10 >= 2 && iNumber % 10 <= 4 && (iNumber % 100 < 10 || iNumber % 100 >= 20) ? 1 : 2);
			break;
		case 'Portuguese-Portuguese':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Portuguese-Brazil':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Romanian':
			iResult = (iNumber === 1 ? 0 : (iNumber === 0 || (iNumber % 100 > 0 && iNumber % 100 < 20)) ? 1 : 2);
			break;
		case 'Russian':
			iResult = (iNumber % 10 === 1 && iNumber % 100 !== 11 ? 0 : iNumber % 10 >= 2 && iNumber % 10 <= 4 && (iNumber % 100 < 10 || iNumber % 100 >= 20) ? 1 : 2);
			break;
		case 'Serbian':
			iResult = (iNumber % 10 === 1 && iNumber % 100 !== 11 ? 0 : iNumber % 10 >= 2 && iNumber % 10 <= 4 && (iNumber % 100 < 10 || iNumber % 100 >= 20) ? 1 : 2);
			break;
		case 'Spanish':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Swedish':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Thai':
			iResult = 0;
			break;
		case 'Turkish':
			iResult = (iNumber === 1 ? 0 : 1);
			break;
		case 'Ukrainian':
			iResult = (iNumber % 10 === 1 && iNumber % 100 !== 11 ? 0 : iNumber % 10 >= 2 && iNumber % 10 <= 4 && (iNumber % 100 < 10 || iNumber % 100 >= 20) ? 1 : 2);
			break;
		default:
			iResult = 0;
			break;
	}

	return iResult;
};

/**
 * @param {string} sFile
 * 
 * @return {string}
 */
Utils.getFileExtension = function (sFile)
{
	var 
		sResult = '',
		iIndex = sFile.lastIndexOf('.')
	;
	
	if (iIndex > -1)
	{
		sResult = sFile.substr(iIndex + 1);
	}

	return sResult;
};

/**
 * @param {string} sFile
 * 
 * @return {string}
 */
Utils.getFileNameWithoutExtension = function (sFile)
{
	var 
		sResult = sFile,
		iIndex = sFile.lastIndexOf('.')
	;
	if (iIndex > -1)
	{
		sResult = sFile.substr(0, iIndex);	
	}
	return sResult;
};

/**
 * @param {Object} oElement
 * @param {Object} oItem
 */
Utils.defaultOptionsAfterRender = function (oElement, oItem)
{
	if (oItem)
	{
		if (!Utils.isUnd(oItem.disable))
		{
			ko.applyBindingsToNode(oElement, {
				'disable': oItem.disable
			}, oItem);
		}
	}
};

/**
 * @param {string} sDateFormat
 * 
 * @return string
 */
Utils.getDateFormatForMoment = function (sDateFormat)
{
	var sMomentDateFormat = 'MM/DD/YYYY';
	
	switch (sDateFormat)
	{
		case 'MM/DD/YYYY':
			sMomentDateFormat = 'MM/DD/YYYY';
			break;
		case 'DD/MM/YYYY':
			sMomentDateFormat = 'DD/MM/YYYY';
			break;
		case 'DD Month YYYY':
			sMomentDateFormat = 'DD MMMM YYYY';
			break;
	}
	
	return sMomentDateFormat;
};

/**
 * @param {string} sDateFormat
 * 
 * @return string
 */
Utils.getDateFormatForDatePicker = function (sDateFormat)
{
	var sDatePickerDateFormat = 'mm/dd/yy';
	
	switch (sDateFormat)
	{
		case 'MM/DD/YYYY':
			sDatePickerDateFormat = 'mm/dd/yy';
			break;
		case 'DD/MM/YYYY':
			sDatePickerDateFormat = 'dd/mm/yy';
			break;
		case 'DD Month YYYY':
			sDatePickerDateFormat = 'dd MM yy';
			break;
	}
	
	return sDatePickerDateFormat;
};

/**
 * @return Array
 */
Utils.getDateFormatsForSelector = function ()
{
	return _.map(AppData.App.DateFormats, function (sDateFormat) {
		switch (sDateFormat)
		{
			case 'MM/DD/YYYY':
				return {name: Utils.i18n('DATETIME/DATEFORMAT_MMDDYYYY'), value: sDateFormat};
			case 'DD/MM/YYYY':
				return {name: Utils.i18n('DATETIME/DATEFORMAT_DDMMYYYY'), value: sDateFormat};
			case 'DD Month YYYY':
				return {name: Utils.i18n('DATETIME/DATEFORMAT_DDMONTHYYYY'), value: sDateFormat};
			default:
				return {name: sDateFormat, value: sDateFormat};
		}
	});
};

/**
 * @param {string} sSubject
 * 
 * @return {string}
 */
Utils.getTitleForEvent = function (sSubject)
{
	var
		sTitle = sSubject ? Utils.trim(sSubject.replace(/[\n\r]/, ' ')) : '',
		iFirstSpacePos = sTitle.indexOf(' ', 180)
	;

	if (iFirstSpacePos >= 0)
	{
		sTitle = sTitle.substring(0, iFirstSpacePos) + '...';
	}
	
	if (sTitle.length > 200)
	{
		sTitle = sTitle.substring(0, 200) + '...';
	}
	
	return sTitle;
};

Utils.desktopNotify = (function ()
{
	var
		aNotifications = [],
		iTimeoutID = 0
	;

	return function (oData)
	{
		if (AppData.User.DesktopNotifications && window.Notification && !App.focused())
		{
			if (oData && oData.action === 'show' && window.Notification.permission !== Enums.notificationPermission.Denied)
			{
				// oData - action, body, dir, lang, tag, icon, callback, timeout
				var
					oOptions = { //https://developer.mozilla.org/en-US/docs/Web/API/Notification
						body: oData.body || '', //A string representing an extra content to display within the notification
						dir: oData.dir || "auto", //The direction of the notification; it can be auto, ltr, or rtl
						lang: oData.lang || '', //Specify the lang used within the notification. This string must be a valid BCP 47 language tag
						tag: oData.tag || Math.floor(Math.random() * (1000 - 100) + 100), //An ID for a given notification that allows to retrieve, replace or remove it if necessary
						icon: oData.icon || false //The URL of an image to be used as an icon by the notification
					},
					oNotification,
					fShowNotification = function()
					{
						oNotification = new window.Notification(oData.title, oOptions); //Firefox and Safari close the notifications automatically after a few moments, e.g. 4  seconds.
						oNotification.onclick = function (oEv)
						{
							if(oData.callback)
							{
								oData.callback();
							}
							oNotification.close();
						};
						oNotification.onshow = function (oEv) {};
						oNotification.onclose = function (oEv) {};
						oNotification.onerror = function (oEv) {};

						if(oData.timeout) {
							iTimeoutID = setTimeout(function() {oNotification.close();}, oData.timeout);
						}

						aNotifications.push(oNotification);
					}
				;

				if (window.Notification.permission === 'granted')
				{
					fShowNotification();
				}
				else if (window.Notification.permission === 'default')
				{
					window.Notification.requestPermission(function (sPermission) {
						if(sPermission === "granted")
						{
							fShowNotification();
						}
					});
				}

			}
			else if (oData && oData.action === 'hide')
			{
				_.each(aNotifications, function (oNotifi, ikey) {
					if (oData.tag === oNotifi.tag) {
						oNotifi.close();
						aNotifications.splice(ikey, 1);
					}
				});
			}
			else if (oData && oData.action === 'hideAll')
			{
				_.each(aNotifications,function (oNotifi) {
					oNotifi.close();
				});
				aNotifications.length = 0;
			}
		}
	};
}());

/**
 * @return {boolean}
 */
Utils.isRTL = function ()
{
	return $html.hasClass('rtl');
};

/**
 * @param {string} sName
 * @return {boolean}
 */
Utils.validateFileOrFolderName = function (sName)
{
	sName = Utils.trim(sName);
	return '' !== sName && !/["\/\\*?<>|:]/.test(sName);
};

/**
 * @param {string} sColor
 * @param {number} iPercent
 * 
 * @return {string}
 */
Utils.shadeColor = function (sColor, iPercent) 
{
	var
		usePound = false,
		num = 0,
		r = 0,
		b = 0,
		g = 0
	;
	
	if (sColor[0] === "#") 
	{
		sColor = sColor.slice(1);
		usePound = true;
	}
	num = window.parseInt(sColor, 16);
	r = (num >> 16) + iPercent;
	if (r > 255) 
	{
		r = 255;
	} 
	else if (r < 0) 
	{
		r = 0;
	}
	b = ((num >> 8) & 0x00FF) + iPercent;
	if (b > 255) 
	{
		b = 255;
	} 
	else if (b < 0) 
	{
		b = 0;
	}
	g = (num & 0x0000FF) + iPercent;
	if (g > 255) 
	{
		g = 255;
	} 
	else if (g < 0) 
	{
		g = 0;
	}
	return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);
};

/**
 * @param {Object} ChildClass
 * @param {Object} ParentClass
 */
Utils.extend = function (ChildClass, ParentClass)
{
	/**
	 * @constructor
	 */
	var TmpClass = function(){};
	TmpClass.prototype = ParentClass.prototype;
	ChildClass.prototype = new TmpClass();
	ChildClass.prototype.constructor = ChildClass;
};

Utils.thumbQueue = (function () {

	var
		oImages = {},
		oImagesIncrements = {},
		iNumberOfImages = 2
	;

	return function (sSessionUid, sImageSrc, fImageSrcObserver)
	{
		if(sImageSrc && fImageSrcObserver)
		{
			if(!(sSessionUid in oImagesIncrements) || oImagesIncrements[sSessionUid] > 0) //load first images
			{
				if(!(sSessionUid in oImagesIncrements)) //on first image
				{
					oImagesIncrements[sSessionUid] = iNumberOfImages;
					oImages[sSessionUid] = [];
				}
				oImagesIncrements[sSessionUid]--;

				fImageSrcObserver(sImageSrc); //load image
			}
			else //create queue
			{
				oImages[sSessionUid].push({
					imageSrc: sImageSrc,
					imageSrcObserver: fImageSrcObserver,
					messageUid: sSessionUid
				});
			}
		}
		else //load images from queue (fires load event)
		{
			if(oImages[sSessionUid] && oImages[sSessionUid].length)
			{
				oImages[sSessionUid][0].imageSrcObserver(oImages[sSessionUid][0].imageSrc);
				oImages[sSessionUid].shift();
			}
		}
	};
}());

Utils.checkConnection = (function () {

	var
		iTimer = -1,
		iLastWakeTime = new Date().getTime(),
		iCurrentTime = 0,
		bAwoke = false
	;

	setInterval(function() { //fix for sleep mode
		iCurrentTime = new Date().getTime();
		bAwoke = iCurrentTime > (iLastWakeTime + 5000 + 1000);
		iLastWakeTime = iCurrentTime;
		if (bAwoke)
		{
			App.Api.hideError(true);
		}
	}, 5000);

	return function (sAction, sStatus)
	{
		clearTimeout(iTimer);
		if (sStatus !== 'error')
		{
			App.InternetConnectionError = false;
			App.Api.hideError(true);
		}
		else
		{
			if (sAction === 'SystemPing')
			{
				App.InternetConnectionError = true;
				App.Api.showError(Utils.i18n('WARNING/NO_INTERNET_CONNECTION'), false, true, true);
				iTimer = setTimeout(function () {
					App.Ajax.send({'Action': 'SystemPing'});
				}, 60000);
			}
			else
			{
				App.Ajax.send({'Action': 'SystemPing'});
			}
		}
	};
}());

Utils.loadScript = function (sUrl, fCallback, aParams, sFuncName)
{
	var script = document.createElement('script');
	if (!Utils.isUnd(sFuncName) && fCallback)
	{
		window[sFuncName] = fCallback;
	}
	if (Utils.isUnd(aParams))
	{
		aParams = {};
	}
	
	_.each(aParams, function(value, key){ 
		script.setAttribute(key, value);
	});
	
	script.type = 'text/javascript';
	script.src = sUrl;
	document.body.appendChild(script);
};

Utils.registerMailto = function (bRegisterOnce)
{
	if (window.navigator && Utils.isFunc(window.navigator.registerProtocolHandler) && (!bRegisterOnce || App.Storage.getData('MailtoAsked') !== 1))
	{
		window.navigator.registerProtocolHandler(
			'mailto',
			Utils.getAppPath() + '#' + Enums.Screens.Compose + '/to/%s',
			AppData.App.SiteName !== '' ? AppData.App.SiteName : 'WebMail'
		);

		App.Storage.setData('MailtoAsked', 1);
	}
};

Utils.CustomTooltip = {
	_$Region: null,
	_$ArrowTop: null,
	_$Text: null,
	_$ArrowBottom: null,
	_iArrowBorderLeft: 0,
	_iArrowMarginLeft: 0,
	_iLeftShift: 0,
	_bInitialized: false,
	_bShown: false,
	
	init: function ()
	{
		if (!this._bInitialized)
		{
			this._$Region = $('<span class="custom_tooltip"></span>').appendTo('body').hide();
			this._$ArrowTop = $('<span class="custom_tooltip_arrow top"></span>').appendTo(this._$Region);
			this._$Text = $('<span class="custom_tooltip_text"></span>').appendTo(this._$Region);
			this._$ArrowBottom = $('<span class="custom_tooltip_arrow bottom"></span>').appendTo(this._$Region);
			
			this._iArrowMarginLeft = Utils.pInt(this._$ArrowTop.css('margin-left'));
			this._iArrowBorderLeft = Utils.pInt(this._$ArrowTop.css('border-left-width'));
			this._iLeftShift = Utils.pInt(this._$Region.css('margin-left')) + this._iArrowMarginLeft + this._iArrowBorderLeft;
			
			this._bInitialized = true;
		}
		
		this._$ArrowTop.show();
		this._$ArrowBottom.hide();
		this._$ArrowTop.css({
			'margin-left': this._iArrowMarginLeft + 'px'
		});
		this._$ArrowBottom.css({
			'margin-left': this._iArrowMarginLeft + 'px'
		});
	},
	
	show: function (sText, $ItemToAlign)
	{
		this.init();
		
		var
			oItemOffset = $ItemToAlign.offset(),
			iItemWidth = $ItemToAlign.width(),
			iItemHalfWidth = (iItemWidth < 70) ? iItemWidth/2 : iItemWidth/4,
			iItemPaddingLeft = Utils.pInt($ItemToAlign.css('padding-left')),
			jqBody = $('body')
		;
		
		this._$Text.html(sText);
		this._bShown = true;
		this._$Region.fadeIn(260, _.bind(function () {
			if (!this._bShown)
			{
				this._$Region.hide();
			}
		}, this)).css({
			'top': oItemOffset.top + $ItemToAlign.outerHeight() + 1,
			'left': oItemOffset.left + iItemPaddingLeft + iItemHalfWidth - this._iLeftShift,
			'right': 'auto'
		});
		
		if (jqBody.outerHeight() < this._$Region.outerHeight() + this._$Region.offset().top)
		{
			this._$ArrowTop.hide();
			this._$ArrowBottom.show();
			this._$Region.css({
				'top': oItemOffset.top - this._$Region.outerHeight()
			});
		}

		setTimeout(function () {
			if (jqBody.width() < (this._$Region.outerWidth(true) + this._$Region.offset().left))
			{
				this._$Region.css({
					'left': 'auto',
					'right': 0
				});
				this._$ArrowTop.css({
					'margin-left': (iItemHalfWidth + oItemOffset.left - this._$Region.offset().left - this._iArrowBorderLeft) + 'px'
				});
				this._$ArrowBottom.css({
					'margin-left': (iItemHalfWidth + oItemOffset.left - this._$Region.offset().left - this._iArrowBorderLeft + Utils.pInt(this._$Region.css('margin-right'))) + 'px'
				});
			}
		}.bind(this), 1);
	},
	
	hide: function ()
	{
		if (this._bInitialized)
		{
			this._bShown = false;
			this._$Region.hide();
		}
	}
};

/**
 * @param {string} sParamName
 * @returns {string|null}
 */
Utils.getRequestParam = function (sParamName)
{
	var 
		aParams = [],   
		aKeyValues = [],
		aGetRequestParams = [],
		sGetRequest = location.search,
		sResult = null
	;

	if(sGetRequest !== '')
	{
		aParams = (sGetRequest.substr(1)).split('&');   
		for(var i=0; i < aParams.length; i++) 
		{
			aKeyValues = aParams[i].split('=');       
			aGetRequestParams[aKeyValues[0]] = aKeyValues[1];       
		}
	}
	if (!Utils.isUnd(aGetRequestParams[sParamName]))
	{
		sResult = aGetRequestParams[sParamName];
	}

	return sResult;
};	

/**
 * @param {string} sHtml
 * @returns {Boolean}
 */
Utils.htmlStartsWithBlockquote = function (sHtml)
{
	var
		aParts = sHtml.split('<blockquote'),
		sBegin = aParts.length > 0 ? aParts[0] : '',
		sBeginWithoutTags = Utils.trim(sBegin.replace(/<[^>]*>/g, ''))
	;
	
	return sBeginWithoutTags === '';
};

ko.bindingHandlers.command = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		var
			jqElement = $(oElement),
			oCommand = fValueAccessor()
		;

		if (!oCommand || !oCommand.enabled || !oCommand.canExecute)
		{
			throw new Error('You are not using command function');
		}

		jqElement.addClass('command');
		ko.bindingHandlers[jqElement.is('form') ? 'submit' : 'click'].init.apply(oViewModel, arguments);
	},

	'update': function (oElement, fValueAccessor) {

		var
			bResult = true,
			jqElement = $(oElement),
			oCommand = fValueAccessor()
		;

		bResult = oCommand.enabled();
		jqElement.toggleClass('command-not-enabled', !bResult);

		if (bResult)
		{
			bResult = oCommand.canExecute();
			jqElement.toggleClass('unavailable', !bResult);
		}

		jqElement.toggleClass('command-disabled disable disabled', !bResult);
		jqElement.toggleClass('command-disabled', !bResult);

//		if (jqElement.is('input') || jqElement.is('button'))
//		{
//			jqElement.prop('disabled', !bResult);
//		}
	}
};

ko.bindingHandlers.simpleTemplate = {
	'init': function (oElement, fValueAccessor) {
		var oEl = $(oElement);
		
		if (oEl.length > 0 && oEl.data('replaced') !== 'replaced')
		{
			oEl.html(oEl.html().replace(/&lt;script(.*?)&gt;/i, '<script$1>').replace(/&lt;\/script(.*?)&gt;/i, '</script>'));
			oEl.data('replaced', 'replaced');
		}
	}
};

ko.bindingHandlers.findFocused = {
	'init': function (oElement) {

		var
			$oEl = $(oElement),
			$oInp = null
		;

		$oInp = $oEl.find('.catch-focus');
		if ($oInp && 1 === $oInp.length && $oInp[0])
		{
			$oInp.on('blur', function () {
				$oEl.removeClass('focused');
			}).on('focus', function () {
				$oEl.addClass('focused');
			});
		}
	}
};

ko.bindingHandlers.findFilled = {
	'init': function (oElement) {

		var
			$oEl = $(oElement),
			$oInp = null,
			fFunc = null
		;

		$oInp = $oEl.find('.catch-filled');
		if ($oInp && 1 === $oInp.length && $oInp[0])
		{
			fFunc = function () {
				$oEl.toggleClass('filled', '' !== $oInp.val());
			};

			fFunc();
			_.delay(fFunc, 200);
			$oInp.on('change', fFunc);
		}
	}
};

ko.bindingHandlers.alert = {
	'init': function (oElement, fValueAccessor) {
		window.alert(ko.utils.unwrapObservable(fValueAccessor()));
	},
	'update': function (oElement, fValueAccessor) {
		window.alert(ko.utils.unwrapObservable(fValueAccessor()));
	}
};

ko.bindingHandlers.onEnter = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'keyup': function (oData, oEvent) {
					if (oEvent && 13 === window.parseInt(oEvent.keyCode, 10))
					{
						$(oElement).trigger('change');
						fValueAccessor().call(this, oData);
					}
				}
			};
		}, fAllBindingsAccessor, oViewModel);
	}
};

ko.bindingHandlers.onCtrlEnter = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'keydown': function (oData, oEvent) {
					if (oEvent && 13 === window.parseInt(oEvent.keyCode, 10) && oEvent.ctrlKey)
					{
						$(oElement).trigger('change');
						fValueAccessor().call(this, oData);

						return false;
					}

					return true;
				}
			};
		}, fAllBindingsAccessor, oViewModel);
	}
};

ko.bindingHandlers.onEsc = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'keyup': function (oData, oEvent) {
					if (oEvent && 27 === window.parseInt(oEvent.keyCode, 10))
					{
						$(oElement).trigger('change');
						fValueAccessor().call(this, oData);
					}
				}
			};
		}, fAllBindingsAccessor, oViewModel);
	}
};

ko.bindingHandlers.onFocusSelect = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'focus': function () {
					oElement.select();
				}
			};
		}, fAllBindingsAccessor, oViewModel);
	}
};

ko.bindingHandlers.onEnterChange = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'keyup': function (oData, oEvent) {
					if (oEvent && 13 === window.parseInt(oEvent.keyCode, 10))
					{
						$(oElement).trigger('change');
					}
				}
			};
		}, fAllBindingsAccessor, oViewModel);
	}
};

ko.bindingHandlers.fadeIn = {
	'update': function (oElement, fValueAccessor) {
		if (ko.utils.unwrapObservable(fValueAccessor()))
		{
			$(oElement).hide().fadeIn('fast');
		}
	}
};

ko.bindingHandlers.fadeOut = {
	'update': function (oElement, fValueAccessor) {
		if (ko.utils.unwrapObservable(fValueAccessor()))
		{
			$(oElement).fadeOut();
		}
	}
};

ko.bindingHandlers.csstext = {
	'init': function (oElement, fValueAccessor) {
		if (oElement && oElement.styleSheet && !Utils.isUnd(oElement.styleSheet.cssText))
		{
			oElement.styleSheet.cssText = ko.utils.unwrapObservable(fValueAccessor());
		}
		else
		{
			$(oElement).text(ko.utils.unwrapObservable(fValueAccessor()));
		}
	},
	'update': function (oElement, fValueAccessor) {
		if (oElement && oElement.styleSheet && !Utils.isUnd(oElement.styleSheet.cssText))
		{
			oElement.styleSheet.cssText = ko.utils.unwrapObservable(fValueAccessor());
		}
		else
		{
			$(oElement).text(ko.utils.unwrapObservable(fValueAccessor()));
		}
	}
};

ko.bindingHandlers.i18n = {
	'init': function (oElement, fValueAccessor) {

		var
			sKey = $(oElement).data('i18n'),
			sValue = sKey ? Utils.i18n(sKey) : sKey
		;

		if ('' !== sValue)
		{
			switch (fValueAccessor()) {
			case 'value':
				$(oElement).val(sValue);
				break;
			case 'text':
				$(oElement).text(sValue);
				break;
			case 'html':
				$(oElement).html(sValue);
				break;
			case 'title':
				$(oElement).attr('title', sValue);
				break;
			case 'placeholder':
				$(oElement).attr({'placeholder': sValue});
				break;
			}
		}
	}
};

ko.bindingHandlers.link = {
	'init': function (oElement, fValueAccessor) {
		$(oElement).attr('href', ko.utils.unwrapObservable(fValueAccessor()));
	}
};

ko.bindingHandlers.title = {
	'init': function (oElement, fValueAccessor) {
		$(oElement).attr('title', ko.utils.unwrapObservable(fValueAccessor()));
	},
	'update': function (oElement, fValueAccessor) {
		$(oElement).attr('title', ko.utils.unwrapObservable(fValueAccessor()));
	}
};

ko.bindingHandlers.initDom = {
	'init': function (oElement, fValueAccessor) {
		if (fValueAccessor()) {
			if (_.isArray(fValueAccessor()))
			{
				var
					aList = fValueAccessor(),
					iIndex = aList.length - 1
				;

				for (; 0 <= iIndex; iIndex--)
				{
					aList[iIndex]($(oElement));
				}
			}
			else
			{
				fValueAccessor()($(oElement));
			}
		}
	}
};

ko.bindingHandlers.customScrollbar = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		if (bMobileDevice)
		{
			return;
		}

		var
			jqElement = $(oElement),
			oCommand = fValueAccessor()
		;

		/*_.delay(_.bind(function () {
			var jqCustomScrollbar = jqElement.find('.customscroll-scrollbar-vertical');

			jqCustomScrollbar.on('click', function (oEv) {
				oEv.stopPropagation();
			});
		}, this), 1000);*/



		oCommand = /** @type {{scrollToTopTrigger:{subscribe:Function},scrollToBottomTrigger:{subscribe:Function},scrollTo:{subscribe:Function},reset:Function}}*/ oCommand;
		
		jqElement.addClass('scroll-wrap').customscroll(oCommand);
		
		if (!Utils.isUnd(oCommand.reset)) {
			oElement._customscroll_reset = _.throttle(function () {
				jqElement.data('customscroll').reset();
			}, 100);
		}
		
		if (!Utils.isUnd(oCommand.scrollToTopTrigger) && Utils.isFunc(oCommand.scrollToTopTrigger.subscribe)) {
			oCommand.scrollToTopTrigger.subscribe(function () {
				if (jqElement.data('customscroll')) {
					jqElement.data('customscroll')['scrollToTop']();
				}
			});
		}
		
		if (!Utils.isUnd(oCommand.scrollToBottomTrigger) && Utils.isFunc(oCommand.scrollToBottomTrigger.subscribe)) {
			oCommand.scrollToBottomTrigger.subscribe(function () {
				if (jqElement.data('customscroll')) {
					jqElement.data('customscroll')['scrollToBottom']();
				}
			});
		}
		
		if (!Utils.isUnd(oCommand.scrollTo) && Utils.isFunc(oCommand.scrollTo.subscribe)) {
			oCommand.scrollTo.subscribe(function () {
				if (jqElement.data('customscroll')) {
					jqElement.data('customscroll')['scrollTo'](oCommand.scrollTo());
				}
			});
		}
	},
	
	'update': bMobileApp ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {
		if (bMobileDevice)
		{
			return;
		}
		
		if (oElement._customscroll_reset) {
			oElement._customscroll_reset();
		}
		if (!Utils.isUnd(fValueAccessor().top)) {
			$(oElement).data('customscroll')['vertical'].set(fValueAccessor().top);
		}
	}
};

/*jslint vars: true*/
ko.bindingHandlers.customOptions = {
	'init': function () {
		return {
			'controlsDescendantBindings': true
		};
	},

	'update': function (element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
		var i = 0, j = 0;
		var previousSelectedValues = ko.utils.arrayMap(ko.utils.arrayFilter(element.childNodes, function (node) {
			return node.tagName && node.tagName === 'OPTION' && node.selected;
		}), function (node) {
			return ko.selectExtensions.readValue(node) || node.innerText || node.textContent;
		});
		var previousScrollTop = element.scrollTop;
		var value = ko.utils.unwrapObservable(valueAccessor());

		// Remove all existing <option>s.
		while (element.length > 0)
		{
			ko.cleanNode(element.options[0]);
			element.remove(0);
		}

		if (value)
		{
			if (typeof value.length !== 'number')
			{
				value = [value];
			}

			var optionsBind = allBindingsAccessor()['optionsBind'];
			for (i = 0, j = value.length; i < j; i++)
			{
				var option = document.createElement('OPTION');
				var optionValue = ko.utils.unwrapObservable(value[i]);
				ko.selectExtensions.writeValue(option, optionValue);
				option.appendChild(document.createTextNode(optionValue));
				element.appendChild(option);
				if (optionsBind)
				{
					option.setAttribute('data-bind', optionsBind);
					ko.applyBindings(bindingContext['createChildContext'](optionValue), option);
				}
			}

			var newOptions = element.getElementsByTagName('OPTION');
			var countSelectionsRetained = 0;
			var isIe = navigator.userAgent.indexOf("MSIE 6") >= 0;
			for (i = 0, j = newOptions.length; i < j; i++)
			{
				if (ko.utils.arrayIndexOf(previousSelectedValues, ko.selectExtensions.readValue(newOptions[i])) >= 0)
				{
					if (isIe) {
						newOptions[i].setAttribute("selected", true);
					} else {
						newOptions[i].selected = true;
					}

					countSelectionsRetained++;
				}
			}

			element.scrollTop = previousScrollTop;

			if (countSelectionsRetained < previousSelectedValues.length)
			{
				ko.utils.triggerEvent(element, 'change');
			}
		}
	}
};
/*jslint vars: false*/

ko.bindingHandlers.splitter = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor) {
		setTimeout(function() {
			$(oElement).splitter(fValueAccessor());
		}, 1);
	}
};

ko.bindingHandlers.dropdown = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		var
			jqElement = $(oElement),
			oCommand = _.defaults(
				fValueAccessor(), {
					'disabled': 'disabled',
					'expand': 'expand',
					'control': true,
					'container': '.dropdown_content',
					'scrollToTopContainer': '.scroll-inner',
					'passClick': true,
					'trueValue': true
				}
			),
			element = oCommand['control'] ? jqElement.find('.control') : jqElement,
			jqDrop = jqElement.find('.dropdown'),
			jqDropHelper = jqElement.find('.dropdown_helper'),
			jqDropArrow = jqElement.find('.dropdown_arrow'),
			jqDropBottomArrow = jqElement.find('.dropdown_arrow.bottom'),
			oDocument = $(document),
			bScrollBar = false,
			oOffset,
			iLeft,
			iFitToScreenOffset,
			fCallback = function () {
				if (!Utils.isUnd(oCommand['callback'])) {
					oCommand['callback'].call(
						oViewModel,
						jqElement.hasClass(oCommand['expand']) ? oCommand['trueValue'] : false,
						jqElement
					);
				}
			},
			fStop = function (event) {
				event.stopPropagation();
			},
			fScrollToTop = function () {
				if (oCommand['scrollToTopContainer'])
				{
					jqElement.find(oCommand['scrollToTopContainer']).scrollTop(0);
				}
			},
			fToggleExpand = function (bValue) {
				if (Utils.isUnd(bValue))
				{
					bValue = !jqElement.hasClass(oCommand['expand']);
				}

				if (!bValue && jqElement.hasClass(oCommand['expand']))
				{
					fScrollToTop();
				}

				jqElement.toggleClass(oCommand['expand'], bValue);
				
				if (jqDropBottomArrow.length > 0 && jqElement.hasClass(oCommand['expand']))
				{
					jqDrop.css({
						'top': (jqElement.position().top - jqDropHelper.height()) + 'px',
						'left': jqElement.position().left + 'px',
						'width': 'auto'
					});
				}
			},
			fFitToScreen = function (iOffsetLeft) {
				oOffset = jqDropHelper.offset();
				if (!Utils.isUnd(oOffset))
				{
					iLeft = oOffset.left + 10;
					iFitToScreenOffset = $(window).width() - (iLeft + jqDropHelper.outerWidth(true));

					if (iFitToScreenOffset > 0) {
						iFitToScreenOffset = 0;
					}

					jqDropHelper.css('left', iOffsetLeft || iFitToScreenOffset + 'px');
					jqDropArrow.css('left', iOffsetLeft || Math.abs(iFitToScreenOffset ? iFitToScreenOffset + parseInt(jqDropArrow.css('margin-left')) : 0) + 'px');
				}
			}
		;

		if (!oCommand['passClick']) {
			jqElement.find(oCommand['container']).click(fStop);
			element.click(fStop);
		}

		fToggleExpand(false);
		
		if (oCommand['close'] && oCommand['close']['subscribe']) {
			oCommand['close'].subscribe(function (bValue) {
				if (!bValue) {
					oDocument.unbind('click.dropdown');
					fToggleExpand(false);
				}

				fCallback();
			});
		}

		jqElement.on('mousedown', function(oEv, oEl) {
			bScrollBar = ($(oEv.target).hasClass('customscroll-scrollbar') || $(oEv.target.parentElement).hasClass('customscroll-scrollbar'));
		});

		//TODO fix data-bind click
		element.click(function(oEv){

			if (!jqElement.hasClass(oCommand['disabled']) && !bScrollBar) {

				fToggleExpand();

				_.defer(function(){
					fCallback();
				});

				if (jqElement.hasClass(oCommand['expand'])) {

					if (oCommand['close'] && oCommand['close']['subscribe']) {
						oCommand['close'](true);
					}

					_.defer(function(){
						oDocument.on('click.dropdown', function (ev) {
							if((oCommand['passClick'] || ev.button !== Enums.MouseKey.Right) && !bScrollBar)
							{
								oDocument.unbind('click.dropdown');
								if (oCommand['close'] && oCommand['close']['subscribe'])
								{
									oCommand['close'](false);
								}

								fToggleExpand(false);

								fCallback();
								fFitToScreen(0);
							}
							bScrollBar = false;
						});
					});

					fFitToScreen();
				}
			}
		});
	}
};

ko.bindingHandlers.customSelect = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		var
			jqElement = $(oElement),
			oCommand = _.defaults(
				fValueAccessor(), {
					'disabled': 'disabled',
					'selected': 'selected',
					'expand': 'expand',
					'control': true,
					'input': false,
					'expandState': function () {}
				}
			),
			aOptions = [],
			oControl = oCommand['control'] ? jqElement.find('.control') : jqElement,
			oContainer = jqElement.find('.dropdown_content'),
			oText = jqElement.find('.link'),

			updateField = function (value) {
				_.each(aOptions, function (item) {
					item.removeClass(oCommand['selected']);
				});
				var item = _.find(oCommand['options'], function (item) {
					return item[oCommand['optionsValue']] === value;
				});
				if (Utils.isUnd(item)) {
					item = oCommand['options'][0];
				}
				else
				{
					aOptions[_.indexOf(oCommand['options'], item)].addClass(oCommand['selected']);
					oText.text($.trim(item[oCommand['optionsText']]));
				}

//				aOptions[_.indexOf(oCommand['options'], item)].addClass(oCommand['selected']);
//				oText.text($.trim(item[oCommand['optionsText']]));

				return item[oCommand['optionsValue']];
			},
			updateList = function (aList) {
				oContainer.empty();
				aOptions = [];

				_.each(aList ? aList : oCommand['options'], function (item) {
					var
						oOption = $('<span class="item"></span>')
							.text(item[oCommand['optionsText']])
							.data('value', item[oCommand['optionsValue']]),
						isDisabled = item['isDisabled']
						;

					if (isDisabled)
					{
						oOption.data('isDisabled', isDisabled).addClass('disabled');
					}
					else
					{
						oOption.data('isDisabled', isDisabled).removeClass('disabled');
					}

					aOptions.push(oOption);
					oContainer.append(oOption);
				}, this);
			}
		;

		updateList();

		oContainer.on('click', '.item', function () {
			var jqItem = $(this);

			if(!jqItem.data('isDisabled'))
			{
				oCommand.value(jqItem.data('value'));
			}
		});

		if (!oCommand.input && oCommand['value'] && oCommand['value'].subscribe)
		{
			oCommand['value'].subscribe(function () {
				var mValue = updateField(oCommand['value']());
				if (oCommand['value']() !== mValue)
				{
					oCommand['value'](mValue);
				}
			}, oViewModel);

			oCommand['value'].valueHasMutated();
		}

		if (oCommand.input && oCommand['value'] && oCommand['value'].subscribe)
		{
			oCommand['value'].subscribe(function () {
				updateField(oCommand['value']());
			}, oViewModel);

			oCommand['value'].valueHasMutated();
		}
		
		if (oCommand.input && oCommand['value'] && oCommand['value'].subscribe)
		{
			oCommand['value'].subscribe(function () {
				updateField(oCommand['value']());
			}, oViewModel);

			oCommand['value'].valueHasMutated();
		}

		if(oCommand.alarmOptions)
		{
			oCommand.alarmOptions.subscribe(function () {
				updateList();
			}, oViewModel);
		}
		if(oCommand.timeOptions)
		{
			oCommand.timeOptions.subscribe(function (aList) {
				updateList(aList);
			}, oViewModel);
		}

		//TODO fix data-bind click
		jqElement.removeClass(oCommand['expand']);
		oControl.click(function(ev){
			if (!jqElement.hasClass(oCommand['disabled'])) {
				jqElement.toggleClass(oCommand['expand']);
				oCommand['expandState'](jqElement.hasClass(oCommand['expand']));

				if (jqElement.hasClass(oCommand['expand'])) {
					var	jqContent = jqElement.find('.dropdown_content'),
						jqSelected = jqContent.find('.selected');

					if (jqSelected.position()) {
						jqContent.scrollTop(0);// need for proper calculation position().top
						jqContent.scrollTop(jqSelected.position().top - 100);// 100 - hardcoded indent to the element in pixels
					}

					_.defer(function(){
						$(document).one('click', function () {
							jqElement.removeClass(oCommand['expand']);
							oCommand['expandState'](false);
						});
					});
				}
				/*else
				{
					jqElement.addClass(oCommand['expand']);
				}*/
			}
		});
	}
};

ko.bindingHandlers.moveToFolderFilter = {

	'init': function (oElement, fValueAccessor, allBindingsAccessor, viewModel, bindingContext) {
		var
			jqElement = $(oElement),
			oCommand = fValueAccessor(),
			jqContainer = $(oElement).find(oCommand['container']),
			aOptions = _.isArray(oCommand['options']) ? oCommand['options'] : oCommand['options'](),
			sFolderName = oCommand['value'] ? oCommand['value']() : '',
			oFolderOption = _.find(aOptions, function (oOption) {
				return oOption[oCommand['optionsValue']] === sFolderName;
			})
		;

		if (!oFolderOption)
		{
			sFolderName = '';
			oCommand['value']('');
		}

		jqElement.removeClass('expand');
		
		jqContainer.empty();

		_.each(aOptions, function (oOption) {
			var jqOption = $('<span class="item"></span>')
				.text(oOption[oCommand['optionsText']])
				.data('value', oOption[oCommand['optionsValue']]);

			if (sFolderName === oOption[oCommand['optionsValue']])
			{
				jqOption.addClass('selected');
			}
			
			oOption['jq'] = jqOption;
			
			jqContainer.append(jqOption);
		});
		
		jqContainer.on('click', '.item', function () {
			var sFolderName = $(this).data('value');
			oCommand['value'](sFolderName);
		});

		jqElement.click(function () {
			jqElement.toggleClass('expand');

			if (jqElement.hasClass('expand'))
			{
				_.defer(function () {
					$(document).one('click', function () {
						jqElement.removeClass('expand');
					});
				});
			}
		});
	},
	'update': function (oElement, fValueAccessor) {
		var
			jqElement = $(oElement),
			oCommand = fValueAccessor(),
			aOptions = _.isArray(oCommand['options']) ? oCommand['options'] : oCommand['options'](),
			sFolderName = oCommand['value'] ? oCommand['value']() : '',
			oFolderOption = _.find(aOptions, function (oOption) {
				return oOption[oCommand['optionsValue']] === sFolderName;
			}),
			jqText = jqElement.find('.link')
		;
		
		_.each(aOptions, function (oOption) {
			if (oOption['jq'])
			{
				oOption['jq'].toggleClass('selected', sFolderName === oOption[oCommand['optionsValue']]);
			}
		});
		
		if (oFolderOption)
		{
			jqText.text($.trim(oFolderOption[oCommand['optionsText']]));
		}
	}
};

ko.bindingHandlers.contactCardInMessage = {
	'update': (bMobileApp || bMobileDevice) ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		var
			jqElement = $(oElement),
			oCommand = fValueAccessor(),
			sAddress = oCommand.address,
			jqPopup = $('div.item_viewer[data-email=\'' + sAddress + '\']'),
			bPopupOpened = false,
			iCloseTimeoutId = 0,
			fOpenPopup = function () {
				if (jqPopup && jqElement)
				{
					bPopupOpened = true;
					clearTimeout(iCloseTimeoutId);
					setTimeout(function () {
						var	oOffset = jqElement.offset(),
							iLeft, iTop, iFitToScreenOffset;
						if (bPopupOpened && oOffset.left + oOffset.top !== 0)
						{
							iLeft = oOffset.left + 10;
							iTop = oOffset.top + jqElement.height() + 6;
							iFitToScreenOffset = $(window).width() - (iLeft + 396); //396 - popup outer width

							if (iFitToScreenOffset > 0) {
								iFitToScreenOffset = 0;
							}
							jqPopup.addClass('expand').offset({'top': iTop, 'left': iLeft + iFitToScreenOffset});
						}
					}, 180);
				}
			},
			fClosePopup = function () {
				if (bPopupOpened && jqPopup && jqElement)
				{
					bPopupOpened = false;
					iCloseTimeoutId = setTimeout(function () {
						if (!bPopupOpened)
						{
							jqPopup.removeClass('expand');
						}
					}, 200);
				}
			}
		;
		
		if (jqPopup.length > 0)
		{
			jqElement
				.off()
				.on('mouseover', function () {
					jqPopup
						.off()
						.on('mouseenter', fOpenPopup)
						.on('mouseleave', fClosePopup)
						.find('.link, .button')
						.off('.links')
						.on('click.links', function () {
							bPopupOpened = false;
							jqPopup.removeClass('expand');
						})
					;

					setTimeout(function () {
						jqPopup
							.find('.link, .button')
							.off('click.links')
							.on('click.links', function () {
								bPopupOpened = false;
								jqPopup.removeClass('expand');
							});
					}.bind(this), 100);

					fOpenPopup();
				})
				.on('mouseout', fClosePopup)
			;

			bPopupOpened = false;
			jqPopup.removeClass('expand');
		}
		else
		{
			jqElement.off();
		}
	}
};

ko.bindingHandlers.contactcard = {
	'init': (bMobileApp || bMobileDevice) ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
		var
			jqElement = $(oElement),
			bShown = false,
			oCommand = _.defaults(
				fValueAccessor(), {
					'disabled': 'disabled',
					'expand': 'expand',
					'control': true
				}
			),
			element = oCommand['control'] ? jqElement.find('.control') : jqElement
		;

		if (oCommand['trigger'] !== undefined && oCommand['trigger'].subscribe !== undefined) {
			
			jqElement.removeClass(oCommand['expand']);
			
			element.bind({
				'mouseover': function() {
					if (!jqElement.hasClass(oCommand['disabled']) && oCommand['trigger']()) {
						bShown = true;
						_.delay(function () {
							if (bShown) {
								if (oCommand['controlWidth'] !== undefined && oCommand['controlWidth'].subscribe !== undefined) {
									oCommand['controlWidth'](element.width());
								}
								jqElement.addClass(oCommand['expand']);
							}
						}, 200);
					}
				},
				'mouseout': function() {
					if (oCommand['trigger']()) {
						bShown = false;
						_.delay(function () {
							if (!bShown) {
								jqElement.removeClass(oCommand['expand']);
							}
						}, 200);
					}
				}
			});
		}
	}
};

ko.bindingHandlers.checkmail = {
	'update': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {
			
		var
			oOptions = oElement.oOptions || null,
			jqElement = oElement.jqElement || null,
			oIconIE = oElement.oIconIE || null,
			values = fValueAccessor(),
			state = values.state
		;

		if (values.state !== undefined) {
			if (!jqElement)
			{
				oElement.jqElement = jqElement = $(oElement);
			}

			if (!oOptions)
			{
				oElement.oOptions = oOptions = _.defaults(
					values, {
						'activeClass': 'process',
						'duration': 800
					}
				);
			}

			Utils.deferredUpdate(jqElement, state, oOptions['duration'], function(element, state){
				if (App.browser.ie9AndBelow)
				{
					if (!oIconIE)
					{
						oElement.oIconIE = oIconIE = jqElement.find('.icon');
					}

					if (!oIconIE.__intervalIE && !!state)
					{
						var
							i = 0,
							style = ''
						;

						oIconIE.__intervalIE = setInterval(function() {
							style = '0px -' + (20 * i) + 'px';
							i = i < 7 ? i + 1 : 0;
							oIconIE.css({'background-position': style});
						} , 1000/12);
					}
					else
					{
						oIconIE.css({'background-position': '0px 0px'});
						clearInterval(oIconIE.__intervalIE);
						oIconIE.__intervalIE = null;
					}
				}
				else
				{
					element.toggleClass(oOptions['activeClass'], state);
				}
			});
		}
	}
};

ko.bindingHandlers.heightAdjust = {
	'update': function (oElement, fValueAccessor, fAllBindingsAccessor) {
		
		var 
			jqElement = oElement.jqElement || null,
			height = 0,
			sLocation = fValueAccessor().location,
			sDelay = fValueAccessor().delay || 400
		;

		if (!jqElement) {
			oElement.jqElement = jqElement = $(oElement);
		}
		_.delay(function () {
			_.each(fValueAccessor().elements, function (mItem) {
				
				var element = mItem();
				if (element) {
					height += element.is(':visible') ? element.outerHeight() : 0;
				}
			});
			
			if (sLocation === 'top' || sLocation === undefined) {
				jqElement.css({
					'padding-top': height,
					'margin-top': -height
				});
			} else if (sLocation === 'bottom') {
				jqElement.css({
					'padding-bottom': height,
					'margin-bottom': -height
				});
			}
		}, sDelay);
	}
};

ko.bindingHandlers.minHeightAdjust = {
	'update': function (oElement, fValueAccessor, fAllBindingsAccessor) {

		var
			jqEl = $(oElement),
			oOptions = fValueAccessor(),
			jqAdjustEl = oOptions.adjustElement || $('body'),
			iMinHeight = oOptions.minHeight || 0
		;
		
		if (oOptions.removeTrigger)
		{
			jqAdjustEl.css('min-height', 'inherit');
		}
		
		if (oOptions.trigger)
		{
			_.delay(function () {
				jqAdjustEl.css({'min-height': jqEl.outerHeight(true) + iMinHeight});
			}, 100);
		}
	}
};

ko.bindingHandlers.watchWidth = {
	'init': function (oElement, fValueAccessor) {
		var isTriggered = false;

		if (!isTriggered) {
			fValueAccessor().subscribe(function () {
				fValueAccessor()($(oElement).outerWidth());
				isTriggered = true;
			}, this);
		}
	}
};

ko.bindingHandlers.columnCalc = {
	'init': function (oElement, fValueAccessor) {

		var
			$oElement = $(oElement),
			oProp = fValueAccessor()['prop'],
			$oItem = null,
			iWidth = 0
		;
			
		$oItem = $oElement.find(fValueAccessor()['itemSelector']);

		if ($oItem[0] === undefined) {
			return;
		}
		
		iWidth = $oItem.outerWidth(true);
		iWidth = 1 >= iWidth ? 1 : iWidth;
		
		if (oProp)
		{
			$(window).bind('resize', function () {
				var iW = $oElement.width();
				oProp(0 < iW ? Math.floor(iW / iWidth) : 1);
			});
		}
	}
};

ko.bindingHandlers.listWithMoreButton = {
	'init': function (oElement, fValueAccessor) {

		var
			$Element = $(oElement),
			skipOneResize = false //for some flicker at slow resize (does not solve the problem completely TODO)
		;

		$Element.closest('div.panel.left_panel').resize(function () {
			
			var
				$ItemsVisible = $Element.find('span.hotkey'),
				$ItemsHidden = $Element.find('span.item'),
				$MoreHints = $Element.find('span.more_hints').show(),
				iElementWidth = $Element.width(),
				iMoreWidth = $MoreHints.width(),
				bHideMoreHints = true
			;

			if (!skipOneResize) {
				_.each($ItemsVisible, function (oItem, index) {

					var
						$Item = $(oItem),
						iItemWidth = $Item.width()
					;

					if (bHideMoreHints && iMoreWidth + iItemWidth < iElementWidth) {
						skipOneResize = false;
						$Item.show();
						$($ItemsHidden[index]).hide();
						iMoreWidth += iItemWidth;
					}
					else
					{
						skipOneResize = true;
						bHideMoreHints = false;
						$Item.hide();
						$($ItemsHidden[index]).show();
					}
				});

				if (bHideMoreHints)
				{
					$MoreHints.hide();
				}
			}
			else
			{
				skipOneResize = false;
			}
		});
	}
};

ko.bindingHandlers.quickReplyAnim = {
	'update': bMobileApp ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var
			jqTextarea = oElement.jqTextarea || null,
			jqStatus = oElement.jqStatus || null,
			jqButtons = oElement.jqButtons || null,
			jqElement = oElement.jqElement || null,
			oPrevActions = oElement.oPrevActions || null,
			values = fValueAccessor(),
			oActions = null
		;

		oActions = _.defaults(
			values, {
				'saveAction': false,
				'sendAction': false,
				'activeAction': false
			}
		);

		if (!jqElement)
		{
			oElement.jqElement = jqElement = $(oElement);
			oElement.jqTextarea = jqTextarea = jqElement.find('textarea');
			oElement.jqStatus = jqStatus = jqElement.find('.status');
			oElement.jqButtons = jqButtons = jqElement.find('.buttons');
			
			oElement.oPrevActions = oPrevActions = {
				'saveAction': null,
				'sendAction': null,
				'activeAction': null
			};
		}

		if (jqElement.is(':visible'))
		{
			if (App.browser.ie9AndBelow)
			{
				if (jqTextarea && !jqElement.defualtHeight && !jqTextarea.defualtHeight)
				{
					jqElement.defualtHeight = jqElement.outerHeight();
					jqTextarea.defualtHeight = jqTextarea.outerHeight();
					jqStatus.defualtHeight = jqButtons.outerHeight();
					jqButtons.defualtHeight = jqButtons.outerHeight();
				}

				_.defer(function () {
					var 
						activeChanged = oPrevActions.activeAction !== oActions['activeAction'],
						sendChanged = oPrevActions.sendAction !== oActions['sendAction'],
						saveChanged = oPrevActions.saveAction !== oActions['saveAction']
					;

					if (activeChanged)
					{
						if (oActions['activeAction'])
						{
							jqTextarea.animate({
								'height': jqTextarea.defualtHeight + 50
							}, 300);
							jqElement.animate({
								'max-height': jqElement.defualtHeight + jqButtons.defualtHeight + 50
							}, 300);
						}
						else
						{
							jqTextarea.animate({
								'height': jqTextarea.defualtHeight
							}, 300);
							jqElement.animate({
								'max-height': jqElement.defualtHeight
							}, 300);
						}
					}

					if (sendChanged || saveChanged)
					{
						if (oActions['sendAction'])
						{
							jqElement.animate({
								'max-height': '30px'
							}, 300);
							jqStatus.animate({
								'max-height': '30px',
								'opacity': 1
							}, 300);
						}
						else if (oActions['saveAction'])
						{
							jqElement.animate({
								'max-height': 0
							}, 300);
						}
						else
						{
							jqElement.animate({
								'max-height': jqElement.defualtHeight + jqButtons.defualtHeight + 50
							}, 300);
							jqStatus.animate({
								'max-height': 0,
								'opacity': 0
							}, 300);
						}
					}
				});
			}
			else
			{
				jqElement.toggleClass('saving', oActions['saveAction']);
				jqElement.toggleClass('sending', oActions['sendAction']);
				jqElement.toggleClass('active', oActions['activeAction']);
			}
		}

		_.defer(function () {
			oPrevActions = oActions;
		});
	}
};

ko.extenders.reversible = function (oTarget)
{
	var mValue = oTarget();

	oTarget.commit = function ()
	{
		mValue = oTarget();
	};

	oTarget.revert = function ()
	{
		oTarget(mValue);
	};

	oTarget.commitedValue = function ()
	{
		return mValue;
	};

	oTarget.changed = function ()
	{
		return mValue !== oTarget();
	};
	
	return oTarget;
};

ko.extenders.autoResetToFalse = function (oTarget, iOption)
{
	oTarget.iTimeout = 0;
	oTarget.subscribe(function (bValue) {
		if (bValue)
		{
			window.clearTimeout(oTarget.iTimeout);
			oTarget.iTimeout = window.setTimeout(function () {
				oTarget.iTimeout = 0;
				oTarget(false);
			}, Utils.pInt(iOption));
		}
	});

	return oTarget;
};

/**
 * @param {(Object|null|undefined)} oContext
 * @param {Function} fExecute
 * @param {(Function|boolean|null)=} fCanExecute
 * @return {Function}
 */
Utils.createCommand = function (oContext, fExecute, fCanExecute)
{
	var
		fResult = fExecute ? function () {
			if (fResult.canExecute && fResult.canExecute())
			{
				return fExecute.apply(oContext, Array.prototype.slice.call(arguments));
			}
			return false;
		} : function () {}
	;

	fResult.enabled = ko.observable(true);

	fCanExecute = Utils.isUnd(fCanExecute) ? true : fCanExecute;
	if (Utils.isFunc(fCanExecute))
	{
		fResult.canExecute = ko.computed(function () {
			return fResult.enabled() && fCanExecute.call(oContext);
		});
	}
	else
	{
		fResult.canExecute = ko.computed(function () {
			return fResult.enabled() && !!fCanExecute;
		});
	}

	return fResult;
};

ko.bindingHandlers.autocomplete = {
	'init': function (oElement, fValueAccessor) {
		
		function split(val)
		{
			return val.split(/,\s*/);
		}

		function extractLast(term)
		{
			return split(term).pop();
		}

		var 
			fCallback = fValueAccessor(),
			oJqElement = $(oElement)
		;
		
		if (fCallback && oJqElement && oJqElement[0])
		{
			oJqElement.autocomplete({
				'minLength': 1,
				'autoFocus': true,
				'source': function (request, response) {
					fCallback(extractLast(request['term']), response);
				},
				'search': function () {
					var term = extractLast(this.value);
					if (term.length < 1) {
						return false;
					}

					return true;
				},
				'focus': function () {
					return false;
				},
				'select': function (event, ui) {
					var terms = split(this.value), moveCursorToEnd = null;

					terms.pop();
					terms.push(ui['item']['value']);
					terms.push('');

					this.value = terms.join(', ').slice(0, -2);

					oJqElement.trigger('change');

					// Move to the end of the input string
					moveCursorToEnd = function(el) {
						var endIndex = el.value.length;

						//Chrome
						el.blur();
						el.focus();
						//IE, firefox and Opera
						if (el.setSelectionRange) {
							el.setSelectionRange(endIndex, endIndex);
						}
					};
					moveCursorToEnd(oJqElement[0]);

					return false;
				}
			});
		}
	}
};

ko.bindingHandlers.autocompleteSimple = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var
			jqEl = $(oElement),
			oOptions = fValueAccessor(),
			fCallback = oOptions['callback'],
			fDataAccessor = oOptions.dataAccessor
//			fAutocompleteTrigger = oOptions.autocompleteTrigger
		;

		if (fCallback && jqEl && jqEl[0])
		{
			jqEl.autocomplete({
				'minLength': 1,
				'autoFocus': true,
				'position': {
					collision: "flip"
				},
				'source': function (request, response) {
					fCallback(request['term'], response);
				},
				'focus': function () {
					return false;
				},
				'select': function (oEvent, oItem) {
					_.delay(function () {
						jqEl.trigger('change');
					}, 5);

					if (fDataAccessor)
					{
						fDataAccessor(oItem.item);
					}

					return true;
				}
			});

			/*if (fAutocompleteTrigger)
			{
				fAutocompleteTrigger.subscribe(function() {
					jqEl.autocomplete( "option", "minLength", 0 );// dirty hack for trigger search
					jqEl.autocomplete("search");
					jqEl.autocomplete( "option", "minLength", 1 );
				}, this);
			}*/
		}
	}
};


ko.bindingHandlers.draggablePlace = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {
		if (fValueAccessor() === null)
		{
			return null;
		}

		var oAllBindingsAccessor = fAllBindingsAccessor ? fAllBindingsAccessor() : null;
		$(oElement).draggable({
			'distance': 20,
			'handle': '.dragHandle',
			'cursorAt': {'top': 0, 'left': 0},
			'helper': function (oEvent) {
				//return fValueAccessor().call(oViewModel, oEvent && oEvent.target ? ko.dataFor(oEvent.target) : null);
				return fValueAccessor().apply(oViewModel, oEvent && oEvent.target ? [ko.dataFor(oEvent.target), oEvent.ctrlKey] : null);
			},
			'start': (oAllBindingsAccessor && oAllBindingsAccessor['draggableDragStartCallback']) ? oAllBindingsAccessor['draggableDragStartCallback'] : Utils.emptyFunction,
			'stop': (oAllBindingsAccessor && oAllBindingsAccessor['draggableDragStopCallback']) ? oAllBindingsAccessor['draggableDragStopCallback'] : Utils.emptyFunction
		}).on('mousedown', function () {
			Utils.removeActiveFocus();
		});
	}
};

ko.bindingHandlers.droppable = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor) {
		var oOptions = fValueAccessor(),
			fValueFunc = oOptions.valueFunc,
			fSwitchObserv = oOptions.switchObserv
		;
		if (false !== fValueFunc)
		{
			$(oElement).droppable({
				'hoverClass': 'droppableHover',
				'drop': function (oEvent, oUi) {
					fValueFunc(oEvent, oUi);
				}
			});
		}
		if(fSwitchObserv && fValueFunc !== false)
		{
			fSwitchObserv.subscribe(function (bIsSelected) {
				if($(oElement).data().droppable)
				{
					if(bIsSelected)
					{
						$(oElement).droppable('disable');
					}
					else
					{
						$(oElement).droppable('enable');
					}
				}
			}, this);
			fSwitchObserv.valueHasMutated();
		}
	}
};

ko.bindingHandlers.draggable = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor) {
		$(oElement).attr('draggable', ko.utils.unwrapObservable(fValueAccessor()));
	}
};

ko.bindingHandlers.autosize = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var
			jqEl = $(oElement),
			oOptions = fValueAccessor(),
			iHeight = jqEl.height(),
			iOuterHeight = jqEl.outerHeight(),
			iInnerHeight = jqEl.innerHeight(),
			iBorder = iOuterHeight - iInnerHeight,
			iPaddingTB = iInnerHeight - iHeight,
			iMinHeight = oOptions.minHeight ? oOptions.minHeight : 0,
			iMaxHeight = oOptions.maxHeight ? oOptions.maxHeight : 0,
			iScrollableHeight = oOptions.scrollableHeight ? oOptions.scrollableHeight : 1000,// max-height of .scrollable_field
			oAutosizeTrigger = oOptions.autosizeTrigger ? oOptions.autosizeTrigger : null,
				
			/**
			 * @param {boolean=} bIgnoreScrollableHeight
			 */
			fResize = function (bIgnoreScrollableHeight) {
				var iPadding = 0;

				if (App.browser.firefox)
				{
					iPadding = parseInt(jqEl.css('padding-top'), 10) * 2;
				}

				if (iMaxHeight)
				{
					/* 0-timeout to get the already changed text */
					setTimeout(function () {
						if (jqEl.prop('scrollHeight') < iMaxHeight)
						{
							jqEl.height(iMinHeight - iPaddingTB - iBorder);
							jqEl.height(jqEl.prop('scrollHeight') + iPadding - iPaddingTB);
						}
						else
						{
							jqEl.height(iMaxHeight - iPaddingTB - iBorder);
						}
					}, 100);
				}
				else if (bIgnoreScrollableHeight || jqEl.prop('scrollHeight') < iScrollableHeight)
				{
					setTimeout(function () {
						jqEl.height(iMinHeight - iPaddingTB - iBorder);
						jqEl.height(jqEl.prop('scrollHeight') + iPadding - iPaddingTB);
						//$('.calendar_event .scrollable_field').scrollTop(jqEl.height('scrollHeight'))
					}, 100);
				}
			}
		;

		jqEl.on('keydown', function(oEvent, oData) {
			fResize();
		});
		jqEl.on('paste', function(oEvent, oData) {
			fResize();
		});
		/*jqEl.on('input', function(oEvent, oData) {
			fResize();
		});
		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'keydown': function (oData, oEvent) {
					fResize();
					return true;
				}
			};
		}, fAllBindingsAccessor, oViewModel);*/

		if (oAutosizeTrigger)
		{
			oAutosizeTrigger.subscribe(function (arg) {
				fResize(arg);
			}, this);
		}

		fResize();
	}
};

ko.bindingHandlers.customBind = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var
			oOptions = fValueAccessor(),
			oKeydown = oOptions.onKeydown ? oOptions.onKeydown : null,
			oKeyup = oOptions.onKeyup ? oOptions.onKeyup : null,
			oPaste = oOptions.onPaste ? oOptions.onPaste : null,
			oInput = oOptions.onInput ? oOptions.onInput : null,
			oValueObserver = oOptions.valueObserver ? oOptions.valueObserver : null
		;

		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'keydown': function (oData, oEvent) {
					if(oKeydown)
					{
						oKeydown.call(this, oElement, oEvent, oValueObserver);
					}
					return true;
				},
				'keyup': function (oData, oEvent) {
					if(oKeyup)
					{
						oKeyup.call(this, oElement, oEvent, oValueObserver);
					}
					return true;
				},
				'paste': function (oData, oEvent) {
					if(oPaste)
					{
						oPaste.call(this, oElement, oEvent, oValueObserver);
					}
					return true;
				},
				'input': function (oData, oEvent) {
					if(oInput)
					{
						oInput.call(this, oElement, oEvent, oValueObserver);
					}
					return true;
				}
			};
		}, fAllBindingsAccessor, oViewModel);
	}
};

ko.bindingHandlers.fade = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var jqEl = $(oElement),
			jqElFaded = $('<span class="faded"></span>'),
			oOptions = _.defaults(
				fValueAccessor(), {
					'color': null,
					'css': 'fadeout'
				}
			),
			oColor = oOptions.color,
			sCss = oOptions.css,
			updateColor = function (sColor)
			{
				if (sColor === '') {
					return;
				}

				var
					oHex2Rgb = hex2Rgb(sColor),
					sRGBColor = "rgba(" + oHex2Rgb.r + "," + oHex2Rgb.g + "," + oHex2Rgb.b
				;

				colorIt(sColor, sRGBColor);
			},
			hex2Rgb = function (sHex) {
				// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
				var
					shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i,
					result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(sHex)
				;
				sHex = sHex.replace(shorthandRegex, function(m, r, g, b) {
					return r + r + g + g + b + b;
				});

				return result ? {
					r: parseInt(result[1], 16),
					g: parseInt(result[2], 16),
					b: parseInt(result[3], 16)
				} : null;
			},
			colorIt = function (hex, rgb) {
				if (Utils.isRTL())
				{
					jqElFaded
						.css("filter", "progid:DXImageTransform.Microsoft.gradient(startColorstr='" + hex + "', endColorstr='" + hex + "',GradientType=1 )")
						.css("background-image", "-webkit-gradient(linear, left top, right top, color-stop(0%," + rgb + ",1)" + "), color-stop(100%," + rgb + ",0)" + "))")
						.css("background-image", "-moz-linear-gradient(left, " + rgb + ",1)" + "0%, " + rgb + ",0)" + "100%)")
						.css("background-image", "-webkit-linear-gradient(left, " + rgb + "1)" + "0%," + rgb + ",0)" + "100%)")
						.css("background-image", "-o-linear-gradient(left, " + rgb + ",1)" + "0%," + rgb + ",0)" + "100%)")
						.css("background-image", "-ms-linear-gradient(left, " + rgb + ",1)" + "0%," + rgb + ",0)" + "100%)")
						.css("background-image", "linear-gradient(left, " + rgb + ",1)" + "0%," + rgb + ",0)" + "100%)");
				}
				else
				{
					jqElFaded
						.css("filter", "progid:DXImageTransform.Microsoft.gradient(startColorstr='" + hex + "', endColorstr='" + hex + "',GradientType=1 )")
						.css("background-image", "-webkit-gradient(linear, left top, right top, color-stop(0%," + rgb + ",0)" + "), color-stop(100%," + rgb + ",1)" + "))")
						.css("background-image", "-moz-linear-gradient(left, " + rgb + ",0)" + "0%, " + rgb + ",1)" + "100%)")
						.css("background-image", "-webkit-linear-gradient(left, " + rgb + ",0)" + "0%," + rgb + ",1)" + "100%)")
						.css("background-image", "-o-linear-gradient(left, " + rgb + ",0)" + "0%," + rgb + ",1)" + "100%)")
						.css("background-image", "-ms-linear-gradient(left, " + rgb + ",0)" + "0%," + rgb + ",1)" + "100%)")
						.css("background-image", "linear-gradient(left, " + rgb + ",0)" + "0%," + rgb + ",1)" + "100%)");
				}
			}
		;

		jqEl.parent().addClass(sCss);
		jqEl.after(jqElFaded);

		if (oOptions.color.subscribe !== undefined)
		{
			updateColor(oColor());
			oColor.subscribe(function (sColor) {
				updateColor(sColor);
			}, this);
		}
	}
};

ko.bindingHandlers.highlighter = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var
			jqEl = $(oElement),
			oOptions = fValueAccessor(),
			oValueObserver = oOptions.valueObserver ? oOptions.valueObserver : null,
			oHighlighterValueObserver = oOptions.highlighterValueObserver ? oOptions.highlighterValueObserver : null,
			oHighlightTrigger = oOptions.highlightTrigger ? oOptions.highlightTrigger : null,
			aHighlightWords = ['from:', 'to:', 'subject:', 'text:', 'email:', 'has:', 'date:', 'text:', 'body:'],
			rPattern = (function () {
				var sPatt = '';
				$.each(aHighlightWords, function(i, oEl) {
					sPatt = (!i) ? (sPatt + '\\b' + oEl) : (sPatt + '|\\b' + oEl);
				});

				return new RegExp('(' + sPatt + ')', 'g');
			}()),
			fClear = function (sStr) {
				return sStr.replace(/\xC2\xA0/g, ' ').replace(/\xA0/g, ' ').replace(/[\s]+/g, ' ');
			},
			iPrevKeyCode = -1,
			sUserLanguage = window.navigator.language || window.navigator.userLanguage,
			aTabooLang = ['zh', 'zh-TW', 'zh-CN', 'zh-HK', 'zh-SG', 'zh-MO', 'ja', 'ja-JP', 'ko', 'ko-KR', 'vi', 'vi-VN', 'th', 'th-TH'],// , 'ru', 'ru-RU'
			bHighlight = !_.include(aTabooLang, sUserLanguage)
		;

		ko.bindingHandlers.event.init(oElement, function () {
			return {
				'keydown': function (oData, oEvent) {
					return oEvent.keyCode !== Enums.Key.Enter;
				},
				'keyup': function (oData, oEvent) {
					var
						aMoveKeys = [Enums.Key.Left, Enums.Key.Right, Enums.Key.Home, Enums.Key.End],
						bMoveKeys = -1 !== Utils.inArray(oEvent.keyCode, aMoveKeys)
					;

					if (!(
//							oEvent.keyCode === Enums.Key.Enter					||
							oEvent.keyCode === Enums.Key.Shift					||
							oEvent.keyCode === Enums.Key.Ctrl					||
							// for international english -------------------------
							oEvent.keyCode === Enums.Key.Dash					||
							oEvent.keyCode === Enums.Key.Apostrophe				||
							oEvent.keyCode === Enums.Key.Six && oEvent.shiftKey	||
							// ---------------------------------------------------
							bMoveKeys											||
//							((oEvent.shiftKey || iPrevKeyCode === Enums.Key.Shift) && bMoveKeys) ||
							((oEvent.ctrlKey || iPrevKeyCode === Enums.Key.Ctrl) && oEvent.keyCode === Enums.Key.a)
						))
					{
						oValueObserver(fClear(jqEl.text()));
						highlight(false);
					}
					iPrevKeyCode = oEvent.keyCode;
					return true;
				},
				// firefox fix for html paste
				'paste': function (oData, oEvent) {
					setTimeout(function () {
						oValueObserver(fClear(jqEl.text()));
						highlight(false);
					}, 0);
					return true;
				}
			};
		}, fAllBindingsAccessor, oViewModel);

		// highlight on init
		setTimeout(function () {
			highlight(true);
		}, 0);

		function highlight(bNotRestoreSel) {
			if(bHighlight)
			{
				var
					iCaretPos = 0,
					sContent = jqEl.text(),
					aContent = sContent.split(rPattern),
					aDividedContent = [],
					sReplaceWith = '<span class="search_highlight"' + '>$&</span>'
				;

				$.each(aContent, function (i, sEl) {
					if (_.any(aHighlightWords, function (oAnyEl) {return oAnyEl === sEl;}))
					{
						$.each(sEl, function (i, sElem) {
							aDividedContent.push($(sElem.replace(/(.)/, sReplaceWith)));
						});
					}
					else
					{
						$.each(sEl, function(i, sElem) {
							if(sElem === ' ')
							{
								// space fix for firefox
								aDividedContent.push(document.createTextNode('\u00A0'));
							}
							else
							{
								aDividedContent.push(document.createTextNode(sElem));
							}
						});
					}
				});

				if (bNotRestoreSel)
				{
					jqEl.empty().append(aDividedContent);
				}
				else
				{
					iCaretPos = getCaretOffset();
					jqEl.empty().append(aDividedContent);
					setCursor(iCaretPos);
				}
			}
		}

		function getCaretOffset() {
			var
				caretOffset = 0,
				range,
				preCaretRange,
				textRange,
				preCaretTextRange
				;

			if (typeof window.getSelection !== "undefined")
			{
				range = window.getSelection().getRangeAt(0);
				preCaretRange = range.cloneRange();
				preCaretRange.selectNodeContents(oElement);
				preCaretRange.setEnd(range.endContainer, range.endOffset);
				caretOffset = preCaretRange.toString().length;
			}
			else if (typeof document.selection !== "undefined" && document.selection.type !== "Control")
			{
				textRange = document.selection.createRange();
				preCaretTextRange = document.body.createTextRange();
				preCaretTextRange.moveToElementText(oElement);
				preCaretTextRange.setEndPoint("EndToEnd", textRange);
				caretOffset = preCaretTextRange.text.length;
			}

			return caretOffset;
		}

		function setCursor(iCaretPos) {
			var
				range,
				selection,
				textRange
				;

			if (!oElement)
			{
				return false;
			}
			else if(document.createRange)
			{
				range = document.createRange();
				range.selectNodeContents(oElement);
				range.setStart(oElement, iCaretPos);
				range.setEnd(oElement, iCaretPos);
				selection = window.getSelection();
				selection.removeAllRanges();
				selection.addRange(range);
			}
			else if(oElement.createTextRange)
			{
				textRange = oElement.createTextRange();
				textRange.collapse(true);
				textRange.moveEnd(iCaretPos);
				textRange.moveStart(iCaretPos);
				textRange.select();
				return true;
			}
			else if(oElement.setSelectionRange)
			{
				oElement.setSelectionRange(iCaretPos, iCaretPos);
				return true;
			}

			return false;
		}

		oHighlightTrigger.notifySubscribers();

		oHighlightTrigger.subscribe(function (bNotRestoreSel) {
			setTimeout(function () {
				highlight(!!bNotRestoreSel);
			}, 0);
		}, this);

		oHighlighterValueObserver.subscribe(function () {
			jqEl.text(oValueObserver());
		}, this);
	}
};

ko.bindingHandlers.quoteText = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var
			jqEl = $(oElement),
			jqButton = $('<span class="button_quote">' + Utils.i18n('HELPDESK/BUTTON_QUOTE') + '</span>'),
			oOptions = fValueAccessor(),
			fActionHandler = oOptions.actionHandler,
			bIsQuoteArea = false,
			oSelection = null,
			sText = ''
		;

		$('#pSevenContent').append(jqButton);

		$(document.body).on('click', function(oEvent) {

			bIsQuoteArea = !!(($(oEvent.target)).parents('.posts')[0]);
			if (document.getSelection)
			{
				oSelection = document.getSelection();
				if (oSelection)
				{
					sText = oSelection.toString();
				}
			}
			else
			{
				sText = document.selection.createRange().text;
			}

			if(bIsQuoteArea)
			{
				if(sText.replace(/[\n\r\s]/, '') !== '') //replace - for dbl click on empty area
				{
					jqButton.css({
						'top': oEvent.clientY + 20, //20 - custom indent
						'left': oEvent.clientX + 20
					}).show();
				}
				else
				{
					jqButton.hide();
				}
			}
			else
			{
				jqButton.hide();
			}
		});

		jqButton.on('click', function(oEvent) {
			fActionHandler.call(oViewModel, sText);
		});
	}
};

ko.bindingHandlers.adjustHeightToContent = {
	'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {

		var
			jqEl = $(oElement),
			jqTargetEl = null,
			jqParentEl = null,
			jqNearEl = null
		;

		_.delay(_.bind(function(){
			jqTargetEl = $(_.max(jqEl.find('.title .text'), function(domEl){
				return domEl.offsetWidth;
			}));

			jqParentEl = jqTargetEl.parent();
			jqNearEl = jqParentEl.find('.icon');

			jqEl.css('min-width',
				parseInt(jqParentEl.css("margin-left")) +
				parseInt(jqParentEl.css("padding-left")) +
				parseInt(jqNearEl.width()) +
				parseInt(jqNearEl.css("margin-left")) +
				parseInt(jqNearEl.css("margin-right")) +
				parseInt(jqNearEl.css("padding-left")) +
				parseInt(jqNearEl.css("padding-right")) +
				parseInt(jqTargetEl.width()) +
				parseInt(jqTargetEl.css("margin-left")) +
				parseInt(jqTargetEl.css("padding-left")) +
				10
			);
		},this), 1);
	}
};

ko.bindingHandlers.customTooltip = {
	'init': (bMobileDevice || bMobileApp) ? null : function (oElement, fValueAccessor) {
		var
			sTooltipText = Utils.i18n(fValueAccessor()),
			$Element = $(oElement),
			$Dropdown = $Element.find('span.dropdown'),
			bShown = false,
			iTimer, iHideTimer,
			fMouseIn = function () {
				var $ItemToAlign = $(this);
				if (!$ItemToAlign.hasClass('expand'))
				{
					clearTimeout(iHideTimer);
					bShown = true;
					clearTimeout(iTimer);
					iTimer = setTimeout(function () {
						if (bShown)
						{
							if ($ItemToAlign.hasClass('expand'))
							{
								bShown = false;
								clearTimeout(iTimer);
								Utils.CustomTooltip.hide();
							}
							else
							{
								Utils.CustomTooltip.show(sTooltipText, $ItemToAlign);
							}
						}
					}, 100);
				}
			},
			fMouseOut = function () {
				clearTimeout(iHideTimer);
				iHideTimer = setTimeout(function () {
					bShown = false;
					clearTimeout(iTimer);
					Utils.CustomTooltip.hide();
				}, 10);
			},
			fEmpty = function () {},
			fBindEvents = function () {
				$Element.unbind('mouseover', fMouseIn);
				$Element.unbind('mouseout', fMouseOut);
				$Element.unbind('click', fMouseOut);
				$Dropdown.unbind('mouseover', fMouseOut);
				$Dropdown.unbind('mouseout', fEmpty);
				if (sTooltipText !== '')
				{
					$Element.bind('mouseover', fMouseIn);
					$Element.bind('mouseout', fMouseOut);
					$Element.bind('click', fMouseOut);
					$Dropdown.bind('mouseover', fMouseOut);
					$Dropdown.bind('mouseout', fEmpty);
				}
			},
			fSubscribtion = null
		;
		
		if (typeof sTooltipText === 'function')
		{
			sTooltipText = sTooltipText();
		}
		
		fBindEvents();
		
		if (typeof fValueAccessor().subscribe === 'function' && fSubscribtion === null)
		{
			fSubscribtion = fValueAccessor().subscribe(function (sValue) {
				sTooltipText = sValue;
				fBindEvents();
			});
		}
	}
};


/**
 * @constructor
 */
function CRouting()
{
	this.defaultScreen = Enums.Screens.Mailbox;
	this.currentScreen = Enums.Screens.Mailbox;
	this.lastMailboxHash = ko.observable(Enums.Screens.Mailbox);
	this.lastHelpdeskHash = ko.observable(Enums.Screens.Helpdesk);
	this.lastSettingsHash = ko.observable(Enums.Screens.Settings);

	this.currentHash = ko.observable('');
	this.previousHash = ko.observable('');
}

/**
 * Initializes object.
 * 
 * @param {string} sDefaultScreen
 */
CRouting.prototype.init = function (sDefaultScreen)
{
	this.defaultScreen = sDefaultScreen;
	hasher.initialized.removeAll();
	hasher.changed.removeAll();
	hasher.initialized.add(this.parseRouting, this);
	hasher.changed.add(this.parseRouting, this);
	hasher.init();
	hasher.initialized.removeAll();
};

/**
 * Finalizes the object and puts an empty hash.
 */
CRouting.prototype.finalize = function ()
{
	hasher.dispose();
	this.setHashFromString('');
};

/**
 * Sets a new hash.
 * 
 * @param {string} sNewHash
 * 
 * @return {boolean}
 */
CRouting.prototype.setHashFromString = function (sNewHash)
{
	var bSame = (location.hash === decodeURIComponent(sNewHash));
	
	if (!bSame)
	{
		location.hash = sNewHash;
	}
	
	return bSame;
};

/**
 * Sets a new hash without part.
 * 
 * @param {string} sUid
 */
CRouting.prototype.replaceHashWithoutMessageUid = function (sUid)
{
	if (typeof sUid === 'string' && sUid !== '')
	{
		var sNewHash = location.hash.replace('/msg' + sUid, '');
		this.replaceHashFromString(sNewHash);
	}
};

/**
 * Sets a new hash.
 * 
 * @param {string} sNewHash
 */
CRouting.prototype.replaceHashFromString = function (sNewHash)
{
	if (location.hash !== sNewHash)
	{
		location.replace(sNewHash);
	}
};

/**
 * Sets a new hash made ​​up of an array.
 * 
 * @param {Array} aRoutingParts
 * 
 * @return boolean
 */
CRouting.prototype.setHash = function (aRoutingParts)
{
	return this.setHashFromString(this.buildHashFromArray(aRoutingParts));
};

/**
 * @param {Array} aRoutingParts
 */
CRouting.prototype.replaceHash = function (aRoutingParts)
{
	this.replaceHashFromString(this.buildHashFromArray(aRoutingParts));
};

/**
 * @param {Array} aRoutingParts
 */
CRouting.prototype.replaceHashDirectly = function (aRoutingParts)
{
	hasher.stop();
	this.replaceHashFromString(this.buildHashFromArray(aRoutingParts));
	hasher.init();
};

CRouting.prototype.setPreviousHash = function ()
{
	location.hash = this.previousHash();
};

/**
 * Makes a hash of a string array.
 *
 * @param {(string|Array)} aRoutingParts
 * 
 * @return {string}
 */
CRouting.prototype.buildHashFromArray = function (aRoutingParts)
{
	var
		iIndex = 0,
		iLen = 0,
		sHash = ''
	;

	if (_.isArray(aRoutingParts))
	{
		for (iLen = aRoutingParts.length; iIndex < iLen; iIndex++)
		{
			aRoutingParts[iIndex] = encodeURIComponent(aRoutingParts[iIndex]);
		}
	}
	else
	{
		aRoutingParts = [encodeURIComponent(aRoutingParts.toString())];
	}
	
	sHash = aRoutingParts.join('/');
	
	if (sHash !== '')
	{
		sHash = '#' + sHash;
	}

	return sHash;
};

/**
 * Returns the value of the hash string of location.href.
 * location.hash returns the decoded string and location.href - not, so it uses location.href.
 * 
 * @return {string}
 */
CRouting.prototype.getHashFromHref = function ()
{
	var
		iPos = location.href.indexOf('#'),
		sHash = ''
	;

	if (iPos !== -1)
	{
		sHash = location.href.substr(iPos + 1);
	}

	return sHash;
};

CRouting.prototype.isSingleMode = function ()
{
	var
		sScreen = this.getScreenFromHash(),
		bSingleMode = (sScreen === Enums.Screens.SingleMessageView || sScreen === Enums.Screens.SingleCompose || 
			sScreen === Enums.Screens.SingleHelpdesk)
	;
	
	this.currentScreen = sScreen;
	
	return bSingleMode;
};

/**
 * @param {Array} aRoutingParts
 * @param {Array} aAddParams
 */
CRouting.prototype.goDirectly = function (aRoutingParts, aAddParams)
{
	hasher.stop();
	this.setHash(aRoutingParts);
	this.parseRouting(aAddParams);
	hasher.init();
};

/**
 * @param {string} sNeedScreen
 */
CRouting.prototype.historyBackWithoutParsing = function (sNeedScreen)
{
	hasher.stop();
	location.hash = this.currentHash();
	hasher.init();
};

/**
 * @returns {String}
 */
CRouting.prototype.getScreenFromHash = function ()
{
	var
		sHash = this.getHashFromHref(),
		aHash = sHash.split('/')
	;
	return decodeURIComponent(aHash.shift()) || this.defaultScreen;
};

/**
 * @param {Array} aAddParams
 */
CRouting.prototype.parseRouting = function (aAddParams)
{
	var
		oCurrentModel = App.Screens.getCurrentScreenModel(),
		fContinueScreenChanging = _.bind(this.chooseScreen, this, aAddParams)
	;
	
	if (oCurrentModel && Utils.isFunc(oCurrentModel.beforeHide))
	{
		oCurrentModel.beforeHide(fContinueScreenChanging);
	}
	else
	{
		fContinueScreenChanging();
	}
};

/**
 * Parses the hash string and opens the corresponding routing screen.
 * 
 * @param {Array} aAddParams
 */
CRouting.prototype.chooseScreen = function (aAddParams)
{
	var
		sHash = this.getHashFromHref(),
		aHash = sHash.split('/'),
		sScreen = decodeURIComponent(aHash.shift()) || this.defaultScreen,
		bScreenInEnum = !!_.find(Enums.Screens, function (sScreenInEnum) {
			return sScreenInEnum === sScreen;
		}),
		iIndex = 0,
		iLen = aHash.length
	;

	if (sScreen === Enums.Screens.Mailbox)
	{
		this.lastMailboxHash(sHash);
	}
	if (sScreen === Enums.Screens.Helpdesk)
	{
		this.lastHelpdeskHash(sHash);
	}
	if (sScreen === Enums.Screens.Settings)
	{
		this.lastSettingsHash(sHash);
	}
	this.previousHash(this.currentHash());
	this.currentHash(sHash);
	
	for (; iIndex < iLen; iIndex++)
	{
		aHash[iIndex] = decodeURIComponent(aHash[iIndex]);
	}
	
	if ($.isArray(aAddParams))
	{
		aHash = _.union(aHash, aAddParams);
	}
	
	this.currentScreen = sScreen;
	
	switch (sScreen)
	{
		case Enums.Screens.SingleMessageView:
		case Enums.Screens.SingleCompose:
		case Enums.Screens.SingleHelpdesk:
			AppData.SingleMode = true;
			App.Screens.showCurrentScreen(sScreen, aHash);
			break;
		default:
			if (!bScreenInEnum)
			{
				sScreen = this.defaultScreen;
			}
			AppData.SingleMode = false;
			App.Screens.showNormalScreen(Enums.Screens.Header);
			App.Screens.showCurrentScreen(sScreen, aHash);
			break;
		case Enums.Screens.Mailbox:
			AppData.SingleMode = false;
			App.Screens.showNormalScreen(Enums.Screens.Header);
			App.Screens.showCurrentScreen(Enums.Screens.Mailbox, aHash);
			break;
	}
};


/**
 * @constructor
 */
function CLinkBuilder()
{
}

/**
 * @param {string=} sFolder = 'INBOX'
 * @param {number=} iPage = 1
 * @param {string=} sUid = ''
 * @param {string=} sSearch = ''
 * @param {string=} sFilters = ''
 * @return {Array}
 */
CLinkBuilder.prototype.mailbox = function (sFolder, iPage, sUid, sSearch, sFilters)
{
	var	aResult = [Enums.Screens.Mailbox];
	
	iPage = Utils.isNormal(iPage) ? Utils.pInt(iPage) : 1;
	sUid = Utils.isNormal(sUid) ? Utils.pString(sUid) : '';
	sSearch = Utils.isNormal(sSearch) ? Utils.pString(sSearch) : '';
	sFilters = Utils.isNormal(sFilters) ? Utils.pString(sFilters) : '';

	if (sFolder && '' !== sFolder)
	{
		aResult.push(sFolder);
	}
	
	if (sFilters && '' !== sFilters)
	{
		aResult.push('filter:' + sFilters);
	}
	
	if (1 < iPage)
	{
		aResult.push('p' + iPage);
	}

	if (sUid && '' !== sUid)
	{
		aResult.push('msg' + sUid);
	}

	if (sSearch && '' !== sSearch)
	{
		aResult.push(sSearch);
	}
	
	return aResult;
};

/**
 * @return {Array}
 */
CLinkBuilder.prototype.inbox = function ()
{
	return this.mailbox();
};

/**
 * @param {Array} aParams
 * 
 * @return {Object}
 */
CLinkBuilder.prototype.parseMailbox = function (aParams)
{
	var
		sFolder = 'INBOX',
		iPage = 1,
		sUid = '',
		sSearch = '',
		sFilters = '',
		sTemp = '',
		iIndex = 0
	;
	
	if (Utils.isNonEmptyArray(aParams))
	{
		sFolder = Utils.pString(aParams[iIndex]);
		iIndex++;

		if (aParams.length > iIndex)
		{
			sTemp = Utils.pString(aParams[iIndex]);
			if (sTemp === 'filter:' + Enums.FolderFilter.Flagged)
			{
				sFilters = Enums.FolderFilter.Flagged;
				iIndex++;
			}
			if (sTemp === 'filter:' + Enums.FolderFilter.Unseen)
			{
				sFilters = Enums.FolderFilter.Unseen;
				iIndex++;
			}
		}

		if (aParams.length > iIndex)
		{
			sTemp = Utils.pString(aParams[iIndex]);
			if (this.isPageParam(sTemp))
			{
				iPage = Utils.pInt(sTemp.substr(1));
				if (iPage <= 0)
				{
					iPage = 1;
				}
				iIndex++;
			}
		}
		
		if (aParams.length > iIndex)
		{
			sTemp = Utils.pString(aParams[iIndex]);
			if (this.isMsgParam(sTemp))
			{
				sUid = sTemp.substr(3);
				iIndex++;
			}
		}

		if (aParams.length > iIndex)
		{
			sSearch = Utils.pString(aParams[iIndex]);
		}
	}
	
	return {
		'Folder': sFolder,
		'Page': iPage,
		'Uid': sUid,
		'Search': sSearch,
		'Filters': sFilters
	};
};

/**
 * @param {number=} iType
 * @param {string=} sGroupId
 * @param {string=} sSearch
 * @param {number=} iPage
 * @param {string=} sUid
 * @returns {Array}
 */
CLinkBuilder.prototype.contacts = function (iType, sGroupId, sSearch, iPage, sUid)
{
	var
		aParams = [Enums.Screens.Contacts]
	;
	
	if (typeof iType === 'number')
	{
		aParams.push(iType);
	}
	
	if (sGroupId && sGroupId !== '')
	{
		aParams.push(sGroupId);
	}
	
	if (sSearch && sSearch !== '')
	{
		aParams.push(sSearch);
	}
	
	if (Utils.isNumeric(iPage))
	{
		aParams.push('p' + iPage);
	}
	
	if (sUid && sUid !== '')
	{
		aParams.push('cnt' + sUid);
	}
	
	return aParams;
};

/**
 * @param {Array} aParam
 * 
 * @return {Object}
 */
CLinkBuilder.prototype.parseContacts = function (aParam)
{
	var
		iIndex = 0,
		aGroupTypes = [Enums.ContactsGroupListType.Personal, Enums.ContactsGroupListType.SharedToAll,
			Enums.ContactsGroupListType.Global, Enums.ContactsGroupListType.All],
		iType = Enums.ContactsGroupListType.All,
		sGroupId = '',
		sSearch = '',
		iPage = 1,
		sUid = ''
	;
	
	if (Utils.isNonEmptyArray(aParam))
	{
		iType = Utils.pInt(aParam[iIndex]);
		iIndex++;
		if (-1 === Utils.inArray(iType, aGroupTypes))
		{
			iType = Enums.ContactsGroupListType.SubGroup;
		}
		if (iType === Enums.ContactsGroupListType.SubGroup)
		{
			if (aParam.length > iIndex)
			{
				sGroupId = Utils.pString(aParam[iIndex]);
				iIndex++;
			}
			else
			{
				iType = Enums.ContactsGroupListType.Personal;
			}
		}
		
		if (aParam.length > iIndex && !this.isPageParam(aParam[iIndex]) && !this.isContactParam(aParam[iIndex]))
		{
			sSearch = Utils.pString(aParam[iIndex]);
			iIndex++;
		}
		
		if (aParam.length > iIndex && this.isPageParam(aParam[iIndex]))
		{
			iPage = Utils.pInt(aParam[iIndex].substr(1));
			iIndex++;
			if (iPage <= 0)
			{
				iPage = 1;
			}
		}
		
		if (aParam.length > iIndex && this.isContactParam(aParam[iIndex]))
		{
			sUid = Utils.pString(aParam[iIndex].substr(3));
			iIndex++;
		}
	}
	
	return {
		'Type': iType,
		'GroupId': sGroupId,
		'Search': sSearch,
		'Page': iPage,
		'Uid': sUid
	};
};

/**
 * @param {string} sTemp
 * 
 * @return {boolean}
 */
CLinkBuilder.prototype.isPageParam = function (sTemp)
{
	return ('p' === sTemp.substr(0, 1) && (/^[1-9][\d]*$/).test(sTemp.substr(1)));
};

/**
 * @param {string} sTemp
 * 
 * @return {boolean}
 */
CLinkBuilder.prototype.isContactParam = function (sTemp)
{
	return ('cnt' === sTemp.substr(0, 3) && (/^[1-9][\d]*$/).test(sTemp.substr(3)));
};

/**
 * @param {string} sTemp
 * 
 * @return {boolean}
 */
CLinkBuilder.prototype.isMsgParam = function (sTemp)
{
	return ('msg' === sTemp.substr(0, 3) && (/^[1-9][\d]*$/).test(sTemp.substr(3)));
};

/**
 * @return {Array}
 */
CLinkBuilder.prototype.compose = function ()
{
	var sScreen = (AppData.SingleMode) ? Enums.Screens.SingleCompose : Enums.Screens.Compose;
	
	return [sScreen];
};

/**
 * @param {string} sType
 * @param {string} sFolder
 * @param {string} sUid
 * @param {boolean} bSingleMode
 * 
 * @return {Array}
 */
CLinkBuilder.prototype.composeFromMessage = function (sType, sFolder, sUid, bSingleMode)
{
	var sScreen = (bSingleMode || AppData.SingleMode) ? Enums.Screens.SingleCompose : Enums.Screens.Compose;
	
	return [sScreen, sType, sFolder, sUid];
};

/**
 * @param {string} sTo
 * 
 * @return {Array}
 */
CLinkBuilder.prototype.composeWithToField = function (sTo)
{
	var sScreen = (AppData.SingleMode) ? Enums.Screens.SingleCompose : Enums.Screens.Compose;
	
	return [sScreen, 'to', sTo];
};

/**
 * @param {?} mToAddr
 * @returns {Object}
 */
CLinkBuilder.prototype.parseToAddr = function (mToAddr)
{
	var
		sToAddr = decodeURI(Utils.pString(mToAddr)),
		bHasMailTo = sToAddr.indexOf('mailto:') !== -1,
		aMailto = [],
		aMessageParts = [],
		sSubject = '',
		sCcAddr = '',
		sBccAddr = '',
		sBody = ''
	;
	
	if (bHasMailTo)
	{
		aMailto = sToAddr.replace(/^mailto:/, '').split('?');
		sToAddr = aMailto[0];
		if (aMailto.length === 2)
		{
			aMessageParts = aMailto[1].split('&');
			_.each(aMessageParts, function (sPart) {
				var
					aParts = sPart.split('=')
				;
				if (aParts.length === 2)
				{
					switch (aParts[0])
					{
						case 'subject': sSubject = aParts[1]; break;
						case 'cc': sCcAddr = aParts[1]; break;
						case 'bcc': sBccAddr = aParts[1]; break;
						case 'body': sBody = aParts[1]; break;
	}
				}
			});
		}
	}
	
	return {
		'to': sToAddr,
		'hasMailto': bHasMailTo,
		'subject': sSubject,
		'cc': sCcAddr,
		'bcc': sBccAddr,
		'body': sBody
	};
};



/**
 * @constructor
 */
function CMessageSender()
{
	this.replyText = ko.observable('');
	this.replyDraftUid = ko.observable('');

	this.postponedMailData = null;
}


/**
 * @param {string} sReplyText
 * @param {string} sDraftUid
 */
CMessageSender.prototype.setReplyData = function (sReplyText, sDraftUid)
{
	this.replyText(sReplyText);
	this.replyDraftUid(sDraftUid);
};

/**
 * @param {string} sAction
 * @param {Object} oParameters
 * @param {boolean} bSaveMailInSentItems
 * @param {boolean} bShowLoading
 * @param {Function} fMessageSendResponseHandler
 * @param {Object} oMessageSendResponseContext
 * @param {boolean=} bPostponedSending = false
 */
CMessageSender.prototype.send = function (sAction, oParameters, bSaveMailInSentItems, bShowLoading,
											fMessageSendResponseHandler, oMessageSendResponseContext, bPostponedSending)
{
	var
		iAccountID = oParameters.AccountID,
		oFolderList = App.MailCache.oFolderListItems[iAccountID],
		sLoadingMessage = '',
		sSentFolder = oFolderList ? oFolderList.sentFolderFullName() : '',
		sDraftFolder = oFolderList ? oFolderList.draftsFolderFullName() : '',
		sCurrEmail = AppData.Accounts.getEmail(iAccountID),
		bSelfRecipient = (oParameters.To.indexOf(sCurrEmail) > -1 || oParameters.Cc.indexOf(sCurrEmail) > -1 || 
			oParameters.Bcc.indexOf(sCurrEmail) > -1),
		oParentApp = (AppData.SingleMode && window.opener && window.opener.App) ? window.opener.App : App
	;
	
	if (AppData.User.SaveRepliedToCurrFolder && !bSelfRecipient && Utils.isNonEmptyArray(oParameters.DraftInfo, 3))
	{
		sSentFolder = oParameters.DraftInfo[2];
	}
	
	oParameters.Action = sAction;
	oParameters.ShowReport = bShowLoading;
	
	switch (sAction)
	{
		case 'MessageSend':
			sLoadingMessage = Utils.i18n('COMPOSE/INFO_SENDING');
			if (bSaveMailInSentItems)
			{
				oParameters.SentFolder = sSentFolder;
			}
			if (oParameters.DraftUid !== '')
			{
				oParameters.DraftFolder = sDraftFolder;
				oParentApp.MailCache.removeOneMessageFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder, oParameters.DraftUid);
				oParentApp.Routing.replaceHashWithoutMessageUid(oParameters.DraftUid);
			}
			break;
		case 'MessageSave':
			sLoadingMessage = Utils.i18n('COMPOSE/INFO_SAVING');
			oParameters.DraftFolder = sDraftFolder;
			App.MailCache.savingDraftUid(oParameters.DraftUid);
			oParentApp.MailCache.startMessagesLoadingWhenDraftSaving(oParameters.AccountID, oParameters.DraftFolder);
			oParentApp.Routing.replaceHashWithoutMessageUid(oParameters.DraftUid);
			break;
	}
	
	if (bShowLoading)
	{
		App.Api.showLoading(sLoadingMessage);
	}
	
	if (bPostponedSending)
	{
		this.postponedMailData = {
			'Parameters': oParameters,
			'MessageSendResponseHandler': fMessageSendResponseHandler,
			'MessageSendResponseContext': oMessageSendResponseContext
		};
	}
	else
	{
		App.Ajax.send(oParameters, fMessageSendResponseHandler, oMessageSendResponseContext);
	}
};

/**
 * @param {string} sDraftUid
 */
CMessageSender.prototype.sendPostponedMail = function (sDraftUid)
{
	var
		oData = this.postponedMailData,
		oParameters = oData.Parameters,
		iAccountID = oParameters.AccountID,
		oFolderList = App.MailCache.oFolderListItems[iAccountID],
		sDraftFolder = oFolderList ? oFolderList.draftsFolderFullName() : '',
		oParentApp = (AppData.SingleMode && window.opener && window.opener.App) ? window.opener.App : App
	;
	
	if (sDraftUid !== '')
	{
		oParameters.DraftUid = sDraftUid;
		oParameters.DraftFolder = sDraftFolder;
		oParentApp.MailCache.removeOneMessageFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder, oParameters.DraftUid);
		oParentApp.Routing.replaceHashWithoutMessageUid(oParameters.DraftUid);
	}
	
	if (this.postponedMailData)
	{
		App.Ajax.send(oParameters, oData.MessageSendResponseHandler, oData.MessageSendResponseContext);
		this.postponedMailData = null;
	}
};

/**
 * @param {string} sAction
 * @param {string} sText
 * @param {string} sDraftUid
 * @param {Function} fMessageSendResponseHandler
 * @param {Object} oMessageSendResponseContext
 * @param {boolean} bRequiresPostponedSending
 */
CMessageSender.prototype.sendReplyMessage = function (sAction, sText, sDraftUid, fMessageSendResponseHandler, 
														oMessageSendResponseContext, bRequiresPostponedSending)
{
	var
		oParameters = null,
		oMessage = App.MailCache.currentMessage(),
		aRecipients = [],
		oFetcherOrIdentity = null
	;

	if (oMessage)
	{
		aRecipients = oMessage.oTo.aCollection.concat(oMessage.oCc.aCollection);
		oFetcherOrIdentity = this.getFirstFetcherOrIdentityByRecipientsOrDefault(aRecipients, oMessage.accountId());

		oParameters = this.getReplyDataFromMessage(oMessage, Enums.ReplyType.ReplyAll, oMessage.accountId(), oFetcherOrIdentity, false, sText, sDraftUid);

		oParameters.AccountID = oMessage.accountId();

		if (oFetcherOrIdentity)
		{
			oParameters.FetcherID = oFetcherOrIdentity && oFetcherOrIdentity.FETCHER ? oFetcherOrIdentity.id() : '';
			oParameters.IdentityID = oFetcherOrIdentity && !oFetcherOrIdentity.FETCHER ? oFetcherOrIdentity.id() : '';
		}

		oParameters.Bcc = '';
		oParameters.Importance = Enums.Importance.Normal;
		oParameters.Sensivity = Enums.Sensivity.Nothing;
		oParameters.ReadingConfirmation = '0';
		oParameters.IsQuickReply = '1';
		oParameters.IsHtml = '1';

		oParameters.Attachments = this.convertAttachmentsForSending(oParameters.Attachments);

		this.send(sAction, oParameters, AppData.User.getSaveMailInSentItems(), false,
			fMessageSendResponseHandler, oMessageSendResponseContext, bRequiresPostponedSending);
	}
};

/**
 * @param {Array} aAttachments
 * 
 * @return {Object}
 */
CMessageSender.prototype.convertAttachmentsForSending = function (aAttachments)
{
	var oAttachments = {};
	
	_.each(aAttachments, function (oAttach) {
		oAttachments[oAttach.tempName()] = [
			oAttach.fileName(),
			oAttach.cid(),
			oAttach.inline() ? '1' : '0',
			oAttach.linked() ? '1' : '0',
			oAttach.contentLocation()
		];
	});
	
	return oAttachments;
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 * @param {boolean} bRequiresPostponedSending
 * 
 * @return {Object}
 */
CMessageSender.prototype.onMessageSendOrSaveResponse = function (oResponse, oRequest, bRequiresPostponedSending)
{
	var
		oParentApp = (AppData.SingleMode && window.opener && window.opener.App) ? window.opener.App : App,
		bResult = !!oResponse.Result,
		sFullName, sUid, sReplyType
	;

	if (!bRequiresPostponedSending)
	{
		App.Api.hideLoading();
	}
	
	switch (oRequest.Action)
	{
		case 'MessageSave':
			if (!bResult)
			{
				if (oRequest.ShowReport)
				{
					App.Api.showErrorByCode(oResponse, Utils.i18n('COMPOSE/ERROR_MESSAGE_SAVING'));
				}
			}
			else
			{
				if (oRequest.ShowReport && !bRequiresPostponedSending)
				{
					App.Api.showReport(Utils.i18n('COMPOSE/REPORT_MESSAGE_SAVED'));
				}

				if (!oResponse.Result.NewUid)
				{
					AppData.User.AllowAutosaveInDrafts = false;
				}
			}
			break;
		case 'MessageSend':
			if (!bResult && oResponse.ErrorCode !== Enums.Errors.NotSavedInSentItems)
			{
				App.Api.showErrorByCode(oResponse, Utils.i18n('COMPOSE/ERROR_MESSAGE_SENDING'));
			}
			else
			{
				if (!bResult && oResponse.ErrorCode === Enums.Errors.NotSavedInSentItems)
				{
					App.Api.showError(Utils.i18n('WARNING/SENT_EMAIL_NOT_SAVED'));
				}
				else if (oRequest.IsQuickReply)
				{
					App.Api.showReport(Utils.i18n('COMPOSE/REPORT_MESSAGE_SENT'));
				}
				else
				{
					oParentApp.Api.showReport(Utils.i18n('COMPOSE/REPORT_MESSAGE_SENT'));
				}

				if (_.isArray(oRequest.DraftInfo) && oRequest.DraftInfo.length === 3)
				{
					sReplyType = oRequest.DraftInfo[0];
					sUid = oRequest.DraftInfo[1];
					sFullName = oRequest.DraftInfo[2];
					App.MailCache.markMessageReplied(oRequest.AccountID, sFullName, sUid, sReplyType);
				}
			}
			
			if (oRequest.SentFolder)
			{
				oParentApp.MailCache.removeMessagesFromCacheForFolder(oRequest.AccountID, oRequest.SentFolder);
			}
			
			break;
	}

	if (oRequest.DraftFolder && !bRequiresPostponedSending)
	{
		oParentApp.MailCache.removeMessagesFromCacheForFolder(oRequest.AccountID, oRequest.DraftFolder);
	}
	
	return {Action: oRequest.Action, Result: bResult, NewUid: oResponse.Result ? oResponse.Result.NewUid : ''};
};

/**
 * @param {Object} oMessage
 * @param {string} sReplyType
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * @param {boolean} bPasteSignatureAnchor
 * @param {string} sText
 * @param {string} sDraftUid
 * 
 * @return {Object}
 */
CMessageSender.prototype.getReplyDataFromMessage = function (oMessage, sReplyType, iAccountId,
													oFetcherOrIdentity, bPasteSignatureAnchor, sText, sDraftUid)
{
	var
		oReplyData = {
			DraftInfo: [],
			DraftUid: '',
			To: '',
			Cc: '',
			Bcc: '',
			Subject: '',
			Attachments: [],
			InReplyTo: oMessage.messageId(),
			References: this.getReplyReferences(oMessage)
		},
		aAttachmentsLink = [],
		sToAddr = oMessage.oReplyTo.getFull(),
		sTo = oMessage.oTo.getFull()
	;
	
	if (sToAddr === '' || oMessage.oFrom.getFirstEmail() === oMessage.oReplyTo.getFirstEmail() && oMessage.oReplyTo.getFirstName() === '')
	{
		sToAddr = oMessage.oFrom.getFull();
	}
	
	if (!sText || sText === '')
	{
		sText = this.replyText();
		this.replyText('');
	}
	
	if (sReplyType === 'forward')
	{
		oReplyData.Text = sText + this.getForwardMessageBody(oMessage, iAccountId, oFetcherOrIdentity);
	}
	else if (sReplyType === 'resend')
	{
		oReplyData.Text = oMessage.getConvertedHtml();
		oReplyData.Cc = oMessage.cc();
		oReplyData.Bcc = oMessage.bcc();
	}
	else
	{
		oReplyData.Text = sText + this._getReplyMessageBody(oMessage, iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor);
	}
	
	if (sDraftUid)
	{
		oReplyData.DraftUid = sDraftUid;
	}
	else
	{
		oReplyData.DraftUid = this.replyDraftUid();
		this.replyDraftUid('');
	}

	switch (sReplyType)
	{
		case Enums.ReplyType.Reply:
			oReplyData.DraftInfo = [Enums.ReplyType.Reply, oMessage.uid(), oMessage.folder()];
			oReplyData.To = sToAddr;
			oReplyData.Subject = this.subjectCompiler(oMessage.subject(), true);
			aAttachmentsLink = _.filter(oMessage.attachments(), function (oAttach) {
				return oAttach.linked();
			});
			break;
		case Enums.ReplyType.ReplyAll:
			oReplyData.DraftInfo = [Enums.ReplyType.ReplyAll, oMessage.uid(), oMessage.folder()];
			oReplyData.To = sToAddr;
			oReplyData.Cc = this._getReplyAllCcAddr(oMessage, iAccountId, oFetcherOrIdentity);
			oReplyData.Subject = this.subjectCompiler(oMessage.subject(), true);
			aAttachmentsLink = _.filter(oMessage.attachments(), function (oAttach) {
				return oAttach.linked();
			});
			break;
		case Enums.ReplyType.Resend:
			oReplyData.DraftInfo = [Enums.ReplyType.Resend, oMessage.uid(), oMessage.folder(), oMessage.cc(), oMessage.bcc()];
			oReplyData.To = sTo;
			oReplyData.Subject = oMessage.subject();
			aAttachmentsLink = oMessage.attachments();
			break;
		case Enums.ReplyType.Forward:
			oReplyData.DraftInfo = [Enums.ReplyType.Forward, oMessage.uid(), oMessage.folder()];
			oReplyData.Subject = this.subjectCompiler(oMessage.subject(), false);
			aAttachmentsLink = oMessage.attachments();
			break;
	}
	
	_.each(aAttachmentsLink, function (oAttachLink) {
		if (oAttachLink.getCopy)
		{
			var
				oCopy = oAttachLink.getCopy(),
				sThumbSessionUid = Date.now().toString()
			;
			oCopy.getInThumbQueue(sThumbSessionUid);
			oReplyData.Attachments.push(oCopy);
		}
	});

	return oReplyData;
};

/**
 * Prepares and returns references for reply message.
 *
 * @param {Object} oMessage
 * 
 * @return {string}
 */
CMessageSender.prototype.getReplyReferences = function (oMessage)
{
	var
		sRef = oMessage.references(),
		sInR = oMessage.messageId(),
		sPos = sRef.indexOf(sInR)
	;

	if (sPos === -1)
	{
		sRef += ' ' + sInR;
	}

	return sRef;
};

/**
 * @param {Object} oMessage
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * @param {boolean} bPasteSignatureAnchor
 * 
 * @return {string}
 */
CMessageSender.prototype._getReplyMessageBody = function (oMessage, iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor)
{
	var
		sReplyTitle = Utils.i18n('COMPOSE/REPLY_MESSAGE_TITLE', {
			'DATE': oMessage.oDateModel.getDate(),
			'TIME': oMessage.oDateModel.getTime(),
			'SENDER': Utils.encodeHtml(oMessage.oFrom.getFull())
		}),
		sReplyBody = '<br /><br />' + this.getSignatureText(iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor) + '<br /><br />' +
			'<div data-anchor="reply-title">' + sReplyTitle + '</div><blockquote>' + oMessage.getConvertedHtml() + '</blockquote>'
	;

	return sReplyBody;
};

/**
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * 
 * @return {string}
 */
CMessageSender.prototype.getClearSignature = function (iAccountId, oFetcherOrIdentity)
{
	var
		oAccount = AppData.Accounts.getAccount(iAccountId),
		bUseSignature = !!(oFetcherOrIdentity ? (oFetcherOrIdentity.useSignature ? oFetcherOrIdentity.useSignature() : oFetcherOrIdentity.signatureOptions()) : true),
		sSignature = ''
	;

	if (oAccount)
	{
		if (bUseSignature)
		{
			if (oFetcherOrIdentity && oFetcherOrIdentity.accountId() === oAccount.id())
			{
				sSignature = oFetcherOrIdentity.signature();
			}
			else
			{
				sSignature = (oAccount.signature() && parseInt(oAccount.signature().options())) ?
					oAccount.signature().signature() : '';
			}
		}
	}

	return sSignature;
};

/**
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * @param {boolean} bPasteSignatureAnchor
 * 
 * @return {string}
 */
CMessageSender.prototype.getSignatureText = function (iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor)
{
	var sSignature = this.getClearSignature(iAccountId, oFetcherOrIdentity);

	if (bPasteSignatureAnchor)
	{
		return '<div data-anchor="signature">' + sSignature + '</div>';
	}

	return '<div>' + sSignature + '</div>';
};

/**
 * @param {Array} aRecipients
 * @param {number} iAccountId
 * 
 * @return Object
 */
CMessageSender.prototype.getFirstFetcherOrIdentityByRecipientsOrDefault = function (aRecipients, iAccountId)
{
	var
		oAccount = AppData.Accounts.getAccount(iAccountId),
		aList = this.getAccountFetchersIdentitiesList(oAccount),
		aEqualEmailList = [],
		oFoundFetcherOrIdentity = null
	;
	
	_.each(aRecipients, function (oAddr) {
		if (!oFoundFetcherOrIdentity)
		{
			aEqualEmailList = _.filter(aList, function (oItem) {
				return oAddr.sEmail === oItem.email;
			});
			
			switch (aEqualEmailList.length)
			{
				case 0:
					break;
				case 1:
					oFoundFetcherOrIdentity = aEqualEmailList[0];
					break;
				default:
					oFoundFetcherOrIdentity = _.find(aEqualEmailList, function (oItem) {
						return oAddr.sEmail === oItem.email && oAddr.sName === oItem.name;
					});
					
					if (!oFoundFetcherOrIdentity)
					{
						oFoundFetcherOrIdentity = _.find(aEqualEmailList, function (oItem) {
							return oItem.default;
						});
						if (!oFoundFetcherOrIdentity)
						{
							oFoundFetcherOrIdentity = aEqualEmailList[0];
						}
					}
					break;
			}
		}
	});
	
	if (!oFoundFetcherOrIdentity)
	{
		oFoundFetcherOrIdentity = _.find(aList, function (oItem) {
			return oItem.default;
		});
	}
	
	return oFoundFetcherOrIdentity && oFoundFetcherOrIdentity.result;
};

/**
 * @param {Object} oAccount
 * @returns {Array}
 */
CMessageSender.prototype.getAccountFetchersIdentitiesList = function (oAccount)
{
	var aList = [];
	
	if (oAccount)
	{
		if (oAccount.fetchers())
		{
			_.each(oAccount.fetchers().collection(), function (oFtch) {
				aList.push({
					'email': oFtch.email(),
					'name': oFtch.userName(),
					'default': false,
					'result': oFtch
				});
			});
		}
		
		_.each(oAccount.identities(), function (oIdnt) {
			aList.push({
				'email': oIdnt.email(),
				'name': oIdnt.friendlyName(),
				'default': oIdnt.isDefault(),
				'result': oIdnt
			});
		});
	}

	return aList;
};

/**
 * @param {Object} oMessage
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * 
 * @return {string}
 */
CMessageSender.prototype.getForwardMessageBody = function (oMessage, iAccountId, oFetcherOrIdentity)
{
	var
		sCcAddr = Utils.encodeHtml(oMessage.oCc.getFull()),
		sCcPart = (sCcAddr !== '') ? Utils.i18n('COMPOSE/FORWARD_MESSAGE_BODY_CC', {'CCADDR': sCcAddr}) : '',
		sForwardTitle = Utils.i18n('COMPOSE/FORWARD_MESSAGE_TITLE', {
			'FROMADDR': Utils.encodeHtml(oMessage.oFrom.getFull()),
			'TOADDR': Utils.encodeHtml(oMessage.oTo.getFull()),
			'CCPART': sCcPart,
			'FULLDATE': oMessage.oDateModel.getFullDate(),
			'SUBJECT': oMessage.subject()
		}),
		sForwardBody = '<br /><br />' + this.getSignatureText(iAccountId, oFetcherOrIdentity, true) + '<br /><br />' + 
			'<div data-anchor="reply-title">' + sForwardTitle + '</div><br /><br />' + oMessage.getConvertedHtml()
	;

	return sForwardBody;
};

/**
 * Prepares and returns cc address for reply message.
 *
 * @param {Object} oMessage
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * 
 * @return {string}
 */
CMessageSender.prototype._getReplyAllCcAddr = function (oMessage, iAccountId, oFetcherOrIdentity)
{
	var
		oAddressList = new CAddressListModel(),
		aAddrCollection = _.union(oMessage.oTo.aCollection, oMessage.oCc.aCollection, 
			oMessage.oBcc.aCollection),
		oCurrAccount = _.find(AppData.Accounts.collection(), function (oAccount) {
			return oAccount.id() === iAccountId;
		}, this),
		oCurrAccAddress = new CAddressModel(),
		oFetcherAddress = new CAddressModel()
	;

	oCurrAccAddress.sEmail = oCurrAccount.email();
	oFetcherAddress.sEmail = oFetcherOrIdentity ? oFetcherOrIdentity.email() : '';
	oAddressList.addCollection(aAddrCollection);
	oAddressList.excludeCollection(_.union(oMessage.oFrom.aCollection, [oCurrAccAddress, oFetcherAddress]));

	return oAddressList.getFull();
};

CMessageSender._subjectPrefixes = _.map(_.uniq(
	[Utils.i18n('COMPOSE/REPLY_PREFIX'), Utils.i18n('COMPOSE/FORWARD_PREFIX'), 'Re', 'Fwd', 'НА', 'HA']
), function (sName) { return sName.toUpperCase(); });

/**
 * @param {string} sSubject
 * @param {boolean} bRe
 *
 * @return {string}
 */
CMessageSender.prototype.subjectCompiler = function (sSubject, bRe)
{
	sSubject = Utils.trim(sSubject.replace(/[\s]+/g, ' '));

	var
		bAddCounts = true,
		sRePrefix = Utils.i18n('COMPOSE/REPLY_PREFIX'),
		sFwdPrefix = Utils.i18n('COMPOSE/FORWARD_PREFIX'),
		sRePrefixUpper = sRePrefix.toUpperCase(),
		sFwdPrefixUpper = sFwdPrefix.toUpperCase(),
		bBreak = false,
		bSubBreak = false,
		oPrefixParts = {},
		aPrefixParts = [],
		aSubjectParts = [],
		aPart = sSubject.split(':')
	;

	oPrefixParts[bRe ? sRePrefix : sFwdPrefix] = 1;

	_.each(aPart, function (sItem) {
		if (bBreak)
		{
			aSubjectParts.push(sItem);
		}
		else
		{
			sItem = Utils.trim(sItem);
			var sItemUpper = sItem.toUpperCase();
			
			if (sItem && -1 < _.indexOf(CMessageSender._subjectPrefixes, sItemUpper))
			{
				sItem = sRePrefixUpper === sItemUpper ? sRePrefix : sItem;
				sItem = sFwdPrefixUpper === sItemUpper ? sFwdPrefix : sItem;

				oPrefixParts[sItem] = Utils.isUnd(oPrefixParts[sItem]) ? 1 : 0 + oPrefixParts[sItem] + 1;
			}
			else
			{
				bSubBreak = false;
				if (sItem && !bBreak)
				{
					_.each(CMessageSender._subjectPrefixes, function (sSubjectPrefixes) {
						if (!bSubBreak)
						{
							var 
								sStrippedItem = '',
								oMatch = (new window.RegExp('^' + sSubjectPrefixes + '\\s?[\\[\\(]([\\d]+)[\\]\\)]$', 'gi')).exec(sItemUpper)
							;

							if (oMatch && oMatch[1])
							{
								sStrippedItem = Utils.trim(sItem.replace(/[\s]*[\[\(][\d]+[\]\)]$/g, ''));
								bSubBreak = true;

								sStrippedItem = sRePrefixUpper === sItemUpper ? sRePrefix : sStrippedItem;
								sStrippedItem = sFwdPrefixUpper === sItemUpper ? sFwdPrefix : sStrippedItem;
								
								oPrefixParts[sStrippedItem] = Utils.isUnd(oPrefixParts[sStrippedItem]) ? Utils.pInt(oMatch[1]) :
									0 + oPrefixParts[sStrippedItem] + Utils.pInt(oMatch[1]);
							}
						}
					});
				}

				if (!bSubBreak)
				{
					bBreak = true;
					aSubjectParts.push(sItem);
				}
			}
		}
	});

	_.each(oPrefixParts, function (iCount, sPrefix) {
		if (iCount)
		{
			aPrefixParts.push(sPrefix + (bAddCounts && 1 < iCount ? '[' + iCount + ']' : ''));
		}
	});

	return Utils.trim((aPrefixParts.join(': ') + (aPrefixParts.length ? ': ' : '') + aSubjectParts.join(':')).replace(/[\s]+/g, ' '));
};

/**
 * @param {string} sPlain
 * 
 * @return {string}
 */
CMessageSender.prototype.getHtmlFromText = function (sPlain)
{
	return sPlain
		.replace(/&/g, '&amp;').replace(/>/g, '&gt;').replace(/</g, '&lt;')
		.replace(/\r/g, '').replace(/\n/g, '<br />')
	;
};

/*CMessageSender.prototype.isFetcherOrIdentitySameAsChiefAccount = function (iAccountId, oMessage)
{
	var
		oAccount = AppData.Accounts.getAccount(iAccountId || AppData.Accounts.currentId()),
		oAccountEmail = oAccount.email(),
		oAccountFriendlyName = oAccount.friendlyName(),
		aFetchersAndIdentities = []
	;

	if (oAccount.identities())
	{
		//aFetchersAndIdentities = oAccount.identities();
		_.each(oAccount.identities(), function (oIdentity) {
			if (!oIdentity.loyal())
			{
				aFetchersAndIdentities.unshift(oIdentity);
			}
		}, this);
	}
	if (oAccount.fetchers())
	{
		aFetchersAndIdentities = aFetchersAndIdentities.concat(oAccount.fetchers().collection());
	}

	return _.any(aFetchersAndIdentities, function (oAddr) {
		return oAddr.email() === oAccountEmail && (oAddr.friendlyName ? oAddr.friendlyName() === oAccountFriendlyName : oAddr.userName() === oAccountFriendlyName);
	});
};*/


/**
 * @constructor
 */
function CPrefetcher()
{
	this.prefetchStarted = ko.observable(false);
	this.serverInitializationsDone = ko.observable(false);
	this.helpdeskInitialized = ko.observable(false);
	this.fetchersIdentitiesPrefetched = ko.observable(false);
	
	this.init();
}

CPrefetcher.prototype.init = function ()
{
	setInterval(_.bind(function () {
		this.start();
	}, this), 10000);
};

CPrefetcher.prototype.start = function ()
{
	if (AppData.Auth && !AppData.SingleMode && !App.InternetConnectionError && !App.Ajax.hasOpenedRequests())
	{
		this.prefetchStarted(false);
		this.prefetchAll();
	}
};

CPrefetcher.prototype.prefetchAll = function ()
{
	this.prefetchFetchersIdentities();
	
	if (AppData.App.AllowPrefetch)
	{
		this.startMessagesPrefetch();

		this.startThreadListPrefetch();
		
		this.doServerInitializations();
		
		this.prefetchStarredMessageList();
		
		this.startOtherPagesPrefetch();

		this.prefetchUnseenMessageList();

		this.prefetchAccountQuota();

		this.startOtherFoldersPrefetch();

		this.prefetchCalendarList();

		this.initHelpdesk();
	}
	else
	{
		this.doServerInitializations();
		
		this.prefetchStarredMessageList();

		this.prefetchAccountQuota();

		this.prefetchCalendarList();

		this.initHelpdesk();
	}
};

CPrefetcher.prototype.prefetchCalendarList = function ()
{
	if (!this.prefetchStarted())
	{
		this.prefetchStarted(App.CalendarCache.firstRequestCalendarList());
	}
};

CPrefetcher.prototype.prefetchFetchersIdentities = function ()
{
	if (!AppData.SingleMode && !this.fetchersIdentitiesPrefetched() && !this.prefetchStarted() && (AppData.User.AllowFetcher || AppData.AllowIdentities))
	{
		AppData.Accounts.populateFetchersIdentities();
		this.fetchersIdentitiesPrefetched(true);
		this.prefetchStarted(true);
	}	
};

CPrefetcher.prototype.initHelpdesk = function ()
{
	if (AppData.User.IsHelpdeskSupported && !this.prefetchStarted() && !this.helpdeskInitialized())
	{
		App.Screens.initHelpdesk();
		this.helpdeskInitialized(true);
		this.prefetchStarted(true);
	}
};

CPrefetcher.prototype.doServerInitializations = function ()
{
	if (!AppData.SingleMode && !this.prefetchStarted() && !this.serverInitializationsDone())
	{
		App.Ajax.send({'Action': 'SystemDoServerInitializations'});
		this.serverInitializationsDone(true);
		this.prefetchStarted(true);
	}
};

CPrefetcher.prototype.prefetchStarredMessageList = function ()
{
	if (!this.prefetchStarted())
	{
		var
			oFolderList = App.MailCache.folderList(),
			oInbox = oFolderList ? oFolderList.inboxFolder() : null,
			oRes = null
		;
		
		if (oInbox && !oInbox.hasChanges())
		{
			oRes = App.MailCache.requestMessageList(oInbox.fullName(), 1, '', Enums.FolderFilter.Flagged, false, false);
			if (oRes.RequestStarted)
			{
				this.prefetchStarted(true);
			}
		}
	}
};

CPrefetcher.prototype.prefetchUnseenMessageList = function ()
{
	if (!this.prefetchStarted())
	{
		var
			oFolderList = App.MailCache.folderList(),
			oInbox = oFolderList ? oFolderList.inboxFolder() : null,
			oRes = null
		;
		
		if (oInbox && !oInbox.hasChanges())
		{
			oRes = App.MailCache.requestMessageList(oInbox.fullName(), 1, '', Enums.FolderFilter.Unseen, false, false);
			if (oRes.RequestStarted)
			{
				this.prefetchStarted(true);
			}
		}
	}
};

CPrefetcher.prototype.startOtherPagesPrefetch = function ()
{
	if (!this.prefetchStarted())
	{
		this.startPagePrefetch(App.MailCache.page() + 1);
	}
	
	if (!this.prefetchStarted())
	{
		this.startPagePrefetch(App.MailCache.page() - 1);
	}
};

/**
 * @param {string} sCurrentUid
 */
CPrefetcher.prototype.prefetchNextPage = function (sCurrentUid)
{
	var
		oUidList = App.MailCache.uidList(),
		iIndex = _.indexOf(oUidList.collection(), sCurrentUid),
		iPage = Math.ceil(iIndex/AppData.User.MailsPerPage) + 1
	;
	this.startPagePrefetch(iPage - 1);
};

/**
 * @param {string} sCurrentUid
 */
CPrefetcher.prototype.prefetchPrevPage = function (sCurrentUid)
{
	var
		oUidList = App.MailCache.uidList(),
		iIndex = _.indexOf(oUidList.collection(), sCurrentUid),
		iPage = Math.ceil((iIndex + 1)/AppData.User.MailsPerPage) + 1
	;
	this.startPagePrefetch(iPage);
};

/**
 * @param {number} iPage
 */
CPrefetcher.prototype.startPagePrefetch = function (iPage)
{
	var
		oCurrFolder = App.MailCache.folderList().currentFolder(),
		oUidList = App.MailCache.uidList(),
		iOffset = (iPage - 1) * AppData.User.MailsPerPage,
		bPageExists = iPage > 0 && iOffset < oUidList.resultCount(),
		oParams = null,
		oRequestData = null
	;
	
	if (oCurrFolder && !oCurrFolder.hasChanges() && bPageExists)
	{
		oParams = {
			folder: oCurrFolder.fullName(),
			page: iPage,
			search: oUidList.search()
		};
		
		if (!oCurrFolder.hasListBeenRequested(oParams))
		{
			oRequestData = App.MailCache.requestMessageList(oParams.folder, oParams.page, oParams.search, '', false, false);

			if (oRequestData && oRequestData.RequestStarted)
			{
				this.prefetchStarted(true);
			}
		}
	}
};

CPrefetcher.prototype.startOtherFoldersPrefetch = function ()
{
	if (!this.prefetchStarted())
	{
		var
			oFolderList = App.MailCache.folderList(),
			sCurrFolder = oFolderList.currentFolderFullName(),
			aFoldersFromAccount = AppData.Accounts.getCurrentFetchersAndFiltersFolderNames(),
			aSystemFolders = oFolderList ? [oFolderList.inboxFolderFullName(), oFolderList.sentFolderFullName(), oFolderList.draftsFolderFullName(), oFolderList.spamFolderFullName()] : [],
			aOtherFolders = (aFoldersFromAccount.length < 3) ? this.getOtherFolderNames(3 - aFoldersFromAccount.length) : [],
			aFolders = _.uniq(_.compact(_.union(aSystemFolders, aFoldersFromAccount, aOtherFolders)))
		;
		
		_.each(aFolders, _.bind(function (sFolder) {
			if (sCurrFolder !== sFolder)
			{
				this.startFolderPrefetch(oFolderList.getFolderByFullName(sFolder));
			}
		}, this));
	}
};

/**
 * @param {number} iCount
 * @returns {Array}
 */
CPrefetcher.prototype.getOtherFolderNames = function (iCount)
{
	var
		oInbox = App.MailCache.folderList().inboxFolder(),
		aInboxSubFolders = oInbox ? oInbox.subfolders() : [],
		aOtherFolders = _.filter(App.MailCache.folderList().collection(), function (oFolder) {
			return !oFolder.isSystem();
		}, this),
		aFolders = _.first(_.union(aInboxSubFolders, aOtherFolders), iCount)
	;
	
	return _.map(aFolders, function (oFolder) {
		return oFolder.fullName();
	});
};

/**
 * @param {Object} oFolder
 */
CPrefetcher.prototype.startFolderPrefetch = function (oFolder)
{
	if (!this.prefetchStarted() && oFolder)
	{
		var
			iPage = 1,
			sSearch = '',
			oParams = {
				folder: oFolder.fullName(),
				page: iPage,
				search: sSearch
			},
			oRequestData = null
		;

		if (!oFolder.hasListBeenRequested(oParams))
		{
			oRequestData = App.MailCache.requestMessageList(oParams.folder, oParams.page, oParams.search, '', false, false);

			if (oRequestData && oRequestData.RequestStarted)
			{
				this.prefetchStarted(true);
			}
		}
	}
};

CPrefetcher.prototype.startThreadListPrefetch = function ()
{
	if (!this.prefetchStarted())
	{
		var
			aUidsForLoad = [],
			oCurrFolder = App.MailCache.getCurrentFolder()
		;

		_.each(App.MailCache.messages(), function (oCacheMess) {
			if (oCacheMess.threadCount() > 0)
			{
				_.each(oCacheMess.threadUids(), function (sThreadUid) {
					var oThreadMess = oCurrFolder.oMessages[sThreadUid];
					if (!oThreadMess || !oCurrFolder.hasThreadUidBeenRequested(sThreadUid))
					{
						aUidsForLoad.push(sThreadUid);
					}
				});
			}
		}, this);

		if (aUidsForLoad.length > 0)
		{
			aUidsForLoad = aUidsForLoad.slice(0, AppData.User.MailsPerPage);
			oCurrFolder.addRequestedThreadUids(aUidsForLoad);
			oCurrFolder.loadThreadMessages(aUidsForLoad);
			this.prefetchStarted(true);
		}
	}
};

CPrefetcher.prototype.startMessagesPrefetch = function ()
{
	if (!this.prefetchStarted())
	{
		var
			iAccountId = App.MailCache.currentAccountId(),
			oCurrFolder = App.MailCache.getCurrentFolder(),
			iTotalSize = 0,
			iMaxSize = AppData.App.MaxPrefetchBodiesSize,
			aUids = [],
			oParameters = null,
			iJsonSizeOf1Message = 2048,
			fFillUids = function (oMsg) {
				var
					bNotFilled = (!oMsg.deleted() && !oMsg.completelyFilled()),
					bUidNotAdded = !_.find(aUids, function (sUid) {
						return sUid === oMsg.uid();
					}, this),
					bHasNotBeenRequested = !oCurrFolder.hasUidBeenRequested(oMsg.uid())
				;

				if (iTotalSize < iMaxSize && bNotFilled && bUidNotAdded && bHasNotBeenRequested)
				{
					aUids.push(oMsg.uid());
					iTotalSize += oMsg.trimmedTextSize() + iJsonSizeOf1Message;
				}
			}
		;

		if (oCurrFolder && oCurrFolder.selected())
		{
			_.each(App.MailCache.messages(), fFillUids);
			_.each(oCurrFolder.oMessages, fFillUids);

			if (aUids.length > 0)
			{
				oCurrFolder.addRequestedUids(aUids);

				oParameters = {
					'AccountID': iAccountId,
					'Action': 'MessagesGetBodies',
					'Folder': oCurrFolder.fullName(),
					'Uids': aUids
				};

				App.Ajax.send(oParameters, this.onMessagesGetBodiesResponse, this);
				this.prefetchStarted(true);
			}
		}
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CPrefetcher.prototype.onMessagesGetBodiesResponse = function (oData, oParameters)
{
	var
		oFolder = App.MailCache.getFolderByFullName(oParameters.AccountID, oParameters.Folder)
	;
	
	if (_.isArray(oData.Result))
	{
		_.each(oData.Result, function (oRawMessage) {
			oFolder.parseAndCacheMessage(oRawMessage, false, false);
		});
	}
};

CPrefetcher.prototype.prefetchAccountQuota = function ()
{
	var
		oAccount = AppData.Accounts.getCurrent(),
		bShowQuotaBar = AppData.App && AppData.App.ShowQuotaBar,
		bNeedQuotaRequest = oAccount && !oAccount.quotaRecieved()
	;
	
	if (!this.prefetchStarted() && bShowQuotaBar && bNeedQuotaRequest)
	{
		oAccount.updateQuotaParams();
		this.prefetchStarted(true);
	}
};

/**
 * @param {Function} list (knockout)
 * @param {Function=} fSelectCallback
 * @param {Function=} fDeleteCallback
 * @param {Function=} fDblClickCallback
 * @param {Function=} fEnterCallback
 * @param {Function=} multiplyLineFactor (knockout)
 * @param {boolean=} bResetCheckedOnClick = false
 * @param {boolean=} bCheckOnSelect = false
 * @param {boolean=} bUnselectOnCtrl = false
 * @param {boolean=} bDisableMultiplySelection = false
 * @constructor
 */
function CSelector(list, fSelectCallback, fDeleteCallback, fDblClickCallback, fEnterCallback, multiplyLineFactor,
	bResetCheckedOnClick, bCheckOnSelect, bUnselectOnCtrl, bDisableMultiplySelection)
{
	this.fBeforeSelectCallback = null;
	this.fSelectCallback = fSelectCallback || function() {};
	this.fDeleteCallback = fDeleteCallback || function() {};
	this.fDblClickCallback = (!bMobileApp && fDblClickCallback) ? fDblClickCallback : function() {};
	this.fEnterCallback = fEnterCallback || function() {};
	this.bResetCheckedOnClick = Utils.isUnd(bResetCheckedOnClick) ? false : !!bResetCheckedOnClick;
	this.bCheckOnSelect = Utils.isUnd(bCheckOnSelect) ? false : !!bCheckOnSelect;
	this.bUnselectOnCtrl = Utils.isUnd(bUnselectOnCtrl) ? false : !!bUnselectOnCtrl;
	this.bDisableMultiplySelection = Utils.isUnd(bDisableMultiplySelection) ? false : !!bDisableMultiplySelection;

	this.useKeyboardKeys = ko.observable(false);

	this.list = ko.observableArray([]);

	if (list && list['subscribe'])
	{
		list['subscribe'](function (mValue) {
			this.list(mValue);
		}, this);
	}
	
	this.multiplyLineFactor = multiplyLineFactor;
	
	this.oLast = null;
	this.oListScope = null;
	this.oScrollScope = null;

	this.iTimer = 0;
	this.iFactor = 1;

	this.KeyUp = Enums.Key.Up;
	this.KeyDown = Enums.Key.Down;
	this.KeyLeft = Enums.Key.Up;
	this.KeyRight = Enums.Key.Down;

	if (this.multiplyLineFactor)
	{
		if (this.multiplyLineFactor.subscribe)
		{
			this.multiplyLineFactor.subscribe(function (iValue) {
				this.iFactor = 0 < iValue ? iValue : 1;
			}, this);
		}
		else
		{
			this.iFactor = Utils.pInt(this.multiplyLineFactor);
		}

		this.KeyUp = Enums.Key.Up;
		this.KeyDown = Enums.Key.Down;
		this.KeyLeft = Enums.Key.Left;
		this.KeyRight = Enums.Key.Right;

		if ($('html').hasClass('rtl'))
		{
			this.KeyLeft = Enums.Key.Right;
			this.KeyRight = Enums.Key.Left;
		}
	}

	this.sActionSelector = '';
	this.sSelectabelSelector = '';
	this.sCheckboxSelector = '';

	var self = this;

	// reading returns a list of checked items.
	// recording (bool) puts all checked, or unchecked.
	this.listChecked = ko.computed({
		'read': function () {
			var aList = _.filter(this.list(), function (oItem) {
				var
					bC = oItem.checked(),
					bS = oItem.selected()
				;

				return bC || (self.bCheckOnSelect && bS);
			});

			return aList;
		},
		'write': function (bValue) {
			bValue = !!bValue;
			_.each(this.list(), function (oItem) {
				oItem.checked(bValue);
			});
			this.list.valueHasMutated();
		},
		'owner': this
	});

	this.checkAll = ko.computed({
		'read': function () {
			return 0 < this.listChecked().length;
		},

		'write': function (bValue) {
			this.listChecked(!!bValue);
		},
		'owner': this
	});

	this.selectorHook = ko.observable(null);

	this.selectorHook.subscribe(function () {
		var oPrev = this.selectorHook();
		if (oPrev)
		{
			oPrev.selected(false);
		}
	}, this, 'beforeChange');

	this.selectorHook.subscribe(function (oGroup) {
		if (oGroup)
		{
			oGroup.selected(true);
		}
	}, this);

	this.itemSelected = ko.computed({

		'read': this.selectorHook,

		'write': function (oItemToSelect) {

			this.selectorHook(oItemToSelect);

			if (oItemToSelect)
			{
//				self.scrollToSelected();
				this.oLast = oItemToSelect;
			}
		},
		'owner': this
	});

	this.list.subscribe(function (aList) {
		if (_.isArray(aList))
		{
			var	oSelected = this.itemSelected();
			if (oSelected)
			{
				if (!_.find(aList, function (oItem) {
					return oSelected === oItem;
				}))
				{
					this.itemSelected(null);
				}
			}
		}
		else
		{
			this.itemSelected(null);
		}
	}, this);

	this.listCheckedOrSelected = ko.computed({
		'read': function () {
			var
				oSelected = this.itemSelected(),
				aChecked = this.listChecked()
			;
			return 0 < aChecked.length ? aChecked : (oSelected ? [oSelected] : []);
		},
		'write': function (bValue) {
			if (!bValue)
			{
				this.itemSelected(null);
				this.listChecked(false);
			}
			else
			{
				this.listChecked(true);
			}
		},
		'owner': this
	});

	this.listCheckedAndSelected = ko.computed({
		'read': function () {
			var
				aResult = [],
				oSelected = this.itemSelected(),
				aChecked = this.listChecked()
			;

			if (aChecked)
			{
				aResult = aChecked.slice(0);
			}

			if (oSelected && _.indexOf(aChecked, oSelected) === -1)
			{
				aResult.push(oSelected);
			}

			return aResult;
		},
		'write': function (bValue) {
			if (!bValue)
			{
				this.itemSelected(null);
				this.listChecked(false);
			}
			else
			{
				this.listChecked(true);
			}
		},
		'owner': this
	});

	this.isIncompleteChecked = ko.computed(function () {
		var
			iM = this.list().length,
			iC = this.listChecked().length
		;
		return 0 < iM && 0 < iC && iM > iC;
	}, this);

	this.onKeydownBinded = _.bind(this.onKeydown, this);
}

CSelector.prototype.iTimer = 0;
CSelector.prototype.bResetCheckedOnClick = false;
CSelector.prototype.bCheckOnSelect = false;
CSelector.prototype.bUnselectOnCtrl = false;
CSelector.prototype.bDisableMultiplySelection = false;

/**
 * @param {Function} fBeforeSelectCallback
 */
CSelector.prototype.setBeforeSelectCallback = function (fBeforeSelectCallback)
{
	this.fBeforeSelectCallback = fBeforeSelectCallback || null;
};

CSelector.prototype.getLastOrSelected = function ()
{
	var
		iCheckedCount = 0,
		oLastSelected = null
	;
	
	_.each(this.list(), function (oItem) {
		if (oItem.checked())
		{
			iCheckedCount++;
		}

		if (oItem.selected())
		{
			oLastSelected = oItem;
		}
	});

	return 0 === iCheckedCount && oLastSelected ? oLastSelected : this.oLast;
};

/**
 * @return {boolean}
 */
/*CSelector.prototype.inFocus = function ()
{
	var mTagName = document && document.activeElement ? document.activeElement.tagName : null;
	return 'INPUT' === mTagName || 'TEXTAREA' === mTagName || 'IFRAME' === mTagName;
};*/

/**
 * @param {string} sActionSelector css-selector for the active for pressing regions of the list
 * @param {string} sSelectabelSelector css-selector to the item that was selected
 * @param {string} sCheckboxSelector css-selector to the element that checkbox in the list
 * @param {*} oListScope
 * @param {*} oScrollScope
 */
CSelector.prototype.initOnApplyBindings = function (sActionSelector, sSelectabelSelector, sCheckboxSelector, oListScope, oScrollScope)
{
	$(document).on('keydown', this.onKeydownBinded);

	this.oListScope = oListScope;
	this.oScrollScope = oScrollScope;
	this.sActionSelector = sActionSelector;
	this.sSelectabelSelector = sSelectabelSelector;
	this.sCheckboxSelector = sCheckboxSelector;

	var
		self = this,

		fEventClickFunction = function (oLast, oItem, oEvent) {

			var
				iIndex = 0,
				iLength = 0,
				oListItem = null,
				bChangeRange = false,
				bIsInRange = false,
				aList = [],
				bChecked = false
			;

			oItem = oItem ? oItem : null;
			if (oEvent && oEvent.shiftKey)
			{
				if (null !== oItem && null !== oLast && oItem !== oLast)
				{
					aList = self.list();
					bChecked = oItem.checked();

					for (iIndex = 0, iLength = aList.length; iIndex < iLength; iIndex++)
					{
						oListItem = aList[iIndex];

						bChangeRange = false;
						if (oListItem === oLast || oListItem === oItem)
						{
							bChangeRange = true;
						}

						if (bChangeRange)
						{
							bIsInRange = !bIsInRange;
						}

						if (bIsInRange || bChangeRange)
						{
							oListItem.checked(bChecked);
						}
					}
				}
			}

			if (oItem)
			{
				self.oLast = oItem;
			}
		}
	;

	$(this.oListScope).on('dblclick', sActionSelector, function (oEvent) {
		var oItem = ko.dataFor(this);
		if (oItem && oEvent && !oEvent.ctrlKey && !oEvent.altKey && !oEvent.shiftKey)
		{
			self.onDblClick(oItem);
		}
	});

	if (bMobileDevice)
	{
		$(this.oListScope).on('touchstart', sActionSelector, function (e) {

			if (!e)
			{
				return;
			}

			var
				t2 = e.timeStamp,
				t1 = $(this).data('lastTouch') || t2,
				dt = t2 - t1,
				fingers = e.originalEvent && e.originalEvent.touches ? e.originalEvent.touches.length : 0
			;

			$(this).data('lastTouch', t2);
			if (!dt || dt > 250 || fingers > 1)
			{
				return;
			}

			e.preventDefault();
			$(this).trigger('dblclick');
		});
	}

	$(this.oListScope).on('click', sActionSelector, function (oEvent) {

		var
			bClick = true,
			oSelected = null,
			oLast = self.getLastOrSelected(),
			oItem = ko.dataFor(this)
		;

		if (oItem && oEvent)
		{
			if (oEvent.shiftKey)
			{
				bClick = false;
				if (!self.bDisableMultiplySelection)
				{
					if (null === self.oLast)
					{
						self.oLast = oItem;
					}


					oItem.checked(!oItem.checked());
					fEventClickFunction(oLast, oItem, oEvent);
				}
			}
			else if (oEvent.ctrlKey)
			{
				bClick = false;
				if (!self.bDisableMultiplySelection)
				{
					self.oLast = oItem;
					oSelected = self.itemSelected();
					if (oSelected && !oSelected.checked() && !oItem.checked())
					{
						oSelected.checked(true);
					}

					if (self.bUnselectOnCtrl && oItem === self.itemSelected())
					{
						oItem.checked(!oItem.selected());
						self.itemSelected(null);
					}
					else
					{
						oItem.checked(!oItem.checked());
					}
				}
			}

			if (bClick)
			{
				self.onSelect(oItem);
				self.scrollToSelected();
			}
		}
	});

	$(this.oListScope).on('click', sCheckboxSelector, function (oEvent) {

		var oItem = ko.dataFor(this);
		if (oItem && oEvent && !self.bDisableMultiplySelection)
		{
			if (oEvent.shiftKey)
			{
				if (null === self.oLast)
				{
					self.oLast = oItem;
				}

				fEventClickFunction(self.getLastOrSelected(), oItem, oEvent);
			}
			else
			{
				self.oLast = oItem;
			}
		}

		if (oEvent && oEvent.stopPropagation)
		{
			oEvent.stopPropagation();
		}
	});

	$(this.oListScope).on('dblclick', sCheckboxSelector, function (oEvent) {
		if (oEvent && oEvent.stopPropagation)
		{
			oEvent.stopPropagation();
		}
	});
};

/**
 * @param {Object} oSelected
 * @param {number} iEventKeyCode
 * 
 * @return {Object}
 */
CSelector.prototype.getResultSelection = function (oSelected, iEventKeyCode)
{
	var
		self = this,
		bStop = false,
		bNext = false,
		oResult = null,
		iPageStep = this.iFactor,
		bMultiply = !!this.multiplyLineFactor,
		iIndex = 0,
		iLen = 0,
		aList = []
	;

	if (!oSelected && -1 < Utils.inArray(iEventKeyCode, [this.KeyUp, this.KeyDown, this.KeyLeft, this.KeyRight,
		Enums.Key.PageUp, Enums.Key.PageDown, Enums.Key.Home, Enums.Key.End]))
	{
		aList = this.list();
		if (aList && 0 < aList.length)
		{
			if (-1 < Utils.inArray(iEventKeyCode, [this.KeyDown, this.KeyRight, Enums.Key.PageUp, Enums.Key.Home]))
			{
				oResult = aList[0];
			}
			else if (-1 < Utils.inArray(iEventKeyCode, [this.KeyUp, this.KeyLeft, Enums.Key.PageDown, Enums.Key.End]))
			{
				oResult = aList[aList.length - 1];
			}
		}
	}
	else if (oSelected)
	{
		aList = this.list();
		iLen = aList ? aList.length : 0;

		if (0 < iLen)
		{
			if (
				Enums.Key.Home === iEventKeyCode || Enums.Key.PageUp === iEventKeyCode ||
				Enums.Key.End === iEventKeyCode || Enums.Key.PageDown === iEventKeyCode ||
				(bMultiply && (Enums.Key.Left === iEventKeyCode || Enums.Key.Right === iEventKeyCode)) ||
				(!bMultiply && (Enums.Key.Up === iEventKeyCode || Enums.Key.Down === iEventKeyCode))
			)
			{
				_.each(aList, function (oItem) {
					if (!bStop)
					{
						switch (iEventKeyCode) {
							case self.KeyUp:
							case self.KeyLeft:
								if (oSelected === oItem)
								{
									bStop = true;
								}
								else
								{
									oResult = oItem;
								}
								break;
							case Enums.Key.Home:
							case Enums.Key.PageUp:
								oResult = oItem;
								bStop = true;
								break;
							case self.KeyDown:
							case self.KeyRight:
								if (bNext)
								{
									oResult = oItem;
									bStop = true;
								}
								else if (oSelected === oItem)
								{
									bNext = true;
								}
								break;
							case Enums.Key.End:
							case Enums.Key.PageDown:
								oResult = oItem;
								break;
						}
					}
				});
			}
			else if (bMultiply && this.KeyDown === iEventKeyCode)
			{
				for (; iIndex < iLen; iIndex++)
				{
					if (oSelected === aList[iIndex])
					{
						iIndex += iPageStep;
						if (iLen - 1 < iIndex)
						{
							iIndex -= iPageStep;
						}

						oResult = aList[iIndex];
						break;
					}
				}
			}
			else if (bMultiply && this.KeyUp === iEventKeyCode)
			{
				for (iIndex = iLen; iIndex >= 0; iIndex--)
				{
					if (oSelected === aList[iIndex])
					{
						iIndex -= iPageStep;
						if (0 > iIndex)
						{
							iIndex += iPageStep;
						}

						oResult = aList[iIndex];
						break;
					}
				}
			}
		}
	}

	return oResult;
};

/**
 * @param {Object} oResult
 * @param {Object} oSelected
 * @param {number} iEventKeyCode
 */
CSelector.prototype.shiftClickResult = function (oResult, oSelected, iEventKeyCode)
{
	if (oSelected)
	{
		var
			bMultiply = !!this.multiplyLineFactor,
			bInRange = false,
			bSelected = false
		;

		if (-1 < Utils.inArray(iEventKeyCode,
			bMultiply ? [Enums.Key.Left, Enums.Key.Right] : [Enums.Key.Up, Enums.Key.Down]))
		{
			oSelected.checked(!oSelected.checked());
		}
		else if (-1 < Utils.inArray(iEventKeyCode, bMultiply ?
			[Enums.Key.Up, Enums.Key.Down, Enums.Key.PageUp, Enums.Key.PageDown, Enums.Key.Home, Enums.Key.End] :
			[Enums.Key.Left, Enums.Key.Right, Enums.Key.PageUp, Enums.Key.PageDown, Enums.Key.Home, Enums.Key.End]
		))
		{
			bSelected = !oSelected.checked();

			_.each(this.list(), function (oItem) {
				var Add = false;
				if (oItem === oResult || oSelected === oItem)
				{
					bInRange = !bInRange;
					Add = true;
				}

				if (bInRange || Add)
				{
					oItem.checked(bSelected);
					Add = false;
				}
			});
			
			if (bMultiply && oResult && (iEventKeyCode === Enums.Key.Up || iEventKeyCode === Enums.Key.Down))
			{
				oResult.checked(!oResult.checked());
			}
		}
	}	
};

/**
 * @param {number} iEventKeyCode
 * @param {boolean} bShiftKey
 */
CSelector.prototype.clickNewSelectPosition = function (iEventKeyCode, bShiftKey)
{
	var
		self = this,
		iTimeout = 0,
		oResult = null,
		oSelected = this.itemSelected()
	;

	oResult = this.getResultSelection(oSelected, iEventKeyCode);

	if (oResult)
	{
		if (bShiftKey)
		{
			this.shiftClickResult(oResult, oSelected, iEventKeyCode);
		}

		if (oResult && this.fBeforeSelectCallback)
		{
			this.fBeforeSelectCallback(oResult, function (bResult) {
				if (bResult)
				{
					self.itemSelected(oResult);

					iTimeout = 0 === self.iTimer ? 50 : 150;
					if (0 !== self.iTimer)
					{
						window.clearTimeout(self.iTimer);
					}

					self.iTimer = window.setTimeout(function () {
						self.iTimer = 0;
						self.onSelect(oResult, false);
					}, iTimeout);

					this.scrollToSelected();
				}
			});

			this.scrollToSelected();
		}
		else
		{
			this.itemSelected(oResult);

			iTimeout = 0 === this.iTimer ? 50 : 150;
			if (0 !== this.iTimer)
			{
				window.clearTimeout(this.iTimer);
			}

			this.iTimer = window.setTimeout(function () {
				self.iTimer = 0;
				self.onSelect(oResult);
			}, iTimeout);

			this.scrollToSelected();
		}
	}
	else if (oSelected)
	{
		if (bShiftKey && (-1 < Utils.inArray(iEventKeyCode, [this.KeyUp, this.KeyDown, this.KeyLeft, this.KeyRight,
			Enums.Key.PageUp, Enums.Key.PageDown, Enums.Key.Home, Enums.Key.End])))
		{
			oSelected.checked(!oSelected.checked());
		}
	}
};

/**
 * @param {Object} oEvent
 * 
 * @return {boolean}
 */
CSelector.prototype.onKeydown = function (oEvent)
{
	var
		bResult = true,
		iCode = 0
	;

	if (this.useKeyboardKeys() && oEvent && !Utils.isTextFieldFocused() && !App.Screens.hasOpenedMaximizedPopups())
	{
		iCode = oEvent.keyCode;
		if (!oEvent.ctrlKey &&
			(
				this.KeyUp === iCode || this.KeyDown === iCode ||
				this.KeyLeft === iCode || this.KeyRight === iCode ||
				Enums.Key.PageUp === iCode || Enums.Key.PageDown === iCode ||
				Enums.Key.Home === iCode || Enums.Key.End === iCode
			)
		)
		{
			this.clickNewSelectPosition(iCode, oEvent.shiftKey);
			bResult = false;
		}
		else if (Enums.Key.Del === iCode && !oEvent.ctrlKey && !oEvent.shiftKey)
		{
			if (0 < this.list().length)
			{
				this.onDelete();
				bResult = false;
			}
		}
		else if (Enums.Key.Enter === iCode)
		{
			if (0 < this.list().length && !oEvent.ctrlKey)
			{
				this.onEnter(this.itemSelected());
				bResult = false;
			}
		}
		else if (oEvent.ctrlKey && !oEvent.altKey && !oEvent.shiftKey && Enums.Key.a === iCode)
		{
			this.checkAll(!(this.checkAll() && !this.isIncompleteChecked()));
			bResult = false;
		}
	}

	return bResult;
};

CSelector.prototype.onDelete = function ()
{
	this.fDeleteCallback.call(this, this.listCheckedOrSelected());
};

/**
 * @param {Object} oItem
 */
CSelector.prototype.onEnter = function (oItem)
{
	var self = this;
	if (oItem && this.fBeforeSelectCallback)
	{
		this.fBeforeSelectCallback(oItem, function (bResult) {
			if (bResult)
			{
				self.itemSelected(oItem);
				self.fEnterCallback.call(this, oItem);
			}
		});
	}
	else
	{
		this.itemSelected(oItem);
		this.fEnterCallback.call(this, oItem);
	}
};

/**
 * @param {Object} oItem
 */
CSelector.prototype.selectionFunc = function (oItem)
{
	this.itemSelected(null);
	if (this.bResetCheckedOnClick)
	{
		this.listChecked(false);
	}

	this.itemSelected(oItem);
	this.fSelectCallback.call(this, oItem);
};

/**
 * @param {Object} oItem
 * @param {boolean=} bCheckBefore = true
 */
CSelector.prototype.onSelect = function (oItem, bCheckBefore)
{
	bCheckBefore = Utils.isUnd(bCheckBefore) ? true : !!bCheckBefore;
	if (this.fBeforeSelectCallback && bCheckBefore)
	{
		var self = this;
		this.fBeforeSelectCallback(oItem, function (bResult) {
			if (bResult)
			{
				self.selectionFunc(oItem);
			}
		});
	}
	else
	{
		this.selectionFunc(oItem);
	}
};

/**
 * @param {Object} oItem
 */
CSelector.prototype.onDblClick = function (oItem)
{
	this.fDblClickCallback.call(this, oItem);
};

CSelector.prototype.koCheckAll = function ()
{
	return ko.computed({
		'read': this.checkAll,
		'write': this.checkAll,
		'owner': this
	});
};

CSelector.prototype.koCheckAllIncomplete = function ()
{
	return ko.computed({
		'read': this.isIncompleteChecked,
		'write': this.isIncompleteChecked,
		'owner': this
	});
};

/**
 * @return {boolean}
 */
CSelector.prototype.scrollToSelected = function ()
{
	if (!this.oListScope || !this.oScrollScope)
	{
		return false;
	}

	var
		iOffset = 20,
		oSelected = $(this.sSelectabelSelector, this.oScrollScope),
		oPos = oSelected.position(),
		iVisibleHeight = this.oScrollScope.height(),
		iSelectedHeight = oSelected.outerHeight()
	;

	if (oPos && (oPos.top < 0 || oPos.top + iSelectedHeight > iVisibleHeight))
	{
		if (oPos.top < 0)
		{
			this.oScrollScope.scrollTop(this.oScrollScope.scrollTop() + oPos.top - iOffset);
		}
		else
		{
			this.oScrollScope.scrollTop(this.oScrollScope.scrollTop() + oPos.top - iVisibleHeight + iSelectedHeight + iOffset);
		}

		return true;
	}

	return false;
};


/**
 * @constructor
 */
function CApi()
{
	this.openPgp = null;
	this.openPgpCallbacks = [];
}

CApi.prototype.composeMessage = function ()
{
	App.Routing.setHash([Enums.Screens.Compose]);
};

/**
 * @param {string} sFolder
 * @param {string} sUid
 */
CApi.prototype.composeMessageFromDrafts = function (sFolder, sUid)
{
	var aParams = App.Links.composeFromMessage('drafts', sFolder, sUid);
	App.Routing.setHash(aParams);
};

/**
 * @param {string} sReplyType
 * @param {string} sFolder
 * @param {string} sUid
 */
CApi.prototype.composeMessageAsReplyOrForward = function (sReplyType, sFolder, sUid)
{
	var aParams = App.Links.composeFromMessage(sReplyType, sFolder, sUid);
	App.Routing.setHash(aParams);
};

/**
 * @param {string} sToAddresses
 */
CApi.prototype.composeMessageToAddresses = function (sToAddresses)
{
	var aParams = App.Links.composeWithToField(sToAddresses);
	App.Routing.setHash(aParams);
};

/**
 * @param {Object} oVcard
 */
CApi.prototype.composeMessageWithVcard = function (oVcard)
{
	var aParams = ['vcard', oVcard];
	App.Routing.goDirectly(App.Links.compose(), aParams);
};

/**
 * @param {string} sArmor
 * @param {string} sDownloadLinkFilename
 */
CApi.prototype.composeMessageWithPgpKey = function (sArmor, sDownloadLinkFilename)
{
	var aParams = ['data-as-file', sArmor, sDownloadLinkFilename];
	App.Routing.goDirectly(App.Links.compose(), aParams);
};

/**
 * @param {Array} aFileItems
 */
CApi.prototype.composeMessageWithFiles = function (aFileItems)
{
	var aParams = ['file', aFileItems];
	App.Routing.goDirectly(App.Links.compose(), aParams);
};

CApi.prototype.closeComposePopup = function ()
{
	//function is overrided in mail module
};

/**
 * Downloads by url through iframe or new window.
 *
 * @param {string} sUrl
 */
CApi.prototype.downloadByUrl = function (sUrl)
{
	var oIframe = null;
	
	if (bMobileDevice)
	{
		window.open(sUrl);
	}
	else
	{
		oIframe = $('<iframe style="display: none;"></iframe>').appendTo(document.body);
		
		oIframe.attr('src', sUrl);
		
		setTimeout(function () {
			oIframe.remove();
		}, 60000);
	}
};

/**
 * @return {boolean}
 */
CApi.prototype.isPgpSupported = function ()
{
	return !!(window.crypto && window.crypto.getRandomValues);
};

/**
 * @param {Function} fCallback
 * @param {mixed=} sUserUid
 */
CApi.prototype.pgp = function (fCallback, sUserUid)
{
	if (Utils.isFunc(fCallback))
	{
		if (this.openPgp)
		{
			fCallback(this.openPgp);
		}
		else if (this.isPgpSupported())
		{
			if (null !== this.openPgpCallbacks)
			{
				this.openPgpCallbacks.push(fCallback);
			}
			else
			{
				fCallback(false);
			}
			
			var self = this;
			if (!this.openPgpRequest)
			{
				this.openPgpRequest = true;
				
				$.ajax({
					'url': 'static/js/openpgp.js',
					'dataType': 'script',
					'cache': true,
					'complete': function () {
						
						self.openPgp = window.openpgp ? new OpenPgp(window.openpgp, 'user_' + (sUserUid || '0') + '_') : false;

						if (null !== self.openPgpCallbacks)
						{
							_.each(self.openPgpCallbacks, function (fItemCallback) {
								fItemCallback(self.openPgp);
							});
						}

						self.openPgpCallbacks = null;
					}
				});
			}
		}
		else
		{
			fCallback(false);
		}
	}
};

/**
 * @param {string} sLoading
 */
CApi.prototype.showLoading = function (sLoading)
{
	App.Screens.showLoading(sLoading);
};

CApi.prototype.hideLoading = function ()
{
	App.Screens.hideLoading();
};

/**
 * @param {string} sReport
 * @param {number=} iDelay if 0 comes then report will not be closed automatically
 */
CApi.prototype.showReport = function (sReport, iDelay)
{
	App.Screens.showReport(sReport, iDelay);
};

/**
 * @param {string} sError
 * @param {boolean=} bHtml = false
 * @param {boolean=} bNotHide = false
 * @param {boolean=} bGray = false
 */
CApi.prototype.showError = function (sError, bHtml, bNotHide, bGray)
{
	App.Screens.showError(sError, bHtml, bNotHide, bGray);
};

/**
 * @param {boolean=} bGray = false
 */
CApi.prototype.hideError = function (bGray)
{
	App.Screens.hideError(bGray);
};

/**
 * @param {Object} oRes
 * @param {string} sPgpAction
 * @param {string=} sDefaultError
 */
CApi.prototype.showPgpErrorByCode = function (oRes, sPgpAction, sDefaultError)
{
	var
		aErrors = Utils.isNonEmptyArray(oRes.errors) ? oRes.errors : [],
		aNotices = Utils.isNonEmptyArray(oRes.notices) ? oRes.notices : [],
		aEmailsWithoutPublicKey = [],
		aEmailsWithoutPrivateKey = [],
		sError = '',
		bNoSignDataNotice = false,
		bNotice = true
	;
	
	_.each(_.union(aErrors, aNotices), function (aError) {
		if (aError.length === 2)
		{
			switch(aError[0])
			{
				case OpenPgpResult.Enum.GenerateKeyError:
					sError = Utils.i18n('OPENPGP/ERROR_GENERATE_KEY');
					break;
				case OpenPgpResult.Enum.ImportKeyError:
					sError = Utils.i18n('OPENPGP/ERROR_IMPORT_KEY');
					break;
				case OpenPgpResult.Enum.ImportNoKeysFoundError:
					sError = Utils.i18n('OPENPGP/ERROR_IMPORT_NO_KEY_FOUNDED');
					break;
				case OpenPgpResult.Enum.PrivateKeyNotFoundError:
				case OpenPgpResult.Enum.PrivateKeyNotFoundNotice:
					aEmailsWithoutPrivateKey.push(aError[1]);
					break;
				case OpenPgpResult.Enum.PublicKeyNotFoundError:
					bNotice = false;
					aEmailsWithoutPublicKey.push(aError[1]);
					break;
				case OpenPgpResult.Enum.PublicKeyNotFoundNotice:
					aEmailsWithoutPublicKey.push(aError[1]);
					break;
				case OpenPgpResult.Enum.KeyIsNotDecodedError:
					if (sPgpAction === Enums.PgpAction.DecryptVerify)
					{
						sError = Utils.i18n('OPENPGP/ERROR_DECRYPT') + ' ' + Utils.i18n('OPENPGP/ERROR_KEY_NOT_DECODED', {'USER': aError[1]});
					}
					else if (sPgpAction === Enums.PgpAction.Sign || sPgpAction === Enums.PgpAction.EncryptSign)
					{
						sError = Utils.i18n('OPENPGP/ERROR_SIGN') + ' ' + Utils.i18n('OPENPGP/ERROR_KEY_NOT_DECODED', {'USER': aError[1]});
					}
					break;
				case OpenPgpResult.Enum.SignError:
					sError = Utils.i18n('OPENPGP/ERROR_SIGN');
					break;
				case OpenPgpResult.Enum.VerifyError:
					sError = Utils.i18n('OPENPGP/ERROR_VERIFY');
					break;
				case OpenPgpResult.Enum.EncryptError:
					sError = Utils.i18n('OPENPGP/ERROR_ENCRYPT');
					break;
				case OpenPgpResult.Enum.DecryptError:
					sError = Utils.i18n('OPENPGP/ERROR_DECRYPT');
					break;
				case OpenPgpResult.Enum.SignAndEncryptError:
					sError = Utils.i18n('OPENPGP/ERROR_ENCRYPT_OR_SIGN');
					break;
				case OpenPgpResult.Enum.VerifyAndDecryptError:
					sError = Utils.i18n('OPENPGP/ERROR_DECRYPT_OR_VERIFY');
					break;
				case OpenPgpResult.Enum.DeleteError:
					sError = Utils.i18n('OPENPGP/ERROR_DELETE_KEY');
					break;
				case OpenPgpResult.Enum.VerifyErrorNotice:
					sError = Utils.i18n('OPENPGP/ERROR_VERIFY');
					break;
				case OpenPgpResult.Enum.NoSignDataNotice:
					bNoSignDataNotice = true;
					break;
			}
		}
	});
	
	if (aEmailsWithoutPublicKey.length > 0)
	{
		aEmailsWithoutPublicKey = _.without(aEmailsWithoutPublicKey, '');
		if (aEmailsWithoutPublicKey.length > 0)
		{
			sError = Utils.i18n('OPENPGP/ERROR_NO_PUBLIC_KEYS_FOR_USERS_PLURAL', 
					{'USERS': aEmailsWithoutPublicKey.join(', ')}, null, aEmailsWithoutPublicKey.length);
		}
		else if (sPgpAction === Enums.PgpAction.Verify)
		{
			sError = Utils.i18n('OPENPGP/ERROR_NO_PUBLIC_KEY_FOUND_FOR_VERIFY');
		}
		if (bNotice && sError !== '')
		{
			sError += ' ' + Utils.i18n('OPENPGP/ERROR_MESSAGE_WAS_NOT_VERIFIED');
		}
	}
	else if (aEmailsWithoutPrivateKey.length > 0)
	{
		aEmailsWithoutPrivateKey = _.without(aEmailsWithoutPrivateKey, '');
		if (aEmailsWithoutPrivateKey.length > 0)
		{
			sError = Utils.i18n('OPENPGP/ERROR_NO_PRIVATE_KEYS_FOR_USERS_PLURAL', 
					{'USERS': aEmailsWithoutPrivateKey.join(', ')}, null, aEmailsWithoutPrivateKey.length);
		}
		else if (sPgpAction === Enums.PgpAction.DecryptVerify)
		{
			sError = Utils.i18n('OPENPGP/ERROR_NO_PRIVATE_KEY_FOUND_FOR_DECRYPT');
		}
	}
	
	if (sError === '' && !bNoSignDataNotice)
	{
		switch (sPgpAction)
		{
			case Enums.PgpAction.Generate:
				sError = Utils.i18n('OPENPGP/ERROR_GENERATE_KEY');
				break;
			case Enums.PgpAction.Import:
				sError = Utils.i18n('OPENPGP/ERROR_IMPORT_KEY');
				break;
			case Enums.PgpAction.DecryptVerify:
				sError = Utils.i18n('OPENPGP/ERROR_DECRYPT');
				break;
			case Enums.PgpAction.Verify:
				sError = Utils.i18n('OPENPGP/ERROR_VERIFY');
				break;
			case Enums.PgpAction.Encrypt:
				sError = Utils.i18n('OPENPGP/ERROR_ENCRYPT');
				break;
			case Enums.PgpAction.EncryptSign:
				sError = Utils.i18n('OPENPGP/ERROR_ENCRYPT_OR_SIGN');
				break;
			case Enums.PgpAction.Sign:
				sError = Utils.i18n('OPENPGP/ERROR_SIGN');
				break;
		}
		sError = sDefaultError;
	}
	
	if (sError !== '')
	{
		App.Api.showError(sError);
	}
	
	return bNoSignDataNotice;
};

/**
 * @param {Object} oResponse
 * @param {string=} sDefaultError
 * @param {boolean=} bNotHide = false
 */
CApi.prototype.showErrorByCode = function (oResponse, sDefaultError, bNotHide)
{
	var
		iErrorCode = oResponse.ErrorCode,
		sResponseError = oResponse.ErrorMessage || '',
		sResultError = ''
	;
	
	switch (iErrorCode)
	{
		default:
			sResultError = sDefaultError;
			break;
		case Enums.Errors.AuthError:
			sResultError = Utils.i18n('WARNING/LOGIN_PASS_INCORRECT');
			break;
		case Enums.Errors.DataBaseError:
			sResultError = Utils.i18n('WARNING/DATABASE_ERROR');
			break;
		case Enums.Errors.LicenseProblem:
			sResultError = Utils.i18n('WARNING/INVALID_LICENSE');
			break;
		case Enums.Errors.DemoLimitations:
			sResultError = Utils.i18n('DEMO/WARNING_THIS_FEATURE_IS_DISABLED');
			break;
		case Enums.Errors.Captcha:
			sResultError = Utils.i18n('WARNING/CAPTCHA_IS_INCORRECT');
			break;
		case Enums.Errors.CanNotGetMessage:
			sResultError = Utils.i18n('MESSAGE/ERROR_MESSAGE_DELETED');
			break;
		case Enums.Errors.NoRequestedMailbox:
			sResultError = sDefaultError + ' ' + Utils.i18n('COMPOSE/ERROR_INVALID_ADDRESS', {'ADDRESS': (oResponse.Mailbox || '')});
			break;
		case Enums.Errors.CanNotChangePassword:
			sResultError = Utils.i18n('WARNING/UNABLE_CHANGE_PASSWORD');
			break;
		case Enums.Errors.AccountOldPasswordNotCorrect:
			sResultError = Utils.i18n('WARNING/CURRENT_PASSWORD_NOT_CORRECT');
			break;
		case Enums.Errors.FetcherIncServerNotAvailable:
			sResultError = Utils.i18n('WARNING/FETCHER_SAVE_ERROR');
			break;
		case Enums.Errors.FetcherLoginNotCorrect:
			sResultError = Utils.i18n('WARNING/FETCHER_SAVE_ERROR');
			break;
		case Enums.Errors.HelpdeskUserNotExists:
			sResultError = Utils.i18n('HELPDESK/ERROR_FORGOT_NO_ACCOUNT');
			break;
		case Enums.Errors.MailServerError:
			sResultError = Utils.i18n('WARNING/CANT_CONNECT_TO_SERVER');
			break;
		case Enums.Errors.DataTransferFailed:
			sResultError = Utils.i18n('WARNING/DATA_TRANSFER_FAILED');
			break;
		case Enums.Errors.NotDisplayedError:
			sResultError = '';
			break;
	}
	
	if (sResultError !== '')
	{
		if (sResponseError !== '')
		{
			sResultError += ' (' + sResponseError + ')';
		}
		this.showError(sResultError, false, !!bNotHide);
	}
	else if (sResponseError !== '')
	{
		this.showError(sResponseError);
	}
};

/**
 * @param {string} sFileName
 * @param {number} iSize
 * @returns {Boolean}
 */
CApi.prototype.showErrorIfAttachmentSizeLimit = function (sFileName, iSize)
{
	var
		sWarning = Utils.i18n('COMPOSE/UPLOAD_ERROR_FILENAME_SIZE', {
			'FILENAME': sFileName,
			'MAXSIZE': Utils.friendlySize(AppData.App.AttachmentSizeLimit)
		})
	;
	
	if (AppData.App.AttachmentSizeLimit > 0 && iSize > AppData.App.AttachmentSizeLimit)
	{
		App.Screens.showPopup(AlertPopup, [sWarning]);
		return true;
	}
	
	return false;
};

/**
 * Moves the specified messages in the current folder to the Trash or delete permanently 
 * if the current folder is Trash or Spam.
 * 
 * @param {Array} aUids
 * @param {Object} oApp
 * @param {Function=} fAfterDelete
 */
CApi.prototype.deleteMessages = function (aUids, oApp, fAfterDelete)
{
	if (!Utils.isFunc(fAfterDelete))
	{
		fAfterDelete = function () {};
	}
	
	var
		oFolderList = App.MailCache.folderList(),
		sCurrFolder = oFolderList.currentFolderFullName(),
		oTrash = oFolderList.trashFolder(),
		bInTrash =(oTrash && sCurrFolder === oTrash.fullName()),
		oSpam = oFolderList.spamFolder(),
		bInSpam = (oSpam && sCurrFolder === oSpam.fullName()),
		fDeleteMessages = function (bResult) {
			if (bResult)
			{
				oApp.MailCache.deleteMessages(aUids);
				fAfterDelete();
			}
		}
	;
	
	if (bInSpam)
	{
		oApp.MailCache.deleteMessages(aUids);
		fAfterDelete();
	}
	else if (bInTrash)
	{
		App.Screens.showPopup(ConfirmPopup, [Utils.i18n('MAILBOX/CONFIRM_MESSAGES_DELETE'), fDeleteMessages]);
	}
	else if (oTrash)
	{
		oApp.MailCache.moveMessagesToFolder(oTrash.fullName(), aUids);
		fAfterDelete();
	}
	else if (!oTrash)
	{
		App.Screens.showPopup(ConfirmPopup, [Utils.i18n('MAILBOX/CONFIRM_MESSAGES_DELETE_NO_TRASH_FOLDER'), fDeleteMessages]);
	}
};


/**
 * @param {string} sName
 * @param {string} sHeaderTitle
 * @param {string} sDocumentTitle
 * @param {string} sTemplateName
 * @param {Object} oViewModelClass
 */
AfterLogicApi.addScreenToHeader = function (sName, sHeaderTitle, sDocumentTitle, sTemplateName, oViewModelClass)
{
	App.addScreenToHeader(sName, sHeaderTitle, sDocumentTitle, sTemplateName, oViewModelClass, true);
};

/**
 * @param {string} sNewDefaultTab
 */
AfterLogicApi.setDefaultTab = function (sNewDefaultTab)
{
	var bDefaultTabInEnum = !!_.find(Enums.Screens, function (sScreenInEnum) {
		return sScreenInEnum === sNewDefaultTab;
	});
	
	if (bDefaultTabInEnum)
	{
		AppData.App.DefaultTab = sNewDefaultTab;
	}
};

AfterLogicApi.aSettingsTabs = [];

/**
 * @param {Object} oViewModelClass
 */
AfterLogicApi.addSettingsTab = function (oViewModelClass)
{
	if (oViewModelClass.prototype.TabName)
	{
		Enums.SettingsTab[oViewModelClass.prototype.TabName] = oViewModelClass.prototype.TabName;
		AfterLogicApi.aSettingsTabs.push(oViewModelClass);
	}
};

/**
 * @return {Array}
 */
AfterLogicApi.getPluginsSettingsTabs = function ()
{
	return AfterLogicApi.aSettingsTabs;
};

/**
 * @param {string} sSettingName
 * 
 * @return {string}
 */
AfterLogicApi.getSetting = function (sSettingName)
{
	return AppData.App[sSettingName];
};

/**
 * @param {string} sPluginName
 * 
 * @return {string|null}
 */
AfterLogicApi.getPluginSettings = function (sPluginName)
{
	if (AppData && AppData.Plugins)
	{
		return AppData.Plugins[sPluginName];
	}
	
	return null;
};

AfterLogicApi.oPluginHooks = {};

/**
 * @param {string} sName
 * @param {Function} fCallback
 */
AfterLogicApi.addPluginHook = function (sName, fCallback)
{
	if (Utils.isFunc(fCallback))
	{
		if (!$.isArray(this.oPluginHooks[sName]))
		{
			this.oPluginHooks[sName] = [];
		}
		
		this.oPluginHooks[sName].push(fCallback);
	}
};

/**
 * @param {string} sName
 * @param {Array=} aArguments
 */
AfterLogicApi.runPluginHook = function (sName, aArguments)
{
	if ($.isArray(this.oPluginHooks[sName]))
	{
		aArguments = aArguments || [];
		
		_.each(this.oPluginHooks[sName], function (fCallback) {
			fCallback.apply(null, aArguments);
		});
	}
};

/**
 * @param {Object} oParameters
 * @param {Function=} fResponseHandler
 * @param {Object=} oContext
 */
AfterLogicApi.sendAjaxRequest = function (oParameters, fResponseHandler, oContext)
{
	App.Ajax.send(oParameters, fResponseHandler, oContext);
};

/**
 * @param {string} sKey
 * @param {?Object=} oValueList
 * @param {?string=} sDefaulValue
 * @param {number=} nPluralCount
 * 
 * @return {string}
 */
AfterLogicApi.i18n = Utils.i18n;

/**
 * @param {string} sRecipients
 * 
 * @return {Array}
 */
AfterLogicApi.getArrayRecipients = Utils.getArrayRecipients;

/**
 * @param {string} sFullEmail
 * 
 * @return {Object}
 */
AfterLogicApi.getEmailParts = Utils.getEmailParts;

/**
* @param {string} sAlert
*/
AfterLogicApi.showAlertPopup = function (sAlert)
{
	App.Screens.showPopup(AlertPopup, [sAlert]);
};

/**
* @param {string} sConfirm
* @param {Function} fConfirmCallback
*/
AfterLogicApi.showConfirmPopup = function (sConfirm, fConfirmCallback)
{
	App.Screens.showPopup(ConfirmPopup, [sConfirm, fConfirmCallback]);
};

AfterLogicApi.showPopup = function (sName, aParams)
{
	App.Screens.showPopup(sName, aParams);
};

/**
* @param {string} sReport
* @param {number=} iDelay if 0 comes then report will not be closed automatically
*/
AfterLogicApi.showReport = function(sReport, iDelay)
{
	App.Screens.showReport(sReport, iDelay);
};

/**
* @param {string} sError
*/
AfterLogicApi.showError = function(sError)
{
	App.Screens.showError(sError);
};

AfterLogicApi.getPrimaryAccountData = function()
{
	var oDefault = AppData.Accounts.getDefault();
	
	return {
		'Id': oDefault.id(),
		'Email': oDefault.email(),
		'FriendlyName': oDefault.friendlyName()
	};
};

AfterLogicApi.getCurrentAccountData = function()
{
	var oDefault = AppData.Accounts.getCurrent();
	
	return {
		'Id': oDefault.id(),
		'Email': oDefault.email(),
		'FriendlyName': oDefault.friendlyName()
	};
};

/**
 * @return {boolean}
 */
AfterLogicApi.isMobile = function ()
{
	return this.getAppDataItem('IsMobile');
};

/**
 * @param {string} sParamName
 * 
 * @return {string|null}
 */
AfterLogicApi.getRequestParam = Utils.getRequestParam;

AfterLogicApi.editedFolderList = function ()
{
	return App.MailCache.editedFolderList;
};

AfterLogicApi.FileSizeLimit = AppData.App.FileSizeLimit;

AfterLogicApi.isFunc = Utils.isFunc;
AfterLogicApi.isUnd = Utils.isUnd;

/**
 * @param {string} sItemName
 * 
 * @return {string}
 */
AfterLogicApi.getAppDataItem = function (sItemName)
{
	if (AppData && AppData[sItemName])
	{
		return AppData[sItemName];
	}
	
	return null;	
};

/**
 * @constructor
 */
function CStorage()
{
	Data.init();
}

CStorage.prototype.setData = function (key, value)
{
	Data.setVar(key, value);
};

CStorage.prototype.removeData = function (key)
{
	Data.setVar(key, '');
};

CStorage.prototype.getData = function (key)
{
	return Data.getVar(key);
};

CStorage.prototype.hasData = function (key)
{
	return Data.hasVar(key);
};

/**
 * @todo
 * @param {Object} oOpenPgp
 * @param {string=} sPrefix
 * @constructor
 */
function OpenPgp(oOpenPgp, sPrefix)
{
	this.pgp = oOpenPgp;
	this.pgpKeyring = new this.pgp.Keyring(new this.pgp.Keyring.localstore(sPrefix));
	
	this.keys = ko.observableArray([]);

	this.reloadKeysFromStorage();
}

OpenPgp.prototype.pgp = null;
OpenPgp.prototype.pgpKeyring = null;
OpenPgp.prototype.keys = [];

/**
 * @return {Array}
 */
OpenPgp.prototype.getKeys = function ()
{
	return this.keys();
};

/**
 * @return {mixed}
 */
OpenPgp.prototype.getKeysObservable = function ()
{
	return this.keys;
};

/**
 * @private
 */
OpenPgp.prototype.reloadKeysFromStorage = function ()
{
	var
		aKeys = [],
		oOpenpgpKeys = this.pgpKeyring.getAllKeys()
	;

	_.each(oOpenpgpKeys, function (oItem) {
		if (oItem && oItem.primaryKey)
		{
			aKeys.push(new OpenPgpKey(oItem));
		}
	});

	this.keys(aKeys);
};

/**
 * @private
 * @param {Array} aKeys
 * @return {Array}
 */
OpenPgp.prototype.convertToNativeKeys = function (aKeys)
{
	return _.map(aKeys, function (oItem) {
		return (oItem && oItem.pgpKey) ? oItem.pgpKey : oItem;
	});
};

/**
 * @private
 */
OpenPgp.prototype.cloneKey = function (oKey)
{
	var oPrivateKey = null;
	if (oKey)
	{
		oPrivateKey = this.pgp.key.readArmored(oKey.armor());
		if (oPrivateKey && !oPrivateKey.err && oPrivateKey.keys && oPrivateKey.keys[0])
		{
			oPrivateKey = oPrivateKey.keys[0];
			if (!oPrivateKey || !oPrivateKey.primaryKey)
			{
				oPrivateKey = null;
			}
		}
		else
		{
			oPrivateKey = null;
		}
	}

	return oPrivateKey;
};

/**
 * @private
 */
OpenPgp.prototype.decryptKeyHelper = function (oResult, oKey, sPassword, sKeyEmail)
{
	if (oKey)
	{
		try
		{
			oKey.decrypt(Utils.pString(sPassword));
			if (!oKey || !oKey.primaryKey || !oKey.primaryKey.isDecrypted)
			{
				oResult.addError(OpenPgpResult.Enum.KeyIsNotDecodedError, sKeyEmail || '');
			}
		}
		catch (e)
		{
			oResult.addExceptionMessage(e, OpenPgpResult.Enum.KeyIsNotDecodedError, sKeyEmail || '');
		}
	}
	else
	{
		oResult.addError(OpenPgpResult.Enum.KeyIsNotDecodedError, sKeyEmail || '');
	}
};

/**
 * @private
 */
OpenPgp.prototype.verifyMessageHelper = function (oResult, sFromEmail, oDecryptedMessage)
{
	var
		bResult = false,
		oValidKey = null,
		aVerifyResult = [],
		aVerifyKeysId = [],
		aPublicKeys = []
	;

	if (oDecryptedMessage && oDecryptedMessage.getSigningKeyIds)
	{
		aVerifyKeysId = oDecryptedMessage.getSigningKeyIds();
		if (aVerifyKeysId && 0 < aVerifyKeysId.length)
		{
			aPublicKeys = this.findKeysByEmails([sFromEmail], true);
			if (!aPublicKeys || 0 === aPublicKeys.length)
			{
				oResult.addNotice(OpenPgpResult.Enum.PublicKeyNotFoundNotice, sFromEmail);
			}
			else
			{
				aVerifyResult = [];
				try
				{
					aVerifyResult = oDecryptedMessage.verify(this.convertToNativeKeys(aPublicKeys));
				}
				catch (e)
				{
					oResult.addNotice(OpenPgpResult.Enum.VerifyErrorNotice, sFromEmail);
				}

				if (aVerifyResult && 0 < aVerifyResult.length)
				{
					oValidKey = _.find(aVerifyResult, function (oItem) {
						return oItem && oItem.keyid && oItem.valid;
					});

					if (oValidKey && oValidKey.keyid && 
						aPublicKeys && aPublicKeys[0] &&
						oValidKey.keyid.toHex().toLowerCase() === aPublicKeys[0].getId())
					{
						bResult = true;
					}
					else
					{
						oResult.addNotice(OpenPgpResult.Enum.VerifyErrorNotice, sFromEmail);
					}
				}
			}
		}
		else
		{
			oResult.addNotice(OpenPgpResult.Enum.NoSignDataNotice);
		}
	}
	else
	{
		oResult.addError(OpenPgpResult.Enum.UnknownError);
	}

	if (!bResult && !oResult.hasNotices())
	{
		oResult.addNotice(OpenPgpResult.Enum.VerifyErrorNotice);
	}

	return bResult;
};

/**
 * @param {string} sUserID
 * @param {string} sPassword
 * @param {number} nKeyLength
 *
 * @return {OpenPgpResult}
 */
OpenPgp.prototype.generateKey = function (sUserID, sPassword, nKeyLength)
{
	var 
		oResult = new OpenPgpResult(),
		mKeyPair = null
	;

	try
	{
//		mKeyPair = this.pgp.generateKeyPair(1, Utils.pInt(nKeyLength), sUserID, Utils.trim(sPassword));
		mKeyPair = this.pgp.generateKeyPair({
			'userId': sUserID,
			'numBits': Utils.pInt(nKeyLength),
			'passphrase': Utils.trim(sPassword)
		});
	}
	catch (e)
	{
		oResult.addExceptionMessage(e);
	}

	if (mKeyPair && mKeyPair.privateKeyArmored)
	{
		try
		{
			this.pgpKeyring.privateKeys.importKey(mKeyPair.privateKeyArmored);
			this.pgpKeyring.publicKeys.importKey(mKeyPair.publicKeyArmored);
			this.pgpKeyring.store();
		}
		catch (e)
		{
			oResult.addExceptionMessage(e, OpenPgpResult.Enum.GenerateKeyError);
		}
	}
	else
	{
		oResult.addError(OpenPgpResult.Enum.GenerateKeyError);
	}

	this.reloadKeysFromStorage();

	return oResult;
};

/**
 * @private
 * @param {string} sArmor
 * @return {Array}
 */
OpenPgp.prototype.splitKeys = function (sArmor)
{
	var
		aResult = [],
		iCount = 0,
		iLimit = 30,
		aMatch = null,
		sKey = Utils.trim(sArmor),
		oReg = /[\-]{3,6}BEGIN[\s]PGP[\s](PRIVATE|PUBLIC)[\s]KEY[\s]BLOCK[\-]{3,6}[\s\S]+?[\-]{3,6}END[\s]PGP[\s](PRIVATE|PUBLIC)[\s]KEY[\s]BLOCK[\-]{3,6}/gi
	;

	sKey = sKey.replace(/[\r\n]([a-zA-Z0-9]{2,}:[^\r\n]+)[\r\n]+([a-zA-Z0-9\/\\+=]{10,})/g, '\n$1---xyx---$2')
		.replace(/[\n\r]+/g, '\n').replace(/---xyx---/g, '\n\n');

	do
	{
		aMatch = oReg.exec(sKey);
		if (!aMatch || 0 > iLimit)
		{
			break;
		}

		if (aMatch[0] && aMatch[1] && aMatch[2] && aMatch[1] === aMatch[2])
		{
			if ('PRIVATE' === aMatch[1] || 'PUBLIC' === aMatch[1])
			{
				aResult.push([aMatch[1], aMatch[0]]);
				iCount++;
			}
		}

		iLimit--;
	}
	while (true);

	return aResult;
};

/**
 * @param {string} sArmor
 * @return {OpenPgpResult}
 */
OpenPgp.prototype.importKeys = function (sArmor)
{
	sArmor = Utils.trim(sArmor);

	var
		iIndex = 0,
		iCount = 0,
		oResult = new OpenPgpResult(),
		aData = null,
		aKeys = []
	;

	if (!sArmor)
	{
		return oResult.addError(OpenPgpResult.Enum.InvalidArgumentErrors);
	}

	aKeys = this.splitKeys(sArmor);

	for (iIndex = 0; iIndex < aKeys.length; iIndex++)
	{
		aData = aKeys[iIndex];
		if ('PRIVATE' === aData[0])
		{
			try
			{
				this.pgpKeyring.privateKeys.importKey(aData[1]);
				iCount++;
			}
			catch (e)
			{
				oResult.addExceptionMessage(e, OpenPgpResult.Enum.ImportKeyError, 'private');
			}
		}
		else if ('PUBLIC' === aData[0])
		{
			try
			{
				this.pgpKeyring.publicKeys.importKey(aData[1]);
				iCount++;
			}
			catch (e)
			{
				oResult.addExceptionMessage(e, OpenPgpResult.Enum.ImportKeyError, 'public');
			}
		}
	}

	if (0 < iCount)
	{
		this.pgpKeyring.store();
	}
	else
	{
		oResult.addError(OpenPgpResult.Enum.ImportNoKeysFoundError);
	}

	this.reloadKeysFromStorage();

	return oResult;
};

/**
 * @param {string} sArmor
 * @return {Array|boolean}
 */
OpenPgp.prototype.getArmorInfo = function (sArmor)
{
	sArmor = Utils.trim(sArmor);

	var
		iIndex = 0,
		iCount = 0,
		oKey = null,
		aResult = [],
		aData = null,
		aKeys = []
	;

	if (!sArmor)
	{
		return false;
	}

	aKeys = this.splitKeys(sArmor);

	for (iIndex = 0; iIndex < aKeys.length; iIndex++)
	{
		aData = aKeys[iIndex];
		if ('PRIVATE' === aData[0])
		{
			try
			{
				oKey = this.pgp.key.readArmored(aData[1]);
				if (oKey && !oKey.err && oKey.keys && oKey.keys[0])
				{
					aResult.push(new OpenPgpKey(oKey.keys[0]));
				}
				
				iCount++;
			}
			catch (e)
			{
				aResult.push(null);
			}
		}
		else if ('PUBLIC' === aData[0])
		{
			try
			{
				oKey = this.pgp.key.readArmored(aData[1]);
				if (oKey && !oKey.err && oKey.keys && oKey.keys[0])
				{
					aResult.push(new OpenPgpKey(oKey.keys[0]));
				}

				iCount++;
			}
			catch (e)
			{
				aResult.push(null);
			}
		}
	}

	return aResult;
};

/**
 * @param {string} sID
 * @param {boolean} bPublic
 * @return {OpenPgpKey|null}
 */
OpenPgp.prototype.findKeyByID = function (sID, bPublic)
{
	bPublic = !!bPublic;
	sID = sID.toLowerCase();
	
	var oKey = _.find(this.keys(), function (oKey) {
		
		var
			oResult = false,
			aKeys = null
		;
		
		if (oKey && bPublic === oKey.isPublic())
		{
			aKeys = oKey.pgpKey.getKeyIds();
			if (aKeys)
			{
				oResult = _.find(aKeys, function (oKey) {
					return oKey && oKey.toHex && sID === oKey.toHex().toLowerCase();
				});
			}
		}
		
		return !!oResult;
	});

	return oKey ? oKey : null;
};

/**
 * @param {Array} aEmail
 * @param {boolean} bIsPublic
 * @param {OpenPgpResult=} oResult
 * @return {Array}
 */
OpenPgp.prototype.findKeysByEmails = function (aEmail, bIsPublic, oResult)
{
	bIsPublic = !!bIsPublic;
	
	var
		aResult = [],
		aKeys = this.keys()
	;
	_.each(aEmail, function (sEmail) {

		var oKey = _.find(aKeys, function (oKey) {
			return oKey && bIsPublic === oKey.isPublic() && sEmail === oKey.getEmail();
		});

		if (oKey)
		{
			aResult.push(oKey);
		}
		else
		{
			if (oResult)
			{
				oResult.addError(bIsPublic ?
					OpenPgpResult.Enum.PublicKeyNotFoundError : OpenPgpResult.Enum.PrivateKeyNotFoundError, sEmail);
			}
		}
	});

	return aResult;
};

/**
 * @param {string} sData
 * @param {string} sAccountEmail
 * @param {string} sFromEmail
 * @param {string=} sPrivateKeyPassword = ''
 * @return {string}
 */
OpenPgp.prototype.decryptAndVerify = function (sData, sAccountEmail, sFromEmail, sPrivateKeyPassword)
{
	var
		self = this,
		oMessage = null,
		oPrivateEmailKey = null,
		oPrivateKey = null,
		oPrivateKeyClone = null,
		oMessageDecrypted = null,
		oResult = new OpenPgpResult(),
		aEncryptionKeyIds = []
	;

	oMessage = this.pgp.message.readArmored(sData);
	if (oMessage && oMessage.decrypt)
	{
		aEncryptionKeyIds = oMessage.getEncryptionKeyIds();
		if (aEncryptionKeyIds)
		{
			oPrivateKey = null;
			oPrivateEmailKey = null;
			
			_.each(aEncryptionKeyIds, function (oKey) {
				if (!oPrivateEmailKey)
				{
					oPrivateEmailKey = self.findKeyByID(oKey.toHex(), false);
					if (oPrivateEmailKey && sAccountEmail !== oPrivateEmailKey.getEmail())
					{
						oPrivateEmailKey = null;
					}
				}
			});

			if (oPrivateEmailKey)
			{
				oPrivateKey = oPrivateEmailKey;
			}

			if (!oPrivateKey)
			{
				_.each(aEncryptionKeyIds, function (oKey) {
					if (!oPrivateKey)
					{
						oPrivateKey = self.findKeyByID(oKey.toHex(), false);
					}
				});
			}
		}

		if (!oPrivateKey)
		{
			oResult.addError(OpenPgpResult.Enum.PrivateKeyNotFoundError);
		}
		else
		{
			oPrivateKeyClone = this.cloneKey(this.convertToNativeKeys([oPrivateKey])[0]);
			
			this.decryptKeyHelper(oResult, oPrivateKeyClone, sPrivateKeyPassword, oPrivateKey.getEmail());

			if (oPrivateKeyClone && !oResult.hasErrors())
			{
				try
				{
					oMessageDecrypted = oMessage.decrypt(oPrivateKeyClone);
				}
				catch (e)
				{
					oResult.addExceptionMessage(e, OpenPgpResult.Enum.DecryptError);
					oMessageDecrypted = null;
				}
			}

			if (oMessageDecrypted && !oResult.hasErrors())
			{
				this.verifyMessageHelper(oResult, sFromEmail, oMessageDecrypted);

				oResult.result = oMessageDecrypted.getText();
			}
		}
	}

	return oResult;
};

/**
 * @param {string} sData
 * @param {string} sFromEmail
 * @return {string}
 */
OpenPgp.prototype.verify = function (sData, sFromEmail)
{
	var
		oMessageDecrypted = null,
		oResult = new OpenPgpResult()
	;

	oMessageDecrypted = this.pgp.cleartext.readArmored(sData);
	if (oMessageDecrypted && oMessageDecrypted.getText && oMessageDecrypted.verify)
	{
		this.verifyMessageHelper(oResult, sFromEmail, oMessageDecrypted);

		oResult.result = oMessageDecrypted.getText();
	}
	else
	{
		oResult.addError(OpenPgpResult.Enum.CanNotReadMessage);
	}

	return oResult;
};

/**
 * @param {string} sData
 * @param {Array} aPrincipalsEmail
 * @return {string}
 */
OpenPgp.prototype.encrypt = function (sData, aPrincipalsEmail)
{
	var
		oResult = new OpenPgpResult(),
		aPublicKeys = this.findKeysByEmails(aPrincipalsEmail, true, oResult)
	;

	if (!oResult.hasErrors())
	{
		try
		{
			oResult.result = this.pgp.encryptMessage(
				this.convertToNativeKeys(aPublicKeys), sData);
		}
		catch (e)
		{
			oResult.addExceptionMessage(e, OpenPgpResult.Enum.EncryptError);
		}
	}

	return oResult;
};

/**
 * @param {string} sData
 * @param {string} sFromEmail
 * @param {string=} sPrivateKeyPassword
 * @return {string}
 */
OpenPgp.prototype.sign = function (sData, sFromEmail, sPrivateKeyPassword)
{
	var
		oResult = new OpenPgpResult(),
		oPrivateKey = null,
		oPrivateKeyClone = null,
		aPrivateKeys = this.findKeysByEmails([sFromEmail], false, oResult)
	;

	if (!oResult.hasErrors())
	{
		oPrivateKey = this.convertToNativeKeys(aPrivateKeys)[0];
		oPrivateKeyClone = this.cloneKey(oPrivateKey);

		this.decryptKeyHelper(oResult, oPrivateKeyClone, sPrivateKeyPassword, sFromEmail);

		if (oPrivateKeyClone && !oResult.hasErrors())
		{
			try
			{
				oResult.result = this.pgp.signClearMessage([oPrivateKeyClone], sData);
			}
			catch (e)
			{
				oResult.addExceptionMessage(e, OpenPgpResult.Enum.SignError, sFromEmail);
			}
		}
	}

	return oResult;
};

/**
 * @param {string} sData
 * @param {string} sFromEmail
 * @param {Array} aPrincipalsEmail
 * @param {string=} sPrivateKeyPassword
 * @return {string}
 */
OpenPgp.prototype.signAndEncrypt = function (sData, sFromEmail, aPrincipalsEmail, sPrivateKeyPassword)
{
	var
		oPrivateKey = null,
		oPrivateKeyClone = null,
		oResult = new OpenPgpResult(),
		aPrivateKeys = this.findKeysByEmails([sFromEmail], false, oResult),
		aPublicKeys = this.findKeysByEmails(aPrincipalsEmail, true, oResult)
	;

	if (!oResult.hasErrors())
	{
		oPrivateKey = this.convertToNativeKeys(aPrivateKeys)[0];
		oPrivateKeyClone = this.cloneKey(oPrivateKey);

		this.decryptKeyHelper(oResult, oPrivateKeyClone, sPrivateKeyPassword, sFromEmail);
		
		if (oPrivateKeyClone && !oResult.hasErrors())
		{
			try
			{
				oResult.result = this.pgp.signAndEncryptMessage(
					this.convertToNativeKeys(aPublicKeys), oPrivateKeyClone, sData);
			}
			catch (e)
			{
				oResult.addExceptionMessage(e, OpenPgpResult.Enum.SignAndEncryptError);
			}
		}
	}
	
	return oResult;
};

/**
 * @param {OpenPgpKey} oKey
 */
OpenPgp.prototype.deleteKey = function (oKey)
{
	var oResult = new OpenPgpResult();
	if (oKey)
	{
		try
		{
			this.pgpKeyring[oKey.isPrivate() ? 'privateKeys' : 'publicKeys'].removeForId(oKey.getFingerprint());
			this.pgpKeyring.store();
		}
		catch (e)
		{
			oResult.addExceptionMessage(e, OpenPgpResult.Enum.DeleteError);
		}
	}
	else
	{
		oResult.addError(oKey ? OpenPgpResult.Enum.UnknownError : OpenPgpResult.Enum.InvalidArgumentError);
	}

	this.reloadKeysFromStorage();

	return oResult;
};


/**
 * @todo
 * @param {Object} oOpenPgpKey
 * @constructor
 */
function OpenPgpKey(oOpenPgpKey)
{
	this.pgpKey = oOpenPgpKey;

	var oPrimaryUser = this.pgpKey.getPrimaryUser();
	
	this.user = (oPrimaryUser && oPrimaryUser.user) ? oPrimaryUser.user.userId.userid :
		(this.pgpKey.users && this.pgpKey.users[0] ? this.pgpKey.users[0].userId.userid : '');

	this.emailParts = Utils.getEmailParts(this.user);
}

/**
 * @type {Object}
 */
OpenPgpKey.prototype.pgpKey = null;

/**
 * @type {Object}
 */
OpenPgpKey.prototype.emailParts = null;

/**
 * @type {string}
 */
OpenPgpKey.prototype.user = '';

/**
 * @return {string}
 */
OpenPgpKey.prototype.getId = function ()
{
	return this.pgpKey.primaryKey.getKeyId().toHex().toLowerCase();
};

/**
 * @return {string}
 */
OpenPgpKey.prototype.getEmail = function ()
{
	return this.emailParts['email'] || this.user;
};

/**
 * @return {string}
 */
OpenPgpKey.prototype.getUser = function ()
{
	return this.user;
};

/**
 * @return {string}
 */
OpenPgpKey.prototype.getFingerprint = function ()
{
	return this.pgpKey.primaryKey.getFingerprint();
};

/**
 * @return {number}
 */
OpenPgpKey.prototype.getBitSize = function ()
{
	return this.pgpKey.primaryKey.getBitSize();
};

/**
 * @return {string}
 */
OpenPgpKey.prototype.getArmor = function ()
{
	return this.pgpKey.armor();
};

/**
 * @return {boolean}
 */
OpenPgpKey.prototype.isPrivate = function ()
{
	return !!this.pgpKey.isPrivate();
};

/**
 * @return {boolean}
 */
OpenPgpKey.prototype.isPublic = function ()
{
	return !this.isPrivate();
};


/**
 * @todo
 * @constructor
 */
function OpenPgpResult()
{
	this.result = true;
	this.errors = null;
	this.notices = null;
	this.exceptions = null;
}

OpenPgpResult.Enum = {
	'UnknownError': 0,
	'UnknownNotice': 1,
	'InvalidArgumentError': 2,
	'GenerateKeyError': 10,
	'ImportKeyError': 20,
	'ImportNoKeysFoundError': 21,
	'PrivateKeyNotFoundError': 30,
	'PublicKeyNotFoundError': 31,
	'KeyIsNotDecodedError': 32,
	'SignError': 40,
	'VerifyError': 41,
	'EncryptError': 42,
	'DecryptError': 43,
	'SignAndEncryptError': 44,
	'VerifyAndDecryptError': 45,
	'CanNotReadMessage': 50,
	'CanNotReadKey': 51,
	'DeleteError': 60,
	'PublicKeyNotFoundNotice': 70,
	'PrivateKeyNotFoundNotice': 71,
	'VerifyErrorNotice': 72,
	'NoSignDataNotice': 73
};

/**
 * @type {mixed}
 */
OpenPgpResult.prototype.result = false;

/**
 * @type {Array|null}
 */
OpenPgpResult.prototype.errors = null;

/**
 * @type {Array|null}
 */
OpenPgpResult.prototype.notices = null;

/**
 * @param {number} iCode
 * @param {string} sValue
 * @return {OpenPgpResult}
 */
OpenPgpResult.prototype.addError = function (iCode, sValue)
{
	this.result = false;
	this.errors = this.errors || [];
	this.errors.push([iCode || OpenPgpResult.Enum.UnknownError, sValue || '']);

	return this;
};

/**
 * @param {number} iCode
 * @param {string} sValue
 * @return {OpenPgpResult}
 */
OpenPgpResult.prototype.addNotice = function (iCode, sValue)
{
	this.notices = this.notices || [];
	this.notices.push([iCode || OpenPgpResult.Enum.UnknownNotice, sValue || '']);

	return this;
};

/**
 * @param {Error} e
 * @param {number=} iErrorCode
 * @param {string=} sErrorMessage
 * @return {OpenPgpResult}
 */
OpenPgpResult.prototype.addExceptionMessage = function (e, iErrorCode, sErrorMessage)
{
	if (e)
	{
		this.result = false;
		this.exceptions = this.exceptions || [];
		this.exceptions.push('' + (e.name || 'unknown') + ': ' + (e.message || ''));
	}

	if (!Utils.isUnd(iErrorCode))
	{
		this.addError(iErrorCode, sErrorMessage);
	}

	return this;
};

/**
 *  @return {boolean}
 */
OpenPgpResult.prototype.hasErrors = function ()
{
	return this.errors && 0 < this.errors.length;
};

/**
 *  @return {boolean}
 */
OpenPgpResult.prototype.hasNotices = function ()
{
	return this.notices && 0 < this.notices.length;
};

/**
 * @constructor
 */
function AlertPopup()
{
	this.alertDesc = ko.observable('');
	this.closeCallback = null;
	this.title = ko.observable('');
	this.okButtonText = ko.observable(Utils.i18n('MAIN/BUTTON_OK'));
}

/**
 * @param {string} sDesc
 * @param {Function=} fCloseCallback = null
 * @param {string=} sTitle = ''
 * @param {string=} sOkButtonText = 'Ok'
 */
AlertPopup.prototype.onShow = function (sDesc, fCloseCallback, sTitle, sOkButtonText)
{
	this.alertDesc(sDesc);
	this.closeCallback = fCloseCallback || null;
	this.title(sTitle || '');
	this.okButtonText(sOkButtonText || Utils.i18n('MAIN/BUTTON_OK'));
};

/**
 * @return {string}
 */
AlertPopup.prototype.popupTemplate = function ()
{
	return 'Popups_AlertPopupViewModel';
};

AlertPopup.prototype.onEnterHandler = function ()
{
	this.close();
};

AlertPopup.prototype.close = function ()
{
	if (Utils.isFunc(this.closeCallback))
	{
		this.closeCallback();
	}
	this.closeCommand();
};

/**
 * @constructor
 */
function ConfirmPopup()
{
	this.fConfirmCallback = null;
	this.confirmDesc = ko.observable('');
	this.title = ko.observable('');
	this.okButtonText = ko.observable(Utils.i18n('MAIN/BUTTON_OK'));
	this.cancelButtonText = ko.observable(Utils.i18n('MAIN/BUTTON_CANCEL'));
	this.shown = false;
}

/**
 * @param {string} sDesc
 * @param {Function} fConfirmCallback
 * @param {string=} sTitle = ''
 * @param {string=} sOkButtonText = ''
 * @param {string=} sCancelButtonText = ''
 */
ConfirmPopup.prototype.onShow = function (sDesc, fConfirmCallback, sTitle, sOkButtonText, sCancelButtonText)
{
	this.title(sTitle || '');
	this.okButtonText(sOkButtonText || Utils.i18n('MAIN/BUTTON_OK'));
	this.cancelButtonText(sCancelButtonText || Utils.i18n('MAIN/BUTTON_CANCEL'));
	if (Utils.isFunc(fConfirmCallback))
	{
		this.fConfirmCallback = fConfirmCallback;
		this.confirmDesc(sDesc);
	}
	this.shown = true;
};

ConfirmPopup.prototype.onHide = function ()
{
	this.shown = false;
};

/**
 * @return {string}
 */
ConfirmPopup.prototype.popupTemplate = function ()
{
	return 'Popups_ConfirmPopupViewModel';
};

ConfirmPopup.prototype.onEnterHandler = function ()
{
	this.yesClick();
};

ConfirmPopup.prototype.yesClick = function ()
{
	if (this.shown && this.fConfirmCallback)
	{
		this.fConfirmCallback(true);
	}

	this.closeCommand();
};

ConfirmPopup.prototype.noClick = function ()
{
	if (this.fConfirmCallback)
	{
		this.fConfirmCallback(false);
	}

	this.closeCommand();
};

ConfirmPopup.prototype.onEscHandler = function ()
{
	this.noClick();
};

/**
 * @constructor
 */
function CImportOpenPgpKeyPopup()
{
	this.pgp = null;
	this.keyArmor = ko.observable('');
	this.keyArmorFocused = ko.observable(false);
	this.keys = ko.observableArray([]);
	this.hasExistingKeys = ko.observable(false);
	this.headlineText = ko.computed(function () {
		return Utils.i18n('OPENPGP/INFO_TEXT_INCLUDES_KEYS_PLURAL', {}, null, this.keys().length);
	}, this);
}

/**
 * @param {Object} oPgp
 */
CImportOpenPgpKeyPopup.prototype.onShow = function (oPgp, sArmor)
{
	this.pgp = oPgp;
	this.keyArmor(sArmor || '');
	this.keyArmorFocused(true);
	this.keys([]);
	this.hasExistingKeys(false);
	if (this.keyArmor() !== '')
	{
		this.checkArmor();
	}
};

/**
 * @return {string}
 */
CImportOpenPgpKeyPopup.prototype.popupTemplate = function ()
{
	return 'Popups_ImportOpenPgpKeyPopupViewModel';
};

CImportOpenPgpKeyPopup.prototype.checkArmor = function ()
{
	var
		aRes = null,
		aKeys = [],
		oPgp = this.pgp,
		bHasExistingKeys = false
	;
	
	if (this.keyArmor() === '')
	{
		this.keyArmorFocused(true);
	}
	else if (oPgp)
	{
		aRes = oPgp.getArmorInfo(this.keyArmor());
		
		if (Utils.isNonEmptyArray(aRes))
		{
			_.each(aRes, function (oKey) {
				if (oKey)
				{
					var
						oSameKey = oPgp.findKeyByID(oKey.getId(), oKey.isPublic()),
						bHasSameKey = (oSameKey !== null),
						sAddInfoLangKey = oKey.isPublic() ? 'OPENPGP/PUBLIC_KEY_ADD_INFO' : 'OPENPGP/PRIVATE_KEY_ADD_INFO'
					;
					bHasExistingKeys = bHasExistingKeys || bHasSameKey;
					aKeys.push({
						'armor': oKey.getArmor(),
						'email': oKey.user,
						'id': oKey.getId(),
						'addInfo': Utils.i18n(sAddInfoLangKey, {'LENGTH': oKey.getBitSize()}),
						'needToImport': ko.observable(!bHasSameKey),
						'disabled': bHasSameKey
					});
				}
			});
		}
		
		if (aKeys.length === 0)
		{
			App.Api.showError(Utils.i18n('OPENPGP/ERROR_IMPORT_NO_KEY_FOUNDED'));
		}
		
		this.keys(aKeys);
		this.hasExistingKeys(bHasExistingKeys);
	}
};

CImportOpenPgpKeyPopup.prototype.importKey = function ()
{
	var
		oRes = null,
		aArmors = []
	;
	if (this.pgp)
	{
		
		_.each(this.keys(), function (oSimpleKey) {
			if (oSimpleKey.needToImport())
			{
				aArmors.push(oSimpleKey.armor);
			}
		});
		
		if (aArmors.length > 0)
		{
			oRes = this.pgp.importKeys(aArmors.join(''));

			if (oRes && oRes.result)
			{
				App.Api.showReport(Utils.i18n('OPENPGP/REPORT_KEY_SUCCESSFULLY_IMPORTED_PLURAL', {}, null, aArmors.length));
			}

			if (oRes && !oRes.result)
			{
				App.Api.showPgpErrorByCode(oRes, Enums.PgpAction.Import, Utils.i18n('OPENPGP/ERROR_IMPORT_KEY'));
			}

			this.closeCommand();
		}
		else
		{
			App.Api.showError(Utils.i18n('OPENPGP/ERROR_IMPORT_NO_KEY_SELECTED'));
		}
	}
};



/**
 * @constructor
 * @param {boolean} bAllowOpenPgp
 */
function CAppSettingsModel(bAllowOpenPgp)
{
	this.AllowWebMail  = true;

	// allows to edit common settings and calendar settings
	this.AllowUsersChangeInterfaceSettings = true;

	// allows to delete accounts, allows to change account properties (name and password is always possible to change),
	// allows to manage special folders, allows to add new accounts
	this.AllowUsersChangeEmailSettings = true;

	// allows to add new accounts (if AllowUsersChangeEmailSettings === true)
	this.AllowUsersAddNewAccounts = true || this.AllowUsersChangeEmailSettings;
	
	this.SiteName = '';

	// list of available languages
	this.Languages = [
		{name: 'English', value: 'en'}
	];

	// list of available themes
	this.Themes = [
		'Default'
	];

	// list of available date formats
	this.DateFormats = [];
	
	this.DefaultLanguage = 'English';

	// maximum size of uploading attachment
	this.AttachmentSizeLimit = 10240000;
	this.ImageUploadSizeLimit = 10240000;
	
	this.FileSizeLimit = 10240000;

	// activate autosave
	this.AutoSave = true;
	this.AutoSaveIntervalSeconds = 60;
	this.IdleSessionTimeout = 0;
	
	// allows to insert an image to html-text in rich text editor
	this.AllowInsertImage = true;
	this.AllowBodySize = false;
	this.MaxBodySize = 600;
	this.MaxSubjectSize = 255;

	this.AllowPrefetch = true;
	this.MaxPrefetchBodiesSize = 50000;

	this.LoginFormType = Enums.LoginFormType.Email;
	this.LoginAtDomainValue = '';
	this.AllowRegistration = false;
	this.AllowPasswordReset = false;
	this.RegistrationDomains = [];
	this.RegistrationQuestions = [];

	this.DemoWebMail = true;
	this.DemoWebMailLogin = '';
	this.DemoWebMailPassword = '';
	this.LoginDescription = '';
	this.GoogleAnalyticsAccount = '';
	this.ShowQuotaBar = false;
	this.ServerUseUrlRewrite = false;

	this.AllowLanguageOnLogin = false;
	this.FlagsLangSelect = false;
	
	this.CustomLoginUrl = '';
	this.CustomLogoutUrl = '';

	this.IosDetectOnLogin = false;
	
	this.AllowContactsSharing = false;

	this.DefaultLanguageShort = 'en';
	
	this.AllowOpenPgp = bAllowOpenPgp;
	
	this.DefaultTab = '';
	
	this.AllowIosProfile = true;
	
	this.PasswordMinLength = 0;
	this.PasswordMustBeComplex = false;
}
	
/**
 * Parses the application settings from the server.
 * 
 * @param {Object} oData
 */
CAppSettingsModel.prototype.parse = function (oData)
{
	this.AllowWebMail = !!oData.AllowWebMail;
	this.AllowUsersChangeInterfaceSettings = !!oData.AllowUsersChangeInterfaceSettings;
	this.AllowUsersChangeEmailSettings = !!oData.AllowUsersChangeEmailSettings;
	this.AllowUsersAddNewAccounts = !!oData.AllowUsersAddNewAccounts || this.AllowUsersChangeEmailSettings;
	this.SiteName = Utils.pString(oData.SiteName);
	this.Languages = oData.Languages;
	this.Themes = oData.Themes;
	this.DateFormats = oData.DateFormats;
	this.AttachmentSizeLimit = Utils.pInt(oData.AttachmentSizeLimit);
	this.ImageUploadSizeLimit = Utils.pInt(oData.ImageUploadSizeLimit);
	this.FileSizeLimit = Utils.pInt(oData.FileSizeLimit);
	this.AutoSave = !!oData.AutoSave;
	this.IdleSessionTimeout = Utils.pInt(oData.IdleSessionTimeout) * 60 * 1000; // converts minutes to milliseconds
	this.AllowInsertImage = !!oData.AllowInsertImage;
	this.AllowBodySize = !!oData.AllowBodySize;
	this.MaxBodySize = Utils.pInt(oData.MaxBodySize);
	this.MaxSubjectSize = Utils.pInt(oData.MaxSubjectSize);
	this.AllowPrefetch = !!oData.AllowPrefetch;

	this.LoginFormType = Utils.pInt(oData.LoginFormType);
	this.LoginSignMeType = Utils.pInt(oData.LoginSignMeType);
	this.LoginAtDomainValue = Utils.pString(oData.LoginAtDomainValue);
	this.AllowRegistration = !!oData.AllowRegistration;
	this.AllowPasswordReset = !!oData.AllowPasswordReset;
	this.RegistrationDomains = oData.RegistrationDomains;
	this.RegistrationQuestions = _.without(oData.RegistrationQuestions, '');
	
	this.DemoWebMail = !!oData.DemoWebMail;
	this.DemoWebMailLogin = Utils.pString(oData.DemoWebMailLogin);
	this.DemoWebMailPassword = Utils.pString(oData.DemoWebMailPassword);
	this.GoogleAnalyticsAccount = oData.GoogleAnalyticsAccount;
	this.ShowQuotaBar = !!oData.ShowQuotaBar;
	this.ServerUseUrlRewrite = !!oData.ServerUseUrlRewrite;

	this.AllowLanguageOnLogin = !bMobileApp && !!oData.AllowLanguageOnLogin;
	this.FlagsLangSelect = !!oData.FlagsLangSelect;

	this.DefaultLanguage = Utils.pString(oData.DefaultLanguage);
	this.LoginDescription = Utils.pString(oData.LoginDescription);
	
	this.CustomLoginUrl = Utils.pString(oData.CustomLoginUrl);
	this.CustomLogoutUrl = Utils.pString(oData.CustomLogoutUrl);

	this.IosDetectOnLogin = !!oData.IosDetectOnLogin;

	this.AllowContactsSharing = !!oData.AllowContactsSharing;

	if (oData.DefaultLanguageShort !== '')
	{
		this.DefaultLanguageShort = oData.DefaultLanguageShort;
	}
	this.DefaultTab = oData.DefaultTab;
	this.AllowIosProfile = !!oData.AllowIosProfile;
	this.PasswordMinLength = oData.PasswordMinLength;
	this.PasswordMustBeComplex = !!oData.PasswordMustBeComplex;
};

/**
 * @constructor
 */
function CUserSettingsModel()
{
	this.IdUser = 1;

	// general settings that can be changed in the settings screen
	this.MailsPerPage = 20;
	this.ContactsPerPage = 20;
	this.iInterval = -1;
	this.AutoCheckMailInterval = 0;
	this.DefaultTheme = 'Default';
	this.DefaultLanguage = 'English';
	this.DefaultLanguageShort = 'en';
	this.DefaultDateFormat = 'MM/DD/YYYY';
	this.defaultTimeFormat = ko.observable(Enums.TimeFormat.F24);
	this.ThreadsEnabled = true;
	this.useThreads = ko.observable(true);
	this.SaveRepliedToCurrFolder = true;
	this.AllowChangeInputDirection = false;
	this.DesktopNotifications = false;

	// allows the creation of messages
	this.AllowCompose = true;

	this.AllowReply = true;
	this.AllowForward = true;
	this.SaveMail = Enums.SaveMail.Checked;

	this.AllowFetcher = false;

	this.OutlookSyncEnable = true;
	this.MobileSyncEnable = true;

	this.ShowPersonalContacts = true;
	this.ShowGlobalContacts = false;
	
	this.IsFilesSupported = false;
	this.IsHelpdeskSupported = false;
	this.IsHelpdeskAgent = false;
	this.HelpdeskIframeUrl = '';

	// allows to go to contacts screen and edit their settings
	this.ShowContacts = this.ShowPersonalContacts || this.ShowGlobalContacts;

	this.LastLogin = 0;
	this.IsDemo = false;

	this.AllowVoice = false;
	this.SipRealm = '';
	this.SipWebsocketProxyUrl = '';
	this.SipOutboundProxyUrl = '';
	this.SipCallerID = '';
	this.SipImpi = '';
	this.SipImpu = '';
	this.SipPassword = '';
	
	this.VoiceProvider = '';
//	this.VoiceAccountSID = '';
//	this.VoiceAuthToken = '';
//	this.VoiceAppSID = '';

	// allows to go to calendar screen and edit its settings
	this.AllowCalendar = true;
	
	this.CalendarSharing = false;
	this.CalendarAppointments = false;

	// calendar settings that can be changed in the settings screen
	this.CalendarShowWeekEnds = false;
	this.CalendarShowWorkDay = false;
	this.CalendarWorkDayStarts = 0;
	this.CalendarWorkDayEnds = 0;
	this.CalendarWeekStartsOn = 0;
	this.CalendarDefaultTab = Enums.CalendarDefaultTab.Month;
	
	this.mobileSync = ko.observable(null);
	this.MobileSyncDemoPass = 'demo';
	this.outlookSync = ko.observable(null);
	this.OutlookSyncDemoPass = 'demo';
	
	this.AllowHelpdeskNotifications = false;
	
	this.IsCollaborationSupported = false;
	this.AllowFilesSharing = false;
	
	this.DefaultFontName = 'Tahoma';
	this.fillDefaultFontName();
	
	this.DefaultFontSize = 3;
	this.fillDefaultFontSize();
	
	this.enableOpenPgp = ko.observable(false);
	this.AllowAutosaveInDrafts = true;
	this.AutosignOutgoingEmails = false;
	
	this.filesEnable = ko.observable(true);
	this.SocialAccounts = ko.observableArray([]);
}

CUserSettingsModel.prototype.fillDefaultFontName = function ()
{
	var sDefaultFontName = Utils.pString(AppData.HtmlEditorDefaultFontName);
	
	if (sDefaultFontName !== '')
	{
		this.DefaultFontName = sDefaultFontName;
	}
};

CUserSettingsModel.prototype.fillDefaultFontSize = function ()
{
	var iDefaultFontSize = Utils.pInt(AppData.HtmlEditorDefaultFontSize);
	
	if (Utils.inArray(iDefaultFontSize, [2, 3, 5, 7]) !== -1)
	{
		this.DefaultFontSize = iDefaultFontSize;
	}
};

/**
 * @return {boolean}
 */
CUserSettingsModel.prototype.getSaveMailInSentItems = function ()
{
	var bSaveMailInSentItems = true;
	
	switch (this.SaveMail)
	{
		case Enums.SaveMail.Unchecked:
			bSaveMailInSentItems = false;
			break;
		case Enums.SaveMail.Checked:
		case Enums.SaveMail.Hidden:
			bSaveMailInSentItems = true;
			break;
	}
	
	return bSaveMailInSentItems;
};

/**
 * @return {boolean}
 */
CUserSettingsModel.prototype.getUseSaveMailInSentItems = function ()
{
	var bUseSaveMailInSentItems = false;
	
	switch (this.SaveMail)
	{
		case Enums.SaveMail.Unchecked:
		case Enums.SaveMail.Checked:
			bUseSaveMailInSentItems = true;
			break;
		case Enums.SaveMail.Hidden:
			bUseSaveMailInSentItems = false;
			break;
	}
	
	return bUseSaveMailInSentItems;
};

/**
 * @param {AjaxUserSettingsResponse} oData
 */
CUserSettingsModel.prototype.parse = function (oData)
{
	var oCalendar = null;

	if (oData !== null)
	{
		this.IdUser = Utils.pInt(oData.IdUser);
		this.MailsPerPage = Utils.pInt(oData.MailsPerPage);
		this.ContactsPerPage = Utils.pInt(oData.ContactsPerPage);
		this.AutoCheckMailInterval = Utils.pInt(oData.AutoCheckMailInterval);
		this.DefaultTheme = Utils.pString(oData.DefaultTheme);
		this.DefaultLanguage = Utils.pString(oData.DefaultLanguage);
		this.DefaultLanguageShort = Utils.pString(oData.DefaultLanguageShort);
		this.DefaultDateFormat = Utils.pString(oData.DefaultDateFormat);
		this.defaultTimeFormat(Utils.pString(oData.DefaultTimeFormat));
		this.ThreadsEnabled = !!oData.ThreadsEnabled;
		this.useThreads(!!oData.UseThreads);
		this.SaveRepliedToCurrFolder = !!oData.SaveRepliedMessagesToCurrentFolder;
		this.DesktopNotifications = !!oData.DesktopNotifications;
		this.AllowChangeInputDirection = !!oData.AllowChangeInputDirection;
		this.AllowCompose = !!oData.AllowCompose;
		this.AllowReply = !!oData.AllowReply;
		this.AllowForward = !!oData.AllowForward;
		this.SaveMail = Utils.pInt(oData.SaveMail);

		this.AllowFetcher = !!oData.AllowFetcher;

		this.OutlookSyncEnable = !!oData.OutlookSyncEnable;
		this.MobileSyncEnable = !!oData.MobileSyncEnable;
		this.ShowPersonalContacts = !!oData.ShowPersonalContacts;
		this.ShowGlobalContacts = !!oData.ShowGlobalContacts;
		this.ShowContacts = this.ShowPersonalContacts || this.ShowGlobalContacts;
		
		this.IsFilesSupported = !!oData.IsFilesSupported && !bMobileApp;
		this.filesEnable(!!oData.FilesEnable && !bMobileApp);
		this.IsHelpdeskSupported = !!oData.IsHelpdeskSupported && !bMobileApp;
		this.IsHelpdeskAgent = !!oData.IsHelpdeskAgent;
		
		this.LastLogin = Utils.pInt(oData.LastLogin);
		this.AllowCalendar = !!oData.AllowCalendar && !bMobileApp;

		this.CalendarSharing = !!oData.CalendarSharing;
		this.CalendarAppointments = !!oData.CalendarAppointments;
		
		this.IsDemo = !!oData.IsDemo;

		this.AllowVoice = !!oData.AllowVoice;
		this.SipRealm = oData.SipRealm;
		this.SipWebsocketProxyUrl = oData.SipWebsocketProxyUrl;
		this.SipOutboundProxyUrl = oData.SipOutboundProxyUrl;
		this.SipCallerID = oData.SipCallerID;
		this.SipImpi = oData.SipImpi;
		this.SipImpu = oData.SipImpu;
		this.SipPassword = oData.SipPassword;
		
		this.VoiceProvider = oData.VoiceProvider;
//		this.VoiceAccountSID = oData.VoiceRealm;
//		this.VoiceAuthToken = oData.VoiceAuthToken;
//		this.VoiceAppSID = oData.VoiceAppSID;
		
		this.AllowHelpdeskNotifications = oData.AllowHelpdeskNotifications;
		this.IsCollaborationSupported = !!oData.IsCollaborationSupported;
		this.AllowFilesSharing = !!oData.AllowFilesSharing;
		
		this.enableOpenPgp(!!oData.EnableOpenPgp);
		this.AllowAutosaveInDrafts = !!oData.AllowAutosaveInDrafts && (AppData.App ? AppData.App.AutoSave : false);
//		this.AutosignOutgoingEmails = !!oData.AutosignOutgoingEmails;

		oCalendar = oData.Calendar;
		if (oCalendar)
		{
			this.CalendarShowWeekEnds = !!oCalendar.ShowWeekEnds;
			this.CalendarShowWorkDay = !!oCalendar.ShowWorkDay;
			this.CalendarWorkDayStarts = Utils.pInt(oCalendar.WorkDayStarts);
			this.CalendarWorkDayEnds = Utils.pInt(oCalendar.WorkDayEnds);
			this.CalendarWeekStartsOn = Utils.pInt(oCalendar.WeekStartsOn);
			this.CalendarDefaultTab = Utils.pInt(oCalendar.DefaultTab);
		}
		
		this.SocialAccounts(oData.SocialAccounts);
	}
};

/**
 * @param {number} iMailsPerPage
 * @param {number} iContactsPerPage
 * @param {number} iAutoCheckMailInterval
 * @param {string} sDefaultTheme
 * @param {string} sDefaultLanguage
 * @param {string} sDefaultDateFormat
 * @param {string} sDefaultTimeFormat
 * @param {string} sUseThreads
 * @param {string} sSaveRepliedToCurrFolder
 * @param {string} sDesktopNotifications
 * @param {string} sAllowChangeInputDirection
 */
CUserSettingsModel.prototype.updateCommonSettings = function (iMailsPerPage, iContactsPerPage,
	iAutoCheckMailInterval, sDefaultTheme, sDefaultLanguage, sDefaultDateFormat, sDefaultTimeFormat, 
	sUseThreads, sSaveRepliedToCurrFolder, sDesktopNotifications, sAllowChangeInputDirection)
{
	var bNeedToUpdateMessageDates = this.defaultTimeFormat() !== sDefaultTimeFormat;
	
	this.MailsPerPage = iMailsPerPage;
	this.ContactsPerPage = iContactsPerPage;
	this.AutoCheckMailInterval = iAutoCheckMailInterval;
	App.MailCache.setAutocheckmailTimer();
	this.DefaultTheme = sDefaultTheme;
	this.DefaultLanguage = sDefaultLanguage;
	this.DefaultDateFormat = sDefaultDateFormat;
	this.defaultTimeFormat(sDefaultTimeFormat);
	this.useThreads('1' === sUseThreads);
	
	this.SaveRepliedToCurrFolder = '1' === sSaveRepliedToCurrFolder;
	this.AllowChangeInputDirection = '1' === sAllowChangeInputDirection;
	this.DesktopNotifications = '1' === sDesktopNotifications;
	
	if (bNeedToUpdateMessageDates)
	{
		App.nowMoment.valueHasMutated();
	}
};

/**
 * @param {string} sEnableOpenPgp
 * @param {string} sAllowAutosaveInDrafts
 * @param {string} sAutosignOutgoingEmails
 */
CUserSettingsModel.prototype.updateOpenPgpSettings = function (sEnableOpenPgp, sAllowAutosaveInDrafts, sAutosignOutgoingEmails)
{
	this.enableOpenPgp('1' === sEnableOpenPgp);
	this.AllowAutosaveInDrafts = '1' === sAllowAutosaveInDrafts;
//	this.AutosignOutgoingEmails = '1' === sAutosignOutgoingEmails;
};

/**
 * @param {boolean} bShowWeekEnds
 * @param {boolean} bShowWorkDay
 * @param {number} iWorkDayStarts
 * @param {number} iWorkDayEnds
 * @param {number} iWeekStartsOn
 * @param {number} iDefaultTab
 */
CUserSettingsModel.prototype.updateCalendarSettings = function (bShowWeekEnds, bShowWorkDay,
		iWorkDayStarts, iWorkDayEnds, iWeekStartsOn, iDefaultTab)
{
	this.CalendarShowWeekEnds = bShowWeekEnds;
	this.CalendarShowWorkDay = bShowWorkDay;
	this.CalendarWorkDayStarts = iWorkDayStarts;
	this.CalendarWorkDayEnds = iWorkDayEnds;
	this.CalendarWeekStartsOn = iWeekStartsOn;
	this.CalendarDefaultTab = iDefaultTab;
};

/**
 * @param {boolean} bAllowHelpdeskNotifications
 */
CUserSettingsModel.prototype.updateHelpdeskSettings = function (bAllowHelpdeskNotifications)
{
	this.AllowHelpdeskNotifications = bAllowHelpdeskNotifications;
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CUserSettingsModel.prototype.onUserSettingsGetSyncResponse = function (oResponse, oRequest)
{
	if (oResponse.Result)
	{
		this.mobileSync(oResponse.Result.Mobile);
		this.outlookSync(oResponse.Result.Outlook);
	}
	else
	{
		App.Api.showErrorByCode(oResponse);
	}
};

CUserSettingsModel.prototype.requestSyncSettings = function ()
{
	if (this.mobileSync() === null || this.outlookSync() === null)
	{
		App.Ajax.send({'Action': 'UserSettingsGetSync'}, this.onUserSettingsGetSyncResponse, this);
	}
};


/**
 * @constructor
 */
function CAccountModel()
{
	this.id = ko.observable(0);
	this.email = ko.observable('');
	
	this.extensions = ko.observableArray([]);
	this.fetchers = ko.observable(null);
	this.identities = ko.observable(null);
	this.friendlyName = ko.observable('');
	this.incomingMailLogin = ko.observable('');
	this.incomingMailPort = ko.observable(143); 
	this.incomingMailServer = ko.observable('');
	this.isInternal = ko.observable(false);
	this.isLinked = ko.observable(false);
	this.isDefault = ko.observable(false);
	this.outgoingMailAuth = ko.observable(0);
	this.outgoingMailLogin = ko.observable('');
	this.outgoingMailPort = ko.observable(25);
	this.outgoingMailServer = ko.observable('');
	this.isExtended = ko.observable(false);
	this.signature = ko.observable(null);
	this.autoresponder = ko.observable(null);
	this.forward = ko.observable(null);
	this.filters = ko.observable(null);

	this.quota = ko.observable(0);
	this.usedSpace = ko.observable(0);
	this.quotaRecieved = ko.observable(false);

	this.fullEmail = ko.computed(function () {
		if (this.friendlyName() === '')
		{
			return this.email();
		}
		else
		{
			return this.friendlyName() + ' <' + this.email() + '>';
		}
	}, this);
	
	this.isCurrent = ko.observable(false);
	this.isEdited = ko.observable(false);
	
	this.extensionsRequested = ko.observable(false);
	
	this.removeHint = ko.computed(function () {
		var
			sAndOther = '',
			sHint = ''
		;
		
		if (this.isDefault())
		{
			if (AppData.User.AllowCalendar && AppData.User.ShowContacts)
			{
				sAndOther = Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_CONTACTS_CALENDARS_HINT');
			}
			else if (AppData.User.AllowCalendar)
			{
				sAndOther = Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_CALENDARS_HINT');
			}
			else if (AppData.User.ShowContacts)
			{
				sAndOther = Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_CONTACTS_HINT');
			}
			sHint = Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_DEFAULT_HINT', {'AND_OTHER': sAndOther});
			
			if (AppData.Accounts.collection().length > 1)
			{
				sHint += Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_DEFAULT_NOTSINGLE_HINT');
			}
		}
		else
		{
			sHint = Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_HINT');
		}
		
		return sHint;
	}, this);
	
	this.removeConfirmation = ko.computed(function () {
		if (this.isDefault())
		{
			return this.removeHint() + Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_DEFAULT_CONFIRMATION');
		}
		else
		{
			return Utils.i18n('SETTINGS/ACCOUNTS_REMOVE_CONFIRMATION');
		}
	}, this);
}

/**
 * @param {number} iId
 * @param {string} sEmail
 * @param {string} sFriendlyName
 */
CAccountModel.prototype.init = function (iId, sEmail, sFriendlyName)
{
	this.id(iId);
	this.email(sEmail);
	this.friendlyName(sFriendlyName);
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CAccountModel.prototype.onAccountGetQuotaResponse = function (oData, oParameters)
{
	if (oData && oData.Result && _.isArray(oData.Result) && 1 < oData.Result.length)
	{
		this.quota(Utils.pInt(oData.Result[1]));
		this.usedSpace(Utils.pInt(oData.Result[0]));

		App.MailCache.quotaChangeTrigger(!App.MailCache.quotaChangeTrigger());
	}
	
	this.quotaRecieved(true);
};

CAccountModel.prototype.updateQuotaParams = function ()
{
	var
		oParams = {
			'Action': 'AccountGetQuota',
			'AccountID': this.id()
		}
	;
	
	if (AppData.App && AppData.App.ShowQuotaBar)
	{
		App.Ajax.send(oParams, this.onAccountGetQuotaResponse, this);
	}
};

/**
 * @param {Object} oData
 * @param {number} iDefaultId
 */
CAccountModel.prototype.parse = function (oData, iDefaultId)
{
	var oSignature = new CSignatureModel();
	
	this.init(parseInt(oData.AccountID, 10), Utils.pString(oData.Email), 
		Utils.pString(oData.FriendlyName));
	
	oSignature.parse(this.id(), oData.Signature);
	this.signature(oSignature);
	
	this.isCurrent(iDefaultId === this.id());
	this.isEdited(iDefaultId === this.id());
};

CAccountModel.prototype.requestExtensions = function ()
{
	if (!this.extensionsRequested() && App.Ajax)
	{
		var oTz = window.jstz ? window.jstz.determine() : null;
		App.Ajax.send({
			'AccountID': this.id(),
			'Action': 'SystemIsAuth',
			'ClientTimeZone': oTz ? oTz.name() : ''
		}, this.onSystemIsAuthResponse, this);
	}
};

/**
 * @param {Object} oResult
 * @param {Object} oRequest
 */
CAccountModel.prototype.onSystemIsAuthResponse = function (oResult, oRequest)
{
	var
		bResult = !!oResult.Result,
		aExtensions = bResult ? oResult.Result.Extensions : []
	;
	
	if (bResult)
	{
		this.setExtensions(aExtensions);
		this.extensionsRequested(true);
	}
};

/**
 * @param {Array} aExtensions
 */
CAccountModel.prototype.setExtensions = function(aExtensions)
{
	if (_.isArray(aExtensions))
	{
		this.extensions(aExtensions);
	}
};

/**
 * @param {string} sExtension
 * 
 * return {boolean}
 */
CAccountModel.prototype.extensionExists = function(sExtension)
{
	return (_.indexOf(this.extensions(), sExtension) === -1) ? false : true;
};

/**
 * @param {?} ExtendedData
 */
CAccountModel.prototype.updateExtended = function (ExtendedData)
{
	if (ExtendedData)
	{
		this.isExtended(true);
		
		if (Utils.isNormal(ExtendedData.FriendlyName))
		{
			this.friendlyName(ExtendedData.FriendlyName);
		}

		if (Utils.isNormal(ExtendedData.IncomingMailLogin))
		{
			this.incomingMailLogin(ExtendedData.IncomingMailLogin);
		}
		if (Utils.isNormal(ExtendedData.IncomingMailPort))
		{
			this.incomingMailPort(ExtendedData.IncomingMailPort); 
		}		
		if (Utils.isNormal(ExtendedData.IncomingMailServer))
		{
			this.incomingMailServer(ExtendedData.IncomingMailServer);
		}
		if (Utils.isNormal(ExtendedData.IsInternal))
		{
			this.isInternal(ExtendedData.IsInternal);
		}
		if (Utils.isNormal(ExtendedData.IsLinked))
		{
			this.isLinked(ExtendedData.IsLinked);
		}
		if (Utils.isNormal(ExtendedData.IsDefault))
		{
			this.isDefault(ExtendedData.IsDefault);
		}
		if (Utils.isNormal(ExtendedData.OutgoingMailAuth))
		{
			this.outgoingMailAuth(ExtendedData.OutgoingMailAuth);
		}
		if (Utils.isNormal(ExtendedData.OutgoingMailLogin))
		{
			this.outgoingMailLogin(ExtendedData.OutgoingMailLogin);
		}
		if (Utils.isNormal(ExtendedData.OutgoingMailPort))
		{
			this.outgoingMailPort(ExtendedData.OutgoingMailPort);
		}
		if (Utils.isNormal(ExtendedData.OutgoingMailServer))
		{
			this.outgoingMailServer(ExtendedData.OutgoingMailServer);
		}
		this.setExtensions(ExtendedData.Extensions);
	}
};

CAccountModel.prototype.changeAccount = function()
{
	AppData.Accounts.changeCurrentAccount(this.id());
};

CAccountModel.prototype.getDefaultIdentity = function()
{
	return _.find(this.identities() || [], function (oIdentity) {
		return oIdentity.isDefault();
	});
};

/**
 * @returns {Array}
 */
CAccountModel.prototype.getFetchersIdentitiesEmails = function()
{
	var
		aFetchers = this.fetchers() ? this.fetchers().collection() : [],
		aIdentities = this.identities() || [],
		aEmails = []
	;
	
	_.each(aFetchers, function (oFetcher) {
		aEmails.push(oFetcher.email());
	});
	
	_.each(aIdentities, function (oIdentity) {
		aEmails.push(oIdentity.email());
	});
	
	return aEmails;
};

/**
 * @constructor
 */
function CAccountListModel()
{
	this.defaultId = ko.observable(0);
	this.currentId = ko.observable(0);
	this.editedId = ko.observable(0);

	this.currentId.subscribe(function(value) {
		var oCurrentAccount = this.getCurrent();
		oCurrentAccount.requestExtensions();
		
		// deferred execution to edited account has changed a bit later and did not make a second request 
		// of the folder list of the same account.
		_.delay(_.bind(function () {
			this.editedId(value);
		}, this), 1000);
	}, this);

	this.collection = ko.observableArray([]);
}

/**
 * Changes current account. Sets hash to show new account data.
 * 
 * @param {number} iNewCurrentId
 */
CAccountListModel.prototype.changeCurrentAccount = function (iNewCurrentId)
{
	var
		oCurrentAccount = this.getCurrent(),
		oNewCurrentAccount = this.getAccount(iNewCurrentId)
	;

	if (oNewCurrentAccount && this.currentId() !== iNewCurrentId)
	{
		oCurrentAccount.isCurrent(false);
		this.currentId(iNewCurrentId);
		oNewCurrentAccount.isCurrent(true);
		App.Routing.setHash(App.Links.inbox());
	}
};

/**
 * Changes editable account.
 * 
 * @param {number} iNewEditedId
 */
CAccountListModel.prototype.changeEditedAccount = function (iNewEditedId)
{
	var
		oEditedAccount = this.getEdited(),
		oNewEditedAccount = this.getAccount(iNewEditedId)
	;
	
	if (oNewEditedAccount && this.editedId() !== iNewEditedId)
	{
		oEditedAccount.isEdited(false);
		this.editedId(iNewEditedId);
		oNewEditedAccount.isEdited(true);
	}
};

/**
 * Fills the collection of accounts. Checks for default account. If it is not listed, 
 * then assigns a credit default the first account from the list.
 *
 * @param {number} iDefaultId
 * @param {Array} aAccounts
 */
CAccountListModel.prototype.parse = function (iDefaultId, aAccounts)
{
	var
		oAccount = null,
		bHasDefault = false,
		oDefaultAccount = null
	;

	if (_.isArray(aAccounts))
	{
		this.collection(_.map(aAccounts, function (oRawAccount)
		{
			var oAcct = new CAccountModel();
			oAcct.parse(oRawAccount, iDefaultId);
			if (oAcct.id() === iDefaultId)
			{
				bHasDefault = true;
			}
			return oAcct;
		}));
	}

	if (!bHasDefault && this.collection.length > 0)
	{
		oAccount = this.collection()[0];
		iDefaultId = oAccount.id();
		bHasDefault = true;
	}

	if (bHasDefault)
	{
		this.defaultId(iDefaultId);
		this.currentId(iDefaultId);
		this.editedId(iDefaultId);
	}
	
	oDefaultAccount = this.getDefault();
	if (oDefaultAccount)
	{
		_.defer(function () {
			oDefaultAccount.isDefault(true);
		});
	}
};

/**
 * @param {number} iId
 * 
 * @return {Object|undefined}
 */
CAccountListModel.prototype.getAccount = function (iId)
{
	var oAccount = _.find(this.collection(), function (oAcct) {
		return oAcct.id() === iId;
	}, this);
	
	/**	@type {Object|undefined} */
	return oAccount;
};

/**
 * @return {Object|undefined}
 */
CAccountListModel.prototype.getCurrent = function ()
{
	return this.getAccount(this.currentId());
};

/**
 * @return {Object|undefined}
 */
CAccountListModel.prototype.getDefault = function ()
{
	return this.getAccount(this.defaultId());
};

/**
 * @return {Object|undefined}
 */
CAccountListModel.prototype.getEdited = function ()
{
	return this.getAccount(this.editedId());
};

/**
 * @param {number=} iAccountId
 * @return {string}
 */
CAccountListModel.prototype.getEmail = function (iAccountId)
{
	iAccountId = iAccountId || this.currentId();
	
	var
		sEmail = '',
		oAccount = this.getAccount(iAccountId)
	;
	
	if (oAccount)
	{
		sEmail = oAccount.email();
	}
	
	return sEmail;
};

/**
 * @param {Object} oAccount
 */
CAccountListModel.prototype.addAccount = function (oAccount)
{
	this.collection.push(oAccount);
};

/**
 * @param {number} iId
 */
CAccountListModel.prototype.deleteAccount = function (iId)
{
	if (this.currentId() === iId)
	{
		this.changeCurrentAccount(this.defaultId());
	}
	
	if (this.editedId() === iId)
	{
		this.changeEditedAccount(this.defaultId());
	}
	
	this.collection.remove(function (oAcct){return oAcct.id() === iId;});
};

/**
 * @param {number} iId
 * 
 * @return {boolean}
 */
CAccountListModel.prototype.hasAccountWithId = function (iId)
{
	var oAccount = _.find(this.collection(), function (oAcct) {
		return oAcct.id() === iId;
	}, this);

	return !!oAccount;
};

CAccountListModel.prototype.populateFetchersIdentities = function ()
{
	this.populateFetchers();
	this.populateIdentities();
};

CAccountListModel.prototype.populateFetchers = function ()
{
	if (AppData.User.AllowFetcher)
	{
		App.Ajax.send({
			'Action': 'AccountFetcherGetList',
			'AccountID': AppData.Accounts.defaultId()
		}, this.onAccountFetcherGetListResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CAccountListModel.prototype.onAccountFetcherGetListResponse = function (oResponse, oRequest)
{
	var
		oFetcherList = null,
		oDefaultAccount = this.getDefault()
	;

	if (Utils.isNonEmptyArray(oResponse.Result))
	{
		oFetcherList = new CFetcherListModel();
		oFetcherList.parse(AppData.Accounts.defaultId(), oResponse.Result);
	}
	oDefaultAccount.fetchers(oFetcherList);
};

CAccountListModel.prototype.populateIdentities = function ()
{
	if (AppData.AllowIdentities)
	{
		App.Ajax.send({'Action': 'AccountIdentitiesGet'}, this.onAccountIdentitiesGetResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CAccountListModel.prototype.onAccountIdentitiesGetResponse = function (oResponse, oRequest)
{
	var oIdentities = {};
	
	if (Utils.isNonEmptyArray(oResponse.Result))
	{
		_.each(oResponse.Result, function (oIdentityData) {
			var
				oIdentity = new CIdentityModel(),
				iAccountId = -1
			;

			oIdentity.parse(oIdentityData);
			iAccountId = oIdentity.accountId();
			if (!oIdentities[iAccountId])
			{
				oIdentities[iAccountId] = [];
			}
			oIdentities[iAccountId].push(oIdentity);
		});
	}

	_.each(this.collection(), function (oAccount) {
		var
			aIdentities = oIdentities[oAccount.id()],
			oIdentity = new CIdentityModel()
		;

		if (!Utils.isNonEmptyArray(aIdentities))
		{
			aIdentities = [];
		}

		oIdentity.parse({
			'@Object': 'Object/CIdentity',
			Loyal: true,
			Default: !_.find(aIdentities, function(oIdentity){ return oIdentity.isDefault(); }),
			Email: oAccount.email(),
			Enabled: true,
			FriendlyName: oAccount.friendlyName(),
			IdAccount: oAccount.id(),
			IdIdentity: oAccount.id() * 100000,
			Signature: oAccount.signature() ? oAccount.signature().signature() : '',
			UseSignature: oAccount.signature() ? !!oAccount.signature().options() : false
		});
		aIdentities.unshift(oIdentity);

		oAccount.identities(aIdentities);
	});
};

CAccountListModel.prototype.populateIdentitiesFromSourceAccount = function (oSrcAccounts)
{
	if (oSrcAccounts)
	{
		_.each(this.collection(), function (oAccount) {
			var oSrcAccount = oSrcAccounts.getAccount(oAccount.id());
			if (oSrcAccount)
			{
				oAccount.fetchers(oSrcAccount.fetchers());
				oAccount.identities(oSrcAccount.identities());
				oAccount.signature(oSrcAccount.signature());
			}
		});
	}
};

CAccountListModel.prototype.getAllFullEmails = function ()
{
	var aFullEmails = [];
	
	_.each(this.collection(), function (oAccount) {
		if (oAccount)
		{
			aFullEmails.push(oAccount.fullEmail());
			if (oAccount.fetchers() && Utils.isNonEmptyArray(oAccount.fetchers().collection()))
			{
				_.each(oAccount.fetchers().collection(), function (oFetcher) {
					if (oFetcher.isOutgoingEnabled() && oFetcher.fullEmail() !== '')
					{
						aFullEmails.push(oFetcher.fullEmail());
					}
				});
			}
			if (Utils.isNonEmptyArray(oAccount.identities()))
			{
				_.each(oAccount.identities(), function (oIdentity) {
					aFullEmails.push(oIdentity.fullEmail());
				});
			}
		}
	});
	
	return aFullEmails;
};

CAccountListModel.prototype.getCurrentFetchersAndFiltersFolderNames = function ()
{
	var
		oAccount = this.getCurrent(),
		aFolders = []
	;
	
	if (oAccount)
	{
		if (oAccount.filters())
		{
			_.each(oAccount.filters().collection(), function (oFilter) {
				aFolders.push(oFilter.folder());
			}, this);
		}

		if (oAccount.fetchers())
		{
			_.each(oAccount.fetchers().collection(), function (oFetcher) {
				aFolders.push(oFetcher.folder());
			}, this);
		}
	}
	
	return aFolders;
};

/**
 * @param {Array} aEmails
 * @returns {string}
 */
CAccountListModel.prototype.getAttendee = function (aEmails)
{
	var
		aAccountsEmails = [],
		sAttendee = ''
	;
	
	_.each(this.collection(), function (oAccount) {
		if (oAccount.isCurrent())
		{
			aAccountsEmails = _.union(oAccount.email(), oAccount.getFetchersIdentitiesEmails(), aAccountsEmails);
		}
		else
		{
			aAccountsEmails = _.union(aAccountsEmails, oAccount.email(), oAccount.getFetchersIdentitiesEmails());
		}
	});
	
	aAccountsEmails = _.uniq(aAccountsEmails);
	
	_.each(aAccountsEmails, _.bind(function (sAccountEmail) {
		if (sAttendee === '')
		{
			var sFoundEmail = _.find(aEmails, function (sEmail) {
				return (sEmail === sAccountEmail);
			});
			if (sFoundEmail === sAccountEmail)
			{
				sAttendee = sAccountEmail;
			}
		}
	}, this));
	
	return sAttendee;
};


/**
 * @constructor
 */
function CAddressModel()
{
	this.sName = '';
	/** @type {string} */
	this.sEmail = '';
	
	this.sDisplay = '';
	this.sFull = '';
	
	this.loaded = ko.observable(false);
	this.founded = ko.observable(false);
}

/**
 * @param {Object} oData
 */
CAddressModel.prototype.parse = function (oData)
{
	if (oData !== null)
	{
		this.sName = Utils.pString(oData.DisplayName);
		if (typeof this.sName !== 'string')
		{
			this.sName = '';
		}
		this.sEmail = Utils.pString(oData.Email);
		if (typeof this.sEmail !== 'string')
		{
			this.sEmail = '';
		}
		
		this.sDisplay = (this.sName.length > 0) ? this.sName : this.sEmail;
		this.setFull();
	}
};

/**
 * @return {string}
 */
CAddressModel.prototype.getEmail = function ()
{
	return this.sEmail;
};

/**
 * @return {string}
 */
CAddressModel.prototype.getName = function ()
{
	return this.sName;
};

/**
 * @return {string}
 */
CAddressModel.prototype.getDisplay = function ()
{
	return this.sDisplay;
};

CAddressModel.prototype.setFull = function ()
{
	var sFull = '';
	
	if (this.sEmail.length > 0)
	{
		if (this.sName.length > 0)
		{
			if (this.sName.indexOf(',') !== -1)
			{
				sFull = '"' + this.sName + '" <' + this.sEmail + '>';
			}
			else
			{
				sFull = this.sName + ' <' + this.sEmail + '>';
			}
		}
		else
		{
			sFull = this.sEmail;
		}
	}
	else
	{
		sFull = this.sName;
	}
	
	this.sFull = sFull;
};

/**
 * @return {string}
 */
CAddressModel.prototype.getFull = function ()
{
	return this.sFull;
};


/**
 * @constructor
 */
function CAddressListModel()
{
	this.aCollection = [];
}

/**
 * @param {Array} aData
 */
CAddressListModel.prototype.parse = function (aData)
{
	this.aCollection = _.map(aData, function (oItem) {
		var oAddress = new CAddressModel();
		oAddress.parse(oItem);
		return oAddress;
	});
};

/**
 * @param {Array} aCollection
 */
CAddressListModel.prototype.addCollection = function (aCollection)
{
	_.each(aCollection, function (oAddress) {
		var oFoundAddress = _.find(this.aCollection, function (oThisAddress) {
			return oAddress.sEmail === oThisAddress.sEmail;
		});
		
		if (!oFoundAddress)
		{
			this.aCollection.push(oAddress);
		}
	}, this);
};

/**
 * @param {Array} aCollection
 */
CAddressListModel.prototype.excludeCollection = function (aCollection)
{
	_.each(aCollection, function (oAddress) {
		this.aCollection = _.filter(this.aCollection, function (oThisAddress) {
			return oAddress.sEmail !== oThisAddress.sEmail;
		});
	}, this);
};

/**
 * @return {string}
 */
CAddressListModel.prototype.getFirstEmail = function ()
{
	if (this.aCollection.length > 0)
	{
		return this.aCollection[0].getEmail();
	}
	
	return '';
};

/**
 * @return {string}
 */
CAddressListModel.prototype.getFirstName = function ()
{
	if (this.aCollection.length > 0)
	{
		return this.aCollection[0].getName();
	}
	
	return '';
};

/**
 * @return {string}
 */
CAddressListModel.prototype.getFirstDisplay = function ()
{
	if (this.aCollection.length > 0)
	{
		return this.aCollection[0].getDisplay();
	}
	
	return '';
};

/**
 * @param {string=} sMeReplacement
 * @param {string=} sMyAccountEmail
 * 
 * @return {string}
 */
CAddressListModel.prototype.getDisplay = function (sMeReplacement, sMyAccountEmail)
{
	var aAddresses = _.map(this.aCollection, function (oAddress) {
		if (sMeReplacement && sMyAccountEmail === oAddress.sEmail)
		{
			return sMeReplacement;
		}
		return oAddress.getDisplay(sMeReplacement);
	});
	
	return aAddresses.join(', ');
};

/**
 * @return {string}
 */
CAddressListModel.prototype.getFull = function ()
{
	var aAddresses = _.map(this.aCollection, function (oAddress) {
		return oAddress.getFull();
	});
	
	return aAddresses.join(', ');
};

/**
 * @return {Array}
 */
CAddressListModel.prototype.getEmails = function ()
{
	var aEmails = _.map(this.aCollection, function (oAddress) {
		return oAddress.getEmail();
	});
	
	return aEmails;
};


/**
 * @constructor
 */
function CDateModel()
{
	this.iTimeStampInUTC = 0;
	this.oMoment = null;
}

/**
 * @param {number} iTimeStampInUTC
 */
CDateModel.prototype.parse = function (iTimeStampInUTC)
{
	this.iTimeStampInUTC = iTimeStampInUTC;
	this.oMoment = moment.unix(this.iTimeStampInUTC);
};

/**
 * @param {number} iYear
 * @param {number} iMonth
 * @param {number} iDay
 */
CDateModel.prototype.setDate = function (iYear, iMonth, iDay)
{
	this.oMoment = moment([iYear, iMonth, iDay]);
};

/**
 * @return {string}
 */
CDateModel.prototype.getTimeFormat = function ()
{
	return (AppData.User.defaultTimeFormat() === Enums.TimeFormat.F24) ?
		'HH:mm' : 'hh:mm A';
};

/**
 * @return {string}
 */
CDateModel.prototype.getFullDate = function ()
{
	return (this.oMoment) ? this.oMoment.format('ddd, MMM D, YYYY, ' + this.getTimeFormat()) : '';
};

/**
 * @return {string}
 */
CDateModel.prototype.getMidDate = function ()
{
	return this.getShortDate(true);
};

/**
 * @param {boolean=} bTime = false
 * 
 * @return {string}
 */
CDateModel.prototype.getShortDate = function (bTime)
{
	var
		sResult = '',
		oMomentNow = null
	;

	if (this.oMoment)
	{
		oMomentNow = moment();

		if (oMomentNow.format('L') === this.oMoment.format('L'))
		{
			sResult = this.oMoment.format(this.getTimeFormat());
		}
		else
		{
			if (oMomentNow.clone().subtract('days', 1).format('L') === this.oMoment.format('L'))
			{
				sResult = Utils.i18n('DATETIME/YESTERDAY');
			}
			else if (oMomentNow.year() === this.oMoment.year())
			{
				sResult = this.oMoment.format('MMM D');
			}
			else
			{
				sResult = this.oMoment.format('MMM D, YYYY');
			}

			if (Utils.isUnd(bTime) ? false : !!bTime)
			{
				sResult += ', ' + this.oMoment.format(this.getTimeFormat());
			}
		}
	}

	return sResult;
};

/**
 * @return {string}
 */
CDateModel.prototype.getDate = function ()
{
	return (this.oMoment) ? this.oMoment.format('ddd, MMM D, YYYY') : '';
};

/**
 * @return {string}
 */
CDateModel.prototype.getTime = function ()
{
	return (this.oMoment) ? this.oMoment.format(this.getTimeFormat()): '';
};

/**
 * @param {string} iDate
 * 
 * @return {string}
 */
CDateModel.prototype.convertDate = function (iDate)
{
	var sFormat = Utils.getDateFormatForMoment(AppData.User.DefaultDateFormat) + ' ' + this.getTimeFormat();
	
	return moment(iDate * 1000).format(sFormat);
};

/**
 * @return {number}
 */
CDateModel.prototype.getTimeStampInUTC = function ()
{
	return this.iTimeStampInUTC;
};

/**
 * @constructor
 */
function CIdentityModel()
{
	this.loyal = ko.observable(false);
	this.isDefault = ko.observable(false);
	this.enabled = ko.observable(true);
	this.email = ko.observable('');
	this.friendlyName = ko.observable('');
	this.fullEmail = ko.computed(function () {
		var sEmail = '';

		if (this.friendlyName() !== '')
		{
			sEmail = this.friendlyName() + ' <' + this.email() + '>';
		}
		else
		{
			sEmail = this.email();
		}

		return sEmail;
	}, this);
	this.accountId = ko.observable(-1);
	this.id = ko.observable(-1);
	this.signature = ko.observable('');
	this.useSignature = ko.observable(false);
}

/**
 * @param {Object} oData
 */
CIdentityModel.prototype.parse = function (oData)
{
	if (oData['@Object'] === 'Object/CIdentity')
	{
		this.loyal(!!oData.Loyal);
		this.isDefault(!!oData.Default);
		this.enabled(!!oData.Enabled);
		this.email(Utils.pString(oData.Email));
		this.friendlyName(Utils.pString(oData.FriendlyName));
		this.accountId(Utils.pInt(oData.IdAccount));
		this.id(Utils.pInt(oData.IdIdentity));
		this.signature(Utils.pString(oData.Signature));
		this.useSignature(!!oData.UseSignature);
	}
};

/**
 * @constructor
 */
function CCommonFileModel()
{
	this.isIosDevice = bIsIosDevice;

	this.isFolder = ko.observable(false);
	this.isLink = ko.observable(false);
	this.linkType = ko.observable(Enums.FileStorageLinkType.Unknown);
	this.linkUrl = ko.observable('');
	this.isPopupItem = ko.observable(false);
	
	this.id = ko.observable('');
	this.fileName = ko.observable('');
	this.tempName = ko.observable('');
	this.displayName = ko.observable('');
	this.extension = ko.observable('');
	
	this.fileName.subscribe(function () {
		var 
			sFileName = this.fileName()
		;
		this.id(sFileName);
		if (!this.isFolder())
		{
			this.displayName(Utils.getFileNameWithoutExtension(sFileName));
			this.extension(Utils.getFileExtension(sFileName));
		}
		else
		{
			this.displayName(sFileName);
			this.extension('');
		}
	}, this);
	
	this.size = ko.observable(0);
	this.friendlySize = ko.computed(function () {
		return Utils.friendlySize(this.size());
	}, this);
	
	this.content = ko.observable('');

	this.accountId = ko.observable((AppData.Accounts) ? AppData.Accounts.defaultId() : null);
	this.hash = ko.observable('');
	this.thumb = ko.observable(false);
	this.iframedView = ko.observable(false);

	this.downloadLink = ko.computed(function () {
		return Utils.getDownloadLinkByHash(this.accountId(), this.hash());
	}, this);

	this.viewLink = ko.computed(function () {
		var sUrl = Utils.getViewLinkByHash(this.accountId(), this.hash());
		return this.iframedView() ? Utils.getIframeWrappwer(this.accountId(), sUrl) : sUrl;
	}, this);

	this.thumbnailSrc = ko.observable('');
	this.thumbnailLoaded = ko.observable(false);
	this.thumbnailSessionUid = ko.observable('');

	this.thumbnailLink = ko.computed(function () {
		return this.thumb() ? Utils.getViewThumbnailLinkByHash(this.accountId(), this.hash()) : '';
	}, this);

	this.type = ko.observable('');
	this.uploadUid = ko.observable('');
	this.uploaded = ko.observable(false);
	this.uploadError = ko.observable(false);
	this.visibleImportLink = ko.computed(function () {
		return AppData.User.enableOpenPgp() && this.extension().toLowerCase() === 'asc' && this.content() !== '' && !this.isPopupItem();
	}, this);
	this.isViewMimeType = ko.computed(function () {
		return (-1 !== $.inArray(this.type(), aViewMimeTypes)) || this.iframedView();
	}, this);
	this.isMessageType = ko.observable(false);
	this.visibleViewLink = ko.computed(function () {
		return this.isVisibleViewLink() && !this.isPopupItem();
	}, this);
	this.visibleDownloadLink = ko.computed(function () {
		return !this.isPopupItem();
	}, this);
	
	this.subFiles = ko.observableArray([]);
	this.allowExpandSubFiles = ko.observable(false);
	this.subFilesLoaded = ko.observable(false);
	this.subFilesCollapsed = ko.observable(false);
	this.subFilesStartedLoading = ko.observable(false);
	this.visibleExpandLink = ko.computed(function () {
		return this.allowExpandSubFiles() && !this.subFilesCollapsed() && !this.subFilesStartedLoading();
	}, this);
	this.visibleExpandingText = ko.computed(function () {
		return this.allowExpandSubFiles() && !this.subFilesCollapsed() && this.subFilesStartedLoading();
	}, this);
	
	this.visibleSpinner = ko.observable(false);
	this.statusText = ko.observable('');
	this.statusTooltip = ko.computed(function () {
		return this.uploadError() ? this.statusText() : '';
	}, this);
	this.progressPercent = ko.observable(0);
	this.visibleProgress = ko.observable(false);
	
	this.uploadStarted = ko.observable(false);
	this.uploadStarted.subscribe(function () {
		if (this.uploadStarted())
		{
			this.uploaded(false);
			this.visibleProgress(true);
			this.progressPercent(20);
		}
		else
		{
			this.progressPercent(100);
			this.visibleProgress(false);
			this.uploaded(true);
		}
	}, this);
	
	this.allowDrag = ko.observable(false);
	this.allowSelect = ko.observable(false);
	this.allowCheck = ko.observable(false);
	this.allowDelete = ko.observable(false);
	this.allowUpload = ko.observable(false);
	this.allowSharing = ko.observable(false);
	this.allowHeader = ko.observable(false);
	this.allowDownload = ko.observable(true);

	this.downloadTitle = ko.computed(function () {
		if (!this.allowSelect() && this.allowDownload())
		{
			return Utils.i18n('MESSAGE/ATTACHMENT_CLICK_TO_DOWNLOAD', {
				'FILENAME': this.fileName(),
				'SIZE': this.friendlySize()
			});
		}
		return '';
	}, this);
}

/**
 * Can be overridden.
 */
CCommonFileModel.prototype.dataObjectName = '';

/**
 * Can be overridden.
 * 
 * @returns {boolean}
 */
CCommonFileModel.prototype.isVisibleViewLink = function ()
{
	return this.uploaded() && !this.uploadError() && this.isViewMimeType();
};

/**
 * Parses attachment data from server.
 *
 * @param {AjaxAttachmenResponse} oData
 * @param {number} iAccountId
 */
CCommonFileModel.prototype.parse = function (oData, iAccountId)
{
	if (oData['@Object'] === this.dataObjectName)
	{
		this.fileName(Utils.pString(oData.FileName));
		this.tempName(Utils.pString(oData.TempName));
		if (this.tempName() === '')
		{
			this.tempName(this.fileName());
		}
		
		this.type(Utils.pString(oData.MimeType));
		this.size(oData.EstimatedSize ? parseInt(oData.EstimatedSize, 10) : parseInt(oData.SizeInBytes, 10));
		this.content(Utils.pString(oData.Content));

		this.thumb(!!oData.Thumb);
		this.hash(Utils.pString(oData.Hash));
		this.accountId(iAccountId);
		this.allowExpandSubFiles(!!oData.Expand);
		
		this.iframedView(!!oData.Iframed);

		this.uploadUid(this.hash());
		this.uploaded(true);
		
		if (Utils.isFunc(this.additionalParse))
		{
			this.additionalParse(oData);
		}
	}
};

CCommonFileModel.prototype.getInThumbQueue = function (sThumbSessionUid)
{
	this.thumbnailSessionUid(sThumbSessionUid);
	if(this.thumb() && (!this.linked || this.linked && !this.linked()))
	{
		Utils.thumbQueue(this.thumbnailSessionUid(), this.thumbnailLink(), this.thumbnailSrc);
	}
};

/**
 * @param {Object=} oApp
 * 
 * Starts downloading attachment on click.
 */
CCommonFileModel.prototype.downloadFile = function (oApp)
{
	if (this.allowDownload())
	{
		if (!oApp || !oApp.Api || !oApp.Api.downloadByUrl)
		{
			oApp = App;
		}

		if (oApp && this.downloadLink().length > 0 && this.downloadLink() !== '#')
		{
			oApp.Api.downloadByUrl(this.downloadLink());
		}
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CCommonFileModel.prototype.onFileExpandResponse = function (oResponse, oRequest)
{
	this.subFiles([]);
	if (Utils.isNonEmptyArray(oResponse.Result))
	{
		_.each(oResponse.Result, _.bind(function (oRawFile) {
			var oFile = this.getInstance();
			oRawFile['@Object'] = this.dataObjectName;
			oFile.parse(oRawFile, this.accountId());
			this.subFiles.push(oFile);
		}, this));
		this.subFilesLoaded(true);
		this.subFilesCollapsed(true);
	}
	this.subFilesStartedLoading(false);
};

/**
 * Starts expanding attachment on click.
 */
CCommonFileModel.prototype.expandFile = function ()
{
	if (!this.subFilesLoaded())
	{
		this.subFilesStartedLoading(true);
		App.Ajax.send({
			'Action': 'FileExpand',
			'RawKey': this.hash()
		}, this.onFileExpandResponse, this);
	}
	else
	{
		this.subFilesCollapsed(true);
	}
};

/**
 * Collapse attachment on click.
 */
CCommonFileModel.prototype.collapseFile = function ()
{
	this.subFilesCollapsed(false);
};

/**
 * @returns {CCommonFileModel}
 */
CCommonFileModel.prototype.getInstance = function ()
{
	return new CCommonFileModel();
};

/**
 * Starts importing attachment on click.
 */
CCommonFileModel.prototype.importFile = function ()
{
	var
		sContent = this.content(),
		fPgpCallback = _.bind(function (oPgp) {
			if (oPgp)
			{
				App.Screens.showPopup(CImportOpenPgpKeyPopup, [oPgp, sContent]);
			}
		}, this)
	;
	
	App.Api.pgp(fPgpCallback, AppData.User.IdUser);
};

/**
 * Can be overridden.
 * 
 * Starts viewing attachment on click.
 */
CCommonFileModel.prototype.viewFile = function ()
{
	this.viewCommonFile();
};

/**
 * Starts viewing attachment on click.
 */
CCommonFileModel.prototype.viewCommonFile = function ()
{
	var
		oWin = null,
		sUrl = Utils.getAppPath() + this.viewLink()
	;
	
	if (this.visibleViewLink() && this.viewLink().length > 0 && this.viewLink() !== '#')
	{
		if (this.isLink()/* && this.linkType() === Enums.FileStorageLinkType.GoogleDrive*/)
		{
			sUrl = this.linkUrl();
		}

		if (this.iframedView())
		{
			oWin = Utils.WindowOpener.openTab(sUrl);
		}
		else
		{
			oWin = Utils.WindowOpener.open(sUrl, sUrl, false);
		}

		if (oWin)
		{
			oWin.focus();
		}
	}
};

/**
 * @param {Object} oAttachment
 * @param {*} oEvent
 * @return {boolean}
 */
CCommonFileModel.prototype.eventDragStart = function (oAttachment, oEvent)
{
	var oLocalEvent = oEvent.originalEvent || oEvent;
	if (oAttachment && oLocalEvent && oLocalEvent.dataTransfer && oLocalEvent.dataTransfer.setData)
	{
		oLocalEvent.dataTransfer.setData('DownloadURL', this.generateTransferDownloadUrl());
	}

	return true;
};

/**
 * @return {string}
 */
CCommonFileModel.prototype.generateTransferDownloadUrl = function ()
{
	var sLink = this.downloadLink();
	if ('http' !== sLink.substr(0, 4))
	{
		sLink = window.location.protocol + '//' + window.location.host + window.location.pathname + sLink;
	}

	return this.type() + ':' + this.fileName() + ':' + sLink;
};

/**
 * Fills attachment data for upload.
 *
 * @param {string} sFileUid
 * @param {Object} oFileData
 */
CCommonFileModel.prototype.onUploadSelect = function (sFileUid, oFileData)
{
	this.fileName(Utils.pString(oFileData['FileName']));
	this.type(Utils.pString(oFileData['Type']));
	this.size(Utils.pInt(oFileData['Size']));

	this.uploadUid(sFileUid);
	this.uploaded(false);
	this.visibleSpinner(false);
	this.statusText('');
	this.progressPercent(0);
	this.visibleProgress(false);
};

/**
 * Starts spinner and progress.
 */
CCommonFileModel.prototype.onUploadStart = function ()
{
	this.visibleSpinner(true);
	this.visibleProgress(true);
};

/**
 * Fills progress upload data.
 *
 * @param {number} iUploadedSize
 * @param {number} iTotalSize
 */
CCommonFileModel.prototype.onUploadProgress = function (iUploadedSize, iTotalSize)
{
	if (iTotalSize > 0)
	{
		this.progressPercent(Math.ceil(iUploadedSize / iTotalSize * 100));
		this.visibleProgress(true);
	}
};

/**
 * Fills data when upload has completed.
 *
 * @param {string} sFileUid
 * @param {boolean} bResponseReceived
 * @param {Object} oResult
 */
CCommonFileModel.prototype.onUploadComplete = function (sFileUid, bResponseReceived, oResult)
{
	var
		bError = !bResponseReceived || !oResult || oResult.Error || false,
		sError = (oResult && oResult.Error === 'size') ?
			Utils.i18n('COMPOSE/UPLOAD_ERROR_SIZE') :
			Utils.i18n('COMPOSE/UPLOAD_ERROR_UNKNOWN')
	;
	
	this.visibleSpinner(false);
	this.progressPercent(0);
	this.visibleProgress(false);
	
	this.uploaded(true);
	this.uploadError(bError);
	this.statusText(bError ? sError : Utils.i18n('COMPOSE/UPLOAD_COMPLETE'));

	if (!bError)
	{
		this.fillDataAfterUploadComplete(oResult, sFileUid);
		
		setTimeout((function (self) {
			return function () {
				self.statusText('');
			};
		})(this), 3000);
	}
};

/**
 * Should be overriden.
 * 
 * @param {Object} oResult
 * @param {string} sFileUid
 */
CCommonFileModel.prototype.fillDataAfterUploadComplete = function (oResult, sFileUid)
{
};

/**
 * @param {Object} oAttachmentModel
 * @param {Object} oEvent
 */
CCommonFileModel.prototype.onImageLoad = function (oAttachmentModel, oEvent)
{
	if(this.thumb() && !this.thumbnailLoaded())
	{
		this.thumbnailLoaded(true);
		Utils.thumbQueue(this.thumbnailSessionUid());
	}
};
/**
 * @constructor
 * @extends CCommonFileModel
 */
function CMailAttachmentModel()
{
	this.folderName = ko.observable('');
	this.messageUid = ko.observable('');
	
	this.cid = ko.observable('');
	this.contentLocation = ko.observable('');
	this.inline = ko.observable(false);
	this.linked = ko.observable(false);
	this.mimePartIndex = ko.observable('');

	this.messagePart = ko.observable(null);
	
	CCommonFileModel.call(this);
	
	this.isMessageType = ko.computed(function () {
		this.type();
		this.mimePartIndex();
		return (this.type() === 'message/rfc822' && this.mimePartIndex() !== '');
	}, this);
}

Utils.extend(CMailAttachmentModel, CCommonFileModel);

CMailAttachmentModel.prototype.dataObjectName = 'Object/CApiMailAttachment';

/**
 * @returns {CMailAttachmentModel}
 */
CMailAttachmentModel.prototype.getInstance = function ()
{
	return new CMailAttachmentModel();
};

CMailAttachmentModel.prototype.getCopy = function ()
{
	var oCopy = new CMailAttachmentModel();
	
	oCopy.copyProperties(this);
	
	return oCopy;
};

CMailAttachmentModel.prototype.copyProperties = function (oSource)
{
	this.fileName(oSource.fileName());
	this.tempName(oSource.tempName());
	this.size(oSource.size());
	this.accountId(oSource.accountId());
	this.hash(oSource.hash());
	this.type(oSource.type());
	this.cid(oSource.cid());
	this.contentLocation(oSource.contentLocation());
	this.inline(oSource.inline());
	this.linked(oSource.linked());
	this.thumb(oSource.thumb());
	this.thumbnailSrc(oSource.thumbnailSrc());
	this.thumbnailLoaded(oSource.thumbnailLoaded());
	this.statusText(oSource.statusText());
	this.uploaded(oSource.uploaded());
	this.iframedView(oSource.iframedView());
};

CMailAttachmentModel.prototype.isVisibleViewLink = function ()
{
	return this.uploaded() && !this.uploadError() && (this.isViewMimeType() || this.isMessageType());
};

/**
 * Parses attachment data from server.
 *
 * @param {AjaxAttachmenResponse} oData
 */
CMailAttachmentModel.prototype.additionalParse = function (oData)
{
	this.mimePartIndex(Utils.pString(oData.MimePartIndex));

	this.cid(Utils.pString(oData.CID));
	this.contentLocation(Utils.pString(oData.ContentLocation));
	this.inline(!!oData.IsInline);
	this.linked(!!oData.IsLinked);
};

/**
 * @param {string} sFolderName
 * @param {string} sMessageUid
 */
CMailAttachmentModel.prototype.setMessageData = function (sFolderName, sMessageUid)
{
	this.folderName(sFolderName);
	this.messageUid(sMessageUid);
};

/**
 * @param {AjaxDefaultResponse} oData
 * @param {Object=} oParameters
 */
CMailAttachmentModel.prototype.onMessageGetResponse = function (oData, oParameters)
{
	var
		oResult = oData.Result,
		oMessage = new CMessageModel()
	;
	
	if (oResult && this.oNewWindow)
	{
		oMessage.parse(oResult, oData.AccountID, false, true);
		this.messagePart(oMessage);
		this.messagePart().viewMessage(this.oNewWindow);
		this.oNewWindow = undefined;
	}
};

/**
 * Starts viewing attachment on click.
 */
CMailAttachmentModel.prototype.viewFile = function ()
{
	if (this.isMessageType())
	{
		this.viewMessageFile();
	}
	else
	{
		this.viewCommonFile();
	}
};

/**
 * Starts viewing attachment on click.
 */
CMailAttachmentModel.prototype.viewMessageFile = function ()
{
	var
		oWin = null,
		sLoadingText = '<div style="margin: 30px; text-align: center; font: normal 14px Tahoma;">' + 
			Utils.i18n('MAIN/LOADING') + '</div>'
	;
	
	oWin = Utils.WindowOpener.open('', this.fileName());
	if (oWin)
	{
		if (this.messagePart())
		{
			this.messagePart().viewMessage(oWin);
		}
		else
		{
			$(oWin.document.body).html(sLoadingText);
			this.oNewWindow = oWin;

			App.Ajax.send({
				'Action': 'MessageGet',
				'Folder': this.folderName(),
				'Uid': this.messageUid(),
				'Rfc822MimeIndex': this.mimePartIndex()
			}, this.onMessageGetResponse, this);
		}
		
		oWin.focus();
	}
};

/**
 * Starts viewing attachment on click.
 */
CMailAttachmentModel.prototype.viewCommonFile = function ()
{
	var
		oWin = null,
		sUrl = Utils.getAppPath() + this.viewLink()
	;
	
	if (this.visibleViewLink() && this.viewLink().length > 0 && this.viewLink() !== '#')
	{
		sUrl = Utils.getAppPath() + this.viewLink();

		if (this.iframedView())
		{
			oWin = Utils.WindowOpener.openTab(sUrl);
		}
		else
		{
			oWin = Utils.WindowOpener.open(sUrl, sUrl, false);
		}

		if (oWin)
		{
			oWin.focus();
		}
	}
};

/**
 * @param {Object} oResult
 * @param {string} sFileUid
 */
CMailAttachmentModel.prototype.fillDataAfterUploadComplete = function (oResult, sFileUid)
{
	this.cid(sFileUid);
	this.tempName(oResult.Result.Attachment.TempName);
	this.type(oResult.Result.Attachment.MimeType);
	this.size(oResult.Result.Attachment.Size);
	this.hash(oResult.Result.Attachment.Hash);
	this.iframedView(oResult.Result.Attachment.Iframed);
	this.accountId(oResult.AccountID);
};

/**
 * Parses contact attachment data from server.
 *
 * @param {AjaxFileDataResponse} oData
 * @param {number} iAccountId
 */
CMailAttachmentModel.prototype.parseFromUpload = function (oData, iAccountId)
{
	this.fileName(oData.Name.toString());
	this.tempName(oData.TempName ? oData.TempName.toString() : this.fileName());
	this.type(oData.MimeType.toString());
	this.size(parseInt(oData.Size, 10));

	this.hash(oData.Hash);
	this.accountId(iAccountId);

	this.uploadUid(this.hash());
	this.uploaded(true);
	
	this.uploadStarted(false);
};

CMailAttachmentModel.prototype.errorFromUpload = function ()
{
	this.uploaded(true);
	this.uploadError(true);
	this.uploadStarted(false);
	this.statusText(Utils.i18n('COMPOSE/UPLOAD_ERROR_UNKNOWN'));
};

/**
 * @constructor
 * @param {Object} oFolderList
 */
function CFolderModel(oFolderList)
{
	this.iAccountId = 0;

	this.account = ko.computed(function () {
		return AppData.Accounts.getAccount(this.iAccountId);
	}, this);
	
	this.parentFullName = ko.observable('');
	
	this.level = ko.observable(0);
	this.name = ko.observable('');
	this.nameForEdit = ko.observable('');
	this.fullName = ko.observable('');
	this.fullNameHash = ko.observable('');
	this.uidNext = ko.observable('');
	this.hash = ko.observable('');
	this.routingHash = ko.observable('');
	this.delimiter = ko.observable('');
	this.type = ko.observable(Enums.FolderTypes.User);
	this.showUnseenMessages = ko.computed(function () {
		return (this.type() !== Enums.FolderTypes.Drafts);
	}, this);
	this.withoutThreads = ko.computed(function () {
		return (this.type() === Enums.FolderTypes.Drafts || 
			this.type() === Enums.FolderTypes.Spam || this.type() === Enums.FolderTypes.Trash);
	}, this);

	this.messageCount = ko.observable(0);
	this.unseenMessageCount = ko.observable(0);
	this.unseenMessageCount.subscribe(function (iCount) {
		_.delay(_.bind(function(){ App.MailCache.countMessages(this); },this), 1000);
	}, this);
	this.realUnseenMessageCount = ko.observable(0);

	this.enableEmptyFolder = ko.computed(function () {
		return (this.messageCount() > 0 &&
			(this.type() === Enums.FolderTypes.Spam || this.type() === Enums.FolderTypes.Trash));
	}, this);

	this.virtual = ko.observable(false);
	this.virtualEmpty = ko.computed(function () {
		return this.virtual() && this.messageCount() === 0;
	}, this);
	
	this.hasExtendedInfo = ko.observable(false);

	this.selectable = ko.observable(true);
	this.subscribed = ko.observable(true);
	this.subscribed.subscribe(function (bIsSubscribe) {
		if(this.parentFullName())
		{
			var oParentFolder = App.MailCache.folderList().getFolderByFullName(this.parentFullName());
			if(oParentFolder)
			{
				App.MailCache.countMessages(oParentFolder);
			}
		}
	}, this);
	this.existen = ko.observable(true);

	this.isNamespace = ko.observable(false);

	this.subfoldersMessagesCount = ko.observable(0);
	this.subfolders = ko.observableArray([]);
	this.subfolders.subscribe(function (aSubFolders) {
		var canExpand = _.any(
				_.map(aSubFolders, function(oFolder, key){
					return oFolder.subscribed();
				})
			);
		this.canExpand(canExpand);
	}, this);
	this.canExpand = ko.observable(true);
	this.expanded = ko.observable(false);
	this.isCollapseHandler = ko.computed(function () {
		return this.subfolders().length !== 0 && !this.isNamespace() && this.canExpand();
	}, this);

	this.messageCountToShow = ko.computed(function () {
		if(this.canExpand())
		{
			return (this.showUnseenMessages()) ? this.unseenMessageCount() + this.subfoldersMessagesCount() : this.messageCount();
		}
		else
		{
			return (this.showUnseenMessages()) ? this.unseenMessageCount() : this.messageCount();
		}
	}, this);
	
	this.isSubFolder = ko.computed(function () {
		return (this.level() > 0);
	}, this);

	this.selected = ko.observable(false);
	this.recivedAnim = ko.observable(false).extend({'autoResetToFalse': 500});

	this.hasSubscribedSubfolders = ko.computed(function () {
		return !!ko.utils.arrayFirst(this.subfolders(), function (oFolder) {
			return oFolder.subscribed();
		});
	}, this);

	this.isSystem = ko.computed(function () {
		return (this.type() !== Enums.FolderTypes.User ? true : false);
	}, this);

	this.visible = ko.computed(function () {
		var
			bSubScribed = this.subscribed(),
			bExisten = this.existen(),
			bSelectable = this.selectable(),
			bSubFolders = this.hasSubscribedSubfolders(),
			bSystem = this.isSystem()
		;

		return bSubScribed || bSystem || (bSubFolders && (!bExisten || !bSelectable));

	}, this);

	this.edited = ko.observable(false);
	
	this.edited.subscribe(function (value) {
		if (value === false)
		{
			this.nameForEdit(this.name());
		}
	}, this);

	this.canBeSelected = ko.computed(function () {
		var
			bExisten = this.existen(),
			bSelectable = this.selectable()
		;

		return bExisten && bSelectable;
	}, this);
	
	this.canSubscribe = ko.computed(function () {
		var
			oAccount = this.account(),
			bDisableManageSubscribe = false
		;
		
		if (oAccount)
		{
			bDisableManageSubscribe = oAccount.extensionExists('DisableManageSubscribe');
		}
		
		return (!this.isSystem() && this.canBeSelected() && !bDisableManageSubscribe);
	}, this);

	this.canDelete = ko.computed(function () {
		return (!this.isSystem() && this.hasExtendedInfo() && this.messageCount() === 0 && this.subfolders().length === 0);
	}, this);

	this.canRename = ko.computed(function () {
		return (!this.isSystem() && this.canBeSelected());
	}, this);

	this.subscribeButtonHint = ko.computed(function () {
		if (this.canSubscribe())
		{
			return this.subscribed() ? Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_HIDE_FOLDER_HINT') : Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_SHOW_FOLDER_HINT');
		}
		return '';
	}, this);
	
	this.deleteButtonHint = ko.computed(function () {
		return this.canDelete() ? Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_DELETE_FOLDER_HINT') : '';
	}, this);
	
	this.usedAs = ko.computed(function () {
		
		var 
			result = ''
		;
		
		switch (this.type())
		{
			case Enums.FolderTypes.Inbox:
				result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_USED_AS_INBOX');
				break;
			case Enums.FolderTypes.Sent:
				result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_USED_AS_SENT');
				break;
			case Enums.FolderTypes.Drafts:
				result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_USED_AS_DRAFTS');
				break;
			case Enums.FolderTypes.Trash:
				result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_USED_AS_TRASH');
				break;
			case Enums.FolderTypes.Spam:
				result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_USED_AS_SPAM');
				break;
			default:
				result =  '';
				break;
		}
		
		return result;

	}, this);

	this.oMessages = {};
	
	this.oUids = {};

	this.aResponseHandlers = [];
	
	this.displayName = ko.computed(function () {
		
		var 
			result = this.name()
		;
		
		switch (this.type())
		{
			case Enums.FolderTypes.Inbox:
				result = Utils.i18n('MAIN/FOLDER_INBOX');
				break;
			case Enums.FolderTypes.Sent:
				result = Utils.i18n('MAIN/FOLDER_SENT');
				break;
			case Enums.FolderTypes.Drafts:
				result = Utils.i18n('MAIN/FOLDER_DRAFTS');
				break;
			case Enums.FolderTypes.Trash:
				result = Utils.i18n('MAIN/FOLDER_TRASH');
				break;
			case Enums.FolderTypes.Spam:
				result = Utils.i18n('MAIN/FOLDER_SPAM');
				break;
		}
		
		return result;

	}, this);
	
	this.aRequestedUids = [];
	this.aRequestedThreadUids = [];
	this.requestedLists = [];
	
	this.hasChanges = ko.observable(false);
	this.hasChanges.subscribe(function () {
		this.requestedLists = [];
	}, this);
	
	this.unseenFilterCommand = Utils.createCommand(this, this.executeUnseenFilter, this.showUnseenMessages);
	this.unseenMessagesTitle = ko.computed(function () {
		return this.showUnseenMessages() ? Utils.i18n('MAILBOX/TITLE_UNSEEN_MESSAGES_ONLY') : '';
	}, this);
	
	this.relevantInformationLastMoment = null;
}

/**
 * @param {string} sUid
 * @returns {Object}
 */
CFolderModel.prototype.getMessageByUid = function (sUid)
{
	return this.oMessages[sUid];
};

/**
 * @returns {Array}
 */
CFolderModel.prototype.getFlaggedMessageUids = function ()
{
	var aUids = [];
	_.each(this.oMessages, function (oMessage) {
		if (oMessage.flagged())
		{
			aUids.push(oMessage.uid());
		}
	});
	return aUids;
};

/**
 * @param {string} sUid
 */
CFolderModel.prototype.setMessageUnflaggedByUid = function (sUid)
{
	var oMessage = this.oMessages[sUid];
	if (oMessage)
	{
		oMessage.flagged(false);
	}
};

/**
 * @param {Object} oMessage
 */
CFolderModel.prototype.hideThreadMessages = function (oMessage)
{
	_.each(oMessage.threadUids(), function (sThreadUid) {
		var oMess = this.oMessages[sThreadUid];
		if (oMess)
		{
			if (!oMess.deleted())
			{
				oMess.threadShowAnimation(false);
				oMess.threadHideAnimation(true);
				
				setTimeout(function () {
					oMess.threadHideAnimation(false);
				}, 1000);
			}
		}
	}, this);
};

/**
 * @param {Object} oMessage
 */
CFolderModel.prototype.getThreadMessages = function (oMessage)
{
	var
		aLoadedMessages = [],
		aUidsForLoad = [],
		aChangedThreadUids = [],
		iCount = 0,
		oLastMessage = null,
		iShowThrottle = 50
	;
	
	_.each(oMessage.threadUids(), function (sThreadUid) {
		if (iCount < oMessage.threadCountForLoad())
		{
			var oMess = this.oMessages[sThreadUid];
			if (oMess)
			{
				if (!oMess.deleted())
				{
					oMess.markAsThreadPart(iShowThrottle, oMessage.uid());
					aLoadedMessages.push(oMess);
					aChangedThreadUids.push(oMess.uid());
					iCount++;
					oLastMessage = oMess;
				}
			}
			else
			{
				aUidsForLoad.push(sThreadUid);
				aChangedThreadUids.push(sThreadUid);
				iCount++;
			}
		}
		else
		{
			aChangedThreadUids.push(sThreadUid);
		}
	}, this);
	
	if (!oMessage.threadLoading())
	{
		this.loadThreadMessages(aUidsForLoad);
	}
	
	oMessage.changeThreadUids(aChangedThreadUids, aLoadedMessages.length);
	
	if (oLastMessage && aLoadedMessages.length < oMessage.threadUids().length)
	{
		oLastMessage.showNextLoadingLink(_.bind(oMessage.increaseThreadCountForLoad, oMessage));
	}
	
	this.addThreadUidsToUidLists(oMessage.uid(), oMessage.threadUids());
	
	return aLoadedMessages;
};

/**
 * @param {Object} oMessage
 */
CFolderModel.prototype.computeThreadData = function (oMessage)
{
	var
		iUnreadCount = 0,
		bPartialFlagged = false,
		aSenders = [],
		aEmails = [],
		sMainEmail = oMessage.oFrom.getFirstEmail()
	;
	
	_.each(oMessage.threadUids(), function (sThreadUid) {
		var
			oThreadMessage = this.oMessages[sThreadUid],
			sThreadEmail = ''
		;
		
		if (oThreadMessage && !oThreadMessage.deleted())
		{
			if (!oThreadMessage.seen())
			{
				iUnreadCount++;
			}
			if (oThreadMessage.flagged())
			{
				bPartialFlagged = true;
			}
			
			sThreadEmail = oThreadMessage.oFrom.getFirstEmail();
			if ((sThreadEmail !== sMainEmail) && (-1 === Utils.inArray(sThreadEmail, aEmails)))
			{
				aEmails.push(sThreadEmail);
				if (sThreadEmail === AppData.Accounts.getEmail())
				{
					aSenders.push(Utils.i18n('MESSAGE/ME_SENDER'));
				}
				else
				{
					aSenders.push(oThreadMessage.oFrom.getFirstDisplay());
				}
			}
		}
	}, this);
	
	oMessage.threadUnreadCount(iUnreadCount);
	oMessage.partialFlagged(bPartialFlagged);
	oMessage.threadSenders(aSenders);
};

/**
 * 
 * @param {string} sUid
 * @param {Array} aThreadUids
 */
CFolderModel.prototype.addThreadUidsToUidLists = function (sUid, aThreadUids)
{
	_.each(this.oUids, function (oUidSearchList) {
		_.each(oUidSearchList, function (oUidList) {
			oUidList.addThreadUids(sUid, aThreadUids);
		});
	});
};

/**
 * @param {Array} aUidsForLoad
 */
CFolderModel.prototype.loadThreadMessages = function (aUidsForLoad)
{
	if (aUidsForLoad.length > 0)
	{
		var
			oParameters = {
				'Action': 'MessagesGetListByUids',
				'Folder': this.fullName(),
				'Uids': aUidsForLoad
			}
		;

		App.Ajax.send(oParameters, this.onMessagesGetListByUidsResponse, this);
	}
};

/**
 * @param {Array} aMessages
 */
CFolderModel.prototype.getThreadCheckedUidsFromList = function (aMessages)
{
	var
		oFolder = this,
		aThreadUids = []
	;
	
	_.each(aMessages, function (oMessage) {
		if (oMessage.threadCount() > 0 && !oMessage.threadOpened())
		{
			_.each(oMessage.threadUids(), function (sUid) {
				var oThreadMessage = oFolder.oMessages[sUid];
				if (oThreadMessage && !oThreadMessage.deleted() && oThreadMessage.checked())
				{
					aThreadUids.push(sUid);
				}
			});
		}
	});
	
	return aThreadUids;
};

/**
 * @param {Object} oRawMessage
 * @param {boolean} bThreadPart
 * @param {boolean} bTrustThreadInfo
 */
CFolderModel.prototype.parseAndCacheMessage = function (oRawMessage, bThreadPart, bTrustThreadInfo)
{
	var
		sUid = oRawMessage.Uid.toString(),
		bNewMessage = Utils.isUnd(this.oMessages[sUid]),
		oMessage = bNewMessage ? new CMessageModel() : this.oMessages[sUid]
	;
	
	oMessage.parse(oRawMessage, this.iAccountId, bThreadPart, bTrustThreadInfo);
	if (this.type() === Enums.FolderTypes.Inbox && bNewMessage && oMessage.flagged())
	{
		App.MailCache.increaseStarredCount();
	}
	
	this.oMessages[oMessage.uid()] = oMessage;
	
	return oMessage;
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CFolderModel.prototype.onMessagesGetListByUidsResponse = function (oResponse, oRequest)
{
	var oResult = oResponse.Result;
	
	if (oResult && oResult['@Object'] === 'Collection/MessageCollection')
	{
		_.each(oResult['@Collection'], function (oRawMessage) {
			this.parseAndCacheMessage(oRawMessage, true, true);
		}, this);
		
		App.MailCache.showOpenedThreads(this.fullName());
	}
};

/**
 * Adds uids of requested messages.
 * 
 * @param {Array} aUids
 */
CFolderModel.prototype.addRequestedUids = function (aUids)
{
	this.aRequestedUids = _.union(this.aRequestedUids, aUids);
};

/**
 * @param {string} sUid
 */
CFolderModel.prototype.hasUidBeenRequested = function (sUid)
{
	return _.indexOf(this.aRequestedUids, sUid) !== -1;
};

/**
 * Adds uids of requested thread message headers.
 * 
 * @param {Array} aUids
 */
CFolderModel.prototype.addRequestedThreadUids = function (aUids)
{
	this.aRequestedThreadUids = _.union(this.aRequestedThreadUids, aUids);
};

/**
 * @param {string} sUid
 */
CFolderModel.prototype.hasThreadUidBeenRequested = function (sUid)
{
	return _.indexOf(this.aRequestedThreadUids, sUid) !== -1;
};

/**
 * @param {Object} oParams
 */
CFolderModel.prototype.hasListBeenRequested = function (oParams)
{
	var
		aFindedParams = _.where(this.requestedLists, oParams),
		bHasParams = aFindedParams.length > 0
	;
	
	if (!bHasParams)
	{
		this.requestedLists.push(oParams);
	}
	return bHasParams;
};

/**
 * @param {string} sUid
 * @param {string} sReplyType
 */
CFolderModel.prototype.markMessageReplied = function (sUid, sReplyType)
{
	var oMsg = this.oMessages[sUid];
	
	if (oMsg)
	{
		switch (sReplyType)
		{
			case Enums.ReplyType.Reply:
			case Enums.ReplyType.ReplyAll:
				oMsg.answered(true);
				break;
			case Enums.ReplyType.Forward:
				oMsg.forwarded(true);
				break;
		}
	}
};

CFolderModel.prototype.removeAllMessages = function ()
{
	var oUidList = null;
	
	this.oMessages = {};
	this.oUids = {};

	this.messageCount(0);
	this.unseenMessageCount(0);
	this.realUnseenMessageCount(0);
	
	oUidList = this.getUidList('', '');
	oUidList.resultCount(0);
};

CFolderModel.prototype.removeAllMessageListsFromCacheIfHasChanges = function ()
{
	if (this.hasChanges())
	{
		this.oUids = {};
		this.requestedLists = [];
		this.aRequestedThreadUids = [];
		this.hasChanges(false);
	}
};

CFolderModel.prototype.removeFlaggedMessageListsFromCache = function ()
{
	_.each(this.oUids, function (oSearchUids, sSearch) {
		delete this.oUids[sSearch][Enums.FolderFilter.Flagged];
	}, this);
};

CFolderModel.prototype.removeUnseenMessageListsFromCache = function ()
{
	_.each(this.oUids, function (oSearchUids, sSearch) {
		delete this.oUids[sSearch][Enums.FolderFilter.Unseen];
	}, this);
};

/**
 * @param {string} sUidNext
 * @param {string} sHash
 * @param {number} iMsgCount
 * @param {number} iMsgUnseenCount
 * @param {boolean} bOnlyRealCount
 */
CFolderModel.prototype.setRelevantInformation = function (sUidNext, sHash, iMsgCount, iMsgUnseenCount, bOnlyRealCount)
{
	var hasChanges = this.hasExtendedInfo() && (this.hash() !== sHash || this.realUnseenMessageCount() !== iMsgUnseenCount);
	
	this.uidNext(sUidNext);
	this.hash(sHash); // if different, either new messages were appeared, or some messages were deleted
	if (!this.hasExtendedInfo() || !bOnlyRealCount)
	{
		this.messageCount(iMsgCount);
		this.unseenMessageCount(iMsgUnseenCount);
		if (iMsgUnseenCount === 0) { this.unseenMessageCount.valueHasMutated(); } //fix for folder count summing
	}
	this.realUnseenMessageCount(iMsgUnseenCount);
	this.hasExtendedInfo(true);

	if (hasChanges)
	{
		this.markHasChanges();
	}
	
	this.relevantInformationLastMoment = moment();
	
	return hasChanges;
};

CFolderModel.prototype.increaseCountIfHasNotInfo = function ()
{
	if (!this.hasExtendedInfo())
	{
		this.messageCount(this.messageCount() + 1);
	}
};

CFolderModel.prototype.markHasChanges = function ()
{
	this.hasChanges(true);
};

/**
 * @param {number} iDiff
 * @param {number} iUnseenDiff
 */
CFolderModel.prototype.addMessagesCountsDiff = function (iDiff, iUnseenDiff)
{
	var
		iCount = this.messageCount() + iDiff,
		iUnseenCount = this.unseenMessageCount() + iUnseenDiff
	;

	if (iCount < 0)
	{
		iCount = 0;
	}
	this.messageCount(iCount);

	if (iUnseenCount < 0)
	{
		iUnseenCount = 0;
	}
	if (iUnseenCount > iCount)
	{
		iUnseenCount = iCount;
	}
	this.unseenMessageCount(iUnseenCount);
};

/**
 * @param {Array} aUids
 */
CFolderModel.prototype.markDeletedByUids = function (aUids)
{
	var
		iMinusDiff = 0,
		iUnseenMinusDiff = 0
	;

	_.each(aUids, function (sUid)
	{
		var oMessage = this.oMessages[sUid];

		if (oMessage)
		{
			iMinusDiff++;
			if (!oMessage.seen())
			{
				iUnseenMinusDiff++;
			}
			oMessage.deleted(true);
		}

	}, this);

	this.addMessagesCountsDiff(-iMinusDiff, -iUnseenMinusDiff);
	
	return {MinusDiff: iMinusDiff, UnseenMinusDiff: iUnseenMinusDiff};
};

/**
 * @param {Array} aUids
 */
CFolderModel.prototype.revertDeleted = function (aUids)
{
	var
		iPlusDiff = 0,
		iUnseenPlusDiff = 0
	;

	_.each(aUids, function (sUid)
	{
		var oMessage = this.oMessages[sUid];

		if (oMessage && oMessage.deleted())
		{
			iPlusDiff++;
			if (!oMessage.seen())
			{
				iUnseenPlusDiff++;
			}
			oMessage.deleted(false);
		}

	}, this);

	this.addMessagesCountsDiff(iPlusDiff, iUnseenPlusDiff);

	return {PlusDiff: iPlusDiff, UnseenPlusDiff: iUnseenPlusDiff};
};

/**
 * @param {Array} aUids
 */
CFolderModel.prototype.commitDeleted = function (aUids)
{
	_.each(aUids, _.bind(function (sUid) {
		delete this.oMessages[sUid];
	}, this));
	
	_.each(this.oUids, function (oUidSearchList) {
		_.each(oUidSearchList, function (oUidList) {
			oUidList.deleteUids(aUids);
		});
	});
};

/**
 * @param {string} sSearch
 * @param {string} sFilters
 */
CFolderModel.prototype.getUidList = function (sSearch, sFilters)
{
	var
		oUidList = null
	;
	
	if (this.oUids[sSearch] === undefined)
	{
		this.oUids[sSearch] = {};
	}
	
	if (this.oUids[sSearch][sFilters] === undefined)
	{
		oUidList = new CUidListModel();
		oUidList.search(sSearch);
		oUidList.filters(sFilters);
		this.oUids[sSearch][sFilters] = oUidList;
	}
	
	return this.oUids[sSearch][sFilters];
};

/**
 * @param {Object} oResult
 * @param {string} sParentFullName
 */
CFolderModel.prototype.parse = function (oResult, sParentFullName)
{
	var sName = '',
		aFolders = App.Storage.getData('folderAccordion') || [];

	if (oResult['@Object'] === 'Object/Folder')
	{
		this.parentFullName(sParentFullName);

		sName = Utils.pString(oResult.Name);
		
		this.name(sName);
		this.nameForEdit(sName);
		this.fullName(Utils.pString(oResult.FullNameRaw));
		this.fullNameHash(Utils.pString(oResult.FullNameHash));
		this.routingHash(App.Routing.buildHashFromArray([Enums.Screens.Mailbox, this.fullName()]));
		this.delimiter(oResult.Delimiter);
		this.type(oResult.Type);
		
		this.subscribed(oResult.IsSubscribed);
		this.selectable(oResult.IsSelectable);
		this.existen(oResult.IsExists);
		
		if (oResult.Extended)
		{
			this.setRelevantInformation(oResult.Extended.UidNext.toString(), oResult.Extended.Hash, 
				oResult.Extended.MessageCount, oResult.Extended.MessageUnseenCount, false);
		}

		if(_.find(aFolders, function(sFolder){ return sFolder === this.name(); }, this))
		{
			this.expanded(true);
		}

		return oResult.SubFolders;
	}

	return null;
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CFolderModel.prototype.onMessageGetResponse = function (oResponse, oRequest)
{
	var
		oResult = oResponse.Result,
		oHand = null,
		sUid = oResult ? oResult.Uid.toString() : oRequest.Uid.toString(),
		oMessage = this.oMessages[sUid],
		bSelected = oMessage ? oMessage.selected() : false
	;
	
	if (!oResult)
	{
		if (bSelected)
		{
			App.Api.showErrorByCode(oResponse, Utils.i18n('WARNING/UNKNOWN_ERROR'));
			App.Routing.replaceHashWithoutMessageUid(sUid);
		}
		
		oMessage = null;
	}
	else
	{
		oMessage = this.parseAndCacheMessage(oResult, false, false);
		if (oMessage && oMessage.ical() && oMessage.ical().isReplyType() && App.CalendarCache)
		{
			App.CalendarCache.calendarChanged(true);
		}
	}

	oHand = this.aResponseHandlers[sUid];
	if (oHand)
	{
		oHand.handler.call(oHand.context, oMessage, sUid);
		delete this.aResponseHandlers[sUid];
	}
};

/**
 * @param {string} sUid
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 */
CFolderModel.prototype.getCompletelyFilledMessage = function (sUid, fResponseHandler, oContext)
{
	var
		oMessage = this.oMessages[sUid],
		oParameters = {
			'Action': 'MessageGet',
			'Folder': this.fullName(),
			'Uid': sUid
		}
	;

	if (sUid.length > 0)
	{
		if (!oMessage || !oMessage.completelyFilled() || oMessage.trimmed())
		{
			if (fResponseHandler && oContext)
			{
				this.aResponseHandlers[sUid] = {handler: fResponseHandler, context: oContext};
			}
			
			App.Ajax.send(oParameters, this.onMessageGetResponse, this);
		}
		else if (fResponseHandler && oContext)
		{
			fResponseHandler.call(oContext, oMessage, sUid);
		}
	}
};

/**
 * @param {string} sUid
 */
CFolderModel.prototype.showExternalPictures = function (sUid)
{
	var oMessage = this.oMessages[sUid];

	if (oMessage !== undefined)
	{
		oMessage.showExternalPictures();
	}
};

/**
 * @param {string} sEmail
 */
CFolderModel.prototype.alwaysShowExternalPicturesForSender = function (sEmail)
{
	_.each(this.oMessages, function (oMessage)
	{
		var aFrom = oMessage.oFrom.aCollection;
		if (aFrom.length > 0 && aFrom[0].sEmail === sEmail)
		{
			oMessage.alwaysShowExternalPicturesForSender();
		}
	}, this);
};

/**
 * @param {string} sField
 * @param {Array} aUids
 * @param {boolean} bSetAction
 */
CFolderModel.prototype.executeGroupOperation = function (sField, aUids, bSetAction)
{
	var iUnseenDiff = 0;

	_.each(this.oMessages, function (oMessage)
	{
		if (aUids.length > 0)
		{
			_.each(aUids, function (sUid)
			{
				if (oMessage && oMessage.uid() === sUid && oMessage[sField]() !== bSetAction)
				{
					oMessage[sField](bSetAction);
					iUnseenDiff++;
				}
			});
		}
		else
		{
			oMessage[sField](bSetAction);
		}
	});

	if (aUids.length === 0)
	{
		iUnseenDiff = (bSetAction) ? this.unseenMessageCount() : this.messageCount() - this.unseenMessageCount();
	}

	if (sField === 'seen' && iUnseenDiff > 0)
	{
		if (bSetAction)
		{
			this.addMessagesCountsDiff(0, -iUnseenDiff);
		}
		else
		{
			this.addMessagesCountsDiff(0, iUnseenDiff);
		}
		this.markHasChanges();
	}
};

CFolderModel.prototype.emptyFolder = function ()
{
	var
		sWarning = Utils.i18n('MAILBOX/CONFIRM_EMPTY_FOLDER'),
		fCallBack = _.bind(this.clearFolder, this)
	;
	
	if (this.enableEmptyFolder())
	{
		App.Screens.showPopup(ConfirmPopup, [sWarning, fCallBack]);
	}
};

/**
 * @param {boolean} bOkAnswer
 */
CFolderModel.prototype.clearFolder = function (bOkAnswer)
{
	var
		oParameters = {
			'Action': 'FolderClear',
			'Folder': this.fullName()
		}
	;
	
	if (this.enableEmptyFolder() && bOkAnswer)
	{
		App.Ajax.send(oParameters);

		this.removeAllMessages();

		App.MailCache.onClearFolder(this);
	}
};

CFolderModel.prototype.getNameWhithLevel = function ()
{
	var iLevel = this.level();
	
	if (!this.isNamespace() && iLevel > 0)
	{
		iLevel--;
	}
	
	return Utils.strRepeat("\u00A0", iLevel * 3) + this.name();
};

/**
 * @param {Object} oFolder
 * @param {Object} oEvent
 */
CFolderModel.prototype.onAccordion = function (oFolder, oEvent)
{
	var bExpanded = !this.expanded(),
		aFolders = App.Storage.getData('folderAccordion') || [];

	if (bExpanded)
	{
		aFolders.push(this.name());
	}
	else
	{
		// remove current folder from expanded folders
		aFolders = _.reject(aFolders, function(sFolder){ return sFolder === this.name(); }, this);
	}

	App.Storage.setData('folderAccordion', aFolders);
	this.expanded(bExpanded);

	App.MailCache.countMessages(this);
	
	oEvent.stopPropagation();
};

CFolderModel.prototype.executeUnseenFilter = function ()
{
	var bNotChanged = false;
	if (this.showUnseenMessages())
	{
		App.MailCache.waitForUnseenMessages(true);
		bNotChanged = App.Routing.setHash(App.Links.mailbox(this.fullName(), 1, '', '', Enums.FolderFilter.Unseen));

		if (bNotChanged)
		{
			App.MailCache.changeCurrentMessageList(this.fullName(), 1, '', Enums.FolderFilter.Unseen);
		}
		return false;
	}
	return true;
};


/**
 * @constructor
 */
function CFolderListModel()
{
	this.iAccountId = 0;

	this.bInitialized = ko.observable(false);
	this.expandFolders = ko.observable(false);
	this.expandNames = ko.observableArray([]);
	this.collection = ko.observableArray([]);
	this.options = ko.observableArray([]);
	this.sNamespace = '';
	this.sNamespaceFolder = '';
	this.oStarredFolder = null;

	this.oNamedCollection = {};

	var
		self = this,
		fSetSystemType = function (iType) {
			return function (oFolder) {
				if (oFolder)
				{
					oFolder.type(iType);
				}
			};
		},
		fFullNameHelper = function (fFolder) {
			return {
				'read': function () {
					this.collection();
					return fFolder() ? fFolder().fullName() : '';
				},
				'write': function (sValue) {
					fFolder(this.getFolderByFullName(sValue));
				},
				'owner': self
			};
		}
	;

	this.currentFolder = ko.observable(null);

	this.inboxFolder = ko.observable(null);
	this.sentFolder = ko.observable(null);
	this.draftsFolder = ko.observable(null);
	this.spamFolder = ko.observable(null);
	this.trashFolder = ko.observable(null);
	
	this.countsCompletelyFilled = ko.observable(false);

	this.inboxFolder.subscribe(fSetSystemType(Enums.FolderTypes.User), this, 'beforeChange');
	this.sentFolder.subscribe(fSetSystemType(Enums.FolderTypes.User), this, 'beforeChange');
	this.draftsFolder.subscribe(fSetSystemType(Enums.FolderTypes.User), this, 'beforeChange');
	this.spamFolder.subscribe(fSetSystemType(Enums.FolderTypes.User), this, 'beforeChange');
	this.trashFolder.subscribe(fSetSystemType(Enums.FolderTypes.User), this, 'beforeChange');
	
	this.inboxFolder.subscribe(fSetSystemType(Enums.FolderTypes.Inbox));
	this.sentFolder.subscribe(fSetSystemType(Enums.FolderTypes.Sent));
	this.draftsFolder.subscribe(fSetSystemType(Enums.FolderTypes.Drafts));
	this.spamFolder.subscribe(fSetSystemType(Enums.FolderTypes.Spam));
	this.trashFolder.subscribe(fSetSystemType(Enums.FolderTypes.Trash));
	
	this.inboxFolderFullName = ko.computed(fFullNameHelper(this.inboxFolder));
	this.sentFolderFullName = ko.computed(fFullNameHelper(this.sentFolder));
	this.draftsFolderFullName = ko.computed(fFullNameHelper(this.draftsFolder));
	this.spamFolderFullName = ko.computed(fFullNameHelper(this.spamFolder));
	this.trashFolderFullName = ko.computed(fFullNameHelper(this.trashFolder));
	
	this.currentFolderFullName = ko.computed(fFullNameHelper(this.currentFolder));
	this.currentFolderType = ko.computed(function () {
		return this.currentFolder() ? this.currentFolder().type() : Enums.FolderTypes.User;
	}, this);
	
	this.delimiter = ko.computed(function (){
		return this.inboxFolder() ? this.inboxFolder().delimiter() : '';
	}, this);
}

CFolderListModel.prototype.getTotalMessageCount = function ()
{
	var iCount = 0;
	
	_.each(this.oNamedCollection, function (oFolder) {
		iCount += oFolder.messageCount();
	}, this);
	
	return iCount;
};

/**
 * @returns {Array}
 */
CFolderListModel.prototype.getFoldersWithoutCountInfo = function ()
{
	var aFolders = _.compact(_.map(this.oNamedCollection, function(oFolder, sFullName) {
		if (oFolder.canBeSelected() && !oFolder.hasExtendedInfo())
		{
			return sFullName;
		}
		
		return null;
	}));
	
	return aFolders;
};

/**
 * @param {string} sFolderFullName
 * @param {string} sFilters
 */
CFolderListModel.prototype.setCurrentFolder = function (sFolderFullName, sFilters)
{
	var
		oFolder = this.getFolderByFullName(sFolderFullName)
	;
	
	if (oFolder === null)
	{
		oFolder = this.inboxFolder();
	}
	
	if (oFolder !== null)
	{
		if (this.currentFolder())
		{
			this.currentFolder().selected(false);
			if (this.oStarredFolder)
			{
				this.oStarredFolder.selected(false);
			}
		}
		
		this.currentFolder(oFolder);
		if (sFilters === Enums.FolderFilter.Flagged)
		{
			if (this.oStarredFolder)
			{
				this.oStarredFolder.selected(true);
			}
		}
		else
		{
			this.currentFolder().selected(true);
		}
	}
};

/**
 * Returns a folder, found by the type.
 * 
 * @param {number} iType
 * @returns {CFolderModel|null}
 */
CFolderListModel.prototype.getFolderByType = function (iType)
{
	switch (iType) 
	{
		case Enums.FolderTypes.Inbox:
			return this.inboxFolder();
		case Enums.FolderTypes.Sent:
			return this.sentFolder();
		case Enums.FolderTypes.Drafts:
			return this.draftsFolder();
		case Enums.FolderTypes.Trash:
			return this.trashFolder();
		case Enums.FolderTypes.Spam:
			return this.spamFolder();
	}
	
	return null;
};

/**
 * Returns a folder, found by the full name.
 * 
 * @param {string} sFolderFullName
 * @returns {CFolderModel|null}
 */
CFolderListModel.prototype.getFolderByFullName = function (sFolderFullName)
{
	var
		oFolder = this.oNamedCollection[sFolderFullName]
	;
	
	return oFolder ? oFolder : null;
};

/**
 * Calls a recursive parsing of the folder tree.
 * 
 * @param {number} iAccountId
 * @param {Object} oData
 * @param {Object} oNamedFolderListOld
 */
CFolderListModel.prototype.parse = function (iAccountId, oData, oNamedFolderListOld)
{
	this.iAccountId = iAccountId;
	this.sNamespace = Utils.pString(oData.Namespace);
	this.bInitialized(true);

	if (this.sNamespace.length > 0)
	{
		this.sNamespaceFolder = this.sNamespace.substring(0, this.sNamespace.length - 1);
	}
	else
	{
		this.sNamespaceFolder = this.sNamespace;
	}

	this.expandFolders(AppData['MailExpandFolders'] && !App.Storage.hasData('folderAccordion'));
	if (!App.Storage.hasData('folderAccordion'))
	{
		App.Storage.setData('folderAccordion', []);
	}
	
	this.collection(this.parseRecursively(oData['@Collection'], oNamedFolderListOld));
};

/**
 * Recursively parses the folder tree.
 * 
 * @param {Array} aRowCollection
 * @param {Object} oNamedFolderListOld
 * @param {number=} iLevel
 * @param {string=} sParentFullName
 * @returns {Array}
 */
CFolderListModel.prototype.parseRecursively = function (aRowCollection, oNamedFolderListOld, iLevel, sParentFullName)
{
	var
		self = this,
		aParsedCollection = [],
		iIndex = 0,
		iLen = 0,
		oFolder = null,
		oFolderOld = null,
		sFolderFullName = '',
		oSubFolders = null,
		aSubfolders = [],
		bFolderIsNamespace = false,
		bExpandFolders = this.expandFolders(),
		oAccount = AppData.Accounts.getAccount(this.iAccountId),
		fDetectSpamFolder = function () {
			var oSpamFolder = self.getFolderByType(Enums.FolderTypes.Spam);
			if (!oAccount || !oAccount.extensionExists('AllowSpamFolderExtension'))
			{
				oSpamFolder.type(Enums.FolderTypes.User);
				self.spamFolder(null);
			}
		},
		fAccountExtensionsRequestedSubscribe = function () {
			if (oAccount && oAccount.extensionsRequested())
			{
				fDetectSpamFolder();
				oAccount.extensionsRequestedSubscription.dispose();
				oAccount.extensionsRequestedSubscription = undefined;
			}
		}
	;

	sParentFullName = sParentFullName || '';
	
	if (Utils.isUnd(iLevel))
	{
		iLevel = -1;
	}

	iLevel++;
	if (_.isArray(aRowCollection))
	{
		for (iLen = aRowCollection.length; iIndex < iLen; iIndex++)
		{
			sFolderFullName = Utils.pString(aRowCollection[iIndex].FullNameRaw);
			oFolderOld = oNamedFolderListOld[sFolderFullName];
			oFolder = new CFolderModel(this);
			oFolder.iAccountId = this.iAccountId;
			oSubFolders = oFolder.parse(aRowCollection[iIndex], sParentFullName);
			if (oFolderOld && oFolderOld.hasExtendedInfo() && !oFolder.hasExtendedInfo())
			{
				oFolder.setRelevantInformation(oFolderOld.uidNext(), oFolderOld.hash(), 
					oFolderOld.messageCount(), oFolderOld.unseenMessageCount(), false);
			}

			if (bExpandFolders && oSubFolders !== null)
			{
				oFolder.expanded(true);
				this.expandNames().push(Utils.pString(aRowCollection[iIndex].Name));
			}

			bFolderIsNamespace = (this.sNamespace === oFolder.fullName() + oFolder.delimiter());
			oFolder.isNamespace(bFolderIsNamespace);
			oFolder.level(iLevel);

			this.oNamedCollection[oFolder.fullName()] = oFolder;

			switch (oFolder.type())
			{
				case Enums.FolderTypes.Inbox:
					this.inboxFolder(oFolder);
					break;
				case Enums.FolderTypes.Sent:
					this.sentFolder(oFolder);
					break;
				case Enums.FolderTypes.Drafts:
					this.draftsFolder(oFolder);
					break;
				case Enums.FolderTypes.Trash:
					this.trashFolder(oFolder);
					break;
				case Enums.FolderTypes.Spam:
					this.spamFolder(oFolder);
					if (oAccount.extensionsRequested())
					{
						fDetectSpamFolder();
					}
					else
					{
						oAccount.extensionsRequestedSubscription = oAccount.extensionsRequested.subscribe(fAccountExtensionsRequestedSubscribe);
					}
					break;
			}

			aParsedCollection.push(oFolder);
			
			if (oSubFolders === null && oFolder.type() === Enums.FolderTypes.Inbox)
			{
				this.createStarredFolder(oFolder.fullName(), iLevel);
				if (this.oStarredFolder)
				{
					aParsedCollection.push(this.oStarredFolder);
				}
			}
			else if (oSubFolders !== null)
			{
				aSubfolders = this.parseRecursively(oSubFolders['@Collection'], oNamedFolderListOld, iLevel, oFolder.fullName());
				if(oFolder.type() === Enums.FolderTypes.Inbox)
				{
					if (oFolder.isNamespace())
					{
						this.createStarredFolder(oFolder.fullName(), iLevel + 1);
						if (this.oStarredFolder)
						{
							aSubfolders.unshift(this.oStarredFolder);
						}
					}
					else
					{
						this.createStarredFolder(oFolder.fullName(), iLevel);
						if (this.oStarredFolder)
						{
							aParsedCollection.push(this.oStarredFolder);
						}
					}
				}
				oFolder.subfolders(aSubfolders);
			}
		}

		if (bExpandFolders)
		{
			App.Storage.setData('folderAccordion', this.expandNames());
		}
	}

	return aParsedCollection;
};

/**
 * @param {string} sFullName
 * @param {number} iLevel
 */
CFolderListModel.prototype.createStarredFolder = function (sFullName, iLevel)
{
	var oStarredFolder = new CFolderModel(this);
	oStarredFolder.iAccountId = this.iAccountId;
	oStarredFolder.virtual(true);
	oStarredFolder.level(iLevel);
	oStarredFolder.fullName(sFullName);
	oStarredFolder.name(Utils.i18n('MAIN/FOLDER_STARRED'));
	oStarredFolder.type(Enums.FolderTypes.Starred);
	oStarredFolder.routingHash(App.Routing.buildHashFromArray(App.Links.mailbox(oStarredFolder.fullName(), 1, '', '', Enums.FolderFilter.Flagged)));
	this.oStarredFolder = oStarredFolder;
};

/**
 * @param {string} sFirstItem
 * @param {boolean=} bEnableSystem = false
 * @param {boolean=} bHideInbox = false
 * @param {boolean=} bIgnoreCanBeSelected = false
 * @returns {Array}
 */
CFolderListModel.prototype.getOptions = function (sFirstItem, bEnableSystem, bHideInbox, bIgnoreCanBeSelected)
{
	var
		sDeepPrefix = '\u00A0\u00A0\u00A0\u00A0',
		fGetOptionsFromCollection = function (aOrigCollection) {

			var
				iIndex = 0,
				iLen = 0,
				oItem = null,
				aResCollection = []
			;
			
			if (Utils.isUnd(bEnableSystem))
			{
				bEnableSystem = false;
			}
			
			if (Utils.isUnd(bHideInbox))
			{
				bHideInbox = false;
			}
			
			if (Utils.isUnd(bIgnoreCanBeSelected))
			{
				bIgnoreCanBeSelected = false;
			}

			for (iIndex = 0, iLen = aOrigCollection.length; iIndex < iLen; iIndex++)
			{
				oItem = aOrigCollection[iIndex];
				
				if (!oItem.virtual() && (oItem.type() !== Enums.FolderTypes.Inbox && bHideInbox || !bHideInbox))
				{
					aResCollection.push({
						'name': oItem.name(),
						'fullName': oItem.fullName(),
						'displayName': (new Array(oItem.level() + 1)).join(sDeepPrefix) + oItem.name(),
						'translatedDisplayName': (new Array(oItem.level() + 1)).join(sDeepPrefix) + oItem.displayName(),
						'disable': ((oItem.isSystem() && !bEnableSystem) || (!bIgnoreCanBeSelected && !oItem.canBeSelected()))
					});
				}
				
				aResCollection = aResCollection.concat(fGetOptionsFromCollection(oItem.subfolders()));
			}

			return aResCollection;
		},
		aCollection = fGetOptionsFromCollection(this.collection())
	;

	if (sFirstItem !== '')
	{
		aCollection.unshift({
			'name': sFirstItem,
			'fullName': '',
			'displayName': sFirstItem,
			'translatedDisplayName': sFirstItem,
			'disable': false
		});
	}
	
	return aCollection;
};

/**
 * @param {Object} oFolderToDelete
 */
CFolderListModel.prototype.deleteFolder = function (oFolderToDelete)
{
	var
		fRemoveFolder = function (oFolder) {
			if (oFolderToDelete && oFolderToDelete.fullName() === oFolder.fullName())
			{
				return true;
			}
			oFolder.subfolders.remove(fRemoveFolder);
			return false;
		}
	;

	this.collection.remove(fRemoveFolder);
};

/**
 * @constructor
 */
function CMessageModel()
{
	this.accountId = ko.observable(AppData.Accounts.currentId());

	this.folder = ko.observable('');
	this.uid = ko.observable('');
	this.subject = ko.observable('');
	this.emptySubject = ko.computed(function () {
		return (Utils.trim(this.subject()) === '');
	}, this);
	this.subjectForDisplay = ko.computed(function () {
		return this.emptySubject() ? Utils.i18n('MAILBOX/EMPTY_SUBJECT') : this.subject();
	}, this);
	this.messageId = ko.observable('');
	this.size = ko.observable(0);
	this.friendlySize = ko.computed(function () {
		return Utils.friendlySize(this.size());
	}, this);
	this.textSize = ko.observable(0);
	this.oDateModel = new CDateModel();
	this.fullDate = ko.observable('');
	this.oFrom = new CAddressListModel();
	this.fullFrom = ko.observable('');
	this.oTo = new CAddressListModel();
	this.to = ko.observable('');
	this.fromOrToText = ko.observable('');
	this.oCc = new CAddressListModel();
	this.cc = ko.observable('');
	this.oBcc = new CAddressListModel();
	this.bcc = ko.observable('');
	this.oSender = new CAddressListModel();
	this.oReplyTo = new CAddressListModel();
	
	this.seen = ko.observable(false);
	
	this.flagged = ko.observable(false);
	this.partialFlagged = ko.observable(false);
	this.answered = ko.observable(false);
	this.forwarded = ko.observable(false);
	this.hasAttachments = ko.observable(false);
	this.hasIcalAttachment = ko.observable(false);
	this.hasVcardAttachment = ko.observable(false);
	this.showCalendarIcon = ko.computed(function () {
		return AppData.User.AllowCalendar && this.hasIcalAttachment();
	}, this);

	this.folderObject = ko.computed(function () {
		return App.MailCache.getFolderByFullName(this.accountId(), this.folder());
	}, this);
	this.threadsAllowed = ko.computed(function () {
		var
			oFolder = this.folderObject(),
			bFolderWithoutThreads = oFolder && (oFolder.type() === Enums.FolderTypes.Drafts || 
				oFolder.type() === Enums.FolderTypes.Spam || oFolder.type() === Enums.FolderTypes.Trash)
		;
		return AppData.User.useThreads() && !bFolderWithoutThreads;
	}, this);
	this.otherSendersAllowed = ko.computed(function () {
		var oFolder = this.folderObject();
		return oFolder && (oFolder.type() !== Enums.FolderTypes.Drafts) && (oFolder.type() !== Enums.FolderTypes.Sent);
	}, this);
	
	this.threadPart = ko.observable(false);
	this.threadPart.subscribe(function () {
		if (this.threadPart())
		{
			this.partialFlagged(false);
			this.threadSenders(false);
		}
	}, this);
	this.threadParentUid = ko.observable('');
	
	this.threadUids = ko.observableArray([]);
	this.threadSenders = ko.observableArray([]);
	this.threadMoreSendersText = ko.observable('');
	this.threadSendersText = ko.computed(function () {
		if (this.otherSendersAllowed())
		{
			var aSenders = this.threadSenders();

			if (aSenders.length > 3)
			{
				this.threadMoreSendersText(Utils.i18n('MAILBOX/THREAD_MORE_SENDERS', {'COUNT': aSenders.length - 1}));
				return ', ' + aSenders[0];
			}
			else
			{
				this.threadMoreSendersText('');
			}
			
			if (aSenders.length > 0)
			{
				aSenders.unshift('');
				return aSenders.join(', ');
			}
		}
		return '';
	}, this);
	this.threadSendersVisible = ko.computed(function () {
		return this.threadSendersText().length > 0;
	}, this);
	this.threadMoreSendersVisible = ko.computed(function () {
		return this.threadMoreSendersText().length > 0;
	}, this);
	this.threadCount = ko.computed(function () {
		return this.threadUids().length;
	}, this);
	this.threadUnreadCount = ko.observable(0);
	this.threadOpened = ko.observable(false);
	this.threadLoading = ko.observable(false);
	this.threadLoadingVisible = ko.computed(function () {
		return this.threadsAllowed() && this.threadOpened() && this.threadLoading();
	}, this);
	this.threadCountVisible = ko.computed(function () {
		return this.threadsAllowed() && this.threadCount() > 0 && !this.threadLoading();
	}, this);
	this.threadCountHint = ko.computed(function () {
		if (this.threadCount() > 0)
		{
			if (this.threadOpened())
			{
				return  Utils.i18n('MAILBOX/THREAD_TOOLTIP_FOLD');
			}
			else
			{
				if (this.threadUnreadCount() > 0)
				{
					return  Utils.i18n('MAILBOX/THREAD_TOOLTIP_HAS_UNSEEN_PLURAL', {}, null, this.threadUnreadCount());
				}
				else
				{
					return  Utils.i18n('MAILBOX/THREAD_TOOLTIP_UNFOLD');
				}
			}
		}
		return '';
	}, this);
	this.threadCountForLoad = ko.observable(5);
	this.threadNextLoadingVisible = ko.observable(false);
	this.threadNextLoadingLinkVisible = ko.observable(false);
	this.threadFunctionLoadNext = null;
	this.threadShowAnimation = ko.observable(false);
	this.threadHideAnimation = ko.observable(false);
	
	this.importance = ko.observable(Enums.Importance.Normal);
	this.draftInfo = ko.observableArray([]);
	this.sensitivity = ko.observable(Enums.Sensivity.Nothing);
	this.hash = ko.observable('');
	this.downloadLink = ko.computed(function () {
		return (this.hash().length > 0) ? Utils.getDownloadLinkByHash(this.accountId(), this.hash()) : '';
	}, this);

	this.completelyFilled = ko.observable(false);

	this.checked = ko.observable(false);
	this.checked.subscribe(function (bChecked) {
		if (!this.threadOpened() && App.MailCache.useThreadsInCurrentList())
		{
			var
				oFolder = App.MailCache.folderList().getFolderByFullName(this.folder())
			;
			_.each(this.threadUids(), function (sUid) {
				var oMessage = oFolder.oMessages[sUid];
				if (oMessage)
				{
					oMessage.checked(bChecked);
				}
			});
		}
	}, this);
	this.selected = ko.observable(false);
	this.deleted = ko.observable(false); // temporary removal until it was confirmation from the server to delete

	this.trimmed = ko.observable(false);
	this.trimmedTextSize = ko.observable(0);
	this.inReplyTo = ko.observable('');
	this.references = ko.observable('');
	this.readingConfirmation = ko.observable('');
	this.isPlain = ko.observable(false);
	this.text = ko.observable('');
	this.textBodyForNewWindow = ko.observable('');
	this.$text = null;
	this.rtl = ko.observable(false);
	this.hasExternals = ko.observable(false);
	this.isExternalsShown = ko.observable(false);
	this.isExternalsAlwaysShown = ko.observable(false);
	this.foundedCids = ko.observableArray([]);
	this.attachments = ko.observableArray([]);
	this.usesAttachmentString = false;
	this.allAttachmentsHash = '';
	this.safety = ko.observable(false);
	this.sourceHeaders = ko.observable('');

	this.date = ko.observable('');
	
	this.ical = ko.observable(null);
	this.vcard = ko.observable(null);
	
	this.textRaw = ko.observable('');
	this.encryptedMessage = ko.observable(false);
	this.signedMessage = ko.observable(false);
	
	this.domMessageForPrint = ko.observable(null);
	
	this.Custom = {};
	
	if (App.nowMoment)
	{
		App.nowMoment.subscribe(function () {
			this.updateMomentDate();
		}, this);
	}
}

/**
 * @param {Object} oWin
 */
CMessageModel.prototype.viewMessage = function (oWin)
{
	var
		oDomText = this.getDomText(Utils.getAppPath()),
		sHtml = ''
	;
	
	this.textBodyForNewWindow(oDomText.html());
	sHtml = $(this.domMessageForPrint()).html();
	
	if (oWin)
	{
		$(oWin.document.body).html(sHtml);
		oWin.focus();
		_.each(this.attachments(), function (oAttach) {
			var oLink = $(oWin.document.body).find("[data-hash='download-" + oAttach.hash() + "']");
			oLink.on('click', _.bind(oAttach.downloadFile, oAttach, App));
			
			oLink = $(oWin.document.body).find("[data-hash='view-" + oAttach.hash() + "']");
			oLink.on('click', _.bind(oAttach.viewFile, oAttach));
		}, this);
	}
};

/**
 * Fields accountId, folder, oTo & oFrom should be filled.
 */
CMessageModel.prototype.fillFromOrToText = function ()
{
	var
		oFolder = App.MailCache.getFolderByFullName(this.accountId(), this.folder()),
		oAccount = AppData.Accounts.getAccount(this.accountId())
	;
	
	if (oFolder.type() === Enums.FolderTypes.Drafts || oFolder.type() === Enums.FolderTypes.Sent)
	{
		this.fromOrToText(this.oTo.getDisplay(Utils.i18n('MESSAGE/ME_RECIPIENT'), oAccount.email()));
	}
	else
	{
		this.fromOrToText(this.oFrom.getDisplay(Utils.i18n('MESSAGE/ME_SENDER'), oAccount.email()));
	}
};

/**
 * @param {Array} aChangedThreadUids
 * @param {number} iLoadedMessagesCount
 */
CMessageModel.prototype.changeThreadUids = function (aChangedThreadUids, iLoadedMessagesCount)
{
	this.threadUids(aChangedThreadUids);
	this.threadLoading(iLoadedMessagesCount < Math.min(this.threadUids().length, this.threadCountForLoad()));
};

/**
 * @param {Function} fLoadNext
 */
CMessageModel.prototype.showNextLoadingLink = function (fLoadNext)
{
	if (this.threadNextLoadingLinkVisible())
	{
		this.threadNextLoadingVisible(true);
		this.threadFunctionLoadNext = fLoadNext;
	}
};

CMessageModel.prototype.increaseThreadCountForLoad = function ()
{
	this.threadCountForLoad(this.threadCountForLoad() + 5);
	App.MailCache.showOpenedThreads(this.folder());
};

CMessageModel.prototype.loadNextMessages = function ()
{
	if (this.threadFunctionLoadNext)
	{
		this.threadFunctionLoadNext();
		this.threadNextLoadingLinkVisible(false);
		this.threadFunctionLoadNext = null;
	}
};

/**
 * @param {number} iShowThrottle
 * @param {string} sParentUid
 */
CMessageModel.prototype.markAsThreadPart = function (iShowThrottle, sParentUid)
{
	var self = this;
	
	this.threadPart(true);
	this.threadParentUid(sParentUid);
	this.threadUids([]);
	this.threadNextLoadingVisible(false);
	this.threadNextLoadingLinkVisible(true);
	this.threadFunctionLoadNext = null;
	this.threadHideAnimation(false);
	
	setTimeout(function () {
		self.threadShowAnimation(true);
	}, iShowThrottle);
};

/**
 * @param {AjaxMessageResponse} oData
 * @param {number} iAccountId
 * @param {boolean} bThreadPart
 * @param {boolean} bTrustThreadInfo
 */
CMessageModel.prototype.parse = function (oData, iAccountId, bThreadPart, bTrustThreadInfo)
{
	var
		oIcal = null,
		oVcard = null,
		sHtml = '',
		sPlain = ''
	;
	
	if (bTrustThreadInfo)
	{
		this.threadPart(bThreadPart);
	}
	if (!this.threadPart())
	{
		this.threadParentUid('');
	}
	
	if (oData['@Object'] === 'Object/MessageListItem')
	{
		this.seen(!!oData.IsSeen);
		this.flagged(!!oData.IsFlagged);
		this.answered(!!oData.IsAnswered);
		this.forwarded(!!oData.IsForwarded);
		
		if (oData.Custom)
		{
			this.Custom = oData.Custom;
		}
	}
	
	if (oData['@Object'] === 'Object/Message' || oData['@Object'] === 'Object/MessageListItem')
	{
		this.accountId(iAccountId);
		
		this.folder(oData.Folder);
		this.uid(Utils.pString(oData.Uid));
		this.subject(Utils.pString(oData.Subject));
		this.messageId(Utils.pString(oData.MessageId));
		this.size(oData.Size);
		this.textSize(oData.TextSize);
		this.oDateModel.parse(oData.TimeStampInUTC);
		this.oFrom.parse(oData.From);
		this.oTo.parse(oData.To);
		this.fillFromOrToText();
		this.oCc.parse(oData.Cc);
		this.oBcc.parse(oData.Bcc);
		this.oSender.parse(oData.Sender);
		this.oReplyTo.parse(oData.ReplyTo);
		
		this.fullDate(this.oDateModel.getFullDate());
		this.fullFrom(this.oFrom.getFull());
		this.to(this.oTo.getFull());
		this.cc(this.oCc.getFull());
		this.bcc(this.oBcc.getFull());
		
		this.hasAttachments(!!oData.HasAttachments);
		this.hasIcalAttachment(!!oData.HasIcalAttachment);
		this.hasVcardAttachment(!!oData.HasVcardAttachment);
		
		if (oData['@Object'] === 'Object/MessageListItem' && bTrustThreadInfo)
		{
			this.threadUids(_.map(oData.Threads, function (iUid) {
				return iUid.toString();
			}, this));
		}
		
		this.importance(oData.Priority);
		if (_.isArray(oData.DraftInfo))
		{
			this.draftInfo(oData.DraftInfo);
		}
		this.sensitivity(oData.Sensitivity);
		this.hash(Utils.pString(oData.Hash));

		if (oData['@Object'] === 'Object/Message')
		{
			this.trimmed(oData.Trimmed);
			this.trimmedTextSize(oData.TrimmedTextSize);
			this.inReplyTo(oData.InReplyTo);
			this.references(oData.References);
			this.readingConfirmation(oData.ReadingConfirmation);
			sHtml = Utils.pString(oData.Html);
			sPlain = Utils.pString(oData.Plain);
			if (sHtml !== '')
			{
				this.text(sHtml);
				this.isPlain(false);
			}
			else
			{
				this.textRaw(oData.PlainRaw);
				if (this.textRaw().indexOf('-----BEGIN PGP MESSAGE-----') !== -1)
				{
					this.text('<pre>' + Utils.encodeHtml(this.textRaw()) + '</pre>');
					this.encryptedMessage(true);
				}
				else if (this.textRaw().indexOf('-----BEGIN PGP SIGNED MESSAGE-----') !== -1)
				{
					this.text('<pre>' + Utils.encodeHtml(this.textRaw()) + '</pre>');
					this.signedMessage(true);
				}
				else
				{
					this.text(sPlain !== '' ? '<div>' + sPlain + '</div>' : '');
				}
				this.isPlain(true);
			}
			this.$text = null;
			this.isExternalsShown(false);
			this.rtl(oData.Rtl);
			this.hasExternals(!!oData.HasExternals);
			this.foundedCids(oData.FoundedCIDs);
			this.parseAttachments(oData.Attachments, iAccountId);
			this.safety(oData.Safety);
			this.sourceHeaders(oData.Headers);
			
			if (oData.ICAL !== null)
			{
				oIcal = new CIcalModel();
				oIcal.parse(oData.ICAL, AppData.Accounts.getAttendee(this.oTo.getEmails()));
				this.ical(oIcal);
			}
			
			if (oData.VCARD !== null)
			{
				oVcard = new CVcardModel();
				oVcard.parse(oData.VCARD);
				this.vcard(oVcard);
			}
			
			this.completelyFilled(true);
		}

		this.updateMomentDate();
	}
};

CMessageModel.prototype.updateMomentDate = function ()
{
	this.date(this.oDateModel.getShortDate(moment().clone().subtract('days', 1).format('L') ===
		moment.unix(this.oDateModel.getTimeStampInUTC()).format('L')));
};

/**
 * @param {string=} sAppPath = ''
 * @param {boolean=} bForcedShowPictures
 * 
 * return {Object}
 */
CMessageModel.prototype.getDomText = function (sAppPath, bForcedShowPictures)
{
	var $text = this.$text;
	
	sAppPath = sAppPath || '';
	
	if (this.$text === null || sAppPath !== '')
	{
		if (this.completelyFilled())
		{
			this.$text = $(this.text());

			this.showInlinePictures(sAppPath);
			if (this.safety() === true)
			{
				this.alwaysShowExternalPicturesForSender();
			}
			
			if (bForcedShowPictures && this.isExternalsShown())
			{
				this.showExternalPictures();
			}
			
			$text = this.$text;
		}
		else
		{
			$text = $('');
		}
	}
	
	//returns a clone, because it uses both in the parent window and the new
	return $text.clone();
};

/**
 * @param {string=} sAppPath = ''
 * @param {boolean=} bForcedShowPictures
 * 
 * return {string}
 */
CMessageModel.prototype.getConvertedHtml = function (sAppPath, bForcedShowPictures)
{
	var oDomText = this.getDomText(sAppPath, bForcedShowPictures);
	return (oDomText.length > 0) ? oDomText.wrap('<p>').parent().html() : '';
};

/**
 * Parses attachments.
 *
 * @param {Array} aData
 * @param {number} iAccountId
 */
CMessageModel.prototype.parseAttachments = function (aData, iAccountId)
{
	if (_.isArray(aData))
	{
		var sThumbSessionUid = Date.now().toString();

		this.attachments(_.map(aData, function (oRawAttach) {
			var oAttachment = new CMailAttachmentModel();
			oAttachment.parse(oRawAttach, iAccountId);
			oAttachment.getInThumbQueue(sThumbSessionUid);
			oAttachment.setMessageData(this.folder(), this.uid());
			return oAttachment;
		}, this));
	}
};

/**
 * Parses an array of email addresses.
 *
 * @param {Array} aData
 * @return {Array}
 */
CMessageModel.prototype.parseAddressArray = function (aData)
{
	var
		aAddresses = []
	;

	if (_.isArray(aData))
	{
		aAddresses = _.map(aData, function (oRawAddress) {
			var oAddress = new CAddressModel();
			oAddress.parse(oRawAddress);
			return oAddress;
		});
	}

	return aAddresses;
};

/**
 * Finds and returns the specified Attachment cid.
 *
 * @param {string} sCid
 * @return {*}
 */
CMessageModel.prototype.findAttachmentByCid = function (sCid)
{
	return _.find(this.attachments(), function (oAttachment) {
		return oAttachment.cid() === sCid;
	});
};

/**
 * Finds and returns the specified Attachment Content Location.
 *
 * @param {string} sContentLocation
 * @return {*}
 */
CMessageModel.prototype.findAttachmentByContentLocation = function (sContentLocation)
{
	return _.find(this.attachments(), function (oAttachment) {
		return oAttachment.contentLocation() === sContentLocation;
	});
};

/**
 * Displays embedded images, which have cid on the list.
 * 
 * @param {string} sAppPath
 */
CMessageModel.prototype.showInlinePictures = function (sAppPath)
{
	var self = this;
	
	if (this.foundedCids().length > 0)
	{
		$('[data-x-src-cid]', this.$text).each(function () {
			var
				sCid = $(this).attr('data-x-src-cid'),
				oAttachment = self.findAttachmentByCid(sCid)
			;

			if (oAttachment && oAttachment.viewLink().length > 0)
			{
				$(this).attr('src', sAppPath + oAttachment.viewLink());
			}
		});

		$('[data-x-style-cid]', this.$text).each(function () {
			var
				sStyle = '',
				sName = $(this).attr('data-x-style-cid-name'),
				sCid = $(this).attr('data-x-style-cid'),
				oAttachment = self.findAttachmentByCid(sCid)
			;

			if (oAttachment && oAttachment.viewLink().length > 0 && '' !== sName)
			{
				sStyle = Utils.trim($(this).attr('style'));
				sStyle = '' === sStyle ? '' : (';' === sStyle.substr(-1) ? sStyle + ' ' : sStyle + '; ');
				$(this).attr('style', sStyle + sName + ': url(\'' + oAttachment.viewLink() + '\')');
			}
		});
	}

	$('[data-x-src-location]', this.$text).each(function () {

		var
			sLocation = $(this).attr('data-x-src-location'),
			oAttachment = self.findAttachmentByContentLocation(sLocation)
		;

		if (!oAttachment)
		{
			oAttachment = self.findAttachmentByCid(sLocation);
		}

		if (oAttachment && oAttachment.viewLink().length > 0)
		{
			$(this).attr('src', sAppPath + oAttachment.viewLink());
		}
	});
};

/**
 * Display external images.
 */
CMessageModel.prototype.showExternalPictures = function ()
{
	$('[data-x-src]', this.$text).each(function () {
		$(this).attr('src', $(this).attr('data-x-src')).removeAttr('data-x-src');
	});

	$('[data-x-style-url]', this.$text).each(function () {
		var sStyle = Utils.trim($(this).attr('style'));
		sStyle = '' === sStyle ? '' : (';' === sStyle.substr(-1) ? sStyle + ' ' : sStyle + '; ');
		$(this).attr('style', sStyle + $(this).attr('data-x-style-url')).removeAttr('data-x-style-url');
	});
	
	this.isExternalsShown(true);
};

/**
 * Sets a flag that external images are always displayed.
 */
CMessageModel.prototype.alwaysShowExternalPicturesForSender = function ()
{
	if (this.completelyFilled())
	{
		this.isExternalsAlwaysShown(true);
		if (!this.isExternalsShown())
		{
			this.showExternalPictures();
		}
	}
};

CMessageModel.prototype.openThread = function ()
{
	if (this.threadCountVisible())
	{
		var sFolder = this.folder();

		this.threadOpened(!this.threadOpened());
		if (this.threadOpened())
		{
			App.MailCache.showOpenedThreads(sFolder);
		}
		else
		{
			App.MailCache.hideThreads(this);
			setTimeout(function () {
				App.MailCache.showOpenedThreads(sFolder);
			}, 500);
		}
	}
};

CMessageModel.prototype.downloadAllAttachments = function ()
{
	if (this.allAttachmentsHash !== '')
	{
		App.Api.downloadByUrl(Utils.getDownloadLinkByHash(this.accountId(), this.allAttachmentsHash));
	}
	else
	{
		var
			aNotInlineAttachments = _.filter(this.attachments(), function (oAttach) {
				return !oAttach.linked();
			}),
			aHashes = _.map(aNotInlineAttachments, function (oAttach) {
				return oAttach.hash();
			})
		;

		App.Ajax.send({
			'Action': 'MessageAttachmentsZip',
			'Hashes': aHashes
		}, this.onMessageZipAttachments, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMessageModel.prototype.onMessageZipAttachments = function (oResponse, oRequest)
{
	if (oResponse.Result)
	{
		this.allAttachmentsHash = oResponse.Result;
		App.Api.downloadByUrl(Utils.getDownloadLinkByHash(this.accountId(), this.allAttachmentsHash));
	}
};

CMessageModel.prototype.saveAttachmentsToFiles = function ()
{
	var
		aNotInlineAttachments = _.filter(this.attachments(), function (oAttach) {
			return !oAttach.linked();
		}),
		aHashes = _.map(aNotInlineAttachments, function (oAttach) {
			return oAttach.hash();
		})
	;

	App.filesRecievedAnim(true);
	App.Ajax.send({
		'Action': 'MessageAttachmentsSaveToFiles',
		'Attachments': aHashes
	}, this.onMessageAttachmentsSaveToFilesResponse, this);
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMessageModel.prototype.onMessageAttachmentsSaveToFilesResponse = function (oResponse, oRequest)
{
	var
		iSavedCount = 0,
		iTotalCount = oRequest.Attachments.length
	;
	
	if (oResponse.Result)
	{
		_.each(oRequest.Attachments, function (sHash) {
			if (oResponse.Result[sHash] !== undefined)
			{
				iSavedCount++;
			}
		});
	}
	
	if (iSavedCount === 0)
	{
		App.Api.showError(Utils.i18n('MESSAGE/ERROR_ATTACHMENTS_SAVED_TO_FILES'));
	}
	else if (iSavedCount < iTotalCount)
	{
		App.Api.showError(Utils.i18n('MESSAGE/WARNING_ATTACHMENTS_SAVED_TO_FILES', {
			'SAVED_COUNT': iSavedCount,
			'TOTAL_COUNT': iTotalCount
		}));
	}
	else
	{
		App.Api.showReport(Utils.i18n('MESSAGE/REPORT_ATTACHMENTS_SAVED_TO_FILES'));
	}
};

CMessageModel.prototype.downloadAllAttachmentsSeparately = function ()
{
	_.each(this.attachments(), function (oAttach) {
		if (!oAttach.linked())
		{
			oAttach.downloadFile(App);
		}
	});
};

/**
 * Uses for logging.
 * 
 * @returns {Object}
 */
CMessageModel.prototype.toJSON = function ()
{
	return {
		uid: this.uid(),
		accountId: this.accountId(),
		to: this.to(),
		subject: this.subject(),
		threadPart: this.threadPart(),
		threadUids: this.threadUids(),
		threadOpened: this.threadOpened()
	};
};


/**
 * @constructor
 * 
 * !!!Attention!!!
 * It is not used underscore, because the collection may contain undefined-elements.
 * They have their own importance. But all underscore-functions removes them automatically.
 */
function CUidListModel()
{
	this.resultCount = ko.observable(-1);
	
	this.search = ko.observable('');
	this.filters = ko.observable('');
	
	this.collection = ko.observableArray([]);
	
	this.threadUids = {};
}

/**
 * @param {string} sUid
 * @param {Array} aThreadUids
 */
CUidListModel.prototype.addThreadUids = function (sUid, aThreadUids)
{
	if (-1 !== _.indexOf(this.collection(), sUid))
	{
		this.threadUids[sUid] = aThreadUids;
	}
};

/**
 * @param {Object} oResult
 */
CUidListModel.prototype.setUidsAndCount = function (oResult)
{
	if (oResult['@Object'] === 'Collection/MessageCollection')
	{
		_.each(oResult.Uids, function (sUid, iIndex) {
			
			this.collection()[iIndex + oResult.Offset] = sUid.toString();

		}, this);

		this.resultCount(oResult.MessageResultCount);
	}
};

/**
 * @param {number} iOffset
 * @param {Object} oMessages
 */
CUidListModel.prototype.getUidsForOffset = function (iOffset, oMessages)
{
	var
		iIndex = 0,
		iLen = this.collection().length,
		sUid = '',
		iExistsCount = 0,
		aUids = [],
		oMsg = null
	;
	
	for(; iIndex < iLen; iIndex++)
	{
		if (iIndex >= iOffset && iExistsCount < AppData.User.MailsPerPage) {
			sUid = this.collection()[iIndex];
			oMsg = oMessages[sUid];

			if (oMsg && !oMsg.deleted() || sUid === undefined)
			{
				iExistsCount++;
				if (sUid !== undefined)
				{
					aUids.push(sUid);
				}
			}
		}
	}
	
	return aUids;
};

/**
 * @param {Array} aUids
 */
CUidListModel.prototype.deleteUids = function (aUids)
{
	var
		iIndex = 0,
		iLen = this.collection().length,
		sUid = '',
		aNewCollection = [],
		iDiff = 0
	;
	
	for (; iIndex < iLen; iIndex++)
	{
		sUid = this.collection()[iIndex];
		if (_.indexOf(aUids, sUid) === -1)
		{
			aNewCollection.push(sUid);
		}
		else
		{
			iDiff++;
		}
	}
	
	this.collection(aNewCollection);
	this.resultCount(this.resultCount() - iDiff);
};

/**
 * @constructor
 */
function CIcalModel()
{
	this.uid = ko.observable('');
	this.file = ko.observable('');
	this.attendee = ko.observable('');
	
	this.type = ko.observable('');
	this.icalType = ko.observable('');
	this.icalConfig = ko.observable('');
	this.type.subscribe(function () {
		var
			aTypeParts = this.type().split('-'),
			sType = aTypeParts.shift(),
			sFoundType = _.find(Enums.IcalType, function (sIcalType) {
				return sType === sIcalType;
			}, this),
			sConfig = aTypeParts.join('-'),
			sFoundConfig = _.find(Enums.IcalConfig, function (sIcalConfig) {
				return sConfig === sIcalConfig;
			}, this)
		;
		
		if (sType !== sFoundType)
		{
			sType = Enums.IcalType.Save;
		}
		this.icalType(sType);
		
		if (sConfig !== sFoundConfig)
		{
			sConfig = Enums.IcalConfig.NeedsAction;
		}
		this.icalConfig(sConfig);
	}, this);
	
	this.isRequestType = ko.computed(function () {
		return this.icalType() === Enums.IcalType.Request;
	}, this);
	this.isCancelType = ko.computed(function () {
		return this.icalType() === Enums.IcalType.Cancel;
	}, this);
	this.cancelDecision = ko.observable('');
	this.isReplyType = ko.computed(function () {
		return this.icalType() === Enums.IcalType.Reply;
	}, this);
	this.replyDecision = ko.observable('');
	this.isSaveType = ko.computed(function () {
		return this.icalType() === Enums.IcalType.Save;
	}, this);
	this.isJustSaved = ko.observable(false);
	
	this.fillDecisions();
	
	this.isAccepted = ko.computed(function () {
		return this.icalConfig() === Enums.IcalConfig.Accepted;
	}, this);
	this.isDeclined = ko.computed(function () {
		return this.icalConfig() === Enums.IcalConfig.Declined;
	}, this);
	this.isTentative = ko.computed(function () {
		return this.icalConfig() === Enums.IcalConfig.Tentative;
	}, this);
	
	this.location = ko.observable('');
	this.description = ko.observable('');
	this.when = ko.observable('');
	
	this.calendarId = ko.observable('');
	this.calendars = ko.observableArray([]);
	if (AppData.SingleMode && window.opener)
	{
		this.calendars(window.opener.App.CalendarCache.calendars());
		window.opener.App.CalendarCache.calendars.subscribe(function () {
			this.calendars(window.opener.App.CalendarCache.calendars());
		}, this);
	}
	else
	{
		this.calendars(App.CalendarCache.calendars());
		App.CalendarCache.calendars.subscribe(function () {
			this.calendars(App.CalendarCache.calendars());
		}, this);
	}

	this.selectedCalendarId = ko.observable('');

	this.chosenCalendarName = ko.computed(function () {
		var oFoundCal = null;

		if (this.calendarId() !== '') {
			oFoundCal = _.find(this.calendars(), function (oCal) {
				return oCal.id === this.calendarId();
			}, this);
		}
		
		return oFoundCal ? oFoundCal.name : '';
	}, this);
	
	this.calendarIsChosen = ko.computed(function () {
		return this.chosenCalendarName() !== '';
	}, this);
	
	this.visibleCalendarDropdown = ko.computed(function () {
		return !this.calendarIsChosen() && this.calendars().length > 1 && (this.isRequestType() || this.isSaveType());
	}, this);
	
	this.visibleCalendarName = ko.computed(function () {
		return this.calendarIsChosen();
	}, this);
	
	this.visibleFirstCalendarName = ko.computed(function () {
		return this.calendars().length === 1 && !this.calendarIsChosen();
	}, this);
	
	this.visibleCalendarRow = ko.computed(function () {
		return this.attendee() !== '' && (this.visibleCalendarDropdown() || this.visibleCalendarName() || this.visibleFirstCalendarName());
	}, this);
	
	this.visibleRequestButtons = ko.computed(function () {
		return this.isRequestType() && this.attendee() !== '';
	}, this);
	
	// animation of buttons turns on with delay
	// so it does not trigger when placing initial values
	this.animation = ko.observable(false);
}

CIcalModel.prototype.fillDecisions = function ()
{
	var
		oAccount = AppData.Accounts.getCurrent(),
		sSender = oAccount ? oAccount.email() : ''
	;
	
	this.cancelDecision(Utils.i18n('MESSAGE/APPOINTMENT_CANCELED', {'SENDER': sSender}));
	
	switch (this.icalConfig())
	{
		case Enums.IcalConfig.Accepted:
			this.replyDecision(Utils.i18n('MESSAGE/APPOINTMENT_ACCEPTED', {'SENDER': sSender}));
			break;
		case Enums.IcalConfig.Declined:
			this.replyDecision(Utils.i18n('MESSAGE/APPOINTMENT_DECLINED', {'SENDER': sSender}));
			break;
		case Enums.IcalConfig.Tentative:
			this.replyDecision(Utils.i18n('MESSAGE/APPOINTMENT_TENTATIVELY_ACCEPTED', {'SENDER': sSender}));
			break;
	}
};

/**
 * @param {AjaxIcsResponse} oData
 * @param {string} sAttendee
 */
CIcalModel.prototype.parse = function (oData, sAttendee)
{
	var sDescription = '';
	
	if (oData && oData['@Object'] === 'Object/CApiMailIcs')
	{
		sDescription = Utils.pString(oData.Description);
		this.uid(Utils.pString(oData.Uid));
		this.file(Utils.pString(oData.File));
		this.attendee(Utils.pString(oData.Attendee) || sAttendee);
		this.type(oData.Type);
		this.location(Utils.pString(oData.Location));
		this.description(sDescription.replace(/\r/g, '').replace(/\n/g,"<br />"));
		this.when(Utils.pString(oData.When));
		this.calendarId(Utils.pString(oData.CalendarId));
		this.selectedCalendarId(Utils.pString(oData.CalendarId));
		
		App.CalendarCache.addIcal(this);
	}
};

CIcalModel.prototype.acceptAppointment = function ()
{
	this.calendarId(this.selectedCalendarId());
	this.changeAndSaveConfig(Enums.IcalConfig.Accepted);
};

CIcalModel.prototype.tentativeAppointment = function ()
{
	this.calendarId(this.selectedCalendarId());
	this.changeAndSaveConfig(Enums.IcalConfig.Tentative);
};

CIcalModel.prototype.declineAppointment = function ()
{
	this.calendarId('');
	this.selectedCalendarId('');
	this.changeAndSaveConfig(Enums.IcalConfig.Declined);
};

/**
 * @param {string} sConfig
 */
CIcalModel.prototype.changeAndSaveConfig = function (sConfig)
{
	if (this.icalConfig() !== sConfig)
	{
		if (this.icalConfig() !== sConfig &&
			(sConfig !== Enums.IcalConfig.Declined || this.icalConfig() !== Enums.IcalConfig.NeedsAction)) 
		{
			App.CalendarCache.recivedAnim(true);
		}

		this.changeConfig(sConfig);
		this.setAppointmentAction();
	}
};

/**
 * @param {string} sConfig
 */
CIcalModel.prototype.changeConfig = function (sConfig)
{
	this.type(this.icalType() + '-' + sConfig);
	if (AppData.SingleMode && window.opener)
	{
		window.opener.App.CalendarCache.markIcalTypeByFile(this.file(), this.type(), this.cancelDecision(),
									this.replyDecision(), this.calendarId(), this.selectedCalendarId());
	}
	else
	{
		App.CalendarCache.markIcalTypeByFile(this.file(), this.type(), this.cancelDecision(),
									this.replyDecision(), this.calendarId(), this.selectedCalendarId());
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CIcalModel.prototype.onCalendarAppointmentSetActionResponse = function (oResponse, oRequest)
{
	if (!oResponse.Result)
	{
		App.Api.showErrorByCode(oResponse, Utils.i18n('WARNING/UNKNOWN_ERROR'));
	}
	else if (App.CalendarCache)
	{
		App.CalendarCache.calendarChanged(true);
	}
};

CIcalModel.prototype.setAppointmentAction = function ()
{
	var
		oParameters = {
			'Action': 'CalendarAppointmentSetAction',
			'AppointmentAction': this.icalConfig(),
			'CalendarId': this.selectedCalendarId(),
			'File': this.file(),
			'Attendee': this.attendee()
		}
	;

	App.Ajax.send(oParameters, this.onCalendarAppointmentSetActionResponse, this);
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CIcalModel.prototype.onCalendarSaveIcsResponse = function (oResponse, oRequest)
{
	if (!oResponse.Result)
	{
		App.Api.showErrorByCode(oResponse);
	}
	else
	{
		if (oResponse.Result.Uid)
		{
			this.uid(oResponse.Result.Uid);
		}
		if (App.CalendarCache)
		{
			App.CalendarCache.calendarChanged(true);
		}
	}
};

CIcalModel.prototype.addEvent = function ()
{
	var
		oParameters = {
			'Action': 'CalendarSaveIcs',
			'CalendarId': this.selectedCalendarId(),
			'File': this.file()
		}
	;
	
	App.Ajax.send(oParameters, this.onCalendarSaveIcsResponse, this);
	
	this.isJustSaved(true);
	this.calendarId(this.selectedCalendarId());
	
	setTimeout(_.bind(function () {
		this.isJustSaved(false);
	}, this), 20000);
	
	App.CalendarCache.recivedAnim(true);
};

CIcalModel.prototype.onEventDelete = function ()
{
	this.calendarId('');
	this.selectedCalendarId('');
	this.changeConfig(Enums.IcalConfig.NeedsAction);
};

CIcalModel.prototype.onEventTentative = function ()
{
	this.changeConfig(Enums.IcalConfig.Tentative);
};

CIcalModel.prototype.onEventAccept = function ()
{
	this.changeConfig(Enums.IcalConfig.Accepted);
};

CIcalModel.prototype.firstCalendarName = function ()
{
	return this.calendars()[0] ? this.calendars()[0].name : '';
};

/**
 * @param {string} sEmail
 */
CIcalModel.prototype.updateAttendeeStatus = function (sEmail)
{
	if (this.icalType() === Enums.IcalType.Cancel || this.icalType() === Enums.IcalType.Reply)
	{
		var
			oParameters = {
				'Action': 'CalendarAttendeeUpdateStatus',
				'File': this.file(),
				'FromEmail': sEmail
			}
		;

		App.Ajax.send(oParameters, this.onCalendarAttendeeUpdateStatusResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CIcalModel.prototype.onCalendarAttendeeUpdateStatusResponse = function (oResponse, oRequest)
{
	if (oResponse.Result && App.CalendarCache)
	{
		App.CalendarCache.recivedAnim(true);
		App.CalendarCache.calendarChanged(true);
	}
};


/**
 * @constructor
 */
function CVcardModel()
{
	this.uid = ko.observable('');
	this.file = ko.observable('');
	this.name = ko.observable('');
	this.email = ko.observable('');
	this.isExists = ko.observable(false);
	this.isJustSaved = ko.observable(false);
}

/**
 * @param {AjaxVCardResponse} oData
 */
CVcardModel.prototype.parse = function (oData)
{
	if (oData && oData['@Object'] === 'Object/CApiMailVcard')
	{
		this.uid(Utils.pString(oData.Uid));
		this.file(Utils.pString(oData.File));
		this.name(Utils.pString(oData.Name));
		this.email(Utils.pString(oData.Email));
		this.isExists(!!oData.Exists);
		
		App.ContactsCache.addVcard(this);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CVcardModel.prototype.onContactsSaveVcfResponse = function (oData, oParameters)
{
	if (oData && oData.Result && oData.Result.Uid)
	{
		this.uid(oData.Result.Uid);
	}
};

CVcardModel.prototype.addContact = function ()
{
	var
		oParameters = {
			'Action': 'ContactsSaveVcf',
			'File': this.file()
		}
	;
	
	App.Ajax.send(oParameters, this.onContactsSaveVcfResponse, this);
	
	this.isJustSaved(true);
	this.isExists(true);
	
	setTimeout(_.bind(function () {
		this.isJustSaved(false);
	}, this), 20000);
	
	App.ContactsCache.recivedAnim(true);
	
	if (AppData.SingleMode && window.opener)
	{
		window.opener.App.ContactsCache.markVcardExistentByFile(this.file());
	}
	else
	{
		App.ContactsCache.markVcardExistentByFile(this.file());
	}
};


/**
 * @constructor
 */
function CContactModel()
{
	this.sEmailDefaultType = Enums.ContactEmailType.Personal;
	this.sPhoneDefaultType = Enums.ContactPhoneType.Mobile;
	this.sAddressDefaultType = Enums.ContactAddressType.Personal;
	
	this.voiceApp = null;
	if (App.Phone)
	{
		this.voiceApp = App.Phone.voiceApp;
	}

	this.idContact = ko.observable('');
	this.idUser = ko.observable('');
	this.global = ko.observable(false);
	this.itsMe = ko.observable(false);

	this.isNew = ko.observable(false);
	this.readOnly = ko.observable(false);
	this.edited = ko.observable(false);
	this.extented = ko.observable(false);
	this.personalCollapsed = ko.observable(false);
	this.businessCollapsed = ko.observable(false);
	this.otherCollapsed = ko.observable(false);
	this.groupsCollapsed = ko.observable(false);

	this.displayName = ko.observable('');
	this.firstName = ko.observable('');
	this.lastName = ko.observable('');
	this.nickName = ko.observable('');

	this.skype = ko.observable('');
	this.facebook = ko.observable('');

	this.displayNameFocused = ko.observable(false);

	this.primaryEmail = ko.observable(this.sEmailDefaultType);
	this.primaryPhone = ko.observable(this.sPhoneDefaultType);
	this.primaryAddress = ko.observable(this.sAddressDefaultType);

	this.mainPrimaryEmail = ko.computed({
		'read': this.primaryEmail,
		'write': function (mValue) {
			if (!Utils.isUnd(mValue) && 0 <= Utils.inArray(mValue, [Enums.ContactEmailType.Personal, Enums.ContactEmailType.Business, Enums.ContactEmailType.Other]))
			{
				this.primaryEmail(mValue);
			}
			else
			{
				this.primaryEmail(Enums.ContactEmailType.Personal);
			}
		},
		'owner': this
	});

	this.mainPrimaryPhone = ko.computed({
		'read': this.primaryPhone,
		'write': function (mValue) {
			if (!Utils.isUnd(mValue) && 0 <= Utils.inArray(mValue, [Enums.ContactPhoneType.Mobile, Enums.ContactPhoneType.Personal, Enums.ContactPhoneType.Business]))
			{
				this.primaryPhone(mValue);
			}
			else
			{
				this.primaryPhone(Enums.ContactPhoneType.Mobile);
			}
		},
		'owner': this
	});
	
	this.mainPrimaryAddress = ko.computed({
		'read': this.primaryAddress,
		'write': function (mValue) {
			if (!Utils.isUnd(mValue) && 0 <= Utils.inArray(mValue, [Enums.ContactAddressType.Personal, Enums.ContactAddressType.Business]))
			{
				this.primaryAddress(mValue);
			}
			else
			{
				this.primaryAddress(Enums.ContactAddressType.Personal);
			}
		},
		'owner': this
	});

	this.personalEmail = ko.observable('');
	this.personalStreetAddress = ko.observable('');
	this.personalCity = ko.observable('');
	this.personalState = ko.observable('');
	this.personalZipCode = ko.observable('');
	this.personalCountry = ko.observable('');
	this.personalWeb = ko.observable('');
	this.personalFax = ko.observable('');
	this.personalPhone = ko.observable('');
	this.personalMobile = ko.observable('');

	this.businessEmail = ko.observable('');
	this.businessCompany = ko.observable('');
	this.businessDepartment = ko.observable('');
	this.businessJob = ko.observable('');
	this.businessOffice = ko.observable('');
	this.businessStreetAddress = ko.observable('');
	this.businessCity = ko.observable('');
	this.businessState = ko.observable('');
	this.businessZipCode = ko.observable('');
	this.businessCountry = ko.observable('');
	this.businessWeb = ko.observable('');
	this.businessFax = ko.observable('');
	this.businessPhone = ko.observable('');

	this.otherEmail = ko.observable('');
	this.otherBirthdayMonth = ko.observable('0');
	this.otherBirthdayDay = ko.observable('0');
	this.otherBirthdayYear = ko.observable('0');
	this.otherNotes = ko.observable('');
	this.etag = ko.observable('');
	
	this.sharedToAll = ko.observable(false);

	this.birthdayIsEmpty = ko.computed(function () {
		var
			bMonthEmpty = '0' === this.otherBirthdayMonth(),
			bDayEmpty = '0' === this.otherBirthdayDay(),
			bYearEmpty = '0' === this.otherBirthdayYear()
		;

		return (bMonthEmpty || bDayEmpty || bYearEmpty);
	}, this);
	
	this.otherBirthday = ko.computed(function () {
		var
			sBirthday = '',
			iYear = Utils.pInt(this.otherBirthdayYear()),
			iMonth = Utils.pInt(this.otherBirthdayMonth()),
			iDay = Utils.pInt(this.otherBirthdayDay()),
			oDateModel = new CDateModel()
		;
		
		if (!this.birthdayIsEmpty())
		{
			var fullYears = moment().diff(moment(iYear + '/' + iMonth + '/' + iDay, "YYYY/MM/DD"), 'years'),
				text = Utils.i18n('CONTACTS/YEARS_TEXT_PLURAL', {
					'COUNT': fullYears
				}, null, fullYears)
			;
			oDateModel.setDate(iYear, 0 < iMonth ? iMonth - 1 : 0, iDay);
			sBirthday = oDateModel.getShortDate() + ' (' + text + ')';
		}
		
		return sBirthday;
	}, this);

	this.groups = ko.observableArray([]);

	this.groupsIsEmpty = ko.computed(function () {
		return 0 === this.groups().length;
	}, this);

	this.email = ko.computed({
		'read': function () {
			var sResult = '';
			switch (this.primaryEmail()) {
				case Enums.ContactEmailType.Personal:
					sResult = this.personalEmail();
					break;
				case Enums.ContactEmailType.Business:
					sResult = this.businessEmail();
					break;
				case Enums.ContactEmailType.Other:
					sResult = this.otherEmail();
					break;
			}
			return sResult;
		},
		'write': function (sEmail) {
			switch (this.primaryEmail()) {
				case Enums.ContactEmailType.Personal:
					this.personalEmail(sEmail);
					break;
				case Enums.ContactEmailType.Business:
					this.businessEmail(sEmail);
					break;
				case Enums.ContactEmailType.Other:
					this.otherEmail(sEmail);
					break;
				default:
					this.primaryEmail(this.sEmailDefaultType);
					this.email(sEmail);
					break;
			}
		},
		'owner': this
	});

	this.personalIsEmpty = ko.computed(function () {
		var sPersonalEmail = (this.personalEmail() !== this.email()) ? this.personalEmail() : '';
		return '' === '' + sPersonalEmail +
			this.personalStreetAddress() +
			this.personalCity() +
			this.personalState() +
			this.personalZipCode() +
			this.personalCountry() +
			this.personalWeb() +
			this.personalFax() +
			this.personalPhone() +
			this.personalMobile()
		;
	}, this);

	this.businessIsEmpty = ko.computed(function () {
		var sBusinessEmail = (this.businessEmail() !== this.email()) ? this.businessEmail() : '';
		return '' === '' + sBusinessEmail +
			this.businessCompany() +
			this.businessDepartment() +
			this.businessJob() +
			this.businessOffice() +
			this.businessStreetAddress() +
			this.businessCity() +
			this.businessState() +
			this.businessZipCode() +
			this.businessCountry() +
			this.businessWeb() +
			this.businessFax() +
			this.businessPhone()
		;
	}, this);

	this.otherIsEmpty = ko.computed(function () {
		var sOtherEmail = (this.otherEmail() !== this.email()) ? this.otherEmail() : '';
		return ('' === ('' + sOtherEmail + this.otherNotes())) && this.birthdayIsEmpty();
	}, this);
	
	this.phone = ko.computed({
		'read': function () {
			var sResult = '';
			switch (this.primaryPhone()) {
				case Enums.ContactPhoneType.Mobile:
					sResult = this.personalMobile();
					break;
				case Enums.ContactPhoneType.Personal:
					sResult = this.personalPhone();
					break;
				case Enums.ContactPhoneType.Business:
					sResult = this.businessPhone();
					break;
			}
			return sResult;
		},
		'write': function (sPhone) {
			switch (this.primaryPhone()) {
				case Enums.ContactPhoneType.Mobile:
					this.personalMobile(sPhone);
					break;
				case Enums.ContactPhoneType.Personal:
					this.personalPhone(sPhone);
					break;
				case Enums.ContactPhoneType.Business:
					this.businessPhone(sPhone);
					break;
				default:
					this.primaryPhone(this.sEmailDefaultType);
					this.phone(sPhone);
					break;
			}
		},
		'owner': this
	});
	
	this.address = ko.computed({
		'read': function () {
			var sResult = '';
			switch (this.primaryAddress()) {
				case Enums.ContactAddressType.Personal:
					sResult = this.personalStreetAddress();
					break;
				case Enums.ContactAddressType.Business:
					sResult = this.businessStreetAddress();
					break;
			}
			return sResult;
		},
		'write': function (sAddress) {
			switch (this.primaryAddress()) {
				case Enums.ContactAddressType.Personal:
					this.personalStreetAddress(sAddress);
					break;
				case Enums.ContactAddressType.Business:
					this.businessStreetAddress(sAddress);
					break;
				default:
					this.primaryAddress(this.sEmailDefaultType);
					this.address(sAddress);
					break;
			}
		},
		'owner': this
	});

	this.emails = ko.computed(function () {
		var aList = [];
		
		if ('' !== this.personalEmail())
		{
			aList.push({'text': Utils.i18n('CONTACTS/OPTION_PERSONAL') + ': ' + this.personalEmail(), 'value': Enums.ContactEmailType.Personal});
		}
		if ('' !== this.businessEmail())
		{
			aList.push({'text': Utils.i18n('CONTACTS/OPTION_BUSINESS') + ': ' + this.businessEmail(), 'value': Enums.ContactEmailType.Business});
		}
		if ('' !== this.otherEmail())
		{
			aList.push({'text': Utils.i18n('CONTACTS/OPTION_OTHER') + ': ' + this.otherEmail(), 'value': Enums.ContactEmailType.Other});
		}

		return aList;

	}, this);

	this.phones = ko.computed(function () {
		var aList = [];

		if ('' !== this.personalMobile())
		{
			aList.push({'text': Utils.i18n('CONTACTS/LABEL_MOBILE') + ': ' + this.personalMobile(), 'value': Enums.ContactPhoneType.Mobile});
		}
		if ('' !== this.personalPhone())
		{
			aList.push({'text': Utils.i18n('CONTACTS/OPTION_PERSONAL') + ': ' + this.personalPhone(), 'value': Enums.ContactPhoneType.Personal});
		}
		if ('' !== this.businessPhone())
		{
			aList.push({'text': Utils.i18n('CONTACTS/OPTION_BUSINESS') + ': ' + this.businessPhone(), 'value': Enums.ContactPhoneType.Business});
		}
		return aList;

	}, this);
	
	this.addresses = ko.computed(function () {
		var aList = [];

		if ('' !== this.personalStreetAddress())
		{
			aList.push({'text': Utils.i18n('CONTACTS/OPTION_PERSONAL') + ': ' + this.personalStreetAddress(), 'value': Enums.ContactAddressType.Personal});
		}
		if ('' !== this.businessStreetAddress())
		{
			aList.push({'text': Utils.i18n('CONTACTS/OPTION_BUSINESS') + ': ' + this.businessStreetAddress(), 'value': Enums.ContactAddressType.Business});
		}
		return aList;

	}, this);

	this.hasEmails = ko.computed(function () {
		return 0 < this.emails().length;
	}, this);

	this.extented.subscribe(function (bValue) {
		if (bValue)
		{
			this.personalCollapsed(!this.personalIsEmpty());
			this.businessCollapsed(!this.businessIsEmpty());
			this.otherCollapsed(!this.otherIsEmpty());
			this.groupsCollapsed(!this.groupsIsEmpty());
		}
	}, this);

	this.birthdayMonthSelect = CContactModel.birthdayMonthSelect;
	this.birthdayYearSelect = CContactModel.birthdayYearSelect;

	this.birthdayDaySelect = ko.computed(function () {

		var
			iIndex = 1,
			iLen = Utils.pInt(Utils.daysInMonth(this.otherBirthdayMonth(), this.otherBirthdayYear())),
			sIndex = '',
			aList = [{'text': Utils.i18n('DATETIME/DAY'), 'value': '0'}]
		;

		for (; iIndex <= iLen; iIndex++)
		{
			sIndex = iIndex.toString();
			aList.push({'text': sIndex, 'value': sIndex});
		}

		return aList;

	}, this);


	for (var oDate = new Date(), sIndex = '', iIndex = oDate.getFullYear(), iLen = 2012 - 80; iIndex >= iLen; iIndex--)
	{
		sIndex = iIndex.toString();
		this.birthdayYearSelect.push(
			{'text': sIndex, 'value': sIndex}
		);
	}

	this.canBeSave = ko.computed(function () {
		return this.displayName() !== '' || !!this.emails().length;
	}, this);
	
	this.sendMailLink = ko.computed(function () {
		return bMobileApp ? this.getSendMailLink(this.email()) : '#';
	}, this);

	this.sendMailToPersonalLink = ko.computed(function () {
		return bMobileApp ? this.getSendMailLink(this.personalEmail()) : '#';
	}, this);
	
	this.sendMailToBusinessLink = ko.computed(function () {
		return bMobileApp ? this.getSendMailLink(this.businessEmail()) : '#';
	}, this);
	
	this.sendMailToOtherLink = ko.computed(function () {
		return bMobileApp ? this.getSendMailLink(this.otherEmail()) : '#';
	}, this);
}

CContactModel.birthdayMonths = Utils.getMonthNamesArray();

CContactModel.birthdayMonthSelect = [
	{'text': Utils.i18n('DATETIME/MONTH'), value: '0'},
	{'text': CContactModel.birthdayMonths[0], value: '1'},
	{'text': CContactModel.birthdayMonths[1], value: '2'},
	{'text': CContactModel.birthdayMonths[2], value: '3'},
	{'text': CContactModel.birthdayMonths[3], value: '4'},
	{'text': CContactModel.birthdayMonths[4], value: '5'},
	{'text': CContactModel.birthdayMonths[5], value: '6'},
	{'text': CContactModel.birthdayMonths[6], value: '7'},
	{'text': CContactModel.birthdayMonths[7], value: '8'},
	{'text': CContactModel.birthdayMonths[8], value: '9'},
	{'text': CContactModel.birthdayMonths[9], value: '10'},
	{'text': CContactModel.birthdayMonths[10], value: '11'},
	{'text': CContactModel.birthdayMonths[11], value: '12'}
];

CContactModel.birthdayYearSelect = [
	{'text': Utils.i18n('DATETIME/YEAR'), 'value': '0'}
];

/**
 * @param {string} sEmail
 * @return {Array}
 */
CContactModel.prototype.getSendMailParts = function (sEmail)
{
	return App.Links.composeWithToField(this.getFullEmail(sEmail));
};

/**
 * @param {string} sEmail
 * @return {string}
 */
CContactModel.prototype.getSendMailLink = function (sEmail)
{
	return App.Routing.buildHashFromArray(this.getSendMailParts(sEmail));
};

CContactModel.prototype.sendMail = function ()
{
	App.Api.composeMessageToAddresses(this.email());
	return bMobileApp;
};

CContactModel.prototype.sendMailToPersonal = function ()
{
	App.Api.composeMessageToAddresses(this.personalEmail());
	return bMobileApp;
};

CContactModel.prototype.sendMailToBusiness = function ()
{
	App.Api.composeMessageToAddresses(this.businessEmail());
	return bMobileApp;
};

CContactModel.prototype.sendMailToOther = function ()
{
	App.Api.composeMessageToAddresses(this.otherEmail());
	return bMobileApp;
};

CContactModel.prototype.clear = function ()
{
	this.isNew(false);
	this.readOnly(false);

	this.idContact('');
	this.idUser('');
	this.global(false);
	this.itsMe(false);

	this.edited(false);
	this.extented(false);
	this.personalCollapsed(false);
	this.businessCollapsed(false);
	this.otherCollapsed(false);
	this.groupsCollapsed(false);

	this.displayName('');
	this.firstName('');
	this.lastName('');
	this.nickName('');

	this.skype('');
	this.facebook('');

	this.primaryEmail(this.sEmailDefaultType);
	this.primaryPhone(this.sPhoneDefaultType);
	this.primaryAddress(this.sAddressDefaultType);

	this.personalEmail('');
	this.personalStreetAddress('');
	this.personalCity('');
	this.personalState('');
	this.personalZipCode('');
	this.personalCountry('');
	this.personalWeb('');
	this.personalFax('');
	this.personalPhone('');
	this.personalMobile('');

	this.businessEmail('');
	this.businessCompany('');
	this.businessDepartment('');
	this.businessJob('');
	this.businessOffice('');
	this.businessStreetAddress('');
	this.businessCity('');
	this.businessState('');
	this.businessZipCode('');
	this.businessCountry('');
	this.businessWeb('');
	this.businessFax('');
	this.businessPhone('');

	this.otherEmail('');
	this.otherBirthdayMonth('0');
	this.otherBirthdayDay('0');
	this.otherBirthdayYear('0');
	this.otherNotes('');

	this.etag('');
	this.sharedToAll(false);

	this.groups([]);
};

CContactModel.prototype.switchToNew = function ()
{
	this.clear();
	this.edited(true);
	this.extented(false);
	this.isNew(true);
	if (!bMobileApp)
	{
		this.displayNameFocused(true);
	}
};

CContactModel.prototype.switchToView = function ()
{
	this.edited(false);
	this.extented(false);
};

/**
 * @return {Object}
 */
CContactModel.prototype.toObject = function ()
{
	var oResult = {
		'ContactId': this.idContact(),
		'PrimaryEmail': this.primaryEmail(),
		'PrimaryPhone': this.primaryPhone(),
		'PrimaryAddress': this.primaryAddress(),
		'UseFriendlyName': '1',
		'Title': '',
		'FullName': this.displayName(),
		'FirstName': this.firstName(),
		'LastName': this.lastName(),
		'NickName': this.nickName(),

		'Global': this.global() ? '1' : '0',
		'ItsMe': this.itsMe() ? '1' : '0',

		'Skype': this.skype(),
		'Facebook': this.facebook(),

		'HomeEmail': this.personalEmail(),
		'HomeStreet': this.personalStreetAddress(),
		'HomeCity': this.personalCity(),
		'HomeState': this.personalState(),
		'HomeZip': this.personalZipCode(),
		'HomeCountry': this.personalCountry(),
		'HomeFax': this.personalFax(),
		'HomePhone': this.personalPhone(),
		'HomeMobile': this.personalMobile(),
		'HomeWeb': this.personalWeb(),

		'BusinessEmail': this.businessEmail(),
		'BusinessCompany': this.businessCompany(),
		'BusinessJobTitle': this.businessJob(),
		'BusinessDepartment': this.businessDepartment(),
		'BusinessOffice': this.businessOffice(),
		'BusinessStreet': this.businessStreetAddress(),
		'BusinessCity': this.businessCity(),
		'BusinessState': this.businessState(),
		'BusinessZip': this.businessZipCode(),
		'BusinessCountry': this.businessCountry(),
		'BusinessFax': this.businessFax(),
		'BusinessPhone': this.businessPhone(),
		'BusinessWeb': this.businessWeb(),

		'OtherEmail': this.otherEmail(),
		'Notes': this.otherNotes(),
		'ETag': this.etag(),
		'BirthdayDay': this.otherBirthdayDay(),
		'BirthdayMonth': this.otherBirthdayMonth(),
		'BirthdayYear': this.otherBirthdayYear(),

		'SharedToAll': this.sharedToAll() ? '1' : '0',
		
		'GroupsIds': this.groups()
	};

	return oResult;
};

/**
 * @param {Object} oData
 */
CContactModel.prototype.parse = function (oData)
{
	if (oData && 'Object/CContact' === oData['@Object'])
	{
		var
			iPrimaryEmail = 0,
			iPrimaryPhone = 0,
			iPrimaryAddress = 0,
			aGroupsIds = []
		;

		this.idContact(Utils.pExport(oData, 'IdContact', '').toString());
		this.idUser(Utils.pExport(oData, 'IdUser', '').toString());

		this.global(!!Utils.pExport(oData, 'Global', false));
		this.itsMe(!!Utils.pExport(oData, 'ItsMe', false));
		this.readOnly(!!Utils.pExport(oData, 'ReadOnly', false));

		this.displayName(Utils.pExport(oData, 'FullName', ''));
		this.firstName(Utils.pExport(oData, 'FirstName', ''));
		this.lastName(Utils.pExport(oData, 'LastName', ''));
		this.nickName(Utils.pExport(oData, 'NickName', ''));

		this.skype(Utils.pExport(oData, 'Skype', ''));
		this.facebook(Utils.pExport(oData, 'Facebook', ''));

		iPrimaryEmail = Utils.pInt(Utils.pExport(oData, 'PrimaryEmail', 0));
		switch (iPrimaryEmail)
		{
			case 1:
				iPrimaryEmail = Enums.ContactEmailType.Business;
				break;
			case 2:
				iPrimaryEmail = Enums.ContactEmailType.Other;
				break;
			default:
			case 0:
				iPrimaryEmail = Enums.ContactEmailType.Personal;
				break;
		}
		this.primaryEmail(iPrimaryEmail);

		iPrimaryPhone = Utils.pInt(Utils.pExport(oData, 'PrimaryPhone', 0));
		switch (iPrimaryPhone)
		{
			case 2:
				iPrimaryPhone = Enums.ContactPhoneType.Business;
				break;
			case 1:
				iPrimaryPhone = Enums.ContactPhoneType.Personal;
				break;
			default:
			case 0:
				iPrimaryPhone = Enums.ContactPhoneType.Mobile;
				break;
		}
		this.primaryPhone(iPrimaryPhone);
		
		iPrimaryAddress = Utils.pInt(Utils.pExport(oData, 'PrimaryAddress', 0));
		switch (iPrimaryAddress)
		{
			case 1:
				iPrimaryAddress = Enums.ContactAddressType.Business;
				break;
			default:
			case 0:
				iPrimaryAddress = Enums.ContactAddressType.Personal;
				break;
		}
		this.primaryAddress(iPrimaryAddress);

		this.personalEmail(Utils.pExport(oData, 'HomeEmail', ''));
		this.personalStreetAddress(Utils.pExport(oData, 'HomeStreet', ''));
		this.personalCity(Utils.pExport(oData, 'HomeCity', ''));
		this.personalState(Utils.pExport(oData, 'HomeState', ''));
		this.personalZipCode(Utils.pExport(oData, 'HomeZip', ''));
		this.personalCountry(Utils.pExport(oData, 'HomeCountry', ''));
		this.personalWeb(Utils.pExport(oData, 'HomeWeb', ''));
		this.personalFax(Utils.pExport(oData, 'HomeFax', ''));
		this.personalPhone(Utils.pExport(oData, 'HomePhone', ''));
		this.personalMobile(Utils.pExport(oData, 'HomeMobile', ''));

		this.businessEmail(Utils.pExport(oData, 'BusinessEmail', ''));
		this.businessCompany(Utils.pExport(oData, 'BusinessCompany', ''));
		this.businessDepartment(Utils.pExport(oData, 'BusinessDepartment', ''));
		this.businessJob(Utils.pExport(oData, 'BusinessJobTitle', ''));
		this.businessOffice(Utils.pExport(oData, 'BusinessOffice', ''));
		this.businessStreetAddress(Utils.pExport(oData, 'BusinessStreet', ''));
		this.businessCity(Utils.pExport(oData, 'BusinessCity', ''));
		this.businessState(Utils.pExport(oData, 'BusinessState', ''));
		this.businessZipCode(Utils.pExport(oData, 'BusinessZip', ''));
		this.businessCountry(Utils.pExport(oData, 'BusinessCountry', ''));
		this.businessWeb(Utils.pExport(oData, 'BusinessWeb', ''));
		this.businessFax(Utils.pExport(oData, 'BusinessFax', ''));
		this.businessPhone(Utils.pExport(oData, 'BusinessPhone', ''));

		this.otherEmail(Utils.pExport(oData, 'OtherEmail', ''));
		this.otherBirthdayMonth(Utils.pExport(oData, 'BirthdayMonth', '0').toString());
		this.otherBirthdayDay(Utils.pExport(oData, 'BirthdayDay', '0').toString());
		this.otherBirthdayYear(Utils.pExport(oData, 'BirthdayYear', '0').toString());
		this.otherNotes(Utils.pExport(oData, 'Notes', ''));

		this.etag(Utils.pExport(oData, 'ETag', ''));

		this.sharedToAll(!!Utils.pExport(oData, 'SharedToAll', false));

		aGroupsIds = Utils.pExport(oData, 'GroupsIds', []);
		if (_.isArray(aGroupsIds))
		{
			this.groups(
				_.map(aGroupsIds, function (sItem) {
					return Utils.pInt(sItem);
				})
			);
		}
	}
};

/**
 * @param {string} sEmail
 * @return {string}
 */
CContactModel.prototype.getFullEmail = function (sEmail)
{
	var sFullEmail = sEmail;
	
	if (this.displayName() !== '')
	{
		if (sEmail !== '')
		{
			sFullEmail = '"' + this.displayName() + '" <' + sEmail + '>';
		}
		else
		{
			sFullEmail = this.displayName();
		}
	}
	
	return sFullEmail;
};
CContactModel.prototype.getEmailsString = function ()
{
	return _.uniq(_.without([this.email(), this.personalEmail(), this.businessEmail(), this.otherEmail()], '')).join(',');
};
CContactModel.prototype.viewAllMails = function ()
{
	App.MailCache.searchMessagesInInbox('email:' + this.getEmailsString());
};

CContactModel.prototype.sendThisContact = function ()
{
	App.Api.composeMessageWithVcard(this);
};

/**
 * @param {?} mLink
 * @return {boolean}
 */
CContactModel.prototype.isStrLink = function (mLink)
{
	return (/^http/).test(mLink);
};

/**
 * @param {string} sPhone
 */
CContactModel.prototype.onCallClick = function (sPhone)
{
	App.Phone.call(sPhone);
};

CContactModel.prototype.viewAllMailsWithContact = function ()
{
	var sSearch = this.getEmailsString();
	
	if (AppData.SingleMode && window.opener && window.opener.App)
	{
		window.opener.App.MailCache.searchMessagesInCurrentFolder('email:' + sSearch);
		window.opener.focus();
		window.close();
	}
	else
	{
		App.MailCache.searchMessagesInCurrentFolder('email:' + sSearch);
	}
};


/**
 * @constructor
 */
function CGroupModel()
{
	this.isNew = ko.observable(false);
	this.readOnly = ko.observable(false);

	this.idGroup = ko.observable('');
	this.idUser = ko.observable('');

	this.name = ko.observable('');

	this.isOrganization = ko.observable(false);
	this.email = ko.observable('');
	this.country = ko.observable('');
	this.city = ko.observable('');
	this.company = ko.observable('');
	this.fax = ko.observable('');
	this.phone = ko.observable('');
	this.state = ko.observable('');
	this.street = ko.observable('');
	this.web = ko.observable('');
	this.zip = ko.observable('');
	
	this.edited = ko.observable(false);

	this.nameFocused = ko.observable(false);

	this.canBeSave = ko.computed(function () {
		return '' !== this.name();
	}, this);

	this.newContactsInGroupCount = ko.observable(0);

	this.newContactsInGroupHint = ko.computed(function () {
		var iCount = this.newContactsInGroupCount();
		return this.isNew() && 0 < iCount ? Utils.i18n('CONTACTS/CONTACT_ADD_TO_NEW_HINT_PLURAL', {
			'COUNT' : iCount
		}, null, iCount) : '';
	}, this);
	
	this.events = ko.observableArray([]);

}

CGroupModel.prototype.clear = function ()
{
	this.isNew(false);

	this.idGroup('');
	this.idUser('');

	this.name('');
	this.nameFocused(false);
	this.edited(false);
	
	this.isOrganization(false);
	this.email('');
	this.country('');
	this.city('');
	this.company('');
	this.fax('');
	this.phone('');
	this.state('');
	this.street('');
	this.web('');
	this.zip('');	
	this.events([]);
};

CGroupModel.prototype.populate = function (oGroup)
{
	this.isNew(oGroup.isNew());

	this.idGroup(oGroup.idGroup());
	this.idUser(oGroup.idUser());

	this.name(oGroup.name());
	this.nameFocused(oGroup.nameFocused());
	this.edited(oGroup.edited());
	
	this.isOrganization(oGroup.isOrganization());
	this.email(oGroup.email());
	this.country(oGroup.country());
	this.city(oGroup.city());
	this.company(oGroup.company());
	this.fax(oGroup.fax());
	this.phone(oGroup.phone());
	this.state(oGroup.state());
	this.street(oGroup.street());
	this.web(oGroup.web());
	this.zip(oGroup.zip());	
};

CGroupModel.prototype.switchToNew = function ()
{
	this.clear();
	this.edited(true);
	this.isNew(true);
	if (!bMobileApp)
	{
		this.nameFocused(true);
	}
};

CGroupModel.prototype.switchToView = function ()
{
	this.edited(false);
};

/**
 * @return {Object}
 */
CGroupModel.prototype.toObject = function ()
{
	return {
		'GroupId': this.idGroup(),
		'Name': this.name(),
		'IsOrganization': this.isOrganization() ? '1' : '0',
		'Email': this.email(),
		'Country': this.country(),
		'City': this.city(),
		'Company': this.company(),
		'Fax': this.fax(),
		'Phone': this.phone(),
		'State': this.state(),
		'Street': this.street(),
		'Web': this.web(),
		'Zip': this.zip()			
	};
};


/**
 * @constructor
 */
function CContactListItemModel()
{
	this.bIsGroup = false;
	this.bIsOrganization = false;
	this.bReadOnly = false;
	this.bItsMe = false;
	this.bGlobal = false;
	this.sId = '';
	this.sName = '';
	this.sEmail = '';
	this.bSharedToAll = false;

	this.deleted = ko.observable(false);
	this.checked = ko.observable(false);
	this.selected = ko.observable(false);
	this.recivedAnim = ko.observable(false).extend({'autoResetToFalse': 500});
}

/**
 *
 * @param {Object} oData
 * @param {boolean=} bGlobal
 */
CContactListItemModel.prototype.parse = function (oData, bGlobal)
{
	if (oData && 'Object/CContactListItem' === Utils.pExport(oData, '@Object', ''))
	{
		this.sId = Utils.pString(oData.Id);
		this.sName = Utils.pString(oData.Name);
		this.sEmail = Utils.pString(oData.Email);

		this.bIsGroup = !!oData.IsGroup;
		this.bIsOrganization = !!oData.IsOrganization;
		this.bReadOnly = !!oData.ReadOnly;
		this.bItsMe = !!oData.ItsMe;
		this.bGlobal = !!oData.Global;
		this.bSharedToAll =  !!oData.SharedToAll;
	}
};

/**
 * @return {boolean}
 */
CContactListItemModel.prototype.IsGroup = function ()
{
	return this.bIsGroup;
};

/**
 * @return {boolean}
 */
CContactListItemModel.prototype.Global = function ()
{
	return this.bGlobal;
};

/**
 * @return {boolean}
 */
CContactListItemModel.prototype.ReadOnly = function ()
{
	return this.bReadOnly;
};

/**
 * @return {boolean}
 */
CContactListItemModel.prototype.ItsMe = function ()
{
	return this.bItsMe;
};

/**
 * @return {string}
 */
CContactListItemModel.prototype.Id = function ()
{
	return this.sId;
};

/**
 * @return {string}
 */
CContactListItemModel.prototype.Name = function ()
{
	return this.sName;
};

/**
 * @return {string}
 */
CContactListItemModel.prototype.Email = function ()
{
	return this.sEmail;
};

/**
 * @return {string}
 */
CContactListItemModel.prototype.EmailAndName = function ()
{
	return this.sName && this.sEmail && 0 < this.sName.length && 0 < this.sEmail.length ? '"' + this.sName + '" <' + this.sEmail + '>' : this.sEmail;
};

/**
 * @return {boolean}
 */
CContactListItemModel.prototype.IsSharedToAll = function ()
{
	return this.bSharedToAll;
};

/**
 * @return {boolean}
 */
CContactListItemModel.prototype.IsOrganization = function ()
{
	return this.bIsOrganization;
};


/**
 * @constructor
 */
function CSignatureModel()
{
	this.iAccountId = 0;

	this.type = ko.observable(true);
	this.options = ko.observable(0);
	this.signature = ko.observable('');
	
}

/**
 * Calls a recursive parsing of the folder tree.
 * 
 * @param {number} iAccountId
 * @param {Object} oData
 */
CSignatureModel.prototype.parse = function (iAccountId, oData)
{
	this.iAccountId = iAccountId;
	
//	this.type(parseInt(oData.Type, 10) === 1 ? true : false);
	this.options(parseInt(oData.Options, 10));
	this.signature(oData.Signature);
};

/**
 * @constructor
 */
function CAutoresponderModel()
{
	this.iAccountId = 0;

	this.enable = false;
	this.subject = '';
	this.message = '';
}

/**
 * @param {number} iAccountId
 * @param {Object} oData
 */
CAutoresponderModel.prototype.parse = function (iAccountId, oData)
{
	this.iAccountId = iAccountId;

	this.enable = !!oData.Enable;
	this.subject = Utils.pString(oData.Subject);
	this.message = Utils.pString(oData.Message);
};

/**
 * @constructor
 */
function CFetcherModel()
{
	this.FETCHER = true; // constant
	
	this.id = ko.observable(0);
	this.accountId = ko.observable(0);
	this.isEnabled = ko.observable(false);
	this.isLocked = ko.observable(false).extend({'autoResetToFalse': 1000});
	this.email = ko.observable('');
	this.userName = ko.observable('');
	this.folder = ko.observable('');
	this.signatureOptions = ko.observable(false);
	this.signature = ko.observable('');
	this.incomingMailServer = ko.observable('');
	this.incomingMailPort = ko.observable(0);
	this.incomingMailLogin = ko.observable('');
	this.leaveMessagesOnServer = ko.observable('');
	this.isOutgoingEnabled = ko.observable(false);
	this.outgoingMailServer = ko.observable('');
	this.outgoingMailPort = ko.observable(0);
	this.outgoingMailAuth = ko.observable(false);
	
	this.fullEmail = ko.computed(function () {
		if (this.userName() === '')
		{
			return this.email();
		}
		else
		{
			return this.userName() + ' <' + this.email() + '>';
		}
	}, this);
}

/**
 * @constructor
 */
function CFetcherListModel()
{
	this.accountId = 0;

	this.collection = ko.observableArray([]);
}

/**
 * @param {number} iAccountId
 * @param {Array} aData
 */
CFetcherListModel.prototype.parse = function (iAccountId, aData)
{
	this.accountId = iAccountId;

	var
		aParsedCollection = [],
		iIndex = 0,
		iLen = 0,
		oFetcher = null,
		oData = null,
		iTimeout = 0
	;

	if (_.isArray(aData))
	{
		for (iLen = aData.length; iIndex < iLen; iIndex++)
		{
			oData = aData[iIndex];
			oFetcher = new CFetcherModel();

			oFetcher.id(oData['IdFetcher']);
			oFetcher.accountId(oData['IdAccount']);
			oFetcher.isEnabled(oData['IsEnabled']);
			oFetcher.isLocked(oData['IsLocked']);
			oFetcher.email(oData['Email']);
			oFetcher.userName(oData['Name']);
			oFetcher.folder(oData['Folder']);
			oFetcher.signatureOptions(!!oData['SignatureOptions']);
			oFetcher.signature(oData['Signature']);
			oFetcher.incomingMailServer(oData['IncomingMailServer']);
			oFetcher.incomingMailPort(oData['IncomingMailPort']);
			oFetcher.incomingMailLogin(oData['IncomingMailLogin']);
			oFetcher.leaveMessagesOnServer(oData['LeaveMessagesOnServer']);
			oFetcher.isOutgoingEnabled(oData['IsOutgoingEnabled']);
			oFetcher.outgoingMailServer(oData['OutgoingMailServer']);
			oFetcher.outgoingMailPort(oData['OutgoingMailPort']);
			oFetcher.outgoingMailAuth(oData['OutgoingMailAuth']);

			aParsedCollection.push(oFetcher);
		}
	}

	this.collection(aParsedCollection);
};

/**
 * @param {number} iFetcherId
 */
CFetcherListModel.prototype.getFetcher = function (iFetcherId)
{
	var
		oFetcher = null,
		iIndex = 0,
		iLen = 0,
		collection = this.collection()
	;

	for (iLen = collection.length; iIndex < iLen; iIndex++)
	{
		if (collection[iIndex].id() === iFetcherId)
		{
			oFetcher = collection[iIndex];
		}
	}

	return oFetcher;
};


/**
 * @constructor
 */
function CForwardModel()
{
	this.iAccountId = 0;

	this.enable = false;
	this.email = '';
}

/**
 * @param {number} iAccountId
 * @param {Object} oData
 */
CForwardModel.prototype.parse = function (iAccountId, oData)
{
	this.iAccountId = iAccountId;

	this.enable = !!oData.Enable;
	this.email = Utils.pString(oData.Email);
};

/**
 * @constructor
 */
function CSieveFiltersModel()
{
	this.iAccountId = 0;
	this.collection = ko.observableArray([]);
}

/**
 * @param {number} iAccountId
 * @param {Object} oData
 */
CSieveFiltersModel.prototype.parse = function (iAccountId, oData)
{
	var 
		iIndex = 0,
		iLen = oData.length,
		oSieveFilter = null
	;

	this.iAccountId = iAccountId;
	
	if (_.isArray(oData))
	{
		for (iLen = oData.length; iIndex < iLen; iIndex++)
		{	
			oSieveFilter =  new CSieveFilterModel(iAccountId);
			oSieveFilter.parse(oData[iIndex]);
			this.collection.push(oSieveFilter);
		}
	}
};

/**
 * @param {number} iAccountID
 * @constructor
 */
function CSieveFilterModel(iAccountID)
{
	this.iAccountId = iAccountID;
	
	this.enable = ko.observable(true).extend({'reversible': true});
	
	this.field = ko.observable('').extend({'reversible': true}); //map to Field
	this.condition = ko.observable('').extend({'reversible': true});
	this.filter = ko.observable('').extend({'reversible': true});
	this.action = ko.observable('').extend({'reversible': true});
	this.folder = ko.observable('').extend({'reversible': true});
}

/**
 * @param {Object} oData
 */
CSieveFilterModel.prototype.parse = function (oData)
{
	this.enable(!!oData.Enable);

	this.field(Utils.pInt(oData.Field));
	this.condition(Utils.pInt(oData.Condition));
	this.filter(Utils.pString(oData.Filter));
	this.action(Utils.pInt(oData.Action));
	this.folder(Utils.pString(oData.FolderFullName));
	this.commit();
};

CSieveFilterModel.prototype.revert = function ()
{
	this.enable.revert();
	this.field.revert();
	this.condition.revert();
	this.filter.revert();
	this.action.revert();
	this.folder.revert();
};

CSieveFilterModel.prototype.commit = function ()
{
	this.enable.commit();
	this.field.commit();
	this.condition.commit();
	this.filter.commit();
	this.action.commit();
	this.folder.commit();
};

CSieveFilterModel.prototype.toString = function ()
{
	var aState = [
		this.enable(),
		this.field(),
		this.condition(),
		this.filter(),
		this.action(),
		this.folder()
	];
	
	return aState.join(':');	
};


/**
 * @constructor
 */
function CInformationViewModel()
{
	this.iAnimationDuration = 500;
	this.iReportDuration = 5000;
	this.iErrorDuration = 10000;
	
	this.loadingMessage = ko.observable('');
	this.loadingHidden = ko.observable(true);
	this.loadingVisible = ko.observable(false);
	this.reportMessage = ko.observable('');
	this.reportHidden = ko.observable(true);
	this.reportVisible = ko.observable(false);
	this.reportVisibleClose = ko.observable(false);
	this.iReportTimeout = -1;
	this.errorMessage = ko.observable('');
	this.errorHidden = ko.observable(true);
	this.errorVisible = ko.observable(false);
	this.iErrorTimeout = -1;
	this.isHtmlError = ko.observable(false);
	this.gray = ko.observable(false);
}

/**
 * @param {string} sMessage
 */
CInformationViewModel.prototype.showLoading = function (sMessage)
{
	if (sMessage && sMessage !== '')
	{
		this.loadingMessage(sMessage);
	}
	else
	{
		this.loadingMessage(Utils.i18n('MAIN/LOADING'));
	}
	this.loadingVisible(true);
	_.defer(_.bind(function () {
		this.loadingHidden(false);
	}, this));
}
;

CInformationViewModel.prototype.hideLoading = function ()
{
	this.loadingHidden(true);
	setTimeout(_.bind(function () {
		if (this.loadingHidden())
		{
			this.loadingVisible(false);
		}
	}, this), this.iAnimationDuration);
};

/**
 * Displays a message. Starts a timer for hiding.
 * 
 * @param {string} sMessage
 * @param {number=} iDelay
 */
CInformationViewModel.prototype.showReport = function (sMessage, iDelay)
{
	if (iDelay !== 0)
	{
		iDelay = iDelay || this.iReportDuration;
	}
	
	if (sMessage && sMessage !== '')
	{
		this.reportMessage(sMessage);
		
		this.reportVisible(true);
		_.defer(_.bind(this.reportHidden, this, false));
		
		clearTimeout(this.iReportTimeout);
		if (iDelay === 0)
		{
			this.reportVisibleClose(true);
		}
		else
		{
			this.reportVisibleClose(false);
			this.iReportTimeout = setTimeout(_.bind(this.selfHideReport, this), iDelay);
		}
	}
	else
	{
		this.reportHidden(true);
		this.reportVisible(false);
	}
};

CInformationViewModel.prototype.selfHideReport = function ()
{
	this.reportHidden(true);
	setTimeout(_.bind(function () {
		if (this.reportHidden())
		{
			this.reportVisible(false);
		}
	}, this), this.iAnimationDuration);
};

/**
 * Displays an error message. Starts a timer for hiding.
 *
 * @param {string} sMessage
 * @param {boolean=} bHtml = false
 * @param {boolean=} bNotHide = false
 * @param {boolean=} bGray = false
 */
CInformationViewModel.prototype.showError = function (sMessage, bHtml, bNotHide, bGray)
{
	if (sMessage && sMessage !== '')
	{
		this.gray(!!bGray);
		this.errorMessage(sMessage);
		this.isHtmlError(bHtml);
		
		this.errorVisible(true);
		_.defer(_.bind(function () {
			this.errorHidden(false);
		}, this));
		
		clearTimeout(this.iErrorTimeout);
		if (!bNotHide)
		{
			this.iErrorTimeout = setTimeout(_.bind(function () {
				this.selfHideError();
			}, this), this.iErrorDuration);
		}
	}
	else
	{
		this.selfHideError();
	}
};

CInformationViewModel.prototype.selfHideError = function ()
{
	this.errorHidden(true);
	setTimeout(_.bind(function () {
		if (this.errorHidden())
		{
			this.errorVisible(false);
		}
	}, this), this.iAnimationDuration);
};

/**
 * @param {boolean=} bGray = false
 */
CInformationViewModel.prototype.hideError = function (bGray)
{
	bGray = Utils.isUnd(bGray) ? false : !!bGray;
	if (bGray === this.gray())
	{
		this.selfHideError();
	}
};


/**
 * @constructor
 */
function CHeaderBaseViewModel()
{
	var self = this;
	this.mobileApp = bMobileApp;
	this.mobileDevice = bMobileDevice;

	this.allowWebMail = AppData.App.AllowWebMail;
	this.currentAccountId = AppData.Accounts.currentId;
	this.currentAccountId.subscribe(function () {
		_.delay(function () {
			self.changeCurrentAccount();
		}, 300);
	}, this);
	
	this.tabs = App.headerTabs;

	this.email = ko.observable('');

	this.accounts = AppData.Accounts.collection;
	
	this.currentTab = App.Screens.currentScreen;

	this.isMailboxTab = ko.computed(function () {
		return this.currentTab() === Enums.Screens.Mailbox;
	}, this);

	this.helpdeskUnseenCount = App.helpdeskUnseenCount;
	this.helpdeskUnseenVisible = ko.computed(function () {
		return this.currentTab() !== Enums.Screens.Helpdesk && !!this.helpdeskUnseenCount();
	}, this);
	this.helpdeskPendingCount = App.helpdeskPendingCount;
	this.helpdeskPendingVisible = ko.computed(function () {
		return this.currentTab() !== Enums.Screens.Helpdesk && !!this.helpdeskPendingCount();
	}, this);
	this.mailUnseenCount = App.mailUnseenCount;
	this.mailUnseenVisible = ko.computed(function () {
		return this.currentTab() !== Enums.Screens.Mailbox && !!this.mailUnseenCount();
	}, this);

	this.mailboxHash = App.Routing.lastMailboxHash;
	this.settingsHash = App.Routing.lastSettingsHash;
	
	this.contactsRecivedAnim = App.ContactsCache.recivedAnim;
	this.calendarRecivedAnim = App.CalendarCache.recivedAnim;

	this.appCustomLogo = ko.observable(AppData['AppStyleImage'] || '');

	this.domMailHelperSmall = ko.observable(null);
	this.domMailHelper = ko.observable(null);
	this.mailHelperwidth = ko.observable(null);

	ko.computed(function(){
		var
			self = this,
			oElement = this.isMailboxTab() ? this.domMailHelper() : this.domMailHelperSmall()
		;
		this.accounts();
		this.email();
		if (oElement){
			_.delay(function () {
				if (oElement.width() !== 0){
					self.mailHelperwidth(oElement.outerWidth());
				}
			}, 400);
		}
	}, this);
}

CHeaderBaseViewModel.prototype.onRoute = function ()
{
	this.changeCurrentAccount();
};

CHeaderBaseViewModel.prototype.changeCurrentAccount = function ()
{
	this.email(AppData.Accounts.getEmail());
};

CHeaderBaseViewModel.prototype.logout = function ()
{
	App.logout();
};

CHeaderBaseViewModel.prototype.switchToFullVersion = function ()
{
	App.Ajax.send({
		'Action': 'SystemSetMobile',
		'Mobile': 0
	}, function () {
		window.location.reload();
	}, this);
};


/**
 * @constructor
 */
function CHeaderMobileViewModel()
{
	CHeaderBaseViewModel.call(this);
}

_.extend(CHeaderMobileViewModel.prototype, CHeaderBaseViewModel.prototype);

/**
 * @constructor
 * @param {number} iCount
 * @param {number} iPerPage
 */
function CPageSwitcherViewModel(iCount, iPerPage)
{
	this.shown = false;
	
	this.currentPage = ko.observable(1);
	this.count = ko.observable(iCount);
	this.perPage = ko.observable(iPerPage);
	this.firstPage = ko.observable(1);
	this.lastPage = ko.observable(1);

	this.pagesCount = ko.computed(function () {
		var iCount = Math.ceil(this.count() / this.perPage());
		return (iCount > 0) ? iCount : 1;
	}, this);

	ko.computed(function () {

		var
			iAllLimit = 20,
			iLimit = 4,
			iPagesCount = this.pagesCount(),
			iCurrentPage = this.currentPage(),
			iStart = iCurrentPage,
			iEnd = iCurrentPage
		;

		if (iPagesCount > 1)
		{
			while (true)
			{
				iAllLimit--;
				
				if (1 < iStart)
				{
					iStart--;
					iLimit--;
				}

				if (0 === iLimit)
				{
					break;
				}

				if (iPagesCount > iEnd)
				{
					iEnd++;
					iLimit--;
				}

				if (0 === iLimit)
				{
					break;
				}

				if (0 === iAllLimit)
				{
					break;
				}
			}
		}

		this.firstPage(iStart);
		this.lastPage(iEnd);
		
	}, this);

	
//	this.firstPage = ko.computed(function () {
//		var iValue = this.currentPage() - this.iLimitAround;
//		return (iValue > 0) ? iValue : 1;
//	}, this);
//
//	this.lastPage = ko.computed(function () {
//		var iValue = this.firstPage() + this.iLimitAround * 2;
//		return (iValue <= this.pagesCount()) ? iValue : this.pagesCount();
//	}, this);

	this.visibleFirst = ko.computed(function () {
		return (this.firstPage() > 1);
	}, this);

	this.visibleLast = ko.computed(function () {
		return (this.lastPage() < this.pagesCount());
	}, this);

	this.clickPage = _.bind(this.clickPage, this);

	this.pages = ko.computed(function () {
		var
			iIndex = this.firstPage(),
			aPages = []
		;

		if (this.firstPage() < this.lastPage())
		{
			for (; iIndex <= this.lastPage(); iIndex++)
			{
				aPages.push({
					number: iIndex,
					current: (iIndex === this.currentPage()),
					clickFunc: this.clickPage
				});
			}
		}

		return aPages;
	}, this);
	
	this.hotKeysBind();
}

CPageSwitcherViewModel.prototype.hotKeysBind = function ()
{
	$(document).on('keydown', $.proxy(function(ev) {
		if (this.shown && !Utils.isTextFieldFocused())
		{
			var sKey = ev.keyCode;
			if (ev.ctrlKey && sKey === Enums.Key.Left)
			{
				this.clickPreviousPage();
			}
			else if (ev.ctrlKey && sKey === Enums.Key.Right)
			{
				this.clickNextPage();
			}
		}
	},this));
};

CPageSwitcherViewModel.prototype.hide = function ()
{
	this.shown = false;
};

CPageSwitcherViewModel.prototype.show = function ()
{
	this.shown = true;
};

CPageSwitcherViewModel.prototype.clear = function ()
{
	this.currentPage(1);
	this.count(0);
};

/**
 * @param {number} iCount
 */
CPageSwitcherViewModel.prototype.setCount = function (iCount)
{
	this.count(iCount);
	if (this.currentPage() > this.pagesCount())
	{
		this.currentPage(this.pagesCount());
	}
};

/**
 * @param {number} iPage
 * @param {number} iPerPage
 */
CPageSwitcherViewModel.prototype.setPage = function (iPage, iPerPage)
{
	this.perPage(iPerPage);
	if (iPage > this.pagesCount())
	{
		this.currentPage(this.pagesCount());
	}
	else
	{
		this.currentPage(iPage);
	}
};

/**
 * @param {Object} oPage
 */
CPageSwitcherViewModel.prototype.clickPage = function (oPage)
{
	var iPage = oPage.number;
	if (iPage < 1)
	{
		iPage = 1;
	}
	if (iPage > this.pagesCount())
	{
		iPage = this.pagesCount();
	}
	this.currentPage(iPage);
};

CPageSwitcherViewModel.prototype.clickFirstPage = function ()
{
	this.currentPage(1);
};

CPageSwitcherViewModel.prototype.clickPreviousPage = function ()
{
	var iPrevPage = this.currentPage() - 1;
	if (iPrevPage < 1)
	{
		iPrevPage = 1;
	}
	this.currentPage(iPrevPage);
};

CPageSwitcherViewModel.prototype.clickNextPage = function ()
{
	var iNextPage = this.currentPage() + 1;
	if (iNextPage > this.pagesCount())
	{
		iNextPage = this.pagesCount();
	}
	this.currentPage(iNextPage);
};

CPageSwitcherViewModel.prototype.clickLastPage = function ()
{
	this.currentPage(this.pagesCount());
};

/**
 * @constructor
 * @param {boolean} bInsertImageAsBase64
 * @param {Object=} oParent
 */
function CHtmlEditorViewModel(bInsertImageAsBase64, oParent)
{
	this.mobileApp = bMobileApp;
	
	this.oParent = oParent;
	
	this.creaId = 'creaId' + Math.random().toString().replace('.', '');
	this.textFocused = ko.observable(false);
	this.workareaDom = ko.observable();
	this.uploaderAreaDom = ko.observable();
	this.editorUploaderBodyDragOver = ko.observable(false);
	this.editorUploaderProgress = ko.observable(false);
	
	this.htmlEditorDom = ko.observable();
	this.colorPickerDropdownDom = ko.observable();
	this.insertLinkDropdownDom = ko.observable();
	this.insertImageDropdownDom = ko.observable();

	this.isEnable = ko.observable(true);
	this.isEnable.subscribe(function () {
		if (this.oCrea)
		{
			this.oCrea.setEditable(this.isEnable());
		}
	}, this);

	this.bInsertImageAsBase64 = bInsertImageAsBase64;
	this.bAllowFileUpload = !(bInsertImageAsBase64 && window.File === undefined);
	this.allowInsertImage = ko.observable(AppData.App.AllowInsertImage);
	this.lockFontSubscribing = ko.observable(false);
	this.bAllowImageDragAndDrop = !App.browser.ie10AndAbove;

	this.aFonts = ['Arial', 'Arial Black', 'Courier New', 'Tahoma', 'Times New Roman', 'Verdana'];
	this.sDefaultFont = AppData.User.DefaultFontName;
	this.correctFontFromSettings();
	this.selectedFont = ko.observable('');
	this.selectedFont.subscribe(function () {
		if (this.oCrea && !this.lockFontSubscribing() && !this.inactive())
		{
			this.oCrea.fontName(this.selectedFont());
		}
	}, this);

	this.iDefaultSize = AppData.User.DefaultFontSize;
	this.selectedSize = ko.observable(0);
	this.selectedSize.subscribe(function () {
		if (this.oCrea && !this.lockFontSubscribing() && !this.inactive())
		{
			this.oCrea.fontSize(this.selectedSize());
		}
	}, this);

	this.visibleInsertLinkPopup = ko.observable(false);
	this.linkForInsert = ko.observable('');
	this.linkFocused = ko.observable(false);
	this.visibleLinkPopup = ko.observable(false);
	this.linkPopupTop = ko.observable(0);
	this.linkPopupLeft = ko.observable(0);
	this.linkHref = ko.observable('');
	this.visibleLinkHref = ko.observable(false);

	this.visibleImagePopup = ko.observable(false);
	this.visibleImagePopup.subscribe(function () {
		this.onImageOut();
	}, this);
	this.imagePopupTop = ko.observable(0);
	this.imagePopupLeft = ko.observable(0);
	this.imageSelected = ko.observable(false);
	
	this.tooltipText = ko.observable('');
	this.tooltipPopupTop = ko.observable(0);
	this.tooltipPopupLeft = ko.observable(0);

	this.visibleInsertImagePopup = ko.observable(false);
	this.imageUploaderButton = ko.observable(null);
	this.uploadedImagePathes = ko.observableArray([]);
	this.imagePathFromWeb = ko.observable('');

	this.visibleFontColorPopup = ko.observable(false);
	this.oFontColorPicker = new CColorPickerViewModel(Utils.i18n('HTMLEDITOR/TEXT_COLOR_CAPTION'), this.setTextColorFromPopup, this);
	this.oBackColorPicker = new CColorPickerViewModel(Utils.i18n('HTMLEDITOR/BACKGROUND_COLOR_CAPTION'), this.setBackColorFromPopup, this);

	this.activitySource = ko.observable(1);
	this.activitySourceSubscription = null;
	this.inactive = ko.observable(false);
	this.inactive.subscribe(function () {
		var sText = this.removeAllTags(this.getText());
		
		if (this.inactive())
		{
			if (sText === '' || sText === '&nbsp;')
			{
				this.setText('<span style="color: #AAAAAA;">' + Utils.i18n('HTMLEDITOR/SIGNATURE_PLACEHOLDER') + '</span>');
				if (this.oCrea)
				{
					this.oCrea.setBlur();
				}
			}
		}
		else
		{
			if (sText === Utils.i18n('HTMLEDITOR/SIGNATURE_PLACEHOLDER'))
			{
				this.setText('');
			}
		}
	}, this);
	
	this.allowChangeInputDirection = Utils.isRTL() || AppData.User.AllowChangeInputDirection;
	this.disabled = ko.observable(false);
	
	this.textChanged = ko.observable(false);
}

CHtmlEditorViewModel.prototype.hasOpenedPopup = function ()
{
	return this.visibleInsertLinkPopup() || this.visibleLinkPopup() || this.visibleImagePopup() || this.visibleInsertImagePopup() || this.visibleFontColorPopup();
};
	
CHtmlEditorViewModel.prototype.init = function ()
{
	var self = this;

	$(document.body).on('click', _.bind(function (oEvent) {
		this.closeAllPopups(true);
	}, this));
	
	this.initEditorUploader();
};

CHtmlEditorViewModel.prototype.correctFontFromSettings = function ()
{
	var
		sDefaultFont = this.sDefaultFont,
		bFinded = false
	;
	
	_.each(this.aFonts, function (sFont) {
		if (sFont.toLowerCase() === sDefaultFont.toLowerCase())
		{
			sDefaultFont = sFont;
			bFinded = true;
		}
	});
	
	if (bFinded)
	{
		this.sDefaultFont = sDefaultFont;
	}
	else
	{
		this.aFonts.push(sDefaultFont);
	}
};

/**
 * @param {Object} $link
 */
CHtmlEditorViewModel.prototype.showLinkPopup = function ($link)
{
	var
		$workarea = $(this.workareaDom()),
		oWorkareaPos = $workarea.position(),
		oPos = $link.position(),
		iHeight = $link.height()
	;

	this.linkHref($link.attr('href') || $link.text());
	this.linkPopupLeft(Math.round(oPos.left + oWorkareaPos.left));
	this.linkPopupTop(Math.round(oPos.top + iHeight + oWorkareaPos.top));

	this.visibleLinkPopup(true);
};

CHtmlEditorViewModel.prototype.hideLinkPopup = function ()
{
	this.visibleLinkPopup(false);
};

CHtmlEditorViewModel.prototype.showChangeLink = function ()
{
	this.visibleLinkHref(true);
	this.hideLinkPopup();
};

CHtmlEditorViewModel.prototype.changeLink = function ()
{
	this.oCrea.changeLink(this.linkHref());
	this.hideChangeLink();
};

CHtmlEditorViewModel.prototype.hideChangeLink = function ()
{
	this.visibleLinkHref(false);
};

/**
 * @param {jQuery} $image
 * @param {Object} oEvent
 */
CHtmlEditorViewModel.prototype.showImagePopup = function ($image, oEvent)
{
	var
		$workarea = $(this.workareaDom()),
		oWorkareaPos = $workarea.position(),
		oWorkareaOffset = $workarea.offset()
	;
	
	this.imagePopupLeft(Math.round(oEvent.pageX + oWorkareaPos.left - oWorkareaOffset.left));
	this.imagePopupTop(Math.round(oEvent.pageY + oWorkareaPos.top - oWorkareaOffset.top));

	this.visibleImagePopup(true);
};

CHtmlEditorViewModel.prototype.hideImagePopup = function ()
{
	this.visibleImagePopup(false);
};

CHtmlEditorViewModel.prototype.resizeImage = function (sSize)
{
	var oParams = {
		'width': 'auto',
		'height': 'auto'
	};
	
	switch (sSize)
	{
		case Enums.HtmlEditorImageSizes.Small:
			oParams.width = '300px';
			break;
		case Enums.HtmlEditorImageSizes.Medium:
			oParams.width = '600px';
			break;
		case Enums.HtmlEditorImageSizes.Large:
			oParams.width = '1200px';
			break;
		case Enums.HtmlEditorImageSizes.Original:
			oParams.width = 'auto';
			break;
	}
	
	this.oCrea.changeCurrentImage(oParams);
	
	this.visibleImagePopup(false);
};

CHtmlEditorViewModel.prototype.onImageOver = function (oEvent)
{
	if (oEvent.target.nodeName === 'IMG' && !this.visibleImagePopup())
	{
		this.imageSelected(true);
		
		this.tooltipText(Utils.i18n('HTMLEDITOR/CLICK_TO_EDIT_IMAGE'));
		
		var 
			self = this,
			$workarea = $(this.workareaDom())
		;
		
		$workarea.bind('mousemove.image', function (oEvent) {

			var
				oWorkareaPos = $workarea.position(),
				oWorkareaOffset = $workarea.offset()
			;

			self.tooltipPopupTop(Math.round(oEvent.pageY + oWorkareaPos.top - oWorkareaOffset.top));
			self.tooltipPopupLeft(Math.round(oEvent.pageX + oWorkareaPos.left - oWorkareaOffset.left));
		});
	}
	
	return true;
};

CHtmlEditorViewModel.prototype.onImageOut = function (oEvent)
{
	if (this.imageSelected())
	{
		this.imageSelected(false);
		
		var $workarea = $(this.workareaDom());
		$workarea.unbind('mousemove.image');
	}
	
	return true;
};

CHtmlEditorViewModel.prototype.commit = function ()
{
	this.textChanged(false);
};

/**
 * @param {string} sText
 * @param {boolean} bPlain
 * @param {string} sTabIndex
 */
CHtmlEditorViewModel.prototype.initCrea = function (sText, bPlain, sTabIndex)
{
	if (!this.oCrea)
	{
		this.init();
		this.oCrea = new Crea({
			'creaId': this.creaId,
			'fontNameArray': this.aFonts,
			'defaultFontName': this.sDefaultFont,
			'defaultFontSize': this.iDefaultSize,
			'isRtl': Utils.isRTL(),
			'enableDrop': false,
			'onChange': _.bind(this.textChanged, this, true),
			'onCursorMove': _.bind(this.setFontValuesFromText, this),
			'onFocus': _.bind(this.onCreaFocus, this),
			'onBlur': _.bind(this.onCreaBlur, this),
			'onUrlIn': _.bind(this.showLinkPopup, this),
			'onUrlOut': _.bind(this.hideLinkPopup, this),
			'onImageSelect': _.bind(this.showImagePopup, this),
			'onImageBlur': _.bind(this.hideImagePopup, this),
			'onItemOver': (bMobileDevice || bMobileApp) ? null : _.bind(this.onImageOver, this),
			'onItemOut': (bMobileDevice || bMobileApp) ? null : _.bind(this.onImageOut, this),
			'openInsertLinkDialog': _.bind(this.insertLink, this)
		});
		this.oCrea.start(this.isEnable());
	}

	this.oCrea.setTabIndex(sTabIndex);
	this.oCrea.clearUndoRedo();
	this.setText(sText, bPlain);
	this.setFontValuesFromText();
	this.uploadedImagePathes([]);
	this.selectedFont(this.sDefaultFont);
	this.selectedSize(this.iDefaultSize);
	//this.selectedFont(window.document.queryCommandValue('FontName'));
};

CHtmlEditorViewModel.prototype.setFocus = function ()
{
	if (this.oCrea)
	{
		this.oCrea.setFocus(false);
	}
};

/**
 * @param {string} sNewSignatureContent
 * @param {string} sOldSignatureContent
 */
CHtmlEditorViewModel.prototype.changeSignatureContent = function (sNewSignatureContent, sOldSignatureContent)
{
	if (this.oCrea)
	{
		this.oCrea.changeSignatureContent(sNewSignatureContent, sOldSignatureContent);
	}
};

CHtmlEditorViewModel.prototype.setFontValuesFromText = function ()
{
	this.lockFontSubscribing(true);
	this.selectedFont(this.oCrea.getFontName());
	this.selectedSize(this.oCrea.getFontSizeInNumber());
	this.lockFontSubscribing(false);

};

CHtmlEditorViewModel.prototype.isUndoAvailable = function ()
{
	if (this.oCrea)
	{
		return this.oCrea.isUndoAvailable();
	}

	return false;
};

CHtmlEditorViewModel.prototype.getPlainText = function ()
{
	if (this.oCrea)
	{
		return this.oCrea.getPlainText();
	}

	return '';
};

/**
 * @param {boolean=} bRemoveSignatureAnchor = false
 */
CHtmlEditorViewModel.prototype.getText = function (bRemoveSignatureAnchor)
{
	return this.oCrea ? this.oCrea.getText(bRemoveSignatureAnchor) : '';
};

/**
 * @param {string} sText
 * @param {boolean} bPlain
 */
CHtmlEditorViewModel.prototype.setText = function (sText, bPlain)
{
	if (this.oCrea)
	{
		if (bPlain)
		{
			this.oCrea.setPlainText(sText);
		}
		else
		{
			this.oCrea.setText(sText);
		}
		this.inactive.valueHasMutated();
	}
};

CHtmlEditorViewModel.prototype.undoAndClearRedo = function ()
{
	if (this.oCrea)
	{
		this.oCrea.undo();
		this.oCrea.clearRedo();
	}
};
CHtmlEditorViewModel.prototype.clearUndoRedo = function ()
{
	if (this.oCrea)
	{
		this.oCrea.clearUndoRedo();
	}
};

CHtmlEditorViewModel.prototype.isEditing = function ()
{
	return this.oCrea ? this.oCrea.bEditing : false;
};

CHtmlEditorViewModel.prototype.getNotDefaultText = function ()
{
	var sText = this.getText();

	return this.removeAllTags(sText) !== Utils.i18n('HTMLEDITOR/SIGNATURE_PLACEHOLDER') ? sText : '';
};

/**
 * @param {string} sText
 */
CHtmlEditorViewModel.prototype.removeAllTags = function (sText)
{
	return sText.replace(/<style>.*<\/style>/g, '').replace(/<[^>]*>/g, '');
};

/**
 * @param {koProperty} koActivitySource
 */
CHtmlEditorViewModel.prototype.setActivitySource = function (koActivitySource)
{
	this.activitySource = koActivitySource;
	
	if (this.activitySourceSubscription !== null)
	{
		this.activitySourceSubscription.dispose();
	}
	this.activitySourceSubscription = this.activitySource.subscribe(function () {
		this.inactive(Utils.pInt(this.activitySource()) === 0);
	}, this);

	this.inactive(Utils.pInt(this.activitySource()) === 0);
};

CHtmlEditorViewModel.prototype.onCreaFocus = function ()
{
	if (this.oCrea)
	{
		this.closeAllPopups();
		this.activitySource(1);
		this.textFocused(true);
	}
};

CHtmlEditorViewModel.prototype.onCreaBlur = function ()
{
	if (this.oCrea)
	{
		this.textFocused(false);
	}
};

CHtmlEditorViewModel.prototype.onEscHandler = function ()
{
	if (App.Screens.popups.length === 0)
	{
		this.closeAllPopups();
	}
};

/**
 * @param {boolean} bWithoutLinkPopup
 */
CHtmlEditorViewModel.prototype.closeAllPopups = function (bWithoutLinkPopup)
{
	bWithoutLinkPopup = !!bWithoutLinkPopup;
	if (!bWithoutLinkPopup)
	{
		this.visibleLinkPopup(false);
	}
	this.visibleInsertLinkPopup(false);
	this.visibleImagePopup(false);
	this.visibleInsertImagePopup(false);
	this.visibleFontColorPopup(false);
};

/**
 * @param {string} sHtml
 */
CHtmlEditorViewModel.prototype.insertHtml = function (sHtml)
{
	if (this.oCrea)
	{
		if (!this.oCrea.isFocused())
		{
			this.oCrea.setFocus(true);
		}
		
		this.oCrea.insertHtml(sHtml, false);
	}
};

/**
 * @param {Object} oViewModel
 * @param {Object} oEvent
 */
CHtmlEditorViewModel.prototype.insertLink = function (oViewModel, oEvent)
{
	if (oEvent)
	{
		this.setActive(oEvent);
	}
	if (!this.visibleInsertLinkPopup())
	{
		this.linkForInsert(this.oCrea.getSelectedText());
		this.closeAllPopups();
		this.visibleInsertLinkPopup(true);
		this.linkFocused(true);
	}
};

/**
 * @param {Object} oCurrentViewModel
 * @param {Object} event
 */
CHtmlEditorViewModel.prototype.insertLinkFromPopup = function (oCurrentViewModel, event)
{
	if (this.linkForInsert().length > 0)
	{
		if (Utils.isCorrectEmail(this.linkForInsert()))
		{
			this.oCrea.insertEmailLink(this.linkForInsert());
		}
		else
		{
			this.oCrea.insertLink(this.linkForInsert());
		}
	}
	
	this.closeInsertLinkPopup(oCurrentViewModel, event);
};

/**
 * @param {Object} oCurrentViewModel
 * @param {Object} event
 */
CHtmlEditorViewModel.prototype.closeInsertLinkPopup = function (oCurrentViewModel, event)
{
	this.visibleInsertLinkPopup(false);
	if (event)
	{
		event.stopPropagation();
	}
};

CHtmlEditorViewModel.prototype.textColor = function (oViewModel, oEvent)
{
	this.setActive(oEvent);
	if (this.visibleFontColorPopup())
	{
		this.closeAllPopups();
	}
	else
	{
		this.closeAllPopups();
		this.visibleFontColorPopup(true);
		this.oFontColorPicker.onShow();
		this.oBackColorPicker.onShow();
	}
};

/**
 * @param {string} sColor
 * @return string
 */
CHtmlEditorViewModel.prototype.colorToHex = function (sColor)
{
    if (sColor.substr(0, 1) === '#')
	{
        return sColor;
    }

	/*jslint bitwise: true*/
    var
		aDigits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(sColor),
		iRed = parseInt(aDigits[2], 10),
		iGreen = parseInt(aDigits[3], 10),
		iBlue = parseInt(aDigits[4], 10),
		iRgb = iBlue | (iGreen << 8) | (iRed << 16),
		sRgb = iRgb.toString(16)
	;
	/*jslint bitwise: false*/

	while (sRgb.length < 6)
	{
		sRgb = '0' + sRgb;
	}

    return aDigits[1] + '#' + sRgb;
};

/**
 * @param {string} sColor
 */
CHtmlEditorViewModel.prototype.setTextColorFromPopup = function (sColor)
{
	this.oCrea.textColor(this.colorToHex(sColor));
	this.closeAllPopups();
};

/**
 * @param {string} sColor
 */
CHtmlEditorViewModel.prototype.setBackColorFromPopup = function (sColor)
{
	this.oCrea.backgroundColor(this.colorToHex(sColor));
	this.closeAllPopups();
};

CHtmlEditorViewModel.prototype.insertImage = function (oViewModel, oEvent)
{
	this.setActive(oEvent);
	if (this.allowInsertImage() && !this.visibleInsertImagePopup())
	{
		this.imagePathFromWeb('');
		this.closeAllPopups();
		this.visibleInsertImagePopup(true);
		this.initUploader();
	}

	return true;
};

/**
 * @param {Object} oCurrentViewModel
 * @param {Object} event
 */
CHtmlEditorViewModel.prototype.insertWebImageFromPopup = function (oCurrentViewModel, event)
{
	if (this.allowInsertImage() && this.imagePathFromWeb().length > 0)
	{
		this.oCrea.insertImage(this.imagePathFromWeb());
	}

	this.closeInsertImagePopup(oCurrentViewModel, event);
};

/**
 * @param {string} sUid
 * @param oAttachment
 */
CHtmlEditorViewModel.prototype.insertComputerImageFromPopup = function (sUid, oAttachment)
{
	var
		sViewLink = Utils.getViewLinkByHash(AppData.Accounts.currentId(), oAttachment.Hash),
		bResult = false
	;

	if (this.allowInsertImage() && sViewLink.length > 0)
	{
		bResult = this.oCrea.insertImage(sViewLink);
		if (bResult)
		{
			$(this.oCrea.$editableArea)
				.find('img[src="' + sViewLink + '"]')
				.attr('data-x-src-cid', sUid)
			;

			oAttachment.CID = sUid;
			this.uploadedImagePathes.push(oAttachment);
		}
	}

	this.closeInsertImagePopup();
};

/**
 * @param {?=} oCurrentViewModel
 * @param {?=} event
 */
CHtmlEditorViewModel.prototype.closeInsertImagePopup = function (oCurrentViewModel, event)
{
	this.visibleInsertImagePopup(false);
	if (event)
	{
		event.stopPropagation();
	}
};

/**
 * Initializes file uploader.
 */
CHtmlEditorViewModel.prototype.initUploader = function ()
{
	if (this.imageUploaderButton() && !this.oJua)
	{
		this.oJua = new Jua({
			'action': '?/Upload/Attachment/',
			'name': 'jua-uploader',
			'queueSize': 2,
			'clickElement': this.imageUploaderButton(),
			'hiddenElementsPosition': Utils.isRTL() ? 'right' : 'left',
			'disableMultiple': true,
			'disableAjaxUpload': false,
			'disableDragAndDrop': true,
			'hidden': {
				'Token': function () {
					return AppData.Token;
				},
				'AccountID': function () {
					return AppData.Accounts.currentId();
				}
			}
		});

		if (this.bInsertImageAsBase64)
		{
			this.oJua
				.on('onSelect', _.bind(this.onEditorDrop, this))
			;
		}
		else
		{
			this.oJua
				.on('onSelect', _.bind(this.onFileUploadSelect, this))
				.on('onComplete', _.bind(this.onFileUploadComplete, this))
			;
		}
	}
};

/**
 * Initializes file uploader for editor.
 */
CHtmlEditorViewModel.prototype.initEditorUploader = function ()
{
	if (AppData.App.AllowInsertImage && this.uploaderAreaDom() && !this.editorUploader)
	{
		var
			fBodyDragEnter = null,
			fBodyDragOver = null
		;

		if (this.oParent && this.oParent.composeUploaderDragOver && this.oParent.onFileUploadProgress &&
				this.oParent.onFileUploadStart && this.oParent.onFileUploadComplete)
		{
			fBodyDragEnter = _.bind(function () {
				this.editorUploaderBodyDragOver(true);
				this.oParent.composeUploaderDragOver(true);
			}, this);

			fBodyDragOver = _.bind(function () {
				this.editorUploaderBodyDragOver(false);
				this.oParent.composeUploaderDragOver(false);
			}, this);

			this.editorUploader = new Jua({
				'action': '?/Upload/Attachment/',
				'name': 'jua-uploader',
				'queueSize': 1,
				'dragAndDropElement': this.bAllowImageDragAndDrop ? this.uploaderAreaDom() : null,
				'disableMultiple': true,
				'disableAjaxUpload': false,
				'disableDragAndDrop': !this.bAllowImageDragAndDrop,
				'hidden': {
					'Token': function () {
						return AppData.Token;
					},
					'AccountID': function () {
						return AppData.Accounts.currentId();
					}
				}
			});

			this.editorUploader
				.on('onDragEnter', _.bind(this.oParent.composeUploaderDragOver, this.oParent, true))
				.on('onDragLeave', _.bind(this.oParent.composeUploaderDragOver, this.oParent, false))
				.on('onBodyDragEnter', fBodyDragEnter)
				.on('onBodyDragLeave', fBodyDragOver)
				.on('onProgress', _.bind(this.oParent.onFileUploadProgress, this.oParent))
				.on('onSelect', _.bind(this.onEditorDrop, this))
				.on('onStart', _.bind(this.oParent.onFileUploadStart, this.oParent))
				.on('onComplete', _.bind(this.oParent.onFileUploadComplete, this.oParent))
			;
		}
		else
		{
			fBodyDragEnter = _.bind(this.editorUploaderBodyDragOver, this, true);
			fBodyDragOver = _.bind(this.editorUploaderBodyDragOver, this, false);

			this.editorUploader = new Jua({
				'queueSize': 1,
				'dragAndDropElement': this.bAllowImageDragAndDrop ? this.uploaderAreaDom() : null,
				'disableMultiple': true,
				'disableAjaxUpload': false,
				'disableDragAndDrop': !this.bAllowImageDragAndDrop
			});

			this.editorUploader
				.on('onBodyDragEnter', fBodyDragEnter)
				.on('onBodyDragLeave', fBodyDragOver)
				.on('onSelect', _.bind(this.onEditorDrop, this))
			;
		}
	}
};

CHtmlEditorViewModel.prototype.onEditorDrop = function (sUid, oData) {
	var 
		oReader = null,
		oFile = null,
		self = this,
		bCreaFocused = false,
		hash = Math.random().toString(),
		sId = ''
	;
	
	if (oData && oData.File && oData.File.type)
	{
		if (AppData.App.AllowInsertImage && 0 === oData.File.type.indexOf('image/'))
		{
			oFile = oData.File;
			if (AppData.App.ImageUploadSizeLimit > 0 && oFile.size > AppData.App.ImageUploadSizeLimit)
			{
				App.Screens.showPopup(AlertPopup, [Utils.i18n('COMPOSE/UPLOAD_ERROR_SIZE')]);
			}
			else
			{
				oReader = new window.FileReader();
				bCreaFocused = this.oCrea.isFocused();
				if (!bCreaFocused)
				{
					this.oCrea.setFocus(true);
				}

				sId = oFile.name + '_' + hash;
				this.oCrea.insertHtml('<img id="' + sId + '" src="skins/Default/images/wait.gif" />', true);
				if (!bCreaFocused)
				{
					this.oCrea.fixFirefoxCursorBug();
				}

				oReader.onload = function (oEvent) {
					self.oCrea.changeImageSource(sId, oEvent.target.result);
				};

				oReader.readAsDataURL(oFile);
			}
		}
		else
		{
			if (this.oParent && this.oParent.onFileUploadSelect)
			{
				this.oParent.onFileUploadSelect(sUid, oData);
				return true;
			}
			else if (!App.browser.ie10AndAbove)
			{
				App.Screens.showPopup(AlertPopup, [Utils.i18n('HTMLEDITOR/UPLOAD_ERROR_NOT_IMAGE')]);
			}
		}
	}
	
	return false;
};

/**
 * @param {Object} oFile
 */
CHtmlEditorViewModel.prototype.isFileImage = function (oFile)
{
	if (typeof oFile.Type === 'string')
	{
		return (-1 !== oFile.Type.indexOf('image'));
	}
	else
	{
		var
			iDotPos = oFile.FileName.lastIndexOf('.'),
			sExt = oFile.FileName.substr(iDotPos + 1),
			aImageExt = ['jpg', 'jpeg', 'gif', 'tif', 'tiff', 'png']
		;

		return (-1 !== $.inArray(sExt, aImageExt));
	}
};

/**
 * @param {string} sUid
 * @param {Object} oFile
 */
CHtmlEditorViewModel.prototype.onFileUploadSelect = function (sUid, oFile)
{
	if (!this.isFileImage(oFile))
	{
		App.Screens.showPopup(AlertPopup, [Utils.i18n('HTMLEDITOR/UPLOAD_ERROR_NOT_IMAGE')]);
		return false;
	}
	
	this.closeInsertImagePopup();
	return true;
};

/**
 * @param {string} sUid
 * @param {boolean} bResponseReceived
 * @param {Object} oData
 */
CHtmlEditorViewModel.prototype.onFileUploadComplete = function (sUid, bResponseReceived, oData)
{
	var sError = '';
	
	if (oData && oData.Result)
	{
		if (oData.Result.Error)
		{
			sError = oData.Result.Error === 'size' ?
				Utils.i18n('COMPOSE/UPLOAD_ERROR_SIZE') :
				Utils.i18n('COMPOSE/UPLOAD_ERROR_UNKNOWN');

			App.Screens.showPopup(AlertPopup, [sError]);
		}
		else
		{
			this.oCrea.setFocus(true);
			this.insertComputerImageFromPopup(sUid, oData.Result.Attachment);
		}
	}
	else
	{
		App.Screens.showPopup(AlertPopup, [Utils.i18n('COMPOSE/UPLOAD_ERROR_UNKNOWN')]);
	}
};

CHtmlEditorViewModel.prototype.setActive = function (oEvent)
{
	oEvent.stopPropagation();
	this.activitySource(1);
};

/**
 * @constructor
 * @param {string} sCaption
 * @param {Function} fPickHandler
 * @param {Object} oPickContext
 */
function CColorPickerViewModel(sCaption, fPickHandler, oPickContext)
{
	this.aGreyColors = ['rgb(0, 0, 0)', 'rgb(68, 68, 68)', 'rgb(102, 102, 102)', 'rgb(153, 153, 153)',
		'rgb(204, 204, 204)', 'rgb(238, 238, 238)', 'rgb(243, 243, 243)', 'rgb(255, 255, 255)'];
	
	this.aBrightColors = ['rgb(255, 0, 0)', 'rgb(255, 153, 0)', 'rgb(255, 255, 0)', 'rgb(0, 255, 0)', 
		'rgb(0, 255, 255)', 'rgb(0, 0, 255)', 'rgb(153, 0, 255)', 'rgb(255, 0, 255)'];
	
	this.aColorLines = [
		['rgb(244, 204, 204)', 'rgb(252, 229, 205)', 'rgb(255, 242, 204)', 'rgb(217, 234, 211)', 
				'rgb(208, 224, 227)', 'rgb(207, 226, 243)', 'rgb(217, 210, 233)', 'rgb(234, 209, 220)'],
		['rgb(234, 153, 153)', 'rgb(249, 203, 156)', 'rgb(255, 229, 153)', 'rgb(182, 215, 168)', 
				'rgb(162, 196, 201)', 'rgb(159, 197, 232)', 'rgb(180, 167, 214)', 'rgb(213, 166, 189)'],
		['rgb(224, 102, 102)', 'rgb(246, 178, 107)', 'rgb(255, 217, 102)', 'rgb(147, 196, 125)', 
				'rgb(118, 165, 175)', 'rgb(111, 168, 220)', 'rgb(142, 124, 195)', 'rgb(194, 123, 160)'],
		['rgb(204, 0, 0)', 'rgb(230, 145, 56)', 'rgb(241, 194, 50)', 'rgb(106, 168, 79)', 
				'rgb(69, 129, 142)', 'rgb(61, 133, 198)', 'rgb(103, 78, 167)', 'rgb(166, 77, 121)'],
		['rgb(153, 0, 0)', 'rgb(180, 95, 6)', 'rgb(191, 144, 0)', 'rgb(56, 118, 29)', 
				'rgb(19, 79, 92)', 'rgb(11, 83, 148)', 'rgb(53, 28, 117)', 'rgb(116, 27, 71)'],
		['rgb(102, 0, 0)', 'rgb(120, 63, 4)', 'rgb(127, 96, 0)', 'rgb(39, 78, 19)', 
				'rgb(12, 52, 61)', 'rgb(7, 55, 99)', 'rgb(32, 18, 77)', 'rgb(76, 17, 48)']
	];
	
	this.caption = sCaption;
	this.pickHandler = fPickHandler;
	this.pickContext = oPickContext;
	
	this.colorPickerDom = ko.observable(null);
}

CColorPickerViewModel.prototype.onShow = function ()
{
	$(this.colorPickerDom()).find('span.color-item').on('click', _.bind(function (oEv)
	{
		oEv.stopPropagation();
		this.setColorFromPopup($(oEv.target).data('color'));
	}, this));
};

/**
 * @param {string} sColor
 */
CColorPickerViewModel.prototype.setColorFromPopup = function (sColor)
{
	this.pickHandler.call(this.pickContext, sColor);
};
/**
 * @constructor
 */
function CPhoneViewModel()
{
	this.phone = App.Phone;
	this.action = this.phone.action;
	this.report = this.phone.report;

	this.logs = ko.observableArray([]);
	this.logsToShow = ko.observableArray([]);
	this.spinner = ko.observable(true);
	this.tooltip = ko.observable(Utils.i18n('PHONE/NOT_CONNECTED'));
	this.indicator = ko.observable(Utils.i18n('PHONE/MISSED_CALLS'));
	this.dropdownShow = ko.observable(false);
	this.input = ko.observable('');
	this.input.subscribe(function(sInput) {
		this.dropdownShow(sInput === '' && this.action() === Enums.PhoneAction.OnlineActive);
	}, this);
	this.inputFocus = ko.observable(false);
	this.inputFocus.subscribe(function(bFocus) {
		if(bFocus && this.input() === '' && this.action() === Enums.PhoneAction.OnlineActive)
		{
			this.dropdownShow(true);
		}
	}, this);
	this.phoneAutocompleteItem = ko.observable(null);

	this.action.subscribe(function(sAction) {
		switch (sAction)
		{
			case Enums.PhoneAction.Offline:
				this.tooltip(Utils.i18n('PHONE/NOT_CONNECTED'));
				break;
			case Enums.PhoneAction.OfflineError:
				this.tooltip(Utils.i18n('Connection error'));
				break;
			case Enums.PhoneAction.OfflineInit:
				this.tooltip(Utils.i18n('PHONE/CONNECTING'));
				break;
			case Enums.PhoneAction.OfflineActive:
				break;
			case Enums.PhoneAction.Online:
				this.tooltip(Utils.i18n('PHONE/CONNECTED'));
				this.input('');
				this.report('');
				this.timer('stop');
				break;
			case Enums.PhoneAction.OnlineActive:
				break;
			case Enums.PhoneAction.Outgoing:
				this.timer('start');
				break;
			case Enums.PhoneAction.OutgoingConnect:
				this.tooltip(Utils.i18n('In Call'));
				break;
			case Enums.PhoneAction.Incoming:
				break;
			case Enums.PhoneAction.IncomingConnect:
				this.tooltip(Utils.i18n('In Call'));
				this.report('');
				this.timer('start');
				break;
		}
	}, this);

	$(document).on('click', _.bind(function (e) {
		if ($(e.target).closest('.item.phone, .ui-autocomplete').length === 0) {
			if (this.action() === Enums.PhoneAction.OnlineActive) {
				this.action(Enums.PhoneAction.Online);
				this.dropdownShow(false);
			}
		}
	}, this));
}

CPhoneViewModel.prototype.answer = function ()
{
	this.action(Enums.PhoneAction.IncomingConnect);
};

CPhoneViewModel.prototype.multiAction = function ()
{
	var sAction = this.action();
	/*if (sAction === Enums.PhoneAction.Offline)
	 {
	 //this.action(Enums.PhoneAction.OfflineActive);
	 }
	 else */
	if (sAction === Enums.PhoneAction.OfflineActive)
	{
		this.action(Enums.PhoneAction.Offline);
	}
	else if (sAction === Enums.PhoneAction.Online)
	{
		this.action(Enums.PhoneAction.OnlineActive);
		this.getLogs();
		_.delay(_.bind(function(){
			this.inputFocus(true);
		},this), 500);
	}
	else if (sAction === Enums.PhoneAction.OnlineActive && this.validateNumber())
	{
		if (this.phone)
		{
			this.phone.phoneToCall(this.input());
			this.action(Enums.PhoneAction.Outgoing);
		}

		this.inputFocus(false);
	}
	else if (sAction === Enums.PhoneAction.OnlineActive && !this.validateNumber()) //online action performed through the loss of focus
	{
		this.action(Enums.PhoneAction.Online);
		this.dropdownShow(false);
	}
	else if (
		sAction === Enums.PhoneAction.Outgoing  ||
		sAction === Enums.PhoneAction.Incoming ||
		sAction === Enums.PhoneAction.OutgoingConnect ||
		sAction === Enums.PhoneAction.IncomingConnect
	)
	{
		this.action(Enums.PhoneAction.Online);
		this.dropdownShow(false);
	}
};

CPhoneViewModel.prototype.autocompleteCallback = function (sTerm, fResponse)
{
	var oParameters = {
			'Action': 'ContactSuggestions',
			'Search': sTerm,
			'PhoneOnly': '1'
		}
	;

	this.phoneAutocompleteItem(null);

	sTerm = Utils.trim(sTerm);
	if ('' !== sTerm)
	{
		App.Ajax.send(oParameters, function (oData) {
			var aList = []
			//sCategory = ''
				;

			if (oData && oData.Result && oData.Result.List)
			{
				_.each(oData.Result.List, function (oItem) {
					//sCategory = oItem.Name === '' ? oItem.Email : oItem.Name + ' ' + oItem.Email;
					_.each(oItem.Phones, function (sPhone, sKey) {
						aList.push({
							label: oItem.Name !== '' ? oItem.Name + ' ' + '<' + oItem.Email + '> ' + sPhone : oItem.Email + ' ' + sPhone,
							value: sPhone,
							frequency: oItem.Frequency
							//category: sCategory
						});
					}, this);
				}, this);

				aList = _.sortBy(_.compact(aList), function(num){ return -(num.frequency); });
			}
			fResponse(aList);

		}, this);
	}
};

CPhoneViewModel.prototype.validateNumber = function ()
{
	return (/^[^a-zA-Z\u00BF-\u1FFF\u2C00-\uD7FF]+$/g).test(this.input()); //Check for letters absence
};

CPhoneViewModel.prototype.onLogItem = function (oItem)
{
	this.input(oItem.phoneToCall);
	this.dropdownShow(false);
};

CPhoneViewModel.prototype.getLogs = function ()
{
	this.spinner(true);
	this.logs([]);
	this.logsToShow([]);

	this.phone.getLogs(this.onLogsResponse, this);
};

CPhoneViewModel.prototype.onLogsResponse = function (oResponse, oRequest)
{
	if (oResponse && oResponse.Result) {
		this.logs([]);

		/*_.each(oResponse.Result, function (oStatus) {
			_.each(oStatus, function (oDirection) {
				_.each(oDirection, function (oItem) {
					oItem.phoneToShow = this.phone.getCleanedPhone(oItem.UserDirection === 'incoming' ? oItem.From : oItem.To);
					if (oItem.phoneToShow) {
						this.logs.push(oItem);
					}
				}, this);
			}, this);
		}, this);*/
		_.each(oResponse.Result, function (oItem) {

			oItem.phoneToCall = this.phone.getCleanedPhone(oItem.UserDirection === 'incoming' ? oItem.From : oItem.To);
			if (oItem.UserDisplayName)
			{
				oItem.phoneToShow = oItem.UserDisplayName;
			}
			else
			{
				oItem.phoneToShow = oItem.phoneToCall;
			}

			if (oItem.phoneToShow) {
				this.logs.push(oItem);
			}
		}, this);

		this.logs(_.sortBy(this.logs(), function(oItem){ return -(Date.parse(oItem.StartTime)); }).slice(0, 100));

		this.seeMore();
	}

	this.spinner(false);
};

CPhoneViewModel.prototype.seeMore = function ()
{
	this.logsToShow(this.logs().slice(0, this.logsToShow().length + 10));
};

CPhoneViewModel.prototype.timer = (function ()
{
	var
		iIntervalId = 0,
		iSeconds = 0,
		iMinutes = 0,
		fAddNull = function (iItem) {
			var sItem = iItem.toString();
			return sItem.length === 1 ? sItem = '0' + sItem : sItem;
		};

	return function (sAction)
	{
		if (sAction === 'start')
		{
			iSeconds = 0;
			iMinutes = 0;
			iIntervalId = setInterval(_.bind(function() {
				if(iSeconds === 60)
				{
					iSeconds = 0;
					iMinutes++;
				}
				this.report(Utils.i18n('PHONE/PASSED_TIME') + ' ' + fAddNull(iMinutes) + ':' + fAddNull(iSeconds));
				iSeconds++;
			}, this), 1000);
		}
		else if (sAction === 'stop')
		{
			clearInterval(iIntervalId);
		}
	};
}());

/**
 * @constructor
 */
function CWrapLoginViewModel()
{
	this.rtl = ko.observable(Utils.isRTL());
	
	this.allowRegistration = AppData.App.AllowRegistration;
	this.allowPasswordReset = AppData.App.AllowPasswordReset;
	
	this.oLoginViewModel = new CLoginViewModel();
	if (this.allowRegistration)
	{
		this.oRegisterViewModel = new CRegisterViewModel();
	}
	if (this.allowPasswordReset)
	{
		this.oForgotViewModel = new CForgotViewModel();
	}
	this.gotoForgot = this.allowPasswordReset ? this.oForgotViewModel.gotoForgot : ko.observable(false);
	this.gotoRegister = ko.observable(false);

	this.emailVisible = this.oLoginViewModel.emailVisible;
	this.loginVisible = this.oLoginViewModel.loginVisible;
	this.loginDescription = ko.observable(AppData.App.LoginDescription || '');

	this.aLanguages = AppData.App.Languages;
	this.currentLanguage = ko.observable(AppData.App.DefaultLanguage);
	
	this.allowLanguages = ko.observable(AppData.App.AllowLanguageOnLogin);
	this.viewLanguagesAsDropdown = ko.observable(!AppData.App.FlagsLangSelect);

	this.loginCustomLogo = ko.observable(AppData['LoginStyleImage'] || '');
	
	if (AfterLogicApi.runPluginHook)
	{
		AfterLogicApi.runPluginHook('view-model-defined', [this.__name, this]);
	}
}

CWrapLoginViewModel.prototype.__name = 'CWrapLoginViewModel';

CWrapLoginViewModel.prototype.onShow = function ()
{
	if (this.oLoginViewModel.onShow)
	{
		this.oLoginViewModel.onShow();
	}
};

CWrapLoginViewModel.prototype.onApplyBindings = function ()
{
	if (this.oLoginViewModel.onApplyBindings)
	{
		this.oLoginViewModel.onApplyBindings();
	}
};

/**
 * @param {string} sLanguage
 */
CWrapLoginViewModel.prototype.changeLanguage = function (sLanguage)
{
	if (sLanguage && this.allowLanguages())
	{
		this.currentLanguage(sLanguage);
		this.oLoginViewModel.changingLanguage(true);

		App.Ajax.send({
			'Action': 'SystemUpdateLanguageOnLogin',
			'Language': sLanguage
		}, function () {
			window.location.reload();
		}, this);
	}
};


/**
 * @constructor
 */
function CLoginViewModel()
{
	this.allowRegistration = AppData.App.AllowRegistration;
	this.allowPasswordReset = AppData.App.AllowPasswordReset;

	this.email = ko.observable('');
	this.login = ko.observable('');
	this.password = ko.observable('');
	
	this.emailFocus = ko.observable(false);
	this.loginFocus = ko.observable(false);
	this.passwordFocus = ko.observable(false);

	this.loading = ko.observable(false);
	this.changingLanguage = ko.observable(false);

	this.loginFocus.subscribe(function (bFocus) {
		if (bFocus && '' === this.login()) {
			this.login(this.email());
		}
	}, this);

	this.loginFormType = ko.observable(AppData.App.LoginFormType);
	this.loginAtDomainValue = ko.observable(AppData.App.LoginAtDomainValue);
	this.loginAtDomainValueWithAt = ko.computed(function () {
		var sV = this.loginAtDomainValue();
		return '' === sV ? '' : '@' + sV;
	}, this);

	this.emailVisible = ko.computed(function () {
		return Enums.LoginFormType.Login !== this.loginFormType();
	}, this);
	
	this.loginVisible = ko.computed(function () {
		return Enums.LoginFormType.Email !== this.loginFormType();
	}, this);

	this.signMeType = ko.observable(AppData.App.LoginSignMeType);
	
	this.signMe = ko.observable(Enums.LoginSignMeType.DefaultOn === this.signMeType());
	this.signMeType.subscribe(function () {
		this.signMe(Enums.LoginSignMeType.DefaultOn === this.signMeType());
	}, this);
	this.signMeFocused = ko.observable(false);

	this.emailDom = ko.observable(null);
	this.loginDom = ko.observable(null);
	this.passwordDom = ko.observable(null);

	this.focusedField = '';

	this.canBeLogin = ko.computed(function () {
		return !this.loading() && !this.changingLanguage();
	}, this);

	this.signInButtonText = ko.computed(function () {
		return this.loading() ? Utils.i18n('LOGIN/BUTTON_SIGNING_IN') : Utils.i18n('LOGIN/BUTTON_SIGN_IN');
	}, this);

	this.loginCommand = Utils.createCommand(this, this.signIn, this.canBeLogin);

	this.email(AppData.App.DemoWebMailLogin || '');
	this.password(AppData.App.DemoWebMailPassword || '');
	
	if (AfterLogicApi.runPluginHook)
	{
		AfterLogicApi.runPluginHook('view-model-defined', [this.__name, this]);
	}

	this.shake = ko.observable(false).extend({'autoResetToFalse': 800});
}

CLoginViewModel.prototype.__name = 'CLoginViewModel';

CLoginViewModel.prototype.onApplyBindings = function ()
{
	$html.addClass('non-adjustable-valign');
};

CLoginViewModel.prototype.onShow = function ()
{
	this.fillFields();
};

CLoginViewModel.prototype.fillFields = function ()
{
	_.delay(_.bind(function(){
		this.focusFields();
	},this), 1);
};

CLoginViewModel.prototype.focusFields = function ()
{
	if (this.emailVisible() && this.email() === '')
	{
		this.emailFocus(true);
	}
	else if (this.loginVisible() && this.login() === '')
	{
		this.loginFocus(true);
	}
};

CLoginViewModel.prototype.signIn = function ()
{
	$('.check_autocomplete_input').trigger('input').trigger('change').trigger('keydown');

	var
		iLoginType = this.loginFormType(),
		sEmail = this.email(),
		sLogin = this.login(),
		sPassword = this.password()
	;

	if (!this.loading() && !this.changingLanguage() && '' !== Utils.trim(sPassword) && (
		(Enums.LoginFormType.Login === iLoginType && '' !== Utils.trim(sLogin)) ||
		(Enums.LoginFormType.Email === iLoginType && '' !== Utils.trim(sEmail)) ||
		(Enums.LoginFormType.Both === iLoginType && '' !== Utils.trim(sEmail))
	))
	{
		this.sendRequest();
	}
	else
	{
		this.shake(true);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CLoginViewModel.prototype.onSystemLoginResponse = function (oResponse, oRequest)
{
	if (false === oResponse.Result)
	{
		this.loading(false);
		this.shake(true);
		
		App.Api.showErrorByCode(oResponse, Utils.i18n('WARNING/LOGIN_PASS_INCORRECT'));
	}
	else
	{
		window.location.reload();
	}
};

CLoginViewModel.prototype.sendRequest = function ()
{
	var
		oParameters = {
			'Action': 'SystemLogin',
			'Email': this.emailVisible() ? this.email() : '',
			'IncLogin': this.loginVisible() ? this.login() : '',
			'IncPassword': this.password(),
			'SignMe': this.signMe() ? '1' : '0'
		}
	;

	this.loading(true);
	App.Ajax.send(oParameters, this.onSystemLoginResponse, this);
};

/**
 * @constructor
 */
function CForgotViewModel()
{
	this.gotoForgot = ko.observable(false);
	this.gotoForgot.subscribe(function () {
		this.visibleEmailForm(true);
		this.visibleQuestionForm(false);
		this.visiblePasswordForm(false);
	}, this);
	
	this.visibleEmailForm = ko.observable(true);
	this.email = ko.observable('');
	this.emailFocus = ko.observable(false);
	this.gettingQuestion = ko.observable(false);
	this.getQuestionButtonText = ko.computed(function () {
		return this.gettingQuestion() ? Utils.i18n('LOGIN/BUTTON_GETTING_QUESTION') : Utils.i18n('LOGIN/BUTTON_GET_QUESTION');
	}, this);
	this.allowGetQuestion = ko.computed(function () {
		return !this.gettingQuestion() && Utils.trim(this.email()) !== '';
	}, this);
	this.getQuestionCommand = Utils.createCommand(this, this.executeGetQuestion, this.allowGetQuestion);
	
	this.visibleQuestionForm = ko.observable(false);
	this.question = ko.observable('');
	this.answer = ko.observable('');
	this.answerFocus = ko.observable(false);
	this.validatingAnswer = ko.observable(false);
	this.validateAnswerButtonText = ko.computed(function () {
		return this.validatingAnswer() ? Utils.i18n('LOGIN/BUTTON_VALIDATING_ANSWER') : Utils.i18n('LOGIN/BUTTON_VALIDATE_ANSWER');
	}, this);
	this.allowValidatingAnswer = ko.computed(function () {
		return !this.validatingAnswer() && Utils.trim(this.answer()) !== '';
	}, this);
	this.validateAnswerCommand = Utils.createCommand(this, this.executeValidateAnswer, this.allowValidatingAnswer);
	
	this.visiblePasswordForm = ko.observable(false);
	this.password = ko.observable('');
	this.confirmPassword = ko.observable('');
	this.passwordFocus = ko.observable(false);
	this.confirmPasswordFocus = ko.observable(false);
	this.changingPassword = ko.observable(false);
	this.changePasswordButtonText = ko.computed(function () {
		return this.changingPassword() ? Utils.i18n('LOGIN/BUTTON_RESETTING_PASSWORD') : Utils.i18n('LOGIN/BUTTON_RESET_PASSWORD');
	}, this);
	this.allowChangePassword = ko.computed(function () {
		var
			sPassword = Utils.trim(this.password()),
			sConfirmPassword = Utils.trim(this.confirmPassword()),
			bEmptyFields = (sPassword === '' || sConfirmPassword === '')
		;

		return !this.changingPassword() && !bEmptyFields;
	}, this);
	this.changePasswordCommand = Utils.createCommand(this, this.executeChangePassword, this.allowChangePassword);
	
	if (AfterLogicApi.runPluginHook)
	{
		AfterLogicApi.runPluginHook('view-model-defined', [this.__name, this]);
	}
}

CForgotViewModel.prototype.__name = 'CForgotViewModel';

CForgotViewModel.prototype.executeGetQuestion = function ()
{
	var
		oParameters = {
			'Action': 'AccountGetForgotQuestion',
			'Email': this.email()
		}
	;

	this.gettingQuestion(true);

	App.Ajax.send(oParameters, this.onAccountGetForgotQuestionResponse, this);
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CForgotViewModel.prototype.onAccountGetForgotQuestionResponse = function (oResponse, oRequest)
{
	var sQuestion = '';
	
	this.gettingQuestion(false);
	
	if (false === oResponse.Result)
	{
		App.Api.showErrorByCode(oResponse, Utils.i18n('LOGIN/ERROR_GETTING_QUESTION'));
	}
	else
	{
		sQuestion = Utils.pString(oResponse.Result.Question);
		
		if (sQuestion === '')
		{
			App.Api.showError(Utils.i18n('LOGIN/ERROR_PASSWORD_RESET_NOT_AVAILABLE'));
		}
		else
		{
			this.question(sQuestion);
			this.visibleEmailForm(false);
			this.visibleQuestionForm(true);
			this.visiblePasswordForm(false);
		}
	}
};

CForgotViewModel.prototype.executeValidateAnswer = function ()
{
	var
		oParameters = {
			'Action': 'AccountValidateForgotQuestion',
			'Email': this.email(),
			'Question': this.question(),
			'Answer': this.answer()
		}
	;

	this.validatingAnswer(true);

	App.Ajax.send(oParameters, this.onAccountValidateForgotQuestionResponse, this);
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CForgotViewModel.prototype.onAccountValidateForgotQuestionResponse = function (oResponse, oRequest)
{
	this.validatingAnswer(false);
	
	if (false === oResponse.Result)
	{
		App.Api.showErrorByCode(oResponse, Utils.i18n('LOGIN/ERROR_WRONG_ANSWER'));
	}
	else
	{
		this.visibleEmailForm(false);
		this.visibleQuestionForm(false);
		this.visiblePasswordForm(true);
	}
};

CForgotViewModel.prototype.executeChangePassword = function ()
{
	if (this.password() !== this.confirmPassword())
	{
		App.Api.showError(Utils.i18n('WARNING/PASSWORDS_DO_NOT_MATCH'));
	}
	else
	{
		var
			oParameters = {
				'Action': 'AccountChangeForgotPassword',
				'Email': this.email(),
				'Question': this.question(),
				'Answer': this.answer(),
				'Password': this.password()
			}
		;

		this.changingPassword(true);
		
		App.Ajax.send(oParameters, this.onAccountChangeForgotPasswordResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CForgotViewModel.prototype.onAccountChangeForgotPasswordResponse = function (oResponse, oRequest)
{
	this.changingPassword(false);
	
	if (false === oResponse.Result)
	{
		App.Api.showErrorByCode(oResponse, Utils.i18n('LOGIN/ERROR_RESETTING_PASSWORD'));
	}
	else
	{
		this.gotoForgot(false);
		App.Api.showReport(Utils.i18n('LOGIN/REPORT_PASSWORD_CHANGED'));
	}
};


/**
 * @constructor
 */
function CRegisterViewModel()
{
	this.name = ko.observable('');
	this.login = ko.observable('');
	this.password = ko.observable('');
	this.confirmPassword = ko.observable('');
	this.question = ko.observable('');
	this.yourQuestion = ko.observable('');
	this.answer = ko.observable('');
	this.allowQuestionPart = Utils.isNonEmptyArray(AppData.App.RegistrationQuestions);
	this.visibleYourQuestion = ko.computed(function () {
		return (this.question() === Utils.i18n('LOGIN/OPTION_YOUR_QUESTION'));
	}, this);
	
	this.nameFocus = ko.observable(false);
	this.loginFocus = ko.observable(false);
	this.passwordFocus = ko.observable(false);
	this.confirmPasswordFocus = ko.observable(false);
	this.questionFocus = ko.observable(false);
	this.answerFocus = ko.observable(false);
	this.yourQuestionFocus = ko.observable(false);
	
	this.domains = ko.observable(Utils.isNonEmptyArray(AppData.App.RegistrationDomains) ? AppData.App.RegistrationDomains : []);
	this.domain = ko.computed(function () {
		return (this.domains().length === 1) ? this.domains()[0] : '';
	}, this);
	this.selectedDomain = ko.observable((this.domains().length > 0) ? this.domains()[0] : '');
	
	this.registrationQuestions = [];
	if (this.allowQuestionPart)
	{
		this.registrationQuestions = _.map(_.union('', _.without(AppData.App.RegistrationQuestions, '*')), function (sQuestion) {
			return {text: (sQuestion !== '') ? sQuestion : Utils.i18n('LOGIN/LABEL_SELECT_QUESTION'), value: sQuestion};
		});
		if (_.indexOf(AppData.App.RegistrationQuestions, '*') !== -1)
		{
			this.registrationQuestions.push({text: Utils.i18n('LOGIN/OPTION_YOUR_QUESTION'), value: Utils.i18n('LOGIN/OPTION_YOUR_QUESTION')});
		}
	}
	
	this.loading = ko.observable(false);
	
	this.canBeRegister = ko.computed(function () {
		var
			sLogin = Utils.trim(this.login()),
			sPassword = Utils.trim(this.password()),
			sConfirmPassword = Utils.trim(this.confirmPassword()),
			sQuestion = Utils.trim(this.visibleYourQuestion() ? this.yourQuestion() : this.question()),
			sAnswer = Utils.trim(this.answer()),
			bEmptyFields = (sLogin === '' || sPassword === '' || sConfirmPassword === '' || 
					this.allowQuestionPart && (sQuestion === '' || sAnswer === ''))
		;

		return !this.loading() && !bEmptyFields;
	}, this);

	this.registerButtonText = ko.computed(function () {
		return this.loading() ? Utils.i18n('LOGIN/BUTTON_REGISTERING') : Utils.i18n('LOGIN/BUTTON_REGISTER');
	}, this);
	
	this.registerCommand = Utils.createCommand(this, this.registerAccount, this.canBeRegister);
	
	if (AfterLogicApi.runPluginHook)
	{
		AfterLogicApi.runPluginHook('view-model-defined', [this.__name, this]);
	}
}

CRegisterViewModel.prototype.__name = 'CRegisterViewModel';

CRegisterViewModel.prototype.registerAccount = function ()
{
	if (this.password() !== this.confirmPassword())
	{
		App.Api.showError(Utils.i18n('WARNING/PASSWORDS_DO_NOT_MATCH'));
	}
	else
	{
		var
			oParameters = {
				'Action': 'AccountRegister',
				'Name': this.name(),
				'Email': this.login() + '@' + this.selectedDomain(),
				'Password': this.password(),
				'Question': this.allowQuestionPart ? (this.visibleYourQuestion() ? this.yourQuestion() : this.question()) : '',
				'Answer': this.allowQuestionPart ? this.answer() : ''
			}
		;

		this.loading(true);
		
		App.Ajax.send(oParameters, this.onAccountRegisterResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CRegisterViewModel.prototype.onAccountRegisterResponse = function (oResponse, oRequest)
{
	if (false === oResponse.Result)
	{
		this.loading(false);
		
		App.Api.showErrorByCode(oResponse, Utils.i18n('WARNING/LOGIN_PASS_INCORRECT'));
	}
	else
	{
		window.location.reload();
	}
};


/**
 * @constructor
 */
function CFolderListViewModel()
{
	this.accounts = AppData.Accounts.collection;
	
	this.mobileApp = bMobileApp;
	
	this.folderList = App.MailCache.folderList;
	
	this.manageFoldersHash = App.Routing.buildHashFromArray([Enums.Screens.Settings, 
		Enums.SettingsTab.EmailAccounts, 
		Enums.AccountSettingsTab.Folders]);

	this.quotaProc = ko.observable(-1);
	this.quotaDesc = ko.observable('');

	ko.computed(function () {

		if (!AppData.App || AppData.App && !AppData.App.ShowQuotaBar)
		{
			return true;
		}

		App.MailCache.quotaChangeTrigger();

		var
			oAccount = AppData.Accounts.getCurrent(),
			iQuota = oAccount ? oAccount.quota() : 0,
			iUsed = oAccount ? oAccount.usedSpace() : 0,
			iProc = 0 < iQuota ? Math.ceil((iUsed / iQuota) * 100) : -1
		;

		iProc = 100 < iProc ? 100 : iProc;
		
		this.quotaProc(iProc);
		this.quotaDesc(-1 < iProc ?
			Utils.i18n('MAILBOX/QUOTA_TOOLTIP', {
				'PROC': iProc,
				'QUOTA': Utils.friendlySize(iQuota * 1024)
			}) : '');

		return true;
		
	}, this);
}


/**
 * @constructor
 * 
 * @param {Function} fOpenMessageInNewWindowBinded
 */
function CMessageListViewModel(fOpenMessageInNewWindowBinded)
{
	this.isPublic = bExtApp;

	this.uploaderArea = ko.observable(null);
	this.bDragActive = ko.observable(false);
	this.bDragActiveComp = ko.computed(function () {
		return this.bDragActive();
	}, this);

	this.openMessageInNewWindowBinded = fOpenMessageInNewWindowBinded;
	
	this.isFocused = ko.observable(false);

	this.messagesContainer = ko.observable(null);

	this.searchInput = ko.observable('');
	this.searchInputFrom = ko.observable('');
	this.searchInputTo = ko.observable('');
	this.searchInputSubject = ko.observable('');
	this.searchInputText = ko.observable('');
	this.searchSpan = ko.observable('');
	this.highlightTrigger = ko.observable('');

	this.currentMessage = App.MailCache.currentMessage;
	this.currentMessage.subscribe(function () {
		this.isFocused(false);
		this.selector.itemSelected(this.currentMessage());
	}, this);

	this.folderList = App.MailCache.folderList;
	this.folderList.subscribe(this.onFolderListSubscribe, this);
	this.folderFullName = ko.observable('');
	this.folderType = ko.observable(Enums.FolderTypes.User);
	this.filters = ko.observable('');

	this.uidList = App.MailCache.uidList;
	this.uidList.subscribe(function () {
		if (this.uidList().searchCountSubscription)
		{
			this.uidList().searchCountSubscription.dispose();
			this.uidList().searchCountSubscription = undefined;
		}
		this.uidList().searchCountSubscription = this.uidList().resultCount.subscribe(function () {
			if (this.uidList().resultCount() >= 0)
			{
				this.oPageSwitcher.setCount(this.uidList().resultCount());
			}
		}, this);
		
		if (this.uidList().resultCount() >= 0)
		{
			this.oPageSwitcher.setCount(this.uidList().resultCount());
		}
	}, this);

	this.useThreads = ko.computed(function () {
		var
			oFolder = this.folderList().currentFolder(),
			bFolderWithoutThreads = oFolder && oFolder.withoutThreads(),
			bNotSearchOrFilters = this.uidList().search() === '' && this.uidList().filters() === ''
		;
		
		return AppData.User.useThreads() && !bFolderWithoutThreads && bNotSearchOrFilters;
	}, this);

	this.collection = App.MailCache.messages;
	
	this._search = ko.observable('');
	this.search = ko.computed({
		'read': function () {
			return Utils.trim(this._search());
		},
		'write': this._search,
		'owner': this
	});

	this.isEmptyList = ko.computed(function () {
		return this.collection().length === 0;
	}, this);

	this.isNotEmptyList = ko.computed(function () {
		return this.collection().length !== 0;
	}, this);

	this.isSearch = ko.computed(function () {
		return this.search().length > 0;
	}, this);

	this.isUnseenFilter = ko.computed(function () {
		return this.filters() === Enums.FolderFilter.Unseen;
	}, this);

	this.isLoading = App.MailCache.messagesLoading;

	this.isError = App.MailCache.messagesLoadingError;

	this.visibleInfoLoading = ko.computed(function () {
		return !this.isSearch() && this.isLoading();
	}, this);
	this.visibleInfoSearchLoading = ko.computed(function () {
		return this.isSearch() && this.isLoading();
	}, this);
	this.visibleInfoSearchList = ko.computed(function () {
		return this.isSearch() && !this.isUnseenFilter() && !this.isLoading() && !this.isEmptyList();
	}, this);
	this.visibleInfoMessageListEmpty = ko.computed(function () {
		return !this.isLoading() && !this.isSearch() && (this.filters() === '') && this.isEmptyList() && !this.isError();
	}, this);
	this.visibleInfoStarredFolderEmpty = ko.computed(function () {
		return !this.isLoading() && !this.isSearch() && (this.filters() === Enums.FolderFilter.Flagged) && this.isEmptyList() && !this.isError();
	}, this);
	this.visibleInfoSearchEmpty = ko.computed(function () {
		return this.isSearch() && !this.isUnseenFilter() && this.isEmptyList() && !this.isError() && !this.isLoading();
	}, this);
	this.visibleInfoMessageListError = ko.computed(function () {
		return !this.isSearch() && this.isError();
	}, this);
	this.visibleInfoSearchError = ko.computed(function () {
		return this.isSearch() && this.isError();
	}, this);
	this.visibleInfoUnseenFilterList = ko.computed(function () {
		return this.isUnseenFilter() && (this.isLoading() || !this.isEmptyList());
	}, this);
	this.visibleInfoUnseenFilterEmpty = ko.computed(function () {
		return this.isUnseenFilter() && this.isEmptyList() && !this.isError() && !this.isLoading();
	}, this);

	this.searchText = ko.computed(function () {

		return Utils.i18n('MAILBOX/INFO_SEARCH_RESULT', {
			'SEARCH': this.calculateSearchStringForDescription(),
			'FOLDER': this.folderList().currentFolder() ? this.folderList().currentFolder().displayName() : ''
		});
		
	}, this);

	this.unseenFilterText = ko.computed(function () {

		if (this.search() === '')
		{
			return Utils.i18n('MAILBOX/INFO_UNSEEN_FILTER_RESULT', {
				'FOLDER': this.folderList().currentFolder() ? this.folderList().currentFolder().displayName() : ''
			});
		}
		else
		{
			return Utils.i18n('MAILBOX/INFO_SEARCH_UNSEEN_FILTER_RESULT', {
				'SEARCH': this.calculateSearchStringForDescription(),
				'FOLDER': this.folderList().currentFolder() ? this.folderList().currentFolder().displayName() : ''
			});
		}
		
	}, this);

	this.unseenFilterEmptyText = ko.computed(function () {

		if (this.search() === '')
		{
			return Utils.i18n('MAILBOX/INFO_UNSEEN_FILTER_EMPTY');
		}
		else
		{
			return Utils.i18n('MAILBOX/INFO_SEARCH_UNSEEN_FILTER_EMPTY');
		}
		
	}, this);

	this.isEnableGroupOperations = ko.observable(false).extend({'throttle': 250});

	this.selector = new CSelector(
		this.collection,
		_.bind(this.routeForMessage, this),
		_.bind(this.onDeletePress, this),
		_.bind(this.onMessageDblClick, this),
		_.bind(this.onEnterPress, this)
	);

	this.checkedUids = ko.computed(function () {
		var
			aChecked = this.selector.listChecked(),
			aCheckedUids = _.map(aChecked, function (oItem) {
				return oItem.uid();
			}),
			oFolder = App.MailCache.folderList().currentFolder(),
			aThreadCheckedUids = oFolder ? oFolder.getThreadCheckedUidsFromList(aChecked) : [],
			aUids = _.union(aCheckedUids, aThreadCheckedUids)
		;

		return aUids;
	}, this);
	
	this.checkedOrSelectedUids = ko.computed(function () {
		var aChecked = this.checkedUids();
		if (aChecked.length === 0 && App.MailCache.currentMessage() && !App.MailCache.currentMessage().deleted())
		{
			aChecked = [App.MailCache.currentMessage().uid()];
		}
		return aChecked;
	}, this);

	ko.computed(function () {
		this.isEnableGroupOperations(0 < this.selector.listCheckedOrSelected().length);
	}, this);

	this.checkAll = this.selector.koCheckAll();
	this.checkAllIncomplite = this.selector.koCheckAllIncomplete();

	this.pageSwitcherLocked = ko.observable(false);
	this.oPageSwitcher = new CPageSwitcherViewModel(0, AppData.User.MailsPerPage);
	this.oPageSwitcher.currentPage.subscribe(function (iPage) {
		var
			sFolder = this.folderList().currentFolderFullName(),
			sUid = !bMobileApp && this.currentMessage() ? this.currentMessage().uid() : '',
			sSearch = this.search()
		;
		
		if (!this.pageSwitcherLocked())
		{
			this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch, this.filters());
		}
	}, this);
	this.currentPage = ko.observable(0);
	
	// to the message list does not twitch
	if (App.browser.firefox || App.browser.ie)
	{
		this.listChangedThrottle = ko.observable(false).extend({'throttle': 10});
	}
	else
	{
		this.listChangedThrottle = ko.observable(false);
	}
	
	this.firstCompleteCollection = ko.observable(true);
	this.collection.subscribe(function () {
		if (this.collection().length > 0)
		{
			this.firstCompleteCollection(false);
		}
	}, this);
	this.currentAccountId = AppData.Accounts.currentId;
	this.listChanged = ko.computed(function () {
		return [
			this.firstCompleteCollection(),
			this.currentAccountId(),
			this.folderFullName(),
			this.filters(),
			this.search(),
			this.oPageSwitcher.currentPage()
		];
	}, this);
	
	this.listChanged.subscribe(function() {
		this.listChangedThrottle(!this.listChangedThrottle());
	}, this);

	this.bAdvancedSearch = ko.observable(false);
	this.searchAttachmentsCheckbox = ko.observable(false);
	this.searchAttachments = ko.observable('');
	this.searchAttachments.subscribe(function(sText) {
		this.searchAttachmentsCheckbox(!!sText);
	}, this);
	
	this.panelTopDom = ko.observable(null);
	this.extendedDom = ko.observable(null);
	this.searchAttachmentsFocus = ko.observable(false);
	this.searchFromFocus = ko.observable(false);
	this.searchSubjectFocus = ko.observable(false);
	this.searchToFocus = ko.observable(false);
	this.searchTextFocus = ko.observable(false);
	this.searchTrigger = ko.observable(null);
	this.searchDateStartFocus = ko.observable(false);
	this.searchDateEndFocus = ko.observable(false);
	this.searchDateStartDom = ko.observable(null);
	this.searchDateStart = ko.observable('');
	this.searchDateEndDom = ko.observable(null);
	this.searchDateEnd = ko.observable('');
	this.dateFormatDatePicker = 'yy.mm.dd';
	this.attachmentsPlaceholder = ko.computed(function () {
		return Utils.i18n('MAILBOX/SEARCH_FIELD_HAS_ATTACHMENTS');
	}, this);

	_.delay(_.bind(function(){
		this.createDatePickerObject(this.searchDateStartDom());
		this.createDatePickerObject(this.searchDateEndDom());
	}, this), 1000);
}

CMessageListViewModel.prototype.createDatePickerObject = function (oElement)
{
	$(oElement).datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		monthNames: Utils.getMonthNamesArray(),
		dayNamesMin: Utils.i18n('DATETIME/DAY_NAMES_MIN').split(' '),
		nextText: '',
		prevText: '',
		firstDay: AppData.User.CalendarWeekStartsOn,
		showOn: 'focus',
		dateFormat: this.dateFormatDatePicker
	});

	$(oElement).mousedown(function() {
		$('#ui-datepicker-div').toggle();
	});
};

/**
 * @param {string} sFolder
 * @param {number} iPage
 * @param {string} sUid
 * @param {string} sSearch
 * @param {string} sFilters
 */
CMessageListViewModel.prototype.changeRoutingForMessageList = function (sFolder, iPage, sUid, sSearch, sFilters)
{
	var bSame = App.Routing.setHash(App.Links.mailbox(sFolder, iPage, sUid, sSearch, sFilters));
	
	if (bSame && sSearch.length > 0 && this.search() === sSearch)
	{
		this.listChangedThrottle(!this.listChangedThrottle());
	}
};

/**
 * @param {CMessageModel} oMessage
 */
CMessageListViewModel.prototype.onEnterPress = function (oMessage)
{
	oMessage.openThread();
};

/**
 * @param {CMessageModel} oMessage
 */
CMessageListViewModel.prototype.onMessageDblClick = function (oMessage)
{
	if (!this.isSavingDraft(oMessage))
	{
		var
			oFolder = this.folderList().getFolderByFullName(oMessage.folder())
		;

		if (oFolder.type() === Enums.FolderTypes.Drafts)
		{
			App.Api.composeMessageFromDrafts(oMessage.folder(), oMessage.uid());
		}
		else
		{
			this.openMessageInNewWindowBinded(oMessage);
		}
	}
};

CMessageListViewModel.prototype.onFolderListSubscribe = function ()
{
	this.setCurrentFolder();
	this.requestMessageList();
};

/**
 * @param {Array} aParams
 */
CMessageListViewModel.prototype.onShow = function (aParams)
{
	this.selector.useKeyboardKeys(true);
	this.oPageSwitcher.show();

	if (this.oJua)
	{
		this.oJua.setDragAndDropEnabledStatus(true);
	}
};

/**
 * @param {Array} aParams
 */
CMessageListViewModel.prototype.onHide = function (aParams)
{
	this.selector.useKeyboardKeys(false);
	this.oPageSwitcher.hide();

	if (this.oJua)
	{
		this.oJua.setDragAndDropEnabledStatus(false);
	}
};

/**
 * @param {Array} aParams
 */
CMessageListViewModel.prototype.onRoute = function (aParams)
{
	var
		oParams = App.Links.parseMailbox(aParams),
		bRouteChanged = this.currentPage() !== oParams.Page ||
			this.folderFullName() !== oParams.Folder ||
			this.filters() !== oParams.Filters || (oParams.Filters === Enums.FolderFilter.Unseen && App.MailCache.waitForUnseenMessages()) ||
			this.search() !== oParams.Search,
		bMailsPerPageChanged = AppData.User.MailsPerPage !== this.oPageSwitcher.perPage()
	;
	
	this.pageSwitcherLocked(true);
	if (this.folderFullName() !== oParams.Folder || this.search() !== oParams.Search || this.filters() !== oParams.Filters)
	{
		this.oPageSwitcher.clear();
	}
	else
	{
		this.oPageSwitcher.setPage(oParams.Page, AppData.User.MailsPerPage);
	}
	this.pageSwitcherLocked(false);
	
	if (oParams.Page !== this.oPageSwitcher.currentPage())
	{
		App.Routing.replaceHash(App.Links.mailbox(oParams.Folder, this.oPageSwitcher.currentPage(), oParams.Uid, oParams.Search, oParams.Filters));
	}

	this.currentPage(this.oPageSwitcher.currentPage());
	this.folderFullName(oParams.Folder);
	this.filters(oParams.Filters);
	this.search(oParams.Search);
	this.searchInput(this.search());
	this.searchSpan.notifySubscribers();

	this.setCurrentFolder();
	
	if (bRouteChanged || bMailsPerPageChanged || this.collection().length === 0)
	{
		if (oParams.Filters === Enums.FolderFilter.Unseen)
		{
			App.MailCache.waitForUnseenMessages(true);
		}
		this.requestMessageList();
	}

	this.highlightTrigger.notifySubscribers(true);
};

CMessageListViewModel.prototype.setCurrentFolder = function ()
{
	this.folderList().setCurrentFolder(this.folderFullName(), this.filters());
	this.folderType(App.MailCache.folderList().currentFolderType());
};

CMessageListViewModel.prototype.requestMessageList = function ()
{
	var
		sFullName = this.folderList().currentFolderFullName(),
		iPage = this.oPageSwitcher.currentPage()
	;
	
	if (sFullName.length > 0)
	{
		App.MailCache.changeCurrentMessageList(sFullName, iPage, this.search(), this.filters());
	}
	else
	{
		App.MailCache.checkCurrentFolderList();
	}
};

CMessageListViewModel.prototype.calculateSearchStringFromAdvancedForm  = function ()
{
	var
		sFrom = this.searchInputFrom(),
		sTo = this.searchInputTo(),
		sSubject = this.searchInputSubject(),
		sText = this.searchInputText(),
		bAttachmentsCheckbox = this.searchAttachmentsCheckbox(),
		sAttachments = this.searchAttachments(),
		sDateStart = this.searchDateStart(),
		sDateEnd = this.searchDateEnd(),
		aOutput = [],
		fEsc = function (sText) {

			sText = $.trim(sText).replace(/"/g, '\\"');
			
			if (-1 < sText.indexOf(' ') || -1 < sText.indexOf('"'))
			{
				sText = '"' + sText + '"';
			}
			
			return sText;
		}
	;

	if (sFrom !== '')
	{
		aOutput.push('from:' + fEsc(sFrom));
	}

	if (sTo !== '')
	{
		aOutput.push('to:' + fEsc(sTo));
	}

	if (sSubject !== '')
	{
		aOutput.push('subject:' + fEsc(sSubject));
	}
	
	if (sText !== '')
	{
		aOutput.push('text:' + fEsc(sText));
	}

	if (bAttachmentsCheckbox)
	{
		aOutput.push('has:attachments');
	}

	/*if (sAttachments !== '')
	{
		aOutput.push('attachments:' + fEsc(sAttachments));
	}*/

	if (sDateStart !== '' || sDateEnd !== '')
	{
		aOutput.push('date:' + fEsc(sDateStart) + '/' + fEsc(sDateEnd));
	}

	return aOutput.join(' ');
};

CMessageListViewModel.prototype.onSearchClick = function ()
{
	var
		sFolder = this.folderList().currentFolderFullName(),
		//sUid = this.currentMessage() ? this.currentMessage().uid() : '',
		iPage = 1,
		sSearch = this.searchInput()
	;

	if (this.bAdvancedSearch())
	{
		sSearch = this.calculateSearchStringFromAdvancedForm();
		this.searchInput(sSearch);
		this.bAdvancedSearch(false);
	}
	this.changeRoutingForMessageList(sFolder, iPage, '', sSearch, this.filters());
	//this.highlightTrigger.notifySubscribers();
};

CMessageListViewModel.prototype.onRetryClick = function ()
{
	this.requestMessageList();
};

CMessageListViewModel.prototype.onClearSearchClick = function ()
{
	var
		sFolder = this.folderList().currentFolderFullName(),
		sUid = this.currentMessage() ? this.currentMessage().uid() : '',
		sSearch = '',
		iPage = 1
	;

	this.clearAdvancedSearch();
	this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch, this.filters());
};

CMessageListViewModel.prototype.onClearFilterClick = function ()
{
	var
		sFolder = this.folderList().currentFolderFullName(),
		sUid = this.currentMessage() ? this.currentMessage().uid() : '',
		sSearch = '',
		iPage = 1,
		sFilters = ''
	;

	this.clearAdvancedSearch();
	this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch, sFilters);
};

CMessageListViewModel.prototype.onStopSearchClick = function ()
{
	this.onClearSearchClick();
};

/**
 * @param {Object} oMessage
 */
CMessageListViewModel.prototype.isSavingDraft = function (oMessage)
{
	var oFolder = this.folderList().currentFolder();
	
	return (oFolder.type() === Enums.FolderTypes.Drafts) && (oMessage.uid() === App.MailCache.savingDraftUid());
};

/**
 * @param {Object} oMessage
 */
CMessageListViewModel.prototype.routeForMessage = function (oMessage)
{
	if (oMessage !== null && !this.isSavingDraft(oMessage))
	{
		var
			oFolder = this.folderList().currentFolder(),
			sFolder = this.folderList().currentFolderFullName(),
			iPage = this.oPageSwitcher.currentPage(),
			sUid = oMessage.uid(),
			sSearch = this.search()
		;
		
		if (sUid !== '')
		{
			if (bMobileApp && oFolder.type() === Enums.FolderTypes.Drafts)
			{
				App.Routing.setHash(App.Links.composeFromMessage('drafts', oMessage.folder(), oMessage.uid()));
			}
			else
			{
				this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch, this.filters());
				if (bMobileApp && App.MailCache.currentMessage() && sUid === App.MailCache.currentMessage().uid())
				{
					App.MailCache.currentMessage.valueHasMutated();
				}
			}
		}
	}
};

/**
 * @param {Object} $viewModel
 */
CMessageListViewModel.prototype.onApplyBindings = function ($viewModel)
{
	var
		self = this,
		fStopPopagation = _.bind(function (oEvent) {
			if (oEvent && oEvent.stopPropagation)
			{
				oEvent.stopPropagation();
			}
		}, this)
	;

	$('.message_list', $viewModel)
		.on('click', function ()
		{
			self.isFocused(false);
		})
		.on('click', '.message_sub_list .item .flag', function (oEvent)
		{
			self.onFlagClick(ko.dataFor(this));
			if (oEvent && oEvent.stopPropagation)
			{
				oEvent.stopPropagation();
			}
		})
		.on('dblclick', '.message_sub_list .item .flag', fStopPopagation)
		.on('click', '.message_sub_list .item .thread', fStopPopagation)
		.on('dblclick', '.message_sub_list .item .thread', fStopPopagation)
	;

	this.selector.initOnApplyBindings(
		'.message_sub_list .item',
		'.message_sub_list .item.selected',
		'.message_sub_list .item .custom_checkbox',
		$('.message_list', $viewModel),
		$('.message_list_scroll.scroll-inner', $viewModel)
	);

	this.initUploader();
};

/**
 * Puts / removes the message flag by clicking on it.
 *
 * @param {Object} oMessage
 */
CMessageListViewModel.prototype.onFlagClick = function (oMessage)
{
	if (!this.isSavingDraft(oMessage))
	{
		App.MailCache.executeGroupOperation('MessageSetFlagged', [oMessage.uid()], 'flagged', !oMessage.flagged());
	}
};

/**
 * Marks the selected messages read.
 */
CMessageListViewModel.prototype.executeMarkAsRead = function ()
{
	App.MailCache.executeGroupOperation('MessageSetSeen', this.checkedOrSelectedUids(), 'seen', true);
};

/**
 * Marks the selected messages unread.
 */
CMessageListViewModel.prototype.executeMarkAsUnread = function ()
{
	App.MailCache.executeGroupOperation('MessageSetSeen', this.checkedOrSelectedUids(), 'seen', false);
};

/**
 * Marks Read all messages in a folder.
 */
CMessageListViewModel.prototype.executeMarkAllRead = function ()
{
	App.MailCache.executeGroupOperation('MessagesSetAllSeen', [], 'seen', true);
};

/**
 * Moves the selected messages in the current folder in the specified.
 * 
 * @param {string} sToFolder
 */
CMessageListViewModel.prototype.executeMoveToFolder = function (sToFolder)
{
	App.MailCache.moveMessagesToFolder(sToFolder, this.checkedOrSelectedUids());
};

CMessageListViewModel.prototype.executeCopyToFolder = function (sToFolder)
{
	App.MailCache.copyMessagesToFolder(sToFolder, this.checkedOrSelectedUids());
};

/**
 * Calls for the selected messages delete operation. Called from the keyboard.
 * 
 * @param {Array} aMessages
 */
CMessageListViewModel.prototype.onDeletePress = function (aMessages)
{
	var aUids = _.map(aMessages, function (oMessage)
	{
		return oMessage.uid();
	});

	if (aUids.length > 0)
	{
		App.Api.deleteMessages(aUids, App);
	}
};

/**
 * Calls for the selected messages delete operation. Called by the mouse click on the delete button.
 */
CMessageListViewModel.prototype.executeDelete = function ()
{
	App.Api.deleteMessages(this.checkedOrSelectedUids(), App);
};

/**
 * Moves the selected messages from the current folder to the folder Spam.
 */
CMessageListViewModel.prototype.executeSpam = function ()
{
	var sSpamFullName = this.folderList().spamFolderFullName();

	if (this.folderList().currentFolderFullName() !== sSpamFullName)
	{
		App.MailCache.moveMessagesToFolder(sSpamFullName, this.checkedOrSelectedUids());
	}
};

/**
 * Moves the selected messages from the Spam folder to folder Inbox.
 */
CMessageListViewModel.prototype.executeNotSpam = function ()
{
	var oInbox = this.folderList().inboxFolder();

	if (oInbox && this.folderList().currentFolderFullName() !== oInbox.fullName())
	{
		App.MailCache.moveMessagesToFolder(oInbox.fullName(), this.checkedOrSelectedUids());
	}
};

CMessageListViewModel.prototype.clearAdvancedSearch = function ()
{
	this.searchInputFrom('');
	this.searchInputTo('');
	this.searchInputSubject('');
	this.searchInputText('');
	this.bAdvancedSearch(false);
	this.searchAttachmentsCheckbox(false);
	this.searchAttachments('');
	this.searchDateStart('');
	this.searchDateEnd('');
};

CMessageListViewModel.prototype.onAdvancedSearchClick = function ()
{
	this.bAdvancedSearch(!this.bAdvancedSearch());
};

CMessageListViewModel.prototype.calculateSearchStringForDescription = function ()
{
	return '<span class="part">' + Utils.encodeHtml(this.search()) + '</span>';
};

CMessageListViewModel.prototype.initUploader = function ()
{
	var self = this;

	if (this.uploaderArea())
	{
		this.oJua = new Jua({
			'action': '?/Upload/Message/',
			'name': 'jua-uploader',
			'queueSize': 2,
			'dragAndDropElement': this.uploaderArea(),
			'disableAjaxUpload': this.isPublic,
			'disableFolderDragAndDrop': this.isPublic,
			'disableDragAndDrop': this.isPublic,
			'hidden': {
				'Token': function () {
					return AppData.Token;
				},
				'AccountID': function () {
					return AppData.Accounts.currentId();
				},
				'AdditionalData':  function (oFile) {
					return JSON.stringify({
						'Folder': self.folderFullName()
					});
				}
			}
		});

		this.oJua
			.on('onDrop', _.bind(this.onFileDrop, this))
			.on('onComplete', _.bind(this.onFileUploadComplete, this))
			.on('onBodyDragEnter', _.bind(this.bDragActive, this, true))
			.on('onBodyDragLeave', _.bind(this.bDragActive, this, false))
		;
	}
};

CMessageListViewModel.prototype.onFileDrop = function (oData)
{
	if (!(oData && oData.File && oData.File.type && oData.File.type.indexOf('message/') === 0))
	{
		App.Api.showError(Utils.i18n('MAILBOX/ERROR_INCORRECT_FILE_EXTENSION'));
	}
};

CMessageListViewModel.prototype.onFileUploadComplete = function (sFileUid, bResponseReceived, oResponse)
{
	var bError = !bResponseReceived || !oResponse || oResponse.Error|| oResponse.Result.Error || false;

	if (!bError)
	{
		App.MailCache.executeCheckMail();
	}
	else
	{
		if (oResponse.ErrorCode && oResponse.ErrorCode === Enums.Errors.IncorrectFileExtension)
		{
			App.Api.showError(Utils.i18n('CONTACTS/ERROR_INCORRECT_FILE_EXTENSION'));
		}
		else
		{
			App.Api.showError(Utils.i18n('WARNING/ERROR_UPLOAD_FILE'));
		}
	}
};


/**
 * @constructor
 * 
 * @param {Function} fOpenMessageInNewWindowBinded
 */
function CMessagePaneViewModel(fOpenMessageInNewWindowBinded)
{
	this.openMessageInNewWindowBinded = fOpenMessageInNewWindowBinded;
	
	this.singleMode = ko.observable(AppData.SingleMode);
	this.isLoading = ko.observable(false);

	App.MailCache.folderList.subscribe(this.onFolderListSubscribe, this);
	this.messages = App.MailCache.messages;
	this.messages.subscribe(this.onMessagesSubscribe, this);
	this.currentMessage = App.MailCache.currentMessage;
	this.currentMessage.subscribe(this.onCurrentMessageSubscribe, this);
	AppData.User.defaultTimeFormat.subscribe(this.onCurrentMessageSubscribe, this);
	this.displayedMessageUid = ko.observable('');
	
	this.isCurrentMessage = ko.computed(function () {
		return !!this.currentMessage();
	}, this);
	
	this.isCurrentMessageLoaded = ko.computed(function () {
		return this.isCurrentMessage() && !this.isLoading();
	}, this);
	
	this.visibleNoMessageSelectedText = ko.computed(function () {
		return this.messages().length > 0 && !this.isCurrentMessage();
	}, this);
	
	this.prevMessageUid = App.MailCache.prevMessageUid;
	this.nextMessageUid = App.MailCache.nextMessageUid;

	this.isEnablePrevMessage = ko.computed(function () {
		return typeof this.prevMessageUid() === 'string' && this.prevMessageUid() !== '';
	}, this);
	this.isEnableNextMessage = ko.computed(function () {
		return typeof this.nextMessageUid() === 'string' && this.nextMessageUid() !== '';
	}, this);
	
	this.isEnableDelete = this.isCurrentMessage;
	this.isEnableReply = this.isCurrentMessageLoaded;
	this.isEnableReplyAll = this.isCurrentMessageLoaded;
	this.isEnableResend = this.isCurrentMessageLoaded;
	this.isEnableForward = this.isCurrentMessageLoaded;
	this.isEnablePrint = this.isCurrentMessageLoaded;
	this.isEnableSave = this.isCurrentMessage;
	
	this.allowSaveAsPdf =  ko.observable(!!AppData.AllowSaveAsPdf);
	
	this.isEnableSaveAsPdf = ko.computed(function () {
		return this.isCurrentMessageLoaded() && this.allowSaveAsPdf();
	}, this);

	this.deleteCommand = Utils.createCommand(this, this.executeDeleteMessage, this.isEnableDelete);
	this.prevMessageCommand = Utils.createCommand(this, this.executePrevMessage, this.isEnablePrevMessage);
	this.nextMessageCommand = Utils.createCommand(this, this.executeNextMessage, this.isEnableNextMessage);
	this.replyCommand = Utils.createCommand(this, this.executeReply, this.isEnableReply);
	this.replyAllCommand = Utils.createCommand(this, this.executeReplyAll, this.isEnableReplyAll);
	this.resendCommand = Utils.createCommand(this, this.executeResend, this.isEnableResend);
	this.forwardCommand = Utils.createCommand(this, this.executeForward, this.isEnableForward);
	this.printCommand = Utils.createCommand(this, this.executePrint, this.isEnablePrint);
	this.saveCommand = Utils.createCommand(this, this.executeSave, this.isEnableSave);
	this.saveAsPdfCommand = Utils.createCommand(this, this.executeSaveAsPdf, this.isEnableSaveAsPdf);
	this.moreCommand = Utils.createCommand(this, null, this.isCurrentMessageLoaded);

	this.ical = ko.observable(null);
	this.icalSubscription = this.ical.subscribe(function () {
		if (this.ical() !== null)
		{
			App.CalendarCache.firstRequestCalendarList();
			this.icalSubscription.dispose();
		}
	}, this);
	this.vcard = ko.observable(null);

	this.processed = ko.observable(false);

	this.visiblePicturesControl = ko.observable(false);
	this.visibleShowPicturesLink = ko.observable(false);
	this.visibleAppointmentInfo = ko.computed(function () {
		return this.ical() !== null;
	}, this);
	this.visibleVcardInfo = ko.computed(function () {
		return this.vcard() !== null;
	}, this);
	
	this.sensitivityText = ko.computed(function () {
		var sText = '';
		
		if (this.currentMessage())
		{
			switch (this.currentMessage().sensitivity())
			{
				case Enums.Sensivity.Confidential:
					sText = Utils.i18n('MESSAGE/SENSIVITY_CONFIDENTIAL');
					break;
				case Enums.Sensivity.Personal:
					sText = Utils.i18n('MESSAGE/SENSIVITY_PERSONAL');
					break;
				case Enums.Sensivity.Private:
					sText = Utils.i18n('MESSAGE/SENSIVITY_PRIVATE');
					break;
			}
		}
		
		return sText;
	}, this);

	this.visibleConfirmationControl = ko.computed(function () {
		return (this.currentMessage() && this.currentMessage().readingConfirmation() !== '');
	}, this);
	
	this.isCurrentNotDraftOrSent = ko.computed(function () {
		var oCurrFolder = App.MailCache.folderList().currentFolder();
		return (oCurrFolder && oCurrFolder.fullName().length > 0 &&
			oCurrFolder.type() !== Enums.FolderTypes.Drafts &&
			oCurrFolder.type() !== Enums.FolderTypes.Sent);
	}, this);

	this.isCurrentSentFolder = ko.computed(function () {
		var oCurrFolder = App.MailCache.folderList().currentFolder();
		return oCurrFolder && oCurrFolder.fullName().length > 0 && oCurrFolder.type() === Enums.FolderTypes.Sent;
	}, this);

	this.isCurrentNotDraftFolder = ko.computed(function () {
		var oCurrFolder = App.MailCache.folderList().currentFolder();
		return (oCurrFolder && oCurrFolder.fullName().length > 0 &&
			oCurrFolder.type() !== Enums.FolderTypes.Drafts);
	}, this);

	this.isVisibleReplyTool = this.isCurrentNotDraftOrSent;
	this.isVisibleResendTool = this.isCurrentSentFolder;
	this.isVisibleForwardTool = this.isCurrentNotDraftFolder;

	this.uid = ko.observable('');
	this.folder = ko.observable('');
	this.subject = ko.observable('');
	this.emptySubject = ko.computed(function () {
		return (Utils.trim(this.subject()) === '');
	}, this);
	this.subjectForDisplay = ko.computed(function () {
		return this.emptySubject() ? Utils.i18n('MAILBOX/EMPTY_SUBJECT') : this.subject();
	}, this);
	this.importance = ko.observable(Enums.Importance.Normal);
	this.oFromAddr = ko.observable(null);
	this.from = ko.observable('');
	this.fromEmail = ko.observable('');
	this.fullFrom = ko.observable('');
	this.to = ko.observable('');
	this.aToAddr = ko.observableArray([]);
	this.cc = ko.observable('');
	this.aCcAddr = ko.observableArray([]);
	this.bcc = ko.observable('');
	this.aBccAddr = ko.observableArray([]);
	this.allRecipients = ko.observable('');
	this.aAllRecipients = ko.observableArray([]);
	this.recipientsContacts = ko.observableArray([]);
	this.currentAccountEmail = ko.observable();
	this.meSender = Utils.i18n('MESSAGE/ME_SENDER');
	this.meRecipient = Utils.i18n('MESSAGE/ME_RECIPIENT');
	
	this.fullDate = ko.observable('');
	this.midDate = ko.observable('');

	this.textBody = ko.observable('');
	this.textBodyForNewWindow = ko.observable('');
	this.domTextBody = ko.observable(null);
	this.rtlMessage = ko.observable(false);
	
	this.contentHasFocus = ko.observable(false);

	this.decryptPassword = ko.observable('');
	this.visibleDecryptControl = ko.observable(false);
	this.visibleVerifyControl = ko.observable(false);

	this.fakeHeader = ko.computed(function () {
		return !(this.visiblePicturesControl() || this.visibleConfirmationControl() || 
				this.sensitivityText() !== '' || this.visibleDecryptControl() || this.visibleVerifyControl());
	}, this);

	this.mobileApp = bMobileApp;
	
	this.attachments = ko.observableArray([]);
	this.usesAttachmentString = true;
	this.attachmentsInString = ko.computed(function () {
		return _.map(this.attachments(), function (oAttachment) {
			return oAttachment.fileName();
		}, this).join(', ');
	}, this);
	this.notInlineAttachments = ko.computed(function () {
		return _.filter(this.attachments(), function (oAttach) {
			return !oAttach.linked();
		});
	}, this);
	this.visibleDownloadAllAttachments = ko.computed(function () {
		return AppData.ZipAttachments && this.notInlineAttachments().length > 1;
	}, this);
	this.visibleSaveAttachmentsToFiles = AppData.User.IsFilesSupported;
	this.visibleDownloadAllAttachmentsSeparately = ko.computed(function () {
		return this.notInlineAttachments().length > 1;
	}, this);
	this.visibleExtendedDownload = ko.computed(function () {
		return !this.mobileApp && (this.visibleDownloadAllAttachments() || this.visibleDownloadAllAttachmentsSeparately() || this.visibleSaveAttachmentsToFiles);
	}, this);

	this.detailsVisible = ko.observable(false);
	this.detailsTooltip = ko.computed(function () {
		return this.detailsVisible() ? Utils.i18n('MESSAGE/ACTION_HIDE_DETAILS') : Utils.i18n('MESSAGE/ACTION_SHOW_DETAILS');
	}, this);

	this.hasNotInlineAttachments = ko.computed(function () {
		return this.notInlineAttachments().length > 0;
	}, this);
	
	this.hasBodyText = ko.computed(function () {
		return this.textBody().length > 0;
	}, this);

	this.visibleAddMenu = ko.observable(false);
	
	// Quick Reply Part
	
	this.replyText = ko.observable('');
	this.replyTextFocus = ko.observable(false);
	this.replyPaneVisible = ko.computed(function () {
		return this.currentMessage() && this.currentMessage().completelyFilled();
	}, this);
	this.replySendingStarted = ko.observable(false);
	this.replySavingStarted = ko.observable(false);
	this.replyAutoSavingStarted = ko.observable(false);
	this.requiresPostponedSending = ko.observable(false);
	this.replyAutoSavingStarted.subscribe(function () {
		if (!this.replyAutoSavingStarted() && this.requiresPostponedSending())
		{
			App.MessageSender.sendPostponedMail(this.replyDraftUid());
			this.requiresPostponedSending(false);
		}
	}, this);
	
	ko.computed(function () {
		if (!this.replyTextFocus() || this.replyAutoSavingStarted() || this.replySavingStarted() || this.replySendingStarted())
		{
			this.stopAutosaveTimer();
		}
		if (this.replyTextFocus() && !this.replyAutoSavingStarted() && !this.replySavingStarted() && !this.replySendingStarted())
		{
			this.startAutosaveTimer();
		}
	}, this);
	
	this.saveButtonText = ko.computed(function () {
		return this.replyAutoSavingStarted() ? Utils.i18n('COMPOSE/TOOL_SAVING') : Utils.i18n('COMPOSE/TOOL_SAVE');
	}, this);
	this.replyDraftUid = ko.observable('');
	this.replyLoadingText = ko.computed(function () {
		if (this.replySendingStarted())
		{
			return Utils.i18n('COMPOSE/INFO_SENDING');
		}
		else if (this.replySavingStarted())
		{
			return Utils.i18n('COMPOSE/INFO_SAVING');
		}
		return '';
	}, this);
	
	this.isEnableSendQuickReply = ko.computed(function () {
		return this.isCurrentMessageLoaded() && this.replyText() !== '' && !this.replySendingStarted();
	}, this);
	this.isEnableSaveQuickReply = ko.computed(function () {
		return this.isEnableSendQuickReply() && !this.replySavingStarted() && !this.replyAutoSavingStarted();
	}, this);
	
	this.saveQuickReplyCommand = Utils.createCommand(this, this.executeSaveQuickReply, this.isEnableSaveQuickReply);
	this.sendQuickReplyCommand = Utils.createCommand(this, this.executeSendQuickReplyCommand, this.isEnableSendQuickReply);

	this.domMessageHeader = ko.observable(null);
	this.domQuickReply = ko.observable(null);
	
	this.domMessageForPrint = ko.observable(null);
	
	// to have time to take action "Open full reply form" before the animation starts
	this.replyTextFocusThrottled = ko.observable(false).extend({'throttle': 50});
	
	this.replyTextFocus.subscribe(function () {
		this.replyTextFocusThrottled(this.replyTextFocus());
	}, this);
	
	this.isQuickReplyActive = ko.computed(function () {
		return this.replyText().length > 0 || this.replyTextFocusThrottled();
	}, this);

	//*** Quick Reply Part

	this.jqPanelHelper = null;
	
	this.visibleAttachments = ko.observable(false);
	this.showMessage = function () {
		this.visibleAttachments(false);
	};
	this.showAttachments = function () {
		this.visibleAttachments(true);
	};
	
	this.defaultFontName = AppData.User.DefaultFontName;
	
	if (App.nowMoment)
	{
		App.nowMoment.subscribe(function () {
			this.updateMomentDate();
		}, this);
	}
}

CMessagePaneViewModel.prototype.resizeDblClick = function (oData, oEvent)
{
	if (oEvent.target.className !== '' && !!oEvent.target.className.search(/add_contact|icon|link|title|subject|link|date|from/))
	{
		oEvent.preventDefault();
		if (oEvent.stopPropagation)
		{
			oEvent.stopPropagation();
		}
		else
		{
			oEvent.cancelBubble = true;
		}

		Utils.removeSelection();
		if (!this.jqPanelHelper)
		{
			this.jqPanelHelper = $('.MailLayout .panel_helper');
		}
		this.jqPanelHelper.trigger('resize', [5, 'min', true]);
	}
};

CMessagePaneViewModel.prototype.notifySender = function ()
{
	if (this.currentMessage() && this.currentMessage().readingConfirmation() !== '')
	{
		App.Ajax.send({
			'Action': 'MessageSendConfirmation',
			'Confirmation': this.currentMessage().readingConfirmation(),
			'Subject': Utils.i18n('MESSAGE/RETURN_RECEIPT_MAIL_SUBJECT'),
			'Text': Utils.i18n('MESSAGE/RETURN_RECEIPT_MAIL_TEXT', {
				'EMAIL': AppData.Accounts.getEmail(),
				'SUBJECT': this.subject()
			}),
			'ConfirmFolder': this.currentMessage().folder(),
			'ConfirmUid': this.currentMessage().uid()
		});
		this.currentMessage().readingConfirmation('');
	}
};

CMessagePaneViewModel.prototype.onFolderListSubscribe = function ()
{
	if (AppData.SingleMode)
	{
		this.onMessagesSubscribe();
	}
};

CMessagePaneViewModel.prototype.onMessagesSubscribe = function ()
{
	if (!this.currentMessage() && this.uid().length > 0)
	{
		App.MailCache.setCurrentMessage(this.uid(), this.folder());
	}
};

CMessagePaneViewModel.prototype.onCurrentMessageSubscribe = function ()
{
	var
		oIcal = null,
		oVcard = null,
		oMessage = this.currentMessage(),
		oAccount = oMessage ? AppData.Accounts.getAccount(oMessage.accountId()) : null
	;
	
	if (this.singleMode() && window.opener && window.opener.oReplyDataFromViewPane)
	{
		this.replyText(window.opener.oReplyDataFromViewPane.ReplyText);
		this.replyDraftUid(window.opener.oReplyDataFromViewPane.ReplyDraftUid);
		window.opener.oReplyDataFromViewPane = null;
	}
	else if (!oMessage || oMessage.uid() !== this.displayedMessageUid())
	{
		this.replyText('');
		this.replyDraftUid('');
	}
	
	if (oMessage && this.uid() === oMessage.uid())
	{
		this.subject(oMessage.subject());
		this.importance(oMessage.importance());
		this.from(oMessage.oFrom.getDisplay());
		this.fromEmail(oMessage.oFrom.getFirstEmail());

		this.fullFrom(oMessage.oFrom.getFull());
		if (oMessage.oFrom.aCollection.length > 0)
		{
			this.oFromAddr(oMessage.oFrom.aCollection[0]);
		}
		else
		{
			this.oFromAddr(null);
		}
		
		this.to(oMessage.oTo.getFull());
		this.aToAddr(oMessage.oTo.aCollection);
		this.cc(oMessage.oCc.getFull());
		this.aCcAddr(oMessage.oCc.aCollection);
		this.bcc(oMessage.oBcc.getFull());
		this.aBccAddr(oMessage.oBcc.aCollection);

		this.currentAccountEmail(oAccount.email());
		this.aAllRecipients(_.uniq(_.union(this.aToAddr(), this.aCcAddr(), this.aBccAddr())));
		if (!this.mobileApp)
		{
			this.requestContactsByEmail(_.union(oMessage.oFrom.aCollection, this.aAllRecipients()));
		}

		this.midDate(oMessage.oDateModel.getMidDate());
		this.fullDate(oMessage.oDateModel.getFullDate());

		this.isLoading(oMessage.uid() !== '' && !oMessage.completelyFilled());
		
		this.setMessageBody();
		this.rtlMessage(oMessage.rtl());

		if (this.singleMode())
		{
			/*jshint onevar: false*/
			var
				aAtachments = [],
				sThumbSessionUid = Date.now().toString()
			;
			/*jshint onevar: true*/

			_.each(oMessage.attachments(), _.bind(function (oAttach) {
				var oCopy = new CMailAttachmentModel();
				oCopy.copyProperties(oAttach);
				oCopy.getInThumbQueue(sThumbSessionUid);
				aAtachments.push(oCopy);
			}, this));
			
			this.attachments(aAtachments);
		}
		else
		{
			this.attachments(oMessage.attachments());
		}

		// animation of buttons turns on with delay
		// so it does not trigger when placing initial values
		if (this.ical() !== null)
		{
			this.ical().animation(false);
		}
		oIcal = oMessage.ical();
		if (oIcal && this.singleMode())
		{
			oIcal = this.getIcalCopy(oIcal);
		}
		this.ical(oIcal);
		if (this.ical() !== null)
		{
			_.defer(_.bind(function () {
				if (this.ical() !== null)
				{
					this.ical().animation(true);
				}
			}, this));
			this.ical().updateAttendeeStatus(this.fromEmail());
		}
		oVcard = oMessage.vcard();
		if (oVcard && this.singleMode())
		{
			oVcard = this.getVcardCopy(oVcard);
		}
		this.vcard(oVcard);
		
		if (!oMessage.completelyFilled() || oMessage.trimmed())
		{
			/*jshint onevar: false*/
			var oSubscribedField = !oMessage.completelyFilled() ? oMessage.completelyFilled : oMessage.trimmed;
			/*jshint onevar: true*/
			if (this.singleMode())
			{
				oMessage.completelyFilledSingleModeSubscription = oSubscribedField.subscribe(this.onCurrentMessageSubscribe, this);
			}
			else
			{
				oMessage.completelyFilledSubscription = oSubscribedField.subscribe(this.onCurrentMessageSubscribe, this);
			}
		}
		else if (oMessage.completelyFilledSubscription)
		{
			oMessage.completelyFilledSubscription.dispose();
			oMessage.completelyFilledSubscription = undefined;
		}
		else if (oMessage.completelyFilledSingleModeSubscription)
		{
			oMessage.completelyFilledSingleModeSubscription.dispose();
			oMessage.completelyFilledSingleModeSubscription = undefined;
		}
	}
	else
	{
		this.isLoading(false);
		$(this.domTextBody()).empty().data('displayed-message-uid', '');
		this.displayedMessageUid('');
		this.rtlMessage(false);
		
		// cannot use removeAll, because the attachments of messages are passed by reference 
		// and the call to removeAll removes attachments from message in the cache too.
		this.attachments([]);
		this.visiblePicturesControl(false);
		this.visibleShowPicturesLink(false);
		this.ical(null);
		this.vcard(null);
		this.decryptPassword('');
		this.visibleDecryptControl(false);
		this.visibleVerifyControl(false);
	}
};

CMessagePaneViewModel.prototype.updateMomentDate = function ()
{
	var oMessage = this.currentMessage();
	if (oMessage && oMessage.oDateModel)
	{
		this.midDate(oMessage.oDateModel.getMidDate());
		this.fullDate(oMessage.oDateModel.getFullDate());
	}
};

/**
 * @param {Array} aRecipients
 */
CMessagePaneViewModel.prototype.requestContactsByEmail = function (aRecipients)
{
	_.each(aRecipients, _.bind(function (oAddress) {
		App.ContactsCache.getContactByEmail(oAddress.sEmail, this.onContactResponse, this);
	}, this));
};

/**
 * @param {Object} oContact
 * @param {string} sEmail
 */
CMessagePaneViewModel.prototype.onContactResponse = function (oContact, sEmail)
{
	if (oContact)
	{
		this.recipientsContacts.push(oContact);
		this.recipientsContacts(_.uniq(this.recipientsContacts()));
	}
	_.each(_.union([this.oFromAddr()], this.aAllRecipients()), function (oAddress) {
		if (oAddress && oAddress.sEmail === sEmail)
		{
			oAddress.loaded(true);
			if (oContact)
			{
				oAddress.founded(true);
			}
		}
	});
};

CMessagePaneViewModel.prototype.getVcardCopy = function (oVcard)
{
	var oNewVcard = new CVcardModel();
	oNewVcard.uid(oVcard.uid());
	oNewVcard.file(oVcard.file());
	oNewVcard.name(oVcard.name());
	oNewVcard.email(oVcard.email());
	oNewVcard.isExists(oVcard.isExists());
	oNewVcard.isJustSaved(oVcard.isJustSaved());
	return oNewVcard;
};

CMessagePaneViewModel.prototype.getIcalCopy = function (oIcal)
{
	var oNewIcal = new CIcalModel();
	oNewIcal.uid(oIcal.uid());
	oNewIcal.file(oIcal.file());
	oNewIcal.attendee(oIcal.attendee());
	oNewIcal.type(oIcal.type());
	oNewIcal.cancelDecision(oIcal.cancelDecision());
	oNewIcal.replyDecision(oIcal.replyDecision());
	oNewIcal.isJustSaved(oIcal.isJustSaved());
	oNewIcal.location(oIcal.location());
	oNewIcal.description(oIcal.description());
	oNewIcal.when(oIcal.when());
	oNewIcal.calendarId(oIcal.calendarId());
	oNewIcal.selectedCalendarId(oIcal.selectedCalendarId());
	oNewIcal.calendars(oIcal.calendars());
	oNewIcal.animation(oIcal.animation());
	return oNewIcal;
};

CMessagePaneViewModel.prototype.setMessageBody = function ()
{
	if (this.currentMessage())
	{
		var
			oMessage = this.currentMessage(),
			sText = oMessage.text()
		;
		
		this.textBody(sText);
		
		_.defer(_.bind(function () {
			if (this.currentMessage())
			{
				var
					oMessage = this.currentMessage(),
					sText = oMessage.text(),
					oDom = null,
					sHtml = '',
					sLen = sText.length,
					sMaxLen = 5000000,
					$body = $(this.domTextBody()),
					aCollapsedStatuses = []
				;
				
				if ($body.data('displayed-message-uid') === oMessage.uid())
				{
					aCollapsedStatuses = this.getBlockquotesStatus();
				}

				$body.empty();
				
				if (oMessage.isPlain() || sLen > sMaxLen)
				{
					$body.html(sText);
					
					this.visiblePicturesControl(false);
				}
				else
				{
					oDom = oMessage.getDomText();
					sHtml = oDom.length > 0 ? oDom.html() : '';
					
					$body.append(sHtml);

					this.visiblePicturesControl(oMessage.hasExternals() && !oMessage.isExternalsAlwaysShown());
					this.visibleShowPicturesLink(!oMessage.isExternalsShown());

					if (!Utils.htmlStartsWithBlockquote(sHtml))
					{
						this.doHidingBlockquotes(aCollapsedStatuses);
					}
				}
				
				this.decryptPassword('');
				this.visibleDecryptControl(AppData.User.enableOpenPgp() && oMessage.encryptedMessage());
				this.visibleVerifyControl(AppData.User.enableOpenPgp() && oMessage.signedMessage());
				$body.data('displayed-message-uid', oMessage.uid());
				this.displayedMessageUid(oMessage.uid());
			}
		}, this));
	}
};

CMessagePaneViewModel.prototype.getBlockquotesStatus = function ()
{
	var aCollapsedStatuses = [];
	
	$($('blockquote', $(this.domTextBody())).get()).each(function () {
		var
			$blockquote = $(this)
		;
		
		if ($blockquote.hasClass('blockquote_before_toggle'))
		{
			aCollapsedStatuses.push($blockquote.hasClass('collapsed'));
		}
	});
	
	return aCollapsedStatuses;
};

CMessagePaneViewModel.prototype.doHidingBlockquotes = function (aCollapsedStatuses)
{
	var
		iMinHeightForHide = 120,
		iHiddenHeight = 80,
		iStatusIndex = 0
	;
	
	$($('blockquote', $(this.domTextBody())).get()).each(function () {
		var
			$blockquote = $(this),
			$parentBlockquotes = $blockquote.parents('blockquote'),
			$switchButton = $('<span class="blockquote_toggle"></span>').html(Utils.i18n('MESSAGE/SHOW_QUOTED_TEXT')),
			bHidden = true
		;
		if ($parentBlockquotes.length === 0)
		{
			if ($blockquote.height() > iMinHeightForHide)
			{
				$blockquote
					.addClass('blockquote_before_toggle')
					.after($switchButton)
					.wrapInner('<div class="blockquote_content"></div>')
				;
				$switchButton.bind('click', function () {
					if (bHidden)
					{
						$blockquote.height('auto');
						$switchButton.html(Utils.i18n('MESSAGE/HIDE_QUOTED_TEXT'));
						bHidden = false;
					}
					else
					{
						$blockquote.height(iHiddenHeight);
						$switchButton.html(Utils.i18n('MESSAGE/SHOW_QUOTED_TEXT'));
						bHidden = true;
					}
					
					$blockquote.toggleClass('collapsed', bHidden);
				});
				if (iStatusIndex < aCollapsedStatuses.length)
				{
					bHidden = aCollapsedStatuses[iStatusIndex];
					iStatusIndex++;
				}
				$blockquote.height(bHidden ? iHiddenHeight : 'auto').toggleClass('collapsed', bHidden);
			}
		}
	});
};

/**
 * @param {Array} aParams
 */
CMessagePaneViewModel.prototype.onRoute = function (aParams)
{
	var oParams = App.Links.parseMailbox(aParams);

	if (this.replyText() !== '' && this.uid() !== oParams.Uid)
	{
		this.saveReplyMessage(false);
	}

	this.uid(oParams.Uid);
	this.folder(oParams.Folder);
	App.MailCache.setCurrentMessage(this.uid(), this.folder());
	
	this.contentHasFocus(true);
};

CMessagePaneViewModel.prototype.showPictures = function ()
{
	App.MailCache.showExternalPictures(false);
	this.visibleShowPicturesLink(false);
	this.setMessageBody();
};

CMessagePaneViewModel.prototype.alwaysShowPictures = function ()
{
	var
		sEmail = this.currentMessage() ? this.currentMessage().oFrom.getFirstEmail() : ''
	;

	if (sEmail.length > 0)
	{
		App.Ajax.send({
			'Action': 'EmailSetSafety',
			'Email': sEmail
		});
	}

	App.MailCache.showExternalPictures(true);
	this.visiblePicturesControl(false);
	this.setMessageBody();
};

CMessagePaneViewModel.prototype.openInNewWindow = function ()
{
	this.openMessageInNewWindowBinded(this.currentMessage());
};

CMessagePaneViewModel.prototype.addToContacts = function (sEmail, sName)
{
	if(!this.processed())
	{
		this.processed(true);
		App.ContactsCache.addToContacts(sName, sEmail, this.onAddToContactsResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMessagePaneViewModel.prototype.onAddToContactsResponse = function (oResponse, oRequest)
{
	if (oResponse.Result && oRequest.HomeEmail !== '')
	{
		App.Api.showReport(Utils.i18n('CONTACTS/REPORT_CONTACT_SUCCESSFULLY_ADDED'));
		App.ContactsCache.clearInfoAboutEmail(oRequest.HomeEmail);
		App.ContactsCache.getContactByEmail(oRequest.HomeEmail, this.onContactResponse, this);
	}
	this.processed(false);
};

CMessagePaneViewModel.prototype.getReplyHtmlText = function ()
{
	return '<div style="font-family: ' + this.defaultFontName + '; font-size: 16px">' + App.MessageSender.getHtmlFromText(this.replyText()) + '</div>';
};

/**
 * @param {string} sReplyType
 */
CMessagePaneViewModel.prototype.executeReplyOrForward = function (sReplyType)
{
	if (this.currentMessage())
	{
		App.MessageSender.setReplyData(this.getReplyHtmlText(), this.replyDraftUid());
		
		this.replyText('');
		this.replyDraftUid('');
		
		App.Api.composeMessageAsReplyOrForward(sReplyType, this.currentMessage().folder(), this.currentMessage().uid());
	}
};

CMessagePaneViewModel.prototype.executeDeleteMessage = function ()
{
	if (this.currentMessage())
	{
		if (this.singleMode() && window.opener && window.opener.App && window.opener.App.MailCache)
		{
			App.Api.deleteMessages([this.currentMessage().uid()], window.opener.App, function () {window.close();});
		}
		else if (this.mobileApp)
		{
			App.Api.deleteMessages([this.currentMessage().uid()], App);
		}
	}
};

CMessagePaneViewModel.prototype.executePrevMessage = function ()
{
	if (this.isEnablePrevMessage())
	{
		this.moveToSingleMessageView(this.prevMessageUid());
	}
};

CMessagePaneViewModel.prototype.executeNextMessage = function ()
{
	if (this.isEnableNextMessage())
	{
		this.moveToSingleMessageView(this.nextMessageUid());
	}
};

/**
 * @param {string} sUid
 */
CMessagePaneViewModel.prototype.moveToSingleMessageView = function (sUid)
{
	var
		sFolder = App.MailCache.folderList().currentFolderFullName(),
		aHash = [Enums.Screens.SingleMessageView, sFolder, 'msg' + sUid]
	;

	App.Routing.setHash(aHash);
};

CMessagePaneViewModel.prototype.executeReply = function ()
{
	this.executeReplyOrForward(Enums.ReplyType.Reply);
};

CMessagePaneViewModel.prototype.executeReplyAll = function ()
{
	this.executeReplyOrForward(Enums.ReplyType.ReplyAll);
};

CMessagePaneViewModel.prototype.executeResend = function ()
{
	this.executeReplyOrForward(Enums.ReplyType.Resend);
};

CMessagePaneViewModel.prototype.executeForward = function ()
{
	this.executeReplyOrForward(Enums.ReplyType.Forward);
};

CMessagePaneViewModel.prototype.executePrint = function ()
{
	var
		oMessage = this.currentMessage(),
		oWin = oMessage ? Utils.WindowOpener.open('', this.subject() + '-print') : null,
		sHtml = ''
	;

	if (oMessage && oWin)
	{
		this.textBodyForNewWindow(oMessage.getConvertedHtml(Utils.getAppPath(), true));
		sHtml = $(this.domMessageForPrint()).html();

		$(oWin.document.body).html(sHtml);
		oWin.print();
	}
};

CMessagePaneViewModel.prototype.executeSave = function ()
{
	if (this.currentMessage())
	{
		App.Api.downloadByUrl(this.currentMessage().downloadLink());
	}
};

CMessagePaneViewModel.prototype.executeSaveAsPdf = function ()
{
	if (this.currentMessage())
	{
		var
			oBody = this.currentMessage().getDomText(),
			iAccountId = this.currentMessage().accountId(),
			fReplaceWithBase64 = function (oImg) {

				try
				{
					var
						oCanvas = document.createElement('canvas'),
						oCtx = null
					;

					oCanvas.width = oImg.width;
					oCanvas.height = oImg.height;

					oCtx = oCanvas.getContext('2d');
					oCtx.drawImage(oImg, 0, 0);

					oImg.src = oCanvas.toDataURL('image/png');
				}
				catch (e) {}
			}
		;

		$('img[data-x-src-cid]', oBody).each(function () {
			fReplaceWithBase64(this);
		});

		App.Ajax.send({
			'Action': 'MessageGetPdfFromHtml',
			'Subject': this.subject(),
			'Html': oBody.html()
		}, function (oData) {
			if (oData && oData.Result && oData.Result['Hash'])
			{
				App.Api.downloadByUrl(Utils.getDownloadLinkByHash(iAccountId, oData.Result['Hash']));
			}
			else
			{
				App.Api.showError(Utils.i18n('WARNING/CREATING_PDF_ERROR'));
			}
		}, this);
	}
};

CMessagePaneViewModel.prototype.changeAddMenuVisibility = function ()
{
	var bVisibility = !this.visibleAddMenu();
	this.visibleAddMenu(bVisibility);
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMessagePaneViewModel.prototype.onMessageSendOrSaveResponse = function (oResponse, oRequest)
{
	var oResData = App.MessageSender.onMessageSendOrSaveResponse(oResponse, oRequest, this.requiresPostponedSending());
	switch (oResData.Action)
	{
		case 'MessageSend':
			this.replySendingStarted(false);
			if (oResData.Result)
			{
				this.replyText('');
			}
			break;
		case 'MessageSave':
			if (oResData.Result)
			{
				this.replyDraftUid(oResData.NewUid);
			}
			this.replySavingStarted(false);
			this.replyAutoSavingStarted(false);
			break;
	}
};

CMessagePaneViewModel.prototype.executeSendQuickReplyCommand = function ()
{
	if (this.isEnableSendQuickReply())
	{
		this.replySendingStarted(true);
		this.requiresPostponedSending(this.replyAutoSavingStarted());
		App.MessageSender.sendReplyMessage('MessageSend', this.getReplyHtmlText(), this.replyDraftUid(), 
			this.onMessageSendOrSaveResponse, this, this.requiresPostponedSending());

		this.replyTextFocus(false);
	}
};

CMessagePaneViewModel.prototype.executeSaveQuickReply = function ()
{
	this.saveReplyMessage(false);
};

CMessagePaneViewModel.prototype.saveReplyMessage = function (bAutosave)
{
	if (this.isEnableSaveQuickReply())
	{
		if (bAutosave)
		{
			this.replyAutoSavingStarted(true);
		}
		else
		{
			this.replySavingStarted(true);
		}
		App.MessageSender.sendReplyMessage('MessageSave', this.getReplyHtmlText(), this.replyDraftUid(), 
			this.onMessageSendOrSaveResponse, this);
	}
};

/**
 * Stops autosave.
 */
CMessagePaneViewModel.prototype.stopAutosaveTimer = function ()
{
	window.clearTimeout(this.autoSaveTimer);
};

/**
 * Starts autosave.
 */
CMessagePaneViewModel.prototype.startAutosaveTimer = function ()
{
	if (this.isEnableSaveQuickReply())
	{
		var fSave = _.bind(this.saveReplyMessage, this, true);
		this.stopAutosaveTimer();
		if (AppData.User.AllowAutosaveInDrafts)
		{
			this.autoSaveTimer = window.setTimeout(fSave, AppData.App.AutoSaveIntervalSeconds * 1000);
		}
	}
};

CMessagePaneViewModel.prototype.downloadAllAttachments = function ()
{
	if (this.currentMessage())
	{
		this.currentMessage().downloadAllAttachments();
	}
};

CMessagePaneViewModel.prototype.saveAttachmentsToFiles = function ()
{
	if (this.currentMessage())
	{
		this.currentMessage().saveAttachmentsToFiles();
	}
};

CMessagePaneViewModel.prototype.downloadAllAttachmentsSeparately = function ()
{
	if (this.currentMessage())
	{
		this.currentMessage().downloadAllAttachmentsSeparately();
	}
};

CMessagePaneViewModel.prototype.onApplyBindings = function (oMailViewModel)
{
	App.registerSessionTimeoutFunction(_.bind(function () {
		if (this.replyText() !== '')
		{
			this.saveReplyMessage(false);
		}
	}, this));
	
	$(oMailViewModel).on('mousedown', 'a', function (oEvent) {
		if (oEvent && 3 !== oEvent['which'])
		{
			var sHref = $(this).attr('href');
			if (sHref && 'mailto:' === sHref.toString().toLowerCase().substr(0, 7))
			{
				App.Api.composeMessageToAddresses(sHref.toString().substr(7));
				return false;
			}
		}

		return true;
	});

	this.hotKeysBind();
};

CMessagePaneViewModel.prototype.hotKeysBind = function ()
{
	$(document).on('keydown', $.proxy(function(ev) {

		var	bComputed = App.Screens.currentScreen() === Enums.Screens.Mailbox && ev && !ev.ctrlKey && !ev.shiftKey &&
			!Utils.isTextFieldFocused() && this.isEnableReply();

		if (bComputed && ev.keyCode === Enums.Key.q)
		{
			ev.preventDefault();
			this.replyTextFocus(true);
		}
		else if (bComputed && ev.keyCode === Enums.Key.r)
		{
			this.executeReply();
		}
	}, this));
};

CMessagePaneViewModel.prototype.showSourceHeaders = function ()
{
	var
		oMessage = this.currentMessage(),
		oWin = oMessage && oMessage.completelyFilled() ? Utils.WindowOpener.open('', this.subject() + '-headers') : null
	;

	if (oWin)
	{
		$(oWin.document.body).html('<pre>' + Utils.encodeHtml(oMessage.sourceHeaders()) + '</pre>');
	}
};

CMessagePaneViewModel.prototype.onDecryptMessageClick = function ()
{
	if (this.currentMessage() && this.currentMessage().encryptedMessage())
	{
		this.decryptVerifyMessage(false);
	}
};

CMessagePaneViewModel.prototype.onVerifyMessageClick = function ()
{
	if (this.currentMessage() && this.currentMessage().signedMessage())
	{
		this.decryptVerifyMessage(true);
	}
};

/**
 * @param {boolean} bVerifyOnly
 */
CMessagePaneViewModel.prototype.decryptVerifyMessage = function (bVerifyOnly)
{
	var fPgpCallback = _.bind(function (oPgp) {
		var oMessage = this.currentMessage();
		if (oPgp && oMessage)
		{
			if (bVerifyOnly && oMessage.signedMessage())
			{
				this.verifyMessage(oPgp);
			}
			else if (oMessage.encryptedMessage())
			{
				this.decryptMessage(oPgp);
			}
		}
	}, this);
	
	App.Api.pgp(fPgpCallback, AppData.User.IdUser);
};

/**
 * @param {Object} oPgp
 */
CMessagePaneViewModel.prototype.decryptMessage = function (oPgp)
{
	var
		oMessage = this.currentMessage(),
		sData = oMessage.textRaw(),
		sAccountEmail = AppData.Accounts.getEmail(),
		sFromEmail = oMessage.oFrom.getFirstEmail(),
		sPrivateKeyPassword = this.decryptPassword(),
		oRes = oPgp.decryptAndVerify(sData, sAccountEmail, sFromEmail, sPrivateKeyPassword),
		bNoSignDataNotice = false
	;
	
	if (oRes && oRes.result && !oRes.errors)
	{
		oMessage.text('<pre>' + Utils.encodeHtml(oRes.result) + '</pre>');
		oMessage.$text = null;
		oMessage.encryptedMessage(false);
		this.decryptPassword('');
		this.visibleDecryptControl(false);
		this.setMessageBody();
		if (!oRes.notices)
		{
			App.Api.showReport(Utils.i18n('OPENPGP/REPORT_MESSAGE_SUCCESSFULLY_DECRYPTED_AND_VERIFIED'));
		}
		else
		{
			App.Api.showReport(Utils.i18n('OPENPGP/REPORT_MESSAGE_SUCCESSFULLY_DECRYPTED'));
		}
	}
	
	if (oRes && (oRes.errors || oRes.notices))
	{
		bNoSignDataNotice = App.Api.showPgpErrorByCode(oRes, Enums.PgpAction.DecryptVerify);
		if (bNoSignDataNotice)
		{
			App.Api.showReport(Utils.i18n('OPENPGP/REPORT_MESSAGE_SUCCESSFULLY_DECRYPTED_AND_NOT_SIGNED'));
		}
	}
};

/**
 * @param {Object} oPgp
 */
CMessagePaneViewModel.prototype.verifyMessage = function (oPgp)
{
	var
		oMessage = this.currentMessage(),
		sData = oMessage.textRaw(),
		sFromEmail = oMessage.oFrom.getFirstEmail(),
		oRes = oPgp.verify(sData, sFromEmail)
	;
	
	if (oRes && oRes.result && !(oRes.errors || oRes.notices))
	{
		oMessage.text('<pre>' + Utils.encodeHtml(oRes.result) + '</pre>');
		oMessage.$text = null;
		oMessage.signedMessage(false);
		this.visibleVerifyControl(false);
		this.setMessageBody();
		App.Api.showReport(Utils.i18n('OPENPGP/REPORT_MESSAGE_SUCCESSFULLY_VERIFIED'));
	}
	
	if (oRes && (oRes.errors || oRes.notices))
	{
		App.Api.showPgpErrorByCode(oRes, Enums.PgpAction.Verify);
	}
};

CMessagePaneViewModel.prototype.testFunction = function ()
{
	App.Api.pgp(function (oOpenPgp) {
		// TODO
//		if (oOpenPgp)
//		{
//		}
	});
};

/**
 * @constructor
 */
function CMailViewModel()
{
	this.folderList = App.MailCache.folderList;
	this.domFolderList = ko.observable(null);
	
	this.openMessageInNewWindowBinded = _.bind(this.openMessageInNewWindow, this);
	
	this.oFolderList = new CFolderListViewModel();
	this.oMessageList = new CMessageListViewModel(this.openMessageInNewWindowBinded);
	this.oMessagePane = new CMessagePaneViewModel(this.openMessageInNewWindowBinded);

	this.isEnableGroupOperations = this.oMessageList.isEnableGroupOperations;

	this.composeLink = ko.observable(App.Routing.buildHashFromArray(App.Links.compose()));
	this.composeCommand = Utils.createCommand(this, this.executeCompose);

	this.checkMailCommand = Utils.createCommand(this, this.executeCheckMail);
	this.checkMailIndicator = ko.observable(true).extend({ throttle: 50 });
	ko.computed(function () {
		this.checkMailIndicator(App.MailCache.checkMailStarted() || App.MailCache.messagesLoading());
	}, this);
	this.markAsReadCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeMarkAsRead, this.isEnableGroupOperations);
	this.markAsUnreadCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeMarkAsUnread, this.isEnableGroupOperations);
	this.markAllReadCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeMarkAllRead);
	this.moveToFolderCommand = Utils.createCommand(this, Utils.emptyFunction, this.isEnableGroupOperations);
//	this.copyToFolderCommand = Utils.createCommand(this, Utils.emptyFunction, this.isEnableGroupOperations);
	this.deleteCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeDelete, this.isEnableGroupOperations);
	this.selectedCount = ko.computed(function () {
		return this.oMessageList.checkedUids().length;
	}, this);
	this.emptyTrashCommand = Utils.createCommand(App.MailCache, App.MailCache.executeEmptyTrash, this.oMessageList.isNotEmptyList);
	this.emptySpamCommand = Utils.createCommand(App.MailCache, App.MailCache.executeEmptySpam, this.oMessageList.isNotEmptyList);
	this.spamCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeSpam, this.isEnableGroupOperations);
	this.notSpamCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeNotSpam, this.isEnableGroupOperations);

	this.bVisibleComposeMessage = AppData.User.AllowCompose;
	
	this.isVisibleReplyTool = ko.computed(function () {
		return (this.folderList().currentFolder() &&
			this.folderList().currentFolderFullName().length > 0 &&
			this.folderList().currentFolderType() !== Enums.FolderTypes.Drafts &&
			this.folderList().currentFolderType() !== Enums.FolderTypes.Sent);
	}, this);

	this.isVisibleForwardTool = ko.computed(function () {
		return (this.folderList().currentFolder() &&
			this.folderList().currentFolderFullName().length > 0 &&
			this.folderList().currentFolderType() !== Enums.FolderTypes.Drafts);
	}, this);

	this.isSpamFolder = ko.computed(function () {
		return this.folderList().currentFolderType() === Enums.FolderTypes.Spam;
	}, this);
	
	this.allowedSpamAction = ko.computed(function () {
		var oAccount = AppData.Accounts.getCurrent();
		return oAccount ? oAccount.extensionExists('AllowSpamFolderExtension') && !this.isSpamFolder() : false;
	}, this);
	
	this.allowedNotSpamAction = ko.computed(function () {
		var oAccount = AppData.Accounts.getCurrent();
		return oAccount ? oAccount.extensionExists('AllowSpamFolderExtension') && this.isSpamFolder() : false;
	}, this);
	
	this.isTrashFolder = ko.computed(function () {
		return this.folderList().currentFolderType() === Enums.FolderTypes.Trash;
	}, this);

	this.jqPanelHelper = null;
	
	this.mobileApp = bMobileApp;
	this.selectedPanel = ko.observable(Enums.MobilePanel.Items);
	App.MailCache.currentMessage.subscribe(function () {
		this.gotoMessagePane();
	}, this);
}

CMailViewModel.prototype.executeCompose = function ()
{
	App.Api.composeMessage();
};

CMailViewModel.prototype.executeCheckMail = function ()
{
	App.MailCache.checkMessageFlags();
	App.MailCache.executeCheckMail();
};

CMailViewModel.prototype.openMessageInNewWindow = function (oMessage)
{
	var
		oFolder = this.folderList().getFolderByFullName(oMessage.folder()),
		bDraftFolder = (oFolder.type() === Enums.FolderTypes.Drafts)
	;
	
	if (this.oMessagePane.currentMessage() && this.oMessagePane.currentMessage().uid() === oMessage.uid() &&
			(this.oMessagePane.replyText() !== '' || this.oMessagePane.replyDraftUid() !== ''))
	{
		window.oReplyDataFromViewPane = {
			'ReplyText': this.oMessagePane.replyText(),
			'ReplyDraftUid': this.oMessagePane.replyDraftUid()
		};
		this.oMessagePane.replyText('');
		this.oMessagePane.replyDraftUid('');
	}
	
	Utils.WindowOpener.openMessage(oMessage, bDraftFolder);
};

CMailViewModel.prototype.gotoFolderList = function ()
{
	this.changeSelectedPanel(Enums.MobilePanel.Groups);
};

CMailViewModel.prototype.gotoMessageList = function ()
{
	this.changeSelectedPanel(Enums.MobilePanel.Items);
	return true;
};

CMailViewModel.prototype.gotoMessagePane = function ()
{
	if (App.MailCache.currentMessage())
	{
		this.changeSelectedPanel(Enums.MobilePanel.View);
	}
	else
	{
		this.gotoMessageList();
	}
};

/**
 * @param {number} iPanel
 */
CMailViewModel.prototype.changeSelectedPanel = function (iPanel)
{
	if (this.mobileApp)
	{
		if (this.selectedPanel() !== iPanel)
		{
			this.selectedPanel(iPanel);
		}
	}
};

/**
 * @param {Object} oData
 * @param {Object} oEvent
 */
CMailViewModel.prototype.resizeDblClick = function (oData, oEvent)
{
	oEvent.preventDefault();
	if (oEvent.stopPropagation)
	{
		oEvent.stopPropagation();
	}
	else
	{
		oEvent.cancelBubble = true;
	}

	Utils.removeSelection();
	if (!this.jqPanelHelper)
	{
		this.jqPanelHelper = $('.MailLayout .panel_helper');
	}
	this.jqPanelHelper.trigger('resize', [600, 'max']);
};

/**
 * @param {Array} aParams
 */
CMailViewModel.prototype.onRoute = function (aParams)
{
	this.oMessageList.onRoute(aParams);
	this.oMessagePane.onRoute(aParams);
};

CMailViewModel.prototype.onShow = function ()
{
	this.oMessageList.onShow();
};

CMailViewModel.prototype.onHide = function ()
{
	this.oMessageList.onHide();
};

CMailViewModel.prototype.onApplyBindings = function ()
{
	var self = this;

	this.oMessageList.onApplyBindings(this.$viewModel);
	this.oMessagePane.onApplyBindings(this.$viewModel);

	$(this.domFolderList()).on('click', 'span.folder', function (oEvent) {
		if (self.folderList().currentFolderFullName() !== $(this).data('folder')) {
			if (oEvent.ctrlKey) {
				self.oMessageList.executeCopyToFolder($(this).data('folder'));
			}
			else {
				self.oMessageList.executeMoveToFolder($(this).data('folder'));
			}
		}
	});

	this.hotKeysBind();
};

CMailViewModel.prototype.hotKeysBind = function ()
{
	$(document).on('keydown', $.proxy(function(ev) {
		var
			sKey = ev.keyCode,
			bComputed = ev && !ev.ctrlKey && !ev.altKey && !ev.shiftKey && !Utils.isTextFieldFocused() && App.Screens.currentScreen() === Enums.Screens.Mailbox,
			oList = this.oMessageList,
			oFirstMessage = oList.collection()[0],
			bGotoSearch = oFirstMessage && App.MailCache.currentMessage() && oFirstMessage.uid() === App.MailCache.currentMessage().uid()
		;
		
		if (bComputed && sKey === Enums.Key.s || bComputed && bGotoSearch && sKey === Enums.Key.Up)
		{
			ev.preventDefault();
			this.searchFocus();
		}
		else if (oList.isFocused() && ev && sKey === Enums.Key.Down && oFirstMessage)
		{
			ev.preventDefault();
			oList.isFocused(false);
			oList.routeForMessage(oFirstMessage);
		}
		else if (bComputed && sKey === Enums.Key.n)
		{
			window.location.href = '#compose';
		}
	},this));
};

/**
 * @param {Object} oMessage
 * @param {boolean} bCtrl
 */
CMailViewModel.prototype.dragAndDropHelper = function (oMessage, bCtrl)
{
	if (oMessage)
	{
		oMessage.checked(true);
	}

	var
		oHelper = Utils.draggableMessages(),
		aUids = this.oMessageList.checkedOrSelectedUids(),
		iCount = aUids.length
	;
		
	oHelper.data('p7-message-list-folder', this.folderList().currentFolderFullName());
	oHelper.data('p7-message-list-uids', aUids);

	$('.count-text', oHelper).text(Utils.i18n('MAILBOX/DRAG_TEXT_PLURAL', {
		'COUNT': bCtrl ? '+ ' + iCount : iCount
	}, null, iCount));

	return oHelper;
};

/**
 * @param {Object} oToFolder
 * @param {Object} oEvent
 * @param {Object} oUi
 */
CMailViewModel.prototype.messagesDrop = function (oToFolder, oEvent, oUi)
{
	if (oToFolder)
	{
		var
			oHelper = oUi && oUi.helper ? oUi.helper : null,
			sFolder = oHelper ? oHelper.data('p7-message-list-folder') : '',
			aUids = oHelper ? oHelper.data('p7-message-list-uids') : null
		;

		if ('' !== sFolder && null !== aUids)
		{
			Utils.uiDropHelperAnim(oEvent, oUi);
			if(oEvent.ctrlKey)
			{
				this.oMessageList.executeCopyToFolder(oToFolder.fullName());
			}
			else
			{
				this.oMessageList.executeMoveToFolder(oToFolder.fullName());
			}
			
			this.uncheckMessages();
		}
	}
};

CMailViewModel.prototype.searchFocus = function ()
{
	if (this.oMessageList.selector.useKeyboardKeys() && !Utils.isTextFieldFocused())
	{
		this.oMessageList.isFocused(true);
	}
};

CMailViewModel.prototype.backToList = function ()
{
	App.Routing.setPreviousHash();
};

CMailViewModel.prototype.onVolumerClick = function (oVm, oEv)
{
	oEv.stopPropagation();
};

CMailViewModel.prototype.uncheckMessages = function ()
{
	_.each(App.MailCache.messages(), function(oMessage) {
		oMessage.checked(false);
	});
};


/**
 * @constructor
 */
function CComposeViewModel()
{
	var self = this;
	
	this.toAddrDom = ko.observable();
	this.toAddrDom.subscribe(function () {
		this.initInputosaurus(this.toAddrDom, this.toAddr, this.lockToAddr, 'to');
	}, this);
	this.ccAddrDom = ko.observable();
	this.ccAddrDom.subscribe(function () {
		this.initInputosaurus(this.ccAddrDom, this.ccAddr, this.lockCcAddr, 'cc');
	}, this);
	this.bccAddrDom = ko.observable();
	this.bccAddrDom.subscribe(function () {
		this.initInputosaurus(this.bccAddrDom, this.bccAddr, this.lockBccAddr, 'bcc');
	}, this);
	
	this.folderList = App.MailCache.folderList;
	this.folderList.subscribe(function () {
		this.getMessageOnRoute();
	}, this);

	this.singleMode = ko.observable(AppData.SingleMode);
	this.isDemo = ko.observable(AppData.User.IsDemo);
	
	this.sending = ko.observable(false);
	this.sending.subscribe(this.sendingAndSavingSubscription, this);
	this.saving = ko.observable(false);
	this.saving.subscribe(this.sendingAndSavingSubscription, this);

	this.oHtmlEditor = new CHtmlEditorViewModel(false, this);
	this.textFocused = this.oHtmlEditor.textFocused;
	
	this.visibleBcc = ko.observable(false);
	this.visibleBcc.subscribe(function () {
		$html.toggleClass('screen-compose-bcc', this.visibleCc());
		_.defer(_.bind(function () {
			$(this.bccAddrDom()).inputosaurus('resizeInput');
		}, this));
	}, this);
	this.visibleCc = ko.observable(false);
	this.visibleCc.subscribe(function () {
		$html.toggleClass('screen-compose-cc', this.visibleCc());
		_.defer(_.bind(function () {
			$(this.ccAddrDom()).inputosaurus('resizeInput');
		}, this));
	}, this);
	this.visibleCounter = ko.observable(false);

	this.readingConfirmation = ko.observable(false);
	this.saveMailInSentItems = ko.observable(true);
	this.useSaveMailInSentItems = ko.observable(false);

	this.composeUploaderButton = ko.observable(null);
	this.composeUploaderButton.subscribe(function () {
		this.initUploader();
	}, this);
	this.composeUploaderDropPlace = ko.observable(null);
	this.composeUploaderBodyDragOver = ko.observable(false);
	this.composeUploaderDragOver = ko.observable(false);
	this.allowDragNDrop = ko.observable(false);
	this.uploaderBodyDragOver = ko.computed(function () {
		return this.allowDragNDrop() && this.composeUploaderBodyDragOver();
	}, this);
	this.uploaderDragOver = ko.computed(function () {
		return this.allowDragNDrop() && this.composeUploaderDragOver();
	}, this);

	this.selectedImportance = ko.observable(Enums.Importance.Normal);
	this.selectedSensitivity = ko.observable(Enums.Sensivity.Nothing);

	this.oSenderSelector = new CSenderSelector();
	this.senderAccountId = this.oSenderSelector.senderAccountId;
	this.senderList = this.oSenderSelector.senderList;
	this.visibleFrom = ko.computed(function () {
		return this.senderList().length > 1;
	}, this);
	this.selectedSender = this.oSenderSelector.selectedSender;
	this.selectedFetcherOrIdentity = this.oSenderSelector.selectedFetcherOrIdentity;
	this.selectedFetcherOrIdentity.subscribe(function () {
		if (!this.oHtmlEditor.isEditing())
		{
			this.oHtmlEditor.clearUndoRedo();
		}
	}, this);

	this.signature = ko.observable('');
	this.prevSignature = ko.observable(null);
	ko.computed(function () {
		var sSignature = App.MessageSender.getClearSignature(this.senderAccountId(), this.selectedFetcherOrIdentity());
		
		if (this.prevSignature() === null)
		{
			this.prevSignature(sSignature);
			this.signature(sSignature);
		}
		else
		{
			this.prevSignature(this.signature());
			this.signature(sSignature);
			this.oHtmlEditor.changeSignatureContent(this.signature(), this.prevSignature());
		}
	}, this);

	this.lockToAddr = ko.observable(false);
	this.toAddr = ko.observable('').extend({'reversible': true});
	this.toAddr.subscribe(function () {
		if (!this.lockToAddr())
		{
			$(this.toAddrDom()).val(this.toAddr());
			$(this.toAddrDom()).inputosaurus('refresh');
		}
	}, this);
	this.lockCcAddr = ko.observable(false);
	this.ccAddr = ko.observable('').extend({'reversible': true});
	this.ccAddr.subscribe(function () {
		if (!this.lockCcAddr())
		{
			$(this.ccAddrDom()).val(this.ccAddr());
			$(this.ccAddrDom()).inputosaurus('refresh');
		}
	}, this);
	this.lockBccAddr = ko.observable(false);
	this.bccAddr = ko.observable('').extend({'reversible': true});
	this.bccAddr.subscribe(function () {
		if (!this.lockBccAddr())
		{
			$(this.bccAddrDom()).val(this.bccAddr());
			$(this.bccAddrDom()).inputosaurus('refresh');
		}
	}, this);
	this.recipientEmails = ko.computed(function () {
		var
			aRecip = [this.toAddr(), this.ccAddr(), this.bccAddr()].join(',').split(','),
			aEmails = []
		;
		_.each(aRecip, function (sRecip) {
			var
				sTrimmedRecip = Utils.trim(sRecip),
				oRecip = null
			;
			if (sTrimmedRecip !== '')
			{
				oRecip = Utils.getEmailParts(sTrimmedRecip);
				if (oRecip.email)
				{
					aEmails.push(oRecip.email);
				}
			}
		});
		return aEmails;
	}, this);
	this.subject = ko.observable('').extend({'reversible': true});
	this.counter = ko.observable(0);
	this.plainText = ko.observable(false);
	this.textBody = ko.observable('');
	this.textBody.subscribe(function () {
		this.oHtmlEditor.setText(this.textBody(), this.plainText());
		this.oHtmlEditor.commit();
	}, this);

	this.focusedField = ko.observable();
	this.textFocused.subscribe(function () {
		if (this.textFocused())
		{
			this.focusedField('text');
		}
	}, this);
	this.subjectFocused = ko.observable(false);
	this.subjectFocused.subscribe(function () {
		if (this.subjectFocused())
		{
			this.focusedField('subject');
		}
	}, this);

	this.draftUid = ko.observable('');
	this.draftUid.subscribe(function () {
		App.MailCache.editedDraftUid(this.draftUid());
	}, this);
	this.draftInfo = ko.observableArray([]);
	this.routeType = ko.observable('');
	this.routeParams = ko.observableArray([]);
	this.inReplyTo = ko.observable('');
	this.references = ko.observable('');

	this.bUploadStatus = false;
	this.iUploadAttachmentsTimer = 0;
	this.messageUploadAttachmentsStarted = ko.observable(false);

	this.messageUploadAttachmentsStarted.subscribe(function (bValue) {
		window.clearTimeout(self.iUploadAttachmentsTimer);
		if (bValue)
		{
			self.iUploadAttachmentsTimer = window.setTimeout(function () {
				self.bUploadStatus = true;
				App.Api.showLoading(Utils.i18n('COMPOSE/INFO_ATTACHMENTS_LOADING'));
			}, 4000);
		}
		else
		{
			if (self.bUploadStatus)
			{
				self.iUploadAttachmentsTimer = window.setTimeout(function () {
					self.bUploadStatus = false;
					App.Api.hideLoading();
				}, 1000);
			}
			else
			{
				App.Api.hideLoading();
			}
		}
	}, this);

	this.attachments = ko.observableArray([]);
	this.attachmentsChanged = ko.observable(false);
	this.attachments.subscribe(function () {
		this.attachmentsChanged(true);
	}, this);
	this.notUploadedAttachments = ko.computed(function () {
		return _.filter(this.attachments(), function (oAttach) {
			return !oAttach.uploaded();
		});
	}, this);

	this.allAttachmentsUploaded = ko.computed(function () {
		return this.notUploadedAttachments().length === 0 && !this.messageUploadAttachmentsStarted();
	}, this);

	this.notInlineAttachments = ko.computed(function () {
		return _.filter(this.attachments(), function (oAttach) {
			return !oAttach.linked();
		});
	}, this);
	this.notInlineAttachments.subscribe(function () {
		$html.toggleClass('screen-compose-attachments', this.notInlineAttachments().length > 0);
	}, this);

	this.allowStartSending = ko.computed(function() {
		return !this.saving();
	}, this);
	this.allowStartSending.subscribe(function () {
		if (this.allowStartSending() && this.requiresPostponedSending())
		{
			App.MessageSender.sendPostponedMail(this.draftUid());
			this.requiresPostponedSending(false);
		}
	}, this);
	this.requiresPostponedSending = ko.observable(false);

	// file uploader
	this.oJua = null;

	this.isDraftsCleared = ko.observable(false);
	this.autoSaveTimer = -1;

	this.shown = ko.observable(false);
	this.shown.subscribe(function () {
		if (!this.shown())
		{
			this.stopAutosaveTimer();
		}
	}, this);
	this.backToListOnSendOrSave = ko.observable(false);

	this.enableOpenPgp = AppData.User.enableOpenPgp;
	this.pgpSecured = ko.observable(false);
	this.pgpSecured.subscribe(function () {
		this.oHtmlEditor.disabled(this.pgpSecured());
	}, this);
	this.pgpEncrypted = ko.observable(false);
	this.fromDrafts = ko.observable(false);
	this.visibleDoPgpButton = ko.computed(function () {
		return this.enableOpenPgp() && (!this.pgpSecured() || this.pgpEncrypted() && this.fromDrafts());
	}, this);
	this.visibleUndoPgpButton = ko.computed(function () {
		return this.enableOpenPgp() && this.pgpSecured() && (!this.pgpEncrypted() || !this.fromDrafts());
	}, this);
	this.isEnableOpenPgpCommand = ko.computed(function () {
		return this.enableOpenPgp() && !this.pgpSecured();
	}, this);

	this.backToListCommand = Utils.createCommand(this, this.executeBackToList);
	this.sendCommand = Utils.createCommand(this, this.executeSend, this.isEnableSending);
	this.saveCommand = Utils.createCommand(this, this.executeSaveCommand, this.isEnableSaving);
	this.openPgpCommand = Utils.createCommand(this, this.confirmOpenPgp, this.isEnableOpenPgpCommand);

	this.messageFields = ko.observable(null);
	this.bottomPanel = ko.observable(null);

	this.mobileApp = bMobileApp;
	this.showHotkeysHints = !bMobileDevice && !bMobileApp;
	
	this.aHotkeys = [
		{ value: 'Ctrl+Enter', action: Utils.i18n('COMPOSE/HOTKEY_SEND') },
		{ value: 'Ctrl+S', action: Utils.i18n('COMPOSE/HOTKEY_SAVE') },
		{ value: 'Ctrl+Z', action: Utils.i18n('COMPOSE/HOTKEY_UNDO') },
		{ value: 'Ctrl+Y', action: Utils.i18n('COMPOSE/HOTKEY_REDO') },
		{ value: 'Ctrl+K', action: Utils.i18n('COMPOSE/HOTKEY_LINK') },
		{ value: 'Ctrl+B', action: Utils.i18n('COMPOSE/HOTKEY_BOLD') },
		{ value: 'Ctrl+I', action: Utils.i18n('COMPOSE/HOTKEY_ITALIC') },
		{ value: 'Ctrl+U', action: Utils.i18n('COMPOSE/HOTKEY_UNDERLINE') }
	];

	this.allowFiles = ko.observable(false);

	this.dropboxConnected = ko.observable(false);
	this.allowDropbox = ko.computed(function () {
		return AppData.SocialDropbox && this.dropboxConnected();
	}, this);
	this.dropboxKey = AppData.SocialDropboxKey;
	//this.dropboxSecret = AppData.SocialDropboxSecret;

	this.googleConnected = ko.observable(false);
	this.allowGoogle = ko.computed(function () {
		return AppData.SocialGoogle && this.googleConnected();
	}, this);
	/*this.googleKey = AppData.SocialGoogleApiKey;
	this.googleClientId = AppData.SocialGoogleId;

	this.googleApiLoaded = false;
	this.pickerApiLoaded = false;*/
	this.closeBecauseSingleCompose = ko.observable(false);
	this.changedInPreviousWindow = ko.observable(false);

	this.minHeightAdjustTrigger = ko.observable(false).extend({'autoResetToFalse': 105});
	this.minHeightRemoveTrigger = ko.observable(false).extend({'autoResetToFalse': 105});
	this.jqContainers = $('.pSevenMain:first, .popup.compose_popup');
	ko.computed(function () {
		this.minHeightAdjustTrigger();
		this.minHeightRemoveTrigger();
		_.delay(function () {
			$('.compose_popup .panel_content .panels').trigger('resize');
		}, 200);
	}, this);

	this.hasSomethingToSave = ko.computed(function () {
		return this.isChanged() && this.isEnableSaving();
	}, this);

	this.saveAndCloseTooltip = ko.computed(function () {
		return this.hasSomethingToSave() ? Utils.i18n('COMPOSE/TOOL_SAVE_CLOSE') : Utils.i18n('COMPOSE/TOOL_CLOSE');
	}, this);

	if (window.opener)
	{
		setTimeout(function() {
			window.onbeforeunload = function(){
				if (self.hasSomethingToSave())
				{
					self.beforeHide(window.close);
					return '';
				}
			};
		}, 1000);
	}

	this.splitterDom = ko.observable();
}

CComposeViewModel.prototype.__name = 'CComposeViewModel';

/**
 * Determines if sending a message is allowed.
 */
CComposeViewModel.prototype.isEnableSending = function ()
{
	var
		bRecipientIsEmpty = this.toAddr().length === 0 && this.ccAddr().length === 0 && this.bccAddr().length === 0,
		bFoldersLoaded = this.folderList() && this.folderList().iAccountId !== 0
	;

	return bFoldersLoaded && !this.sending() && !bRecipientIsEmpty && this.allAttachmentsUploaded();
};

/**
 * Determines if saving a message is allowed.
 */
CComposeViewModel.prototype.isEnableSaving = function ()
{
	var bFoldersLoaded = this.folderList() && this.folderList().iAccountId !== 0;

	return this.shown() && bFoldersLoaded && !this.sending() && !this.saving();
};

/**
 * @param {Object} koAddrDom
 * @param {Object} koAddr
 * @param {Object} koLockAddr
 * @param {string} sFocusedField
 */
CComposeViewModel.prototype.initInputosaurus = function (koAddrDom, koAddr, koLockAddr, sFocusedField)
{
	if (koAddrDom() && $(koAddrDom()).length > 0)
	{
		$(koAddrDom()).inputosaurus({
			width: 'auto',
			parseOnBlur: true,
			autoCompleteSource: _.bind(function (oData, fResponse) {
				this.autocompleteCallback(oData.term, fResponse);
			}, this),
			autoCompleteAppendTo : $(koAddrDom()).closest('td'),
			change : _.bind(function (ev) {
				koLockAddr(true);
				this.setRecipient(koAddr, ev.target.value);
				this.minHeightAdjustTrigger(true);
				koLockAddr(false);
			}, this),
			copy: _.bind(function (sVal) {
				this.inputosaurusBuffer = sVal;
			}, this),
			paste: _.bind(function () {
				var sInputosaurusBuffer = this.inputosaurusBuffer || '';
				this.inputosaurusBuffer = '';
				return sInputosaurusBuffer;
			}, this),
			focus: _.bind(this.focusedField, this, sFocusedField),
			mobileDevice: bMobileDevice
		});
	}
};

/**
 * Executes after applying bindings.
 */
CComposeViewModel.prototype.onApplyBindings = function ()
{

	App.registerSessionTimeoutFunction(_.bind(this.executeSave, this, false));

	/*if (this.allowGoogle)
	{
		Utils.loadScript('https://apis.google.com/js/api.js?onload=onGoogleApiLoad', _.bind(this.onGoogleApiLoad, this), null, 'onGoogleApiLoad');
	}*/
	if (this.allowDropbox)
	{
		Utils.loadScript('https://www.dropbox.com/static/api/2/dropins.js', null, {
			'id': 'dropboxjs',
			'data-app-key': this.dropboxKey
		});
	}

	this.hotKeysBind();
};

CComposeViewModel.prototype.hotKeysBind = function ()
{
	$(document).on('keydown', $.proxy(function(ev) {

		if (ev && ev.ctrlKey && !ev.altKey && !ev.shiftKey)
		{
			var
				nKey = ev.keyCode,
				bShown = this.shown() && (!this.minimized || !this.minimized()),
				bComputed = bShown && ev && ev.ctrlKey,
				oEnumsKey = Enums.Key
			;

			if (bComputed && nKey === oEnumsKey.s)
			{
				ev.preventDefault();
				ev.returnValue = false;

				if (this.isEnableSaving())
				{
					this.saveCommand();
				}
			}
			else if (bComputed && nKey === oEnumsKey.Enter && this.toAddr() !== '')
			{
				this.sendCommand();
			}
		}

	},this));
};

CComposeViewModel.prototype.getMessageOnRoute = function ()
{
	var
		aParams = this.routeParams(),
		sFolderName = '',
		sUid = ''
	;

	if (this.routeType() !== '' && aParams.length === 3)
	{
		sFolderName = aParams[1];
		sUid = aParams[2];

		App.MailCache.getMessage(sFolderName, sUid, this.onMessageResponse, this);
	}
};

/**
 * Executes if the view model shows. Requests a folder list from the server to know the full names
 * of the folders Drafts and Sent Items.
 */
CComposeViewModel.prototype.onShow = function ()
{
	var sFocusedField = this.focusedField();
	this.shown(true);

	$(this.splitterDom()).trigger('resize');

	this.useSaveMailInSentItems(AppData.User.getUseSaveMailInSentItems());
	this.saveMailInSentItems(AppData.User.getSaveMailInSentItems());

	this.oHtmlEditor.initCrea(this.textBody(), this.plainText(), '7');
	this.oHtmlEditor.commit();

	this.initUploader();

	this.backToListOnSendOrSave(false);
	this.startAutosaveTimer();
	
	this.focusedField(sFocusedField);//oHtmlEditor initialization puts focus on it and changes the variable focusedField

	$html.addClass('screen-compose');

	if (this.oJua)
	{
		this.oJua.setDragAndDropEnabledStatus(true);
	}

	this.getSocialAccounts();
};

/**
 * Executes if routing changed.
 *
 * @param {Array} aParams
 */
CComposeViewModel.prototype.onRoute = function (aParams)
{
	var
		sSignature = '',
		oToAddr = {}
	;

	this.plainText(false);
	this.pgpSecured(false);
	this.pgpEncrypted(false);
	this.fromDrafts(false);

	this.bUploadStatus = false;
	window.clearTimeout(this.iUploadAttachmentsTimer);
	this.messageUploadAttachmentsStarted(false);

	this.draftUid('');
	this.draftInfo.removeAll();
	this.setDataFromMessage(new CMessageModel());

	this.isDraftsCleared(false);

	this.routeType((aParams.length > 0) ? aParams[0] : '');
	switch (this.routeType())
	{
		case Enums.ReplyType.Reply:
		case Enums.ReplyType.ReplyAll:
		case Enums.ReplyType.Resend:
		case Enums.ReplyType.Forward:
		case 'drafts':
			this.routeParams(aParams);
			if (this.folderList().iAccountId !== 0)
			{
				this.getMessageOnRoute();
			}
			break;
		default:
			sSignature = App.MessageSender.getSignatureText(this.senderAccountId(), this.selectedFetcherOrIdentity(), true);

			if (AppData.SingleMode && window.opener && window.opener.aMessagesParametersFromCompose && window.opener.aMessagesParametersFromCompose[window.name])
			{
				this.setMessageDataInSingleMode(window.opener.aMessagesParametersFromCompose[window.name]);
			}
			else if (sSignature !== '')
			{
				this.textBody('<br /><br />' + sSignature + '<br />');
			}

			if (this.routeType() === 'to' && aParams.length === 2)
			{
				oToAddr = App.Links.parseToAddr(aParams[1]);
				this.setRecipient(this.toAddr, oToAddr.to);
				if (oToAddr.hasMailto)
				{
					this.subject(oToAddr.subject);
					this.setRecipient(this.ccAddr, oToAddr.cc);
					this.setRecipient(this.bccAddr, oToAddr.bcc);
					this.textBody(oToAddr.body);
				}
			}

			if (this.routeType() === 'vcard' && aParams.length === 2)
			{
				this.addContactAsAttachment(aParams[1]);
			}

			if (this.routeType() === 'file' && aParams.length === 2)
			{
				this.addFilesAsAttachment(aParams[1]);
			}

			if (this.routeType() === 'data-as-file' && aParams.length === 3)
			{
				this.addDataAsAttachment(aParams[1], aParams[2]);
			}
			
			_.defer(_.bind(function () {
				this.focusAfterFilling();
			}, this));
			
			break;
	}

	this.visibleCc(this.ccAddr() !== '');
	this.visibleBcc(this.bccAddr() !== '');
	this.commit(true);

	this.allowFiles(AppData.User.IsFilesSupported && AppData.User.filesEnable());
	
	if (AppData.SingleMode && this.changedInPreviousWindow())
	{
		_.defer(_.bind(this.executeSave, this, true));
	}
};

CComposeViewModel.prototype.focusToAddr = function ()
{
	$(this.toAddrDom()).inputosaurus('focus');
};

CComposeViewModel.prototype.focusCcAddr = function ()
{
	$(this.ccAddrDom()).inputosaurus('focus');
};

CComposeViewModel.prototype.focusBccAddr = function ()
{
	$(this.bccAddrDom()).inputosaurus('focus');
};

CComposeViewModel.prototype.focusAfterFilling = function ()
{
	switch (this.focusedField())
	{
		case 'to':
			this.focusToAddr();
			break;
		case 'cc':
			this.visibleCc(true);
			this.focusCcAddr();
			break;
		case 'bcc':
			this.visibleBcc(true);
			this.focusBccAddr();
			break;
		case 'subject':
			this.subjectFocused(true);
			break;
		case 'text':
			this.oHtmlEditor.setFocus();
			break;
		default:
			if (this.toAddr().length === 0)
			{
				this.focusToAddr();
			}
			else if (this.subject().length === 0)
			{
				this.subjectFocused(true);
			}
			else
			{
				this.oHtmlEditor.setFocus();
			}
			break;
	}
};

/**
 * @param {Function} fContinueScreenChanging
 */
CComposeViewModel.prototype.beforeHide = function (fContinueScreenChanging)
{
	var
		sConfirm = Utils.i18n('COMPOSE/CONFIRM_DISCARD_CHANGES'),
		fOnConfirm = _.bind(function (bOk) {
			if (bOk && Utils.isFunc(fContinueScreenChanging))
			{
				this.commit();
				fContinueScreenChanging();
			}
			else
			{
				App.Routing.historyBackWithoutParsing(Enums.Screens.Compose);
			}
		}, this)
	;

	if (!this.closeBecauseSingleCompose() && this.hasSomethingToSave())
	{
		App.Screens.showPopup(ConfirmPopup, [sConfirm, fOnConfirm]);
	}
	else if (Utils.isFunc(fContinueScreenChanging))
	{
		fContinueScreenChanging();
	}
};

/**
 * Executes if view model was hidden.
 */
CComposeViewModel.prototype.onHide = function ()
{
	this.stopAutosaveTimer();

	if (!Utils.isFunc(this.closeCommand) && this.hasSomethingToSave())
	{
		this.executeSave(true);
	}

	this.shown(false);
	
	this.routeParams([]);

	this.subjectFocused(false);
	this.focusedField('');

	this.oHtmlEditor.closeAllPopups();
	this.oHtmlEditor.visibleLinkPopup(false);

	this.messageUploadAttachmentsStarted(false);

	$html.removeClass('screen-compose').removeClass('screen-compose-cc').removeClass('screen-compose-bcc').removeClass('screen-compose-attachments');
	this.minHeightRemoveTrigger(true);

	if (this.oJua)
	{
		this.oJua.setDragAndDropEnabledStatus(false);
	}
};

CComposeViewModel.prototype.sendingAndSavingSubscription = function ()
{
	if (this.sending() || this.saving())
	{
		this.stopAutosaveTimer();
	}
	if (!this.sending() && !this.saving())
	{
		this.startAutosaveTimer();
	}
};

/**
 * Stops autosave.
 */
CComposeViewModel.prototype.stopAutosaveTimer = function ()
{
	window.clearTimeout(this.autoSaveTimer);
};

/**
 * Starts autosave.
 */
CComposeViewModel.prototype.startAutosaveTimer = function ()
{
	if (this.shown() && !this.pgpSecured())
	{
		var fSave = _.bind(this.executeSave, this, true);
		this.stopAutosaveTimer();
		if (AppData.User.AllowAutosaveInDrafts)
		{
			this.autoSaveTimer = window.setTimeout(fSave, AppData.App.AutoSaveIntervalSeconds * 1000);
		}
	}
};

/**
 * @param {Object} koRecipient
 * @param {string} sRecipient
 */
CComposeViewModel.prototype.setRecipient = function (koRecipient, sRecipient)
{
	if (koRecipient() === sRecipient)
	{
		koRecipient.valueHasMutated();
	}
	else
	{
		koRecipient(sRecipient);
	}
};

/**
 * @param {Object} oMessage
 */
CComposeViewModel.prototype.onMessageResponse = function (oMessage)
{
	var oReplyData = null;

	if (oMessage === null)
	{
		this.setDataFromMessage(new CMessageModel());
	}
	else
	{
		switch (this.routeType())
		{
			case Enums.ReplyType.Reply:
			case Enums.ReplyType.ReplyAll:
				this.oSenderSelector.setFetcherOrIdentityByReplyMessage(oMessage);
				
				oReplyData = App.MessageSender.getReplyDataFromMessage(oMessage, this.routeType(), this.senderAccountId(), this.selectedFetcherOrIdentity(), true);

				this.draftInfo(oReplyData.DraftInfo);
				this.draftUid(oReplyData.DraftUid);
				this.setRecipient(this.toAddr, oReplyData.To);
				this.setRecipient(this.ccAddr, oReplyData.Cc);
				this.setRecipient(this.bccAddr, oReplyData.Bcc);
				this.subject(oReplyData.Subject);
				this.textBody(oReplyData.Text);
				this.attachments(oReplyData.Attachments);
				this.inReplyTo(oReplyData.InReplyTo);
				this.references(oReplyData.References);
				break;

			case Enums.ReplyType.Forward:
				this.oSenderSelector.setFetcherOrIdentityByReplyMessage(oMessage);

				oReplyData = App.MessageSender.getReplyDataFromMessage(oMessage, this.routeType(), this.senderAccountId(), this.selectedFetcherOrIdentity(), true);

				this.draftInfo(oReplyData.DraftInfo);
				this.draftUid(oReplyData.DraftUid);
				this.setRecipient(this.toAddr, oReplyData.To);
				this.setRecipient(this.ccAddr, oReplyData.Cc);
				this.subject(oReplyData.Subject);
				this.textBody(oReplyData.Text);
				this.attachments(oReplyData.Attachments);
				this.inReplyTo(oReplyData.InReplyTo);
				this.references(oReplyData.References);
				break;

			case Enums.ReplyType.Resend:
				this.setDataFromMessage(oMessage);
				break;

			case 'drafts':
				this.draftUid(oMessage.uid());
				this.setDataFromMessage(oMessage);
				this.fromDrafts(true);
				break;
		}

		this.routeType('');
	}

	if (this.attachments().length > 0)
	{
		this.requestAttachmentsTempName();
	}

	this.visibleCc(this.ccAddr() !== '');
	this.visibleBcc(this.bccAddr() !== '');
	this.commit(true);

	_.defer(_.bind(function () {
		this.focusAfterFilling();
	}, this));

	this.minHeightAdjustTrigger(true);
};

/**
 * @param {Object} oMessage
 */
CComposeViewModel.prototype.setDataFromMessage = function (oMessage)
{
	var
		sTextBody = '',
		bPgpEncrypted = false,
		bPgpSigned = false,
		oFetcherOrIdentity = App.MessageSender.getFirstFetcherOrIdentityByRecipientsOrDefault(oMessage.oFrom.aCollection, oMessage.accountId())
	;

	this.oSenderSelector.changeSenderAccountId(oMessage.accountId(), oFetcherOrIdentity);

	if (oMessage.isPlain())
	{
		bPgpEncrypted = oMessage.textRaw().indexOf('-----BEGIN PGP MESSAGE-----') !== -1;
		bPgpSigned = oMessage.textRaw().indexOf('-----BEGIN PGP SIGNED MESSAGE-----') !== -1;
		if (bPgpSigned || bPgpEncrypted)
		{
			sTextBody = oMessage.textRaw();
			this.pgpSecured(true);
			this.pgpEncrypted(bPgpEncrypted);
		}
		else
		{
			sTextBody = oMessage.textRaw();
		}
	}
	else
	{
		sTextBody = oMessage.getConvertedHtml();
	}
	
	this.draftInfo(oMessage.draftInfo());
	this.inReplyTo(oMessage.inReplyTo());
	this.references(oMessage.references());
	this.setRecipient(this.toAddr, oMessage.oTo.getFull());
	this.setRecipient(this.ccAddr, oMessage.oCc.getFull());
	this.setRecipient(this.bccAddr, oMessage.oBcc.getFull());
	this.subject(oMessage.subject());
	this.attachments(oMessage.attachments());
	this.plainText(oMessage.isPlain());
	this.textBody(sTextBody);
	this.selectedImportance(oMessage.importance());
	this.selectedSensitivity(oMessage.sensitivity());
	this.readingConfirmation(oMessage.readingConfirmation());
};

/**
 * @param {string} sData
 * @param {string} sFileName
 */
CComposeViewModel.prototype.addDataAsAttachment = function (sData, sFileName)
{
	var
		sHash = 'data-as-attachment-' + Math.random(),
		oParameters = {
			'Action': 'DataAsAttachmentUpload',
			'Data': sData,
			'FileName': sFileName,
			'Hash': sHash
		},
		oAttach = new CMailAttachmentModel()
	;

	this.subject(sFileName.substr(0, sFileName.length - 4));

	oAttach.fileName(sFileName);
	oAttach.hash(sHash);
	oAttach.uploadStarted(true);

	this.attachments.push(oAttach);

	this.messageUploadAttachmentsStarted(true);

	App.Ajax.send(oParameters, this.onDataAsAttachmentUpload, this);
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CComposeViewModel.prototype.onDataAsAttachmentUpload = function (oResponse, oRequest)
{
	var
		oResult = oResponse.Result,
		sHash = oRequest.Hash,
		oAttachment = _.find(this.attachments(), function (oAttach) {
			return oAttach.hash() === sHash;
		})
	;

	this.messageUploadAttachmentsStarted(false);

	if (oAttachment)
	{
		if (oResult && oResult.Attachment)
		{
			oAttachment.parseFromUpload(oResult.Attachment, oResponse.AccountID);
		}
		else
		{
			oAttachment.errorFromUpload();
		}
	}
};

/**
 * @param {Array} aFiles
 */
CComposeViewModel.prototype.addFilesAsAttachment = function (aFiles)
{
	var
		oAttach = null,
		aHashes = [],
		oParameters = null
	;

	_.each(aFiles, function (oFile) {
		oAttach = new CMailAttachmentModel();
		oAttach.fileName(oFile.fileName());
		oAttach.hash(oFile.hash());
		oAttach.thumb(oFile.thumb());
		oAttach.uploadStarted(true);

		this.attachments.push(oAttach);

		aHashes.push(oFile.hash());
	}, this);

	if (aHashes.length > 0)
	{
		oParameters = {
			'Action': 'FilesUpload',
			'Hashes': aHashes
		};

		this.messageUploadAttachmentsStarted(true);

		App.Ajax.send(oParameters, this.onFilesUpload, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CComposeViewModel.prototype.onFilesUpload = function (oResponse, oRequest)
{
	var
		aResult = oResponse.Result,
		aHashes = oRequest.Hashes,
		sThumbSessionUid = Date.now().toString()
	;
	this.messageUploadAttachmentsStarted(false);
	if ($.isArray(aResult))
	{
		_.each(aResult, function (oFileData) {
			var oAttachment = _.find(this.attachments(), function (oAttach) {
				return oAttach.hash() === oFileData.Hash;
			});

			if (oAttachment)
			{
				oAttachment.parseFromUpload(oFileData, oResponse.AccountID);
				oAttachment.hash(oFileData.NewHash);
				oAttachment.getInThumbQueue(sThumbSessionUid);
			}
		}, this);
	}
	else
	{
		_.each(aHashes, function (sHash) {
			var oAttachment = _.find(this.attachments(), function (oAttach) {
				return oAttach.hash() === sHash;
			});

			if (oAttachment)
			{
				oAttachment.errorFromUpload();
			}
		}, this);
	}
};

/**
 * @param {Object} oContact
 */
CComposeViewModel.prototype.addContactAsAttachment = function (oContact)
{
	var
		oAttach = new CMailAttachmentModel(),
		oParameters = null
	;

	if (oContact)
	{
		oAttach.fileName('contact-' + oContact.idContact() + '.vcf');
		oAttach.uploadStarted(true);

		this.attachments.push(oAttach);

		oParameters = {
			'Action': 'ContactVCardUpload',
			'ContactId': oContact.idContact(),
			'Global': oContact.global() ? '1' : '0',
			'Name': oAttach.fileName()
		};

		this.messageUploadAttachmentsStarted(true);

		App.Ajax.send(oParameters, this.onContactVCardUpload, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CComposeViewModel.prototype.onContactVCardUpload = function (oResponse, oRequest)
{
	var
		oResult = oResponse.Result,
		oAttach = null
	;

	this.messageUploadAttachmentsStarted(false);

	if (oResult)
	{
		oAttach = _.find(this.attachments(), function (oAttach) {
			return oAttach.fileName() === oResult.Name && oAttach.uploadStarted();
		});

		if (oAttach)
		{
			oAttach.parseFromUpload(oResult, oResponse.AccountID);
		}
	}
	else
	{
		oAttach = _.find(this.attachments(), function (oAttach) {
			return oAttach.fileName() === oRequest.Name && oAttach.uploadStarted();
		});

		if (oAttach)
		{
			oAttach.errorFromUpload();
		}
	}
};

CComposeViewModel.prototype.requestAttachmentsTempName = function ()
{
	var
		aHash = _.map(this.attachments(), function (oAttach) {
			oAttach.uploadUid(oAttach.hash());
			oAttach.uploadStarted(true);
			return oAttach.hash();
		}),
		oParameters = {
			'Action': 'MessageAttachmentsUpload',
			'Attachments': aHash
		}
	;

	if (aHash.length > 0)
	{
		this.messageUploadAttachmentsStarted(true);

		App.Ajax.send(oParameters, this.onMessageUploadAttachmentsResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CComposeViewModel.prototype.onMessageUploadAttachmentsResponse = function (oResponse, oRequest)
{
	var aHashes = oRequest.Attachments;

	this.messageUploadAttachmentsStarted(false);

	if (oResponse.Result)
	{
		_.each(oResponse.Result, _.bind(this.setAttachTepmNameByHash, this));
	}
	else
	{
		_.each(aHashes, function (sHash) {
			var oAttachment = _.find(this.attachments(), function (oAttach) {
				return oAttach.hash() === sHash;
			});

			if (oAttachment)
			{
				oAttachment.errorFromUpload();
			}
		}, this);
		App.Api.showError(Utils.i18n('COMPOSE/UPLOAD_ERROR_REPLY_ATTACHMENTS'));
	}
};

/**
 * @param {string} sHash
 * @param {string} sTempName
 */
CComposeViewModel.prototype.setAttachTepmNameByHash = function (sHash, sTempName)
{
	_.each(this.attachments(), function (oAttach) {
		if (oAttach.hash() === sHash)
		{
			oAttach.tempName(sTempName);
			oAttach.uploadStarted(false);
		}
	});
};

/**
 * @param {Object} oParameters
 */
CComposeViewModel.prototype.setMessageDataInSingleMode = function (oParameters)
{

	this.draftInfo(oParameters.draftInfo);
	this.draftUid(oParameters.draftUid);
	this.inReplyTo(oParameters.inReplyTo);
	this.references(oParameters.references);
	this.setRecipient(this.toAddr, oParameters.toAddr);
	this.setRecipient(this.ccAddr, oParameters.ccAddr);
	this.setRecipient(this.bccAddr, oParameters.bccAddr);
	this.subject(oParameters.subject);
	this.attachments(_.map(oParameters.attachments, function (oRawAttach) {
		var oAttach = new CMailAttachmentModel();
		oAttach.parse(oRawAttach, this.senderAccountId());
		return oAttach;
	}, this));
	this.textBody(oParameters.textBody);
	this.selectedImportance(oParameters.selectedImportance);
	this.selectedSensitivity(oParameters.selectedSensitivity);
	this.readingConfirmation(oParameters.readingConfirmation);
	this.changedInPreviousWindow(oParameters.changedInPreviousWindow);

	this.oSenderSelector.changeSenderAccountId(oParameters.senderAccountId, oParameters.selectedFetcherOrIdentity);
	this.focusedField(oParameters.focusedField);
};

/**
 * @param {boolean=} bOnlyCurrentWindow = false
 */
CComposeViewModel.prototype.commit = function (bOnlyCurrentWindow)
{
	this.toAddr.commit();
	this.toAddr.valueHasMutated();
	this.ccAddr.commit();
	this.toAddr.valueHasMutated();
	this.bccAddr.commit();
	this.toAddr.valueHasMutated();
	this.subject.commit();
	this.toAddr.valueHasMutated();
	this.oHtmlEditor.commit();
	this.attachmentsChanged(false);
	if (!bOnlyCurrentWindow)
	{
		this.changedInPreviousWindow(false);
	}
};

CComposeViewModel.prototype.isChanged = function ()
{
	return this.toAddr.changed() || this.ccAddr.changed() || this.bccAddr.changed() ||
			this.subject.changed() || this.oHtmlEditor.textChanged() ||
			this.attachmentsChanged() || this.changedInPreviousWindow();
};

CComposeViewModel.prototype.executeBackToList = function ()
{
	if (AppData.SingleMode)
	{
		window.close();
	}
	else if (this.shown())
	{
		App.Routing.setPreviousHash();
	}
	this.backToListOnSendOrSave(false);
};

/**
 * Creates new attachment for upload.
 *
 * @param {string} sFileUid
 * @param {Object} oFileData
 */
CComposeViewModel.prototype.onFileUploadSelect = function (sFileUid, oFileData)
{
	var oAttach;

	if (App.Api.showErrorIfAttachmentSizeLimit(oFileData.FileName, Utils.pInt(oFileData.Size)))
	{
		return false;
	}

	oAttach = new CMailAttachmentModel();
	oAttach.onUploadSelect(sFileUid, oFileData);
	this.attachments.push(oAttach);

	return true;
};

/**
 * Returns attachment found by uid.
 *
 * @param {string} sFileUid
 */
CComposeViewModel.prototype.getAttachmentByUid = function (sFileUid)
{
	return _.find(this.attachments(), function (oAttach) {
		return oAttach.uploadUid() === sFileUid;
	});
};

/**
 * Finds attachment by uid. Calls it's function to start upload.
 *
 * @param {string} sFileUid
 */
CComposeViewModel.prototype.onFileUploadStart = function (sFileUid)
{
	var oAttach = this.getAttachmentByUid(sFileUid);

	if (oAttach)
	{
		oAttach.onUploadStart();
	}
};

/**
 * Finds attachment by uid. Calls it's function to progress upload.
 *
 * @param {string} sFileUid
 * @param {number} iUploadedSize
 * @param {number} iTotalSize
 */
CComposeViewModel.prototype.onFileUploadProgress = function (sFileUid, iUploadedSize, iTotalSize)
{
	var oAttach = this.getAttachmentByUid(sFileUid);

	if (oAttach)
	{
		oAttach.onUploadProgress(iUploadedSize, iTotalSize);
	}
};

/**
 * Finds attachment by uid. Calls it's function to complete upload.
 *
 * @param {string} sFileUid
 * @param {boolean} bResponseReceived
 * @param {Object} oResult
 */
CComposeViewModel.prototype.onFileUploadComplete = function (sFileUid, bResponseReceived, oResult)
{
	var
		oAttach = this.getAttachmentByUid(sFileUid),
		sThumbSessionUid = Date.now().toString()
	;

	if (oAttach)
	{
		oAttach.onUploadComplete(sFileUid, bResponseReceived, oResult);
		if (oAttach.type().substr(0, 5) === 'image')
		{
			oAttach.thumb(true);
			oAttach.getInThumbQueue(sThumbSessionUid);
		}
	}
};

/**
 * Finds attachment by uid. Calls it's function to cancel upload.
 *
 * @param {string} sFileUid
 */
CComposeViewModel.prototype.onFileRemove = function (sFileUid)
{
	var oAttach = this.getAttachmentByUid(sFileUid);

	if (this.oJua)
	{
		this.oJua.cancel(sFileUid);
	}

	this.attachments.remove(oAttach);
};

/**
 * Initializes file uploader.
 */
CComposeViewModel.prototype.initUploader = function ()
{
	if (this.shown() && this.composeUploaderButton() && this.oJua === null)
	{
		this.oJua = new Jua({
			'action': '?/Upload/Attachment/',
			'name': 'jua-uploader',
			'queueSize': 2,
			'clickElement': this.composeUploaderButton(),
			'hiddenElementsPosition': Utils.isRTL() ? 'right' : 'left',
			'dragAndDropElement': this.composeUploaderDropPlace(),
			'disableAjaxUpload': false,
			'disableFolderDragAndDrop': false,
			'disableDragAndDrop': false,
			'hidden': {
				'Token': function () {
					return AppData.Token;
				},
				'AccountID': function () {
					return AppData.Accounts.currentId();
				}
			}
		});

		this.oJua
			.on('onDragEnter', _.bind(this.composeUploaderDragOver, this, true))
			.on('onDragLeave', _.bind(this.composeUploaderDragOver, this, false))
			.on('onBodyDragEnter', _.bind(this.composeUploaderBodyDragOver, this, true))
			.on('onBodyDragLeave', _.bind(this.composeUploaderBodyDragOver, this, false))
			.on('onProgress', _.bind(this.onFileUploadProgress, this))
			.on('onSelect', _.bind(this.onFileUploadSelect, this))
			.on('onStart', _.bind(this.onFileUploadStart, this))
			.on('onComplete', _.bind(this.onFileUploadComplete, this))
		;

		this.allowDragNDrop(this.oJua.isDragAndDropSupported());
	}
};

/**
 * @param {boolean} bRemoveSignatureAnchor
 */
CComposeViewModel.prototype.getSendSaveParameters = function (bRemoveSignatureAnchor)
{
	var
		oAttachments = App.MessageSender.convertAttachmentsForSending(this.attachments())
	;

	_.each(this.oHtmlEditor.uploadedImagePathes(), function (oAttach) {
		oAttachments[oAttach.TempName] = [oAttach.Name, oAttach.CID, '1', '1'];
	});

	return {
		'AccountID': this.senderAccountId(),
		'FetcherID': this.selectedFetcherOrIdentity() && this.selectedFetcherOrIdentity().FETCHER ? this.selectedFetcherOrIdentity().id() : '',
		'IdentityID': this.selectedFetcherOrIdentity() && !this.selectedFetcherOrIdentity().FETCHER ? this.selectedFetcherOrIdentity().id() : '',
		'DraftInfo': this.draftInfo(),
		'DraftUid': this.draftUid(),
		'To': this.toAddr(),
		'Cc': this.ccAddr(),
		'Bcc': this.bccAddr(),
		'Subject': this.subject(),
		'Text': this.plainText() ? this.oHtmlEditor.getPlainText() : this.oHtmlEditor.getText(bRemoveSignatureAnchor),
		'IsHtml': this.plainText() ? '0' : '1',
		'Importance': this.selectedImportance(),
		'Sensivity': this.selectedSensitivity(),
		'ReadingConfirmation': this.readingConfirmation() ? '1' : '0',
		'Attachments': oAttachments,
		'InReplyTo': this.inReplyTo(),
		'References': this.references()
	};
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CComposeViewModel.prototype.onMessageSendOrSaveResponse = function (oResponse, oRequest)
{
	var oResData = App.MessageSender.onMessageSendOrSaveResponse(oResponse, oRequest, this.requiresPostponedSending());

	this.commit();

	switch (oResData.Action)
	{
		case 'MessageSave':
			if (oResData.Result && oRequest.DraftUid === this.draftUid())
			{
				this.draftUid(Utils.pString(oResData.NewUid));
				if (AppData.SingleMode)
				{
					App.Routing.replaceHashDirectly(App.Links.composeFromMessage('drafts', oRequest.DraftFolder, this.draftUid()));
				}
			}
			this.saving(false);
			break;
		case 'MessageSend':
			if (oResData.Result)
			{
				if (this.backToListOnSendOrSave())
				{
					if (Utils.isFunc(this.closeCommand))
					{
						this.closeCommand();
					}
					else
					{
						this.executeBackToList();
					}
				}
			}
			this.sending(false);
			break;
	}
};

CComposeViewModel.prototype.verifyDataForSending = function ()
{
	var
		aToIncorrect = Utils.getIncorrectEmailsFromAddressString(this.toAddr()),
		aCcIncorrect = Utils.getIncorrectEmailsFromAddressString(this.ccAddr()),
		aBccIncorrect = Utils.getIncorrectEmailsFromAddressString(this.bccAddr()),
		aIncorrect = _.union(aToIncorrect, aCcIncorrect, aBccIncorrect),
		sWarning = Utils.i18n('COMPOSE/WARNING_INPUT_CORRECT_EMAILS') + aIncorrect.join(', ')
	;

	if (aIncorrect.length > 0)
	{
		App.Screens.showPopup(AlertPopup, [sWarning]);
		return false;
	}

	return true;
};

/**
 * @param {mixed} mParam
 */
CComposeViewModel.prototype.executeSend = function (mParam)
{
	var bAlreadySigned = (mParam === true);

	if (this.isEnableSending() && this.verifyDataForSending())
	{
		if (!bAlreadySigned && this.enableOpenPgp() && AppData.User.AutosignOutgoingEmails && !this.pgpSecured())
		{
			this.openPgpPopup(true);
		}
		else
		{
			this.sending(true);
			this.requiresPostponedSending(!this.allowStartSending());

			App.MessageSender.send('MessageSend', this.getSendSaveParameters(true), this.saveMailInSentItems(),
				true, this.onMessageSendOrSaveResponse, this, this.requiresPostponedSending());
		}

		this.backToListOnSendOrSave(true);
	}
};

CComposeViewModel.prototype.executeSaveCommand = function ()
{
	this.executeSave(false);
};

/**
 * @param {boolean=} bAutosave = false
 * @param {boolean=} bWaitResponse = true
 */
CComposeViewModel.prototype.executeSave = function (bAutosave, bWaitResponse)
{
	bAutosave = Utils.isUnd(bAutosave) ? false : bAutosave;
	bWaitResponse = Utils.isUnd(bWaitResponse) ? true : bWaitResponse;

	if (bAutosave && App.MailCache.disableComposeAutosave())
	{
		return;
	}

	var
		sConfirm = Utils.i18n('OPENPGP/CONFIRM_SAVE_ENCRYPTED_DRAFT'),
		sOkButton = Utils.i18n('COMPOSE/TOOL_SAVE'),
		fSave = _.bind(function (bSave) {
			if (bSave)
			{
				if (bWaitResponse)
				{
					this.saving(true);
					App.MessageSender.send('MessageSave', this.getSendSaveParameters(false), this.saveMailInSentItems(),
						!bAutosave, this.onMessageSendOrSaveResponse, this);
				}
				else
				{
					App.MessageSender.send('MessageSave', this.getSendSaveParameters(false), this.saveMailInSentItems(),
						!bAutosave, App.MessageSender.onMessageSendOrSaveResponse, App.MessageSender);
				}
			}
		}, this)
	;

	if (this.isEnableSaving())
	{
		if (!bAutosave || this.isChanged())
		{
			if (!bAutosave && this.pgpSecured())
			{
				App.Screens.showPopup(ConfirmPopup, [sConfirm, fSave, '', sOkButton]);
			}
			else
			{
				fSave(true);
			}
		}
		else if (bAutosave)
		{
			this.startAutosaveTimer();
		}

		this.backToListOnSendOrSave(true);
	}
};

/**
 * Changes visibility of bcc field.
 */
CComposeViewModel.prototype.changeBccVisibility = function ()
{
	this.visibleBcc(!this.visibleBcc());

	if (this.visibleBcc())
	{
		this.focusBccAddr();
	}
	else
	{
		this.focusToAddr();
	}

	this.minHeightAdjustTrigger(true);
};

/**
 * Changes visibility of bcc field.
 */
CComposeViewModel.prototype.changeCcVisibility = function ()
{
	this.visibleCc(!this.visibleCc());

	if (this.visibleCc())
	{
		this.focusCcAddr();
	}
	else
	{
		this.focusToAddr();
	}

	this.minHeightAdjustTrigger(true);
};

CComposeViewModel.prototype.getMessageDataForSingleMode = function ()
{
	var
		aAttachments = _.map(this.attachments(), function (oAttach)
			{
				return {
					'@Object': 'Object/CApiMailAttachment',
					'FileName': oAttach.fileName(),
					'TempName': oAttach.tempName(),
					'MimeType': oAttach.type(),
					'MimePartIndex': oAttach.mimePartIndex(),
					'EstimatedSize': oAttach.size(),
					'CID': oAttach.cid(),
					'ContentLocation': oAttach.contentLocation(),
					'IsInline': oAttach.inline(),
					'IsLinked': oAttach.linked(),
					'Hash': oAttach.hash()
				};
			})
	;
	
	return {
		accountId: this.senderAccountId(),
		draftInfo: this.draftInfo(),
		draftUid: this.draftUid(),
		inReplyTo: this.inReplyTo(),
		references: this.references(),
		senderAccountId: this.senderAccountId(),
		selectedFetcherOrIdentity: this.selectedFetcherOrIdentity(),
		toAddr: this.toAddr(),
		ccAddr: this.ccAddr(),
		bccAddr: this.bccAddr(),
		subject: this.subject(),
		attachments: aAttachments,
		textBody: this.oHtmlEditor.getText(),
		selectedImportance: this.selectedImportance(),
		selectedSensitivity: this.selectedSensitivity(),
		readingConfirmation: this.readingConfirmation(),
		changedInPreviousWindow: this.isChanged(),
		focusedField: this.focusedField()
	};
};

CComposeViewModel.prototype.openInNewWindow = function ()
{
	var
		sWinName = 'id' + Math.random().toString(),
		oMessageParametersFromCompose = {},
		oWin = null,
		sHash = '#' + Enums.Screens.SingleCompose
	;

	this.closeBecauseSingleCompose(true);
	oMessageParametersFromCompose = this.getMessageDataForSingleMode();

	if (this.draftUid().length > 0 && !this.isChanged())
	{
		sHash = App.Routing.buildHashFromArray(App.Links.composeFromMessage('drafts', App.MailCache.folderList().draftsFolderFullName(), this.draftUid(), true));
		oWin = Utils.WindowOpener.openTab(sHash);
	}
	else if (!this.isChanged())
	{
		sHash = App.Routing.buildHashFromArray(_.union([Enums.Screens.SingleCompose], this.routeParams()));
		oWin = Utils.WindowOpener.openTab(sHash);
	}
	else
	{
		if (!window.aMessagesParametersFromCompose)
		{
			window.aMessagesParametersFromCompose = [];
		}
		window.aMessagesParametersFromCompose[sWinName] = oMessageParametersFromCompose;
		oWin = Utils.WindowOpener.openTab(sHash, sWinName);
	}

	this.commit();

	if (Utils.isFunc(this.closeCommand))
	{
		this.closeCommand();
	}
	else
	{
		this.executeBackToList();
	}
};

/**
 * @param {string} sTerm
 * @param {Function} fResponse
 */
CComposeViewModel.prototype.autocompleteCallback = function (sTerm, fResponse)
{
	var
		oParameters = {
			'Action': 'ContactSuggestions',
			'Search': sTerm
		}
	;

	sTerm = Utils.trim(sTerm);
	if ('' !== sTerm)
	{
		App.Ajax.send(oParameters, function (oResponse) {

			var aList = [];
			if (oResponse && oResponse.Result && oResponse.Result && oResponse.Result.List)
			{
				aList = _.map(oResponse.Result.List, function (oItem) {

					var
						sLabel = '',
						sValue = oItem.Email
					;

					if (oItem.IsGroup)
					{
						if (oItem.Name && 0 < Utils.trim(oItem.Name).length)
						{
							sLabel = '"' + oItem.Name + '" (' + oItem.Email + ')';
						}
						else
						{
							sLabel = '(' + oItem.Email + ')';
						}
					}
					else
					{
						if (oItem.Name && 0 < Utils.trim(oItem.Name).length)
						{
							sLabel = '"' + oItem.Name + '" <' + oItem.Email + '>';
							sValue = sLabel;
						}
						else
						{
							sLabel = oItem.Email;
						}
					}

					return {'label': sLabel, 'value': sValue, 'frequency': oItem.Frequency};
				});

				aList = _.compact(aList);

//				aList = _.sortBy(_.compact(aList), function(nNum) {
//					return nNum['frequency'];
//				}).reverse();
			}

			fResponse(aList);

		}, this);
	}
	else
	{
		fResponse([]);
	}
};

CComposeViewModel.prototype.onShowFilesPopupClick = function ()
{
	var fCallBack = _.bind(this.addFilesAsAttachment, this);
	/*global FileStoragePopup:true */
	App.Screens.showPopup(FileStoragePopup, [fCallBack]);
	/*global FileStoragePopup:false */
};

// Google Drive
CComposeViewModel.prototype.onShowGoogleDriveClick = function ()
{
	/*global GooglePickerPopup:true */
	App.Screens.showPopup(GooglePickerPopup, [_.bind(this.googlePickerCallback, this)]);
	/*global GooglePickerPopup:false */
};

CComposeViewModel.prototype.googlePickerCallback = function (aPickerItems, sAccessToken)
{
	var
		oAttach = null,
		aUrls = [],
		oParameters = {},
		self = this
	;

	_.each(aPickerItems, function (oPickerItem) {

		oAttach = new CMailAttachmentModel()
			.fileName(oPickerItem.name)
			.hash(oPickerItem.id)
			.size(oPickerItem.sizeBytes)
			.uploadStarted(true)
			.type(oPickerItem.mimeType);

		if (oAttach.type().substr(0, 5) === 'image')
		{
			oAttach.thumb(true);
		}

		self.attachments.push(oAttach);
		aUrls.push(oPickerItem.id);

	}, this);

	oParameters = {
		'Action': 'FilesUploadByLink',
		'Links': aUrls,
		'LinksAsIds': true,
		'AccessToken': sAccessToken
	};

	this.messageUploadAttachmentsStarted(true);

	App.Ajax.send(oParameters, this.onFilesUpload, this);
};
// ----------------

// DropBox
CComposeViewModel.prototype.onShowDropBoxClick = function ()
{
	var
		oAttach = null,
		aUrls = [],
		oParameters = {},
		self = this,

		options = {
			success: function(files) {
				_.each(files, function (oFile) {
					oAttach = new CMailAttachmentModel()
						.fileName(oFile.name)
						.hash(oFile.link)
						.size(oFile.bytes)
						.uploadStarted(true)
						.thumb(!!oFile.thumbnailLink);

					self.attachments.push(oAttach);

					aUrls.push(oFile.link);
				}, self);


				oParameters = {
					'Action': 'FilesUploadByLink',
					'Links': aUrls
				};

				self.messageUploadAttachmentsStarted(true);

				App.Ajax.send(oParameters, self.onFilesUpload, self);
			},
			cancel: function() {

			},
			linkType: "direct",
			multiselect: true
		}
	;

	if (this.allowDropbox && window.Dropbox)
	{
		window.Dropbox.choose(options);
	}
};
// ----------------


CComposeViewModel.prototype.confirmOpenPgp = function ()
{
	var
		sConfirm = Utils.i18n('OPENPGP/CONFIRM_HTML_TO_PLAIN_FORMATTING'),
		fOpenPgpEncryptPopup = _.bind(function (bRes) {
			if (bRes)
			{
				this.openPgpPopup(false);
			}
		}, this)
	;

	if (this.notInlineAttachments().length > 0)
	{
		sConfirm += '\r\n\r\n' + Utils.i18n('OPENPGP/CONFIRM_HTML_TO_PLAIN_ATTACHMENTS');
	}

	App.Screens.showPopup(ConfirmPopup, [sConfirm, fOpenPgpEncryptPopup]);
};

/**
 * @param {boolean} bSignAndSend
 */
CComposeViewModel.prototype.openPgpPopup = function (bSignAndSend)
{
	var
		sText = this.oHtmlEditor.getPlainText(),
		fOkCallback = _.bind(function (oSignedEncryptedText, bEncrypted) {
			if (!bSignAndSend)
			{
				this.stopAutosaveTimer();
				this.executeSave(true);
			}
			this.plainText(true);
			this.textBody(oSignedEncryptedText);
			this.pgpSecured(true);
			this.pgpEncrypted(bEncrypted);
			if (bSignAndSend)
			{
				this.executeSend(true);
			}
		}, this),
		fCancelCallback = _.bind(function () {
			if (bSignAndSend)
			{
				this.executeSend(true);
			}
		}, this)
	;

	/*global COpenPgpEncryptPopup:true */
	App.Screens.showPopup(COpenPgpEncryptPopup, [sText, AppData.Accounts.getEmail(this.senderAccountId()), this.recipientEmails(), bSignAndSend, fOkCallback, fCancelCallback]);
	/*global COpenPgpEncryptPopup:true */
};

CComposeViewModel.prototype.undoPgp = function ()
{
	var
		sText = this.textBody(),
		aText = []
	;
	
	if (this.pgpSecured())
	{
		this.plainText(false);
		if (this.fromDrafts() && !this.pgpEncrypted())
		{
			aText = sText.split('-----BEGIN PGP SIGNED MESSAGE-----');
			if (aText.length === 2)
			{
				sText = aText[1];
			}
			
			aText = sText.split('-----BEGIN PGP SIGNATURE-----');
			if (aText.length === 2)
			{
				sText = aText[0];
			}
			
			aText = sText.split('\r\n\r\n');
			if (aText.length > 0)
			{
				aText.shift();
				sText = aText.join('\r\n\r\n');
			}
			
			sText = '<div>' + sText.replace(/\r\n/gi, '<br />') + '</div>';
			
			this.textBody(sText);
		}
		else
		{
			this.oHtmlEditor.undoAndClearRedo();
		}
		this.pgpSecured(false);
		this.pgpEncrypted(false);
	}
};

CComposeViewModel.prototype.getSocialAccounts = function ()
{
	var 
		fConnected = Utils.emptyFunction()
	;

	this.googleConnected(false);
	this.dropboxConnected(false);
	_.each(AppData.User.SocialAccounts(), function (oItem){
		if (oItem['@Object'] === 'Object/CSocial')
		{
			if (_.indexOf(oItem.Scopes, 'filestorage') >= 0 && Utils.isFunc(this[oItem.Type + 'Connected']))
			{
				fConnected = this[oItem.Type + 'Connected'];
				fConnected(true);
			}
		}
	}, this);
};



function CSenderSelector()
{
	this.senderList = ko.observableArray([]);
	
	this.senderAccountId = ko.observable(AppData.Accounts.currentId());
	this.selectedFetcherOrIdentity = ko.observable(null);
	this.lockSelectedSender = ko.observable(false);
	this.selectedSender = ko.observable('');
	this.selectedSender.subscribe(function () {
		if (!this.lockSelectedSender())
		{
			var
				oAccount = AppData.Accounts.getAccount(this.senderAccountId()),
				sId = this.selectedSender(),
				oFetcherOrIdentity = null
			;
			
			if (sId.indexOf('fetcher') === 0)
			{
				if (oAccount.fetchers())
				{
					sId = sId.replace('fetcher', '');
					oFetcherOrIdentity = _.find(oAccount.fetchers().collection(), function (oFtchr) {
						return oFtchr.id() === Utils.pInt(sId);
					});
				}
			}
			else
			{
				oFetcherOrIdentity = _.find(oAccount.identities(), function (oIdnt) {
					return oIdnt.id() === Utils.pInt(sId);
				});
			}
			
			if (oFetcherOrIdentity)
			{
				this.selectedFetcherOrIdentity(oFetcherOrIdentity);
			}
		}
	}, this);
}

CSenderSelector.prototype.changeSelectedSender = function (oFetcherOrIdentity)
{
	if (oFetcherOrIdentity)
	{
		var sSelectedSenderId = Utils.pString(oFetcherOrIdentity.id());

		if (oFetcherOrIdentity.FETCHER)
		{
			sSelectedSenderId = 'fetcher' + sSelectedSenderId;
		}

		if (_.find(this.senderList(), function (oItem) {return oItem.id === sSelectedSenderId;}))
		{
			this.lockSelectedSender(true);
			this.selectedSender(sSelectedSenderId);
			this.selectedFetcherOrIdentity(oFetcherOrIdentity);
			this.lockSelectedSender(false);
		}
	}
};

/**
 * @param {number} iId
 * @param {string=} oFetcherOrIdentity
 */
CSenderSelector.prototype.changeSenderAccountId = function (iId, oFetcherOrIdentity)
{
	var bChanged = false;
	if (this.senderAccountId() !== iId)
	{
		if (AppData.Accounts.hasAccountWithId(iId))
		{
			this.senderAccountId(iId);
			bChanged = true;
		}
		else if (!AppData.Accounts.hasAccountWithId(this.senderAccountId()))
		{
			this.senderAccountId(AppData.Accounts.currentId());
			bChanged = true;
		}
	}
	
	if (bChanged || this.senderList().length === 0)
	{
		this.fillSenderList(oFetcherOrIdentity);
		bChanged = true;
	}
		
	if (!bChanged && oFetcherOrIdentity)
	{
		this.changeSelectedSender(oFetcherOrIdentity);
	}
};

/**
 * @param {string=} oFetcherOrIdentity
 */
CSenderSelector.prototype.fillSenderList = function (oFetcherOrIdentity)
{
	var
		aSenderList = [],
		oAccount = AppData.Accounts.getAccount(this.senderAccountId())
	;

	if (oAccount)
	{
		if (_.isArray(oAccount.identities()))
		{
			_.each(oAccount.identities(), function (oIdentity) {
				if (oIdentity.enabled())
				{
					aSenderList.push({fullEmail: oIdentity.fullEmail(), id: Utils.pString(oIdentity.id())});
				}
			}, this);
		}

		if (!oAccount.identitiesSubscribtion)
		{
			oAccount.identitiesSubscribtion = oAccount.identities.subscribe(function (aIdentities) {
				this.fillSenderList(oFetcherOrIdentity);
				this.changeSelectedSender(oAccount.getDefaultIdentity());
			}, this);
		}

		if (oAccount.fetchers())
		{
			_.each(oAccount.fetchers().collection(), function (oFetcher) {
				var sFullEmail = oFetcher.fullEmail();
				if (oFetcher.isOutgoingEnabled() && sFullEmail.length > 0)
				{
					aSenderList.push({fullEmail: sFullEmail, id: 'fetcher' + oFetcher.id()});
				}
			}, this);
		}
		else if (!oAccount.fetchersSubscribtion)
		{
			oAccount.fetchersSubscribtion = oAccount.fetchers.subscribe(function () {
				this.fillSenderList(oFetcherOrIdentity);
			}, this);
		}
	}

	this.senderList(aSenderList);

	this.changeSelectedSender(oFetcherOrIdentity);
};

/**
 * @param {Object} oMessage
 */
CSenderSelector.prototype.setFetcherOrIdentityByReplyMessage = function (oMessage)
{
	var
		aRecipients = oMessage.oTo.aCollection.concat(oMessage.oCc.aCollection),
		oFetcherOrIdentity = App.MessageSender.getFirstFetcherOrIdentityByRecipientsOrDefault(aRecipients, oMessage.accountId())
	;
	
	if (oFetcherOrIdentity)
	{
		this.changeSelectedSender(oFetcherOrIdentity);
	}
};


/**
 * @param {CContactsViewModel} oParent
 * @constructor
 */
function CContactsImportViewModel(oParent)
{
	this.oJua = null;
	this.oParent = oParent;

	this.visibility = ko.observable(false);
	this.importing = ko.observable(false);
}

/**
 * @param {Object} $oViewModel
 */
CContactsImportViewModel.prototype.onApplyBindings = function ($oViewModel)
{
	this.oJua = new Jua({
		'action': '?/Upload/Contacts/',
		'name': 'jua-uploader',
		'queueSize': 1,
		'clickElement': $('#jue_import_button', $oViewModel),
		'hiddenElementsPosition': Utils.isRTL() ? 'right' : 'left',
		'disableAjaxUpload': false,
		'disableDragAndDrop': true,
		'disableMultiple': true,
		'hidden': {
			'Token': function () {
				return AppData.Token;
			},
			'AccountID': function () {
				return AppData.Accounts.currentId();
			}
		}
	});

	this.oJua
		.on('onStart', _.bind(this.onFileUploadStart, this))
		.on('onComplete', _.bind(this.onFileUploadComplete, this))
	;
};

CContactsImportViewModel.prototype.onFileUploadStart = function ()
{
	this.importing(true);
};

/**
 * @param {string} sFileUid
 * @param {boolean} bResponseReceived
 * @param {Object} oResponse
 */
CContactsImportViewModel.prototype.onFileUploadComplete = function (sFileUid, bResponseReceived, oResponse)
{
	var
		bError = !bResponseReceived || !oResponse || oResponse.Error|| oResponse.Result.Error || false,
		iImportedCount = 0
		;

	this.importing(false);
	this.oParent.requestContactList();

	if (!bError)
	{
		iImportedCount = Utils.pInt(oResponse.Result.ImportedCount);

		if (0 < iImportedCount)
		{
			App.Api.showReport(Utils.i18n('CONTACTS/CONTACT_IMPORT_HINT_PLURAL', {
				'NUM': iImportedCount
			}, null, iImportedCount));
		}
		else
		{
			App.Api.showError(Utils.i18n('WARNING/CONTACTS_IMPORT_NO_CONTACTS'));
		}
	}
	else
	{
		if (oResponse.ErrorCode && oResponse.ErrorCode === Enums.Errors.IncorrectFileExtension)
		{
			App.Api.showError(Utils.i18n('CONTACTS/ERROR_INCORRECT_FILE_EXTENSION'));
		}
		else
		{
			App.Api.showError(Utils.i18n('WARNING/ERROR_UPLOAD_FILE'));
		}
	}
};

/**
 * @constructor
 */
function CContactsViewModel()
{
	this.isPublic = bExtApp;

	this.uploaderArea = ko.observable(null);
	this.bDragActive = ko.observable(false);
	this.bDragActiveComp = ko.computed(function () {
		return this.bDragActive();
	}, this);

	this.importingHelpLink = App.getHelpLink('ImportingContacts');
	this.allowWebMail = AppData.App.AllowWebMail;
	this.loadingList = ko.observable(false);
	this.preLoadingList = ko.observable(false);
	this.loadingList.subscribe(function (bLoading) {
		this.preLoadingList(bLoading);
	}, this);
	this.loadingViewPane = ko.observable(false);
	
	this.showPersonalContacts = ko.observable(false);
	this.showGlobalContacts = ko.observable(false);
	this.showSharedToAllContacts = ko.observable(false);

	this.showAllContacts = ko.computed(function () {
		return 1 < [this.showPersonalContacts() ? '1' : '',
			this.showGlobalContacts() ? '1' : '',
			this.showSharedToAllContacts() ? '1' : ''
		].join('').length;
	}, this);

	this.recivedAnimShare = ko.observable(false).extend({'autoResetToFalse': 500});
	this.recivedAnimUnshare = ko.observable(false).extend({'autoResetToFalse': 500});

	this.isGlobalGroupSelect = ko.observable(true);
	this.isAllOrSubOrGlobalGroupSelect = ko.observable(true);
	this.selectedGroupType = ko.observable(Enums.ContactsGroupListType.Personal);
	this.selectedGroupType.subscribe(function (sGroup) {
		this.isGlobalGroupSelect(sGroup === Enums.ContactsGroupListType.Global);
		this.isAllOrSubOrGlobalGroupSelect(sGroup === Enums.ContactsGroupListType.SubGroup || sGroup === Enums.ContactsGroupListType.Global || sGroup === Enums.ContactsGroupListType.All);
	}, this);

	this.selectedGroupInList = ko.observable(null);

	this.selectedGroupInList.subscribe(function () {
		var oPrev = this.selectedGroupInList();
		if (oPrev)
		{
			oPrev.selected(false);
		}
	}, this, 'beforeChange');

	this.selectedGroupInList.subscribe(function (oGroup) {
		if (oGroup && this.showPersonalContacts())
		{
			oGroup.selected(true);

			this.selectedGroupType(Enums.ContactsGroupListType.SubGroup);

			this.requestContactList();
		}
	}, this);

	this.selectedGroup = ko.observable(null);
	this.selectedContact = ko.observable(null);
	this.selectedGroupContactsList = ko.observable(null);
	
	this.currentGroupId = ko.observable(0);

	this.oContactModel = new CContactModel();
	this.oGroupModel = new CGroupModel();

	this.oContactImportViewModel = new CContactsImportViewModel(this);

	this.selectedOldItem = ko.observable(null);
	this.selectedItem = ko.computed({
		'read': function () {
			return this.selectedContact() || this.selectedGroup() || null;
		},
		'write': function (oItem) {
			if (oItem instanceof CContactModel)
			{
				this.oContactImportViewModel.visibility(false);
				this.selectedGroup(null);
				this.selectedContact(oItem);
			}
			else if (oItem instanceof CGroupModel)
			{
				this.oContactImportViewModel.visibility(false);
				this.selectedContact(null);
				this.selectedGroup(oItem);
				this.currentGroupId(oItem.idGroup());
			}
			else
			{
				this.selectedGroup(null);
				this.selectedContact(null);
			}

			this.loadingViewPane(false);
		},
		'owner': this
	});

	this.sortOrder = ko.observable(true);
	this.sortType = ko.observable(Enums.ContactSortType.Name);

	this.collection = ko.observableArray([]);
	this.contactUidForRequest = ko.observable('');
	this.collection.subscribe(function () {
		if (this.collection().length > 0 && this.contactUidForRequest() !== '')
		{
			this.requestContact(this.contactUidForRequest());
			this.contactUidForRequest('');
		}
	}, this);
	
	this.isSearchFocused = ko.observable(false);
	this.searchInput = ko.observable('');
	this.search = ko.observable('');

	this.groupFullCollection = ko.observableArray([]);

	this.selectedContact.subscribe(function (oContact) {
		if (oContact)
		{
			var aGroupsId = oContact.groups();
			_.each(this.groupFullCollection(), function (oItem) {
				oItem.checked(oItem && 0 <= Utils.inArray(oItem.Id(), aGroupsId));
			});
		}
	}, this);

	this.selectedGroupType.subscribe(function (iValue) {

		if (Enums.ContactsGroupListType.All === iValue && !this.showAllContacts())
		{
			this.selectedGroupType(Enums.ContactsGroupListType.Personal);
		}
		else if (Enums.ContactsGroupListType.Personal === iValue && !this.showPersonalContacts() && this.showGlobalContacts())
		{
			this.selectedGroupType(Enums.ContactsGroupListType.Global);
		}
		else if (Enums.ContactsGroupListType.Global === iValue && !this.showGlobalContacts() && this.showPersonalContacts())
		{
			this.selectedGroupType(Enums.ContactsGroupListType.Personal);
		}
		else if (Enums.ContactsGroupListType.Personal === iValue || Enums.ContactsGroupListType.Global === iValue || 
				Enums.ContactsGroupListType.SharedToAll === iValue || Enums.ContactsGroupListType.All === iValue)
		{
			this.selectedGroupInList(null);
			this.selectedItem(null);
			this.selector.listCheckedOrSelected(false);
			this.requestContactList();
		}
	}, this);

	this.pageSwitcherLocked = ko.observable(false);
	this.oPageSwitcher = new CPageSwitcherViewModel(0, AppData.User.ContactsPerPage);
	this.oPageSwitcher.currentPage.subscribe(function () {
		var
			iType = this.selectedGroupType(),
			sGroupId = (iType === Enums.ContactsGroupListType.SubGroup) ? this.currentGroupId() : ''
		;
		
		if (!this.pageSwitcherLocked())
		{
			App.Routing.setHash(App.Links.contacts(iType, sGroupId, this.search(), this.oPageSwitcher.currentPage()));
		}
	}, this);
	this.currentPage = ko.observable(1);

	this.search.subscribe(function (sValue) {
		this.searchInput(sValue);
	}, this);

	this.searchSubmitCommand = Utils.createCommand(this, function () {
		var
			iType = this.selectedGroupType(),
			sGroupId = (iType === Enums.ContactsGroupListType.SubGroup) ? this.currentGroupId() : ''
		;
		
		App.Routing.setHash(App.Links.contacts(iType, sGroupId, this.searchInput()));
	});

	this.selector = new CSelector(this.collection, _.bind(function (oItem) {
		if (oItem)
		{
			var
				iType = this.selectedGroupType(),
				sGroupId = (iType === Enums.ContactsGroupListType.SubGroup) ? this.currentGroupId() : ''
			;
			App.Routing.setHash(App.Links.contacts(iType, sGroupId, this.search(), this.oPageSwitcher.currentPage(), oItem.sId));
		}
	}, this), _.bind(this.executeDelete, this), _.bind(this.onContactDblClick, this));

	this.checkAll = this.selector.koCheckAll();
	this.checkAllIncomplite = this.selector.koCheckAllIncomplete();

	this.allowContactsSharing = !!AppData.App.AllowContactsSharing;

	this.isCheckedOrSelected = ko.computed(function () {
		return 0 < this.selector.listCheckedOrSelected().length;
	}, this);
	this.isEnableAddContacts = this.isCheckedOrSelected;
	this.isEnableRemoveContactsFromGroup = this.isCheckedOrSelected;
	this.isEnableDeleting = this.isCheckedOrSelected;
	this.isEnableSharing = this.isCheckedOrSelected;
	this.visibleShareCommand = ko.computed(function () {
		return this.showPersonalContacts() && this.showSharedToAllContacts() && 
				(this.selectedGroupType() === Enums.ContactsGroupListType.Personal);
	}, this);
	this.visibleUnshareCommand = ko.computed(function () {
		return this.showPersonalContacts() && this.showSharedToAllContacts() && 
				(this.selectedGroupType() === Enums.ContactsGroupListType.SharedToAll);
	}, this);
	this.isSelectedGroupTypeNotGlobal = ko.computed(function () {
		return this.selectedGroupType() !== Enums.ContactsGroupListType.Global;
	}, this);

	/*this.canBeSave = ko.computed(function () {
		var oItem = this.selectedItem();
		return oItem ? oItem.canBeSave() : false;
	}, this);*/

	this.newContactCommand = Utils.createCommand(this, this.executeNewContact, this.isSelectedGroupTypeNotGlobal);
	this.newGroupCommand = Utils.createCommand(this, this.executeNewGroup);
	this.addContactsCommand = Utils.createCommand(this, Utils.emptyFunction, this.isEnableAddContacts);
	this.deleteCommand = Utils.createCommand(this, this.executeDelete, this.isEnableDeleting);
	this.shareCommand = Utils.createCommand(this, this.executeShare, this.isEnableSharing);
	this.removeFromGroupCommand = Utils.createCommand(this, this.executeRemoveFromGroup, this.isEnableRemoveContactsFromGroup);
	this.importCommand = Utils.createCommand(this, this.executeImport);
	this.exportCSVCommand = Utils.createCommand(this, this.executeCSVExport);
	this.exportVCFCommand = Utils.createCommand(this, this.executeVCFExport);
	this.saveCommand = Utils.createCommand(this, this.executeSave);
	this.updateSharedToAllCommand = Utils.createCommand(this, this.executeUpdateSharedToAll, function () {
		return (1 === this.selector.listCheckedOrSelected().length);
	});

	this.newMessageCommand = Utils.createCommand(this, function () {
		
		var 
			aList = this.selector.listCheckedOrSelected(),
			aText = []
		;
		
		if (_.isArray(aList) && 0 < aList.length)
		{
			aText = _.map(aList, function (oItem) {
				return oItem.EmailAndName();
			});

			aText = _.compact(aText);
			App.Api.composeMessageToAddresses(aText.join(', '));
		}

	}, function () {
		return 0 < this.selector.listCheckedOrSelected().length;
	});

	this.selector.listCheckedOrSelected.subscribe(function (aList) {
		this.oGroupModel.newContactsInGroupCount(aList.length);
	}, this);

	this.isSearch = ko.computed(function () {
		return this.search() !== '';
	}, this);
	this.isEmptyList = ko.computed(function () {
		return 0 === this.collection().length;
	}, this);
	this.inGroup = ko.computed(function () {
		return Enums.ContactsGroupListType.SubGroup === this.selectedGroupType();
	}, this);

	this.searchText = ko.computed(function () {
		return Utils.i18n('CONTACTS/INFO_SEARCH_RESULT', {
			'SEARCH': this.search()
		});
	}, this);
	
	this.mobileApp = bMobileApp;
	this.selectedPanel = ko.observable(Enums.MobilePanel.Items);
	this.selectedItem.subscribe(function () {
		
		var bViewGroup = this.selectedItem() && this.selectedItem() instanceof CGroupModel &&
				!this.selectedItem().isNew();
		
		if (this.selectedItem() && !bViewGroup)
		{
			this.gotoViewPane();
		}
		else
		{
			this.gotoContactList();
		}
	}, this);
}

/**
 * 
 * @param {?} mValue
 * @param {Object} oElement
 */
CContactsViewModel.prototype.groupDropdownToggle = function (mValue, oElement) {
	this.currentGroupDropdown(mValue);
};

CContactsViewModel.prototype.gotoGroupList = function ()
{
	this.changeSelectedPanel(Enums.MobilePanel.Groups);
};

CContactsViewModel.prototype.gotoContactList = function ()
{
	this.changeSelectedPanel(Enums.MobilePanel.Items);
	return true;
};

CContactsViewModel.prototype.gotoViewPane = function ()
{
	this.changeSelectedPanel(Enums.MobilePanel.View);
};

CContactsViewModel.prototype.backToContactList = function ()
{
	var
		iType = this.selectedGroupType(),
		sGroupId = (iType === Enums.ContactsGroupListType.SubGroup) ? this.currentGroupId() : ''
	;

	App.Routing.setHash(App.Links.contacts(iType, sGroupId, this.search(), this.oPageSwitcher.currentPage()));
};

/**
 * @param {number} iPanel
 */
CContactsViewModel.prototype.changeSelectedPanel = function (iPanel)
{
	if (this.mobileApp)
	{
		this.selectedPanel(iPanel);
	}
};

/**
 * @param {Object} oData
 */
CContactsViewModel.prototype.executeSave = function (oData)
{
	var
		oResult = {},
		aList = []
	;

	if (oData === this.selectedItem() && this.selectedItem().canBeSave())
	{
		if (oData instanceof CContactModel && !oData.readOnly())
		{
			_.each(this.groupFullCollection(), function (oItem) {
				if (oItem && oItem.checked())
				{
					aList.push(oItem.Id());
				}
			});

			oData.groups(aList);

			oResult = oData.toObject();
			oResult.Action = oData.isNew() ? 'ContactCreate' : 'ContactUpdate';
			
			if (oData.isNew())
			{
				oResult.SharedToAll = (Enums.ContactsGroupListType.SharedToAll === this.selectedGroupType()) ? '1' : '0';
			}
			else
			{
				oResult.SharedToAll = oData.sharedToAll() ? '1' : '0';
			}

			if (oData.edited())
			{
				oData.edited(false);
			}

			if (oData.isNew())
			{
				this.selectedItem(null);
			}

			if (this.selectedGroupType() === Enums.ContactsGroupListType.Global || this.selectedGroupType() === Enums.ContactsGroupListType.All) {
				this.recivedAnimUnshare(true);
			}

			App.Ajax.send(oResult, this.onContactCreateResponse, this);
		}
		else if (oData instanceof CGroupModel && !oData.readOnly())
		{
			oResult = oData.toObject();
			oResult.Action = oData.isNew() ? 'ContactsGroupCreate' : 'ContactsGroupUpdate';

			this.gotoGroupList();
			
			if (oData.edited())
			{
				oData.edited(false);
			}

			if (oData.isNew() && !this.mobileApp)
			{
				this.selectedItem(null);
			}

			App.Ajax.send(oResult, this.onGroupCreateResponse, this);
		}
	}
	else
	{
		App.Api.showError(Utils.i18n('CONTACTS/ERROR_EMPTY_CONTACT'));
	}
};

CContactsViewModel.prototype.executeNewContact = function ()
{
	if (this.showPersonalContacts()) {
		var oGr = this.selectedGroupInList();
		this.oContactModel.switchToNew();
		this.oContactModel.groups(oGr ? [oGr.Id()] : []);
		this.selectedItem(this.oContactModel);
		this.selector.itemSelected(null);
		this.gotoViewPane();
	}
};

CContactsViewModel.prototype.executeNewGroup = function ()
{
	this.oGroupModel.switchToNew();
	this.selectedItem(this.oGroupModel);
	this.selector.itemSelected(null);
	this.gotoViewPane();
};

CContactsViewModel.prototype.executeDelete = function ()
{
	var iGroupType = this.selectedGroupType();
	if (iGroupType === Enums.ContactsGroupListType.Personal || iGroupType === Enums.ContactsGroupListType.SharedToAll)
	{
		var
			aChecked = _.filter(this.selector.listCheckedOrSelected(), function (oItem) {
				return !oItem.ReadOnly();
			}),
			iCount = aChecked.length,
			sConfirmText = Utils.i18n('CONTACTS/CONFIRM_DELETE_CONTACT_PLURAL', {}, null, iCount),
			fDeleteContacts = _.bind(function (bResult) {
				if (bResult) {
					this.deleteContacts(aChecked);
				}
			}, this)
			;

		App.Screens.showPopup(ConfirmPopup, [sConfirmText, fDeleteContacts]);
	}
	else if (iGroupType === Enums.ContactsGroupListType.SubGroup)
	{
		this.removeFromGroupCommand();
	}
};

CContactsViewModel.prototype.deleteContacts = function (aChecked)
{
	var
		self = this,
		oMainContact = this.selectedContact(),
		aContactsId = _.map(aChecked, function (oItem) {
			return oItem.Id();
		})
	;

	if (0 < aContactsId.length)
	{
		this.preLoadingList(true);

		_.each(aChecked, function (oContact) {
			if (oContact)
			{
				App.ContactsCache.clearInfoAboutEmail(oContact.Email());

				if (oMainContact && !oContact.IsGroup() && !oContact.ReadOnly() && !oMainContact.readOnly() && oMainContact.idContact() === oContact.Id())
				{
					oMainContact = null;
					this.selectedContact(null);
				}
			}
		}, this);

		_.each(this.collection(), function (oContact) {
			if (-1 < Utils.inArray(oContact, aChecked))
			{
				oContact.deleted(true);
			}
		});

		_.delay(function () {
			self.collection.remove(function (oItem) {
				return oItem.deleted();
			});
		}, 500);

		App.Ajax.send({
			'Action': 'ContactDelete',
			'ContactsId': aContactsId.join(','),
			'SharedToAll': (Enums.ContactsGroupListType.SharedToAll === this.selectedGroupType()) ? '1' : '0'
		}, this.requestContactList, this);
		
		App.ContactsCache.markVcardsNonexistentByUid(aContactsId);
	}
};

CContactsViewModel.prototype.executeRemoveFromGroup = function ()
{
	var
		self = this,
		oGroup = this.selectedGroupInList(),
		aChecked = this.selector.listCheckedOrSelected(),
		aContactsId = _.map(aChecked, function (oItem) {
			return oItem.ReadOnly() ? '' : oItem.Id();
		})
	;

	aContactsId = _.compact(aContactsId);

	if (oGroup && 0 < aContactsId.length)
	{
		this.preLoadingList(true);

		_.each(this.collection(), function (oContact) {
			if (-1 < Utils.inArray(oContact, aChecked))
			{
				oContact.deleted(true);
			}
		});

		_.delay(function () {
			self.collection.remove(function (oItem) {
				return oItem.deleted();
			});
		}, 500);

		App.Ajax.send({
			'Action': 'ContactsRemoveFromGroup',
			'GroupId': oGroup.Id(),
			'ContactsId': aContactsId.join(',')
		}, this.requestContactList, this);
	}
};

CContactsViewModel.prototype.executeImport = function ()
{
	this.selectedItem(null);
	this.oContactImportViewModel.visibility(true);
	this.selector.itemSelected(null);
	this.selectedGroupType(Enums.ContactsGroupListType.Personal);
	this.gotoViewPane();
};

CContactsViewModel.prototype.executeCSVExport = function ()
{
	App.Api.downloadByUrl(Utils.getExportContactsLink('csv'));
};

CContactsViewModel.prototype.executeVCFExport = function ()
{
	App.Api.downloadByUrl(Utils.getExportContactsLink('vcf'));
};

CContactsViewModel.prototype.executeCancel = function ()
{
	var
		oData = this.selectedItem()
	;

	if (oData)
	{
		if (oData instanceof CContactModel && !oData.readOnly())
		{
			if (oData.isNew())
			{
				this.selectedItem(null);
			}
			else if (oData.edited())
			{
				oData.edited(false);
			}
		}
		else if (oData instanceof CGroupModel && !oData.readOnly())
		{
			if (oData.isNew())
			{
				this.selectedItem(null);
			}
			else if (oData.edited())
			{
				this.selectedItem(this.selectedOldItem());
				oData.edited(false);
			}
			this.gotoGroupList();
		}
	}

	this.oContactImportViewModel.visibility(false);
};

/**
 * @param {Object} oGroup
 * @param {Array} aContactIds
 */
CContactsViewModel.prototype.executeAddContactsToGroup = function (oGroup, aContactIds)
{
	if (oGroup && _.isArray(aContactIds) && 0 < aContactIds.length)
	{
		oGroup.recivedAnim(true);

		this.executeAddContactsToGroupId(oGroup.Id(), aContactIds);
	}
};

/**
 * @param {number} iGroupId
 * @param {Array} aContactIds
 */
CContactsViewModel.prototype.executeAddContactsToGroupId = function (iGroupId, aContactIds)
{
	if (iGroupId && _.isArray(aContactIds) && 0 < aContactIds.length)
	{
		App.Ajax.send({
			'Action': 'ContactsAddToGroup',
			'GroupId': iGroupId,
			'ContactsId': aContactIds
		}, this.onContactsAddToGroupResponse, this);
	}
};

CContactsViewModel.prototype.onContactsAddToGroupResponse = function () {
	this.requestContactList();
	if (this.selector.itemSelected())
	{
		this.requestContact(this.selector.itemSelected().sId);
	}
};

/**
 * @param {Object} oGroup
 */
CContactsViewModel.prototype.executeAddSelectedContactsToGroup = function (oGroup)
{
	var
		aList = this.selector.listCheckedOrSelected(),
		aContactIds = []
	;

	if (oGroup && _.isArray(aList) && 0 < aList.length)
	{
		_.each(aList, function (oItem) {
			if (oItem && !oItem.IsGroup())
			{
				aContactIds.push([oItem.Id(), oItem.Global() ? '1' : '0']);
			}
		}, this);
	}

	this.executeAddContactsToGroup(oGroup, aContactIds);
};

/**
 * @param {Object} oContact
 */
CContactsViewModel.prototype.groupsInContactView = function (oContact)
{
	var
		aResult = [],
		aGroupIds = []
	;

	if (oContact && !oContact.groupsIsEmpty())
	{
		aGroupIds = oContact.groups();
		aResult = _.filter(this.groupFullCollection(), function (oItem) {
			return 0 <= Utils.inArray(oItem.Id(), aGroupIds);
		});
	}

	return aResult;
};

CContactsViewModel.prototype.onShow = function ()
{
	this.selector.useKeyboardKeys(true);
	
	this.oPageSwitcher.show();
	this.oPageSwitcher.perPage(AppData.User.ContactsPerPage);

	if (this.oJua)
	{
		this.oJua.setDragAndDropEnabledStatus(true);
	}
};

CContactsViewModel.prototype.onHide = function ()
{
	this.selector.listCheckedOrSelected(false);
	this.selector.useKeyboardKeys(false);
	this.selectedItem(null);
	
	this.oPageSwitcher.hide();

	if (this.oJua)
	{
		this.oJua.setDragAndDropEnabledStatus(false);
	}
};

CContactsViewModel.prototype.onApplyBindings = function ()
{
	this.selector.initOnApplyBindings(
		'.contact_sub_list .item',
		'.contact_sub_list .selected.item',
		'.contact_sub_list .item .custom_checkbox',
		$('.contact_list', this.$viewModel),
		$('.contact_list_scroll.scroll-inner', this.$viewModel)
	);

	var self = this;

	this.$viewModel.on('click', '.content .item.add_to .dropdown_helper .item', function () {

		if ($(this).hasClass('new-group'))
		{
			self.executeNewGroup();
		}
		else
		{
			self.executeAddSelectedContactsToGroup(ko.dataFor(this));
		}
	});

	this.showPersonalContacts(!!AppData.User.ShowPersonalContacts);
	this.showGlobalContacts(!!AppData.User.ShowGlobalContacts);
	this.showSharedToAllContacts(!!AppData.App.AllowContactsSharing);
	
	this.selectedGroupType.valueHasMutated();
	
	this.oContactImportViewModel.onApplyBindings(this.$viewModel);
	this.requestGroupFullList();

	this.hotKeysBind();

	this.initUploader();
};

CContactsViewModel.prototype.hotKeysBind = function ()
{
	var bFirstContactFlag = false;

	$(document).on('keydown', _.bind(function(ev) {
		var
			nKey = ev.keyCode,
			oFirstContact = this.collection()[0],
			bListIsFocused = this.isSearchFocused(),
			bFirstContactSelected = false,
			bIsContactsScreen = App.Screens.currentScreen() === Enums.Screens.Contacts
		;

		if (bIsContactsScreen && !Utils.isTextFieldFocused() && !bListIsFocused && ev && nKey === Enums.Key.s)
		{
			ev.preventDefault();
			this.searchFocus();
		}

		else if (oFirstContact)
		{
			bFirstContactSelected = oFirstContact.selected();

			if (oFirstContact && bListIsFocused && ev && nKey === Enums.Key.Down)
			{
				this.isSearchFocused(false);
				this.selector.itemSelected(oFirstContact);

				bFirstContactFlag = true;
			}
			else if (!bListIsFocused && bFirstContactFlag && bFirstContactSelected && ev && nKey === Enums.Key.Up)
			{
				this.isSearchFocused(true);
				this.selector.itemSelected(false);
				
				bFirstContactFlag = false;
			}
			else if (bFirstContactSelected)
			{
				bFirstContactFlag = true;
			}
			else if (!bFirstContactSelected)
			{
				bFirstContactFlag = false;
			}
		}
	}, this));
};

CContactsViewModel.prototype.requestContactList = function ()
{
	this.loadingList(true);

	App.Ajax.send({
		'Action': (Enums.ContactsGroupListType.Global === this.selectedGroupType()) ? 'ContactGlobalList' : 'ContactList',
		'Offset': (this.oPageSwitcher.currentPage() - 1) * AppData.User.ContactsPerPage,
		'Limit': AppData.User.ContactsPerPage,
		'SortField': this.sortType(),
		'SortOrder': this.sortOrder() ? '1' : '0',
		'Search': this.search(),
		'GroupId': this.selectedGroupInList() ? this.selectedGroupInList().Id() : '',
		'SharedToAll': (Enums.ContactsGroupListType.SharedToAll === this.selectedGroupType()) ? '1' : '0',
		'All': (Enums.ContactsGroupListType.All === this.selectedGroupType()) ? '1' : '0'
	}, this.onContactListResponse, this);
};

CContactsViewModel.prototype.requestGroupFullList = function ()
{
	App.Ajax.send({
		'Action': 'ContactsGroupFullList'
	}, this.onGroupListResponse, this);
};

/**
 * @param {string} sUid
 */
CContactsViewModel.prototype.requestContact = function (sUid)
{
	this.loadingViewPane(true);
	
	var oItem = _.find(this.collection(), function (oItm) {
		return oItm.sId === sUid;
	});
	
	if (oItem)
	{
		this.selector.itemSelected(oItem);
		App.Ajax.send({
			'Action': oItem.Global() ? 'ContactGlobal' : 'ContactGet',
			'ContactId': oItem.Id(),
			'SharedToAll': oItem.IsSharedToAll() ? '1' : '0'
		}, this.onContactGetResponse, this);
	}
};

/**
 * @param {string} sItemId
 * @param {boolean} bGlobal
 */
CContactsViewModel.prototype.requestContactById = function (sItemId, bGlobal)
{
	this.loadingViewPane(true);

	if (sItemId)
	{
		App.Ajax.send({
			'Action': bGlobal ? 'ContactGlobal' : 'ContactGet',
			'ContactId': sItemId
		}, this.onContactGetResponse, this);
	}
};

/**
 * @param {Object} oData
 */
CContactsViewModel.prototype.editGroup = function (oData)
{
	var oGroup = new CGroupModel();
	oGroup.populate(oData);
	this.selectedOldItem(oGroup);
	oData.edited(true);
};

/**
 * @param {number} iType
 */
CContactsViewModel.prototype.changeGroupType = function (iType)
{
	App.Routing.setHash(App.Links.contacts(iType));
};

/**
 * @param {Object} oData
 */
CContactsViewModel.prototype.onViewGroupClick = function (oData)
{
	App.Routing.setHash(App.Links.contacts(Enums.ContactsGroupListType.SubGroup, oData.Id()));
};

/**
 * @param {Array} aParams
 */
CContactsViewModel.prototype.onRoute = function (aParams)
{
	var
		oParams = App.Links.parseContacts(aParams),
		aGroupTypes = [Enums.ContactsGroupListType.Personal, Enums.ContactsGroupListType.SharedToAll, Enums.ContactsGroupListType.Global, Enums.ContactsGroupListType.All],
		sCurrentGroupId = (this.selectedGroupType() === Enums.ContactsGroupListType.SubGroup) ?  this.currentGroupId() : '',
		bGroupOrSearchChanged = this.selectedGroupType() !== oParams.Type || sCurrentGroupId !== oParams.GroupId || this.search() !== oParams.Search,
		bGroupFound = true,
		bRequestContacts = false
	;
	
	this.pageSwitcherLocked(true);
	if (bGroupOrSearchChanged)
	{
		this.oPageSwitcher.clear();
	}
	else
	{
		this.oPageSwitcher.setPage(oParams.Page, AppData.User.ContactsPerPage);
	}
	this.pageSwitcherLocked(false);
	if (oParams.Page !== this.oPageSwitcher.currentPage())
	{
		App.Routing.replaceHash(App.Links.contacts(oParams.Type, oParams.GroupId, oParams.Search, this.oPageSwitcher.currentPage()));
	}
	if (this.currentPage() !== oParams.Page)
	{
		this.currentPage(oParams.Page);
		bRequestContacts = true;
	}
	
	if (-1 !== Utils.inArray(oParams.Type, aGroupTypes))
	{
		this.selectedGroupType(oParams.Type);
	}
	else if (sCurrentGroupId !== oParams.GroupId)
	{
		bGroupFound = this.viewGroup(oParams.GroupId);
		if (bGroupFound)
		{
			bRequestContacts = false;
		}
		else
		{
			App.Routing.replaceHash(App.Links.contacts());
		}
	}
	
	if (this.search() !== oParams.Search)
	{
		this.search(oParams.Search);
		bRequestContacts = true;
	}
	
	this.contactUidForRequest('');
	if (oParams.Uid)		
	{
		if (this.collection().length === 0)
		{
			this.contactUidForRequest(oParams.Uid);
		}
		else
		{
			this.requestContact(oParams.Uid);
		}
	}
	else
	{
		this.selector.itemSelected(null);
		this.gotoContactList();
	}

	if (bRequestContacts)
	{
		this.requestContactList();
	}
};

/**
 * @param {string} sGroupId
 */
CContactsViewModel.prototype.viewGroup = function (sGroupId)
{
	var
		oGroup = _.find(this.groupFullCollection(), function (oItem) {
			return oItem && oItem.Id() === sGroupId;
		})
	;

	if (oGroup)
	{
		this.oGroupModel.clear();
		this.oGroupModel
			.idGroup(oGroup.Id())
			.name(oGroup.Name())
		;
		if (oGroup.IsOrganization())
		{
			this.requestGroup(oGroup);
		}

		this.selectedGroupInList(oGroup);
		this.selectedItem(this.oGroupModel);
		this.selector.itemSelected(null);
		this.selector.listCheckedOrSelected(false);
		
		App.Ajax.send({
			'Action': 'ContactsGroupEvents',
			'GroupId': sGroupId
		}, this.onGroupEventsResponse, this);
	}
	
	return !!oGroup;
};

/**
 * @param {string} sGroupId
 */
CContactsViewModel.prototype.deleteGroup = function (sGroupId)
{
	if (sGroupId)
	{
		App.Ajax.send({
			'Action': 'ContactsGroupDelete',
			'GroupId': sGroupId
		}, this.requestGroupFullList, this);

		this.selectedGroupType(Enums.ContactsGroupListType.Personal);

		this.groupFullCollection.remove(function (oItem) {
			return oItem && oItem.Id() === sGroupId;
		});
	}
};

/**
 * @param {Object} oGroup
 */
CContactsViewModel.prototype.mailGroup = function (oGroup)
{
	if (oGroup)
	{
		App.Ajax.send({
			'Action': 'ContactList',
			'Offset': 0,
			'Limit': 99,
			'SortField': Enums.ContactSortType.Email,
			'SortOrder': true ? '1' : '0',
			'GroupId': oGroup.idGroup()
		}, function (oData) {

			if (oData && oData['Result'] && oData['Result']['List'])
			{
				var
					iIndex = 0,
					iLen = 0,
					aText = [],
					oObject = null,
					aList = [],
					aResultList = oData['Result']['List']
					;

				for (iLen = aResultList.length; iIndex < iLen; iIndex++)
				{
					if (aResultList[iIndex] && 'Object/CContactListItem' === Utils.pExport(aResultList[iIndex], '@Object', ''))
					{
						oObject = new CContactListItemModel();
						oObject.parse(aResultList[iIndex]);

						aList.push(oObject);
					}
				}

				aText = _.map(aList, function (oItem) {
					return oItem.EmailAndName();
				});

				aText = _.compact(aText);
				App.Api.composeMessageToAddresses(aText.join(', '));
			}

		}, this);
	}
};

/**
 * @param {Object} oContact
 */
CContactsViewModel.prototype.dragAndDropHelper = function (oContact)
{
	if (oContact)
	{
		oContact.checked(true);
	}

	var
		oSelected = this.selector.itemSelected(),
		oHelper = Utils.draggableMessages(),
		nCount = this.selector.listCheckedOrSelected().length,
		aUids = 0 < nCount ? _.map(this.selector.listCheckedOrSelected(), function (oItem) {
			return [oItem.Id(), oItem.Global() ? '1' : '0'];
		}) : []
	;

	if (oSelected && !oSelected.checked())
	{
		oSelected.checked(true);
	}

	oHelper.data('p7-contatcs-type', this.selectedGroupType());
	oHelper.data('p7-contatcs-uids', aUids);
	
	$('.count-text', oHelper).text(Utils.i18n('CONTACTS/DRAG_TEXT_PLURAL', {
		'COUNT': nCount
	}, null, nCount));

	return oHelper;
};

/**
 * @param {Object} oToGroup
 * @param {Object} oEvent
 * @param {Object} oUi
 */
CContactsViewModel.prototype.contactsDrop = function (oToGroup, oEvent, oUi)
{
	if (oToGroup)
	{
		var
			oHelper = oUi && oUi.helper ? oUi.helper : null,
			aUids = oHelper ? oHelper.data('p7-contatcs-uids') : null
		;

		if (null !== aUids)
		{
			Utils.uiDropHelperAnim(oEvent, oUi);
			this.executeAddContactsToGroup(oToGroup, aUids);
		}
	}
};

CContactsViewModel.prototype.contactsDropToGroupType = function (iGroupType, oEvent, oUi)
{
	var
		oHelper = oUi && oUi.helper ? oUi.helper : null,
		iType = oHelper ? oHelper.data('p7-contatcs-type') : null,
		aUids = oHelper ? oHelper.data('p7-contatcs-uids') : null
	;

	if (iGroupType !== iType)
	{
		if (null !== iType && null !== aUids)
		{
			Utils.uiDropHelperAnim(oEvent, oUi);
			this.executeShare();
		}
	}
};

CContactsViewModel.prototype.searchFocus = function ()
{
	if (this.selector.useKeyboardKeys() && !Utils.isTextFieldFocused())
	{
		this.isSearchFocused(true);
	}
};

CContactsViewModel.prototype.onContactDblClick = function ()
{
	var oContact = this.selectedContact();
	if (oContact)
	{
		App.Api.composeMessageToAddresses(oContact.email());
	}
};

CContactsViewModel.prototype.onClearSearchClick = function ()
{
	// initiation empty search
	this.searchInput('');
	this.searchSubmitCommand();
};

/**
 * @param {type} oResult
 * @param {type} oRequest
 * @returns {undefined}
 */
CContactsViewModel.prototype.onContactGetResponse = function (oResult, oRequest)
{
	if (oResult && oResult.Action && oResult.Result)
	{
		var
			oObject = new CContactModel(),
			oSelected  = this.selector.itemSelected()
			;

		oObject.parse(oResult.Result);

		if (oSelected && oSelected.Id() === oObject.idContact())
		{
			this.selectedItem(oObject);
		}
	}
};

CContactsViewModel.prototype.onContactCreateResponse = function (oResult, oRequest)
{
	if (oResult && oResult.Action && oResult.Result)
	{
		App.Api.showReport(oResult.Action === 'ContactCreate' ?
			Utils.i18n('CONTACTS/REPORT_CONTACT_SUCCESSFULLY_ADDED') : Utils.i18n('CONTACTS/REPORT_CONTACT_SUCCESSFULLY_UPDATED'));
			
		this.requestContactList();
	}
};

CContactsViewModel.prototype.onContactListResponse = function (oResult, oRequest)
{
	if (oResult && oResult.Action && oResult.Result)
	{
		var
			iIndex = 0,
			iLen = 0,
			aList = [],
			bGlobal = 'ContactGlobalList' === oResult.Action,
			oSelected  = this.selector.itemSelected(),
			oSubSelected  = null,
			aChecked = this.selector.listChecked(),
			aCheckedIds = (aChecked && 0 < aChecked.length) ? _.map(aChecked, function (oItem) {
				return oItem.Id();
			}) : [],
			oObject = null
			;

		for (iLen = oResult.Result.List.length; iIndex < iLen; iIndex++)
		{
			if (oResult.Result.List[iIndex] && 'Object/CContactListItem' === Utils.pExport(oResult.Result.List[iIndex], '@Object', ''))
			{
				oObject = new CContactListItemModel();
				oObject.parse(oResult.Result.List[iIndex], bGlobal);

				aList.push(oObject);
			}
		}

		if (oSelected)
		{
			oSubSelected = _.find(aList, function (oItem) {
				return oSelected.Id() === oItem.Id();
			});
		}

		if (aCheckedIds && 0 < aCheckedIds.length)
		{
			_.each(aList, function (oItem) {
				oItem.checked(-1 < Utils.inArray(oItem.Id(), aCheckedIds));
			});
		}

		this.collection(aList);
		this.loadingList(false);
		this.oPageSwitcher.setCount(Utils.pInt(oResult.Result.ContactCount));

		if (oSubSelected)
		{
			this.selector.itemSelected(oSubSelected);
			this.requestContact(oSelected.Id());
		}

		this.selectedGroupContactsList(oResult.Result.List);

		if (oSelected) {
			this.requestContact(oSelected.Id());
		}
	}
};

CContactsViewModel.prototype.viewAllMails = function ()
{
	var
		aContactsList = this.selectedGroupContactsList(),
		sSearchRequest = 'email:'
	;

	if (aContactsList)
	{
		_.each(aContactsList, function(oContact, iContactKey)
		{
			_.each(oContact.Emails, function(sEmail, iEmailKey)
			{
				sSearchRequest = sSearchRequest + sEmail + ',';
			});
		});

		App.MailCache.searchMessagesInInbox(sSearchRequest);
	}
};

CContactsViewModel.prototype.onGroupListResponse = function (oResult, oRequest)
{
	if (oResult && oResult.Action && oResult.Result)
	{
		var
			iIndex = 0,
			iLen = 0,
			aList = [],
			oSelected  = _.find(this.groupFullCollection(), function (oItem) {
				return oItem.selected();
			}),
			oObject = null
			;

		this.groupFullCollection(aList);

		for (iLen = oResult.Result.length; iIndex < iLen; iIndex++)
		{
			if (oResult.Result[iIndex] && 'Object/CContactListItem' === Utils.pExport(oResult.Result[iIndex], '@Object', ''))
			{
				oObject = new CContactListItemModel();
				oObject.parse(oResult.Result[iIndex]);

				if (oObject.IsGroup())
				{
					if (oSelected && oSelected.Id() === oObject.Id())
					{
						this.selectedGroupInList(oObject);
					}

					aList.push(oObject);
				}
			}
		}

		this.groupFullCollection(aList);
	}
};

CContactsViewModel.prototype.onGroupCreateResponse = function (oResult, oRequest)
{
	if (oResult && oResult.Action && oResult.Result)
	{
		var aCheckedIds = _.map(this.selector.listChecked(), function (oItem) {
			return [oItem.Id(), oItem.Global() ? '1' : '0'];
		});

		this.executeAddContactsToGroupId(
			oResult.Result.IdGroup,
			aCheckedIds
		);

		if (!this.mobileApp)
		{
			this.selectedItem(null);
			this.selector.itemSelected(null);
		}

		App.Api.showReport(Utils.i18n('CONTACTS/REPORT_GROUP_SUCCESSFULLY_ADDED'));

		this.requestContactList();
		this.requestGroupFullList();
	}
};

CContactsViewModel.prototype.executeShare = function ()
{
	var
		self = this,
		aChecked = this.selector.listCheckedOrSelected(),
		oMainContact = this.selectedContact(),
		aContactsId = _.map(aChecked, function (oItem) {
			return oItem.ReadOnly() ? '' : oItem.Id();
		})
	;

	aContactsId = _.compact(aContactsId);

	if (0 < aContactsId.length)
	{
		_.each(aChecked, function (oContact) {
			if (oContact)
			{
				App.ContactsCache.clearInfoAboutEmail(oContact.Email());

				if (oMainContact && !oContact.IsGroup() && !oContact.ReadOnly() && !oMainContact.readOnly() && oMainContact.idContact() === oContact.Id())
				{
					oMainContact = null;
					this.selectedContact(null);
				}
			}
		}, this);

		_.each(this.collection(), function (oContact) {
			if (-1 < Utils.inArray(oContact, aChecked))
			{
				oContact.deleted(true);
			}
		});

		_.delay(function () {
			self.collection.remove(function (oItem) {
				return oItem.deleted();
			});
		}, 500);

		if (Enums.ContactsGroupListType.SharedToAll === this.selectedGroupType())
		{
			this.recivedAnimUnshare(true);
		}
		else
		{
			this.recivedAnimShare(true);
		}
	
		App.Ajax.send({
			'Action': 'ContactUpdateSharedToAll',
			'ContactsId': aContactsId.join(','),
			'SharedToAll': (Enums.ContactsGroupListType.SharedToAll === this.selectedGroupType()) ? '1' : '0'
		}, this.onContactUpdateSharedToAllResponse, this);
	}
};

CContactsViewModel.prototype.onContactUpdateSharedToAllResponse = function (oResult, oRequest)
{
	// TODO:
};

/**
 * @param {Object} oItem
 */
CContactsViewModel.prototype.requestGroup = function (oItem)
{
	this.loadingViewPane(true);
	
	if (oItem)
	{
		App.Ajax.send({
			'Action': 'ContactsGroup',
			'GroupId': oItem.Id()
		}, this.onGroupResponse, this);
	}
};

CContactsViewModel.prototype.onGroupResponse = function (oResult, oRequest)
{
	if (oResult && oResult.Action && oResult.Result)
	{
		var oGroup = oResult.Result;
		this.oGroupModel
			.idGroup(oGroup.IdGroup)
			.name(oGroup.Name)
			.isOrganization(oGroup.IsOrganization)
			.company(oGroup.Company)
			.country(oGroup.Country)
			.state(oGroup.State)
			.city(oGroup.City)
			.street(oGroup.Street)
			.zip(oGroup.Zip)
			.phone(oGroup.Phone)
			.fax(oGroup.Fax)
			.email(oGroup.Email)
			.web(oGroup.Web)
		;
	}
};

CContactsViewModel.prototype.onGroupEventsResponse = function (oResult, oRequest)
{
	if (oResult && oResult.Action && oResult.Result)
	{
		var Events = oResult.Result;
		this.oGroupModel.events(Events);
	}
};

CContactsViewModel.prototype.reload = function ()
{
	this.requestContactList();
};

CContactsViewModel.prototype.initUploader = function ()
{
	var self = this;

	if (this.uploaderArea())
	{
		this.oJua = new Jua({
			'action': '?/Upload/Contacts/',
			'name': 'jua-uploader',
			'queueSize': 2,
			'dragAndDropElement': this.uploaderArea(),
			'disableAjaxUpload': this.isPublic,
			'disableFolderDragAndDrop': this.isPublic,
			'disableDragAndDrop': this.isPublic,
			'hidden': {
				'Token': function () {
					return AppData.Token;
				},
				'AccountID': function () {
					return AppData.Accounts.currentId();
				},
				'AdditionalData':  function (oFile) {
					return JSON.stringify({
						'GroupId': self.selectedGroupType() === Enums.ContactsGroupListType.SubGroup ? self.currentGroupId() : 0,
						'IsShared': self.selectedGroupType() === Enums.ContactsGroupListType.SharedToAll
					});
				}
			}
		});

		this.oJua
			.on('onComplete', _.bind(this.onContactUploadComplete, this))
			.on('onBodyDragEnter', _.bind(this.bDragActive, this, true))
			.on('onBodyDragLeave', _.bind(this.bDragActive, this, false))
		;
	}
};

CContactsViewModel.prototype.onContactUploadComplete = function (sFileUid, bResponseReceived, bResponse)
{
	var bError = !bResponseReceived || !bResponse || bResponse.Error|| bResponse.Result.Error || false;

	if (!bError)
	{
		this.reload();
	}
	else
	{
		if (bResponse.ErrorCode)
		{
			App.Api.showErrorByCode(bResponse, Utils.i18n('The file must have .CSV or .VCF extension.'));
		}
		else
		{
			App.Api.showError(Utils.i18n('WARNING/UNKNOWN_ERROR'));
		}
	}
};


/**
 * @constructor
 */
function CScreens()
{
	var $win = $(window);
	this.resizeAll = _.debounce(function () {
		$win.resize();
	}, 100);
	
	this.oScreens = {};

	this.currentScreen = ko.observable('');

	this.informationScreen = ko.observable(null);
	
	this.popups = [];
}

CScreens.prototype.initScreens = function () {};
CScreens.prototype.initLayout = function () {};

CScreens.prototype.init = function ()
{
	this.initScreens();
	
	this.initLayout();
	
	$('#pSevenContent').addClass('single_mode');
	
	_.defer(function () {
		if (!AppData.SingleMode)
		{
			$('#pSevenContent').removeClass('single_mode');
		}
	});
	
	this.informationScreen(this.showNormalScreen(Enums.Screens.Information));
};

CScreens.prototype.hasOpenedMinimizedPopups = function ()
{
	var bOpenedMinimizedPopups = false;
	
	_.each(this.popups, function (oPopup) {
		var vm = oPopup.__vm;
		if (vm.minimized && vm.minimized())
		{
			bOpenedMinimizedPopups = true;
		}
	});
	
	return bOpenedMinimizedPopups;
};

CScreens.prototype.hasOpenedMaximizedPopups = function ()
{
	var bOpenedMaximizedPopups = false;
	
	_.each(this.popups, function (oPopup) {
		var vm = oPopup.__vm;
		if (!vm.minimized || !vm.minimized())
		{
			bOpenedMaximizedPopups = true;
		}
	});
	
	return bOpenedMaximizedPopups;
};

CScreens.prototype.getCurrentScreenModel = function ()
{
	var
		oCurrentScreen = this.oScreens[this.currentScreen()],
		oCurrentModel = (typeof oCurrentScreen !== 'undefined') ? oCurrentScreen.Model : null
	;
	
	return oCurrentModel;
};

/**
 * @param {string} sScreen
 * @param {?=} mParams
 */
CScreens.prototype.showCurrentScreen = function (sScreen, mParams)
{
	var
		oCurrentScreen = this.oScreens[this.currentScreen()],
		oCurrentModel = (typeof oCurrentScreen !== 'undefined') ? oCurrentScreen.Model : null
	;
	
	if (this.currentScreen() !== sScreen)
	{
		if (oCurrentModel && oCurrentScreen.bInitialized)
		{
			oCurrentModel.hideViewModel();
		}
		this.currentScreen(sScreen);
	}
	
	this.showNormalScreen(sScreen, mParams);
	this.resizeAll();
};

/**
 * @param {string} sScreen
 * @param {?=} mParams
 * 
 * @return Object
 */
CScreens.prototype.showNormalScreen = function (sScreen, mParams)
{
	var
		sScreenId = sScreen,
		oScreen = this.oScreens[sScreenId]
	;

	if (oScreen)
	{
		oScreen.bInitialized = (typeof oScreen.bInitialized !== 'boolean') ? false : oScreen.bInitialized;
		if (!oScreen.bInitialized)
		{
			oScreen.Model = this.initViewModel(oScreen.Model, oScreen.TemplateName);
			oScreen.bInitialized = true;
		}

		oScreen.Model.showViewModel(mParams);
	}
	
	return oScreen ? oScreen.Model : null;
};

/**
 * @param {?} CViewModel
 * @param {string} sTemplateId
 * 
 * @return {Object}
 */
CScreens.prototype.initViewModel = function (CViewModel, sTemplateId)
{
	var
		oViewModel = null,
		$viewModel = null
	;

	oViewModel = new CViewModel();

	$viewModel = $('div[data-view-model="' + sTemplateId + '"]')
		.attr('data-bind', 'template: {name: \'' + sTemplateId + '\'}')
		.hide();

	oViewModel.$viewModel = $viewModel;
	oViewModel.bShown = false;
	oViewModel.showViewModel = function (mParams)
	{
		this.$viewModel.show();
		if (typeof this.onRoute === 'function')
		{
			this.onRoute(mParams);
		}
		if (!this.bShown)
		{
			if (typeof this.onShow === 'function')
			{
				this.onShow(mParams);
			}
			if (AfterLogicApi.runPluginHook)
			{
				if (this.__name)
				{
					AfterLogicApi.runPluginHook('view-model-on-show', [this.__name, this]);
				}
			}
			
			this.bShown = true;
		}
	};
	oViewModel.hideViewModel = function ()
	{
		this.$viewModel.hide();
		if (typeof this.onHide === 'function')
		{
			this.onHide();
		}
		this.bShown = false;
	};
	ko.applyBindings(oViewModel, $viewModel[0]);

	if (typeof oViewModel.onApplyBindings === 'function')
	{
		oViewModel.onApplyBindings($viewModel);
	}

	return oViewModel;
};

/**
 * @param {?} CPopupViewModel
 * @param {Array=} aParameters
 */
CScreens.prototype.showPopup = function (CPopupViewModel, aParameters)
{
	if (CPopupViewModel)
	{
		if (!CPopupViewModel.__builded)
		{
			var
				oViewModelDom = null,
				oViewModel = new CPopupViewModel(),
				sTemplate = oViewModel.popupTemplate ? oViewModel.popupTemplate() : ''
			;

			if ('' !== sTemplate)
			{
				oViewModelDom = $('div[data-view-model="' + sTemplate + '"]')
					.attr('data-bind', 'template: {name: \'' + sTemplate + '\'}')
					.removeClass('visible').hide();

				if (oViewModelDom && 1 === oViewModelDom.length)
				{
					oViewModel.visibility = ko.observable(false);

					CPopupViewModel.__builded = true;
					CPopupViewModel.__vm = oViewModel;

					oViewModel.$viewModel = oViewModelDom;
					CPopupViewModel.__dom = oViewModelDom;

					oViewModel.showViewModel = Utils.createCommand(oViewModel, function () {
						if (App && App.Screens)
						{
							App.Screens.showPopup(CPopupViewModel);
						}
					});

					oViewModel.closeCommand = Utils.createCommand(oViewModel, function () {
						if (App && App.Screens)
						{
							App.Screens.hidePopup(CPopupViewModel);
						}
					});
					
					ko.applyBindings(oViewModel, oViewModelDom[0]);

					Utils.delegateRun(oViewModel, 'onApplyBindings', [oViewModelDom]);
				}
			}
		}

		if (CPopupViewModel.__vm && CPopupViewModel.__dom)
		{
			if (!CPopupViewModel.__vm.visibility())
			{
				CPopupViewModel.__dom.show();
				_.delay(function() {
					CPopupViewModel.__dom.addClass('visible');
				}, 50);
				CPopupViewModel.__vm.visibility(true);

				this.popups.push(CPopupViewModel);

				this.keyupPopupBinded = _.bind(this.keyupPopup, this, CPopupViewModel.__vm);
				$(document).on('keyup', this.keyupPopupBinded);
			}
			
			Utils.delegateRun(CPopupViewModel.__vm, 'onShow', aParameters);
		}
	}
};

/**
 * @param {Object} oViewModel
 * @param {Object} oEvent
 */
CScreens.prototype.keyupPopup = function (oViewModel, oEvent)
{
	if (oEvent)
	{
		var iKeyCode = window.parseInt(oEvent.keyCode, 10);
		
		if (Enums.Key.Esc === iKeyCode)
		{
			if (oViewModel.onEscHandler)
			{
				oViewModel.onEscHandler(oEvent);
			}
			else
			{
				oViewModel.closeCommand();
			}
		}

		if ((Enums.Key.Enter === iKeyCode || Enums.Key.Space === iKeyCode) && oViewModel.onEnterHandler)
		{
			oViewModel.onEnterHandler();
		}
	}
};

/**
 * @param {?} CPopupViewModel
 */
CScreens.prototype.hidePopup = function (CPopupViewModel)
{
	if (CPopupViewModel && CPopupViewModel.__vm && CPopupViewModel.__dom)
	{
		if (this.keyupPopupBinded)
		{
			$(document).off('keyup', this.keyupPopupBinded);
			this.keyupPopupBinded = undefined;
		}
		CPopupViewModel.__dom.removeClass('visible').hide();

		CPopupViewModel.__vm.visibility(false);

		Utils.delegateRun(CPopupViewModel.__vm, 'onHide');
		
		this.popups = _.without(this.popups, CPopupViewModel);
	}
};

CScreens.prototype.hideAllPopup = function ()
{
	_.each(this.popups, function (oPopup) {
		this.hidePopup(oPopup);
	}, this);
};

/**
 * @param {string} sMessage
 */
CScreens.prototype.showLoading = function (sMessage)
{
	if (this.informationScreen())
	{
		this.informationScreen().showLoading(sMessage);
	}
};

CScreens.prototype.hideLoading = function ()
{
	if (this.informationScreen())
	{
		this.informationScreen().hideLoading();
	}
};

/**
 * @param {string} sMessage
 * @param {number=} iDelay
 */
CScreens.prototype.showReport = function (sMessage, iDelay)
{
	if (this.informationScreen())
	{
		this.informationScreen().showReport(sMessage, iDelay);
	}
};

/**
 * @param {string} sMessage
 * @param {boolean=} bHtml = false
 * @param {boolean=} bNotHide = false
 * @param {boolean=} bGray = false
 */
CScreens.prototype.showError = function (sMessage, bHtml, bNotHide, bGray)
{
	if (this.informationScreen())
	{
		this.informationScreen().showError(sMessage, bHtml, bNotHide, bGray);
	}
};

/**
 * @param {boolean=} bGray = false
 */
CScreens.prototype.hideError = function (bGray)
{
	if (this.informationScreen())
	{
		this.informationScreen().hideError(bGray);
	}
};

CScreens.prototype.initHelpdesk = function ()
{
	var oScreen = this.oScreens[Enums.Screens.Helpdesk];

	if (AppData.User.IsHelpdeskSupported && oScreen && !oScreen.bInitialized)
	{
		oScreen.Model = this.initViewModel(oScreen.Model, oScreen.TemplateName);
		oScreen.bInitialized = true;
	}
};
CScreens.prototype.initScreens = function ()
{
	this.oScreens[Enums.Screens.Information] = {
		'Model': CInformationViewModel,
		'TemplateName': 'Common_InformationViewModel'
	};
	this.oScreens[Enums.Screens.Login] = {
		'Model': CWrapLoginViewModel,
		'TemplateName': 'Login_WrapLoginViewModel'
	};
	this.oScreens[Enums.Screens.Header] = {
		'Model': CHeaderMobileViewModel,
		'TemplateName': 'Common_HeaderMobileViewModel'
	};
	this.oScreens[Enums.Screens.Mailbox] = {
		'Model': CMailViewModel,
		'TemplateName': 'Mail_LayoutSidePane_MailViewModel'
	};
	this.oScreens[Enums.Screens.SingleMessageView] = {
		'Model': CMessagePaneViewModel,
		'TemplateName': 'Mail_LayoutSidePane_MessagePaneViewModel'
	};
	this.oScreens[Enums.Screens.Compose] = {
		'Model': CComposeViewModel,
		'TemplateName': 'Mail_ComposeViewModel'
	};
//	this.oScreens[Enums.Screens.SingleCompose] = {
//		'Model': CComposeViewModel,
//		'TemplateName': 'Mail_ComposeViewModel'
//	};
//	this.oScreens[Enums.Screens.Settings] = {
//		'Model': CSettingsViewModel,
//		'TemplateName': 'Settings_SettingsViewModel'
//	};
//	this.oScreens[Enums.Screens.SingleHelpdesk] = {
//		'Model': CHelpdeskViewModel,
//		'TemplateName': 'Helpdesk_ViewThreadInNewWindow'
//	};
};

CScreens.prototype.initLayout = function ()
{
	$('#pSevenContent').append($('#Layout').html());
};

/**
 * @constructor
 */
function CMailCache()
{
	this.currentAccountId = AppData.Accounts.currentId;

	this.currentAccountId.subscribe(function (iAccountID) {
		var
			oAccount = AppData.Accounts.getAccount(iAccountID),
			oFolderList = this.oFolderListItems[iAccountID],
			oParameters = {
				'Action': 'FoldersGetList',
				'AccountID': iAccountID
			}
		;
		if (oAccount)
		{
			oAccount.quotaRecieved(false);
			
			this.messagesLoadingError(false);
			
			if (oFolderList)
			{
				this.folderList(oFolderList);
			}
			else
			{
				this.messagesLoading(true);
				this.folderList(new CFolderListModel());
				this.messages([]);
				this.currentMessage(null);
				App.Ajax.send(oParameters, this.onFoldersGetListResponse, this);
			}
		}
	}, this);
	
	this.editedAccountId = AppData.Accounts.editedId;
	this.editedAccountId.subscribe(function (value) {
		var
			oFolderList = this.oFolderListItems[value],
			oParameters = {}
		;
		if (oFolderList)
		{
			this.editedFolderList(oFolderList);
		}
		else if (this.currentAccountId() !== value)
		{
			this.editedFolderList(new CFolderListModel());
			oParameters = {
				'Action': 'FoldersGetList',
				'AccountID': value
			};
			App.Ajax.send(oParameters, this.onFoldersGetListResponse, this);
		}
	}, this);
	
	this.oFolderListItems = {};

	this.quotaChangeTrigger = ko.observable(false);
	
	this.checkMailStarted = ko.observable(false);
	this.checkMailStartedAccountId = ko.observable(0);
	
	this.defaultFolderList = ko.observable(new CFolderListModel());
	
	this.folderList = ko.observable(new CFolderListModel());
	this.folderListLoading = ko.observable(false);
	
	this.editedFolderList = ko.observable(new CFolderListModel());

	this.newMessagesCount = ko.computed(function () {
		var
			oInbox = this.folderList().inboxFolder()
		;
		return oInbox ? oInbox.unseenMessageCount() : 0;
	}, this);
	this.newMessagesCount.subscribe(function (iMessagesCount) {
		App.mailUnseenCount(iMessagesCount > 99 ? '99+' : iMessagesCount);
	}, this);

	this.messages = ko.observableArray([]);
	this.messages.subscribe(function () {
		if (this.messages().length > 0)
		{
			this.messagesLoadingError(false);
		}
	}, this);
	
	this.uidList = ko.observable(new CUidListModel());
	this.page = ko.observable(1);
	
	this.messagesLoading = ko.observable(true);
	this.messagesLoadingError = ko.observable(false);
	
	this.currentMessage = ko.observable(null);
	this.currentMessage.subscribe(function () {
		if (this.currentMessage())
		{
			AfterLogicApi.runPluginHook('view-message', 
				[AppData.Accounts.currentId(), this.currentMessage().folder(), this.currentMessage().uid()]);
		}
	}, this);
	this.nextMessageUid = ko.computed(function () {
		var
			sCurrentUid = '',
			sNextUid = '',
			oFolder = null,
			oParentMessage = null,
			bThreadLevel = false
		;
		if (this.currentMessage() && AppData.SingleMode)
		{
			bThreadLevel = this.currentMessage().threadPart() && this.currentMessage().threadParentUid() !== '';
			oFolder = this.folderList().getFolderByFullName(this.currentMessage().folder());
			sCurrentUid = this.currentMessage().uid();
			if (AppData.ThreadLevel || bThreadLevel)
			{
				AppData.ThreadLevel = true;
				if (bThreadLevel)
				{
					oParentMessage = oFolder.getMessageByUid(this.currentMessage().threadParentUid());
					if (oParentMessage)
					{
						_.each(oParentMessage.threadUids(), function (sUid, iIndex, aCollection) {
							if (sUid === sCurrentUid && iIndex > 0)
							{
								sNextUid = aCollection[iIndex - 1];
							}
						});
						if (Utils.isUnd(sNextUid) || sNextUid === '')
						{
							sNextUid = oParentMessage.uid();
						}
					}
				}
			}
			else
			{
				_.each(this.uidList().collection(), function (sUid, iIndex, aCollection) {
					if (sUid === sCurrentUid && iIndex > 0)
					{
						sNextUid = aCollection[iIndex - 1];
					}
				});
				if (Utils.isUnd(sNextUid))
				{
					sNextUid = '';
				}
				if (sNextUid === '' && window.opener && window.opener.App && window.opener.App.Prefetcher)
				{
					window.opener.App.Prefetcher.prefetchNextPage(sCurrentUid);
				}
			}
		}
		return sNextUid;
	}, this);
	this.prevMessageUid = ko.computed(function () {
		var
			sCurrentUid = this.currentMessage() ? this.currentMessage().uid() : '',
			sPrevUid = '',
			oFolder = null,
			oParentMessage = null,
			bThreadLevel = false
		;
		if (this.currentMessage() && AppData.SingleMode)
		{
			bThreadLevel = this.currentMessage().threadPart() && this.currentMessage().threadParentUid() !== '';
			oFolder = this.folderList().getFolderByFullName(this.currentMessage().folder());
			sCurrentUid = this.currentMessage().uid();
			if (AppData.ThreadLevel || bThreadLevel)
			{
				AppData.ThreadLevel = true;
				if (bThreadLevel)
				{
					oParentMessage = oFolder.getMessageByUid(this.currentMessage().threadParentUid());
					if (oParentMessage)
					{
						_.each(oParentMessage.threadUids(), function (sUid, iIndex, aCollection) {
							if (sUid === sCurrentUid && (iIndex + 1) < aCollection.length)
							{
								sPrevUid = aCollection[iIndex + 1];
							}
						});
						if (Utils.isUnd(sPrevUid))
						{
							sPrevUid = '';
						}
					}
				}
				else if (this.currentMessage().threadCount() > 0)
				{
					sPrevUid = this.currentMessage().threadUids()[0];
				}
			}
			else
			{
				_.each(this.uidList().collection(), function (sUid, iIndex, aCollection) {
					if (sUid === sCurrentUid && (iIndex + 1) < aCollection.length)
					{
						sPrevUid = aCollection[iIndex + 1];
					}
				});
				if (Utils.isUnd(sPrevUid))
				{
					sPrevUid = '';
				}
				if (sPrevUid === '' && window.opener && window.opener.App && window.opener.App.Prefetcher)
				{
					window.opener.App.Prefetcher.prefetchPrevPage(sCurrentUid);
				}
			}
		}
		return sPrevUid;
	}, this);

	this.savingDraftUid = ko.observable('');
	this.editedDraftUid = ko.observable('');
	this.disableComposeAutosave = ko.observable(false);
	
	this.aResponseHandlers = [];

	AppData.User.useThreads.subscribe(function () {
		_.each(this.oFolderListItems, function (oFolderList) {
			_.each(oFolderList.collection(), function (oFolder) {
				oFolder.markHasChanges();
				oFolder.removeAllMessageListsFromCacheIfHasChanges();
			}, this);
		}, this);
		this.messages([]);
	}, this);
	
	this.iAutoCheckMailTimer = -1;
	
	this.waitForUnseenMessages = ko.observable(true);
	
	this.iMessageSetSeenCount = 0;	
	
	this.__name = 'CMailCache';
}

/**
 * @public
 */
CMailCache.prototype.init = function ()
{
	var oMailCache = null;
	
	App.Ajax.openedRequestsCount.subscribe(function () {
		if (App.Ajax.openedRequestsCount() === 0)
		{
			// Delay not to reset these flags between two related requests (e.g. 'FoldersGetRelevantInformation' and 'MessagesGetList')
			_.delay(_.bind(function () {
				if (App.Ajax.requests().length === 0)
				{
					this.checkMailStarted(false);
					this.folderListLoading(false);
				}
			}, this), 10);
		}
	}, this);
	
	if (AppData.SingleMode && window.opener)
	{
		oMailCache = window.opener.App.MailCache;
		
		this.oFolderListItems = oMailCache.oFolderListItems;
		this.uidList(oMailCache.uidList());
		oMailCache.uidList.subscribe(_.bind(function () {
			this.uidList(oMailCache.uidList());
		}, this));
		if (window.name)
		{
			var
				iAccountId = Utils.pInt(window.name),
				oMessageParametersFromCompose
			;
			
			if (iAccountId === 0 && window.opener && window.opener.aMessagesParametersFromCompose)
			{
				oMessageParametersFromCompose = window.opener.aMessagesParametersFromCompose[window.name];
				iAccountId = oMessageParametersFromCompose ? oMessageParametersFromCompose.accountId : 0;
			}
			
			if (iAccountId !== 0)
			{
				this.currentAccountId(iAccountId);
			}
		}
	}
	
	this.currentAccountId.valueHasMutated();
};

CMailCache.prototype.getCurrentFolder = function ()
{
	return this.folderList().currentFolder();
};

/**
 * @param {number} iAccountId
 * @param {string} sFolderFullName
 */
CMailCache.prototype.getFolderByFullName = function (iAccountId, sFolderFullName)
{
	var
		oFolderList = this.oFolderListItems[iAccountId]
	;
	
	if (oFolderList)
	{
		return oFolderList.getFolderByFullName(sFolderFullName);
	}
	
	return null;
};

CMailCache.prototype.checkCurrentFolderList = function ()
{
	var
		iCurrAccountId = AppData.Accounts.currentId(),
		oFolderList = this.oFolderListItems[iCurrAccountId]
	;
	
	if (!oFolderList && !this.messagesLoading())
	{
		this.messagesLoading(true);
		this.messagesLoadingError(false);
		this.getFolderList(iCurrAccountId);
	}
};

/**
 * @param {number=} iAccountID
 */
CMailCache.prototype.getFolderList = function (iAccountID)
{
	var oParameters = {'Action': 'FoldersGetList'};
	if (!Utils.isUnd(iAccountID))
	{
		oParameters['AccountID'] = iAccountID;
	}
	this.folderListLoading(true);
	App.Ajax.send(oParameters, this.onFoldersGetListResponse, this);
};

/**
 * @param {number} iAccountId
 * @param {string} sFullName
 * @param {string} sUid
 * @param {string} sReplyType
 */
CMailCache.prototype.markMessageReplied = function (iAccountId, sFullName, sUid, sReplyType)
{
	var
		oFolderList = this.oFolderListItems[iAccountId],
		oFolder = null
	;
	
	if (oFolderList)
	{
		oFolder = oFolderList.getFolderByFullName(sFullName);
		if (oFolder)
		{
			oFolder.markMessageReplied(sUid, sReplyType);
		}
	}
};

/**
 * @param {Object} oMessage
 */
CMailCache.prototype.hideThreads = function (oMessage)
{
	if (AppData.User.useThreads() && oMessage.folder() === this.folderList().currentFolderFullName() && !oMessage.threadOpened())
	{
		this.folderList().currentFolder().hideThreadMessages(oMessage);
	}
};

/**
 * @param {string} sFolderFullName
 */
CMailCache.prototype.showOpenedThreads = function (sFolderFullName)
{
	this.messages(this.getMessagesWithThreads(sFolderFullName, this.uidList(), this.messages()));
};

/**
 * @param {Object} oUidList
 * @returns {Boolean}
 */
CMailCache.prototype.useThreadsInCurrentList = function (oUidList)
{
	oUidList = oUidList || this.uidList();
	
	var
		oCurrFolder = this.folderList().currentFolder(),
		bFolderWithoutThreads = oCurrFolder && oCurrFolder.withoutThreads(),
		bNotSearchOrFilters = oUidList.search() === '' && oUidList.filters() === ''
	;
	
	return AppData.User.useThreads() && !bFolderWithoutThreads && bNotSearchOrFilters;
};

/**
 * @param {string} sFolderFullName
 * @param {Object} oUidList
 * @param {Array} aOrigMessages
 */
CMailCache.prototype.getMessagesWithThreads = function (sFolderFullName, oUidList, aOrigMessages)
{
	var
		aExtMessages = [],
		aMessages = [],
		oCurrFolder = this.folderList().currentFolder()
	;
	
	if (oCurrFolder && sFolderFullName === oCurrFolder.fullName() && this.useThreadsInCurrentList(oUidList))
	{
		aMessages = _.filter(aOrigMessages, function (oMess) {
			return !oMess.threadPart();
		});

		_.each(aMessages, function (oMess) {
			var aThreadMessages = [];
			aExtMessages.push(oMess);
			if (oMess.threadCount() > 0)
			{
				if (oMess.threadOpened())
				{
					aThreadMessages = this.folderList().currentFolder().getThreadMessages(oMess);
					aExtMessages = _.union(aExtMessages, aThreadMessages);
				}
				oCurrFolder.computeThreadData(oMess);
			}
		}, this);
		
		return aExtMessages;
	}
	
	return aOrigMessages;
};

/**
 * @param {Object} oUidList
 * @param {number} iOffset
 * @param {Object} oMessages
 * @param {boolean} bFillMessages
 */
CMailCache.prototype.setMessagesFromUidList = function (oUidList, iOffset, oMessages, bFillMessages)
{
	var
		aUids = oUidList.getUidsForOffset(iOffset, oMessages),
		aMessages = _.map(aUids, function (sUid) {
			return oMessages[sUid];
		}, this),
		iMessagesCount = aMessages.length
	;
	
	if (bFillMessages)
	{
		this.messages(this.getMessagesWithThreads(this.folderList().currentFolderFullName(), oUidList, aMessages));
		
		if ((iOffset + iMessagesCount < oUidList.resultCount()) &&
			(iMessagesCount < AppData.User.MailsPerPage) &&
			(oUidList.filters() !== Enums.FolderFilter.Unseen || this.waitForUnseenMessages()))
		{
			this.messagesLoading(true);
		}

		if (this.currentMessage() && (this.currentMessage().deleted() ||
			this.currentMessage().folder() !== this.folderList().currentFolderFullName()))
		{
			this.currentMessage(null);
		}
	}

	return aUids;
};

CMailCache.prototype.executeCheckMail = function ()
{
	var
		oFolderList = this.oFolderListItems[this.currentAccountId()],
		oInbox = oFolderList ? oFolderList.inboxFolder() : null,
		sInboxUidnext = oInbox ? oInbox.uidNext() : '',
		aFoldersFromAccount = AppData.Accounts.getCurrentFetchersAndFiltersFolderNames(),
		aFolders = oFolderList ? [oFolderList.inboxFolderFullName(), oFolderList.spamFolderFullName(), oFolderList.currentFolderFullName()] : [],
		iAccountID = oFolderList ? oFolderList.iAccountId : 0,
		bCurrentAccountCheckmailStarted = this.checkMailStarted() && (this.checkMailStartedAccountId() === iAccountID),
		oParameters = null
	;
	
	if ((!App.Ajax.hasOpenedRequests('FoldersGetRelevantInformation') || !bCurrentAccountCheckmailStarted) && (aFolders.length > 0))
	{
		aFolders = _.uniq(_.compact(_.union(aFolders, aFoldersFromAccount)));
		oParameters = {
			'Action': 'FoldersGetRelevantInformation',
			'Folders': aFolders,
			'AccountID': iAccountID,
			'InboxUidnext': sInboxUidnext
		};
		
		this.checkMailStarted(true);
		this.checkMailStartedAccountId(iAccountID);
		App.Ajax.send(oParameters, this.onFoldersGetRelevantInformationResponse, this);
	}
};

CMailCache.prototype.setAutocheckmailTimer = function ()
{
	clearTimeout(this.iAutoCheckMailTimer);
	
	if (!AppData.SingleMode && AppData.User.AutoCheckMailInterval > 0)
	{
		this.iAutoCheckMailTimer = setTimeout(function () {
			if (!App.Ajax.isSearchMessages())
			{
				App.MailCache.executeCheckMail();
			}
		}, AppData.User.AutoCheckMailInterval * 60 * 1000);
	}
};

CMailCache.prototype.checkMessageFlags = function ()
{
	var
		oInbox = this.folderList().inboxFolder(),
		aUids = oInbox ? oInbox.getFlaggedMessageUids() : [],
		oParameters = {
			'Action': 'MessagesGetFlags',
			'Folder': this.folderList().inboxFolderFullName(),
			'Uids': aUids
		}
	;
	
	if (aUids > 0)
	{
		App.Ajax.send(oParameters, this.onMessagesGetFlagsResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.onMessagesGetFlagsResponse = function (oResponse, oRequest)
{
	var oInbox = this.folderList().inboxFolder();
	
	if (oResponse.Result)
	{
		_.each(oResponse.Result, function (aFlags, sUid) {
			if (_.indexOf(aFlags, '\\flagged') === -1)
			{
				oInbox.setMessageUnflaggedByUid(sUid);
			}
		});
	}
	oInbox.removeFlaggedMessageListsFromCache();
	App.Prefetcher.prefetchStarredMessageList();
};

/**
 * @param {string} sFolder
 * @param {number} iPage
 * @param {string} sSearch
 * @param {string=} sFilter
 */
CMailCache.prototype.changeCurrentMessageList = function (sFolder, iPage, sSearch, sFilter)
{
	this.requestCurrentMessageList(sFolder, iPage, sSearch, sFilter, true);
};

/**
 * @param {string} sFolder
 * @param {number} iPage
 * @param {string} sSearch
 * @param {string=} sFilter
 * @param {boolean=} bFillMessages
 */
CMailCache.prototype.requestCurrentMessageList = function (sFolder, iPage, sSearch, sFilter, bFillMessages)
{
	var
		oRequestData = this.requestMessageList(sFolder, iPage, sSearch, sFilter || '', true, (bFillMessages || false)),
		iCheckmailIntervalMilliseconds = AppData.User.AutoCheckMailInterval * 60 * 1000,
		iFolderUpdateDiff = oRequestData.Folder.relevantInformationLastMoment ? moment().diff(oRequestData.Folder.relevantInformationLastMoment) : iCheckmailIntervalMilliseconds + 1
	;
	
	this.uidList(oRequestData.UidList);
	this.page(iPage);
	
	this.messagesLoading(oRequestData.RequestStarted);
	this.messagesLoadingError(false);
	
	if (!oRequestData.RequestStarted && iCheckmailIntervalMilliseconds > 0 && iFolderUpdateDiff > iCheckmailIntervalMilliseconds)
	{
		this.executeCheckMail();
	}
};

/**
 * @param {string} sFolder
 * @param {number} iPage
 * @param {string} sSearch
 * @param {string} sFilters
 * @param {boolean} bCurrent
 * @param {boolean} bFillMessages
 */
CMailCache.prototype.requestMessageList = function (sFolder, iPage, sSearch, sFilters, bCurrent, bFillMessages)
{
	var
		oFolderList = this.oFolderListItems[this.currentAccountId()],
		oFolder = (oFolderList) ? oFolderList.getFolderByFullName(sFolder) : null,
		bFolderWithoutThreads = oFolder && oFolder.withoutThreads(),
		bUseThreads = AppData.User.useThreads() && !bFolderWithoutThreads && sSearch === '' && sFilters === '',
		oUidList = (oFolder) ? oFolder.getUidList(sSearch, sFilters) : null,
		bCacheIsEmpty = oUidList && oUidList.resultCount() === -1,
		iOffset = (iPage - 1) * AppData.User.MailsPerPage,
		oParameters = {
			'Action': 'MessagesGetList',
			'Folder': sFolder,
			'Offset': iOffset,
			'Limit': AppData.User.MailsPerPage,
			'Search': sSearch,
			'Filters': sFilters,
			'UseThreads': bUseThreads ? '1' : '0'
		},
		bStartRequest = false,
		bDataExpected = false,
		fCallBack = bCurrent ? this.onCurrentMessagesGetListResponse : this.onMessagesGetListResponse,
		aUids = []
	;
	
	if (oFolder.type() === Enums.FolderTypes.Inbox)
	{
		oParameters['InboxUidnext'] = oFolder.uidNext();
	}
	
	if (bCacheIsEmpty && oUidList.search() === this.uidList().search() && oUidList.filters() === this.uidList().filters())
	{
		oUidList = this.uidList();
	}
	if (oUidList)
	{
		aUids = this.setMessagesFromUidList(oUidList, iOffset, oFolder.oMessages, bFillMessages);
	}
	
	if (oUidList)
	{
		bDataExpected = 
			(bCacheIsEmpty) ||
			((iOffset + aUids.length < oUidList.resultCount()) && (aUids.length < AppData.User.MailsPerPage))
		;
		bStartRequest = oFolder.hasChanges() || bDataExpected;
	}
	
	if (bStartRequest)
	{
		App.Ajax.send(oParameters, fCallBack, this);
	}
	else
	{
		this.waitForUnseenMessages(false);
	}
	
	return {UidList: oUidList, RequestStarted: bStartRequest, DataExpected: bDataExpected, Folder: oFolder};
};

CMailCache.prototype.executeEmptyTrash = function ()
{
	var oFolder = this.folderList().trashFolder();
	if (oFolder)
	{
		oFolder.emptyFolder();
	}
};

CMailCache.prototype.executeEmptySpam = function ()
{
	var oFolder = this.folderList().spamFolder();
	if (oFolder)
	{
		oFolder.emptyFolder();
	}
};

/**
 * @param {Object} oFolder
 */
CMailCache.prototype.onClearFolder = function (oFolder)
{
	if (oFolder && oFolder.selected())
	{
		this.messages.removeAll();
		this.currentMessage(null);
		var oUidList = (oFolder) ? oFolder.getUidList(this.uidList().search(), this.uidList().filters()) : null;
		if (oUidList)
		{
			this.uidList(oUidList);
		}
		else
		{
			this.uidList(new CUidListModel());
		}
		
		// FoldersGetRelevantInformation-request aborted during folder cleaning, not to get the wrong information.
		// So here indicates that chekmail is over.
		this.checkMailStarted(false);
		this.setAutocheckmailTimer();
	}
};

/**
 * @param {string} sToFolderFullName
 * @param {Array} aUids
 * @param {boolean} bAnimateRecive
 */
CMailCache.prototype.moveMessagesToFolder = function (sToFolderFullName, aUids, bAnimateRecive)
{
	if (aUids.length > 0)
	{
		var
			oCurrFolder = this.folderList().currentFolder(),
			bDraftsFolder = oCurrFolder && oCurrFolder.type() === Enums.FolderTypes.Drafts,
			aOpenedDraftUids = Utils.WindowOpener.getOpenedDraftUids(),
			bTryToDeleteEditedDraft = bDraftsFolder && _.find(aUids, _.bind(function (sUid) {
				return -1 !== Utils.inArray(sUid, aOpenedDraftUids);
			}, this)),
			oToFolder = this.folderList().getFolderByFullName(sToFolderFullName),
			oParameters = {
				'Action': 'MessageMove',
				'Folder': oCurrFolder ? oCurrFolder.fullName() : '',
				'ToFolder': sToFolderFullName,
				'Uids': aUids.join(',')
			},
			oDiffs = null,
			fMoveMessages = _.bind(function () {
				if (this.uidList().filters() === Enums.FolderFilter.Unseen && this.uidList().resultCount() > AppData.User.MailsPerPage)
				{
					this.waitForUnseenMessages(true);
				}
				
				oDiffs = oCurrFolder.markDeletedByUids(aUids);
				oToFolder.addMessagesCountsDiff(oDiffs.MinusDiff, oDiffs.UnseenMinusDiff);

				if (Utils.isUnd(bAnimateRecive) ? true : !!bAnimateRecive)
				{
					oToFolder.recivedAnim(true);
				}

				this.excludeDeletedMessages();

				oToFolder.markHasChanges();
				
				App.Ajax.send(oParameters, this.onMoveMessagesResponse, this);

				if (oToFolder && oToFolder.type() === Enums.FolderTypes.Trash)
				{
					AfterLogicApi.runPluginHook('move-messages-to-trash', 
						[AppData.Accounts.currentId(), oParameters.Folder, aUids]);
				}

				if (oToFolder && oToFolder.type() === Enums.FolderTypes.Spam)
				{
					AfterLogicApi.runPluginHook('move-messages-to-spam', 
						[AppData.Accounts.currentId(), oParameters.Folder, aUids]);
				}
			}, this)
		;

		if (oCurrFolder && oToFolder)
		{
			if (bTryToDeleteEditedDraft)
			{
				this.disableComposeAutosave(true);
				App.Screens.showPopup(ConfirmPopup, [Utils.i18n('MAILBOX/CONFIRM_MESSAGE_FOR_DELETE_IS_EDITED'), 
					_.bind(function (bOk) {
						if (bOk)
						{
							Utils.WindowOpener.closeComposesWithDraftUids(aUids);
							fMoveMessages();
						}
						this.disableComposeAutosave(false);
					}, this), 
					'', Utils.i18n('MAILBOX/BUTTON_CLOSE_DELETE_DRAFT')
				]);
			}
			else
			{
				fMoveMessages();
			}
		}
	}
};

CMailCache.prototype.copyMessagesToFolder = function (sToFolderFullName, aUids, bAnimateRecive)
{
	if (aUids.length > 0)
	{
		var
			oCurrFolder = this.folderList().currentFolder(),
			oToFolder = this.folderList().getFolderByFullName(sToFolderFullName),
			oParameters = {
				'Action': 'MessageCopy',
				'Folder': oCurrFolder ? oCurrFolder.fullName() : '',
				'ToFolder': sToFolderFullName,
				'Uids': aUids.join(',')
			}
		;

		if (oCurrFolder && oToFolder)
		{
			if (Utils.isUnd(bAnimateRecive) ? true : !!bAnimateRecive)
			{
				oToFolder.recivedAnim(true);
			}

			oToFolder.markHasChanges();

			App.Ajax.send(oParameters, this.onCopyMessagesResponse, this);

			if (oToFolder && oToFolder.type() === Enums.FolderTypes.Trash)
			{
				AfterLogicApi.runPluginHook('copy-messages-to-trash',
					[AppData.Accounts.currentId(), oParameters.Folder, aUids]);
			}

			if (oToFolder && oToFolder.type() === Enums.FolderTypes.Spam)
			{
				AfterLogicApi.runPluginHook('copy-messages-to-spam',
					[AppData.Accounts.currentId(), oParameters.Folder, aUids]);
			}
		}
	}
};

CMailCache.prototype.excludeDeletedMessages = function ()
{
	_.delay(_.bind(function () {
		
		var
			oCurrFolder = this.folderList().currentFolder(),
			iOffset = (this.page() - 1) * AppData.User.MailsPerPage
		;
		
		this.setMessagesFromUidList(this.uidList(), iOffset, oCurrFolder.oMessages, true);
		
	}, this), 500);
};

/**
 * @param {number} iAccountID
 * @param {string} sFolderFullName
 * @param {string} sDraftUid
 */
CMailCache.prototype.removeOneMessageFromCacheForFolder = function (iAccountID, sFolderFullName, sDraftUid)
{
	var
		oFolderList = this.oFolderListItems[iAccountID],
		oFolder = oFolderList ? oFolderList.getFolderByFullName(sFolderFullName) : null
	;
	
	if (oFolder && oFolder.type() === Enums.FolderTypes.Drafts)
	{
		oFolder.markDeletedByUids([sDraftUid]);
		oFolder.commitDeleted([sDraftUid]);
	}
};

/**
 * @param {number} iAccountID
 * @param {string} sFolderFullName
 */
CMailCache.prototype.startMessagesLoadingWhenDraftSaving = function (iAccountID, sFolderFullName)
{
	var
		oFolderList = this.oFolderListItems[iAccountID],
		oFolder = oFolderList ? oFolderList.getFolderByFullName(sFolderFullName) : null
	;
	
	if ((oFolder && oFolder.type() === Enums.FolderTypes.Drafts) && oFolder.selected())
	{
		this.messagesLoading(true);
	}
};

/**
 * @param {number} iAccountID
 * @param {string} sFolderFullName
 */
CMailCache.prototype.removeMessagesFromCacheForFolder = function (iAccountID, sFolderFullName)
{
	var
		oFolderList = this.oFolderListItems[iAccountID],
		oFolder = oFolderList ? oFolderList.getFolderByFullName(sFolderFullName) : null,
		sCurrFolderFullName = oFolderList ? oFolderList.currentFolderFullName() : null
	;
	if (oFolder)
	{
		oFolder.markHasChanges();
		if (this.currentAccountId() === iAccountID && sFolderFullName === sCurrFolderFullName)
		{
			this.requestCurrentMessageList(sCurrFolderFullName, this.page(), this.uidList().search(), '', true);
		}
	}
};

/**
 * @param {Array} aUids
 */
CMailCache.prototype.deleteMessages = function (aUids)
{
	var
		oCurrFolder = this.folderList().currentFolder()
	;

	if (oCurrFolder)
	{
		this.deleteMessagesFromFolder(oCurrFolder, aUids);
	}
};

/**
 * @param {Object} oFolder
 * @param {Array} aUids
 */
CMailCache.prototype.deleteMessagesFromFolder = function (oFolder, aUids)
{
	var
		oParameters = {
			'Action': 'MessageDelete',
			'Folder': oFolder.fullName(),
			'Uids': aUids.join(',')
		}
	;

	oFolder.markDeletedByUids(aUids);

	this.excludeDeletedMessages();

	App.Ajax.send(oParameters, this.onMoveMessagesResponse, this);
	
	AfterLogicApi.runPluginHook('delete-messages', 
		[AppData.Accounts.currentId(), oParameters.Folder, aUids]);
};

/**
 * @param {boolean} bAlwaysForSender
 */
CMailCache.prototype.showExternalPictures = function (bAlwaysForSender)
{
	var
		aFrom = [],
		oFolder = null
	;
		
	if (this.currentMessage())
	{
		aFrom = this.currentMessage().oFrom.aCollection;
		oFolder = this.folderList().getFolderByFullName(this.currentMessage().folder());

		if (bAlwaysForSender && aFrom.length > 0)
		{
			oFolder.alwaysShowExternalPicturesForSender(aFrom[0].sEmail);
		}
		else
		{
			oFolder.showExternalPictures(this.currentMessage().uid());
		}
	}
};

/**
 * @param {string|null} sUid
 * @param {string} sFolder
 */
CMailCache.prototype.setCurrentMessage = function (sUid, sFolder)
{
	var
		oCurrFolder = this.folderList().currentFolder(),
		oMessage = oCurrFolder && sUid ? oCurrFolder.oMessages[sUid] : null
	;
	
	if (AppData.SingleMode && (!oCurrFolder || oCurrFolder.fullName() !== sFolder))
	{
		this.folderList().setCurrentFolder(sFolder, '');
		oCurrFolder = this.folderList().currentFolder();
	}
	
	if (oMessage && !oMessage.deleted())
	{
		this.currentMessage(oMessage);
		if (!this.currentMessage().seen())
		{
			this.executeGroupOperation('MessageSetSeen', [this.currentMessage().uid()], 'seen', true);
		}
		oCurrFolder.getCompletelyFilledMessage(sUid, this.onCurrentMessageResponse, this);
	}
	else
	{
		this.currentMessage(null);
		if (AppData.SingleMode && oCurrFolder)
		{
			oCurrFolder.getCompletelyFilledMessage(sUid, this.onCurrentMessageResponse, this);
		}
	}
};

/**
 * @param {Object} oMessage
 * @param {string} sUid
 */
CMailCache.prototype.onCurrentMessageResponse = function (oMessage, sUid)
{
	var sCurrentUid = this.currentMessage() ? this.currentMessage().uid() : '';
	
	if (oMessage === null && sCurrentUid === sUid)
	{
		this.currentMessage(null);
	}
	else if (oMessage && sCurrentUid === sUid)
	{
		this.currentMessage.valueHasMutated();
	}
	else if (AppData.SingleMode && oMessage && this.currentMessage() === null)
	{
		this.currentMessage(oMessage);
	}
};

/**
 * @param {string} sFullName
 * @param {string} sUid
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 */
CMailCache.prototype.getMessage = function (sFullName, sUid, fResponseHandler, oContext)
{
	var
		oFolder = this.folderList().getFolderByFullName(sFullName)
	;
	
	if (oFolder)
	{
		oFolder.getCompletelyFilledMessage(sUid, fResponseHandler, oContext);
	}
};

/**
 * @param {string} sAction
 * @param {Array} aUids
 * @param {string} sField
 * @param {boolean} bSetAction
 */
CMailCache.prototype.executeGroupOperation = function (sAction, aUids, sField, bSetAction)
{
	var
		oCurrFolder = this.folderList().currentFolder(),
		oParameters = {
			'Action': sAction,
			'Folder': oCurrFolder ? oCurrFolder.fullName() : '',
			'Uids': aUids.join(','),
			'SetAction': bSetAction ? 1 : 0
		},
		iOffset = (this.page() - 1) * AppData.User.MailsPerPage,
		iUidsCount = aUids.length,
		iStarredCount = this.folderList().oStarredFolder ? this.folderList().oStarredFolder.messageCount() : 0,
		oStarredUidList = oCurrFolder ? oCurrFolder.getUidList('', Enums.FolderFilter.Flagged) : null
	;

	if (oCurrFolder)
	{
		if (oParameters.Action === 'MessageSetSeen')
		{
			this.iMessageSetSeenCount++;
		}
		App.Ajax.send(oParameters, this.onExecuteGroupOperationResponse, this);

		oCurrFolder.executeGroupOperation(sField, aUids, bSetAction);
		
		if (oCurrFolder.type() === Enums.FolderTypes.Inbox && sField === 'flagged')
		{
			if (this.uidList().filters() === Enums.FolderFilter.Flagged)
			{
				if (!bSetAction)
				{
					this.uidList().deleteUids(aUids);
					if (this.folderList().oStarredFolder)
					{
						this.folderList().oStarredFolder.messageCount(oStarredUidList.resultCount());
					}
				}
			}
			else
			{
				oCurrFolder.removeFlaggedMessageListsFromCache();
				if (this.uidList().search() === '' && this.folderList().oStarredFolder)
				{
					if (bSetAction)
					{
						this.folderList().oStarredFolder.messageCount(iStarredCount + iUidsCount);
					}
					else
					{
						this.folderList().oStarredFolder.messageCount((iStarredCount - iUidsCount > 0) ? iStarredCount - iUidsCount : 0);
					}
				}
			}
		}
			
		if (sField === 'seen')
		{
			oCurrFolder.removeUnseenMessageListsFromCache();
		}
		
		if (this.uidList().filters() !== Enums.FolderFilter.Unseen || this.waitForUnseenMessages())
		{
			this.setMessagesFromUidList(this.uidList(), iOffset, oCurrFolder.oMessages, true);
		}
	}
};

/**
 * private
 */

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.onExecuteGroupOperationResponse = function (oResponse, oRequest)
{
	if (oRequest.Action === 'MessageSetSeen')
	{
		this.iMessageSetSeenCount--;
		if (this.iMessageSetSeenCount < 0)
		{
			this.iMessageSetSeenCount = 0;
		}
		if (this.folderList().currentFolder() && this.iMessageSetSeenCount === 0 && (this.uidList().filters() !== Enums.FolderFilter.Unseen || this.waitForUnseenMessages()))
		{
			this.requestCurrentMessageList(this.folderList().currentFolder().fullName(), this.page(), this.uidList().search(), this.uidList().filters(), false);
		}
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.onFoldersGetListResponse = function (oResponse, oRequest)
{
	var
		oFolderList = new CFolderListModel(),
		iAccountId = parseInt(oResponse.AccountID, 10),
		oFolderListOld = this.oFolderListItems[iAccountId],
		oNamedFolderListOld = oFolderListOld ? oFolderListOld.oNamedCollection : {}
	;		

	if (oResponse.Result === false)
	{
		App.Api.showErrorByCode(oResponse);
		
		if (oRequest.AccountID === this.currentAccountId() && this.messages().length === 0)
		{
			this.messagesLoading(false);
			this.messagesLoadingError(true);
		}
	}
	else
	{
		oFolderList.parse(iAccountId, oResponse.Result, oNamedFolderListOld);
		if (oFolderListOld)
		{
			oFolderList.oStarredFolder.messageCount(oFolderListOld.oStarredFolder.messageCount());
		}
		this.oFolderListItems[iAccountId] = oFolderList;

		setTimeout(_.bind(this.getAllFoldersRelevantInformation, this, iAccountId), 2000);

		if (this.currentAccountId() === iAccountId)
		{
			this.folderList(oFolderList);
		}
		if (this.editedAccountId() === iAccountId)
		{
			this.editedFolderList(oFolderList);
		}
		if (AppData.Accounts.defaultId() === iAccountId)
		{
			this.defaultFolderList(oFolderList);
		}
	}
	
	this.folderListLoading(false);
};

/**
 * @param {Object} oFolderList
 */
CMailCache.prototype.setCurrentFolderList = function (oFolderList)
{
	var iAccountId = oFolderList.iAccountId;
	
	if (iAccountId === this.currentAccountId() && iAccountId !== this.folderList().iAccountId)
	{
		this.folderList(oFolderList);
	}
};

/**
 * @param {number} iAccountId
 */
CMailCache.prototype.getAllFoldersRelevantInformation = function (iAccountId)
{
	var
		oFolderList = this.oFolderListItems[iAccountId],
		oInbox = oFolderList.inboxFolder(),
		sInboxUidnext = oInbox ? oInbox.uidNext() : '',
		aFolders = oFolderList ? oFolderList.getFoldersWithoutCountInfo() : [],
		oParameters = {
			'Action': 'FoldersGetRelevantInformation',
			'Folders': aFolders,
			'AccountID': iAccountId,
			'InboxUidnext': sInboxUidnext
		}
	;
	
	if (aFolders.length > 0)
	{
		App.Ajax.send(oParameters, this.onFoldersGetRelevantInformationResponse, this);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.onFoldersGetRelevantInformationResponse = function (oResponse, oRequest)
{
	var
		bCheckMailStarted = false,
		iAccountId = oResponse.AccountID,
		oFolderList = this.oFolderListItems[iAccountId],
		sCurrentFolderName = this.folderList().currentFolderFullName(),
		bSameAccount = this.currentAccountId() === iAccountId
	;
	
	if (oResponse.Result === false)
	{
		App.Api.showErrorByCode(oResponse);
	}
	else
	{
		if (oFolderList)
		{
			_.each(oResponse.Result && oResponse.Result.Counts, function(aData, sFullName) {
				if (_.isArray(aData) && aData.length > 3)
				{
					var
						iCount = aData[0],
						iUnseenCount = aData[1],
						sUidNext = aData[2],
						sHash = aData[3],
						bFolderHasChanges = false,
						bSameFolder = false,
						oFolder = null
					;

					oFolder = oFolderList.getFolderByFullName(sFullName);
					if (oFolder)
					{
						bSameFolder = bSameAccount && oFolder.fullName() === sCurrentFolderName;
						bFolderHasChanges = oFolder.setRelevantInformation(sUidNext, sHash, iCount, iUnseenCount, bSameFolder);
						if (bSameFolder && bFolderHasChanges && this.uidList().filters() !== Enums.FolderFilter.Unseen)
						{
							this.requestCurrentMessageList(oFolder.fullName(), this.page(), this.uidList().search(), this.uidList().filters(), false);
							bCheckMailStarted = true;
						}
					}
				}
			}, this);
			
			oFolderList.countsCompletelyFilled(true);
		}

		this.showNotificationsForNewMessages(oResponse);
	}
	
	this.checkMailStarted(bCheckMailStarted);
	if (!this.checkMailStarted())
	{
		this.setAutocheckmailTimer();
	}
};

/**
 * @param {Object} oResponse
 */
CMailCache.prototype.showNotificationsForNewMessages = function (oResponse)
{
	var
		iNewLength = 0,
		oParameters = {}
	;
	
	if(oResponse.Result.New && oResponse.Result.New.length)
	{
		iNewLength = oResponse.Result.New.length;
		oParameters = {
			action:'show',
			icon: 'skins/wm_logo_140x140.png',
			title: Utils.i18n('NOTIFICATION/NEW_MESSAGE_PLURAL', {
				'COUNT': iNewLength
			}, null, iNewLength),
			timeout: 5000
		};

		if(iNewLength === 1)
		{
			oParameters.body = Utils.i18n('MESSAGE/HEADER_SUBJECT') + ': ' +
				oResponse.Result.New[0].Subject + '\r\n' +
				Utils.i18n('MESSAGE/HEADER_FROM') + ': ' +
				(_.map(oResponse.Result.New[0].From, function(oFrom, iKey)
				{
					return oFrom.DisplayName !== '' ? oFrom.DisplayName : oFrom.Email;
				})).join(', ');
		}

		App.desktopNotify(oParameters);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.onCurrentMessagesGetListResponse = function (oResponse, oRequest)
{
	this.checkMailStarted(false);

	if (!oResponse.Result)
	{
		App.Api.showErrorByCode(oResponse);
		if (this.messagesLoading() === true && (this.messages().length === 0 || oResponse.ErrorCode !== Enums.Errors.NotDisplayedError))
		{
			this.messagesLoadingError(true);
		}
		this.messagesLoading(false);
		this.setAutocheckmailTimer();
	}
	else
	{
		this.messagesLoadingError(false);
		this.parseMessageList(oResponse, oRequest);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.onMessagesGetListResponse = function (oResponse, oRequest)
{
	if (oResponse && oResponse.Result)
	{
		this.parseMessageList(oResponse, oRequest);
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.parseMessageList = function (oResponse, oRequest)
{
	var
		oResult = oResponse.Result,
		oFolderList = this.oFolderListItems[oResponse.AccountID],
		oFolder = null,
		oUidList = null,
		bTrustThreadInfo = (oRequest.UseThreads === '1'),
		bHasFolderChanges = false,
		bCurrentFolder = this.currentAccountId() === oResponse.AccountID &&
				this.folderList().currentFolderFullName() === oResult.FolderName,
		bCurrentList = bCurrentFolder &&
				this.uidList().search() === oResult.Search &&
				this.uidList().filters() === oResult.Filters,
		bCurrentPage = this.page() === ((oResult.Offset / AppData.User.MailsPerPage) + 1),
		aNewFolderMessages = []
	;
	
	this.showNotificationsForNewMessages(oResponse);
	
	if (oResult !== false && oResult['@Object'] === 'Collection/MessageCollection')
	{
		oFolder = oFolderList.getFolderByFullName(oResult.FolderName);
		
		// perform before getUidList, because in case of a mismatch the uid list will be pre-cleaned
		oFolder.setRelevantInformation(oResult.UidNext.toString(), oResult.FolderHash, 
			oResult.MessageCount, oResult.MessageUnseenCount, bCurrentFolder && !bCurrentList);
		bHasFolderChanges = oFolder.hasChanges();
		oFolder.removeAllMessageListsFromCacheIfHasChanges();
		
		oUidList = oFolder.getUidList(oResult.Search, oResult.Filters);
		oUidList.setUidsAndCount(oResult);
		_.each(oResult['@Collection'], function (oRawMessage) {
			var oFolderMessage = oFolder.parseAndCacheMessage(oRawMessage, false, bTrustThreadInfo);
			aNewFolderMessages.push(oFolderMessage);
		}, this);
		
		AfterLogicApi.runPluginHook('response-custom-messages', 
			[oResponse.AccountID, oFolder.fullName(), aNewFolderMessages]);

		if (bCurrentList)
		{
			this.uidList(oUidList);
			if (bCurrentPage && (oUidList.filters() !== Enums.FolderFilter.Unseen || this.waitForUnseenMessages()))
			{
				this.setMessagesFromUidList(oUidList, oResult.Offset, oFolder.oMessages, true);
				this.messagesLoading(false);
				this.waitForUnseenMessages(false);
				this.setAutocheckmailTimer();
			}
		}
		
		if (bHasFolderChanges && bCurrentFolder && (!bCurrentList || !bCurrentPage) && this.uidList().filters() !== Enums.FolderFilter.Unseen)
		{
			this.requestCurrentMessageList(this.folderList().currentFolderFullName(), this.page(), this.uidList().search(), this.uidList().filters(), false);
		}
		
		if (oFolder.type() === Enums.FolderTypes.Inbox && oUidList.filters() === Enums.FolderFilter.Flagged &&
			oUidList.search() === '' && this.folderList().oStarredFolder)
		{
			this.folderList().oStarredFolder.messageCount(oUidList.resultCount());
			this.folderList().oStarredFolder.hasExtendedInfo(true);
		}
	}
};

CMailCache.prototype.increaseStarredCount = function ()
{
	if (this.folderList().oStarredFolder)
	{
		this.folderList().oStarredFolder.increaseCountIfHasNotInfo();
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CMailCache.prototype.onMoveMessagesResponse = function (oResponse, oRequest)
{
	var
		oResult = oResponse.Result,
		oFolder = this.folderList().getFolderByFullName(oRequest.Folder),
		oToFolder = this.folderList().getFolderByFullName(oRequest.ToFolder),
		bToFolderTrash = (oToFolder && (oToFolder.type() === Enums.FolderTypes.Trash)),
		bToFolderSpam = (oToFolder && (oToFolder.type() === Enums.FolderTypes.Spam)),
		oDiffs = null,
		sConfirm = bToFolderTrash ? Utils.i18n('MAILBOX/CONFIRM_MESSAGES_DELETE_WITHOUT_TRASH') :
			Utils.i18n('MAILBOX/CONFIRM_MESSAGES_MARK_SPAM_WITHOUT_SPAM'),
		fDeleteMessages = _.bind(function (bResult) {
			if (bResult && oFolder)
			{
				this.deleteMessagesFromFolder(oFolder, oRequest.Uids.split(','));
			}
		}, this),
		oCurrFolder = this.folderList().currentFolder(),
		sCurrFolderFullName = oCurrFolder.fullName(),
		bFillMessages = false
	;
	
	if (oResult === false)
	{
		oDiffs = oFolder.revertDeleted(oRequest.Uids.split(','));
		if (oToFolder)
		{
			oToFolder.addMessagesCountsDiff(-oDiffs.PlusDiff, -oDiffs.UnseenPlusDiff);
			if (oResponse.ErrorCode === Enums.Errors.ImapQuota && (bToFolderTrash || bToFolderSpam))
			{
				App.Screens.showPopup(ConfirmPopup, [sConfirm, fDeleteMessages]);
			}
			else
			{
				App.Api.showErrorByCode(oResponse, Utils.i18n('MAILBOX/ERROR_MOVING_MESSAGES'));
			}
		}
		else
		{
			App.Api.showErrorByCode(oResponse, Utils.i18n('MAILBOX/ERROR_DELETING_MESSAGES'));
		}
		bFillMessages = true;
	}
	else
	{
		oFolder.commitDeleted(oRequest.Uids.split(','));
	}
	
	if (sCurrFolderFullName === oFolder.fullName() || oToFolder && sCurrFolderFullName === oToFolder.fullName())
	{
		oCurrFolder.markHasChanges();
		switch (this.uidList().filters())
		{
			case Enums.FolderFilter.Flagged:
				break;
			case Enums.FolderFilter.Unseen:
				if (this.waitForUnseenMessages())
				{
					this.requestCurrentMessageList(sCurrFolderFullName, this.page(), this.uidList().search(), this.uidList().filters(), bFillMessages);
				}
				break;
			default:
				this.requestCurrentMessageList(sCurrFolderFullName, this.page(), this.uidList().search(), this.uidList().filters(), bFillMessages);
				break;
		}
	}
	else if (sCurrFolderFullName !== oFolder.fullName())
	{
		App.Prefetcher.startFolderPrefetch(oFolder);
	}
	else if (oToFolder && sCurrFolderFullName !== oToFolder.fullName())
	{
		App.Prefetcher.startFolderPrefetch(oToFolder);
	}
};

CMailCache.prototype.onCopyMessagesResponse = function (oResponse, oRequest)
{
	var
		oResult = oResponse.Result,
		oFolder = this.folderList().getFolderByFullName(oRequest.Folder),
		oToFolder = this.folderList().getFolderByFullName(oRequest.ToFolder),
		oCurrFolder = this.folderList().currentFolder(),
		sCurrFolderFullName = oCurrFolder.fullName()
	;

	if (oResult === false)
	{
		App.Api.showErrorByCode(oResponse, Utils.i18n('MAILBOX/ERROR_COPYING_MESSAGES'));
	}

	if (sCurrFolderFullName === oFolder.fullName() || oToFolder && sCurrFolderFullName === oToFolder.fullName())
	{
		oCurrFolder.markHasChanges();
		this.requestCurrentMessageList(sCurrFolderFullName, this.page(), this.uidList().search(), '', false);
	}
	else if (sCurrFolderFullName !== oFolder.fullName())
	{
		App.Prefetcher.startFolderPrefetch(oFolder);
	}
	else if (oToFolder && sCurrFolderFullName !== oToFolder.fullName())
	{
		App.Prefetcher.startFolderPrefetch(oToFolder);
	}
};

/**
 * @param {string} sSearch
 */
CMailCache.prototype.searchMessagesInCurrentFolder = function (sSearch)
{
	var
		sFolder = this.folderList().currentFolderFullName() || 'INBOX',
		sUid = this.currentMessage() ? this.currentMessage().uid() : '',
		sFilters = this.uidList().filters()
	;
	
	App.Routing.setHash(App.Links.mailbox(sFolder, 1, sUid, sSearch, sFilters));
};

/**
 * @param {string} sSearch
 */
CMailCache.prototype.searchMessagesInInbox = function (sSearch)
{
	App.Routing.setHash(App.Links.mailbox(this.folderList().inboxFolderFullName() || 'INBOX', 1, '', sSearch, ''));
};

CMailCache.prototype.countMessages = function (oCountedFolder)
{
	var aSubfoldersMessagesCount = [],
		fCountRecursively = function(oFolder)
		{

			_.each(oFolder.subfolders(), function(oSubFolder, iKey) {
				if(oSubFolder.subscribed())
				{
					aSubfoldersMessagesCount.push(oSubFolder.unseenMessageCount());
					if (oSubFolder.subfolders().length && oSubFolder.subscribed())
					{
						fCountRecursively(oSubFolder);
					}
					/*else if (!oSubFolder.canExpand()) //for unknown reasons does not work computed in folder model
					{
						oSubFolder.subfoldersMessagesCount(0);
						oSubFolder.subfoldersMessagesCount.valueHasMutated();
					}*/
				}
			}, this);
		}
	;

	if (oCountedFolder.expanded() || oCountedFolder.isNamespace())
	{
		oCountedFolder.subfoldersMessagesCount(0);
	}
	else
	{
		fCountRecursively(oCountedFolder);
		oCountedFolder.subfoldersMessagesCount(
			_.reduce(aSubfoldersMessagesCount, function(memo, num){ return memo + num; }, 0)
		);
	}

};


/**
 * @constructor
 */
function CContactsCache()
{
	this.contacts = {};
	this.responseHandlers = {};
	
	this.vcardAttachments = [];
	
	this.recivedAnim = ko.observable(false).extend({'autoResetToFalse': 500});
}

/**
 * @param {string} sEmail
 */
CContactsCache.prototype.clearInfoAboutEmail = function (sEmail)
{
	this.contacts[sEmail] = undefined;
};

/**
 * @param {string} sEmail
 * @param {Function} fResponseHandler
 * @param {Object} oResponseContext
 */
CContactsCache.prototype.getContactByEmail = function (sEmail, fResponseHandler, oResponseContext)
{
	if (AppData.User.ShowContacts)
	{
		var
			oContact = this.contacts[sEmail],
			oParameters = {
				'Action': 'ContactGetByEmail',
				'Email': sEmail
			}
		;

		if (oContact !== undefined)
		{
			fResponseHandler.apply(oResponseContext, [oContact, sEmail]);
		}
		else
		{
			this.responseHandlers[sEmail] = {
				Handler: fResponseHandler,
				Context: oResponseContext
			};
			App.Ajax.send(oParameters, this.onContactGetByEmailResponse, this);
		}
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CContactsCache.prototype.onContactGetByEmailResponse = function (oResponse, oRequest)
{
	var
		oContact = null,
		oResponseData = this.responseHandlers[oRequest.Email]
	;
	
	if (oResponse.Result)
	{
		oContact = new CContactModel();
		oContact.parse(oResponse.Result);
	}
	
	this.contacts[oRequest.Email] = oContact;
	
	if (oResponseData)
	{
		oResponseData.Handler.apply(oResponseData.Context, [oContact, oRequest.Email]);
		this.responseHandlers[oRequest.Email] = undefined;
	}
};

/**
 * @param {Object} oVcard
 */
CContactsCache.prototype.addVcard = function (oVcard)
{
	this.vcardAttachments.push(oVcard);
};

/**
 * @param {Array} aUids
 */
CContactsCache.prototype.markVcardsNonexistentByUid = function (aUids)
{
	_.each(this.vcardAttachments, function (oVcard) {
		if (-1 !== _.indexOf(aUids, oVcard.uid()))
		{
			oVcard.isExists(false);
		}
	});
};

/**
 * @param {string} sFile
 */
CContactsCache.prototype.markVcardExistentByFile = function (sFile)
{
	_.each(this.vcardAttachments, function (oVcard) {
		if (oVcard.file() === sFile)
		{
			oVcard.isExists(true);
		}
	});
};

/**
 * @param {string} sName
 * @param {string} sEmail
 * @param {Function} fContactCreateResponse
 * @param {Object} oContactCreateContext
 */
CContactsCache.prototype.addToContacts = function (sName, sEmail, fContactCreateResponse, oContactCreateContext)
{
	var
		oParameters = {
			'Action': 'ContactCreate',
			'PrimaryEmail': 'Home',
			'UseFriendlyName': '1',
			'FullName': sName,
			'HomeEmail': sEmail
		}
	;

	App.Ajax.send(oParameters, fContactCreateResponse, oContactCreateContext);
	
	App.ContactsCache.recivedAnim(true);
};


/**
 * @constructor
 */
function CCalendarCache()
{
	// uses only for ical-attachments
	this.calendars = ko.observableArray([]);
	this.calendarsLoadingStarted = ko.observable(false);
	
	this.icalAttachments = [];
	
	this.recivedAnim = ko.observable(false).extend({'autoResetToFalse': 500});
	
	this.calendarSettingsChanged = ko.observable(false);
	this.calendarChanged = ko.observable(false);
	
	this.canRequestCalendarList = ko.observable(false);
}

/**
 * @param {Object} oIcal
 */
CCalendarCache.prototype.addIcal = function (oIcal)
{
	this.icalAttachments.push(oIcal);
	if (this.calendars().length === 0 && this.canRequestCalendarList())
	{
		this.requestCalendarList();
	}
};

CCalendarCache.prototype.firstRequestCalendarList = function ()
{
	this.canRequestCalendarList(true);
	
	if (this.icalAttachments.length > 0 && this.calendars().length === 0)
	{
		this.requestCalendarList();
	}
	
	return this.calendarsLoadingStarted();
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CCalendarCache.prototype.onCalendarListResponse = function (oResponse, oRequest)
{
	if (oResponse && oResponse.Result)
	{
		var
			oAccounts = AppData.Accounts,
			iCurrentId = oAccounts.currentId(),
			iCurrentEmail = oAccounts.getAccount(iCurrentId).email(),
			aEditableCalendars = _.filter(oResponse.Result, function (oCalendar) {
				return oCalendar.Owner === iCurrentEmail ||
					oCalendar.Access === Enums.CalendarAccess.Full ||
					oCalendar.Access === Enums.CalendarAccess.Write;
			})
		;
		this.calendars(_.map(aEditableCalendars, function (oCalendar) {
			return {'name': oCalendar.Name + ' <' + oCalendar.Owner + '>', 'id': oCalendar.Id};
		}));
	}
	
	this.calendarsLoadingStarted(false);
};

CCalendarCache.prototype.requestCalendarList = function ()
{
	if (!this.calendarsLoadingStarted())
	{
		App.Ajax.send({'Action': 'CalendarList'}, this.onCalendarListResponse, this);
		
		this.calendarsLoadingStarted(true);
	}
};

/**
 * @param {string} sUid
 */
CCalendarCache.prototype.markIcalNonexistent = function (sUid)
{
	_.each(this.icalAttachments, function (oIcal) {
		if (sUid === oIcal.uid())
		{
			oIcal.onEventDelete();
		}
	});
};

/**
 * @param {string} sFile
 * @param {string} sType
 * @param {string} sCancelDecision
 * @param {string} sReplyDecision
 * @param {string} sCalendarId
 * @param {string} sSelectedCalendar
 */
CCalendarCache.prototype.markIcalTypeByFile = function (sFile, sType, sCancelDecision, sReplyDecision,
														sCalendarId, sSelectedCalendar)
{
	_.each(this.icalAttachments, function (oIcal) {
		if (sFile === oIcal.file())
		{
			oIcal.type(sType);
			oIcal.cancelDecision(sCancelDecision);
			oIcal.replyDecision(sReplyDecision);
			oIcal.calendarId(sCalendarId);
			oIcal.selectedCalendarId(sSelectedCalendar);
		}
	});
};

/**
 * @param {string} sUid
 */
CCalendarCache.prototype.markIcalTentative = function (sUid)
{
	_.each(this.icalAttachments, function (oIcal) {
		if (sUid === oIcal.uid())
		{
			oIcal.onEventTentative();
		}
	});
};

/**
 * @param {string} sUid
 */
CCalendarCache.prototype.markIcalAccepted = function (sUid)
{
	_.each(this.icalAttachments, function (oIcal) {
		if (sUid === oIcal.uid())
		{
			oIcal.onEventAccept();
		}
	});
};


/**
 * @constructor
 */
function AbstractApp()
{
	this.browser = new CBrowser();
	
	this.favico = (!this.browser.ie8AndBelow && window.Favico) ? new window.Favico({
		'animation': 'none'
	}) : null;

	this.Ajax = new CAjax();
	this.Screens = new CScreens();
	this.Api = new CApi();
	this.Storage = new CStorage();

	this.helpdeskUnseenCount = ko.observable(0);
	this.helpdeskPendingCount = ko.observable(0);
	this.mailUnseenCount = ko.observable(0);
	
	this.InternetConnectionError = false;
}

AbstractApp.prototype.init = function ()
{
	
};

AbstractApp.prototype.collectScreensData = function ()
{

};

AbstractApp.prototype.run = function ()
{

};

AbstractApp.prototype.momentDateTriggerCallback = function ()
{
	var oItem = ko.dataFor(this);
	if (oItem && oItem.updateMomentDate)
	{
		oItem.updateMomentDate();
	}
};

AbstractApp.prototype.fastMomentDateTrigger = function ()
{
	$('.moment-date-trigger-fast').each(this.momentDateTriggerCallback);
};

/**
 * @param {string=} sTitle
 */
AbstractApp.prototype.setTitle = function (sTitle)
{
	document.title = '.';
	document.title = sTitle || '';
};

/**
 * @constructor
 */
function AppBase()
{
	AbstractApp.call(this);
	
	this.headerTabs = ko.observableArray([]);
	this.screensTitle = {};

	this.Phone = null;
	
	this.Routing = new CRouting();
	this.Links = new CLinkBuilder();
	this.MessageSender = new CMessageSender();
	this.Prefetcher = new CPrefetcher();
	this.MailCache = null;
	this.ContactsCache = new CContactsCache();
	this.CalendarCache = new CCalendarCache();

	this.currentScreen = this.Screens.currentScreen;
	this.currentScreen.subscribe(this.setTitle, this);
	this.focused = ko.observable(true);
	this.focused.subscribe(function() {
		if (!AppData.SingleMode && !window.opener)
		{
			this.setTitle();
		}
	}, this);

	this.filesRecievedAnim = ko.observable(false).extend({'autoResetToFalse': 500});

	this.init();
	
	this.newMessagesCount = this.MailCache.newMessagesCount;
	this.newMessagesCount.subscribe(this.setTitle, this);

	this.currentMessage = this.MailCache.currentMessage;
	this.currentMessage.subscribe(this.setTitle, this);

	this.notification = null;
	
	this.initHeaderInfo();
	
	this.sessionTimeoutFunctions = [];
	this.initSessionTimeout();
}

_.extend(AppBase.prototype, AbstractApp.prototype);

// proto

AppBase.prototype.initPhone = function (bAllow)
{
	return null;
};

AppBase.prototype.init = function ()
{
	var
		self = this,
		oRawUserSettings = /** @type {Object} */ AppData['User'],
		oUserSettings = new CUserSettingsModel(),
		aRawAccounts = AppData['Accounts'],
		oAccounts = new CAccountListModel(),
		oRawAppSettings = /** @type {Object} */ AppData['App'],
		oAppSettings = new CAppSettingsModel(!!oRawAppSettings.AllowOpenPGP && this.Api.isPgpSupported())
	;
	
	oAppSettings.parse(oRawAppSettings);
	AppData.App = oAppSettings;

	oUserSettings.parse(oRawUserSettings);
	AppData.User = oUserSettings;

	oAccounts.parse(Utils.pInt(AppData['Default']), aRawAccounts);
	AppData.Accounts = oAccounts;

	this.MailCache = new CMailCache();
	this.Phone = this.initPhone(oUserSettings.AllowVoice && !this.browser.ie && !bMobileApp);

	this.useGoogleAnalytics();
	
	this.collectScreensData();
	
	$(window).unload(function() {
		if (!bMobileDevice)
		{
			Utils.WindowOpener.closeAll();
		}
	});

	this.nowMoment = ko.observable(moment());
	window.setInterval(function () {
		self.fastMomentDateTrigger();
		if (moment().diff(self.nowMoment(), 'days') > 0)
		{
			self.nowMoment(moment());
		}
	}, 1000 * 60);
	
	if (this.browser.ie8AndBelow)
	{
		$('body').css('overflow', 'hidden');
	}
};

AppBase.prototype.collectScreensData = function () {};

/**
 * @param {Function} fHelpdeskUpdate
 */
AppBase.prototype.registerHelpdeskUpdateFunction = function (fHelpdeskUpdate)
{
	this.fHelpdeskUpdate = fHelpdeskUpdate;
};

AppBase.prototype.updateHelpdesk = function ()
{
	if (this.fHelpdeskUpdate)
	{
		this.fHelpdeskUpdate();
	}
};

AppBase.prototype.useGoogleAnalytics = function ()
{
	var
		ga = null,
		s = null
	;
	
	if (AppData.App.GoogleAnalyticsAccount && 0 < AppData.App.GoogleAnalyticsAccount.length)
	{
		window._gaq = window._gaq || [];
		window._gaq.push(['_setAccount', AppData.App.GoogleAnalyticsAccount]);
		window._gaq.push(['_trackPageview']);

		ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' === document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	}
};

AppBase.prototype.tokenProblem = function ()
{
	var
		sReloadFunc= 'window.location.reload(); return false;',
		sHtmlError = Utils.i18n('WARNING/TOKEN_PROBLEM_HTML', {'RELOAD_FUNC': sReloadFunc})
	;
	
	AppData.Auth = false;
	App.Api.showError(sHtmlError, true, true);
};

/**
 * @param {number=} iLastErrorCode
 */
AppBase.prototype.logout = function (iLastErrorCode)
{
	var oParameters = {'Action': 'SystemLogout'};
	
	if (iLastErrorCode)
	{
		oParameters.LastErrorCode = iLastErrorCode;
	}
	
	App.Ajax.send(oParameters, this.onLogout, this);
	
	AppData.Auth = false;
};

AppBase.prototype.authProblem = function ()
{
	this.logout(Enums.Errors.AuthError);
};

AppBase.prototype.onLogout = function ()
{
	Utils.WindowOpener.closeAll();
	
	App.Routing.finalize();
	
	if (AppData.App.CustomLogoutUrl !== '')
	{
		window.location.href = AppData.App.CustomLogoutUrl;
	}
	else
	{
		window.location.reload();
	}
};

AppBase.prototype.getAccounts = function ()
{
	return AppData.Accounts;
};

AppBase.prototype.run = function ()
{
	this.Screens.init();

	if (bIsIosDevice && AppData && AppData['Auth'] && AppData.App.IosDetectOnLogin && AppData.App.AllowIosProfile)
	{
		window.location.href = '?ios';
	}
	else if (AppData && AppData['Auth'])
	{
		AppData.SingleMode = this.Routing.isSingleMode();
		
		if (AppData.SingleMode && window.opener)
		{
			AppData.Accounts.populateIdentitiesFromSourceAccount(window.opener.App.getAccounts());
		}
		
		if (AppData.App.AllowWebMail)
		{
			this.MailCache.init();
		}
		
		if (AppData.HelpdeskRedirect && this.Routing.currentScreen !== Enums.Screens.Helpdesk)
		{
			this.Routing.setHash([Enums.Screens.Helpdesk]);
		}
		
		this.initRouting();
	}
	else if (AppData && AppData.App.CustomLoginUrl !== '')
	{
		window.location.href = AppData.App.CustomLoginUrl;
	}
	else
	{
		this.Screens.showCurrentScreen(Enums.Screens.Login);
		this.checkLoginScreenStartError();
	}

	this.phoneInOneTab();

	Utils.registerMailto(this.browser.firefox);
};

AppBase.prototype.checkLoginScreenStartError = function ()
{
	var iError = Utils.pInt(Utils.getRequestParam('error'));
	
	if (iError !== 0)
	{
		App.Api.showErrorByCode({'ErrorCode': iError, 'ErrorMessage': ''}, '', true);
	}

	if (AppData && AppData['LastErrorCode'] === Enums.Errors.AuthError)
	{
		this.Api.showError(Utils.i18n('WARNING/AUTH_PROBLEM'), false, true);
	}
};

AppBase.prototype.initRouting = function ()
{
	var bDefaultTabInEnum = !!_.find(Enums.Screens, function (sScreenInEnum) {
		return sScreenInEnum === AppData.App.DefaultTab;
	});

	if (bDefaultTabInEnum && (AppData.App.AllowWebMail || AppData.App.DefaultTab !== Enums.Screens.Mailbox))
	{
		this.Routing.init(AppData.App.DefaultTab);
	}
	else if (AppData.App.AllowWebMail)
	{
		this.Routing.init(Enums.Screens.Mailbox);
	}
	else if (this.headerTabs().length > 0)
	{
		this.Routing.init(this.headerTabs()[0].name);
	}
};

/**
 * @param {string} sName
 * @return {string}
 */
AppBase.prototype.getHelpLink = function (sName)
{
	return AppData && AppData['Links'] && AppData['Links'][sName] ? AppData['Links'][sName] : '';
};

/**
 * @param {string} sName
 * @param {string} sHeaderTitle
 * @param {string} sDocumentTitle
 * @param {string} sTemplateName
 * @param {Object} oViewModelClass
 * @param {boolean} koVisibleTab = undefined
 * @param {Object=} koRecivedAnim = undefined
 */
AppBase.prototype.addScreenToHeader = function (sName, sHeaderTitle, sDocumentTitle, sTemplateName,
	oViewModelClass, koVisibleTab, koRecivedAnim)
{
	var
		mHash = this.Routing.buildHashFromArray([sName]),
		oApp = this
	;
	
	if (sName === Enums.Screens.Helpdesk)
	{
		mHash = ko.computed(function () {
			return '#' + oApp.Routing.lastHelpdeskHash();
		});
	}
	
	Enums.Screens[sName] = sName;
	
	this.Screens.oScreens[sName] = {
		'Model': oViewModelClass,
		'TemplateName': sTemplateName
	};
	this.headerTabs.push({
		'name': sName,
		'title': sHeaderTitle,
		'hash': mHash,
		'koVisibleTab': koVisibleTab,
		'koRecivedAnim': koRecivedAnim
	});
	this.screensTitle[sName] = sDocumentTitle;
};

AppBase.prototype.registerSessionTimeoutFunction = function (oSessionTimeoutFunction)
{
	this.sessionTimeoutFunctions.push(oSessionTimeoutFunction);
};

AppBase.prototype.initSessionTimeout = function ()
{
	this.setSessionTimeout();
	$('body')
		.on('click', _.bind(this.setSessionTimeout, this))
		.on('keydown', _.bind(this.setSessionTimeout, this))
	;
};

AppBase.prototype.setSessionTimeout = function ()
{
	clearTimeout(this.iSessionTimeout);
	if (AppData && AppData.Auth && AppData.App.IdleSessionTimeout)
	{
		this.iSessionTimeout = setTimeout(_.bind(this.logoutBySessionTimeout, this), AppData.App.IdleSessionTimeout);
	}
};

AppBase.prototype.logoutBySessionTimeout = function ()
{
	if (AppData.Auth)
	{
		_.each(this.sessionTimeoutFunctions, function (oFunc) {
			oFunc();
		});
		_.delay(_.bind(this.logout, this), 500);
	}
};

AppBase.prototype.initHeaderInfo = function ()
{
	if (this.browser.ie)
	{
		$(document)
			.bind('focusin', _.bind(this.onFocus, this))
			.bind('focusout', _.bind(this.onBlur, this))
		;
	}
	else
	{
		$(window)
			.bind('focus', _.bind(this.onFocus, this))
			.bind('blur', _.bind(this.onBlur, this))
		;
	}
};

AppBase.prototype.onFocus = function ()
{
	this.focused(true);
};

AppBase.prototype.onBlur = function ()
{
	this.focused(false);
};

/**
 * @param {string=} sTitle
 */
AppBase.prototype.setTitle = function (sTitle)
{
	var 
		sNewMessagesCount = this.newMessagesCount(),
		sTemp = ''
	;
	
	sTitle = sTitle || '';
	
	if (this.focused() || sNewMessagesCount === 0 || !AppData.App.AllowWebMail)
	{
		sTitle = this.getTitleByScreen();
	}
	else
	{
		sTitle = Utils.i18n('TITLE/HAS_UNSEEN_MESSAGES_PLURAL', {'COUNT': sNewMessagesCount}, null, sNewMessagesCount) + ' - ' + AppData.Accounts.getEmail();
	}

	if (this.favico)
	{
		sTemp = 99 < sNewMessagesCount ? '99+' : sNewMessagesCount;
		if (this.favico._cachevalue !== sTemp)
		{
			this.favico._cachevalue = sTemp;
			this.favico.badge(sTemp);
		}
	}

	document.title = '.';
	document.title = sTitle;
};

AppBase.prototype.getTitleByScreen = function ()
{
	var
		sTitle = '',
		sSubject = ''
	;
	
	try
	{
		if (this.MailCache.currentMessage())
		{
			sSubject = this.MailCache.currentMessage().subject();
		}
	}
	catch (oError) {}
	
	switch (this.currentScreen())
	{
		case Enums.Screens.Login:
			sTitle = Utils.i18n('TITLE/LOGIN', null, '');
			break;
		case Enums.Screens.Mailbox:
			sTitle = AppData.Accounts.getEmail() + ' - ' + Utils.i18n('TITLE/MAILBOX');
			break;
		case Enums.Screens.Compose:
		case Enums.Screens.SingleCompose:
			sTitle = AppData.Accounts.getEmail() + ' - ' + Utils.i18n('TITLE/COMPOSE');
			break;
		case Enums.Screens.SingleMessageView:
			sTitle = AppData.Accounts.getEmail() + ' - ' + Utils.i18n('TITLE/VIEW_MESSAGE');
			if (sSubject)
			{
				sTitle = sSubject + ' - ' + sTitle;
			}
			break;
		default:
			if (this.screensTitle[this.currentScreen()])
			{
				sTitle = this.screensTitle[this.currentScreen()];
			}
			break;
	}
	
	if (sTitle === '')
	{
		sTitle = AppData.App.SiteName;
	}
	else
	{
		sTitle += (AppData.App.SiteName && AppData.App.SiteName !== '') ? ' - ' + AppData.App.SiteName : '';
	}

	return sTitle;
};

/**
 * @param {string} sAction
 * @param {string=} sTitle
 * @param {string=} sBody
 * @param {string=} sIcon
 * @param {Function=} fnCallback
 * @param {number=} iTimeout
 */
AppBase.prototype.desktopNotify = function (sAction, sTitle, sBody, sIcon, fnCallback, iTimeout)
{
	Utils.desktopNotify(sAction, sTitle, sBody, sIcon, fnCallback, iTimeout);
};

AppBase.prototype.phoneInOneTab = function ()
{
	// prevent load phone in other tabs
	if (this.Phone && window.localStorage)
	{
		var self = this;
		$(window).on('storage', function(e) {
			if (window.localStorage.getItem('p7phoneLoad') !== 'false')
			{
				window.localStorage.setItem('p7phoneLoad', 'false'); //triggering from other tabs
			}
		});

		window.localStorage.setItem('p7phoneLoad', (Math.floor(Math.random() * (1000 - 100) + 100)).toString()); //random - storage event triggering only if key has been changed
		window.setTimeout(function() { //wait until the triggering storage event
			if (!AppData.SingleMode && self.Phone && (window.localStorage.getItem('p7phoneLoad') !== 'false' || window.sessionStorage.getItem('p7phoneTab')))
			{
				self.Phone.init();
				window.sessionStorage.setItem('p7phoneTab', 'true'); //for phone tab detection, live only one session
			}
		}, 1000);
	}
};


/**
 * @constructor
 */
function AppMobile()
{
	AppBase.call(this);
}

_.extend(AppMobile.prototype, AppBase.prototype);

AppMobile.prototype.collectScreensData = function ()
{
	if (AppData.User.ShowContacts)
	{
		this.addScreenToHeader('contacts', Utils.i18n('HEADER/CONTACTS'), Utils.i18n('TITLE/CONTACTS'), 
			'Contacts_ContactsViewModel', CContactsViewModel, true, this.ContactsCache.recivedAnim);
	}
};

App = new AppMobile();
window.App = App;

/**
 * AppData.IsMobile:
 *	-1 - first time, mobile is not determined
 *	0 - mobile is switched off
 *	1 - mobile is switched on
 */
if (AppData.IsMobile === -1)
{
	/*jshint onevar: false*/
	var bMobile = !window.matchMedia('all and (min-width: 768px)').matches ? 1 : 0;
	/*jshint onevar: true*/

	window.App.Ajax.send({
		'Action': 'SystemSetMobile',
		'Mobile': bMobile
	}, function () {
		if (bMobile) {
			window.location.reload();
		} else {
			$(function () {
				_.defer(function () {
					App.run();
				});
			});
		}
	}, this);

}
else
{
	$(function () {
		_.defer(function () {
			App.run();
		});
	});
}

if (window.Modernizr && navigator)
{
	window.Modernizr.addTest('mobile', function() {
		return bMobileApp;
	});
}

window.AfterLogicApi = AfterLogicApi;

// export
window.Enums = Enums;

$html.removeClass('no-js').addClass('js');

if ($html.hasClass('pdf'))
{
	aViewMimeTypes.push('application/pdf');
	aViewMimeTypes.push('application/x-pdf');
}


}(jQuery, window, ko, crossroads, hasher));
