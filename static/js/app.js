
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
		 * @type {Object.<Function>}
		 */
		Validator = {},
	
		/**
		 * @type {Object}
		 */
		I18n = window.pSevenI18N || {},
	
		/**
		 * @type {Object}
		 */
		App = {},
	
		/**
		 * @type {Object}
		 */
		AppData = window.pSevenAppData || {},
	
		/**
		 * @type {boolean}
		 */
		bIsiOSDevice = -1 < navigator.userAgent.indexOf('iPhone') ||
			-1 < navigator.userAgent.indexOf('iPod') ||
			-1 < navigator.userAgent.indexOf('iPad'),
	
		/**
		 * @type {boolean}
		 */
		bIsAndroidDevice = -1 < navigator.userAgent.toLowerCase().indexOf('android'),
	
		bMobileDevice = bIsiOSDevice || bIsAndroidDevice
	;
	
	
	/**
	 * @constructor
	 */
	function CAjax()
	{
		this.sUrl = 'index.php?/Ajax/';
		this.aRequests = [];
		
		this.hasOpenedRequests = false;
		
		$(document).ajaxStart(_.bind(function () {
			this.hasOpenedRequests = true;
		}, this));
		
		$(document).ajaxStop(_.bind(function () {
			this.hasOpenedRequests = false;
		}, this));
	}
	
	/**
	 * @param {string} sAction
	 */
	CAjax.prototype.isAllowedOnLoginAction = function (sAction)
	{
		return sAction === 'Login' || sAction === 'LoginLanguageUpdate' || sAction === 'Logout';
	};
	
	/**
	 * @param {Object} oParameters
	 * @param {Function} fResponseHandler
	 * @param {Object} oContext
	 */
	CAjax.prototype.send = function (oParameters, fResponseHandler, oContext)
	{
		if (oParameters && (AppData.Auth || this.isAllowedOnLoginAction(oParameters.Action)))
		{
			var
				doneFunc = _.bind(this.done, this, oParameters, fResponseHandler, oContext),
				failFunc = _.bind(this.fail, this, oParameters, fResponseHandler, oContext),
				alwaysFunc = _.bind(this.always, this, oParameters),
				sToken = AppData.Token,
				oXhr = null
			;
	
			if (oParameters.AccountID === undefined && oParameters.Action !== 'Login')
			{
				oParameters.AccountID = AppData.Accounts.currentId();
			}
	
			if (sToken)
			{
				oParameters.Token = sToken;
			}
	
			this.abortRequests(oParameters);
	
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
				this.abortRequestByActionName('MessageList', oParameters.Folder);
				this.abortRequestByActionName('Message');
				break;
			case 'MessageList':
			case 'FolderClear':
				this.abortRequestByActionName('MessageList', oParameters.Folder);
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
		}
	};
	
	/**
	 * @param {string} sAction
	 * @param {string=} sFolder
	 */
	CAjax.prototype.abortRequestByActionName = function (sAction, sFolder)
	{
		_.each(this.aRequests, function (oReq, iIndex) {
			if (oReq && oReq.Parameters.Action === sAction && 
				(sAction !== 'MessageList' || sFolder === oReq.Parameters.Folder))
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
			bContinue = true,
			bLogin = this.isAllowedOnLoginAction(oParameters.Action),
			bExists = AppData.Accounts.hasAccountWithId(oParameters.AccountID),
			bDefault = (oParameters.AccountID === AppData.Accounts.defaultId())
		;
		
		if (bLogin || bExists)
		{
			if (oData && !oData.Result)
			{
				switch (oData.ErrorCode)
				{
					case Enums.Errors.InvalidToken:
						if (!bLogin)
						{
							App.tokenProblem();
							bContinue = false;
						}
						break;
					case Enums.Errors.AuthError:
						if (bDefault && !bLogin && 'AccountCreate' !== oParameters.Action)
						{
							this.abortAllRequests();
							App.authProblem();
							bContinue = false;
						}
						break;
				}
			}
	
			if (bContinue && typeof fResponseHandler === 'function')
			{
				fResponseHandler.apply(oContext, [oData, oParameters]);
			}
		}
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
		var
			oData = {
				'Result': false
			}
		;
	
		switch (sType)
		{
			case 'abort':
				break;
			default:
			case 'error':
			case 'parseerror':
				if (sErrorText !== '')
				{
					App.Api.showError(Utils.i18n('WARNING/DATA_TRANSFER_FAILED'));
	
					if (typeof fResponseHandler === 'function')
					{
						fResponseHandler.apply(oContext, [oData, oParameters]);
					}
				}
				break;
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
	};
	
	
	
	/**
	 * @enum {string}
	 */
	Enums.Screens = {
		'Login': 'login',
		'Information': 'information',
		'Header': 'header',
		'Mailbox': 'mailbox',
		'BottomMailbox': 'bottommailbox',
		'SingleMessageView': 'single-message-view',
		'Compose': 'compose',
		'SingleCompose': 'single-compose',
		'Settings': 'settings',
		'Contacts': 'contacts',
		'Calendar': 'calendar',
		'FileStorage': 'files'
	};
	
	/**
	 * @enum {number}
	 */
	Enums.MailboxLayout = {
		'Side': 0,
		'Bottom': 1
	};
	
	/**
	 * @enum {number}
	 */
	Enums.TimeFormat = {
		'F24': 0,
		'F12': 1
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
		'CanNotGetMessage': 202,
		'ImapQuota': 205,
		'MailServerError': 901
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
		'System': 9,
		'User': 10
	};
	
	/**
	 * @enum {number}
	 */
	Enums.LoginFormType = {
		'Email': 0,
		'Login': 1,
		'Both': 2
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
		'MobileSync': 'mobilesync',
		'OutLookSync': 'outlooksync'
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
		'Folders': 'folders'
	};
	
	/**
	 * @enum {number}
	 */
	Enums.ContactsGroupListType = {
		'Personal': 0,
		'SubGroup': 1,
		'Global': 2
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
		'Del': 46,
		'a': 65,
		'c': 67,
		'n': 78,
		'p': 80,
		'r': 82,
		's': 83,
		'F5': 116,
		'Comma': 188,
		'Dot': 190
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
		'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
			ko.bindingHandlers.event.init(oElement, function () {
				return {
					'keyup': function (oData, oEvent) {
						if (oEvent && 13 === window.parseInt(oEvent.keyCode, 10) && oEvent.ctrlKey)
						{
							$(oElement).trigger('change');
							fValueAccessor().call(this, oData);
						}
					}
				};
			}, fAllBindingsAccessor, oViewModel);
		}
	};
	
	ko.bindingHandlers.onEsc = {
		'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
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
	
	ko.bindingHandlers.onFocusMoveCaretToEnd = {
		'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
			ko.bindingHandlers.event.init(oElement, function () {
				return {
					'focus': function () {
						Utils.moveCaretToEnd(oElement);
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
		'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
			var
				jqElement = $(oElement),
				oCommand = fValueAccessor()
			;
	
			jqElement.addClass('scroll-wrap').customscroll(oCommand);
			
			if (!Utils.isUnd(oCommand.reset)) {
				oElement._customscroll_reset = _.throttle(function () {
					jqElement.data('customscroll').reset();
				}, 100);
			}
		},
		
		'update': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {
			if (oElement._customscroll_reset) {
				oElement._customscroll_reset();
			}
		}
	};
	
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
	
	ko.bindingHandlers.splitter = {
		'init': function (oElement, fValueAccessor) {
			var
				jqElement = $(oElement),
				oCommand = fValueAccessor()
			;
	
			setTimeout(function(){
				jqElement.splitter(_.defaults(
					oCommand,
					{
						'name': '',
						'sizeLeft': 200,
						'minLeft': 20,
						'minRight': 40
					}
				));
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
						'passClick': true
					}
				),
				element = oCommand['control'] ? jqElement.find('.control') : jqElement,
				oDocument = $(document)
			;
			
			if (!oCommand['passClick']) {
				jqElement.find(oCommand['container']).click(function (event) {event.stopPropagation();});
			}
			
			jqElement.removeClass(oCommand['expand']);
			
			if (oCommand['close'] && oCommand['close']['subscribe']) {
				oCommand['close'].subscribe(function (bValue) {
					if (!bValue) {
						oDocument.unbind('click.dropdown');
						jqElement.removeClass(oCommand['expand']);
					}
				});
			}
	
			//TODO fix data-bind click
			element.click(function(){
				if (!jqElement.hasClass(oCommand['disabled'])) {
					
					jqElement.toggleClass(oCommand['expand']);
					
					if (jqElement.hasClass(oCommand['expand'])) {
						if (!Utils.isUnd(oCommand['callback'])) {
							oCommand['callback'].call(oViewModel);
						}
						
						if (oCommand['close'] && oCommand['close']['subscribe']) {
								oCommand['close'](true);
							}
						_.defer(function(){
							oDocument.one('click.dropdown', function () {
								if (oCommand['close'] && oCommand['close']['subscribe']) {
									oCommand['close'](false);
								}
								jqElement.removeClass(oCommand['expand']);
							});
						});
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
						'control': true
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
					
					aOptions[_.indexOf(oCommand['options'], item)].addClass(oCommand['selected']);
					oText.text($.trim(item[oCommand['optionsText']]));
					
					return item[oCommand['optionsValue']];
				}
			;
			
			_.each(oCommand['options'], function (item) {
				var 
					oOption = $('<span class="item"></span>')
						.text(item[oCommand['optionsText']])
						.data('value', item[oCommand['optionsValue']])
				;
				
				aOptions.push(oOption);
				oContainer.append(oOption);
			}, this);
	
			oContainer.on('click', '.item', function () {
				var value = $(this).data('value');
				oCommand.value(value);
			});
			
			if (oCommand['value'] && oCommand['value'].subscribe)
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
			
			//TODO fix data-bind click
			jqElement.removeClass(oCommand['expand']);
			oControl.click(function(){
				if (!jqElement.hasClass(oCommand['disabled'])) {
					jqElement.toggleClass(oCommand['expand']);
					if (jqElement.hasClass(oCommand['expand'])) {
						_.defer(function(){
							$(document).one('click', function () {
								jqElement.removeClass(oCommand['expand']);
							});
						});
					}
				}
			});
		}
	};
	
	ko.bindingHandlers.moveToFolderFilter = {
		
		'init': function (oElement, fValueAccessor) {
			var
				jqElement = $(oElement),
				oCommand = _.defaults(
					fValueAccessor(), {
						'disabled': 'disabled',
						'selected': 'selected',
						'expand': 'expand',
						'control': true,
						'container': '.content'
					}
				),
				oControl = oCommand['control'] ? jqElement.find('.control') : jqElement,
				oContainer = jqElement.find(oCommand['container']),
				text = jqElement.find('.link'),
				aOptions = _.isArray(oCommand['options']) ? oCommand['options'] : oCommand['options']()
			;
	
			jqElement.removeClass(oCommand['expand']);
	
			if (oCommand['value'] && oCommand['value'].subscribe)
			{
				oCommand['value'].subscribe(function (sValue) {
	
					var oFindItem = _.find(aOptions, function (oItem) {
						return oItem[oCommand['optionsValue']] === sValue;
					});
	
					if (!oFindItem)
					{
						oFindItem = _.find(aOptions, function (oItem) {
							return oItem[oCommand['optionsValue']] === '';
						});
	
						if (oFindItem && '' !== sValue)
						{
							oCommand['value']('');
						}
					}
	
					if (oFindItem)
					{
						text.text($.trim(oFindItem[oCommand['optionsText']]));
					}				
				});
	
				oCommand['value'].valueHasMutated();
			}
	
			oContainer.on('click', '.item', function () {
				var sFolderName = $(this).data('value');
				oCommand['value'](sFolderName);
			});
	
			oControl.click(function(){
				if (!jqElement.hasClass(oCommand['disabled'])) {
					jqElement.toggleClass(oCommand['expand']);
					if (jqElement.hasClass(oCommand['expand'])) {
						_.defer(function(){
							$(document).one('click', function () {
								jqElement.removeClass(oCommand['expand']);
							});
						});
					}
				}
			});
		},
		'update': function (oElement, fValueAccessor) {
			var
				oCommand = _.defaults(
					fValueAccessor(), {
						'disabled': 'disabled',
						'selected': 'selected',
						'expand': 'expand',
						'control': true,
						'container': '.content'
					}
				),
				oContainer = $(oElement).find(oCommand['container']),
				aOptions = _.isArray(oCommand['options']) ? oCommand['options'] : oCommand['options'](),
				sFolderName = oCommand['value'] ? oCommand['value']() : ''
			;
	
			oContainer.empty();
			
			_.each(aOptions, function (item) {
				var oOption = $('<span class="item"></span>')
					.text(item[oCommand['optionsText']])
					.data('value', item[oCommand['optionsValue']]);
	
				if (sFolderName === item[oCommand['optionsValue']])
				{
					oOption.addClass('selected');
				}
	
				oContainer.append(oOption);
			});
		}
	};
	
	ko.bindingHandlers.contactcard = {
		'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel) {
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
			
			jqElement.removeClass(oCommand['expand']);
	
			element.bind('mouseover', function() {
				if (!jqElement.hasClass(oCommand['disabled'])) {
					bShown = true;
					setTimeout(function () {
						if (bShown) {
							jqElement.addClass(oCommand['expand']);
						}
					}, 200);
				}
			});
			element.bind('mouseout', function() {
				bShown = false;
				setTimeout(function () {
					if (!bShown) {
						jqElement.removeClass(oCommand['expand']);
					}
				}, 200);
			});
		}
	};
	
	ko.bindingHandlers.checkmail = {
		'update': (function () {
			var
				oOptions = null,
				jqElement = null,
				oIconIE = null
			;
			
			return function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {
				
				var 
					values = fValueAccessor(),
					state = values.state
				;
				
				if (values.state !== undefined) {
					if (!jqElement) {
						jqElement = $(oElement);
					}
	
					if (!oOptions) {
						oOptions = _.defaults(
							values, {
								'activeClass': 'process',
								'duration': 800
							}
						);
					}
	
					Utils.deferedUpdate(jqElement, state, oOptions['duration'], function(element, state){
						if ($.browser.msie && Utils.pInt($.browser.version) <= 9) {
							if (!oIconIE) {
								oIconIE = jqElement.find('.icon');
							}
							
							if (!oIconIE.__intervalIE && !!state) {
								var 
									i = 0,
									style = ''
								;
	
								oIconIE.__intervalIE = setInterval(function() {
									style = '0px -' + (20 * i) + 'px';
									i = i < 7 ? i + 1 : 0;
									oIconIE.css({'background-position': style});
								} , 1000/12);
							} else {
								oIconIE.css({'background-position': '0px 0px'});
								clearInterval(oIconIE.__intervalIE);
								oIconIE.__intervalIE = null;
							}
						} else {
							element.toggleClass(oOptions['activeClass'], state);
						}
					});
				}
			};
		}())
	};
	
	ko.bindingHandlers.quickReplyAnim = {
		'update': (function () {
			var
				jqTextarea = null,
				jqStatus = null,
				jqButtons = null,
				jqElement = null,
				oPrevActions = {
					'saveAction': null,
					'sendAction': null,
					'activeAction': null
				}
			;
			
			return function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {
	
				var 
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
				
				if (!jqElement) {
					jqElement = $(oElement);
					jqTextarea = jqElement.find('textarea');
					jqStatus = jqElement.find('.status');
					jqButtons = jqElement.find('.buttons');
				}
				
				if (jqElement.is(':visible')) {
					if ($.browser.msie && Utils.pInt($.browser.version) <= 9) {
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
								if (oActions['activeAction']) {
									jqTextarea.animate({
										'height': jqTextarea.defualtHeight + 50
									}, 300);
									jqElement.animate({
										'max-height': jqElement.defualtHeight + jqButtons.defualtHeight + 50
									}, 300);
								} else {
									jqTextarea.animate({
										'height': jqTextarea.defualtHeight
									}, 300);
									jqElement.animate({
										'max-height': jqElement.defualtHeight
									}, 300);
								}
							}
							
							if (sendChanged || saveChanged) {
								if (oActions['sendAction']) {
									jqElement.animate({
										'max-height': '30px'
									}, 300);
									jqStatus.animate({
										'max-height': '30px',
										'opacity': 1
									}, 300);
								} else if (oActions['saveAction']) {
									jqElement.animate({
										'max-height': 0
									}, 300);
								} else {
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
					} else {
						jqElement.toggleClass('saving', oActions['saveAction']);
						jqElement.toggleClass('sending', oActions['sendAction']);
						jqElement.toggleClass('active', oActions['activeAction']);
					}
				}
				
				_.defer(function () {
					oPrevActions = oActions;
				});
			};
		}())
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
						var terms = split(this.value);
	
						terms.pop();
						terms.push(ui['item']['value']);
						terms.push('');
	
						this.value = terms.join(', ').slice(0, -2);
	
						oJqElement.trigger('change');
	
						// Move to the end of the input string
						var moveCursorToEnd = function(el) {
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
	
	ko.bindingHandlers.draggablePlace = {
		'init': function (oElement, fValueAccessor, fAllBindingsAccessor, oViewModel, bindingContext) {
			$(oElement).draggable({
				'distance': 20,
				'handle': '.dragHandle',
				'cursorAt': {'top': 22, 'left': 3},
				'helper': function (oEvent) {
					return fValueAccessor().call(oViewModel, oEvent && oEvent.target ? ko.dataFor(oEvent.target) : null);
				}
			}).on('mousedown', function () {
				Utils.removeActiveFocus();
			});
		}
	};
	
	ko.bindingHandlers.droppable = {
		'init': function (oElement, fValueAccessor) {
			var fValueFunc = fValueAccessor();
			if (false !== fValueFunc)
			{
				$(oElement).droppable({
					'hoverClass': 'droppableHover',
					'drop': function (oEvent, oUi) {
						fValueFunc(oEvent, oUi);
					}
				});
			}
		}
	};
	
	/**
	 * @constructor
	 */
	function CRouting()
	{
		var $win = $(window);
		this.resizeAll = _.debounce(function () {
			$win.resize();
		}, 100);
	
		this.lastMailboxRouting = '';
	
		this.currentHash = '';
		this.previousHash = '';
	}
	
	/**
	 * Initializes object.
	 */
	CRouting.prototype.init = function ()
	{
		hasher.initialized.removeAll();
		hasher.changed.removeAll();
		hasher.initialized.add(this.parseRouting, this);
		hasher.changed.add(this.parseRouting, this);
		hasher.init();
	};
	
	/**
	 * Finalizes the object and puts an empty hash.
	 */
	CRouting.prototype.finalize = function ()
	{
		hasher.initialized.removeAll();
		hasher.changed.removeAll();
		this.setHashFromString('');
	};
	
	/**
	 * Sets a new hash.
	 * 
	 * @param {string} sNewHash
	 */
	CRouting.prototype.setHashFromString = function (sNewHash)
	{
		if (location.hash !== sNewHash)
		{
			location.hash = sNewHash;
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
	 */
	CRouting.prototype.setHash = function (aRoutingParts)
	{
		this.setHashFromString(this.buildHashFromArray(aRoutingParts));
	};
	
	/**
	 * @param {Array} aRoutingParts
	 */
	CRouting.prototype.replaceHash = function (aRoutingParts)
	{
		this.replaceHashFromString(this.buildHashFromArray(aRoutingParts));
	};
	
	CRouting.prototype.setLastMailboxHash = function ()
	{
		this.setHashFromString(this.lastMailboxRouting);
	};
	
	CRouting.prototype.setPreviousHash = function ()
	{
		location.hash = this.previousHash;
	};
	
	/**
	 * Makes a hash of a string array.
	 *
	 * @param {(string|Array)} aRoutingParts
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
	
	/**
	 * Parses the hash string and opens the corresponding routing screen.
	 */
	CRouting.prototype.parseRouting = function ()
	{
		var
			sHash = this.getHashFromHref(),
			aHash = sHash.split('/'),
			sScreen = decodeURIComponent(aHash.shift()) || Enums.Screens.Mailbox,
			iIndex = 0,
			iLen = aHash.length
		;
	
		if (sScreen === Enums.Screens.Mailbox)
		{
			this.lastMailboxRouting = sHash;
		}
		this.previousHash = this.currentHash;
		this.currentHash = sHash;
		
		for (; iIndex < iLen; iIndex++)
		{
			aHash[iIndex] = decodeURIComponent(aHash[iIndex]);
		}
	
		switch (sScreen)
		{
			case Enums.Screens.SingleMessageView:
			case Enums.Screens.SingleCompose:
				AppData.SingleMode = true;
				App.Screens.showCurrentScreen(sScreen, aHash);
				break;
			case Enums.Screens.Compose:
			case Enums.Screens.Contacts:
			case Enums.Screens.Calendar:
			case Enums.Screens.FileStorage:
			case Enums.Screens.Settings:
				AppData.SingleMode = false;
				App.Screens.showNormalScreen(Enums.Screens.Header);
				App.Screens.showCurrentScreen(sScreen, aHash);
				break;
			default:
			case Enums.Screens.Mailbox:
				AppData.SingleMode = false;
				App.Screens.showNormalScreen(Enums.Screens.Header);
				if (AppData.User.Layout === Enums.MailboxLayout.Side)
				{
					App.Screens.showCurrentScreen(Enums.Screens.Mailbox, aHash);
				}
				else
				{
					App.Screens.showCurrentScreen(Enums.Screens.BottomMailbox, aHash);
				}
				break;
		}
	
		this.resizeAll();
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
	 * @return {Array}
	 */
	CLinkBuilder.prototype.mailbox = function (sFolder, iPage, sUid, sSearch)
	{
		var	aResult = [Enums.Screens.Mailbox];
		
		iPage = Utils.isNormal(iPage) ? Utils.pInt(iPage) : 1;
		sUid = Utils.isNormal(sUid) ? Utils.pString(sUid) : '';
		sSearch = Utils.isNormal(sSearch) ? Utils.pString(sSearch) : '';
	
		if (sFolder && '' !== sFolder)
		{
			aResult.push(sFolder);
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
			sTemp = ''
		;
		
		if (aParams.length > 0)
		{
			if (Utils.isNormal(aParams[0]))
			{
				sFolder = Utils.pString(aParams[0]);
			}
	
			if (aParams[1])
			{
				sTemp = Utils.pString(aParams[1]);
				if (this.isPageParam(sTemp))
				{
					iPage = Utils.pInt(sTemp.substr(1));
					if (iPage <= 0)
					{
						iPage = 1;
					}
				}
				else if (this.isMsgParam(sTemp))
				{
					sUid = sTemp.substr(3);
				}
				else
				{
					sSearch = sTemp;
				}
			}
	
			if ('' === sSearch)
			{
				if (aParams[2])
				{
					sTemp = Utils.pString(aParams[2]);
					if (this.isMsgParam(sTemp))
					{
						sUid = sTemp.substr(3);
					}
					else
					{
						sSearch = sTemp;
					}
				}
	
				if (aParams[3])
				{
					sSearch = Utils.pString(aParams[3]);
				}
			}
		}
		
		return {
			'Folder': sFolder,
			'Page': iPage,
			'Uid': sUid,
			'Search': sSearch
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
	 * 
	 * @return {Array}
	 */
	CLinkBuilder.prototype.composeFromMessage = function (sType, sFolder, sUid)
	{
		var sScreen = (AppData.SingleMode) ? Enums.Screens.SingleCompose : Enums.Screens.Compose;
		
		return [sScreen, sType, sFolder, sUid];
	};
	
	CLinkBuilder.prototype.composeWithToField = function (sTo)
	{
		var sScreen = (AppData.SingleMode) ? Enums.Screens.SingleCompose : Enums.Screens.Compose;
		
		return [sScreen, 'to', sTo];
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
			sLoadingMessage = '',
			sSentFolder = App.Cache.folderList().sentFolderFullName(),
			sDraftFolder = App.Cache.folderList().draftsFolderFullName()
		;
		
		oParameters.Action = sAction;
		oParameters.IsHtml = '1';
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
				}
				break;
			case 'MessageSave':
				sLoadingMessage = Utils.i18n('COMPOSE/INFO_SAVING');
				oParameters.DraftFolder = sDraftFolder;
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
			sDraftFolder = App.Cache.folderList().draftsFolderFullName()
		;
		
		if (sDraftUid !== '')
		{
			oData.Parameters.DraftUid = sDraftUid;
			oData.Parameters.DraftFolder = sDraftFolder;
		}
		
		if (this.postponedMailData)
		{
			App.Ajax.send(oData.Parameters, oData.MessageSendResponseHandler, oData.MessageSendResponseContext);
			this.postponedMailData = null;
		}
	};
	
	/**
	 * @param {string} sAction
	 * @param {string} sText
	 * @param {string} sDraftUid
	 * @param {Function} fMessageSendResponseHandler
	 * @param {Object} oMessageSendResponseContext
	 */
	CMessageSender.prototype.sendReplyMessage = function (sAction, sText, sDraftUid, fMessageSendResponseHandler, 
															oMessageSendResponseContext)
	{
		var oParameters = null;
		
		if (App.Cache.currentMessage())
		{
			oParameters = this.getReplyDataFromMessage(App.Cache.currentMessage(), 
				Enums.ReplyType.ReplyAll, AppData.Accounts.currentId(), sText, sDraftUid);
	
			oParameters.Bcc = '';
			oParameters.Importance = Enums.Importance.Normal;
			oParameters.Sensivity = Enums.Sensivity.Nothing;
			oParameters.ReadingConfirmation = '0';
	
			oParameters.Attachments = this.convertAttachmentsForSending(oParameters.Attachments);
	
			this.send(sAction, oParameters, AppData.User.getSaveMailInSentItems(), false,
				fMessageSendResponseHandler, oMessageSendResponseContext);
		}
	};
	
	/**
	 * @param {Array} aAttachments
	 * @return Object
	 */
	CMessageSender.prototype.convertAttachmentsForSending = function (aAttachments)
	{
		var oAttachments = {};
		
		_.each(aAttachments, function (oAttach) {
			oAttachments[oAttach.tempName()] = [
				oAttach.fileName(),
				oAttach.cid(),
				oAttach.inline() ? '1' : '0',
				oAttach.linked() ? '1' : '0'
			];
		});
		
		return oAttachments;
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 * @return Object
	 */
	CMessageSender.prototype.onMessageSendResponse = function (oData, oParameters)
	{
		var
			oParentApp = (AppData.SingleMode) ? window.opener.App : App,
			bResult = !!oData.Result,
			sFullName, sUid, sReplyType
		;
	
		App.Api.hideLoading();
		switch (oParameters.Action)
		{
			case 'MessageSave':
				if (!bResult)
				{
					if (oParameters.ShowReport)
					{
						App.Api.showError(Utils.i18n('COMPOSE/ERROR_MESSAGE_SAVING'));
					}
				}
				else
				{
					if (oParameters.ShowReport)
					{
						App.Api.showReport(Utils.i18n('COMPOSE/REPORT_MESSAGE_SAVED'));
					}
	
					if (!oData.Result.NewUid)
					{
						AppData.App.AutoSave = false;
					}
				}
				break;
			case 'MessageSend':
				if (!bResult)
				{
					App.Api.showError(Utils.i18n('COMPOSE/ERROR_MESSAGE_SENDING'));
				}
				else
				{
					oParentApp.Api.showReport(Utils.i18n('COMPOSE/REPORT_MESSAGE_SENT'));
	
					if (_.isArray(oParameters.DraftInfo) && oParameters.DraftInfo.length === 3)
					{
						sReplyType = oParameters.DraftInfo[0];
						sUid = oParameters.DraftInfo[1];
						sFullName = oParameters.DraftInfo[2];
						App.Cache.markMessageReplied(oParameters.AccountID, sFullName, sUid, sReplyType);
					}
				}
				
				if (oParameters.SentFolder)
				{
					oParentApp.Cache.removeMessagesFromCacheForFolder(oParameters.SentFolder);
				}
				
				break;
		}
	
		if (oParameters.DraftFolder)
		{
			oParentApp.Cache.removeMessagesFromCacheForFolder(oParameters.DraftFolder);
		}
		
		return {Action: oParameters.Action, Result: bResult, NewUid: oData.Result ? oData.Result.NewUid : ''};
	};
	
	/**
	 * @param {Object} oMessage
	 * @param {string} sReplyType
	 * @param {number} iAccountId
	 * @param {string} sText
	 * @param {string} sDraftUid
	 */
	CMessageSender.prototype.getReplyDataFromMessage = function (oMessage, sReplyType, iAccountId, sText, sDraftUid)
	{
		var
			oReplyData = {
				DraftInfo: [],
				DraftUid: '',
				To: '',
				Cc: '',
				Subject: '',
				Attachments: [],
				InReplyTo: oMessage.messageId(),
				References: this.getReplyReferences(oMessage)
			}
		;
		
		if (!sText || sText === '')
		{
			sText = this.replyText();
			this.replyText('');
		}
		
		oReplyData.Text = sText + this.getReplyMessageBody(oMessage, iAccountId);
		
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
				oReplyData.To = oMessage.oFrom.getFull();
				oReplyData.Subject = this.replySubjectAdd(Utils.i18n('COMPOSE/REPLY_PREFIX'), oMessage.subject());
				oReplyData.Attachments = _.filter(oMessage.attachments(), function (oAttach) {
					return oAttach.linked();
				});
				break;
			case Enums.ReplyType.ReplyAll:
				oReplyData.DraftInfo = [Enums.ReplyType.ReplyAll, oMessage.uid(), oMessage.folder()];
				oReplyData.To = oMessage.oFrom.getFull();
				oReplyData.Cc = this.getReplyAllCcAddr(oMessage, iAccountId);
				oReplyData.Subject = this.replySubjectAdd(Utils.i18n('COMPOSE/REPLY_PREFIX'), oMessage.subject());
				oReplyData.Attachments = _.filter(oMessage.attachments(), function (oAttach) {
					return oAttach.linked();
				});
				break;
			case Enums.ReplyType.Forward:
				oReplyData.DraftInfo = [Enums.ReplyType.Forward, oMessage.uid(), oMessage.folder()];
				oReplyData.Subject = this.replySubjectAdd(Utils.i18n('COMPOSE/FORWARD_PREFIX'), oMessage.subject());
				oReplyData.Attachments = oMessage.attachments();
				break;
		}
		
		return oReplyData;
	};
	
	/**
	 * Prepares and returns references for reply message.
	 *
	 * @param {Object} oMessage
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
	 * @return {string}
	 */
	CMessageSender.prototype.getReplyMessageBody = function (oMessage, iAccountId)
	{
		var
			oDomText = oMessage.getDomText(),
			sText = oDomText.length > 0 ? oDomText.html() : '',
			sCcAddr = Utils.encodeHtml(oMessage.oCc.getFull()),
			sCcPart = (sCcAddr !== '') ? Utils.i18n('COMPOSE/REPLY_MESSAGE_BODY_CC', {'CCADDR': sCcAddr}) : '',
			sReplyTitle = Utils.i18n('COMPOSE/REPLY_MESSAGE_TITLE', {
				'FROMADDR': Utils.encodeHtml(oMessage.oFrom.getFull()),
				'TOADDR': Utils.encodeHtml(oMessage.oTo.getFull()),
				'CCPART': sCcPart,
				'FULLDATE': oMessage.oDateModel.getFullDate(),
				'SUBJECT': oMessage.subject()
			}),
			oAccount = AppData.Accounts.getAccount(iAccountId),
			oSignature = oAccount.signature(),
			sSignature = (oSignature && oSignature.options()) ? oSignature.signature() + '<br />' : '',
			sReplyBody = '<br /><br />' + sSignature + '<blockquote>' +
				sReplyTitle + '<br /><br />' + sText + '</blockquote>'
		;
	
		return sReplyBody;
	};
	
	/**
	 * Prepares and returns cc address for reply message.
	 *
	 * @param {Object} oMessage
	 * @param {number} iAccountId
	 * @return {string}
	 */
	CMessageSender.prototype.getReplyAllCcAddr = function (oMessage, iAccountId)
	{
		var
			oAddressList = new CAddressListModel(),
			aAddrCollection = _.union(oMessage.oTo.aCollection, oMessage.oCc.aCollection, 
				oMessage.oBcc.aCollection),
			oCurrAccount = _.find(AppData.Accounts.Collection(), function (oAccount) {
				return oAccount.id() === iAccountId;
			}, this),
			oCurrAccAddress = new CAddressModel()
		;
	
		oCurrAccAddress.sEmail = oCurrAccount.email();
		oAddressList.addCollection(aAddrCollection);
		oAddressList.excludeCollection(_.union(oMessage.oFrom.aCollection, [oCurrAccAddress]));
	
		return oAddressList.getFull();
	};
	
	/**
	 * @param {string} sPrefix
	 * @param {string} sSubject
	 * @return {string}
	 */
	CMessageSender.prototype.replySubjectAdd = function (sPrefix, sSubject)
	{
		
		var
			oMatch = null,
			sResult = Utils.trim(sSubject)
		;
	
		if (null !== (oMatch = (new window.RegExp('^' + sPrefix + '[\\s]?\\:(.*)$', 'gi')).exec(sSubject)) && !Utils.isUnd(oMatch[1]))
		{
			sResult = sPrefix + '[2]: ' + oMatch[1];
		}
		else if (null !== (oMatch = (new window.RegExp('^(' + sPrefix + '[\\s]?[\\[\\(]?)([\\d]+)([\\]\\)]?[\\s]?\\:.*)$', 'gi')).exec(sSubject)) &&
			!Utils.isUnd(oMatch[1]) && !Utils.isUnd(oMatch[2]) && !Utils.isUnd(oMatch[3]))
		{
			sResult = oMatch[1] + (Utils.pInt(oMatch[2]) + 1) + oMatch[3];
			sResult = oMatch[1] + (Utils.pInt(oMatch[2]) + 1) + oMatch[3];
		}
		else
		{
			sResult = sPrefix + ': ' + sSubject;
		}
	
		return sResult;
	};
	
	/**
	 * @return {string}
	 */
	CMessageSender.prototype.getHtmlFromText = function (sPlain)
	{
		return sPlain
			.replace(/&/g, '&amp;').replace(/>/g, '&gt;').replace(/</g, '&lt;')
			.replace(/\r/g, '').replace(/\n/g, '<br />')
		;
	};
	
	
	/**
	 * @constructor
	 */
	function CPrefetcher()
	{
		this.prefetchStarted = ko.observable(false);
		
		this.init();
	}
	
	CPrefetcher.prototype.init = function ()
	{
		setInterval(_.bind(function () {
			this.prefetchStarted(false);
			this.start();
		}, this), 2000);
	};
	
	CPrefetcher.prototype.start = function ()
	{
		if (!AppData.SingleMode && AppData.App.AllowPrefetch && !App.Ajax.hasOpenedRequests && !this.prefetchStarted())
		{
			this.startMessagesPrefetch();
	
			this.startOtherPagesPrefetch();
			
			this.startOtherFoldersPrefetch();
		}
	};
	
	CPrefetcher.prototype.startOtherPagesPrefetch = function ()
	{
		var oCurrFolder = App.Cache.folderList().currentFolder();
		
		this.startPagePrefetch(oCurrFolder, App.Cache.page() + 1);
		
		this.startPagePrefetch(oCurrFolder, App.Cache.page() - 1);
	};
	
	/**
	 * @param {Object} oCurrFolder
	 * @param {number} iPage
	 */
	CPrefetcher.prototype.startPagePrefetch = function (oCurrFolder, iPage)
	{
		if (!this.prefetchStarted() && oCurrFolder)
		{
			var
				oUidList = App.Cache.uidList(),
				iOffset = (iPage - 1) * AppData.User.MailsPerPage,
				bPageExists = iPage > 0 && iOffset < oUidList.searchCount(),
				oParams = {
					folder: oCurrFolder.fullName(),
					page: iPage,
					search: oUidList.search()
				},
				oRequestData = null
			;
			
			if (bPageExists && !oCurrFolder.hasListBeenRequested(oParams))
			{
				oRequestData = App.Cache.requestMessageList(oParams.folder, oParams.page, oParams.search, false);
	
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
				oSent = App.Cache.folderList().sentFolder(),
				oDrafts = App.Cache.folderList().draftsFolder(),
				oInbox = App.Cache.folderList().inboxFolder(),
				aInboxSubFolders = oInbox ? oInbox.subfolders() : [],
				aOtherFolders = _.filter(App.Cache.folderList().collection(), function (oFolder) {
					return !oFolder.isSystem();
				}, this),
				aFolders = _.union(aInboxSubFolders, aOtherFolders),
				o1Folder = (aFolders.length > 0) ? aFolders[0] : null,
				o2Folder = (aFolders.length > 1) ? aFolders[1] : null,
				o3Folder = (aFolders.length > 2) ? aFolders[2] : null
			;
	
			this.startFolderPrefetch(oInbox);
			this.startFolderPrefetch(oSent);
			this.startFolderPrefetch(oDrafts);
			this.startFolderPrefetch(o1Folder);
			this.startFolderPrefetch(o2Folder);
			this.startFolderPrefetch(o3Folder);
		}
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
				oRequestData = App.Cache.requestMessageList(oParams.folder, oParams.page, oParams.search, false);
	
				if (oRequestData && oRequestData.RequestStarted)
				{
					this.prefetchStarted(true);
				}
			}
		}
	};
	
	CPrefetcher.prototype.startMessagesPrefetch = function ()
	{
		var
			iAccountId = App.Cache.currentAccountId(),
			oCurrFolder = App.Cache.getCurrentFolder(),
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
					iTotalSize += oMsg.textSize() + iJsonSizeOf1Message;
				}
			}
		;
		
		if (oCurrFolder && oCurrFolder.selected() && !this.prefetchStarted())
		{
			_.each(App.Cache.messages(), fFillUids);
			_.each(oCurrFolder.oMessages, fFillUids);
			
			if (aUids.length > 0)
			{
				oCurrFolder.addRequestedUids(aUids);
				
				oParameters = {
					'AccountID': iAccountId,
					'Action': 'Messages',
					'Folder': oCurrFolder.fullName(),
					'Uids': aUids
				};
				
				App.Ajax.send(oParameters, this.onPrefetchResponse, this);
				this.prefetchStarted(true);
			}
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CPrefetcher.prototype.onPrefetchResponse = function (oData, oParameters)
	{
		var
			oFolder = App.Cache.getFolderByFullName(oParameters.AccountID, oParameters.Folder)
		;
		
		if (_.isArray(oData.Result))
		{
			_.each(oData.Result, function (oRawMsg) {
				var
					sUid = oRawMsg.Uid.toString(),
					oMsg = oFolder.oMessages[sUid] || new CMessageModel()
				;
				
				oMsg.parse(oRawMsg, oData.AccountID);
				
				oFolder.oMessages[sUid] = oMsg;
			});
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
	 * @return {boolean}
	 */
	Utils.isUnd = function (mValue)
	{
		return undefined === mValue;
	};
	
	/**
	 * @param {*} oValue
	 * @return {boolean}
	 */
	Utils.isNull = function (oValue)
	{
		return null === oValue;
	};
	
	/**
	 * @param {*} oValue
	 * @return {boolean}
	 */
	Utils.isNormal = function (oValue)
	{
		return !Utils.isUnd(oValue) && !Utils.isNull(oValue);
	};
	
	/**
	 * @param {(string|number)} mValue
	 * @return {boolean}
	 */
	Utils.isNumeric = function (mValue)
	{
		return Utils.isNormal(mValue) ? (/^[1-9]+[0-9]*$/).test(mValue.toString()) : false;
	};
	
	/**
	 * @param {*} iValue
	 * @return {number}
	 */
	Utils.pInt = function (iValue)
	{
		return Utils.isNormal(iValue) && '' !== iValue ? window.parseInt(iValue, 10) : 0;
	};
	
	/**
	 * @param {*} mValue
	 * @return {string}
	 */
	Utils.pString = function (mValue)
	{
		return Utils.isNormal(mValue) ? mValue.toString() : '';
	};
	
	/**
	 * @param {*} aValue
	 * @return {boolean}
	 */
	Utils.isNonEmptyArray = function (aValue)
	{
		return _.isArray(aValue) && 0 < aValue.length;
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
	 * @param {Object=} oValueList
	 * @param {?string=} sDefaulValue
	 * @param {number=} nPluralCount
	 * @return {string}
	 */
	Utils.i18n = function (sKey, oValueList, sDefaulValue, nPluralCount) {
	
		var
			sValueName = '',
			sResult = I18n[sKey] || (Utils.isNormal(sDefaulValue) ? sDefaulValue : sKey)
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
	
		if (!Utils.isUnd(oValueList) && !Utils.isNull(oValueList))
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
	 * @return {number}
	 */
	Utils.roundNumber = function (iNum, iDec)
	{
		return Math.round(iNum * Math.pow(10, iDec)) / Math.pow(10, iDec);
	};
	
	/**
	 * @param {(number|string)} iSizeInBytes
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
	
		var
			oTimeOuts = {}
		;
	
		return function (sAction, fFunction, iTimeOut)
		{
			if (Utils.isUnd(oTimeOuts[sAction]))
			{
				oTimeOuts[sAction] = 0;
			}
	
			window.clearTimeout(oTimeOuts[sAction]);
			oTimeOuts[sAction] = window.setTimeout(fFunction, iTimeOut);
		};
	}());
	
	/**
	 * @param {...*} var_args javascript annotation for variable numbers of arguments
	 */
	Utils.log = function (var_args)
	{
	//	if (window.console && window.console.log)
	//	{
	//		window.console.log(var_args);
	//	}
	};
	
	/**
	 * @param {string} sFullEmail
	 * @return
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
			sEmail = $.trim(sFullEmail);
		}
		else
		{
			sName = (iQuote1Pos === -1) ?
				$.trim(sFullEmail.substring(0, iLeftBrocketPos)) :
				$.trim(sFullEmail.substring(iQuote1Pos + 1, iQuote2Pos));
	
			sEmail = $.trim(sFullEmail.substring(iLeftBrocketPos + 1, iRightBrocketPos));
		}
	
		return {
			'name': sName,
			'email': sEmail,
			'FullEmail': sFullEmail
		};
	};
	
	/**
	 * @param {string} sValue
	 */
	Utils.isCorrectEmail = function (sValue)
	{
		return (sValue.match(/^[A-Z0-9\"!#\$%\^\{\}`~&'\+\-=_\.]+@[A-Z0-9\.\-]+$/i));
	};
	
	/**
	 * @param {string} sAddresses
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
			sFullEmail = $.trim(aEmails[iIndex]);
			if (sFullEmail.length > 0)
			{
				oEmailParts = Utils.getEmailParts($.trim(aEmails[iIndex]));
				if (!Utils.isCorrectEmail(oEmailParts.email))
				{
					aIncorrectEmails.push(oEmailParts.email);
				}
			}
		}
	
		return aIncorrectEmails;
	};
	
	/**
	 * Gets link for contacts inport.
	 *
	 * @return {string}
	 */
	Utils.getImportContactsLink = function ()
	{
		return 'index.php?/ImportContacts/';
	};
	
	/**
	 * Gets link for contacts export.
	 *
	 * @return {string}
	 */
	Utils.getExportContactsLink = function ()
	{
		return 'index.php?/Raw/Contacts/';
	};
	
	/**
	 * Gets link for calendar export by hash.
	 *
	 * @param {number} iAccountId
	 * @param {string} sHash
	 */
	Utils.getExportCalendarLinkByHash = function (iAccountId, sHash)
	{
		return 'index.php?/Raw/Calendar/' + iAccountId + '/' + sHash;
	};
	
	/**
	 * Gets link for download by hash.
	 *
	 * @param {number} iAccountId
	 * @param {string} sHash
	 */
	Utils.getDownloadLinkByHash = function (iAccountId, sHash)
	{
		return 'index.php?/Raw/Download/' + iAccountId + '/' + sHash;
	};
	
	/**
	 * Gets link for view by hash.
	 *
	 * @param {number} iAccountId
	 * @param {string} sHash
	 */
	Utils.getViewLinkByHash = function (iAccountId, sHash)
	{
		return 'index.php?/Raw/View/' + iAccountId + '/' + sHash;
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
	
	Utils.getAppPath = function ()
	{
		return window.location.protocol + '//' + window.location.host + window.location.pathname;
	};
	
	Utils.WindowOpener = {
	
		_iDefaultRatio: 0.8,
		_aOpenedWins: [],
		
		/**
		 * @param {Object} oMessage
		 * @param {boolean=} bDrafts
		 */
		openMessage: function (oMessage, bDrafts)
		{
			if (oMessage)
			{
				var
					sFolder = oMessage.folder(),
					sUid = oMessage.uid(),
					sHash = ''
				;
				
				if (bDrafts)
				{
					sHash = App.Routing.buildHashFromArray([Enums.Screens.SingleCompose, 'drafts', 
						sFolder, sUid]);
				}
				else
				{
					sHash = App.Routing.buildHashFromArray([Enums.Screens.SingleMessageView, 
						sFolder, 'msg' + sUid]);
				}
	
				this.open(sHash, oMessage.subject());
			}
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
	 * @param {Object} eInput
	 */
	Utils.moveCaretToEnd = function (eInput)
	{
		var oTextRange, iLen;
	
		if (eInput.createTextRange)
		{
			//ie6-8
			oTextRange = eInput.createTextRange();
			oTextRange.collapse(false);
			oTextRange.select();
		}
		else if (eInput.setSelectionRange)
		{
			// ff, opera, ie9
			// Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
			iLen = eInput.value.length * 2;
			eInput.setSelectionRange(iLen, iLen);
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
	 *
	 * @param {string} input
	 * @param {number} multiplier
	 * @return {string}
	 */
	Utils.strRepeat = function (input, multiplier)
	{
		return (new Array(multiplier + 1)).join(input);
	};
	
	
	Utils.deferedUpdate = function (element, state, duration, callback) {
		
		if (!element.__interval && !!state)
		{
			element.__state = true;
			callback(element, true);
	
			element.__interval = window.setInterval(function () {
				if (!element.__state) {
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
	
	Utils.draggebleMessages = function ()
	{
		return $('<div class="draggeble draggebleMessages"><span class="count-text"></span></div>').appendTo('#pSevenHidden');
	};
	
	Utils.draggebleContacts = function ()
	{
		return $('<div class="draggeble draggebleContacts"><span class="count-text"></span></div>').appendTo('#pSevenHidden');
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
			helper = oUi.helper.clone().appendTo('#pSevenHidden'),
			target = $(oEvent.target).find('.text') ,
			position = target.offset()
		;
		
		helper.animate({
			'left': position.left + 'px',
			'top': position.top + 'px',
			'font-size': '0px',
			'opacity': 0
		}, 800, 'easeOutQuint', function() {
			 $(this).remove();
		});
	};
	
	Utils.inFocus = function ()
	{
		var mTagName = document && document.activeElement ? document.activeElement.tagName : null;
		return 'INPUT' === mTagName || 'TEXTAREA' === mTagName || 'IFRAME' === mTagName;
	};
	
	/**
	 * http://docs.translatehouse.org/projects/localization-guide/en/latest/l10n/pluralforms.html?id=l10n/pluralforms
	 * 
	 * @param {string} sLang
	 * @param {number} nNumber
	 * @returns {number}
	 */
	Utils.getPlural = function (sLang, nNumber)
	{
		var nResult = 0;
		nNumber = Utils.pInt(nNumber);
	
		switch (sLang)
		{
			case 'Arabic':
				nResult = (nNumber === 0 ? 0 : nNumber === 1 ? 1 : nNumber === 2 ? 2 : nNumber % 100 >= 3 && nNumber % 100 <= 10 ? 3 : nNumber % 100 >= 11 ? 4 : 5);
				break;
			case 'Bulgarian':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Chinese-Simplified':
				nResult = 0;
				break;
			case 'Chinese-Traditional':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Czech':
				nResult = (nNumber === 1) ? 0 : (nNumber >= 2 && nNumber <= 4) ? 1 : 2;
				break;
			case 'Danish':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Dutch':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'English':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Estonian':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Finish':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'French':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'German':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Greek':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Hebrew':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Hungarian':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Italian':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Japanese':
				nResult = 0;
				break;
			case 'Korean':
				nResult = 0;
				break;
			case 'Latvian':
				nResult = (nNumber % 10 === 1 && nNumber % 100 !== 11 ? 0 : nNumber !== 0 ? 1 : 2);
				break;
			case 'Lithuanian':
				nResult = (nNumber % 10 === 1 && nNumber % 100 !== 11 ? 0 : nNumber % 10 >= 2 && (nNumber % 100 < 10 || nNumber % 100 >= 20) ? 1 : 2);
				break;
			case 'Norwegian':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Persian':
				nResult = 0;
				break;
			case 'Polish':
				nResult = (nNumber === 1 ? 0 : nNumber % 10 >= 2 && nNumber % 10 <= 4 && (nNumber % 100 < 10 || nNumber % 100 >= 20) ? 1 : 2);
				break;
			case 'Portuguese-Brazil':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Romanian':
				nResult = (nNumber === 1 ? 0 : (nNumber === 0 || (nNumber % 100 > 0 && nNumber % 100 < 20)) ? 1 : 2);
				break;
			case 'Russian':
				nResult = (nNumber % 10 === 1 && nNumber % 100 !== 11 ? 0 : nNumber % 10 >= 2 && nNumber % 10 <= 4 && (nNumber % 100 < 10 || nNumber % 100 >= 20) ? 1 : 2);
				break;
			case 'Serbian':
				nResult = (nNumber % 10 === 1 && nNumber % 100 !== 11 ? 0 : nNumber % 10 >= 2 && nNumber % 10 <= 4 && (nNumber % 100 < 10 || nNumber % 100 >= 20) ? 1 : 2);
				break;
			case 'Spanish':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Swedish':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Thai':
				nResult = 0;
				break;
			case 'Turkish':
				nResult = (nNumber === 1 ? 0 : 1);
				break;
			case 'Ukrainian':
				nResult = (nNumber % 10 === 1 && nNumber % 100 !== 11 ? 0 : nNumber % 10 >= 2 && nNumber % 10 <= 4 && (nNumber % 100 < 10 || nNumber % 100 >= 20) ? 1 : 2);
				break;
			default:
				nResult = 0;
				break;
		}
	
		return nResult;
	};
	
	/**
	 * @param {Function} list (knockout)
	 * @param {Function=} fSelectCallback
	 * @param {Function=} fDeleteCallback
	 * @param {Function=} fDblClickCallback
	 * @constructor
	 */
	function CSelector(list, fSelectCallback, fDeleteCallback, fDblClickCallback)
	{
		this.fSelectCallback = fSelectCallback || function() {};
		this.fDeleteCallback = fDeleteCallback || function() {};
		this.fDblClickCallback = fDblClickCallback || function() {};
	
		this.useKeyboardKeys = ko.observable(false);
	
		this.list = list;
		this.oLast = null;
		this.oListScope = null;
		this.oScrollScope = null;
	
		this.iTimer = 0;
	
		this.sActionSelector = '';
		this.sSelectabelSelector = '';
		this.sCheckboxSelector = '';
	
		var self = this;
	
		// reading returns a list of checked items.
		// recording (bool) puts all checked, or unchecked.
		this.listChecked = ko.computed({
			'read': function () {
				return _.filter(this.list(), function (oItem) {
					return oItem.checked();
				});
			},
			'write': function (bValue) {
				bValue = !!bValue;
				_.each(this.list(), function (oItem) {
					oItem.checked(bValue);
				});
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
					self.scrollToSelected();
					this.oLast = oItemToSelect;
				}
			},
			'owner': this
		});
	
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
	
		this.onKeydown = _.bind(this.onKeydown, this);
	}
	
	CSelector.prototype.iTimer = 0;
	
	/**
	 * @return {boolean}
	 */
	CSelector.prototype.inFocus = function ()
	{
		var mTagName = document && document.activeElement ? document.activeElement.tagName : null;
		return 'INPUT' === mTagName || 'TEXTAREA' === mTagName || 'IFRAME' === mTagName;
	};
	
	/**
	 * @param {string} sActionSelector css-selector for the active for pressing regions of the list
	 * @param {string} sSelectabelSelector css-selector to the item that was selected
	 * @param {string} sCheckboxSelector css-selector to the element that checkbox in the list
	 * @param {*} oListScope
	 * @param {*} oScrollScope
	 */
	CSelector.prototype.initOnApplyBindings = function (sActionSelector, sSelectabelSelector, sCheckboxSelector, oListScope, oScrollScope)
	{
		$(document).on('keydown', this.onKeydown);
	
		this.oListScope = oListScope;
		this.oScrollScope = oScrollScope;
		this.sActionSelector = sActionSelector;
		this.sSelectabelSelector = sSelectabelSelector;
		this.sCheckboxSelector = sCheckboxSelector;
	
		var
			self = this,
	
			fEventClickFunction = function (oItem, oEvent) {
	
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
					if (null !== oItem && null !== self.oLast && oItem !== self.oLast)
					{
						aList = self.list();
						bChecked = oItem.checked();
	
						for (iIndex = 0, iLength = aList.length; iIndex < iLength; iIndex++)
						{
							oListItem = aList[iIndex];
	
							bChangeRange = false;
							if (oListItem === self.oLast || oListItem === oItem)
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
	
		$(this.oListScope).on('dblclick', sActionSelector, function () {
			var oItem = ko.dataFor(this);
			if (oItem)
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
				oItem = ko.dataFor(this)
			;
	
			if (oItem && oEvent)
			{
				if (oEvent.shiftKey)
				{
					bClick = false;
					if (null === self.oLast)
					{
						self.oLast = oItem;
					}
	
					oItem.checked(!oItem.checked());
					fEventClickFunction(oItem, oEvent);
				}
				else if (oEvent.ctrlKey)
				{
					bClick = false;
					self.oLast = oItem;
	
					oSelected = self.itemSelected();
					if (oSelected && !oSelected.checked())
					{
						oSelected.checked(true);
					}
					oItem.checked(!oItem.checked());
				}
	
				if (bClick)
				{
					self.onSelect(oItem);
				}
			}
		});
	
		$(this.oListScope).on('click', sCheckboxSelector, function (oEvent) {
	
			var oItem = ko.dataFor(this);
			if (oItem && oEvent)
			{
				if (oEvent.shiftKey)
				{
					if (null === self.oLast)
					{
						self.oLast = oItem;
					}
	
					fEventClickFunction(oItem, oEvent);
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
	 */
	CSelector.prototype.getResultSelection = function (oSelected, iEventKeyCode)
	{
		var
			bStop = false,
			bNext = false,
			oResult = null,
			aList = []
		;
	
		if (!oSelected && -1 < Utils.inArray(iEventKeyCode, [Enums.Key.Up, Enums.Key.Down, Enums.Key.PageUp,
			Enums.Key.PageDown, Enums.Key.Home, Enums.Key.End]))
		{
			aList = this.list();
			if (aList && 0 < aList.length)
			{
				if (-1 < Utils.inArray(iEventKeyCode, [Enums.Key.Down, Enums.Key.PageUp, Enums.Key.Home]))
				{
					oResult = aList[0];
				}
				else if (-1 < Utils.inArray(iEventKeyCode, [Enums.Key.Up, Enums.Key.PageDown, Enums.Key.End]))
				{
					oResult = aList[aList.length - 1];
				}
			}
		}
		else if (oSelected)
		{
			_.each(this.list(), function (oItem) {
				if (!bStop)
				{
					switch (iEventKeyCode) {
						case Enums.Key.Up:
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
						case Enums.Key.Down:
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
	
		return oResult;
	};
	
	/**
	 * @param {Object} oResult
	 * @param {Object} oSelected
	 * @param {number} iEventKeyCode
	 */
	CSelector.prototype.shiftClickResult = function (oResult, oSelected, iEventKeyCode)
	{
		if (oSelected && (Enums.Key.Up === iEventKeyCode || Enums.Key.Down === iEventKeyCode))
		{
			oSelected.checked(!oSelected.checked());
		}
		else if (oSelected && -1 < Utils.inArray(iEventKeyCode, [Enums.Key.PageUp, Enums.Key.PageDown, Enums.Key.Home, Enums.Key.End]))
		{
			var
				bInRange = false,
				bSelected = !oSelected.checked()
			;
	
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
		}
		else if (oSelected)
		{
			if (bShiftKey && (-1 < Utils.inArray(iEventKeyCode, [Enums.Key.Up, Enums.Key.Down, Enums.Key.PageUp,
				Enums.Key.PageDown, Enums.Key.Home, Enums.Key.End])))
			{
				oSelected.checked(!oSelected.checked());
			}
		}
	};
	
	/**
	 * @param {Object} oEvent
	 */
	CSelector.prototype.onKeydown = function (oEvent)
	{
		var
			bResult = true,
			iCode = 0
		;
	
		if (this.useKeyboardKeys() && oEvent && !this.inFocus())
		{
			iCode = oEvent.keyCode;
			if (Enums.Key.Up === iCode || Enums.Key.Down === iCode ||
				Enums.Key.PageUp === iCode || Enums.Key.PageDown === iCode ||
				Enums.Key.Home === iCode || Enums.Key.End === iCode
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
			else if (oEvent.ctrlKey && Enums.Key.a === iCode)
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
		this.itemSelected(null);
		this.listChecked(false);
	};
	
	/**
	 * @param {Object} oItem
	 */
	CSelector.prototype.onSelect = function (oItem)
	{
		this.itemSelected(oItem);
		this.fSelectCallback.call(this, oItem);
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
	
	CSelector.prototype.scrollToSelected = function ()
	{
		if (!this.oListScope || !this.oScrollScope)
		{
			return false;
		}
	
		var
			iOffset = 20,
			oSelected = $(this.sSelectabelSelector, this.oListScope),
			oPos = oSelected.position(),
			iVisibleHeight = this.oListScope.height(),
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
	
	(function ($) {
	 
	 $.fn.splitter = function(args){
		args = args || {};
	
		return this.each(function() {
			var
				startSplitMouse = function (e) {
					bar.addClass(opts['activeClass']);
					$panes[0]._posSplit = $panes[0][0][opts['pxSplit']] - e[opts['eventPos']];
					$('body')
						.attr({'unselectable': "on"})
						.addClass('unselectable');
	
					$(document)
						.bind('mousemove', doSplitMouse)
						.bind('mouseup', endSplitMouse);
				},
				doSplitMouse = function (e) {
					var newPos = $panes[0]._posSplit + e[opts['eventPos']];
					resplit(newPos);
				},
	
				splitName = args.name,
				endSplitMouse = function endSplitMouse(e) {
					bar.removeClass(opts['activeClass']);
	
					$('body')
						.attr({'unselectable': 'off'})
						.removeClass('unselectable');
	
					// Store 'width' data
					if (splitName) {
						App.Storage.setData(splitName + 'ResizerWidth', $panes[0].width());
					}
	
					$(document)
						.unbind('mousemove', doSplitMouse)
						.unbind('mouseup', endSplitMouse);
				},
				resplit = function (newPos) {
					// Constrain new splitbar position to fit pane size limits
					newPos = window.Math.max(
						$panes[0]._min, splitter._DA - $panes[1]._max, 
						window.Math.min(newPos, $panes[0]._max, splitter._DA - $panes[1]._min)
					);
	
					$panes[0].css(opts['split'], newPos);
					$panes[1].css(opts['split'], splitter._DA - newPos);
					
					if (!($.browser.msie && $.browser.version < 9))
					{
						panes.trigger('resize');
					}
				},
				dimSum = function (jq, dims) {
					// Opera returns -1 for missing min/max width, turn into 0
					var sum = 0;
					for (var i=1; i < arguments.length; i++)
					{
						sum += window.Math.max(window.parseInt(jq.css(arguments[i]), 10) || 0, 0);
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
						'keyLeft': 39, 'keyRight': 37, 'cursor': "e-resize",
						'outlineClass': "voutline",
						'type': 'v', 'eventPos': "pageX", 'origin': "left",
						'split': "width",  'pxSplit': "offsetWidth",  'side1': "Left", 'side2': "Right",
						'fixed': "height", 'pxFixed': "offsetHeight", 'side3': "Top",  'side4': "Bottom"
					},
					h: {					// Horizontal splitters:
						'keyTop': 40, 'keyBottom': 38,  'cursor': "n-resize",
						'outlineClass': "houtline",
						'type': 'h', eventPos: "pageY", origin: "top",
						'split': "height", 'pxSplit': "offsetHeight", 'side1': "Top",  'side2': "Bottom",
						'fixed': "width",  'pxFixed': "offsetWidth",  'side3': "Left", 'side4': "Right"
					}
				}[vh], args),
				
				splitter = $(this).css({'position': 'relative'}),
				
				panes = $(">*:not(css3pie)", splitter[0]),
				$panes = [],
				bar = $('.resize_handler', panes[0])
					.attr({'unselectable': 'on'})
					.bind('mousedown', startSplitMouse)
			;
			
			$.each(panes, function(){
				$panes.push($(this));
			});
	
			$panes[0]._pane = opts['side1'];
			$panes[1]._pane = opts['side2'];
			$.each($panes, function(){
				this._min = opts['min' + this._pane] || dimSum(this, 'min-' + opts.split);
				this._max = opts['max' + this._pane] || dimSum(this, 'max-' + opts.split) || 9999;
				this._init = opts['size' + this._pane] === true ?
					window.parseInt($.css(this[0], opts['split']), 10) : opts['size' + this._pane];
			});
	
			// Determine initial position, get from cookie if specified
			var initPos = 0;
			if (splitName) {
				initPos = App.Storage.getData(splitName) || $panes[0]._init;
			} else {
				initPos = $panes[0]._init;
			}
	
			if (!isNaN($panes[1]._init))	// recalc initial B size as an offset from the top or left side
			{
				initPos = splitter[0][opts['pxSplit']] - $panes[1]._init;
			}
			if (isNaN(initPos))
			{
				initPos = splitter[0][opts['pxSplit']];
				initPos = window.Math.round(initPos / panes.length);
			}
	
			// Resize event propagation and splitter sizing
			if (opts['resizeToWidth'] && !($.browser.msie && $.browser.version < 9))
			{
				$(window).bind('resize', function(e) {
					if (e.target !== this)
					{
						return;
					}
					splitter.trigger('resize'); 
				});
			}
			
			splitter.bind('resize', function(e, size){
				// Custom events bubble in jQuery 1.3; don't get into a Yo Dawg
				if (e.target !== this)
				{
					return;
				}
	
				// Determine new width/height of splitter container
				splitter._DA = splitter[0][opts['pxSplit']];
				
				// Bail if splitter isn't visible or content isn't there yet
				if (splitter._DA <= 0)
				{
					return;
				}
				
				if (isNaN(size))
				{
					if (!(opts['sizeRight'] || opts['sizeBottom']))
					{
						size = panes[0][opts['pxSplit']];
					}
					else
					 {
						size = splitter._DA - panes[1][opts['pxSplit']];
					}
				}
				
				resplit(size);
				
				// resplit(!isNaN(size) ? size : (!(opts.sizeRight||opts.sizeBottom) ? 
				// panes[0][opts.pxSplit] : splitter._DA - panes[1][opts.pxSplit]));
				
			}).trigger('resize', [initPos]);
		});
	};
	
	})(jQuery);
	
	/**
	 * @constructor
	 */
	function CApi()
	{
		
	}
	
	/**
	 * @param {string} sSearch
	 */
	CApi.prototype.searchMessagesInInbox = function (sSearch)
	{
		App.Cache.searchMessagesInInbox(sSearch);
	};
	
	/**
	 * @param {string} sSearch
	 */
	CApi.prototype.searchMessagesInCurrentFolder = function (sSearch)
	{
		App.Cache.searchMessagesInCurrentFolder(sSearch);
		window.focus();
	};
	
	/**
	 * @param {string} sToAddresses
	 */
	CApi.prototype.openComposeMessage = function (sToAddresses)
	{
		App.Routing.setHash(App.Links.composeWithToField(sToAddresses));
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
	 * @param {number} iDelay
	 */
	CApi.prototype.showReport = function (sReport, iDelay)
	{
		App.Screens.showReport(sReport, iDelay);
	};
	
	/**
	 * @param {string} sError
	 * @param {boolean=} bHtml = false
	 * @param {boolean=} bNotHide = false
	 */
	CApi.prototype.showError = function (sError, bHtml, bNotHide)
	{
		App.Screens.showError(sError, bHtml, bNotHide);
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
	
	CStorage.prototype.getData = function (key)
	{
		return Data.getVar(key);
	};
	
	/**
	 * @constructor
	 */
	function AlertPopup()
	{
		this.title = ko.observable('');
		this.alertDesc = ko.observable('');
		this.okButtonText = ko.observable(Utils.i18n('MAIN/BUTTON_OK'));
	}
	
	/**
	 * @param {string} sDesc
	 */
	AlertPopup.prototype.onShow = function (sDesc)
	{
		this.alertDesc(sDesc);
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
	}
	
	/**
	 * @param {string} sDesc
	 * @param {Function} fConfirmCallback
	 * @param {string=} sTitle = ''
	 */
	ConfirmPopup.prototype.onShow = function (sDesc, fConfirmCallback, sTitle)
	{
		this.title(sTitle || '');
		if (Utils.isFunc(fConfirmCallback))
		{
			this.fConfirmCallback = fConfirmCallback;
			this.confirmDesc(sDesc);
		}
		else
		{
			window.alert('Second parameter is not a function!');
		}
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
		if (this.fConfirmCallback)
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
	function AccountCreatePopup()
	{
		this.fCallback = null;
		this.editedAccountId = AppData.Accounts.editedId;
		
		this.friendlyName = ko.observable('');
		this.email = ko.observable('');
		this.incomingMailLogin = ko.observable('');
		this.incLoginFocused = ko.observable(false);
		this.incLoginFocused.subscribe(function () {
			if (this.incLoginFocused() && this.incomingMailLogin() === '')
			{
				this.incomingMailLogin(this.email());
			}
		}, this);
		this.incomingMailPassword = ko.observable('');
		this.incomingMailPort = ko.observable(143);
		this.incomingMailServer = ko.observable('');
		this.outgoingMailLogin = ko.observable('');
		this.outgoingMailPassword = ko.observable('');
		this.outgoingMailPort = ko.observable(25);
		this.outgoingMailServer = ko.observable('');
		this.outServerFocused = ko.observable(false);
		this.outServerFocused.subscribe(function () {
			if (this.outServerFocused() && this.outgoingMailServer() === '')
			{
				this.outgoingMailServer(this.incomingMailServer());
			}
		}, this);
		this.useSmtpAuthentication = ko.observable(true);
		this.friendlyNameFocus = ko.observable(false);
	}
	
	/**
	 */
	AccountCreatePopup.prototype.onShow = function ()
	{
		this.friendlyNameFocus(true);
		this.init();
	};
	
	/**
	 * @return {string}
	 */
	AccountCreatePopup.prototype.popupTemplate = function ()
	{
		return 'Popups_AccountCreatePopupViewModel';
	};
	
	AccountCreatePopup.prototype.init = function ()
	{
		this.friendlyName('');
		this.email('');
		this.incomingMailLogin('');
		this.incLoginFocused(false);
		this.incomingMailPassword('');
		this.incomingMailPort(143);
		this.incomingMailServer('');
		this.outgoingMailLogin('');
		this.outgoingMailPassword('');
		this.outgoingMailPort(25);
		this.outgoingMailServer('');
		this.outServerFocused(false);
		this.useSmtpAuthentication(true);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	AccountCreatePopup.prototype.onResponseAddAccount = function (oData, oParameters)
	{
		var
			mResult = oData ? oData.Result : false,
			iErrorCode = oData ? oData.ErrorCode : 0
		;
		
		if (mResult === false)
		{
			switch (iErrorCode)
			{
				case Enums.Errors.MailServerError:
					App.Api.showError(Utils.i18n('WARNING/CANT_CONNECT_TO_SERVER'));
					break;
				case Enums.Errors.AuthError:
					App.Api.showError(Utils.i18n('WARNING/LOGIN_PASS_INCORRECT'));
					break;
				default:
					App.Api.showError(Utils.i18n('WARNING/CREATING_ACCOUNT_ERROR'));
					break;
			}
		}
		else
		{
			var
				oAccount = new CAccountModel(),
				iAccountId = parseInt(oData.Result, 10)
			;
			
			oAccount.init(iAccountId, oParameters.Email, oParameters.FriendlyName);
			oAccount.updateExtended(oParameters);
	
			AppData.Accounts.addAccount(oAccount);
			AppData.Accounts.changeEditedAccount(iAccountId);
			this.closeCommand();
			this.init();
		}
	};
	
	/**
	 *
	 */
	AccountCreatePopup.prototype.onSaveClick = function ()
	{
		var
			oParameters = {
				'Action': 'AccountCreate',
				'AccountID': this.editedAccountId(),
				'FriendlyName': this.friendlyName(),
				'Email': this.email(),
				'IncomingMailLogin': this.incomingMailLogin(),
				'IncomingMailPassword': this.incomingMailPassword(),
				'IncomingMailServer': this.incomingMailServer(),
				'IncomingMailPort': parseInt(this.incomingMailPort(), 10),
				'OutgoingMailServer': this.outgoingMailServer(),
				'OutgoingMailLogin': this.outgoingMailLogin(),
				'OutgoingMailPassword': this.outgoingMailPassword(),
				'OutgoingMailPort': parseInt(this.outgoingMailPort(), 10),
				'OutgoingMailAuth': this.useSmtpAuthentication() ? 2 : 0
			}
		;
	
		App.Ajax.send(oParameters, this.onResponseAddAccount, this);
	};
	
	/**
	 *
	 */
	AccountCreatePopup.prototype.onCancelClick = function ()
	{
		this.closeCommand();
	};
	
	
	/**
	 * @constructor
	 */
	function SystemFoldersPopup()
	{
		this.folders = ko.observable(null);
	
		this.options = ko.computed(function () {
			return this.folders() ? this.folders().getOptions(Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_NO_USAGE_ASSIGNED')) : [];
		}, this);
	
		this.sSentFolderOld = '';
		this.sDraftFolderOld = '';
		this.sSpamFolderOld = '';
		this.sTrashFolderOld = '';
		
		this.sentFolderFullName = ko.observable('');
		this.draftsFolderFullName = ko.observable('');
		this.spamFolderFullName = ko.observable('');
		this.trashFolderFullName = ko.observable('');
	
		this.sentFolderFullName.subscribe(function (sValue) {
			if (this.folders())
			{
				this.folders().sentFolderFullName(sValue);
			}
		}, this);
	
		this.draftsFolderFullName.subscribe(function (sValue) {
			if (this.folders())
			{
				this.folders().draftsFolderFullName(sValue);
			}
		}, this);
	
		this.spamFolderFullName.subscribe(function (sValue) {
			if (this.folders())
			{
				this.folders().spamFolderFullName(sValue);
			}
		}, this);
		
		this.trashFolderFullName.subscribe(function (sValue) {
			if (this.folders())
			{
				this.folders().trashFolderFullName(sValue);
			}
		}, this);
	}
	
	SystemFoldersPopup.prototype.onShow = function ()
	{
		var oFolders = App.Cache.editedFolderList();
	
		this.sSentFolderOld = oFolders.sentFolderFullName();
		this.sDraftFolderOld = oFolders.draftsFolderFullName();
		this.sSpamFolderOld = oFolders.spamFolderFullName();
		this.sTrashFolderOld = oFolders.trashFolderFullName();
	
		this.sentFolderFullName(this.sSentFolderOld);
		this.draftsFolderFullName(this.sDraftFolderOld);
		this.spamFolderFullName(this.sSpamFolderOld);
		this.trashFolderFullName(this.sTrashFolderOld);
		
		this.folders(oFolders);
	};
	
	/**
	 * @return {string}
	 */
	SystemFoldersPopup.prototype.popupTemplate = function ()
	{
		return 'Popups_FolderSystemPopupViewModel';
	};
	
	SystemFoldersPopup.prototype.onResponseSetupSystemFolders = function (oData)
	{
		if (oData && oData.Result !== false)
		{
			App.Cache.getFolderList(AppData.Accounts.editedId());
		}
	};
	
	SystemFoldersPopup.prototype.onOKClick = function ()
	{
		var
			oFolders = this.folders(),
			oParameters = {
				'Action': 'SetupSystemFolders',
				'AccountID': AppData.Accounts.editedId(),
				'Sent': oFolders.sentFolderFullName(),
				'Drafts': oFolders.draftsFolderFullName(),
				'Trash': oFolders.trashFolderFullName(),
				'Spam': oFolders.spamFolderFullName()
			}
		;
		
		App.Ajax.send(oParameters, this.onResponseSetupSystemFolders, this);
		
		this.closeCommand();
	};
	
	SystemFoldersPopup.prototype.onCancelClick = function ()
	{
		var oFolders = this.folders();
	
		oFolders.sentFolderFullName(this.sSentFolderOld);
		oFolders.draftsFolderFullName(this.sDraftFolderOld);
		oFolders.spamFolderFullName(this.sSpamFolderOld);
		oFolders.trashFolderFullName(this.sTrashFolderOld);
		
		this.closeCommand();
	};
	
	
	/**
	 * @constructor
	 */
	function FolderCreatePopup()
	{
		this.folders = App.Cache.editedFolderList;
	
		this.options = ko.computed(function(){
			return this.folders().getOptions(Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_NO_PARENT'), true);
		}, this);
	
		this.namespace = ko.computed(function(){
			return this.folders().sNamespaceFolder;
		}, this);
		this.parentFolder = ko.observable('');
		this.folderName = ko.observable('');
		this.folderNameFocus = ko.observable(false);
	}
	
	/**
	 */
	FolderCreatePopup.prototype.onShow = function ()
	{
		this.folderNameFocus(true);
	};
	
	/**
	 * @return {string}
	 */
	FolderCreatePopup.prototype.popupTemplate = function ()
	{
		return 'Popups_FolderCreatePopupViewModel';
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	FolderCreatePopup.prototype.onResponseFolderCreate = function (oData, oParameters)
	{
		if (oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_CANT_CREATE_FOLDER'));
		}
		else
		{
			this.folderName('');
			this.parentFolder('');
			App.Cache.getFolderList(AppData.Accounts.editedId());
			this.closeCommand();
		}
	};
	
	/**
	 *
	 */
	FolderCreatePopup.prototype.onOKClick = function ()
	{
		var
			parentFolder = (this.parentFolder() === '' ? this.namespace() : this.parentFolder()),
			oParameters = {
				'Action': 'FolderCreate',
				'AccountID': AppData.Accounts.editedId(),
				'FolderNameInUtf8': this.folderName(),
				'FolderParentFullNameRaw': parentFolder,
				'Delimiter': this.folders().delimiter()
			}
		;
		App.Ajax.send(oParameters, this.onResponseFolderCreate, this);
	};
	
	/**
	 *
	 */
	FolderCreatePopup.prototype.onCancelClick = function ()
	{
		this.closeCommand();
	};
	
	
	/**
	 * @constructor
	 */
	function ChangePasswordPopup()
	{
		this.fCallback = null;
		this.editedAccountId = AppData.Accounts.editedId;
		
		this.currentPassword = ko.observable('');
		this.newPassword = ko.observable('');
		this.confirmPassword = ko.observable('');
	}
	
	/**
	 */
	ChangePasswordPopup.prototype.onShow = function ()
	{
		this.init();
	};
	
	/**
	 * @return {string}
	 */
	ChangePasswordPopup.prototype.popupTemplate = function ()
	{
		return 'Popups_ChangePasswordPopupViewModel';
	};
	
	ChangePasswordPopup.prototype.init = function ()
	{
		this.currentPassword('');
		this.newPassword('');
		this.confirmPassword('');
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	ChangePasswordPopup.prototype.onResponse = function (oData, oParameters)
	{
		if (oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ACCOUNT_PROPERTIES_NEW_PASSWORD_UPDATE_ERROR'));
		}
		else
		{
			App.Api.showReport(Utils.i18n('SETTINGS/ACCOUNT_PROPERTIES_CHANGE_PASSWORD_SUCCESS'));
			this.closeCommand();
			this.init();
		}
	};
	
	/**
	 *
	 */
	ChangePasswordPopup.prototype.onOKClick = function ()
	{
		var
			oParameters = {
				'Action': 'UpdateAccountPassword',
				'AccountID': this.editedAccountId(),
				'CurrentIncomingMailPassword': this.currentPassword(),
				'NewIncomingMailPassword': this.newPassword()
			}
		;
		if (this.confirmPassword() !== this.newPassword())
		{
			App.Api.showError(Utils.i18n('SETTINGS/ACCOUNT_PROPERTIES_PASSWORDS_DO_NOT_MATCH'));
		}
		else
		{
			App.Ajax.send(oParameters, this.onResponse, this);
		}
	};
	
	/**
	 *
	 */
	ChangePasswordPopup.prototype.onCancelClick = function ()
	{
		this.closeCommand();
	};
	
	
	/**
	 * @constructor
	 */
	function CAppSettingsModel()
	{
		// allows to edit common settings and calendar settings
		this.AllowUsersChangeInterfaceSettings = true;
	
		// allows to delete accounts, allows to change account properties (name and password is always possible to change),
		// allows to manage special folders, allows to add new accounts
		this.AllowUsersChangeEmailSettings = true;
	
		// allows to add new accounts (if AllowUsersChangeEmailSettings === true)
		this.AllowUsersAddNewAccounts = true || this.AllowUsersChangeEmailSettings;
	
		// list of available languages
		this.Languages = [
			{name: 'English', value: 'en'}
		];
	
		// list of available themes
		this.Themes = [
			'Default'
		];
	
		// list of available date formats
		this.DateFormats = [
			'MM/DD/YYYY',
			'DD/MM/YYYY'
		];
		
		this.DefaultLanguage = 'English';
	
		// maximum size of uploading attachment
		this.AttachmentSizeLimit = 10240000;
		
		this.AllowFirstCharacterSearch = false;
		this.AllowIdentities = false;
		
		// activate autosave
		this.AutoSave = true;
		this.AutoSaveIntervalSeconds = 60;
		this.IdleSessionTimeout = 0;
		
		// allows to insert an image to html-text in rich text editor
		this.AllowInsertImage = true;
		this.AllowBodySize = false;
		this.MaxBodySize = 600;
		this.MaxSubjectSize = 255;
	
		// allows to watch the settings MobileSync
		this.EnableMobileSync = true;
		
		this.AllowPrefetch = true;
		this.MaxPrefetchBodiesSize = 30000;
	
		this.DemoWebMail = true;
		this.DemoWebMailLogin = '';
		this.DemoWebMailPassword = '';
		this.LoginDescription = '';
		this.GoogleAnalyticsAccount = '';
		this.ShowQuotaBar = false;
	
		this.AllowLanguageOnLogin = false;
		this.FlagsLangSelect = false;
		
		this.CustomLogoutUrl = '';
	}
		
	/**
	 * Parses the application settings from the server.
	 * 
	 * @param {Object} oData
	 */
	CAppSettingsModel.prototype.parse = function (oData)
	{
		this.AllowUsersChangeInterfaceSettings = !!oData.AllowUsersChangeInterfaceSettings;
		this.AllowUsersChangeEmailSettings = !!oData.AllowUsersChangeEmailSettings;
		this.AllowUsersAddNewAccounts = !!oData.AllowUsersAddNewAccounts || this.AllowUsersChangeEmailSettings;
		this.Languages = oData.Languages;
		this.Themes = oData.Themes;
		this.DateFormats = oData.DateFormats;
		this.AttachmentSizeLimit = Utils.pInt(oData.AttachmentSizeLimit);
		this.AllowFirstCharacterSearch = !!oData.AllowFirstCharacterSearch;
		this.AllowIdentities = !!oData.AllowIdentities;
		this.AutoSave = !!oData.AutoSave;
		this.IdleSessionTimeout = Utils.pInt(oData.IdleSessionTimeout);
		this.AllowInsertImage = !!oData.AllowInsertImage;
		this.AllowBodySize = !!oData.AllowBodySize;
		this.MaxBodySize = Utils.pInt(oData.MaxBodySize);
		this.MaxSubjectSize = Utils.pInt(oData.MaxSubjectSize);
		this.EnableMobileSync = !!oData.EnableMobileSync;
		this.AllowPrefetch = !!oData.AllowPrefetch;
		
		this.DemoWebMail = !!oData.DemoWebMail;
		this.DemoWebMailLogin = oData.DemoWebMailLogin;
		this.DemoWebMailPassword = oData.DemoWebMailPassword;
		this.GoogleAnalyticsAccount = oData.GoogleAnalyticsAccount;
		this.ShowQuotaBar = !!oData.ShowQuotaBar;
	
		this.AllowLanguageOnLogin = !!oData.AllowLanguageOnLogin;
		this.FlagsLangSelect = !!oData.FlagsLangSelect;
	
		this.DefaultLanguage = oData.DefaultLanguage;
		this.LoginDescription = oData.LoginDescription;
		
		this.CustomLogoutUrl = oData.CustomLogoutUrl;
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
		this.autoCheckMailInterval = ko.observable(0);
		this.autoCheckMailInterval.subscribe(this.changeAutoCheckMailInterval, this);
		this.DefaultEditor = 1;
		this.Layout = Enums.MailboxLayout.Side;
		this.DefaultTheme = 'Default';
		this.DefaultLanguage = 'English';
		this.DefaultDateFormat = 'MM/DD/YYYY';
		this.defaultTimeFormat = ko.observable(0);
	
		// allows the creation of messages
		this.AllowCompose = true;
	
		this.AllowReply = true;
		this.AllowForward = true;
		this.SaveMail = Enums.SaveMail.Checked;
	
		// allows OutlookSync-settings viewing
		this.OutlookSyncEnable = true;
	
		this.ShowPersonalContacts = true;
		this.ShowGlobalContacts = false;
	
		// allows to go to contacts screen and edit their settings
		this.ShowContacts = this.ShowPersonalContacts || this.ShowGlobalContacts;
	
		this.LastLogin = 0;
		this.IsDemo = false;
	
		// allows to go to calendar screen and edit its settings
		this.AllowCalendar = true;
	
		// calendar settings that can be changed in the settings screen
		this.CalendarShowWeekEnds = false;
		this.CalendarShowWorkDay = false;
		this.CalendarWorkDayStarts = 0;
		this.CalendarWorkDayEnds = 0;
		this.CalendarWeekStartsOn = 0;
		this.CalendarDefaultTab = 1;
		
		this.needToReloadCalendar = ko.observable(false);
	
	//	this.CalendarSyncLogin = '';
	//	this.CalendarDavServerUrl = '';
	//	this.CalendarDavPrincipalUrl = '';
	//	this.CalendarAllowReminders = true;
	
		this.mobileSync = ko.observable(null);
		this.MobileSyncDemoPass = 'demo';
		this.outlookSync = ko.observable(null);
		this.OutlookSyncDemoPass = 'demo';
	}
	
	CUserSettingsModel.prototype.changeAutoCheckMailInterval = function ()
	{
		clearInterval(this.iInterval);
		if (!AppData.SingleMode && this.autoCheckMailInterval() > 0)
		{
			this.iInterval = setInterval(function () {
				App.Cache.executeCheckMail();
			}, this.autoCheckMailInterval() * 60 * 1000);
		}
	};
	
	/**
	 * @return boolean
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
	 * @return boolean
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
	 * @param {Object} oData
	 */
	CUserSettingsModel.prototype.parse = function (oData)
	{
		var
			oCalendar = null
		;
	
		if (oData !== null)
		{
			this.IdUser = Utils.pInt(oData.IdUser);
			this.MailsPerPage = Utils.pInt(oData.MailsPerPage);
			this.ContactsPerPage = Utils.pInt(oData.ContactsPerPage);
			this.autoCheckMailInterval(Utils.pInt(oData.AutoCheckMailInterval));
			this.DefaultEditor = Utils.pInt(oData.DefaultEditor);
			this.Layout = Utils.pInt(oData.Layout);
			this.DefaultTheme = oData.DefaultTheme.toString();
			this.DefaultLanguage = oData.DefaultLanguage.toString();
			this.DefaultDateFormat = oData.DefaultDateFormat.toString();
			this.defaultTimeFormat(Utils.pInt(oData.DefaultTimeFormat));
			this.AllowCompose = !!oData.AllowCompose;
			this.AllowReply = !!oData.AllowReply;
			this.AllowForward = !!oData.AllowForward;
			this.SaveMail = Utils.pInt(oData.SaveMail);
			this.OutlookSyncEnable = !!oData.OutlookSyncEnable;
			this.ShowPersonalContacts = !!oData.ShowPersonalContacts;
			this.ShowGlobalContacts = !!oData.ShowGlobalContacts;
			this.ShowContacts = this.ShowPersonalContacts || this.ShowGlobalContacts;
			this.LastLogin = Utils.pInt(oData.LastLogin);
			this.AllowCalendar = !!oData.AllowCalendar;
			this.IsDemo = !!oData.IsDemo;
	
			oCalendar = oData.Calendar;
			if (typeof oCalendar === 'object')
			{
				this.CalendarShowWeekEnds = !!oCalendar.ShowWeekEnds;
				this.CalendarShowWorkDay = !!oCalendar.ShowWorkDay;
				this.CalendarWorkDayStarts = Utils.pInt(oCalendar.WorkDayStarts);
				this.CalendarWorkDayEnds = Utils.pInt(oCalendar.WorkDayEnds);
				this.CalendarWeekStartsOn = Utils.pInt(oCalendar.WeekStartsOn);
				this.CalendarDefaultTab = Utils.pInt(oCalendar.DefaultTab);
			}
		}
	};
	
	/**
	 * @param {number} iMailsPerPage
	 * @param {number} iContactsPerPage
	 * @param {number} iAutoCheckMailInterval
	 * @param {number} iDefaultEditor
	 * @param {number} iLayout
	 * @param {string} sDefaultTheme
	 * @param {string} sDefaultLanguage
	 * @param {string} sDefaultDateFormat
	 * @param {number} iDefaultTimeFormat
	 */
	CUserSettingsModel.prototype.updateCommonSettings = function (iMailsPerPage, iContactsPerPage,
			iAutoCheckMailInterval, iDefaultEditor, iLayout, sDefaultTheme, sDefaultLanguage,
			sDefaultDateFormat, iDefaultTimeFormat)
	{
		if (this.DefaultTheme !== sDefaultTheme || this.DefaultLanguage !== sDefaultLanguage || 
			this.DefaultDateFormat !== sDefaultDateFormat ||this.defaultTimeFormat() !== iDefaultTimeFormat)
		{
			this.needToReloadCalendar(true);
		}
		
		this.MailsPerPage = iMailsPerPage;
		this.ContactsPerPage = iContactsPerPage;
		this.autoCheckMailInterval(iAutoCheckMailInterval);
		this.DefaultEditor = iDefaultEditor;
		this.Layout = iLayout;
		this.DefaultTheme = sDefaultTheme;
		this.DefaultLanguage = sDefaultLanguage;
		this.DefaultDateFormat = sDefaultDateFormat;
		this.defaultTimeFormat(iDefaultTimeFormat);
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
	};
	
	CUserSettingsModel.prototype.isNeedToReloadCalendar = function ()
	{
		var bNeedToReloadCalendar = this.needToReloadCalendar();
		
		this.needToReloadCalendar(false);
		
		return bNeedToReloadCalendar;
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CUserSettingsModel.prototype.onSyncSettingsResponse = function (oData, oParameters)
	{
		if (oData.Result)
		{
			this.mobileSync(oData.Result.Mobile);
			this.outlookSync(oData.Result.Outlook);
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
	function CAccountModel()
	{
		this.id = ko.observable(0);
		this.email = ko.observable('');
		
		this.extensions = ko.observableArray([]);
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
	}
	
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
	CAccountModel.prototype.onQuotaParamsResponse = function (oData, oParameters)
	{
		if (oData && oData.Result && _.isArray(oData.Result) && 2 === oData.Result.length)
		{
			this.quota(Utils.pInt(oData.Result[1]));
			this.usedSpace(Utils.pInt(oData.Result[0]));
	
			App.Cache.quotaChangeTrigger(!App.Cache.quotaChangeTrigger());
		}
	};
	
	CAccountModel.prototype.updateQuotaParams = function ()
	{
		var
			oParams = {
				'Action': 'Quota',
				'AccountID': this.id()
			}
		;
		
		if (AppData.App && AppData.App.ShowQuotaBar)
		{
			App.Ajax.send(oParams, this.onQuotaParamsResponse, this);
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {number} iDefaultId
	 */
	CAccountModel.prototype.parse = function (oData, iDefaultId)
	{
		var oSignature = new CSignatureModel();
		
		this.init(parseInt(oData.AccountID, 10), oData.Email.toString(), 
			oData.FriendlyName.toString());
		
		oSignature.parse(this.id(), oData.Signature);
		this.signature(oSignature);
		
		this.isCurrent(iDefaultId === this.id());
		this.isEdited(iDefaultId === this.id());
	};
	
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
	
			if (ExtendedData.FriendlyName)
			{
				this.friendlyName(ExtendedData.FriendlyName);
			}
	
			if (ExtendedData.IncomingMailLogin)
			{
				this.incomingMailLogin(ExtendedData.IncomingMailLogin);
			}
			if (ExtendedData.IncomingMailPort)
			{
				this.incomingMailPort(ExtendedData.IncomingMailPort); 
			}		
			if (ExtendedData.IncomingMailServer)
			{
				this.incomingMailServer(ExtendedData.IncomingMailServer);
			}
			if (ExtendedData.IsInternal)
			{
				this.isInternal(ExtendedData.IsInternal);
			}
			if (ExtendedData.IsLinked)
			{
				this.isLinked(ExtendedData.IsLinked);
			}
			if (ExtendedData.IsDefault)
			{
				this.isDefault(ExtendedData.IsDefault);
			}
			if (ExtendedData.OutgoingMailAuth)
			{
				this.outgoingMailAuth(ExtendedData.OutgoingMailAuth);
			}
			if (ExtendedData.OutgoingMailLogin)
			{
				this.outgoingMailLogin(ExtendedData.OutgoingMailLogin);
			}
			if (ExtendedData.OutgoingMailPort)
			{
				this.outgoingMailPort(ExtendedData.OutgoingMailPort);
			}
			if (ExtendedData.OutgoingMailServer)
			{
				this.outgoingMailServer(ExtendedData.OutgoingMailServer);
			}
			if (ExtendedData.Extensions)
			{
				this.extensions(ExtendedData.Extensions); 
			}
		}
	};
	
	CAccountModel.prototype.changeAccount = function()
	{
		AppData.Accounts.changeCurrentAccount(this.id());
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
			// deferred execution to edited account has changed a bit later and did not make a second request 
			// of the folder list of the same account.
			_.defer(_.bind(function () {
				this.editedId(value);
			}, this));
		}, this);
	
		this.Collection = ko.observableArray([]);
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
			//TODO: clear history ?
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
			bHasDefault = false
		;
	
		if (_.isArray(aAccounts))
		{
			this.Collection(_.map(aAccounts, function (oRawAccount)
			{
				oAccount = new CAccountModel();
				oAccount.parse(oRawAccount, iDefaultId);
				if (oAccount.id() === iDefaultId)
				{
					bHasDefault = true;
				}
				return oAccount;
			}));
		}
	
		if (!bHasDefault && this.Collection.length > 0)
		{
			oAccount = this.Collection()[0];
			iDefaultId = oAccount.id();
			bHasDefault = true;
		}
	
		if (bHasDefault)
		{
			this.defaultId(iDefaultId);
			this.currentId(iDefaultId);
			this.editedId(iDefaultId);
		}
	};
	
	/**
	 * @param {number} iId
	 * 
	 * @return Object
	 */
	CAccountListModel.prototype.getAccount = function (iId)
	{
		var oAccount = _.find(this.Collection(), function (oAcct) {
			return oAcct.id() === iId;
		}, this);
	
		return oAccount;
	};
	
	/**
	 * @return Object
	 */
	CAccountListModel.prototype.getCurrent = function ()
	{
		var oAccount = _.find(this.Collection(), function (oAcct) {
			return oAcct.id() === this.currentId();
		}, this);
	
		return oAccount;
	};
	
	/**
	 * @return string
	 */
	CAccountListModel.prototype.getEmail = function ()
	{
		var
			sEmail = '',
			oAccount = AppData.Accounts.getCurrent()
		;
		
		if (oAccount)
		{
			sEmail = oAccount.email();
		}
		
		return sEmail;
	};
	
	/**
	 * @return Object
	 */
	CAccountListModel.prototype.getEdited = function ()
	{
		var oAccount = _.find(this.Collection(), function (oAcct) {
			return oAcct.id() === this.editedId();
		}, this);
	
		return oAccount;
	};
	
	/**
	 * @param {Object} oAccount
	 */
	CAccountListModel.prototype.addAccount = function (oAccount)
	{
		this.Collection.push(oAccount);
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
		
		this.Collection.remove(function (oAcct){return oAcct.id() === iId;});
	};
	
	/**
	 * @param {number} iId
	 */
	CAccountListModel.prototype.hasAccountWithId = function (iId)
	{
		var oAccount = _.find(this.Collection(), function (oAcct) {
			return oAcct.id() === iId;
		}, this);
	
		return !!oAccount;
	};
	
	/**
	 * @constructor
	 */
	function CAddressModel()
	{
		this.sName = '';
		this.sEmail = '';
	}
	
	/**
	 * @param {Object} oData
	 */
	CAddressModel.prototype.parse = function (oData)
	{
		if (oData !== null)
		{
			this.sName = Utils.pExport(oData, 'DisplayName', this.sName);
			if (typeof this.sName !== 'string')
			{
				this.sName = '';
			}
			this.sEmail = Utils.pExport(oData, 'Email', this.sEmail);
			if (typeof this.sEmail !== 'string')
			{
				this.sEmail = '';
			}
		}
	};
	
	/**
	 * @return string
	 */
	CAddressModel.prototype.getEmail = function ()
	{
		return this.sEmail;
	};
	
	/**
	 * @return string
	 */
	CAddressModel.prototype.getName = function ()
	{
		return this.sName;
	};
	
	/**
	 * @return string
	 */
	CAddressModel.prototype.getDisplay = function ()
	{
		return (this.sName.length > 0) ? this.sName : this.sEmail;
	};
	
	/**
	 * @return string
	 */
	CAddressModel.prototype.getFull = function ()
	{
		var sFull = '';
		if (this.sEmail.length > 0)
		{
			if (this.sName.length > 0)
			{
				sFull = '"' + this.sName + '" <' + this.sEmail + '>';
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
		return sFull;
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
	 * @return string
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
	 * @return string
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
	 * @return string
	 */
	CAddressListModel.prototype.getDisplay = function ()
	{
		var aAddresses = _.map(this.aCollection, function (oAddress) {
			return oAddress.getDisplay();
		});
		
		return aAddresses.join(', ');
	};
	
	/**
	 * @return string
	 */
	CAddressListModel.prototype.getFull = function ()
	{
		var aAddresses = _.map(this.aCollection, function (oAddress) {
			return oAddress.getFull();
		});
		
		return aAddresses.join(', ');
	};
	
	
	/**
	 * @constructor
	 */
	function CDateModel()
	{
		this.oMoment = null;
	}
	
	/**
	 * @param {number} iTimeStampInUTC
	 */
	CDateModel.prototype.parse = function (iTimeStampInUTC)
	{
		this.oMoment = moment.unix(iTimeStampInUTC);
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
	 * @param {string=} sFolderName = ''
	 * @param {string=} sMessageUid = ''
	 * 
	 * @constructor
	 */
	function CAttachmentModel(sFolderName, sMessageUid)
	{
		this.folderName = ko.observable(sFolderName || '');
		this.messageUid = ko.observable(sMessageUid || '');
	
		this.tempName = ko.observable('');
		this.fileName = ko.observable('');
		this.extension = ko.computed(function () {
			var iDotPos = this.fileName().lastIndexOf('.');
			return this.fileName().substr(iDotPos + 1);
		}, this);
		this.mimePartIndex = ko.observable('');
		this.type = ko.observable('');
		this.size = ko.observable(0);
		this.friendlySize = ko.computed(function () {
			return Utils.friendlySize(this.size());
		}, this);
	
		this.downloadTitle = ko.computed(function () {
			return Utils.i18n('MESSAGE/ATTACHMENT_CLICK_TO_DOWNLOAD', {
				'FILENAME': this.fileName(),
				'SIZE': this.friendlySize()
			});
		}, this);
	
		this.cid = ko.observable('');
		this.inline = ko.observable(false);
		this.linked = ko.observable(false);
		this.hash = ko.observable('');
		this.accountId = ko.observable(0);
		this.download = ko.computed(function () {
			return Utils.getDownloadLinkByHash(this.accountId(), this.hash());
		}, this);
		this.viewLink = ko.computed(function () {
			return Utils.getViewLinkByHash(this.accountId(), this.hash());
		}, this);
	
		this.uploadUid = ko.observable('');
		this.uploaded = ko.observable(false);
		this.uploadError = ko.observable(false);
		this.isMessageType = ko.computed(function () {
			return (this.type() === 'message/rfc822' && this.mimePartIndex() !== '');
		}, this);
		this.messagePart = ko.observable(null);
		this.visibleViewLink = ko.computed(function () {
			var
				aViewTypes = ['image/jpeg', 'image/png', 'image/gif', 'text/plain',
					'text/css', 'application/x-httpd-php', 'application/javascript'],
				bAllowedType = (-1 !== $.inArray(this.type(), aViewTypes))
			;
			return this.uploaded() && !this.uploadError() && (bAllowedType || this.isMessageType());
		}, this);
		this.visibleSpinner = ko.observable(false);
		this.statusText = ko.observable('');
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
	}
	
	/**
	 * Parses attachment data from server.
	 *
	 * @param {AjaxAttachmenResponse} oData
	 * @param {number} iAccountId
	 */
	CAttachmentModel.prototype.parse = function (oData, iAccountId)
	{
		if (oData['@Object'] === 'Object/CApiMailAttachment')
		{
			if (oData.FileName)
			{
				this.tempName(oData.FileName.toString());
				this.fileName(oData.FileName.toString());
			}
			this.mimePartIndex(oData.MimePartIndex.toString());
			this.type(oData.MimeType.toString());
			this.size(parseInt(oData.EstimatedSize, 10));
	
			this.cid(oData.CID.toString());
			this.inline(!!oData.IsInline);
			this.linked(!!oData.IsLinked);
			this.hash(oData.Hash);
			this.accountId(iAccountId);
	
			this.uploadUid(this.hash());
			this.uploaded(true);
		}
	};
	
	/**
	 * Fills attachment data for upload.
	 *
	 * @param {string} sUid
	 * @param {Object} oFileData
	 */
	CAttachmentModel.prototype.onUploadSelect = function (sUid, oFileData)
	{
		this.fileName(oFileData['FileName']);
		this.type(oFileData['Type']);
		this.size(Utils.pInt(oFileData['Size']));
	
		this.uploadUid(sUid);
		this.uploaded(false);
		this.visibleSpinner(false);
		this.statusText('');
		this.progressPercent(0);
		this.visibleProgress(false);
	};
	
	/**
	 * Starts spinner and progress.
	 */
	CAttachmentModel.prototype.onUploadStart = function ()
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
	CAttachmentModel.prototype.onUploadProgress = function (iUploadedSize, iTotalSize)
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
	 * @param {string} sUid
	 * @param {boolean} bResponseReceived
	 * @param {{Error:string,Attachment:AjaxUploadAttachmenResponse}} oResult
	 * @param {number} iAccountId
	 */
	CAttachmentModel.prototype.onUploadComplete = function (sUid, bResponseReceived, oResult, iAccountId)
	{
		var
			bError = !bResponseReceived || !oResult || oResult.Error,
			sError = (oResult && oResult.Error === 'size') ?
				Utils.i18n('COMPOSE/UPLOAD_ERROR_SIZE') :
				Utils.i18n('COMPOSE/UPLOAD_ERROR_UNKNOWN')
		;
	
		if (!bError && !iAccountId)
		{
			bError = true;
		}
	
		this.visibleSpinner(false);
		this.progressPercent(0);
		this.visibleProgress(false);
	
		if (bError)
		{
			this.uploaded(true);
			this.uploadError(true);
			this.statusText(sError);
		}
		else
		{
			this.cid(sUid);
			this.tempName(oResult.Attachment.TempName);
			this.uploadError(false);
			this.uploaded(true);
			this.statusText(Utils.i18n('COMPOSE/UPLOAD_COMPLETE'));
			this.type(oResult.Attachment.MimeType);
			this.hash(oResult.Attachment.Hash);
			this.accountId(iAccountId);
			setTimeout((function (self) {
				return function () {
					self.statusText('');
				};
			})(this), 3000);
		}
	};
	
	/**
	 * @param {AjaxDefaultResponse} oData
	 * @param {Object=} oParameters
	 */
	CAttachmentModel.prototype.onMessageResponse = function (oData, oParameters)
	{
		var
			oResult = oData.Result,
			oMessage = new CMessageModel()
		;
		
		if (oResult && this.oNewWindow)
		{
			oMessage.parse(oResult, oData.AccountID);
			this.messagePart(oMessage);
			this.messagePart().viewMessage(this.oNewWindow);
			this.oNewWindow = undefined;
		}
	};
	
	/**
	 * Starts viewing attachment on click.
	 */
	CAttachmentModel.prototype.viewAttachment = function ()
	{
		var
			oWin = null,
			sLoadingText = '<div style="margin: 30px; text-align: center; font: normal 14px Tahoma;">' + 
				Utils.i18n('MAIN/LOADING') + '</div>',
			sUrl = Utils.getAppPath() + this.viewLink(),
			sParam = 'location=no,menubar=no,status=no,titlebar=no,toolbar=no,resizable=yes,scrollbars=yes'
		;
		
		if (this.isMessageType())
		{
			oWin = Utils.WindowOpener.open('', this.fileName());
			if (oWin)
			{
				if (this.messagePart())
				{
					this.messagePart().viewMessage(oWin);
				}
				else
				{
					var
						oParameters = {
							'Action': 'Message',
							'Folder': this.folderName(),
							'Uid': this.messageUid(),
							'Rfc822MimeIndex': this.mimePartIndex()
						}
					;
	
					$(oWin.document.body).html(sLoadingText);
					this.oNewWindow = oWin;
					App.Ajax.send(oParameters, this.onMessageResponse, this);
				}
				oWin.focus();
			}
		}
		else if (this.visibleViewLink() && this.viewLink().length > 0 && this.viewLink() !== '#')
		{
			sUrl = Utils.getAppPath() + this.viewLink();
			sParam = 'location=no,menubar=no,status=no,titlebar=no,toolbar=no,resizable=yes,scrollbars=yes';
			oWin = window.open(sUrl, sUrl, sParam + this.getNewWindowSizeParameters());
	
			if (oWin)
			{
				oWin.focus();
			}
		}
	};
	
	/**
	 * Starts downloading attachment on click.
	 */
	CAttachmentModel.prototype.downloadAttachment = function ()
	{
		if (this.download().length > 0 && this.download() !== '#')
		{
			App.downloadByUrl(this.download());
		}
	};
	
	/**
	 * Prepares size parameters for the new window.
	 */
	CAttachmentModel.prototype.getNewWindowSizeParameters = function ()
	{
		var
			iRatio = 0.8,
	
			iScreenWidth = window.screen.width,
			iWidth = Math.ceil(iScreenWidth * iRatio),
			iLeft = Math.ceil((iScreenWidth - iWidth) / 2),
	
			iScreenHeight = window.screen.height,
			iHeight = Math.ceil(iScreenHeight * iRatio),
			iTop = Math.ceil((iScreenHeight - iHeight) / 2)
		;
	
		return ',width=' + iWidth + ',height=' + iHeight + ',top=' + iTop + ',left=' + iLeft;
	};
	
	
	/**
	 * @constructor
	 */
	function CFolderModel()
	{
		this.iAccountId = 0;
	
		this.account = ko.computed(function () {
			return AppData.Accounts.getAccount(this.iAccountId);
		}, this);
		
		this.level = ko.observable(0);
		this.name = ko.observable('');
		this.nameForEdit = ko.observable('');
		this.fullName = ko.observable('');
		this.uidNext = ko.observable('');
		this.hash = ko.observable('');
		this.routingHash = ko.observable('');
		this.delimiter = ko.observable('');
		this.type = ko.observable(Enums.FolderTypes.User);
		this.showUnseenMessages = ko.computed(function () {
			return (this.type() !== Enums.FolderTypes.Drafts);
		}, this);
	
		this.messageCount = ko.observable(0);
		this.messageCount.subscribe(function () {
			var oUidList = this.getUidList('');
			oUidList.searchCount(this.messageCount());
		}, this);
		this.unseenMessageCount = ko.observable(0);
		this.messageCountToShow = ko.computed(function () {
			return (this.showUnseenMessages()) ? this.unseenMessageCount() : this.messageCount();
		}, this);
		this.enableEmptyFolder = ko.computed(function () {
			return (this.messageCount() > 0 &&
				(this.type() === Enums.FolderTypes.Spam || this.type() === Enums.FolderTypes.Trash));
		}, this);
		
		this.hasExtendedInfo = ko.observable(false);
	
		this.selectable = ko.observable(true);
		this.subscribed = ko.observable(true);
		this.existen = ko.observable(true);
		this.subfolders = ko.observableArray([]);
		this.isNamespace = ko.observable(false);
		
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
					result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_INBOX');
					break;
				case Enums.FolderTypes.Sent:
					result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_SENT');
					break;
				case Enums.FolderTypes.Drafts:
					result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_DRAFTS');
					break;
				case Enums.FolderTypes.Trash:
					result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_TRASH');
					break;
				case Enums.FolderTypes.Spam:
					result = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_SPAM');
					break;
			}
			
		    return result;
		}, this);
		
		this.aRequestedUids = [];
		this.requestedLists = [];
		
		this.hasChanges = ko.observable(false);
		this.hasChanges.subscribe(function () {
			this.requestedLists = [];
		}, this);
	}
	
	/**
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
		
		oUidList = this.getUidList('');
		oUidList.searchCount(0);
	};
	
	CFolderModel.prototype.removeAllMessageListsFromCacheIfHasChanges = function ()
	{
		if (this.hasChanges())
		{
			this.oUids = {};
			this.requestedLists = [];
			this.hasChanges(false);
		}
	};
	
	/**
	 * @param {string} sUidNext
	 * @param {string} sHash
	 * @param {number} iMsgCount
	 * @param {number} iMsgUnseenCount
	 */
	CFolderModel.prototype.setRelevantInformation = function (sUidNext, sHash, iMsgCount, iMsgUnseenCount)
	{
		var hasChanges = this.hasExtendedInfo() && 
			(this.hash() !== sHash || this.messageCount() !== iMsgCount || 
			this.unseenMessageCount() !== iMsgUnseenCount);
		
		this.uidNext(sUidNext);
		this.hash(sHash); // if different, either new messages were appeared, or some messages were deleted
		this.messageCount(iMsgCount);
		this.unseenMessageCount(iMsgUnseenCount);
		this.hasExtendedInfo(true);
		
		if (hasChanges)
		{
			this.markHasChanges();
		}
		
		return hasChanges;
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
		_.each(this.oUids, function (oUidList) {
			oUidList.deleteUids(aUids);
		}, this);
	};
	
	/**
	 * @param {string} sSearch
	 */
	CFolderModel.prototype.getUidList = function (sSearch)
	{
		var
			oUidList = null
		;
	
		if (this.oUids[sSearch] === undefined)
		{
			oUidList = new CUidListModel();
			oUidList.search(sSearch);
			this.oUids[sSearch] = oUidList;
		}
		
		return this.oUids[sSearch];
	};
	
	/**
	 * @param {Object} oResult
	 */
	CFolderModel.prototype.parse = function (oResult)
	{
		if (oResult['@Object'] === 'Object/Folder')
		{
			this.name(oResult.Name);
			this.nameForEdit(oResult.Name);
			this.fullName(oResult.FullNameRaw);
			this.routingHash(App.Routing.buildHashFromArray([Enums.Screens.Mailbox, this.fullName()]));
			this.delimiter(oResult.Delimiter);
			this.type(oResult.Type);
	
			this.subscribed(oResult.IsSubscribed);
			this.selectable(oResult.IsSelectable);
			this.existen(oResult.IsExisten);
			
			if (oResult.Extended)
			{
				this.setRelevantInformation(oResult.Extended.UidNext.toString(), oResult.Extended.Hash, 
					oResult.Extended.MessageCount, oResult.Extended.MessageUnseenCount);
			}
	
			return oResult.SubFolders;
		}
		
		return null;
	};
	
	CFolderModel.prototype.onFolderClick = function ()
	{
		if (this.canBeSelected())
		{
			App.Routing.setHash(App.Links.mailbox(this.fullName()));
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CFolderModel.prototype.onMessageResponse = function (oData, oParameters)
	{
		var
			oResult = oData.Result,
			oHand = null,
			sUid = oResult ? oResult.Uid.toString() : oParameters.Uid.toString(),
			oMessage = this.oMessages[sUid] ? this.oMessages[sUid] : new CMessageModel(),
			bSelected = oMessage.selected()
		;
	
		if (oResult === false)
		{
			if (bSelected)
			{
				if (oData.ErrorCode === Enums.Errors.CanNotGetMessage)
				{
					App.Api.showError(Utils.i18n('MESSAGE/ERROR_MESSAGE_DELETED'));
				}
				else
				{
					App.Api.showError(Utils.i18n('WARNING/UNKNOWN_ERROR'));
				}
			}
			
			oMessage = null;
		}
		else
		{
			oMessage.parse(oResult, oData.AccountID);
			this.oMessages[sUid] = oMessage;
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
				'Action': 'Message',
				'Folder': this.fullName(),
				'Uid': sUid
			}
		;
	
		if (sUid.length > 0)
		{
			if (!oMessage || !oMessage.completelyFilled())
			{
				if (fResponseHandler && oContext)
				{
					this.aResponseHandlers[sUid] = {handler: fResponseHandler, context: oContext};
				}
				App.Ajax.send(oParameters, this.onMessageResponse, this);
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
		var
			oMessage = this.oMessages[sUid]
		;
	
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
		var
			iUnseenDiff = 0
		;
	
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
	
			App.Cache.onClearFolder(this);
		}
	};
	
	CFolderModel.prototype.getNameWhithLevel = function ()
	{
		var
			iLevel = this.level()
		;
		
		if (!this.isNamespace() && iLevel > 0)
		{
			iLevel--;
		}
		
		return Utils.strRepeat("\u00A0", iLevel * 3) + this.name();
	};
	
	
	/**
	 * @constructor
	 */
	function CFolderListModel()
	{
		this.iAccountId = 0;
		
		this.bInitialized = ko.observable(false);
		this.collection = ko.observableArray([]);
		this.options = ko.observableArray([]);
		this.sNamespace = '';
		this.sNamespaceFolder = '';
	
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
	
		this.messageCount = ko.computed(function (){
			return this.getMessageCount(this.collection());
		}, this);
	
		this.currentFolder = ko.observable(null);
	
		this.inboxFolder = ko.observable(null);
		this.sentFolder = ko.observable(null);
		this.draftsFolder = ko.observable(null);
		this.spamFolder = ko.observable(null);
		this.trashFolder = ko.observable(null);
	
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
	
	/**
	 * @return {Array}
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
	 * @return {Array}
	 */
	CFolderListModel.prototype.getInboxAndCurrentFoldersArray = function ()
	{
		var aFolders = [this.inboxFolderFullName()];
		
		if (this.currentFolderType() !== Enums.FolderTypes.Inbox)
		{
			aFolders.push(this.currentFolderFullName());
		}
		
		return _.compact(aFolders);
	};
	
	/**
	 * @param {string} sFolderFullName
	 */
	CFolderListModel.prototype.setCurrentFolder = function (sFolderFullName)
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
			}
			
			this.currentFolder(oFolder);
			this.currentFolder().selected(true);
		}
	};
	
	/**
	 * Returns a folder, found by the type.
	 * 
	 * @param {number} iType
	 * @return {CFolderModel|null}
	 *
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
	 */
	CFolderListModel.prototype.parse = function (iAccountId, oData)
	{
		this.iAccountId = iAccountId;
		this.sNamespace = Utils.pExport(oData, 'Namespace', this.sNamespace);
		this.bInitialized(true);
	
		if (this.sNamespace.length > 0)
		{
			this.sNamespaceFolder = this.sNamespace.substring(0, this.sNamespace.length - 1);
		}
		else
		{
			this.sNamespaceFolder = this.sNamespace;
		}
		
		this.collection(this.parseRecursively(oData['@Collection']));
	};
	
	/**
	 * Recursively parses the folder tree.
	 * 
	 * @param {Array} aRowCollection
	 * @param {number=} iLevel
	 */
	CFolderListModel.prototype.parseRecursively = function (aRowCollection, iLevel)
	{
		var
			aParsedCollection = [],
			iIndex = 0,
			iLen = 0,
			oFolder = null,
			oSubFolders = null,
			aSubfolders = [],
			bFolderIsNamespace = false
		;
		
		if (Utils.isUnd(iLevel))
		{
			iLevel = -1;
		}
		
		iLevel++;
		if (_.isArray(aRowCollection))
		{
			for (iLen = aRowCollection.length; iIndex < iLen; iIndex++)
			{
				oFolder = new CFolderModel();
				oFolder.iAccountId = this.iAccountId;
				oSubFolders = oFolder.parse(aRowCollection[iIndex]);
				
				bFolderIsNamespace = (this.sNamespace === oFolder.fullName() + oFolder.delimiter());
				oFolder.isNamespace(bFolderIsNamespace);
				if (oSubFolders !== null)
				{
					aSubfolders = this.parseRecursively(oSubFolders['@Collection'], iLevel);
					oFolder.subfolders(aSubfolders);
				}
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
						break;
				}
	
				
				aParsedCollection.push(oFolder);
			}
		}
		
		return aParsedCollection;
	};
	
	/**
	 * @param {string} sFirstItem
	 * @param {boolean=} enableSystem = false
	 * @param {boolean=} hideInbox = false
	 */
	CFolderListModel.prototype.getOptions = function (sFirstItem, enableSystem, hideInbox)
	{
		var
			sDeepPrefix = '\u00A0\u00A0\u00A0\u00A0',
			getOptionsFromCollection = function (collection) {
	
				var
					iIndex = 0,
					iLen = 0,
					oItem = null,
					aResult = []
				;
				
				if (Utils.isUnd(enableSystem))
				{
					enableSystem = false;
				}
				
				if (Utils.isUnd(hideInbox))
				{
					hideInbox = false;
				}
				
				for (iIndex = 0, iLen = collection.length; iIndex < iLen; iIndex++)
				{
					oItem = collection[iIndex];
					
					if (oItem.type() !== Enums.FolderTypes.Inbox && hideInbox || !hideInbox)
					{
						aResult.push({
							'id': oItem.fullName(),
							'name': (new Array(oItem.level() + 1)).join(sDeepPrefix) + oItem.name(),
							'displayName': (new Array(oItem.level() + 1)).join(sDeepPrefix) + oItem.displayName(),
							'disable': ((oItem.isSystem() && !enableSystem) || !oItem.canBeSelected())
						});
					}
					
					aResult = aResult.concat(getOptionsFromCollection(oItem.subfolders()));
				}
	
				return aResult;
			},
			collection = getOptionsFromCollection(this.collection())
		;
	
		if (!Utils.isUnd(sFirstItem))
		{
			collection.unshift({
				'id': '',
				'name': sFirstItem,
				'displayName': sFirstItem,
				'disable': false
			});
		}
		
		return collection;
	};
	
	CFolderListModel.prototype.getMessageCount = function (aList)
	{
		var
			iIndex = 0,
			iLen = 0,
			oItem = null,
			iCount = 0
		;
	
		for (iIndex = 0, iLen = aList.length; iIndex < iLen; iIndex++)
		{
			oItem = aList[iIndex];
			iCount = iCount + oItem.messageCount();
			iCount = iCount + this.getMessageCount(oItem.subfolders());
		}
	
		return iCount;	
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
		this.accountId = ko.observable(0);
	
		this.folder = ko.observable('');
		this.uid = ko.observable('');
		this.subject = ko.observable('');
		this.emptySubject = ko.computed(function () {
			return (Utils.trim(this.subject()) === '');
		}, this);
		this.subjectForDisplay = ko.computed(function () {
			return (this.emptySubject()) ? 
				Utils.i18n('MAILBOX/EMPTY_SUBJECT') : this.subject();
		}, this);
		this.messageId = ko.observable('');
		this.textPartId = ko.observable(0);
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
		
		this.seen = ko.observable(false);
		
		this.flagged = ko.observable(false);
		this.answered = ko.observable(false);
		this.forwarded = ko.observable(false);
		this.hasAttachments = ko.observable(false);
		this.importance = ko.observable(Enums.Importance.Normal);
		this.draftInfo = ko.observableArray([]);
		this.sensitivity = ko.observable(Enums.Sensivity.Nothing);
		this.hash = ko.observable('');
		this.download = ko.computed(function () {
			return (this.hash().length > 0) ? Utils.getDownloadLinkByHash(this.accountId(), this.hash()) : '';
		}, this);
	
		this.completelyFilled = ko.observable(false);
	
		this.checked = ko.observable(false);
		this.selected = ko.observable(false);
		this.deleted = ko.observable(false); // temporary removal until it was confirmation from the server to delete
	
		this.inReplyTo = ko.observable('');
		this.references = ko.observable('');
		this.readingConfirmation = ko.observable('');
		this.text = ko.observable('');
		this.textBodyForNewWindow = ko.observable('');
		this.$text = null;
		this.hasExternals = ko.observable(false);
		this.isExternalsShown = ko.observable(false);
		this.isExternalsAlwaysShown = ko.observable(false);
		this.foundedCids = ko.observableArray([]);
		this.attachments = ko.observableArray([]);
		this.usesAttachmentString = false;
		this.safety = ko.observable(false);
		
		this.ical = ko.observable(null);
		this.vcard = ko.observable(null);
		
		this.domMessageForPrint = ko.observable(null);
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
				oLink.on('click', _.bind(oAttach.downloadAttachment, oAttach));
				
				oLink = $(oWin.document.body).find("[data-hash='view-" + oAttach.hash() + "']");
				oLink.on('click', _.bind(oAttach.viewAttachment, oAttach));
			}, this);
		}
	};
	
	/**
	 * Fields accountId, folder, oTo & oFrom should be filled.
	 */
	CMessageModel.prototype.fillFromOrToText = function ()
	{
		var oFolder = App.Cache.getFolderByFullName(this.accountId(), this.folder());
		
		if (oFolder.type() === Enums.FolderTypes.Drafts || oFolder.type() === Enums.FolderTypes.Sent)
		{
			this.fromOrToText(this.oTo.getDisplay());
		}
		else
		{
			this.fromOrToText(this.oFrom.getDisplay());
		}
	};
	
	/**
	 * @param {AjaxMessageResponse} oData
	 * @param {number} iAccountId
	 */
	CMessageModel.prototype.parse = function (oData, iAccountId)
	{
		var
			oIcal = null,
			oVcard = null
		;
		
		if (oData['@Object'] === 'Object/MessageListItem')
		{
			this.seen(!!oData.IsSeen);
			this.flagged(!!oData.IsFlagged);
			this.answered(!!oData.IsAnswered);
			this.forwarded(!!oData.IsForwarded);
		}
		
		if (oData['@Object'] === 'Object/Message' || oData['@Object'] === 'Object/MessageListItem')
		{
			this.accountId(iAccountId);
	
			this.folder(oData.Folder);
			this.uid(oData.Uid.toString());
			this.subject(oData.Subject);
			this.messageId(oData.MessageId);
			this.textPartId(oData.TextPartID);
			this.size(oData.Size);
			this.textSize(oData.TextSize);
			this.oDateModel.parse(oData.InternalTimeStampInUTC);
			this.oFrom.parse(oData.From);
			this.oTo.parse(oData.To);
			this.fillFromOrToText();
			this.oCc.parse(oData.Cc);
			this.oBcc.parse(oData.Bcc);
			this.oSender.parse(oData.Sender);
			
			this.fullDate(this.oDateModel.getFullDate());
			this.fullFrom(this.oFrom.getFull());
			this.to(this.oTo.getFull());
			this.cc(this.oCc.getFull());
			this.bcc(this.oBcc.getFull());
			
			this.hasAttachments(!!oData.HasAttachments);
			this.importance(oData.Priority);
			if (_.isArray(oData.DraftInfo))
			{
				this.draftInfo(oData.DraftInfo);
			}
			this.sensitivity(oData.Sensitivity);
			this.hash(oData.Hash);
	
			if (oData['@Object'] === 'Object/Message')
			{
				this.inReplyTo(oData.InReplyTo);
				this.references(oData.References);
				this.readingConfirmation(oData.ReadingConfirmation);
				if (oData.Html !== '')
				{
					this.text(oData.Html);
				}
				else
				{
					this.text(oData.Plain !== '' ? '<div>' + oData.Plain + '</div>' : '');
				}
				this.hasExternals(!!oData.HasExternals);
				this.foundedCids(oData.FoundedCIDs);
				this.parseAttachments(oData.Attachments, iAccountId);
				this.safety(oData.Safety);
				
				if (oData.ICAL !== null)
				{
					oIcal = new CIcalModel();
					oIcal.parse(oData.ICAL);
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
		}
	};
	
	/**
	 * @param {string=} sAppPath = ''
	 */
	CMessageModel.prototype.getDomText = function (sAppPath)
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
	 * Parses attachments.
	 *
	 * @param {Array} aData
	 * @param {number} iAccountId
	 */
	CMessageModel.prototype.parseAttachments = function (aData, iAccountId)
	{
		if (_.isArray(aData))
		{
			this.attachments(_.map(aData, function (oRawAttach) {
				var oAttachment = new CAttachmentModel(this.folder(), this.uid());
				oAttachment.parse(oRawAttach, iAccountId);
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
		sCid = '<' + sCid + '>';
	
		return _.find(this.attachments(), function (oAttachment) {
			return oAttachment.cid() === sCid;
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
	
	
	/**
	 * @constructor
	 * 
	 * !!!Attention!!!
	 * It is not used underscore, because the collection may contain undefined-elements.
	 * They have their own importance. But all underscore-functions removes them automatically.
	 */
	function CUidListModel()
	{
		this.searchCount = ko.observable(-1);
		
		this.search = ko.observable('');
		
		this.collection = ko.observableArray([]);
	}
	
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
	
			this.searchCount(oResult.MessageSearchCount);
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
			aNewCollection = []
		;
		
		for (; iIndex < iLen; iIndex++)
		{
			sUid = this.collection()[iIndex];
			if (_.indexOf(aUids, sUid) === -1)
			{
				aNewCollection.push(sUid);
			}
		}
		
		this.collection(aNewCollection);
	};
	
	/**
	 * @constructor
	 */
	function CIcalModel()
	{
		this.uid = ko.observable('');
		this.uid.subscribe(function () {
			if (this.uid() !== '' && this.uid() !== '0')
			{
				App.EventsCache.addIcal(this);
			}
		}, this);
		this.file = ko.observable('');
		
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
		this.calendars = App.EventsCache.calendars;
	
		this.selectedCalendar = ko.observable('');
	
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
	 */
	CIcalModel.prototype.parse = function (oData)
	{
		if (oData && oData['@Object'] === 'Object/CApiMailIcs')
		{
			this.uid(oData.Uid.toString());
			this.file(oData.File);
			this.type(oData.Type);
			this.location(oData.Location);
			this.description(oData.Description.replace(/\r/g, '').replace(/\n/g,"<br />"));
			this.when(oData.When);
			this.calendarId(oData.CalendarId);
			this.selectedCalendar(oData.CalendarId);
		}
	};
	
	CIcalModel.prototype.acceptAppointment = function ()
	{
		this.changeAndSaveConfig(Enums.IcalConfig.Accepted);
	};
	
	CIcalModel.prototype.tentativeAppointment = function ()
	{
		this.changeAndSaveConfig(Enums.IcalConfig.Tentative);
	};
	
	CIcalModel.prototype.declineAppointment = function ()
	{
		this.changeAndSaveConfig(Enums.IcalConfig.Declined);
	};
	
	/**
	 * @param {string} sConfig
	 */
	CIcalModel.prototype.changeAndSaveConfig = function (sConfig)
	{
		this.changeConfig(sConfig);
		this.doProcessAppointment();
	};
	
	/**
	 * @param {string} sConfig
	 */
	CIcalModel.prototype.changeConfig = function (sConfig)
	{
		this.type(this.icalType() + '-' + sConfig);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CIcalModel.prototype.onProcessAppointmentResponse = function (oData, oParameters)
	{
		if (!oData || !oData.Result)
		{
			App.Api.showError(Utils.i18n('WARNING/UNKNOWN_ERROR'));
		}
	};
	
	CIcalModel.prototype.doProcessAppointment = function ()
	{
		var
			oParameters = {
				'Action': 'ProcessAppointment',
				'AppointmentAction': this.icalConfig(),
				'CalendarId': this.selectedCalendar(),
				'File': this.file()
			}
		;
	
		App.Ajax.send(oParameters, this.onProcessAppointmentResponse, this);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CIcalModel.prototype.onAddEventResponse = function (oData, oParameters)
	{
		if (oData && oData.Result && oData.Result.Uid)
		{
			this.uid(oData.Result.Uid);
		}
	};
	
	CIcalModel.prototype.addEvent = function ()
	{
		var
			oParameters = {
				'Action': 'SaveIcs',
				'CalendarId': this.selectedCalendar(),
				'File': this.file()
			}
		;
		
		App.Ajax.send(oParameters, this.onAddEventResponse, this);
		
		this.isJustSaved(true);
		this.calendarId(this.selectedCalendar());
		
		setTimeout(_.bind(function () {
			this.isJustSaved(false);
		}, this), 20000);
		
		App.EventsCache.recivedAnim(true);
	};
	
	CIcalModel.prototype.onEventDelete = function ()
	{
		this.calendarId('');
		this.selectedCalendar('');
		this.icalConfig(Enums.IcalConfig.NeedsAction);
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
	 * @constructor
	 */
	function CVcardModel()
	{
		this.uid = ko.observable('');
		this.uid.subscribe(function () {
			if (this.uid() !== '' && this.uid() !== '0')
			{
				App.ContactsCache.addVcard(this);
			}
		}, this);
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
			this.uid(oData.Uid.toString());
			this.file(oData.File);
			this.name(oData.Name);
			this.email(oData.Email);
			this.isExists(oData.Exists);
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CVcardModel.prototype.onAddContactResponse = function (oData, oParameters)
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
				'Action': 'SaveVcf',
				'File': this.file()
			}
		;
		
		App.Ajax.send(oParameters, this.onAddContactResponse, this);
		
		this.isJustSaved(true);
		this.isExists(true);
		
		setTimeout(_.bind(function () {
			this.isJustSaved(false);
		}, this), 20000);
		
		App.ContactsCache.recivedAnim(true);
	};
	
	
	/**
	 * @constructor
	 */
	function CContactModel()
	{
		this.sDefaultType = Enums.ContactEmailType.Personal;
	
		this.idContact = ko.observable('');
		this.idUser = ko.observable('');
	
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
		
		this.displayNameFocused = ko.observable(false);
	
		this.primaryEmail = ko.observable(this.sDefaultType);
	
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
	
		this.personalIsEmpty = ko.computed(function () {
			return '' === '' + this.personalEmail() +
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
	
		this.businessIsEmpty = ko.computed(function () {
			return '' === '' + this.businessEmail() +
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
	
		this.otherEmail = ko.observable('');
		this.otherBirthdayMonth = ko.observable('0');
		this.otherBirthdayDay = ko.observable('0');
		this.otherBirthdayYear = ko.observable('0');
		this.otherNotes = ko.observable('');
		this.etag = ko.observable('');
		
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
				oDateModel.setDate(iYear, iMonth, iDay);
				sBirthday = oDateModel.getShortDate();
			}
			
			return sBirthday;
		}, this);
	
		this.otherIsEmpty = ko.computed(function () {
			return ('' === ('' + this.otherEmail() + this.otherNotes())) && this.birthdayIsEmpty();
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
						this.primaryEmail(this.sDefaultType);
						this.email(sEmail);
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
			return true;
		}, this);
		
		this.sendMailLink = ko.computed(function () {
			return this.getSendMailLink(this.email());
		}, this);
		
		this.sendMailToPersonalLink = ko.computed(function () {
			return this.getSendMailLink(this.personalEmail());
		}, this);
		
		this.sendMailToBusinessLink = ko.computed(function () {
			return this.getSendMailLink(this.businessEmail());
		}, this);
		
		this.sendMailToOtherLink = ko.computed(function () {
			return this.getSendMailLink(this.otherEmail());
		}, this);
	}
	
	CContactModel.birthdayMonthSelect = [
		{'text': Utils.i18n('DATETIME/MONTH'), value: '0'},
		{'text': Utils.i18n('DATETIME_MONTH/JANUARY'), value: '1'},
		{'text': Utils.i18n('DATETIME_MONTH/FEBRUARY'), value: '2'},
		{'text': Utils.i18n('DATETIME_MONTH/MARCH'), value: '3'},
		{'text': Utils.i18n('DATETIME_MONTH/APRIL'), value: '4'},
		{'text': Utils.i18n('DATETIME_MONTH/MAY'), value: '5'},
		{'text': Utils.i18n('DATETIME_MONTH/JUNE'), value: '6'},
		{'text': Utils.i18n('DATETIME_MONTH/JULY'), value: '7'},
		{'text': Utils.i18n('DATETIME_MONTH/AUGUST'), value: '8'},
		{'text': Utils.i18n('DATETIME_MONTH/SEPTEMBER'), value: '9'},
		{'text': Utils.i18n('DATETIME_MONTH/OCTOBER'), value: '10'},
		{'text': Utils.i18n('DATETIME_MONTH/NOVEMBER'), value: '11'},
		{'text': Utils.i18n('DATETIME_MONTH/DECEMBER'), value: '12'}
	];
	
	CContactModel.birthdayYearSelect = [
		{'text': Utils.i18n('DATETIME/YEAR'), 'value': '0'}
	];
	
	CContactModel.prototype.getSendMailLink = function (sEmail)
	{
		var
			sFullEmail = this.getFullEmail(sEmail),
			aLinkParts = App.Links.composeWithToField(sFullEmail),
			sLink = App.Routing.buildHashFromArray(aLinkParts)
		;
		
		return sLink;
	};
	
	CContactModel.prototype.clear = function ()
	{
		this.isNew(false);
		this.readOnly(false);
	
		this.idContact('');
		this.idUser('');
	
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
	
		this.primaryEmail(this.sDefaultType);
	
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
	
		this.groups([]);
	};
	
	CContactModel.prototype.switchToNew = function ()
	{
		this.clear();
		this.edited(true);
		this.extented(false);
		this.isNew(true);
		this.displayNameFocused(true);
	};
	
	CContactModel.prototype.switchToView = function ()
	{
		this.edited(false);
		this.extented(false);
	};
	
	CContactModel.prototype.toObject = function ()
	{
		var oResult = {
			'ContactId': this.idContact(),
			'PrimaryEmail': this.primaryEmail(),
			'UseFriendlyName': '1',
			'Title': '',
			'FullName': this.displayName(),
			'FirstName': this.firstName(),
			'LastName': this.lastName(),
			'NickName': this.nickName(),
	
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
	
			'GroupsIds': this.groups()
		};
	
		return oResult;
	};
	
	CContactModel.prototype.parse = function (oData)
	{
		if (oData && 'Object/CContact' === oData['@Object'])
		{
			this.idContact(Utils.pExport(oData, 'IdContact', '').toString());
			this.idUser(Utils.pExport(oData, 'IdUser', '').toString());
	
			this.readOnly(!!Utils.pExport(oData, 'ReadOnly', false));
	
			this.displayName(Utils.pExport(oData, 'FullName', ''));
			this.firstName(Utils.pExport(oData, 'FirstName', ''));
			this.lastName(Utils.pExport(oData, 'LastName', ''));
			this.nickName(Utils.pExport(oData, 'NickName', ''));
	
			var iPrimaryEmail =	Utils.pInt(Utils.pExport(oData, 'PrimaryEmail', 0));
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
	
			var aGroupsIds = Utils.pExport(oData, 'GroupsIds', []);
			if (_.isArray(aGroupsIds))
			{
				this.groups(aGroupsIds);
			}
		}
	};
	
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
	
	CContactModel.prototype.viewAllMails = function ()
	{
		App.Api.searchMessagesInInbox('email:' + this.email());
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
		this.edited = ko.observable(false);
	
		this.nameFocused = ko.observable(false);
	
		this.canBeSave = ko.computed(function () {
			return '' !== this.name();
		}, this);
	
		this.newContactsInGroupCount = ko.observable(0);
	
		this.newContactsInGroupHint = ko.computed(function () {
			var iCount = this.newContactsInGroupCount();
			return this.isNew() && 0 < iCount ? Utils.i18n('CONTACTS/CONTACT_ADD_TO_NEW_HINT', {
				'COUNT' : iCount
			}) : '';
		}, this);
	}
	
	CGroupModel.prototype.clear = function ()
	{
		this.isNew(false);
	
		this.idGroup('');
		this.idUser('');
	
		this.name('');
		this.nameFocused(false);
		this.edited(false);
	};
	
	CGroupModel.prototype.switchToNew = function ()
	{
		this.clear();
		this.edited(true);
		this.isNew(true);
		this.nameFocused(true);
	};
	
	CGroupModel.prototype.switchToView = function ()
	{
		this.edited(false);
	};
	
	CGroupModel.prototype.addContactFromList = function (oItem)
	{
	//	if (oItem instanceof CContactListModel)
	//	{
	//		this.emails.push(oItem);
	//	}
	};
	
	CGroupModel.prototype.toObject = function ()
	{
		return {
			'GroupId': this.idGroup(),
			'Name': this.name()
		};
	
	//	var
	//		aContactsIds = _.map(this.emails(), function (oItem) {
	//			return oItem.Id();
	//		}),
	//		aDeletedIds = _.map(this.deletedEmails(), function (oItem) {
	//			return oItem.Id();
	//		})
	//	;
	//
	//	return {
	//		'GroupId': this.idGroup(),
	//		'Name': this.name(),
	//		'ContactsIds': aContactsIds,
	//		'DeletedContactsIds': aDeletedIds
	//	};
	};
	
	
	
	/**
	 * @constructor
	 */
	function CContactListModel()
	{
		this.bIsGroup = false;
		this.bReadOnly = false;
		this.bGlobal = false;
		this.sId = '';
		this.sName = '';
		this.sEmail = '';
	
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
	CContactListModel.prototype.parse = function (oData, bGlobal)
	{
		if (oData && 'Object/CContactListItem' === Utils.pExport(oData, '@Object', ''))
		{
			this.sId = oData.Id.toString();
			this.sName = oData.Name.toString();
			this.sEmail = oData.Email.toString();
	
			this.bIsGroup = !!oData.IsGroup;
			this.bReadOnly = !!oData.ReadOnly;
			this.bGlobal = Utils.isUnd(bGlobal) ? false : !!bGlobal;
		}
	};
	
	/**
	 * @return {boolean}
	 */
	CContactListModel.prototype.IsGroup = function ()
	{
		return this.bIsGroup;
	};
	
	/**
	 * @return {boolean}
	 */
	CContactListModel.prototype.Global = function ()
	{
		return this.bGlobal;
	};
	
	/**
	 * @return {boolean}
	 */
	CContactListModel.prototype.ReadOnly = function ()
	{
		return this.bReadOnly;
	};
	
	/**
	 * @return {string}
	 */
	CContactListModel.prototype.Id = function ()
	{
		return this.sId;
	};
	
	/**
	 * @return {string}
	 */
	CContactListModel.prototype.Name = function ()
	{
		return this.sName;
	};
	
	/**
	 * @return {string}
	 */
	CContactListModel.prototype.Email = function ()
	{
		return this.sEmail;
	};
	
	/**
	 * @return {string}
	 */
	CContactListModel.prototype.EmailAndName = function ()
	{
		return this.sName && this.sEmail && 0 < this.sName.length && 0 < this.sEmail.length ? '"' + this.sName + '" <' + this.sEmail + '>' : this.sEmail;
	};
	
	/**
	 * @constructor
	 */
	function CFileModel()
	{
		this.name = ko.observable('');
		this.size = ko.observable(0);
		this.type = ko.observable('');
		this.friendlySize = ko.computed(function () {
			return Utils.friendlySize(this.size());
		}, this);
		
		this.hash = ko.observable('');
		this.download = ko.computed(function () {
			return Utils.getDownloadLinkByHash(AppData.Accounts.currentId(), this.hash());
		}, this);
	}
	
	CFileModel.prototype.onDownloadClick = function ()
	{
		App.downloadByUrl(this.download());
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
		
		this.enable = ko.observable(true);
		
		this.field = ko.observable(''); //map to Field
		this.condition = ko.observable('');
		this.filter = ko.observable('');
		this.action = ko.observable('');
		this.folder = ko.observable('');
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
		this.loadingMessage = ko.observable('');
		this.loadingVisible = ko.observable(false);
		this.reportMessage = ko.observable('');
		this.reportVisible = ko.observable(false);
		this.iReportTimeout = NaN;
		this.errorMessage = ko.observable('');
		this.errorVisible = ko.observable(false);
		this.iErrorTimeout = -1;
		this.isHtmlError = ko.observable(false);
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
	}
	;
	
	CInformationViewModel.prototype.hideLoading = function ()
	{
		this.loadingVisible(false);
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
		
		iDelay = iDelay || 5000;
		
		if (sMessage && sMessage !== '')
		{
			this.reportMessage(sMessage);
			this.reportVisible(true);
			if (!isNaN(this.iReportTimeout))
			{
				clearTimeout(this.iReportTimeout);
			}
			this.iReportTimeout = setTimeout(function () {
				self.reportVisible(false);
			}, iDelay);
		}
		else
		{
			this.reportVisible(false);
		}
	};
	
	/**
	 * Displays an error message. Starts a timer for hiding.
	 * 
	 * @param {string} sMessage
	 * @param {boolean=} bHtml = false
	 * @param {boolean=} bNotHide = false
	 */
	CInformationViewModel.prototype.showError = function (sMessage, bHtml, bNotHide)
	{
		var self = this;
		
		if (sMessage && sMessage !== '')
		{
			this.errorMessage(sMessage);
			this.errorVisible(true);
			this.isHtmlError(bHtml);
			clearTimeout(this.iErrorTimeout);
			if (!bNotHide)
			{
				this.iErrorTimeout = setTimeout(function () {
					self.errorVisible(false);
				}, 5000);
			}
		}
		else
		{
			this.errorVisible(false);
		}
	};
	
	
	/**
	 * @constructor
	 */
	function CHeaderViewModel()
	{
		this.currentAccountId = AppData.Accounts.currentId;
		this.currentAccountId.subscribe(function () {
			this.changeCurrentAccount();
		}, this);
		
		this.email = ko.observable('');
		this.accounts = AppData.Accounts.Collection;
		
		this.bAllowContacts = AppData.User.ShowContacts;
		this.bAllowCalendar = AppData.User.AllowCalendar;
		
		this.currentTab = App.Screens.currentScreen;
	
		this.sMailboxHash = App.Routing.buildHashFromArray([Enums.Screens.Mailbox]);
		this.sContactsHash = App.Routing.buildHashFromArray([Enums.Screens.Contacts]);
		this.sCalendarHash = App.Routing.buildHashFromArray([Enums.Screens.Calendar]);
		this.sFileStorageHash = App.Routing.buildHashFromArray([Enums.Screens.FileStorage]);
		this.sSettingsHash = App.Routing.buildHashFromArray([Enums.Screens.Settings]);
		
		this.contactsRecivedAnim = App.ContactsCache.recivedAnim;
		this.calendarRecivedAnim = App.EventsCache.recivedAnim;
	}
	
	CHeaderViewModel.prototype.gotoMailbox = function ()
	{
		App.Routing.setLastMailboxHash();
		
		return false;
	};
	
	CHeaderViewModel.prototype.onRoute = function ()
	{
		this.changeCurrentAccount();
	};
	
	CHeaderViewModel.prototype.changeCurrentAccount = function ()
	{
		this.email(AppData.Accounts.getEmail());
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CHeaderViewModel.prototype.onLogoutResponse = function (oData, oParameters)
	{
		App.Routing.finalize();
		if (AppData.App.CustomLogoutUrl !== '')
		{
			window.location.href = AppData.App.CustomLogoutUrl;
		}
		else
		{
			window.location.reload();
		}
	},
	
	CHeaderViewModel.prototype.logout = function ()
	{
		var
			oParameters = {
				'Action': 'Logout'
			}
		;
		
		App.Ajax.send(oParameters, this.onLogoutResponse, this);
	};
	
	
	/**
	 * @constructor
	 * @param {number} iCount
	 * @param {number} iPerPage
	 */
	function CPageSwitcherViewModel(iCount, iPerPage)
	{
		this.currentPage = ko.observable(1);
		this.count = ko.observable(iCount);
		this.perPage = ko.observable(iPerPage);
	
		this.pagesCount = ko.computed(function () {
			return Math.ceil(this.count() / this.perPage());
		}, this);
	
		this.firstPage = ko.computed(function () {
			var iValue = (this.currentPage() - 2);
			return (iValue > 0) ? iValue : 1;
		}, this);
	
		this.lastPage = ko.computed(function () {
			var iValue = this.firstPage() + 4;
			return (iValue <= this.pagesCount()) ? iValue : this.pagesCount();
		}, this);
	
		this.visibleFirst = ko.computed(function () {
			return (this.firstPage() > 1);
		}, this);
	
		this.visibleLast = ko.computed(function () {
			return (this.lastPage() < this.pagesCount());
		}, this);
	
		this.pages = ko.computed(function () {
			var
				iIndex = this.firstPage(),
				aPages = []
			;
	
			if (this.firstPage() < this.lastPage()) {
				for (; iIndex <= this.lastPage(); iIndex++) {
					aPages.push({
						number: iIndex,
						current: (iIndex === this.currentPage()),
						clickFunc: _.bind(this.clickPage, this)
					});
				}
			}
	
			return aPages;
		}, this);
	}
	
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
	};
	
	/**
	 * @param {number} iPage
	 * @param {number} iPerPage
	 */
	CPageSwitcherViewModel.prototype.setPage = function (iPage, iPerPage)
	{
		this.perPage(iPerPage);
		this.currentPage(iPage);
	};
	
	/**
	 * @param {Object} oPage
	 */
	CPageSwitcherViewModel.prototype.clickPage = function (oPage)
	{
		var iPage = oPage.number;
		if (iPage < 1) {
			iPage = 1;
		}
		if (iPage > this.pagesCount()) {
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
		var iPrevPage = this.firstPage() - 1;
		if (iPrevPage < 1) {
			iPrevPage = 1;
		}
		this.currentPage(iPrevPage);
	};
	
	CPageSwitcherViewModel.prototype.clickNextPage = function ()
	{
		var iNextPage = this.lastPage() + 1;
		if (iNextPage > this.pagesCount()) {
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
	 * @param {boolean} bAllowInsertImage
	 */
	function CHtmlEditorViewModel(bAllowInsertImage)
	{
		this.containerDom = ko.observable(null);
		this.headerDom = ko.observable(null);
		
		this.creaId = 'creaId' + Math.random().toString().replace('.', '');
	
		this.allowInsertImage = ko.observable(bAllowInsertImage && AppData.App.AllowInsertImage);
		this.lockFontSubscribing = ko.observable(false);
	
		this.aFonts = ['Arial', 'Arial Black', 'Courier New', 'Tahoma', 'Times New Roman', 'Verdana'];
		this.iDefaultFont = 'Tahoma';
		this.selectedFont = ko.observable(this.iDefaultFont);
		this.selectedFont.subscribe(function () {
			if (!this.lockFontSubscribing())
			{
				this.oCrea.fontName(this.selectedFont());
			}
		}, this);
	
		this.aSizes = [1, 2, 3, 4, 5, 6, 7];
		this.iDefaultSize = 3;
		this.selectedSize = ko.observable(this.iDefaultSize);
		this.selectedSize.subscribe(function () {
			if (!this.lockFontSubscribing())
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
	
		this.visibleInsertImagePopup = ko.observable(false);
		this.imageUploaderButton = ko.observable(null);
		this.uploadedImagePathes = ko.observableArray([]);
		this.imagePathFromWeb = ko.observable('');
	
		this.visibleFontColorPopup = ko.observable(false);
		this.oFontColorPicker = new CColorPickerViewModel(Utils.i18n('HTMLEDITOR/TEXT_COLOR_CAPTION'), this.setTextColorFromPopup, this);
		this.oBackColorPicker = new CColorPickerViewModel(Utils.i18n('HTMLEDITOR/BACKGROUND_COLOR_CAPTION'), this.setBackColorFromPopup, this);
		
		this.activitySource = ko.observable(1);
		this.inactive = ko.observable(false);
		this.inactive.subscribe(function () {
			var sText = this.removeAllTags(this.getText());
			
			if (this.inactive())
			{
				if (sText === '' || sText === '&nbsp;')
				{
					this.setText('<span style="color: #AAAAAA;">' + Utils.i18n('HTMLEDITOR/SIGNATURE_PLACEHOLDER') + '</span>');
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
	}
	
	CHtmlEditorViewModel.prototype.showLinkPopup = function ($link)
	{
		var
			oPos = $link.position(),
			iHeight = $link.height()
		;
		
		this.linkHref($link.attr('href'));
		this.linkPopupLeft(Math.round(oPos.left));
		this.linkPopupTop(Math.round(oPos.top + iHeight));
	
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
	 * @param {string} sText
	 * @param {string} sTabIndex
	 */
	CHtmlEditorViewModel.prototype.initCrea = function (sText, sTabIndex)
	{
		if (!this.oCrea)
		{
			this.oCrea = new Crea({
				'creaId': this.creaId,
				'tabindex': sTabIndex,
				'text': sText,
				'fontNameArray': this.aFonts,
				'defaultFontName': this.iDefaultFont,
				'defaultFontSize': this.iDefaultSize,
				'onCursorMove': _.bind(this.setFontValuesFromText, this),
				'onFocus': _.bind(this.onCreaFocus, this),
				'onUrlIn': _.bind(this.showLinkPopup, this),
				'onUrlOut': _.bind(this.hideLinkPopup, this)
			});
		}
		else
		{
			this.setText(sText);
			this.oCrea.setTabIndex(sTabIndex);
		}
		
		this.resize();
	};
	
	CHtmlEditorViewModel.prototype.setFocus = function ()
	{
		if (this.oCrea)
		{
			this.oCrea.setFocus();
		}
	};
	
	CHtmlEditorViewModel.prototype.setFontValuesFromText = function ()
	{
		this.lockFontSubscribing(true);
		this.selectedFont(this.oCrea.getFontName());
		this.selectedSize(this.oCrea.getFontSize());
		this.lockFontSubscribing(false);
	};
	
	CHtmlEditorViewModel.prototype.getText = function ()
	{
		if (this.oCrea)
		{
			return this.oCrea.getText();
		}
		
		return '';
	};
	
	/**
	 * @param {string} sText
	 */
	CHtmlEditorViewModel.prototype.setText = function (sText)
	{
		if (this.oCrea)
		{
			this.oCrea.setText(sText);
			this.inactive.valueHasMutated();
		}
	};
	
	CHtmlEditorViewModel.prototype.removeAllTags = function (sText)
	{
		return sText.replace(/<style>.*<\/style>/g, '').replace(/<[^>]*>/g, '');
	};
	
	CHtmlEditorViewModel.prototype.setActivitySource = function (activitySource)
	{
		this.activitySource = activitySource;
		
		this.activitySource.subscribe(function () {
			this.inactive(Utils.pInt(this.activitySource()) === 0);
		}, this);
		
		this.inactive(Utils.pInt(this.activitySource()) === 0);
	};
	
	CHtmlEditorViewModel.prototype.onCreaFocus = function ()
	{
		this.closeAllPopups();
		this.activitySource(1);
	};
	
	CHtmlEditorViewModel.prototype.closeAllPopups = function ()
	{
		this.visibleInsertLinkPopup(false);
		this.visibleInsertImagePopup(false);
		this.visibleFontColorPopup(false);
	};
	
	CHtmlEditorViewModel.prototype.insertLink = function ()
	{
		if (!this.visibleInsertLinkPopup())
		{
			this.linkForInsert(this.oCrea.getSelectedText());
			this.closeAllPopups();
			this.visibleInsertLinkPopup(true);
			this.linkFocused(true);
		}
	};
	
	CHtmlEditorViewModel.prototype.insertLinkFromPopup = function (oCurrentViewModel, event)
	{
		if (this.linkForInsert().length > 0)
		{
			this.oCrea.insertLink(this.linkForInsert());
		}
		this.closeInsertLinkPopup(oCurrentViewModel, event);
	};
	
	CHtmlEditorViewModel.prototype.closeInsertLinkPopup = function (oCurrentViewModel, event)
	{
		this.visibleInsertLinkPopup(false);
		if (event)
		{
			event.stopPropagation();
		}
	};
	
	CHtmlEditorViewModel.prototype.textColor = function ()
	{
		this.closeAllPopups();
		this.visibleFontColorPopup(true);
		this.oFontColorPicker.onShow();
		this.oBackColorPicker.onShow();
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
		this.visibleFontColorPopup(false);
	};
	
	/**
	 * @param {string} sColor
	 */
	CHtmlEditorViewModel.prototype.setBackColorFromPopup = function (sColor)
	{
		this.oCrea.backgroundColor(this.colorToHex(sColor));
		this.visibleFontColorPopup(false);
	};
	
	CHtmlEditorViewModel.prototype.resize = function ()
	{
		var
			$cont = $(this.containerDom()),
			$header = $(this.headerDom()),
			iContW = $cont.width(),
			iContH = $cont.height(),
			iCreaH = iContH - $header.height()
		;
	
		if (this.oCrea)
		{
			this.oCrea.resize(iContW, iCreaH);
		}
	};
	
	CHtmlEditorViewModel.prototype.insertImage = function ()
	{
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
	
	CHtmlEditorViewModel.prototype.clearImages = function ()
	{
		this.uploadedImagePathes([]);
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
				'action': 'index.php?/Upload/Attachment/',
				'name': 'jua-uploader',
				'queueSize': 2,
				'clickElement': this.imageUploaderButton(),
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
				},
				'onSelect': _.bind(this.onFileUploadSelect, this),
				'onComplete': _.bind(this.onFileUploadComplete, this)
			});
		}
	};
	
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
				this.insertComputerImageFromPopup(sUid, oData.Result.Attachment);
			}
		}
		else
		{
			App.Screens.showPopup(AlertPopup, [Utils.i18n('COMPOSE/UPLOAD_ERROR_UNKNOWN')]);
		}
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
	
	/**
	 */
	CColorPickerViewModel.prototype.onShow = function ()
	{
		$(this.colorPickerDom()).find('span.color-item').on('click', (function (self)
		{
			return function ()
			{
				self.setColorFromPopup($(this).data('color'));
			};
		})(this));
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
	function CLoginViewModel()
	{
		this.email = ko.observable('');
		this.login = ko.observable('');
		this.password = ko.observable('');
		
		this.loginDescription = ko.observable('');
	
		this.emailFocus = ko.observable(false);
		this.loginFocus = ko.observable(false);
		this.passwordFocus = ko.observable(false);
	
		this.loading = ko.observable(false);
	
		this.loginFocus.subscribe(function (bFocus) {
			if (bFocus && '' === this.login()) {
				this.login(this.email());
			}
		}, this);
	
		this.loginFormType = ko.observable(Enums.LoginFormType.Email);
	
		this.emailVisible = ko.computed(function () {
			return Enums.LoginFormType.Login !== this.loginFormType();
		}, this);
		
		this.loginVisible = ko.computed(function () {
			return Enums.LoginFormType.Email !== this.loginFormType();
		}, this);
	
		this.signMeType = ko.observable(Enums.LoginSignMeType.DefaultOn);
		this.signMe = ko.observable(Enums.LoginSignMeType.DefaultOn === this.signMeType());
		this.signMeType.subscribe(function () {
			this.signMe(Enums.LoginSignMeType.DefaultOn === this.signMeType());
		}, this);
	
		this.focusedField = '';
	
		this.aLanguages = AppData.App.Languages;
		this.currentLanguage = ko.observable(AppData.App.DefaultLanguage);
		
		this.allowLanguages = ko.observable(AppData.App.AllowLanguageOnLogin);
		this.viewLanguagesAsDropdown = ko.observable(!AppData.App.FlagsLangSelect);
	
		this.canBeLogin = ko.computed(function () {
	
			var
				iLoginType = this.loginFormType(),
				sEmail = this.email(),
				sLogin = this.login(),
				sPassword = this.password()
			;
	
			return (
				!this.loading() &&
				'' !== Utils.trim(sPassword) &&
				(
					(Enums.LoginFormType.Login === iLoginType && '' !== Utils.trim(sLogin)) ||
					(Enums.LoginFormType.Email === iLoginType && '' !== Utils.trim(sEmail))
				)
			);
		}, this);
	
		this.signInButtonText = ko.computed(function () {
			return this.loading() ? Utils.i18n('LOGIN/BUTTON_SIGNING_IN') : Utils.i18n('LOGIN/BUTTON_SIGN_IN');
		}, this);
	
		this.loginCommand = Utils.createCommand(this, this.signIn, this.canBeLogin);
	
		this.email(AppData.App.DemoWebMailLogin || '');
		this.password(AppData.App.DemoWebMailPassword || '');
		this.loginDescription(AppData.App.LoginDescription || '');
	}
	
	CLoginViewModel.prototype.onShow = function ()
	{
		this.emailFocus(true);
	};
	
	CLoginViewModel.prototype.signIn = function ()
	{
		this.sendRequest();
	};
	
	/**
	 * @param {Object} oData
	 */
	CLoginViewModel.prototype.onResponse = function (oData)
	{
		if (false === oData.Result)
		{
			this.loading(false);
			App.Api.showError(Utils.i18n('WARNING/LOGIN_PASS_INCORRECT'));
		}
		else
		{
			location.reload();
		}
	};
	
	CLoginViewModel.prototype.sendRequest = function ()
	{
		var
			oParameters = {
				'Action': 'Login',
				'Email': this.email(),
				'IncLogin': this.login(),
				'IncPassword': this.password(),
				'SignMe': this.signMe() ? '1' : '0'
			}
		;
	
		this.loading(true);
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	CLoginViewModel.prototype.changeLanguage = function (sLanguage)
	{
		if (sLanguage && this.allowLanguages())
		{
			this.currentLanguage(sLanguage);
	
			App.Ajax.send({
				'Action': 'LoginLanguageUpdate',
				'Language': sLanguage
			}, function () {
				window.location.reload();
			}, this);
		}
	};
	
	
	/**
	 * @constructor
	 */
	function CFolderListViewModel()
	{
		this.foldersContainer = ko.observable(null);
		
		this.folderList = App.Cache.folderList;
		
		this.manageFoldersHash = App.Routing.buildHashFromArray([Enums.Screens.Settings, 
			Enums.SettingsTab.EmailAccounts, 
			Enums.AccountSettingsTab.Folders]);
	
		this.quotaProc = ko.observable(-1);
		this.quotaDesc = ko.observable('');
	
		ko.computed(function () {
	
			if (!AppData.App.ShowQuotaBar)
			{
				return true;
			}
	
			App.Cache.quotaChangeTrigger();
	
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
	
	CFolderListViewModel.prototype.onApplyBindings = function ()
	{
	//	var self = this;
	//	$(this.foldersContainer())
	//		.on('click', 'div.folder', function (oEvent) {
	//			self.onLineClick(ko.dataFor(this));
	//		})
	//	;
	};
	
	CFolderListViewModel.prototype.onLineClick = function (oFolder)
	{
		oFolder.onFolderClick();
	};
	
	/**
	 * @constructor
	 */
	function CMessageListViewModel()
	{
		this.isFocused = ko.observable(false);
	
		this.messagesContainer = ko.observable(null);
	
		this.searchInput = ko.observable('');
		this.searchInputFrom = ko.observable('');
		this.searchInputTo = ko.observable('');
		this.searchInputSubject = ko.observable('');
		this.searchInputText = ko.observable('');
		this.bAdvancedSearch = ko.observable(false);
		
		this.currentMessage = App.Cache.currentMessage;
		this.currentMessage.subscribe(function () {
			this.selector.itemSelected(this.currentMessage());
		}, this);
	
		this.folderList = App.Cache.folderList;
		this.folderList.subscribe(this.onFolderListSubscribe, this);
		this.folderFullName = ko.observable('');
	
		this.uidList = App.Cache.uidList;
		this.uidList.subscribe(function () {
			if (this.uidList().searchCount() >= 0)
			{
				this.oPageSwitcher.setCount(this.uidList().searchCount());
			}
		}, this);
	
		this.collection = App.Cache.messages;
		this.search = ko.observable('');
	
		this.isEmptyList = ko.computed(function () {
			return this.collection().length === 0;
		}, this);
	
		this.isSearch = ko.computed(function () {
			return this.search().length > 0;
		}, this);
	
		this.isLoading = App.Cache.messagesLoading;
	
		this.isError = App.Cache.messagesLoadingError;
		
		this.visibleInfoSearchLoading = ko.computed(function () {
			return this.isSearch() && this.isLoading();
		}, this);
		this.visibleInfoSearchList = ko.computed(function () {
			return this.isSearch() && !this.isLoading() && !this.isEmptyList();
		}, this);
		this.visibleInfoMessageListEmpty = ko.computed(function () {
			return !this.isLoading() && !this.isSearch() && this.isEmptyList() && !this.isError();
		}, this);
		this.visibleInfoSearchEmpty = ko.computed(function () {
			return this.isSearch() && this.isEmptyList() && !this.isError() && !this.isLoading();
		}, this);
		this.visibleInfoMessageListError = ko.computed(function () {
			return !this.isSearch() && this.isError();
		}, this);
		this.visibleInfoSearchError = ko.computed(function () {
			return this.isSearch() && this.isError();
		}, this);
	
		this.searchText = ko.computed(function () {
	
			return Utils.i18n('MAILBOX/INFO_SEARCH_RESULT', {
				'SEARCH': this.search(),
				'FOLDER': this.folderList().currentFolder() ? this.folderList().currentFolder().displayName() : ''
			});
			
		}, this);
	
		this.collectionChecked = ko.computed(function () {
			
			var aChecked = _.filter(this.collection(), function (oMessage) {
				return !oMessage.deleted() && oMessage.checked();
			});
	
			if (aChecked.length === 0 && App.Cache.currentMessage() && !App.Cache.currentMessage().deleted())
			{
				aChecked = [App.Cache.currentMessage()];
			}
	
			return aChecked;
		}, this);
	
		this.collectionCheckedUids = ko.computed(function () {
			return _.map(this.collectionChecked(), function (oMessage) {
				return oMessage.uid();
			});
		}, this);
	
		this.isEnableGroupOperations = ko.observable(false).extend({'throttle': 250});
	
		this.selector = new CSelector(this.collection, _.bind(this.routeForMessage, this),
			_.bind(this.onDeletePress, this), _.bind(this.onMessageDblClick, this));
	
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
				sUid = this.currentMessage() ? this.currentMessage().uid() : '',
				sSearch = this.search()
			;
			
			if (!this.pageSwitcherLocked())
			{
				this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch);
			}
		}, this);
		
		// to the message list does not twitch
		if ($.browser.mozilla || $.browser.msie)
		{
			this.listChangedThrottle = ko.observable(false).extend({'throttle': 10});
		}
		else
		{
			this.listChangedThrottle = ko.observable(false);
		}
		
		this.listChanged = ko.computed(function () {
			return [
				this.folderFullName(),
				this.search(),
				this.oPageSwitcher.currentPage()
			];
		}, this);
		
		this.listChanged.subscribe(function() {
			this.listChangedThrottle(!this.listChangedThrottle());
		}, this);
	}
	
	/**
	 * @param {string} sFolder
	 * @param {number} iPage
	 * @param {string} sUid
	 * @param {string} sSearch
	 */
	CMessageListViewModel.prototype.changeRoutingForMessageList = function (sFolder, iPage, sUid, sSearch)
	{
		App.Routing.setHash(App.Links.mailbox(sFolder, iPage, sUid, sSearch));
	};
	
	/**
	 * @param {CMessageModel} oMessage
	 */
	CMessageListViewModel.prototype.onMessageDblClick = function (oMessage)
	{
		var
			oFolder = this.folderList().getFolderByFullName(oMessage.folder())
		;
	
		if (oFolder.type() === Enums.FolderTypes.Drafts)
		{
			App.Routing.setHash(App.Links.composeFromMessage('drafts', oMessage.folder(), oMessage.uid()));
		}
		else
		{
			Utils.WindowOpener.openMessage(oMessage);
		}
	};
	
	CMessageListViewModel.prototype.onFolderListSubscribe = function ()
	{
		this.folderList().setCurrentFolder(this.folderFullName());
		this.requestMessageList();
	};
	
	/**
	 * @param {Array} aParams
	 */
	CMessageListViewModel.prototype.onRoute = function (aParams)
	{
		var oParams = App.Links.parseMailbox(aParams);
	
		this.pageSwitcherLocked(true);
		if (this.folderFullName() !== oParams.Folder || this.search() !== oParams.Search)
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
			App.Routing.replaceHash(App.Links.mailbox(oParams.Folder, this.oPageSwitcher.currentPage(), oParams.Uid, oParams.Search));
		}
		
		this.folderFullName(oParams.Folder);
		this.search(oParams.Search);
		this.searchInput(this.search());
	
		this.folderList().setCurrentFolder(this.folderFullName());
		this.requestMessageList();
	};
	
	CMessageListViewModel.prototype.requestMessageList = function ()
	{
		var
			sFullName = this.folderList().currentFolderFullName(),
			iPage = this.oPageSwitcher.currentPage()
		;
		
		if (sFullName.length > 0)
		{
			App.Cache.changeCurrentMessageList(sFullName, iPage, this.search());
		}
	};
	
	CMessageListViewModel.prototype.calculateSearchStringFromAdvancedForm  = function ()
	{
		var
			sFrom = this.searchInputFrom(),
			sTo = this.searchInputTo(),
			sSubject = this.searchInputSubject(),
			sText = this.searchInputText(),
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
	
		return aOutput.join(' ');
	};
	
	CMessageListViewModel.prototype.onSearchClick = function ()
	{
		var
			sFolder = this.folderList().currentFolderFullName(),
			sUid = this.currentMessage() ? this.currentMessage().uid() : '',
			iPage = 1,
			sSearch = this.searchInput()
		;
	
		if (this.bAdvancedSearch())
		{
			sSearch = this.calculateSearchStringFromAdvancedForm();
			this.searchInput(sSearch);
			
			this.bAdvancedSearch(false);
		}
		
		this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch);
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
		
		this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch);
	};
	
	CMessageListViewModel.prototype.onStopSearchClick = function ()
	{
		this.onClearSearchClick();
	};
	
	/**
	 * @param {Object} oMessage
	 */
	CMessageListViewModel.prototype.routeForMessage = function (oMessage)
	{
		if (oMessage !== null)
		{
			var
				sFolder = this.folderList().currentFolderFullName(),
				iPage = this.oPageSwitcher.currentPage(),
				sUid = oMessage.uid(),
				sSearch = this.search()
			;
			
			if (sUid !== '')
			{
				this.changeRoutingForMessageList(sFolder, iPage, sUid, sSearch);
			}
		}
	};
	
	/**
	 * @param {Object} $viewModel
	 */
	CMessageListViewModel.prototype.onApplyBindings = function ($viewModel)
	{
		var self = this;
	
		$('.message_list', $viewModel)
			.on('click', '.message_sub_list .item .flag', function (oEvent)
			{
				self.onFlagClick(ko.dataFor(this));
				if (oEvent && oEvent.stopPropagation)
				{
					oEvent.stopPropagation();
				}
			})
			.on('dblclick', '.message_sub_list .item .flag', function (oEvent)
			{
				if (oEvent && oEvent.stopPropagation)
				{
					oEvent.stopPropagation();
				}
			})
		;
		
		this.selector.initOnApplyBindings(
			'.message_sub_list .item',
			'.message_sub_list .selected.item',
			'.message_sub_list .item .custom_checkbox',
			$('.message_list', $viewModel),
			$('.message_list .scroll-inner', $viewModel)
		);
			
	};
	
	/**
	 * Puts / removes the message flag by clicking on it.
	 *
	 * @param {Object} oMessage
	 */
	CMessageListViewModel.prototype.onFlagClick = function (oMessage)
	{
		App.Cache.executeGroupOperation('MessageSetFlagged', [oMessage.uid()], 'flagged', !oMessage.flagged());
	};
	
	/**
	 * Marks the selected messages read.
	 */
	CMessageListViewModel.prototype.executeMarkAsRead = function ()
	{
		App.Cache.executeGroupOperation('MessageSetSeen', this.collectionCheckedUids(), 'seen', true);
	};
	
	/**
	 * Marks the selected messages unread.
	 */
	CMessageListViewModel.prototype.executeMarkAsUnread = function ()
	{
		App.Cache.executeGroupOperation('MessageSetSeen', this.collectionCheckedUids(), 'seen', false);
	};
	
	/**
	 * Sets flag for messages.
	 */
	CMessageListViewModel.prototype.executeFlag = function ()
	{
		App.Cache.executeGroupOperation('MessageSetFlagged', this.collectionCheckedUids(), 'flagged', true);
	};
	
	/**
	 * Removes flag for messages.
	 */
	CMessageListViewModel.prototype.executeUnflag = function ()
	{
		App.Cache.executeGroupOperation('MessageSetFlagged', this.collectionCheckedUids(), 'flagged', false);
	};
	
	/**
	 * Marks Read all messages in a folder.
	 */
	CMessageListViewModel.prototype.executeMarkAllRead = function ()
	{
		App.Cache.executeGroupOperation('MessageSetAllSeen', [], 'seen', true);
	};
	
	/**
	 * Moves the selected messages in the current folder in the specified.
	 * 
	 * @param {string} sToFolder
	 */
	CMessageListViewModel.prototype.executeMoveToFolder = function (sToFolder)
	{
		App.Cache.moveMessagesToFolder(sToFolder, this.collectionCheckedUids());
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
	
		this.deleteMessages(aUids);
	};
	
	/**
	 * Calls for the selected messages delete operation. Called by the mouse click on the delete button.
	 */
	CMessageListViewModel.prototype.executeDelete = function ()
	{
		this.deleteMessages(this.collectionCheckedUids());
	};
	
	/**
	 * Moves the specified messages in the current folder to the Trash or delete permanently 
	 * if the current folder is Trash or Spam.
	 * 
	 * @param {Array} aUids
	 */
	CMessageListViewModel.prototype.deleteMessages = function (aUids)
	{
		var
			sCurrFolder = this.folderList().currentFolderFullName(),
			oTrash = this.folderList().trashFolder(),
			bInTrash =(oTrash && sCurrFolder === oTrash.fullName()),
			oSpam = this.folderList().spamFolder(),
			bInSpam = (oSpam && sCurrFolder === oSpam.fullName()),
			sConfirm = Utils.i18n('MAILBOX/CONFIRM_MESSAGES_DELETE'),
			fDeleteMessages = function (bResult) {
				if (bResult)
				{
					App.Cache.deleteMessages(aUids);
				}
			}
		;
		
		if (bInSpam)
		{
			App.Cache.deleteMessages(aUids);
		}
		else if (bInTrash)
		{
			App.Screens.showPopup(ConfirmPopup, [sConfirm, fDeleteMessages]);
		}
		else if (oTrash)
		{
			App.Cache.moveMessagesToFolder(oTrash.fullName(), this.collectionCheckedUids());
		}
	};
	
	/**
	 * Moves the selected messages from the current folder to the folder Spam.
	 */
	CMessageListViewModel.prototype.executeSpam = function ()
	{
		var
			sSpamFullName = this.folderList().spamFolderFullName()
		;
	
		if (this.folderList().currentFolderFullName() !== sSpamFullName)
		{
			App.Cache.moveMessagesToFolder(sSpamFullName, this.collectionCheckedUids());
		}
	};
	
	/**
	 * Moves the selected messages from the Spam folder to folder Inbox.
	 */
	CMessageListViewModel.prototype.executeNotSpam = function ()
	{
		var
			oInbox = this.folderList().inboxFolder()
		;
	
		if (oInbox && this.folderList().currentFolderFullName() !== oInbox.fullName())
		{
			App.Cache.moveMessagesToFolder(oInbox.fullName(), this.collectionCheckedUids());
		}
	};
	
	CMessageListViewModel.prototype.fillAdvancedSearch = function ()
	{
		this.searchInputFrom('');
		this.searchInputTo('');
		this.searchInputSubject('');
		this.searchInputText('');
		this.bAdvancedSearch(true);
	};
	
	
	/**
	 * @constructor
	 */
	function CMessagePaneViewModel()
	{
		this.singleMode = ko.observable(AppData.SingleMode);
		this.isLoading = ko.observable(false);
		
		this.messages = App.Cache.messages;
		this.messages.subscribe(this.onMessagesSubscribe, this);
		this.currentMessage = App.Cache.currentMessage;
		this.currentMessage.subscribe(this.onCurrentMessageSubscribe, this);
		AppData.User.defaultTimeFormat.subscribe(this.onCurrentMessageSubscribe, this);
		
		this.isCurrentMessage = ko.computed(function () {
			return !!this.currentMessage();
		}, this);
		
		this.isCurrentMessageLoaded = ko.computed(function () {
			return this.isCurrentMessage() && !this.isLoading();
		}, this);
		
		this.visibleNoMessageSelectedText = ko.computed(function () {
			return this.messages().length > 0 && !this.isCurrentMessage();
		}, this);
	
		this.isEnableReply = this.isCurrentMessageLoaded;
		this.isEnableReplyAll = this.isCurrentMessageLoaded;
		this.isEnableForward = this.isCurrentMessageLoaded;
		this.isEnablePrint = this.isCurrentMessageLoaded;
		this.isEnableSave = this.isCurrentMessage;
	
		this.prevMessageCommand = Utils.createCommand(this, this.executePrevMessage);
		this.nextMessageCommand = Utils.createCommand(this, this.executeNextMessage);
		this.replyCommand = Utils.createCommand(this, this.executeReply, this.isEnableReply);
		this.replyAllCommand = Utils.createCommand(this, this.executeReplyAll, this.isEnableReplyAll);
		this.forwardCommand = Utils.createCommand(this, this.executeForward, this.isEnableForward);
		this.printCommand = Utils.createCommand(this, this.executePrint, this.isEnablePrint);
		this.saveCommand = Utils.createCommand(this, this.executeSave, this.isEnableSave);
	
		this.ical = ko.observable(null);
		this.vcard = ko.observable(null);
	
		this.visiblePicturesControl = ko.observable(false);
		this.visibleShowPicturesLink = ko.observable(false);
		this.visibleAppointmentInfo = ko.computed(function () {
			return this.ical() !== null;
		}, this);
		this.visibleVcardInfo = ko.computed(function () {
			return this.vcard() !== null;
		}, this);
		
		this.sesitivityText = ko.computed(function () {
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
	
		this.isVisibleReplyTool = ko.computed(function () {
			var oCurrFolder = App.Cache.folderList().currentFolder();
			return (oCurrFolder && oCurrFolder.fullName().length > 0 &&
				oCurrFolder.type() !== Enums.FolderTypes.Drafts &&
				oCurrFolder.type() !== Enums.FolderTypes.Sent);
		}, this);
	
		this.isVisibleForwardTool = ko.computed(function () {
			var oCurrFolder = App.Cache.folderList().currentFolder();
			return (oCurrFolder && oCurrFolder.fullName().length > 0 &&
				oCurrFolder.type() !== Enums.FolderTypes.Drafts);
		}, this);
	
		this.uid = ko.observable('');
		this.subject = ko.observable('');
		this.from = ko.observable('');
		this.fullFrom = ko.observable('');
		this.to = ko.observable('');
		this.cc = ko.observable('');
		this.bcc = ko.observable('');
		this.allRecipients = ko.observable('');
		this.fullDate = ko.observable('');
		this.midDate = ko.observable('');
		
		this.fromEmail = ko.observable('');
		this.fromName = ko.observable('');
		this.fromExistsInContacts = ko.observable(false);
		this.fromContactInfoReceived = ko.observable(false);
		this.fromContact = ko.observable(new CContactModel());
		this.hasFromContact = ko.computed(function () {
			return this.fromContactInfoReceived() && this.fromExistsInContacts();
		}, this);
		this.shortFromToDisplay = ko.computed(function () {
			var sFrom = this.from();
			
			if (this.fromName() === '' && this.hasFromContact() && this.fromContact().displayName() !== '')
			{
				sFrom = this.fromContact().displayName();
			}
			
			return sFrom;
		}, this);
		
		this.visibleAddToContacts = ko.computed(function () {
			return this.fromContactInfoReceived() && !this.fromExistsInContacts();
		}, this);
	
		this.textBody = ko.observable('');
		this.textBodyForNewWindow = ko.observable('');
		this.domTextBody = ko.observable(null);
	
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
	
		this.detailsVisible = ko.observable(false);
	
		this.hasNotInlineAttachments = ko.computed(function () {
			return this.notInlineAttachments().length > 0;
		}, this);
		this.hasBodyText = ko.computed(function () {
			return this.textBody().length > 0;
		}, this);
	
		this.visibleAddMenu = ko.observable(false);
		
		this.replyText = ko.observable('');
		this.replyTextFocus = ko.observable(false);
		this.replyPaneVisible = ko.computed(function () {
			return this.currentMessage() && this.currentMessage().completelyFilled();
		}, this);
		this.replySendingStarted = ko.observable(false);
		this.replySavingStarted = ko.observable(false);
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
		
		this.domPreviewPane = ko.observable(null);
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
		
		//fix message body height
		ko.computed(function () {
			var
				oPreviewPane = this.domPreviewPane(),
				oMessageHeader = this.domMessageHeader(),
				oQuickReply = this.domQuickReply()
			;
	
			this.detailsVisible();
			this.currentMessage();
			this.replyPaneVisible();
			this.replyTextFocusThrottled();
	
			if (oPreviewPane && oMessageHeader && oQuickReply)
			{
				_.delay(function () {
					var
						iMessageHeader = oMessageHeader.is(':visible') ? oMessageHeader.outerHeight() : 0,
						iQuickReply = oQuickReply.is(':visible') ? oQuickReply.outerHeight() : 0
					;
					
					oPreviewPane.css({
						'padding-top': iMessageHeader,
						'margin-top': -iMessageHeader,
	
						'padding-bottom': iQuickReply,
						'margin-bottom': -iQuickReply
					});
				}, 300);
			}
	
		}, this);
		
		this.viewAllMailsWithContactBinded = _.bind(this.viewAllMailsWithContact, this);
	}
	
	CMessagePaneViewModel.prototype.notifySender = function ()
	{
		if (this.currentMessage() && this.currentMessage().readingConfirmation() !== '')
		{
			App.Ajax.send({
				'Action': 'MessageSendConfirmation',
				'Confirmation': this.currentMessage().readingConfirmation(),
				'Subject': Utils.i18n('MESSAGE/RETURN_RECEIPT_MAIL_SUBJECT'),
				'Text': Utils.i18n('MESSAGE/RETURN_RECEIPT_MAIL_TEXT', {
					'EMAIL': this.fullFrom(),
					'SUBJECT': this.subject()
				})
			});
			this.currentMessage().readingConfirmation('');
		}
	};
	
	CMessagePaneViewModel.prototype.viewAllMailsWithContact = function ()
	{
		if (AppData.SingleMode && window.opener && window.opener.App)
		{
			window.opener.App.Api.searchMessagesInCurrentFolder('email:' + this.fromEmail());
			window.close();
		}
		else
		{
			App.Api.searchMessagesInCurrentFolder('email:' + this.fromEmail());
		}
	};
	
	CMessagePaneViewModel.prototype.onMessagesSubscribe = function ()
	{
		if (!this.currentMessage() && this.uid().length > 0)
		{
			App.Cache.setCurrentMessage(this.uid());
		}
	};
	
	/**
	 * @param {Object} oContact
	 */
	CMessagePaneViewModel.prototype.onFromContactResponse = function (oContact)
	{
		if (oContact)
		{
			this.fromContact(oContact);
			this.fromExistsInContacts(true);
		}
		else
		{
			this.fromContact(new CContactModel());
			this.fromExistsInContacts(false);
		}
		this.fromContactInfoReceived(true);
	};
	
	CMessagePaneViewModel.prototype.onCurrentMessageSubscribe = function ()
	{
		var
			oMessage = this.currentMessage(),
			sFullTo = '',
			sFullCc = '',
			sFullBcc = '',
			aRecipients = []
		;
	
		this.replyText('');
		this.replyDraftUid('');
	
		if (oMessage && this.uid() === oMessage.uid())
		{
			this.subject(oMessage.subject());
			this.from(oMessage.oFrom.getDisplay());
			this.fromEmail(oMessage.oFrom.getFirstEmail());
			this.fromName(oMessage.oFrom.getFirstName());
			this.fromContactInfoReceived(false);
	
			if (oMessage.completelyFilled() && this.fromEmail() && this.fromEmail() !== '')
			{
				App.ContactsCache.getContactByEmail(this.fromEmail(), this.onFromContactResponse, this);
			}
	
			this.fullFrom(oMessage.oFrom.getFull());
			this.to(oMessage.oTo.getFull());
			this.cc(oMessage.oCc.getFull());
			this.bcc(oMessage.oBcc.getFull());
	
			sFullTo = oMessage.oTo.getDisplay();
			sFullCc = oMessage.oCc.getDisplay();
			sFullBcc = oMessage.oBcc.getDisplay();
			if (sFullTo.length > 0)
			{
				aRecipients.push(sFullTo);
			}
			if (sFullCc.length > 0)
			{
				aRecipients.push(sFullCc);
			}
			if (sFullBcc.length > 0)
			{
				aRecipients.push(sFullBcc);
			}
			this.allRecipients(aRecipients.join(', '));
	
			this.midDate(oMessage.oDateModel.getMidDate());
			this.fullDate(oMessage.oDateModel.getFullDate());
	
			this.isLoading(oMessage.uid() !== '' && !oMessage.completelyFilled());
	
			this.setMessageBody(oMessage);
	
			this.attachments(oMessage.attachments());
	
			this.visiblePicturesControl(oMessage.hasExternals() && !oMessage.isExternalsAlwaysShown());
			this.visibleShowPicturesLink(!oMessage.isExternalsShown());
	
			this.ical(oMessage.ical());
			this.vcard(oMessage.vcard());
			
			if (!oMessage.completelyFilled())
			{
				oMessage.completelyFilledSubscription = oMessage.completelyFilled.subscribe(this.onCurrentMessageSubscribe, this);
			}
			else if (oMessage.completelyFilledSubscription)
			{
				oMessage.completelyFilledSubscription.dispose();
				oMessage.completelyFilledSubscription = undefined;
			}
		}
		else
		{
			this.isLoading(false);
			$(this.domTextBody()).empty();
			
			// cannot use removeAll, because the attachments of messages are passed by reference 
			// and the call to removeAll removes attachments from message in the cache too.
			this.attachments([]);
			this.visiblePicturesControl(false);
			this.visibleShowPicturesLink(false);
			this.ical(null);
			this.vcard(null);
		}
	};
	
	/**
	 * @param {Object} oMessage
	 */
	CMessagePaneViewModel.prototype.setMessageBody = function (oMessage)
	{
		if (oMessage)
		{
			this.textBody(oMessage.text());
			// TODO verify the need for a method getDomText()
			$(this.domTextBody())
				.empty()
				.append(oMessage.getDomText().html())
			;
		}
	};
	
	/**
	 * @param {Array} aParams
	 */
	CMessagePaneViewModel.prototype.onRoute = function (aParams)
	{
		var oParams = App.Links.parseMailbox(aParams);
		
		this.uid(oParams.Uid);
		App.Cache.setCurrentMessage(this.uid());
	};
	
	CMessagePaneViewModel.prototype.showPictures = function ()
	{
		App.Cache.showExternalPictures(false);
		this.visibleShowPicturesLink(false);
		this.setMessageBody(this.currentMessage());
	};
	
	CMessagePaneViewModel.prototype.alwaysShowPictures = function ()
	{
		var
			sEmail = this.currentMessage() ? this.currentMessage().oFrom.getFirstEmail() : ''
		;
	
		if (sEmail.length > 0)
		{
			App.Ajax.send({
				'Action': 'EmailSafety',
				'Email': sEmail
			});
		}
	
		App.Cache.showExternalPictures(true);
		this.visiblePicturesControl(false);
		this.setMessageBody(this.currentMessage());
	};
	
	CMessagePaneViewModel.prototype.showDetails = function ()
	{
		this.detailsVisible(true);
	};
	
	CMessagePaneViewModel.prototype.hideDetails = function ()
	{
		this.detailsVisible(false);
	};
	
	CMessagePaneViewModel.prototype.openInNewWindow = function ()
	{
		var
			oCurrFolder = App.Cache.folderList().currentFolder(),
			bDraftFolder = (oCurrFolder.type() === Enums.FolderTypes.Drafts)
		;
		
		Utils.WindowOpener.openMessage(this.currentMessage(), bDraftFolder);
	};
	
	CMessagePaneViewModel.prototype.addToContacts = function ()
	{
		var
			oParameters = {
				'Action': 'ContactCreate',
				'PrimaryEmail': 'Home',
				'UseFriendlyName': '1',
				'FullName': this.fromName(),
				'HomeEmail': this.fromEmail()
			}
		;
		
		App.Ajax.send(oParameters, this.onAddToContactsResponse, this);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CMessagePaneViewModel.prototype.onAddToContactsResponse = function (oData, oParameters)
	{
		if (oData.Result && oParameters.HomeEmail !== '' && oParameters.HomeEmail === this.fromEmail())
		{
			App.Api.showReport(Utils.i18n('CONTACTS/REPORT_CONTACT_SUCCESSFULLY_ADDED'));
			App.ContactsCache.clearInfoAboutEmail(this.fromEmail());
			App.ContactsCache.getContactByEmail(this.fromEmail(), this.onFromContactResponse, this);
		}
	};
	
	CMessagePaneViewModel.prototype.executePrevMessage = function ()
	{
	};
	
	CMessagePaneViewModel.prototype.executeNextMessage = function ()
	{
	};
	
	/**
	 * @param {string} sReplyType
	 */
	CMessagePaneViewModel.prototype.executeReplyOrForward = function (sReplyType)
	{
		if (this.currentMessage())
		{
			App.MessageSender.setReplyData(this.replyText(), this.replyDraftUid());
			this.replyText('');
			this.replyDraftUid('');
			App.Routing.setHash(App.Links.composeFromMessage(sReplyType, this.currentMessage().folder(), 
				this.currentMessage().uid()));
		}
	};
	
	CMessagePaneViewModel.prototype.executeReply = function ()
	{
		this.executeReplyOrForward(Enums.ReplyType.Reply);
	};
	
	CMessagePaneViewModel.prototype.executeReplyAll = function ()
	{
		this.executeReplyOrForward(Enums.ReplyType.ReplyAll);
	};
	
	CMessagePaneViewModel.prototype.executeForward = function ()
	{
		this.executeReplyOrForward(Enums.ReplyType.Forward);
	};
	
	CMessagePaneViewModel.prototype.executePrint = function ()
	{
		var
			oWin = Utils.WindowOpener.open('', this.subject() + '-print'),
			oDomText = this.currentMessage().getDomText(Utils.getAppPath()),
			sHtml = ''
		;
		
		this.textBodyForNewWindow(oDomText.html());
		sHtml = $(this.domMessageForPrint()).html();
		
		$(oWin.document.body).html(sHtml);
		oWin.print();
	};
	
	CMessagePaneViewModel.prototype.executeSave = function ()
	{
		if (this.currentMessage())
		{
			App.downloadByUrl(this.currentMessage().download());
		}
	};
	
	CMessagePaneViewModel.prototype.changeAddMenuVisibility = function ()
	{
		var bVisibility = !this.visibleAddMenu();
		this.visibleAddMenu(bVisibility);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CMessagePaneViewModel.prototype.onMessageSendResponse = function (oData, oParameters)
	{
		var oResData = App.MessageSender.onMessageSendResponse(oData, oParameters);
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
				this.replySavingStarted(false);
				if (oResData.Result)
				{
					this.replyDraftUid(oResData.NewUid);
				}
				break;
		}
	};
	
	CMessagePaneViewModel.prototype.sendReplyMessage = function ()
	{
		if (this.replyText() !== '')
		{
			var sText = App.MessageSender.getHtmlFromText(this.replyText());
			
			this.replySendingStarted(true);
			App.MessageSender.sendReplyMessage('MessageSend', sText, this.replyDraftUid(), 
				this.onMessageSendResponse, this);
		}
	};
	
	CMessagePaneViewModel.prototype.saveReplyMessage = function ()
	{
		if (this.replyText() !== '')
		{
			var sText = App.MessageSender.getHtmlFromText(this.replyText());
			
			this.replySavingStarted(true);
			App.MessageSender.sendReplyMessage('MessageSave', sText, this.replyDraftUid(), 
				this.onMessageSendResponse, this);
		}
	};
	
	
	/**
	 * @constructor
	 */
	function CMailViewModel()
	{
		this.folderList = App.Cache.folderList;
		this.domFolderList = ko.observable(null);
	
		this.oFolderList = new CFolderListViewModel();
		this.oMessageList = new CMessageListViewModel();
		this.oMessagePane = new CMessagePaneViewModel();
	
		this.isEnableGroupOperations = this.oMessageList.isEnableGroupOperations;
		
		this.composeLink = ko.observable(App.Routing.buildHashFromArray(App.Links.compose()));
	
		this.checkMailCommand = Utils.createCommand(App.Cache, App.Cache.executeCheckMail);
		this.checkMailStarted = App.Cache.checkMailStarted;
		this.markAsReadCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeMarkAsRead, this.isEnableGroupOperations);
		this.markAsUnreadCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeMarkAsUnread, this.isEnableGroupOperations);
		// this.flagCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeFlag, this.isEnableGroupOperations); //deprecated
		// this.unflagCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeUnflag, this.isEnableGroupOperations); //deprecated
		this.markAllReadCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeMarkAllRead);
		this.moveToFolderCommand = Utils.createCommand(this, Utils.emptyFunction, this.isEnableGroupOperations);
		this.deleteCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeDelete, this.isEnableGroupOperations);
		this.emptyTrashCommand = Utils.createCommand(App.Cache, App.Cache.executeEmptyTrash);
		this.emptySpamCommand = Utils.createCommand(App.Cache, App.Cache.executeEmptySpam);
		this.spamCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeSpam, this.isEnableGroupOperations);
		this.notSpamCommand = Utils.createCommand(this.oMessageList, this.oMessageList.executeNotSpam, this.isEnableGroupOperations);
	//	this.replyCommand = Utils.createCommand(this.oMessagePane, this.oMessagePane.executeReply, this.oMessagePane.isEnableReply);
	//	this.replyAllCommand = Utils.createCommand(this.oMessagePane, this.oMessagePane.executeReplyAll, this.oMessagePane.isEnableReplyAll);
	//	this.forwardCommand = Utils.createCommand(this.oMessagePane, this.oMessagePane.executeForward, this.oMessagePane.isEnableForward);
	//	this.printCommand = Utils.createCommand(this.oMessagePane, this.oMessagePane.executePrint, this.oMessagePane.isEnablePrint);
	//	this.saveCommand = Utils.createCommand(this.oMessagePane, this.oMessagePane.executeSave, this.oMessagePane.isEnableSave);
	
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
		
		this.isTrashFolder = ko.computed(function () {
			return this.folderList().currentFolderType() === Enums.FolderTypes.Trash;
		}, this);
	
	}
	
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
		this.oMessageList.selector.useKeyboardKeys(true);
	};
	
	CMailViewModel.prototype.onHide = function ()
	{
		this.oMessageList.selector.useKeyboardKeys(false);
	};
	
	CMailViewModel.prototype.onApplyBindings = function ()
	{
		var self = this;
		
		this.oMessageList.onApplyBindings(this.$viewModel);
	
		$(this.domFolderList()).on('click', 'span.folder', function () {
			self.oMessageList.executeMoveToFolder($(this).data('folder'));
		});
	
		$(this.$viewModel).on('click', 'a', function () {
			var sHref = $(this).attr('href');
			if (sHref && 'mailto:' === sHref.toString().toLowerCase().substr(0, 7))
			{
				App.Api.openComposeMessage(sHref.toString().substr(7));
				return false;
			}
	
			return true;
		});
	
		$(document).on('keyup', function(ev) {
			if (ev && ev.keyCode === Enums.Key.s) {
				self.searchFocus();
			}
		});
	};
	
	CMailViewModel.prototype.dragAndDronHelper = function (oMessage)
	{
		if (oMessage)
		{
			oMessage.checked(true);
		}
	
		var
			oHelper = Utils.draggebleMessages(),
			nCount = this.oMessageList.selector.listCheckedOrSelected().length,
			aUids = 0 < nCount ? _.map(this.oMessageList.selector.listCheckedOrSelected(), function (oItem) {
				return oItem.uid();
			}) : []
		;
	
		oHelper.data('p7-message-list-folder', this.folderList().currentFolderFullName());
		oHelper.data('p7-message-list-uids', aUids);
	
		$('.count-text', oHelper).text(Utils.i18n('MAILBOX/DRAG_TEXT_PLURAL', {
			'COUNT': nCount
		}, null, nCount));
	
		return oHelper;
	};
	
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
				this.oMessageList.executeMoveToFolder(oToFolder.fullName()); // TODO
			}
		}
	};
	
	CMailViewModel.prototype.searchFocus = function ()
	{
		if (this.oMessageList.selector.useKeyboardKeys() && !Utils.inFocus())
		{
			this.oMessageList.isFocused(true);
		}
	};
	
	/**
	 * @constructor
	 */
	function CComposeViewModel()
	{
		this.folderList = App.Cache.folderList;
		this.folderList.subscribe(function () {
			this.getMessageOnRoute();
		}, this);
	
		this.singleMode = ko.observable(AppData.SingleMode);
		this.isDemo = ko.observable(AppData.User.IsDemo);
		
		this.sending = ko.observable(false);
		this.saving = ko.observable(false);
	
		this.oHtmlEditor = new CHtmlEditorViewModel(true);
	
		this.visibleBcc = ko.observable(false);
		this.visibleBcc.subscribe(function () {
			this.computeHeight();
		}, this);
		this.visibleCc = ko.observable(false);
		this.visibleCc.subscribe(function () {
			this.computeHeight();
		}, this);
		this.visibleCounter = ko.observable(false);
	
		this.readingConfirmation = ko.observable(false);
		this.saveMailInSentItems = ko.observable(true);
		this.useSaveMailInSentItems = ko.observable(false);
	
		this.composeUploaderButton = ko.observable(null);
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
	
		this.accounts = AppData.Accounts.Collection;
		this.visibleFrom = ko.computed(function () {
			return this.accounts().length > 1;
		}, this);
		this.selectedAccountId = ko.observable(AppData.Accounts.currentId());
	
		this.toAddr = ko.observable('').extend({'reversible': true});
		this.ccAddr = ko.observable('').extend({'reversible': true});
		this.bccAddr = ko.observable('').extend({'reversible': true});
		this.subject = ko.observable('').extend({'reversible': true});
		this.counter = ko.observable(0);
		this.commitedTextBody = ko.observable('');
		this.textBody = ko.observable('');
		this.textBody.subscribe(function () {
			this.oHtmlEditor.setText(this.textBody());
			this.commitedTextBody(this.oHtmlEditor.getText());
		}, this);
	
		this.toAddrFocused = ko.observable(false);
		this.ccAddrFocused = ko.observable(false);
		this.bccAddrFocused = ko.observable(false);
		this.subjectFocused = ko.observable(false);
	
		this.draftUid = ko.observable('');
		this.draftInfo = ko.observableArray([]);
		this.routeType = ko.observable('');
		this.routeParams = ko.observableArray([]);
		this.inReplyTo = ko.observable('');
		this.references = ko.observable('');
	
		this.uploadAttachmentsTimer = -1;
		this.messageUploadAttachmentsStarted = ko.observable(false);
		this.messageUploadAttachmentsStarted.subscribe(function () {
			clearTimeout(this.uploadAttachmentsTimer);
			if (this.messageUploadAttachmentsStarted())
			{
				this.uploadAttachmentsTimer = setTimeout(function () {
					App.Api.showLoading(Utils.i18n('COMPOSE/INFO_ATTACHMENTS_LOADING'));
				}, 4000);
			}
			else
			{
				App.Api.hideLoading();
			}
		}, this);
		
		this.attachments = ko.observableArray([]);
		this.attachmentsChanged = ko.observable(false);
		this.attachments.subscribe(function () {
			// this.oHtmlEditor.resize();
			this.computeHeight();
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
		this.autoSaveTimer = ko.observable(-1);
	
		this.backToListCommand = Utils.createCommand(this, this.executeBackToList);
		this.sendCommand = Utils.createCommand(this, this.executeSend, this.isEnableSending);
		this.saveCommand = Utils.createCommand(this, this.executeSaveCommand, this.isEnableSaving);
	
		this.messageForm = ko.observable(null);
		this.messageFields = ko.observable(null);
		
		this.shown = ko.observable(false);
	}
	
	/**
	 * Determines if sending a message is allowed.
	 */
	CComposeViewModel.prototype.isEnableSending = function ()
	{
		var
			bRecipientIsEmpty = this.toAddr().length === 0 &&
				this.ccAddr().length === 0 &&
				this.bccAddr().length === 0,
			bFoldersLoaded = this.folderList().iAccountId !== 0
		;
	
		return bFoldersLoaded && !this.sending() && !bRecipientIsEmpty && this.allAttachmentsUploaded();
	};
	
	/**
	 * Determines if saving a message is allowed.
	 */
	CComposeViewModel.prototype.isEnableSaving = function ()
	{
		var bFoldersLoaded = this.folderList().iAccountId !== 0;
	
		return bFoldersLoaded && !this.sending() && !this.saving();
	};
	
	/**
	 * Executes after applying bindings.
	 */
	CComposeViewModel.prototype.onApplyBindings = function ()
	{
		this.initUploader();
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
			App.Cache.getMessage(sFolderName, sUid, this.onMessageResponse, this);
		}
	
		this.routeParams([]);
	};
	
	/**
	 * Executes if the view model shows. Requests a folder list from the server to know the full names
	 * of the folders Drafts and Sent Items.
	 */
	CComposeViewModel.prototype.onShow = function ()
	{
		this.useSaveMailInSentItems(AppData.User.getUseSaveMailInSentItems());
		this.saveMailInSentItems(AppData.User.getSaveMailInSentItems());
		
		this.oHtmlEditor.initCrea(this.textBody(), '7');
		this.oHtmlEditor.clearImages();
		this.commitedTextBody(this.oHtmlEditor.getText());
	
		this.shown(true);
		this.startAutosave();
		this.focusAfterFilling();
	};
	
	/**
	 * Executes if routing changed.
	 *
	 * @param {Array} aParams
	 */
	CComposeViewModel.prototype.onRoute = function (aParams)
	{
		this.selectedAccountId(AppData.Accounts.currentId());
		
		var
			oAccount = AppData.Accounts.getAccount(this.selectedAccountId()),
			oSignature = oAccount ? oAccount.signature() : null,
			sSignature = (oSignature && oSignature.options()) ? oSignature.signature() : ''
		;
	
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
			case Enums.ReplyType.Forward:
			case 'drafts':
				this.routeParams(aParams);
				if (this.folderList().iAccountId !== 0)
				{
					this.getMessageOnRoute();
				}
				break;
			default:
				if (this.singleMode() && window.opener && window.opener.oMessageParametersFromCompose)
				{
					this.setMessageDataInSingleMode(window.opener.oMessageParametersFromCompose);
					window.opener.oMessageParametersFromCompose = undefined;
				}
				else if (sSignature !== '')
				{
					this.textBody('<br /><br />' + sSignature);
				}
				if (this.routeType() === 'to' && aParams.length > 1)
				{
					this.toAddr(aParams[1]);
				}
				break;
		}
	
		this.visibleCc(this.ccAddr() !== '');
		this.visibleBcc(this.bccAddr() !== '');
		this.commit(this.oHtmlEditor.getText());
		
		this.focusAfterFilling();
	};
	
	CComposeViewModel.prototype.focusAfterFilling = function ()
	{
		if (this.toAddr().length === 0)
		{
			this.toAddrFocused(true);
		}
		else if (this.subject().length === 0)
		{
			this.subjectFocused(true);
		}
		else
		{
			this.oHtmlEditor.setFocus();
		}
	};
	
	/**
	 * Executes if view model was hidden.
	 */
	CComposeViewModel.prototype.onHide = function ()
	{
		this.shown(false);
		this.stopAutosave();
	
		this.oHtmlEditor.closeAllPopups();
	};
	
	/**
	 * Stops autosave.
	 */
	CComposeViewModel.prototype.stopAutosave = function ()
	{
		window.clearInterval(this.autoSaveTimer());
	};
	
	/**
	 * Starts autosave.
	 */
	CComposeViewModel.prototype.startAutosave = function ()
	{
		if (this.shown())
		{
			var fSave = _.bind(this.executeSave, this, true);
			this.stopAutosave();
			if (AppData.App.AutoSave)
			{
				this.autoSaveTimer(window.setInterval(fSave, AppData.App.AutoSaveIntervalSeconds * 1000));
			}
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
				case Enums.ReplyType.Forward:
					oReplyData = App.MessageSender.getReplyDataFromMessage(oMessage, this.routeType(), this.selectedAccountId());
					this.draftInfo(oReplyData.DraftInfo);
					this.draftUid(oReplyData.DraftUid);
					this.toAddr(oReplyData.To);
					this.ccAddr(oReplyData.Cc);
					this.subject(oReplyData.Subject);
					this.textBody(oReplyData.Text);
					this.attachments(oReplyData.Attachments);
					this.inReplyTo(oReplyData.InReplyTo);
					this.references(oReplyData.References);
					break;
				case 'drafts':
					this.draftUid(oMessage.uid());
					this.setDataFromMessage(oMessage);
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
		this.commit(this.oHtmlEditor.getText());
		
		this.focusAfterFilling();
	};
	
	/**
	 * @param {number} iId
	 */
	CComposeViewModel.prototype.changeSelectedAccountId = function (iId)
	{
		if (AppData.Accounts.hasAccountWithId(iId))
		{
			this.selectedAccountId(iId);
		}
	};
	
	/**
	 * @param {Object} oMessage
	 */
	CComposeViewModel.prototype.setDataFromMessage = function (oMessage)
	{
		var sText = oMessage.getDomText().html();
		
		this.draftInfo(oMessage.draftInfo());
		this.inReplyTo(oMessage.inReplyTo());
		this.references(oMessage.references());
		this.changeSelectedAccountId(oMessage.accountId());
		this.toAddr(oMessage.oTo.getFull());
		this.ccAddr(oMessage.oCc.getFull());
		this.bccAddr(oMessage.oBcc.getFull());
		this.subject(oMessage.subject());
		this.attachments(oMessage.attachments());
		this.textBody(sText ? sText : oMessage.text());
		this.selectedImportance(oMessage.importance());
		this.selectedSensitivity(oMessage.sensitivity());
		this.readingConfirmation(oMessage.readingConfirmation());
	};
	
	/**
	 */
	CComposeViewModel.prototype.requestAttachmentsTempName = function ()
	{
		var
			aHash = _.map(this.attachments(), function (oAttach) {
				oAttach.uploadStarted(true);
				return oAttach.hash();
			}),
			oParameters = {
				'Action': 'MessageUploadAttachments',
				'Attachments': aHash
			}
		;
	
		if (aHash.length > 0)
		{
			App.Ajax.send(oParameters, this.onMessageUploadAttachmentsResponse, this);
			this.messageUploadAttachmentsStarted(true);
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CComposeViewModel.prototype.onMessageUploadAttachmentsResponse = function (oData, oParameters)
	{
		if (oData.Result)
		{
			_.each(oData.Result, _.bind(this.setAttachTepmNameByHash, this));
		}
		this.messageUploadAttachmentsStarted(false);
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
		this.changeSelectedAccountId(oParameters.selectedAccountId);
		this.toAddr(oParameters.toAddr);
		this.ccAddr(oParameters.ccAddr);
		this.bccAddr(oParameters.bccAddr);
		this.subject(oParameters.subject);
		this.attachments(_.map(oParameters.attachments, function (oRawAttach)
		{
			var oAttach = new CAttachmentModel();
			oAttach.parse(oRawAttach, this.selectedAccountId());
			return oAttach;
		}, this));
		this.textBody(oParameters.textBody);
		this.selectedImportance(oParameters.selectedImportance);
		this.selectedSensitivity(oParameters.selectedSensitivity);
		this.readingConfirmation(oParameters.readingConfirmation);
	};
	
	CComposeViewModel.prototype.isEmpty = function ()
	{
		var
			sTo = Utils.trim(this.toAddr()),
			sCc = Utils.trim(this.ccAddr()),
			sBcc = Utils.trim(this.bccAddr()),
			sSubject = Utils.trim(this.subject()),
			sText = this.oHtmlEditor.getText(),
			sTextWithoutNodes = Utils.trim(sText
				.replace(/<br *\/{0,1}>/gi, '\n')
				.replace(/<[^>]*>/g, '')
				.replace(/&nbsp;/g, ' '))
		;
		
		return (sTo === '' && sCc === '' && sBcc === '' && sSubject === '' &&
			this.attachments().length === 0 && sTextWithoutNodes === '');
	};
	
	/**
	 * @param {string} sText
	 */
	CComposeViewModel.prototype.commit = function (sText)
	{
		this.toAddr.commit();
		this.ccAddr.commit();
		this.bccAddr.commit();
		this.subject.commit();
		this.commitedTextBody(sText);
		this.attachmentsChanged(false);
	};
	
	CComposeViewModel.prototype.isChanged = function ()
	{
		return this.toAddr.changed() || this.ccAddr.changed() || this.bccAddr.changed() || 
			this.subject.changed() || (this.commitedTextBody() !== this.oHtmlEditor.getText()) || 
			this.attachmentsChanged();
	};
	
	CComposeViewModel.prototype.executeBackToList = function ()
	{
		if (this.isChanged() && !this.isEmpty())
		{
			this.executeSave(true);
		}
		
		if (this.singleMode())
		{
			window.close();
		}
		else if (Enums.Screens.Contacts === App.Routing.previousHash)
		{
			App.Routing.setPreviousHash();
		}
		else
		{
			App.Routing.setLastMailboxHash();
		}
	};
	
	/**
	 * Creates new attachment for upload.
	 *
	 * @param {string} sUid
	 * @param {Object} oFileData
	 */
	CComposeViewModel.prototype.onFileUploadSelect = function (sUid, oFileData)
	{
		var
			oAttach,
			sWarning = Utils.i18n('COMPOSE/UPLOAD_ERROR_FILENAME_SIZE', {'FILENAME': oFileData.FileName})
		;
	
		if (AppData.App.AttachmentSizeLimit > 0 && oFileData.Size > AppData.App.AttachmentSizeLimit)
		{
			App.Screens.showPopup(AlertPopup, [sWarning]);
			return false;
		}
	
		oAttach = new CAttachmentModel();
		oAttach.onUploadSelect(sUid, oFileData);
		this.attachments.push(oAttach);
		this.attachmentsChanged(true);
	
		return true;
	};
	
	/**
	 * Returns attachment found by uid.
	 *
	 * @param {string} sUid
	 */
	CComposeViewModel.prototype.getAttachmentByUid = function (sUid)
	{
		return _.find(this.attachments(), function (oAttach) {
			return oAttach.uploadUid() === sUid;
		});
	};
	
	/**
	 * Finds attachment by uid. Calls it's function to start upload.
	 *
	 * @param {string} sUid
	 */
	CComposeViewModel.prototype.onFileUploadStart = function (sUid)
	{
		var oAttach = this.getAttachmentByUid(sUid);
	
		if (oAttach)
		{
			oAttach.onUploadStart();
		}
	};
	
	/**
	 * Finds attachment by uid. Calls it's function to progress upload.
	 *
	 * @param {string} sUid
	 * @param {number} iUploadedSize
	 * @param {number} iTotalSize
	 */
	CComposeViewModel.prototype.onFileUploadProgress = function (sUid, iUploadedSize, iTotalSize)
	{
		var oAttach = this.getAttachmentByUid(sUid);
	
		if (oAttach)
		{
			oAttach.onUploadProgress(iUploadedSize, iTotalSize);
		}
	};
	
	/**
	 * Finds attachment by uid. Calls it's function to complete upload.
	 *
	 * @param {string} sUid
	 * @param {boolean} bResponseReceived
	 * @param {Object} oData
	 */
	CComposeViewModel.prototype.onFileUploadComplete = function (sUid, bResponseReceived, oData)
	{
		var oAttach = this.getAttachmentByUid(sUid);
	
		if (oAttach)
		{
			oAttach.onUploadComplete(sUid, bResponseReceived, oData ? oData.Result : false, oData ? oData.AccountID : null);
		}
	};
	
	/**
	 * Finds attachment by uid. Calls it's function to cancel upload.
	 *
	 * @param {string} sUid
	 */
	CComposeViewModel.prototype.onAttachmentRemove = function (sUid)
	{
		var oAttach = this.getAttachmentByUid(sUid);
	
		if (this.oJua)
		{
			this.oJua.cancel(sUid);
		}
	
		this.attachments.remove(oAttach);
		this.attachmentsChanged(true);
	};
	
	/**
	 * Initializes file uploader.
	 */
	CComposeViewModel.prototype.initUploader = function ()
	{
		if (this.composeUploaderButton())
		{
			this.oJua = new Jua({
				'action': 'index.php?/Upload/Attachment/',
				'name': 'jua-uploader',
				'queueSize': 2,
				'clickElement': this.composeUploaderButton(),
				'dragAndDropElement': this.composeUploaderDropPlace(),
				'disableAjaxUpload': false,
				'disableDragAndDrop': false,
				'hidden': {
					'Token': function () {
						return AppData.Token;
					},
					'AccountID': function () {
						return AppData.Accounts.currentId();
					}
				},
				'onDragEnter': _.bind(this.composeUploaderDragOver, this, true),
				'onDragLeave': _.bind(this.composeUploaderDragOver, this, false),
				'onBodyDragEnter': _.bind(this.composeUploaderBodyDragOver, this, true),
				'onBodyDragLeave':_.bind(this.composeUploaderBodyDragOver, this, false),
				'onProgress': _.bind(this.onFileUploadProgress, this),
				'onSelect': _.bind(this.onFileUploadSelect, this),
				'onStart': _.bind(this.onFileUploadStart, this),
				'onComplete': _.bind(this.onFileUploadComplete, this)
			});
			this.allowDragNDrop(this.oJua.isDragAndDropSupported());
		}
	};
	
	CComposeViewModel.prototype.getSendSaveParameters = function ()
	{
		var
			oAttachments = App.MessageSender.convertAttachmentsForSending(this.attachments())
		;
	
		_.each(this.oHtmlEditor.uploadedImagePathes(), function (oAttach) {
			oAttachments[oAttach.TempName] = [oAttach.Name, oAttach.CID, '1', '1'];
		});
	
		return {
			DraftInfo: this.draftInfo(),
			DraftUid: this.draftUid(),
			To: this.toAddr(),
			Cc: this.ccAddr(),
			Bcc: this.bccAddr(),
			Subject: this.subject(),
			Text: this.oHtmlEditor.getText(),
			Importance: this.selectedImportance(),
			Sensivity: this.selectedSensitivity(),
			ReadingConfirmation: this.readingConfirmation() ? '1' : '0',
			Attachments: oAttachments,
			InReplyTo: this.inReplyTo(),
			References: this.references()
		};
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CComposeViewModel.prototype.onMessageSendResponse = function (oData, oParameters)
	{
		var oResData = App.MessageSender.onMessageSendResponse(oData, oParameters);
		
		this.commit(oParameters.Text);
		
		switch (oResData.Action)
		{
			case 'MessageSave':
				if (oResData.Result)
				{
					if (oResData.NewUid)
					{
						this.draftUid(oResData.NewUid);
						this.startAutosave();
					}
				}
				this.saving(false);
				break;
			case 'MessageSend':
				if (oResData.Result)
				{
					this.executeBackToList();
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
	
	CComposeViewModel.prototype.executeSend = function ()
	{
		if (this.isEnableSending() && this.verifyDataForSending())
		{
			this.stopAutosave();
			this.sending(true);
			this.requiresPostponedSending(!this.allowStartSending());
			App.MessageSender.send('MessageSend', this.getSendSaveParameters(), this.saveMailInSentItems(),
				true, this.onMessageSendResponse, this, this.requiresPostponedSending());
		}
	};
	
	CComposeViewModel.prototype.executeSaveCommand = function ()
	{
		this.executeSave(false);
	};
	
	/**
	 * @param {boolean=} bAutosave = false
	 */
	CComposeViewModel.prototype.executeSave = function (bAutosave)
	{
		if (this.isEnableSaving())
		{
			this.stopAutosave();
			this.saving(true);
			App.MessageSender.send('MessageSave', this.getSendSaveParameters(), this.saveMailInSentItems(),
				!bAutosave, this.onMessageSendResponse, this);
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
			this.bccAddrFocused(true);
		}
		else
		{
			this.toAddrFocused(true);
		}
	};
	
	/**
	 * Changes visibility of bcc field.
	 */
	CComposeViewModel.prototype.changeCcVisibility = function ()
	{	
		this.visibleCc(!this.visibleCc());
		
		if (this.visibleCc())
		{
			this.ccAddrFocused(true);
		}
		else
		{
			this.toAddrFocused(true);
		}
	};
	
	CComposeViewModel.prototype.getMessageDataForSingleMode = function ()
	{
		var aAttachments = _.map(this.attachments(), function (oAttach)
		{
			return {
				'@Object': 'Object/CApiMailAttachment',
				'FileName': oAttach.fileName(),
				'MimeType': oAttach.type(),
				'EstimatedSize': oAttach.size(),
				'CID': oAttach.cid(),
				'IsInline': oAttach.inline(),
				'IsLinked': oAttach.linked(),
				'Hash': oAttach.hash()
			};
		});
	
		return {
			draftInfo: this.draftInfo(),
			draftUid: this.draftUid(),
			inReplyTo: this.inReplyTo(),
			references: this.references(),
			selectedAccountId: this.selectedAccountId(),
			toAddr: this.toAddr(),
			ccAddr: this.ccAddr(),
			bccAddr: this.bccAddr(),
			subject: this.subject(),
			attachments: aAttachments,
			textBody: this.oHtmlEditor.getText(),
			selectedImportance: this.selectedImportance(),
			selectedSensitivity: this.selectedSensitivity(),
			readingConfirmation: this.readingConfirmation()
		};
	};
	
	/**
	 *
	 */
	CComposeViewModel.prototype.openInNewWindow = function ()
	{
		window.oMessageParametersFromCompose = this.getMessageDataForSingleMode();
		Utils.WindowOpener.open('#' + Enums.Screens.SingleCompose, 'new_message_window' + Math.random());
		this.commit(this.oHtmlEditor.getText());
		this.executeBackToList();
	};
	
	/**
	 *
	 */
	CComposeViewModel.prototype.computeHeight = function ()
	{
		var
			oMessageForm = this.messageForm(),
			oMessageFields = this.messageFields()
		;
	
		if (oMessageForm && oMessageFields)
		{
			_.defer(function () {
				var iMessageFields = $(oMessageFields).outerHeight();
	
				$(oMessageForm).css({
					'padding-top': iMessageFields,
					'margin-top': -iMessageFields
				});
			});
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
			App.Ajax.send(oParameters, function (oData) {
				var aList = [];
				if (oData && oData.Result && oData.Result && oData.Result.List)
				{
					aList = _.map(oData.Result.List, function (oItem) {
						return oItem && oItem.Email ? 
							(oItem.Name && 0 < Utils.trim(oItem.Name).length ?
								'"' + oItem.Name + '" <' + oItem.Email + '>' : oItem.Email) : '';
					});
	
					aList = _.compact(aList);
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
	
	CContactsImportViewModel.prototype.onFileImportStart = function ()
	{
		this.importing(true);
	};
	
	CContactsImportViewModel.prototype.onFileImportComplete = function (sUid, bResult, oData)
	{
		this.importing(false);
		this.oParent.requestContactList();
	
		if (bResult && oData && oData.Result)
		{
			var iImportedCount = Utils.pInt(oData.Result.ImportedCount);
	
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
			App.Api.showError(Utils.i18n('WARNING/CONTACTS_IMPORT_ERROR'));
		}
	};
	
	CContactsImportViewModel.prototype.onApplyBindings = function ($oViewModel)
	{
		this.oJua = new Jua({
			'action': 'index.php?/Upload/Contacts/',
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
				}
			},
			'onStart': _.bind(this.onFileImportStart, this),
			'onComplete': _.bind(this.onFileImportComplete, this)
		});
	};
	
	
	/**
	 * @constructor
	 */
	function CContactsViewModel()
	{
		var self = this;
	
		this.isFocused = ko.observable(false);
	
		this.searchInput = ko.observable('');
	
		this.loadingList = ko.observable(false);
		this.loadingViewPane = ko.observable(false);
		
		this.showPersonalContacts = ko.observable(false);
		this.showGlobalContacts = ko.observable(false);
	
		this.selectedGroupType = ko.observable(Enums.ContactsGroupListType.Personal);
	
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
	
		this.oContactModel = new CContactModel();
		this.oGroupModel = new CGroupModel();
	
		this.oContactImportViewModel = new CContactsImportViewModel(this);
	
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
		this.sortType = ko.observable(Enums.ContactSortType.Email);
	
		this.collection = ko.observableArray([]);
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
	
			if (Enums.ContactsGroupListType.Personal === iValue && !this.showPersonalContacts() && this.showGlobalContacts())
			{
				this.selectedGroupType(Enums.ContactsGroupListType.Global);
			}
			else if (Enums.ContactsGroupListType.Global === iValue && !this.showGlobalContacts() && this.showPersonalContacts())
			{
				this.selectedGroupType(Enums.ContactsGroupListType.Personal);
			}
			else if (Enums.ContactsGroupListType.Personal === iValue || Enums.ContactsGroupListType.Global === iValue)
			{
				this.selectedGroupInList(null);
				this.selectedItem(null);
				this.selector.listCheckedOrSelected(false);
				
				this.requestContactList();
			}
		}, this);
	
		this.oPageSwitcher = new CPageSwitcherViewModel(0, AppData.User.ContactsPerPage);
		
		this.oPageSwitcher.currentPage.subscribe(function () {
			this.requestContactList();
		}, this);
	
		this.search.subscribe(function (sValue) {
			this.searchInput(sValue);
		}, this);
	
		this.searchSubmitCommand = Utils.createCommand(this, function () {
	
			this.oPageSwitcher.currentPage(1);
			this.search(this.searchInput());
	
			this.requestContactList();
		});
	
		this.selector = new CSelector(this.collection, function (oItem) {
			if (oItem)
			{
				self.requestContact(oItem);
			}
		}, _.bind(this.executeDelete, this), _.bind(this.onContactDblClick, this));
	
		this.checkAll = this.selector.koCheckAll();
		this.checkAllIncomplite = this.selector.koCheckAllIncomplete();
	
		this.newContactCommand = Utils.createCommand(this, this.executeNewContact);
		this.newGroupCommand = Utils.createCommand(this, this.executeNewGroup);
		this.addContactsCommand = Utils.createCommand(this, Utils.emptyFunction, this.isEnableAddContacts);
		this.deleteCommand = Utils.createCommand(this, this.executeDelete, this.isEnableDeleting);
		this.removeFromGroupCommand = Utils.createCommand(this, this.executeRemoveFromGroup, this.isEnableRemoveContactsFromGroup);
		this.importCommand = Utils.createCommand(this, this.executeImport);
		this.exportCommand = Utils.createCommand(this, this.executeExport);
		this.saveCommand = Utils.createCommand(this, this.executeSave, function () {
			var oItem = this.selectedItem();
			return oItem ? oItem.canBeSave() : false;
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
				App.Api.openComposeMessage(aText.join(', '));
			}
	
		}, function () {
			return 0 < this.selector.listCheckedOrSelected().length;
		});
	
		this.selector.listCheckedOrSelected.subscribe(function (aList) {
			this.oGroupModel.newContactsInGroupCount(aList.length);
		}, this);
	
		this.isLoading = this.loadingList;
		this.isSearch = ko.computed(function () {
			return '' !== this.search();
		}, this);
		this.isEmptyList = ko.computed(function () {
			return 0 === this.collection().length;
		}, this);
		this.inGrooup = ko.computed(function () {
			return Enums.ContactsGroupListType.SubGroup === this.selectedGroupType();
		}, this);
	
		this.searchText = ko.computed(function () {
			return Utils.i18n('CONTACTS/INFO_SEARCH_RESULT', {
				'SEARCH': this.search()
			});
		}, this);
	}
	
	CContactsViewModel.prototype.executeSave = function (oData)
	{
		var
			oResult = {},
			aList = []
		;
	
		if (oData === this.selectedItem())
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
	
				if (oData.edited())
				{
					oData.edited(false);
				}
	
				if (oData.isNew())
				{
					this.selectedItem(null);
				}
	
				App.Ajax.send(oResult, this.onResponse, this);
			}
			else if (oData instanceof CGroupModel && !oData.readOnly())
			{
				oResult = oData.toObject();
				oResult.Action = oData.isNew() ? 'GroupCreate' : 'GroupUpdate';
	
				if (oData.edited())
				{
					oData.edited(false);
				}
	
				if (oData.isNew())
				{
					this.selectedItem(null);
				}
	
				App.Ajax.send(oResult, this.onResponse, this);
			}
		}
	};
	
	CContactsViewModel.prototype.executeBackToList = function ()
	{
		App.Routing.setLastMailboxHash();
	};
	
	CContactsViewModel.prototype.executeNewContact = function ()
	{
		var oGr = this.selectedGroupInList();
		this.oContactModel.switchToNew();
		this.oContactModel.groups(oGr ? [oGr.Id()] : []);
		this.selectedItem(this.oContactModel);
		this.selector.itemSelected(null);
	};
	
	CContactsViewModel.prototype.executeNewGroup = function ()
	{
		this.oGroupModel.switchToNew();
		this.selectedItem(this.oGroupModel);
		this.selector.itemSelected(null);
	};
	
	CContactsViewModel.prototype.executeDelete = function ()
	{
		var
			self = this,
			oMainContact = this.selectedContact(),
			aChecked = this.selector.listCheckedOrSelected(),
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
	
			App.Ajax.send({
				'Action': 'ContactDelete',
				'ContactsId': aContactsId.join(',')
			}, this.onResponse, this);
			
			App.ContactsCache.markVcardsNonexistent(aContactsId);
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
				'Action': 'RemoveContactsFromGroup',
				'GroupId': oGroup.Id(),
				'ContactsId': aContactsId.join(',')
			}, this.onResponse, this);
		}
	};
	
	CContactsViewModel.prototype.executeImport = function ()
	{
		this.selectedItem(null);
		this.oContactImportViewModel.visibility(true);
		this.selector.itemSelected(null);
		this.selectedGroupType(Enums.ContactsGroupListType.Personal);
	};
	
	CContactsViewModel.prototype.executeExport = function ()
	{
		App.downloadByUrl(Utils.getExportContactsLink());
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
					oData.edited(false);
				}
			}
		}
	
		this.oContactImportViewModel.visibility(false);
	};
	
	/**
	 * @param {Object} oGroup
	 * @param {Array} aContactIds
	 * @param {boolean} bGlobal
	 */
	CContactsViewModel.prototype.executeAddContactsToGroup = function (oGroup, aContactIds, bGlobal)
	{
		if (oGroup && _.isArray(aContactIds) && 0 < aContactIds.length)
		{
			oGroup.recivedAnim(true);
	
			App.Ajax.send({
				'Action': 'AddContactsToGroup',
				'Global': bGlobal ? '1' : '0',
				'GroupId': oGroup.Id(),
				'ContactsId': aContactIds.join(',')
			}, this.onResponse, this);
		}
	};
	
	/**
	 * @param {number} iGroupId
	 * @param {Array} aContactIds
	 * @param {boolean} bGlobal
	 */
	CContactsViewModel.prototype.executeAddContactsToGroupId = function (iGroupId, aContactIds, bGlobal)
	{
		if (iGroupId && _.isArray(aContactIds) && 0 < aContactIds.length)
		{
			App.Ajax.send({
				'Action': 'AddContactsToGroup',
				'Global': bGlobal ? '1' : '0',
				'GroupId': iGroupId,
				'ContactsId': aContactIds.join(',')
			}, this.onResponse, this);
		}
	};
	
	/**
	 * @param {Object} oGroup
	 */
	CContactsViewModel.prototype.executeAddSelectedContactsToGroup = function (oGroup)
	{
		var
			bGlobal = false,
			aList = this.selector.listCheckedOrSelected(),
			aContactIds = []
		;
	
		if (oGroup && _.isArray(aList) && 0 < aList.length)
		{
			_.each(aList, function (oItem) {
				if (oItem && !oItem.IsGroup())
				{
					bGlobal = oItem.Global();
					aContactIds.push(oItem.Id());
				}
			}, this);
		}
	
		this.executeAddContactsToGroup(oGroup, aContactIds, bGlobal);
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
	
	CContactsViewModel.prototype.isEnableAddContacts = function ()
	{
		return 0 < this.selector.listCheckedOrSelected().length;
	};
	
	CContactsViewModel.prototype.isEnableRemoveContactsFromGroup = function ()
	{
		return 0 < this.selector.listCheckedOrSelected().length;
	};
	
	CContactsViewModel.prototype.isEnableDeleting = function ()
	{
		return 0 < this.selector.listCheckedOrSelected().length;
	};
	
	CContactsViewModel.prototype.onShow = function ()
	{
		this.selector.useKeyboardKeys(true);
	
		this.oPageSwitcher.perPage(AppData.User.ContactsPerPage);
		this.oPageSwitcher.currentPage(1);
	
		this.requestContactList();
	};
	
	CContactsViewModel.prototype.onHide = function ()
	{
		this.selector.useKeyboardKeys(false);
	};
	
	CContactsViewModel.prototype.onApplyBindings = function ()
	{
		this.selector.initOnApplyBindings(
			'.contact_sub_list .item',
			'.contact_sub_list .selected.item',
			'.contact_sub_list .item .custom_checkbox',
			$('.contact_list', this.$viewModel),
			$('.contact_list .scroll-inner', this.$viewModel)
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
		
		this.selectedGroupType.valueHasMutated();
		
		this.oContactImportViewModel.onApplyBindings(this.$viewModel);
		this.requestGroupFullList();
	
		$(document).on('keyup', function(ev) {
			if (ev && ev.keyCode === Enums.Key.s) {
				self.searchFocus();
			}
		});
	};
	
	CContactsViewModel.prototype.requestContactList = function ()
	{
		this.loadingList(true);
	
		App.Ajax.send({
			'Action': (Enums.ContactsGroupListType.Global === this.selectedGroupType()) ? 'GlobalContactList' : 'ContactList',
			'Offset': (this.oPageSwitcher.currentPage() - 1) * AppData.User.ContactsPerPage,
			'Limit': AppData.User.ContactsPerPage,
			'SortField': this.sortType(),
			'SortOrder': this.sortOrder() ? '1' : '0',
			'Search': this.search(),
			'GroupId': this.selectedGroupInList() ? this.selectedGroupInList().Id() : ''
		}, this.onResponse, this);
	};
	
	CContactsViewModel.prototype.requestGroupFullList = function ()
	{
		App.Ajax.send({
			'Action': 'GroupFullList'
		}, this.onResponse, this);
	};
	
	/**
	 * @param {Object} oItem
	 */
	CContactsViewModel.prototype.requestContact = function (oItem)
	{
		this.loadingViewPane(true);
	
		if (oItem)
		{
			App.Ajax.send({
				'Action': oItem.Global() ? 'GlobalContact' : 'Contact',
				'ContactId': oItem.Id()
			}, this.onResponse, this);
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
				'Action': bGlobal ? 'GlobalContact' : 'Contact',
				'ContactId': sItemId
			}, this.onResponse, this);
		}
	};
	
	/**
	 * @param {Object} oData
	 */
	CContactsViewModel.prototype.viewGroup = function (oData)
	{
		if (oData && oData.IsGroup())
		{
			var
				oGroup = _.find(this.groupFullCollection(), function (oItem) {
					return oItem && oData && oItem === oData;
				})
			;
	
			if (oGroup)
			{
				this.oGroupModel.clear();
				this.oGroupModel
					.idGroup(oGroup.Id())
					.name(oGroup.Name())
				;
	
				this.oPageSwitcher.currentPage(1);
	
				this.selectedGroupInList(oGroup);
				this.selectedItem(this.oGroupModel);
				this.selector.itemSelected(null);
				this.selector.listCheckedOrSelected(false);
			}
		}
	};
	
	/**
	 * @param {Object} oGroup
	 */
	CContactsViewModel.prototype.deleteGroup = function (oGroup)
	{
		if (oGroup)
		{
			App.Ajax.send({
				'Action': 'GroupDelete',
				'GroupId': oGroup.idGroup()
			}, this.onResponse, this);
	
			this.selectedGroupType(Enums.ContactsGroupListType.Personal);
	
			this.groupFullCollection.remove(function (oItem) {
				return oItem && oItem.Id() === oGroup.idGroup();
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
				'SortField': 'Email',
				'SortOrder': '1',
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
							oObject = new CContactListModel();
							oObject.parse(aResultList[iIndex]);
	
							aList.push(oObject);
						}
					}
	
					aText = _.map(aList, function (oItem) {
						return oItem.EmailAndName();
					});
	
					aText = _.compact(aText);
					App.Api.openComposeMessage(aText.join(', '));
				}
				
			}, this);
		}
	};
	
	/**
	 * @param {Object} oResult
	 * @param {Object} oRequest
	 */
	CContactsViewModel.prototype.onResponse = function (oResult, oRequest)
	{
		var
			iIndex = 0,
			iLen = 0,
			aList = [],
			bGlobal = false,
			oSelected  = null,
			oSubSelected  = null,
			aChecked = [],
			aCheckedIds = [],
			oObject = null
		;
	
		if (oResult && oResult.Action && oResult.Result)
		{
			if (0 <= Utils.inArray(oResult.Action, ['Contact', 'GlobalContact']) && 'Object/CContact' === Utils.pExport(oResult.Result, '@Object', ''))
			{
				oObject = new CContactModel();
				oObject.parse(oResult.Result);
	
				oSelected = this.selector.itemSelected();
				if (oSelected && oSelected.Id() === oObject.idContact())
				{
					this.selectedItem(oObject);
				}
			}
			else if (0 <= Utils.inArray(oResult.Action, ['ContactList', 'GlobalContactList']) && oResult.Result && _.isArray(oResult.Result.List))
			{
				bGlobal = 'GlobalContactList' === oResult.Action;
				for (iLen = oResult.Result.List.length; iIndex < iLen; iIndex++)
				{
					if (oResult.Result.List[iIndex] && 'Object/CContactListItem' === Utils.pExport(oResult.Result.List[iIndex], '@Object', ''))
					{
						oObject = new CContactListModel();
						oObject.parse(oResult.Result.List[iIndex], bGlobal);
	
						aList.push(oObject);
					}
				}
	
				aChecked = this.selector.listChecked();
				aCheckedIds = _.map(aChecked, function (oItem) {
					return oItem.Id();
				});
	
				oSelected = this.selector.itemSelected();
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
				}
			}
			else if (0 <= Utils.inArray(oResult.Action, ['ContactCreate', 'ContactUpdate', 'GroupCreate', 'GroupUpdate']))
			{
				if ('GroupCreate' === oResult.Action && oResult.Result.IdGroup)
				{
					aCheckedIds = _.map(this.selector.listChecked(), function (oItem) {
						return oItem.Id();
					});
	
					this.executeAddContactsToGroupId(
						oResult.Result.IdGroup,
						aCheckedIds,
						Enums.ContactsGroupListType.Global === this.selectedGroupType()
					);
	
					this.selectedItem(null);
					this.selector.itemSelected(null);
	
					App.Api.showReport(Utils.i18n('CONTACTS/REPORT_GROUP_SUCCESSFULLY_ADDED'));
				}
				else if ('ContactCreate' === oResult.Action && oResult.Result.IdContact)
				{
					App.Api.showReport(Utils.i18n('CONTACTS/REPORT_CONTACT_SUCCESSFULLY_ADDED'));
				}
				
				this.requestContactList();
	
				if (0 <= Utils.inArray(oResult.Action, ['GroupCreate', 'GroupUpdate']))
				{
					this.requestGroupFullList();
				}
			}
			else if (0 <= Utils.inArray(oResult.Action, ['ContactDelete', 'AddContactsToGroup', 'RemoveContactsFromGroup']))
			{
				this.requestContactList();
			}
			else if (0 <= Utils.inArray(oResult.Action, ['GroupDelete']))
			{
				this.requestGroupFullList();
			}
			else if ('GroupFullList' === oResult.Action)
			{
				oSelected = _.find(this.groupFullCollection(), function (oItem) {
					return oItem.selected();
				});
	
				this.groupFullCollection(aList);
	
				for (iLen = oResult.Result.length; iIndex < iLen; iIndex++)
				{
					if (oResult.Result[iIndex] && 'Object/CContactListItem' === Utils.pExport(oResult.Result[iIndex], '@Object', ''))
					{
						oObject = new CContactListModel();
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
		}
		else
		{
			this.requestContactList();
		}
	};
	
	/**
	 * @param {Object} oContact
	 */
	CContactsViewModel.prototype.dragAndDronHelper = function (oContact)
	{
		if (oContact)
		{
			oContact.checked(true);
		}
	
		var
			oHelper = Utils.draggebleMessages(),
			nCount = this.selector.listCheckedOrSelected().length,
			aUids = 0 < nCount ? _.map(this.selector.listCheckedOrSelected(), function (oItem) {
				return oItem.Id();
			}) : []
		;
	
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
				iType = oHelper ? oHelper.data('p7-contatcs-type') : null,
				aUids = oHelper ? oHelper.data('p7-contatcs-uids') : null
			;
	
			if (null !== iType && null !== aUids)
			{
				Utils.uiDropHelperAnim(oEvent, oUi);
				this.executeAddContactsToGroup(oToGroup, aUids, Enums.ContactsGroupListType.Global === iType);
			}
		}
	};
	
	CContactsViewModel.prototype.searchFocus = function ()
	{
		if (this.selector.useKeyboardKeys() && !Utils.inFocus())
		{
			this.isFocused(true);
		}
	};
	
	CContactsViewModel.prototype.onContactDblClick = function (oMessage)
	{
		App.Api.openComposeMessage(this.selectedContact().email());
	};
	
	CContactsViewModel.prototype.onClearSearchClick = function ()
	{
		// initiation empty search
		this.search('');
		this.searchSubmitCommand();
	};
	
	/**
	 * @constructor
	 */
	function CSettingsViewModel()
	{
		this.oCommon = new CCommonSettingsViewModel();
		this.oEmailAccounts = new CEmailAccountsSettingsViewModel();
		this.oCalendar = new CCalendarSettingsViewModel();
		this.oMobileSync = new CMobileSyncSettingsViewModel();
		this.oOutLookSync = new COutLookSyncSettingsViewModel();
	
		this.tab = ko.observable(Enums.SettingsTab.Common);
	
		this.bAllowUsersChangeInterfaceSettings = AppData.App.AllowUsersChangeInterfaceSettings;
		this.bAllowCalendar = AppData.App.AllowUsersChangeInterfaceSettings && AppData.User.AllowCalendar;
		this.bAllowOutlookSync = AppData.User.OutlookSyncEnable;
		this.bAllowMobileSync = AppData.App.EnableMobileSync;
	
		this.folderListOrderUpdate = _.debounce(this.folderListOrderUpdate, 5000);
	//	this.subscribeFolderCommand = Utils.createCommand(this, this.subscribeFolder);
	}
	
	CSettingsViewModel.prototype.onHide = function (aParams)
	{
		var
			oCurrentViewModel = null,
			sTab = this.tab()
		;
	
		this.confirmSaving(sTab);
		oCurrentViewModel = this.getCurrentViewModel();
		if (oCurrentViewModel && Utils.isFunc(oCurrentViewModel.onHide))
		{
			oCurrentViewModel.onHide(aParams);
		}
	};
	
	CSettingsViewModel.prototype.onShow = function (aParams)
	{
		var
			oCurrentViewModel = null,
			sTab = this.tab()
		;
	
		this.confirmSaving(sTab);
		oCurrentViewModel = this.getCurrentViewModel();
		if (oCurrentViewModel && Utils.isFunc(oCurrentViewModel.onShow))
		{
			oCurrentViewModel.onShow(aParams);
		}
	};
	
	CSettingsViewModel.prototype.viewTab = function (sTab)
	{
		var
			sDefaultTab = this.bAllowUsersChangeInterfaceSettings ? Enums.SettingsTab.Common : Enums.SettingsTab.EmailAccounts,
			bDeniedTab = false,
			bExistingTab = false;
		bExistingTab = (sTab === Enums.SettingsTab.Common ||
			sTab === Enums.SettingsTab.EmailAccounts ||
			sTab === Enums.SettingsTab.Calendar ||
			sTab === Enums.SettingsTab.MobileSync ||
			sTab === Enums.SettingsTab.OutLookSync
		);
		bDeniedTab = (
			(sTab === Enums.SettingsTab.Common && !this.bAllowUsersChangeInterfaceSettings) ||
			(sTab === Enums.SettingsTab.Calendar && !this.bAllowCalendar) ||
			(sTab === Enums.SettingsTab.MobileSync && !this.bAllowMobileSync) ||
			(sTab === Enums.SettingsTab.OutLookSync && !this.bAllowOutlookSync)
		);
	
		sTab = (!bExistingTab || bDeniedTab) ? sDefaultTab : sTab;
	
		this.tab(sTab);
	};
	
	/**
	 * @param {Array} aParams
	 */
	CSettingsViewModel.prototype.onRoute = function (aParams)
	{
		var
			oCurrentViewModel = null,
			sTab = this.tab()
		;
	
		if (_.isArray(aParams) && aParams.length > 0) {
			sTab = aParams[0];
		}
		oCurrentViewModel = this.getCurrentViewModel();
		if (oCurrentViewModel && Utils.isFunc(oCurrentViewModel.onHide))
		{
			oCurrentViewModel.onHide(aParams);
		}
	
		this.confirmSaving(this.tab());
		this.viewTab(sTab);
	
		oCurrentViewModel = this.getCurrentViewModel();
		if (oCurrentViewModel && Utils.isFunc(oCurrentViewModel.onRoute))
		{
			oCurrentViewModel.onRoute(aParams);
		}
	};
	
	CSettingsViewModel.prototype.confirmSaving = function (sTab)
	{
		var oCurrentViewModel = this.getViewModel(sTab),
			sConfirm = Utils.i18n('SETTINGS/CONFIRM_SETTINGS_SAVE'),
			fAction = _.bind(function (bResult) {
				if (oCurrentViewModel)
				{
					if (bResult)
					{
						if (oCurrentViewModel.onSaveClick)
						{
							oCurrentViewModel.onSaveClick();
						}
					}
					else
					{
						if (oCurrentViewModel.init)
						{
							oCurrentViewModel.init();
						}
					}
				}
			}, this);
	
		if (oCurrentViewModel && Utils.isFunc(oCurrentViewModel.isChanged) && oCurrentViewModel.isChanged())
		{
			App.Screens.showPopup(ConfirmPopup, [sConfirm, fAction]);
		}
	};
	
	CSettingsViewModel.prototype.getViewModel = function (sTab)
	{
		switch (sTab) {
			case Enums.SettingsTab.Common:
				return this.oCommon;
			case Enums.SettingsTab.EmailAccounts:
				return this.oEmailAccounts;
			case Enums.SettingsTab.Calendar:
				return this.oCalendar;
			case Enums.SettingsTab.MobileSync:
				return this.oMobileSync;
			case Enums.SettingsTab.OutLookSync:
				return this.oOutLookSync;
		}
	};
	
	/**
	 */
	CSettingsViewModel.prototype.getCurrentViewModel = function ()
	{
		return this.getViewModel(this.tab());
	};
	
	/**
	 * @param {string} sTab
	 */
	CSettingsViewModel.prototype.showTab = function (sTab)
	{
		App.Routing.setHash([Enums.Screens.Settings, sTab]);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CSettingsViewModel.prototype.onResponseFolderDelete = function (oData, oParameters)
	{
		if (oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			App.Cache.getFolderList(AppData.Accounts.editedId());
		}
	};
	
	CSettingsViewModel.prototype.deleteFolder = function (oFolderToDelete, collection, bOkAnswer)
	{
		var
			oParameters = {
				'Action': 'FolderDelete',
				'AccountID': AppData.Accounts.editedId(),
				'Folder': oFolderToDelete.fullName()
			}
		;
	
		if (bOkAnswer && collection && oFolderToDelete)
		{
			collection.remove(function (oFolder) {
					if (oFolderToDelete.fullName() === oFolder.fullName())
					{
						return true;
					}
					return false;
				});
	
			App.Ajax.send(oParameters, this.onResponseFolderDelete, this);
		}
	};
	
	CSettingsViewModel.prototype.onResponseFolderSubscribe = function (oData, oParameters)
	{
		if (oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			App.Cache.getFolderList(AppData.Accounts.editedId());
		}
	};
	
	CSettingsViewModel.prototype.onSubscribeFolderClick = function (oFolder)
	{
		var
			oParameters = {
				'Action': 'FolderSubscribe',
				'AccountID': AppData.Accounts.editedId(),
				'Folder': oFolder.fullName(),
				'SetAction': oFolder.subscribed() ? 0 : 1
			}
		;
	
		if (oFolder && oFolder.canSubscribe())
		{
			oFolder.subscribed(!oFolder.subscribed());
			App.Ajax.send(oParameters, this.onResponseFolderSubscribe, this);
		}
	};
	
	CSettingsViewModel.prototype.onDeleteFolderClick = function (oFolder, parent)
	{
		var
			sWarning = Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_CONFIRMATION_DELETE'),
			collection = this.getCollectionFromParent(parent),
			fCallBack = _.bind(this.deleteFolder, this, oFolder, collection)
		;
		if (oFolder && oFolder.canDelete())
		{
			App.Screens.showPopup(ConfirmPopup, [sWarning, fCallBack]);
		}
		else
		{
			this.oEmailAccounts.oAccountFolders.highlighted(true);
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CSettingsViewModel.prototype.onResponseFolderRename = function (oData, oParameters)
	{
		if (oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			App.Cache.getFolderList(AppData.Accounts.editedId()); // TODO
		}
	};
	
	CSettingsViewModel.prototype.folderEditOnEnter = function (oFolder)
	{
		var
			oParameters = {
				'Action': 'FolderRename',
				'AccountID': AppData.Accounts.editedId(),
				'PrevFolderFullNameRaw': oFolder.fullName(),
				'NewFolderNameInUtf8': oFolder.nameForEdit()
			}
		;
		App.Ajax.send(oParameters, this.onResponseFolderRename, this);
		oFolder.name(oFolder.nameForEdit());
		oFolder.edited(false);
	};
	
	CSettingsViewModel.prototype.folderEditOnEsc = function (oFolder)
	{
		oFolder.edited(false);
	};
	
	CSettingsViewModel.prototype.getCollectionFromParent = function (parent)
	{
		var
			collection = null
		;
		if (parent.subfolders)
		{
			collection = parent.subfolders;
		}
		else
		{
			collection = parent.collection;
		}
	
		return collection;
	};
	
	CSettingsViewModel.prototype.canMoveFolderUp = function (oFolder, index, parent)
	{
		var
			collection = this.getCollectionFromParent(parent),
			oPrevFolder = collection()[index() - 1],
			oPrevFolderFullName = ''
		;
		if (index() > 0 && oPrevFolder)
		{
			oPrevFolderFullName = collection()[index() - 1].fullName();
		}
	
		return (index() !== 0 && oFolder &&
			oFolder.fullName() !== App.Cache.editedFolderList().inboxFolderFullName() &&
			App.Cache.editedFolderList().inboxFolderFullName() !== oPrevFolderFullName);
	};
	
	CSettingsViewModel.prototype.canMoveFolderDown = function (oFolder, index, parent)
	{
		var
			collection = this.getCollectionFromParent(parent)
		;
	
		return (index() !== collection().length - 1 &&
			oFolder.fullName() !== App.Cache.editedFolderList().inboxFolderFullName());
	};
	
	CSettingsViewModel.prototype.moveFolderUp = function (oFolder, index, parent)
	{
		var
			collection = this.getCollectionFromParent(parent)
		;
	
		if (this.canMoveFolderUp(oFolder, index, parent) && collection)
		{
			collection.splice(index(), 1);
			collection.splice(index()-1, 0, oFolder);
			this.folderListOrderUpdate();
		}
	};
	
	CSettingsViewModel.prototype.moveFolderDown = function (oFolder, index, parent)
	{
		var
			collection = this.getCollectionFromParent(parent)
		;
		if (this.canMoveFolderDown(oFolder, index, parent) && collection)
		{
			collection.splice(index(), 1);
			collection.splice(index()+1, 0, oFolder);
			this.folderListOrderUpdate();
		}
	};
	
	CSettingsViewModel.prototype.folderListOrderUpdate = function ()
	{
		var
			options = App.Cache.editedFolderList().getOptions(),
			aFolderList = _.map(options, function (oItem) {
				return (oItem && oItem.id)? oItem.id : null;
			}),
			oParameters = {
				'Action': 'FolderListOrderUpdate',
				'AccountID': AppData.Accounts.editedId(),
				'FolderList': _.compact(aFolderList)
			}
		;
	
		App.Ajax.send(oParameters, this.onResponseFolderListOrderUpdate, this);
	};
	
	CSettingsViewModel.prototype.onResponseFolderListOrderUpdate = function (oData, oParameters)
	{
		if (oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			App.Cache.getFolderList(AppData.Accounts.editedId());
		}
	};
	
	
	
	/**
	 * @constructor
	 */
	function CCommonSettingsViewModel()
	{
		this.aSkins = AppData.App.Themes;
		this.selectedSkin = ko.observable(AppData.User.DefaultTheme);
	
		this.layout = ko.observable(AppData.User.Layout);
	
		this.aLanguages = AppData.App.Languages;
		this.selectedLanguage = ko.observable(AppData.User.DefaultLanguage);
	
		this.messagesPerPage = ko.observable(AppData.User.MailsPerPage);
		this.contactsPerPage = ko.observable(AppData.User.ContactsPerPage);
		this.rangeOfNumbers = ko.observableArray([10, 20, 30, 50, 75, 100, 150, 200]);
		this.autocheckmailInterval = ko.observable(AppData.User.autoCheckMailInterval());
		this.richText = ko.observable(AppData.User.DefaultEditor);
	
		this.timeFormat = ko.observable(AppData.User.defaultTimeFormat());
		this.aDateFormats = AppData.App.DateFormats;
		this.dateFormat = ko.observable(AppData.User.DefaultDateFormat);
	
		this.bAllowContacts = AppData.User.ShowContacts;
		this.bAllowCalendar = AppData.User.AllowCalendar;
		
		this.firstState = this.getState();
	}
	
	CCommonSettingsViewModel.prototype.init = function ()
	{
		this.selectedSkin(AppData.User.DefaultTheme);
		this.layout(AppData.User.Layout);
		this.selectedLanguage(AppData.User.DefaultLanguage);
		this.messagesPerPage(AppData.User.MailsPerPage);
		this.contactsPerPage(AppData.User.ContactsPerPage);
		this.autocheckmailInterval(AppData.User.autoCheckMailInterval());
		this.richText(AppData.User.DefaultEditor);
		this.timeFormat(AppData.User.defaultTimeFormat());
		this.dateFormat(AppData.User.DefaultDateFormat);
	};
	
	CCommonSettingsViewModel.prototype.getState = function ()
	{
		var sState = [
			this.selectedSkin(),
			this.layout(), 
			this.selectedLanguage(),
			this.messagesPerPage(), 
			this.contactsPerPage(),
			this.autocheckmailInterval(),
			this.richText(),
			this.timeFormat(),
			this.dateFormat()
		];
		return sState.join(':');
	};
	
	CCommonSettingsViewModel.prototype.updateFirstState = function ()
	{
		this.firstState = this.getState();
	};
	
	CCommonSettingsViewModel.prototype.isChanged = function ()
	{
		if (this.firstState && this.getState() !== this.firstState)
		{
			return true;
		}
		else
		{
			return false;
		}
	};
	
	/**
	 * Parses the response from the server. If the settings are normally stored, then updates them. 
	 * Otherwise an error message.
	 * 
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCommonSettingsViewModel.prototype.onResponse = function (oData, oParameters)
	{
		var bNeedReload = false;
	
		if (oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			bNeedReload = (oParameters.DefaultTheme !== AppData.User.DefaultTheme ||
				oParameters.DefaultLanguage !== AppData.User.DefaultLanguage);
			
			if (bNeedReload)
			{
				location.reload();
			}
			else
			{
				AppData.User.updateCommonSettings(oParameters.MailsPerPage, oParameters.ContactsPerPage,
					oParameters.AutoCheckMailInterval, oParameters.DefaultEditor, oParameters.Layout,
					oParameters.DefaultTheme, oParameters.DefaultLanguage, oParameters.DefaultDateFormat,
					oParameters.DefaultTimeFormat);
	
				App.Api.showReport(Utils.i18n('SETTINGS/COMMON_REPORT_UPDATED_SUCCESSFULLY'));
			}
		}
	};
	
	/**
	 * Sends a request to the server to save the settings.
	 */
	CCommonSettingsViewModel.prototype.onSaveClick = function ()
	{
		var
			oParameters = {
				'Action': 'UpdateUserSettings',
				'MailsPerPage': parseInt(this.messagesPerPage(), 10),
				'ContactsPerPage': parseInt(this.contactsPerPage(), 10),
				'AutoCheckMailInterval': parseInt(this.autocheckmailInterval(), 10),
				'DefaultEditor': parseInt(this.richText(), 10),
				'Layout': parseInt(this.layout(), 10),
				'DefaultTheme': this.selectedSkin(),
				'DefaultLanguage': this.selectedLanguage(),
				'DefaultDateFormat': this.dateFormat(),
				'DefaultTimeFormat': parseInt(this.timeFormat(), 10)
			}
		;
		this.updateFirstState();
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	/**
	 * @constructor
	 */
	function CEmailAccountsSettingsViewModel()
	{
		this.accounts = AppData.Accounts.Collection;
		
		this.currentAccountId = AppData.Accounts.currentId;
		this.editedAccountId = AppData.Accounts.editedId;
		this.defaultAccountId = AppData.Accounts.defaultId;
		
		this.oAccountProperties = new CAccountPropertiesViewModel(this);
		this.oAccountSignature = new CAccountSignatureViewModel(this);
		this.oAccountFilters = new CAccountFiltersViewModel(this);
		this.oAccountAutoresponder = new CAccountAutoresponderViewModel(this);
		this.oAccountForward = new CAccountForwardViewModel(this);
		this.oAccountFolders = new CAccountFoldersViewModel(this);
		
		this.tab = ko.observable(Enums.AccountSettingsTab.Properties);
		
		this.allowUsersAddNewAccounts = AppData.App.AllowUsersAddNewAccounts;
		
		this.allowAutoresponderExtension = ko.observable(false);
		this.allowForwardExtension = ko.observable(false);
		this.allowSieveFiltersExtension = ko.observable(false);
		
		this.changeAccount(this.editedAccountId());
	
	//	AppData.Accounts.editedId.subscribe(function (value) {
	//		this.changeAccount(value);
	//	}, this);
	}
	
	CEmailAccountsSettingsViewModel.prototype.isChanged = function ()
	{
		return false;
	};
	
	/**
	 * @param {string} sTab
	 * @param {boolean=} bAccountChange
	 * @param {?CAccountModel=} oAccount
	 */
	CEmailAccountsSettingsViewModel.prototype.confirmSaving = function (sTab, bAccountChange, oAccount)
	{
		var oCurrentViewModel = this.getViewModel(sTab),
			oParameters = {},
			sConfirm = Utils.i18n('SETTINGS/CONFIRM_SETTINGS_SAVE'),
			fAction = _.bind(function (bResult) {
				if (oCurrentViewModel)
				{
					if (bResult)
					{
						if (oCurrentViewModel.onSaveClick)
						{
							oCurrentViewModel.saveData(oParameters);
						}
					}
					else
					{
						oCurrentViewModel.updateFirstState();
					}
				}
			}, this);
		
		bAccountChange = Utils.isUnd(bAccountChange) ? false : bAccountChange;
		oAccount = Utils.isUnd(oAccount) ? null : oAccount;
		
		if (!bAccountChange && oCurrentViewModel && Utils.isFunc(oCurrentViewModel.isChanged) && oCurrentViewModel.isChanged())
		{
			oParameters = oCurrentViewModel.prepareParameters();
			App.Screens.showPopup(ConfirmPopup, [sConfirm, fAction]);
		}
	};
	
	/**
	 * @param {Array} aParams
	 */
	CEmailAccountsSettingsViewModel.prototype.onRoute = function (aParams)
	{
		var 
			oAccount = AppData.Accounts.getEdited()
		;
		if (oAccount)
		{
			if (_.isArray(aParams) && aParams.length > 1)
			{
				this.tab(aParams[1]);
			}
			
			this.confirmSaving(this.tab());
			this.viewCurrentTab(aParams);
		}
	};
	
	/**
	 * @param {Array} aParams
	 */
	CEmailAccountsSettingsViewModel.prototype.onHide = function (aParams)
	{
		this.confirmSaving(this.tab());
	};
	
	/**
	 * @param {Array=} aParams
	 */
	CEmailAccountsSettingsViewModel.prototype.viewCurrentTab = function (aParams)
	{
		var
			oAccount = AppData.Accounts.getEdited(),
			oCurrentViewModel = null
		;
	
		if (oAccount)
		{
			if (this.isTabAllowed(this.tab(), oAccount))
			{
				oCurrentViewModel = this.getCurrentViewModel();
				if (oCurrentViewModel)
				{
					if (Utils.isFunc(oCurrentViewModel.onHide))
					{
						oCurrentViewModel.onHide(aParams);
					}
					if (Utils.isFunc(oCurrentViewModel.onShow))
					{
						oCurrentViewModel.onShow(aParams, oAccount);
					}
				}
			}
			else
			{
				this.tab(Enums.AccountSettingsTab.Properties);
				App.Routing.replaceHash([Enums.Screens.Settings, Enums.SettingsTab.EmailAccounts, Enums.AccountSettingsTab.Properties]);
			}
		}
	};
	
	CEmailAccountsSettingsViewModel.prototype.getViewModel = function (sTab)
	{
		switch (sTab) 
		{
			case Enums.AccountSettingsTab.Folders:
				return this.oAccountFolders;
			case Enums.AccountSettingsTab.Filters:
				return this.oAccountFilters;
			case Enums.AccountSettingsTab.Forward:
				return this.oAccountForward;
			case Enums.AccountSettingsTab.Signature:
				return this.oAccountSignature;
			case Enums.AccountSettingsTab.Autoresponder:
				return this.oAccountAutoresponder;
			default:
			case Enums.AccountSettingsTab.Properties:
				return this.oAccountProperties;
		}
	};
	
	CEmailAccountsSettingsViewModel.prototype.getCurrentViewModel = function ()
	{
		return this.getViewModel(this.tab());
	};
	
	CEmailAccountsSettingsViewModel.prototype.isTabAllowed = function (sTab, oAccount)
	{
		if (
			-1 === Utils.inArray(sTab, [
					Enums.AccountSettingsTab.Properties, Enums.AccountSettingsTab.Signature, Enums.AccountSettingsTab.Filters,
					Enums.AccountSettingsTab.Autoresponder, Enums.AccountSettingsTab.Forward, Enums.AccountSettingsTab.Folders
				]) ||
			sTab === Enums.AccountSettingsTab.Filters && !oAccount.extensionExists('AllowSieveFiltersExtension') ||
			sTab === Enums.AccountSettingsTab.Forward && !oAccount.extensionExists('AllowForwardExtension') ||
			sTab === Enums.AccountSettingsTab.Autoresponder && !oAccount.extensionExists('AllowAutoresponderExtension')
		)
		{
			return false;
		}
		
		return true;
	};
	
	/**
	 * @param {string} sTab
	 */
	CEmailAccountsSettingsViewModel.prototype.onTabClick = function (sTab)
	{
		App.Routing.setHash([Enums.Screens.Settings, Enums.SettingsTab.EmailAccounts, sTab]);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CEmailAccountsSettingsViewModel.prototype.onResponseAccountDelete = function (oData, oParameters)
	{
		if (!oData || !oData.Result)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			AppData.Accounts.deleteAccount(oParameters.AccountIDToDelete);
			
			if (this.defaultAccountId() === oParameters.AccountIDToDelete)
			{
				App.Routing.setHash([]);
				location.reload();
			}
		}
	};
	
	CEmailAccountsSettingsViewModel.prototype.deleteAccount = function (iAccountId, bOkAnswer)
	{
		var
			oParameters = {
				'Action': 'AccountDelete',
				'AccountIDToDelete': iAccountId
			}
		;
		
		if (bOkAnswer)
		{
			App.Ajax.send(oParameters, this.onResponseAccountDelete, this);
		}
	};
		
	/**
	 * @param {number} iAccountId
	 */
	CEmailAccountsSettingsViewModel.prototype.onAccountDelete = function (iAccountId)
	{
		var
			sWarning = '',
			fCallBack = _.bind(this.deleteAccount, this, iAccountId),
			oAccount = AppData.Accounts.getAccount(iAccountId),
			sTitle = oAccount.email()
		;
		
		if (this.defaultAccountId() === iAccountId) {
			if (this.accounts().length === 1) {
				sWarning = Utils.i18n('SETTINGS/ACCOUNTS_CONFIRMATION_SINGLE_DEFAULT_DELETE');
			} else {
				sWarning = Utils.i18n('SETTINGS/ACCOUNTS_CONFIRMATION_DEFAULT_DELETE');
			}
		} else {
			sWarning = Utils.i18n('SETTINGS/ACCOUNTS_CONFIRMATION_DELETE');
		}
		
		App.Screens.showPopup(ConfirmPopup, [sWarning, fCallBack, sTitle]);
	};
	
	CEmailAccountsSettingsViewModel.prototype.onEditedAccountDelete = function ()
	{
		var
			sWarning = '',
			fCallBack = _.bind(this.deleteAccount, this, this.editedAccountId()),
			oAccount = AppData.Accounts.getEdited(),
			sTitle = oAccount.email()
		;
		
		if (this.editedAccountId() === this.defaultAccountId()) {
			if (this.accounts().length === 1) {
				sWarning = Utils.i18n('SETTINGS/ACCOUNTS_CONFIRMATION_SINGLE_DEFAULT_DELETE');
			} else {
				sWarning = Utils.i18n('SETTINGS/ACCOUNTS_CONFIRMATION_DEFAULT_DELETE');
			}
		} else {
			sWarning = Utils.i18n('SETTINGS/ACCOUNTS_CONFIRMATION_DELETE');
		}
		
		App.Screens.showPopup(ConfirmPopup, [sWarning, fCallBack, sTitle]);
	};
	
	/**
	 * 
	 */
	CEmailAccountsSettingsViewModel.prototype.onAccountAdd = function ()
	{
		App.Screens.showPopup(AccountCreatePopup, []);
	};
	
	/**
	 * @param {number} iAccountId
	 */
	CEmailAccountsSettingsViewModel.prototype.changeAccount = function (iAccountId)
	{
		var
			oAccount = null,
			oParameters = {
				'Action': 'AccountSettings',
				'AccountID': iAccountId
			}
		;
	
		this.confirmSaving(this.tab());
	
		oAccount = AppData.Accounts.getAccount(iAccountId);
		if (!Utils.isUnd(oAccount) && oAccount.isExtended())
		{
			this.populate(oAccount);
		}
		else
		{
			App.Ajax.send(oParameters, this.onAccountSettingsResponse, this);
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CEmailAccountsSettingsViewModel.prototype.onAccountSettingsResponse = function (oData, oParameters)
	{
		if (!oData && oData.Result === false)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			var oAccount = AppData.Accounts.getAccount(oParameters.AccountID);
			if (!Utils.isUnd(oAccount))
			{
				oAccount.updateExtended(oData.Result);
				this.populate(oAccount);
			}	
		}
	};
	
	/**
	 * @param {Object} oAccount
	 */
	CEmailAccountsSettingsViewModel.prototype.populate = function (oAccount)
	{
		this.allowAutoresponderExtension(oAccount.extensionExists('AllowAutoresponderExtension'));
		this.allowForwardExtension(oAccount.extensionExists('AllowForwardExtension'));
		this.allowSieveFiltersExtension(oAccount.extensionExists('AllowSieveFiltersExtension'));
	
		AppData.Accounts.changeEditedAccount(oAccount.id());
		this.viewCurrentTab();
	};
	
	
	/**
	 * @constructor
	 */
	function CCalendarSettingsViewModel()
	{
		this.showWeekends = ko.observable(AppData.User.CalendarShowWeekEnds);
		
		this.availableTimes = ko.observableArray([
			{text: '00:00', value: '0'},
			{text: '01:00', value: '1'},
			{text: '02:00', value: '2'},
			{text: '03:00', value: '3'},
			{text: '04:00', value: '4'},
			{text: '05:00', value: '5'},
			{text: '06:00', value: '6'},
			{text: '07:00', value: '7'},
			{text: '08:00', value: '8'},
			{text: '09:00', value: '9'},
			{text: '10:00', value: '10'},
			{text: '11:00', value: '11'},
			{text: '12:00', value: '12'},
			{text: '13:00', value: '13'},
			{text: '14:00', value: '14'},
			{text: '15:00', value: '15'},
			{text: '16:00', value: '16'},
			{text: '17:00', value: '17'},
			{text: '18:00', value: '18'},
			{text: '19:00', value: '19'},
			{text: '20:00', value: '20'},
			{text: '21:00', value: '21'},
			{text: '22:00', value: '22'},
			{text: '23:00', value: '23'},
			{text: '24:00', value: '24'}
		]);
		this.selectedWorkdayStarts = ko.observable(AppData.User.CalendarWorkDayStarts);
		this.selectedWorkdayEnds = ko.observable(AppData.User.CalendarWorkDayEnds);
		
		this.showWorkday = ko.observable(AppData.User.CalendarShowWorkDay);
		this.weekStartsOn = ko.observable(AppData.User.CalendarWeekStartsOn);
		this.defaultTab = ko.observable(AppData.User.CalendarDefaultTab);
		
		this.firstState = this.getState();
	}
	
	CCalendarSettingsViewModel.prototype.init = function()
	{
		this.showWeekends(AppData.User.CalendarShowWeekEnds);
		this.selectedWorkdayStarts(AppData.User.CalendarWorkDayStarts);
		this.selectedWorkdayEnds(AppData.User.CalendarWorkDayEnds);
		this.showWorkday(AppData.User.CalendarShowWorkDay);
		this.weekStartsOn(AppData.User.CalendarWeekStartsOn);
		this.defaultTab(AppData.User.CalendarDefaultTab);
	};
	
	CCalendarSettingsViewModel.prototype.getState = function()
	{
		var sState = [
			this.showWeekends(),
			this.selectedWorkdayStarts(),
			this.selectedWorkdayEnds(),
			this.showWorkday(),
			this.weekStartsOn(),
			this.defaultTab()		
		];
		return sState.join(':');
	};
	
	CCalendarSettingsViewModel.prototype.updateFirstState = function()
	{
		this.firstState = this.getState();
	};
	
	CCalendarSettingsViewModel.prototype.isChanged = function()
	{
		if (this.firstState && this.getState() !== this.firstState)
		{
			return true;
		}
		else
		{
			return false;
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCalendarSettingsViewModel.prototype.onResponse = function (oData, oParameters)
	{
		if (oData.Result === false) {
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_CALENDAR_SETTINGS_SAVING_FAILED'));
		}
		else {
			AppData.User.updateCalendarSettings(oParameters.ShowWeekEnds, oParameters.ShowWorkDay, 
				oParameters.WorkDayStarts, oParameters.WorkDayEnds, oParameters.WeekStartsOn, oParameters.DefaultTab);
	
			App.Api.showReport(Utils.i18n('SETTINGS/COMMON_REPORT_UPDATED_SUCCESSFULLY'));
		}
	};
	
	CCalendarSettingsViewModel.prototype.onSaveClick = function ()
	{
		var
			oParameters = {
				'Action': 'UpdateUserSettings',
				'ShowWeekEnds': this.showWeekends(),
				'ShowWorkDay': this.showWorkday(),
				'WorkDayStarts': parseInt(this.selectedWorkdayStarts(), 10),
				'WorkDayEnds': parseInt(this.selectedWorkdayEnds(), 10),
				'WeekStartsOn': parseInt(this.weekStartsOn(), 10),
				'DefaultTab': parseInt(this.defaultTab(), 10)
			}
		;
		
		this.updateFirstState();
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	
	/**
	 * @constructor
	 */
	function CMobileSyncSettingsViewModel()
	{
		this.mobileSync = AppData.User.mobileSync;
		this.mobileSync.subscribe(this.onMobileSyncSubscribe, this);
		
		this.enableDav = ko.observable(false);
		
		this.davLogin = ko.observable('');
		this.davServer = ko.observable('');
		
		this.davCalendars = ko.observable([]);
		this.visibleCalendars = ko.computed(function () {
			return this.davCalendars().length > 0;
		}, this);
		
		this.davPersonalContactsUrl = ko.observable('');
		this.davCollectedAddressesUrl = ko.observable('');
		this.davGlobalAddressBookUrl = ko.observable('');
		
		this.bVisiblePersonalContacts = AppData.User.ShowPersonalContacts;
		this.bVisibleGlobalContacts = AppData.User.ShowGlobalContacts;
		this.bVisibleContacts = AppData.User.ShowContacts;
		this.bVisibleCalendar = AppData.User.AllowCalendar;
		this.bVisibleIosLink = ((navigator.platform.indexOf('iPhone') !== -1) ||
			(navigator.platform.indexOf('iPod') !== -1) ||
			(navigator.platform.indexOf('iPad') !== -1));
		
		this.visibleDavViaUrls = ko.computed(function () {
			return this.visibleCalendars() || this.bVisibleContacts;
		}, this);
		
		this.enableActiveSync = ko.observable(false);
		
		this.activeLogin = ko.observable('');
		this.activeServer = ko.observable('');
		this.password = ko.observable(AppData.User.IsDemo ? AppData.User.MobileSyncDemoPass : 
			Utils.i18n('SETTINGS/MOBILE_DAVSYNC_PASSWORD_VALUE'));
		
		this.bChanged = false;
	}
	
	CMobileSyncSettingsViewModel.prototype.onRoute = function ()
	{
		AppData.User.requestSyncSettings();
	};
	
	CMobileSyncSettingsViewModel.prototype.onMobileSyncSubscribe = function ()
	{
	
		this.enableDav(AppData.App.EnableMobileSync && this.mobileSync() && this.mobileSync()['EnableDav']);
		
		if (this.enableDav())
		{
			this.davLogin(this.mobileSync()['Dav']['Login']);
			this.davServer(this.mobileSync()['Dav']['Server']);
	
			this.davCalendars(this.mobileSync()['Dav']['Calendars']);
			
			this.davPersonalContactsUrl(this.mobileSync()['Dav']['PersonalContactsUrl']);
			this.davCollectedAddressesUrl(this.mobileSync()['Dav']['CollectedAddressesUrl']);
			this.davGlobalAddressBookUrl(this.mobileSync()['Dav']['GlobalAddressBookUrl']);
		}
		
		this.enableActiveSync(AppData.App.EnableMobileSync && this.mobileSync() && this.mobileSync()['EnableActiveSync']);
		
		if (this.enableActiveSync())
		{
			this.activeLogin(this.mobileSync()['ActiveSync']['Login']);
			this.activeServer(this.mobileSync()['ActiveSync']['Server']);
		}
	};
	
	
	/**
	 * @constructor
	 */
	function COutLookSyncSettingsViewModel()
	{
		this.outlookSync = AppData.User.outlookSync;
		this.outlookSync.subscribe(this.onOutlookSyncSubscribe, this);
		
		this.visibleOutlookSync = ko.observable(false);
		
		this.login = ko.observable('');
		this.server = ko.observable('');
		this.password = ko.observable(AppData.User.IsDemo ? AppData.User.OutlookSyncDemoPass : 
			Utils.i18n('SETTINGS/OUTLOOKSYNC_PASSWORD_VALUE'));
		
		this.bChanged = false;
	}
	
	COutLookSyncSettingsViewModel.prototype.onRoute = function ()
	{
		AppData.User.requestSyncSettings();
	};
	
	COutLookSyncSettingsViewModel.prototype.onOutlookSyncSubscribe = function ()
	{
		if (AppData.User.OutlookSyncEnable)
		{
			this.visibleOutlookSync(true);
	
			if (this.outlookSync())
			{
				this.login(this.outlookSync()['Login']);
				this.server(this.outlookSync()['Server']);
			}
		}
	};
	
	
	/**
	 * @param {?=} oParent
	 *
	 * @constructor
	 */ 
	function CAccountPropertiesViewModel(oParent)
	{
		this.account = ko.observable(null);
	
		this.isInternal = ko.observable(true);
		this.isLinked = ko.observable(true);
		this.isDefault = ko.observable(false);
		this.friendlyName = ko.observable('');
		this.email = ko.observable('');
		this.incomingMailLogin = ko.observable('');
		this.incomingMailPassword = ko.observable('');
		this.incomingMailPort = ko.observable(0);
		this.incomingMailServer = ko.observable('');
		this.outgoingMailLogin = ko.observable('');
		this.outgoingMailPassword = ko.observable('');
		this.outgoingMailPort = ko.observable(0);
		this.outgoingMailServer = ko.observable('');
	
		this.allowChangePassword = ko.observable(false);
		this.useSmtpAuthentication = ko.observable(false);
		
		this.incLoginFocused = ko.observable(false);
		this.incLoginFocused.subscribe(function () {
			if (this.incLoginFocused() && this.incomingMailLogin() === '')
			{
				this.incomingMailLogin(this.email());
			}
		}, this);
	
		this.outServerFocused = ko.observable(false);
		this.outServerFocused.subscribe(function () {
			if (this.outServerFocused() && this.outgoingMailServer() === '')
			{
				this.outgoingMailServer(this.incomingMailServer());
			}
		}, this);
	
		this.account.subscribe(function (oAccount) {
			this.populate(oAccount);
		}, this);
		
		this.firstState = null;
	}
	
	CAccountPropertiesViewModel.prototype.getState = function ()
	{
		var aState = [
			this.friendlyName(),
			this.email(),
			this.incomingMailLogin(),
			this.incomingMailPort(),
			this.incomingMailServer(),
			this.outgoingMailLogin(),
			this.outgoingMailPort(),
			this.outgoingMailServer(),
			this.useSmtpAuthentication()
		];
		
		return aState.join(':');
	};
	
	CAccountPropertiesViewModel.prototype.updateFirstState = function()
	{
		this.firstState = this.getState();
	};
	
	CAccountPropertiesViewModel.prototype.isChanged = function()
	{
		if (this.firstState && this.getState() !== this.firstState)
		{
			return true;
		}
		else
		{
			return false;
		}
	};
	
	/**
	 * @param {Object} oAccount
	 */
	CAccountPropertiesViewModel.prototype.populate = function (oAccount)
	{
		if (oAccount)
		{	
			this.allowChangePassword(oAccount.extensionExists('AllowChangePasswordExtension'));
	//		this.allowChangePassword(true);
	
			this.isInternal(oAccount.isInternal());
			this.isLinked(oAccount.isLinked());
			this.isDefault(oAccount.isDefault());
			this.useSmtpAuthentication(Utils.pInt(oAccount.outgoingMailAuth()) === 2 ? true : false);
			this.friendlyName(oAccount.friendlyName());
			this.email(oAccount.email());
			this.incomingMailLogin(oAccount.incomingMailLogin());
			this.incomingMailServer(oAccount.incomingMailServer());
			this.incomingMailPort(oAccount.incomingMailPort());
			this.outgoingMailServer(oAccount.outgoingMailServer());
			this.outgoingMailLogin(oAccount.outgoingMailLogin());
			this.outgoingMailPort(oAccount.outgoingMailPort());
			
			this.updateFirstState();
		}
		else
		{
			this.allowChangePassword(false);
	
			this.isLinked(true);
			this.useSmtpAuthentication(true);
			this.friendlyName('');
			this.email('');
			this.incomingMailLogin('');
			this.incomingMailServer('');
			this.incomingMailPort(143);
			this.outgoingMailServer('');
			this.outgoingMailLogin('');
			this.outgoingMailPort(25);
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CAccountPropertiesViewModel.prototype.onResponse = function (oData, oParameters)
	{
		if (!oData || !oData.Result)
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
		else
		{
			var
				iAccountId = Utils.pInt(oData.AccountID),
				oAccount = 0 < iAccountId ? AppData.Accounts.getAccount(iAccountId) : null
			;
	
			if (oAccount)
			{
				oAccount.updateExtended(oParameters);
				App.Api.showReport(Utils.i18n('SETTINGS/COMMON_REPORT_UPDATED_SUCCESSFULLY'));
			}
		}
	};
	
	/**
	 * @return Object
	 */
	CAccountPropertiesViewModel.prototype.prepareParameters = function ()
	{
		var
			oParameters = {
				'Action': 'UpdateAccountSettings',
				'AccountID': this.account().id(),
				'FriendlyName': this.friendlyName(),
				'Email': this.email(),
				'IncomingMailLogin': this.incomingMailLogin(),
				'IncomingMailServer': this.incomingMailServer(),
				'IncomingMailPort': Utils.pInt(this.incomingMailPort()),
				'OutgoingMailServer': this.outgoingMailServer(),
				'OutgoingMailLogin': this.outgoingMailLogin(),
				'OutgoingMailPort': Utils.pInt(this.outgoingMailPort()),
				'OutgoingMailAuth': this.useSmtpAuthentication() ? 2 : 0
			}
		;
		
		return oParameters;
	};
	
	/**
	 * @param {Object} oParameters
	 */
	CAccountPropertiesViewModel.prototype.saveData = function (oParameters)
	{
		this.updateFirstState();
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	/**
	 * Sends a request to the server to save the settings.
	 */
	CAccountPropertiesViewModel.prototype.onSaveClick = function ()
	{
		if (this.account())
		{
			this.saveData(this.prepareParameters());
		}
	};
	
	CAccountPropertiesViewModel.prototype.onChangePasswordClick = function ()
	{
		App.Screens.showPopup(ChangePasswordPopup, []);
	};
	
	/**
	 * @param {Object} oParams
	 * @param {Object} oAccount
	 */
	CAccountPropertiesViewModel.prototype.onShow = function (oParams, oAccount)
	{
		this.account(oAccount);
	};
	
	/**
	 * @param {?=} oParent
	 *
	 * @constructor
	 */ 
	function CAccountFoldersViewModel(oParent)
	{
		this.parent = oParent;
	
		this.highlighted = ko.observable(false).extend({'autoResetToFalse': 5000});
	
		this.collection = ko.observableArray(App.Cache.editedFolderList().collection());
	
		this.messageCount = ko.computed(function () {
			return App.Cache.editedFolderList().messageCount();
		}, this);
		
		this.enableButtons = ko.computed(function (){
			return App.Cache.editedFolderList().bInitialized();
		}, this);
		
		App.Cache.editedFolderList.subscribe(function(value){
			this.collection(value.collection());
		}, this);
		
		this.addNewFolderCommand = Utils.createCommand(this, this.onAddNewFolderClick, this.enableButtons);
		this.systemFoldersCommand = Utils.createCommand(this, this.onSystemFoldersClick, this.enableButtons);
	}
	
	CAccountFoldersViewModel.prototype.isChanged = function ()
	{
		return false;
	};
	
	CAccountFoldersViewModel.prototype.onAddNewFolderClick = function ()
	{
		App.Screens.showPopup(FolderCreatePopup);
	};
	
	CAccountFoldersViewModel.prototype.onSystemFoldersClick = function ()
	{
		App.Screens.showPopup(SystemFoldersPopup);
	};
	/**
	 * @param {?=} oParent
	 *
	 * @constructor
	 */ 
	function CAccountSignatureViewModel(oParent)
	{
		this.parent = oParent;
	
		this.account = ko.observable(null);
	
		this.type = ko.observable(false);
		this.options = ko.observable(0);
		this.signature = ko.observable('');
	
		this.account.subscribe(function () {
			this.getSignature();
		}, this);
		
		this.oHtmlEditor = new CHtmlEditorViewModel(false);
		
		this.signature.subscribe(function () {
			this.oHtmlEditor.setText(this.signature());
		}, this);
		
		AppData.Accounts.editedId.subscribe(function () {
			this.account(AppData.Accounts.getEdited());
		}, this);
		
		this.getSignature();
		
		this.firstState = null;
	}
	
	CAccountSignatureViewModel.prototype.getState = function ()
	{
		var aState = [
			this.type(),
			this.options(),
			this.oHtmlEditor.getText()
		];
		return aState.join(':');
	};
	
	CAccountSignatureViewModel.prototype.updateFirstState = function ()
	{
		this.firstState = this.getState();
	};
	
	CAccountSignatureViewModel.prototype.isChanged = function ()
	{
		if (this.firstState && this.getState() !== this.firstState)
		{
			return true;
		}
		else
		{
			return false;
		}
	};
		
	CAccountSignatureViewModel.prototype.getSignature = function ()
	{
		if (this.account())
		{
			if (this.account().signature() !== null)
			{
				this.type(this.account().signature().type());
				this.options(this.account().signature().options());
				this.signature(this.account().signature().signature());
			}
			else
			{
				var
					oParameters = {
						'Action': 'AccountSignature',
						'AccountID': this.account().id()
					}
				;
				
				App.Ajax.send(oParameters, this.onSignatureResponse, this);
			}
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CAccountSignatureViewModel.prototype.onSignatureResponse = function (oData, oParameters)
	{
		var
			oSignature = null,
			iAccountId = parseInt(oData.AccountID, 10)
		;
		
		if (oData && oData.Result && this.account() && iAccountId === this.account().id())
		{
			oSignature = new CSignatureModel();
			oSignature.parse(iAccountId, oData.Result);
			
			this.account().signature(oSignature);
			
			this.type(this.account().signature().type());
			this.options(this.account().signature().options());
			this.signature(this.account().signature().signature());
		}
	};
	
	CAccountSignatureViewModel.prototype.prepareParameters = function ()
	{
		var
			oParameters = {
				'Action': 'UpdateAccountSignature',
				'AccountID': this.account().id(),
				'Type': this.type() ? 1 : 0,
				'Options': this.options(),
				'Signature': this.signature()
			}
		;
		
		return oParameters;
	};
	
	/**
	 * @param {Object} oParameters
	 */
	CAccountSignatureViewModel.prototype.saveData = function (oParameters)
	{
		this.updateFirstState();
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	CAccountSignatureViewModel.prototype.onSaveClick = function ()
	{
		if (this.account())
		{
			this.signature(this.oHtmlEditor.getText());
			
			this.account().signature().type(this.type());
			this.account().signature().options(this.options());
			this.account().signature().signature(this.signature());
			
			this.saveData(this.prepareParameters());
		}
	};
	
	/**
	 * Parses the response from the server. If the settings are normally stored, then updates them. 
	 * Otherwise an error message.
	 * 
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CAccountSignatureViewModel.prototype.onResponse = function (oData, oParameters)
	{
		if (oData && oData.Result)
		{
			App.Api.showReport(Utils.i18n('SETTINGS/COMMON_REPORT_UPDATED_SUCCESSFULLY'));
		}
		else
		{
			App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
		}
	};
	
	/**
	 * @param {Array} aParams
	 * @param {Object} oAccount
	 */
	CAccountSignatureViewModel.prototype.onShow = function (aParams, oAccount)
	{
		this.account(oAccount);
		this.oHtmlEditor.initCrea(this.signature(), '');
		this.oHtmlEditor.setActivitySource(this.options);
	
		this.updateFirstState();
	};
	
	
	/**
	 * @param {?=} oParent
	 *
	 * @constructor
	 */
	function CAccountForwardViewModel(oParent)
	{
		this.account = ko.observable(null);
		this.available = ko.observable(false);
		this.loading = ko.observable(false);
	
		this.enable = ko.observable(false);
		this.email = ko.observable('');
	
		this.available = ko.computed(function () {
			var oAccount = this.account();
			return oAccount && oAccount.forward();
		}, this);
	
		this.account.subscribe(function () {
			this.getForward();
		}, this);
		
		this.firstState = null;
	}
	
	CAccountForwardViewModel.prototype.getState = function ()
	{
		var aState = [
			this.enable(),
			this.email()
		];
		
		return aState.join(':');
	};
	
	CAccountForwardViewModel.prototype.updateFirstState = function ()
	{
		this.firstState = this.getState();
	};
	
	CAccountForwardViewModel.prototype.isChanged = function()
	{
		if (this.firstState && this.getState() !== this.firstState)
		{
			return true;
		}
		else
		{
			return false;
		}
	};
	
	CAccountForwardViewModel.prototype.onResponse = function (oResult, oRequest)
	{
		this.loading(false);
	
		if (oRequest && oRequest.Action)
		{
			if ('GetForward' === oRequest.Action)
			{
				if (oResult && oResult.Result && oResult.AccountID && this.account())
				{
					var
						oAccount = null,
						oForward = new CForwardModel(),
						iAccountId = Utils.pInt(oResult.AccountID)
					;
	
					if (iAccountId)
					{
						oAccount = AppData.Accounts.getAccount(iAccountId);
						if (oAccount)
						{
							oForward.parse(iAccountId, oResult.Result);
							oAccount.forward(oForward);
							
							this.enable(oAccount.forward().enable);
							this.email(oAccount.forward().email);
							
							this.updateFirstState();
	
							if (iAccountId === this.account().id())
							{
								this.account.valueHasMutated();
							}
						}
					}
				}
			}
			else if ('UpdateForward' === oRequest.Action)
			{
				if (oResult && oResult.Result)
				{
					App.Api.showReport(Utils.i18n('SETTINGS/ACCOUNT_FORWARD_SUCCESS_REPORT'));
				}
				else
				{
					App.showErrorByCode(oResult.ErrorCode, Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
				}
			}
		}
		else
		{
			App.Api.showError(Utils.i18n('WARNING/UNKNOWN_ERROR'));
		}
	};
	
	CAccountForwardViewModel.prototype.prepareParameters = function ()
	{
		var
			oParameters = {
				'Action': 'UpdateForward',
				'AccountID': this.account().id(),
				'Enable': this.enable() ? '1' : '0',
				'Email': this.email()
			}
		;
		return oParameters;
	};
	
	CAccountForwardViewModel.prototype.saveData = function (oParameters)
	{
		this.updateFirstState();
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	CAccountForwardViewModel.prototype.onSaveClick = function ()
	{
		if (this.account())
		{
			var
				oForward = this.account().forward()
			;
	
			if (oForward)
			{
				oForward.enable = this.enable();
				oForward.email = this.email();
			}
	
			this.loading(true);
			this.saveData(this.prepareParameters());
		}
	};
	
	CAccountForwardViewModel.prototype.getForward = function()
	{
		if (this.account())
		{
			if (this.account().forward() !== null)
			{
				this.enable(this.account().forward().enable);
				this.email(this.account().forward().email);
				this.firstState = this.getState();
			}
			else
			{
				var
					oParameters = {
						'Action': 'GetForward',
						'AccountID': this.account().id()
					}
				;
	
				this.loading(true);
				this.updateFirstState();
				App.Ajax.send(oParameters, this.onResponse, this);
			}
		}
	};
	
	CAccountForwardViewModel.prototype.onShow = function (aParams, oAccount)
	{
		this.account(oAccount);
	};
	
	/**
	 * @param {?=} oParent
	 *
	 * @constructor
	 */ 
	function CAccountAutoresponderViewModel(oParent)
	{
		this.account = ko.observable(null);
		this.available = ko.observable(false);
		this.loading = ko.observable(false);
	
		this.enable = ko.observable(false);
		this.subject = ko.observable('');
		this.message = ko.observable('');
	
		this.available = ko.computed(function () {
			var oAccount = this.account();
			return oAccount && oAccount.autoresponder();
		}, this);
	
		this.account.subscribe(function () {
			this.getAutoresponder();
		}, this);
		
		this.firstState = null;
	}
	
	CAccountAutoresponderViewModel.prototype.getState = function ()
	{
		var aState = [
			this.enable(),
			this.subject(),
			this.message()	
		];
		
		return aState.join(':');
	};
	
	CAccountAutoresponderViewModel.prototype.updateFirstState = function ()
	{
		this.firstState = this.getState();
	};
	
	CAccountAutoresponderViewModel.prototype.isChanged = function()
	{
		if (this.firstState && this.getState() !== this.firstState)
		{
			return true;
		}
		else
		{
			return false;
		}
	};
	
	CAccountAutoresponderViewModel.prototype.onResponse = function (oResult, oRequest)
	{
		this.loading(false);
		
		if (oRequest && oRequest.Action)
		{
			if ('GetAutoresponder' === oRequest.Action)
			{
				if (oResult && oResult.Result && oResult.AccountID && this.account())
				{
					var
						oAccount = null,
						oAutoresponder = new CAutoresponderModel(),
						iAccountId = Utils.pInt(oResult.AccountID)
					;
	
					if (iAccountId)
					{
						oAccount = AppData.Accounts.getAccount(iAccountId);
						if (oAccount)
						{
							oAutoresponder.parse(iAccountId, oResult.Result);
							oAccount.autoresponder(oAutoresponder);
	
							if (iAccountId === this.account().id())
							{
								this.account.valueHasMutated();
							}
						}
					}
				}
			}
			else if ('UpdateAutoresponder' === oRequest.Action)
			{
				if (oResult && oResult.Result)
				{
					App.Api.showReport(Utils.i18n('SETTINGS/ACCOUNT_AUTORESPONDER_SUCCESS_REPORT'));
				}
				else
				{
					App.showErrorByCode(oResult.ErrorCode, Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
				}
			}
		}
		else
		{
			App.Api.showError(Utils.i18n('WARNING/UNKNOWN_ERROR'));
		}
	};
	
	CAccountAutoresponderViewModel.prototype.prepareParameters = function ()
	{
		var
			oParameters = {
				'Action': 'UpdateAutoresponder',
				'AccountID': this.account().id(),
				'Enable': this.enable() ? '1' : '0',
				'Subject': this.subject(),
				'Message': this.message()
			}
		;
		
		return oParameters;
	};
	
	CAccountAutoresponderViewModel.prototype.saveData = function (oParameters)
	{
		this.updateFirstState();
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	CAccountAutoresponderViewModel.prototype.onSaveClick = function ()
	{
		if (this.account())
		{
			var
				oAutoresponder = this.account().autoresponder()
			;
	
			if (oAutoresponder)
			{
				oAutoresponder.enable = this.enable();
				oAutoresponder.subject = this.subject();
				oAutoresponder.message = this.message();
			}
	
			this.loading(true);
			
			this.saveData(this.prepareParameters());
		}
	};
	
	CAccountAutoresponderViewModel.prototype.getAutoresponder = function()
	{
		if (this.account())
		{
			if (this.account().autoresponder() !== null)
			{
				this.enable(this.account().autoresponder().enable);
				this.subject(this.account().autoresponder().subject);
				this.message(this.account().autoresponder().message);
				
				this.updateFirstState();
			}
			else
			{
				var
					oParameters = {
						'Action': 'GetAutoresponder',
						'AccountID': this.account().id()
					}
				;
	
				this.loading(true);
				App.Ajax.send(oParameters, this.onResponse, this);
			}
		}
	};
	
	CAccountAutoresponderViewModel.prototype.onShow = function (aParams, oAccount)
	{
		this.account(oAccount);
	};
	
	
	/**
	 * @param {?=} oParent
	 * 
	 * @constructor
	 */
	function CAccountFiltersViewModel(oParent)
	{
		this.account = ko.observable(null);
		
		this.folderList = ko.observable(App.Cache.editedFolderList());
		
		App.Cache.editedFolderList.subscribe(function(list) {
			this.folderList(list);
		}, this);
	
		this.foldersOptions = ko.computed(function () {
			return this.folderList() ? this.folderList().getOptions(Utils.i18n('SETTINGS/ACCOUNT_FOLDERS_NOT_SELECTED'), true, true) : [];
		}, this);
		
		this.available = ko.observable(false);
		this.loading = ko.observable(false);
		this.collection = ko.observableArray([]);
	
		this.available = ko.computed(function () {
			var oAccount = this.account();
			return !!(oAccount && oAccount.filters());
		}, this);
	
		this.account.subscribe(function () {
			this.getFilters();
		}, this);
		
		this.fieldOptions = [
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_FIELD_FROM'), 'value': 0},
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_FIELD_TO'), 'value': 1},
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_FIELD_SUBJECT'), 'value': 2}
		];
	
		this.conditionOptions = [
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_COND_CONTAIN_SUBSTR'), 'value': 0},
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_COND_EQUAL_TO'), 'value': 1},
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_COND_NOT_CONTAIN_SUBSTR'), 'value': 2}
		];
		
		this.actionOptions = [
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_ACTION_MOVE'), 'value': 3},
			{'text': Utils.i18n('SETTINGS/ACCOUNT_FILTERS_ACTION_DELETE'), 'value': 1}
		];
		
		this.phaseArray = [''];
		
		_.each(Utils.i18n('SETTINGS/ACCOUNT_FILTERS_PHRASE').split(/\s/), function(item) {
			var index = this.phaseArray.length - 1;
			if (item.substr(0,1) === '%' || this.phaseArray[index].substr(-1,1) === '%') {
				this.phaseArray.push(item);
			} else {
				this.phaseArray[index] += ' ' + item;
			}
		}, this);
		
		this.firstState = null;
	}
	
	CAccountFiltersViewModel.prototype.getState = function ()
	{
		var sResult = ':',		
			aState = _.map(this.collection(), function(value){ 
			return value.toString(); 
		}, this);
		if (aState.length > 0)
		{
			sResult = aState.join(':');
		}
		return sResult;
	};
	
	CAccountFiltersViewModel.prototype.updateFirstState = function ()
	{
		this.firstState = this.getState();
	};
	
	CAccountFiltersViewModel.prototype.isChanged = function()
	{
		if (this.firstState && this.getState() !== this.firstState)
		{
			return true;
		}
		else
		{
			return false;
		}
	};
	
	CAccountFiltersViewModel.prototype.onResponse = function (oResult, oRequest)
	{
		this.loading(false);
		if (oRequest && oRequest.Action)
		{
			if ('GetSieveFilters' === oRequest.Action)
			{
	
				if (oResult && oResult.Result && oResult.AccountID && this.account())
				{
					var
						oAccount = null,
						oSieveFilters = new CSieveFiltersModel(),
						iAccountId = Utils.pInt(oResult.AccountID)
					;
	
					if (iAccountId)
					{
						oAccount = AppData.Accounts.getAccount(iAccountId);
						if (oAccount)
						{
							oSieveFilters.parse(iAccountId, oResult.Result);
							oAccount.filters(oSieveFilters);
							
							if (iAccountId === this.account().id())
							{
								this.account.valueHasMutated();
							}
						}
					}
				}
			}
			else if ('UpdateSieveFilters' === oRequest.Action)
			{
				if (oResult && oResult.Result)
				{
					App.Api.showReport(Utils.i18n('SETTINGS/ACCOUNT_FILTERS_SUCCESS_REPORT'));
				}
				else
				{
					App.Api.showError(Utils.i18n('SETTINGS/ERROR_SETTINGS_SAVING_FAILED'));
				}
			}
		}
		else
		{
			App.Api.showError(Utils.i18n('WARNING/UNKNOWN_ERROR'));
		}
	};
	
	CAccountFiltersViewModel.prototype.prepareParameters = function ()
	{
		var
			aFilters =_.map(this.collection(), function (oItem) {
				return {
					'Enable': oItem.enable() ? '1' : '0',
					'Field': oItem.field(),
					'Filter': oItem.filter(),
					'Condition': oItem.condition(),
					'Action': oItem.action(),
					'FolderFullName': oItem.folder()
				};
			}),
			oParameters = {
				'Action': 'UpdateSieveFilters',
				'AccountID': this.account().id(),
				'Filters': aFilters
			}
		;
		
		return oParameters;
	};
	
	CAccountFiltersViewModel.prototype.saveData = function (oParameters)
	{
		this.updateFirstState();
		App.Ajax.send(oParameters, this.onResponse, this);
	};
	
	CAccountFiltersViewModel.prototype.onSaveClick = function ()
	{
		if (this.account())
		{
			this.loading(true);
			this.saveData(this.prepareParameters());
		}
	};
	
	CAccountFiltersViewModel.prototype.getFilters = function()
	{
		if (this.account())
		{
			if (this.account().filters() !== null)
			{
				this.collection(this.account().filters().collection());
				this.updateFirstState();
			}
			else
			{
				var
					oParameters = {
						'Action': 'GetSieveFilters',
						'AccountID': this.account().id()
					}
				;
	
				this.loading(true);
				App.Ajax.send(oParameters, this.onResponse, this);
			}
		}
	};
	
	CAccountFiltersViewModel.prototype.onShow = function (aParams, oAccount)
	{
		this.account(oAccount);
	};
	
	/**
	 * @param {Object} oFilterToDelete
	 */
	CAccountFiltersViewModel.prototype.deleteFilter = function (oFilterToDelete)
	{
		this.collection.remove(oFilterToDelete);
	};
	
	CAccountFiltersViewModel.prototype.addFilter = function ()
	{
		if (this.account())
		{
			var oSieveFilter =  new CSieveFilterModel(this.account().id());
			this.collection.push(oSieveFilter);
		}
	};
	
	/**
	 * @param {string} sPart
	 * @param {string} sPrefix
	 * 
	 * @return {string}
	 */
	CAccountFiltersViewModel.prototype.displayFilterPart = function (sPart, sPrefix)
	{
		var sTemplate = '';
		if (sPart === '%FIELD%') {
			sTemplate = 'Field';
		} else if (sPart === '%CONDITION%') {
			sTemplate = 'Condition';
		} else if (sPart === '%STRING%') {
			sTemplate = 'String';
		} else if (sPart === '%ACTION%') {
			sTemplate = 'Action';
		} else if (sPart === '%FOLDER%') {
			sTemplate = 'Folder';
		} else if (sPart.substr(0, 9) === '%DEPENDED') {
			sTemplate = 'DependedText';
		} else {
			sTemplate = 'Text';
		}
	
		return sPrefix + sTemplate;
	};
	
	CAccountFiltersViewModel.prototype.getDependedText = function (sText)
	{	
		sText = Utils.pString(sText);
		
		if (sText) {
			sText = sText.replace(/%/g, '').split('=')[1] || '';
		}
		
		return sText;
	};
	
	CAccountFiltersViewModel.prototype.getDependedField = function (sText, oParent)
	{	
		sText = Utils.pString(sText);
		
		if (sText)
		{
			sText = sText.replace(/[=](.*)/g, '').split('-')[1] || '';
			sText = sText.toLowerCase();
		}
	
		return Utils.isUnd(oParent[sText]) ? false : oParent[sText]();
	};
	
	
	/**
	 * @constructor
	 */
	function CCalendarViewModel()
	{
		this.calendarIframe = ko.observable();
		this.calendarUrl = 'legacy/calendar.php';
		this.variableCalendarUrl = ko.observable(this.calendarUrl);
	}
	
	CCalendarViewModel.prototype.onRoute = function ()
	{
		var
			$iframe = $(this.calendarIframe()),
			oWin = null
		;
		
		if (AppData.User.isNeedToReloadCalendar())
		{
			this.variableCalendarUrl(this.calendarUrl + '?p=' + Math.random());
		}
		else if (this.calendarIframe())
		{
			if ($iframe.length > 0)
			{
				oWin = $iframe[0].contentWindow;
			}
			
			if (oWin && typeof oWin.RefreshData === 'function')
			{
				oWin.RefreshData();
			}
		}
	};
	
	CCalendarViewModel.prototype.onHide = function ()
	{
		App.EventsCache.requestCalendarList();
	};
	/**
	* @constructor
	*/
	function CFileStorageViewModel()
	{
		this.files = ko.observableArray();
	}
	
	CFileStorageViewModel.prototype.onApplyBindings = function ()
	{
		
	};
	
	CFileStorageViewModel.prototype.onShow = function ()
	{
		App.Ajax.send({'Action': 'Files'}, this.onFilesResponse, this);
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CFileStorageViewModel.prototype.onFilesResponse = function (oData, oParameters)
	{
		window.console.log(oData, oParameters);
		
		// just for testing
		var oFile = new CFileModel();
		oFile.name('suggest.docx').size(300).hash('quooqweytriouwter');
		this.files.removeAll();
		this.files.push(oFile);
		
		oFile = new CFileModel();
		oFile.name('tulips.png').size(3000).hash('lakjdhflasj');
		this.files.push(oFile);	
	};
	
	/**
	 * @param {Array} aParams
	 */
	CFileStorageViewModel.prototype.onRoute = function (aParams)
	{
		window.console.log(aParams);
	};
	
	CFileStorageViewModel.prototype.onHide = function ()
	{
		
	};
	
	CFileStorageViewModel.prototype.getPrivateFiles = function ()
	{
		App.Ajax.send({'Action': 'FilesPrivate'}, this.onFilesResponse, this);
	};
	
	CFileStorageViewModel.prototype.getCorporateFiles = function ()
	{
		App.Ajax.send({'Action': 'FilesCorporate'}, this.onFilesResponse, this);
	};
	
	
	/**
	 * @constructor
	 */
	function CScreens()
	{
		this.oScreens = {};
		this.oScreens[Enums.Screens.Information] = {
			'Model': CInformationViewModel,
			'TemplateName': 'Common_InformationViewModel'
		};
		this.oScreens[Enums.Screens.Login] = {
			'Model': CLoginViewModel,
			'TemplateName': 'Login_LoginViewModel'
		};
		this.oScreens[Enums.Screens.Header] = {
			'Model': CHeaderViewModel,
			'TemplateName': 'Common_HeaderViewModel'
		};
		this.oScreens[Enums.Screens.Mailbox] = {
			'Model': CMailViewModel,
			'TemplateName': 'Mail_LayoutSidePane_MailViewModel'
		};
		this.oScreens[Enums.Screens.BottomMailbox] = {
			'Model': CMailViewModel,
			'TemplateName': 'Mail_LayoutBottomPane_MailViewModel'
		};
		this.oScreens[Enums.Screens.SingleMessageView] = {
			'Model': CMessagePaneViewModel,
			'TemplateName': 'Mail_LayoutSidePane_MessagePaneViewModel'
		};
		this.oScreens[Enums.Screens.Compose] = {
			'Model': CComposeViewModel,
			'TemplateName': 'Mail_ComposeViewModel'
		};
		this.oScreens[Enums.Screens.SingleCompose] = {
			'Model': CComposeViewModel,
			'TemplateName': 'Mail_ComposeViewModel'
		};
		this.oScreens[Enums.Screens.Settings] = {
			'Model': CSettingsViewModel,
			'TemplateName': 'Settings_SettingsViewModel'
		};
		this.oScreens[Enums.Screens.Contacts] = {
			'Model': CContactsViewModel,
			'TemplateName': 'Contacts_ContactsViewModel'
		};
		this.oScreens[Enums.Screens.Calendar] = {
			'Model': CCalendarViewModel,
			'TemplateName': 'Calendar_CalendarViewModel'
		};
		this.oScreens[Enums.Screens.FileStorage] = {
			'Model': CFileStorageViewModel,
			'TemplateName': 'FileStorage_FileStorageViewModel'
		};
	
		this.currentScreen = ko.observable('');
	
		this.popupVisibility = ko.observable(false);
		
		this.informationScreen = ko.observable(null);
		
		this.popups = [];
	}
	
	CScreens.prototype.init = function ()
	{
		$('#pSevenContent').append($('#Layout').html());
		$('#pSevenContent').addClass('single_mode');
		
		_.defer(function () {
			if (!AppData.SingleMode)
			{
				$('#pSevenContent').removeClass('single_mode');
			}
		});
		
		this.informationScreen(this.showNormalScreen(Enums.Screens.Information));
	};
	
	/**
	 * @param {string} sScreen
	 * @param {?=} mParams
	 */
	CScreens.prototype.showCurrentScreen = function (sScreen, mParams)
	{
		if (this.currentScreen() !== sScreen)
		{
			this.hideCurrentScreen();
			this.currentScreen(sScreen);
		}
	
		this.showNormalScreen(sScreen, mParams);
	};
	
	CScreens.prototype.hideCurrentScreen = function ()
	{
		if (this.currentScreen().length > 0)
		{
			this.hideScreen(this.currentScreen());
		}
	};
	
	/**
	 * @param {string} sScreen
	 */
	CScreens.prototype.hideScreen = function (sScreen)
	{
		var
			sScreenId = sScreen,
			oScreen = this.oScreens[sScreenId]
		;
	
		if (typeof oScreen !== 'undefined' && oScreen.bInitialized)
		{
			oScreen.Model.hideViewModel();
		}
	};
	
	/**
	 * @param {string} sScreen
	 * @param {?=} mParams
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
	 */
	CScreens.prototype.showError = function (sMessage, bHtml, bNotHide)
	{
		if (this.informationScreen())
		{
			this.informationScreen().showError(sMessage, bHtml, bNotHide);
		}
	};
	
	
	/**
	 * @constructor
	 */
	function CCache()
	{
		this.currentAccountId = AppData.Accounts.currentId;
		
		this.currentAccountId.subscribe(function (iAccountID) {
			var
				oAccount = AppData.Accounts.getAccount(iAccountID),
				oFolderList = this.oFolderListItems[iAccountID],
				oParameters = {
					'Action': 'FolderList',
					'AccountID': iAccountID
				}
			;
	
			if (oAccount)
			{
				_.delay(_.bind(oAccount.updateQuotaParams, oAccount), 5000);
				
				this.messagesLoading(true);
				
				if (oFolderList)
				{
					this.folderList(oFolderList);
				}
				else
				{
					this.folderList(new CFolderListModel());
					this.messages([]);
					this.currentMessage(null);
					App.Ajax.send(oParameters, this.onFolderListResponse, this);
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
					'Action': 'FolderList',
					'AccountID': value
				};
				App.Ajax.send(oParameters, this.onFolderListResponse, this);
			}
		}, this);
		
		this.oFolderListItems = {};
		
		this.quotaChangeTrigger = ko.observable(false);
		
		this.checkMailStarted = ko.observable(false);
		
		this.folderList = ko.observable(new CFolderListModel());
		this.editedFolderList = ko.observable(new CFolderListModel());
		
		this.newMessagesCount = ko.computed(function () {
			var
				oInbox = this.folderList().inboxFolder()
			;
			return oInbox ? oInbox.unseenMessageCount() : 0;
		}, this);
	
		this.messages = ko.observableArray([]);
		
		this.uidList = ko.observable(new CUidListModel());
		this.page = ko.observable(1);
		
		this.messagesLoading = ko.observable(true);
		this.messagesLoadingError = ko.observable(false);
		
		this.currentMessage = ko.observable(null);
		
		this.aResponseHandlers = [];
	}
	
	/**
	 * @public
	 */
	CCache.prototype.init = function ()
	{
		var oCache = null;
		
		if (AppData.SingleMode && window.opener)
		{
			oCache = window.opener.App.Cache;
			
			this.oFolderListItems = oCache.oFolderListItems;
			this.folderList(oCache.folderList());
			this.messages(oCache.messages());
			this.uidList(oCache.uidList());
			this.page(oCache.page());
		}
		else
		{
			this.currentAccountId.valueHasMutated();
		}
	};
	
	CCache.prototype.getCurrentFolder = function ()
	{
		return this.folderList().currentFolder();
	};
	
	/**
	 * @param {number} iAccountId
	 * @param {string} sFolderFullName
	 */
	CCache.prototype.getFolderByFullName = function (iAccountId, sFolderFullName)
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
	
	/**
	 * @param {number=} iAccountID
	 */
	CCache.prototype.getFolderList = function (iAccountID)
	{
		var oParameters = {'Action': 'FolderList'};
		if (!Utils.isUnd(iAccountID))
		{
			oParameters['AccountID'] = iAccountID;
		}
		App.Ajax.send(oParameters, this.onFolderListResponse, this);
	};
	
	/**
	 * @param {number} iAccountId
	 * @param {string} sFullName
	 * @param {string} sUid
	 * @param {string} sReplyType
	 */
	CCache.prototype.markMessageReplied = function (iAccountId, sFullName, sUid, sReplyType)
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
	 * @param {number} iAccountId
	 * @param {string} sFullName
	 * @param {number} iCount
	 * @param {number} iUnseenCount
	 * @param {string} sUidNext
	 * @param {string} sHash
	 */
	CCache.prototype.setFolderCounts = function (iAccountId, sFullName, iCount, iUnseenCount, sUidNext, sHash)
	{
		var
			oFolderList = this.oFolderListItems[iAccountId],
			oFolder = null,
			bFolderHasChanges = false,
			bCheckMailStarted = false
		;
		
		if (oFolderList)
		{
			oFolder = oFolderList.getFolderByFullName(sFullName);
			if (oFolder)
			{
				bFolderHasChanges = oFolder.setRelevantInformation(sUidNext, sHash, iCount, iUnseenCount);
				if (bFolderHasChanges && oFolder.selected())
				{
					bCheckMailStarted = true;
					this.startCheckMail();
				}
			}
		}
		
		this.checkMailStarted(bCheckMailStarted);
	};
	
	/**
	 * @param {Object} oUidList
	 * @param {number} iOffset
	 * @param {Object} oMessages
	 * @param {boolean} bCurrent
	 */
	CCache.prototype.setMessagesFromUidList = function (oUidList, iOffset, oMessages, bCurrent)
	{
		var
			aUids = oUidList.getUidsForOffset(iOffset, oMessages),
			aMessages = _.map(aUids, function (sUid) {
				return oMessages[sUid];
			}, this),
			iMessagesCount = aMessages.length,
			oCurrFolder = this.folderList().currentFolder()
		;
		
		if (bCurrent)
		{
			this.messages(aMessages);
			
			if ((iOffset + iMessagesCount < oCurrFolder.messageCount()) &&
				(iMessagesCount < AppData.User.MailsPerPage))
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
	
	CCache.prototype.startCheckMail = function ()
	{
		var
			sFolder = this.folderList().currentFolderFullName(),
			iPage = this.page(),
			sSearch = this.uidList().search()
		;
		
		if (sFolder !== '')
		{
			this.requestCurrentMessageList(sFolder, iPage, sSearch);
		}
	};
	
	CCache.prototype.executeCheckMail = function ()
	{
		var
			oFolderList = this.folderList(),
			aFolders = oFolderList ? oFolderList.getInboxAndCurrentFoldersArray() : [],
			oParameters = {
				'Action': 'FolderCounts',
				'Folders': aFolders,
				'AccountID': oFolderList ? oFolderList.iAccountId : 0
			}
		;
		
		if (!this.checkMailStarted() && aFolders.length > 0)
		{
			this.checkMailStarted(true);
			App.Ajax.send(oParameters, this.onFolderCountsResponse, this);
		}
	};
	
	/**
	 * @param {string} sFolder
	 * @param {number} iPage
	 * @param {string} sSearch
	 */
	CCache.prototype.changeCurrentMessageList = function (sFolder, iPage, sSearch)
	{
		this.requestCurrentMessageList(sFolder, iPage, sSearch);
	};
	
	/**
	 * @param {string} sFolder
	 * @param {number} iPage
	 * @param {string} sSearch
	 */
	CCache.prototype.requestCurrentMessageList = function (sFolder, iPage, sSearch)
	{
		var
			oRequestData = this.requestMessageList(sFolder, iPage, sSearch, true)
		;
		
		this.uidList(oRequestData.UidList);
		this.page(iPage);
		
		this.messagesLoading(oRequestData.DataExpected);
		this.messagesLoadingError(false);
		
		App.Prefetcher.start();
	};
	
	/**
	 * @param {string} sFolder
	 * @param {number} iPage
	 * @param {string} sSearch
	 * @param {boolean} bCurrent
	 */
	CCache.prototype.requestMessageList = function (sFolder, iPage, sSearch, bCurrent)
	{
		var
			oFolderList = this.oFolderListItems[this.currentAccountId()],
			oFolder = (oFolderList) ? oFolderList.getFolderByFullName(sFolder) : null,
			oUidList = (oFolder) ? oFolder.getUidList(sSearch) : null,
			iOffset = (iPage - 1) * AppData.User.MailsPerPage,
			aUids = (oUidList) ? this.setMessagesFromUidList(oUidList, iOffset, oFolder.oMessages, bCurrent) : [],
			oParameters = {
				'Action': 'MessageList',
				'Folder': sFolder,
				'Offset': iOffset,
				'Limit': AppData.User.MailsPerPage,
				'Search': sSearch,
				'ExistenUids': (oFolder.hasChanges()) ? [] : aUids
			},
			bStartRequest = false,
			bDataExpected = false,
			fCallBack = bCurrent ? this.onCurrentMessageListResponse : this.onMessageListResponse
		;
		
		if (oUidList)
		{
			bDataExpected = 
				(oUidList.searchCount() === -1) ||
				((iOffset + aUids.length < oUidList.searchCount()) && (aUids.length < AppData.User.MailsPerPage))
			;
			bStartRequest = oFolder.hasChanges() || bDataExpected;
		}
		
		if (bStartRequest)
		{
			App.Ajax.send(oParameters, fCallBack, this);
		}
		
		return {UidList: oUidList, RequestStarted: bStartRequest, DataExpected: bDataExpected};
	};
	
	CCache.prototype.executeEmptyTrash = function ()
	{
		var oFolder = this.folderList().trashFolder();
		if (oFolder)
		{
			oFolder.emptyFolder();
		}
	};
	
	CCache.prototype.executeEmptySpam = function ()
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
	CCache.prototype.onClearFolder = function (oFolder)
	{
		if (oFolder && oFolder.selected())
		{
			this.messages.removeAll();
			this.currentMessage(null);
		}
	};
	
	/**
	 * @param {string} sToFolderFullName
	 * @param {Array} aUids
	 * @param {boolean} bAnimateRecive
	 */
	CCache.prototype.moveMessagesToFolder = function (sToFolderFullName, aUids, bAnimateRecive)
	{
		var
			oCurrFolder = this.folderList().currentFolder(),
			oToFolder = this.folderList().getFolderByFullName(sToFolderFullName),
			oParameters = {
				'Action': 'MessageMove',
				'Folder': oCurrFolder ? oCurrFolder.fullName() : '',
				'ToFolder': sToFolderFullName,
				'Uids': aUids.join(',')
			},
			oDiffs = null
		;
		
		if (oCurrFolder)
		{
			oDiffs = oCurrFolder.markDeletedByUids(aUids);
			oToFolder.addMessagesCountsDiff(oDiffs.MinusDiff, oDiffs.UnseenMinusDiff);
			
			if (Utils.isUnd(bAnimateRecive) ? true : !!bAnimateRecive)
			{
				oToFolder.recivedAnim(true);
			}
	
			this.excludeDeletedMessages();
			
			oToFolder.markHasChanges();
			
			App.Ajax.send(oParameters, this.onMoveMessagesResponse, this);
		}
	};
	
	CCache.prototype.excludeDeletedMessages = function ()
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
	 * @param {string} sFolderFullName
	 */
	CCache.prototype.removeMessagesFromCacheForFolder = function (sFolderFullName)
	{
		var oFolder = this.folderList().getFolderByFullName(sFolderFullName);
		if (oFolder)
		{
			oFolder.markHasChanges();
		}
	};
	
	/**
	 * @param {Array} aUids
	 */
	CCache.prototype.deleteMessages = function (aUids)
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
	CCache.prototype.deleteMessagesFromFolder = function (oFolder, aUids)
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
	};
	
	/**
	 * @param {boolean} bAlwaysForSender
	 */
	CCache.prototype.showExternalPictures = function (bAlwaysForSender)
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
	 * @param {string} sUid
	 */
	CCache.prototype.setCurrentMessage = function (sUid)
	{
		var
			oCurrFolder = this.folderList().currentFolder(),
			oMessage = oCurrFolder ? oCurrFolder.oMessages[sUid] : null
		;
	
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
		}
	};
	
	/**
	 * @param {Object} oMessage
	 * @param {string} sUid
	 */
	CCache.prototype.onCurrentMessageResponse = function (oMessage, sUid)
	{
		var sCurrentUid = this.currentMessage() ? this.currentMessage().uid() : '';
		
		if (oMessage === null && sCurrentUid === sUid)
		{
			this.currentMessage(null);
		}
	};
	
	/**
	 * @param {string} sFullName
	 * @param {string} sUid
	 * @param {Function} fResponseHandler
	 * @param {Object} oContext
	 */
	CCache.prototype.getMessage = function (sFullName, sUid, fResponseHandler, oContext)
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
	CCache.prototype.executeGroupOperation = function (sAction, aUids, sField, bSetAction)
	{
		var
			oCurrFolder = this.folderList().currentFolder(),
			oParameters = {
				'Action': sAction,
				'Folder': oCurrFolder ? oCurrFolder.fullName() : '',
				'Uids': aUids.join(','),
				'SetAction': bSetAction ? 1 : 0
			},
			iOffset = (this.page() - 1) * AppData.User.MailsPerPage
		;
	
		if (oCurrFolder)
		{
			App.Ajax.send(oParameters);
	
			oCurrFolder.executeGroupOperation(sField, aUids, bSetAction);
	
			this.setMessagesFromUidList(this.uidList(), iOffset, oCurrFolder.oMessages, true);
		}
	};
	
	/**
	 * private
	 */
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCache.prototype.onFolderListResponse = function (oData, oParameters)
	{
		var
			oFolderList = new CFolderListModel(),
			iAccountId = parseInt(oData.AccountID, 10),
			oFolderListOld = this.oFolderListItems[iAccountId],
			fMergeFolderList = function (collection) {
				var
					iIndex = 0,
					iLen = 0,
					oItem = null,
					oFolder
				;
				
				for (iIndex = 0, iLen = collection.length; iIndex < iLen; iIndex++)
				{
					oItem = collection[iIndex];
					oFolder = oFolderList.getFolderByFullName(oItem.fullName());
					if (oFolder)
					{
						oFolder.messageCount(oItem.messageCount());
						oFolder.hasExtendedInfo(oItem.hasExtendedInfo());
					}
					fMergeFolderList(oItem.subfolders());
				}
			}
		;		
		
		if (oData.Result === false)
		{
			if (oParameters.AccountID === this.currentAccountId() && this.messages().length === 0)
			{
				this.messagesLoading(false);
				this.messagesLoadingError(true);
			}
		}
		else
		{
			oFolderList.parse(iAccountId, oData.Result);
			this.oFolderListItems[iAccountId] = oFolderList;
			
			setTimeout(_.bind(this.getAllFolderCounts, this, iAccountId), 3000);
			if (oFolderListOld)
			{
				fMergeFolderList(oFolderListOld.collection());
			}
			
			if (this.currentAccountId() === iAccountId)
			{
				this.folderList(oFolderList);
			}
			if (this.editedAccountId() === iAccountId)
			{
				this.editedFolderList(oFolderList);
			}
		}
	},
	
	/**
	 * @param {Object} oFolderList
	 */
	CCache.prototype.setCurrentFolderList = function (oFolderList)
	{
		var iAccountId = oFolderList.iAccountId;
		
		if (iAccountId === this.currentAccountId() && iAccountId !== this.folderList().iAccountId)
		{
			this.folderList(oFolderList);
		}
	},
	
	/**
	 * @param {number} iAccountId
	 */
	CCache.prototype.getAllFolderCounts = function (iAccountId)
	{
		var
			oFolderList = this.oFolderListItems[iAccountId],
			aFolders = oFolderList ? oFolderList.getFoldersWithoutCountInfo() : [],
			oParameters = {
				'Action': 'FolderCounts',
				'Folders': aFolders,
				'AccountID': iAccountId
			}
		;
		
		if (aFolders.length > 0)
		{
			App.Ajax.send(oParameters, this.onFolderCountsResponse, this);
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCache.prototype.onFolderCountsResponse = function (oData, oParameters)
	{
		var self = this;
		if (oData.Result !== false)
		{
			_.each(oData.Result, function(aValue, sKey) {
				if (_.isArray(aValue) && aValue.length > 0)
				{
					self.setFolderCounts(oData.AccountID, sKey, aValue[0], aValue[1], aValue[2], aValue[3]);
				}
			});
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCache.prototype.onCurrentMessageListResponse = function (oData, oParameters)
	{
		if (oData.Result === false)
		{
			if (this.messagesLoading() === true)
			{
				this.messagesLoadingError(true);
			}
			this.messagesLoading(false);
			this.checkMailStarted(false);
		}
		else
		{
			this.messagesLoadingError(false);
			this.parseMessageList(oData, oParameters);
		}
	},
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCache.prototype.onMessageListResponse = function (oData, oParameters)
	{
		if (oData && oData.Result)
		{
			this.parseMessageList(oData, oParameters);
		}
	},
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCache.prototype.parseMessageList = function (oData, oParameters)
	{
		var
			oResult = oData.Result,
			oFolderList = this.oFolderListItems[oData.AccountID],
			oFolder = null,
			oUidList = null
		;
		
		if (oResult !== false && oResult['@Object'] === 'Collection/MessageCollection')
		{
			oFolder = oFolderList.getFolderByFullName(oResult.FolderName);
			
			// perform before getUidList, because in case of a mismatch the uid list will be pre-cleaned
			oFolder.setRelevantInformation(oResult.UidNext.toString(), oResult.FolderHash, 
				oResult.MessageCount, oResult.MessageUnseenCount);
			oFolder.removeAllMessageListsFromCacheIfHasChanges();
			
			oUidList = oFolder.getUidList(oResult.Search);
			oUidList.setUidsAndCount(oResult);
			
			_.each(oResult['@Collection'], function (oRawMessage) {
				var oFolderMessage = oFolder.oMessages[oRawMessage.Uid.toString()];
				
				if (!oFolderMessage)
				{
					oFolderMessage = new CMessageModel();
				}
				oFolderMessage.parse(oRawMessage, oData.AccountID);
				
				oFolder.oMessages[oFolderMessage.uid()] = oFolderMessage;
			}, this);
			
			if (
					this.currentAccountId() === oData.AccountID &&
					this.folderList().currentFolderFullName() === oResult.FolderName &&
					this.uidList().search() === oUidList.search()
				)
			{
				if (this.page() === ((oResult.Offset / AppData.User.MailsPerPage) + 1))
				{
					this.setMessagesFromUidList(oUidList, oResult.Offset, oFolder.oMessages, true);
					this.messagesLoading(false);
					this.checkMailStarted(false);
				}
				this.uidList(oUidList);
			}
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CCache.prototype.onMoveMessagesResponse = function (oData, oParameters)
	{
		var
			oResult = oData.Result,
			oFolder = this.folderList().getFolderByFullName(oParameters.Folder),
			oToFolder = this.folderList().getFolderByFullName(oParameters.ToFolder),
			bToFolderTrash = (oToFolder && (oToFolder.type() === Enums.FolderTypes.Trash)),
			bToFolderSpam = (oToFolder && (oToFolder.type() === Enums.FolderTypes.Spam)),
			oDiffs = null,
			sConfirm = bToFolderTrash ? Utils.i18n('MAILBOX/CONFIRM_MESSAGES_DELETE_WITHOUT_TRASH') :
				Utils.i18n('MAILBOX/CONFIRM_MESSAGES_MARK_SPAM_WITHOUT_SPAM'),
			fDeleteMessages = _.bind(function (bResult) {
				if (bResult && oFolder)
				{
					this.deleteMessagesFromFolder(oFolder, oParameters.Uids.split(','));
				}
			}, this),
			sCurrFolderFullName = this.folderList().currentFolderFullName()
		;
		
		if (oResult === false)
		{
			oDiffs = oFolder.revertDeleted(oParameters.Uids.split(','));
			if (oToFolder)
			{
				oToFolder.addMessagesCountsDiff(-oDiffs.PlusDiff, -oDiffs.UnseenPlusDiff);
				if (oData.ErrorCode === Enums.Errors.ImapQuota && (bToFolderTrash || bToFolderSpam))
				{
					App.Screens.showPopup(ConfirmPopup, [sConfirm, fDeleteMessages]);
				}
				else
				{
					App.Api.showError(Utils.i18n('MAILBOX/ERROR_MOVING_MESSAGES'));
				}
			}
			else
			{
				App.Api.showError(Utils.i18n('MAILBOX/ERROR_DELETING_MESSAGES'));
			}
		}
		else
		{
			oFolder.commitDeleted(oParameters.Uids.split(','));
		}
		
		if (sCurrFolderFullName === oFolder.fullName() || oToFolder && sCurrFolderFullName === oToFolder.fullName())
		{
			this.requestCurrentMessageList(sCurrFolderFullName, this.page(), this.uidList().search());
		}
		
		if (sCurrFolderFullName !== oFolder.fullName())
		{
			setTimeout(function () {
				App.Prefetcher.startFolderPrefetch(oFolder);
			}, 1000);
		}
		
		if (oToFolder && sCurrFolderFullName !== oToFolder.fullName())
		{
			setTimeout(function () {
				App.Prefetcher.startFolderPrefetch(oToFolder);
			}, 1000);
		}
	};
	
	/**
	 * @param {string} sSearch
	 */
	CCache.prototype.searchMessagesInCurrentFolder = function (sSearch)
	{
		var sUid = this.currentMessage() ? this.currentMessage().uid() : '';
		
		App.Routing.setHash(App.Links.mailbox(this.folderList().currentFolderFullName(), 1, sUid, sSearch));
	};
	
	/**
	 * @param {string} sSearch
	 */
	CCache.prototype.searchMessagesInInbox = function (sSearch)
	{
		App.Routing.setHash(App.Links.mailbox(this.folderList().inboxFolderFullName(), 1, '', sSearch));
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
					'Action': 'ContactByEmail',
					'Email': sEmail
				}
			;
	
			if (oContact !== undefined)
			{
				fResponseHandler.apply(oResponseContext, [oContact]);
			}
			else
			{
				this.responseHandlers[sEmail] = {
					Handler: fResponseHandler,
					Context: oResponseContext
				};
				App.Ajax.send(oParameters, this.onContactByEmailResponse, this);
			}
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CContactsCache.prototype.onContactByEmailResponse = function (oData, oParameters)
	{
		var
			oContact = null,
			oResponseData = this.responseHandlers[oParameters.Email]
		;
		
		if (oData.Result)
		{
			oContact = new CContactModel();
			oContact.parse(oData.Result);
		}
		
		this.contacts[oParameters.Email] = oContact;
		
		if (oResponseData)
		{
			oResponseData.Handler.apply(oResponseData.Context, [oContact]);
			this.responseHandlers[oParameters.Email] = undefined;
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
	CContactsCache.prototype.markVcardsNonexistent = function (aUids)
	{
		_.each(this.vcardAttachments, function (oVcard) {
			if (-1 !== _.indexOf(aUids, oVcard.uid()))
			{
				oVcard.isExists(false);
			}
		});
	};
	
	/**
	 * @constructor
	 */
	function CEventsCache()
	{
		this.calendars = ko.observableArray([]);
		
		this.icalAttachments = [];
		
		this.recivedAnim = ko.observable(false).extend({'autoResetToFalse': 500});
	}
	
	/**
	 * @param {Object} oIcal
	 */
	CEventsCache.prototype.addIcal = function (oIcal)
	{
		this.icalAttachments.push(oIcal);
		if (this.calendars().length === 0)
		{
			this.requestCalendarList();
		}
	};
	
	/**
	 * @param {Object} oData
	 * @param {Object} oParameters
	 */
	CEventsCache.prototype.onCalendarListResponse = function (oData, oParameters)
	{
		var
			oResult = oData.Result,
			oUserCalendars = oResult ? oResult['user'] : null
		;
		
		if (oUserCalendars)
		{
			this.calendars(_.map(oUserCalendars, function (oCalendar) {
				return {'name': oCalendar['name'], 'id': oCalendar['calendar_id']};
			}));
		}
	};
	
	CEventsCache.prototype.requestCalendarList = function ()
	{
		App.Ajax.send({'Action': 'CalendarList'}, this.onCalendarListResponse, this);
	};
	
	/**
	 * @param {string} sUid
	 */
	CEventsCache.prototype.markIcalNonexistent = function (sUid)
	{
		_.each(this.icalAttachments, function (oIcal) {
			if (sUid === oIcal.uid())
			{
				oIcal.onEventDelete();
			}
		});
	};
	
	/**
	 * @param {string} sUid
	 */
	CEventsCache.prototype.markIcalTentative = function (sUid)
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
	CEventsCache.prototype.markIcalAccepted = function (sUid)
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
	function CApp()
	{
		this.Ajax = new CAjax();
		this.Routing = new CRouting();
		this.Screens = new CScreens();
		this.Links = new CLinkBuilder();
		this.Api = new CApi();
		this.MessageSender = new CMessageSender();
		this.Prefetcher = new CPrefetcher();
		this.Cache = null;
		this.ContactsCache = new CContactsCache();
		this.EventsCache = new CEventsCache();
		this.Storage = new CStorage();
	
		this.$downloadIframe = null;
	
		this.currentScreen = this.Screens.currentScreen;
		this.currentScreen.subscribe(this.setTitle, this);
		this.focused = ko.observable(true);
		this.focused.subscribe(this.setTitle, this);
		
		this.init();
		
		this.newMessagesCount = this.Cache.newMessagesCount;
		this.newMessagesCount.subscribe(this.setTitle, this);
		
		this.currentMessage = this.Cache.currentMessage;
		this.currentMessage.subscribe(this.setTitle, this);
		
		this.initHeaderInfo();
	}
	
	// proto
	CApp.prototype.init = function ()
	{
		var
			ga = null,
			s = null,
			oUserSettings = new CUserSettingsModel(),
			oAccounts = new CAccountListModel(),
			oAppSettings = new CAppSettingsModel()
		;
	
		oUserSettings.parse(AppData['User']);
		AppData.User = oUserSettings;
	
		oAccounts.parse(AppData['Default'], AppData['Accounts']);
		AppData.Accounts = oAccounts;
	
		oAppSettings.parse(AppData['App']);
		AppData.App = oAppSettings;
	
		this.Cache = new CCache();
	
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
	
	CApp.prototype.tokenProblem = function ()
	{
		var
			sReloadFunc= 'window.location.reload(); return false;',
			sHtmlError = Utils.i18n('WARNING/TOKEN_PROBLEM_HTML', {'RELOAD_FUNC': sReloadFunc})
		;
		
		AppData.Auth = false;
		App.Api.showError(sHtmlError, true, true);
	};
	
	CApp.prototype.authProblem = function ()
	{
		window.location.reload();
	};
	
	CApp.prototype.run = function ()
	{
		Utils.log('run', AppData);
	
		this.Screens.init();
	
		if (AppData && AppData['Auth'])
		{
			this.Routing.init();
			this.Cache.init();
			
			if (!AppData['SingleMode'])
			{
				this.doFirstRequests();
			}
		}
		else
		{
			this.Screens.showCurrentScreen(Enums.Screens.Login);
			if (AppData && AppData['LastErrorCode'] === Enums.Errors.AuthError)
			{
				this.Api.showError(Utils.i18n('WARNING/AUTH_PROBLEM'), false, true);
			}
		}
	};
	
	CApp.prototype.doFirstRequests = function ()
	{
		var oTz = window.jstz ? window.jstz.determine() : null;
		App.Ajax.send({
			'Action': 'IsAuth',
			'ClientTimeZone': oTz ? oTz.name() : ''
		});
	
		window.setTimeout(function () {
			App.Ajax.send({'Action': 'DoServerInitializations'});
		}, 30000);
	};
	
	/**
	 * @param {number} iErrorCode
	 * @param {string} sDefaultError
	 */
	CApp.prototype.showErrorByCode = function (iErrorCode, sDefaultError)
	{
		var sError = sDefaultError;
	
		if (Enums.Errors.DemoLimitations === iErrorCode)
		{
			sError = Utils.i18n('DEMO/WARNING_THIS_FEATURE_IS_DISABLED');
		}
		
		App.Api.showError(sError);
	};
	
	/**
	 * Downloads by url through iframe.
	 *
	 * @param {string} sUrl
	 */
	CApp.prototype.downloadByUrl = function (sUrl)
	{
		if (this.$downloadIframe === null)
		{
			this.$downloadIframe = $('<iframe style="display: none;"></iframe>').appendTo(document.body);
		}
	
		this.$downloadIframe.attr('src', sUrl);
	};
	
	CApp.prototype.initHeaderInfo = function ()
	{
		if ($.browser.msie)
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
	
	CApp.prototype.onFocus = function ()
	{
		this.focused(true);
	};
	
	CApp.prototype.onBlur = function ()
	{
		this.focused(false);
	};
	
	CApp.prototype.setTitle = function ()
	{
		var
			sTitle = '',
			sNewMessagesCount = this.newMessagesCount(),
			bNotChange = AppData.SingleMode && !this.focused()
		;
		
		if (!bNotChange)
		{
			if (this.focused() || sNewMessagesCount === 0)
			{
				sTitle = this.getTitleByScreen();
			}
			else
			{
				sTitle = Utils.i18n('TITLE/HAS_UNSEEN_MESSAGES_PLURAL', {'COUNT': sNewMessagesCount}, null, sNewMessagesCount) + ' - ' + AppData.Accounts.getEmail();
			}
	
			document.title = '.';
			document.title = sTitle;
		}
	};
	
	CApp.prototype.getTitleByScreen = function ()
	{
		var
			sTitle = '',
			sSubject = ''
		;
		
		try
		{
			if (App.Cache.currentMessage())
			{
				sSubject = App.Cache.currentMessage().subject();
			}
		}
		catch (oError) {}
		
		switch (this.currentScreen())
		{
			case Enums.Screens.Login:
				sTitle = Utils.i18n('TITLE/LOGIN');
				break;
			case Enums.Screens.Mailbox:
			case Enums.Screens.BottomMailbox:
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
			case Enums.Screens.Contacts:
				sTitle = Utils.i18n('TITLE/CONTACTS');
				break;
			case Enums.Screens.Calendar:
				sTitle = Utils.i18n('TITLE/CALENDAR');
				break;
			case Enums.Screens.Settings:
				sTitle = Utils.i18n('TITLE/SETTINGS');
				break;
			case Enums.Screens.FileStorage:
				sTitle = Utils.i18n('TITLE/FILESTORAGE');
				break;
		}
	
		return sTitle;
	};
	
	$(function () {
		window.setTimeout(function () {
			App = new CApp();
			App.run();
			window.App = App;
		}, 10);
	});
	
	// export
	window.Enums = Enums;
	
	$('html').removeClass('no-js').addClass('js');
}(jQuery, window, ko, crossroads, hasher));
