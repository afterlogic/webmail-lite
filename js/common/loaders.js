/*
 * Classes:
 *  CCheckMail(type)
 *  CDictionary()
 *  CScriptLoader()
 *  CNetLoader(actionUrl, loadHandler, errorHandler)
 *  CLanguageChanger()
 *  CTip()
 */

var CHECK_MAIL_BY_CLICK = 0;
var CHECK_MAIL_AT_LOGIN = 1;

function CCheckMail(type)
{
	this.isBuilded = false;
	this._type = (type) ? type : CHECK_MAIL_BY_CLICK;
	this.started = false;
    this.hidden = false;
	
	this._url = CheckMailUrl;
	this._email = '';
	this._msgsCount = 0;
	this._preText = '';
	
	this._form = null;
	this._typeObj = null;
	
	this._mainContainer = null;
	this._infomation = null;
	this._message = null;
	this._progressBarUsed = null;

	this.ieCheckTimer = null;
	this.ffCheckTimer = null;

	this._checkTimer = null;
}

CCheckMail.prototype = {
	start: function (hide)
	{
        this.hidden = hide || false;
		if (!this.hidden && this._type == CHECK_MAIL_BY_CLICK) {
			WebMail.InfoContainer.unvisible();
		}
		clearTimeout(this.ieCheckTimer);
		clearTimeout(this.ffCheckTimer);
		if (this.started) {
			return;
	    }
		if (this.isBuilded) {
			if (!this.hidden && this._type == CHECK_MAIL_BY_CLICK) {
				this._infomation.show();
			}
		}
		else {
			this.build();
		}
		this._preText = '';
		if (!this.hidden) {
			this.SetText(Lang.LoggingToServer);
			this.UpdateProgressBar(0);
		}
		this._msgsCount = 0;
		this._typeObj.value = (this.hidden) ? 2 : this._type;
		this._form.action = this._url + '?param=' + Math.random();
		this._form.submit();
		this.started = true;
		this._restartCheckTimer();
	},

	_restartCheckTimer: function ()
	{
		clearTimeout(this._checkTimer);
		this._checkTimer = setTimeout('CheckEndCheckMailHandler()', 120000);
	},

	SetAccount: function (account)
	{
		this._email = account;
		this._mainContainer.className = 'wm_connection_info';
		this._preText = '<b>' + this._email + '</b><br/>';
		this._restartCheckTimer();
	},

	SetFolder: function (folderName, msgsCount)
	{
		this.UpdateProgressBar(0);
		this._folderName = folderName;
		this._msgsCount = msgsCount;
		this._preText = '';
		if (this._email.length > 0) {
			this._preText += '<b>' + this._email + '</b><br/>';
		}
		this._preText += Lang.Folder + ' <b>' + this._folderName + '</b><br/>';
		this._restartCheckTimer();
	},
	
	SetText: function (text)
	{
		this._message.innerHTML = this._preText + text;
		if (this._type == CHECK_MAIL_BY_CLICK) {
			this._infomation.resize();
		}
		this._restartCheckTimer();
	},
	
	DeleteMsg: function (msgNumber) {
		if (msgNumber == -1) {
			this.SetText(Lang.DeletingMessages);
		}
		else {
			this.SetText(Lang.DeletingMessage + ' #' + msgNumber + ' ' + Lang.Of + ' ' + this._msgsCount);
			this.UpdateProgressBar(msgNumber);
		}
		this._restartCheckTimer();
	},
	
	SetMsgNumber: function (msgNumber)
	{
		if (msgNumber <= this._msgsCount) {
			this.SetText(Lang.RetrievingMessage + ' #' + msgNumber + ' ' + Lang.Of + ' ' + this._msgsCount);
		}
		this.UpdateProgressBar(msgNumber);
		this._restartCheckTimer();
	},
	
	UpdateProgressBar: function (msgNumber)
	{
		if (this._msgsCount > 0) {
			var percent = Math.ceil((msgNumber - 1) * 100 / this._msgsCount);
			if (percent < 0) { 
				percent = 0; 
			}
			else if (percent > 100) {
				percent = 100;
			}
			this._progressBarUsed.style.width = percent + 'px';
		}
		this._restartCheckTimer();
	},
	
	end: function ()
	{
		clearTimeout(this._checkTimer);
		if (this._type == CHECK_MAIL_BY_CLICK) {
			this._infomation.hide();
		}
		this.started = false;
		if (!this.hidden && this._type == CHECK_MAIL_BY_CLICK) {
			WebMail.InfoContainer.visible();
		}
	},
	
	_buildCheckMailByClick: function ()
	{
		var tbl = CreateChild(document.body, 'table',
			[['class', 'wm_information wm_connection_information'],
			['id', 'info_cont'],
			['cellpadding', '0'],
			['cellspacing', '0']]);

		var tr = CreateChild(tbl, 'tr', [['style', 'position:relative;z-index:20']]);
		CreateChild(tr, 'td', [['class', 'wm_shadow'],
			['style', 'width:2px;font-size:1px;']]);

		var td = CreateChild(tr, 'td');
		var infoDiv = CreateChild(td, 'div', [['class', 'wm_info_message'], ['id', 'info_message']]);
		var aDiv = CreateChild(td, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		var bDiv = CreateChild(td, 'div', [['class', 'b']]);
		bDiv.innerHTML = '&nbsp;';
		this._message = CreateChild(infoDiv, 'span');
		var divPB = CreateChild(infoDiv, 'div', [['class', 'wm_progressbar']]);
		CreateChild(tr, 'td', [['class', 'wm_shadow'],
			['style', 'width:2px;font-size:1px;']]);

		tr = CreateChild(tbl, 'tr');

		td = CreateChild(tr, 'td', [['class', 'wm_shadow'],
			['colspan', '3'],
			['style', 'height:2px;background:none;']]);
		aDiv = CreateChild(td, 'div', [['class', 'a']]);
		aDiv.innerHTML = '&nbsp;';
		bDiv = CreateChild(td, 'div', [['class', 'b']]);
		bDiv.innerHTML = '&nbsp;';

		tr = CreateChild(tbl, 'tr', [['style', 'position:relative;z-index:19']]);
		td = CreateChild(tr, 'td', [['style', 'height:2px;'], ['colspan', '3']]);
		var div = CreateChild(td, 'div', [['class', 'a wm_shadow'],
			['style', 'margin:0px 2px;height:2px; top:-4px; position:relative; border:0px;background:#555;']]);
		div.innerHTML = '&nbsp;';

		this._containerObj = tbl;
		
		this._progressBarUsed = CreateChild(divPB, 'div', [['class', 'wm_progressbar_used']]);
		this._infomation = new CInformation(tbl, 'wm_information wm_connection_information');
		this._infomation.resize();
	},
	
	_buildCheckMailAtLogin: function ()
	{
		var parentCont = document.getElementById('content');
		parentCont = (parentCont) ? parentCont : document.body;
		var div = CreateChild(parentCont, 'div', [['class', 'wm_hide']]);
		this._mainContainer = div;
		
		CreateChild(div, 'div', [['class', 'a top']]);
		CreateChild(div, 'div', [['class', 'b top']]);
		var cont = CreateChild(div, 'div', [['class', 'wm_connection_content']]);
		CreateChild(div, 'div', [['class', 'b']]);
		CreateChild(div, 'div', [['class', 'a']]);
				
		var header = CreateChild(cont, 'div', [['class', 'wm_connection_header']]);
		header.innerHTML = Lang.Connection;
		CreateChild(header, 'span');
		
		var message = CreateChild(cont, 'div', [['class', 'wm_connection_message']]);
		this._message = message;

		var progressbar = CreateChild(cont, 'div', [['class', 'wm_connection_progressbar']]);
		
		var subSpan = CreateChild(progressbar, 'span', [['class', 'wm_progressbar']]);
		this._progressBarUsed = CreateChild(subSpan, 'span', [['class', 'wm_progressbar_used']]);
	},
	
	build: function ()
	{
		var ifrm = $('<iframe id="CheckMailIframe" name="CheckMailIframe" src="javascript:void(0)" class="wm_hide" />').appendTo(document.body);
		
		var obj = this;
		ifrm.bind('readystatechange', function () {
			if (this.started && this.readyState == 'complete') {
				obj.ieCheckTimer = setTimeout('CheckEndCheckMailHandler()', 1000);
			}
		}); // for ie
		ifrm.bind('load', function () {
			if (this.started) {
				obj.ffCheckTimer = setTimeout('CheckEndCheckMailHandler()', 1000);
			}
		}); // for other browsers
		this._form = CreateChild(document.body, 'form', [['action', this._url], ['target', 'CheckMailIframe'], ['method', 'post'], ['id', 'CheckMailForm'], ['name', 'CheckMailForm'], ['class', 'wm_hide']]);
		this._typeObj = CreateChild(this._form, 'input', [['name', 'Type'], ['value', this._type]]);
		
		switch (this._type) {
			case CHECK_MAIL_BY_CLICK:
				this._buildCheckMailByClick();
				break;
			case CHECK_MAIL_AT_LOGIN:
				this._buildCheckMailAtLogin();
				break;
		}
		
		this.isBuilded = true;
		if (this._infomation && this.hidden) {
			this._infomation.hide();
		}
	}
};

function CDictionary()
{
	this.count = 0;
	this._obj = {};
}

CDictionary.prototype = {
	exists: function (sKey)
	{
		return (this._obj[sKey]) ? true : false;
	},

	add: function (sKey, aVal)
	{
		var k = String(sKey);
		if (this.exists(k)) {
			return false;
		}
		this._obj[k] = aVal;
		this.count++;
		return true;
	},

	remove: function (sKey)
	{
		var k = String(sKey);
		if (!this.exists(k)) {
			return false;
		}
		delete this._obj[k];
		this.count--;
		return true;
	},

	removeAll: function ()
	{
		for (var key in this._obj) {
			if (typeof(this._obj[key]) === 'function') continue;
			delete this._obj[key];
		}
		this.count = 0;
	},

	values: function ()
	{
		var arr = [];
		for (var key in this._obj) {
			if (typeof(this._obj[key]) === 'function') continue;
			arr[arr.length] = this._obj[key];
		}
		return arr;
	},

	keys: function ()
	{
		var arr = [];
		for (var key in this._obj) {
			if (typeof(this._obj[key]) === 'function') continue;
			arr[arr.length] = key;
		}
		return arr;
	},

	items: function ()
	{
		var arr = [];
		for (var key in this._obj) {
			if (typeof(this._obj[key]) === 'function') continue;
			var a = [key, this._obj[key]];
			arr[arr.length] = a;
		}
		return arr;
	},

	getVal: function (sKey)
	{
		var k = String(sKey);
		return this._obj[k];
	},

	setVal: function (sKey, aVal)
	{
		var k = String(sKey);
		if (this.exists(k)) {
			this._obj[k] = aVal;
		}
		else {
			this.add(k, aVal);
		}
	},

	setKey: function (sKey, sNewKey)
	{
		var k = String(sKey);
		var nk = String(sNewKey);
		if (this.exists(k)) {
			if (!this.exists(nk)) {
				this.add(nk, this.getVal(k));
				this.remove(k);
			}
		}
		else if (!this.exists(nk)) {
			this.add(nk, null);
		}
	}
};

function CScriptLoader()
{
	this._onArrayLoad = null;
	this._loadedCount = 0;
	this._scriptsCount = 0;
	this._onItemLoad = null;
	this._scripts = new CDictionary();
}

CScriptLoader.prototype = {
	load: function (urlArray, loadHandler)
	{
		this._onArrayLoad = loadHandler;
		this._loadedCount = 0;
		this._scriptsCount = urlArray.length;
		if (this._scriptsCount == 0) {
			this._onArrayLoad.call();
		}
		for (var i = 0; i < urlArray.length; i++) {
			this._loadItem(urlArray[i], this._scriptLoadHandler);
		}
	},
	
	_scriptLoadHandler: function ()
	{
		this._loadedCount++;
		if (this._loadedCount == this._scriptsCount) {
			this._onArrayLoad.call();
		}
	},
	
	_loadItem: function (url, loadHandler)
	{
		this._onItemLoad = loadHandler;
		var script = document.createElement('script');
		script.setAttribute('type', 'text/javascript');
		var obj = this;
		if (Browser.ie) {
			script.onreadystatechange = function ()
			{
			    if (this.readyState == 'complete' || this.readyState == 'loaded') {
			        if (obj._scripts.exists(this.src)) obj._scripts.remove(this.src);
					obj._onItemLoad.call(obj);
				}
			};
		}
		else {
			script.onload = function () {
				obj._scripts.remove(this.src);
				obj._onItemLoad.call(obj);
			};
		}
		this._scripts.add(url, true);
		script.src = url;
		var headElements = document.getElementsByTagName('head');
		if (headElements && headElements.length > 0) {
			headElements[0].appendChild(script);
		}
	}
};

function CNetLoader(actionUrl, loadHandler, errorHandler)
{
	this._actionUrl = actionUrl;
	this._onLoad = loadHandler;
	this._onError = errorHandler;
	this._requests = [];
	this._requestIndex = 0;
	
	this.bShowErrors = true;
}

CNetLoader.prototype = {
	getTransport: function ()
	{
		var transport = null;
		if (window.XMLHttpRequest) {
			transport = new XMLHttpRequest();
		}
		else {
			if (window.ActiveXObject) {
				try {
					transport = new ActiveXObject('Msxml2.XMLHTTP');
				}
				catch (err) {
					try {
						transport = new ActiveXObject('Microsoft.XMLHTTP');
					}
					catch (err2) {
					}
				}
			}
		}
		return transport;
	},

	hasOpenRequests: function ()
	{
		var
			iReadyStateComplete = 4,
			sKey = '',
			oRequestData = null
		;
		for (sKey in this._requests) {
			oRequestData = this._requests[sKey];
			if (typeof(oRequestData) === 'function') continue;
			
			if (oRequestData.request.readyState != iReadyStateComplete) {
				return true;
			}
		}
		return false;
	},

	checkRequests: function ()
	{
		var
			sKey = '',
			oRequestData = null,
			oDate = null,
			iDiff = 0,
			bSendOrSaveMessage = false
		;
		for (sKey in this._requests) {
			oRequestData = this._requests[sKey];
			if (typeof(oRequestData) === 'function') continue;
			bSendOrSaveMessage = (oRequestData.requestWord === 'message' 
				&& (oRequestData.actionWord === 'send' || oRequestData.actionWord === 'save'));
			
			oDate = new Date();
			iDiff = oDate - oRequestData.date;
			if (iDiff > this._timeInterval && !bSendOrSaveMessage) {
				this._abortRequest(sKey);
				this._onError.call({errorDesc: '', request: oRequestData.requestWord,
					action: oRequestData.actionWord});
			}
		}
	},
	
	showErrors: function (bShow)
	{
		this.bShowErrors = bShow;
	},
	
	loadXmlDoc: function (sPostParams, sActionWord, sRequestWord, bBackground)
	{
		var
			self = this,
			oRequest = this.getTransport(),
			iIndex = 0,
			oRequestData = null,
			sErrorDesc = ''
		;
		
		this.bShowErrors = true;
		
		if (typeof bBackground !== 'boolean') {
			bBackground = false;
		}
		
		if (!this._funcInterval) {
			this._timeInterval = 100000;
			this._funcInterval = setInterval(function () {self.checkRequests();}, this._timeInterval);
		}
		
		if (oRequest) {
			try {
				oRequest.open('POST', this._actionUrl, true);
				oRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				iIndex = this._requestIndex++;
				oRequest.onreadystatechange = function () {
					self.onReadyState(iIndex);
				};

				oRequestData = {
					request: oRequest,
					requestWord: sRequestWord,
					actionWord: sActionWord,
					background: bBackground,
					date: new Date()
				};
				this._requests[iIndex] = oRequestData;
				oRequest.send(sPostParams);
			}
			catch (err) {
				sErrorDesc = (this.bShowErrors) ? Lang.ErrorDataTransferFailed : '';
				this._onError.call({errorDesc: sErrorDesc, request: sRequestWord,
					action: sActionWord, background: bBackground});
			}
		}
		else {
			sErrorDesc = (this.bShowErrors) ? Lang.ErrorDataTransferFailed : '';
			this._onError.call({errorDesc: sErrorDesc, request: sRequestWord,
				action: sActionWord, background: bBackground});
		}
	},
	
	onReadyState: function (iIndex)
	{
		var
			oRequestData = this._requests[iIndex],
			oRequest = oRequestData.request,
			iReadyStateComplete = 4,
			iHttpStatus = 0,
			sErrorDesc = ''
		;
		if (oRequest.readyState == iReadyStateComplete) {
			try {
				iHttpStatus = (typeof oRequest.status != 'undefined') ? oRequest.status : 13030;
			}
			catch (err) {
				// 13030 is the custom code to indicate the condition -- in mozilla/FF --
				// when the o object's status and statusText properties are
				// unavailable, and a query attempt throws an exception.
				iHttpStatus = 13030;
			}
			if (iHttpStatus == 200 || iHttpStatus == 0) {
				if (iHttpStatus != 0 || oRequest.getResponseHeader('Content-Type') != null) {
					this._onLoad.call({responseXML: oRequest.responseXML,
						responseText: oRequest.responseText, request: oRequestData.requestWord,
						action: oRequestData.actionWord, background: oRequestData.background});
				}
				else {
					sErrorDesc = (this.bShowErrors) ? Lang.ErrorCantReachServer : '';
					this._onError.call({errorDesc: sErrorDesc, request: oRequestData.requestWord,
						action: oRequestData.actionWord, background: oRequestData.background});
				}
			}
			else {
				sErrorDesc = (this.bShowErrors) ? Lang.ErrorDataTransferFailed : '';
				this._onError.call({errorDesc: sErrorDesc, request: oRequestData.requestWord,
					action: oRequestData.actionWord, background: oRequestData.background});
			}
			
			delete this._requests[iIndex];
		}
	},
	
	abortRequests: function (sActionWord, sRequestWord)
	{
		var
			sKey = '',
			oRequestData = null
		;
		for (sKey in this._requests) {
			oRequestData = this._requests[sKey];
			if (typeof(oRequestData) === 'function') continue;
			
			if (sActionWord !== undefined && sRequestWord !== undefined) {
				if (sActionWord !== oRequestData.actionWord && sRequestWord !== oRequestData.requestWord) {
					continue;
				}
			}
			
			this._abortRequest(sKey);
		}
	},

	_abortRequest: function (sKey)
	{
		var oRequestData = this._requests[sKey];
		oRequestData.request.onreadystatechange = null;
		oRequestData.request.abort();
		delete this._requests[sKey];
	}
};

function CLanguageChanger()
{
	this._innerHTML = Array();
	this._iCount = 0;
	this._value = Array();
	this._vCount = 0;
	this._title = Array();
	this._tCount = 0;
	this._$items = [];
}

CLanguageChanger.prototype = {
	register: function (type, obj, field, end, start, number)
	{
		if (!start) {
			start = '';
		}
		switch (type) {
			default:
			case 'innerHTML':
				if (!number) {
					number = this._iCount;
					this._iCount++;
				}
				this._innerHTML[number] = {elem: obj, field: field, end: end, start: start};
				return number;
			case 'value':
				if (!number) {
					number = this._vCount;
					this._vCount++;
				}
				this._value[number] = {elem: obj, field: field, end: end, start: start};
				return number;
			case 'title':
				if (!number) {
					number = this._tCount;
					this._tCount++;
				}
				this._title[number] = {elem: obj, field: field, end: end, start: start};
				return number;
		}
	},

	/*
	 * oItem fields:
	 *   $elem
	 *   sField
	 *   sType
	 */
	register$: function (oItem)
	{
		this._apply$(oItem);
		this._$items.push(oItem);
	},

	_apply$: function (oItem)
	{
		var
			sValue = Lang[oItem.sField]
		;
		if (typeof oItem.sEnd === 'string') {
			sValue += oItem.sEnd;
		}
		switch (oItem.sType) {
			case 'html':
				oItem.$elem.html(sValue);
				break;
			case 'text':
				oItem.$elem.text(sValue);
				break;
			case 'value':
				oItem.$elem.attr('value', sValue);
				break;
		}
	},

	go: function ()
	{
		var iCount = this._innerHTML.length;
		var i, obj;
		for (i = 0; i < iCount; i++) {
			obj = this._innerHTML[i];
			if (obj && obj.elem) {
				obj.elem.innerHTML = obj.start + Lang[obj.field] + obj.end;
			}
		}

		iCount = this._value.length;
		for (i = 0; i < iCount; i++) {
			obj = this._value[i];
			if (obj && obj.elem) {
				obj.elem.value = Lang[obj.field] + obj.end;
			}
		}

		iCount = this._title.length;
		for (i = 0; i < iCount; i++) {
			obj = this._title[i];
			if (obj && obj.elem) {
				obj.elem.title = Lang[obj.field] + obj.end;
			}
		}

		iCount = this._$items.length;
		for (i = 0; i < iCount; i++) {
			this._apply$(this._$items[i]);
		}
	}
};

function CTip()
{
	this._container = null;
	this._message = null;
	this._base = '';
	this.min_width = 300;

	this._init();
}

CTip.prototype = {
	show: function (text, element, base, elementForFocus)
	{
		if (elementForFocus === undefined) {
			elementForFocus = element;
		}
		elementForFocus.focus();
		this._setMessageText(text);
		this._setCoord(element);
		this._base = base;
		$(this._container).removeClass('wm_hide');
	},

	hide: function (base)
	{
		if (this._base == base || base == '') {
			$(this._container).addClass('wm_hide');
		}
	},

	_setMessageText: function (text)
	{
		this._message.innerHTML = text;
	},

	_setCoord: function (element)
	{
		var bounds = GetBounds(element);
		this._container.style.top = (bounds.Top + bounds.Height / 2 - 16) + 'px';
		if (window.RTL) {
			this._container.style.right = (GetWidth() - bounds.Left + 6) + 'px';
		}
		else {
			this._container.style.left = (bounds.Left + bounds.Width + 6) + 'px';
		}
	},

	_init: function ()
	{
		this._container = CreateChild(document.body, 'table', [['class', 'wm_tip wm_hide']]);
		var tr = this._container.insertRow(0);
		var td = tr.insertCell(0);
		CreateChild(td, 'div', [['class', 'wm_tip_arrow']]);
		CreateChild(td, 'div', [['class', 'wm_tip_icon']]);
		this._message = CreateChild(td, 'div', [['class', 'wm_tip_message']]);
	}
};

if (typeof window.JSFileLoaded != 'undefined') {
	JSFileLoaded();
}