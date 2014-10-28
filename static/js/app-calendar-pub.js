
/*!
 * Copyright 2004-2014, AfterLogic Corp.
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
bExtApp = true;


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
	this.opera = !!window.opera;
	this.firefox = /firefox/.test(navigator.userAgent.toLowerCase());
	this.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
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
	this.aRequests = [];
}

/**
 * @param {string=} sAction = ''
 * @returns {Boolean}
 */
CAjax.prototype.hasOpenedRequests = function (sAction)
{
	sAction = Utils.isUnd(sAction) ? '' : sAction;
	
	this.aRequests = _.filter(this.aRequests, function (oReq) {
		var
			bComplete = oReq && oReq.Xhr.readyState === 4,
			bAbort = !oReq || oReq.Xhr.readyState === 0 && oReq.Xhr.statusText === 'abort',
			bSameAction = (sAction === '') || oReq && (oReq.Parameters.Action === sAction)
		;
		
		return oReq && !bComplete && !bAbort && bSameAction;
	});
	return this.aRequests.length > 0;
};

/**
 * @return {boolean}
 */
CAjax.prototype.isSearchMessages = function ()
{
	var bSearchMessages = false;
	
	_.each(this.aRequests, function (oReq) {
		if (oReq && oReq.Parameters && oReq.Parameters.Action === 'MessageList' && oReq.Parameters.Search !== '')
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
	var aActionsWithoutAuth = ['Login', 'LoginLanguageUpdate', 'Logout', 'AccountCreate', 
		'SetMobile', 'RegisterAccount', 'GetForgotAccountQuestion', 'ValidateForgotAccountQuestion', 'ChangeForgotAccountPassword'];
	
	return _.indexOf(aActionsWithoutAuth, sAction) !== -1;
};

CAjax.prototype.isAllowedExtAction = function (sAction)
{
	return sAction === 'SocialRegister' || sAction === 'HelpdeskRegister' || sAction === 'HelpdeskForgot' || sAction === 'HelpdeskLogin' || sAction === 'Logout';
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

	this.aRequests.push({Parameters: oParameters, Xhr: oXhr});
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
		aActionsWithoutAuth = [
			'SocialRegister',
			'HelpdeskRegister', 
			'HelpdeskForgot', 
			'HelpdeskLogin',
			'HelpdeskForgotChangePassword',
			'Logout',
			'CalendarList',
			'EventList',
			'FilesPub'
		],
		bAllowWithoutAuth = _.indexOf(aActionsWithoutAuth, oParameters.Action) !== -1
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
			this.abortRequestByActionName('MessageList', {'Folder': oParameters.Folder});
			this.abortRequestByActionName('Message');
			break;
		case 'MessageList':
		case 'MessageSetSeen':
			this.abortRequestByActionName('MessageList', {'Folder': oParameters.Folder});
			break;
		case 'FolderClear':
			this.abortRequestByActionName('MessageList', {'Folder': oParameters.Folder});
			
			// FolderCounts-request aborted during folder cleaning, not to get the wrong information.
			this.abortRequestByActionName('FolderCounts');
			break;
		case 'ContactList':
		case 'GlobalContactList':
			this.abortRequestByActionName('ContactList');
			this.abortRequestByActionName('GlobalContactList');
			break;
		case 'Contact':
		case 'GlobalContact':
			this.abortRequestByActionName('Contact');
			this.abortRequestByActionName('GlobalContact');
			break;
		case 'EventUpdate':
			this.abortRequestByActionName('EventUpdate', {'calendarId': oParameters.calendarId, 'uid': oParameters.uid});
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
	
	_.each(this.aRequests, function (oReq, iIndex) {
		bDoAbort = false;
		
		if (oReq && oReq.Parameters.Action === sAction)
		{
			switch (sAction)
			{
				case 'MessageList':
					if (oParameters.Folder === oReq.Parameters.Folder)
					{
						bDoAbort = true;
					}
					break;
				case 'EventUpdate':
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
			this.aRequests[iIndex] = undefined;
		}
	}, this);
	
	this.aRequests = _.compact(this.aRequests);
};

CAjax.prototype.abortAllRequests = function ()
{
	_.each(this.aRequests, function (oReq) {
		if (oReq)
		{
			oReq.Xhr.abort();
		}
	}, this);
	
	this.aRequests = [];
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
	
	if (typeof fResponseHandler === 'function')
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
	_.each(this.aRequests, function (oReq, iIndex) {
		if (oReq && _.isEqual(oReq.Parameters, oParameters))
		{
			this.aRequests[iIndex] = undefined;
		}
	}, this);
	
	this.aRequests = _.compact(this.aRequests);

	Utils.checkConnection(oParameters.Action, sType);
	
	if (!this.hasOpenedRequests())
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
	'CanNotChangePassword': 502,
	'AccountOldPasswordNotCorrect': 503,
	'FetcherIncServerNotAvailable': 702,
	'FetcherLoginNotCorrect': 703,
	'HelpdeskThrowInWebmail': 805,
	'HelpdeskUserNotExists': 807,
	'HelpdeskUserNotActivated': 808,
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
	'Personal': 0,
	'Corporate': 1,
	'Shared': 2,
	'GoogleDrive': 3,
	'Dropbox': 4
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
		var
			sTemplateID = ko.utils.unwrapObservable(fValueAccessor()),
			oEl = $('#' + sTemplateID)
		;

		if (oEl && oEl[0])
		{
			$(oElement).html(oEl.html().replace(/&lt;script(.*?)&gt;/i, '<script$1>').replace(/&lt;\/script(.*?)&gt;/i, '</script>'));
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
				},
				'mouseup': function () {
					return false;
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

ko.bindingHandlers.jhasfocus = {
	'init': function (oElement, fValueAccessor) {
		$(oElement).on('focus', function () {
			fValueAccessor()(true);
		}).on('blur', function () {
			fValueAccessor()(false);
		});
	},
	'update': function (oElement, fValueAccessor) {
		if (ko.utils.unwrapObservable(fValueAccessor()))
		{
			if (!$(oElement).is(':focus'))
			{
				$(oElement).focus();
			}
		}
		else
		{
			if ($(oElement).is(':focus'))
			{
				$(oElement).blur();
			}
		}
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
		var
			jqElement = $(oElement),
			oCommand = fValueAccessor()
			//fResizeTo = oCommand.resizeTo
		;

		setTimeout(function(){
			jqElement.splitter(_.defaults(
				oCommand,
				{
					//'name': ''
					//'sizeLeft': 200,
					//'minLeft': 20,
					//'minRight': 40
				}
			));

			/*if (fResizeTo)
			{
				fResizeTo.subscribe(function (nSize) {
					if (nSize)
					{
						jqElement.trigger('resize', [nSize]);
					}
				}, this);
			}*/
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
			jqDropHelper = jqElement.find('.dropdown_helper'),
			jqDropArrow = jqElement.find('.dropdown_arrow'),
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
			},
			fFitToScreen = function (iOffsetLeft) {
				oOffset = jqDropHelper.offset();
				if (!Utils.isUnd(oOffset))
				{
					iLeft = oOffset.left + 10;
					iFitToScreenOffset = $(window).width() - (iLeft + jqDropHelper.outerWidth());

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
//				else
//				{
//					jqElement.addClass(oCommand['expand']);
//				}
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

ko.bindingHandlers.contactcard = {
	'init': bMobileApp ? null : function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
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

ko.bindingHandlers.triggerInview = {
	'update': function (oElement, fValueAccessor) {
		if (fValueAccessor().trigger().length <= 0 )
		{
			return;
		}
		
		_.defer(function () {
			var 
				$element = $(oElement),
				frameHeight = $element.height(),
				oCommand = fValueAccessor(),
				elements = null,
				delayedScroll = null
			;
			
			oCommand = /** @type {{selector:string}}*/ oCommand;
			elements = $element.find(oCommand.selector);
			
			elements.each(function () {
				this.$el = $(this);
				this.inviewHeight = this.$el.height();
				this.inview = false;
			});
			
			delayedScroll = _.debounce(function () {
				
				elements.each(function () {
					var 
						inview = this.inview || false,
						elOffset = this.$el.position().top + parseInt(this.$el.css('margin-top'), 10)
					;
					
					if (elOffset > 0 && elOffset < frameHeight)
					{
						if (!inview)
						{
							this.inview = true;
							this.$el.trigger('inview');                        
						}
					}
					else
					{
						this.inview = false;
					}
				});
			}, 2000);
			
			$element.scroll(delayedScroll);
			
			delayedScroll();
		});
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
				return fValueAccessor().call(oViewModel, oEvent && oEvent.target ? ko.dataFor(oEvent.target) : null);
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
//						$('.calendar_event .scrollable_field').scrollTop(jqEl.height('scrollHeight'))
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
//		jqEl.on('input', function(oEvent, oData) {
//			fResize();
//		});
//		ko.bindingHandlers.event.init(oElement, function () {
//			return {
//				'keydown': function (oData, oEvent) {
//					fResize();
//					return true;
//				}
//			};
//		}, fAllBindingsAccessor, oViewModel);

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
					sRGBColor = "rgba("+oHex2Rgb.r+","+oHex2Rgb.g+","+oHex2Rgb.b
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

		if (oOptions.color.subscribe !== undefined) {
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

/**
 * @param {...*} params
 */
Utils.log = function (params)
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
		sLog = $log.html(),
		aLog = sLog.split(/<br {0,1}\/{0,1}><br {0,1}\/{0,1}>/),
		aNewRow = []
	;
	
	_.each(arguments, function (mArg) {
		var sRowPart = typeof(mArg) === 'string' ? mArg : JSON.stringify(mArg, fCensor);
		if (aNewRow.length === 0)
		{
			sRowPart = '<b>' + sRowPart + '</b>';
		}
		aNewRow.push(sRowPart);
	});
	
	Utils.$log = $log;
	
	if (aLog.length > 200)
	{
		aLog.shift();
	}
	
	aLog.push(Utils.encodeHtml(aNewRow.join(', ')));
	
	$log.html(aLog.join('<br /><br />'));
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
			case 'MessageList':
			case 'MessageListByUids':
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
			case 'Message':
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
			case 'Messages':
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
 * 
 * return {Array}
 */
Utils.getArrayRecipients = function (sRecipients)
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
						if (!inList && Utils.isCorrectEmail(oRecipient.email))
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
 * @param {number} iAccountId
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
	 * 
	 * @return Object
	 */
	openTab: function (sUrl)
	{
		var oWin = null;

		oWin = window.open(sUrl, '_blank');
		oWin.focus();
		oWin.name = AppData.Accounts.currentId();

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
		oWin.name = AppData.Accounts.currentId();

		this._aOpenedWins.push(oWin);
		
		return oWin;
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
		sTitle = Utils.trim(sSubject.replace(/[\n\r]/, ' ')),
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
			if (oData && oData.action === 'show' && window.Notification.permission !== 'denied') //granted, denied, default
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

		/*App.desktopNotify({
			action:'show',
			title: 'QQQ',
			icon: 'skins/wm_logo_140x140.png',
			body: 'eeeeeeeee'
		});
		App.desktopNotify({
			action:'show',
			title: 'WWW',
			tag: 'www',
			body: 'ooooooooo'
		});
		setTimeout(function() {
			App.desktopNotify({
				action:'show',
				title: 'RRR',
				body: 'pppppppp'
			});
		}, 1000);
		setTimeout(function() {
			App.desktopNotify({
				tag: 'www',
				action:'hide'
			});
		}, 3000);
		setTimeout(function() {
			App.desktopNotify({
				action:'hideAll'
			});
		}, 4000);*/
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
		if(bAwoke)
		{
			App.Api.hideError(true);
		}
	}, 5000);

	return function (sAction, sStatus)
	{
		clearTimeout(iTimer);
		if(sStatus !== 'error')
		{
			App.Api.hideError(true);
		}
		else
		{
			if (sAction === 'Ping')
			{
				//if (!bAwoke) {
				App.Api.showError(Utils.i18n('WARNING/NO_INTERNET_CONNECTION'), false, true, true);
				iTimer = setTimeout(function () {
					App.Ajax.send({'Action': 'Ping'});
				}, 60000);
				//}
			}
			else
			{
				App.Ajax.send({'Action': 'Ping'});
			}
		}
	};
}());

Utils.loadScript = function loadScript(sUrl, fCallback, aParams, sFuncName) 
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

(function ($) {
 
 /**
  * @param {{name:string,resizeFunc:Function}} args
  */
 $.fn.splitter = function(args) {

	args = args || {};

	return this.each(function () {
		
		var
			bIsMouseSplit = false,
			storageKey = args.name,
			initPosition = 0,
			nSize = 0,
			oLastState = {},
			oLastStateReserve = {},
			startSplitMouse = function (e) {
				bIsMouseSplit = true;
				bar.addClass(opts['activeClass']);

				opts['_posSplit'] = -((rtl ? splitter._overallWidth - e[opts['eventPos']] : e[opts['eventPos']]) - panes.get(0)[opts['pxSplit']] );
				
				$('body')
					.attr({'unselectable': "on"})
					.addClass('unselectable');

				$(document)
					.bind('mousemove', doSplitMouse)
					.bind('mouseup', endSplitMouse);
			},
			doSplitMouse = function (e) {
				var newPos = (rtl ? splitter._overallWidth - e[opts['eventPos']] : e[opts['eventPos']]) + opts['_posSplit'];
				resplit(newPos);
				
				if (Utils.isFunc(args.resizeFunc))
				{
					args.resizeFunc();
				}
			},
			endSplitMouse = function endSplitMouse(e) {
				bar.removeClass(opts['activeClass']);

				$('body')
					.attr({'unselectable': 'off'})
					.removeClass('unselectable');

				// Store 'width' data
				if (storageKey)
				{
					App.Storage.setData(storageKey + 'ResizerWidth', panes.get(0)[opts['pxSplit']]);
				}

				$(document)
					.unbind('mousemove', doSplitMouse)
					.unbind('mouseup', endSplitMouse);
				
				if (Utils.isFunc(args.resizeFunc))
				{
					args.resizeFunc();
				}
			},
			resplit = function (newPosition, bIgnoreSizeLimits) {

				if (!bIgnoreSizeLimits) { // Constrain new splitbar position to fit pane size limits
					newPosition = window.Math.max(
						panes.get(0)._min, splitter._overallWidth - panes.get(1)._max,
						window.Math.min(newPosition, panes.get(0)._max, splitter._overallWidth - panes.get(1)._min)
					);
				}

				panes.get(0).$.css(opts['split'], newPosition);
				panes.get(1).$.css(opts['split'], splitter._overallWidth - newPosition);

				if (!App.browser.ie8AndBelow)
				{
					panes.trigger('resize');
				}
			},
			dimSum = function (elem, dims) {
				// Opera returns -1 for missing min/max width, turn into 0
				var sum = 0, i = 1;
				for (; i < arguments.length; i++)
				{
					sum += window.Math.max(window.parseInt(elem.css(arguments[i]), 10) || 0, 0);
				}
				
				return sum;
			},
			vh = (args.splitHorizontal ? 'h' : args.splitVertical ? 'v' : args.type) || 'v',
			opts = $.extend({
				'activeClass': 'active',	// class name for active splitter
				'pxPerKey': 8,			// splitter px moved per keypress
				'tabIndex': 0,			// tab order indicator
				'accessKey': ''			// accessKey for splitbar
			},{
				v: {					// Vertical splitters:
					'keyLeft': 39, 'keyRight': 37,
					'type': 'v', 'eventPos': "pageX", 'origin': "left",
					'split': "width",  'pxSplit': "offsetWidth",  'side1': "Left", 'side2': "Right",
					'fixed': "height", 'pxFixed': "offsetHeight", 'side3': "Top",  'side4': "Bottom"
				},
				h: {					// Horizontal splitters:
					'keyTop': 40, 'keyBottom': 38,
					'type': 'h', 'eventPos': "pageY", 'origin': "top",
					'split': "height", 'pxSplit': "offsetHeight", 'side1': "Top",  'side2': "Bottom",
					'fixed': "width",  'pxFixed': "offsetWidth",  'side3': "Left", 'side4': "Right"
				}
			}[vh], args),
			
			splitter = $(this),
			panes = $(">*:not(css3pie)", splitter).each(function(){this.$ = $(this);}),
			bar = $('.resize_handler', panes.get(0))
				.attr({'unselectable': 'on'})
				.bind('mousedown', startSplitMouse),
			rtl = splitter.css('direction') === 'rtl'
		;

		panes.get(0)._paneName = opts['side1'];
		panes.get(1)._paneName = opts['side2'];
		
		panes.each(function(){
			this._min = opts['min' + this._paneName] || dimSum(this.$, 'min-' + opts['split']);
			this._max = opts['max' + this._paneName] || dimSum(this.$, 'max-' + opts['split']) || 9999;
			this._init = opts['size' + this._paneName] === undefined ?
				window.parseInt($.css(this, opts['split']), 10) : opts['size' + this._paneName];
		});

		// Determine initial position, get from cookie if specified
		if (storageKey)
		{
			initPosition = App.Storage.getData(storageKey + 'ResizerWidth') || panes.get(0)._init;
		}
		else
		{
			initPosition = panes.get(0)._init;
		}

		/*// no need because we store only primary (left or top) panel
		if (!isNaN(panes.get(1)._init))	// recalc initial B size as an offset from the top or left side
		{
			initPosition = splitter[0][opts['pxSplit']] - panes.get(1)._init;
		}*/
		
		if (isNaN(initPosition))
		{
			initPosition = splitter[0][opts['pxSplit']];
			initPosition = window.Math.round(initPosition / panes.length);
		}
		// Resize event propagation and splitter sizing
		if (opts['resizeToWidth'] && !(App.browser.ie8AndBelow))
		{
			$(window).bind('resize', function(e) {
				if (e.target !== this)
				{
					return;
				}
				splitter.trigger('resize'); 
			});
		}

		splitter.bind('resize', function (ev, size, sCommand, bIgnoreSizeLimits) {

			var tKey = ev.target.className + '_' + sCommand;
			if (bIsMouseSplit)
			{
				oLastState = {};
			}

			// Custom events bubble in jQuery 1.3; don't get into a Yo Dawg
			if (ev.target !== this)
			{
				return;
			}

			// Determine new width/height of splitter container
			splitter._overallWidth = splitter[0][opts['pxSplit']];

			// Return if splitter isn't visible or content isn't there yet
			if (splitter._overallWidth <= 0)
			{
				return;
			}

			if (!(opts['sizeRight'] || opts['sizeBottom']))
			{
				nSize = panes.get(0)[opts['pxSplit']];
			}
			else
			{
				nSize = splitter._overallWidth - panes.get(1)[opts['pxSplit']];
			}

			if (isNaN(size))
			{
				size = nSize;
			}
			else if (sCommand)
			{
				bIsMouseSplit = false;

				if (oLastState[tKey])
				{
					size = oLastState[tKey];
					oLastState[tKey] = null;
				}
				else
				{
					if (size === nSize)
					{
						oLastState[tKey] = null;
						size = oLastStateReserve[tKey];
					}
					else
					{
						oLastState[tKey] = oLastStateReserve[tKey] = nSize;
					}

					_.each(oLastState, function(num, key) {
						if (key !== tKey)
						{
							oLastState[key] = null;
						}
					});
				}
			}

			resplit(size, bIgnoreSizeLimits);
			
		}).trigger('resize', [initPosition]);
	});
};

})(jQuery);
(function ($) {

/**
 * extend jQuery autocomplete
 */

	// styling results
	$.ui.autocomplete.prototype._renderItem = function (ul, item) {

		item.label = Utils.trim(item.label);
		
		item.label = item.label.replace(/\([^\)]+\)$/i, function (sMatch) {
			return '~~1~~' + sMatch + '~~2~~';
		});
		
		item.label = item.label.replace(/<[^>]+>$/i, function (sMatch) {
			return '~~1~~' + sMatch + '~~2~~';
		});

		item.label = Utils.encodeHtml(item.label);

		item.label = item.label
			.replace(/~~1~~/, '<span style="opacity: 0.5">')
			.replace(/~~2~~/, '</span>')
		;
		
		return $('<li>')
			.append('<a>' + item.label + '</a>')
			.appendTo(ul);
	};

	// add categories
	$.ui.autocomplete.prototype._renderMenu = function(ul, items) {
		
		var
			self = this,
			currentCategory = ''
		;

		$.each(items, function(index, item) {
			
			if (item && item.category && item.category !== currentCategory) {
				currentCategory = item.category;
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
			}

			self._renderItemData(ul, item);
		});
	};

})(jQuery);

/**
 * @constructor
 */
function CApi()
{
	this.openPgp = null;
	this.openPgpCallbacks = [];
}

/**
 * @param {string} sToAddresses
 */
CApi.prototype.openComposeMessage = function (sToAddresses)
{
	App.Routing.setHash(App.Links.composeWithToField(sToAddresses));
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
 * @param {number=} iDelay
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
 */
CApi.prototype.showErrorByCode = function (oResponse, sDefaultError)
{
	var
		iErrorCode = oResponse.ErrorCode,
		sErrorMessage = oResponse.ErrorMessage || ''
	;
	
	if (sErrorMessage !== '')
	{
		sErrorMessage = ' (' + sErrorMessage + ')';
	}
	
	switch (iErrorCode)
	{
		default:
			if (sDefaultError && sDefaultError.length > 0)
			{
				this.showError(sDefaultError + sErrorMessage);
			}
			else if (oResponse.ErrorMessage && oResponse.ErrorMessage !== '')
			{
				this.showError(oResponse.ErrorMessage);
			}
			break;
		case Enums.Errors.AuthError:
			this.showError(Utils.i18n('WARNING/LOGIN_PASS_INCORRECT') + sErrorMessage);
			break;
		case Enums.Errors.DemoLimitations:
			this.showError(Utils.i18n('DEMO/WARNING_THIS_FEATURE_IS_DISABLED') + sErrorMessage);
			break;
		case Enums.Errors.Captcha:
			this.showError(Utils.i18n('WARNING/CAPTCHA_IS_INCORRECT') + sErrorMessage);
			break;
		case Enums.Errors.CanNotGetMessage:
			this.showError(Utils.i18n('MESSAGE/ERROR_MESSAGE_DELETED') + sErrorMessage);
			break;
		case Enums.Errors.CanNotChangePassword:
			this.showError(Utils.i18n('WARNING/UNABLE_CHANGE_PASSWORD') + sErrorMessage);
			break;
		case Enums.Errors.AccountOldPasswordNotCorrect:
			this.showError(Utils.i18n('WARNING/CURRENT_PASSWORD_NOT_CORRECT') + sErrorMessage);
			break;
		case Enums.Errors.FetcherIncServerNotAvailable:
			this.showError(Utils.i18n('WARNING/FETCHER_SAVE_ERROR') + sErrorMessage);
			break;
		case Enums.Errors.FetcherLoginNotCorrect:
			this.showError(Utils.i18n('WARNING/FETCHER_SAVE_ERROR') + sErrorMessage);
			break;
		case Enums.Errors.HelpdeskUserNotExists:
			this.showError(Utils.i18n('HELPDESK/ERROR_FORGOT_NO_ACCOUNT') + sErrorMessage);
			break;
		case Enums.Errors.MailServerError:
			this.showError(Utils.i18n('WARNING/CANT_CONNECT_TO_SERVER') + sErrorMessage);
			break;
		case Enums.Errors.DataTransferFailed:
			this.showError(Utils.i18n('WARNING/DATA_TRANSFER_FAILED') + sErrorMessage);
			break;
		case Enums.Errors.NotDisplayedError:
			if (oResponse.ErrorMessage && oResponse.ErrorMessage !== '')
			{
				this.showError(oResponse.ErrorMessage);
			}
			break;
	}
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

//	window.console.log(oResult);
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
 */
function CalendarPopup()
{
	this.fCallback = null;
	
	this.calendarId = ko.observable(null);
	this.calendarName = ko.observable('');
	this.calendarDescription = ko.observable('');
	
	this.calendarNameFocus = ko.observable(false);
	this.calendarDescriptionFocus = ko.observable(false);
	
	this.colors = ko.observableArray([]);
	this.selectedColor = ko.observable(this.colors()[0]);
	
	this.popupTitle = ko.observable('');
}

CalendarPopup.prototype.clearFields = function ()
{
	this.calendarName('');
	this.calendarDescription('');
	this.selectedColor(this.colors[0]);
	this.calendarId(null);
};

/**
 * @param {Function} fCallback
 * @param {Array} aColors
 * @param {Object} oCalendar
 */
CalendarPopup.prototype.onShow = function (fCallback, aColors, oCalendar)
{
	this.clearFields();
	if (Utils.isFunc(fCallback))
	{
		this.fCallback = fCallback;
	}
	if (!Utils.isUnd(aColors))
	{
		this.colors(aColors);
		this.selectedColor(aColors[0]);		
	}
	if (!Utils.isUnd(oCalendar))
	{
		this.popupTitle(oCalendar.name() ? Utils.i18n("CALENDAR/TITLE_EDIT_CALENDAR") : Utils.i18n("CALENDAR/TITLE_CREATE_CALENDAR"));
		this.calendarName(oCalendar.name ? oCalendar.name() : '');
		this.calendarDescription(oCalendar.description ? oCalendar.description() : '');
		this.selectedColor(oCalendar.color ? oCalendar.color() : '');
		this.calendarId(oCalendar.id ? oCalendar.id : null);
	} else {
		this.popupTitle(Utils.i18n("CALENDAR/TITLE_CREATE_CALENDAR"));
	}
};

/**
 * @return {string}
 */
CalendarPopup.prototype.popupTemplate = function ()
{
	return 'Popups_Calendar_CalendarPopupViewModel';
};

CalendarPopup.prototype.onSaveClick = function ()
{
	if (this.calendarName() === '')
	{
		App.Screens.showPopup(AlertPopup, [Utils.i18n('CALENDAR/WARNING_BLANK_CALENDAR_NAME')]);
	}
	else
	{
		if (this.fCallback)
		{
			this.fCallback(this.calendarName(), this.calendarDescription(), this.selectedColor(), this.calendarId());
			this.clearFields();
		}
		this.closeCommand();
	}
};

CalendarPopup.prototype.onCancelClick = function ()
{
	this.closeCommand();
};
/**
 * @constructor
 */
function CalendarImportPopup()
{
	this.fCallback = null;
	
	this.oJua = null;
	this.allowDragNDrop = ko.observable(false);
	
	this.visibility = ko.observable(false);
	this.importing = ko.observable(false);

	this.color	= ko.observable('');
	this.calendarId	= ko.observable('');
}

/**
 * @param {Function} fCallback
 * @param {Object} oCalendar
 */
CalendarImportPopup.prototype.onShow = function (fCallback, oCalendar)
{
	if (Utils.isFunc(fCallback))
	{
		this.fCallback = fCallback;
	}
	if (!Utils.isUnd(oCalendar))
	{
		this.color(oCalendar.color ? oCalendar.color() : '');
		this.calendarId(oCalendar.id ? oCalendar.id : '');
	}
};

/**
 * @return {string}
 */
CalendarImportPopup.prototype.popupTemplate = function ()
{
	return 'Popups_Calendar_ImportPopupViewModel';
};

CalendarImportPopup.prototype.onFileImportStart = function ()
{
	this.importing(true);
};

/**
 * @param {string} sUid
 * @param {boolean} bResult
 * @param {Object} oData
 */
CalendarImportPopup.prototype.onFileImportComplete = function (sUid, bResult, oData)
{
	this.importing(false);
	this.fCallback();
	this.closeCommand();
};

/**
 * @param {Object} $oViewModel
 */
CalendarImportPopup.prototype.onApplyBindings = function ($oViewModel)
{
	var self = this;
	this.oJua = new Jua({
		'action': '?/Upload/Calendars/',
		'name': 'jua-uploader',
		'queueSize': 1,
		'clickElement': $('#jue_import_button', $oViewModel),
		'disableAjaxUpload': false,
		'disableDragAndDrop': true,
		'disableMultiple': true,
		'hidden': {
			'Token': function () {
				return AppData.Token;
			},
			'AccountID': function () {
				return AppData.Accounts.currentId();
			},
			'AdditionalData':  function () {
				return JSON.stringify({
					'CalendarID': self.calendarId()
				});
			}
		}
	});

	this.oJua
		.on('onStart', _.bind(this.onFileImportStart, this))
		.on('onComplete', _.bind(this.onFileImportComplete, this))
	;
	
	this.allowDragNDrop(this.oJua.isDragAndDropSupported());
};

CalendarImportPopup.prototype.onCancelClick = function ()
{
	this.closeCommand();
};
/**
 * @constructor
 */
function CalendarSharePopup()
{
	this.defaultAccount = AppData.Accounts.getDefault();

	this.fCallback = null;

	this.calendarId = ko.observable(null);
	this.selectedColor = ko.observable('');
	this.calendarUrl = ko.observable('');
	this.exportUrl = ko.observable('');
	this.icsLink = ko.observable('');
	this.isPublic = ko.observable(false);
	this.shares = ko.observableArray([]);
	this.oldShares = ko.observableArray([]);
	this.owner = ko.observable('');

	this.recivedAnim = ko.observable(false).extend({'autoResetToFalse': 500});
	this.whomAnimate = ko.observable('');

	this.shareAutocompleteItem = ko.observable(null);
	this.shareAutocompleteItem.subscribe(function (oItem) {
		if (oItem) { this.setGlobal(oItem.email, this.newShareList()); }
	}, this);

	this.newShareList = ko.observableArray([]);
	this.newShare = ko.observable('');
	this.newShareFocus = ko.observable(false);
	this.newShareAccess = ko.observable(Enums.CalendarAccess.Read);
	this.sharedToAll = ko.observable(false);
	this.sharedToAllAccess = ko.observable(Enums.CalendarAccess.Read);
	this.canAdd = ko.observable(false);
	this.aAccess = [
		{'value': Enums.CalendarAccess.Read, 'display': Utils.i18n('CALENDAR/CALENDAR_ACCESS_READ')},
		{'value': Enums.CalendarAccess.Write, 'display': Utils.i18n('CALENDAR/CALENDAR_ACCESS_WRITE')}
	];

	this.autocompleteCallbackBinded = _.bind(this.autocompleteCallback, this);
	this.showGlobalContacts = !!AppData.User.ShowGlobalContacts;
}

/**
 * @param {Function} fCallback
 * @param {Object} oCalendar
 */
CalendarSharePopup.prototype.onShow = function (fCallback, oCalendar)
{
	if (Utils.isFunc(fCallback))
	{
		this.fCallback = fCallback;
	}
	if (!Utils.isUnd(oCalendar))
	{
		this.selectedColor(oCalendar.color());
		this.calendarId(oCalendar.id);
		this.calendarUrl(oCalendar.davUrl() + oCalendar.url());
		this.exportUrl(oCalendar.exportUrl());
		this.icsLink(oCalendar.davUrl() + oCalendar.url() + '?export');
		this.isPublic(oCalendar.isPublic());
		this.shares(oCalendar.shares().slice(0));
		this.oldShares(oCalendar.shares().slice(0));
		this.owner(oCalendar.owner());

		this.sharedToAll(oCalendar.isSharedToAll());
		this.sharedToAllAccess(oCalendar.sharedToAllAccess);
	}
};

/**
 * @return {string}
 */
CalendarSharePopup.prototype.popupTemplate = function ()
{
	return 'Popups_Calendar_SharePopupViewModel';
};

CalendarSharePopup.prototype.onSaveClick = function ()
{
	if (this.fCallback)
	{
		//this.addShare();
		this.fCallback(this.calendarId(), this.isPublic(), this.shares(), this.sharedToAll(), this.sharedToAllAccess());
	}
	this.closePopup();
};

CalendarSharePopup.prototype.onCancelClick = function ()
{
	this.shares(this.oldShares());
	this.closePopup();
};

CalendarSharePopup.prototype.closePopup = function ()
{
	this.cleanAll();

	this.closeCommand();
};

CalendarSharePopup.prototype.cleanAll = function ()
{
	this.newShare('');
	this.newShareAccess(Enums.CalendarAccess.Read);
	this.shareToAllAccess = ko.observable(Enums.CalendarAccess.Read);
	this.shareAutocompleteItem(null);
	this.canAdd(false);
};

CalendarSharePopup.prototype.addShare = function ()
{
	var sEmail = this.shareAutocompleteItem() ? this.shareAutocompleteItem().email : this.newShare();

	if (this.canAdd())
	{
		this.shares.push(
			{
				name: this.shareAutocompleteItem() ? this.shareAutocompleteItem().name : '',
				email: sEmail,
				access: this.newShareAccess()
			});

		this.cleanAll();
	}
	else
	{
		this.whomAnimate(sEmail);
		this.recivedAnim(true);
	}
};

/**
 * @param {Object} oItem
 */
CalendarSharePopup.prototype.removeShare = function (oItem)
{
	this.shares.remove(oItem);
};

/**
 * @param {string} sTerm
 * @param {Function} fResponse
 */
CalendarSharePopup.prototype.autocompleteCallback = function (sTerm, fResponse)
{
	var oParameters = {
			'Action': 'ContactSuggestions',
			'Search': sTerm,
			'GlobalOnly': '1'
		}
	;

	this.shareAutocompleteItem(null);

	sTerm = Utils.trim(sTerm);
	if ('' !== sTerm)
	{
		App.Ajax.send(oParameters, function (oData) {
			var aList = [];
			if (oData && oData.Result && oData.Result && oData.Result.List)
			{
				aList = _.map(oData.Result.List, function (oItem) {
					return oItem && oItem.Email && oItem.Email !== this.owner() ?
						(oItem.Name && 0 < Utils.trim(oItem.Name).length ?
						oItem.ForSharedToAll ? {value: oItem.Name, name: oItem.Name, email: oItem.Email, frequency: oItem.Frequency} :
						{value:'"' + oItem.Name + '" <' + oItem.Email + '>', name: oItem.Name, email: oItem.Email, frequency: oItem.Frequency} : {value: oItem.Email, name: '', email: oItem.Email, frequency: oItem.Frequency}) : null;
				}, this);

				aList = _.sortBy(_.compact(aList), function(num){
					return num.frequency;
				}).reverse();

				this.newShareList(aList);

				this.setGlobal(this.newShare(), aList);
			}
			fResponse(aList);
		}, this);
	}
	else
	{
		fResponse([]);
	}
};

/**
 * @param {string} sText
 * @param {Array} aList
 */
CalendarSharePopup.prototype.setGlobal = function (sText, aList)
{
	var
		isInGlobal = _.any(aList, function (sItem) {
			return sItem.email === sText;
		}, this),
		isAlreadyAdded = _.any(this.shares(), function (sItem) {
			return sItem.email === sText;
		}, this)
	;

	this.canAdd(isInGlobal && !isAlreadyAdded);
};

/**
 * @param {string} sEmail
 */
CalendarSharePopup.prototype.itsMe = function (sEmail)
{
	return (sEmail === this.defaultAccount.email());
};
/**
 * @constructor
 */
function CalendarGetLinkPopup()
{
	this.fCallback = null;

	this.calendarId = ko.observable(null);
	this.selectedColor = ko.observable('');
	this.calendarUrl = ko.observable('');
	this.exportUrl = ko.observable('');
	this.icsLink = ko.observable('');
	this.isPublic = ko.observable(false);
	this.pubUrl = ko.observable('');
}

/**
 * @param {Function} fCallback
 * @param {Object} oCalendar
 */
CalendarGetLinkPopup.prototype.onShow = function (fCallback, oCalendar)
{
	if (Utils.isFunc(fCallback))
	{
		this.fCallback = fCallback;
	}
	if (!Utils.isUnd(oCalendar))
	{
		this.selectedColor(oCalendar.color());
		this.calendarId(oCalendar.id);
		this.calendarUrl(oCalendar.davUrl() + oCalendar.url());
		this.exportUrl(oCalendar.exportUrl());
		this.icsLink(oCalendar.davUrl() + oCalendar.url() + '?export');
		this.isPublic(oCalendar.isPublic());
		this.pubUrl(oCalendar.pubUrl());
		this.exportUrl(oCalendar.exportUrl());
	}
};

/**
 * @return {string}
 */
CalendarGetLinkPopup.prototype.popupTemplate = function ()
{
	return 'Popups_Calendar_GetLinkPopupViewModel';
};

CalendarGetLinkPopup.prototype.onCancelClick = function ()
{
	if (this.fCallback)
	{
		this.fCallback(this.calendarId(), this.isPublic());
	}
	this.closeCommand();
};
/**
 * @constructor
 */
function CalendarEventPopup()
{
	this.defaultAccount = (AppData.Accounts) ? AppData.Accounts.getDefault() : null;

	this.modified = false;
	this.isPublic = bExtApp;
	this.isEditable = ko.observable(false);
	this.isEditableReminders = ko.observable(false);
	this.selectedCalendarIsShared = ko.observable(false);
	this.selectedCalendarIsEditable = ko.observable(false);

	this.callbackSave = null;
	this.callbackDelete = null;
	this.timeFormatMoment = 'HH:mm';
	this.dateFormatMoment = 'MM/DD/YYYY';
	this.dateFormatDatePicker = 'mm/dd/yy';
	this.ampmTimeFormat = ko.observable(false);

	this.calendarId = ko.observable(null);
	this.id = ko.observable(null);
	this.uid = ko.observable(null);
	this.recurrenceId = ko.observable(null);
	this.allEvents = ko.observable(Enums.CalendarEditRecurrenceEvent.AllEvents);

	this.isMyEvent = ko.observable(false);

	this.startDom = ko.observable(null);
	this.endDom = ko.observable(null);
	this.repeatEndDom = ko.observable(null);

	this.yearlyDate = ko.observable('');
	this.monthlyDate = ko.observable('');

	this.subject = ko.observable('');
	this.description = ko.observable('');

	this.startDate = ko.observable('');
	this.startTime = ko.observable('');
	this.startTime.subscribe(function () {
		this.selectStartDate();
	}, this);
	this.allDay = ko.observable(false);
	this.allDay.subscribe(function (arg) {
		if(!arg)
		{
			this.setActualTime();
		}
	}, this);

	this.endDate = ko.observable('');
	this.endTime = ko.observable('');
	this.endTime.subscribe(function () {
		this.selectEndDate();
	}, this);

	this.repeatEndDate = ko.observable('');

	this.isEvOneDay = ko.observable(true);
	this.isEvOneTime = ko.observable(true);

	this.isRepeat = ko.observable(false);

	this.location = ko.observable('');

	this.repeatPeriodOptions = ko.observableArray(this.getDisplayedPeriods());
	this.repeatWeekIntervalOptions = ko.observableArray([1, 2, 3, 4]);
	this.repeatMonthIntervalOptions = ko.observableArray(this.getDisplayedIntervals());
	this.alarmOptions = ko.observableArray(this.getDisplayedAlarms([5, 10, 15, 30, 60, 120, 180, 240, 300, 360, 420, 480, 540, 600, 660, 720, 1080, 1440, 2880, 4320, 5760, 10080, 20160])); // available minutes array
	this.timeOptions = ko.observableArray(this.getDisplayedTimes((AppData.User.defaultTimeFormat() !== Enums.TimeFormat.F24) ? 'hh:mm A' : 'HH:mm'));
	AppData.User.defaultTimeFormat.subscribe(function () {
		this.timeOptions(this.getDisplayedTimes((AppData.User.defaultTimeFormat() !== Enums.TimeFormat.F24) ? 'hh:mm A' : 'HH:mm'));
	}, this);

	this.displayedAlarms = ko.observableArray([]);
	this.displayedAlarms.subscribe(function () {
		this.disableAlarms();
	}, this);

	this.excluded = ko.observable(false);
	this.repeatPeriod = ko.observable(0);
	this.repeatPeriod.subscribe(function (iRepeatPeriod) {
		this.setDayOfWeek(iRepeatPeriod);
		this.isRepeat(!!iRepeatPeriod);
	}, this);
	this.repeatInterval = ko.observable(1);
	this.repeatEnd = ko.observable(0);
	this.repeatCount = ko.observable(null);
	this.repeatWeekNum = ko.observable(null);

	this.weekMO = ko.observable(false);
	this.weekTU = ko.observable(false);
	this.weekWE = ko.observable(false);
	this.weekTH = ko.observable(false);
	this.weekFR = ko.observable(false);
	this.weekSA = ko.observable(false);
	this.weekSU = ko.observable(false);

	this.appointment = ko.observable(false);
	this.attendees = ko.observableArray([]);
	this.attenderStatus = ko.observable(0);
	this.owner = ko.observable('');
	this.ownerName = ko.observable('');
	this.ownerDisplay = ko.computed(function(){
		return (this.ownerName() !== '') ? this.ownerName() : this.owner();
	}, this);

	this.recivedAnim = ko.observable(false).extend({'autoResetToFalse': 500});
	this.whomAnimate = ko.observable('');
	this.guestAutocompleteItem = ko.observable(null);
	this.guestAutocomplete = ko.observable('');
	this.guestEmailFocus = ko.observable(false);
	this.guestAutocomplete.subscribe(function (sItem) {
		if (sItem === '')
		{
			this.guestAutocompleteItem(null);
		}
	}, this);

	this.condition = ko.observable('');

	this.autosizeTrigger = ko.observable(true);

	this.calendars = null;

	this.calendarsList = ko.observableArray([]);
	this.calendarColor = ko.observable('');
	this.selectedCalendar = ko.observable('');
	this.selectedCalendarName = ko.observable('');
	this.selectedCalendar.subscribe(function (sValue) {
		if (sValue)
		{
			var oCalendar = this.calendars.getCalendarById(sValue);
			
			this.selectedCalendarName(oCalendar.name());
			this.selectedCalendarIsShared(oCalendar.isShared());
			this.selectedCalendarIsEditable(oCalendar.isEditable());
			this.changeCalendarColor(sValue);
		}
	}, this);
	
	this.subjectFocus = ko.observable(false);
//	this.subjectFocus.subscribe(function (sValue) {
//
//	}, this);

	this.descriptionFocus = ko.observable(false);
	this.locationFocus = ko.observable(false);

	this.dateEdit = ko.observable(false);
	this.repeatEdit = ko.observable(false);
	this.guestsEdit = ko.observable(false);
	this.defaultOptionsAfterRender = Utils.defaultOptionsAfterRender;
	this.isEditForm = ko.computed(function(){
		return !!this.id();
	}, this);

	this.callbackAttendeeActionDecline = null;

	this.calendarAppointments = AppData.User.AllowCalendar && AppData.User.CalendarAppointments;

	this.allChanges = ko.computed(function() {
		this.subject();
		this.description();
		this.location();
		this.isRepeat();
		this.allDay();
		this.repeatPeriod();
		this.repeatInterval();
		this.repeatEnd();
		this.repeatCount();
		this.repeatWeekNum();
		this.startDate();
		this.startTime();
		this.endDate();
		this.endTime();
		this.repeatEndDate();
		this.displayedAlarms();
		this.weekMO();
		this.weekTU();
		this.weekWE();
		this.weekTH();
		this.weekFR();
		this.weekSA();
		this.weekSU();
		this.attendees();
		this.selectedCalendar();

		this.modified = true;
	}, this);

	this.phaseArray = [''];
	
	_.each(Utils.i18n('CALENDAR/REMINDER_PHRASE').split(/\s/), function (sItem) {
		var iIndex = this.phaseArray.length - 1;
		if (sItem.substr(0,1) === '%' || this.phaseArray[iIndex].substr(-1,1) === '%')
		{
			this.phaseArray.push(sItem);
		}
		else
		{
			this.phaseArray[iIndex] += ' ' + sItem;
		}
	}, this);
}

/**
 * @param {Object} oElement
 * @param {Function} fSelect
 */
CalendarEventPopup.prototype.createDatePickerObject = function (oElement, fSelect)
{
	$(oElement).datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		monthNames: Utils.getMonthNamesArray(),
		dayNamesMin: Utils.i18n('DATETIME/DAY_NAMES_MIN').split(' '),
		firstDay: AppData.User.CalendarWeekStartsOn,
		showOn: "both",
		buttonText: "",
		buttonImage: "skins/Default/images/calendar-icon.png",
		buttonImageOnly: true,
		dateFormat: this.dateFormatDatePicker,
		onSelect: fSelect
	});

	$(oElement).mousedown(function() {
		$('#ui-datepicker-div').toggle();
	});
};


CalendarEventPopup.prototype.initializeDatePickers = function ()
{
	this.createDatePickerObject(this.startDom(), this.selectStartDate.bind(this));
	this.createDatePickerObject(this.endDom(), this.selectEndDate.bind(this));
	this.createDatePickerObject(this.repeatEndDom(), Utils.emptyFunction());

	this.startDom().datepicker( "option", "dateFormat", this.dateFormatDatePicker);
	this.endDom().datepicker( "option", "dateFormat", this.dateFormatDatePicker);
	this.repeatEndDom().datepicker( "option", "dateFormat", this.dateFormatDatePicker);
};

/**
 * @param {Object} oParameters
 */
CalendarEventPopup.prototype.onShow = function (oParameters)
{
	var
		oAccount = this.defaultAccount,
		owner = (oAccount) ? oAccount.email() : '',
		ownerName = (oAccount) ? oAccount.friendlyName() : '',
		oEnd = oParameters.End.clone(),
		sAttendee = ''
	;
	
	this.isMyEvent(owner === (oParameters.Owner || owner));

	this.callbackSave = oParameters.CallbackSave;
	this.callbackDelete = oParameters.CallbackDelete;
	this.callbackAttendeeActionDecline = oParameters.CallbackAttendeeActionDecline;

	this.timeFormatMoment = oParameters.TimeFormat;
	this.dateFormatMoment = Utils.getDateFormatForMoment(oParameters.DateFormat);
	this.dateFormatDatePicker = Utils.getDateFormatForDatePicker(oParameters.DateFormat);
	
	this.ampmTimeFormat(AppData.User.defaultTimeFormat() !== Enums.TimeFormat.F24);
	
	this.initializeDatePickers();
	
	this.allDay(oParameters.AllDay);
	
	this.startDate(oParameters.Start.format(this.dateFormatMoment));
	this.startDom().datepicker("setDate", oParameters.Start.toDate());
	this.startTime(oParameters.Start.format(this.timeFormatMoment));

	if (oParameters.End && this.allDay())
	{
		oEnd.subtract(1, 'days');
	}
	
	
	this.endDate(oEnd ? oEnd.format(this.dateFormatMoment) : this.startDate());
	this.endDom().datepicker("setDate", oEnd ? oEnd.toDate() : oParameters.Start.toDate());
	this.endTime(oEnd ? oEnd.format(this.timeFormatMoment) : this.startTime());

	this.calendars = oParameters.Calendars;
	if (this.calendars)
	{
		this.calendarsList(
			_.filter(
				this.calendars.collection(),
				function(oItem){ 
					return oItem.isEditable(); 
				}
			)
		);
	}
	this.selectedCalendar(oParameters.SelectedCalendar);
	this.selectedCalendar.valueHasMutated();
	
	this.calendarId(this.selectedCalendar());
	this.editableSwitch(this.selectedCalendarIsShared(), this.selectedCalendarIsEditable(), this.isMyEvent());

	this.changeCalendarColor(this.selectedCalendar());
	
	// parameters for event editing only (not for creating)
	this.id(oParameters.ID || null);
	this.uid(oParameters.Uid || null);
	this.recurrenceId(oParameters.RecurrenceId || null);
	
	this.subject(oParameters.Subject || '');
	this.location(oParameters.Location || '');
	this.description(oParameters.Description || '');
	this.allEvents(oParameters.AllEvents || Enums.CalendarEditRecurrenceEvent.AllEvents);

	this.displayedAlarms(this.getDisplayedAlarms(oParameters.Alarms || []));

	this.appointment(oParameters.Appointment);

	this.attendees(oParameters.Attendees || []);
	
	sAttendee = AppData.Accounts.getAttendee(
		_.map(this.attendees(), function (oAttendee){
			return oAttendee.email;
		}, this)
	);
	this.setCurrentAttenderStatus(sAttendee, oParameters.Attendees || []);

	this.owner(oParameters.Owner || owner);
	
	this.ownerName(oParameters.OwnerName || (this.isMyEvent() ? ownerName : ''));
/*	
	if (oParameters.OwnerName === '')
	{
		this.ownerName(oParameters.OwnerName);
	}
	else
	{
		this.ownerName(ownerName);
	}
*/	
	this.guestAutocomplete('');

	this.excluded(oParameters.Excluded || false);
	this.repeatRuleParse(oParameters.RRule || null);

	if (this.id() === null) {
		this.subjectFocus(true);
	}

//	this.selectStartDate();
//	this.selectEndDate();

	this.autosizeTrigger.notifySubscribers(true);
	
	this.modified = false;
};

/*CalendarEventPopup.prototype.onHide = function ()
{

};*/

/**
 * @return {string}
 */
CalendarEventPopup.prototype.popupTemplate = function ()
{
	return 'Popups_Calendar_EventPopupViewModel';
};

/**
 * @param {string} sId
 */
CalendarEventPopup.prototype.changeCalendarColor = function (sId)
{
	if (Utils.isFunc(this.calendars.getCalendarById))
	{
		var oCalendar = this.calendars.getCalendarById(sId);
		if (oCalendar)
		{
			this.calendarColor('');
			this.calendarColor(oCalendar.color());
		}
	}
};

CalendarEventPopup.prototype.onSaveClick = function ()
{
	if (this.subject() === '')
	{
		App.Screens.showPopup(AlertPopup, [Utils.i18n('CALENDAR/WARNING_EVENT_BLANK_SUBJECT'),
			_.bind(function () {
				this.subjectFocus(true);
			}, this)]);
	}
	else
	{
		if (this.callbackSave)
		{
			var
				iPeriod = this.repeatPeriod(),
				sDate = '',
				oDate = null,
				iInterval = 0,
				iWeekNum = 0,
				iEnd = 0,
				oStart = moment(this.getDateFromStr(this.startDate(), this.startTime())),
				oEnd = moment(this.getDateFromStr(this.endDate(), this.endTime())),
				oEventData = {
					calendarId: this.calendarId(),
					newCalendarId: this.selectedCalendar(),
					id: this.id(),
					uid: this.uid(),
					recurrenceId: this.recurrenceId(),
					allEvents:  this.allEvents(),
					subject: this.subject(),
					title: Utils.getTitleForEvent(this.subject()),
					start: oStart,
					allDay: this.allDay(),
					location: this.location(),
					description: this.description(),
					alarms: this.getAlarmsArray(this.displayedAlarms()),
					attendees: this.attendees(),
					owner: this.owner(),
					modified: this.modified
				}
			;
			
			if (this.allDay())
			{
				oEnd.add(1, 'days');
			}
			oEventData.end = oEnd;

			if (iPeriod)
			{
				sDate = this.repeatEndDom().datepicker('getDate');
				oDate = sDate ? this.getUnixTimestamp(sDate) : null;

				iInterval = this.repeatInterval();
				iWeekNum = this.repeatWeekNum();
				iEnd = this.repeatEnd();

				if (iPeriod === Enums.CalendarRepeatPeriod.Daily)
				{
					oEventData.rrule = {
						byDays: [],
						count: null,
						end: 2,
						interval: 1,
						period: iPeriod,
						until: oDate,
						weekNum: null
					};
				}
				else if (iPeriod === Enums.CalendarRepeatPeriod.Weekly)
				{
					this.setDayOfWeek(this.repeatPeriod());

					oEventData.rrule = {
						byDays: this.getDays(),
						count: null,
						end: 0,
						interval: iInterval,
						period: iPeriod,
						until: null,
						weekNum: null
					};
				}
				else if (iPeriod === Enums.CalendarRepeatPeriod.Monthly)
				{
					oEventData.rrule = {
						byDays: [],
						count: null,
						end: 0,
						interval: 1,
						period: iPeriod,
						until: null,
						weekNum: null
					};
				}
				else if (iPeriod === Enums.CalendarRepeatPeriod.Yearly)
				{
					oEventData.rrule = {
						byDays: [],
						count: null,
						end: 0,
						interval: 1,
						period: iPeriod,
						until: null,
						weekNum: null
					};
				}
			}

			this.callbackSave(oEventData);
		}

		this.closePopup();
	}
};

CalendarEventPopup.prototype.closePopup = function ()
{
	this.hideAll();
	this.cleanAll();

	this.closeCommand();
};

CalendarEventPopup.prototype.hideAll = function ()
{
	this.dateEdit(false);
	this.repeatEdit(false);
	this.guestsEdit(false);
};

CalendarEventPopup.prototype.cleanAll = function ()
{
	this.subject('');
	this.description('');
	this.location('');
	this.isRepeat(false);
	this.allDay(false);
	this.repeatPeriod(0);
	this.repeatInterval(1);
	this.repeatEnd(0);
	this.repeatCount(null);
	this.repeatWeekNum(null);
	this.startDate('');
	this.startTime('');
	this.endDate('');
	this.endTime('');
	this.repeatEndDate('');
	this.displayedAlarms([]);
	this.weekMO(false);
	this.weekTU(false);
	this.weekWE(false);
	this.weekTH(false);
	this.weekFR(false);
	this.weekSA(false);
	this.weekSU(false);
	this.attendees([]);
	this.selectedCalendar('');

	this.attendees([]);
};

CalendarEventPopup.prototype.onDeleteClick = function ()
{
	if (this.callbackDelete)
	{
		var
			oEventData = {
				calendarId: this.selectedCalendar(),
				id: this.id(),
				uid: this.uid(),
				recurrenceId: this.recurrenceId(),
				allEvents:  this.allEvents(),
				subject: this.subject(),
				title: Utils.getTitleForEvent(this.subject()),
				start: moment(this.getDateFromStr(this.startDate(), this.startTime())),
				end: moment(this.getDateFromStr(this.endDate(), this.endTime())),
				allDay: this.allDay(),
				location: this.location(),
				description: this.description()
			}
		;

		this.callbackDelete(oEventData);
	}
	this.closePopup();
};

/**
 * @param {Object} oModel
 * @param {Object} oEv
 */
CalendarEventPopup.prototype.showDates = function (oModel, oEv)
{
	oEv.stopPropagation();
	this.dateEdit(!this.dateEdit());
};

CalendarEventPopup.prototype.showGuests = function ()
{
	if (this.attendees().length > 0)
	{
		var
			sConfirm = Utils.i18n('CALENDAR/CONFIRM_CLOSE_ATTENDEERS'),
			fAction = _.bind(function (bResult) {
				if (bResult)
				{
					this.guestsEdit(false);
					this.guestEmailFocus(false);
					this.attendees([]);
				}
			}, this)
		;

		App.Screens.showPopup(ConfirmPopup, [sConfirm, fAction]);

	}
	else
	{
		this.guestsEdit(!this.guestsEdit());
		this.guestEmailFocus(!this.guestEmailFocus());
	}
};

CalendarEventPopup.prototype.onAddGuestClick = function ()
{
	var
		oGuestAutocompleteItem = this.guestAutocompleteItem(),
		sGuestAutocomplete = this.guestAutocomplete(),
		oItem = oGuestAutocompleteItem || {name: '', email: sGuestAutocomplete},
		bIsInvited = _.any(this.attendees(), function (oEl) {
			return oEl.email === oItem.email;
		})
	;

	if (oItem.email === '')
	{
		App.Api.showError(Utils.i18n('CALENDAR/EVENT_ERROR_ENTER_EMAIL'));
	}
	else if (oItem.email === this.owner())
	{
		this.recivedAnim(true);
	}
	else if (bIsInvited)
	{
		this.recivedAnim(true);
	}
//	else if (!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@[a-z0-9_\-]+(\.[a-z0-9_\-]+)*\.[a-z]{2,6}$/).test(oItem.email))
//	else if (!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@[a-z0-9_\-]+(\.[a-z0-9_\-]+)*/).test(oItem.email))
//	{
//		App.Api.showError(Utils.i18n('CALENDAR/EVENT_ERROR_CORRECT_EMAIL'));
//	}
	else
	{
		this.attendees.push(
			{
				status: 0,
				name: oItem.name,
				email: oItem.email
			}
		);
	}

	this.whomAnimate(oItem.email);
	this.guestAutocomplete('');
};

/**
 * @param {Array} aMinutes
 */
CalendarEventPopup.prototype.getDisplayedAlarms = function (aMinutes)
{
	var
		alarm,
		sText,
		aDisplayedAlarms = []
	;

	_.each(aMinutes, function(iMinutes, iIdx)
	{
		alarm = this['alarm' + iMinutes] = ko.observable(iMinutes);
		alarm.subscribe(function() {
			//alarm observable value not actual
			this.disableAlarms();
		}, this);

		if (iMinutes > 0 && iMinutes < 60)
		{
			sText = (Utils.i18n('CALENDAR/ALARM_MINUTES_PLURAL', {
				'COUNT' :iMinutes
			}, null, iMinutes));
		}
		else if (iMinutes >= 60 && iMinutes < 1440)
		{
			sText = (Utils.i18n('CALENDAR/ALARM_HOURS_PLURAL', {
				'COUNT' :iMinutes/60
			}, null, iMinutes/60));
		}
		else if (iMinutes >= 1440 && iMinutes < 10080)
		{
			sText = (Utils.i18n('CALENDAR/ALARM_DAYS_PLURAL', {
				'COUNT' :iMinutes/1440
			}, null, iMinutes/1440));
		}
		else
		{
			sText = (Utils.i18n('CALENDAR/ALARM_WEEKS_PLURAL', {
				'COUNT' :iMinutes/10080
			}, null, iMinutes/10080));
		}

		aDisplayedAlarms.push({
			'value':iMinutes,
			'alarm': alarm,
			'text': sText,
			'isDisabled': false
		});

	}, this);

	return aDisplayedAlarms;
};

CalendarEventPopup.prototype.getDisplayedPeriods = function ()
{
	return [
		{
			label: Utils.i18n('CALENDAR/EVENT_REPEAT_NEVER'),
			value: 0
		},
		{
			label: Utils.i18n('CALENDAR/EVENT_REPEAT_DAILY'),
			value: 1
		},
		{
			label: Utils.i18n('CALENDAR/EVENT_REPEAT_WEEKLY'),
			value: 2
		},
		{
			label: Utils.i18n('CALENDAR/EVENT_REPEAT_MONTHLY'),
			value: 3
		},
		{
			label: Utils.i18n('CALENDAR/EVENT_REPEAT_YEARLY'),
			value: 4
		}
	];
};

CalendarEventPopup.prototype.getDisplayedIntervals = function ()
{
	var
		i = 1,
		aDisplayedIntervals = []
	;

	for (; i <= 30; i++ )
	{
		aDisplayedIntervals.push(
			{
				label: i + Utils.i18n('th'),
				value: i
			}
		);
	}

	return aDisplayedIntervals;
};

/**
 * @param {Array} aDisplayedAlarms
 */
CalendarEventPopup.prototype.getAlarmsArray = function (aDisplayedAlarms)
{
	var aAlarms = [];

	_.each(aDisplayedAlarms, function(oAlarm, iIdx)
	{
		aAlarms.push(oAlarm.alarm());
	}, this);

	return _.sortBy(aAlarms, function(num){return -num;});
};

CalendarEventPopup.prototype.addFirstAlarm = function ()
{
	if(!this.displayedAlarms().length)
	{
		this.displayedAlarms(this.getDisplayedAlarms([this.alarmOptions()[0].value]));
	}
	else
	{
		var
			sConfirm = Utils.i18n('CALENDAR/CONFIRM_CLOSE_ALARMS'),
			fAction = _.bind(function (bResult) {
				if (bResult)
				{
					this.displayedAlarms.removeAll();
				}
			}, this)
		;

		App.Screens.showPopup(ConfirmPopup, [sConfirm, fAction]);
	}
};

CalendarEventPopup.prototype.addAlarm = function ()
{
	var
		oDisplayedAlarm,
		aSortedAlarms,
		iMinutes = 0
	;

	aSortedAlarms = _.sortBy(this.displayedAlarms(), function(oAlarm){return oAlarm.alarm();});

	_.each(aSortedAlarms, function(oAlarm) {

		var nAlarmMinutes = oAlarm.alarm();

		if (nAlarmMinutes !== 5 && iMinutes <= 5)
		{
			iMinutes = 5;
		}
		else if (nAlarmMinutes !== 10 && iMinutes <= 10)
		{
			iMinutes = 10;
		}
		else if (nAlarmMinutes !== 15 && iMinutes <= 15)
		{
			iMinutes = 15;
		}
		else if (nAlarmMinutes !== 30 && iMinutes <= 30)
		{
			iMinutes = 30;
		}
		else if (nAlarmMinutes !== 1440 && iMinutes <= 1440)
		{
			iMinutes = 1440;
		}
	});

	oDisplayedAlarm = this.getDisplayedAlarms([iMinutes])[0];

	this['alarm' + iMinutes] = ko.observable(iMinutes);
	this.displayedAlarms.push(oDisplayedAlarm);
};

/**
 * @param {Object} oItem
 */
CalendarEventPopup.prototype.removeAlarm = function (oItem)
{
	this.displayedAlarms.remove(oItem);
};

/**
 * @param {Object} oItem
 */
CalendarEventPopup.prototype.removeGuest = function (oItem)
{
	this.attendees.remove(oItem);
};

CalendarEventPopup.prototype.disableAlarms = function ()
{
	_.each(this.alarmOptions(), function(oAlarm, iIdx) {

		oAlarm.isDisabled = _.any(this.displayedAlarms(), function(oItem){
			return oItem.alarm() === oAlarm.value;
		});

	}, this);

	this.alarmOptions.valueHasMutated();
};

/**
 * @param {string} sTerm
 * @param {Function} fResponse
 */
CalendarEventPopup.prototype.autocompleteCallback = function (sTerm, fResponse)
{
	var oParameters = {
			'Action': 'ContactSuggestions',
			'Search': sTerm,
			'GlobalOnly': '0'
		}
	;

	this.guestAutocompleteItem(null);

	sTerm = Utils.trim(sTerm);
	if ('' !== sTerm)
	{
		App.Ajax.send(oParameters, function (oData) {
			var aList = [];
			if (oData && oData.Result && oData.Result && oData.Result.List)
			{
				aList = _.map(oData.Result.List, function (oItem) {
					/*return oItem && oItem.Email ? oItem.Email : '';*/
					return oItem && oItem.Email && oItem.Email !== this.owner() ?
						(oItem.Name && 0 < Utils.trim(oItem.Name).length ?
							{value:'"' + oItem.Name + '" <' + oItem.Email + '>', name: oItem.Name, email: oItem.Email, frequency: oItem.Frequency} :
							{value: oItem.Email, name: '', email: oItem.Email, frequency: oItem.Frequency}) :
						null;
				}, this);

				aList = _.sortBy(_.compact(aList), function(num){
					return num.frequency;
				}).reverse();
			}
			fResponse(aList);

		}, this);
	}
	else
	{
		fResponse([]);
	}
};

CalendarEventPopup.prototype.repeatRuleParse = function (oRepeatRule)
{
	var allEvents = this.allEvents();

	this.repeatEndDom().datepicker("option", "minDate", this.datepickerGetDate(this.endDom()));

	//new event
	/*if(!oRepeatRule && allEvents === Enums.CalendarEditRecurrenceEvent.AllEvents)
	{
		//event start date + 7days
		//sDate = moment(this.startDom().datepicker( "getDate" )).add('days', 7).format(this.dateFormatMoment);

		//this.repeatUntil(sDate);
	}
	//excluded event
	else if(!oRepeatRule && allEvents === Enums.CalendarEditRecurrenceEvent.OnlyThisInstance)
	{

	}
	//only this instance
	else if(oRepeatRule && allEvents === Enums.CalendarEditRecurrenceEvent.OnlyThisInstance)
	{

	}
	//all events in the series
	else*/
	
	if(oRepeatRule && allEvents === Enums.CalendarEditRecurrenceEvent.AllEvents)
	{
		if (oRepeatRule.until)
		{
			this.datepickerSetDate(this.repeatEndDom(), new Date(oRepeatRule.until * 1000));
		}

		if (oRepeatRule.byDays.length)
		{
			_.each(oRepeatRule.byDays, function (sItem)
			{
				this['week' + sItem](true);
			}, this);
		}
		this.repeatPeriod(oRepeatRule.period);
		this.repeatInterval(oRepeatRule.interval);
		this.repeatEnd(oRepeatRule.end);
		this.repeatCount(oRepeatRule.count);
		this.repeatWeekNum(oRepeatRule.weekNum);
	}
};

CalendarEventPopup.prototype.getDays = function ()
{
	var aDays = [];

	if (this.weekMO()) {aDays.push('MO');}
	if (this.weekTU()) {aDays.push('TU');}
	if (this.weekWE()) {aDays.push('WE');}
	if (this.weekTH()) {aDays.push('TH');}
	if (this.weekFR()) {aDays.push('FR');}
	if (this.weekSA()) {aDays.push('SA');}
	if (this.weekSU()) {aDays.push('SU');}

	return aDays;
};

CalendarEventPopup.prototype.onMainPanelClick = function ()
{
	if (this.dateEdit())
	{
		this.dateEdit(false);
	}
};

/**
 * @param {Object} oEl
 * @param {Object} oEv
 */
CalendarEventPopup.prototype.onKeydown = function (oEl, oEv)
{
	if(oEv.keyCode === Enums.Key.Enter)
	{
		oEv.preventDefault();
	}
};

/**
 * @param {Object} oEl
 * @param {Object} oEv
 * @param {Function} oValueObserver
 */
CalendarEventPopup.prototype.onKeyup = function (oEl, oEv, oValueObserver)
{
	if((oEv.target.id === "event_subject" || oEv.target.id === "event_location") && oEv.keyCode === Enums.Key.Enter)
	{
		this.onSaveClick();
	}
//	this.autosizeTrigger.notifySubscribers();
};

CalendarEventPopup.prototype.onPaste = function (oEl, oEv, oValueObserver)
{
	var sWithoutLineBreaks = oValueObserver().replace(/[\r\n\t]+/gm, ' ');

	oValueObserver(sWithoutLineBreaks);

};

CalendarEventPopup.prototype.selectStartDate = function ()
{
	if (this.startDate() && this.endDate())
	{
		var
			sDateFormat = this.dateFormatMoment,
			sTimeFormat = this.timeFormatMoment,
			oDate = this.datepickerGetDate(this.startDom()),
			oTimeDate = (moment(this.getDateFromStr(moment(oDate).format(sDateFormat), this.startTime()))).toDate(),
			oCompareDate = this.datepickerGetDate(this.endDom()),
			oCompareTimeDate = (moment(this.getDateFromStr(moment(oCompareDate).format(sDateFormat), this.endTime()))).toDate(),
			nTimeDate = this.getUnixTimestamp(oTimeDate),
			nCompareTimeDate = this.getUnixTimestamp(oCompareTimeDate),
			oMomentTimeDate = moment(oTimeDate),
			oMomentCompareTimeDate = moment(oCompareTimeDate),
			sDateLoc = oMomentTimeDate.format(sDateFormat),
			sTimeLoc = oMomentTimeDate.format(sTimeFormat),
			sCompareDateLoc = oMomentCompareTimeDate.format(sDateFormat),
			sCompareTimeLoc = oMomentCompareTimeDate.format(sTimeFormat)
		;

		this.isEvOneDay(sDateLoc === sCompareDateLoc);
		this.isEvOneTime(sTimeLoc === sCompareTimeLoc);

		if (nTimeDate > nCompareTimeDate)
		{
			this.datepickerSetDate(this.endDom(), oTimeDate);
			this.endDate(sDateLoc);
			this.endTime(sTimeLoc);
			this.isEvOneDay(true);
			this.isEvOneTime(true);
		}

		this.startDate(sDateLoc);
		this.startTime(sTimeLoc);

		this.yearlyDate(oMomentTimeDate.format(this.getPartOfDate(this.dateFormatMoment.slice(0,-5))));
		this.monthlyDate(oMomentTimeDate.format(this.getPartOfDate(this.dateFormatMoment.slice(0,2))));
	}
};

CalendarEventPopup.prototype.selectEndDate = function ()
{
	if (this.endDate() && this.startDate())
	{
		var
			sDateFormat = this.dateFormatMoment,
			sTimeFormat = this.timeFormatMoment,
			oDate = this.datepickerGetDate(this.endDom()),
			oTimeDate = (moment(this.getDateFromStr(moment(oDate).format(sDateFormat), this.endTime()))).toDate(),
			oCompareDate = this.datepickerGetDate(this.startDom()),
			oCompareTimeDate = (moment(this.getDateFromStr(moment(oCompareDate).format(sDateFormat), this.startTime()))).toDate(),
			nTimeDate = this.getUnixTimestamp(oTimeDate),
			nCompareTimeDate = this.getUnixTimestamp(oCompareTimeDate),
			oMomentTimeDate = moment(oTimeDate),
			oMomentCompareTimeDate = moment(oCompareTimeDate),
			sDateLoc = oMomentTimeDate.format(sDateFormat),
			sTimeLoc = oMomentTimeDate.format(sTimeFormat),
			sCompareDateLoc = oMomentCompareTimeDate.format(sDateFormat),
			sCompareTimeLoc = oMomentCompareTimeDate.format(sTimeFormat)
		;

		this.isEvOneDay(sDateLoc === sCompareDateLoc);
		this.isEvOneTime(sTimeLoc === sCompareTimeLoc);

		if(nTimeDate < nCompareTimeDate)
		{
			this.datepickerSetDate(this.startDom(), oTimeDate);
			this.startDate(sDateLoc);
			this.startTime(sTimeLoc);
			this.isEvOneDay(true);
			this.isEvOneTime(true);
		}

		this.endDate(sDateLoc);
		this.endTime(sTimeLoc);
		
		this.repeatEndDom().datepicker('option', 'minDate', oTimeDate);

		if (!this.isRepeat())
		{
			this.datepickerSetDate(this.repeatEndDom(), oMomentTimeDate.add('days', 7).toDate());
		}
	}
};

/**
 * @param {Object} oDate
 * @return {number}
 */
CalendarEventPopup.prototype.getUnixTimestamp = function (oDate)
{
	return moment(oDate).unix();
};

/**
 * @param {Object} oInput
 * @return {Date}
 */
CalendarEventPopup.prototype.datepickerGetDate = function (oInput)
{
	return oInput.datepicker('getDate');
};

/**
 * @param {Object} oInput
 * @param {Object} oDate
 */
CalendarEventPopup.prototype.datepickerSetDate = function (oInput, oDate)
{
	oInput.datepicker('setDate', oDate);
};

/**
 * @param {string} sDate
 * @param {string} sTime
 * @return {Date}
 */
CalendarEventPopup.prototype.getDateFromStr = function (sDate, sTime)
{
	return (moment(sDate + ' ' + sTime, this.dateFormatMoment + ' ' + this.timeFormatMoment)).toDate();
};

/**
 * @param {string} sDateFormat
 * @return {string}
 */
CalendarEventPopup.prototype.getPartOfDate = function (sDateFormat)
{
	var sMomentDateFormat = 'MM/DD';

		switch (sDateFormat)
		{
			case 'MM/DD':
				sMomentDateFormat = 'MM/DD';
				break;
			case 'DD/MM':
				sMomentDateFormat = 'DD/MM';
				break;
			case 'DD MMMM':
				sMomentDateFormat = 'DD MMMM';
				break;
			case 'DD':
				sMomentDateFormat = 'DD';
				break;
		}

	return sMomentDateFormat;
};

CalendarEventPopup.prototype.setActualTime = function ()
{
	var oMoment = moment();

	if (oMoment.minutes() > 30)
	{
		oMoment.minutes(60);
	}
	else
	{
		oMoment.minutes(30);
	}
	oMoment.seconds(0);
	oMoment.milliseconds(0);

	this.startTime(oMoment.format(this.timeFormatMoment));
	this.endTime(oMoment.add('minutes', 30).format(this.timeFormatMoment));
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CalendarEventPopup.prototype.onAppointmentActionResponse = function (oResponse, oRequest)
{
	if (!oResponse.Result)
	{
		App.Api.showErrorByCode(oResponse, Utils.i18n('WARNING/UNKNOWN_ERROR'));
	}
};

/**
 * @param {atring} sDecision
 */
CalendarEventPopup.prototype.doAppointmentAction = function (sDecision)
{
	var
		iDecision = Enums.IcalConfigInt.NeedsAction,
		aAttendees = this.attendees(),
//		sEmail = this.defaultAccount ? this.defaultAccount.email() : '',
		sEmail = AppData.Accounts.getAttendee(
			_.map(this.attendees(), function (oAttendee){
				return oAttendee.email;
			}, this)
		),
		oAttendee = _.find(this.attendees(), function(oAttendee){
			return oAttendee.email === sEmail; 
		}, this),
		oCalendar = this.calendars.getCalendarById(this.selectedCalendar()),
/*		oParameters = {
			'Action': 'EventUpdateAppointment',
			'actionAppointment': iDecision,
			'calendarId': this.selectedCalendar(),
			'uid' : this.uid,
			'attendee': sEmail
		}
*/
		oParameters = {
			'Action': 'AppointmentAction',
			'AppointmentAction': sDecision,
			'CalendarId': this.selectedCalendar(),
			'EventId': this.uid(),
			'Attendee': sEmail
		}
	;

	if (oAttendee)
	{
		switch (sDecision)
		{
			case Enums.IcalConfig.Accepted:
				iDecision = Enums.IcalConfigInt.Accepted;
				App.CalendarCache.markIcalAccepted(this.uid());
				break;
			case Enums.IcalConfig.Tentative:
				iDecision = Enums.IcalConfigInt.Tentative;
				App.CalendarCache.markIcalTentative(this.uid());
				break;
			case Enums.IcalConfig.Declined:
				iDecision = Enums.IcalConfigInt.Declined;
				App.CalendarCache.markIcalNonexistent(this.uid());
				break;
		}
		App.Ajax.send(oParameters, this.onAppointmentActionResponse, this);

		oAttendee.status = iDecision;
		this.attendees([]);
		this.attendees(aAttendees);
		this.setCurrentAttenderStatus(oAttendee.email, this.attendees());
		if (sDecision === Enums.IcalConfig.Declined && oCalendar && 
				this.callbackAttendeeActionDecline && Utils.isFunc(this.callbackAttendeeActionDecline))
		{
			this.callbackAttendeeActionDecline(oCalendar,  this.id());
			this.closePopup();
		}
	}
};

/**
 * @param {boolean} bShared
 * @param {boolean} bEditable
 * @param {boolean} bMyEvent
 */
CalendarEventPopup.prototype.editableSwitch = function (bShared, bEditable, bMyEvent)
{
	this.isEditable((bShared && bEditable && bMyEvent) || (!bShared && bEditable && bMyEvent) || (bShared && bEditable && !bMyEvent));
	this.isEditableReminders(bEditable);
};

/**
 * @param {string} sCurrentEmail
 * @param {Array} aAttendees
 */
CalendarEventPopup.prototype.setCurrentAttenderStatus = function (sCurrentEmail, aAttendees)
{
	var oCurrentAttender = _.find(aAttendees, function(oAttender){ 
		return oAttender.email === sCurrentEmail; 
	});

	this.attenderStatus(oCurrentAttender ? oCurrentAttender.status : 0);
};

CalendarEventPopup.prototype.getAttenderTextStatus = function (sStatus)
{
	switch (sStatus)
	{
		case 0:
			sStatus="pending";
			break;
		case 1:
			sStatus="accepted";
			break;
		case 2:
			sStatus="declined";
			break;
		case 3:
			sStatus="tentative";
			break;
	}
	return sStatus;
};

CalendarEventPopup.prototype.setDayOfWeek = function (iRepeatPeriod)
{
	if (iRepeatPeriod === Enums.CalendarRepeatPeriod.Weekly && !this.getDays().length) {

		var iDayOfWeek = this.datepickerGetDate(this.startDom()).getUTCDay();

		switch (iDayOfWeek) {
			case 0:
				this.weekMO(true);
				break;
			case 1:
				this.weekTU(true);
				break;
			case 2:
				this.weekWE(true);
				break;
			case 3:
				this.weekTH(true);
				break;
			case 4:
				this.weekFR(true);
				break;
			case 5:
				this.weekSA(true);
				break;
			case 6:
				this.weekSU(true);
				break;
		}
	}
};

/**
 * @param {string} sTimeFormatMoment
 * @returns {Array}
 */
CalendarEventPopup.prototype.getDisplayedTimes = function (sTimeFormatMoment)
{
	var aDisplayedTimes = [];

	switch (sTimeFormatMoment)
	{
		case 'HH:mm':
			aDisplayedTimes = [
				{'text':'00:00', 'value':'00:00'},
				{'text':'00:30', 'value':'00:30'},
				{'text':'01:00', 'value':'01:00'},
				{'text':'01:30', 'value':'01:30'},
				{'text':'02:00', 'value':'02:00'},
				{'text':'02:30', 'value':'02:30'},
				{'text':'03:00', 'value':'03:00'},
				{'text':'03:30', 'value':'03:30'},
				{'text':'04:00', 'value':'04:00'},
				{'text':'04:30', 'value':'04:30'},
				{'text':'05:00', 'value':'05:00'},
				{'text':'05:30', 'value':'05:30'},
				{'text':'06:00', 'value':'06:00'},
				{'text':'06:30', 'value':'06:30'},
				{'text':'07:00', 'value':'07:00'},
				{'text':'07:30', 'value':'07:30'},
				{'text':'08:00', 'value':'08:00'},
				{'text':'08:30', 'value':'08:30'},
				{'text':'09:00', 'value':'09:00'},
				{'text':'09:30', 'value':'09:30'},
				{'text':'10:00', 'value':'10:00'},
				{'text':'10:30', 'value':'10:30'},
				{'text':'11:00', 'value':'11:00'},
				{'text':'11:30', 'value':'11:30'},
				{'text':'12:00', 'value':'12:00'},
				{'text':'12:30', 'value':'12:30'},
				{'text':'13:00', 'value':'13:00'},
				{'text':'13:30', 'value':'13:30'},
				{'text':'14:00', 'value':'14:00'},
				{'text':'14:30', 'value':'14:30'},
				{'text':'15:00', 'value':'15:00'},
				{'text':'15:30', 'value':'15:30'},
				{'text':'16:00', 'value':'16:00'},
				{'text':'16:30', 'value':'16:30'},
				{'text':'17:00', 'value':'17:00'},
				{'text':'17:30', 'value':'17:30'},
				{'text':'18:00', 'value':'18:00'},
				{'text':'18:30', 'value':'18:30'},
				{'text':'19:00', 'value':'19:00'},
				{'text':'19:30', 'value':'19:30'},
				{'text':'20:00', 'value':'20:00'},
				{'text':'20:30', 'value':'20:30'},
				{'text':'21:00', 'value':'21:00'},
				{'text':'21:30', 'value':'21:30'},
				{'text':'22:00', 'value':'22:00'},
				{'text':'22:30', 'value':'22:30'},
				{'text':'23:00', 'value':'23:00'},
				{'text':'23:30', 'value':'23:30'}
			];
			break;
		case 'hh:mm A':
			aDisplayedTimes = [
				{'text':'12:00 AM', 'value':'12:00 AM'},
				{'text':'12:30 AM', 'value':'12:30 AM'},
				{'text':'01:00 AM', 'value':'01:00 AM'},
				{'text':'01:30 AM', 'value':'01:30 AM'},
				{'text':'02:00 AM', 'value':'02:00 AM'},
				{'text':'02:30 AM', 'value':'02:30 AM'},
				{'text':'03:00 AM', 'value':'03:00 AM'},
				{'text':'03:30 AM', 'value':'03:30 AM'},
				{'text':'04:00 AM', 'value':'04:00 AM'},
				{'text':'04:30 AM', 'value':'04:30 AM'},
				{'text':'05:00 AM', 'value':'05:00 AM'},
				{'text':'05:30 AM', 'value':'05:30 AM'},
				{'text':'06:00 AM', 'value':'06:00 AM'},
				{'text':'06:30 AM', 'value':'06:30 AM'},
				{'text':'07:00 AM', 'value':'07:00 AM'},
				{'text':'07:30 AM', 'value':'07:30 AM'},
				{'text':'08:00 AM', 'value':'08:00 AM'},
				{'text':'08:30 AM', 'value':'08:30 AM'},
				{'text':'09:00 AM', 'value':'09:00 AM'},
				{'text':'09:30 AM', 'value':'09:30 AM'},
				{'text':'10:00 AM', 'value':'10:00 AM'},
				{'text':'10:30 AM', 'value':'10:30 AM'},
				{'text':'11:00 AM', 'value':'11:00 AM'},
				{'text':'11:30 AM', 'value':'11:30 AM'},
				{'text':'12:00 PM', 'value':'12:00 PM'},
				{'text':'12:30 PM', 'value':'12:30 PM'},
				{'text':'01:00 PM', 'value':'01:00 PM'},
				{'text':'01:30 PM', 'value':'01:30 PM'},
				{'text':'02:00 PM', 'value':'02:00 PM'},
				{'text':'02:30 PM', 'value':'02:30 PM'},
				{'text':'03:00 PM', 'value':'03:00 PM'},
				{'text':'03:30 PM', 'value':'03:30 PM'},
				{'text':'04:00 PM', 'value':'04:00 PM'},
				{'text':'04:30 PM', 'value':'04:30 PM'},
				{'text':'05:00 PM', 'value':'05:00 PM'},
				{'text':'05:30 PM', 'value':'05:30 PM'},
				{'text':'06:00 PM', 'value':'06:00 PM'},
				{'text':'06:30 PM', 'value':'06:30 PM'},
				{'text':'07:00 PM', 'value':'07:00 PM'},
				{'text':'07:30 PM', 'value':'07:30 PM'},
				{'text':'08:00 PM', 'value':'08:00 PM'},
				{'text':'08:30 PM', 'value':'08:30 PM'},
				{'text':'09:00 PM', 'value':'09:00 PM'},
				{'text':'09:30 PM', 'value':'09:30 PM'},
				{'text':'10:00 PM', 'value':'10:00 PM'},
				{'text':'10:30 PM', 'value':'10:30 PM'},
				{'text':'11:00 PM', 'value':'11:00 PM'},
				{'text':'11:30 PM', 'value':'11:30 PM'}
			];
			break;
	}

	return aDisplayedTimes;
};

CalendarEventPopup.prototype.displayReminderPart = function (sPart, sPrefix)
{
	var sTemplate = '';
	if (sPart === '%REMINDERS%')
	{
		sTemplate = 'Reminders';
	}
	else
	{
		sTemplate = 'Text';
	}

	return sPrefix + sTemplate;
};
/**
 * @constructor
 */
function CalendarEditRecurrenceEventPopup()
{
	this.fCallback = null;
	this.confirmDesc = Utils.i18n('CALENDAR/EDIT_RECURRENCE_CONFIRM_DESCRIPTION');
	this.onlyThisInstanceButtonText = ko.observable(Utils.i18n('CALENDAR/ONLY_THIS_INSTANCE'));
	this.allEventsButtonText = ko.observable(Utils.i18n('CALENDAR/ALL_EVENTS_IN_THE_SERIES'));
	this.cancelButtonText = ko.observable(Utils.i18n('MAIN/BUTTON_CANCEL'));
}

/**
 * @param {Function} fCallback
 */
CalendarEditRecurrenceEventPopup.prototype.onShow = function (fCallback)
{
	if (Utils.isFunc(fCallback))
	{
		this.fCallback = fCallback;
	}
};

/**
 * @return {string}
 */
CalendarEditRecurrenceEventPopup.prototype.popupTemplate = function ()
{
	return 'Popups_Calendar_EditRecurrenceEventPopupViewModel';
};

CalendarEditRecurrenceEventPopup.prototype.onlyThisInstanceButtonClick = function ()
{
	if (this.fCallback)
	{
		this.fCallback(Enums.CalendarEditRecurrenceEvent.OnlyThisInstance);
	}

	this.closeCommand();
};

CalendarEditRecurrenceEventPopup.prototype.allEventsButtonClick = function ()
{
	if (this.fCallback)
	{
		this.fCallback(Enums.CalendarEditRecurrenceEvent.AllEvents);
	}

	this.closeCommand();
};

CalendarEditRecurrenceEventPopup.prototype.cancelButtonClick = function ()
{
	if (this.fCallback)
	{
		this.fCallback(Enums.CalendarEditRecurrenceEvent.None);
	}

	this.closeCommand();
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
	
	this.needToReloadCalendar = ko.observable(false);
	this.needToReloadCalendarTriger = ko.observable(false);

	this.mobileSync = ko.observable(null);
	this.MobileSyncDemoPass = 'demo';
	this.outlookSync = ko.observable(null);
	this.OutlookSyncDemoPass = 'demo';
	
	this.AllowHelpdeskNotifications = false;
	
	this.IsCollaborationSupported = false;
	this.AllowFilesSharing = false;
	
	this.DefaultFontName = 'Tahoma';
	this.fillDefaultFontName();
	
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
 * @param {boolean} bAllowOpenPgp
 */
CUserSettingsModel.prototype.parse = function (oData, bAllowOpenPgp)
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
		
		this.enableOpenPgp(bAllowOpenPgp && !!oData.EnableOpenPgp);
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
	if (this.DefaultTheme !== sDefaultTheme || this.DefaultLanguage !== sDefaultLanguage || 
		this.DefaultDateFormat !== sDefaultDateFormat || this.defaultTimeFormat() !== sDefaultTimeFormat)
	{
		this.needToReloadCalendar(true);
	}
	
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
	
	if (this.needToReloadCalendar())
	{
		this.needToReloadCalendarTriger(!this.needToReloadCalendarTriger());
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
	if (this.CalendarShowWeekEnds !== bShowWeekEnds || this.CalendarShowWorkDay !== bShowWorkDay || 
		this.CalendarWorkDayStarts !== iWorkDayStarts ||this.CalendarWorkDayEnds !== iWorkDayEnds ||
		this.CalendarWeekStartsOn !== iWeekStartsOn ||this.CalendarDefaultTab !== iDefaultTab)
	{
		this.needToReloadCalendar(true);
	}
	
	this.CalendarShowWeekEnds = bShowWeekEnds;
	this.CalendarShowWorkDay = bShowWorkDay;
	this.CalendarWorkDayStarts = iWorkDayStarts;
	this.CalendarWorkDayEnds = iWorkDayEnds;
	this.CalendarWeekStartsOn = iWeekStartsOn;
	this.CalendarDefaultTab = iDefaultTab;
	
	if (this.needToReloadCalendar())
	{
		this.needToReloadCalendarTriger(!this.needToReloadCalendarTriger());
	}
};

/**
 * @param {boolean} bAllowHelpdeskNotifications
 */
CUserSettingsModel.prototype.updateHelpdeskSettings = function (bAllowHelpdeskNotifications)
{
	this.AllowHelpdeskNotifications = bAllowHelpdeskNotifications;
};

/**
 * @return {boolean}
 */
CUserSettingsModel.prototype.isNeedToReloadCalendar = function ()
{
	var bNeedToReloadCalendar = this.needToReloadCalendar();
	
	this.needToReloadCalendar(false);
	
	return bNeedToReloadCalendar;
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CUserSettingsModel.prototype.onSyncSettingsResponse = function (oResponse, oRequest)
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
		App.Ajax.send({'Action': 'SyncSettings'}, this.onSyncSettingsResponse, this);
	}
};


/**
 * @constructor
 */
function CCalendarModel()
{
	this.id = 0;
	this.cTag = 0;
	this.name = ko.observable('');
	this.description = ko.observable('');
	this.owner = ko.observable('');
	this.isDefault = false;
	this.isShared =  ko.observable(false);
	this.isSharedToAll = ko.observable(false);
	this.sharedToAllAccess = Enums.CalendarAccess.Read;
	this.isPublic = ko.observable(false);
	this.url = ko.observable('');
	this.davUrl = ko.observable('');
	this.exportUrl = ko.observable('');
	this.pubUrl = ko.observable('');
	this.shares = ko.observableArray([]);
	this.events = ko.observableArray([]);
	this.eventsCount = ko.computed(function() {
        return this.events().length;
    }, this);
	this.access = ko.observable(Enums.CalendarAccess.Write);
	
	this.control = ko.computed(function() {
		return !(this.access() === Enums.CalendarAccess.Read && this.isSharedToAll());
	}, this);
	
	this.color = ko.observable('');
	this.color.subscribe(function(){
		
		this.events(_.map(this.events(), function (oEvent) {
			oEvent.backgroundColor = oEvent.borderColor = this.color();
			return oEvent;
		}, this));
		
		this.name.valueHasMutated();

	}, this);
	
	this.active = ko.observable(true);
	
	this.startDateTime = 0;
	this.endDateTime = 0;
	
	this.account = AppData.Accounts ? AppData.Accounts.getDefault() : null;
}

/**
 * @param {AjaxCalendarResponse} oData
 */
CCalendarModel.prototype.parse = function (oData)
{
	this.id = Utils.pString(oData.Id);
	this.cTag = oData.CTag;
	this.name(Utils.pString(oData.Name));
	this.description(Utils.pString(oData.Description));
	this.owner(Utils.pString(oData.Owner));
	this.active(App.Storage.hasData(this.id) ? App.Storage.getData(this.id) : true);
	this.isDefault = !!oData.IsDefault;
	this.access(oData.Access);
	this.isShared(!!oData.Shared);
	this.isSharedToAll(!!oData.SharedToAll);
	this.sharedToAllAccess = oData.SharedToAllAccess;
	this.isPublic(!!oData.IsPublic);

	var color = Utils.pString(oData.Color);
//	color = (this.access === Enums.CalendarAccess.Read) ? Utils.shadeColor(color, 30) : color;
	
	this.color(color);
	this.url(Utils.pString(oData.Url));
	this.davUrl(Utils.pString(oData.ServerUrl));
	this.exportUrl(Utils.getAppPath() + '?/Raw/Calendars/0/' + Utils.pString(oData.ExportHash));
	this.pubUrl(Utils.getAppPath() + '?calendar-pub=' + Utils.pString(oData.PubHash));
	this.shares(oData.Shares || []);
	
	_.each(oData.Events, function (oEvent) {
		this.addEvent(oEvent);
	}, this);
};

/**
 * @param {string} sId
 * 
 * @return {?}
 */
CCalendarModel.prototype.eventExists = function (sId)
{
	return _.find(this.events(), function(oEvent){ 
		return (sId === oEvent.id); 
	});
};

/**
 * @param {Object} oEvent
 */
CCalendarModel.prototype.updateEvent = function (oEvent)
{
	var bResult = false;
	if (oEvent)
	{
		this.removeEvent(oEvent.id);
		this.addEvent(oEvent);
	}
	
	return bResult;
};

/**
 * @param {Object} oEvent
 */
CCalendarModel.prototype.addEvent = function (oEvent)
{
	if (oEvent && !this.eventExists(oEvent.id))
	{
		this.events.push(this.parseEvent(oEvent));
	}
};

/**
 * @param {string} sId
 */
CCalendarModel.prototype.getEvent = function (sId)
{
	return _.find(this.events(), function(oEvent){ 
		return oEvent.id === sId;
	}, this);
};

/**
 * @param {string} sId
 */
CCalendarModel.prototype.getEvents = function (start, end)
{
	var aResult = _.filter(this.events(), function(oEvent){ 
		return moment.utc(oEvent.start).unix() >= start.unix() && moment.utc(oEvent.end).unix() <= end.unix() ||
				moment.utc(oEvent.start).unix() <= start.unix() && moment.utc(oEvent.end).unix() >= end.unix()
		;
	}, this);
	if (Utils.isUnd(aResult))
	{
		aResult = [];
	}
	return aResult;
};

/**
 * @param {string} sId
 */
CCalendarModel.prototype.removeEvent = function (sId)
{
	this.events(_.filter(this.events(), function(oEvent){ 
		return oEvent.id !== sId;
	}, this));
};

/**
 * @param {string} sUid
 * @param {boolean} bSkipExcluded
 */
CCalendarModel.prototype.removeEventByUid = function (sUid, bSkipExcluded)
{
	if (Utils.isUnd(bSkipExcluded))
	{
		bSkipExcluded = false;
	}
	
	this.events(_.filter(this.events(), function(oEvent){ 
		return (oEvent.uid !== sUid && (!bSkipExcluded || !oEvent.excluded));
	}, this));
};

CCalendarModel.prototype.removeEvents = function ()
{
	this.events([]);
};

CCalendarModel.prototype.expungeEvents = function (aEventIds, start, end)
{
	var aEventRemoveIds = [];
	_.each(this.getEvents(moment.unix(start), moment.unix(end)), function(oEvent) {
		if (!_.include(aEventIds, oEvent.id))
		{
			aEventRemoveIds.push(oEvent.id);
		}
	}, this);
	this.events(_.filter(this.events(), function(oEvent){
		return !_.include(aEventRemoveIds, oEvent.id);
	},this));
};

/**
 * @return {boolean}
 */
CCalendarModel.prototype.isEditable = function ()
{
	return this.access() !== Enums.CalendarAccess.Read;
};

/**
 * @return {boolean}
 */
CCalendarModel.prototype.isOwner = function ()
{
	return (this.account && this.account.email() === this.owner());
};

CCalendarModel.prototype.parseEvent = function (oEvent)
{
/*
	oEvent.start = moment(oEvent.start);
	oEvent.end = moment(oEvent.end);
*/

	oEvent.title = Utils.getTitleForEvent(oEvent.subject);
	oEvent.editable = oEvent.appointment ? false : true;
	oEvent.backgroundColor = oEvent.borderColor = this.color();
	if (!_.isArray(oEvent.className))
	{
		var className = oEvent.className;
		oEvent.className = [className];
	}
	if (this.access() === Enums.CalendarAccess.Read)
	{
		oEvent.className.push('fc-event-readonly');
	}
	else
	{
		oEvent.className = _.filter(oEvent.className, function(sItem){ 
			return sItem !== 'fc-event-readonly'; 
		});
	}
	if (oEvent.rrule && !oEvent.excluded)
	{
		oEvent.className.push('fc-event-repeat');
	}
	else
	{
		oEvent.className = _.filter(oEvent.className, function(sItem){ 
			return sItem !== 'fc-event-repeat'; 
		});		
	}
	if (Utils.isNonEmptyArray(oEvent.attendees))
	{
		oEvent.className.push('fc-event-appointment');
	}
	else
	{
		oEvent.className = _.filter(oEvent.className, function(sItem){ 
			return sItem !== 'fc-event-appointment'; 
		});		
	}
	return oEvent;
};

CCalendarModel.prototype.reloadEvents = function ()
{
	this.events(_.map(this.events(), function (oEvent) {
		return this.parseEvent(oEvent);
	}, this));
};

CCalendarModel.prototype.canShare = function ()
{
	return (!this.isShared() || this.isShared() && this.access() === Enums.CalendarAccess.Write || this.isOwner());
};

/**
 * @param {Object} oParameters
 * @constructor
 */
function CCalendarListModel(oParameters)
{
	this.parentOnCalendarActiveChange = oParameters.onCalendarActiveChange;
	this.parentOnCalendarCollectionChange = oParameters.onCalendarCollectionChange;
	
	this.defaultCal = ko.observable(null);
	this.currentCal = ko.observable(null);
	
	this.collection = ko.observableArray([]);
	this.collection.subscribe(function () {
		this.pickCurrentCalendar(this.defaultCal());
		
		if (this.parentOnCalendarCollectionChange)
		{
			this.parentOnCalendarCollectionChange();
		}
	}, this);
	this.count = ko.computed(function () {
		return this.collection().length;
	}, this);
	
	this.own = ko.computed(function () {
		var 
			calendars = _.filter(this.collection(), 
				function(oItem){ return (!oItem.isShared()); 
			})
		;
		return calendars;
	}, this);
	this.ownCount = ko.computed(function () {
		return this.own().length;
	}, this);
	this.shared = ko.computed(function () {
		var 
			calendars = _.filter(this.collection(), 
				function(oItem){ return (oItem.isShared() && !oItem.isSharedToAll()); 
			})
		;
		return calendars;
	}, this);
	this.sharedCount = ko.computed(function () {
		return this.shared().length;
	}, this);
	this.sharedToAll = ko.computed(function () {
		var 
			calendars = _.filter(this.collection(), 
				function(oItem){ return (oItem.isShared() && oItem.isSharedToAll()); 
			})
		;
		return calendars;
	}, this);
	this.sharedToAllCount = ko.computed(function () {
		return this.sharedToAll().length;
	}, this);
	this.ids = ko.computed(function () {
		return _.map(this.collection(), function (oCalendar){
			return oCalendar.id;
		}, this);
	}, this);
}

/**
 * @param {Object} oPickCalendar
 */
CCalendarListModel.prototype.pickCurrentCalendar = function (oPickCalendar)
{
	var
		oFirstActiveCal = _.find(this.collection(), function (oCalendar) {
			return oCalendar.active() && (oCalendar.access() === Enums.CalendarAccess.Write);
		}, this)
	;
	
	if (!this.currentCal() || !this.currentCal().active())
	{
		if (oPickCalendar && oPickCalendar.active())
		{
			this.currentCal(oPickCalendar);
		}
		else if (this.defaultCal() && (this.defaultCal().active() || !oFirstActiveCal))
		{
			this.currentCal(this.defaultCal());
		}
		else if (oFirstActiveCal)
		{
			this.currentCal(oFirstActiveCal);
		}
	}
};

/**
 * @param {number} iCalendarId
 */
CCalendarListModel.prototype.hideOtherCalendars = function (iCalendarId)
{
	_.each(this.collection(), function (oCalendar) {
		oCalendar.active(oCalendar.id === iCalendarId);
	}, this);
};

/**
 * @param {string} sCalendarId
 */
CCalendarListModel.prototype.getCalendarById = function (sCalendarId)
{
	var oCalendar = _.find(this.collection(), function(oCalendar) {
		return oCalendar.id === sCalendarId;
	}, this);
	
	return oCalendar;
};

/**
 * @return {Array}
 */
CCalendarListModel.prototype.getEvents = function (start, end)
{
	var
		aCalendarsEvents = [],
		aCalendarEvents = []
	;
	
	_.each(this.collection(), function (oCalendar) {
		if (oCalendar && oCalendar.active())
		{
			if (!Utils.isUnd(start) && !Utils.isUnd(end))
			{
				aCalendarEvents = oCalendar.getEvents(start, end);
			}
			else
			{
				aCalendarEvents = oCalendar.events();
			}
			aCalendarsEvents = _.union(aCalendarsEvents, aCalendarEvents);
		}
	}, this);

	return aCalendarsEvents;
};

/**
 * @param {AjaxCalendarResponse} oCalendarData
 * 
 * @return {Object}
 */
CCalendarListModel.prototype.parseCalendar = function (oCalendarData)
{
	var	oCalendar = new CCalendarModel();
	oCalendar.parse(oCalendarData);
	
	return oCalendar;
};
	
/**
 * @param {AjaxCalendarResponse} oCalendarData
 * 
 * @return {Object}
 */
CCalendarListModel.prototype.parseAndAddCalendar = function (oCalendarData)
{
	var
		mIndex = 0,
		oClientCalendar = null,
		oCalendar = this.parseCalendar(oCalendarData)
	;
	
	oCalendar.active.subscribe(function (value) {
		this.parentOnCalendarActiveChange(oCalendar);
		var oPickCalendar = oCalendar.active() ? oCalendar : this.defaultCal();
		this.pickCurrentCalendar(oPickCalendar);
		App.Storage.setData(oCalendar.id, value);
	}, this);
	
	if (oCalendar.isDefault)
	{
		this.defaultCal(oCalendar);
	}
	
	mIndex = this.calendarExists(oCalendar.id);
	if (mIndex || mIndex === 0)
	{
		oClientCalendar = this.getCalendarById(oCalendar.id);
		oCalendar.events(oClientCalendar.events());
		this.collection.splice(mIndex, 1, oCalendar);
		
	}
	else
	{
		this.collection.push(oCalendar);
	}
	
	this.sort();
	
	return oCalendar;
};

/**
 * @param {string|number} sId
 * 
 * @return {?}
 */
CCalendarListModel.prototype.calendarExists = function (sId)
{
	var iIndex = _.indexOf(_.map(this.collection(), function(oItem){return oItem.id;}), sId);
	
	return (iIndex < 0) ? false : iIndex;
};

/**
 * @param {string} sId
 */
CCalendarListModel.prototype.removeCalendar = function (sId)
{
	this.collection(_.filter(this.collection(), function(oCalendar) {
		return oCalendar.id !== sId;
	}, this));
};


CCalendarListModel.prototype.clearCollection = function ()
{
	this.collection.removeAll();
};

CCalendarListModel.prototype.getColors = function ()
{
	return _.map(this.collection(), function (oCalendar) {
		return oCalendar.color().toLowerCase();
	}, this);
};

/**
 * @param {string} sId
 */
CCalendarListModel.prototype.setDefault = function (sId)
{
	_.each(this.collection(), function(oCalendar) {
		if (oCalendar.id !== sId)
		{
			oCalendar.isDefault = true;
			this.defaultCal(oCalendar);
		}
		else
		{
			oCalendar.isDefault = false;
		}
	}, this);
};

/**
 */
CCalendarListModel.prototype.sort = function ()
{
	var collection = _.sortBy(this.collection(), function(oCalendar){return oCalendar.name();});
	this.collection(_.sortBy(collection, function(oCalendar){return oCalendar.isShared();}));
};

CCalendarListModel.prototype.expunge = function (aIds)
{
	this.collection(_.filter(this.collection(), function(oCalendar) {
		return _.include(aIds, oCalendar.id);
	}, this));
};


/**
 * @constructor
 */
function CCalendarViewModel()
{
	var self = this;
	this.initialized = ko.observable(false);
	this.isPublic = bExtApp;
	
	this.uploaderArea = ko.observable(null);
	this.bDragActive = ko.observable(false);
	this.bDragActiveComp = ko.computed(function () {
		return this.bDragActive();
	}, this);
	
	this.defaultAccount = (AppData.Accounts) ? AppData.Accounts.getDefault() : null;
	this.todayDate = new Date();
	this.aDayNames = Utils.i18n('DATETIME/DAY_NAMES').split(' ');
	
	this.publicCalendarId = (this.isPublic) ? AppData.CalendarPubHash : '';
	this.publicCalendarName = ko.observable('');

	this.timeFormat = (AppData.User.defaultTimeFormat() === Enums.TimeFormat.F24) ? 'HH:mm' : 'hh:mm A';
	this.dateFormat = AppData.User.DefaultDateFormat;
	
	this.dateTitle = ko.observable('');
	this.dateTitleHelper = ko.observableArray(Utils.i18n('DATETIME/MONTH_NAMES').split(' '));
	this.selectedView = ko.observable('');
	this.visibleWeekdayHeader = ko.computed(function () {
		return this.selectedView() === 'month';
	}, this);
	this.selectedView.subscribe(function () {
		this.resize();
	}, this);
	
	this.$calendarGrid = null;
	this.calendarGridDom = ko.observable(null);
	
	this.$datePicker = null;
	this.datePickerDom = ko.observable(null);

	this.calendars = new CCalendarListModel({
		onCalendarCollectionChange: function () {
			self.refreshView();
		},
		onCalendarActiveChange: function () {
			self.refreshView();
		}
	});

	this.colors = [
		'#f09650', 
		'#f68987', 
		'#6fd0ce', 
		'#8fbce2', 
		'#b9a4f5', 
		'#f68dcf', 
		'#d88adc', 
		'#4afdb4', 
		'#9da1ff', 
		'#5cc9c9', 
		'#77ca71', 
		'#aec9c9'
	];
	
	this.busyDays = ko.observableArray([]);
	
	this.$inlineEditedEvent = null;
	this.inlineEditedEventText = null;
	this.checkStarted = ko.observable(false);
	
	this.loaded = false;
	
	this.startDateTime = 0;
	this.endDateTime = 0;
	
	this.needsToReload = false;
	
	this.calendarListClick = function (oItem) {
		oItem.active(!oItem.active());
	};
	this.currentCalendarDropdown = ko.observable(false);
	this.currentCalendarDropdownOffset = ko.observable(0);
	this.calendarDropdownToggle = function (bValue, oElement) {
		if (oElement && bValue)
		{
			var
				position = oElement.position(),
				height = oElement.outerHeight()
			;

			self.currentCalendarDropdownOffset(parseInt(position.top, 10) + height);
		}

		self.currentCalendarDropdown(bValue);
	};
	
	this.dayNamesResizeBinding = _.throttle(_.bind(this.resize, this), 50);

	this.customscrollTop = ko.observable(0);
	this.fullcalendarOptions = {
		eventLimit: 10,
		header: false,
		editable: !this.isPublic,
		selectable: !this.isPublic,
		allDayText: Utils.i18n('CALENDAR/TITLE_ALLDAY'),
		dayNames: this.aDayNames,
		isRTL: Utils.isRTL(),
		scrollTime: moment.duration(8, 'hours'),
		forceEventDuration: true,
		columnFormat: {
			month: 'dddd',  // Monday
			week: 'dddd D', // Monday 7
			day: 'dddd D'	// Monday 7
		},
		titleFormat: {
			month: 'MMMM YYYY',                         // September 2009
			week: "MMMM D[ YYYY]{ '-'[ MMMM] D YYYY}",	// Sep 7 - 13 2009
			day: 'MMMM D, YYYY'							// Tuesday, Sep 8, 2009
		},
		displayEventEnd:  {
			month: true,
			basicWeek: true,
			'default': true
		},
		select: _.bind(this.createEventFromGrid, this),
		eventClick: _.bind(this.eventClickCallback, this),
		eventDragStart: _.bind(this.onEventDragStart, this),
		eventDragStop: _.bind(this.onEventDragStop, this),
		eventResizeStart: _.bind(this.onEventResizeStart, this),
		eventResizeStop: _.bind(this.onEventResizeStop, this),
		eventDrop: _.bind(this.moveEvent, this),
		eventResize: _.bind(this.resizeEvent, this),
		viewRender: _.bind(this.viewRenderCallback, this),
		events: _.bind(this.eventsSource, this)
	};
	
	this.revertFunction = null;
	
	this.calendarSharing = AppData.User.AllowCalendar && AppData.User.CalendarSharing;
	
	this.defaultViewName = ko.computed(function () {
		var 
			viewName = 'month',
			needToReloadCalendar = AppData.User.needToReloadCalendarTriger()
		;
		
		switch (AppData.User.CalendarDefaultTab)
		{
			case Enums.CalendarDefaultTab.Day:
				viewName = 'agendaDay';
				break;
			case Enums.CalendarDefaultTab.Week:
				viewName = 'agendaWeek';
				break;
			case Enums.CalendarDefaultTab.Month:
				viewName = 'month';
				break;
		}
		return viewName;
	}, this);
	
	this.iAutoReloadTimer = -1;

	this.dragEventTrigger = false;
	this.delayOnEventResult = false;
	this.delayOnEventResultData = [];
	
	this.refreshView = _.throttle(_.bind(this.refreshViewSingle, this), 100);
	this.defaultCalendarId = ko.computed(function () {
		var 
			defaultCalendar = this.calendars.defaultCal()
		;
		if (defaultCalendar)
		{
			return defaultCalendar.id;
		}
	}, this);
	this.changeFullCalendarDate = true;
}

CCalendarViewModel.prototype.getFCObject = function ()
{
	return this.$calendarGrid.fullCalendar('getCalendar');
};

CCalendarViewModel.prototype.getDateFromCurrentView = function (sDateType)
{
	var
		oView = this.$calendarGrid.fullCalendar('getView'),
		oDate = oView && oView[sDateType] ? oView[sDateType] : null
	;
	if (oDate && sDateType === 'end' && oView.name === 'agendaDay')
	{
		oDate.add(1, 'd');
	}
	
	return (oDate && oDate['unix']) ? oView[sDateType]['unix']() : 0;
};

CCalendarViewModel.prototype.eventsSource = function (start, end, timezone, callback)
{
	callback(this.calendars.getEvents(start, end));
};

CCalendarViewModel.prototype.initFullCalendar = function ()
{
	this.$calendarGrid.fullCalendar(this.fullcalendarOptions);
};

CCalendarViewModel.prototype.applyCalendarSettings = function ()
{
	this.timeFormat = (AppData.User.defaultTimeFormat() === Enums.TimeFormat.F24) ? 'HH:mm' : 'hh:mm A';
	this.dateFormat = AppData.User.DefaultDateFormat;

	this.calendarGridDom().removeClass("fc-show-weekends");
	if (AppData.User.CalendarShowWeekEnds)
	{
		this.calendarGridDom().addClass("fc-show-weekends");
	}

	this.fullcalendarOptions.timeFormat = this.timeFormat;
	this.fullcalendarOptions.axisFormat = this.timeFormat;
	this.fullcalendarOptions.defaultView = this.defaultViewName();
	this.fullcalendarOptions.lang = moment.lang();
	
	this.applyFirstDay();

	this.$calendarGrid.fullCalendar('destroy');
	this.$calendarGrid.fullCalendar(this.fullcalendarOptions);
	this.changeView(this.defaultViewName());
};

CCalendarViewModel.prototype.applyFirstDay = function ()
{
	var
		aDayNames = [],
		sFirstDay = '',
		sLastDay = ''
	;

	if (AppData.Auth)
	{
		this.fullcalendarOptions.firstDay = AppData.User.CalendarWeekStartsOn;
	}
	
	_.each(this.aDayNames, function (sDayName) {
		aDayNames.push(sDayName);
	});
	
	switch (this.fullcalendarOptions.firstDay)
	{
		case 1:
			sLastDay = aDayNames.shift();
			aDayNames.push(sLastDay);
			break;
		case 6:
			sFirstDay = aDayNames.pop();
			aDayNames.unshift(sFirstDay);
			break;
	}
	
	this.$datePicker.datepicker('option', 'firstDay', this.fullcalendarOptions.firstDay);
};

CCalendarViewModel.prototype.initDatePicker = function ()
{
	this.$datePicker.datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		monthNames: Utils.getMonthNamesArray(),
		dayNamesMin: Utils.i18n('DATETIME/DAY_NAMES_MIN').split(' '),
		onChangeMonthYear: _.bind(this.changeMonthYearFromDatePicker, this),
		onSelect: _.bind(this.selectDateFromDatePicker, this),
		beforeShowDay: _.bind(this.getDayDescription, this)
	});
};

CCalendarViewModel.prototype.onApplyBindings = function ()
{
	this.$calendarGrid = $(this.calendarGridDom());
	this.$datePicker = $(this.datePickerDom());
	this.initUploader();
};

CCalendarViewModel.prototype.onShow = function ()
{
	if (!this.initialized())
	{
		this.initDatePicker();

		this.applyCalendarSettings();
		this.highlightWeekInDayPicker();
		this.initialized(true);
	}

	if (App.CalendarCache)
	{
		if (App.CalendarCache.calendarSettingsChanged() || App.CalendarCache.calendarChanged())
		{
			if (App.CalendarCache.calendarSettingsChanged())
			{
				this.applyCalendarSettings();
			}
			App.CalendarCache.calendarSettingsChanged(false);
			App.CalendarCache.calendarChanged(false);
			this.getCalendars();
		}
	}
	else if (this.isPublic)
	{
		this.$calendarGrid.fullCalendar("render");
	}
	this.refetchEvents();
};

CCalendarViewModel.prototype.setTimeline = function () 
{
	var 
		oView = this.$calendarGrid.fullCalendar("getView"),
		now = new Date(),
		nowDate = new Date(now.getFullYear(), now.getMonth(), now.getDate()),
		todayDate = new Date(this.todayDate.getFullYear(), this.todayDate.getMonth(), this.todayDate.getDate()),
		parentDiv = null,
		timeline = null,
		curSeconds = 0,
		percentOfDay = 0,
		topLoc = 0
	;

	if (todayDate < nowDate)
	{// the day has changed
		this.execCommand("today");
		this.todayDate = this.$calendarGrid.fullCalendar("getDate").toDate();
		this.$calendarGrid.fullCalendar("render");
	}
	
	// render timeline
	parentDiv = $(".fc-slats:visible").parent();
	timeline = parentDiv.children(".timeline");
	
	if (timeline.length === 0) 
	{ //if timeline isn't there, add it
		timeline = $("<hr>").addClass("timeline");
		parentDiv.prepend(timeline);
	}
	timeline.css('left', $("td .fc-axis").width() + 10);
	
	timeline.show();
	
/*	
	if (oView.start.toDate() < now && oView.end.toDate() > now)
	{
		timeline.show();
	}
	else
	{
		timeline.hide();
	}
*/
	curSeconds = (now.getHours() * 60 * 60) + (now.getMinutes() * 60) + now.getSeconds();
	percentOfDay = curSeconds / 86400; //24 * 60 * 60 = 86400, % of seconds in a day
	topLoc = Math.floor(parentDiv.height() * percentOfDay);

	timeline.css("top", topLoc + "px");
};

/**
 * @param {Object} oView
 * @param {Object} oElement
 */
CCalendarViewModel.prototype.viewRenderCallback = function (oView, oElement)
{
	var 
		count = 0,
		prevDate = null,
		constDate = "01/01/1971 ",
		timelineInterval
	;
	this.changeDate();
	
	if (!this.loaded)
	{
		this.initResizing();
	}
	
	if(typeof(timelineInterval) !== "undefined")
	{
		window.clearInterval(timelineInterval);
	}
	timelineInterval = window.setInterval(_.bind(function () {
		this.setTimeline();
	}, this), 60000);
	try 
	{
		this.setTimeline();
	} 
	catch(err) { }	
	
	if (oView.name !== 'month' && AppData.User.CalendarShowWorkDay)
	{
		$('.fc-slats tr').each(function() {
			$('tr .fc-time span').each(function() {
				var 
					theValue = $(this).eq(0).text(),
					theDate = (theValue !== '') ? Date.parse(constDate + theValue) : prevDate,
					rangeTimeFrom = Date.parse(constDate + AppData.User.CalendarWorkDayStarts + ':00'),
					rangeTimeTo = Date.parse(constDate + AppData.User.CalendarWorkDayEnds + ':00')
				;
				prevDate = theDate;
				if(theDate < rangeTimeFrom || theDate >= rangeTimeTo)
				{
					$(this).parent().parent().addClass("fc-non-working-time");
					$(this).parent().parent().next().addClass("fc-non-working-time");
				}
			});
		});	
	}
	
	this.activateCustomScrollInDayAndWeekView();
};

CCalendarViewModel.prototype.collectBusyDays = function ()
{
	var 
		aBusyDays = [],
		oStart = null,
		oEnd = null,
		iDaysDiff = 0,
		iIndex = 0
	;

	_.each(this.calendars.getEvents(), function (oEvent) {
		oStart = moment(oEvent.start);
		oEnd = oEvent.end ? moment(oEvent.end) : null;
		if (oEvent.allDay && oEnd)
		{
			oEnd.subtract(1, 'days');
		}
		
		iDaysDiff = oEnd ? oEnd.diff(oStart, 'days') : 0;
		iIndex = 0;

		for (; iIndex <= iDaysDiff; iIndex++)
		{
			aBusyDays.push(oStart.clone().add('days', iIndex).toDate());
		}
	}, this);

	this.busyDays(aBusyDays);
};

CCalendarViewModel.prototype.refreshDatePicker = function ()
{
	var self = this;
	
	_.defer(function () {
		self.collectBusyDays();
		self.$datePicker.datepicker('refresh');
		self.highlightWeekInDayPicker();
	});
};

/**
 * @param {Object} oDate
 */
CCalendarViewModel.prototype.getDayDescription = function (oDate)
{
	var
		bSelectable = true,
		oFindedBusyDay = _.find(this.busyDays(), function (oBusyDay) {
			return oBusyDay.getDate() === oDate.getDate() && oBusyDay.getMonth() === oDate.getMonth() &&
				oBusyDay.getYear() === oDate.getYear();
		}, this),
		sDayClass = oFindedBusyDay ? 'day_with_events' : '',
		sDayTitle = ''
	;
	
	return [bSelectable, sDayClass, sDayTitle];
};

CCalendarViewModel.prototype.initResizing = function ()
{
	var fResize = _.throttle(_.bind(this.resize, this), 50);

	$(window).bind('resize', function (e) {
		if (e.target !== this && !App.browser.ie8AndBelow)
		{
			return;
		}

		fResize();
	});

	fResize();
};

CCalendarViewModel.prototype.resize = function ()
{
	var oParent = this.$calendarGrid.parent();
	if (oParent)
	{
		this.$calendarGrid.fullCalendar('option', 'height', oParent.height());
	}
	this.dayNamesResize();
};

CCalendarViewModel.prototype.dayNamesResize = function ()
{
	if (this.selectedView() === 'month')
	{
		var
			oDayNamesHeaderItem = $('div.weekday-header-item'),
			oFirstWeek = $('tr.fc-first td.fc-day'),
			oFirstWeekWidth = $(oFirstWeek[0]).width(),
			iIndex = 0
		;
		
		if (oDayNamesHeaderItem.length === 7 && oFirstWeek.length === 7 && oFirstWeekWidth !== 0)
		{
			for(; iIndex < 7; iIndex++)
			{
				$(oDayNamesHeaderItem[iIndex]).width(oFirstWeekWidth);
			}
		}
	}
};

/**
 * @param {number} iYear
 * @param {number} iMonth
 * @param {Object} oInst
 */
CCalendarViewModel.prototype.changeMonthYearFromDatePicker = function (iYear, iMonth, oInst)
{
	if (this.changeFullCalendarDate)
	{
		var oDate = this.$calendarGrid.fullCalendar('getDate');
		// Date object in javascript and fullcalendar use numbers 0,1,2...11 for monthes
		// datepiker uses numbers 1,2,3...12 for monthes
		oDate
			.month(iMonth - 1)
			.year(iYear);
		this.$calendarGrid.fullCalendar('gotoDate', oDate);
	}
};

/**
 * @param {string} sDate
 * @param {Object} oInst
 */
CCalendarViewModel.prototype.selectDateFromDatePicker = function (sDate, oInst)
{
	var oDate = this.getFCObject().moment(sDate);
	this.$calendarGrid.fullCalendar('gotoDate', oDate);
	
	_.defer(_.bind(this.highlightWeekInDayPicker, this));
};

CCalendarViewModel.prototype.highlightWeekInDayPicker = function ()
{
	var
		$currentDay = this.$datePicker.find('td.ui-datepicker-current-day'),
		$currentWeek = $currentDay.parent(),
		$currentMonth = this.$datePicker.find('table.ui-datepicker-calendar'),
		oView = this.$calendarGrid.fullCalendar('getView')
	;
	
	switch (oView.name)
	{
		case 'agendaDay':
			$currentMonth.addClass('highlight_day').removeClass('highlight_week');
			break;
		case 'agendaWeek':
			$currentMonth.removeClass('highlight_day').addClass('highlight_week');
			break;
		default:
			$currentMonth.removeClass('highlight_day').removeClass('highlight_week');
			break;
	}
	
	$currentWeek.addClass('current_week');
};

CCalendarViewModel.prototype.changeDateTitle = function ()
{
	var
		oDate = this.$calendarGrid.fullCalendar('getDate'),
		oView = this.$calendarGrid.fullCalendar('getView'),
		sTitle = oDate.format('MMMM YYYY'),
		oStart = oView.intervalStart,
		oEnd = oView.intervalEnd ? oView.intervalEnd.add('days', -1) : null
	;
	
	switch (oView.name)
	{
		case 'agendaDay':
			sTitle = oDate.format('MMMM D, YYYY');
			break;
		case 'agendaWeek':
			if (oStart && oEnd)
			{
				sTitle = oStart.format('MMMM D, YYYY') + ' - ' + oEnd.format('MMMM D, YYYY');
			}
			break;
	}
	this.dateTitle(sTitle);
};

CCalendarViewModel.prototype.changeDate = function ()
{
	this.changeDateInDatePicker();
	this.changeDateTitle();
	this.getTimeLimits();
	this.getCalendars();
};

CCalendarViewModel.prototype.changeDateInDatePicker = function ()
{
	var 
		oDateMoment = this.$calendarGrid.fullCalendar('getDate')
	;
	this.changeFullCalendarDate = false;
	this.$datePicker.datepicker('setDate', oDateMoment.local().toDate());
	this.changeFullCalendarDate = true;
	this.highlightWeekInDayPicker();
};

CCalendarViewModel.prototype.activateCustomScrollInDayAndWeekView = function ()
{
	if (bMobileDevice)
	{
		return;
	}
	
	var 
		oView = this.$calendarGrid.fullCalendar('getView'),
		sGridType = oView.name === 'month' ? 'day' : 'time',
		oGridContainer = $('.fc-' + sGridType + '-grid-container'),
		oScrollWrapper = $('<div></div>')
	;
	oGridContainer.parent().append(oScrollWrapper);
	oGridContainer.appendTo(oScrollWrapper);
	
	if (!oScrollWrapper.hasClass('scroll-wrap'))
	{
		oScrollWrapper.attr('data-bind', 'customScrollbar: {x: false, y: true}');
		oGridContainer.css({'overflow': 'hidden'}).addClass('scroll-inner');
		ko.applyBindings({}, oScrollWrapper[0]);
	}
};

/**
 * @param {string} sCmd
 * @param {string=} sParam = ''
 */
CCalendarViewModel.prototype.execCommand = function (sCmd, sParam)
{
	if (sParam)
	{
		this.$calendarGrid.fullCalendar(sCmd, sParam);
	}
	else
	{
		this.$calendarGrid.fullCalendar(sCmd);
	}
};

CCalendarViewModel.prototype.displayToday = function ()
{
	this.execCommand('today');
};

CCalendarViewModel.prototype.displayPrev = function ()
{
	this.execCommand('prev');
};

CCalendarViewModel.prototype.displayNext = function ()
{
	this.execCommand('next');
};

CCalendarViewModel.prototype.changeView = function (viewName)
{
	this.selectedView(viewName);
	this.$calendarGrid.fullCalendar('changeView', viewName);
};

CCalendarViewModel.prototype.setAutoReloadTimer = function ()
{
	var self = this;
	clearTimeout(this.iAutoReloadTimer);
	
	if (AppData.User.AutoCheckMailInterval > 0)
	{
		this.iAutoReloadTimer = setTimeout(function () {
			self.getCalendars();
		}, AppData.User.AutoCheckMailInterval * 60 * 1000);
	}
};

CCalendarViewModel.prototype.reloadAll = function ()
{
//	this.startDateTime = 0;
//	this.endDateTime = 0;
	this.needsToReload = true;
	
	this.getCalendars();
};

CCalendarViewModel.prototype.getTimeLimits = function ()
{
	var
		iStart = this.getDateFromCurrentView('start'),
		iEnd = this.getDateFromCurrentView('end')
	;
	
	this.startDateTime = iStart;
	this.endDateTime = iEnd;
	this.needsToReload = true;
	
/*	
	if (this.startDateTime === 0 && this.endDateTime === 0)
	{
		this.startDateTime = iStart;
		this.endDateTime = iEnd;
		this.needsToReload = true;
	}
	else if (iStart < this.startDateTime && iEnd > this.endDateTime)
	{
		this.startDateTime = iStart;
		this.endDateTime = iEnd;
		this.needsToReload = true;
	}
	else if (iStart < this.startDateTime)
	{
		iEnd= this.startDateTime;
		this.startDateTime = iStart;
		this.needsToReload = true;
	}
	else if (iEnd > this.endDateTime)
	{
		iStart = this.endDateTime;
		this.endDateTime = iEnd;
		this.needsToReload = true;
	}
*/	
};

CCalendarViewModel.prototype.getCalendars = function ()
{
	this.checkStarted(true);
	this.setCalendarGridVisibility();	

	App.Ajax.abortRequestByActionName('CalendarList');
	App.Ajax.sendExt({
			'Action': 'CalendarList',
			'IsPublic': this.isPublic ? 1 : 0,
			'PublicCalendarId': this.publicCalendarId
		}, this.onCalendarsResponse, this
	);
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onCalendarsResponse = function (oData, oParameters)
{
	var
		aCalendarIds = [],
		aNewCalendarIds = [],
		oCalendar = null,
		oClientCalendar = null
	;

	if (oData.Result)
	{
		this.loaded = true;

		//sets default calendar aways fist in list
		oData.Result = _.sortBy(oData.Result, function(oItem){return !oItem.isDefault;});
		_.each(oData.Result, function (oCalendarData) {
			oCalendar = this.calendars.parseCalendar(oCalendarData);
			aCalendarIds.push(oCalendar.id);
			oClientCalendar = this.calendars.getCalendarById(oCalendar.id);
			if (/*this.needsToReload || */!oClientCalendar || 
					(oCalendar && oClientCalendar && 
						oClientCalendar.cTag !== oCalendar.cTag))
			{
				oCalendar = this.calendars.parseAndAddCalendar(oCalendarData);
				if (oCalendar)
				{
					if (this.isPublic)
					{
						App.setTitle(oCalendar.name());
						this.publicCalendarName(oCalendar.name());
					}
					aNewCalendarIds.push(oCalendar.id);
				}
			}
		}, this);


		if (this.calendars.count() === 0 && this.isPublic && this.needsToReload)
		{
			App.setTitle(Utils.i18n('CALENDAR/NO_CALENDAR_FOUND'));
			App.Api.showErrorByCode(0, Utils.i18n('CALENDAR/NO_CALENDAR_FOUND'));
		}

		this.needsToReload = false;
		this.calendars.expunge(aCalendarIds);

		_.each(aCalendarIds, function (sCalendarId){
			oCalendar = this.calendars.getCalendarById(sCalendarId);
			if (oCalendar && oCalendar.eventsCount() > 0)
			{
				oCalendar.reloadEvents();
			}
		}, this);
		this.getEvents(aCalendarIds);
	}
	else
	{
		this.setCalendarGridVisibility();
		this.checkStarted(false);
	}
};

/**
 * @param {Array} aCalendarIds
 */
CCalendarViewModel.prototype.getEvents = function (aCalendarIds)
{
	if (aCalendarIds.length > 0)
	{
//		this.checkStarted(true);
//		if (aCalendarIds.length > 1)
//		{
//			this.$calendarGrid.find('.fc-view div').first().css('visibility', 'hidden');
//		}
		App.Ajax.abortRequestByActionName('EventList');
		App.Ajax.sendExt({
			'CalendarIds': JSON.stringify(aCalendarIds),
			'Start': this.startDateTime,
			'End': this.endDateTime,
			'IsPublic': this.isPublic ? 1 : 0,
			'Action': 'EventList'
		}, this.onEventsResponse, this);
	}
	else
	{
		this.setAutoReloadTimer();
		this.checkStarted(false);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onEventsResponse = function (oData, oParameters)
{
	var 
		oCalendar = null,
		aCalendarIds = oParameters.CalendarIds ? JSON.parse(oParameters.CalendarIds) : [],
		aEvents = []
	;

	if (oData.Result)
	{
		_.each(oData.Result, function (oEventData) {
			oCalendar = this.calendars.getCalendarById(oEventData.calendarId);			
			if (oCalendar)
			{
				aEvents.push(oEventData.id);
				var oEvent = oCalendar.eventExists(oEventData.id);
				if (Utils.isUnd(oEvent))
				{
					oCalendar.addEvent(oEventData);
				}
				else if (oEvent.lastModified !== oEventData.lastModified)
				{
					oCalendar.updateEvent(oEventData);
				}
			}
		}, this);
		
		_.each(aCalendarIds, function (sCalendarId){
			oCalendar = this.calendars.getCalendarById(sCalendarId);
			if (oCalendar && oCalendar.eventsCount() > 0 && oCalendar.active())
			{
				oCalendar.expungeEvents(aEvents, this.startDateTime, this.endDateTime);
			}
		}, this);

		this.refreshView();
	}
	
//	this.setCalendarGridVisibility();
	this.setAutoReloadTimer();
	this.checkStarted(false);
};

CCalendarViewModel.prototype.setCalendarGridVisibility = function ()
{
	this.$calendarGrid
		.css('visibility', '')
		.find('.fc-view div')
		.first()
		.css('visibility', '')
	;
};
	
CCalendarViewModel.prototype.getUnusedColor = function ()
{
	var 
		colors = _.difference(this.colors, this.calendars.getColors())
	;
	
	return (colors.length > 0) ? colors[0] :  this.colors[0];
};

CCalendarViewModel.prototype.openCreateCalendarForm = function ()
{
	if (!this.isPublic)
	{
		var 
			oCalendar = new CCalendarModel()
		;
		oCalendar.color(this.getUnusedColor());
		App.Screens.showPopup(CalendarPopup, [_.bind(this.createCalendar, this), this.colors, oCalendar]);
	}
};

/**
 * @param {string} sName
 * @param {string} sDescription
 * @param {string} sColor
 */
CCalendarViewModel.prototype.createCalendar = function (sName, sDescription, sColor)
{
	if (!this.isPublic)
	{
		App.Ajax.send({
				'Name': sName,
				'Description': sDescription,
				'Color': sColor,
				'Action': 'CalendarCreate'
			}, this.onCalendarCreateResponse, this
		);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onCalendarCreateResponse = function (oData, oParameters)
{
	if (oData.Result)
	{
		this.calendars.parseAndAddCalendar(oData.Result);
		this.calendars.sort();
	}
};

/**
 * @param {Object} oCalendar
 */
CCalendarViewModel.prototype.openImportCalendarForm = function (oCalendar)
{
	if (!this.isPublic)
	{
		App.Screens.showPopup(CalendarImportPopup, [_.bind(this.reloadAll, this), oCalendar]);
	}
};

/**
 * @param {Object} oCalendar
 */
CCalendarViewModel.prototype.openUpdateCalendarForm = function (oCalendar)
{
	if (!this.isPublic)
	{
		App.Screens.showPopup(CalendarPopup, [_.bind(this.updateCalendar, this), this.colors, oCalendar]);
	}
};

/**
 * @param {string} sName
 * @param {string} sDescription
 * @param {string} sColor
 * @param {string} sId
 */
CCalendarViewModel.prototype.updateCalendar = function (sName, sDescription, sColor, sId)
{
	if (!this.isPublic)
	{
		App.Ajax.send({
				'Name': sName,
				'Description': sDescription,
				'Color': sColor,
				'Id': sId,
				'Action': 'CalendarUpdate'
			}, this.onCalendarUpdateResponse, this
		);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onCalendarUpdateResponse = function (oData, oParameters)
{
	var
		oCalendar = null
	;
	if (oData.Result)
	{
		oCalendar = this.calendars.getCalendarById(oParameters.Id);
		if (oCalendar)
		{
			oCalendar.name(oParameters.Name);
			oCalendar.description(oParameters.Description);
			oCalendar.color(oParameters.Color);
			this.refetchEvents();
		}
	}
};

/**
 * @param {string} sColor
 * @param {string} sId
 */
CCalendarViewModel.prototype.updateCalendarColor = function (sColor, sId)
{
	if (!this.isPublic)
	{
		App.Ajax.send({
				'Color': sColor,
				'Id': sId,
				'Action': 'CalendarUpdateColor'
			}, this.onCalendarUpdateColorResponse, this
		);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onCalendarUpdateColorResponse = function (oData, oParameters)
{
	if (oData.Result)
	{
		var oCalendar = this.calendars.getCalendarById(oParameters.Id);
		if (oCalendar)
		{
			oCalendar.color(oParameters.Color);
			this.refetchEvents();
		}
	}
};

/**
 * @param {Object} oCalendar
 */
CCalendarViewModel.prototype.openGetLinkCalendarForm = function (oCalendar)
{
	if (!this.isPublic)
	{
		App.Screens.showPopup(CalendarGetLinkPopup, [_.bind(this.publicCalendar, this), oCalendar]);
	}
};

/**
 * @param {Object} oCalendar
 */
CCalendarViewModel.prototype.openShareCalendarForm = function (oCalendar)
{
	if (!this.isPublic)
	{
		App.Screens.showPopup(CalendarSharePopup, [_.bind(this.shareCalendar, this), oCalendar]);
	}
};

/**
 * @param {string} sId
 * @param {boolean} bIsPublic
 * @param {Array} aShares
 * @param {boolean} bShareToAll
 * @param {number} iShareToAllAccess
 */
CCalendarViewModel.prototype.shareCalendar = function (sId, bIsPublic, aShares, bShareToAll, iShareToAllAccess)
{
	if (!this.isPublic)
	{
		App.Ajax.send({
				'Action': 'CalendarShareUpdate',
				'Id': sId,
				'IsPublic': bIsPublic ? 1 : 0,
				'Shares': JSON.stringify(aShares),
				'ShareToAll': bShareToAll ? 1 : 0, 
				'ShareToAllAccess': iShareToAllAccess
			}, this.onCalendarShareUpdateResponse, this
		);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onCalendarShareUpdateResponse = function (oData, oParameters)
{
	if (oData.Result)
	{
		var	oCalendar = this.calendars.getCalendarById(oParameters.Id);
		if (oCalendar)
		{
			oCalendar.shares(JSON.parse(oParameters.Shares));
			if (oParameters.ShareToAll === 1)
			{
				oCalendar.isShared(true);
				oCalendar.isSharedToAll(true);
				oCalendar.sharedToAllAccess = oParameters.ShareToAllAccess;
			}
			else
			{
//				oCalendar.isShared(false);
				oCalendar.isSharedToAll(false);
			}
		}
	}
};

/**
 * @param {string} sId
 * @param {boolean} bIsPublic
 */
CCalendarViewModel.prototype.publicCalendar = function (sId, bIsPublic)
{
	if (!this.isPublic)
	{
		App.Ajax.send({
				'Action': 'CalendarPublicUpdate',
				'Id': sId,
				'IsPublic': bIsPublic ? 1 : 0
			}, this.onCalendarPublicUpdateResponse, this
		);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onCalendarPublicUpdateResponse = function (oData, oParameters)
{
	if (oData.Result)
	{
		var	oCalendar = this.calendars.getCalendarById(oParameters.Id);
		if (oCalendar)
		{
			oCalendar.isPublic(oParameters.IsPublic);
		}
	}
};

/**
 * @param {string} sId
 */
CCalendarViewModel.prototype.deleteCalendar = function (sId)
{
	var
		oCalendar = this.calendars.getCalendarById(sId),
		sConfirm = oCalendar ? Utils.i18n('CALENDAR/CONFIRM_REMOVE_CALENDAR', {'CALENDARNAME': oCalendar.name()}) : '',
		fRemove = _.bind(function (bRemove) {
			if (bRemove)
			{
				App.Ajax.send({
						'Id': sId,
						'Action': 'CalendarDelete'
					}, this.onCalendarDeleteResponse, this
				);
			}
		}, this)
	;
	
	if (!this.isPublic && oCalendar)
	{
		App.Screens.showPopup(ConfirmPopup, [sConfirm, fRemove]);
	}	
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onCalendarDeleteResponse = function (oData, oParameters)
{
	if (oData.Result)
	{
		var oCalendar = this.calendars.getCalendarById(oParameters.Id);
		if (oCalendar && !oCalendar.isDefault)
		{
			if (this.calendars.currentCal().id === oCalendar.id)
			{
				this.calendars.currentCal(null);
			}

			this.calendars.removeCalendar(oCalendar.id);
			this.refetchEvents();
		}
	}
};

CCalendarViewModel.prototype.onEventDragStart = function ()
{
	this.dragEventTrigger = true;
	this.refreshDatePicker();
};

CCalendarViewModel.prototype.onEventDragStop = function ()
{
	var self = this;
	this.dragEventTrigger = false;
	if (this.delayOnEventResult && this.delayOnEventResultData && 0 < this.delayOnEventResultData.length)
	{
		this.delayOnEventResult = false;
		
		_.each(this.delayOnEventResultData, function (aData) {
			self.onEventActionResponse(aData[0], aData[1], false);
		});
		
		this.delayOnEventResultData = [];
		this.refreshView();
	}
	else
	{
		this.refreshDatePicker();
	}
};

CCalendarViewModel.prototype.onEventResizeStart = function ()
{
	this.dragEventTrigger = true;
};

CCalendarViewModel.prototype.onEventResizeStop = function ()
{
	var self = this;
	this.dragEventTrigger = false;
	if (this.delayOnEventResult && this.delayOnEventResultData && 0 < this.delayOnEventResultData.length)
	{
		this.delayOnEventResult = false;

		_.each(this.delayOnEventResultData, function (aData) {
			self.onEventActionResponse(aData[0], aData[1], false);
		});

		this.delayOnEventResultData = [];
		this.refreshView();
	}
	else
	{
		this.refreshDatePicker();
	}
};

CCalendarViewModel.prototype.createEventInCurrentCalendar = function ()
{
	this.createEventToday(this.calendars.currentCal());
};

/**
 * @param {string} sCalendarId
 */
CCalendarViewModel.prototype.createEventInCalendar = function (sCalendarId)
{
	this.createEventToday(this.calendars.getCalendarById(sCalendarId));
};

/**
 * @param {Object} oCalendar
 */
CCalendarViewModel.prototype.createEventToday = function (oCalendar)
{
	var oToday = this.getFCObject().moment();
	
	if (oToday.minutes() > 30)
	{
		oToday.add('minutes', 60 - oToday.minutes());
	}
	else
	{
		oToday.minutes(30);
	}
	oToday
		.seconds(0)
		.milliseconds(0);
	
	this.openEventPopup(oCalendar, oToday, oToday.clone().add('minutes', 30), false);
};

/**
 * @param {Object} oEventData
 */
CCalendarViewModel.prototype.getParamsFromEventData = function (oEventData)
{
	return {
		id: oEventData.id,
		uid: oEventData.uid,
		calendarId: oEventData.calendarId,
		newCalendarId: !Utils.isUnd(oEventData.newCalendarId) ? oEventData.newCalendarId : oEventData.calendarId,
		subject: oEventData.subject,
		allDay: oEventData.allDay ? 1 : 0,
		location: oEventData.location,
		description: oEventData.description,
		alarms: oEventData.alarms ? JSON.stringify(oEventData.alarms) : '[]',
		attendees: oEventData.attendees ? JSON.stringify(oEventData.attendees) : '[]',
		owner: oEventData.owner,
		recurrenceId: oEventData.recurrenceId,
		excluded: oEventData.excluded,
		allEvents: oEventData.allEvents,
		modified: oEventData.modified ? 1 : 0,
		start: oEventData.start.local().toDate(),
		end: oEventData.end.local().toDate(),
		startTS: oEventData.start.unix(),
		endTS: oEventData.end ? oEventData.end.unix() : oEventData.end.unix(),
		rrule: oEventData.rrule ? JSON.stringify(oEventData.rrule) : null
	};
};

/**
 * @param {Array} aParameters
 */
CCalendarViewModel.prototype.getEventDataFromParams = function (aParameters)
{
	var	oEventData = aParameters;
	
	oEventData.alarms = aParameters.alarms ? JSON.parse(aParameters.alarms) : [];
	oEventData.attendees = aParameters.attendees ? JSON.parse(aParameters.attendees) : [];

	if(aParameters.rrule)
	{
		oEventData.rrule = JSON.parse(aParameters.rrule);
	}

	return oEventData;
};

/**
 * @param {Object} oStart
 * @param {Object} oEnd
 */
CCalendarViewModel.prototype.createEventFromGrid = function (oStart, oEnd)
{
	var 
		bAllDay = !oStart.hasTime()
	;
	this.openEventPopup(this.calendars.currentCal(), oStart.local(), oEnd.local(), bAllDay);
};

/**
 * @param {Object} oCalendar
 * @param {Object} oStart
 * @param {Object} oEnd
 * @param {boolean} bAllDay
 */
CCalendarViewModel.prototype.openEventPopup = function (oCalendar, oStart, oEnd, bAllDay)
{
	if (!this.isPublic)
	{
		App.Screens.showPopup(CalendarEventPopup, [{
			CallbackSave: _.bind(this.createEvent, this),
			CallbackDelete: _.bind(this.deleteEvent, this),
			Calendars: this.calendars,
			SelectedCalendar: oCalendar.id,
			Start: oStart,
			End: oEnd,
			AllDay: bAllDay,
			TimeFormat: this.timeFormat,
			DateFormat: this.dateFormat,
			CallbackAttendeeActionDecline: _.bind(this.attendeeActionDecline, this)/*,
			Owner: oSelectedCalendar.owner()*/
		}]);
	}
};

/**
 * @param {Object} oEventData
 */
CCalendarViewModel.prototype.createEvent = function (oEventData)
{
	var 
		aParameters = this.getParamsFromEventData(oEventData)
	;

	if (!this.isPublic)
	{
		aParameters.calendarId = oEventData.newCalendarId;
		aParameters.selectStart = this.getDateFromCurrentView('start');
		aParameters.selectEnd = this.getDateFromCurrentView('end');
		aParameters.Action = 'EventCreate';
		App.Ajax.send(aParameters, this.onEventActionResponseWithSubThrottle, this);
	}
};

/**
 * @param {Object} oEventData
 */
CCalendarViewModel.prototype.eventClickCallback = function (oEventData)
{
	var
		/**
		 * @param {number} iResult
		 */
		fCallback = _.bind(function (iResult) {
			var oParams = {
					ID: oEventData.id,
					Uid: oEventData.uid,
					RecurrenceId: oEventData.recurrenceId,
					Calendars: this.calendars,
					SelectedCalendar: oEventData.calendarId,
					AllDay: oEventData.allDay,
					Location: oEventData.location,
					Description: oEventData.description,
					Subject: oEventData.subject,
					Alarms: oEventData.alarms,
					Attendees: oEventData.attendees,
					RRule: oEventData.rrule ? oEventData.rrule : null,
					Excluded: oEventData.excluded ? oEventData.excluded : false,
					Owner: oEventData.owner,
					Appointment: oEventData.appointment,
					OwnerName: oEventData.ownerName,
					TimeFormat: this.timeFormat,
					DateFormat: this.dateFormat,
					AllEvents: iResult,
					CallbackSave: _.bind(this.updateEvent, this),
					CallbackDelete: _.bind(this.deleteEvent, this),
					CallbackAttendeeActionDecline: _.bind(this.attendeeActionDecline, this)
				}
			;
			if (iResult !== Enums.CalendarEditRecurrenceEvent.None)
			{
				if (iResult === Enums.CalendarEditRecurrenceEvent.AllEvents && oEventData.rrule)
				{
					oParams.Start = moment.unix(oEventData.rrule.startBase);
					oParams.End = moment.unix(oEventData.rrule.endBase);
				}
				else
				{
					oParams.Start = oEventData.start.clone();
					oParams.Start = oParams.Start.local();
					
					oParams.End = oEventData.end.clone();
					oParams.End = oParams.End.local();
				}
				App.Screens.showPopup(CalendarEventPopup, [oParams]);
			}
		}, this)
	;
	
	if (oEventData.rrule)
	{
		if (oEventData.excluded)
		{
			fCallback(Enums.CalendarEditRecurrenceEvent.OnlyThisInstance);
		}
		else
		{
			App.Screens.showPopup(CalendarEditRecurrenceEventPopup, [fCallback]);
		}
	}
	else
	{
		fCallback(Enums.CalendarEditRecurrenceEvent.AllEvents);
	}
};

/**
 * @param {string} sAction
 * @param {Object} oParameters
 * @param {Function=} fRevertFunc = undefined
 */
CCalendarViewModel.prototype.eventAction = function (sAction, oParameters, fRevertFunc)
{
	var oCalendar = this.calendars.getCalendarById(oParameters.calendarId);
	
	if (oCalendar.access() === Enums.CalendarAccess.Read)
	{
		if (fRevertFunc)
		{
			fRevertFunc();		
		}
	}
	else
	{
		if (!this.isPublic)
		{
			if (fRevertFunc)
			{
				this.revertFunction = fRevertFunc;
			}
			
			oParameters.Action = sAction;
			App.Ajax.send(
				oParameters,
				this.onEventActionResponseWithSubThrottle, this
			);
		}
	}
};

/**
 * @param {Object} oEventData
 */
CCalendarViewModel.prototype.updateEvent = function (oEventData)
{
	var 
		oParameters = this.getParamsFromEventData(oEventData)
	;
	
	oParameters.selectStart = this.getDateFromCurrentView('start');
	oParameters.selectEnd = this.getDateFromCurrentView('end');
	
	if (oEventData.modified)
	{
		this.calendars.setDefault(oEventData.newCalendarId);
		this.eventAction('EventUpdate', oParameters);
	}
};

/**
 * @param {Object} oEventData
 * @param {number} dayDelta
 * @param {number} minuteDelta
 * @param {boolean} allDay
 * @param {Function} revertFunc
 */
CCalendarViewModel.prototype.moveEvent = function (oEventData, delta, revertFunc)
{
/*	oEventData.dayDelta = dayDelta ? dayDelta : 0;
	oEventData.minuteDelta = minuteDelta ? minuteDelta : 0;
*/
	var 
		oParameters = this.getParamsFromEventData(oEventData)
//		iNewStart = oParameters.startTimestamp,
//		iAllEvStart,
//		iAllEvEnd,

//		sConfirm = Utils.i18n('With drag-n-drop you can change the date of this single instance only. To alter the entire series, open the event and change its date.'),
//		fConfirm = _.bind(function (bConfirm) {
//			if (bConfirm)
//			{
//				oParameters.allEvents = Enums.CalendarEditRecurrenceEvent.OnlyThisInstance;
//				this.eventAction('EventUpdate', oParameters, revertFunc);
//			}
//			else if (revertFunc)
//			{
//				revertFunc();
//			}
//		}, this)
	;

	oParameters.selectStart = this.getDateFromCurrentView('start');
	oParameters.selectEnd = this.getDateFromCurrentView('end');
	if (!this.isPublic)
	{
		if (oParameters.rrule)
		{
			revertFunc(false);
/*			
			iAllEvStart = JSON.parse(oParameters.rrule).startBase;
			iAllEvEnd = JSON.parse(oParameters.rrule).until;

			if (iAllEvStart <= iNewStart && iNewStart <= iAllEvEnd)
			{
				if (oParameters.excluded)
				{
					oParameters.allEvents = Enums.CalendarEditRecurrenceEvent.OnlyThisInstance;
					this.eventAction('EventUpdate', oParameters, revertFunc);
				}
				else
				{
					App.Screens.showPopup(ConfirmPopup, [sConfirm, fConfirm, '', 'Update this instance']);
				}
			}
			else 
			{
				revertFunc(false);
			}
*/				
		}
		else
		{
			oParameters.allEvents = Enums.CalendarEditRecurrenceEvent.AllEvents;
			this.eventAction('EventUpdate', oParameters, revertFunc);
		}
	}	
	
};

/**
 * @param {Object} oEventData
 * @param {number} dayDelta, 
 * @param {number} minuteDelta, 
 * @param {Function} revertFunc
 */
CCalendarViewModel.prototype.resizeEvent = function (oEventData, delta, revertFunc)
{
	var 
		oParameters = this.getParamsFromEventData(oEventData),
		/**
		 * @param {number} iResult
		 */
		fCallback = _.bind(function (iResult) {
			if (iResult !== Enums.CalendarEditRecurrenceEvent.None)
			{
//				if (iResult === Enums.CalendarEditRecurrenceEvent.AllEvents)
//				{
//
//				}
				oParameters.allEvents = iResult;
				this.eventAction('EventUpdate', oParameters, revertFunc);
			}
			else
			{
				revertFunc();
			}
		}, this)
	;
	
	oParameters.selectStart = this.getDateFromCurrentView('start');
	oParameters.selectEnd = this.getDateFromCurrentView('end');
	if (oEventData.rrule)
	{
		if (oParameters.excluded)
		{
			fCallback(Enums.CalendarEditRecurrenceEvent.OnlyThisInstance);
		}
		else
		{
			App.Screens.showPopup(CalendarEditRecurrenceEventPopup, [fCallback]);
		}
	}
	else
	{
		fCallback(Enums.CalendarEditRecurrenceEvent.AllEvents);
	}
};

/**
 * @param {Object} oEventData
 */
CCalendarViewModel.prototype.deleteEvent = function (oEventData)
{
	this.eventAction('EventDelete', this.getParamsFromEventData(oEventData));
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 */
CCalendarViewModel.prototype.onEventActionResponseWithSubThrottle = function (oData, oParameters)
{
	if (this.dragEventTrigger)
	{
		this.delayOnEventResult = true;
		this.delayOnEventResultData.push([oData, oParameters]);
	}
	else
	{
		this.onEventActionResponse(oData, oParameters);
	}
};

/**
 * @param {Object} oData
 * @param {Object} oParameters
 * @param {boolean=} bDoRefresh
 */
CCalendarViewModel.prototype.onEventActionResponse = function (oData, oParameters, bDoRefresh)
{
	var
		oCalendar = this.calendars.getCalendarById(oParameters.calendarId),
		oEventData = null,
		oEvent = null
	;
	bDoRefresh = Utils.isUnd(bDoRefresh) ? true : !!bDoRefresh;
	if (oData && oData.Result && !Utils.isUnd(oCalendar))
	{
		if (oParameters.Action === 'EventCreate' || oParameters.Action === 'EventUpdate')
		{
			this.customscrollTop(parseInt($('.calendar .scroll-inner').scrollTop(), 10));
			
			oEvent = oCalendar.getEvent(oParameters.id);
			
			if (((!Utils.isUnd(oEvent) && !Utils.isUnd(oEvent.rrule)) || oParameters.rrule) && oParameters.allEvents === Enums.CalendarEditRecurrenceEvent.AllEvents)
			{
				oCalendar.removeEventByUid(oParameters.uid, true);
			}
			else
			{
				oCalendar.removeEvent(oParameters.id);
			}
			
			if (oParameters.newCalendarId && oParameters.newCalendarId !== oParameters.calendarId)
			{
				oCalendar = this.calendars.getCalendarById(oParameters.newCalendarId);			
			}

			_.each(oData.Result.Events, function (oEventData) {
				oCalendar.addEvent(oEventData);
			}, this);
			
			oCalendar.cTag = oData.Result.CTag;
			
			if (!oCalendar.active())
			{
				oCalendar.active(true);
			}

			if (bDoRefresh)
			{
				this.refreshView();
			}
			
			this.customscrollTop.valueHasMutated();
			this.calendars.currentCal(oCalendar);
		}
		else if (oParameters.Action === 'EventDelete')
		{
			oCalendar.cTag = oData.Result; 
			if(oParameters.allEvents === Enums.CalendarEditRecurrenceEvent.OnlyThisInstance)
			{
				oCalendar.removeEvent(oParameters.id);
			}
			else
			{
				oCalendar.removeEventByUid(oParameters.uid);
			}

			if (bDoRefresh)
			{
				this.refreshView();
			}
		}
		else if (oParameters.Action === 'EventBase')
		{
			oEventData = oData.Result;
			App.Screens.showPopup(CalendarEventPopup, [{
				CallbackSave: _.bind(this.updateEvent, this),
				CallbackDelete: _.bind(this.deleteEvent, this),
				ID: oEventData.id,
				Uid: oEventData.uid,
				RecurrenceId: oEventData.recurrenceId,
				Calendars: this.calendars,
				SelectedCalendar: oEventData.calendarId,
				Start: moment(oEventData.start * 1000),
				End: moment(oEventData.end * 1000),
				AllDay: oEventData.allDay,
				Location: oEventData.location,
				Description: oEventData.description,
				Subject: oEventData.subject,
				Alarms: oEventData.alarms,
				Attendees: oEventData.attendees,
				RRule: oEventData.rrule ? oEventData.rrule : null,
				Excluded: oEventData.excluded ? oEventData.excluded : false,
				Owner: oEventData.owner,
				Appointment: oEventData.appointment,
				TimeFormat: this.timeFormat,
				DateFormat: this.dateFormat,
				AllEvents: Enums.CalendarEditRecurrenceEvent.AllEvents
			}]);
		}
	}
	else if (oParameters.Action === 'EventUpdate' && !oData.Result &&
		1155 === Utils.pInt(oData.ErrorCode))
	{
		this.revertFunction = null;
	}
	else if (this.revertFunction)
	{
		this.revertFunction();
	}
	
	this.revertFunction = null;
};

/**
 * @param {Object} oCalendar
 * @param {string} sId
 */
CCalendarViewModel.prototype.attendeeActionDecline = function (oCalendar, sId)
{
	oCalendar.removeEvent(sId);
	this.refreshView();
};

CCalendarViewModel.prototype.refetchEvents = function ()
{
	this.$calendarGrid.fullCalendar('refetchEvents');
};

CCalendarViewModel.prototype.refreshViewSingle = function ()
{
	this.refetchEvents();
	this.refreshDatePicker();
};

CCalendarViewModel.prototype.refreshView = function () {};

CCalendarViewModel.prototype.dropItem = function () {
//    App.Screens.showPopup(CalendarSelectCalendarsPopup, [_.bind(this.createCalendar, this)]);
    this.checkStarted(true);
};

CCalendarViewModel.prototype.onUploadComplete = function () {
	this.reloadAll();
};

/**
 * Initializes file uploader.
 */
CCalendarViewModel.prototype.initUploader = function ()
{
	var 
		self = this
	;
	
	if (this.uploaderArea())
	{
		this.oJua = new Jua({
			'action': '?/Upload/Calendars/',
			'name': 'jua-uploader',
			'queueSize': 2,
			'dragAndDropElement': this.uploaderArea(),
			'disableAjaxUpload': this.isPublic ? true : false,
			'disableFolderDragAndDrop': this.isPublic ? true : false,
			'disableDragAndDrop': this.isPublic ? true : false,
			'hidden': {
				'Token': function () {
					return AppData.Token;
				},
				'AccountID': function () {
					return AppData.Accounts.currentId();
				},
				'AdditionalData':  function () {
					return JSON.stringify({
						'CalendarID': self.defaultCalendarId()
					});
				}
			}			
		});

		this.oJua
			.on('onDrop', _.bind(this.dropItem, this))
			.on('onBodyDragEnter', _.bind(this.bDragActive, this, true))
			.on('onBodyDragLeave', _.bind(this.bDragActive, this, false))
			.on('onComplete', _.bind(this.onUploadComplete, this))
		;
	}
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
 * @param {number} iDelay
 */
CInformationViewModel.prototype.showReport = function (sMessage, iDelay)
{
	var self = this;
	
	iDelay = iDelay || this.iReportDuration;
	
	if (sMessage && sMessage !== '')
	{
		this.reportMessage(sMessage);
		
		this.reportVisible(true);
		_.defer(function () {
			self.reportHidden(false);
		});
		
		clearTimeout(this.iReportTimeout);
		this.iReportTimeout = setTimeout(function () {
			self.reportHidden(true);
			setTimeout(function () {
				if (self.reportHidden())
				{
					self.reportVisible(false);
				}
			}, this.iAnimationDuration);
		}, iDelay);
	}
	else
	{
		this.reportHidden(true);
		this.reportVisible(false);
	}
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
function CScreens()
{
	var $win = $(window);
	this.resizeAll = _.debounce(function () {
		$win.resize();
	}, 100);
	
	this.oScreens = {};

	this.currentScreen = ko.observable('');

	this.popupVisibility = ko.observable(false);
	
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
			CPopupViewModel.__dom.show();
			_.delay(function() {
				CPopupViewModel.__dom.addClass('visible');
			}, 50);
			CPopupViewModel.__vm.visibility(true);

			Utils.delegateRun(CPopupViewModel.__vm, 'onShow', aParameters);
			this.popupVisibility(true);
			
			this.popups.push(CPopupViewModel);
			
			this.keyupPopupBinded = _.bind(this.keyupPopup, this, CPopupViewModel.__vm);
			$(document).on('keyup', this.keyupPopupBinded);
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
		
		// Esc
		if (27 === iKeyCode)
		{
			oViewModel.closeCommand();
		}

		// Enter or Space
		if ((13 === iKeyCode || 32 === iKeyCode) && oViewModel.onEnterHandler)
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
		this.popupVisibility(false);
		
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
 * @param {number} iDelay
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

	if (AppData.User.IsHelpdeskSupported && oScreen)
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
	this.oScreens[Enums.Screens.Calendar] = {
		'Model': CCalendarViewModel,
		'TemplateName': 'Calendar_CalendarViewModel'
	};
};

CScreens.prototype.initLayout = function ()
{
	$('#pSevenContent').append($('#CalendarPubLayout').html());
};


/**
 * @constructor
 */
function AbstractApp()
{
	this.browser = new CBrowser();
	
	this.favico = window.Favico ? new window.Favico({
		'animation': 'none'
	}) : null;

	this.Ajax = new CAjax();
	this.Screens = new CScreens();
	this.Api = new CApi();
	this.Storage = new CStorage();

	this.helpdeskUnseenCount = ko.observable(0);
	this.mailUnseenCount = ko.observable(0);
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

AbstractApp.prototype.slowMomentDateTrigger = function ()
{
	$('.moment-date-trigger-slow').each(this.momentDateTriggerCallback);
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
function AppCalendarPub()
{
	AbstractApp.call(this);

	this.init();
}

_.extend(AppCalendarPub.prototype, AbstractApp.prototype);

AppCalendarPub.prototype.init = function ()
{
	var
		oRawUserSettings = /** @type {Object} */ AppData['User'],
		oUserSettings = new CUserSettingsModel()
	;

	oUserSettings.parse(oRawUserSettings);
	AppData.User = oUserSettings;

	if (!AppData.Auth)
	{
		AppData.User.CalendarWeekStartsOn = parseInt(moment().weekday(0).format("d"));
	}
};

// proto
AppCalendarPub.prototype.tokenProblem = function ()
{
	var
		sReloadFunc= 'window.location.reload(); return false;',
		sHtmlError = Utils.i18n('WARNING/TOKEN_PROBLEM_HTML', {'RELOAD_FUNC': sReloadFunc})
	;
	
	AppData.Auth = false;
	App.Api.showError(sHtmlError, true, true);
};

AppCalendarPub.prototype.authProblem = function ()
{
};

AppCalendarPub.prototype.run = function ()
{
	this.Screens.init();
	
	this.Screens.showCurrentScreen(Enums.Screens.Calendar);
};

App = new AppCalendarPub();
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
		'Action': 'SetMobile',
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
