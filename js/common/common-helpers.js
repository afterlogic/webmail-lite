/*
 * Classes:
 *  CSlideItem(sObjName)
 *  CBrowser()
 *  CInfoContainer(fadeEffect)
 * Objects:
 *  WindowOpener
 *  Validator
 *  Keys
 *  HeaderInfo
 *  Favicon
 *  XmlHelper
 *  ButtonsBuilder
 *  Cookies
 *  PreFetch
 *  Statistics
 *  Dialog
 *  Logger
 */

function CSlideItem(sObjName)
{
	this.obj = document.getElementById(sObjName);

	this.bMoving = false;
	this.iEndHeight = 0;
	this.iStartTime = 0;
	this.iTimerID = -1;
	this.sDir = 'hide';
	this.bLogin = false;

	this.startSlide = function (sDir)
	{
		this.bMoving = true;
		this.sDir = sDir;

		if (this.sDir === 'hide') {
			this.iEndHeight = this.obj.offsetHeight;
		}
		else if (this.iEndHeight === 0) {
			this.iEndHeight = parseInt(this.obj.style.height, 10);
			this.obj.style.height = '1px';
			this.obj.style.display = '';
			this.bLogin = true;
		}
		this.iStartTime = (new Date()).getTime();
	};

	this.endSlide = function ()
	{
		this.bMoving = false;
		clearInterval(this.iTimerID);
	};
}

var Slider = {
	iTimerLen: 40,
	iSlideAniLen: 520,
	aItems: [],

	slideIt: function (sObjName, sDir)
	{
		var oItem = this.aItems[sObjName];
		if (oItem == undefined) {
			oItem = new CSlideItem(sObjName);
		}
		if (oItem.bMoving) {
			return;
		}
		oItem.startSlide(sDir);
		oItem.iTimerID = setInterval(function () {
			Slider.slideTick(sObjName);
		}, this.iTimerLen);
		this.aItems[sObjName] = oItem;
	},

	_endSlide: function (oItem) {
		oItem.endSlide();
		if (oItem.bLogin) {
			var iEndHeight = oItem.iEndHeight;
			if (oItem.sDir === 'hide') {
				oItem.obj.style.display = 'none';
				oItem.iEndHeight = 0;
			}
			oItem.obj.style.height = iEndHeight + 'px';
			if (LoginScreen && typeof LoginScreen.changeMode === 'function') {
				LoginScreen.changeMode();
			}
		}
		else {
			oItem.obj.style.height = '0px';
			var oListScreen = WebMail.getCurrentListScreen();
			if (oListScreen && typeof oListScreen.endSlideReplyPane === 'function') {
				oListScreen.endSlideReplyPane(oItem.sDir);
			}
		}
	},

	slideTick: function (sObjName) {
		var oItem = this.aItems[sObjName];
		if (oItem === undefined) {
			return;
		}
		var iElapsed = (new Date()).getTime() - oItem.iStartTime;
		if (iElapsed > this.iSlideAniLen) {
			this._endSlide(oItem);
		}
		else {
			var fProgress = iElapsed / this.iSlideAniLen;
			fProgress = Math.pow(fProgress, 2);
			var iNewHeight = Math.round(fProgress * oItem.iEndHeight);
			if (oItem.sDir === 'hide') {
				iNewHeight = oItem.iEndHeight - iNewHeight;
			}
			oItem.obj.style.height = iNewHeight + 'px';
		}
		if (!oItem.bLogin) {
			var oListScreen = WebMail.getCurrentListScreen();
			if (typeof oListScreen === 'object' && typeof oListScreen.resizeScreen === 'function') {
				oListScreen.resizeScreen(RESIZE_MODE_MSG_HEIGHT);
			}
		}
		this.aItems[sObjName] = oItem;
	}
};

function CBrowser()
{
	this._init = function ()
	{
		var len = this._profiles.length;
		for (var i = 0; i < len; i++) {
			if (this._profiles[i].criterion) {
				this.name = this._profiles[i].id;
				this.version = this._profiles[i].version();
				break;
			}
		}
		this.ie = (this.name == 'ie' && this.version < 9);
		this.ie9 = (this.name == 'ie' && this.version >= 9);
		this.opera = (this.name == 'opera');
		this.mozilla = (this.name == 'mozilla' || this.name == 'firefox' || this.name == 'netscape'
			|| this.name == 'chrome' || this.name == 'safari');
		this.safari = (this.name == 'safari');
		this.chrome = (this.name == 'chrome');
		this.gecko = (this.opera || this.mozilla);

		this.ios = ((navigator.platform.indexOf("iPhone") != -1)
			|| (navigator.platform.indexOf("iPod") != -1)
			|| (navigator.platform.indexOf("iPad") != -1));
	};

	this._profiles = [
		{
			id: 'opera',
			criterion: window.opera,
			version: function () {
				var start, end, r, start1, start2;
				r = navigator.userAgent;
				start1 = r.indexOf('Opera/');
				start2 = r.indexOf('Opera ');
				if (-1 == start1) {
					start = start2 + 6;
					end = r.length;
				} else {
					start = start1 + 6;
					end = r.indexOf(' ');
				}
				r = parseFloat(r.slice(start, end));
				return r;
			}
		},
		{
			id: 'chrome',
			criterion:
			(
				(navigator.appCodeName.toLowerCase() == 'mozilla') &&
				(navigator.appName.toLowerCase() == 'netscape') &&
				(navigator.product.toLowerCase() == 'gecko') &&
				(navigator.userAgent.toLowerCase().indexOf('chrome') != -1)
			),
			version: function () {
				return parseFloat(navigator.userAgent.split('Chrome/').reverse().join('Chrome/'));
			}
		},
		{
			id: 'safari',
			criterion:
			(
				(navigator.appCodeName.toLowerCase() == 'mozilla') &&
				(navigator.appName.toLowerCase() == 'netscape') &&
				(navigator.product.toLowerCase() == 'gecko') &&
				(navigator.userAgent.toLowerCase().indexOf('safari') != -1)
			),
			version: function () {
				var r = navigator.userAgent;
				return parseFloat(r.split('Version/').reverse().join(' '));
			}
		},
		{
			id: 'firefox',
			criterion:
			(
				(navigator.appCodeName.toLowerCase() == 'mozilla') &&
				(navigator.appName.toLowerCase() == 'netscape') &&
				(navigator.product.toLowerCase() == 'gecko') &&
				((navigator.userAgent.toLowerCase().indexOf('firefox') != -1) ||
				(navigator.userAgent.toLowerCase().indexOf('iceweasel') != -1))
			),
			version: function () {
				var userAgent = navigator.userAgent.toLowerCase();
				if (userAgent.indexOf('firefox/') != -1) {
					return parseFloat(userAgent.split('firefox/').reverse().join('firefox/'));
				}
				if (userAgent.indexOf('iceweasel/') != -1) {
					return parseFloat(userAgent.split('iceweasel/').reverse().join('iceweasel/'));
				}
				return 0;
			}
		},
		{
			id: 'netscape',
			criterion:
			(
				(navigator.appCodeName.toLowerCase() == 'mozilla') &&
				(navigator.appName.toLowerCase() == 'netscape') &&
				(navigator.product.toLowerCase() == 'gecko') &&
				(navigator.userAgent.toLowerCase().indexOf('netscape') != -1)
			),
			version: function () {
				var r = navigator.userAgent.split(' ').reverse().join(' ');
				r = parseFloat(r.slice(r.indexOf('/') + 1, r.indexOf(' ')));
				return r;
			}
		},
		{
			id: 'mozilla',
			criterion:
			(
				(navigator.appCodeName.toLowerCase() == 'mozilla') &&
				(navigator.appName.toLowerCase() == 'netscape') &&
				(navigator.product.toLowerCase() == 'gecko') &&
				(navigator.userAgent.toLowerCase().indexOf('mozilla') != -1)
			),
			version: function () {
				var r = navigator.userAgent;
				return parseFloat(r.split('Firefox/').reverse().join('Firefox/'));
			}
		},
		{
			id: 'ie',
			criterion:
			(
				(navigator.appName.toLowerCase() == 'microsoft internet explorer') &&
				(navigator.appVersion.toLowerCase().indexOf('msie') !== 0) &&
				(navigator.userAgent.toLowerCase().indexOf('msie') !== 0) &&
				(!window.opera)
			),
			version: function () {
				var r = navigator.userAgent.toLowerCase();
				r = parseFloat(r.slice(r.indexOf('msie') + 4, r.indexOf(';', r.indexOf('msie') + 4)));
				return r;
			}
		}
	];

	this._init();
}

function CInfoContainer(fadeEffect)
{
	this._infoMessage = null;
	this._infoObj = null;
	this._infoVisible = true;
	this._infoShown = false;
	this._errorObj = null;
	this._reportObj = null;
	this._cont = null;
	this._build(fadeEffect);
}

CInfoContainer.prototype = {
	resize: function ()
	{
		this._errorObj.resize();
		this._reportObj.resize();
		this._infoObj.resize();
	},

	unvisible: function()
	{
		this._errorObj.unvisible();
		this._errorObj.hide();
		this._reportObj.unvisible();
		this._reportObj.hide();
		this._infoVisible = false;
	},

	visible: function()
	{
		this._errorObj.visible();
		if (!this._errorObj.shown) {
			this._reportObj.visible();
			this._infoVisible = true;
			if (this._infoShown) {
				this._infoObj.show();
				this._infoObj.resize();
			}
		}
	},

	showInfo: function(info)
	{
		this._infoMessage.innerHTML = info;
		if (this._infoVisible) {
			this._infoObj.show();
			this._infoObj.resize();
		}
		this._infoShown = true;
		this.hideReport();
	},

	hideInfo: function()
	{
		this._infoObj.hide();
		this._infoShown = false;
	},

	showError: function(errorDesc)
	{
		this._errorObj.show(errorDesc);
		this._reportObj.unvisible();
		this._infoVisible = false;
	},

	hideError: function()
	{
		this._errorObj.hide();
		this._reportObj.visible();
		if (!this._reportObj.shown) {
			this._infoVisible = true;
			if (this._infoShown) {
				this._infoObj.show();
				this._infoObj.resize();
			}
		}
	},

	showReport: function(report, priorDelay)
	{
		this._reportObj.show(report, priorDelay);
		this.hideInfo();
	},

	hideReport: function()
	{
		this._reportObj.hide();
	},

	_build: function (fadeEffect)
	{
		var tbl = document.getElementById('info_cont');
		this._infoMessage = document.getElementById('info_message');
		this._infoObj = new CInformation(tbl, 'wm_information wm_status_information');

		this._errorObj = new CReport('WebMail.hideError();', 10000, 'wm_information wm_error_information', true);
		this._errorObj.build();

		this._reportObj = new CReport('WebMail.hideReport();', 5000, 'wm_information wm_report_information', false);
		this._reportObj.build();

		if (!Browser.ie) {
			this._errorObj.setFade(fadeEffect);
			this._reportObj.setFade(fadeEffect);
		}
	}
};

var WindowOpener = {
	_iDefaultRatio: 0.8,
	_aOpenedWins: [],

	open: function (sUrl, sPopupName, bMenubar)
	{
		sPopupName = sPopupName.replace(/\W/g, '');//forbidden characters in the name of the window for ie
		var sMenubar = (bMenubar) ? ',menubar=yes' : ',menubar=no';
		var sParams = 'location=no,toolbar=no,status=no,scrollbars=yes,resizable=yes' + sMenubar;
		sParams += this._getSizeParameters();
		var oWin = window.open(sUrl, sPopupName, sParams);
		oWin.focus();
		this._aOpenedWins.push(oWin);
	},

	closeAll: function ()
	{
		var iCount = this._aOpenedWins.length;
		for (var i = 0; i < iCount; i++) {
			var oWin = this._aOpenedWins[i];
			if (!oWin.closed) {
				oWin.close();
			}
		}
		this._aOpenedWins = [];
	},

	_getSizeParameters: function ()
	{
		var iScreenWidth = window.screen.width;
		var iWidth = WebMail.Settings.getMiniWindowWidth();
		if (iWidth === 0) {
			iWidth = Math.ceil(iScreenWidth * this._iDefaultRatio);
		}
		var iLeft = Math.ceil((iScreenWidth - iWidth) / 2);

		var iScreenHeight = window.screen.height;
		var iHeight = WebMail.Settings.getMiniWindowHeight();
		if (iHeight === 0) {
			iHeight = Math.ceil(iScreenHeight * this._iDefaultRatio);
		}
		var iTop = Math.ceil((iScreenHeight - iHeight) / 2);

		return ',width=' + iWidth + ',height=' + iHeight + ',top=' + iTop + ',left=' + iLeft;
	}
};

var Validator = {
    isEmpty: function (strValue)
    {
		return (strValue.replace(/\s+/g, '') == '');
    },

    hasEmailForbiddenSymbols: function (strValue)
    {
		return (strValue.match(/[^A-Z0-9\"!#\$%\^\{\}`~&'\+\-=_@\.]/i));
    },

    isCorrectEmail: function (strValue)
    {
		return (strValue.match(/^[A-Z0-9\"!#\$%\^\{\}`~&'\+\-=_\.]+@[A-Z0-9\.\-]+$/i));
    },

    isCorrectServerName: function (strValue)
    {
		return (!strValue.match(/[^A-Z0-9\.\-\:\/]/i));
    },

    isPositiveNumber: function (intValue)
    {
        if (isNaN(intValue) || intValue <= 0 || Math.round(intValue) != intValue) {
            return false;
        }
        return true;
    },

    correctNumber: function (value, minValue, maxValue)
    {
        if (isNaN(value) || value <= minValue) {
            return minValue;
        }
        if (maxValue != undefined && value >= maxValue) {
			return maxValue;
		}
        return Math.round(value);
    },

    isPort: function (intValue)
    {
		return (this.isPositiveNumber(intValue) && intValue <= 65535);
    },

    hasSpecSymbols: function (strValue)
    {
		return (strValue.match(/[\/\\*?|:]/));
    },

    isCorrectFileName: function (strValue)
    {
        if (!this.hasSpecSymbols(strValue)) {
			return !strValue.match(/^(CON|AUX|COM1|COM2|COM3|COM4|LPT1|LPT2|LPT3|PRN|NUL)$/i);
        }
        return false;
    },

    correctWebPage: function (strValue)
    {
        return strValue.replace(/^[\/;<=>\[\\#\?]+/g, '');
    },

    hasFileExtention: function (strValue, strExtension)
    {
		return (strValue.substr(strValue.length - strExtension.length - 1, strExtension.length + 1).toLowerCase() == '.' + strExtension.toLowerCase());
    },

	isCorrectFolderNameValue: function (sFolderName)
    {
		if (VALIDATION_FOLDER_NAME_REGEXP)
		{
			return null !== sFolderName.match(VALIDATION_FOLDER_NAME_REGEXP);
		}
		return true;
    },

	isCorrectFolderNameLength: function (sFolderName)
    {
		if (VALIDATION_FOLDER_NAME_LENGTH && 0 < VALIDATION_FOLDER_NAME_LENGTH)
		{
			return VALIDATION_FOLDER_NAME_LENGTH >= sFolderName.length;
		}
		return true;
    }
};

var Keys =
{
	tab: 9,
	enter: 13,
	shift: 16,
	ctrl: 17,
	space: 32,
	pageUp: 33,
	pageDown: 34,
	end: 35,
	home: 36,
	up: 38,
	down: 40,
	del: 46,
	a: 65,
	c: 67,
	n: 78,
	p: 80,
	r: 82,
	s: 83,
	f5: 116,
	comma: 188,
	dot: 190,

	getCodeFromEvent: function (ev)
	{
		var key = -1;
		if (window.event) {
			key = window.event.keyCode;
		}
		else if (ev) {
			key = ev.which;
		}
		return key;
	},

	isTab: function (ev)
	{
		var key = Keys.getCodeFromEvent(ev);
		ev = window.event ? window.event : ev;
		if (!ev.ctrlKey && !ev.shiftKey && !ev.altKey && key === Keys.tab) {
			return true;
		}
		return false;
	}
};

var HeaderInfo = {
//public
	init: function (sBaseTitle) {
		var self = this;
		
		this.sBaseTitle = sBaseTitle;
		
		if (Browser.ie || Browser.ie9) {
			$(document)
				.bind('focusin', function () {self.onFocus();})
				.bind('focusout', function () {self.onBlur();})
			;
		}
		else {
			$(window)
				.bind('focus', function () {self.onFocus();})
				.bind('blur', function () {self.onBlur();})
			;
		}
	},
	
	setNewMessagesCount: function ()
	{
		var
			oListScreen = WebMail.Screens[WebMail.listScreenId],
			iNewMessagesCount = (oListScreen) ? oListScreen.GetNewMsgsCount() : 0
		;
		this.iNewMessagesCount = iNewMessagesCount;
		this.applyData();
	},
	
	applyData: function (sTitleText)
	{
		if (this.bFocused || this.iNewMessagesCount === 0) {
			this.applyCommonData(sTitleText);
		}
		else {
			this.applyNewMessagesCountData();
		}
	},
	
//private
	bFocused: true,
	iNewMessagesCount: 0,
	sBaseTitle: '',
	
	onFocus: function ()
	{
		HtmlEditorField.focused = false;
		if (!this.bFocused) {
			this.bFocused = true;

			if (Browser.chrome) {
				if (this.$ChromeFocusIframe === undefined) {
					this.$ChromeFocusIframe = $('<iframe src="about:blank" class="wm_hide"></iframe>').appendTo(document.body);
				}
				this.$ChromeFocusIframe.attr('src', 'about:blank');
			}

			this.applyData();
		}
	},
	
	onBlur: function ()
	{
		var
			oCalScreen = WebMail.Screens[SCREEN_CALENDAR],
			self = this
		;
		
		if (oCalScreen && oCalScreen.bIsShown) {
			return;
		}
		if (HtmlEditorField.focused !== false) {
			return;
		}
		this.bFocused = false;
		this.setNewMessagesCount();
		
		$(document.body).one('click', function () {self.onClick();});
	},
	
	/**
	 * sometimes title doesn't change in ff and chrome
	 * 
	 * also document.focusin does not work when navigating the page by click the elements 
	 * with onmousedown = function () { return false; }
	 */
	onClick: function ()
	{
		if (this.bFocused) {
			this.applyData();
		}
		else {
			this.onFocus();
		}
	},
	
	applyCommonData: function (sTitleText)
	{
		var
			sTitle = this.sBaseTitle,
			oScreenDesc = Screens[WebMail.ScreenId]
		;
		
		if (oScreenDesc != undefined) {
			sTitle += ' - ' + Lang[oScreenDesc.titleLangField];
		}
		
		if (typeof(sTitleText) == 'string' && sTitleText.length > 0) {
			sTitle += ' - ' + sTitleText;
		}
		
		this.setTitle(sTitle);
		
		if (!Browser.ie) {
			Favicon.change('favicon.ico');
		}
	},
	
	applyNewMessagesCountData: function ()
	{
		var
			sTitle = Lang.TitleNewMessagesCount.replace(/%count%/, this.iNewMessagesCount)
		;
		
		this.setTitle(sTitle);
		
		if (!Browser.ie) {
			Favicon.animate(['favicon-blink.ico', 'favicon.ico'], 1000);
		}
	},
	
	setTitle: function (sTitle)
	{
		document.title = '.';
		document.title = sTitle;
	}
}

var Favicon = {
//public
	change: function(iconURL) {
		this._init();
		clearTimeout(this._loopTimer);
		this._addLink(iconURL);
	},

	animate: function(iconSequence, optionalDelay) {
		this._init();
		this._preloadIcons(iconSequence);
		this.iconSequence = iconSequence;
		this.sequencePause = (optionalDelay) ?  optionalDelay : this._defaultPause;
		Favicon.index = 0;
		Favicon.change(iconSequence[0]);
		this._loopTimer = setInterval(function() {
			Favicon.index = (Favicon.index + 1) % Favicon.iconSequence.length;
			Favicon._addLink(Favicon.iconSequence[Favicon.index], false);
		}, Favicon.sequencePause);
	},

//private
	_defaultPause: 2000,
	_loopTimer: null,
	_iconURL: '',
	_initialized: false,
	_docHead: document.getElementsByTagName('head')[0],

	_init: function ()
	{
		if (this._initialized) return;
		this._initialized = true;
	},

	_preloadIcons: function(iconSequence) {
		var dummyImageForPreloading = document.createElement('img');
		for (var i = 0; i < iconSequence.length; i++) {
			dummyImageForPreloading.src = iconSequence[i];
		}
	},

	_addLink: function(iconURL) {
		this._iconURL = iconURL;
		var link = document.createElement('link');
		link.type = 'image/x-icon';
		link.rel = 'shortcut icon';
		link.href = iconURL;
		this._removeLinkIfExists();
		this._docHead.appendChild(link);
	},

	_removeLinkIfExists: function() {
		var links = this._docHead.getElementsByTagName('link');
		for (var i = 0; i < links.length; i++) {
			var link = links[i];
			if (link.type === 'image/x-icon' && link.rel === 'shortcut icon') {
				this._docHead.removeChild(link);
				return; // Assuming only one match at most.
			}
		}
	}
};

var XmlHelper = {
	getBoolAttributeByName: function (node, sName, bDefaultValue)
	{
		var sDefaultValue = (bDefaultValue) ? 'true' : 'false';
		var sAttr = this.getAttributeByName(node, sName, sDefaultValue);
		var bAttr = (sAttr === '1' || sAttr === 'true') ? true : false;
		return bAttr;
	},

	getIntAttributeByName: function (node, sName, iDefaultValue)
	{
		var sDefaultValue = iDefaultValue + '';
		var sAttr = this.getAttributeByName(node, sName, sDefaultValue);
		var iAttr = sAttr - 0;
		return iAttr;
	},

	/*
	 * return string
	 */
	getAttributeByName: function (node, sName, sDefaultValue)
	{
		if (node === null) {
			return sDefaultValue;
		}
		var sAttr = node.getAttribute(sName);
		if (typeof(sAttr) === 'string') return sAttr;
		return sDefaultValue;
	},

	getFirstChildNodeByName: function (node, sName)
	{
		if (node === null) {
			return null;
		}
		var aChilds = node.childNodes;
		for (var i = 0; i < aChilds.length; i++) {
			if (aChilds[i].tagName === sName) {
				return aChilds[i];
			}
		}
		return null;
	},

	getFirstChildValue: function (node, sDefaultValue)
	{
		if (node === null) {
			return sDefaultValue;
		}
		if (node.childNodes.length > 0) {
			return node.childNodes[0].nodeValue;
		}
		return sDefaultValue;
	}
};

var ButtonsBuilder = {
	addStandard: function (container, langField, clickFunc, rightIndent)
	{
		var inp = CreateChild(container, 'input', [['type', 'button'], ['class', 'wm_button'],
			['value', Lang[langField]]]);
		WebMail.langChanger.register('value', inp, langField, '');
		inp.onclick = clickFunc;
		if (rightIndent) {
			var span = CreateChild(container, 'span');
			span.innerHTML = '&nbsp;';
		}
		return inp;
	},

	addForQuickReply: function (container, langField, clickFunc)
	{
		var span = CreateChild(container, 'span');
		span.className = 'wm_reply_button wm_control';
		span.onclick = clickFunc;

		var spanCh = CreateChild(span, 'span');
		spanCh.innerHTML = Lang[langField];
		WebMail.langChanger.register('innerHTML', spanCh, langField, '');

		return span;
	}
};

var Cookies = {
//public
	create: function (name, value, days) {
		if (days == undefined) days = COOKIE_STORAGE_DAYS;
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = '; expires=' + date.toGMTString();
		var path = '; path=' + this._getAppPath();
		document.cookie = name + '=' + value + expires + path;
	},

	remove: function (name) {
		this.create(name, '', -1);
	},

	readBool: function (name, defaultValue)
	{
		var strCookie = this._read(name);
		if (strCookie == null) return defaultValue;
		var boolCookie = (strCookie == '1' || strCookie == 'true') ? true : false;
		return boolCookie;
	},

	readInt: function (name, defaultValue) {
		var strCookie = this._read(name);
		if (strCookie == null) return defaultValue;
		var intCookie = parseInt(strCookie, 10);
		if (isNaN(intCookie)) {
			return defaultValue;
		}
		else {
			return intCookie;
		}
	},

	readString: function (sName, sDefault)
	{
		var sCookie = this._read(sName);
		if (sCookie === null) return sDefault;
		return sCookie;
	},

//private
	_read: function (name) {
		var nameEQ = name + '=';
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1, c.length);
			}
			if (c.indexOf(nameEQ) == 0) {
				return c.substring(nameEQ.length, c.length);
			}
		}
		return null;
	},

	_getAppPath: function ()
	{
		var path = location.pathname;
		var dotIndex = path.lastIndexOf('.');
		var delimIndex = path.lastIndexOf('/');
		if (delimIndex < dotIndex || delimIndex == path.length - 1) {
			path = path.substring(0, delimIndex);
		}
		if (path.length == 0) {
			path = '/';
		}
		else if (path.substr(path.length - 1, 1) != '/') {
			path += '/';
		}
		return path;
	}
};

var sCsrfTokenCache = null;
function GetCsrfToken()
{
	if (null === sCsrfTokenCache)
	{
		sCsrfTokenCache = Cookies.readString('awmcsrftoken', '');
	}

	return null === sCsrfTokenCache ? '' : sCsrfTokenCache;
}

	function StringtoXML(text){
		var
			doc = null,
			parser = null
		;
		if (window.ActiveXObject){
			doc = new ActiveXObject('Microsoft.XMLDOM');
			doc.async = 'false';
			doc.loadXML(text);
		}
		else {
			parser = new DOMParser();
			doc = parser.parseFromString(text,'text/xml');
		}

		return doc;
	}

function prefetchData (sXmlResponse)
{
	var
		oMsg = new CMessage(),
		oXmlResponse = StringtoXML(sXmlResponse),
		oWebmail = XmlHelper.getFirstChildNodeByName(oXmlResponse, 'webmail'),
		oMessage = XmlHelper.getFirstChildNodeByName(oWebmail, 'message'),
		sDataKey = ''
	;

	oMsg.getFromXml(oMessage);

	sDataKey = WebMail.DataSource.getStringDataKeyFromObj(TYPE_MESSAGE, oMsg);
	WebMail.DataSource.cache.addData(TYPE_MESSAGE, sDataKey, oMsg)
}

var NewPreFetch = {
	$form: null,
	$data: null,

	getMessagesBodies: function (oMessagesBodies, oFoldersParam)
	{
		var
			sJson = ''
		;
		if (window.UsePrefetch) {
			this._init();

			sJson = oMessagesBodies.getInJson(oFoldersParam);
			if (sJson.length > 0) {
				this.$data.attr('value', sJson);
				this.$form.submit();
			}

			WebMail.requestMessageListNextPage(true);
			WebMail.RequestFoldersMessageList();
		}
	},

	_init: function ()
	{
		if (this.$form === null) {
			$('<iframe id="messages-bodies-frame" name="messages-bodies-frame" src="javascript:void(0)" class="wm_hide"></iframe>').appendTo(document.body);

			this.$form = $('<form target="messages-bodies-frame" name="messages-bodies-form" action="prefetch.php" method="post" class="wm_hide"></form>').appendTo(document.body);

			this.$data = $('<input type="text" name="data" />').appendTo(this.$form);
		}
	}

};

var PreFetch = {
	bStartedMessagesBodies: false,

	_aMsgsBodiesCache: [],
	_iMsgsBodiesCount: 0,
	_iMsgsBodiesLimit: 5,

	initMessagesBodies: function ()
	{
		this._iMsgsBodiesCount = 0;
	},

	hasInCache: function (sCacheKey)
	{
		return (this._aMsgsBodiesCache[sCacheKey] === true);
	},

	enoughMessagesBodies: function (sCacheKey)
	{
		this._iMsgsBodiesCount++;
		if (this._iMsgsBodiesCount > this._iMsgsBodiesLimit) {
			return true;
		}
		this._aMsgsBodiesCache[sCacheKey] = true;
		return false;
	},

	getMessagesBodies: function (oMessagesBodies, oFoldersParam)
	{
		NewPreFetch.getMessagesBodies(oMessagesBodies, oFoldersParam);
		return;

		if (!this.bStartedMessagesBodies) {
			this._oMessagesBodies = oMessagesBodies;
			this._oFoldersParam = oFoldersParam;
		}
		this.bStartedMessagesBodies = false;
		if (this._allowMessagesBodies()) {
			var xml = this._oMessagesBodies.getInXml(this._oFoldersParam);
			if (xml.length > 0) {
				WebMail.DataSource.get(TYPE_MESSAGES_BODIES, {}, [], xml);
				this.bStartedMessagesBodies = true;
			}
		}
		if (this.bStartedMessagesBodies === false) {
			this._aMsgsBodiesCache = [];
		}
	},

	_allowMessagesBodies: function ()
	{
		return (window.UsePrefetch
			&& typeof(this._oMessagesBodies) !== 'undefined'
			&& typeof(this._oFoldersParam) !== 'undefined');
	}
};

var TextFormatter = {
	htmlToPlain: function (sText)
	{
		return HtmlDecode(sText.replace(/<br *\/{0,1}>/gi, '\n').replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' '));
	},

	plainToHtml: function (sText)
	{
		return HtmlEncode(sText).replace(/\n/g, '<br>').replace(/ /g, '&nbsp;');
	},

	removeAllTags: function (sText)
	{
		return sText.replace(/<[^>]*>/g, '');
	}
};

var Statistics = {
	sXml: '',

	add: function (iType, sId)
	{
		var oDate = new Date();
		var iMsDate = oDate - 0;
		var iMsOffset = (oDate.getTimezoneOffset() * 60 * 1000);
		oDate = new Date(iMsDate + iMsOffset);
		var sDate = oDate.toString('yyyy-MM-dd HH:mm:ss');
		this.sXml += '<statistic type="' + iType + '" id="' + sId + '" date="' + sDate + '" />';
	},

	getXml: function ()
	{
		var sXml = '<statistics>' + this.sXml + '</statistics>';
		if (this.sXml === '') {
			sXml = '';
		}
		this.sXml = '';
		return sXml;
	}
}

var Dialog = {
	_eText: null,
	_oDialog: null,
	bOpened: false,

	alert: function (sText, sTitle)
	{
		var aButtons = [
			{
				text: Lang.OK,
				click: function() {$(this).dialog('close');}
			}
		];
		this.open(sText, aButtons, sTitle);
	},

	confirm: function (sText, fOk, fCancel, sOk, sTitle)
	{
		fCancel = fCancel || function () {};
		sOk = sOk || Lang.OK;
		var aButtons = [
			{
				text: sOk,
				click: function() {fOk.call();Dialog.close();}
			},
			{
				text: Lang.Cancel,
				click: function() {fCancel.call();Dialog.close();}
			}
		];
		this.open(sText, aButtons, sTitle);
	},

	open: function (sText, aButtons, sTitle)
	{
		if (sTitle === undefined) {
			sTitle = '';
		}
		this.bOpened = true;
		this._init();
		this._eText.innerHTML = sText;
		this._oDialog.dialog('option', 'buttons', aButtons);
		this._oDialog.dialog({title: sTitle});
		this._oDialog.dialog('open');
	},

	close: function ()
	{
		this._oDialog.dialog('close');
		this.bOpened = false;
	},

	_init: function ()
	{
		if (this._oDialog !== null) {
			return;
		}
		var eDialog = CreateChild(document.body, 'div');
		this._eText = CreateChild(eDialog, 'span');
		this._oDialog = $(eDialog);
		this._oDialog.dialog({
					modal: true,
					autoOpen: false,
					minHeight: 100,
					minWidth: 140,
					position: 'center'
				});
	}
}

var Logger = {
//public
	write: function ()
	{
		this._write(arguments, '; ');
	},

	writeLine: function ()
	{
		this._write(arguments, '<br />');
	},

	clear: function ()
	{
		this._init();
		if (!this._initialized) return;
		this._container.innerHTML = '';
	},

//private
	_container: null,
	_initialized: false,

	_init: function ()
	{
		if (this._initialized == true) {
			return;
		}
		this._container = CreateChild(document.body, 'div');
		this._container.dir = 'ltr';

		var st = this._container.style;

		st.color = 'black';
		st.border = 'solid 2px black';
		st.background = 'white';
		st.width = '700px';
		st.height = '100px';
		st.bottom = '0px';
		st.right = '0px';
		st.position = 'absolute';
		st.zIndex = '10';
		st.textAlign = 'left';
		st.overflow = 'auto';

		this._initialized = true;
	},

	_write: function (args, toAdd)
	{
		if (!Browser.ie && !Browser.opera && window.console && console.log) {
			console.log(args);
		}
		else {
			this._init();
			if (!this._initialized) return;
			var msg = '';
			for (var i = 0; i < args.length; i++) {
				msg += args[i];
				if ((i + 1) < args.length) {
					msg += ', ';
				}
			}
			this._container.innerHTML = this._container.innerHTML + msg + toAdd;
		}
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}
